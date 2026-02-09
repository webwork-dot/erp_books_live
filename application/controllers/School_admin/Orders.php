<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends CI_Controller
{
	private $current_tenant = NULL;

	public function __construct()
	{
		parent::__construct();
		$this->load->library('Tenant');
		$this->load->model('Order_model');
		$this->load->library('pagination');

		$http_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
		if (strpos($http_host, ':') !== false) {
			$http_host = substr($http_host, 0, strpos($http_host, ':'));
		}
		$http_host = strtolower(trim($http_host));
		$domain = root_domain($http_host);
		$resolved = $this->tenant->resolveByDomain(($domain == "localhost") ? "shivambook.com" : $domain);
		if (!$resolved && !empty($http_host)) {
			$resolved = $this->tenant->resolveByDomain($http_host);
		}
		if ($resolved) {
			$this->current_tenant = $resolved;
			$current_db = isset($this->db->database) ? $this->db->database : NULL;
			if (!empty($resolved['database_name']) && $current_db !== $resolved['database_name']) {
				$this->tenant->switchDatabase($resolved);
			}
		}
	}

	public function index($status = 'all')
	{

		// echo json_encode($this->session->userdata()); exit();

		$filter_data['date_range'] = $this->input->get('date_range');
		$filter_data['keywords']  = $this->input->get('keywords');
		$filter_data['order_status']  = $status ?: 'all';
		$filter_data['school_user_id']  = $this->session->userdata('school_user_id') ?: '0';

		$total_count = $this->Order_model->get_paginated_orders_count(NULL, $filter_data);

		$page_data['total_count'] = $total_count;
		$page_data['order_status']  = $filter_data['order_status'];
		$page_data['order_counts'] = $this->Order_model->get_order_status_counts(NULL);

		$per_page = 10;
		$page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
		if ($page < 1) $page = 1;
		$offset = ($page - 1) * $per_page;

		$page_data['order_list'] = $this->Order_model->get_paginated_orders(NULL, $filter_data, $per_page, $offset);

		$pagination_config = array(
			'base_url' => base_url('school-admin/orders/' . $status),
			'total_rows' => $total_count,
			'per_page' => $per_page,
			'page_query_string' => TRUE,
			'query_string_segment' => 'page'
		);
		$this->pagination->initialize($pagination_config);
		$page_data['pagination'] = $this->pagination->create_links();

		$data['title'] = 'Orders' . (isset($this->current_tenant['name']) ? ' - ' . $this->current_tenant['name'] : '');
		$data['current_vendor'] = $this->current_tenant ?: array();
		$data['breadcrumb'] = array(array('label' => 'Orders', 'active' => true));
		$data['content'] = $this->load->view('school_admin/orders/index', $page_data, TRUE);

		$this->load->view('school_admin/layouts/index_template', $data);
	}

	public function cancelled_orders()
	{
		$filter_data['date_range'] = $this->input->get('date_range');
		$filter_data['keywords']  = $this->input->get('keywords');
		$filter_data['is_refund'] = $this->input->get('is_refund') ? $this->input->get('is_refund') : '0';
		$filter_data['order_status'] = $this->input->get('order_status') ? $this->input->get('order_status') : '6';
		$filter_data['school_user_id']  = $this->session->userdata('school_user_id') ?: '0';

		$total_count = $this->Order_model->get_paginated_cancelled_order_count($filter_data);
		$page_data['total_count'] = $total_count;
		$page_data['order_counts'] = $this->Order_model->get_order_status_counts(NULL);
		$page_data['order_status'] = 'cancelled';

		$per_page = 10;
		$page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
		if ($page < 1) $page = 1;
		$offset = ($page - 1) * $per_page;

		$page_data['order_list'] = $this->Order_model->get_paginated_cancelled_order($filter_data, $per_page, $offset);

		$pagination_config = array(
			'base_url' => base_url('school-admin/orders/cancelled-orders'),
			'total_rows' => $total_count,
			'per_page' => $per_page,
			'page_query_string' => TRUE,
			'query_string_segment' => 'page'
		);
		$this->pagination->initialize($pagination_config);
		$page_data['pagination'] = $this->pagination->create_links();

		$data['title'] = 'Cancelled Orders' . (isset($this->current_tenant['name']) ? ' - ' . $this->current_tenant['name'] : '');
		$data['current_vendor'] = $this->current_tenant ?: array();
		$data['breadcrumb'] = array(array('label' => 'Cancelled Orders', 'active' => true));
		$data['content'] = $this->load->view('school_admin/orders/rejected_orders', $page_data, TRUE);

		$this->load->view('school_admin/layouts/index_template', $data);
	}
}
