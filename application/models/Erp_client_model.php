<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ERP Client Model
 *
 * Handles database operations for client management
 *
 * @package		ERP
 * @subpackage	Models
 * @category	Models
 * @author		ERP Team
 */
class Erp_client_model extends CI_Model
{
	/**
	 * Constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
		// Use master database
		$this->load->database('default', TRUE);
	}
	
	/**
	 * Get all clients
	 *
	 * @param	array	$filters	Optional filters
	 * @param	int	$limit		Limit (for pagination)
	 * @param	int	$offset		Offset (for pagination)
	 * @return	array	Array of clients
	 */
	public function getAllClients($filters = array(), $limit = NULL, $offset = 0)
	{
		$this->db->select('erp_clients.*');
		$this->db->from('erp_clients');
		
		if (isset($filters['status']))
		{
			$this->db->where('erp_clients.status', $filters['status']);
		}
		
		if (isset($filters['search']))
		{
			$this->db->group_start();
			$this->db->like('erp_clients.name', $filters['search']);
			$this->db->or_like('erp_clients.domain', $filters['search']);
			$this->db->group_end();
		}
		
		$this->db->order_by('erp_clients.created_at', 'ASC');
		
		if ($limit !== NULL)
		{
			$this->db->limit($limit, $offset);
		}
		
		$query = $this->db->get();
		
		return $query->result_array();
	}
	
	/**
	 * Get total clients count
	 *
	 * @param	array	$filters	Optional filters
	 * @return	int	Total number of clients
	 */
	public function getTotalClients($filters = array())
	{
		$this->db->from('erp_clients');
		
		if (isset($filters['status']))
		{
			$this->db->where('erp_clients.status', $filters['status']);
		}
		
		if (isset($filters['search']))
		{
			$this->db->group_start();
			$this->db->like('erp_clients.name', $filters['search']);
			$this->db->or_like('erp_clients.domain', $filters['search']);
			$this->db->group_end();
		}
		
		return $this->db->count_all_results();
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
		$query = $this->db->get('erp_clients');
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		
		return NULL;
	}
	
	/**
	 * Create client
	 *
	 * @param	array	$data	Client data
	 * @return	int|FALSE	Client ID on success, FALSE on failure
	 */
	public function createClient($data)
	{
		// Generate database name if not provided
		// Prefer domain over name for database name generation (domain is more stable)
		if (empty($data['database_name']))
		{
			if (!empty($data['domain']))
			{
				$data['database_name'] = $this->generateDatabaseName($data['domain']);
			}
			else
			{
				$data['database_name'] = $this->generateDatabaseName($data['name']);
			}
		}
		
		// Ensure database name is unique
		$data['database_name'] = $this->ensureUniqueDatabaseName($data['database_name']);
		
		$this->db->insert('erp_clients', $data);
		
		if ($this->db->affected_rows() > 0)
		{
			$client_id = $this->db->insert_id();
			
			// Create default settings
			$this->createDefaultSettings($client_id);
			
			return $client_id;
		}
		
		return FALSE;
	}
	
	/**
	 * Update client
	 *
	 * @param	int	$client_id	Client ID
	 * @param	array	$data		Client data
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function updateClient($client_id, $data)
	{
		// Don't update if data array is empty
		if (empty($data))
		{
			return TRUE; // Nothing to update, consider it successful
		}
		
		$this->db->where('id', $client_id);
		$this->db->update('erp_clients', $data);
		
		$error = $this->db->error();
		
		// Check for database errors
		// Return TRUE if query executed successfully (even if no rows were affected)
		// This handles the case where data hasn't changed but query executed successfully
		// MySQL returns 0 affected rows when UPDATE doesn't change any values, but query still succeeds
		if (isset($error['code']) && $error['code'] != 0)
		{
			// There's a database error
			log_message('error', 'Client update failed: ' . json_encode($error));
			return FALSE;
		}
		
		// No error - query executed successfully (even if 0 rows affected)
		return TRUE;
	}
	
	/**
	 * Delete client
	 *
	 * @param	int	$client_id	Client ID
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function deleteClient($client_id)
	{
		$this->db->where('id', $client_id);
		$this->db->delete('erp_clients');
		
		return $this->db->affected_rows() > 0;
	}
	
	/**
	 * Get recent clients
	 *
	 * @param	int	$limit	Number of clients to return
	 * @return	array	Array of clients
	 */
	public function getRecentClients($limit = 10)
	{
		$this->db->order_by('created_at', 'ASC');
		$this->db->limit($limit);
		$query = $this->db->get('erp_clients');
		
		return $query->result_array();
	}
	
