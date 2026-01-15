<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Feature Sync Model
 *
 * Handles syncing feature assignments from master database to vendor databases
 *
 * @package		ERP
 * @subpackage	Models
 * @category	Models
 * @author		ERP Team
 */
class Feature_sync_model extends CI_Model
{
	/**
	 * Constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
		// Use master database for operations
		$this->load->database('default', TRUE);
		$this->load->model('Erp_feature_model');
	}
	
	/**
	 * Sync all features for a vendor from master to vendor database
	 *
	 * @param	int	$vendor_id	Vendor ID
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function syncVendorFeatures($vendor_id)
	{
		// Get vendor information
		$this->load->model('Erp_client_model');
		$vendor = $this->Erp_client_model->getClientById($vendor_id);
		
		if (!$vendor || empty($vendor['database_name']))
		{
			log_message('error', 'Vendor not found or database name missing for vendor ID: ' . $vendor_id);
			return FALSE;
		}
		
		// Get all feature assignments for this vendor from master database
		$vendor_features = $this->Erp_client_model->getClientFeatures($vendor_id);
		$vendor_subcategories = $this->Erp_client_model->getClientSubcategories($vendor_id);
		
		log_message('info', 'Starting feature sync for vendor ID: ' . $vendor_id . '. Found ' . count($vendor_features) . ' features and ' . count($vendor_subcategories) . ' subcategories in master database.');
		
		// Connect to vendor database
		$vendor_db = $this->connectToVendorDatabase($vendor['database_name']);
		if (!$vendor_db)
		{
			log_message('error', 'Failed to connect to vendor database: ' . $vendor['database_name']);
			return FALSE;
		}
		
		// Ensure vendor_features table exists
		$table_check = $vendor_db->query("SHOW TABLES LIKE 'vendor_features'");
		if (!$table_check || $table_check->num_rows == 0)
		{
			log_message('error', 'vendor_features table does not exist in vendor database: ' . $vendor['database_name']);
			$vendor_db->close();
			return FALSE;
		}
		
		$success = TRUE;
		$synced_count = 0;
		$failed_count = 0;
		
		// Sync all features (including disabled ones to prevent data loss)
		foreach ($vendor_features as $feature)
		{
			// Get full feature details from master database
			$feature_details = $this->Erp_feature_model->getFeatureById($feature['id']);
			if (!$feature_details)
			{
				log_message('warning', 'Feature details not found for feature ID: ' . $feature['id']);
				continue;
			}
			
			// Prepare feature data for sync
			$feature_data = array(
				'feature_id' => $feature['id'],
				'feature_slug' => isset($feature_details['slug']) ? $feature_details['slug'] : (isset($feature['slug']) ? $feature['slug'] : ''),
				'feature_name' => isset($feature_details['name']) ? $feature_details['name'] : (isset($feature['name']) ? $feature['name'] : ''),
				'is_enabled' => isset($feature['is_enabled']) ? (int)$feature['is_enabled'] : 0,
				'has_variations' => isset($feature_details['has_variations']) ? (int)$feature_details['has_variations'] : 0,
				'has_size' => isset($feature_details['has_size']) ? (int)$feature_details['has_size'] : 0,
				'has_colour' => isset($feature_details['has_colour']) ? (int)$feature_details['has_colour'] : 0
			);
			
			if ($this->syncFeatureToDatabase($vendor_db, $feature_data))
			{
				$synced_count++;
			}
			else
			{
				$failed_count++;
				$success = FALSE;
				log_message('error', 'Failed to sync feature ID: ' . $feature['id'] . ' to vendor database: ' . $vendor['database_name']);
			}
		}
		
		// Sync all subcategories (including disabled ones)
		$subcat_synced_count = 0;
		$subcat_failed_count = 0;
		foreach ($vendor_subcategories as $subcategory)
		{
			// Get subcategory details from master database
			$subcat_details = $this->Erp_feature_model->getFeatureById($subcategory['subcategory_id']);
			if (!$subcat_details)
			{
				log_message('warning', 'Subcategory details not found for subcategory ID: ' . $subcategory['subcategory_id']);
				continue;
			}
			
			// Prepare subcategory data for sync
			$subcategory_data = array(
				'feature_id' => isset($subcategory['feature_id']) ? (int)$subcategory['feature_id'] : 0,
				'subcategory_id' => isset($subcategory['subcategory_id']) ? (int)$subcategory['subcategory_id'] : 0,
				'subcategory_slug' => isset($subcat_details['slug']) ? $subcat_details['slug'] : '',
				'subcategory_name' => isset($subcat_details['name']) ? $subcat_details['name'] : '',
				'is_enabled' => isset($subcategory['is_enabled']) ? (int)$subcategory['is_enabled'] : 0
			);
			
			if ($this->syncSubcategoryToDatabase($vendor_db, $subcategory_data))
			{
				$subcat_synced_count++;
			}
			else
			{
				$subcat_failed_count++;
				$success = FALSE;
				log_message('error', 'Failed to sync subcategory ID: ' . $subcategory['subcategory_id'] . ' to vendor database: ' . $vendor['database_name']);
			}
		}
		
		$vendor_db->close();
		
		if ($success)
		{
			log_message('info', 'Successfully synced features for vendor ID: ' . $vendor_id . '. Features: ' . $synced_count . ' synced, ' . $failed_count . ' failed. Subcategories: ' . $subcat_synced_count . ' synced, ' . $subcat_failed_count . ' failed.');
		}
		else
		{
			log_message('error', 'Feature sync completed with errors for vendor ID: ' . $vendor_id . '. Features: ' . $synced_count . ' synced, ' . $failed_count . ' failed. Subcategories: ' . $subcat_synced_count . ' synced, ' . $subcat_failed_count . ' failed.');
		}
		
		return $success;
	}
	
	/**
	 * Sync a single feature assignment
	 *
	 * @param	int	$vendor_id	Vendor ID
	 * @param	int	$feature_id	Feature ID
	 * @param	bool	$enabled	Is feature enabled
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function syncFeature($vendor_id, $feature_id, $enabled)
	{
		// Get vendor information
		$this->load->model('Erp_client_model');
		$vendor = $this->Erp_client_model->getClientById($vendor_id);
		
		if (!$vendor || empty($vendor['database_name']))
		{
			log_message('error', 'Vendor not found or database name missing for vendor ID: ' . $vendor_id);
			return FALSE;
		}
		
		// Get feature details from master database
		$feature = $this->Erp_feature_model->getFeatureById($feature_id);
		if (!$feature)
		{
			log_message('error', 'Feature not found: ' . $feature_id);
			return FALSE;
		}
		
		// Connect to vendor database
		$vendor_db = $this->connectToVendorDatabase($vendor['database_name']);
		if (!$vendor_db)
		{
			return FALSE;
		}
		
		// Prepare feature data
		$feature_data = array(
			'feature_id' => $feature_id,
			'feature_slug' => $feature['slug'],
			'feature_name' => $feature['name'],
			'is_enabled' => $enabled ? 1 : 0,
			'has_variations' => isset($feature['has_variations']) ? (int)$feature['has_variations'] : 0,
			'has_size' => isset($feature['has_size']) ? (int)$feature['has_size'] : 0,
			'has_colour' => isset($feature['has_colour']) ? (int)$feature['has_colour'] : 0
		);
		
		$success = $this->syncFeatureToDatabase($vendor_db, $feature_data);
		
		$vendor_db->close();
		
		return $success;
	}
	
	/**
	 * Sync subcategory assignment
	 *
	 * @param	int	$vendor_id	Vendor ID
	 * @param	int	$feature_id	Feature ID
	 * @param	int	$subcategory_id	Subcategory ID
	 * @param	bool	$enabled	Is subcategory enabled
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function syncSubcategory($vendor_id, $feature_id, $subcategory_id, $enabled)
	{
		// Get vendor information
		$this->load->model('Erp_client_model');
		$vendor = $this->Erp_client_model->getClientById($vendor_id);
		
		if (!$vendor || empty($vendor['database_name']))
		{
			log_message('error', 'Vendor not found or database name missing for vendor ID: ' . $vendor_id);
			return FALSE;
		}
		
		// Get subcategory details from master database
		$subcategory = $this->Erp_feature_model->getFeatureById($subcategory_id);
		if (!$subcategory)
		{
			log_message('error', 'Subcategory not found: ' . $subcategory_id);
			return FALSE;
		}
		
		// Connect to vendor database
		$vendor_db = $this->connectToVendorDatabase($vendor['database_name']);
		if (!$vendor_db)
		{
			return FALSE;
		}
		
		// Prepare subcategory data
		$subcategory_data = array(
			'feature_id' => $feature_id,
			'subcategory_id' => $subcategory_id,
			'subcategory_slug' => isset($subcategory['slug']) ? $subcategory['slug'] : '',
			'subcategory_name' => isset($subcategory['name']) ? $subcategory['name'] : '',
			'is_enabled' => $enabled ? 1 : 0
		);
		
		$success = $this->syncSubcategoryToDatabase($vendor_db, $subcategory_data);
		
		$vendor_db->close();
		
		return $success;
	}
	
	/**
	 * Get enabled features from vendor database
	 *
	 * @param	string	$database_name	Vendor database name
	 * @return	array	Array of enabled features
	 */
	public function getVendorEnabledFeatures($database_name)
	{
		$vendor_db = $this->connectToVendorDatabase($database_name);
		if (!$vendor_db)
		{
			return array();
		}
		
		$features = array();
		$query = $vendor_db->query("SELECT * FROM vendor_features WHERE is_enabled = 1");
		
		if ($query)
		{
			while ($row = $query->fetch_assoc())
			{
				$features[] = $row;
			}
		}
		
		$vendor_db->close();
		return $features;
	}
	
