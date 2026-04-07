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
	 * Cache table existence checks.
	 *
	 * @var array
	 */
	private $table_exists_cache = array();

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
			// Some tenants use `name` as the chart title column.
			$this->db->like('name', $filters['search']);
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
		$now = date('Y-m-d H:i:s');
		if ($this->db->field_exists('created_at', 'erp_size_charts') && !isset($data['created_at'])) {
			$data['created_at'] = $now;
		}
		if ($this->db->field_exists('updated_at', 'erp_size_charts')) {
			$data['updated_at'] = $now;
		}

		// Normalize title field to `name` (older schema). Ignore `chart_name` if present.
		if (isset($data['chart_name']) && !isset($data['name'])) {
			$data['name'] = $data['chart_name'];
		}
		unset($data['chart_name']);

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
		// Normalize title field to `name` (older schema). Ignore `chart_name` if present.
		if (isset($data['chart_name']) && !isset($data['name'])) {
			$data['name'] = $data['chart_name'];
		}
		unset($data['chart_name']);

		if ($this->db->field_exists('updated_at', 'erp_size_charts')) {
			$data['updated_at'] = date('Y-m-d H:i:s');
		}
		
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
		// Soft delete: mark inactive
		$this->db->where('id', $id);
		if ($vendor_id !== NULL)
		{
			$this->db->where('vendor_id', $vendor_id);
		}

		$data = array('status' => 'inactive');
		if ($this->db->field_exists('updated_at', 'erp_size_charts')) {
			$data['updated_at'] = date('Y-m-d H:i:s');
		}

		return $this->db->update('erp_size_charts', $data);
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
			$this->db->like('name', $filters['search']);
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
		// Deprecated in this ERP: sizes are managed via `erp_sizes`.
		return array();
	}

	/**
	 * Add measurement to size chart
	 *
	 * @param	array	$data	Measurement data
	 * @return	int	Inserted ID or FALSE on failure
	 */
	public function addMeasurement($data)
	{
		// Deprecated in this ERP: sizes are managed via `erp_sizes`.
		return FALSE;
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
		// Deprecated in this ERP: sizes are managed via `erp_sizes`.
		return FALSE;
	}

	/**
	 * Delete measurement
	 *
	 * @param	int	$id	Measurement ID
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function deleteMeasurement($id)
	{
		// Deprecated in this ERP: sizes are managed via `erp_sizes`.
		return FALSE;
	}

	/**
	 * Delete all measurements for a chart
	 *
	 * @param	int	$chart_id	Size chart ID
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function deleteMeasurementsByChart($chart_id)
	{
		// Deprecated in this ERP: sizes are managed via `erp_sizes`.
		return FALSE;
	}

	/**
	 * Get sizes for a chart.
	 *
	 * @param int $chart_id
	 * @param bool $include_inactive
	 * @return array
	 */
	public function getSizesByChart($chart_id, $include_inactive = FALSE)
	{
		$this->db->from('erp_sizes');
		$this->db->where('size_chart_id', (int) $chart_id);
		if (!$include_inactive) {
			$this->db->where('status', 'active');
		}
		$this->db->order_by('display_order', 'ASC');
		$this->db->order_by('name', 'ASC');
		return $this->db->get()->result_array();
	}

	/**
	 * Add multiple sizes to chart (comma/newline input supported upstream).
	 *
	 * @param int $chart_id
	 * @param array $sizes
	 * @return bool
	 */
	public function addMultipleSizes($chart_id, $sizes)
	{
		if (empty($sizes) || !is_array($sizes)) {
			return FALSE;
		}

		$chart_id = (int) $chart_id;

		// Existing (active+inactive) names to dedupe
		$existing = $this->db
			->select('LOWER(TRIM(name)) as n')
			->from('erp_sizes')
			->where('size_chart_id', $chart_id)
			->get()
			->result_array();
		$existing_map = array();
		foreach ($existing as $row) {
			if (!empty($row['n'])) {
				$existing_map[$row['n']] = true;
			}
		}

		// Start order after max display_order
		$max_row = $this->db->select_max('display_order')->where('size_chart_id', $chart_id)->get('erp_sizes')->row_array();
		$order = isset($max_row['display_order']) && $max_row['display_order'] !== NULL ? ((int) $max_row['display_order'] + 1) : 0;

		$batch = array();
		foreach ($sizes as $size_name) {
			$size_name = trim((string) $size_name);
			if ($size_name === '') {
				continue;
			}
			$key = strtolower($size_name);
			if (isset($existing_map[$key])) {
				continue;
			}
			$existing_map[$key] = true;
			$batch[] = array(
				'size_chart_id' => $chart_id,
				'name' => $size_name,
				'display_order' => $order++,
				'status' => 'active'
			);
		}

		if (empty($batch)) {
			return TRUE;
		}

		$this->db->insert_batch('erp_sizes', $batch);
		return $this->db->affected_rows() > 0;
	}

	/**
	 * Soft delete a size (mark inactive).
	 *
	 * @param int $chart_id
	 * @param int $size_id
	 * @return bool
	 */
	public function deactivateSize($chart_id, $size_id)
	{
		$this->db->where('id', (int) $size_id);
		$this->db->where('size_chart_id', (int) $chart_id);
		return $this->db->update('erp_sizes', array('status' => 'inactive'));
	}

	/**
	 * Check if table exists in current tenant DB.
	 *
	 * @param string $table
	 * @return bool
	 */
	private function tableExists($table)
	{
		$table = (string) $table;
		if (isset($this->table_exists_cache[$table]))
		{
			return $this->table_exists_cache[$table];
		}

		$exists = $this->db->table_exists($table);
		$this->table_exists_cache[$table] = (bool) $exists;
		return $this->table_exists_cache[$table];
	}
}