<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Uniforms Controller
 *
 * Handles uniform management for vendors
 *
 * @package		ERP
 * @subpackage	Controllers
 * @category	Controllers
 * @author		ERP Team
 */

// Load base controller
require_once(APPPATH . 'controllers/Vendor/Vendor_base.php');

class Uniforms extends Vendor_base
{
	/**
	 * Constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Uniform_model');
		$this->load->model('School_model');
		$this->load->model('Branch_model');
		$this->load->model('School_board_model');
		$this->load->model('Location_model');
		$this->load->library('form_validation');
		$this->load->library('upload');
	}
	
	/**
	 * List all uniforms
	 *
	 * @return	void
	 */
	public function index()
	{
		// Filters
		$filters = array();
		if ($this->input->get('status'))
		{
			$filters['status'] = $this->input->get('status');
		}
		if ($this->input->get('school_id'))
		{
			$filters['school_id'] = $this->input->get('school_id');
		}
		if ($this->input->get('search'))
		{
			$filters['search'] = $this->input->get('search');
		}
		if ($this->input->get('uniform_type_id'))
		{
			$filters['uniform_type_id'] = $this->input->get('uniform_type_id');
		}
		if ($this->input->get('board_id'))
		{
			$filters['board_id'] = $this->input->get('board_id');
		}
		if ($this->input->get('material_id'))
		{
			$filters['material_id'] = $this->input->get('material_id');
		}
		if ($this->input->get('gender'))
		{
			$filters['gender'] = $this->input->get('gender');
		}
		if ($this->input->get('branch_id'))
		{
			$filters['branch_id'] = $this->input->get('branch_id');
		}
		
		// Pagination
		$per_page = 10;
		$page = (int)$this->input->get('page');
		if ($page < 1) $page = 1;
		$offset = ($page - 1) * $per_page;
		
		// Get total count for pagination
		$total_uniforms = $this->Uniform_model->getTotalUniformsByVendor($this->current_vendor['id'], $filters);
		
		// Get uniforms with pagination
		$uniforms = $this->Uniform_model->getUniformsByVendor($this->current_vendor['id'], $filters, $per_page, $offset);
		
		// Enhance uniforms data with images and size prices
		foreach ($uniforms as &$uniform)
		{
			// Get main image (is_main = 1), fallback to first image if no main image is set
			$this->db->select('image_path');
			$this->db->from('erp_uniform_images');
			$this->db->where('uniform_id', $uniform['id']);
			$this->db->where('is_main', 1);
			$this->db->limit(1);
			$img_query = $this->db->get();
			
			if ($img_query->num_rows() > 0)
			{
				$uniform['thumbnail'] = $img_query->row()->image_path;
			}
			else
			{
				// If no main image found, use first image by order
				$this->db->select('image_path');
				$this->db->from('erp_uniform_images');
				$this->db->where('uniform_id', $uniform['id']);
				$this->db->order_by('image_order', 'ASC');
				$this->db->limit(1);
				$img_query = $this->db->get();
				$uniform['thumbnail'] = ($img_query->num_rows() > 0) ? $img_query->row()->image_path : NULL;
			}
			
			// Get size prices
			$size_prices = $this->Uniform_model->getUniformSizePrices($uniform['id']);
			$uniform['size_prices'] = $size_prices;
			
			// Calculate price range if size prices exist
			if (!empty($size_prices))
			{
				$mrp_values = array();
				$selling_price_values = array();
				foreach ($size_prices as $sp)
				{
					if (!empty($sp['mrp'])) $mrp_values[] = (float)$sp['mrp'];
					if (!empty($sp['selling_price'])) $selling_price_values[] = (float)$sp['selling_price'];
				}
				$uniform['min_mrp'] = !empty($mrp_values) ? min($mrp_values) : NULL;
				$uniform['max_mrp'] = !empty($mrp_values) ? max($mrp_values) : NULL;
				$uniform['min_selling_price'] = !empty($selling_price_values) ? min($selling_price_values) : NULL;
				$uniform['max_selling_price'] = !empty($selling_price_values) ? max($selling_price_values) : NULL;
			}
		}
		unset($uniform); // Break reference
		
		$data['uniforms'] = $uniforms;
		$data['total_uniforms'] = $total_uniforms;
		$data['per_page'] = $per_page;
		$data['current_page'] = $page;
		$data['total_pages'] = ceil($total_uniforms / $per_page);
		
		// Get filter dropdown data
		$data['schools'] = $this->School_model->getSchoolsByVendor($this->current_vendor['id']);
		$data['uniform_types'] = $this->Uniform_model->getAllUniformTypes();
		$data['materials'] = $this->Uniform_model->getAllMaterials();
		$data['boards'] = $this->School_board_model->getBoardsByVendor($this->current_vendor['id']);
		
		// Get branches for filter (all branches for the vendor)
		$this->load->model('Branch_model');
		$data['branches'] = $this->Branch_model->getBranchesByVendor($this->current_vendor['id']);
		
		$data['title'] = 'Manage Uniforms';
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $this->getVendorDomainForUrl();
		$data['filters'] = $filters;
		$data['breadcrumb'] = array(
			array('label' => 'Dashboard', 'url' => base_url($this->current_vendor['domain'] . '/dashboard')),
			array('label' => 'Uniforms', 'active' => true)
		);
		
		// Load content view
		$data['content'] = $this->load->view('vendor/uniforms/index', $data, TRUE);
		
		// Load main layout
		$this->load->view('vendor/layouts/index_template', $data);
	}
	
