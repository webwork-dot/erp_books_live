<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Frontend Home Controller
 *
 * Default controller for the frontend
 *
 * @package		ERP
 * @subpackage	Controllers
 * @category	Controllers
 * @author		ERP Team
 */
class Home extends CI_Controller
{
	/**
	 * Constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->library('Tenant');
	}
	
	/**
	 * Index page
	 *
	 * @return	void
	 */
	public function index()
	{
		$tenant = $this->tenant->getClient();
		
		// Allow homepage to work without tenant (for initial setup)
		$data['title'] = 'Welcome';
		$data['tenant'] = $tenant;
		
		$this->load->view('frontend/home/index', $data);
	}
}

