<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Add erp_schools.short_code for SMS-friendly unique share URLs.
 */
class Migration_2026_09_11_191500_add_school_short_code
{
	public $name = '2026_09_11_191500_add_school_short_code';

	/**
	 * @param mysqli $mysqli
	 * @param Schema_migrator $migrator
	 * @return array
	 */
	public function up($mysqli, $migrator)
	{
		if (!$migrator->table_exists($mysqli, 'erp_schools')) {
			return array(
				'status' => 'skipped',
				'message' => 'Table erp_schools not present',
			);
		}

		$messages = array();

		if ($migrator->column_exists($mysqli, 'erp_schools', 'short_code')) {
			$messages[] = 'Column short_code already exists';
		} else {
			$after = $migrator->column_exists($mysqli, 'erp_schools', 'private_bookset_token')
				? ' AFTER private_bookset_token'
				: '';
			$sql = "ALTER TABLE erp_schools
				ADD COLUMN short_code VARCHAR(12) NULL DEFAULT NULL
				COMMENT 'Unique short code for storefront /s/{code} share URL'{$after}";
			if (!$mysqli->query($sql)) {
				throw new RuntimeException('ADD short_code failed: ' . $mysqli->error);
			}
			$messages[] = 'Added column short_code';
		}

		if ($migrator->index_exists($mysqli, 'erp_schools', 'uq_erp_schools_short_code')) {
			$messages[] = 'Unique index already exists';
		} else {
			$sql = "ALTER TABLE erp_schools ADD UNIQUE KEY uq_erp_schools_short_code (short_code)";
			if (!$mysqli->query($sql)) {
				throw new RuntimeException('ADD unique key failed: ' . $mysqli->error);
			}
			$messages[] = 'Added unique index uq_erp_schools_short_code';
		}

		return array(
			'status' => 'done',
			'message' => implode('; ', $messages),
		);
	}
}
