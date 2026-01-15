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
		$data['vendor_domain'] = $this->current_vendor['domain'];
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
			$data['vendor_domain'] = $this->current_vendor['domain'];
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
					redirect(base_url($this->current_vendor['domain'] . '/schools/add'));
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
					redirect(base_url($this->current_vendor['domain'] . '/branches'));
				}
				else
				{
					$this->session->set_flashdata('error', 'Failed to create branch.');
					redirect(base_url($this->current_vendor['domain'] . '/schools/add'));
				}
			}
			else
			{
				// Get selected boards
				$selected_boards = $this->input->post('school_board');
				if (empty($selected_boards) || !is_array($selected_boards))
				{
					$this->session->set_flashdata('error', 'Please select at least one board.');
					redirect(base_url($this->current_vendor['domain'] . '/schools/add'));
				}
				
				// Filter out empty values and ensure we have valid integers
				$selected_boards = array_filter(array_map('intval', $selected_boards));
				
				if (empty($selected_boards))
				{
					$this->session->set_flashdata('error', 'Please select at least one valid board.');
					redirect(base_url($this->current_vendor['domain'] . '/schools/add'));
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
					redirect(base_url($this->current_vendor['domain'] . '/schools'));
				}
				else
				{
					$this->session->set_flashdata('error', 'Failed to create school.');
					redirect(base_url($this->current_vendor['domain'] . '/schools/add'));
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
			$data['vendor_domain'] = $this->current_vendor['domain'];
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
				redirect(base_url($this->current_vendor['domain'] . '/schools/edit/' . $school_id));
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
				redirect(base_url($this->current_vendor['domain'] . '/schools'));
			}
			else
			{
				// Check if there was a database error
				$db_error = $this->db->error();
				if (!empty($db_error['message']) && $db_error['code'] != 0)
				{
					$this->session->set_flashdata('error', 'Failed to update school: ' . $db_error['message']);
					redirect(base_url($this->current_vendor['domain'] . '/schools/edit/' . $school_id));
				}
				else
				{
					// If we get here, it means update returned FALSE but no error
					// This can happen if data hasn't changed, but boards were updated
					// So we should still show success
					$this->session->set_flashdata('success', 'School updated successfully.');
					redirect(base_url($this->current_vendor['domain'] . '/schools'));
				}
			}
		}
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
		
		redirect(base_url($this->current_vendor['domain'] . '/schools'));
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
		
		redirect(base_url($this->current_vendor['domain'] . '/schools/edit/' . $image['school_id']));
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
		
		// Get vendor's custom boards only
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$this->db->order_by('board_name', 'ASC');
		$query = $this->db->get('erp_school_boards');
		$data['custom_boards'] = $query->result_array();
		
		// Prepare view data
		$data['title'] = 'Manage Boards - ' . $this->current_vendor['name'];
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $this->current_vendor['domain'];
		$data['breadcrumb'] = array(
			array('label' => 'Schools', 'url' => base_url($this->current_vendor['domain'] . '/schools')),
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
	 * Handle image uploads
	 *
	 * @param	int	$school_id	School ID
	 * @return	void
	 */
	private function handleImageUploads($school_id)
	{
		// Create upload directory if it doesn't exist
		$upload_path = FCPATH . 'uploads/schools/';
		if (!is_dir($upload_path))
		{
			mkdir($upload_path, 0755, TRUE);
		}
		
		// Handle multiple file uploads
		if (!empty($_FILES['school_images']['name'][0]))
		{
			$files = $_FILES['school_images'];
			$file_count = count($files['name']);
			
			for ($i = 0; $i < $file_count; $i++)
			{
				if ($files['error'][$i] == 0)
				{
					$config['upload_path'] = $upload_path;
					$config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
					$config['max_size'] = 5120; // 5MB
					$config['file_name'] = 'school_' . $school_id . '_' . time() . '_' . $i;
					
					$_FILES['file']['name'] = $files['name'][$i];
					$_FILES['file']['type'] = $files['type'][$i];
					$_FILES['file']['tmp_name'] = $files['tmp_name'][$i];
					$_FILES['file']['error'] = $files['error'][$i];
					$_FILES['file']['size'] = $files['size'][$i];
					
					$this->load->library('upload', $config);
					
					if ($this->upload->do_upload('file'))
					{
						$upload_data = $this->upload->data();
						
						// Save to database
						$image_data = array(
							'school_id' => $school_id,
							'image_path' => $upload_data['file_name'],
							'image_name' => $files['name'][$i],
							'display_order' => $i,
							'is_primary' => ($i == 0) ? 1 : 0 // First image is primary
						);
						
						$this->School_model->addSchoolImage($image_data);
					}
				}
			}
		}
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

