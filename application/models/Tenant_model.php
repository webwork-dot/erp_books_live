<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Tenant Model
 *
 * Handles database operations for tenant management
 *
 * @package		ERP
 * @subpackage	Models
 * @category	Models
 * @author		ERP Team
 */
class Tenant_model extends CI_Model
{
	/**
	 * Constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
		// Use master database for tenant operations
		$this->load->database('default', TRUE);
	}
	
	/**
	 * Get client by domain
	 *
	 * @param	string	$domain	Domain name
	 * @return	array|NULL	Client data or NULL if not found
	 */
	public function getClientByDomain($domain)
	{
		$this->db->where('domain', $domain);
		$this->db->where('status', 'active');
		$query = $this->db->get('erp_clients');
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		
		return NULL;
	}
	
	/**
	 * Get client by ID
	 *
	 * @param	int	$client_id	Client ID
	 * @return	array|NULL	Client data or NULL if not found
	 */
	public function getClientById($client_id)
	{
		$this->db->where('id', $client_id);
		$this->db->where('status', 'active');
		$query = $this->db->get('erp_clients');
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		
		return NULL;
	}
	
	/**
	 * Get client database name
	 *
	 * @param	int	$client_id	Client ID
	 * @return	string|NULL	Database name or NULL if not found
	 */
	public function getClientDatabase($client_id)
	{
		$this->db->select('database_name');
		$this->db->where('id', $client_id);
		$query = $this->db->get('erp_clients');
		
		if ($query->num_rows() > 0)
		{
			$row = $query->row();
			return $row->database_name;
		}
		
		return NULL;
	}
	
	/**
	 * Get client settings
	 *
	 * @param	int	$client_id	Client ID
	 * @return	array|NULL	Client settings or NULL if not found
	 */
	public function getClientSettings($client_id)
	{
		$this->db->where('client_id', $client_id);
		$query = $this->db->get('erp_client_settings');
		
		if ($query->num_rows() > 0)
		{
			$settings = $query->row_array();
			
			// Decode JSON fields
			if (!empty($settings['sms_credentials']))
			{
				$settings['sms_credentials'] = json_decode($settings['sms_credentials'], TRUE);
			}
			if (!empty($settings['email_smtp_config']))
			{
				$settings['email_smtp_config'] = json_decode($settings['email_smtp_config'], TRUE);
			}
			if (!empty($settings['whatsapp_config']))
			{
				$settings['whatsapp_config'] = json_decode($settings['whatsapp_config'], TRUE);
			}
			if (!empty($settings['firebase_config']))
			{
				$settings['firebase_config'] = json_decode($settings['firebase_config'], TRUE);
			}
			
			return $settings;
		}
		
		return NULL;
	}
	
	/**
	 * Check if feature is enabled for client
	 *
	 * @param	int	$client_id	Client ID
	 * @param	string	$feature_slug	Feature slug
	 * @return	bool	TRUE if enabled, FALSE otherwise
	 */
	public function isFeatureEnabled($client_id, $feature_slug)
	{
		$this->db->select('erp_client_features.is_enabled');
		$this->db->from('erp_client_features');
		$this->db->join('erp_features', 'erp_features.id = erp_client_features.feature_id', 'inner');
		$this->db->where('erp_client_features.client_id', $client_id);
		$this->db->where('erp_features.slug', $feature_slug);
		$this->db->where('erp_features.is_active', 1);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0)
		{
			$row = $query->row();
			return (bool)$row->is_enabled;
		}
		
