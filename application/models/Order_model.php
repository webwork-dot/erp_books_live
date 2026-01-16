<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Order Model
 *
 * Handles database operations for orders
 *
 * @package		ERP
 * @subpackage	Models
 * @category	Models
 * @author		ERP Team
 */
class Order_model extends CI_Model
{
	/**
	 * Constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
		// Use default database connection (will be switched to vendor database by Vendor_base)
		// Do not load separate connection - use $this->db which is switched to vendor database
	}
	
	/**
	 * Get orders by vendor with filters
	 *
	 * @param	int	$vendor_id	Vendor ID
	 * @param	array	$filters	Optional filters (payment_status, order_status, search)
	 * @param	int	$limit		Limit (for pagination)
	 * @param	int	$offset		Offset (for pagination)
	 * @return	array	Array of orders
	 */
	public function getOrdersByVendor($vendor_id, $filters = array(), $limit = NULL, $offset = 0)
	{
		$this->db->select('erp_orders.*, erp_schools.school_name, erp_schools.affiliation_no, erp_school_branches.branch_name, erp_school_branches.school_id as parent_school_id');
		$this->db->from('erp_orders');
		$this->db->join('erp_schools', 'erp_schools.id = erp_orders.school_id', 'left');
		$this->db->join('erp_school_branches', 'erp_school_branches.id = erp_orders.school_id', 'left');
		$this->db->where('erp_orders.vendor_id', $vendor_id);
		
		// Apply filters
		if (isset($filters['payment_status']) && !empty($filters['payment_status']))
		{
			$this->db->where('erp_orders.payment_status', $filters['payment_status']);
		}
		
		if (isset($filters['order_status']) && !empty($filters['order_status']))
		{
			$this->db->where('erp_orders.order_status', $filters['order_status']);
		}
		
		if (isset($filters['search']) && !empty($filters['search']))
		{
			$this->db->group_start();
			$this->db->like('erp_orders.order_number', $filters['search']);
			$this->db->or_like('erp_schools.school_name', $filters['search']);
			$this->db->or_like('erp_orders.customer_name', $filters['search']);
			$this->db->or_like('erp_orders.customer_email', $filters['search']);
			$this->db->group_end();
		}
		
		// Order by date descending
		$this->db->order_by('erp_orders.order_date', 'DESC');
		$this->db->order_by('erp_orders.id', 'DESC');
		
		if ($limit !== NULL)
		{
			$this->db->limit($limit, $offset);
		}
		
		$query = $this->db->get();
		$orders = $query->result_array();
		
		// Fetch grade, board, and branch information for each order
		foreach ($orders as &$order)
		{
			$order['grade_name'] = NULL;
			$order['board_name'] = NULL;
			
			// If branch_name exists, this is a branch order - get parent school name
			if (!empty($order['branch_name']) && !empty($order['parent_school_id']))
			{
				$this->db->select('school_name');
				$this->db->from('erp_schools');
				$this->db->where('id', $order['parent_school_id']);
				$parent_query = $this->db->get();
				if ($parent_query->num_rows() > 0)
				{
					$parent = $parent_query->row_array();
					$order['parent_school_name'] = $parent['school_name'];
					// Use parent school name as the main school name
					$order['school_name'] = $parent['school_name'];
				}
			}
			
			// Try to get grade and board from order items (bookset/package)
			$this->db->select('oi.bookset_id, oi.package_id');
			$this->db->from('erp_order_items oi');
			$this->db->where('oi.order_id', $order['id']);
			$this->db->where_in('oi.product_type', array('bookset', 'package'));
			$this->db->limit(1);
			$item_query = $this->db->get();
			
			if ($item_query->num_rows() > 0)
			{
				$item = $item_query->row_array();
				
				// Check if booksets table exists
				if ($this->db->table_exists('erp_booksets') && !empty($item['bookset_id']))
				{
					$this->db->select('bs.grade_id, bs.board_id, tg.name as grade_name, sb.board_name');
					$this->db->from('erp_booksets bs');
					$this->db->join('erp_textbook_grades tg', 'tg.id = bs.grade_id', 'left');
					$this->db->join('erp_school_boards sb', 'sb.id = bs.board_id', 'left');
					$this->db->where('bs.id', $item['bookset_id']);
					$bookset_query = $this->db->get();
					
					if ($bookset_query->num_rows() > 0)
					{
						$bookset = $bookset_query->row_array();
						$order['grade_name'] = $bookset['grade_name'];
						$order['board_name'] = $bookset['board_name'];
					}
				}
				// Check if bookset_packages table exists
				elseif ($this->db->table_exists('erp_bookset_packages') && !empty($item['package_id']))
				{
					$this->db->select('bp.grade_id, bp.board_id, tg.name as grade_name, sb.board_name');
					$this->db->from('erp_bookset_packages bp');
					$this->db->join('erp_textbook_grades tg', 'tg.id = bp.grade_id', 'left');
					$this->db->join('erp_school_boards sb', 'sb.id = bp.board_id', 'left');
					$this->db->where('bp.id', $item['package_id']);
					$package_query = $this->db->get();
					
					if ($package_query->num_rows() > 0)
					{
						$package = $package_query->row_array();
						$order['grade_name'] = $package['grade_name'];
						$order['board_name'] = $package['board_name'];
					}
				}
			}
			
			// If still no grade/board, try to get first board from school
			if (empty($order['board_name']) && !empty($order['school_id']))
			{
				$this->db->select('sb.board_name');
				$this->db->from('erp_school_boards_mapping sbm');
				$this->db->join('erp_school_boards sb', 'sb.id = sbm.board_id', 'left');
				$this->db->where('sbm.school_id', $order['school_id']);
				$this->db->limit(1);
				$board_query = $this->db->get();
				
				if ($board_query->num_rows() > 0)
				{
					$board = $board_query->row_array();
					$order['board_name'] = $board['board_name'];
				}
			}
		}
		
		return $orders;
	}
	
