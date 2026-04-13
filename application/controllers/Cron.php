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
   
}

