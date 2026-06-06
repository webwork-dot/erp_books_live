<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * House Model
 *
 * Handles database operations for house management (erp_houses table).
 * Houses are vendor-agnostic (global), similar to the classes table.
 *
 * @package     ERP
 * @subpackage  Models
 * @category    Models
 */
class House_model extends CI_Model
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all active houses
     *
     * @return array
     */
    public function getAllHouses()
    {
        $this->db->where('status', 'active');
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get('erp_houses');
        return $query->result_array();
    }

    /**
     * Get house by ID
     *
     * @param  int $id
     * @return array|NULL
     */
    public function getHouseById($id)
    {
        $this->db->where('id', (int)$id);
        $query = $this->db->get('erp_houses');
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return NULL;
    }

    /**
     * Get multiple houses by array of IDs
     *
     * @param  array $ids
     * @return array
     */
    public function getHousesByIds(array $ids)
    {
        if (empty($ids)) {
            return array();
        }
        $ids = array_filter(array_map('intval', $ids));
        if (empty($ids)) {
            return array();
        }
        $this->db->where_in('id', $ids);
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get('erp_houses');
        return $query->result_array();
    }

    /**
     * Create a new house
     *
     * @param  array $data  ['name', 'color_code', 'status']
     * @return int|FALSE    New house ID on success
     */
    public function createHouse($data)
    {
        $allowed = array('name', 'color_code', 'status');
        $insert  = array_intersect_key($data, array_flip($allowed));
        if (empty($insert['name'])) {
            return FALSE;
        }
        if (!isset($insert['status'])) {
            $insert['status'] = 'active';
        }
        $this->db->insert('erp_houses', $insert);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        }
        return FALSE;
    }

    /**
     * Update a house
     *
     * @param  int   $id
     * @param  array $data
     * @return bool
     */
    public function updateHouse($id, $data)
    {
        $allowed = array('name', 'color_code', 'status');
        $update  = array_intersect_key($data, array_flip($allowed));
        if (empty($update)) {
            return FALSE;
        }
        $this->db->where('id', (int)$id);
        $this->db->update('erp_houses', $update);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Delete a house (hard delete)
     *
     * @param  int $id
     * @return bool
     */
    public function deleteHouse($id)
    {
        $this->db->where('id', (int)$id);
        $this->db->delete('erp_houses');
        return $this->db->affected_rows() > 0;
    }
}