	/**
	 * Get total orders count by vendor with filters
	 *
	 * @param	int	$vendor_id	Vendor ID
	 * @param	array	$filters	Optional filters
	 * @return	int	Total number of orders
	 */
	public function getTotalOrdersByVendor($vendor_id, $filters = array())
	{
		$this->db->from('erp_orders');
		$this->db->join('erp_schools', 'erp_schools.id = erp_orders.school_id', 'left');
		$this->db->where('erp_orders.vendor_id', $vendor_id);
		
		// Apply filters
		if (isset($filters['payment_status']) && !empty($filters['payment_status']))
		{
			$this->db->where('erp_orders.payment_status', $filters['payment_status']);
		}
		
		if (isset($filters['order_status']) && !empty($filters['order_status']))
		{
			$this->db->where('erp_orders.order_status', $filters['order_status']);
		}
		
		if (isset($filters['search']) && !empty($filters['search']))
		{
			$this->db->group_start();
			$this->db->like('erp_orders.order_number', $filters['search']);
			$this->db->or_like('erp_schools.school_name', $filters['search']);
			$this->db->or_like('erp_orders.customer_name', $filters['search']);
			$this->db->or_like('erp_orders.customer_email', $filters['search']);
			$this->db->group_end();
		}
		
		return $this->db->count_all_results();
	}
	
	/**
	 * Get order by ID with items
	 *
	 * @param	int	$order_id	Order ID
	 * @param	int	$vendor_id	Vendor ID (for security)
	 * @return	array|false	Order data with items or false if not found
	 */
	public function getOrderById($order_id, $vendor_id = NULL)
	{
		$this->db->select('erp_orders.*, erp_schools.school_name, erp_schools.affiliation_no, erp_schools.address as school_address, erp_schools.phone as school_phone, erp_schools.email as school_email');
		$this->db->from('erp_orders');
		$this->db->join('erp_schools', 'erp_schools.id = erp_orders.school_id', 'left');
		$this->db->where('erp_orders.id', $order_id);
		
		if ($vendor_id !== NULL)
		{
			$this->db->where('erp_orders.vendor_id', $vendor_id);
		}
		
		$query = $this->db->get();
		$order = $query->row_array();
		
		if ($order)
		{
			// Get order items
			$order['items'] = $this->getOrderItems($order_id);
			
			// Try to get grade and board from order items
			$order['grade_name'] = NULL;
			$order['board_name'] = NULL;
			
			// Try to get grade and board from order items (bookset/package)
			$this->db->select('oi.bookset_id, oi.package_id');
			$this->db->from('erp_order_items oi');
			$this->db->where('oi.order_id', $order_id);
			$this->db->where_in('oi.product_type', array('bookset', 'package'));
			$this->db->limit(1);
			$item_query = $this->db->get();
			
			if ($item_query->num_rows() > 0)
			{
				$item = $item_query->row_array();
				
				// Check if booksets table exists
				if ($this->db->table_exists('erp_booksets') && !empty($item['bookset_id']))
				{
					$this->db->select('bs.grade_id, bs.board_id, tg.name as grade_name, sb.board_name');
					$this->db->from('erp_booksets bs');
					$this->db->join('erp_textbook_grades tg', 'tg.id = bs.grade_id', 'left');
					$this->db->join('erp_school_boards sb', 'sb.id = bs.board_id', 'left');
					$this->db->where('bs.id', $item['bookset_id']);
					$bookset_query = $this->db->get();
					
					if ($bookset_query->num_rows() > 0)
					{
						$bookset = $bookset_query->row_array();
						$order['grade_name'] = $bookset['grade_name'];
						$order['board_name'] = $bookset['board_name'];
					}
				}
				// Check if bookset_packages table exists
				elseif ($this->db->table_exists('erp_bookset_packages') && !empty($item['package_id']))
				{
					$this->db->select('bp.grade_id, bp.board_id, tg.name as grade_name, sb.board_name');
					$this->db->from('erp_bookset_packages bp');
					$this->db->join('erp_textbook_grades tg', 'tg.id = bp.grade_id', 'left');
					$this->db->join('erp_school_boards sb', 'sb.id = bp.board_id', 'left');
					$this->db->where('bp.id', $item['package_id']);
					$package_query = $this->db->get();
					
					if ($package_query->num_rows() > 0)
					{
						$package = $package_query->row_array();
						$order['grade_name'] = $package['grade_name'];
						$order['board_name'] = $package['board_name'];
					}
				}
			}
			
			// If still no grade/board, try to get first board from school
			if (empty($order['board_name']) && !empty($order['school_id']))
			{
				$this->db->select('sb.board_name');
				$this->db->from('erp_school_boards_mapping sbm');
				$this->db->join('erp_school_boards sb', 'sb.id = sbm.board_id', 'left');
				$this->db->where('sbm.school_id', $order['school_id']);
				$this->db->limit(1);
				$board_query = $this->db->get();
				
				if ($board_query->num_rows() > 0)
				{
					$board = $board_query->row_array();
					$order['board_name'] = $board['board_name'];
				}
			}
		}
		
		return $order ? $order : false;
	}
	
