<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH . 'controllers/Vendor/Vendor_base.php');

/**
 * Vendor POS Agents Controller
 */
class Pos_agents extends Vendor_base
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pos_agent_model');
        $this->load->model('Pos_school_qr_model');
        $this->load->library('form_validation');

            // if (!$this->checkFeatureAccess('pos')) {
            //     show_error('POS module is not enabled for this vendor.', 403);
            // }
    }

    public function index()
    {
        $vendor_id = (int)$this->current_vendor['id'];
        $filters = array(
            'vendor_id' => $vendor_id,
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
        $data['current_vendor'] = $this->current_vendor;
        $data['vendor_domain'] = $this->getVendorDomainForUrl();

        $data['content'] = $this->load->view('vendor/pos_agents/index', $data, TRUE);
        $this->load->view('vendor/layouts/index_template', $data);
    }

    public function add()
    {
        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
        $this->form_validation->set_rules('school_ids[]', 'Schools', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['schools'] = $this->Pos_agent_model->getCurrentVendorSchools();
            $data['upi_options_by_school'] = $this->Pos_school_qr_model->getActiveUpiOptionsByVendor((int)$this->current_vendor['id']);
            $data['title'] = 'Add POS Agent';
            $data['current_vendor'] = $this->current_vendor;
            $data['vendor_domain'] = $this->getVendorDomainForUrl();

            $data['content'] = $this->load->view('vendor/pos_agents/add', $data, TRUE);
            $this->load->view('vendor/layouts/index_template', $data);
            return;
        }

        $username = trim((string)$this->input->post('username', TRUE));
        $email = trim((string)$this->input->post('email', TRUE));

        if ($this->Pos_agent_model->usernameExists($username)) {
            $this->session->set_flashdata('error', 'Username is already in use.');
            redirect('pos-agents/add');
            return;
        }

        if ($email !== '' && $this->Pos_agent_model->emailExists($email)) {
            $this->session->set_flashdata('error', 'Email is already in use.');
            redirect('pos-agents/add');
            return;
        }

        $plain_password = trim((string)$this->input->post('password', TRUE));
        if ($plain_password === '') {
            $plain_password = $this->generateRandomPassword(10);
        }

        $actor_id = (int)$this->current_vendor['id'];
        $vendor_id = (int)$this->current_vendor['id'];

        $agent_data = array(
            'username' => $username,
            'email' => $email,
            'password' => sha1($plain_password),
            'status' => $this->input->post('status') ? 1 : 0,
            'created_by' => $actor_id,
            'updated_by' => $actor_id
        );

        $school_access_rows = $this->buildSchoolAccessRows($vendor_id, $actor_id);
        if (empty($school_access_rows)) {
            $this->session->set_flashdata('error', 'Please select at least one school and category access.');
            redirect('pos-agents/add');
            return;
        }

        $agent_id = $this->Pos_agent_model->createAgent($agent_data, $school_access_rows);

        if ($agent_id) {
            $this->session->set_flashdata('success', 'POS agent created. Username: ' . $agent_data['username'] . ' | Password: ' . $plain_password);
            redirect('pos-agents');
            return;
        }

        $this->session->set_flashdata('error', 'Failed to create POS agent.');
        redirect('pos-agents/add');
    }

    public function edit($agent_user_id)
    {
        $agent_user_id = (int)$agent_user_id;
        $vendor_id = (int)$this->current_vendor['id'];

        if (!$this->Pos_agent_model->isAgentMappedToVendor($agent_user_id, $vendor_id)) {
            show_404();
        }

        $agent = $this->Pos_agent_model->getAgentById($agent_user_id);
        if (!$agent) {
            show_404();
        }

        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email');

        if ($this->form_validation->run() == FALSE) {
            $data['agent'] = $agent;
            $data['existing_access'] = $this->Pos_agent_model->getAgentSchoolAccessByVendor($agent_user_id, $vendor_id);
            $data['schools'] = $this->Pos_agent_model->getCurrentVendorSchools();
            $data['upi_options_by_school'] = $this->Pos_school_qr_model->getActiveUpiOptionsByVendor($vendor_id);
            $data['title'] = 'Edit POS Agent';
            $data['current_vendor'] = $this->current_vendor;
            $data['vendor_domain'] = $this->getVendorDomainForUrl();

            $data['content'] = $this->load->view('vendor/pos_agents/edit', $data, TRUE);
            $this->load->view('vendor/layouts/index_template', $data);
            return;
        }

        $email = trim((string)$this->input->post('email', TRUE));
        if ($email !== '' && $this->Pos_agent_model->emailExists($email, $agent_user_id)) {
            $this->session->set_flashdata('error', 'Email is already in use.');
            redirect('pos-agents/edit/' . $agent_user_id);
            return;
        }

        $actor_id = (int)$this->current_vendor['id'];

        $agent_data = array(
            'email' => $email,
            'status' => $this->input->post('status') ? 1 : 0,
            'updated_by' => $actor_id
        );

        $new_password = trim((string)$this->input->post('password', TRUE));
        if ($new_password !== '') {
            $agent_data['password'] = sha1($new_password);
        }

        $school_access_rows = $this->buildSchoolAccessRows($vendor_id, $actor_id);

        if ($this->Pos_agent_model->updateAgent($agent_user_id, $agent_data, $school_access_rows)) {
            $this->session->set_flashdata('success', 'POS agent updated successfully.');
            redirect('pos-agents');
            return;
        }

        $this->session->set_flashdata('error', 'Failed to update POS agent.');
        redirect('pos-agents/edit/' . $agent_user_id);
    }

    public function delete($agent_user_id)
    {
        $agent_user_id = (int)$agent_user_id;
        $vendor_id = (int)$this->current_vendor['id'];

        if (!$this->Pos_agent_model->isAgentMappedToVendor($agent_user_id, $vendor_id)) {
            show_404();
        }

        if ($this->Pos_agent_model->updateAgentStatus($agent_user_id, 0)) {
            $this->session->set_flashdata('success', 'POS agent deactivated successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to deactivate POS agent.');
        }

        redirect('pos-agents');
    }

    public function reset_credentials($agent_user_id)
    {
        $agent_user_id = (int)$agent_user_id;
        $vendor_id = (int)$this->current_vendor['id'];

        if (!$this->Pos_agent_model->isAgentMappedToVendor($agent_user_id, $vendor_id)) {
            show_404();
        }

        $agent = $this->Pos_agent_model->getAgentById($agent_user_id);
        if (!$agent) {
            show_404();
        }

        $plain_password = $this->generateRandomPassword(10);
        $updated = $this->Pos_agent_model->updateAgent(
            $agent_user_id,
            array(
                'password' => sha1($plain_password),
                'updated_by' => (int)$this->current_vendor['id']
            ),
            array(),
            FALSE
        );

        if ($updated) {
            $this->session->set_flashdata('success', 'Credentials reset. Username: ' . $agent['username'] . ' | Password: ' . $plain_password);
        } else {
            $this->session->set_flashdata('error', 'Failed to reset credentials.');
        }

        redirect('pos-agents');
    }

    public function get_vendor_schools()
    {
        $schools = $this->Pos_agent_model->getCurrentVendorSchools();

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array(
                'status' => 'success',
                'schools' => $schools
            )));
    }

    private function buildSchoolAccessRows($vendor_id, $actor_id)
    {
        $school_ids = $this->input->post('school_ids');
        $category_map = $this->input->post('category_access');
        $upi_map = $this->input->post('upi_qr_id');

        if (!is_array($school_ids)) {
            return array();
        }

        $rows = array();
        foreach ($school_ids as $school_id) {
            $school_id = (int)$school_id;
            if ($school_id <= 0 || $vendor_id <= 0) {
                continue;
            }

            $category_value = '';
            if (is_array($category_map) && isset($category_map[$school_id])) {
                $category_value = strtolower(trim((string)$category_map[$school_id]));
            }

            $can_uniform = 0;
            $can_bookset = 0;

            if ($category_value === 'uniform') {
                $can_uniform = 1;
            } elseif ($category_value === 'bookset') {
                $can_bookset = 1;
            } elseif ($category_value === 'both') {
                $can_uniform = 1;
                $can_bookset = 1;
            }

            if ($can_uniform === 0 && $can_bookset === 0) {
                continue;
            }

            $selected_upi_qr_id = NULL;
            if (is_array($upi_map) && isset($upi_map[$school_id])) {
                $candidate_qr_id = (int)$upi_map[$school_id];
                if ($candidate_qr_id > 0 && $this->Pos_school_qr_model->isActiveUpiForVendorSchool($candidate_qr_id, $vendor_id, $school_id)) {
                    $selected_upi_qr_id = $candidate_qr_id;
                }
            }

            $rows[] = array(
                'vendor_id' => $vendor_id,
                'school_id' => $school_id,
                'can_uniform' => $can_uniform,
                'can_bookset' => $can_bookset,
                'upi_qr_id' => $selected_upi_qr_id,
                'status' => 1,
                'created_by' => $actor_id,
                'updated_by' => $actor_id
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
