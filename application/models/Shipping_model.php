<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shipping_model extends CI_Model {

   public function create_velocity_bulk_booking($data){
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

            "CustomerName"        => $provider->name, // company name
            "serviceType"         => "VELOSKY",
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
            "pickup_City"       => "Mumbai",
            "pickup_State"      => "Maharashtra",
            "pickup_Address"    => "Your Warehouse Address",
            "pickup_Landmark"   => "",
            "pickup_Pincode"    => "400001",
            "pickup_Phoneno"    => "9999999999",
            "pickup_Alt_Phoneno"=> "",
            "pickup_Name"       => "Warehouse Manager",
            "pickup_Emailid"    => "warehouse@example.com",

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


}