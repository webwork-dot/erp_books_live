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
		$this->load->model('Uniform_model');
	}
	
	/**
	 * Get pagination configuration
	 *
	 * @param	string	$base_url	Base URL for pagination links
	 * @param	int	$total_rows	Total number of rows
	 * @param	int	$per_page	Items per page
	 * @return	array	Pagination configuration array
	 */
	private function get_pagination_config($base_url, $total_rows, $per_page)
	{
		$config = array();
		
		$config['base_url'] = $base_url;
		$config['total_rows'] = $total_rows;
		$config['per_page'] = $per_page;
		$config['page_query_string'] = TRUE;
		$config['query_string_segment'] = 'page';
		$config['use_page_numbers'] = TRUE;
		$config['reuse_query_string'] = TRUE;
		
		$config['full_tag_open'] = '<ul class="pagination justify-content-center">';
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = '<span aria-hidden="true">&laquo;&laquo;</span>';
		$config['last_link'] = '<span aria-hidden="true">&raquo;&raquo;</span>';
		$config['first_tag_open'] = '<li class="page-item">';
		$config['first_tag_close'] = '</li>';
		$config['prev_link'] = '<span aria-hidden="true">&laquo;</span>';
		$config['prev_tag_open'] = '<li class="page-item">';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = '<span aria-hidden="true">&raquo;</span>';
		$config['next_tag_open'] = '<li class="page-item">';
		$config['next_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li class="page-item">';
		$config['last_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="page-item">';
		$config['num_tag_close'] = '</li>';
		$config['attributes'] = array('class' => 'page-link');
		
		return $config;
	}
	 
	/**
	 * Index - List all orders with filters
	 *
	 * @param	string	$param1	Order status (pending, processing, out_for_delivery, delivered, return)
	 * @param	string	$param2	Additional parameter (not used)
	 * @return	void
	 */
	public function index($param1 = "", $param2 = "")
	{
		$vendor_id = $this->current_vendor['id'];
		
		// Get filter parameters
		$filter_data['date_range'] = $this->input->get('date_range');
		$filter_data['machine']   = $this->input->get('machine');
		$filter_data['keywords']  = $this->input->get('keywords');
		$filter_data['order_status']  = ($param1 != "" ? $param1 : 'all');
		$page_data['order_status']  = $filter_data['order_status'];

		// Get total count and orders using new methods
		$total_count = $this->Order_model->get_paginated_orders_count($vendor_id, $filter_data);
		$page_data['total_count'] = $total_count;
		
		// Get order counts for each status (for tabs)
		$page_data['order_counts'] = $this->Order_model->get_order_status_counts($vendor_id);
		
		// Pagination setup
		$per_page = 10;
		$page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
		if ($page < 1) $page = 1;
		$offset = ($page - 1) * $per_page;
		
		// Get orders
		$page_data['order_list'] = $this->Order_model->get_paginated_orders($vendor_id, $filter_data, $per_page, $offset);
		
		// Update seen status (if common_model exists)
		// $data_update = array('is_seen' => 1);
		// $this->load->model('common_model');
		// $this->common_model->updateByids($data_update, array('is_seen' => 0), 'tbl_order_details');

		// Setup pagination
		$this->load->library('pagination');
		$pagination_config = $this->get_pagination_config(
			base_url($this->current_vendor['domain'] . '/orders/' . $param1),
			$total_count,
			$per_page
		);
		
		$this->pagination->initialize($pagination_config);
		$page_data['pagination'] = $this->pagination->create_links();

		// Prepare page data
		$page_data['page_name']    = 'orders';
		$page_data['page_title']   = 'Orders';
		$page_data['current_page'] = 'Orders';
		$page_data['navigate']     = 'Orders';
		$page_data['current_vendor'] = $this->current_vendor;
		$page_data['vendor_domain'] = $this->current_vendor['domain'];
		$page_data['filter_data'] = $filter_data;
		
		// Load content view
		$data['title'] = 'Orders - ' . $this->current_vendor['name'];
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $this->current_vendor['domain'];
		$data['content'] = $this->load->view('vendor/orders/index', $page_data, TRUE);
		
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
	
	/**
	 * View order details
	 *
	 * @param	string	$order_no	Order unique ID
	 * @return	void
	 */
	public function view($order_no)
	{
		// Get order details using get_order method
		$order_data = $this->Order_model->get_order($order_no);
		
		if (!$order_data)
		{
			show_error('Order not found', 404);
			return;
		}
		
		// Verify order belongs to vendor through order items
		$order_id = $order_data[0]->id;
		
		
		// Get order items
		$items_arr = $this->db->select('*')
			->from('tbl_order_items')
			->where('order_id', $order_id)
			->order_by('id', 'ASC')
			->get()
			->result();
		
		// Add school and branch information for each item from erp_uniforms
		foreach ($items_arr as $item) {
			$item->school_name = '';
			$item->branch_name = '';
			$item->size_name = '';
			
			if (!empty($item->product_id)) {
				// Check if it's a uniform (has erp_uniforms table with school_id and branch_id)
				$uniform_query = $this->db->query("SELECT u.school_id, u.branch_id, usp.size_id FROM erp_uniforms u LEFT JOIN erp_uniform_size_prices usp ON u.id = usp.uniform_id WHERE u.id = '" . (int)$item->product_id . "' LIMIT 1");
				if ($uniform_query->num_rows() > 0) {
					$uniform = $uniform_query->row();
					
					// Get school name if school_id exists
					if (!empty($uniform->school_id)) {
						$school_query = $this->db->query("SELECT school_name FROM erp_schools WHERE id = '" . (int)$uniform->school_id . "' LIMIT 1");
						if ($school_query->num_rows() > 0) {
							$item->school_name = $school_query->row()->school_name;
						}
					}
					
					// Get branch name if branch_id exists
					if (!empty($uniform->branch_id)) {
						$branch_query = $this->db->query("SELECT branch_name FROM erp_school_branches WHERE id = '" . (int)$uniform->branch_id . "' LIMIT 1");
						if ($branch_query->num_rows() > 0) {
							$item->branch_name = $branch_query->row()->branch_name;
						}
					}
					
					// Get size name if size_id exists
					if (!empty($uniform->size_id)) {
						$size_query = $this->db->query("SELECT name FROM erp_sizes WHERE id = '" . (int)$uniform->size_id . "' LIMIT 1");
						if ($size_query->num_rows() > 0) {
							$item->size_name = $size_query->row()->name;
						}
					}
				} else {
					// Check if it's a regular product (has product_variations table)
					$variation_query = $this->db->query("SELECT pvar.size FROM products p INNER JOIN product_variations pvar ON p.id = pvar.product_id WHERE p.id = '" . (int)$item->product_id . "' LIMIT 1");
					if ($variation_query->num_rows() > 0) {
						$variation = $variation_query->row();
						if (!empty($variation->size)) {
							// Get size name from oc_attribute_values
							$size_query = $this->db->query("SELECT name FROM oc_attribute_values WHERE attribute_id = '" . (int)$variation->size . "' LIMIT 1");
							if ($size_query->num_rows() > 0) {
								$item->size_name = $size_query->row()->name;
							}
						}
					}
				}
			}
		}
		
		// Get order addresses (billing and shipping)
		$address_arr = $this->db->select('*')
			->from('tbl_order_address')
			->where('order_id', $order_id)
			->order_by('id', 'ASC')
			->get()
			->result();
		
		// If no addresses found, create default from order data
		if (empty($address_arr))
		{
			$default_address = new stdClass();
			$default_address->name = $order_data[0]->user_name;
			$default_address->mobile_no = $order_data[0]->user_phone;
			$default_address->address = '';
			$default_address->city = '';
			$default_address->state = '';
			$default_address->country = 'India';
			$default_address->pincode = '';
			$default_address->landmark = '';
			$address_arr = array($default_address);
		}
		
		// Prepare page data
		$data['title'] = 'Order Details - ' . $order_no;
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $this->current_vendor['domain'];
		$data['order_data'] = $order_data;
		$data['items_arr'] = $items_arr;
		$data['address_arr'] = $address_arr;
		$data['current_page'] = 'Order Details';
		
		// Load content view
		$data['content'] = $this->load->view('vendor/orders/view', $data, TRUE);
		
		// Load main layout
		$this->load->view('vendor/layouts/index_template', $data);
	}
	
	/**
	 * Move orders to processing status
	 *
	 * @return	void
	 */
	public function move_to_processing()
	{
		
		$order_ids = $this->input->post('order_id');
		
		if (empty($order_ids) || !is_array($order_ids))
		{
			$this->session->set_flashdata('error', 'No orders selected.');
			redirect($this->current_vendor['domain'] . '/orders/pending');
			return;
		}
		
		// Update orders status from 1 (pending) to 2 (processing) and set processing_date
		$processing_date = date("Y-m-d H:i:s");
		$updated_count = 0;
		
		foreach ($order_ids as $order_id)
		{
			// Verify order belongs to vendor (check through order items)
			$order_check = $this->db->query("SELECT od.id FROM tbl_order_details od 
				INNER JOIN tbl_order_items oi ON oi.order_id = od.id 
				WHERE od.id = ? AND od.order_status = '1'", 
				array($order_id))->row();
			
			if ($order_check)
			{
				$this->db->where('id', $order_id);
				$this->db->where('order_status', '1'); // Only update pending orders
				$this->db->update('tbl_order_details', array(
					'order_status' => '2',
					'processing_date' => $processing_date
				));
				
				if ($this->db->affected_rows() > 0)
				{
					$updated_count++;
				}
			}
		}
		
		if ($updated_count > 0)
		{
			$this->session->set_flashdata('success', $updated_count . ' order(s) moved to processing successfully.');

			echo json_encode([
				'status' => '200',
				'message' => $updated_count . ' order(s) moved to processing successfully.',
				'url' => base_url($this->current_vendor['domain'] . '/orders/pending')
			]);
		}
		else
		{
			$this->session->set_flashdata('error', 'No orders were updated. Please ensure orders are in pending status.');
			
			echo json_encode([
				'status' => '400',
				'message' => 'No orders were updated. Please ensure orders are in pending status.',
			]);
		}
	}
	
	/**
	 * Move orders to out for delivery status
	 *
	 * @return	void
	 */
	public function move_to_out_for_delivery()
	{
		
		$order_ids = $this->input->post('order_id');
		
		if (empty($order_ids) || !is_array($order_ids))
		{
			$this->session->set_flashdata('error', 'No orders selected.');
			redirect($this->current_vendor['domain'] . '/orders/processing');
			return;
		}
		
		// Update orders status from 2 (processing) to 3 (out_for_delivery) and set shipment_date
		$shipment_date = date("Y-m-d H:i:s");
		$updated_count = 0;
		
		foreach ($order_ids as $order_id)
		{
			// Verify order belongs to vendor (check through order items)
			$order_check = $this->db->query("SELECT od.id FROM tbl_order_details od 
				INNER JOIN tbl_order_items oi ON oi.order_id = od.id 
				WHERE od.id = ? AND od.order_status = '2'", 
				array($order_id))->row();
			
			if ($order_check)
			{
				$this->db->where('id', $order_id);
				$this->db->where('order_status', '2'); // Only update processing orders
				$this->db->update('tbl_order_details', array(
					'order_status' => '3',
					'shipment_date' => $shipment_date
				));
				
				if ($this->db->affected_rows() > 0)
				{
					$updated_count++;
				}
			}
		}
		
		if ($updated_count > 0)
		{
			$this->session->set_flashdata('success', $updated_count . ' order(s) moved to out for delivery successfully.');

			echo json_encode([
				'status' => '200',
				'message' => $updated_count . ' order(s) moved to out for delivery successfully.',
				'url' => base_url($this->current_vendor['domain'] . '/orders/processing')
			]);
		}
		else
		{
			$this->session->set_flashdata('error', 'No orders were updated. Please ensure orders are in processing status.');
			
			echo json_encode([
				'status' => '400',
				'message' => 'No orders were updated. Please ensure orders are in processing status.',
			]);
		}
	}
	
	/**
	 * Move orders to delivered status
	 *
	 * @return	void
	 */
	public function move_to_delivered()
	{
		
		$order_ids = $this->input->post('order_id');
		
		if (empty($order_ids) || !is_array($order_ids))
		{
			$this->session->set_flashdata('error', 'No orders selected.');
			redirect($this->current_vendor['domain'] . '/orders/out_for_delivery');
			return;
		}
		
		// Update orders status from 3 (out_for_delivery) to 4 (delivered) and set delivery_date
		$delivery_date = date("Y-m-d H:i:s");
		$updated_count = 0;
		
		foreach ($order_ids as $order_id)
		{
			// Verify order belongs to vendor (check through order items)
			$order_check = $this->db->query("SELECT od.id FROM tbl_order_details od 
				INNER JOIN tbl_order_items oi ON oi.order_id = od.id 
				WHERE od.id = ? AND od.order_status = '3'", 
				array($order_id))->row();
			
			if ($order_check)
			{
				$this->db->where('id', $order_id);
				$this->db->where('order_status', '3'); // Only update out_for_delivery orders
				$this->db->update('tbl_order_details', array(
					'order_status' => '4',
					'delivery_date' => $delivery_date
				));
				
				if ($this->db->affected_rows() > 0)
				{
					$updated_count++;
				}
			}
		}
		
		if ($updated_count > 0)
		{
			$this->session->set_flashdata('success', $updated_count . ' order(s) moved to delivered successfully.');

			echo json_encode([
				'status' => '200',
				'message' => $updated_count . ' order(s) moved to delivered successfully.',
				'url' => base_url($this->current_vendor['domain'] . '/orders/out_for_delivery')
			]);
		}
		else
		{
			$this->session->set_flashdata('error', 'No orders were updated. Please ensure orders are in out for delivery status.');
			
			echo json_encode([
				'status' => '400',
				'message' => 'No orders were updated. Please ensure orders are in out for delivery status.',
			]);
		}
	}
	
	/**
	 * Pending Orders - List orders with pending/failed payment status
	 *
	 * @return	void
	 */
	public function pending_orders()
	{
		// Get filter parameters
		$filter_data['date_range'] = $this->input->get('date_range');
		$filter_data['machine']   = $this->input->get('machine');
		$filter_data['keywords']  = $this->input->get('keywords');

		// Get total count and orders using new methods
		$total_count = $this->Order_model->get_paginated_pending_order_count($filter_data);
		$page_data['total_count'] = $total_count;
		
		// Get order counts for each status (for tabs)
		$vendor_id = $this->current_vendor['id'];
		$page_data['order_counts'] = $this->Order_model->get_order_status_counts($vendor_id);
		$page_data['order_status'] = 'pending';
		
		// Pagination setup
		$per_page = 10;
		$page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
		if ($page < 1) $page = 1;
		$offset = ($page - 1) * $per_page;
		
		// Get orders
		$page_data['order_list'] = $this->Order_model->get_paginated_pending_order($filter_data, $per_page, $offset);

		// Setup pagination
		$this->load->library('pagination');
		$pagination_config = $this->get_pagination_config(
			base_url($this->current_vendor['domain'] . '/orders/pending'),
			$total_count,
			$per_page
		);
		
		$this->pagination->initialize($pagination_config);
		$page_data['pagination'] = $this->pagination->create_links();

		// Prepare page data
		$page_data['page_name']    = 'pending_orders';
		$page_data['page_title']   = 'Pending Orders';
		$page_data['current_page'] = 'Pending Orders';
		$page_data['navigate']     = 'Pending Orders';
		$page_data['current_vendor'] = $this->current_vendor;
		$page_data['vendor_domain'] = $this->current_vendor['domain'];
		$page_data['filter_data'] = $filter_data;
		
		// Load content view
		$data['title'] = 'Pending Orders - ' . $this->current_vendor['name'];
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $this->current_vendor['domain'];
		$data['content'] = $this->load->view('vendor/orders/pending_orders', $page_data, TRUE);
		
		// Load main layout
		$this->load->view('vendor/layouts/index_template', $data);
	}
	
	/**
	 * Rejected Orders - List cancelled/rejected orders
	 *
	 * @return	void
	 */
	public function cancelled_orders()
	{
		// Get filter parameters
		$filter_data['date_range'] = $this->input->get('date_range');
		$filter_data['machine']   = $this->input->get('machine');
		$filter_data['keywords']  = $this->input->get('keywords');
		$filter_data['is_refund'] = $this->input->get('is_refund') ? $this->input->get('is_refund') : '0';
		$filter_data['order_status'] = $this->input->get('order_status') ? $this->input->get('order_status') : '6';

		// Get total count and orders using new methods
		$total_count = $this->Order_model->get_paginated_cancelled_order_count($filter_data);
		$page_data['total_count'] = $total_count;
		
		// Get order counts for each status (for tabs)
		$vendor_id = $this->current_vendor['id'];
		$page_data['order_counts'] = $this->Order_model->get_order_status_counts($vendor_id);
		$page_data['order_status'] = 'cancelled';
		
		// Pagination setup
		$per_page = 10;
		$page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
		if ($page < 1) $page = 1;
		$offset = ($page - 1) * $per_page;
		
		// Get orders
		$page_data['order_list'] = $this->Order_model->get_paginated_cancelled_order($filter_data, $per_page, $offset);

		// Setup pagination
		$this->load->library('pagination');
		$pagination_config = $this->get_pagination_config(
			base_url($this->current_vendor['domain'] . '/orders/rejected-orders'),
			$total_count,
			$per_page
		);
		
		$this->pagination->initialize($pagination_config);
		$page_data['pagination'] = $this->pagination->create_links();

		// Prepare page data
		$page_data['page_name']    = 'rejected_orders';
		$page_data['page_title']   = 'Cancelled Orders';
		$page_data['current_page'] = 'Cancelled Orders';
		$page_data['navigate']     = 'Cancelled Orders';
		$page_data['current_vendor'] = $this->current_vendor;
		$page_data['vendor_domain'] = $this->current_vendor['domain'];
		$page_data['filter_data'] = $filter_data;
		
		// Load content view
		$data['title'] = 'Cancelled Orders - ' . $this->current_vendor['name'];
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $this->current_vendor['domain'];
		$data['content'] = $this->load->view('vendor/orders/rejected_orders', $page_data, TRUE);
		
		// Load main layout
		$this->load->view('vendor/layouts/index_template', $data);
	}

	
	/**
	 * Offers
	 *
	 * @return	void
	 */
	public function offers()
	{
		// Get filter parameters
		$filter_data['date_range'] = $this->input->get('date_range');
		$filter_data['machine']   = $this->input->get('machine');
		$filter_data['keywords']  = $this->input->get('keywords');

		// Get total count and orders using new methods
		$total_count = $this->Order_model->get_paginated_offers_count($filter_data);
		$page_data['total_count'] = $total_count;
		
		// Pagination setup
		$per_page = 10;
		$page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
		if ($page < 1) $page = 1;
		$offset = ($page - 1) * $per_page;
		
		// Get orders
		$page_data['order_list'] = $this->Order_model->get_paginated_offers($filter_data, $per_page, $offset);

		// Setup pagination
		$this->load->library('pagination');
		$pagination_config = $this->get_pagination_config(
			base_url($this->current_vendor['domain'] . '/orders/offers'),
			$total_count,
			$per_page
		);
		
		$this->pagination->initialize($pagination_config);
		$page_data['pagination'] = $this->pagination->create_links();

		// Prepare page data
		$page_data['page_name']    = 'offers';
		$page_data['page_title']   = 'Offers';
		$page_data['current_page'] = 'Offers';
		$page_data['navigate']     = 'Offers';
		$page_data['current_vendor'] = $this->current_vendor;
		$page_data['vendor_domain'] = $this->current_vendor['domain'];
		$page_data['filter_data'] = $filter_data;
		
		// Load content view
		$data['title'] = 'Offers';
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $this->current_vendor['domain'];
		$data['content'] = $this->load->view('vendor/orders/offers', $page_data, TRUE);
		
		// Load main layout
		$this->load->view('vendor/layouts/index_template', $data);
	}

	/**
	 * Add new offer
	 *
	 * @return	void
	 */
	public function add_offers()
	{
		if ($this->input->server('REQUEST_METHOD') == 'POST')
		{
			// Handle AJAX form submission
			$resultpost = array(
				"status" => 200,
				"message" => "Offer added successfully",
				"url" => base_url($this->current_vendor['domain'] . '/offers'),
			);

			$discount_code = html_escape($this->input->post('discount_code'));
			$title = html_escape($this->input->post('title'));
			$is_show = !empty($this->input->post('is_show')) ? 1 : 0;
			$is_app = !empty($this->input->post('is_app')) ? 1 : 0;
			$is_new_only = !empty($this->input->post('is_new_only')) ? 1 : 0;

			$offer_type = (!empty($discount_code)) ? 'discount_code' : 'automatic_discount';

			if (!empty($discount_code)) {
				// Check if discount code already exists
				$this->db->where('discount_code', $discount_code);
				$check_code = $this->db->get('offers')->row();
				if ($check_code) {
					header('Content-Type: application/json');
					echo json_encode(array(
						"status" => 400,
						"message" => "Discount code already exists"
					));
					return;
				}
			}

			$item_type = $this->input->post('item_type') ?? 'all';
			$data = array(
				'offer_type' => $offer_type,
				'discount_code' => $discount_code,
				'is_show' => $is_show,
				'is_app' => $is_app,
				'is_new_only' => $is_new_only,
				'title' => $title,
				'min_type' => $this->input->post('min_type') ?? 'quantity',
				'min_value' => $this->input->post('min_value') ?? 0,
				'max_per_user' => $this->input->post('max_per_user'),
				'description' => $this->input->post('description'),
				'no_coupon' => $this->input->post('no_coupon'),
				'item_type' => $item_type,
				'offer_value_type' => $this->input->post('offer_value_type') ?? 'percentage',
				'status' => 1
			);

			if (!empty($this->input->post('item_type_list'))) {
				$item_type_list = $this->input->post('item_type_list');
				if ($item_type == 'products') {
					// For products, store the IDs directly
					$data['item_type_list'] = is_array($item_type_list) ? implode(',', $item_type_list) : $item_type_list;
					$data['variation_ids'] = null;
				} else {
					// For categories (uniform types), store the IDs
					$data['item_type_list'] = is_array($item_type_list) ? implode(',', $item_type_list) : $item_type_list;
					$data['variation_ids'] = null;
				}
			} else {
				$data['item_type_list'] = null;
				$data['variation_ids'] = null;
			}

			$offer_value_type = $data['offer_value_type'];

			if ($offer_value_type == 'percentage') {
				$data['offer_value'] = $this->input->post('offer_value_percentage') ?? 0;
			} elseif ($offer_value_type == 'amount') {
				$data['offer_value'] = $this->input->post('offer_value_amount') ?? 0;
			} elseif ($offer_value_type == 'free') {
				$data['free_quantity'] = $this->input->post('free_quantity') ?? 0;
				$data['item_type_get'] = $this->input->post('item_type_get') ?? 'all';

				if (!empty($this->input->post('item_type_list_get'))) {
					$item_type_list_get = $this->input->post('item_type_list_get');
					if ($data['item_type_get'] == 'products') {
						$data['item_type_list_get'] = is_array($item_type_list_get) ? implode(',', $item_type_list_get) : $item_type_list_get;
						$data['variation_ids_get'] = null;
					} else {
						$data['item_type_list_get'] = is_array($item_type_list_get) ? implode(',', $item_type_list_get) : $item_type_list_get;
						$data['variation_ids_get'] = null;
					}
				} else {
					$data['item_type_list_get'] = null;
					$data['variation_ids_get'] = null;
				}
			}

			if ($this->input->post('is_cashback')) {
				$data['is_cashback'] = 1;
				$data['cashback_type'] = $this->input->post('cashback_type') ?? 'flat';

				if ($data['cashback_type'] === 'flat') {
					$data['cashback_value'] = $this->input->post('cashback_flat_value') ?? 0;
				} elseif ($data['cashback_type'] === 'percentage') {
					$data['cashback_value'] = $this->input->post('cashback_percentage_value') ?? 0;
					$data['is_upto'] = $this->input->post('is_upto') ? 1 : 0;
					if ($data['is_upto']) {
						$data['upto_amount'] = $this->input->post('upto_amount') ?? 0;
					}
				}
			}

			if (!is_numeric($data['min_value'])) {
				header('Content-Type: application/json');
				echo json_encode(array(
					"status" => 400,
					"message" => "Minimum value must be a valid number"
				));
				return;
			}

			if (($offer_value_type == 'percentage' || $offer_value_type == 'amount') && !is_numeric($data['offer_value'])) {
				header('Content-Type: application/json');
				echo json_encode(array(
					"status" => 400,
					"message" => "Offer value must be a valid number"
				));
				return;
			}

			if ($offer_value_type == 'free' && !is_numeric($data['free_quantity'])) {
				header('Content-Type: application/json');
				echo json_encode(array(
					"status" => 400,
					"message" => "Free quantity must be a valid number"
				));
				return;
			}

			if ($this->input->post('is_cashback')) {
				if (!is_numeric($data['cashback_value'])) {
					header('Content-Type: application/json');
					echo json_encode(array(
						"status" => 400,
						"message" => "Cashback value must be a valid number"
					));
					return;
				}
				if (
					$data['cashback_type'] === 'percentage'
					&& !empty($data['is_upto'])
					&& !is_numeric($data['upto_amount'])
				) {
					header('Content-Type: application/json');
					echo json_encode(array(
						"status" => 400,
						"message" => "Upto amount must be a valid number"
					));
					return;
				}
			}

			if (!$this->db->insert('offers', $data)) {
				header('Content-Type: application/json');
				echo json_encode(array(
					"status" => 500,
					"message" => "Database error: Failed to save offer"
				));
				return;
			}
			
			header('Content-Type: application/json');
			echo json_encode($resultpost);
			return;
		}
		else
		{
			// Get dropdown data
			$page_data['uniform_types'] = $this->Uniform_model->getAllUniformTypes();
			$page_data['uniforms'] = $this->Uniform_model->getUniformsByVendor($this->current_vendor['id'], array(), 1000, 0); // Get all uniforms
			
			$page_data['title'] = 'Add New Offer';
			$page_data['current_vendor'] = $this->current_vendor;
			$page_data['vendor_domain'] = $this->current_vendor['domain'];
			$page_data['current_page'] = 'Add New Offer';
			$page_data['breadcrumb'] = array(
				array('label' => 'Offers', 'url' => base_url($this->current_vendor['domain'] . '/offers')),
				array('label' => 'Add', 'active' => true)
			);
			
			$data['title'] = 'Add New Offer';
			$data['current_vendor'] = $this->current_vendor;
			$data['vendor_domain'] = $this->current_vendor['domain'];
			
			// Load content view
			$data['content'] = $this->load->view('vendor/orders/add_offers', $page_data, TRUE);
			
			// Load main layout
			$this->load->view('vendor/layouts/index_template', $data);
		}
	}

	/**
	 * Download invoice for an order
	 *
	 * @param	string	$order_no	Order unique ID
	 * @return	void
	 */
	public function download_invoice($order_no)
	{
		// Get order details
		$order_data = $this->Order_model->get_order($order_no);
		
		if (!$order_data || empty($order_data))
		{
			show_error('Order not found', 404);
			return;
		}
		
		$order = $order_data[0];
		$order_id = $order->id;
		
		// Check if invoice already exists
		if (!empty($order->invoice_url) && file_exists($order->invoice_url))
		{
			// Download existing invoice
			$this->load->helper('download');
			$data = file_get_contents($order->invoice_url);
			$name = 'invoice_' . $order->order_unique_id . '.pdf';
			force_download($name, $data);
			return;
		}
		
		// Generate invoice on the fly - get order details from tbl_order_details
		$this->db->select('*');
		$this->db->from('tbl_order_details');
		$this->db->where('id', $order_id);
		$this->db->where('(payment_status="success" OR payment_status="cod" OR payment_method="cod")');
		$query = $this->db->get();
		
		if ($query->num_rows() == 0)
		{
			show_error('Order details not found', 404);
			return;
		}
		
		$order_row = $query->row_array();
		$order_address_id = $order_row['order_address'];
		
		// Get order address
		$shipping = array();
		if (!empty($order_address_id))
		{
			$this->db->select('*');
			$this->db->from('tbl_order_address');
			$this->db->where('order_id', $order_id);
			$this->db->limit(1);
			$address_query = $this->db->get();
			if ($address_query->num_rows() > 0)
			{
				$shipping = $address_query->row_array();
			}
		}
		
		// Get order items
		$this->db->select('id,product_id,product_title,product_sku,product_qty,variation_name,product_mrp,product_price,product_gst,total_gst_amt,total_price,discount_amt,hsn,excl_price,excl_price_total');
		$this->db->from('tbl_order_items');
		$this->db->where('order_id', $order_id);
		$items_query = $this->db->get();
		$products = $items_query->result_array();
		
		// Calculate totals
		$gst_total = 0;
		$total_product_discount = 0;
		foreach ($products as $product)
		{
			$gst_total += isset($product['total_gst_amt']) ? $product['total_gst_amt'] : 0;
			$total_product_discount += isset($product['discount_amt']) ? $product['discount_amt'] : 0;
		}
		
		// Format order details for invoice view
		$order_details = array(
			'id' => $order_row['id'],
			'order_unique_id' => $order_row['order_unique_id'],
			'user_name' => $order_row['user_name'],
			'user_email' => $order_row['user_email'],
			'user_phone' => $order_row['user_phone'],
			'order_date' => date("d M Y | h:i A", strtotime($order_row['order_date'])),
			'invoice_date' => !empty($order_row['invoice_date']) ? date("d M Y", strtotime($order_row['invoice_date'])) : date("d M Y"),
			'invoice_no' => !empty($order_row['invoice_no']) ? $order_row['invoice_no'] : '',
			'payable_amt' => $order_row['payable_amt'],
			'discount_amt' => $order_row['discount_amt'],
			'delivery_charge' => isset($order_row['delivery_charge']) ? $order_row['delivery_charge'] : 0,
			'payment_method' => $order_row['payment_method'],
			'currency' => isset($order_row['currency']) ? $order_row['currency'] : 'INR',
			'currency_code' => isset($order_row['currency_code']) ? $order_row['currency_code'] : '₹',
			'shipping' => $shipping,
			'products' => $products,
			'gst_total' => $gst_total,
			'total_product_discount' => $total_product_discount,
			'freight_charges' => isset($order_row['freight_charges']) ? $order_row['freight_charges'] : 0,
			'freight_gst' => isset($order_row['freight_gst']) ? $order_row['freight_gst'] : 0,
			'freight_charges_excl' => isset($order_row['freight_charges_excl']) ? $order_row['freight_charges_excl'] : 0,
			'freight_gst_per' => isset($order_row['freight_gst_per']) ? $order_row['freight_gst_per'] : 0,
		);
		
		// Generate invoice number if not exists
		if (empty($order_details['invoice_no']))
		{
			$invoice_no = 'INV' . date('Ymd') . str_pad($order_id, 6, '0', STR_PAD_LEFT);
			$this->db->where('id', $order_id);
			$this->db->update('tbl_order_details', array('invoice_no' => $invoice_no));
			$order_details['invoice_no'] = $invoice_no;
		}
		
		// Load PDF library
		$this->load->library('pdf');

		// Suppress deprecation warnings from dompdf HTML5 parser
		error_reporting(E_ALL & ~E_DEPRECATED);

		// Prepare data for invoice view
		$page_data['data'] = $order_details;
		
		// Try to load invoice view from frontend
		$frontend_view_path = FCPATH . 'book_erp_frontend/application/views/invoice/invoice_bill.php';
		if (file_exists($frontend_view_path))
		{
			// Load view from frontend
			ob_start();
			extract($page_data);
			include($frontend_view_path);
			$html_content = ob_get_clean();
		}
		else
		{
			// Fallback: try application views
			$invoice_view_path = APPPATH . 'views/invoice/invoice_bill.php';
			if (file_exists($invoice_view_path))
			{
				$html_content = $this->load->view('invoice/invoice_bill', $page_data, TRUE);
			}
			else
			{
				show_error('Invoice template not found', 500);
				return;
			}
		}
		
		// Generate PDF
		$this->pdf->set_paper("A4", "portrait");

		// Suppress deprecation warnings during PDF generation
		$old_error_reporting = error_reporting();
		error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

		try {
			$this->pdf->set_option('isHtml5ParserEnabled', TRUE);
			$this->pdf->load_html($html_content);
			$this->pdf->render();

			// Stream PDF for download
			$pdfname = 'invoice_' . $order->order_unique_id . '.pdf';
			$this->pdf->stream($pdfname, array("Attachment" => 1));
			exit();
		} catch (Exception $e) {
			// If HTML5 parser fails, try without it
			error_reporting($old_error_reporting);
			$this->pdf = new Pdf(); // Reinitialize PDF object
			$this->pdf->set_paper("A4", "portrait");
			$this->pdf->set_option('isHtml5ParserEnabled', FALSE);
			$this->pdf->load_html($html_content);
			$this->pdf->render();

			// Stream PDF for download
			$pdfname = 'invoice_' . $order->order_unique_id . '.pdf';
			$this->pdf->stream($pdfname, array("Attachment" => 1));
			exit();
		}

		// Restore original error reporting
		error_reporting($old_error_reporting);
	}


}

