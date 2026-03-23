<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * POS School UPI QR Model
 *
 * Handles school-wise UPI QR records and single-active validation.
 */
class Pos_school_qr_model extends CI_Model
{
    private $master_db;

    public function __construct()
    {
        parent::__construct();
        $this->master_db = $this->load->database('default', TRUE);
    }

    public function getAll($filters = array(), $limit = NULL, $offset = 0)
    {
        $this->master_db->from('erp_school_upi_qr');

        if (!empty($filters['vendor_id'])) {
            $this->master_db->where('vendor_id', (int)$filters['vendor_id']);
        }

        if (!empty($filters['school_id'])) {
            $this->master_db->where('school_id', (int)$filters['school_id']);
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $this->master_db->where('is_active', (int)$filters['is_active']);
        }

        $this->master_db->order_by('id', 'DESC');

        if ($limit !== NULL) {
            $this->master_db->limit((int)$limit, (int)$offset);
        }

        return $this->master_db->get()->result_array();
    }

    public function getTotal($filters = array())
    {
        $this->master_db->from('erp_school_upi_qr');

        if (!empty($filters['vendor_id'])) {
            $this->master_db->where('vendor_id', (int)$filters['vendor_id']);
        }

        if (!empty($filters['school_id'])) {
            $this->master_db->where('school_id', (int)$filters['school_id']);
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $this->master_db->where('is_active', (int)$filters['is_active']);
        }

        return (int)$this->master_db->count_all_results();
    }

    public function getById($id)
    {
        return $this->master_db
            ->from('erp_school_upi_qr')
            ->where('id', (int)$id)
            ->get()
            ->row_array();
    }

    public function getByIdForVendor($id, $vendor_id)
    {
        return $this->master_db
            ->from('erp_school_upi_qr')
            ->where('id', (int)$id)
            ->where('vendor_id', (int)$vendor_id)
            ->get()
            ->row_array();
    }

    public function create($data)
    {
        $this->master_db->insert('erp_school_upi_qr', $data);
        return (int)$this->master_db->insert_id();
    }

    public function update($id, $data)
    {
        return $this->master_db
            ->where('id', (int)$id)
            ->update('erp_school_upi_qr', $data);
    }

    public function updateForVendor($id, $vendor_id, $data)
    {
        return $this->master_db
            ->where('id', (int)$id)
            ->where('vendor_id', (int)$vendor_id)
            ->update('erp_school_upi_qr', $data);
    }

    public function delete($id)
    {
        return $this->master_db
            ->where('id', (int)$id)
            ->delete('erp_school_upi_qr');
    }

    public function deleteForVendor($id, $vendor_id)
    {
        return $this->master_db
            ->where('id', (int)$id)
            ->where('vendor_id', (int)$vendor_id)
            ->delete('erp_school_upi_qr');
    }

    public function activateForSchool($id, $vendor_id, $school_id, $updated_by = NULL)
    {
        $id = (int)$id;
        $vendor_id = (int)$vendor_id;
        $school_id = (int)$school_id;
        $active_school_key = $vendor_id . '-' . $school_id;

        $this->master_db->trans_start();

        // Deactivate existing active rows for same school.
        $this->master_db->where('vendor_id', $vendor_id)
            ->where('school_id', $school_id)
            ->where('is_active', 1)
            ->update('erp_school_upi_qr', array(
                'is_active' => 0,
                'active_school_key' => NULL,
                'updated_by' => $updated_by
            ));

        // Activate requested row.
        $this->master_db->where('id', $id)
            ->where('vendor_id', $vendor_id)
            ->where('school_id', $school_id)
            ->update('erp_school_upi_qr', array(
                'is_active' => 1,
                'active_school_key' => $active_school_key,
                'updated_by' => $updated_by
            ));

        $this->master_db->trans_complete();

        return $this->master_db->trans_status();
    }

    public function getActiveUpiOptionsByVendor($vendor_id)
    {
        $rows = $this->master_db
            ->select('id, school_id, upi_id, payment_note')
            ->from('erp_school_upi_qr')
            ->where('vendor_id', (int)$vendor_id)
            ->where('is_active', 1)
            ->order_by('school_id', 'ASC')
            ->order_by('id', 'DESC')
            ->get()
            ->result_array();

        $grouped = array();
        foreach ($rows as $row) {
            $school_id = (int)$row['school_id'];
            if (!isset($grouped[$school_id])) {
                $grouped[$school_id] = array();
            }

            $label = trim((string)$row['upi_id']);
            if ($label === '') {
                $label = 'QR #' . (int)$row['id'];
            }

            if (!empty($row['payment_note'])) {
                $label .= ' (' . $row['payment_note'] . ')';
            }

            $grouped[$school_id][] = array(
                'id' => (int)$row['id'],
                'upi_id' => (string)$row['upi_id'],
                'payment_note' => (string)$row['payment_note'],
                'label' => $label
            );
        }

        return $grouped;
    }

    public function isActiveUpiForVendorSchool($qr_id, $vendor_id, $school_id)
    {
        $count = $this->master_db
            ->from('erp_school_upi_qr')
            ->where('id', (int)$qr_id)
            ->where('vendor_id', (int)$vendor_id)
            ->where('school_id', (int)$school_id)
            ->where('is_active', 1)
            ->count_all_results();

        return (int)$count > 0;
    }
}
