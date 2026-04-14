<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ERP Vendor Notification Model
 *
 * Stores per-vendor Email/SMS/WhatsApp configuration (master DB).
 */
class Erp_vendor_notification_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		// Master database
		// IMPORTANT: this model is used from tenant/vendor context too,
		// so we must force master connection as $this->db.
		$this->db = $this->load->database('default', TRUE);
	}

	public function getSettings($vendor_id)
	{
		$row = $this->db
			->where('vendor_id', (int)$vendor_id)
			->get('erp_vendor_notification_settings')
			->row_array();

		if (!$row) {
			return NULL;
		}

		return $this->decodeJsonFields($row);
	}

	public function upsertSettings($vendor_id, array $data)
	{
		$vendor_id = (int)$vendor_id;
		$existing = $this->db
			->select('id')
			->where('vendor_id', $vendor_id)
			->get('erp_vendor_notification_settings')
			->row_array();

		$payload = $this->sanitizeSettingsPayload($data);
		$payload['vendor_id'] = $vendor_id;

		if ($existing) {
			$this->db->where('vendor_id', $vendor_id);
			return (bool)$this->db->update('erp_vendor_notification_settings', $payload);
		}

		return (bool)$this->db->insert('erp_vendor_notification_settings', $payload);
	}

	public function getWhatsappTemplates($vendor_id)
	{
		$vendor_id = (int)$vendor_id;
		$rows = $this->db
			->where('vendor_id', $vendor_id)
			->order_by('id', 'asc')
			->get('erp_vendor_whatsapp_templates')
			->result_array();

		foreach ($rows as &$row) {
			$row['param_map_json'] = $this->decodeJson($row['param_map_json']);
		}
		unset($row);

		return $rows;
	}

	public function getEmailTemplates($vendor_id)
	{
		$vendor_id = (int)$vendor_id;
		return $this->db
			->where('vendor_id', $vendor_id)
			->order_by('id', 'asc')
			->get('erp_vendor_email_templates')
			->result_array();
	}

	/**
	 * Upsert vendor email templates by event_key.
	 *
	 * - Inserts new templates for new event_keys
	 * - Updates existing templates for same event_key
	 * - Does NOT delete other templates (use deleteEmailTemplatesByEventKeys)
	 */
	public function upsertEmailTemplates($vendor_id, array $templates)
	{
		$vendor_id = (int)$vendor_id;

		$this->db->trans_start();
		foreach ($templates as $t) {
			$event_key = isset($t['event_key']) ? trim((string)$t['event_key']) : '';
			$email_subject = isset($t['email_subject']) ? trim((string)$t['email_subject']) : '';
			$email_html = isset($t['email_html']) ? (string)$t['email_html'] : '';
			if ($event_key === '' || $email_subject === '' || trim($email_html) === '') {
				continue;
			}

			$payload = [
				'vendor_id' => $vendor_id,
				'event_key' => $event_key,
				'email_subject' => $email_subject,
				'email_html' => $email_html,
				'is_active' => isset($t['is_active']) ? (int)(!!$t['is_active']) : 1,
			];

			$existing = $this->db
				->select('id')
				->where('vendor_id', $vendor_id)
				->where('event_key', $event_key)
				->get('erp_vendor_email_templates')
				->row_array();

			if ($existing && !empty($existing['id'])) {
				$this->db->where('id', (int)$existing['id']);
				$this->db->update('erp_vendor_email_templates', $payload);
			} else {
				$this->db->insert('erp_vendor_email_templates', $payload);
			}
		}

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	public function deleteEmailTemplatesByEventKeys($vendor_id, array $event_keys)
	{
		$vendor_id = (int)$vendor_id;
		if ($vendor_id <= 0) return false;

		$keys = [];
		foreach ($event_keys as $k) {
			$k = trim((string)$k);
			if ($k !== '') $keys[] = $k;
		}
		$keys = array_values(array_unique($keys));
		if (empty($keys)) return true;

		$this->db->where('vendor_id', $vendor_id);
		$this->db->where_in('event_key', $keys);
		return (bool)$this->db->delete('erp_vendor_email_templates');
	}

	public function getSmsTemplates($vendor_id)
	{
		$vendor_id = (int)$vendor_id;
		return $this->db
			->where('vendor_id', $vendor_id)
			->order_by('id', 'asc')
			->get('erp_vendor_sms_templates')
			->result_array();
	}

	public function replaceSmsTemplates($vendor_id, array $templates)
	{
		$vendor_id = (int)$vendor_id;

		$this->db->trans_start();
		$this->db->where('vendor_id', $vendor_id)->delete('erp_vendor_sms_templates');

		foreach ($templates as $t) {
			$template_key = isset($t['template_key']) ? trim((string)$t['template_key']) : '';
			$event_key = isset($t['event_key']) ? trim((string)$t['event_key']) : '';
			$message_template = isset($t['message_template']) ? trim((string)$t['message_template']) : '';
			if ($template_key === '' || $message_template === '') {
				continue;
			}

			$this->db->insert('erp_vendor_sms_templates', [
				'vendor_id' => $vendor_id,
				'template_key' => $template_key,
				'event_key' => $event_key !== '' ? $event_key : null,
				'message_template' => $message_template,
				'is_active' => isset($t['is_active']) ? (int)(!!$t['is_active']) : 1,
			]);
		}

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	/**
	 * Replace all templates for a vendor (simple + predictable).
	 *
	 * @param int $vendor_id
	 * @param array $templates Each template: template_key, template_name, language?, param_map_json (array|string), is_active?
	 * @return bool
	 */
	public function replaceWhatsappTemplates($vendor_id, array $templates)
	{
		$vendor_id = (int)$vendor_id;

		$this->db->trans_start();

		$this->db->where('vendor_id', $vendor_id)->delete('erp_vendor_whatsapp_templates');

		foreach ($templates as $t) {
			$template_key = isset($t['template_key']) ? trim((string)$t['template_key']) : '';
			$event_key = isset($t['event_key']) ? trim((string)$t['event_key']) : '';
			$template_name = isset($t['template_name']) ? trim((string)$t['template_name']) : '';

			if ($template_key === '' || $template_name === '') {
				continue;
			}

			$insert = [
				'vendor_id' => $vendor_id,
				'template_key' => $template_key,
				'event_key' => $event_key !== '' ? $event_key : null,
				'template_name' => $template_name,
				'language' => isset($t['language']) && $t['language'] !== '' ? trim((string)$t['language']) : NULL,
				'param_map_json' => $this->encodeJson(isset($t['param_map_json']) ? $t['param_map_json'] : NULL),
				'is_active' => isset($t['is_active']) ? (int)(!!$t['is_active']) : 1
			];

			$this->db->insert('erp_vendor_whatsapp_templates', $insert);
		}

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	// ---------------------------------------------------------------------
	// Master Events + Master Templates + Vendor Mappings (master DB)
	// ---------------------------------------------------------------------

	public function getNotificationEvents($include_inactive = false)
	{
		$q = $this->db->order_by('title', 'asc')->from('erp_notification_events');
		if (!$include_inactive) {
			$q->where('is_active', 1);
		}
		return $q->get()->result_array();
	}

	public function getNotificationEventById($event_id)
	{
		return $this->db
			->where('id', (int)$event_id)
			->get('erp_notification_events')
			->row_array();
	}

	public function getNotificationEventByKey($event_key)
	{
		$event_key = trim((string)$event_key);
		if ($event_key === '') return null;
		return $this->db
			->where('event_key', $event_key)
			->where('is_active', 1)
			->get('erp_notification_events')
			->row_array();
	}

	public function upsertNotificationEvent(array $data, $event_id = null)
	{
		$payload = [
			'event_key' => isset($data['event_key']) ? trim((string)$data['event_key']) : '',
			'title' => isset($data['title']) ? trim((string)$data['title']) : '',
			'is_active' => isset($data['is_active']) ? (int)(!!$data['is_active']) : 1,
		];

		if ($payload['event_key'] === '' || $payload['title'] === '') {
			return false;
		}

		if ($event_id) {
			$this->db->where('id', (int)$event_id);
			return (bool)$this->db->update('erp_notification_events', $payload);
		}

		return (bool)$this->db->insert('erp_notification_events', $payload);
	}

	public function deleteNotificationEvent($event_id)
	{
		$this->db->where('id', (int)$event_id);
		return (bool)$this->db->delete('erp_notification_events');
	}

	public function getMasterTemplates($filters = [])
	{
		$q = $this->db->from('erp_notification_master_templates');

		if (!empty($filters['event_id'])) {
			$q->where('event_id', (int)$filters['event_id']);
		}
		if (!empty($filters['channel'])) {
			$q->where('channel', (string)$filters['channel']);
		}
		if (empty($filters['include_inactive'])) {
			$q->where('is_active', 1);
		}

		$rows = $q->order_by('name', 'asc')->get()->result_array();
		foreach ($rows as &$r) {
			$r['param_map_json'] = $this->decodeJson($r['param_map_json']);
		}
		unset($r);
		return $rows;
	}

	public function getMasterTemplateById($template_id)
	{
		$row = $this->db
			->where('id', (int)$template_id)
			->get('erp_notification_master_templates')
			->row_array();

		if ($row) {
			$row['param_map_json'] = $this->decodeJson($row['param_map_json']);
		}
		return $row;
	}

	public function upsertMasterTemplate(array $data, $template_id = null)
	{
		$payload = [
			'event_id' => isset($data['event_id']) ? (int)$data['event_id'] : 0,
			'channel' => isset($data['channel']) ? trim((string)$data['channel']) : '',
			'name' => isset($data['name']) ? trim((string)$data['name']) : '',
			'is_active' => isset($data['is_active']) ? (int)(!!$data['is_active']) : 1,

			'email_subject' => isset($data['email_subject']) ? trim((string)$data['email_subject']) : null,
			'email_html' => isset($data['email_html']) ? (string)$data['email_html'] : null,

			'template_name' => isset($data['template_name']) ? trim((string)$data['template_name']) : null,
			'language' => isset($data['language']) && trim((string)$data['language']) !== '' ? trim((string)$data['language']) : null,
			'param_map_json' => $this->encodeJson(isset($data['param_map_json']) ? $data['param_map_json'] : null),

			'message_template' => isset($data['message_template']) ? trim((string)$data['message_template']) : null,
		];

		if ($payload['event_id'] <= 0 || $payload['channel'] === '' || $payload['name'] === '') {
			return false;
		}

		$channel = strtolower($payload['channel']);
		if (!in_array($channel, ['email', 'whatsapp', 'sms'], true)) {
			return false;
		}
		$payload['channel'] = $channel;

		// Normalize unused fields by channel (keeps DB clean)
		if ($channel !== 'email') {
			$payload['email_subject'] = null;
			$payload['email_html'] = null;
		}
		if ($channel !== 'whatsapp') {
			$payload['template_name'] = null;
			$payload['language'] = null;
			$payload['param_map_json'] = null;
		}
		if ($channel !== 'sms') {
			$payload['message_template'] = null;
		}

		if ($template_id) {
			$this->db->where('id', (int)$template_id);
			return (bool)$this->db->update('erp_notification_master_templates', $payload);
		}

		return (bool)$this->db->insert('erp_notification_master_templates', $payload);
	}

	public function deleteMasterTemplate($template_id)
	{
		$this->db->where('id', (int)$template_id);
		return (bool)$this->db->delete('erp_notification_master_templates');
	}

	public function getVendorEventTemplateMappings($vendor_id)
	{
		$vendor_id = (int)$vendor_id;
		$rows = $this->db
			->where('vendor_id', $vendor_id)
			->get('erp_vendor_notification_event_templates')
			->result_array();

		$out = [];
		foreach ($rows as $r) {
			$event_id = (int)($r['event_id'] ?? 0);
			$channel = (string)($r['channel'] ?? '');
			if ($event_id <= 0 || $channel === '') continue;
			$out[$event_id][$channel] = [
				'master_template_id' => isset($r['master_template_id']) ? (int)$r['master_template_id'] : 0,
				'is_enabled' => !empty($r['is_enabled']) ? 1 : 0,
			];
		}
		return $out;
	}

	public function getVendorEventTemplateMapping($vendor_id, $event_id, $channel)
	{
		$vendor_id = (int)$vendor_id;
		$event_id = (int)$event_id;
		$channel = strtolower(trim((string)$channel));
		if ($vendor_id <= 0 || $event_id <= 0 || $channel === '') return null;

		$row = $this->db
			->where('vendor_id', $vendor_id)
			->where('event_id', $event_id)
			->where('channel', $channel)
			->get('erp_vendor_notification_event_templates')
			->row_array();
		return $row ?: null;
	}

	public function replaceVendorEventTemplateMappings($vendor_id, array $mappings)
	{
		$vendor_id = (int)$vendor_id;

		$this->db->trans_start();
		$this->db->where('vendor_id', $vendor_id)->delete('erp_vendor_notification_event_templates');

		foreach ($mappings as $m) {
			$event_id = isset($m['event_id']) ? (int)$m['event_id'] : 0;
			$channel = isset($m['channel']) ? strtolower(trim((string)$m['channel'])) : '';
			if ($event_id <= 0 || !in_array($channel, ['email', 'whatsapp', 'sms'], true)) {
				continue;
			}

			$this->db->insert('erp_vendor_notification_event_templates', [
				'vendor_id' => $vendor_id,
				'event_id' => $event_id,
				'channel' => $channel,
				'master_template_id' => !empty($m['master_template_id']) ? (int)$m['master_template_id'] : null,
				'is_enabled' => !empty($m['is_enabled']) ? 1 : 0,
			]);
		}

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	private function sanitizeSettingsPayload(array $data)
	{
		$allowed = [
			'email_enabled',
			'email_smtp_host',
			'email_smtp_port',
			'email_smtp_user',
			'email_smtp_pass',
			'email_smtp_crypto',
			'email_from_name',
			'email_from_email',
			'whatsapp_enabled',
			'whatsapp_provider_name',
			'whatsapp_endpoint_url',
			'whatsapp_http_method',
			'whatsapp_headers_json',
			'whatsapp_default_params_json',
			'sms_enabled',
			'sms_provider_name',
			'sms_endpoint_url',
			'sms_http_method',
			'sms_headers_json',
			'sms_default_params_json',
			// sms params are stored in sms_default_params_json (key/value UI)
		];

		$out = [];
		foreach ($allowed as $key) {
			if (array_key_exists($key, $data)) {
				$out[$key] = $data[$key];
			}
		}

		$out['email_enabled'] = isset($out['email_enabled']) ? (int)(!!$out['email_enabled']) : 0;
		$out['whatsapp_enabled'] = isset($out['whatsapp_enabled']) ? (int)(!!$out['whatsapp_enabled']) : 0;
		$out['sms_enabled'] = isset($out['sms_enabled']) ? (int)(!!$out['sms_enabled']) : 0;

		if (isset($out['email_smtp_port']) && $out['email_smtp_port'] !== NULL && $out['email_smtp_port'] !== '') {
			$out['email_smtp_port'] = (int)$out['email_smtp_port'];
		} else {
			$out['email_smtp_port'] = NULL;
		}

		$out['whatsapp_headers_json'] = $this->encodeJson(isset($out['whatsapp_headers_json']) ? $out['whatsapp_headers_json'] : NULL);
		$out['whatsapp_default_params_json'] = $this->encodeJson(isset($out['whatsapp_default_params_json']) ? $out['whatsapp_default_params_json'] : NULL);
		$out['sms_headers_json'] = $this->encodeJson(isset($out['sms_headers_json']) ? $out['sms_headers_json'] : NULL);
		$out['sms_default_params_json'] = $this->encodeJson(isset($out['sms_default_params_json']) ? $out['sms_default_params_json'] : NULL);

		return $out;
	}

	private function decodeJsonFields(array $row)
	{
		$row['whatsapp_headers_json'] = $this->decodeJson(isset($row['whatsapp_headers_json']) ? $row['whatsapp_headers_json'] : NULL);
		$row['whatsapp_default_params_json'] = $this->decodeJson(isset($row['whatsapp_default_params_json']) ? $row['whatsapp_default_params_json'] : NULL);
		$row['sms_headers_json'] = $this->decodeJson(isset($row['sms_headers_json']) ? $row['sms_headers_json'] : NULL);
		$row['sms_default_params_json'] = $this->decodeJson(isset($row['sms_default_params_json']) ? $row['sms_default_params_json'] : NULL);
		return $row;
	}

	private function encodeJson($value)
	{
		if ($value === NULL || $value === '') {
			return NULL;
		}

		if (is_string($value)) {
			$trim = trim($value);
			if ($trim === '') {
				return NULL;
			}
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
		if ($value === NULL || $value === '') {
			return NULL;
		}
		if (is_array($value)) {
			return $value;
		}

		$decoded = json_decode((string)$value, TRUE);
		if (json_last_error() === JSON_ERROR_NONE) {
			return $decoded;
		}
		return NULL;
	}
}

