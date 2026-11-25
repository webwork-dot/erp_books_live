<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Purchase In Controller
 * Vendors, Companies, Warehouses
 */

require_once(APPPATH . 'controllers/Vendor/Vendor_base.php');

class Purchase_in extends Vendor_base
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Purchase_in_model');
		$this->load->model('Location_model');
		$this->load->library('form_validation');

		if (!$this->checkFeatureAccess('purchase-in-category') && !$this->checkFeatureAccess('purchase-in') && !$this->checkFeatureAccess('purchase_in')) {
			redirect('dashboard');
			return;
		}

		$this->Purchase_in_model->ensureTables();
	}

	private function ownerId()
	{
		return (int)$this->current_vendor['id'];
	}

	private function render($view, $data)
	{
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $this->getVendorDomainForUrl();
		$data['content'] = $this->load->view($view, $data, TRUE);
		$this->load->view('vendor/layouts/index_template', $data);
	}

	public function get_cities()
	{
		header('Content-Type: application/json');
		$state_id = (int)$this->input->get('state_id');
		$cities = $state_id > 0 ? $this->Location_model->getCitiesByState($state_id) : array();
		$state = $state_id > 0 ? $this->Location_model->getStateById($state_id) : NULL;
		echo json_encode(array(
			'status' => 'success',
			'cities' => $cities,
			'state_code' => $state && isset($state['code']) ? (string)$state['code'] : ''
		));
	}

	/* ===================== Vendors ===================== */

	public function vendors()
	{
		$data['title'] = 'Purchase Vendors - ' . $this->current_vendor['name'];
		$data['items'] = $this->Purchase_in_model->get_vendors($this->ownerId());
		$data['breadcrumb'] = array(
			array('label' => 'Purchase In', 'url' => '#'),
			array('label' => 'Vendors', 'active' => TRUE)
		);
		$this->render('vendor/purchase_in/vendors/index', $data);
	}

	public function vendors_add()
	{
		$data['title'] = 'Add Vendor - ' . $this->current_vendor['name'];
		$data['states'] = $this->Location_model->getAllStates();
		$data['cities'] = array();
		$data['breadcrumb'] = array(
			array('label' => 'Purchase In', 'url' => '#'),
			array('label' => 'Vendors', 'url' => base_url('purchase-in/vendors')),
			array('label' => 'Add', 'active' => TRUE)
		);

		$this->form_validation->set_rules('vendor_name', 'Vendor Name', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('email', 'Email', 'trim|valid_email|max_length[150]');

		if ($this->form_validation->run() === FALSE) {
			$state_id = (int)$this->input->post('state_id');
			if ($state_id > 0) {
				$data['cities'] = $this->Location_model->getCitiesByState($state_id);
			}
			$this->render('vendor/purchase_in/vendors/form', $data);
			return;
		}

		$payload = $this->vendorPayload();
		$payload['vendor_id'] = $this->ownerId();
		$id = $this->Purchase_in_model->add_vendor($payload);
		if ($id) {
			$this->piLog('vendor', (int)$id, $payload['vendor_name'], 'create', 'Vendor created');
		}
		$this->session->set_flashdata($id ? 'success' : 'error', $id ? 'Vendor saved successfully.' : 'Failed to save vendor.');
		redirect('purchase-in/vendors');
	}

	public function vendors_edit($id)
	{
		$item = $this->Purchase_in_model->get_vendor($id, $this->ownerId());
		if (!$item) {
			$this->session->set_flashdata('error', 'Vendor not found.');
			redirect('purchase-in/vendors');
			return;
		}

		$data['title'] = 'Edit Vendor - ' . $this->current_vendor['name'];
		$data['item'] = $item;
		$data['states'] = $this->Location_model->getAllStates();
		$data['cities'] = !empty($item['state_id']) ? $this->Location_model->getCitiesByState((int)$item['state_id']) : array();
		$data['breadcrumb'] = array(
			array('label' => 'Purchase In', 'url' => '#'),
			array('label' => 'Vendors', 'url' => base_url('purchase-in/vendors')),
			array('label' => 'Edit', 'active' => TRUE)
		);

		$this->form_validation->set_rules('vendor_name', 'Vendor Name', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('email', 'Email', 'trim|valid_email|max_length[150]');

		if ($this->form_validation->run() === FALSE) {
			$state_id = (int)$this->input->post('state_id');
			if ($state_id > 0) {
				$data['cities'] = $this->Location_model->getCitiesByState($state_id);
			}
			$this->render('vendor/purchase_in/vendors/form', $data);
			return;
		}

		$payload = $this->vendorPayload();
		$ok = $this->Purchase_in_model->update_vendor((int)$id, $payload, $this->ownerId());
		if ($ok) {
			$this->piLog('vendor', (int)$id, $payload['vendor_name'], 'update', 'Vendor updated');
		}
		$this->session->set_flashdata($ok ? 'success' : 'error', $ok ? 'Vendor updated successfully.' : 'Failed to update vendor.');
		redirect('purchase-in/vendors');
	}

	public function vendors_delete($id)
	{
		$existing = $this->Purchase_in_model->get_vendor((int)$id, $this->ownerId());
		$ok = $this->Purchase_in_model->delete_vendor((int)$id, $this->ownerId());
		if ($ok) {
			$this->piLog('vendor', (int)$id, $existing ? $existing['vendor_name'] : ('#' . $id), 'delete', 'Vendor deleted');
		}
		$this->session->set_flashdata($ok ? 'success' : 'error', $ok ? 'Vendor deleted.' : 'Failed to delete vendor.');
		redirect('purchase-in/vendors');
	}

	private function vendorPayload()
	{
		return array(
			'vendor_name' => trim((string)$this->input->post('vendor_name', TRUE)),
			'contact_person_name' => trim((string)$this->input->post('contact_person_name', TRUE)) ?: NULL,
			'contact_person_number' => trim((string)$this->input->post('contact_person_number', TRUE)) ?: NULL,
			'email' => trim((string)$this->input->post('email', TRUE)) ?: NULL,
			'gst_name' => trim((string)$this->input->post('gst_name', TRUE)) ?: NULL,
			'gst_no' => trim((string)$this->input->post('gst_no', TRUE)) ?: NULL,
			'fssai_no' => trim((string)$this->input->post('fssai_no', TRUE)) ?: NULL,
			'address_1' => trim((string)$this->input->post('address_1', TRUE)) ?: NULL,
			'address_2' => trim((string)$this->input->post('address_2', TRUE)) ?: NULL,
			'state_id' => (int)$this->input->post('state_id') ?: NULL,
			'city_id' => (int)$this->input->post('city_id') ?: NULL,
			'pincode' => trim((string)$this->input->post('pincode', TRUE)) ?: NULL,
			'state_code' => trim((string)$this->input->post('state_code', TRUE)) ?: NULL,
			'status' => 1
		);
	}

	/* ===================== Companies ===================== */

	public function companies()
	{
		$data['title'] = 'Companies - ' . $this->current_vendor['name'];
		$data['items'] = $this->Purchase_in_model->get_companies($this->ownerId());
		$data['breadcrumb'] = array(
			array('label' => 'Purchase In', 'url' => '#'),
			array('label' => 'Companies', 'active' => TRUE)
		);
		$this->render('vendor/purchase_in/companies/index', $data);
	}

	public function companies_add()
	{
		$data['title'] = 'Add Company - ' . $this->current_vendor['name'];
		$data['states'] = $this->Location_model->getAllStates();
		$data['cities'] = array();
		$data['breadcrumb'] = array(
			array('label' => 'Purchase In', 'url' => '#'),
			array('label' => 'Companies', 'url' => base_url('purchase-in/companies')),
			array('label' => 'Add', 'active' => TRUE)
		);

		$this->form_validation->set_rules('company_name', 'Company Name', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('email', 'Email', 'trim|valid_email|max_length[150]');

		if ($this->form_validation->run() === FALSE) {
			$state_id = (int)$this->input->post('state_id');
			if ($state_id > 0) {
				$data['cities'] = $this->Location_model->getCitiesByState($state_id);
			}
			$this->render('vendor/purchase_in/companies/form', $data);
			return;
		}

		$payload = $this->companyPayload();
		$payload['vendor_id'] = $this->ownerId();
		$id = $this->Purchase_in_model->add_company($payload);
		if ($id) {
			$this->piLog('company', (int)$id, $payload['company_name'], 'create', 'Company created');
		}
		$this->session->set_flashdata($id ? 'success' : 'error', $id ? 'Company saved successfully.' : 'Failed to save company.');
		redirect('purchase-in/companies');
	}

	public function companies_edit($id)
	{
		$item = $this->Purchase_in_model->get_company($id, $this->ownerId());
		if (!$item) {
			$this->session->set_flashdata('error', 'Company not found.');
			redirect('purchase-in/companies');
			return;
		}

		$data['title'] = 'Edit Company - ' . $this->current_vendor['name'];
		$data['item'] = $item;
		$data['states'] = $this->Location_model->getAllStates();
		$data['cities'] = !empty($item['state_id']) ? $this->Location_model->getCitiesByState((int)$item['state_id']) : array();
		$data['breadcrumb'] = array(
			array('label' => 'Purchase In', 'url' => '#'),
			array('label' => 'Companies', 'url' => base_url('purchase-in/companies')),
			array('label' => 'Edit', 'active' => TRUE)
		);

		$this->form_validation->set_rules('company_name', 'Company Name', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('email', 'Email', 'trim|valid_email|max_length[150]');

		if ($this->form_validation->run() === FALSE) {
			$state_id = (int)$this->input->post('state_id');
			if ($state_id > 0) {
				$data['cities'] = $this->Location_model->getCitiesByState($state_id);
			}
			$this->render('vendor/purchase_in/companies/form', $data);
			return;
		}

		$payload = $this->companyPayload();
		$ok = $this->Purchase_in_model->update_company((int)$id, $payload, $this->ownerId());
		if ($ok) {
			$this->piLog('company', (int)$id, $payload['company_name'], 'update', 'Company updated');
		}
		$this->session->set_flashdata($ok ? 'success' : 'error', $ok ? 'Company updated successfully.' : 'Failed to update company.');
		redirect('purchase-in/companies');
	}

	public function companies_delete($id)
	{
		$existing = $this->Purchase_in_model->get_company((int)$id, $this->ownerId());
		$ok = $this->Purchase_in_model->delete_company((int)$id, $this->ownerId());
		if ($ok) {
			$this->piLog('company', (int)$id, $existing ? $existing['company_name'] : ('#' . $id), 'delete', 'Company deleted');
		}
		$this->session->set_flashdata($ok ? 'success' : 'error', $ok ? 'Company deleted.' : 'Failed to delete company.');
		redirect('purchase-in/companies');
	}

	private function companyPayload()
	{
		$status = $this->input->post('status');
		return array(
			'company_name' => trim((string)$this->input->post('company_name', TRUE)),
			'contact_person_name' => trim((string)$this->input->post('contact_person_name', TRUE)) ?: NULL,
			'contact_person_number' => trim((string)$this->input->post('contact_person_number', TRUE)) ?: NULL,
			'email' => trim((string)$this->input->post('email', TRUE)) ?: NULL,
			'gst_name' => trim((string)$this->input->post('gst_name', TRUE)) ?: NULL,
			'gst_no' => trim((string)$this->input->post('gst_no', TRUE)) ?: NULL,
			'fssai_no' => trim((string)$this->input->post('fssai_no', TRUE)) ?: NULL,
			'address_1' => trim((string)$this->input->post('address_1', TRUE)) ?: NULL,
			'address_2' => trim((string)$this->input->post('address_2', TRUE)) ?: NULL,
			'address_3' => trim((string)$this->input->post('address_3', TRUE)) ?: NULL,
			'state_id' => (int)$this->input->post('state_id') ?: NULL,
			'city_id' => (int)$this->input->post('city_id') ?: NULL,
			'pincode' => trim((string)$this->input->post('pincode', TRUE)) ?: NULL,
			'state_code' => trim((string)$this->input->post('state_code', TRUE)) ?: NULL,
			'status' => ($status === NULL || $status === '' || (string)$status === '1') ? 1 : 0
		);
	}

	/* ===================== Warehouses ===================== */

	public function warehouses()
	{
		$data['title'] = 'Warehouses - ' . $this->current_vendor['name'];
		$data['items'] = $this->Purchase_in_model->get_warehouses($this->ownerId());
		$data['breadcrumb'] = array(
			array('label' => 'Purchase In', 'url' => '#'),
			array('label' => 'Warehouse', 'active' => TRUE)
		);
		$this->render('vendor/purchase_in/warehouses/index', $data);
	}

	public function warehouses_add()
	{
		$data['title'] = 'Add Warehouse - ' . $this->current_vendor['name'];
		$data['states'] = $this->Location_model->getAllStates();
		$data['cities'] = array();
		$data['companies'] = $this->Purchase_in_model->get_companies($this->ownerId(), TRUE);
		$data['breadcrumb'] = array(
			array('label' => 'Purchase In', 'url' => '#'),
			array('label' => 'Warehouse', 'url' => base_url('purchase-in/warehouses')),
			array('label' => 'Add', 'active' => TRUE)
		);

		$this->form_validation->set_rules('warehouse_name', 'Warehouse Name', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('company_ids[]', 'Companies', 'required');

		if ($this->form_validation->run() === FALSE) {
			$state_id = (int)$this->input->post('state_id');
			if ($state_id > 0) {
				$data['cities'] = $this->Location_model->getCitiesByState($state_id);
			}
			$this->render('vendor/purchase_in/warehouses/form', $data);
			return;
		}

		$payload = $this->warehousePayload();
		$payload['vendor_id'] = $this->ownerId();
		$company_ids = $this->input->post('company_ids');
		$id = $this->Purchase_in_model->add_warehouse($payload, is_array($company_ids) ? $company_ids : array());
		if ($id) {
			$this->piLog('warehouse', (int)$id, $payload['warehouse_name'], 'create', 'Warehouse created', array('company_ids' => $company_ids));
		}
		$this->session->set_flashdata($id ? 'success' : 'error', $id ? 'Warehouse saved successfully.' : 'Failed to save warehouse.');
		redirect('purchase-in/warehouses');
	}

	public function warehouses_edit($id)
	{
		$item = $this->Purchase_in_model->get_warehouse($id, $this->ownerId());
		if (!$item) {
			$this->session->set_flashdata('error', 'Warehouse not found.');
			redirect('purchase-in/warehouses');
			return;
		}

		$data['title'] = 'Edit Warehouse - ' . $this->current_vendor['name'];
		$data['item'] = $item;
		$data['states'] = $this->Location_model->getAllStates();
		$data['cities'] = !empty($item['state_id']) ? $this->Location_model->getCitiesByState((int)$item['state_id']) : array();
		$data['companies'] = $this->Purchase_in_model->get_companies($this->ownerId(), TRUE);
		$data['breadcrumb'] = array(
			array('label' => 'Purchase In', 'url' => '#'),
			array('label' => 'Warehouse', 'url' => base_url('purchase-in/warehouses')),
			array('label' => 'Edit', 'active' => TRUE)
		);

		$this->form_validation->set_rules('warehouse_name', 'Warehouse Name', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('company_ids[]', 'Companies', 'required');

		if ($this->form_validation->run() === FALSE) {
			$state_id = (int)$this->input->post('state_id');
			if ($state_id > 0) {
				$data['cities'] = $this->Location_model->getCitiesByState($state_id);
			}
			$this->render('vendor/purchase_in/warehouses/form', $data);
			return;
		}

		$company_ids = $this->input->post('company_ids');
		$payload = $this->warehousePayload();
		$ok = $this->Purchase_in_model->update_warehouse((int)$id, $payload, $this->ownerId(), is_array($company_ids) ? $company_ids : array());
		if ($ok) {
			$this->piLog('warehouse', (int)$id, $payload['warehouse_name'], 'update', 'Warehouse updated', array('company_ids' => $company_ids));
		}
		$this->session->set_flashdata($ok ? 'success' : 'error', $ok ? 'Warehouse updated successfully.' : 'Failed to update warehouse.');
		redirect('purchase-in/warehouses');
	}

	public function warehouses_delete($id)
	{
		$existing = $this->Purchase_in_model->get_warehouse((int)$id, $this->ownerId());
		$ok = $this->Purchase_in_model->delete_warehouse((int)$id, $this->ownerId());
		if ($ok) {
			$this->piLog('warehouse', (int)$id, $existing ? $existing['warehouse_name'] : ('#' . $id), 'delete', 'Warehouse deleted');
		}
		$this->session->set_flashdata($ok ? 'success' : 'error', $ok ? 'Warehouse deleted.' : 'Failed to delete warehouse.');
		redirect('purchase-in/warehouses');
	}

	private function warehousePayload()
	{
		$status = $this->input->post('status');
		return array(
			'warehouse_name' => trim((string)$this->input->post('warehouse_name', TRUE)),
			'address_1' => trim((string)$this->input->post('address_1', TRUE)) ?: NULL,
			'address_2' => trim((string)$this->input->post('address_2', TRUE)) ?: NULL,
			'address_3' => trim((string)$this->input->post('address_3', TRUE)) ?: NULL,
			'state_id' => (int)$this->input->post('state_id') ?: NULL,
			'city_id' => (int)$this->input->post('city_id') ?: NULL,
			'pincode' => trim((string)$this->input->post('pincode', TRUE)) ?: NULL,
			'status' => ($status === NULL || $status === '' || (string)$status === '1') ? 1 : 0
		);
	}

	/* ===================== Purchase Orders ===================== */

	public function purchase_orders()
	{
		$data['title'] = 'Purchase Orders - ' . $this->current_vendor['name'];
		$data['items'] = $this->Purchase_in_model->get_purchase_orders($this->ownerId());
		$data['breadcrumb'] = array(
			array('label' => 'Purchase In', 'url' => '#'),
			array('label' => 'Purchase Order', 'active' => TRUE)
		);
		$this->render('vendor/purchase_in/orders/index', $data);
	}

	public function purchase_orders_add()
	{
		$data['title'] = 'New Purchase Order - ' . $this->current_vendor['name'];
		$data['companies'] = $this->Purchase_in_model->get_companies($this->ownerId(), TRUE);
		$data['warehouses'] = $this->Purchase_in_model->get_warehouses($this->ownerId());
		$data['suppliers'] = $this->Purchase_in_model->get_vendors($this->ownerId());
		$suggested = $this->Purchase_in_model->suggest_po_no($this->ownerId());
		$data['suggested_po'] = $suggested;
		$data['breadcrumb'] = array(
			array('label' => 'Purchase In', 'url' => '#'),
			array('label' => 'Purchase Order', 'url' => base_url('purchase-in/orders')),
			array('label' => 'Add', 'active' => TRUE)
		);
		$this->render('vendor/purchase_in/orders/form', $data);
	}

	public function purchase_orders_view($id)
	{
		$item = $this->Purchase_in_model->get_purchase_order((int)$id, $this->ownerId());
		if (!$item) {
			$this->session->set_flashdata('error', 'Purchase order not found.');
			redirect('purchase-in/orders');
			return;
		}
		$data['title'] = 'PO ' . $item['po_no'] . ' - ' . $this->current_vendor['name'];
		$data['item'] = $item;
		$data['company'] = $this->Purchase_in_model->get_company((int)$item['company_id'], $this->ownerId());
		$data['warehouse'] = $this->Purchase_in_model->get_warehouse((int)$item['warehouse_id'], $this->ownerId());
		$data['supplier'] = $this->Purchase_in_model->get_vendor((int)$item['purchase_vendor_id'], $this->ownerId());
		$data['breadcrumb'] = array(
			array('label' => 'Purchase In', 'url' => '#'),
			array('label' => 'Purchase Order', 'url' => base_url('purchase-in/orders')),
			array('label' => $item['po_no'], 'active' => TRUE)
		);
		$this->render('vendor/purchase_in/orders/view', $data);
	}

	public function purchase_orders_save()
	{
		header('Content-Type: application/json');
		$company_id = (int)$this->input->post('company_id');
		$warehouse_id = (int)$this->input->post('warehouse_id');
		$purchase_vendor_id = (int)$this->input->post('purchase_vendor_id');
		$po_date = trim((string)$this->input->post('po_date'));
		$items_raw = $this->input->post('items');

		if ($company_id <= 0 || $warehouse_id <= 0 || $purchase_vendor_id <= 0) {
			echo json_encode(array('status' => 'error', 'message' => 'Company, Warehouse and Vendor are required.'));
			return;
		}
		if ($po_date === '') {
			$po_date = date('Y-m-d');
		}

		$company = $this->Purchase_in_model->get_company($company_id, $this->ownerId());
		$warehouse = $this->Purchase_in_model->get_warehouse($warehouse_id, $this->ownerId());
		$supplier = $this->Purchase_in_model->get_vendor($purchase_vendor_id, $this->ownerId());
		if (!$company || !$warehouse || !$supplier) {
			echo json_encode(array('status' => 'error', 'message' => 'Invalid company, warehouse or vendor.'));
			return;
		}

		if (is_string($items_raw)) {
			$decoded = json_decode($items_raw, TRUE);
			$items_raw = is_array($decoded) ? $decoded : array();
		}
		if (!is_array($items_raw) || empty($items_raw)) {
			echo json_encode(array('status' => 'error', 'message' => 'Add at least one product line.'));
			return;
		}

		$without_gst = 0;
		$direct_purchase = 0;
		$same_state = TRUE;
		if (!empty($company['state_id']) && !empty($supplier['state_id'])) {
			$same_state = ((int)$company['state_id'] === (int)$supplier['state_id']);
		}

		$line_items = array();
		$basic_total = 0.0;
		$taxable_total = 0.0;
		$gst_total = 0.0;

		foreach ($items_raw as $row) {
			$name = isset($row['product_name']) ? trim((string)$row['product_name']) : '';
			if ($name === '') {
				continue;
			}
			$qty = isset($row['qty']) ? (float)$row['qty'] : 0;
			$rate = isset($row['rate']) ? (float)$row['rate'] : 0;
			$discount = isset($row['discount']) ? (float)$row['discount'] : 0;
			$gst_percent = $without_gst ? 0 : (isset($row['gst_percent']) ? (float)$row['gst_percent'] : 0);
			if ($qty <= 0) {
				continue;
			}
			$basic = round($qty * $rate, 2);
			$taxable = max(0, round($basic - $discount, 2));
			$gst_amt = round($taxable * $gst_percent / 100, 2);
			$line_total = round($taxable + $gst_amt, 2);
			$basic_total += $basic;
			$taxable_total += $taxable;
			$gst_total += $gst_amt;
			$line_items[] = array(
				'product_id' => isset($row['product_id']) ? (int)$row['product_id'] : NULL,
				'product_type' => isset($row['product_type']) ? substr((string)$row['product_type'], 0, 50) : NULL,
				'product_name' => substr($name, 0, 500),
				'hsn' => isset($row['hsn']) ? substr((string)$row['hsn'], 0, 50) : NULL,
				'gst_percent' => $gst_percent,
				'qty' => $qty,
				'rate' => $rate,
				'discount' => $discount,
				'basic_amount' => $basic,
				'taxable_amount' => $taxable,
				'gst_amount' => $gst_amt,
				'line_total' => $line_total
			);
		}

		if (empty($line_items)) {
			echo json_encode(array('status' => 'error', 'message' => 'Add at least one valid product line.'));
			return;
		}

		$discount_percent = (float)$this->input->post('discount_percent');
		$discount_amount = (float)$this->input->post('discount_amount');
		$transport_percent = 0;
		$transport_amount = 0;
		$other_charges_amount = (float)$this->input->post('other_charges_amount');
		$other_charges_desc = trim((string)$this->input->post('other_charges_desc', TRUE)) ?: NULL;
		$apply_round_off = (int)$this->input->post('apply_round_off') ? 1 : 0;

		if ($discount_percent > 0 && $discount_amount <= 0) {
			$discount_amount = round($taxable_total * $discount_percent / 100, 2);
		}
		$after_discount = max(0, $taxable_total - $discount_amount);

		$cgst = 0.0;
		$sgst = 0.0;
		$igst = 0.0;
		if (!$without_gst) {
			if ($same_state) {
				$cgst = round($gst_total / 2, 2);
				$sgst = round($gst_total - $cgst, 2);
			} else {
				$igst = round($gst_total, 2);
			}
		}

		$sub_grand = $after_discount + $gst_total + $transport_amount + $other_charges_amount;
		$round_off = 0.0;
		if ($apply_round_off) {
			$rounded = round($sub_grand);
			$round_off = round($rounded - $sub_grand, 2);
			$sub_grand = $rounded;
		}
		$grand_total = round($sub_grand, 2);

		$suggested = $this->Purchase_in_model->suggest_po_no($this->ownerId(), $po_date);
		$expected_date = trim((string)$this->input->post('expected_date'));
		$header = array(
			'vendor_id' => $this->ownerId(),
			'po_no' => $suggested['po_no'],
			'fy_label' => $suggested['fy_label'],
			'po_seq' => $suggested['po_seq'],
			'company_id' => $company_id,
			'warehouse_id' => $warehouse_id,
			'purchase_vendor_id' => $purchase_vendor_id,
			'po_date' => $po_date,
			'expected_date' => $expected_date !== '' ? $expected_date : NULL,
			'without_gst' => $without_gst,
			'direct_purchase' => $direct_purchase,
			'notes' => trim((string)$this->input->post('notes', TRUE)) ?: NULL,
			'discount_percent' => $discount_percent,
			'discount_amount' => $discount_amount,
			'transport_percent' => $transport_percent,
			'transport_amount' => $transport_amount,
			'other_charges_desc' => $other_charges_desc,
			'other_charges_amount' => $other_charges_amount,
			'round_off' => $round_off,
			'apply_round_off' => $apply_round_off,
			'basic_amount' => round($basic_total, 2),
			'taxable_amount' => round($taxable_total, 2),
			'cgst_amount' => $cgst,
			'sgst_amount' => $sgst,
			'igst_amount' => $igst,
			'grand_total' => $grand_total,
			'status' => 'pending'
		);

		$po_id = $this->Purchase_in_model->create_purchase_order($header, $line_items);
		if (!$po_id) {
			echo json_encode(array('status' => 'error', 'message' => 'Failed to save purchase order.'));
			return;
		}

		$this->piLog('purchase_order', (int)$po_id, $header['po_no'], 'create', 'Purchase order created', array(
			'company' => $company['company_name'],
			'warehouse' => $warehouse['warehouse_name'],
			'vendor' => $supplier['vendor_name'],
			'grand_total' => $grand_total,
			'lines' => count($line_items)
		));

		echo json_encode(array(
			'status' => 'success',
			'message' => 'Purchase order saved.',
			'po_id' => $po_id,
			'po_no' => $header['po_no'],
			'redirect' => base_url('purchase-in/orders/view/' . $po_id)
		));
	}

	public function party_details()
	{
		header('Content-Type: application/json');
		$type = trim((string)$this->input->get('type'));
		$id = (int)$this->input->get('id');
		$row = NULL;
		if ($type === 'company') {
			$row = $this->Purchase_in_model->get_company($id, $this->ownerId());
		} elseif ($type === 'warehouse') {
			$row = $this->Purchase_in_model->get_warehouse($id, $this->ownerId());
		} elseif ($type === 'vendor') {
			$row = $this->Purchase_in_model->get_vendor($id, $this->ownerId());
		}
		if (!$row) {
			echo json_encode(array('status' => 'error', 'message' => 'Not found'));
			return;
		}

		$state_name = '';
		$city_name = '';
		if (!empty($row['state_id'])) {
			$st = $this->Location_model->getStateById((int)$row['state_id']);
			$state_name = $st && isset($st['name']) ? $st['name'] : '';
		}
		if (!empty($row['city_id']) && method_exists($this->Location_model, 'getCityById')) {
			$ct = $this->Location_model->getCityById((int)$row['city_id']);
			$city_name = $ct && isset($ct['name']) ? $ct['name'] : '';
		}

		$address_parts = array();
		foreach (array('address_1', 'address_2', 'address_3') as $ak) {
			if (!empty($row[$ak])) {
				$address_parts[] = $row[$ak];
			}
		}
		if ($city_name !== '') {
			$address_parts[] = $city_name;
		}
		if ($state_name !== '') {
			$address_parts[] = $state_name;
		}
		if (!empty($row['pincode'])) {
			$address_parts[] = $row['pincode'];
		}

		$name = '';
		if ($type === 'company') {
			$name = $row['company_name'];
		} elseif ($type === 'warehouse') {
			$name = $row['warehouse_name'];
		} else {
			$name = $row['vendor_name'];
		}

		echo json_encode(array(
			'status' => 'success',
			'data' => array(
				'name' => $name,
				'gst' => isset($row['gst_no']) ? $row['gst_no'] : '',
				'fssai' => isset($row['fssai_no']) ? $row['fssai_no'] : '',
				'contact' => isset($row['contact_person_name']) ? $row['contact_person_name'] : '',
				'phone' => isset($row['contact_person_number']) ? $row['contact_person_number'] : '',
				'address' => implode(', ', $address_parts),
				'state_id' => isset($row['state_id']) ? (int)$row['state_id'] : 0
			)
		));
	}

	public function warehouses_by_company()
	{
		header('Content-Type: application/json');
		$company_id = (int)$this->input->get('company_id');
		$rows = $company_id > 0
			? $this->Purchase_in_model->get_warehouses_for_company($this->ownerId(), $company_id)
			: array();
		echo json_encode(array('status' => 'success', 'warehouses' => $rows));
	}

	public function product_search()
	{
		header('Content-Type: application/json');
		$q = trim((string)$this->input->get('q'));
		$rows = $this->Purchase_in_model->search_products($this->ownerId(), $q, 25);
		echo json_encode(array('status' => 'success', 'products' => $rows));
	}

	public function product_history()
	{
		header('Content-Type: application/json');
		$product_id = (int)$this->input->get('product_id');
		$rows = $product_id > 0
			? $this->Purchase_in_model->get_product_purchase_history($this->ownerId(), $product_id, 30)
			: array();
		$last_rate = !empty($rows) ? (float)$rows[0]['rate'] : 0;
		echo json_encode(array(
			'status' => 'success',
			'last_purchase' => $last_rate,
			'history' => $rows
		));
	}

	public function activity_logs()
	{
		$data['title'] = 'Purchase In Logs - ' . $this->current_vendor['name'];
		$data['items'] = $this->Purchase_in_model->get_activity_logs($this->ownerId(), 200);
		$data['breadcrumb'] = array(
			array('label' => 'Purchase In', 'url' => '#'),
			array('label' => 'Activity Logs', 'active' => TRUE)
		);
		$this->render('vendor/purchase_in/logs/index', $data);
	}

	/* ===================== Purchase Receipts ===================== */

	public function purchase_receipts()
	{
		$data['title'] = 'Purchase Receipts - ' . $this->current_vendor['name'];
		$data['items'] = $this->Purchase_in_model->get_purchase_receipts($this->ownerId());
		$data['breadcrumb'] = array(
			array('label' => 'Purchase In', 'url' => '#'),
			array('label' => 'Purchase Receipt', 'active' => TRUE)
		);
		$this->render('vendor/purchase_in/receipts/index', $data);
	}

	public function purchase_receipts_view($id)
	{
		$item = $this->Purchase_in_model->get_purchase_receipt((int)$id, $this->ownerId());
		if (!$item) {
			$this->session->set_flashdata('error', 'Purchase receipt not found.');
			redirect('purchase-in/receipts');
			return;
		}
		$data['title'] = 'PR ' . $item['pr_no'] . ' - ' . $this->current_vendor['name'];
		$data['item'] = $item;
		$data['company'] = $this->Purchase_in_model->get_company((int)$item['company_id'], $this->ownerId());
		$data['warehouse'] = $this->Purchase_in_model->get_warehouse((int)$item['warehouse_id'], $this->ownerId());
		$data['supplier'] = $this->Purchase_in_model->get_vendor((int)$item['purchase_vendor_id'], $this->ownerId());
		$data['breadcrumb'] = array(
			array('label' => 'Purchase In', 'url' => '#'),
			array('label' => 'Purchase Receipt', 'url' => base_url('purchase-in/receipts')),
			array('label' => $item['pr_no'], 'active' => TRUE)
		);
		$this->render('vendor/purchase_in/receipts/view', $data);
	}

	public function receive_delivery_data($po_id)
	{
		header('Content-Type: application/json');
		$po = $this->Purchase_in_model->get_purchase_order((int)$po_id, $this->ownerId());
		if (!$po) {
			echo json_encode(array('status' => 'error', 'message' => 'Purchase order not found.'));
			return;
		}
		if (strtolower((string)$po['status']) === 'received') {
			echo json_encode(array('status' => 'error', 'message' => 'This PO is already fully received.'));
			return;
		}
		$company = $this->Purchase_in_model->get_company((int)$po['company_id'], $this->ownerId());
		$warehouse = $this->Purchase_in_model->get_warehouse((int)$po['warehouse_id'], $this->ownerId());
		$supplier = $this->Purchase_in_model->get_vendor((int)$po['purchase_vendor_id'], $this->ownerId());
		$suggested = $this->Purchase_in_model->suggest_pr_no($this->ownerId());
		$lines = array();
		$has_pending = FALSE;
		foreach ($po['items'] as $line) {
			$pending = (float)$line['qty_pending'];
			if ($pending > 0) {
				$has_pending = TRUE;
			}
			$lines[] = array(
				'po_item_id' => (int)$line['id'],
				'product_id' => isset($line['product_id']) ? (int)$line['product_id'] : 0,
				'product_type' => isset($line['product_type']) ? $line['product_type'] : '',
				'product_name' => $line['product_name'],
				'ordered_qty' => (float)$line['qty'],
				'qty_received' => (float)$line['qty_received'],
				'qty_pending' => $pending,
				'rate' => (float)$line['rate']
			);
		}
		if (!$has_pending) {
			echo json_encode(array('status' => 'error', 'message' => 'No pending quantities to receive.'));
			return;
		}
		echo json_encode(array(
			'status' => 'success',
			'po' => array(
				'id' => (int)$po['id'],
				'po_no' => $po['po_no'],
				'company' => $company ? $company['company_name'] : '',
				'warehouse' => $warehouse ? $warehouse['warehouse_name'] : '',
				'vendor' => $supplier ? $supplier['vendor_name'] : '',
				'company_id' => (int)$po['company_id'],
				'warehouse_id' => (int)$po['warehouse_id'],
				'purchase_vendor_id' => (int)$po['purchase_vendor_id']
			),
			'suggested_pr' => $suggested,
			'lines' => $lines
		));
	}

	public function receive_delivery_save()
	{
		header('Content-Type: application/json');
		$po_id = (int)$this->input->post('po_id');
		$invoice_no = trim((string)$this->input->post('invoice_no', TRUE));
		$received_date = trim((string)$this->input->post('received_date'));
		$items_raw = $this->input->post('items');

		$po = $this->Purchase_in_model->get_purchase_order($po_id, $this->ownerId());
		if (!$po) {
			echo json_encode(array('status' => 'error', 'message' => 'Purchase order not found.'));
			return;
		}
		if (strtolower((string)$po['status']) === 'received') {
			echo json_encode(array('status' => 'error', 'message' => 'This PO is already fully received.'));
			return;
		}
		if ($invoice_no === '') {
			echo json_encode(array('status' => 'error', 'message' => 'Invoice No is required.'));
			return;
		}
		if ($received_date === '') {
			$received_date = date('Y-m-d');
		}
		$today = date('Y-m-d');
		if ($received_date < $today) {
			echo json_encode(array('status' => 'error', 'message' => 'Past received dates are not allowed.'));
			return;
		}
		if (is_string($items_raw)) {
			$decoded = json_decode($items_raw, TRUE);
			$items_raw = is_array($decoded) ? $decoded : array();
		}
		if (!is_array($items_raw) || empty($items_raw)) {
			echo json_encode(array('status' => 'error', 'message' => 'Enter receive qty for at least one line.'));
			return;
		}

		$po_items = array();
		foreach ($po['items'] as $line) {
			$po_items[(int)$line['id']] = $line;
		}

		$receipt_items = array();
		$items_total = 0.0;
		foreach ($items_raw as $row) {
			$po_item_id = isset($row['po_item_id']) ? (int)$row['po_item_id'] : 0;
			$recv = isset($row['receive_qty']) ? (float)$row['receive_qty'] : 0;
			if ($po_item_id <= 0 || $recv <= 0) {
				continue;
			}
			if (!isset($po_items[$po_item_id])) {
				echo json_encode(array('status' => 'error', 'message' => 'Invalid PO line.'));
				return;
			}
			$line = $po_items[$po_item_id];
			$pending = (float)$line['qty_pending'];
			if ($recv > $pending + 0.0001) {
				echo json_encode(array('status' => 'error', 'message' => 'Receive qty cannot exceed pending for ' . $line['product_name']));
				return;
			}
			$rate = (float)$line['rate'];
			$items_total += round($recv * $rate, 2);
			$receipt_items[] = array(
				'po_item_id' => $po_item_id,
				'product_id' => !empty($line['product_id']) ? (int)$line['product_id'] : NULL,
				'product_type' => isset($line['product_type']) ? $line['product_type'] : NULL,
				'product_name' => $line['product_name'],
				'ordered_qty' => (float)$line['qty'],
				'receive_qty' => $recv,
				'rate' => $rate
			);
		}
		if (empty($receipt_items)) {
			echo json_encode(array('status' => 'error', 'message' => 'Enter receive qty for at least one line.'));
			return;
		}

		$transport_percent = (float)$this->input->post('transport_percent');
		$transport_amount = (float)$this->input->post('transport_amount');
		$transport_effect = round($transport_amount + ($transport_amount * $transport_percent / 100), 2);
		$grand_total = round($items_total + $transport_effect, 2);

		$suggested = $this->Purchase_in_model->suggest_pr_no($this->ownerId(), $received_date);
		$header = array(
			'vendor_id' => $this->ownerId(),
			'po_id' => $po_id,
			'pr_no' => $suggested['pr_no'],
			'fy_label' => $suggested['fy_label'],
			'pr_seq' => $suggested['pr_seq'],
			'company_id' => (int)$po['company_id'],
			'warehouse_id' => (int)$po['warehouse_id'],
			'purchase_vendor_id' => (int)$po['purchase_vendor_id'],
			'invoice_no' => $invoice_no,
			'received_date' => $received_date,
			'transport_percent' => $transport_percent,
			'transport_amount' => $transport_amount,
			'items_total' => round($items_total, 2),
			'grand_total' => $grand_total,
			'notes' => trim((string)$this->input->post('notes', TRUE)) ?: NULL,
			'status' => 'posted'
		);

		$receipt_id = $this->Purchase_in_model->create_purchase_receipt($header, $receipt_items, $this->ownerId(), TRUE);
		if (!$receipt_id) {
			echo json_encode(array('status' => 'error', 'message' => 'Failed to save purchase receipt.'));
			return;
		}

		$po_after = $this->Purchase_in_model->get_purchase_order($po_id, $this->ownerId());
		$this->piLog('purchase_receipt', (int)$receipt_id, $header['pr_no'], 'create', 'Purchase receipt created', array(
			'po_no' => $po['po_no'],
			'invoice_no' => $invoice_no,
			'lines' => count($receipt_items),
			'po_status' => $po_after ? $po_after['status'] : NULL
		));

		echo json_encode(array(
			'status' => 'success',
			'message' => 'Delivery received. Stock updated.',
			'receipt_id' => $receipt_id,
			'pr_no' => $header['pr_no'],
			'po_status' => $po_after ? $po_after['status'] : 'pending',
			'redirect' => base_url('purchase-in/receipts/view/' . $receipt_id)
		));
	}

	private function piLog($entity_type, $entity_id, $entity_label, $action, $action_label, $meta = NULL)
	{
		$actor_name = '';
		if (!empty($this->current_vendor['name'])) {
			$actor_name = $this->current_vendor['name'];
		} elseif (!empty($this->current_vendor['business_name'])) {
			$actor_name = $this->current_vendor['business_name'];
		}
		$this->Purchase_in_model->log_activity($this->ownerId(), array(
			'entity_type' => $entity_type,
			'entity_id' => $entity_id,
			'entity_label' => $entity_label,
			'action' => $action,
			'action_label' => $action_label,
			'actor_id' => $this->ownerId(),
			'actor_name' => $actor_name,
			'meta' => $meta,
			'ip_address' => $this->input->ip_address()
		));
	}
}
