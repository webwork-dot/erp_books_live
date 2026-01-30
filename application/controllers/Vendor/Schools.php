<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Vendor Schools Controller
 *
 * Handles school management for vendors
 *
 * @package		ERP
 * @subpackage	Controllers
 * @category	Controllers
 * @author		ERP Team
 */

// Load base controller
require_once(APPPATH . 'controllers/Vendor/Vendor_base.php');

class Schools extends Vendor_base
{
	/**
	 * Constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('School_model');
		$this->load->model('Location_model');
		$this->load->model('School_board_model');
		$this->load->model('Branch_model');
		$this->load->library('form_validation');
		$this->load->helper('file');
	}
	
	/**
	 * List all schools
	 *
	 * @return	void
	 */
	public function index()
	{
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
		$total_schools = $this->School_model->getTotalSchoolsByVendor($this->current_vendor['id'], $filters);
		
		// Get schools with pagination
		$schools = $this->School_model->getSchoolsByVendor($this->current_vendor['id'], $filters, $per_page, $offset);
		
		// Convert board IDs to board names for display and fetch school images
		foreach ($schools as &$school)
		{
			if (!empty($school['school_board']))
			{
				// school_board contains comma-separated IDs, convert to names
				$school['school_board_names'] = $this->School_model->getBoardNamesFromIds($school['school_board']);
			}
			else
			{
				$school['school_board_names'] = '';
			}
			
			// Get school image (primary first, then first available)
			$school['thumbnail'] = NULL;
			if (!empty($school['id'])) {
				$this->db->select('image_path');
				$this->db->from('erp_school_images');
				$this->db->where('school_id', $school['id']);
				$this->db->where('is_primary', 1);
				$this->db->limit(1);
				$school_image = $this->db->get()->row_array();
				if (!$school_image) {
					$this->db->select('image_path');
					$this->db->from('erp_school_images');
					$this->db->where('school_id', $school['id']);
					$this->db->order_by('id', 'ASC');
					$this->db->limit(1);
					$school_image = $this->db->get()->row_array();
				}
				$school['thumbnail'] = $school_image ? $school_image['image_path'] : NULL;
			}
		}
		unset($school); // Break reference
		
		$data['schools'] = $schools;
		$data['total_schools'] = $total_schools;
		$data['per_page'] = $per_page;
		$data['current_page'] = $page;
		$data['total_pages'] = ceil($total_schools / $per_page);
		
		$data['title'] = 'Manage Schools';
		$data['current_vendor'] = $this->current_vendor;
		$data['filters'] = $filters;
		$data['vendor_domain'] = $this->getVendorDomainForUrl();
		$data['breadcrumb'] = array(
			array('label' => 'Schools', 'active' => true)
		);
		
		// Load content view
		$data['content'] = $this->load->view('vendor/schools/index', $data, TRUE);
		
		// Load main layout
		$this->load->view('vendor/layouts/index_template', $data);
	}
	
