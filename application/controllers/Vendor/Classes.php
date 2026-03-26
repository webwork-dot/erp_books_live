<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH . 'controllers/Vendor/Vendor_base.php');

/**
 * Vendor Classes Controller
 *
 * @property CI_Loader $load
 * @property CI_Input $input
 * @property CI_Session $session
 * @property Class_model $Class_model
 * @property CI_Form_validation $form_validation
 */
class Classes extends Vendor_base
{
	/**
	 * Constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Class_model');
		$this->load->library('form_validation');
	}
	
	/**
	 * List all classes
	 *
	 * @return	void
	 */
	public function index()
	{
		// Get filters
		$filters = array();
		if ($this->input->get('search'))
		{
			$filters['search'] = $this->input->get('search');
		}
		
		// Pagination
		$per_page = 20;
		$page = (int)$this->input->get('page');
		if ($page < 1) $page = 1;
		$offset = ($page - 1) * $per_page;
		
		// Get total count for pagination
		$total_classes = $this->Class_model->getTotalClasses($filters);
		
		// Get classes with pagination
		$classes = $this->Class_model->getClasses($filters, $per_page, $offset);
		
		$data['classes'] = $classes;
		$data['total_classes'] = $total_classes;
		$data['per_page'] = $per_page;
		$data['current_page'] = $page;
		$data['total_pages'] = ceil($total_classes / $per_page);
		
		$data['title'] = 'Manage Classes';
		$data['current_vendor'] = $this->current_vendor;
		$data['filters'] = $filters;
		$data['vendor_domain'] = $this->getVendorDomainForUrl();
		$data['breadcrumb'] = array(
			array('label' => 'Classes', 'active' => true)
		);
		
		// Load content view
		$data['content'] = $this->load->view('vendor/classes/index', $data, TRUE);
		
		// Load main layout
		$this->load->view('vendor/layouts/index_template', $data);
	}
	
	/**
	 * Add new class
	 *
	 * @return	void
	 */
	public function add()
	{
		// Set validation rules
		$this->form_validation->set_rules('class_name', 'Class Name', 'required|trim');
		
		if ($this->form_validation->run() == FALSE)
		{
			$data['title'] = 'Add New Class';
			$data['current_vendor'] = $this->current_vendor;
			$data['vendor_domain'] = $this->getVendorDomainForUrl();
			$data['breadcrumb'] = array(
				array('label' => 'Classes', 'url' => base_url($this->current_vendor['domain'] . '/classes')),
				array('label' => 'Add New', 'active' => true)
			);
			
			// Load content view
			$data['content'] = $this->load->view('vendor/classes/add', $data, TRUE);
			
			// Load main layout
			$this->load->view('vendor/layouts/index_template', $data);
		}
		else
		{
			// Create class
			$class_data = array(
				'class_name' => $this->input->post('class_name')
			);
			
			$class_id = $this->Class_model->createClass($class_data);
			
			if ($class_id)
			{
				$this->session->set_flashdata('success', 'Class created successfully.');
				redirect(base_url($this->current_vendor['domain'] . '/classes'));
			}
			else
			{
				$this->session->set_flashdata('error', 'Failed to create class.');
				redirect(base_url($this->current_vendor['domain'] . '/classes/add'));
			}
		}
	}
	
	/**
	 * Edit class
	 *
	 * @param	int	$class_id	Class ID
	 * @return	void
	 */
	public function edit($class_id)
	{
		// Get class
		$class = $this->Class_model->getClassById($class_id);
		
		if (!$class)
		{
			show_404();
		}
		
		// Set validation rules
		$this->form_validation->set_rules('class_name', 'Class Name', 'required|trim');
		
		if ($this->form_validation->run() == FALSE)
		{
			$data['class'] = $class;
			
			$data['title'] = 'Edit Class';
			$data['current_vendor'] = $this->current_vendor;
			$data['vendor_domain'] = $this->getVendorDomainForUrl();
			$data['breadcrumb'] = array(
				array('label' => 'Classes', 'url' => base_url($this->current_vendor['domain'] . '/classes')),
				array('label' => 'Edit', 'active' => true)
			);
			
			// Load content view
			$data['content'] = $this->load->view('vendor/classes/edit', $data, TRUE);
			
			// Load main layout
			$this->load->view('vendor/layouts/index_template', $data);
		}
		else
		{
			// Update class
			$class_data = array(
				'class_name' => $this->input->post('class_name')
			);
			
			if ($this->Class_model->updateClass($class_id, $class_data))
			{
				$this->session->set_flashdata('success', 'Class updated successfully.');
				redirect(base_url($this->current_vendor['domain'] . '/classes'));
			}
			else
			{
				$this->session->set_flashdata('error', 'Failed to update class.');
				redirect(base_url($this->current_vendor['domain'] . '/classes/edit/' . $class_id));
			}
		}
	}
	
	/**
	 * Delete class
	 *
	 * @param	int	$class_id	Class ID
	 * @return	void
	 */
	public function delete($class_id)
	{
		if ($this->Class_model->deleteClass($class_id))
		{
			$this->session->set_flashdata('success', 'Class deleted successfully.');
		}
		else
		{
			$this->session->set_flashdata('error', 'Failed to delete class.');
		}
		
		redirect(base_url($this->current_vendor['domain'] . '/classes'));
	}
}
