<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Site Settings Controller for Vendors
 *
 * Allows vendors to customize their live site appearance including logos and colors
 *
 * @package		ERP
 * @subpackage	Controllers
 * @category	Vendor
 * @author		ERP Team
 */

// Load base controller
require_once(APPPATH . 'controllers/Vendor/Vendor_base.php');

class SiteSettings extends Vendor_base
{
	/**
	 * Constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Vendor_site_settings_model');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('upload');
	}

	/**
	 * Index - Display site settings form
	 *
	 * @return	void
	 */
	public function index()
	{
		$data['page_title'] = 'Live Site Settings';
		$data['page_heading'] = 'Customize Your Live Site';
		$data['current_vendor'] = $this->current_vendor;

		// Get current settings for this vendor
		$vendor_id = $this->current_vendor['id'];
		$settings = $this->Vendor_site_settings_model->get_settings($vendor_id);

		// If no settings exist, create default ones
		if (!$settings) {
			$this->Vendor_site_settings_model->create_default_settings($vendor_id);
			$settings = $this->Vendor_site_settings_model->get_settings($vendor_id);
		}

		$data['settings'] = $settings;
		
		// Load banners for this vendor
		$this->load->model('Banners_model');
		$banners = $this->Banners_model->get_banners_by_vendor($vendor_id);
		
		// Ensure all banner images are available in frontend directory
		foreach ($banners as $banner) {
			$this->_sync_banner_to_frontend($banner['banner_image']);
		}
		
		$data['banners'] = $banners;
		
		// Load vendor features for image management
		$data['vendor_features'] = $this->_get_vendor_features();

		// Load content view and set as content variable
		$data['content'] = $this->load->view('vendor/site-settings/index', $data, TRUE);

		// Load main layout
		$this->load->view('vendor/layouts/index_template', $data);
	}

	/**
	 * Save site settings
	 *
	 * @return	void
	 */
	public function save()
	{
		log_message('debug', 'Save method called. POST data: ' . json_encode($this->input->post()));
		log_message('debug', 'FILES data: ' . json_encode($_FILES));

		// Validate form data
		$this->form_validation->set_rules('site_title', 'Site Title', 'trim|max_length[255]');
		$this->form_validation->set_rules('site_description', 'Site Description', 'trim');
		$this->form_validation->set_rules('meta_title', 'Meta Title', 'trim|max_length[255]');
		$this->form_validation->set_rules('meta_keywords', 'Meta Keywords', 'trim');
		$this->form_validation->set_rules('meta_description', 'Meta Description', 'trim|max_length[500]');
		$this->form_validation->set_rules('since_text', 'Since Text', 'trim|max_length[255]');

		if ($this->form_validation->run() === FALSE) {
			// Validation failed, reload form with errors
			log_message('error', 'Form validation failed. Errors: ' . json_encode($this->form_validation->error_array()));
			$this->index();
			return;
		}

		$vendor_id = $this->current_vendor['id'];

		// Prepare settings data
		$settings_data = array(
			'site_title' => $this->input->post('site_title'),
			'site_description' => $this->input->post('site_description'),
			'meta_title' => $this->input->post('meta_title'),
			'meta_keywords' => $this->input->post('meta_keywords'),
			'meta_description' => $this->input->post('meta_description'),
			'since_text' => $this->input->post('since_text'),
			'custom_css' => $this->input->post('custom_css')
		);

		// Handle logo upload
		log_message('debug', 'Starting logo upload process');
		$logo_uploaded = $this->_handle_logo_upload();
		if ($logo_uploaded) {
			$settings_data['logo_path'] = $logo_uploaded;
			log_message('debug', 'Logo uploaded successfully: ' . $logo_uploaded);
		} else {
			log_message('debug', 'Logo upload failed or no file provided');
		}

		// Handle favicon upload
		log_message('debug', 'Starting favicon upload process');
		$favicon_uploaded = $this->_handle_favicon_upload();
		if ($favicon_uploaded) {
			$settings_data['favicon_path'] = $favicon_uploaded;
			log_message('debug', 'Favicon uploaded successfully: ' . $favicon_uploaded);
		} else {
			log_message('debug', 'Favicon upload failed or no file provided');
		}

		// Handle single banner upload (for backward compatibility)
		// Note: For multiple banners, we now use a separate form/process
		// Only process if banner_image field exists in the main settings form
		if (isset($_FILES['banner_image']) && !empty($_FILES['banner_image']['name'])) {
			log_message('debug', 'Starting single banner upload process');
			$banner_uploaded = $this->_handle_single_banner_upload();
			if ($banner_uploaded) {
				$settings_data['banner_image'] = $banner_uploaded;
				log_message('debug', 'Single banner uploaded successfully: ' . $banner_uploaded);
			} else {
				log_message('debug', 'Single banner upload failed');
			}
		}

		// Save settings
		$result = $this->Vendor_site_settings_model->save_settings($vendor_id, $settings_data);

		if ($result) {
			$this->session->set_flashdata('success', 'Site settings saved successfully!');
		} else {
			$this->session->set_flashdata('error', 'Failed to save site settings. Please try again.');
		}

		redirect(base_url($this->current_vendor['domain'] . '/site-settings'));
	}

