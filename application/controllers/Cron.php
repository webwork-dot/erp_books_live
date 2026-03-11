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

   
}

