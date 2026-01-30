<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Vendor Base Controller
 *
 * Base controller for all vendor controllers
 * Handles authentication and common functionality
 *
 * @package		ERP
 * @subpackage	Controllers
 * @category	Controllers
 * @author		ERP Team
 */
class Vendor_base extends CI_Controller
{
	/**
	 * Current vendor data
	 *
	 * @var	array
	 */
	protected $current_vendor;
	
	/**
	 * Constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('Erp_client_model');
		
		// Check authentication
		$this->checkAuth();
		
		// Load current vendor
		$this->loadCurrentVendor();
	}
	
	/**
	 * Check if vendor is authenticated
	 *
	 * @return	void
	 */
	protected function checkAuth()
	{
		// Check for vendor session (either from unified login or legacy)
		$vendor_logged_in = $this->session->userdata('vendor_logged_in');
		$user_type = $this->session->userdata('user_type');
		
		// Allow access if vendor_logged_in is true OR user_type is vendor
		if (!$vendor_logged_in && $user_type !== 'vendor')
		{
			// Get vendor domain from HTTP_HOST first (subdomain-based routing)
			$http_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
			if (strpos($http_host, ':') !== false) {
				$http_host = substr($http_host, 0, strpos($http_host, ':'));
			}
			
			// Check if HTTP_HOST is a vendor domain
			$vendor = null;
			if (!empty($http_host) && strpos($http_host, 'localhost') === false && strpos($http_host, '127.0.0.1') === false) {
				$vendor = $this->Erp_client_model->getClientByDomain($http_host);
			}
			
			// Fallback to URI segment (path-based routing) - for backward compatibility
			if (!$vendor) {
				$vendor_domain_segment = $this->uri->segment(1);
				$reserved_routes = array('erp-admin', 'api', 'frontend', 'vendor', 'Vendor', 'auth');
				if ($vendor_domain_segment && !in_array($vendor_domain_segment, $reserved_routes)) {
					$vendor = $this->Erp_client_model->getClientByDomain($vendor_domain_segment);
				}
			}
			
			// Redirect to login page (no vendor domain in URL)
			redirect('auth/login', 'refresh');
		}
	}
	
