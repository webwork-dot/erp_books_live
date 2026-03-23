<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Individual Product Model
 *
 * Handles database operations for individual product management
 *
 * @package		ERP
 * @subpackage	Models
 * @category	Models
 * @author		ERP Team
 */
class Individual_product_model extends CI_Model
{
	/**
	 * Constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
		// Use default database connection (will be switched to vendor database by Vendor_base)
		// Do not load separate connection - use $this->db which is switched to vendor database
	}
	
	/**
	 * Get all individual products by vendor
	 *
	 * @param	int	$vendor_id	Vendor ID
	 * @param	array	$filters	Optional filters
	 * @param	int	$limit		Limit (for pagination)
	 * @param	int	$offset		Offset (for pagination)
	 * @return	array	Array of products
	 */
	public function getProductsByVendor($vendor_id, $filters = array(), $limit = NULL, $offset = 0)
	{
		$this->db->select('erp_individual_products.*');
		$this->db->from('erp_individual_products');
		$this->db->where('erp_individual_products.vendor_id', $vendor_id);
		
		if (isset($filters['status']))
		{
			$this->db->where('erp_individual_products.status', $filters['status']);
		}
		
		if (isset($filters['variation_type']))
		{
			$this->db->where('erp_individual_products.variation_type', $filters['variation_type']);
		}
		
		// Search filter
		if (isset($filters['search']) && !empty($filters['search']))
		{
			$this->db->group_start();
			$this->db->like('erp_individual_products.product_name', $filters['search']);
			$this->db->or_like('erp_individual_products.sku', $filters['search']);
			$this->db->or_like('erp_individual_products.isbn', $filters['search']);
			$this->db->or_like('erp_individual_products.barcode', $filters['search']);
			$this->db->group_end();
		}
		
		// Category filter
		if (isset($filters['category_id']) && !empty($filters['category_id']))
		{
			$this->db->join('erp_individual_product_category_mapping', 'erp_individual_product_category_mapping.product_id = erp_individual_products.id', 'inner');
			$this->db->where('erp_individual_product_category_mapping.category_id', $filters['category_id']);
			$this->db->group_by('erp_individual_products.id');
		}
		
		$this->db->order_by('erp_individual_products.created_at', 'DESC');
		
		if ($limit !== NULL)
		{
			$this->db->limit($limit, $offset);
		}
		
		$query = $this->db->get();
		return $query->result_array();
	}
	
	/**
	 * Get product by ID
	 *
	 * @param	int	$product_id	Product ID
	 * @return	array|FALSE	Product data or FALSE if not found
	 */
	public function getProductById($product_id)
	{
		$this->db->where('id', $product_id);
		$query = $this->db->get('erp_individual_products');
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		
		return FALSE;
	}
	
	/**
	 * Create product
	 *
	 * @param	array	$data	Product data
	 * @return	int|FALSE	Product ID on success, FALSE on failure
	 */
	public function createProduct($data)
	{
		$data['created_at'] = date('Y-m-d H:i:s');
		$this->db->insert('erp_individual_products', $data);
		
		if ($this->db->affected_rows() > 0)
		{
			return $this->db->insert_id();
		}
		
		return FALSE;
	}
	
	/**
	 * Update product
	 *
	 * @param	int	$product_id	Product ID
	 * @param	array	$data	Product data
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function updateProduct($product_id, $data)
	{
		$data['updated_at'] = date('Y-m-d H:i:s');
		$this->db->where('id', $product_id);
		$this->db->update('erp_individual_products', $data);
		
		return $this->db->affected_rows() >= 0;
	}
	
	/**
	 * Delete product
	 *
	 * @param	int	$product_id	Product ID
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function deleteProduct($product_id)
	{
		// Delete related data first
		$this->db->where('product_id', $product_id);
		$this->db->delete('erp_individual_product_category_mapping');
		
		$this->db->where('product_id', $product_id);
		$this->db->delete('erp_individual_product_color_mapping');
		
		$this->db->where('product_id', $product_id);
		$this->db->delete('erp_individual_product_size_color_prices');
		
		$this->db->where('product_id', $product_id);
		$this->db->delete('erp_individual_product_images');
		
		// Delete product
		$this->db->where('id', $product_id);
		$this->db->delete('erp_individual_products');
		
		return $this->db->affected_rows() > 0;
	}
	
	/**
	 * Get all categories by vendor
	 *
	 * @param	int	$vendor_id	Vendor ID
	 * @return	array	Array of categories
	 */
	public function getCategoriesByVendor($vendor_id)
	{
		$this->db->where('vendor_id', $vendor_id);
		$this->db->where('status', 'active');
		$this->db->order_by('name', 'ASC');
		$query = $this->db->get('erp_individual_product_categories');
		
		return $query->result_array();
	}
	
