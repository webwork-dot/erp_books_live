<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * School Model
 *
 * Handles database operations for school management
 *
 * @package		ERP
 * @subpackage	Models
 * @category	Models
 * @author		ERP Team
 */
class School_model extends CI_Model
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
	 * Get all schools by vendor
	 *
	 * @param	int	$vendor_id	Vendor ID
	 * @param	array	$filters	Optional filters
	 * @param	int	$limit		Limit (for pagination)
	 * @param	int	$offset		Offset (for pagination)
	 * @return	array	Array of schools
	 */
	public function getSchoolsByVendor($vendor_id, $filters = array(), $limit = NULL, $offset = 0)
	{
		$this->db->select('erp_schools.*, states.name as state_name, cities.name as city_name');
		$this->db->from('erp_schools');
		$this->db->join('states', 'states.id = erp_schools.state_id', 'left');
		$this->db->join('cities', 'cities.id = erp_schools.city_id', 'left');
		$this->db->where('erp_schools.vendor_id', $vendor_id);
		
		if (isset($filters['status']))
		{
			$this->db->where('erp_schools.status', $filters['status']);
		}
		
		if (isset($filters['search']))
		{
			$this->db->group_start();
			$this->db->like('erp_schools.school_name', $filters['search']);
			$this->db->or_like('erp_schools.school_board', $filters['search']);
			$this->db->or_like('erp_schools.admin_email', $filters['search']);
			$this->db->group_end();
		}
		
		$this->db->order_by('erp_schools.created_at', 'DESC');
		
		if ($limit !== NULL)
		{
			$this->db->limit($limit, $offset);
		}
		
		$query = $this->db->get();
		
		return $query->result_array();
	}
	
	/**
	 * Get school by ID
	 *
	 * @param	int	$school_id	School ID
	 * @param	int	$vendor_id	Vendor ID (for security)
	 * @return	array|NULL	School data or NULL if not found
	 */
	public function getSchoolById($school_id, $vendor_id = NULL)
	{
		$this->db->select('erp_schools.*, states.name as state_name, cities.name as city_name');
		$this->db->from('erp_schools');
		$this->db->join('states', 'states.id = erp_schools.state_id', 'left');
		$this->db->join('cities', 'cities.id = erp_schools.city_id', 'left');
		$this->db->where('erp_schools.id', $school_id);
		
		if ($vendor_id !== NULL)
		{
			$this->db->where('erp_schools.vendor_id', $vendor_id);
		}
		
		$query = $this->db->get();
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		
		return NULL;
	}
	
	/**
	 * Create school
	 *
	 * @param	array	$data	School data
	 * @return	int|FALSE	School ID on success, FALSE on failure
	 */
	public function createSchool($data)
	{
		// Hash admin password if provided
		if (isset($data['admin_password']) && !empty($data['admin_password']))
		{
			$data['admin_password'] = sha1($data['admin_password']);
		}
		
		$this->db->insert('erp_schools', $data);
		
		if ($this->db->affected_rows() > 0)
		{
			return $this->db->insert_id();
		}
		
		return FALSE;
	}
	
	/**
	 * Update school
	 *
	 * @param	int	$school_id	School ID
	 * @param	array	$data		School data
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function updateSchool($school_id, $data)
	{
		// Hash admin password if provided
		if (isset($data['admin_password']) && !empty($data['admin_password']))
		{
			$data['admin_password'] = sha1($data['admin_password']);
		}
		else
		{
			// Don't update password if not provided
			unset($data['admin_password']);
		}
		
		// Don't update if data array is empty
		if (empty($data))
		{
			return TRUE; // Nothing to update, consider it successful
		}
		
		$this->db->where('id', $school_id);
		$this->db->update('erp_schools', $data);
		
		$error = $this->db->error();
		
		// Check for database errors
		// Return TRUE if query executed successfully (even if no rows were affected)
		// This handles the case where data hasn't changed but query executed successfully
		// MySQL returns 0 affected rows when UPDATE doesn't change any values, but query still succeeds
		// CodeIgniter's error() returns array with 'code' and 'message' keys
		// If code is 0 or empty, and message is empty, there's no error
		if (isset($error['code']) && $error['code'] != 0)
		{
			// There's a database error
			log_message('error', 'School update failed: ' . json_encode($error));
			return FALSE;
		}
		
		// No error - query executed successfully (even if 0 rows affected)
		return TRUE;
	}
	
	/**
	 * Delete school
	 *
	 * @param	int	$school_id	School ID
	 * @param	int	$vendor_id	Vendor ID (for security)
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function deleteSchool($school_id, $vendor_id = NULL)
	{
		$this->db->where('id', $school_id);
		
		if ($vendor_id !== NULL)
		{
			$this->db->where('vendor_id', $vendor_id);
		}
		
		$this->db->delete('erp_schools');
		
		return $this->db->affected_rows() > 0;
	}
	
	/**
	 * Get school images
	 *
	 * @param	int	$school_id	School ID
	 * @return	array	Array of images
	 */
	public function getSchoolImages($school_id)
	{
		$this->db->where('school_id', $school_id);
		$this->db->order_by('is_primary', 'DESC');
		$this->db->order_by('display_order', 'ASC');
		$query = $this->db->get('erp_school_images');
		
		return $query->result_array();
	}
	
	/**
	 * Add school image
	 *
	 * @param	array	$data	Image data
	 * @return	int|FALSE	Image ID on success, FALSE on failure
	 */
	public function addSchoolImage($data)
	{
		$this->db->insert('erp_school_images', $data);
		
		if ($this->db->affected_rows() > 0)
		{
			return $this->db->insert_id();
		}
		
		return FALSE;
	}
	
	/**
	 * Delete school image
	 *
	 * @param	int	$image_id	Image ID
	 * @param	int	$school_id	School ID (for security)
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function deleteSchoolImage($image_id, $school_id = NULL)
	{
		$this->db->where('id', $image_id);
		
		if ($school_id !== NULL)
		{
			$this->db->where('school_id', $school_id);
		}
		
		$this->db->delete('erp_school_images');
		
		return $this->db->affected_rows() > 0;
	}
	/**
	 * Delete all images for a school
	 *
	 * @param int $school_id
	 * @return bool
	 */
	public function deleteSchoolImagesBySchool($school_id)
	{
		$this->db->where('school_id', $school_id)
				->delete('erp_school_images');

		return $this->db->affected_rows() > 0;
	}
	
	/**
	 * Set primary image
	 *
	 * @param	int	$image_id	Image ID
	 * @param	int	$school_id	School ID
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function setPrimaryImage($image_id, $school_id)
	{
		// First, unset all primary images for this school
		$this->db->where('school_id', $school_id);
		$this->db->update('erp_school_images', array('is_primary' => 0));
		
		// Then set the selected image as primary
		$this->db->where('id', $image_id);
		$this->db->where('school_id', $school_id);
		$this->db->update('erp_school_images', array('is_primary' => 1));
		
		return $this->db->affected_rows() > 0;
	}
	
	/**
	 * Get total schools by vendor
	 *
	 * @param	int	$vendor_id	Vendor ID
	 * @param	array	$filters	Optional filters
	 * @return	int	Total number of schools
	 */
	public function getTotalSchoolsByVendor($vendor_id, $filters = array())
	{
		$this->db->where('erp_schools.vendor_id', $vendor_id);
		
		if (isset($filters['status']))
		{
			$this->db->where('erp_schools.status', $filters['status']);
		}
		
		if (isset($filters['search']))
		{
			$this->db->group_start();
			$this->db->like('erp_schools.school_name', $filters['search']);
			$this->db->or_like('erp_schools.school_board', $filters['search']);
			$this->db->or_like('erp_schools.admin_email', $filters['search']);
			$this->db->group_end();
		}
		
		return $this->db->count_all_results('erp_schools');
	}
	
	/**
	 * Save school boards (many-to-many)
	 *
	 * @param	int	$school_id	School ID
	 * @param	array	$board_ids	Array of board IDs
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function saveSchoolBoards($school_id, $board_ids)
	{
		// Delete existing mappings
		$this->db->where('school_id', $school_id);
		$this->db->delete('erp_school_boards_mapping');
		
		// Insert new mappings
		if (!empty($board_ids) && is_array($board_ids))
		{
			foreach ($board_ids as $board_id)
			{
				$mapping_data = array(
					'school_id' => $school_id,
					'board_id' => (int)$board_id
				);
				$this->db->insert('erp_school_boards_mapping', $mapping_data);
				
				// Check for errors
				$error = $this->db->error();
				if (isset($error['code']) && $error['code'] != 0)
				{
					log_message('error', 'Failed to save school board mapping: ' . json_encode($error));
					return FALSE;
				}
			}
		}
		
		return TRUE;
	}
	
	/**
	 * Get school board IDs
	 *
	 * @param	int	$school_id	School ID
	 * @return	array	Array of board IDs
	 */
	public function getSchoolBoardIds($school_id)
	{
		$this->db->select('board_id');
		$this->db->from('erp_school_boards_mapping');
		$this->db->where('school_id', $school_id);
		$query = $this->db->get();
		
		$board_ids = array();
		if ($query->num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
				$board_ids[] = $row['board_id'];
			}
		}
		
		return $board_ids;
	}
	
	/**
	 * Get school board names (comma-separated)
	 *
	 * @param	int	$school_id	School ID
	 * @return	string	Comma-separated board names
	 */
	public function getSchoolBoardNames($school_id)
	{
		$this->db->select('erp_school_boards.board_name');
		$this->db->from('erp_school_boards_mapping');
		$this->db->join('erp_school_boards', 'erp_school_boards.id = erp_school_boards_mapping.board_id', 'inner');
		$this->db->where('erp_school_boards_mapping.school_id', $school_id);
		$this->db->order_by('erp_school_boards.board_name', 'ASC');
		$query = $this->db->get();
		
		$board_names = array();
		if ($query->num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
				$board_names[] = $row['board_name'];
			}
		}
		
		return implode(', ', $board_names);
	}
	
	/**
	 * Get board names from comma-separated IDs
	 *
	 * @param	string	$board_ids	Comma-separated board IDs (e.g., "10,7,8")
	 * @return	string	Comma-separated board names
	 */
	public function getBoardNamesFromIds($board_ids)
	{
		if (empty($board_ids))
		{
			return '';
		}
		
		$ids = explode(',', $board_ids);
		$ids = array_filter(array_map('trim', $ids));
		
		if (empty($ids))
		{
			return '';
		}
		
		$this->db->select('board_name');
		$this->db->from('erp_school_boards');
		$this->db->where_in('id', $ids);
		$this->db->order_by('board_name', 'ASC');
		$query = $this->db->get();
		
		$board_names = array();
		if ($query->num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
				$board_names[] = $row['board_name'];
			}
		}
		
		return implode(', ', $board_names);
	}
}

