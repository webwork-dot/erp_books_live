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
			$vendor_domain = $this->uri->segment(1);
			$reserved_routes = array('erp-admin', 'api', 'frontend', 'vendor', 'Vendor', 'auth');
			if ($vendor_domain && !in_array($vendor_domain, $reserved_routes))
			{
				redirect($vendor_domain . '/login', 'refresh');
			}
			else
			{
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
		
		if ($vendor_id)
		{
			$this->current_vendor = $this->Erp_client_model->getClientById($vendor_id);
			
			if (!$this->current_vendor || $this->current_vendor['status'] !== 'active')
			{
				$this->session->set_flashdata('error', 'Your vendor account is not active.');
				$this->session->unset_userdata('vendor_logged_in');
				redirect($this->current_vendor['domain'] . '/login', 'refresh');
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
			$this->load->library('Feature_access');
			$this->Feature_access->setVendorDatabase($this->current_vendor['database_name']);
			$vendor_db_features = $this->Feature_access->getEnabledFeatures();
			
			if (!empty($vendor_db_features))
			{
				return $vendor_db_features;
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