	/**
	 * Delete logo
	 *
	 * @return	void
	 */
	public function delete_logo()
	{
		$vendor_id = $this->current_vendor['id'];

		// Get current settings
		$settings = $this->Vendor_site_settings_model->get_settings($vendor_id);

		if ($settings && !empty($settings['logo_path'])) {
			// Delete file from backend uploads
			$file_path = FCPATH . $settings['logo_path'];
			if (file_exists($file_path)) {
				unlink($file_path);
			}

			// Delete file from frontend uploads
			$frontend_path = dirname(FCPATH) . '/frontend/' . $settings['logo_path'];
			if (file_exists($frontend_path)) {
				unlink($frontend_path);
			}

			// Also check old paths for backward compatibility
			$old_path1 = str_replace('uploads/vendors_logos/', 'uploads/vendors/', $file_path);
			$old_path2 = str_replace('uploads/', 'assets/', $file_path);
			$old_frontend_path1 = str_replace('uploads/vendors_logos/', 'uploads/vendors/', $frontend_path);
			$old_frontend_path2 = str_replace('uploads/', 'assets/', $frontend_path);

			if (file_exists($old_path1)) {
				unlink($old_path1);
			}
			if (file_exists($old_path2)) {
				unlink($old_path2);
			}
			if (file_exists($old_frontend_path1)) {
				unlink($old_frontend_path1);
			}
			if (file_exists($old_frontend_path2)) {
				unlink($old_frontend_path2);
			}

			// Remove from database
			$this->Vendor_site_settings_model->update_settings($vendor_id, array('logo_path' => NULL));
		}

		$this->session->set_flashdata('success', 'Logo deleted successfully!');
		redirect(base_url($this->current_vendor['domain'] . '/site-settings'));
	}

	/**
	 * Delete favicon
	 *
	 * @return	void
	 */
	public function delete_favicon()
	{
		$vendor_id = $this->current_vendor['id'];

		// Get current settings
		$settings = $this->Vendor_site_settings_model->get_settings($vendor_id);

		if ($settings && !empty($settings['favicon_path'])) {
			// Delete file from backend uploads
			$file_path = FCPATH . $settings['favicon_path'];
			if (file_exists($file_path)) {
				unlink($file_path);
			}

			// Delete file from frontend uploads
			$frontend_path = dirname(FCPATH) . '/frontend/' . $settings['favicon_path'];
			if (file_exists($frontend_path)) {
				unlink($frontend_path);
			}

			// Also check old paths for backward compatibility
			$old_path1 = str_replace('uploads/vendors_logos/', 'uploads/vendors/', $file_path);
			$old_path2 = str_replace('uploads/', 'assets/', $file_path);
			$old_frontend_path1 = str_replace('uploads/vendors_logos/', 'uploads/vendors/', $frontend_path);
			$old_frontend_path2 = str_replace('uploads/', 'assets/', $frontend_path);

			if (file_exists($old_path1)) {
				unlink($old_path1);
			}
			if (file_exists($old_path2)) {
				unlink($old_path2);
			}
			if (file_exists($old_frontend_path1)) {
				unlink($old_frontend_path1);
			}
			if (file_exists($old_frontend_path2)) {
				unlink($old_frontend_path2);
			}

			// Remove from database
			$this->Vendor_site_settings_model->update_settings($vendor_id, array('favicon_path' => NULL));
		}

		$this->session->set_flashdata('success', 'Favicon deleted successfully!');
		redirect(base_url($this->current_vendor['domain'] . '/site-settings'));
	}

	/**
	 * Delete banner
	 *
	 * @return	void
	 */
	public function delete_banner()
	{
		$vendor_id = $this->current_vendor['id'];

		// Get current settings
		$settings = $this->Vendor_site_settings_model->get_settings($vendor_id);

		if ($settings && !empty($settings['banner_image'])) {
			// Delete file from backend uploads
			$file_path = FCPATH . $settings['banner_image'];
			if (file_exists($file_path)) {
				unlink($file_path);
			}

			// Delete file from frontend uploads
			$frontend_path = dirname(FCPATH) . '/frontend/' . $settings['banner_image'];
			if (file_exists($frontend_path)) {
				unlink($frontend_path);
			}

			// Also check old paths for backward compatibility
			$old_path1 = str_replace('uploads/vendors_logos/', 'uploads/vendors/', $file_path);
			$old_path2 = str_replace('uploads/', 'assets/', $file_path);
			$old_frontend_path1 = str_replace('uploads/vendors_logos/', 'uploads/vendors/', $frontend_path);
			$old_frontend_path2 = str_replace('uploads/', 'assets/', $frontend_path);

			if (file_exists($old_path1)) {
				unlink($old_path1);
			}
			if (file_exists($old_path2)) {
				unlink($old_path2);
			}
			if (file_exists($old_frontend_path1)) {
				unlink($old_frontend_path1);
			}
			if (file_exists($old_frontend_path2)) {
				unlink($old_frontend_path2);
			}

			// Remove from database
			$this->Vendor_site_settings_model->update_settings($vendor_id, array('banner_image' => NULL));
		}

		$this->session->set_flashdata('success', 'Legacy banner deleted successfully!');
		redirect(base_url($this->current_vendor['domain'] . '/site-settings'));
	}