	/**
	 * Generate database name from client name or domain
	 *
	 * @param	string	$name	Client name or domain
	 * @return	string	Database name
	 */
	private function generateDatabaseName($name)
	{
		// Convert to lowercase, replace hyphens and spaces with underscores, remove other special characters
		$db_name = strtolower($name);
		$db_name = str_replace('-', '_', $db_name); // Replace hyphens with underscores
		$db_name = str_replace(' ', '_', $db_name); // Replace spaces with underscores
		$db_name = preg_replace('/[^a-z0-9_]/', '', $db_name); // Remove other special characters
		$db_name = 'erp_client_' . $db_name;
		
		return $db_name;
	}
	
	/**
	 * Ensure database name is unique
	 *
	 * @param	string	$database_name	Database name
	 * @return	string	Unique database name
	 */
	private function ensureUniqueDatabaseName($database_name)
	{
		$original_name = $database_name;
		$counter = 1;
		
		while ($this->databaseExists($database_name))
		{
			$database_name = $original_name . '_' . $counter;
			$counter++;
		}
		
		return $database_name;
	}
	
	/**
	 * Check if database name exists
	 *
	 * @param	string	$database_name	Database name
	 * @return	bool	TRUE if exists, FALSE otherwise
	 */
	private function databaseExists($database_name)
	{
		$this->db->where('database_name', $database_name);
		$query = $this->db->get('erp_clients');
		
		return $query->num_rows() > 0;
	}
	
	/**
	 * Create default settings for client
	 *
	 * @param	int	$client_id	Client ID
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	private function createDefaultSettings($client_id)
	{
		$settings = array(
			'client_id' => $client_id,
			'primary_color' => '#007bff',
			'secondary_color' => '#6c757d',
			'theme' => 'default'
		);
		
		$this->db->insert('erp_client_settings', $settings);
		
		return $this->db->affected_rows() > 0;
	}
	
	/**
	 * Get client features
	 *
	 * @param	int	$client_id	Client ID
	 * @return	array	Array of features
	 */
	public function getClientFeatures($client_id)
	{
		$this->db->select('erp_features.*, erp_client_features.is_enabled');
		$this->db->from('erp_client_features');
		$this->db->join('erp_features', 'erp_features.id = erp_client_features.feature_id', 'inner');
		$this->db->where('erp_client_features.client_id', $client_id);
		$query = $this->db->get();
		
		return $query->result_array();
	}
	
