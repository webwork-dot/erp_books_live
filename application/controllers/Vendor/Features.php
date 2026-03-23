<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Vendor Features Controller
 *
 * Handles feature image management for vendors
 *
 * @package		ERP
 * @subpackage	Controllers
 * @category	Controllers
 * @author		ERP Team
 */

// Load base controller
require_once(APPPATH . 'controllers/Vendor/Vendor_base.php');

class Features extends Vendor_base
{
	/**
	 * Constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->library('upload');
		$this->load->helper('file');
	}
	
	/**
	 * List all vendor features
	 *
	 * @return	void
	 */
	public function index()
	{
		// Ensure image column exists
		$this->ensureVendorFeaturesColumns();
		
		// Get only enabled features for this vendor
		$this->db->select('*');
		$this->db->from('vendor_features');
		$this->db->where('is_enabled', 1);
		$this->db->order_by('feature_name', 'ASC');
		$query = $this->db->get();
		$features = $query->result_array();
		
		$data['features'] = $features;
		$data['title'] = 'Manage Feature Images';
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $this->getVendorDomainForUrl();
		$data['breadcrumb'] = array(
			array('label' => 'Features', 'active' => true)
		);
		
		// Load content view
		$data['content'] = $this->load->view('vendor/features/index', $data, TRUE);
		
		// Load main layout
		$this->load->view('vendor/layouts/index_template', $data);
	}
	
	/**
	 * Upload feature image
	 *
	 * @return	void
	 */
	public function upload_image()
	{
		$feature_id = $this->input->post('feature_id');
		
		if (empty($feature_id))
		{
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode(array(
					'success' => false,
					'message' => 'Feature ID is required'
				)));
			return;
		}
		
		// Verify feature belongs to vendor (check if feature exists in vendor_features table)
		$this->db->where('feature_id', $feature_id);
		$feature = $this->db->get('vendor_features')->row_array();
		
		if (!$feature)
		{
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode(array(
					'success' => false,
					'message' => 'Feature not found or not assigned to your account'
				)));
			return;
		}
		
		// Create upload directory if it doesn't exist
		$upload_path = './uploads/vendor_features/';
		if (!is_dir($upload_path))
		{
			mkdir($upload_path, 0755, true);
		}
		
		// Configure upload
		$config['upload_path'] = $upload_path;
		$config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
		$config['max_size'] = 2048; // 2MB
		$config['file_name'] = 'feature_' . $feature_id . '_' . time();
		$config['overwrite'] = TRUE;
		
		$this->upload->initialize($config);
		
		if ($this->upload->do_upload('image'))
		{
			$upload_data = $this->upload->data();
			
			// Delete old image if exists
			if (!empty($feature['image']) && file_exists($upload_path . $feature['image']))
			{
				unlink($upload_path . $feature['image']);
			}
			
			// Update database
			$this->db->where('feature_id', $feature_id);
			$this->db->update('vendor_features', array(
				'image' => $upload_data['file_name'],
				'updated_at' => date('Y-m-d H:i:s')
			));
			
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode(array(
					'success' => true,
					'message' => 'Image uploaded successfully',
					'image_url' => base_url('uploads/vendor_features/' . $upload_data['file_name'])
				)));
		}
		else
		{
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode(array(
					'success' => false,
					'message' => $this->upload->display_errors('', '')
				)));
		}
	}
	
	/**
	 * Delete feature image
	 *
	 * @return	void
	 */
	public function delete_image()
	{
		$feature_id = $this->input->post('feature_id');
		
		if (empty($feature_id))
		{
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode(array(
					'success' => false,
					'message' => 'Feature ID is required'
				)));
			return;
		}
		
		// Verify feature belongs to vendor (check if feature exists in vendor_features table)
		$this->db->where('feature_id', $feature_id);
		$feature = $this->db->get('vendor_features')->row_array();
		
		if (!$feature)
		{
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode(array(
					'success' => false,
					'message' => 'Feature not found or not assigned to your account'
				)));
			return;
		}
		
		// Delete image file
		if (!empty($feature['image']))
		{
			$image_path = './uploads/vendor_features/' . $feature['image'];
			if (file_exists($image_path))
			{
				unlink($image_path);
			}
		}
		
		// Update database
		$this->db->where('feature_id', $feature_id);
		$this->db->update('vendor_features', array(
			'image' => NULL,
			'updated_at' => date('Y-m-d H:i:s')
		));
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array(
				'success' => true,
				'message' => 'Image deleted successfully'
			)));
	}
	
	/**
	 * Ensure vendor_features table has required columns
	 *
	 * @return	void
	 */
	private function ensureVendorFeaturesColumns()
	{
		try {
			// Check if image column exists
			$check_image = $this->db->query("SHOW COLUMNS FROM vendor_features LIKE 'image'");
			if (!$check_image || $check_image->num_rows() == 0)
			{
				$this->db->query("ALTER TABLE vendor_features ADD COLUMN `image` VARCHAR(255) NULL DEFAULT NULL AFTER `feature_name`");
			}
			
			// Check if updated_at column exists
			$check_updated = $this->db->query("SHOW COLUMNS FROM vendor_features LIKE 'updated_at'");
			if (!$check_updated || $check_updated->num_rows() == 0)
			{
				$this->db->query("ALTER TABLE vendor_features ADD COLUMN `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP AFTER `synced_at`");
			}
			
			// Check if idx_image index exists
			$check_index = $this->db->query("SHOW INDEX FROM vendor_features WHERE Key_name = 'idx_image'");
			if (!$check_index || $check_index->num_rows() == 0)
			{
				$this->db->query("ALTER TABLE vendor_features ADD INDEX `idx_image` (`image`)");
			}
		} catch (Exception $e) {
			// Silently fail if table doesn't exist or other error
			log_message('debug', 'Could not ensure vendor_features columns: ' . $e->getMessage());
		}
	}
}

