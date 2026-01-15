<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Vendor Search Controller
 *
 * Handles global search for vendors
 *
 * @package		ERP
 * @subpackage	Controllers
 * @category	Controllers
 * @author		ERP Team
 */

// Load base controller
require_once(APPPATH . 'controllers/Vendor/Vendor_base.php');

class Search extends Vendor_base
{
	/**
	 * Constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Global search
	 *
	 * @return	void
	 */
	public function index()
	{
		$query = $this->input->get('q');
		$query = trim($query);
		
		$results = array(
			'products' => array(),
			'orders' => array(),
			'schools' => array(),
			'customers' => array()
		);
		
		if (!empty($query)) {
			$vendor_id = $this->current_vendor['id'];
			
			// Search products (textbooks) - remove status column if it doesn't exist
			try {
				$this->db->select('id, product_name, sku, isbn');
				$this->db->from('erp_textbooks');
				$this->db->where('vendor_id', $vendor_id);
				$this->db->group_start();
				$this->db->like('product_name', $query);
				$this->db->or_like('sku', $query);
				$this->db->or_like('isbn', $query);
				$this->db->group_end();
				$this->db->limit(10);
				$results['products'] = $this->db->get()->result_array();
			} catch (Exception $e) {
				log_message('error', 'Error searching textbooks: ' . $e->getMessage());
				$results['products'] = array();
			}
			
			// Search orders - use order_status instead of status
			try {
				$this->db->select('id, order_number, customer_name, total_amount, order_status as status');
				$this->db->from('erp_orders');
				$this->db->where('vendor_id', $vendor_id);
				$this->db->group_start();
				$this->db->like('order_number', $query);
				$this->db->or_like('customer_name', $query);
				$this->db->or_like('customer_email', $query);
				$this->db->group_end();
				$this->db->limit(10);
				$results['orders'] = $this->db->get()->result_array();
			} catch (Exception $e) {
				log_message('error', 'Error searching orders: ' . $e->getMessage());
				$results['orders'] = array();
			}
			
			// Search schools - remove status column if it doesn't exist
			try {
				$this->db->select('id, school_name, board_name, email');
				$this->db->from('erp_schools');
				$this->db->where('vendor_id', $vendor_id);
				$this->db->group_start();
				$this->db->like('school_name', $query);
				$this->db->or_like('board_name', $query);
				$this->db->or_like('email', $query);
				$this->db->group_end();
				$this->db->limit(10);
				$school_results = $this->db->get()->result_array();
				// Add status field if it exists, otherwise set to null
				foreach ($school_results as &$school) {
					$school['status'] = isset($school['status']) ? $school['status'] : null;
				}
				$results['schools'] = $school_results;
			} catch (Exception $e) {
				log_message('error', 'Error searching schools: ' . $e->getMessage());
				$results['schools'] = array();
			}
			
			// Search customers - remove status column if it doesn't exist
			try {
				$this->db->select('id, customer_name, email, phone');
				$this->db->from('erp_customers');
				$this->db->where('vendor_id', $vendor_id);
				$this->db->group_start();
				$this->db->like('customer_name', $query);
				$this->db->or_like('email', $query);
				$this->db->or_like('phone', $query);
				$this->db->group_end();
				$this->db->limit(10);
				$customer_results = $this->db->get()->result_array();
				// Add status field if it exists, otherwise set to null
				foreach ($customer_results as &$customer) {
					$customer['status'] = isset($customer['status']) ? $customer['status'] : null;
				}
				$results['customers'] = $customer_results;
			} catch (Exception $e) {
				log_message('error', 'Error searching customers: ' . $e->getMessage());
				$results['customers'] = array();
			}
		}
		
		// Extract base domain for URL generation
		$base_domain = $this->Erp_client_model->extractBaseDomain($this->current_vendor['domain']);
		if (empty($base_domain)) {
			$base_domain = $this->current_vendor['domain'];
		}
		
		$data['title'] = 'Search Results';
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $base_domain; // Pass base domain for URL generation
		$data['query'] = $query;
		$data['results'] = $results;
		$this->load->helper('common');
		$data['breadcrumb'] = array(
			array('label' => 'Dashboard', 'url' => vendor_url('dashboard', $base_domain)),
			array('label' => 'Search', 'active' => true)
		);
		
		// Load content view
		$data['content'] = $this->load->view('vendor/search/index', $data, TRUE);
		
		// Load main layout
		$this->load->view('vendor/layouts/index_template', $data);
	}
}

