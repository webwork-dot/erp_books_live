<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ERP Admin Features Controller
 *
 * Manages features in the ERP system
 *
 * @package		ERP
 * @subpackage	Controllers
 * @category	Controllers
 * @author		ERP Team
 */

// Load base controller
require_once(APPPATH . 'controllers/Erp_admin/Erp_base.php');

class Features extends Erp_base
{
	/**
	 * Constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Erp_feature_model');
		$this->load->library('form_validation');
	}
	
	/**
	 * List all features
	 *
	 * @return	void
	 */
	public function index()
	{
		// Check permission
		if (!$this->hasPermission('features', 'read'))
		{
			show_error('You do not have permission to access this page.', 403);
		}
		
		// Pagination
		$per_page = 10;
		$page = (int)$this->input->get('page');
		if ($page < 1) $page = 1;
		$offset = ($page - 1) * $per_page;
		
		// Get total count for pagination
		$total_features = $this->Erp_feature_model->getTotalFeatures();
		
		// Get features with pagination
		$data['features'] = $this->Erp_feature_model->getAllFeatures(array(), $per_page, $offset);
		$data['main_categories'] = $this->Erp_feature_model->getMainCategories();
		$data['total_features'] = $total_features;
		$data['per_page'] = $per_page;
		$data['current_page'] = $page;
		$data['total_pages'] = ceil($total_features / $per_page);
		$data['title'] = 'Manage Features';
		$data['current_user'] = $this->current_user;
		$data['breadcrumb'] = array(
			array('label' => 'Features', 'active' => true)
		);
		
		// Load content view
		$data['content'] = $this->load->view('erp_admin/features/index', $data, TRUE);
		
		// Load main layout
		$this->load->view('erp_admin/layouts/index_template', $data);
	}
	
	/**
	 * Add new feature
	 *
	 * @return	void
	 */
	public function add()
	{
		// Check permission
		if (!$this->hasPermission('features', 'create'))
		{
			show_error('You do not have permission to access this page.', 403);
		}
		
		// Set validation rules
		$this->form_validation->set_rules('name', 'Feature Name', 'required|trim');
		$this->form_validation->set_rules('slug', 'Slug', 'required|trim|is_unique[erp_features.slug]|alpha_dash');
		$this->form_validation->set_rules('description', 'Description', 'trim');
		$this->form_validation->set_rules('is_active', 'Is Active', 'required|in_list[0,1]');
		$this->form_validation->set_rules('is_school', 'Is School', 'in_list[0,1]');
		$this->form_validation->set_rules('has_variations', 'Has Variations', 'in_list[0,1]');
		$this->form_validation->set_rules('has_size', 'Has Size', 'in_list[0,1]');
		$this->form_validation->set_rules('has_colour', 'Has Colour', 'in_list[0,1]');
		
		if ($this->form_validation->run() == FALSE)
		{
			// Get main categories for parent dropdown
			$data['main_categories'] = $this->Erp_feature_model->getMainCategories();
			
			// Show form
			$data['title'] = 'Add New Feature';
			$data['current_user'] = $this->current_user;
			
			// Load content view
			$data['content'] = $this->load->view('erp_admin/features/add', $data, TRUE);
			
			// Load main layout
			$this->load->view('erp_admin/layouts/index_template', $data);
		}
		else
		{
			// Process form
			$parent_id = $this->input->post('parent_id');
			$slug = $this->input->post('slug');
			
			// If slug is empty, generate from name
			if (empty($slug))
			{
				$slug = $this->generateSlug($this->input->post('name'));
			}
			
			// Ensure slug is unique
			$slug = $this->ensureUniqueSlug($slug);
			
			$feature_data = array(
				'name' => $this->input->post('name'),
				'slug' => $slug,
				'description' => $this->input->post('description'),
				'is_active' => $this->input->post('is_active'),
				'is_school' => $this->input->post('is_school') ? 1 : 0,
				'has_variations' => $this->input->post('has_variations') ? 1 : 0,
				'has_size' => $this->input->post('has_size') ? 1 : 0,
				'has_colour' => $this->input->post('has_colour') ? 1 : 0,
				'parent_id' => (!empty($parent_id)) ? (int)$parent_id : NULL
			);
			
			$feature_id = $this->Erp_feature_model->createFeature($feature_data);
			
			if ($feature_id)
			{
				$this->session->set_flashdata('success', 'Feature created successfully.');
				redirect('erp-admin/features');
			}
			else
			{
				$this->session->set_flashdata('error', 'Failed to create feature.');
				redirect('erp-admin/features/add');
			}
		}
	}
	
