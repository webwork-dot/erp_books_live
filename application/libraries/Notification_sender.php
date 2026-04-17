<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Notification Sender
 *
 * Sends Email / WhatsApp / SMS using per-vendor configuration stored in master DB.
 */
class Notification_sender
{
	/** @var CI_Controller */
	private $CI;

	public function __construct()
	{
		$this->CI =& get_instance();
		// Runtime must use vendor DB only
		$this->CI->load->model('Erp_vendor_notification_vendor_model');
		$this->CI->load->library('email');
	}

	/**
	 * Send a configured event notification (master template + vendor mapping).
	 *
	 * @param int $vendor_id
	 * @param string $event_key
	 * @param array $variables Must include targets like email_to and/or mobile depending on channels.
	 * @return array
	 */
	public function sendEvent($vendor_id, $event_key, $variables = [])
	{
		$vendor_id = (int)$vendor_id;
		$event_key = trim((string)$event_key);
		$vars = is_array($variables) ? $variables : [];

		if ($vendor_id <= 0 || $event_key === '') {
			log_message('error', 'Notification_sender: invalid vendor_id/event_key. vendor_id=' . $vendor_id . ' event_key=' . $event_key);
			return ['success' => false, 'message' => 'Invalid vendor_id or event_key.'];
		}

		$event = $this->CI->Erp_vendor_notification_vendor_model->getNotificationEventByKey($vendor_id, $event_key);
		if (!$event || empty($event['id'])) {
			log_message('error', 'Notification_sender: event not found or inactive. vendor_id=' . $vendor_id . ' event_key=' . $event_key);
			return ['success' => false, 'message' => 'Event not found or inactive.'];
		}

		$event_id = (int)$event['id'];
		$vars['event_key'] = $event_key;
		$vars['event_title'] = $event['title'] ?? '';

		$out = [
			'success' => true,
			'event_key' => $event_key,
			'results' => []
		];

		// Email (supports separate user + vendor templates per event_key)
		$emailTpls = $this->CI->Erp_vendor_notification_vendor_model->getEmailTemplates($vendor_id);
		$userTpl = null;
		$vendorTpl = null;
		foreach ($emailTpls as $t) {
			if (empty($t['is_active']) || !isset($t['event_key']) || (string)$t['event_key'] !== (string)$event_key) {
				continue;
			}
			$aud = isset($t['audience']) ? strtolower((string)$t['audience']) : 'user';
			if ($aud === 'vendor' && $vendorTpl === null) {
				$vendorTpl = $t;
			} elseif ($aud !== 'vendor' && $userTpl === null) {
				$userTpl = $t;
			}
			if ($userTpl && $vendorTpl) break;
		}

		$out['results']['email_user'] = null;
		$out['results']['email_vendor'] = null;
		// Backward compatibility: older code expects $res['results']['email']
		$out['results']['email'] = null;

		// User email
		if ($userTpl) {
			$to = trim((string)($vars['email_to'] ?? $vars['to'] ?? ''));
			if ($to !== '') {
				$subject = $this->replaceTokensInString((string)($userTpl['email_subject'] ?? ''), $vars);
				$html = $this->replaceTokensInString((string)($userTpl['email_html'] ?? ''), $vars);
				$html = html_entity_decode($html, ENT_QUOTES, 'UTF-8');
				$cc = (string)($userTpl['cc_emails'] ?? '');
				$res = $this->sendEmail($vendor_id, $to, $subject, $html, [], $cc);
				$out['results']['email_user'] = $res;
				$out['results']['email'] = $res;
				if (empty($res['success'])) {
					$out['success'] = false;
					log_message('error', 'Notification_sender: user email send failed. vendor_id=' . $vendor_id . ' event_key=' . $event_key . ' to=' . $to . ' message=' . ($res['message'] ?? ''));
				}
			} else {
				// Skip user email when recipient is not available (do not fail the whole event).
				$out['results']['email_user'] = ['success' => false, 'skipped' => true, 'message' => 'Skipped user email (missing email_to).'];
				$out['results']['email'] = $out['results']['email_user'];
			}
		}

		// Vendor email (separate template; recipients come from template_to_emails)
		if ($vendorTpl) {
			$to = trim((string)($vendorTpl['to_emails'] ?? ''));
			if ($to !== '') {
				$subject = $this->replaceTokensInString((string)($vendorTpl['email_subject'] ?? ''), $vars);
				$html = $this->replaceTokensInString((string)($vendorTpl['email_html'] ?? ''), $vars);
				$html = html_entity_decode($html, ENT_QUOTES, 'UTF-8');
				$cc = (string)($vendorTpl['cc_emails'] ?? '');
				$res = $this->sendEmail($vendor_id, $to, $subject, $html, [], $cc);
				$out['results']['email_vendor'] = $res;
				if (empty($res['success'])) {
					$out['success'] = false;
					log_message('error', 'Notification_sender: vendor email send failed. vendor_id=' . $vendor_id . ' event_key=' . $event_key . ' to=' . $to . ' message=' . ($res['message'] ?? ''));
				}
			} else {
				$out['results']['email_vendor'] = ['success' => false, 'message' => 'Missing to_emails for vendor email template.'];
				$out['success'] = false;
				log_message('error', 'Notification_sender: missing to_emails (vendor). vendor_id=' . $vendor_id . ' event_key=' . $event_key);
			}
		}

		if (!$userTpl && !$vendorTpl) {
			log_message('error', 'Notification_sender: no active email template. vendor_id=' . $vendor_id . ' event_key=' . $event_key);
		}

		// WhatsApp (vendor WhatsApp templates mapped by event_key)
		$waTpl = null;
		$waTpls = $this->CI->Erp_vendor_notification_vendor_model->getWhatsappTemplates($vendor_id);
		foreach ($waTpls as $t) {
			if (!empty($t['is_active']) && isset($t['event_key']) && (string)$t['event_key'] === (string)$event_key) {
				$waTpl = $t;
				break;
			}
		}
		if ($waTpl) {
			$mobile = trim((string)($vars['mobile'] ?? ''));
			if ($mobile !== '') {
				$template_key = (string)($waTpl['template_key'] ?? '');
				$out['results']['whatsapp'] = $this->sendWhatsapp($vendor_id, $mobile, $template_key, $vars);
				if (empty($out['results']['whatsapp']['success'])) $out['success'] = false;
			} else {
				$out['results']['whatsapp'] = ['success' => false, 'message' => 'Missing mobile for WhatsApp channel.'];
				$out['success'] = false;
			}
		}

		// SMS (vendor SMS templates mapped by event_key)
		$smsTpl = null;
		$smsTpls = $this->CI->Erp_vendor_notification_vendor_model->getSmsTemplates($vendor_id);
		foreach ($smsTpls as $t) {
			if (!empty($t['is_active']) && isset($t['event_key']) && (string)$t['event_key'] === (string)$event_key) {
				$smsTpl = $t;
				break;
			}
		}
		if ($smsTpl) {
			$mobile = trim((string)($vars['mobile'] ?? ''));
			if ($mobile !== '') {
				$message = $this->replaceTokensInString((string)($smsTpl['message_template'] ?? ''), $vars);
				$res = $this->sendSms($vendor_id, $mobile, $message, $vars);
				$out['results']['sms'] = $res;
				if (empty($res['success'])) $out['success'] = false;
			} else {
				$out['results']['sms'] = ['success' => false, 'message' => 'Missing mobile for SMS channel.'];
				$out['success'] = false;
			}
		}

		return $out;
	}

