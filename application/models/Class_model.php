<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Model
 *
 * Handles database operations for class management
 *
 * @package		ERP
 * @subpackage	Models
 * @category	Models
 * @author		ERP Team
 */
class Class_model extends CI_Model
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
	}
	
	/**
	 * Get all classes
	 *
	 * @param	array	$filters	Optional filters
	 * @param	int	$limit		Limit (for pagination)
	 * @param	int	$offset		Offset (for pagination)
	 * @return	array	Array of classes
	 */
	public function getClasses($filters = array(), $limit = NULL, $offset = 0)
	{
		$this->db->select('*');
		$this->db->from('classes');
		
		if (isset($filters['search']))
		{
			$this->db->like('class_name', $filters['search']);
		}
		
		$this->db->order_by('id', 'ASC');
		
		if ($limit !== NULL)
		{
			$this->db->limit($limit, $offset);
		}
		
		$query = $this->db->get();
		
		return $query->result_array();
	}
	
	/**
	 * Get total classes count
	 *
	 * @param	array	$filters	Optional filters
	 * @return	int	Total number of classes
	 */
	public function getTotalClasses($filters = array())
	{
		$this->db->from('classes');
		
		if (isset($filters['search']))
		{
			$this->db->like('class_name', $filters['search']);
		}
		
		return $this->db->count_all_results();
	}
	
	/**
	 * Get class by ID
	 *
	 * @return	array|null	Class data or null if not found
	 */
	public function getClassById($class_id)
	{
		$this->db->where('id', $class_id);
		$query = $this->db->get('classes');
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		
		return null;
	}
	
	/**
	 * Create class
	 *
	 * @param	array	$data	Class data
	 * @return	int|false	Class ID on success, false on failure
	 */
	public function createClass($data)
	{
		$this->db->insert('classes', $data);
		
		if ($this->db->affected_rows() > 0)
		{
			return $this->db->insert_id();
		}
		
		return false;
	}
	
	/**
	 * Update class
	 *
	 * @param	int	$class_id	Class ID
	 * @param	array	$data		Class data
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function updateClass($class_id, $data)
	{
		$this->db->where('id', $class_id);
		$this->db->update('classes', $data);
		
		return $this->db->affected_rows() >= 0;
	}
	
	/**
	 * Delete class
	 *
	 * @param	int	$class_id	Class ID
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function deleteClass($class_id)
	{
		$this->db->where('id', $class_id);
		$this->db->delete('classes');
		
		return $this->db->affected_rows() > 0;
	}
}
