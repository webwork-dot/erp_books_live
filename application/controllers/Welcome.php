<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Welcome Controller
 *
 * Default controller for the homepage
 *
 * @package		ERP
 * @subpackage	Controllers
 * @category	Controllers
 * @author		ERP Team
 */
class Welcome extends CI_Controller
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
	 * Index page - Redirects to login
	 *
	 * @return	void
	 */
	public function index()
	{
		// Redirect root domain to login page
		redirect('auth/login', 'location', 301);
	}
}

