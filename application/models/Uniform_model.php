<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Uniform Model
 *
 * Handles database operations for uniform management
 *
 * @package		ERP
 * @subpackage	Models
 * @category	Models
 * @author		ERP Team
 */
class Uniform_model extends CI_Model
{
	/**
	 * Constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
		// Use master database
		$this->load->database('default', TRUE);
	}
	
	/**
	 * Get all uniforms by vendor
	 *
	 * @param	int	$vendor_id	Vendor ID
	 * @param	array	$filters	Optional filters
	 * @param	int	$limit		Limit (for pagination)
	 * @param	int	$offset		Offset (for pagination)
	 * @return	array	Array of uniforms
	 */
	public function getUniformsByVendor($vendor_id, $filters = array(), $limit = NULL, $offset = 0)
	{
		$this->db->select('erp_uniforms.*, erp_uniform_types.name as uniform_type_name, erp_schools.school_name, erp_school_branches.branch_name, erp_school_boards.board_name, erp_materials.name as material_name');
		$this->db->from('erp_uniforms');
		$this->db->join('erp_uniform_types', 'erp_uniform_types.id = erp_uniforms.uniform_type_id', 'left');
		$this->db->join('erp_schools', 'erp_schools.id = erp_uniforms.school_id', 'left');
		$this->db->join('erp_school_branches', 'erp_school_branches.id = erp_uniforms.branch_id', 'left');
		$this->db->join('erp_school_boards', 'erp_school_boards.id = erp_uniforms.board_id', 'left');
		$this->db->join('erp_materials', 'erp_materials.id = erp_uniforms.material_id', 'left');
		$this->db->where('erp_uniforms.vendor_id', $vendor_id);
		
		if (isset($filters['status']))
		{
			$this->db->where('erp_uniforms.status', $filters['status']);
		}
		
		if (isset($filters['school_id']))
		{
			$this->db->where('erp_uniforms.school_id', $filters['school_id']);
		}
		
		if (isset($filters['search']))
		{
			$this->db->group_start();
			$this->db->like('erp_uniforms.product_name', $filters['search']);
			$this->db->or_like('erp_uniforms.isbn', $filters['search']);
			$this->db->group_end();
		}
		
		if (isset($filters['uniform_type_id']))
		{
			$this->db->where('erp_uniforms.uniform_type_id', $filters['uniform_type_id']);
		}
		
		if (isset($filters['board_id']))
		{
			$this->db->where('erp_uniforms.board_id', $filters['board_id']);
		}
		
		if (isset($filters['material_id']))
		{
			$this->db->where('erp_uniforms.material_id', $filters['material_id']);
		}
		
		if (isset($filters['gender']))
		{
			$this->db->where('erp_uniforms.gender', $filters['gender']);
		}
		
		if (isset($filters['branch_id']))
		{
			$this->db->where('erp_uniforms.branch_id', $filters['branch_id']);
		}
		
		$this->db->order_by('erp_uniforms.id', 'DESC');
		
		if ($limit !== NULL)
		{
			$this->db->limit($limit, $offset);
		}
		
		$query = $this->db->get();
		
		return $query->result_array();
	}
	
	/**
	 * Get total uniforms count
	 *
	 * @param	int	$vendor_id	Vendor ID
	 * @param	array	$filters	Optional filters
	 * @return	int	Total number of uniforms
	 */
	public function getTotalUniformsByVendor($vendor_id, $filters = array())
	{
		$this->db->from('erp_uniforms');
		$this->db->where('vendor_id', $vendor_id);
		
		if (isset($filters['status']))
		{
			$this->db->where('status', $filters['status']);
		}
		
		if (isset($filters['school_id']))
		{
			$this->db->where('school_id', $filters['school_id']);
		}
		
		if (isset($filters['search']))
		{
			$this->db->group_start();
			$this->db->like('product_name', $filters['search']);
			$this->db->or_like('isbn', $filters['search']);
			$this->db->group_end();
		}
		
		if (isset($filters['uniform_type_id']))
		{
			$this->db->where('uniform_type_id', $filters['uniform_type_id']);
		}
		
		if (isset($filters['board_id']))
		{
			$this->db->where('board_id', $filters['board_id']);
		}
		
		if (isset($filters['material_id']))
		{
			$this->db->where('material_id', $filters['material_id']);
		}
		
		if (isset($filters['gender']))
		{
			$this->db->where('gender', $filters['gender']);
		}
		
		if (isset($filters['branch_id']))
		{
			$this->db->where('branch_id', $filters['branch_id']);
		}
		
		return $this->db->count_all_results();
	}
	