	/**
	 * Get order items by order ID
	 *
	 * @param	int	$order_id	Order ID
	 * @return	array	Array of order items
	 */
	public function getOrderItems($order_id)
	{
		$this->db->from('erp_order_items');
		$this->db->where('order_id', $order_id);
		$this->db->order_by('id', 'ASC');
		$query = $this->db->get();
		
		return $query->result_array();
	}
	
	/**
	 * Get order statistics by vendor
	 *
	 * @param	int	$vendor_id	Vendor ID
	 * @return	array	Statistics array
	 */
	public function getOrderStatistics($vendor_id)
	{
		$stats = array();
		
		// Total orders
		$this->db->where('vendor_id', $vendor_id);
		$stats['total'] = $this->db->count_all_results('erp_orders');
		
		// Payment pending
		$this->db->where('vendor_id', $vendor_id);
		$this->db->where('payment_status', 'pending');
		$stats['payment_pending'] = $this->db->count_all_results('erp_orders');
		
		// Payment failed
		$this->db->where('vendor_id', $vendor_id);
		$this->db->where('payment_status', 'failed');
		$stats['payment_failed'] = $this->db->count_all_results('erp_orders');
		
		// Payment success
		$this->db->where('vendor_id', $vendor_id);
		$this->db->where('payment_status', 'success');
		$stats['payment_success'] = $this->db->count_all_results('erp_orders');
		
		// Delivered
		$this->db->where('vendor_id', $vendor_id);
		$this->db->where('order_status', 'delivered');
		$stats['delivered'] = $this->db->count_all_results('erp_orders');
		
		// Cancelled
		$this->db->where('vendor_id', $vendor_id);
		$this->db->where('order_status', 'cancelled');
		$stats['cancelled'] = $this->db->count_all_results('erp_orders');
		
		return $stats;
	}
	
	/**
	 * Get total orders
	 *
	 * @param	array	$filters	Optional filters
	 * @return	int	Total number of orders
	 */
	public function getTotalOrders($filters = array())
	{
		$this->db->from('erp_orders');
		
		if (isset($filters['status']))
		{
			$this->db->where('order_status', $filters['status']);
		}
		
		return $this->db->count_all_results();
	}
	
	/**
	 * Get recent orders
	 *
	 * @param	int	$limit	Number of orders to return
	 * @return	array	Array of orders
	 */
	public function getRecentOrders($limit = 10)
	{
		$this->db->order_by('created_at', 'DESC');
		$this->db->limit($limit);
		$query = $this->db->get('erp_orders');
		
		return $query->result_array();
	}
	
