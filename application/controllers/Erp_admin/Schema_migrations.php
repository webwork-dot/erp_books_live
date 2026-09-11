<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Schema Migrations (Laravel-style multi-tenant runner)
 *
 * URLs:
 *   /erp-admin/schema-migrations
 *   /erp-admin/schema-migrations/run
 *   /erp-admin/schema-migrations/run/{migration_name}
 *
 * CLI:
 *   php index.php Erp_admin/Schema_migrations/status
 *   php index.php Erp_admin/Schema_migrations/run
 *   php index.php Erp_admin/Schema_migrations/run migration_name
 */
require_once APPPATH . 'controllers/Erp_admin/Erp_base.php';

class Schema_migrations extends Erp_base
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('Schema_migrator');
	}

	/**
	 * Allow CLI without admin session.
	 *
	 * @return void
	 */
	protected function checkAuth()
	{
		if ($this->input->is_cli_request()) {
			return;
		}
		parent::checkAuth();
	}

	/**
	 * List migrations + per-database status.
	 *
	 * @return void
	 */
	public function index()
	{
		$this->status();
	}

	/**
	 * Alias for index.
	 *
	 * @return void
	 */
	public function status()
	{
		try {
			$data = $this->schema_migrator->status();
		} catch (Exception $e) {
			$this->output_text('ERROR: ' . $e->getMessage() . "\n");
			return;
		}

		$lines = array();
		$lines[] = 'Schema Migrations';
		$lines[] = 'Master DB: ' . $data['master_db'];
		$lines[] = 'Targets (' . count($data['targets']) . '): ' . implode(', ', $data['targets']);
		$lines[] = str_repeat('-', 72);

		if (empty($data['migrations'])) {
			$lines[] = 'No migration files found in database/migrations/';
		}

		foreach ($data['migrations'] as $migration) {
			$sum = $migration['summary'];
			$lines[] = $migration['migration'];
			$lines[] = sprintf(
				'  summary: done=%d pending=%d failed=%d skipped=%d',
				$sum['done'],
				$sum['pending'],
				$sum['failed'],
				$sum['skipped']
			);
			foreach ($migration['databases'] as $db) {
				$msg = $db['message'] ? (' — ' . $db['message']) : '';
				$lines[] = '  [' . $db['status'] . '] ' . $db['database_name'] . $msg;
			}
			$lines[] = '';
		}

		$lines[] = 'Run all pending: /erp-admin/schema-migrations/run';
		$lines[] = 'CLI: php index.php Erp_admin/Schema_migrations/run';
		$this->output_text(implode("\n", $lines) . "\n");
	}

	/**
	 * Run all pending migrations, or one migration when $name is provided.
	 *
	 * @param string $name
	 * @return void
	 */
	public function run($name = '')
	{
		if ($this->input->is_cli_request() && ($name === '' || $name === NULL)) {
			$args = $this->uri->rsegment_array();
			// php index.php Erp_admin/Schema_migrations/run some_name
			if (isset($args[4]) && $args[4] !== '') {
				$name = $args[4];
			}
		}

		$only = ($name !== '' && $name !== NULL) ? $name : NULL;

		try {
			$report = $this->schema_migrator->run($only);
		} catch (Exception $e) {
			$this->output_text('ERROR: ' . $e->getMessage() . "\n");
			return;
		}

		$lines = array();
		$lines[] = $only ? ('Ran migration: ' . $only) : 'Ran all pending migrations';
		$lines[] = str_repeat('-', 72);

		if (empty($report)) {
			$lines[] = 'Nothing to run.';
		}

		foreach ($report as $row) {
			$lines[] = sprintf(
				'[%s] %s @ %s — %s',
				strtoupper($row['status']),
				$row['migration'],
				$row['database'],
				$row['message']
			);
		}

		$lines[] = '';
		$lines[] = 'Done.';
		$this->output_text(implode("\n", $lines) . "\n");
	}

	/**
	 * @param string $text
	 * @return void
	 */
	protected function output_text($text)
	{
		if (!$this->input->is_cli_request()) {
			header('Content-Type: text/plain; charset=utf-8');
		}
		echo $text;
	}
}
