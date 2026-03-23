<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ERP Base Controller
 *
 * Base controller for all ERP admin controllers with authentication check
 *
 * @package		ERP
 * @subpackage	Controllers
 * @category	Controllers
 * @author		ERP Team
 */
class Erp_base extends CI_Controller
{
	/**
	 * Current user data
	 *
	 * @var	array
	 */
	protected $current_user = NULL;
	
	/**
	 * Constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Load URL helper if not already loaded
		$this->load->helper('url');
		
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
		// Check if user is logged in (either unified or legacy)
		$erp_logged_in = $this->session->userdata('erp_logged_in');
		$user_type = $this->session->userdata('user_type');
		
		// Allow access if erp_logged_in is true OR user_type is admin (not vendor)
		if (!$erp_logged_in && $user_type !== 'admin')
		{
			// Check if it's a vendor trying to access admin area
			if ($user_type === 'vendor')
			{
				// Redirect to dashboard (no vendor domain in URL)
				redirect('dashboard', 'refresh');
			}
			else
			{
				// Redirect to unified login
				redirect('auth/login', 'refresh');
			}
		}
	}
	
	/**
	 * Load current user data
	 *
	 * @return	void
	 */
	protected function loadCurrentUser()
	{
		$user_id = $this->session->userdata('erp_user_id');
		
		if ($user_id)
		{
			$this->load->model('Erp_user_model');
			$this->current_user = $this->Erp_user_model->getUserByUsername($this->session->userdata('erp_username'));
		}
	}
	
	/**
	 * Check permission
	 *
	 * @param	string	$module		Module name
	 * @param	string	$action		Action (create, read, update, delete)
	 * @return	bool	TRUE if user has permission, FALSE otherwise
	 */
	protected function hasPermission($module, $action)
	{
		if (!$this->current_user)
		{
			return FALSE;
		}
		
		// Super admin role (ID 1) has all permissions
		if (isset($this->current_user['role_id']) && $this->current_user['role_id'] == 1)
		{
			return TRUE;
		}
		
		// Check permissions
		if (isset($this->current_user['permissions']) && is_array($this->current_user['permissions']))
		{
			if (isset($this->current_user['permissions'][$module]))
			{
				return in_array($action, $this->current_user['permissions'][$module]);
			}
		}
		
		return FALSE;
	}
}

