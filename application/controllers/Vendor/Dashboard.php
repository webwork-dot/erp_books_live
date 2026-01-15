<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Vendor Dashboard Controller
 *
 * @package		ERP
 * @subpackage	Controllers
 * @category	Controllers
 * @author		ERP Team
 */

// Load base controller
require_once(APPPATH . 'controllers/Vendor/Vendor_base.php');

class Dashboard extends Vendor_base
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
		$this->load->model('Erp_feature_model');
		$this->load->model('Order_model');
		$this->load->model('Individual_product_model');
		$this->load->model('Uniform_model');
		$this->load->model('School_model');
	}
	
	/**
	 * Dashboard index
	 *
	 * @return	void
	 */
	public function index()
	{
		$vendor_id = $this->current_vendor['id'];
		
		// Don't load features here - let the template load them from vendor database
		// This ensures features are loaded from the correct database (vendor's own DB)
		
		// Prepare dashboard data
		$data['title'] = 'Dashboard - ' . $this->current_vendor['name'];
		$data['current_vendor'] = $this->current_vendor;
		// Don't set enabled_features - let template load from vendor database
		$data['vendor_domain'] = $this->current_vendor['domain'];
		
		// Calculate account age
		if (isset($this->current_vendor['created_at']) && !empty($this->current_vendor['created_at'])) {
			try {
				$created = new DateTime($this->current_vendor['created_at']);
				$now = new DateTime();
				$interval = $created->diff($now);
				$data['account_age_days'] = $interval->days;
				$data['account_age_months'] = $interval->m + ($interval->y * 12);
			} catch (Exception $e) {
				$data['account_age_days'] = 0;
				$data['account_age_months'] = 0;
			}
		} else {
			$data['account_age_days'] = 0;
			$data['account_age_months'] = 0;
		}
		
		// Individual Orders Statistics
		$data['individual_orders'] = array(
			'new_order' => $this->Order_model->getOrderCountByTypeAndStatus($vendor_id, 'individual', 'pending'),
			'processing' => $this->Order_model->getOrderCountByTypeAndStatus($vendor_id, 'individual', 'processing'),
			'ready_for_ship' => $this->Order_model->getOrderCountByTypeAndStatus($vendor_id, 'individual', 'shipment'),
			'out_for_delivery' => $this->Order_model->getOrderCountByTypeAndStatus($vendor_id, 'individual', 'out_for_delivery'),
			'delivered' => $this->Order_model->getOrderCountByTypeAndStatus($vendor_id, 'individual', 'delivered')
		);
		
		// Uniform Orders Statistics
		$data['uniform_orders'] = array(
			'new_order' => $this->Order_model->getOrderCountByTypeAndStatus($vendor_id, 'uniform', 'pending'),
			'processing' => $this->Order_model->getOrderCountByTypeAndStatus($vendor_id, 'uniform', 'processing'),
			'ready_for_ship' => $this->Order_model->getOrderCountByTypeAndStatus($vendor_id, 'uniform', 'shipment'),
			'out_for_delivery' => $this->Order_model->getOrderCountByTypeAndStatus($vendor_id, 'uniform', 'out_for_delivery'),
			'delivered' => $this->Order_model->getOrderCountByTypeAndStatus($vendor_id, 'uniform', 'delivered')
		);
		
		// Individual Products Statistics
		$data['individual_products'] = array(
			'active' => $this->Individual_product_model->getProductCountByStatus($vendor_id, 'active'),
			'inactive' => $this->Individual_product_model->getProductCountByStatus($vendor_id, 'inactive'),
			'out_of_stock' => $this->Individual_product_model->getOutOfStockCount($vendor_id)
		);
		
		// Uniform Products Statistics
		$data['uniform_products'] = array(
			'active' => $this->Uniform_model->getUniformCountByStatus($vendor_id, 'active'),
			'inactive' => $this->Uniform_model->getUniformCountByStatus($vendor_id, 'inactive'),
			'out_of_stock' => $this->Uniform_model->getOutOfStockCount($vendor_id)
		);
		
		// School Statistics
		$data['schools'] = array(
			'active' => $this->School_model->getTotalSchoolsByVendor($vendor_id, array('status' => 'active')),
			'inactive' => $this->School_model->getTotalSchoolsByVendor($vendor_id, array('status' => 'inactive'))
		);
		
		$data['breadcrumb'] = array(
			array('label' => 'Dashboard', 'active' => true)
		);
		
		// Load content view
		$data['content'] = $this->load->view('vendor/dashboard/index', $data, TRUE);
		
		// Load main layout
		$this->load->view('vendor/layouts/index_template', $data);
	}
}

