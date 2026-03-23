<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH . 'controllers/Erp_admin/Erp_base.php');

/**
 * POS School UPI QR Controller
 */
class Pos_school_qr extends Erp_base
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pos_school_qr_model');
        $this->load->model('Pos_agent_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        if (!$this->hasPermission('pos', 'read')) {
            show_error('You do not have permission to access this page.', 403);
        }

        $filters = array(
            'vendor_id' => (int)$this->input->get('vendor_id'),
            'school_id' => (int)$this->input->get('school_id'),
            'is_active' => $this->input->get('is_active', TRUE)
        );

        if ($filters['is_active'] !== '' && $filters['is_active'] !== NULL) {
            $filters['is_active'] = (int)$filters['is_active'];
        }

        $per_page = 10;
        $page = (int)$this->input->get('page');
        if ($page < 1) {
            $page = 1;
        }
        $offset = ($page - 1) * $per_page;

        $data['vendors'] = $this->Pos_agent_model->getVendors();
        $data['schools'] = $filters['vendor_id'] > 0 ? $this->Pos_agent_model->getVendorSchools($filters['vendor_id']) : array();
        $data['qrs'] = $this->Pos_school_qr_model->getAll($filters, $per_page, $offset);
        $total = $this->Pos_school_qr_model->getTotal($filters);

        $data['total_qrs'] = $total;
        $data['per_page'] = $per_page;
        $data['current_page'] = $page;
        $data['total_pages'] = (int)ceil($total / $per_page);
        $data['filters'] = $filters;
        $data['title'] = 'School UPI QR';
        $data['current_user'] = $this->current_user;

        $data['content'] = $this->load->view('erp_admin/pos_school_qr/index', $data, TRUE);
        $this->load->view('erp_admin/layouts/index_template', $data);
    }

    public function add()
    {
        if (!$this->hasPermission('pos', 'create')) {
            show_error('You do not have permission to access this page.', 403);
        }

        $this->form_validation->set_rules('vendor_id', 'Vendor', 'required|integer');
        $this->form_validation->set_rules('school_id', 'School', 'required|integer');
        $this->form_validation->set_rules('upi_id', 'UPI ID', 'trim|max_length[120]');
        $this->form_validation->set_rules('payment_note', 'Payment Note', 'trim|max_length[255]');

        if ($this->form_validation->run() == FALSE) {
            $selected_vendor_id = (int)$this->input->post('vendor_id');
            $data['vendors'] = $this->Pos_agent_model->getVendors();
            $data['schools'] = $selected_vendor_id > 0 ? $this->Pos_agent_model->getVendorSchools($selected_vendor_id) : array();
            $data['selected_vendor_id'] = $selected_vendor_id;
            $data['title'] = 'Add School UPI QR';
            $data['current_user'] = $this->current_user;

            $data['content'] = $this->load->view('erp_admin/pos_school_qr/add', $data, TRUE);
            $this->load->view('erp_admin/layouts/index_template', $data);
            return;
        }

        $upload = $this->handleQrUpload('qr_image');
        if ($upload['status'] !== 'success') {
            $this->session->set_flashdata('error', $upload['message']);
            redirect('erp-admin/pos-school-qr/add');
            return;
        }

        $vendor_id = (int)$this->input->post('vendor_id');
        $school_id = (int)$this->input->post('school_id');
        $is_active = $this->input->post('is_active') ? 1 : 0;

        $insert_data = array(
            'vendor_id' => $vendor_id,
            'school_id' => $school_id,
            'qr_image_path' => $upload['relative_path'],
            'qr_image_original_name' => $upload['original_name'],
            'upi_id' => trim((string)$this->input->post('upi_id', TRUE)),
            'payment_note' => trim((string)$this->input->post('payment_note', TRUE)),
            'is_active' => $is_active,
            'active_school_key' => $is_active ? ($vendor_id . '-' . $school_id) : NULL,
            'created_by' => (int)$this->session->userdata('erp_user_id'),
            'updated_by' => (int)$this->session->userdata('erp_user_id')
        );

        $qr_id = $this->Pos_school_qr_model->create($insert_data);

        if (!$qr_id) {
            $this->deleteUploadedFile($upload['absolute_path']);
            $this->session->set_flashdata('error', 'Failed to save QR data.');
            redirect('erp-admin/pos-school-qr/add');
            return;
        }

        if ($is_active) {
            $this->Pos_school_qr_model->activateForSchool($qr_id, $vendor_id, $school_id, (int)$this->session->userdata('erp_user_id'));
        }

        $this->session->set_flashdata('success', 'School UPI QR added successfully.');
        redirect('erp-admin/pos-school-qr');
    }

    public function edit($id)
    {
        if (!$this->hasPermission('pos', 'update')) {
            show_error('You do not have permission to access this page.', 403);
        }

        $id = (int)$id;
        $qr = $this->Pos_school_qr_model->getById($id);
        if (!$qr) {
            show_404();
        }

        $this->form_validation->set_rules('vendor_id', 'Vendor', 'required|integer');
        $this->form_validation->set_rules('school_id', 'School', 'required|integer');
        $this->form_validation->set_rules('upi_id', 'UPI ID', 'trim|max_length[120]');
        $this->form_validation->set_rules('payment_note', 'Payment Note', 'trim|max_length[255]');

        if ($this->form_validation->run() == FALSE) {
            $selected_vendor_id = (int)$this->input->post('vendor_id');
            if ($selected_vendor_id <= 0) {
                $selected_vendor_id = (int)$qr['vendor_id'];
            }

            $data['qr'] = $qr;
            $data['vendors'] = $this->Pos_agent_model->getVendors();
            $data['schools'] = $selected_vendor_id > 0 ? $this->Pos_agent_model->getVendorSchools($selected_vendor_id) : array();
            $data['selected_vendor_id'] = $selected_vendor_id;
            $data['title'] = 'Edit School UPI QR';
            $data['current_user'] = $this->current_user;

            $data['content'] = $this->load->view('erp_admin/pos_school_qr/edit', $data, TRUE);
            $this->load->view('erp_admin/layouts/index_template', $data);
            return;
        }

        $vendor_id = (int)$this->input->post('vendor_id');
        $school_id = (int)$this->input->post('school_id');
        $is_active = $this->input->post('is_active') ? 1 : 0;

        $update_data = array(
            'vendor_id' => $vendor_id,
            'school_id' => $school_id,
            'upi_id' => trim((string)$this->input->post('upi_id', TRUE)),
            'payment_note' => trim((string)$this->input->post('payment_note', TRUE)),
            'is_active' => $is_active,
            'active_school_key' => $is_active ? ($vendor_id . '-' . $school_id) : NULL,
            'updated_by' => (int)$this->session->userdata('erp_user_id')
        );

        if (!empty($_FILES['qr_image']['name'])) {
            $upload = $this->handleQrUpload('qr_image');
            if ($upload['status'] !== 'success') {
                $this->session->set_flashdata('error', $upload['message']);
                redirect('erp-admin/pos-school-qr/edit/' . $id);
                return;
            }

            $update_data['qr_image_path'] = $upload['relative_path'];
            $update_data['qr_image_original_name'] = $upload['original_name'];

            if (!empty($qr['qr_image_path'])) {
                $this->deleteUploadedFile($this->buildAbsolutePath($qr['qr_image_path']));
            }
        }

        $updated = $this->Pos_school_qr_model->update($id, $update_data);

        if (!$updated) {
            $this->session->set_flashdata('error', 'Failed to update QR data.');
            redirect('erp-admin/pos-school-qr/edit/' . $id);
            return;
        }

        if ($is_active) {
            $this->Pos_school_qr_model->activateForSchool($id, $vendor_id, $school_id, (int)$this->session->userdata('erp_user_id'));
        }

        $this->session->set_flashdata('success', 'School UPI QR updated successfully.');
        redirect('erp-admin/pos-school-qr');
    }

    public function activate($id)
    {
        if (!$this->hasPermission('pos', 'update')) {
            show_error('You do not have permission to access this page.', 403);
        }

        $id = (int)$id;
        $row = $this->Pos_school_qr_model->getById($id);
        if (!$row) {
            show_404();
        }

        if ($this->Pos_school_qr_model->activateForSchool($id, (int)$row['vendor_id'], (int)$row['school_id'], (int)$this->session->userdata('erp_user_id'))) {
            $this->session->set_flashdata('success', 'QR activated successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to activate QR.');
        }

        redirect('erp-admin/pos-school-qr');
    }

    public function deactivate($id)
    {
        if (!$this->hasPermission('pos', 'update')) {
            show_error('You do not have permission to access this page.', 403);
        }

        $id = (int)$id;
        $row = $this->Pos_school_qr_model->getById($id);
        if (!$row) {
            show_404();
        }

        $updated = $this->Pos_school_qr_model->update($id, array(
            'is_active' => 0,
            'active_school_key' => NULL,
            'updated_by' => (int)$this->session->userdata('erp_user_id')
        ));

        if ($updated) {
            $this->session->set_flashdata('success', 'QR deactivated successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to deactivate QR.');
        }

        redirect('erp-admin/pos-school-qr');
    }

    public function delete($id)
    {
        if (!$this->hasPermission('pos', 'delete')) {
            show_error('You do not have permission to access this page.', 403);
        }

        $id = (int)$id;
        $row = $this->Pos_school_qr_model->getById($id);
        if (!$row) {
            show_404();
        }

        if ($this->Pos_school_qr_model->delete($id)) {
            if (!empty($row['qr_image_path'])) {
                $this->deleteUploadedFile($this->buildAbsolutePath($row['qr_image_path']));
            }
            $this->session->set_flashdata('success', 'QR deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete QR.');
        }

        redirect('erp-admin/pos-school-qr');
    }

    private function handleQrUpload($field_name)
    {
        if (empty($_FILES[$field_name]['name'])) {
            return array('status' => 'error', 'message' => 'QR image is required.');
        }

        $this->config->load('upload');
        $uploadCfg = $this->config->item('pos_upi_qr_upload');
        if (empty($uploadCfg)) {
            return array('status' => 'error', 'message' => 'POS upload config is missing.');
        }

        $allowed_types = isset($uploadCfg['allowed_types']) ? $uploadCfg['allowed_types'] : array();
        if (!is_array($allowed_types)) {
            $allowed_types = array_filter(array_map('trim', explode('|', (string)$allowed_types)));
        }
        if (empty($allowed_types)) {
            return array('status' => 'error', 'message' => 'POS upload allowed types config is invalid.');
        }

        $base_root = $this->resolvePosUploadBaseRoot($uploadCfg);
        $date_folder = date('Y_m_d');
        $upload_path = rtrim($base_root, '/') . '/' . trim($uploadCfg['relative_dir'], '/') . '/' . $date_folder . '/';

        if (!is_dir($upload_path) && !mkdir($upload_path, 0755, TRUE)) {
            return array('status' => 'error', 'message' => 'Upload directory is not writable: ' . $upload_path);
        }

        $ext = strtolower(pathinfo($_FILES[$field_name]['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed_types, TRUE)) {
            return array('status' => 'error', 'message' => 'Invalid file type. Allowed: ' . implode(', ', $allowed_types));
        }

        $image_info = @getimagesize($_FILES[$field_name]['tmp_name']);
        if ($image_info === FALSE) {
            return array('status' => 'error', 'message' => 'Uploaded file is not a valid image.');
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($_FILES[$field_name]['tmp_name']);
        $allowed_mimes = array('image/png', 'image/jpeg', 'image/webp');
        if (!in_array($mime, $allowed_mimes, TRUE)) {
            return array('status' => 'error', 'message' => 'Invalid image MIME type.');
        }

        $_FILES['image']['name'] = $_FILES[$field_name]['name'];
        $_FILES['image']['type'] = $_FILES[$field_name]['type'];
        $_FILES['image']['tmp_name'] = $_FILES[$field_name]['tmp_name'];
        $_FILES['image']['error'] = $_FILES[$field_name]['error'];
        $_FILES['image']['size'] = $_FILES[$field_name]['size'];

        $config = array(
            'upload_path' => $upload_path,
            'allowed_types' => implode('|', $allowed_types),
            'max_size' => $uploadCfg['max_size'],
            'encrypt_name' => TRUE,
            'overwrite' => FALSE,
            'remove_spaces' => TRUE,
            'detect_mime' => TRUE,
            'mod_mime_fix' => TRUE
        );

        $this->load->library('upload');
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('image')) {
            return array('status' => 'error', 'message' => strip_tags($this->upload->display_errors('', '')));
        }

        $uploaded = $this->upload->data();
        $relative_path = trim($uploadCfg['relative_dir'], '/') . '/' . $date_folder . '/' . $uploaded['file_name'];

        return array(
            'status' => 'success',
            'relative_path' => $relative_path,
            'absolute_path' => $upload_path . $uploaded['file_name'],
            'original_name' => $_FILES[$field_name]['name']
        );
    }

    private function buildAbsolutePath($relative_path)
    {
        $this->config->load('upload');
        $uploadCfg = $this->config->item('pos_upi_qr_upload');
        if (empty($uploadCfg)) {
            return '';
        }

        $base_root = $this->resolvePosUploadBaseRoot($uploadCfg);

        return rtrim($base_root, '/') . '/' . ltrim($relative_path, '/');
    }

    private function resolvePosUploadBaseRoot($uploadCfg)
    {
        $configured_root = '';
        if (isset($uploadCfg['base_root'])) {
            $configured_root = rtrim((string)$uploadCfg['base_root'], '/');
        }

        if ($configured_root !== '') {
            if (is_dir($configured_root) && is_writable($configured_root)) {
                return $configured_root;
            }

            if (!is_dir($configured_root) && @mkdir($configured_root, 0755, TRUE)) {
                return $configured_root;
            }
        }

        return rtrim(FCPATH, '/');
    }

    private function deleteUploadedFile($absolute_path)
    {
        if (!empty($absolute_path) && file_exists($absolute_path)) {
            @unlink($absolute_path);
        }
    }
}