	public function sendEmail($vendor_id, $to, $subject, $html, $attachments = [], $cc = '')
	{
		$settings = $this->CI->Erp_vendor_notification_vendor_model->getSettings($vendor_id);
		if (empty($settings) || empty($settings['email_enabled'])) {
			return ['success' => false, 'message' => 'Email is not configured for this vendor.'];
		}

		$host = trim((string)($settings['email_smtp_host'] ?? ''));
		$port = (int)($settings['email_smtp_port'] ?? 0);
		$user = trim((string)($settings['email_smtp_user'] ?? ''));
		$pass = (string)($settings['email_smtp_pass'] ?? '');

		if ($host === '' || $port <= 0 || $user === '' || $pass === '') {
			return ['success' => false, 'message' => 'SMTP settings are incomplete.'];
		}

		$crypto = strtolower((string)($settings['email_smtp_crypto'] ?? ''));
		if (!in_array($crypto, ['', 'tls', 'ssl'], true)) {
			$crypto = '';
		}

		$config = [
			'protocol' => 'smtp',
			'smtp_host' => $host,
			'smtp_user' => $user,
			'smtp_pass' => $pass,
			'smtp_port' => $port,
			'smtp_crypto' => $crypto !== '' ? $crypto : NULL,
			'mailtype' => 'html',
			'charset' => 'utf-8',
			'newline' => "\r\n",
			'crlf' => "\r\n"
		];

		$this->CI->email->clear(true);
		$this->CI->email->initialize($config);

		$fromEmail = trim((string)($settings['email_from_email'] ?? ''));
		if ($fromEmail === '') {
			$fromEmail = $user;
		}
		$fromName = trim((string)($settings['email_from_name'] ?? ''));

		$this->CI->email->from($fromEmail, $fromName !== '' ? $fromName : NULL);
		$this->CI->email->to($this->normalizeEmailList($to));
		$ccList = $this->normalizeEmailList($cc);
		if (!empty($ccList)) {
			$this->CI->email->cc($ccList);
		}
		$this->CI->email->subject($subject);
		$this->CI->email->message($html);

		if (is_array($attachments)) {
			foreach ($attachments as $a) {
				if (!empty($a)) {
					$this->CI->email->attach($a);
				}
			}
		}

		$ok = $this->CI->email->send();
		if ($ok) {
			return ['success' => true, 'message' => 'Email sent successfully.'];
		}

		$debug = (string)$this->CI->email->print_debugger();
		return [
			'success' => false,
			'message' => strip_tags($debug),
			'debug' => $debug,
		];
	}

