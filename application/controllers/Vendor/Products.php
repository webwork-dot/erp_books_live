<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Vendor Products Controller
 *
 * Handles product feature pages based on enabled features
 *
 * @package		ERP
 * @subpackage	Controllers
 * @category	Controllers
 * @author		ERP Team
 */

// Load base controller
require_once(APPPATH . 'controllers/Vendor/Vendor_base.php');

class Products extends Vendor_base
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
	}
	
	/**
	 * Index - Display feature page based on slug
	 *
	 * @param	string	$feature_slug	Feature slug
	 * @return	void
	 */
	public function index($feature_slug = NULL)
	{
		if (empty($feature_slug))
		{
			show_404();
		}
		
		// Get feature by slug
		$feature = $this->Erp_feature_model->getFeatureBySlug($feature_slug);
		
		if (!$feature)
		{
			show_404();
		}
		
		// Check if feature is enabled for this vendor
		$vendor_features = $this->Erp_client_model->getClientFeatures($this->current_vendor['id']);
		$is_enabled = FALSE;
		
		foreach ($vendor_features as $vendor_feature) {
			if ($vendor_feature['id'] == $feature['id'] && $vendor_feature['is_enabled'] == 1) {
				$is_enabled = TRUE;
				break;
			}
		}
		
		if (!$is_enabled)
		{
			$this->session->set_flashdata('error', 'This feature is not enabled for your account.');
			// Redirect to dashboard (no vendor domain in URL)
			redirect('dashboard');
		}
		
		// Prepare data
		$data['title'] = $feature['name'] . ' - ' . $this->current_vendor['name'];
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $this->getVendorDomainForUrl();
		$data['feature'] = $feature;
		$data['breadcrumb'] = array(
			array('label' => 'Dashboard', 'url' => base_url($this->current_vendor['domain'] . '/dashboard')),
			array('label' => 'Products', 'url' => '#'),
			array('label' => $feature['name'], 'active' => true)
		);
		
		// Load content view
		$data['content'] = $this->load->view('vendor/products/index', $data, TRUE);
		
		// Load main layout
		$this->load->view('vendor/layouts/index_template', $data);
	}
	
	/**
	 * Stationery Index - List all stationery products
	 *
	 * @return	void
	 */
	public function stationery_index()
	{
		// Load models (you'll need to create these models)
		// $this->load->model('Stationery_model');
		// $this->load->model('Category_model');
		
		// Filters
		$filters = array();
		if ($this->input->get('status'))
		{
			$filters['status'] = $this->input->get('status');
		}
		if ($this->input->get('category_id'))
		{
			$filters['category_id'] = $this->input->get('category_id');
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
		// $total_stationery = $this->Stationery_model->getTotalStationeryByVendor($this->current_vendor['id'], $filters);
		$total_stationery = 0; // Placeholder - replace with actual model call
		
		// Get stationery with pagination
		// $data['stationery_list'] = $this->Stationery_model->getStationeryByVendor($this->current_vendor['id'], $filters, $per_page, $offset);
		$data['stationery_list'] = array(); // Placeholder - replace with actual model call
		$data['total_stationery'] = $total_stationery;
		$data['per_page'] = $per_page;
		$data['current_page'] = $page;
		$data['total_pages'] = ceil($total_stationery / $per_page);
		
		// Get categories for filter
		// $data['categories'] = $this->Category_model->getCategoriesByVendor($this->current_vendor['id']);
		$data['categories'] = array(); // Placeholder - replace with actual model call
		
		$data['title'] = 'Manage Stationery';
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $this->getVendorDomainForUrl();
		$data['filters'] = $filters;
		$data['breadcrumb'] = array(
			array('label' => 'Dashboard', 'url' => base_url($this->current_vendor['domain'] . '/dashboard')),
			array('label' => 'Products', 'url' => '#'),
			array('label' => 'Stationery', 'active' => true)
		);
		
		// Load content view
		$data['content'] = $this->load->view('vendor/products/stationery/index', $data, TRUE);
		
		// Load main layout
		$this->load->view('vendor/layouts/index_template', $data);
	}
	
	/**
	 * Individual Products Index - List all individual products across different categories
	 *
	 * @return	void
	 */
	public function individual_products()
	{
		$this->load->model('Individual_product_model');
		$this->load->model('Variation_model');
		
		// Filters
		$filters = array();
		if ($this->input->get('status'))
		{
			$filters['status'] = $this->input->get('status');
		}
		if ($this->input->get('product_type'))
		{
			$filters['product_type'] = $this->input->get('product_type');
		}
		if ($this->input->get('search'))
		{
			$filters['search'] = $this->input->get('search');
		}
		if ($this->input->get('category_id'))
		{
			$filters['category_id'] = $this->input->get('category_id');
		}
		
		// Get categories for filter dropdown
		$data['categories'] = $this->Individual_product_model->getCategoriesByVendor($this->current_vendor['id']);
		
		// Pagination
		$per_page = 10;
		$page = (int)$this->input->get('page');
		if ($page < 1) $page = 1;
		$offset = ($page - 1) * $per_page;
		
		$products_list = array();
		
		// Get individual textbooks - simple query
		if (empty($filters['product_type']) || $filters['product_type'] == 'textbook')
		{
			$this->db->select('t.id, t.product_name, t.status, t.sku, t.isbn, t.mrp, t.selling_price');
			$this->db->from('erp_textbooks t');
			$this->db->where('t.vendor_id', $this->current_vendor['id']);
			$this->db->where('t.is_individual', 1);
			
			// Apply status filter
			if (isset($filters['status']))
			{
				$this->db->where('t.status', $filters['status']);
			}
			
			// Apply search filter
			if (isset($filters['search']) && !empty($filters['search']))
			{
				$this->db->group_start();
				$this->db->like('t.product_name', $filters['search']);
				$this->db->or_like('t.sku', $filters['search']);
				$this->db->or_like('t.isbn', $filters['search']);
				$this->db->group_end();
			}
			
			$textbooks = $this->db->get()->result_array();
		foreach ($textbooks as $textbook)
		{
			$product = array(
				'id' => $textbook['id'],
				'product_name' => $textbook['product_name'],
				'product_type' => 'textbook',
				'status' => isset($textbook['status']) ? $textbook['status'] : 'inactive',
				'image' => '',
				'sku' => '',
				'isbn' => '',
				'mrp' => 0,
				'selling_price' => 0,
				'additional_info' => ''
			);
			
			// Get main image (is_main = 1), fallback to first image if no main image
			$this->db->select('image_path');
			$this->db->from('erp_textbook_images');
			$this->db->where('textbook_id', $textbook['id']);
			$this->db->where('is_main', 1);
			$this->db->limit(1);
			$img_query = $this->db->get();
			if ($img_query->num_rows() > 0) {
				$image_path = $img_query->row()->image_path;
				// Handle different path formats
				if (strpos($image_path, 'assets/uploads/') === 0) {
					$product['image'] = $image_path;
				} elseif (strpos($image_path, 'uploads/') === 0) {
					$product['image'] = 'assets/' . $image_path;
				} else {
					$product['image'] = 'assets/uploads/' . ltrim($image_path, '/');
				}
			} else {
				// If no main image found, use first image by order
				$this->db->select('image_path');
				$this->db->from('erp_textbook_images');
				$this->db->where('textbook_id', $textbook['id']);
				$this->db->order_by('image_order', 'ASC');
				$this->db->limit(1);
				$img_query = $this->db->get();
				if ($img_query->num_rows() > 0) {
					$image_path = $img_query->row()->image_path;
					// Handle different path formats
					if (strpos($image_path, 'assets/uploads/') === 0) {
						$product['image'] = $image_path;
					} elseif (strpos($image_path, 'uploads/') === 0) {
						$product['image'] = 'assets/' . $image_path;
					} else {
						$product['image'] = 'assets/uploads/' . ltrim($image_path, '/');
					}
				}
			}
			
			// Use data from query
			$product['sku'] = isset($textbook['sku']) ? $textbook['sku'] : '';
			$product['isbn'] = isset($textbook['isbn']) ? $textbook['isbn'] : '';
			$product['mrp'] = isset($textbook['mrp']) ? $textbook['mrp'] : 0;
			$product['selling_price'] = isset($textbook['selling_price']) ? $textbook['selling_price'] : 0;
			
			$products_list[] = $product;
		}
		}
		
		// Get individual notebooks - simple query
		if (empty($filters['product_type']) || $filters['product_type'] == 'notebook')
		{
			$this->db->select('n.id, n.product_name, n.status, n.sku, n.isbn, n.mrp, n.selling_price');
			$this->db->from('erp_notebooks n');
			$this->db->where('n.vendor_id', $this->current_vendor['id']);
			$this->db->where('n.is_individual', 1);
			
			// Apply status filter
			if (isset($filters['status']))
			{
				$this->db->where('n.status', $filters['status']);
			}
			
			// Apply search filter
			if (isset($filters['search']) && !empty($filters['search']))
			{
				$this->db->group_start();
				$this->db->like('n.product_name', $filters['search']);
				$this->db->or_like('n.sku', $filters['search']);
				$this->db->or_like('n.isbn', $filters['search']);
				$this->db->group_end();
			}
			
			$notebooks = $this->db->get()->result_array();
		foreach ($notebooks as $notebook)
		{
			$product = array(
				'id' => $notebook['id'],
				'product_name' => $notebook['product_name'],
				'product_type' => 'notebook',
				'status' => isset($notebook['status']) ? $notebook['status'] : 'inactive',
				'image' => '',
				'sku' => '',
				'isbn' => '',
				'mrp' => 0,
				'selling_price' => 0,
				'additional_info' => ''
			);
			
			// Get main image (is_main = 1), fallback to first image if no main image
			$this->db->select('image_path');
			$this->db->from('erp_notebook_images');
			$this->db->where('notebook_id', $notebook['id']);
			$this->db->where('is_main', 1);
			$this->db->limit(1);
			$img_query = $this->db->get();
			if ($img_query->num_rows() > 0) {
				$image_path = $img_query->row()->image_path;
				// Handle different path formats
				if (strpos($image_path, 'assets/uploads/') === 0) {
					$product['image'] = $image_path;
				} elseif (strpos($image_path, 'vendors/') === 0) {
					$product['image'] = 'assets/uploads/' . $image_path;
				} else {
					$product['image'] = 'assets/uploads/' . ltrim($image_path, '/');
				}
			} else {
				// If no main image found, use first image by order
				$this->db->select('image_path');
				$this->db->from('erp_notebook_images');
				$this->db->where('notebook_id', $notebook['id']);
				$this->db->order_by('image_order', 'ASC');
				$this->db->limit(1);
				$img_query = $this->db->get();
				if ($img_query->num_rows() > 0) {
					$image_path = $img_query->row()->image_path;
					// Handle different path formats
					if (strpos($image_path, 'assets/uploads/') === 0) {
						$product['image'] = $image_path;
					} elseif (strpos($image_path, 'vendors/') === 0) {
						$product['image'] = 'assets/uploads/' . $image_path;
					} else {
						$product['image'] = 'assets/uploads/' . ltrim($image_path, '/');
					}
				}
			}
			
			// Use data from query
			$product['sku'] = isset($notebook['sku']) ? $notebook['sku'] : '';
			$product['isbn'] = isset($notebook['isbn']) ? $notebook['isbn'] : '';
			$product['mrp'] = isset($notebook['mrp']) ? $notebook['mrp'] : 0;
			$product['selling_price'] = isset($notebook['selling_price']) ? $notebook['selling_price'] : 0;
			
			$products_list[] = $product;
		}
		}
		
		// Get individual stationery - simple query
		if (empty($filters['product_type']) || $filters['product_type'] == 'stationery')
		{
			$this->db->select('s.id, s.product_name, s.status, s.sku, s.mrp, s.selling_price');
			$this->db->from('erp_stationery s');
			$this->db->where('s.vendor_id', $this->current_vendor['id']);
			$this->db->where('s.is_individual', 1);
			
			// Apply status filter
			if (isset($filters['status']))
			{
				$this->db->where('s.status', $filters['status']);
			}
			
			// Apply search filter
			if (isset($filters['search']) && !empty($filters['search']))
			{
				$this->db->group_start();
				$this->db->like('s.product_name', $filters['search']);
				$this->db->or_like('s.sku', $filters['search']);
				$this->db->group_end();
			}
			
			$stationery = $this->db->get()->result_array();
		foreach ($stationery as $item)
		{
			$product = array(
				'id' => $item['id'],
				'product_name' => $item['product_name'],
				'product_type' => 'stationery',
				'status' => isset($item['status']) ? $item['status'] : 'inactive',
				'image' => '',
				'sku' => '',
				'isbn' => '',
				'mrp' => 0,
				'selling_price' => 0,
				'additional_info' => ''
			);
			
			// Get main image (is_main = 1), fallback to first image if no main image
			$this->db->select('image_path');
			$this->db->from('erp_stationery_images');
			$this->db->where('stationery_id', $item['id']);
			$this->db->where('is_main', 1);
			$this->db->limit(1);
			$img_query = $this->db->get();
			if ($img_query->num_rows() > 0) {
				$image_path = $img_query->row()->image_path;
				// Handle different path formats
				if (strpos($image_path, 'assets/uploads/') === 0) {
					$product['image'] = $image_path;
				} elseif (strpos($image_path, 'vendors/') === 0) {
					$product['image'] = 'assets/uploads/' . $image_path;
				} else {
					$product['image'] = 'assets/uploads/' . ltrim($image_path, '/');
				}
			} else {
				// If no main image found, use first image by order
				$this->db->select('image_path');
				$this->db->from('erp_stationery_images');
				$this->db->where('stationery_id', $item['id']);
				$this->db->order_by('image_order', 'ASC');
				$this->db->limit(1);
				$img_query = $this->db->get();
				if ($img_query->num_rows() > 0) {
					$image_path = $img_query->row()->image_path;
					// Handle different path formats
					if (strpos($image_path, 'assets/uploads/') === 0) {
						$product['image'] = $image_path;
					} elseif (strpos($image_path, 'vendors/') === 0) {
						$product['image'] = 'assets/uploads/' . $image_path;
					} else {
						$product['image'] = 'assets/uploads/' . ltrim($image_path, '/');
					}
				}
			}
			
			// Use data from query
			$product['sku'] = isset($item['sku']) ? $item['sku'] : '';
			$product['mrp'] = isset($item['mrp']) ? $item['mrp'] : 0;
			$product['selling_price'] = isset($item['selling_price']) ? $item['selling_price'] : 0;
			
			$products_list[] = $product;
		}
		}
		
		// Get individual uniforms - simple query (only basic fields)
		if (empty($filters['product_type']) || $filters['product_type'] == 'uniform')
		{
			$this->db->select('u.id, u.product_name, u.status, u.price');
			$this->db->from('erp_uniforms u');
			$this->db->where('u.vendor_id', $this->current_vendor['id']);
			$this->db->where('u.is_individual', 1);
			
			// Apply status filter
			if (isset($filters['status']))
			{
				$this->db->where('u.status', $filters['status']);
			}
			
			// Apply search filter
			if (isset($filters['search']) && !empty($filters['search']))
			{
				$this->db->like('u.product_name', $filters['search']);
			}
			
			$uniforms = $this->db->get()->result_array();
		foreach ($uniforms as $uniform)
		{
			$product = array(
				'id' => $uniform['id'],
				'product_name' => $uniform['product_name'],
				'product_type' => 'uniform',
				'status' => isset($uniform['status']) ? $uniform['status'] : 'inactive',
				'image' => '',
				'sku' => '',
				'isbn' => '',
				'mrp' => 0,
				'selling_price' => 0,
				'additional_info' => ''
			);
			
			// Get main image (is_main = 1), fallback to first image if no main image
			$this->db->select('image_path');
			$this->db->from('erp_uniform_images');
			$this->db->where('uniform_id', $uniform['id']);
			$this->db->where('is_main', 1);
			$this->db->limit(1);
			$img_query = $this->db->get();
			if ($img_query->num_rows() > 0) {
				$image_path = $img_query->row()->image_path;
				// Handle different path formats
				if (strpos($image_path, 'assets/uploads/') === 0) {
					$product['image'] = $image_path;
				} elseif (strpos($image_path, 'vendors/') === 0) {
					$product['image'] = 'assets/uploads/' . $image_path;
				} else {
					$product['image'] = 'assets/uploads/' . ltrim($image_path, '/');
				}
			} else {
				// If no main image found, use first image by order
				$this->db->select('image_path');
				$this->db->from('erp_uniform_images');
				$this->db->where('uniform_id', $uniform['id']);
				$this->db->order_by('image_order', 'ASC');
				$this->db->limit(1);
				$img_query = $this->db->get();
				if ($img_query->num_rows() > 0) {
					$image_path = $img_query->row()->image_path;
					// Handle different path formats
					if (strpos($image_path, 'assets/uploads/') === 0) {
						$product['image'] = $image_path;
					} elseif (strpos($image_path, 'vendors/') === 0) {
						$product['image'] = 'assets/uploads/' . $image_path;
					} else {
						$product['image'] = 'assets/uploads/' . ltrim($image_path, '/');
					}
				}
			}
			
			// Use data from query (uniforms use 'price' not 'mrp' or 'selling_price')
			if (isset($uniform['price'])) {
				$product['selling_price'] = $uniform['price'];
				$product['mrp'] = $uniform['price'];
			}
			
			$products_list[] = $product;
		}
		}
		
		// Get individual products from erp_individual_products table
		if (empty($filters['product_type']) || $filters['product_type'] == 'individual')
		{
			$individual_filters = array();
			if (isset($filters['status']))
			{
				$individual_filters['status'] = $filters['status'];
			}
			if (isset($filters['search']))
			{
				$individual_filters['search'] = $filters['search'];
			}
			if (isset($filters['category_id']))
			{
				$individual_filters['category_id'] = $filters['category_id'];
			}
			
			$individual_products = $this->Individual_product_model->getProductsByVendor($this->current_vendor['id'], $individual_filters);
			
			foreach ($individual_products as $ind_product)
			{
				$product = array(
					'id' => $ind_product['id'],
					'product_name' => $ind_product['product_name'],
					'product_type' => 'individual',
					'status' => isset($ind_product['status']) ? $ind_product['status'] : 'inactive',
					'image' => '',
					'sku' => isset($ind_product['sku']) ? $ind_product['sku'] : '',
					'isbn' => isset($ind_product['isbn']) ? $ind_product['isbn'] : '',
					'mrp' => isset($ind_product['mrp']) ? $ind_product['mrp'] : 0,
					'selling_price' => isset($ind_product['selling_price']) ? $ind_product['selling_price'] : 0,
					'additional_info' => ''
				);
				
				// Get main image
				$images = $this->Individual_product_model->getProductImages($ind_product['id']);
				if (!empty($images))
				{
					foreach ($images as $img)
					{
						if (isset($img['is_main']) && $img['is_main'] == 1)
						{
							$image_path = $img['image_path'];
							// Handle different path formats
							if (strpos($image_path, 'assets/uploads/') === 0) {
								$product['image'] = $image_path;
							} elseif (strpos($image_path, 'vendors/') === 0) {
								$product['image'] = 'assets/uploads/' . $image_path;
							} else {
								$product['image'] = 'assets/uploads/' . ltrim($image_path, '/');
							}
							break;
						}
					}
					// If no main image, use first image
					if (empty($product['image']) && !empty($images[0]))
					{
						$image_path = $images[0]['image_path'];
						// Handle different path formats
						if (strpos($image_path, 'assets/uploads/') === 0) {
							$product['image'] = $image_path;
						} elseif (strpos($image_path, 'vendors/') === 0) {
							$product['image'] = 'assets/uploads/' . $image_path;
						} else {
							$product['image'] = 'assets/uploads/' . ltrim($image_path, '/');
						}
					}
				}
				
				// Get categories
				$categories = $this->Individual_product_model->getProductCategories($ind_product['id']);
				if (!empty($categories))
				{
					$category_names = array();
					foreach ($categories as $cat)
					{
						$category_names[] = $cat['name'];
					}
					$product['categories'] = $category_names;
					$product['category_display'] = implode(', ', $category_names);
				}
				else
				{
					$product['categories'] = array();
					$product['category_display'] = '';
				}
				
				// Get variation types and combinations for individual products
				$variation_types = $this->Variation_model->getProductVariationTypes($ind_product['id']);
				if (!empty($variation_types))
				{
					$variation_type_names = array();
					foreach ($variation_types as $vt)
					{
						$variation_type_names[] = $vt['name'];
					}
					$product['variation_types'] = $variation_type_names;
					$product['variation_types_display'] = implode(', ', $variation_type_names);
					
					// Get combinations count
					$combinations = $this->Variation_model->getProductCombinations($ind_product['id']);
					$product['variation_combinations_count'] = count($combinations);
					
					// Calculate price range from combinations
					if (!empty($combinations))
					{
						$mrp_values = array();
						$selling_price_values = array();
						foreach ($combinations as $combo)
						{
							if (isset($combo['mrp']) && $combo['mrp'] > 0) $mrp_values[] = (float)$combo['mrp'];
							if (isset($combo['selling_price']) && $combo['selling_price'] > 0) $selling_price_values[] = (float)$combo['selling_price'];
						}
						if (!empty($mrp_values))
						{
							$product['min_mrp'] = min($mrp_values);
							$product['max_mrp'] = max($mrp_values);
						}
						if (!empty($selling_price_values))
						{
							$product['min_selling_price'] = min($selling_price_values);
							$product['max_selling_price'] = max($selling_price_values);
						}
					}
					$product['has_variations'] = true;
				}
				else
				{
					$product['variation_types'] = array();
					$product['variation_types_display'] = '';
					$product['variation_combinations_count'] = 0;
					$product['has_variations'] = false;
				}
				
				$products_list[] = $product;
			}
		}
		
		// Sort by product name
		usort($products_list, function($a, $b) {
			return strcmp($a['product_name'], $b['product_name']);
		});
		
		// Get total count
		$total_products = count($products_list);
		
		// Apply pagination
		$products_list = array_slice($products_list, $offset, $per_page);
		
		$data['products_list'] = $products_list;
		$data['total_products'] = $total_products;
		$data['per_page'] = $per_page;
		$data['current_page'] = $page;
		$data['total_pages'] = ceil($total_products / $per_page);
		$data['filters'] = $filters;
		
		$data['title'] = 'Manage Individual Products';
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $this->getVendorDomainForUrl();
		$data['breadcrumb'] = array(
			array('label' => 'Dashboard', 'url' => base_url($this->current_vendor['domain'] . '/dashboard')),
			array('label' => 'Products', 'url' => '#'),
			array('label' => 'Individual Products', 'active' => true)
		);
		
		// Load content view
		$data['content'] = $this->load->view('vendor/products/individual-products/index', $data, TRUE);
		
		// Load main layout
		$this->load->view('vendor/layouts/index_template', $data);
	}
	
	/**
	 * Individual Products Add - Show add form
	 *
	 * @return	void
	 */
	public function individual_products_add()
	{
		$this->load->model('Individual_product_model');
		$this->load->model('Variation_model');
		
		// Get parent categories (categories without parent)
		$data['parent_categories'] = $this->Individual_product_model->getParentCategoriesByVendor($this->current_vendor['id']);
		
		// Get all categories (for subcategory dropdown)
		$data['categories'] = $this->Individual_product_model->getCategoriesByVendor($this->current_vendor['id']);
		
		// Get variation types for the new flexible system
		$data['variation_types'] = $this->Variation_model->getVariationTypesByVendor($this->current_vendor['id']);
		
		$data['title'] = 'Add Individual Product';
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $this->getVendorDomainForUrl();
		$data['breadcrumb'] = array(
			array('label' => 'Dashboard', 'url' => base_url($this->current_vendor['domain'] . '/dashboard')),
			array('label' => 'Products', 'url' => '#'),
			array('label' => 'Individual Products', 'url' => base_url($this->current_vendor['domain'] . '/products/individual-products')),
			array('label' => 'Add Product', 'active' => true)
		);
		
		// Load content view
		$data['content'] = $this->load->view('vendor/products/individual-products/add', $data, TRUE);
		
		// Load main layout
		$this->load->view('vendor/layouts/index_template', $data);
	}
	
	/**
	 * Individual Products Save - Save product
	 *
	 * @return	void
	 */
	public function individual_products_save()
	{
		$this->load->library('form_validation');
		$this->load->model('Individual_product_model');
		$this->load->model('Variation_model');
		
		// Validation rules
		$this->form_validation->set_rules('product_name', 'Product Name', 'required|trim');
		$this->form_validation->set_rules('category_id', 'Category', 'required|integer');
		$this->form_validation->set_rules('subcategory_id', 'Subcategory', 'integer');
		$this->form_validation->set_rules('min_quantity', 'Min Quantity', 'required|integer|greater_than[0]');
		$this->form_validation->set_rules('product_origin', 'Product Origin', 'required|trim');
		$this->form_validation->set_rules('product_description', 'Product Description', 'required|trim');
		$this->form_validation->set_rules('gst_percentage', 'GST (%)', 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]');
		
		// Check if using variations
		$variation_type_ids = $this->input->post('variation_type_ids');
		$has_variations = !empty($variation_type_ids) && is_array($variation_type_ids) && count($variation_type_ids) > 0;
		
		// Price validation - if no variations, require base price
		if (!$has_variations)
		{
			$this->form_validation->set_rules('mrp', 'MRP', 'required|numeric|greater_than_equal_to[0]');
			$this->form_validation->set_rules('selling_price', 'Selling Price', 'required|numeric|greater_than_equal_to[0]|callback_validate_selling_price_less_than_mrp');
		}
		
		if ($this->form_validation->run() == FALSE)
		{
			// Reload form with errors
			$this->individual_products_add();
			return;
		}
		
		// Manual validation: selling price must be less than MRP
		$mrp = $this->input->post('mrp');
		$selling_price = $this->input->post('selling_price');
		if (!$has_variations && !empty($mrp) && !empty($selling_price) && $selling_price >= $mrp)
		{
			$this->session->set_flashdata('error', 'Selling price must be less than MRP.');
			redirect($this->current_vendor['domain'] . '/products/individual-products/add');
			return;
		}
		
		// Check SKU uniqueness if provided
		$sku = $this->input->post('sku');
		if (!empty($sku))
		{
			if ($this->Individual_product_model->checkSkuExists($sku, $this->current_vendor['id']))
			{
				$this->session->set_flashdata('error', 'SKU already exists. Please use a different SKU.');
				redirect($this->current_vendor['domain'] . '/products/individual-products/add');
				return;
			}
		}
		
		// Prepare product data
		$product_data = array(
			'vendor_id' => $this->current_vendor['id'],
			'product_name' => $this->input->post('product_name'),
			'display_name' => $this->input->post('display_name'),
			'isbn' => $this->input->post('isbn'),
			'barcode' => $this->input->post('barcode'),
			'sku' => $sku,
			'min_quantity' => $this->input->post('min_quantity'),
			'days_to_exchange' => $this->input->post('days_to_exchange') ? $this->input->post('days_to_exchange') : NULL,
			'product_origin' => $this->input->post('product_origin'),
			'product_description' => $this->input->post('product_description'),
			'packaging_length' => $this->input->post('packaging_length') ? $this->input->post('packaging_length') : NULL,
			'packaging_width' => $this->input->post('packaging_width') ? $this->input->post('packaging_width') : NULL,
			'packaging_height' => $this->input->post('packaging_height') ? $this->input->post('packaging_height') : NULL,
			'packaging_weight' => $this->input->post('packaging_weight') ? $this->input->post('packaging_weight') : NULL,
			'gst_percentage' => $this->input->post('gst_percentage'),
			'hsn' => $this->input->post('hsn'),
			'mrp' => $has_variations ? 0.00 : $this->input->post('mrp'),
			'selling_price' => $has_variations ? 0.00 : $this->input->post('selling_price'),
			'meta_title' => $this->input->post('meta_title'),
			'meta_keywords' => $this->input->post('meta_keywords'),
			'meta_description' => $this->input->post('meta_description'),
			'variation_type' => 'custom', // Mark as using custom variation system
			'status' => 'active'
		);
		
		// Create product
		$product_id = $this->Individual_product_model->createProduct($product_data);
		
		if ($product_id)
		{
			// Add categories - handle both category_id and subcategory_id
			$category_ids = array();
			
			// Add main category if selected
			$category_id = $this->input->post('category_id');
			if (!empty($category_id))
			{
				$category_ids[] = $category_id;
			}
			
			// Add subcategory if selected
			$subcategory_id = $this->input->post('subcategory_id');
			if (!empty($subcategory_id))
			{
				$category_ids[] = $subcategory_id;
			}
			
			if (!empty($category_ids))
			{
				$this->Individual_product_model->addProductCategories($product_id, $category_ids);
			}
			
			// Handle flexible variation system
			if ($has_variations)
			{
				// Add variation types to product
				$this->Variation_model->addProductVariationTypes($product_id, $variation_type_ids);
				
				// Get and save combinations with pricing
				$combinations_json = $this->input->post('variation_combinations');
				if (!empty($combinations_json))
				{
					$combinations = json_decode($combinations_json, TRUE);
					if (is_array($combinations) && !empty($combinations))
					{
						// Format combinations for saving
						$formatted_combinations = array();
						foreach ($combinations as $combo)
						{
							if (isset($combo['key']) && isset($combo['values']))
							{
								$formatted_combinations[] = array(
									'key' => $combo['key'],
									'values' => $combo['values'],
									'data' => isset($combo['data']) ? $combo['data'] : array(),
									'mrp' => isset($combo['mrp']) ? (float)$combo['mrp'] : 0.00,
									'selling_price' => isset($combo['selling_price']) ? (float)$combo['selling_price'] : 0.00,
									'stock_quantity' => isset($combo['stock_quantity']) ? (int)$combo['stock_quantity'] : NULL,
									'sku' => isset($combo['sku']) ? $combo['sku'] : NULL
								);
							}
						}
						
						if (!empty($formatted_combinations))
						{
							$this->Variation_model->saveProductCombinations($product_id, $formatted_combinations);
						}
					}
				}
			}
			
			// Handle image uploads
			$this->handleIndividualProductImageUploads($product_id);
			
			$this->session->set_flashdata('success', 'Product added successfully.');
			redirect($this->current_vendor['domain'] . '/products/individual-products');
		}
		else
		{
			$this->session->set_flashdata('error', 'Failed to add product. Please try again.');
			redirect($this->current_vendor['domain'] . '/products/individual-products/add');
		}
	}
	
	/**
	 * Individual Products Edit - Show edit form
	 *
	 * @param	int	$id	Product ID
	 * @return	void
	 */
	public function individual_products_edit($id = NULL)
	{
		if (empty($id))
		{
			show_404();
		}
		
		$this->load->model('Individual_product_model');
		$this->load->model('Variation_model');
		
		// Get product data - check all tables since products can come from different sources
		$product = NULL;
		$product_type = NULL;
		
		// First check erp_individual_products table
		$product = $this->Individual_product_model->getProductById($id);
		if ($product && $product['vendor_id'] == $this->current_vendor['id'])
		{
			$product_type = 'individual';
		}
		else
		{
			// Check textbooks table
			$this->db->where('id', $id);
			$this->db->where('vendor_id', $this->current_vendor['id']);
			$this->db->where('is_individual', 1);
			$textbook = $this->db->get('erp_textbooks')->row_array();
			if ($textbook)
			{
				$product = $textbook;
				$product_type = 'textbook';
			}
			
			// Check notebooks table
			if (!$product)
			{
				$this->db->where('id', $id);
				$this->db->where('vendor_id', $this->current_vendor['id']);
				$this->db->where('is_individual', 1);
				$notebook = $this->db->get('erp_notebooks')->row_array();
				if ($notebook)
				{
					$product = $notebook;
					$product_type = 'notebook';
				}
			}
			
			// Check stationery table
			if (!$product)
			{
				$this->db->where('id', $id);
				$this->db->where('vendor_id', $this->current_vendor['id']);
				$this->db->where('is_individual', 1);
				$stationery = $this->db->get('erp_stationery')->row_array();
				if ($stationery)
				{
					$product = $stationery;
					$product_type = 'stationery';
				}
			}
			
			// Check uniforms table
			if (!$product)
			{
				$this->db->where('id', $id);
				$this->db->where('vendor_id', $this->current_vendor['id']);
				$this->db->where('is_individual', 1);
				$uniform = $this->db->get('erp_uniforms')->row_array();
				if ($uniform)
				{
					$product = $uniform;
					$product_type = 'uniform';
				}
			}
		}
		
		if (!$product)
		{
			show_404();
		}
		
		// If product is from another table (not erp_individual_products), redirect to appropriate edit page
		if ($product_type && $product_type != 'individual')
		{
			switch ($product_type)
			{
				case 'textbook':
					redirect($this->current_vendor['domain'] . '/products/textbook/edit/' . $id);
					break;
				case 'notebook':
					redirect($this->current_vendor['domain'] . '/products/notebook/edit/' . $id);
					break;
				case 'stationery':
					redirect($this->current_vendor['domain'] . '/products/stationery/edit/' . $id);
					break;
				case 'uniform':
					redirect($this->current_vendor['domain'] . '/products/uniforms/edit/' . $id);
					break;
			}
			return;
		}
		
		// Get parent categories
		$data['parent_categories'] = $this->Individual_product_model->getParentCategoriesByVendor($this->current_vendor['id']);
		
		// Get all categories (for subcategory dropdown)
		$data['categories'] = $this->Individual_product_model->getCategoriesByVendor($this->current_vendor['id']);
		
		// Get product categories
		$product_categories = $this->Individual_product_model->getProductCategories($id);
		$data['selected_category_id'] = NULL;
		$data['selected_subcategory_id'] = NULL;
		
		foreach ($product_categories as $cat)
		{
			if (empty($cat['parent_id']))
			{
				$data['selected_category_id'] = $cat['id'];
			}
			else
			{
				$data['selected_subcategory_id'] = $cat['id'];
			}
		}
		
		// Get subcategories for selected category
		$data['subcategories'] = array();
		if ($data['selected_category_id'])
		{
			$data['subcategories'] = $this->Individual_product_model->getSubcategoriesByParent($data['selected_category_id']);
		}
		
		// Get variation types
		$data['variation_types'] = $this->Variation_model->getVariationTypesByVendor($this->current_vendor['id']);
		
		// Get product variation types
		$product_variation_types = $this->Variation_model->getProductVariationTypes($id);
		$data['selected_variation_type_ids'] = array();
		foreach ($product_variation_types as $type)
		{
			$data['selected_variation_type_ids'][] = $type['id'];
		}
		
		// Get product combinations
		$data['product_combinations'] = array();
		if (!empty($data['selected_variation_type_ids']))
		{
			$combinations = $this->Variation_model->getProductCombinations($id);
			
			// Debug: Log raw combinations
			log_message('debug', 'Product ID: ' . $id . ' - Found ' . count($combinations) . ' combinations');
			
			foreach ($combinations as $combo)
			{
				// Format for frontend
				$formatted_combo = array(
					'id' => isset($combo['id']) ? $combo['id'] : NULL,
					'key' => isset($combo['combination_key']) ? $combo['combination_key'] : '',
					'data' => !empty($combo['combination_data']) ? json_decode($combo['combination_data'], TRUE) : array(),
					'values' => array(),
					'mrp' => isset($combo['mrp']) ? (float)$combo['mrp'] : 0.00,
					'selling_price' => isset($combo['selling_price']) ? (float)$combo['selling_price'] : 0.00,
					'stock_quantity' => isset($combo['stock_quantity']) ? (int)$combo['stock_quantity'] : NULL,
					'sku' => isset($combo['sku']) ? $combo['sku'] : NULL
				);
				
				// Get values for this combination
				if (isset($combo['values']) && is_array($combo['values']))
				{
					foreach ($combo['values'] as $value)
					{
						$formatted_combo['values'][] = array(
							'type_id' => isset($value['variation_type_id']) ? $value['variation_type_id'] : 0,
							'type_name' => isset($value['type_name']) ? $value['type_name'] : '',
							'value_id' => isset($value['variation_value_id']) ? $value['variation_value_id'] : 0,
							'value_name' => isset($value['value_name']) ? $value['value_name'] : ''
						);
					}
				}
				
				$data['product_combinations'][] = $formatted_combo;
			}
			
			// Debug: Log formatted combinations
			log_message('debug', 'Formatted combinations: ' . json_encode($data['product_combinations']));
		}
		
		// Get product images
		$data['product_images'] = $this->Individual_product_model->getProductImages($id);
		
		$data['product'] = $product;
		$data['title'] = 'Edit Individual Product';
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $this->getVendorDomainForUrl();
		$data['breadcrumb'] = array(
			array('label' => 'Dashboard', 'url' => base_url($this->current_vendor['domain'] . '/dashboard')),
			array('label' => 'Products', 'url' => '#'),
			array('label' => 'Individual Products', 'url' => base_url($this->current_vendor['domain'] . '/products/individual-products')),
			array('label' => 'Edit Product', 'active' => true)
		);
		
		// Load content view
		$data['content'] = $this->load->view('vendor/products/individual-products/edit', $data, TRUE);
		
		// Load main layout
		$this->load->view('vendor/layouts/index_template', $data);
	}
	
	/**
	 * Individual Products Update - Update product
	 *
	 * @param	int	$id	Product ID
	 * @return	void
	 */
	public function individual_products_update($id = NULL)
	{
		if (empty($id))
		{
			show_404();
		}
		
		$this->load->library('form_validation');
		$this->load->model('Individual_product_model');
		$this->load->model('Variation_model');
		
		// Verify product exists and belongs to vendor
		$product = $this->Individual_product_model->getProductById($id);
		if (!$product || $product['vendor_id'] != $this->current_vendor['id'])
		{
			show_404();
		}
		
		// Validation rules
		$this->form_validation->set_rules('product_name', 'Product Name', 'required|trim');
		$this->form_validation->set_rules('category_id', 'Category', 'required|integer');
		$this->form_validation->set_rules('subcategory_id', 'Subcategory', 'integer');
		$this->form_validation->set_rules('min_quantity', 'Min Quantity', 'required|integer|greater_than[0]');
		$this->form_validation->set_rules('product_origin', 'Product Origin', 'required|trim');
		$this->form_validation->set_rules('product_description', 'Product Description', 'required|trim');
		$this->form_validation->set_rules('gst_percentage', 'GST (%)', 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]');
		
		// Check if using variations
		$variation_type_ids = $this->input->post('variation_type_ids');
		$has_variations = !empty($variation_type_ids) && is_array($variation_type_ids) && count($variation_type_ids) > 0;
		
		// Price validation - if no variations, require base price
		if (!$has_variations)
		{
			$this->form_validation->set_rules('mrp', 'MRP', 'required|numeric|greater_than_equal_to[0]');
			$this->form_validation->set_rules('selling_price', 'Selling Price', 'required|numeric|greater_than_equal_to[0]|callback_validate_selling_price_less_than_mrp');
			
			// Manual validation: selling price must be less than MRP (backup check)
			$mrp = $this->input->post('mrp');
			$selling_price = $this->input->post('selling_price');
			if (!empty($mrp) && !empty($selling_price) && $selling_price >= $mrp)
			{
				$this->form_validation->set_message('selling_price', 'Selling price must be less than MRP.');
				$this->form_validation->set_rules('selling_price', 'Selling Price', 'callback_validate_selling_price_less_than_mrp');
			}
		}
		
		if ($this->form_validation->run() == FALSE)
		{
			// Reload form with errors
			$this->individual_products_edit($id);
			return;
		}
		
		// Check SKU uniqueness if provided
		$sku = $this->input->post('sku');
		if (!empty($sku))
		{
			if ($this->Individual_product_model->checkSkuExists($sku, $this->current_vendor['id'], $id))
			{
				$this->session->set_flashdata('error', 'SKU already exists. Please use a different SKU.');
				redirect($this->current_vendor['domain'] . '/products/individual-products/edit/' . $id);
				return;
			}
		}
		
		// Prepare product data
		$product_data = array(
			'product_name' => $this->input->post('product_name'),
			'display_name' => $this->input->post('display_name'),
			'isbn' => $this->input->post('isbn'),
			'barcode' => $this->input->post('barcode'),
			'sku' => $sku,
			'min_quantity' => $this->input->post('min_quantity'),
			'days_to_exchange' => $this->input->post('days_to_exchange') ? $this->input->post('days_to_exchange') : NULL,
			'product_origin' => $this->input->post('product_origin'),
			'product_description' => $this->input->post('product_description'),
			'packaging_length' => $this->input->post('packaging_length') ? $this->input->post('packaging_length') : NULL,
			'packaging_width' => $this->input->post('packaging_width') ? $this->input->post('packaging_width') : NULL,
			'packaging_height' => $this->input->post('packaging_height') ? $this->input->post('packaging_height') : NULL,
			'packaging_weight' => $this->input->post('packaging_weight') ? $this->input->post('packaging_weight') : NULL,
			'gst_percentage' => $this->input->post('gst_percentage'),
			'hsn' => $this->input->post('hsn'),
			'mrp' => $has_variations ? 0.00 : $this->input->post('mrp'),
			'selling_price' => $has_variations ? 0.00 : $this->input->post('selling_price'),
			'meta_title' => $this->input->post('meta_title'),
			'meta_keywords' => $this->input->post('meta_keywords'),
			'meta_description' => $this->input->post('meta_description'),
			'variation_type' => 'custom',
			'updated_at' => date('Y-m-d H:i:s')
		);
		
		// Update product
		$updated = $this->Individual_product_model->updateProduct($id, $product_data);
		
		if ($updated)
		{
			// Update categories
			$category_ids = array();
			
			$category_id = $this->input->post('category_id');
			if (!empty($category_id))
			{
				$category_ids[] = $category_id;
			}
			
			$subcategory_id = $this->input->post('subcategory_id');
			if (!empty($subcategory_id))
			{
				$category_ids[] = $subcategory_id;
			}
			
			// Delete existing category mappings
			$this->db->where('product_id', $id);
			$this->db->delete('erp_individual_product_category_mapping');
			
			if (!empty($category_ids))
			{
				$this->Individual_product_model->addProductCategories($id, $category_ids);
			}
			
			// Handle flexible variation system
			if ($has_variations)
			{
				// Update variation types to product
				$this->Variation_model->addProductVariationTypes($id, $variation_type_ids);
				
				// Get and save combinations with pricing
				$combinations_json = $this->input->post('variation_combinations');
				if (!empty($combinations_json))
				{
					$combinations = json_decode($combinations_json, TRUE);
					if (is_array($combinations) && !empty($combinations))
					{
						// Format combinations for saving
						$formatted_combinations = array();
						foreach ($combinations as $combo)
						{
							if (isset($combo['key']) && isset($combo['values']))
							{
								$formatted_combinations[] = array(
									'key' => $combo['key'],
									'values' => $combo['values'],
									'data' => isset($combo['data']) ? $combo['data'] : array(),
									'mrp' => isset($combo['mrp']) ? (float)$combo['mrp'] : 0.00,
									'selling_price' => isset($combo['selling_price']) ? (float)$combo['selling_price'] : 0.00,
									'stock_quantity' => isset($combo['stock_quantity']) ? (int)$combo['stock_quantity'] : NULL,
									'sku' => isset($combo['sku']) ? $combo['sku'] : NULL
								);
							}
						}
						
						if (!empty($formatted_combinations))
						{
							$this->Variation_model->saveProductCombinations($id, $formatted_combinations);
						}
					}
				}
			}
			else
			{
				// Remove variation types if no variations selected
				$this->db->where('product_id', $id);
				$this->db->delete('erp_product_variation_types');
				
				// Remove combinations
				$this->db->where('product_id', $id);
				$this->db->delete('erp_variation_combinations');
			}
			
			// Handle existing image updates (order, main image, deletions)
			$this->handleIndividualProductImageUpdates($id);
			
			// Handle new image uploads
			$this->handleIndividualProductImageUploads($id);
			
			$this->session->set_flashdata('success', 'Product updated successfully.');
			redirect($this->current_vendor['domain'] . '/products/individual-products');
		}
		else
		{
			$this->session->set_flashdata('error', 'Failed to update product. Please try again.');
			redirect($this->current_vendor['domain'] . '/products/individual-products/edit/' . $id);
		}
	}
	
	/**
	 * Handle individual product image uploads
	 *
	 * @param	int	$product_id	Product ID
	 * @return	void
	 */
	protected function handleIndividualProductImageUploads($product_id)
	{
		if (!empty($_FILES['images']['name'][0]))
		{
			$upload_path = './assets/uploads/vendors/' . $this->current_vendor['id'] . '/individual-products/images/';
			if (!is_dir($upload_path))
			{
				mkdir($upload_path, 0755, TRUE);
			}
			
			$files = $_FILES['images'];
			$image_order = json_decode($this->input->post('image_order'), TRUE);
			$main_image_index = (int)$this->input->post('main_image_index');
			
			if (!is_array($image_order))
			{
				$image_order = array();
				for ($i = 0; $i < count($files['name']); $i++)
				{
					$image_order[] = $i;
				}
			}
			
			$upload_errors = array();
			$images_data = array();
			
			foreach ($image_order as $order => $original_index)
			{
				if (isset($files['name'][$original_index]) && $files['error'][$original_index] == 0 && !empty($files['name'][$original_index]))
				{
					// Validate MIME type
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
					
					// Get file extension
					$file_ext = strtolower(pathinfo($files['name'][$original_index], PATHINFO_EXTENSION));
					
					$config['upload_path'] = $upload_path;
					$config['allowed_types'] = '*';
					$config['max_size'] = 5120; // 5MB
					$config['file_name'] = 'individual_product_' . $product_id . '_' . time() . '_' . $original_index . '.' . $file_ext;
					$config['overwrite'] = FALSE;
					
					$this->load->library('upload');
					$this->upload->initialize($config);
					
					if ($this->upload->do_upload('image'))
					{
						$upload_data = $this->upload->data();
						$images_data[] = array(
							'path' => 'assets/uploads/vendors/' . $this->current_vendor['id'] . '/individual-products/images/' . $upload_data['file_name'],
							'order' => $order,
							'is_main' => ($order == $main_image_index) ? 1 : 0
						);
					}
					else
					{
						$upload_errors[] = $files['name'][$original_index] . ': ' . $this->upload->display_errors('', '');
					}
				}
			}
			
			if (!empty($images_data))
			{
				$this->load->model('Individual_product_model');
				$this->Individual_product_model->addProductImages($product_id, $images_data);
			}
			
			if (!empty($upload_errors))
			{
				$this->session->set_flashdata('warning', 'Some images failed to upload: ' . implode(', ', $upload_errors));
			}
		}
	}
	
	/**
	 * Handle existing individual product image updates (order, main image, deletions)
	 *
	 * @param	int	$product_id	Product ID
	 * @return	void
	 */
	protected function handleIndividualProductImageUpdates($product_id)
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
				$this->load->model('Individual_product_model');
				foreach ($deleted_ids as $image_id)
				{
					$image_id = trim($image_id);
					if (!empty($image_id))
					{
						// Get image to delete file
						$images = $this->Individual_product_model->getProductImages($product_id);
						foreach ($images as $img)
						{
							if ($img['id'] == $image_id)
							{
								$image_path = FCPATH . 'assets/uploads/' . ltrim($img['image_path'], '/');
								if (file_exists($image_path))
								{
									@unlink($image_path);
								}
								break;
							}
						}
						
						// Delete from database
						$this->Individual_product_model->deleteProductImage($image_id);
					}
				}
			}
		}
		
		// Handle image order and main image
		if (!empty($image_order))
		{
			$image_ids = explode(',', $image_order);
			$image_ids = array_filter(array_map('trim', $image_ids));
			
			// Update image order and main image
			foreach ($image_ids as $order => $image_id)
			{
				$image_id = trim($image_id);
				if (!empty($image_id) && is_numeric($image_id))
				{
					// Check if image belongs to this product
					$this->db->where('id', $image_id);
					$this->db->where('product_id', $product_id);
					$image = $this->db->get('erp_individual_product_images')->row_array();
					
					if ($image)
					{
						// Determine if this is the main image
						$is_main = (!empty($main_image_id) && $main_image_id == $image_id) ? 1 : 0;
						
						// Update image order and is_main
						$this->db->where('id', $image_id);
						$this->db->update('erp_individual_product_images', array(
							'image_order' => $order,
							'is_main' => $is_main
						));
					}
				}
			}
		}
		
		// If main_image_id is set but not in image_order, update it separately
		if (!empty($main_image_id) && (empty($image_order) || strpos($image_order, $main_image_id) === false))
		{
			// First, set all images to not main
			$this->db->where('product_id', $product_id);
			$this->db->update('erp_individual_product_images', array('is_main' => 0));
			
			// Then set the specified image as main
			$this->db->where('id', $main_image_id);
			$this->db->where('product_id', $product_id);
			$this->db->update('erp_individual_product_images', array('is_main' => 1));
		}
	}
	
	/**
	 * Add category (AJAX)
	 *
	 * @return	void
	 */
	public function individual_products_add_category()
	{
		header('Content-Type: application/json');
		
		$name = $this->input->post('name');
		
		if (empty($name))
		{
			echo json_encode(array('status' => 'error', 'message' => 'Category name is required'));
			return;
		}
		
		$this->load->model('Individual_product_model');
		
		$data = array(
			'vendor_id' => $this->current_vendor['id'],
			'name' => $name,
			'description' => $this->input->post('description'),
			'parent_id' => $this->input->post('parent_id') ? $this->input->post('parent_id') : NULL,
			'status' => 'active'
		);
		
		$category_id = $this->Individual_product_model->createCategory($data);
		
		if ($category_id)
		{
			$is_subcategory = !empty($data['parent_id']);
			echo json_encode(array(
				'status' => 'success',
				'id' => $category_id,
				'name' => $name,
				'parent_id' => $data['parent_id'],
				'is_subcategory' => $is_subcategory
			));
		}
		else
		{
			echo json_encode(array('status' => 'error', 'message' => 'Failed to create category'));
		}
	}
	
	/**
	 * Get subcategories by parent (AJAX)
	 *
	 * @return	void
	 */
	public function individual_products_get_subcategories()
	{
		header('Content-Type: application/json');
		
		$parent_id = $this->input->get('parent_id');
		
		if (empty($parent_id))
		{
			echo json_encode(array('status' => 'error', 'message' => 'Parent category ID is required', 'subcategories' => array()));
			return;
		}
		
		$this->load->model('Individual_product_model');
		$subcategories = $this->Individual_product_model->getSubcategoriesByParent($parent_id);
		
		echo json_encode(array(
			'status' => 'success',
			'subcategories' => $subcategories
		));
	}
	
	/**
	 * Add color (AJAX)
	 *
	 * @return	void
	 */
	/**
	 * Delete individual product
	 * Routes to appropriate delete method based on product type
	 *
	 * @param	int	$id	Product ID
	 * @return	void
	 */
	public function individual_products_delete($id = NULL)
	{
		if (empty($id))
		{
			show_404();
		}
		
		// Determine product type by checking each table
		$product_type = NULL;
		$product = NULL;
		
		// Check textbooks
		$this->db->where('id', $id);
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$this->db->where('is_individual', 1);
		$textbook = $this->db->get('erp_textbooks')->row_array();
		if ($textbook)
		{
			$product_type = 'textbook';
			$product = $textbook;
		}
		
		// Check notebooks
		if (!$product_type)
		{
			$this->db->where('id', $id);
			$this->db->where('vendor_id', $this->current_vendor['id']);
			$this->db->where('is_individual', 1);
			$notebook = $this->db->get('erp_notebooks')->row_array();
			if ($notebook)
			{
				$product_type = 'notebook';
				$product = $notebook;
			}
		}
		
		// Check stationery
		if (!$product_type)
		{
			$this->db->where('id', $id);
			$this->db->where('vendor_id', $this->current_vendor['id']);
			$this->db->where('is_individual', 1);
			$stationery = $this->db->get('erp_stationery')->row_array();
			if ($stationery)
			{
				$product_type = 'stationery';
				$product = $stationery;
			}
		}
		
		// Check uniforms
		if (!$product_type)
		{
			$this->db->where('id', $id);
			$this->db->where('vendor_id', $this->current_vendor['id']);
			$this->db->where('is_individual', 1);
			$uniform = $this->db->get('erp_uniforms')->row_array();
			if ($uniform)
			{
				$product_type = 'uniform';
				$product = $uniform;
			}
		}
		
		// Check individual products table
		if (!$product_type)
		{
			$this->db->where('id', $id);
			$this->db->where('vendor_id', $this->current_vendor['id']);
			$ind_product = $this->db->get('erp_individual_products')->row_array();
			if ($ind_product)
			{
				$product_type = 'individual';
				$product = $ind_product;
			}
		}
		
		if (!$product_type || !$product)
		{
			$this->session->set_flashdata('error', 'Product not found.');
			redirect($this->current_vendor['domain'] . '/products/individual-products');
			return;
		}
		
		// Route to appropriate delete method
		switch ($product_type)
		{
			case 'textbook':
				$this->textbook_delete($id);
				break;
			case 'notebook':
				$this->notebook_delete($id);
				break;
			case 'stationery':
				$this->stationery_delete($id);
				break;
			case 'uniform':
				// Uniform delete method would go here if it exists
				$this->load->model('Uniform_model');
				if (method_exists($this->Uniform_model, 'deleteUniform'))
				{
					$this->Uniform_model->deleteUniform($id);
					$this->session->set_flashdata('success', 'Uniform deleted successfully.');
				}
				else
				{
					$this->session->set_flashdata('error', 'Delete functionality not available for uniforms.');
				}
				redirect($this->current_vendor['domain'] . '/products/individual-products');
				break;
			case 'individual':
				// Delete individual product
				$this->load->model('Individual_product_model');
				
				// Delete images
				$images = $this->Individual_product_model->getProductImages($id);
				foreach ($images as $image)
				{
					$image_path = FCPATH . 'assets/uploads/' . ltrim($image['image_path'], '/');
					if (file_exists($image_path))
					{
						@unlink($image_path);
					}
				}
				
				// Delete product
				$this->db->where('id', $id);
				$this->db->where('vendor_id', $this->current_vendor['id']);
				$this->db->delete('erp_individual_products');
				
				$this->session->set_flashdata('success', 'Product deleted successfully.');
				redirect($this->current_vendor['domain'] . '/products/individual-products');
				break;
			default:
				$this->session->set_flashdata('error', 'Invalid product type.');
				redirect($this->current_vendor['domain'] . '/products/individual-products');
		}
	}
	
	public function individual_products_add_color()
	{
		header('Content-Type: application/json');
		
		$name = $this->input->post('name');
		
		if (empty($name))
		{
			echo json_encode(array('status' => 'error', 'message' => 'Color name is required'));
			return;
		}
		
		$this->load->model('Individual_product_model');
		
		$data = array(
			'vendor_id' => $this->current_vendor['id'],
			'name' => $name,
			'color_code' => $this->input->post('color_code'),
			'description' => $this->input->post('description'),
			'status' => 'active'
		);
		
		$color_id = $this->Individual_product_model->createColor($data);
		
		if ($color_id)
		{
			echo json_encode(array(
				'status' => 'success',
				'id' => $color_id,
				'name' => $name
			));
		}
		else
		{
			echo json_encode(array('status' => 'error', 'message' => 'Failed to create color'));
		}
	}
	
	/**
	 * Get sizes by size chart (AJAX) - DEPRECATED, use variation system
	 *
	 * @return	void
	 */
	public function individual_products_get_sizes()
	{
		header('Content-Type: application/json');
		
		$size_chart_id = $this->input->get('size_chart_id');
		
		if (empty($size_chart_id))
		{
			echo json_encode(array('status' => 'error', 'message' => 'Size chart ID is required', 'sizes' => array()));
			return;
		}
		
		$this->load->model('Uniform_model');
		$sizes = $this->Uniform_model->getSizesBySizeChart($size_chart_id);
		
		echo json_encode(array(
			'status' => 'success',
			'sizes' => $sizes
		));
	}
	
	/**
	 * Variation Management - List variation types
	 *
	 * @return	void
	 */
	public function variations()
	{
		$this->load->model('Variation_model');
		
		$data['variation_types'] = $this->Variation_model->getVariationTypesByVendor($this->current_vendor['id']);
		
		$data['title'] = 'Manage Variations';
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $this->getVendorDomainForUrl();
		$data['breadcrumb'] = array(
			array('label' => 'Dashboard', 'url' => base_url($this->current_vendor['domain'] . '/dashboard')),
			array('label' => 'Products', 'url' => '#'),
			array('label' => 'Variations', 'active' => true)
		);
		
		// Load content view
		$data['content'] = $this->load->view('vendor/products/variations/index', $data, TRUE);
		
		// Load main layout
		$this->load->view('vendor/layouts/index_template', $data);
	}
	
	/**
	 * Add variation type (AJAX)
	 *
	 * @return	void
	 */
	public function add_variation_type()
	{
		header('Content-Type: application/json');
		
		$name = trim($this->input->post('name'));
		
		if (empty($name))
		{
			echo json_encode(array('status' => 'error', 'message' => 'Variation type name is required'));
			return;
		}
		
		$this->load->model('Variation_model');
		
		// Check for duplicate name
		if ($this->Variation_model->checkVariationTypeNameExists($name, $this->current_vendor['id']))
		{
			echo json_encode(array('status' => 'error', 'message' => 'A variation type with this name already exists'));
			return;
		}
		
		// Handle image upload
		$image_path = NULL;
		if (!empty($_FILES['image']['name']))
		{
			$upload_path = './assets/uploads/vendors/' . $this->current_vendor['id'] . '/variations/';
			if (!is_dir($upload_path))
			{
				mkdir($upload_path, 0755, TRUE);
			}
			
			// Validate file type
			$allowed_mimes = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp');
			$allowed_extensions = array('jpg', 'jpeg', 'png', 'gif', 'webp');
			$file_mime = $_FILES['image']['type'];
			$file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
			
			$mime_valid = in_array($file_mime, $allowed_mimes);
			$ext_valid = in_array($file_ext, $allowed_extensions);
			
			if (!$mime_valid && !$ext_valid)
			{
				echo json_encode(array('status' => 'error', 'message' => 'Invalid file type. Only image files are allowed.'));
				return;
			}
			
			// Check file size (2MB max)
			if ($_FILES['image']['size'] > 2097152)
			{
				echo json_encode(array('status' => 'error', 'message' => 'Image size exceeds 2MB limit.'));
				return;
			}
			
			$config['upload_path'] = $upload_path;
			$config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
			$config['max_size'] = 2048; // 2MB
			$config['file_name'] = 'variation_type_' . time() . '_' . uniqid() . '.' . $file_ext;
			$config['overwrite'] = FALSE;
			
			$this->load->library('upload');
			$this->upload->initialize($config);
			
			if ($this->upload->do_upload('image'))
			{
				$upload_data = $this->upload->data();
				$image_path = 'assets/uploads/vendors/' . $this->current_vendor['id'] . '/variations/' . $upload_data['file_name'];
			}
			else
			{
				$error = $this->upload->display_errors('', '');
				echo json_encode(array('status' => 'error', 'message' => 'Image upload failed: ' . $error));
				return;
			}
		}
		
		$data = array(
			'vendor_id' => $this->current_vendor['id'],
			'name' => $name,
			'description' => $this->input->post('description'),
			'image' => $image_path,
			'status' => 'active'
		);
		
		$type_id = $this->Variation_model->createVariationType($data);
		
		if ($type_id)
		{
			echo json_encode(array(
				'status' => 'success',
				'id' => $type_id,
				'name' => $name
			));
		}
		else
		{
			echo json_encode(array('status' => 'error', 'message' => 'Failed to create variation type'));
		}
	}
	
	/**
	 * Get variation values by type (AJAX)
	 *
	 * @return	void
	 */
	public function get_variation_values()
	{
		header('Content-Type: application/json');
		
		$type_id = $this->input->get('type_id');
		
		if (empty($type_id))
		{
			echo json_encode(array('status' => 'error', 'message' => 'Variation type ID is required', 'values' => array()));
			return;
		}
		
		$this->load->model('Variation_model');
		$values = $this->Variation_model->getValuesByType($type_id);
		
		echo json_encode(array(
			'status' => 'success',
			'values' => $values
		));
	}
	
	/**
	 * Add variation value (AJAX)
	 *
	 * @return	void
	 */
	public function add_variation_value()
	{
		header('Content-Type: application/json');
		
		$type_id = $this->input->post('type_id');
		$name = $this->input->post('name');
		
		if (empty($type_id) || empty($name))
		{
			echo json_encode(array('status' => 'error', 'message' => 'Variation type ID and value name are required'));
			return;
		}
		
		$this->load->model('Variation_model');
		
		$data = array(
			'variation_type_id' => $type_id,
			'name' => $name,
			'value' => $this->input->post('value'),
			'status' => 'active'
		);
		
		$value_id = $this->Variation_model->createVariationValue($data);
		
		if ($value_id)
		{
			echo json_encode(array(
				'status' => 'success',
				'id' => $value_id,
				'name' => $name
			));
		}
		else
		{
			echo json_encode(array('status' => 'error', 'message' => 'Failed to create variation value'));
		}
	}
	
	/**
	 * Update variation type (AJAX)
	 *
	 * @param	int	$type_id	Variation type ID
	 * @return	void
	 */
	public function update_variation_type($type_id = NULL)
	{
		header('Content-Type: application/json');
		
		if (empty($type_id))
		{
			echo json_encode(array('status' => 'error', 'message' => 'Variation type ID is required'));
			return;
		}
		
		$this->load->model('Variation_model');
		
		// Verify ownership
		$type = $this->Variation_model->getVariationTypeById($type_id);
		if (!$type || $type['vendor_id'] != $this->current_vendor['id'])
		{
			echo json_encode(array('status' => 'error', 'message' => 'Variation type not found'));
			return;
		}
		
		$name = trim($this->input->post('name'));
		
		if (empty($name))
		{
			echo json_encode(array('status' => 'error', 'message' => 'Variation type name is required'));
			return;
		}
		
		// Check for duplicate name (excluding current type)
		if ($this->Variation_model->checkVariationTypeNameExists($name, $this->current_vendor['id'], $type_id))
		{
			echo json_encode(array('status' => 'error', 'message' => 'A variation type with this name already exists'));
			return;
		}
		
		$data = array(
			'name' => $name,
			'description' => $this->input->post('description')
		);
		
		$result = $this->Variation_model->updateVariationType($type_id, $data);
		
		if ($result)
		{
			echo json_encode(array('status' => 'success', 'message' => 'Variation type updated successfully'));
		}
		else
		{
			echo json_encode(array('status' => 'error', 'message' => 'Failed to update variation type'));
		}
	}
	
	/**
	 * Delete variation type (AJAX)
	 *
	 * @param	int	$type_id	Variation type ID
	 * @return	void
	 */
	public function delete_variation_type($type_id = NULL)
	{
		header('Content-Type: application/json');
		
		if (empty($type_id))
		{
			echo json_encode(array('status' => 'error', 'message' => 'Variation type ID is required'));
			return;
		}
		
		$this->load->model('Variation_model');
		
		// Verify ownership
		$type = $this->Variation_model->getVariationTypeById($type_id);
		if (!$type || $type['vendor_id'] != $this->current_vendor['id'])
		{
			echo json_encode(array('status' => 'error', 'message' => 'Variation type not found'));
			return;
		}
		
		$result = $this->Variation_model->deleteVariationType($type_id);
		
		if ($result)
		{
			echo json_encode(array('status' => 'success', 'message' => 'Variation type deleted successfully'));
		}
		else
		{
			echo json_encode(array('status' => 'error', 'message' => 'Failed to delete variation type'));
		}
	}
	
	/**
	 * Delete variation value (AJAX)
	 *
	 * @param	int	$value_id	Variation value ID
	 * @return	void
	 */
	public function delete_variation_value($value_id = NULL)
	{
		header('Content-Type: application/json');
		
		if (empty($value_id))
		{
			echo json_encode(array('status' => 'error', 'message' => 'Variation value ID is required'));
			return;
		}
		
		$this->load->model('Variation_model');
		
		// Verify ownership through type
		$value = $this->Variation_model->getVariationValueById($value_id);
		if (!$value)
		{
			echo json_encode(array('status' => 'error', 'message' => 'Variation value not found'));
			return;
		}
		
		$type = $this->Variation_model->getVariationTypeById($value['variation_type_id']);
		if (!$type || $type['vendor_id'] != $this->current_vendor['id'])
		{
			echo json_encode(array('status' => 'error', 'message' => 'Unauthorized'));
			return;
		}
		
		$result = $this->Variation_model->deleteVariationValue($value_id);
		
		if ($result)
		{
			echo json_encode(array('status' => 'success', 'message' => 'Variation value deleted successfully'));
		}
		else
		{
			echo json_encode(array('status' => 'error', 'message' => 'Failed to delete variation value'));
		}
	}
	
	/**
	 * Generate combinations (AJAX)
	 *
	 * @return	void
	 */
	public function generate_combinations()
	{
		header('Content-Type: application/json');
		
		$type_ids = $this->input->post('type_ids');
		
		if (empty($type_ids) || !is_array($type_ids))
		{
			echo json_encode(array('status' => 'error', 'message' => 'Variation type IDs are required', 'combinations' => array()));
			return;
		}
		
		$this->load->model('Variation_model');
		
		// Get variation types with their values
		$variation_types = array();
		foreach ($type_ids as $type_id)
		{
			$type = $this->Variation_model->getVariationTypeById($type_id);
			if ($type && $type['vendor_id'] == $this->current_vendor['id'])
			{
				$type['values'] = $this->Variation_model->getValuesByType($type_id);
				$variation_types[] = $type;
			}
		}
		
		// Generate combinations
		$combinations = $this->Variation_model->generateCombinations($variation_types);
		
		echo json_encode(array(
			'status' => 'success',
			'combinations' => $combinations
		));
	}
	
	/**
	 * Stationery Add - Add new stationery product
	 *
	 * @return	void
	 */
	public function stationery_add()
	{
		$this->load->library('form_validation');
		
		// Load models (you'll need to create these models)
		// $this->load->model('Stationery_model');
		// $this->load->model('Category_model');
		// $this->load->model('Brand_model');
		// $this->load->model('Colour_model');
		
		// For now, prepare empty data
		$data['categories'] = array(); // $this->Category_model->getCategoriesByVendor($this->current_vendor['id']);
		$data['brands'] = array(); // $this->Brand_model->getBrandsByVendor($this->current_vendor['id']);
		$data['colours'] = array(); // $this->Colour_model->getColoursByVendor($this->current_vendor['id']);
		
		if ($this->input->method() == 'post')
		{
			// Handle form submission
			// Validation and save logic will go here
			// For now, just redirect back
			$this->session->set_flashdata('success', 'Stationery product added successfully (placeholder)');
			redirect($this->current_vendor['domain'] . '/products/stationery');
		}
		
		$data['title'] = 'Add Stationery - ' . $this->current_vendor['name'];
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $this->getVendorDomainForUrl();
		$data['breadcrumb'] = array(
			array('label' => 'Dashboard', 'url' => base_url($this->current_vendor['domain'] . '/dashboard')),
			array('label' => 'Products', 'url' => '#'),
			array('label' => 'Stationery', 'url' => base_url($this->current_vendor['domain'] . '/products/stationery')),
			array('label' => 'Add New', 'active' => true)
		);
		
		// Load content view
		$data['content'] = $this->load->view('vendor/products/stationery/add', $data, TRUE);
		
		// Load main layout
		$this->load->view('vendor/layouts/index_template', $data);
	}
	
	/**
	 * Stationery Edit - Edit stationery product
	 *
	 * @param	int	$id	Stationery ID
	 * @return	void
	 */
	public function stationery_edit($id = NULL)
	{
		if (empty($id))
		{
			show_404();
		}
		
		$this->load->library('form_validation');
		
		// Load models (you'll need to create these models)
		// $this->load->model('Stationery_model');
		// $this->load->model('Category_model');
		// $this->load->model('Brand_model');
		// $this->load->model('Colour_model');
		
		// Get stationery data
		// $stationery = $this->Stationery_model->getStationeryById($id, $this->current_vendor['id']);
		// if (!$stationery) {
		//     show_404();
		// }
		
		// For now, prepare placeholder data
		$data['stationery'] = array(
			'id' => $id,
			'category_id' => '',
			'product_name' => '',
			'isbn' => '',
			'sku' => '',
			'product_code' => '',
			'min_quantity' => 1,
			'days_to_exchange' => '',
			'pointers' => '',
			'product_description' => '',
			'packaging_length' => '',
			'packaging_width' => '',
			'packaging_height' => '',
			'packaging_weight' => '',
			'gst_percentage' => 0,
			'gst_type' => '',
			'hsn' => '',
			'mrp' => '',
			'selling_price' => '',
			'meta_title' => '',
			'meta_keywords' => '',
			'meta_description' => ''
		);
		$data['stationery_images'] = array();
		
		$data['categories'] = array(); // $this->Category_model->getCategoriesByVendor($this->current_vendor['id']);
		$data['brands'] = array(); // $this->Brand_model->getBrandsByVendor($this->current_vendor['id']);
		$data['colours'] = array(); // $this->Colour_model->getColoursByVendor($this->current_vendor['id']);
		
		if ($this->input->method() == 'post')
		{
			// Handle form submission
			// Validation and update logic will go here
			// For now, just redirect back
			$this->session->set_flashdata('success', 'Stationery product updated successfully (placeholder)');
			redirect($this->current_vendor['domain'] . '/products/stationery');
		}
		
		$data['title'] = 'Edit Stationery - ' . $this->current_vendor['name'];
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $this->getVendorDomainForUrl();
		$data['breadcrumb'] = array(
			array('label' => 'Dashboard', 'url' => base_url($this->current_vendor['domain'] . '/dashboard')),
			array('label' => 'Products', 'url' => '#'),
			array('label' => 'Stationery', 'url' => base_url($this->current_vendor['domain'] . '/products/stationery')),
			array('label' => 'Edit', 'active' => true)
		);
		
		// Load content view
		$data['content'] = $this->load->view('vendor/products/stationery/edit', $data, TRUE);
		
		// Load main layout
		$this->load->view('vendor/layouts/index_template', $data);
	}
	
	/**
	 * Stationery Delete - Delete stationery product
	 *
	 * @param	int	$id	Stationery ID
	 * @return	void
	 */
	public function stationery_delete($id = NULL)
	{
		if (empty($id))
		{
			show_404();
		}
		
		// Delete logic will go here
		// For now, just redirect
		$this->session->set_flashdata('success', 'Stationery product deleted successfully (placeholder)');
		redirect($this->current_vendor['domain'] . '/products/stationery');
	}
	
	/**
	 * Add Category via AJAX
	 *
	 * @return	void
	 */
	public function stationery_add_category()
	{
		if ($this->input->method() != 'post')
		{
			show_404();
		}
		
		$name = $this->input->post('name');
		
		if (empty($name))
		{
			echo json_encode(array('status' => 'error', 'message' => 'Name is required'));
			return;
		}
		
		// Add category logic will go here
		// For now, return placeholder
		echo json_encode(array(
			'status' => 'success',
			'id' => 1,
			'name' => $name
		));
	}
	
	/**
	 * Add Brand via AJAX
	 *
	 * @return	void
	 */
	public function stationery_add_brand()
	{
		if ($this->input->method() != 'post')
		{
			show_404();
		}
		
		$name = $this->input->post('name');
		
		if (empty($name))
		{
			echo json_encode(array('status' => 'error', 'message' => 'Name is required'));
			return;
		}
		
		// Add brand logic will go here
		// For now, return placeholder
		echo json_encode(array(
			'status' => 'success',
			'id' => 1,
			'name' => $name
		));
	}
	
	/**
	 * Add Colour via AJAX
	 *
	 * @return	void
	 */
	public function stationery_add_colour()
	{
		if ($this->input->method() != 'post')
		{
			show_404();
		}
		
		$name = $this->input->post('name');
		
		if (empty($name))
		{
			echo json_encode(array('status' => 'error', 'message' => 'Name is required'));
			return;
		}
		
		// Add colour logic will go here
		// For now, return placeholder
		echo json_encode(array(
			'status' => 'success',
			'id' => 1,
			'name' => $name
		));
	}
	
	/**
	 * Delete Image via AJAX
	 *
	 * @param	int	$id	Image ID
	 * @return	void
	 */
	public function stationery_delete_image($id = NULL)
	{
		if (empty($id))
		{
			echo json_encode(array('status' => 'error', 'message' => 'Invalid image ID'));
			return;
		}
		
		// Delete image logic will go here
		// For now, return placeholder
		echo json_encode(array('status' => 'success', 'message' => 'Image deleted successfully'));
	}
	
	/**
	 * Textbook Index - List all textbook products
	 *
	 * @return	void
	 */
	public function textbook_index()
	{
		$this->load->model('School_board_model');
		
		// Filters
		$filters = array();
		if ($this->input->get('status'))
		{
			$filters['status'] = $this->input->get('status');
		}
		if ($this->input->get('publisher_id'))
		{
			$filters['publisher_id'] = $this->input->get('publisher_id');
		}
		if ($this->input->get('board_id'))
		{
			$filters['board_id'] = $this->input->get('board_id');
		}
		if ($this->input->get('type_id'))
		{
			$filters['type_id'] = $this->input->get('type_id');
		}
		if ($this->input->get('grade_id'))
		{
			$filters['grade_id'] = $this->input->get('grade_id');
		}
		if ($this->input->get('age_id'))
		{
			$filters['age_id'] = $this->input->get('age_id');
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
		
		// Build base query for counting
		$this->db->select('COUNT(DISTINCT t.id) as total');
		$this->db->from('erp_textbooks t');
		$this->db->where('t.vendor_id', $this->current_vendor['id']);
		
		// Join mapping tables if needed for filtering
		if (!empty($filters['type_id']))
		{
			$this->db->join('erp_textbook_type_mapping ttm', 'ttm.textbook_id = t.id', 'inner');
		}
		if (!empty($filters['grade_id']))
		{
			$this->db->join('erp_textbook_grade_mapping tgm', 'tgm.textbook_id = t.id', 'inner');
		}
		if (!empty($filters['age_id']))
		{
			$this->db->join('erp_textbook_age_mapping tam', 'tam.textbook_id = t.id', 'inner');
		}
		
		// Apply filters for count
		if (!empty($filters['status']))
		{
			$this->db->where('t.status', $filters['status']);
		}
		if (!empty($filters['publisher_id']))
		{
			$this->db->where('t.publisher_id', $filters['publisher_id']);
		}
		if (!empty($filters['board_id']))
		{
			$this->db->where('t.board_id', $filters['board_id']);
		}
		if (!empty($filters['type_id']))
		{
			$this->db->where('ttm.type_id', $filters['type_id']);
		}
		if (!empty($filters['grade_id']))
		{
			$this->db->where('tgm.grade_id', $filters['grade_id']);
		}
		if (!empty($filters['age_id']))
		{
			$this->db->where('tam.age_id', $filters['age_id']);
		}
		if (!empty($filters['search']))
		{
			$this->db->group_start();
			$this->db->like('t.product_name', $filters['search']);
			$this->db->or_like('t.isbn', $filters['search']);
			$this->db->or_like('t.sku', $filters['search']);
			$this->db->group_end();
		}
		
		// Get total count for pagination
		$count_result = $this->db->get()->row_array();
		$total_textbooks = $count_result['total'];
		
		// Build query for data
		$this->db->select('t.*, p.name as publisher_name, b.board_name');
		$this->db->from('erp_textbooks t');
		$this->db->join('erp_textbook_publishers p', 'p.id = t.publisher_id', 'left');
		$this->db->join('erp_school_boards b', 'b.id = t.board_id', 'left');
		$this->db->where('t.vendor_id', $this->current_vendor['id']);
		
		// Join mapping tables if needed for filtering
		if (!empty($filters['type_id']))
		{
			$this->db->join('erp_textbook_type_mapping ttm', 'ttm.textbook_id = t.id', 'inner');
		}
		if (!empty($filters['grade_id']))
		{
			$this->db->join('erp_textbook_grade_mapping tgm', 'tgm.textbook_id = t.id', 'inner');
		}
		if (!empty($filters['age_id']))
		{
			$this->db->join('erp_textbook_age_mapping tam', 'tam.textbook_id = t.id', 'inner');
		}
		
		// Apply filters for data
		if (!empty($filters['status']))
		{
			$this->db->where('t.status', $filters['status']);
		}
		if (!empty($filters['publisher_id']))
		{
			$this->db->where('t.publisher_id', $filters['publisher_id']);
		}
		if (!empty($filters['board_id']))
		{
			$this->db->where('t.board_id', $filters['board_id']);
		}
		if (!empty($filters['type_id']))
		{
			$this->db->where('ttm.type_id', $filters['type_id']);
		}
		if (!empty($filters['grade_id']))
		{
			$this->db->where('tgm.grade_id', $filters['grade_id']);
		}
		if (!empty($filters['age_id']))
		{
			$this->db->where('tam.age_id', $filters['age_id']);
		}
		if (!empty($filters['search']))
		{
			$this->db->group_start();
			$this->db->like('t.product_name', $filters['search']);
			$this->db->or_like('t.isbn', $filters['search']);
			$this->db->or_like('t.sku', $filters['search']);
			$this->db->group_end();
		}
		
		// Group by to avoid duplicates from joins
		$this->db->group_by('t.id');
		
		// Apply pagination
		$this->db->limit($per_page, $offset);
		$this->db->order_by('t.created_at', 'DESC');
		
		// Get textbooks with pagination
		$textbook_list = $this->db->get()->result_array();
		
		// Fetch additional data for each textbook (types, grades, ages, subjects)
		foreach ($textbook_list as &$textbook)
		{
			// Get types
			$this->db->select('tt.name');
			$this->db->from('erp_textbook_type_mapping ttm');
			$this->db->join('erp_textbook_types tt', 'tt.id = ttm.type_id', 'left');
			$this->db->where('ttm.textbook_id', $textbook['id']);
			$textbook['types'] = $this->db->get()->result_array();
			
			// Get grades
			$this->db->select('tg.name');
			$this->db->from('erp_textbook_grade_mapping tgm');
			$this->db->join('erp_textbook_grades tg', 'tg.id = tgm.grade_id', 'left');
			$this->db->where('tgm.textbook_id', $textbook['id']);
			$textbook['grades'] = $this->db->get()->result_array();
			
			// Get ages
			$this->db->select('ta.name');
			$this->db->from('erp_textbook_age_mapping tam');
			$this->db->join('erp_textbook_ages ta', 'ta.id = tam.age_id', 'left');
			$this->db->where('tam.textbook_id', $textbook['id']);
			$textbook['ages'] = $this->db->get()->result_array();
			
			// Get subjects
			$this->db->select('ts.name');
			$this->db->from('erp_textbook_subject_mapping tsm');
			$this->db->join('erp_textbook_subjects ts', 'ts.id = tsm.subject_id', 'left');
			$this->db->where('tsm.textbook_id', $textbook['id']);
			$textbook['subjects'] = $this->db->get()->result_array();
			
			// Get main image (is_main = 1) for thumbnail, fallback to first image
			$textbook['thumbnail'] = NULL;
			$this->db->select('image_path');
			$this->db->from('erp_textbook_images');
			$this->db->where('textbook_id', $textbook['id']);
			$this->db->where('is_main', 1);
			$this->db->limit(1);
			$image = $this->db->get()->row_array();
			
			if ($image)
			{
				$textbook['thumbnail'] = $image['image_path'];
			}
			else
			{
				// If no main image found, use first image by order
				$this->db->select('image_path');
				$this->db->from('erp_textbook_images');
				$this->db->where('textbook_id', $textbook['id']);
				$this->db->order_by('image_order', 'ASC');
				$this->db->limit(1);
				$image = $this->db->get()->row_array();
				$textbook['thumbnail'] = $image ? $image['image_path'] : NULL;
			}
		}
		unset($textbook);
		
		$data['textbook_list'] = $textbook_list;
		
		// Get publishers for filter dropdown
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$this->db->where('status', 'active');
		$this->db->order_by('name', 'ASC');
		$data['publishers'] = $this->db->get('erp_textbook_publishers')->result_array();
		
		// Get boards for filter dropdown
		$data['boards'] = $this->School_board_model->getBoardsByVendor($this->current_vendor['id']);
		
		// Get types for filter dropdown
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$this->db->where('status', 'active');
		$this->db->order_by('name', 'ASC');
		$data['types'] = $this->db->get('erp_textbook_types')->result_array();
		
		// Get grades for filter dropdown
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$this->db->where('status', 'active');
		$this->db->order_by('name', 'ASC');
		$data['grades'] = $this->db->get('erp_textbook_grades')->result_array();
		
		// Get ages for filter dropdown
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$this->db->where('status', 'active');
		$this->db->order_by('name', 'ASC');
		$data['ages'] = $this->db->get('erp_textbook_ages')->result_array();
		$data['total_textbooks'] = $total_textbooks;
		$data['per_page'] = $per_page;
		$data['current_page'] = $page;
		$data['total_pages'] = ceil($total_textbooks / $per_page);
		
		// Get publishers for filter
		// $data['publishers'] = $this->Textbook_model->getPublishersByVendor($this->current_vendor['id']);
		$data['publishers'] = array(); // Placeholder - replace with actual model call
		
		$data['title'] = 'Manage Textbooks';
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $this->getVendorDomainForUrl();
		$data['filters'] = $filters;
		$data['breadcrumb'] = array(
			array('label' => 'Dashboard', 'url' => base_url($this->current_vendor['domain'] . '/dashboard')),
			array('label' => 'Products', 'url' => '#'),
			array('label' => 'Books', 'url' => '#'),
			array('label' => 'Textbooks', 'active' => true)
		);
		
		// Load content view
		$data['content'] = $this->load->view('vendor/products/books/textbook/index', $data, TRUE);
		
		// Load main layout
		$this->load->view('vendor/layouts/index_template', $data);
	}
	
	/**
	 * Textbook Add - Add new textbook product
	 *
	 * @return	void
	 */
	public function textbook_add()
	{
		$this->load->library('form_validation');
		$this->load->model('School_board_model');
		
		// Load models (you'll need to create these models)
		// $this->load->model('Textbook_model');
		
		// Get boards for this vendor
		$data['boards'] = $this->School_board_model->getBoardsByVendor($this->current_vendor['id']);
		
		// Load data from database
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$this->db->where('status', 'active');
		$this->db->order_by('name', 'ASC');
		$data['types'] = $this->db->get('erp_textbook_types')->result_array();
		
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$this->db->where('status', 'active');
		$this->db->order_by('name', 'ASC');
		$data['publishers'] = $this->db->get('erp_textbook_publishers')->result_array();
		
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$this->db->where('status', 'active');
		$this->db->order_by('name', 'ASC');
		$data['grades'] = $this->db->get('erp_textbook_grades')->result_array();
		
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$this->db->where('status', 'active');
		$this->db->order_by('name', 'ASC');
		$data['ages'] = $this->db->get('erp_textbook_ages')->result_array();
		
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$this->db->where('status', 'active');
		$this->db->order_by('name', 'ASC');
		$data['subjects'] = $this->db->get('erp_textbook_subjects')->result_array();
		
		if ($this->input->method() == 'post')
		{
			// Form validation rules
			$this->form_validation->set_rules('publisher_id', 'Publisher', 'required');
			$this->form_validation->set_rules('board_id', 'Board', 'required');
			$this->form_validation->set_rules('grade_age_type', 'Grade/Age Type', 'required');
			$this->form_validation->set_rules('product_name', 'Product Name', 'required');
			$this->form_validation->set_rules('isbn', 'ISBN/Bar Code No./SKU', 'required');
			$this->form_validation->set_rules('min_quantity', 'Min Quantity', 'required|numeric');
			$this->form_validation->set_rules('product_description', 'Product Description', 'required');
			$this->form_validation->set_rules('gst_percentage', 'GST (%)', 'required|numeric');
			$this->form_validation->set_rules('mrp', 'MRP', 'required|numeric');
			$this->form_validation->set_rules('selling_price', 'Selling Price', 'required|numeric');
			$this->form_validation->set_rules('types[]', 'Type', 'required');
			$this->form_validation->set_rules('subjects[]', 'Subject', 'required');
			
			if ($this->form_validation->run() == FALSE)
			{
				// Validation failed, reload form with errors
			}
			else
			{
				// Get form data
				$textbook_data = array(
					'vendor_id' => $this->current_vendor['id'],
					'publisher_id' => $this->input->post('publisher_id'),
					'board_id' => $this->input->post('board_id'),
					'grade_age_type' => $this->input->post('grade_age_type'),
					'product_name' => $this->input->post('product_name'),
					'isbn' => $this->input->post('isbn'),
					'min_quantity' => $this->input->post('min_quantity') ? $this->input->post('min_quantity') : 1,
					'days_to_exchange' => $this->input->post('days_to_exchange') ? $this->input->post('days_to_exchange') : NULL,
					'pointers' => $this->input->post('pointers') ? $this->input->post('pointers') : NULL,
					'product_description' => $this->input->post('product_description'),
					'packaging_length' => $this->input->post('packaging_length') ? $this->input->post('packaging_length') : NULL,
					'packaging_width' => $this->input->post('packaging_width') ? $this->input->post('packaging_width') : NULL,
					'packaging_height' => $this->input->post('packaging_height') ? $this->input->post('packaging_height') : NULL,
					'packaging_weight' => $this->input->post('packaging_weight') ? $this->input->post('packaging_weight') : NULL,
					'gst_percentage' => $this->input->post('gst_percentage') ? $this->input->post('gst_percentage') : 0,
					'hsn' => $this->input->post('hsn') ? $this->input->post('hsn') : NULL,
					'product_code' => $this->input->post('product_code') ? $this->input->post('product_code') : NULL,
					'sku' => $this->input->post('sku') ? $this->input->post('sku') : NULL,
					'mrp' => $this->input->post('mrp'),
					'selling_price' => $this->input->post('selling_price'),
					'meta_title' => $this->input->post('meta_title') ? $this->input->post('meta_title') : NULL,
					'meta_keywords' => $this->input->post('meta_keywords') ? $this->input->post('meta_keywords') : NULL,
					'meta_description' => $this->input->post('meta_description') ? $this->input->post('meta_description') : NULL,
					'is_individual' => $this->input->post('is_individual') ? 1 : 0,
					'is_set' => $this->input->post('is_set') ? 1 : 0,
					'status' => 'active',
					'created_at' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s')
				);
				
				// Insert textbook
				$this->db->insert('erp_textbooks', $textbook_data);
				$textbook_id = $this->db->insert_id();
				
				if ($textbook_id)
				{
					// Handle images upload
					if (!empty($_FILES['images']['name'][0]))
					{
						$this->load->library('upload');
						
						// Create base uploads directory if it doesn't exist
						$base_upload_path = './uploads/';
						if (!is_dir($base_upload_path))
						{
							mkdir($base_upload_path, 0755, TRUE);
						}
						
						// Create textbooks directory if it doesn't exist
						$textbooks_upload_path = './uploads/textbooks/';
						if (!is_dir($textbooks_upload_path))
						{
							mkdir($textbooks_upload_path, 0755, TRUE);
						}
						
						// Create upload directory if it doesn't exist
						$upload_path = './uploads/textbooks/' . $this->current_vendor['id'] . '/';
						if (!is_dir($upload_path))
						{
							if (!mkdir($upload_path, 0755, TRUE))
							{
								log_message('error', 'Failed to create upload directory: ' . $upload_path);
								$this->session->set_flashdata('error', 'Failed to create upload directory. Please check folder permissions.');
							}
						}
						
						$files = $_FILES['images'];
						$image_order = 0;
						$upload_errors = array();
						
						foreach ($files['name'] as $key => $filename)
						{
							if ($files['error'][$key] == 0 && !empty($filename))
							{
								// Validate MIME type to ensure it's an image
								$allowed_mimes = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/bmp', 'image/svg+xml', 'image/x-icon', 'image/tiff', 'image/tif', 'image/avif');
								$allowed_extensions = array('jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg', 'ico', 'tiff', 'tif', 'avif');
								$file_mime = $files['type'][$key];
								
								// Get file extension as fallback
								$file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
								
								// Check both MIME type and file extension (some browsers send incorrect MIME types for AVIF)
								// Accept file if EITHER MIME type OR extension is valid (extension takes precedence for AVIF)
								$mime_valid = in_array($file_mime, $allowed_mimes);
								$ext_valid = in_array($file_ext, $allowed_extensions);
								$is_valid = $mime_valid || $ext_valid;
								
								if (!$is_valid)
								{
									$upload_errors[] = $filename . ': Invalid file type. Only image files are allowed.';
									continue;
								}
								
								// Reset $_FILES array for this iteration
								$_FILES['image']['name'] = $files['name'][$key];
								$_FILES['image']['type'] = $files['type'][$key];
								$_FILES['image']['tmp_name'] = $files['tmp_name'][$key];
								$_FILES['image']['error'] = $files['error'][$key];
								$_FILES['image']['size'] = $files['size'][$key];
								
								// Get file extension and convert to lowercase
								$file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
								
								$config['upload_path'] = $upload_path;
								$config['allowed_types'] = '*'; // Allow all types - we validate MIME type above
								$config['max_size'] = 5120; // 5MB
								$config['file_name'] = 'textbook_' . $textbook_id . '_' . time() . '_' . $key . '.' . $file_ext;
								$config['overwrite'] = FALSE;
								
								$this->upload->initialize($config);
								
								if ($this->upload->do_upload('image'))
								{
									$upload_data = $this->upload->data();
									$image_data = array(
										'textbook_id' => $textbook_id,
										'image_path' => 'uploads/textbooks/' . $this->current_vendor['id'] . '/' . $upload_data['file_name'],
										'image_order' => $image_order++,
										'created_at' => date('Y-m-d H:i:s')
									);
									$this->db->insert('erp_textbook_images', $image_data);
								}
								else
								{
									$error = $this->upload->display_errors('', '');
									$upload_errors[] = $filename . ': ' . $error;
									log_message('error', 'Image upload failed: ' . $error);
								}
							}
							elseif ($files['error'][$key] != 0)
							{
								$upload_errors[] = $filename . ': Upload error code ' . $files['error'][$key];
							}
						}
						
						// Show upload errors if any
						if (!empty($upload_errors))
						{
							$this->session->set_flashdata('error', 'Some images failed to upload: ' . implode(', ', $upload_errors));
						}
					}
					
					// Handle types (many-to-many)
					$types = $this->input->post('types');
					if (!empty($types) && is_array($types))
					{
						foreach ($types as $type_id)
						{
							if (!empty($type_id))
							{
								$type_mapping = array(
									'textbook_id' => $textbook_id,
									'type_id' => $type_id,
									'created_at' => date('Y-m-d H:i:s')
								);
								$this->db->insert('erp_textbook_type_mapping', $type_mapping);
							}
						}
					}
					
					// Handle grades (many-to-many)
					$grades = $this->input->post('grades');
					if (!empty($grades) && is_array($grades))
					{
						foreach ($grades as $grade_id)
						{
							if (!empty($grade_id))
							{
								$grade_mapping = array(
									'textbook_id' => $textbook_id,
									'grade_id' => $grade_id,
									'created_at' => date('Y-m-d H:i:s')
								);
								$this->db->insert('erp_textbook_grade_mapping', $grade_mapping);
							}
						}
					}
					
					// Handle ages (many-to-many)
					$ages = $this->input->post('ages');
					if (!empty($ages) && is_array($ages))
					{
						foreach ($ages as $age_id)
						{
							if (!empty($age_id))
							{
								$age_mapping = array(
									'textbook_id' => $textbook_id,
									'age_id' => $age_id,
									'created_at' => date('Y-m-d H:i:s')
								);
								$this->db->insert('erp_textbook_age_mapping', $age_mapping);
							}
						}
					}
					
					// Handle subjects (many-to-many)
					$subjects = $this->input->post('subjects');
					if (!empty($subjects) && is_array($subjects))
					{
						foreach ($subjects as $subject_id)
						{
							if (!empty($subject_id))
							{
								$subject_mapping = array(
									'textbook_id' => $textbook_id,
									'subject_id' => $subject_id,
									'created_at' => date('Y-m-d H:i:s')
								);
								$this->db->insert('erp_textbook_subject_mapping', $subject_mapping);
							}
						}
					}
					
					if (empty($upload_errors))
					{
						$this->session->set_flashdata('success', 'Textbook product added successfully');
					}
					redirect($this->current_vendor['domain'] . '/products/textbook');
				}
				else
				{
					$this->session->set_flashdata('error', 'Failed to add textbook product');
				}
			}
		}
		
		$data['title'] = 'Add Textbook - ' . $this->current_vendor['name'];
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $this->getVendorDomainForUrl();
		$data['breadcrumb'] = array(
			array('label' => 'Dashboard', 'url' => base_url($this->current_vendor['domain'] . '/dashboard')),
			array('label' => 'Products', 'url' => '#'),
			array('label' => 'Books', 'url' => '#'),
			array('label' => 'Textbooks', 'url' => base_url($this->current_vendor['domain'] . '/products/textbook')),
			array('label' => 'Add New', 'active' => true)
		);
		
		// Load content view
		$data['content'] = $this->load->view('vendor/products/books/textbook/add', $data, TRUE);
		
		// Load main layout
		$this->load->view('vendor/layouts/index_template', $data);
	}
	
	/**
	 * Textbook Edit - Edit textbook product
	 *
	 * @param	int	$id	Textbook ID
	 * @return	void
	 */
	public function textbook_edit($id = NULL)
	{
		if (empty($id))
		{
			show_404();
		}
		
		$this->load->library('form_validation');
		$this->load->model('School_board_model');
		
		// Load models (you'll need to create these models)
		// $this->load->model('Textbook_model');
		
		// Get boards for this vendor
		$data['boards'] = $this->School_board_model->getBoardsByVendor($this->current_vendor['id']);
		
		// Load data from database
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$this->db->where('status', 'active');
		$this->db->order_by('name', 'ASC');
		$data['types'] = $this->db->get('erp_textbook_types')->result_array();
		
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$this->db->where('status', 'active');
		$this->db->order_by('name', 'ASC');
		$data['publishers'] = $this->db->get('erp_textbook_publishers')->result_array();
		
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$this->db->where('status', 'active');
		$this->db->order_by('name', 'ASC');
		$data['grades'] = $this->db->get('erp_textbook_grades')->result_array();
		
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$this->db->where('status', 'active');
		$this->db->order_by('name', 'ASC');
		$data['ages'] = $this->db->get('erp_textbook_ages')->result_array();
		
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$this->db->where('status', 'active');
		$this->db->order_by('name', 'ASC');
		$data['subjects'] = $this->db->get('erp_textbook_subjects')->result_array();
		
		// Get textbook data
		$this->db->where('id', $id);
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$textbook = $this->db->get('erp_textbooks')->row_array();
		
		if (!$textbook)
		{
			show_404();
		}
		
		$data['textbook'] = $textbook;
		
		// Get textbook images
		$this->db->where('textbook_id', $id);
		$this->db->order_by('image_order', 'ASC');
		$data['textbook_images'] = $this->db->get('erp_textbook_images')->result_array();
		
		// Get textbook types
		$this->db->select('ttm.type_id');
		$this->db->from('erp_textbook_type_mapping ttm');
		$this->db->where('ttm.textbook_id', $id);
		$data['textbook_types'] = $this->db->get()->result_array();
		
		// Get textbook grades
		$this->db->select('tgm.grade_id');
		$this->db->from('erp_textbook_grade_mapping tgm');
		$this->db->where('tgm.textbook_id', $id);
		$data['textbook_grades'] = $this->db->get()->result_array();
		
		// Get textbook ages
		$this->db->select('tam.age_id');
		$this->db->from('erp_textbook_age_mapping tam');
		$this->db->where('tam.textbook_id', $id);
		$data['textbook_ages'] = $this->db->get()->result_array();
		
		// Get textbook subjects
		$this->db->select('tsm.subject_id');
		$this->db->from('erp_textbook_subject_mapping tsm');
		$this->db->where('tsm.textbook_id', $id);
		$data['textbook_subjects'] = $this->db->get()->result_array();
		
		if ($this->input->method() == 'post')
		{
			// Form validation rules
			$this->form_validation->set_rules('publisher_id', 'Publisher', 'required');
			$this->form_validation->set_rules('board_id', 'Board', 'required');
			$this->form_validation->set_rules('grade_age_type', 'Grade/Age Type', 'required');
			$this->form_validation->set_rules('product_name', 'Product Name', 'required');
			$this->form_validation->set_rules('isbn', 'ISBN/Bar Code No./SKU', 'required');
			$this->form_validation->set_rules('min_quantity', 'Min Quantity', 'required|numeric');
			$this->form_validation->set_rules('product_description', 'Product Description', 'required');
			$this->form_validation->set_rules('gst_percentage', 'GST (%)', 'required|numeric');
			$this->form_validation->set_rules('mrp', 'MRP', 'required|numeric');
			$this->form_validation->set_rules('selling_price', 'Selling Price', 'required|numeric');
			$this->form_validation->set_rules('types[]', 'Type', 'required');
			$this->form_validation->set_rules('subjects[]', 'Subject', 'required');
			
			if ($this->form_validation->run() == FALSE)
			{
				// Validation failed, reload form with errors
			}
			else
			{
				// Get form data
				$textbook_data = array(
					'publisher_id' => $this->input->post('publisher_id'),
					'board_id' => $this->input->post('board_id'),
					'grade_age_type' => $this->input->post('grade_age_type'),
					'product_name' => $this->input->post('product_name'),
					'isbn' => $this->input->post('isbn'),
					'min_quantity' => $this->input->post('min_quantity') ? $this->input->post('min_quantity') : 1,
					'days_to_exchange' => $this->input->post('days_to_exchange') ? $this->input->post('days_to_exchange') : NULL,
					'pointers' => $this->input->post('pointers') ? $this->input->post('pointers') : NULL,
					'product_description' => $this->input->post('product_description'),
					'packaging_length' => $this->input->post('packaging_length') ? $this->input->post('packaging_length') : NULL,
					'packaging_width' => $this->input->post('packaging_width') ? $this->input->post('packaging_width') : NULL,
					'packaging_height' => $this->input->post('packaging_height') ? $this->input->post('packaging_height') : NULL,
					'packaging_weight' => $this->input->post('packaging_weight') ? $this->input->post('packaging_weight') : NULL,
					'gst_percentage' => $this->input->post('gst_percentage') ? $this->input->post('gst_percentage') : 0,
					'hsn' => $this->input->post('hsn') ? $this->input->post('hsn') : NULL,
					'product_code' => $this->input->post('product_code') ? $this->input->post('product_code') : NULL,
					'sku' => $this->input->post('sku') ? $this->input->post('sku') : NULL,
					'mrp' => $this->input->post('mrp'),
					'selling_price' => $this->input->post('selling_price'),
					'meta_title' => $this->input->post('meta_title') ? $this->input->post('meta_title') : NULL,
					'meta_keywords' => $this->input->post('meta_keywords') ? $this->input->post('meta_keywords') : NULL,
					'meta_description' => $this->input->post('meta_description') ? $this->input->post('meta_description') : NULL,
					'is_individual' => $this->input->post('is_individual') ? 1 : 0,
					'is_set' => $this->input->post('is_set') ? 1 : 0,
					'updated_at' => date('Y-m-d H:i:s')
				);
				
				// Update textbook
				$this->db->where('id', $id);
				$this->db->where('vendor_id', $this->current_vendor['id']);
				$this->db->update('erp_textbooks', $textbook_data);
				
				// Handle new images upload
				if (!empty($_FILES['images']['name'][0]))
				{
					$this->load->library('upload');
					
					// Create base uploads directory if it doesn't exist
					$base_upload_path = './uploads/';
					if (!is_dir($base_upload_path))
					{
						mkdir($base_upload_path, 0755, TRUE);
					}
					
					// Create textbooks directory if it doesn't exist
					$textbooks_upload_path = './uploads/textbooks/';
					if (!is_dir($textbooks_upload_path))
					{
						mkdir($textbooks_upload_path, 0755, TRUE);
					}
					
					// Create upload directory if it doesn't exist
					$upload_path = './uploads/textbooks/' . $this->current_vendor['id'] . '/';
					if (!is_dir($upload_path))
					{
						if (!mkdir($upload_path, 0755, TRUE))
						{
							log_message('error', 'Failed to create upload directory: ' . $upload_path);
							$this->session->set_flashdata('error', 'Failed to create upload directory. Please check folder permissions.');
						}
					}
					
					// Get current max image order
					$this->db->select_max('image_order');
					$this->db->where('textbook_id', $id);
					$max_order_result = $this->db->get('erp_textbook_images')->row_array();
					$image_order = $max_order_result['image_order'] ? $max_order_result['image_order'] + 1 : 0;
					
					$files = $_FILES['images'];
					$upload_errors = array();
					
					foreach ($files['name'] as $key => $filename)
					{
						if ($files['error'][$key] == 0 && !empty($filename))
						{
							// Validate MIME type to ensure it's an image
							$allowed_mimes = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/bmp', 'image/svg+xml', 'image/x-icon', 'image/tiff', 'image/tif', 'image/avif');
							$allowed_extensions = array('jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg', 'ico', 'tiff', 'tif', 'avif');
							$file_mime = $files['type'][$key];
							
							// Get file extension as fallback
							$file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
							
							// Check both MIME type and file extension (some browsers send incorrect MIME types for AVIF)
							// Accept file if EITHER MIME type OR extension is valid (extension takes precedence for AVIF)
							$mime_valid = in_array($file_mime, $allowed_mimes);
							$ext_valid = in_array($file_ext, $allowed_extensions);
							$is_valid = $mime_valid || $ext_valid;
							
							if (!$is_valid)
							{
								$upload_errors[] = $filename . ': Invalid file type. Only image files are allowed.';
								continue;
							}
							
							// Reset $_FILES array for this iteration
							$_FILES['image']['name'] = $files['name'][$key];
							$_FILES['image']['type'] = $files['type'][$key];
							$_FILES['image']['tmp_name'] = $files['tmp_name'][$key];
							$_FILES['image']['error'] = $files['error'][$key];
							$_FILES['image']['size'] = $files['size'][$key];
							
							// Get file extension and convert to lowercase
							$file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
							
							$config['upload_path'] = $upload_path;
							$config['allowed_types'] = '*'; // Allow all types - we validate MIME type above
							$config['max_size'] = 5120; // 5MB
							$config['file_name'] = 'textbook_' . $id . '_' . time() . '_' . $key . '.' . $file_ext;
							$config['overwrite'] = FALSE;
							
							$this->upload->initialize($config);
							
							if ($this->upload->do_upload('image'))
							{
								$upload_data = $this->upload->data();
								$image_data = array(
									'textbook_id' => $id,
									'image_path' => 'uploads/textbooks/' . $this->current_vendor['id'] . '/' . $upload_data['file_name'],
									'image_order' => $image_order++,
									'created_at' => date('Y-m-d H:i:s')
								);
								$this->db->insert('erp_textbook_images', $image_data);
							}
							else
							{
								$error = $this->upload->display_errors('', '');
								$upload_errors[] = $filename . ': ' . $error;
								log_message('error', 'Image upload failed: ' . $error);
							}
						}
						elseif ($files['error'][$key] != 0)
						{
							$upload_errors[] = $filename . ': Upload error code ' . $files['error'][$key];
						}
					}
					
					// Show upload errors if any
					if (!empty($upload_errors))
					{
						$this->session->set_flashdata('error', 'Some images failed to upload: ' . implode(', ', $upload_errors));
					}
				}
				
				// Delete existing mappings and recreate
				$this->db->where('textbook_id', $id);
				$this->db->delete('erp_textbook_type_mapping');
				
				$this->db->where('textbook_id', $id);
				$this->db->delete('erp_textbook_grade_mapping');
				
				$this->db->where('textbook_id', $id);
				$this->db->delete('erp_textbook_age_mapping');
				
				$this->db->where('textbook_id', $id);
				$this->db->delete('erp_textbook_subject_mapping');
				
				// Handle types (many-to-many)
				$types = $this->input->post('types');
				if (!empty($types) && is_array($types))
				{
					foreach ($types as $type_id)
					{
						if (!empty($type_id))
						{
							$type_mapping = array(
								'textbook_id' => $id,
								'type_id' => $type_id,
								'created_at' => date('Y-m-d H:i:s')
							);
							$this->db->insert('erp_textbook_type_mapping', $type_mapping);
						}
					}
				}
				
				// Handle grades (many-to-many)
				$grades = $this->input->post('grades');
				if (!empty($grades) && is_array($grades))
				{
					foreach ($grades as $grade_id)
					{
						if (!empty($grade_id))
						{
							$grade_mapping = array(
								'textbook_id' => $id,
								'grade_id' => $grade_id,
								'created_at' => date('Y-m-d H:i:s')
							);
							$this->db->insert('erp_textbook_grade_mapping', $grade_mapping);
						}
					}
				}
				
				// Handle ages (many-to-many)
				$ages = $this->input->post('ages');
				if (!empty($ages) && is_array($ages))
				{
					foreach ($ages as $age_id)
					{
						if (!empty($age_id))
						{
							$age_mapping = array(
								'textbook_id' => $id,
								'age_id' => $age_id,
								'created_at' => date('Y-m-d H:i:s')
							);
							$this->db->insert('erp_textbook_age_mapping', $age_mapping);
						}
					}
				}
				
				// Handle subjects (many-to-many)
				$subjects = $this->input->post('subjects');
				if (!empty($subjects) && is_array($subjects))
				{
					foreach ($subjects as $subject_id)
					{
						if (!empty($subject_id))
						{
							$subject_mapping = array(
								'textbook_id' => $id,
								'subject_id' => $subject_id,
								'created_at' => date('Y-m-d H:i:s')
							);
							$this->db->insert('erp_textbook_subject_mapping', $subject_mapping);
						}
					}
				}
				
				$this->session->set_flashdata('success', 'Textbook product updated successfully');
				redirect($this->current_vendor['domain'] . '/products/textbook');
			}
		}
		
		$data['title'] = 'Edit Textbook - ' . $this->current_vendor['name'];
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $this->getVendorDomainForUrl();
		$data['breadcrumb'] = array(
			array('label' => 'Dashboard', 'url' => base_url($this->current_vendor['domain'] . '/dashboard')),
			array('label' => 'Products', 'url' => '#'),
			array('label' => 'Books', 'url' => '#'),
			array('label' => 'Textbooks', 'url' => base_url($this->current_vendor['domain'] . '/products/textbook')),
			array('label' => 'Edit', 'active' => true)
		);
		
		// Load content view
		$data['content'] = $this->load->view('vendor/products/books/textbook/edit', $data, TRUE);
		
		// Load main layout
		$this->load->view('vendor/layouts/index_template', $data);
	}
	
	/**
	 * Textbook Delete - Delete textbook product
	 *
	 * @param	int	$id	Textbook ID
	 * @return	void
	 */
	public function textbook_delete($id = NULL)
	{
		if (empty($id))
		{
			show_404();
		}
		
		// Delete logic will go here
		// For now, just redirect
		$this->session->set_flashdata('success', 'Textbook product deleted successfully (placeholder)');
		redirect($this->current_vendor['domain'] . '/products/books/textbook');
	}
	
	/**
	 * Add Type via AJAX
	 *
	 * @return	void
	 */
	public function textbook_add_type()
	{
		if ($this->input->method() != 'post')
		{
			show_404();
		}
		
		$name = $this->input->post('name');
		$description = $this->input->post('description');
		
		if (empty($name))
		{
			echo json_encode(array('status' => 'error', 'message' => 'Name is required'));
			return;
		}
		
		// Check if type already exists for this vendor
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$this->db->where('name', $name);
		$existing = $this->db->get('erp_textbook_types')->row_array();
		
		if ($existing)
		{
			echo json_encode(array(
				'status' => 'success',
				'id' => $existing['id'],
				'name' => $existing['name']
			));
			return;
		}
		
		// Insert new type
		$data = array(
			'vendor_id' => $this->current_vendor['id'],
			'name' => $name,
			'description' => $description ? $description : NULL,
			'status' => 'active',
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s')
		);
		
		$this->db->insert('erp_textbook_types', $data);
		$id = $this->db->insert_id();
		
		echo json_encode(array(
			'status' => 'success',
			'id' => $id,
			'name' => $name
		));
	}
	
	/**
	 * Add Publisher via AJAX
	 *
	 * @return	void
	 */
	public function textbook_add_publisher()
	{
		if ($this->input->method() != 'post')
		{
			show_404();
		}
		
		$name = $this->input->post('name');
		$description = $this->input->post('description');
		
		if (empty($name))
		{
			echo json_encode(array('status' => 'error', 'message' => 'Name is required'));
			return;
		}
		
		// Check if publisher already exists for this vendor
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$this->db->where('name', $name);
		$existing = $this->db->get('erp_textbook_publishers')->row_array();
		
		if ($existing)
		{
			echo json_encode(array(
				'status' => 'success',
				'id' => $existing['id'],
				'name' => $existing['name']
			));
			return;
		}
		
		// Insert new publisher
		$data = array(
			'vendor_id' => $this->current_vendor['id'],
			'name' => $name,
			'description' => $description ? $description : NULL,
			'status' => 'active',
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s')
		);
		
		$this->db->insert('erp_textbook_publishers', $data);
		$id = $this->db->insert_id();
		
		echo json_encode(array(
			'status' => 'success',
			'id' => $id,
			'name' => $name
		));
	}
	
	/**
	 * Add Grade via AJAX
	 *
	 * @return	void
	 */
	public function textbook_add_grade()
	{
		if ($this->input->method() != 'post')
		{
			show_404();
		}
		
		$name = $this->input->post('name');
		$description = $this->input->post('description');
		
		if (empty($name))
		{
			echo json_encode(array('status' => 'error', 'message' => 'Name is required'));
			return;
		}
		
		// Check if grade already exists for this vendor
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$this->db->where('name', $name);
		$existing = $this->db->get('erp_textbook_grades')->row_array();
		
		if ($existing)
		{
			echo json_encode(array(
				'status' => 'success',
				'id' => $existing['id'],
				'name' => $existing['name']
			));
			return;
		}
		
		// Insert new grade
		$data = array(
			'vendor_id' => $this->current_vendor['id'],
			'name' => $name,
			'description' => $description ? $description : NULL,
			'status' => 'active',
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s')
		);
		
		$this->db->insert('erp_textbook_grades', $data);
		$id = $this->db->insert_id();
		
		echo json_encode(array(
			'status' => 'success',
			'id' => $id,
			'name' => $name
		));
	}
	
	/**
	 * Add Age via AJAX
	 *
	 * @return	void
	 */
	public function textbook_add_age()
	{
		if ($this->input->method() != 'post')
		{
			show_404();
		}
		
		$name = $this->input->post('name');
		$description = $this->input->post('description');
		
		if (empty($name))
		{
			echo json_encode(array('status' => 'error', 'message' => 'Name is required'));
			return;
		}
		
		// Check if age already exists for this vendor
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$this->db->where('name', $name);
		$existing = $this->db->get('erp_textbook_ages')->row_array();
		
		if ($existing)
		{
			echo json_encode(array(
				'status' => 'success',
				'id' => $existing['id'],
				'name' => $existing['name']
			));
			return;
		}
		
		// Insert new age
		$data = array(
			'vendor_id' => $this->current_vendor['id'],
			'name' => $name,
			'description' => $description ? $description : NULL,
			'status' => 'active',
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s')
		);
		
		$this->db->insert('erp_textbook_ages', $data);
		$id = $this->db->insert_id();
		
		echo json_encode(array(
			'status' => 'success',
			'id' => $id,
			'name' => $name
		));
	}
	
	/**
	 * Add Subject via AJAX
	 *
	 * @return	void
	 */
	public function textbook_add_subject()
	{
		if ($this->input->method() != 'post')
		{
			show_404();
		}
		
		$name = $this->input->post('name');
		$description = $this->input->post('description');
		
		if (empty($name))
		{
			echo json_encode(array('status' => 'error', 'message' => 'Name is required'));
			return;
		}
		
		// Check if subject already exists for this vendor
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$this->db->where('name', $name);
		$existing = $this->db->get('erp_textbook_subjects')->row_array();
		
		if ($existing)
		{
			echo json_encode(array(
				'status' => 'success',
				'id' => $existing['id'],
				'name' => $existing['name']
			));
			return;
		}
		
		// Insert new subject
		$data = array(
			'vendor_id' => $this->current_vendor['id'],
			'name' => $name,
			'description' => $description ? $description : NULL,
			'status' => 'active',
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s')
		);
		
		$this->db->insert('erp_textbook_subjects', $data);
		$id = $this->db->insert_id();
		
		echo json_encode(array(
			'status' => 'success',
			'id' => $id,
			'name' => $name
		));
	}
	
	/**
	 * Delete Image via AJAX
	 *
	 * @param	int	$id	Image ID
	 * @return	void
	 */
	public function textbook_delete_image($id = NULL)
	{
		if (empty($id))
		{
			echo json_encode(array('status' => 'error', 'message' => 'Invalid image ID'));
			return;
		}
		
		// Get image data
		$this->db->where('id', $id);
		$image = $this->db->get('erp_textbook_images')->row_array();
		
		if (!$image)
		{
			echo json_encode(array('status' => 'error', 'message' => 'Image not found'));
			return;
		}
		
		// Verify textbook belongs to this vendor
		$this->db->where('id', $image['textbook_id']);
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$textbook = $this->db->get('erp_textbooks')->row_array();
		
		if (!$textbook)
		{
			echo json_encode(array('status' => 'error', 'message' => 'Unauthorized access'));
			return;
		}
		
		// Delete physical file
		$image_path = FCPATH . $image['image_path'];
		if (file_exists($image_path))
		{
			@unlink($image_path);
		}
		
		// Delete from database
		$this->db->where('id', $id);
		$this->db->delete('erp_textbook_images');
		
		echo json_encode(array('status' => 'success', 'message' => 'Image deleted successfully'));
	}
	
	/**
	 * Notebook Index
	 */
	public function notebook_index()
	{
		// Filters
		$filters = array();
		if ($this->input->get('status'))
		{
			$filters['status'] = $this->input->get('status');
		}
		if ($this->input->get('brand_id'))
		{
			$filters['brand_id'] = $this->input->get('brand_id');
		}
		if ($this->input->get('type_id'))
		{
			$filters['type_id'] = $this->input->get('type_id');
		}
		if ($this->input->get('binding_type'))
		{
			$filters['binding_type'] = $this->input->get('binding_type');
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
		
		// Build query
		$this->db->select('n.*, GROUP_CONCAT(DISTINCT tt.name SEPARATOR ", ") as type_names, tp.name as brand_name');
		$this->db->from('erp_notebooks n');
		$this->db->join('erp_textbook_publishers tp', 'tp.id = n.brand_id', 'left');
		$this->db->join('erp_notebook_type_mapping ntm', 'ntm.notebook_id = n.id', 'left');
		$this->db->join('erp_textbook_types tt', 'tt.id = ntm.type_id', 'left');
		$this->db->where('n.vendor_id', $this->current_vendor['id']);
		
		if (!empty($filters['status']))
		{
			$this->db->where('n.status', $filters['status']);
		}
		if (!empty($filters['brand_id']))
		{
			$this->db->where('n.brand_id', $filters['brand_id']);
		}
		if (!empty($filters['binding_type']))
		{
			$this->db->where('n.binding_type', $filters['binding_type']);
		}
		if (!empty($filters['type_id']))
		{
			$this->db->where('ntm.type_id', $filters['type_id']);
		}
		if (!empty($filters['search']))
		{
			$this->db->group_start();
			$this->db->like('n.product_name', $filters['search']);
			$this->db->or_like('n.isbn', $filters['search']);
			$this->db->or_like('n.sku', $filters['search']);
			$this->db->group_end();
		}
		
		$this->db->group_by('n.id');
		$this->db->order_by('n.id', 'DESC');
		
		// Get total count
		$total_query = clone $this->db;
		$total_notebooks = $total_query->count_all_results('', FALSE);
		
		// Apply pagination
		$this->db->limit($per_page, $offset);
		$notebooks = $this->db->get()->result_array();
		
		// Enhance notebooks data with images
		foreach ($notebooks as &$notebook)
		{
			// Get main image first (is_main = 1), fallback to first image if no main image
			$this->db->select('image_path');
			$this->db->from('erp_notebook_images');
			$this->db->where('notebook_id', $notebook['id']);
			$this->db->where('is_main', 1);
			$this->db->limit(1);
			$image = $this->db->get()->row_array();
			
			// If no main image found, get first image by order
			if (!$image) {
			$this->db->select('image_path');
			$this->db->from('erp_notebook_images');
			$this->db->where('notebook_id', $notebook['id']);
			$this->db->order_by('image_order', 'ASC');
			$this->db->limit(1);
			$image = $this->db->get()->row_array();
			}
			
			$notebook['thumbnail'] = $image ? $image['image_path'] : NULL;
		}
		unset($notebook);
		
		// Get filter dropdown data
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$this->db->where('status', 'active');
		$this->db->order_by('name', 'ASC');
		$data['types'] = $this->db->get('erp_textbook_types')->result_array();
		
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$this->db->where('status', 'active');
		$this->db->order_by('name', 'ASC');
		$data['brands'] = $this->db->get('erp_textbook_publishers')->result_array();
		
		$data['notebook_list'] = $notebooks;
		$data['total_notebooks'] = $total_notebooks;
		$data['per_page'] = $per_page;
		$data['current_page'] = $page;
		$data['total_pages'] = ceil($total_notebooks / $per_page);
		$data['filters'] = $filters;
		$data['title'] = 'Manage Notebooks';
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $this->getVendorDomainForUrl();
		$data['breadcrumb'] = array(
			array('label' => 'Dashboard', 'url' => base_url($this->current_vendor['domain'] . '/dashboard')),
			array('label' => 'Notebooks', 'active' => true)
		);
		
		// Load content view
		$data['content'] = $this->load->view('vendor/products/books/notebooks/index', $data, TRUE);
		
		// Load main layout
		$this->load->view('vendor/layouts/index_template', $data);
	}
	
	/**
	 * Bookset Index - Show all bookset packages
	 */
	public function bookset_index()
	{
		$this->load->model('School_board_model');
		
		// Check if packages table exists
		if (!$this->db->table_exists('erp_bookset_packages'))
		{
			$data['bookset_list'] = array();
			$data['total_booksets'] = 0;
			$data['per_page'] = 10;
			$data['current_page'] = 1;
			$data['total_pages'] = 0;
			$data['active_tab'] = 'with_product';
			$data['filters'] = array();
			$data['title'] = 'Manage Booksets';
			$data['current_vendor'] = $this->current_vendor;
			$data['vendor_domain'] = $this->getVendorDomainForUrl();
			$data['breadcrumb'] = array(
				array('label' => 'Dashboard', 'url' => base_url($this->current_vendor['domain'] . '/dashboard')),
				array('label' => 'Products', 'url' => '#'),
				array('label' => 'Books', 'url' => '#'),
				array('label' => 'Booksets', 'active' => true)
			);
			$data['content'] = $this->load->view('vendor/products/books/bookset/index', $data, TRUE);
			$this->load->view('vendor/layouts/index_template', $data);
			return;
		}
		
		// Get active tab (with_product or without_product)
		$active_tab = $this->input->get('tab');
		if (!in_array($active_tab, array('with_product', 'without_product'))) {
			$active_tab = 'with_product'; // Default to "with product"
		}
		
		// Filters
		$filters = array();
		if ($this->input->get('status'))
		{
			$filters['status'] = $this->input->get('status');
		}
		if ($this->input->get('type'))
		{
			$filters['type'] = $this->input->get('type');
		}
		if ($this->input->get('school_id'))
		{
			$filters['school_id'] = $this->input->get('school_id');
		}
		if ($this->input->get('board_id'))
		{
			$filters['board_id'] = $this->input->get('board_id');
		}
		if ($this->input->get('grade_id'))
		{
			$filters['grade_id'] = $this->input->get('grade_id');
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
		
		// Check if booksets table exists, if not fall back to packages
		$use_booksets = $this->db->table_exists('erp_booksets');
		
		if ($use_booksets) {
			// Build query for booksets
			$this->db->select('bs.*, s.school_name, b.board_name, tg.name as grade_name');
			$this->db->from('erp_booksets bs');
			$this->db->join('erp_schools s', 's.id = bs.school_id', 'left');
			$this->db->join('erp_school_boards b', 'b.id = bs.board_id', 'left');
			$this->db->join('erp_textbook_grades tg', 'tg.id = bs.grade_id', 'left');
			$this->db->where('bs.vendor_id', $this->current_vendor['id']);
		} else {
			// Fallback to packages (old structure)
			$this->db->select('bp.*, s.school_name, b.board_name, tg.name as grade_name, bc.name as category_name');
			$this->db->from('erp_bookset_packages bp');
			$this->db->join('erp_schools s', 's.id = bp.school_id', 'left');
			$this->db->join('erp_school_boards b', 'b.id = bp.board_id', 'left');
			$this->db->join('erp_textbook_grades tg', 'tg.id = bp.grade_id', 'left');
			$this->db->join('erp_bookset_categories bc', 'bc.id = bp.category_id', 'left');
			$this->db->where('bp.vendor_id', $this->current_vendor['id']);
		}
		
		// Apply filters
		if (!empty($filters['status']))
		{
			if ($use_booksets) {
				$this->db->where('bs.status', $filters['status']);
			} else {
				$this->db->where('bp.status', $filters['status']);
			}
		}
		if (!empty($filters['school_id']))
		{
			if ($use_booksets) {
				$this->db->where('bs.school_id', $filters['school_id']);
			} else {
				$this->db->where('bp.school_id', $filters['school_id']);
			}
		}
		if (!empty($filters['board_id']))
		{
			if ($use_booksets) {
				$this->db->where('bs.board_id', $filters['board_id']);
			} else {
				$this->db->where('bp.board_id', $filters['board_id']);
			}
		}
		if (!empty($filters['grade_id']))
		{
			if ($use_booksets) {
				$this->db->where('bs.grade_id', $filters['grade_id']);
			} else {
				$this->db->where('bp.grade_id', $filters['grade_id']);
			}
		}
		if (!empty($filters['search']))
		{
			$this->db->group_start();
			if ($use_booksets) {
				$this->db->like('bs.bookset_name', $filters['search']);
				$this->db->or_like('s.school_name', $filters['search']);
				$this->db->or_like('b.board_name', $filters['search']);
			} else {
				$this->db->like('bp.package_name', $filters['search']);
				$this->db->or_like('s.school_name', $filters['search']);
				$this->db->or_like('b.board_name', $filters['search']);
				$this->db->or_like('bc.name', $filters['search']);
			}
			$this->db->group_end();
		}
		
		// Get ALL booksets/packages (without pagination limit)
		if ($use_booksets) {
			$this->db->order_by('bs.created_at', 'DESC');
		} else {
			$this->db->order_by('bp.created_at', 'DESC');
		}
		$all_items = $this->db->get()->result_array();
		
		// Fetch additional data for each bookset/package
		$filtered_bookset_list = array();
		foreach ($all_items as &$item)
		{
			if ($use_booksets) {
				// For booksets: fetch packages and products
				$item['type'] = 'bookset';
				
				// Get packages for this bookset
				$this->db->select('bp.*');
				$this->db->from('erp_bookset_packages bp');
				$this->db->where('bp.bookset_id', $item['id']);
				// Filter by type if specified
				if (!empty($filters['type'])) {
					$this->db->where('bp.category', $filters['type']);
				}
				$this->db->order_by('bp.created_at', 'ASC');
				$packages = $this->db->get()->result_array();
				
				$item['packages'] = array();
				$total_products = 0;
				$item['thumbnail'] = NULL;
				
				// Get school image instead of product thumbnail
				if (!empty($item['school_id'])) {
					$this->db->select('image_path');
					$this->db->from('erp_school_images');
					$this->db->where('school_id', $item['school_id']);
					$this->db->where('is_primary', 1);
					$this->db->limit(1);
					$school_image = $this->db->get()->row_array();
					if (!$school_image) {
						$this->db->select('image_path');
						$this->db->from('erp_school_images');
						$this->db->where('school_id', $item['school_id']);
						$this->db->order_by('id', 'ASC');
						$this->db->limit(1);
						$school_image = $this->db->get()->row_array();
					}
					$item['thumbnail'] = $school_image ? $school_image['image_path'] : NULL;
				}
				
				foreach ($packages as $package) {
					// Get products for this package
					$this->db->select('bpp.*');
					$this->db->from('erp_bookset_package_products bpp');
					$this->db->where('bpp.package_id', $package['id']);
					$this->db->where('bpp.status', 'active');
					$products = $this->db->get()->result_array();
					
					$package['products'] = $products;
					$package['product_count'] = count($products);
					$total_products += count($products);
					
					$item['packages'][] = $package;
				}
				
				$item['package_count'] = count($packages);
				$item['product_count'] = $total_products;
				
				// If type filter was applied and no packages match, skip this bookset
				if (!empty($filters['type']) && count($packages) == 0) {
					continue;
				}
				
				// Filter based on active tab
				if (($active_tab == 'with_product' && $item['has_products'] == 1) || 
				    ($active_tab == 'without_product' && $item['has_products'] == 0)) {
					$filtered_bookset_list[] = $item;
				}
			} else {
				// Fallback: old package structure
				$item['type'] = 'package';
				$item['package_count'] = 1;
				
				// Get package products count
				$this->db->select('COUNT(*) as product_count');
				$this->db->from('erp_bookset_package_products');
				$this->db->where('package_id', $item['id']);
				$this->db->where('status', 'active');
				$product_count_result = $this->db->get()->row_array();
				$item['product_count'] = $product_count_result['product_count'];
				
				// Get products
				$this->db->select('bpp.*');
				$this->db->from('erp_bookset_package_products bpp');
				$this->db->where('bpp.package_id', $item['id']);
				$this->db->where('bpp.status', 'active');
				$item['packages'] = array(array(
					'id' => $item['id'],
					'package_name' => $item['package_name'],
					'products' => $this->db->get()->result_array()
				));
				
				// Get thumbnail
				$item['thumbnail'] = NULL;
				if ($item['with_product'] == 1 && $item['product_count'] > 0) {
					$first_product = $item['packages'][0]['products'][0] ?? NULL;
					if ($first_product) {
						if ($first_product['product_type'] == 'textbook') {
							$this->db->select('image_path');
							$this->db->from('erp_textbook_images');
							$this->db->where('textbook_id', $first_product['product_id']);
							$this->db->where('is_main', 1);
							$this->db->limit(1);
							$image = $this->db->get()->row_array();
							if (!$image) {
								$this->db->select('image_path');
								$this->db->from('erp_textbook_images');
								$this->db->where('textbook_id', $first_product['product_id']);
								$this->db->order_by('image_order', 'ASC');
								$this->db->limit(1);
								$image = $this->db->get()->row_array();
							}
							$item['thumbnail'] = $image ? $image['image_path'] : NULL;
						} elseif ($first_product['product_type'] == 'notebook') {
							$this->db->select('image_path');
							$this->db->from('erp_notebook_images');
							$this->db->where('notebook_id', $first_product['product_id']);
							$this->db->where('is_main', 1);
							$this->db->limit(1);
							$image = $this->db->get()->row_array();
							if (!$image) {
								$this->db->select('image_path');
								$this->db->from('erp_notebook_images');
								$this->db->where('notebook_id', $first_product['product_id']);
								$this->db->order_by('image_order', 'ASC');
								$this->db->limit(1);
								$image = $this->db->get()->row_array();
							}
							$item['thumbnail'] = $image ? $image['image_path'] : NULL;
						}
					}
				}
				
				// Filter by type if specified
				if (!empty($filters['type'])) {
					if (!isset($item['category']) || $item['category'] != $filters['type']) {
						continue; // Skip this package if it doesn't match the type filter
					}
				}
				
				// Filter based on active tab
				if (($active_tab == 'with_product' && $item['with_product'] == 1) || 
				    ($active_tab == 'without_product' && $item['with_product'] == 0)) {
					$filtered_bookset_list[] = $item;
				}
			}
		}
		unset($item);
		
		// Recalculate pagination for filtered results
		$total_booksets = count($filtered_bookset_list);
		$total_pages = ceil($total_booksets / $per_page);
		
		// Apply pagination to filtered list
		$paginated_bookset_list = array_slice($filtered_bookset_list, $offset, $per_page);
		
		$data['bookset_list'] = $paginated_bookset_list;
		$data['active_tab'] = $active_tab;
		
		// Get schools for filter dropdown
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$this->db->where('status', 'active');
		$this->db->order_by('school_name', 'ASC');
		$data['schools'] = $this->db->get('erp_schools')->result_array();
		
		// Get boards for filter dropdown
		$data['boards'] = $this->School_board_model->getBoardsByVendor($this->current_vendor['id']);
		
		// Get grades for filter dropdown
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$this->db->where('status', 'active');
		$this->db->order_by('name', 'ASC');
		$data['grades'] = $this->db->get('erp_textbook_grades')->result_array();
		
		// Types for filter dropdown (Textbook, Notebook, and Stationery)
		$data['types'] = array(
			array('id' => 'textbook', 'name' => 'Textbook'),
			array('id' => 'notebook', 'name' => 'Notebook'),
			array('id' => 'stationery', 'name' => 'Stationery')
		);
		
		$data['total_booksets'] = $total_booksets;
		$data['per_page'] = $per_page;
		$data['current_page'] = $page;
		$data['total_pages'] = $total_pages;
		$data['filters'] = $filters;
		$data['title'] = 'Manage Booksets';
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $this->getVendorDomainForUrl();
		$data['breadcrumb'] = array(
			array('label' => 'Dashboard', 'url' => base_url($this->current_vendor['domain'] . '/dashboard')),
			array('label' => 'Products', 'url' => '#'),
			array('label' => 'Books', 'url' => '#'),
			array('label' => 'Booksets', 'active' => true)
		);
		
		// Load content view
		$data['content'] = $this->load->view('vendor/products/books/bookset/index', $data, TRUE);
		
		// Load main layout
		$this->load->view('vendor/layouts/index_template', $data);
	}
	
	/**
	 * AJAX: Add Bookset Category
	 */
	public function bookset_package_add_category()
	{
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('name', 'Category Name', 'required|trim');
		
		if ($this->form_validation->run() == FALSE)
		{
			$response = array(
				'success' => false,
				'message' => validation_errors()
			);
		}
		else
		{
			// Check if table exists
			if (!$this->db->table_exists('erp_bookset_categories'))
			{
				$response = array(
					'success' => false,
					'message' => 'Categories table does not exist. Please create the erp_bookset_categories table first.'
				);
			}
			else
			{
				$category_data = array(
					'vendor_id' => $this->current_vendor['id'],
					'name' => $this->input->post('name'),
					'description' => $this->input->post('description') ? $this->input->post('description') : NULL,
					'status' => 'active',
					'created_at' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s')
				);
				
				$this->db->insert('erp_bookset_categories', $category_data);
				$category_id = $this->db->insert_id();
				
				if ($category_id)
				{
					$response = array(
						'success' => true,
						'message' => 'Category added successfully',
						'category' => array(
							'id' => $category_id,
							'name' => $category_data['name']
						)
					);
				}
				else
				{
					$response = array(
						'success' => false,
						'message' => 'Failed to add category'
					);
				}
			}
		}
		
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	/**
	 * AJAX: Get Products for Bookset Package (is_set = 1)
	 */
	public function bookset_package_get_products()
	{
		header('Content-Type: application/json');
		
		try {
			$filters = array();
			if ($this->input->get('publisher_id') && $this->input->get('publisher_id') != '')
			{
				$filters['publisher_id'] = $this->input->get('publisher_id');
			}
			if ($this->input->get('board_id') && $this->input->get('board_id') != '')
			{
				$filters['board_id'] = $this->input->get('board_id');
			}
			if ($this->input->get('type_id') && $this->input->get('type_id') != '')
			{
				$filters['type_id'] = $this->input->get('type_id');
			}
			
			// Get textbooks with is_set = 1
			$this->db->select('t.id, t.product_name, t.sku, t.isbn, t.packaging_weight, p.name as publisher_name, b.board_name, ti.image_path as main_image');
			$this->db->from('erp_textbooks t');
			$this->db->join('erp_textbook_publishers p', 'p.id = t.publisher_id', 'left');
			$this->db->join('erp_school_boards b', 'b.id = t.board_id', 'left');
			$this->db->join('erp_textbook_images ti', 'ti.textbook_id = t.id AND ti.is_main = 1', 'left');
			$this->db->where('t.vendor_id', $this->current_vendor['id']);
			$this->db->where('t.is_set', 1);
			$this->db->where('t.status', 'active');
			
			if (!empty($filters['publisher_id']))
			{
				$this->db->where('t.publisher_id', $filters['publisher_id']);
			}
			if (!empty($filters['board_id']))
			{
				$this->db->where('t.board_id', $filters['board_id']);
			}
			if (!empty($filters['type_id']))
			{
				$this->db->join('erp_textbook_type_mapping ttm', 'ttm.textbook_id = t.id', 'inner');
				$this->db->where('ttm.type_id', $filters['type_id']);
			}
			
			$this->db->group_by('t.id');
			$this->db->order_by('t.product_name', 'ASC');
			$textbooks = $this->db->get()->result_array();
			
			// Get notebooks with is_set = 1
			$this->db->select('n.id, n.product_name, n.sku, n.isbn, n.packaging_weight, p.name as publisher_name, ni.image_path as main_image');
			$this->db->from('erp_notebooks n');
			$this->db->join('erp_textbook_publishers p', 'p.id = n.brand_id', 'left');
			$this->db->join('erp_notebook_images ni', 'ni.notebook_id = n.id AND ni.is_main = 1', 'left');
			$this->db->where('n.vendor_id', $this->current_vendor['id']);
			$this->db->where('n.is_set', 1);
			$this->db->where('n.status', 'active');
			
			if (!empty($filters['publisher_id']))
			{
				$this->db->where('n.brand_id', $filters['publisher_id']);
			}
			if (!empty($filters['type_id']))
			{
				$this->db->join('erp_notebook_type_mapping ntm', 'ntm.notebook_id = n.id', 'inner');
				$this->db->where('ntm.type_id', $filters['type_id']);
			}
			
			$this->db->group_by('n.id');
			$this->db->order_by('n.product_name', 'ASC');
			$notebooks = $this->db->get()->result_array();
			
			// Combine and format results
			$products = array();
			foreach ($textbooks as $textbook)
			{
				$products[] = array(
					'id' => 'textbook_' . $textbook['id'],
					'product_id' => $textbook['id'],
					'product_type' => 'textbook',
					'product_name' => $textbook['product_name'],
					'sku' => $textbook['sku'],
					'isbn' => $textbook['isbn'],
					'packaging_weight' => isset($textbook['packaging_weight']) ? $textbook['packaging_weight'] : NULL,
					'main_image' => isset($textbook['main_image']) ? $textbook['main_image'] : NULL,
					'publisher_name' => $textbook['publisher_name'],
					'board_name' => $textbook['board_name']
				);
			}
			foreach ($notebooks as $notebook)
			{
				$products[] = array(
					'id' => 'notebook_' . $notebook['id'],
					'product_id' => $notebook['id'],
					'product_type' => 'notebook',
					'product_name' => $notebook['product_name'],
					'sku' => $notebook['sku'],
					'isbn' => $notebook['isbn'],
					'packaging_weight' => isset($notebook['packaging_weight']) ? $notebook['packaging_weight'] : NULL,
					'main_image' => isset($notebook['main_image']) ? $notebook['main_image'] : NULL,
					'publisher_name' => isset($notebook['publisher_name']) ? $notebook['publisher_name'] : NULL,
					'board_name' => NULL
				);
			}
			
			echo json_encode(array('success' => true, 'products' => $products));
		}
		catch (Exception $e)
		{
			echo json_encode(array('success' => false, 'message' => $e->getMessage(), 'products' => array()));
		}
	}
	
	/**
	 * AJAX: Get Products by Type (for bookset with products form)
	 */
	public function bookset_package_get_products_by_type()
	{
		header('Content-Type: application/json');
		
		try {
			$category = $this->input->get('category');
			
			if (empty($category) || !in_array($category, array('textbook', 'notebook', 'stationery')))
			{
				echo json_encode(array('success' => false, 'message' => 'Invalid category', 'products' => array()));
				return;
			}
			
			$products = array();
			
			if ($category == 'textbook')
			{
				// Get all textbooks with is_set = 1 (no type filtering, but include type names for display)
				$this->db->select('t.id, t.product_name, t.sku, t.isbn, t.packaging_weight, p.name as publisher_name, b.board_name, ti.image_path as main_image, GROUP_CONCAT(DISTINCT tt.name ORDER BY tt.name SEPARATOR ", ") as type_names');
				$this->db->from('erp_textbooks t');
				$this->db->join('erp_textbook_publishers p', 'p.id = t.publisher_id', 'left');
				$this->db->join('erp_school_boards b', 'b.id = t.board_id', 'left');
				$this->db->join('erp_textbook_images ti', 'ti.textbook_id = t.id AND ti.is_main = 1', 'left');
				$this->db->join('erp_textbook_type_mapping ttm', 'ttm.textbook_id = t.id', 'left');
				$this->db->join('erp_textbook_types tt', 'tt.id = ttm.type_id', 'left');
				$this->db->where('t.vendor_id', $this->current_vendor['id']);
				$this->db->where('t.is_set', 1);
				$this->db->where('t.status', 'active');
				$this->db->group_by('t.id');
				$this->db->order_by('t.product_name', 'ASC');
				$textbooks = $this->db->get()->result_array();
				
				foreach ($textbooks as $textbook)
				{
					// If no main image, get first image by order
					if (empty($textbook['main_image']))
					{
						$this->db->select('image_path');
						$this->db->from('erp_textbook_images');
						$this->db->where('textbook_id', $textbook['id']);
						$this->db->order_by('image_order', 'ASC');
						$this->db->limit(1);
						$image = $this->db->get()->row_array();
						$textbook['main_image'] = $image ? $image['image_path'] : NULL;
					}
					
					$products[] = array(
						'id' => 'textbook_' . $textbook['id'],
						'product_id' => $textbook['id'],
						'product_type' => 'textbook',
						'type_names' => $textbook['type_names'] ? $textbook['type_names'] : '',
						'product_name' => $textbook['product_name'],
						'sku' => $textbook['sku'],
						'isbn' => $textbook['isbn'],
						'packaging_weight' => isset($textbook['packaging_weight']) ? $textbook['packaging_weight'] : NULL,
						'main_image' => isset($textbook['main_image']) ? $textbook['main_image'] : NULL,
						'publisher_name' => $textbook['publisher_name'],
						'board_name' => $textbook['board_name']
					);
				}
			}
			elseif ($category == 'notebook')
			{
				// Get all notebooks with is_set = 1 (no type filtering, but include type names for display)
				$this->db->select('n.id, n.product_name, n.sku, n.isbn, n.packaging_weight, p.name as publisher_name, ni.image_path as main_image, GROUP_CONCAT(DISTINCT tt.name ORDER BY tt.name SEPARATOR ", ") as type_names');
				$this->db->from('erp_notebooks n');
				$this->db->join('erp_textbook_publishers p', 'p.id = n.brand_id', 'left');
				$this->db->join('erp_notebook_images ni', 'ni.notebook_id = n.id AND ni.is_main = 1', 'left');
				$this->db->join('erp_notebook_type_mapping ntm', 'ntm.notebook_id = n.id', 'left');
				$this->db->join('erp_textbook_types tt', 'tt.id = ntm.type_id', 'left');
				$this->db->where('n.vendor_id', $this->current_vendor['id']);
				$this->db->where('n.is_set', 1);
				$this->db->where('n.status', 'active');
				$this->db->group_by('n.id');
				$this->db->order_by('n.product_name', 'ASC');
				$notebooks = $this->db->get()->result_array();
				
				foreach ($notebooks as $notebook)
				{
					// If no main image, get first image by order
					if (empty($notebook['main_image']))
					{
						$this->db->select('image_path');
						$this->db->from('erp_notebook_images');
						$this->db->where('notebook_id', $notebook['id']);
						$this->db->order_by('image_order', 'ASC');
						$this->db->limit(1);
						$image = $this->db->get()->row_array();
						$notebook['main_image'] = $image ? $image['image_path'] : NULL;
					}
					
					$products[] = array(
						'id' => 'notebook_' . $notebook['id'],
						'product_id' => $notebook['id'],
						'product_type' => 'notebook',
						'type_names' => $notebook['type_names'] ? $notebook['type_names'] : '',
						'product_name' => $notebook['product_name'],
						'sku' => $notebook['sku'],
						'isbn' => $notebook['isbn'],
						'packaging_weight' => isset($notebook['packaging_weight']) ? $notebook['packaging_weight'] : NULL,
						'main_image' => isset($notebook['main_image']) ? $notebook['main_image'] : NULL,
						'publisher_name' => isset($notebook['publisher_name']) ? $notebook['publisher_name'] : NULL,
						'board_name' => NULL
					);
				}
			}
			elseif ($category == 'stationery')
			{
				// Get all stationery products with is_set = 1
				$this->db->select('s.id, s.product_name, s.sku, s.isbn, s.packaging_weight, si.image_path as main_image, c.name as category_name');
				$this->db->from('erp_stationery s');
				$this->db->join('erp_stationery_images si', 'si.stationery_id = s.id AND si.is_main = 1', 'left');
				$this->db->join('erp_stationery_categories c', 'c.id = s.category_id', 'left');
				$this->db->where('s.vendor_id', $this->current_vendor['id']);
				$this->db->where('s.is_set', 1);
				$this->db->where('s.status', 'active');
				$this->db->group_by('s.id');
				$this->db->order_by('s.product_name', 'ASC');
				$stationery_products = $this->db->get()->result_array();
				
				foreach ($stationery_products as $stationery)
				{
					// If no main image, get first image by order
					if (empty($stationery['main_image']))
					{
						$this->db->select('image_path');
						$this->db->from('erp_stationery_images');
						$this->db->where('stationery_id', $stationery['id']);
						$this->db->order_by('image_order', 'ASC');
						$this->db->limit(1);
						$image = $this->db->get()->row_array();
						$stationery['main_image'] = $image ? $image['image_path'] : NULL;
					}
					
					$products[] = array(
						'id' => 'stationery_' . $stationery['id'],
						'product_id' => $stationery['id'],
						'product_type' => 'stationery',
						'type_names' => $stationery['category_name'] ? $stationery['category_name'] : '',
						'product_name' => $stationery['product_name'],
						'sku' => $stationery['sku'],
						'isbn' => isset($stationery['isbn']) ? $stationery['isbn'] : '',
						'packaging_weight' => isset($stationery['packaging_weight']) ? $stationery['packaging_weight'] : NULL,
						'main_image' => isset($stationery['main_image']) ? $stationery['main_image'] : NULL,
						'publisher_name' => NULL,
						'board_name' => NULL
					);
				}
			}
			
			echo json_encode(array('success' => true, 'products' => $products));
		}
		catch (Exception $e)
		{
			echo json_encode(array('success' => false, 'message' => $e->getMessage(), 'products' => array()));
		}
	}
	
	/**
	 * Bookset Package Add With Products (Multi-step form)
	 */
	public function bookset_package_add_with_products()
	{
		$this->load->library('form_validation');
		$this->load->model('School_board_model');
		
		// Handle form submission
		if ($this->input->method() == 'post')
		{
			// Get packages data first to check if mandatory_packages is needed
			$packages_data_json = $this->input->post('packages_data');
			$packages_data = json_decode($packages_data_json, TRUE);
			
			// Check if there are any mandatory+optional packages
			$has_mandatory_optional = FALSE;
			if (!empty($packages_data) && is_array($packages_data))
			{
				foreach ($packages_data as $pkg)
				{
					if (isset($pkg['is_it']) && $pkg['is_it'] == 'mandatory+optional')
					{
						$has_mandatory_optional = TRUE;
						break;
					}
				}
			}
			
			// Set validation rules
			$this->form_validation->set_rules('school_id', 'School', 'required');
			$this->form_validation->set_rules('board_id', 'Board', 'required');
			$this->form_validation->set_rules('grade_id', 'Grade', 'required');
			
			// Only require mandatory_packages if there are mandatory+optional packages
			if ($has_mandatory_optional)
			{
				$this->form_validation->set_rules('mandatory_packages', 'Mandatory Packages', 'required|numeric');
			}
			else
			{
				// If no mandatory+optional packages, set to 0
				$_POST['mandatory_packages'] = 0;
				$this->form_validation->set_rules('mandatory_packages', 'Mandatory Packages', 'numeric');
			}
			
			$this->form_validation->set_rules('status', 'Status', 'required|in_list[active,inactive]');
			$this->form_validation->set_rules('packages_data', 'Packages', 'required');
			
			if ($this->form_validation->run() == FALSE)
			{
				$this->session->set_flashdata('error', validation_errors());
			}
			else
			{
				if (empty($packages_data) || !is_array($packages_data))
				{
					$this->session->set_flashdata('error', 'Please add at least one package.');
				}
				else
				{
					// Validate products - check that discounted_mrp is required and > 0
					$validation_errors = array();
					foreach ($packages_data as $pkg_index => $pkg)
					{
						if (isset($pkg['products']) && is_array($pkg['products']) && count($pkg['products']) > 0)
						{
							foreach ($pkg['products'] as $product_index => $product)
							{
								if (!isset($product['discounted_mrp']) || 
									$product['discounted_mrp'] === '' || 
									$product['discounted_mrp'] === null || 
									!is_numeric($product['discounted_mrp']) || 
									floatval($product['discounted_mrp']) <= 0)
								{
									$validation_errors[] = 'Package ' . ($pkg_index + 1) . ', Product ' . ($product_index + 1) . ': Discounted MRP is required and must be greater than 0';
								}
							}
						}
					}
					
					if (!empty($validation_errors))
					{
						$this->session->set_flashdata('error', implode('<br>', $validation_errors));
					}
					else
					{
						// Start transaction
						$this->db->trans_start();
						
						// Calculate package counts
						$mandatory_count = 0;
						$optional_count = 0;
						$mandatory_optional_count = 0;
						
						foreach ($packages_data as $pkg)
						{
							if ($pkg['is_it'] == 'mandatory')
							{
								$mandatory_count++;
							}
							elseif ($pkg['is_it'] == 'optional')
							{
								$optional_count++;
							}
							elseif ($pkg['is_it'] == 'mandatory+optional')
							{
								$mandatory_optional_count++;
							}
						}
						
						// Create bookset
						$bookset_data = array(
							'vendor_id' => $this->current_vendor['id'],
							'school_id' => $this->input->post('school_id'),
							'board_id' => $this->input->post('board_id'),
							'grade_id' => $this->input->post('grade_id'),
							'bookset_name' => $this->input->post('bookset_name') ? $this->input->post('bookset_name') : NULL,
							'has_products' => 1,
							'mandatory_packages' => $this->input->post('mandatory_packages'),
							'total_packages' => count($packages_data),
							'status' => $this->input->post('status'),
							'created_at' => date('Y-m-d H:i:s'),
							'updated_at' => date('Y-m-d H:i:s')
						);
						
						$this->db->insert('erp_booksets', $bookset_data);
						$bookset_id = $this->db->insert_id();
						
						if ($bookset_id)
						{
							// Create packages
							foreach ($packages_data as $pkg)
							{
								// Determine package counts
								$pkg_mandatory = 0;
								$pkg_optional = 0;
								$pkg_mandatory_optional = 0;
								
								if ($pkg['is_it'] == 'mandatory')
								{
									$pkg_mandatory = 1;
								}
								elseif ($pkg['is_it'] == 'optional')
								{
									$pkg_optional = 1;
								}
								elseif ($pkg['is_it'] == 'mandatory+optional')
								{
									$pkg_mandatory_optional = 1;
								}
								
								// Create package
								$package_data = array(
									'vendor_id' => $this->current_vendor['id'],
									'bookset_id' => $bookset_id,
									'school_id' => $this->input->post('school_id'),
									'board_id' => $this->input->post('board_id'),
									'grade_id' => $this->input->post('grade_id'),
									'package_name' => $pkg['package_name'],
									'package_weight' => $pkg['weight'],
									'note' => isset($pkg['note']) && !empty($pkg['note']) ? $pkg['note'] : NULL,
									'is_it' => $pkg['is_it'],
									'mandatory_count' => $pkg_mandatory,
									'optional_count' => $pkg_optional,
									'mandatory_optional_count' => $pkg_mandatory_optional,
									'status' => $this->input->post('status'),
									'with_product' => 1,
									'created_at' => date('Y-m-d H:i:s'),
									'updated_at' => date('Y-m-d H:i:s')
								);
								
								// category_id is not used in this flow (packages with products)
								// Set to NULL - the column should be nullable, if not, run:
								// ALTER TABLE `erp_bookset_packages` MODIFY `category_id` INT(11) NULL;
								$package_data['category_id'] = NULL;
								
								// Save category (textbook, notebook, stationery) for filtering
								$package_data['category'] = isset($pkg['category']) && !empty($pkg['category']) ? $pkg['category'] : NULL;
								
								$this->db->insert('erp_bookset_packages', $package_data);
								$package_id = $this->db->insert_id();
								
								if ($package_id && isset($pkg['products']) && is_array($pkg['products']) && count($pkg['products']) > 0)
								{
									// Add multiple products to package
									foreach ($pkg['products'] as $product)
									{
										$product_data = array(
											'package_id' => $package_id,
											'product_type' => isset($product['product_type']) ? $product['product_type'] : $pkg['category'],
											'product_id' => isset($product['product_id']) ? $product['product_id'] : 0,
											'display_name' => isset($product['display_name']) && !empty($product['display_name']) ? $product['display_name'] : (isset($product['product_name']) ? $product['product_name'] : $pkg['package_name']),
											'quantity' => isset($product['quantity']) && $product['quantity'] > 0 ? $product['quantity'] : 1,
											'discounted_mrp' => isset($product['discounted_mrp']) ? $product['discounted_mrp'] : 0,
											'weight' => isset($product['packaging_weight']) ? $product['packaging_weight'] : 0,
											'note' => isset($pkg['note']) && !empty($pkg['note']) ? $pkg['note'] : NULL,
											'status' => 'active',
											'created_at' => date('Y-m-d H:i:s'),
											'updated_at' => date('Y-m-d H:i:s')
										);
										
										$this->db->insert('erp_bookset_package_products', $product_data);
									}
								}
							}
							
							$this->db->trans_complete();
							
							if ($this->db->trans_status() === FALSE)
							{
								$this->session->set_flashdata('error', 'Failed to create bookset. Please try again.');
							}
							else
							{
								$this->session->set_flashdata('success', 'Bookset with products added successfully.');
								redirect(base_url($this->current_vendor['domain'] . '/products/bookset?tab=with_product'));
								return;
							}
						}
						else
						{
							$this->session->set_flashdata('error', 'Failed to create bookset. Please try again.');
						}
					}
				}
			}
		}
		
		// Get schools with state and city for dropdown
		$this->db->select('erp_schools.id, erp_schools.school_name, erp_schools.state_id, erp_schools.city_id, states.name as state_name, cities.name as city_name, "school" as type');
		$this->db->from('erp_schools');
		$this->db->join('states', 'states.id = erp_schools.state_id', 'left');
		$this->db->join('cities', 'cities.id = erp_schools.city_id', 'left');
		$this->db->where('erp_schools.vendor_id', $this->current_vendor['id']);
		$this->db->where('erp_schools.status', 'active');
		$this->db->order_by('erp_schools.school_name', 'ASC');
		$schools = $this->db->get()->result_array();
		
		// Get branches with state and city for dropdown
		$this->load->model('Branch_model');
		$this->db->select('erp_school_branches.id, erp_school_branches.branch_name as school_name, erp_school_branches.state_id, erp_school_branches.city_id, states.name as state_name, cities.name as city_name, "branch" as type, erp_schools.school_name as parent_school_name');
		$this->db->from('erp_school_branches');
		$this->db->join('erp_schools', 'erp_schools.id = erp_school_branches.school_id', 'left');
		$this->db->join('states', 'states.id = erp_school_branches.state_id', 'left');
		$this->db->join('cities', 'cities.id = erp_school_branches.city_id', 'left');
		$this->db->where('erp_school_branches.vendor_id', $this->current_vendor['id']);
		$this->db->where('erp_school_branches.status', 'active');
		$branches = $this->db->get()->result_array();
		
		// Combine schools and branches
		$all_schools = array_merge($schools, $branches);
		
		// Sort alphabetically by school/branch name (A-Z)
		usort($all_schools, function($a, $b) {
			return strcasecmp($a['school_name'], $b['school_name']);
		});
		
		$data['schools'] = $all_schools;
		
		// Get boards for dropdown
		$data['boards'] = $this->School_board_model->getBoardsByVendor($this->current_vendor['id']);
		
		// Get grades for dropdown
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$this->db->where('status', 'active');
		$this->db->order_by('name', 'ASC');
		$data['grades'] = $this->db->get('erp_textbook_grades')->result_array();
		
		// Main categories: Textbook, Notebook, and Stationery
		$data['types'] = array(
			array('id' => 'textbook', 'name' => 'Textbook', 'category' => 'textbook'),
			array('id' => 'notebook', 'name' => 'Notebook', 'category' => 'notebook'),
			array('id' => 'stationery', 'name' => 'Stationery', 'category' => 'stationery')
		);
		
		$data['title'] = 'Add Bookset with Products';
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $this->getVendorDomainForUrl();
		$data['breadcrumb'] = array(
			array('label' => 'Dashboard', 'url' => base_url($this->current_vendor['domain'] . '/dashboard')),
			array('label' => 'Products', 'url' => '#'),
			array('label' => 'Books', 'url' => '#'),
			array('label' => 'Booksets', 'url' => base_url($this->current_vendor['domain'] . '/products/bookset?tab=with_product')),
			array('label' => 'Add Bookset with Products', 'active' => true)
		);
		
		// Load content view
		$data['content'] = $this->load->view('vendor/products/books/bookset/package/add_with_products', $data, TRUE);
		
		// Load main layout
		$this->load->view('vendor/layouts/index_template', $data);
	}
	
	/**
	 * Bookset Package Add Without Products
	 */
	public function bookset_package_add_without_products()
	{
		$this->load->library('form_validation');
		$this->load->model('School_board_model');
		
		// Handle form submission
		if ($this->input->method() == 'post')
		{
			// Get packages data first to check if mandatory_packages is needed
			$packages_data_json = $this->input->post('packages_data');
			$packages_data = json_decode($packages_data_json, TRUE);
			
			// Check if there are any mandatory+optional packages
			$has_mandatory_optional = FALSE;
			if (!empty($packages_data) && is_array($packages_data))
			{
				foreach ($packages_data as $pkg)
				{
					if (isset($pkg['is_it']) && $pkg['is_it'] == 'mandatory+optional')
					{
						$has_mandatory_optional = TRUE;
						break;
					}
				}
			}
			
			// Set validation rules
			$this->form_validation->set_rules('school_id', 'School', 'required');
			$this->form_validation->set_rules('board_id', 'Board', 'required');
			$this->form_validation->set_rules('grade_id', 'Grade', 'required');
			
			// Only require mandatory_packages if there are mandatory+optional packages
			if ($has_mandatory_optional)
			{
				$this->form_validation->set_rules('mandatory_packages', 'Mandatory Packages', 'required|numeric');
			}
			else
			{
				// If no mandatory+optional packages, set to 0
				$_POST['mandatory_packages'] = 0;
				$this->form_validation->set_rules('mandatory_packages', 'Mandatory Packages', 'numeric');
			}
			
			$this->form_validation->set_rules('status', 'Status', 'required|in_list[active,inactive]');
			$this->form_validation->set_rules('packages_data', 'Packages', 'required');
			
			if ($this->form_validation->run() == FALSE)
			{
				$this->session->set_flashdata('error', validation_errors());
			}
			else
			{
				if (empty($packages_data) || !is_array($packages_data))
				{
					$this->session->set_flashdata('error', 'Please add at least one package.');
				}
				else
				{
					// Start transaction
					$this->db->trans_start();
					
					// Calculate package counts
					$mandatory_count = 0;
					$optional_count = 0;
					$mandatory_optional_count = 0;
					
					foreach ($packages_data as $pkg)
					{
						if ($pkg['is_it'] == 'mandatory')
						{
							$mandatory_count++;
						}
						elseif ($pkg['is_it'] == 'optional')
						{
							$optional_count++;
						}
						elseif ($pkg['is_it'] == 'mandatory+optional')
						{
							$mandatory_optional_count++;
						}
					}
					
					// Create bookset
					$bookset_data = array(
						'vendor_id' => $this->current_vendor['id'],
						'school_id' => $this->input->post('school_id'),
						'board_id' => $this->input->post('board_id'),
						'grade_id' => $this->input->post('grade_id'),
						'bookset_name' => $this->input->post('bookset_name') ? $this->input->post('bookset_name') : NULL,
						'has_products' => 0,
						'mandatory_packages' => $this->input->post('mandatory_packages'),
						'total_packages' => count($packages_data),
						'status' => $this->input->post('status'),
						'created_at' => date('Y-m-d H:i:s'),
						'updated_at' => date('Y-m-d H:i:s')
					);
					
					$this->db->insert('erp_booksets', $bookset_data);
					$bookset_id = $this->db->insert_id();
					
					if ($bookset_id)
					{
						// Create packages
						foreach ($packages_data as $pkg)
						{
							// Determine package counts
							$pkg_mandatory = 0;
							$pkg_optional = 0;
							$pkg_mandatory_optional = 0;
							
							if ($pkg['is_it'] == 'mandatory')
							{
								$pkg_mandatory = 1;
							}
							elseif ($pkg['is_it'] == 'optional')
							{
								$pkg_optional = 1;
							}
							elseif ($pkg['is_it'] == 'mandatory+optional')
							{
								$pkg_mandatory_optional = 1;
							}
							
							// Create package
							$package_data = array(
								'vendor_id' => $this->current_vendor['id'],
								'bookset_id' => $bookset_id,
								'school_id' => $this->input->post('school_id'),
								'board_id' => $this->input->post('board_id'),
								'grade_id' => $this->input->post('grade_id'),
								'package_name' => $pkg['package_name'],
								'package_price' => isset($pkg['package_price']) ? $pkg['package_price'] : 0,
								'package_offer_price' => isset($pkg['package_offer_price']) ? $pkg['package_offer_price'] : 0,
								'gst' => isset($pkg['gst']) ? $pkg['gst'] : 0,
								'hsn' => isset($pkg['hsn']) && !empty($pkg['hsn']) ? $pkg['hsn'] : NULL,
								'package_weight' => $pkg['weight'],
								'note' => isset($pkg['note']) && !empty($pkg['note']) ? $pkg['note'] : NULL,
								'is_it' => $pkg['is_it'],
								'mandatory_count' => $pkg_mandatory,
								'optional_count' => $pkg_optional,
								'mandatory_optional_count' => $pkg_mandatory_optional,
								'status' => $this->input->post('status'),
								'with_product' => 0,
								'category_id' => NULL,
								'category' => NULL, // Packages without products don't have a category
								'created_at' => date('Y-m-d H:i:s'),
								'updated_at' => date('Y-m-d H:i:s')
							);
							
							$this->db->insert('erp_bookset_packages', $package_data);
						}
						
						$this->db->trans_complete();
						
						if ($this->db->trans_status() === FALSE)
						{
							$this->session->set_flashdata('error', 'Failed to create bookset. Please try again.');
						}
						else
						{
							$this->session->set_flashdata('success', 'Bookset without products added successfully.');
							redirect(base_url($this->current_vendor['domain'] . '/products/bookset?tab=without_product'));
							return;
						}
					}
					else
					{
						$this->session->set_flashdata('error', 'Failed to create bookset. Please try again.');
					}
				}
			}
		}
		
		// Get schools for dropdown
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$this->db->where('status', 'active');
		$this->db->order_by('school_name', 'ASC');
		$data['schools'] = $this->db->get('erp_schools')->result_array();
		
		// Get boards for dropdown
		$data['boards'] = $this->School_board_model->getBoardsByVendor($this->current_vendor['id']);
		
		// Get grades for dropdown
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$this->db->where('status', 'active');
		$this->db->order_by('name', 'ASC');
		$data['grades'] = $this->db->get('erp_textbook_grades')->result_array();
		
		$data['title'] = 'Add Bookset without Products';
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $this->getVendorDomainForUrl();
		$data['breadcrumb'] = array(
			array('label' => 'Dashboard', 'url' => base_url($this->current_vendor['domain'] . '/dashboard')),
			array('label' => 'Products', 'url' => '#'),
			array('label' => 'Books', 'url' => '#'),
			array('label' => 'Booksets', 'url' => base_url($this->current_vendor['domain'] . '/products/bookset?tab=without_product')),
			array('label' => 'Add Bookset without Products', 'active' => true)
		);
		
		// Load content view
		$data['content'] = $this->load->view('vendor/products/books/bookset/package/add_without_products', $data, TRUE);
		
		// Load main layout
		$this->load->view('vendor/layouts/index_template', $data);
	}
	
	/**
	 * AJAX: Get Boards by School (for bookset with products form)
	 */
	public function bookset_package_get_boards()
	{
		header('Content-Type: application/json');
		
		$this->load->model('School_model');
		$this->load->model('School_board_model');
		$this->load->model('Branch_model');
		
		$school_id = $this->input->get('school_id');
		
		if (empty($school_id))
		{
			echo json_encode(array('status' => 'error', 'message' => 'School ID required'));
			return;
		}
		
		// Check if it's a school or branch
		$school = $this->School_model->getSchoolById($school_id, $this->current_vendor['id']);
		$branch = null;
		$actual_school_id = $school_id;
		
		if (!$school)
		{
			// Try as branch
			$branch = $this->Branch_model->getBranchById($school_id, $this->current_vendor['id']);
			if ($branch)
			{
				$actual_school_id = $branch['school_id']; // Use parent school ID for boards
			}
			else
			{
				echo json_encode(array('status' => 'error', 'message' => 'School/Branch not found'));
				return;
			}
		}
		
		$all_boards = $this->School_board_model->getBoardsByVendor($this->current_vendor['id']);
		
		// Get board IDs from mapping table (use parent school ID if branch)
		$board_ids = $this->School_model->getSchoolBoardIds($actual_school_id);
		
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
	 * Bookset Package Delete
	 */
	public function bookset_package_delete($package_id = NULL)
	{
		if (!$package_id)
		{
			$this->session->set_flashdata('error', 'Invalid package ID.');
			redirect(base_url($this->current_vendor['domain'] . '/products/bookset?tab=with_product'));
			return;
		}
		
		// Get package data to check if it exists
		$this->db->where('id', $package_id);
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$package = $this->db->get('erp_bookset_packages')->row_array();
		
		if (!$package)
		{
			$this->session->set_flashdata('error', 'Package not found.');
			redirect(base_url($this->current_vendor['domain'] . '/products/bookset?tab=with_product'));
			return;
		}
		
		// Start transaction
		$this->db->trans_start();
		
		// Delete package products first
		$this->db->where('package_id', $package_id);
		$this->db->delete('erp_bookset_package_products');
		
		// Delete package
		$this->db->where('id', $package_id);
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$this->db->delete('erp_bookset_packages');
		
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->session->set_flashdata('error', 'Failed to delete package. Please try again.');
		}
		else
		{
			$this->session->set_flashdata('success', 'Bookset package deleted successfully.');
		}
		
		redirect(base_url($this->current_vendor['domain'] . '/products/bookset?tab=with_product'));
	}
	
	/**
	 * Bookset Edit
	 */
	public function bookset_edit($bookset_id = NULL)
	{
		if (!$bookset_id)
		{
			$this->session->set_flashdata('error', 'Invalid bookset ID.');
			redirect(base_url($this->current_vendor['domain'] . '/products/bookset?tab=with_product'));
			return;
		}
		
		$this->load->library('form_validation');
		$this->load->model('School_board_model');
		
		// Get bookset data
		$this->db->where('id', $bookset_id);
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$bookset = $this->db->get('erp_booksets')->row_array();
		
		if (!$bookset)
		{
			$this->session->set_flashdata('error', 'Bookset not found.');
			redirect(base_url($this->current_vendor['domain'] . '/products/bookset?tab=with_product'));
			return;
		}
		
		// Check if this is a bookset with or without products
		$has_products = isset($bookset['has_products']) && $bookset['has_products'] == 1;
		$redirect_tab = $has_products ? 'with_product' : 'without_product';
		
		// Handle form submission
		if ($this->input->method() == 'post')
		{
			// Get packages data first to check if mandatory_packages is needed
			$packages_data_json = $this->input->post('packages_data');
			$packages_data = json_decode($packages_data_json, TRUE);
			
			// Check if there are any mandatory+optional packages
			$has_mandatory_optional = FALSE;
			if (!empty($packages_data) && is_array($packages_data))
			{
				foreach ($packages_data as $pkg)
				{
					if (isset($pkg['is_it']) && $pkg['is_it'] == 'mandatory+optional')
					{
						$has_mandatory_optional = TRUE;
						break;
					}
				}
			}
			
			// Set validation rules
			$this->form_validation->set_rules('school_id', 'School', 'required');
			$this->form_validation->set_rules('board_id', 'Board', 'required');
			$this->form_validation->set_rules('grade_id', 'Grade', 'required');
			
			// Only require mandatory_packages if there are mandatory+optional packages
			if ($has_mandatory_optional)
			{
				$this->form_validation->set_rules('mandatory_packages', 'Mandatory Packages', 'required|numeric');
			}
			else
			{
				// If no mandatory+optional packages, set to 0
				$_POST['mandatory_packages'] = 0;
				$this->form_validation->set_rules('mandatory_packages', 'Mandatory Packages', 'numeric');
			}
			
			$this->form_validation->set_rules('status', 'Status', 'required|in_list[active,inactive]');
			$this->form_validation->set_rules('packages_data', 'Packages', 'required');
			
			if ($this->form_validation->run() == FALSE)
			{
				$this->session->set_flashdata('error', validation_errors());
			}
			else
			{
				if (empty($packages_data) || !is_array($packages_data))
				{
					$this->session->set_flashdata('error', 'Please add at least one package.');
				}
				else
				{
					// Validate products - check that discounted_mrp is required and > 0
					$validation_errors = array();
					foreach ($packages_data as $pkg_index => $pkg)
					{
						if (isset($pkg['products']) && is_array($pkg['products']) && count($pkg['products']) > 0)
						{
							foreach ($pkg['products'] as $product_index => $product)
							{
								if (!isset($product['discounted_mrp']) || 
									$product['discounted_mrp'] === '' || 
									$product['discounted_mrp'] === null || 
									!is_numeric($product['discounted_mrp']) || 
									floatval($product['discounted_mrp']) <= 0)
								{
									$validation_errors[] = 'Package ' . ($pkg_index + 1) . ', Product ' . ($product_index + 1) . ': Discounted MRP is required and must be greater than 0';
								}
							}
						}
					}
					
					if (!empty($validation_errors))
					{
						$this->session->set_flashdata('error', implode('<br>', $validation_errors));
					}
					else
					{
						// Start transaction
						$this->db->trans_start();
						
						// Update bookset
						$bookset_data = array(
							'school_id' => $this->input->post('school_id'),
							'board_id' => $this->input->post('board_id'),
							'grade_id' => $this->input->post('grade_id'),
							'bookset_name' => $this->input->post('bookset_name') ? $this->input->post('bookset_name') : NULL,
							'mandatory_packages' => $this->input->post('mandatory_packages'),
							'total_packages' => count($packages_data),
							'status' => $this->input->post('status'),
							'updated_at' => date('Y-m-d H:i:s')
						);
						
						$this->db->where('id', $bookset_id);
						$this->db->where('vendor_id', $this->current_vendor['id']);
						$this->db->update('erp_booksets', $bookset_data);
					
					// Delete existing packages and their products
					$this->db->select('id');
					$this->db->from('erp_bookset_packages');
					$this->db->where('bookset_id', $bookset_id);
					$existing_packages = $this->db->get()->result_array();
					
					foreach ($existing_packages as $existing_package)
					{
						// Delete package products
						$this->db->where('package_id', $existing_package['id']);
						$this->db->delete('erp_bookset_package_products');
						
						// Delete package
						$this->db->where('id', $existing_package['id']);
						$this->db->delete('erp_bookset_packages');
					}
					
					// Create new packages
					foreach ($packages_data as $pkg)
					{
						// Determine package counts
						$pkg_mandatory = 0;
						$pkg_optional = 0;
						$pkg_mandatory_optional = 0;
						
						if ($pkg['is_it'] == 'mandatory')
						{
							$pkg_mandatory = 1;
						}
						elseif ($pkg['is_it'] == 'optional')
						{
							$pkg_optional = 1;
						}
						elseif ($pkg['is_it'] == 'mandatory+optional')
						{
							$pkg_mandatory_optional = 1;
						}
						
						// Create package
						$package_data = array(
							'vendor_id' => $this->current_vendor['id'],
							'bookset_id' => $bookset_id,
							'school_id' => $this->input->post('school_id'),
							'board_id' => $this->input->post('board_id'),
							'grade_id' => $this->input->post('grade_id'),
							'package_name' => $pkg['package_name'],
							'package_weight' => $pkg['weight'],
							'note' => isset($pkg['note']) && !empty($pkg['note']) ? $pkg['note'] : NULL,
							'is_it' => $pkg['is_it'],
							'mandatory_count' => $pkg_mandatory,
							'optional_count' => $pkg_optional,
							'mandatory_optional_count' => $pkg_mandatory_optional,
							'status' => $this->input->post('status'),
							'with_product' => $has_products ? 1 : 0,
							'category_id' => NULL,
							'created_at' => date('Y-m-d H:i:s'),
							'updated_at' => date('Y-m-d H:i:s')
						);
						
						// Add price/GST/HSN fields for booksets without products
						if (!$has_products)
						{
							$package_data['package_price'] = isset($pkg['package_price']) ? $pkg['package_price'] : 0;
							$package_data['package_offer_price'] = isset($pkg['package_offer_price']) ? $pkg['package_offer_price'] : 0;
							$package_data['gst'] = isset($pkg['gst']) ? $pkg['gst'] : 0;
							$package_data['hsn'] = isset($pkg['hsn']) && !empty($pkg['hsn']) ? $pkg['hsn'] : NULL;
							$package_data['category'] = NULL; // Packages without products don't have a category
						} else {
							// Save category (textbook, notebook, stationery) for packages with products
							$package_data['category'] = isset($pkg['category']) && !empty($pkg['category']) ? $pkg['category'] : NULL;
						}
						
						$this->db->insert('erp_bookset_packages', $package_data);
						$package_id = $this->db->insert_id();
						
						// Only add products if this is a bookset with products
						if ($has_products && $package_id && isset($pkg['products']) && is_array($pkg['products']) && count($pkg['products']) > 0)
						{
							// Add multiple products to package
							foreach ($pkg['products'] as $product)
							{
								$product_data = array(
									'package_id' => $package_id,
									'product_type' => isset($product['product_type']) ? $product['product_type'] : $pkg['category'],
									'product_id' => isset($product['product_id']) ? $product['product_id'] : 0,
									'display_name' => isset($product['display_name']) && !empty($product['display_name']) ? $product['display_name'] : (isset($product['product_name']) ? $product['product_name'] : $pkg['package_name']),
									'quantity' => isset($product['quantity']) && $product['quantity'] > 0 ? $product['quantity'] : 1,
									'discounted_mrp' => isset($product['discounted_mrp']) ? $product['discounted_mrp'] : 0,
									'weight' => isset($product['packaging_weight']) ? $product['packaging_weight'] : 0,
									'note' => isset($pkg['note']) && !empty($pkg['note']) ? $pkg['note'] : NULL,
									'status' => 'active',
									'created_at' => date('Y-m-d H:i:s'),
									'updated_at' => date('Y-m-d H:i:s')
								);
								
								$this->db->insert('erp_bookset_package_products', $product_data);
							}
						}
					}
					
						$this->db->trans_complete();
						
						if ($this->db->trans_status() === FALSE)
						{
							$this->session->set_flashdata('error', 'Failed to update bookset. Please try again.');
						}
						else
						{
							$this->session->set_flashdata('success', 'Bookset updated successfully.');
							redirect(base_url($this->current_vendor['domain'] . '/products/bookset?tab=' . $redirect_tab));
							return;
						}
					}
				}
			}
		}
		
		// Get existing packages and products for this bookset
		$this->db->select('bp.*');
		$this->db->from('erp_bookset_packages bp');
		$this->db->where('bp.bookset_id', $bookset_id);
		$this->db->order_by('bp.created_at', 'ASC');
		$existing_packages = $this->db->get()->result_array();
		
		if ($has_products)
		{
			// Format packages with products
			$packages_with_products = array();
			foreach ($existing_packages as $package)
			{
				// Get products for this package
				$this->db->select('bpp.*');
				$this->db->from('erp_bookset_package_products bpp');
				$this->db->where('bpp.package_id', $package['id']);
				$this->db->where('bpp.status', 'active');
				$products = $this->db->get()->result_array();
				
				// Format products for JavaScript
				$formatted_products = array();
				foreach ($products as $product)
				{
					// Get product name from actual product table
					$product_name = $product['display_name'];
					if ($product['product_type'] == 'textbook')
					{
						$this->db->select('product_name');
						$this->db->from('erp_textbooks');
						$this->db->where('id', $product['product_id']);
						$textbook = $this->db->get()->row_array();
						if ($textbook)
						{
							$product_name = $textbook['product_name'];
						}
					}
					elseif ($product['product_type'] == 'notebook')
					{
						$this->db->select('product_name');
						$this->db->from('erp_notebooks');
						$this->db->where('id', $product['product_id']);
						$notebook = $this->db->get()->row_array();
						if ($notebook)
						{
							$product_name = $notebook['product_name'];
						}
					}
					
					$formatted_products[] = array(
						'id' => $product['product_type'] . '_' . $product['product_id'], // Format: textbook_123 or notebook_456
						'product_id' => $product['product_id'],
						'product_type' => $product['product_type'],
						'product_name' => $product_name,
						'display_name' => $product['display_name'],
						'quantity' => $product['quantity'],
						'discounted_mrp' => $product['discounted_mrp'],
						'packaging_weight' => $product['weight']
					);
				}
				
				// Determine category from products
				$category = 'textbook'; // Default
				if (!empty($formatted_products))
				{
					$category = $formatted_products[0]['product_type'];
				}
				
				$packages_with_products[] = array(
					'category' => $category,
					'package_name' => $package['package_name'],
					'is_it' => $package['is_it'],
					'weight' => $package['package_weight'],
					'note' => $package['note'],
					'products' => $formatted_products
				);
			}
		}
		else
		{
			// Format packages without products
			$packages_with_products = array();
			foreach ($existing_packages as $package)
			{
				$packages_with_products[] = array(
					'package_name' => $package['package_name'],
					'package_price' => isset($package['package_price']) ? $package['package_price'] : 0,
					'package_offer_price' => isset($package['package_offer_price']) ? $package['package_offer_price'] : 0,
					'gst' => isset($package['gst']) ? $package['gst'] : 0,
					'hsn' => isset($package['hsn']) ? $package['hsn'] : '',
					'is_it' => $package['is_it'],
					'weight' => $package['package_weight'],
					'note' => $package['note']
				);
			}
		}
		
		// Get schools with state and city for dropdown
		$this->db->select('erp_schools.id, erp_schools.school_name, erp_schools.state_id, erp_schools.city_id, states.name as state_name, cities.name as city_name, "school" as type');
		$this->db->from('erp_schools');
		$this->db->join('states', 'states.id = erp_schools.state_id', 'left');
		$this->db->join('cities', 'cities.id = erp_schools.city_id', 'left');
		$this->db->where('erp_schools.vendor_id', $this->current_vendor['id']);
		$this->db->where('erp_schools.status', 'active');
		$schools = $this->db->get()->result_array();
		
		// Get branches with state and city for dropdown
		$this->load->model('Branch_model');
		$this->db->select('erp_school_branches.id, erp_school_branches.branch_name as school_name, erp_school_branches.state_id, erp_school_branches.city_id, states.name as state_name, cities.name as city_name, "branch" as type, erp_schools.school_name as parent_school_name');
		$this->db->from('erp_school_branches');
		$this->db->join('erp_schools', 'erp_schools.id = erp_school_branches.school_id', 'left');
		$this->db->join('states', 'states.id = erp_school_branches.state_id', 'left');
		$this->db->join('cities', 'cities.id = erp_school_branches.city_id', 'left');
		$this->db->where('erp_school_branches.vendor_id', $this->current_vendor['id']);
		$this->db->where('erp_school_branches.status', 'active');
		$branches = $this->db->get()->result_array();
		
		// Combine schools and branches
		$all_schools = array_merge($schools, $branches);
		
		// Sort alphabetically by display name (A-Z)
		// For branches, use parent school name; for schools, use school name
		usort($all_schools, function($a, $b) {
			$a_name = (!empty($a['type']) && $a['type'] == 'branch' && !empty($a['parent_school_name'])) 
				? $a['parent_school_name'] 
				: $a['school_name'];
			$b_name = (!empty($b['type']) && $b['type'] == 'branch' && !empty($b['parent_school_name'])) 
				? $b['parent_school_name'] 
				: $b['school_name'];
			return strcasecmp($a_name, $b_name);
		});
		
		$data['schools'] = $all_schools;
		
		// Get boards for dropdown
		$data['boards'] = $this->School_board_model->getBoardsByVendor($this->current_vendor['id']);
		
		// Get grades for dropdown
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$this->db->where('status', 'active');
		$this->db->order_by('name', 'ASC');
		$data['grades'] = $this->db->get('erp_textbook_grades')->result_array();
		
		$data['bookset'] = $bookset;
		$data['existing_packages'] = $packages_with_products;
		$data['has_products'] = $has_products;
		$data['title'] = 'Edit Bookset';
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $this->getVendorDomainForUrl();
		$data['breadcrumb'] = array(
			array('label' => 'Dashboard', 'url' => base_url($this->current_vendor['domain'] . '/dashboard')),
			array('label' => 'Products', 'url' => '#'),
			array('label' => 'Books', 'url' => '#'),
			array('label' => 'Booksets', 'url' => base_url($this->current_vendor['domain'] . '/products/bookset?tab=' . $redirect_tab)),
			array('label' => 'Edit Bookset', 'active' => true)
		);
		
		// Load appropriate view based on bookset type
		if ($has_products)
		{
			// Main categories: Textbook, Notebook, and Stationery
			$data['types'] = array(
				array('id' => 'textbook', 'name' => 'Textbook', 'category' => 'textbook'),
				array('id' => 'notebook', 'name' => 'Notebook', 'category' => 'notebook'),
				array('id' => 'stationery', 'name' => 'Stationery', 'category' => 'stationery')
			);
			$data['content'] = $this->load->view('vendor/products/books/bookset/edit', $data, TRUE);
		}
		else
		{
			$data['content'] = $this->load->view('vendor/products/books/bookset/edit_without_products', $data, TRUE);
		}
		
		// Load main layout
		$this->load->view('vendor/layouts/index_template', $data);
	}
	
	/**
	 * Bookset Delete
	 */
	public function bookset_delete($bookset_id = NULL)
	{
		if (!$bookset_id)
		{
			$this->session->set_flashdata('error', 'Invalid bookset ID.');
			redirect(base_url($this->current_vendor['domain'] . '/products/bookset?tab=with_product'));
			return;
		}
		
		// Get bookset data to check if it exists
		$this->db->where('id', $bookset_id);
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$bookset = $this->db->get('erp_booksets')->row_array();
		
		if (!$bookset)
		{
			$this->session->set_flashdata('error', 'Bookset not found.');
			redirect(base_url($this->current_vendor['domain'] . '/products/bookset?tab=with_product'));
			return;
		}
		
		// Start transaction
		$this->db->trans_start();
		
		// Get all packages for this bookset
		$this->db->select('id');
		$this->db->from('erp_bookset_packages');
		$this->db->where('bookset_id', $bookset_id);
		$packages = $this->db->get()->result_array();
		
		// Delete package products first
		foreach ($packages as $package)
		{
			$this->db->where('package_id', $package['id']);
			$this->db->delete('erp_bookset_package_products');
		}
		
		// Delete packages
		$this->db->where('bookset_id', $bookset_id);
		$this->db->delete('erp_bookset_packages');
		
		// Delete bookset
		$this->db->where('id', $bookset_id);
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$this->db->delete('erp_booksets');
		
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->session->set_flashdata('error', 'Failed to delete bookset. Please try again.');
		}
		else
		{
			$this->session->set_flashdata('success', 'Bookset deleted successfully.');
		}
		
		redirect(base_url($this->current_vendor['domain'] . '/products/bookset?tab=with_product'));
	}
	
	/**
	 * Notebook Add
	 */
	public function notebook_add()
	{
		$this->load->library('form_validation');
		
		// Load data from database
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$this->db->where('status', 'active');
		$this->db->order_by('name', 'ASC');
		$data['types'] = $this->db->get('erp_textbook_types')->result_array();
		
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$this->db->where('status', 'active');
		$this->db->order_by('name', 'ASC');
		$data['brands'] = $this->db->get('erp_textbook_publishers')->result_array();
		
		if ($this->input->method() == 'post')
		{
			// Form validation rules
			$this->form_validation->set_rules('brand_id', 'Brand', 'required');
			$this->form_validation->set_rules('product_name', 'Product Name', 'required');
			$this->form_validation->set_rules('min_quantity', 'Min Quantity', 'required|numeric');
			$this->form_validation->set_rules('product_description', 'Product Description', 'required');
			$this->form_validation->set_rules('gst_percentage', 'GST (%)', 'required|numeric');
			$this->form_validation->set_rules('mrp', 'MRP', 'required|numeric');
			$this->form_validation->set_rules('selling_price', 'Selling Price', 'required|numeric');
			$this->form_validation->set_rules('types[]', 'Type', 'required');
			
			if ($this->form_validation->run() == FALSE)
			{
				// Validation failed, reload form with errors
			}
			else
			{
				// Get form data
				$notebook_data = array(
					'vendor_id' => $this->current_vendor['id'],
					'brand_id' => $this->input->post('brand_id'),
					'product_name' => $this->input->post('product_name'),
					'isbn' => $this->input->post('isbn') ? $this->input->post('isbn') : NULL,
					'size' => $this->input->post('size') ? $this->input->post('size') : NULL,
					'binding_type' => $this->input->post('binding_type') ? $this->input->post('binding_type') : NULL,
					'no_of_pages' => $this->input->post('no_of_pages') ? $this->input->post('no_of_pages') : NULL,
					'min_quantity' => $this->input->post('min_quantity') ? $this->input->post('min_quantity') : 1,
					'days_to_exchange' => $this->input->post('days_to_exchange') ? $this->input->post('days_to_exchange') : NULL,
					'pointers' => $this->input->post('pointers') ? $this->input->post('pointers') : NULL,
					'product_description' => $this->input->post('product_description'),
					'packaging_length' => $this->input->post('packaging_length') ? $this->input->post('packaging_length') : NULL,
					'packaging_width' => $this->input->post('packaging_width') ? $this->input->post('packaging_width') : NULL,
					'packaging_height' => $this->input->post('packaging_height') ? $this->input->post('packaging_height') : NULL,
					'packaging_weight' => $this->input->post('packaging_weight') ? $this->input->post('packaging_weight') : NULL,
					'gst_percentage' => $this->input->post('gst_percentage') ? $this->input->post('gst_percentage') : 0,
					'hsn' => $this->input->post('hsn') ? $this->input->post('hsn') : NULL,
					'product_code' => $this->input->post('product_code') ? $this->input->post('product_code') : NULL,
					'sku' => $this->input->post('sku') ? $this->input->post('sku') : NULL,
					'mrp' => $this->input->post('mrp'),
					'selling_price' => $this->input->post('selling_price'),
					'meta_title' => $this->input->post('meta_title') ? $this->input->post('meta_title') : NULL,
					'meta_keywords' => $this->input->post('meta_keywords') ? $this->input->post('meta_keywords') : NULL,
					'meta_description' => $this->input->post('meta_description') ? $this->input->post('meta_description') : NULL,
					'is_individual' => $this->input->post('is_individual') ? 1 : 0,
					'is_set' => $this->input->post('is_set') ? 1 : 0,
					'status' => 'active',
					'created_at' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s')
				);
				
				// Insert notebook
				$this->db->insert('erp_notebooks', $notebook_data);
				$notebook_id = $this->db->insert_id();
				
				if ($notebook_id)
				{
					// Handle images upload
					$this->handleNotebookImageUploads($notebook_id);
					
					// Handle type mappings
					$types = $this->input->post('types');
					if (!empty($types) && is_array($types))
					{
						foreach ($types as $type_id)
						{
							$this->db->insert('erp_notebook_type_mapping', array(
								'notebook_id' => $notebook_id,
								'type_id' => $type_id
							));
						}
					}
					
					$this->session->set_flashdata('success', 'Notebook created successfully.');
					redirect(base_url($this->current_vendor['domain'] . '/products/notebooks'));
				}
				else
				{
					$this->session->set_flashdata('error', 'Failed to create notebook.');
				}
			}
		}
		
		$data['title'] = 'Add New Notebook';
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $this->getVendorDomainForUrl();
		$data['breadcrumb'] = array(
			array('label' => 'Dashboard', 'url' => base_url($this->current_vendor['domain'] . '/dashboard')),
			array('label' => 'Notebooks', 'url' => base_url($this->current_vendor['domain'] . '/products/notebooks')),
			array('label' => 'Add New', 'active' => true)
		);
		
		// Load content view
		$data['content'] = $this->load->view('vendor/products/books/notebooks/add', $data, TRUE);
		
		// Load main layout
		$this->load->view('vendor/layouts/index_template', $data);
	}
	
	/**
	 * Notebook Edit
	 */
	public function notebook_edit($id = NULL)
	{
		if (empty($id))
		{
			show_404();
		}
		
		$this->load->library('form_validation');
		
		// Get notebook
		$this->db->where('id', $id);
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$notebook = $this->db->get('erp_notebooks')->row_array();
		
		if (!$notebook)
		{
			show_404();
		}
		
		// Load data from database
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$this->db->where('status', 'active');
		$this->db->order_by('name', 'ASC');
		$data['types'] = $this->db->get('erp_textbook_types')->result_array();
		
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$this->db->where('status', 'active');
		$this->db->order_by('name', 'ASC');
		$data['brands'] = $this->db->get('erp_textbook_publishers')->result_array();
		
		// Get notebook images
		$this->db->where('notebook_id', $id);
		$this->db->order_by('image_order', 'ASC');
		$data['notebook_images'] = $this->db->get('erp_notebook_images')->result_array();
		
		// Get notebook types
		$this->db->select('ntm.type_id');
		$this->db->from('erp_notebook_type_mapping ntm');
		$this->db->where('ntm.notebook_id', $id);
		$data['notebook_types'] = $this->db->get()->result_array();
		
		if ($this->input->method() == 'post')
		{
			// Form validation rules
			$this->form_validation->set_rules('brand_id', 'Brand', 'required');
			$this->form_validation->set_rules('product_name', 'Product Name', 'required');
			$this->form_validation->set_rules('min_quantity', 'Min Quantity', 'required|numeric');
			$this->form_validation->set_rules('product_description', 'Product Description', 'required');
			$this->form_validation->set_rules('gst_percentage', 'GST (%)', 'required|numeric');
			$this->form_validation->set_rules('mrp', 'MRP', 'required|numeric');
			$this->form_validation->set_rules('selling_price', 'Selling Price', 'required|numeric');
			$this->form_validation->set_rules('types[]', 'Type', 'required');
			
			if ($this->form_validation->run() == FALSE)
			{
				// Validation failed, reload form with errors
			}
			else
			{
				// Get form data
				$notebook_data = array(
					'brand_id' => $this->input->post('brand_id'),
					'product_name' => $this->input->post('product_name'),
					'isbn' => $this->input->post('isbn') ? $this->input->post('isbn') : NULL,
					'size' => $this->input->post('size') ? $this->input->post('size') : NULL,
					'binding_type' => $this->input->post('binding_type') ? $this->input->post('binding_type') : NULL,
					'no_of_pages' => $this->input->post('no_of_pages') ? $this->input->post('no_of_pages') : NULL,
					'min_quantity' => $this->input->post('min_quantity') ? $this->input->post('min_quantity') : 1,
					'days_to_exchange' => $this->input->post('days_to_exchange') ? $this->input->post('days_to_exchange') : NULL,
					'pointers' => $this->input->post('pointers') ? $this->input->post('pointers') : NULL,
					'product_description' => $this->input->post('product_description'),
					'packaging_length' => $this->input->post('packaging_length') ? $this->input->post('packaging_length') : NULL,
					'packaging_width' => $this->input->post('packaging_width') ? $this->input->post('packaging_width') : NULL,
					'packaging_height' => $this->input->post('packaging_height') ? $this->input->post('packaging_height') : NULL,
					'packaging_weight' => $this->input->post('packaging_weight') ? $this->input->post('packaging_weight') : NULL,
					'gst_percentage' => $this->input->post('gst_percentage') ? $this->input->post('gst_percentage') : 0,
					'hsn' => $this->input->post('hsn') ? $this->input->post('hsn') : NULL,
					'product_code' => $this->input->post('product_code') ? $this->input->post('product_code') : NULL,
					'sku' => $this->input->post('sku') ? $this->input->post('sku') : NULL,
					'mrp' => $this->input->post('mrp'),
					'selling_price' => $this->input->post('selling_price'),
					'meta_title' => $this->input->post('meta_title') ? $this->input->post('meta_title') : NULL,
					'meta_keywords' => $this->input->post('meta_keywords') ? $this->input->post('meta_keywords') : NULL,
					'meta_description' => $this->input->post('meta_description') ? $this->input->post('meta_description') : NULL,
					'is_individual' => $this->input->post('is_individual') ? 1 : 0,
					'is_set' => $this->input->post('is_set') ? 1 : 0,
					'status' => $this->input->post('status') ? $this->input->post('status') : 'active',
					'updated_at' => date('Y-m-d H:i:s')
				);
				
				// Update notebook
				$this->db->where('id', $id);
				$this->db->where('vendor_id', $this->current_vendor['id']);
				$this->db->update('erp_notebooks', $notebook_data);
				
				// Handle images upload
				$this->handleNotebookImageUploads($id);
				
				// Handle existing image updates (order, main image, deletions)
				$this->handleNotebookImageUpdates($id);
				
				// Update type mappings
				$this->db->where('notebook_id', $id);
				$this->db->delete('erp_notebook_type_mapping');
				
				$types = $this->input->post('types');
				if (!empty($types) && is_array($types))
				{
					foreach ($types as $type_id)
					{
						$this->db->insert('erp_notebook_type_mapping', array(
							'notebook_id' => $id,
							'type_id' => $type_id
						));
					}
				}
				
				$this->session->set_flashdata('success', 'Notebook updated successfully.');
				redirect(base_url($this->current_vendor['domain'] . '/products/notebooks'));
			}
		}
		
		$data['notebook'] = $notebook;
		$data['title'] = 'Edit Notebook';
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $this->getVendorDomainForUrl();
		$data['breadcrumb'] = array(
			array('label' => 'Dashboard', 'url' => base_url($this->current_vendor['domain'] . '/dashboard')),
			array('label' => 'Notebooks', 'url' => base_url($this->current_vendor['domain'] . '/products/notebooks')),
			array('label' => 'Edit', 'active' => true)
		);
		
		// Load content view
		$data['content'] = $this->load->view('vendor/products/books/notebooks/edit', $data, TRUE);
		
		// Load main layout
		$this->load->view('vendor/layouts/index_template', $data);
	}
	
	/**
	 * Notebook Delete
	 */
	public function notebook_delete($id = NULL)
	{
		if (empty($id))
		{
			show_404();
		}
		
		// Get notebook
		$this->db->where('id', $id);
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$notebook = $this->db->get('erp_notebooks')->row_array();
		
		if (!$notebook)
		{
			show_404();
		}
		
		// Delete images
		$this->db->where('notebook_id', $id);
		$images = $this->db->get('erp_notebook_images')->result_array();
		foreach ($images as $image)
		{
			$image_path = FCPATH . 'assets/uploads/' . $image['image_path'];
			if (file_exists($image_path))
			{
				@unlink($image_path);
			}
		}
		
		// Delete type mappings
		$this->db->where('notebook_id', $id);
		$this->db->delete('erp_notebook_type_mapping');
		
		// Delete images
		$this->db->where('notebook_id', $id);
		$this->db->delete('erp_notebook_images');
		
		// Delete notebook
		$this->db->where('id', $id);
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$this->db->delete('erp_notebooks');
		
		$this->session->set_flashdata('success', 'Notebook deleted successfully.');
		redirect(base_url($this->current_vendor['domain'] . '/products/books/notebooks'));
	}
	
	/**
	 * Handle Notebook Image Uploads
	 */
	protected function handleNotebookImageUploads($notebook_id)
	{
		if (!empty($_FILES['images']['name'][0]))
		{
			$upload_path = './assets/uploads/vendors/' . $this->current_vendor['id'] . '/notebooks/images/';
			if (!is_dir($upload_path))
			{
				mkdir($upload_path, 0755, TRUE);
			}
			
			$files = $_FILES['images'];
			$image_order = 0;
			$upload_errors = array();
			
			foreach ($files['name'] as $key => $filename)
			{
				if ($files['error'][$key] == 0 && !empty($filename))
				{
					// Validate MIME type to ensure it's an image
					$allowed_mimes = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/bmp', 'image/svg+xml', 'image/x-icon', 'image/tiff', 'image/tif', 'image/avif');
					$allowed_extensions = array('jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg', 'ico', 'tiff', 'tif', 'avif');
					$file_mime = $files['type'][$key];
					
					// Get file extension as fallback
					$file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
					
					// Check both MIME type and file extension (some browsers send incorrect MIME types for AVIF)
					// Accept file if EITHER MIME type OR extension is valid (extension takes precedence for AVIF)
					$mime_valid = in_array($file_mime, $allowed_mimes);
					$ext_valid = in_array($file_ext, $allowed_extensions);
					$is_valid = $mime_valid || $ext_valid;
					
					if (!$is_valid)
					{
						$upload_errors[] = $filename . ': Invalid file type. Only image files are allowed.';
						continue;
					}
					
					// Reset $_FILES array for this iteration
					$_FILES['image']['name'] = $files['name'][$key];
					$_FILES['image']['type'] = $files['type'][$key];
					$_FILES['image']['tmp_name'] = $files['tmp_name'][$key];
					$_FILES['image']['error'] = $files['error'][$key];
					$_FILES['image']['size'] = $files['size'][$key];
					
					// Get file extension and convert to lowercase
					$file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
					
					$config['upload_path'] = $upload_path;
					$config['allowed_types'] = '*'; // Allow all types - we validate MIME type above
					$config['max_size'] = 5120; // 5MB
					$config['file_name'] = 'notebook_' . $notebook_id . '_' . time() . '_' . $key . '.' . $file_ext;
					$config['overwrite'] = FALSE;
					
					$this->load->library('upload');
					$this->upload->initialize($config);
					
					if ($this->upload->do_upload('image'))
					{
						$upload_data = $this->upload->data();
						$this->db->insert('erp_notebook_images', array(
							'notebook_id' => $notebook_id,
							'image_path' => 'vendors/' . $this->current_vendor['id'] . '/notebooks/images/' . $upload_data['file_name'],
							'image_order' => $image_order++,
							'created_at' => date('Y-m-d H:i:s')
						));
					}
					else
					{
						$error = $this->upload->display_errors('', '');
						$upload_errors[] = $filename . ': ' . $error;
						log_message('error', 'Notebook image upload failed: ' . $error);
					}
				}
				elseif ($files['error'][$key] != 0)
				{
					$upload_errors[] = $filename . ': Upload error code ' . $files['error'][$key];
				}
			}
			
			// Show upload errors if any
			if (!empty($upload_errors))
			{
				$this->session->set_flashdata('error', 'Some images failed to upload: ' . implode(', ', $upload_errors));
			}
		}
	}
	
	/**
	 * Handle existing notebook image updates (order, main image, deletions)
	 */
	protected function handleNotebookImageUpdates($notebook_id)
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
				$this->db->where('notebook_id', $notebook_id);
				$this->db->where_in('id', $deleted_ids);
				$this->db->delete('erp_notebook_images');
			}
		}
		
		// Handle image order and main image
		if (!empty($image_order))
		{
			$image_ids = explode(',', $image_order);
			$image_ids = array_filter(array_map('trim', $image_ids));
			
			// Update image order
			foreach ($image_ids as $order => $image_id)
			{
				$image_id = trim($image_id);
				if (!empty($image_id))
				{
					// Check if image belongs to this notebook
					$this->db->where('id', $image_id);
					$this->db->where('notebook_id', $notebook_id);
					$image = $this->db->get('erp_notebook_images')->row_array();
					
					if ($image)
					{
						// Determine if this is the main image
						$is_main = ($main_image_id == $image_id) ? 1 : 0;
						
						// Update image order and is_main
						$this->db->where('id', $image_id);
						$this->db->update('erp_notebook_images', array(
							'image_order' => $order,
							'is_main' => $is_main
						));
					}
				}
			}
		}
		
		// If main_image_id is set but not in image_order, update it separately
		if (!empty($main_image_id) && empty($image_order))
		{
			// First, set all images to not main
			$this->db->where('notebook_id', $notebook_id);
			$this->db->update('erp_notebook_images', array('is_main' => 0));
			
			// Then set the specified image as main
			$this->db->where('id', $main_image_id);
			$this->db->where('notebook_id', $notebook_id);
			$this->db->update('erp_notebook_images', array('is_main' => 1));
		}
	}
	
	/**
	 * Notebook Add Type (AJAX)
	 */
	public function notebook_add_type()
	{
		header('Content-Type: application/json');
		
		$name = $this->input->post('name');
		$description = $this->input->post('description');
		
		if (empty($name))
		{
			echo json_encode(array('status' => 'error', 'message' => 'Name is required'));
			return;
		}
		
		// Check if type already exists for this vendor
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$this->db->where('name', $name);
		$existing = $this->db->get('erp_textbook_types')->row_array();
		
		if ($existing)
		{
			echo json_encode(array('status' => 'error', 'message' => 'Type already exists'));
			return;
		}
		
		// Insert type
		$this->db->insert('erp_textbook_types', array(
			'vendor_id' => $this->current_vendor['id'],
			'name' => $name,
			'description' => $description ? $description : NULL,
			'status' => 'active',
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s')
		));
		
		$type_id = $this->db->insert_id();
		
		if ($type_id)
		{
			echo json_encode(array('status' => 'success', 'id' => $type_id, 'name' => $name));
		}
		else
		{
			echo json_encode(array('status' => 'error', 'message' => 'Failed to add type'));
		}
	}
	
	/**
	 * Notebook Add Brand (AJAX) - Uses textbook_publishers table
	 */
	public function notebook_add_brand()
	{
		header('Content-Type: application/json');
		
		$name = $this->input->post('name');
		$description = $this->input->post('description');
		
		if (empty($name))
		{
			echo json_encode(array('status' => 'error', 'message' => 'Name is required'));
			return;
		}
		
		// Check if publisher/brand already exists for this vendor
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$this->db->where('name', $name);
		$existing = $this->db->get('erp_textbook_publishers')->row_array();
		
		if ($existing)
		{
			echo json_encode(array('status' => 'error', 'message' => 'Brand already exists'));
			return;
		}
		
		// Insert publisher (used as brand)
		$this->db->insert('erp_textbook_publishers', array(
			'vendor_id' => $this->current_vendor['id'],
			'name' => $name,
			'description' => $description ? $description : NULL,
			'status' => 'active',
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s')
		));
		
		$brand_id = $this->db->insert_id();
		
		if ($brand_id)
		{
			echo json_encode(array('status' => 'success', 'id' => $brand_id, 'name' => $name));
		}
		else
		{
			echo json_encode(array('status' => 'error', 'message' => 'Failed to add brand'));
		}
	}
	
	/**
	 * Notebook Delete Image (AJAX)
	 */
	public function notebook_delete_image($id = NULL)
	{
		header('Content-Type: application/json');
		
		if (empty($id))
		{
			echo json_encode(array('status' => 'error', 'message' => 'Image ID required'));
			return;
		}
		
		// Get image
		$this->db->where('id', $id);
		$image = $this->db->get('erp_notebook_images')->row_array();
		
		if (!$image)
		{
			echo json_encode(array('status' => 'error', 'message' => 'Image not found'));
			return;
		}
		
		// Verify notebook belongs to vendor
		$this->db->where('id', $image['notebook_id']);
		$this->db->where('vendor_id', $this->current_vendor['id']);
		$notebook = $this->db->get('erp_notebooks')->row_array();
		
		if (!$notebook)
		{
			echo json_encode(array('status' => 'error', 'message' => 'Unauthorized access'));
			return;
		}
		
		// Delete physical file
		$image_path = FCPATH . 'assets/uploads/' . $image['image_path'];
		if (file_exists($image_path))
		{
			@unlink($image_path);
		}
		
		// Delete from database
		$this->db->where('id', $id);
		$this->db->delete('erp_notebook_images');
		
		echo json_encode(array('status' => 'success', 'message' => 'Image deleted successfully'));
	}
}