	/**
	 * Handle logo upload
	 *
	 * @return	string|boolean
	 */
	private function _handle_logo_upload()
	{
		log_message('debug', 'Logo upload: Checking $_FILES - ' . (isset($_FILES['logo']) ? 'logo field exists' : 'logo field missing'));
		if (isset($_FILES['logo'])) {
			log_message('debug', 'Logo upload: $_FILES[logo] = ' . json_encode($_FILES['logo']));
		}

		if (!isset($_FILES['logo']) || empty($_FILES['logo']['name'])) {
			log_message('error', 'Logo upload: No file uploaded or file field empty');
			return FALSE;
		}

		// Use shared upload path accessible by both frontend and backend
		$upload_path = FCPATH . 'uploads/vendors_logos/logos/';

		$config['upload_path'] = $upload_path;
		$config['allowed_types'] = 'gif|jpg|jpeg|png|svg';
		$config['max_size'] = 2048; // 2MB
		// Remov dimension restrictions fn CSSedor logos - they can be scaled i
		$config['file_name'] = 'vendor_' . $this->current_vendor['id'] . '_' . time();

		log_message('debug', 'Logo upload config: ' . json_encode($config));

		log_message('debug', 'Logo upload path: ' . $upload_path);

		// Create directory if it doesn't exist
		if (!is_dir($upload_path)) {
			if (!mkdir($upload_path, 0755, TRUE)) {
				log_message('error', 'Logo upload: Failed to create directory: ' . $upload_path);
				$this->session->set_flashdata('logo_error', 'Failed to create upload directory');
				return FALSE;
			}
		}

		$this->upload->initialize($config);

		if ($this->upload->do_upload('logo')) {
			$upload_data = $this->upload->data();
			$source_path = $upload_path . $upload_data['file_name'];

			// Copy to frontend uploads for direct access
			$frontend_upload_path = dirname(FCPATH) . '/frontend/uploads/vendors_logos/logos/';
			if (!is_dir($frontend_upload_path)) {
				if (!mkdir($frontend_upload_path, 0755, TRUE)) {
					log_message('error', 'Logo upload: Failed to create frontend directory: ' . $frontend_upload_path);
				}
			}

			$frontend_dest = $frontend_upload_path . $upload_data['file_name'];
			if (copy($source_path, $frontend_dest)) {
				log_message('debug', 'Logo successfully copied to frontend: ' . $frontend_dest);
			} else {
				log_message('error', 'Logo upload: Failed to copy to frontend: ' . $source_path . ' -> ' . $frontend_dest);
			}

			// Return path accessible from both frontend and backend
			$file_path = 'uploads/vendors_logos/logos/' . $upload_data['file_name'];
			log_message('debug', 'Logo uploaded successfully: ' . $file_path);
			return $file_path;
		} else {
			$error = $this->upload->display_errors();
			log_message('error', 'Logo upload failed: ' . $error);
			$this->session->set_flashdata('logo_error', $error);
			return FALSE;
		}
	}

	/**
	 * Handle favicon upload
	 *
	 * @return	string|boolean
	 */
	private function _handle_favicon_upload()
	{
		if (!isset($_FILES['favicon']) || empty($_FILES['favicon']['name'])) {
			log_message('error', 'Favicon upload: No file uploaded or file field empty');
			return FALSE;
		}

		// Use absolute path for better reliability
		$upload_path = FCPATH . 'uploads/vendors_logos/favicons/';

		$config['upload_path'] = $upload_path;
		$config['allowed_types'] = 'ico|png|gif|jpg|jpeg';
		$config['max_size'] = 512; // 512KB
		// Removed dimension restrictions for favicons - they can be scaled in browsers
		$config['file_name'] = 'vendor_' . $this->current_vendor['id'] . '_favicon_' . time();

		log_message('debug', 'Favicon upload path: ' . $upload_path);

		// Create directory if it doesn't exist
		if (!is_dir($upload_path)) {
			if (!mkdir($upload_path, 0755, TRUE)) {
				log_message('error', 'Favicon upload: Failed to create directory: ' . $upload_path);
				$this->session->set_flashdata('favicon_error', 'Failed to create upload directory');
				return FALSE;
			}
		}

		$this->upload->initialize($config);

		if ($this->upload->do_upload('favicon')) {
			$upload_data = $this->upload->data();
			$source_path = $upload_path . $upload_data['file_name'];

			// Copy to frontend uploads for direct access
			$frontend_upload_path = dirname(FCPATH) . '/frontend/uploads/vendors_logos/favicons/';
			if (!is_dir($frontend_upload_path)) {
				if (!mkdir($frontend_upload_path, 0755, TRUE)) {
					log_message('error', 'Favicon upload: Failed to create frontend directory: ' . $frontend_upload_path);
				}
			}

			$frontend_dest = $frontend_upload_path . $upload_data['file_name'];
			if (copy($source_path, $frontend_dest)) {
				log_message('debug', 'Favicon successfully copied to frontend: ' . $frontend_dest);
			} else {
				log_message('error', 'Favicon upload: Failed to copy to frontend: ' . $source_path . ' -> ' . $frontend_dest);
			}

			// Return path accessible from both frontend and backend
			$file_path = 'uploads/vendors_logos/favicons/' . $upload_data['file_name'];
			log_message('debug', 'Favicon uploaded successfully: ' . $file_path);
			return $file_path;
		} else {
			$error = $this->upload->display_errors();
			log_message('error', 'Favicon upload failed: ' . $error);
			$this->session->set_flashdata('favicon_error', $error);
			return FALSE;
		}
	}