	/**
	 * Add new school
	 *
	 * @return	void
	 */
	public function add()
	{
		// Set validation rules
		$this->form_validation->set_rules('school_name', 'School Name', 'required|trim');
		// Use callback to validate school_board array
		$this->form_validation->set_rules('school_board', 'School Board', 'callback_validate_school_boards');
		$this->form_validation->set_rules('total_strength', 'Total School Strength', 'integer');
		$this->form_validation->set_rules('school_description', 'School Description', 'trim');
		$this->form_validation->set_rules('affiliation_no', 'Affiliation No.', 'trim');
		$this->form_validation->set_rules('address', 'Address', 'required|trim');
		$this->form_validation->set_rules('state_id', 'State', 'required|integer');
		$this->form_validation->set_rules('city_id', 'City', 'required|integer');
		$this->form_validation->set_rules('pincode', 'Pincode', 'required|trim|min_length[6]|max_length[10]');
		$this->form_validation->set_rules('admin_name', 'Admin Name', 'required|trim');
		$this->form_validation->set_rules('admin_phone', 'Admin Phone', 'required|trim');
		$this->form_validation->set_rules('admin_email', 'Admin Email', 'required|valid_email');
		$this->form_validation->set_rules('admin_password', 'Admin Password', 'required|min_length[6]');
		
		if ($this->form_validation->run() == FALSE)
		{
			// Get states and boards for dropdown
			$data['states'] = $this->Location_model->getAllStates();
			$data['cities'] = array(); // Will be populated via AJAX
			$data['boards'] = $this->School_board_model->getBoardsByVendor($this->current_vendor['id']);
			
			// Get parent schools for branch selection
			$data['parent_schools'] = $this->School_model->getSchoolsByVendor($this->current_vendor['id'], array('status' => 'active'), NULL, 0);
			
			$data['title'] = 'Add New School';
			$data['current_vendor'] = $this->current_vendor;
			$data['vendor_domain'] = $this->getVendorDomainForUrl();
			$data['breadcrumb'] = array(
				array('label' => 'Schools', 'url' => $this->current_vendor['domain'] . '/schools'),
				array('label' => 'Add New', 'active' => true)
			);
			
			// Load content view
			$data['content'] = $this->load->view('vendor/schools/add', $data, TRUE);
			
			// Load main layout
			$this->load->view('vendor/layouts/index_template', $data);
		}
		else
		{
			// Check if this is a branch
			$is_branch = $this->input->post('is_branch') == '1';
			
			if ($is_branch)
			{
				// Validate parent school
				$parent_school_id = $this->input->post('parent_school_id');
				if (empty($parent_school_id))
				{
					$this->session->set_flashdata('error', 'Please select a parent school for the branch.');
					$this->load->helper('common');
					$vendor_domain = $this->getVendorDomainForUrl();
					redirect(vendor_url('schools/add', $vendor_domain));
				}
				
				// Create branch
				$branch_data = array(
					'school_id' => (int)$parent_school_id,
					'vendor_id' => $this->current_vendor['id'],
					'branch_name' => $this->input->post('school_name'),
					'address' => $this->input->post('address'),
					'state_id' => $this->input->post('state_id'),
					'city_id' => $this->input->post('city_id'),
					'pincode' => $this->input->post('pincode'),
					'status' => 'active'
				);
				
				$branch_id = $this->Branch_model->createBranch($branch_data);
				
				if ($branch_id)
				{
					$this->session->set_flashdata('success', 'Branch created successfully.');
					$this->load->helper('common');
					$vendor_domain = $this->getVendorDomainForUrl();
					redirect(vendor_url('branches', $vendor_domain));
				}
				else
				{
					$this->session->set_flashdata('error', 'Failed to create branch.');
					$this->load->helper('common');
					$vendor_domain = $this->getVendorDomainForUrl();
					redirect(vendor_url('schools/add', $vendor_domain));
				}
			}
			else
			{
				// Get selected boards
				$selected_boards = $this->input->post('school_board');
				if (empty($selected_boards) || !is_array($selected_boards))
				{
					$this->session->set_flashdata('error', 'Please select at least one board.');
					$this->load->helper('common');
					$vendor_domain = $this->getVendorDomainForUrl();
					redirect(vendor_url('schools/add', $vendor_domain));
				}
				
				// Filter out empty values and ensure we have valid integers
				$selected_boards = array_filter(array_map('intval', $selected_boards));
				
				if (empty($selected_boards))
				{
					$this->session->set_flashdata('error', 'Please select at least one valid board.');
					$this->load->helper('common');
					$vendor_domain = $this->getVendorDomainForUrl();
					redirect(vendor_url('schools/add', $vendor_domain));
				}
				
				// Create school (store comma-separated board IDs)
				$school_data = array(
					'vendor_id' => $this->current_vendor['id'],
					'school_name' => $this->input->post('school_name'),
					'school_board' => implode(',', $selected_boards),
					'total_strength' => $this->input->post('total_strength') ? (int)$this->input->post('total_strength') : NULL,
					'school_description' => $this->input->post('school_description'),
					'affiliation_no' => $this->input->post('affiliation_no'),
					'address' => $this->input->post('address'),
					'state_id' => $this->input->post('state_id'),
					'city_id' => $this->input->post('city_id'),
					'pincode' => $this->input->post('pincode'),
					'admin_name' => $this->input->post('admin_name'),
					'admin_phone' => $this->input->post('admin_phone'),
					'admin_email' => $this->input->post('admin_email'),
					'admin_password' => $this->input->post('admin_password'),
					'status' => 'active'
				);
				
				$school_id = $this->School_model->createSchool($school_data);
				
				if ($school_id)
				{
					// Update school slug
					$school_updated_data = array(
						'slug' => slugify($this->input->post('school_name')) . '-' . $school_id,
					);

					$this->School_model->updateSchool($school_id, $school_updated_data);

					// Save multiple boards
					$this->School_model->saveSchoolBoards($school_id, $selected_boards);
					
					// Handle image uploads
					$this->handleImageUploads($school_id);
					
					$this->session->set_flashdata('success', 'School created successfully.');
					$this->load->helper('common');
					$vendor_domain = $this->getVendorDomainForUrl();
					redirect(vendor_url('schools', $vendor_domain));
				}
				else
				{
					$this->session->set_flashdata('error', 'Failed to create school.');
					$this->load->helper('common');
					$vendor_domain = $this->getVendorDomainForUrl();
					redirect(vendor_url('schools/add', $vendor_domain));
				}
			}
		}
	}
	
