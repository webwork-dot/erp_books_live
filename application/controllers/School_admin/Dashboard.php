<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
	private $current_tenant = NULL;

	public function __construct()
	{
		parent::__construct();
		$this->load->library('Tenant');
		$this->load->model('School_dashboard_model');
		$this->load->model('Erp_client_model');

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

	public function index()
	{
		$stationery_orders = $this->School_dashboard_model->getStationeryOrderCounts();

		$data = array();
		$data['title'] = 'School Dashboard' . (isset($this->current_tenant['name']) ? ' - ' . $this->current_tenant['name'] : '');
		$data['current_vendor'] = $this->current_tenant ?: array();
		$data['stationery_orders'] = $stationery_orders;
		$data['breadcrumb'] = array(array('label' => 'Dashboard', 'active' => true));

		$data['content'] = $this->load->view('school_admin/dashboard/index', $data, TRUE);
		$this->load->view('school_admin/layouts/index_template', $data);
	}
}
