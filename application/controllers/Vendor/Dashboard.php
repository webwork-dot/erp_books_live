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
		
		// Load enabled features for dynamic dashboard cards
		// Use the same method as index_template.php to ensure consistency
		$data['enabled_features'] = $this->loadEnabledFeatures();
		
		// Prepare dashboard data
		$data['title'] = 'Dashboard - ' . $this->current_vendor['name'];
		$data['current_vendor'] = $this->current_vendor;
		$data['vendor_domain'] = $this->getVendorDomainForUrl();
		
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
		
		// Feature-based statistics (dynamic based on enabled features)
		$data['feature_stats'] = array();
		
		// Map feature slugs to their product types and models
		$feature_config = array(
			'uniforms' => array(
				'product_type' => 'uniform',
				'model' => 'Uniform_model',
				'name' => 'Uniform'
			),
			'stationery' => array(
				'product_type' => 'stationery',
				'model' => null, // Will query directly
				'name' => 'Stationery'
			),
			'textbook' => array(
				'product_type' => 'textbook',
				'model' => null, // Will query directly
				'name' => 'Textbook'
			),
			'notebooks' => array(
				'product_type' => 'notebook',
				'model' => null, // Will query directly
				'name' => 'Notebook'
			)
		);
		
		// Get statistics for each enabled feature
		foreach ($data['enabled_features'] as $feature) {
			$feature_slug = isset($feature['slug']) ? $feature['slug'] : '';
			
			if (isset($feature_config[$feature_slug])) {
				$config = $feature_config[$feature_slug];
				$product_type = $config['product_type'];
				
				// Get order statistics
				$orders = array(
					'new_order' => $this->getOrderCountByProductType($vendor_id, $product_type, 'pending'),
					'processing' => $this->getOrderCountByProductType($vendor_id, $product_type, 'processing'),
					'ready_for_ship' => $this->getOrderCountByProductType($vendor_id, $product_type, 'shipment'),
					'out_for_delivery' => $this->getOrderCountByProductType($vendor_id, $product_type, 'out_for_delivery'),
					'delivered' => $this->getOrderCountByProductType($vendor_id, $product_type, 'delivered')
				);
				
				// Get product statistics
				$products = $this->getProductStatsByType($vendor_id, $product_type);
				
				$data['feature_stats'][$feature_slug] = array(
					'name' => $config['name'],
					'slug' => $feature_slug,
					'orders' => $orders,
					'products' => $products
				);
			}
		}
		
		// Uniform Orders Statistics (for backward compatibility)
		$data['uniform_orders'] = isset($data['feature_stats']['uniforms']['orders']) 
			? $data['feature_stats']['uniforms']['orders'] 
			: array('new_order' => 0, 'processing' => 0, 'ready_for_ship' => 0, 'out_for_delivery' => 0, 'delivered' => 0);
		
		// Uniform Products Statistics (for backward compatibility)
		$data['uniform_products'] = isset($data['feature_stats']['uniforms']['products']) 
			? $data['feature_stats']['uniforms']['products'] 
			: array('active' => 0, 'inactive' => 0, 'out_of_stock' => 0);
		
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
	
	/**
	 * Get order count by product type and status
	 *
	 * @param	int	$vendor_id	Vendor ID
	 * @param	string	$product_type	Product type (uniform, stationery, textbook, notebook)
	 * @param	string	$order_status	Order status
	 * @return	int	Count of orders
	 */
	private function getOrderCountByProductType($vendor_id, $product_type, $order_status)
	{
		$this->db->select('COUNT(DISTINCT erp_orders.id) as count');
		$this->db->from('erp_orders');
		$this->db->join('erp_order_items', 'erp_order_items.order_id = erp_orders.id', 'inner');
		$this->db->where('erp_orders.vendor_id', $vendor_id);
		$this->db->where('erp_orders.order_status', $order_status);
		$this->db->where('erp_order_items.product_type', $product_type);
		
		$query = $this->db->get();
		$result = $query->row_array();
		
		return isset($result['count']) ? (int)$result['count'] : 0;
	}
	
	/**
	 * Get product statistics by type
	 *
	 * @param	int	$vendor_id	Vendor ID
	 * @param	string	$product_type	Product type (uniform, stationery, textbook, notebook)
	 * @return	array	Array with active, inactive, and out_of_stock counts
	 */
	private function getProductStatsByType($vendor_id, $product_type)
	{
		$stats = array('active' => 0, 'inactive' => 0, 'out_of_stock' => 0);
		
		switch ($product_type) {
			case 'uniform':
				$stats['active'] = $this->Uniform_model->getUniformCountByStatus($vendor_id, 'active');
				$stats['inactive'] = $this->Uniform_model->getUniformCountByStatus($vendor_id, 'inactive');
				$stats['out_of_stock'] = $this->Uniform_model->getOutOfStockCount($vendor_id);
				break;
				
			case 'stationery':
				// Query erp_stationery table
				$this->db->where('vendor_id', $vendor_id);
				$this->db->where('status', 'active');
				$stats['active'] = $this->db->count_all_results('erp_stationery');
				
				$this->db->where('vendor_id', $vendor_id);
				$this->db->where('status', 'inactive');
				$stats['inactive'] = $this->db->count_all_results('erp_stationery');
				
				// Stock quantity not available yet - set to 0
				$stats['out_of_stock'] = 0;
				break;
				
			case 'textbook':
				// Query erp_textbooks table
				$this->db->where('vendor_id', $vendor_id);
				$this->db->where('status', 'active');
				$stats['active'] = $this->db->count_all_results('erp_textbooks');
				
				$this->db->where('vendor_id', $vendor_id);
				$this->db->where('status', 'inactive');
				$stats['inactive'] = $this->db->count_all_results('erp_textbooks');
				
				// Stock quantity not available yet - set to 0
				$stats['out_of_stock'] = 0;
				break;
				
			case 'notebook':
				// Query erp_notebooks table
				$this->db->where('vendor_id', $vendor_id);
				$this->db->where('status', 'active');
				$stats['active'] = $this->db->count_all_results('erp_notebooks');
				
				$this->db->where('vendor_id', $vendor_id);
				$this->db->where('status', 'inactive');
				$stats['inactive'] = $this->db->count_all_results('erp_notebooks');
				
				// Stock quantity not available yet - set to 0
				$stats['out_of_stock'] = 0;
				break;
		}
		
		return $stats;
	}
	
	/**
	 * Load enabled features (same logic as index_template.php)
	 *
	 * @return	array	Array of enabled features
	 */
	private function loadEnabledFeatures()
	{
		$enabled_features = array();
		
		if (!isset($this->current_vendor['id']))
		{
			return $enabled_features;
		}
		
		// Try to load from vendor database first (preferred method)
		if (!empty($this->current_vendor['database_name'])) {
			try {
				// Check if vendor_features table exists in vendor database
				$table_check = $this->db->query("SHOW TABLES LIKE 'vendor_features'");
				if ($table_check && $table_check->num_rows() > 0) {
					$this->db->select('*');
					$this->db->from('vendor_features');
					$this->db->where('is_enabled', 1);
					$this->db->order_by('feature_id', 'ASC');
					$query = $this->db->get();
					
					if ($query && $query->num_rows() > 0) {
						$all_vendor_features = $query->result_array();
						
						// Get parent_id information from master database to filter main categories
						$feature_parent_map = array();
						$feature_active_map = array();
						
						try {
							// Temporarily switch to master database
							$this->load->database('default', FALSE, TRUE);
							$master_db = $this->db;
							
							// Get feature IDs to check
							$feature_ids = array();
							foreach ($all_vendor_features as $vf) {
								$feature_ids[] = (int)$vf['feature_id'];
							}
							
							if (!empty($feature_ids)) {
								$master_db->select('id, parent_id, is_active, slug, name');
								$master_db->from('erp_features');
								$master_db->where_in('id', $feature_ids);
								$master_query = $master_db->get();
								
								if ($master_query && $master_query->num_rows() > 0) {
									foreach ($master_query->result_array() as $mf) {
										$feature_parent_map[$mf['id']] = $mf['parent_id'];
										$feature_active_map[$mf['id']] = $mf['is_active'];
										
										// Only include main categories (no parent_id) that are active
										if (empty($mf['parent_id']) && $mf['is_active'] == 1) {
											$enabled_features[] = array(
												'id' => $mf['id'],
												'slug' => $mf['slug'],
												'name' => $mf['name']
											);
										}
									}
								}
							}
							
							// Switch back to vendor database
							if (!empty($this->current_vendor['database_name'])) {
								$this->load->library('Tenant');
								$this->tenant->switchDatabase($this->current_vendor);
							}
						} catch (Exception $e) {
							log_message('error', 'Error loading feature parent info: ' . $e->getMessage());
							// Switch back to vendor database
							if (!empty($this->current_vendor['database_name'])) {
								$this->load->library('Tenant');
								$this->tenant->switchDatabase($this->current_vendor);
							}
						}
					}
				}
			} catch (Exception $e) {
				log_message('error', 'Error loading features from vendor database: ' . $e->getMessage());
			}
		}
		
		// Fallback to master database check if no features found
		if (empty($enabled_features)) {
			$vendor_features = $this->Erp_client_model->getClientFeatures($this->current_vendor['id']);
			
			foreach ($vendor_features as $feature) {
				if ($feature['is_enabled'] == 1 && $feature['is_active'] == 1 && empty($feature['parent_id'])) {
					$enabled_features[] = array(
						'id' => $feature['id'],
						'slug' => isset($feature['slug']) ? $feature['slug'] : '',
						'name' => isset($feature['name']) ? $feature['name'] : ''
					);
				}
			}
		}
		
		return $enabled_features;
	}
}

