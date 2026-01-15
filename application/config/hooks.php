<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/

/*
| -------------------------------------------------------------------------
| Tenant Resolution Hook
| -------------------------------------------------------------------------
| This hook resolves the tenant (client) based on the incoming request
| and switches the database connection accordingly.
|
*/

$hook['pre_system'] = array(
	'class'    => '',
	'function' => '',
	'filename' => '',
	'filepath' => ''
);

// Vendor domain routing hook - runs before controller
$hook['pre_controller'] = array(
	'class'    => '',
	'function' => 'route_vendor_domain',
	'filename' => 'vendor_routing_hook.php',
	'filepath' => 'hooks'
);

// Controller case normalization is handled by core/MY_Router.php
// Hook disabled to avoid conflicts
// $hook['pre_controller'] = array(
// 	'class'    => '',
// 	'function' => 'normalize_controller_case',
// 	'filename' => 'controller_case_hook.php',
// 	'filepath' => 'hooks'
// );

$hook['post_controller_constructor'] = array(
	'class'    => '',
	'function' => 'resolve_tenant',
	'filename' => 'tenant_hook.php',
	'filepath' => 'hooks'
);