	/**
	 * Connect to vendor database
	 *
	 * @param	string	$database_name	Database name
	 * @return	mysqli|FALSE	Database connection or FALSE on failure
	 */
	private function connectToVendorDatabase($database_name)
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
		
		return $connection;
	}
	
	/**
	 * Ensure vendor_features table has required columns
	 *
	 * @param	mysqli	$vendor_db	Vendor database connection
	 * @return	void
	 */
	private function ensureVendorFeaturesColumns($vendor_db)
	{
		// Check and add has_variations column
		$check = $vendor_db->query("SHOW COLUMNS FROM vendor_features LIKE 'has_variations'");
		if ($check->num_rows == 0)
		{
			$vendor_db->query("ALTER TABLE vendor_features ADD COLUMN has_variations TINYINT(1) NOT NULL DEFAULT 0 AFTER is_enabled");
		}
		
		// Check and add has_size column
		$check = $vendor_db->query("SHOW COLUMNS FROM vendor_features LIKE 'has_size'");
		if ($check->num_rows == 0)
		{
			$vendor_db->query("ALTER TABLE vendor_features ADD COLUMN has_size TINYINT(1) NOT NULL DEFAULT 0 AFTER has_variations");
		}
		
		// Check and add has_colour column
		$check = $vendor_db->query("SHOW COLUMNS FROM vendor_features LIKE 'has_colour'");
		if ($check->num_rows == 0)
		{
			$vendor_db->query("ALTER TABLE vendor_features ADD COLUMN has_colour TINYINT(1) NOT NULL DEFAULT 0 AFTER has_size");
		}
	}
	
	/**
	 * Sync feature to vendor database
	 *
	 * @param	mysqli	$vendor_db	Vendor database connection
	 * @param	array	$feature	Feature data
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	private function syncFeatureToDatabase($vendor_db, $feature)
	{
		$feature_id = isset($feature['feature_id']) ? (int)$feature['feature_id'] : (isset($feature['id']) ? (int)$feature['id'] : 0);
		$feature_slug = isset($feature['feature_slug']) ? $feature['feature_slug'] : (isset($feature['slug']) ? $feature['slug'] : '');
		$feature_name = isset($feature['feature_name']) ? $feature['feature_name'] : (isset($feature['name']) ? $feature['name'] : '');
		$is_enabled = isset($feature['is_enabled']) ? (int)$feature['is_enabled'] : 0;
		$has_variations = isset($feature['has_variations']) ? (int)$feature['has_variations'] : 0;
		$has_size = isset($feature['has_size']) ? (int)$feature['has_size'] : 0;
		$has_colour = isset($feature['has_colour']) ? (int)$feature['has_colour'] : 0;
		
		// Ensure columns exist in vendor_features table
		$this->ensureVendorFeaturesColumns($vendor_db);
		
		// Validate required fields
		if (empty($feature_id) || empty($feature_slug) || empty($feature_name))
		{
			log_message('error', 'Invalid feature data for sync. Feature ID: ' . $feature_id . ', Slug: ' . $feature_slug . ', Name: ' . $feature_name);
			return FALSE;
		}
		
		$sql = "INSERT INTO vendor_features (feature_id, feature_slug, feature_name, is_enabled, has_variations, has_size, has_colour, synced_at) 
				VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
				ON DUPLICATE KEY UPDATE 
					feature_slug = VALUES(feature_slug),
					feature_name = VALUES(feature_name),
					is_enabled = VALUES(is_enabled),
					has_variations = VALUES(has_variations),
					has_size = VALUES(has_size),
					has_colour = VALUES(has_colour),
					synced_at = NOW()";
		
		$stmt = $vendor_db->prepare($sql);
		if (!$stmt)
		{
			log_message('error', 'Failed to prepare statement for feature sync: ' . $vendor_db->error . '. SQL: ' . $sql);
			return FALSE;
		}
		
		$stmt->bind_param('issiiii', $feature_id, $feature_slug, $feature_name, $is_enabled, $has_variations, $has_size, $has_colour);
		$result = $stmt->execute();
		
		if (!$result)
		{
			log_message('error', 'Failed to execute feature sync statement: ' . $stmt->error . '. Feature ID: ' . $feature_id);
			$stmt->close();
			return FALSE;
		}
		
		$stmt->close();
		return TRUE;
	}
	
	/**
	 * Sync subcategory to vendor database
	 *
	 * @param	mysqli	$vendor_db	Vendor database connection
	 * @param	array	$subcategory	Subcategory data
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	private function syncSubcategoryToDatabase($vendor_db, $subcategory)
	{
		$feature_id = isset($subcategory['feature_id']) ? (int)$subcategory['feature_id'] : 0;
		$subcategory_id = isset($subcategory['subcategory_id']) ? (int)$subcategory['subcategory_id'] : (isset($subcategory['id']) ? (int)$subcategory['id'] : 0);
		$subcategory_slug = isset($subcategory['subcategory_slug']) ? $subcategory['subcategory_slug'] : (isset($subcategory['slug']) ? $subcategory['slug'] : '');
		$subcategory_name = isset($subcategory['subcategory_name']) ? $subcategory['subcategory_name'] : (isset($subcategory['name']) ? $subcategory['name'] : '');
		$is_enabled = isset($subcategory['is_enabled']) ? (int)$subcategory['is_enabled'] : 0;
		
		// Validate required fields
		if (empty($feature_id) || empty($subcategory_id) || empty($subcategory_slug) || empty($subcategory_name))
		{
			log_message('error', 'Invalid subcategory data for sync. Feature ID: ' . $feature_id . ', Subcategory ID: ' . $subcategory_id . ', Slug: ' . $subcategory_slug);
			return FALSE;
		}
		
		$sql = "INSERT INTO vendor_feature_subcategories (feature_id, subcategory_id, subcategory_slug, subcategory_name, is_enabled, synced_at) 
				VALUES (?, ?, ?, ?, ?, NOW())
				ON DUPLICATE KEY UPDATE 
					subcategory_slug = VALUES(subcategory_slug),
					subcategory_name = VALUES(subcategory_name),
					is_enabled = VALUES(is_enabled),
					synced_at = NOW()";
		
		$stmt = $vendor_db->prepare($sql);
		if (!$stmt)
		{
			log_message('error', 'Failed to prepare statement for subcategory sync: ' . $vendor_db->error . '. SQL: ' . $sql);
			return FALSE;
		}
		
		$stmt->bind_param('iissi', $feature_id, $subcategory_id, $subcategory_slug, $subcategory_name, $is_enabled);
		$result = $stmt->execute();
		
		if (!$result)
		{
			log_message('error', 'Failed to execute subcategory sync statement: ' . $stmt->error . '. Subcategory ID: ' . $subcategory_id);
			$stmt->close();
			return FALSE;
		}
		
		$stmt->close();
		return TRUE;
	}
	
	/**
	 * Verify and repair feature sync for a vendor
	 * Compares master database with vendor database and syncs missing features
	 *
	 * @param	int	$vendor_id	Vendor ID
	 * @return	array	Array with verification results
	 */
	public function verifyAndRepairSync($vendor_id)
	{
		// Get vendor information
		$this->load->model('Erp_client_model');
		$vendor = $this->Erp_client_model->getClientById($vendor_id);
		
		if (!$vendor || empty($vendor['database_name']))
		{
			return array(
				'success' => FALSE,
				'message' => 'Vendor not found or database name missing'
			);
		}
		
		// Get features from master database
		$master_features = $this->Erp_client_model->getClientFeatures($vendor_id);
		$master_subcategories = $this->Erp_client_model->getClientSubcategories($vendor_id);
		
		// Connect to vendor database
		$vendor_db = $this->connectToVendorDatabase($vendor['database_name']);
		if (!$vendor_db)
		{
			return array(
				'success' => FALSE,
				'message' => 'Failed to connect to vendor database'
			);
		}
		
		// Get features from vendor database
		$vendor_features_query = $vendor_db->query("SELECT * FROM vendor_features");
		$vendor_features_db = array();
		if ($vendor_features_query)
		{
			while ($row = $vendor_features_query->fetch_assoc())
			{
				$vendor_features_db[$row['feature_id']] = $row;
			}
		}
		
		// Get subcategories from vendor database
		$vendor_subcategories_query = $vendor_db->query("SELECT * FROM vendor_feature_subcategories");
		$vendor_subcategories_db = array();
		if ($vendor_subcategories_query)
		{
			while ($row = $vendor_subcategories_query->fetch_assoc())
			{
				$key = $row['feature_id'] . '_' . $row['subcategory_id'];
				$vendor_subcategories_db[$key] = $row;
			}
		}
		
		$missing_features = array();
		$missing_subcategories = array();
		$repair_count = 0;
		
		// Check for missing features
		foreach ($master_features as $master_feature)
		{
			if (!isset($vendor_features_db[$master_feature['id']]))
			{
				$missing_features[] = $master_feature['id'];
				// Repair: sync missing feature
				$feature_details = $this->Erp_feature_model->getFeatureById($master_feature['id']);
				if ($feature_details)
				{
					$feature_data = array(
						'feature_id' => $master_feature['id'],
						'feature_slug' => $feature_details['slug'],
						'feature_name' => $feature_details['name'],
						'is_enabled' => isset($master_feature['is_enabled']) ? (int)$master_feature['is_enabled'] : 0
					);
					if ($this->syncFeatureToDatabase($vendor_db, $feature_data))
					{
						$repair_count++;
					}
				}
			}
		}
		
		// Check for missing subcategories
		foreach ($master_subcategories as $master_subcat)
		{
			$key = $master_subcat['feature_id'] . '_' . $master_subcat['subcategory_id'];
			if (!isset($vendor_subcategories_db[$key]))
			{
				$missing_subcategories[] = $key;
				// Repair: sync missing subcategory
				$subcat_details = $this->Erp_feature_model->getFeatureById($master_subcat['subcategory_id']);
				if ($subcat_details)
				{
					$subcategory_data = array(
						'feature_id' => $master_subcat['feature_id'],
						'subcategory_id' => $master_subcat['subcategory_id'],
						'subcategory_slug' => $subcat_details['slug'],
						'subcategory_name' => $subcat_details['name'],
						'is_enabled' => isset($master_subcat['is_enabled']) ? (int)$master_subcat['is_enabled'] : 0
					);
					if ($this->syncSubcategoryToDatabase($vendor_db, $subcategory_data))
					{
						$repair_count++;
					}
				}
			}
		}
		
		$vendor_db->close();
		
		return array(
			'success' => TRUE,
			'missing_features' => count($missing_features),
			'missing_subcategories' => count($missing_subcategories),
			'repaired' => $repair_count,
			'message' => 'Verification complete. Found ' . count($missing_features) . ' missing features and ' . count($missing_subcategories) . ' missing subcategories. Repaired ' . $repair_count . ' items.'
		);
	}
}