	private function normalizeEmailList($value)
	{
		if ($value === NULL) return [];
		if (is_array($value)) {
			$out = [];
			foreach ($value as $v) {
				$v = trim((string)$v);
				if ($v !== '') $out[] = $v;
			}
			return array_values(array_unique($out));
		}
		$s = trim((string)$value);
		if ($s === '') return [];
		$parts = preg_split('/[,\s]+/', $s);
		$out = [];
		foreach ($parts as $p) {
			$p = trim((string)$p);
			if ($p !== '') $out[] = $p;
		}
		return array_values(array_unique($out));
	}

	public function sendWhatsapp($vendor_id, $mobile, $template_key, $variables = [], $media_url = NULL)
	{
		$settings = $this->CI->Erp_vendor_notification_vendor_model->getSettings($vendor_id);
		if (empty($settings) || empty($settings['whatsapp_enabled'])) {
			return ['success' => false, 'message' => 'WhatsApp is not configured for this vendor.'];
		}

		$endpoint = trim((string)($settings['whatsapp_endpoint_url'] ?? ''));
		if ($endpoint === '') {
			return ['success' => false, 'message' => 'WhatsApp endpoint URL is missing.'];
		}

		$templates = $this->CI->Erp_vendor_notification_vendor_model->getWhatsappTemplates($vendor_id);
		$template = NULL;
		foreach ($templates as $t) {
			if (!empty($t['is_active']) && isset($t['template_key']) && (string)$t['template_key'] === (string)$template_key) {
				$template = $t;
				break;
			}
		}
		if (!$template) {
			return ['success' => false, 'message' => 'WhatsApp template key not found.'];
		}

		$vars = is_array($variables) ? $variables : [];
		$vars['mobile'] = $mobile;
		$vars['template_key'] = $template_key;
		$vars['template_name'] = $template['template_name'] ?? '';
		$vars['media_url'] = $media_url;

		$method = strtoupper((string)($settings['whatsapp_http_method'] ?? 'POST'));
		if (!in_array($method, ['GET', 'POST'], true)) {
			$method = 'POST';
		}

		$headers = is_array($settings['whatsapp_headers_json'] ?? NULL) ? $settings['whatsapp_headers_json'] : [];
		$defaultParams = is_array($settings['whatsapp_default_params_json'] ?? NULL) ? $settings['whatsapp_default_params_json'] : [];
		$params = $this->applyTokens($defaultParams, $vars);

		$paramMap = $template['param_map_json'] ?? NULL;
		if (is_array($paramMap)) {
			$params = array_merge($params, $this->applyTokens($paramMap, $vars));
		}

		// Some "WhatsApp" gateways are param-based (bhashsms-like). Help ensure core params exist.
		$lowerKeys = array_map('strtolower', array_keys($params));

		$hasMobileKey = in_array('mobile', $lowerKeys, true) || in_array('phone', $lowerKeys, true) || in_array('to', $lowerKeys, true);
		if (!$hasMobileKey) {
			$params['phone'] = $mobile;
		}

		// If the gateway expects a message/text param, default to template_name unless vendor mapped it.
		$hasMessageKey = in_array('message', $lowerKeys, true) || in_array('text', $lowerKeys, true) || in_array('msg', $lowerKeys, true);
		if (!$hasMessageKey) {
			$text = trim((string)($template['template_name'] ?? ''));
			if ($text !== '') {
				$params['text'] = $text;
			}
		}

		if (isset($vars['test']) || ENVIRONMENT !== 'production') {
			log_message('info', 'Notification_sender: sending WhatsApp. vendor_id=' . $vendor_id . ' mobile=' . $mobile . ' template_key=' . $template_key . ' params=' . json_encode($params));
		}

		return $this->sendHttp($endpoint, $method, $params, $headers);
	}

