<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Theme Helper
 *
 * Provides helper functions for consistent theming across the application
 *
 * @package		ERP
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ERP Team
 */

if (!function_exists('theme_css'))
{
	/**
	 * Get theme CSS file path
	 *
	 * @return	string	CSS file path
	 */
	function theme_css()
	{
		return base_url('assets/css/theme.css');
	}
}

if (!function_exists('theme_color'))
{
	/**
	 * Get theme color
	 *
	 * @param	string	$color_name	Color name (primary, secondary, success, etc.)
	 * @return	string	Color value
	 */
	function theme_color($color_name = 'primary')
	{
		$colors = array(
			'primary' => '#2563EB',
			'primary-dark' => '#1E40AF',
			'primary-soft' => '#E8F0FF',
			'secondary' => '#22C55E',
			'secondary-soft' => '#E9F9EF',
			'accent-yellow' => '#FACC15',
			'accent-purple' => '#8B5CF6',
			'accent-pink' => '#F472B6',
			'success' => '#22C55E',
			'danger' => '#DC2626',
			'warning' => '#FACC15',
			'info' => '#2563EB'
		);
		
		return isset($colors[$color_name]) ? $colors[$color_name] : $colors['primary'];
	}
}

if (!function_exists('btn_class'))
{
	/**
	 * Get button class
	 *
	 * @param	string	$type	Button type (primary, secondary, success, danger)
	 * @return	string	Button class
	 */
	function btn_class($type = 'primary')
	{
		return 'btn btn-' . $type;
	}
}

if (!function_exists('badge_class'))
{
	/**
	 * Get badge class
	 *
	 * @param	string	$type	Badge type (success, danger, warning, info)
	 * @return	string	Badge class
	 */
	function badge_class($type = 'secondary')
	{
		return 'badge badge-' . $type;
	}
}

