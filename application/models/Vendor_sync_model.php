<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Vendor Sync Model
 *
 * Handles syncing vendor data from master database to vendor databases
 *
 * @package		ERP
 * @subpackage	Models
 * @category	Models
 * @author		ERP Team
 */
class Vendor_sync_model extends CI_Model
{
	/**
	 * Constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
		// Use master database for operations
		$this->load->database('default', TRUE);
	}
	
	/**
	 * Sync vendor data from master to vendor database
	 *
	 * @param	int	$vendor_id	Vendor ID
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function syncVendorData($vendor_id)
	{
		// Get vendor information from master database
		$this->load->model('Erp_client_model');
		$vendor = $this->Erp_client_model->getClientById($vendor_id);
		
		if (!$vendor || empty($vendor['database_name']))
		{
			log_message('error', 'Vendor not found or database name missing for vendor ID: ' . $vendor_id);
			return FALSE;
		}
		
		log_message('info', 'Starting vendor data sync for vendor ID: ' . $vendor_id . ' to database: ' . $vendor['database_name']);
		
		// Connect to vendor database
		$vendor_db = $this->connectToVendorDatabase($vendor['database_name']);
		if (!$vendor_db)
		{
			log_message('error', 'Failed to connect to vendor database: ' . $vendor['database_name']);
			return FALSE;
		}
		
		// Ensure erp_clients table exists in vendor database
		$table_check = $vendor_db->query("SHOW TABLES LIKE 'erp_clients'");
		if (!$table_check || $table_check->num_rows == 0)
		{
			log_message('error', 'erp_clients table does not exist in vendor database: ' . $vendor['database_name']);
			$vendor_db->close();
			return FALSE;
		}
		
		// Prepare vendor data for sync (only sync relevant fields)
		$vendor_data = array(
			'id' => $vendor['id'],
			'name' => isset($vendor['name']) ? $vendor['name'] : '',
			'domain' => isset($vendor['domain']) ? $vendor['domain'] : '',
			'username' => isset($vendor['username']) ? $vendor['username'] : '',
			'status' => isset($vendor['status']) ? $vendor['status'] : 'active',
			'logo' => isset($vendor['logo']) ? $vendor['logo'] : NULL,
			'favicon' => isset($vendor['favicon']) ? $vendor['favicon'] : NULL,
			'site_title' => isset($vendor['site_title']) ? $vendor['site_title'] : NULL,
			'meta_description' => isset($vendor['meta_description']) ? $vendor['meta_description'] : NULL,
			'meta_keywords' => isset($vendor['meta_keywords']) ? $vendor['meta_keywords'] : NULL,
			'sidebar_color' => isset($vendor['sidebar_color']) ? $vendor['sidebar_color'] : NULL,
			'payment_gateway' => isset($vendor['payment_gateway']) ? $vendor['payment_gateway'] : NULL,
			'razorpay_key_id' => isset($vendor['razorpay_key_id']) ? $vendor['razorpay_key_id'] : NULL,
			'razorpay_key_secret' => isset($vendor['razorpay_key_secret']) ? $vendor['razorpay_key_secret'] : NULL,
			'ccavenue_merchant_id' => isset($vendor['ccavenue_merchant_id']) ? $vendor['ccavenue_merchant_id'] : NULL,
			'ccavenue_access_code' => isset($vendor['ccavenue_access_code']) ? $vendor['ccavenue_access_code'] : NULL,
			'ccavenue_working_key' => isset($vendor['ccavenue_working_key']) ? $vendor['ccavenue_working_key'] : NULL,
			'zepto_mail_api_key' => isset($vendor['zepto_mail_api_key']) ? $vendor['zepto_mail_api_key'] : NULL,
			'zepto_mail_from_email' => isset($vendor['zepto_mail_from_email']) ? $vendor['zepto_mail_from_email'] : NULL,
			'zepto_mail_from_name' => isset($vendor['zepto_mail_from_name']) ? $vendor['zepto_mail_from_name'] : NULL,
			'firebase_api_key' => isset($vendor['firebase_api_key']) ? $vendor['firebase_api_key'] : NULL,
			'firebase_auth_domain' => isset($vendor['firebase_auth_domain']) ? $vendor['firebase_auth_domain'] : NULL,
			'firebase_project_id' => isset($vendor['firebase_project_id']) ? $vendor['firebase_project_id'] : NULL,
			'firebase_storage_bucket' => isset($vendor['firebase_storage_bucket']) ? $vendor['firebase_storage_bucket'] : NULL,
			'firebase_messaging_sender_id' => isset($vendor['firebase_messaging_sender_id']) ? $vendor['firebase_messaging_sender_id'] : NULL,
			'firebase_app_id' => isset($vendor['firebase_app_id']) ? $vendor['firebase_app_id'] : NULL,
			'updated_at' => date('Y-m-d H:i:s')
		);
		
		// Ensure columns exist in erp_clients table
		$this->ensureErpClientsColumns($vendor_db);
		
		// Sync vendor data to vendor database
		$success = $this->syncVendorToDatabase($vendor_db, $vendor_data);
		
		$vendor_db->close();
		
		if ($success)
		{
			log_message('info', 'Successfully synced vendor data for vendor ID: ' . $vendor_id);
		}
		else
		{
			log_message('error', 'Failed to sync vendor data for vendor ID: ' . $vendor_id);
		}
		
		return $success;
	}
	
	/**
	 * Connect to vendor database
	 *
	 * @param	string	$database_name	Database name
	 * @return	mysqli|FALSE	Database connection or FALSE on failure
	 */
	private function connectToVendorDatabase($database_name)
	{
		$hostname = $this->db->hostname;
		$username = $this->db->username;
		$password = $this->db->password;
		
		$connection = new mysqli($hostname, $username, $password, $database_name);
		
		if ($connection->connect_error)
		{
			log_message('error', 'Failed to connect to vendor database: ' . $connection->connect_error);
			return FALSE;
		}
		
		return $connection;
	}
	
