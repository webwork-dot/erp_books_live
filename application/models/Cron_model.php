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

}