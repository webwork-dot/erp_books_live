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
		$this->load->library('form_validation');
	}

	/**
	 * Get profile data (AJAX)
	 *
	 * @return	void
	 */
	public function get_profile()
	{
		header('Content-Type: application/json');
		
		if (!$this->input->is_ajax_request()) {
			echo json_encode(array('success' => false, 'message' => 'Invalid request'));
			return;
		}

		$vendor_id = $this->current_vendor['id'];
		$vendor = $this->Erp_client_model->getClientById($vendor_id);

		if ($vendor) {
			// Return only the profile fields
			$profile_data = array(
				'name' => isset($vendor['name']) ? $vendor['name'] : '',
				'address' => isset($vendor['address']) ? $vendor['address'] : '',
				'pincode' => isset($vendor['pincode']) ? $vendor['pincode'] : '',
				'pan' => isset($vendor['pan']) ? $vendor['pan'] : '',
				'gstin' => isset($vendor['gstin']) ? $vendor['gstin'] : ''
			);
			
			echo json_encode(array('success' => true, 'data' => $profile_data));
		} else {
			echo json_encode(array('success' => false, 'message' => 'Vendor not found'));
		}
	}

	/**
	 * Update profile (AJAX)
	 *
	 * @return	void
	 */
	public function update_profile()
	{
		header('Content-Type: application/json');
		
		if (!$this->input->is_ajax_request()) {
			echo json_encode(array('success' => false, 'message' => 'Invalid request'));
			return;
		}

		// Set validation rules
		$this->form_validation->set_rules('name', 'Name', 'required|trim|max_length[255]');
		$this->form_validation->set_rules('address', 'Address', 'trim');
		$this->form_validation->set_rules('pincode', 'Pincode', 'trim|max_length[10]');
		$this->form_validation->set_rules('pan', 'PAN', 'trim|max_length[20]');
		$this->form_validation->set_rules('gstin', 'GSTIN', 'trim|max_length[20]');

		if ($this->form_validation->run() == FALSE) {
			echo json_encode(array(
				'success' => false,
				'message' => 'Validation failed',
				'errors' => $this->form_validation->error_array()
			));
			return;
		}

		$vendor_id = $this->current_vendor['id'];
		
		// Prepare update data
		$update_data = array(
			'name' => $this->input->post('name'),
			'address' => $this->input->post('address'),
			'pincode' => $this->input->post('pincode'),
			'pan' => strtoupper($this->input->post('pan')), // PAN is usually uppercase
			'gstin' => strtoupper($this->input->post('gstin')) // GSTIN is usually uppercase
		);

		// Update vendor profile
		$result = $this->Erp_client_model->updateClient($vendor_id, $update_data);

		if ($result) {
			// Reload vendor data to get updated info
			$this->current_vendor = $this->Erp_client_model->getClientById($vendor_id);
			
			echo json_encode(array(
				'success' => true,
				'message' => 'Profile updated successfully',
				'data' => array(
					'name' => $this->current_vendor['name'],
					'address' => isset($this->current_vendor['address']) ? $this->current_vendor['address'] : '',
					'pincode' => isset($this->current_vendor['pincode']) ? $this->current_vendor['pincode'] : '',
					'pan' => isset($this->current_vendor['pan']) ? $this->current_vendor['pan'] : '',
					'gstin' => isset($this->current_vendor['gstin']) ? $this->current_vendor['gstin'] : ''
				)
			));
		} else {
			echo json_encode(array('success' => false, 'message' => 'Failed to update profile'));
		}
	}
}

