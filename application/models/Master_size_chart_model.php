<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Master size chart (image gallery) model
 */
class Master_size_chart_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getChartsByVendor($vendor_id, $filters = array(), $limit = NULL, $offset = 0)
	{
		$this->db->from('erp_master_size_charts');
		$this->db->where('vendor_id', $vendor_id);
		if (isset($filters['status']) && $filters['status'] !== '') {
			$this->db->where('status', $filters['status']);
		}
		if (!empty($filters['search'])) {
			$this->db->like('name', $filters['search']);
		}
		$this->db->order_by('created_at', 'DESC');
		if ($limit !== NULL) {
			$this->db->limit($limit, $offset);
		}
		return $this->db->get()->result_array();
	}

	public function countChartsByVendor($vendor_id, $filters = array())
	{
		$this->db->from('erp_master_size_charts');
		$this->db->where('vendor_id', $vendor_id);
		if (isset($filters['status']) && $filters['status'] !== '') {
			$this->db->where('status', $filters['status']);
		}
		if (!empty($filters['search'])) {
			$this->db->like('name', $filters['search']);
		}
		return (int) $this->db->count_all_results();
	}

	public function getChartById($id, $vendor_id = NULL)
	{
		$this->db->where('id', (int) $id);
		if ($vendor_id !== NULL) {
			$this->db->where('vendor_id', (int) $vendor_id);
		}
		return $this->db->get('erp_master_size_charts')->row_array();
	}

	public function createChart($data)
	{
		$now = date('Y-m-d H:i:s');
		if (!isset($data['created_at'])) {
			$data['created_at'] = $now;
		}
		$data['updated_at'] = $now;
		$this->db->insert('erp_master_size_charts', $data);
		return (int) $this->db->insert_id();
	}

	public function updateChart($id, $vendor_id, $data)
	{
		$data['updated_at'] = date('Y-m-d H:i:s');
		$this->db->where('id', (int) $id)->where('vendor_id', (int) $vendor_id);
		return $this->db->update('erp_master_size_charts', $data);
	}

	/**
	 * Soft delete (inactive), same pattern as size charts.
	 */
	public function softDeleteChart($id, $vendor_id)
	{
		return $this->updateChart($id, $vendor_id, array('status' => 'inactive'));
	}

	public function getImagesByChartId($master_size_chart_id)
	{
		$this->db->where('master_size_chart_id', (int) $master_size_chart_id);
		$this->db->order_by('sort_order', 'ASC');
		$this->db->order_by('id', 'ASC');
		return $this->db->get('erp_master_size_chart_images')->result_array();
	}

	public function getMaxSortOrder($master_size_chart_id)
	{
		$row = $this->db->select_max('sort_order')
			->where('master_size_chart_id', (int) $master_size_chart_id)
			->get('erp_master_size_chart_images')
			->row_array();
		return isset($row['sort_order']) && $row['sort_order'] !== null ? (int) $row['sort_order'] : -1;
	}

	public function addImage($master_size_chart_id, $image_path, $sort_order = 0)
	{
		$this->db->insert('erp_master_size_chart_images', array(
			'master_size_chart_id' => (int) $master_size_chart_id,
			'image_path' => $image_path,
			'sort_order' => (int) $sort_order,
			'created_at' => date('Y-m-d H:i:s'),
		));
		return (int) $this->db->insert_id();
	}

	/**
	 * Image row by id with vendor check via chart.
	 */
	public function getImageWithVendor($image_id, $vendor_id)
	{
		$this->db->select('erp_master_size_chart_images.*');
		$this->db->from('erp_master_size_chart_images');
		$this->db->join('erp_master_size_charts', 'erp_master_size_charts.id = erp_master_size_chart_images.master_size_chart_id');
		$this->db->where('erp_master_size_chart_images.id', (int) $image_id);
		$this->db->where('erp_master_size_charts.vendor_id', (int) $vendor_id);
		return $this->db->get()->row_array();
	}

	public function deleteImageRow($image_id, $vendor_id)
	{
		$row = $this->getImageWithVendor($image_id, $vendor_id);
		if (empty($row)) {
			return FALSE;
		}
		$this->db->where('id', (int) $image_id)->delete('erp_master_size_chart_images');
		return $this->db->affected_rows() > 0 ? $row : FALSE;
	}

	public function countImages($master_size_chart_id)
	{
		return (int) $this->db
			->where('master_size_chart_id', (int) $master_size_chart_id)
			->count_all_results('erp_master_size_chart_images');
	}
}
