<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Banners Model
 *
 * Handles database operations for vendor banner management
 *
 * @package		ERP
 * @subpackage	Models
 * @category	Banners
 * @author		ERP Team
 */
class Banners_model extends CI_Model {

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	/**
	 * Get all banners for a specific vendor
	 *
	 * @param	int	$vendor_id
	 * @return	array
	 */
	public function get_banners_by_vendor($vendor_id)
	{
		$this->db->where('vendor_id', $vendor_id);
		$this->db->order_by('sort_order ASC, created_at DESC');
		$query = $this->db->get('banners');
		return $query->result_array();
	}

	/**
	 * Get a specific banner by ID
	 *
	 * @param	int	$banner_id
	 * @return	array|null
	 */
	public function get_banner_by_id($banner_id)
	{
		$this->db->where('id', $banner_id);
		$query = $this->db->get('banners');
		return $query->row_array();
	}

	/**
	 * Add a new banner
	 *
	 * @param	array	$banner_data
	 * @return	int|bool	ID of inserted record or FALSE on failure
	 */
	public function add_banner($banner_data)
	{
		$result = $this->db->insert('banners', $banner_data);
		return $result ? $this->db->insert_id() : FALSE;
	}

	/**
	 * Update an existing banner
	 *
	 * @param	int	$banner_id
	 * @param	array	$banner_data
	 * @return	bool
	 */
	public function update_banner($banner_id, $banner_data)
	{
		$this->db->where('id', $banner_id);
		return $this->db->update('banners', $banner_data);
	}

	/**
	 * Delete a banner
	 *
	 * @param	int	$banner_id
	 * @return	bool
	 */
	public function delete_banner($banner_id)
	{
		$this->db->where('id', $banner_id);
		return $this->db->delete('banners');
	}

	/**
	 * Activate/Deactivate a banner
	 *
	 * @param	int	$banner_id
	 * @param	bool	$is_active
	 * @return	bool
	 */
	public function set_banner_active_status($banner_id, $is_active)
	{
		$this->db->where('id', $banner_id);
		return $this->db->update('banners', array('is_active' => (int)$is_active));
	}
}