	/**
	 * Get uniform by ID
	 *
	 * @param	int	$uniform_id	Uniform ID
	 * @return	array|NULL	Uniform data or NULL if not found
	 */
	public function getUniformById($uniform_id)
	{
		$this->db->select('erp_uniforms.*, erp_uniform_types.name as uniform_type_name, erp_schools.school_name, erp_school_branches.branch_name, erp_school_boards.board_name, erp_materials.name as material_name');
		$this->db->from('erp_uniforms');
		$this->db->join('erp_uniform_types', 'erp_uniform_types.id = erp_uniforms.uniform_type_id', 'left');
		$this->db->join('erp_schools', 'erp_schools.id = erp_uniforms.school_id', 'left');
		$this->db->join('erp_school_branches', 'erp_school_branches.id = erp_uniforms.branch_id', 'left');
		$this->db->join('erp_school_boards', 'erp_school_boards.id = erp_uniforms.board_id', 'left');
		$this->db->join('erp_materials', 'erp_materials.id = erp_uniforms.material_id', 'left');
		$this->db->where('erp_uniforms.id', $uniform_id);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		
		return NULL;
	}
	
	/**
	 * Create uniform
	 *
	 * @param	array	$data	Uniform data
	 * @return	int|FALSE	Uniform ID on success, FALSE on failure
	 */
	public function createUniform($data)
	{
		$this->db->insert('erp_uniforms', $data);
		
		if ($this->db->affected_rows() > 0)
		{
			return $this->db->insert_id();
		}
		
		return FALSE;
	}
	
	/**
	 * Update uniform
	 *
	 * @param	int	$uniform_id	Uniform ID
	 * @param	array	$data		Uniform data
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function updateUniform($uniform_id, $data)
	{
		$this->db->where('id', $uniform_id);
		$this->db->update('erp_uniforms', $data);
		
		return $this->db->affected_rows() > 0;
	}
	
	/**
	 * Delete uniform
	 *
	 * @param	int	$uniform_id	Uniform ID
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function deleteUniform($uniform_id)
	{
		$this->db->where('id', $uniform_id);
		$this->db->delete('erp_uniforms');
		
		return $this->db->affected_rows() > 0;
	}
	
	/**
	 * Get uniform images
	 *
	 * @param	int	$uniform_id	Uniform ID
	 * @return	array	Array of images
	 */
	public function getUniformImages($uniform_id)
	{
		$this->db->where('uniform_id', $uniform_id);
		$this->db->order_by('image_order', 'ASC');
		$query = $this->db->get('erp_uniform_images');
		
		return $query->result_array();
	}
	
	/**
	 * Add uniform image
	 *
	 * @param	array	$data	Image data
	 * @return	int|FALSE	Image ID on success, FALSE on failure
	 */
	public function addUniformImage($data)
	{
		$this->db->insert('erp_uniform_images', $data);
		
		if ($this->db->affected_rows() > 0)
		{
			return $this->db->insert_id();
		}
		
		return FALSE;
	}
	
	/**
	 * Delete uniform image
	 *
	 * @param	int	$image_id	Image ID
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function deleteUniformImage($image_id)
	{
		$this->db->where('id', $image_id);
		$this->db->delete('erp_uniform_images');
		
		return $this->db->affected_rows() > 0;
	}
	
	/**
	 * Delete all uniform images
	 *
	 * @param	int	$uniform_id	Uniform ID
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function deleteAllUniformImages($uniform_id)
	{
		$this->db->where('uniform_id', $uniform_id);
		$this->db->delete('erp_uniform_images');
		
		return $this->db->affected_rows() >= 0;
	}
	
	/**
	 * Get all uniform types
	 *
	 * @return	array	Array of uniform types
	 */
	public function getAllUniformTypes()
	{
		$this->db->where('status', 'active');
		$this->db->order_by('name', 'ASC');
		$query = $this->db->get('erp_uniform_types');
		
		return $query->result_array();
	}
	
	/**
	 * Create uniform type
	 *
	 * @param	array	$data	Uniform type data
	 * @return	int|FALSE	Type ID on success, FALSE on failure
	 */
	public function createUniformType($data)
	{
		$this->db->insert('erp_uniform_types', $data);
		
		if ($this->db->affected_rows() > 0)
		{
			return $this->db->insert_id();
		}
		
		return FALSE;
	}
	
	/**
	 * Get all materials
	 *
	 * @return	array	Array of materials
	 */
	public function getAllMaterials()
	{
		$this->db->where('status', 'active');
		$this->db->order_by('name', 'ASC');
		$query = $this->db->get('erp_materials');
		
		return $query->result_array();
	}
	
	/**
	 * Create material
	 *
	 * @param	array	$data	Material data
	 * @return	int|FALSE	Material ID on success, FALSE on failure
	 */
	public function createMaterial($data)
	{
		$this->db->insert('erp_materials', $data);
		
		if ($this->db->affected_rows() > 0)
		{
			return $this->db->insert_id();
		}
		
		return FALSE;
	}
	
	/**
	 * Get all size charts by vendor
	 *
	 * @param	int	$vendor_id	Vendor ID
	 * @return	array	Array of size charts
	 */
	public function getSizeChartsByVendor($vendor_id)
	{
		$this->db->where('vendor_id', $vendor_id);
		$this->db->where('status', 'active');
		$this->db->order_by('name', 'ASC');
		$query = $this->db->get('erp_size_charts');
		
		return $query->result_array();
	}
	
