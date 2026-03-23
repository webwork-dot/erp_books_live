<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH . 'controllers/Erp_admin/Erp_base.php');

/**
 * POS Agents Controller
 */
class Pos_agents extends Erp_base
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pos_agent_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        if (!$this->hasPermission('pos', 'read')) {
            show_error('You do not have permission to access this page.', 403);
        }

        $filters = array(
            'status' => $this->input->get('status', TRUE),
            'search' => trim((string)$this->input->get('search', TRUE))
        );

        $per_page = 10;
        $page = (int)$this->input->get('page');
        if ($page < 1) {
            $page = 1;
        }
        $offset = ($page - 1) * $per_page;

        $total_agents = $this->Pos_agent_model->getTotalAgents($filters);

        $data['agents'] = $this->Pos_agent_model->getAllAgents($filters, $per_page, $offset);
        $data['total_agents'] = $total_agents;
        $data['per_page'] = $per_page;
        $data['current_page'] = $page;
        $data['total_pages'] = (int)ceil($total_agents / $per_page);
        $data['filters'] = $filters;
        $data['title'] = 'POS Agents';
        $data['current_user'] = $this->current_user;

        $data['content'] = $this->load->view('erp_admin/pos_agents/index', $data, TRUE);
        $this->load->view('erp_admin/layouts/index_template', $data);
    }

    public function add()
    {
        if (!$this->hasPermission('pos', 'create')) {
            show_error('You do not have permission to access this page.', 403);
        }

        $this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[erp_agent_users.username]');
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|is_unique[erp_agent_users.email]');
        $this->form_validation->set_rules('vendor_id', 'Vendor', 'required|integer');
        $this->form_validation->set_rules('school_ids[]', 'Schools', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['vendors'] = $this->Pos_agent_model->getVendors();
            $data['selected_vendor_id'] = (int)$this->input->post('vendor_id');
            $data['schools'] = $data['selected_vendor_id'] > 0
                ? $this->Pos_agent_model->getVendorSchools($data['selected_vendor_id'])
                : array();
            $data['title'] = 'Add POS Agent';
            $data['current_user'] = $this->current_user;

            $data['content'] = $this->load->view('erp_admin/pos_agents/add', $data, TRUE);
            $this->load->view('erp_admin/layouts/index_template', $data);
            return;
        }

        $plain_password = $this->input->post('password', TRUE);
        if (empty($plain_password)) {
            $plain_password = $this->generateRandomPassword(10);
        }

        $agent_data = array(
            'username' => trim((string)$this->input->post('username', TRUE)),
            'email' => trim((string)$this->input->post('email', TRUE)),
            'password' => sha1($plain_password),
            'status' => $this->input->post('status') ? 1 : 0
        );

        $school_access_rows = $this->buildSchoolAccessRows((int)$this->input->post('vendor_id'));
        if (empty($school_access_rows)) {
            $this->session->set_flashdata('error', 'Please select at least one school.');
            redirect('erp-admin/pos-agents/add');
            return;
        }

        $agent_id = $this->Pos_agent_model->createAgent($agent_data, $school_access_rows);

        if ($agent_id) {
            $this->session->set_flashdata('success', 'POS agent created. Username: ' . $agent_data['username'] . ' | Password: ' . $plain_password);
            redirect('erp-admin/pos-agents');
            return;
        }

        $this->session->set_flashdata('error', 'Failed to create POS agent.');
        redirect('erp-admin/pos-agents/add');
    }

    public function edit($agent_user_id)
    {
        if (!$this->hasPermission('pos', 'update')) {
            show_error('You do not have permission to access this page.', 403);
        }

        $agent_user_id = (int)$agent_user_id;
        $agent = $this->Pos_agent_model->getAgentById($agent_user_id);
        if (!$agent) {
            show_404();
        }

        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
        $this->form_validation->set_rules('vendor_id', 'Vendor', 'required|integer');

        if ($this->form_validation->run() == FALSE) {
            $existing_access = $this->Pos_agent_model->getAgentSchoolAccess($agent_user_id);
            $selected_vendor_id = (int)$this->input->post('vendor_id');
            if ($selected_vendor_id <= 0 && !empty($existing_access)) {
                $selected_vendor_id = (int)$existing_access[0]['vendor_id'];
            }

            $data['agent'] = $agent;
            $data['existing_access'] = $existing_access;
            $data['vendors'] = $this->Pos_agent_model->getVendors();
            $data['selected_vendor_id'] = $selected_vendor_id;
            $data['schools'] = $selected_vendor_id > 0
                ? $this->Pos_agent_model->getVendorSchools($selected_vendor_id)
                : array();
            $data['title'] = 'Edit POS Agent';
            $data['current_user'] = $this->current_user;

            $data['content'] = $this->load->view('erp_admin/pos_agents/edit', $data, TRUE);
            $this->load->view('erp_admin/layouts/index_template', $data);
            return;
        }

        $agent_data = array(
            'email' => trim((string)$this->input->post('email', TRUE)),
            'status' => $this->input->post('status') ? 1 : 0
        );

        $new_password = trim((string)$this->input->post('password', TRUE));
        if ($new_password !== '') {
            $agent_data['password'] = sha1($new_password);
        }

        $school_access_rows = $this->buildSchoolAccessRows((int)$this->input->post('vendor_id'));

        if ($this->Pos_agent_model->updateAgent($agent_user_id, $agent_data, $school_access_rows)) {
            $this->session->set_flashdata('success', 'POS agent updated successfully.');
            redirect('erp-admin/pos-agents');
            return;
        }

        $this->session->set_flashdata('error', 'Failed to update POS agent.');
        redirect('erp-admin/pos-agents/edit/' . $agent_user_id);
    }

    public function delete($agent_user_id)
    {
        if (!$this->hasPermission('pos', 'delete')) {
            show_error('You do not have permission to access this page.', 403);
        }

        $agent_user_id = (int)$agent_user_id;
        if ($agent_user_id === (int)$this->session->userdata('erp_user_id')) {
            $this->session->set_flashdata('error', 'You cannot delete your own account.');
            redirect('erp-admin/pos-agents');
            return;
        }

        if ($this->Pos_agent_model->updateAgentStatus($agent_user_id, 0)) {
            $this->session->set_flashdata('success', 'POS agent deactivated successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to deactivate POS agent.');
        }

        redirect('erp-admin/pos-agents');
    }

    public function toggle_status($agent_user_id)
    {
        if (!$this->hasPermission('pos', 'update')) {
            show_error('You do not have permission to access this page.', 403);
        }

        $agent_user_id = (int)$agent_user_id;
        $status = (int)$this->input->post('status');
        $status = $status === 1 ? 1 : 0;

        if ($this->Pos_agent_model->updateAgentStatus($agent_user_id, $status)) {
            $this->session->set_flashdata('success', 'POS agent status updated successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to update POS agent status.');
        }

        redirect('erp-admin/pos-agents');
    }

    public function reset_credentials($agent_user_id)
    {
        if (!$this->hasPermission('pos', 'update')) {
            show_error('You do not have permission to access this page.', 403);
        }

        $agent_user_id = (int)$agent_user_id;
        $agent = $this->Pos_agent_model->getAgentById($agent_user_id);
        if (!$agent) {
            show_404();
        }

        $plain_password = $this->generateRandomPassword(10);
        $hashed_password = sha1($plain_password);

        $updated = $this->Pos_agent_model->updateAgent($agent_user_id, array('password' => $hashed_password), array(), FALSE);

        if ($updated) {
            $this->session->set_flashdata('success', 'Credentials reset. Username: ' . $agent['username'] . ' | Password: ' . $plain_password);
        } else {
            $this->session->set_flashdata('error', 'Failed to reset credentials.');
        }

        redirect('erp-admin/pos-agents');
    }

    public function get_schools_by_vendor($vendor_id)
    {
        if (!$this->hasPermission('pos', 'read')) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array('status' => 'error', 'message' => 'Permission denied')));
            return;
        }

        $vendor_id = (int)$vendor_id;
        $schools = $this->Pos_agent_model->getVendorSchools($vendor_id);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array(
                'status' => 'success',
                'schools' => $schools
            )));
    }

    private function buildSchoolAccessRows($vendor_id)
    {
        $vendor_id = (int)$vendor_id;
        $school_ids = $this->input->post('school_ids');
        $uniform_map = $this->input->post('category_uniform');
        $bookset_map = $this->input->post('category_bookset');

        if (!is_array($school_ids)) {
            return array();
        }

        $rows = array();
        foreach ($school_ids as $school_id) {
            $school_id = (int)$school_id;
            if ($school_id <= 0 || $vendor_id <= 0) {
                continue;
            }

            $can_uniform = isset($uniform_map[$school_id]) ? 1 : 0;
            $can_bookset = isset($bookset_map[$school_id]) ? 1 : 0;

            // Keep access usable even if both checkboxes are unchecked.
            if ($can_uniform === 0 && $can_bookset === 0) {
                continue;
            }

            $rows[] = array(
                'vendor_id' => $vendor_id,
                'school_id' => $school_id,
                'can_uniform' => $can_uniform,
                'can_bookset' => $can_bookset,
                'status' => 1,
                'created_by' => (int)$this->session->userdata('erp_user_id'),
                'updated_by' => (int)$this->session->userdata('erp_user_id')
            );
        }

        return $rows;
    }

    private function generateRandomPassword($length = 10)
    {
        $pool = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789@#$%';
        $pool_len = strlen($pool);
        $password = '';

        for ($i = 0; $i < $length; $i++) {
            $password .= $pool[random_int(0, $pool_len - 1)];
        }

        return $password;
    }
}