	/**
	 * Add new uniform
	 *
	 * @return	void
	 */
	public function add()
	{
		// Set validation rules
		$this->form_validation->set_rules('uniform_type_id', 'Uniform Type', 'required|integer');
		$this->form_validation->set_rules('school_id', 'School', 'required|integer');
		$this->form_validation->set_rules('board_id', 'Board', 'required|integer');
		$this->form_validation->set_rules('gender', 'Gender', 'required|in_list[male,female,unisex]');
		$this->form_validation->set_rules('product_name', 'Product Name', 'required|trim');
		$this->form_validation->set_rules('min_quantity', 'Min Quantity', 'required|integer|greater_than[0]');
		$this->form_validation->set_rules('material_id', 'Material', 'required|integer');
		$this->form_validation->set_rules('product_description', 'Product Description', 'required|trim');
		$this->form_validation->set_rules('gst_percentage', 'GST (%)', 'required|numeric');
		
		if ($this->form_validation->run() == FALSE)
		{
			// Get dropdown data
			$data['uniform_types'] = $this->Uniform_model->getAllUniformTypes();
			$data['materials'] = $this->Uniform_model->getAllMaterials();
			$data['schools'] = $this->School_model->getSchoolsByVendor($this->current_vendor['id']);
			$data['boards'] = $this->School_board_model->getBoardsByVendor($this->current_vendor['id']);
			$data['size_charts'] = $this->Uniform_model->getSizeChartsByVendor($this->current_vendor['id']);
			
			$data['title'] = 'Add New Uniform';
			$data['current_vendor'] = $this->current_vendor;
			$data['vendor_domain'] = $this->getVendorDomainForUrl();
			$data['breadcrumb'] = array(
				array('label' => 'Uniforms', 'url' => base_url($this->current_vendor['domain'] . '/products/uniforms')),
				array('label' => 'Add', 'active' => true)
			);
			
			// Load content view
			$data['content'] = $this->load->view('vendor/uniforms/add', $data, TRUE);
			
			// Load main layout
			$this->load->view('vendor/layouts/index_template', $data);
		}
		else
		{

			$commission_type  = $this->input->post('school_commission_type');
			$commission_value = $this->input->post('school_commission_value');

			if (!in_array($commission_type, ['fixed', 'percentage'])) {
				$commission_type  = NULL;
				$commission_value = NULL;
			}
			// Process form
			$uniform_data = array(
				'vendor_id' => $this->current_vendor['id'],
				'uniform_type_id' => $this->input->post('uniform_type_id'),
				'school_id' => $this->input->post('school_id'),
				'branch_id' => $this->input->post('branch_id') ? (int)$this->input->post('branch_id') : NULL,
				'board_id' => $this->input->post('board_id'),
				'gender' => $this->input->post('gender'),
				'color' => $this->input->post('color'),
				'product_name' => $this->input->post('product_name'),
				'isbn' => $this->input->post('isbn'),
				'min_quantity' => $this->input->post('min_quantity'),
				'days_to_exchange' => $this->input->post('days_to_exchange') ? (int)$this->input->post('days_to_exchange') : NULL,
				'material_id' => $this->input->post('material_id'),
				'product_origin' => $this->input->post('product_origin'),
				'product_description' => $this->input->post('product_description'),
				'manufacturer_details' => $this->input->post('manufacturer_details'),
				'packer_details' => $this->input->post('packer_details'),
				'customer_details' => $this->input->post('customer_details'),
				'price' => $this->input->post('price') ? (float)$this->input->post('price') : NULL,
				'size_chart_id' => $this->input->post('size_chart_id') ? (int)$this->input->post('size_chart_id') : NULL,
				'size_id' => $this->input->post('size_id') ? (int)$this->input->post('size_id') : NULL,
				'packaging_length' => $this->input->post('packaging_length') ? (float)$this->input->post('packaging_length') : NULL,
				'packaging_width' => $this->input->post('packaging_width') ? (float)$this->input->post('packaging_width') : NULL,
				'packaging_height' => $this->input->post('packaging_height') ? (float)$this->input->post('packaging_height') : NULL,
				'packaging_weight' => $this->input->post('packaging_weight') ? (float)$this->input->post('packaging_weight') : NULL,
				'tax' => $this->input->post('tax') ? (float)$this->input->post('tax') : NULL,
				'gst_percentage' => $this->input->post('gst_percentage'),
				'hsn' => $this->input->post('hsn'),
				'meta_title' => $this->input->post('meta_title'),
				'meta_keywords' => $this->input->post('meta_keywords'),
				'meta_description' => $this->input->post('meta_description'),
				'school_commission_type' => $commission_type,
				'school_commission_value' => $commission_value,
				'status' => $this->input->post('status') ? $this->input->post('status') : 'active',
				'is_individual' => $this->input->post('is_individual') ? 1 : 0,
				'is_set' => $this->input->post('is_set') ? 1 : 0
			);

			$uniform_id = $this->Uniform_model->createUniform($uniform_data);
			
			if ($uniform_id)
			{
				// Handle image uploads
				$this->handleImageUploads($uniform_id);

				// Slug
				$slug = slugify($uniform_data['product_name']) . '-' . $uniform_id;
				$this->Uniform_model->updateUniform($uniform_id, array('slug' => $slug));

				// Save size prices if provided
				$size_prices = $this->input->post('size_prices');
				if (!empty($size_prices) && is_array($size_prices))
				{
					$this->Uniform_model->saveUniformSizePrices($uniform_id, $size_prices);
				}
				
				$this->session->set_flashdata('success', 'Uniform created successfully.');
				$this->load->helper('common');
				redirect(base_url('products/uniforms'));
			}
			else
			{
				$this->session->set_flashdata('error', 'Failed to create uniform.');
				$this->load->helper('common');
				redirect(base_url('products/uniforms/add'));
			}
		}
	}
	
