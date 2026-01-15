<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ERP Admin Search Controller
 *
 * Handles global search for admin
 *
 * @package		ERP
 * @subpackage	Controllers
 * @category	Controllers
 * @author		ERP Team
 */

// Load base controller
require_once(APPPATH . 'controllers/Erp_admin/Erp_base.php');

class Search extends Erp_base
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
	 * Global search
	 *
	 * @return	void
	 */
	public function index()
	{
		$query = $this->input->get('q');
		$query = trim($query);
		
		$results = array(
			'vendors' => array(),
			'users' => array()
		);
		
		if (!empty($query)) {
			// Search vendors
			$this->db->select('id, name, domain, username, status');
			$this->db->from('erp_clients');
			$this->db->group_start();
			$this->db->like('name', $query);
			$this->db->or_like('domain', $query);
			$this->db->or_like('username', $query);
			$this->db->group_end();
			$this->db->limit(10);
			$results['vendors'] = $this->db->get()->result_array();
			
			// Search users
			$this->load->model('Erp_user_model');
			$this->db->select('id, username, email, user_type');
			$this->db->from('erp_users');
			$this->db->group_start();
			$this->db->like('username', $query);
			$this->db->or_like('email', $query);
			$this->db->group_end();
			$this->db->limit(10);
			$results['users'] = $this->db->get()->result_array();
		}
		
		$data['title'] = 'Search Results';
		$data['current_user'] = $this->current_user;
		$data['query'] = $query;
		$data['results'] = $results;
		$data['breadcrumb'] = array(
			array('label' => 'Dashboard', 'url' => 'erp-admin/dashboard'),
			array('label' => 'Search', 'active' => true)
		);
		
		// Load content view
		$data['content'] = $this->load->view('erp_admin/search/index', $data, TRUE);
		
		// Load main layout
		$this->load->view('erp_admin/layouts/index_template', $data);
	}
}

