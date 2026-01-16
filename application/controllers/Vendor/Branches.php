<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Vendor Branches Controller
 *
 * Handles branch management for vendors
 *
 * @package		ERP
 * @subpackage	Controllers
 * @category	Controllers
 * @author		ERP Team
 */

// Load base controller
require_once(APPPATH . 'controllers/Vendor/Vendor_base.php');

class Branches extends Vendor_base
{
	/**
	 * Constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Branch_model');
		$this->load->model('School_model');
		$this->load->model('Location_model');
		$this->load->library('form_validation');
	}
	
	/**
	 * List all branches
	 *
	 * @return	void
	 */
	public function index()
	{
		// Get filters
		$filters = array();
		if ($this->input->get('status'))
		{
			$filters['status'] = $this->input->get('status');
		}
		if ($this->input->get('school_id'))
		{
			$filters['school_id'] = $this->input->get('school_id');
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
		$total_branches = $this->Branch_model->getTotalBranchesByVendor($this->current_vendor['id'], $filters);
		
		// Get branches with pagination
		$branches = $this->Branch_model->getBranchesByVendor($this->current_vendor['id'], $filters, $per_page, $offset);
		
		// Get schools for filter dropdown
		$schools = $this->School_model->getSchoolsByVendor($this->current_vendor['id'], array(), NULL, 0);
		
		$data['branches'] = $branches;
		$data['schools'] = $schools;
		$data['total_branches'] = $total_branches;
		$data['per_page'] = $per_page;
		$data['current_page'] = $page;
		$data['total_pages'] = ceil($total_branches / $per_page);
		
		$data['title'] = 'Manage School Branches';
		$data['current_vendor'] = $this->current_vendor;
		$data['filters'] = $filters;
		$data['vendor_domain'] = $this->getVendorDomainForUrl();
		$data['breadcrumb'] = array(
			array('label' => 'Branches', 'active' => true)
		);
		
		// Load content view
		$data['content'] = $this->load->view('vendor/branches/index', $data, TRUE);
		
		// Load main layout
		$this->load->view('vendor/layouts/index_template', $data);
	}
	
	/**
	 * Add new branch
	 *
	 * @return	void
	 */
	public function add()
	{
		// Set validation rules
		$this->form_validation->set_rules('school_id', 'School', 'required|integer');
		$this->form_validation->set_rules('branch_name', 'Branch Name', 'required|trim');
		$this->form_validation->set_rules('address', 'Address', 'required|trim');
		$this->form_validation->set_rules('state_id', 'State', 'required|integer');
		$this->form_validation->set_rules('city_id', 'City', 'required|integer');
		$this->form_validation->set_rules('pincode', 'Pincode', 'required|trim|min_length[6]|max_length[10]');
		
		if ($this->form_validation->run() == FALSE)
		{
			// Get schools and states for dropdown
			$data['schools'] = $this->School_model->getSchoolsByVendor($this->current_vendor['id'], array(), NULL, 0);
			$data['states'] = $this->Location_model->getAllStates();
			$data['cities'] = array(); // Will be populated via AJAX
			
			$data['title'] = 'Add New Branch';
			$data['current_vendor'] = $this->current_vendor;
			$data['vendor_domain'] = $this->getVendorDomainForUrl();
			$data['breadcrumb'] = array(
				array('label' => 'Branches', 'url' => $this->current_vendor['domain'] . '/branches'),
				array('label' => 'Add New', 'active' => true)
			);
			
			// Load content view
			$data['content'] = $this->load->view('vendor/branches/add', $data, TRUE);
			
			// Load main layout
			$this->load->view('vendor/layouts/index_template', $data);
		}
		else
		{
			// Verify school belongs to vendor
			$school = $this->School_model->getSchoolById($this->input->post('school_id'), $this->current_vendor['id']);
			
			if (!$school)
			{
				$this->session->set_flashdata('error', 'Invalid school selected.');
				redirect(base_url($this->current_vendor['domain'] . '/branches/add'));
				return;
			}
			
			// Create branch
			$branch_data = array(
				'school_id' => $this->input->post('school_id'),
				'vendor_id' => $this->current_vendor['id'],
				'branch_name' => $this->input->post('branch_name'),
				'address' => $this->input->post('address'),
				'state_id' => $this->input->post('state_id'),
				'city_id' => $this->input->post('city_id'),
				'pincode' => $this->input->post('pincode'),
				'country_id' => 101, // Default to India
				'status' => 'active'
			);
			
			$branch_id = $this->Branch_model->createBranch($branch_data);
			
			if ($branch_id)
			{

				// Update branch slug
				$branch_updated_data = array(
					'slug' => slugify($this->input->post('branch_name')) . '-' . $branch_id,
				);
				$this->Branch_model->updateBranch($branch_id, $branch_updated_data);

				$this->session->set_flashdata('success', 'Branch created successfully.');
				redirect(base_url($this->current_vendor['domain'] . '/branches'));
			}
			else
			{
				$this->session->set_flashdata('error', 'Failed to create branch.');
				redirect(base_url($this->current_vendor['domain'] . '/branches/add'));
			}
		}
	}
	
	/**
	 * Edit branch
	 *
	 * @param	int	$branch_id	Branch ID
	 * @return	void
	 */
	public function edit($branch_id)
	{
		// Get branch
		$branch = $this->Branch_model->getBranchById($branch_id, $this->current_vendor['id']);
		
		if (!$branch)
		{
			show_404();
		}
		
		// Set validation rules
		$this->form_validation->set_rules('school_id', 'School', 'required|integer');
		$this->form_validation->set_rules('branch_name', 'Branch Name', 'required|trim');
		$this->form_validation->set_rules('address', 'Address', 'required|trim');
		$this->form_validation->set_rules('state_id', 'State', 'required|integer');
		$this->form_validation->set_rules('city_id', 'City', 'required|integer');
		$this->form_validation->set_rules('pincode', 'Pincode', 'required|trim|min_length[6]|max_length[10]');
		$this->form_validation->set_rules('status', 'Status', 'required|in_list[active,inactive]');
		
		if ($this->form_validation->run() == FALSE)
		{
			// Get schools, states, and cities for dropdown
			$data['schools'] = $this->School_model->getSchoolsByVendor($this->current_vendor['id'], array(), NULL, 0);
			$data['states'] = $this->Location_model->getAllStates();
			$data['cities'] = $this->Location_model->getCitiesByState($branch['state_id']);
			
			$data['branch'] = $branch;
			
			$data['title'] = 'Edit Branch';
			$data['current_vendor'] = $this->current_vendor;
			$data['vendor_domain'] = $this->getVendorDomainForUrl();
			$data['breadcrumb'] = array(
				array('label' => 'Branches', 'url' => $this->current_vendor['domain'] . '/branches'),
				array('label' => 'Edit', 'active' => true)
			);
			
			// Load content view
			$data['content'] = $this->load->view('vendor/branches/edit', $data, TRUE);
			
			// Load main layout
			$this->load->view('vendor/layouts/index_template', $data);
		}
		else
		{
			// Verify school belongs to vendor
			$school = $this->School_model->getSchoolById($this->input->post('school_id'), $this->current_vendor['id']);
			
			if (!$school)
			{
				$this->session->set_flashdata('error', 'Invalid school selected.');
				redirect(base_url($this->current_vendor['domain'] . '/branches/edit/' . $branch_id));
				return;
			}
			
			// Update branch
			$branch_data = array(
				'school_id' => $this->input->post('school_id'),
				'branch_name' => $this->input->post('branch_name'),
				'slug' => slugify($this->input->post('branch_name')) . '-' . $branch_id,
				'address' => $this->input->post('address'),
				'state_id' => $this->input->post('state_id'),
				'city_id' => $this->input->post('city_id'),
				'pincode' => $this->input->post('pincode'),
				'status' => $this->input->post('status')
			);
			
			if ($this->Branch_model->updateBranch($branch_id, $branch_data))
			{
				$this->session->set_flashdata('success', 'Branch updated successfully.');
				redirect(base_url($this->current_vendor['domain'] . '/branches'));
			}
			else
			{
				$this->session->set_flashdata('error', 'Failed to update branch.');
				redirect(base_url($this->current_vendor['domain'] . '/branches/edit/' . $branch_id));
			}
		}
	}
	
	/**
	 * Delete branch
	 *
	 * @param	int	$branch_id	Branch ID
	 * @return	void
	 */
	public function delete($branch_id)
	{
		// Get branch to verify ownership
		$branch = $this->Branch_model->getBranchById($branch_id, $this->current_vendor['id']);
		
		if (!$branch)
		{
			show_404();
		}
		
		if ($this->Branch_model->deleteBranch($branch_id, $this->current_vendor['id']))
		{
			$this->session->set_flashdata('success', 'Branch deleted successfully.');
		}
		else
		{
			$this->session->set_flashdata('error', 'Failed to delete branch.');
		}
		
		redirect(base_url($this->current_vendor['domain'] . '/branches'));
	}
	
	/**
	 * Get cities by state (AJAX)
	 *
	 * @return	void
	 */
	public function get_cities()
	{
		// Accept both GET and POST to avoid CSRF issues
		$state_id = $this->input->post('state_id') ? $this->input->post('state_id') : $this->input->get('state_id');
		
		if ($state_id)
		{
			$cities = $this->Location_model->getCitiesByState($state_id);
			header('Content-Type: application/json');
			echo json_encode($cities);
		}
		else
		{
			header('Content-Type: application/json');
			echo json_encode(array());
		}
	}
}

