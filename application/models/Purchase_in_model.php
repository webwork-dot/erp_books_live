<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Purchase In Model
 * Vendors, Companies, Warehouses for purchase-in-category feature.
 */
class Purchase_in_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function ensureTables()
	{
		$queries = array(
			"CREATE TABLE IF NOT EXISTS `erp_purchase_vendors` (
				`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				`vendor_id` INT UNSIGNED NOT NULL,
				`vendor_name` VARCHAR(200) NOT NULL,
				`contact_person_name` VARCHAR(150) DEFAULT NULL,
				`contact_person_number` VARCHAR(30) DEFAULT NULL,
				`email` VARCHAR(150) DEFAULT NULL,
				`gst_name` VARCHAR(200) DEFAULT NULL,
				`gst_no` VARCHAR(50) DEFAULT NULL,
				`fssai_no` VARCHAR(50) DEFAULT NULL,
				`address_1` TEXT DEFAULT NULL,
				`address_2` TEXT DEFAULT NULL,
				`state_id` INT UNSIGNED DEFAULT NULL,
				`city_id` INT UNSIGNED DEFAULT NULL,
				`pincode` VARCHAR(20) DEFAULT NULL,
				`state_code` VARCHAR(20) DEFAULT NULL,
				`status` TINYINT(1) NOT NULL DEFAULT 1,
				`created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
				`updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`),
				KEY `idx_vendor_id` (`vendor_id`),
				KEY `idx_status` (`status`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

			"CREATE TABLE IF NOT EXISTS `erp_purchase_companies` (
				`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				`vendor_id` INT UNSIGNED NOT NULL,
				`company_name` VARCHAR(200) NOT NULL,
				`contact_person_name` VARCHAR(150) DEFAULT NULL,
				`contact_person_number` VARCHAR(30) DEFAULT NULL,
				`email` VARCHAR(150) DEFAULT NULL,
				`gst_name` VARCHAR(200) DEFAULT NULL,
				`gst_no` VARCHAR(50) DEFAULT NULL,
				`fssai_no` VARCHAR(50) DEFAULT NULL,
				`address_1` TEXT DEFAULT NULL,
				`address_2` TEXT DEFAULT NULL,
				`address_3` TEXT DEFAULT NULL,
				`state_id` INT UNSIGNED DEFAULT NULL,
				`city_id` INT UNSIGNED DEFAULT NULL,
				`pincode` VARCHAR(20) DEFAULT NULL,
				`state_code` VARCHAR(20) DEFAULT NULL,
				`status` TINYINT(1) NOT NULL DEFAULT 1,
				`created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
				`updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`),
				KEY `idx_vendor_id` (`vendor_id`),
				KEY `idx_status` (`status`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

			"CREATE TABLE IF NOT EXISTS `erp_purchase_warehouses` (
				`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				`vendor_id` INT UNSIGNED NOT NULL,
				`warehouse_name` VARCHAR(200) NOT NULL,
				`address_1` TEXT DEFAULT NULL,
				`address_2` TEXT DEFAULT NULL,
				`address_3` TEXT DEFAULT NULL,
				`state_id` INT UNSIGNED DEFAULT NULL,
				`city_id` INT UNSIGNED DEFAULT NULL,
				`pincode` VARCHAR(20) DEFAULT NULL,
				`status` TINYINT(1) NOT NULL DEFAULT 1,
				`created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
				`updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`),
				KEY `idx_vendor_id` (`vendor_id`),
				KEY `idx_status` (`status`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

			"CREATE TABLE IF NOT EXISTS `erp_purchase_warehouse_companies` (
				`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				`warehouse_id` INT UNSIGNED NOT NULL,
				`company_id` INT UNSIGNED NOT NULL,
				`created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`),
				UNIQUE KEY `uniq_warehouse_company` (`warehouse_id`,`company_id`),
				KEY `idx_company_id` (`company_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

			"CREATE TABLE IF NOT EXISTS `erp_purchase_orders` (
				`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				`vendor_id` INT UNSIGNED NOT NULL,
				`po_no` VARCHAR(50) NOT NULL,
				`fy_label` VARCHAR(10) NOT NULL,
				`po_seq` INT UNSIGNED NOT NULL DEFAULT 1,
				`company_id` INT UNSIGNED NOT NULL,
				`warehouse_id` INT UNSIGNED NOT NULL,
				`purchase_vendor_id` INT UNSIGNED NOT NULL,
				`po_date` DATE NOT NULL,
				`expected_date` DATE DEFAULT NULL,
				`without_gst` TINYINT(1) NOT NULL DEFAULT 0,
				`direct_purchase` TINYINT(1) NOT NULL DEFAULT 0,
				`notes` TEXT DEFAULT NULL,
				`discount_percent` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
				`discount_amount` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
				`transport_percent` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
				`transport_amount` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
				`other_charges_desc` VARCHAR(255) DEFAULT NULL,
				`other_charges_amount` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
				`round_off` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
				`apply_round_off` TINYINT(1) NOT NULL DEFAULT 0,
				`basic_amount` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
				`taxable_amount` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
				`cgst_amount` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
				`sgst_amount` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
				`igst_amount` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
				`grand_total` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
				`status` VARCHAR(30) NOT NULL DEFAULT 'draft',
				`created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
				`updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`),
				UNIQUE KEY `uniq_po_no_vendor` (`vendor_id`,`po_no`),
				KEY `idx_vendor_id` (`vendor_id`),
				KEY `idx_company_id` (`company_id`),
				KEY `idx_warehouse_id` (`warehouse_id`),
				KEY `idx_purchase_vendor_id` (`purchase_vendor_id`),
				KEY `idx_fy_label` (`fy_label`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

			"CREATE TABLE IF NOT EXISTS `erp_purchase_order_items` (
				`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				`po_id` INT UNSIGNED NOT NULL,
				`line_no` INT UNSIGNED NOT NULL DEFAULT 1,
				`product_id` INT UNSIGNED DEFAULT NULL,
				`product_type` VARCHAR(50) DEFAULT NULL,
				`product_name` VARCHAR(500) NOT NULL,
				`hsn` VARCHAR(50) DEFAULT NULL,
				`gst_percent` DECIMAL(8,2) NOT NULL DEFAULT 0.00,
				`qty` DECIMAL(14,3) NOT NULL DEFAULT 1.000,
				`rate` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
				`discount` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
				`basic_amount` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
				`taxable_amount` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
				`gst_amount` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
				`line_total` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
				`created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`),
				KEY `idx_po_id` (`po_id`),
				KEY `idx_product_id` (`product_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

			"CREATE TABLE IF NOT EXISTS `erp_purchase_activity_log` (
				`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				`vendor_id` INT UNSIGNED NOT NULL,
				`entity_type` VARCHAR(40) NOT NULL,
				`entity_id` INT UNSIGNED DEFAULT NULL,
				`entity_label` VARCHAR(255) DEFAULT NULL,
				`action` VARCHAR(40) NOT NULL,
				`action_label` VARCHAR(255) DEFAULT NULL,
				`actor_id` INT UNSIGNED DEFAULT NULL,
				`actor_name` VARCHAR(150) DEFAULT NULL,
				`meta_json` TEXT DEFAULT NULL,
				`ip_address` VARCHAR(45) DEFAULT NULL,
				`created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`),
				KEY `idx_vendor_id` (`vendor_id`),
				KEY `idx_entity` (`entity_type`,`entity_id`),
				KEY `idx_created_at` (`created_at`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

			"CREATE TABLE IF NOT EXISTS `erp_purchase_receipts` (
				`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				`vendor_id` INT UNSIGNED NOT NULL,
				`po_id` INT UNSIGNED NOT NULL,
				`pr_no` VARCHAR(50) NOT NULL,
				`fy_label` VARCHAR(10) NOT NULL,
				`pr_seq` INT UNSIGNED NOT NULL DEFAULT 1,
				`company_id` INT UNSIGNED NOT NULL,
				`warehouse_id` INT UNSIGNED NOT NULL,
				`purchase_vendor_id` INT UNSIGNED NOT NULL,
				`invoice_no` VARCHAR(100) DEFAULT NULL,
				`received_date` DATE NOT NULL,
				`transport_percent` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
				`transport_amount` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
				`items_total` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
				`grand_total` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
				`notes` TEXT DEFAULT NULL,
				`status` VARCHAR(30) NOT NULL DEFAULT 'posted',
				`created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
				`updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`),
				UNIQUE KEY `uniq_pr_no_vendor` (`vendor_id`,`pr_no`),
				KEY `idx_vendor_id` (`vendor_id`),
				KEY `idx_po_id` (`po_id`),
				KEY `idx_fy_label` (`fy_label`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

			"CREATE TABLE IF NOT EXISTS `erp_purchase_receipt_items` (
				`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				`receipt_id` INT UNSIGNED NOT NULL,
				`po_item_id` INT UNSIGNED NOT NULL,
				`product_id` INT UNSIGNED DEFAULT NULL,
				`product_type` VARCHAR(50) DEFAULT NULL,
				`product_name` VARCHAR(500) NOT NULL,
				`ordered_qty` DECIMAL(14,3) NOT NULL DEFAULT 0.000,
				`receive_qty` DECIMAL(14,3) NOT NULL DEFAULT 0.000,
				`rate` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
				`created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`),
				KEY `idx_receipt_id` (`receipt_id`),
				KEY `idx_po_item_id` (`po_item_id`),
				KEY `idx_product_id` (`product_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
		);

		foreach ($queries as $sql) {
			$this->db->query($sql);
		}

		$this->ensureReceiptExtraColumns();
		$this->ensureProductCostPriceColumns();
		return TRUE;
	}

	protected function ensureProductCostPriceColumns()
	{
		$this->load->model('Product_model');
		$this->Product_model->ensure_cost_price_columns();
	}

	protected function ensureReceiptExtraColumns()
	{
		if (!$this->db->table_exists('erp_purchase_receipts')) {
			return;
		}
		$cols = array(
			'transport_percent' => "ALTER TABLE `erp_purchase_receipts` ADD COLUMN `transport_percent` DECIMAL(10,2) NOT NULL DEFAULT 0.00 AFTER `received_date`",
			'transport_amount' => "ALTER TABLE `erp_purchase_receipts` ADD COLUMN `transport_amount` DECIMAL(14,2) NOT NULL DEFAULT 0.00 AFTER `transport_percent`",
			'items_total' => "ALTER TABLE `erp_purchase_receipts` ADD COLUMN `items_total` DECIMAL(14,2) NOT NULL DEFAULT 0.00 AFTER `transport_amount`",
			'grand_total' => "ALTER TABLE `erp_purchase_receipts` ADD COLUMN `grand_total` DECIMAL(14,2) NOT NULL DEFAULT 0.00 AFTER `items_total`"
		);
		foreach ($cols as $col => $sql) {
			if (!$this->db->field_exists($col, 'erp_purchase_receipts')) {
				$this->db->query($sql);
			}
		}
	}

	/* ---------- Fiscal year / PO number ---------- */

	public function get_fy_label($date = NULL)
	{
		$ts = $date ? strtotime($date) : time();
		if ($ts === FALSE) {
			$ts = time();
		}
		$y = (int)date('Y', $ts);
		$m = (int)date('n', $ts);
		if ($m >= 4) {
			$start = $y;
			$end = $y + 1;
		} else {
			$start = $y - 1;
			$end = $y;
		}
		return substr((string)$start, -2) . '-' . substr((string)$end, -2);
	}

	public function suggest_po_no($owner_vendor_id, $date = NULL)
	{
		$fy = $this->get_fy_label($date);
		$this->db->select_max('po_seq', 'max_seq');
		$this->db->from('erp_purchase_orders');
		$this->db->where('vendor_id', (int)$owner_vendor_id);
		$this->db->where('fy_label', $fy);
		$row = $this->db->get()->row_array();
		$next = (isset($row['max_seq']) && (int)$row['max_seq'] > 0) ? ((int)$row['max_seq'] + 1) : 1;
		$po_no = 'PO-' . str_pad((string)$next, 4, '0', STR_PAD_LEFT) . '/' . $fy;
		return array(
			'po_no' => $po_no,
			'fy_label' => $fy,
			'po_seq' => $next
		);
	}

	public function suggest_pr_no($owner_vendor_id, $date = NULL)
	{
		$fy = $this->get_fy_label($date);
		$this->db->select_max('pr_seq', 'max_seq');
		$this->db->from('erp_purchase_receipts');
		$this->db->where('vendor_id', (int)$owner_vendor_id);
		$this->db->where('fy_label', $fy);
		$row = $this->db->get()->row_array();
		$next = (isset($row['max_seq']) && (int)$row['max_seq'] > 0) ? ((int)$row['max_seq'] + 1) : 1;
		$pr_no = 'PR-' . str_pad((string)$next, 4, '0', STR_PAD_LEFT) . '/' . $fy;
		return array(
			'pr_no' => $pr_no,
			'fy_label' => $fy,
			'pr_seq' => $next
		);
	}

	/* ---------- Activity log ---------- */

	public function log_activity($owner_vendor_id, $data)
	{
		$row = array(
			'vendor_id' => (int)$owner_vendor_id,
			'entity_type' => isset($data['entity_type']) ? (string)$data['entity_type'] : '',
			'entity_id' => isset($data['entity_id']) ? (int)$data['entity_id'] : NULL,
			'entity_label' => isset($data['entity_label']) ? $data['entity_label'] : NULL,
			'action' => isset($data['action']) ? (string)$data['action'] : '',
			'action_label' => isset($data['action_label']) ? $data['action_label'] : NULL,
			'actor_id' => isset($data['actor_id']) ? (int)$data['actor_id'] : NULL,
			'actor_name' => isset($data['actor_name']) ? $data['actor_name'] : NULL,
			'meta_json' => NULL,
			'ip_address' => isset($data['ip_address']) ? $data['ip_address'] : NULL,
			'created_at' => date('Y-m-d H:i:s')
		);
		if (isset($data['meta_json'])) {
			$row['meta_json'] = is_string($data['meta_json']) ? $data['meta_json'] : json_encode($data['meta_json']);
		} elseif (isset($data['meta'])) {
			$row['meta_json'] = is_string($data['meta']) ? $data['meta'] : json_encode($data['meta']);
		}
		if ($row['entity_type'] === '' || $row['action'] === '') {
			return FALSE;
		}
		return (bool)$this->db->insert('erp_purchase_activity_log', $row);
	}

	public function get_activity_logs($owner_vendor_id, $limit = 100)
	{
		$this->db->from('erp_purchase_activity_log');
		$this->db->where('vendor_id', (int)$owner_vendor_id);
		$this->db->order_by('id', 'DESC');
		$this->db->limit((int)$limit);
		return $this->db->get()->result_array();
	}

	/* ---------- Vendors ---------- */

	public function get_vendors($owner_vendor_id)
	{
		$this->db->select('pv.*, s.name AS state_name, c.name AS city_name');
		$this->db->from('erp_purchase_vendors pv');
		$this->db->join('states s', 's.id = pv.state_id', 'left');
		$this->db->join('cities c', 'c.id = pv.city_id', 'left');
		$this->db->where('pv.vendor_id', (int)$owner_vendor_id);
		$this->db->order_by('pv.id', 'DESC');
		return $this->db->get()->result_array();
	}

	public function get_vendor($id, $owner_vendor_id)
	{
		$this->db->where('id', (int)$id);
		$this->db->where('vendor_id', (int)$owner_vendor_id);
		$row = $this->db->get('erp_purchase_vendors')->row_array();
		return $row ? $row : NULL;
	}

	public function add_vendor($data)
	{
		$this->db->insert('erp_purchase_vendors', $data);
		return $this->db->insert_id();
	}

	public function update_vendor($id, $data, $owner_vendor_id)
	{
		$this->db->where('id', (int)$id);
		$this->db->where('vendor_id', (int)$owner_vendor_id);
		return $this->db->update('erp_purchase_vendors', $data);
	}

	public function delete_vendor($id, $owner_vendor_id)
	{
		$this->db->where('id', (int)$id);
		$this->db->where('vendor_id', (int)$owner_vendor_id);
		return $this->db->delete('erp_purchase_vendors');
	}

	/* ---------- Companies ---------- */

	public function get_companies($owner_vendor_id, $active_only = FALSE)
	{
		$this->db->select('pc.*, s.name AS state_name, c.name AS city_name');
		$this->db->from('erp_purchase_companies pc');
		$this->db->join('states s', 's.id = pc.state_id', 'left');
		$this->db->join('cities c', 'c.id = pc.city_id', 'left');
		$this->db->where('pc.vendor_id', (int)$owner_vendor_id);
		if ($active_only) {
			$this->db->where('pc.status', 1);
		}
		$this->db->order_by('pc.id', 'DESC');
		return $this->db->get()->result_array();
	}

	public function get_company($id, $owner_vendor_id)
	{
		$this->db->where('id', (int)$id);
		$this->db->where('vendor_id', (int)$owner_vendor_id);
		$row = $this->db->get('erp_purchase_companies')->row_array();
		return $row ? $row : NULL;
	}

	public function add_company($data)
	{
		$this->db->insert('erp_purchase_companies', $data);
		return $this->db->insert_id();
	}

	public function update_company($id, $data, $owner_vendor_id)
	{
		$this->db->where('id', (int)$id);
		$this->db->where('vendor_id', (int)$owner_vendor_id);
		return $this->db->update('erp_purchase_companies', $data);
	}

	public function delete_company($id, $owner_vendor_id)
	{
		$this->db->where('company_id', (int)$id)->delete('erp_purchase_warehouse_companies');
		$this->db->where('id', (int)$id);
		$this->db->where('vendor_id', (int)$owner_vendor_id);
		return $this->db->delete('erp_purchase_companies');
	}

	/* ---------- Warehouses ---------- */

	public function get_warehouses($owner_vendor_id)
	{
		$this->db->select('pw.*, s.name AS state_name, c.name AS city_name');
		$this->db->from('erp_purchase_warehouses pw');
		$this->db->join('states s', 's.id = pw.state_id', 'left');
		$this->db->join('cities c', 'c.id = pw.city_id', 'left');
		$this->db->where('pw.vendor_id', (int)$owner_vendor_id);
		$this->db->order_by('pw.id', 'DESC');
		$rows = $this->db->get()->result_array();

		foreach ($rows as &$row) {
			$row['company_ids'] = $this->get_warehouse_company_ids((int)$row['id']);
			$row['company_names'] = $this->get_warehouse_company_names((int)$row['id']);
		}
		return $rows;
	}

	public function get_warehouse($id, $owner_vendor_id)
	{
		$this->db->where('id', (int)$id);
		$this->db->where('vendor_id', (int)$owner_vendor_id);
		$row = $this->db->get('erp_purchase_warehouses')->row_array();
		if (!$row) {
			return NULL;
		}
		$row['company_ids'] = $this->get_warehouse_company_ids((int)$row['id']);
		return $row;
	}

	public function get_warehouse_company_ids($warehouse_id)
	{
		$rows = $this->db->select('company_id')->from('erp_purchase_warehouse_companies')->where('warehouse_id', (int)$warehouse_id)->get()->result_array();
		$ids = array();
		foreach ($rows as $r) {
			$ids[] = (int)$r['company_id'];
		}
		return $ids;
	}

	public function get_warehouse_company_names($warehouse_id)
	{
		$this->db->select('pc.company_name');
		$this->db->from('erp_purchase_warehouse_companies wcm');
		$this->db->join('erp_purchase_companies pc', 'pc.id = wcm.company_id', 'inner');
		$this->db->where('wcm.warehouse_id', (int)$warehouse_id);
		$this->db->order_by('pc.company_name', 'ASC');
		$rows = $this->db->get()->result_array();
		$names = array();
		foreach ($rows as $r) {
			$names[] = $r['company_name'];
		}
		return $names;
	}

	public function add_warehouse($data, $company_ids = array())
	{
		$this->db->trans_begin();
		$this->db->insert('erp_purchase_warehouses', $data);
		$warehouse_id = (int)$this->db->insert_id();
		$this->sync_warehouse_companies($warehouse_id, $company_ids);
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return 0;
		}
		$this->db->trans_commit();
		return $warehouse_id;
	}

	public function update_warehouse($id, $data, $owner_vendor_id, $company_ids = array())
	{
		$this->db->trans_begin();
		$this->db->where('id', (int)$id);
		$this->db->where('vendor_id', (int)$owner_vendor_id);
		$this->db->update('erp_purchase_warehouses', $data);
		$this->sync_warehouse_companies((int)$id, $company_ids);
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		}
		$this->db->trans_commit();
		return TRUE;
	}

	public function sync_warehouse_companies($warehouse_id, $company_ids = array())
	{
		$this->db->where('warehouse_id', (int)$warehouse_id)->delete('erp_purchase_warehouse_companies');
		if (!is_array($company_ids)) {
			return;
		}
		$seen = array();
		foreach ($company_ids as $cid) {
			$cid = (int)$cid;
			if ($cid <= 0 || isset($seen[$cid])) {
				continue;
			}
			$seen[$cid] = TRUE;
			$this->db->insert('erp_purchase_warehouse_companies', array(
				'warehouse_id' => (int)$warehouse_id,
				'company_id' => $cid
			));
		}
	}

	public function delete_warehouse($id, $owner_vendor_id)
	{
		$this->db->where('warehouse_id', (int)$id)->delete('erp_purchase_warehouse_companies');
		$this->db->where('id', (int)$id);
		$this->db->where('vendor_id', (int)$owner_vendor_id);
		return $this->db->delete('erp_purchase_warehouses');
	}

	public function get_warehouses_for_company($owner_vendor_id, $company_id)
	{
		$this->db->select('pw.id, pw.warehouse_name');
		$this->db->from('erp_purchase_warehouses pw');
		$this->db->join('erp_purchase_warehouse_companies wcm', 'wcm.warehouse_id = pw.id', 'inner');
		$this->db->where('pw.vendor_id', (int)$owner_vendor_id);
		$this->db->where('pw.status', 1);
		$this->db->where('wcm.company_id', (int)$company_id);
		$this->db->order_by('pw.warehouse_name', 'ASC');
		return $this->db->get()->result_array();
	}

	/* ---------- Purchase Orders ---------- */

	public function get_purchase_orders($owner_vendor_id)
	{
		$this->db->select('po.*, pc.company_name, pw.warehouse_name, pv.vendor_name AS supplier_name');
		$this->db->from('erp_purchase_orders po');
		$this->db->join('erp_purchase_companies pc', 'pc.id = po.company_id', 'left');
		$this->db->join('erp_purchase_warehouses pw', 'pw.id = po.warehouse_id', 'left');
		$this->db->join('erp_purchase_vendors pv', 'pv.id = po.purchase_vendor_id', 'left');
		$this->db->where('po.vendor_id', (int)$owner_vendor_id);
		$this->db->order_by('po.id', 'DESC');
		return $this->db->get()->result_array();
	}

	public function get_purchase_order($id, $owner_vendor_id)
	{
		$this->db->where('id', (int)$id);
		$this->db->where('vendor_id', (int)$owner_vendor_id);
		$row = $this->db->get('erp_purchase_orders')->row_array();
		if (!$row) {
			return NULL;
		}
		$row['items'] = $this->get_purchase_order_items_with_receive((int)$row['id']);
		return $row;
	}

	public function get_purchase_order_items($po_id)
	{
		$this->db->from('erp_purchase_order_items');
		$this->db->where('po_id', (int)$po_id);
		$this->db->order_by('line_no', 'ASC');
		$this->db->order_by('id', 'ASC');
		return $this->db->get()->result_array();
	}

	public function get_received_qty_map($po_id)
	{
		$map = array();
		if (!$this->db->table_exists('erp_purchase_receipt_items')) {
			return $map;
		}
		$this->db->select('pri.po_item_id, SUM(pri.receive_qty) AS qty_received');
		$this->db->from('erp_purchase_receipt_items pri');
		$this->db->join('erp_purchase_receipts pr', 'pr.id = pri.receipt_id', 'inner');
		$this->db->where('pr.po_id', (int)$po_id);
		$this->db->group_by('pri.po_item_id');
		foreach ($this->db->get()->result_array() as $r) {
			$map[(int)$r['po_item_id']] = (float)$r['qty_received'];
		}
		return $map;
	}

	public function get_purchase_order_items_with_receive($po_id)
	{
		$items = $this->get_purchase_order_items($po_id);
		$received = $this->get_received_qty_map($po_id);
		foreach ($items as &$item) {
			$ordered = (float)$item['qty'];
			$recv = isset($received[(int)$item['id']]) ? (float)$received[(int)$item['id']] : 0.0;
			$item['qty_received'] = $recv;
			$item['qty_pending'] = max(0, round($ordered - $recv, 3));
		}
		unset($item);
		return $items;
	}

	public function refresh_po_receive_status($po_id, $owner_vendor_id)
	{
		$items = $this->get_purchase_order_items_with_receive((int)$po_id);
		if (empty($items)) {
			return FALSE;
		}
		$any_received = FALSE;
		$all_done = TRUE;
		foreach ($items as $item) {
			if ((float)$item['qty_received'] > 0) {
				$any_received = TRUE;
			}
			if ((float)$item['qty_pending'] > 0.0001) {
				$all_done = FALSE;
			}
		}
		$status = 'pending';
		if ($all_done) {
			$status = 'received';
		} elseif ($any_received) {
			$status = 'partial';
		}
		$this->db->where('id', (int)$po_id);
		$this->db->where('vendor_id', (int)$owner_vendor_id);
		return $this->db->update('erp_purchase_orders', array('status' => $status));
	}

	public function create_purchase_order($header, $items)
	{
		$this->db->trans_begin();
		$this->db->insert('erp_purchase_orders', $header);
		$po_id = (int)$this->db->insert_id();
		if ($po_id <= 0) {
			$this->db->trans_rollback();
			return 0;
		}
		$line = 1;
		$this->load->model('Product_model');
		foreach ($items as $item) {
			$item['po_id'] = $po_id;
			$item['line_no'] = $line++;
			$this->db->insert('erp_purchase_order_items', $item);
			if (!empty($item['product_id']) && isset($item['rate']) && (float)$item['rate'] > 0) {
				$this->Product_model->fill_cost_price_if_empty((int)$item['product_id'], (float)$item['rate']);
			}
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return 0;
		}
		$this->db->trans_commit();
		return $po_id;
	}

	public function get_product_filter_options($owner_vendor_id)
	{
		$vid = (int)$owner_vendor_id;
		$out = array(
			'publishers' => array(),
			'boards' => array(),
			'grades' => array(),
			'ages' => array(),
			'stationery_categories' => array(),
			'stationery_brands' => array(),
			'stationery_colours' => array()
		);
		if ($this->db->table_exists('erp_textbook_publishers')) {
			$out['publishers'] = $this->db->select('id,name')->from('erp_textbook_publishers')
				->where('vendor_id', $vid)->where('status', 'active')->order_by('name', 'ASC')->get()->result_array();
		}
		if ($this->db->table_exists('erp_school_boards')) {
			$out['boards'] = $this->db->select('id,board_name')->from('erp_school_boards')
				->order_by('board_name', 'ASC')->get()->result_array();
		}
		if ($this->db->table_exists('erp_textbook_grades')) {
			$out['grades'] = $this->db->select('id,name')->from('erp_textbook_grades')
				->where('vendor_id', $vid)->where('status', 'active')->order_by('name', 'ASC')->get()->result_array();
		}
		if ($this->db->table_exists('erp_textbook_ages')) {
			$out['ages'] = $this->db->select('id,name')->from('erp_textbook_ages')
				->where('vendor_id', $vid)->where('status', 'active')->order_by('name', 'ASC')->get()->result_array();
		}
		if ($this->db->table_exists('erp_stationery_categories')) {
			$out['stationery_categories'] = $this->db->select('id,name')->from('erp_stationery_categories')
				->where('vendor_id', $vid)->where('status', 'active')->order_by('name', 'ASC')->get()->result_array();
		}
		if ($this->db->table_exists('erp_stationery_brands')) {
			$out['stationery_brands'] = $this->db->select('id,name')->from('erp_stationery_brands')
				->where('vendor_id', $vid)->where('status', 'active')->order_by('name', 'ASC')->get()->result_array();
		}
		if ($this->db->table_exists('erp_stationery_colours')) {
			$out['stationery_colours'] = $this->db->select('id,name')->from('erp_stationery_colours')
				->where('vendor_id', $vid)->where('status', 'active')->order_by('name', 'ASC')->get()->result_array();
		}
		return $out;
	}

	public function search_products($owner_vendor_id, $q, $limit = 25, $type = '', $filters = array())
	{
		$this->ensureProductCostPriceColumns();
		$q = trim((string)$q);
		$type = strtolower(trim((string)$type));
		if (!is_array($filters)) {
			$filters = array();
		}
		$publisher_id = isset($filters['publisher_id']) ? (int)$filters['publisher_id'] : 0;
		$board_id = isset($filters['board_id']) ? (int)$filters['board_id'] : 0;
		$grade_age_type = isset($filters['grade_age_type']) ? trim((string)$filters['grade_age_type']) : '';
		$grade_id = isset($filters['grade_id']) ? (int)$filters['grade_id'] : 0;
		$age_id = isset($filters['age_id']) ? (int)$filters['age_id'] : 0;
		$category_id = isset($filters['category_id']) ? (int)$filters['category_id'] : 0;
		$brand_id = isset($filters['brand_id']) ? (int)$filters['brand_id'] : 0;
		$colour_id = isset($filters['colour_id']) ? (int)$filters['colour_id'] : 0;
		$notebook_brand_id = isset($filters['notebook_brand_id']) ? (int)$filters['notebook_brand_id'] : 0;

		$limit = max(1, min(100, (int)$limit));
		$results = array();

		if (!$this->db->table_exists('erp_products')) {
			return $results;
		}

		$has_cp = $this->db->field_exists('cost_price', 'erp_products');
		$has_code = $this->db->field_exists('product_code', 'erp_products');
		$has_isbn = $this->db->field_exists('isbn', 'erp_products');
		$select = 'p.id, p.product_name, p.type, p.hsn, p.gst, p.selling_price, p.product_mrp, p.sku';
		if ($has_cp) {
			$select .= ', p.cost_price';
		}
		if ($has_code) {
			$select .= ', p.product_code';
		}
		if ($has_isbn) {
			$select .= ', p.isbn';
		}

		$tokens = array();
		if ($q !== '') {
			$parts = preg_split('/\s+/', $q);
			foreach ($parts as $part) {
				$part = trim($part);
				if ($part !== '') {
					$tokens[] = $part;
				}
			}
			$tokens = array_slice($tokens, 0, 6);
		}

		$need_textbook = ($type === 'textbook')
			|| $publisher_id > 0 || $board_id > 0 || $grade_id > 0 || $age_id > 0
			|| $grade_age_type === 'grade' || $grade_age_type === 'age';
		$need_notebook = ($type === 'notebook') || $notebook_brand_id > 0;
		$need_stationery = ($type === 'stationery') || $category_id > 0 || $brand_id > 0 || $colour_id > 0;

		$this->db->select($select, FALSE);
		$this->db->from('erp_products p');
		$this->db->where('p.vendor_id', (int)$owner_vendor_id);
		$this->db->where('p.is_deleted', 0);
		$this->db->where('p.status', 1);
		if ($type !== '' && $type !== 'all') {
			$this->db->where('p.type', $type);
		}

		if ($need_textbook && $this->db->table_exists('erp_textbooks')) {
			$this->db->join(
				'erp_textbooks tb',
				"tb.id = p.legacy_id AND p.legacy_table = 'erp_textbooks' AND p.type = 'textbook'",
				($type === 'textbook' || $publisher_id || $board_id || $grade_id || $age_id) ? 'inner' : 'left'
			);
			if ($publisher_id > 0) {
				$this->db->where('tb.publisher_id', $publisher_id);
			}
			if ($board_id > 0) {
				$this->db->where('tb.board_id', $board_id);
			}
			if ($grade_age_type === 'grade' || $grade_age_type === 'age') {
				$this->db->where('tb.grade_age_type', $grade_age_type);
			}
			if ($grade_id > 0 && $this->db->table_exists('erp_textbook_grade_mapping')) {
				$this->db->join('erp_textbook_grade_mapping tgm', 'tgm.textbook_id = tb.id AND tgm.grade_id = ' . (int)$grade_id, 'inner');
			}
			if ($age_id > 0 && $this->db->table_exists('erp_textbook_age_mapping')) {
				$this->db->join('erp_textbook_age_mapping tam', 'tam.textbook_id = tb.id AND tam.age_id = ' . (int)$age_id, 'inner');
			}
		}

		if ($need_notebook && $this->db->table_exists('erp_notebooks')) {
			$this->db->join(
				'erp_notebooks nb',
				"nb.id = p.legacy_id AND p.legacy_table = 'erp_notebooks' AND p.type = 'notebook'",
				($type === 'notebook' || $notebook_brand_id > 0) ? 'inner' : 'left'
			);
			if ($notebook_brand_id > 0) {
				$this->db->where('nb.brand_id', $notebook_brand_id);
			}
		}

		if ($need_stationery && $this->db->table_exists('erp_stationery')) {
			$this->db->join(
				'erp_stationery st',
				"st.id = p.legacy_id AND p.legacy_table = 'erp_stationery' AND p.type = 'stationery'",
				($type === 'stationery' || $category_id || $brand_id || $colour_id) ? 'inner' : 'left'
			);
			if ($category_id > 0) {
				$this->db->where('st.category_id', $category_id);
			}
			if ($brand_id > 0) {
				$this->db->where('st.brand_id', $brand_id);
			}
			if ($colour_id > 0) {
				$this->db->where('st.colour_id', $colour_id);
			}
		}

		foreach ($tokens as $token) {
			$this->db->group_start();
			$this->db->like('p.product_name', $token);
			$this->db->or_like('p.sku', $token);
			$this->db->or_like('p.type', $token);
			if ($has_code) {
				$this->db->or_like('p.product_code', $token);
			}
			if ($has_isbn) {
				$this->db->or_like('p.isbn', $token);
			}
			if (is_numeric($token)) {
				$this->db->or_where('p.hsn', (int)$token);
			} else {
				$this->db->or_like('p.hsn', $token);
			}
			$this->db->group_end();
		}

		if ($q !== '') {
			$esc = $this->db->escape_like_str($q);
			$this->db->order_by("CASE
				WHEN p.product_name = ".$this->db->escape($q)." THEN 0
				WHEN p.product_name LIKE ".$this->db->escape($esc.'%')." THEN 1
				WHEN p.sku = ".$this->db->escape($q)." THEN 2
				WHEN p.sku LIKE ".$this->db->escape($esc.'%')." THEN 3
				ELSE 4 END", '', FALSE);
		}
		$this->db->group_by('p.id');
		$this->db->order_by('p.product_name', 'ASC');
		$this->db->limit($limit);
		$rows = $this->db->get()->result_array();

		$stock_map = array();
		if ($this->db->table_exists('inventory_stock_snapshot') && !empty($rows)) {
			$ids = array();
			foreach ($rows as $r) {
				$ids[] = (int)$r['id'];
			}
			$this->db->select('item_ref_id, SUM(qty_available) AS qty');
			$this->db->from('inventory_stock_snapshot');
			$this->db->where('item_type', 'book');
			$this->db->where_in('item_ref_id', $ids);
			$this->db->group_by('item_ref_id');
			foreach ($this->db->get()->result_array() as $s) {
				$stock_map[(int)$s['item_ref_id']] = (float)$s['qty'];
			}
		}

		foreach ($rows as $r) {
			$pid = (int)$r['id'];
			$cost = ($has_cp && isset($r['cost_price'])) ? (float)$r['cost_price'] : 0;
			$results[] = array(
				'id' => $pid,
				'product_name' => (string)$r['product_name'],
				'product_type' => (string)$r['type'],
				'hsn' => isset($r['hsn']) ? (string)$r['hsn'] : '',
				'gst_percent' => isset($r['gst']) ? (float)$r['gst'] : 0,
				'rate' => 0,
				'cost_price' => $cost,
				'sku' => isset($r['sku']) ? (string)$r['sku'] : '',
				'product_code' => isset($r['product_code']) ? (string)$r['product_code'] : '',
				'stock' => isset($stock_map[$pid]) ? $stock_map[$pid] : 0,
				'category' => ucfirst((string)$r['type'])
			);
		}

		return $results;
	}

	public function get_product_purchase_history($owner_vendor_id, $product_id, $limit = 20)
	{
		$this->db->select('poi.qty, poi.rate, poi.created_at, po.po_no, po.po_date, pv.vendor_name');
		$this->db->from('erp_purchase_order_items poi');
		$this->db->join('erp_purchase_orders po', 'po.id = poi.po_id', 'inner');
		$this->db->join('erp_purchase_vendors pv', 'pv.id = po.purchase_vendor_id', 'left');
		$this->db->where('po.vendor_id', (int)$owner_vendor_id);
		$this->db->where('poi.product_id', (int)$product_id);
		$this->db->order_by('po.po_date', 'DESC');
		$this->db->order_by('poi.id', 'DESC');
		$this->db->limit((int)$limit);
		return $this->db->get()->result_array();
	}

	/* ---------- Purchase Receipts ---------- */

	public function get_purchase_receipts($owner_vendor_id)
	{
		$this->db->select('pr.*, po.po_no, pc.company_name, pw.warehouse_name, pv.vendor_name AS supplier_name');
		$this->db->from('erp_purchase_receipts pr');
		$this->db->join('erp_purchase_orders po', 'po.id = pr.po_id', 'left');
		$this->db->join('erp_purchase_companies pc', 'pc.id = pr.company_id', 'left');
		$this->db->join('erp_purchase_warehouses pw', 'pw.id = pr.warehouse_id', 'left');
		$this->db->join('erp_purchase_vendors pv', 'pv.id = pr.purchase_vendor_id', 'left');
		$this->db->where('pr.vendor_id', (int)$owner_vendor_id);
		$this->db->order_by('pr.id', 'DESC');
		$rows = $this->db->get()->result_array();
		foreach ($rows as &$row) {
			$row = $this->normalize_receipt_totals($row);
		}
		unset($row);
		return $rows;
	}

	public function get_purchase_receipt($id, $owner_vendor_id)
	{
		$this->db->select('pr.*, po.po_no');
		$this->db->from('erp_purchase_receipts pr');
		$this->db->join('erp_purchase_orders po', 'po.id = pr.po_id', 'left');
		$this->db->where('pr.id', (int)$id);
		$this->db->where('pr.vendor_id', (int)$owner_vendor_id);
		$row = $this->db->get()->row_array();
		if (!$row) {
			return NULL;
		}
		$this->db->from('erp_purchase_receipt_items');
		$this->db->where('receipt_id', (int)$row['id']);
		$this->db->order_by('id', 'ASC');
		$row['items'] = $this->db->get()->result_array();
		$row = $this->normalize_receipt_totals($row, TRUE);
		return $row;
	}

	/**
	 * Ensure items_total / grand_total are populated (backfill from lines if missing).
	 */
	public function normalize_receipt_totals($row, $persist = FALSE)
	{
		if (!is_array($row) || empty($row['id'])) {
			return $row;
		}
		$items_total = isset($row['items_total']) ? (float)$row['items_total'] : 0.0;
		$grand_total = isset($row['grand_total']) ? (float)$row['grand_total'] : 0.0;
		$t_pct = isset($row['transport_percent']) ? (float)$row['transport_percent'] : 0.0;
		$t_amt = isset($row['transport_amount']) ? (float)$row['transport_amount'] : 0.0;
		$t_effect = round($t_amt + ($t_amt * $t_pct / 100), 2);

		if ($items_total <= 0) {
			$calc = 0.0;
			if (!empty($row['items']) && is_array($row['items'])) {
				foreach ($row['items'] as $it) {
					$calc += ((float)$it['receive_qty']) * ((float)$it['rate']);
				}
			} else {
				$sum = $this->db
					->select('SUM(receive_qty * rate) AS items_total', FALSE)
					->from('erp_purchase_receipt_items')
					->where('receipt_id', (int)$row['id'])
					->get()
					->row_array();
				$calc = isset($sum['items_total']) ? (float)$sum['items_total'] : 0.0;
			}
			$items_total = round($calc, 2);
		}

		if ($grand_total <= 0) {
			$grand_total = round($items_total + $t_effect, 2);
		}

		$row['items_total'] = $items_total;
		$row['grand_total'] = $grand_total;
		$row['transport_effect'] = $t_effect;

		if ($persist && $items_total > 0) {
			$this->db->where('id', (int)$row['id'])->update('erp_purchase_receipts', array(
				'items_total' => $items_total,
				'grand_total' => $grand_total
			));
		}

		return $row;
	}

	public function create_purchase_receipt($header, $items, $owner_vendor_id, $post_stock = TRUE)
	{
		$this->db->trans_begin();
		$this->db->insert('erp_purchase_receipts', $header);
		$receipt_id = (int)$this->db->insert_id();
		if ($receipt_id <= 0) {
			$this->db->trans_rollback();
			return 0;
		}
		foreach ($items as $item) {
			$item['receipt_id'] = $receipt_id;
			$this->db->insert('erp_purchase_receipt_items', $item);
			if ($post_stock && !empty($item['product_id']) && (float)$item['receive_qty'] > 0) {
				$this->add_stock_qty(
					(int)$item['product_id'],
					(float)$item['receive_qty'],
					(int)$owner_vendor_id,
					'purchase_receipt',
					'PR:' . $header['pr_no'] . ':' . $item['product_id'] . ':' . microtime(TRUE),
					'Purchase receipt ' . $header['pr_no']
				);
			}
		}
		$this->refresh_po_receive_status((int)$header['po_id'], (int)$owner_vendor_id);
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return 0;
		}
		$this->db->trans_commit();
		return $receipt_id;
	}

	public function get_main_admin_stock_location_id()
	{
		if (!$this->db->table_exists('inventory_locations')) {
			return 0;
		}
		$row = $this->db
			->select('id')
			->from('inventory_locations')
			->where('location_type', 'admin')
			->where('location_ref_id', 0)
			->limit(1)
			->get()
			->row_array();
		if (!empty($row['id'])) {
			return (int)$row['id'];
		}
		$this->db->insert('inventory_locations', array(
			'location_type' => 'admin',
			'location_ref_id' => 0,
			'name' => 'Main Admin Stock',
			'is_active' => 1
		));
		return (int)$this->db->insert_id();
	}

	public function add_stock_qty($product_id, $qty, $owner_vendor_id, $movement_type, $external_ref, $remarks)
	{
		if (!$this->db->table_exists('inventory_stock_snapshot') || !$this->db->table_exists('inventory_stock_movements')) {
			return FALSE;
		}
		$location_id = $this->get_main_admin_stock_location_id();
		if ($location_id <= 0 || $product_id <= 0 || $qty <= 0) {
			return FALSE;
		}
		$item_type = 'book';
		$variation_key = 'default';
		$school_id = 0;
		$branch_id = 0;

		$existing = $this->db
			->where('location_id', $location_id)
			->where('item_type', $item_type)
			->where('item_ref_id', (int)$product_id)
			->where('variation_key', $variation_key)
			->where('school_id', $school_id)
			->where('branch_id', $branch_id)
			->limit(1)
			->get('inventory_stock_snapshot')
			->row_array();
		$before = $existing ? (float)$existing['qty_available'] : 0.0;
		$after = $before + (float)$qty;

		if (!empty($existing['id'])) {
			$this->db->where('id', (int)$existing['id'])->update('inventory_stock_snapshot', array('qty_available' => $after));
		} else {
			$this->db->insert('inventory_stock_snapshot', array(
				'location_id' => $location_id,
				'item_type' => $item_type,
				'item_ref_id' => (int)$product_id,
				'variation_key' => $variation_key,
				'school_id' => $school_id,
				'branch_id' => $branch_id,
				'qty_available' => $after
			));
		}

		$this->db->insert('inventory_stock_movements', array(
			'movement_type' => $movement_type,
			'external_ref' => substr((string)$external_ref, 0, 120),
			'location_id' => $location_id,
			'item_type' => $item_type,
			'item_ref_id' => (int)$product_id,
			'variation_key' => $variation_key,
			'school_id' => $school_id,
			'branch_id' => $branch_id,
			'qty_delta' => (float)$qty,
			'qty_before' => $before,
			'qty_after' => $after,
			'actor_type' => 'vendor_admin',
			'actor_id' => (int)$owner_vendor_id,
			'remarks' => $remarks
		));
		return TRUE;
	}
}