	/**
	 * Load current vendor data
	 *
	 * @return	void
	 */
	protected function loadCurrentVendor()
	{
		$vendor_id = NULL;
		$vendor = NULL;
		
		// Always check HTTP_HOST first (for subdomain-based routing)
		// This ensures we use the correct vendor for the current domain
		$http_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
		if (strpos($http_host, ':') !== false) {
			$http_host = substr($http_host, 0, strpos($http_host, ':'));
		}
		
		if (!empty($http_host) && strpos($http_host, 'localhost') === false && strpos($http_host, '127.0.0.1') === false) {
			// getClientByDomain handles subdomain matching automatically
			$vendor = $this->Erp_client_model->getClientByDomain($http_host);
			if ($vendor) {
				$vendor_id = $vendor['id'];
			}
		}
		
		// Fallback to session vendor_id if HTTP_HOST didn't match a vendor
		// This handles cases where HTTP_HOST might not be a vendor domain
		if (!$vendor_id) {
			$session_vendor_id = $this->session->userdata('vendor_id');
			if ($session_vendor_id) {
				$vendor_id = $session_vendor_id;
			}
		}
		
		// If we found a vendor (from HTTP_HOST or session), load it
		if ($vendor_id)
		{
			// If we already have vendor data from HTTP_HOST detection, use it
			// Otherwise, load from database
			if (!$vendor) {
				$this->current_vendor = $this->Erp_client_model->getClientById($vendor_id);
			} else {
				$this->current_vendor = $vendor;
			}
			
			if (!$this->current_vendor || $this->current_vendor['status'] !== 'active')
			{
				$this->session->set_flashdata('error', 'Your vendor account is not active.');
				$this->session->unset_userdata('vendor_logged_in');
				$this->session->unset_userdata('vendor_id');
				// Redirect to login page (no vendor domain in URL)
				redirect('auth/login', 'refresh');
			}
			
			// Update session with current vendor info (in case it changed or was detected from HTTP_HOST)
			$base_domain = $this->Erp_client_model->extractBaseDomain($this->current_vendor['domain']);
			if (empty($base_domain)) {
				$base_domain = $this->current_vendor['domain'];
			}
			$this->session->set_userdata('vendor_id', $vendor_id);
			$this->session->set_userdata('vendor_logged_in', true);
			$this->session->set_userdata('user_type', 'vendor');
			$this->session->set_userdata('vendor_domain', $base_domain);
			// Store domain in session as domain_url for easy access
			if (isset($this->current_vendor['domain'])) {
				$this->session->set_userdata('domain_url', $this->current_vendor['domain']);
			}
			
			// Switch to vendor's database if database_name is set
			if (!empty($this->current_vendor['database_name']))
			{
				$this->load->library('Tenant');
				if (!$this->tenant->switchDatabase($this->current_vendor))
				{
					log_message('error', 'Failed to switch to vendor database: ' . $this->current_vendor['database_name'] . ' for vendor ID: ' . $vendor_id);
					show_error('Unable to connect to vendor database. Please contact support.', 500, 'Database Connection Error');
				}
				else
				{
					log_message('debug', 'Switched to vendor database: ' . $this->current_vendor['database_name'] . ' for vendor ID: ' . $vendor_id);
					
					// Verify database switch was successful by checking current database
					$current_db = isset($this->db->database) ? $this->db->database : '';
					if (!empty($current_db) && $current_db !== $this->current_vendor['database_name']) {
						log_message('error', 'Database switch verification failed. Expected: ' . $this->current_vendor['database_name'] . ', Got: ' . $current_db);
						// Try to switch again
						$this->tenant->switchDatabase($this->current_vendor);
					}
					
					// Check and fix foreign key constraints on first access (one-time fix)
					$constraint_fixed_key = 'fk_constraints_fixed_' . $this->current_vendor['database_name'];
					if (!$this->session->userdata($constraint_fixed_key))
					{
						$this->load->model('Tenant_model');
						$hostname = $this->db->hostname;
						$username = $this->db->username;
						$password = $this->db->password;
						
						$connection = @new mysqli($hostname, $username, $password, $this->current_vendor['database_name']);
						if (!$connection->connect_error)
						{
							if ($this->Tenant_model->removeVendorForeignKeyConstraints($connection))
							{
								log_message('info', 'Fixed foreign key constraints for vendor database: ' . $this->current_vendor['database_name']);
								$this->session->set_userdata($constraint_fixed_key, TRUE);
							}
							$connection->close();
						}
					}
				}
			}
			else
			{
				log_message('warning', 'Vendor database_name is empty for vendor ID: ' . $vendor_id . '. Using master database.');
			}
		}
		else
		{
			// No vendor found - this should not happen if checkAuth passed
			log_message('error', 'No vendor found in loadCurrentVendor() - HTTP_HOST: ' . $http_host);
		}
	}
	
	/**
	 * Get vendor domain
	 * Checks session first (domain_url), then falls back to database (erp_clients.domain)
	 *
	 * @return	string	Vendor domain
	 */
	protected function getVendorDomain()
	{
		// Check session first
		$domain_url = $this->session->userdata('domain_url');
		if (!empty($domain_url)) {
			return $domain_url;
		}
		
		// Fallback to database if not in session
		if (isset($this->current_vendor['domain'])) {
			// Store in session for future use
			$this->session->set_userdata('domain_url', $this->current_vendor['domain']);
			return $this->current_vendor['domain'];
		}
		
		// If still not found, try to get from database using vendor_id
		$vendor_id = $this->session->userdata('vendor_id');
		if ($vendor_id) {
			$vendor = $this->Erp_client_model->getClientById($vendor_id);
			if ($vendor && isset($vendor['domain'])) {
				// Store in session for future use
				$this->session->set_userdata('domain_url', $vendor['domain']);
				return $vendor['domain'];
			}
		}
		
		return '';
	}
	
