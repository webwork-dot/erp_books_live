<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Tenant Library
 *
 * Handles tenant resolution and database switching for multi-tenant architecture
 *
 * @package		ERP
 * @subpackage	Libraries
 * @category	Libraries
 * @author		ERP Team
 */
class Tenant
{
	/**
	 * Current tenant data
	 *
	 * @var	array
	 */
	private $current_tenant = NULL;
	
	/**
	 * CodeIgniter instance
	 *
	 * @var	object
	 */
	private $CI;
	
	/**
	 * Constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->config('tenant');
		$this->CI->load->model('Tenant_model');
	}
	
	/**
	 * Resolve tenant by domain
	 *
	 * @param	string	$domain	Domain name
	 * @return	array|NULL	Tenant data or NULL if not found
	 */
	public function resolveByDomain($domain)
	{
		$tenant = $this->CI->Tenant_model->getClientByDomain($domain);
		if ($tenant)
		{
			$this->current_tenant = $tenant;
		}
		return $tenant;
	}
	
	/**
	 * Resolve tenant by subdomain
	 *
	 * @param	string	$subdomain	Subdomain name
	 * @return	array|NULL	Tenant data or NULL if not found
	 */
	public function resolveBySubdomain($subdomain)
	{
		// Convert subdomain to domain format
		$domain = $subdomain . '.' . $this->CI->config->item('base_domain', 'tenant');
		return $this->resolveByDomain($domain);
	}
	
	/**
	 * Resolve tenant by ID
	 *
	 * @param	int	$tenant_id	Tenant ID
	 * @return	array|NULL	Tenant data or NULL if not found
	 */
	public function resolveById($tenant_id)
	{
		$tenant = $this->CI->Tenant_model->getClientById($tenant_id);
		if ($tenant)
		{
			$this->current_tenant = $tenant;
		}
		return $tenant;
	}
	
	/**
	 * Get current tenant
	 *
	 * @return	array|NULL	Current tenant data
	 */
	public function getClient()
	{
		return $this->current_tenant;
	}
	
	/**
	 * Switch database connection to tenant database
	 *
	 * @param	array	$tenant	Tenant data
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function switchDatabase($tenant)
	{
		if (!isset($tenant['database_name']) || empty($tenant['database_name']))
		{
			log_message('error', 'Tenant database name not found for tenant: ' . $tenant['id']);
			return FALSE;
		}
		
		// Check if database connection already exists and try to switch directly
		if (isset($this->CI->db) && is_object($this->CI->db))
		{
			// For mysqli driver, try to directly select the database
			if (property_exists($this->CI->db, 'conn_id') && $this->CI->db->conn_id)
			{
				// Check if it's a mysqli connection
				if (is_object($this->CI->db->conn_id) && get_class($this->CI->db->conn_id) === 'mysqli')
				{
					// Directly select the database using mysqli
					if ($this->CI->db->conn_id->select_db($tenant['database_name']))
					{
						// Update the database property
						$this->CI->db->database = $tenant['database_name'];
						
						// Store current tenant
						$this->current_tenant = $tenant;
						
						// Load feature configuration from vendor database if Feature_access library is available
						if (class_exists('Feature_access'))
						{
							$this->CI->load->library('Feature_access');
							$this->CI->Feature_access->setVendorDatabase($tenant['database_name']);
						}
						
						log_message('debug', 'Switched to tenant database: ' . $tenant['database_name']);
						return TRUE;
					}
				}
			}
		}
		
		// Fallback: Reload database connection with new database name
		// Get default database configuration
		$this->CI->config->load('database', TRUE);
		$all_db_configs = $this->CI->config->item('database');
		$db_config = isset($all_db_configs['default']) ? $all_db_configs['default'] : array();
		
		// Ensure we have all required fields
		if (empty($db_config) || !is_array($db_config))
		{
			log_message('error', 'Failed to load database configuration');
			return FALSE;
		}
		
		// Update database name to tenant database
		$db_config['database'] = $tenant['database_name'];
		
		// Ensure dbdriver is set
		if (empty($db_config['dbdriver']))
		{
			$db_config['dbdriver'] = 'mysqli';
		}
		
		// Close existing connection if it exists
		if (isset($this->CI->db) && is_object($this->CI->db))
		{
			if (method_exists($this->CI->db, 'close'))
			{
				$this->CI->db->close();
			}
		}
		
		// Unset the db object
		unset($this->CI->db);
		
		// Load tenant database as the default connection
		// Parameters: config array, return DB object (FALSE), replace default (FALSE = replace default)
		$this->CI->load->database($db_config, FALSE, FALSE);
		
		// Store current tenant
		$this->current_tenant = $tenant;
		
		// Load feature configuration from vendor database if Feature_access library is available
		if (class_exists('Feature_access'))
		{
			$this->CI->load->library('Feature_access');
			$this->CI->Feature_access->setVendorDatabase($tenant['database_name']);
		}
		
		log_message('debug', 'Switched to tenant database: ' . $tenant['database_name']);
		
		return TRUE;
	}
	
	/**
	 * Get tenant settings
	 *
	 * @return	array|NULL	Tenant settings
	 */
	public function getSettings()
	{
		if (!$this->current_tenant)
		{
			return NULL;
		}
		
		// Load settings from database
		$settings = $this->CI->Tenant_model->getClientSettings($this->current_tenant['id']);
		
		return $settings;
	}
	
	/**
	 * Check if feature is enabled for current tenant
	 *
	 * @param	string	$feature_slug	Feature slug
	 * @return	bool	TRUE if enabled, FALSE otherwise
	 */
	public function isFeatureEnabled($feature_slug)
	{
		if (!$this->current_tenant)
		{
			return FALSE;
		}
		
		return $this->CI->Tenant_model->isFeatureEnabled($this->current_tenant['id'], $feature_slug);
	}
	
	/**
	 * Create client database
	 *
	 * @param	string	$database_name	Database name
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function createClientDatabase($database_name)
	{
		return $this->CI->Tenant_model->createClientDatabase($database_name);
	}
}