	/**
	 * Edit uniform
	 *
	 * @param	int	$uniform_id	Uniform ID
	 * @return	void
	 */
	public function edit($uniform_id)
	{
		// Get uniform
		$uniform = $this->Uniform_model->getUniformById($uniform_id);
		
		if (!$uniform || $uniform['vendor_id'] != $this->current_vendor['id'])
		{
			show_404();
		}
		
		// Set validation rules
		$this->form_validation->set_rules('uniform_type_id', 'Uniform Type', 'required|integer');
		$this->form_validation->set_rules('school_id', 'School', 'required|integer');
		$this->form_validation->set_rules('board_id', 'Board', 'required|integer');
		$this->form_validation->set_rules('gender', 'Gender', 'required|in_list[male,female,unisex]');
		$this->form_validation->set_rules('product_name', 'Product Name', 'required|trim');
		$this->form_validation->set_rules('min_quantity', 'Min Quantity', 'required|integer|greater_than[0]');
		$this->form_validation->set_rules('material_id', 'Material', 'required|integer');
		$this->form_validation->set_rules('product_description', 'Product Description', 'required|trim');
		$this->form_validation->set_rules('gst_percentage', 'GST (%)', 'required|numeric');
		
		if ($this->form_validation->run() == FALSE)
		{
			// Get dropdown data
			$data['uniform_types'] = $this->Uniform_model->getAllUniformTypes();
			$data['materials'] = $this->Uniform_model->getAllMaterials();
			$data['schools'] = $this->School_model->getSchoolsByVendor($this->current_vendor['id']);
			$data['boards'] = $this->School_board_model->getBoardsByVendor($this->current_vendor['id']);
			$data['size_charts'] = $this->Uniform_model->getSizeChartsByVendor($this->current_vendor['id']);
			
			// Get branches for selected school
			if ($uniform['school_id'])
			{
				$data['branches'] = $this->Branch_model->getBranchesByVendor($this->current_vendor['id'], array('school_id' => $uniform['school_id']));
			}
			else
			{
				$data['branches'] = array();
			}
			
			// Get boards for selected school
			if ($uniform['school_id'])
			{
				$school = $this->School_model->getSchoolById($uniform['school_id'], $this->current_vendor['id']);
				if ($school && !empty($school['school_board']))
				{
					$board_ids = $this->School_model->getSchoolBoardIds($uniform['school_id']);
					$data['school_boards'] = array();
					foreach ($board_ids as $board_id)
					{
						foreach ($data['boards'] as $board)
						{
							if ($board['id'] == $board_id)
							{
								$data['school_boards'][] = $board;
								break;
							}
						}
					}
				}
				else
				{
					$data['school_boards'] = $data['boards'];
				}
			}
			else
			{
				$data['school_boards'] = $data['boards'];
			}
			
			// Get images
			$data['uniform_images'] = $this->Uniform_model->getUniformImages($uniform_id);
			
			// Get size prices
			$data['size_prices'] = $this->Uniform_model->getUniformSizePrices($uniform_id);
			
			$data['uniform'] = $uniform;
			$data['title'] = 'Edit Uniform';
			$data['current_vendor'] = $this->current_vendor;
			$data['vendor_domain'] = $this->getVendorDomainForUrl();
			$data['breadcrumb'] = array(
				array('label' => 'Uniforms', 'url' => base_url($this->current_vendor['domain'] . '/products/uniforms')),
				array('label' => 'Edit', 'active' => true)
			);
			
			// Load content view
			$data['content'] = $this->load->view('vendor/uniforms/edit', $data, TRUE);
			
			// Load main layout
			$this->load->view('vendor/layouts/index_template', $data);
		}
		else
		{
			$commission_type  = $this->input->post('school_commission_type');
			$commission_value = $this->input->post('school_commission_value');

			if (!in_array($commission_type, ['fixed', 'percentage'])) {
				$commission_type  = NULL;
				$commission_value = NULL;
			}
			// Process form
			$uniform_data = array(
				'uniform_type_id' => $this->input->post('uniform_type_id'),
				'school_id' => $this->input->post('school_id'),
				'branch_id' => $this->input->post('branch_id') ? (int)$this->input->post('branch_id') : NULL,
				'board_id' => $this->input->post('board_id'),
				'gender' => $this->input->post('gender'),
				'color' => $this->input->post('color'),
				'product_name' => $this->input->post('product_name'),
				'slug' => slugify($this->input->post('product_name')) . '-' . $uniform_id,
				'isbn' => $this->input->post('isbn'),
				'min_quantity' => $this->input->post('min_quantity'),
				'days_to_exchange' => $this->input->post('days_to_exchange') ? (int)$this->input->post('days_to_exchange') : NULL,
				'material_id' => $this->input->post('material_id'),
				'product_origin' => $this->input->post('product_origin'),
				'product_description' => $this->input->post('product_description'),
				'manufacturer_details' => $this->input->post('manufacturer_details'),
				'packer_details' => $this->input->post('packer_details'),
				'customer_details' => $this->input->post('customer_details'),
				'price' => $this->input->post('price') ? (float)$this->input->post('price') : NULL,
				'size_chart_id' => $this->input->post('size_chart_id') ? (int)$this->input->post('size_chart_id') : NULL,
				'size_id' => $this->input->post('size_id') ? (int)$this->input->post('size_id') : NULL,
				'packaging_length' => $this->input->post('packaging_length') ? (float)$this->input->post('packaging_length') : NULL,
				'packaging_width' => $this->input->post('packaging_width') ? (float)$this->input->post('packaging_width') : NULL,
				'packaging_height' => $this->input->post('packaging_height') ? (float)$this->input->post('packaging_height') : NULL,
				'packaging_weight' => $this->input->post('packaging_weight') ? (float)$this->input->post('packaging_weight') : NULL,
				'tax' => $this->input->post('tax') ? (float)$this->input->post('tax') : NULL,
				'gst_percentage' => $this->input->post('gst_percentage'),
				'hsn' => $this->input->post('hsn'),
				'meta_title' => $this->input->post('meta_title'),
				'meta_keywords' => $this->input->post('meta_keywords'),
				'meta_description' => $this->input->post('meta_description'),
				'school_commission_type' => $commission_type,
				'school_commission_value' => $commission_value,
				'status' => $this->input->post('status') ? $this->input->post('status') : 'active',
				'is_individual' => $this->input->post('is_individual') ? 1 : 0,
				'is_set' => $this->input->post('is_set') ? 1 : 0
			);
			
			// Try to update uniform data (may return FALSE if no changes, which is OK)
			$update_result = $this->Uniform_model->updateUniform($uniform_id, $uniform_data);
			
			// Check if there are image updates to process
			$main_image_id = $this->input->post('main_image_id');
			$image_order = $this->input->post('image_order');
			$deleted_image_ids = $this->input->post('deleted_image_ids');
			$has_new_images = !empty($_FILES['images']['name'][0]);
			$has_image_updates = !empty($main_image_id) || !empty($image_order) || !empty($deleted_image_ids);
			
			// Process image updates (even if uniform data update returned FALSE - could be no data changes)
			$this->handleUniformImageUpdates($uniform_id);
			$this->handleImageUploads($uniform_id);
			
			// Save size prices if provided
			$size_prices = $this->input->post('size_prices');
			if (!empty($size_prices) && is_array($size_prices))
			{
				$this->Uniform_model->saveUniformSizePrices($uniform_id, $size_prices);
			}
			else
			{
				// If no size prices provided, delete existing ones
				$this->Uniform_model->saveUniformSizePrices($uniform_id, array());
			}
			
			// Show success if uniform data was updated OR if image updates were processed
			if ($update_result || $has_image_updates || $has_new_images)
			{
				$this->session->set_flashdata('success', 'Uniform updated successfully.');
				$this->load->helper('common');
				redirect(base_url('products/uniforms'));
			}
			else
			{
				// Only show error if no updates of any kind were detected
				$this->session->set_flashdata('error', 'No changes detected.');
				$this->load->helper('common');
				redirect(base_url('products/uniforms/edit/' . $uniform_id));
			}
		}
	}
	
