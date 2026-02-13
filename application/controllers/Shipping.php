<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Public Shipping Controller
 * 
 * Displays shipping details when QR code is scanned
 * This is a public controller (no authentication required)
 */
class Shipping extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('Order_model');
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
}

