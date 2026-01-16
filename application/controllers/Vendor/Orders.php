<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Vendor Orders Controller
 *
 * Handles order management for vendors
 *
 * @package		ERP
 * @subpackage	Controllers
 * @category	Controllers
 * @author		ERP Team
 */

// Load base controller
require_once(APPPATH . 'controllers/Vendor/Vendor_base.php');

class Orders extends Vendor_base
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
		$this->load->model('School_model');
	}
	
	/**
	 * Index - List all orders with filters
	 *
	 * @return	void
	 */
	public function index()
	{
		$vendor_id = $this->current_vendor['id'];
		
		// Get filter parameters
		$filter_payment = $this->input->get('payment_status');
		$filter_order = $this->input->get('order_status');
		$search = $this->input->get('search');
		
		// Build filters array
		$filters = array();
		if (!empty($filter_payment))
		{
			$filters['payment_status'] = $filter_payment;
		}
		if (!empty($filter_order))
		{
			$filters['order_status'] = $filter_order;
		}
		if (!empty($search))
		{
			$filters['search'] = $search;
		}
		
		// Pagination
		$per_page = 20;
		$page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
		$offset = ($page - 1) * $per_page;
		
		// Get orders
		$orders = $this->Order_model->getOrdersByVendor($vendor_id, $filters, $per_page, $offset);
		$total_orders = $this->Order_model->getTotalOrdersByVendor($vendor_id, $filters);
		
		// Get statistics
		$statistics = $this->Order_model->getOrderStatistics($vendor_id);
		
		// Prepare data
		$data['title'] = 'Orders - ' . $this->current_vendor['name'];
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $this->getVendorDomainForUrl();
		$data['orders'] = $orders;
		$data['statistics'] = $statistics;
		$data['filters'] = $filters;
		$data['current_page'] = $page;
		$data['total_orders'] = $total_orders;
		$data['per_page'] = $per_page;
		$data['total_pages'] = ceil($total_orders / $per_page);
		$data['breadcrumb'] = array(
			array('label' => 'Dashboard', 'url' => base_url($this->current_vendor['domain'] . '/dashboard')),
			array('label' => 'Orders', 'active' => true)
		);
		
		// Load content view
		$data['content'] = $this->load->view('vendor/orders/index', $data, TRUE);
		
		// Load main layout
		$this->load->view('vendor/layouts/index_template', $data);
	}
	
	/**
	 * Get order details (AJAX)
	 *
	 * @param	int	$order_id	Order ID
	 * @return	void
	 */
	public function get_order_details($order_id)
	{
		$vendor_id = $this->current_vendor['id'];
		
		// Get order details
		$order = $this->Order_model->getOrderById($order_id, $vendor_id);
		
		if (!$order)
		{
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode(array(
					'success' => false,
					'message' => 'Order not found'
				)));
			return;
		}
		
		// Return JSON response
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array(
				'success' => true,
				'order' => $order
			)));
	}
}

