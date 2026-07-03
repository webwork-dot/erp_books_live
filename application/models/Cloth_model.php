<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cloth Model — products stored directly in erp_products (type=cloths).
 */
class Cloth_model extends CI_Model
{
	const PRODUCT_TYPE = 'cloths';

	public function __construct()
	{
		parent::__construct();
	}

	protected function baseSelect()
	{
		$select = 'erp_products.*, erp_uniform_types.name as cloth_type_name, erp_materials.name as material_name';
		if ($this->db->table_exists('erp_individual_product_colors') && $this->db->field_exists('color_id', 'erp_products')) {
			$select .= ', erp_individual_product_colors.name as color_name, erp_individual_product_colors.color_code';
		}
		if ($this->db->table_exists('erp_master_size_charts') && $this->db->field_exists('master_size_chart_id', 'erp_products')) {
			$select .= ', erp_master_size_charts.name as master_size_chart_name';
		}
		if ($this->db->table_exists('erp_size_charts') && $this->db->field_exists('size_chart_id', 'erp_products')) {
			$select .= ', erp_size_charts.name as size_chart_name';
		}
		return $select;
	}

	protected function applyJoins()
	{
		$this->db->join('erp_uniform_types', 'erp_uniform_types.id = erp_products.cloth_type_id', 'left');
		$this->db->join('erp_materials', 'erp_materials.id = erp_products.material_id', 'left');
		if ($this->db->table_exists('erp_individual_product_colors') && $this->db->field_exists('color_id', 'erp_products')) {
			$this->db->join('erp_individual_product_colors', 'erp_individual_product_colors.id = erp_products.color_id', 'left');
		}
		if ($this->db->table_exists('erp_master_size_charts') && $this->db->field_exists('master_size_chart_id', 'erp_products')) {
			$this->db->join('erp_master_size_charts', 'erp_master_size_charts.id = erp_products.master_size_chart_id', 'left');
		}
		if ($this->db->table_exists('erp_size_charts') && $this->db->field_exists('size_chart_id', 'erp_products')) {
			$this->db->join('erp_size_charts', 'erp_size_charts.id = erp_products.size_chart_id', 'left');
		}
	}

	protected function applyFilters($vendor_id, $filters = array())
	{
		$this->db->where('erp_products.vendor_id', (int) $vendor_id);
		$this->db->where('erp_products.type', self::PRODUCT_TYPE);
		$this->db->where('erp_products.is_deleted', 0);

		if (isset($filters['status']) && $filters['status'] !== '') {
			$status = ($filters['status'] === 'active') ? 1 : 0;
			$this->db->where('erp_products.status', $status);
		}

		if (!empty($filters['search'])) {
			$this->db->group_start();
			$this->db->like('erp_products.product_name', $filters['search']);
			$this->db->or_like('erp_products.isbn', $filters['search']);
			$this->db->or_like('erp_products.sku', $filters['search']);
			$this->db->group_end();
		}

		if (!empty($filters['cloth_type_id'])) {
			$this->db->where('erp_products.cloth_type_id', (int) $filters['cloth_type_id']);
		}

		if (!empty($filters['material_id'])) {
			$this->db->where('erp_products.material_id', (int) $filters['material_id']);
		}

		if (!empty($filters['gender'])) {
			$this->db->where("FIND_IN_SET('" . $this->db->escape_str($filters['gender']) . "', erp_products.gender) >", 0);
		}
	}

	public function getClothsByVendor($vendor_id, $filters = array(), $limit = NULL, $offset = 0)
	{
		$this->db->select($this->baseSelect());
		$this->db->from('erp_products');
		$this->applyJoins();
		$this->applyFilters($vendor_id, $filters);
		$this->db->order_by('erp_products.id', 'DESC');

		if ($limit !== NULL) {
			$this->db->limit($limit, $offset);
		}

		return $this->db->get()->result_array();
	}

	public function getTotalClothsByVendor($vendor_id, $filters = array())
	{
		$this->db->from('erp_products');
		$this->applyFilters($vendor_id, $filters);
		return $this->db->count_all_results();
	}

	public function getClothById($product_id, $vendor_id = NULL)
	{
		$this->db->select($this->baseSelect());
		$this->db->from('erp_products');
		$this->applyJoins();
		$this->db->where('erp_products.id', (int) $product_id);
		$this->db->where('erp_products.type', self::PRODUCT_TYPE);
		$this->db->where('erp_products.is_deleted', 0);
		if ($vendor_id !== NULL) {
			$this->db->where('erp_products.vendor_id', (int) $vendor_id);
		}

		$row = $this->db->get()->row_array();
		return $row ?: NULL;
	}

	public function getClothBySlug($slug)
	{
		$this->db->select($this->baseSelect());
		$this->db->from('erp_products');
		$this->applyJoins();
		$this->db->where('erp_products.slug', $slug);
		$this->db->where('erp_products.type', self::PRODUCT_TYPE);
		$this->db->where('erp_products.status', 1);
		$this->db->where('erp_products.is_deleted', 0);

		$row = $this->db->get()->row_array();
		return $row ?: NULL;
	}

	/**
	 * Keep only real erp_products columns (drops JOIN aliases like color_name).
	 */
	protected function sanitizeClothRowForDb(array $data)
	{
		if (!$this->db->table_exists('erp_products')) {
			return $data;
		}

		$allowed = array_flip($this->db->list_fields('erp_products'));
		return array_intersect_key($data, $allowed);
	}

