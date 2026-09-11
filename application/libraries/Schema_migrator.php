<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Multi-tenant schema migrator (Laravel-style).
 *
 * Discovers files in database/migrations/, applies each to erp_master + all
 * erp_clients databases, and records status in erp_master.sys_schema_migrations.
 */
class Schema_migrator
{
	/** @var CI_Controller */
	protected $CI;

	/** @var string */
	protected $migrations_path;

	/** @var string */
	protected $master_db;

	/** @var array */
	protected $db_config;

	public function __construct()
	{
		$this->CI =& get_instance();
		$this->migrations_path = rtrim(FCPATH, '/\\') . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'migrations';
		$this->db_config = array(
			'hostname' => $this->CI->db->hostname,
			'username' => $this->CI->db->username,
			'password' => $this->CI->db->password,
		);
		$this->master_db = !empty($this->CI->db->database) ? $this->CI->db->database : 'erp_master';
	}

	/**
	 * Ensure registry table exists on master.
	 *
	 * @return void
	 */
	public function ensure_registry()
	{
		$mysqli = $this->connect($this->master_db);
		$sql = "CREATE TABLE IF NOT EXISTS sys_schema_migrations (
			id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
			migration VARCHAR(191) NOT NULL,
			database_name VARCHAR(191) NOT NULL,
			status VARCHAR(32) NOT NULL DEFAULT 'pending',
			batch INT NOT NULL DEFAULT 0,
			message TEXT NULL,
			applied_at DATETIME NULL,
			created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			UNIQUE KEY uq_migration_database (migration, database_name),
			KEY idx_status (status),
			KEY idx_batch (batch)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
		if (!$mysqli->query($sql)) {
			throw new RuntimeException('Failed to create sys_schema_migrations: ' . $mysqli->error);
		}
		$mysqli->close();
	}

	/**
	 * Discover migration files ordered by name.
	 *
	 * @return array list of array(name, path, instance)
	 */
	public function discover_migrations()
	{
		$items = array();
		if (!is_dir($this->migrations_path)) {
			return $items;
		}

		$files = glob($this->migrations_path . DIRECTORY_SEPARATOR . '*.php');
		if (!$files) {
			return $items;
		}

		sort($files, SORT_STRING);

		foreach ($files as $path) {
			$basename = basename($path, '.php');
			if ($basename === '' || $basename[0] === '_') {
				continue;
			}

			require_once $path;

			$class = 'Migration_' . $basename;
			if (!class_exists($class, FALSE)) {
				throw new RuntimeException('Migration class not found: ' . $class . ' in ' . $path);
			}

			$instance = new $class();
			$name = isset($instance->name) ? (string) $instance->name : $basename;
			$items[] = array(
				'name' => $name,
				'path' => $path,
				'instance' => $instance,
			);
		}

		return $items;
	}

	/**
	 * Target databases: master + erp_clients.database_name
	 *
	 * @return array
	 */
	public function target_databases()
	{
		$targets = array($this->master_db);
		$mysqli = $this->connect($this->master_db);

		$res = $mysqli->query("SHOW TABLES LIKE 'erp_clients'");
		if ($res && $res->num_rows > 0) {
			$clients = $mysqli->query("SELECT database_name FROM erp_clients WHERE database_name IS NOT NULL AND database_name != ''");
			if ($clients) {
				while ($row = $clients->fetch_assoc()) {
					$db = trim((string) $row['database_name']);
					if ($db !== '' && !in_array($db, $targets, TRUE)) {
						$targets[] = $db;
					}
				}
			}
		}

		$mysqli->close();
		return $targets;
	}

