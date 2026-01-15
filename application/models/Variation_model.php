<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Variation Model
 * Handles variation types, values, and combinations
 */
class Variation_model extends CI_Model
{
	/**
	 * Get all variation types by vendor
	 *
	 * @param	int	$vendor_id	Vendor ID
	 * @return	array	Array of variation types
	 */
	public function getVariationTypesByVendor($vendor_id)
	{
		$this->db->where('vendor_id', $vendor_id);
		$this->db->where('status', 'active');
		$this->db->order_by('name', 'ASC');
		$query = $this->db->get('erp_variation_types');
		
		return $query->result_array();
	}
	
	/**
	 * Get variation type by ID
	 *
	 * @param	int	$type_id	Variation type ID
	 * @return	array|FALSE	Variation type data or FALSE
	 */
	public function getVariationTypeById($type_id)
	{
		$this->db->where('id', $type_id);
		$query = $this->db->get('erp_variation_types');
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		
		return FALSE;
	}
	
	/**
	 * Create variation type
	 *
	 * @param	array	$data	Variation type data
	 * @return	int|FALSE	Type ID on success, FALSE on failure
	 */
	public function createVariationType($data)
	{
		// Remove display_order if present
		if (isset($data['display_order']))
		{
			unset($data['display_order']);
		}
		
		$data['created_at'] = date('Y-m-d H:i:s');
		$this->db->insert('erp_variation_types', $data);
		
		if ($this->db->affected_rows() > 0)
		{
			return $this->db->insert_id();
		}
		
		return FALSE;
	}
	
	/**
	 * Update variation type
	 *
	 * @param	int	$type_id	Variation type ID
	 * @param	array	$data	Variation type data
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function updateVariationType($type_id, $data)
	{
		// Remove display_order if present
		if (isset($data['display_order']))
		{
			unset($data['display_order']);
		}
		
		$data['updated_at'] = date('Y-m-d H:i:s');
		$this->db->where('id', $type_id);
		$this->db->update('erp_variation_types', $data);
		
		return $this->db->affected_rows() >= 0;
	}
	
	/**
	 * Delete variation type
	 *
	 * @param	int	$type_id	Variation type ID
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function deleteVariationType($type_id)
	{
		$this->db->where('id', $type_id);
		$this->db->delete('erp_variation_types');
		
		return $this->db->affected_rows() > 0;
	}
	
	/**
	 * Get all values for a variation type
	 *
	 * @param	int	$type_id	Variation type ID
	 * @return	array	Array of variation values
	 */
	public function getValuesByType($type_id)
	{
		$this->db->where('variation_type_id', $type_id);
		$this->db->where('status', 'active');
		$this->db->order_by('name', 'ASC');
		$query = $this->db->get('erp_variation_values');
		
		return $query->result_array();
	}
	
	/**
	 * Get variation value by ID
	 *
	 * @param	int	$value_id	Variation value ID
	 * @return	array|FALSE	Variation value data or FALSE
	 */
	public function getVariationValueById($value_id)
	{
		$this->db->where('id', $value_id);
		$query = $this->db->get('erp_variation_values');
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		
		return FALSE;
	}
	
	/**
	 * Create variation value
	 *
	 * @param	array	$data	Variation value data
	 * @return	int|FALSE	Value ID on success, FALSE on failure
	 */
	public function createVariationValue($data)
	{
		// Remove display_order if present
		if (isset($data['display_order']))
		{
			unset($data['display_order']);
		}
		
		$data['created_at'] = date('Y-m-d H:i:s');
		$this->db->insert('erp_variation_values', $data);
		
		if ($this->db->affected_rows() > 0)
		{
			return $this->db->insert_id();
		}
		
		return FALSE;
	}
	
	/**
	 * Update variation value
	 *
	 * @param	int	$value_id	Variation value ID
	 * @param	array	$data	Variation value data
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function updateVariationValue($value_id, $data)
	{
		// Remove display_order if present
		if (isset($data['display_order']))
		{
			unset($data['display_order']);
		}
		
		$data['updated_at'] = date('Y-m-d H:i:s');
		$this->db->where('id', $value_id);
		$this->db->update('erp_variation_values', $data);
		
		return $this->db->affected_rows() >= 0;
	}
	
	/**
	 * Delete variation value
	 *
	 * @param	int	$value_id	Variation value ID
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function deleteVariationValue($value_id)
	{
		$this->db->where('id', $value_id);
		$this->db->delete('erp_variation_values');
		
		return $this->db->affected_rows() > 0;
	}
	
	/**
	 * Get variation types for a product
	 *
	 * @param	int	$product_id	Product ID
	 * @return	array	Array of variation types with their values
	 */
	public function getProductVariationTypes($product_id)
	{
		$this->db->select('erp_variation_types.*');
		$this->db->from('erp_product_variation_types');
		$this->db->join('erp_variation_types', 'erp_variation_types.id = erp_product_variation_types.variation_type_id', 'inner');
		$this->db->where('erp_product_variation_types.product_id', $product_id);
		$this->db->where('erp_variation_types.status', 'active');
		$this->db->order_by('erp_variation_types.name', 'ASC');
		$query = $this->db->get();
		
		$types = $query->result_array();
		
		// Get values for each type
		foreach ($types as &$type)
		{
			$type['values'] = $this->getValuesByType($type['id']);
		}
		
		return $types;
	}
	
