<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'my_controller/my_method';
|
| This route lets you set a 404 override controller. This controller
| will be loaded if the URI contains no data or if the requested
| controller/method is not found.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. Instead of
| underscores, dashes will be used.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
|
*/

// Unified Auth Routes (must be before Vendor routes)
$route['auth/login'] = 'auth/login';
$route['auth/logout'] = 'auth/logout';

// ERP Admin Routes
$route['erp-admin'] = 'Erp_admin/dashboard';
$route['erp-admin/auth/login'] = 'auth/login'; // Redirect to unified login
$route['erp-admin/auth/logout'] = 'auth/logout'; // Redirect to unified logout
$route['erp-admin/dashboard'] = 'Erp_admin/dashboard';
$route['erp-admin/vendors'] = 'Erp_admin/vendors';
$route['erp-admin/vendors/add'] = 'Erp_admin/vendors/add';
$route['erp-admin/vendors/edit/(:num)'] = 'Erp_admin/vendors/edit/$1';
$route['erp-admin/vendors/delete/(:num)'] = 'Erp_admin/vendors/delete/$1';
$route['erp-admin/vendors/features/(:num)'] = 'Erp_admin/vendors/features/$1';
$route['erp-admin/vendors/get_features/(:num)'] = 'Erp_admin/vendors/get_features/$1';
$route['erp-admin/vendors/update_features/(:num)'] = 'Erp_admin/vendors/update_features/$1';
$route['erp-admin/vendors/debug_database/(:num)'] = 'Erp_admin/vendors/debug_database/$1';
$route['erp-admin/vendors/create_database/(:num)'] = 'Erp_admin/vendors/create_database/$1';
$route['erp-admin/vendors/fix_foreign_keys/(:num)'] = 'Erp_admin/vendors/fix_foreign_keys/$1';
$route['erp-admin/vendors/sync_features/(:num)'] = 'Erp_admin/vendors/sync_features/$1';
$route['erp-admin/features'] = 'Erp_admin/features';
$route['erp-admin/features/add'] = 'Erp_admin/features/add';
$route['erp-admin/features/edit/(:num)'] = 'Erp_admin/features/edit/$1';
$route['erp-admin/features/delete/(:num)'] = 'Erp_admin/features/delete/$1';
$route['erp-admin/features/check_slug/(:any)/(:num)'] = 'Erp_admin/features/check_slug/$1/$2';
$route['erp-admin/features/check_slug/(:any)'] = 'Erp_admin/features/check_slug/$1';
$route['erp-admin/users'] = 'Erp_admin/users';

// Client Admin Routes
$route['client-admin'] = 'Client_admin/dashboard';
$route['client-admin/auth/login'] = 'Client_admin/auth/login';
$route['client-admin/auth/logout'] = 'Client_admin/auth/logout';
$route['client-admin/dashboard'] = 'Client_admin/dashboard';

// School Admin Routes
$route['school-admin'] = 'School_admin/dashboard';
$route['school-admin/dashboard'] = 'School_admin/dashboard';

// API Routes
$route['api'] = 'api';
$route['api/auth'] = 'api/auth';

