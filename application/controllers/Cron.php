<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->model('cron_model');
	}
	
	public function bigship_token($token = null){
		if (!$token) show_error('Unauthorized', 403);

		list($key, $vendor_id) = explode('_', $token);

		if ($key !== SECURE_KEY || !is_numeric($vendor_id)) {
			show_error('Unauthorized', 403);
		}

		$this->cron_model->bigship_token((int)$vendor_id);
		echo "Done";
	}
	
	public function bigship_assign_courier($token = null){
		if (!$token) show_error('Unauthorized', 403);

		list($key, $vendor_id) = explode('_', $token);

		if ($key !== SECURE_KEY || !is_numeric($vendor_id)) {
			show_error('Unauthorized', 403);
		}

		$this->cron_model->bigship_assign_courier((int)$vendor_id);
		echo "Done";
	}

	public function bigship_update_failed_awb_courier($token = null){
		if (!$token) show_error('Unauthorized', 403);

		list($key, $vendor_id) = explode('_', $token);

		if ($key !== SECURE_KEY || !is_numeric($vendor_id)) {
			show_error('Unauthorized', 403);
		}

		$this->cron_model->bigship_update_failed_awb_courier((int)$vendor_id);
		echo "Done";
	}
	
	public function bigship_tracking($token = null){
		if (!$token) show_error('Unauthorized', 403);

		list($key, $vendor_id) = explode('_', $token);

		if ($key !== SECURE_KEY || !is_numeric($vendor_id)) {
			show_error('Unauthorized', 403);
		}

		$this->cron_model->bigship_tracking((int)$vendor_id);
		echo "Done";
	}
	
	public function velocity_tracking($token = null){
		if (!$token) show_error('Unauthorized', 403);

		list($key, $vendor_id) = explode('_', $token);

		if ($key !== SECURE_KEY || !is_numeric($vendor_id)) {
			show_error('Unauthorized', 403);
		}

		$this->cron_model->velocity_tracking((int)$vendor_id);
		echo "Done";
	}

	public function shiprocket_update_failed_awb_courier($token = null){
		if (!$token) show_error('Unauthorized', 403);

		list($key, $vendor_id) = explode('_', $token);

		if ($key !== SECURE_KEY || !is_numeric($vendor_id)) {
			show_error('Unauthorized', 403);
		}

		$result = $this->cron_model->shiprocket_update_failed_awb_courier((int)$vendor_id);
		
		// Output detailed result for better debugging
		if (is_array($result)) {
			echo "Status: " . ($result['status'] ?? 'unknown') . "\n";
			echo "Processed: " . ($result['processed'] ?? 0) . "\n";
			echo "Success: " . ($result['success'] ?? 0) . "\n";
			echo "Failed: " . ($result['failed'] ?? 0) . "\n";
			if (!empty($result['reason'])) {
				echo "Reason: " . $result['reason'] . "\n";
			}
		} else {
			echo "Done (legacy output)";
		}
	}
	public function shiprocket_token() {
        $this->cron_model->get_shiprocket_token();
    } 

	/**
	 * Public cron endpoint (no token, no vendor id).
	 * Sends order_placed notifications for all active vendors.
	 * URL: cron/send_order_placed_notifications
	 */
	public function send_order_placed_notifications()
	{
		$res = $this->cron_model->send_order_placed_notifications_all();
		header('Content-Type: text/plain; charset=utf-8');
		if (is_array($res)) {
			echo "Status: " . ($res['status'] ?? 'unknown') . "\n";
			echo "VendorsProcessed: " . ($res['vendors_processed'] ?? 0) . "\n";
			echo "OrdersProcessed: " . ($res['orders_processed'] ?? 0) . "\n";
			echo "EmailSent: " . ($res['email_sent'] ?? 0) . "\n";
			echo "EmailMarked: " . ($res['email_marked'] ?? 0) . "\n";
			if (!empty($res['errors'])) {
				echo "Errors: " . count($res['errors']) . "\n";
			}
			return;
		}
		echo "Done";
	}

	/**
	 * Public cron endpoint (no token, no vendor id): run only one database.
	 * URL: cron/send_order_placed_notifications/db/{database_name}
	 */
	public function send_order_placed_notifications_db($database_name = null)
	{
		$database_name = trim((string)$database_name);
		if ($database_name === '') {
			show_error('Database key required', 400);
		}

		$res = $this->cron_model->send_order_placed_notifications_by_db($database_name);
		header('Content-Type: text/plain; charset=utf-8');
		if (is_array($res)) {
			echo "Status: " . ($res['status'] ?? 'unknown') . "\n";
			echo "Processed: " . ($res['processed'] ?? 0) . "\n";
			echo "EmailSent: " . ($res['email_sent'] ?? 0) . "\n";
			echo "EmailMarked: " . ($res['email_marked'] ?? 0) . "\n";
			if (!empty($res['message'])) {
				echo "Message: " . $res['message'] . "\n";
			}
			if (!empty($res['errors']) && is_array($res['errors'])) {
				$first = array_slice($res['errors'], 0, 5);
				foreach ($first as $e) {
					echo "ErrorOrderId: " . ($e['order_id'] ?? '') . " | " . ($e['message'] ?? '') . "\n";
					if (!empty($e['debug'])) {
						$debug = strip_tags((string)$e['debug']);
						$debug = preg_replace('/\\s+/', ' ', $debug);
						// Keep output readable in browser
						echo "Debug: " . substr($debug, 0, 500) . "\n";
					}
				}
			}
			return;
		}
		echo "Done";
	}

	/**
	 * Send Order Placed notifications for a vendor (secured).
	 * URL: cron/send_order_placed_notifications/{SECURE_KEY}_{vendorId}
	 */
	public function send_order_placed_notifications_secured($token = null)
	{
		if (!$token) show_error('Unauthorized', 403);

		// Split on last underscore so SECURE_KEY can contain underscores
		$pos = strrpos($token, '_');
		if ($pos === false) {
			show_error('Unauthorized', 403);
		}
		$key = substr($token, 0, $pos);
		$vendor_id = substr($token, $pos + 1);

		if ($key !== SECURE_KEY || !is_numeric($vendor_id)) {
			show_error('Unauthorized', 403);
		}

		$res = $this->cron_model->send_order_placed_notifications((int)$vendor_id);
		if (is_array($res)) {
			header('Content-Type: text/plain; charset=utf-8');
			echo "Status: " . ($res['status'] ?? 'unknown') . "\n";
			echo "Processed: " . ($res['processed'] ?? 0) . "\n";
			echo "EmailSent: " . ($res['email_sent'] ?? 0) . "\n";
			echo "EmailMarked: " . ($res['email_marked'] ?? 0) . "\n";
			if (!empty($res['errors'])) {
				echo "Errors: " . count($res['errors']) . "\n";
			}
			return;
		}

		echo "Done";
	}

	/**
	 * Public cron endpoint to auto-assign invoice numbers to confirmed orders.
	 * URL: cron/auto_assign_invoices
	 */
	public function auto_assign_invoices()
	{
		$this->load->model('Crud_model');

		// Query confirmed orders that do not have an invoice number assigned yet
		$query = $this->db->query("
			SELECT id, order_unique_id, payment_status, payment_method 
			FROM tbl_order_details 
			WHERE (invoice_no IS NULL OR invoice_no = '') 
			  AND (payment_status = 'success' 
			       OR payment_status = 'cod' 
			       OR payment_status = 'payment_at_school' 
			       OR payment_method = 'payment_at_school')
			ORDER BY id ASC
		");

		$orders = $query->result_array();
		$processed = 0;
		$results = array();

		foreach ($orders as $order) {
			$order_id = (int)$order['id'];
			// Generate and assign invoice number
			$invoice_no = $this->Crud_model->update_invoice_number($order_id);
			if ($invoice_no) {
				$results[] = array(
					'order_id' => $order_id,
					'order_no' => $order['order_unique_id'],
					'invoice_no' => $invoice_no
				);
				$processed++;
			}
		}

		header('Content-Type: text/plain; charset=utf-8');
		echo "Status: SUCCESS\n";
		echo "Total Orders Processed: " . $processed . "\n\n";
		if (!empty($results)) {
			echo "Details:\n";
			foreach ($results as $res) {
				echo "Order ID: {$res['order_id']} | Order No: {$res['order_no']} | Assigned Invoice: {$res['invoice_no']}\n";
			}
		} else {
			echo "No pending confirmed orders found.\n";
		}
	}

	/**
	 * Public endpoint to fix invoice numbers that were assigned with the bad format,
	 * resolving any possible duplicates dynamically.
	 * URL: cron/fix_live_invoices
	 */
	public function fix_live_invoices()
	{
		// 1. Fetch all orders with any assigned invoice
		$query = $this->db->query("
			SELECT id, invoice_no 
			FROM tbl_order_details 
			WHERE invoice_no IS NOT NULL AND invoice_no != ''
			ORDER BY id ASC
		");
		
		$all_orders = $query->result_array();

		// 2. Classify orders and track taken invoices
		$taken_invoices = array();
		$bad_orders = array();

		foreach ($all_orders as $order) {
			$invoice_no = $order['invoice_no'];
			// Check if it matches bad format "MMYYYY/SS" e.g. "052026/07"
			if (preg_match('/^([0-9]{2})([0-9]{4})\/([0-9]+)$/', $invoice_no, $matches)) {
				$bad_orders[] = array(
					'id' => (int)$order['id'],
					'invoice_no' => $invoice_no,
					'mm' => $matches[1],
					'yyyy' => $matches[2],
					'seq' => intval($matches[3])
				);
			} else {
				// It's a valid invoice, so it is taken!
				$taken_invoices[$invoice_no] = (int)$order['id'];
			}
		}

		$processed = 0;
		$results = array();

		// 3. Resolve and update bad invoices uniquely
		foreach ($bad_orders as $order) {
			$mm = $order['mm'];
			$yyyy = $order['yyyy'];
			$yy = substr($yyyy, 2, 2);
			$seq = $order['seq'];
			$order_id = $order['id'];

			// Find a unique invoice number by incrementing sequence if taken
			do {
				$digits = $seq >= 1000 ? strlen((string)$seq) : 3;
				$good_inv = "INV/{$yy}-{$mm}/" . str_pad((string)$seq, $digits, '0', STR_PAD_LEFT);
				
				// It is taken if it exists in taken_invoices and belongs to a DIFFERENT order
				$is_taken = isset($taken_invoices[$good_inv]) && $taken_invoices[$good_inv] !== $order_id;
				if ($is_taken) {
					$seq++;
				}
			} while ($is_taken);

			// Mark as taken for subsequent iterations
			$taken_invoices[$good_inv] = $order_id;

			// Update tbl_order_details and order_user_invoice
			$this->db->query("UPDATE tbl_order_details SET invoice_no = ? WHERE id = ?", array($good_inv, $order_id));
			$this->db->query("UPDATE order_user_invoice SET user_invoice = ? WHERE order_id = ?", array($good_inv, $order_id));

			$results[] = array(
				'order_id' => $order_id,
				'old_invoice' => $order['invoice_no'],
				'new_invoice' => $good_inv
			);
			$processed++;
		}

		header('Content-Type: text/plain; charset=utf-8');
		echo "Status: SUCCESS\n";
		echo "Total Invoices Corrected & De-duplicated: " . $processed . "\n\n";
		if (!empty($results)) {
			echo "Details of Corrected Invoices:\n";
			foreach ($results as $res) {
				echo "Order ID: {$res['order_id']} | Old: {$res['old_invoice']} | New: {$res['new_invoice']}\n";
			}
		} else {
			echo "No incorrectly formatted invoices found.\n";
		}
	}
   
}