	/**
	 * Delete uniform
	 *
	 * @param	int	$uniform_id	Uniform ID
	 * @return	void
	 */
	public function delete($uniform_id)
	{
		$uniform = $this->Uniform_model->getUniformById($uniform_id);
		
		if (!$uniform || $uniform['vendor_id'] != $this->current_vendor['id'])
		{
			show_404();
		}
		
		if ($this->Uniform_model->deleteUniform($uniform_id))
		{
			$this->session->set_flashdata('success', 'Uniform deleted successfully.');
		}
		else
		{
			$this->session->set_flashdata('error', 'Failed to delete uniform.');
		}
		
		redirect(base_url('products/uniforms'));
	}
	
	/**
	 * Get branches by school (AJAX)
	 *
	 * @return	void
	 */
	public function get_branches()
	{
		header('Content-Type: application/json');
		
		$school_id = $this->input->get('school_id');
		
		if (empty($school_id))
		{
			echo json_encode(array('status' => 'error', 'message' => 'School ID required'));
			return;
		}
		
		$branches = $this->Branch_model->getBranchesByVendor($this->current_vendor['id'], array('school_id' => $school_id));
		
		echo json_encode(array('status' => 'success', 'branches' => $branches));
	}
	
	/**
	 * Get boards by school (AJAX)
	 *
	 * @return	void
	 */
	public function get_boards()
	{
		header('Content-Type: application/json');
		
		$school_id = $this->input->get('school_id');
		
		if (empty($school_id))
		{
			echo json_encode(array('status' => 'error', 'message' => 'School ID required'));
			return;
		}
		
		$school = $this->School_model->getSchoolById($school_id, $this->current_vendor['id']);
		
		if (!$school)
		{
			echo json_encode(array('status' => 'error', 'message' => 'School not found'));
			return;
		}
		
		$all_boards = $this->School_board_model->getBoardsByVendor($this->current_vendor['id']);
		
		// Get board IDs from mapping table
		$board_ids = $this->School_model->getSchoolBoardIds($school_id);
		
		if (!empty($board_ids))
		{
			// Filter boards to only show those mapped to this school
			$school_boards = array();
			foreach ($board_ids as $board_id)
			{
				foreach ($all_boards as $board)
				{
					if ($board['id'] == $board_id)
					{
						$school_boards[] = $board;
						break;
					}
				}
			}
		}
		else
		{
			// If no boards mapped, show all boards for the vendor
			$school_boards = $all_boards;
		}
		
		echo json_encode(array('status' => 'success', 'boards' => $school_boards));
	}
	
