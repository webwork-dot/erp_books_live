<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Client Admin Authentication Controller
 *
 * Handles login and logout for client admin
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
		$this->load->model('Client_user_model');
		$this->load->library('form_validation');
		$this->load->library('Tenant');
		
		// Get current tenant
		$this->current_tenant = $this->tenant->getClient();
		
		if (!$this->current_tenant)
		{
			show_error('Tenant not found. Please contact administrator.', 500);
		}
	}
	
	/**
	 * Login page
	 *
	 * @return	void
	 */
	public function login()
	{
		// Check if already logged in
		if ($this->session->userdata('client_user_id'))
		{
			redirect('client-admin/dashboard');
		}
		
		// Set validation rules
		$this->form_validation->set_rules('username', 'Username', 'required|trim');
		$this->form_validation->set_rules('password', 'Password', 'required');
		
		if ($this->form_validation->run() == FALSE)
		{
			// Show login form
			$data['title'] = 'Client Admin Login';
			$data['error'] = '';
			$data['tenant'] = $this->current_tenant;
			
			$this->load->view('client_admin/auth/login', $data);
		}
		else
		{
			// Process login
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			
			// Get user
			$user = $this->Client_user_model->getUserByUsername($username);
			
			if ($user && $this->Client_user_model->verifyPassword($password, $user['password']))
			{
				// Set session data
				$session_data = array(
					'client_user_id' => $user['id'],
					'client_username' => $user['username'],
					'client_email' => $user['email'],
					'client_role_id' => isset($user['role_id']) ? $user['role_id'] : NULL,
					'client_logged_in' => TRUE
				);
				
				$this->session->set_userdata($session_data);
				
				// Update last login
				$this->Client_user_model->updateLastLogin($user['id']);
				
				// Redirect to dashboard
				redirect('client-admin/dashboard');
			}
			else
			{
				// Invalid credentials
				$data['title'] = 'Client Admin Login';
				$data['error'] = 'Invalid username or password';
				$data['tenant'] = $this->current_tenant;
				
				$this->load->view('client_admin/auth/login', $data);
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
		redirect('client-admin/auth/login');
	}
}

