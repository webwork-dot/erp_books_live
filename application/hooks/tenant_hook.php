<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Tenant Resolution Hook
 *
 * This hook resolves the tenant (client) based on the incoming request
 * and switches the database connection to the appropriate client database.
 *
 * @package		ERP
 * @subpackage	Hooks
 * @category	Hooks
 * @author		ERP Team
 */

if (!function_exists('resolve_tenant'))
{
	/**
	 * Resolve tenant and switch database
	 *
	 * This function is called after controller constructor.
	 * It identifies the tenant from the request and switches the database.
	 *
	 * @return	void
	 */
	function resolve_tenant()
	{
		// Get CodeIgniter instance
		$CI =& get_instance();
		
		// Check if CI is properly initialized
		if (!isset($CI) || !is_object($CI))
		{
			return;
		}
		
		// Check if config is available
		if (!property_exists($CI, 'config') || !is_object($CI->config))
		{
			return;
		}
		
		try {
			// Load tenant configuration
			$CI->config->load('tenant', TRUE);
			$tenant_config = $CI->config->item('tenant');
			
			if (!$tenant_config || !is_array($tenant_config))
			{
				return;
			}
			
			// Check if URI is available
			if (!property_exists($CI, 'uri') || !is_object($CI->uri))
			{
				return;
			}
			
			// Get HTTP_HOST first - prioritize domain-based resolution
			$http_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
			
			// Remove port if present (e.g., localhost:8080 -> localhost)
			if (strpos($http_host, ':') !== false) {
				$http_host = substr($http_host, 0, strpos($http_host, ':'));
			}
			
			// Get current URI segments
			$first_segment = $CI->uri->segment(1);
			
			// Check if this route should skip tenant resolution
			$skip_routes = isset($tenant_config['skip_tenant_routes']) ? $tenant_config['skip_tenant_routes'] : array('erp-admin', 'erp_admin', 'api', 'frontend', 'Frontend');
			
			// Check if HTTP_HOST matches a reserved/admin domain (like localhost, erp-admin domain, etc.)
			$is_admin_domain = false;
			if (strpos($http_host, 'localhost') !== false || 
				strpos($http_host, '127.0.0.1') !== false ||
				strpos($http_host, 'erp-admin') !== false ||
				$http_host === '') {
				$is_admin_domain = true;
			}
			
			// Skip tenant resolution for certain routes or admin domains
			if ($is_admin_domain || (empty($first_segment) || $first_segment === FALSE || in_array($first_segment, $skip_routes)))
			{
				// Use master database for ERP admin, API, and frontend home
				return;
			}
			
			// Skip for Frontend controller
			if (strtolower($first_segment) === 'frontend')
			{
				return;
			}
			
			// Check if load library method exists
			if (!property_exists($CI, 'load') || !is_object($CI->load))
			{
				return;
			}
			
			// Load Tenant library
			$CI->load->library('Tenant');
			
			// Check if tenant library loaded
			if (!property_exists($CI, 'tenant') || !is_object($CI->tenant))
			{
				return;
			}
			
			// Check if vendor session exists - if so, resolve by vendor_id first
			$vendor_id = NULL;
			$tenant = NULL; // Initialize tenant variable
			if (property_exists($CI, 'session') && is_object($CI->session))
			{
				$vendor_id = $CI->session->userdata('vendor_id');
			}
			
			// If vendor session exists, try to resolve by vendor ID first
			if ($vendor_id)
			{
				$tenant = $CI->tenant->resolveById($vendor_id);
			}
			
			// If not resolved yet, try configured resolution method
			// PRIORITIZE HTTP_HOST domain resolution first
			if (!$tenant && !empty($http_host) && !$is_admin_domain)
			{
				// Try to resolve by HTTP_HOST domain first
				$tenant = $CI->tenant->resolveByDomain($http_host);
			}
			
			// If still not resolved, try configured resolution method
			if (!$tenant)
			{
				$resolution_method = isset($tenant_config['tenant_resolution_method']) ? $tenant_config['tenant_resolution_method'] : 'domain';
				
				switch ($resolution_method)
				{
					case 'domain':
						// Already tried HTTP_HOST above, now try URI segment as fallback
						if (!$tenant && !empty($first_segment) && $first_segment !== FALSE && !in_array($first_segment, $skip_routes))
						{
							// Try resolving by domain name from URI segment using tenant library
							$tenant = $CI->tenant->resolveByDomain($first_segment);
						}
						break;
						
					case 'subdomain':
						$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
						if ($host)
						{
							$separator = isset($tenant_config['subdomain_separator']) ? $tenant_config['subdomain_separator'] : '.';
							$parts = explode($separator, $host);
							if (count($parts) > 1)
							{
								$subdomain = $parts[0];
								$tenant = $CI->tenant->resolveBySubdomain($subdomain);
							}
						}
						break;
						
					case 'path':
						$path_segment = isset($tenant_config['path_segment']) ? $tenant_config['path_segment'] : 1;
						$tenant_id = $CI->uri->segment($path_segment);
						if ($tenant_id)
						{
							$tenant = $CI->tenant->resolveById($tenant_id);
						}
						break;
						
					case 'header':
						$header_name = isset($tenant_config['tenant_header']) ? $tenant_config['tenant_header'] : 'X-Tenant-ID';
						$header_key = 'HTTP_' . str_replace('-', '_', strtoupper($header_name));
						$tenant_id = isset($_SERVER[$header_key]) ? $_SERVER[$header_key] : NULL;
						if ($tenant_id)
						{
							$tenant = $CI->tenant->resolveById($tenant_id);
						}
						break;
				}
			}
			
			// If tenant not resolved, try default
			if (!$tenant && isset($tenant_config['default_tenant']) && $tenant_config['default_tenant'])
			{
				$tenant = $CI->tenant->resolveById($tenant_config['default_tenant']);
			}
			
			// If tenant found, switch database (but only if not already switched by Vendor_base)
			if ($tenant && method_exists($CI->tenant, 'switchDatabase'))
			{
				// Check if database is already switched (check current database name)
				$current_db = isset($CI->db) && is_object($CI->db) && property_exists($CI->db, 'database') ? $CI->db->database : NULL;
				
				// Only switch if not already on the correct database
				if ($current_db !== $tenant['database_name'])
				{
					$CI->tenant->switchDatabase($tenant);
				}
			}
		}
		catch (Exception $e)
		{
			// Silently fail - use master database
			if (function_exists('log_message'))
			{
				log_message('error', 'Tenant hook error: ' . $e->getMessage());
			}
		}
	}
}
