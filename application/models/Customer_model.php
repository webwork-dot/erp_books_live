<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Customer Model
 *
 * Handles database operations for customers
 *
 * @package		ERP
 * @subpackage	Models
 * @category	Models
 * @author		ERP Team
 */
class Customer_model extends CI_Model
{
	/**
	 * Constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
		// Use the current database connection (already switched to vendor database by Vendor_base)
		// Don't load default database here as it would override the vendor database connection
	}
	
	/**
	 * Get customers by vendor with filters and pagination
	 * Note: Since each vendor has their own database, all users in the database belong to that vendor
	 *
	 * @param	int	$vendor_id	Vendor ID (not used, kept for compatibility)
	 * @param	array	$filters	Optional filters
	 * @param	int	$limit	Number of records to return
	 * @param	int	$offset	Offset for pagination
	 * @return	array	Array of customers
	 */
	public function getCustomersByVendor($vendor_id, $filters = array(), $limit = 20, $offset = 0)
	{
		$this->db->select('users.*');
		$this->db->from('users');
		
		// Apply filters
		if (isset($filters['search']) && !empty($filters['search']))
		{
			$this->db->group_start();
			$this->db->like('users.username', $filters['search']);
			$this->db->or_like('users.firm_name', $filters['search']);
			$this->db->or_like('users.email', $filters['search']);
			$this->db->or_like('users.phone_number', $filters['search']);
			$this->db->group_end();
		}
		
		if (isset($filters['status']) && $filters['status'] !== '')
		{
			$this->db->where('users.status', $filters['status']);
		}
		
		// Order by created date
		$this->db->order_by('users.created_at', 'DESC');
		
		// Apply pagination
		if ($limit > 0)
		{
			$this->db->limit($limit, $offset);
		}
		
		$query = $this->db->get();
		return $query->result_array();
	}
	
	/**
	 * Get total customers count by vendor with filters
	 * Note: Since each vendor has their own database, all users in the database belong to that vendor
	 *
	 * @param	int	$vendor_id	Vendor ID (not used, kept for compatibility)
	 * @param	array	$filters	Optional filters
	 * @return	int	Total number of customers
	 */
	public function getTotalCustomersByVendor($vendor_id, $filters = array())
	{
		$this->db->from('users');
		
		// Apply filters
		if (isset($filters['search']) && !empty($filters['search']))
		{
			$this->db->group_start();
			$this->db->like('users.username', $filters['search']);
			$this->db->or_like('users.firm_name', $filters['search']);
			$this->db->or_like('users.email', $filters['search']);
			$this->db->or_like('users.phone_number', $filters['search']);
			$this->db->group_end();
		}
		
		if (isset($filters['status']) && $filters['status'] !== '')
		{
			$this->db->where('users.status', $filters['status']);
		}
		
		return $this->db->count_all_results();
	}
	
	/**
	 * Get customer details by user ID
	 * Includes addresses and orders
	 *
	 * @param	int	$user_id	User ID
	 * @return	array|false	Customer data with addresses and orders or false if not found
	 */
	public function getCustomerDetails($user_id)
	{
		// Get customer basic info
		$this->db->select('users.*');
		$this->db->from('users');
		$this->db->where('users.id', $user_id);
		$query = $this->db->get();
		$customer = $query->row_array();
		
		if (!$customer)
		{
			return false;
		}
		
		// Get customer addresses
		$this->db->select('address.*');
		$this->db->from('address');
		$this->db->where('address.user_id', $user_id);
		$this->db->where('address.is_deleted', 0);
		$this->db->order_by('address.created_at', 'DESC');
		$address_query = $this->db->get();
		$customer['addresses'] = $address_query->result_array();
		
		// Get orders from tbl_order_details
		$this->db->select('tbl_order_details.*');
		$this->db->from('tbl_order_details');
		$this->db->where('tbl_order_details.user_id', $user_id);
		$this->db->order_by('tbl_order_details.order_date', 'DESC');
		$orders_query = $this->db->get();
		$customer['orders'] = $orders_query->result_array();
		
		// Get orders from erp_orders (if customer_email matches)
		if (!empty($customer['email']))
		{
			$this->db->select('erp_orders.*, erp_schools.school_name, erp_school_branches.branch_name');
			$this->db->from('erp_orders');
			$this->db->join('erp_schools', 'erp_schools.id = erp_orders.school_id', 'left');
			$this->db->join('erp_school_branches', 'erp_school_branches.id = erp_orders.school_id', 'left');
			$this->db->where('erp_orders.customer_email', $customer['email']);
			$this->db->order_by('erp_orders.order_date', 'DESC');
			$erp_orders_query = $this->db->get();
			$erp_orders = $erp_orders_query->result_array();
			
			// Get order items for each erp order
			foreach ($erp_orders as &$order)
			{
				$this->db->select('erp_order_items.*');
				$this->db->from('erp_order_items');
				$this->db->where('erp_order_items.order_id', $order['id']);
				$items_query = $this->db->get();
				$order['items'] = $items_query->result_array();
			}
			
			$customer['erp_orders'] = $erp_orders;
		}
		else
		{
			$customer['erp_orders'] = array();
		}
		
		return $customer;
	}
}