	/**
	 * Create size chart
	 *
	 * @param	array	$data	Size chart data
	 * @return	int|FALSE	Size chart ID on success, FALSE on failure
	 */
	public function createSizeChart($data)
	{
		$this->db->insert('erp_size_charts', $data);
		
		if ($this->db->affected_rows() > 0)
		{
			return $this->db->insert_id();
		}
		
		return FALSE;
	}
	
	/**
	 * Get sizes by size chart ID
	 *
	 * @param	int	$size_chart_id	Size chart ID
	 * @return	array	Array of sizes
	 */
	public function getSizesBySizeChart($size_chart_id)
	{
		$this->db->where('size_chart_id', $size_chart_id);
		$this->db->where('status', 'active');
		$this->db->order_by('display_order', 'ASC');
		$this->db->order_by('name', 'ASC');
		$query = $this->db->get('erp_sizes');
		
		return $query->result_array();
	}
	
	/**
	 * Add size to size chart
	 *
	 * @param	array	$data	Size data
	 * @return	int|FALSE	Size ID on success, FALSE on failure
	 */
	public function addSize($data)
	{
		$this->db->insert('erp_sizes', $data);
		
		if ($this->db->affected_rows() > 0)
		{
			return $this->db->insert_id();
		}
		
		return FALSE;
	}
	
	/**
	 * Add multiple sizes to size chart
	 *
	 * @param	int	$size_chart_id	Size chart ID
	 * @param	array	$sizes	Array of size names
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function addMultipleSizes($size_chart_id, $sizes)
	{
		if (empty($sizes) || !is_array($sizes))
		{
			return FALSE;
		}
		
		$data = array();
		$order = 0;
		foreach ($sizes as $size_name)
		{
			$size_name = trim($size_name);
			if (!empty($size_name))
			{
				$data[] = array(
					'size_chart_id' => $size_chart_id,
					'name' => $size_name,
					'display_order' => $order++,
					'status' => 'active'
				);
			}
		}
		
		if (!empty($data))
		{
			$this->db->insert_batch('erp_sizes', $data);
			return $this->db->affected_rows() > 0;
		}
		
		return FALSE;
	}
	
	/**
	 * Get size prices for a uniform
	 *
	 * @param	int	$uniform_id	Uniform ID
	 * @return	array	Array of size prices with size names
	 */
	public function getUniformSizePrices($uniform_id)
	{
		$this->db->select('erp_uniform_size_prices.*, erp_sizes.name as size_name');
		$this->db->from('erp_uniform_size_prices');
		$this->db->join('erp_sizes', 'erp_sizes.id = erp_uniform_size_prices.size_id', 'left');
		$this->db->where('erp_uniform_size_prices.uniform_id', $uniform_id);
		$this->db->order_by('erp_sizes.display_order', 'ASC');
		$this->db->order_by('erp_sizes.name', 'ASC');
		$query = $this->db->get();
		
		return $query->result_array();
	}
	
	/**
	 * Save size prices for a uniform
	 *
	 * @param	int	$uniform_id	Uniform ID
	 * @param	array	$size_prices	Array of size prices
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function saveUniformSizePrices($uniform_id, $size_prices)
	{
		if (empty($size_prices) || !is_array($size_prices))
		{
			return FALSE;
		}
		
		// Delete existing size prices for this uniform
		$this->db->where('uniform_id', $uniform_id);
		$this->db->delete('erp_uniform_size_prices');
		
		// Insert new size prices
		$data = array();
		foreach ($size_prices as $size_id => $price_data)
		{
			if (isset($price_data['size_id']) && isset($price_data['mrp']) && isset($price_data['selling_price']))
			{
				$data[] = array(
					'uniform_id' => $uniform_id,
					'size_id' => (int)$price_data['size_id'],
					'mrp' => (float)$price_data['mrp'],
					'selling_price' => (float)$price_data['selling_price']
				);
			}
		}
		
		if (!empty($data))
		{
			$this->db->insert_batch('erp_uniform_size_prices', $data);
			return $this->db->affected_rows() > 0;
		}
		
		return TRUE; // No prices to save, but that's okay
	}
	
	/**
	 * Get uniform count by status
	 *
	 * @param	int	$vendor_id	Vendor ID
	 * @param	string	$status	Uniform status ('active' or 'inactive')
	 * @return	int	Count of uniforms
	 */
	public function getUniformCountByStatus($vendor_id, $status)
	{
		$this->db->where('vendor_id', $vendor_id);
		$this->db->where('status', $status);
		return $this->db->count_all_results('erp_uniforms');
	}
	
	/**
	 * Get out of stock uniform count
	 *
	 * @param	int	$vendor_id	Vendor ID
	 * @return	int	Count of out of stock uniforms (returns 0 as stock column doesn't exist)
	 */
	public function getOutOfStockCount($vendor_id)
	{
		// Stock column doesn't exist in the database
		return 0;
	}
}

