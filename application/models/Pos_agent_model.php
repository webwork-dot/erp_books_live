<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * POS Agent Model
 *
 * Handles POS agent CRUD and school/category mapping.
 */
class Pos_agent_model extends CI_Model
{
    private $master_db;

    public function __construct()
    {
        parent::__construct();
        $this->master_db = $this->load->database('default', TRUE);
    }

    public function getAllAgents($filters = array(), $limit = NULL, $offset = 0)
    {
        $this->master_db->select('u.id, u.username, u.email, u.status, u.created_at');
        $this->master_db->from('erp_agent_users u');

        if (isset($filters['status']) && $filters['status'] !== '') {
            $this->master_db->where('u.status', (int)$filters['status']);
        }

        if (!empty($filters['vendor_id'])) {
            $this->master_db->join('erp_pos_agent_school_access sa', 'sa.agent_user_id = u.id', 'inner');
            $this->master_db->where('sa.vendor_id', (int)$filters['vendor_id']);
            $this->master_db->group_by('u.id');
        }

        if (!empty($filters['search'])) {
            $this->master_db->group_start();
            $this->master_db->like('u.username', $filters['search']);
            $this->master_db->or_like('u.email', $filters['search']);
            $this->master_db->group_end();
        }

        $this->master_db->order_by('u.created_at', 'DESC');

        if ($limit !== NULL) {
            $this->master_db->limit((int)$limit, (int)$offset);
        }

        return $this->master_db->get()->result_array();
    }

    public function getTotalAgents($filters = array())
    {
        $this->master_db->select('COUNT(DISTINCT u.id) AS total_rows', FALSE);
        $this->master_db->from('erp_agent_users u');

        if (isset($filters['status']) && $filters['status'] !== '') {
            $this->master_db->where('u.status', (int)$filters['status']);
        }

        if (!empty($filters['vendor_id'])) {
            $this->master_db->join('erp_pos_agent_school_access sa', 'sa.agent_user_id = u.id', 'inner');
            $this->master_db->where('sa.vendor_id', (int)$filters['vendor_id']);
        }

        if (!empty($filters['search'])) {
            $this->master_db->group_start();
            $this->master_db->like('u.username', $filters['search']);
            $this->master_db->or_like('u.email', $filters['search']);
            $this->master_db->group_end();
        }

        $row = $this->master_db->get()->row_array();
        return isset($row['total_rows']) ? (int)$row['total_rows'] : 0;
    }

    public function getAgentById($agent_user_id)
    {
        return $this->master_db
            ->select('u.*')
            ->from('erp_agent_users u')
            ->where('u.id', (int)$agent_user_id)
            ->get()
            ->row_array();
    }

    public function createAgent($agent_data, $school_access_rows = array())
    {
        $this->master_db->trans_start();

        $this->master_db->insert('erp_agent_users', $agent_data);
        $agent_user_id = (int)$this->master_db->insert_id();

        if ($agent_user_id > 0 && !empty($school_access_rows)) {
            foreach ($school_access_rows as &$row) {
                $row['agent_user_id'] = $agent_user_id;
            }
            unset($row);

            $school_access_rows = $this->sanitizeSchoolAccessRows($school_access_rows);
            if (!empty($school_access_rows)) {
                $this->master_db->insert_batch('erp_pos_agent_school_access', $school_access_rows);
            }
        }

        $this->master_db->trans_complete();

        if ($this->master_db->trans_status() === FALSE || $agent_user_id <= 0) {
            return FALSE;
        }

        return $agent_user_id;
    }

    public function updateAgent($agent_user_id, $agent_data, $school_access_rows = array(), $replace_school_access = TRUE)
    {
        $agent_user_id = (int)$agent_user_id;

        $this->master_db->trans_start();

        if (!empty($agent_data)) {
            $this->master_db->where('id', $agent_user_id)->update('erp_agent_users', $agent_data);
        }

        if ($replace_school_access) {
            $this->master_db->where('agent_user_id', $agent_user_id)->delete('erp_pos_agent_school_access');

            if (!empty($school_access_rows)) {
                foreach ($school_access_rows as &$row) {
                    $row['agent_user_id'] = $agent_user_id;
                }
                unset($row);

                $school_access_rows = $this->sanitizeSchoolAccessRows($school_access_rows);
                if (!empty($school_access_rows)) {
                    $this->master_db->insert_batch('erp_pos_agent_school_access', $school_access_rows);
                }
            }
        }

        $this->master_db->trans_complete();

        return $this->master_db->trans_status();
    }

