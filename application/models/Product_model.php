<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Product Model
 *
 * Handles database operations for products
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
	 * Get total products
	 *
	 * @param	array	$filters	Optional filters
	 * @return	int	Total number of products
	 */
	public function getTotalProducts($filters = array())
	{
		$this->db->from('products');
		
		if (isset($filters['type']))
		{
			$this->db->where('type', $filters['type']);
		}
		
		if (isset($filters['status']))
		{
			$this->db->where('status', $filters['status']);
		}
		
		return $this->db->count_all_results();
	}
}

