<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Reports Model
 *
 * Handles report queries for vendor dashboard - sales, orders by school,
 * location, delivery performance, etc. Based on tbl_order_details structure.
 *
 * @package     ERP
 * @subpackage  Models
 * @category    Models
 */
class Reports_model extends CI_Model
{
    /** Payment status filter for valid orders */
    protected $payment_filter = "(d.payment_status IN ('success','cod','payment_at_school') OR d.payment_method IN ('cod','payment_at_school'))";

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Build base date filter for order queries
     */
    protected function _date_filter($from, $to)
    {
        if (empty($from) || empty($to)) {
            return '';
        }
        $from = $this->db->escape_str($from);
        $to = $this->db->escape_str($to);
        return " AND (DATE(d.order_date) BETWEEN '{$from}' AND '{$to}')";
    }

    /**
     * Build base where clause for valid paid orders (exclude cancelled)
     */
    protected function _base_where($date_filter = '')
    {
        return "d.id <> '' AND {$this->payment_filter} AND (d.order_status IS NULL OR d.order_status != '5') {$date_filter}";
    }

    /**
     * Sales summary report - total revenue, order count, average order value
     */
    public function get_sales_summary($from = null, $to = null, $filters = array())
    {
        $date_filter = $this->_date_filter($from, $to);
        $extra = $this->_build_filters($filters);
        $where = $this->_base_where($date_filter) . $extra;

        $sql = "SELECT
            COUNT(DISTINCT d.id) as order_count,
            COALESCE(SUM(d.payable_amt), 0) as total_revenue,
            COALESCE(AVG(d.payable_amt), 0) as avg_order_value
            FROM tbl_order_details d
            INNER JOIN tbl_order_items oi ON oi.order_id = d.id
            WHERE {$where}";
        $q = $this->db->query($sql);
        return $q->row_array();
    }

    /**
     * Orders by school (for bookset vendors)
     */
    public function get_orders_by_school($from = null, $to = null, $filters = array())
    {
        $date_filter = $this->_date_filter($from, $to);
        $extra = $this->_build_filters($filters);
        $where = $this->_base_where($date_filter) . $extra;

        $school_filter = " AND oi.school_id IS NOT NULL AND oi.school_id > 0 AND oi.order_type = 'bookset'";

        $sql = "SELECT
            oi.school_id,
            s.school_name,
            COUNT(DISTINCT d.id) as order_count,
            COALESCE(SUM(d.payable_amt), 0) as total_revenue
            FROM tbl_order_details d
            INNER JOIN tbl_order_items oi ON oi.order_id = d.id
            LEFT JOIN erp_schools s ON s.id = oi.school_id
            WHERE {$where} {$school_filter}
            GROUP BY oi.school_id, s.school_name
            ORDER BY order_count DESC, total_revenue DESC";
        $q = $this->db->query($sql);
        return $q->result_array();
    }

    /**
     * Orders by location (city/state from tbl_order_address or tbl_order_details)
     */
    public function get_orders_by_location($from = null, $to = null, $filters = array(), $group_by = 'state')
    {
        $date_filter = $this->_date_filter($from, $to);
        $extra = $this->_build_filters($filters);
        $where = $this->_base_where($date_filter) . $extra;

        $loc_col = ($group_by === 'city') ? 'COALESCE(oa.city, d.del_city, "Unknown")' : 'COALESCE(oa.state, d.del_state, "Unknown")';
        $loc_alias = ($group_by === 'city') ? 'location_name' : 'location_name';

        $sql = "SELECT
            {$loc_col} as {$loc_alias},
            COUNT(DISTINCT d.id) as order_count,
            COALESCE(SUM(d.payable_amt), 0) as total_revenue
            FROM tbl_order_details d
            INNER JOIN tbl_order_items oi ON oi.order_id = d.id
            LEFT JOIN tbl_order_address oa ON oa.order_id = d.id
            WHERE {$where}
            GROUP BY {$loc_col}
            ORDER BY order_count DESC, total_revenue DESC";
        $q = $this->db->query($sql);
        return $q->result_array();
    }