	/**
	 * Get parent categories by vendor (categories without parent)
	 *
	 * @param	int	$vendor_id	Vendor ID
	 * @return	array	Array of parent categories
	 */
	public function getParentCategoriesByVendor($vendor_id)
	{
		$this->db->where('vendor_id', $vendor_id);
		$this->db->where('parent_id IS NULL', NULL, FALSE);
		$this->db->where('status', 'active');
		$this->db->order_by('name', 'ASC');
		$query = $this->db->get('erp_individual_product_categories');
		
		return $query->result_array();
	}
	
	/**
	 * Get subcategories by parent category
	 *
	 * @param	int	$parent_id	Parent category ID
	 * @return	array	Array of subcategories
	 */
	public function getSubcategoriesByParent($parent_id)
	{
		$this->db->where('parent_id', $parent_id);
		$this->db->where('status', 'active');
		$this->db->order_by('name', 'ASC');
		$query = $this->db->get('erp_individual_product_categories');
		
		return $query->result_array();
	}
	
	/**
	 * Create category
	 *
	 * @param	array	$data	Category data
	 * @return	int|FALSE	Category ID on success, FALSE on failure
	 */
	public function createCategory($data)
	{
		$data['created_at'] = date('Y-m-d H:i:s');
		$this->db->insert('erp_individual_product_categories', $data);
		
		if ($this->db->affected_rows() > 0)
		{
			return $this->db->insert_id();
		}
		
		return FALSE;
	}
	
	/**
	 * Get all colors by vendor
	 *
	 * @param	int	$vendor_id	Vendor ID
	 * @return	array	Array of colors
	 */
	public function getColorsByVendor($vendor_id)
	{
		$this->db->where('vendor_id', $vendor_id);
		$this->db->where('status', 'active');
		$this->db->order_by('name', 'ASC');
		$query = $this->db->get('erp_individual_product_colors');
		
		return $query->result_array();
	}
	
	/**
	 * Create color
	 *
	 * @param	array	$data	Color data
	 * @return	int|FALSE	Color ID on success, FALSE on failure
	 */
	public function createColor($data)
	{
		$data['created_at'] = date('Y-m-d H:i:s');
		$this->db->insert('erp_individual_product_colors', $data);
		
		if ($this->db->affected_rows() > 0)
		{
			return $this->db->insert_id();
		}
		
		return FALSE;
	}
	
	/**
	 * Add product categories
	 *
	 * @param	int	$product_id	Product ID
	 * @param	array	$category_ids	Array of category IDs
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function addProductCategories($product_id, $category_ids)
	{
		if (empty($category_ids) || !is_array($category_ids))
		{
			return FALSE;
		}
		
		$data = array();
		foreach ($category_ids as $category_id)
		{
			$data[] = array(
				'product_id' => $product_id,
				'category_id' => $category_id,
				'created_at' => date('Y-m-d H:i:s')
			);
		}
		
		if (!empty($data))
		{
			$this->db->insert_batch('erp_individual_product_category_mapping', $data);
			return $this->db->affected_rows() > 0;
		}
		
		return FALSE;
	}
	
	/**
	 * Get product categories
	 *
	 * @param	int	$product_id	Product ID
	 * @return	array	Array of category data with id and name
	 */
	public function getProductCategories($product_id)
	{
		$this->db->select('erp_individual_product_categories.id, erp_individual_product_categories.name, erp_individual_product_categories.parent_id');
		$this->db->from('erp_individual_product_category_mapping');
		$this->db->join('erp_individual_product_categories', 'erp_individual_product_categories.id = erp_individual_product_category_mapping.category_id', 'inner');
		$this->db->where('erp_individual_product_category_mapping.product_id', $product_id);
		$query = $this->db->get();
		
		return $query->result_array();
	}
	
	/**
	 * Add product colors
	 *
	 * @param	int	$product_id	Product ID
	 * @param	array	$color_ids	Array of color IDs
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function addProductColors($product_id, $color_ids)
	{
		if (empty($color_ids) || !is_array($color_ids))
		{
			return FALSE;
		}
		
		$data = array();
		foreach ($color_ids as $color_id)
		{
			$data[] = array(
				'product_id' => $product_id,
				'color_id' => $color_id,
				'created_at' => date('Y-m-d H:i:s')
			);
		}
		
		if (!empty($data))
		{
			$this->db->insert_batch('erp_individual_product_color_mapping', $data);
			return $this->db->affected_rows() > 0;
		}
		
		return FALSE;
	}
	
	/**
	 * Get product colors
	 *
	 * @param	int	$product_id	Product ID
	 * @return	array	Array of color IDs
	 */
	public function getProductColors($product_id)
	{
		$this->db->select('color_id');
		$this->db->where('product_id', $product_id);
		$query = $this->db->get('erp_individual_product_color_mapping');
		
		$colors = array();
		foreach ($query->result_array() as $row)
		{
			$colors[] = $row['color_id'];
		}
		
		return $colors;
	}
	
