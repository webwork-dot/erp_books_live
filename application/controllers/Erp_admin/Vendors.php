<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ERP Admin Vendors Controller
 *
 * Manages vendors in the ERP system
 *
 * @package		ERP
 * @subpackage	Controllers
 * @category	Controllers
 * @author		ERP Team
 */

// Load base controller
require_once(APPPATH . 'controllers/Erp_admin/Erp_base.php');

class Vendors extends Erp_base
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
		$this->load->model('Erp_feature_model');
		$this->load->model('Erp_user_model');
		$this->load->model('Vendor_sync_model');
		$this->load->library('form_validation');
		$this->load->library('Tenant');
	}
	
	/**
	 * List all vendors
	 *
	 * @return	void
	 */
	public function index()
	{
		// Check permission
		if (!$this->hasPermission('vendors', 'read'))
		{
			show_error('You do not have permission to access this page.', 403);
		}
		
		// Get filters
		$filters = array();
		if ($this->input->get('status'))
		{
			$filters['status'] = $this->input->get('status');
		}
		if ($this->input->get('search'))
		{
			$filters['search'] = $this->input->get('search');
		}
		
		// Pagination
		$per_page = 10;
		$page = (int)$this->input->get('page');
		if ($page < 1) $page = 1;
		$offset = ($page - 1) * $per_page;
		
		// Get total count for pagination
		$total_vendors = $this->Erp_client_model->getTotalClients($filters);
		
		// Get vendors with pagination
		$data['vendors'] = $this->Erp_client_model->getAllClients($filters, $per_page, $offset);
		$data['total_vendors'] = $total_vendors;
		$data['per_page'] = $per_page;
		$data['current_page'] = $page;
		$data['total_pages'] = ceil($total_vendors / $per_page);
		
		// Get all features for modal
		$data['all_features'] = $this->Erp_feature_model->getAllFeatures();
		
		$data['title'] = 'Manage Vendors';
		$data['current_user'] = $this->current_user;
		$data['filters'] = $filters;
		$data['breadcrumb'] = array(
			array('label' => 'Vendors', 'active' => true)
		);
		
		// Load content view
		$data['content'] = $this->load->view('erp_admin/vendors/index', $data, TRUE);
		
		// Load main layout
		$this->load->view('erp_admin/layouts/index_template', $data);
	}
	
	/**
	 * Add new vendor
	 *
	 * @return	void
	 */
	public function add()
	{
		// --------------------------------------------------
		// 0. Permission
		// --------------------------------------------------
		if (!$this->hasPermission('vendors', 'create')) {
			show_error('You do not have permission to access this page.', 403);
		}

		// --------------------------------------------------
		// 1. Validation
		// --------------------------------------------------
		$this->form_validation->set_rules('name', 'Vendor Name', 'required|trim');
		$this->form_validation->set_rules('domain', 'Domain', 'required|trim|is_unique[erp_clients.domain]|callback_validate_domain');
		$this->form_validation->set_rules('username', 'Username', 'required|trim|callback_check_username_unique[0]|min_length[3]|max_length[100]');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
		$this->form_validation->set_rules('status', 'Status', 'in_list[active,suspended]');

		if ($this->form_validation->run() === FALSE) {
			$data['title'] = 'Add New Vendor';
			$data['content'] = $this->load->view('erp_admin/vendors/add', $data, TRUE);
			$this->load->view('erp_admin/layouts/index_template', $data);
			return;
		}

		// --------------------------------------------------
		// 2. RAW PASSWORD (IMPORTANT)
		// --------------------------------------------------
		$raw_password = $this->input->post('password'); // for MySQL user
		$hashed_password = sha1($raw_password);         // for app login

		// --------------------------------------------------
		// 3. CREATE VENDOR (MASTER DB)
		// --------------------------------------------------
		$status = $this->input->post('status');
		if (empty($status) || !in_array($status, ['active', 'suspended'])) {
			$status = 'active'; // Default to active
		}
		
		$vendor_data = [
			'name'     => $this->input->post('name'),
			'domain'   => $this->input->post('domain'),
			'username' => $this->input->post('username'),
			'password' => $hashed_password,
			'status'   => $status
		];

		$vendor_id = $this->Erp_client_model->createClient($vendor_data);

		if (!$vendor_id) {
			$this->session->set_flashdata('error', 'Vendor creation failed.');
			redirect('erp-admin/vendors/add');
		}

		$vendor = $this->Erp_client_model->getClientById($vendor_id);

		// --------------------------------------------------
		// 4. CREATE VENDOR DATABASE
		// --------------------------------------------------
		$this->load->config('tenant');
		$this->load->model('Tenant_model');

		$result = $this->tenant->createClientDatabase($vendor['database_name']);
		if (!$result) {
			// Rollback: Delete the vendor from master database
			$this->Erp_client_model->deleteClient($vendor_id);
			$this->session->set_flashdata('error', 'Failed to create vendor database. Vendor creation rolled back.');
			redirect('erp-admin/vendors/add');
		}

		$template_path = $this->config->item('database_template_path', 'tenant');
		if (empty($template_path)) {
			$template_path = APPPATH . '../erp_master.sql';
		}

		$result = $this->Tenant_model->initializeClientDatabase(
			$vendor['database_name'],
			$template_path
		);
		if (!$result) {
			// Rollback: Delete the vendor from master database
			$this->Erp_client_model->deleteClient($vendor_id);
			$this->session->set_flashdata('error', 'Failed to initialize vendor database. Vendor creation rolled back.');
			redirect('erp-admin/vendors/add');
		}

		// --------------------------------------------------
		// 5. CONNECT TO VENDOR DB
		// --------------------------------------------------
		$vendor_db = $this->load->database([
			'hostname' => $this->db->hostname,
			'username' => $this->db->username,
			'password' => $this->db->password,
			'database' => $vendor['database_name'],
			'dbdriver' => 'mysqli',
			'pconnect' => FALSE,
			'db_debug' => FALSE,
			'char_set' => 'utf8',
			'dbcollat' => 'utf8_general_ci'
		], TRUE);

		// Check if vendor database connection was successful
		if (!$vendor_db || $vendor_db->conn_id === FALSE) {
			// Rollback: Delete the vendor from master database
			$this->Erp_client_model->deleteClient($vendor_id);
			$this->session->set_flashdata('error', 'Failed to connect to vendor database. Vendor creation rolled back.');
			redirect('erp-admin/vendors/add');
		}

		// --------------------------------------------------
		// 6. COPY ROLE INTO VENDOR DB
		// --------------------------------------------------
		$master_role_id = $this->Erp_user_model->getOrCreateVendorRole();

		$master_role = $this->db
			->where('id', $master_role_id)
			->get('erp_user_roles')
			->row_array();

		if (!$master_role) {
			// Rollback: Delete the vendor from master database
			$this->Erp_client_model->deleteClient($vendor_id);
			$this->session->set_flashdata('error', 'Failed to get master role for vendor. Vendor creation rolled back.');
			redirect('erp-admin/vendors/add');
		}

		// Check if erp_user_roles table exists in vendor database
		$table_exists = $vendor_db->query("SHOW TABLES LIKE 'erp_user_roles'");
		if (!$table_exists || $table_exists->num_rows() == 0) {
			// Rollback: Delete the vendor from master database
			$this->Erp_client_model->deleteClient($vendor_id);
			$this->session->set_flashdata('error', 'erp_user_roles table does not exist in vendor database. Vendor creation rolled back.');
			redirect('erp-admin/vendors/add');
		}

		$vendor_role = $vendor_db
			->where('name', $master_role['name'])
			->get('erp_user_roles')
			->row_array();

		if (!$vendor_role) {
			$result = $vendor_db->insert('erp_user_roles', [
				'name'        => $master_role['name'],
				'description' => $master_role['description'],
				'permissions' => $master_role['permissions'],
				'created_at'  => date('Y-m-d H:i:s'),
				'updated_at'  => date('Y-m-d H:i:s')
			]);
			if (!$result) {
				// Rollback: Delete the vendor from master database
				$this->Erp_client_model->deleteClient($vendor_id);
				$this->session->set_flashdata('error', 'Failed to insert role into vendor database. Vendor creation rolled back.');
				redirect('erp-admin/vendors/add');
			}
			$vendor_role_id = $vendor_db->insert_id();
		} else {
			$vendor_role_id = $vendor_role['id'];
		}

		// --------------------------------------------------
		// 7. CREATE APPLICATION USER (VENDOR DB)
		// --------------------------------------------------
		$vendor_db->insert('erp_users', [
			'username'   => $vendor['username'],
			'email'      => $vendor['username'] . '@' . $vendor['domain'] . '.local',
			'password'   => $hashed_password,
			'role_id'    => $vendor_role_id,
			'status'     => ($vendor['status'] === 'active') ? 1 : 0,
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s')
		]);

		// --------------------------------------------------
		// 8. CREATE MYSQL DATABASE USER (LIMITED ACCESS)
		// --------------------------------------------------
		$db_user = 'vd_' . preg_replace('/[^a-zA-Z0-9_]/', '', $vendor['username']);
		$db_name = $vendor['database_name'];
		$db_host = 'localhost'; // safer than %

		$admin_db = $this->load->database('default', TRUE);

		$admin_db->query("
			CREATE USER IF NOT EXISTS '$db_user'@'$db_host'
			IDENTIFIED BY '$raw_password'
		");

		$admin_db->query("
			GRANT SELECT, INSERT, UPDATE, DELETE
			ON `$db_name`.*
			TO '$db_user'@'$db_host'
		");

		$admin_db->query("FLUSH PRIVILEGES");

		// --------------------------------------------------
		// 9. STORE DB USERNAME (OPTIONAL, SAFE)
		// --------------------------------------------------
		$this->Erp_client_model->updateClient($vendor_id, [
			'db_username' => $db_user
		]);

		// --------------------------------------------------
		// 10. SYNC VENDOR DATA TO VENDOR DATABASE
		// --------------------------------------------------
		// Sync all vendor data from master database to vendor database
		try {
			$sync_result = $this->Vendor_sync_model->syncVendorData($vendor_id);
			if (!$sync_result) {
				log_message('warning', 'Vendor data sync failed for vendor ID: ' . $vendor_id . '. Vendor was created but sync to vendor database failed.');
			}
		} catch (Exception $e) {
			log_message('error', 'Exception during vendor data sync: ' . $e->getMessage());
		}

		// --------------------------------------------------
		// DONE
		// --------------------------------------------------
		$this->session->set_flashdata('success', 'Vendor created successfully.');
		redirect('erp-admin/vendors');
	}



	
	/**
	 * Edit vendor
	 *
	 * @param	int	$vendor_id	Vendor ID
	 * @return	void
	 */
	public function edit($vendor_id)
	{
		// Check permission
		if (!$this->hasPermission('vendors', 'update'))
		{
			show_error('You do not have permission to access this page.', 403);
		}
		
		// Get vendor
		$vendor = $this->Erp_client_model->getClientById($vendor_id);
		if (!$vendor)
		{
			show_404();
		}
		
		// Set validation rules
		$this->form_validation->set_rules('name', 'Vendor Name', 'required|trim');
		$this->form_validation->set_rules('domain', 'Domain', 'required|trim|callback_check_domain_unique[' . $vendor_id . ']|callback_validate_domain');
		$this->form_validation->set_rules('username', 'Username', 'required|trim|callback_check_username_unique[' . $vendor_id . ']|min_length[3]|max_length[100]');
		$this->form_validation->set_rules('password', 'Password', 'min_length[6]');
		$this->form_validation->set_rules('sidebar_color', 'Sidebar Color', 'trim|callback_validate_color');
		$this->form_validation->set_rules('payment_gateway', 'Payment Gateway', 'trim|in_list[razorpay,ccavenue,]');
		
		// Conditional validation for payment gateway fields
		$payment_gateway = $this->input->post('payment_gateway');
		if ($payment_gateway == 'razorpay') {
			$this->form_validation->set_rules('razorpay_key_id', 'Razorpay Key ID', 'required|trim');
			$this->form_validation->set_rules('razorpay_key_secret', 'Razorpay Key Secret', 'required|trim');
		} elseif ($payment_gateway == 'ccavenue') {
			$this->form_validation->set_rules('ccavenue_merchant_id', 'CCAvenue Merchant ID', 'required|trim');
			$this->form_validation->set_rules('ccavenue_access_code', 'CCAvenue Access Code', 'required|trim');
			$this->form_validation->set_rules('ccavenue_working_key', 'CCAvenue Working Key', 'required|trim');
		}
		
		// Email (Zepto Mail) validation
		$this->form_validation->set_rules('zepto_mail_api_key', 'Zepto Mail API Key', 'trim');
		$this->form_validation->set_rules('zepto_mail_from_email', 'Zepto Mail From Email', 'trim|valid_email');
		$this->form_validation->set_rules('zepto_mail_from_name', 'Zepto Mail From Name', 'trim');
		
		// Firebase validation
		$this->form_validation->set_rules('firebase_api_key', 'Firebase API Key', 'trim');
		$this->form_validation->set_rules('firebase_auth_domain', 'Firebase Auth Domain', 'trim');
		$this->form_validation->set_rules('firebase_project_id', 'Firebase Project ID', 'trim');
		$this->form_validation->set_rules('firebase_storage_bucket', 'Firebase Storage Bucket', 'trim');
		$this->form_validation->set_rules('firebase_messaging_sender_id', 'Firebase Messaging Sender ID', 'trim');
		$this->form_validation->set_rules('firebase_app_id', 'Firebase App ID', 'trim');
		
		if ($this->form_validation->run() == FALSE)
		{
			// Get all features (only main categories for the checkbox list)
			$all_features = $this->Erp_feature_model->getAllFeatures();
			$main_features = array();
			foreach ($all_features as $feature)
			{
				if (empty($feature['parent_id']))
				{
					$main_features[] = $feature;
				}
			}
			$data['all_features'] = $main_features;
			
			// Get vendor's assigned features
			$data['vendor_features'] = $this->Erp_client_model->getClientFeatures($vendor_id);
			
			// Get vendor's assigned subcategories
			$data['vendor_subcategories'] = $this->Erp_client_model->getClientSubcategories($vendor_id);
			
			// Build a map of feature_id => array of enabled subcategory_ids
			$subcategory_map = array();
			foreach ($data['vendor_subcategories'] as $subcat)
			{
				$feature_id = (int)$subcat['feature_id'];
				if (!isset($subcategory_map[$feature_id]))
				{
					$subcategory_map[$feature_id] = array();
				}
				$subcategory_map[$feature_id][] = (int)$subcat['subcategory_id'];
			}
			$data['subcategory_map'] = $subcategory_map;
			
			// Get all subcategories grouped by parent feature
			$data['all_subcategories'] = array();
			foreach ($main_features as $main_feature)
			{
				$subcategories = $this->Erp_feature_model->getSubcategoriesByParent($main_feature['id']);
				if (!empty($subcategories))
				{
					$data['all_subcategories'][$main_feature['id']] = $subcategories;
				}
			}
			
			$data['vendor'] = $vendor;
			$data['title'] = 'Edit Vendor';
			$data['current_user'] = $this->current_user;
			
			// Load content view
			$data['content'] = $this->load->view('erp_admin/vendors/edit', $data, TRUE);
			
			// Load main layout
			$this->load->view('erp_admin/layouts/index_template', $data);
		}
		else
		{
			// Update vendor
			$vendor_data = array(
				'name' => $this->input->post('name'),
				'domain' => $this->input->post('domain'),
				'username' => $this->input->post('username'),
				// Preserve existing status when editing (no status field in edit form)
				'status' => $vendor['status'],
				'sidebar_color' => $this->input->post('sidebar_color') ? $this->input->post('sidebar_color') : 'sidebarbg1',
				'payment_gateway' => $this->input->post('payment_gateway') ? $this->input->post('payment_gateway') : ''
			);
			
			// Handle payment gateway fields based on selection
			$payment_gateway = $this->input->post('payment_gateway');
			if ($payment_gateway == 'razorpay') {
				$vendor_data['razorpay_key_id'] = $this->input->post('razorpay_key_id');
				$vendor_data['razorpay_key_secret'] = $this->input->post('razorpay_key_secret');
				// Clear CCAvenue fields
				$vendor_data['ccavenue_merchant_id'] = NULL;
				$vendor_data['ccavenue_access_code'] = NULL;
				$vendor_data['ccavenue_working_key'] = NULL;
			} elseif ($payment_gateway == 'ccavenue') {
				$vendor_data['ccavenue_merchant_id'] = $this->input->post('ccavenue_merchant_id');
				$vendor_data['ccavenue_access_code'] = $this->input->post('ccavenue_access_code');
				$vendor_data['ccavenue_working_key'] = $this->input->post('ccavenue_working_key');
				// Clear Razorpay fields
				$vendor_data['razorpay_key_id'] = NULL;
				$vendor_data['razorpay_key_secret'] = NULL;
			} else {
				// No gateway selected, clear all payment fields
				$vendor_data['razorpay_key_id'] = NULL;
				$vendor_data['razorpay_key_secret'] = NULL;
				$vendor_data['ccavenue_merchant_id'] = NULL;
				$vendor_data['ccavenue_access_code'] = NULL;
				$vendor_data['ccavenue_working_key'] = NULL;
			}
			
			// Handle Email (Zepto Mail) fields
			$vendor_data['zepto_mail_api_key'] = $this->input->post('zepto_mail_api_key') ? $this->input->post('zepto_mail_api_key') : NULL;
			$vendor_data['zepto_mail_from_email'] = $this->input->post('zepto_mail_from_email') ? $this->input->post('zepto_mail_from_email') : NULL;
			$vendor_data['zepto_mail_from_name'] = $this->input->post('zepto_mail_from_name') ? $this->input->post('zepto_mail_from_name') : NULL;
			
			// Handle Firebase fields
			$vendor_data['firebase_api_key'] = $this->input->post('firebase_api_key') ? $this->input->post('firebase_api_key') : NULL;
			$vendor_data['firebase_auth_domain'] = $this->input->post('firebase_auth_domain') ? $this->input->post('firebase_auth_domain') : NULL;
			$vendor_data['firebase_project_id'] = $this->input->post('firebase_project_id') ? $this->input->post('firebase_project_id') : NULL;
			$vendor_data['firebase_storage_bucket'] = $this->input->post('firebase_storage_bucket') ? $this->input->post('firebase_storage_bucket') : NULL;
			$vendor_data['firebase_messaging_sender_id'] = $this->input->post('firebase_messaging_sender_id') ? $this->input->post('firebase_messaging_sender_id') : NULL;
			$vendor_data['firebase_app_id'] = $this->input->post('firebase_app_id') ? $this->input->post('firebase_app_id') : NULL;
			
			// Update password only if provided
			$password = $this->input->post('password');
			if (!empty($password))
			{
				$vendor_data['password'] = sha1($password);
			}
			
			// Handle logo upload (EXACT pattern like notebooks, domain-based folder)
			if (!empty($_FILES['logo']['name']))
			{
				$this->config->load('upload');
				$uploadCfg = $this->config->item('vendor_logo_upload');
				// Derive vendor folder from domain (EXACT same logic as earlier)
				$raw_domain = strtolower(trim($this->input->post('domain')));
				$raw_domain = preg_replace('#^https?://#', '', $raw_domain);
				$raw_domain = preg_replace('#^www\.#', '', $raw_domain);
				$raw_domain = rtrim($raw_domain, '/');

				$segments = preg_split('#/#', $raw_domain, -1, PREG_SPLIT_NO_EMPTY);
				$vendor_folder = !empty($segments) ? end($segments) : 'default';
				$vendor_folder = preg_replace('/[^a-z0-9\.\-]/', '', $vendor_folder);

				if (empty($vendor_folder)) {
					$vendor_folder = 'default';
				}


				// SAME path structure as notebook/uniform
				$upload_path = rtrim($uploadCfg['root_path'], '/')
							. '/'
							. $vendor_folder . '/'
							. trim($uploadCfg['relative_dir'], '/')
							. '/';

				if (!is_dir($upload_path)) {
					mkdir($upload_path, 0775, true);
				}


				// check writability ONLY if directory does not exist
				if (!is_dir($upload_path)) {
					mkdir($upload_path, 0775, true);
				}

				$config = [
					'upload_path'   => $upload_path,
					'allowed_types' => implode('|', $uploadCfg['allowed_types']),
					'max_size'      => $uploadCfg['max_size'],
					'file_name'     => 'vendor_' . $vendor_id . '_' . time(),
					'overwrite'     => true
				];

				$this->load->library('upload');
				$this->upload->initialize($config);

				if (!$this->upload->do_upload('logo')) {
					$this->session->set_flashdata(
						'error',
						'Logo upload failed: ' . strip_tags($this->upload->display_errors())
					);
					redirect('erp-admin/vendors/edit/' . $vendor_id);
				}

				$upload_data = $this->upload->data();

				// ✅ STORE RELATIVE PATH ONLY (same as notebooks)
				$vendor_data['logo'] =
					trim($uploadCfg['relative_dir'], '/') . '/'
					. $upload_data['file_name'];

				// Delete old logo
				if (!empty($vendor['logo']) && file_exists(FCPATH . $vendor['logo'])) {
					@unlink(FCPATH . $vendor['logo']);
				}
			}


			// Handle logo removal
			elseif ($this->input->post('remove_logo') == '1')
			{
				// Delete old logo if exists
				if (!empty($vendor['logo']) && file_exists(FCPATH . $vendor['logo']))
				{
					@unlink(FCPATH . $vendor['logo']);
				}
				$vendor_data['logo'] = NULL;
			}
			
			// Update vendor
			$update_result = $this->Erp_client_model->updateClient($vendor_id, $vendor_data);
			
			// Update vendor user in erp_users table (non-critical, continue even if fails)
			$vendor_role_id = $this->Erp_user_model->getOrCreateVendorRole();
			
			if ($vendor_role_id)
			{
				// Check if username has changed
				$username_changed = ($vendor['username'] !== $vendor_data['username']);

				// If username changed, check if new username already exists
				if ($username_changed)
				{
					$user_with_new_username = $this->Erp_user_model->getUserByUsername($vendor_data['username']);
					if ($user_with_new_username)
					{
						$this->session->set_flashdata('error', 'Username "' . $vendor_data['username'] . '" is already taken.');
						redirect('erp-admin/vendors/edit/' . $vendor_id);
					}
				}

				// Get existing user by old username
				$existing_user = $this->Erp_user_model->getUserByUsername($vendor['username']);
				
				if ($existing_user)
				{
					// Update existing user
					$user_data = array(
						'username' => $vendor_data['username'],
						'email' => $vendor_data['username'] . '@' . $vendor_data['domain'] . '.local',
						'role_id' => $vendor_role_id,
						'status' => ($vendor_data['status'] == 'active') ? 1 : 0
					);
					
					// Update password if provided (already hashed)
					if (!empty($password))
					{
						$user_data['password'] = $vendor_data['password']; // Already SHA1 hashed
					}
					
					// Direct update to avoid double hashing
					if (!empty($password))
					{
						$user_data['password'] = $vendor_data['password'];
						$this->db->where('id', $existing_user['id']);
						$this->db->update('erp_users', $user_data);
					}
					else
					{
						$this->Erp_user_model->updateUser($existing_user['id'], $user_data);
					}
				}
				else
				{
					// This shouldn't happen in edit mode, but handle it just in case
					log_message('error', 'Vendor user not found during edit operation for vendor: ' . $vendor['username']);
				}
			}
			
			// Handle feature assignment (always process, even if main update had no changes)
			$features = $this->input->post('features');
			if (!is_array($features))
			{
				$features = array();
			}
			
			$subcategories = $this->input->post('subcategories');
			if (!is_array($subcategories))
			{
				$subcategories = array();
			}
			
			// Normalize feature keys to integers (POST keys are strings, but we need integers for comparison)
			$normalized_features = array();
			foreach ($features as $key => $value) {
				$normalized_features[(int)$key] = $value;
			}
			$features = $normalized_features;
			
			// Normalize subcategory assignments
			$normalized_subcategories = array();
			foreach ($subcategories as $feature_id => $subcat_list)
			{
				if (is_array($subcat_list))
				{
					$normalized_subcategories[(int)$feature_id] = array_map('intval', $subcat_list);
				}
			}
			$subcategories = $normalized_subcategories;
			
			// Get all available features (only main categories)
			$all_features = $this->Erp_feature_model->getAllFeatures();
			$main_features = array();
			foreach ($all_features as $feature)
			{
				if (empty($feature['parent_id']))
				{
					$main_features[] = $feature;
				}
			}
			
			$features_updated = FALSE;
			foreach ($main_features as $feature)
			{
				$feature_id = (int)$feature['id'];
				$enabled = isset($features[$feature_id]) && $features[$feature_id] == '1' ? TRUE : FALSE;
				
				$assign_result = $this->Erp_client_model->assignFeature($vendor_id, $feature_id, $enabled);
				if ($assign_result)
				{
					$features_updated = TRUE;
				}
				
				// Handle subcategories for this main feature
				if ($enabled)
				{
					// Get all subcategories for this feature
					$all_subcategories = $this->Erp_feature_model->getSubcategoriesByParent($feature_id);
					
					// Get submitted subcategories for this feature (default to empty array if not set)
					$submitted_subcat_ids = isset($subcategories[$feature_id]) && is_array($subcategories[$feature_id]) ? $subcategories[$feature_id] : array();
					
					// Get currently enabled subcategories for this vendor and feature
					$current_subcategories = $this->Erp_client_model->getClientSubcategories($vendor_id, $feature_id);
					$current_subcat_ids = array();
					foreach ($current_subcategories as $current_subcat)
					{
						$current_subcat_ids[] = (int)$current_subcat['subcategory_id'];
					}
					
					foreach ($all_subcategories as $subcat)
					{
						$subcat_id = (int)$subcat['id'];
						// Subcategory is enabled only if it's in the submitted array
						$subcat_enabled = in_array($subcat_id, $submitted_subcat_ids);
						
						if ($subcat_enabled)
						{
							// Enable/assign the subcategory
							if ($this->Erp_client_model->assignSubcategory($vendor_id, $feature_id, $subcat_id, TRUE))
							{
								$features_updated = TRUE;
							}
						}
						else
						{
							// Remove the subcategory if it was previously enabled
							if (in_array($subcat_id, $current_subcat_ids))
							{
								if ($this->Erp_client_model->removeSubcategory($vendor_id, $feature_id, $subcat_id))
								{
									$features_updated = TRUE;
								}
							}
						}
					}
				}
				elseif (!$enabled)
				{
					// If main feature is disabled, remove all subcategory assignments
					$all_subcategories = $this->Erp_feature_model->getSubcategoriesByParent($feature_id);
					foreach ($all_subcategories as $subcat)
					{
						$this->Erp_client_model->removeSubcategory($vendor_id, $feature_id, $subcat['id']);
					}
				}
			}
			
			// Sync all features to vendor database after updates
			if ($features_updated)
			{
				$this->load->model('Feature_sync_model');
				try
				{
					$this->Feature_sync_model->syncVendorFeatures($vendor_id);
				}
				catch (Exception $e)
				{
					log_message('error', 'Failed to sync features after vendor update: ' . $e->getMessage());
					// Don't block update if sync fails
				}
			}
			
			// Sync vendor data to vendor database after update
			try {
				$sync_result = $this->Vendor_sync_model->syncVendorData($vendor_id);
				if (!$sync_result) {
					log_message('warning', 'Vendor data sync failed for vendor ID: ' . $vendor_id . '. Vendor was updated but sync to vendor database failed.');
				}
			} catch (Exception $e) {
				log_message('error', 'Exception during vendor data sync after update: ' . $e->getMessage());
			}
			
			// Consider update successful if:
			// 1. Main update succeeded, OR
			// 2. Features were updated (which means something changed)
			if ($update_result || $features_updated)
			{
				$this->session->set_flashdata('success', 'Vendor updated successfully.');
				redirect('erp-admin/vendors');
			}
			else
			{
				// Check if there was a database error
				$db_error = $this->db->error();
				if (!empty($db_error['message']) && isset($db_error['code']) && $db_error['code'] != 0)
				{
					$this->session->set_flashdata('error', 'Failed to update vendor: ' . $db_error['message']);
					redirect('erp-admin/vendors/edit/' . $vendor_id);
				}
				else
				{
					// If we get here, it means update returned FALSE but no error
					// This can happen if data hasn't changed, but features were updated
					// So we should still show success
					$this->session->set_flashdata('success', 'Vendor updated successfully.');
					redirect('erp-admin/vendors');
				}
			}
		}
	}
	
	/**
	 * Delete vendor
	 *
	 * @param	int	$vendor_id	Vendor ID
	 * @return	void
	 */
	public function delete($vendor_id)
	{
		// Check permission
		if (!$this->hasPermission('vendors', 'delete'))
		{
			show_error('You do not have permission to access this page.', 403);
		}
		
		if ($this->Erp_client_model->deleteClient($vendor_id))
		{
			$this->session->set_flashdata('success', 'Vendor deleted successfully.');
		}
		else
		{
			$this->session->set_flashdata('error', 'Failed to delete vendor.');
		}
		
		redirect('erp-admin/vendors');
	}
	
	/**
	 * Get vendor features (AJAX)
	 *
	 * @param	int	$vendor_id	Vendor ID
	 * @return	void
	 */
	public function get_features($vendor_id)
	{
		header('Content-Type: application/json');
		
		// Check permission
		if (!$this->hasPermission('vendors', 'read'))
		{
			echo json_encode(array('status' => 'error', 'message' => 'Permission denied'));
			return;
		}
		
		// Get vendor
		$vendor = $this->Erp_client_model->getClientById($vendor_id);
		if (!$vendor)
		{
			echo json_encode(array('status' => 'error', 'message' => 'Vendor not found'));
			return;
		}
		
		// Get all features and vendor's assigned features
		$all_features = $this->Erp_feature_model->getAllFeatures();
		$vendor_features = $this->Erp_client_model->getClientFeatures($vendor_id);
		
		// Get vendor's assigned subcategories
		$vendor_subcategories = $this->Erp_client_model->getClientSubcategories($vendor_id);
		
		// Create a map of enabled features (ensure IDs are integers for consistent comparison)
		$enabled_features = array();
		foreach ($vendor_features as $vf)
		{
			if (isset($vf['is_enabled']) && $vf['is_enabled'] == 1)
			{
				$enabled_features[] = (int)$vf['id'];
			}
		}
		
		// Create a map of enabled subcategories by feature
		$enabled_subcategories = array();
		foreach ($vendor_subcategories as $vs)
		{
			if (isset($vs['is_enabled']) && $vs['is_enabled'] == 1)
			{
				$feature_id = (int)$vs['feature_id'];
				if (!isset($enabled_subcategories[$feature_id]))
				{
					$enabled_subcategories[$feature_id] = array();
				}
				$enabled_subcategories[$feature_id][] = (int)$vs['subcategory_id'];
			}
		}
		
		// Get subcategories for each main feature
		$features_with_subcategories = array();
		foreach ($all_features as $feature)
		{
			$feature['subcategories'] = array();
			if (empty($feature['parent_id']))
			{
				// This is a main category, get its subcategories
				$feature['subcategories'] = $this->Erp_feature_model->getSubcategoriesByParent($feature['id']);
			}
			$features_with_subcategories[] = $feature;
		}
		
		echo json_encode(array(
			'status' => 'success',
			'vendor' => $vendor,
			'features' => $features_with_subcategories,
			'enabled_features' => $enabled_features,
			'enabled_subcategories' => $enabled_subcategories
		));
	}
	
	/**
	 * Update vendor features (AJAX)
	 *
	 * @param	int	$vendor_id	Vendor ID
	 * @return	void
	 */
	public function update_features($vendor_id)
	{
		header('Content-Type: application/json');
		
		// Check permission
		if (!$this->hasPermission('vendors', 'update'))
		{
			echo json_encode(array('status' => 'error', 'message' => 'Permission denied'));
			return;
		}
		
		// Get vendor
		$vendor = $this->Erp_client_model->getClientById($vendor_id);
		if (!$vendor)
		{
			echo json_encode(array('status' => 'error', 'message' => 'Vendor not found'));
			return;
		}
		
		$features = $this->input->post('features');
		if (!is_array($features))
		{
			$features = array();
		}
		
		$subcategories = $this->input->post('subcategories');
		if (!is_array($subcategories))
		{
			$subcategories = array();
		}
		
		// Normalize feature keys to integers (POST keys are strings, but we need integers for comparison)
		$normalized_features = array();
		foreach ($features as $key => $value) {
			$normalized_features[(int)$key] = $value;
		}
		$features = $normalized_features;
		
		// Normalize subcategory assignments
		$normalized_subcategories = array();
		foreach ($subcategories as $feature_id => $subcat_list)
		{
			if (is_array($subcat_list))
			{
				$normalized_subcategories[(int)$feature_id] = array_map('intval', $subcat_list);
			}
		}
		$subcategories = $normalized_subcategories;
		
		// Get all available features
		$all_features = $this->Erp_feature_model->getAllFeatures();
		
		$success_count = 0;
		$error_count = 0;
		
		// Update main features
		foreach ($all_features as $feature)
		{
			$feature_id = (int)$feature['id'];
			// Only process main categories (no parent)
			if (empty($feature['parent_id']))
			{
			$enabled = isset($features[$feature_id]) && $features[$feature_id] == '1' ? TRUE : FALSE;
			
			if ($this->Erp_client_model->assignFeature($vendor_id, $feature_id, $enabled))
			{
				$success_count++;
			}
			else
			{
				$error_count++;
				}
				
				// Handle subcategories for this main feature
				if ($enabled)
				{
					// Get all subcategories for this feature
					$all_subcategories = $this->Erp_feature_model->getSubcategoriesByParent($feature_id);
					
					// Get submitted subcategories for this feature (default to empty array if not set)
					$submitted_subcat_ids = isset($subcategories[$feature_id]) && is_array($subcategories[$feature_id]) ? $subcategories[$feature_id] : array();
					
					// Get currently enabled subcategories for this vendor and feature
					$current_subcategories = $this->Erp_client_model->getClientSubcategories($vendor_id, $feature_id);
					$current_subcat_ids = array();
					foreach ($current_subcategories as $current_subcat)
					{
						$current_subcat_ids[] = (int)$current_subcat['subcategory_id'];
					}
					
					foreach ($all_subcategories as $subcat)
					{
						$subcat_id = (int)$subcat['id'];
						// Subcategory is enabled only if it's in the submitted array
						$subcat_enabled = in_array($subcat_id, $submitted_subcat_ids);
						
						if ($subcat_enabled)
						{
							// Enable/assign the subcategory
							if ($this->Erp_client_model->assignSubcategory($vendor_id, $feature_id, $subcat_id, TRUE))
							{
								$success_count++;
							}
						}
						else
						{
							// Remove the subcategory if it was previously enabled
							if (in_array($subcat_id, $current_subcat_ids))
							{
								if ($this->Erp_client_model->removeSubcategory($vendor_id, $feature_id, $subcat_id))
								{
									$success_count++;
								}
							}
						}
					}
				}
				elseif (!$enabled)
				{
					// If main feature is disabled, remove all subcategory assignments
					$all_subcategories = $this->Erp_feature_model->getSubcategoriesByParent($feature_id);
					foreach ($all_subcategories as $subcat)
					{
						$this->Erp_client_model->removeSubcategory($vendor_id, $feature_id, $subcat['id']);
					}
				}
			}
		}
		
		// Sync all features to vendor database after bulk update
		if ($success_count > 0)
		{
			$this->load->model('Feature_sync_model');
			try
			{
				$this->Feature_sync_model->syncVendorFeatures($vendor_id);
			}
			catch (Exception $e)
			{
				log_message('error', 'Failed to sync features after bulk update: ' . $e->getMessage());
				// Don't block update if sync fails
			}
		}
		
		if ($error_count > 0)
		{
			echo json_encode(array(
				'status' => 'error',
				'message' => 'Some features could not be updated. ' . $success_count . ' updated successfully, ' . $error_count . ' failed.'
			));
		}
		else
		{
			echo json_encode(array(
				'status' => 'success',
				'message' => 'Features and subcategories updated successfully.'
			));
		}
	}
	
	/**
	 * Manage vendor features
	 *
	 * @param	int	$vendor_id	Vendor ID
	 * @return	void
	 */
	public function features($vendor_id)
	{
		// Check permission
		if (!$this->hasPermission('vendors', 'update'))
		{
			show_error('You do not have permission to access this page.', 403);
		}
		
		// Get vendor
		$vendor = $this->Erp_client_model->getClientById($vendor_id);
		if (!$vendor)
		{
			show_404();
		}
		
		// Handle feature assignment
		if ($this->input->post('assign_features'))
		{
			$features = $this->input->post('features');
			
			// Ensure features is an array (checkboxes that are unchecked don't send values)
			if (!is_array($features))
			{
				$features = array();
			}
			
			// Get all available features
			$all_features = $this->Erp_feature_model->getAllFeatures();
			
			$success_count = 0;
			$error_count = 0;
			
			foreach ($all_features as $feature)
			{
				$enabled = isset($features[$feature['id']]) && $features[$feature['id']] == '1' ? TRUE : FALSE;
				
				if ($this->Erp_client_model->assignFeature($vendor_id, $feature['id'], $enabled))
				{
					$success_count++;
				}
				else
				{
					$error_count++;
				}
			}
			
			if ($error_count > 0)
			{
				$this->session->set_flashdata('error', 'Some features could not be updated. ' . $success_count . ' updated successfully, ' . $error_count . ' failed.');
			}
			else
			{
				$this->session->set_flashdata('success', 'Features updated successfully.');
			}
			
			redirect('erp-admin/vendors/features/' . $vendor_id);
		}
		
		// Get all features
		$data['all_features'] = $this->Erp_feature_model->getAllFeatures();
		
		// Get vendor features
		$data['vendor_features'] = $this->Erp_client_model->getClientFeatures($vendor_id);
		
		$data['vendor'] = $vendor;
		$data['title'] = 'Manage Features for ' . $vendor['name'];
		$data['current_user'] = $this->current_user;
		
		// Load content view
		$data['content'] = $this->load->view('erp_admin/vendors/features', $data, TRUE);
		
		// Load main layout
		$this->load->view('erp_admin/layouts/index_template', $data);
	}
	
	/**
	 * Debug vendor database creation (temporary - remove after testing)
	 *
	 * @param	int	$vendor_id	Vendor ID
	 * @return	void
	 */
	public function debug_database($vendor_id)
	{
		// Debug method - skip permission check but still require authentication
		// Authentication is already checked in Erp_base constructor
		
		$vendor = $this->Erp_client_model->getClientById($vendor_id);
		
		if (!$vendor)
		{
			echo "Vendor not found!";
			return;
		}
		
		echo "<h2>Debug Vendor Database Creation</h2>";
		echo "<h3>Vendor Info:</h3>";
		echo "<pre>";
		print_r($vendor);
		echo "</pre>";
		
		echo "<h3>Config Check:</h3>";
		$this->load->config('tenant');
		$auto_create = $this->config->item('auto_create_database', 'tenant');
		$template_path = $this->config->item('database_template_path', 'tenant');
		echo "auto_create_database: " . ($auto_create ? 'TRUE' : 'FALSE') . "<br>";
		echo "database_template_path: " . ($template_path ? $template_path : 'NOT SET') . "<br>";
		if ($template_path) {
			echo "Template file exists: " . (file_exists($template_path) ? 'YES' : 'NO') . "<br>";
			if (!file_exists($template_path)) {
				echo "<span style='color: red;'>ERROR: Template file not found at: " . htmlspecialchars($template_path) . "</span><br>";
			}
		} else {
			echo "<span style='color: red;'>ERROR: Template path is not configured!</span><br>";
		}
		
		echo "<h3>Database Name:</h3>";
		echo "database_name: " . (isset($vendor['database_name']) ? $vendor['database_name'] : 'NOT SET') . "<br>";
		
		if (!empty($vendor['database_name']))
		{
			echo "<h3>Testing Database Creation:</h3>";
			$this->load->library('Tenant');
			
			// Test connection
			$hostname = $this->db->hostname;
			$username = $this->db->username;
			$password = $this->db->password;
			
			echo "Host: " . $hostname . "<br>";
			echo "User: " . $username . "<br>";
			
			$connection = @new mysqli($hostname, $username, $password);
			if ($connection->connect_error)
			{
				echo "Connection Error: " . $connection->connect_error . "<br>";
			}
			else
			{
				echo "MySQL Connection: OK<br>";
				
				// Check if database exists
				$result = $connection->query("SHOW DATABASES LIKE '" . $connection->real_escape_string($vendor['database_name']) . "'");
				if ($result && $result->num_rows > 0)
				{
					echo "Database EXISTS: " . $vendor['database_name'] . "<br>";
				}
				else
				{
					echo "Database DOES NOT EXIST: " . $vendor['database_name'] . "<br>";
					
					// Try to create
					echo "<h4>Attempting to create database...</h4>";
					$sql = "CREATE DATABASE IF NOT EXISTS `" . $connection->real_escape_string($vendor['database_name']) . "` CHARACTER SET utf8 COLLATE utf8_general_ci";
					if ($connection->query($sql))
					{
						echo "Database created successfully!<br>";
					}
					else
					{
						echo "Error creating database: " . $connection->error . "<br>";
					}
				}
				$connection->close();
			}
		}
		
		echo "<h3>Recent Log Entries:</h3>";
		$log_path = APPPATH . 'logs/';
		$log_files = glob($log_path . 'log-*.php');
		if (!empty($log_files))
		{
			rsort($log_files);
			$latest_log = $log_files[0];
			$log_content = file_get_contents($latest_log);
			$lines = explode("\n", $log_content);
			$recent_lines = array_slice($lines, -50);
			echo "<pre style='max-height: 400px; overflow: auto;'>";
			echo htmlspecialchars(implode("\n", $recent_lines));
			echo "</pre>";
		}
		
		echo "<hr>";
		echo "<h3>Database Data Check:</h3>";
		if (!empty($vendor['database_name']))
		{
			$connection = @new mysqli($hostname, $username, $password, $vendor['database_name']);
			if (!$connection->connect_error)
			{
				// Check location tables
				$location_tables = array('countries', 'states', 'cities');
				foreach ($location_tables as $table)
				{
					$result = $connection->query("SELECT COUNT(*) as count FROM `" . $connection->real_escape_string($table) . "`");
					if ($result)
					{
						$row = $result->fetch_assoc();
						echo "Table <strong>" . $table . "</strong>: " . $row['count'] . " rows<br>";
					}
				}
				$connection->close();
			}
		}
		
		echo "<hr>";
		echo "<h3>Actions:</h3>";
		
		// Check if database exists
		$db_exists = FALSE;
		if (!empty($vendor['database_name']))
		{
			$connection = @new mysqli($hostname, $username, $password);
			if (!$connection->connect_error)
			{
				$result = $connection->query("SHOW DATABASES LIKE '" . $connection->real_escape_string($vendor['database_name']) . "'");
				$db_exists = ($result && $result->num_rows > 0);
				$connection->close();
			}
		}
		
		if ($db_exists)
		{
			echo "<p><strong>Database exists but may not be initialized.</strong></p>";
			echo "<a href='" . site_url('erp-admin/vendors/create_database/' . $vendor_id) . "' class='btn btn-warning' onclick='return confirm(\"This will initialize the database with the template and sync features. Continue?\");'>Initialize Database Now</a> ";
		}
		else
		{
			echo "<a href='" . site_url('erp-admin/vendors/create_database/' . $vendor_id) . "' class='btn btn-primary' onclick='return confirm(\"Are you sure you want to create and initialize the database for this vendor?\");'>Create & Initialize Database Now</a>";
		}
	}
	
	/**
	 * Manually create database for existing vendor
	 *
	 * @param	int	$vendor_id	Vendor ID
	 * @return	void
	 */
	public function create_database($vendor_id)
	{
		// Debug method - skip permission check but still require authentication
		// Authentication is already checked in Erp_base constructor
		
		$vendor = $this->Erp_client_model->getClientById($vendor_id);
		
		if (!$vendor)
		{
			$this->session->set_flashdata('error', 'Vendor not found.');
			redirect('erp-admin/vendors');
		}
		
		if (empty($vendor['database_name']))
		{
			$this->session->set_flashdata('error', 'Vendor does not have a database_name set.');
			redirect('erp-admin/vendors');
		}
		
		// Check if database already exists
		$hostname = $this->db->hostname;
		$username = $this->db->username;
		$password = $this->db->password;
		
		$connection = @new mysqli($hostname, $username, $password);
		if ($connection->connect_error)
		{
			$this->session->set_flashdata('error', 'Failed to connect to MySQL: ' . $connection->connect_error);
			redirect('erp-admin/vendors');
		}
		
		// Check if database exists
		$result = $connection->query("SHOW DATABASES LIKE '" . $connection->real_escape_string($vendor['database_name']) . "'");
		$db_exists = ($result && $result->num_rows > 0);
		
		// Check if database has tables (to see if it's initialized)
		$is_initialized = FALSE;
		if ($db_exists)
		{
			$connection->select_db($vendor['database_name']);
			$tables_result = $connection->query("SHOW TABLES");
			$is_initialized = ($tables_result && $tables_result->num_rows > 0);
		}
		
		$connection->close();
		
		// Load config explicitly
		$this->load->config('tenant');
		$template_path = $this->config->item('database_template_path', 'tenant');
		
		// If template path is empty, use default
		if (empty($template_path))
		{
			$template_path = APPPATH . '../erp_master.sql';
			log_message('info', 'Template path was empty, using default: ' . $template_path);
		}
		
		// Create database if it doesn't exist
		if (!$db_exists)
		{
			log_message('info', 'Manually creating database for vendor ID: ' . $vendor_id . ', database_name: ' . $vendor['database_name']);
			
			if (!$this->tenant->createClientDatabase($vendor['database_name']))
			{
				log_message('error', 'Failed to create database: ' . $vendor['database_name']);
				$this->session->set_flashdata('error', 'Failed to create database. Check logs for details.');
				redirect('erp-admin/vendors');
			}
			
			log_message('info', 'Database created successfully: ' . $vendor['database_name']);
		}
		else
		{
			if ($is_initialized)
			{
				log_message('info', 'Database already exists and is initialized: ' . $vendor['database_name']);
				// Still sync features in case they were updated
			}
			else
			{
				log_message('info', 'Database exists but is not initialized. Initializing now: ' . $vendor['database_name']);
			}
		}
		
		// Initialize database if it's not initialized
		if (!$is_initialized)
		{
			$this->load->model('Tenant_model');
			
			if (!$this->Tenant_model->initializeClientDatabase($vendor['database_name'], $template_path))
			{
				log_message('error', 'Failed to initialize database: ' . $vendor['database_name']);
				$this->session->set_flashdata('error', 'Database initialization failed. Check logs for details.');
				redirect('erp-admin/vendors');
			}
			
			log_message('info', 'Database initialized successfully: ' . $vendor['database_name']);
		}
		else
		{
			// Database exists and has tables, but check if feature tables exist
			// If not, create them
			$connection = @new mysqli($hostname, $username, $password, $vendor['database_name']);
			if (!$connection->connect_error)
			{
				// Check if vendor_features table exists
				$result = $connection->query("SHOW TABLES LIKE 'vendor_features'");
				$feature_tables_exist = ($result && $result->num_rows > 0);
				
				if (!$feature_tables_exist)
				{
					log_message('info', 'Database initialized but feature tables missing. Creating feature tables now.');
					$this->load->model('Tenant_model');
					
					if ($this->Tenant_model->createFeatureTables($connection))
					{
						log_message('info', 'Feature tables created successfully.');
						
						// Also create feature enforcement
						$this->Tenant_model->createFeatureEnforcement($connection);
					}
					else
					{
						log_message('error', 'Failed to create feature tables.');
					}
				}
				else
				{
					// Feature tables exist, ensure new columns are added
					$this->load->model('Tenant_model');
					if ($this->Tenant_model->ensureVendorFeaturesColumns($connection))
					{
						log_message('info', 'Ensured vendor_features columns are up to date.');
					}
				}
				
				// Always fix foreign key constraints (remove references to erp_clients)
				$this->load->model('Tenant_model');
				if ($this->Tenant_model->removeVendorForeignKeyConstraints($connection))
				{
					log_message('info', 'Fixed foreign key constraints for vendor database.');
				}
				
				$connection->close();
			}
		}
		
		// Sync assigned features to vendor database (always sync, even if already initialized)
		$this->load->model('Feature_sync_model');
		try
		{
			$this->Feature_sync_model->syncVendorFeatures($vendor_id);
			log_message('info', 'Features synced successfully for vendor ID: ' . $vendor_id);
			
			if ($is_initialized)
			{
				$this->session->set_flashdata('success', 'Features synced successfully for vendor: ' . $vendor['name']);
			}
			else
			{
				$this->session->set_flashdata('success', 'Database created, initialized, and features synced successfully for vendor: ' . $vendor['name']);
			}
		}
		catch (Exception $e)
		{
			log_message('error', 'Failed to sync features: ' . $e->getMessage());
			if ($is_initialized)
			{
				$this->session->set_flashdata('warning', 'Feature sync failed. Check logs for details.');
			}
			else
			{
				$this->session->set_flashdata('warning', 'Database created and initialized, but feature sync failed. Check logs for details.');
			}
		}
		
		redirect('erp-admin/vendors');
	}
	
	/**
	 * Fix foreign key constraints for existing vendor database
	 *
	 * @param	int	$vendor_id	Vendor ID
	 * @return	void
	 */
	public function fix_foreign_keys($vendor_id)
	{
		// Check permission
		if (!$this->hasPermission('vendors', 'update'))
		{
			show_error('You do not have permission to access this page.', 403);
		}
		
		$vendor = $this->Erp_client_model->getClientById($vendor_id);
		
		if (!$vendor)
		{
			$this->session->set_flashdata('error', 'Vendor not found.');
			redirect('erp-admin/vendors');
		}
		
		if (empty($vendor['database_name']))
		{
			$this->session->set_flashdata('error', 'Vendor does not have a database_name set.');
			redirect('erp-admin/vendors');
		}
		
		// Connect to vendor database
		$hostname = $this->db->hostname;
		$username = $this->db->username;
		$password = $this->db->password;
		
		$connection = @new mysqli($hostname, $username, $password, $vendor['database_name']);
		if ($connection->connect_error)
		{
			$this->session->set_flashdata('error', 'Failed to connect to MySQL: ' . $connection->connect_error);
			redirect('erp-admin/vendors');
		}
		
		// Fix foreign key constraints
		$this->load->model('Tenant_model');
		if ($this->Tenant_model->removeVendorForeignKeyConstraints($connection))
		{
			$this->session->set_flashdata('success', 'Foreign key constraints fixed successfully for vendor: ' . $vendor['name']);
		}
		else
		{
			$this->session->set_flashdata('warning', 'Some foreign key constraints may not have been removed. Check logs for details.');
		}
		
		$connection->close();
		redirect('erp-admin/vendors');
	}
	
	/**
	 * Manually sync features to vendor database
	 *
	 * @param	int	$vendor_id	Vendor ID
	 * @return	void
	 */
	public function sync_features($vendor_id)
	{
		// Check permission
		if (!$this->hasPermission('vendors', 'update'))
		{
			show_error('You do not have permission to access this page.', 403);
		}
		
		$vendor = $this->Erp_client_model->getClientById($vendor_id);
		
		if (!$vendor)
		{
			$this->session->set_flashdata('error', 'Vendor not found.');
			redirect('erp-admin/vendors');
		}
		
		if (empty($vendor['database_name']))
		{
			$this->session->set_flashdata('error', 'Vendor does not have a database_name set.');
			redirect('erp-admin/vendors');
		}
		
		// Sync features
		$this->load->model('Feature_sync_model');
		try
		{
			if ($this->Feature_sync_model->syncVendorFeatures($vendor_id))
			{
				$this->session->set_flashdata('success', 'Features synced successfully to vendor database: ' . $vendor['name']);
			}
			else
			{
				$this->session->set_flashdata('warning', 'Feature sync completed with some errors. Check logs for details.');
			}
		}
		catch (Exception $e)
		{
			log_message('error', 'Failed to sync features: ' . $e->getMessage());
			$this->session->set_flashdata('error', 'Failed to sync features: ' . $e->getMessage());
		}
		
		redirect('erp-admin/vendors');
	}
	
	/**
	 * Validate domain format (callback)
	 *
	 * @param	string	$domain	Domain name
	 * @return	bool	TRUE if valid, FALSE otherwise
	 */
	public function validate_domain($domain)
	{
		// Allow alphanumeric, dots, dashes, and underscores
		if (preg_match('/^[a-zA-Z0-9._-]+$/', $domain))
		{
			return TRUE;
		}
		
		$this->form_validation->set_message('validate_domain', 'The {field} field may only contain letters, numbers, dots, dashes, and underscores.');
		return FALSE;
	}
	
	/**
	 * Check domain uniqueness (callback)
	 *
	 * @param	string	$domain	Domain name
	 * @param	int	$vendor_id	Vendor ID (for edit)
	 * @return	bool	TRUE if unique, FALSE otherwise
	 */
	public function check_domain_unique($domain, $vendor_id)
	{
		$this->db->where('domain', $domain);
		$this->db->where('id !=', $vendor_id);
		$query = $this->db->get('erp_clients');
		
		if ($query->num_rows() > 0)
		{
			$this->form_validation->set_message('check_domain_unique', 'The {field} field must contain a unique value.');
			return FALSE;
		}
		
		return TRUE;
	}
	
	/**
	 * Check username uniqueness (callback)
	 *
	 * @param	string	$username	Username
	 * @param	int	$vendor_id	Vendor ID (for edit)
	 * @return	bool	TRUE if unique, FALSE otherwise
	 */
	public function check_username_unique($username, $vendor_id)
	{
		// Check uniqueness in erp_clients table (exclude current vendor if editing)
		$this->db->where('username', $username);
		if (!empty($vendor_id) && $vendor_id > 0)
		{
		$this->db->where('id !=', $vendor_id);
		}
		$query = $this->db->get('erp_clients');

		if ($query->num_rows() > 0)
		{
			$this->form_validation->set_message('check_username_unique', 'The {field} field must contain a unique value.');
			return FALSE;
		}

		// For editing, we need to check if username has actually changed
		// If editing and username hasn't changed, allow it
		if (!empty($vendor_id) && $vendor_id > 0)
		{
			$current_vendor = $this->db->where('id', $vendor_id)->get('erp_clients')->row();
			if ($current_vendor && $current_vendor->username === $username)
			{
				// Username hasn't changed, so it's valid
				return TRUE;
			}
		}

		// Check uniqueness in erp_users table (only for new usernames)
		$this->db->where('username', $username);
		$query = $this->db->get('erp_users');
		
		if ($query->num_rows() > 0)
		{
			$this->form_validation->set_message('check_username_unique', 'The {field} field must contain a unique value.');
			return FALSE;
		}
		
		return TRUE;
	}
	
	/**
	 * Validate color (callback)
	 * Accepts hex codes (#RRGGBB) or predefined theme names
	 *
	 * @param	string	$color	Color value
	 * @return	bool	TRUE if valid, FALSE otherwise
	 */
	public function validate_color($color)
	{
		// Allow empty (will use default)
		if (empty($color))
		{
			return TRUE;
		}
		
		// Check if it's a valid hex code
		if (preg_match('/^#[0-9A-Fa-f]{6}$/', $color))
		{
			return TRUE;
		}
		
		// Check if it's a predefined theme name (for backward compatibility)
		$valid_themes = array('sidebarbg1', 'sidebarbg2', 'sidebarbg3', 'sidebarbg4', 'sidebarbg5', 'sidebarbg6');
		if (in_array($color, $valid_themes))
		{
			return TRUE;
		}
		
		$this->form_validation->set_message('validate_color', 'The {field} field must be a valid hex color code (e.g., #7539ff) or a predefined theme name.');
		return FALSE;
	}
	
	/**
	 * Toggle vendor status (Active/Suspended)
	 *
	 * @param int $vendor_id
	 * @return void
	 */
	public function toggle_status($vendor_id)
	{
		// Check permission
		if (!$this->hasPermission('vendors', 'update'))
		{
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode([
					'status' => 'error',
					'message' => 'You do not have permission to perform this action.'
				]));
			return;
		}
		
		// Validate vendor ID
		if (empty($vendor_id) || !is_numeric($vendor_id))
		{
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode([
					'status' => 'error',
					'message' => 'Invalid vendor ID.'
				]));
			return;
		}
		
		// Get status from POST
		$status = $this->input->post('status');
		
		// Validate status
		if (!in_array($status, ['active', 'suspended']))
		{
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode([
					'status' => 'error',
					'message' => 'Invalid status. Only active or suspended are allowed.'
				]));
			return;
		}
		
		// Get vendor
		$vendor = $this->Erp_client_model->getClientById($vendor_id);
		
		if (!$vendor)
		{
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode([
					'status' => 'error',
					'message' => 'Vendor not found.'
				]));
			return;
		}
		
		// Update status
		$result = $this->Erp_client_model->updateClient($vendor_id, [
			'status' => $status
		]);
		
		if ($result)
		{
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode([
					'status' => 'success',
					'message' => 'Vendor status updated successfully.',
					'new_status' => $status
				]));
		}
		else
		{
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode([
					'status' => 'error',
					'message' => 'Failed to update vendor status.'
				]));
		}
	}
}
