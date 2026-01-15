<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Extended Router Class
 * 
 * Handles case normalization for controller directories and classes
 * to fix case sensitivity issues between routes and actual folder structure
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
}