	/**
	 * Status snapshot for UI/CLI.
	 *
	 * @return array
	 */
	public function status()
	{
		$this->ensure_registry();
		$migrations = $this->discover_migrations();
		$targets = $this->target_databases();
		$rows = $this->all_registry_rows();

		$by_key = array();
		foreach ($rows as $row) {
			$by_key[$row['migration'] . '|' . $row['database_name']] = $row;
		}

		$result = array();
		foreach ($migrations as $migration) {
			$name = $migration['name'];
			$entry = array(
				'migration' => $name,
				'databases' => array(),
				'summary' => array('done' => 0, 'pending' => 0, 'failed' => 0, 'skipped' => 0),
			);
			foreach ($targets as $db) {
				$key = $name . '|' . $db;
				$status = isset($by_key[$key]) ? $by_key[$key]['status'] : 'pending';
				$message = isset($by_key[$key]) ? $by_key[$key]['message'] : NULL;
				$entry['databases'][] = array(
					'database_name' => $db,
					'status' => $status,
					'message' => $message,
					'applied_at' => isset($by_key[$key]) ? $by_key[$key]['applied_at'] : NULL,
				);
				if (isset($entry['summary'][$status])) {
					$entry['summary'][$status]++;
				} else {
					$entry['summary']['pending']++;
				}
			}
			$result[] = $entry;
		}

		return array(
			'master_db' => $this->master_db,
			'targets' => $targets,
			'migrations' => $result,
		);
	}

	/**
	 * Run all pending migrations, or one named migration.
	 *
	 * @param string|null $only_migration
	 * @return array run report
	 */
	public function run($only_migration = NULL)
	{
		$this->ensure_registry();
		$migrations = $this->discover_migrations();
		$targets = $this->target_databases();
		$batch = $this->next_batch();
		$report = array();

		foreach ($migrations as $migration) {
			$name = $migration['name'];
			if ($only_migration !== NULL && $only_migration !== '' && $only_migration !== $name) {
				continue;
			}

			foreach ($targets as $db_name) {
				$existing = $this->get_registry_row($name, $db_name);
				if ($existing && in_array($existing['status'], array('done', 'skipped'), TRUE)) {
					$report[] = array(
						'migration' => $name,
						'database' => $db_name,
						'status' => $existing['status'],
						'message' => 'Already ' . $existing['status'] . '; skipped.',
					);
					continue;
				}

				try {
					$mysqli = $this->connect($db_name);
					$result = $migration['instance']->up($mysqli, $this);
					$mysqli->close();

					$status = isset($result['status']) ? $result['status'] : 'done';
					$message = isset($result['message']) ? $result['message'] : '';
					if (!in_array($status, array('done', 'skipped', 'failed'), TRUE)) {
						$status = 'done';
					}

					$this->upsert_registry($name, $db_name, $status, $batch, $message);
					$report[] = array(
						'migration' => $name,
						'database' => $db_name,
						'status' => $status,
						'message' => $message,
					);
				} catch (Exception $e) {
					$message = $e->getMessage();
					$status = (stripos($message, 'Unknown database') !== FALSE) ? 'skipped' : 'failed';
					$this->upsert_registry($name, $db_name, $status, $batch, $message);
					$report[] = array(
						'migration' => $name,
						'database' => $db_name,
						'status' => $status,
						'message' => $message,
					);
				}
			}
		}

		return $report;
	}

	/**
	 * Whether a table exists in the current DB connection.
	 *
	 * @param mysqli $mysqli
	 * @param string $table
	 * @return bool
	 */
	public function table_exists($mysqli, $table)
	{
		$table = $mysqli->real_escape_string($table);
		$res = $mysqli->query("SHOW TABLES LIKE '{$table}'");
		return $res && $res->num_rows > 0;
	}

	/**
	 * Whether a column exists on a table.
	 *
	 * @param mysqli $mysqli
	 * @param string $table
	 * @param string $column
	 * @return bool
	 */
	public function column_exists($mysqli, $table, $column)
	{
		$table = $mysqli->real_escape_string($table);
		$column = $mysqli->real_escape_string($column);
		$res = $mysqli->query("SHOW COLUMNS FROM `{$table}` LIKE '{$column}'");
		return $res && $res->num_rows > 0;
	}

