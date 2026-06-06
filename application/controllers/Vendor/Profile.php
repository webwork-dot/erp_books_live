<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Vendor Profile Controller
 *
 * Handles vendor profile information updates
 *
 * @package		ERP
 * @subpackage	Controllers
 * @category	Vendor
 * @author		ERP Team
 */

// Load base controller
require_once(APPPATH . 'controllers/Vendor/Vendor_base.php');

class Profile extends Vendor_base
{
	/**
	 * Constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Erp_client_model');
	}

	/**
	 * Get profile data (AJAX) - reads directly from erp_clients in vendor database
	 *
	 * @return	void
	 */
	public function get_profile()
	{
		header('Content-Type: application/json');

		// Use vendor database (already switched by Vendor_base)
		$query = $this->db->select('id, name, address, pincode, pan, gstin')
						  ->from('erp_clients')
						  ->limit(1)
						  ->get();
		$client = ($query->num_rows() > 0) ? $query->row_array() : null;

		if ($client) {
			echo json_encode(array(
				'success' => true,
				'data' => array(
					'name' => isset($client['name']) ? $client['name'] : '',
					'address' => isset($client['address']) ? $client['address'] : '',
					'pincode' => isset($client['pincode']) ? $client['pincode'] : '',
					'pan' => isset($client['pan']) ? $client['pan'] : '',
					'gstin' => isset($client['gstin']) ? $client['gstin'] : ''
				)
			));
		} else {
			echo json_encode(array('success' => false, 'message' => 'No client found in erp_clients'));
		}
	}

	/**
	 * Update profile (AJAX) - updates erp_clients directly in vendor database AND master database
	 *
	 * @return	void
	 */
	public function update_profile()
	{
		header('Content-Type: application/json');

		$update_data = array(
			'name' => $this->input->post('name') ? trim($this->input->post('name')) : '',
			'address' => $this->input->post('address') ? trim($this->input->post('address')) : '',
			'pincode' => $this->input->post('pincode') ? trim($this->input->post('pincode')) : '',
			'pan' => $this->input->post('pan') ? strtoupper(trim($this->input->post('pan'))) : '',
			'gstin' => $this->input->post('gstin') ? strtoupper(trim($this->input->post('gstin'))) : ''
		);

		// Use vendor database (already switched by Vendor_base)
		// First get the first client's ID
		$first = $this->db->select('id')->from('erp_clients')->limit(1)->get()->row();
		if ($first) {
			$this->db->where('id', $first->id)->update('erp_clients', $update_data);
		}

		// Also update master database
		$master_db = $this->load->database('default', TRUE);
		if ($this->current_vendor && isset($this->current_vendor['id'])) {
			$master_db->where('id', $this->current_vendor['id'])->update('erp_clients', $update_data);
		}

		echo json_encode(array(
			'success' => true,
			'message' => 'Profile updated successfully',
			'data' => $update_data
		));
	}
}