    public function updateAgentStatus($agent_user_id, $status)
    {
        return $this->master_db
            ->where('id', (int)$agent_user_id)
            ->update('erp_agent_users', array('status' => (int)$status));
    }

    public function getAgentSchoolAccess($agent_user_id)
    {
        return $this->master_db
            ->from('erp_pos_agent_school_access')
            ->where('agent_user_id', (int)$agent_user_id)
            ->order_by('id', 'ASC')
            ->get()
            ->result_array();
    }

    public function getAgentSchoolAccessByVendor($agent_user_id, $vendor_id)
    {
        return $this->master_db
            ->from('erp_pos_agent_school_access')
            ->where('agent_user_id', (int)$agent_user_id)
            ->where('vendor_id', (int)$vendor_id)
            ->order_by('id', 'ASC')
            ->get()
            ->result_array();
    }

    public function isAgentMappedToVendor($agent_user_id, $vendor_id)
    {
        $count = $this->master_db
            ->from('erp_pos_agent_school_access')
            ->where('agent_user_id', (int)$agent_user_id)
            ->where('vendor_id', (int)$vendor_id)
            ->count_all_results();

        return (int)$count > 0;
    }

    public function getVendors()
    {
        return $this->master_db
            ->select('id, name, username, database_name, status')
            ->from('erp_clients')
            ->where('status', 'active')
            ->order_by('name', 'ASC')
            ->get()
            ->result_array();
    }

    public function getVendorById($vendor_id)
    {
        return $this->master_db
            ->select('id, name, username, domain, database_name, status')
            ->from('erp_clients')
            ->where('id', (int)$vendor_id)
            ->get()
            ->row_array();
    }

    public function getCurrentVendorSchools()
    {
        return $this->db
            ->select('id, school_name, status')
            ->from('erp_schools')
            ->where_in('status', array(1, '1', 'active'))
            ->order_by('school_name', 'ASC')
            ->get()
            ->result_array();
    }

    public function usernameExists($username, $exclude_agent_id = 0)
    {
        $this->master_db->from('erp_agent_users');
        $this->master_db->where('username', trim((string)$username));

        if ((int)$exclude_agent_id > 0) {
            $this->master_db->where('id !=', (int)$exclude_agent_id);
        }

        return (int)$this->master_db->count_all_results() > 0;
    }

    public function emailExists($email, $exclude_agent_id = 0)
    {
        $email = trim((string)$email);
        if ($email === '') {
            return FALSE;
        }

        $this->master_db->from('erp_agent_users');
        $this->master_db->where('email', $email);

        if ((int)$exclude_agent_id > 0) {
            $this->master_db->where('id !=', (int)$exclude_agent_id);
        }

        return (int)$this->master_db->count_all_results() > 0;
    }

    public function getVendorSchools($vendor_id)
    {
        $vendor = $this->getVendorById($vendor_id);
        if (empty($vendor) || empty($vendor['database_name'])) {
            return array();
        }

        $vendor_db = $this->load->database(array(
            'hostname' => $this->db->hostname,
            'username' => $this->db->username,
            'password' => $this->db->password,
            'database' => $vendor['database_name'],
            'dbdriver' => 'mysqli',
            'dbprefix' => '',
            'pconnect' => FALSE,
            'db_debug' => (ENVIRONMENT !== 'production'),
            'cache_on' => FALSE,
            'cachedir' => '',
            'char_set' => 'utf8',
            'dbcollat' => 'utf8_general_ci'
        ), TRUE);

        if (!$vendor_db || $vendor_db->conn_id === FALSE) {
            return array();
        }

        $schools = $vendor_db
            ->select('id, school_name, status')
            ->from('erp_schools')
            ->where_in('status', array(1, '1', 'active'))
            ->order_by('school_name', 'ASC')
            ->get()
            ->result_array();

        $vendor_db->close();

        return $schools;
    }

    private function sanitizeSchoolAccessRows($rows)
    {
        if (empty($rows)) {
            return array();
        }

        $columns = $this->master_db->list_fields('erp_pos_agent_school_access');
        if (empty($columns)) {
            return $rows;
        }

        $allowed = array_flip($columns);
        $clean_rows = array();

        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }
            $clean_rows[] = array_intersect_key($row, $allowed);
        }

        return $clean_rows;
    }
}
