<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shipping_model extends CI_Model { 
   private $bigship_url = BIGSHIP_URL;
   
    function __construct(){
        parent::__construct();
        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
		date_default_timezone_set('Asia/Kolkata');
    }
	
    public function bigship_token($vendor_id) {
    date_default_timezone_set('Asia/Kolkata');
    $curr_date = date('Y-m-d H:i:s');
    $update_date = date('Y-m-d H:i:s');
        
    $token=$this->db->get_where('erp_shipping_providers', array('client_id' => $vendor_id,'provider' => 'bigship'))->row_array();
    $token_expiry = date('Y-m-d H:i:s',strtotime($token['token_expiry']));	
	// echo $this->db->last_query();exit();
	
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
            $this->db->where('id', $token['id']);
            $this->db->update('erp_shipping_providers', $data_update);  
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

        if (!$provider) {
            return [
                'status'  => 'error',
                'message' => 'Velocity provider configuration not found.'
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
		
        $schedule_date = date("d-m-Y", strtotime($data['schedule_date']));
        $from_Time     = date("H:i:s", strtotime($data['from_time']));
        $to_Time       = date("H:i:s", strtotime($data['to_time']));
		 	
        $payload = [[
            "username"            => $provider->name,
            "password"            => $provider->password,
            "accno"               => $provider->company_id,
            "secret_code"         => $provider->channel_id,

            "CustomerName"        => $provider->name,
            "serviceType"         => "VELOFREIGHT",
            "Product_Description" => "Order #" . $order->invoice_no,
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
            "drop_Name"        => $address->name ?? $order->user_name ?? '',
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
            "cod_amount"     => (strtolower($order->payment_method) == 'cod')
                                ? (float) $order->payment_amount
                                : 0,

            "payment_mode"   => (strtolower($order->payment_method) == 'cod')
                                ? "COD"
                                : "PAID",

            "send_otp" => false,

            "RTO_vendorname"      => "",
            "RTO_vendoraddress"   => "",
            "RTO_vendorpincode"   => "",
            "RTO_vendorcontactno" => ""
        ]];
		
				echo json_encode([
			'debug' => $payload,
			'csrf'  => [
				'name' => $this->security->get_csrf_token_name(),
				'hash' => $this->security->get_csrf_hash()
			]
		]);
		exit;

        // ===============================
        // CURL CALL
        // ===============================
        $ch = curl_init('https://velexp.com/corporate-bulk-booking/');

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
            return [
                'status'  => 'error',
                'message' => 'Curl Error: ' . $curl_err
            ];
        }

        if ($http_code != 200) {
            return [
                'status'  => 'error',
                'message' => 'Velocity API HTTP Error: ' . $http_code
            ];
        }

        $response = json_decode($result, true);

        // ===============================
        // VALIDATE API RESPONSE
        // ===============================
        if (empty($response)) {
            return [
                'status'  => 'error',
                'message' => 'Invalid response from Velocity.'
            ];
        }

        // Optional: check for AWB or success key
        // if (!isset($response[0]['awb'])) { ... }

        return [
            'status'   => 'success',
            'response' => $response
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

        $payload = [
            "shipment_category" => "b2c",
            "warehouse_detail" => [
                "pickup_location_id" => $pickup_address_id,
                "return_location_id" => $pickup_address_id
            ],
            "consignee_detail" => [
                "first_name" => $order->user_name,
                "last_name"  => "",
                "contact_number_primary" => $order->user_phone,
                "email_id" => $order->user_email,
                "consignee_address" => [
                    "address_line1" => $address->address ?? '',
                    "pincode" => $address->pincode ?? ''
                ]
            ],
            "order_detail" => [
                "invoice_date" => date("Y-m-d\TH:i:s"),
                "invoice_id" => $order->order_unique_id,
                "payment_type" => strtolower($order->payment_method) == 'cod' ? "COD" : "Prepaid",
                "shipment_invoice_amount" => (float)$order->payment_amount,
                "total_collectable_amount" => strtolower($order->payment_method) == 'cod'
                    ? (float)$order->payment_amount
                    : 0,
                "box_details" => [[
                    "each_box_dead_weight" => $weight,
                    "each_box_length" => (float)$data['length'],
                    "each_box_width"  => (float)$data['breadth'],
                    "each_box_height" => (float)$data['height'],
                    "each_box_invoice_amount" => (float)$order->payment_amount,
                    "each_box_collectable_amount" => 0,
                    "box_count" => 1,
                    "product_details" => $product_details
                ]]
            ]
        ];
			echo json_encode([
			'debug' => $payload,
			'csrf'  => [
				'name' => $this->security->get_csrf_token_name(),
				'hash' => $this->security->get_csrf_hash()
			]
		]);
		exit;
        // ===============================
        // CALL BIGSHIP API
        // ===============================
        $ch = curl_init('https://api.bigship.in/api/order/add/single');

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

        // ===============================
        // DEFAULT VALUES
        // ===============================
        $api_status  = 'failed';
        $api_message = '';
        $system_order_id = null;
        $awb_number      = null;

        if ($curl_err) {
            $api_message = $curl_err;
        } elseif ($http_code == 200 && !empty($response['success']) && $response['success'] == true) {

            $api_status = 'success';
            $api_message = $response['message'] ?? 'Success';

            $system_order_id = $response['data']['system_order_id'] ?? null;
            $awb_number      = $response['data']['awb_number'] ?? null;

            // ===============================
            // UPDATE tbl_order_details
            // ===============================
            $this->db->where('id', $order_id)
                     ->update('tbl_order_details', [
                         'shipment_id' => $system_order_id,
                         'awb_no'      => $awb_number,
                         'shipment_date' => date('Y-m-d H:i:s')
                     ]);
        } else {
            $api_message = $response['message'] ?? 'Bigship booking failed';
        }

        // ===============================
        // UPDATE tbl_order_third_party_shipping
        // ===============================
        $this->db->where('order_id', $order_id)
                 ->update('tbl_order_third_party_shipping', [
                     'provider_request_json'  => json_encode($payload),
                     'provider_response_json' => $result,
                     'system_order_id'        => $system_order_id,
                     'awb_number'             => $awb_number,
                     'api_status'             => $api_status,
                     'api_message'            => $api_message,
                     'booking_time'           => date('Y-m-d H:i:s')
                 ]);

        if ($api_status != 'success') {
            return [
                'status'  => 'error',
                'message' => $api_message
            ];
        }

        return [
            'status' => 'success',
            'response' => $response
        ];

    } catch (Exception $e) {
        return [
            'status'  => 'error',
            'message' => $e->getMessage()
        ];
    }
}


}