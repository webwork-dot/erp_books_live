<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ERP Admin Users Controller
 *
 * Manages super admin users
 *
 * @package		ERP
 * @subpackage	Controllers
 * @category	Controllers
 * @author		ERP Team
 */

// Load base controller
require_once(APPPATH . 'controllers/Erp_admin/Erp_base.php');

class Users extends Erp_base
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
	}
	
	/**
	 * List all users
	 *
	 * @return	void
	 */
	public function index()
	{
		// Check permission
		if (!$this->hasPermission('users', 'read'))
		{
			show_error('You do not have permission to access this page.', 403);
		}
		
		// Get filters
		$filters = array();
		if ($this->input->get('status'))
		{
			$filters['status'] = $this->input->get('status');
		}
		if ($this->input->get('role_id'))
		{
			$filters['role_id'] = $this->input->get('role_id');
		}
		
		// Pagination
		$per_page = 10;
		$page = (int)$this->input->get('page');
		if ($page < 1) $page = 1;
		$offset = ($page - 1) * $per_page;
		
		// Get total count for pagination
		$total_users = $this->Erp_user_model->getTotalUsers($filters);
		
		// Get users with pagination
		$data['users'] = $this->Erp_user_model->getAllUsers($filters, $per_page, $offset);
		$data['total_users'] = $total_users;
		$data['per_page'] = $per_page;
		$data['current_page'] = $page;
		$data['total_pages'] = ceil($total_users / $per_page);
		$data['title'] = 'Manage Users';
		$data['current_user'] = $this->current_user;
		$data['filters'] = $filters;
		$data['breadcrumb'] = array(
			array('label' => 'Users', 'active' => true)
		);
		
		// Load content view
		$data['content'] = $this->load->view('erp_admin/users/index', $data, TRUE);
		
		// Load main layout
		$this->load->view('erp_admin/layouts/index_template', $data);
	}
	
	/**
	 * Add new user
	 *
	 * @return	void
	 */
	public function add()
	{
		// Check permission
		if (!$this->hasPermission('users', 'create'))
		{
			show_error('You do not have permission to access this page.', 403);
		}
		
		// Set validation rules
		$this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[erp_users.username]');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[erp_users.email]');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
		$this->form_validation->set_rules('role_id', 'Role', 'required|integer');
		
		if ($this->form_validation->run() == FALSE)
		{
			$data['title'] = 'Add New User';
			$data['current_user'] = $this->current_user;
			
			// Load content view
			$data['content'] = $this->load->view('erp_admin/users/add', $data, TRUE);
			
			// Load main layout
			$this->load->view('erp_admin/layouts/index_template', $data);
		}
		else
		{
			// Create user
			$user_data = array(
				'username' => $this->input->post('username'),
				'email' => $this->input->post('email'),
				'password' => $this->input->post('password'),
				'role_id' => $this->input->post('role_id'),
				'status' => $this->input->post('status') ? 1 : 0
			);
			
			if ($this->Erp_user_model->createUser($user_data))
			{
				$this->session->set_flashdata('success', 'User created successfully.');
				redirect('erp-admin/users');
			}
			else
			{
				$this->session->set_flashdata('error', 'Failed to create user.');
				redirect('erp-admin/users/add');
			}
		}
	}
	
	/**
	 * Edit user
	 *
	 * @param	int	$user_id	User ID
	 * @return	void
	 */
	public function edit($user_id)
	{
		// Check permission
		if (!$this->hasPermission('users', 'update'))
		{
			show_error('You do not have permission to access this page.', 403);
		}
		
		// Get user
		$user = $this->Erp_user_model->getUserById($user_id);
		if (!$user)
		{
			show_404();
		}
		
		// Set validation rules
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('role_id', 'Role', 'required|integer');
		
		if ($this->form_validation->run() == FALSE)
		{
			$data['user'] = $user;
			$data['title'] = 'Edit User';
			$data['current_user'] = $this->current_user;
			
			// Load content view
			$data['content'] = $this->load->view('erp_admin/users/edit', $data, TRUE);
			
			// Load main layout
			$this->load->view('erp_admin/layouts/index_template', $data);
		}
		else
		{
			// Update user
			$user_data = array(
				'email' => $this->input->post('email'),
				'role_id' => $this->input->post('role_id'),
				'status' => $this->input->post('status') ? 1 : 0
			);
			
			// Update password if provided
			if ($this->input->post('password'))
			{
				$user_data['password'] = $this->input->post('password');
			}
			
			if ($this->Erp_user_model->updateUser($user_id, $user_data))
			{
				$this->session->set_flashdata('success', 'User updated successfully.');
				redirect('erp-admin/users');
			}
			else
			{
				$this->session->set_flashdata('error', 'Failed to update user.');
				redirect('erp-admin/users/edit/' . $user_id);
			}
		}
	}
	
	/**
	 * Delete user
	 *
	 * @param	int	$user_id	User ID
	 * @return	void
	 */
	public function delete($user_id)
	{
		// Check permission
		if (!$this->hasPermission('users', 'delete'))
		{
			show_error('You do not have permission to access this page.', 403);
		}
		
		// Prevent deleting yourself
		if ($user_id == $this->session->userdata('erp_user_id'))
		{
			$this->session->set_flashdata('error', 'You cannot delete your own account.');
			redirect('erp-admin/users');
		}
		
		// Delete user (soft delete by setting status to 0)
		$user_data = array('status' => 0);
		if ($this->Erp_user_model->updateUser($user_id, $user_data))
		{
			$this->session->set_flashdata('success', 'User deleted successfully.');
		}
		else
		{
			$this->session->set_flashdata('error', 'Failed to delete user.');
		}
		
		redirect('erp-admin/users');
	}
}

