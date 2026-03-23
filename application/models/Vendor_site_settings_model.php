<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Vendor Site Settings Model
 *
 * Handles database operations for vendor site customization settings
 *
 * @package		ERP
 * @subpackage	Models
 * @category	Vendor
 * @author		ERP Team
 */
class Vendor_site_settings_model extends CI_Model
{
	/**
	 * Master database connection
	 */
	private $master_db;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		// Load master database connection (vendor_site_settings table is in master DB)
		$this->master_db = $this->load->database('master', TRUE);
	}

	/**
	 * Get settings for a specific vendor
	 *
	 * @param	int	$vendor_id
	 * @return	array|null
	 */
	public function get_settings($vendor_id)
	{
		$this->master_db->where('vendor_id', $vendor_id);
		$query = $this->master_db->get('vendor_site_settings');

		if ($query->num_rows() > 0) {
			return $query->row_array();
		}

		return NULL;
	}

	/**
	 * Create default settings for a vendor
	 *
	 * @param	int	$vendor_id
	 * @return	bool
	 */
	public function create_default_settings($vendor_id)
	{
		$default_settings = array(
			'vendor_id' => $vendor_id,
			'site_title' => 'My Online Store',
			'site_description' => 'Welcome to our online store',
			'primary_color' => '#116B31',
			'secondary_color' => '#ffffff',
			'accent_color' => '#28a745',
			'header_bg_color' => '#ffffff',
			'footer_bg_color' => '#f8f9fa',
			'text_primary_color' => '#333333',
			'text_secondary_color' => '#666666',
			'link_color' => '#116B31',
			'link_hover_color' => '#0d5a26',
			'button_primary_bg' => '#116B31',
			'button_primary_text' => '#ffffff',
			'button_secondary_bg' => '#6c757d',
			'button_secondary_text' => '#ffffff',
			'modal_bg_gradient_start' => '#116B31',
			'modal_bg_gradient_end' => '#28a745',
			'modal_button_bg' => '#ffffff',
			'modal_button_text' => '#116B31',
			'since_text' => 'SINCE 1952',
			'banner_image' => NULL,
			'is_active' => 1
		);

		return $this->master_db->insert('vendor_site_settings', $default_settings);
	}

	/**
	 * Save/update settings for a vendor
	 *
	 * @param	int	$vendor_id
	 * @param	array	$settings_data
	 * @return	bool
	 */
	public function save_settings($vendor_id, $settings_data)
	{
		$this->master_db->where('vendor_id', $vendor_id);
		$query = $this->master_db->get('vendor_site_settings');

		if ($query->num_rows() > 0) {
			// Update existing settings
			$this->master_db->where('vendor_id', $vendor_id);
			return $this->master_db->update('vendor_site_settings', $settings_data);
		} else {
			// Insert new settings
			$settings_data['vendor_id'] = $vendor_id;
			return $this->master_db->insert('vendor_site_settings', $settings_data);
		}
	}

	/**
	 * Update specific settings for a vendor
	 *
	 * @param	int	$vendor_id
	 * @param	array	$settings_data
	 * @return	bool
	 */
	public function update_settings($vendor_id, $settings_data)
	{
		$this->master_db->where('vendor_id', $vendor_id);
		return $this->master_db->update('vendor_site_settings', $settings_data);
	}

	/**
	 * Get all active vendor settings
	 *
	 * @return	array
	 */
	public function get_all_active_settings()
	{
		$this->master_db->where('is_active', 1);
		$query = $this->master_db->get('vendor_site_settings');
		return $query->result_array();
	}

	/**
	 * Get settings by vendor ID (for single vendor database)
	 *
	 * @param	int	$vendor_id
	 * @return	array|null
	 */
	public function get_settings_by_vendor_id($vendor_id)
	{
		$this->master_db->where('vendor_id', $vendor_id);
		$this->master_db->where('is_active', 1);
		$query = $this->master_db->get('vendor_site_settings');

		if ($query->num_rows() > 0) {
			return $query->row_array();
		}

		return NULL;
	}

	/**
	 * Delete settings for a vendor
	 *
	 * @param	int	$vendor_id
	 * @return	bool
	 */
	public function delete_settings($vendor_id)
	{
		$this->master_db->where('vendor_id', $vendor_id);
		return $this->master_db->delete('vendor_site_settings');
	}

	/**
	 * Toggle settings active status
	 *
	 * @param	int	$vendor_id
	 * @param	int	$status
	 * @return	bool
	 */
	public function toggle_active_status($vendor_id, $status)
	{
		$this->master_db->where('vendor_id', $vendor_id);
		return $this->master_db->update('vendor_site_settings', array('is_active' => $status));
	}
}
