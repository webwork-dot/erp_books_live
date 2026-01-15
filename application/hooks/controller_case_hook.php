<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Case Normalization Hook
 * 
 * Normalizes controller directory and class names to match actual folder structure
 * This fixes case sensitivity issues between routes and controller folders
 * 
 * Note: This hook is a backup solution. The primary fix is in core/MY_Router.php
 */
function normalize_controller_case()
{
	// Check if CodeIgniter instance and router are available
	if (!function_exists('get_instance'))
	{
		return;
	}
	
	$CI =& get_instance();
	
	// Check if router is available
	if (!isset($CI->router) || !is_object($CI->router))
	{
		return;
	}
	
	// Get the current directory and controller from the router
	$directory = isset($CI->router->directory) ? $CI->router->directory : '';
	$class = isset($CI->router->class) ? $CI->router->class : '';
	
	// Check if directory exists and normalize case
	if (!empty($directory))
	{
		$controllers_path = APPPATH . 'controllers/';
		$dir_lower = strtolower(trim($directory, '/'));
		
		// Check actual directory structure and correct case
		if ($dir_lower === 'erp_admin')
		{
			if (is_dir($controllers_path . 'Erp_admin'))
			{
				$CI->router->set_directory('Erp_admin/');
			}
		}
		elseif ($dir_lower === 'client_admin')
		{
			if (is_dir($controllers_path . 'Client_admin'))
			{
				$CI->router->set_directory('Client_admin/');
			}
		}
		elseif ($dir_lower === 'school_admin')
		{
			if (is_dir($controllers_path . 'School_admin'))
			{
				$CI->router->set_directory('School_admin/');
			}
		}
	}
	
	// Normalize controller class names (first letter uppercase)
	if (!empty($class))
	{
		$normalized_class = ucfirst(strtolower($class));
		if ($class !== $normalized_class)
		{
			$CI->router->set_class($normalized_class);
		}
	}
}