	/**
	 * Ensure erp_clients table has required columns
	 *
	 * @param	mysqli	$vendor_db	Vendor database connection
	 * @return	void
	 */
	private function ensureErpClientsColumns($vendor_db)
	{
		// List of columns that should exist in erp_clients table
		$columns = array(
			'logo' => "ALTER TABLE erp_clients ADD COLUMN logo VARCHAR(255) NULL AFTER status",
			'sidebar_color' => "ALTER TABLE erp_clients ADD COLUMN sidebar_color VARCHAR(50) NULL AFTER logo",
			'payment_gateway' => "ALTER TABLE erp_clients ADD COLUMN payment_gateway VARCHAR(50) NULL AFTER sidebar_color",
			'razorpay_key_id' => "ALTER TABLE erp_clients ADD COLUMN razorpay_key_id VARCHAR(255) NULL AFTER payment_gateway",
			'razorpay_key_secret' => "ALTER TABLE erp_clients ADD COLUMN razorpay_key_secret VARCHAR(255) NULL AFTER razorpay_key_id",
			'ccavenue_merchant_id' => "ALTER TABLE erp_clients ADD COLUMN ccavenue_merchant_id VARCHAR(255) NULL AFTER razorpay_key_secret",
			'ccavenue_access_code' => "ALTER TABLE erp_clients ADD COLUMN ccavenue_access_code VARCHAR(255) NULL AFTER ccavenue_merchant_id",
			'ccavenue_working_key' => "ALTER TABLE erp_clients ADD COLUMN ccavenue_working_key VARCHAR(255) NULL AFTER ccavenue_access_code",
			'zepto_mail_api_key' => "ALTER TABLE erp_clients ADD COLUMN zepto_mail_api_key TEXT NULL AFTER ccavenue_working_key",
			'zepto_mail_from_email' => "ALTER TABLE erp_clients ADD COLUMN zepto_mail_from_email VARCHAR(255) NULL AFTER zepto_mail_api_key",
			'zepto_mail_from_name' => "ALTER TABLE erp_clients ADD COLUMN zepto_mail_from_name VARCHAR(255) NULL AFTER zepto_mail_from_email",
			'firebase_api_key' => "ALTER TABLE erp_clients ADD COLUMN firebase_api_key TEXT NULL AFTER zepto_mail_from_name",
			'firebase_auth_domain' => "ALTER TABLE erp_clients ADD COLUMN firebase_auth_domain VARCHAR(255) NULL AFTER firebase_api_key",
			'firebase_project_id' => "ALTER TABLE erp_clients ADD COLUMN firebase_project_id VARCHAR(255) NULL AFTER firebase_auth_domain",
			'firebase_storage_bucket' => "ALTER TABLE erp_clients ADD COLUMN firebase_storage_bucket VARCHAR(255) NULL AFTER firebase_project_id",
			'firebase_messaging_sender_id' => "ALTER TABLE erp_clients ADD COLUMN firebase_messaging_sender_id VARCHAR(255) NULL AFTER firebase_storage_bucket",
			'firebase_app_id' => "ALTER TABLE erp_clients ADD COLUMN firebase_app_id VARCHAR(255) NULL AFTER firebase_messaging_sender_id"
		);
		
		foreach ($columns as $column_name => $sql)
		{
			$check = $vendor_db->query("SHOW COLUMNS FROM erp_clients LIKE '$column_name'");
			if ($check->num_rows == 0)
			{
				$vendor_db->query($sql);
				log_message('info', "Added column '$column_name' to erp_clients table in vendor database");
			}
		}
	}
	
