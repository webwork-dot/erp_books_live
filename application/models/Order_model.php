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
		// Use master database
		$this->load->database('default', TRUE);
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
}

