<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Vendor Reports Controller
 *
 * Provides reports for vendors: sales, orders by school, location,
 * delivery performance. Access is based on enabled features.
 *
 * @package     ERP
 * @subpackage  Controllers
 * @category    Vendor
 */
require_once(APPPATH . 'controllers/Vendor/Vendor_base.php');

class Reports extends Vendor_base
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Reports_model');
    }

    /**
     * Reports index - main reports dashboard
     */
    public function index()
    {
        $data['title'] = 'Reports';
        $data['current_vendor'] = $this->current_vendor;
        $data['vendor_domain'] = $this->getVendorDomainForUrl();
        // Do NOT pass enabled_features - let index_template load it for sidebar Products section
        $data['breadcrumb'] = array(
            array('label' => 'Reports', 'active' => true)
        );

        // Preset date ranges
        $preset = $this->input->get('preset') ?: 'this_month';
        list($from, $to) = $this->_get_date_range($preset);
        $data['preset'] = $preset;
        $data['date_from'] = $from;
        $data['date_to'] = $to;

        // Filters
        $filters = array(
            'school_id' => $this->input->get('school'),
            'state' => $this->input->get('state'),
            'city' => $this->input->get('city'),
            'order_type' => $this->input->get('order_type')
        );
        $data['filters'] = $filters;

        // Dropdown options
        $data['schools'] = $this->Reports_model->get_schools_for_filter();
        $data['states'] = $this->Reports_model->get_states_for_filter();
        $data['cities'] = $this->Reports_model->get_cities_for_filter();

        // Reports - always show sales summary and delivery performance
        $data['sales_summary'] = $this->Reports_model->get_sales_summary($from, $to, $filters);
        $data['delivery_performance'] = $this->Reports_model->get_delivery_performance($from, $to, $filters);
        $data['orders_by_type'] = $this->Reports_model->get_orders_by_type($from, $to, $filters);

        // Feature-based reports (bookset/books = school-wise reports)
        $data['has_bookset'] = $this->_has_feature('bookset') || $this->_has_feature('books');
        $data['has_uniforms'] = $this->_has_feature('uniforms');
        $data['orders_by_school'] = array();
        $data['orders_by_location'] = array();

        if ($data['has_bookset']) {
            $data['orders_by_school'] = $this->Reports_model->get_orders_by_school($from, $to, $filters);
        }
        $data['orders_by_location'] = $this->Reports_model->get_orders_by_location($from, $to, $filters, 'state');
        $data['orders_by_city'] = $this->Reports_model->get_orders_by_location($from, $to, $filters, 'city');

        // Sales trend (full preset range; daily if <=30 days, monthly if >30 days; respects filters)
        $trend_from = $from ?: date('Y-m-d', strtotime('-30 days'));
        $trend_to = $to ?: date('Y-m-d');
        $data['sales_trend'] = $this->Reports_model->get_sales_trend($trend_from, $trend_to, $filters);

        $data['content'] = $this->load->view('vendor/reports/index', $data, TRUE);
        $this->load->view('vendor/layouts/index_template', $data);
    }

    /**
     * AJAX: Get report data (for dynamic filter updates)
     */
    public function get_data()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $preset = $this->input->post('preset') ?: $this->input->get('preset') ?: 'this_month';
        list($from, $to) = $this->_get_date_range($preset);
        $filters = array(
            'school_id' => $this->input->post('school') ?: $this->input->get('school'),
            'state' => $this->input->post('state') ?: $this->input->get('state'),
            'city' => $this->input->post('city') ?: $this->input->get('city'),
            'order_type' => $this->input->post('order_type') ?: $this->input->get('order_type')
        );

        $data = array(
            'sales_summary' => $this->Reports_model->get_sales_summary($from, $to, $filters),
            'delivery_performance' => $this->Reports_model->get_delivery_performance($from, $to, $filters),
            'orders_by_type' => $this->Reports_model->get_orders_by_type($from, $to, $filters),
            'orders_by_school' => $this->Reports_model->get_orders_by_school($from, $to, $filters),
            'orders_by_location' => $this->Reports_model->get_orders_by_location($from, $to, $filters, 'state'),
            'date_from' => $from,
            'date_to' => $to
        );
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Export report to CSV
     */
    public function export($report_type = 'sales')
    {
        $preset = $this->input->get('preset') ?: 'this_month';
        list($from, $to) = $this->_get_date_range($preset);
        $filters = array(
            'school_id' => $this->input->get('school'),
            'state' => $this->input->get('state'),
            'city' => $this->input->get('city'),
            'order_type' => $this->input->get('order_type')
        );

        $filename = 'report_' . $report_type . '_' . date('Y-m-d') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $out = fopen('php://output', 'w');
        fputcsv($out, array('Report: ' . ucfirst(str_replace('_', ' ', $report_type))));
        fputcsv($out, array('Date Range: ' . $from . ' to ' . $to));
        fputcsv($out, array());

        switch ($report_type) {
            case 'school':
                $rows = $this->Reports_model->get_orders_by_school($from, $to, $filters);
                fputcsv($out, array('School', 'Order Count', 'Total Revenue'));
                foreach ($rows as $r) {
                    fputcsv($out, array(
                        $r['school_name'] ?: 'Unknown',
                        $r['order_count'],
                        $r['total_revenue']
                    ));
                }
                break;
            case 'location':
                $rows = $this->Reports_model->get_orders_by_location($from, $to, $filters, 'state');
                fputcsv($out, array('State', 'Order Count', 'Total Revenue'));
                foreach ($rows as $r) {
                    fputcsv($out, array($r['location_name'], $r['order_count'], $r['total_revenue']));
                }
                break;
            case 'delivery':
                $rows = $this->Reports_model->get_delivery_performance($from, $to, $filters);
                fputcsv($out, array('Courier', 'Orders', 'Revenue', 'Delivered', 'Returns'));
                foreach ($rows as $r) {
                    fputcsv($out, array(
                        $r['courier_name'],
                        $r['order_count'],
                        $r['total_revenue'],
                        $r['delivered_count'],
                        $r['return_count']
                    ));
                }
                break;
            default:
                $rows = $this->Reports_model->get_orders_by_type($from, $to, $filters);
                fputcsv($out, array('Order Type', 'Order Count', 'Total Revenue'));
                foreach ($rows as $r) {
                    fputcsv($out, array($r['order_type'], $r['order_count'], $r['total_revenue']));
                }
        }
        fclose($out);
        exit;
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
            case 'this_quarter':
                $q = ceil(date('n') / 3);
                $start = date('Y-m-d', strtotime(date('Y') . '-' . (($q - 1) * 3 + 1) . '-01'));
                return array($start, $today);
            case 'this_year':
                $start = date('Y-01-01');
                return array($start, $today);
            case 'custom':
                $from = $this->input->get('from') ?: $this->input->post('from');
                $to = $this->input->get('to') ?: $this->input->post('to');
                if ($from && $to) {
                    return array($from, $to);
                }
                return array(date('Y-m-01'), $today);
            default:
                return array(date('Y-m-01'), $today);
        }
    }

    /**
     * Check if vendor has a specific feature enabled
     */
    protected function _has_feature($slug)
    {
        $features = $this->getEnabledFeatures();
        $slug_lower = strtolower($slug);
        foreach ($features as $f) {
            $s = isset($f['slug']) ? $f['slug'] : (isset($f['feature_slug']) ? $f['feature_slug'] : '');
            if (strtolower($s) === $slug_lower) {
                return true;
            }
        }
        return false;
    }
}