	/**
	 * Add uniform type (AJAX)
	 *
	 * @return	void
	 */
	public function add_uniform_type()
	{
		header('Content-Type: application/json');
		
		$name = $this->input->post('name');
		
		if (empty($name))
		{
			echo json_encode(array('status' => 'error', 'message' => 'Name is required'));
			return;
		}
		
		$data = array(
			'name' => $name,
			'description' => $this->input->post('description'),
			'status' => 'active'
		);
		
		$type_id = $this->Uniform_model->createUniformType($data);
		
		if ($type_id)
		{
			echo json_encode(array('status' => 'success', 'id' => $type_id, 'name' => $name));
		}
		else
		{
			echo json_encode(array('status' => 'error', 'message' => 'Failed to create uniform type'));
		}
	}
	
	/**
	 * Add material (AJAX)
	 *
	 * @return	void
	 */
	public function add_material()
	{
		header('Content-Type: application/json');
		
		$name = $this->input->post('name');
		
		if (empty($name))
		{
			echo json_encode(array('status' => 'error', 'message' => 'Name is required'));
			return;
		}
		
		$data = array(
			'name' => $name,
			'description' => $this->input->post('description'),
			'status' => 'active'
		);
		
		$material_id = $this->Uniform_model->createMaterial($data);
		
		if ($material_id)
		{
			echo json_encode(array('status' => 'success', 'id' => $material_id, 'name' => $name));
		}
		else
		{
			echo json_encode(array('status' => 'error', 'message' => 'Failed to create material'));
		}
	}
	
