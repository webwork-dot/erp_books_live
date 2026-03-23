<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Tenant Configuration
|--------------------------------------------------------------------------
|
| Configuration for multi-tenant functionality
|
*/

// Tenant resolution methods
$config['tenant_resolution_method'] = 'domain'; // 'domain', 'subdomain', 'path', 'header'

// Domain-based resolution
$config['domain_mapping'] = array(
	// Example: 'client1.com' => 'client1_db',
	// Will be populated from database
);

// Subdomain-based resolution
$config['subdomain_separator'] = '.';
$config['subdomain_remove'] = array('www');

// Path-based resolution (e.g., /tenant1/controller/method)
$config['path_segment'] = 1; // Which URI segment contains the tenant identifier

// Header-based resolution
$config['tenant_header'] = 'X-Tenant-ID';

// Default tenant (if resolution fails)
$config['default_tenant'] = NULL;

// Routes that should skip tenant resolution
$config['skip_tenant_routes'] = array(
	'erp-admin',
	'erp_admin',
	'api',
	'frontend',
	'Frontend',
	'vendor', // Skip vendor routes (they use their own routing)
	'Vendor',
);

// Database connection settings for tenant databases
$config['tenant_db_host'] = 'localhost';
$config['tenant_db_user'] = 'root';
$config['tenant_db_pass'] = '';
$config['tenant_db_prefix'] = 'erp_client_'; // Prefix for client database names

// Auto-create database on client creation
$config['auto_create_database'] = TRUE;

// Database template file path (SQL file to use as template for new clients)
// Using erp_master.sql to give vendors complete database structure
$config['database_template_path'] = APPPATH . '../erp_master.sql';

// Feature tables SQL file path
$config['feature_tables_path'] = APPPATH . '../database/feature_tables.sql';

// Feature enforcement SQL file path
$config['feature_enforcement_path'] = APPPATH . '../database/feature_enforcement.sql';