	public function createCloth(array $data)
	{
		$data = $this->sanitizeClothRowForDb($data);
		$data['type'] = self::PRODUCT_TYPE;
		$data['legacy_table'] = NULL;
		$data['legacy_id'] = NULL;
		$data['is_deleted'] = 0;
		if (!isset($data['created_at'])) {
			$data['created_at'] = date('Y-m-d H:i:s');
		}

		$this->db->insert('erp_products', $data);
		if ($this->db->affected_rows() > 0) {
			return (int) $this->db->insert_id();
		}
		return FALSE;
	}

	public function updateCloth($product_id, array $data)
	{
		$data = $this->sanitizeClothRowForDb($data);
		$data['updated_at'] = date('Y-m-d H:i:s');
		$this->db->where('id', (int) $product_id);
		$this->db->where('type', self::PRODUCT_TYPE);
		$this->db->update('erp_products', $data);
		return $this->db->affected_rows() >= 0;
	}

	public function deleteCloth($product_id)
	{
		$this->db->where('id', (int) $product_id);
		$this->db->where('type', self::PRODUCT_TYPE);
		$this->db->update('erp_products', array('is_deleted' => 1, 'status' => 0, 'updated_at' => date('Y-m-d H:i:s')));
		return $this->db->affected_rows() > 0;
	}

	public function getClothSizePrices($product_id)
	{
		if (!$this->db->table_exists('erp_product_size_prices')) {
			return array();
		}

		$this->db->select('erp_product_size_prices.*, erp_sizes.name as size_name');
		$this->db->from('erp_product_size_prices');
		$this->db->join('erp_sizes', 'erp_sizes.id = erp_product_size_prices.size_id', 'left');
		$this->db->where('erp_product_size_prices.product_id', (int) $product_id);
		$this->db->order_by('erp_sizes.display_order', 'ASC');
		$this->db->order_by('erp_sizes.name', 'ASC');
		return $this->db->get()->result_array();
	}

	/**
	 * Save flat per-size prices. Accepts uniform-style nested array or flat size_id => row.
	 */
	public function saveClothSizePrices($product_id, $size_prices)
	{
		if (!$this->db->table_exists('erp_product_size_prices')) {
			return FALSE;
		}

		$product_id = (int) $product_id;
		if (!is_array($size_prices)) {
			$size_prices = array();
		}

		$normalized = array();
		foreach ($size_prices as $class_or_size => $maybe_sizes) {
			if (is_array($maybe_sizes) && isset($maybe_sizes['mrp'])) {
				$size_id = (int) $class_or_size;
				$row = $maybe_sizes;
			} elseif (is_array($maybe_sizes)) {
				foreach ($maybe_sizes as $size_id => $row) {
					if (!is_array($row)) {
						continue;
					}
					$size_id = isset($row['size_id']) ? (int) $row['size_id'] : (int) $size_id;
					$this->appendPriceRow($normalized, $size_id, $row);
				}
				continue;
			} else {
				continue;
			}
			$this->appendPriceRow($normalized, $size_id, $row);
		}

		$this->db->trans_begin();
		$this->db->where('product_id', $product_id)->delete('erp_product_size_prices');

		if (!empty($normalized)) {
			$batch = array();
			$now = date('Y-m-d H:i:s');
			foreach ($normalized as $row) {
				$row['product_id'] = $product_id;
				$row['created_at'] = $now;
				$row['updated_at'] = $now;
				$batch[] = $row;
			}
			$this->db->insert_batch('erp_product_size_prices', $batch);
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		}
		$this->db->trans_commit();
		return TRUE;
	}

	protected function appendPriceRow(array &$normalized, $size_id, array $row)
	{
		$size_id = (int) $size_id;
		if ($size_id <= 0) {
			return;
		}
		$mrp = isset($row['mrp']) && $row['mrp'] !== '' ? (float) $row['mrp'] : NULL;
		$selling = isset($row['selling_price']) && $row['selling_price'] !== '' ? (float) $row['selling_price'] : NULL;
		if ($mrp === NULL || $selling === NULL) {
			return;
		}
		$normalized[$size_id] = array(
			'size_id' => $size_id,
			'mrp' => $mrp,
			'selling_price' => $selling,
		);
	}

	public function getAllClothTypes()
	{
		$this->load->model('Uniform_model');
		return $this->Uniform_model->getAllUniformTypes();
	}

	public function createClothType($data)
	{
		$this->load->model('Uniform_model');
		return $this->Uniform_model->createUniformType($data);
	}

	public function getAllMaterials()
	{
		$this->load->model('Uniform_model');
		return $this->Uniform_model->getAllMaterials();
	}

	public function getColorsByVendor($vendor_id)
	{
		if (!$this->db->table_exists('erp_individual_product_colors')) {
			return array();
		}
		$this->load->model('Individual_product_model');
		return $this->Individual_product_model->getColorsByVendor($vendor_id);
	}

	public function createColor($data)
	{
		if (!$this->db->table_exists('erp_individual_product_colors')) {
			return FALSE;
		}
		$this->load->model('Individual_product_model');
		return $this->Individual_product_model->createColor($data);
	}

	public function createMaterial($data)
	{
		$this->load->model('Uniform_model');
		return $this->Uniform_model->createMaterial($data);
	}

	public function getSizeChartsByVendor($vendor_id)
	{
		$this->load->model('Uniform_model');
		return $this->Uniform_model->getSizeChartsByVendor($vendor_id);
	}

	public function getMasterSizeChartsByVendor($vendor_id, $include_chart_id = NULL)
	{
		$this->load->model('Uniform_model');
		return $this->Uniform_model->getMasterSizeChartsByVendor($vendor_id, $include_chart_id);
	}

	public function getSizesBySizeChart($size_chart_id)
	{
		$this->load->model('Uniform_model');
		return $this->Uniform_model->getSizesBySizeChart($size_chart_id);
	}
}
