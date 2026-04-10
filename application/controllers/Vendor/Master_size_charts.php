<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH . 'controllers/Vendor/Vendor_base.php');

class Master_size_charts extends Vendor_base
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Master_size_chart_model');
		$this->load->library('form_validation');
		$this->load->library('upload');
		$this->enforceUniformFeatureAccess();
	}

	private function enforceUniformFeatureAccess()
	{
		if ($this->checkFeatureAccess('uniforms') || $this->checkFeatureAccess('uniform')) {
			return;
		}
		$enabled_features = $this->getEnabledFeatures();
		if (!empty($enabled_features) && is_array($enabled_features)) {
			foreach ($enabled_features as $feature) {
				$slug = isset($feature['slug']) ? strtolower(trim((string) $feature['slug'])) : '';
				$name = isset($feature['name']) ? strtolower(trim((string) $feature['name'])) : '';
				if ($slug === 'uniforms' || $slug === 'uniform' || strpos($name, 'uniform') !== false) {
					return;
				}
			}
		}
	}

	private function unlinkStoredImage($relative_path)
	{
		$relative_path = ltrim(trim((string) $relative_path), '/');
		if ($relative_path === '') {
			return;
		}
		$this->config->load('upload');
		$uploadCfg = $this->config->item('master_size_chart_upload');
		$vendor_folder = get_vendor_domain_folder();
		if (empty($uploadCfg['base_root']) || empty($vendor_folder)) {
			return;
		}
		$abs = rtrim($uploadCfg['base_root'], '/') . '/' . $vendor_folder . '/' . $relative_path;
		if (is_file($abs)) {
			@unlink($abs);
		}
	}

	/**
	 * @return int Number of successfully uploaded images
	 */
	private function uploadImagesForChart($master_chart_id)
	{
		if (empty($_FILES['images']['name']) || !is_array($_FILES['images']['name'])) {
			return 0;
		}
		$files = $_FILES['images'];
		if (empty($files['name'][0])) {
			return 0;
		}

		$this->config->load('upload');
		$uploadCfg = $this->config->item('master_size_chart_upload');
		$vendor_folder = get_vendor_domain_folder();
		if (empty($uploadCfg) || empty($vendor_folder) || empty($uploadCfg['base_root'])) {
			return 0;
		}

		$date_folder = date('Y_m_d');
		$upload_path =
			rtrim($uploadCfg['base_root'], '/') . '/'
			. $vendor_folder . '/'
			. trim($uploadCfg['relative_dir'], '/') . '/'
			. $date_folder . '/';

		if (!is_dir($upload_path)) {
			mkdir($upload_path, 0775, true);
		}

		$start_order = $this->Master_size_chart_model->getMaxSortOrder($master_chart_id) + 1;
		$uploaded = 0;
		$count = count($files['name']);

		for ($index = 0; $index < $count; $index++) {
			if (empty($files['name'][$index]) || (int) $files['error'][$index] !== 0) {
				continue;
			}
			$ext = strtolower(pathinfo($files['name'][$index], PATHINFO_EXTENSION));
			if (!in_array($ext, $uploadCfg['allowed_types'], true)) {
				continue;
			}

			$_FILES['msc_single'] = array(
				'name' => $files['name'][$index],
				'type' => $files['type'][$index],
				'tmp_name' => $files['tmp_name'][$index],
				'error' => $files['error'][$index],
				'size' => $files['size'][$index],
			);

			$config = array(
				'upload_path' => $upload_path,
				'allowed_types' => implode('|', $uploadCfg['allowed_types']),
				'max_size' => $uploadCfg['max_size'],
				'file_name' => 'msc_' . $master_chart_id . '_' . uniqid() . '_' . $index . '.' . $ext,
				'overwrite' => false,
			);

			$this->upload->initialize($config);
			if ($this->upload->do_upload('msc_single')) {
				$data = $this->upload->data();
				$relative = trim($uploadCfg['relative_dir'], '/') . '/' . $date_folder . '/' . $data['file_name'];
				$this->Master_size_chart_model->addImage($master_chart_id, $relative, $start_order + $uploaded);
				$uploaded++;
			}
		}

		return $uploaded;
	}

	public function index()
	{
		$filters = array();
		$filters['status'] = $this->input->get('status') ? $this->input->get('status') : 'active';
		if ($this->input->get('search')) {
			$filters['search'] = $this->input->get('search');
		}

		$limit = 20;
		$page = (int) $this->input->get('page');
		if ($page < 1) {
			$page = 1;
		}
		$offset = ($page - 1) * $limit;

		$charts = $this->Master_size_chart_model->getChartsByVendor($this->current_vendor['id'], $filters, $limit, $offset);
		foreach ($charts as &$c) {
			$c['image_count'] = $this->Master_size_chart_model->countImages($c['id']);
		}
		unset($c);
		$data['charts'] = $charts;
		$data['total_count'] = $this->Master_size_chart_model->countChartsByVendor($this->current_vendor['id'], $filters);
		$data['filters'] = $filters;
		$data['current_page'] = $page;
		$data['total_pages'] = (int) ceil(($data['total_count'] ?: 0) / $limit);

		$this->load->library('pagination');
		$config['base_url'] = base_url('master-size-charts');
		$config['total_rows'] = $data['total_count'];
		$config['per_page'] = $limit;
		$config['page_query_string'] = TRUE;
		$config['query_string_segment'] = 'page';
		$config['full_tag_open'] = '<nav aria-label="Master size charts pagination"><ul class="pagination pagination-sm mb-0 justify-content-end">';
		$config['full_tag_close'] = '</ul></nav>';
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['first_tag_open'] = '<li class="page-item">';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li class="page-item">';
		$config['last_tag_close'] = '</li>';
		$config['next_link'] = 'Next';
		$config['next_tag_open'] = '<li class="page-item">';
		$config['next_tag_close'] = '</li>';
		$config['prev_link'] = 'Prev';
		$config['prev_tag_open'] = '<li class="page-item">';
		$config['prev_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
		$config['cur_tag_close'] = '</span></li>';
		$config['num_tag_open'] = '<li class="page-item">';
		$config['num_tag_close'] = '</li>';
		$config['attributes'] = array('class' => 'page-link');
		$config['reuse_query_string'] = TRUE;
		$config['use_page_numbers'] = TRUE;

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();

		$data['title'] = 'Master Size Charts';
		$data['page_title'] = 'Master Size Charts';
		$data['active_menu'] = 'master-size-charts';
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $this->getVendorDomainForUrl();
		$data['content'] = $this->load->view('vendor/master_size_charts/index', $data, TRUE);
		$this->load->view('vendor/layouts/index_template', $data);
	}

	public function add()
	{
		if ($this->input->post()) {
			$this->form_validation->set_rules('chart_name', 'Name', 'required|trim|max_length[255]');
			$this->form_validation->set_rules('status', 'Status', 'required|in_list[active,inactive]');

			if ($this->form_validation->run() === TRUE) {
				$chart_id = $this->Master_size_chart_model->createChart(array(
					'vendor_id' => $this->current_vendor['id'],
					'name' => $this->input->post('chart_name'),
					'status' => $this->input->post('status'),
				));

				if ($chart_id) {
					$n = $this->uploadImagesForChart($chart_id);
					$this->session->set_flashdata('success', 'Master size chart created' . ($n ? ' with ' . $n . ' image(s).' : '.'));
					redirect('master-size-charts');
					return;
				}
				$this->session->set_flashdata('error', 'Failed to create master size chart.');
			}
		}

		$data['title'] = 'Add Master Size Chart';
		$data['page_title'] = 'Add Master Size Chart';
		$data['active_menu'] = 'master-size-charts';
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $this->getVendorDomainForUrl();
		$data['content'] = $this->load->view('vendor/master_size_charts/add', $data, TRUE);
		$this->load->view('vendor/layouts/index_template', $data);
	}

	public function edit($id)
	{
		$chart = $this->Master_size_chart_model->getChartById($id, $this->current_vendor['id']);
		if (empty($chart)) {
			show_404();
		}

		if ($this->input->post()) {
			$this->form_validation->set_rules('chart_name', 'Name', 'required|trim|max_length[255]');
			$this->form_validation->set_rules('status', 'Status', 'required|in_list[active,inactive]');

			if ($this->form_validation->run() === TRUE) {
				$this->Master_size_chart_model->updateChart($id, $this->current_vendor['id'], array(
					'name' => $this->input->post('chart_name'),
					'status' => $this->input->post('status'),
				));

				$remove_ids = $this->input->post('remove_image_ids');
				if (is_array($remove_ids)) {
					foreach ($remove_ids as $rid) {
						$rid = (int) $rid;
						if ($rid <= 0) {
							continue;
						}
						$deleted = $this->Master_size_chart_model->deleteImageRow($rid, $this->current_vendor['id']);
						if (is_array($deleted) && !empty($deleted['image_path'])) {
							$this->unlinkStoredImage($deleted['image_path']);
						}
					}
				}

				$n = $this->uploadImagesForChart($id);
				$this->session->set_flashdata('success', 'Master size chart updated' . ($n ? ' (' . $n . ' new image(s)).' : '.'));
				redirect('master-size-charts');
				return;
			}
		}

		$data['chart'] = $chart;
		$data['images'] = $this->Master_size_chart_model->getImagesByChartId($id);
		$data['title'] = 'Edit Master Size Chart';
		$data['page_title'] = 'Edit Master Size Chart';
		$data['active_menu'] = 'master-size-charts';
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $this->getVendorDomainForUrl();
		$data['content'] = $this->load->view('vendor/master_size_charts/edit', $data, TRUE);
		$this->load->view('vendor/layouts/index_template', $data);
	}

	public function delete($id)
	{
		if ($this->Master_size_chart_model->softDeleteChart((int) $id, (int) $this->current_vendor['id'])) {
			$this->session->set_flashdata('success', 'Master size chart inactivated.');
		} else {
			$this->session->set_flashdata('error', 'Failed to update master size chart.');
		}
		redirect('master-size-charts');
	}

	public function delete_image($image_id)
	{
		$deleted = $this->Master_size_chart_model->deleteImageRow((int) $image_id, $this->current_vendor['id']);
		if (is_array($deleted) && !empty($deleted['image_path'])) {
			$this->unlinkStoredImage($deleted['image_path']);
			$this->session->set_flashdata('success', 'Image removed.');
		} else {
			$this->session->set_flashdata('error', 'Image not found.');
		}
		$ref = $this->input->server('HTTP_REFERER');
		if (!empty($ref)) {
			redirect($ref);
			return;
		}
		redirect('master-size-charts');
	}
}
