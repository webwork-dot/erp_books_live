<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Vendor Site Settings Model
 *
 * Handles database operations for vendor site customization settings.
 * Synchronizes settings with the tenant/client database as well as the master database.
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
		// Load master database connection
		$this->master_db = $this->load->database('master', TRUE);
	}

	/**
	 * Get the client database connection for a vendor
	 *
	 * @param	int	$vendor_id
	 * @return	CI_DB
	 */
	private function get_client_db($vendor_id)
	{
		// 1. If $this->db is already loaded and is a tenant DB (not master DB)
		if (isset($this->db) && is_object($this->db)) {
			if (!empty($this->db->database) && $this->db->database !== 'erp_master') {
				return $this->db;
			}
		}

		// 2. Try looking up client database from master DB
		try {
			$this->master_db->where('id', $vendor_id);
			$client = $this->master_db->get('erp_clients')->row_array();
			if ($client && !empty($client['database_name'])) {
				$db_config = array(
					'dsn'	=> '',
					'hostname' => 'localhost',
					'username' => !empty($client['db_username']) ? $client['db_username'] : 'root',
					'password' => !empty($client['db_password']) ? $client['db_password'] : '',
					'database' => $client['database_name'],
					'dbdriver' => 'mysqli',
					'dbprefix' => '',
					'pconnect' => FALSE,
					'db_debug' => FALSE,
					'cache_on' => FALSE,
					'cachedir' => '',
					'char_set' => 'utf8',
					'dbcollat' => 'utf8_general_ci',
					'swap_pre' => '',
					'encrypt' => FALSE,
					'compress' => FALSE,
					'stricton' => FALSE,
					'failover' => array(),
					'save_queries' => TRUE
				);
				return $this->load->database($db_config, TRUE);
			}
		} catch (Exception $e) {
			log_message('error', 'Error connecting to vendor client database: ' . $e->getMessage());
		}

		return $this->db;
	}

	/**
	 * Get settings for a specific vendor
	 * Priority: Client Database first, then Master Database fallback
	 *
	 * @param	int	$vendor_id
	 * @return	array|null
	 */
	public function get_settings($vendor_id)
	{
		$client_db = $this->get_client_db($vendor_id);
		if ($client_db && is_object($client_db)) {
			try {
				if ($client_db->table_exists('vendor_site_settings')) {
					$client_db->where('vendor_id', $vendor_id);
					$query = $client_db->get('vendor_site_settings');
					if ($query && $query->num_rows() > 0) {
						return $query->row_array();
					}
					// Check any row if vendor_id column didn't match
					$any_query = $client_db->order_by('id', 'DESC')->limit(1)->get('vendor_site_settings');
					if ($any_query && $any_query->num_rows() > 0) {
						return $any_query->row_array();
					}
				}
			} catch (Exception $e) {
				log_message('error', 'Failed to get settings from client DB: ' . $e->getMessage());
			}
		}

		$this->master_db->where('vendor_id', $vendor_id);
		$query = $this->master_db->get('vendor_site_settings');

		if ($query && $query->num_rows() > 0) {
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
			'shipping_charge' => 60.00,
			'banner_image' => NULL,
			'is_active' => 1
		);

		// Insert in client DB
		$client_db = $this->get_client_db($vendor_id);
		if ($client_db && is_object($client_db)) {
			try {
				if ($client_db->table_exists('vendor_site_settings')) {
					$client_db->insert('vendor_site_settings', $default_settings);
				}
			} catch (Exception $e) {
				log_message('error', 'Failed to insert default settings in client DB: ' . $e->getMessage());
			}
		}

		return $this->master_db->insert('vendor_site_settings', $default_settings);
	}

	/**
	 * Save/update settings for a vendor
	 * Saves to client DB (so frontend gets live updates) AND syncs to master DB
	 *
	 * @param	int	$vendor_id
	 * @param	array	$settings_data
	 * @return	bool
	 */
	public function save_settings($vendor_id, $settings_data)
	{
		$client_db = $this->get_client_db($vendor_id);
		$client_saved = false;

		// 1. Update in Client DB (primary source of truth for frontend)
		if ($client_db && is_object($client_db)) {
			try {
				if ($client_db->table_exists('vendor_site_settings')) {
					$client_db->where('vendor_id', $vendor_id);
					$query = $client_db->get('vendor_site_settings');

					if ($query && $query->num_rows() > 0) {
						$client_db->where('vendor_id', $vendor_id);
						$client_saved = $client_db->update('vendor_site_settings', $settings_data);
					} else {
						// Check if any row exists in vendor_site_settings
						$any_query = $client_db->limit(1)->get('vendor_site_settings');
						if ($any_query && $any_query->num_rows() > 0) {
							$row = $any_query->row_array();
							$client_db->where('id', $row['id']);
							$client_data = $settings_data;
							$client_data['vendor_id'] = $vendor_id;
							$client_saved = $client_db->update('vendor_site_settings', $client_data);
						} else {
							$insert_data = $settings_data;
							$insert_data['vendor_id'] = $vendor_id;
							$client_saved = $client_db->insert('vendor_site_settings', $insert_data);
						}
					}
				}

				// Also sync shipping_charge to erp_clients table in client DB if column exists
				if (isset($settings_data['shipping_charge']) && $client_db->table_exists('erp_clients')) {
					if ($client_db->field_exists('shipping_charge', 'erp_clients')) {
						$client_db->update('erp_clients', array('shipping_charge' => $settings_data['shipping_charge']));
					}
				}
			} catch (Exception $e) {
				log_message('error', 'Failed to update client DB vendor_site_settings: ' . $e->getMessage());
			}
		}

		// 2. Also update Master DB for consistency
		$master_saved = false;
		try {
			$this->master_db->where('vendor_id', $vendor_id);
			$query = $this->master_db->get('vendor_site_settings');

			if ($query && $query->num_rows() > 0) {
				$this->master_db->where('vendor_id', $vendor_id);
				$master_saved = $this->master_db->update('vendor_site_settings', $settings_data);
			} else {
				$insert_data = $settings_data;
				$insert_data['vendor_id'] = $vendor_id;
				$master_saved = $this->master_db->insert('vendor_site_settings', $insert_data);
			}

			if (isset($settings_data['shipping_charge']) && $this->master_db->table_exists('erp_clients')) {
				if ($this->master_db->field_exists('shipping_charge', 'erp_clients')) {
					$this->master_db->where('id', $vendor_id);
					$this->master_db->update('erp_clients', array('shipping_charge' => $settings_data['shipping_charge']));
				}
			}
		} catch (Exception $e) {
			log_message('error', 'Failed to update master DB vendor_site_settings: ' . $e->getMessage());
		}

		return $client_saved || $master_saved;
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
		$client_db = $this->get_client_db($vendor_id);
		if ($client_db && is_object($client_db)) {
			try {
				if ($client_db->table_exists('vendor_site_settings')) {
					$client_db->where('vendor_id', $vendor_id);
					$client_db->update('vendor_site_settings', $settings_data);
				}
			} catch (Exception $e) {
				log_message('error', 'Failed to update settings in client DB: ' . $e->getMessage());
			}
		}

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
		return $this->get_settings($vendor_id);
	}

	/**
	 * Delete settings for a vendor
	 *
	 * @param	int	$vendor_id
	 * @return	bool
	 */
	public function delete_settings($vendor_id)
	{
		$client_db = $this->get_client_db($vendor_id);
		if ($client_db && is_object($client_db)) {
			try {
				if ($client_db->table_exists('vendor_site_settings')) {
					$client_db->where('vendor_id', $vendor_id);
					$client_db->delete('vendor_site_settings');
				}
			} catch (Exception $e) {
				log_message('error', 'Failed to delete settings in client DB: ' . $e->getMessage());
			}
		}

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
		$client_db = $this->get_client_db($vendor_id);
		if ($client_db && is_object($client_db)) {
			try {
				if ($client_db->table_exists('vendor_site_settings')) {
					$client_db->where('vendor_id', $vendor_id);
					$client_db->update('vendor_site_settings', array('is_active' => $status));
				}
			} catch (Exception $e) {
				log_message('error', 'Failed to toggle status in client DB: ' . $e->getMessage());
			}
		}

		$this->master_db->where('vendor_id', $vendor_id);
		return $this->master_db->update('vendor_site_settings', array('is_active' => $status));
	}
}
