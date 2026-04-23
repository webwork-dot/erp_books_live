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

		$tenant_db_username = isset($tenant['db_username']) ? trim((string)$tenant['db_username']) : '';
		$tenant_db_password = isset($tenant['db_password']) ? (string)$tenant['db_password'] : '';
		$has_dedicated_credentials = ($tenant_db_username !== '');
		
		// Check if database connection already exists and try to switch directly
		// NOTE: direct select_db works only when current MySQL user has access.
		// If vendor has dedicated DB credentials, we must reconnect with those credentials.
		if (!$has_dedicated_credentials && isset($this->CI->db) && is_object($this->CI->db))
		{
			// For mysqli driver, try to directly select the database
			if (property_exists($this->CI->db, 'conn_id') && $this->CI->db->conn_id)
			{
				// Check if it's a mysqli connection
				if (is_object($this->CI->db->conn_id) && get_class($this->CI->db->conn_id) === 'mysqli')
				{
					// Directly select the database using mysqli
					try
					{
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
								if (isset($this->CI->Feature_access) && is_object($this->CI->Feature_access))
								{
									$this->CI->Feature_access->setVendorDatabase($tenant['database_name']);
								}
							}
							
							log_message('debug', 'Switched to tenant database: ' . $tenant['database_name']);
							return TRUE;
						}
					}
					catch (Exception $e)
					{
						log_message('error', 'Direct tenant DB switch failed for "' . $tenant['database_name'] . '": ' . $e->getMessage());
					}
				}
			}
		}
		
		// Fallback: Reload database connection with new database name
		// Build config from existing DB object first, then fallback to database.php
		$db_config = $this->getDefaultDbConfig();
		
		// Ensure we have all required fields
		if (empty($db_config) || !is_array($db_config))
		{
			log_message('error', 'Failed to load database configuration');
			return FALSE;
		}
		
		// Update database name to tenant database
		$db_config['database'] = $tenant['database_name'];

		// Prefer dedicated vendor DB credentials when configured
		if ($has_dedicated_credentials)
		{
			$db_config['username'] = $tenant_db_username;
			$db_config['password'] = $tenant_db_password;
		}
		
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
		try
		{
			$this->CI->load->database($db_config, FALSE, FALSE);
		}
		catch (Exception $e)
		{
			log_message('error', 'Tenant DB connection failed for "' . $tenant['database_name'] . '": ' . $e->getMessage());
			return FALSE;
		}
		
		// Store current tenant
		$this->current_tenant = $tenant;
		
		// Load feature configuration from vendor database if Feature_access library is available
		if (class_exists('Feature_access'))
		{
			$this->CI->load->library('Feature_access');
			if (isset($this->CI->Feature_access) && is_object($this->CI->Feature_access))
			{
				$this->CI->Feature_access->setVendorDatabase($tenant['database_name']);
			}
		}
		
		log_message('debug', 'Switched to tenant database: ' . $tenant['database_name']);
		
		return TRUE;
	}

	/**
	 * Get default DB config safely for runtime switching
	 *
	 * @return array
	 */
	private function getDefaultDbConfig()
	{
		$db_config = array();

		// Preferred source: current active db object (already validated by CI)
		if (isset($this->CI->db) && is_object($this->CI->db))
		{
			$keys = array(
				'hostname', 'username', 'password', 'database', 'dbdriver',
				'dbprefix', 'pconnect', 'db_debug', 'cache_on', 'cachedir',
				'char_set', 'dbcollat', 'swap_pre', 'encrypt', 'compress',
				'stricton', 'failover', 'save_queries', 'port'
			);

			foreach ($keys as $key)
			{
				if (property_exists($this->CI->db, $key))
				{
					$db_config[$key] = $this->CI->db->{$key};
				}
			}
		}

		if (!empty($db_config))
		{
			return $db_config;
		}

		// Fallback source: include CI database config file directly
		$db = array();
		$active_group = 'default';
		$query_builder = TRUE;

		$db_file = APPPATH . 'config/database.php';
		if (file_exists($db_file))
		{
			include $db_file;
		}

		if (isset($db[$active_group]) && is_array($db[$active_group]))
		{
			return $db[$active_group];
		}

		return array();
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