	/**
	 * Add size chart (AJAX)
	 *
	 * @return	void
	 */
	public function add_size_chart()
	{
		header('Content-Type: application/json');
		
		$name = $this->input->post('name');
		$sizes = $this->input->post('sizes'); // Array of size names
		
		if (empty($name))
		{
			echo json_encode(array('status' => 'error', 'message' => 'Size Chart Name is required'));
			return;
		}
		
		if (empty($sizes) || !is_array($sizes) || count($sizes) == 0)
		{
			echo json_encode(array('status' => 'error', 'message' => 'At least one size is required'));
			return;
		}
		
		$data = array(
			'vendor_id' => $this->current_vendor['id'],
			'name' => $name,
			'description' => $this->input->post('description'),
			'status' => 'active'
		);
		
		$size_chart_id = $this->Uniform_model->createSizeChart($data);
		
		if ($size_chart_id)
		{
			// Add sizes
			$sizes_added = $this->Uniform_model->addMultipleSizes($size_chart_id, $sizes);
			
			if ($sizes_added)
			{
				// Get all sizes for this chart
				$chart_sizes = $this->Uniform_model->getSizesBySizeChart($size_chart_id);
				
				echo json_encode(array(
					'status' => 'success',
					'id' => $size_chart_id,
					'name' => $name,
					'sizes' => $chart_sizes
				));
			}
			else
			{
				echo json_encode(array('status' => 'error', 'message' => 'Size chart created but failed to add sizes'));
			}
		}
		else
		{
			echo json_encode(array('status' => 'error', 'message' => 'Failed to create size chart'));
		}
	}
	
	/**
	 * Get sizes by size chart (AJAX)
	 *
	 * @return	void
	 */
	public function get_sizes()
	{
		header('Content-Type: application/json');
		
		// Accept both GET and POST requests to avoid CSRF issues for read-only operations
		$size_chart_id = $this->input->post('size_chart_id') ? $this->input->post('size_chart_id') : $this->input->get('size_chart_id');
		
		if (empty($size_chart_id))
		{
			echo json_encode(array('status' => 'error', 'message' => 'Size Chart ID is required'));
			return;
		}
		
		// Verify the size chart belongs to this vendor
		$size_charts = $this->Uniform_model->getSizeChartsByVendor($this->current_vendor['id']);
		$valid_chart = false;
		foreach ($size_charts as $chart)
		{
			if ($chart['id'] == $size_chart_id)
			{
				$valid_chart = true;
				break;
			}
		}
		
		if (!$valid_chart)
		{
			echo json_encode(array('status' => 'error', 'message' => 'Invalid size chart'));
			return;
		}
		
		$sizes = $this->Uniform_model->getSizesBySizeChart($size_chart_id);
		
		echo json_encode(array('status' => 'success', 'sizes' => $sizes));
	}
	
	/**
	 * Delete uniform image (AJAX)
	 *
	 * @param	int	$image_id	Image ID
	 * @return	void
	 */
	public function delete_image($image_id)
	{
		header('Content-Type: application/json');
		
		$image = $this->db->where('id', $image_id)->get('erp_uniform_images')->row_array();
		
		if (!$image)
		{
			echo json_encode(array('status' => 'error', 'message' => 'Image not found'));
			return;
		}
		
		$uniform = $this->Uniform_model->getUniformById($image['uniform_id']);
		
		if (!$uniform || $uniform['vendor_id'] != $this->current_vendor['id'])
		{
			echo json_encode(array('status' => 'error', 'message' => 'Permission denied'));
			return;
		}
		
		// Delete file
		if (file_exists($image['image_path']))
		{
			unlink($image['image_path']);
		}
		
		if ($this->Uniform_model->deleteUniformImage($image_id))
		{
			echo json_encode(array('status' => 'success', 'message' => 'Image deleted successfully'));
		}
		else
		{
			echo json_encode(array('status' => 'error', 'message' => 'Failed to delete image'));
		}
	}
	
