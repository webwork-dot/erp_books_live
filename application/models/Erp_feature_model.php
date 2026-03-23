<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ERP Feature Model
 *
 * Handles database operations for feature management
 *
 * @package		ERP
 * @subpackage	Models
 * @category	Models
 * @author		ERP Team
 */
class Erp_feature_model extends CI_Model
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
	 * Get all features
	 *
	 * @param	array	$filters	Optional filters
	 * @param	int	$limit		Limit (for pagination)
	 * @param	int	$offset		Offset (for pagination)
	 * @return	array	Array of features
	 */
	public function getAllFeatures($filters = array(), $limit = NULL, $offset = 0)
	{
		$this->db->select('erp_features.*, parent.name as parent_name');
		$this->db->from('erp_features');
		$this->db->join('erp_features as parent', 'parent.id = erp_features.parent_id', 'left');
		
		if (isset($filters['is_active']))
		{
			$this->db->where('erp_features.is_active', $filters['is_active']);
		}
		
		if (isset($filters['parent_id']))
		{
			if ($filters['parent_id'] === NULL || $filters['parent_id'] === '')
			{
				$this->db->where('erp_features.parent_id IS NULL');
			}
			else
			{
				$this->db->where('erp_features.parent_id', $filters['parent_id']);
			}
		}
		
		$this->db->order_by('erp_features.parent_id', 'ASC');
		$this->db->order_by('erp_features.id', 'ASC');
		
		if ($limit !== NULL)
		{
			$this->db->limit($limit, $offset);
		}
		
		$query = $this->db->get();
		
		return $query->result_array();
	}
	
	/**
	 * Get main categories only (no parent)
	 *
	 * @return	array	Array of main category features
	 */
	public function getMainCategories()
	{
		$this->db->where('parent_id IS NULL');
		$this->db->where('is_active', 1);
		$this->db->order_by('name', 'ASC');
		$query = $this->db->get('erp_features');
		
		return $query->result_array();
	}
	
	/**
	 * Get subcategories by parent ID
	 *
	 * @param	int	$parent_id	Parent feature ID
	 * @return	array	Array of subcategory features
	 */
	public function getSubcategoriesByParent($parent_id)
	{
		$this->db->where('parent_id', $parent_id);
		$this->db->where('is_active', 1);
		$this->db->order_by('name', 'ASC');
		$query = $this->db->get('erp_features');
		
		return $query->result_array();
	}
	
	/**
	 * Get total features count
	 *
	 * @param	array	$filters	Optional filters
	 * @return	int	Total number of features
	 */
	public function getTotalFeatures($filters = array())
	{
		$this->db->from('erp_features');
		
		if (isset($filters['is_active']))
		{
			$this->db->where('is_active', $filters['is_active']);
		}
		
		return $this->db->count_all_results();
	}
	
	/**
	 * Get feature by ID
	 *
	 * @param	int	$feature_id	Feature ID
	 * @return	array|NULL	Feature data or NULL if not found
	 */
	public function getFeatureById($feature_id)
	{
		$this->db->where('id', $feature_id);
		$query = $this->db->get('erp_features');
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		
		return NULL;
	}
	
	/**
	 * Get feature by slug
	 *
	 * @param	string	$slug	Feature slug
	 * @return	array|NULL	Feature data or NULL if not found
	 */
	public function getFeatureBySlug($slug)
	{
		$this->db->where('slug', $slug);
		$query = $this->db->get('erp_features');
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		
		return NULL;
	}
	
	/**
	 * Create feature
	 *
	 * @param	array	$data	Feature data
	 * @return	int|FALSE	Feature ID on success, FALSE on failure
	 */
	public function createFeature($data)
	{
		$this->db->insert('erp_features', $data);
		
		if ($this->db->affected_rows() > 0)
		{
			return $this->db->insert_id();
		}
		
		return FALSE;
	}
	
	/**
	 * Update feature
	 *
	 * @param	int	$feature_id	Feature ID
	 * @param	array	$data		Feature data
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function updateFeature($feature_id, $data)
	{
		$this->db->where('id', $feature_id);
		$result = $this->db->update('erp_features', $data);
		
		// Return TRUE if query executed successfully, even if no rows were affected
		// (which happens when the data hasn't changed)
		return $result !== FALSE;
	}
	
	/**
	 * Delete feature
	 *
	 * @param	int	$feature_id	Feature ID
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function deleteFeature($feature_id)
	{
		$this->db->where('id', $feature_id);
		$this->db->delete('erp_features');
		
		return $this->db->affected_rows() > 0;
	}
}

