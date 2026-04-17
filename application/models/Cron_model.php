<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron_model extends CI_Model { 
    private $bigship_url = BIGSHIP_URL;
    private $velocity_url = VELOCITY_URL;
    private $shiprocket_base_url = 'https://apiv2.shiprocket.in/v1/external/';
     
	private $client_db = [];

    function __construct(){
        parent::__construct();

        /* cache control */
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        date_default_timezone_set('Asia/Kolkata');

        /* load once */
        $this->load->model('Erp_client_model');
        $this->load->model('Vendor_sync_model');
    }

	private function buildOrderVarsFromRow(array $row, $order_id)
	{
		$vars = [
			'order_id' => (int)$order_id,
			'order_unique_id' => $row['order_unique_id'] ?? '',
			'order_date' => $row['order_date'] ?? '',
			'payment_status' => $row['payment_status'] ?? '',

			// Common aliases (match template token names)
			'user_name' => $row['user_name'] ?? '',
			'user_email' => $row['user_email'] ?? '',
			'user_phone' => $row['user_phone'] ?? '',

			// Backward-compatible aliases (some gateways/templates use these)
			'parent_name' => $row['user_name'] ?? '',
			// Date-only helper (Y-m-d) from order_date
			'date' => !empty($row['order_date']) ? date('Y-m-d', strtotime((string)$row['order_date'])) : date('Y-m-d'),

			'customer_name' => $row['user_name'] ?? '',
			'email_to' => $row['user_email'] ?? '',
			'mobile' => $row['user_phone'] ?? '',
			'payment_method' => $row['payment_method'] ?? '',
			'payable_amt' => $row['payable_amt'] ?? ($row['total_amt'] ?? ''),
			'order_amount' => $row['payable_amt'] ?? ($row['total_amt'] ?? ''),
			'invoice_no' => $row['invoice_no'] ?? '',
			'awb_no' => $row['awb_no'] ?? '',
			'courier' => $row['courier'] ?? '',
		];
		return $vars;
	}

	/**
	 * Enrich vars with shipping + items + school/board/grade + child/student details.
	 * Uses vendor DB connection.
	 */
	private function enrichOrderVars($client_db, $vendor_id, $order_id, array $row, array $vars)
	{
		$vendor_id = (int)$vendor_id;
		$order_id = (int)$order_id;
		$vendor_domain = '';
		$vendor = $this->Erp_client_model->getClientById($vendor_id);
		if ($vendor && !empty($vendor['domain'])) {
			$vendor_domain = trim((string)$vendor['domain'], " \t\n\r\0\x0B./");
		}
		$vars['vendor_domain'] = $vendor_domain;

		// Shipping
		$ship = $client_db->select('*')->from('tbl_order_address')->where('order_id', $order_id)->order_by('id', 'ASC')->limit(1)->get()->row_array();
		if (!empty($ship)) {
			$vars['shipping_name'] = (string)($ship['name'] ?? '');
			$vars['shipping_phone'] = (string)($ship['mobile_no'] ?? '');
			$vars['shipping_address'] = (string)($ship['address'] ?? '');
			$vars['shipping_city'] = (string)($ship['city'] ?? '');
			$vars['shipping_state'] = (string)($ship['state'] ?? '');
			$vars['shipping_pincode'] = (string)($ship['pincode'] ?? '');
		} else {
			$vars['shipping_name'] = (string)($row['user_name'] ?? '');
			$vars['shipping_phone'] = (string)($row['user_phone'] ?? '');
			$vars['shipping_address'] = '';
			$vars['shipping_city'] = '';
			$vars['shipping_state'] = '';
			$vars['shipping_pincode'] = '';
		}

		// Items + HTML
		$items = $client_db->select('product_id, product_title, product_qty, product_price, total_price, variation_name, thumbnail_img, order_type, f_name, grade, school_id, branch_id, grade_id, board_id')
			->from('tbl_order_items')
			->where('order_id', $order_id)
			->order_by('id', 'ASC')
			->get()->result_array();

		$this->load->helper('common_helper');

		$total_qty = 0;
		$subtotal = 0;
		$order_items_html = '';

		foreach ($items as $it) {
			$qty = (int)($it['product_qty'] ?? 1);
			if ($qty <= 0) $qty = 1;
			$total_qty += $qty;

			$unit_price = (float)($it['product_price'] ?? 0);
			$row_total = (float)($it['total_price'] ?? 0);
			if ($row_total <= 0 && $unit_price > 0) $row_total = $unit_price * $qty;
			$subtotal += $row_total;

			$name = (string)($it['product_title'] ?? '');
			$size = (string)($it['variation_name'] ?? '');

			$img_url = '';
			$thumb = (string)($it['thumbnail_img'] ?? '');
			if ($thumb !== '') {
				// Always use vendor storefront domain for email images.
				if (stripos($thumb, 'http://') === 0 || stripos($thumb, 'https://') === 0) {
					$u = @parse_url($thumb);
					$path = isset($u['path']) ? $u['path'] : '';
					$query = isset($u['query']) ? ('?' . $u['query']) : '';
					$img_url = ($vendor_domain !== '' ? ('https://' . $vendor_domain) : '') . $path . $query;
				} else {
					$img_url = ($vendor_domain !== '' ? ('https://' . $vendor_domain . '/') : rtrim(base_url(), '/') . '/') . ltrim($thumb, '/');
				}
			} else {
				$pid = (int)($it['product_id'] ?? 0);
				if ($pid > 0) {
					if ($client_db->table_exists('erp_product_images')) {
						// Some tenants have different column names; pick the first existing.
						$imgCol = null;
						foreach (['image', 'file', 'file_name', 'image_path', 'img'] as $c) {
							if ($client_db->field_exists($c, 'erp_product_images')) {
								$imgCol = $c;
								break;
							}
						}
						if ($imgCol) {
							$img = $client_db->select($imgCol)->from('erp_product_images')
								->where('product_id', $pid)->where('vendor_id', $vendor_id)
								->order_by('is_main', 'DESC')->order_by('image_order', 'ASC')->limit(1)->get()->row_array();
							if (!empty($img[$imgCol])) $img_url = ($vendor_domain !== '' ? ('https://' . $vendor_domain . '/') : rtrim(base_url(), '/') . '/') . ltrim($img[$imgCol], '/');
						}
					}
					if ($img_url === '' && $client_db->table_exists('product_images')) {
						$imgCol = null;
						foreach (['image', 'file', 'file_name', 'image_path', 'img'] as $c) {
							if ($client_db->field_exists($c, 'product_images')) {
								$imgCol = $c;
								break;
							}
						}
						if ($imgCol) {
							$img = $client_db->select($imgCol)->from('product_images')
								->where('product_id', $pid)->order_by('is_main', 'DESC')->order_by('id', 'ASC')->limit(1)->get()->row_array();
							if (!empty($img[$imgCol])) $img_url = ($vendor_domain !== '' ? ('https://' . $vendor_domain . '/') : rtrim(base_url(), '/') . '/') . ltrim($img[$imgCol], '/');
						}
					}
				}
			}
			// Ensure https scheme.
			if (stripos($img_url, 'http://') === 0) $img_url = 'https://' . substr($img_url, 7);

			$img_cell = '';
			if ($img_url !== '') {
				$img_cell = '<img src="' . htmlspecialchars($img_url) . '" width="48" height="48" style="display:block;object-fit:cover;border:1px solid #e5e7eb;" alt="">';
			}

			$order_items_html .= '<tr>'
				. '<td style="padding:8px;border-bottom:1px solid #f1f5f9;">'
				. '<table cellpadding="0" cellspacing="0" border="0"><tr>'
				. '<td style="padding-right:10px;vertical-align:top;">' . $img_cell . '</td>'
				. '<td style="vertical-align:top;"><div style="font-weight:600;">' . htmlspecialchars($name) . '</div></td>'
				. '</tr></table>'
				. '</td>'
				. '<td style="padding:8px;border-bottom:1px solid #f1f5f9;">' . htmlspecialchars($size) . '</td>'
				. '<td align="center" style="padding:8px;border-bottom:1px solid #f1f5f9;">' . (int)$qty . '</td>'
				. '<td align="right" style="padding:8px;border-bottom:1px solid #f1f5f9;">' . htmlspecialchars((string)$row_total) . '</td>'
				. '</tr>';
		}

		$vars['order_items'] = $order_items_html;
		$vars['total_qty'] = $total_qty;
		$vars['subtotal'] = $subtotal;
		$vars['delivery_charge'] = $row['delivery_charge'] ?? '';
		$vars['discount_amt'] = $row['discount_amt'] ?? '';
		$vars['currency_code'] = $row['currency_code'] ?? ($row['currency'] ?? '');

		// Public invoice link (for WhatsApp document templates etc.)
		$invoice_link = '';
		
		// 1. Prioritize pre-generated physical PDF from database
		$db_invoice_url = trim((string)($row['invoice_url'] ?? ''));
		if ($db_invoice_url !== '') {
			if (stripos($db_invoice_url, 'http') === 0) {
				$invoice_link = $db_invoice_url;
			} else {
				if ($vendor_domain !== '') {
					$invoice_link = 'https://' . $vendor_domain . '/' . ltrim($db_invoice_url, '/');
				} else {
					$invoice_link = rtrim((string)base_url(), '/') . '/' . ltrim($db_invoice_url, '/');
				}
			}
		} 
		
		// 2. Fallback to dynamic link if no physical file is found
		if ($invoice_link === '' && !empty($vars['order_unique_id'])) {
			$invoice_path = '/shipping/customer_invoice/' . rawurlencode((string)$vars['order_unique_id']);
			if ($vendor_domain !== '') {
				$invoice_link = 'https://' . $vendor_domain . $invoice_path;
			} else {
				$invoice_link = rtrim((string)base_url(), '/') . $invoice_path;
			}
		}

		$vars['invoice_url'] = $invoice_link;
		// Common alias used by legacy WA integrations (document URL)
		$vars['file_url'] = $invoice_link;

		// Gateway helper: pre-built Params CSV (name,date,order_unique_id)
		if (!isset($vars['Params']) || $vars['Params'] === '') {
			$vars['Params'] = trim((string)($vars['parent_name'] ?? '')) . ',' . trim((string)($vars['date'] ?? '')) . ',' . trim((string)($vars['order_unique_id'] ?? ''));
		}

		// School/Board/Grade/Child
		$vars['school_name'] = '';
		$vars['board_name'] = '';
		$vars['grade_name'] = '';
		$vars['child_name'] = '';
		$vars['child_class'] = '';
		$vars['child_section'] = '';
		$school_id_for_board = 0;

		if (!empty($row['children_data'])) {
			$parsed = json_decode((string)$row['children_data'], true);
			if (is_array($parsed) && !empty($parsed)) {
				$first = $parsed[0];
				if (is_array($first)) {
					$vars['child_name'] = (string)($first['name'] ?? ($first['childName'] ?? ''));
					$vars['child_class'] = (string)($first['grade'] ?? ($first['class'] ?? ''));
					$vars['child_section'] = (string)($first['section'] ?? '');
				}
			}
		}

		foreach ($items as $it) {
			$otype = strtolower((string)($it['order_type'] ?? ''));
			if ($otype === 'bookset' || $otype === 'package') {
				$school_id = (int)($it['school_id'] ?? 0);
				$grade_id = (int)($it['grade_id'] ?? 0);
				$board_id = (int)($it['board_id'] ?? 0);
				if ($school_id_for_board <= 0 && $school_id > 0) $school_id_for_board = $school_id;

				if ($vars['school_name'] === '' && $school_id > 0 && $client_db->table_exists('erp_schools')) {
					$s = $client_db->select('school_name')->from('erp_schools')->where('id', $school_id)->limit(1)->get()->row_array();
					if (!empty($s['school_name'])) $vars['school_name'] = (string)$s['school_name'];
				}
				if ($vars['grade_name'] === '' && $grade_id > 0 && $client_db->table_exists('erp_textbook_grades')) {
					$g = $client_db->select('name as grade_name')->from('erp_textbook_grades')->where('id', $grade_id)->limit(1)->get()->row_array();
					if (!empty($g['grade_name'])) $vars['grade_name'] = (string)$g['grade_name'];
				}
				if ($vars['board_name'] === '' && $board_id > 0 && $client_db->table_exists('erp_school_boards')) {
					$b = $client_db->select('board_name')->from('erp_school_boards')->where('id', $board_id)->limit(1)->get()->row_array();
					if (!empty($b['board_name'])) $vars['board_name'] = (string)$b['board_name'];
				}

				if ($vars['child_name'] === '') {
					$fn = trim((string)($it['f_name'] ?? ''));
					if ($fn !== '') $vars['child_name'] = $fn;
				}
				break;
			}
		}

		// Uniform / school delivery: if school is still missing, try branch_id/school_id from first item.
		if ($vars['school_name'] === '') {
			foreach ($items as $it) {
				$branch_id = (int)($it['branch_id'] ?? 0);
				$school_id = (int)($it['school_id'] ?? 0);
				if ($school_id_for_board <= 0 && $school_id > 0) $school_id_for_board = $school_id;
				if ($branch_id > 0 && $client_db->table_exists('erp_school_branches')) {
					$br = $client_db->select('sb.branch_name, s.school_name')
						->from('erp_school_branches sb')
						->join('erp_schools s', 's.id = sb.school_id', 'left')
						->where('sb.id', $branch_id)->limit(1)->get()->row_array();
					if (!empty($br['school_name'])) $vars['school_name'] = (string)$br['school_name'];
				} elseif ($school_id > 0 && $client_db->table_exists('erp_schools')) {
					$s = $client_db->select('school_name')->from('erp_schools')->where('id', $school_id)->limit(1)->get()->row_array();
					if (!empty($s['school_name'])) $vars['school_name'] = (string)$s['school_name'];
				}
				if ($vars['school_name'] !== '') break;
			}
		}

		// If board_name is still missing but school_id exists, pick first board mapped to school.
		if ($vars['board_name'] === '' && $school_id_for_board > 0 && $client_db->table_exists('erp_school_boards_mapping') && $client_db->table_exists('erp_school_boards')) {
			$b = $client_db->select('sb.board_name')
				->from('erp_school_boards_mapping sbm')
				->join('erp_school_boards sb', 'sb.id = sbm.board_id', 'left')
				->where('sbm.school_id', $school_id_for_board)
				->limit(1)->get()->row_array();
			if (!empty($b['board_name'])) $vars['board_name'] = (string)$b['board_name'];
		}

		if ($vars['child_name'] === '') {
			foreach ($items as $it) {
				$fn = trim((string)($it['f_name'] ?? ''));
				if ($fn !== '') {
					$vars['child_name'] = $fn;
					if ($vars['child_class'] === '') {
						$vars['child_class'] = (string)($it['grade'] ?? '');
					}
					break;
				}
			}
		}

		return $vars;
	}

	/**
	 * Send order_placed notifications for orders where is_mail_sent=0.
	 * Marks is_mail_sent=1 only when Email was sent successfully.
	 */
	public function send_order_placed_notifications($vendor_id, $limit = 50)
	{
		$vendor_id = (int)$vendor_id;
		$limit = (int)$limit;
		if ($limit <= 0 || $limit > 500) $limit = 50;

		$client_db = $this->getVendorDB($vendor_id);
		if (!$client_db) {
			return ['status' => 'error', 'message' => 'Vendor DB not available', 'processed' => 0];
		}

		// Pull orders eligible for confirmation (match existing Crud_model conditions)
		$rows = $client_db->query(
			"SELECT id, user_name, user_email, user_phone, order_unique_id, order_date, payment_method, payment_status,
			        payable_amt, total_amt, invoice_no, awb_no, courier, is_mail_sent, user_id,
			        delivery_charge, discount_amt, currency_code, currency, children_data, invoice_url
			 FROM tbl_order_details
			 WHERE is_mail_sent = 0
			   AND (payment_status='success' OR payment_status='cod' OR payment_status='payment_at_school' OR payment_method='payment_at_school')
			 ORDER BY id ASC
			 LIMIT ?",
			[$limit]
		)->result_array();

		if (empty($rows)) {
			return ['status' => 'ok', 'processed' => 0, 'email_sent' => 0, 'email_marked' => 0];
		}

		$this->load->library('Notification_sender');

		$processed = 0;
		$email_sent = 0;
		$email_marked = 0;
		$errors = [];

		foreach ($rows as $r) {
			$order_id = (int)($r['id'] ?? 0);
			if ($order_id <= 0) continue;
			$processed++;

			$vars = $this->buildOrderVarsFromRow($r, $order_id);
			
			// Ensure PDF invoice exists for WhatsApp document support if missing
			if (empty($r['invoice_url'])) {
				$this->load->model('App_model');
				$physical_url = $this->App_model->generateUniformOrderInvoice($client_db, $vendor_id, $order_id, $r['order_unique_id'] ?? '');
				if (!empty($physical_url)) {
					$r['invoice_url'] = $physical_url;
				}
			}

			$vars = $this->enrichOrderVars($client_db, $vendor_id, $order_id, $r, $vars);
			$res = $this->notification_sender->sendEvent($vendor_id, 'order_placed', $vars);

			$emailUserOk = !empty($res['results']['email_user']['success']);
			$emailVendorOk = !empty($res['results']['email_vendor']['success']);
			$waOk = !empty($res['results']['whatsapp']['success']);
			$smsOk = !empty($res['results']['sms']['success']);

			$anyOk = ($emailUserOk || $emailVendorOk || $waOk || $smsOk);
			if ($anyOk) {
				$email_sent++;

				$client_db->where('id', $order_id);
				$client_db->update('tbl_order_details', [
					'is_mail_sent' => 1,
					'is_mail_date' => date('Y-m-d H:i:s'),
				]);
				if ($client_db->affected_rows() > 0) {
					$email_marked++;
				}
			} else {
				$errors[] = [
					'order_id' => $order_id,
					'message' => $res['message'] ?? 'Notification not sent',
					'debug' => $res['results'] ?? null,
				];
			}
		}

		return [
			'status' => 'ok',
			'processed' => $processed,
			'email_sent' => $email_sent,
			'email_marked' => $email_marked,
			'errors' => $errors,
		];
	}

	/**
	 * Send order_placed notifications for all active vendors.
	 */
	public function send_order_placed_notifications_all($limit_per_vendor = 50)
	{
		$limit_per_vendor = (int)$limit_per_vendor;
		if ($limit_per_vendor <= 0 || $limit_per_vendor > 500) $limit_per_vendor = 50;

		$vendors = $this->db
			->select('id')
			->from('erp_clients')
			->where('status', 'active')
			->order_by('id', 'asc')
			->get()
			->result_array();

		$vendors_processed = 0;
		$orders_processed = 0;
		$email_sent = 0;
		$email_marked = 0;
		$errors = [];

		foreach ($vendors as $v) {
			$vendor_id = (int)($v['id'] ?? 0);
			if ($vendor_id <= 0) continue;
			$vendors_processed++;

			$res = $this->send_order_placed_notifications($vendor_id, $limit_per_vendor);
			$orders_processed += (int)($res['processed'] ?? 0);
			$email_sent += (int)($res['email_sent'] ?? 0);
			$email_marked += (int)($res['email_marked'] ?? 0);

			if (!empty($res['errors']) && is_array($res['errors'])) {
				foreach ($res['errors'] as $e) {
					$e['vendor_id'] = $vendor_id;
					$errors[] = $e;
				}
			}
		}

		return [
			'status' => 'ok',
			'vendors_processed' => $vendors_processed,
			'orders_processed' => $orders_processed,
			'email_sent' => $email_sent,
			'email_marked' => $email_marked,
			'errors' => $errors,
		];
	}

	/**
	 * Send order_placed notifications for a single vendor database_name.
	 */
	public function send_order_placed_notifications_by_db($database_name, $limit = 50)
	{
		$database_name = trim((string)$database_name);
		if ($database_name === '' || !preg_match('/^[a-zA-Z0-9_]+$/', $database_name)) {
			return ['status' => 'error', 'message' => 'Invalid database key'];
		}

		$vendor = $this->Erp_client_model->getClientByDatabaseName($database_name);
		if (!$vendor || empty($vendor['id'])) {
			return ['status' => 'error', 'message' => 'Vendor not found for database'];
		}

		return $this->send_order_placed_notifications((int)$vendor['id'], $limit);
	}
		
	
	private function getVendorDB($vendor_id)   {
        /* reuse existing connection */
        if (isset($this->client_db[$vendor_id])) {
            return $this->client_db[$vendor_id];
        }

        /* get vendor details */
        $vendor = $this->Erp_client_model->getClientById($vendor_id);

        if (!$vendor) {
            log_message('error', 'Vendor not found for ID: '.$vendor_id);
            return false;
        }

        if (empty($vendor['database_name'])) {
            log_message('error', 'Vendor database name is empty for ID: '.$vendor_id);
            return false;
        }

        /* connect vendor DB directly using CI db config */
        $db_config = $this->_getVendorDbConfig($vendor['database_name']);
        if (!$db_config) {
            log_message('error', 'Failed to build DB config for vendor: '.$vendor_id);
            return false;
        }

        $client_db = $this->load->database($db_config, TRUE);

        if (!$client_db) {
            log_message('error', 'Failed to connect to vendor database: '.$vendor['database_name'].' for vendor: '.$vendor_id);
            return false;
        }

        /* store connection */
        $this->client_db[$vendor_id] = $client_db;

        return $this->client_db[$vendor_id];
    }

    /**
     * Get database configuration for vendor connection
     * Uses master DB credentials but switches to vendor database
     */
    private function _getVendorDbConfig($database_name) {
        // Load CI database.php in local scope to access $active_group and $db array.
        $active_group = null;
        $db = [];
        $db_file = APPPATH . 'config/database.php';
        if (!file_exists($db_file)) {
            log_message('error', 'Database config file not found: ' . $db_file);
            return FALSE;
        }
        require $db_file;

        if (empty($active_group) || !isset($db[$active_group]) || !is_array($db[$active_group])) {
            log_message('error', 'Invalid active_group or DB config in database.php');
            return FALSE;
        }
        $db_config = $db[$active_group];

        if (empty($db_config['hostname']) || empty($db_config['username'])) {
            log_message('error', 'Could not load hostname/username for vendor DB connection');
            return FALSE;
        }

        // Validate database name
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $database_name)) {
            log_message('error', 'Invalid database name: ' . $database_name);
            return FALSE;
        }

        return [
            'dsn'      => '',
            'hostname' => $db_config['hostname'],
            'username' => $db_config['username'],
            'password' => $db_config['password'] ?? '',
            'database' => $database_name,
            'dbdriver' => $db_config['dbdriver'] ?? 'mysqli',
            'dbprefix' => '',
            'pconnect' => FALSE,
            'db_debug' => FALSE,
            'cache_on' => FALSE,
            'char_set' => $db_config['char_set'] ?? 'utf8mb4',
            'dbcollat' => $db_config['dbcollat'] ?? 'utf8mb4_general_ci',
            'swap_pre' => '',
            'encrypt'  => FALSE,
            'compress' => FALSE,
            'stricton' => FALSE,
            'failover' => [],
            'save_queries' => TRUE
        ];
    }
	
	private function callBigshipAPI($method,$endpoint,$token,$payload=null){
		$curl = curl_init();

		curl_setopt_array($curl,[
			CURLOPT_URL => $this->bigship_url.$endpoint,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 60,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => $method,
			CURLOPT_HTTPHEADER => [
				"content-type: application/json",
				"Authorization: Bearer ".$token
			]
		]);	
		if(in_array($method, ['POST','PUT','PATCH'])){
			if($payload){
				curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($payload));
			} else {
				// prevent HTTP 411 error
				curl_setopt($curl,CURLOPT_POSTFIELDS,"");
			}
		}
		
		$response = curl_exec($curl);
		curl_close($curl);
		return json_decode($response);
	}

	private function callShiprocketAPI($method,$endpoint,$token,$payload=null){
		$curl = curl_init();

		curl_setopt_array($curl,[
			CURLOPT_URL => $this->shiprocket_base_url.$endpoint,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 60,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => $method,
			CURLOPT_HTTPHEADER => [
				"content-type: application/json",
				"Authorization: Bearer ".$token
			],
			CURLOPT_SSL_VERIFYPEER => true
		]);	
		if(in_array($method, ['POST','PUT','PATCH'])){
			if($payload){
				curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($payload));
			} else {
				// prevent HTTP 411 error
				curl_setopt($curl,CURLOPT_POSTFIELDS,"");
			}
		}
		
		$response = curl_exec($curl);
		$curl_error = curl_error($curl);
		$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);

		if ($curl_error) {
			log_message('error', "callShiprocketAPI: cURL error for endpoint={$endpoint}: {$curl_error}");
			return (object)['error' => 'curl_error', 'message' => $curl_error, 'status_code' => 0];
		}

		if ($http_code >= 400) {
			log_message('error', "callShiprocketAPI: HTTP error for endpoint={$endpoint}: code={$http_code}, response={$response}");
		}

		$decoded = json_decode($response);
		
		if (json_last_error() !== JSON_ERROR_NONE) {
			log_message('error', "callShiprocketAPI: JSON decode error for endpoint={$endpoint}: " . json_last_error_msg());
			return (object)['error' => 'json_decode_error', 'message' => json_last_error_msg(), 'raw_response' => $response, 'status_code' => $http_code];
		}

		return $decoded;
	}
	
    public function bigship_token($vendor_id) {
	    $client_db = $this->getVendorDB($vendor_id);
		if (!$client_db) return false;
		
		$curr_date = date('Y-m-d H:i:s');
		$update_date = date('Y-m-d H:i:s');
			
		$token=$client_db->get_where('erp_shipping_providers', array('client_id' => $vendor_id,'provider' => 'bigship'))->row_array();
		$token_expiry = date('Y-m-d H:i:s',strtotime($token['token_expiry']));	
		// echo '11'.json_encode($client_db);exit();
		// echo '11'.$client_db->last_query();exit();
		
		if($token_expiry < $curr_date){
			$url = $this->bigship_url ."login/user";
			
			$ip_addr=$this->input->ip_address();
			$data = array(
			  "user_name"  => $token['email'],
			  "password"   => $token['password'],
			  "access_key" => $token['company_id'],
			);
			
			$payload = json_encode($data);        
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "$url",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 60,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_POSTFIELDS =>$payload,
			  CURLOPT_HTTPHEADER => array(
				"cache-control: no-cache",
				"content-type: application/json",
			  ),
			));
			
			$result = curl_exec($curl);
			$api_data = json_decode($result, TRUE);
			//echo json_encode($api_data);exit();  
			if(!empty($api_data)){
				$token_expiry = date('Y-m-d H:i:s', strtotime('+8 hours', strtotime($curr_date)));
				$data_update=array();
				$data_update['token'] 		 = $api_data['data']['token'];
				$data_update['token_expiry'] = $token_expiry;
				$data_update['created_at'] 	 = $curr_date;
				$data_update['last_updated'] = $update_date;
				$client_db->where('id', $token['id']);
				$client_db->update('erp_shipping_providers', $data_update);  
				// echo '11'.$client_db->last_query();exit();
			}
		  }
    }

	private function getBigshipToken($vendor_id){
		$client_db = $this->getVendorDB($vendor_id);
		if (!$client_db) return false;

		$curr_date = date('Y-m-d H:i:s');
		$token = $client_db->get_where('erp_shipping_providers', [
			'client_id' => $vendor_id,
			'provider'  => 'bigship'
		])->row_array();

		if (!$token) return false;

		$token_expiry = strtotime($token['token_expiry']);

		if ($token_expiry > time()) {
			return $token['token'];
		}

		// TOKEN EXPIRED → LOGIN AGAIN
		$url = $this->bigship_url."login/user";

		$payload = json_encode([
			"user_name"  => $token['email'],
			"password"   => $token['password'],
			"access_key" => $token['company_id']
		]);

		$curl = curl_init();

		curl_setopt_array($curl, [
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $payload,
			CURLOPT_HTTPHEADER => [
				"content-type: application/json"
			]
		]);

		$result = curl_exec($curl);
		curl_close($curl);

		$api_data = json_decode($result, true);

		if (!empty($api_data['data']['token'])) {

			$new_token = $api_data['data']['token'];
			$token_expiry = date('Y-m-d H:i:s', strtotime('+8 hours'));

			$client_db->where('id', $token['id'])->update('erp_shipping_providers', [
				'token'        => $new_token,
				'token_expiry' => $token_expiry,
				'last_updated' => $curr_date
			]);

			return $new_token;
		}

		return false;
	}
		
	private function getShiprocketToken($vendor_id){
		$client_db = $this->getVendorDB($vendor_id);
		if (!$client_db) {
			log_message('error', "getShiprocketToken: Failed to get vendor DB for vendor_id={$vendor_id}");
			return false;
		}

		$curr_date = date('Y-m-d H:i:s');
		$token = $client_db->get_where('erp_shipping_providers', [
			'client_id' => $vendor_id,
			'provider'  => 'shiprocket'
		])->row_array();

		if (!$token) {
			log_message('error', "getShiprocketToken: No shiprocket provider config found for vendor_id={$vendor_id}");
			return false;
		}

		// Check if email/password are configured
		if (empty($token['email']) || empty($token['password'])) {
			log_message('error', "getShiprocketToken: Missing email or password in provider config for vendor_id={$vendor_id}, provider_id={$token['id']}");
			return false;
		}

		// Return valid cached token
		if (!empty($token['token']) && !empty($token['token_expiry']) && $token['token_expiry'] > $curr_date) {
			log_message('debug', "getShiprocketToken: Using cached token for vendor_id={$vendor_id}");
			return $token['token'];
		}

		// Need to re-login
		log_message('info', "getShiprocketToken: Token expired or missing, re-authenticating for vendor_id={$vendor_id}");
		
		$url = $this->shiprocket_base_url."auth/login";

		$payload = json_encode([
			"email"    => $token['email'],
			"password" => $token['password']
		]);

		$curl = curl_init();
		curl_setopt_array($curl, [
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $payload,
			CURLOPT_HTTPHEADER => [
				"content-type: application/json"
			],
			CURLOPT_TIMEOUT => 60,
			CURLOPT_SSL_VERIFYPEER => true
		]);
		$result = curl_exec($curl);
		$curl_error = curl_error($curl);
		$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);

		if ($curl_error) {
			log_message('error', "getShiprocketToken: cURL error for vendor_id={$vendor_id}: {$curl_error}");
			return false;
		}

		if ($http_code !== 200) {
			log_message('error', "getShiprocketToken: HTTP error for vendor_id={$vendor_id}: code={$http_code}, response={$result}");
			return false;
		}

		$api_data = json_decode($result, true);

		if (json_last_error() !== JSON_ERROR_NONE) {
			log_message('error', "getShiprocketToken: JSON decode error for vendor_id={$vendor_id}: " . json_last_error_msg());
			return false;
		}

		if (!empty($api_data['token'])) {
			$new_token = $api_data['token'];
			$token_expiry = date('Y-m-d H:i:s', strtotime('+9 days'));

			$client_db->where('id', $token['id'])->update('erp_shipping_providers', [
				'token'        => $new_token,
				'token_expiry' => $token_expiry,
				'last_updated' => $curr_date
			]);

			log_message('info', "getShiprocketToken: Successfully refreshed token for vendor_id={$vendor_id}");
			return $new_token;
		}

		// Log the error response for debugging
		$error_msg = isset($api_data['message']) ? $api_data['message'] : (isset($api_data['error']) ? $api_data['error'] : 'Unknown error');
		log_message('error', "getShiprocketToken: Login failed for vendor_id={$vendor_id}: {$error_msg}");
		return false;
	}
		
	public function bigship_get_courier_rates($vendor_id,$system_order_id){
		$token = $this->getBigshipToken($vendor_id);

		if(!$token){
			return ['status'=>400,'message'=>'Token error'];
		}

		$endpoint = "order/shipping/rates?shipment_category=B2C&system_order_id=".$system_order_id;

		$api_data = $this->callBigshipAPI('GET',$endpoint,$token);

		if($api_data->responseCode==200 && $api_data->success){

			usort($api_data->data,function($a,$b){

				$isA = strpos($a->courier_name,'Delhivery') !== false;
				$isB = strpos($b->courier_name,'Delhivery') !== false;

				if($isA && !$isB) return -1;
				if(!$isA && $isB) return 1;

				return $a->total_shipping_charges <=> $b->total_shipping_charges;
			});

			return [
				'status'=>200,
				'data'=>$api_data->data[0] ?? null
			];
		}

		return ['status'=>400,'message'=>'API error'];
	}
  
	public function bigship_get_balance($vendor_id){
		$token = $this->getBigshipToken($vendor_id);
		$api_data = $this->callBigshipAPI('GET','Wallet/balance/get',$token);

		if($api_data->responseCode==200 && $api_data->success){
			return [
				'status'=>200,
				'balance'=>$api_data->data
			];
		}
		return [
			'status'=>400,
			'balance'=>0
		];
	}
			
    public function bigship_manifest_order($vendor_id, $params){
		$token = $this->getBigshipToken($vendor_id);

		if (!$token) {
			return [
				'status' => 400,
				'message' => 'Token generation failed'
			];
		}

		$endpoint = "order/manifest/single";
		$api_data = $this->callBigshipAPI('POST',$endpoint,$token,$params);

		if (!empty($api_data) && $api_data->responseCode == 200 && $api_data->success == true) {

			return [
				'status' => 200,
				'message' => 'success'
			];

		} else {

			return [
				'status' => 400,
				'message' => isset($api_data->message) ? $api_data->message : 'API Error'
			];
		}
	}
				
    public function bigship_get_awb($vendor_id, $system_order_id){
		$token = $this->getBigshipToken($vendor_id);

		if (!$token) {
			return [
				'status' => 400,
				'message' => 'Token generation failed'
			];
		}

		$endpoint = "shipment/data?shipment_data_id=1&system_order_id=".$system_order_id;
		$api_data = $this->callBigshipAPI('POST',$endpoint,$token);
		
		if (!empty($api_data) && $api_data->responseCode == 200 && $api_data->success == true) {
			$master_awb = isset($api_data->data->master_awb) ? $api_data->data->master_awb : null;
			return [
				'status' => 200,
				'data' => $api_data->data,
				'master_awb' => $master_awb,
				'message' => 'success'
			];

		} else {

			return [
				'status' => 400,
				'message' => isset($api_data->message) ? $api_data->message : 'API Error'
			];
		}
	}
		
	public function bigship_assign_courier($vendor_id)	{
		$client_db = $this->getVendorDB($vendor_id);
		if (!$client_db) return false;

		$curr_date = date('Y-m-d H:i:s');

		$orders = $client_db->query("
			SELECT id, order_unique_id, shipment_id AS system_order_id
			FROM tbl_order_details
			WHERE courier='3rd_party'
			AND courier_name IS NULL
			AND awb_no IS NULL
			AND order_unique_id IS NOT NULL
			AND third_party_provider='bigship'
			AND (payment_status='success' OR payment_status='cod' OR payment_status='payment_at_school')
			AND order_status='2'
			ORDER BY id ASC
			LIMIT 5
		");

		if ($orders->num_rows() == 0) {
			return;
		}

		foreach ($orders->result_array() as $item) {
			$order_id        = $item['id'];
			$order_unique_id = $item['order_unique_id'];
			$system_order_id = $item['system_order_id'];

			$courier_res = $this->bigship_get_courier_rates($vendor_id,$system_order_id);
			$wallet_balance = 0;
		

			if ($courier_res['status'] == 200) {

				$courier = $courier_res['data'];

				$courier_id   = $courier->courier_id;
				$courier_name = $courier->courier_name;
				$shipping_charges = $courier->total_shipping_charges;

				$wallet_res = $this->bigship_get_balance($vendor_id);
				$wallet_balance = $wallet_res['balance'];

				if ($wallet_balance > $shipping_charges) {

					$params = [
						'system_order_id' => $system_order_id,
						'courier_id'      => $courier_id
					];

					$response = $this->bigship_manifest_order($vendor_id,$params);

					if ($response['status'] == 200) {

						$awb_res = $this->bigship_get_awb($vendor_id,$system_order_id);

						$master_awb = NULL;

						if ($awb_res['status'] == 200) {
							$master_awb = $awb_res['master_awb'];
						}

						/* UPDATE ORDER */

						$client_db->where('id',$order_id);
						$client_db->update('tbl_order_details',[
							'awb_no'        => $master_awb,
							'courier_name'  => $courier_name,
						]);
						
						$client_db->where('order_id',$order_id);
						$client_db->update('tbl_order_third_party_shipping',[
							'awb_no'       		 => $master_awb,
							'courier'       	 => $courier_name,
							'courier_charge'     => $shipping_charges,
							'courier_assign_date'=> $curr_date
						]);

						/* CRON TRACK SUCCESS */
						$function_name='manifest_order';
						$json_request=json_encode($item);
						$json_data=json_encode([]);
						$remark='1';

						$track_data = [
							'json_courier' => json_encode($courier_res),
							'wallet'       => $wallet_balance,
							'json_request' => $json_request,
							'json_data'    => $json_data,
							'master_awb'   => $master_awb,
							'remark'       => $remark
						];

						$this->add_cronjob_track($vendor_id,$order_unique_id,$function_name,$track_data);

					} else {

						/* MANIFEST FAILED */

						$function_name='manifest_order';
						$json_request=json_encode($item);
						$json_data=json_encode($response);
						$remark='2';

						$track_data = [
							'json_courier' => json_encode($courier_res),
							'wallet'       => $wallet_balance,
							'json_request' => $json_request,
							'json_data'    => $json_data,
							'master_awb'   => NULL,
							'remark'       => $remark
						];

						$this->add_cronjob_track($vendor_id,$order_unique_id,$function_name,$track_data);
					}

				} else {

					/* WALLET LOW */

					$function_name='manifest_order';
					$json_request=json_encode($item);
					$json_data=json_encode([]);
					$remark='3';

					$track_data = [
						'json_courier' => json_encode($courier_res),
						'wallet'       => $wallet_balance,
						'json_request' => $json_request,
						'json_data'    => $json_data,
						'master_awb'   => NULL,
						'remark'       => $remark
					];

					$this->add_cronjob_track($vendor_id,$order_unique_id,$function_name,$track_data);
				}

			} else {

				/* COURIER NOT FOUND */

				$function_name='manifest_order';
				$json_request=json_encode($item);
				$json_data=json_encode([]);
				$remark='4';

				$track_data = [
					'json_courier' => json_encode($courier_res),
					'wallet'       => $wallet_balance,
					'json_request' => $json_request,
					'json_data'    => $json_data,
					'master_awb'   => NULL,
					'remark'       => $remark
				];

				$this->add_cronjob_track($vendor_id,$order_unique_id,$function_name,$track_data);
			}
		}
	}

	public function bigship_update_failed_awb_courier($vendor_id){
		$client_db = $this->getVendorDB($vendor_id);
		if (!$client_db) return false;

		$curr_date = date('Y-m-d H:i:s');

		$orders = $client_db->query("
			SELECT id, order_unique_id, shipment_id AS system_order_id
			FROM tbl_order_details
			WHERE courier='3rd_party'
			AND courier_name IS NOT NULL
			AND awb_no IS NULL
			AND order_unique_id IS NOT NULL
			AND third_party_provider='bigship'
			AND (payment_status='success' OR payment_status='cod' OR payment_status='payment_at_school')
			AND order_status='2'
			ORDER BY id ASC
			LIMIT 5
		");

		if ($orders->num_rows() == 0) {
			return;
		}

		foreach ($orders->result_array() as $item) {

			$order_id        = $item['id'];
			$order_unique_id = $item['order_unique_id'];
			$system_order_id = $item['system_order_id'];

			$courier_res = [];
			$awb_res = $this->bigship_get_awb($vendor_id,$system_order_id);
		 
			if ($awb_res['status'] == 200) {

				$master_awb = $awb_res['master_awb'];

				/* UPDATE ORDER */

				$client_db->where('id',$order_id);
				$client_db->update('tbl_order_details',[
					'awb_no' => $master_awb
				]);

				$client_db->where('order_id',$order_id);
				$client_db->update('tbl_order_third_party_shipping',[
					'awb_no' => $master_awb
				]);

				/* CRON TRACK SUCCESS */

				$function_name='update_awb_number';
				$json_request=json_encode($item);
				$json_data=json_encode($awb_res);
				$remark='1';

				$track_data = [
					'json_courier' => json_encode($courier_res),
					'wallet'       => 0,
					'json_request' => $json_request,
					'json_data'    => $json_data,
					'master_awb'   => $master_awb,
					'remark'       => $remark
				];

				$this->add_cronjob_track($vendor_id,$order_unique_id,$function_name,$track_data);

			} else {

				/* FAILED TO FETCH AWB */

				$function_name='update_awb_number';
				$json_request=json_encode($item);
				$json_data=json_encode($awb_res);
				$remark='2';

				$track_data = [
					'json_courier' => json_encode($courier_res),
					'wallet'       => 0,
					'json_request' => $json_request,
					'json_data'    => $json_data,
					'master_awb'   => NULL,
					'remark'       => $remark
				];

				$this->add_cronjob_track($vendor_id,$order_unique_id,$function_name,$track_data);
			}
		}
	}

	public function shiprocket_update_failed_awb_courier($vendor_id){
		$client_db = $this->getVendorDB($vendor_id);
		if (!$client_db) {
			log_message('error', "shiprocket_update_failed_awb_courier: Failed to get vendor DB for vendor_id={$vendor_id}");
			return ['status' => 'error', 'reason' => 'db_connection_failed', 'processed' => 0, 'success' => 0, 'failed' => 0];
		}

		$token = $this->getShiprocketToken($vendor_id);
		if (!$token) {
			log_message('error', "shiprocket_update_failed_awb_courier: Failed to get Shiprocket token for vendor_id={$vendor_id}");
			return ['status' => 'error', 'reason' => 'token_failed', 'processed' => 0, 'success' => 0, 'failed' => 0];
		}

		$curr_date = date('Y-m-d H:i:s');

		// shipment_id here is Shiprocket's order_id (from system_order_id)
		$orders = $client_db->query("
			SELECT id, order_unique_id, shipment_id AS system_order_id, user_id
			FROM tbl_order_details
			WHERE courier='3rd_party'
			AND awb_no IS NULL
			AND shipment_id IS NOT NULL
			AND third_party_provider='shiprocket'
			AND (payment_status='success' OR payment_status='cod' OR payment_status='payment_at_school')
			AND order_status='2' AND order_unique_id='ORD260328344'
			ORDER BY id ASC
			LIMIT 5
		");

		if ($orders->num_rows() == 0) {
			log_message('info', "shiprocket_update_failed_awb_courier: No pending orders found for vendor_id={$vendor_id}");
			return ['status' => 'success', 'reason' => 'no_orders', 'processed' => 0, 'success' => 0, 'failed' => 0];
		}

		$processed = 0;
		$success_count = 0;
		$failed_count = 0;

		foreach ($orders->result_array() as $item) {
			$processed++;
			$order_id        = $item['id'];
			$order_unique_id = $item['order_unique_id'];
			$sr_order_id     = $item['system_order_id'];
			$user_id         = $item['user_id'];

			// Initialize tracking data
			$failure_reason = null;
			$api_debug_data = [
				'sr_order_id' => $sr_order_id,
				'orders_show_request' => 'GET orders/show/' . $sr_order_id,
				'orders_show_response' => null,
				'assign_awb_request' => null,
				'assign_awb_response' => null,
				'parsed' => [
					'shipment_id' => null,
					'awb_from_order' => null,
					'awb_from_assign' => null,
					'courier_from_order' => null,
					'courier_from_assign' => null
				]
			];

			// 1. Fetch Order Details from Shiprocket to get shipment_id
			$ord_res = $this->callShiprocketAPI('GET', 'orders/show/'.$sr_order_id, $token);
			$api_debug_data['orders_show_response'] = $ord_res;
			
			$shipment_id = null;
			$awb_code = null;
			$courier_name = null;

			// Hardened parsing for orders/show response - handles both object and array structures
			if (!empty($ord_res)) {
				// Convert to array if it's an object
				$ord_data = is_object($ord_res) ? json_decode(json_encode($ord_res), true) : $ord_res;
				
				// Try to find shipment data in various possible locations
				$shipments = null;
				
			
				if (isset($ord_data['data']['shipments'])) {
					$shipments = $ord_data['data']['shipments'];
				} elseif (isset($ord_data['shipments'])) {
					$shipments = $ord_data['shipments'];
				} elseif (isset($ord_data['data']['shipment'])) {
					$shipments = [$ord_data['data']['shipment']];
				} elseif (isset($ord_data['shipment'])) {
					$shipments = [$ord_data['shipment']];
				}
				
					
				if (!empty($shipments)) {
					// Get first shipment safely for both indexed and associative arrays
					if (is_array($shipments)) {
						
						$first_shipment = reset($shipments);
						$shipment = ($first_shipment !== false) ? $first_shipment : [];
						
						$shipment = $shipments;

					} else {
						$shipment = $shipments;
					}
					$shipment = is_object($shipment) ? json_decode(json_encode($shipment), true) : $shipment;
					if (!is_array($shipment)) {
						$shipment = [];
					}
					
					// Try multiple possible field names for shipment_id
					$shipment_id = $shipment['id'] ?? $shipment['shipment_id'] ?? $shipment['shipmentId'] ?? null;
					$awb_code = $shipment['awb'] ?? $shipment['awb_code'] ?? $shipment['awbCode'] ?? null;
					$courier_name = $shipment['courier'] ?? $shipment['courier_name'] ?? $shipment['courierName'] ?? null;
					
					$api_debug_data['parsed']['shipment_id'] = $shipment_id;
					$api_debug_data['parsed']['awb_from_order'] = $awb_code;
					$api_debug_data['parsed']['courier_from_order'] = $courier_name;
				}
			}

			if (empty($shipment_id)) {
				// Fallback: in some older records, tbl_order_details.shipment_id already stores Shiprocket shipment_id.
				if (!empty($sr_order_id) && ctype_digit((string)$sr_order_id)) {
					$shipment_id = (string)$sr_order_id;
					$api_debug_data['parsed']['shipment_id'] = $shipment_id;
					log_message('info', "shiprocket_update_failed_awb_courier: Using fallback shipment_id from tbl_order_details for order_id={$order_id}, shipment_id={$shipment_id}");
				} else {
					$failure_reason = 'missing_shipment_id';
					log_message('error', "shiprocket_update_failed_awb_courier: No shipment_id found for order_id={$order_id}, sr_order_id={$sr_order_id}");
				}
			}

			// 2. Assign AWB if not already assigned and we have shipment_id
			if (empty($awb_code) && !empty($shipment_id)) {
				$assign_payload = ["shipment_id" => $shipment_id];
				$api_debug_data['assign_awb_request'] = $assign_payload;
				
				$assign_res = $this->callShiprocketAPI('POST', 'courier/assign/awb', $token, $assign_payload);
				$api_debug_data['assign_awb_response'] = $assign_res;
				
				// Hardened parsing for assign/awb response
				if (!empty($assign_res)) {
					$assign_data = is_object($assign_res) ? json_decode(json_encode($assign_res), true) : $assign_res;
					
					// Check for success status (could be in different locations)
					$is_success = false;
					if (isset($assign_data['status']) && $assign_data['status'] == 200) {
						$is_success = true;
					} elseif (isset($assign_data['status_code']) && $assign_data['status_code'] == 200) {
						$is_success = true;
					} elseif (isset($assign_data['success']) && $assign_data['success'] === true) {
						$is_success = true;
					}
					
					if ($is_success) {
						// Try multiple possible field locations for AWB and courier
						$response_data = $assign_data['response']['data'] ?? $assign_data['data'] ?? $assign_data['response'] ?? $assign_data;
						if (is_array($response_data)) {
							$awb_code = $response_data['awb_code'] ?? $response_data['awb'] ?? $response_data['awbCode'] ?? null;
							$courier_name = $response_data['courier_name'] ?? $response_data['courier'] ?? $response_data['courierName'] ?? null;
						}
						
						$api_debug_data['parsed']['awb_from_assign'] = $awb_code;
						$api_debug_data['parsed']['courier_from_assign'] = $courier_name;
					} else {
						// Log the error details
						$error_msg = $assign_data['message'] ?? $assign_data['error'] ?? 'Unknown error from assign/awb';
						log_message('error', "shiprocket_update_failed_awb_courier: AWB assignment failed for order_id={$order_id}, error={$error_msg}");
						$failure_reason = 'assign_awb_failed';
					}
				} else {
					$failure_reason = 'assign_awb_empty_response';
					log_message('error', "shiprocket_update_failed_awb_courier: Empty response from assign/awb for order_id={$order_id}");
				}
			}

			if (!empty($awb_code)) {
				$success_count++;
				/* UPDATE ORDER */
				$order_update = [
					'awb_no' => $awb_code
				];
				if ($client_db->field_exists('courier_name', 'tbl_order_details')) {
					$order_update['courier_name'] = $courier_name;
				}
				$client_db->where('id', $order_id);
				$client_db->update('tbl_order_details', $order_update);

				$client_db->where('order_id', $order_id);
				$client_db->update('tbl_order_third_party_shipping', [
					'awb_no'  => $awb_code,
					'courier' => $courier_name
				]);

				/* CRON TRACK SUCCESS */
				$function_name = 'update_awb_number_shiprocket';
				$track_data = [
					'json_courier' => json_encode(['courier' => $courier_name]),
					'wallet'       => 0,
					'json_request' => json_encode($item),
					'json_data'    => json_encode($api_debug_data),
					'master_awb'   => $awb_code,
					'remark'       => '1'
				];
				$this->add_cronjob_track($vendor_id, $order_unique_id, $function_name, $track_data);
				log_message('info', "shiprocket_update_failed_awb_courier: SUCCESS - order_id={$order_id}, awb={$awb_code}, courier={$courier_name}");
			} else {
				$failed_count++;
				/* CRON TRACK FAILED */
				$function_name = 'update_awb_number_shiprocket';
				
				// Determine specific failure reason if not already set
				if (empty($failure_reason)) {
					if (empty($shipment_id)) {
						$failure_reason = 'missing_shipment_id';
					} else {
						$failure_reason = 'awb_not_generated';
					}
				}
				
				$track_data = [
					'json_courier' => json_encode(['failure_reason' => $failure_reason]),
					'wallet'       => 0,
					'json_request' => json_encode($item),
					'json_data'    => json_encode($api_debug_data),
					'master_awb'   => null,
					'remark'       => '2'
				];
				$this->add_cronjob_track($vendor_id, $order_unique_id, $function_name, $track_data);
				log_message('error', "shiprocket_update_failed_awb_courier: FAILED - order_id={$order_id}, reason={$failure_reason}");
			}
		}

		// Return summary statistics
		return [
			'status'    => ($success_count > 0) ? 'success' : ($failed_count > 0 ? 'partial_failure' : 'no_action'),
			'processed' => $processed,
			'success'   => $success_count,
			'failed'    => $failed_count
		];
	}
	   	   
	public function bigship_tracking($vendor_id){
		$client_db = $this->getVendorDB($vendor_id);
		if (!$client_db) return false;

		$token = $this->getBigshipToken($vendor_id);
		if(!$token) return false;

		$curr_date = date('Y-m-d H:i:s');

		// 3 = Out for delivery
		// 6 = Ready for shipment
		$orders = $client_db->query("
			SELECT id, order_unique_id, awb_no, shipment_id AS system_order_id, track_date, user_id
			FROM tbl_order_details
			WHERE courier='3rd_party'
			AND awb_no IS NOT NULL
			AND third_party_provider='bigship'
			AND order_status IN ('3','6')
			AND (track_date IS NULL OR track_date < DATE_SUB(NOW(),INTERVAL 2 HOUR))
			ORDER BY id ASC
			LIMIT 10
		");

		if ($orders->num_rows() == 0) {
			return;
		}

		foreach ($orders->result_array() as $item){

			$order_id        = $item['id'];
			$order_unique_id = $item['order_unique_id'];
			$tracking_id     = $item['awb_no'];
			$user_id         = $item['user_id'];

			$endpoint = "tracking?tracking_type=awb&tracking_id=".$tracking_id;

			$api_data = $this->callBigshipAPI('GET',$endpoint,$token);

			/* UPDATE TRACK DATE */

			$client_db->where('id',$order_id);
			$client_db->update('tbl_order_details',[
				'track_date'=>$curr_date
			]);

			$function_name = 'tracking_check';
			$remark = '2';

			if(!empty($api_data) && $api_data->success){

				$status = $api_data->data->order_detail->current_tracking_status ?? '';
				$tracking_time = $api_data->data->order_detail->current_tracking_datetime ?? '';

				/* normalize status */
				$status = strtolower(str_replace([' ', '-'], '_', $status));

				if($status == 'out_for_delivery'){
					$ofd_date = date('Y-m-d H:i:s',strtotime($tracking_time));

					$client_db->where('id',$order_id);
					$client_db->update('tbl_order_details',[
						'order_status' => '3',
						'shipment_date' => $ofd_date
					]);

					$client_db->insert('tbl_order_status', [
						'order_id' => $order_id,
						'user_id' => $user_id,
						'product_id' => 0,
						'status_title' => '3',
						'status_desc' => 'Order Out For Delivery',
						'created_at' => $ofd_date
					]);

					$function_name = 'order_out_for_delivery';
					$remark = '1';
				}
				elseif($status == 'delivered'){
					$delivery_date = date('Y-m-d H:i:s',strtotime($tracking_time));

					$client_db->where('id',$order_id);
					$client_db->update('tbl_order_details',[
						'order_status' => '4',
						'delivery_date' => $delivery_date
					]);

					$client_db->insert('tbl_order_status', [
						'order_id' => $order_id,
						'user_id' => $user_id,
						'product_id' => 0,
						'status_title' => '4',
						'status_desc' => 'Order Delivered',
						'created_at' => $delivery_date
					]);

					$function_name = 'order_delivered';
					$remark = '1';

				}
				else{

					$function_name = 'order_in_transit';
					$remark = '1';

				}

			}else{

				if(isset($api_data->responseCode)){
					if($api_data->responseCode == 404){
						$function_name = 'tracking_id_not_found';
					}
					elseif($api_data->responseCode == 202){
						$function_name = 'invalid_tracking_type';
					}
					else{
						$function_name = 'api_error';
					}
				}
				else{
					$function_name = 'invalid_api_response';
				}

			}

			/* CRON LOG */

			$track_data = [
				'json_courier' => NULL,
				'wallet'       => 0,
				'json_request' => json_encode($item),
				'json_data'    => json_encode($api_data),
				'master_awb'   => $tracking_id,
				'remark'       => $remark
			];

			$this->add_cronjob_track($vendor_id,$order_unique_id,$function_name,$track_data);
		}
	}   
	
	public function velocity_tracking($vendor_id){
		$client_db = $this->getVendorDB($vendor_id);
		if (!$client_db) return false;

		$curr_date = date('Y-m-d H:i:s');

		$orders = $client_db->query("
			SELECT id, order_unique_id, awb_no, shipment_id AS system_order_id, track_date, user_id
			FROM tbl_order_details
			WHERE courier='3rd_party'
			AND awb_no IS NOT NULL
			AND third_party_provider='velocity'
			AND order_status IN ('3','6')
			AND (track_date IS NULL OR track_date < DATE_SUB(NOW(), INTERVAL 2 HOUR))
			ORDER BY id ASC
			LIMIT 10");

		if ($orders->num_rows() == 0) {
			return;
		}

		foreach ($orders->result_array() as $item) {

			$order_id        = $item['id'];
			$order_unique_id = $item['order_unique_id'];
			$tracking_id     = $item['awb_no'];
			$user_id         = $item['user_id'];

			$url = $this->velocity_url . "trackAWB/" . $tracking_id;

			$ch = curl_init();
			curl_setopt_array($ch, [
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_TIMEOUT => 30,
			]);

			$response = curl_exec($ch);

			if (curl_errno($ch)) {
				curl_close($ch);
				$api_data = null;
			} else {
				curl_close($ch);
				$api_data = json_decode($response, true);
			}
			
			//echo $response;exit();

			/* UPDATE TRACK DATE */
		    $client_db->where('id', $order_id);
			$client_db->update('tbl_order_details', [
				'track_date' => $curr_date
			]);

			$function_name = 'tracking_check';
			$remark = '2';

			if (!empty($api_data) && is_array($api_data)) {
				$data = $api_data[0] ?? [];

				if (!empty($data['Error'])) {
					$function_name = 'api_error';
				} else {
					$parent = $data['Parent'][0] ?? [];
					$childs = $data['Child'] ?? [];


					if (empty($parent)) {
						$function_name = 'no_parent_data';
					} 
					if (empty($childs)) {
						$function_name = 'no_child_data';
					} else {
						$latest = [];
						$latest = $childs[0] ?? [];

						$status_code = strtolower($latest['Statuscode'] ?? '');
						$status_date = $latest['Statusdate'] ?? '';
						$status_time = $latest['Statustime'] ?? '';

						$tracking_time = (!empty($status_date) && !empty($status_time))
							? date('Y-m-d H:i:s', strtotime($status_date . ' ' . $status_time))
							: $curr_date;

						// OUT FOR DELIVERY
						if ($status_code == 'ofd') {
							$client_db->where('id', $order_id);
							$client_db->update('tbl_order_details', [
								'order_status'  => '3',
								'shipment_date' => $tracking_time
							]);

							// prevent duplicate
							$exists = $client_db->query("
								SELECT id FROM tbl_order_status 
								WHERE order_id = '$order_id' 
								AND status_title = '3'
								LIMIT 1
							")->num_rows();

							if ($exists == 0) {
								$client_db->insert('tbl_order_status', [
									'order_id'    => $order_id,
									'user_id'     => $user_id,
									'product_id'  => 0,
									'status_title'=> '3',
									'status_desc' => 'Order Out For Delivery',
									'created_at'  => $tracking_time
								]);
							}

							$function_name = 'order_out_for_delivery';
							$remark = '1';

						}

						// DELIVERED
						elseif ($status_code == 'spd') {

							$client_db->where('id', $order_id);
							$client_db->update('tbl_order_details', [
								'order_status'  => '4',
								'delivery_date' => $tracking_time
							]);

							// prevent duplicate
							$exists = $client_db->query("
								SELECT id FROM tbl_order_status 
								WHERE order_id = '$order_id' 
								AND status_title = '4'
								LIMIT 1
							")->num_rows();

							if ($exists == 0) {
								$client_db->insert('tbl_order_status', [
									'order_id'    => $order_id,
									'user_id'     => $user_id,
									'product_id'  => 0,
									'status_title'=> '4',
									'status_desc' => 'Order Delivered',
									'created_at'  => $tracking_time
								]);
							}

							$function_name = 'order_delivered';
							$remark = '1';

						}

						// RTO / RETURN
						elseif (in_array($status_code, ['rto','rts','rtd'])) {

							$function_name = 'order_rto';
							$remark = '1';

						}

						// IN TRANSIT
						else {

							$function_name = 'order_in_transit';
							$remark = '1';
						}
					}
				}

			} else {
				$function_name = 'invalid_api_response';
			}

			/* CRON LOG */
			$track_data = [
				'json_courier' => NULL,
				'wallet'       => 0,
				'json_request' => json_encode($item),
				'json_data'    => json_encode($api_data),
				'master_awb'   => $tracking_id,
				'remark'       => $remark
			];

			$this->add_cronjob_track($vendor_id, $order_unique_id, $function_name, $track_data);
		}
	}

	public function shiprocket_tracking($vendor_id){
		$client_db = $this->getVendorDB($vendor_id);
		if (!$client_db) return false;

		$token = $this->getShiprocketToken($vendor_id);
		if(!$token) return false;

		$curr_date = date('Y-m-d H:i:s');

		$orders = $client_db->query(" 
			SELECT id, order_unique_id, shipment_id, awb_no, track_date, user_id
			FROM tbl_order_details
			WHERE courier='3rd_party'
			AND third_party_provider='shiprocket'
			AND shipment_id IS NOT NULL
			AND order_status IN ('3','6')
			AND (track_date IS NULL OR track_date < DATE_SUB(NOW(),INTERVAL 2 HOUR))
			ORDER BY id ASC
			LIMIT 10
		");

		if ($orders->num_rows() == 0) {
			return;
		}

		foreach ($orders->result_array() as $item){

			$order_id        = $item['id'];
			$order_unique_id = $item['order_unique_id'];
			$shipment_id     = trim((string)$item['shipment_id']);
			$user_id         = $item['user_id'];

			if ($shipment_id === '') {
				continue;
			}

			$endpoint = 'courier/track/shipment/' . rawurlencode($shipment_id);
			$api_data = $this->callShiprocketAPI('GET', $endpoint, $token);

			/* UPDATE TRACK DATE */
			$client_db->where('id',$order_id);
			$client_db->update('tbl_order_details',[
				'track_date'=>$curr_date
			]);

			$function_name = 'tracking_check_shiprocket';
			$remark = '2';
			$normalized_status = '';
			$tracking_time = '';

			if (!empty($api_data)) {
				$api_arr = is_object($api_data) ? json_decode(json_encode($api_data), true) : $api_data;

				if (is_array($api_arr)) {
					$trackData = [];

					// Shiprocket may return either:
					// 1) { tracking_data: {...} }
					// 2) { "<shipment_id>": { tracking_data: {...} } }
					if (isset($api_arr['tracking_data']) && is_array($api_arr['tracking_data'])) {
						$trackData = $api_arr['tracking_data'];
					} elseif (isset($api_arr[$shipment_id]['tracking_data']) && is_array($api_arr[$shipment_id]['tracking_data'])) {
						$trackData = $api_arr[$shipment_id]['tracking_data'];
					} else {
						foreach ($api_arr as $root_value) {
							if (is_array($root_value) && isset($root_value['tracking_data']) && is_array($root_value['tracking_data'])) {
								$trackData = $root_value['tracking_data'];
								break;
							}
						}
					}

					$shipmentTrack = [];
					$activities = [];
					$track_status = isset($trackData['track_status']) ? (string)$trackData['track_status'] : '';
					$track_error = isset($trackData['error']) ? trim((string)$trackData['error']) : '';

					if (isset($trackData['shipment_track']) && is_array($trackData['shipment_track']) && !empty($trackData['shipment_track'])) {
						$shipmentTrack = $trackData['shipment_track'][0];
					}

					if (isset($trackData['shipment_track_activities']) && is_array($trackData['shipment_track_activities']) && !empty($trackData['shipment_track_activities'])) {
						$activities = $trackData['shipment_track_activities'][0];
					}

					$status_candidates = [
						$shipmentTrack['current_status'] ?? '',
						$shipmentTrack['shipment_status'] ?? '',
						$activities['activity'] ?? '',
						$activities['status'] ?? ''
					];

					foreach ($status_candidates as $candidate) {
						if (!empty($candidate)) {
							$normalized_status = strtolower(str_replace([' ', '-'], '_', trim((string)$candidate)));
							break;
						}
					}

					$time_candidates = [
						$activities['date'] ?? '',
						$shipmentTrack['edd'] ?? ''
					];

					foreach ($time_candidates as $candidate_time) {
						if (!empty($candidate_time)) {
							$ts = strtotime($candidate_time);
							if ($ts) {
								$tracking_time = date('Y-m-d H:i:s', $ts);
								break;
							}
						}
					}

					if (in_array($normalized_status, ['out_for_delivery','ofd'])) {
						$ofd_date = !empty($tracking_time) ? $tracking_time : $curr_date;

						$client_db->where('id',$order_id);
						$client_db->update('tbl_order_details',[
							'order_status' => '3',
							'shipment_date' => $ofd_date
						]);

						$exists = $client_db->query("SELECT id FROM tbl_order_status WHERE order_id = '{$order_id}' AND status_title = '3' LIMIT 1")->num_rows();
						if ($exists == 0) {
							$client_db->insert('tbl_order_status', [
								'order_id' => $order_id,
								'user_id' => $user_id,
								'product_id' => 0,
								'status_title' => '3',
								'status_desc' => 'Order Out For Delivery',
								'created_at' => $ofd_date
							]);
						}

						$function_name = 'order_out_for_delivery_shiprocket';
						$remark = '1';
					} elseif (in_array($normalized_status, ['delivered','delivered_to_consignee','dlv'])) {
						$delivery_date = !empty($tracking_time) ? $tracking_time : $curr_date;

						$client_db->where('id',$order_id);
						$client_db->update('tbl_order_details',[
							'order_status' => '4',
							'delivery_date' => $delivery_date
						]);

						$exists = $client_db->query("SELECT id FROM tbl_order_status WHERE order_id = '{$order_id}' AND status_title = '4' LIMIT 1")->num_rows();
						if ($exists == 0) {
							$client_db->insert('tbl_order_status', [
								'order_id' => $order_id,
								'user_id' => $user_id,
								'product_id' => 0,
								'status_title' => '4',
								'status_desc' => 'Order Delivered',
								'created_at' => $delivery_date
							]);
						}

						$function_name = 'order_delivered_shiprocket';
						$remark = '1';
					} elseif (!empty($normalized_status)) {
						$function_name = 'order_in_transit_shiprocket';
						$remark = '1';
					} elseif ($track_status === '0' || stripos($track_error, 'no activities found') !== false) {
						// Keep polling later: tracking exists but no movement events yet.
						$function_name = 'tracking_pending_shiprocket';
						$remark = '1';
					}
				} else {
					$function_name = 'invalid_api_response';
				}
			} else {
				$function_name = 'invalid_api_response';
			}

			/* CRON LOG */
			$track_data = [
				'json_courier' => NULL,
				'wallet'       => 0,
				'json_request' => json_encode($item),
				'json_data'    => json_encode($api_data),
				'master_awb'   => !empty($item['awb_no']) ? $item['awb_no'] : $shipment_id,
				'remark'       => $remark
			];

			$this->add_cronjob_track($vendor_id,$order_unique_id,$function_name,$track_data);
		}
	}


	
	
	   	   
	public function add_cronjob_track($vendor_id,$order_unique_id,$function_name,$params){
		$client_db = $this->getVendorDB($vendor_id);
		if (!$client_db) return false;
		if (!$client_db->table_exists('shipping_track')) {
			log_message('error', "add_cronjob_track: table shipping_track missing for vendor_id={$vendor_id}");
			return false;
		}

		$added_date = date('Y-m-d H:i:s');

		$data = [
			'order_slot'    => $order_unique_id,
			'function_name' => $function_name,
			'json_courier'  => $params['json_courier'],
			'wallet'        => $params['wallet'],
			'json_request'  => $params['json_request'],
			'json_data'     => $params['json_data'],
			'master_awb'    => $params['master_awb'],
			'remark'        => $params['remark'],
			'created_date'  => $added_date
		];

		$client_db->insert('shipping_track', $data);
	}
	
	public function get_shiprocket_token($vendor_id = null)
	{
		if (empty($vendor_id) || !is_numeric($vendor_id)) {
			log_message('error', 'get_shiprocket_token: Invalid vendor_id');
			return false;
		}

		// Use vendor client DB provider config (erp_shipping_providers).
		return $this->getShiprocketToken((int)$vendor_id);
	}

}