	/**
	 * Add variation types to product
	 *
	 * @param	int	$product_id	Product ID
	 * @param	array	$type_ids	Array of variation type IDs
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function addProductVariationTypes($product_id, $type_ids)
	{
		if (empty($type_ids) || !is_array($type_ids))
		{
			return FALSE;
		}
		
		// Delete existing mappings
		$this->db->where('product_id', $product_id);
		$this->db->delete('erp_product_variation_types');
		
		// Insert new mappings
		$data = array();
		foreach ($type_ids as $type_id)
		{
			$data[] = array(
				'product_id' => $product_id,
				'variation_type_id' => $type_id,
				'created_at' => date('Y-m-d H:i:s')
			);
		}
		
		if (!empty($data))
		{
			$this->db->insert_batch('erp_product_variation_types', $data);
			return $this->db->affected_rows() > 0;
		}
		
		return TRUE;
	}
	
	/**
	 * Generate all combinations for given variation types
	 *
	 * @param	array	$variation_types	Array of variation types with values
	 * @return	array	Array of all possible combinations
	 */
	public function generateCombinations($variation_types)
	{
		if (empty($variation_types))
		{
			return array();
		}
		
		// Get all value arrays
		$value_arrays = array();
		foreach ($variation_types as $type)
		{
			if (!empty($type['values']))
			{
				$value_arrays[] = $type['values'];
			}
		}
		
		if (empty($value_arrays))
		{
			return array();
		}
		
		// Generate cartesian product
		$combinations = $this->cartesianProduct($value_arrays);
		
		// Format combinations with type information
		$formatted = array();
		foreach ($combinations as $combo)
		{
			$formatted_combo = array(
				'values' => array(),
				'key' => '',
				'data' => array()
			);
			
			$key_parts = array();
			foreach ($combo as $index => $value)
			{
				$type = $variation_types[$index];
				$formatted_combo['values'][] = array(
					'type_id' => $type['id'],
					'type_name' => $type['name'],
					'value_id' => $value['id'],
					'value_name' => $value['name']
				);
				$key_parts[] = $type['id'] . ':' . $value['id'];
				$formatted_combo['data'][$type['name']] = $value['name'];
			}
			
			$formatted_combo['key'] = implode('|', $key_parts);
			$formatted[] = $formatted_combo;
		}
		
		return $formatted;
	}
	
	/**
	 * Calculate cartesian product of arrays
	 *
	 * @param	array	$arrays	Array of arrays
	 * @return	array	Cartesian product
	 */
	private function cartesianProduct($arrays)
	{
		if (count($arrays) == 0)
		{
			return array(array());
		}
		
		$result = array();
		$head = array_shift($arrays);
		$tail = $this->cartesianProduct($arrays);
		
		foreach ($head as $item)
		{
			foreach ($tail as $tail_item)
			{
				$result[] = array_merge(array($item), $tail_item);
			}
		}
		
		return $result;
	}
	
