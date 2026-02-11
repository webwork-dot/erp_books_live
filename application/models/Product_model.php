<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Product Model
 *
 * Unified product access layer for non-bookset products.
 * Uses the `erp_products` table (one row per product) and exposes
 * simple CRUD + query helpers for controllers.
 *
 * @package		ERP
 * @subpackage	Models
 * @category	Models
 * @author		ERP Team
 */
class Product_model extends CI_Model
{
	/**
	 * Constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
		// Uses tenant database (switched by Tenant library)
	}
	
	/**
	 * Create product
	 *
	 * @param	array	$data	Product data
	 * @return	int|FALSE	Product ID on success, FALSE on failure
	 */
	public function create_product(array $data)
	{
		if (!isset($data['created_at']))
		{
			$data['created_at'] = date('Y-m-d H:i:s');
		}
		
		$this->db->insert('erp_products', $data);
		
		if ($this->db->affected_rows() > 0)
		{
			return $this->db->insert_id();
		}
		
		return FALSE;
	}
	
	/**
	 * Update product
	 *
	 * @param	int	$product_id	Product ID
	 * @param	array	$data		Product data
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function update_product($product_id, array $data)
	{
		if (!isset($data['updated_at']))
		{
			$data['updated_at'] = date('Y-m-d H:i:s');
		}
		
		$this->db->where('id', (int) $product_id);
		$this->db->update('erp_products', $data);
		
		return $this->db->affected_rows() >= 0;
	}
	
	/**
	 * Soft delete product
	 *
	 * @param	int	$product_id	Product ID
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function delete_product($product_id)
	{
		// Use soft delete by default
		$this->db->where('id', (int) $product_id);
		$this->db->update('erp_products', array('is_deleted' => 1, 'status' => 'inactive'));
		
		return $this->db->affected_rows() > 0;
	}
	
	/**
	 * Get product by ID
	 *
	 * @param	int	$product_id	Product ID
	 * @param	int|null $vendor_id	Optional vendor ID for security
	 * @return	array|FALSE
	 */
	public function get_product_by_id($product_id, $vendor_id = NULL)
	{
		$this->db->from('erp_products');
		$this->db->where('id', (int) $product_id);
		if ($vendor_id !== NULL)
		{
			$this->db->where('vendor_id', (int) $vendor_id);
		}
		$this->db->where('is_deleted', 0);
		
		$query = $this->db->get();
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		
		return FALSE;
	}
	
	/**
	 * Get product row by legacy table + legacy ID
	 *
	 * @param	string	$legacy_table
	 * @param	int		$legacy_id
	 * @param	int		$vendor_id
	 * @return	array|FALSE
	 */
	public function get_product_by_legacy($legacy_table, $legacy_id, $vendor_id)
	{
		$this->db->from('erp_products');
		$this->db->where('legacy_table', $legacy_table);
		$this->db->where('legacy_id', (int) $legacy_id);
		$this->db->where('vendor_id', (int) $vendor_id);
		$this->db->where('is_deleted', 0);
		
		$query = $this->db->get();
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		
		return FALSE;
	}
	
	/**
	 * Get total products
	 *
	 * @param	array	$filters	Optional filters
	 * @return	int	Total number of products
	 */
	public function getTotalProducts($filters = array())
	{
		$this->db->from('erp_products');
		$this->apply_filters($filters);
		return $this->db->count_all_results();
	}
	
	/**
	 * Get products list (with basic filtering & pagination)
	 *
	 * @param	array	$filters	Filters (vendor_id, type, status, search, category_id, board_id, grade_id)
	 * @param	int|null $limit		Limit
	 * @param	int	$offset		Offset
	 * @return	array
	 */
	public function getProducts($filters = array(), $limit = NULL, $offset = 0)
	{
		$this->db->from('erp_products');
		$this->apply_filters($filters);
		
		// Ordering
		if (isset($filters['order_by']) && in_array($filters['order_by'], array('created_at', 'product_name', 'product_mrp', 'selling_price')))
		{
			$direction = (isset($filters['order_dir']) && strtolower($filters['order_dir']) === 'asc') ? 'ASC' : 'DESC';
			$this->db->order_by($filters['order_by'], $direction);
		}
		else
		{
			$this->db->order_by('id', 'DESC');
		}
		
		if ($limit !== NULL)
		{
			$this->db->limit($limit, $offset);
		}
		
		$query = $this->db->get();
		return $query->result_array();
	}
	
	/**
	 * Check if SKU exists for vendor
	 *
	 * @param	string	$sku
	 * @param	int	$vendor_id
	 * @param	int|null $exclude_id
	 * @return	bool
	 */
	public function sku_exists($sku, $vendor_id, $exclude_id = NULL)
	{
		$this->db->from('erp_products');
		$this->db->where('sku', $sku);
		$this->db->where('vendor_id', (int) $vendor_id);
		$this->db->where('is_deleted', 0);
		
		if ($exclude_id !== NULL)
		{
			$this->db->where('id !=', (int) $exclude_id);
		}
		
		$query = $this->db->get();
		return $query->num_rows() > 0;
	}
	
	/**
	 * Internal helper: apply common filters to current query
	 *
	 * @param	array	$filters
	 * @return	void
	 */
	protected function apply_filters($filters = array())
	{
		// Vendor
		if (isset($filters['vendor_id']))
		{
			$this->db->where('vendor_id', (int) $filters['vendor_id']);
		}
		
		// Product type (textbook, notebook, stationery, uniform, individual, other)
		if (isset($filters['type']))
		{
			$this->db->where('type', $filters['type']);
		}
		
		// Status
		if (isset($filters['status']))
		{
			$this->db->where('status', $filters['status']);
		}
		
		// Category
		if (isset($filters['category_id']))
		{
			$this->db->where('category_id', (int) $filters['category_id']);
		}
		
		// Board / grade / subject
		if (isset($filters['board_id']))
		{
			$this->db->where('board_id', (int) $filters['board_id']);
		}
		if (isset($filters['grade_id']))
		{
			$this->db->where('grade_id', (int) $filters['grade_id']);
		}
		if (isset($filters['subject_id']))
		{
			$this->db->where('subject_id', (int) $filters['subject_id']);
		}
		
		// Search (by name, SKU, ISBN)
		if (isset($filters['search']) && $filters['search'] !== '')
		{
			$search = trim($filters['search']);
			$this->db->group_start();
			$this->db->like('product_name', $search);
			$this->db->or_like('sku', $search);
			$this->db->or_like('isbn', $search);
			$this->db->group_end();
		}
		
		// Legacy mapping
		if (isset($filters['legacy_table']))
		{
			$this->db->where('legacy_table', $filters['legacy_table']);
		}
		if (isset($filters['legacy_id']))
		{
			$this->db->where('legacy_id', (int) $filters['legacy_id']);
		}
		
		// Exclude deleted by default
		if (!isset($filters['include_deleted']) || !$filters['include_deleted'])
		{
			$this->db->where('is_deleted', 0);
		}
	}
}

