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
		$this->load->model('Pdf_model');
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
		$filter_data['pincode']   = $this->input->get('pincode');
		$filter_data['school']    = $this->input->get('school');
		$filter_data['grade']     = $this->input->get('grade');
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
		
		// Get schools and grades for filter dropdowns
		$page_data['schools'] = $this->get_schools_for_filter();
		$page_data['grades'] = $this->get_grades_for_filter();
		
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
		
		// Determine order type - first check type_order field in tbl_order_details
		$order_type = 'individual';
		if (!empty($order_data[0]->type_order)) {
			$order_type = strtolower($order_data[0]->type_order);
		} else {
			// Fallback: determine from order items
			$has_bookset = false;
			$has_uniform = false;
			
			foreach ($items_arr as $item) {
				if (isset($item->order_type)) {
					if ($item->order_type == 'bookset' || $item->order_type == 'package') {
						$has_bookset = true;
						break;
					} elseif ($item->order_type == 'uniform') {
						$has_uniform = true;
					}
				}
			}
			
			if ($has_bookset) {
				$order_type = 'bookset';
			} elseif ($has_uniform) {
				$order_type = 'uniform';
			}
		}
		
		// Get bookset products and info if order type is bookset
		$bookset_products = array();
		$bookset_info = null;
		if ($order_type == 'bookset') {
			// First, try to get bookset products from tbl_order_bookset_products (the actual ordered products)
			if ($this->db->table_exists('tbl_order_bookset_products')) {
				$bookset_products = $this->db->select('*')
					->from('tbl_order_bookset_products')
					->where('order_id', $order_id)
					->order_by('package_id', 'ASC')
					->order_by('id', 'ASC')
					->get()
					->result();
			}
			
			// If no products from tbl_order_bookset_products, try erp_bookset_order_products
			if (empty($bookset_products) && $this->db->table_exists('erp_bookset_order_products')) {
				$bookset_products = $this->db->select('*')
					->from('erp_bookset_order_products')
					->where('order_id', $order_id)
					->order_by('id', 'ASC')
					->get()
					->result();
			}
			
			// If no products from erp_bookset_order_products, try to get from bookset_packages_json in tbl_order_items
			if (empty($bookset_products) && !empty($items_arr)) {
				foreach ($items_arr as $item) {
					if (isset($item->order_type) && $item->order_type == 'bookset') {
						// Try to get bookset_packages_json from order item
						// Note: This field might be stored in a different way, so we'll use the data we have
						$bookset_id = isset($item->product_id) ? $item->product_id : null;
						$package_ids = isset($item->package_id) ? $item->package_id : '';
						
						// If package_ids exist, fetch products from packages
						if (!empty($package_ids)) {
							$package_id_array = explode(',', $package_ids);
							$package_id_array = array_filter(array_map('trim', $package_id_array));
							
							if (!empty($package_id_array) && $this->db->table_exists('erp_bookset_package_products')) {
								$package_products = $this->db->select('bpp.*, bp.package_name, bp.package_price')
									->from('erp_bookset_package_products bpp')
									->join('erp_bookset_packages bp', 'bp.id = bpp.package_id', 'left')
									->where_in('bpp.package_id', $package_id_array)
									->where('bpp.status', 'active')
									->order_by('bpp.package_id', 'ASC')
									->order_by('bpp.id', 'ASC')
									->get()
									->result();
								
								foreach ($package_products as $pkg_prod) {
									$bookset_products[] = (object)array(
										'package_id' => $pkg_prod->package_id,
										'package_name' => $pkg_prod->package_name,
										'package_price' => $pkg_prod->package_price,
										'product_id' => $pkg_prod->product_id,
										'product_type' => $pkg_prod->product_type,
										'product_name' => $pkg_prod->display_name,
										'product_sku' => '',
										'quantity' => $pkg_prod->quantity,
										'unit_price' => $pkg_prod->discounted_mrp,
										'total_price' => $pkg_prod->discounted_mrp * $pkg_prod->quantity,
									);
								}
							}
						}
						break; // Only process first bookset item
					}
				}
			}
			
			// Get bookset info (school, grade, board, student details) from order items
			if (!empty($items_arr)) {
				foreach ($items_arr as $item) {
					if (isset($item->order_type) && $item->order_type == 'bookset') {
						// Get info directly from order item
						$bookset_info = new stdClass();
						$bookset_info->school_id = isset($item->school_id) ? $item->school_id : null;
						$bookset_info->grade_id = isset($item->grade_id) ? $item->grade_id : null;
						$bookset_info->board_id = isset($item->board_id) ? $item->board_id : null;
						$bookset_info->f_name = isset($item->f_name) ? $item->f_name : '';
						$bookset_info->m_name = isset($item->m_name) ? $item->m_name : '';
						$bookset_info->s_name = isset($item->s_name) ? $item->s_name : '';
						$bookset_info->dob = isset($item->dob) ? $item->dob : '';
						
						// Get roll_number - check direct field first, then JSON
						$roll_number = '';
						if (isset($item->roll_number) && !empty($item->roll_number)) {
							$roll_number = $item->roll_number;
						} elseif (isset($item->roll_no) && !empty($item->roll_no)) {
							$roll_number = $item->roll_no;
						} elseif (isset($item->bookset_packages_json) && !empty($item->bookset_packages_json)) {
							// Try to extract from JSON
							$json_data = json_decode($item->bookset_packages_json, true);
							if (is_array($json_data) && isset($json_data['roll_number'])) {
								$roll_number = $json_data['roll_number'];
							} elseif (is_array($json_data) && isset($json_data['roll_no'])) {
								$roll_number = $json_data['roll_no'];
							} elseif (is_object($json_data) && isset($json_data->roll_number)) {
								$roll_number = $json_data->roll_number;
							} elseif (is_object($json_data) && isset($json_data->roll_no)) {
								$roll_number = $json_data->roll_no;
							}
						}
						$bookset_info->roll_number = $roll_number;
						
						// Fetch school name if school_id exists
						if (!empty($bookset_info->school_id) && $this->db->table_exists('erp_schools')) {
							$school_row = $this->db->select('school_name')
								->from('erp_schools')
								->where('id', $bookset_info->school_id)
								->limit(1)
								->get()
								->row();
							if (!empty($school_row)) {
								$bookset_info->school_name = $school_row->school_name;
							}
						}
						
						// Fetch grade name if grade_id exists
						if (!empty($bookset_info->grade_id) && $this->db->table_exists('erp_textbook_grades')) {
							$grade_row = $this->db->select('name as grade_name')
								->from('erp_textbook_grades')
								->where('id', $bookset_info->grade_id)
								->limit(1)
								->get()
								->row();
							if (!empty($grade_row)) {
								$bookset_info->grade_name = $grade_row->grade_name;
							}
						}
						
						// Fetch board name if board_id exists
						if (!empty($bookset_info->board_id) && $this->db->table_exists('erp_school_boards')) {
							$board_row = $this->db->select('board_name')
								->from('erp_school_boards')
								->where('id', $bookset_info->board_id)
								->limit(1)
								->get()
								->row();
							if (!empty($board_row)) {
								$bookset_info->board_name = $board_row->board_name;
							}
						}
						
						break; // Only process first bookset item
					}
				}
			}
		}
		
		// Get order status history from erp_order_status_history
		$status_history = array();
		if ($this->db->table_exists('erp_order_status_history')) {
			// Try to get order_id from erp_orders table
			// Note: erp_orders uses 'order_number', not 'order_unique_id'
			$erp_order = $this->db->select('id')
				->from('erp_orders')
				->where('order_number', $order_no)
				->limit(1)
				->get()
				->row();
			
			$erp_order_id = !empty($erp_order) ? $erp_order->id : null;
			
			// If order not found in erp_orders, it might be from tbl_order_details
			// In that case, check if there's a relationship via order_id
			// Since erp_order_status_history references erp_orders.id, 
			// we can only get history if order exists in erp_orders
			if (!empty($erp_order_id)) {
				$status_history = $this->db->select('*')
					->from('erp_order_status_history')
					->where('order_id', $erp_order_id)
					->order_by('created_at', 'ASC')
					->get()
					->result();
			}
		}
		
		// Get additional status entries from tbl_order_status
		$additional_status = array();
		if (!empty($order_data[0]->id)) {
			$additional_status = $this->db->select('*')
				->from('tbl_order_status')
				->where('order_id', $order_data[0]->id)
				->where('status_title !=', '1') // Exclude order placed
				->where('status_title !=', '2') // Exclude processing (we use processing_date)
				->where('status_title !=', '3') // Exclude out for delivery (we use shipment_date)
				->where('status_title !=', '4') // Exclude delivered (we use delivery_date)
				->order_by('created_at', 'ASC')
				->get()
				->result();
		}
		
		// Prepare page data
		$data['title'] = 'Order Details - ' . $order_no;
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $this->current_vendor['domain'];
		$data['order_data'] = $order_data;
		$data['items_arr'] = $items_arr;
		$data['address_arr'] = $address_arr;
		$data['current_page'] = 'Order Details';
		$data['order_type'] = $order_type;
		$data['bookset_products'] = $bookset_products;
		$data['bookset_info'] = $bookset_info;
		$data['status_history'] = $status_history;
		$data['additional_status'] = $additional_status;
		$data['order_id'] = isset($order_data[0]->id) ? $order_data[0]->id : 0;
		
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
	 * Move single order to processing status
	 *
	 * @return	void
	 */
	public function move_to_processing_single()
	{
		$order_unique_id = $this->input->post('order_unique_id');
		
		if (empty($order_unique_id)) {
			echo json_encode([
				'status' => '400',
				'message' => 'Order ID is required.',
			]);
			return;
		}
		
		// Get order by unique_id
		$order = $this->Order_model->get_order($order_unique_id);
		
		if (empty($order)) {
			echo json_encode([
				'status' => '400',
				'message' => 'Order not found.',
			]);
			return;
		}
		
		$order_data = $order[0];
		$order_id = $order_data->id;
		
		// Verify order is in pending status
		if ($order_data->order_status != '1' && $order_data->order_status != 1) {
			echo json_encode([
				'status' => '400',
				'message' => 'Order must be in pending status to move to processing.',
			]);
			return;
		}
		
		// Update order status
		$processing_date = date("Y-m-d H:i:s");
		$this->db->where('id', $order_id);
		$this->db->where('order_status', '1');
		$this->db->update('tbl_order_details', array(
			'order_status' => '2',
			'processing_date' => $processing_date
		));
		
		if ($this->db->affected_rows() > 0) {
			// Update order items status
			$this->db->where('order_id', $order_id);
			$this->db->update('tbl_order_items', array('pro_order_status' => '2'));
			
			// Add status history
			$this->db->insert('tbl_order_status', array(
				'order_id' => $order_id,
				'user_id' => $order_data->user_id,
				'product_id' => 0,
				'status_title' => '2',
				'status_desc' => 'Order moved to processing',
				'created_at' => $processing_date
			));
			
			echo json_encode([
				'status' => '200',
				'message' => 'Order moved to processing successfully.',
			]);
		} else {
			echo json_encode([
				'status' => '400',
				'message' => 'Failed to update order status.',
			]);
		}
	}
	
	/**
	 * Set shipper (courier) for an order
	 *
	 * @return	void
	 */
	public function set_shipper()
	{
		// Debug: Log incoming data
		log_message('debug', 'set_shipper called with POST data: ' . print_r($this->input->post(), true));
		
		$order_unique_id = $this->input->post('order_unique_id');
		$courier = $this->input->post('courier'); // 'manual' or 'shiprocket'
		
		if (empty($order_unique_id) || empty($courier)) {
			echo json_encode([
				'status' => '400',
				'message' => 'Order ID and courier type are required. Received: order_id=' . $order_unique_id . ', courier=' . $courier,
			]);
			return;
		}
		
		if (!in_array($courier, ['manual', 'shiprocket'])) {
			echo json_encode([
				'status' => '400',
				'message' => 'Invalid courier type. Must be "manual" or "shiprocket". Received: ' . $courier,
			]);
			return;
		}
		
		// Get order by unique_id
		$order = $this->Order_model->get_order($order_unique_id);
		
		if (empty($order)) {
			echo json_encode([
				'status' => '400',
				'message' => 'Order not found.',
			]);
			return;
		}
		
		$order_data = $order[0];
		$order_id = $order_data->id;
		
		// Verify order is in processing status
		if ($order_data->order_status != '2' && $order_data->order_status != 2) {
			echo json_encode([
				'status' => '400',
				'message' => 'Order must be in processing status to set shipper. Current status: ' . $order_data->order_status,
			]);
			return;
		}
		
		// Update courier - save as 'manual' for self delivery (enum only allows 'shiprocket', 'manual', '')
		// Note: Database enum is ('shiprocket','manual',''), so we use 'manual' instead of 'SELF'
		$courier_value = ($courier == 'manual') ? 'manual' : $courier;
		
		// Check current courier value first
		$current_courier_row = $this->db->select('courier')
			->from('tbl_order_details')
			->where('id', $order_id)
			->get()
			->row();
		
		$current_courier = !empty($current_courier_row) ? $current_courier_row->courier : null;
		
		// If already set to the same value, return success
		if ($current_courier == $courier_value) {
			echo json_encode([
				'status' => '200',
				'message' => 'Shipper is already set to ' . ($courier == 'manual' ? 'Self Delivery' : '3rd Party (Shiprocket)') . '.',
			]);
			return;
		}
		
		// Debug: Log update attempt
		log_message('debug', 'Updating order_id=' . $order_id . ' with courier=' . $courier_value . ' (current: ' . $current_courier . ')');
		
		// Update courier
		$this->db->where('id', $order_id);
		$update_result = $this->db->update('tbl_order_details', array(
			'courier' => $courier_value
		));
		
		// Check for database errors
		$db_error = $this->db->error();
		if (!empty($db_error['message'])) {
			log_message('error', 'Database error: ' . $db_error['message']);
			echo json_encode([
				'status' => '400',
				'message' => 'Database error: ' . $db_error['message'],
			]);
			return;
		}
		
		$affected_rows = $this->db->affected_rows();
		log_message('debug', 'Update result: ' . ($update_result ? 'true' : 'false') . ', affected_rows=' . $affected_rows . ', SQL: ' . $this->db->last_query());
		
		// Verify the update
		$updated_courier_row = $this->db->select('courier')
			->from('tbl_order_details')
			->where('id', $order_id)
			->get()
			->row();
		
		$updated_courier = !empty($updated_courier_row) ? $updated_courier_row->courier : null;
		
		if ($updated_courier == $courier_value || $affected_rows > 0) {
			// Add timeline entry
			$this->db->insert('tbl_order_status', array(
				'order_id' => $order_id,
				'user_id' => isset($this->current_vendor['id']) ? $this->current_vendor['id'] : 0,
				'product_id' => 0,
				'status_title' => 'Shipper Selected',
				'status_desc' => 'Shipper set to ' . ($courier == 'manual' ? 'Self Delivery' : '3rd Party (Shiprocket)'),
				'created_at' => date('Y-m-d H:i:s')
			));
			
			echo json_encode([
				'status' => '200',
				'message' => 'Shipper set to ' . ($courier == 'manual' ? 'Self Delivery' : '3rd Party (Shiprocket)') . ' successfully.',
			]);
		} else {
			echo json_encode([
				'status' => '400',
				'message' => 'Failed to update shipper. Current courier: ' . ($current_courier ? $current_courier : 'empty') . ', Attempted: ' . $courier_value . ', After update: ' . ($updated_courier ? $updated_courier : 'empty'),
			]);
		}
	}
	
	/**
	 * Move single order to out for delivery status
	 *
	 * @return	void
	 */
	public function move_to_out_for_delivery_single()
	{
		$order_unique_id = $this->input->post('order_unique_id');
		
		if (empty($order_unique_id)) {
			echo json_encode([
				'status' => '400',
				'message' => 'Order ID is required.',
			]);
			return;
		}
		
		// Get order by unique_id
		$order = $this->Order_model->get_order($order_unique_id);
		
		if (empty($order)) {
			echo json_encode([
				'status' => '400',
				'message' => 'Order not found.',
			]);
			return;
		}
		
		$order_data = $order[0];
		$order_id = $order_data->id;
		
		// Verify order is in processing status
		if ($order_data->order_status != '2' && $order_data->order_status != 2) {
			echo json_encode([
				'status' => '400',
				'message' => 'Order must be in processing status to move to out for delivery.',
			]);
			return;
		}
		
		// For self delivery, verify shipping label exists
		if ($order_data->courier == 'manual' && empty($order_data->shipping_label)) {
			echo json_encode([
				'status' => '400',
				'message' => 'Shipping label must be generated before moving to out for delivery.',
			]);
			return;
		}
		
		// Update order status
		$shipment_date = date("Y-m-d H:i:s");
		$this->db->where('id', $order_id);
		$this->db->where('order_status', '2');
		$this->db->update('tbl_order_details', array(
			'order_status' => '3',
			'shipment_date' => $shipment_date
		));
		
		if ($this->db->affected_rows() > 0) {
			// Update order items status
			$this->db->where('order_id', $order_id);
			$this->db->update('tbl_order_items', array('pro_order_status' => '3'));
			
			// Add status history
			$this->db->insert('tbl_order_status', array(
				'order_id' => $order_id,
				'user_id' => $order_data->user_id,
				'product_id' => 0,
				'status_title' => '3',
				'status_desc' => 'Order moved to out for delivery',
				'created_at' => $shipment_date
			));
			
			echo json_encode([
				'status' => '200',
				'message' => 'Order moved to out for delivery successfully.',
			]);
		} else {
			echo json_encode([
				'status' => '400',
				'message' => 'Failed to update order status.',
			]);
		}
	}
	
	/**
	 * Move single order to delivered status
	 *
	 * @return	void
	 */
	public function move_to_delivered_single()
	{
		$order_unique_id = $this->input->post('order_unique_id');
		
		if (empty($order_unique_id)) {
			echo json_encode([
				'status' => '400',
				'message' => 'Order ID is required.',
			]);
			return;
		}
		
		// Get order by unique_id
		$order = $this->Order_model->get_order($order_unique_id);
		
		if (empty($order)) {
			echo json_encode([
				'status' => '400',
				'message' => 'Order not found.',
			]);
			return;
		}
		
		$order_data = $order[0];
		$order_id = $order_data->id;
		
		// Verify order is in out for delivery status
		if ($order_data->order_status != '3' && $order_data->order_status != 3) {
			echo json_encode([
				'status' => '400',
				'message' => 'Order must be in out for delivery status to mark as delivered.',
			]);
			return;
		}
		
		// Update order status
		$delivery_date = date("Y-m-d H:i:s");
		$this->db->where('id', $order_id);
		$this->db->where('order_status', '3');
		$this->db->update('tbl_order_details', array(
			'order_status' => '4',
			'delivery_date' => $delivery_date
		));
		
		if ($this->db->affected_rows() > 0) {
			// Update order items status
			$this->db->where('order_id', $order_id);
			$this->db->update('tbl_order_items', array('pro_order_status' => '4'));
			
			// Add status history
			$this->db->insert('tbl_order_status', array(
				'order_id' => $order_id,
				'user_id' => $order_data->user_id,
				'product_id' => 0,
				'status_title' => '4',
				'status_desc' => 'Order delivered',
				'created_at' => $delivery_date
			));
			
			echo json_encode([
				'status' => '200',
				'message' => 'Order marked as delivered successfully.',
			]);
		} else {
			echo json_encode([
				'status' => '400',
				'message' => 'Failed to update order status.',
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
		$filter_data['pincode']   = $this->input->get('pincode');
		$filter_data['school']    = $this->input->get('school');
		$filter_data['grade']     = $this->input->get('grade');

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
		
		// Get schools and grades for filter dropdowns
		$page_data['schools'] = $this->get_schools_for_filter();
		$page_data['grades'] = $this->get_grades_for_filter();
		
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
		$filter_data['pincode']   = $this->input->get('pincode');
		$filter_data['school']    = $this->input->get('school');
		$filter_data['grade']     = $this->input->get('grade');
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
		
		// Get schools and grades for filter dropdowns
		$page_data['schools'] = $this->get_schools_for_filter();
		$page_data['grades'] = $this->get_grades_for_filter();
		
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

	/**
	 * Get schools for filter dropdown
	 *
	 * @return	array	Array of schools
	 */
	private function get_schools_for_filter()
	{
		$vendor_id = $this->current_vendor['id'];
		
		// Get schools
		$this->db->select('erp_schools.id, erp_schools.school_name, "school" as type');
		$this->db->from('erp_schools');
		$this->db->where('erp_schools.vendor_id', $vendor_id);
		$this->db->where('erp_schools.status', 'active');
		$this->db->order_by('erp_schools.school_name', 'ASC');
		$schools = $this->db->get()->result_array();
		
		// Get branches
		$this->db->select('erp_school_branches.id, erp_school_branches.branch_name as school_name, "branch" as type, erp_schools.school_name as parent_school_name');
		$this->db->from('erp_school_branches');
		$this->db->join('erp_schools', 'erp_schools.id = erp_school_branches.school_id', 'left');
		$this->db->where('erp_school_branches.vendor_id', $vendor_id);
		$this->db->where('erp_school_branches.status', 'active');
		$branches = $this->db->get()->result_array();
		
		// Combine and format
		$all_schools = array();
		foreach ($schools as $school) {
			$all_schools[] = array(
				'id' => $school['id'],
				'name' => $school['school_name'],
				'type' => 'school'
			);
		}
		foreach ($branches as $branch) {
			$all_schools[] = array(
				'id' => $branch['id'],
				'name' => $branch['school_name'] . (isset($branch['parent_school_name']) ? ' (' . $branch['parent_school_name'] . ')' : ''),
				'type' => 'branch'
			);
		}
		
		// Sort alphabetically
		usort($all_schools, function($a, $b) {
			return strcasecmp($a['name'], $b['name']);
		});
		
		return $all_schools;
	}

	/**
	 * Get grades for filter dropdown
	 *
	 * @return	array	Array of grades
	 */
	private function get_grades_for_filter()
	{
		$vendor_id = $this->current_vendor['id'];
		
		$this->db->select('id, name');
		$this->db->from('erp_textbook_grades');
		$this->db->where('vendor_id', $vendor_id);
		$this->db->where('status', 'active');
		$this->db->order_by('name', 'ASC');
		$grades = $this->db->get()->result_array();
		
		return $grades;
	}

	/**
	 * Generate shipping label for an order
	 *
	 * @param	string	$order_no	Order unique ID
	 * @return	void
	 */
	public function generate_shipping_label($order_no)
	{
		// Increase memory limit for PDF generation
		ini_set('memory_limit', '256M');
		
		// Get order details
		$order_data = $this->Order_model->get_order($order_no);
		
		if (!$order_data)
		{
			show_error('Order not found', 404);
			return;
		}
		
		$order = $order_data[0];
		$order_id = $order->id;
		
		// Verify order belongs to vendor and is in processing status
		if ($order->order_status != '2' && $order->order_status != 2)
		{
			$this->session->set_flashdata('error', 'Shipping label can only be generated for orders in processing status.');
			redirect(base_url('orders/view/' . $order_no));
			return;
		}
		
		// Generate shipping number (tracking ID) - use order_unique_id as slot_no for compatibility
		$shipping_number = $order_no; // Use order_unique_id as shipping number
		
		// Generate unique ship_order_id for this shipping label generation (BEFORE barcode generation)
		// Format: SHIP + YYYYMMDD + HHMMSS + random 4 digits
		$unique_ship_order_id = 'SHIP' . date('YmdHis') . sprintf('%04d', mt_rand(0, 9999));
		
		// Ensure uniqueness by checking if it exists
		$check_unique = $this->db->where('ship_order_id', $unique_ship_order_id)
			->get('tbl_order_details')
			->num_rows();
		if ($check_unique > 0) {
			// If exists, add more random digits
			$unique_ship_order_id = 'SHIP' . date('YmdHis') . sprintf('%06d', mt_rand(0, 999999));
		}
		
		// Check if shipping label already exists in vendor_shipping_label table
		$shipping_label = $this->Pdf_model->get_shipping_label($shipping_number);
		$label_id = null;
		$barcode_url = '';
		
		if ($shipping_label->num_rows() > 0) {
			$label_row = $shipping_label->row();
			$label_id = $label_row->id;
			// Generate barcode using ship_order_id (not shipping_number)
			if (empty($label_row->barcode_url)) {
				$this->Pdf_model->get_picqer_barcode($unique_ship_order_id, $label_id, 'barcode_url');
				// Get updated barcode URL
				$updated_label = $this->Pdf_model->get_shipping_label($shipping_number)->row();
				$barcode_url = !empty($updated_label->barcode_url) ? base_url($updated_label->barcode_url) : '';
			} else {
				$barcode_url = base_url($label_row->barcode_url);
			}
		} else {
			// Create new shipping label entry in vendor_shipping_label table
			// Use current vendor_id from order
			$vendor_id = isset($order->vendor_id) ? $order->vendor_id : (isset($this->current_vendor['id']) ? $this->current_vendor['id'] : null);
			
			// Check if vendor_shipping_label table exists before inserting
			if ($this->db->table_exists('vendor_shipping_label')) {
				$label_id = $this->Pdf_model->add_shipping_label($shipping_number, $vendor_id, $shipping_number);
				
				if ($label_id) {
					// Generate barcode using ship_order_id (not shipping_number)
					$this->Pdf_model->get_picqer_barcode($unique_ship_order_id, $label_id, 'barcode_url');
					// Get barcode URL
					$updated_label = $this->Pdf_model->get_shipping_label($shipping_number)->row();
					$barcode_url = !empty($updated_label->barcode_url) ? base_url($updated_label->barcode_url) : '';
				}
			} else {
				// Table doesn't exist, generate QR code and save directly to order
				// Generate QR code using ship_order_id
				try {
					require_once APPPATH . 'vendor/autoload.php';
					
					$qrCode = \Endroid\QrCode\QrCode::create($unique_ship_order_id)
						->setSize(300)
						->setMargin(10);
					
					$writer = new \Endroid\QrCode\Writer\PngWriter();
					$result = $writer->write($qrCode);
					$barcode_data = $result->getString();
					
					// Save to main folder (not vendor-specific): /uploads/vendor_picqer_barcode/{date_folder}/
					$date_folder = date('Y_m_d');
					$relative_dir = 'uploads/vendor_picqer_barcode/';
					
					$upload_path = FCPATH . trim($relative_dir, '/') . '/'
						. $date_folder . '/';
					
					// Create directory structure step by step
					if (!is_dir($upload_path)) {
						// Try to create the full path
						if (!@mkdir($upload_path, 0775, true)) {
							// If that fails, try creating directories one by one
							$dirs_to_create = array();
							$current_path = $upload_path;
							while (!is_dir($current_path) && $current_path !== FCPATH && $current_path !== '/') {
								$dirs_to_create[] = $current_path;
								$current_path = dirname($current_path);
							}
							$dirs_to_create = array_reverse($dirs_to_create);
							
							foreach ($dirs_to_create as $dir) {
								if (!is_dir($dir)) {
									@mkdir($dir, 0775, true);
								}
							}
						}
					}
					
					$file_name = $unique_ship_order_id . ".png";
					$pngAbsoluteFilePath = $upload_path . $file_name;
					$relative_path = trim($relative_dir, '/') . '/'
						. $date_folder . '/'
						. $file_name;
					
					@file_put_contents($pngAbsoluteFilePath, $barcode_data);
					$barcode_url = base_url($relative_path);
					$label_id = null;
				} catch (Exception $e) {
					$barcode_url = '';
					$label_id = null;
				}
			}
		}
		
		// Get order items to determine order type
		$items_arr = $this->db->select('*')
			->from('tbl_order_items')
			->where('order_id', $order_id)
			->order_by('id', 'ASC')
			->get()
			->result();
		
		// Determine order type (bookset, individual, or uniform)
		$order_type_label = 'Individual';
		$has_bookset = false;
		$has_uniform = false;
		
		foreach ($items_arr as $item) {
			// Check order_type field in tbl_order_items
			if (isset($item->order_type)) {
				if ($item->order_type == 'bookset' || $item->order_type == 'package') {
					$has_bookset = true;
					break; // Found bookset, no need to check further
				} elseif ($item->order_type == 'uniform') {
					$has_uniform = true;
				}
			}
		}
		
		if ($has_bookset) {
			$order_type_label = 'Bookset';
		} elseif ($has_uniform) {
			$order_type_label = 'Uniform';
		} else {
			$order_type_label = 'Individual';
		}
		
		// Get order address
		$address_arr = $this->db->select('*')
			->from('tbl_order_address')
			->where('order_id', $order_id)
			->order_by('id', 'ASC')
			->limit(1)
			->get()
			->result();
		
		// Get vendor logo directly from erp_clients table
		$logo_path = null;
		$logo_url = '';
		$logo_base64 = '';
		
		// Get logo path directly from erp_clients table
		$logo_row = $this->db->select('logo')
			->from('erp_clients')
			->limit(1)
			->get()
			->row();
		
		if (!empty($logo_row) && !empty($logo_row->logo)) {
			$logo_path = FCPATH . ltrim($logo_row->logo, '/');
			if (file_exists($logo_path)) {
				$logo_url = base_url($logo_row->logo);
			} else {
				$logo_path = null;
			}
		}
		
		// Function to resize and compress image for PDF
		$resize_image_for_pdf = function($image_path, $max_width = 200, $max_height = 100, $quality = 85) {
			if (!file_exists($image_path) || !function_exists('imagecreatefromjpeg')) {
				return false;
			}
			
			$image_info = getimagesize($image_path);
			if ($image_info === false) {
				return false;
			}
			
			$mime_type = $image_info['mime'];
			$width = $image_info[0];
			$height = $image_info[1];
			
			// Calculate new dimensions
			$ratio = min($max_width / $width, $max_height / $height);
			$new_width = (int)($width * $ratio);
			$new_height = (int)($height * $ratio);
			
			// Create image resource based on type
			// Suppress PNG iCCP warnings
			$old_error_reporting = error_reporting();
			error_reporting($old_error_reporting & ~E_WARNING);
			
			switch ($mime_type) {
				case 'image/jpeg':
					$source = @imagecreatefromjpeg($image_path);
					break;
				case 'image/png':
					$source = @imagecreatefrompng($image_path);
					break;
				case 'image/gif':
					$source = @imagecreatefromgif($image_path);
					break;
				default:
					error_reporting($old_error_reporting);
					return false;
			}
			
			// Restore error reporting
			error_reporting($old_error_reporting);
			
			if (!$source) {
				return false;
			}
			
			// Create new image
			$new_image = imagecreatetruecolor($new_width, $new_height);
			
			// Preserve transparency for PNG
			if ($mime_type == 'image/png') {
				imagealphablending($new_image, false);
				imagesavealpha($new_image, true);
				$transparent = imagecolorallocatealpha($new_image, 255, 255, 255, 127);
				imagefilledrectangle($new_image, 0, 0, $new_width, $new_height, $transparent);
			}
			
			// Resize
			imagecopyresampled($new_image, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
			
			// Output to buffer
			ob_start();
			switch ($mime_type) {
				case 'image/jpeg':
					imagejpeg($new_image, null, $quality);
					break;
				case 'image/png':
					imagepng($new_image, null, 9);
					break;
				case 'image/gif':
					imagegif($new_image);
					break;
			}
			$image_data = ob_get_contents();
			ob_end_clean();
			
			// Clean up
			imagedestroy($source);
			imagedestroy($new_image);
			
			return array('data' => $image_data, 'mime' => $mime_type);
		};
		
		// Convert logo to base64 for PDF compatibility (with optimization)
			if (!empty($logo_path) && file_exists($logo_path)) {
				$resized = $resize_image_for_pdf($logo_path, 200, 100, 85);
				if ($resized !== false) {
					$logo_base64 = 'data:' . $resized['mime'] . ';base64,' . base64_encode($resized['data']);
				} else {
					// Fallback: use original image if resize fails
					$image_data = file_get_contents($logo_path);
					$image_info = getimagesize($logo_path);
					if ($image_info !== false) {
						$mime_type = $image_info['mime'];
						// Limit size to 500KB
						if (strlen($image_data) < 500000) {
							$logo_base64 = 'data:' . $mime_type . ';base64,' . base64_encode($image_data);
						}
					}
				}
			}
		
		// Get barcode and convert to base64 for PDF
		$barcode_base64 = '';
		$barcode_file_path = '';
		
		// First, try to get barcode path from order table
		if (!empty($order->barcode_path)) {
			$barcode_file_path = FCPATH . ltrim($order->barcode_path, '/');
			if (file_exists($barcode_file_path)) {
				$barcode_url = base_url($order->barcode_path);
			}
		}
		
		// If not found, try to get from barcode_url
		if (empty($barcode_file_path) && !empty($barcode_url)) {
			// Extract relative path from URL
			$barcode_relative = str_replace(base_url(), '', $barcode_url);
			$barcode_relative = ltrim($barcode_relative, '/');
			$barcode_file_path = FCPATH . $barcode_relative;
			
			// If file doesn't exist, try to get from database
			if (!file_exists($barcode_file_path)) {
				if ($label_id) {
					$label_row = $this->Pdf_model->get_shipping_label($shipping_number)->row();
					if (!empty($label_row->barcode_url)) {
						$barcode_file_path = FCPATH . ltrim($label_row->barcode_url, '/');
						$barcode_url = base_url($label_row->barcode_url);
					}
				}
			}
		}
		
		// Convert barcode to base64 for PDF
		if (!empty($barcode_file_path) && file_exists($barcode_file_path)) {
			$barcode_data = file_get_contents($barcode_file_path);
			if ($barcode_data !== false) {
				$barcode_base64 = 'data:image/png;base64,' . base64_encode($barcode_data);
			}
		}
		
		// Get logo absolute file path (already have logo_path from above)
		$logo_file_path = '';
		if (!empty($logo_path) && file_exists($logo_path)) {
			$logo_file_path = $logo_path;
		}
		
		// Prepare data for shipping label
		$label_data = array(
			'order' => $order,
			'items' => $items_arr,
			'address' => !empty($address_arr) ? $address_arr[0] : null,
			'order_type_label' => $order_type_label,
			'logo_url' => $logo_url, // Use URL for HTML preview
			'logo_file_path' => $logo_file_path, // Absolute file path for PDF
			'logo_base64' => $logo_base64, // Base64 for PDF
			'shipping_number' => $shipping_number,
			'barcode_url' => $barcode_url, // URL for HTML preview
			'barcode_file_path' => $barcode_file_path, // Absolute file path for PDF
			'barcode_base64' => $barcode_base64 // Base64 for PDF
		);
		
		// Start output buffering to prevent any output before headers
		ob_start();
		
		// Generate PDF
		$this->load->library('pdf');
		
		// Suppress deprecation warnings from dompdf HTML5 parser
		$old_error_reporting = error_reporting();
		error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
		
		// Generate unique ship_order_id for this shipping label generation
		// Format: SHIP + YYYYMMDD + HHMMSS + random 4 digits
		$unique_ship_order_id = 'SHIP' . date('YmdHis') . sprintf('%04d', mt_rand(0, 9999));
		
		// Ensure uniqueness by checking if it exists
		$check_unique = $this->db->where('ship_order_id', $unique_ship_order_id)
			->get('tbl_order_details')
			->num_rows();
		if ($check_unique > 0) {
			// If exists, add more random digits
			$unique_ship_order_id = 'SHIP' . date('YmdHis') . sprintf('%06d', mt_rand(0, 999999));
		}
		
		// Use kirtiBook design - fetch shipping label HTML from model
		$address_obj = !empty($address_arr) ? $address_arr[0] : null;
		
		// Embed CSS inline for PDF compatibility (PDF libraries don't load external CSS files)
		$html = '<!DOCTYPE html><html><head><meta charset="UTF-8"><style>';
		// Bootstrap CSS (minified - include key styles for PDF)
		if (file_exists(FCPATH . 'assets/pdf/bootstrap.min.css')) {
			$html .= file_get_contents(FCPATH . 'assets/pdf/bootstrap.min.css');
		}
		// Custom A5 CSS
		if (file_exists(FCPATH . 'assets/pdf/cutsom-a5.css')) {
			$html .= file_get_contents(FCPATH . 'assets/pdf/cutsom-a5.css');
		}
		$html .= '</style></head><body>';
		
		$html .= $this->Pdf_model->fetch_shipping_label($shipping_number, $order, $items_arr, $address_obj, $order_type_label, $logo_url, $barcode_url, 'self', $unique_ship_order_id);
		
		$html .= '</body></html>';
		
		// Generate PDF
		$this->pdf->set_paper("A4", "portrait");
		
		// Clear any output that might have been generated
		ob_clean();
		
		// Use the same upload pattern as images
		$this->load->helper('common');
		$this->config->load('upload');
		$uploadCfg = $this->config->item('shipping_label_upload');
		$vendor_folder = get_vendor_domain_folder();
		$date_folder = date('Y_m_d');
		
		// Build upload path using the same pattern as textbook images
		$upload_path = rtrim($uploadCfg['base_root'], '/') . '/'
			. $vendor_folder . '/'
			. trim($uploadCfg['relative_dir'], '/') . '/'
			. $date_folder . '/';
		
		// Create directory if it doesn't exist (with proper permissions)
		if (!is_dir($upload_path)) {
			mkdir($upload_path, 0775, true);
		}
		
		// Delete old shipping labels for this order if they exist
		if (!empty($order->shipping_label)) {
			// Get old file path
			$old_relative_path = $order->shipping_label;
			$old_path_parts = explode('/', $old_relative_path);
			$old_date_folder = isset($old_path_parts[2]) ? $old_path_parts[2] : date('Y_m_d');
			$old_filename = end($old_path_parts);
			
			// Build old file path
			$old_file_path = rtrim($uploadCfg['base_root'], '/') . '/'
				. $vendor_folder . '/'
				. trim($uploadCfg['relative_dir'], '/') . '/'
				. $old_date_folder . '/'
				. $old_filename;
			
			// Delete old file if it exists
			if (file_exists($old_file_path)) {
				@unlink($old_file_path);
			}
			
			// Also try to delete any other shipping labels for this order (in case there are multiple)
			$old_pattern = $upload_path . 'shipping_label_' . $order_no . '_*.pdf';
			$old_files = glob($old_pattern);
			if ($old_files) {
				foreach ($old_files as $old_file) {
					if (file_exists($old_file)) {
						@unlink($old_file);
					}
				}
			}
		}
		
		// Generate PDF filename
		$pdf_filename = 'shipping_label_' . $order_no . '_' . time() . '.pdf';
		$pdf_path = $upload_path . $pdf_filename;
		
		try {
			$this->pdf->set_option('isHtml5ParserEnabled', TRUE);
			$this->pdf->load_html($html);
			$this->pdf->render();
			
			// Get PDF output
			$pdf_output = $this->pdf->output();
			file_put_contents($pdf_path, $pdf_output);
		} catch (Exception $e) {
			// If HTML5 parser fails, try without it
			error_reporting($old_error_reporting);
			$this->pdf = new Pdf(); // Reinitialize PDF object
			$this->pdf->set_paper("A4", "portrait");
			$this->pdf->set_option('isHtml5ParserEnabled', FALSE);
			$this->pdf->load_html($html);
			$this->pdf->render();
			
			// Get PDF output
			$pdf_output = $this->pdf->output();
			file_put_contents($pdf_path, $pdf_output);
		}
		
		// Restore original error reporting
		error_reporting($old_error_reporting);
		
		// Store relative path in database (same pattern as images)
		$relative_path = 'uploads/shipping_labels/' . $date_folder . '/' . $pdf_filename;
		
		// Get relative barcode path for order table (get directly from database)
		// Barcode should be generated against ship_order_id, so check with that
		$barcode_relative_path = '';
		if ($label_id) {
			$label_row = $this->Pdf_model->get_shipping_label($shipping_number)->row();
			if (!empty($label_row->barcode_url)) {
				$barcode_relative_path = $label_row->barcode_url; // Already a relative path
			}
		}
		
		// Update order with shipping label, unique shipping ID, and barcode path
		$order_update_data = array(
			'shipping_label' => $relative_path,
			'ship_order_id' => $unique_ship_order_id,
			'courier' => 'manual' // 'manual' means self delivery (enum only allows 'shiprocket', 'manual', '')
		);
		
		// Add barcode_path if we have it
		if (!empty($barcode_relative_path)) {
			$order_update_data['barcode_path'] = $barcode_relative_path;
		}
		
		$this->db->where('id', $order_id);
		$this->db->update('tbl_order_details', $order_update_data);
		
		// Add timeline entry for shipping label generation
		$this->db->insert('tbl_order_status', array(
			'order_id' => $order_id,
			'user_id' => isset($this->current_vendor['id']) ? $this->current_vendor['id'] : 0,
			'product_id' => 0,
			'status_title' => 'Shipping Label Generated',
			'status_desc' => 'Shipping label has been generated and is ready for download',
			'created_at' => date('Y-m-d H:i:s')
		));
		
		// Update vendor_shipping_label table with label URL if label_id exists and table exists
		if ($label_id && $this->db->table_exists('vendor_shipping_label')) {
			$this->db->where('id', $label_id);
			$this->db->update('vendor_shipping_label', array(
				'label_url' => $relative_path
			));
		}
		
		// End output buffering
		ob_end_clean();
		
		// Set success message and redirect back to order view (page will auto-reload)
		$this->session->set_flashdata('success', 'Shipping label generated successfully.');
		redirect(base_url('orders/view/' . $order_no));
	}
	
	/**
	 * Download shipping label for an order
	 *
	 * @param	string	$order_no	Order unique ID
	 * @return	void
	 */
	public function download_shipping_label($order_no)
	{
		// Get order details
		$order_data = $this->Order_model->get_order($order_no);
		
		if (!$order_data)
		{
			show_error('Order not found', 404);
			return;
		}
		
		$order = $order_data[0];
		
		if (empty($order->shipping_label))
		{
			$this->session->set_flashdata('error', 'Shipping label not found. Please generate it first.');
			redirect(base_url('orders/view/' . $order_no));
			return;
		}
		
		// Use the same path pattern as images (construct full path from relative path)
		$this->load->helper('common');
		$this->config->load('upload');
		$uploadCfg = $this->config->item('shipping_label_upload');
		$vendor_folder = get_vendor_domain_folder();
		
		// Extract date folder from relative path (format: uploads/shipping_labels/2026_02_13/filename.pdf)
		$relative_path = $order->shipping_label;
		$path_parts = explode('/', $relative_path);
		$date_folder = isset($path_parts[2]) ? $path_parts[2] : date('Y_m_d');
		$filename = end($path_parts);
		
		// Build full file path using the same pattern as upload
		$file_path = rtrim($uploadCfg['base_root'], '/') . '/'
			. $vendor_folder . '/'
			. trim($uploadCfg['relative_dir'], '/') . '/'
			. $date_folder . '/'
			. $filename;
		
		// Fallback to FCPATH if the above path doesn't exist (for backward compatibility)
		if (!file_exists($file_path)) {
			$file_path = FCPATH . $relative_path;
		}
		
		if (!file_exists($file_path))
		{
			$this->session->set_flashdata('error', 'Shipping label file not found at: ' . $file_path);
			redirect(base_url('orders/view/' . $order_no));
			return;
		}
		
		// Output PDF using readfile (no deprecation issues)
		header('Content-Type: application/pdf');
		header('Content-Disposition: attachment; filename="shipping_label_' . $order_no . '.pdf"');
		header('Content-Length: ' . filesize($file_path));
		readfile($file_path);
		exit;
	}
	
	/**
	 * Test shipping label view for design purposes (HTML preview, not PDF)
	 *
	 * @param	string	$order_no	Order unique ID
	 * @return	void
	 */
	public function test_shipping_label($order_no)
	{
		// Increase memory limit for PDF generation
		ini_set('memory_limit', '256M');
		
		// Get order details
		$order_data = $this->Order_model->get_order($order_no);
		
		if (!$order_data)
		{
			show_error('Order not found', 404);
			return;
		}
		
		$order = $order_data[0];
		$order_id = $order->id;
		
		// Get order items
		$items_arr = $this->db->select('*')
			->from('tbl_order_items')
			->where('order_id', $order_id)
			->order_by('id', 'ASC')
			->get()
			->result();
		
		// Get order address
		$address_arr = $this->db->select('*')
			->from('tbl_order_address')
			->where('order_id', $order_id)
			->order_by('id', 'ASC')
			->limit(1)
			->get()
			->result();
		
		// Get vendor logo - use URL directly for HTML preview (no need for base64)
		$this->load->helper('common');
		$logo_url = get_simple_vendor_logo_url();
		
		// Determine order type (bookset, individual, or uniform)
		$order_type_label = 'Individual';
		$has_bookset = false;
		$has_uniform = false;
		
		foreach ($items_arr as $item) {
			// Check order_type field in tbl_order_items
			if (isset($item->order_type)) {
				if ($item->order_type == 'bookset' || $item->order_type == 'package') {
					$has_bookset = true;
					break; // Found bookset, no need to check further
				} elseif ($item->order_type == 'uniform') {
					$has_uniform = true;
				}
			}
		}
		
		if ($has_bookset) {
			$order_type_label = 'Bookset';
		} elseif ($has_uniform) {
			$order_type_label = 'Uniform';
		} else {
			$order_type_label = 'Individual';
		}
		
		// Use order_unique_id as shipping number for consistency
		$shipping_number = $order_no;
		
		// Get barcode URL if it exists in order
		$barcode_url = '';
		if (!empty($order->barcode_path)) {
			$barcode_url = base_url($order->barcode_path);
		} else {
			// Try to get from vendor_shipping_label
			$shipping_label = $this->Pdf_model->get_shipping_label($shipping_number);
			if ($shipping_label->num_rows() > 0) {
				$label_row = $shipping_label->row();
				if (!empty($label_row->barcode_url)) {
					$barcode_url = base_url($label_row->barcode_url);
				}
			}
		}
		
		// Use kirtiBook design - fetch shipping label HTML from model
		$address_obj = !empty($address_arr) ? $address_arr[0] : null;
		
		// For HTML preview, use external CSS links
		$html = '<link rel="stylesheet" href="' . base_url() . 'assets/pdf/bootstrap.min.css">';
		$html .= '<link rel="stylesheet" href="' . base_url() . 'assets/pdf/cutsom-a5.css">';
		$html .= $this->Pdf_model->fetch_shipping_label($shipping_number, $order, $items_arr, $address_obj, $order_type_label, $logo_url, $barcode_url, 'self');
		
		echo $html;
	}

	/**
	 * Test barcode generation and shipping label functionality
	 * 
	 * @param	string	$order_no	Order unique ID (optional)
	 * @return	void
	 */
	public function test_barcode($order_no = '')
	{
		// Increase memory limit for PDF generation
		ini_set('memory_limit', '256M');
		
		echo "<h1>Barcode Generation Test</h1>";
		echo "<hr>";
		
		// Test 1: Check if Pdf_model is loaded
		echo "<h2>Test 1: Model Loading</h2>";
		if (isset($this->Pdf_model)) {
			echo "✓ Pdf_model is loaded<br>";
		} else {
			echo "✗ Pdf_model is NOT loaded<br>";
			return;
		}
		
		// Test 2: Check if QR code library is available
		echo "<h2>Test 2: QR Code Library</h2>";
		if (class_exists('Endroid\QrCode\QrCode')) {
			echo "✓ Endroid QR Code class is available<br>";
		} else {
			echo "✗ Endroid QR Code class is NOT available<br>";
			echo "Trying to load autoload...<br>";
			require_once APPPATH . 'vendor/autoload.php';
			if (class_exists('Endroid\QrCode\QrCode')) {
				echo "✓ QR Code library loaded successfully<br>";
			} else {
				echo "✗ Failed to load QR Code library<br>";
				return;
			}
		}
		
		// Test 3: Generate a test QR code using the actual upload path
		echo "<h2>Test 3: QR Code Generation & Upload</h2>";
		$test_code = 'TEST' . date('YmdHis');
		try {
			require_once APPPATH . 'vendor/autoload.php';
			
			$qrCode = \Endroid\QrCode\QrCode::create($test_code)
				->setSize(300)
				->setMargin(10);
			
			$writer = new \Endroid\QrCode\Writer\PngWriter();
			$result = $writer->write($qrCode);
			$barcode = $result->getString();
			
			if ($barcode) {
				echo "✓ Barcode generated successfully for code: $test_code<br>";
				echo "Barcode size: " . strlen($barcode) . " bytes<br>";
				
				// Use the same path structure as Pdf_model (main folder, not vendor-specific)
				$date_folder = date('Y_m_d');
				$relative_dir = 'uploads/vendor_picqer_barcode/';
				
				// Full upload path (absolute) - main folder, not vendor-specific
				$upload_path = FCPATH . trim($relative_dir, '/') . '/'
					. $date_folder . '/';
				
				echo "<br><strong>Upload Path Details:</strong><br>";
				echo "FCPATH: " . FCPATH . "<br>";
				echo "Relative Dir: $relative_dir<br>";
				echo "Date Folder: $date_folder<br>";
				echo "Full Upload Path: $upload_path<br>";
				
				// Check if directory exists
				if (!is_dir($upload_path)) {
					echo "⚠ Directory does not exist. Attempting to create...<br>";
					
					// Try to create directory structure step by step
					$dirs_to_create = array();
					$current_path = $upload_path;
					while (!is_dir($current_path) && $current_path !== FCPATH && $current_path !== '/') {
						$dirs_to_create[] = $current_path;
						$current_path = dirname($current_path);
					}
					$dirs_to_create = array_reverse($dirs_to_create);
					
					$created = false;
					foreach ($dirs_to_create as $dir) {
						if (!is_dir($dir)) {
							if (@mkdir($dir, 0775, true)) {
								echo "✓ Created directory: $dir<br>";
								$created = true;
							} else {
								$last_error = error_get_last();
								$error_msg = $last_error && isset($last_error['message']) ? $last_error['message'] : 'Unknown error';
								echo "✗ Failed to create directory: $dir<br>";
								echo "Error: $error_msg<br>";
								break;
							}
						}
					}
					
					if ($created && is_dir($upload_path)) {
						echo "✓ Directory structure created successfully<br>";
					} else {
						echo "<br><strong>Directory Creation Debug:</strong><br>";
						$parent_dir = dirname($upload_path);
						echo "Parent dir: $parent_dir<br>";
						echo "Parent dir exists: " . (is_dir($parent_dir) ? "Yes" : "No") . "<br>";
						if (is_dir($parent_dir)) {
							echo "Parent dir writable: " . (is_writable($parent_dir) ? "Yes" : "No") . "<br>";
							echo "Parent dir permissions: " . substr(sprintf('%o', fileperms($parent_dir)), -4) . "<br>";
						}
					}
				} else {
					echo "✓ Directory exists<br>";
				}
				
				// Check if directory is writable
				if (is_dir($upload_path)) {
					echo "Directory is writable: " . (is_writable($upload_path) ? "Yes" : "No") . "<br>";
					if (!is_writable($upload_path)) {
						echo "Directory permissions: " . substr(sprintf('%o', fileperms($upload_path)), -4) . "<br>";
						echo "⚠ Warning: Directory exists but is not writable. You may need to set permissions manually.<br>";
					}
				}
				
				// Use test code as filename (like order number)
				$test_file_name = $test_code . '.png';
				$test_pngAbsoluteFilePath = $upload_path . $test_file_name;
				$test_relative_path = trim($relative_dir, '/') . '/'
					. $date_folder . '/'
					. $test_file_name;
				
				echo "<br><strong>File Details:</strong><br>";
				echo "Absolute File Path: $test_pngAbsoluteFilePath<br>";
				echo "Relative Path (for DB): $test_relative_path<br>";
				
				// Try to save the file
				$write_result = @file_put_contents($test_pngAbsoluteFilePath, $barcode);
				if ($write_result !== false) {
					echo "✓ File write returned: $write_result bytes written<br>";
				} else {
					echo "✗ File write failed. Error: " . (error_get_last() ? error_get_last()['message'] : 'Unknown error') . "<br>";
				}
				
				// Check if file exists
				if (file_exists($test_pngAbsoluteFilePath)) {
					$file_size = filesize($test_pngAbsoluteFilePath);
					echo "✓ Test barcode file exists!<br>";
					echo "File size: $file_size bytes<br>";
					echo "File path: $test_relative_path<br>";
					echo "<br><img src='" . base_url($test_relative_path) . "' alt='Test Barcode' style='max-width:400px; border:1px solid #ccc;'><br>";
					echo "<br><strong>File URL:</strong> <a href='" . base_url($test_relative_path) . "' target='_blank'>" . base_url($test_relative_path) . "</a><br>";
				} else {
					echo "✗ Test barcode file does NOT exist after write attempt<br>";
					echo "Attempted path: $test_pngAbsoluteFilePath<br>";
					echo "<br><strong>⚠ Permission Issue Detected:</strong><br>";
					echo "The uploads directory exists but is not writable. You need to manually create the directory structure with proper permissions.<br>";
					echo "<br><strong>Solution:</strong><br>";
					echo "Please run these commands on your server (via SSH or file manager):<br>";
					echo "<code>mkdir -p " . dirname($upload_path) . "<br>";
					echo "chmod 775 " . dirname($upload_path) . "<br>";
					echo "mkdir -p $upload_path<br>";
					echo "chmod 775 $upload_path</code><br>";
					echo "<br>Or create the directory manually:<br>";
					echo "<code>" . dirname($upload_path) . "</code><br>";
					echo "with permissions 775 or 777<br>";
				}
			} else {
				echo "✗ Barcode generation returned empty<br>";
			}
		} catch (Exception $e) {
			echo "✗ Error generating barcode: " . $e->getMessage() . "<br>";
			echo "Stack trace: " . $e->getTraceAsString() . "<br>";
		}
		
		// Test 4: Test with actual order if provided
		if (!empty($order_no)) {
			echo "<h2>Test 4: Order Barcode Generation</h2>";
			$order_data = $this->Order_model->get_order($order_no);
			
			if ($order_data && !empty($order_data[0])) {
				$order = $order_data[0];
				$order_id = $order->id;
				$shipping_number = $order_no;
				
				echo "Order ID: $order_id<br>";
				echo "Order Number: $order_no<br>";
				echo "Shipping Number: $shipping_number<br>";
		
				// Check if vendor_shipping_label table exists
				$table_exists = $this->db->table_exists('vendor_shipping_label');
				echo "Vendor Shipping Label Table Exists: " . ($table_exists ? "Yes" : "No") . "<br>";
				
				// Check if shipping label exists
				$shipping_label = $this->Pdf_model->get_shipping_label($shipping_number);
				$label_id = null;
				
				if ($table_exists && $shipping_label->num_rows() > 0) {
					$label_row = $shipping_label->row();
					$label_id = $label_row->id;
					echo "✓ Shipping label found in database (ID: $label_id)<br>";
					
					if (!empty($label_row->barcode_url)) {
						// Check if path is correct (should start with 'uploads/vendor_picqer_barcode/')
						$needs_regeneration = false;
						if (strpos($label_row->barcode_url, 'uploads/vendor_picqer_barcode/') !== 0) {
							echo "⚠ Barcode URL has incorrect path format: " . $label_row->barcode_url . "<br>";
							echo "Regenerating with correct path...<br>";
							$needs_regeneration = true;
						} else {
							echo "✓ Barcode URL exists: " . $label_row->barcode_url . "<br>";
							// Check if file actually exists
							$this->config->load('upload');
							$uploadCfg = $this->config->item('picqer_barcode_upload');
							if (empty($uploadCfg)) {
								$textbookCfg = $this->config->item('textbook_upload');
								$uploadCfg = array(
									'base_root' => $textbookCfg['base_root'],
									'relative_dir' => 'uploads/vendor_picqer_barcode/'
								);
							}
							// Check file existence - barcodes are now in main folder (not vendor-specific)
							$full_path = FCPATH . ltrim($label_row->barcode_url, '/');
							if (!file_exists($full_path)) {
								echo "⚠ Barcode file does not exist at: $full_path<br>";
								echo "Regenerating...<br>";
								$needs_regeneration = true;
							}
						}
						
						if ($needs_regeneration) {
							$this->Pdf_model->get_picqer_barcode($shipping_number, $label_id, 'barcode_url');
							$updated_label = $this->Pdf_model->get_shipping_label($shipping_number)->row();
							if (!empty($updated_label->barcode_url)) {
								echo "✓ Barcode regenerated and saved: " . $updated_label->barcode_url . "<br>";
								// Update order table
								$this->db->where('id', $order_id);
								$this->db->update('tbl_order_details', array(
									'barcode_path' => $updated_label->barcode_url
								));
								echo "✓ Barcode path updated in order table<br>";
								$label_row->barcode_url = $updated_label->barcode_url;
							}
						}
						
						if (!empty($label_row->barcode_url)) {
							echo "<img src='" . base_url($label_row->barcode_url) . "' alt='Order Barcode' style='max-width:400px;'><br>";
						}
		} else {
						echo "⚠ Barcode URL is empty, generating new barcode...<br>";
						$this->Pdf_model->get_picqer_barcode($shipping_number, $label_id, 'barcode_url');
						$updated_label = $this->Pdf_model->get_shipping_label($shipping_number)->row();
						if (!empty($updated_label->barcode_url)) {
							echo "✓ Barcode generated and saved: " . $updated_label->barcode_url . "<br>";
							// Update order table
							$this->db->where('id', $order_id);
							$this->db->update('tbl_order_details', array(
								'barcode_path' => $updated_label->barcode_url
							));
							echo "✓ Barcode path saved to order table<br>";
							echo "<img src='" . base_url($updated_label->barcode_url) . "' alt='Order Barcode' style='max-width:400px;'><br>";
						}
					}
				} else {
					if (!$table_exists) {
						echo "⚠ vendor_shipping_label table does not exist. Generating barcode directly...<br>";
					} else {
						echo "⚠ Shipping label not found, creating new entry...<br>";
					}
					
					$vendor_id = isset($order->vendor_id) ? $order->vendor_id : (isset($this->current_vendor['id']) ? $this->current_vendor['id'] : null);
					
					if ($table_exists) {
						$label_id = $this->Pdf_model->add_shipping_label($shipping_number, $vendor_id, $shipping_number);
						
						if ($label_id) {
							echo "✓ Shipping label created (ID: $label_id)<br>";
							$this->Pdf_model->get_picqer_barcode($shipping_number, $label_id, 'barcode_url');
							$updated_label = $this->Pdf_model->get_shipping_label($shipping_number)->row();
							if (!empty($updated_label->barcode_url)) {
								echo "✓ Barcode generated and saved: " . $updated_label->barcode_url . "<br>";
								echo "<img src='" . base_url($updated_label->barcode_url) . "' alt='Order Barcode' style='max-width:400px;'><br>";
								
								// Update order with barcode path
								$this->db->where('id', $order_id);
								$this->db->update('tbl_order_details', array(
									'barcode_path' => $updated_label->barcode_url
								));
								echo "✓ Barcode path saved to order table<br>";
							}
						}
					} else {
						// Generate QR code directly without vendor_shipping_label table
						try {
							require_once APPPATH . 'vendor/autoload.php';
							
							$qrCode = \Endroid\QrCode\QrCode::create($shipping_number)
								->setSize(300)
								->setMargin(10);
							
							$writer = new \Endroid\QrCode\Writer\PngWriter();
							$result = $writer->write($qrCode);
							$barcode_data = $result->getString();
							
							// Save to main folder (not vendor-specific): /uploads/vendor_picqer_barcode/{date_folder}/
							$date_folder = date('Y_m_d');
							$relative_dir = 'uploads/vendor_picqer_barcode/';
							
							$upload_path = FCPATH . trim($relative_dir, '/') . '/'
								. $date_folder . '/';
							
							if (!is_dir($upload_path)) {
								@mkdir($upload_path, 0775, true);
							}
							
							$file_name = $shipping_number . ".png";
							$pngAbsoluteFilePath = $upload_path . $file_name;
							$relative_path = trim($relative_dir, '/') . '/'
								. $date_folder . '/'
								. $file_name;
							
							@file_put_contents($pngAbsoluteFilePath, $barcode_data);
							echo "✓ Barcode generated and saved: " . $relative_path . "<br>";
							echo "<img src='" . base_url($relative_path) . "' alt='Order Barcode' style='max-width:400px;'><br>";
							
							// Update order with barcode path
							$this->db->where('id', $order_id);
							$this->db->update('tbl_order_details', array(
								'barcode_path' => $relative_path
							));
							echo "✓ Barcode path saved to order table<br>";
						} catch (Exception $e) {
							echo "✗ Error generating barcode: " . $e->getMessage() . "<br>";
						}
					}
				}
				
				// Check order table for barcode_path
				$this->db->select('barcode_path');
				$this->db->where('id', $order_id);
				$order_check = $this->db->get('tbl_order_details')->row();
				if (!empty($order_check->barcode_path)) {
					echo "✓ Order table has barcode_path: " . $order_check->barcode_path . "<br>";
				} else {
					echo "⚠ Order table does not have barcode_path<br>";
				}
			} else {
				echo "✗ Order not found: $order_no<br>";
			}
		} else {
			echo "<h2>Test 4: Skipped (No order number provided)</h2>";
			echo "To test with an order, use: " . base_url('orders/test_barcode/ORDER_NUMBER') . "<br>";
		}
		
		// Test 5: Upload configuration
		echo "<h2>Test 5: Upload Configuration</h2>";
		$this->config->load('upload');
		$uploadCfg = $this->config->item('picqer_barcode_upload');
		
		// Check if config file exists
		$config_file = APPPATH . 'config/upload.php';
		if (file_exists($config_file)) {
			echo "✓ Upload config file exists<br>";
		} else {
			echo "✗ Upload config file NOT found at: $config_file<br>";
		}
		
		// If picqer config not found, use textbook config as reference
		if (empty($uploadCfg) || !is_array($uploadCfg)) {
			echo "⚠ picqer_barcode_upload config not found, using textbook_upload as reference...<br>";
			$textbookCfg = $this->config->item('textbook_upload');
			if (!empty($textbookCfg) && is_array($textbookCfg)) {
				$uploadCfg = array(
					'base_root' => $textbookCfg['base_root'],
					'relative_dir' => 'uploads/vendor_picqer_barcode/'
				);
				echo "✓ Using fallback config from textbook_upload<br>";
			}
		}
		
		if ($uploadCfg && !empty($uploadCfg) && is_array($uploadCfg)) {
			echo "✓ Picqer barcode upload config found<br>";
			echo "Relative Dir: " . (isset($uploadCfg['relative_dir']) ? $uploadCfg['relative_dir'] : 'NOT SET') . "<br>";
			echo "Note: Barcodes are saved to main folder (not vendor-specific)<br>";
			echo "<br><strong>Path Information:</strong><br>";
			echo "FCPATH: " . FCPATH . "<br>";
			
			$date_folder = date('Y_m_d');
			$relative_dir = isset($uploadCfg['relative_dir']) ? $uploadCfg['relative_dir'] : 'uploads/vendor_picqer_barcode/';
			
			// Use FCPATH directly (main folder, not vendor-specific)
			$upload_path = FCPATH . trim($relative_dir, '/') . '/'
				. $date_folder . '/';
			echo "Full Upload Path: $upload_path<br>";
			
			if (is_dir($upload_path)) {
				echo "✓ Upload directory exists<br>";
				echo "Directory is writable: " . (is_writable($upload_path) ? "Yes" : "No") . "<br>";
			} else {
				echo "⚠ Upload directory does not exist<br>";
				echo "Attempting to create directory...<br>";
				if (@mkdir($upload_path, 0775, true)) {
					echo "✓ Directory created successfully<br>";
					echo "Directory is writable: " . (is_writable($upload_path) ? "Yes" : "No") . "<br>";
				} else {
					$last_error = error_get_last();
					echo "✗ Failed to create directory<br>";
					if ($last_error) {
						echo "Error: " . $last_error['message'] . "<br>";
					}
					echo "<br><strong>Directory Permission Check:</strong><br>";
					$parent_dir = dirname($upload_path);
					echo "Parent dir: $parent_dir<br>";
					echo "Parent dir exists: " . (is_dir($parent_dir) ? "Yes" : "No") . "<br>";
					if (is_dir($parent_dir)) {
						echo "Parent dir writable: " . (is_writable($parent_dir) ? "Yes" : "No") . "<br>";
						echo "Parent dir permissions: " . substr(sprintf('%o', fileperms($parent_dir)), -4) . "<br>";
					}
					// Check FCPATH itself
					echo "<br>FCPATH exists: " . (is_dir(FCPATH) ? "Yes" : "No") . "<br>";
					if (is_dir(FCPATH)) {
						echo "FCPATH writable: " . (is_writable(FCPATH) ? "Yes" : "No") . "<br>";
						echo "FCPATH permissions: " . substr(sprintf('%o', fileperms(FCPATH)), -4) . "<br>";
					}
					// Check uploads directory
					$uploads_dir = FCPATH . 'uploads/';
					echo "<br>Uploads dir: $uploads_dir<br>";
					echo "Uploads dir exists: " . (is_dir($uploads_dir) ? "Yes" : "No") . "<br>";
					if (is_dir($uploads_dir)) {
						echo "Uploads dir writable: " . (is_writable($uploads_dir) ? "Yes" : "No") . "<br>";
						echo "Uploads dir permissions: " . substr(sprintf('%o', fileperms($uploads_dir)), -4) . "<br>";
					}
				}
			}
		} else {
			echo "✗ Picqer barcode upload config NOT found or is empty<br>";
			echo "Using default path structure...<br>";
			$date_folder = date('Y_m_d');
			$relative_dir = 'uploads/vendor_picqer_barcode/';
			$upload_path = FCPATH . trim($relative_dir, '/') . '/' . $date_folder . '/';
			echo "FCPATH: " . FCPATH . "<br>";
			echo "Default Upload Path: $upload_path<br>";
		}
		
		echo "<hr>";
		echo "<h2>Test Summary</h2>";
		echo "<p>All tests completed. Check the results above.</p>";
		echo "<p><a href='" . base_url('orders') . "'>Back to Orders</a></p>";
	}


}