	/**
	 * Edit school
	 *
	 * @param	int	$school_id	School ID
	 * @return	void
	 */
	public function edit($school_id)
	{
		// Get school
		$school = $this->School_model->getSchoolById($school_id, $this->current_vendor['id']);
		
		if (!$school)
		{
			show_404();
		}
		
		// Set validation rules
		$this->form_validation->set_rules('school_name', 'School Name', 'required|trim');
		// Note: school_board[] is an array, validation handled manually below
		$this->form_validation->set_rules('total_strength', 'Total School Strength', 'integer');
		$this->form_validation->set_rules('school_description', 'School Description', 'trim');
		$this->form_validation->set_rules('affiliation_no', 'Affiliation No.', 'trim');
		$this->form_validation->set_rules('address', 'Address', 'required|trim');
		$this->form_validation->set_rules('state_id', 'State', 'required|integer');
		$this->form_validation->set_rules('city_id', 'City', 'required|integer');
		$this->form_validation->set_rules('pincode', 'Pincode', 'required|trim|min_length[6]|max_length[10]');
		$this->form_validation->set_rules('admin_name', 'Admin Name', 'required|trim');
		$this->form_validation->set_rules('admin_phone', 'Admin Phone', 'required|trim');
		$this->form_validation->set_rules('admin_email', 'Admin Email', 'required|valid_email');
		$this->form_validation->set_rules('admin_password', 'Admin Password', 'min_length[6]');
		$this->form_validation->set_rules('status', 'Status', 'required|in_list[active,inactive,suspended]');
		// Use callback to validate school_board array
		$this->form_validation->set_rules('school_board', 'School Board', 'callback_validate_school_boards');
		
		if ($this->form_validation->run() == FALSE)
		{
			// Get states, cities, and boards for dropdown
			$data['states'] = $this->Location_model->getAllStates();
			$data['cities'] = $this->Location_model->getCitiesByState($school['state_id']);
			$data['boards'] = $this->School_board_model->getBoardsByVendor($this->current_vendor['id']);
			
			// Get selected board IDs for this school
			$selected_board_ids = $this->School_model->getSchoolBoardIds($school_id);
			$school['board_ids'] = $selected_board_ids;
			
			$data['school'] = $school;
			$data['school_images'] = $this->School_model->getSchoolImages($school_id);
			
			$data['title'] = 'Edit School';
			$data['current_vendor'] = $this->current_vendor;
			$data['vendor_domain'] = $this->getVendorDomainForUrl();
			$data['breadcrumb'] = array(
				array('label' => 'Schools', 'url' => $this->current_vendor['domain'] . '/schools'),
				array('label' => 'Edit', 'active' => true)
			);
			
			// Load content view
			$data['content'] = $this->load->view('vendor/schools/edit', $data, TRUE);
			
			// Load main layout
			$this->load->view('vendor/layouts/index_template', $data);
		}
		else
		{
			// Get selected boards (already validated above)
			$selected_boards = $this->input->post('school_board');
			
			// Filter out empty values and ensure we have valid integers
			if (!is_array($selected_boards))
			{
				$selected_boards = array();
			}
			$selected_boards = array_filter(array_map('intval', $selected_boards));
			
			// Ensure we have at least one board (validation should have caught this, but double-check)
			if (empty($selected_boards))
			{
			$this->session->set_flashdata('error', 'Please select at least one board.');
			$this->load->helper('common');
			$vendor_domain = $this->getVendorDomainForUrl();
			redirect(vendor_url('schools/edit/' . $school_id, $vendor_domain));
			return;
			}
			
			// Update school (store comma-separated board IDs)
			$school_data = array(
				'school_name' => $this->input->post('school_name'),
				'slug' => slugify($this->input->post('school_name')) . '-' . $school_id,
				'school_board' => implode(',', $selected_boards),
				'total_strength' => $this->input->post('total_strength') ? (int)$this->input->post('total_strength') : NULL,
				'school_description' => $this->input->post('school_description'),
				'affiliation_no' => $this->input->post('affiliation_no'),
				'address' => $this->input->post('address'),
				'state_id' => $this->input->post('state_id'),
				'city_id' => $this->input->post('city_id'),
				'pincode' => $this->input->post('pincode'),
				'admin_name' => $this->input->post('admin_name'),
				'admin_phone' => $this->input->post('admin_phone'),
				'admin_email' => $this->input->post('admin_email'),
				'status' => $this->input->post('status')
			);
			
			// Update password only if provided
			if ($this->input->post('admin_password'))
			{
				$school_data['admin_password'] = $this->input->post('admin_password');
			}
			
			// Update school
			$update_result = $this->School_model->updateSchool($school_id, $school_data);
			
			// Update multiple boards (this always succeeds if called)
			$boards_updated = $this->School_model->saveSchoolBoards($school_id, $selected_boards);
			
			// Handle image uploads (non-critical, continue even if fails)
			$this->handleImageUploads($school_id);
			
			// Consider update successful if:
			// 1. Main update succeeded, OR
			// 2. Boards were updated successfully
			// The main update might return FALSE if no rows were affected (data unchanged), but that's OK
			if ($update_result || $boards_updated)
			{
				$this->session->set_flashdata('success', 'School updated successfully.');
				$this->load->helper('common');
			$vendor_domain = $this->getVendorDomainForUrl();
			redirect(vendor_url('schools', $vendor_domain));
			}
			else
			{
				// Check if there was a database error
				$db_error = $this->db->error();
				if (!empty($db_error['message']) && $db_error['code'] != 0)
				{
					$this->session->set_flashdata('error', 'Failed to update school: ' . $db_error['message']);
					$this->load->helper('common');
					$vendor_domain = $this->getVendorDomainForUrl();
					redirect(vendor_url('schools/edit/' . $school_id, $vendor_domain));
				}
				else
				{
					// If we get here, it means update returned FALSE but no error
					// This can happen if data hasn't changed, but boards were updated
					// So we should still show success
					$this->session->set_flashdata('success', 'School updated successfully.');
					$this->load->helper('common');
					$vendor_domain = $this->getVendorDomainForUrl();
					redirect(vendor_url('schools', $vendor_domain));
				}
			}
		}
	}
	