	/**
	 * Get order count by type and status
	 *
	 * @param	int	$vendor_id	Vendor ID
	 * @param	string	$order_type	Order type ('individual' or 'uniform')
	 * @param	string	$order_status	Order status
	 * @return	int	Count of orders
	 */
	public function getOrderCountByTypeAndStatus($vendor_id, $order_type, $order_status)
	{
		$this->db->select('COUNT(DISTINCT erp_orders.id) as count');
		$this->db->from('erp_orders');
		$this->db->join('erp_order_items', 'erp_order_items.order_id = erp_orders.id', 'inner');
		$this->db->where('erp_orders.vendor_id', $vendor_id);
		$this->db->where('erp_orders.order_status', $order_status);
		
		if ($order_type == 'individual') {
			$this->db->where_in('erp_order_items.product_type', array('individual', 'textbook', 'notebook', 'stationery'));
		} elseif ($order_type == 'uniform') {
			$this->db->where('erp_order_items.product_type', 'uniform');
		}
		
		$query = $this->db->get();
		$result = $query->row_array();
		
		return isset($result['count']) ? (int)$result['count'] : 0;
	}
	
	/**
	 * Get paginated orders count for vendor (using tbl_order_details structure)
	 *
	 * @param	int	$vendor_id	Vendor ID
	 * @param	array	$filter_data	Filter data (order_status, keywords, date_range)
	 * @return	int	Total count
	 */
	public function get_paginated_orders_count($vendor_id, $filter_data)
	{
		$resultdata = array();

		$machine_filter = "";
		$keyword_filter = "";
		$order_date_filter = "";
		$order_status_filter = "";
		$vendor_filter = "";
		$order_status = isset($filter_data['order_status']) ? $filter_data['order_status'] : 'pending';

		if ($order_status == 'pending') {
			$order_status_filter = " AND order_status='1'";
		} elseif ($order_status == 'processing') {
			$order_status_filter = " AND order_status='2'";
		} elseif ($order_status == 'out_for_delivery') {
			$order_status_filter = " AND order_status='3'";
		} elseif ($order_status == 'delivered') {
			$order_status_filter = " AND order_status='4'";
		} elseif ($order_status == 'return') {
			$order_status_filter = " AND order_status='7'";
		} else {
			$order_status_filter = " AND order_status='0'";
		}

		// Add vendor filter - check if vendor_id column exists in tbl_order_details
		// If not, we'll filter through order items
		if (!empty($vendor_id)) {
			// Try to filter by vendor_id if column exists, otherwise filter through order items
			// $vendor_filter = " AND EXISTS (SELECT 1 FROM tbl_order_items oi WHERE oi.order_id = tbl_order_details.id AND oi.vendor_id = '" . (int)$vendor_id . "')";
		}

		if (isset($filter_data['keywords']) && $filter_data['keywords'] != "") :
			$keyword = trim($filter_data['keywords']);
			$keyword_filter = " AND (order_type like '%" . $this->db->escape_like_str($keyword) . "%'
			  or order_unique_id like '%" . $this->db->escape_like_str($keyword) . "%'
			  or user_name like '%" . $this->db->escape_like_str($keyword) . "%'
			  or invoice_no like '%" . $this->db->escape_like_str($keyword) . "%'
			  or coupon_code like '%" . $this->db->escape_like_str($keyword) . "%'
			  or user_phone like '%" . $this->db->escape_like_str($keyword) . "%')";
		endif;

		if (isset($filter_data['date_range']) && $filter_data['date_range'] != "") :
			$order_date = explode(' - ', $filter_data['date_range']);
			$from = date('Y-m-d', strtotime($order_date[0]));
			$to = date('Y-m-d', strtotime($order_date[1]));
			$order_date_filter = " AND (DATE(order_date) BETWEEN '" . $this->db->escape_str($from) . "' AND '" . $this->db->escape_str($to) . "')";
		endif;

