<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Courier Model
 * Manages master couriers for vendors
 * Table: erp_master_courier (or erp_master_courier - update if your table name differs)
 */
class Courier_model extends CI_Model
{
    protected $table = 'erp_master_courier';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all couriers for a vendor
     */
    public function get_couriers($vendor_id = null)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        if ($vendor_id) {
            $this->db->where('vendor_id', $vendor_id);
        }
        $this->db->order_by('courier_name', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get courier by ID (with vendor check for security)
     */
    public function get_courier_by_id($id, $vendor_id = null)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('id', $id);
        if ($vendor_id) {
            $this->db->where('vendor_id', $vendor_id);
        }
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * Add new courier
     */
    public function add_courier($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * Update courier
     */
    public function update_courier($id, $data, $vendor_id = null)
    {
        if ($vendor_id) {
            $this->db->where('vendor_id', $vendor_id);
        }
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    /**
     * Delete courier
     */
    public function delete_courier($id, $vendor_id = null)
    {
        if ($vendor_id) {
            $this->db->where('vendor_id', $vendor_id);
        }
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    /**
     * Toggle courier status (1=active, 0=inactive)
     */
    public function toggle_status($id, $vendor_id = null)
    {
        $courier = $this->get_courier_by_id($id, $vendor_id);
        if (!$courier) {
            return false;
        }
        $new_status = ($courier['status'] == 1) ? 0 : 1;
        return $this->update_courier($id, array('status' => $new_status), $vendor_id);
    }

    /**
     * Get orders by courier (out for delivery + delivered)
     * Returns orders where erp_courier_id matches and order_status is 3 or 4
     *
     * @param int $courier_id Courier ID
     * @param int $vendor_id Vendor ID (for security - courier must belong to vendor)
     * @return array Orders with order_unique_id, user_name, order_status, shipment_date, delivery_date, track_date, etc.
     */
    public function get_orders_by_courier($courier_id, $vendor_id = null)
    {
        $courier = $this->get_courier_by_id($courier_id, $vendor_id);
        if (!$courier) {
            return array();
        }

        $cols = 'd.id, d.order_unique_id, d.user_name, d.user_phone, d.order_status, d.order_date, d.shipment_date, d.delivery_date, d.invoice_no';
        if ($this->db->field_exists('ship_order_id', 'tbl_order_details')) {
            $cols .= ', d.ship_order_id';
        }
        if ($this->db->field_exists('track_date', 'tbl_order_details')) {
            $cols .= ', d.track_date';
        }
        if ($this->db->field_exists('awb_no', 'tbl_order_details')) {
            $cols .= ', d.awb_no';
        }
        $this->db->select($cols);
        $this->db->from('tbl_order_details d');
        $this->db->where('d.erp_courier_id', (int) $courier_id);
        $this->db->where_in('d.order_status', array('3', '4'));
        $this->db->where('d.order_status !=', '5');
        $this->db->order_by('d.shipment_date', 'DESC');
        $this->db->order_by('d.delivery_date', 'DESC');
        $query = $this->db->get();

        if ($query->num_rows() == 0) {
            return array();
        }

        $orders = array();
        foreach ($query->result_array() as $row) {
            $orders[] = $row;
        }
        return $orders;
    }
}
