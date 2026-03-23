<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Vendor Customers Controller
 *
 * Handles customer management for vendors
 *
 * @package		ERP
 * @subpackage	Controllers
 * @category	Controllers
 * @author		ERP Team
 */

// Load base controller
require_once(APPPATH . 'controllers/Vendor/Vendor_base.php');

class Customers extends Vendor_base
{
	/**
	 * Constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Customer_model');
	}
	
	/**
	 * Index - Default method, redirects to list
	 *
	 * @return	void
	 */
	public function index()
	{
		$this->list();
	}
	
	/**
	 * List - List all customers with filters
	 *
	 * @return	void
	 */
	public function list()
	{
		$vendor_id = $this->current_vendor['id'];
		
		// Get filter parameters
		$search = $this->input->get('search');
		$status = $this->input->get('status');
		
		// Build filters array
		$filters = array();
		if (!empty($search))
		{
			$filters['search'] = $search;
		}
		if (!empty($status))
		{
			$filters['status'] = $status;
		}
		
		// Pagination
		$per_page = 20;
		$page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
		$offset = ($page - 1) * $per_page;
		
		// Get customers
		$customers = $this->Customer_model->getCustomersByVendor($vendor_id, $filters, $per_page, $offset);
		$total_customers = $this->Customer_model->getTotalCustomersByVendor($vendor_id, $filters);
		
		// Prepare data
		$data['title'] = 'Customers - ' . $this->current_vendor['name'];
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $this->getVendorDomainForUrl();
		$data['customers'] = $customers;
		$data['filters'] = $filters;
		$data['current_page'] = $page;
		$data['total_customers'] = $total_customers;
		$data['per_page'] = $per_page;
		$data['total_pages'] = ceil($total_customers / $per_page);
		$data['breadcrumb'] = array(
			array('label' => 'Dashboard', 'url' => base_url($this->current_vendor['domain'] . '/dashboard')),
			array('label' => 'Customers', 'active' => true)
		);
		
		// Load content view
		$data['content'] = $this->load->view('vendor/customers/list', $data, TRUE);
		
		// Load main layout
		$this->load->view('vendor/layouts/index_template', $data);
	}
	
	/**
	 * Get customer details (AJAX)
	 *
	 * @param	int	$customer_id	Customer/User ID
	 * @return	void
	 */
	public function get_customer_details($customer_id)
	{
		// Get customer details
		$customer = $this->Customer_model->getCustomerDetails($customer_id);
		
		if (!$customer)
		{
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode(array(
					'success' => false,
					'message' => 'Customer not found'
				)));
			return;
		}
		
		// Return JSON response
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array(
				'success' => true,
				'customer' => $customer
			)));
	}
}