	/**
	 * Edit feature
	 *
	 * @param	int	$id	Feature ID
	 * @return	void
	 */
	public function edit($id)
	{
		// Check permission
		if (!$this->hasPermission('features', 'update'))
		{
			show_error('You do not have permission to access this page.', 403);
		}
		
		$feature = $this->Erp_feature_model->getFeatureById($id);
		
		if (!$feature)
		{
			show_404();
		}
		
		// Set validation rules
		$this->form_validation->set_rules('name', 'Feature Name', 'required|trim');
		$this->form_validation->set_rules('slug', 'Slug', 'required|trim|callback_check_slug_unique[' . $id . ']|alpha_dash');
		$this->form_validation->set_rules('description', 'Description', 'trim');
		$this->form_validation->set_rules('is_active', 'Is Active', 'required|in_list[0,1]');
		$this->form_validation->set_rules('is_school', 'Is School', 'in_list[0,1]');
		$this->form_validation->set_rules('has_variations', 'Has Variations', 'in_list[0,1]');
		$this->form_validation->set_rules('has_size', 'Has Size', 'in_list[0,1]');
		$this->form_validation->set_rules('has_colour', 'Has Colour', 'in_list[0,1]');
		
		if ($this->form_validation->run() == FALSE)
		{
			// Get main categories for parent dropdown (exclude current feature and its descendants)
			$data['main_categories'] = $this->Erp_feature_model->getMainCategories();
			// Filter out current feature and any features that have this as parent (to prevent circular references)
			$exclude_ids = array($id);
			$subcategories = $this->Erp_feature_model->getSubcategoriesByParent($id);
			foreach ($subcategories as $subcat)
			{
				$exclude_ids[] = $subcat['id'];
			}
			$data['main_categories'] = array_filter($data['main_categories'], function($cat) use ($exclude_ids) {
				return !in_array($cat['id'], $exclude_ids);
			});
			
			// Show form
			$data['feature'] = $feature;
			$data['title'] = 'Edit Feature';
			$data['current_user'] = $this->current_user;
			
			// Load content view
			$data['content'] = $this->load->view('erp_admin/features/edit', $data, TRUE);
			
			// Load main layout
			$this->load->view('erp_admin/layouts/index_template', $data);
		}
		else
		{
			// Prevent circular reference - can't set parent to itself or its descendants
			$parent_id = $this->input->post('parent_id');
			if (!empty($parent_id) && $parent_id == $id)
			{
				$this->session->set_flashdata('error', 'A feature cannot be its own parent.');
				redirect('erp-admin/features/edit/' . $id);
				return;
			}
			
			// Check if parent is a descendant (would create circular reference)
			if (!empty($parent_id))
			{
				$subcategories = $this->Erp_feature_model->getSubcategoriesByParent($id);
				foreach ($subcategories as $subcat)
				{
					if ($subcat['id'] == $parent_id)
					{
						$this->session->set_flashdata('error', 'Cannot set parent to a sub-category of this feature.');
						redirect('erp-admin/features/edit/' . $id);
						return;
					}
				}
			}
			
			// Process form
			$slug = $this->input->post('slug');
			
			// If slug is empty, generate from name
			if (empty($slug))
			{
				$slug = $this->generateSlug($this->input->post('name'));
			}
			
			// Ensure slug is unique (excluding current feature)
			$slug = $this->ensureUniqueSlug($slug, $id);
			
			$feature_data = array(
				'name' => $this->input->post('name'),
				'slug' => $slug,
				'description' => $this->input->post('description'),
				'is_active' => $this->input->post('is_active'),
				'is_school' => $this->input->post('is_school') ? 1 : 0,
				'has_variations' => $this->input->post('has_variations') ? 1 : 0,
				'has_size' => $this->input->post('has_size') ? 1 : 0,
				'has_colour' => $this->input->post('has_colour') ? 1 : 0,
				'parent_id' => (!empty($parent_id)) ? (int)$parent_id : NULL
			);
			
			$update_result = $this->Erp_feature_model->updateFeature($id, $feature_data);
			
			if ($update_result)
			{
				$this->session->set_flashdata('success', 'Feature updated successfully.');
				redirect('erp-admin/features');
			}
			else
			{
				// Log the error for debugging
				log_message('error', 'Failed to update feature ID: ' . $id . ' with data: ' . print_r($feature_data, TRUE));
				$this->session->set_flashdata('error', 'Failed to update feature. Please check the data and try again.');
				redirect('erp-admin/features/edit/' . $id);
			}
		}
	}
	
