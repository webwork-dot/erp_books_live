<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Vendor Authentication Controller
 *
 * Handles vendor login/logout
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
		$this->load->model('Erp_client_model');
		$this->load->library('form_validation');
		$this->load->helper('url');
	}
	
	/**
	 * Vendor login page
	 *
	 * @return	void
	 */
	public function login()
	{
		// Check if already logged in
		if ($this->session->userdata('vendor_logged_in'))
		{
			// Redirect to dashboard (no vendor domain in URL)
			redirect('dashboard');
		}
		
		// Get vendor domain from URL (first segment)
		$vendor_domain = $this->uri->segment(1);
		
		// Skip if it's a reserved route
		$reserved_routes = array('erp-admin', 'api', 'frontend', 'vendor', 'Vendor');
		if (empty($vendor_domain) || in_array($vendor_domain, $reserved_routes))
		{
			show_404();
		}
		
		// Check if vendor exists
		$vendor = $this->Erp_client_model->getClientByDomain($vendor_domain);
		
		if (!$vendor)
		{
			show_404('Vendor not found');
		}
		
		// Set validation rules
		$this->form_validation->set_rules('username', 'Username', 'required|trim');
		$this->form_validation->set_rules('password', 'Password', 'required');
		
		if ($this->form_validation->run() == FALSE)
		{
			$data['vendor'] = $vendor;
			$data['title'] = 'Vendor Login - ' . $vendor['name'];
			$this->load->view('vendor/auth/login', $data);
		}
		else
		{
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			
			// Verify credentials
			if ($vendor['username'] === $username && $this->Erp_client_model->verifyPassword($password, $vendor['password']))
			{
				// Set session
				$session_data = array(
					'vendor_id' => $vendor['id'],
					'vendor_name' => $vendor['name'],
					'vendor_domain' => $vendor['domain'],
					'vendor_username' => $vendor['username'],
					'vendor_logged_in' => TRUE
				);
				
				$this->session->set_userdata($session_data);
				
				// Redirect to dashboard (no vendor domain in URL)
				redirect('dashboard', 'refresh');
			}
			else
			{
				$this->session->set_flashdata('error', 'Invalid username or password.');
				redirect($vendor_domain . '/login', 'refresh');
			}
		}
	}
	
	/**
	 * Vendor logout
	 *
	 * @return	void
	 */
	public function logout()
	{
		$vendor_domain = $this->session->userdata('vendor_domain');
		
		$this->session->unset_userdata(array(
			'vendor_id',
			'vendor_name',
			'vendor_domain',
			'vendor_username',
			'vendor_logged_in'
		));
		
		$this->session->set_flashdata('success', 'You have been logged out successfully.');
		
		// Redirect to login page (no vendor domain in URL)
		redirect('auth/login', 'refresh');
	}
}