	/**
	 * Add new banner via AJAX
	 *
	 * @return	void
	 */
	public function add_banner_ajax()
	{
		// Check if it's an AJAX request
		if (!$this->input->is_ajax_request()) {
			show_error('Direct access not allowed.', 403);
		}
		
		$vendor_id = $this->current_vendor['id'];
		
		// Handle banner upload - check if multiple files are being uploaded
		if (isset($_FILES['banner_images']) && isset($_FILES['banner_images']['name']) && !empty($_FILES['banner_images']['name'][0])) {
			// Multiple banner upload
			$banner_paths = $this->_handle_multiple_banner_uploads();
			if (!$banner_paths || !is_array($banner_paths) || empty($banner_paths)) {
				header('Content-Type: application/json');
				echo json_encode(array(
					'status' => 'error',
					'message' => $this->session->flashdata('banner_error') ?: 'Banner upload failed'
				));
				return;
			}
			
			// Process each uploaded banner
			$results = array();
			$successful_uploads = 0;
			foreach ($banner_paths as $banner_path) {
				// Prepare banner data
				$banner_data = array(
					'vendor_id' => $vendor_id,
					'banner_image' => $banner_path,
					'alt_text' => $this->input->post('alt_text'),
					'caption' => $this->input->post('caption'),
					'is_active' => $this->input->post('is_active') ? 1 : 0,
					'sort_order' => (int)$this->input->post('sort_order')
				);
				
				// Load banners model and save
				$this->load->model('Banners_model');
				$result = $this->Banners_model->add_banner($banner_data);
				if ($result) {
					$successful_uploads++;
				}
				$results[] = $result;
			}
			
			// Check if any banners were saved successfully
			if ($successful_uploads > 0) {
				header('Content-Type: application/json');
				echo json_encode(array(
					'status' => 'success',
					'message' => $successful_uploads . ' banner(s) added successfully',
					'count' => $successful_uploads
				));
			} else {
				header('Content-Type: application/json');
				echo json_encode(array(
					'status' => 'error',
					'message' => 'Failed to save banner(s) to database'
				));
			}
			return;
		} else {
			// Single banner upload
			$banner_path = $this->_handle_single_banner_upload();
			
			if (!$banner_path) {
				header('Content-Type: application/json');
				echo json_encode(array(
					'status' => 'error',
					'message' => $this->session->flashdata('banner_error') ?: 'Banner upload failed'
				));
				return;
			}
			
			// Prepare banner data
			$banner_data = array(
				'vendor_id' => $vendor_id,
				'banner_image' => $banner_path,
				'alt_text' => $this->input->post('alt_text'),
				'caption' => $this->input->post('caption'),
				'is_active' => $this->input->post('is_active') ? 1 : 0,
				'sort_order' => (int)$this->input->post('sort_order')
			);
			
			// Load banners model and save
			$this->load->model('Banners_model');
			$result = $this->Banners_model->add_banner($banner_data);
			
			if ($result) {
				header('Content-Type: application/json');
				echo json_encode(array(
					'status' => 'success',
					'message' => 'Banner added successfully',
					'banner_id' => $result
				));
			} else {
				header('Content-Type: application/json');
				echo json_encode(array(
					'status' => 'error',
					'message' => 'Failed to save banner to database'
				));
			}
		}
	}

