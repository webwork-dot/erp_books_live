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

        /* connect vendor DB */
        $db = $this->Vendor_sync_model->getVendorDbConnection($vendor['database_name']);

        if (!$db) {
            log_message('error', 'Client DB connection failed for vendor: '.$vendor_id);
            return false;
        }

        /* store connection */
        $this->client_db[$vendor_id] = $db;

        return $this->client_db[$vendor_id];
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