	/**
	 * View school details
	 *
	 * @param	int	$school_id	School ID
	 * @return	void
	 */
	/**
	 * Get school details for modal (AJAX)
	 *
	 * @param	int	$school_id	School ID
	 * @return	void
	 */
	public function get_school_details($school_id)
	{
		// Get school
		$school = $this->School_model->getSchoolById($school_id, $this->current_vendor['id']);
		
		if (!$school)
		{
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode(array(
					'success' => false,
					'message' => 'School not found'
				)));
			return;
		}
		
		// Get school boards
		$board_ids = $this->School_model->getSchoolBoardIds($school_id);
		$boards = array();
		if (!empty($board_ids))
		{
			foreach ($board_ids as $board_id)
			{
				$board = $this->School_board_model->getBoardById($board_id, $this->current_vendor['id']);
				if ($board)
				{
					$boards[] = $board;
				}
			}
		}
		$school['boards'] = $boards;
		
		// Get school images
		$school['images'] = $this->School_model->getSchoolImages($school_id);
		
		// Get all branches for this school
		$branches = $this->Branch_model->getBranchesBySchool($school_id, $this->current_vendor['id']);
		
		// Get boards for each branch (branches inherit boards from parent school)
		foreach ($branches as &$branch)
		{
			$branch['boards'] = $boards; // Branches use the same boards as parent school
		}
		unset($branch);
		
