<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Feature Access Library
 *
 * Provides centralized feature access checking from vendor database
 *
 * @package		ERP
 * @subpackage	Libraries
 * @category	Libraries
 * @author		ERP Team
 */
class Feature_access
{
	/**
	 * CodeIgniter instance
	 *
	 * @var	object
	 */
	private $CI;
	
	/**
	 * Current vendor database name
	 *
	 * @var	string
	 */
	private $vendor_database = NULL;
	
	/**
	 * Cached enabled features
	 *
	 * @var	array
	 */
	private $enabled_features_cache = NULL;
	
	/**
	 * Constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('Feature_sync_model');
	}
	
	/**
	 * Set vendor database name
	 *
	 * @param	string	$database_name	Vendor database name
	 * @return	void
	 */
	public function setVendorDatabase($database_name)
	{
		$this->vendor_database = $database_name;
		$this->enabled_features_cache = NULL; // Clear cache when database changes
	}
	
	/**
	 * Check if feature is enabled (checks vendor database)
	 *
	 * @param	string	$feature_slug	Feature slug
	 * @return	bool	TRUE if enabled, FALSE otherwise
	 */
	public function isEnabled($feature_slug)
	{
		if (empty($this->vendor_database))
		{
			// Try to get from current vendor
			if (isset($this->CI->current_vendor) && !empty($this->CI->current_vendor['database_name']))
			{
				$this->vendor_database = $this->CI->current_vendor['database_name'];
			}
			else
			{
				log_message('error', 'Vendor database not set for feature check');
				return FALSE;
			}
		}
		
		// Load enabled features if not cached
		if ($this->enabled_features_cache === NULL)
		{
			$this->enabled_features_cache = $this->getEnabledFeatures();
		}
		
		// Check if feature is in enabled list
		foreach ($this->enabled_features_cache as $feature)
		{
			if ($feature['feature_slug'] === $feature_slug && $feature['is_enabled'] == 1)
			{
				return TRUE;
			}
		}
		
		return FALSE;
	}
	
	/**
	 * Get all enabled features from vendor database
	 *
	 * @return	array	Array of enabled features
	 */
	public function getEnabledFeatures()
	{
		if (empty($this->vendor_database))
		{
			// Try to get from current vendor
			if (isset($this->CI->current_vendor) && !empty($this->CI->current_vendor['database_name']))
			{
				$this->vendor_database = $this->CI->current_vendor['database_name'];
			}
			else
			{
				return array();
			}
		}
		
		return $this->CI->Feature_sync_model->getVendorEnabledFeatures($this->vendor_database);
	}
	
	/**
	 * Enforce feature access (throws exception if not enabled)
	 *
	 * @param	string	$feature_slug	Feature slug
	 * @throws	Exception	If feature not enabled
	 * @return	void
	 */
	public function enforceAccess($feature_slug)
	{
		if (!$this->isEnabled($feature_slug))
		{
			throw new Exception('Feature "' . $feature_slug . '" is not enabled for this vendor.');
		}
	}
	
	/**
	 * Check if subcategory is enabled
	 *
	 * @param	int	$feature_id	Feature ID
	 * @param	string	$subcategory_slug	Subcategory slug
	 * @return	bool	TRUE if enabled, FALSE otherwise
	 */
	public function isSubcategoryEnabled($feature_id, $subcategory_slug)
	{
		if (empty($this->vendor_database))
		{
			// Try to get from current vendor
			if (isset($this->CI->current_vendor) && !empty($this->CI->current_vendor['database_name']))
			{
				$this->vendor_database = $this->CI->current_vendor['database_name'];
			}
			else
			{
				return FALSE;
			}
		}
		
		// Connect to vendor database and check
		$hostname = $this->CI->db->hostname;
		$username = $this->CI->db->username;
		$password = $this->CI->db->password;
		
		$connection = new mysqli($hostname, $username, $password, $this->vendor_database);
		
		if ($connection->connect_error)
		{
			log_message('error', 'Failed to connect to vendor database for subcategory check: ' . $connection->connect_error);
			return FALSE;
		}
		
		$stmt = $connection->prepare("SELECT is_enabled FROM vendor_feature_subcategories WHERE feature_id = ? AND subcategory_slug = ? AND is_enabled = 1 LIMIT 1");
		$stmt->bind_param('is', $feature_id, $subcategory_slug);
		$stmt->execute();
		$result = $stmt->get_result();
		$enabled = $result->num_rows > 0;
		$stmt->close();
		$connection->close();
		
		return $enabled;
	}
}