	/**
	 * Assign feature to client
	 *
	 * @param	int	$client_id	Client ID
	 * @param	int	$feature_id	Feature ID
	 * @param	bool	$enabled	Is feature enabled
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function assignFeature($client_id, $feature_id, $enabled = TRUE)
	{
		$data = array(
			'client_id' => $client_id,
			'feature_id' => $feature_id,
			'is_enabled' => $enabled ? 1 : 0
		);
		
		// Use INSERT ... ON DUPLICATE KEY UPDATE for better reliability
		$is_enabled_value = $enabled ? 1 : 0;
		$sql = "INSERT INTO `erp_client_features` (`client_id`, `feature_id`, `is_enabled`) 
				VALUES (?, ?, ?) 
				ON DUPLICATE KEY UPDATE `is_enabled` = ?, `updated_at` = CURRENT_TIMESTAMP";
		
		$this->db->query($sql, array($client_id, $feature_id, $is_enabled_value, $is_enabled_value));
		
		$result = $this->db->affected_rows() > 0;
		
		// Sync to vendor database if update was successful
		if ($result)
		{
			$this->load->model('Feature_sync_model');
			$vendor = $this->getClientById($client_id);
			if ($vendor && !empty($vendor['database_name']))
			{
				// Try to sync, but don't fail if sync fails
				try
				{
					$this->Feature_sync_model->syncFeature($client_id, $feature_id, $enabled);
				}
				catch (Exception $e)
				{
					log_message('error', 'Failed to sync feature to vendor database: ' . $e->getMessage());
				}
			}
		}
		
		return $result;
	}
	
	/**
	 * Remove feature from client
	 *
	 * @param	int	$client_id	Client ID
	 * @param	int	$feature_id	Feature ID
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function removeFeature($client_id, $feature_id)
	{
		$this->db->where('client_id', $client_id);
		$this->db->where('feature_id', $feature_id);
		$this->db->delete('erp_client_features');
		
		$result = $this->db->affected_rows() > 0;
		
		// Sync removal to vendor database (set enabled to false)
		if ($result)
		{
			$this->load->model('Feature_sync_model');
			$vendor = $this->getClientById($client_id);
			if ($vendor && !empty($vendor['database_name']))
			{
				// Try to sync, but don't fail if sync fails
				try
				{
					$this->Feature_sync_model->syncFeature($client_id, $feature_id, FALSE);
				}
				catch (Exception $e)
				{
					log_message('error', 'Failed to sync feature removal to vendor database: ' . $e->getMessage());
				}
			}
		}
		
		return $result;
	}
	
	/**
	 * Get client by username
	 *
	 * @param	string	$username	Username
	 * @return	array|NULL	Client data or NULL if not found
	 */
	public function getClientByUsername($username)
	{
		$this->db->where('username', $username);
		$this->db->where('status', 'active');
		$query = $this->db->get('erp_clients');
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		
		return NULL;
	}
	
	/**
	 * Get client by domain (supports subdomain matching)
	 *
	 * @param	string	$domain	Domain name (can be subdomain like master.varitty.in or base domain like varitty.in)
	 * @return	array|NULL	Client data or NULL if not found
	 */
	public function getClientByDomain($domain)
	{
		// First try exact match
		$this->db->where('domain', $domain);
		$this->db->where('status', 'active');
		$query = $this->db->get('erp_clients');
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		
		// If not found and domain contains subdomain (e.g., master.varitty.in)
		// Extract base domain (varitty.in) and try matching
		if (strpos($domain, '.') !== false)
		{
			$parts = explode('.', $domain);
			if (count($parts) >= 2)
			{
				// Remove first part (subdomain) and join rest (base domain)
				array_shift($parts);
				$base_domain = implode('.', $parts);
				
				// Try matching with base domain
				$this->db->where('domain', $base_domain);
				$this->db->where('status', 'active');
				$query = $this->db->get('erp_clients');
				
				if ($query->num_rows() > 0)
				{
					return $query->row_array();
				}
			}
		}
		
		// Also try reverse: if stored domain is a subdomain and request is base domain
		// Match any subdomain of the requested domain
		$this->db->like('domain', '.' . $domain, 'after');
		$this->db->or_where('domain', $domain);
		$this->db->where('status', 'active');
		$query = $this->db->get('erp_clients');
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		
		return NULL;
	}
	
	/**
	 * Extract base domain from subdomain
	 *
	 * @param	string	$domain	Full domain (e.g., master.varitty.in)
	 * @return	string	Base domain (e.g., varitty.in)
	 */
	public function extractBaseDomain($domain)
	{
		if (strpos($domain, '.') === false)
		{
			return $domain;
		}
		
		$parts = explode('.', $domain);
		if (count($parts) >= 2)
		{
			// Remove first part (subdomain) and return rest
			array_shift($parts);
			return implode('.', $parts);
		}
		
		return $domain;
	}
	
	/**
	 * Generate vendor subdomain URL
	 *
	 * @param	string	$base_domain	Base domain (e.g., varitty.in)
	 * @param	string	$subdomain		Subdomain prefix (default: 'master')
	 * @return	string	Full subdomain (e.g., master.varitty.in)
	 */
	public function getVendorSubdomain($base_domain, $subdomain = 'master')
	{
		return $subdomain . '.' . $base_domain;
	}
	