// Vendor Routes (Dynamic - must be before default)
// These routes will catch Vendor domain URLs
// Format: /Vendor-domain/controller/method
// Note: These routes will only work if the first segment is a valid Vendor domain
// The Vendor controllers will validate the domain exists
$route['([a-zA-Z0-9_\-]+)/login'] = 'auth/login'; // Unified login - will detect Vendor from domain
$route['([a-zA-Z0-9_\-]+)/logout'] = 'auth/logout'; // Unified logout
$route['([a-zA-Z0-9_\-]+)/dashboard'] = 'Vendor/dashboard/index';
$route['([a-zA-Z0-9_\-]+)/dashboard/(:any)'] = 'Vendor/dashboard/$1';
$route['([a-zA-Z0-9_\-]+)/schools'] = 'Vendor/schools/index';
$route['([a-zA-Z0-9_\-]+)/schools/add'] = 'Vendor/schools/add';
$route['([a-zA-Z0-9_\-]+)/schools/edit/(:num)'] = 'Vendor/schools/edit/$2';
$route['([a-zA-Z0-9_\-]+)/schools/delete/(:num)'] = 'Vendor/schools/delete/$2';
$route['([a-zA-Z0-9_\-]+)/schools/delete_image/(:num)'] = 'Vendor/schools/delete_image/$2';
$route['([a-zA-Z0-9_\-]+)/schools/get_cities'] = 'Vendor/schools/get_cities';
$route['([a-zA-Z0-9_\-]+)/schools/add_board'] = 'Vendor/schools/add_board';
$route['([a-zA-Z0-9_\-]+)/schools/get_boards'] = 'Vendor/schools/get_boards';
	$route['([a-zA-Z0-9_\-]+)/schools/boards'] = 'Vendor/schools/boards';
	$route['([a-zA-Z0-9_\-]+)/schools/update_board'] = 'Vendor/schools/update_board';
	$route['([a-zA-Z0-9_\-]+)/schools/delete_board'] = 'Vendor/schools/delete_board';
	$route['([a-zA-Z0-9_\-]+)/schools/toggle_payment_block'] = 'Vendor/schools/toggle_payment_block';
	$route['([a-zA-Z0-9_\-]+)/schools/toggle_national_block'] = 'Vendor/schools/toggle_national_block';
	$route['([a-zA-Z0-9_\-]+)/schools/toggle_status'] = 'Vendor/schools/toggle_status';
	$route['([a-zA-Z0-9_\-]+)/branches'] = 'Vendor/branches/index';
	$route['([a-zA-Z0-9_\-]+)/branches/add'] = 'Vendor/branches/add';
	$route['([a-zA-Z0-9_\-]+)/branches/edit/(:num)'] = 'Vendor/branches/edit/$2';
	$route['([a-zA-Z0-9_\-]+)/branches/delete/(:num)'] = 'Vendor/branches/delete/$2';
	$route['([a-zA-Z0-9_\-]+)/branches/get_cities'] = 'Vendor/branches/get_cities';
	
	// Orders routes
	$route['([a-zA-Z0-9_\-]+)/orders'] = 'Vendor/orders/index';
	$route['([a-zA-Z0-9_\-]+)/orders/get_order_details/(:num)'] = 'Vendor/orders/get_order_details/$2';

	// Uniforms routes under products
	$route['([a-zA-Z0-9_\-]+)/products/uniforms'] = 'Vendor/uniforms/index';
	$route['([a-zA-Z0-9_\-]+)/products/uniforms/add'] = 'Vendor/uniforms/add';
	$route['([a-zA-Z0-9_\-]+)/products/uniforms/edit/(:num)'] = 'Vendor/uniforms/edit/$2';
	$route['([a-zA-Z0-9_\-]+)/products/uniforms/delete/(:num)'] = 'Vendor/uniforms/delete/$2';
	$route['([a-zA-Z0-9_\-]+)/products/uniforms/get_branches'] = 'Vendor/uniforms/get_branches';
	$route['([a-zA-Z0-9_\-]+)/products/uniforms/get_boards'] = 'Vendor/uniforms/get_boards';
	$route['([a-zA-Z0-9_\-]+)/products/uniforms/add_uniform_type'] = 'Vendor/uniforms/add_uniform_type';
	$route['([a-zA-Z0-9_\-]+)/products/uniforms/add_material'] = 'Vendor/uniforms/add_material';
	$route['([a-zA-Z0-9_\-]+)/products/uniforms/add_size_chart'] = 'Vendor/uniforms/add_size_chart';
	$route['([a-zA-Z0-9_\-]+)/products/uniforms/get_sizes'] = 'Vendor/uniforms/get_sizes';
	$route['([a-zA-Z0-9_\-]+)/products/uniforms/delete_image/(:num)'] = 'Vendor/uniforms/delete_image/$2';
	$route['([a-zA-Z0-9_\-]+)/products/uniforms/toggle_status/(:num)'] = 'Vendor/uniforms/toggle_status/$2';
	$route['([a-zA-Z0-9_\-]+)/products/uniforms/toggle_status'] = 'Vendor/uniforms/toggle_status';

	
	// Stationery routes under products
	$route['([a-zA-Z0-9_\-]+)/products/stationery'] = 'Vendor/products/stationery_index';
	$route['([a-zA-Z0-9_\-]+)/products/stationery/add'] = 'Vendor/products/stationery_add';
	$route['([a-zA-Z0-9_\-]+)/products/stationery/edit/(:num)'] = 'Vendor/products/stationery_edit/$2';
	$route['([a-zA-Z0-9_\-]+)/products/stationery/delete/(:num)'] = 'Vendor/products/stationery_delete/$2';
	$route['([a-zA-Z0-9_\-]+)/products/stationery/add_category'] = 'Vendor/products/stationery_add_category';
	$route['([a-zA-Z0-9_\-]+)/products/stationery/add_brand'] = 'Vendor/products/stationery_add_brand';
	$route['([a-zA-Z0-9_\-]+)/products/stationery/add_colour'] = 'Vendor/products/stationery_add_colour';
	$route['([a-zA-Z0-9_\-]+)/products/stationery/delete_image/(:num)'] = 'Vendor/products/stationery_delete_image/$2';
	
	// Textbook routes under products
	$route['([a-zA-Z0-9_\-]+)/products/textbook'] = 'Vendor/products/textbook_index';
	$route['([a-zA-Z0-9_\-]+)/products/textbook/add'] = 'Vendor/products/textbook_add';
	$route['([a-zA-Z0-9_\-]+)/products/textbook/edit/(:num)'] = 'Vendor/products/textbook_edit/$2';
	$route['([a-zA-Z0-9_\-]+)/products/textbook/delete/(:num)'] = 'Vendor/products/textbook_delete/$2';
	$route['([a-zA-Z0-9_\-]+)/products/textbook/add_type'] = 'Vendor/products/textbook_add_type';
	$route['([a-zA-Z0-9_\-]+)/products/textbook/add_publisher'] = 'Vendor/products/textbook_add_publisher';
	$route['([a-zA-Z0-9_\-]+)/products/textbook/add_grade'] = 'Vendor/products/textbook_add_grade';
	$route['([a-zA-Z0-9_\-]+)/products/textbook/add_age'] = 'Vendor/products/textbook_add_age';
	$route['([a-zA-Z0-9_\-]+)/products/textbook/add_subject'] = 'Vendor/products/textbook_add_subject';
	$route['([a-zA-Z0-9_\-]+)/products/textbook/delete_image/(:num)'] = 'Vendor/products/textbook_delete_image/$2';
	
	// Notebook routes under products
	$route['([a-zA-Z0-9_\-]+)/products/notebooks'] = 'Vendor/products/notebook_index';
	$route['([a-zA-Z0-9_\-]+)/products/notebooks/add'] = 'Vendor/products/notebook_add';
	$route['([a-zA-Z0-9_\-]+)/products/notebooks/edit/(:num)'] = 'Vendor/products/notebook_edit/$2';
	$route['([a-zA-Z0-9_\-]+)/products/notebooks/delete/(:num)'] = 'Vendor/products/notebook_delete/$2';
	$route['([a-zA-Z0-9_\-]+)/products/notebooks/add_type'] = 'Vendor/products/notebook_add_type';
	$route['([a-zA-Z0-9_\-]+)/products/notebooks/add_brand'] = 'Vendor/products/notebook_add_brand';
	$route['([a-zA-Z0-9_\-]+)/products/notebooks/delete_image/(:num)'] = 'Vendor/products/notebook_delete_image/$2';
	
	// Bookset routes under products/books
	$route['([a-zA-Z0-9_\-]+)/products/books/bookset'] = 'Vendor/products/bookset_index';
	$route['([a-zA-Z0-9_\-]+)/products/bookset'] = 'Vendor/products/bookset_index';
	$route['([a-zA-Z0-9_\-]+)/products/bookset/edit/(:num)'] = 'Vendor/products/bookset_edit/$2';
	$route['([a-zA-Z0-9_\-]+)/products/bookset/delete/(:num)'] = 'Vendor/products/bookset_delete/$2';
	$route['([a-zA-Z0-9_\-]+)/products/bookset/package/add_with_products'] = 'Vendor/products/bookset_package_add_with_products';
	$route['([a-zA-Z0-9_\-]+)/products/bookset/package/add_without_products'] = 'Vendor/products/bookset_package_add_without_products';
	$route['([a-zA-Z0-9_\-]+)/products/bookset/package/delete/(:num)'] = 'Vendor/products/bookset_package_delete/$2';
	$route['([a-zA-Z0-9_\-]+)/products/bookset/package/add_category'] = 'Vendor/products/bookset_package_add_category';
	$route['([a-zA-Z0-9_\-]+)/products/bookset/package/get_products'] = 'Vendor/products/bookset_package_get_products';
	$route['([a-zA-Z0-9_\-]+)/products/bookset/package/get_products_by_type'] = 'Vendor/products/bookset_package_get_products_by_type';
	$route['([a-zA-Z0-9_\-]+)/products/bookset/package/get_boards'] = 'Vendor/products/bookset_package_get_boards';

	// Vendor Features routes
	$route['([a-zA-Z0-9_\-]+)/features'] = 'Vendor/features/index';
	$route['([a-zA-Z0-9_\-]+)/features/upload_image'] = 'Vendor/features/upload_image';
	$route['([a-zA-Z0-9_\-]+)/features/delete_image'] = 'Vendor/features/delete_image';
	
	// Site Settings routes
	$route['([a-zA-Z0-9_\-]+)/site-settings'] = 'Vendor/sitesettings/index';
	$route['([a-zA-Z0-9_\-]+)/site-settings/save'] = 'Vendor/sitesettings/save';
	$route['([a-zA-Z0-9_\-]+)/site-settings/delete-logo'] = 'Vendor/sitesettings/delete_logo';
	$route['([a-zA-Z0-9_\-]+)/site-settings/delete-favicon'] = 'Vendor/sitesettings/delete_favicon';
	$route['([a-zA-Z0-9_\-]+)/site-settings/delete-banner'] = 'Vendor/sitesettings/delete_banner';
	$route['([a-zA-Z0-9_\-]+)/site-settings/delete-banner-ajax/([0-9]+)'] = 'Vendor/sitesettings/delete_banner_ajax/$2';
	$route['([a-zA-Z0-9_\-]+)/site-settings/add-banner-ajax'] = 'Vendor/sitesettings/add_banner_ajax';
	$route['([a-zA-Z0-9_\-]+)/site-settings/update-banner-ajax'] = 'Vendor/sitesettings/update_banner_ajax';
	$route['([a-zA-Z0-9_\-]+)/site-settings/get-banner-data/([0-9]+)'] = 'Vendor/sitesettings/get_banner_data/$2';
	$route['([a-zA-Z0-9_\-]+)/site-settings/preview'] = 'Vendor/sitesettings/preview';

	// Individual products routes
	$route['([a-zA-Z0-9_\-]+)/products/individual-products'] = 'Vendor/products/individual_products';
	$route['([a-zA-Z0-9_\-]+)/products/individual-products/add'] = 'Vendor/products/individual_products_add';
	$route['([a-zA-Z0-9_\-]+)/products/individual-products/edit/(:num)'] = 'Vendor/products/individual_products_edit/$2';
	$route['([a-zA-Z0-9_\-]+)/products/individual-products/update/(:num)'] = 'Vendor/products/individual_products_update/$2';
	$route['([a-zA-Z0-9_\-]+)/products/individual-products/save'] = 'Vendor/products/individual_products_save';
	$route['([a-zA-Z0-9_\-]+)/products/individual-products/add_category'] = 'Vendor/products/individual_products_add_category';
	$route['([a-zA-Z0-9_\-]+)/products/individual-products/get_subcategories'] = 'Vendor/products/individual_products_get_subcategories';
	$route['([a-zA-Z0-9_\-]+)/products/individual-products/add_color'] = 'Vendor/products/individual_products_add_color';
	$route['([a-zA-Z0-9_\-]+)/products/individual-products/get_sizes'] = 'Vendor/products/individual_products_get_sizes';
	$route['([a-zA-Z0-9_\-]+)/products/individual-products/delete/(:num)'] = 'Vendor/products/individual_products_delete/$2';
	
	// Variation management routes
	$route['([a-zA-Z0-9_\-]+)/products/variations'] = 'Vendor/products/variations';
	$route['([a-zA-Z0-9_\-]+)/products/variations/add_type'] = 'Vendor/products/add_variation_type';
	$route['([a-zA-Z0-9_\-]+)/products/variations/update_type/(:num)'] = 'Vendor/products/update_variation_type/$2';
	$route['([a-zA-Z0-9_\-]+)/products/variations/delete_type/(:num)'] = 'Vendor/products/delete_variation_type/$2';
	$route['([a-zA-Z0-9_\-]+)/products/variations/get_values'] = 'Vendor/products/get_variation_values';
	$route['([a-zA-Z0-9_\-]+)/products/variations/add_value'] = 'Vendor/products/add_variation_value';
	$route['([a-zA-Z0-9_\-]+)/products/variations/delete_value/(:num)'] = 'Vendor/products/delete_variation_value/$2';
	$route['([a-zA-Z0-9_\-]+)/products/variations/generate_combinations'] = 'Vendor/products/generate_combinations';
	$route['([a-zA-Z0-9_\-]+)/products/individual-products/(:any)'] = 'Vendor/products/individual_products/$2';

	$route['([a-zA-Z0-9_\-]+)/customers'] = 'Vendor/customers/list';
	$route['([a-zA-Z0-9_\-]+)/customers/get_customer_details/(:num)'] = 'Vendor/customers/get_customer_details/$2';
	
	$route['([a-zA-Z0-9_\-]+)/products/(:any)/(:any)'] = 'Vendor/products/$2/$3'; // Products feature method routes (e.g., /products/books/add)
	$route['([a-zA-Z0-9_\-]+)/products/(:any)'] = 'Vendor/products/index/$2'; // Products feature routes (e.g., /products/books)
	$route['([a-zA-Z0-9_\-]+)/(:any)/(:any)'] = 'Vendor/$2/$3'; // Vendor controller/method routes (domain/controller/method -> Vendor/controller/method)
	$route['([a-zA-Z0-9_\-]+)/(:any)'] = 'Vendor/$2'; // Vendor controller routes (domain/controller -> Vendor/controller)
	$route['([a-zA-Z0-9_\-]+)'] = 'Vendor/dashboard'; // Default Vendor route redirects to dashboard

// Default route
$route['default_controller'] = 'Welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

