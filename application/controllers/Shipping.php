<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Public Shipping Controller
 * 
 * Displays shipping details when QR code is scanned
 * This is a public controller (no authentication required)
 */

class Shipping extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->model('Order_model');
		$this->load->model('shipping_model');
	}

	/**
	 * Display shipping details by shipping number
	 * 
	 * @param string $shipping_number Shipping number from QR code
	 */
	public function index($shipping_number = '')
	{
		if (empty($shipping_number)) {
			show_error('Shipping number is required', 400);
			return;
		}

		// Get order by shipping number
		$order_result = $this->db->select('*')
			->from('tbl_order_details')
			->where('ship_order_id', $shipping_number)
			->limit(1)
			->get()
			->result();

		if (empty($order_result)) {
			show_error('Shipping details not found', 404);
			return;
		}

		$order = $order_result[0];
		$order_id = $order->id;

		// Get all order items
		$items_arr = $this->db->select('*')
			->from('tbl_order_items')
			->where('order_id', $order_id)
			->order_by('id', 'ASC')
			->get()
			->result();

		// Get order address
		$address_arr = $this->db->select('*')
			->from('tbl_order_address')
			->where('order_id', $order_id)
			->order_by('id', 'ASC')
			->limit(1)
			->get()
			->result();

		// Prepare data for view
		$data = array(
			'order' => $order,
			'items' => $items_arr,
			'address' => !empty($address_arr) ? $address_arr[0] : null,
			'shipping_number' => $shipping_number
		);

		// Load view
		$this->load->view('public/shipping_details', $data);
	}
	
	
	public function bigship_token($token = null){
		if (!$token) show_error('Unauthorized', 403);

		list($key, $vendor_id) = explode('_', $token);

		if ($key !== SECURE_KEY || !is_numeric($vendor_id)) {
			show_error('Unauthorized', 403);
		}

		$this->shipping_model->bigship_token((int)$vendor_id);

		echo "Done";
	}

	/**
	 * Customer invoice download (for frontend users on kirtibook.in)
	 * URL: https://master.kirtibook.in/shipping/customer_invoice/ORD123
	 *
	 * @param string $order_unique_id Order unique ID (e.g. ORD260310645)
	 */
	public function customer_invoice($order_unique_id = '')
	{
		$order_unique_id = trim($order_unique_id);

		if (empty($order_unique_id)) {
			show_error('Invalid request', 400);
			return;
		}

		$order_result = $this->db->select('*')
			->from('tbl_order_details')
			->where('order_unique_id', $order_unique_id)
			->limit(1)
			->get()
			->result();

		if (empty($order_result)) {
			show_error('Invoice not found', 404);
			return;
		}

		$order = $order_result[0];
		$order_id = $order->id;

		// Check payment status
		$payment_ok = in_array($order->payment_status, array('success', 'cod', 'payment_at_school')) ||
			in_array($order->payment_method, array('cod', 'payment_at_school'));
		if (!$payment_ok) {
			show_error('Invoice not available for this order', 403);
			return;
		}

		// Stream existing invoice file if available
		if (!empty($order->invoice_url)) {
			$file_path = is_file($order->invoice_url) ? $order->invoice_url : FCPATH . ltrim($order->invoice_url, '/');
			if ($file_path && file_exists($file_path)) {
				$this->load->helper('download');
				$data = file_get_contents($file_path);
				force_download('invoice_' . $order->order_unique_id . '.pdf', $data);
				return;
			}
		}

		// Generate invoice on the fly
		$order_row = $this->db->select('*')->from('tbl_order_details')->where('id', $order_id)->get()->row_array();
		if (empty($order_row)) {
			show_error('Order not found', 404);
			return;
		}

		$shipping = array();
		$addr = $this->db->select('*')->from('tbl_order_address')->where('order_id', $order_id)->limit(1)->get()->row_array();
		if ($addr) $shipping = $addr;

		$products = $this->db->select('id,product_id,product_title,product_sku,product_qty,variation_name,product_mrp,product_price,product_gst,total_gst_amt,total_price,discount_amt,hsn,excl_price,excl_price_total')
			->from('tbl_order_items')->where('order_id', $order_id)->get()->result_array();

		$gst_total = 0;
		$total_product_discount = 0;
		foreach ($products as $p) {
			$gst_total += isset($p['total_gst_amt']) ? $p['total_gst_amt'] : 0;
			$total_product_discount += isset($p['discount_amt']) ? $p['discount_amt'] : 0;
		}

		$invoice_no = !empty($order_row['invoice_no']) ? $order_row['invoice_no'] : $this->_shipping_gen_invoice_no($order_id);
		if (empty($order_row['invoice_no'])) {
			$this->db->where('id', $order_id)->update('tbl_order_details', array('invoice_no' => $invoice_no));
		}

		$order_details = array(
			'id' => $order_row['id'],
			'order_unique_id' => $order_row['order_unique_id'],
			'user_name' => $order_row['user_name'],
			'user_email' => $order_row['user_email'],
			'user_phone' => $order_row['user_phone'],
			'order_date' => date("d M Y | h:i A", strtotime($order_row['order_date'])),
			'invoice_date' => !empty($order_row['invoice_date']) ? date("d M Y", strtotime($order_row['invoice_date'])) : date("d M Y"),
			'invoice_no' => $invoice_no,
			'payable_amt' => $order_row['payable_amt'],
			'discount_amt' => $order_row['discount_amt'],
			'delivery_charge' => isset($order_row['delivery_charge']) ? $order_row['delivery_charge'] : 0,
			'payment_method' => $order_row['payment_method'],
			'currency' => isset($order_row['currency']) ? $order_row['currency'] : 'INR',
			'currency_code' => isset($order_row['currency_code']) ? $order_row['currency_code'] : '₹',
			'shipping' => $shipping,
			'products' => $products,
			'gst_total' => $gst_total,
			'total_product_discount' => $total_product_discount,
			'freight_charges' => isset($order_row['freight_charges']) ? $order_row['freight_charges'] : 0,
			'freight_gst' => isset($order_row['freight_gst']) ? $order_row['freight_gst'] : 0,
			'freight_charges_excl' => isset($order_row['freight_charges_excl']) ? $order_row['freight_charges_excl'] : 0,
			'freight_gst_per' => isset($order_row['freight_gst_per']) ? $order_row['freight_gst_per'] : 0,
		);

		$order_details['logo_src'] = $this->_shipping_get_logo_base64();
		$company = $this->_shipping_get_company();
		$order_details['company_name'] = !empty($company['name']) ? $company['name'] : 'Kirti Book';
		$order_details['company_address'] = !empty($company['address']) ? $company['address'] : '';
		if (!empty($company['pincode'])) $order_details['company_address'] = trim($order_details['company_address'] . ', ' . $company['pincode']);
		$order_details['company_gstin'] = !empty($company['gstin']) ? $company['gstin'] : '-';
		$order_details['company_pan'] = !empty($company['pan']) ? $company['pan'] : '-';
		$order_details['company_phone'] = isset($company['contact_number']) ? $company['contact_number'] : '';

		$order_details['order_type_label'] = $this->_shipping_get_order_type($order_id, $order_row);
		$order_details['items_arr'] = $this->db->select('*')->from('tbl_order_items')->where('order_id', $order_id)->order_by('id', 'ASC')->get()->result();
		$order_details['bookset_products'] = array();
		if ($order_details['order_type_label'] == 'Bookset' && $this->db->table_exists('tbl_order_bookset_products')) {
			$order_details['bookset_products'] = $this->db->select('*')->from('tbl_order_bookset_products')->where('order_id', $order_id)->order_by('package_id', 'ASC')->order_by('id', 'ASC')->get()->result();
		}
		$school_name = !empty($order_row['school_name']) ? $order_row['school_name'] : '';
		if (empty($school_name) && $this->db->table_exists('erp_schools')) {
			$si = $this->db->select('s.school_name')->from('tbl_order_items oi')->join('erp_schools s', 's.id = oi.school_id', 'left')->where('oi.order_id', $order_id)->where('oi.school_id IS NOT NULL')->limit(1)->get()->row();
			if ($si && !empty($si->school_name)) $school_name = $si->school_name;
		}
		$order_details['order_obj'] = (object) array_merge($order_row, array('school_name' => $school_name));

		@ini_set('memory_limit', '256M');
		$this->load->helper('common');
		$this->load->library('pdf');
		$page_data['data'] = $order_details;
		$invoice_view = APPPATH . 'views/invoice/invoice_bill.php';
		if (!file_exists($invoice_view)) {
			show_error('Invoice template not found', 500);
			return;
		}
		$html_content = $this->load->view('invoice/invoice_bill', $page_data, TRUE);

		$this->pdf->set_paper('A4', 'portrait');
		error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
		$this->pdf->set_option('isHtml5ParserEnabled', TRUE);
		$this->pdf->load_html($html_content);
		$this->pdf->render();
		$this->pdf->stream('invoice_' . $order->order_unique_id . '.pdf', array('Attachment' => 1));
		exit;
	}

	private function _shipping_gen_invoice_no($order_id)
	{
		$prefix = 'INV/' . date('y') . '-' . date('m') . '/';
		$this->db->like('invoice_no', $prefix, 'after');
		$rows = $this->db->select('invoice_no')->from('tbl_order_details')->get()->result();
		$max_seq = 0;
		$pl = strlen($prefix);
		foreach ($rows as $r) {
			if (empty($r->invoice_no)) continue;
			$n = (int) preg_replace('/[^0-9]/', '', substr($r->invoice_no, $pl));
			if ($n > $max_seq) $max_seq = $n;
		}
		$next = $max_seq + 1;
		$digits = $next >= 1000 ? strlen((string)$next) : 3;
		return $prefix . str_pad((string)$next, $digits, '0', STR_PAD_LEFT);
	}

	private function _shipping_get_company()
	{
		if (!$this->db->table_exists('erp_clients')) return array();
		$cols = array('name', 'address', 'pincode', 'pan', 'gstin');
		if ($this->db->field_exists('contact_number', 'erp_clients')) $cols[] = 'contact_number';
		$row = $this->db->select(implode(', ', $cols))->from('erp_clients')->limit(1)->get()->row_array();
		return is_array($row) ? $row : array();
	}

	private function _shipping_get_logo_base64()
	{
		if ($this->db->table_exists('erp_clients')) {
			$r = $this->db->select('logo')->from('erp_clients')->limit(1)->get()->row();
			if (!empty($r->logo)) {
				$p = FCPATH . ltrim($r->logo, '/');
				if (file_exists($p) && @filesize($p) <= 300000) {
					$d = @file_get_contents($p);
					if ($d !== false) {
						$info = @getimagesize($p);
						$mime = ($info && isset($info['mime'])) ? $info['mime'] : 'image/png';
						return 'data:' . $mime . ';base64,' . base64_encode($d);
					}
				}
			}
		}
		return '';
	}

	private function _shipping_get_order_type($order_id, $order_row)
	{
		$t = isset($order_row['type_order']) ? strtolower($order_row['type_order']) : '';
		if (!empty($t)) return ucfirst($t);
		$items = $this->db->select('order_type')->from('tbl_order_items')->where('order_id', $order_id)->get()->result();
		foreach ($items as $i) {
			if (isset($i->order_type) && in_array($i->order_type, array('bookset', 'package'))) return 'Bookset';
			if (isset($i->order_type) && $i->order_type == 'uniform') return 'Uniform';
		}
		return 'Individual';
	}

    
}