	public function sendSms($vendor_id, $mobile, $message, $variables = [])
	{
		$settings = $this->CI->Erp_vendor_notification_vendor_model->getSettings($vendor_id);
		if (empty($settings) || empty($settings['sms_enabled'])) {
			return ['success' => false, 'message' => 'SMS is not configured for this vendor.'];
		}

		$endpoint = trim((string)($settings['sms_endpoint_url'] ?? ''));
		if ($endpoint === '') {
			return ['success' => false, 'message' => 'SMS endpoint URL is missing.'];
		}

		$vars = is_array($variables) ? $variables : [];
		$vars['mobile'] = $mobile;
		$vars['message'] = $message;

		$method = strtoupper((string)($settings['sms_http_method'] ?? 'GET'));
		if (!in_array($method, ['GET', 'POST'], true)) {
			$method = 'GET';
		}

		$headers = is_array($settings['sms_headers_json'] ?? NULL) ? $settings['sms_headers_json'] : [];
		$defaultParams = is_array($settings['sms_default_params_json'] ?? NULL) ? $settings['sms_default_params_json'] : [];
		$params = $this->applyTokens($defaultParams, $vars);

		// Ensure core content is present (many gateways expect phone/text-like fields)
		// We do not override keys if the vendor already configured them.
		$lowerKeys = array_map('strtolower', array_keys($params));

		$hasMobileKey = in_array('mobile', $lowerKeys, true) || in_array('phone', $lowerKeys, true) || in_array('to', $lowerKeys, true);
		if (!$hasMobileKey) {
			$params['mobile'] = $mobile;
		}

		$hasMessageKey = in_array('message', $lowerKeys, true) || in_array('text', $lowerKeys, true) || in_array('msg', $lowerKeys, true);
		if (!$hasMessageKey) {
			$params['message'] = $message;
		}

		return $this->sendHttp($endpoint, $method, $params, $headers);
	}

	public function sendSmsTemplate($vendor_id, $mobile, $template_key, $variables = [])
	{
		$templates = $this->CI->Erp_vendor_notification_vendor_model->getSmsTemplates($vendor_id);
		$template = NULL;
		foreach ($templates as $t) {
			if (!empty($t['is_active']) && isset($t['template_key']) && (string)$t['template_key'] === (string)$template_key) {
				$template = $t;
				break;
			}
		}
		if (!$template) {
			return ['success' => false, 'message' => 'SMS template key not found.'];
		}

		$vars = is_array($variables) ? $variables : [];
		$message = $this->replaceTokensInString((string)$template['message_template'], $vars);
		if (trim($message) === '') {
			return ['success' => false, 'message' => 'SMS message template produced empty text.'];
		}

		return $this->sendSms($vendor_id, $mobile, $message, $vars);
	}

