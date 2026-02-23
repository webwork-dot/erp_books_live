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

		// Check if filters are provided (for filtered order listing)
		$filters_provided = $this->input->get('date_from') || $this->input->get('date_to') ||
						   $this->input->get('school_id') || $this->input->get('state') ||
						   $this->input->get('city') || $this->input->get('order_type');

		$results = array(
			'products' => array(),
			'orders' => array(),
			'schools' => array(),
			'customers' => array()
		);

		if ($filters_provided) {
			// Handle filtered order listing (from reports)
			$filters = array(
				'date_from' => $this->input->get('date_from'),
				'date_to' => $this->input->get('date_to'),
				'school_id' => $this->input->get('school_id'),
				'state' => $this->input->get('state'),
				'city' => $this->input->get('city'),
				'order_type' => $this->input->get('order_type')
			);
			$results['orders'] = $this->Order_model->get_orders_by_filters($filters);
		} elseif (!empty($query)) {
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
		$data['title'] = $filters_provided ? 'Filtered Orders' : 'Search Results';
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $this->getVendorDomainForUrl(); // Pass vendor domain for URL generation (empty for subdomain routing)
		$data['query'] = $query;
		$data['results'] = $results;
		$data['filters_provided'] = $filters_provided;
		if ($filters_provided) {
			$data['applied_filters'] = array(
				'date_from' => $this->input->get('date_from'),
				'date_to' => $this->input->get('date_to'),
				'school_id' => $this->input->get('school_id'),
				'state' => $this->input->get('state'),
				'city' => $this->input->get('city'),
				'order_type' => $this->input->get('order_type')
			);
		}
		$this->load->helper('common');
		$data['breadcrumb'] = array(
			array('label' => 'Dashboard', 'url' => vendor_url('dashboard', $data['vendor_domain'])),
			array('label' => $filters_provided ? 'Filtered Orders' : 'Search', 'active' => true)
		);
		
		// Load content view
		$data['content'] = $this->load->view('vendor/search/index', $data, TRUE);
		
		// Load main layout
		$this->load->view('vendor/layouts/index_template', $data);
	}

	/**
	 * Export filtered orders to CSV
	 */
	public function export()
	{
		// Check if filters are provided
		$filters_provided = $this->input->get('date_from') || $this->input->get('date_to') ||
						   $this->input->get('school_id') || $this->input->get('state') ||
						   $this->input->get('city') || $this->input->get('order_type');

		if (!$filters_provided) {
			show_404();
			return;
		}

		$filters = array(
			'date_from' => $this->input->get('date_from'),
			'date_to' => $this->input->get('date_to'),
			'school_id' => $this->input->get('school_id'),
			'state' => $this->input->get('state'),
			'city' => $this->input->get('city'),
			'order_type' => $this->input->get('order_type')
		);

		// Get filtered orders
		$orders = $this->Order_model->get_orders_by_filters($filters);

		// Create filename with date
		$filename = 'filtered_orders_' . date('Y-m-d_H-i-s') . '.csv';

		// Set headers for CSV download
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename="' . $filename . '"');

		// Create output stream
		$out = fopen('php://output', 'w');

		// Add header information
		fputcsv($out, array('Filtered Orders Export'));
		fputcsv($out, array('Export Date: ' . date('Y-m-d H:i:s')));

		// Add filter information
		$filter_info = array('Applied Filters:');
		if (!empty($filters['date_from']) && !empty($filters['date_to'])) {
			$filter_info[] = 'Date Range: ' . $filters['date_from'] . ' to ' . $filters['date_to'];
		}
		if (!empty($filters['school_id'])) {
			$filter_info[] = 'School ID: ' . $filters['school_id'];
		}
		if (!empty($filters['state'])) {
			$filter_info[] = 'State: ' . $filters['state'];
		}
		if (!empty($filters['city'])) {
			$filter_info[] = 'City: ' . $filters['city'];
		}
		if (!empty($filters['order_type'])) {
			$filter_info[] = 'Order Type: ' . $filters['order_type'];
		}
		fputcsv($out, $filter_info);
		fputcsv($out, array('Total Orders: ' . count($orders)));
		fputcsv($out, array()); // Empty row

		// Add column headers
		fputcsv($out, array(
			'Order ID',
			'Status',
			'User Name',
			'User Phone',
			'Product Name',
			'Address',
			'School Name',
			'Grade Name',
			'Delivery Type',
			'Date',
			'Payment Method',
			'Invoice No',
			'Courier',
			'Shipping Order ID',
			'AWB No'
		));

		// Add order data
		foreach ($orders as $order) {
			// Format status
			$status_text = '';
			switch ($order['status']) {
				case '1': $status_text = 'New Order'; break;
				case '2': $status_text = 'Processing'; break;
				case '3': $status_text = 'Out for Delivery'; break;
				case '4': $status_text = 'Delivered'; break;
				case '7': $status_text = 'Return'; break;
				default: $status_text = 'Unknown'; break;
			}

			// Format payment method
			$payment_display = $order['payment_method'];
			if ($payment_display == 'payment_at_school' || $payment_display == 'payment_at_scho') {
				$payment_display = 'Payment at School';
			} elseif ($payment_display == 'cod') {
				$payment_display = 'Cash On Delivery';
			} else {
				$payment_display = ucfirst(str_replace('_', ' ', $payment_display));
			}

			// Format delivery type
			$delivery_type = $order['is_deliver_at_school'] ? 'Deliver at School' : 'Deliver at Address';

			// Format courier info
			$courier_display = $order['courier_name'];
			if (!empty($order['ship_order_id']) || !empty($order['awb_no'])) {
				$courier_parts = array();
				if (!empty($order['courier_name']) && $order['courier_name'] != '-') {
					$courier_parts[] = $order['courier_name'];
				}
				if (!empty($order['ship_order_id'])) {
					$courier_parts[] = 'Ship #' . $order['ship_order_id'];
				}
				if (!empty($order['awb_no'])) {
					$courier_parts[] = 'AWB ' . $order['awb_no'];
				}
				$courier_display = !empty($courier_parts) ? implode(' · ', $courier_parts) : '-';
			}

			fputcsv($out, array(
				$order['order_unique_id'],
				$status_text,
				$order['user_name'],
				$order['user_phone'],
				$order['product_name'] ?: '-',
				$order['address'] ?: '-',
				$order['school_name'] ?: '-',
				$order['grade_name'] ?: '-',
				$delivery_type,
				$order['date'],
				$payment_display,
				$order['invoice_no'],
				$courier_display,
				$order['ship_order_id'] ?: '',
				$order['awb_no'] ?: ''
			));
		}

		fclose($out);
		exit;
	}
}