		return FALSE;
	}
	
	/**
	 * Create client database
	 *
	 * @param	string	$database_name	Database name
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function createClientDatabase($database_name)
	{
		// Get database configuration
		$config = $this->db->dbdriver;
		$hostname = $this->db->hostname;
		$username = $this->db->username;
		$password = $this->db->password;
		
		// Create database connection without selecting a database
		$connection = new mysqli($hostname, $username, $password);
		
		if ($connection->connect_error)
		{
			log_message('error', 'Failed to connect to MySQL: ' . $connection->connect_error);
			return FALSE;
		}
		
		// Create database
		$sql = "CREATE DATABASE IF NOT EXISTS `" . $connection->real_escape_string($database_name) . "` CHARACTER SET utf8 COLLATE utf8_general_ci";
		
		if ($connection->query($sql))
		{
			$connection->close();
			log_message('info', 'Created database: ' . $database_name);
			return TRUE;
		}
		else
		{
			log_message('error', 'Failed to create database: ' . $connection->error);
			$connection->close();
			return FALSE;
		}
	}
	
	/**
	 * Initialize client database with template
	 *
	 * @param	string	$database_name	Database name
	 * @param	string	$template_path	Path to SQL template file
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function initializeClientDatabase($database_name, $template_path = NULL)
	{
		$this->load->config('tenant');
		
		if (!$template_path)
		{
			$template_path = $this->config->item('database_template_path', 'tenant');
		}
		
		if (!file_exists($template_path))
		{
			log_message('error', 'Database template file not found: ' . $template_path);
			return FALSE;
		}
		
		// Read SQL template
		$sql = file_get_contents($template_path);
		
		if (empty($sql))
		{
			log_message('error', 'Database template file is empty: ' . $template_path);
			return FALSE;
		}
		
		// Get database configuration
		$hostname = $this->db->hostname;
		$username = $this->db->username;
		$password = $this->db->password;
		
		// Create database connection
		$connection = new mysqli($hostname, $username, $password, $database_name);
		
		if ($connection->connect_error)
		{
			log_message('error', 'Failed to connect to database: ' . $connection->connect_error);
			return FALSE;
		}
		
		// Replace database name in SQL if needed (for erp_master template)
		// Remove CREATE DATABASE statements (database already exists)
		$sql = preg_replace('/CREATE DATABASE\s+IF\s+NOT\s+EXISTS\s+`[^`]+`[^;]*;/i', '', $sql);
		$sql = preg_replace('/CREATE DATABASE\s+`[^`]+`[^;]*;/i', '', $sql);
		// Replace USE statements
		$sql = preg_replace('/USE\s+`[^`]+`[^;]*;/i', 'USE `' . $database_name . '`;', $sql);
		// Replace database references in comments
		$sql = str_replace('-- Database: `erp_master`', '-- Database: `' . $database_name . '`', $sql);
		
		// Remove INSERT statements, but keep certain essential ones
		// Location data (countries, states, cities) will be copied from master database separately
		// This ensures vendor databases start with essential data but are otherwise empty
		$lines = explode("\n", $sql);
		$filtered_lines = array();
		$in_insert = FALSE;
		$current_table = '';
		
		foreach ($lines as $line)
		{
			// Check if this is the start of an INSERT statement
			if (preg_match('/^\s*INSERT\s+INTO\s+`?([^`\s]+)/i', $line, $matches))
			{
				$table_name = $matches[1];
				// Don't skip INSERT statements for essential tables like erp_user_roles
				// These tables need default values for the system to function
				if ($table_name === 'erp_user_roles') {
					$filtered_lines[] = $line; // Keep this INSERT
					continue;
				}
				$in_insert = TRUE;
				// Skip this line
				continue;
			}
			
			// If we're inside an INSERT statement that's not essential, skip until we hit a semicolon
			if ($in_insert)
			{
				// Check if this is the end of the INSERT statement (ends with ;)
				if (preg_match('/;\s*$/', trim($line)))
				{
					$in_insert = FALSE;
				}
				// Skip this line
				continue;
			}
			
			// Keep all other lines (CREATE TABLE, ALTER TABLE, etc.)
			$filtered_lines[] = $line;
		}
		
		$sql = implode("\n", $filtered_lines);
		
		// Execute SQL template
		$connection->multi_query($sql);
		
		// Clear any remaining results
		while ($connection->next_result())
		{
			if ($result = $connection->store_result())
			{
				$result->free();
			}
		}
		
		if ($connection->error)
		{
			log_message('error', 'Failed to initialize database: ' . $connection->error);
			$connection->close();
			return FALSE;
		}
		
		// Create feature tables
		if (!$this->createFeatureTables($connection))
		{
			log_message('error', 'Failed to create feature tables in database: ' . $database_name);
			$connection->close();
			return FALSE;
		}
		
		// Create feature enforcement (stored procedures, functions)
		if (!$this->createFeatureEnforcement($connection))
		{
			log_message('warning', 'Failed to create feature enforcement in database: ' . $database_name);
			// Don't fail initialization if enforcement fails
		}
		
		// Copy location data from master database (countries, states, cities)
		// This ensures all vendors have the same location reference data
		$this->copyLocationData($connection, $database_name);
		
		// Remove foreign key constraints that reference erp_clients (which doesn't exist in vendor databases)
		$this->removeVendorForeignKeyConstraints($connection);
		
		// Ensure essential tables have default data
		$this->ensureEssentialTableDefaults($connection);
		
		$connection->close();
		log_message('info', 'Initialized database: ' . $database_name);
		return TRUE;
	}
	
	/**
	 * Remove foreign key constraints that reference erp_clients table
	 * These constraints cause errors because erp_clients only exists in master database
	 *
	 * @param	mysqli	$connection	Database connection
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function removeVendorForeignKeyConstraints($connection)
	{
		// List of foreign key constraints to remove (constraint_name => table_name)
		$foreign_keys_to_remove = array(
			'fk_bookset_packages_vendor' => 'erp_bookset_packages',
			'fk_notebooks_vendor' => 'erp_notebooks',
			'fk_schools_vendor' => 'erp_schools',
			'fk_boards_vendor' => 'erp_school_boards',
			'fk_school_branches_vendor' => 'erp_school_branches',
			'fk_size_charts_vendor' => 'erp_size_charts',
			'fk_stationery_vendor' => 'erp_stationery',
			'fk_stationery_brands_vendor' => 'erp_stationery_brands',
			'fk_stationery_categories_vendor' => 'erp_stationery_categories',
			'fk_stationery_colours_vendor' => 'erp_stationery_colours',
			'fk_textbooks_vendor' => 'erp_textbooks',
			'fk_textbook_ages_vendor' => 'erp_textbook_ages',
			'fk_textbook_grades_vendor' => 'erp_textbook_grades',
			'fk_textbook_publishers_vendor' => 'erp_textbook_publishers',
			'fk_textbook_subjects_vendor' => 'erp_textbook_subjects',
			'fk_textbook_types_vendor' => 'erp_textbook_types',
			'fk_uniforms_vendor' => 'erp_uniforms'
		);
		
		$success = TRUE;
		
		foreach ($foreign_keys_to_remove as $constraint_name => $table_name)
		{
			// Check if table exists
			$table_check = $connection->query("SHOW TABLES LIKE '" . $connection->real_escape_string($table_name) . "'");
			if (!$table_check || $table_check->num_rows == 0)
			{
				continue; // Table doesn't exist, skip
			}
			
			// Check if constraint exists
			$constraint_check = $connection->query("
				SELECT CONSTRAINT_NAME 
				FROM information_schema.KEY_COLUMN_USAGE 
				WHERE TABLE_SCHEMA = DATABASE() 
				AND TABLE_NAME = '" . $connection->real_escape_string($table_name) . "' 
				AND CONSTRAINT_NAME = '" . $connection->real_escape_string($constraint_name) . "'
			");
			
			if ($constraint_check && $constraint_check->num_rows > 0)
			{
				// Remove the foreign key constraint
				$sql = "ALTER TABLE `" . $connection->real_escape_string($table_name) . "` 
						DROP FOREIGN KEY `" . $connection->real_escape_string($constraint_name) . "`";
				
				if (!$connection->query($sql))
				{
					log_message('warning', 'Failed to remove foreign key constraint ' . $constraint_name . ' from ' . $table_name . ': ' . $connection->error);
					$success = FALSE;
				}
				else
				{
					log_message('debug', 'Removed foreign key constraint ' . $constraint_name . ' from ' . $table_name);
				}
			}
		}
		
		return $success;
	}
	
	/**
	 * Fix foreign key constraints for existing vendor database
	 * Removes foreign keys that reference erp_clients (which doesn't exist in vendor databases)
	 *
	 * @param	string	$database_name	Vendor database name
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function fixVendorDatabaseConstraints($database_name)
	{
		$hostname = $this->db->hostname;
		$username = $this->db->username;
		$password = $this->db->password;
		
		$connection = new mysqli($hostname, $username, $password, $database_name);
		
		if ($connection->connect_error)
		{
			log_message('error', 'Failed to connect to vendor database: ' . $connection->connect_error);
			return FALSE;
		}
		
		$result = $this->removeVendorForeignKeyConstraints($connection);
		$connection->close();
		
		return $result;
	}
	
	/**
	 * Create feature tables in vendor database
	 *
	 * @param	mysqli	$connection	Database connection
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function createFeatureTables($connection)
	{
		$this->load->config('tenant');
		$feature_tables_path = $this->config->item('feature_tables_path', 'tenant');
		
		// If path is empty, use default
		if (empty($feature_tables_path))
		{
			$feature_tables_path = APPPATH . '../database/feature_tables.sql';
			log_message('info', 'Feature tables path was empty, using default: ' . $feature_tables_path);
		}
		
		if (!file_exists($feature_tables_path))
		{
			log_message('error', 'Feature tables SQL file not found: ' . $feature_tables_path);
			return FALSE;
		}
		
		$sql = file_get_contents($feature_tables_path);
		
		if (empty($sql))
		{
			log_message('error', 'Feature tables SQL file is empty: ' . $feature_tables_path);
			return FALSE;
		}
		
		// Execute feature tables SQL
		$connection->multi_query($sql);
		
		// Clear any remaining results
		while ($connection->next_result())
		{
			if ($result = $connection->store_result())
			{
				$result->free();
			}
		}
		
		if ($connection->error)
		{
			log_message('error', 'Failed to create feature tables: ' . $connection->error);
			return FALSE;
		}
		
		log_message('info', 'Created feature tables in vendor database');
		
		// Ensure new columns exist (for existing databases)
		$this->ensureVendorFeaturesColumns($connection);
		
		return TRUE;
	}
	
	/**
 	 * Ensure vendor_features table has required columns (image, updated_at, has_variations, has_size, has_colour)
	 * This handles existing databases that were created before these columns were added
	 *
	 * @param	mysqli	$connection	Database connection
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function ensureVendorFeaturesColumns($connection)
	{
		// Check if image column exists
		$check_image = $connection->query("SHOW COLUMNS FROM vendor_features LIKE 'image'");
		if (!$check_image || $check_image->num_rows == 0)
		{
			$connection->query("ALTER TABLE vendor_features ADD COLUMN `image` VARCHAR(255) NULL DEFAULT NULL AFTER `feature_name`");
			log_message('info', 'Added image column to vendor_features table');
		}
		
		// Check if updated_at column exists
		$check_updated = $connection->query("SHOW COLUMNS FROM vendor_features LIKE 'updated_at'");
		if (!$check_updated || $check_updated->num_rows == 0)
		{
			$connection->query("ALTER TABLE vendor_features ADD COLUMN `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP AFTER `synced_at`");
			log_message('info', 'Added updated_at column to vendor_features table');
		}
		
		// Check if has_variations column exists
		$check_variations = $connection->query("SHOW COLUMNS FROM vendor_features LIKE 'has_variations'");
		if (!$check_variations || $check_variations->num_rows == 0)
		{
			$connection->query("ALTER TABLE vendor_features ADD COLUMN `has_variations` TINYINT(1) NOT NULL DEFAULT 0 AFTER `is_enabled`");
			log_message('info', 'Added has_variations column to vendor_features table');
		}
		
		// Check if has_size column exists
		$check_size = $connection->query("SHOW COLUMNS FROM vendor_features LIKE 'has_size'");
		if (!$check_size || $check_size->num_rows == 0)
		{
			$connection->query("ALTER TABLE vendor_features ADD COLUMN `has_size` TINYINT(1) NOT NULL DEFAULT 0 AFTER `has_variations`");
			log_message('info', 'Added has_size column to vendor_features table');
		}
		
		// Check if has_colour column exists
		$check_colour = $connection->query("SHOW COLUMNS FROM vendor_features LIKE 'has_colour'");
		if (!$check_colour || $check_colour->num_rows == 0)
		{
			$connection->query("ALTER TABLE vendor_features ADD COLUMN `has_colour` TINYINT(1) NOT NULL DEFAULT 0 AFTER `has_size`");
			log_message('info', 'Added has_colour column to vendor_features table');
		}
		
		// Check if idx_image index exists
		$check_index = $connection->query("SHOW INDEX FROM vendor_features WHERE Key_name = 'idx_image'");
		if (!$check_index || $check_index->num_rows == 0)
		{
			$connection->query("ALTER TABLE vendor_features ADD INDEX `idx_image` (`image`)");
			log_message('info', 'Added idx_image index to vendor_features table');
		}
		
		if ($connection->error)
		{
			log_message('error', 'Failed to ensure vendor_features columns: ' . $connection->error);
			return FALSE;
		}
		
		return TRUE;
	}
	
	/**
	 * Create feature enforcement (stored procedures, functions)
	 *
	 * @param	mysqli	$connection	Database connection
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function createFeatureEnforcement($connection)
	{
		$this->load->config('tenant');
		$feature_enforcement_path = $this->config->item('feature_enforcement_path', 'tenant');
		
		// If path is empty, use default
		if (empty($feature_enforcement_path))
		{
			$feature_enforcement_path = APPPATH . '../database/feature_enforcement.sql';
			log_message('info', 'Feature enforcement path was empty, using default: ' . $feature_enforcement_path);
		}
		
		if (!file_exists($feature_enforcement_path))
		{
			log_message('warning', 'Feature enforcement SQL file not found: ' . $feature_enforcement_path);
			return FALSE;
		}
		
		$sql = file_get_contents($feature_enforcement_path);
		
		if (empty($sql))
		{
			log_message('warning', 'Feature enforcement SQL file is empty: ' . $feature_enforcement_path);
			return FALSE;
		}
		
		// Remove DELIMITER statements (not supported by mysqli)
		// DELIMITER is a MySQL client command, not a SQL statement
		$sql = preg_replace('/DELIMITER\s+\$\$[\r\n]+/i', '', $sql);
		$sql = preg_replace('/DELIMITER\s+;[\r\n]+/i', '', $sql);
		
		// Split SQL by $$ delimiter (stored procedures use $$ as delimiter)
		// Execute each statement separately
		$statements = preg_split('/\$\$/s', $sql);
		
		foreach ($statements as $statement)
		{
			$statement = trim($statement);
			if (empty($statement))
			{
				continue;
			}
			
			// Add semicolon if not present
			if (substr(rtrim($statement), -1) !== ';')
			{
				$statement .= ';';
			}
			
			// Execute each statement
			if (!$connection->query($statement))
			{
				log_message('warning', 'Failed to execute feature enforcement statement: ' . $connection->error . ' | Statement: ' . substr($statement, 0, 100));
				// Continue with next statement even if one fails
			}
		}
		
		// Clear any remaining results
		while ($connection->next_result())
		{
			if ($result = $connection->store_result())
			{
				$result->free();
			}
		}
		
		if ($connection->error)
		{
			log_message('warning', 'Failed to create feature enforcement: ' . $connection->error);
			return FALSE;
		}
		
		log_message('info', 'Created feature enforcement in vendor database');
		return TRUE;
	}
	
	/**
	 * Copy location data (countries, states, cities) from master database
	 *
	 * @param	mysqli	$connection		Database connection to vendor database
	 * @param	string	$database_name	Vendor database name
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	private function copyLocationData($connection, $database_name)
	{
		// Get master database name from database config
		// Tenant_model uses master database connection, so get it from config directly
		$this->load->database('default', TRUE);
		$master_db = $this->db->database;
		
		// Default to 'erp_master' if still empty
		if (empty($master_db))
		{
			$master_db = 'erp_master';
			log_message('info', 'Master database name was empty, using default: ' . $master_db);
		}
		
		log_message('info', 'Copying location data from master database: ' . $master_db . ' to vendor database: ' . $database_name);
		
		// Get database connection details from the default database config
		$hostname = $this->db->hostname;
		$username = $this->db->username;
		$password = $this->db->password;
		
		// Connect to master database
		$master_connection = @new mysqli($hostname, $username, $password, $master_db);
		
		if ($master_connection->connect_error)
		{
			log_message('error', 'Failed to connect to master database for copying location data. Master DB: ' . $master_db . ', Error: ' . $master_connection->connect_error);
			return FALSE;
		}
		
		log_message('info', 'Successfully connected to master database: ' . $master_db);
		
		// Copy tables in order to respect foreign key constraints
		// countries -> states -> cities (cities references states, states references countries)
		$location_tables = array('countries', 'states', 'cities');
		$success_count = 0;
		
		// Disable foreign key checks for the entire copy operation
		// This allows us to delete/truncate tables with foreign key constraints
		$connection->query("SET FOREIGN_KEY_CHECKS = 0");
		
		foreach ($location_tables as $table)
		{
			// Check if table exists in master database
			$check_result = $master_connection->query("SHOW TABLES LIKE '" . $master_connection->real_escape_string($table) . "'");
			if (!$check_result || $check_result->num_rows == 0)
			{
				log_message('warning', 'Table ' . $table . ' does not exist in master database (' . $master_db . '), skipping.');
				continue;
			}
			
			log_message('info', 'Found table ' . $table . ' in master database, copying data...');
			
			// Get all data from master database
			$result = $master_connection->query("SELECT * FROM `" . $master_connection->real_escape_string($table) . "`");
			
			if ($master_connection->error)
			{
				log_message('error', 'Error querying master database table ' . $table . ': ' . $master_connection->error);
				continue;
			}
			
			if ($result && $result->num_rows > 0)
			{
				// Clear existing data in vendor database (in case it was partially inserted)
				// Use DELETE instead of TRUNCATE because TRUNCATE doesn't work with foreign key constraints
				// Foreign key checks are already disabled at the start of the loop
				$connection->query("DELETE FROM `" . $connection->real_escape_string($table) . "`");
				
				// Get column names
				$columns = array();
				$fields = $result->fetch_fields();
				foreach ($fields as $field)
				{
					$columns[] = "`" . $field->name . "`";
				}
				$column_list = implode(', ', $columns);
				
				// Reset result pointer
				$result->data_seek(0);
				
				// Insert data in batches
				$batch_size = 1000;
				$batch = array();
				$inserted = 0;
				
				while ($row = $result->fetch_assoc())
				{
					$values = array();
					foreach ($row as $value)
					{
						if ($value === NULL)
						{
							$values[] = 'NULL';
						}
						else
						{
							$values[] = "'" . $connection->real_escape_string($value) . "'";
						}
					}
					$batch[] = '(' . implode(', ', $values) . ')';
					
					if (count($batch) >= $batch_size)
					{
						$sql = "INSERT INTO `" . $connection->real_escape_string($table) . "` (" . $column_list . ") VALUES " . implode(', ', $batch);
						if ($connection->query($sql))
						{
							$inserted += count($batch);
						}
						else
						{
							log_message('warning', 'Failed to insert batch into ' . $table . ': ' . $connection->error);
						}
						$batch = array();
					}
				}
				
				// Insert remaining rows
				if (!empty($batch))
				{
					$sql = "INSERT INTO `" . $connection->real_escape_string($table) . "` (" . $column_list . ") VALUES " . implode(', ', $batch);
					if ($connection->query($sql))
					{
						$inserted += count($batch);
					}
					else
					{
						log_message('warning', 'Failed to insert final batch into ' . $table . ': ' . $connection->error);
					}
				}
				
				if ($inserted > 0)
				{
					log_message('info', 'Copied ' . $inserted . ' rows from ' . $table . ' to vendor database (' . $database_name . ')');
					$success_count++;
				}
				else
				{
					log_message('warning', 'No rows were inserted into ' . $table . ' table in vendor database (' . $database_name . ')');
				}
			}
			else
			{
				$row_count = $result ? $result->num_rows : 0;
				log_message('warning', 'No data found in master database table: ' . $table . ' (master DB: ' . $master_db . ', rows: ' . $row_count . ')');
			}
		}
		
		// Re-enable foreign key checks after all operations
		$connection->query("SET FOREIGN_KEY_CHECKS = 1");
		
		$master_connection->close();
		
		if ($success_count > 0)
		{
			log_message('info', 'Successfully copied location data from master database (' . $success_count . ' tables)');
			return TRUE;
		}
		else
		{
			log_message('warning', 'No location data was copied from master database');
			return FALSE;
		}
	}
	
	/**
	 * Ensure essential tables have default data
	 *
	 * @param	mysqli	$connection	Database connection
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function ensureEssentialTableDefaults($connection)
	{
		// Check if erp_user_roles table exists
		$table_check = $connection->query("SHOW TABLES LIKE 'erp_user_roles'");
		if (!$table_check || $table_check->num_rows == 0) {
			// Create the erp_user_roles table if it doesn't exist
			$create_table_sql = "
			CREATE TABLE `erp_user_roles` (
			  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			  `name` varchar(100) NOT NULL COMMENT 'Role name',
			  `description` text DEFAULT NULL COMMENT 'Role description',
			  `permissions` text DEFAULT NULL COMMENT 'Permissions (JSON)',
			  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
			  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Vendor role definitions';";

			if (!$connection->query($create_table_sql)) {
				log_message('error', 'Failed to create erp_user_roles table: ' . $connection->error);
				return FALSE;
			}
			log_message('info', 'Created erp_user_roles table');
		}

		// Check if the table has any data
		$count_result = $connection->query("SELECT COUNT(*) as count FROM erp_user_roles");
		if ($count_result) {
			$row = $count_result->fetch_assoc();
			if ($row['count'] == 0) {
				// Insert default vendor role
				$default_role_sql = "INSERT INTO `erp_user_roles` (`name`, `description`, `permissions`, `created_at`, `updated_at`) 
								VALUES ('Vendor Admin', 'Default vendor administrator role', '{}', NOW(), NOW());";
				
				if (!$connection->query($default_role_sql)) {
					log_message('error', 'Failed to insert default role into erp_user_roles: ' . $connection->error);
					return FALSE;
				}
				log_message('info', 'Inserted default role into erp_user_roles table');
			}
		}

		// Create banners table if it doesn't exist
		if (!$this->createBannersTable($connection)) {
			log_message('error', 'Failed to create banners table');
			return FALSE;
		}

		return TRUE;
	}
	
	/**
	 * Create banners table if it doesn't exist
	 *
	 * @param	mysqli	$connection	Database connection
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function createBannersTable($connection)
	{
		// Check if banners table exists
		$table_check = $connection->query("SHOW TABLES LIKE 'banners'");
		if (!$table_check || $table_check->num_rows == 0) {
			// Create the banners table if it doesn't exist
			$create_table_sql = "
			CREATE TABLE `banners` (
			  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			  `vendor_id` INT(11) UNSIGNED NOT NULL,
			  `banner_image` VARCHAR(500) NOT NULL,
			  `alt_text` VARCHAR(255) DEFAULT NULL,
			  `caption` VARCHAR(500) DEFAULT NULL,
			  `is_active` TINYINT(1) DEFAULT 1,
			  `sort_order` INT(11) DEFAULT 0,
			  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			  PRIMARY KEY (`id`),
			  INDEX `idx_vendor_id` (`vendor_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
			
			if (!$connection->query($create_table_sql)) {
				log_message('error', 'Failed to create banners table: ' . $connection->error);
				return FALSE;
			}
			log_message('info', 'Created banners table');
		}
		
		return TRUE;
	}
}

