<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class School_reports_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Get all school and branch IDs that belong to a school admin
	 */
	private function get_all_school_branch_ids($school_user_id)
	{
		$school_user_id = (int) $school_user_id;
		$ids = array($school_user_id); // Include the main school ID

		// Get all branch IDs that belong to this school
		$this->db->select('id');
		$this->db->from('erp_school_branches');
		$this->db->where('school_id', $school_user_id);
		$this->db->where('status', 'active');
		$branch_query = $this->db->get();

		if ($branch_query->num_rows() > 0) {
			foreach ($branch_query->result_array() as $branch) {
				$ids[] = $branch['id'];
			}
		}

		return $ids;
	}

	/**
	 * Get order status summary for a specific school
	 */
	public function get_order_status_summary($school_user_id, $from = null, $to = null)
	{
		$school_user_id = (int) $school_user_id;
		if ($school_user_id <= 0) {
			return array();
		}

		$date_filter = '';
		if (!empty($from) && !empty($to)) {
			$from = $this->db->escape_str($from);
			$to = $this->db->escape_str($to);
			$date_filter = " AND DATE(d.order_date) BETWEEN '{$from}' AND '{$to}'";
		}

		// Get all school/branch IDs that belong to this school admin
		$school_ids = $this->get_all_school_branch_ids($school_user_id);
		$school_ids_str = implode(',', array_map('intval', $school_ids));

		$sql = "SELECT
			CASE
				WHEN d.order_status = '1' THEN 'New Order'
				WHEN d.order_status = '2' THEN 'Processing'
				WHEN d.order_status = '3' THEN 'Out for Delivery'
				WHEN d.order_status = '4' THEN 'Delivered'
				WHEN d.order_status = '5' THEN 'Cancelled'
				WHEN d.order_status = '7' THEN 'Return/Refund'
				ELSE 'Unknown'
			END as status_name,
			COUNT(DISTINCT d.id) as count,
			COALESCE(SUM(d.payable_amt), 0) as total_revenue
			FROM tbl_order_details d
			INNER JOIN tbl_order_items oi ON oi.order_id = d.id
			WHERE oi.school_id IN ({$school_ids_str})
			AND oi.order_type = 'bookset'
			AND (d.payment_status IN ('success', 'cod', 'payment_at_school') OR d.payment_method IN ('cod', 'payment_at_school'))
			AND (d.order_status IS NULL OR d.order_status != '5')
			{$date_filter}
			GROUP BY d.order_status
			ORDER BY d.order_status";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	/**
	 * Get monthly revenue for a specific school
	 */
	public function get_monthly_revenue($school_user_id, $from = null, $to = null)
	{
		$school_user_id = (int) $school_user_id;
		if ($school_user_id <= 0) {
			return array();
		}

		$date_filter = '';
		if (!empty($from) && !empty($to)) {
			$from = $this->db->escape_str($from);
			$to = $this->db->escape_str($to);
			$date_filter = " AND DATE(d.order_date) BETWEEN '{$from}' AND '{$to}'";
		} else {
			// Default to last 12 months if no date range specified
			$from = date('Y-m-d', strtotime('-12 months'));
			$to = date('Y-m-d');
			$date_filter = " AND DATE(d.order_date) BETWEEN '{$from}' AND '{$to}'";
		}

		// Get all school/branch IDs that belong to this school admin
		$school_ids = $this->get_all_school_branch_ids($school_user_id);
		$school_ids_str = implode(',', array_map('intval', $school_ids));

		$sql = "SELECT
			DATE_FORMAT(d.order_date, '%Y-%m') as month,
			DATE_FORMAT(d.order_date, '%M %Y') as month_name,
			COUNT(DISTINCT d.id) as order_count,
			COALESCE(SUM(d.payable_amt), 0) as revenue
			FROM tbl_order_details d
			INNER JOIN tbl_order_items oi ON oi.order_id = d.id
			WHERE oi.school_id IN ({$school_ids_str})
			AND oi.order_type = 'bookset'
			AND (d.payment_status IN ('success', 'cod', 'payment_at_school') OR d.payment_method IN ('cod', 'payment_at_school'))
			AND (d.order_status IS NULL OR d.order_status != '5')
			{$date_filter}
			GROUP BY DATE_FORMAT(d.order_date, '%Y-%m'), DATE_FORMAT(d.order_date, '%M %Y')
			ORDER BY month";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	/**
	 * Get grade distribution for a specific school
	 */
	public function get_grade_distribution($school_user_id, $from = null, $to = null)
	{
		$school_user_id = (int) $school_user_id;
		if ($school_user_id <= 0) {
			return array();
		}

		$date_filter = '';
		if (!empty($from) && !empty($to)) {
			$from = $this->db->escape_str($from);
			$to = $this->db->escape_str($to);
			$date_filter = " AND DATE(d.order_date) BETWEEN '{$from}' AND '{$to}'";
		}

		// Get all school/branch IDs that belong to this school admin
		$school_ids = $this->get_all_school_branch_ids($school_user_id);
		$school_ids_str = implode(',', array_map('intval', $school_ids));

		$sql = "SELECT
			COALESCE(tg.name, 'Unknown') as grade,
			COUNT(DISTINCT d.id) as order_count,
			COALESCE(SUM(d.payable_amt), 0) as revenue
			FROM tbl_order_details d
			INNER JOIN tbl_order_items oi ON oi.order_id = d.id
			LEFT JOIN erp_booksets bs ON bs.id = oi.product_id AND oi.order_type = 'bookset'
			LEFT JOIN erp_textbook_grades tg ON tg.id = bs.grade_id
			WHERE oi.school_id IN ({$school_ids_str})
			AND oi.order_type = 'bookset'
			AND (d.payment_status IN ('success', 'cod', 'payment_at_school') OR d.payment_method IN ('cod', 'payment_at_school'))
			AND (d.order_status IS NULL OR d.order_status != '5')
			{$date_filter}
			GROUP BY tg.name
			ORDER BY order_count DESC";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	/**
	 * Get popular packages for a specific school
	 */
	public function get_popular_packages($school_user_id, $from = null, $to = null, $limit = 10)
	{
		$school_user_id = (int) $school_user_id;
		if ($school_user_id <= 0) {
			return array();
		}

		$date_filter = '';
		if (!empty($from) && !empty($to)) {
			$from = $this->db->escape_str($from);
			$to = $this->db->escape_str($to);
			$date_filter = " AND DATE(d.order_date) BETWEEN '{$from}' AND '{$to}'";
		}

		// Get all school/branch IDs that belong to this school admin
		$school_ids = $this->get_all_school_branch_ids($school_user_id);
		$school_ids_str = implode(',', array_map('intval', $school_ids));

		$sql = "SELECT
			p.package_name,
			COUNT(DISTINCT d.id) as order_count,
			COALESCE(SUM(d.payable_amt), 0) as revenue
			FROM tbl_order_details d
			INNER JOIN tbl_order_items oi ON oi.order_id = d.id
			INNER JOIN erp_bookset_packages p ON CONCAT(',', oi.package_id, ',') LIKE CONCAT('%,', p.id, ',%')
			WHERE oi.school_id IN ({$school_ids_str})
			AND oi.order_type = 'bookset'
			AND oi.package_id IS NOT NULL AND oi.package_id != ''
			AND (d.payment_status IN ('success', 'cod', 'payment_at_school') OR d.payment_method IN ('cod', 'payment_at_school'))
			AND (d.order_status IS NULL OR d.order_status != '5')
			{$date_filter}
			GROUP BY p.id, p.package_name
			ORDER BY order_count DESC
			LIMIT {$limit}";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	/**
	 * Get payment methods distribution for a specific school
	 */
	public function get_payment_methods($school_user_id, $from = null, $to = null)
	{
		$school_user_id = (int) $school_user_id;
		if ($school_user_id <= 0) {
			return array();
		}

		$date_filter = '';
		if (!empty($from) && !empty($to)) {
			$from = $this->db->escape_str($from);
			$to = $this->db->escape_str($to);
			$date_filter = " AND DATE(d.order_date) BETWEEN '{$from}' AND '{$to}'";
		}

		// Get all school/branch IDs that belong to this school admin
		$school_ids = $this->get_all_school_branch_ids($school_user_id);
		$school_ids_str = implode(',', array_map('intval', $school_ids));

		$sql = "SELECT
			CASE
				WHEN d.payment_method = 'cashfree' OR d.payment_status = 'success' THEN 'Online Payment'
				WHEN d.payment_method = 'cod' THEN 'Cash on Delivery'
				WHEN d.payment_method = 'payment_at_school' OR d.payment_status = 'payment_at_school' THEN 'Payment at School'
				ELSE 'Other'
			END as payment_method,
			COUNT(DISTINCT d.id) as order_count,
			COALESCE(SUM(d.payable_amt), 0) as revenue
			FROM tbl_order_details d
			INNER JOIN tbl_order_items oi ON oi.order_id = d.id
			WHERE oi.school_id IN ({$school_ids_str})
			AND oi.order_type = 'bookset'
			AND (d.payment_status IN ('success', 'cod', 'payment_at_school') OR d.payment_method IN ('cod', 'payment_at_school'))
			AND (d.order_status IS NULL OR d.order_status != '5')
			{$date_filter}
			GROUP BY
			CASE
				WHEN d.payment_method = 'cashfree' OR d.payment_status = 'success' THEN 'Online Payment'
				WHEN d.payment_method = 'cod' THEN 'Cash on Delivery'
				WHEN d.payment_method = 'payment_at_school' OR d.payment_status = 'payment_at_school' THEN 'Payment at School'
				ELSE 'Other'
			END
			ORDER BY revenue DESC";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	/**
	 * Get student distribution for a specific school
	 */
	public function get_student_distribution($school_user_id, $from = null, $to = null)
	{
		$school_user_id = (int) $school_user_id;
		if ($school_user_id <= 0) {
			return array();
		}

		$date_filter = '';
		if (!empty($from) && !empty($to)) {
			$from = $this->db->escape_str($from);
			$to = $this->db->escape_str($to);
			$date_filter = " AND DATE(d.order_date) BETWEEN '{$from}' AND '{$to}'";
		}

		// Get all school/branch IDs that belong to this school admin
		$school_ids = $this->get_all_school_branch_ids($school_user_id);
		$school_ids_str = implode(',', array_map('intval', $school_ids));

		$sql = "SELECT
			COUNT(DISTINCT d.id) as total_orders,
			COUNT(DISTINCT CONCAT(COALESCE(oi.f_name, ''), '_', COALESCE(oi.s_name, ''), '_', COALESCE(oi.dob, ''))) as unique_students,
			AVG(d.payable_amt) as avg_order_value,
			SUM(d.payable_amt) as total_revenue
			FROM tbl_order_details d
			INNER JOIN tbl_order_items oi ON oi.order_id = d.id
			WHERE oi.school_id IN ({$school_ids_str})
			AND oi.order_type = 'bookset'
			AND (d.payment_status IN ('success', 'cod', 'payment_at_school') OR d.payment_method IN ('cod', 'payment_at_school'))
			AND (d.order_status IS NULL OR d.order_status != '5')
			{$date_filter}";

		$query = $this->db->query($sql);
		$result = $query->row_array();

		// Calculate additional metrics
		if ($result) {
			$result['avg_orders_per_student'] = $result['unique_students'] > 0 ?
				round($result['total_orders'] / $result['unique_students'], 1) : 0;
		}

		return $result ?: array();
	}
}