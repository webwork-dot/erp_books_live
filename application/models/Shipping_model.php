<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shipping_model extends CI_Model { 
   private $bigship_url = BIGSHIP_URL;
   private $velocity_url = VELOCITY_URL;
   private $shiprocket_base_url = 'https://apiv2.shiprocket.in/v1/external/';
   
    function __construct(){
        parent::__construct();
        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
		date_default_timezone_set('Asia/Kolkata');
    }
	
    public function bigship_token($vendor_id) {
		$this->load->model('Erp_client_model');
		$this->load->model('Vendor_sync_model');
		$vendor = $this->Erp_client_model->getClientById($vendor_id);
		if (!$vendor) {
			log_message('error', 'Vendor not found for shipping sync.');
			return false;
		}
			
		/* ==========================================================
		 * CONNECT USING CI MULTI DATABASE
		 * ========================================================== */
		$client_db = $this->Vendor_sync_model->getVendorDbConnection($vendor['database_name']);

		if (!$client_db) {
			log_message('error', 'Client DB connection failed.');
			return false;
		}	
			
		
		date_default_timezone_set('Asia/Kolkata');
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

	public function get_bigship_warehouses($client_id){
		// Get token
		$row = $this->db->select('token')
			->from('erp_shipping_providers')
			->where([
				'client_id' => $client_id,
				'provider'  => 'bigship',
				'status'    => 1
			])
			->get()
			->row();

		if (!$row || empty($row->token)) {
			return [
				'status' => false,
				'message' => 'Bigship not configured.'
			];
		}

		$ch = curl_init($this->bigship_url . 'warehouse/get/list?page_index=1&page_size=50');
		curl_setopt_array($ch, [
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => [
				'Authorization: Bearer ' . trim($row->token),
				'Content-Type: application/json'
			]
		]);

		$result = curl_exec($ch);
		curl_close($ch);

		$res = json_decode($result, true);

		if (empty($res['success']) || empty($res['data']['result_data'])) {
			return [
				'status' => false,
				'message' => 'No warehouse found.'
			];
		}

		return [
			'status' => true,
			'data'   => $res['data']['result_data']
		];
	}
 
    public function create_velocity_booking($data){
		try {
	 
			
			$provider = $data['provider'];
			$order    = $data['order_data'];
			$address  = $data['address_row'];
			$product_details = $data['product_details'];

			if (!$provider) {
				return [
					'status'  => 'error',
					'message' => 'Velocity provider configuration not found.'
				];
			}
		
			// ===============================
			// PREVENT DUPLICATE BOOKING
			// ===============================
			if (!empty($order->shipment_id)) {
				return [
					'status'  => 'error',
					'message' => 'Shipment already created for this order.'
				];
			}

			// ===============================
			// BUILD PIECES ARRAY
			// ===============================
			$pieces = [[
				"weight"  => (float) $data['weight'],
				"length"  => (float) $data['length'],
				"breadth" => (float) $data['breadth'],
				"height"  => (float) $data['height']
			]];

		   
			// ===============================
			// FIX DROP PINCODE (Fallback From Address)
			// ===============================
			$drop_pincode = $address->pincode ?? '';

			if (empty($drop_pincode) && !empty($address->address)) {
				if (preg_match('/\b\d{6}\b/', $address->address, $matches)) {
					$drop_pincode = $matches[0];
				}
			}	
			
			
			if (empty($drop_pincode)) {
				return [
					'status'  => 'error',
					'message' => 'Delivery pincode not found.'
				];
			}

			// check from client DB
			$serviceable = $this->db->select('id')->from('velocity_pincode')->where('pincode', $drop_pincode)->where('ecommerce', 'Y')->limit(1)->get()->row_array();
			if (!$serviceable) {
				return [
					'status'  => 'error',
					'message' => 'Velocity does not service this pincode in ' . $drop_pincode
				];
			}
			
			$description_items = [];
			$counter = 1;
			$maxLength = 255;
			foreach ($product_details as $item) {
				$line = $counter . ') ' .
						$item['product_name'] .
						' (Qty: ' . $item['product_quantity'] . ')';

				$tempString = implode(', ', $description_items);
				if (strlen($tempString . ', ' . $line) > $maxLength) {
					$description_items[] = '...';
					break;
				}

				$description_items[] = $line;
				$counter++;
			}
			$combined_description = implode(', ', $description_items);
					
			$schedule_date = date("d-m-Y", strtotime($data['schedule_date']));
			$from_Time     = date("H:i:s", strtotime($data['from_time']));
			$to_Time       = date("H:i:s", strtotime($data['to_time']));		 
			$name = sanitizeString($order->user_name); 
				
			$payload = [[
				"username"            => $provider->email,
				"password"            => $provider->password,
				"accno"               => $provider->company_id,
				"secret_code"         => $provider->channel_id,

				"CustomerName"        => $provider->name,
				"serviceType"         => "VELOFREIGHT",
				"Product_Description" => $combined_description,
				"Order_ID"            => $order->order_unique_id,

				"pieces"              => $pieces,

				// ================= DELIVERY =================
				"drop_City"        => $address->city ?? '',
				"drop_State"       => $address->state ?? '',
				"drop_Address"     => $address->address ?? '',
				"drop_Landmark"    => '',
				"drop_Pincode"     => $drop_pincode ?? '',
				"drop_Phoneno"     => $address->phone ?? $order->user_phone ?? '',
				"drop_Alt_Phoneno" => '',
				"drop_Name"        => clean($address->name) ?? clean($order->user_name) ?? '',
				"drop_Emailid"     => $address->email ?? $order->user_email ?? '',

				// ================= PICKUP =================
				"pickup_City"       => $provider->pickup_city,
				"pickup_State"      => $provider->pickup_state,
				"pickup_Address"    => $provider->pickup_address,
				"pickup_Landmark"   => $provider->pickup_landmark,
				"pickup_Pincode"    => $provider->pickup_pincode,
				"pickup_Phoneno"    => $provider->pickup_phoneno,
				"pickup_Alt_Phoneno"=> $provider->pickup_alt_phoneno,
				"pickup_Name"       => $provider->pickup_name,
				"pickup_Emailid"    => $provider->pickup_emailid,

				"schedule_date" => $schedule_date,
				"from_Time"     => $from_Time,
				"to_Time"       => $to_Time,

				"Shipment_value" => (float) $order->payment_amount,
				//"cod_amount"     => (strtolower($order->payment_method) == 'cod')? (float) $order->payment_amount: 0,
				//"payment_mode" => (strtolower($order->payment_method) == 'cod')? "COD": "PAID",
				"cod_amount"     => 0,
				"payment_mode"   => "PAID",

				"send_otp" => false,

				"RTO_vendorname"      => "",
				"RTO_vendoraddress"   => "",
				"RTO_vendorpincode"   => "",
				"RTO_vendorcontactno" => ""
			]];
			
			/*echo json_encode([
				'debug' => $payload,
				'csrf'  => [
					'name' => $this->security->get_csrf_token_name(),
					'hash' => $this->security->get_csrf_hash()
				]
			]);
			exit;*/

			// ===============================
			// CURL CALL
			// ===============================
			$ch = curl_init($this->velocity_url.'corporate-bulk-booking/');

			curl_setopt_array($ch, [
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POST           => true,
				CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
				CURLOPT_POSTFIELDS     => json_encode($payload),
				CURLOPT_TIMEOUT        => 30
			]);

			$result    = curl_exec($ch);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$curl_err  = curl_error($ch);
			curl_close($ch);
			
			if ($curl_err) {
				return ['status'=>'error','message'=>'Curl Error: '.$curl_err];
			}

			if ($http_code != 200) {
				return ['status'=>'error','message'=>'Velocity API HTTP Error: '.$http_code];
			}

			$response = json_decode($result, true);

			if (!is_array($response) || empty($response[0])) {
				return ['status'=>'error','message'=>'Invalid response from Velocity.'];
			}
			

			$response = json_decode($result, true);

			/*echo json_encode([
				'debug' => $result,
				'debug2' => $response[0]['status'],
				'csrf'  => [
					'name' => $this->security->get_csrf_token_name(),
					'hash' => $this->security->get_csrf_hash()
				]
			]);
			exit;*/

			 // ===============================
			// HANDLE ERROR RESPONSE
			// ===============================
			if (strtolower($response[0]['status']) !== 'success') {

				$reason = 'Velocity booking failed';

				if (!empty($response[0]['message'])) {

					if (is_array($response[0]['message'])) {
						$reason = implode(', ', $response[0]['message']);
					} else {
						$reason = $response[0]['message'];
					}

				}

				return [
					'status'  => 'error',
					'message' => $reason
				];
			}
				
			  // ===============================
			// SUCCESS RESPONSE
			// ===============================
			$messageBlock = $response[0]['message'] ?? [];

			if (!is_array($messageBlock)) {
				return ['status'=>'error','message'=>'Invalid success response format.'];
			}

			$awb_number = $messageBlock['AWBNumber'] ?? null;

			if (empty($awb_number)) {
				return ['status'=>'error','message'=>'AWB not generated by Velocity.'];
			}

			$system_order_id = $awb_number;						
			 // ===============================
			// RETURN SUCCESS (NO DB UPDATE HERE)
			// ===============================
			return [
				'status'            => 'success',
				'awb_no'            => $awb_number,
				'system_order_id'   => $awb_number,
				'track_url'  		 => '',
				'provider_request'  => json_encode($payload),
				'provider_response' => $result
			];

		} catch (Exception $e) {
			return [
				'status'  => 'error',
				'message' => $e->getMessage()
			];
		}
	}

    public function create_bigship_booking($data){
		 try {
			$provider = $data['provider'];
			$order    = $data['order_data'];
			$address  = $data['address_row'];

			if (!$provider || empty($provider->token)) {
				return [
					'status'  => 'error',
					'message' => 'Bigship provider configuration not found.'
				];
			}

			$order_id = $order->id;
			$order_date = $order->order_date;
			$pickup_address_id = $data['pickup_address_id'];
			$product_details = $data['product_details'];

			// ===============================
			// PREVENT DUPLICATE BOOKING
			// ===============================
			if (!empty($order->shipment_id)) {
				return [
					'status'  => 'error',
					'message' => 'Shipment already created for this order.'
				];
			}

			// ===============================
			// BUILD PAYLOAD
			// ===============================
			$weight = (float) $data['weight'] > 0 ? (float)$data['weight'] : 0.5;
			
				$address_line_1 = sanitizeAddress($address->address);
				$address_length = strlen($address_line_1);

				$address1 = $address2 = $address_landmark = '';

				if ($address_length < 10) {
					$address_line_1 = str_pad($address_line_1, 10, ' ');
				}

				if ($address_length > 50) {
					$chunkSize = 50;
					$address1 = substr($address_line_1, 0, $chunkSize);
					$remaining = substr($address_line_1, $chunkSize);

					$lastSpace = strrpos($address1, ' ');
					if ($lastSpace !== false) {
						$address1 = substr($address_line_1, 0, $lastSpace);
						$address2 = substr($address_line_1, $lastSpace + 1);
					} else {
						$address2 = $remaining;
					}

					if (strlen($address2) > 50) {
						$lastSpaceAddress2 = strrpos(substr($address2, 0, 50), ' ');
						if ($lastSpaceAddress2 !== false) {
							$address_landmark = substr($address2, $lastSpaceAddress2 + 1);
							$address2 = substr($address2, 0, $lastSpaceAddress2);
						} else {
							$address_landmark = substr($address2, 50);
							$address2 = substr($address2, 0, 50);
						}
					}

					if (strlen($address_landmark) > 50) {
						$address_landmark = substr($address_landmark, 0, 50);
					}
				} else {
					$address1 = $address_line_1;
					$address2 = '';
					$address_landmark = '';
				}
			
			
			$name_ = sanitizeString($order->user_name); 
			$name_parts = explode(' ', trim($name_), 2);

			$first_name = $name_parts[0] ?? '';
			if (strlen($first_name) < 3) {
				$first_name = str_pad($first_name, 3, '.'); 
			} elseif (strlen($first_name) > 25) {
				$first_name = substr($first_name, 0, 25); 
			}

			$last_name = $name_parts[1] ?? '...'; 
			if (strlen($last_name) < 3) {
				$last_name = str_pad($last_name, 3, '.'); 
			} elseif (strlen($last_name) > 25) {
				$last_name = substr($last_name, 0, 25); 
			}
			
			
			$drop_pincode = $address->pincode ?? '';

			if (empty($drop_pincode) && !empty($address->address)) {
				if (preg_match('/\b\d{6}\b/', $address->address, $matches)) {
					$drop_pincode = $matches[0];
				}
			}	

			$payload = [ 
				"shipment_category" => "b2c",
				"warehouse_detail" => [
					"pickup_location_id" =>  (int)$pickup_address_id,
					"return_location_id" =>  (int)$pickup_address_id
				],
				"consignee_detail" => [
					"first_name" => $first_name?? 'NA',
					"last_name"  => $last_name?? 'NA',
					"company_name" => "",
					"contact_number_primary" => $order->user_phone,
					"contact_number_secondary" => '',
					"email_id" => $order->user_email,
					"consignee_address" => [
						"address_line1" => $address1,
						"address_line2" => $address2,
						"address_landmark" => $address_landmark,
						"pincode" => $drop_pincode ?? ''
					]
				],
				"order_detail" => [
					"invoice_date" =>  date("Y-m-d\TH:i:s", strtotime($order_date)),
					"invoice_id" => $order->order_unique_id,
					//"payment_type" => strtolower($order->payment_method) == 'cod' ? "COD" : "Prepaid",
					"payment_type" => "Prepaid",
					"shipment_invoice_amount" => (float)$order->payment_amount,
					//"total_collectable_amount" => strtolower($order->payment_method) == 'cod'? (float)$order->payment_amount: 0,
					"total_collectable_amount" => 0,
					"box_details" => [[
						"each_box_dead_weight" => $weight,
						"each_box_length" => (float)$data['length'],
						"each_box_width"  => (float)$data['breadth'],
						"each_box_height" => (float)$data['height'],
						"each_box_invoice_amount" => (float)$order->payment_amount,
						"each_box_collectable_amount" => 0,
						"box_count" => 1,
						"product_details" => $product_details
					]], 
					"ewaybill_number" => "",
					"document_detail" => [
						"invoice_document_file" => "",
						"ewaybill_document_file" => ""
					],
				]
			];
			/*	echo json_encode([
				'debug' => $payload,
				'csrf'  => [
					'name' => $this->security->get_csrf_token_name(),
					'hash' => $this->security->get_csrf_hash()
				]
			]);
			exit;*/
			// ===============================
			// CALL BIGSHIP API
			// ===============================
			$ch = curl_init($this->bigship_url.'order/add/single');

			curl_setopt_array($ch, [
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POST => true,
				CURLOPT_HTTPHEADER => [
					'Content-Type: application/json',
					'Authorization: Bearer ' . trim($provider->token)
				],
				CURLOPT_POSTFIELDS => json_encode($payload),
				CURLOPT_TIMEOUT => 30
			]);

			$result    = curl_exec($ch);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$curl_err  = curl_error($ch);
			curl_close($ch);

			$response = json_decode($result, true);
			/*echo json_encode([
				'debug' => $result,
				'csrf'  => [
					'name' => $this->security->get_csrf_token_name(),
					'hash' => $this->security->get_csrf_hash()
				]
			]);
			exit;*/
			

			// ===============================
			// HANDLE CURL ERROR
			// ===============================
			if ($curl_err) {
				return [
					'status'  => 'error',
					'message' => $curl_err
				];
			}

			// ===============================
			// HANDLE HTTP ERROR
			// ===============================
			if ($http_code != 200) {

				$error_message = 'Bigship API Error';

				if (!empty($response['errors'])) {
					$error_message = json_encode($response['errors']);
				} elseif (!empty($response['message'])) {
					$error_message = $response['message'];
				}

				return [
					'status'  => 'error',
					'message' => $error_message
				];
			}

			// ===============================
			// SUCCESS RESPONSE CHECK
			// ===============================
			if (empty($response['success']) || $response['success'] !== true) {
				return [
					'status'  => 'error',
					'message' => $response['message'] ?? 'Bigship booking failed'
				];
			}

			// ===============================
			// EXTRACT DATA
			// ===============================	
			$system_order_id = null;
			$awb_number      = null;
			$track_url       = null;

			if (!empty($response['data'])) {

				// CASE 1: String response (Most common)
				if (is_string($response['data'])) {

					if (strpos($response['data'], 'system_order_id is') !== false) {

						$parts = explode('system_order_id is', $response['data']);
						$system_order_id = isset($parts[1]) ? trim($parts[1]) : null;
					}
				}

				// CASE 2: If Bigship later returns structured object
				elseif (is_array($response['data'])) {

					$system_order_id = $response['data']['system_order_id'] ?? null;
					$awb_number      = $response['data']['awb_number'] ?? null;
					$track_url       = $response['data']['tracking_url'] ?? null;
				}
			}
			
			
			
			// ===============================
			// RETURN SUCCESS (NO DB UPDATE HERE)
			// ===============================
			return [
				'status'            => 'success',
				'awb_no'            => $awb_number,
				'system_order_id'   => $system_order_id,
				'track_url'  		 => $track_url,
				'provider_request'  => json_encode($payload),
				'provider_response' => $result
			];	

		} catch (Exception $e) {

			return [
				'status'  => 'error',
				'message' => $e->getMessage()
			];
		}
	}

	/**
	 * Get or refresh Shiprocket token; stores in erp_shipping_providers
	 * @param object $provider_row Row from erp_shipping_providers (provider=shiprocket)
	 * @return string|false Token or false on failure
	 */
	public function get_shiprocket_token($provider_row) {
		if (!$provider_row || empty($provider_row->email) || empty($provider_row->password)) {
			return false;
		}
		$now = date('Y-m-d H:i:s');
		if (!empty($provider_row->token) && !empty($provider_row->token_expiry) && $provider_row->token_expiry > $now) {
			return trim($provider_row->token);
		}
		$payload = json_encode([
			'email'    => $provider_row->email,
			'password' => $provider_row->password,
		]);
		$ch = curl_init();
		curl_setopt_array($ch, [
			CURLOPT_URL            => $this->shiprocket_base_url . 'auth/login',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING       => '',
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_TIMEOUT        => 60,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST  => 'POST',
			CURLOPT_POSTFIELDS     => $payload,
			CURLOPT_HTTPHEADER     => [
				'Content-Type: application/json',
				'Cache-Control: no-cache',
			],
		]);
		$result = curl_exec($ch);
		$curlErr = curl_error($ch);
		curl_close($ch);
		if ($curlErr) {
			return false;
		}
		$apiData = json_decode($result, true);
		if (empty($apiData['token'])) {
			return false;
		}
		$token = trim($apiData['token']);
		$expiry = date('Y-m-d H:i:s', strtotime('+9 days'));
		$this->db->where('id', $provider_row->id)
			->update('erp_shipping_providers', [
				'token'       => $token,
				'token_expiry'=> $expiry,
				'last_updated'=> date('Y-m-d H:i:s')
			]);
		return $token;
	}

	/**
	 * Fetch default pickup location name from Shiprocket; uses provider->pickup_name if set
	 */
	private function _shiprocket_pickup_location($provider, $token) {
		if (!empty($provider->pickup_name)) {
			return trim($provider->pickup_name);
		}
		$ch = curl_init($this->shiprocket_base_url . 'settings/company/pickup');
		curl_setopt_array($ch, [
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => [
				'Content-Type: application/json',
				'Authorization: Bearer ' . $token,
			],
			CURLOPT_TIMEOUT => 30
		]);
		$result = curl_exec($ch);
		curl_close($ch);
		$data = json_decode($result, true);
		if (!is_array($data)) {
			return null;
		}
		// Shiprocket: shipping_address array at top level; each item has pickup_location
		if (isset($data['shipping_address']) && is_array($data['shipping_address']) && !empty($data['shipping_address'])) {
			$first = reset($data['shipping_address']);
			if (!empty($first['pickup_location'])) {
				return trim($first['pickup_location']);
			}
		}
		$pickupData = $data['data'] ?? $data;
		if (isset($pickupData['shipping_address']) && is_array($pickupData['shipping_address']) && !empty($pickupData['shipping_address'])) {
			$first = reset($pickupData['shipping_address']);
			if (!empty($first['pickup_location'])) {
				return trim($first['pickup_location']);
			}
		}
		if (isset($pickupData['address']) && is_array($pickupData['address'])) {
			$first = reset($pickupData['address']);
			return isset($first['pickup_location']) ? trim($first['pickup_location']) : (isset($first['name']) ? trim($first['name']) : null);
		}
		if (isset($pickupData['pickup_addresses']) && is_array($pickupData['pickup_addresses'])) {
			$first = reset($pickupData['pickup_addresses']);
			return isset($first['pickup_location']) ? trim($first['pickup_location']) : (isset($first['name']) ? trim($first['name']) : null);
		}
		if (isset($pickupData['pickup_location'])) {
			return trim($pickupData['pickup_location']);
		}
		if (isset($pickupData['name'])) {
			return trim($pickupData['name']);
		}
		return null;
	}

	/**
	 * Create Shiprocket adhoc order; same interface as create_bigship_booking
	 */
	public function create_shiprocket_booking($data) {
		try {
			$provider = $data['provider'];
			$order    = $data['order_data'];
			$address  = $data['address_row'];
			$product_details = $data['product_details'] ?? [];

			if (!$provider || empty($provider->email) || empty($provider->password)) {
				return [
					'status'  => 'error',
					'message' => 'Shiprocket provider configuration not found.'
				];
			}

			if (!empty($order->shipment_id)) {
				return [
					'status'  => 'error',
					'message' => 'Shipment already created for this order.'
				];
			}

			$token = $this->get_shiprocket_token($provider);
			if (!$token) {
				return [
					'status'  => 'error',
					'message' => 'Shiprocket login failed. Check email/password.'
				];
			}

			$pickup_location = null;
			if (!empty($data['pickup_address_id']) && is_string($data['pickup_address_id'])) {
				$pickup_location = trim($data['pickup_address_id']);
			}
			if (empty($pickup_location)) {
				$pickup_location = $this->_shiprocket_pickup_location($provider, $token);
			}
			if (empty($pickup_location)) {
				return [
					'status'  => 'error',
					'message' => 'Could not determine Shiprocket pickup location. Add pickup in Shiprocket dashboard or set pickup name in vendor config.'
				];
			}

			$weight = (float) ($data['weight'] ?? 0);
			if ($weight <= 0) {
				$weight = 0.5;
			}
			$length = max(0.5, (float) ($data['length'] ?? 10));
			$breadth = max(0.5, (float) ($data['breadth'] ?? 10));
			$height = max(0.5, (float) ($data['height'] ?? 10));

			$name_ = isset($address->name) ? $address->name : ($order->user_name ?? 'Customer');
			$name_parts = explode(' ', trim($name_), 2);
			$billing_first = trim($name_parts[0] ?? 'Customer') ?: 'Customer';
			$billing_last  = trim($name_parts[1] ?? '') ?: $billing_first;
			$billing_city  = trim($address->city ?? '') ?: 'NA';
			$billing_state = trim($address->state ?? '') ?: 'NA';
			$billing_pincode = trim($address->pincode ?? $address->billing_pincode ?? '');
			if (empty($billing_pincode)) {
				$addr_text = trim($address->address ?? $address->billing_address ?? '');
				if (preg_match('/\b\d{6}\b/', $addr_text, $m)) {
					$billing_pincode = $m[0];
				}
			}
			if (empty($billing_pincode) && !empty($address->landmark) && preg_match('/\b\d{6}\b/', $address->landmark, $m)) {
				$billing_pincode = $m[0];
			}
			if (empty($billing_pincode)) {
				return [
					'status'  => 'error',
					'message' => 'Delivery pincode not found. Please add pincode in order delivery address (Order #' . ($order->order_unique_id ?? $order->id) . ').'
				];
			}
			$billing_country = trim($address->country ?? '') ?: 'India';
			$billing_email   = trim($address->email ?? $order->user_email ?? '') ?: 'noreply@example.com';
			$billing_phone_raw = $address->mobile_no ?? $order->user_phone ?? '';
			$billing_phone   = (int) preg_replace('/\D/', '', $billing_phone_raw) ?: 9999999999;
			$billing_address = trim($address->address ?? $address->billing_address ?? '') ?: 'Address not provided';
			$billing_address = substr(trim(preg_replace('/\s+/', ' ', $billing_address)), 0, 190);
			if (strlen($billing_address) < 3) {
				$billing_address = 'Delivery Address';
			}

			$payment_method = 'Prepaid';
			if (!empty($order->payment_method) && strtolower($order->payment_method) === 'cod') {
				$payment_method = 'COD';
			}

			$order_total = (float) ($order->payment_amount ?? 0);
			$item_names = [];
			foreach ($product_details as $item) {
				$item_names[] = isset($item['product_name']) ? $item['product_name'] : 'Product';
			}
			$item_desc = !empty($item_names) ? implode(', ', array_slice($item_names, 0, 3)) : 'Order';
			if (count($item_names) > 3) {
				$item_desc .= ' +' . (count($item_names) - 3) . ' more';
			}
			$item_desc = substr($item_desc, 0, 100);
			$sub_total = $order_total > 0 ? (int) ceil($order_total) : 100;

			$order_items = [[
				'name'          => $item_desc,
				'sku'           => 'PKG-' . ($order->order_unique_id ?? $order->id),
				'units'         => 1,
				'selling_price' => $sub_total
			]];

			$order_id_str = trim($order->order_unique_id ?? ('ORD-' . $order->id));
			$order_date_str = date('Y-m-d H:i', strtotime($order->order_date ?? 'now'));

			$billing_pincode_int = (int) preg_replace('/\D/', '', $billing_pincode) ?: 110001;

			$payload = [
				'order_id'             => $order_id_str,
				'order_date'           => $order_date_str,
				'pickup_location'      => $pickup_location,
				'billing_customer_name'=> substr($billing_first, 0, 50),
				'billing_last_name'    => substr($billing_last, 0, 50),
				'billing_address'      => $billing_address,
				'billing_city'         => substr($billing_city, 0, 30),
				'billing_pincode'      => $billing_pincode_int,
				'billing_state'        => substr($billing_state, 0, 50),
				'billing_country'      => $billing_country,
				'billing_email'        => $billing_email,
				'billing_phone'        => $billing_phone,
				'shipping_is_billing'  => true,
				'order_items'          => $order_items,
				'payment_method'       => $payment_method,
				'sub_total'            => $sub_total,
				'shipping_charges'     => 0,
				'giftwrap_charges'     => 0,
				'transaction_charges'  => 0,
				'total_discount'       => 0,
				'length'               => $length,
				'breadth'              => $breadth,
				'height'               => $height,
				'weight'               => $weight
			];

			if (!empty($data['_debug'])) {
				return [
					'status' => 'debug',
					'api_url' => $this->shiprocket_base_url . 'orders/create/adhoc',
					'payload' => $payload,
					'payload_json' => json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
				];
			}

			$ch = curl_init($this->shiprocket_base_url . 'orders/create/adhoc');
			curl_setopt_array($ch, [
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POST           => true,
				CURLOPT_HTTPHEADER     => [
					'Content-Type: application/json',
					'Authorization: Bearer ' . $token
				],
				CURLOPT_POSTFIELDS     => json_encode($payload),
				CURLOPT_TIMEOUT        => 60
			]);

			$result    = curl_exec($ch);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$curl_err  = curl_error($ch);
			curl_close($ch);

			if ($curl_err) {
				return [
					'status'  => 'error',
					'message' => $curl_err
				];
			}

			$response = json_decode($result, true);

			$err_detail = function() use ($response) {
				$msg = $response['message'] ?? '';
				$msg = is_array($msg) ? implode(', ', $msg) : (string) $msg;
				if (!empty($response['errors'])) {
					$err = is_array($response['errors']) ? $response['errors'] : ['raw' => $response['errors']];
					$parts = [];
					foreach ($err as $k => $v) {
						$parts[] = $k . ': ' . (is_array($v) ? implode(', ', $v) : $v);
					}
					$msg .= ($msg ? '. ' : '') . implode('; ', $parts);
				}
				return $msg ?: 'Shiprocket API error';
			};

			if ($http_code >= 400) {
				return [
					'status'  => 'error',
					'message' => $err_detail()
				];
			}

			$order_id_sr = $response['order_id'] ?? null;
			if (empty($order_id_sr) && isset($response['message'])) {
				$err_msg = is_array($response['message']) ? implode(', ', $response['message']) : (string) $response['message'];
				if (stripos($err_msg, 'invalid') !== false || stripos($err_msg, 'error') !== false || stripos($err_msg, 'Oops') !== false) {
					return [
						'status'  => 'error',
						'message' => $err_detail()
					];
				}
			}
			$awb_no = $response['awb_assign_code'] ?? $response['awb'] ?? null;
			$track_url = $response['tracking_url'] ?? '';

			return [
				'status'            => 'success',
				'awb_no'            => $awb_no,
				'system_order_id'   => $order_id_sr,
				'track_url'         => $track_url,
				'provider_request'  => json_encode($payload),
				'provider_response' => $result
			];

		} catch (Exception $e) {
			return [
				'status'  => 'error',
				'message' => $e->getMessage()
			];
		}
	}

	/**
	 * Get Shiprocket pickup locations for dropdown (used by get_provider_pickup_addresses)
	 */
	public function get_shiprocket_pickups($client_id) {
		$row = $this->db->select('*')
			->from('erp_shipping_providers')
			->where([
				'client_id' => $client_id,
				'provider'  => 'shiprocket',
				'status'    => 1
			])
			->limit(1)
			->get()
			->row();

		if (!$row) {
			return ['status' => false, 'message' => 'Shiprocket not configured.'];
		}

		$token = $this->get_shiprocket_token($row);
		if (!$token) {
			return ['status' => false, 'message' => 'Shiprocket login failed.'];
		}

		$ch = curl_init($this->shiprocket_base_url . 'settings/company/pickup');
		curl_setopt_array($ch, [
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => [
				'Content-Type: application/json',
				'Authorization: Bearer ' . $token
			],
			CURLOPT_TIMEOUT => 30
		]);
		$result = curl_exec($ch);
		curl_close($ch);
		$data = json_decode($result, true);
		if (!is_array($data)) {
			return ['status' => true, 'data' => []];
		}

		$list = [];
		// Shiprocket: shipping_address array at top level; fetch full address from API
		$shippingAddr = $data['shipping_address'] ?? ($data['data']['shipping_address'] ?? null);
		if (is_array($shippingAddr) && !empty($shippingAddr)) {
			foreach ($shippingAddr as $addr) {
				$loc = !empty($addr['pickup_location']) ? trim($addr['pickup_location']) : null;
				if ($loc) {
					$addressLine = trim($addr['address'] ?? '');
					$address2    = trim($addr['address_2'] ?? '');
					$city        = trim($addr['city'] ?? '');
					$state       = trim($addr['state'] ?? '');
					$pincode     = trim($addr['pin_code'] ?? '');
					$contactName = trim($addr['name'] ?? '');
					$fullAddress = implode(', ', array_filter([$addressLine, $address2, $city, $state, $pincode]));
					$display     = $fullAddress ? $loc . ' - ' . $fullAddress : ($loc . ($city ? ' - ' . $city : ''));
					$list[] = [
						'value'   => $loc,
						'name'    => $display,
						'address' => $fullAddress,
						'city'    => $city,
						'state'   => $state,
						'pincode' => $pincode,
						'contact' => $contactName
					];
				}
			}
		}
		$pickupData = $data['data'] ?? $data;
		if (empty($list) && isset($pickupData['address']) && is_array($pickupData['address'])) {
			foreach ($pickupData['address'] as $addr) {
				$loc = $addr['pickup_location'] ?? $addr['name'] ?? null;
				if ($loc) {
					$list[] = ['value' => $loc, 'name' => $loc];
				}
			}
		}
		if (empty($list) && isset($pickupData['pickup_addresses']) && is_array($pickupData['pickup_addresses'])) {
			foreach ($pickupData['pickup_addresses'] as $addr) {
				$loc = $addr['pickup_location'] ?? $addr['name'] ?? null;
				if ($loc) {
					$list[] = ['value' => $loc, 'name' => $loc];
				}
			}
		}
		if (empty($list) && !empty($row->pickup_name)) {
			$list[] = ['value' => $row->pickup_name, 'name' => $row->pickup_name];
		}
		return ['status' => true, 'data' => $list];
	}

}