	/**
	 * Sync vendor data to vendor database
	 *
	 * @param	mysqli	$vendor_db	Vendor database connection
	 * @param	array	$vendor_data	Vendor data
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	private function syncVendorToDatabase($vendor_db, $vendor_data)
	{
		$vendor_id = isset($vendor_data['id']) ? (int)$vendor_data['id'] : 0;
		
		if (empty($vendor_id))
		{
			log_message('error', 'Invalid vendor data for sync. Vendor ID is missing.');
			return FALSE;
		}
		
		// Build SQL for INSERT ... ON DUPLICATE KEY UPDATE
		$sql = "INSERT INTO erp_clients (
			id, name, domain, username, status, logo, favicon, site_title, meta_description, meta_keywords, sidebar_color, 
			payment_gateway, razorpay_key_id, razorpay_key_secret,
			ccavenue_merchant_id, ccavenue_access_code, ccavenue_working_key,
			zepto_mail_api_key, zepto_mail_from_email, zepto_mail_from_name,
			firebase_api_key, firebase_auth_domain, firebase_project_id,
			firebase_storage_bucket, firebase_messaging_sender_id, firebase_app_id,
			updated_at
		) VALUES (
			?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
			?, ?, ?,
			?, ?, ?,
			?, ?, ?,
			?, ?, ?,
			?, ?, ?,
			?
		) ON DUPLICATE KEY UPDATE 
			name = VALUES(name),
			domain = VALUES(domain),
			username = VALUES(username),
			status = VALUES(status),
			logo = VALUES(logo),
			favicon = VALUES(favicon),
			site_title = VALUES(site_title),
			meta_description = VALUES(meta_description),
			meta_keywords = VALUES(meta_keywords),
			sidebar_color = VALUES(sidebar_color),
			payment_gateway = VALUES(payment_gateway),
			razorpay_key_id = VALUES(razorpay_key_id),
			razorpay_key_secret = VALUES(razorpay_key_secret),
			ccavenue_merchant_id = VALUES(ccavenue_merchant_id),
			ccavenue_access_code = VALUES(ccavenue_access_code),
			ccavenue_working_key = VALUES(ccavenue_working_key),
			zepto_mail_api_key = VALUES(zepto_mail_api_key),
			zepto_mail_from_email = VALUES(zepto_mail_from_email),
			zepto_mail_from_name = VALUES(zepto_mail_from_name),
			firebase_api_key = VALUES(firebase_api_key),
			firebase_auth_domain = VALUES(firebase_auth_domain),
			firebase_project_id = VALUES(firebase_project_id),
			firebase_storage_bucket = VALUES(firebase_storage_bucket),
			firebase_messaging_sender_id = VALUES(firebase_messaging_sender_id),
			firebase_app_id = VALUES(firebase_app_id),
			updated_at = VALUES(updated_at)";
		
		$stmt = $vendor_db->prepare($sql);
		if (!$stmt)
		{
			log_message('error', 'Failed to prepare statement for vendor sync: ' . $vendor_db->error . '. SQL: ' . $sql);
			return FALSE;
		}
		
		// Bind parameters
		// Type string: 1 integer (id) + 26 strings = 27 parameters total
		// Parameters: id, name, domain, username, status, logo, favicon, site_title, meta_description, meta_keywords, sidebar_color, payment_gateway, razorpay_key_id, razorpay_key_secret, ccavenue_merchant_id, ccavenue_access_code, ccavenue_working_key, zepto_mail_api_key, zepto_mail_from_email, zepto_mail_from_name, firebase_api_key, firebase_auth_domain, firebase_project_id, firebase_storage_bucket, firebase_messaging_sender_id, firebase_app_id, updated_at
		$stmt->bind_param('iisssssssssssssssssssssssss',
			$vendor_data['id'],
			$vendor_data['name'],
			$vendor_data['domain'],
			$vendor_data['username'],
			$vendor_data['status'],
			$vendor_data['logo'],
			$vendor_data['favicon'],
			$vendor_data['site_title'],
			$vendor_data['meta_description'],
			$vendor_data['meta_keywords'],
			$vendor_data['sidebar_color'],
			$vendor_data['payment_gateway'],
			$vendor_data['razorpay_key_id'],
			$vendor_data['razorpay_key_secret'],
			$vendor_data['ccavenue_merchant_id'],
			$vendor_data['ccavenue_access_code'],
			$vendor_data['ccavenue_working_key'],
			$vendor_data['zepto_mail_api_key'],
			$vendor_data['zepto_mail_from_email'],
			$vendor_data['zepto_mail_from_name'],
			$vendor_data['firebase_api_key'],
			$vendor_data['firebase_auth_domain'],
			$vendor_data['firebase_project_id'],
			$vendor_data['firebase_storage_bucket'],
			$vendor_data['firebase_messaging_sender_id'],
			$vendor_data['firebase_app_id'],
			$vendor_data['updated_at']
		);
		
		
		$result = $stmt->execute();
		
		if (!$result)
		{
			log_message('error', 'Failed to execute vendor sync statement: ' . $stmt->error . '. Vendor ID: ' . $vendor_id);
			$stmt->close();
			return FALSE;
		}
		
		$stmt->close();
		return TRUE;
	}
}

