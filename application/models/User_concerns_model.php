<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User Concerns Model
 *
 * Handles tbl_user_concerns - concerns from frontend linked via user_id
 * Columns: id, user_id, order_id, concern_type, message, contact_preference, status, admin_response, created_at, updated_at
 *
 * @package		ERP
 * @subpackage	Models
 * @category	Models
 */
class User_concerns_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Get concerns with customer info
	 *
	 * @param	array	$filters	Optional: status, concern_type, search
	 * @param	int	$limit
	 * @param	int	$offset
	 * @return	array
	 */
	public function get_concerns($filters = array(), $limit = 20, $offset = 0)
	{
		$this->db->select('c.id, c.user_id, c.order_id, c.concern_type, c.message, c.contact_preference, c.status, c.admin_response, c.created_at, c.updated_at');
		$this->db->select('u.username, u.email, u.phone_number, u.dial_code, u.firm_name');
		if ($this->db->table_exists('tbl_order_details')) {
			$this->db->select('od.order_unique_id');
		}
		$this->db->from('tbl_user_concerns c');
		$this->db->join('users u', 'u.id = c.user_id', 'left');
		if ($this->db->table_exists('tbl_order_details')) {
			$this->db->join('tbl_order_details od', 'od.id = c.order_id AND c.order_id > 0', 'left');
		}

		if (!empty($filters['status'])) {
			$this->db->where('c.status', $filters['status']);
		}
		if (!empty($filters['concern_type'])) {
			$this->db->where('c.concern_type', $filters['concern_type']);
		}
		if (!empty($filters['search'])) {
			$s = $this->db->escape_like_str($filters['search']);
			$this->db->group_start();
			$this->db->like('c.message', $s);
			$this->db->or_like('c.concern_type', $s);
			if (is_numeric($filters['search'])) {
				$this->db->or_where('c.order_id', (int) $filters['search']);
			}
			$this->db->or_like('u.username', $s);
			$this->db->or_like('u.email', $s);
			$this->db->or_like('u.phone_number', $s);
			$this->db->or_like('u.firm_name', $s);
			$this->db->group_end();
		}

		$this->db->order_by('c.created_at', 'DESC');
		$this->db->limit($limit, $offset);
		$query = $this->db->get();
		return $query->result_array();
	}

	/**
	 * Get total concerns count
	 *
	 * @param	array	$filters
	 * @return	int
	 */
	public function get_total_concerns($filters = array())
	{
		$this->db->from('tbl_user_concerns c');
		$this->db->join('users u', 'u.id = c.user_id', 'left');

		if (!empty($filters['status'])) {
			$this->db->where('c.status', $filters['status']);
		}
		if (!empty($filters['concern_type'])) {
			$this->db->where('c.concern_type', $filters['concern_type']);
		}
		if (!empty($filters['search'])) {
			$s = $this->db->escape_like_str($filters['search']);
			$this->db->group_start();
			$this->db->like('c.message', $s);
			$this->db->or_like('c.concern_type', $s);
			if (is_numeric($filters['search'])) {
				$this->db->or_where('c.order_id', (int) $filters['search']);
			}
			$this->db->or_like('u.username', $s);
			$this->db->or_like('u.email', $s);
			$this->db->or_like('u.phone_number', $s);
			$this->db->or_like('u.firm_name', $s);
			$this->db->group_end();
		}

		return $this->db->count_all_results();
	}

	/**
	 * Get single concern by ID with customer info
	 *
	 * @param	int	$id
	 * @return	array|false
	 */
	public function get_concern_by_id($id)
	{
		$this->db->select('c.*');
		$this->db->select('u.username, u.email, u.phone_number, u.dial_code, u.firm_name');
		$this->db->from('tbl_user_concerns c');
		$this->db->join('users u', 'u.id = c.user_id', 'left');
		$this->db->where('c.id', (int) $id);
		$query = $this->db->get();
		return $query->row_array();
	}

	/**
	 * Update concern status
	 *
	 * @param	int	$id
	 * @param	string	$status	pending|in_progress|resolved
	 * @return	bool
	 */
	public function update_status($id, $status)
	{
		$allowed = array('pending', 'in_progress', 'resolved');
		if (!in_array($status, $allowed)) {
			return false;
		}
		$this->db->where('id', (int) $id);
		$this->db->update('tbl_user_concerns', array(
			'status' => $status,
			'updated_at' => date('Y-m-d H:i:s')
		));
		return $this->db->affected_rows() > 0;
	}

	/**
	 * Update admin response
	 *
	 * @param	int	$id
	 * @param	string	$admin_response
	 * @return	bool
	 */
	public function update_admin_response($id, $admin_response)
	{
		$this->db->where('id', (int) $id);
		$this->db->update('tbl_user_concerns', array(
			'admin_response' => $admin_response,
			'updated_at' => date('Y-m-d H:i:s')
		));
		return $this->db->affected_rows() > 0;
	}

	/**
	 * Check if tbl_user_concerns exists
	 *
	 * @return	bool
	 */
	public function table_exists()
	{
		return $this->db->table_exists('tbl_user_concerns');
	}
}