	/**
	 * Add product images
	 *
	 * @param	int	$product_id	Product ID
	 * @param	array	$images	Array of image data (path, order, is_main)
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function addProductImages($product_id, $images)
	{
		if (empty($images) || !is_array($images))
		{
			return FALSE;
		}
		
		$data = array();
		foreach ($images as $image)
		{
			$data[] = array(
				'product_id' => $product_id,
				'image_path' => $image['path'],
				'image_order' => isset($image['order']) ? $image['order'] : 0,
				'is_main' => isset($image['is_main']) ? $image['is_main'] : 0,
				'created_at' => date('Y-m-d H:i:s')
			);
		}
		
		if (!empty($data))
		{
			$this->db->insert_batch('erp_individual_product_images', $data);
			return $this->db->affected_rows() > 0;
		}
		
		return FALSE;
	}
	
	/**
	 * Get product images
	 *
	 * @param	int	$product_id	Product ID
	 * @return	array	Array of images
	 */
	public function getProductImages($product_id)
	{
		$this->db->where('product_id', $product_id);
		$this->db->order_by('image_order', 'ASC');
		$this->db->order_by('id', 'ASC');
		$query = $this->db->get('erp_individual_product_images');
		
		return $query->result_array();
	}
	
	/**
	 * Delete product image
	 *
	 * @param	int	$image_id	Image ID
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function deleteProductImage($image_id)
	{
		$this->db->where('id', $image_id);
		$this->db->delete('erp_individual_product_images');
		
		return $this->db->affected_rows() > 0;
	}
	
	/**
	 * Add or update size-color prices
	 *
	 * @param	int	$product_id	Product ID
	 * @param	array	$prices	Array of price data (size_id, color_id, mrp, selling_price)
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function saveSizeColorPrices($product_id, $prices)
	{
		if (empty($prices) || !is_array($prices))
		{
			return FALSE;
		}
		
		// Delete existing prices for this product
		$this->db->where('product_id', $product_id);
		$this->db->delete('erp_individual_product_size_color_prices');
		
		$data = array();
		foreach ($prices as $price)
		{
			$data[] = array(
				'product_id' => $product_id,
				'size_id' => isset($price['size_id']) ? $price['size_id'] : NULL,
				'color_id' => isset($price['color_id']) ? $price['color_id'] : NULL,
				'mrp' => isset($price['mrp']) ? $price['mrp'] : 0.00,
				'selling_price' => isset($price['selling_price']) ? $price['selling_price'] : 0.00,
				'created_at' => date('Y-m-d H:i:s')
			);
		}
		
		if (!empty($data))
		{
			$this->db->insert_batch('erp_individual_product_size_color_prices', $data);
			return $this->db->affected_rows() > 0;
		}
		
		return TRUE; // If no prices, deletion was successful
	}
	
	/**
	 * Get size-color prices for product
	 *
	 * @param	int	$product_id	Product ID
	 * @return	array	Array of prices
	 */
	public function getSizeColorPrices($product_id)
	{
		$this->db->select('erp_individual_product_size_color_prices.*, erp_sizes.name as size_name, erp_individual_product_colors.name as color_name');
		$this->db->from('erp_individual_product_size_color_prices');
		$this->db->join('erp_sizes', 'erp_sizes.id = erp_individual_product_size_color_prices.size_id', 'left');
		$this->db->join('erp_individual_product_colors', 'erp_individual_product_colors.id = erp_individual_product_size_color_prices.color_id', 'left');
		$this->db->where('erp_individual_product_size_color_prices.product_id', $product_id);
		$this->db->order_by('erp_sizes.display_order', 'ASC');
		$this->db->order_by('erp_individual_product_colors.name', 'ASC');
		$query = $this->db->get();
		
		return $query->result_array();
	}
	
	/**
	 * Check if SKU exists
	 *
	 * @param	string	$sku	SKU code
	 * @param	int	$vendor_id	Vendor ID
	 * @param	int	$exclude_id	Product ID to exclude (for updates)
	 * @return	bool	TRUE if exists, FALSE otherwise
	 */
	public function checkSkuExists($sku, $vendor_id, $exclude_id = NULL)
	{
		$this->db->where('sku', $sku);
		$this->db->where('vendor_id', $vendor_id);
		
		if ($exclude_id !== NULL)
		{
			$this->db->where('id !=', $exclude_id);
		}
		
		$query = $this->db->get('erp_individual_products');
		
		return $query->num_rows() > 0;
	}
	
	/**
	 * Get product count by status
	 *
	 * @param	int	$vendor_id	Vendor ID
	 * @param	string	$status	Product status ('active' or 'inactive')
	 * @return	int	Count of products
	 */
	public function getProductCountByStatus($vendor_id, $status)
	{
		$this->db->where('vendor_id', $vendor_id);
		$this->db->where('status', $status);
		return $this->db->count_all_results('erp_individual_products');
	}
	
	/**
	 * Get out of stock product count
	 *
	 * @param	int	$vendor_id	Vendor ID
	 * @return	int	Count of out of stock products (returns 0 as stock column doesn't exist)
	 */
	public function getOutOfStockCount($vendor_id)
	{
		// Stock column doesn't exist in the database
		return 0;
	}
}