    /**
     * Delivery / Courier performance report
     * Groups by courier type: manual (erp_master_courier), shiprocket, SELF, 3rd_party
     */
    public function get_delivery_performance($from = null, $to = null, $filters = array())
    {
        $date_filter = $this->_date_filter($from, $to);
        $extra = $this->_build_filters($filters);
        $where = $this->_base_where($date_filter) . $extra;

        $has_erp_courier = $this->db->field_exists('erp_courier_id', 'tbl_order_details');
        $has_third_party = $this->db->field_exists('third_party_provider', 'tbl_order_details');

        $manual_then = $has_erp_courier
            ? "WHEN d.courier = 'manual' AND d.erp_courier_id IS NOT NULL THEN COALESCE(c.courier_name, 'Manual') WHEN d.courier = 'manual' THEN 'Manual (Unassigned)'"
            : "WHEN d.courier = 'manual' THEN 'Manual'";
        $third_party_then = $has_third_party
            ? "WHEN d.courier = '3rd_party' THEN COALESCE(d.third_party_provider, '3rd Party')"
            : "";

        $courier_select = "CASE
            {$manual_then}
            WHEN d.courier = 'shiprocket' THEN 'Shiprocket'
            WHEN d.courier = 'SELF' THEN 'Self Delivery'
            {$third_party_then}
            WHEN d.courier IS NULL OR d.courier = '' THEN 'Not Assigned'
            ELSE COALESCE(d.courier, 'Unknown')
        END";

        $erp_courier_join = $has_erp_courier
            ? "LEFT JOIN erp_master_courier c ON c.id = d.erp_courier_id"
            : "";

        $sql = "SELECT
            {$courier_select} as courier_name,
            COUNT(DISTINCT d.id) as order_count,
            COALESCE(SUM(d.payable_amt), 0) as total_revenue,
            SUM(CASE WHEN d.order_status = '4' THEN 1 ELSE 0 END) as delivered_count,
            SUM(CASE WHEN d.order_status = '7' THEN 1 ELSE 0 END) as return_count
            FROM tbl_order_details d
            INNER JOIN tbl_order_items oi ON oi.order_id = d.id
            {$erp_courier_join}
            WHERE {$where}
            GROUP BY 1
            ORDER BY order_count DESC";
        $q = $this->db->query($sql);
        return $q->result_array();
    }

    /**
     * Orders by order type (bookset, individual, uniform)
     */
    public function get_orders_by_type($from = null, $to = null, $filters = array())
    {
        $date_filter = $this->_date_filter($from, $to);
        $extra = $this->_build_filters($filters);
        $where = $this->_base_where($date_filter) . $extra;

        $type_col = $this->db->field_exists('type_order', 'tbl_order_details')
            ? "COALESCE(d.type_order, oi.order_type, 'individual')"
            : "COALESCE(oi.order_type, 'individual')";

        $sql = "SELECT
            {$type_col} as order_type,
            COUNT(DISTINCT d.id) as order_count,
            COALESCE(SUM(d.payable_amt), 0) as total_revenue
            FROM tbl_order_details d
            INNER JOIN tbl_order_items oi ON oi.order_id = d.id
            WHERE {$where}
            GROUP BY order_type
            ORDER BY order_count DESC";
        $q = $this->db->query($sql);
        return $q->result_array();
    }

    /**
     * Daily/Weekly/Monthly sales trend
     * Returns ALL dates/months in range (0 for periods with no orders)
     * Uses daily when range <= 30 days, monthly when > 30 days
     * Respects filters (school, state, city, order_type)
     * Returns empty array when filtered orders are 0 (no zero bars)
     */
    public function get_sales_trend($from, $to, $filters = array())
    {
        if (empty($from) || empty($to)) {
            return array();
        }
        $from = $this->db->escape_str($from);
        $to = $this->db->escape_str($to);
        $extra = $this->_build_filters($filters);

        $days = (strtotime($to) - strtotime($from)) / 86400 + 1;
        $use_monthly = ($days > 30);
        $date_format = $use_monthly ? "%Y-%m" : "%Y-%m-%d";
        $date_expr = "DATE_FORMAT(d.order_date, '{$date_format}')";

        $sql = "SELECT
            {$date_expr} as period,
            COUNT(DISTINCT d.id) as order_count,
            COALESCE(SUM(d.payable_amt), 0) as total_revenue
            FROM tbl_order_details d
            INNER JOIN tbl_order_items oi ON oi.order_id = d.id
            WHERE {$this->payment_filter} AND (d.order_status IS NULL OR d.order_status != '5')
            AND (DATE(d.order_date) BETWEEN '{$from}' AND '{$to}')
            {$extra}
            GROUP BY period
            ORDER BY period ASC";
        $q = $this->db->query($sql);
        $db_result = $q->result_array();
        $by_period = array();
        $total_orders = 0;
        foreach ($db_result as $row) {
            $by_period[$row['period']] = $row;
            $total_orders += (int)$row['order_count'];
        }

        // When filters result in 0 orders, return empty (no zero bars)
        if ($total_orders === 0) {
            return array();
        }

        $result = array();
        if ($use_monthly) {
            $start = new DateTime($from);
            $start->modify('first day of this month');
            $end = new DateTime($to);
            $end->modify('first day of this month');
            $current = clone $start;
            while ($current <= $end) {
                $p = $current->format('Y-m');
                $result[] = array(
                    'period' => $p,
                    'order_count' => isset($by_period[$p]) ? $by_period[$p]['order_count'] : 0,
                    'total_revenue' => isset($by_period[$p]) ? $by_period[$p]['total_revenue'] : 0
                );
                $current->modify('+1 month');
            }
        } else {
            $start = strtotime($from);
            $end = strtotime($to);
            for ($t = $start; $t <= $end; $t += 86400) {
                $p = date('Y-m-d', $t);
                $result[] = array(
                    'period' => $p,
                    'order_count' => isset($by_period[$p]) ? $by_period[$p]['order_count'] : 0,
                    'total_revenue' => isset($by_period[$p]) ? $by_period[$p]['total_revenue'] : 0
                );
            }
        }
        return $result;
    }

