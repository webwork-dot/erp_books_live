<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Unified Authentication Controller
 *
 * Handles login for all user types (Admin, Vendor, etc.)
 * Automatically detects user type and redirects to appropriate dashboard
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
		$this->load->model('Erp_client_model');
		$this->load->library('form_validation');
		$this->load->helper('url');
	}
	
	/**
	 * Unified login page
	 *
	 * @return	void
	 */
	public function login()
	{
		// Check if already logged in and redirect based on user type
		if ($this->session->userdata('erp_logged_in'))
		{
			$this->redirectBasedOnUserType();
			return;
		}
		
		if ($this->session->userdata('vendor_logged_in'))
		{
			$vendor_domain = $this->session->userdata('vendor_domain');
			if ($vendor_domain)
			{
				redirect($vendor_domain . '/dashboard', 'refresh');
			}
			else
			{
				redirect('erp-admin/dashboard', 'refresh');
			}
			return;
		}
		
		// Get vendor domain from URL if accessing via vendor domain
		$vendor_domain_from_url = $this->uri->segment(1);
		$reserved_routes = array('erp-admin', 'api', 'frontend', 'vendor', 'Vendor', 'auth', 'client-admin', 'school-admin');
		$is_vendor_domain = !empty($vendor_domain_from_url) && !in_array($vendor_domain_from_url, $reserved_routes);
		
		// If this is a vendor domain route, verify vendor exists
		if ($is_vendor_domain)
		{
			$vendor = $this->Erp_client_model->getClientByDomain($vendor_domain_from_url);
			if (!$vendor)
			{
				show_404('Vendor not found: ' . $vendor_domain_from_url);
				return;
			}
			// Store vendor info for the login form
			$data['vendor'] = $vendor;
		}
		
		// Set validation rules
		$this->form_validation->set_rules('username', 'Username', 'required|trim');
		$this->form_validation->set_rules('password', 'Password', 'required');
		
		if ($this->form_validation->run() == FALSE)
		{
			// Show login form
			$data['title'] = 'Login';
			$data['vendor_domain'] = $is_vendor_domain ? $vendor_domain_from_url : null;
			$this->load->view('auth/login', $data);
		}
		else
		{
			// Process login
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			
			// First, check in erp_users table
			$user = $this->Erp_user_model->getUserByUsername($username);
			
			if ($user && $this->Erp_user_model->verifyPassword($password, $user['password']))
			{
				// User found in erp_users - check role
				$role_name = isset($user['role_name']) ? strtolower($user['role_name']) : '';
				
				if ($role_name === 'vendor')
				{
					// This is a vendor user - get vendor info from erp_clients
					$vendor = $this->Erp_client_model->getClientByUsername($username);
					
					// If vendor domain is in URL, verify it matches
					if ($is_vendor_domain && $vendor && $vendor['domain'] !== $vendor_domain_from_url)
					{
						$data['title'] = 'Login';
						$data['error'] = 'Invalid vendor domain. Please use the correct vendor login URL.';
						$this->load->view('auth/login', $data);
						return;
					}
					
					if ($vendor && $vendor['status'] === 'active')
					{
						// Set vendor session
						$session_data = array(
							'vendor_id' => $vendor['id'],
							'vendor_name' => $vendor['name'],
							'vendor_domain' => $vendor['domain'],
							'vendor_username' => $vendor['username'],
							'vendor_logged_in' => TRUE,
							'user_type' => 'vendor',
							'erp_user_id' => $user['id'], // Also store erp_user_id for reference
							'erp_role_name' => 'vendor'
						);
						
						$this->session->set_userdata($session_data);
						
						// Update last login
						$this->Erp_user_model->updateLastLogin($user['id']);
						
						// Redirect to vendor dashboard with domain URL
						redirect($vendor['domain'] . '/dashboard', 'refresh');
						return;
					}
					else
					{
						// Vendor account not active or not found
						$data['title'] = 'Login';
						$data['error'] = 'Your vendor account is not active. Please contact administrator.';
						$this->load->view('auth/login', $data);
						return;
					}
				}
				else
				{
					// This is an admin or other user type
					// Set ERP admin session
					$session_data = array(
						'erp_user_id' => $user['id'],
						'erp_username' => $user['username'],
						'erp_email' => $user['email'],
						'erp_role_id' => $user['role_id'],
						'erp_role_name' => isset($user['role_name']) ? $user['role_name'] : '',
						'erp_permissions' => isset($user['permissions']) ? $user['permissions'] : array(),
						'erp_logged_in' => TRUE,
						'user_type' => 'admin'
					);
					
					$this->session->set_userdata($session_data);
					
					// Update last login
					$this->Erp_user_model->updateLastLogin($user['id']);
					
					// Redirect to admin dashboard
					redirect('erp-admin/dashboard', 'refresh');
					return;
				}
			}
			else
			{
				// User not found in erp_users - check if it's a vendor login (legacy)
				// Check in erp_clients table
				$vendor = $this->Erp_client_model->getClientByUsername($username);
				
				if ($vendor && $vendor['status'] === 'active' && $this->Erp_client_model->verifyPassword($password, $vendor['password']))
				{
					// Legacy vendor login - set vendor session
					$session_data = array(
						'vendor_id' => $vendor['id'],
						'vendor_name' => $vendor['name'],
						'vendor_domain' => $vendor['domain'],
						'vendor_username' => $vendor['username'],
						'vendor_logged_in' => TRUE,
						'user_type' => 'vendor'
					);
					
					$this->session->set_userdata($session_data);
					
					// Redirect to vendor dashboard
					redirect($vendor['domain'] . '/dashboard', 'refresh');
					return;
				}
				else
				{
					// Invalid credentials
					$data['title'] = 'Login';
					$data['error'] = 'Invalid username or password.';
					$this->load->view('auth/login', $data);
				}
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
		$user_type = $this->session->userdata('user_type');
		$vendor_domain = $this->session->userdata('vendor_domain');
		
		// Destroy all session data
		$this->session->sess_destroy();
		
		// Redirect based on user type
		if ($user_type === 'vendor' && $vendor_domain)
		{
			$this->session->set_flashdata('success', 'You have been logged out successfully.');
			redirect($vendor_domain . '/login', 'refresh');
		}
		else
		{
			$this->session->set_flashdata('success', 'You have been logged out successfully.');
			redirect('auth/login', 'refresh');
		}
	}
	
	/**
	 * Redirect based on user type
	 *
	 * @return	void
	 */
	private function redirectBasedOnUserType()
	{
		$user_type = $this->session->userdata('user_type');
		
		if ($user_type === 'vendor')
		{
			$vendor_domain = $this->session->userdata('vendor_domain');
			if ($vendor_domain)
			{
				redirect($vendor_domain . '/dashboard', 'refresh');
			}
			else
			{
				redirect('erp-admin/dashboard', 'refresh');
			}
		}
		else
		{
			redirect('erp-admin/dashboard', 'refresh');
		}
	}
}