		// Format response
		$response = array(
			'success' => true,
			'school' => $school,
			'branches' => $branches
		);
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($response));
	}
	
	public function view($school_id)
	{
		// Get school
		$school = $this->School_model->getSchoolById($school_id, $this->current_vendor['id']);
		
		if (!$school)
		{
			show_404();
		}
		
		// Get school boards
		$board_ids = $this->School_model->getSchoolBoardIds($school_id);
		$boards = array();
		if (!empty($board_ids))
		{
			foreach ($board_ids as $board_id)
			{
				$board = $this->School_board_model->getBoardById($board_id, $this->current_vendor['id']);
				if ($board)
				{
					$boards[] = $board;
				}
			}
		}
		$school['boards'] = $boards;
		
		// Get school images
		$school['images'] = $this->School_model->getSchoolImages($school_id);
		
		// Get all branches for this school
		$branches = $this->Branch_model->getBranchesBySchool($school_id, $this->current_vendor['id']);
		
		$data['school'] = $school;
		$data['branches'] = $branches;
		$data['title'] = 'School Details - ' . $school['school_name'];
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $this->getVendorDomainForUrl();
		$data['breadcrumb'] = array(
			array('label' => 'Schools', 'url' => $this->current_vendor['domain'] . '/schools'),
			array('label' => 'View Details', 'active' => true)
		);
		
		// Load content view
		$data['content'] = $this->load->view('vendor/schools/view', $data, TRUE);
		
		// Load main layout
		$this->load->view('vendor/layouts/index_template', $data);
	}
	
	/**
	 * Delete school
	 *
	 * @param	int	$school_id	School ID
	 * @return	void
	 */
	public function delete($school_id)
	{
		// Get school to verify ownership
		$school = $this->School_model->getSchoolById($school_id, $this->current_vendor['id']);
		
		if (!$school)
		{
			show_404();
		}
		
		// Delete school images first
		$images = $this->School_model->getSchoolImages($school_id);
		foreach ($images as $image)
		{
			$image_path = FCPATH . 'uploads/schools/' . $image['image_path'];
			if (file_exists($image_path))
			{
				unlink($image_path);
			}
		}
		
		if ($this->School_model->deleteSchool($school_id, $this->current_vendor['id']))
		{
			$this->session->set_flashdata('success', 'School deleted successfully.');
		}
		else
		{
			$this->session->set_flashdata('error', 'Failed to delete school.');
		}
		
		$this->load->helper('common');
		$vendor_domain = $this->getVendorDomainForUrl();
		redirect(vendor_url('schools', $vendor_domain));
	}
	
	/**
	 * Delete school image
	 *
	 * @param	int	$image_id	Image ID
	 * @return	void
	 */
	public function delete_image($image_id)
	{
		// Get image info
		$this->db->where('id', $image_id);
		$query = $this->db->get('erp_school_images');
		
		if ($query->num_rows() > 0)
		{
			$image = $query->row_array();
			
			// Verify school belongs to vendor
			$school = $this->School_model->getSchoolById($image['school_id'], $this->current_vendor['id']);
			
			if ($school)
			{
				// Delete file
				$image_path = FCPATH . 'uploads/schools/' . $image['image_path'];
				if (file_exists($image_path))
				{
					unlink($image_path);
				}
				
				// Delete from database
				$this->School_model->deleteSchoolImage($image_id, $image['school_id']);
				
				$this->session->set_flashdata('success', 'Image deleted successfully.');
			}
		}
		
		$this->load->helper('common');
		$vendor_domain = $this->getVendorDomainForUrl();
		redirect(vendor_url('schools/edit/' . $image['school_id'], $vendor_domain));
	}
	
	/**
	 * Get cities by state (AJAX)
	 *
	 * @return	void
	 */
	public function get_cities()
	{
		// Accept both GET and POST to avoid CSRF issues
		$state_id = $this->input->post('state_id') ? $this->input->post('state_id') : $this->input->get('state_id');
		
		if ($state_id)
		{
			$cities = $this->Location_model->getCitiesByState($state_id);
			header('Content-Type: application/json');
			echo json_encode($cities);
		}
		else
		{
			header('Content-Type: application/json');
			echo json_encode(array());
		}
	}
	
	/**
	 * Add new board (AJAX)
	 *
	 * @return	void
	 */
	public function add_board()
	{
		// Set validation rules
		$this->form_validation->set_rules('board_name', 'Board Name', 'required|trim|max_length[100]');
		$this->form_validation->set_rules('description', 'Description', 'trim');
		
		if ($this->form_validation->run() == FALSE)
		{
			$response = array(
				'status' => 'error',
				'message' => validation_errors()
			);
		}
		else
		{
			$board_name = $this->input->post('board_name');
			
			// Check if board name already exists
			if ($this->School_board_model->boardNameExists($board_name, $this->current_vendor['id']))
			{
				$response = array(
					'status' => 'error',
					'message' => 'Board name already exists.'
				);
			}
			else
			{
				// Create board
				$board_data = array(
					'vendor_id' => $this->current_vendor['id'],
					'board_name' => $board_name,
					'description' => $this->input->post('description'),
					'status' => 'active'
				);
				
				$board_id = $this->School_board_model->createBoard($board_data);
				
				if ($board_id)
				{
					$response = array(
						'status' => 'success',
						'message' => 'Board added successfully.',
						'board' => array(
							'id' => $board_id,
							'board_name' => $board_name
						)
					);
				}
				else
				{
					$response = array(
						'status' => 'error',
						'message' => 'Failed to add board.'
					);
				}
			}
		}
		
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	/**
	 * Get boards (AJAX)
	 *
	 * @return	void
	 */
	public function get_boards()
	{
		$boards = $this->School_board_model->getBoardsByVendor($this->current_vendor['id']);
		header('Content-Type: application/json');
		echo json_encode($boards);
	}
	
	/**
	 * List and manage boards
	 *
	 * @return	void
	 */
	public function boards()
	{
		// Get all boards for this vendor (including system boards)
		$data['boards'] = $this->School_board_model->getBoardsByVendor($this->current_vendor['id']);
		
		// Calculate total boards count
		$data['total_boards'] = count($data['boards']);
		
		// Get vendor's custom boards only
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$this->db->order_by('board_name', 'ASC');
		$query = $this->db->get('erp_school_boards');
		$data['custom_boards'] = $query->result_array();
		
		// Prepare view data
		$data['title'] = 'Manage Boards - ' . $this->current_vendor['name'];
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $this->getVendorDomainForUrl();
		$data['breadcrumb'] = array(
			array('label' => 'Schools', 'url' => base_url((!empty($data['vendor_domain']) ? $data['vendor_domain'] . '/' : '') . 'schools')),
			array('label' => 'Boards', 'active' => true)
		);
		
		// Load content view
		$data['content'] = $this->load->view('vendor/schools/boards', $data, TRUE);
		
		// Load main layout
		$this->load->view('vendor/layouts/index_template', $data);
	}
	
	/**
	 * Update board (AJAX)
	 *
	 * @return	void
	 */
	public function update_board()
	{
		// Set validation rules
		$this->form_validation->set_rules('board_id', 'Board ID', 'required|integer');
		$this->form_validation->set_rules('board_name', 'Board Name', 'required|trim|max_length[100]');
		$this->form_validation->set_rules('description', 'Description', 'trim');
		
		if ($this->form_validation->run() == FALSE)
		{
			$response = array(
				'status' => 'error',
				'message' => validation_errors()
			);
		}
		else
		{
			$board_id = $this->input->post('board_id');
			$board_name = $this->input->post('board_name');
			
			// Verify board belongs to vendor
			$board = $this->School_board_model->getBoardById($board_id, $this->current_vendor['id']);
			
			if (!$board || $board['vendor_id'] != $this->current_vendor['id'])
			{
				$response = array(
					'status' => 'error',
					'message' => 'Board not found or you do not have permission to edit it.'
				);
			}
			else
			{
				// Check if board name already exists (excluding current board)
				if ($this->School_board_model->boardNameExists($board_name, $this->current_vendor['id'], $board_id))
				{
					$response = array(
						'status' => 'error',
						'message' => 'Board name already exists.'
					);
				}
				else
				{
					// Update board
					$board_data = array(
						'board_name' => $board_name,
						'description' => $this->input->post('description')
					);
					
					if ($this->School_board_model->updateBoard($board_id, $board_data))
					{
						$response = array(
							'status' => 'success',
							'message' => 'Board updated successfully.'
						);
					}
					else
					{
						$response = array(
							'status' => 'error',
							'message' => 'Failed to update board.'
						);
					}
				}
			}
		}
		
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	/**
	 * Delete board (AJAX)
	 *
	 * @return	void
	 */
	public function delete_board()
	{
		// Set validation rules
		$this->form_validation->set_rules('board_id', 'Board ID', 'required|integer');
		
		if ($this->form_validation->run() == FALSE)
		{
			$response = array(
				'status' => 'error',
				'message' => validation_errors()
			);
		}
		else
		{
			$board_id = $this->input->post('board_id');
			
			// Delete board (only vendor's own boards)
			if ($this->School_board_model->deleteBoard($board_id, $this->current_vendor['id']))
			{
				$response = array(
					'status' => 'success',
					'message' => 'Board deleted successfully.'
				);
			}
			else
			{
				$response = array(
					'status' => 'error',
					'message' => 'Failed to delete board. Board may not exist or you do not have permission to delete it.'
				);
			}
		}
		
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	/**
	 * Handle school image upload (single image only)
	 *
	 * @param int $school_id
	 * @return void
	 */
	private function handleImageUploads($school_id)
	{
		if (empty($_FILES['school_image']['name'])) {
			return;
		}

		$this->config->load('upload');
		$uploadCfg = $this->config->item('school_upload');

		if (!$uploadCfg) {
			log_message('error', 'school_upload config missing');
			return;
		}

		$vendor_folder = get_vendor_domain_folder(); // filesystem only
		$date_folder   = date('Y_m_d');

		// ✅ REAL upload path (filesystem)
		$upload_path =
			rtrim($uploadCfg['base_root'], '/') . '/'
			. $vendor_folder . '/'
			. trim($uploadCfg['relative_dir'], '/') . '/'
			. $date_folder . '/';

		if (!is_dir($upload_path) && !mkdir($upload_path, 0755, true)) {
			log_message('error', 'Upload directory not writable: ' . $upload_path);
			return;
		}

		/**
		 * 🔥 DELETE OLD IMAGES (FILES + DB)
		 */
		$old_images = $this->School_model->getSchoolImages($school_id);

		foreach ($old_images as $img) {
			if (!empty($img['image_path'])) {
				$old_file =
					rtrim($uploadCfg['base_root'], '/') . '/'
					. $vendor_folder . '/'
					. ltrim($img['image_path'], '/');

				if (file_exists($old_file)) {
					@unlink($old_file);
				}
			}
		}

		$this->School_model->deleteSchoolImagesBySchool($school_id);

		/**
		 * ✅ Upload new image
		 */
		$file_ext = strtolower(pathinfo($_FILES['school_image']['name'], PATHINFO_EXTENSION));

		if (!in_array($file_ext, $uploadCfg['allowed_types'], true)) {
			$this->session->set_flashdata('error', 'Invalid image type');
			return;
		}

		$_FILES['image']['name']     = $_FILES['school_image']['name'];
		$_FILES['image']['type']     = $_FILES['school_image']['type'];
		$_FILES['image']['tmp_name'] = $_FILES['school_image']['tmp_name'];
		$_FILES['image']['error']    = $_FILES['school_image']['error'];
		$_FILES['image']['size']     = $_FILES['school_image']['size'];


		$config = [
			'upload_path'   => $upload_path,
			'allowed_types' => implode('|', $uploadCfg['allowed_types']),
			'max_size'      => $uploadCfg['max_size'],
			'file_name'     => 'school_' . $school_id . '_' . uniqid(),
			'overwrite'     => false
		];

		$this->load->library('upload');
		$this->upload->initialize($config);

		if (!$this->upload->do_upload('image')) {
			log_message('error', 'School image upload failed: ' . $this->upload->display_errors('', ''));
			return;
		}

		$upload_data = $this->upload->data();

		/**
		 * ✅ SAVE ONLY RELATIVE PATH (NO DOMAIN, NO VENDOR)
		 */
		$this->School_model->addSchoolImage([
			'school_id'     => $school_id,
			'image_path'    => 'uploads/schools/images/' . $date_folder . '/' . $upload_data['file_name'],
			'image_name'    => $_FILES['school_image']['name'],
			'is_primary'    => 1,
			'display_order' => 0
		]);
	}



	
	/**
	 * Custom validation callback for school_board array
	 *
	 * @param	mixed	$value	The field value (will be empty for arrays)
	 * @return	bool
	 */
	public function validate_school_boards($value)
	{
		$selected_boards = $this->input->post('school_board');
		
		if (empty($selected_boards) || !is_array($selected_boards) || count(array_filter($selected_boards)) == 0)
		{
			$this->form_validation->set_message('validate_school_boards', 'The School Board field is required.');
			return FALSE;
		}
		
		return TRUE;
	}
	
	/**
	 * Toggle payment block status (AJAX)
	 *
	 * @return	void
	 */
	public function toggle_payment_block()
	{
		$school_id = $this->input->post('school_id');
		$status = $this->input->post('status'); // 1 for blocked, 0 for active
		
		if (empty($school_id))
		{
			$response = array(
				'status' => 'error',
				'message' => 'School ID is required.'
			);
		}
		else
		{
			// Verify school belongs to vendor
			$school = $this->School_model->getSchoolById($school_id, $this->current_vendor['id']);
			
			if (!$school)
			{
				$response = array(
					'status' => 'error',
					'message' => 'School not found or you do not have permission to edit it.'
				);
			}
			else
			{
				// Update payment block status
				$update_data = array(
					'is_block_payment' => (int)$status
				);
				
				if ($this->School_model->updateSchool($school_id, $update_data))
				{
					$message = $status == 1 ? 'Payment is now blocked.' : 'Payment is now active.';
					$response = array(
						'status' => 'success',
						'message' => $message
					);
				}
				else
				{
					$response = array(
						'status' => 'error',
						'message' => 'Failed to update payment block status.'
					);
				}
			}
		}
		
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	/**
	 * Toggle national delivery block status (AJAX)
	 *
	 * @return	void
	 */
	public function toggle_national_block()
	{
		$school_id = $this->input->post('school_id');
		$status = $this->input->post('status'); // 1 for blocked, 0 for active
		
		if (empty($school_id))
		{
			$response = array(
				'status' => 'error',
				'message' => 'School ID is required.'
			);
		}
		else
		{
			// Verify school belongs to vendor
			$school = $this->School_model->getSchoolById($school_id, $this->current_vendor['id']);
			
			if (!$school)
			{
				$response = array(
					'status' => 'error',
					'message' => 'School not found or you do not have permission to edit it.'
				);
			}
			else
			{
				// Update national delivery block status
				$update_data = array(
					'is_national_block' => (int)$status
				);
				
				if ($this->School_model->updateSchool($school_id, $update_data))
				{
					$message = $status == 1 ? 'National delivery is now blocked.' : 'National delivery is now active.';
					$response = array(
						'status' => 'success',
						'message' => $message
					);
				}
				else
				{
					$response = array(
						'status' => 'error',
						'message' => 'Failed to update national delivery block status.'
					);
				}
			}
		}
		
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	/**
	 * Toggle school status (AJAX)
	 *
	 * @return	void
	 */
	public function toggle_status()
	{
		$school_id = $this->input->post('school_id');
		$status = $this->input->post('status'); // 'active' or 'inactive'
		
		if (empty($school_id))
		{
			$response = array(
				'status' => 'error',
				'message' => 'School ID is required.'
			);
		}
		elseif (!in_array($status, array('active', 'inactive', 'suspended')))
		{
			$response = array(
				'status' => 'error',
				'message' => 'Invalid status value.'
			);
		}
		else
		{
			// Verify school belongs to vendor
			$school = $this->School_model->getSchoolById($school_id, $this->current_vendor['id']);
			
			if (!$school)
			{
				$response = array(
					'status' => 'error',
					'message' => 'School not found or you do not have permission to edit it.'
				);
			}
			else
			{
				// Update status
				$update_data = array(
					'status' => $status
				);
				
				if ($this->School_model->updateSchool($school_id, $update_data))
				{
					$message = 'Status updated to ' . ucfirst($status) . '.';
					$response = array(
						'status' => 'success',
						'message' => $message
					);
				}
				else
				{
					$response = array(
						'status' => 'error',
						'message' => 'Failed to update status.'
					);
				}
			}
		}
		
		header('Content-Type: application/json');
		echo json_encode($response);
	}
}
