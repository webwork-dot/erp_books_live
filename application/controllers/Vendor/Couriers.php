<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Vendor Couriers Controller
 * Manages master couriers for vendors (add, edit, delete, toggle status)
 */

require_once(APPPATH . 'controllers/Vendor/Vendor_base.php');

class Couriers extends Vendor_base
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Courier_model');
        $this->load->library('form_validation');
    }

    /**
     * List all couriers for the current vendor
     */
    public function index()
    {
        $vendor_id = $this->current_vendor['id'];
        $data['couriers'] = $this->Courier_model->get_couriers($vendor_id);
        $data['title'] = 'Manage Couriers';
        $data['current_vendor'] = $this->current_vendor;
        $data['vendor_domain'] = $this->getVendorDomainForUrl();
        $data['breadcrumb'] = array(
            array('label' => 'Couriers', 'active' => true)
        );

        $data['content'] = $this->load->view('vendor/couriers/index', $data, TRUE);
        $this->load->view('vendor/layouts/index_template', $data);
    }

    /**
     * Add new courier form
     */
    public function add()
    {
        $data['title'] = 'Add New Courier';
        $data['current_vendor'] = $this->current_vendor;
        $data['vendor_domain'] = $this->getVendorDomainForUrl();
        $data['breadcrumb'] = array(
            array('label' => 'Couriers', 'url' => base_url('couriers')),
            array('label' => 'Add New', 'active' => true)
        );

        $this->form_validation->set_rules('courier_name', 'Courier Name', 'required|trim|max_length[150]');
        $this->form_validation->set_rules('tracking_link', 'Tracking Link', 'trim|max_length[255]');
        $this->form_validation->set_rules('status', 'Status', 'required|in_list[0,1]');

        if ($this->form_validation->run() == FALSE) {
            $data['content'] = $this->load->view('vendor/couriers/add', $data, TRUE);
            $this->load->view('vendor/layouts/index_template', $data);
        } else {
            $insert_data = array(
                'vendor_id' => $this->current_vendor['id'],
                'courier_name' => $this->input->post('courier_name'),
                'tracking_link' => $this->input->post('tracking_link') ?: null,
                'status' => (int) $this->input->post('status')
            );
            $id = $this->Courier_model->add_courier($insert_data);
            if ($id) {
                $this->session->set_flashdata('success', 'Courier added successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to add courier.');
            }
            redirect('couriers');
        }
    }

    /**
     * Edit courier form
     */
    public function edit($id)
    {
        $vendor_id = $this->current_vendor['id'];
        $courier = $this->Courier_model->get_courier_by_id($id, $vendor_id);

        if (!$courier) {
            $this->session->set_flashdata('error', 'Courier not found.');
            redirect('couriers');
        }

        $data['courier'] = $courier;
        $data['title'] = 'Edit Courier';
        $data['current_vendor'] = $this->current_vendor;
        $data['vendor_domain'] = $this->getVendorDomainForUrl();
        $data['breadcrumb'] = array(
            array('label' => 'Couriers', 'url' => base_url('couriers')),
            array('label' => 'Edit', 'active' => true)
        );

        $this->form_validation->set_rules('courier_name', 'Courier Name', 'required|trim|max_length[150]');
        $this->form_validation->set_rules('tracking_link', 'Tracking Link', 'trim|max_length[255]');
        $this->form_validation->set_rules('status', 'Status', 'required|in_list[0,1]');

        if ($this->form_validation->run() == FALSE) {
            $data['content'] = $this->load->view('vendor/couriers/edit', $data, TRUE);
            $this->load->view('vendor/layouts/index_template', $data);
        } else {
            $update_data = array(
                'courier_name' => $this->input->post('courier_name'),
                'tracking_link' => $this->input->post('tracking_link') ?: null,
                'status' => (int) $this->input->post('status')
            );
            if ($this->Courier_model->update_courier($id, $update_data, $vendor_id)) {
                $this->session->set_flashdata('success', 'Courier updated successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to update courier.');
            }
            redirect('couriers');
        }
    }

    /**
     * Delete courier
     */
    public function delete($id)
    {
        $vendor_id = $this->current_vendor['id'];
        $courier = $this->Courier_model->get_courier_by_id($id, $vendor_id);

        if (!$courier) {
            $this->session->set_flashdata('error', 'Courier not found.');
            redirect('couriers');
        }

        if ($this->Courier_model->delete_courier($id, $vendor_id)) {
            $this->session->set_flashdata('success', 'Courier deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete courier.');
        }
        redirect('couriers');
    }

    /**
     * AJAX: Get courier details (for modal/view)
     */
    public function get_courier_details($id)
    {
        header('Content-Type: application/json');
        $vendor_id = $this->current_vendor['id'];
        $courier = $this->Courier_model->get_courier_by_id($id, $vendor_id);

        if (!$courier) {
            echo json_encode(array('success' => false, 'message' => 'Courier not found.'));
            return;
        }

        echo json_encode(array('success' => true, 'courier' => $courier));
    }

    /**
     * AJAX: Get orders for a courier (out for delivery + delivered) - returns HTML for modal
     */
    public function get_courier_orders($id)
    {
        $vendor_id = $this->current_vendor['id'];
        $courier = $this->Courier_model->get_courier_by_id($id, $vendor_id);

        if (!$courier) {
            header('Content-Type: text/html; charset=utf-8');
            echo '<p class="text-muted">Courier not found.</p>';
            return;
        }

        $orders = $this->Courier_model->get_orders_by_courier($id, $vendor_id);

        $out_for_delivery = array();
        $delivered = array();
        foreach ($orders as $order) {
            if ($order['order_status'] == '3') {
                $out_for_delivery[] = $order;
            } else {
                $delivered[] = $order;
            }
        }

        $data['courier'] = $courier;
        $data['out_for_delivery'] = $out_for_delivery;
        $data['delivered'] = $delivered;
        $data['orders_base_url'] = base_url('orders/view/');

        $this->load->view('vendor/couriers/courier_orders_modal', $data);
    }

    /**
     * AJAX: Toggle courier status
     */
    public function toggle_status($id)
    {
        header('Content-Type: application/json');
        $vendor_id = $this->current_vendor['id'];

        if ($this->Courier_model->toggle_status($id, $vendor_id)) {
            $courier = $this->Courier_model->get_courier_by_id($id, $vendor_id);
            echo json_encode(array(
                'status' => 'success',
                'message' => 'Status updated.',
                'new_status' => (int) $courier['status']
            ));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Failed to update status.'));
        }
    }
}
