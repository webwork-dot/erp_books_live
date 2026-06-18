<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Product Migration Controller
 *
 * One-off utility to copy existing rows from legacy product tables
 * (erp_textbooks, erp_notebooks, erp_stationery, erp_uniforms,
 *  erp_individual_products) into the unified erp_products table,
 * and sync legacy images into erp_product_images.
 *
 * IMPORTANT:
 * - Run this per vendor database after creating `erp_products`.
 * - Safe to run multiple times; it skips rows that already have
 *   a matching (legacy_table, legacy_id, vendor_id) entry.
 *
 * Usage (CLI recommended):
 *   php index.php Erp_admin/Product_migration/run
 *   php index.php Erp_admin/Product_migration/sync_images
 *   php index.php Erp_admin/Product_migration/run_all
 *   php index.php Erp_admin/Product_migration/sync_images --vendor_id=12
 *   php index.php Erp_admin/Product_migration/sync_images --database=vendor_db_name
 */
class Product_migration extends CI_Controller
{
	/** @var array */
	protected $image_sync_stats = array();

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Product_model');
	}

	/**
	 * Run migration for all supported legacy tables.
	 *
	 * @return	void
	 */
	public function run()
	{
		$this->resolveTargetDatabase();
		$this->migrate_textbooks();
		$this->migrate_notebooks();
		$this->migrate_stationery();
		$this->migrate_uniforms();
		$this->migrate_individual_products();
		$this->fix_legacy_status_values();

		echo "Product migration completed.\n";
		echo "Connected DB: " . $this->db->database . "\n";
	}

	/**
	 * Migrate products then sync all legacy images.
	 *
	 * @return void
	 */
	public function run_all()
	{
		$this->resolveTargetDatabase();
		$this->migrate_textbooks();
		$this->migrate_notebooks();
		$this->migrate_stationery();
		$this->migrate_uniforms();
		$this->migrate_individual_products();
		$this->fix_legacy_status_values();
		$this->sync_images_internal(FALSE);

		echo "Product + image migration completed.\n";
	}

	/**
	 * Sync legacy product images into erp_product_images for the connected DB.
	 *
	 * @return void
	 */
	public function sync_images()
	{
		$this->resolveTargetDatabase();
		$summary = $this->sync_images_internal(FALSE);

		if ($this->input->is_cli_request())
		{
			$this->printImageSyncSummary($summary);
		}
		else
		{
			header('Content-Type: text/plain; charset=utf-8');
			$this->printImageSyncSummary($summary);
		}
	}

	/**
	 * Switch DB connection when vendor_id or database CLI/GET arg is provided.
	 *
	 * @return void
	 */
	protected function resolveTargetDatabase()
	{
		$vendor_id = $this->getCliOrGetArg('vendor_id');
		$database = $this->getCliOrGetArg('database');

		if (empty($vendor_id) && empty($database))
		{
			return;
		}

		$this->load->model('Tenant_model');
		$this->load->library('Tenant');

		$tenant = NULL;
		if ( ! empty($vendor_id))
		{
			$tenant = $this->Tenant_model->getClientById((int) $vendor_id);
		}
		elseif ( ! empty($database))
		{
			$this->db->where('database_name', $database);
			$query = $this->db->get('erp_clients');
			$tenant = $query->num_rows() > 0 ? $query->row_array() : NULL;
			if ( ! $tenant)
			{
				$tenant = array('id' => 0, 'database_name' => $database);
			}
		}

		if ( ! $tenant || empty($tenant['database_name']))
		{
			show_error('Target vendor database could not be resolved.', 400);
		}

		if ( ! $this->tenant->switchDatabase($tenant))
		{
			show_error('Failed to switch to database: ' . $tenant['database_name'], 500);
		}
	}

	/**
	 * Read CLI flag or GET query parameter.
	 *
	 * @param string $name
	 * @return string|null
	 */
	protected function getCliOrGetArg($name)
	{
		$value = $this->input->get($name);
		if ($value !== NULL && $value !== '')
		{
			return (string) $value;
		}

		if ( ! $this->input->is_cli_request())
		{
			return NULL;
		}

		$argv = isset($_SERVER['argv']) ? $_SERVER['argv'] : array();
		$prefix = '--' . $name . '=';
		foreach ($argv as $arg)
		{
			if (strpos($arg, $prefix) === 0)
			{
				return substr($arg, strlen($prefix));
			}
		}

		return NULL;
	}

	/**
	 * Sync all legacy image tables.
	 *
	 * @param bool $ensure_products When TRUE, migrate missing parent products first
	 * @return array
	 */
	protected function sync_images_internal($ensure_products = FALSE)
	{
		if ( ! $this->db->table_exists('erp_product_images'))
		{
			return array('error' => 'erp_product_images table does not exist');
		}

		$sources = array(
			array(
				'image_table'   => 'erp_textbook_images',
				'parent_fk'     => 'textbook_id',
				'parent_table'  => 'erp_textbooks',
				'migrate_method'=> 'migrate_textbooks',
			),
			array(
				'image_table'   => 'erp_notebook_images',
				'parent_fk'     => 'notebook_id',
				'parent_table'  => 'erp_notebooks',
				'migrate_method'=> 'migrate_notebooks',
			),
			array(
				'image_table'   => 'erp_uniform_images',
				'parent_fk'     => 'uniform_id',
				'parent_table'  => 'erp_uniforms',
				'migrate_method'=> 'migrate_uniforms',
			),
			array(
				'image_table'   => 'erp_individual_product_images',
				'parent_fk'     => 'product_id',
				'parent_table'  => 'erp_individual_products',
				'migrate_method'=> 'migrate_individual_products',
			),
			array(
				'image_table'   => 'erp_stationery_images',
				'parent_fk'     => 'stationery_id',
				'parent_table'  => 'erp_stationery',
				'migrate_method'=> 'migrate_stationery',
			),
		);

		$summary = array(
			'database' => $this->db->database,
			'tables'   => array(),
			'total_inserted' => 0,
		);

		foreach ($sources as $source)
		{
			if ( ! $this->db->table_exists($source['image_table']))
			{
				continue;
			}

			$summary['tables'][$source['image_table']] = $this->syncLegacyImageTable(
				$source['image_table'],
				$source['parent_fk'],
				$source['parent_table'],
				$source['migrate_method'],
				$ensure_products
			);
			$summary['total_inserted'] += $summary['tables'][$source['image_table']]['inserted'];
		}

		return $summary;
	}

	/**
	 * Sync one legacy image table.
	 *
	 * @param string $image_table
	 * @param string $parent_fk
	 * @param string $parent_table
	 * @param string $migrate_method
	 * @param bool $ensure_products
	 * @return array
	 */
	protected function syncLegacyImageTable($image_table, $parent_fk, $parent_table, $migrate_method, $ensure_products)
	{
		$stats = array(
			'inserted'    => 0,
			'updated'     => 0,
			'skipped'     => 0,
			'no_product'  => 0,
		);

		if ( ! $this->db->table_exists($image_table))
		{
			return $stats;
		}

		$has_is_main = $this->db->field_exists('is_main', $image_table);
		$images = $this->db->get($image_table)->result_array();
		$synced_parents = array();
		$all_parents = array();

		foreach ($images as $image)
		{
			$legacy_image_id = (int) $image['id'];
			$parent_id = isset($image[$parent_fk]) ? (int) $image[$parent_fk] : 0;

			if ($parent_id <= 0 || empty($image['image_path']))
			{
				$stats['skipped']++;
				continue;
			}

			$parent = $this->db->where('id', $parent_id)->get($parent_table)->row_array();
			if ( ! $parent || ! isset($parent['vendor_id']))
			{
				$stats['no_product']++;
				continue;
			}

			$vendor_id = (int) $parent['vendor_id'];
			$parent_key = $parent_table . '|' . $parent_id . '|' . $vendor_id;
			$all_parents[$parent_key] = array(
				'vendor_id' => $vendor_id,
				'parent_id' => $parent_id,
			);

			$already_exists = $this->Product_model->unified_image_exists($image_table, $legacy_image_id);
			$unified = $this->Product_model->get_product_by_legacy($parent_table, $parent_id, $vendor_id);

			if ( ! $unified && $ensure_products && method_exists($this, $migrate_method))
			{
				$this->{$migrate_method}();
				$unified = $this->Product_model->get_product_by_legacy($parent_table, $parent_id, $vendor_id);
			}

			if ( ! $unified)
			{
				$stats['no_product']++;
				continue;
			}

			$is_main = 0;
			if ($has_is_main && ! empty($image['is_main']))
			{
				$is_main = 1;
			}

			$image_order = isset($image['image_order']) ? (int) $image['image_order'] : 0;
			$result = $this->Product_model->sync_legacy_image(
				$vendor_id,
				$parent_table,
				$parent_id,
				$image_table,
				$legacy_image_id,
				$image['image_path'],
				$is_main,
				$image_order
			);

			if ($result)
			{
				if ($already_exists)
				{
					$stats['updated']++;
				}
				else
				{
					$stats['inserted']++;
				}
				$synced_parents[$parent_table . '|' . $parent_id . '|' . $vendor_id] = array(
					'vendor_id' => $vendor_id,
					'parent_id' => $parent_id,
				);
			}
			else
			{
				$stats['skipped']++;
			}
		}

		foreach ($all_parents as $parent_info)
		{
			if ( ! $this->Product_model->get_product_by_legacy($parent_table, $parent_info['parent_id'], $parent_info['vendor_id']))
			{
				continue;
			}

			$this->Product_model->sync_main_image_from_legacy(
				$parent_info['vendor_id'],
				$parent_table,
				$parent_info['parent_id'],
				$image_table,
				$parent_fk
			);
		}

		return $stats;
	}

	/**
	 * @param array $summary
	 * @return void
	 */
	protected function printImageSyncSummary(array $summary)
	{
		if (isset($summary['error']))
		{
			echo $summary['error'] . "\n";
			return;
		}

		echo "Connected DB: " . $summary['database'] . "\n";
		foreach ($summary['tables'] as $table => $stats)
		{
			echo sprintf(
				"%s: inserted=%d updated=%d skipped=%d no_product=%d\n",
				$table,
				$stats['inserted'],
				isset($stats['updated']) ? $stats['updated'] : 0,
				$stats['skipped'],
				$stats['no_product']
			);
		}
		echo 'TOTAL inserted=' . (int) $summary['total_inserted'] . "\n";
	}

	/**
	 * Re-sync erp_products.status from all legacy product tables.
	 * Repairs rows corrupted when string 'active' was stored in a tinyint column (becomes 0).
	 *
	 * @return void
	 */
	protected function fix_legacy_status_values()
	{
		if ( ! $this->db->table_exists('erp_products'))
		{
			return;
		}

		$legacy_sources = array(
			array('table' => 'erp_textbooks', 'legacy_table' => 'erp_textbooks'),
			array('table' => 'erp_notebooks', 'legacy_table' => 'erp_notebooks'),
			array('table' => 'erp_stationery', 'legacy_table' => 'erp_stationery'),
			array('table' => 'erp_uniforms', 'legacy_table' => 'erp_uniforms'),
			array('table' => 'erp_individual_products', 'legacy_table' => 'erp_individual_products'),
		);

		foreach ($legacy_sources as $source)
		{
			if ( ! $this->db->table_exists($source['table']))
			{
				continue;
			}

			$rows = $this->db->get($source['table'])->result_array();
			foreach ($rows as $row)
			{
				if ( ! isset($row['id'], $row['vendor_id']))
				{
					continue;
				}

				$legacy_status = isset($row['status']) ? $row['status'] : 'active';
				$this->Product_model->sync_status_from_legacy(
					$source['legacy_table'],
					$row['id'],
					$row['vendor_id'],
					$legacy_status
				);
			}
		}
	}

	/**
	 * Insert unified product or update status when legacy row already exists.
	 *
	 * @param string $legacy_table
	 * @param array $row Legacy product row
	 * @param array $payload Full create payload
	 * @return void
	 */
	protected function upsert_legacy_product($legacy_table, array $row, array $payload)
	{
		$existing = $this->Product_model->get_product_by_legacy($legacy_table, $row['id'], $row['vendor_id']);
		if ($existing)
		{
			$this->Product_model->update_product($existing['id'], array(
				'status'          => $payload['status'],
				'product_name'    => $payload['product_name'],
				'description'     => isset($payload['description']) ? $payload['description'] : NULL,
				'selling_price'   => isset($payload['selling_price']) ? $payload['selling_price'] : 0,
				'product_mrp'     => isset($payload['product_mrp']) ? $payload['product_mrp'] : 0,
			));
			return;
		}

		$this->Product_model->create_product($payload);
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
			$payload = array(
				'vendor_id'       => (int) $row['vendor_id'],
				'category_id'     => NULL,
				'type'            => 'textbook',
				'slug'            => isset($row['slug']) ? $row['slug'] : NULL,
				'product_name'    => $row['product_name'],
				'description'     => isset($row['product_description']) ? $row['product_description'] : NULL,
				'status'          => $this->Product_model->normalize_product_status(isset($row['status']) ? $row['status'] : 'active'),
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

			$this->upsert_legacy_product('erp_textbooks', $row, $payload);
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
			$payload = array(
				'vendor_id'       => (int) $row['vendor_id'],
				'category_id'     => NULL,
				'type'            => 'notebook',
				'slug'            => isset($row['slug']) ? $row['slug'] : NULL,
				'product_name'    => $row['product_name'],
				'description'     => isset($row['product_description']) ? $row['product_description'] : NULL,
				'status'          => $this->Product_model->normalize_product_status(isset($row['status']) ? $row['status'] : 'active'),
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

			$this->upsert_legacy_product('erp_notebooks', $row, $payload);
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
			$payload = array(
				'vendor_id'       => (int) $row['vendor_id'],
				'category_id'     => isset($row['category_id']) ? (int) $row['category_id'] : NULL,
				'type'            => 'stationery',
				'slug'            => isset($row['slug']) ? $row['slug'] : NULL,
				'product_name'    => $row['product_name'],
				'description'     => isset($row['product_description']) ? $row['product_description'] : NULL,
				'status'          => $this->Product_model->normalize_product_status(isset($row['status']) ? $row['status'] : 'active'),
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

			$this->upsert_legacy_product('erp_stationery', $row, $payload);
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
			$payload = array(
				'vendor_id'       => (int) $row['vendor_id'],
				'category_id'     => NULL,
				'type'            => 'uniform',
				'slug'            => isset($row['slug']) ? $row['slug'] : NULL,
				'product_name'    => $row['product_name'],
				'description'     => isset($row['product_description']) ? $row['product_description'] : NULL,
				'status'          => $this->Product_model->normalize_product_status(isset($row['status']) ? $row['status'] : 'active'),
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

			$this->upsert_legacy_product('erp_uniforms', $row, $payload);
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
			$payload = array(
				'vendor_id'       => (int) $row['vendor_id'],
				'category_id'     => NULL,
				'type'            => 'individual',
				'slug'            => isset($row['slug']) ? $row['slug'] : NULL,
				'product_name'    => $row['product_name'],
				'description'     => isset($row['product_description']) ? $row['product_description'] : NULL,
				'status'          => $this->Product_model->normalize_product_status(isset($row['status']) ? $row['status'] : 'active'),
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

			$this->upsert_legacy_product('erp_individual_products', $row, $payload);
		}
	}
}