	/**
	 * Delete banner via AJAX
	 *
	 * @param	int	$banner_id
	 * @return	void
	 */
	public function delete_banner_ajax($banner_id = null)
	{
		// Check if it's an AJAX request
		if (!$this->input->is_ajax_request()) {
			show_error('Direct access not allowed.', 403);
		}
		
		if (!$banner_id) {
			header('Content-Type: application/json');
			echo json_encode(array(
				'status' => 'error',
				'message' => 'Banner ID is required'
			));
			return;
		}
		
		$vendor_id = $this->current_vendor['id'];
		
		// Load banners model and get banner
		$this->load->model('Banners_model');
		$banner = $this->Banners_model->get_banner_by_id($banner_id);
		
		// Verify that the banner belongs to the current vendor
		if (!$banner || $banner['vendor_id'] != $vendor_id) {
			header('Content-Type: application/json');
			echo json_encode(array(
				'status' => 'error',
				'message' => 'Banner not found or does not belong to this vendor'
			));
			return;
		}
		
		// Delete the banner record
		$result = $this->Banners_model->delete_banner($banner_id);
		
		if ($result) {
			// Also delete the physical file
			if (!empty($banner['banner_image'])) {
				$file_path = FCPATH . $banner['banner_image'];
				if (file_exists($file_path)) {
					unlink($file_path);
				}
				// Also delete from frontend
				$frontend_path = dirname(FCPATH) . '/frontend/' . $banner['banner_image'];
				if (file_exists($frontend_path)) {
					unlink($frontend_path);
				}
			}
			
			header('Content-Type: application/json');
			echo json_encode(array(
				'status' => 'success',
				'message' => 'Banner deleted successfully'
			));
		} else {
			header('Content-Type: application/json');
			echo json_encode(array(
				'status' => 'error',
				'message' => 'Failed to delete banner from database'
			));
		}
	}

	/**
	 * Handle banner upload (single file)
	 *
	 * @return	string|boolean
	 */
	private function _handle_single_banner_upload()
	{
		log_message('debug', 'Banner upload: Checking $_FILES - ' . (isset($_FILES['banner_image']) ? 'banner_image field exists' : 'banner_image field missing'));
		if (isset($_FILES['banner_image'])) {
			log_message('debug', 'Banner upload: $_FILES[banner_image] = ' . json_encode($_FILES['banner_image']));
		}

		if (!isset($_FILES['banner_image']) || empty($_FILES['banner_image']['name'])) {
			log_message('error', 'Banner upload: No file uploaded or file field empty');
			return FALSE;
		}

		// Use shared upload path accessible by both frontend and backend
		$upload_path = FCPATH . 'uploads/vendors_logos/banners/';

		$config['upload_path'] = $upload_path;
		$config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
		$config['max_size'] = 5120; // 5MB
		$config['file_name'] = 'vendor_' . $this->current_vendor['id'] . '_banner_' . time();

		log_message('debug', 'Banner upload config: ' . json_encode($config));

		log_message('debug', 'Banner upload path: ' . $upload_path);

		// Create directory if it doesn't exist
		if (!is_dir($upload_path)) {
			if (!mkdir($upload_path, 0755, TRUE)) {
				log_message('error', 'Banner upload: Failed to create directory: ' . $upload_path);
				$this->session->set_flashdata('banner_error', 'Failed to create upload directory');
				return FALSE;
			}
		}

		$this->upload->initialize($config);

		if ($this->upload->do_upload('banner_image')) {
			$upload_data = $this->upload->data();
			$source_path = $upload_path . $upload_data['file_name'];

			// Copy to frontend uploads for direct access
			$frontend_upload_path = dirname(FCPATH) . '/frontend/uploads/vendors_logos/banners/';
			if (!is_dir($frontend_upload_path)) {
				if (!mkdir($frontend_upload_path, 0755, TRUE)) {
					log_message('error', 'Banner upload: Failed to create frontend directory: ' . $frontend_upload_path);
				}
			}

			$frontend_dest = $frontend_upload_path . $upload_data['file_name'];
			if (copy($source_path, $frontend_dest)) {
				log_message('debug', 'Banner successfully copied to frontend: ' . $frontend_dest);
			} else {
				log_message('error', 'Banner upload: Failed to copy to frontend: ' . $source_path . ' -> ' . $frontend_dest);
			}

			// Return path accessible from both frontend and backend
			$file_path = 'uploads/vendors_logos/banners/' . $upload_data['file_name'];
			log_message('debug', 'Banner uploaded successfully: ' . $file_path);
			return $file_path;
		} else {
			$error = $this->upload->display_errors();
			log_message('error', 'Banner upload failed: ' . $error);
			$this->session->set_flashdata('banner_error', $error);
			return FALSE;
		}
	}
	
