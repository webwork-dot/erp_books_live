<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Branch Model
 *
 * Handles database operations for school branch management
 *
 * @package		ERP
 * @subpackage	Models
 * @category	Models
 * @author		ERP Team
 */
class Branch_model extends CI_Model
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
	 * Get all branches by vendor
	 *
	 * @param	int	$vendor_id	Vendor ID
	 * @param	array	$filters	Optional filters
	 * @param	int	$limit		Limit (for pagination)
	 * @param	int	$offset		Offset (for pagination)
	 * @return	array	Array of branches
	 */
	public function getBranchesByVendor($vendor_id, $filters = array(), $limit = NULL, $offset = 0)
	{
		$this->db->select('erp_school_branches.*, erp_schools.school_name, states.name as state_name, cities.name as city_name');
		$this->db->from('erp_school_branches');
		$this->db->join('erp_schools', 'erp_schools.id = erp_school_branches.school_id', 'left');
		$this->db->join('states', 'states.id = erp_school_branches.state_id', 'left');
		$this->db->join('cities', 'cities.id = erp_school_branches.city_id', 'left');
		$this->db->where('erp_school_branches.vendor_id', $vendor_id);
		
		if (isset($filters['school_id']))
		{
			$this->db->where('erp_school_branches.school_id', $filters['school_id']);
		}
		
		if (isset($filters['status']))
		{
			$this->db->where('erp_school_branches.status', $filters['status']);
		}
		
		if (isset($filters['search']))
		{
			$this->db->group_start();
			$this->db->like('erp_school_branches.branch_name', $filters['search']);
			$this->db->or_like('erp_school_branches.address', $filters['search']);
			$this->db->or_like('erp_schools.school_name', $filters['search']);
			$this->db->group_end();
		}
		
		$this->db->order_by('erp_school_branches.created_at', 'DESC');
		
		if ($limit !== NULL)
		{
			$this->db->limit($limit, $offset);
		}
		
		$query = $this->db->get();
		
		return $query->result_array();
	}
	
	/**
	 * Get branch by ID
	 *
	 * @param	int	$branch_id	Branch ID
	 * @param	int	$vendor_id	Vendor ID (for security)
	 * @return	array|NULL	Branch data or NULL if not found
	 */
	public function getBranchById($branch_id, $vendor_id = NULL)
	{
		$this->db->select('erp_school_branches.*, erp_schools.school_name, states.name as state_name, cities.name as city_name');
		$this->db->from('erp_school_branches');
		$this->db->join('erp_schools', 'erp_schools.id = erp_school_branches.school_id', 'left');
		$this->db->join('states', 'states.id = erp_school_branches.state_id', 'left');
		$this->db->join('cities', 'cities.id = erp_school_branches.city_id', 'left');
		$this->db->where('erp_school_branches.id', $branch_id);
		
		if ($vendor_id !== NULL)
		{
			$this->db->where('erp_school_branches.vendor_id', $vendor_id);
		}
		
		$query = $this->db->get();
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		
		return NULL;
	}
	
	/**
	 * Create branch
	 *
	 * @param	array	$data	Branch data
	 * @return	int|FALSE	Branch ID on success, FALSE on failure
	 */
	public function createBranch($data)
	{
		$this->db->insert('erp_school_branches', $data);
		
		if ($this->db->affected_rows() > 0)
		{
			return $this->db->insert_id();
		}
		
		return FALSE;
	}
	
	/**
	 * Update branch
	 *
	 * @param	int	$branch_id	Branch ID
	 * @param	array	$data		Branch data
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function updateBranch($branch_id, $data)
	{
		// Don't update if data array is empty
		if (empty($data))
		{
			return TRUE;
		}
		
		$this->db->where('id', $branch_id);
		$this->db->update('erp_school_branches', $data);
		
		$error = $this->db->error();
		
		if (isset($error['code']) && $error['code'] != 0)
		{
			log_message('error', 'Branch update failed: ' . json_encode($error));
			return FALSE;
		}
		
		return TRUE;
	}
	
	/**
	 * Delete branch
	 *
	 * @param	int	$branch_id	Branch ID
	 * @param	int	$vendor_id	Vendor ID (for security)
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function deleteBranch($branch_id, $vendor_id = NULL)
	{
		$this->db->where('id', $branch_id);
		
		if ($vendor_id !== NULL)
		{
			$this->db->where('vendor_id', $vendor_id);
		}
		
		$this->db->delete('erp_school_branches');
		
		return $this->db->affected_rows() > 0;
	}
	
	/**
	 * Get total branches by vendor
	 *
	 * @param	int	$vendor_id	Vendor ID
	 * @param	array	$filters	Optional filters
	 * @return	int	Total number of branches
	 */
	public function getTotalBranchesByVendor($vendor_id, $filters = array())
	{
		$this->db->from('erp_school_branches');
		$this->db->where('erp_school_branches.vendor_id', $vendor_id);
		
		if (isset($filters['school_id']))
		{
			$this->db->where('erp_school_branches.school_id', $filters['school_id']);
		}
		
		if (isset($filters['status']))
		{
			$this->db->where('erp_school_branches.status', $filters['status']);
		}
		
		if (isset($filters['search']))
		{
			$this->db->group_start();
			$this->db->like('erp_school_branches.branch_name', $filters['search']);
			$this->db->or_like('erp_school_branches.address', $filters['search']);
			$this->db->group_end();
		}
		
		return $this->db->count_all_results();
	}
	
	/**
	 * Get branches by school
	 *
	 * @param	int	$school_id	School ID
	 * @param	int	$vendor_id	Vendor ID (for security)
	 * @return	array	Array of branches
	 */
	public function getBranchesBySchool($school_id, $vendor_id = NULL)
	{
		$this->db->select('erp_school_branches.*, states.name as state_name, cities.name as city_name');
		$this->db->from('erp_school_branches');
		$this->db->join('states', 'states.id = erp_school_branches.state_id', 'left');
		$this->db->join('cities', 'cities.id = erp_school_branches.city_id', 'left');
		$this->db->where('erp_school_branches.school_id', $school_id);
		
		if ($vendor_id !== NULL)
		{
			$this->db->where('erp_school_branches.vendor_id', $vendor_id);
		}
		
		$this->db->order_by('erp_school_branches.branch_name', 'ASC');
		
		$query = $this->db->get();
		
		return $query->result_array();
	}
}