	/**
	 * Whether an index exists on a table.
	 *
	 * @param mysqli $mysqli
	 * @param string $table
	 * @param string $index
	 * @return bool
	 */
	public function index_exists($mysqli, $table, $index)
	{
		$table = $mysqli->real_escape_string($table);
		$index = $mysqli->real_escape_string($index);
		$res = $mysqli->query("SHOW INDEX FROM `{$table}` WHERE Key_name = '{$index}'");
		return $res && $res->num_rows > 0;
	}

	/**
	 * @param string $database
	 * @return mysqli
	 */
	protected function connect($database)
	{
		$mysqli = @new mysqli(
			$this->db_config['hostname'],
			$this->db_config['username'],
			$this->db_config['password'],
			$database
		);
		if ($mysqli->connect_errno) {
			throw new RuntimeException('Cannot connect to database ' . $database . ': ' . $mysqli->connect_error);
		}
		$mysqli->set_charset('utf8mb4');
		return $mysqli;
	}

	/**
	 * @return array
	 */
	protected function all_registry_rows()
	{
		$mysqli = $this->connect($this->master_db);
		$rows = array();
		$res = $mysqli->query('SELECT * FROM sys_schema_migrations');
		if ($res) {
			while ($row = $res->fetch_assoc()) {
				$rows[] = $row;
			}
		}
		$mysqli->close();
		return $rows;
	}

	/**
	 * @param string $migration
	 * @param string $database_name
	 * @return array|null
	 */
	protected function get_registry_row($migration, $database_name)
	{
		$mysqli = $this->connect($this->master_db);
		$stmt = $mysqli->prepare('SELECT * FROM sys_schema_migrations WHERE migration = ? AND database_name = ? LIMIT 1');
		$stmt->bind_param('ss', $migration, $database_name);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result ? $result->fetch_assoc() : NULL;
		$stmt->close();
		$mysqli->close();
		return $row ?: NULL;
	}

	/**
	 * @param string $migration
	 * @param string $database_name
	 * @param string $status
	 * @param int $batch
	 * @param string $message
	 * @return void
	 */
	protected function upsert_registry($migration, $database_name, $status, $batch, $message)
	{
		$mysqli = $this->connect($this->master_db);
		$applied_at = in_array($status, array('done', 'skipped'), TRUE) ? date('Y-m-d H:i:s') : NULL;

		if ($applied_at === NULL) {
			$sql = "INSERT INTO sys_schema_migrations
				(migration, database_name, status, batch, message, applied_at, created_at)
				VALUES (?, ?, ?, ?, ?, NULL, NOW())
				ON DUPLICATE KEY UPDATE
					status = VALUES(status),
					batch = VALUES(batch),
					message = VALUES(message),
					applied_at = NULL";
			$stmt = $mysqli->prepare($sql);
			$stmt->bind_param('sssis', $migration, $database_name, $status, $batch, $message);
		} else {
			$sql = "INSERT INTO sys_schema_migrations
				(migration, database_name, status, batch, message, applied_at, created_at)
				VALUES (?, ?, ?, ?, ?, ?, NOW())
				ON DUPLICATE KEY UPDATE
					status = VALUES(status),
					batch = VALUES(batch),
					message = VALUES(message),
					applied_at = VALUES(applied_at)";
			$stmt = $mysqli->prepare($sql);
			$stmt->bind_param('sssiss', $migration, $database_name, $status, $batch, $message, $applied_at);
		}

		if (!$stmt->execute()) {
			$err = $stmt->error;
			$stmt->close();
			$mysqli->close();
			throw new RuntimeException('Failed to update migration registry: ' . $err);
		}
		$stmt->close();
		$mysqli->close();
	}

	/**
	 * @return int
	 */
	protected function next_batch()
	{
		$mysqli = $this->connect($this->master_db);
		$batch = 1;
		$res = $mysqli->query('SELECT MAX(batch) AS max_batch FROM sys_schema_migrations');
		if ($res && ($row = $res->fetch_assoc())) {
			$batch = ((int) $row['max_batch']) + 1;
		}
		$mysqli->close();
		return $batch;
	}
}