	/**
	 * Handle multiple banner uploads
	 *
	 * @return	array|boolean Array of file paths on success, FALSE on failure
	 */
	private function _handle_multiple_banner_uploads()
	{
		// Check if multiple files are being uploaded
		if (!isset($_FILES['banner_images']) || !isset($_FILES['banner_images']['name']) || empty($_FILES['banner_images']['name'][0])) {
			log_message('error', 'Multiple banner upload: No files uploaded');
			return FALSE;
		}

		$uploaded_files = array();
		$upload_path = FCPATH . 'uploads/vendors_logos/banners/';

		// Create directory if it doesn't exist
		if (!is_dir($upload_path)) {
			if (!mkdir($upload_path, 0755, TRUE)) {
				log_message('error', 'Multiple banner upload: Failed to create directory: ' . $upload_path);
				$this->session->set_flashdata('banner_error', 'Failed to create upload directory');
				return FALSE;
			}
		}

		// Process each file
		$file_count = count($_FILES['banner_images']['name']);
		for ($i = 0; $i < $file_count; $i++) {
			// Set temporary array for single file processing
			$_FILES['temp_banner'] = array(
				'name' => $_FILES['banner_images']['name'][$i],
				'type' => $_FILES['banner_images']['type'][$i],
				'tmp_name' => $_FILES['banner_images']['tmp_name'][$i],
				'error' => $_FILES['banner_images']['error'][$i],
				'size' => $_FILES['banner_images']['size'][$i]
			);

			$config['upload_path'] = $upload_path;
			$config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
			$config['max_size'] = 5120; // 5MB
			$config['file_name'] = 'vendor_' . $this->current_vendor['id'] . '_banner_' . time() . '_' . $i;

			$this->upload->initialize($config);

			if ($this->upload->do_upload('temp_banner')) {
				$upload_data = $this->upload->data();
				$source_path = $upload_path . $upload_data['file_name'];

				// Copy to frontend uploads for direct access
				$frontend_upload_path = dirname(FCPATH) . '/frontend/uploads/vendors_logos/banners/';
				if (!is_dir($frontend_upload_path)) {
					if (!mkdir($frontend_upload_path, 0755, TRUE)) {
						log_message('error', 'Multiple banner upload: Failed to create frontend directory: ' . $frontend_upload_path);
					}
				}

				$frontend_dest = $frontend_upload_path . $upload_data['file_name'];
				if (copy($source_path, $frontend_dest)) {
					log_message('debug', 'Banner successfully copied to frontend: ' . $frontend_dest);
				} else {
					log_message('error', 'Multiple banner upload: Failed to copy to frontend: ' . $source_path . ' -> ' . $frontend_dest);
				}

				// Add path to results array
				$file_path = 'uploads/vendors_logos/banners/' . $upload_data['file_name'];
				$uploaded_files[] = $file_path;
				log_message('debug', 'Multiple banner uploaded successfully: ' . $file_path);
			} else {
				$error = $this->upload->display_errors();
				log_message('error', 'Multiple banner upload failed for file ' . $i . ': ' . $error);
				// Rollback previously uploaded files
				foreach ($uploaded_files as $file_path) {
					$file_to_delete = FCPATH . $file_path;
					if (file_exists($file_to_delete)) {
						unlink($file_to_delete);
					}
					
					// Also delete from frontend
					$frontend_file = dirname(FCPATH) . '/frontend/' . $file_path;
					if (file_exists($frontend_file)) {
						unlink($frontend_file);
					}
				}
				return FALSE;
			}
		}

		return $uploaded_files;
	}
	
	/**
	 * Update banner via AJAX
	 *
	 * @return	void
	 */
	public function update_banner_ajax()
	{
		// Check if it's an AJAX request
		if (!$this->input->is_ajax_request()) {
			show_error('Direct access not allowed.', 403);
		}
		
		$vendor_id = $this->current_vendor['id'];
		$banner_id = $this->input->post('banner_id');
		
		if (!$banner_id) {
			header('Content-Type: application/json');
			echo json_encode(array(
				'status' => 'error',
				'message' => 'Banner ID is required'
			));
			return;
		}
		
		// Load the banner to verify ownership
		$this->load->model('Banners_model');
		$existing_banner = $this->Banners_model->get_banner_by_id($banner_id);
		
		if (!$existing_banner || $existing_banner['vendor_id'] != $vendor_id) {
			header('Content-Type: application/json');
			echo json_encode(array(
				'status' => 'error',
				'message' => 'Banner not found or does not belong to this vendor'
			));
			return;
		}
		
		// Prepare banner data
		$banner_data = array(
			'alt_text' => $this->input->post('alt_text'),
			'caption' => $this->input->post('caption'),
			'is_active' => $this->input->post('is_active') ? 1 : 0,
			'sort_order' => (int)$this->input->post('sort_order')
		);
		
		// Handle file upload if provided
		if (isset($_FILES['banner_images']) && isset($_FILES['banner_images']['name']) && !empty($_FILES['banner_images']['name'][0])) {
			// Multiple banner upload - get the first file
			$banner_paths = $this->_handle_multiple_banner_uploads();
			if ($banner_paths && is_array($banner_paths) && !empty($banner_paths)) {
				$banner_data['banner_image'] = $banner_paths[0]; // Use first image
				
				// Sync new banner image to frontend directory
				$this->_sync_banner_to_frontend($banner_data['banner_image']);
				
				// Delete old image file
				if (!empty($existing_banner['banner_image'])) {
					$old_file_path = FCPATH . $existing_banner['banner_image'];
					if (file_exists($old_file_path)) {
						unlink($old_file_path);
					}
					
					// Also delete from frontend
					$frontend_path = dirname(FCPATH) . '/frontend/' . $existing_banner['banner_image'];
					if (file_exists($frontend_path)) {
						unlink($frontend_path);
					}
				}
			} else {
				header('Content-Type: application/json');
				echo json_encode(array(
					'status' => 'error',
					'message' => $this->session->flashdata('banner_error') ?: 'Banner update failed'
				));
				return;
			}
		} else {
			// If no new image is provided, preserve the existing image
			$banner_data['banner_image'] = $existing_banner['banner_image'];
		}
		
		// Update the banner record
		$result = $this->Banners_model->update_banner($banner_id, $banner_data);
		
		if ($result) {
			// If banner image was updated, ensure it's also in frontend directory
			if (isset($banner_data['banner_image']) && !empty($banner_data['banner_image'])) {
				$this->_sync_banner_to_frontend($banner_data['banner_image']);
			}
			
			// If only metadata was updated, ensure existing banner image is in frontend directory
			if (!isset($banner_data['banner_image']) || empty($banner_data['banner_image'])) {
				$this->_sync_banner_to_frontend($existing_banner['banner_image']);
			}
			
			header('Content-Type: application/json');
			echo json_encode(array(
				'status' => 'success',
				'message' => 'Banner updated successfully'
			));
		} else {
			header('Content-Type: application/json');
			echo json_encode(array(
				'status' => 'error',
				'message' => 'Failed to update banner in database'
			));
		}
	}
	
