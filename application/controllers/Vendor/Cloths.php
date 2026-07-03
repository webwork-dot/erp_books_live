<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH . 'controllers/Vendor/Vendor_base.php');

class Cloths extends Vendor_base
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Cloth_model');
		$this->load->model('Product_model');
		$this->load->model('Uniform_model');
		$this->load->library('form_validation');
		$this->load->library('upload');
	}

	public function index()
	{
		$filters = array();
		if ($this->input->get('status')) {
			$filters['status'] = $this->input->get('status');
		}
		if ($this->input->get('search')) {
			$filters['search'] = $this->input->get('search');
		}
		if ($this->input->get('cloth_type_id')) {
			$filters['cloth_type_id'] = $this->input->get('cloth_type_id');
		}
		if ($this->input->get('material_id')) {
			$filters['material_id'] = $this->input->get('material_id');
		}
		if ($this->input->get('gender')) {
			$filters['gender'] = $this->input->get('gender');
		}

		$per_page = 10;
		$page = max(1, (int) $this->input->get('page'));
		$offset = ($page - 1) * $per_page;

		$total = $this->Cloth_model->getTotalClothsByVendor($this->current_vendor['id'], $filters);
		$cloths = $this->Cloth_model->getClothsByVendor($this->current_vendor['id'], $filters, $per_page, $offset);

		$stock_map = array();
		if ($this->db->table_exists('inventory_stock_snapshot') && !empty($cloths)) {
			$location_row = $this->db->select('id')->from('inventory_locations')
				->where('location_type', 'admin')->where('location_ref_id', 0)->limit(1)->get()->row_array();
			if (!empty($location_row['id'])) {
				$ids = array_map('intval', array_column($cloths, 'id'));
				$stock_rows = $this->db->select('item_ref_id, SUM(qty_available) AS total_qty')
					->from('inventory_stock_snapshot')
					->where('location_id', (int) $location_row['id'])
					->where('item_type', 'cloths')
					->where_in('item_ref_id', $ids)
					->group_by('item_ref_id')->get()->result_array();
				foreach ($stock_rows as $s) {
					$stock_map[(int) $s['item_ref_id']] = (float) $s['total_qty'];
				}
			}
		}

		foreach ($cloths as &$cloth) {
			$cloth['current_stock_qty'] = isset($stock_map[(int) $cloth['id']]) ? $stock_map[(int) $cloth['id']] : 0.0;
			$main = $this->Product_model->get_main_product_image($cloth['id'], $this->current_vendor['id']);
			$cloth['thumbnail'] = $main ? $main['image_path'] : NULL;

			$size_prices = $this->Cloth_model->getClothSizePrices($cloth['id']);
			$cloth['size_prices'] = $size_prices;
			$mrp_vals = $sell_vals = array();
			foreach ($size_prices as $sp) {
				if (!empty($sp['mrp'])) {
					$mrp_vals[] = (float) $sp['mrp'];
				}
				if (!empty($sp['selling_price'])) {
					$sell_vals[] = (float) $sp['selling_price'];
				}
			}
			$cloth['min_mrp'] = $mrp_vals ? min($mrp_vals) : NULL;
			$cloth['max_mrp'] = $mrp_vals ? max($mrp_vals) : NULL;
			$cloth['min_selling_price'] = $sell_vals ? min($sell_vals) : NULL;
			$cloth['max_selling_price'] = $sell_vals ? max($sell_vals) : NULL;
			$cloth['status_label'] = ((int) $cloth['status'] === 1) ? 'active' : 'inactive';
		}
		unset($cloth);

		$data['cloths'] = $cloths;
		$data['total_cloths'] = $total;
		$data['per_page'] = $per_page;
		$data['current_page'] = $page;
		$data['total_pages'] = max(1, (int) ceil($total / $per_page));
		$data['cloth_types'] = $this->Cloth_model->getAllClothTypes();
		$data['materials'] = $this->Cloth_model->getAllMaterials();
		$data['title'] = 'Manage Cloths';
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $this->getVendorDomainForUrl();
		$data['filters'] = $filters;
		$data['breadcrumb'] = array(
			array('label' => 'Dashboard', 'url' => base_url($this->current_vendor['domain'] . '/dashboard')),
			array('label' => 'Cloths', 'active' => true),
		);
		$data['content'] = $this->load->view('vendor/cloths/index', $data, TRUE);
		$this->load->view('vendor/layouts/index_template', $data);
	}

	public function add()
	{
		$this->form_validation->set_rules('cloth_type_id', 'Cloth Type', 'required|integer');
		$this->form_validation->set_rules('product_name', 'Product Name', 'required|trim');
		$this->form_validation->set_rules('min_quantity', 'Min Quantity', 'required|integer|greater_than[0]');
		$this->form_validation->set_rules('material_id', 'Material', 'integer');
		$this->form_validation->set_rules('gst_percentage', 'GST (%)', 'required|numeric');

		if ($this->form_validation->run() === FALSE) {
			$this->loadFormView();
			return;
		}

		$cloth_data = $this->clothDataFromPost();
		$product_id = $this->Cloth_model->createCloth($cloth_data);

		if (!$product_id) {
			$this->session->set_flashdata('error', 'Failed to create cloth.');
			redirect(base_url('products/cloths/add'));
			return;
		}

		$slug = slugify($cloth_data['product_name']) . '-' . $product_id;
		$this->Cloth_model->updateCloth($product_id, array('slug' => $slug));
		$this->handleImageUploads($product_id);
		$this->savePostedSizePrices($product_id, $cloth_data['size_chart_id']);

		$this->session->set_flashdata('success', 'Cloth created successfully.');
		redirect(base_url('products/cloths'));
	}

	public function edit($product_id)
	{
		$cloth = $this->Cloth_model->getClothById($product_id, $this->current_vendor['id']);
		if (!$cloth) {
			show_404();
		}

		$this->form_validation->set_rules('cloth_type_id', 'Cloth Type', 'required|integer');
		$this->form_validation->set_rules('product_name', 'Product Name', 'required|trim');
		$this->form_validation->set_rules('min_quantity', 'Min Quantity', 'required|integer|greater_than[0]');
		$this->form_validation->set_rules('material_id', 'Material', 'integer');
		$this->form_validation->set_rules('gst_percentage', 'GST (%)', 'required|numeric');

		if ($this->form_validation->run() === FALSE) {
			$this->loadFormView($cloth);
			return;
		}

		$cloth_data = $this->clothDataFromPost();
		$cloth_data['slug'] = slugify($cloth_data['product_name']) . '-' . $product_id;
		$this->Cloth_model->updateCloth($product_id, $cloth_data);
		$this->handleClothImageUpdates($product_id);
		$this->handleImageUploads($product_id);
		$this->savePostedSizePrices($product_id, $cloth_data['size_chart_id']);

		$this->session->set_flashdata('success', 'Cloth updated successfully.');
		redirect(base_url('products/cloths'));
	}

	public function delete($product_id)
	{
		$cloth = $this->Cloth_model->getClothById($product_id, $this->current_vendor['id']);
		if (!$cloth) {
			show_404();
		}
		$this->Cloth_model->deleteCloth($product_id);
		$this->session->set_flashdata('success', 'Cloth deleted successfully.');
		redirect(base_url('products/cloths'));
	}

	public function get_sizes()
	{
		header('Content-Type: application/json');
		$size_chart_id = $this->input->post('size_chart_id') ?: $this->input->get('size_chart_id');
		if (empty($size_chart_id)) {
			echo json_encode(array('status' => 'error', 'message' => 'Size Chart ID is required'));
			return;
		}
		$charts = $this->Cloth_model->getSizeChartsByVendor($this->current_vendor['id']);
		$valid = FALSE;
		foreach ($charts as $c) {
			if ((int) $c['id'] === (int) $size_chart_id) {
				$valid = TRUE;
				break;
			}
		}
		if (!$valid) {
			echo json_encode(array('status' => 'error', 'message' => 'Invalid size chart'));
			return;
		}
		echo json_encode(array('status' => 'success', 'sizes' => $this->Cloth_model->getSizesBySizeChart($size_chart_id)));
	}

	public function add_cloth_type()
	{
		header('Content-Type: application/json');
		if ($this->input->method() !== 'post') {
			show_404();
		}
		$name = trim((string) $this->input->post('name'));
		if ($name === '') {
			echo json_encode(array('status' => 'error', 'message' => 'Name is required'));
			return;
		}
		$id = $this->Cloth_model->createClothType(array('name' => $name, 'status' => 'active'));
		if ($id) {
			echo json_encode(array('status' => 'success', 'id' => $id, 'name' => $name));
		} else {
			echo json_encode(array('status' => 'error', 'message' => 'Failed to add cloth type'));
		}
	}

	public function add_material()
	{
		header('Content-Type: application/json');
		if ($this->input->method() !== 'post') {
			show_404();
		}
		$name = trim((string) $this->input->post('name'));
		if ($name === '') {
			echo json_encode(array('status' => 'error', 'message' => 'Name is required'));
			return;
		}
		$id = $this->Cloth_model->createMaterial(array('name' => $name, 'status' => 'active'));
		if ($id) {
			echo json_encode(array('status' => 'success', 'id' => $id, 'name' => $name));
		} else {
			echo json_encode(array('status' => 'error', 'message' => 'Failed to add material'));
		}
	}

	public function add_color()
	{
		header('Content-Type: application/json');
		if ($this->input->method() !== 'post') {
			show_404();
		}
		$name = trim((string) $this->input->post('name'));
		if ($name === '') {
			echo json_encode(array('status' => 'error', 'message' => 'Color name is required'));
			return;
		}
		$id = $this->Cloth_model->createColor(array(
			'vendor_id' => (int) $this->current_vendor['id'],
			'name' => $name,
			'color_code' => $this->input->post('color_code'),
			'description' => $this->input->post('description'),
			'status' => 'active',
		));
		if ($id) {
			echo json_encode(array('status' => 'success', 'id' => $id, 'name' => $name));
		} else {
			echo json_encode(array('status' => 'error', 'message' => 'Failed to add color'));
		}
	}

	public function delete_image($image_id)
	{
		header('Content-Type: application/json');
		$image = $this->db->where('id', (int) $image_id)->get('erp_product_images')->row_array();
		if (!$image || (int) $image['vendor_id'] !== (int) $this->current_vendor['id']) {
			echo json_encode(array('status' => 'error', 'message' => 'Permission denied'));
			return;
		}
		$cloth = $this->Cloth_model->getClothById($image['product_id'], $this->current_vendor['id']);
		if (!$cloth) {
			echo json_encode(array('status' => 'error', 'message' => 'Permission denied'));
			return;
		}
		$this->config->load('upload');
		$uploadCfg = $this->config->item('cloth_upload');
		$vendor_folder = get_vendor_domain_folder();
		if ($vendor_folder && !empty($uploadCfg['base_root'])) {
			$file = rtrim($uploadCfg['base_root'], '/') . '/' . $vendor_folder . '/' . ltrim($image['image_path'], '/');
			if (is_file($file)) {
				@unlink($file);
			}
		}
		$this->db->where('id', (int) $image_id)->delete('erp_product_images');
		echo json_encode(array('status' => 'success', 'message' => 'Image deleted successfully'));
	}

	public function toggle_status($product_id)
	{
		header('Content-Type: application/json');
		$cloth = $this->Cloth_model->getClothById($product_id, $this->current_vendor['id']);
		if (!$cloth) {
			echo json_encode(array('status' => 'error', 'message' => 'Cloth not found'));
			return;
		}
		$new_status = $this->input->post('status');
		if (!in_array($new_status, array('active', 'inactive'), TRUE)) {
			echo json_encode(array('status' => 'error', 'message' => 'Invalid status'));
			return;
		}
		$this->Cloth_model->updateCloth($product_id, array(
			'status' => $this->Product_model->normalize_product_status($new_status),
		));
		echo json_encode(array('status' => 'success', 'message' => 'Status updated', 'new_status' => $new_status));
	}

	public function duplicate_cloth()
	{
		$source_id = (int) $this->input->post('cloth_id');
		$source = $this->Cloth_model->getClothById($source_id, $this->current_vendor['id']);
		if (!$source) {
			$this->session->set_flashdata('error', 'Cloth not found.');
			redirect(base_url('products/cloths'));
			return;
		}

		$this->config->load('upload');
		$uploadCfg = $this->config->item('cloth_upload');
		$vendor_folder = get_vendor_domain_folder();

		$this->db->trans_begin();
		$new_data = $source;
		unset($new_data['id'], $new_data['cloth_type_name'], $new_data['material_name'], $new_data['size_chart_name'], $new_data['master_size_chart_name'], $new_data['status_label']);
		$new_data['product_name'] = trim($source['product_name']) . ' copy';
		$new_data['slug'] = slugify($new_data['product_name']) . '-tmp-' . time();
		$new_data['created_at'] = date('Y-m-d H:i:s');
		$new_data['updated_at'] = date('Y-m-d H:i:s');

		$new_id = $this->Cloth_model->createCloth($new_data);
		if (!$new_id) {
			$this->db->trans_rollback();
			$this->session->set_flashdata('error', 'Failed to duplicate cloth.');
			redirect(base_url('products/cloths'));
			return;
		}

		$this->Cloth_model->updateCloth($new_id, array('slug' => slugify($new_data['product_name']) . '-' . $new_id));

		$prices = $this->Cloth_model->getClothSizePrices($source_id);
		if (!empty($prices)) {
			$map = array();
			foreach ($prices as $p) {
				$map[0][$p['size_id']] = array('size_id' => $p['size_id'], 'mrp' => $p['mrp'], 'selling_price' => $p['selling_price']);
			}
			$this->Cloth_model->saveClothSizePrices($new_id, $map);
		}

		$images = $this->Product_model->get_product_images($source_id, $this->current_vendor['id']);
		foreach ($images as $img) {
			$new_path = $img['image_path'];
			if ($vendor_folder && !empty($uploadCfg['base_root'])) {
				$src = rtrim($uploadCfg['base_root'], '/') . '/' . $vendor_folder . '/' . ltrim($img['image_path'], '/');
				if (is_file($src)) {
					$date_folder = date('Y_m_d');
					$dest_dir = rtrim($uploadCfg['base_root'], '/') . '/' . $vendor_folder . '/' . trim($uploadCfg['relative_dir'], '/') . '/' . $date_folder . '/';
					if (!is_dir($dest_dir)) {
						mkdir($dest_dir, 0775, TRUE);
					}
					$basename = 'cloth_' . $new_id . '_' . uniqid() . '.' . pathinfo($src, PATHINFO_EXTENSION);
					$dest = $dest_dir . $basename;
					if (@copy($src, $dest)) {
						$new_path = 'uploads/cloths/images/' . $date_folder . '/' . $basename;
					}
				}
			}
			$this->Product_model->add_image_reference(array(
				'product_id' => $new_id,
				'image_path' => $new_path,
				'image_order' => (int) $img['image_order'],
				'is_main' => (int) $img['is_main'],
				'vendor_id' => (int) $this->current_vendor['id'],
			));
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$this->session->set_flashdata('error', 'Failed to duplicate cloth.');
		} else {
			$this->db->trans_commit();
			$this->session->set_flashdata('success', 'Cloth duplicated successfully.');
		}
		redirect(base_url('products/cloths'));
	}

	protected function loadFormView($cloth = NULL)
	{
		$data['cloth_types'] = $this->Cloth_model->getAllClothTypes();
		$data['materials'] = $this->Cloth_model->getAllMaterials();
		$data['size_charts'] = $this->Cloth_model->getSizeChartsByVendor($this->current_vendor['id']);
		$include_msc = $cloth && !empty($cloth['master_size_chart_id']) ? (int) $cloth['master_size_chart_id'] : NULL;
		$data['master_size_charts'] = $this->Cloth_model->getMasterSizeChartsByVendor($this->current_vendor['id'], $include_msc);
		$data['colors'] = $this->Cloth_model->getColorsByVendor($this->current_vendor['id']);
		if ($cloth) {
			$data['cloth'] = $cloth;
			$data['cloth_images'] = $this->Product_model->get_product_images($cloth['id'], $this->current_vendor['id']);
			$data['size_prices'] = $this->Cloth_model->getClothSizePrices($cloth['id']);
			$data['title'] = 'Edit Cloth';
			$data['breadcrumb'] = array(
				array('label' => 'Cloths', 'url' => base_url('products/cloths')),
				array('label' => 'Edit', 'active' => TRUE),
			);
		} else {
			$data['title'] = 'Add New Cloth';
			$data['breadcrumb'] = array(
				array('label' => 'Cloths', 'url' => base_url('products/cloths')),
				array('label' => 'Add', 'active' => TRUE),
			);
		}
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $this->getVendorDomainForUrl();
		$data['content'] = $this->load->view('vendor/cloths/add', $data, TRUE);
		$this->load->view('vendor/layouts/index_template', $data);
	}

	protected function clothDataFromPost()
	{
		$status = $this->input->post('status') === 'inactive' ? 'inactive' : 'active';
		$gender = $this->input->post('gender');
		$tags = $this->input->post('cloth_tag');

		return array(
			'vendor_id' => (int) $this->current_vendor['id'],
			'type' => 'cloths',
			'cloth_type_id' => (int) $this->input->post('cloth_type_id'),
			'gender' => $gender ? implode(',', array_intersect((array) $gender, array('male', 'female', 'unisex'))) : NULL,
			'color_id' => $this->input->post('color_id') ? (int) $this->input->post('color_id') : NULL,
			'product_name' => $this->input->post('product_name'),
			'isbn' => $this->input->post('isbn'),
			'sku' => $this->input->post('isbn'),
			'min_quantity' => (int) $this->input->post('min_quantity'),
			'days_to_exchange' => $this->input->post('days_to_exchange') ? (int) $this->input->post('days_to_exchange') : NULL,
			'material_id' => $this->input->post('material_id') ? (int) $this->input->post('material_id') : NULL,
			'product_origin' => $this->input->post('product_origin'),
			'cloth_tag' => $tags ? implode(',', array_intersect((array) $tags, array('regular', 'PT'))) : 'regular',
			'description' => $this->input->post('product_description'),
			'manufacturer_details' => $this->input->post('manufacturer_details'),
			'packer_details' => $this->input->post('packer_details'),
			'customer_details' => $this->input->post('customer_details'),
			'pointers' => $this->input->post('pointers'),
			'size_chart_id' => $this->input->post('size_chart_id') ? (int) $this->input->post('size_chart_id') : NULL,
			'master_size_chart_id' => $this->validatedMasterSizeChartId($this->input->post('master_size_chart_id')),
			'length' => $this->input->post('packaging_length') !== '' ? (float) $this->input->post('packaging_length') : NULL,
			'width' => $this->input->post('packaging_width') !== '' ? (float) $this->input->post('packaging_width') : NULL,
			'height' => $this->input->post('packaging_height') !== '' ? (float) $this->input->post('packaging_height') : NULL,
			'weight' => $this->input->post('packaging_weight') !== '' ? (float) $this->input->post('packaging_weight') : NULL,
			'gst' => $this->input->post('gst_percentage'),
			'hsn' => $this->input->post('hsn'),
			'meta_title' => $this->input->post('meta_title'),
			'meta_keyword' => $this->input->post('meta_keywords'),
			'meta_description' => $this->input->post('meta_description'),
			'status' => $this->Product_model->normalize_product_status($status),
			'is_individual' => $this->input->post('is_individual') ? 1 : 0,
			'is_set' => $this->input->post('is_set') ? 1 : 0,
			'legacy_table' => NULL,
			'legacy_id' => NULL,
		);
	}

	protected function validatedMasterSizeChartId($raw)
	{
		if (!$this->db->table_exists('erp_master_size_charts') || !$this->db->field_exists('master_size_chart_id', 'erp_products')) {
			return NULL;
		}
		if ($raw === NULL || $raw === '') {
			return NULL;
		}
		$id = (int) $raw;
		if ($id <= 0) {
			return NULL;
		}
		$row = $this->db->select('id')->from('erp_master_size_charts')
			->where('id', $id)->where('vendor_id', (int) $this->current_vendor['id'])->get()->row_array();
		return $row ? $id : NULL;
	}

	protected function savePostedSizePrices($product_id, $size_chart_id)
	{
		$json = $this->input->post('size_prices_json');
		$size_prices = $json ? json_decode($json, TRUE) : $this->input->post('size_prices');
		if (!is_array($size_prices)) {
			$size_prices = array();
		}
		if (!empty($size_chart_id)) {
			$allowed = $this->Cloth_model->getSizesBySizeChart($size_chart_id);
			$allowed_ids = array();
			foreach ($allowed as $s) {
				$allowed_ids[(int) $s['id']] = TRUE;
			}
			$filtered = array();
			foreach ($size_prices as $class_id => $sizes_arr) {
				if (!is_array($sizes_arr)) {
					continue;
				}
				foreach ($sizes_arr as $size_id => $row) {
					if (!is_array($row)) {
						continue;
					}
					$sid = isset($row['size_id']) ? (int) $row['size_id'] : (int) $size_id;
					if ($sid > 0 && isset($allowed_ids[$sid])) {
						$filtered[0][$sid] = $row;
					}
				}
			}
			$size_prices = $filtered;
		} else {
			$size_prices = array();
		}
		$this->Cloth_model->saveClothSizePrices($product_id, $size_prices);
	}

	protected function handleImageUploads($product_id)
	{
		if (empty($_FILES['images']['name'][0])) {
			return;
		}
		$this->config->load('upload');
		$uploadCfg = $this->config->item('cloth_upload');
		$vendor_folder = get_vendor_domain_folder();
		if (empty($vendor_folder)) {
			return;
		}
		$date_folder = date('Y_m_d');
		$upload_path = rtrim($uploadCfg['base_root'], '/') . '/' . $vendor_folder . '/' . trim($uploadCfg['relative_dir'], '/') . '/' . $date_folder . '/';
		if (!is_dir($upload_path)) {
			mkdir($upload_path, 0775, TRUE);
		}

		$files = $_FILES['images'];
		$image_order = json_decode($this->input->post('image_order'), TRUE);
		$main_image_index = (int) $this->input->post('main_image_index');
		if (!is_array($image_order)) {
			$image_order = range(0, count($files['name']) - 1);
		}

		$existing = $this->Product_model->get_product_images($product_id, $this->current_vendor['id']);
		$start_order = 0;
		foreach ($existing as $ex) {
			$start_order = max($start_order, (int) $ex['image_order'] + 1);
		}

		$uploaded_ids = array();
		$uploaded_count = 0;
		foreach ($image_order as $order => $index) {
			if (!isset($files['error'][$index]) || $files['error'][$index] !== 0) {
				continue;
			}
			$ext = strtolower(pathinfo($files['name'][$index], PATHINFO_EXTENSION));
			if (!in_array($ext, $uploadCfg['allowed_types'], TRUE)) {
				continue;
			}
			$_FILES['image'] = array(
				'name' => $files['name'][$index],
				'type' => $files['type'][$index],
				'tmp_name' => $files['tmp_name'][$index],
				'error' => $files['error'][$index],
				'size' => $files['size'][$index],
			);
			$this->upload->initialize(array(
				'upload_path' => $upload_path,
				'allowed_types' => implode('|', $uploadCfg['allowed_types']),
				'max_size' => $uploadCfg['max_size'],
				'file_name' => 'cloth_' . $product_id . '_' . uniqid() . '.' . $ext,
				'overwrite' => FALSE,
			));
			if ($this->upload->do_upload('image')) {
				$data = $this->upload->data();
				$image_path = 'uploads/cloths/images/' . $date_folder . '/' . $data['file_name'];
				$image_id = $this->Product_model->add_image_reference(array(
					'product_id' => (int) $product_id,
					'image_path' => $image_path,
					'image_order' => $start_order + $uploaded_count,
					'is_main' => 0,
					'vendor_id' => (int) $this->current_vendor['id'],
				));
				if ($image_id) {
					$uploaded_ids[$order] = $image_id;
					$uploaded_count++;
				}
			}
		}

		if (isset($uploaded_ids[$main_image_index])) {
			$this->Product_model->set_main_image($product_id, $uploaded_ids[$main_image_index], $this->current_vendor['id']);
		} elseif ($uploaded_count > 0 && empty($existing)) {
			$first = reset($uploaded_ids);
			if ($first) {
				$this->Product_model->set_main_image($product_id, $first, $this->current_vendor['id']);
			}
		}
	}

	protected function handleClothImageUpdates($product_id)
	{
		$main_image_id = $this->input->post('main_image_id');
		$image_order = $this->input->post('image_order');
		$deleted_image_ids = $this->input->post('deleted_image_ids');
		$this->config->load('upload');
		$uploadCfg = $this->config->item('cloth_upload');
		$vendor_folder = get_vendor_domain_folder();

		if (!empty($deleted_image_ids)) {
			$deleted_ids = array_filter(array_map('trim', explode(',', $deleted_image_ids)));
			foreach ($deleted_ids as $img_id) {
				$img = $this->db->where('id', (int) $img_id)->where('product_id', (int) $product_id)->get('erp_product_images')->row_array();
				if ($img && $vendor_folder && !empty($uploadCfg['base_root'])) {
					$file = rtrim($uploadCfg['base_root'], '/') . '/' . $vendor_folder . '/' . ltrim($img['image_path'], '/');
					if (is_file($file)) {
						@unlink($file);
					}
				}
				$this->db->where('id', (int) $img_id)->where('product_id', (int) $product_id)->delete('erp_product_images');
			}
		}

		if (!empty($main_image_id) && is_numeric($main_image_id)) {
			$this->Product_model->set_main_image($product_id, (int) $main_image_id, $this->current_vendor['id']);
		}

		if (!empty($image_order) && empty($_FILES['images']['name'][0])) {
			$image_ids = array_filter(array_map('trim', explode(',', $image_order)));
			foreach ($image_ids as $order => $img_id) {
				if (is_numeric($img_id)) {
					$this->db->where('id', (int) $img_id)->where('product_id', (int) $product_id)
						->update('erp_product_images', array('image_order' => (int) $order));
				}
			}
		}
	}
}
