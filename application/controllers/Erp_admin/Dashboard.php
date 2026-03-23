<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ERP Admin Dashboard Controller
 *
 * @package		ERP
 * @subpackage	Controllers
 * @category	Controllers
 * @author		ERP Team
 */

// Load base controller
require_once(APPPATH . 'controllers/Erp_admin/Erp_base.php');

class Dashboard extends Erp_base
{
	/**
	 * Constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Erp_client_model');
	}
	
	/**
	 * Dashboard index
	 *
	 * @return	void
	 */
	public function index()
	{
		// Get statistics
		$data['total_vendors'] = $this->Erp_client_model->getTotalClients();
		$data['active_vendors'] = $this->Erp_client_model->getTotalClients(array('status' => 'active'));
		$data['suspended_vendors'] = $this->Erp_client_model->getTotalClients(array('status' => 'suspended'));
		$data['recent_vendors'] = $this->Erp_client_model->getRecentClients(5);
		
		$data['title'] = 'ERP Admin Dashboard';
		$data['current_user'] = $this->current_user;
		$data['breadcrumb'] = array(
			array('label' => 'Dashboard', 'active' => true)
		);
		
		// Load content view
		$data['content'] = $this->load->view('erp_admin/dashboard/index', $data, TRUE);
		
		// Load main layout
		$this->load->view('erp_admin/layouts/index_template', $data);
	}
}