	/**
	 * Get banner data via AJAX
	 *
	 * @param	int	$banner_id
	 * @return	void
	 */
	public function get_banner_data($banner_id = null)
	{
		// Check if it's an AJAX request
		if (!$this->input->is_ajax_request()) {
			show_error('Direct access not allowed.', 403);
		}
		
		if (!$banner_id) {
			header('Content-Type: application/json');
			echo json_encode(array(
				'status' => 'error',
				'message' => 'Banner ID is required'
			));
			return;
		}
		
		$vendor_id = $this->current_vendor['id'];
		
		// Load banners model and get banner
		$this->load->model('Banners_model');
		$banner = $this->Banners_model->get_banner_by_id($banner_id);
		
		// Verify that the banner belongs to the current vendor
		if (!$banner || $banner['vendor_id'] != $vendor_id) {
			header('Content-Type: application/json');
			echo json_encode(array(
				'status' => 'error',
				'message' => 'Banner not found or does not belong to this vendor'
			));
			return;
		}
		
		header('Content-Type: application/json');
		echo json_encode(array(
			'status' => 'success',
			'data' => array(
				'banner_image' => $banner['banner_image'],
				'alt_text' => $banner['alt_text'],
				'caption' => $banner['caption'],
				'sort_order' => $banner['sort_order'],
				'is_active' => $banner['is_active']
			)
		));
	}
	
	/**
	 * Preview site settings (AJAX)
	 *
	 * @return	void
	 */
	public function preview()
	{
		$vendor_id = $this->current_vendor['id'];
		$settings = $this->Vendor_site_settings_model->get_settings($vendor_id);
		
		header('Content-Type: application/json');
		echo json_encode($settings);
	}
	
	/**
	 * Utility function to sync banner images to frontend directory
	 *
	 * @param string $banner_path Path to the banner image
	 * @return bool True if successful, false otherwise
	 */
	private function _sync_banner_to_frontend($banner_path)
	{
		if (empty($banner_path)) {
			return FALSE;
		}
		
		// Source path in main uploads
		$source_path = FCPATH . $banner_path;
		
		// Destination path in frontend uploads
		$frontend_path = dirname(FCPATH) . '/frontend/' . $banner_path;
		
		// Ensure frontend directory exists
		$frontend_dir = dirname($frontend_path);
		if (!is_dir($frontend_dir)) {
			if (!mkdir($frontend_dir, 0755, TRUE)) {
				log_message('error', 'Failed to create frontend directory: ' . $frontend_dir);
				return FALSE;
			}
		}
		
		// Copy file to frontend if it doesn't exist or is newer
		if (file_exists($source_path)) {
			if (!file_exists($frontend_path) || filemtime($source_path) > filemtime($frontend_path)) {
				if (copy($source_path, $frontend_path)) {
					log_message('debug', 'Banner copied to frontend: ' . $banner_path);
					return TRUE;
				} else {
					log_message('error', 'Failed to copy banner to frontend: ' . $source_path . ' -> ' . $frontend_path);
					return FALSE;
				}
			}
		}
		
		return FALSE;
	}
	
	/**
	 * Utility function to sync all vendor banners to frontend directory
	 *
	 * @param int $vendor_id ID of the vendor
	 * @return int Number of banners synced
	 */
	private function _sync_all_vendor_banners_to_frontend($vendor_id)
	{
		$this->load->model('Banners_model');
		$banners = $this->Banners_model->get_banners_by_vendor($vendor_id);
		
		$synced_count = 0;
		foreach ($banners as $banner) {
			if ($this->_sync_banner_to_frontend($banner['banner_image'])) {
				$synced_count++;
			}
		}
		
		return $synced_count;
	}
	
	/**
	 * Manual sync endpoint to sync all banners to frontend directory
	 *
	 * @return void
	 */
	public function sync_banners_to_frontend()
	{
		$vendor_id = $this->current_vendor['id'];
		$synced_count = $this->_sync_all_vendor_banners_to_frontend($vendor_id);
		
		$this->session->set_flashdata('success', $synced_count . ' banner(s) synced to frontend directory successfully!');
		redirect(base_url($this->current_vendor['domain'] . '/site-settings'));
	}