	/**
	 * Save variation combinations for a product
	 *
	 * @param	int	$product_id	Product ID
	 * @param	array	$combinations	Array of combinations with pricing
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function saveProductCombinations($product_id, $combinations)
	{
		if (empty($combinations) || !is_array($combinations))
		{
			return FALSE;
		}
		
		// Delete existing combinations
		$this->db->where('product_id', $product_id);
		$this->db->delete('erp_variation_combinations');
		
		$this->db->where('product_id', $product_id);
		$this->db->delete('erp_variation_combination_prices');
		
		$combination_data = array();
		$price_data = array();
		$value_mapping_data = array();
		
		foreach ($combinations as $combo)
		{
			if (empty($combo['key']) || empty($combo['values']))
			{
				continue;
			}
			
			// Insert combination
			$combo_insert = array(
				'product_id' => $product_id,
				'combination_key' => $combo['key'],
				'combination_data' => json_encode($combo['data']),
				'created_at' => date('Y-m-d H:i:s')
			);
			
			$this->db->insert('erp_variation_combinations', $combo_insert);
			$combination_id = $this->db->insert_id();
			
			if ($combination_id)
			{
				// Insert combination values
				foreach ($combo['values'] as $value_info)
				{
					$value_mapping_data[] = array(
						'combination_id' => $combination_id,
						'variation_type_id' => $value_info['type_id'],
						'variation_value_id' => $value_info['value_id']
					);
				}
				
				// Insert pricing
				$price_data[] = array(
					'product_id' => $product_id,
					'combination_id' => $combination_id,
					'mrp' => isset($combo['mrp']) ? $combo['mrp'] : 0.00,
					'selling_price' => isset($combo['selling_price']) ? $combo['selling_price'] : 0.00,
					'stock_quantity' => isset($combo['stock_quantity']) ? $combo['stock_quantity'] : NULL,
					'sku' => isset($combo['sku']) ? $combo['sku'] : NULL,
					'created_at' => date('Y-m-d H:i:s')
				);
			}
		}
		
		// Batch insert value mappings
		if (!empty($value_mapping_data))
		{
			$this->db->insert_batch('erp_variation_combination_values', $value_mapping_data);
		}
		
		// Batch insert prices
		if (!empty($price_data))
		{
			$this->db->insert_batch('erp_variation_combination_prices', $price_data);
		}
		
		return TRUE;
	}
	
	/**
	 * Get combinations for a product with pricing
	 *
	 * @param	int	$product_id	Product ID
	 * @return	array	Array of combinations with pricing
	 */
	public function getProductCombinations($product_id)
	{
		$this->db->select('
			erp_variation_combinations.*,
			erp_variation_combination_prices.mrp,
			erp_variation_combination_prices.selling_price,
			erp_variation_combination_prices.stock_quantity,
			erp_variation_combination_prices.sku
		');
		$this->db->from('erp_variation_combinations');
		$this->db->join('erp_variation_combination_prices', 'erp_variation_combination_prices.combination_id = erp_variation_combinations.id', 'left');
		$this->db->where('erp_variation_combinations.product_id', $product_id);
		$query = $this->db->get();
		
		$combinations = $query->result_array();
		
		// Get values for each combination
		foreach ($combinations as &$combo)
		{
			$this->db->select('
				erp_variation_combination_values.*,
				erp_variation_types.name as type_name,
				erp_variation_values.name as value_name
			');
			$this->db->from('erp_variation_combination_values');
			$this->db->join('erp_variation_types', 'erp_variation_types.id = erp_variation_combination_values.variation_type_id', 'inner');
			$this->db->join('erp_variation_values', 'erp_variation_values.id = erp_variation_combination_values.variation_value_id', 'inner');
			$this->db->where('erp_variation_combination_values.combination_id', $combo['id']);
			$values_query = $this->db->get();
			$combo['values'] = $values_query->result_array();
		}
		
		return $combinations;
	}
	
	/**
	 * Check if variation type name exists for vendor
	 *
	 * @param	string	$name		Variation type name
	 * @param	int	$vendor_id	Vendor ID
	 * @param	int	$exclude_id	Optional type ID to exclude from check
	 * @return	bool	TRUE if exists, FALSE otherwise
	 */
	public function checkVariationTypeNameExists($name, $vendor_id, $exclude_id = NULL)
	{
		$this->db->where('name', $name);
		$this->db->where('vendor_id', $vendor_id);
		$this->db->where('status', 'active');
		
		if ($exclude_id !== NULL)
		{
			$this->db->where('id !=', $exclude_id);
		}
		
		$query = $this->db->get('erp_variation_types');
		return $query->num_rows() > 0;
	}
	
	/**
	 * Check if variation value name exists for type
	 *
	 * @param	string	$name		Variation value name
	 * @param	int	$type_id	Variation type ID
	 * @param	int	$exclude_id	Optional value ID to exclude from check
	 * @return	bool	TRUE if exists, FALSE otherwise
	 */
	public function checkVariationValueNameExists($name, $type_id, $exclude_id = NULL)
	{
		$this->db->where('name', $name);
		$this->db->where('variation_type_id', $type_id);
		$this->db->where('status', 'active');
		
		if ($exclude_id !== NULL)
		{
			$this->db->where('id !=', $exclude_id);
		}
		
		$query = $this->db->get('erp_variation_values');
		return $query->num_rows() > 0;
	}
}

