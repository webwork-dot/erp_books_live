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
			
			if ($vendor) {
				// Use vendor_url helper which handles localhost vs production automatically
				$base_domain = $this->Erp_client_model->extractBaseDomain($vendor['domain']);
				if (empty($base_domain)) {
					$base_domain = $vendor['domain'];
				}
				$this->load->helper('common');
				redirect(vendor_url('login', $base_domain), 'refresh');
			} else {
				redirect('auth/login', 'refresh');
			}
		}
	}
	
	/**
	 * Load current vendor data
	 *
	 * @return	void
	 */
	protected function loadCurrentVendor()
	{
		$vendor_id = $this->session->userdata('vendor_id');
		
		// If no vendor_id in session, try to detect from HTTP_HOST
		if (!$vendor_id) {
			$http_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
			if (strpos($http_host, ':') !== false) {
				$http_host = substr($http_host, 0, strpos($http_host, ':'));
			}
			
			if (!empty($http_host) && strpos($http_host, 'localhost') === false && strpos($http_host, '127.0.0.1') === false) {
				// getClientByDomain now handles subdomain matching automatically
				$vendor = $this->Erp_client_model->getClientByDomain($http_host);
				if ($vendor) {
					$vendor_id = $vendor['id'];
					// Set session for future requests
					// Store base domain (not subdomain) in session
					$base_domain = $this->Erp_client_model->extractBaseDomain($vendor['domain']);
					if (empty($base_domain)) {
						$base_domain = $vendor['domain'];
					}
					$this->session->set_userdata('vendor_id', $vendor_id);
					$this->session->set_userdata('vendor_logged_in', true);
					$this->session->set_userdata('user_type', 'vendor');
					$this->session->set_userdata('vendor_domain', $base_domain);
				}
			}
		}
		
		if ($vendor_id)
		{
			$this->current_vendor = $this->Erp_client_model->getClientById($vendor_id);
			
			if (!$this->current_vendor || $this->current_vendor['status'] !== 'active')
			{
				$this->session->set_flashdata('error', 'Your vendor account is not active.');
				$this->session->unset_userdata('vendor_logged_in');
				$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
				redirect($protocol . '://' . $this->current_vendor['domain'] . '/login', 'refresh');
			}
			
			// Switch to vendor's database if database_name is set
			if (!empty($this->current_vendor['database_name']))
			{
				$this->load->library('Tenant');
				if (!$this->tenant->switchDatabase($this->current_vendor))
				{
					log_message('error', 'Failed to switch to vendor database: ' . $this->current_vendor['database_name'] . ' for vendor ID: ' . $vendor_id);
					// Don't block access, but log the error
				}
				else
				{
					log_message('debug', 'Switched to vendor database: ' . $this->current_vendor['database_name'] . ' for vendor ID: ' . $vendor_id);
					
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