	/**
	 * Get vendor domain for URL generation
	 * Returns empty string for subdomain routing, domain for path-based routing
	 *
	 * @return	string	Vendor domain for URLs (empty if subdomain routing)
	 */
	protected function getVendorDomainForUrl()
	{
		// Check if we're using subdomain routing
		$http_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
		if (strpos($http_host, ':') !== false) {
			$http_host = substr($http_host, 0, strpos($http_host, ':'));
		}
		
		// If HTTP_HOST is a subdomain (like master.varitty.in), return empty string
		// This means URLs should not include domain in path
		if (!empty($http_host) && 
			strpos($http_host, 'localhost') === false && 
			strpos($http_host, '127.0.0.1') === false &&
			strpos($http_host, 'erp-admin') === false) {
			// Check if HTTP_HOST matches vendor domain (subdomain routing)
			$vendor = $this->Erp_client_model->getClientByDomain($http_host);
			if ($vendor) {
				// Using subdomain routing - don't include domain in URLs
				return '';
			}
		}
		
		// Using path-based routing - return base domain for URLs
		if (isset($this->current_vendor['domain'])) {
			$base_domain = $this->Erp_client_model->extractBaseDomain($this->current_vendor['domain']);
			return empty($base_domain) ? $this->current_vendor['domain'] : $base_domain;
		}
		
		return '';
	}
	
	protected function buildVendorUploadPath($relative)
	{
		$base_domain = isset($this->current_vendor['domain']) ? $this->Erp_client_model->extractBaseDomain($this->current_vendor['domain']) : '';
		$root = $this->config->item('webwork_root_path');
		if (empty($root)) { $root = '/webwork'; }
		$root = rtrim($root, '/\\');
		$relative = ltrim($relative, '/\\');
		$path = $root . DIRECTORY_SEPARATOR . $base_domain . DIRECTORY_SEPARATOR . $relative . DIRECTORY_SEPARATOR;
		return $path;
	}
	
	/**
	 * Get enabled features for current vendor
	 *
	 * @return	array	Array of enabled features
	 */
	protected function getEnabledFeatures()
	{
		if (!isset($this->current_vendor['id']))
		{
			return array();
		}
		
		// Try to get from vendor database first (preferred method)
		if (!empty($this->current_vendor['database_name']))
		{
			try {
				$this->load->library('Feature_access');
				
				// Check if library loaded successfully
				if (!isset($this->Feature_access) || !is_object($this->Feature_access))
				{
					log_message('error', 'Feature_access library failed to load');
					// Fall through to master database check
				}
				else
				{
					$this->Feature_access->setVendorDatabase($this->current_vendor['database_name']);
					$vendor_db_features = $this->Feature_access->getEnabledFeatures();
					
					if (!empty($vendor_db_features))
					{
						return $vendor_db_features;
					}
				}
			} catch (Exception $e) {
				log_message('error', 'Error loading Feature_access library: ' . $e->getMessage());
				// Fall through to master database check
			}
		}
		
		// Fallback to master database check
		$vendor_features = $this->Erp_client_model->getClientFeatures($this->current_vendor['id']);
		$enabled_features = array();
		
		foreach ($vendor_features as $feature) {
			if ($feature['is_enabled'] == 1 && $feature['is_active'] == 1) {
				$enabled_features[] = $feature;
			}
		}
		
		return $enabled_features;
	}
	
	/**
	 * Check if feature is enabled for current vendor
	 *
	 * @param	string	$feature_slug	Feature slug
	 * @return	bool	TRUE if enabled, FALSE otherwise
	 */
	protected function checkFeatureAccess($feature_slug)
	{
		if (!isset($this->current_vendor['id']))
		{
			return FALSE;
		}
		
		// Try to check vendor database first (preferred method)
		if (!empty($this->current_vendor['database_name']))
		{
			$this->load->library('Feature_access');
			$this->Feature_access->setVendorDatabase($this->current_vendor['database_name']);
			return $this->Feature_access->isEnabled($feature_slug);
		}
		
		// Fallback to master database check
		$vendor_features = $this->Erp_client_model->getClientFeatures($this->current_vendor['id']);
		
		foreach ($vendor_features as $feature) {
			if ($feature['slug'] === $feature_slug && $feature['is_enabled'] == 1 && $feature['is_active'] == 1) {
				return TRUE;
			}
		}
		
		return FALSE;
	}
}
