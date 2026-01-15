<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Client Base Controller
 *
 * Base controller for all client admin controllers with authentication and tenant context
 *
 * @package		ERP
 * @subpackage	Controllers
 * @category	Controllers
 * @author		ERP Team
 */
class Client_base extends CI_Controller
{
	/**
	 * Current user data
	 *
	 * @var	array
	 */
	protected $current_user = NULL;
	
	/**
	 * Current tenant/client data
	 *
	 * @var	array
	 */
	protected $current_tenant = NULL;
	
	/**
	 * Constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Load tenant library
		$this->load->library('Tenant');
		
		// Get current tenant
		$this->current_tenant = $this->tenant->getClient();
		
		if (!$this->current_tenant)
		{
			show_error('Tenant not found. Please contact administrator.', 500);
		}
		
		// Check authentication
		$this->checkAuth();
		
		// Load user data
		$this->loadCurrentUser();
	}
	
	/**
	 * Check authentication
	 *
	 * @return	void
	 */
	protected function checkAuth()
	{
		// Check if user is logged in
		if (!$this->session->userdata('client_user_id'))
		{
			// Redirect to login if not authenticated
			redirect('client-admin/auth/login');
		}
	}
	
	/**
	 * Load current user data
	 *
	 * @return	void
	 */
	protected function loadCurrentUser()
	{
		$user_id = $this->session->userdata('client_user_id');
		
		if ($user_id)
		{
			$this->load->model('Client_user_model');
			$this->current_user = $this->Client_user_model->getUserById($user_id);
		}
	}
	
	/**
	 * Check if feature is enabled
	 *
	 * @param	string	$feature_slug	Feature slug
	 * @return	bool	TRUE if enabled, FALSE otherwise
	 */
	protected function isFeatureEnabled($feature_slug)
	{
		return $this->tenant->isFeatureEnabled($feature_slug);
	}
}