	/**
	 * Verify vendor password
	 *
	 * @param	string	$password	Plain text password
	 * @param	string	$hash		Hashed password
	 * @return	bool	TRUE if password matches, FALSE otherwise
	 */
	public function verifyPassword($password, $hash)
	{
		return sha1($password) === $hash;
	}
	
	/**
	 * Assign subcategory to client
	 *
	 * @param	int	$client_id		Client ID
	 * @param	int	$feature_id		Main Feature ID (parent category)
	 * @param	int	$subcategory_id	Sub-category Feature ID
	 * @param	bool	$enabled		Is subcategory enabled
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function assignSubcategory($client_id, $feature_id, $subcategory_id, $enabled = TRUE)
	{
		$data = array(
			'client_id' => $client_id,
			'feature_id' => $feature_id,
			'subcategory_id' => $subcategory_id,
			'is_enabled' => $enabled ? 1 : 0
		);
		
		// Use INSERT ... ON DUPLICATE KEY UPDATE
		$is_enabled_value = $enabled ? 1 : 0;
		$sql = "INSERT INTO `erp_client_feature_subcategories` (`client_id`, `feature_id`, `subcategory_id`, `is_enabled`) 
				VALUES (?, ?, ?, ?) 
				ON DUPLICATE KEY UPDATE `is_enabled` = ?, `updated_at` = CURRENT_TIMESTAMP";
		
		$this->db->query($sql, array($client_id, $feature_id, $subcategory_id, $is_enabled_value, $is_enabled_value));
		
		$result = $this->db->affected_rows() > 0;
		
		// Sync to vendor database if update was successful
		if ($result)
		{
			$this->load->model('Feature_sync_model');
			$vendor = $this->getClientById($client_id);
			if ($vendor && !empty($vendor['database_name']))
			{
				// Try to sync, but don't fail if sync fails
				try
				{
					$this->Feature_sync_model->syncSubcategory($client_id, $feature_id, $subcategory_id, $enabled);
				}
				catch (Exception $e)
				{
					log_message('error', 'Failed to sync subcategory to vendor database: ' . $e->getMessage());
				}
			}
		}
		
		return $result;
	}
	
	/**
	 * Remove subcategory from client
	 *
	 * @param	int	$client_id		Client ID
	 * @param	int	$feature_id		Main Feature ID
	 * @param	int	$subcategory_id	Sub-category Feature ID
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function removeSubcategory($client_id, $feature_id, $subcategory_id)
	{
		$this->db->where('client_id', $client_id);
		$this->db->where('feature_id', $feature_id);
		$this->db->where('subcategory_id', $subcategory_id);
		$this->db->delete('erp_client_feature_subcategories');
		
		$result = $this->db->affected_rows() > 0;
		
		// Sync removal to vendor database (set enabled to false)
		if ($result)
		{
			$this->load->model('Feature_sync_model');
			$vendor = $this->getClientById($client_id);
			if ($vendor && !empty($vendor['database_name']))
			{
				// Try to sync, but don't fail if sync fails
				try
				{
					$this->Feature_sync_model->syncSubcategory($client_id, $feature_id, $subcategory_id, FALSE);
				}
				catch (Exception $e)
				{
					log_message('error', 'Failed to sync subcategory removal to vendor database: ' . $e->getMessage());
				}
			}
		}
		
		return $result;
	}
	
	/**
	 * Get client subcategories
	 *
	 * @param	int	$client_id	Client ID
	 * @param	int	$feature_id	Main Feature ID (optional)
	 * @return	array	Array of subcategory assignments
	 */
	public function getClientSubcategories($client_id, $feature_id = NULL)
	{
		$this->db->select('erp_client_feature_subcategories.*, erp_features.name as subcategory_name');
		$this->db->from('erp_client_feature_subcategories');
		$this->db->join('erp_features', 'erp_features.id = erp_client_feature_subcategories.subcategory_id', 'left');
		$this->db->where('erp_client_feature_subcategories.client_id', $client_id);
		
		if ($feature_id !== NULL)
		{
			$this->db->where('erp_client_feature_subcategories.feature_id', $feature_id);
		}
		
		$this->db->where('erp_client_feature_subcategories.is_enabled', 1);
		
		$query = $this->db->get();
		
		return $query->result_array();
	}
}

