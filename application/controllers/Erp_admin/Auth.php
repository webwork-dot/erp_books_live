<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ERP Admin Authentication Controller
 *
 * Handles login, logout, and authentication for super admin
 *
 * @package		ERP
 * @subpackage	Controllers
 * @category	Controllers
 * @author		ERP Team
 */
class Auth extends CI_Controller
{
	/**
	 * Constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Erp_user_model');
		$this->load->library('form_validation');
		$this->load->helper('form');
	}
	
	/**
	 * Login page
	 *
	 * @return	void
	 */
	public function login()
	{
		// Check if already logged in
		if ($this->session->userdata('erp_user_id'))
		{
			redirect('erp-admin/dashboard');
		}
		
		// Set validation rules
		$this->form_validation->set_rules('username', 'Username', 'required|trim');
		$this->form_validation->set_rules('password', 'Password', 'required');
		
		if ($this->form_validation->run() == FALSE)
		{
			// Show login form
			$data['title'] = 'ERP Admin Login';
			$data['error'] = '';
			
			$this->load->view('erp_admin/auth/login', $data);
		}
		else
		{
			// Process login
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			
			// Get user
			$user = $this->Erp_user_model->getUserByUsername($username);
			
			// Debug logging (remove in production)
			log_message('debug', 'Login attempt - Username: ' . $username);
			if ($user) {
				log_message('debug', 'User found - ID: ' . $user['id'] . ', Status: ' . $user['status']);
				log_message('debug', 'DB Password Hash: ' . $user['password']);
				log_message('debug', 'DB Password Hash Length: ' . strlen($user['password']));
				log_message('debug', 'Input Password SHA1: ' . sha1($password));
				log_message('debug', 'Password Match: ' . ($this->Erp_user_model->verifyPassword($password, $user['password']) ? 'YES' : 'NO'));
			} else {
				log_message('debug', 'User NOT found in database');
			}
			
			if ($user && $this->Erp_user_model->verifyPassword($password, $user['password']))
			{
				// Set session data
				$session_data = array(
					'erp_user_id' => $user['id'],
					'erp_username' => $user['username'],
					'erp_email' => $user['email'],
					'erp_role_id' => $user['role_id'],
					'erp_role_name' => isset($user['role_name']) ? $user['role_name'] : '',
					'erp_permissions' => isset($user['permissions']) ? $user['permissions'] : array(),
					'erp_logged_in' => TRUE
				);
				
				$this->session->set_userdata($session_data);
				
				// Update last login
				$this->Erp_user_model->updateLastLogin($user['id']);
				
				// Redirect to dashboard
				redirect('erp-admin/dashboard');
			}
			else
			{
				// Invalid credentials
				$data['title'] = 'ERP Admin Login';
				$data['error'] = 'Invalid username or password';
				
				// Add debug info if user found but password wrong
				if ($user) {
					$data['debug_info'] = 'User found but password verification failed. Check log file for details.';
				} else {
					$data['debug_info'] = 'User not found. Make sure admin user exists in database.';
				}
				
				$this->load->view('erp_admin/auth/login', $data);
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
		redirect('erp-admin/auth/login');
	}
}