	/**
	 * Handle image uploads
	 *
	 * @param	int	$uniform_id	Uniform ID
	 * @return	void
	 */
	/**
	 * Handle existing uniform image updates (order, main image, deletions)
	 *
	 * @param	int	$uniform_id	Uniform ID
	 * @return	void
	 */
	private function handleUniformImageUpdates($uniform_id)
	{
		$image_order = $this->input->post('image_order');
		$main_image_id = $this->input->post('main_image_id');
		$deleted_image_ids = $this->input->post('deleted_image_ids');
		
		// Handle deleted images
		if (!empty($deleted_image_ids))
		{
			$deleted_ids = explode(',', $deleted_image_ids);
			$deleted_ids = array_filter(array_map('trim', $deleted_ids));
			
			if (!empty($deleted_ids))
			{
				// Get images to delete files
				$this->db->where('uniform_id', $uniform_id);
				$this->db->where_in('id', $deleted_ids);
				$images_to_delete = $this->db->get('erp_uniform_images')->result_array();
				
				foreach ($images_to_delete as $img)
				{
					$image_path = FCPATH . 'assets/uploads/' . ltrim($img['image_path'], '/');
					if (file_exists($image_path))
					{
						@unlink($image_path);
					}
				}
				
				// Delete from database
				$this->db->where('uniform_id', $uniform_id);
				$this->db->where_in('id', $deleted_ids);
				$this->db->delete('erp_uniform_images');
			}
		}
		
		// Handle main image change (do this first to ensure only one main image)
		if (!empty($main_image_id))
		{
			// First, set all images to not main
			$this->db->where('uniform_id', $uniform_id);
			$this->db->update('erp_uniform_images', array('is_main' => 0));
			
			// Then set the specified image as main
			$this->db->where('id', $main_image_id);
			$this->db->where('uniform_id', $uniform_id);
			$this->db->update('erp_uniform_images', array('is_main' => 1));
		}
		
		// Handle image order
		if (!empty($image_order))
		{
			$image_ids = explode(',', $image_order);
			$image_ids = array_filter(array_map('trim', $image_ids));
			
			// Update image order
			foreach ($image_ids as $order => $image_id)
			{
				$image_id = trim($image_id);
				if (!empty($image_id) && is_numeric($image_id))
				{
					// Check if image belongs to this uniform
					$this->db->where('id', $image_id);
					$this->db->where('uniform_id', $uniform_id);
					$image = $this->db->get('erp_uniform_images')->row_array();
					
					if ($image)
					{
						// Update image order (is_main is already handled above)
						$this->db->where('id', $image_id);
						$this->db->update('erp_uniform_images', array(
							'image_order' => $order
						));
					}
				}
			}
		}
	}
	
