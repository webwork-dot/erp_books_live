<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller
{
	private $current_tenant = NULL;

	public function __construct()
	{
		parent::__construct();
		$this->load->library('Tenant');
		$this->load->model('School_reports_model');

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
		$school_user_id = (int) ($this->session->userdata('school_user_id') ?: 0);
		if ($school_user_id <= 0) {
			show_error('Unauthorized access', 403);
		}

		$data = array();
		$data['title'] = 'School & Branch Reports - School Dashboard';
		$data['current_vendor'] = $this->current_tenant ?: array();
		$data['breadcrumb'] = array(array('label' => 'School & Branch Reports', 'active' => true));

		// Preset date ranges
		$preset = $this->input->get('preset') ?: 'this_month';
		list($from, $to) = $this->_get_date_range($preset);
		$data['preset'] = $preset;
		$data['date_from'] = $from;
		$data['date_to'] = $to;

		// Get school-specific reports
		$data['order_status_summary'] = $this->School_reports_model->get_order_status_summary($school_user_id, $from, $to);
		$data['monthly_revenue'] = $this->School_reports_model->get_monthly_revenue($school_user_id, $from, $to);
		$data['grade_distribution'] = $this->School_reports_model->get_grade_distribution($school_user_id, $from, $to);
		$data['popular_packages'] = $this->School_reports_model->get_popular_packages($school_user_id, $from, $to);
		$data['payment_methods'] = $this->School_reports_model->get_payment_methods($school_user_id, $from, $to);
		$data['student_distribution'] = $this->School_reports_model->get_student_distribution($school_user_id, $from, $to);

		$data['content'] = $this->load->view('school_admin/reports/index', $data, TRUE);
		$this->load->view('school_admin/layouts/index_template', $data);
	}

	/**
	 * Get date range from preset
	 */
	protected function _get_date_range($preset)
	{
		$today = date('Y-m-d');
		switch ($preset) {
			case 'today':
				return array($today, $today);
			case 'yesterday':
				$y = date('Y-m-d', strtotime('-1 day'));
				return array($y, $y);
			case 'this_week':
				$start = date('Y-m-d', strtotime('monday this week'));
				return array($start, $today);
			case 'last_week':
				$start = date('Y-m-d', strtotime('monday last week'));
				$end = date('Y-m-d', strtotime('sunday last week'));
				return array($start, $end);
			case 'this_month':
				$start = date('Y-m-01');
				return array($start, $today);
			case 'last_month':
				$start = date('Y-m-01', strtotime('first day of last month'));
				$end = date('Y-m-t', strtotime('last day of last month'));
				return array($start, $end);
			case 'this_year':
				$start = date('Y-01-01');
				return array($start, $today);
			case 'custom':
				$from = $this->input->get('from');
				$to = $this->input->get('to');
				if ($from && $to) {
					return array($from, $to);
				}
				return array(date('Y-m-01'), $today);
			default:
				return array(date('Y-m-01'), $today);
		}
	}
}