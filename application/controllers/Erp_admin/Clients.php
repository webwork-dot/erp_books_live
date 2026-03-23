<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ERP Admin Clients Controller
 *
 * Manages clients/vendors in the ERP system
 *
 * @package		ERP
 * @subpackage	Controllers
 * @category	Controllers
 * @author		ERP Team
 */

// Load base controller
require_once(APPPATH . 'controllers/Erp_admin/Erp_base.php');

class Clients extends Erp_base
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
		$this->load->model('Erp_feature_model');
		$this->load->library('form_validation');
		$this->load->library('Tenant');
	}
	
	/**
	 * List all clients
	 *
	 * @return	void
	 */
	public function index()
	{
		// Check permission
		if (!$this->hasPermission('clients', 'read'))
		{
			show_error('You do not have permission to access this page.', 403);
		}
		
		// Get filters
		$filters = array();
		if ($this->input->get('status'))
		{
			$filters['status'] = $this->input->get('status');
		}
		if ($this->input->get('search'))
		{
			$filters['search'] = $this->input->get('search');
		}
		
		// Pagination
		$per_page = 10;
		$page = (int)$this->input->get('page');
		if ($page < 1) $page = 1;
		$offset = ($page - 1) * $per_page;
		
		// Get total count for pagination
		$total_clients = $this->Erp_client_model->getTotalClients($filters);
		
		// Get clients with pagination
		$data['clients'] = $this->Erp_client_model->getAllClients($filters, $per_page, $offset);
		$data['total_clients'] = $total_clients;
		$data['per_page'] = $per_page;
		$data['current_page'] = $page;
		$data['total_pages'] = ceil($total_clients / $per_page);
		$data['title'] = 'Manage Clients';
		$data['current_user'] = $this->current_user;
		$data['filters'] = $filters;
		
		$this->load->view('erp_admin/layouts/header', $data);
		$this->load->view('erp_admin/clients/index', $data);
		$this->load->view('erp_admin/layouts/footer', $data);
	}
	
	/**
	 * Add new client
	 *
	 * @return	void
	 */
	public function add()
	{
		// Check permission
		if (!$this->hasPermission('clients', 'create'))
		{
			show_error('You do not have permission to access this page.', 403);
		}
		
		// Set validation rules
		$this->form_validation->set_rules('name', 'Client Name', 'required|trim');
		$this->form_validation->set_rules('domain', 'Domain', 'required|trim|is_unique[erp_clients.domain]');
		$this->form_validation->set_rules('status', 'Status', 'required|in_list[active,suspended,inactive]');
		
		if ($this->form_validation->run() == FALSE)
		{
			// Show form
			$data['title'] = 'Add New Client';
			$data['current_user'] = $this->current_user;
			
			$this->load->view('erp_admin/layouts/header', $data);
			$this->load->view('erp_admin/clients/add', $data);
			$this->load->view('erp_admin/layouts/footer', $data);
		}
		else
		{
			// Process form
			$client_data = array(
				'name' => $this->input->post('name'),
				'domain' => $this->input->post('domain'),
				'status' => $this->input->post('status')
			);
			
			$client_id = $this->Erp_client_model->createClient($client_data);
			
			if ($client_id)
			{
				// Create client database
				$client = $this->Erp_client_model->getClientById($client_id);
				if ($client && $this->config->item('auto_create_database', 'tenant'))
				{
					// Create database
					if ($this->tenant->createClientDatabase($client['database_name']))
					{
						// Initialize database with template
						$template_path = $this->config->item('database_template_path', 'tenant');
						$this->load->model('Tenant_model');
						$this->Tenant_model->initializeClientDatabase($client['database_name'], $template_path);
					}
				}
				
				$this->session->set_flashdata('success', 'Client created successfully.');
				redirect('erp-admin/clients');
			}
			else
			{
				$this->session->set_flashdata('error', 'Failed to create client.');
				redirect('erp-admin/clients/add');
			}
		}
	}
	
	/**
	 * Edit client
	 *
	 * @param	int	$id	Client ID
	 * @return	void
	 */
	public function edit($id)
	{
		// Check permission
		if (!$this->hasPermission('clients', 'update'))
		{
			show_error('You do not have permission to access this page.', 403);
		}
		
		$client = $this->Erp_client_model->getClientById($id);
		
		if (!$client)
		{
			show_404();
		}
		
		// Set validation rules
		$this->form_validation->set_rules('name', 'Client Name', 'required|trim');
		$this->form_validation->set_rules('domain', 'Domain', 'required|trim|callback_check_domain_unique[' . $id . ']');
		$this->form_validation->set_rules('status', 'Status', 'required|in_list[active,suspended,inactive]');
		
		if ($this->form_validation->run() == FALSE)
		{
			// Show form
			$data['client'] = $client;
			$data['title'] = 'Edit Client';
			$data['current_user'] = $this->current_user;
			
			$this->load->view('erp_admin/layouts/header', $data);
			$this->load->view('erp_admin/clients/edit', $data);
			$this->load->view('erp_admin/layouts/footer', $data);
		}
		else
		{
			// Process form
			$client_data = array(
				'name' => $this->input->post('name'),
				'domain' => $this->input->post('domain'),
				'status' => $this->input->post('status')
			);
			
			if ($this->Erp_client_model->updateClient($id, $client_data))
			{
				$this->session->set_flashdata('success', 'Client updated successfully.');
				redirect('erp-admin/clients');
			}
			else
			{
				$this->session->set_flashdata('error', 'Failed to update client.');
				redirect('erp-admin/clients/edit/' . $id);
			}
		}
	}
	
	/**
	 * Delete client
	 *
	 * @param	int	$id	Client ID
	 * @return	void
	 */
	public function delete($id)
	{
		// Check permission
		if (!$this->hasPermission('clients', 'delete'))
		{
			show_error('You do not have permission to access this page.', 403);
		}
		
		if ($this->Erp_client_model->deleteClient($id))
		{
			$this->session->set_flashdata('success', 'Client deleted successfully.');
		}
		else
		{
			$this->session->set_flashdata('error', 'Failed to delete client.');
		}
		
		redirect('erp-admin/clients');
	}
	
	/**
	 * Manage client features
	 *
	 * @param	int	$id	Client ID
	 * @return	void
	 */
	public function features($id)
	{
		// Check permission
		if (!$this->hasPermission('clients', 'update'))
		{
			show_error('You do not have permission to access this page.', 403);
		}
		
		$client = $this->Erp_client_model->getClientById($id);
		
		if (!$client)
		{
			show_404();
		}
		
		// Handle feature assignment
		if ($this->input->post('assign_features'))
		{
			$features = $this->input->post('features');
			
			// Get all available features
			$all_features = $this->Erp_feature_model->getAllFeatures();
			
			foreach ($all_features as $feature)
			{
				$enabled = isset($features[$feature['id']]) ? TRUE : FALSE;
				$this->Erp_client_model->assignFeature($id, $feature['id'], $enabled);
			}
			
			$this->session->set_flashdata('success', 'Features updated successfully.');
			redirect('erp-admin/clients/features/' . $id);
		}
		
		// Get client features
		$data['client'] = $client;
		$data['client_features'] = $this->Erp_client_model->getClientFeatures($id);
		$data['all_features'] = $this->Erp_feature_model->getAllFeatures();
		$data['title'] = 'Manage Client Features';
		$data['current_user'] = $this->current_user;
		
		$this->load->view('erp_admin/layouts/header', $data);
		$this->load->view('erp_admin/clients/features', $data);
		$this->load->view('erp_admin/layouts/footer', $data);
	}
	
	/**
	 * Check domain uniqueness (callback)
	 *
	 * @param	string	$domain	Domain name
	 * @param	int	$client_id	Client ID (for edit)
	 * @return	bool	TRUE if unique, FALSE otherwise
	 */
	public function check_domain_unique($domain, $client_id)
	{
		$this->db->where('domain', $domain);
		$this->db->where('id !=', $client_id);
		$query = $this->db->get('erp_clients');
		
		if ($query->num_rows() > 0)
		{
			$this->form_validation->set_message('check_domain_unique', 'The {field} field must contain a unique value.');
			return FALSE;
		}
		
		return TRUE;
	}
}