		$query = $this->db->query("SELECT id FROM tbl_order_details WHERE (id<>'') AND (payment_status='success' OR payment_status='cod' OR payment_method='cod') $keyword_filter $order_status_filter $order_date_filter $vendor_filter ORDER BY id asc");
		return $query->num_rows();
	}

	/**
	 * Get paginated orders for vendor (using tbl_order_details structure)
	 *
	 * @param	int	$vendor_id	Vendor ID
	 * @param	array	$filter_data	Filter data (order_status, keywords, date_range)
	 * @param	int	$per_page	Items per page
	 * @param	int	$offset		Offset
	 * @return	array	Array of orders
	 */
	public function get_paginated_orders($vendor_id, $filter_data, $per_page, $offset)
	{
		$resultdata = array();

		$machine_filter = "";
		$keyword_filter = "";
		$order_date_filter = "";
		$order_status_filter = "";
		$vendor_filter = "";
		$order_status = isset($filter_data['order_status']) ? $filter_data['order_status'] : 'pending';

		if ($order_status == 'pending') {
			$order_status_filter = " AND order_status='1'";
		} elseif ($order_status == 'processing') {
			$order_status_filter = " AND order_status='2'";
		} elseif ($order_status == 'out_for_delivery') {
			$order_status_filter = " AND order_status='3'";
		} elseif ($order_status == 'delivered') {
			$order_status_filter = " AND order_status='4'";
		} elseif ($order_status == 'return') {
			$order_status_filter = " AND order_status='7'";
		} else {
			$order_status_filter = " AND order_status='0'";
		}

		// Add vendor filter
		// if (!empty($vendor_id)) {
		// 	$vendor_filter = " AND EXISTS (SELECT 1 FROM tbl_order_items oi WHERE oi.order_id = tbl_order_details.id AND oi.vendor_id = '" . (int)$vendor_id . "')";
		// }

		if (isset($filter_data['keywords']) && $filter_data['keywords'] != "") :
			$keyword = trim($filter_data['keywords']);
			$keyword_filter = " AND (order_type like '%" . $this->db->escape_like_str($keyword) . "%'
			  or order_unique_id like '%" . $this->db->escape_like_str($keyword) . "%'
			  or user_name like '%" . $this->db->escape_like_str($keyword) . "%'
			  or invoice_no like '%" . $this->db->escape_like_str($keyword) . "%'
			  or coupon_code like '%" . $this->db->escape_like_str($keyword) . "%'
			  or user_phone like '%" . $this->db->escape_like_str($keyword) . "%')";
		endif;

		if (isset($filter_data['date_range']) && $filter_data['date_range'] != "") :
			$order_date = explode(' - ', $filter_data['date_range']);
			$from = date('Y-m-d', strtotime($order_date[0]));
			$to = date('Y-m-d', strtotime($order_date[1]));
			$order_date_filter = " AND (DATE(order_date) BETWEEN '" . $this->db->escape_str($from) . "' AND '" . $this->db->escape_str($to) . "')";
		endif;

		$resultdata = array();
		$query = $this->db->query("SELECT id,checkout_type,payment_method,razorpay_order_id,payment_id,processing_date,shipment_date,delivery_date,return_date,tracking_id,shipping_label,track_url,courier,order_type,order_token,order_unique_id,user_name,user_phone,order_status,payment_status,order_date,invoice_no,invoice_url,coupon_code,source FROM tbl_order_details WHERE (payment_status='success' OR payment_status='cod' OR payment_method='cod') AND order_status!='5' $keyword_filter $order_status_filter $order_date_filter $vendor_filter ORDER BY id desc LIMIT $offset,$per_page");
		
		if (!empty($query)) {
			foreach ($query->result_array() as $item) {

				if ($order_status == 'pending') {
					$date = date("d M, Y H:i:s", strtotime($item['order_date']));
				} elseif ($order_status == 'processing') {
					$date = date("d M, Y H:i:s", strtotime($item['processing_date']));
				} elseif ($order_status == 'out_for_delivery') {
					$date = date("d M, Y H:i:s", strtotime($item['shipment_date']));
				} elseif ($order_status == 'delivered') {
					$date = date("d M, Y H:i:s", strtotime($item['delivery_date']));
				} elseif ($order_status == 'return') {
					$date = date("d M, Y H:i:s", strtotime($item['return_date']));
				} else {
					$date = date("d M, Y H:i:s", strtotime($item['order_date']));
				}

				$price = [];
				$sku = [];
				$hsn = [];

				$table_item = $this->db->select('product_id,product_price,product_sku,hsn')->where('order_id', $item['id'])->get('tbl_order_items');
				if ($table_item->num_rows() > 0) {
					$table_item = $table_item->result_array();
					foreach ($table_item as $t_item) {
						if ($t_item['product_price'] != '' && $t_item['product_price'] != NULL) {
							$price[] = $t_item['product_price'];
						}

						if ($t_item['product_sku'] != '' && $t_item['product_sku'] != NULL) {
							$sku[] = $t_item['product_sku'];
						}

						if ($t_item['hsn'] != '' && $t_item['hsn'] != NULL) {
							$hsn[] = $t_item['hsn'];
						}
					}
				}

				$price = (count($price) > 0) ? implode(', ', $price) : '-';
				$sku = (count($sku) > 0) ? implode(', ', $sku) : '-';
				$hsn = (count($hsn) > 0) ? implode(', ', $hsn) : '-';

				$checkout_type = '';
				if ($item['checkout_type'] == 'guest_checkout') {
					$checkout_type = 'Guest Checkout';
				} else {
					$checkout_type = 'User Checkout';
				}

				$invoice_url_path = !empty($item['invoice_url']) ? $item['invoice_url'] : '';
				$invoice_no = !empty($invoice_url_path) ? '<a href="' . base_url($invoice_url_path) . '" target="_blank">' . $item['invoice_no'] . '</a>' : $item['invoice_no'];
				$resultdata[] = array(
					"id"              => $item['id'],
					"order_type"      => $item['order_type'],
					"order_token"     => $item['order_token'],
					"order_unique_id" => $item['order_unique_id'],
					"user_name"       => $item['user_name'],
					"user_phone"      => $item['user_phone'],
					"coupon_code"     => ($item['coupon_code']) ? $item['coupon_code'] : '-',
					"status"          => $item['order_status'],
					"payment_status"  => $item['payment_status'],
					"tracking_id"     => $item['tracking_id'],
					"shipping_label"  => $item['shipping_label'],
					"track_url"       => $item['track_url'],
					"courier"         => $item['courier'],
					"source"         => $item['source'],
					"price"           => $price,
					"sku"             => $sku,
					"hsn"             => $hsn,
					"razorpay_order_id" => $item['razorpay_order_id'],
					"payment_id"      => $item['payment_id'],
					"invoice_no"      => $invoice_no,
					"payment_method"      => $item['payment_method'],
					"checkout_type"      => $checkout_type,
					"processing_date" => date("d M, Y H:i:s", strtotime($item['processing_date'])),
					"shipment_date"   => date("d M, Y H:i:s", strtotime($item['shipment_date'])),
					"delivery_date"   => date("d M, Y H:i:s", strtotime($item['delivery_date'])),
					"date"            => $date,
				);
			}
		}
		return $resultdata;
	}


	public function get_order($order_no)
	{
		$this->db->select('*');
		$this->db->from('tbl_order_details');
		$this->db->where('order_unique_id', $order_no);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 1) {
				return $query->result();
		} else {
				return false;
		}
	}

	/**
	 * Get paginated pending order count
	 *
	 * @param	array	$filter_data	Filter data (keywords, date_range, machine)
	 * @return	int	Total count of pending orders
	 */
	public function get_paginated_pending_order_count($filter_data)
	{
		$machine_filter = "";
		$keyword_filter = "";
		$order_date_filter = "";

		if (isset($filter_data['keywords']) && $filter_data['keywords'] != "") {
			$keyword = trim($filter_data['keywords']);
			$keyword_filter = " AND (order_type like '%" . $this->db->escape_like_str($keyword) . "%'
			or order_unique_id like '%" . $this->db->escape_like_str($keyword) . "%'
			or user_name like '%" . $this->db->escape_like_str($keyword) . "%'
			or user_phone like '%" . $this->db->escape_like_str($keyword) . "%')";
		}

		if (isset($filter_data['date_range']) && $filter_data['date_range'] != "") {
			$order_date = explode(' - ', $filter_data['date_range']);
			$from = date('Y-m-d', strtotime($order_date[0]));
			$to = date('Y-m-d', strtotime($order_date[1]));
			$order_date_filter = " AND (DATE(order_date) BETWEEN '" . $this->db->escape_str($from) . "' AND '" . $this->db->escape_str($to) . "')";
		}

		$query = $this->db->query("SELECT id FROM tbl_order_details WHERE (id<>'') AND (payment_status='pending' or payment_status='failed') $keyword_filter $machine_filter $order_date_filter ORDER BY id asc");
		return $query->num_rows();
	}

	/**
	 * Get paginated pending orders
	 *
	 * @param	array	$filter_data	Filter data (keywords, date_range, machine)
	 * @param	int	$per_page	Items per page
	 * @param	int	$offset		Offset
	 * @return	array	Array of pending orders
	 */
	public function get_paginated_pending_order($filter_data, $per_page, $offset)
	{
		$resultdata = array();

		$machine_filter = "";
		$keyword_filter = "";
		$order_date_filter = "";

		if (isset($filter_data['keywords']) && $filter_data['keywords'] != "") {
			$keyword = trim($filter_data['keywords']);
			$keyword_filter = " AND (order_type like '%" . $this->db->escape_like_str($keyword) . "%'
			or order_unique_id like '%" . $this->db->escape_like_str($keyword) . "%'
			or user_name like '%" . $this->db->escape_like_str($keyword) . "%'
			or user_phone like '%" . $this->db->escape_like_str($keyword) . "%')";
		}

		if (isset($filter_data['date_range']) && $filter_data['date_range'] != "") {
			$order_date = explode(' - ', $filter_data['date_range']);
			$from = date('Y-m-d', strtotime($order_date[0]));
			$to = date('Y-m-d', strtotime($order_date[1]));
			$order_date_filter = " AND (DATE(order_date) BETWEEN '" . $this->db->escape_str($from) . "' AND '" . $this->db->escape_str($to) . "')";
		}

		$query = $this->db->query("SELECT id,razorpay_order_id,payment_id,order_type,order_token,order_unique_id,user_name,user_phone,order_status,payment_method,payment_status,order_date FROM tbl_order_details WHERE (id<>'') AND ((payment_status='pending' or payment_status='failed') and payment_method<>'cod') $keyword_filter $machine_filter $order_date_filter ORDER BY id desc LIMIT $offset,$per_page");
		
		if (!empty($query)) {
			foreach ($query->result_array() as $item) {
				$resultdata[] = array(
					"id"             => $item['id'],
					"order_type"     => $item['order_type'],
					"order_token"    => $item['order_token'],
					"order_unique_id"    => $item['order_unique_id'],
					"user_name"      => $item['user_name'],
					"user_phone"     => $item['user_phone'],
					"status"         => $item['order_status'],
					"payment_status" => $item['payment_status'],
					"payment_method" => $item['payment_method'],
					"razorpay_order_id" => $item['razorpay_order_id'],
					"payment_id"      => ($item['payment_id']) ? $item['payment_id'] : '-',
					"date"            => date("d M, Y H:i:s", strtotime($item['order_date'])),
				);
			}
		}
		return $resultdata;
	}

	/**
	 * Get paginated cancelled/rejected order count
	 *
	 * @param	array	$filter_data	Filter data (keywords, date_range, machine, is_refund, order_status)
	 * @return	int	Total count of cancelled/rejected orders
	 */
	public function get_paginated_cancelled_order_count($filter_data)
	{
		$machine_filter = "";
		$keyword_filter = "";
		$order_date_filter = "";
		$is_refund = isset($filter_data['is_refund']) ? $filter_data['is_refund'] : '0';
		$order_status = isset($filter_data['order_status']) ? $filter_data['order_status'] : '6';

		if (isset($filter_data['keywords']) && $filter_data['keywords'] != "") {
			$keyword = trim($filter_data['keywords']);
			$keyword_filter = " AND (order_type like '%" . $this->db->escape_like_str($keyword) . "%'
			or order_unique_id like '%" . $this->db->escape_like_str($keyword) . "%'
			or user_name like '%" . $this->db->escape_like_str($keyword) . "%'
			or user_phone like '%" . $this->db->escape_like_str($keyword) . "%')";
		}

		if (isset($filter_data['date_range']) && $filter_data['date_range'] != "") {
			$order_date = explode(' - ', $filter_data['date_range']);
			$from = date('Y-m-d', strtotime($order_date[0]));
			$to = date('Y-m-d', strtotime($order_date[1]));
			$order_date_filter = " AND (DATE(order_date) BETWEEN '" . $this->db->escape_str($from) . "' AND '" . $this->db->escape_str($to) . "')";
		}

		$query = $this->db->query("SELECT id FROM tbl_order_details WHERE is_refund='" . $this->db->escape_str($is_refund) . "' AND payment_status='success' AND order_status='" . $this->db->escape_str($order_status) . "' $keyword_filter $machine_filter $order_date_filter ORDER BY id asc");
		return $query->num_rows();
	}

	/**
	 * Get paginated cancelled/rejected orders
	 *
	 * @param	array	$filter_data	Filter data (keywords, date_range, machine, is_refund, order_status)
	 * @param	int	$per_page	Items per page
	 * @param	int	$offset		Offset
	 * @return	array	Array of cancelled/rejected orders
	 */
	public function get_paginated_cancelled_order($filter_data, $per_page, $offset)
	{
		$resultdata = array();
		$machine_filter = "";
		$keyword_filter = "";
		$order_date_filter = "";
		$is_refund = isset($filter_data['is_refund']) ? $filter_data['is_refund'] : '0';
		$order_status = isset($filter_data['order_status']) ? $filter_data['order_status'] : '6';

		if (isset($filter_data['keywords']) && $filter_data['keywords'] != "") {
			$keyword = trim($filter_data['keywords']);
			$keyword_filter = " AND (order_type like '%" . $this->db->escape_like_str($keyword) . "%'
			or order_unique_id like '%" . $this->db->escape_like_str($keyword) . "%'
			or user_name like '%" . $this->db->escape_like_str($keyword) . "%'
			or user_phone like '%" . $this->db->escape_like_str($keyword) . "%')";
		}

		if (isset($filter_data['date_range']) && $filter_data['date_range'] != "") {
			$order_date = explode(' - ', $filter_data['date_range']);
			$from = date('Y-m-d', strtotime($order_date[0]));
			$to = date('Y-m-d', strtotime($order_date[1]));
			$order_date_filter = " AND (DATE(order_date) BETWEEN '" . $this->db->escape_str($from) . "' AND '" . $this->db->escape_str($to) . "')";
		}

		$query = $this->db->query("SELECT id,razorpay_order_id,payment_id,remark,payable_amt,order_type,order_unique_id,user_id,order_status,payment_status,refund_amt,order_date,cancelled_date,invoice_no,cancel_invoice_url FROM tbl_order_details WHERE is_refund='" . $this->db->escape_str($is_refund) . "' AND (payment_status='success' OR payment_status='cod') AND order_status='" . $this->db->escape_str($order_status) . "' $keyword_filter $machine_filter $order_date_filter ORDER BY id desc LIMIT $offset,$per_page");
		
		if (!empty($query)) {
			foreach ($query->result_array() as $item) {
				$invoice_url = !empty($item['cancel_invoice_url']) ? base_url($item['cancel_invoice_url']) : '#';
				$invoice_no = !empty($item['cancel_invoice_url']) ? '<a href="' . $invoice_url . '" target="_blank">' . $item['invoice_no'] . '</a>' : $item['invoice_no'];

				$resultdata[] = array(
					"id"              => $item['id'],
					"order_type"      => $item['order_type'],
					"order_unique_id" => $item['order_unique_id'],
					"user_id"         => $item['user_id'],
					"order_status"    => $item['order_status'],
					"payment_status"  => $item['payment_status'],
					"refund_amt"       => $item['refund_amt'],
					"payable_amt"       => $item['payable_amt'],
					"remark"           => $item['remark'],
					"razorpay_order_id" => $item['razorpay_order_id'],
					"payment_id"      => $item['payment_id'],
					"order_date"      => date('d-m-Y h:i A', strtotime($item['order_date'])),
					"cancelled_date"  => ($item['cancelled_date']) ? date('d-m-Y h:i A', strtotime($item['cancelled_date'])) : '-',
					"invoice_no"       => $invoice_no,
				);
			}
		}
		return $resultdata;
	}

	/**
	 * Get paginated cancelled/rejected order count
	 *
	 * @param	array	$filter_data	Filter data (keywords, date_range, machine, is_refund, order_status)
	 * @return	int	Total count of cancelled/rejected orders
	 */
	public function get_paginated_offers_count($filter_data)
	{

		$keyword_filter = "";
		if (isset($filter_data['keywords']) && $filter_data['keywords'] != "") {
			$keyword = trim($filter_data['keywords']);
			$keyword_filter .= " AND (offer_type like '%" . $this->db->escape_like_str($keyword) . "%')";
		}

		// if (isset($filter_data['date_range']) && $filter_data['date_range'] != "") {
		// 	$order_date = explode(' - ', $filter_data['date_range']);
		// 	$from = date('Y-m-d', strtotime($order_date[0]));
		// 	$to = date('Y-m-d', strtotime($order_date[1]));
		// 	$order_date_filter = " AND (DATE(order_date) BETWEEN '" . $this->db->escape_str($from) . "' AND '" . $this->db->escape_str($to) . "')";
		// }

		$query = $this->db->query("SELECT id FROM offers WHERE (id<>'') $keyword_filter ORDER BY id asc");
		return $query->num_rows();
	}

	/**
	 * Get paginated cancelled/rejected orders
	 *
	 * @param	array	$filter_data	Filter data (keywords, date_range, machine, is_refund, order_status)
	 * @param	int	$per_page	Items per page
	 * @param	int	$offset		Offset
	 * @return	array	Array of cancelled/rejected orders
	 */
	public function get_paginated_offers($filter_data, $per_page, $offset)
	{
		$resultdata = array();
		

		$keyword_filter = "";
		if (isset($filter_data['keywords']) && $filter_data['keywords'] != "") {
			$keyword = trim($filter_data['keywords']);
			$keyword_filter .= " AND (offer_type like '%" . $this->db->escape_like_str($keyword) . "%')";
		}

		// if (isset($filter_data['date_range']) && $filter_data['date_range'] != "") {
		// 	$order_date = explode(' - ', $filter_data['date_range']);
		// 	$from = date('Y-m-d', strtotime($order_date[0]));
		// 	$to = date('Y-m-d', strtotime($order_date[1]));
		// 	$order_date_filter = " AND (DATE(order_date) BETWEEN '" . $this->db->escape_str($from) . "' AND '" . $this->db->escape_str($to) . "')";
		// }

		$query = $this->db->query("SELECT * FROM offers WHERE (id<>'') $keyword_filter ORDER BY id DESC LIMIT $offset,$per_page");
		
		if (!empty($query)) {
			foreach ($query->result_array() as $item) {
				$resultdata[] = array(
					"id"              	=> $item['id'],
					"offer_type"      	=> $item['offer_type'],
					"discount_code" 		=> $item['discount_code'],
					"title"         		=> $item['title'],
					"min_type"    			=> $item['min_type'],
					"min_value"  				=> $item['min_value'],
					"offer_value_type"	=> $item['offer_value_type'],
					"offer_value"       => $item['offer_value'],
					"free_quantity"			=> $item['free_quantity'],
					"status"   				  => $item['status'],
				);
			}
		}
		return $resultdata;
	}

}

