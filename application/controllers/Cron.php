<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('cron_model');
	}

	public function bigship_token($token = null)
	{
		if (!$token)
			show_error('Unauthorized', 403);

		list($key, $vendor_id) = explode('_', $token);

		if ($key !== SECURE_KEY || !is_numeric($vendor_id)) {
			show_error('Unauthorized', 403);
		}

		$this->cron_model->bigship_token((int) $vendor_id);
		echo "Done";
	}

	public function bigship_assign_courier($token = null)
	{
		if (!$token)
			show_error('Unauthorized', 403);

		list($key, $vendor_id) = explode('_', $token);

		if ($key !== SECURE_KEY || !is_numeric($vendor_id)) {
			show_error('Unauthorized', 403);
		}

		$this->cron_model->bigship_assign_courier((int) $vendor_id);
		echo "Done";
	}

	public function bigship_update_failed_awb_courier($token = null)
	{
		if (!$token)
			show_error('Unauthorized', 403);

		list($key, $vendor_id) = explode('_', $token);

		if ($key !== SECURE_KEY || !is_numeric($vendor_id)) {
			show_error('Unauthorized', 403);
		}

		$this->cron_model->bigship_update_failed_awb_courier((int) $vendor_id);
		echo "Done";
	}

	public function bigship_tracking($token = null)
	{
		if (!$token)
			show_error('Unauthorized', 403);

		list($key, $vendor_id) = explode('_', $token);

		if ($key !== SECURE_KEY || !is_numeric($vendor_id)) {
			show_error('Unauthorized', 403);
		}

		$this->cron_model->bigship_tracking((int) $vendor_id);
		echo "Done";
	}

	public function velocity_tracking($token = null)
	{
		if (!$token)
			show_error('Unauthorized', 403);

		list($key, $vendor_id) = explode('_', $token);

		if ($key !== SECURE_KEY || !is_numeric($vendor_id)) {
			show_error('Unauthorized', 403);
		}

		$this->cron_model->velocity_tracking((int) $vendor_id);
		echo "Done";
	}

	public function shiprocket_tracking($token = null)
	{
		if (!$token)
			show_error('Unauthorized', 403);

		list($key, $vendor_id) = explode('_', $token);

		if ($key !== SECURE_KEY || !is_numeric($vendor_id)) {
			show_error('Unauthorized', 403);
		}

		$this->cron_model->shiprocket_tracking((int) $vendor_id);
		echo "Done";
	}

	public function shiprocket_update_failed_awb_courier($token = null)
	{
		if (!$token)
			show_error('Unauthorized', 403);

		list($key, $vendor_id) = explode('_', $token);

		if ($key !== SECURE_KEY || !is_numeric($vendor_id)) {
			show_error('Unauthorized', 403);
		}

		$result = $this->cron_model->shiprocket_update_failed_awb_courier((int) $vendor_id);

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

	public function shiprocket_token($token = null)
	{
		if (!$token)
			show_error('Unauthorized', 403);

		list($key, $vendor_id) = explode('_', $token);

		if ($key !== SECURE_KEY || !is_numeric($vendor_id)) {
			show_error('Unauthorized', 403);
		}

		$this->cron_model->get_shiprocket_token((int) $vendor_id);
		echo "Done";
	}

}

