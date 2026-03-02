<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class School_dashboard_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	private function countByStatus($status)
  {
      $school_user_id = (int) ($this->session->userdata('school_user_id') ?: 0);

      // Map text status -> numeric code used in tbl_order_details
      $statusMap = [
          'pending'          => '1',
          'processing'       => '2',
          'out_for_delivery' => '3',
          'delivered'        => '4',
          'return'           => '7',
      ];

      $statusCode = isset($statusMap[$status]) ? $statusMap[$status] : '0';

      $this->db->select('COUNT(DISTINCT d.id) AS count', false);
      $this->db->from('tbl_order_details d');
      $this->db->join('tbl_order_items oi', 'oi.order_id = d.id', 'inner');

      // Only bookset orders for school and its branches
      $this->db->where('oi.order_type', 'bookset');
      if ($school_user_id > 0) {
          // Get all school/branch IDs that belong to this school admin
          $school_ids = $this->get_all_school_branch_ids($school_user_id);
          $this->db->where_in('oi.school_id', $school_ids);
      }

      // Payment condition
      $this->db->group_start();
          $this->db->where('d.payment_status', 'success');
          $this->db->or_where('d.payment_status', 'cod');
          $this->db->or_where('d.payment_method', 'cod');
      $this->db->group_end();

      // Status condition
      $this->db->where('d.order_status', $statusCode);

      $row = $this->db->get()->row_array();
      return isset($row['count']) ? (int) $row['count'] : 0;
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

  public function getStationeryOrderCounts()
  {
      // (keeping your same keys)
      return array(
          'new_order'        => $this->countByStatus('pending'),
          'processing'       => $this->countByStatus('processing'),
          'out_for_delivery' => $this->countByStatus('out_for_delivery'),
          'delivered'        => $this->countByStatus('delivered'),
          'return'           => $this->countByStatus('return'),
      );
  }

}
