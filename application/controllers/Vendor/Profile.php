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
	 * Get profile data (AJAX) - reads directly from erp_clients
	 *
	 * @return	void
	 */
	public function get_profile()
	{
		header('Content-Type: application/json');
		
		$client = $this->Erp_client_model->getFirstClient();
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
	 * Update profile (AJAX) - updates erp_clients directly
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

		$this->Erp_client_model->updateFirstClient($update_data);
		
		echo json_encode(array(
			'success' => true,
			'message' => 'Profile updated successfully',
			'data' => $update_data
		));
	}
}

