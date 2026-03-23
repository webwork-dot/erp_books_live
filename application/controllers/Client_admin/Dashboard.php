<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Client Admin Dashboard Controller
 *
 * @package		ERP
 * @subpackage	Controllers
 * @category	Controllers
 * @author		ERP Team
 */

// Load base controller
require_once(APPPATH . 'controllers/Client_admin/Client_base.php');

class Dashboard extends Client_base
{
	/**
	 * Constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Order_model');
		$this->load->model('Product_model');
	}
	
	/**
	 * Dashboard index
	 *
	 * @return	void
	 */
	public function index()
	{
		// Get statistics
		$data['total_orders'] = $this->Order_model->getTotalOrders();
		$data['pending_orders'] = $this->Order_model->getTotalOrders(array('status' => 'pending'));
		$data['total_products'] = $this->Product_model->getTotalProducts();
		$data['recent_orders'] = $this->Order_model->getRecentOrders(5);
		
		$data['title'] = 'Client Dashboard';
		$data['current_user'] = $this->current_user;
		$data['tenant'] = $this->current_tenant;
		
		$this->load->view('client_admin/layouts/header', $data);
		$this->load->view('client_admin/dashboard/index', $data);
		$this->load->view('client_admin/layouts/footer', $data);
	}
}

