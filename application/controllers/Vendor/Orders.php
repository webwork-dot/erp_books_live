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
		$this->load->model('Courier_model');
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
		$filter_data['payment_method'] = $this->input->get('payment_method');
		$filter_data['delivery_type']  = $this->input->get('delivery_type');
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
		
		// Add school, branch, and bookset information for each item
		foreach ($items_arr as $item) {
			$item->school_name = '';
			$item->branch_name = '';
			$item->size_name = '';
			$item->grade_name = '';
			$item->order_type = isset($item->order_type) ? $item->order_type : 'individual';
			$item->packages = array();
			$item->books = array();

			// Handle bookset items
			if ($item->order_type === 'bookset') {
				// Get school name
				if (isset($item->school_id) && !empty($item->school_id)) {
					$school_query = $this->db->query("SELECT school_name FROM erp_schools WHERE id = '" . (int)$item->school_id . "' LIMIT 1");
					if ($school_query->num_rows() > 0) {
						$item->school_name = $school_query->row()->school_name;
					}
				}

				// Get grade name from bookset or package
				if (empty($item->grade_name)) {
					$bookset_id = isset($item->product_id) ? $item->product_id : 0;
					if (!empty($bookset_id) && $this->db->table_exists('erp_booksets')) {
						$grade_query = $this->db->query("
							SELECT tg.name as grade_name
							FROM erp_booksets bs
							LEFT JOIN erp_textbook_grades tg ON tg.id = bs.grade_id
							WHERE bs.id = '" . (int)$bookset_id . "'
							LIMIT 1
						");
						if ($grade_query->num_rows() > 0) {
							$item->grade_name = $grade_query->row()->grade_name;
						}
					}

					// If still no grade, try from package
					if (empty($item->grade_name) && isset($item->package_id) && !empty($item->package_id)) {
						$package_ids = explode(',', $item->package_id);
						$first_package_id = trim($package_ids[0]);
						if (!empty($first_package_id) && $this->db->table_exists('erp_bookset_packages')) {
							$grade_query = $this->db->query("
								SELECT tg.name as grade_name
								FROM erp_bookset_packages bp
								LEFT JOIN erp_textbook_grades tg ON tg.id = bp.grade_id
								WHERE bp.id = '" . (int)$first_package_id . "'
								LIMIT 1
							");
							if ($grade_query->num_rows() > 0) {
								$item->grade_name = $grade_query->row()->grade_name;
							}
						}
					}
				}

				// Get packages and books for this bookset
				$order_item_id = isset($item->id) ? $item->id : 0;

				if ($order_item_id > 0) {
					// Check if erp_bookset_order_products exists (the table we're using)
					if ($this->db->table_exists('erp_bookset_order_products')) {
						// Get packages and products from order table
						$order_products_query = $this->db->query("
							SELECT
								eop.package_id,
								eop.package_name,
								eop.unit_price as package_price,
								eop.product_type,
								eop.product_id,
								eop.product_name,
								eop.product_sku as sku,
								'' as isbn,
								eop.quantity,
								eop.unit_price,
								eop.total_price,
								0 as weight
							FROM erp_bookset_order_products eop
							WHERE eop.order_id = '" . (int)$order_id . "'
							ORDER BY eop.package_id, eop.id ASC
						");

						if ($order_products_query->num_rows() > 0) {
							$order_products = $order_products_query->result_array();
							$packages_map = array();

							// Group products by package
							foreach ($order_products as $prod) {
								$pkg_id = $prod['package_id'];
								if (!isset($packages_map[$pkg_id])) {
									$packages_map[$pkg_id] = array(
										'package_id' => $pkg_id,
										'package_name' => $prod['package_name'],
										'package_price' => isset($prod['package_price']) ? $prod['package_price'] : 0,
										'category' => '',
										'is_it' => '',
										'books' => array()
									);
								}
								$packages_map[$pkg_id]['books'][] = array(
									'product_type' => $prod['product_type'],
									'product_id' => $prod['product_id'],
									'product_name' => $prod['product_name'],
									'display_name' => $prod['product_name'],
									'sku' => $prod['sku'],
									'product_sku' => $prod['sku'],
									'isbn' => $prod['isbn'],
									'quantity' => $prod['quantity'],
									'unit_price' => $prod['unit_price'],
									'total_price' => $prod['total_price'],
									'weight' => $prod['weight']
								);
							}

							$item->packages = array_values($packages_map);
							foreach ($item->packages as $pkg) {
								foreach ($pkg['books'] as $book) {
									$item->books[] = $book;
								}
							}
						}
					}

					// Also check tbl_order_bookset_products (old table name, if exists)
					if (empty($item->packages) && $this->db->table_exists('tbl_order_bookset_products')) {
						// Get packages and products from order table
						$order_products_query = $this->db->query("
							SELECT
								tobp.package_id,
								tobp.package_name,
								tobp.package_price,
								tobp.product_type,
								tobp.product_id,
								COALESCE(tobp.product_name, bpp.display_name) as product_name,
								COALESCE(tobp.product_sku, bpp.display_name) as sku,
								tobp.product_isbn as isbn,
								tobp.quantity,
								-- Priority: Use discounted_mrp from erp_bookset_package_products, then unit_price from order table
								COALESCE(
									bpp.discounted_mrp,
									tobp.unit_price,
									0
								) as unit_price,
								-- Calculate total_price using the correct unit_price
								(COALESCE(
									bpp.discounted_mrp,
									tobp.unit_price,
									0
								) * tobp.quantity) as total_price,
								tobp.weight
							FROM tbl_order_bookset_products tobp
							LEFT JOIN erp_bookset_package_products bpp ON bpp.package_id = tobp.package_id
								AND bpp.product_type = tobp.product_type
								AND bpp.product_id = tobp.product_id
								AND bpp.status = 'active'
							WHERE tobp.order_item_id = '" . (int)$order_item_id . "'
							ORDER BY tobp.package_id, tobp.id ASC
						");

						if ($order_products_query->num_rows() > 0) {
							$order_products = $order_products_query->result_array();
							$packages_map = array();

							// Group products by package
							foreach ($order_products as $prod) {
								$pkg_id = $prod['package_id'];
								if (!isset($packages_map[$pkg_id])) {
									$packages_map[$pkg_id] = array(
										'package_id' => $pkg_id,
										'package_name' => $prod['package_name'],
										'package_price' => isset($prod['package_price']) ? $prod['package_price'] : 0,
										'books' => array()
									);
								}
								$packages_map[$pkg_id]['books'][] = array(
									'product_type' => $prod['product_type'],
									'product_id' => $prod['product_id'],
									'product_name' => $prod['product_name'],
									'sku' => $prod['sku'],
									'isbn' => $prod['isbn'],
									'quantity' => $prod['quantity'],
									'unit_price' => $prod['unit_price'],
									'total_price' => $prod['total_price'],
									'weight' => $prod['weight']
								);
							}

							$item->packages = array_values($packages_map);
							foreach ($item->packages as $pkg) {
								foreach ($pkg['books'] as $book) {
									$item->books[] = $book;
								}
							}
						}
					}
				}

				// Fallback: Get from package products table if order products not found
				if (empty($item->packages) && isset($item->package_id) && !empty($item->package_id)) {
					$package_ids = explode(',', $item->package_id);
					$package_ids = array_filter(array_map('trim', $package_ids));

					foreach ($package_ids as $package_id) {
						if (empty($package_id)) continue;

						// Get package details
						$package_query = $this->db->query("SELECT id, package_name, package_price, package_offer_price, category, is_it FROM erp_bookset_packages WHERE id = '" . (int)$package_id . "' LIMIT 1");
						if ($package_query->num_rows() > 0) {
							$package = $package_query->row_array();

							// Use offer price if available, otherwise regular price
							$package_price = ($package['package_offer_price'] > 0) ? $package['package_offer_price'] : $package['package_price'];
							$package['package_price'] = $package_price;

							// Get books/products in this package
							$books_query = $this->db->query("
								SELECT
									bpp.id,
									bpp.product_type,
									bpp.product_id,
									bpp.quantity,
									bpp.display_name,
									bpp.display_name as product_name,
									bpp.discounted_mrp,
									bpp.discounted_mrp as unit_price,
									(bpp.discounted_mrp * bpp.quantity) as total_price,
									bpp.weight,
									'' as sku,
									'' as isbn,
									bpp.weight as product_weight
								FROM erp_bookset_package_products bpp
								WHERE bpp.package_id = '" . (int)$package_id . "'
								AND bpp.status = 'active'
								ORDER BY bpp.id ASC
							");

							$package['books'] = $books_query->result_array();
							$item->packages[] = $package;

							// Also add to flat books array for easy access
							foreach ($package['books'] as $book) {
								$item->books[] = $book;
							}
						}
					}
				}
			} else {
				// Handle regular products and uniforms - use thumbnail_img from tbl_order_items for individual orders
				if (!empty($item->product_id)) {

					// Check if it's a uniform (has erp_uniforms table with school_id and branch_id)
					// IMPORTANT: Prefer order item's school_id/branch_id (from tbl_order_items) over product's - order item reflects the branch the customer selected
					$use_school_id = isset($item->school_id) && !empty($item->school_id) ? (int)$item->school_id : null;
					$use_branch_id = isset($item->branch_id) && !empty($item->branch_id) ? (int)$item->branch_id : null;

					$uniform_query = $this->db->query("SELECT u.school_id, u.branch_id, usp.size_id FROM erp_uniforms u LEFT JOIN erp_uniform_size_prices usp ON u.id = usp.uniform_id WHERE u.id = '" . (int)$item->product_id . "' LIMIT 1");
					if ($uniform_query->num_rows() > 0) {
						$uniform = $uniform_query->row();

						// Fallback to product's school/branch if order item doesn't have them
						if (empty($use_school_id) && !empty($uniform->school_id)) $use_school_id = (int)$uniform->school_id;
						if (empty($use_branch_id) && !empty($uniform->branch_id)) $use_branch_id = (int)$uniform->branch_id;

						// Get school name if school_id exists
						if (!empty($use_school_id)) {
							$school_query = $this->db->query("SELECT school_name FROM erp_schools WHERE id = '" . $use_school_id . "' LIMIT 1");
							if ($school_query->num_rows() > 0) {
								$item->school_name = $school_query->row()->school_name;
							}
						}

						// Get branch name if branch_id exists
						if (!empty($use_branch_id)) {
							$branch_query = $this->db->query("SELECT branch_name FROM erp_school_branches WHERE id = '" . $use_branch_id . "' LIMIT 1");
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

					// For individual products, use thumbnail_img from tbl_order_items if available
					// This simplifies the complex image lookup logic
					if ($item->order_type == 'individual' && !empty($item->thumbnail_img)) {
						$item->product_image = $item->thumbnail_img;
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
		
		// When deliver at school/branch: if address is empty, use school/branch from order items with full address
		$addr_first = $address_arr[0];
		$addr_empty = empty($addr_first->address) && empty($addr_first->city) && empty($addr_first->state) && empty($addr_first->pincode);
		$address_from_school_branch = false;
		if ($addr_empty && !empty($items_arr)) {
			foreach ($items_arr as $oi) {
				if (!empty($oi->branch_id)) {
					$br = $this->db->select('sb.branch_name, sb.address, sb.pincode, s.school_name, c.name as city_name, st.name as state_name')
						->from('erp_school_branches sb')
						->join('erp_schools s', 's.id = sb.school_id', 'left')
						->join('cities c', 'c.id = sb.city_id', 'left')
						->join('states st', 'st.id = sb.state_id', 'left')
						->where('sb.id', (int)$oi->branch_id)
						->limit(1)->get()->row();
					if ($br) {
						$addr_first->address = $br->branch_name . (!empty($br->school_name) ? ' (' . $br->school_name . ')' : '');
						if (!empty($br->address)) $addr_first->address .= ', ' . $br->address;
						$addr_first->city = isset($br->city_name) ? $br->city_name : '';
						$addr_first->state = isset($br->state_name) ? $br->state_name : '';
						$addr_first->pincode = isset($br->pincode) ? $br->pincode : '';
						$address_from_school_branch = true;
						break;
					}
				} elseif (!empty($oi->school_id)) {
					$sch = $this->db->select('s.school_name, s.address, s.pincode, c.name as city_name, st.name as state_name')
						->from('erp_schools s')
						->join('cities c', 'c.id = s.city_id', 'left')
						->join('states st', 'st.id = s.state_id', 'left')
						->where('s.id', (int)$oi->school_id)
						->limit(1)->get()->row();
					if ($sch) {
						$addr_first->address = $sch->school_name;
						if (!empty($sch->address)) $addr_first->address .= ', ' . $sch->address;
						$addr_first->city = isset($sch->city_name) ? $sch->city_name : '';
						$addr_first->state = isset($sch->state_name) ? $sch->state_name : '';
						$addr_first->pincode = isset($sch->pincode) ? $sch->pincode : '';
						$address_from_school_branch = true;
						break;
					}
				}
			}
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

						// If IDs are not set in direct fields, try to get them from bookset_packages_json
						if ((empty($bookset_info->school_id) || empty($bookset_info->grade_id) || empty($bookset_info->board_id)) &&
							isset($item->bookset_packages_json) && !empty($item->bookset_packages_json)) {
							$json_data = json_decode($item->bookset_packages_json, true);

							if (empty($bookset_info->school_id)) {
								if (is_array($json_data) && isset($json_data['school_id'])) {
									$bookset_info->school_id = $json_data['school_id'];
								} elseif (is_object($json_data) && isset($json_data->school_id)) {
									$bookset_info->school_id = $json_data->school_id;
								}
							}

							if (empty($bookset_info->grade_id)) {
								if (is_array($json_data) && isset($json_data['grade_id'])) {
									$bookset_info->grade_id = $json_data['grade_id'];
								} elseif (is_object($json_data) && isset($json_data->grade_id)) {
									$bookset_info->grade_id = $json_data->grade_id;
								}
							}

							if (empty($bookset_info->board_id)) {
								if (is_array($json_data) && isset($json_data['board_id'])) {
									$bookset_info->board_id = $json_data['board_id'];
								} elseif (is_object($json_data) && isset($json_data->board_id)) {
									$bookset_info->board_id = $json_data->board_id;
								}
							}
						}

						// DEBUG: Log what we found
						// error_log("Bookset Info - School: " . $bookset_info->school_id . ", Grade: " . $bookset_info->grade_id . ", Board: " . $bookset_info->board_id);
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

		// Use is_deliver_at_school from tbl_order_details (single source of truth)
		$is_deliver_at_school = (isset($order_data[0]->is_deliver_at_school) && (int)$order_data[0]->is_deliver_at_school === 1);

		// Build uniform_info when school/branch available in order items (all uniform orders, or any order with school/branch)
		$uniform_info = null;
		$uniform_student_details = array();
		if (!empty($items_arr)) {
			$first_oi = null;
			foreach ($items_arr as $oi) {
				if (!empty($oi->branch_id) || !empty($oi->school_id)) {
					$first_oi = $oi;
					break;
				}
			}
			if ($first_oi) {
				$uniform_info = new stdClass();
				$uniform_info->school_name = '';
				$uniform_info->branch_name = '';
				$uniform_info->display_name = '';
				$uniform_info->address = '';

				if (!empty($first_oi->branch_id)) {
					$br = $this->db->select('sb.branch_name, sb.address, sb.pincode, s.school_name, c.name as city_name, st.name as state_name')
						->from('erp_school_branches sb')
						->join('erp_schools s', 's.id = sb.school_id', 'left')
						->join('cities c', 'c.id = sb.city_id', 'left')
						->join('states st', 'st.id = sb.state_id', 'left')
						->where('sb.id', (int)$first_oi->branch_id)
						->limit(1)->get()->row();
					if ($br) {
						$uniform_info->branch_name = $br->branch_name;
						$uniform_info->school_name = isset($br->school_name) ? $br->school_name : '';
						$uniform_info->display_name = $br->branch_name . (!empty($br->school_name) ? ' (' . $br->school_name . ')' : '');
						$addr_parts = array_filter([
							isset($br->address) ? $br->address : '',
							isset($br->city_name) ? $br->city_name : '',
							isset($br->state_name) ? $br->state_name : '',
							isset($br->pincode) ? $br->pincode : ''
						]);
						$uniform_info->address = implode(', ', $addr_parts);
					}
				} elseif (!empty($first_oi->school_id)) {
					$sch = $this->db->select('s.school_name, s.address, s.pincode, c.name as city_name, st.name as state_name')
						->from('erp_schools s')
						->join('cities c', 'c.id = s.city_id', 'left')
						->join('states st', 'st.id = s.state_id', 'left')
						->where('s.id', (int)$first_oi->school_id)
						->limit(1)->get()->row();
					if ($sch) {
						$uniform_info->school_name = isset($sch->school_name) ? $sch->school_name : '';
						$uniform_info->display_name = isset($sch->school_name) ? $sch->school_name : '';
						$addr_parts = array_filter([
							isset($sch->address) ? $sch->address : '',
							isset($sch->city_name) ? $sch->city_name : '',
							isset($sch->state_name) ? $sch->state_name : '',
							isset($sch->pincode) ? $sch->pincode : ''
						]);
						$uniform_info->address = implode(', ', $addr_parts);
					}
				}

				// Collect student details from order items (f_name, grade, roll_number, remarks)
				$seen_students = array();
				foreach ($items_arr as $oi) {
					$f_name = isset($oi->f_name) ? trim($oi->f_name) : '';
					$grade = isset($oi->grade) ? trim($oi->grade) : '';
					$roll_number = isset($oi->roll_number) ? trim($oi->roll_number) : '';
					$remarks = isset($oi->remarks) ? trim($oi->remarks) : '';
					$key = $f_name . '|' . $grade . '|' . $roll_number;
					if (!isset($seen_students[$key]) && ($f_name || $grade || $roll_number || $remarks)) {
						$seen_students[$key] = true;
						$uniform_student_details[] = (object)array(
							'f_name' => $f_name,
							'grade' => $grade,
							'roll_number' => $roll_number,
							'remarks' => $remarks
						);
					}
				}
			}
		}
		
		// When is_deliver_at_school and uniform_info not built from items, try address_from_school_branch
		if ($is_deliver_at_school && empty($uniform_info) && $address_from_school_branch && !empty($addr_first)) {
			$uniform_info = new stdClass();
			$uniform_info->display_name = isset($addr_first->address) ? $addr_first->address : '';
			$addr_parts = array_filter([
				isset($addr_first->city) ? $addr_first->city : '',
				isset($addr_first->state) ? $addr_first->state : '',
				isset($addr_first->pincode) ? $addr_first->pincode : ''
			]);
			$uniform_info->address = implode(', ', $addr_parts);
			$uniform_info->school_name = '';
			$uniform_info->branch_name = '';
		}
		
		// When deliver at school, collect student details from all items if not yet collected
		if ($is_deliver_at_school && empty($uniform_student_details) && !empty($items_arr)) {
			$seen_students = array();
			foreach ($items_arr as $oi) {
				$f_name = isset($oi->f_name) ? trim($oi->f_name) : '';
				$grade = isset($oi->grade) ? trim($oi->grade) : '';
				$roll_number = isset($oi->roll_number) ? trim($oi->roll_number) : '';
				$remarks = isset($oi->remarks) ? trim($oi->remarks) : '';
				$key = $f_name . '|' . $grade . '|' . $roll_number;
				if (!isset($seen_students[$key]) && ($f_name || $grade || $roll_number || $remarks)) {
					$seen_students[$key] = true;
					$uniform_student_details[] = (object)array(
						'f_name' => $f_name,
						'grade' => $grade,
						'roll_number' => $roll_number,
						'remarks' => $remarks
					);
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
		
		// Get ALL status entries from tbl_order_status (order_id = tbl_order_details.id)
		$additional_status = array();
		if (!empty($order_data[0]->id)) {
			$additional_status = $this->db->select('*')
				->from('tbl_order_status')
				->where('order_id', $order_data[0]->id)
				->order_by('created_at', 'ASC')
				->get()
				->result();
		}
		
		// Payment at School flag (is_deliver_at_school already set from tbl_order_details above)
		$is_payment_at_school = ($order_data[0]->payment_method == 'payment_at_school' || $order_data[0]->payment_method == 'payment_at_scho');

		// Has school/branch in order items (show badge like Bookset Order)
		$has_school_branch = false;
		if (!empty($items_arr)) {
			foreach ($items_arr as $oi) {
				if (!empty($oi->branch_id) || !empty($oi->school_id)) {
					$has_school_branch = true;
					break;
				}
			}
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
		$data['uniform_info'] = $uniform_info;
		$data['uniform_student_details'] = $uniform_student_details;
		$data['is_payment_at_school'] = $is_payment_at_school;
		$data['is_deliver_at_school'] = $is_deliver_at_school;
		$data['has_school_branch'] = $has_school_branch;
		$data['status_history'] = $status_history;
		$data['additional_status'] = $additional_status;
		$data['order_id'] = isset($order_data[0]->id) ? $order_data[0]->id : 0;
		
		// Courier info for self-delivery orders (from erp_master_courier)
		$data['courier_info'] = null;
		$erp_courier_id = isset($order_data[0]->erp_courier_id) ? (int)$order_data[0]->erp_courier_id : 0;
		if ($erp_courier_id > 0) {
			$data['courier_info'] = $this->Courier_model->get_courier_by_id($erp_courier_id, $this->current_vendor['id']);
		}
		
		// Load content view
		$data['content'] = $this->load->view('vendor/orders/view', $data, TRUE);
		
		// Load main layout
		$this->load->view('vendor/layouts/index_template', $data);
	}
	
	/**
	 * Get order timeline HTML (AJAX) for display in orders list modal
	 *
	 * @param	string	$order_no	Order unique ID
	 * @return	void
	 */
	public function get_order_timeline($order_no)
	{
		$order_data = $this->Order_model->get_order($order_no);
		if (!$order_data || empty($order_data)) {
			header('Content-Type: text/html; charset=utf-8');
			echo '<p class="text-muted">Order not found.</p>';
			return;
		}
		
		$order_id = $order_data[0]->id;
		
		// Get status history from erp_order_status_history
		$status_history = array();
		if ($this->db->table_exists('erp_order_status_history')) {
			$erp_order = $this->db->select('id')->from('erp_orders')->where('order_number', $order_no)->limit(1)->get()->row();
			$erp_order_id = !empty($erp_order) ? $erp_order->id : null;
			if (!empty($erp_order_id)) {
				$status_history = $this->db->select('*')->from('erp_order_status_history')
					->where('order_id', $erp_order_id)->order_by('created_at', 'ASC')->get()->result();
			}
		}
		
		// Get ALL status entries from tbl_order_status (order_id = tbl_order_details.id)
		$additional_status = array();
		if (!empty($order_id)) {
			$additional_status = $this->db->select('*')->from('tbl_order_status')
				->where('order_id', $order_id)
				->order_by('created_at', 'ASC')->get()->result();
		}
		
		// Build timeline items (same logic as order view)
		$timeline_items = $this->_build_timeline_items($order_data, $additional_status, $status_history);
		
		$data['order_data'] = $order_data;
		$data['timeline_items'] = $timeline_items;
		$data['order_no'] = $order_no;
		
		$this->load->view('vendor/orders/timeline_partial', $data);
	}
	
	/**
	 * Build timeline items array from order data and status history
	 *
	 * @param	array	$order_data		Order data
	 * @param	array	$additional_status	Additional status entries
	 * @param	array	$status_history		Status history
	 * @return	array	Timeline items
	 */
	private function _build_timeline_items($order_data, $additional_status, $status_history)
	{
		$status_title_map = array(
			'1' => 'New Order / Pending',
			'2' => 'Processing',
			'3' => 'Out for Delivery',
			'4' => 'Delivered',
			'7' => 'Return'
		);
		
		$timeline_items = array();
		$od = $order_data[0];
		
		$timeline_items[] = array('status' => 'Order Placed', 'date' => $od->order_date, 'completed' => true, 'notes' => '');
		
		// Add ALL entries from tbl_order_status (order_id = tbl_order_details.id)
		if (!empty($additional_status)) {
			foreach ($additional_status as $status) {
				$display_status = isset($status_title_map[$status->status_title]) 
					? $status_title_map[$status->status_title] 
					: $status->status_title;
				$timeline_items[] = array('status' => $display_status, 'date' => $status->created_at, 'completed' => true, 'notes' => isset($status->status_desc) ? $status->status_desc : '');
			}
		}
		
		// Add tbl_order_details entries that may not have tbl_order_status records
		if (!empty($od->track_date) && !empty($od->erp_courier_id)) {
			$has_courier = false;
			foreach ($timeline_items as $item) {
				if (stripos($item['status'], 'Courier') !== false || stripos($item['status'], 'Shipper') !== false) {
					$has_courier = true;
					break;
				}
			}
			if (!$has_courier) {
				$timeline_items[] = array('status' => 'Courier Assigned', 'date' => $od->track_date, 'completed' => true, 'notes' => 'Courier and tracking details added');
			}
		}
		if (!empty($od->ready_to_ship_time)) {
			$has_ready = false;
			foreach ($timeline_items as $item) {
				if (stripos($item['status'], 'Ready to Ship') !== false) {
					$has_ready = true;
					break;
				}
			}
			if (!$has_ready) {
				$timeline_items[] = array('status' => 'Ready to Ship', 'date' => $od->ready_to_ship_time, 'completed' => true, 'notes' => 'Order marked as ready to ship');
			}
		}
		if (!empty($od->shipping_label)) {
			$has_label = false;
			foreach ($timeline_items as $item) {
				if (stripos($item['status'], 'Label') !== false || stripos($item['status'], 'label') !== false) {
					$has_label = true;
					break;
				}
			}
			if (!$has_label) {
				$timeline_items[] = array('status' => 'Shipping Label Generated', 'date' => !empty($od->processing_date) ? $od->processing_date : $od->order_date, 'completed' => true, 'notes' => 'Shipping label has been generated');
			}
		}
		
		if (!empty($status_history)) {
			foreach ($status_history as $history) {
				if (isset($history->status_type) && $history->status_type == 'order_status') {
					$status_label = '';
					switch (isset($history->new_status) ? $history->new_status : '') {
						case '1': $status_label = 'Pending'; break;
						case '2': $status_label = 'Processing'; break;
						case '3': $status_label = 'Out for Delivery'; break;
						case '4': $status_label = 'Delivered'; break;
						case 'label_generated': $status_label = 'Shipping Label Generated'; break;
						case 'shipper_selected': $status_label = 'Shipper Selected'; break;
						case '7': $status_label = 'Return'; break;
						default: $status_label = ucfirst(isset($history->new_status) ? $history->new_status : '');
					}
					$exists = false;
					foreach ($timeline_items as $item) {
						if (stripos($item['status'], $status_label) !== false || stripos($status_label, $item['status']) !== false) {
							$exists = true;
							break;
						}
					}
					if (!$exists) {
						$timeline_items[] = array('status' => $status_label, 'date' => $history->created_at, 'completed' => true, 'notes' => isset($history->notes) ? $history->notes : '');
					}
				}
			}
		}
		
		usort($timeline_items, function($a, $b) {
			return strtotime($a['date']) - strtotime($b['date']);
		});
		
		return $timeline_items;
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
	
	//Not In Use
	public function set_shipper___(){
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
		if ($order_data->order_status != '1' && $order_data->order_status != 1) {
			echo json_encode([
				'status' => '400',
				'message' => 'Order must be in pending status to set shipper. Current status: ' . $order_data->order_status,
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
		$processing_date = date("Y-m-d H:i:s");
		
		$this->db->where('id', $order_id);
		$update_result = $this->db->update('tbl_order_details', array(
			'courier' => $courier_value,
			'order_status' => '2',
			'processing_date' => $processing_date
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

 
	public function bulk_set_shipper(){
		header('Content-Type: application/json');

		$order_ids = $this->input->post('order_ids');
		$courier   = $this->input->post('courier');

		if (empty($order_ids) || !is_array($order_ids) || empty($courier)) {
			echo json_encode([
				'status'  => '400',
				'message' => 'Order IDs and courier type are required.',
			]);
			return;
		}

		if (!in_array($courier, ['manual'])) {
			echo json_encode([
				'status'  => '400',
				'message' => 'Invalid courier type.',
			]);
			return;
		}

		$courier_value = ($courier == 'manual') ? 'manual' : $courier;

	   // ==============================
		// ✅ STEP 1: FAST PRE-VALIDATION
		// ==============================

		$orders = $this->db->select('id, order_status, courier,order_unique_id')
			->from('tbl_order_details')
			->where_in('id', $order_ids)
			->get()
			->result();

		if (count($orders) !== count($order_ids)) {
			echo json_encode([
				'status'  => '400',
				'message' => 'Some selected orders were not found.',
			]);
			return;
		}

		foreach ($orders as $order) {
			if ($order->order_status != '1') {
				echo json_encode([
					'status'  => '400',
					'message' => "Order No {$order->order_unique_id} is not in pending status.",
				]);
				return;
			}
			if ($order->courier == $courier_value) {
				echo json_encode([
					'status'  => '400',
					'message' => "Order No {$order->order_unique_id} already has selected shipper.",
				]);
				return;
			}
		}

		// ==============================
		// ✅ STEP 2: ALL VALID → UPDATE
		// ==============================

		$this->db->trans_begin();
		$processing_date = date("Y-m-d H:i:s");

		foreach ($order_ids as $order_id) {

			$this->db->where('id', $order_id);
			$this->db->update('tbl_order_details', [
				'courier'         => $courier_value,
				'order_status'    => '2',
				'processing_date' => $processing_date
			]);

			$this->db->insert('tbl_order_status', [
				'order_id'     => $order_id,
				'user_id'      => isset($this->current_vendor['id']) ? $this->current_vendor['id'] : 0,
				'product_id'   => 0,
				'status_title' => 'Shipper Selected',
				'status_desc'  => 'Shipper set to ' . 
					($courier == 'manual' ? 'Self Delivery' : '3rd Party ('.$courier.')') . 
					' (Bulk)',
				'created_at'   => date('Y-m-d H:i:s')
			]);
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode([
				'status'  => '400',
				'message' => 'Failed to update orders.',
			]);
		} else {
			$this->db->trans_commit();
			echo json_encode([
				'status'  => '200',
				'message' => count($order_ids) . ' order(s) updated successfully.',
			]);
		}
	}
 
	public function bulk_save_third_party_shipping(){
    header('Content-Type: application/json');

    $response = function($status, $message, $extra = []) {
        echo json_encode([
            'status'  => $status,
            'message' => $message,
            'data'    => $extra,
            'csrf'    => [
                'name' => $this->security->get_csrf_token_name(),
                'hash' => $this->security->get_csrf_hash()
            ]
        ]);
        exit;
    };

    $order_ids = $this->input->post('order_ids');
    $third_party_provider = trim($this->input->post('third_party_provider', true));
    $length  = (float) $this->input->post('length');
    $breadth = (float) $this->input->post('breadth');
    $height  = (float) $this->input->post('height');
    $weight  = (float) $this->input->post('weight');
    $schedule_date = $this->input->post('schedule_date', true);
    $from_time     = $this->input->post('from_Time', true);
    $to_time       = $this->input->post('to_Time', true);
    $pickup_address_id = $this->input->post('pickup_address_id', true);

    // ===============================
    // BASIC VALIDATION
    // ===============================

    if (empty($order_ids) || !is_array($order_ids) || empty($third_party_provider)) {
        $response('400', 'Order IDs and provider are required.');
    }

    if ($length <= 0 || $breadth <= 0 || $height <= 0 || $weight <= 0) {
        $response('400', 'Package dimensions and weight must be greater than 0.');
    }

    // Validate provider allowed
    $providers = $this->db->select('shipping_providers')
        ->from('erp_clients')
        ->where('id', $this->current_vendor['id'])
        ->get()->row_array();

    $allowed_providers = explode(",", $providers['shipping_providers'] ?? '');

    if (!in_array($third_party_provider, $allowed_providers)) {
        $response('400', 'Invalid provider.');
    }

    // Provider config
    $provider_config = $this->db->select('*')
        ->from('erp_shipping_providers')
        ->where('provider', $third_party_provider)
        ->where('client_id', $this->current_vendor['id'])
        ->limit(1)
        ->get()->row();

    if (!$provider_config) {
        $response('400', 'Shipping provider configuration not found.');
    }

    $this->load->model('Shipping_model');

    // ===============================
    // FETCH ORDERS
    // ===============================

    $orders = $this->db->select('*')
        ->from('tbl_order_details')
        ->where_in('id', $order_ids)
        ->get()
        ->result();

    if (empty($orders)) {
        $response('400', 'No valid orders found.');
    }

    // ===============================
    // PRE-VALIDATION OF ALL ORDERS
    // ===============================

    $valid_orders  = [];
    $failed_orders = [];

    foreach ($orders as $order_data) {

        $order_id = $order_data->id;
        $order_unique_id = $order_data->order_unique_id;

        if ($order_data->order_status != 1) {
            $failed_orders[$order_unique_id] = 'Not in pending status';
            continue;
        }

        if (!empty($order_data->shipment_id)) {
            $failed_orders[$order_unique_id] = 'Shipment already created';
            continue;
        }

        $items_count = $this->db
            ->where('order_id', $order_id)
            ->count_all_results('tbl_order_items');

        if ($items_count == 0) {
            $failed_orders[$order_unique_id] = 'No order items found';
            continue;
        }

        // ===============================
        // PROPER ADDRESS VALIDATION
        // ===============================

        $is_deliver_at_school = (
            isset($order_data->is_deliver_at_school) &&
            (int)$order_data->is_deliver_at_school === 1
        );

        $addr_row = null;

        if ($is_deliver_at_school) {

            $order_item = $this->db->select('branch_id, school_id')
                ->from('tbl_order_items')
                ->where('order_id', $order_id)
                ->limit(1)
                ->get()
                ->row();

            if ($order_item) {

                if (!empty($order_item->branch_id)) {

                    $addr_row = $this->db->select('id')
                        ->from('erp_school_branches')
                        ->where('id', (int)$order_item->branch_id)
                        ->limit(1)
                        ->get()
                        ->row();

                } elseif (!empty($order_item->school_id)) {

                    $addr_row = $this->db->select('id')
                        ->from('erp_schools')
                        ->where('id', (int)$order_item->school_id)
                        ->limit(1)
                        ->get()
                        ->row();
                }
            }

        } else {

            $addr_row = $this->db->select('id')
                ->from('tbl_order_address')
                ->where('order_id', $order_id)
                ->limit(1)
                ->get()
                ->row();
        }

        if (!$addr_row) {
            $failed_orders[$order_unique_id] = 'Delivery address not found';
            continue;
        }

        $valid_orders[] = $order_data;
    }

    if (empty($valid_orders)) {

        $message = "No orders were eligible for third-party booking.\n\n";
        $message .= "Total Selected: " . count($orders) . "\n";
        $message .= "Total Rejected: " . count($failed_orders) . "\n\n";

        if (!empty($failed_orders)) {
            $message .= "Rejected Orders:\n";
            foreach ($failed_orders as $order_no => $reason) {
                $message .= "- Order {$order_no}: {$reason}\n";
            }
        }

        $response('400', $message);
    }

    // ===============================
    // PROCESS VALID ORDERS
    // ===============================

    $success_count = 0;

    foreach ($valid_orders as $order_data) {

        $order_id = $order_data->id;
        $order_unique_id = $order_data->order_unique_id;

        try {

			// ===============================
			// DELIVERY ADDRESS (School or Normal)
			// ===============================
				$is_deliver_at_school = (isset($order_data->is_deliver_at_school) 
				&& (int)$order_data->is_deliver_at_school === 1);

				$addr_row = null;

		 	if ($is_deliver_at_school) {

				// Get first order item with branch or school
				$order_item = $this->db->select('branch_id, school_id')
					->from('tbl_order_items')
					->where('order_id', $order_id)
					->limit(1)
					->get()
					->row();

				if ($order_item) {

					// ===============================
					// BRANCH ADDRESS
					// ===============================
					if (!empty($order_item->branch_id)) {

						$addr_row = $this->db->select('
								sb.branch_name as name,
								sb.address,
								sb.pincode,
								c.name as city,
								st.name as state
							')
							->from('erp_school_branches sb')
							->join('cities c', 'c.id = sb.city_id', 'left')
							->join('states st', 'st.id = sb.state_id', 'left')
							->where('sb.id', (int)$order_item->branch_id)
							->limit(1)
							->get()
							->row();

					} 
					// ===============================
					// SCHOOL ADDRESS
					// ===============================
					elseif (!empty($order_item->school_id)) {

						$addr_row = $this->db->select('
								s.school_name as name,
								s.address,
								s.pincode,
								c.name as city,
								st.name as state
							')
							->from('erp_schools s')
							->join('cities c', 'c.id = s.city_id', 'left')
							->join('states st', 'st.id = s.state_id', 'left')
							->where('s.id', (int)$order_item->school_id)
							->limit(1)
							->get()
							->row();
					}
				}

			} else {

				// ===============================
				// NORMAL DELIVERY ADDRESS
				// ===============================
				$addr_row = $this->db->select('*')
					->from('tbl_order_address')
					->where('order_id', $order_id)
					->limit(1)
					->get()
					->row();
			}

			// If still empty → fallback error
			if (!$addr_row) {
				throw new Exception('Delivery address not found.');
			}
							
									
			$delivery_address_full = '';

			$parts = array_filter([
				$addr_row->address ?? '',
				$addr_row->city ?? '',
				$addr_row->state ?? '',
				$addr_row->pincode ?? '',
				'India'
			]);

			$delivery_address_full = implode(', ', $parts);



            // PRODUCT DETAILS
   
			$consignments = array();
			$product_details = array();
			$declared_value=0;
			$total_weight=0;
			$total_weight_gm=0;	   
			$order_type = strtolower($order_data->type_order);
	 
 
		if ($order_type === 'bookset') {
			$order_products = $this->db->select('bookset_packages_json')
				->from('tbl_order_items')
				->where('order_id', $order_id)
				->get()
				->result();
				
			if (empty($order_products)) {
				throw new Exception('No bookset items found.');
			}

			foreach ($order_products as $row) {

				if (empty($row->bookset_packages_json)) {
					continue;
				}

				$json = json_decode($row->bookset_packages_json, true);
				if (!isset($json['packages'])) {
					continue;
				}

				foreach ($json['packages'] as $package) {

					if (empty($package['products'])) {
						continue;
					}

					foreach ($package['products'] as $product) {

						$product_name = $product['display_name'] ?? 'Book';
						$qty          = (int) ($product['quantity'] ?? 1);
						$price_total  = (float) ($product['total_price'] ?? 0);
						$weight_gm    = (float) ($product['weight'] ?? 0);

						$declared_value += $price_total;

						if ($weight_gm <= 0) {
							$weight_gm = 500;
						}

						$total_weight_gm += ($weight_gm * $qty);

						$product_details[] = array(
							"product_category"               => "Others",
							"product_sub_category"           => $package['package_name'] ?? "",
							"product_name"                   => sanitize_allowed_chars($product_name),
							"product_quantity"               => $qty,
							"each_product_invoice_amount"    => $price_total,
							"each_product_collectable_amount"=> 0,
							"hsn"                            => $package['hsn'] ?? ""
						);
					}
				}
			}

			// Convert gm to kg
			$total_weight = round($total_weight_gm / 1000, 2);
		}
		else {

			$order_products = $this->db->select('product_title, product_qty, total_price, weight, hsn')
				->from('tbl_order_items')
				->where('order_id', $order_id)
				->get()
				->result();

			if (empty($order_products)) {
				throw new Exception('No order items found.');
			}

			foreach ($order_products as $item) {

				$product_name = $item->product_title;
				$qty          = (int) $item->product_qty;
				$price_total  = (float) $item->total_price;
				$weight_gm    = (float) $item->weight;

				$declared_value += $price_total;

				if ($weight_gm <= 0) {
					$weight_gm = 500;
				}

				$total_weight_gm += ($weight_gm * $qty);

				$product_details[] = array(
					"product_category"               => "Others",
					"product_sub_category"           => "",
					"product_name"                   => sanitize_allowed_chars($product_name),
					"product_quantity"               => $qty,
					"each_product_invoice_amount"    => $price_total / max($qty,1),
					"each_product_collectable_amount"=> 0,
					"hsn"                            => $item->hsn ?? ""
				);
			}

			$total_weight = round($total_weight_gm / 1000, 2);
		}


		/*echo json_encode([
				'debug' => $product_details,
				'csrf'  => [
					'name' => $this->security->get_csrf_token_name(),
					'hash' => $this->security->get_csrf_hash()
				]
			]);
			exit;*/

            // CALL PROVIDER API
            $api_res = null;

            switch (strtolower($third_party_provider)) {

                case 'velocity':
                    $api_res = $this->Shipping_model->create_velocity_booking([
                        'provider' => $provider_config,
                        'order_data' => $order_data,
                        'address_row' => $addr_row,
                        'product_details' => $product_details,
                        'length' => $length,
                        'breadth' => $breadth,
                        'height' => $height,
                        'weight' => $weight,
                        'schedule_date' => $schedule_date,
                        'from_time' => $from_time,
                        'to_time' => $to_time
                    ]);
                    break;

                case 'bigship':
                    $api_res = $this->Shipping_model->create_bigship_booking([
                        'provider' => $provider_config,
                        'order_data' => $order_data,
                        'address_row' => $addr_row,
                        'product_details' => $product_details,
                        'length' => $length,
                        'breadth' => $breadth,
                        'height' => $height,
                        'weight' => $weight,
                        'pickup_address_id' => $pickup_address_id
                    ]);
                    break;

                case 'shiprocket':
                    $api_res = ['status' => 'success'];
                    break;

                default:
                    $failed_orders[$order_unique_id] = 'Provider not implemented';
                    continue 2;
            }

            if (!isset($api_res['status']) || $api_res['status'] !== 'success') {
                $failed_orders[$order_unique_id] = $api_res['message'] ?? 'API booking failed';
                continue;
            }

            // ===============================
            // DB UPDATE PER ORDER
            // ===============================

            $this->db->trans_begin();

			$awb_no = $api_res['awb_no'] ?? null;
			$system_order_id = $api_res['system_order_id'] ?? null;
			$track_url = $api_res['track_url'] ?? null;
			$provider_request = $api_res['provider_request'] ?? null;
			$provider_response = $api_res['provider_response'] ?? null;


			// ===============================
			// THIRD PARTY TABLE
			// ===============================
			if ($this->db->table_exists('tbl_order_third_party_shipping')) {

				$tp_data = [
					'order_id'               => $order_id,
					'order_unique_id'        => $order_unique_id,
					'invoice_number'         => $order_data->invoice_no ?? null,
					'delivery_address_full'  => $delivery_address_full,
					'length_cm'              => $length ?: null,
					'breadth_cm'             => $breadth ?: null,
					'height_cm'              => $height ?: null,
					'weight_kg'              => $weight ?: null,
					'third_party_provider'   => $third_party_provider,
					'schedule_date'          => $schedule_date ?: null,
					'from_time'              => $from_time ?: null,
					'to_time'                => $to_time ?: null,
					
					'provider_request'  	=> $provider_request,
					'provider_response'	 	=> $provider_response,
					'system_order_id'   	=> $system_order_id,
					'awb_no'            	=> $awb_no,
					'track_url'         	=> $track_url,
					'booking_time'      	=> date('Y-m-d H:i:s')
				];

				$existing = $this->db->select('id')
					->from('tbl_order_third_party_shipping')
					->where('order_id', $order_id)
					->get()
					->row();

				if ($existing) {
					$this->db->where('id', $existing->id)
							 ->update('tbl_order_third_party_shipping', $tp_data);
				} else {
					$this->db->insert('tbl_order_third_party_shipping', $tp_data);
				}
			}


            $this->db->where('id', $order_id)
                ->update('tbl_order_details', [
                    'courier' => '3rd_party',
                    'third_party_provider' => $third_party_provider,
                    'pkg_length_cm' => $length,
                    'pkg_breadth_cm' => $breadth,
                    'pkg_height_cm' => $height,
                    'pkg_weight_kg' => $weight,
					'shipment_id' => $system_order_id,
					'awb_no'      => $awb_no,
                    'order_status' => 2,
                    'processing_date' => date('Y-m-d H:i:s')
                ]);

            $this->db->insert('tbl_order_status', [
                'order_id' => $order_id,
                'user_id' => $this->current_vendor['id'] ?? 0,
                'product_id' => 0,
                'status_title' => '3rd Party Selected',
                'status_desc' => ucfirst($third_party_provider) .
                    " (L:$length B:$breadth H:$height W:$weight kg) (Bulk)",
                'created_at' => date('Y-m-d H:i:s')
            ]);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $failed_orders[$order_unique_id] = 'Database update failed';
                continue;
            }

            $this->db->trans_commit();
            $success_count++;

        } catch (Exception $e) {
            $failed_orders[$order_unique_id] = $e->getMessage();
        }
    }

    $response('200',
        "$success_count orders booked successfully, "
        . count($failed_orders) . " failed.",
        $failed_orders
    );
}
	

	public function get_order_couriers()
	{
		header('Content-Type: application/json');
		$vendor_id = $this->current_vendor['id'];
		$couriers = $this->Courier_model->get_couriers($vendor_id);
		// Filter only active couriers
		$active_couriers = array_filter($couriers, function($c) { return isset($c['status']) && $c['status'] == 1; });
		echo json_encode(array('success' => true, 'couriers' => array_values($active_couriers)));
	}
	
	/**
	 * Save courier selection and AWB for self-delivery order
	 * Saves erp_courier_id, awb_no, track_url to tbl_order_details
	 *
	 * @return	void
	 */
	public function save_order_courier_awb()
	{
		header('Content-Type: application/json');
		$order_unique_id = $this->input->post('order_unique_id');
		$erp_courier_id = (int) $this->input->post('erp_courier_id');
		$awb_no = trim($this->input->post('awb_no'));
		
		if (empty($order_unique_id) || empty($erp_courier_id)) {
			echo json_encode(array('status' => '400', 'message' => 'Order ID and courier selection are required.'));
			return;
		}
		
		$order = $this->Order_model->get_order($order_unique_id);
		if (empty($order)) {
			echo json_encode(array('status' => '400', 'message' => 'Order not found.'));
			return;
		}
		
		$order_data = $order[0];
		$order_id = $order_data->id;
		
		if ($order_data->courier != 'manual') {
			echo json_encode(array('status' => '400', 'message' => 'Order must have Self Delivery selected.'));
			return;
		}
		
		$vendor_id = $this->current_vendor['id'];
		$courier = $this->Courier_model->get_courier_by_id($erp_courier_id, $vendor_id);
		if (empty($courier)) {
			echo json_encode(array('status' => '400', 'message' => 'Invalid courier selected.'));
			return;
		}
		
		// Save raw tracking_link from courier to track_url (no AWB replacement)
		$track_url = !empty($courier['tracking_link']) ? $courier['tracking_link'] : null;
		
		$update_data = array(
			'erp_courier_id' => $erp_courier_id,
			'awb_no' => $awb_no ?: null,
			'track_url' => $track_url,
			'track_date' => date('Y-m-d H:i:s')
		);
		
		// Check if erp_courier_id column exists (for backward compatibility)
		if (!$this->db->field_exists('erp_courier_id', 'tbl_order_details')) {
			unset($update_data['erp_courier_id']);
		}
		
		$this->db->where('id', $order_id);
		$result = $this->db->update('tbl_order_details', $update_data);
		
		if ($result) {
			// Add tbl_order_status entry for timeline
			$courier_name = isset($courier['courier_name']) ? $courier['courier_name'] : 'Courier';
			$this->db->insert('tbl_order_status', array(
				'order_id' => $order_id,
				'user_id' => isset($this->current_vendor['id']) ? $this->current_vendor['id'] : 0,
				'product_id' => 0,
				'status_title' => 'Courier Assigned',
				'status_desc' => 'Courier selected: ' . $courier_name . (!empty($awb_no) ? ' (AWB: ' . $awb_no . ')' : ''),
				'created_at' => date('Y-m-d H:i:s')
			));
			echo json_encode(array('status' => '200', 'message' => 'Courier saved successfully.'));
		} else {
			echo json_encode(array('status' => '400', 'message' => 'Failed to save.'));
		}
	}
	
	/**
	 * Get vendor address from erp_clients (for 3rd party shipping pickup)
	 * Fetches from tenant DB erp_clients (same as Profile - vendor's synced client data)
	 *
	 * @return	void
	 */
	public function get_vendor_address()
	{
		header('Content-Type: application/json');
		// Use tenant DB ($this->db) - erp_clients in tenant has vendor's address (synced from master)
		// Same approach as Profile::get_profile and Pdf_model
		$row = $this->db->select('address, pincode')
			->from('erp_clients')
			->limit(1)
			->get()
			->row_array();
		if (empty($row)) {
			echo json_encode(array('success' => false, 'message' => 'Vendor not found in erp_clients.'));
			return;
		}
		$addr = isset($row['address']) ? trim($row['address']) : '';
		$pincode = isset($row['pincode']) ? trim($row['pincode']) : '';
		$parts = array_filter(array($addr, $pincode));
		$address_full = implode(', ', $parts);
		echo json_encode(array(
			'success' => true,
			'address' => $addr,
			'state' => '',
			'country' => '',
			'pincode' => $pincode,
			'address_full' => $address_full ?: 'Please add address in Profile.'
		));
	}
	
	/**
	 * Save 3rd party shipping details (Shiprocket, Big Ship)
	 * Saves to tbl_order_details and tbl_order_third_party_shipping
	 *
	 * @return	void
	 */
	/*public function save_third_party_shipping()
	{ 
		header('Content-Type: application/json');
		$order_unique_id = $this->input->post('order_unique_id');
		$third_party_provider = trim($this->input->post('third_party_provider')); // shiprocket, bigship
		$length = (float) $this->input->post('length');
		$breadth = (float) $this->input->post('breadth');
		$height = (float) $this->input->post('height');
		$weight = (float) $this->input->post('weight');
		
		if (empty($order_unique_id) || empty($third_party_provider)) {
			echo json_encode(array('status' => '400', 'message' => 'Order ID and 3rd party provider are required.',	'csrf' => ['name' => $this->security->get_csrf_token_name(),'hash' => $this->security->get_csrf_hash()
			]));
			return;
		}
		if (!in_array($third_party_provider, array('shiprocket', 'bigship'))) {
			echo json_encode(array('status' => '400', 'message' => 'Invalid provider. Use Shiprocket or Big Ship.',	'csrf' => ['name' => $this->security->get_csrf_token_name(),'hash' => $this->security->get_csrf_hash()));
			return;
		}
		
		$order = $this->Order_model->get_order($order_unique_id);
		if (empty($order)) {
			echo json_encode(array('status' => '400', 'message' => 'Order not found.',	'csrf' => ['name' => $this->security->get_csrf_token_name(),'hash' => $this->security->get_csrf_hash()));
			return;
		}
		
		$order_data = $order[0];
		$order_id = $order_data->id;
		
		if ($order_data->order_status != '2' && $order_data->order_status != 2) {
			echo json_encode(array('status' => '400', 'message' => 'Order must be in processing status.',	'csrf' => ['name' => $this->security->get_csrf_token_name(),'hash' => $this->security->get_csrf_hash()));
			return;
		}
		
		// Build delivery address from tbl_order_address (same logic as order view)
		$addr_row = $this->db->select('*')->from('tbl_order_address')
			->where('order_id', $order_id)->order_by('id', 'ASC')->limit(1)->get()->row();
		$delivery_address_full = '';
		if ($addr_row) {
			$addr = isset($addr_row->address) ? trim($addr_row->address) : '';
			$city = isset($addr_row->city) ? trim($addr_row->city) : '';
			$state = isset($addr_row->state) ? trim($addr_row->state) : '';
			$pincode = isset($addr_row->pincode) ? trim($addr_row->pincode) : '';
			$country = isset($addr_row->country) ? trim($addr_row->country) : '';
			$parts = array_filter(array($addr, $city, $state, $pincode, $country));
			$delivery_address_full = implode(', ', $parts);
		}
		// Fallback: deliver-at-school - address may come from school/branch in order items
		if (empty($delivery_address_full)) {
			$items = $this->db->select('branch_id, school_id')->from('tbl_order_items')
				->where('order_id', $order_id)->get()->result();
			foreach ($items as $oi) {
				if (!empty($oi->branch_id)) {
					$br = $this->db->select('sb.branch_name, sb.address, sb.pincode, s.school_name, c.name as city_name, st.name as state_name')
						->from('erp_school_branches sb')
						->join('erp_schools s', 's.id = sb.school_id', 'left')
						->join('cities c', 'c.id = sb.city_id', 'left')
						->join('states st', 'st.id = sb.state_id', 'left')
						->where('sb.id', (int)$oi->branch_id)->limit(1)->get()->row();
					if ($br) {
						$p = array_filter(array(
							$br->branch_name . (!empty($br->school_name) ? ' (' . $br->school_name . ')' : ''),
							isset($br->address) ? $br->address : '',
							isset($br->city_name) ? $br->city_name : '',
							isset($br->state_name) ? $br->state_name : '',
							isset($br->pincode) ? $br->pincode : ''
						));
						$delivery_address_full = implode(', ', $p);
						break;
					}
				}
				if (!empty($oi->school_id) && empty($delivery_address_full)) {
					$sch = $this->db->select('s.school_name, s.address, s.pincode, c.name as city_name, st.name as state_name')
						->from('erp_schools s')
						->join('cities c', 'c.id = s.city_id', 'left')
						->join('states st', 'st.id = s.state_id', 'left')
						->where('s.id', (int)$oi->school_id)->limit(1)->get()->row();
					if ($sch) {
						$p = array_filter(array(
							isset($sch->school_name) ? $sch->school_name : '',
							isset($sch->address) ? $sch->address : '',
							isset($sch->city_name) ? $sch->city_name : '',
							isset($sch->state_name) ? $sch->state_name : '',
							isset($sch->pincode) ? $sch->pincode : ''
						));
						$delivery_address_full = implode(', ', $p);
						break;
					}
				}
			}
		}
		
		// Vendor pickup address from erp_clients (tenant DB - same as get_vendor_address)
		$pickup_row = $this->db->select('address, pincode')->from('erp_clients')->limit(1)->get()->row_array();
		$pickup_address = isset($pickup_row['address']) ? trim($pickup_row['address']) : '';
		$pickup_pincode = isset($pickup_row['pincode']) ? trim($pickup_row['pincode']) : '';
		$pickup_state = '';
		$pickup_country = '';
		$pickup_parts = array_filter(array($pickup_address, $pickup_pincode));
		$pickup_address_full = implode(', ', $pickup_parts);
		
		// Update tbl_order_details
		$update_data = array(
			'courier' => '3rd_party',
			'third_party_provider' => $third_party_provider,
			'pkg_length_cm' => $length > 0 ? $length : null,
			'pkg_breadth_cm' => $breadth > 0 ? $breadth : null,
			'pkg_height_cm' => $height > 0 ? $height : null,
			'pkg_weight_kg' => $weight > 0 ? $weight : null
		);
		
		// Check if new columns exist
		if (!$this->db->field_exists('third_party_provider', 'tbl_order_details')) {
			unset($update_data['third_party_provider']);
		}
		if (!$this->db->field_exists('pkg_length_cm', 'tbl_order_details')) {
			unset($update_data['pkg_length_cm'], $update_data['pkg_breadth_cm'], $update_data['pkg_height_cm'], $update_data['pkg_weight_kg']);
		}
		
		$this->db->where('id', $order_id);
		$result = $this->db->update('tbl_order_details', $update_data);
		
		if (!$result) {
			echo json_encode(array('status' => '400', 'message' => 'Failed to update order.',	'csrf' => ['name' => $this->security->get_csrf_token_name(),'hash' => $this->security->get_csrf_hash()));
			return;
		}
		
		// Insert or update tbl_order_third_party_shipping
		if ($this->db->table_exists('tbl_order_third_party_shipping')) {
			$existing = $this->db->select('id')->from('tbl_order_third_party_shipping')
				->where('order_id', $order_id)->limit(1)->get()->row();
			
			$tp_data = array(
				'order_id' => $order_id,
				'order_unique_id' => $order_unique_id,
				'invoice_number' => isset($order_data->invoice_no) ? $order_data->invoice_no : null,
				'delivery_address_full' => $delivery_address_full,
				'pickup_address_full' => $pickup_address_full,
				'pickup_state' => $pickup_state,
				'pickup_country' => $pickup_country,
				'length_cm' => $length > 0 ? $length : null,
				'breadth_cm' => $breadth > 0 ? $breadth : null,
				'height_cm' => $height > 0 ? $height : null,
				'weight_kg' => $weight > 0 ? $weight : null,
				'third_party_provider' => $third_party_provider
			);
			
			if ($existing) {
				$this->db->where('id', $existing->id);
				$this->db->update('tbl_order_third_party_shipping', $tp_data);
			} else {
				$this->db->insert('tbl_order_third_party_shipping', $tp_data);
			}
		}
		
		// Add tbl_order_status entry
		$this->db->insert('tbl_order_status', array(
			'order_id' => $order_id,
			'user_id' => isset($this->current_vendor['id']) ? $this->current_vendor['id'] : 0,
			'product_id' => 0,
			'status_title' => '3rd Party Selected',
			'status_desc' => '3rd party shipping: ' . ucfirst($third_party_provider) . ' (L:' . $length . ' B:' . $breadth . ' H:' . $height . ' W:' . $weight . ' kg)',
			'created_at' => date('Y-m-d H:i:s')
		));
		
		echo json_encode(array('status' => '200', 'message' => '3rd party shipping saved successfully.'));
	}*/
	
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

		// Check if order is marked as ready to ship
		$is_ready_to_ship = isset($order_data->ready_to_ship) && $order_data->ready_to_ship == 1;
		if (!$is_ready_to_ship) {
			echo json_encode([
				'status' => '400',
				'message' => 'Order must be marked as ready to ship before moving to out for delivery.',
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
	 * Mark single order as ready to ship
	 *
	 * @return	void
	 */
	public function mark_ready_to_ship()
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
				'message' => 'Order must be in processing status to mark as ready to ship.',
			]);
			return;
		}

		// For self delivery, verify shipping label exists
		if ($order_data->courier == 'manual' && empty($order_data->shipping_label)) {
			echo json_encode([
				'status' => '400',
				'message' => 'Shipping label must be generated before marking order ready to ship.',
			]);
			return;
		}

		// For self delivery, verify courier is selected
		if ($order_data->courier == 'manual') {
			$erp_courier_id = isset($order_data->erp_courier_id) ? (int)$order_data->erp_courier_id : 0;
			if ($erp_courier_id <= 0) {
				echo json_encode([
					'status' => '400',
					'message' => 'Please select a courier before marking order ready to ship.',
				]);
				return;
			}
		}

		// Update order ready_to_ship status
		$ready_time = date("Y-m-d H:i:s");
		$this->db->where('id', $order_id);
		$this->db->update('tbl_order_details', array(
			'ready_to_ship' => 1,
			'ready_to_ship_time' => $ready_time
		));

		if ($this->db->affected_rows() > 0) {
			// Add status history
			$this->db->insert('tbl_order_status', array(
				'order_id' => $order_id,
				'user_id' => $order_data->user_id,
				'product_id' => 0,
				'status_title' => 'Ready to Ship',
				'status_desc' => 'Order marked as ready to ship',
				'created_at' => $ready_time
			));

			echo json_encode([
				'status' => '200',
				'message' => 'Order marked as ready to ship successfully.',
			]);
		} else {
			echo json_encode([
				'status' => '400',
				'message' => 'Failed to update order status.',
			]);
		}
	}

	/**
	 * Unmark single order as ready to ship
	 *
	 * @return	void
	 */
	public function unmark_ready_to_ship()
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

		// Verify order is in processing status and is marked as ready to ship
		if ($order_data->order_status != '2' && $order_data->order_status != 2) {
			echo json_encode([
				'status' => '400',
				'message' => 'Order must be in processing status.',
			]);
			return;
		}

		$is_ready_to_ship = isset($order_data->ready_to_ship) && $order_data->ready_to_ship == 1;
		if (!$is_ready_to_ship) {
			echo json_encode([
				'status' => '400',
				'message' => 'Order is not marked as ready to ship.',
			]);
			return;
		}

		// Update order ready_to_ship status
		$this->db->where('id', $order_id);
		$this->db->update('tbl_order_details', array(
			'ready_to_ship' => 0,
			'ready_to_ship_time' => null
		));

		if ($this->db->affected_rows() > 0) {
			// Add status history
			$this->db->insert('tbl_order_status', array(
				'order_id' => $order_id,
				'user_id' => $order_data->user_id,
				'product_id' => 0,
				'status_title' => 'Unmarked Ready to Ship',
				'status_desc' => 'Order unmarked as ready to ship',
				'created_at' => date("Y-m-d H:i:s")
			));

			echo json_encode([
				'status' => '200',
				'message' => 'Order unmarked as ready to ship successfully.',
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
	 * Move single order back from Out for Delivery to Processing
	 * Resets: shipping_label, courier, awb, track_url, ready_to_ship, etc.
	 *
	 * @return	void
	 */
	public function move_back_to_processing_single()
	{
		$order_unique_id = $this->input->post('order_unique_id');

		if (empty($order_unique_id)) {
			echo json_encode(['status' => '400', 'message' => 'Order ID is required.']);
			return;
		}

		$order = $this->Order_model->get_order($order_unique_id);
		if (empty($order)) {
			echo json_encode(['status' => '400', 'message' => 'Order not found.']);
			return;
		}

		$order_data = $order[0];
		$order_id = $order_data->id;

		if ($order_data->order_status != '3' && $order_data->order_status != 3) {
			echo json_encode(['status' => '400', 'message' => 'Order must be in out for delivery status to move back.']);
			return;
		}

		$reset_data = array(
			'order_status' => '2',
			'shipment_date' => null,
			'shipping_label' => null,
			'ship_order_id' => null,
			'erp_courier_id' => null,
			'awb_no' => null,
			'track_url' => null,
			'track_date' => null,
			'ready_to_ship' => 0,
			'ready_to_ship_time' => null,
			'courier' => '',
			'third_party_provider' => null,
			'pkg_length_cm' => null,
			'pkg_breadth_cm' => null,
			'pkg_height_cm' => null,
			'pkg_weight_kg' => null
		);

		if (!$this->db->field_exists('erp_courier_id', 'tbl_order_details')) {
			unset($reset_data['erp_courier_id']);
		}
		if (!$this->db->field_exists('ready_to_ship', 'tbl_order_details')) {
			unset($reset_data['ready_to_ship'], $reset_data['ready_to_ship_time']);
		}
		if (!$this->db->field_exists('ship_order_id', 'tbl_order_details')) {
			unset($reset_data['ship_order_id']);
		}
		if (!$this->db->field_exists('third_party_provider', 'tbl_order_details')) {
			unset($reset_data['third_party_provider'], $reset_data['pkg_length_cm'], $reset_data['pkg_breadth_cm'], $reset_data['pkg_height_cm'], $reset_data['pkg_weight_kg']);
		}

		// Remove 3rd party shipping entry if exists
		if ($this->db->table_exists('tbl_order_third_party_shipping')) {
			$this->db->where('order_id', $order_id);
			$this->db->delete('tbl_order_third_party_shipping');
		}

		$this->db->where('id', $order_id);
		$this->db->where('order_status', '3');
		$this->db->update('tbl_order_details', $reset_data);

		if ($this->db->affected_rows() > 0) {
			$this->db->where('order_id', $order_id);
			$this->db->update('tbl_order_items', array('pro_order_status' => '2'));
			$this->db->insert('tbl_order_status', array(
				'order_id' => $order_id,
				'user_id' => $order_data->user_id,
				'product_id' => 0,
				'status_title' => 'Moved Back to Processing',
				'status_desc' => 'Order moved back to processing (shipping details reset)',
				'created_at' => date("Y-m-d H:i:s")
			));
			echo json_encode(['status' => '200', 'message' => 'Order moved back to processing successfully.']);
		} else {
			echo json_encode(['status' => '400', 'message' => 'Failed to update order status.']);
		}
	}

	/**
	 * Move single order back from Processing to New Order (Pending)
	 *
	 * @return	void
	 */
	public function move_back_to_pending_single()
	{
		$order_unique_id = $this->input->post('order_unique_id');

		if (empty($order_unique_id)) {
			echo json_encode(['status' => '400', 'message' => 'Order ID is required.']);
			return;
		}

		$order = $this->Order_model->get_order($order_unique_id);
		if (empty($order)) {
			echo json_encode(['status' => '400', 'message' => 'Order not found.']);
			return;
		}

		$order_data = $order[0];
		$order_id = $order_data->id;

		if ($order_data->order_status != '2' && $order_data->order_status != 2) {
			echo json_encode(['status' => '400', 'message' => 'Order must be in processing status to move back.']);
			return;
		}

		$reset_data = array(
			'order_status' => '1',
			'processing_date' => null,
			'shipment_date' => null,
			'shipping_label' => null,
			'third_party_provider' => null,
			'pkg_length_cm' => null,
			'pkg_breadth_cm' => null,
			'pkg_height_cm' => null,
			'pkg_weight_kg' => null,
			'ship_order_id' => null,
			'erp_courier_id' => null,
			'awb_no' => null,
			'track_url' => null,
			'track_date' => null,
			'ready_to_ship' => 0,
			'ready_to_ship_time' => null,
			'barcode_path' => null,
			'courier' => ''
		);

		if (!$this->db->field_exists('erp_courier_id', 'tbl_order_details')) {
			unset($reset_data['erp_courier_id']);
		}
		if (!$this->db->field_exists('ready_to_ship', 'tbl_order_details')) {
			unset($reset_data['ready_to_ship'], $reset_data['ready_to_ship_time']);
		}
		if (!$this->db->field_exists('ship_order_id', 'tbl_order_details')) {
			unset($reset_data['ship_order_id']);
		}
		if (!$this->db->field_exists('third_party_provider', 'tbl_order_details')) {
			unset($reset_data['third_party_provider'], $reset_data['pkg_length_cm'], $reset_data['pkg_breadth_cm'], $reset_data['pkg_height_cm'], $reset_data['pkg_weight_kg']);
		}

		// Remove 3rd party shipping entry if exists
		if ($this->db->table_exists('tbl_order_third_party_shipping')) {
			$this->db->where('order_id', $order_id);
			$this->db->delete('tbl_order_third_party_shipping');
		}

		$this->db->where('id', $order_id);
		$this->db->where('order_status', '2');
		$this->db->update('tbl_order_details', $reset_data);

		if ($this->db->affected_rows() > 0) {
			$this->db->where('order_id', $order_id);
			$this->db->update('tbl_order_items', array('pro_order_status' => '1'));
			$this->db->insert('tbl_order_status', array(
				'order_id' => $order_id,
				'user_id' => $order_data->user_id,
				'product_id' => 0,
				'status_title' => 'Moved Back to New Order',
				'status_desc' => 'Order moved back to new order (pending)',
				'created_at' => date("Y-m-d H:i:s")
			));
			echo json_encode(['status' => '200', 'message' => 'Order moved back to new order successfully.']);
		} else {
			echo json_encode(['status' => '400', 'message' => 'Failed to update order status.']);
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
	 * Get logo as base64 for invoice (like shipping label - from erp_clients)
	 *
	 * @return	string	Base64 data URL or empty string
	 */
	private function _get_invoice_logo_base64()
	{
		$logo_path = !empty($this->current_vendor['logo']) ? trim($this->current_vendor['logo']) : '';
		if (empty($logo_path)) return '';
		$full_path = FCPATH . ltrim($logo_path, '/');
		if (file_exists($full_path)) {
			$file_size = @filesize($full_path);
			if ($file_size > 200000) return ''; // Skip if > 200KB to avoid dompdf memory exhaustion
			$logo_data = file_get_contents($full_path);
			if ($logo_data !== false) {
				$image_info = @getimagesize($full_path);
				$mime_type = ($image_info !== false && isset($image_info['mime'])) ? $image_info['mime'] : 'image/png';
				return 'data:' . $mime_type . ';base64,' . base64_encode($logo_data);
			}
		}
		// Fallback: try vendor frontend folder
		$vendor_folder = !empty($this->current_vendor['domain']) ? $this->current_vendor['domain'] : '';
		if (preg_match('/shivambook/i', $vendor_folder)) {
			$alt_path = FCPATH . 'shivam_book_frontend/' . ltrim($logo_path, '/');
			if (file_exists($alt_path) && @filesize($alt_path) <= 200000) {
				$logo_data = file_get_contents($alt_path);
				if ($logo_data !== false) {
					$image_info = @getimagesize($alt_path);
					$mime_type = ($image_info !== false && isset($image_info['mime'])) ? $image_info['mime'] : 'image/png';
					return 'data:' . $mime_type . ';base64,' . base64_encode($logo_data);
				}
			}
		}
		$alt_path = FCPATH . 'book_erp_frontend/' . ltrim($logo_path, '/');
		if (file_exists($alt_path) && @filesize($alt_path) <= 200000) {
			$logo_data = file_get_contents($alt_path);
			if ($logo_data !== false) {
				$image_info = @getimagesize($alt_path);
				$mime_type = ($image_info !== false && isset($image_info['mime'])) ? $image_info['mime'] : 'image/png';
				return 'data:' . $mime_type . ';base64,' . base64_encode($logo_data);
			}
		}
		return '';
	}

	/**
	 * Get order type label (Bookset, Individual, Uniform)
	 */
	private function _get_order_type_label($order_id, $order_row)
	{
		$type_order = isset($order_row['type_order']) ? strtolower($order_row['type_order']) : '';
		if (!empty($type_order)) return ucfirst($type_order);
		$items = $this->db->select('order_type')->from('tbl_order_items')->where('order_id', $order_id)->get()->result();
		foreach ($items as $item) {
			if (isset($item->order_type)) {
				if ($item->order_type == 'bookset' || $item->order_type == 'package') return 'Bookset';
				if ($item->order_type == 'uniform') return 'Uniform';
			}
		}
		return 'Individual';
	}

	/**
	 * Get items array for invoice (as objects)
	 */
	private function _get_invoice_items_arr($order_id)
	{
		return $this->db->select('*')->from('tbl_order_items')->where('order_id', $order_id)->order_by('id', 'ASC')->get()->result();
	}

	/**
	 * Get bookset products for invoice
	 */
	private function _get_invoice_bookset_products($order_id, $order_type_label)
	{
		if ($order_type_label != 'Bookset') return array();
		if ($this->db->table_exists('tbl_order_bookset_products')) {
			return $this->db->select('*')->from('tbl_order_bookset_products')->where('order_id', $order_id)->order_by('package_id', 'ASC')->order_by('id', 'ASC')->get()->result();
		}
		if ($this->db->table_exists('erp_bookset_order_products')) {
			return $this->db->select('*')->from('erp_bookset_order_products')->where('order_id', $order_id)->order_by('id', 'ASC')->get()->result();
		}
		return array();
	}

	/**
	 * Get school name for order
	 */
	private function _get_order_school_name($order_id, $order_row)
	{
		if (!empty($order_row['school_name'])) return $order_row['school_name'];
		if (!$this->db->table_exists('erp_schools')) return '';
		$item = $this->db->select('oi.school_id, s.school_name')->from('tbl_order_items oi')->join('erp_schools s', 's.id = oi.school_id', 'left')->where('oi.order_id', $order_id)->where('oi.school_id IS NOT NULL')->limit(1)->get()->row();
		return !empty($item) && !empty($item->school_name) ? $item->school_name : '';
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
		$this->db->where('(payment_status="success" OR payment_status="cod" OR payment_status="payment_at_school" OR payment_method="cod" OR payment_method="payment_at_school")');
		$query = $this->db->get();
		
		if ($query->num_rows() == 0)
		{
			show_error('Order details not found', 404);
			return;
		}
		
		$order_row = $query->row_array();
		
		// Get order address (by order_id - tbl_order_address stores shipping for each order)
		$shipping = array();
		$this->db->select('*');
		$this->db->from('tbl_order_address');
		$this->db->where('order_id', $order_id);
		$this->db->limit(1);
		$address_query = $this->db->get();
		if ($address_query->num_rows() > 0)
		{
			$shipping = $address_query->row_array();
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
		
		// Increase memory for PDF generation (dompdf can exhaust default limit with images)
		@ini_set('memory_limit', '256M');

		// Load helpers for invoice (price_format_decimal, rupees_word)
		$this->load->helper('common');
		
		// Load PDF library
		$this->load->library('pdf');

		// Suppress deprecation warnings from dompdf HTML5 parser
		error_reporting(E_ALL & ~E_DEPRECATED);

		// Vendor/company info from erp_clients (like shipping label)
		$order_details['logo_src'] = $this->_get_invoice_logo_base64();
		$order_details['company_name'] = !empty($this->current_vendor['name']) ? $this->current_vendor['name'] : 'Shivam Books';
		$order_details['company_address'] = !empty($this->current_vendor['address']) ? $this->current_vendor['address'] : '';
		$order_details['company_gstin'] = !empty($this->current_vendor['gstin']) ? $this->current_vendor['gstin'] : '-';
		$order_details['company_pan'] = !empty($this->current_vendor['pan']) ? $this->current_vendor['pan'] : '-';

		// Fetch order_type, items_arr, bookset_products for product display (like shipping label)
		$order_details['order_type_label'] = $this->_get_order_type_label($order_id, $order_row);
		$order_details['items_arr'] = $this->_get_invoice_items_arr($order_id);
		$order_details['bookset_products'] = $this->_get_invoice_bookset_products($order_id, $order_details['order_type_label']);
		$order_details['order_obj'] = (object)array_merge($order_row, array('order_unique_id' => $order->order_unique_id, 'invoice_no' => $order_details['invoice_no'], 'school_name' => $this->_get_order_school_name($order_id, $order_row)));

		// Prepare data for invoice view
		$page_data['data'] = $order_details;
		
		// Prefer application invoice (Shivam Books / kirtibook design)
		$invoice_view_path = APPPATH . 'views/invoice/invoice_bill.php';
		if (file_exists($invoice_view_path))
		{
			$html_content = $this->load->view('invoice/invoice_bill', $page_data, TRUE);
		}
		else
		{
			// Fallback: book_erp_frontend template
			$frontend_view_path = FCPATH . 'book_erp_frontend/application/views/invoice/invoice_bill.php';
			if (file_exists($frontend_view_path))
			{
				ob_start();
				extract($page_data);
				include($frontend_view_path);
				$html_content = ob_get_clean();
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
	 * Test invoice generation and download (bypasses payment status check)
	 * Use for testing: https://admin.shivambook.com/orders/test_invoice/ORD260218150
	 *
	 * @param	string	$order_no	Order unique ID
	 * @return	void
	 */
	public function test_invoice($order_no)
	{
		$order_data = $this->Order_model->get_order($order_no);
		if (!$order_data || empty($order_data))
		{
			show_error('Order not found', 404);
			return;
		}
		$order = $order_data[0];
		$order_id = $order->id;

		// Get order (skip payment status for test)
		$this->db->select('*');
		$this->db->from('tbl_order_details');
		$this->db->where('id', $order_id);
		$query = $this->db->get();
		if ($query->num_rows() == 0)
		{
			show_error('Order details not found', 404);
			return;
		}
		$order_row = $query->row_array();

		$shipping = array();
		$this->db->select('*');
		$this->db->from('tbl_order_address');
		$this->db->where('order_id', $order_id);
		$this->db->limit(1);
		$address_query = $this->db->get();
		if ($address_query->num_rows() > 0)
		{
			$shipping = $address_query->row_array();
		}

		$this->db->select('id,product_id,product_title,product_sku,product_qty,variation_name,product_mrp,product_price,product_gst,total_gst_amt,total_price,discount_amt,hsn,excl_price,excl_price_total');
		$this->db->from('tbl_order_items');
		$this->db->where('order_id', $order_id);
		$products = $this->db->get()->result_array();

		$gst_total = 0;
		$total_product_discount = 0;
		foreach ($products as $product)
		{
			$gst_total += isset($product['total_gst_amt']) ? $product['total_gst_amt'] : 0;
			$total_product_discount += isset($product['discount_amt']) ? $product['discount_amt'] : 0;
		}

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

		if (empty($order_details['invoice_no']))
		{
			$invoice_no = 'INV' . date('Ymd') . str_pad($order_id, 6, '0', STR_PAD_LEFT);
			$this->db->where('id', $order_id);
			$this->db->update('tbl_order_details', array('invoice_no' => $invoice_no));
			$order_details['invoice_no'] = $invoice_no;
		}

		@ini_set('memory_limit', '256M');
		$this->load->helper('common');
		$this->load->library('pdf');
		error_reporting(E_ALL & ~E_DEPRECATED);

		$order_details['logo_src'] = $this->_get_invoice_logo_base64();
		$order_details['company_name'] = !empty($this->current_vendor['name']) ? $this->current_vendor['name'] : 'Shivam Books';
		$order_details['company_address'] = !empty($this->current_vendor['address']) ? $this->current_vendor['address'] : '';
		$order_details['company_gstin'] = !empty($this->current_vendor['gstin']) ? $this->current_vendor['gstin'] : '-';
		$order_details['company_pan'] = !empty($this->current_vendor['pan']) ? $this->current_vendor['pan'] : '-';
		$order_details['order_type_label'] = $this->_get_order_type_label($order_id, $order_row);
		$order_details['items_arr'] = $this->_get_invoice_items_arr($order_id);
		$order_details['bookset_products'] = $this->_get_invoice_bookset_products($order_id, $order_details['order_type_label']);
		$order_details['order_obj'] = (object)array_merge($order_row, array('order_unique_id' => $order->order_unique_id, 'invoice_no' => $order_details['invoice_no'], 'school_name' => $this->_get_order_school_name($order_id, $order_row)));

		$page_data['data'] = $order_details;
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

		$this->pdf->set_paper("A4", "portrait");
		$old_error_reporting = error_reporting();
		error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
		try {
			$this->pdf->set_option('isHtml5ParserEnabled', TRUE);
			$this->pdf->load_html($html_content);
			$this->pdf->render();
			$pdfname = 'invoice_test_' . $order->order_unique_id . '.pdf';
			$this->pdf->stream($pdfname, array("Attachment" => 1));
			exit();
		} catch (Exception $e) {
			error_reporting($old_error_reporting);
			$this->pdf = new Pdf();
			$this->pdf->set_paper("A4", "portrait");
			$this->pdf->set_option('isHtml5ParserEnabled', FALSE);
			$this->pdf->load_html($html_content);
			$this->pdf->render();
			$pdfname = 'invoice_test_' . $order->order_unique_id . '.pdf';
			$this->pdf->stream($pdfname, array("Attachment" => 1));
			exit();
		}
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
	public function generate_shipping_label($order_no, $mode = 'single')
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
		
		// Verify order is in processing status
		if ($order->order_status != '2' && $order->order_status != 2)
		{
			if ($mode === 'bulk') {
				return false;
			}
			$this->session->set_flashdata('error', 'Shipping label can only be generated for orders in processing status.');
			redirect(base_url('orders/view/' . $order_no));
			return;
		}

		// Verify courier is self-delivery (manual)
		if (!isset($order->courier) || $order->courier !== 'manual') {
			if ($mode === 'bulk') {
				return false;
			}
			$this->session->set_flashdata('error', 'Shipping label can only be generated for self-delivery orders (courier: manual).');
			redirect(base_url('orders/view/' . $order_no));
			return;
		}
		
		// Generate shipping number (tracking ID) - use order_unique_id as slot_no for compatibility
		$shipping_number = $order_no; // Use order_unique_id as shipping number
		
		// Generate unique ship_order_id - 8 characters only (alphanumeric)
		// First 2 letters are constant: "SH"
		$prefix = 'SH'; // Constant prefix
		$chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
		do {
			$unique_ship_order_id = $prefix; // Start with constant prefix
			// Generate remaining 6 random characters
			for ($i = 0; $i < 6; $i++) {
				$unique_ship_order_id .= $chars[mt_rand(0, strlen($chars) - 1)];
			}
			$check_unique = $this->db->where('ship_order_id', $unique_ship_order_id)
				->get('tbl_order_details')
				->num_rows();
		} while ($check_unique > 0);
		
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
		
		// When deliver at school/branch: if address is empty, build from school/branch in order items
		$addr_obj = !empty($address_arr) ? $address_arr[0] : null;
		$addr_empty = !$addr_obj || (empty($addr_obj->address) && empty($addr_obj->city) && empty($addr_obj->state) && empty($addr_obj->pincode));
		if ($addr_empty && !empty($items_arr)) {
			$school_branch_name = '';
			foreach ($items_arr as $oi) {
				if (!empty($oi->branch_id)) {
					$br = $this->db->select('sb.branch_name, sb.address, sb.pincode, s.school_name, c.name as city_name, st.name as state_name')
						->from('erp_school_branches sb')
						->join('erp_schools s', 's.id = sb.school_id', 'left')
						->join('cities c', 'c.id = sb.city_id', 'left')
						->join('states st', 'st.id = sb.state_id', 'left')
						->where('sb.id', (int)$oi->branch_id)
						->limit(1)->get()->row();
					if ($br) {
						$school_branch_name = $br->branch_name . (!empty($br->school_name) ? ' (' . $br->school_name . ')' : '');
						$addr_obj = $addr_obj ?: new stdClass();
						$addr_obj->name = $order->user_name;
						$addr_obj->mobile_no = $order->user_phone;
						$addr_obj->address = $school_branch_name . (!empty($br->address) ? ', ' . $br->address : '');
						$addr_obj->city = !empty($br->city_name) ? $br->city_name : '';
						$addr_obj->state = !empty($br->state_name) ? $br->state_name : '';
						$addr_obj->country = 'India';
						$addr_obj->pincode = !empty($br->pincode) ? $br->pincode : '';
						$address_arr = array($addr_obj);
						break;
					}
				} elseif (!empty($oi->school_id)) {
					$sch = $this->db->select('s.school_name, s.address, s.pincode, c.name as city_name, st.name as state_name')
						->from('erp_schools s')
						->join('cities c', 'c.id = s.city_id', 'left')
						->join('states st', 'st.id = s.state_id', 'left')
						->where('s.id', (int)$oi->school_id)
						->limit(1)->get()->row();
					if ($sch) {
						$school_branch_name = $sch->school_name;
						$addr_obj = $addr_obj ?: new stdClass();
						$addr_obj->name = $order->user_name;
						$addr_obj->mobile_no = $order->user_phone;
						$addr_obj->address = $sch->school_name . (!empty($sch->address) ? ', ' . $sch->address : '');
						$addr_obj->city = !empty($sch->city_name) ? $sch->city_name : '';
						$addr_obj->state = !empty($sch->state_name) ? $sch->state_name : '';
						$addr_obj->country = 'India';
						$addr_obj->pincode = !empty($sch->pincode) ? $sch->pincode : '';
						$address_arr = array($addr_obj);
						break;
					}
				}
			}
		}
		
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
		
		// Generate PDF - use fresh instance in bulk to avoid dompdf state bleeding between renders
		// (reusing the same instance causes corrupted layout in 2nd, 3rd, etc. labels)
		$this->load->library('pdf');
		if ($mode === 'bulk') {
			$this->pdf = new Pdf();
		}
		
		// Suppress deprecation warnings from dompdf HTML5 parser
		$old_error_reporting = error_reporting();
		error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
		
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
		
		// For bulk mode, just return the relative path (no redirect/flash)
		if ($mode === 'bulk') {
			return $relative_path;
		}
		
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
	 * Bulk download shipping labels for selected orders (ZIP)
	 *
	 * Expects POST with order_ids[] (tbl_order_details.id values)
	 *
	 * @return void
	 */
	public function bulk_download_shipping_labels()
	{
		if (strtoupper($this->input->method()) !== 'POST') {
			show_error('Invalid request method.', 405);
			return;
		}

		$order_ids = $this->input->post('order_ids');

		if (empty($order_ids) || !is_array($order_ids)) {
			$this->session->set_flashdata('error', 'No orders selected for bulk shipping label download.');
			redirect(base_url('orders/processing'));
			return;
		}

		// Normalize and limit order IDs to avoid very heavy queries
		$order_ids = array_map('intval', (array)$order_ids);
		$order_ids = array_slice($order_ids, 0, 100);

		// Fetch orders ensuring they belong to current vendor
		$vendor_id = isset($this->current_vendor['id']) ? (int)$this->current_vendor['id'] : 0;

		$orders = $this->db->select('td.id, td.order_unique_id, td.shipping_label, td.order_status, td.courier')
			->from('tbl_order_details td')
			->where_in('td.id', $order_ids)
			->get()
			->result();

		if (empty($orders)) {
			$this->session->set_flashdata('error', 'Selected orders not found.');
			redirect(base_url('orders/processing'));
			return;
		}

		// Prepare ZIP
		if (!class_exists('ZipArchive')) {
			$this->session->set_flashdata('error', 'ZIP extension is not enabled on the server.');
			redirect(base_url('orders/processing'));
			return;
		}

		$this->load->helper('common');
		$this->config->load('upload');
		$uploadCfg = $this->config->item('shipping_label_upload');
		$vendor_folder = get_vendor_domain_folder();

		$zip = new ZipArchive();
		$tmp_dir = sys_get_temp_dir();
		$zip_filename = 'shipping_labels_' . date('Ymd_His') . '.zip';
		$zip_path = rtrim($tmp_dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $zip_filename;

		if ($zip->open($zip_path, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
			$this->session->set_flashdata('error', 'Unable to create ZIP file.');
			redirect(base_url('orders/processing'));
			return;
		}

		$added_files = 0;

		foreach ($orders as $order) {
			// Only allow processing status orders
			if ($order->order_status != '2' && $order->order_status != 2) {
				continue;
			}

			// Only handle self-delivery (manual) orders for bulk label generation
			if (!isset($order->courier) || $order->courier !== 'manual') {
				continue;
			}

			// If no label yet, try to generate it first
			if (empty($order->shipping_label)) {
				// generate_shipping_label() expects order_unique_id
				$relative_path = $this->generate_shipping_label($order->order_unique_id, 'bulk');

				if ($relative_path) {
					$order->shipping_label = $relative_path;
				} else {
					// As fallback, re-fetch fresh row for this order
					$refetched = $this->Order_model->get_order($order->order_unique_id);
					if ($refetched && !empty($refetched[0]->shipping_label)) {
						$order->shipping_label = $refetched[0]->shipping_label;
					}
				}
			}

			if (empty($order->shipping_label)) {
				continue;
			}

			// Build full file path exactly like download_shipping_label() - with FCPATH fallback
			$relative_path = $order->shipping_label; // uploads/shipping_labels/2026_02_13/filename.pdf
			$path_parts = explode('/', $relative_path);
			$date_folder = isset($path_parts[2]) ? $path_parts[2] : date('Y_m_d');
			$filename = end($path_parts);

			$file_path = rtrim($uploadCfg['base_root'], '/') . '/'
				. $vendor_folder . '/'
				. trim($uploadCfg['relative_dir'], '/') . '/'
				. $date_folder . '/'
				. $filename;

			// Fallback to FCPATH if the above path doesn't exist (same as single download)
			if (!file_exists($file_path)) {
				$file_path = FCPATH . $relative_path;
			}

			if (file_exists($file_path)) {
				// Use addFromString (not addFile) so content is embedded directly - avoids corruption
				// and path resolution issues across different environments
				$content = @file_get_contents($file_path);
				if ($content !== false) {
					$zip_name_in_archive = 'shipping_label_' . $order->order_unique_id . '.pdf';
					$zip->addFromString($zip_name_in_archive, $content);
					$added_files++;
				}
			}
		}

		$zip->close();

		if ($added_files === 0 || !file_exists($zip_path)) {
			if (file_exists($zip_path)) {
				@unlink($zip_path);
			}
			$this->session->set_flashdata('error', 'No shipping labels found for selected orders.');
			redirect(base_url('orders/processing'));
			return;
		}

		// Clear any output buffer before sending binary - prevents ZIP corruption
		if (ob_get_level()) {
			ob_end_clean();
		}

		// Output ZIP (same pattern as single download: headers + readfile)
		header('Content-Type: application/zip');
		header('Content-Disposition: attachment; filename="' . $zip_filename . '"');
		header('Content-Length: ' . filesize($zip_path));

		readfile($zip_path);
		@unlink($zip_path);
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
		
		// When deliver at school/branch: if address is empty, build from school/branch in order items
		$addr_obj = !empty($address_arr) ? $address_arr[0] : null;
		$addr_empty = !$addr_obj || (empty($addr_obj->address) && empty($addr_obj->city) && empty($addr_obj->state) && empty($addr_obj->pincode));
		if ($addr_empty && !empty($items_arr)) {
			foreach ($items_arr as $oi) {
				if (!empty($oi->branch_id)) {
					$br = $this->db->select('sb.branch_name, sb.address, sb.pincode, s.school_name, c.name as city_name, st.name as state_name')
						->from('erp_school_branches sb')
						->join('erp_schools s', 's.id = sb.school_id', 'left')
						->join('cities c', 'c.id = sb.city_id', 'left')
						->join('states st', 'st.id = sb.state_id', 'left')
						->where('sb.id', (int)$oi->branch_id)
						->limit(1)->get()->row();
					if ($br) {
						$addr_obj = $addr_obj ?: new stdClass();
						$addr_obj->name = $order->user_name;
						$addr_obj->mobile_no = $order->user_phone;
						$addr_obj->address = $br->branch_name . (!empty($br->school_name) ? ' (' . $br->school_name . ')' : '') . (!empty($br->address) ? ', ' . $br->address : '');
						$addr_obj->city = !empty($br->city_name) ? $br->city_name : '';
						$addr_obj->state = !empty($br->state_name) ? $br->state_name : '';
						$addr_obj->country = 'India';
						$addr_obj->pincode = !empty($br->pincode) ? $br->pincode : '';
						$address_arr = array($addr_obj);
						break;
					}
				} elseif (!empty($oi->school_id)) {
					$sch = $this->db->select('s.school_name, s.address, s.pincode, c.name as city_name, st.name as state_name')
						->from('erp_schools s')
						->join('cities c', 'c.id = s.city_id', 'left')
						->join('states st', 'st.id = s.state_id', 'left')
						->where('s.id', (int)$oi->school_id)
						->limit(1)->get()->row();
					if ($sch) {
						$addr_obj = $addr_obj ?: new stdClass();
						$addr_obj->name = $order->user_name;
						$addr_obj->mobile_no = $order->user_phone;
						$addr_obj->address = $sch->school_name . (!empty($sch->address) ? ', ' . $sch->address : '');
						$addr_obj->city = !empty($sch->city_name) ? $sch->city_name : '';
						$addr_obj->state = !empty($sch->state_name) ? $sch->state_name : '';
						$addr_obj->country = 'India';
						$addr_obj->pincode = !empty($sch->pincode) ? $sch->pincode : '';
						$address_arr = array($addr_obj);
						break;
					}
				}
			}
		}
		
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



	public function get_active_shipping_providers()	{
		$providers = $this->db
			->select('provider')
			->from('erp_shipping_providers')
			->where('client_id', $this->current_vendor['id'])
			->where('status', 1)
			->get()
			->result_array();

		echo json_encode([
			'success' => true,
			'providers' => $providers
		]);
	}
			
	

	
	public function get_provider_pickup_addresses(){
		header('Content-Type: application/json');

		$provider  = strtolower(trim($this->input->post('provider')));
		$client_id = $this->current_vendor['id'];

		if (empty($provider)) {
			return jsonResponse(false, 'Provider is required.');
		}
	 
		if ($provider === 'shiprocket') {
			return jsonResponse(true, '', []);
		}
	 
		if ($provider === 'velocity') {

			$row = $this->db->select('pickup_name, pickup_phoneno,
									  pickup_address, pickup_landmark,
									  pickup_city, pickup_state, pickup_pincode')
				->from('erp_shipping_providers')
				->where('client_id', $client_id)
				->where('provider', 'velocity')
				->where('status', 1)
				->limit(1)
				->get()
				->row_array();

			if (!$row) {
				return jsonResponse(false, 'Velocity pickup not configured.');
			}

			$city     = ucwords(strtolower(trim($row['pickup_city'] ?? '')));
			$state    = ucwords(strtolower(trim($row['pickup_state'] ?? '')));
			$address  = trim($row['pickup_address'] ?? '');
			$landmark = trim($row['pickup_landmark'] ?? '');
			$pincode  = trim($row['pickup_pincode'] ?? '');
			$name     = trim($row['pickup_name'] ?? '');
			$phone    = trim($row['pickup_phoneno'] ?? '');

			$full_address = implode(', ', array_filter([
				$name,
				$phone,
				$address,
				$landmark,
				$city,
				$state,
				$pincode
			]));

			return jsonResponse(true, '', [
				[
					'value' => 1,
					'name'  => $full_address
				]
			]);
		}
		elseif ($provider === 'bigship') {
			$this->load->model('shipping_model');
			$response = $this->shipping_model->get_bigship_warehouses($client_id);

			if (!$response['status']) {
				return jsonResponse(false, $response['message']);
			}

			$data = [];
			foreach ($response['data'] as $w) {
				$data[] = [
					'value' => $w['warehouse_id'],
					'name'  => $w['warehouse_name'] . ', ' .
							   $w['address_line1'] . ', ' . 
							   $w['address_city'] . ', ' .
							   $w['address_state'] . ' - ' .
							   $w['address_pincode']
				];
			}

			return jsonResponse(true, '', $data);
		}

		return jsonResponse(true, '', []);
	}
	
	public function save_third_party_shipping(){
		header('Content-Type: application/json');

		$response = function($status, $message) {
			echo json_encode([
				'status'  => $status,
				'message' => $message,
				'csrf'    => [
					'name' => $this->security->get_csrf_token_name(),
					'hash' => $this->security->get_csrf_hash()
				]
			]);
			exit;
		};
	
		$order_unique_id      = $this->input->post('order_unique_id', true);
		$third_party_provider = trim($this->input->post('third_party_provider', true));
		$length  = (float) $this->input->post('length');
		$breadth = (float) $this->input->post('breadth');
		$height  = (float) $this->input->post('height');
		$weight  = (float) $this->input->post('weight');
		$schedule_date = $this->input->post('schedule_date', true);
		$from_time     = $this->input->post('from_Time', true);
		$to_time       = $this->input->post('to_Time', true);
		$pickup_address_id = $this->input->post('pickup_address_id', true);

		if (empty($order_unique_id) || empty($third_party_provider)) {
			$response('400', 'Order ID and provider are required.');
		}

		$providers = $this->db->select('shipping_providers')->from('erp_clients')->where('id', $this->current_vendor['id'])->get()->row_array();
		$allowed_providers = explode(",",$providers['shipping_providers']);

		if (!in_array($third_party_provider, $allowed_providers)) {
			$response('400', 'Invalid provider.');
		}

		$order = $this->Order_model->get_order($order_unique_id);
		if (empty($order)) {
			$response('400', 'Order not found.');
		}

		$order_data = $order[0];
		$order_id   = $order_data->id;

		if ($order_data->order_status != 1) {
			$response('400', 'Order must be in pending status.');
		}
		
		
		$this->db->trans_begin();

		try {

			// ===============================
			// DELIVERY ADDRESS (School or Normal)
			// ===============================

			$is_deliver_at_school = (isset($order_data->is_deliver_at_school) 
				&& (int)$order_data->is_deliver_at_school === 1);

			$addr_row = null;

			if ($is_deliver_at_school) {

				// Get first order item with branch or school
				$order_item = $this->db->select('branch_id, school_id')
					->from('tbl_order_items')
					->where('order_id', $order_id)
					->limit(1)
					->get()
					->row();

				if ($order_item) {

					// ===============================
					// BRANCH ADDRESS
					// ===============================
					if (!empty($order_item->branch_id)) {

						$addr_row = $this->db->select('
								sb.branch_name as name,
								sb.address,
								sb.pincode,
								c.name as city,
								st.name as state
							')
							->from('erp_school_branches sb')
							->join('cities c', 'c.id = sb.city_id', 'left')
							->join('states st', 'st.id = sb.state_id', 'left')
							->where('sb.id', (int)$order_item->branch_id)
							->limit(1)
							->get()
							->row();

					} 
					// ===============================
					// SCHOOL ADDRESS
					// ===============================
					elseif (!empty($order_item->school_id)) {

						$addr_row = $this->db->select('
								s.school_name as name,
								s.address,
								s.pincode,
								c.name as city,
								st.name as state
							')
							->from('erp_schools s')
							->join('cities c', 'c.id = s.city_id', 'left')
							->join('states st', 'st.id = s.state_id', 'left')
							->where('s.id', (int)$order_item->school_id)
							->limit(1)
							->get()
							->row();
					}
				}

			} else {

				// ===============================
				// NORMAL DELIVERY ADDRESS
				// ===============================
				$addr_row = $this->db->select('*')
					->from('tbl_order_address')
					->where('order_id', $order_id)
					->limit(1)
					->get()
					->row();
			}

			// If still empty → fallback error
			if (!$addr_row) {
				throw new Exception('Delivery address not found.');
			}
				
						
			$delivery_address_full = '';

			$parts = array_filter([
				$addr_row->address ?? '',
				$addr_row->city ?? '',
				$addr_row->state ?? '',
				$addr_row->pincode ?? '',
				'India'
			]);

			$delivery_address_full = implode(', ', $parts);

			// ===============================
			// UPDATE ORDER DETAILS
			// ===============================
			$update_data = [
				'courier'               => '3rd_party',
				'third_party_provider'  => $third_party_provider,
				'pkg_length_cm'         => $length ?: null,
				'pkg_breadth_cm'        => $breadth ?: null,
				'pkg_height_cm'         => $height ?: null,
				'pkg_weight_kg'         => $weight ?: null
			];

			$this->db->where('id', $order_id);
			$this->db->update('tbl_order_details', $update_data);

			// ===============================
			// THIRD PARTY TABLE
			// ===============================
			if ($this->db->table_exists('tbl_order_third_party_shipping')) {

				$tp_data = [
					'order_id'               => $order_id,
					'order_unique_id'        => $order_unique_id,
					'invoice_number'         => $order_data->invoice_no ?? null,
					'delivery_address_full'  => $delivery_address_full,
					'length_cm'              => $length ?: null,
					'breadth_cm'             => $breadth ?: null,
					'height_cm'              => $height ?: null,
					'weight_kg'              => $weight ?: null,
					'third_party_provider'   => $third_party_provider,
					'schedule_date'          => $schedule_date ?: null,
					'from_time'              => $from_time ?: null,
					'to_time'                => $to_time ?: null
				];

				$existing = $this->db->select('id')
					->from('tbl_order_third_party_shipping')
					->where('order_id', $order_id)
					->get()
					->row();

				if ($existing) {
					$this->db->where('id', $existing->id)
							 ->update('tbl_order_third_party_shipping', $tp_data);
				} else {
					$this->db->insert('tbl_order_third_party_shipping', $tp_data);
				}
			}

			// ===============================
			// STATUS HISTORY
			// ===============================
			$this->db->insert('tbl_order_status', [
				'order_id'    => $order_id,
				'user_id'     => $this->current_vendor['id'] ?? 0,
				'product_id'  => 0,
				'status_title'=> '3rd Party Selected',
				'status_desc' => ucfirst($third_party_provider) .
								 " (L:$length B:$breadth H:$height W:$weight kg)",
				'created_at'  => date('Y-m-d H:i:s')
			]);
			
			
			// ===============================
			// CALL PROVIDER API
			// ===============================
			
				
			$provider = $this->db->select('name,email,password,company_id,channel_id,token,token_expiry,pickup_city, pickup_state, pickup_address, pickup_landmark, pickup_pincode, pickup_phoneno, pickup_alt_phoneno, pickup_name, pickup_emailid')
			->from('erp_shipping_providers')
			->where('provider', $third_party_provider)
			->where('client_id', $this->current_vendor['id'] ?? 0)
			->limit(1)
			->get()->row();
			
			$this->load->model('Shipping_model');

			$api_response = [];
			

			$consignments = array();
			$product_details = array();
			$declared_value=0;
			$total_weight=0;
			$total_weight_gm=0;	
			$order_type = $order_data->type_order;
		

			$order_id   = $order_data->id;
			$order_type = strtolower($order_data->type_order);

 
		if ($order_type === 'bookset') {

			$order_products = $this->db->select('bookset_packages_json')
				->from('tbl_order_items')
				->where('order_id', $order_id)
				->get()
				->result();
				
				
		 	/*echo json_encode([
			'debug' => $order_products,
			'csrf'  => [
				'name' => $this->security->get_csrf_token_name(),
				'hash' => $this->security->get_csrf_hash()
			]
		]); exit();*/
		
			if (empty($order_products)) {
				throw new Exception('No bookset items found.');
			}

			foreach ($order_products as $row) {

				if (empty($row->bookset_packages_json)) {
					continue;
				}

				$json = json_decode($row->bookset_packages_json, true);
				if (!isset($json['packages'])) {
					continue;
				}

				foreach ($json['packages'] as $package) {

					if (empty($package['products'])) {
						continue;
					}

					foreach ($package['products'] as $product) {

						$product_name = $product['display_name'] ?? 'Book';
						$qty          = (int) ($product['quantity'] ?? 1);
						$price_total  = (float) ($product['total_price'] ?? 0);
						$weight_gm    = (float) ($product['weight'] ?? 0);

						$declared_value += $price_total;

						if ($weight_gm <= 0) {
							$weight_gm = 500;
						}

						$total_weight_gm += ($weight_gm * $qty);

						$product_details[] = array(
							"product_category"               => "Others",
							"product_sub_category"           => $package['package_name'] ?? "",
							"product_name"                   => sanitize_allowed_chars($product_name),
							"product_quantity"               => $qty,
							"each_product_invoice_amount"    => $price_total,
							"each_product_collectable_amount"=> 0,
							"hsn"                            => $package['hsn'] ?? ""
						);
					}
				}
			}

			// Convert gm to kg
			$total_weight = round($total_weight_gm / 1000, 2);
		}
		else {

			$order_products = $this->db->select('product_title, product_qty, total_price, weight, hsn')
				->from('tbl_order_items')
				->where('order_id', $order_id)
				->get()
				->result();

			if (empty($order_products)) {
				throw new Exception('No order items found.');
			}

			foreach ($order_products as $item) {

				$product_name = $item->product_title;
				$qty          = (int) $item->product_qty;
				$price_total  = (float) $item->total_price;
				$weight_gm    = (float) $item->weight;

				$declared_value += $price_total;

				if ($weight_gm <= 0) {
					$weight_gm = 500;
				}

				$total_weight_gm += ($weight_gm * $qty);

				$product_details[] = array(
					"product_category"               => "Others",
					"product_sub_category"           => "",
					"product_name"                   => sanitize_allowed_chars($product_name),
					"product_quantity"               => $qty,
					"each_product_invoice_amount"    => $price_total / max($qty,1),
					"each_product_collectable_amount"=> 0,
					"hsn"                            => $item->hsn ?? ""
				);
			}

			$total_weight = round($total_weight_gm / 1000, 2);
		}
		 	/*echo json_encode([
				'debug' => $product_details,
				'csrf'  => [
					'name' => $this->security->get_csrf_token_name(),
					'hash' => $this->security->get_csrf_hash()
				]
			]); exit();*/
		
		
			switch (strtolower($third_party_provider)) {
				case 'velocity':

					$api_response = $this->Shipping_model->create_velocity_booking([
						'provider'     	  => $provider,
						'order_data'      => $order_data,
						'address_row'     => $addr_row,
						'product_details' => $product_details,
						'length'          => $length,
						'breadth'         => $breadth,
						'height'          => $height,
						'weight'          => $weight,
						'schedule_date'   => $schedule_date ?: null,
						'from_time'       => $from_time ?: null,
						'to_time'         => $to_time ?: null
					]);

					if ($api_response['status'] != 'success') {
						throw new Exception($api_response['message']);
					}
					break;

				case 'bigship':
				
					$api_response = $this->Shipping_model->create_bigship_booking([
						'provider'     	  => $provider,
						'order_data'      => $order_data,
						'address_row'     => $addr_row,
						'product_details' => $product_details,
						'length'          => $length,
						'breadth'         => $breadth,
						'height'          => $height,
						'weight'          => $weight,
						'schedule_date'   => $schedule_date ?: null,
						'from_time'       => $from_time ?: null,
						'to_time'         => $to_time ?: null,
						'pickup_address_id'=> $pickup_address_id ?: null
					]);

					if ($api_response['status'] != 'success') {
						throw new Exception($api_response['message']);
					}
					
				break;

				case 'shiprocket':
					// Shiprocket dimensions are saved but no direct booking API call implemented here yet
				break;

				default:
					throw new Exception('Provider API not implemented.');
			}
			
			

			if ($this->db->trans_status() === FALSE) {
				throw new Exception('Database error');
			}
 
			$processing_date = date("Y-m-d H:i:s");			
			$this->db->where('id', $order_id);
			$update_result = $this->db->update('tbl_order_details', array(
				'order_status' => '2',
				'processing_date' => $processing_date
			)); 

			$this->db->trans_commit();

			$response('200', '3rd party shipping saved successfully.');

		} catch (Exception $e) {

			$this->db->trans_rollback();
			$response('400', $e->getMessage());
		}
	}
	
	 
public function test(){
	echo 'xxx';
	exit();
	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://velexp.com/corporate-bulk-booking',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'POST',
	  CURLOPT_POSTFIELDS =>'[{ 
	"username": "corporate_user", 
	"password": "test@123", 
	"accno": "12351", 
	"secret_code": "TEST20200720", 
	"CustomerName": "IT-TESTING", 
	"serviceType": "VELOSKY", 
	"Product_Description": "Electronics", 
	"pieces": [ 
	{ "weight": 5.5, "length": 10, "breadth": 12, "height": 8 }, 
	{ "weight": 3.2, "length": 15, "breadth": 10, "height": 6 } 
	], 
	"drop_City": "pune", 
	"drop_State": "maharashtra", 
	"drop_Address": "456 Drop Avenue", 
	"drop_Landmark": "Near Mall", 
	"drop_Pincode": "416012", 
	"drop_Phoneno": "8765432109", 
	"drop_Alt_Phoneno": "9012345678", 
	"drop_Name": "Mike Drop", 
	"drop_Emailid": "mike.drop@example.com", 
	"pickup_City": "Kolhapur", 
	"pickup_State": "Maharashtra", 
	"pickup_Address": "123 Pickup Street", 
	"pickup_Landmark": "Near Park", 
	"pickup_Pincode": "416012", 
	"pickup_Phoneno": "9876543210", 
	"pickup_Alt_Phoneno": "9123456780", 
	"pickup_Name": "Jane Pickup", 
	"pickup_Emailid": "jane.pickup@example.com", 
	"schedule_date": "29-11-2024", 
	"from_Time": "09:00:00", 
	"to_Time": "17:00:00", 
	"Shipment_value": 100.0, 
	"cod_amount": 100.0, 
	"payment_mode": "COD", 
	"send_otp": "False", 
	"RTO_vendorname": "Onkar", 
	"RTO_vendoraddress": "address address address", 
	"RTO_vendorpincode": "416012", 
	"RTO_vendorcontactno": "9876543210" 
	}] 
	',
	  CURLOPT_HTTPHEADER => array(
		'Content-Type: application/json'
	  ),
	));

	$response = curl_exec($curl);

	curl_close($curl);
	echo $response;
}
	
}

