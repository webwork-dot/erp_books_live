<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'vendor/autoload.php';
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class Pdf_model extends CI_Model
{
    public function fetch_shipping_label($shipping_no, $order, $items_arr, $address_obj, $order_type_label, $logo_url, $barcode_url, $type = "self", $ship_order_id = null)
    {
        $shipping_label = $this->get_shipping_label($shipping_no)->row();
        $barcode_no = '';
        $check_flag = 0;
        
        // Load config and helper for file path checking
        $this->config->load('upload');
        $uploadCfg = $this->config->item('picqer_barcode_upload');
        $this->load->helper('common');
        
        // Helper function to check if barcode file exists (using main folder, not vendor-specific)
        $check_barcode_file_exists = function($relative_path) {
            if (empty($relative_path)) {
                return false;
            }
            $full_path = FCPATH . ltrim($relative_path, '/');
            return file_exists($full_path);
        };
        
        // Use ship_order_id for barcode if provided, otherwise use shipping_no
        $barcode_number = !empty($ship_order_id) ? $ship_order_id : $shipping_no;
        
        if ($type == "self") {
            if (!empty($shipping_label) && !empty($shipping_label->barcode_url)) {
                $barcode_file_path = FCPATH . ltrim($shipping_label->barcode_url, '/');
                if (file_exists($barcode_file_path)) {
                    // Use base64 for PDF compatibility
                    $barcode_data = file_get_contents($barcode_file_path);
                    if ($barcode_data !== false) {
                        $barcode = 'data:image/png;base64,' . base64_encode($barcode_data);
                    } else {
                        $barcode = base_url($shipping_label->barcode_url);
                    }
                } else {
                    $barcode = base_url($shipping_label->barcode_url);
                }
                $barcode_no = $barcode_number;
                
                if (!$check_barcode_file_exists($shipping_label->barcode_url)) {
                    // Regenerate barcode with ship_order_id if provided
                    $barcode_url_new = $this->get_picqer_barcode_refresh($barcode_number, $shipping_label->id, 'barcode_url');
                    $barcode_file_path = FCPATH . ltrim($barcode_url_new, '/');
                    if (file_exists($barcode_file_path)) {
                        $barcode_data = file_get_contents($barcode_file_path);
                        if ($barcode_data !== false) {
                            $barcode = 'data:image/png;base64,' . base64_encode($barcode_data);
                        } else {
                            $barcode = base_url($barcode_url_new);
                        }
                    } else {
                        $barcode = base_url($barcode_url_new);
                    }
                }
            } elseif (!empty($barcode_url)) {
                // Try to convert URL to base64 if file exists
                $barcode_relative = str_replace(base_url(), '', $barcode_url);
                $barcode_relative = ltrim($barcode_relative, '/');
                $barcode_file_path = FCPATH . $barcode_relative;
                if (file_exists($barcode_file_path)) {
                    $barcode_data = file_get_contents($barcode_file_path);
                    if ($barcode_data !== false) {
                        $barcode = 'data:image/png;base64,' . base64_encode($barcode_data);
                    } else {
                        $barcode = $barcode_url;
                    }
                } else {
                    $barcode = $barcode_url;
                }
                $barcode_no = $barcode_number;
            } else {
                $check_flag = 1;
            }
        } elseif ($type == "bigship" && !empty($shipping_label) && !empty($shipping_label->awb_number)) {
            $barcode = base_url($shipping_label->barcode_awb_url);
            $barcode_no = $shipping_label->awb_number;
            if (!$check_barcode_file_exists($shipping_label->barcode_awb_url) || empty($shipping_label->barcode_awb_url)) {
                $barcode_url_new = $this->get_picqer_barcode_refresh($shipping_label->awb_number, $shipping_label->id, 'barcode_awb_url');
                $barcode = base_url($barcode_url_new);
            }
        } else {
            $check_flag = 1;
        }
    
        $output = '';
        if ($check_flag == 0) {
            $output = '<body class="A5"><div class="panel-body tbold" id="page-wrap">';
            $output .= '<section id="data-list-view" class="print-this" >
                    <body>
                    <div class="panel-body tbold" id="page-wrap">
                    <div class="box no-pad" >
                    <table id="invoice" class="">';

            // Logo - Get directly from erp_clients table (old way)
            $logo_src = '';
            $logo_row = $this->db->select('logo')
                ->from('erp_clients')
                ->limit(1)
                ->get()
                ->row();
            if (!empty($logo_row) && !empty($logo_row->logo)) {
                $logo_path = FCPATH . ltrim($logo_row->logo, '/');
                // Check if file exists and convert to base64 for PDF compatibility
                if (file_exists($logo_path)) {
                    $logo_data = file_get_contents($logo_path);
                    if ($logo_data !== false) {
                        $image_info = @getimagesize($logo_path);
                        $mime_type = ($image_info !== false && isset($image_info['mime'])) ? $image_info['mime'] : 'image/png';
                        $logo_src = 'data:' . $mime_type . ';base64,' . base64_encode($logo_data);
                    } else {
                        $logo_src = base_url($logo_row->logo);
                    }
                } else {
                    $logo_src = base_url($logo_row->logo);
                }
            }

            // Header: Logo on left, School info on right
            $output .= '<tr>
                     <th colspan="1" class="text-left head order_no" style="width: 30%!important;">';
            if (!empty($logo_src)) {
                $output .= '<img src="' . htmlspecialchars($logo_src) . '" class="logo" width="200" style="height: auto; max-height: 120px;">';
            }
            $output .= '</th>

                     <th colspan="2" class="text-left head order_no" style="width: 70%!important; white-space: normal; word-break: break-word;">
                     <h4 class="mb-0 mt-0 text-right school">';
            
            // If order type is Bookset, show school name and grade on the right
            if ($order_type_label == 'Bookset') {
                if (!empty($order->school_name)) {
                    $output .= '<b>' . htmlspecialchars($order->school_name) . '</b>';
                    if (!empty($order->grade_name)) {
                        $output .= ' <br/> <b>' . htmlspecialchars($order->grade_name) . '</b><br/>';
                    }
                }
            } else {
                // For other order types, show category
                if (!empty($order->category_name)) {
                    $output .= '<b>' . htmlspecialchars($order->category_name) . '</b><br/>';
                }
            }
            
            // Show order date for all orders
            if (!empty($order->created_at)) {
                $output .= ' <b>Order Date: ' . date('d M Y', strtotime($order->created_at)) . '</b>';
            }
            
            $student_name = '';
            if (!empty($address_obj) && !empty($address_obj->student_name)) {
                $student_name = $address_obj->student_name;
            }
            
            $output .= '</h4>
                    </th>
                    </tr>
                    </table>
                    </div>

                    <!-- Top Section: Shipping ID on left, Pincode + QR Code + Bookset on right -->
                    <table id="invoice" class="" style="width: 100%; margin-top: 10px;">
                    <tr>
                    <th class="text-left head order_no" style="width: 50%;">
                    <div class="box" style="border: 2px solid #000; padding: 10px; text-align: center;">
                        <h4 class="mb-0 mt-0" style="font-size: 18px !important; font-weight: bold;">
                            <b>' . htmlspecialchars(!empty($ship_order_id) ? $ship_order_id : $shipping_no) . '</b>
                        </h4>
                    </div>
                    </th>
                    <th class="text-left head order_no" style="width: 50%;">
                    <div class="box" style="border: 2px solid #000; padding: 10px; text-align: center;">
                        <h4 class="mb-0 mt-0" style="font-size: 18px !important; font-weight: bold;">
                            <b>Pincode: ' . (!empty($address_obj) ? htmlspecialchars($address_obj->pincode) : '') . '</b>
                        </h4>
                    </div>
                    <div style="text-align: center; margin-top: 10px;">
                        <img src="' . htmlspecialchars($barcode) . '" style="width: 150px; height: 150px; display: block; margin: 0 auto; object-fit: contain; max-width: 150px; max-height: 150px;">
                    </div>
                    <div class="box" style="border: 2px solid #000; padding: 10px; text-align: center; margin-top: 10px;">
                        <h4 class="mb-0 mt-0" style="font-size: 18px !important; font-weight: bold;">
                            <b>' . strtoupper($order_type_label) . '</b>
                        </h4>
                    </div>
                    </th>
                    </tr>
                    </table>

                    <!-- Shipping Details Section -->
                    <table id="invoice" class="" style="width: 100%; margin-top: 15px;">
                    <tr>
                    <th class="text-left head order_no w-100" style="padding: 10px;">
                        <h5 class="mt-0" style="font-size: 16px; font-weight: bold; margin-bottom: 10px;">
                            <b>Shipping to :</b>
                        </h5>
                        <p style="font-size: 14px; margin: 5px 0;"><b>Name: </b>' . (!empty($address_obj) ? htmlspecialchars($address_obj->name) : htmlspecialchars($order->user_name)) . ', <b>Contact No: </b>' . (!empty($address_obj) ? htmlspecialchars($address_obj->mobile_no) : htmlspecialchars($order->user_phone)) . '</p>';
            
            if ($student_name != '' && $student_name != NULL) {
                $output .= '<p style="font-size: 14px; margin: 5px 0;"><b>Student: </b><b>' . htmlspecialchars($student_name) . '</b></p>';
            }
            
            // Address details
            if (!empty($address_obj)) {
                if (!empty($address_obj->flat_house_no)) {
                    $output .= '<p style="font-size: 14px; margin: 5px 0;"><b>Flat / House Number: </b>' . htmlspecialchars($address_obj->flat_house_no);
                    if (!empty($address_obj->building_name)) {
                        $output .= ', <b>Building Name: </b>' . htmlspecialchars($address_obj->building_name);
                    }
                    $output .= '</p>';
                } elseif (!empty($address_obj->building_name)) {
                    $output .= '<p style="font-size: 14px; margin: 5px 0;"><b>Building Name: </b>' . htmlspecialchars($address_obj->building_name) . '</p>';
                }
                if (!empty($address_obj->address)) {
                    $output .= '<p style="font-size: 14px; margin: 5px 0;"><b>Street Name: </b>' . htmlspecialchars($address_obj->address) . '</p>';
                }
                if (!empty($address_obj->landmark)) {
                    $output .= '<p style="font-size: 14px; margin: 5px 0;"><b>Landmark: </b>' . htmlspecialchars($address_obj->landmark) . '</p>';
                }
                
                // Location details
                $location_parts = array();
                if (!empty($address_obj->country)) $location_parts[] = 'Country: ' . htmlspecialchars($address_obj->country);
                if (!empty($address_obj->state)) $location_parts[] = 'State: ' . htmlspecialchars($address_obj->state);
                if (!empty($address_obj->city)) $location_parts[] = 'City: ' . htmlspecialchars($address_obj->city);
                if (!empty($address_obj->pincode)) $location_parts[] = 'Pincode: ' . htmlspecialchars($address_obj->pincode);
                
                if (!empty($location_parts)) {
                    $output .= '<p style="font-size: 14px; margin: 5px 0;"><b>Location: </b>' . implode(', ', $location_parts) . '</p>';
                }
                
                if (!empty($address_obj->alternate_phone)) {
                    $output .= '<p style="font-size: 14px; margin: 5px 0;"><b>Alternate Contact No: </b>' . htmlspecialchars($address_obj->alternate_phone) . '</p>';
                }
            }
            
            $output .= '</th>
                    </tr>
                    </table>

                    <hr style="margin: 10px 0; border: 1px solid #000;"/>';


            // Product Details Table
            $output .= '<table id="order_invoice_" class="table table-bordered" style="width: 100%; margin-top: 10px; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f0f0f0;">
                            <th class="text-left" style="border: 1px solid #000; padding: 8px; width: 15%;">Order No.</th>
                            <th class="text-left" style="border: 1px solid #000; padding: 8px; width: 15%;">Invoice No.</th>
                            <th class="text-left" style="border: 1px solid #000; padding: 8px; width: 40%;">Product</th>
                            <th class="text-left" style="border: 1px solid #000; padding: 8px; width: 15%;">Product Code</th>
                            <th class="text-left" style="border: 1px solid #000; padding: 8px; width: 15%;">Invoice Value</th>
                        </tr>
                    </thead>
                    <tbody>';

            foreach ($items_arr as $item) {
                $output .= '<tr>
                        <td class="text-left" style="border: 1px solid #000; padding: 8px;">' . htmlspecialchars($order->order_unique_id) . '</td>
                        <td class="text-left" style="border: 1px solid #000; padding: 8px;">' . (!empty($order->invoice_no) ? htmlspecialchars($order->invoice_no) : '') . '</td>
                        <td class="text-left" style="border: 1px solid #000; padding: 8px;">';
                if ($order_type_label == 'Individual') {
                    $output .= htmlspecialchars(isset($item->product_title) ? $item->product_title : (isset($item->product_name) ? $item->product_name : ''));
                } else {
                    $output .= (!empty($order->school_name) ? htmlspecialchars($order->school_name) : '') . '<br>';
                    $output .= '<small class="book-pack">' . htmlspecialchars(isset($item->product_title) ? $item->product_title : (isset($item->product_name) ? $item->product_name : '')) . '</small>';
                }
                $output .= '</td>
                        <td class="text-left" style="border: 1px solid #000; padding: 8px;">' . (!empty($item->model_number) ? htmlspecialchars($item->model_number) : (!empty($item->product_sku) ? htmlspecialchars($item->product_sku) : '')) . '</td>
                        <td class="text-left" style="border: 1px solid #000; padding: 8px;">' . (!empty($item->total_price) ? number_format($item->total_price, 2) : (!empty($item->product_price) ? number_format($item->product_price * (isset($item->product_qty) ? $item->product_qty : 1), 2) : '0.00')) . '</td>
                        </tr>';
            }	

            $output .= '</tbody>
                    </table>';

            // Bottom Section: Shipping ID on left, Pincode + QR Code on right
            $output .= '<table id="invoice" class="" style="width: 100%; margin-top: 15px;">
                    <tr>
                    <th class="text-left head order_no" style="width: 50%;">
                    <div class="box" style="border: 2px solid #000; padding: 10px; text-align: center;">
                        <h4 class="mb-0 mt-0" style="font-size: 18px !important; font-weight: bold;">
                            <b>' . htmlspecialchars(!empty($ship_order_id) ? $ship_order_id : $shipping_no) . '</b>
                        </h4>
                    </div>
                    </th>
                    <th class="text-left head order_no" style="width: 50%;">
                    <div class="box" style="border: 2px solid #000; padding: 10px; text-align: center;">
                        <h4 class="mb-0 mt-0" style="font-size: 18px !important; font-weight: bold;">
                            <b>Pincode: ' . (!empty($address_obj) ? htmlspecialchars($address_obj->pincode) : '') . '</b>
                        </h4>
                    </div>
                    <div style="text-align: center; margin-top: 10px;">
                        <img src="' . htmlspecialchars($barcode) . '" style="width: 150px; height: 150px; display: block; margin: 0 auto; object-fit: contain; max-width: 150px; max-height: 150px;">
                    </div>
                    </th>
                    </tr>
                    </table>';

            // Seller Details Section
            $output .= '<table id="invoice" class="" style="width: 100%; margin-top: 15px;">
                    <tr>
                    <th class="text-left head order_no" style="padding: 10px;">
                        <h5 style="font-size: 16px; font-weight: bold; margin-bottom: 10px;">
                            <b>Seller Details:</b>
                        </h5>';
            
            // Get vendor details - join with vendor_communication_details and vendor_billing_details
            $vendor_id = isset($order->vendor_id) ? $order->vendor_id : null;
            $vendor_info = null;
            $vendor_billing = null;
            
            if (!empty($vendor_id)) {
                // Get vendor communication details
                $vendor_info = $this->db->select('v.company_name, v.address, states.name as state, cities.name as city, v.pincode')
                    ->from('vendor_communication_details as v')
                    ->join('states', 'states.id = v.state_id', 'left')
                    ->join('cities', 'cities.id = v.city_id', 'left')
                    ->where('v.vendor_id', $vendor_id)
                    ->get()
                    ->row();
                
                // Get vendor billing details (PAN, GST)
                $vendor_billing = $this->db->select('pan, gst')
                    ->from('vendor_billing_details')
                    ->where('vendor_id', $vendor_id)
                    ->get()
                    ->row();
            }
            
            $output .= '<p style="font-size: 14px; margin: 5px 0;"><b>Sold By: </b>' . (!empty($vendor_info->company_name) ? htmlspecialchars($vendor_info->company_name) : '') . '</p>';
            if (!empty($vendor_info->address) || !empty($vendor_info->state) || !empty($vendor_info->city)) {
                $vendor_addr = array();
                if (!empty($vendor_info->address)) $vendor_addr[] = htmlspecialchars($vendor_info->address);
                if (!empty($vendor_info->state)) $vendor_addr[] = htmlspecialchars($vendor_info->state);
                if (!empty($vendor_info->city)) $vendor_addr[] = htmlspecialchars($vendor_info->city);
                $output .= '<p style="font-size: 14px; margin: 5px 0;"><b>Address: </b>' . implode(',', $vendor_addr) . '</p>';
                if (!empty($vendor_info->pincode)) {
                    $output .= '<p style="font-size: 14px; margin: 5px 0;"><b>Pincode: </b>' . htmlspecialchars($vendor_info->pincode) . '</p>';
                }
            }
            if (!empty($vendor_billing->pan) || !empty($vendor_billing->gst)) {
                $output .= '<p style="font-size: 14px; margin: 5px 0;"><b>PAN: </b>' . (!empty($vendor_billing->pan) ? htmlspecialchars($vendor_billing->pan) : '') . '</p>';
                $output .= '<p style="font-size: 14px; margin: 5px 0;"><b>GSTIN: </b>' . (!empty($vendor_billing->gst) ? htmlspecialchars($vendor_billing->gst) : '') . '</p>';
            }
            
            $output .= '</th>
                    </tr>
                    </table>';

            // Final Row: Sold On, Weight, Payment Type with QR Code
            // Get vendor domain
            $vendor_domain = '';
            $client_row = $this->db->select('domain')
                ->from('erp_clients')
                ->limit(1)
                ->get()
                ->row();
            if (!empty($client_row) && !empty($client_row->domain)) {
                $vendor_domain = $client_row->domain;
                // Remove http:// or https:// if present
                $vendor_domain = preg_replace('#^https?://#', '', $vendor_domain);
                // Remove trailing slash
                $vendor_domain = rtrim($vendor_domain, '/');
            } else {
                $vendor_domain = 'www.kirtibook.in'; // Default fallback
            }
            
            // Calculate total weight
            $total_weight = 0;
            foreach ($items_arr as $item) {
                $item_weight = isset($item->weight) ? floatval($item->weight) : 0;
                $item_qty = isset($item->product_qty) ? intval($item->product_qty) : 1;
                $total_weight += ($item_weight * $item_qty);
            }
            if ($total_weight == 0) {
                $total_weight = 5.50; // Default weight if not available
            }
            
            // Payment Type
            $payment_type = 'Paid';
            if (!empty($order->payment_status)) {
                if ($order->payment_status == 'pending' || $order->payment_status == 'failed') {
                    $payment_type = 'Pending';
                } elseif ($order->payment_status == 'cod' || $order->payment_method == 'cod') {
                    $payment_type = 'COD';
                } else {
                    $payment_type = 'Paid';
                }
            }
            
            $output .= '<table id="invoice" class="" style="width: 100%; margin-top: 15px;">
                    <tr>
                    <th class="text-left" style="width: 25%; padding: 10px;">
                        <p style="font-size: 14px; margin: 5px 0;"><b>Sold On: </b><b style="text-decoration: underline;">' . htmlspecialchars($vendor_domain) . '</b></p>
                    </th>
                    <th class="text-left" style="width: 25%; padding: 10px;">
                        <p style="font-size: 14px; margin: 5px 0;"><b>Weight: </b><b>' . number_format($total_weight, 2) . ' KG</b></p>
                    </th>
                    <th class="text-left" style="width: 25%; padding: 10px;">
                        <p style="font-size: 14px; margin: 5px 0;"><b>Payment Type: </b><b>' . $payment_type . '</b></p>
                    </th>
                    
                    </tr>
                    </table>';

            $output .= ' </body>
            </section>';

            $output .= '</div></body>';
        }
        return $output;
    }


    public function get_shipping_label_dtdc($slot_no)
    {
        $this->db->where('slot_no', $slot_no);
        $this->db->where('courier', 'DTDC');
        $query = $this->db->get('vendor_shipping_label');
        return $query;
    }
	
	public function get_dtdc_label_limit()
    {
		$this->db->select('awb_number,slot_no');
        $this->db->where('courier', 'DTDC');
		$where_awb = "awb_number is NOT NULL";		
		$where_label = "dtdc_label_url is NULL";
		$this->db->where($where_awb);
		$this->db->where($where_label);
		$this->db->limit(10);
        $query = $this->db->get('vendor_shipping_label');
        return $query; 
	}
	
	public function get_shipping_label_dtdc_limit_1($slot_no)
    {
		$this->db->select('awb_number');
        $this->db->where('slot_no', $slot_no);
        $this->db->where('courier', 'DTDC');
		$this->db->limit(1);
        $query = $this->db->get('vendor_shipping_label');
        return $query;
	}

    public function get_shipping_label($slot_no)
    {
        // Check if vendor_shipping_label table exists
        if (!$this->db->table_exists('vendor_shipping_label')) {
            // Return empty result set if table doesn't exist
            return $this->db->query("SELECT NULL as id, NULL as slot_no, NULL as barcode_url, NULL as label_url WHERE 1=0");
        }
        
        $this->db->where('slot_no', $slot_no);
        $query = $this->db->get('vendor_shipping_label');
        return $query;
    }

	public function check_shipping_label_provider($slot_nos,$shipment_provider){
		$this->db->from('vendor_shipping_label');
		$this->db->where_in('slot_no', $slot_nos);
		$this->db->where('shipment_provider', $shipment_provider);
		$query = $this->db->get();
		$num_rows = $query->num_rows();
		if ($num_rows == count($slot_nos)) {
			return true; 
		} else {
			return false;
		}

    }


    public function check_shipping_label($slot_no)
    {
        $this->db->select('id');
        $this->db->where('label_url!=', NULL);
        $this->db->where('slot_no', $slot_no);
        $query = $this->db->get('vendor_shipping_label');
        return $query;
    }

    public function add_shipping_label($shipping_no, $vendor_id = null, $process_slot = null)
    {
        // Check if vendor_shipping_label table exists
        if (!$this->db->table_exists('vendor_shipping_label')) {
            // Table doesn't exist, return null (will be handled by calling code)
            return null;
        }
        
        // Try to get vendor_id from order if not provided
        if (empty($vendor_id)) {
            // Try from tbl_order_details (current structure)
            $query2 = $this->db->query("SELECT user_id as vendor_id FROM tbl_order_details WHERE order_unique_id='$shipping_no' limit 1");
            if ($query2->num_rows() > 0) {
                $row2 = $query2->row_array();
                $vendor_id = isset($row2['vendor_id']) ? $row2['vendor_id'] : null;
            }
            
            // If still empty, try from old orders table (for backward compatibility)
            if (empty($vendor_id) && $this->db->table_exists('orders')) {
                $query2 = $this->db->query("SELECT vendor_id,process_slot FROM orders WHERE order_slot='$shipping_no' limit 1");
                if ($query2->num_rows() > 0) {
                    $row2 = $query2->row_array();
                    $vendor_id = isset($row2['vendor_id']) ? $row2['vendor_id'] : null;
                    $process_slot = isset($row2['process_slot']) ? $row2['process_slot'] : null;
                }
            }
        }

        $check_sql = $this->db->query("SELECT id FROM vendor_shipping_label WHERE slot_no='$shipping_no' limit 1");

        if ($check_sql->num_rows() == 0) {
            date_default_timezone_set('Asia/Kolkata');
            $added_date = date('Y-m-d H:i:s');
            $data_slot  = array(
                'vendor_id' => $vendor_id ? $vendor_id : 0,
                'process_slot' => $process_slot ? $process_slot : $shipping_no,
                'slot_no' => $shipping_no,
                'ctype' => 'direct',
                'created_at' => $added_date
            );
            $insert     = $this->db->insert('vendor_shipping_label', $data_slot);
            $id         = $this->db->insert_id();
        } else {
            $row = $check_sql->row();
            $id  = $row->id;
        }

        return $id;
    }




    function get_barcode($code, $id) {
        //load library
        //$this->load->library('zend');
        //load in folder Zend
        //$this->zend->load('Zend/Barcode');

        //generate barcode
        //$file = Zend_Barcode::draw('code39', 'image', array(
        //    'text' => $code,
        //    'factor' => 2
        //), array());


        include('./phpqrcode/qrlib.php');

        $year      = date("Y");
        $month     = date("m");
        $day       = date("d");
        $directory = "uploads/vendor_barcode_new/" . "$year/$month/$day/";

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $fileName = 'sk_qr_'.$code.'.png';

        $pngAbsoluteFilePath = $directory.$fileName;
        $urlRelativeFilePath = $directory.$fileName;


        if (!file_exists($pngAbsoluteFilePath)) {
            QRcode::png($code, $pngAbsoluteFilePath);
        }

        //$code = 'sk_qr_' . $code;


        //$year      = date("Y");
        //$month     = date("m");
        //$day       = date("d");
        //The folder path for our file should be YYYY/MM/DD
        //$directory = "uploads/vendor_barcode_new/" . "$year/$month/$day/";

        //If the directory doesn't already exists.
        //if (!is_dir($directory)) {
        //    mkdir($directory, 0755, true);
        //}

        //$store_image = imagepng($file, "$directory{$code}.png");
        //$file_url    = $directory . $code . '.png';
        $data_order  = array(
            'barcode_url' => $pngAbsoluteFilePath
        );

        $this->db->where('id', $id);
        $this->db->update('vendor_shipping_label', $data_order);
    }



    function get_barcode_refresh($code)
    {
        $slot_no = $code;
        //load library
        //$this->load->library('zend');
        //load in folder Zend
        //$this->zend->load('Zend/Barcode');
        //generate barcode
        //$file = Zend_Barcode::draw('code39', 'image', array(
        //    'text' => $code
        //), array());
        //$code = 'sk_barcode_' . $code . '_' . date("Ymdhis");

        //$year      = date("Y");
        //$month     = date("m");
        //$day       = date("d");
        //The folder path for our file should be YYYY/MM/DD
        //$directory = "uploads/vendor_barcode_new/" . "$year/$month/$day/";

        //If the directory doesn't already exists.
        //if (!is_dir($directory)) {
        //    mkdir($directory, 0755, true);
        //}

        //$store_image = imagepng($file, "$directory{$code}.png");
        //$file_url    = $directory . $code . '.png';
        //$data_order  = array(
        //    'barcode_url' => $file_url
        //);

        include('./phpqrcode/qrlib.php');

        $year      = date("Y");
        $month     = date("m");
        $day       = date("d");
        $directory = "uploads/vendor_barcode_new/" . "$year/$month/$day/";

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $fileName = 'sk_qr_'.$code.'.png';

        $pngAbsoluteFilePath = $directory.$fileName;
        $urlRelativeFilePath = $directory.$fileName;


        if (!file_exists($pngAbsoluteFilePath)) {
            QRcode::png($code, $pngAbsoluteFilePath);
        }

        $data_order  = array(
            'barcode_url' => $pngAbsoluteFilePath
        );

        //$this->db->where('slot_no', $slot_no);
        $this->db->where('slot_no', $slot_no);
        $this->db->update('vendor_shipping_label', $data_order);
        return $file_url;
    }


    public function update_label_generated($shipping_no, $file_url)
    {
        $data_order = array(
            'label_url' => $file_url,
            'is_label' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        );
        $this->db->where('slot_no', $shipping_no);
        $this->db->update('vendor_shipping_label', $data_order);
    }
	
    public function update_label_bighsip_generated($shipping_no, $file_url) {
        $data_order = array(
            'bigship_label_url' => $file_url,
            'is_label' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        );
        $this->db->where('slot_no', $shipping_no);
        $this->db->update('vendor_shipping_label', $data_order);
    }


    public function fetch_invoice_report($order_id)
    {
        $order      = $this->pdf_model->get_invoice_bookset_order_details($order_id);
        $order_date = date('d-m-Y', strtotime($order->created_at));

        $vendor         = $this->pdf_model->get_vendor_details($order->vendor_id);
        $vendor_billing = $this->pdf_model->get_vendor_billing_details($order->vendor_id);
        $shipping       = $this->pdf_model->get_order_shipping($order_id);
        $order_student  = $this->pdf_model->get_order_student_details($order_id);
        $student_name   = $order_student->f_name . ' ' . $order_student->m_name . ' ' . $order_student->s_name;
        if ($vendor_billing['state'] == $shipping['shipping_state']):
            $is_igst = 0;
        else:
            $is_igst = 1;
        endif;
        $vendor_sign    = vendor_url() . 'uploads/vendor/' . $vendor['signature'];
        $price_shipping = price_format_decimal($order->price_shipping);
        $total_amt      = $order->price_total - $price_shipping;

        if ($order->order_type == 'bookset') {
            $output          = '<body class="invoice txtup">
          <div class="panel-body" id="page-wrap">

            <table id="invoice">
                <thead>
                <tr>
                    <th class="head-img text-left" style="width: 30%;">
                      <p>Ordered through</p>
                      <img src="https://kirtibook.in/images/logo-pdf.png" class="logo">
                    </th>
                    <th class="text-right head order_no">
                     <div class="box"> <h2> ' . $order->order_number . '</h2></div>
                     <div class="box"> <h3> ' . $order->grade_name . '</h3></div>
                     </th>
                    <th class="text-right head" style="width: 36%;">
                      <p><b>Tax Invoice/Bill of Supply/Cash Memo</b></p>
                      <p>(Original for Recipient)</p>
                      <p class="m-t-20"><b  class="bold font12">Order Number:' . $order->order_number . '</b>  | <b>Order Date:</b> ' . $order_date . '
                      </p><p><b class="bold font12">Invoice Number : ' . $order->invoice_no . '</b>  | <b>Invoice Date :</b>' . $order_date . '</p>
                    </th>
                </tr>
                </thead>
            </table>
            <table id="invoice_3" class="m-t-10 table table-bordered" style="table-layout: fixed;">
                <thead>
                <tr>
                    <th class="p-l-r text-left" style="width: 36%;">
                        <p><b>Sold By: </b>' . $vendor['company_name'] . '</p>
                        <p><b>Address: </b> ' . $vendor['address'] . '</p>
                        <p class="m-t-10"><b>PAN: </b>' . $vendor_billing['pan'] . '</p>
                        <p><b>GSTIN: </b> ' . $vendor_billing['gst'] . '</p>
                        <p><b>Place of Supply: </b> ' . $vendor_billing['state'] . '</p>
                    </th>
                    <th class="p-l-r text-left"  style="width: 36%;">
                        <p><b>Name:</b> ' . $shipping['name'] . '</p>
                        <p><b>Address:</b> ' . $shipping['address'] . ', ' . $shipping['shipping_city'] . '-' . $shipping['pincode'] . ', ' . $shipping['shipping_state'] . '.  Landmark- ' . $shipping['landmark'] . '</p>
                        <p><b>GSTIN:</b> URP</p>

                    </th>
                    <th class="p-l-r text-left">
                        <p><b>Student Details:</b></p>
                        <p><b>Name:</b> ' . $student_name . '</p>
                        <p><b>School:</b> ' . $order->school_name . '</p>
                        <p><b>Grade:</b> ' . $order->grade_name . '</p>
                    </th>
                </tr>
                </thead>
            </table>
            <table id="invoice_1" class="m-t-10 product table table-bordered">
            <thead>
                <tr>
                    <th rowspan="2">#</th>
                    <th rowspan="2">Product</th>
                    <th rowspan="2">Qty<br> <small>(nos.)</small></th>
                    <th rowspan="2">Amount<br><small>(INR)</small></th>
                    <th rowspan="2">HSN</th>
                    <th colspan="2">CGST</span></th>
                    <th colspan="2">SGST</th>
                    <th colspan="2">IGST</th>
                    <th rowspan="2">Amount <br>(incl. Tax)</small></th>
                </tr>
                <tr>
                    <th>%</th>
                    <th>Amt</th>
                    <th>%</th>
                    <th>Amt</th>
                    <th>%</th>
                    <th>Amt</th>
                </tr>
                </thead>
                <tbody>';
            $package_details = $this->pdf_model->get_invoice_package_by_id($order_id);
            $sr_no           = 1;
            foreach ($package_details as $package_) {
                $output .= '<tr class="border-top">
                    <td>' . $sr_no . '.</td>
                    <td class="left">
                        <b>' . $package_['package_name'] . '</b>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>';

                foreach ($package_['products'] as $product) {
                    $output .= '<tr>
                    <td></td>
                    <td class="left ">
                        ' . $product['name'] . '
                    </td>
                    <td> ' . $product['quantity'] . '    </td>
                    <td> ' . $product['total_price_excl'] . ' </td>
                    <td> ' . $product['hsn'] . ' </td>';

                    if ($is_igst == 0):
                        $output .= '<td> ' . ($product['gst'] / 2) . '%  </td>
                        <td> ' . price_format_decimal($product['gst_amt'] / 2) . '  </td>
                        <td> ' . ($product['gst'] / 2) . '%  </td>
                        <td> ' . price_format_decimal($product['gst_amt'] / 2) . '  </td>
                        <td> -  </td>
                        <td> -  </td>';
                    else:
                        $output .= '<td> -  </td><td> -  </td><td> -  </td><td> -  </td>
                        <td> ' . $product['gst'] . '% </td>
                        <td> ' . $product['gst_amt'] . '  </td>';
                    endif;
                    $output .= '<td> ' . $product['total_price'] . ' </td>
                    </tr>';

                }
                $sr_no++;
            }

            if ($order->price_discount > 0) {
                $output .= '<tr>
                    <td>' . $sr_no . '.</td>
                    <td class="left ">
                    Discount
                    </td>
                    <td> -   </td>
                    <td> ' . $order->price_discount . ' </td>
                    <td> - </td>
                     <td> -  </td>
                     <td> -  </td>
                     <td> -  </td>
                     <td> -  </td>
                    <td> - </td>
                    <td> - </td>
                    <td> ' . $order->price_discount . '  </td>
                    </tr>';
            }

            $output .= '
                <tr class="border-top">
                    <td colspan="9" class="left"><b>Amount in Words:</b> ' . rupees_word($total_amt) . '</td>
                    <td colspan="2" class="right"><b>Total Amount</b></td>
                    <td><b>' . price_format_decimal($total_amt) . '</b></td>
                </tr>

                <tr class="border-top">
                    <td colspan="9" class="left">
                    <p  class="text-left text-gray">Whether tax is payable on reverse charge basis - "No" <span class="pull-right"> E.&O.E.</span> </p>

                    <p  class="text-left"><b>Declaration:</b> <small>The goods sold are intended for end user consumption and not for resale. Please note that this invoice is not a demand for payment </small> </p>
                    <p  class="text-left"><b>Note:</b> <small>Out of Stock items(if any) will be handed over in the school/classroom. </small> </p>
                    <p  class="text-left"><b>Note:</b> <small>Goods sold once will not be returned.</small> </p>
                    </td>
                    <td class="right" colspan="3">
                    <p>
                    <b>For ' . $vendor['company_name'] . ':</b><br>
                    <img src="' . $vendor_sign . '" style="max-width: 200px;height: 70px;"><br>
                    <b>Authorized Signatory</b>
                    </p>
                    </td>
                  </tr>
                </tbody>
            </table>';

            //      $second_shipments= $this->pdf_model->get_invoice_second_shipment_by_id($order_id);


            $output .= '    </div></body>';
            return $output;
        } else {
            $output          = '<body class="invoice txtup">
          <div class="panel-body" id="page-wrap">

            <table id="invoice">
                <thead>
                <tr>
                    <th class="head-img text-left" style="width: 30%;">
                      <p>Ordered through</p>
                      <img src="https://kirtibook.in/images/logo-pdf.png" class="logo">
                    </th>
                    <th class="text-right head order_no">
                     <div class="box"> <h2> ' . $order->order_number . '</h2></div>
                     </th>
                    <th class="text-right head" style="width: 36%;">
                      <p><b>Tax Invoice/Bill of Supply/Cash Memo</b></p>
                      <p>(Original for Recipient)</p>
                      <p class="m-t-20"><b  class="bold font12">Order Number:' . $order->order_number . '</b>  | <b>Order Date:</b> ' . $order_date . '
                      </p><p><b class="bold font12">Invoice Number : ' . $order->invoice_no . '</b>  | <b>Invoice Date :</b>' . $order_date . '</p>
                    </th>
                </tr>
                </thead>
            </table>
            <table id="invoice_3" class="m-t-10 table table-bordered" style="table-layout: fixed;">
                <thead>
                <tr>
                    <th class="p-l-r text-left" style="width: 50%;">
                        <p><b>Sold By: </b>' . $vendor['company_name'] . '</p>
                        <p><b>Address: </b> ' . $vendor['address'] . '</p>
                        <p class="m-t-10"><b>PAN: </b>' . $vendor_billing['pan'] . '</p>
                        <p><b>GSTIN: </b> ' . $vendor_billing['gst'] . '</p>
                        <p><b>Place of Supply: </b> ' . $vendor_billing['state'] . '</p>
                    </th>
                    <th class="p-l-r text-left"  style="width: 50%;">
                        <p><b>Name:</b> ' . $shipping['name'] . '</p>
                        <p><b>Address:</b> ' . $shipping['address'] . ', ' . $shipping['shipping_city'] . '-' . $shipping['pincode'] . ', ' . $shipping['shipping_state'] . '.  Landmark- ' . $shipping['landmark'] . '</p>
                        <p><b>GSTIN:</b> URP</p>

                    </th>
                </tr>
                </thead>
            </table>
            <table id="invoice_1" class="m-t-10 product table table-bordered">
            <thead>
                <tr>
                    <th rowspan="2">#</th>
                    <th rowspan="2">Product</th>
                    <th rowspan="2">Qty<br> <small>(nos.)</small></th>
                    <th rowspan="2">Amount<br><small>(INR)</small></th>
                    <th rowspan="2">HSN</th>
                    <th colspan="2">CGST</span></th>
                    <th colspan="2">SGST</th>
                    <th colspan="2">IGST</th>
                    <th rowspan="2">Amount <br>(incl. Tax)</small></th>
                </tr>
                <tr>
                    <th>%</th>
                    <th>Amt</th>
                    <th>%</th>
                    <th>Amt</th>
                    <th>%</th>
                    <th>Amt</th>
                </tr>
                </thead>
                <tbody>';
            $package_details = $this->pdf_model->get_invoice_product_by_id($order_id);
            $sr_no           = 1;
            foreach ($package_details as $package_) {
                foreach ($package_['products'] as $product) {
                    $size_name = ($order->size_name != '' ? ' Size-' . $order->size_name : '');

                    $output .= '<tr class="border-top">
                    <td>' . $sr_no . '.</td>
                    <td class="left ">
                    ' . $product['name'] . '  ' . $size_name . '
                    </td>
                    <td> ' . $product['quantity'] . '    </td>
                    <td> ' . $product['total_price_excl'] . ' </td>
                    <td> ' . $product['hsn'] . ' </td>';

                    if ($is_igst == 0):
                        $output .= '<td> ' . ($product['gst'] / 2) . '%  </td>
                        <td> ' . price_format_decimal($product['gst_amt'] / 2) . '  </td>
                        <td> ' . ($product['gst'] / 2) . '%  </td>
                        <td> ' . price_format_decimal($product['gst_amt'] / 2) . '  </td>
                        <td> -  </td>
                        <td> -  </td>';
                    else:
                        $output .= '<td> -  </td><td> -  </td><td> -  </td><td> -  </td>
                        <td> ' . $product['gst'] . '% </td>
                        <td> ' . $product['gst_amt'] . '  </td>';
                    endif;
                    $output .= '<td> ' . $product['total_price'] . ' </td>
                    </tr>';

                    $sr_no++;
                }
            }

            if ($order->price_discount > 0) {
                $output .= '<tr>
                    <td>' . $sr_no . '.</td>
                    <td class="left ">
                    Discount
                    </td>
                    <td> -   </td>
                    <td> ' . $order->price_discount . ' </td>
                    <td> - </td>
                     <td> -  </td>
                     <td> -  </td>
                     <td> -  </td>
                     <td> -  </td>
                    <td> - </td>
                    <td> - </td>
                    <td> ' . $order->price_discount . '  </td>
                    </tr>';
            }

            $output .= '
                <tr class="border-top">
                    <td colspan="9" class="left"><b>Amount in Words:</b> ' . rupees_word($total_amt) . '</td>
                    <td colspan="2" class="right"><b>Total Amount</b></td>
                    <td><b>' . price_format_decimal($total_amt) . '</b></td>
                </tr>

                <tr class="border-top">
                    <td colspan="9" class="left">
                    <p  class="text-left text-gray">Whether tax is payable on reverse charge basis - "No" <span class="pull-right"> E.&O.E.</span> </p>

                    <p  class="text-left"><b>Declaration:</b> <small>The goods sold are intended for end user consumption and not for resale. Please note that this invoice is not a demand for payment </small> </p>
                    <p  class="text-left"><b>Note:</b> <small>Out of Stock items(if any) will be handed over in the school/classroom. </small> </p>
                    </td>
                    <td class="right" colspan="3">
                    <p>
                    <b>For ' . $vendor['company_name'] . ':</b><br>
                    <img src="' . $vendor_sign . '" style="max-width: 200px;height: 70px;"><br>
                    <b>Authorized Signatory</b>
                    </p>
                    </td>
                  </tr>
                </tbody>
            </table>';

            $output .= '    </div></body>';
            return $output;
        }

    }

    public function get_invoice_bookset_order_details($order_id)
    {
        $this->db->select('orders.id,orders.invoice_cn,orders.size_name,orders.cancelled_date,orders.order_type,orders.order_number,orders.grade_name,orders.invoice_no,orders.price_discount,orders.shipping_date,orders.price_shipping,orders.price_total,orders.vendor_id,orders.total_weight,orders.slot_no,orders.created_at,orders.school_name,orders.grade_name,orders.board_name,cities.name as school_city');
        $this->db->join('school', 'orders.school_id = school.id', 'left');
        $this->db->join('cities', 'school.city_id = cities.id', 'left');
        $this->db->join('users', 'orders.vendor_id = users.id');
        $this->db->where('orders.id', $order_id);
        $query = $this->db->get('orders');
        return $query->row();
    }

    public function get_vendor_details($vendor_id)
    {
        $this->db->select('vendor_documents.signature,users.email,v.company_name,v.address,states.name as state,states.code as state_code,cities.name as city,v.pincode,v.contact_number as phone');
        $this->db->join('vendor_communication_details as v', 'users.id = v.vendor_id');
        $this->db->join('vendor_documents', 'users.id = vendor_documents.vendor_id');
        $this->db->join('states', 'states.id = v.state_id');
        $this->db->join('cities', 'cities.id = v.city_id');
        $this->db->where('users.id', $vendor_id);
        $query = $this->db->get('users');
        return $query->row_array();
    }

    public function get_vendor_billing_details($vendor_id)
    {
        $this->db->select('vendor_billing_details.pan,vendor_billing_details.address,vendor_billing_details.city_id,vendor_billing_details.gst,states.name as state');
        $this->db->join('states', 'states.id = vendor_billing_details.state_id');
        $this->db->where('vendor_id', $vendor_id);
        $query = $this->db->get('vendor_billing_details');
        return $query->row_array();
    }

    public function get_order_shipping($order_id)
    {
        $order_id = clean_number($order_id);
        $this->db->select('order_shipping.*,order_shipping.state_name as shipping_state,order_shipping.city_name as shipping_city');
        $this->db->where('order_id', $order_id);
        $query = $this->db->get('order_shipping');
        return $query->row_array();
    }

    public function get_order_student_details($order_id)
    {
        $this->db->select('orders.f_name,orders.m_name,orders.s_name');
        $this->db->where('id', $order_id);
        $this->db->group_by('id');
        $query = $this->db->get('orders');
        return $query->row();
    }

    public function get_invoice_package_by_id($order_id)
    {
        $order_id      = clean_number($order_id);
        $package       = $this->db->query("SELECT op.orderform_id,op.package_name,op.package_weight,op.package_id,orders.size_name FROM orders INNER JOIN order_products AS op ON  orders.id=op.order_id WHERE orders.id='$order_id' AND orders.payment_status='payment_received' group by op.package_id order by op.id ASC");
        $package_count = $package->num_rows();
        if ($package_count > 0) {
            foreach ($package->result_array() as $package_row) {
                $package_id     = $package_row['package_id'];
                $package_name   = $package_row['package_name'];
                $package_weight = $package_row['package_weight'];
                $size_name      = ($package_row['size_name'] != '' ? $package_row['size_name'] : '');

                $subtotal         = 0;
                $total            = 0;
                $products         = array();
                $package_products = $this->db->query("SELECT product_id,product_unit_price,product_quantity,product_total_price,product_name,publish_name,model_number,category_id,product_gst,hsn FROM order_products WHERE package_id='$package_id' AND order_id = '$order_id' group by product_id");
                foreach ($package_products->result_array() as $package_products_row) {
                    $product_id          = $package_products_row['product_id'];
                    $product_name        = $package_products_row['product_name'];
                    $product_unit_price  = $package_products_row['product_unit_price'];
                    $product_quantity    = $package_products_row['product_quantity'];
                    $product_total_price = $package_products_row['product_total_price'];
                    $publish_name        = $package_products_row['publish_name'];
                    $hsn                 = $package_products_row['hsn'];
                    $gst                 = $package_products_row['product_gst'];

                    $gst_excl         = ($gst + 100) / 100;
                    $total_price_excl = $product_total_price / $gst_excl;
                    $gst_amt          = $product_total_price - $total_price_excl;

                    $products[] = array(
                        "id" => $product_id,
                        "name" => $product_name,
                        "size_name" => $size_name,
                        "quantity" => $product_quantity,
                        "unit_price" => price_format_decimal($product_unit_price),
                        "total_price" => price_format_decimal($product_total_price),
                        "hsn" => $hsn,
                        "gst" => $gst,
                        "total_price_excl" => price_format_decimal($total_price_excl),
                        "gst_amt" => price_format_decimal($gst_amt)
                    );
                }

                $resultpost[] = array(
                    'package_id' => $package_id,
                    'package_name' => $package_name,
                    'products' => $products
                );
            }
        } else {
            $resultpost = array();
        }
        return $resultpost;
    }

    public function get_invoice_product_by_id($order_id)
    {
        $order_id = clean_number($order_id);

        $package_products = $this->db->query("SELECT product_id,product_unit_price,product_quantity,product_total_price,product_name,publish_name,model_number,category_id,product_gst,hsn FROM order_products WHERE order_id = '$order_id' group by product_id");
        foreach ($package_products->result_array() as $package_products_row) {
            $product_id          = $package_products_row['product_id'];
            $product_name        = $package_products_row['product_name'];
            $product_unit_price  = $package_products_row['product_unit_price'];
            $product_quantity    = $package_products_row['product_quantity'];
            $product_total_price = $package_products_row['product_total_price'];
            $publish_name        = $package_products_row['publish_name'];
            $hsn                 = $package_products_row['hsn'];
            $gst                 = $package_products_row['product_gst'];

            $gst_excl         = ($gst + 100) / 100;
            $total_price_excl = $product_total_price / $gst_excl;
            $gst_amt          = $product_total_price - $total_price_excl;

            $products[] = array(
                "id" => $product_id,
                "name" => $product_name,
                "quantity" => $product_quantity,
                "unit_price" => price_format_decimal($product_unit_price),
                "total_price" => price_format_decimal($product_total_price),
                "hsn" => $hsn,
                "gst" => $gst,
                "total_price_excl" => price_format_decimal($total_price_excl),
                "gst_amt" => price_format_decimal($gst_amt)
            );
        }

        $resultpost[] = array(
            'products' => $products
        );

        return $resultpost;
    }

    public function update_invoice_generated($order_id, $file_url)
    {
        $data_order = array(
            'invoice_url' => $file_url,
            'is_invoice' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        );
        $this->db->where('id', $order_id);
        $this->db->update('orders', $data_order);
    }



    public function fetch_user_invoice($order_id)
    {
        $order             = $this->order_model->get_order($order_id);
        $shipping          = $this->pdf_model->get_order_shipping($order_id);
        //$vendor_billing= $this->pdf_model->get_vendor_billing_details($order->vendor_id);
        //print_r($shipping);
        $price_shipping    = $order->price_shipping;
        $price_shipping_bt = $order->price_shipping / 1.18;
        $shipping_gst      = $order->price_shipping - $price_shipping_bt;

        if ($shipping['shipping_state'] == 'Maharashtra'):
            $is_igst = 0;
        else:
            $is_igst = 1;
        endif;

        $output = '<body class="invoice txtup">
      <div class="panel-body" id="page-wrap">
        <table id="invoice">
            <thead>
            <tr>
                <th class="head-img text-left">
                  <img src="https://kirtibook.in/images/logo-pdf.png" class="logo">
                </th>
                <th class="text-right head">
                  <p><b>Tax Invoice</b></p>
                  <p>(Original for Recipient)</p>
                  <p><b>Invoice Number :</b>' . $order->user_invoice . '</p>
                  <p><b>Invoice Date :</b>' . date('d-m-Y', strtotime($order->user_invoice_date)) . '</p>
                </th>
            </tr>
            </thead>
        </table>
        <table id="invoice_3" class="m-t-10 table " style="table-layout: fixed;">
            <thead>
            <tr>
                <th class="p-l-r text-left" style="width: 36%;">
                    <p><b>Kirti Book Store</b></p>
                    <p> Shop No. A-1, BBC Tower, below Bank Of India, near City International School, Aundh, Pune, Maharashtra 411007</p>
                    <p class="m-t-20"><b>email ID: </b>info@kirtibook.in</p>
                    <p><b>Contact No.: </b> 8380082390</p>
                    <p><b>PAN : </b> -</p>
                    <p><b>GSTIN : </b> -</p>
                </th>
                <th class="p-l-r text-left"  style="width: 36%;">
                    <p><b>Bill To : </b>' . $shipping['name'] . '</p>
                    <p><b>Address:</b> ' . $shipping['address'] . ', ' . $shipping['shipping_city'] . '-' . $shipping['pincode'] . ', ' . $shipping['shipping_state'] . '.  Landmark- ' . $shipping['landmark'] . '</p>
                    <p><b>email ID : </b>' . $shipping['email'] . '</p>
                    <p><b>Contact No. : </b>' . $shipping['phone'] . '</p>
                    <p class="m-t-20"><b>GSTIN:</b> URP</p>
                    <p><b>Place of Supply : </b>' . $shipping['shipping_state'] . '</p>

                </th>
            </tr>
            </thead>
        </table>
        <table id="invoice_1" class="m-t-10 product table table-bordered">
            <thead>
            <tr>
                <th rowspan="2">Sl. No</th>
                <th rowspan="2">Description of<br/> the Service</th>
                <th colspan="1">Order No.<br> <small></small></th>
                <th rowspan="2">Amount<br><small>(INR)</small></th>
                <th rowspan="2">HSN / SAC</th>';
        $output .= '<th colspan="2">CGST</span></th>
                <th colspan="2">SGST</th>';
        $output .= '<th colspan="2">IGST</th>';
        $output .= '<th rowspan="2">Amount(INR) <br>(incl. Tax)</small></th>
            </tr>
            <tr>
              <th>Carrier</th>';
        $output .= '<th>Rate</th>
                <th>Amt(INR)</th>
                <th>Rate</th>
                <th>Amt(INR)</th>
                <th>Rate</th>
                <th>Amt(INR)</th>
             </tr>
            </thead>
            <tbody>

                <tr>
                    <td>1.</td>
                    <td>Product Handling Charges</td>
                    <td>' . $order->order_number . ' <br> ' . $order->courier . '</td>
                    <td>' . price_format_decimal($price_shipping_bt) . '</td>
                    <td>996812</td>';

        if ($is_igst == 0):
            $output .= '
                    <td>9%</td>
                    <td>' . price_format_decimal($shipping_gst / 2) . '</td>
                    <td>9%</td>
                    <td>' . price_format_decimal($shipping_gst / 2) . '</td>
                    <td>18%</td><td>-</td>';
        else:
            $output .= '<td>9%</td><td>-</td><td>9%</td><td>-</td><td>18%</td>
                    <td>' . price_format_decimal($shipping_gst) . '</td>';
        endif;

        $output .= '<td>' . price_format_decimal($price_shipping) . '</td></tr>
            <tr class="border-top">
                <td colspan="12" class="left"><b>Amount in Words:</b> ' . rupees_word($price_shipping) . '</td>
            </tr>
        </tbody>
    </table>
    <table id="invoice" class="m-t-10">
        <tbody>
            <tr class="border-top">
                <td colspan="9" class="left">
                <p  class="text-left text-gray">E.&O.E. </p>
                <p  class="text-left"><b>Note: </b> Whether tax is payable on Reverse Charge – No.
                <br/>    Please note that this invoice is not a demand for payment</p>
                </td>
                <td class="right" colspan="3">
                <p>
                <b>For Kirti Book Store:</b><br>
                <img src="https://kirtibook.in/images/sign.PNG" style="max-width: 200px;height: 45px;"><br>
                <b>Authorized Signatory</b>
                </p>
                </td>
            </tr>
        </tbody>
    </table>';
        return $output;
    }


    public function update_user_invoice_generated($order_id, $file_url)
    {
        $data_order = array(
            'user_invoice_url' => $file_url
        );
        $this->db->where('id', $order_id);
        $this->db->update('orders', $data_order);
    }


   /* public function update_user_invoice_number($order_id)
    {
        date_default_timezone_set('Asia/Kolkata');
        $date_created  = date('Y-m-d H:i');
        $order_details = $this->order_model->get_order($order_id);
        if ($order_details->user_invoice == NULL) {
            $vendor_id      = $order_details->vendor_id;
            $delivered_date = $order_details->delivered_date;
            //$delivered_date='2021-05-30';

            $delivery_day = date('m', strtotime($delivered_date . ' + 0 day'));
            $d_year       = date('Y', strtotime($delivered_date . ' + 0 day'));
            if ($delivery_day >= 4) {
                $pre_year = (date($d_year) + 1);
            } else {
                $pre_year = (date($d_year));
            }

            $order_series = $this->db->query("SELECT order_series FROM `users` WHERE id='$vendor_id' LIMIT 1")->row()->order_series;
            $vendor_pre   = strtoupper(strtolower($order_series));


            $check_inv_sql = $this->db->query("SELECT user_invoice FROM `order_user_invoice` WHERE year='$pre_year' AND vendor_id='$vendor_id' order by id desc LIMIT 1");
            if ($check_inv_sql->num_rows() > 0) {
                $row             = $check_inv_sql->row();
                $last_invoice_id = $row->user_invoice;
            } else {
                $ini_number      = sprintf('%04d', '0');
                $last_invoice_id = $pre_year . '-' . $vendor_pre . '-' . $ini_number;
            }

            $user_invoice_date = date('Y-m-d H:i', strtotime($delivered_date . ' + 0 day'));

            $length = strlen(trim($order_series)) + 6;

            $invoice_id     = (int) substr($last_invoice_id, $length); //this will remove KON
            $vendor_type    = $pre_year . '-' . $vendor_pre . '-'; // this will retuen KON
            $new_invoice_id = $vendor_type . sprintf('%04d', $invoice_id + 1);


            $count = $this->db->get_where('order_user_invoice', array(
                'year' => $pre_year,
                'vendor_id' => $vendor_id
            ))->num_rows();


            if ($count > 0) {
                $data['vendor_id']    = $vendor_id;
                $data['year']         = $pre_year;
                $data['user_invoice'] = $new_invoice_id;
                $data['invoice_date'] = $user_invoice_date;
                $this->db->where('vendor_id', $vendor_id);
                $this->db->where('year', $pre_year);
                $this->db->update('order_user_invoice', $data);
            } else {
                $data1['vendor_id']    = $vendor_id;
                $data1['year']         = $pre_year;
                $data1['user_invoice'] = $new_invoice_id;
                $data1['invoice_date'] = $user_invoice_date;
                $this->db->insert('order_user_invoice', $data1);
            }


            $data = array(
                'user_invoice' => $new_invoice_id,
                'user_invoice_date' => $user_invoice_date
            );
            $this->db->where('id', $order_id);
            $this->db->update('orders', $data);
        } else {
            $new_invoice_id = $order_details->user_invoice;
        }
        return $new_invoice_id;
    }*/
    public function get_order_details_by_id($id){
        $db3 = $this->load->database('crondb3', TRUE);
        $db3->select('id,vendor_id,user_invoice2,delivered_date,user_invoice,user_invoice_url,user_invoice_ci,refunded_date,order_status,user_invoice_cn,cancelled_date,user_invoice_cn_url');
        $db3->where('id', $id);
        $query = $db3->get('orders');
        return $query->row();
    }

      public function user_invoice_manager($last_invoice_id, $user_invoice_date, $pre_year, $vendor_id, $vendor_type, $order_id){
		 $check_inv_exist = $this->db->query("SELECT user_invoice FROM `order_user_invoice` WHERE order_id='$order_id' LIMIT 1");
        if ($check_inv_exist->num_rows() > 0) {
            $row_ = $check_inv_exist->row();
            return $invoice_number = $row_->user_invoice;
        } else {
            $invoice_id_ini  = (int) $last_invoice_id;
            $invoice_id      = $invoice_id_ini + 1;
            $vendor_type     = $vendor_type;
            $new_invoice_id  = $vendor_type . sprintf('%04d', $invoice_id);

			//sql query to check new_invoice_id is already exist in table or not
            $count = $this->db->get_where('order_user_invoice', array(
			    'year' 		   => $pre_year,
                'vendor_id'    => $vendor_id,
                'invoice_id'   => $invoice_id
            ))->num_rows();

            if ($count > 0) {
                // if new_invoice_id already exists
                $this->user_invoice_manager($invoice_id, $user_invoice_date, $pre_year, $vendor_id, $vendor_type, $order_id);
            } else {

				$data['order_id']      = $order_id;
				$data['vendor_id']     = $vendor_id;
				$data['year']		   = $pre_year;
				$data['user_invoice']  = $new_invoice_id;
				$data['invoice_id']    = $invoice_id;
				$data['invoice_date']  = $user_invoice_date;
                $this->db->insert('order_user_invoice', $data);
                return $new_invoice_id;
            }
        }
    }

     public function update_user_invoice_number($order_id) {
        date_default_timezone_set('Asia/Kolkata');
        $db3 = $this->load->database('crondb3', TRUE);
	  	$order_details = $this->get_order_details_by_id($order_id);
	  	if($order_details->user_invoice==NULL){
		$vendor_id=$order_details->vendor_id;
		$delivered_date=$order_details->delivered_date;
		//$delivered_date='2021-05-30';

		$delivery_day=date('m',strtotime($delivered_date. ' + 0 day'));
        $d_year=date('Y',strtotime($delivered_date. ' + 0 day'));
        if ($delivery_day >= 4) {
            $pre_year = (date($d_year)+1);
        }
        else {
            $pre_year = (date($d_year));
        }

        $order_series = $db3->query("SELECT order_series FROM `users` WHERE id='$vendor_id' LIMIT 1")->row()->order_series;
		$vendor_pre   = strtoupper(strtolower($order_series));

        $check_inv_sql = $db3->query("SELECT user_invoice,invoice_id FROM `order_user_invoice` WHERE year='$pre_year' AND vendor_id='$vendor_id' AND invoice_id IS NOT NULL order by id desc LIMIT 1");
        if ($check_inv_sql->num_rows() > 0) {
            $row          = $check_inv_sql->row();
            $last_invoice_id = $row->invoice_id;
        } else {
            $ini_number      = sprintf('%04d', '0');
            $last_invoice_id = $ini_number;
        }

		$vendor_type    =  $pre_year.'-'.$vendor_pre.'-'; // this will retuen KON
        $user_invoice_date=date('Y-m-d H:i',strtotime($delivered_date. ' + 0 day'));

        $new_invoice_id = $this->user_invoice_manager($last_invoice_id, $user_invoice_date, $pre_year, $vendor_id, $vendor_type, $order_id);

       	$data = array();
       	$data = array(
		  'user_invoice' => $new_invoice_id,
		  'user_invoice_date' => $user_invoice_date
		);
		$db3->where('id', $order_id);
		$db3->update('orders', $data);
	  	}
	  	else{
	  	  $new_invoice_id=$order_details->user_invoice;
	  	}
        return $new_invoice_id;
    }

    public function fetch_user_invoice_cn($order_id)
    {

        $order             = $this->order_model->get_order($order_id);
        $shipping          = $this->pdf_model->get_order_shipping($order_id);
        //$vendor_billing= $this->pdf_model->get_vendor_billing_details($order->vendor_id);
        //print_r($shipping);
        $price_shipping    = $order->price_shipping;
        $price_shipping_bt = $order->price_shipping / 1.18;
        $shipping_gst      = $order->price_shipping - $price_shipping_bt;

        if ($order->order_status == 'cancelled') {
            $cancelled_date = $order->cancelled_date;
        } else {
            $cancelled_date = $order->refunded_date;
        }


        if ($shipping['shipping_state'] == 'Maharashtra'):
            $is_igst = 0;
        else:
            $is_igst = 1;
        endif;

        $output = '<body class="invoice txtup">
      <div class="panel-body" id="page-wrap">
        <table id="invoice">
            <thead>
            <tr>
                <th class="head-img text-left">
                  <img src="https://kirtibook.in/images/logo-pdf.png" class="logo">
                </th>
                <th class="text-right head">
                  <h3><b>Credit Note</b></h3>
                  <p>(Original for Recipient)</p>
                  <p><b>CN Number :</b>' . $order->user_invoice_cn . '</p>
                  <p><b>CN Date :</b>' . date('d-m-Y', strtotime($cancelled_date)) . '</p>
                </th>
            </tr>
            </thead>
        </table>
        <table id="invoice_3" class="m-t-10 table " style="table-layout: fixed;">
            <thead>
            <tr>
                <th class="p-l-r text-left" style="width: 36%;">
                    <p><b>Kirti Book Store</b></p>
                    <p> Shop No. A-1, BBC Tower, below Bank Of India, near City International School, Aundh, Pune, Maharashtra 411007</p>
                    <p class="m-t-20"><b>email ID: </b>info@kirtibook.in</p>
                    <p><b>Contact No.: </b> 8380082390</p>
                    <p><b>PAN : </b> -</p>
                    <p><b>GSTIN : </b> -</p>
                </th>
                <th class="p-l-r text-left"  style="width: 36%;">
                    <p><b>Bill To : </b>' . $shipping['name'] . '</p>
                    <p><b>Address:</b> ' . $shipping['address'] . ', ' . $shipping['shipping_city'] . '-' . $shipping['pincode'] . ', ' . $shipping['shipping_state'] . '.  Landmark- ' . $shipping['landmark'] . '</p>
                    <p><b>email ID : </b>' . $shipping['email'] . '</p>
                    <p><b>Contact No. : </b>' . $shipping['phone'] . '</p>
                    <p class="m-t-20"><b>GSTIN:</b> URP</p>
                    <p><b>Place of Supply : </b>' . $shipping['shipping_state'] . '</p>

                </th>
            </tr>
            </thead>
        </table>
        <table id="invoice_1" class="m-t-10 product table table-bordered">
            <thead>
            <tr>
                <th rowspan="2">Sl. No</th>
                <th rowspan="2">Description of<br/> the Service</th>
                <th rowspan="2">Original Invoice No.</th>
                <th rowspan="2">Amount<br><small>(INR)</small></th>
                <th rowspan="2">HSN / SAC</th>';
        $output .= '<th colspan="2">CGST</span></th>
                <th colspan="2">SGST</th>';
        $output .= '<th colspan="2">IGST</th>';
        $output .= '<th rowspan="2">Amount(INR) <br>(incl. Tax)</small></th>
            </tr>
            <tr>';
        $output .= '<th>Rate</th>
                <th>Amt(INR)</th>
                <th>Rate</th>
                <th>Amt(INR)</th>
                <th>Rate</th>
                <th>Amt(INR)</th>
             </tr>
            </thead>
            <tbody>

                <tr>
                    <td>1.</td>
                    <td>Product Handling Charges</td>
                    <td>' . $order->user_invoice . '</td>
                    <td>' . price_format_decimal($price_shipping_bt) . '</td>
                    <td>996812</td>';

        if ($is_igst == 0):
            $output .= '
                    <td>9%</td>
                    <td>' . price_format_decimal($shipping_gst / 2) . '</td>
                    <td>9%</td>
                    <td>' . price_format_decimal($shipping_gst / 2) . '</td>
                    <td>18%</td><td>-</td>';
        else:
            $output .= '<td>9%</td><td>-</td><td>9%</td><td>-</td><td>18%</td>
                    <td>' . price_format_decimal($shipping_gst) . '</td>';
        endif;

        $output .= '<td>' . price_format_decimal($price_shipping) . '</td></tr>
            <tr class="border-top">
                <td colspan="12" class="left"><b>Amount in Words:</b> ' . rupees_word($price_shipping) . '</td>
            </tr>
        </tbody>
    </table>
    <table id="invoice" class="m-t-10">
        <tbody>
            <tr class="border-top">
                <td colspan="9" class="left">
                <p  class="text-left text-gray">E.&O.E. </p>
                <p  class="text-left"><b>Note: </b> Whether tax is payable on Reverse Charge – No.
                <br/>    Please note that this invoice is not a demand for payment</p>
                </td>
                <td class="right" colspan="3">
                <p>
                <b>For Kirti Book Store:</b><br>
                <img src="https://kirtibook.in/images/sign.PNG" style="max-width: 200px;height: 45px;"><br>
                <b>Authorized Signatory</b>
                </p>
                </td>
            </tr>
        </tbody>
    </table>';
        return $output;
    }


     public function user_invoice_cn_manager($last_invoice_id, $user_invoice_date, $pre_year, $vendor_id, $vendor_type, $order_id){
		 $check_inv_exist = $this->db->query("SELECT user_invoice_cn FROM `order_user_invoice_cn` WHERE order_id='$order_id' LIMIT 1");
        if ($check_inv_exist->num_rows() > 0) {
            $row_ = $check_inv_exist->row();
            return $invoice_number = $row_->user_invoice_cn;
        } else {
            $invoice_id_ini  = (int) $last_invoice_id;
            $invoice_id      = $invoice_id_ini + 1;
            $vendor_type     = $vendor_type;
            $new_invoice_id  = $vendor_type . sprintf('%04d', $invoice_id);

			//sql query to check new_invoice_id is already exist in table or not
            $count = $this->db->get_where('order_user_invoice_cn', array(
			    'year' 		   => $pre_year,
                'vendor_id'    => $vendor_id,
                'invoice_id'   => $invoice_id
            ))->num_rows();

            if ($count > 0) {
                // if new_invoice_id already exists
                $this->user_invoice_cn_manager($invoice_id, $user_invoice_date, $pre_year, $vendor_id, $vendor_type, $order_id);
            } else {
				$data['order_id']      	 = $order_id;
				$data['vendor_id']     	 = $vendor_id;
				$data['year']		  	 = $pre_year;
				$data['user_invoice_cn'] = $new_invoice_id;
				$data['invoice_id']      = $invoice_id;
				$data['invoice_date']    = $user_invoice_date;
				$data['last_modified']   =  date('Y-m-d H:i');
                $this->db->insert('order_user_invoice_cn', $data);
                return $new_invoice_id;
            }
        }
    }


    public function update_user_invoice_cn_number($order_id) {
        date_default_timezone_set('Asia/Kolkata');
        $db3 = $this->load->database('crondb3', TRUE);
	  	$order_details = $this->get_order_details_by_id($order_id);

	  	if($order_details->user_invoice_cn==NULL){
		 $vendor_id=$order_details->vendor_id;
		 if($order_details->order_status=='cancelled'){
		  $cancelled_date=$order_details->cancelled_date;
		}
		else{
		  $cancelled_date=$order_details->refunded_date;
		}

		//$delivered_date='2021-05-30';

		$cancelled_date=date('Y-m-d',strtotime($cancelled_date));
		$cancelled_day=date('m',strtotime($cancelled_date. ' + 0 day'));
        $d_year=date('Y',strtotime($cancelled_date. ' + 0 day'));

        if ($cancelled_day >= 4) {
            $pre_year = (date($d_year)+1);
        }
        else {
            $pre_year = (date($d_year));
        }

        $order_series = $db3->query("SELECT order_series FROM `users` WHERE id='$vendor_id' LIMIT 1")->row()->order_series;
		$vendor_pre   = strtoupper(strtolower($order_series));

		$check_inv_sql = $db3->query("SELECT user_invoice_cn,invoice_id FROM `order_user_invoice_cn` WHERE year='$pre_year' AND vendor_id='$vendor_id' AND invoice_id IS NOT NULL order by id desc LIMIT 1");
        if ($check_inv_sql->num_rows() > 0) {
            $row         	 = $check_inv_sql->row();
            $last_invoice_id = $row->invoice_id;
        } else {
            $ini_number      = sprintf('%04d', '0');
            $last_invoice_id = $ini_number;
        }

        $vendor_type    =  $pre_year.'-'.$vendor_pre.'-CN-';
        $user_invoice_date=date('Y-m-d H:i',strtotime($cancelled_date. ' + 0 day'));

        $new_invoice_id = $this->user_invoice_cn_manager($last_invoice_id, $user_invoice_date, $pre_year, $vendor_id, $vendor_type, $order_id);

       	$data = array(
		  'user_invoice_cn' => $new_invoice_id,
		);
		$db3->where('id', $order_id);
		$db3->update('orders', $data);
	  	}
	  	else{
	  	  $new_invoice_id=$order_details->user_invoice_cn;
	  	}
        return $new_invoice_id;
    }

  /*  public function update_user_invoice_cn_number($order_id)
    {

        date_default_timezone_set('Asia/Kolkata');
        $date_created  = date('Y-m-d H:i');
        $order_details = $this->order_model->get_order($order_id);

        if ($order_details->user_invoice_cn == NULL) {
            $vendor_id = $order_details->vendor_id;

            if ($order_details->order_status == 'cancelled') {
                $cancelled_date = $order_details->cancelled_date;
            } else {
                $cancelled_date = $order_details->refunded_date;
            }

            //$cancelled_date='2021-05-30';
            $cancelled_date = date('Y-m-d', strtotime($cancelled_date));
            $cancelled_day  = date('m', strtotime($cancelled_date . ' + 0 day'));
            $d_year         = date('Y', strtotime($cancelled_date . ' + 0 day'));

            if ($cancelled_day >= 4) {
                $pre_year = (date($d_year) + 1);
            } else {
                $pre_year = (date($d_year));
            }

            $order_series = $this->db->query("SELECT order_series FROM `users` WHERE id='$vendor_id' LIMIT 1")->row()->order_series;
            $vendor_pre   = strtoupper(strtolower($order_series));


            $check_inv_sql = $this->db->query("SELECT user_invoice_cn FROM `order_user_invoice_cn` WHERE year='$pre_year' AND vendor_id='$vendor_id' order by id desc LIMIT 1");
            if ($check_inv_sql->num_rows() > 0) {
                $row             = $check_inv_sql->row();
                $last_invoice_id = $row->user_invoice_cn;
            } else {
                $ini_number      = sprintf('%04d', '0');
                $last_invoice_id = $pre_year . '-' . $vendor_pre . '-CN-' . $ini_number;
            }

            $user_invoice_date = date('Y-m-d H:i', strtotime($cancelled_date . ' + 0 day'));

            $length = strlen(trim($order_series)) + 9;

            $invoice_id     = (int) substr($last_invoice_id, $length); //this will remove KON
            $vendor_type    = $pre_year . '-' . $vendor_pre . '-CN-'; // this will retuen KON
            $new_invoice_id = $vendor_type . sprintf('%04d', $invoice_id + 1);


            $count = $this->db->get_where('order_user_invoice_cn', array(
                'year' => $pre_year,
                'vendor_id' => $vendor_id
            ))->num_rows();

            if ($count > 0) {
                $data['order_id']        = $order_id;
                $data['vendor_id']       = $vendor_id;
                $data['year']            = $pre_year;
                $data['user_invoice_cn'] = $new_invoice_id;
                $this->db->where('vendor_id', $vendor_id);
                $this->db->where('year', $pre_year);
                $this->db->update('order_user_invoice_cn', $data);
            } else {
                $data1['order_id']        = $order_id;
                $data1['vendor_id']       = $vendor_id;
                $data1['year']            = $pre_year;
                $data1['user_invoice_cn'] = $new_invoice_id;
                $this->db->insert('order_user_invoice_cn', $data1);
            }


            $data = array(
                'user_invoice_cn' => $new_invoice_id
            );
            $this->db->where('id', $order_id);
            $this->db->update('orders', $data);
        } else {
            $new_invoice_id = $order_details->user_invoice_cn;
        }
        return $new_invoice_id;
    }
    */

    public function update_user_invoice_cn_generated($order_id, $file_url)
    {
        $data_order = array(
            'user_invoice_cn_url' => $file_url
        );
        $this->db->where('id', $order_id);
        $this->db->update('orders', $data_order);
    }





    public function fetch_invoice_cn_report($order_id)
    {
        $order          = $this->pdf_model->get_invoice_bookset_order_details($order_id);
        $order_date     = date('d-m-Y', strtotime($order->created_at));
        $cancelled_date = date('d-m-Y', strtotime($order->cancelled_date));

        $vendor         = $this->pdf_model->get_vendor_details($order->vendor_id);
        $vendor_billing = $this->pdf_model->get_vendor_billing_details($order->vendor_id);
        $shipping       = $this->pdf_model->get_order_shipping($order_id);
        $order_student  = $this->pdf_model->get_order_student_details($order_id);
        $student_name   = $order_student->f_name . ' ' . $order_student->m_name . ' ' . $order_student->s_name;
        if ($vendor_billing['state'] == $shipping['shipping_state']):
            $is_igst = 0;
        else:
            $is_igst = 1;
        endif;
        $vendor_sign    = vendor_url() . 'uploads/vendor/' . $vendor['signature'];
        $price_shipping = price_format_decimal($order->price_shipping);
        $total_amt      = $order->price_total - $price_shipping;

        if ($order->order_type == 'bookset') {
            $output          = '<body class="invoice txtup">
          <div class="panel-body" id="page-wrap">

            <table id="invoice">
                <thead>
                <tr>
                    <th class="head-img text-left" style="width: 30%;">
                      <p>Ordered through</p>
                      <img src="https://kirtibook.in/images/logo-pdf.png" class="logo">
                    </th>
                    <th class="text-right head order_no">
                      <div class="box"> <h5>Original Invoice No: <br/>' . $order->invoice_no . '</h5></div>
                     <div class="box"> <h5>Order No: ' . $order->order_number . '</h5>
                     <h5>Order Date: ' . $order_date . '</h5></div>
                     </th>
                    <th class="text-right head" style="width: 36%;">
                      <h3><b>Credit Note</b></h3>
                      <p>(Original for Recipient)</p>
                      <p class="m-t-20"><b  class="bold font12">CN Number:' . $order->invoice_cn . '</b><br/>
                      <b  class="bold font12">CN Date : ' . $cancelled_date . '</b></p>
                    </th>
                </tr>
                </thead>
            </table>
            <table id="invoice_3" class="m-t-10 table table-bordered" style="table-layout: fixed;">
                <thead>
                <tr>
                    <th class="p-l-r text-left" style="width: 36%;">
                        <p><b>Sold By: </b>' . $vendor['company_name'] . '</p>
                        <p><b>Address: </b> ' . $vendor['address'] . '</p>
                        <p class="m-t-10"><b>PAN: </b>' . $vendor_billing['pan'] . '</p>
                        <p><b>GSTIN: </b> ' . $vendor_billing['gst'] . '</p>
                        <p><b>Place of Supply: </b> ' . $vendor_billing['state'] . '</p>
                    </th>
                    <th class="p-l-r text-left"  style="width: 36%;">
                        <p><b>Purchased by:</b> ' . $shipping['name'] . '</p>
                        <p><b>Address:</b> ' . $shipping['address'] . ', ' . $shipping['shipping_city'] . '-' . $shipping['pincode'] . ', ' . $shipping['shipping_state'] . '.  Landmark- ' . $shipping['landmark'] . '</p>
                        <p><b>GSTIN:</b> URP</p>

                    </th>
                    <th class="p-l-r text-left">
                        <p><b>Student Details:</b></p>
                        <p><b>Name:</b> ' . $student_name . '</p>
                        <p><b>School:</b> ' . $order->school_name . '</p>
                        <p><b>Grade:</b> ' . $order->grade_name . '</p>
                    </th>
                </tr>
                </thead>
            </table>
            <table id="invoice_1" class="m-t-10 product table table-bordered">
            <thead>
                <tr>
                    <th rowspan="2">#</th>
                    <th rowspan="2">Returned/Cancelled</th>
                    <th rowspan="2">Qty<br> <small>(nos.)</small></th>
                    <th rowspan="2">Amount<br><small>(INR)</small></th>
                    <th rowspan="2">HSN</th>
                    <th colspan="2">CGST</span></th>
                    <th colspan="2">SGST</th>
                    <th colspan="2">IGST</th>
                    <th rowspan="2">Amount <br>(incl. Tax)</small></th>
                </tr>
                <tr>
                    <th>%</th>
                    <th>Amt</th>
                    <th>%</th>
                    <th>Amt</th>
                    <th>%</th>
                    <th>Amt</th>
                </tr>
                </thead>
                <tbody>';
            $package_details = $this->pdf_model->get_invoice_package_by_id($order_id);
            $sr_no           = 1;
            foreach ($package_details as $package_) {
                $output .= '<tr class="border-top">
                    <td>' . $sr_no . '.</td>
                    <td class="left">
                        <b>' . $package_['package_name'] . '</b>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>';

                foreach ($package_['products'] as $product) {
                    $output .= '<tr>
                    <td></td>
                    <td class="left ">
                        ' . $product['name'] . '
                    </td>
                    <td> ' . $product['quantity'] . '    </td>
                    <td> ' . $product['total_price_excl'] . ' </td>
                    <td> ' . $product['hsn'] . ' </td>';

                    if ($is_igst == 0):
                        $output .= '<td> ' . ($product['gst'] / 2) . '%  </td>
                        <td> ' . price_format_decimal($product['gst_amt'] / 2) . '  </td>
                        <td> ' . ($product['gst'] / 2) . '%  </td>
                        <td> ' . price_format_decimal($product['gst_amt'] / 2) . '  </td>
                        <td> -  </td>
                        <td> -  </td>';
                    else:
                        $output .= '<td> -  </td><td> -  </td><td> -  </td><td> -  </td>
                        <td> ' . $product['gst'] . '% </td>
                        <td> ' . $product['gst_amt'] . '  </td>';
                    endif;
                    $output .= '<td> ' . $product['total_price'] . ' </td>
                    </tr>';

                }
                $sr_no++;
            }

            if ($order->price_discount > 0) {
                $output .= '<tr>
                    <td>' . $sr_no . '.</td>
                    <td class="left ">
                    Discount
                    </td>
                    <td> -   </td>
                    <td> ' . $order->price_discount . ' </td>
                    <td> - </td>
                     <td> -  </td>
                     <td> -  </td>
                     <td> -  </td>
                     <td> -  </td>
                    <td> - </td>
                    <td> - </td>
                    <td> ' . $order->price_discount . '  </td>
                    </tr>';
            }

            $output .= '
                <tr class="border-top">
                    <td colspan="9" class="left"><b>Amount in Words:</b> ' . rupees_word($total_amt) . '</td>
                    <td colspan="2" class="right"><b>Total Amount</b></td>
                    <td><b>' . price_format_decimal($total_amt) . '</b></td>
                </tr>

                <tr class="border-top">
                    <td colspan="9" class="left">
                    <p  class="text-left text-gray">Whether tax is payable on reverse charge basis - "No" <span class="pull-right"> E.&O.E.</span> </p>

                    <p  class="text-left"><b>Declaration:</b> <small>The goods sold are intended for end user consumption and not for resale. Please note that this invoice is not a demand for payment </small> </p>
                    <p  class="text-left"><b>Note:</b> <small>Out of Stock items(if any) will be handed over in the school/classroom. </small> </p>
                    </td>
                    <td class="right" colspan="3">
                    <p>
                    <b>For ' . $vendor['company_name'] . ':</b><br>
                    <img src="' . $vendor_sign . '" style="max-width: 200px;height: 70px;"><br>
                    <b>Authorized Signatory</b>
                    </p>
                    </td>
                  </tr>
                </tbody>
            </table>';

            //      $second_shipments= $this->pdf_model->get_invoice_second_shipment_by_id($order_id);


            $output .= '    </div></body>';
            return $output;
        } else {
            $output          = '<body class="invoice txtup">
          <div class="panel-body" id="page-wrap">

            <table id="invoice">
                <thead>
                 <tr>
                    <th class="head-img text-left" style="width: 30%;">
                      <p>Ordered through</p>
                      <img src="https://kirtibook.in/images/logo-pdf.png" class="logo">
                    </th>
                    <th class="text-right head order_no">
                      <div class="box"> <h5>Original Invoice No: <br/>' . $order->invoice_no . '</h5></div>
                     <div class="box"> <h5>Order No: ' . $order->order_number . '</h5>
                     <h5>Order Date: ' . $order_date . '</h5></div>
                     </th>
                    <th class="text-right head" style="width: 36%;">
                      <h3><b>Credit Note</b></h3>
                      <p>(Original for Recipient)</p>
                      <p class="m-t-20"><b  class="bold font12">CN Number:' . $order->invoice_cn . '</b><br/>
                      <b  class="bold font12">CN Date : ' . $cancelled_date . '</b></p>
                    </th>
                </tr>
                </thead>
            </table>
            <table id="invoice_3" class="m-t-10 table table-bordered" style="table-layout: fixed;">
                <thead>
                <tr>
                    <th class="p-l-r text-left" style="width: 50%;">
                        <p><b>Sold By: </b>' . $vendor['company_name'] . '</p>
                        <p><b>Address: </b> ' . $vendor['address'] . '</p>
                        <p class="m-t-10"><b>PAN: </b>' . $vendor_billing['pan'] . '</p>
                        <p><b>GSTIN: </b> ' . $vendor_billing['gst'] . '</p>
                        <p><b>Place of Supply: </b> ' . $vendor_billing['state'] . '</p>
                    </th>
                    <th class="p-l-r text-left"  style="width: 50%;">
                        <p><b>Purchased By:</b> ' . $shipping['name'] . '</p>
                        <p><b>Address:</b> ' . $shipping['address'] . ', ' . $shipping['shipping_city'] . '-' . $shipping['pincode'] . ', ' . $shipping['shipping_state'] . '.  Landmark- ' . $shipping['landmark'] . '</p>
                        <p><b>GSTIN:</b> URP</p>

                    </th>
                </tr>
                </thead>
            </table>
            <table id="invoice_1" class="m-t-10 product table table-bordered">
            <thead>
                <tr>
                    <th rowspan="2">#</th>
                    <th rowspan="2">Returned/Cancelled</th>
                    <th rowspan="2">Qty<br> <small>(nos.)</small></th>
                    <th rowspan="2">Amount<br><small>(INR)</small></th>
                    <th rowspan="2">HSN</th>
                    <th colspan="2">CGST</span></th>
                    <th colspan="2">SGST</th>
                    <th colspan="2">IGST</th>
                    <th rowspan="2">Amount <br>(incl. Tax)</small></th>
                </tr>
                <tr>
                    <th>%</th>
                    <th>Amt</th>
                    <th>%</th>
                    <th>Amt</th>
                    <th>%</th>
                    <th>Amt</th>
                </tr>
                </thead>
                <tbody>';
            $package_details = $this->pdf_model->get_invoice_product_by_id($order_id);
            $sr_no           = 1;
            foreach ($package_details as $package_) {
                foreach ($package_['products'] as $product) {
                    $size_name = ($order->size_name != '' ? ' Size-' . $order->size_name : '');

                    $output .= '<tr>
                    <td>' . $sr_no . '.</td>
                    <td class="left ">
                        ' . $product['name'] . ' ' . $size_name . '
                    </td>
                    <td> ' . $product['quantity'] . '    </td>
                    <td> ' . $product['total_price_excl'] . ' </td>
                    <td> ' . $product['hsn'] . ' </td>';

                    if ($is_igst == 0):
                        $output .= '<td> ' . ($product['gst'] / 2) . '%  </td>
                        <td> ' . price_format_decimal($product['gst_amt'] / 2) . '  </td>
                        <td> ' . ($product['gst'] / 2) . '%  </td>
                        <td> ' . price_format_decimal($product['gst_amt'] / 2) . '  </td>
                        <td> -  </td>
                        <td> -  </td>';
                    else:
                        $output .= '<td> -  </td><td> -  </td><td> -  </td><td> -  </td>
                        <td> ' . $product['gst'] . '% </td>
                        <td> ' . $product['gst_amt'] . '  </td>';
                    endif;
                    $output .= '<td> ' . $product['total_price'] . ' </td>
                    </tr>';

                    $sr_no++;
                }
                $sr_no++;
            }

            if ($order->price_discount > 0) {
                $output .= '<tr>
                    <td>' . $sr_no . '.</td>
                    <td class="left ">
                    Discount
                    </td>
                    <td> -   </td>
                    <td> ' . $order->price_discount . ' </td>
                    <td> - </td>
                     <td> -  </td>
                     <td> -  </td>
                     <td> -  </td>
                     <td> -  </td>
                    <td> - </td>
                    <td> - </td>
                    <td> ' . $order->price_discount . '  </td>
                    </tr>';
            }

            $output .= '
                <tr class="border-top">
                    <td colspan="9" class="left"><b>Amount in Words:</b> ' . rupees_word($total_amt) . '</td>
                    <td colspan="2" class="right"><b>Total Amount</b></td>
                    <td><b>' . price_format_decimal($total_amt) . '</b></td>
                </tr>

                <tr class="border-top">
                    <td colspan="9" class="left">
                    <p  class="text-left text-gray">Whether tax is payable on reverse charge basis - "No" <span class="pull-right"> E.&O.E.</span> </p>

                    <p  class="text-left"><b>Declaration:</b> <small>The goods sold are intended for end user consumption and not for resale. Please note that this invoice is not a demand for payment </small> </p>
                    <p  class="text-left"><b>Note:</b> <small>Out of Stock items(if any) will be handed over in the school/classroom. </small> </p>
                    </td>
                    <td class="right" colspan="3">
                    <p>
                    <b>For ' . $vendor['company_name'] . ':</b><br>
                    <img src="' . $vendor_sign . '" style="max-width: 200px;height: 70px;"><br>
                    <b>Authorized Signatory</b>
                    </p>
                    </td>
                  </tr>
                </tbody>
            </table>';

            $output .= '    </div></body>';
            return $output;
        }

    }

    public function update_invoice_cn_number($order_id)
    {
        date_default_timezone_set('Asia/Kolkata');
        $date_created  = date('Y-m-d H:i');
        $order_details = $this->order_model->get_order($order_id);

        if ($order_details->invoice_cn == NULL) {
            $vendor_id      = $order_details->vendor_id;
            $cancelled_date = $order_details->cancelled_date;
            $cancelled_date = date('Y-m-d', strtotime($cancelled_date));

            $order_series = $this->db->query("SELECT order_series FROM `users` WHERE id='$vendor_id' LIMIT 1")->row()->order_series;
            $vendor_pre   = strtoupper(strtolower($order_series));

            $check_inv_sql = $this->db->query("SELECT invoice_cn FROM `order_invoice_cn` WHERE vendor_id='$vendor_id' order by id desc LIMIT 1");
            if ($check_inv_sql->num_rows() > 0) {
                $row             = $check_inv_sql->row();
                $last_invoice_id = $row->invoice_cn;
            } else {
                $ini_number      = sprintf('%04d', '0');
                $last_invoice_id = $vendor_pre . '-CN-' . $ini_number;
            }

            $user_invoice_date = date('Y-m-d H:i', strtotime($cancelled_date . ' + 0 day'));
            $length            = strlen(trim($order_series)) + 4;

            $invoice_id     = (int) substr($last_invoice_id, $length); //this will remove KON
            $vendor_type    = $vendor_pre . '-CN-'; // this will retuen KON
            $new_invoice_id = $vendor_type . sprintf('%04d', $invoice_id + 1);

            $count = $this->db->get_where('order_invoice_cn', array(
                'vendor_id' => $vendor_id
            ))->num_rows();

            if ($count > 0) {
                $data['order_id']   = $order_id;
                $data['vendor_id']  = $vendor_id;
                $data['invoice_cn'] = $new_invoice_id;
                $this->db->where('vendor_id', $vendor_id);
                $this->db->update('order_invoice_cn', $data);
            } else {
                $data1['order_id']   = $order_id;
                $data1['vendor_id']  = $vendor_id;
                $data1['invoice_cn'] = $new_invoice_id;
                $this->db->insert('order_invoice_cn', $data1);
            }


            $data = array(
                'invoice_cn' => $new_invoice_id
            );
            $this->db->where('id', $order_id);
            $this->db->update('orders', $data);
        } else {
            $new_invoice_id = $order_details->invoice_cn;
        }
        return $new_invoice_id;
    }



    public function update_invoice_cn_generated($order_id, $file_url)
    {
        $data_order = array(
            'invoice_cn_url' => $file_url
        );
        $this->db->where('id', $order_id);
        $this->db->update('orders', $data_order);
    }



    public function fetch_user_invoice_ci($order_id)
    {
        $order        = $this->order_model->get_order($order_id);
        $shipping     = $this->pdf_model->get_order_shipping($order_id);
        //$vendor_billing= $this->pdf_model->get_vendor_billing_details($order->vendor_id);
        //print_r($shipping);
        $final_amount = 0;

        if ($order->order_status == 'cancelled') {
            $price_total    = $order->price_total;
            $cancelled_date = $order->refunded_date;
        } else {
            //manual
            $price_total    = $order->price_shipping;
            $cancelled_date = $order->refunded_date;
        }



        $refund_amount   = $order->refund_amount;
        $final_amount    = price_format_decimal($price_total - $refund_amount);
        $final_amount_bt = $final_amount / 1.18;
        $shipping_gst    = $final_amount - $final_amount_bt;

        if ($shipping['shipping_state'] == 'Maharashtra'):
            $is_igst = 0;
        else:
            $is_igst = 1;
        endif;

        $output = '<body class="invoice txtup">
      <div class="panel-body" id="page-wrap">
        <table id="invoice">
            <thead>
            <tr>
                <th class="head-img text-left">
                  <img src="https://kirtibook.in/images/logo-pdf.png" class="logo">
                </th>
                <th class="text-right head">
                  <h3><b>Tax Invoice</b></h3>
                  <p>(Original for Recipient)</p>
                  <p><b>CI Number :</b>' . $order->user_invoice_ci . '</p>
                  <p><b>CI Date :</b>' . date('d-m-Y', strtotime($cancelled_date)) . '</p>
                </th>
            </tr>
            </thead>
        </table>
        <table id="invoice_3" class="m-t-10 table " style="table-layout: fixed;">
            <thead>
            <tr>
                <th class="p-l-r text-left" style="width: 36%;">
                    <p><b>Kirti Book Store</b></p>
                    <p> Shop No. A-1, BBC Tower, below Bank Of India, near City International School, Aundh, Pune, Maharashtra 411007</p>
                    <p class="m-t-20"><b>email ID: </b>info@kirtibook.in</p>
                    <p><b>Contact No.: </b> 8380082390</p>
                    <p><b>PAN : </b> -</p>
                    <p><b>GSTIN : </b> -</p>
                </th>
                <th class="p-l-r text-left"  style="width: 36%;">
                    <p><b>Bill To : </b>' . $shipping['name'] . '</p>
                    <p><b>Address:</b> ' . $shipping['address'] . ', ' . $shipping['shipping_city'] . '-' . $shipping['pincode'] . ', ' . $shipping['shipping_state'] . '.  Landmark- ' . $shipping['landmark'] . '</p>
                    <p><b>email ID : </b>' . $shipping['email'] . '</p>
                    <p><b>Contact No. : </b>' . $shipping['phone'] . '</p>
                    <p class="m-t-20"><b>GSTIN:</b> URP</p>
                    <p><b>Place of Supply : </b>' . $shipping['shipping_state'] . '</p>

                </th>
            </tr>
            </thead>
        </table>
        <table id="invoice_1" class="m-t-10 product table table-bordered">
            <thead>
            <tr>
                <th rowspan="2">Sl. No</th>
                <th rowspan="2">Description of<br/> the Service</th>
                <th rowspan="2">Amount<br><small>(INR)</small></th>
                <th rowspan="2">HSN / SAC</th>';
        $output .= '<th colspan="2">CGST</span></th>
                <th colspan="2">SGST</th>';
        $output .= '<th colspan="2">IGST</th>';
        $output .= '<th rowspan="2">Amount(INR) <br>(incl. Tax)</small></th>
            </tr>
            <tr>';
        $output .= '<th>Rate</th>
                <th>Amt(INR)</th>
                <th>Rate</th>
                <th>Amt(INR)</th>
                <th>Rate</th>
                <th>Amt(INR)</th>
             </tr>
            </thead>
            <tbody>

                <tr>
                    <td>1.</td>
                    <td>Cancellation Charges</td>
                    <td>' . price_format_decimal($final_amount_bt) . '</td>
                    <td>999799</td>';

        if ($is_igst == 0):
            $output .= '
                    <td>9%</td>
                    <td>' . price_format_decimal($shipping_gst / 2) . '</td>
                    <td>9%</td>
                    <td>' . price_format_decimal($shipping_gst / 2) . '</td>
                    <td>18%</td><td>-</td>';
        else:
            $output .= '<td>9%</td><td>-</td><td>9%</td><td>-</td><td>18%</td>
                    <td>' . price_format_decimal($shipping_gst) . '</td>';
        endif;

        $output .= '<td>' . price_format_decimal($final_amount) . '</td></tr>
            <tr class="border-top">
                <td colspan="12" class="left"><b>Amount in Words:</b> ' . rupees_word($final_amount) . '</td>
            </tr>
        </tbody>
    </table>
    <table id="invoice" class="m-t-10">
        <tbody>
            <tr class="border-top">
                <td colspan="9" class="left">
                <p  class="text-left text-gray">E.&O.E. </p>
                <p  class="text-left"><b>Note: </b> Whether tax is payable on Reverse Charge – No.
                <br/>    Please note that this invoice is not a demand for payment</p>
                </td>
                <td class="right" colspan="3">
                <p>
                <b>For Kirti Book Store:</b><br>
                <img src="https://kirtibook.in/images/sign.PNG" style="max-width: 200px;height: 45px;"><br>
                <b>Authorized Signatory</b>
                </p>
                </td>
            </tr>
        </tbody>
    </table>';
        return $output;
    }


    /*public function update_user_invoice_ci_number($order_id)
    {

        date_default_timezone_set('Asia/Kolkata');
        $date_created  = date('Y-m-d H:i');
        $order_details = $this->order_model->get_order($order_id);

        if ($order_details->user_invoice_ci == NULL) {
            $vendor_id = $order_details->vendor_id;
            //$cancelled_date='2021-05-30';

            if ($order_details->order_status == 'cancelled') {
                $cancelled_date = $order_details->refunded_date;
            } else {
                $cancelled_date = $order_details->refunded_date;
            }

            $cancelled_date = date('Y-m-d', strtotime($cancelled_date));
            $cancelled_day  = date('m', strtotime($cancelled_date . ' + 0 day'));
            $d_year         = date('Y', strtotime($cancelled_date . ' + 0 day'));

            if ($cancelled_day >= 4) {
                $pre_year = (date($d_year) + 1);
            } else {
                $pre_year = (date($d_year));
            }

            $order_series = $this->db->query("SELECT order_series FROM `users` WHERE id='$vendor_id' LIMIT 1")->row()->order_series;
            $vendor_pre   = strtoupper(strtolower($order_series));

            $check_inv_sql = $this->db->query("SELECT user_invoice_ci FROM `order_user_invoice_ci` WHERE year='$pre_year' AND vendor_id='$vendor_id' order by id desc LIMIT 1");
            if ($check_inv_sql->num_rows() > 0) {
                $row             = $check_inv_sql->row();
                $last_invoice_id = $row->user_invoice_ci;
            } else {
                $ini_number      = sprintf('%04d', '0');
                $last_invoice_id = $pre_year . '-' . $vendor_pre . '-CI-' . $ini_number;
            }

            $user_invoice_date = date('Y-m-d H:i', strtotime($cancelled_date . ' + 0 day'));

            $length = strlen(trim($order_series)) + 9;

            $invoice_id     = (int) substr($last_invoice_id, $length); //this will remove KON
            $vendor_type    = $pre_year . '-' . $vendor_pre . '-CI-'; // this will retuen KON
            $new_invoice_id = $vendor_type . sprintf('%04d', $invoice_id + 1);


            $count = $this->db->get_where('order_user_invoice_ci', array(
                'year' => $pre_year,
                'vendor_id' => $vendor_id
            ))->num_rows();

            if ($count > 0) {
                $data['order_id']        = $order_id;
                $data['vendor_id']       = $vendor_id;
                $data['year']            = $pre_year;
                $data['user_invoice_ci'] = $new_invoice_id;
                $this->db->where('vendor_id', $vendor_id);
                $this->db->where('year', $pre_year);
                $this->db->update('order_user_invoice_ci', $data);
            } else {
                $data1['order_id']        = $order_id;
                $data1['vendor_id']       = $vendor_id;
                $data1['year']            = $pre_year;
                $data1['user_invoice_ci'] = $new_invoice_id;
                $this->db->insert('order_user_invoice_ci', $data1);
            }


            $data = array(
                'user_invoice_ci' => $new_invoice_id
            );
            $this->db->where('id', $order_id);
            $this->db->update('orders', $data);
        } else {
            $new_invoice_id = $order_details->user_invoice_ci;
        }
        return $new_invoice_id;
    }
    */


    public function user_invoice_ci_manager($last_invoice_id, $user_invoice_date, $pre_year, $vendor_id, $vendor_type, $order_id){
		$check_inv_exist = $this->db->query("SELECT user_invoice_ci FROM `order_user_invoice_ci` WHERE order_id='$order_id' LIMIT 1");
        if ($check_inv_exist->num_rows() > 0) {
            $row_ = $check_inv_exist->row();
            return $invoice_number = $row_->user_invoice_ci;
        } else {
            $invoice_id_ini  = (int) $last_invoice_id;
            $invoice_id      = $invoice_id_ini + 1;
            $vendor_type     = $vendor_type;
            $new_invoice_id  = $vendor_type . sprintf('%04d', $invoice_id);

			//sql query to check new_invoice_id is already exist in table or not
            $count = $this->db->get_where('order_user_invoice_ci', array(
			    'year' 		   => $pre_year,
                'vendor_id'    => $vendor_id,
                'invoice_id'   => $invoice_id
            ))->num_rows();

            if ($count > 0) {
                // if new_invoice_id already exists
                $this->user_invoice_ci_manager($invoice_id, $user_invoice_date, $pre_year, $vendor_id, $vendor_type, $order_id);
            } else {
				$data['order_id']      	 = $order_id;
				$data['vendor_id']     	 = $vendor_id;
				$data['year']		  	 = $pre_year;
				$data['user_invoice_ci'] = $new_invoice_id;
				$data['invoice_id']      = $invoice_id;
				$data['invoice_date']    = $user_invoice_date;
				$data['last_modified']   =  date('Y-m-d H:i');
                $this->db->insert('order_user_invoice_ci', $data);
                return $new_invoice_id;
            }
        }
    }


    public function update_user_invoice_ci_number($order_id) {
        date_default_timezone_set('Asia/Kolkata');
        $db3 = $this->load->database('crondb3', TRUE);
	  	$order_details = $this->get_order_details_by_id($order_id);

	  	if($order_details->user_invoice_ci==NULL){
		 $vendor_id=$order_details->vendor_id;
		 if($order_details->order_status=='cancelled'){
		  $cancelled_date=$order_details->refunded_date;
		}
		else{
		  $cancelled_date=$order_details->refunded_date;
		}

		//$delivered_date='2021-05-30';

		$cancelled_date=date('Y-m-d',strtotime($cancelled_date));
		$cancelled_day=date('m',strtotime($cancelled_date. ' + 0 day'));
        $d_year=date('Y',strtotime($cancelled_date. ' + 0 day'));

        if ($cancelled_day >= 4) {
            $pre_year = (date($d_year)+1);
        }
        else {
            $pre_year = (date($d_year));
        }

        $order_series = $db3->query("SELECT order_series FROM `users` WHERE id='$vendor_id' LIMIT 1")->row()->order_series;
		$vendor_pre   = strtoupper(strtolower($order_series));

		$check_inv_sql = $db3->query("SELECT user_invoice_ci,invoice_id FROM `order_user_invoice_ci` WHERE year='$pre_year' AND vendor_id='$vendor_id' AND invoice_id IS NOT NULL order by id desc LIMIT 1");
        if ($check_inv_sql->num_rows() > 0) {
            $row         	 = $check_inv_sql->row();
            $last_invoice_id = $row->invoice_id;
        } else {
            $ini_number      = sprintf('%04d', '0');
            $last_invoice_id = $ini_number;
        }

        $vendor_type    =  $pre_year.'-'.$vendor_pre.'-CI-';
        $user_invoice_date=date('Y-m-d H:i',strtotime($cancelled_date. ' + 0 day'));

        $new_invoice_id = $this->user_invoice_ci_manager($last_invoice_id, $user_invoice_date, $pre_year, $vendor_id, $vendor_type, $order_id);

       	$data = array(
		  'user_invoice_ci' => $new_invoice_id,
		);
		$db3->where('id', $order_id);
		$db3->update('orders', $data);
	  	}
	  	else{
	  	  $new_invoice_id=$order_details->user_invoice_ci;
	  	}
        return $new_invoice_id;
    }

    public function update_user_invoice_ci_generated($order_id, $file_url)
    {
        $data_order = array(
            'user_invoice_ci_url' => $file_url
        );
        $this->db->where('id', $order_id);
        $this->db->update('orders', $data_order);
    }


    public function check_order_by_id($order_id, $user_id)
    {
        $this->db->select('id,is_invoice,invoice_url,user_invoice,user_invoice_url,vendor_invoice');
        $this->db->where('buyer_id', $user_id);
        $this->db->where('id', $order_id);
        $query = $this->db->get('orders');
        return $query;
    }

    public function get_order_by_id($order_id)
    {
        $this->db->select('id,is_invoice,invoice_url,user_invoice,user_invoice_url');
        $this->db->where('id', $order_id);
        $query = $this->db->get('orders');
        return $query;
    }


    public function check_kirtibook_invoice($vendor_invoice, $user_id)
    {
        $query = $this->db->query("SELECT id,vendor_invoice FROM `orders` WHERE vendor_id='$user_id' AND vendor_invoice='$vendor_invoice' AND vendor_invoice IS NOT NULL LIMIT 1");
        return $query;
    }

    public function get_order_vendor_invoice($vendor_invoice, $vendor_id)
    {
        $this->db->select('inv.*,orders.vendor_id');
        $this->db->join('orders', 'inv.vendor_invoice=orders.vendor_invoice');
        $this->db->where('orders.vendor_invoice', $vendor_invoice);
        $this->db->where('orders.vendor_id', $vendor_id);
        $this->db->group_by('orders.vendor_invoice');
        $query = $this->db->get('order_vendor_invoice as inv');
        return $query;
    }




    public function manual_fetch_user_invoice($order_id)
    {
        $order    = $this->order_model->get_order($order_id);
        $shipping = $this->pdf_model->get_order_shipping($order_id);
        //$vendor_billing= $this->pdf_model->get_vendor_billing_details($order->vendor_id);

        $final_amount  = 0;
        $price_total   = $order->price_shipping;
        $refund_amount = $order->refund_amount;

        $final_amount    = price_format_decimal($price_total - $refund_amount);
        $final_amount_bt = $final_amount / 1.18;
        $shipping_gst    = $final_amount - $final_amount_bt;


        if ($shipping['shipping_state'] == 'Maharashtra'):
            $is_igst = 0;
        else:
            $is_igst = 1;
        endif;

        $output = '<body class="invoice txtup">
      <div class="panel-body" id="page-wrap">
        <table id="invoice">
            <thead>
            <tr>
                <th class="head-img text-left">
                  <img src="https://kirtibook.in/images/logo-pdf.png" class="logo">
                </th>
                <th class="text-right head">
                  <p><b>Tax Invoice</b></p>
                  <p>(Original for Recipient)</p>
                  <p><b>Invoice Number :</b>' . $order->user_invoice2 . '</p>
                  <p><b>Invoice Date :</b>' . date('d-m-Y', strtotime($order->refunded_date)) . '</p>
                </th>
            </tr>
            </thead>
        </table>
        <table id="invoice_3" class="m-t-10 table " style="table-layout: fixed;">
            <thead>
            <tr>
                <th class="p-l-r text-left" style="width: 36%;">
                    <p><b>Kirti Book Store</b></p>
                    <p> Shop No. A-1, BBC Tower, below Bank Of India, near City International School, Aundh, Pune, Maharashtra 411007</p>
                    <p class="m-t-20"><b>email ID: </b>info@kirtibook.in</p>
                    <p><b>Contact No.: </b> 8380082390</p>
                    <p><b>PAN : </b> -</p>
                    <p><b>GSTIN : </b> -</p>
                </th>
                <th class="p-l-r text-left"  style="width: 36%;">
                    <p><b>Bill To : </b>' . $shipping['name'] . '</p>
                    <p><b>Address:</b> ' . $shipping['address'] . ', ' . $shipping['shipping_city'] . '-' . $shipping['pincode'] . ', ' . $shipping['shipping_state'] . '.  Landmark- ' . $shipping['landmark'] . '</p>
                    <p><b>email ID : </b>' . $shipping['email'] . '</p>
                    <p><b>Contact No. : </b>' . $shipping['phone'] . '</p>
                    <p class="m-t-20"><b>GSTIN:</b> URP</p>
                    <p><b>Place of Supply : </b>' . $shipping['shipping_state'] . '</p>

                </th>
            </tr>
            </thead>
        </table>
        <table id="invoice_1" class="m-t-10 product table table-bordered">
            <thead>
            <tr>
                <th rowspan="2">Sl. No</th>
                <th rowspan="2">Description of<br/> the Service</th>
                <th colspan="1">Order No.<br> <small></small></th>
                <th rowspan="2">Amount<br><small>(INR)</small></th>
                <th rowspan="2">HSN / SAC</th>';
        $output .= '<th colspan="2">CGST</span></th>
                <th colspan="2">SGST</th>';
        $output .= '<th colspan="2">IGST</th>';
        $output .= '<th rowspan="2">Amount(INR) <br>(incl. Tax)</small></th>
            </tr>
            <tr>
              <th>Carrier</th>';
        $output .= '<th>Rate</th>
                <th>Amt(INR)</th>
                <th>Rate</th>
                <th>Amt(INR)</th>
                <th>Rate</th>
                <th>Amt(INR)</th>
             </tr>
            </thead>
            <tbody>

                <tr>
                    <td>1.</td>
                    <td>Product Handling Charges</td>
                    <td>' . $order->order_number . ' <br> ' . $order->courier . '</td>
                    <td>' . price_format_decimal($final_amount_bt) . '</td>
                    <td>996812</td>';

        if ($is_igst == 0):
            $output .= '
                    <td>9%</td>
                    <td>' . price_format_decimal($shipping_gst / 2) . '</td>
                    <td>9%</td>
                    <td>' . price_format_decimal($shipping_gst / 2) . '</td>
                    <td>18%</td><td>-</td>';
        else:
            $output .= '<td>9%</td><td>-</td><td>9%</td><td>-</td><td>18%</td>
                    <td>' . price_format_decimal($shipping_gst) . '</td>';
        endif;

        $output .= '<td>' . price_format_decimal($final_amount) . '</td></tr>
            <tr class="border-top">
                <td colspan="12" class="left"><b>Amount in Words:</b> ' . rupees_word($final_amount) . '</td>
            </tr>
        </tbody>
    </table>
    <table id="invoice" class="m-t-10">
        <tbody>
            <tr class="border-top">
                <td colspan="9" class="left">
                <p  class="text-left text-gray">E.&O.E. </p>
                <p  class="text-left"><b>Note: </b> Whether tax is payable on Reverse Charge – No.
                <br/>    Please note that this invoice is not a demand for payment</p>
                </td>
                <td class="right" colspan="3">
                <p>
                <b>For Kirti Book Store:</b><br>
                <img src="https://kirtibook.in/images/sign.PNG" style="max-width: 200px;height: 45px;"><br>
                <b>Authorized Signatory</b>
                </p>
                </td>
            </tr>
        </tbody>
    </table>';
        return $output;
    }




   /* public function update_user_invoice_number2($order_id)
    {
        date_default_timezone_set('Asia/Kolkata');
        $date_created  = date('Y-m-d H:i');
        $order_details = $this->order_model->get_order($order_id);
        if ($order_details->user_invoice2 == NULL) {
            $vendor_id      = $order_details->vendor_id;
            $delivered_date = $order_details->refunded_date;
            //$delivered_date='2021-05-30';

            $delivery_day = date('m', strtotime($delivered_date . ' + 0 day'));
            $d_year       = date('Y', strtotime($delivered_date . ' + 0 day'));
            if ($delivery_day >= 4) {
                $pre_year = (date($d_year) + 1);
            } else {
                $pre_year = (date($d_year));
            }

            $order_series = $this->db->query("SELECT order_series FROM `users` WHERE id='$vendor_id' LIMIT 1")->row()->order_series;
            $vendor_pre   = strtoupper(strtolower($order_series));


            $check_inv_sql = $this->db->query("SELECT user_invoice FROM `order_user_invoice` WHERE year='$pre_year' AND vendor_id='$vendor_id' order by id desc LIMIT 1");
            if ($check_inv_sql->num_rows() > 0) {
                $row             = $check_inv_sql->row();
                $last_invoice_id = $row->user_invoice;
            } else {
                $ini_number      = sprintf('%04d', '0');
                $last_invoice_id = $pre_year . '-' . $vendor_pre . '-' . $ini_number;
            }

            $user_invoice_date = date('Y-m-d H:i', strtotime($delivered_date . ' + 0 day'));

            $length = strlen(trim($order_series)) + 6;

            $invoice_id     = (int) substr($last_invoice_id, $length); //this will remove KON
            $vendor_type    = $pre_year . '-' . $vendor_pre . '-'; // this will retuen KON
            $new_invoice_id = $vendor_type . sprintf('%04d', $invoice_id + 1);


            $count = $this->db->get_where('order_user_invoice', array(
                'year' => $pre_year,
                'vendor_id' => $vendor_id
            ))->num_rows();


            if ($count > 0) {
                $data['vendor_id']    = $vendor_id;
                $data['year']         = $pre_year;
                $data['user_invoice'] = $new_invoice_id;
                $data['invoice_date'] = $user_invoice_date;
                $this->db->where('vendor_id', $vendor_id);
                $this->db->where('year', $pre_year);
                $this->db->update('order_user_invoice', $data);
            } else {
                $data1['vendor_id']    = $vendor_id;
                $data1['year']         = $pre_year;
                $data1['user_invoice'] = $new_invoice_id;
                $data1['invoice_date'] = $user_invoice_date;
                $this->db->insert('order_user_invoice', $data1);
            }


            $data = array(
                'user_invoice2' => $new_invoice_id
            );
            $this->db->where('id', $order_id);
            $this->db->update('orders', $data);
        } else {
            $new_invoice_id = $order_details->user_invoice2;
        }
        return $new_invoice_id;
    }*/

    public function update_user_invoice_number2($order_id) {
        date_default_timezone_set('Asia/Kolkata');
        $db3 = $this->load->database('crondb3', TRUE);
	  	$order_details = $this->get_order_details_by_id($order_id);
	  	if($order_details->user_invoice2==NULL){
		$vendor_id=$order_details->vendor_id;
		$delivered_date=$order_details->refunded_date;
		//$delivered_date='2021-05-30';

		$delivery_day=date('m',strtotime($delivered_date. ' + 0 day'));
        $d_year=date('Y',strtotime($delivered_date. ' + 0 day'));
        if ($delivery_day >= 4) {
            $pre_year = (date($d_year)+1);
        }
        else {
            $pre_year = (date($d_year));
        }

        $order_series = $db3->query("SELECT order_series FROM `users` WHERE id='$vendor_id' LIMIT 1")->row()->order_series;
		$vendor_pre   = strtoupper(strtolower($order_series));

        $check_inv_sql = $db3->query("SELECT user_invoice,invoice_id FROM `order_user_invoice` WHERE year='$pre_year' AND vendor_id='$vendor_id' AND invoice_id IS NOT NULL order by id desc LIMIT 1");
        if ($check_inv_sql->num_rows() > 0) {
            $row          = $check_inv_sql->row();
            $last_invoice_id = $row->invoice_id;
        } else {
            $ini_number      = sprintf('%04d', '0');
            $last_invoice_id = $ini_number;
        }

		$vendor_type    =  $pre_year.'-'.$vendor_pre.'-'; // this will retuen KON
        $user_invoice_date=date('Y-m-d H:i',strtotime($delivered_date. ' + 0 day'));

        $new_invoice_id = $this->user_invoice_manager($last_invoice_id, $user_invoice_date, $pre_year, $vendor_id, $vendor_type, $order_id);

       	$data = array();
       	$data = array(
		  'user_invoice2' => $new_invoice_id,
		);
		$db3->where('id', $order_id);
		$db3->update('orders', $data);
	  	}
	  	else{
	  	  $new_invoice_id=$order_details->user_invoice2;
	  	}
        return $new_invoice_id;
    }

    public function update_user_invoice_generated2($order_id, $file_url)
    {
        $data_order = array(
            'user_invoice_url2' => $file_url
        );
        $this->db->where('id', $order_id);
        $this->db->update('orders', $data_order);
    }



    public function update_invoice_generated_refresh($order_id, $file_url)
    {
        $data_order = array(
            'is_refresh' => 1,
            'invoice_url' => $file_url,
            'updated_at' => date('Y-m-d H:i:s')
        );
        $this->db->where('id', $order_id);
        $this->db->update('orders', $data_order);
    }


    public function update_shipping_invoice_refresh($order_id, $file_url)
    {
        $data_order = array(
            'is_refresh' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        );
        $this->db->where('id', $order_id);
        $this->db->update('orders', $data_order);
    }


    public function update_vendor_cn_number($order_id)
    {
        date_default_timezone_set('Asia/Kolkata');
        $date_created  = date('Y-m-d H:i');
        $order_details = $this->order_model->get_order($order_id);
        if ($order_details->vendor_cn == NULL) {
            $vendor_id      = $order_details->vendor_id;
            $cancelled_date = $order_details->vendor_cn_date;

            //$cancelled_date='2021-05-30';
            $cancelled_date = date('Y-m-d', strtotime($cancelled_date));
            $cancelled_date = date('Y-m-d', strtotime($cancelled_date));
            $cancelled_day  = date('m', strtotime($cancelled_date . ' + 0 day'));
            $d_year         = date('Y', strtotime($cancelled_date . ' + 0 day'));

            if ($cancelled_day >= 4) {
                $pre_year = (date($d_year) + 1);
            } else {
                $pre_year = (date($d_year));
            }

            $check_inv_sql = $this->db->query("SELECT vendor_invoice FROM `order_vendor_invoice_cn` WHERE year='$pre_year' order by id desc LIMIT 1");
            if ($check_inv_sql->num_rows() > 0) {
                $row             = $check_inv_sql->row();
                $last_invoice_id = $row->vendor_invoice;
            } else {
                $ini_number      = sprintf('%04d', '0');
                $last_invoice_id = $pre_year . '-VI-CN-' . $ini_number;
            }


            $invoice_date = date('Y-m-d H:i', strtotime($cancelled_date . ' + 0 day'));

            $length         = 11;
            $invoice_id     = (int) substr($last_invoice_id, $length); //this will remove KON
            $vendor_type    = $pre_year . '-VI-CN-'; // this will retuen KON
            $new_invoice_id = $vendor_type . sprintf('%04d', $invoice_id + 1);


            $count = $this->db->get_where('order_vendor_invoice_cn', array(
                'year' => $pre_year,
                'vendor_id' => $vendor_id,
                'order_id' => $order_id
            ))->num_rows();

            if ($count > 0) {
                $data1['order_id']       = $order_id;
                $data1['vendor_id']      = $vendor_id;
                $data1['year']           = $pre_year;
                $data1['invoice_date']   = $invoice_date;
                $data1['vendor_invoice'] = $new_invoice_id;
                $this->db->where('order_id', $order_id);
                $this->db->where('vendor_id', $vendor_id);
                $this->db->where('year', $pre_year);
                $this->db->update('order_vendor_invoice_cn', $data);
            } else {
                $data1['order_id']       = $order_id;
                $data1['vendor_id']      = $vendor_id;
                $data1['year']           = $pre_year;
                $data1['invoice_date']   = $invoice_date;
                $data1['vendor_invoice'] = $new_invoice_id;
                $this->db->insert('order_vendor_invoice_cn', $data1);
            }

            $data = array(
                'vendor_cn' => $new_invoice_id
            );
            $this->db->where('id', $order_id);
            $this->db->update('orders', $data);
        } else {
            $new_invoice_id = $order_details->vendor_cn;
        }
        return $new_invoice_id;
    }



    public function fetch_vendor_invoice_cn($order_id){
        $sgst_total=0;
        $cgst_total=0;
        $igst_total=0;
        $commision_amount_bt=0;
        $order           = $this->order_model->get_order($order_id);
        $shipping        = $this->pdf_model->get_order_shipping($order_id);
        $vendor          = $this->pdf_model->get_vendor_billing_details($order->vendor_id);
        $vendor_id= $order->vendor_id;
        //print_r($shipping); changed in 9-03-22
        //$price_shipping    = $order->vendor_refund;
        //$price_shipping_bt = $order->vendor_refund / 1.18;
        //$shipping_gst      = $order->vendor_refund - $price_shipping_bt;


	    $user = $this->db->query("SELECT email,phone_number FROM `users` WHERE id ='$vendor_id' ")->row_array();

        $cancelled_date = $order->vendor_cn_date;

        $price_shipping = $order->price_shipping;
        $price_total    = $order->price_total-$price_shipping;
        $commision_amount_bt= $order->commision_amt/1.18;
        $commision_gst= $order->commision_amt-$commision_amount_bt;

         if($vendor['state']=='Maharashtra'):
            $is_igst = 0;
            $sgst_total=($commision_gst/2);
            $cgst_total=($commision_gst/2);
        else:
            $is_igst = 1;
            $igst_total=$commision_gst;
        endif;


        $output = '<body class="invoice txtup">
      <div class="panel-body" id="page-wrap">
        <table id="invoice">
            <thead>
            <tr>
                <th class="head-img text-left">
                  <img src="https://kirtibook.in/images/logo-pdf.png" class="logo">
                </th>
                <th class="text-right head">
                  <h3><b>Credit Note</b></h3>
                  <p>(Original for Recipient)</p>
                  <p><b>CN Number :</b>' . $order->vendor_cn . '</p>
                  <p><b>CN Date :</b>' . date('d-m-Y', strtotime($cancelled_date)) . '</p>
                </th>
            </tr>
            </thead>
        </table>
        <table id="invoice_3" class="m-t-10 table " style="table-layout: fixed;">
            <thead>
            <tr>
                <th class="p-l-r text-left" style="width: 36%;">
                    <p><b>Kirti Book Store</b></p>
                    <p> Shop No. A-1, BBC Tower, below Bank Of India, near City International School, Aundh, Pune, Maharashtra 411007</p>
                    <p class="m-t-20"><b>email ID: </b>info@kirtibook.in</p>
                    <p><b>Contact No.: </b> 8380082390</p>
                    <p><b>PAN : </b> -</p>
                    <p><b>GSTIN : </b> -</p>
                </th>
                <th class="p-l-r text-left"  style="width: 36%;">
                    <p><b>Bill To : </b>' . $order->firm_name . '</p>
                    <p><b>Address:</b> ' . $vendor['address'] . '</p>
                    <p><b>Email ID : </b>' . $user['email'] . '</p>
                    <p><b>Contact No. : </b>' . $user['phone_number'] . '</p>
                    <p class="m-t-20"><b>GSTIN:</b> ' . $vendor['gst'] . '</p>
                    <p><b>Place of Supply : </b>' . $vendor['state'] . '</p>

                </th>
            </tr>
            </thead>
        </table>
        <table id="invoice_1" class="m-t-10 product table table-bordered">
            <thead>
            <tr>
                <th rowspan="2">Sl. No</th>
                <th rowspan="2">Description of<br/> the Service</th>
                <th rowspan="2">Original Invoice No.</th>
                <th rowspan="2">Amount<br><small>(INR)</small></th>
                <th rowspan="2">HSN / SAC</th>';
        $output .= '<th colspan="2">CGST</span></th>
                <th colspan="2">SGST</th>';
        $output .= '<th colspan="2">IGST</th>';
        $output .= '<th rowspan="2">Amount(INR) <br>(incl. Tax)</small></th>
            </tr>
            <tr>';
        $output .= '<th>Rate</th>
                <th>Amt(INR)</th>
                <th>Rate</th>
                <th>Amt(INR)</th>
                <th>Rate</th>
                <th>Amt(INR)</th>
             </tr>
            </thead>
            <tbody>

                <tr>
                    <td>1.</td>
                    <td>Gateway Handling Charges</td>
                    <td>' . $order->vendor_invoice . '</td>
                    <td>' . price_format_decimal($commision_amount_bt) . '</td>
                    <td>998599</td>';

        if ($is_igst == 0):
            $output .= '
                    <td>9%</td>
                    <td>' . price_format_decimal($cgst_total) . '</td>
                    <td>9%</td>
                    <td>' . price_format_decimal($sgst_total) . '</td>
                    <td>18%</td><td>-</td>';
        else:
            $output .= '<td>9%</td><td>-</td><td>9%</td><td>-</td><td>18%</td>
                    <td>' . price_format_decimal($igst_total) . '</td>';
        endif;

        $output .= '<td>' . price_format_decimal($order->commision_amt) . '</td></tr>
            <tr class="border-top">
                <td colspan="12" class="left"><b>Amount in Words:</b> ' . rupees_word($order->commision_amt) . '</td>
            </tr>
        </tbody>
    </table>
    <table id="invoice" class="m-t-10">
        <tbody>
            <tr class="border-top">
                <td colspan="9" class="left">
                <p  class="text-left text-gray">E.&O.E. </p>
                <p  class="text-left"><b>Note: </b> Whether tax is payable on Reverse Charge – No.
                <br/>    Please note that this invoice is not a demand for payment</p>
                </td>
                <td class="right" colspan="3">
                <p>
                <b>For Kirti Book Store:</b><br>
                <img src="https://kirtibook.in/images/sign.PNG" style="max-width: 200px;height: 45px;"><br>
                <b>Authorized Signatory</b>
                </p>
                </td>
            </tr>
        </tbody>
    </table>';
        return $output;
    }

    public function update_vendor_invoice_cn_generated($order_id, $file_url)
    {
        $data_order = array(
            'vendor_cn_url' => $file_url
        );
        $this->db->where('id', $order_id);
        $this->db->update('orders', $data_order);
    }

    public function get_blank_vendor_shipping_dtdc($shipping_no, $file_url)
    {
        $new_url = $file_url;
        if (file_exists(FCPATH . $new_url)) {
            $data_order = array(
                'dtdc_label_url' => $new_url
            );
            $this->db->where('slot_no', $shipping_no);
            $this->db->update('vendor_shipping_label', $data_order);

        } else {
            $data_order = array(
                'dtdc_label_url' => NULL,
                'is_label' => 0,
                'updated_at' => date('Y-m-d H:i:s')
            );
            $this->db->where('slot_no', $shipping_no);
            $this->db->update('vendor_shipping_label', $data_order);
        }
    }

    public function get_blank_vendor_shipping($shipping_no, $file_url)
    {

        $new_url = str_replace("shipping", "Shipping", $file_url);
        if (file_exists(FCPATH . $new_url)) {
            $data_order = array(
                'label_url' => $new_url
            );
            $this->db->where('slot_no', $shipping_no);
            $this->db->update('vendor_shipping_label', $data_order);

        } else {
            $data_order = array(
                'label_url' => NULL,
                'is_label' => 0,
                'updated_at' => date('Y-m-d H:i:s')
            );
            $this->db->where('slot_no', $shipping_no);
            $this->db->update('vendor_shipping_label', $data_order);
        }

    }  

	public function get_blank_vendor_shipping_bigship($shipping_no, $file_url)   {

        $new_url = str_replace("shipping", "Shipping", $file_url);
        if (file_exists(FCPATH . $new_url)) {
            $data_order = array(
                'bigship_label_url' => $new_url
            );
            $this->db->where('slot_no', $shipping_no);
            $this->db->update('vendor_shipping_label', $data_order);

        } else {
            $data_order = array(
                'bigship_label_url' => NULL,
                'is_label' => 0,
                'updated_at' => date('Y-m-d H:i:s')
            );
            $this->db->where('slot_no', $shipping_no);
            $this->db->update('vendor_shipping_label', $data_order);
        }

    }


    public function check_vorder_by_id($order_id)
    {
        $this->db->select('id,is_invoice,invoice_url,user_invoice,user_invoice_url,vendor_invoice');       
        $this->db->where('id', $order_id);
        $query = $this->db->get('orders');
        return $query;
    }



	function get_picqer_barcode($code, $id, $field) {
		$awb_number = $code; // This is the order number
		
		// Generate QR code instead of barcode
		$qrCode = QrCode::create($awb_number)
			->setSize(300)
			->setMargin(10);
		
		$writer = new PngWriter();
		$result = $writer->write($qrCode);
		$qrCodeData = $result->getString();
		
		// Save to main folder (not vendor-specific): /uploads/vendor_picqer_barcode/{date_folder}/
		$date_folder = date('Y_m_d');
		$relative_dir = 'uploads/vendor_picqer_barcode/';
		
		// Full upload path (absolute) - main folder, not vendor-specific
		$upload_path = FCPATH . trim($relative_dir, '/') . '/'
			. $date_folder . '/';
		
		if (!is_dir($upload_path)) {
			@mkdir($upload_path, 0775, true);
		}

		// Use order number as filename
		$file_name = $awb_number . ".png";
		$pngAbsoluteFilePath = $upload_path . $file_name;
		
		// Relative path for database
		// Pattern: uploads/vendor_picqer_barcode/{date_folder}/{order_number}.png
		$relative_path = trim($relative_dir, '/') . '/'
			. $date_folder . '/'
			. $file_name;

		@file_put_contents($pngAbsoluteFilePath, $qrCodeData);

		$data_order = array(
			$field => $relative_path
		);

		$this->db->where('id', $id);
		$this->db->update('vendor_shipping_label', $data_order);
	}



    function get_picqer_barcode_refresh($code, $id, $field) {
		$awb_number = $code; // This is the order number
		
		// Generate QR code instead of barcode
		$qrCode = QrCode::create($awb_number)
			->setSize(300)
			->setMargin(10);
		
		$writer = new PngWriter();
		$result = $writer->write($qrCode);
		$qrCodeData = $result->getString();
		
		// Save to main folder (not vendor-specific): /uploads/vendor_picqer_barcode/{date_folder}/
		$date_folder = date('Y_m_d');
		$relative_dir = 'uploads/vendor_picqer_barcode/';
		
		// Full upload path (absolute) - main folder, not vendor-specific
		$upload_path = FCPATH . trim($relative_dir, '/') . '/'
			. $date_folder . '/';
		
		if (!is_dir($upload_path)) {
			@mkdir($upload_path, 0775, true);
		}

		// Use order number as filename
		$file_name = $awb_number . ".png";
		$pngAbsoluteFilePath = $upload_path . $file_name;
		
		// Relative path for database
		// Pattern: uploads/vendor_picqer_barcode/{date_folder}/{order_number}.png
		$relative_path = trim($relative_dir, '/') . '/'
			. $date_folder . '/'
			. $file_name;

		@file_put_contents($pngAbsoluteFilePath, $qrCodeData);

		$data_order = array(
			$field => $relative_path
		);

		$this->db->where('id', $id);
		$this->db->update('vendor_shipping_label', $data_order);
        return $relative_path;
    }

}
