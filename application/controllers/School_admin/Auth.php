<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * School Admin Authentication Controller
 *
 * Handles login and logout for school admin
 *
 * @package		ERP
 * @subpackage	Controllers
 * @category	Controllers
 * @author		ERP Team
 */
class Auth extends CI_Controller
{
	/**
	 * Current tenant
	 *
	 * @var	array
	 */
	private $current_tenant = NULL;
	
	/**
	 * Constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('School_user_model');
		$this->load->library('form_validation');
		$this->load->library('Tenant');
		
		// Get current tenant
		// $this->current_tenant = $this->tenant->getClient();
		
		// if (!$this->current_tenant)
		// {
		// 	show_error('Tenant not found. Please contact administrator.', 500);
		// }
	}
	
	/**
	 * Login page
	 *
	 * @return	void
	 */
	public function login()
	{
		// Check if already logged in
		if ($this->session->userdata('school_user_id'))
		{
			redirect('school-admin/dashboard');
		}

		$http_host = $this->input->server('HTTP_HOST');
		if (strpos($http_host, ':') !== false)
		{
			$http_host = substr($http_host, 0, strpos($http_host, ':'));
		}
		$http_host = strtolower(trim($http_host));
		$domain = root_domain($http_host);

		$tenant_error = '';
		$tenant = $this->tenant->getClient();
		if (!$tenant && !empty($http_host))
		{
			$tenant = $this->tenant->resolveByDomain(($http_host == "localhost") ? "shivambook.com" : $http_host);
		}
		if (!$tenant && !empty($domain) && $domain !== $http_host)
		{
			$tenant = $this->tenant->resolveByDomain(($domain == "localhost") ? "shivambook.com" : $domain);
		}
		if ($tenant)
		{
			$this->current_tenant = $tenant;
			$current_db = isset($this->db->database) ? $this->db->database : NULL;
			if (!empty($tenant['database_name']) && $current_db !== $tenant['database_name'])
			{
				if (!$this->tenant->switchDatabase($tenant))
				{
					$tenant_error = 'Unable to connect to tenant database.';
				}
			}
			elseif (empty($tenant['database_name']))
			{
				$tenant_error = 'Tenant database not configured.';
			}
		}
		else
		{
			$tenant_error = 'Tenant not found. Please contact administrator.';
		}

		// echo json_encode($tenant); exit;
		
		// Set validation rules
		$this->form_validation->set_rules('username', 'Username', 'required|trim');
		$this->form_validation->set_rules('password', 'Password', 'required');
		
		if ($this->form_validation->run() == FALSE)
		{
			// Show login form
			$data['title'] = 'School Admin Login';
			$data['error'] = $tenant_error;
			$data['tenant'] = $this->current_tenant;
			
			$this->load->view('school_admin/auth/login', $data);
		}
		else
		{
			if (!empty($tenant_error))
			{
				$data['title'] = 'School Admin Login';
				$data['error'] = $tenant_error;
				$data['tenant'] = $this->current_tenant;
				$this->load->view('school_admin/auth/login', $data);
				return;
			}

			// Process login
			$username = $this->input->post('username');
			$password = $this->input->post('password');

			// Get user
			$user = $this->School_user_model->getUserByUsername($username);
			
			// print_r($this->School_user_model->verifyPassword($password, $user['admin_password'])); exit;
			if ($user && $this->School_user_model->verifyPassword($password, $user['admin_password']))
			{
				// Set session data
				$session_data = array(
					'school_user_id' => $user['id'],
					'school_username' => $user['username'],
					'school_email' => $user['email'],
					'school_role_id' => isset($user['role_id']) ? $user['role_id'] : NULL,
					'school_logged_in' => TRUE
				);
				
				
				$this->session->set_userdata($session_data);
				
				// Update last login
				$this->School_user_model->updateLastLogin($user['id']);
				
				// Redirect to dashboard
				redirect('school-admin/dashboard');
			}
			else
			{
				// Invalid credentials
				$data['title'] = 'School Admin Login';
				$data['error'] = 'Invalid username or password';
				$data['tenant'] = $this->current_tenant;
				
				$this->load->view('school_admin/auth/login', $data);
			}
		}
	}
	
	/**
	 * Logout
	 *
	 * @return	void
	 */
	public function logout()
	{
		// Destroy session
		$this->session->sess_destroy();
		
		// Redirect to login
		redirect('school-admin/auth/login');
	}
}

