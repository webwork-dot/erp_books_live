<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Product Migration Controller
 *
 * One-off utility to copy existing rows from legacy product tables
 * (erp_textbooks, erp_notebooks, erp_stationery, erp_uniforms,
 *  erp_individual_products) into the unified erp_products table.
 *
 * IMPORTANT:
 * - Run this per vendor database after creating `erp_products`.
 * - Safe to run multiple times; it skips rows that already have
 *   a matching (legacy_table, legacy_id, vendor_id) entry.
 *
 * Usage (CLI recommended):
 *   php index.php Erp_admin/Product_migration/run
 */
class Product_migration extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		// This controller is expected to be called by an authenticated admin
		// in your environment. There is intentionally no auth here to keep
		// the helper small and self-contained.

		$this->load->model('Product_model');
	}

	/**
	 * Run migration for all supported legacy tables.
	 *
	 * @return	void
	 */
	public function run()
	{
		$this->migrate_textbooks();
		$this->migrate_notebooks();
		$this->migrate_stationery();
		$this->migrate_uniforms();
		$this->migrate_individual_products();

		echo "Product migration completed.\n";
	}

	protected function migrate_textbooks()
	{
		if ( ! $this->db->table_exists('erp_textbooks'))
		{
			return;
		}

		$query = $this->db->get('erp_textbooks');
		foreach ($query->result_array() as $row)
		{
			// Skip if already migrated
			$existing = $this->Product_model->get_product_by_legacy('erp_textbooks', $row['id'], $row['vendor_id']);
			if ($existing)
			{
				continue;
			}

			$payload = array(
				'vendor_id'       => (int) $row['vendor_id'],
				'category_id'     => NULL,
				'type'            => 'textbook',
				'slug'            => isset($row['slug']) ? $row['slug'] : NULL,
				'product_name'    => $row['product_name'],
				'description'     => isset($row['product_description']) ? $row['product_description'] : NULL,
				'status'          => ($row['status'] === 'active') ? 1 : 0,
				'brand_id'        => isset($row['publisher_id']) ? (int) $row['publisher_id'] : NULL,
				'board_id'        => isset($row['board_id']) ? (int) $row['board_id'] : NULL,
				'grade_id'        => NULL,
				'subject_id'      => NULL,
				'discount'        => 0,
				'discount_amount' => 0,
				'selling_price'   => isset($row['selling_price']) ? (float) $row['selling_price'] : 0,
				'product_mrp'     => isset($row['mrp']) ? (float) $row['mrp'] : 0,
				'gst'             => isset($row['gst_percentage']) ? (float) $row['gst_percentage'] : NULL,
				'isbn'            => isset($row['isbn']) ? $row['isbn'] : NULL,
				'hsn'             => isset($row['hsn']) ? (int) $row['hsn'] : NULL,
				'sku'             => isset($row['sku']) ? $row['sku'] : NULL,
				'quantity'        => 0,
				'length'          => isset($row['packaging_length']) ? $row['packaging_length'] : NULL,
				'width'           => isset($row['packaging_width']) ? $row['packaging_width'] : NULL,
				'height'          => isset($row['packaging_height']) ? $row['packaging_height'] : NULL,
				'weight'          => isset($row['packaging_weight']) ? $row['packaging_weight'] : NULL,
				'meta_title'      => isset($row['meta_title']) ? $row['meta_title'] : NULL,
				'meta_keyword'    => isset($row['meta_keywords']) ? $row['meta_keywords'] : NULL,
				'meta_description'=> isset($row['meta_description']) ? $row['meta_description'] : NULL,
				'min_quantity'    => isset($row['min_quantity']) ? (int) $row['min_quantity'] : 0,
				'legacy_table'    => 'erp_textbooks',
				'legacy_id'       => (int) $row['id'],
			);

			$this->Product_model->create_product($payload);
		}
	}

	protected function migrate_notebooks()
	{
		if ( ! $this->db->table_exists('erp_notebooks'))
		{
			return;
		}

		$query = $this->db->get('erp_notebooks');
		foreach ($query->result_array() as $row)
		{
			$existing = $this->Product_model->get_product_by_legacy('erp_notebooks', $row['id'], $row['vendor_id']);
			if ($existing)
			{
				continue;
			}

			$payload = array(
				'vendor_id'       => (int) $row['vendor_id'],
				'category_id'     => NULL,
				'type'            => 'notebook',
				'slug'            => isset($row['slug']) ? $row['slug'] : NULL,
				'product_name'    => $row['product_name'],
				'description'     => isset($row['product_description']) ? $row['product_description'] : NULL,
				'status'          => ($row['status'] === 'active') ? 1 : 0,
				'brand_id'        => isset($row['brand_id']) ? (int) $row['brand_id'] : NULL,
				'discount'        => 0,
				'discount_amount' => 0,
				'selling_price'   => isset($row['selling_price']) ? (float) $row['selling_price'] : 0,
				'product_mrp'     => isset($row['mrp']) ? (float) $row['mrp'] : 0,
				'gst'             => isset($row['gst_percentage']) ? (float) $row['gst_percentage'] : NULL,
				'isbn'            => isset($row['isbn']) ? $row['isbn'] : NULL,
				'hsn'             => isset($row['hsn']) ? (int) $row['hsn'] : NULL,
				'sku'             => isset($row['sku']) ? $row['sku'] : NULL,
				'quantity'        => 0,
				'length'          => isset($row['packaging_length']) ? $row['packaging_length'] : NULL,
				'width'           => isset($row['packaging_width']) ? $row['packaging_width'] : NULL,
				'height'          => isset($row['packaging_height']) ? $row['packaging_height'] : NULL,
				'weight'          => isset($row['packaging_weight']) ? $row['packaging_weight'] : NULL,
				'meta_title'      => isset($row['meta_title']) ? $row['meta_title'] : NULL,
				'meta_keyword'    => isset($row['meta_keywords']) ? $row['meta_keywords'] : NULL,
				'meta_description'=> isset($row['meta_description']) ? $row['meta_description'] : NULL,
				'no_of_pages'     => isset($row['no_of_pages']) ? (int) $row['no_of_pages'] : NULL,
				'binding_type'    => isset($row['binding_type']) ? $row['binding_type'] : NULL,
				'min_quantity'    => isset($row['min_quantity']) ? (int) $row['min_quantity'] : 0,
				'legacy_table'    => 'erp_notebooks',
				'legacy_id'       => (int) $row['id'],
			);

			$this->Product_model->create_product($payload);
		}
	}

	protected function migrate_stationery()
	{
		if ( ! $this->db->table_exists('erp_stationery'))
		{
			return;
		}

		$query = $this->db->get('erp_stationery');
		foreach ($query->result_array() as $row)
		{
			$existing = $this->Product_model->get_product_by_legacy('erp_stationery', $row['id'], $row['vendor_id']);
			if ($existing)
			{
				continue;
			}

			$payload = array(
				'vendor_id'       => (int) $row['vendor_id'],
				'category_id'     => isset($row['category_id']) ? (int) $row['category_id'] : NULL,
				'type'            => 'stationery',
				'slug'            => isset($row['slug']) ? $row['slug'] : NULL,
				'product_name'    => $row['product_name'],
				'description'     => isset($row['product_description']) ? $row['product_description'] : NULL,
				'status'          => ($row['status'] === 'active') ? 1 : 0,
				'discount'        => 0,
				'discount_amount' => 0,
				'selling_price'   => isset($row['selling_price']) ? (float) $row['selling_price'] : 0,
				'product_mrp'     => isset($row['mrp']) ? (float) $row['mrp'] : 0,
				'gst'             => isset($row['gst_percentage']) ? (float) $row['gst_percentage'] : NULL,
				'isbn'            => isset($row['isbn']) ? $row['isbn'] : NULL,
				'hsn'             => isset($row['hsn']) ? (int) $row['hsn'] : NULL,
				'sku'             => isset($row['sku']) ? $row['sku'] : NULL,
				'quantity'        => 0,
				'length'          => isset($row['packaging_length']) ? $row['packaging_length'] : NULL,
				'width'           => isset($row['packaging_width']) ? $row['packaging_width'] : NULL,
				'height'          => isset($row['packaging_height']) ? $row['packaging_height'] : NULL,
				'weight'          => isset($row['packaging_weight']) ? $row['packaging_weight'] : NULL,
				'meta_title'      => isset($row['meta_title']) ? $row['meta_title'] : NULL,
				'meta_keyword'    => isset($row['meta_keywords']) ? $row['meta_keywords'] : NULL,
				'meta_description'=> isset($row['meta_description']) ? $row['meta_description'] : NULL,
				'min_quantity'    => isset($row['min_quantity']) ? (int) $row['min_quantity'] : 0,
				'legacy_table'    => 'erp_stationery',
				'legacy_id'       => (int) $row['id'],
			);

			$this->Product_model->create_product($payload);
		}
	}

	protected function migrate_uniforms()
	{
		if ( ! $this->db->table_exists('erp_uniforms'))
		{
			return;
		}

		$query = $this->db->get('erp_uniforms');
		foreach ($query->result_array() as $row)
		{
			$existing = $this->Product_model->get_product_by_legacy('erp_uniforms', $row['id'], $row['vendor_id']);
			if ($existing)
			{
				continue;
			}

			$payload = array(
				'vendor_id'       => (int) $row['vendor_id'],
				'category_id'     => NULL,
				'type'            => 'uniform',
				'slug'            => isset($row['slug']) ? $row['slug'] : NULL,
				'product_name'    => $row['product_name'],
				'description'     => isset($row['product_description']) ? $row['product_description'] : NULL,
				'status'          => ($row['status'] === 'active') ? 1 : 0,
				'brand_id'        => NULL,
				'board_id'        => isset($row['board_id']) ? (int) $row['board_id'] : NULL,
				'discount'        => 0,
				'discount_amount' => 0,
				'selling_price'   => isset($row['price']) ? (float) $row['price'] : 0,
				'product_mrp'     => isset($row['price']) ? (float) $row['price'] : 0,
				'gst'             => isset($row['gst_percentage']) ? (float) $row['gst_percentage'] : NULL,
				'isbn'            => isset($row['isbn']) ? $row['isbn'] : NULL,
				'hsn'             => isset($row['hsn']) ? (int) $row['hsn'] : NULL,
				'sku'             => NULL,
				'quantity'        => 0,
				'length'          => isset($row['packaging_length']) ? $row['packaging_length'] : NULL,
				'width'           => isset($row['packaging_width']) ? $row['packaging_width'] : NULL,
				'height'          => isset($row['packaging_height']) ? $row['packaging_height'] : NULL,
				'weight'          => isset($row['packaging_weight']) ? $row['packaging_weight'] : NULL,
				'meta_title'      => isset($row['meta_title']) ? $row['meta_title'] : NULL,
				'meta_keyword'    => isset($row['meta_keywords']) ? $row['meta_keywords'] : NULL,
				'meta_description'=> isset($row['meta_description']) ? $row['meta_description'] : NULL,
				'material_id'     => isset($row['material_id']) ? (int) $row['material_id'] : NULL,
				'min_quantity'    => isset($row['min_quantity']) ? (int) $row['min_quantity'] : 0,
				'legacy_table'    => 'erp_uniforms',
				'legacy_id'       => (int) $row['id'],
			);

			$this->Product_model->create_product($payload);
		}
	}

	protected function migrate_individual_products()
	{
		if ( ! $this->db->table_exists('erp_individual_products'))
		{
			return;
		}

		$query = $this->db->get('erp_individual_products');
		foreach ($query->result_array() as $row)
		{
			$existing = $this->Product_model->get_product_by_legacy('erp_individual_products', $row['id'], $row['vendor_id']);
			if ($existing)
			{
				continue;
			}

			$payload = array(
				'vendor_id'       => (int) $row['vendor_id'],
				'category_id'     => NULL,
				'type'            => 'individual',
				'slug'            => isset($row['slug']) ? $row['slug'] : NULL,
				'product_name'    => $row['product_name'],
				'description'     => isset($row['product_description']) ? $row['product_description'] : NULL,
				'status'          => ($row['status'] === 'active') ? 1 : 0,
				'discount'        => 0,
				'discount_amount' => 0,
				'selling_price'   => isset($row['selling_price']) ? (float) $row['selling_price'] : 0,
				'product_mrp'     => isset($row['mrp']) ? (float) $row['mrp'] : 0,
				'gst'             => NULL,
				'isbn'            => isset($row['isbn']) ? $row['isbn'] : NULL,
				'hsn'             => NULL,
				'sku'             => isset($row['sku']) ? $row['sku'] : NULL,
				'quantity'        => 0,
				'meta_title'      => NULL,
				'meta_keyword'    => NULL,
				'meta_description'=> NULL,
				'min_quantity'    => 0,
				'legacy_table'    => 'erp_individual_products',
				'legacy_id'       => (int) $row['id'],
			);

			$this->Product_model->create_product($payload);
		}
	}
}

