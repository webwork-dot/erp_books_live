<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * School Board Model
 *
 * Handles database operations for school boards
 *
 * @package		ERP
 * @subpackage	Models
 * @category	Models
 * @author		ERP Team
 */
class School_board_model extends CI_Model
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
	 * Get all boards for vendor (including default/system boards)
	 *
	 * @param	int	$vendor_id	Vendor ID
	 * @return	array	Array of boards
	 */
	public function getBoardsByVendor($vendor_id)
	{
		$this->db->where('(vendor_id IS NULL OR vendor_id = ' . (int)$vendor_id . ')');
		$this->db->where('status', 'active');
		$this->db->order_by('vendor_id', 'ASC'); // System boards (NULL) first
		$this->db->order_by('board_name', 'ASC');
		$query = $this->db->get('erp_school_boards');
		
		return $query->result_array();
	}
	
	/**
	 * Get board by ID
	 *
	 * @param	int	$board_id	Board ID
	 * @param	int	$vendor_id	Vendor ID (for security)
	 * @return	array|NULL	Board data or NULL if not found
	 */
	public function getBoardById($board_id, $vendor_id = NULL)
	{
		$this->db->where('id', $board_id);
		
		if ($vendor_id !== NULL)
		{
			$this->db->where('(vendor_id IS NULL OR vendor_id = ' . (int)$vendor_id . ')');
		}
		
		$query = $this->db->get('erp_school_boards');
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		
		return NULL;
	}
	
	/**
	 * Create board
	 *
	 * @param	array	$data	Board data
	 * @return	int|FALSE	Board ID on success, FALSE on failure
	 */
	public function createBoard($data)
	{
		$this->db->insert('erp_school_boards', $data);
		
		if ($this->db->affected_rows() > 0)
		{
			return $this->db->insert_id();
		}
		
		return FALSE;
	}
	
	/**
	 * Update board
	 *
	 * @param	int	$board_id	Board ID
	 * @param	array	$data		Board data
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function updateBoard($board_id, $data)
	{
		$this->db->where('id', $board_id);
		$this->db->update('erp_school_boards', $data);
		
		return $this->db->affected_rows() > 0;
	}
	
	/**
	 * Delete board
	 *
	 * @param	int	$board_id	Board ID
	 * @param	int	$vendor_id	Vendor ID (for security - can only delete own boards)
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function deleteBoard($board_id, $vendor_id)
	{
		// Can only delete own boards, not system boards (vendor_id IS NULL)
		$this->db->where('id', $board_id);
		$this->db->where('vendor_id', $vendor_id);
		$this->db->where('vendor_id IS NOT NULL'); // Cannot delete system boards
		$this->db->delete('erp_school_boards');
		
		return $this->db->affected_rows() > 0;
	}
	
	/**
	 * Check if board name exists for vendor
	 *
	 * @param	string	$board_name	Board name
	 * @param	int	$vendor_id	Vendor ID
	 * @param	int	$exclude_id	Board ID to exclude (for updates)
	 * @return	bool	TRUE if exists, FALSE otherwise
	 */
	public function boardNameExists($board_name, $vendor_id, $exclude_id = NULL)
	{
		$this->db->where('board_name', $board_name);
		$this->db->where('(vendor_id IS NULL OR vendor_id = ' . (int)$vendor_id . ')');
		
		if ($exclude_id !== NULL)
		{
			$this->db->where('id !=', $exclude_id);
		}
		
		$query = $this->db->get('erp_school_boards');
		
		return $query->num_rows() > 0;
	}
}