    /**
     * Get list of schools for filter dropdown
     */
    public function get_schools_for_filter()
    {
        if (!$this->db->table_exists('erp_schools')) {
            return array();
        }
        $this->db->select('id, school_name');
        $this->db->from('erp_schools');
        $this->db->where('status', 'active');
        $this->db->order_by('school_name', 'ASC');
        $q = $this->db->get();
        return $q->result_array();
    }

    /**
     * Get distinct states for filter dropdown (from tbl_order_address + tbl_order_details)
     */
    public function get_states_for_filter()
    {
        $parts = array("SELECT state as name FROM tbl_order_address WHERE state IS NOT NULL AND state != ''");
        if ($this->db->field_exists('del_state', 'tbl_order_details')) {
            $parts[] = "SELECT del_state as name FROM tbl_order_details WHERE del_state IS NOT NULL AND del_state != ''";
        }
        $sql = "SELECT DISTINCT name FROM (" . implode(' UNION ', $parts) . ") t WHERE name IS NOT NULL AND name != '' ORDER BY name ASC";
        $q = $this->db->query($sql);
        return $q->result_array();
    }

    /**
     * Get distinct cities for filter dropdown
     */
    public function get_cities_for_filter()
    {
        $parts = array("SELECT city as name FROM tbl_order_address WHERE city IS NOT NULL AND city != ''");
        if ($this->db->field_exists('del_city', 'tbl_order_details')) {
            $parts[] = "SELECT del_city as name FROM tbl_order_details WHERE del_city IS NOT NULL AND del_city != ''";
        }
        $sql = "SELECT DISTINCT name FROM (" . implode(' UNION ', $parts) . ") t WHERE name IS NOT NULL AND name != '' ORDER BY name ASC";
        $q = $this->db->query($sql);
        return $q->result_array();
    }

    /**
     * Build extra filter SQL from filters array
     */
    protected function _build_filters($filters)
    {
        $sql = '';
        if (isset($filters['school_id']) && (int)$filters['school_id'] > 0) {
            $sid = (int)$filters['school_id'];
            $sql .= " AND EXISTS (SELECT 1 FROM tbl_order_items oi2 WHERE oi2.order_id = d.id AND oi2.school_id = {$sid})";
        }
        if (isset($filters['state']) && trim($filters['state']) !== '') {
            $s = $this->db->escape_str(trim($filters['state']));
            $sql .= " AND (EXISTS (SELECT 1 FROM tbl_order_address oa2 WHERE oa2.order_id = d.id AND oa2.state = '{$s}') OR d.del_state = '{$s}')";
        }
        if (isset($filters['city']) && trim($filters['city']) !== '') {
            $c = $this->db->escape_str(trim($filters['city']));
            $sql .= " AND (EXISTS (SELECT 1 FROM tbl_order_address oa2 WHERE oa2.order_id = d.id AND oa2.city = '{$c}') OR d.del_city = '{$c}')";
        }
        if (isset($filters['order_type']) && trim($filters['order_type']) !== '') {
            $ot = $this->db->escape_str(trim($filters['order_type']));
            $sql .= " AND EXISTS (SELECT 1 FROM tbl_order_items oi2 WHERE oi2.order_id = d.id AND oi2.order_type = '{$ot}')";
        }
        return $sql;
    }
}
