<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Extended Router Class
 * 
 * Handles case normalization for controller directories and classes
 * to fix case sensitivity issues between routes and actual folder structure
 * Also handles domain names with dots in URI segments
 */
class MY_Router extends CI_Router
{
	/**
	 * Set the directory name
	 *
	 * @param	string	$dir	Directory name
	 * @return	void
	 */
	public function set_directory($dir)
	{
		// Normalize the directory name to match actual folder structure
		$dir = trim($dir, '/');
		$dir_lower = strtolower($dir);
		
		// Check actual directory structure and correct case
		$controllers_path = APPPATH . 'controllers/';
		
		if ($dir_lower === 'erp_admin' && is_dir($controllers_path . 'Erp_admin'))
		{
			$dir = 'Erp_admin';
		}
		elseif ($dir_lower === 'client_admin' && is_dir($controllers_path . 'Client_admin'))
		{
			$dir = 'Client_admin';
		}
		elseif ($dir_lower === 'school_admin' && is_dir($controllers_path . 'School_admin'))
		{
			$dir = 'School_admin';
		}
		
		$this->directory = $dir . '/';
	}
	
	/**
	 * Set the class name
	 *
	 * @param	string	$class	Class name
	 * @return	void
	 */
	public function set_class($class)
	{
		// Normalize controller class names (first letter uppercase)
		$class = ucfirst(strtolower($class));
		$this->class = $class;
	}
	
	/**
	 * Parse Routes
	 * 
	 * Override to handle domain names with dots in URI segments
	 * Reconstructs domains like "varitty.in" from split segments
	 *
	 * @return	void
	 */
	protected function _parse_routes()
	{
		// Get original segments
		$segments = $this->uri->segments;
		
		// Reconstruct domain names with dots
		// If we have segments like ['varitty', 'in', 'login'], combine first two as 'varitty.in'
		if (count($segments) >= 2) {
			$reserved_routes = array('erp-admin', 'api', 'frontend', 'vendor', 'Vendor', 'auth', 'client-admin', 'school-admin');
			$first_segment = $segments[0];
			
			// If first segment is not a reserved route and second segment looks like a TLD
			if (!in_array($first_segment, $reserved_routes) && 
				preg_match('/^[a-z]{2,4}$/i', $segments[1]) && 
				!in_array($segments[1], $reserved_routes)) {
				
				// Check if this might be a domain (has common TLD patterns)
				$common_tlds = array('com', 'net', 'org', 'in', 'co', 'io', 'me', 'us', 'uk', 'au', 'ca');
				if (in_array(strtolower($segments[1]), $common_tlds) || 
					(count($segments) >= 3 && !in_array($segments[2], $reserved_routes))) {
					
					// Reconstruct domain: combine first two segments
					$domain = $segments[0] . '.' . $segments[1];
					
					// Remove the first two segments and replace with combined domain
					array_shift($segments);
					array_shift($segments);
					array_unshift($segments, $domain);
					
					// Update URI segments
					$this->uri->segments = $segments;
				}
			}
		}
		
		// Call parent method to continue with normal routing
		parent::_parse_routes();
	}
}

