<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Load base controller
require_once(APPPATH . 'controllers/Erp_admin/Erp_base.php');

class Notification_templates extends Erp_base
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Erp_vendor_notification_model');
		$this->load->library('form_validation');
	}

	public function index()
	{
		// Basic permission gate (keeps consistent with other ERP-admin screens)
		if (!$this->hasPermission('vendors', 'read')) {
			show_error('You do not have permission to access this page.', 403);
		}

		$edit_event_id = (int)$this->input->get('edit_event_id');

		$data['events'] = $this->Erp_vendor_notification_model->getNotificationEvents(true);

		$data['edit_event'] = $edit_event_id ? $this->Erp_vendor_notification_model->getNotificationEventById($edit_event_id) : null;

		$data['title'] = 'Notification Types';
		$data['current_user'] = $this->current_user;
		$data['breadcrumb'] = array(
			array('label' => 'Notification Types', 'active' => true)
		);

		$data['content'] = $this->load->view('erp_admin/notification_templates/index', $data, TRUE);
		$this->load->view('erp_admin/layouts/index_template', $data);
	}

	public function save_event()
	{
		if (!$this->hasPermission('vendors', 'update')) {
			show_error('You do not have permission to access this page.', 403);
		}

		$event_id = (int)$this->input->post('event_id');
		$this->form_validation->set_rules('event_key', 'Event Key', 'required|trim');
		$this->form_validation->set_rules('title', 'Title', 'required|trim');
		$this->form_validation->set_rules('is_active', 'Active', 'in_list[0,1]');

		if ($this->form_validation->run() === FALSE) {
			$this->session->set_flashdata('error', validation_errors());
			redirect('erp-admin/notification-templates' . ($event_id ? '?edit_event_id=' . $event_id : ''));
		}

		$ok = $this->Erp_vendor_notification_model->upsertNotificationEvent([
			'event_key' => $this->input->post('event_key'),
			'title' => $this->input->post('title'),
			'is_active' => $this->input->post('is_active') ? 1 : 0,
		], $event_id ?: null);

		$this->session->set_flashdata($ok ? 'success' : 'error', $ok ? 'Event saved.' : 'Failed to save event.');
		redirect('erp-admin/notification-templates');
	}

	public function delete_event($event_id)
	{
		if (!$this->hasPermission('vendors', 'delete')) {
			show_error('You do not have permission to access this page.', 403);
		}

		$ok = $this->Erp_vendor_notification_model->deleteNotificationEvent((int)$event_id);
		$this->session->set_flashdata($ok ? 'success' : 'error', $ok ? 'Event deleted.' : 'Failed to delete event.');
		redirect('erp-admin/notification-templates');
	}

	// Master templates are now configured per vendor (Email/WhatsApp/SMS) with an event_key dropdown.
}

