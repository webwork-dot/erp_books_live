<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Size Chart Model
 *
 * Handles database operations for size chart management
 *
 * @package		ERP
 * @subpackage	Models
 * @category	Models
 * @author		ERP Team
 */
class Size_chart_model extends CI_Model
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
	}

	/**
	 * Get all size charts by vendor
	 *
	 * @param	int	$vendor_id	Vendor ID
	 * @param	array	$filters	Optional filters
	 * @param	int	$limit		Limit (for pagination)
	 * @param	int	$offset		Offset (for pagination)
	 * @return	array	Array of size charts
	 */
	public function getSizeChartsByVendor($vendor_id, $filters = array(), $limit = NULL, $offset = 0)
	{
		$this->db->select('*');
		$this->db->from('erp_size_charts');
		$this->db->where('vendor_id', $vendor_id);
		
		if (isset($filters['status']))
		{
			$this->db->where('status', $filters['status']);
		}
		
		if (isset($filters['search']) && !empty($filters['search']))
		{
			$this->db->group_start();
			$this->db->like('chart_name', $filters['search']);
			$this->db->or_like('description', $filters['search']);
			$this->db->group_end();
		}
		
		if ($limit !== NULL)
		{
			$this->db->limit($limit, $offset);
		}
		
		$this->db->order_by('created_at', 'DESC');
		
		$query = $this->db->get();
		return $query->result_array();
	}

	/**
	 * Get size chart by ID
	 *
	 * @param	int	$id	Size chart ID
	 * @param	int	$vendor_id	Vendor ID (for security)
	 * @return	array	Size chart data or empty array if not found
	 */
	public function getSizeChartById($id, $vendor_id = NULL)
	{
		$this->db->select('*');
		$this->db->from('erp_size_charts');
		$this->db->where('id', $id);
		
		if ($vendor_id !== NULL)
		{
			$this->db->where('vendor_id', $vendor_id);
		}
		
		$query = $this->db->get();
		return $query->row_array();
	}

	/**
	 * Create new size chart
	 *
	 * @param	array	$data	Size chart data
	 * @return	int	Inserted ID or FALSE on failure
	 */
	public function createSizeChart($data)
	{
		$data['created_at'] = date('Y-m-d H:i:s');
		$data['updated_at'] = date('Y-m-d H:i:s');
		
		$this->db->insert('erp_size_charts', $data);
		return $this->db->insert_id();
	}

	/**
	 * Update size chart
	 *
	 * @param	int	$id	Size chart ID
	 * @param	array	$data	Size chart data
	 * @param	int	$vendor_id	Vendor ID (for security)
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function updateSizeChart($id, $data, $vendor_id = NULL)
	{
		$data['updated_at'] = date('Y-m-d H:i:s');
		
		$this->db->where('id', $id);
		if ($vendor_id !== NULL)
		{
			$this->db->where('vendor_id', $vendor_id);
		}
		
		return $this->db->update('erp_size_charts', $data);
	}

	/**
	 * Delete size chart
	 *
	 * @param	int	$id	Size chart ID
	 * @param	int	$vendor_id	Vendor ID (for security)
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function deleteSizeChart($id, $vendor_id = NULL)
	{
		$this->db->where('id', $id);
		if ($vendor_id !== NULL)
		{
			$this->db->where('vendor_id', $vendor_id);
		}
		
		return $this->db->delete('erp_size_charts');
	}

	/**
	 * Count size charts by vendor
	 *
	 * @param	int	$vendor_id	Vendor ID
	 * @param	array	$filters	Optional filters
	 * @return	int	Total count
	 */
	public function countSizeChartsByVendor($vendor_id, $filters = array())
	{
		$this->db->from('erp_size_charts');
		$this->db->where('vendor_id', $vendor_id);
		
		if (isset($filters['status']))
		{
			$this->db->where('status', $filters['status']);
		}
		
		if (isset($filters['search']) && !empty($filters['search']))
		{
			$this->db->group_start();
			$this->db->like('chart_name', $filters['search']);
			$this->db->or_like('description', $filters['search']);
			$this->db->group_end();
		}
		
		return $this->db->count_all_results();
	}

	/**
	 * Get size chart measurements
	 *
	 * @param	int	$chart_id	Size chart ID
	 * @return	array	Array of measurements
	 */
	public function getMeasurements($chart_id)
	{
		$this->db->select('*');
		$this->db->from('erp_size_chart_measurements');
		$this->db->where('chart_id', $chart_id);
		$this->db->order_by('measurement_order', 'ASC');
		
		$query = $this->db->get();
		return $query->result_array();
	}

	/**
	 * Add measurement to size chart
	 *
	 * @param	array	$data	Measurement data
	 * @return	int	Inserted ID or FALSE on failure
	 */
	public function addMeasurement($data)
	{
		return $this->db->insert('erp_size_chart_measurements', $data);
	}

	/**
	 * Update measurement
	 *
	 * @param	int	$id	Measurement ID
	 * @param	array	$data	Measurement data
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function updateMeasurement($id, $data)
	{
		$this->db->where('id', $id);
		return $this->db->update('erp_size_chart_measurements', $data);
	}

	/**
	 * Delete measurement
	 *
	 * @param	int	$id	Measurement ID
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function deleteMeasurement($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete('erp_size_chart_measurements');
	}

	/**
	 * Delete all measurements for a chart
	 *
	 * @param	int	$chart_id	Size chart ID
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function deleteMeasurementsByChart($chart_id)
	{
		$this->db->where('chart_id', $chart_id);
		return $this->db->delete('erp_size_chart_measurements');
	}
}