	private function sendHttp($endpoint, $method, array $params, array $headersAssoc)
	{
		$ch = curl_init();
		if (!$ch) {
			return ['success' => false, 'message' => 'cURL is not available.'];
		}

		$headers = [];
		$contentType = '';
		foreach ($headersAssoc as $k => $v) {
			$k = trim((string)$k);
			if ($k === '') continue;
			$line = $k . ': ' . (is_scalar($v) ? (string)$v : json_encode($v));
			$headers[] = $line;
			if (strtolower($k) === 'content-type') {
				$contentType = strtolower((string)$v);
			}
		}

		$url = $endpoint;
		$body = NULL;
		if ($method === 'GET') {
			$qs = http_build_query($params);
			if ($qs !== '') {
				$url .= (strpos($url, '?') === false ? '?' : '&') . $qs;
			}
		} else {
			if ($contentType !== '' && strpos($contentType, 'application/json') !== false) {
				$body = json_encode($params, JSON_UNESCAPED_UNICODE);
			} else {
				$body = http_build_query($params);
				if ($contentType === '') {
					$headers[] = 'Content-Type: application/x-www-form-urlencoded';
				}
			}
		}

		curl_setopt_array($ch, [
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_CONNECTTIMEOUT => 15,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_CUSTOMREQUEST => $method,
			// Make requests closer to Postman/browser defaults
			CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) ERP-Notifications/1.0',
			CURLOPT_ENCODING => '', // allow gzip/deflate/br if supported
		]);

		// Ensure we have an Accept header (some gateways behave differently)
		$hasAccept = false;
		foreach ($headers as $h) {
			if (stripos($h, 'accept:') === 0) {
				$hasAccept = true;
				break;
			}
		}
		if (!$hasAccept) {
			$headers[] = 'Accept: */*';
		}

		if (!empty($headers)) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		}
		if ($method === 'POST') {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
		}

		$response = curl_exec($ch);
		$err = curl_error($ch);
		$http = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$effective_url = (string)curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
		curl_close($ch);

		if ($response === false) {
			return ['success' => false, 'message' => 'HTTP request failed: ' . $err, 'http_code' => $http, 'effective_url' => $effective_url];
		}

		if ($http < 200 || $http >= 300) {
			log_message('error', 'Notification_sender HTTP Error: ' . $http . ' URL: ' . $effective_url . ' Response: ' . substr((string)$response, 0, 500));
		} elseif (ENVIRONMENT !== 'production') {
			log_message('info', 'Notification_sender HTTP OK: ' . $http . ' URL: ' . $effective_url);
		}

		if ($http >= 200 && $http < 300) {
			return [
				'success' => true,
				'message' => 'Request sent successfully.',
				'http_code' => $http,
				'effective_url' => $effective_url,
				'response' => $response,
				'response_len' => strlen((string)$response),
			];
		}

		return [
			'success' => false,
			'message' => 'HTTP error: ' . $http,
			'http_code' => $http,
			'effective_url' => $effective_url,
			'response' => $response,
			'response_len' => strlen((string)$response),
		];
	}

	private function applyTokens($data, array $vars)
	{
		if (!is_array($data)) {
			return [];
		}

		$out = [];
		foreach ($data as $k => $v) {
			if (is_array($v)) {
				$out[$k] = $this->applyTokens($v, $vars);
				continue;
			}
			if (is_string($v)) {
				$out[$k] = preg_replace_callback('/\\{\\{\\s*([a-zA-Z0-9_]+)\\s*\\}\\}|\\{\\s*([a-zA-Z0-9_]+)\\s*\\}/', function ($m) use ($vars) {
					$key = !empty($m[1]) ? $m[1] : (!empty($m[2]) ? $m[2] : '');
					return array_key_exists($key, $vars) && $vars[$key] !== NULL ? (string)$vars[$key] : '';
				}, $v);
				continue;
			}
			$out[$k] = $v;
		}
		return $out;
	}

	private function replaceTokensInString($text, array $vars)
	{
		return preg_replace_callback('/\\{\\{\\s*([a-zA-Z0-9_]+)\\s*\\}\\}|\\{\\s*([a-zA-Z0-9_]+)\\s*\\}/', function ($m) use ($vars) {
			$key = !empty($m[1]) ? $m[1] : (!empty($m[2]) ? $m[2] : '');
			return array_key_exists($key, $vars) && $vars[$key] !== NULL ? (string)$vars[$key] : '';
		}, (string)$text);
	}
}

