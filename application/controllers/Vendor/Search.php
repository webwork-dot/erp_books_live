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
		$this->load->model('Order_model');
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
			
			// First: search orders by order number, shipping number, AWB, or customer phone (tbl_order_details)
			$order_matches = $this->Order_model->find_order_by_search($query);
			if (!empty($order_matches)) {
				// If exactly one order found, redirect to order details
				if (count($order_matches) === 1) {
					redirect('orders/view/' . $order_matches[0]['order_unique_id']);
					return;
				}
				// Multiple orders: get full order list data (product_name, address, school, courier, etc.)
				$order_ids = array_column($order_matches, 'id');
				$results['orders'] = $this->Order_model->get_orders_for_search_display($order_ids);
			}
			
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
			
			// Search schools (erp_schools has school_board, admin_email - not board_name, email)
			try {
				$this->db->select('erp_schools.id, erp_schools.school_name, erp_schools.school_board as board_name, erp_schools.admin_email as email, erp_schools.status');
				$this->db->from('erp_schools');
				$this->db->where('erp_schools.vendor_id', $vendor_id);
				$this->db->group_start();
				$this->db->like('erp_schools.school_name', $query);
				$this->db->or_like('erp_schools.school_board', $query);
				$this->db->or_like('erp_schools.admin_email', $query);
				$this->db->group_end();
				$this->db->limit(10);
				$school_results = $this->db->get()->result_array();
				$results['schools'] = $school_results;
			} catch (Exception $e) {
				log_message('error', 'Error searching schools: ' . $e->getMessage());
				$results['schools'] = array();
			}
			
			// Search customers (uses users table like Customer_model - username, firm_name, email, phone_number)
			try {
				$this->db->select('users.id, users.username, users.firm_name, users.email, users.phone_number, users.status');
				$this->db->from('users');
				if ($this->db->field_exists('vendor_id', 'users')) {
					$this->db->where('users.vendor_id', $vendor_id);
				}
				$this->db->group_start();
				$this->db->like('users.username', $query);
				$this->db->or_like('users.firm_name', $query);
				$this->db->or_like('users.email', $query);
				$this->db->or_like('users.phone_number', $query);
				$this->db->group_end();
				$this->db->limit(10);
				$customer_results = $this->db->get()->result_array();
				foreach ($customer_results as &$c) {
					$c['customer_name'] = !empty($c['firm_name']) ? $c['firm_name'] : ($c['username'] ?? '');
					$c['phone'] = $c['phone_number'] ?? '';
					$c['status'] = isset($c['status']) && $c['status'] == 1 ? 'active' : 'inactive';
				}
				$results['customers'] = $customer_results;
			} catch (Exception $e) {
				log_message('error', 'Error searching customers: ' . $e->getMessage());
				$results['customers'] = array();
			}
		}
		
		// Extract base domain for URL generation
		$data['title'] = 'Search Results';
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $this->getVendorDomainForUrl(); // Pass vendor domain for URL generation (empty for subdomain routing)
		$data['query'] = $query;
		$data['results'] = $results;
		$this->load->helper('common');
		$data['breadcrumb'] = array(
			array('label' => 'Dashboard', 'url' => vendor_url('dashboard', $data['vendor_domain'])),
			array('label' => 'Search', 'active' => true)
		);
		
		// Load content view
		$data['content'] = $this->load->view('vendor/search/index', $data, TRUE);
		
		// Load main layout
		$this->load->view('vendor/layouts/index_template', $data);
	}
}