	private function handleImageUploads($uniform_id)
	{
		if (!empty($_FILES['images']['name'][0]))
		{
			$upload_path = './assets/uploads/vendors/' . $this->current_vendor['id'] . '/uniforms/images/';
			if (!is_dir($upload_path))
			{
				mkdir($upload_path, 0755, TRUE);
			}
			
			$files = $_FILES['images'];
			$image_order_json = $this->input->post('image_order');
			$image_order = json_decode($image_order_json, TRUE);
			$main_image_index = (int)$this->input->post('main_image_index');
			
			// Get max existing image_order for edit forms (to append new images after existing ones)
			$this->db->select_max('image_order');
			$this->db->where('uniform_id', $uniform_id);
			$max_order_result = $this->db->get('erp_uniform_images')->row_array();
			$start_order = $max_order_result['image_order'] !== NULL ? ($max_order_result['image_order'] + 1) : 0;
			
			// If image_order is not a valid array, use sequential order
			if (!is_array($image_order))
			{
				$image_order = array();
				for ($i = 0; $i < count($files['name']); $i++)
				{
					$image_order[] = $i;
				}
			}
			
			$upload_errors = array();
			$uploaded_count = 0;
			$uploaded_image_ids = array();
			
			foreach ($image_order as $order => $original_index)
			{
				if (isset($files['name'][$original_index]) && $files['error'][$original_index] == 0 && !empty($files['name'][$original_index]))
				{
					// Validate MIME type to ensure it's an image
					$allowed_mimes = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/bmp', 'image/svg+xml', 'image/x-icon', 'image/tiff', 'image/tif', 'image/avif');
					$allowed_extensions = array('jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg', 'ico', 'tiff', 'tif', 'avif');
					$file_mime = $files['type'][$original_index];
					
					// Get file extension as fallback
					$file_ext = strtolower(pathinfo($files['name'][$original_index], PATHINFO_EXTENSION));
					
					// Check both MIME type and file extension (some browsers send incorrect MIME types for AVIF)
					// Accept file if EITHER MIME type OR extension is valid (extension takes precedence for AVIF)
					$mime_valid = in_array($file_mime, $allowed_mimes);
					$ext_valid = in_array($file_ext, $allowed_extensions);
					$is_valid = $mime_valid || $ext_valid;
					
					if (!$is_valid)
					{
						$upload_errors[] = $files['name'][$original_index] . ': Invalid file type. Only image files are allowed.';
						continue;
					}
					
					// Reset $_FILES array for this iteration
					$_FILES['image']['name'] = $files['name'][$original_index];
					$_FILES['image']['type'] = $files['type'][$original_index];
					$_FILES['image']['tmp_name'] = $files['tmp_name'][$original_index];
					$_FILES['image']['error'] = $files['error'][$original_index];
					$_FILES['image']['size'] = $files['size'][$original_index];
					
					$config['upload_path'] = $upload_path;
					$config['allowed_types'] = '*'; // Allow all types - we validate MIME type above
					$config['max_size'] = 5120; // 5MB
					$config['file_name'] = 'uniform_' . $uniform_id . '_' . time() . '_' . $original_index . '.' . $file_ext;
					$config['overwrite'] = FALSE;
					
					$this->upload->initialize($config);
					
					if ($this->upload->do_upload('image'))
					{
						$upload_data = $this->upload->data();
						
						// Calculate final order (for edit forms, append after existing; for add forms, use order from array)
						$final_order = $start_order + $uploaded_count;
						
						// For add forms (start_order == 0), set is_main based on main_image_index
						// For edit forms (start_order > 0), new uploads should not be set as main (handled by handleUniformImageUpdates)
						$is_main = ($start_order == 0 && $order == $main_image_index) ? 1 : 0;
						
						$image_id = $this->Uniform_model->addUniformImage(array(
							'uniform_id' => $uniform_id,
							'image_path' => 'vendors/' . $this->current_vendor['id'] . '/uniforms/images/' . $upload_data['file_name'],
							'image_order' => $final_order,
							'is_main' => $is_main
						));
						
						if ($image_id)
						{
							$uploaded_image_ids[$order] = $image_id;
							$uploaded_count++;
						}
					}
					else
					{
						$error = $this->upload->display_errors('', '');
						$upload_errors[] = $files['name'][$original_index] . ': ' . $error;
						log_message('error', 'Image upload failed: ' . $error);
					}
				}
			}
			
			// For add forms, ensure only the image at main_image_index is marked as main
			if ($start_order == 0 && !empty($uploaded_image_ids) && isset($uploaded_image_ids[$main_image_index]))
			{
				// Set all uploaded images to not main first
				if (!empty($uploaded_image_ids))
				{
					$this->db->where('uniform_id', $uniform_id);
					$this->db->where_in('id', array_values($uploaded_image_ids));
					$this->db->update('erp_uniform_images', array('is_main' => 0));
				}
				
				// Then set the main image
				$this->db->where('id', $uploaded_image_ids[$main_image_index]);
				$this->db->update('erp_uniform_images', array('is_main' => 1));
			}
			
			// Show upload errors if any
			if (!empty($upload_errors))
			{
				$this->session->set_flashdata('error', 'Some images failed to upload: ' . implode(', ', $upload_errors));
			}
		}
	}
	
	/**
	 * Toggle uniform status (AJAX)
	 *
	 * @param	int	$uniform_id	Uniform ID
	 * @return	void
	 */
	public function toggle_status($uniform_id)
	{
		header('Content-Type: application/json');
		
		// Get the uniform
		$uniform = $this->Uniform_model->getUniformById($uniform_id);
		
		if (!$uniform || $uniform['vendor_id'] != $this->current_vendor['id'])
		{
			echo json_encode(array('status' => 'error', 'message' => 'Uniform not found or unauthorized access'));
			return;
		}
		
		// Get the new status from POST data
		$new_status = $this->input->post('status');
		
		// Validate the new status
		if (!in_array($new_status, array('active', 'inactive')))
		{
			echo json_encode(array('status' => 'error', 'message' => 'Invalid status value'));
			return;
		}
		
		// Update the uniform status
		$update_data = array('status' => $new_status);
		$result = $this->Uniform_model->updateUniform($uniform_id, $update_data);
		
		if ($result)
		{
			echo json_encode(array(
				'status' => 'success', 
				'message' => 'Status updated successfully',
				'new_status' => $new_status
			));
		}
		else
		{
			echo json_encode(array('status' => 'error', 'message' => 'Failed to update status'));
		}
	}
}

