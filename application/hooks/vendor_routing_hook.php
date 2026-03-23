<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Vendor Domain Routing Hook
 *
 * This hook handles domain-based vendor routing by detecting vendor domains
 * from HTTP_HOST and routing to Vendor controllers without path prefix
 *
 * @package		ERP
 * @subpackage	Hooks
 * @category	Hooks
 * @author		ERP Team
 */

if (!function_exists('route_vendor_domain'))
{
	/**
	 * Route vendor domain requests
	 *
	 * This function is called before controller is loaded.
	 * It detects vendor domains and routes accordingly.
	 *
	 * @return	void
	 */
	function route_vendor_domain()
	{
		// Get CodeIgniter instance
		$CI =& get_instance();
		
		// Check if CI is properly initialized
		if (!isset($CI) || !is_object($CI))
		{
			return;
		}
		
		// Get HTTP_HOST
		$http_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
		if (strpos($http_host, ':') !== false) {
			$http_host = substr($http_host, 0, strpos($http_host, ':'));
		}
		
		// Skip if admin domain (localhost, 127.0.0.1, or erp-admin domain)
		if (empty($http_host) || 
			strpos($http_host, 'localhost') !== false || 
			strpos($http_host, '127.0.0.1') !== false ||
			strpos($http_host, 'erp-admin') !== false) {
			return;
		}
		
		// Load Erp_client_model to check if domain is a vendor
		if (!property_exists($CI, 'load') || !is_object($CI->load)) {
			return;
		}
		
		$CI->load->model('Erp_client_model');
		
		// Check if HTTP_HOST matches a vendor domain
		$vendor = $CI->Erp_client_model->getClientByDomain($http_host);
		
		if ($vendor && !empty($vendor['database_name'])) {
			// This is a vendor domain - store vendor info for later use
			// The tenant hook will switch the database
			// Routes will handle the controller routing normally
			// We just need to ensure URI segments don't include the domain
			
			// Get URI segments
			if (!property_exists($CI, 'uri') || !is_object($CI->uri)) {
				return;
			}
			
			$segments = $CI->uri->segments;
			
			// If first segment matches the domain, remove it (path-based fallback)
			if (!empty($segments) && $segments[0] === $http_host) {
				array_shift($segments);
				// Update URI segments
				$CI->uri->segments = $segments;
			}
			
			// If no segments after removing domain, default to dashboard
			if (empty($segments)) {
				$segments = array('dashboard');
				$CI->uri->segments = $segments;
			}
		}
	}
}