	/**
	 * Get vendor features for image management
	 *
	 * @return array Array of vendor features
	 */
	private function _get_vendor_features()
	{
		// Ensure image column exists
		$this->_ensure_vendor_features_image_column();
		
		$this->db->select('id, feature_id, feature_slug, feature_name, image, is_enabled');
		$this->db->from('vendor_features');
		$this->db->where('is_enabled', 1);
		$this->db->order_by('feature_name', 'ASC');
		$query = $this->db->get();
		
		return $query->result_array();
	}

	/**
	 * Ensure vendor_features table has image column
	 *
	 * @return void
	 */
	private function _ensure_vendor_features_image_column()
	{
		try {
			$check_image = $this->db->query("SHOW COLUMNS FROM vendor_features LIKE 'image'");
			if (!$check_image || $check_image->num_rows() == 0)
			{
				$this->db->query("ALTER TABLE vendor_features ADD COLUMN `image` VARCHAR(255) NULL DEFAULT NULL AFTER `feature_name`");
			}
		} catch (Exception $e) {
			log_message('debug', 'Could not ensure vendor_features image column: ' . $e->getMessage());
		}
	}

	/**
	 * Upload feature image
	 *
	 * @return void
	 */
	public function upload_feature_image()
	{
		$feature_id = $this->input->post('feature_id');
		
		if (empty($feature_id)) {
			$response = array('success' => false, 'message' => 'Feature ID is required');
			echo json_encode($response);
			return;
		}

		// Ensure image column exists
		$this->_ensure_vendor_features_image_column();

		// Get feature info
		$this->db->where('id', $feature_id);
		$feature = $this->db->get('vendor_features')->row_array();

		if (!$feature) {
			$response = array('success' => false, 'message' => 'Feature not found');
			echo json_encode($response);
			return;
		}

		// Upload configuration
		$upload_path = './uploads/features/';
		if (!is_dir($upload_path)) {
			mkdir($upload_path, 0755, true);
		}

		$config['upload_path'] = $upload_path;
		$config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
		$config['max_size'] = 2048; // 2MB
		$config['file_name'] = 'feature_' . $feature_id . '_' . time();
		$config['overwrite'] = false;

		$this->upload->initialize($config);

		if ($this->upload->do_upload('feature_image')) {
			$upload_data = $this->upload->data();
			$image_path = 'features/' . $upload_data['file_name'];

			// Delete old image if exists
			if (!empty($feature['image'])) {
				$old_image_path = './uploads/' . $feature['image'];
				if (file_exists($old_image_path)) {
					unlink($old_image_path);
				}
			}

			// Update database
			$this->db->where('id', $feature_id);
			$this->db->update('vendor_features', array('image' => $image_path, 'updated_at' => date('Y-m-d H:i:s')));

			// Sync to frontend
			$this->_sync_feature_image_to_frontend($image_path);

			$response = array(
				'success' => true,
				'message' => 'Image uploaded successfully',
				'image_url' => base_url('uploads/' . $image_path)
			);
		} else {
			$response = array(
				'success' => false,
				'message' => $this->upload->display_errors('', '')
			);
		}

		echo json_encode($response);
	}

	/**
	 * Delete feature image
	 *
	 * @return void
	 */
	public function delete_feature_image()
	{
		$feature_id = $this->input->post('feature_id');
		
		if (empty($feature_id)) {
			$response = array('success' => false, 'message' => 'Feature ID is required');
			echo json_encode($response);
			return;
		}

		// Get feature info
		$this->db->where('id', $feature_id);
		$feature = $this->db->get('vendor_features')->row_array();

		if (!$feature) {
			$response = array('success' => false, 'message' => 'Feature not found');
			echo json_encode($response);
			return;
		}

		// Delete image file if exists
		if (!empty($feature['image'])) {
			$image_path = './uploads/' . $feature['image'];
			if (file_exists($image_path)) {
				unlink($image_path);
			}

			// Delete from frontend too
			$frontend_path = './frontend/uploads/' . $feature['image'];
			if (file_exists($frontend_path)) {
				unlink($frontend_path);
			}
		}

		// Update database
		$this->db->where('id', $feature_id);
		$this->db->update('vendor_features', array('image' => null, 'updated_at' => date('Y-m-d H:i:s')));

		$response = array('success' => true, 'message' => 'Image deleted successfully');
		echo json_encode($response);
	}

	/**
	 * Sync feature image to frontend directory
	 *
	 * @param string $image_path Image path relative to uploads folder
	 * @return bool TRUE on success, FALSE on failure
	 */
	private function _sync_feature_image_to_frontend($image_path)
	{
		$source_path = './uploads/' . $image_path;
		$target_path = './frontend/uploads/' . $image_path;

		if (!file_exists($source_path)) {
			return false;
		}

		// Create directory if it doesn't exist
		$target_dir = dirname($target_path);
		if (!is_dir($target_dir)) {
			mkdir($target_dir, 0755, true);
		}

		// Copy file
		return copy($source_path, $target_path);
	}
}

