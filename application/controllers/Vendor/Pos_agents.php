<?php
defined('BASEPATH') or exit('No direct script access allowed');

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

        $agents = $this->Pos_agent_model->getAllAgents($filters, $per_page, $offset);

        if (!empty($agents)) {
            $agent_ids = array_values(array_filter(array_map(function ($agent) {
                return isset($agent['id']) ? (int)$agent['id'] : 0;
            }, $agents)));

            $agent_access_map = $this->Pos_agent_model->getAssignedAccessByAgentIds($vendor_id, $agent_ids);

            $all_school_ids = array();
            $all_qr_ids = array();
            foreach ($agent_access_map as $rows) {
                if (empty($rows) || !is_array($rows)) {
                    continue;
                }

                foreach ($rows as $row) {
                    if (!empty($row['school_id'])) {
                        $all_school_ids[] = (int)$row['school_id'];
                    }
                    if (!empty($row['upi_qr_id'])) {
                        $all_qr_ids[] = (int)$row['upi_qr_id'];
                    }
                }
            }

            $all_school_ids = array_values(array_unique(array_map('intval', $all_school_ids)));
            $all_qr_ids = array_values(array_unique(array_map('intval', $all_qr_ids)));

            $school_name_map = $this->Pos_agent_model->getSchoolNameMapByIds($all_school_ids);
            $upi_id_map = $this->Pos_agent_model->getUpiIdMapByQrIds($all_qr_ids);

            foreach ($agents as &$agent) {
                $aid = isset($agent['id']) ? (int)$agent['id'] : 0;
                $assigned_rows = isset($agent_access_map[$aid]) ? $agent_access_map[$aid] : array();

                $school_names = array();
                $category_labels = array();
                $upi_ids = array();

                foreach ($assigned_rows as $row) {
                    $sid = isset($row['school_id']) ? (int)$row['school_id'] : 0;
                    if ($sid > 0 && isset($school_name_map[$sid]) && $school_name_map[$sid] !== '' && !in_array($school_name_map[$sid], $school_names, TRUE)) {
                        $school_names[] = $school_name_map[$sid];
                    }

                    $can_uniform = isset($row['can_uniform']) ? (int)$row['can_uniform'] : 0;
                    $can_bookset = isset($row['can_bookset']) ? (int)$row['can_bookset'] : 0;
                    $category_label = '';
                    if ($can_uniform === 1 && $can_bookset === 1) {
                        $category_label = 'Uniform + Bookset';
                    } elseif ($can_uniform === 1) {
                        $category_label = 'Uniform Only';
                    } elseif ($can_bookset === 1) {
                        $category_label = 'Bookset Only';
                    }

                    if ($category_label !== '' && !in_array($category_label, $category_labels, TRUE)) {
                        $category_labels[] = $category_label;
                    }

                    $qr_id = isset($row['upi_qr_id']) ? (int)$row['upi_qr_id'] : 0;
                    if ($qr_id > 0 && isset($upi_id_map[$qr_id]) && $upi_id_map[$qr_id] !== '' && !in_array($upi_id_map[$qr_id], $upi_ids, TRUE)) {
                        $upi_ids[] = $upi_id_map[$qr_id];
                    }
                }

                $agent['assigned_schools_preview'] = implode(', ', array_slice($school_names, 0, 3));
                $agent['assigned_schools_more_count'] = max(count($school_names) - 3, 0);

                $agent['assigned_categories_preview'] = implode(', ', array_slice($category_labels, 0, 3));
                $agent['assigned_categories_more_count'] = max(count($category_labels) - 3, 0);

                $agent['assigned_upi_preview'] = implode(', ', array_slice($upi_ids, 0, 3));
                $agent['assigned_upi_more_count'] = max(count($upi_ids) - 3, 0);
            }
            unset($agent);
        }

        $data['agents'] = $agents;
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

    public function stock($agent_user_id)
    {
        $agent_user_id = (int)$agent_user_id;
        $vendor_id = (int)$this->current_vendor['id'];
        if ($agent_user_id <= 0 || !$this->Pos_agent_model->isAgentMappedToVendor($agent_user_id, $vendor_id)) {
            show_404();
        }

        $agent = $this->Pos_agent_model->getAgentById($agent_user_id);
        if (!$agent) {
            show_404();
        }

        $data['agent'] = $agent;
        $data['title'] = 'POS Agent Stock';
        $data['current_vendor'] = $this->current_vendor;
        $data['vendor_domain'] = $this->getVendorDomainForUrl();
        $data['content'] = $this->load->view('vendor/pos_agents/stock', $data, TRUE);
        $this->load->view('vendor/layouts/index_template', $data);
    }

    public function add()
    {
        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[6]');
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

        if ($this->Pos_agent_model->softDeleteAgent($agent_user_id, $vendor_id)) {
            $this->session->set_flashdata('success', 'POS agent deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete POS agent.');
        }

        redirect('pos-agents');
    }

    public function delete_agent()
    {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            show_404();
        }

        $agent_user_id = (int)$this->input->post('agent_user_id');
        $vendor_id = (int)$this->current_vendor['id'];

        if ($agent_user_id <= 0) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array(
                    'status' => 'error',
                    'message' => 'Invalid agent id.'
                )));
        }

        if (!$this->Pos_agent_model->isAgentMappedToVendor($agent_user_id, $vendor_id)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array(
                    'status' => 'error',
                    'message' => 'Agent not found.'
                )));
        }

        $deleted = $this->Pos_agent_model->softDeleteAgent($agent_user_id, $vendor_id);

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array(
                'status' => $deleted ? 'success' : 'error',
                'message' => $deleted ? 'POS agent deleted successfully.' : 'Failed to delete POS agent.'
            )));
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

    public function toggle_status()
    {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            show_404();
        }

        $agent_user_id = (int)$this->input->post('agent_user_id');
        $status = (int)$this->input->post('status');
        $vendor_id = (int)$this->current_vendor['id'];

        if ($agent_user_id <= 0 || !in_array($status, array(0, 1), TRUE)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array(
                    'status' => 'error',
                    'message' => 'Invalid request data.'
                )));
        }

        if (!$this->Pos_agent_model->isAgentMappedToVendor($agent_user_id, $vendor_id)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array(
                    'status' => 'error',
                    'message' => 'Agent not found.'
                )));
        }

        $updated = $this->Pos_agent_model->updateAgentStatus($agent_user_id, $status);

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array(
                'status' => $updated ? 'success' : 'error',
                'message' => $updated ? 'Status updated successfully.' : 'Failed to update status.'
            )));
    }

    public function stock_catalog()
    {
        $vendor_id = (int)$this->current_vendor['id'];
        $agent_id = (int)$this->input->get('agent_user_id', TRUE);
        $q = trim((string)$this->input->get('q', TRUE));
        if ($q === '') {
            return $this->output->set_content_type('application/json')->set_output(json_encode(array('status' => 'success', 'items' => array())));
        }

        $rows = $this->buildStockCatalogRows($vendor_id);
        $items = array();
        foreach ($rows as $row) {
            $hay = strtolower($row['product_name'] . ' ' . $row['variation_key'] . ' ' . $row['school_name'] . ' ' . $row['board_name']);
            if (strpos($hay, strtolower($q)) === FALSE) {
                continue;
            }
            $row['main_qty'] = $this->getSnapshotQty(
                $this->getMainAdminLocationId(),
                $row['item_type'],
                (int)$row['item_ref_id'],
                (string)$row['variation_key'],
                isset($row['school_id']) ? $row['school_id'] : NULL,
                isset($row['branch_id']) ? $row['branch_id'] : NULL
            );

            $row['agent_qty'] = $agent_id > 0 ? $this->getSnapshotQty(
                $this->getOrCreateAgentLocationId($agent_id),
                $row['item_type'],
                (int)$row['item_ref_id'],
                (string)$row['variation_key'],
                isset($row['school_id']) ? $row['school_id'] : NULL,
                isset($row['branch_id']) ? $row['branch_id'] : NULL
            ) : 0;
            $items[] = $row;
            if (count($items) >= 5) {
                break;
            }
        }
        return $this->output->set_content_type('application/json')->set_output(json_encode(array('status' => 'success', 'items' => $items)));
    }

    public function stock_summary()
    {
        $agent_id = (int)$this->input->get('agent_user_id', TRUE);
        $vendor_id = (int)$this->current_vendor['id'];
        if ($agent_id <= 0 || !$this->Pos_agent_model->isAgentMappedToVendor($agent_id, $vendor_id)) {
            return $this->output->set_content_type('application/json')->set_output(json_encode(array('status' => 'error', 'message' => 'Invalid agent')));
        }
        $agent_location_id = $this->getAgentLocationId($agent_id);
        if (!$agent_location_id) {
            return $this->output->set_content_type('application/json')->set_output(json_encode(array('status' => 'success', 'holdings' => array(), 'movements' => array())));
        }

        $holdings = $this->db
            ->select('item_type,item_ref_id,variation_key,school_id,branch_id,qty_available,updated_at')
            ->from('inventory_stock_snapshot')
            ->where('location_id', $agent_location_id)
            ->order_by('updated_at', 'DESC')
            ->limit(100)
            ->get()->result_array();

        $movements = $this->db
            ->select('movement_type,item_type,item_ref_id,variation_key,qty_delta,qty_before,qty_after,created_at,remarks')
            ->from('inventory_stock_movements')
            ->where('location_id', $agent_location_id)
            ->order_by('id', 'DESC')
            ->limit(100)
            ->get()->result_array();

        $catalog = $this->buildStockCatalogRows($vendor_id);
        $catalog_map = array();
        foreach ($catalog as $c) {
            $ckey = strtolower((string)$c['item_type']) . '|' . (int)$c['item_ref_id'] . '|' . strtolower((string)($c['variation_key'] ?: 'default')) . '|' . (int)(isset($c['school_id']) ? $c['school_id'] : 0) . '|' . (int)(isset($c['branch_id']) ? $c['branch_id'] : 0);
            $catalog_map[$ckey] = $c;
        }

        $stats_map = array();
        foreach ($movements as $m) {
            $mkey = strtolower((string)$m['item_type']) . '|' . (int)$m['item_ref_id'] . '|' . strtolower((string)($m['variation_key'] ?: 'default'));
            if (!isset($stats_map[$mkey])) {
                $stats_map[$mkey] = array(
                    'total_qty' => 0.0,
                    'sold_qty' => 0.0,
                    'last_assigned_date' => ''
                );
            }
            if ((string)$m['movement_type'] === 'pos_assign_in' && (float)$m['qty_delta'] > 0) {
                $stats_map[$mkey]['total_qty'] += (float)$m['qty_delta'];
                if (empty($stats_map[$mkey]['last_assigned_date']) || strtotime($m['created_at']) > strtotime($stats_map[$mkey]['last_assigned_date'])) {
                    $stats_map[$mkey]['last_assigned_date'] = (string)$m['created_at'];
                }
            }
            // "Sold Qty" kept simple as all outflow from agent stock.
            if ((float)$m['qty_delta'] < 0) {
                $stats_map[$mkey]['sold_qty'] += abs((float)$m['qty_delta']);
            }
        }

        $holdings_enriched = array();
        foreach ($holdings as $h) {
            $hkey = strtolower((string)$h['item_type']) . '|' . (int)$h['item_ref_id'] . '|' . strtolower((string)($h['variation_key'] ?: 'default')) . '|' . (int)(isset($h['school_id']) ? $h['school_id'] : 0) . '|' . (int)(isset($h['branch_id']) ? $h['branch_id'] : 0);
            $meta = isset($catalog_map[$hkey]) ? $catalog_map[$hkey] : array();
            $stats = isset($stats_map[$hkey]) ? $stats_map[$hkey] : array('total_qty' => (float)$h['qty_available'], 'sold_qty' => 0.0, 'last_assigned_date' => '');
            $holdings_enriched[] = array(
                'item_type' => (string)$h['item_type'],
                'item_ref_id' => (int)$h['item_ref_id'],
                'product_name' => isset($meta['product_name']) ? (string)$meta['product_name'] : ('Item #' . (int)$h['item_ref_id']),
                'uniform_type_name' => isset($meta['uniform_type_name']) ? (string)$meta['uniform_type_name'] : '-',
                'variation_key' => (string)$h['variation_key'],
                'gender' => isset($meta['gender']) ? (string)$meta['gender'] : '-',
                'school_name' => isset($meta['school_name']) ? (string)$meta['school_name'] : '-',
                'branch_name' => isset($meta['branch_name']) ? (string)$meta['branch_name'] : '',
                'board_name' => isset($meta['board_name']) ? (string)$meta['board_name'] : '-',
                'grade_name' => isset($meta['grade_name']) ? (string)$meta['grade_name'] : '-',
                'total_qty' => (float)$stats['total_qty'],
                'sold_qty' => (float)$stats['sold_qty'],
                'remain_qty' => (float)$h['qty_available'],
                'last_assigned_date' => !empty($stats['last_assigned_date']) ? date('d-m-Y h:i:s A', strtotime($stats['last_assigned_date'])) : '-',
                'updated_at' => !empty($h['updated_at']) ? date('d-m-Y h:i:s A', strtotime($h['updated_at'])) : '-'
            );
        }

        $movements_enriched = array();
        foreach ($movements as $m) {
            $mkey = strtolower((string)$m['item_type']) . '|' . (int)$m['item_ref_id'] . '|' . strtolower((string)($m['variation_key'] ?: 'default'));
            $meta = isset($catalog_map[$mkey]) ? $catalog_map[$mkey] : array();
            $movements_enriched[] = array(
                'movement_type' => (string)$m['movement_type'],
                'product_name' => isset($meta['product_name']) ? (string)$meta['product_name'] : ('Item #' . (int)$m['item_ref_id']),
                'variation_key' => (string)$m['variation_key'],
                'qty' => abs((float)$m['qty_delta']),
                'direction' => ((float)$m['qty_delta'] >= 0 ? 'IN' : 'OUT'),
                'created_at' => !empty($m['created_at']) ? date('d-m-Y h:i:s A', strtotime($m['created_at'])) : '-',
                'remarks' => (string)$m['remarks']
            );
        }

        return $this->output->set_content_type('application/json')->set_output(json_encode(array('status' => 'success', 'holdings' => $holdings_enriched, 'movements' => $movements_enriched)));
    }

    public function stock_transfer()
    {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            show_404();
        }
        $vendor_id = (int)$this->current_vendor['id'];
        $agent_id = (int)$this->input->post('agent_user_id', TRUE);
        $item_type = trim((string)$this->input->post('item_type', TRUE));
        $item_ref_id = (int)$this->input->post('item_ref_id', TRUE);
        $variation_key = trim((string)$this->input->post('variation_key', TRUE));
        $school_id = $this->input->post('school_id', TRUE);
        $branch_id = $this->input->post('branch_id', TRUE);
        $action = trim((string)$this->input->post('action', TRUE)); // assign|return
        $qty = (float)$this->input->post('qty', TRUE);
        $remarks = trim((string)$this->input->post('remarks', TRUE));
        if ($variation_key === '') $variation_key = 'default';
        $school_id = ($school_id === NULL || $school_id === '' ? NULL : (int)$school_id);
        $branch_id = ($branch_id === NULL || $branch_id === '' ? NULL : (int)$branch_id);

        if (strtolower($item_type) === 'uniform' && (int)$school_id <= 0) {
            return $this->output->set_content_type('application/json')->set_output(json_encode(array('status' => 'error', 'message' => 'School is required for uniform stock.')));
        }
        if (!in_array($action, array('assign', 'return'), TRUE) || $agent_id <= 0 || $item_type === '' || $item_ref_id <= 0 || $qty <= 0) {
            return $this->output->set_content_type('application/json')->set_output(json_encode(array('status' => 'error', 'message' => 'Invalid request')));
        }
        if (!$this->Pos_agent_model->isAgentMappedToVendor($agent_id, $vendor_id)) {
            return $this->output->set_content_type('application/json')->set_output(json_encode(array('status' => 'error', 'message' => 'Agent not mapped')));
        }

        $admin_location_id = $this->getMainAdminLocationId();
        $agent_location_id = $this->getOrCreateAgentLocationId($agent_id);
        $admin_qty = $this->getSnapshotQty($admin_location_id, $item_type, $item_ref_id, $variation_key, $school_id, $branch_id);
        $agent_qty = $this->getSnapshotQty($agent_location_id, $item_type, $item_ref_id, $variation_key, $school_id, $branch_id);

        if ($action === 'assign' && $admin_qty < $qty) {
            return $this->output->set_content_type('application/json')->set_output(json_encode(array('status' => 'error', 'message' => 'Insufficient main stock')));
        }
        if ($action === 'return' && $agent_qty < $qty) {
            return $this->output->set_content_type('application/json')->set_output(json_encode(array('status' => 'error', 'message' => 'Insufficient agent stock')));
        }

        $this->db->trans_begin();
        if ($action === 'assign') {
            $this->setSnapshotQty($admin_location_id, $item_type, $item_ref_id, $variation_key, $admin_qty - $qty, $school_id, $branch_id);
            $this->setSnapshotQty($agent_location_id, $item_type, $item_ref_id, $variation_key, $agent_qty + $qty, $school_id, $branch_id);
            $this->insertMovement($admin_location_id, 'pos_assign_out', $item_type, $item_ref_id, $variation_key, -1 * $qty, $admin_qty, $admin_qty - $qty, $remarks, $school_id, $branch_id);
            $this->insertMovement($agent_location_id, 'pos_assign_in', $item_type, $item_ref_id, $variation_key, $qty, $agent_qty, $agent_qty + $qty, $remarks, $school_id, $branch_id);
        } else {
            $this->setSnapshotQty($agent_location_id, $item_type, $item_ref_id, $variation_key, $agent_qty - $qty, $school_id, $branch_id);
            $this->setSnapshotQty($admin_location_id, $item_type, $item_ref_id, $variation_key, $admin_qty + $qty, $school_id, $branch_id);
            $this->insertMovement($agent_location_id, 'pos_return_out', $item_type, $item_ref_id, $variation_key, -1 * $qty, $agent_qty, $agent_qty - $qty, $remarks, $school_id, $branch_id);
            $this->insertMovement($admin_location_id, 'pos_return_in', $item_type, $item_ref_id, $variation_key, $qty, $admin_qty, $admin_qty + $qty, $remarks, $school_id, $branch_id);
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return $this->output->set_content_type('application/json')->set_output(json_encode(array('status' => 'error', 'message' => 'Transfer failed')));
        }
        $this->db->trans_commit();
        return $this->output->set_content_type('application/json')->set_output(json_encode(array('status' => 'success', 'message' => 'Stock updated')));
    }

    public function stock_history()
    {
        $agent_id = (int)$this->input->get('agent_user_id', TRUE);
        $item_type = trim((string)$this->input->get('item_type', TRUE));
        $item_ref_id = (int)$this->input->get('item_ref_id', TRUE);
        $variation_key = trim((string)$this->input->get('variation_key', TRUE));
        $vendor_id = (int)$this->current_vendor['id'];
        if ($variation_key === '') $variation_key = 'default';
        if ($agent_id <= 0 || $item_type === '' || $item_ref_id <= 0) {
            return $this->output->set_content_type('application/json')->set_output(json_encode(array('status' => 'error', 'message' => 'Invalid request')));
        }
        if (!$this->Pos_agent_model->isAgentMappedToVendor($agent_id, $vendor_id)) {
            return $this->output->set_content_type('application/json')->set_output(json_encode(array('status' => 'error', 'message' => 'Agent not mapped')));
        }
        $location_id = $this->getAgentLocationId($agent_id);
        if ($location_id <= 0) {
            return $this->output->set_content_type('application/json')->set_output(json_encode(array('status' => 'success', 'history' => array())));
        }
        $rows = $this->db
            ->select('movement_type,qty_delta,qty_before,qty_after,created_at,remarks')
            ->from('inventory_stock_movements')
            ->where('location_id', (int)$location_id)
            ->where('item_type', $item_type)
            ->where('item_ref_id', $item_ref_id)
            ->where('variation_key', $variation_key)
            ->order_by('id', 'DESC')
            ->limit(100)
            ->get()->result_array();
        $history = array();
        foreach ($rows as $r) {
            $history[] = array(
                'direction' => ((float)$r['qty_delta'] >= 0 ? 'IN' : 'OUT'),
                'source' => str_replace(array('pos_', '_in', '_out'), '', (string)$r['movement_type']),
                'qty' => abs((float)$r['qty_delta']),
                'before' => (float)$r['qty_before'],
                'after' => (float)$r['qty_after'],
                'date' => !empty($r['created_at']) ? date('d-m-Y h:i:s A', strtotime($r['created_at'])) : '-',
                'remarks' => (string)$r['remarks']
            );
        }
        return $this->output->set_content_type('application/json')->set_output(json_encode(array('status' => 'success', 'history' => $history)));
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

    private function buildStockCatalogRows($vendor_id)
    {
        $rows = array();
        if ($this->db->table_exists('erp_uniforms') && $this->db->table_exists('erp_uniform_size_prices')) {
            $has_class_table = $this->db->table_exists('erp_classes');
            $uniform_select = 'u.id AS item_ref_id,u.product_name,ut.name AS uniform_type_name,u.gender,s.name AS variation_key,u.school_id,u.branch_id,sch.school_name,br.branch_name,bo.board_name';
            $uniform_select .= $has_class_table ? ',g.name AS grade_name' : ',"-" AS grade_name';
            $uniform_rows = $this->db
                ->select($uniform_select)
                ->from('erp_uniforms u')
                ->join('erp_uniform_size_prices usp', 'usp.uniform_id = u.id', 'left')
                ->join('erp_sizes s', 's.id = usp.size_id', 'left')
                ->join('erp_uniform_types ut', 'ut.id = u.uniform_type_id', 'left')
                ->join('erp_schools sch', 'sch.id = u.school_id', 'left')
                ->join('erp_school_branches br', 'br.id = u.branch_id', 'left')
                ->join('erp_school_boards bo', 'bo.id = u.board_id', 'left')
                ->where('u.vendor_id', (int)$vendor_id)
                ->where('u.status', 'active')
                ->order_by('u.product_name', 'ASC');
            if ($has_class_table) {
                $this->db->join('erp_classes g', 'g.id = u.class_id', 'left');
            }
            $uniform_rows = $this->db->get()->result_array();
            foreach ($uniform_rows as $r) {
                $r['item_type'] = 'uniform';
                if (empty($r['variation_key'])) $r['variation_key'] = 'default';
                $rows[] = $r;
            }
        }
        if ($this->db->table_exists('erp_products')) {
            $products = $this->db->select('id AS item_ref_id,product_name')->from('erp_products')->where('vendor_id', (int)$vendor_id)->where('is_deleted', 0)->where('status', 'active')->where('type !=', 'uniform')->order_by('product_name', 'ASC')->get()->result_array();
            foreach ($products as $p) {
                $rows[] = array(
                    'item_type' => 'book',
                    'item_ref_id' => (int)$p['item_ref_id'],
                    'product_name' => (string)$p['product_name'],
                    'uniform_type_name' => '-',
                    'gender' => '-',
                    'variation_key' => 'default',
                    'school_id' => NULL,
                    'branch_id' => NULL,
                    'school_name' => '-',
                    'branch_name' => '',
                    'board_name' => '-',
                    'grade_name' => '-'
                );
            }
        }
        return $rows;
    }

    private function getMainAdminLocationId()
    {
        $row = $this->db->select('id')->from('inventory_locations')->where('location_type', 'admin')->where('location_ref_id', 0)->limit(1)->get()->row_array();
        if (!empty($row['id'])) return (int)$row['id'];
        $this->db->insert('inventory_locations', array('location_type' => 'admin', 'location_ref_id' => 0, 'name' => 'Main Admin Stock', 'is_active' => 1));
        return (int)$this->db->insert_id();
    }

    private function getAgentLocationId($agent_id)
    {
        $row = $this->db->select('id')->from('inventory_locations')->where('location_type', 'pos_agent')->where('location_ref_id', (int)$agent_id)->limit(1)->get()->row_array();
        return !empty($row['id']) ? (int)$row['id'] : 0;
    }

    private function getOrCreateAgentLocationId($agent_id)
    {
        $id = $this->getAgentLocationId($agent_id);
        if ($id > 0) return $id;
        $this->db->insert('inventory_locations', array('location_type' => 'pos_agent', 'location_ref_id' => (int)$agent_id, 'name' => 'POS Agent #' . (int)$agent_id, 'is_active' => 1));
        return (int)$this->db->insert_id();
    }

    private function getSnapshotQty($location_id, $item_type, $item_ref_id, $variation_key, $school_id = NULL, $branch_id = NULL)
    {
        $school_id = ($school_id === NULL || $school_id === '' ? NULL : (int)$school_id);
        $branch_id = ($branch_id === NULL || $branch_id === '' ? NULL : (int)$branch_id);
        $qb = $this->db->select('qty_available')->from('inventory_stock_snapshot')
            ->where('location_id', (int)$location_id)
            ->where('item_type', $item_type)
            ->where('item_ref_id', (int)$item_ref_id)
            ->where('variation_key', $variation_key);
        if ($school_id === NULL) {
            $qb->where('school_id', NULL);
        } else {
            $qb->where('school_id', (int)$school_id);
        }
        if ($branch_id === NULL) {
            $qb->where('branch_id', NULL);
        } else {
            $qb->where('branch_id', (int)$branch_id);
        }
        $row = $qb->limit(1)->get()->row_array();
        return !empty($row['qty_available']) ? (float)$row['qty_available'] : 0.0;
    }

    private function setSnapshotQty($location_id, $item_type, $item_ref_id, $variation_key, $qty, $school_id = NULL, $branch_id = NULL)
    {
        $school_id = ($school_id === NULL || $school_id === '' ? NULL : (int)$school_id);
        $branch_id = ($branch_id === NULL || $branch_id === '' ? NULL : (int)$branch_id);
        $qb = $this->db->select('id')->from('inventory_stock_snapshot')
            ->where('location_id', (int)$location_id)
            ->where('item_type', $item_type)
            ->where('item_ref_id', (int)$item_ref_id)
            ->where('variation_key', $variation_key);
        if ($school_id === NULL) {
            $qb->where('school_id', NULL);
        } else {
            $qb->where('school_id', (int)$school_id);
        }
        if ($branch_id === NULL) {
            $qb->where('branch_id', NULL);
        } else {
            $qb->where('branch_id', (int)$branch_id);
        }
        $row = $qb->limit(1)->get()->row_array();
        if (!empty($row['id'])) {
            $this->db->where('id', (int)$row['id'])->update('inventory_stock_snapshot', array('qty_available' => (float)$qty));
            return;
        }
        $this->db->insert('inventory_stock_snapshot', array(
            'location_id' => (int)$location_id,
            'item_type' => $item_type,
            'item_ref_id' => (int)$item_ref_id,
            'variation_key' => $variation_key,
            'school_id' => $school_id,
            'branch_id' => $branch_id,
            'qty_available' => (float)$qty
        ));
    }

    private function insertMovement($location_id, $movement_type, $item_type, $item_ref_id, $variation_key, $delta, $before, $after, $remarks, $school_id = NULL, $branch_id = NULL)
    {
        $school_id = ($school_id === NULL || $school_id === '' ? NULL : (int)$school_id);
        $branch_id = ($branch_id === NULL || $branch_id === '' ? NULL : (int)$branch_id);
        $this->db->insert('inventory_stock_movements', array(
            'movement_type' => $movement_type,
            'external_ref' => $movement_type . ':' . $location_id . ':' . $item_type . ':' . $item_ref_id . ':' . $variation_key . ':' . (string)$school_id . ':' . (string)$branch_id . ':' . microtime(TRUE),
            'location_id' => (int)$location_id,
            'item_type' => $item_type,
            'item_ref_id' => (int)$item_ref_id,
            'variation_key' => $variation_key,
            'school_id' => $school_id,
            'branch_id' => $branch_id,
            'qty_delta' => (float)$delta,
            'qty_before' => (float)$before,
            'qty_after' => (float)$after,
            'actor_type' => 'vendor_admin',
            'actor_id' => (int)$this->current_vendor['id'],
            'remarks' => $remarks
        ));
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
