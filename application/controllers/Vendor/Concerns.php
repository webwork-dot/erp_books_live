<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Vendor User Concerns Controller
 *
 * Displays and manages customer concerns from tbl_user_concerns (frontend)
 * Linked via user_id to users table for customer info
 *
 * @package		ERP
 * @subpackage	Controllers
 * @category	Vendor
 */
require_once(APPPATH . 'controllers/Vendor/Vendor_base.php');
class Concerns extends Vendor_base
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('User_concerns_model');
	}

	/**
	 * List concerns with customer information
	 */
	public function index()
	{
		if (!$this->User_concerns_model->table_exists()) {
			show_error('User concerns table (tbl_user_concerns) is not available.', 404);
		}

		$filters = array();
		if ($this->input->get('status')) {
			$filters['status'] = $this->input->get('status');
		}
		if ($this->input->get('concern_type')) {
			$filters['concern_type'] = $this->input->get('concern_type');
		}
		if ($this->input->get('search')) {
			$filters['search'] = trim($this->input->get('search'));
		}

		$per_page = 20;
		$page = $this->input->get('page') ? (int) $this->input->get('page') : 1;
		$offset = ($page - 1) * $per_page;

		$concerns = $this->User_concerns_model->get_concerns($filters, $per_page, $offset);
		$total = $this->User_concerns_model->get_total_concerns($filters);
		$total_pages = ceil($total / $per_page);

		$data['title'] = 'User Concerns - ' . $this->current_vendor['name'];
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $this->getVendorDomainForUrl();
		$data['concerns'] = $concerns;
		$data['filters'] = $filters;
		$data['total'] = $total;
		$data['per_page'] = $per_page;
		$data['current_page'] = $page;
		$data['total_pages'] = $total_pages;
		$data['breadcrumb'] = array(
			array('label' => 'Dashboard', 'url' => base_url('dashboard')),
			array('label' => 'User Concerns', 'active' => true)
		);
		$data['content'] = $this->load->view('vendor/concerns/list', $data, TRUE);

		$this->load->view('vendor/layouts/index_template', $data);
	}

	/**
	 * Get concern details (AJAX) for modal
	 */
	public function get_concern($id)
	{
		$concern = $this->User_concerns_model->get_concern_by_id($id);
		if (!$concern) {
			$this->output->set_content_type('application/json')->set_output(json_encode(array('success' => false, 'message' => 'Not found')));
			return;
		}
		$this->output->set_content_type('application/json')->set_output(json_encode(array('success' => true, 'data' => $concern)));
	}

	/**
	 * Update concern status (AJAX)
	 */
	public function update_status()
	{
		$id = $this->input->post('id');
		$status = $this->input->post('status');
		if (!$id || !$status) {
			$this->output->set_content_type('application/json')->set_output(json_encode(array('success' => false, 'message' => 'Invalid request')));
			return;
		}
		$ok = $this->User_concerns_model->update_status($id, $status);
		$this->output->set_content_type('application/json')->set_output(json_encode(array(
			'success' => $ok,
			'message' => $ok ? 'Status updated' : 'Update failed'
		)));
	}

	/**
	 * Update admin response (AJAX)
	 */
	public function update_admin_response()
	{
		$id = $this->input->post('id');
		$admin_response = $this->input->post('admin_response');
		if (!$id) {
			$this->output->set_content_type('application/json')->set_output(json_encode(array('success' => false, 'message' => 'Invalid request')));
			return;
		}
		$ok = $this->User_concerns_model->update_admin_response($id, $admin_response);
		$this->output->set_content_type('application/json')->set_output(json_encode(array(
			'success' => $ok,
			'message' => $ok ? 'Response saved' : 'Update failed'
		)));
	}
}