	/**
	 * Delete feature
	 *
	 * @param	int	$id	Feature ID
	 * @return	void
	 */
	public function delete($id)
	{
		// Check permission
		if (!$this->hasPermission('features', 'delete'))
		{
			show_error('You do not have permission to access this page.', 403);
		}
		
		if ($this->Erp_feature_model->deleteFeature($id))
		{
			$this->session->set_flashdata('success', 'Feature deleted successfully.');
		}
		else
		{
			$this->session->set_flashdata('error', 'Failed to delete feature.');
		}
		
		redirect('erp-admin/features');
	}
	
	/**
	 * Check slug uniqueness (callback)
	 *
	 * @param	string	$slug	Slug
	 * @param	int	$feature_id	Feature ID (for edit)
	 * @return	bool	TRUE if unique, FALSE otherwise
	 */
	public function check_slug_unique($slug, $feature_id)
	{
		$this->db->where('slug', $slug);
		$this->db->where('id !=', $feature_id);
		$query = $this->db->get('erp_features');
		
		if ($query->num_rows() > 0)
		{
			$this->form_validation->set_message('check_slug_unique', 'The {field} field must contain a unique value.');
			return FALSE;
		}
		
		return TRUE;
	}
	
	/**
	 * Check slug uniqueness via AJAX
	 *
	 * @param	string	$slug	Slug to check
	 * @param	int	$feature_id	Optional feature ID (for edit, to exclude current feature)
	 * @return	void	JSON response
	 */
	public function check_slug($slug, $feature_id = NULL)
	{
		$slug = urldecode($slug);
		
		$this->db->where('slug', $slug);
		if ($feature_id !== NULL)
		{
			$this->db->where('id !=', (int)$feature_id);
		}
		$query = $this->db->get('erp_features');
		
		$available = ($query->num_rows() == 0);
		
		if (!$available)
		{
			// Generate a unique slug suggestion
			$base_slug = $slug;
			$counter = 1;
			$suggested_slug = $base_slug . '-' . $counter;
			
			// Keep trying until we find an available slug
			while ($counter < 100) // Safety limit
			{
				$this->db->where('slug', $suggested_slug);
				if ($feature_id !== NULL)
				{
					$this->db->where('id !=', (int)$feature_id);
				}
				$check_query = $this->db->get('erp_features');
				
				if ($check_query->num_rows() == 0)
				{
					break; // Found available slug
				}
				
				$counter++;
				$suggested_slug = $base_slug . '-' . $counter;
			}
		}
		else
		{
			$suggested_slug = $slug;
		}
		
		header('Content-Type: application/json');
		echo json_encode(array(
			'available' => $available,
			'suggested_slug' => isset($suggested_slug) ? $suggested_slug : $slug
		));
	}
	
	/**
	 * Generate slug from text
	 *
	 * @param	string	$text	Text to convert to slug
	 * @return	string	Generated slug
	 */
	private function generateSlug($text)
	{
		$text = strtolower(trim($text));
		$text = preg_replace('/[^\w\s-]/', '', $text); // Remove special characters
		$text = preg_replace('/[\s_-]+/', '-', $text); // Replace spaces and underscores with hyphens
		$text = preg_replace('/^-+|-+$/', '', $text); // Remove leading/trailing hyphens
		
		return $text;
	}
	
	/**
	 * Ensure slug is unique
	 *
	 * @param	string	$slug	Slug to check
	 * @param	int	$exclude_id	Optional feature ID to exclude (for edit)
	 * @return	string	Unique slug
	 */
	private function ensureUniqueSlug($slug, $exclude_id = NULL)
	{
		$base_slug = $slug;
		$counter = 1;
		
		while ($counter < 100) // Safety limit
		{
			$this->db->where('slug', $slug);
			if ($exclude_id !== NULL)
			{
				$this->db->where('id !=', (int)$exclude_id);
			}
			$query = $this->db->get('erp_features');
			
			if ($query->num_rows() == 0)
			{
				return $slug; // Slug is unique
			}
			
			$counter++;
			$slug = $base_slug . '-' . $counter;
		}
		
		// Fallback: add timestamp if we couldn't find a unique slug
		return $base_slug . '-' . time();
	}
}

