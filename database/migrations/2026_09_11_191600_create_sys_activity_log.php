<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Create sys_activity_log for auditing school and other module actions.
 */
class Migration_2026_09_11_191600_create_sys_activity_log
{
	public $name = '2026_09_11_191600_create_sys_activity_log';

	/**
	 * @param mysqli $mysqli
	 * @param Schema_migrator $migrator
	 * @return array
	 */
	public function up($mysqli, $migrator)
	{
		if ($migrator->table_exists($mysqli, 'sys_activity_log')) {
			return array(
				'status' => 'skipped',
				'message' => 'Table sys_activity_log already exists',
			);
		}

		$sql = "CREATE TABLE sys_activity_log (
			id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
			module VARCHAR(64) NOT NULL,
			action VARCHAR(64) NOT NULL,
			action_label VARCHAR(255) NULL,
			actor_id INT NULL,
			actor_name VARCHAR(255) NULL,
			actor_role VARCHAR(64) NULL,
			reference_type VARCHAR(64) NULL,
			reference_id INT NULL,
			reference_label VARCHAR(255) NULL,
			meta_json TEXT NULL,
			ip_address VARCHAR(45) NULL,
			created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			KEY idx_module_action (module, action),
			KEY idx_reference (reference_type, reference_id),
			KEY idx_created_at (created_at)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

		if (!$mysqli->query($sql)) {
			throw new RuntimeException('CREATE sys_activity_log failed: ' . $mysqli->error);
		}

		return array(
			'status' => 'done',
			'message' => 'Created table sys_activity_log',
		);
	}
}
