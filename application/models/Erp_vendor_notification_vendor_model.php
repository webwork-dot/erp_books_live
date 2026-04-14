<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ERP Vendor Notification Vendor Model (TENANT DB)
 *
 * Runtime reads from vendor database.
 * Admin can sync master -> vendor database using syncFromMaster().
 */
class Erp_vendor_notification_vendor_model extends CI_Model
{
	/** @var array<int, CI_DB_query_builder> */
	private $client_db = [];
	/** @var string */
	private $last_error = '';

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Erp_client_model');
	}

	private function getVendorDb($vendor_id)
	{
		$vendor_id = (int)$vendor_id;
		if ($vendor_id <= 0) {
			$this->last_error = 'Invalid vendor_id';
			return NULL;
		}

		if (isset($this->client_db[$vendor_id])) {
			return $this->client_db[$vendor_id];
		}

		$vendor = $this->Erp_client_model->getClientById($vendor_id);
		if (!$vendor || empty($vendor['database_name'])) {
			$this->last_error = 'Vendor DB not found in erp_clients';
			log_message('error', 'Erp_vendor_notification_vendor_model: vendor DB not found for vendor_id=' . $vendor_id);
			return NULL;
		}

		$db_name = (string)$vendor['database_name'];
		if (!preg_match('/^[a-zA-Z0-9_]+$/', $db_name)) {
			$this->last_error = 'Invalid database_name: ' . $db_name;
			log_message('error', 'Erp_vendor_notification_vendor_model: invalid database_name=' . $db_name . ' vendor_id=' . $vendor_id);
			return NULL;
		}

		// Build vendor DB config from active DB group credentials
		$active_group = null;
		$db = [];
		$db_file = APPPATH . 'config/database.php';
		if (!file_exists($db_file)) {
			$this->last_error = 'database.php not found';
			log_message('error', 'Erp_vendor_notification_vendor_model: database.php not found');
			return NULL;
		}
		require $db_file;

		if (empty($active_group) || empty($db[$active_group]) || !is_array($db[$active_group])) {
			$this->last_error = 'Invalid active_group config in database.php';
			log_message('error', 'Erp_vendor_notification_vendor_model: invalid active_group config');
			return NULL;
		}
		$cfg = $db[$active_group];

		$client_db = $this->load->database([
			'dsn'      => '',
			'hostname' => $cfg['hostname'],
			'username' => $cfg['username'],
			'password' => $cfg['password'],
			'database' => $db_name,
			'dbdriver' => 'mysqli',
			'dbprefix' => '',
			'pconnect' => FALSE,
			'db_debug' => (ENVIRONMENT !== 'production'),
			'cache_on' => FALSE,
			'char_set' => 'utf8mb4',
			'dbcollat' => 'utf8mb4_general_ci',
			'swap_pre' => '',
			'encrypt'  => FALSE,
			'compress' => FALSE,
			'stricton' => FALSE,
			'failover' => [],
			'save_queries' => TRUE
		], TRUE);

		if (!$client_db || $client_db->conn_id === FALSE) {
			$this->last_error = 'Failed to connect vendor DB: ' . $db_name;
			log_message('error', 'Erp_vendor_notification_vendor_model: failed to connect vendor DB vendor_id=' . $vendor_id . ' db=' . $db_name);
			return NULL;
		}

		$this->client_db[$vendor_id] = $client_db;
		return $client_db;
	}

	public function getLastError()
	{
		return (string)$this->last_error;
	}

	public function ensureTables($vendor_id)
	{
		$db = $this->getVendorDb($vendor_id);
		if (!$db) return FALSE;

		$required = [
			'erp_vendor_notification_settings',
			'erp_vendor_email_templates',
			'erp_vendor_whatsapp_templates',
			'erp_vendor_sms_templates',
			'erp_notification_events',
		];
		$missing = [];
		foreach ($required as $t) {
			if (!$db->table_exists($t)) $missing[] = $t;
		}
		if (empty($missing)) return TRUE;

		$sql_path = APPPATH . '../database/tenant/create_vendor_notifications_tables.sql';
		if (!file_exists($sql_path)) {
			log_message('error', 'Erp_vendor_notification_vendor_model: tenant SQL not found at ' . $sql_path);
			return FALSE;
		}

		$sql = file_get_contents($sql_path);
		if (!is_string($sql) || trim($sql) === '') {
			$this->last_error = 'Tenant SQL empty';
			log_message('error', 'Erp_vendor_notification_vendor_model: tenant SQL empty');
			return FALSE;
		}

		// Very simple SQL splitter (safe for this file: no procedures/triggers).
		$statements = preg_split('/;\s*[\r\n]+/', $sql);
		if (!is_array($statements)) $statements = [];

		foreach ($statements as $st) {
			$st = trim($st);
			if ($st === '') continue;

			// Remove full-line SQL comments so we don't skip CREATE statements
			// that share a chunk with leading comments.
			$st = preg_replace('/^\s*--.*$/m', '', $st);
			$st = trim((string)$st);
			if ($st === '') continue;
			$ok = $db->query($st . ';');
			if ($ok === FALSE) {
				$err = $db->error();
				$this->last_error = 'Failed to apply tenant notifications schema. SQL error: ' . ($err['message'] ?? 'unknown');
				log_message('error', 'Erp_vendor_notification_vendor_model: schema apply failed vendor_id=' . (int)$vendor_id . ' code=' . ($err['code'] ?? '') . ' message=' . ($err['message'] ?? '') . ' sql=' . substr($st, 0, 200));
				return FALSE;
			}
		}

		// Re-check required tables after apply
		$missing = [];
		foreach ($required as $t) {
			if (!$db->table_exists($t)) $missing[] = $t;
		}
		if (!empty($missing)) {
			$this->last_error = 'Schema applied but still missing tables: ' . implode(', ', $missing);
			return FALSE;
		}

		$this->last_error = '';
		return TRUE;
	}

	// ---------------------------------------------------------------------
	// Runtime reads (vendor DB)
	// ---------------------------------------------------------------------

	public function getSettings($vendor_id)
	{
		$db = $this->getVendorDb($vendor_id);
		if (!$db) return NULL;
		$this->ensureTables($vendor_id);

		$row = $db->where('vendor_id', (int)$vendor_id)->get('erp_vendor_notification_settings')->row_array();
		if (!$row) return NULL;
		return $this->decodeJsonFields($row);
	}

	public function getEmailTemplates($vendor_id)
	{
		$db = $this->getVendorDb($vendor_id);
		if (!$db) return [];
		$this->ensureTables($vendor_id);
		return $db->where('vendor_id', (int)$vendor_id)->order_by('id', 'asc')->get('erp_vendor_email_templates')->result_array();
	}

	public function getWhatsappTemplates($vendor_id)
	{
		$db = $this->getVendorDb($vendor_id);
		if (!$db) return [];
		$this->ensureTables($vendor_id);
		$rows = $db->where('vendor_id', (int)$vendor_id)->order_by('id', 'asc')->get('erp_vendor_whatsapp_templates')->result_array();
		foreach ($rows as &$row) {
			$row['param_map_json'] = $this->decodeJson($row['param_map_json'] ?? NULL);
		}
		unset($row);
		return $rows;
	}

	public function getSmsTemplates($vendor_id)
	{
		$db = $this->getVendorDb($vendor_id);
		if (!$db) return [];
		$this->ensureTables($vendor_id);
		return $db->where('vendor_id', (int)$vendor_id)->order_by('id', 'asc')->get('erp_vendor_sms_templates')->result_array();
	}

	public function getNotificationEvents($vendor_id, $include_inactive = false)
	{
		$db = $this->getVendorDb($vendor_id);
		if (!$db) return [];
		$this->ensureTables($vendor_id);

		$q = $db->order_by('title', 'asc')->from('erp_notification_events');
		if (!$include_inactive) {
			$q->where('is_active', 1);
		}
		return $q->get()->result_array();
	}

	public function getNotificationEventByKey($vendor_id, $event_key)
	{
		$db = $this->getVendorDb($vendor_id);
		if (!$db) return NULL;
		$this->ensureTables($vendor_id);

		$event_key = trim((string)$event_key);
		if ($event_key === '') return NULL;
		return $db->where('event_key', $event_key)->where('is_active', 1)->get('erp_notification_events')->row_array() ?: NULL;
	}

	// ---------------------------------------------------------------------
	// Master -> vendor sync (admin)
	// ---------------------------------------------------------------------

	public function syncFromMaster($vendor_id, $include_inactive_events = true)
	{
		$vendor_id = (int)$vendor_id;
		$db = $this->getVendorDb($vendor_id);
		if (!$db) return FALSE;
		if (!$this->ensureTables($vendor_id)) return FALSE;

		$this->load->model('Erp_vendor_notification_model'); // master

		// Sync events
		$events = $this->Erp_vendor_notification_model->getNotificationEvents($include_inactive_events);
		foreach ((array)$events as $ev) {
			$ek = trim((string)($ev['event_key'] ?? ''));
			$title = trim((string)($ev['title'] ?? ''));
			if ($ek === '' || $title === '') continue;
			$is_active = !empty($ev['is_active']) ? 1 : 0;
			$existing = $db->select('id')->where('event_key', $ek)->get('erp_notification_events')->row_array();
			$payload = ['event_key' => $ek, 'title' => $title, 'is_active' => $is_active];
			if ($existing && !empty($existing['id'])) {
				$ok = $db->where('id', (int)$existing['id'])->update('erp_notification_events', $payload);
			} else {
				$ok = $db->insert('erp_notification_events', $payload);
			}
			if (empty($ok)) {
				$err = $db->error();
				$this->last_error = 'Failed syncing notification events: ' . ($err['message'] ?? 'unknown');
				log_message('error', 'Erp_vendor_notification_vendor_model: event sync failed vendor_id=' . $vendor_id . ' event_key=' . $ek . ' code=' . ($err['code'] ?? '') . ' message=' . ($err['message'] ?? ''));
				return FALSE;
			}
		}

		// Sync settings
		$settings = $this->Erp_vendor_notification_model->getSettings($vendor_id);
		if (is_array($settings)) {
			$settings['vendor_id'] = $vendor_id;
			$exists = $db->select('id')->where('vendor_id', $vendor_id)->get('erp_vendor_notification_settings')->row_array();
			if ($exists) {
				$ok = $db->where('vendor_id', $vendor_id)->update('erp_vendor_notification_settings', $this->encodeJsonFields($settings));
			} else {
				$ok = $db->insert('erp_vendor_notification_settings', $this->encodeJsonFields($settings));
			}
			if (empty($ok)) {
				$err = $db->error();
				$this->last_error = 'Failed syncing notification settings: ' . ($err['message'] ?? 'unknown');
				log_message('error', 'Erp_vendor_notification_vendor_model: settings sync failed vendor_id=' . $vendor_id . ' code=' . ($err['code'] ?? '') . ' message=' . ($err['message'] ?? ''));
				return FALSE;
			}
		}

		// Sync templates (replace per vendor_id for predictability)
		if (!$db->table_exists('erp_vendor_email_templates')) {
			$this->last_error = 'Missing table in vendor DB: erp_vendor_email_templates';
			log_message('error', 'Erp_vendor_notification_vendor_model: vendor table missing vendor_id=' . $vendor_id . ' table=erp_vendor_email_templates');
			return FALSE;
		}
		$ok = $db->where('vendor_id', $vendor_id)->delete('erp_vendor_email_templates');
		if ($ok === FALSE) {
			$err = $db->error();
			$this->last_error = 'Failed clearing vendor email templates: ' . ($err['message'] ?? 'unknown');
			log_message('error', 'Erp_vendor_notification_vendor_model: clear email templates failed vendor_id=' . $vendor_id . ' code=' . ($err['code'] ?? '') . ' message=' . ($err['message'] ?? ''));
			return FALSE;
		}
		foreach ((array)$this->Erp_vendor_notification_model->getEmailTemplates($vendor_id) as $t) {
			unset($t['id']);
			$ok = $db->insert('erp_vendor_email_templates', $t);
			if (empty($ok)) {
				$err = $db->error();
				$this->last_error = 'Failed syncing vendor email templates: ' . ($err['message'] ?? 'unknown');
				log_message('error', 'Erp_vendor_notification_vendor_model: insert email template failed vendor_id=' . $vendor_id . ' code=' . ($err['code'] ?? '') . ' message=' . ($err['message'] ?? ''));
				return FALSE;
			}
		}

		if (!$db->table_exists('erp_vendor_whatsapp_templates')) {
			$this->last_error = 'Missing table in vendor DB: erp_vendor_whatsapp_templates';
			log_message('error', 'Erp_vendor_notification_vendor_model: vendor table missing vendor_id=' . $vendor_id . ' table=erp_vendor_whatsapp_templates');
			return FALSE;
		}
		$ok = $db->where('vendor_id', $vendor_id)->delete('erp_vendor_whatsapp_templates');
		if ($ok === FALSE) {
			$err = $db->error();
			$this->last_error = 'Failed clearing vendor WhatsApp templates: ' . ($err['message'] ?? 'unknown');
			log_message('error', 'Erp_vendor_notification_vendor_model: clear whatsapp templates failed vendor_id=' . $vendor_id . ' code=' . ($err['code'] ?? '') . ' message=' . ($err['message'] ?? ''));
			return FALSE;
		}
		foreach ((array)$this->Erp_vendor_notification_model->getWhatsappTemplates($vendor_id) as $t) {
			unset($t['id']);
			// normalize param_map_json back to string
			if (isset($t['param_map_json']) && is_array($t['param_map_json'])) {
				$t['param_map_json'] = json_encode($t['param_map_json'], JSON_UNESCAPED_UNICODE);
			}
			$ok = $db->insert('erp_vendor_whatsapp_templates', $t);
			if (empty($ok)) {
				$err = $db->error();
				$this->last_error = 'Failed syncing vendor WhatsApp templates: ' . ($err['message'] ?? 'unknown');
				log_message('error', 'Erp_vendor_notification_vendor_model: insert whatsapp template failed vendor_id=' . $vendor_id . ' code=' . ($err['code'] ?? '') . ' message=' . ($err['message'] ?? ''));
				return FALSE;
			}
		}

		if (!$db->table_exists('erp_vendor_sms_templates')) {
			$this->last_error = 'Missing table in vendor DB: erp_vendor_sms_templates';
			log_message('error', 'Erp_vendor_notification_vendor_model: vendor table missing vendor_id=' . $vendor_id . ' table=erp_vendor_sms_templates');
			return FALSE;
		}
		$ok = $db->where('vendor_id', $vendor_id)->delete('erp_vendor_sms_templates');
		if ($ok === FALSE) {
			$err = $db->error();
			$this->last_error = 'Failed clearing vendor SMS templates: ' . ($err['message'] ?? 'unknown');
			log_message('error', 'Erp_vendor_notification_vendor_model: clear sms templates failed vendor_id=' . $vendor_id . ' code=' . ($err['code'] ?? '') . ' message=' . ($err['message'] ?? ''));
			return FALSE;
		}
		foreach ((array)$this->Erp_vendor_notification_model->getSmsTemplates($vendor_id) as $t) {
			unset($t['id']);
			$ok = $db->insert('erp_vendor_sms_templates', $t);
			if (empty($ok)) {
				$err = $db->error();
				$this->last_error = 'Failed syncing vendor SMS templates: ' . ($err['message'] ?? 'unknown');
				log_message('error', 'Erp_vendor_notification_vendor_model: insert sms template failed vendor_id=' . $vendor_id . ' code=' . ($err['code'] ?? '') . ' message=' . ($err['message'] ?? ''));
				return FALSE;
			}
		}

		$this->last_error = '';
		return TRUE;
	}

	// ---------------------------------------------------------------------
	// JSON helpers (same behavior as master model)
	// ---------------------------------------------------------------------

	private function encodeJsonFields(array $row)
	{
		$row['whatsapp_headers_json'] = $this->encodeJson($row['whatsapp_headers_json'] ?? NULL);
		$row['whatsapp_default_params_json'] = $this->encodeJson($row['whatsapp_default_params_json'] ?? NULL);
		$row['sms_headers_json'] = $this->encodeJson($row['sms_headers_json'] ?? NULL);
		$row['sms_default_params_json'] = $this->encodeJson($row['sms_default_params_json'] ?? NULL);
		return $row;
	}

	private function decodeJsonFields(array $row)
	{
		$row['whatsapp_headers_json'] = $this->decodeJson($row['whatsapp_headers_json'] ?? NULL);
		$row['whatsapp_default_params_json'] = $this->decodeJson($row['whatsapp_default_params_json'] ?? NULL);
		$row['sms_headers_json'] = $this->decodeJson($row['sms_headers_json'] ?? NULL);
		$row['sms_default_params_json'] = $this->decodeJson($row['sms_default_params_json'] ?? NULL);
		return $row;
	}

	private function encodeJson($value)
	{
		if ($value === NULL || $value === '') return NULL;
		if (is_string($value)) {
			$trim = trim($value);
			if ($trim === '') return NULL;
			$decoded = json_decode($trim, TRUE);
			if (json_last_error() === JSON_ERROR_NONE) {
				return json_encode($decoded, JSON_UNESCAPED_UNICODE);
			}
			return $trim;
		}
		return json_encode($value, JSON_UNESCAPED_UNICODE);
	}

	private function decodeJson($value)
	{
		if ($value === NULL || $value === '') return NULL;
		if (is_array($value)) return $value;
		$decoded = json_decode((string)$value, TRUE);
		return (json_last_error() === JSON_ERROR_NONE) ? $decoded : NULL;
	}
}

