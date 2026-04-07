<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * App API Controller
 *
 * Central API controller for mobile app requests
 * Handles authentication, data retrieval, and app-specific operations
 * 
 * @package		ERP
 * @subpackage	Controllers
 * @category	API
 */
class App_api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('App_model');
    }

    public function test_api()
    {
        $response = array('status' => 200, 'message' => 'API is working');
        $this->simple_json_output($response);
    }

    public function login()
    {

        $method = $_SERVER['REQUEST_METHOD'];
        if ($method != 'POST') {
            $response = array(
                'status' => 400,
                'message' => 'Bad request.'
            );
        } else {
            $check_auth_client = $this->App_model->check_auth_client();
            if ($check_auth_client == true) {
                $params = json_decode(file_get_contents('php://input'), TRUE);
                $email = clean_and_escape(isset($params['email']) ? $params['email'] : '');
                $password = clean_and_escape(isset($params['password']) ? $params['password'] : '');
                $unique_id = clean_and_escape(isset($params['uniqueId']) ? $params['uniqueId'] : '');
                $fcm_token = clean_and_escape(isset($params['fcmToken']) ? $params['fcmToken'] : '');
                $agent_platform = clean_and_escape(isset($params['agent']) ? $params['agent'] : '');

                if ($email == '' || $password == '') {
                    $response = array('status' => 400, 'message' => 'Enter email and password !');
                } else {
                    $response = $this->App_model->login($email, $password, $unique_id, $fcm_token, $agent_platform);
                }
            } else {
                $response = array('status' => 401, 'message' => 'Unauthorized client');
            }
        }
        $this->simple_json_output($response);
    }

    public function verify()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method != 'POST') {
            $response = array('status' => 400, 'message' => 'Bad request.');
        } else {
            $params = json_decode(file_get_contents('php://input'), TRUE);
            $agent_id = (int) (isset($params['agent_id']) ? $params['agent_id'] : 0);

            if ($agent_id <= 0) {
                $response = array('status' => 400, 'message' => 'Enter agent id !');
            } else {
                $profile = $this->App_model->getAgentProfile($agent_id);
                if (!empty($profile)) {
                    $response = array('status' => 200, 'message' => 'Success', 'data' => $profile);
                } else {
                    $response = array('status' => 401, 'message' => 'Invalid agent');
                }
            }
        }
        $this->simple_json_output($response);
    }

    public function logout()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method != 'POST') {
            $response = array('status' => 400, 'message' => 'Bad request.');
        } else {
            $params = json_decode(file_get_contents('php://input'), TRUE);
            $agent_id = (int) (isset($params['agent_id']) ? $params['agent_id'] : 0);

            if ($agent_id <= 0) {
                $response = array('status' => 400, 'message' => 'Enter agent id !');
            } else {
                $this->App_model->clearAgentDeviceInfo($agent_id);
                $response = array('status' => 200, 'message' => 'Success');
            }
        }
        $this->simple_json_output($response);
    }

    public function agent_profile()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method != 'POST') {
            $response = array('status' => 400, 'message' => 'Bad request.');
        } else {
            $params = json_decode(file_get_contents('php://input'), TRUE);
            $agent_id = (int) (isset($params['agent_id']) ? $params['agent_id'] : 0);

            if ($agent_id <= 0) {
                $response = array('status' => 400, 'message' => 'Enter agent id !');
            } else {
                $profile = $this->App_model->getAgentProfile($agent_id);
                if (!empty($profile)) {
                    $response = array('status' => 200, 'message' => 'Success', 'data' => $profile);
                } else {
                    $response = array('status' => 404, 'message' => 'Agent not found');
                }
            }
        }
        $this->simple_json_output($response);
    }

    public function agent_schools()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method != 'POST') {
            $response = array('status' => 400, 'message' => 'Bad request.');
        } else {
            $params = json_decode(file_get_contents('php://input'), TRUE);
            $agent_id = (int) (isset($params['agent_id']) ? $params['agent_id'] : 0);

            if ($agent_id <= 0) {
                $response = array('status' => 400, 'message' => 'Enter agent id !');
            } else {
                $schools = $this->App_model->getAgentSchools($agent_id);
                $response = array('status' => 200, 'message' => 'Success', 'schools' => $schools);
            }
        }
        $this->simple_json_output($response);
    }
    public function school_branches()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method != 'POST') {
            $response = array('status' => 400, 'message' => 'Bad request.');
        } else {
            $params = json_decode(file_get_contents('php://input'), TRUE);
            $school_id = (int) (isset($params['school_id']) ? $params['school_id'] : 0);
            $agent_id = (int) (isset($params['agent_id']) ? $params['agent_id'] : 0);

            if ($school_id <= 0) {
                $response = array('status' => 400, 'message' => 'Enter school id !');
            } else {
                $schools = $this->App_model->get_school_branches($school_id, $agent_id);
                $response = array('status' => 200, 'message' => 'Success', 'schools' => $schools);
            }
        }
        $this->simple_json_output($response);
    }
    public function school_boards()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method != 'POST') {
            $response = array('status' => 400, 'message' => 'Bad request.');
        } else {
            $params = json_decode(file_get_contents('php://input'), TRUE);
            $school_id = (int) (isset($params['school_id']) ? $params['school_id'] : 0);
            $agent_id = (int) (isset($params['agent_id']) ? $params['agent_id'] : 0);

            if ($school_id <= 0) {
                $response = array('status' => 400, 'message' => 'Enter school id !');
            } else {
                $boards = $this->App_model->get_school_boards($school_id, $agent_id);
                $response = array('status' => 200, 'message' => 'Success', 'boards' => $boards);
            }
        }
        $this->simple_json_output($response);
    }
    public function get_classes()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method != 'POST') {
            $response = array('status' => 400, 'message' => 'Bad request.');
        } else {
            $params = json_decode(file_get_contents('php://input'), TRUE);
            $school_id = (int) (isset($params['school_id']) ? $params['school_id'] : 0);

            if ($school_id <= 0) {
                $response = array('status' => 400, 'message' => 'Enter school id !');
            } else {
                $classes = $this->App_model->getSchoolClasses($school_id);
                $response = array('status' => 200, 'message' => 'Success', 'classes' => $classes);
            }
        }
        $this->simple_json_output($response);
    }
    public function agent_categories()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method != 'POST') {
            $response = array('status' => 400, 'message' => 'Bad request.');
        } else {
            $params = json_decode(file_get_contents('php://input'), TRUE);
            $agent_id = (int) (isset($params['agent_id']) ? $params['agent_id'] : 0);
            $school_id = (int) (isset($params['school_id']) ? $params['school_id'] : 0);


            if ($agent_id <= 0) {
                $response = array('status' => 400, 'message' => 'Enter agent id !');
            } else {
                $categories = $this->App_model->getAgentCategories($agent_id , $school_id);
                $response = array('status' => 200, 'message' => 'Success', 'categories' => $categories);
            }
        }
        $this->simple_json_output($response);
    }
    public function get_uniforms()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method != 'POST') {
            $response = array('status' => 400, 'message' => 'Bad request.');
        } else {
            $params = json_decode(file_get_contents('php://input'), TRUE);
            $schoolId = (int) (isset($params['school_id']) ? $params['school_id'] : 0);

            if ($schoolId <= 0) {
                $response = array('status' => 400, 'message' => 'Enter school id !');
            } else {
                $uniforms = $this->App_model->getSchoolUniforms($schoolId);
                $response = array('status' => 200, 'message' => 'Success', 'uniforms' => $uniforms);
            }
        }
        $this->simple_json_output($response);
    }
    public function get_upi_info()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method != 'POST') {
            $response = array('status' => 400, 'message' => 'Bad request.');
        } else {
            $params = json_decode(file_get_contents('php://input'), TRUE);
            $schoolId = (int) (isset($params['school_id']) ? $params['school_id'] : 0);

            if ($schoolId <= 0) {
                $response = array('status' => 400, 'message' => 'Enter school id !');
            } else {
                $upiInfo = $this->App_model->getSchoolupiInfo($schoolId);
                $response = array('status' => 200, 'message' => 'Success', 'upi_info' => $upiInfo);
            }
        }
        $this->simple_json_output($response);
    }

    public function school($school_id = 0)
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method != 'POST') {
            $response = array('status' => 400, 'message' => 'Bad request.');
        } else {
            $params = json_decode(file_get_contents('php://input'), TRUE);
            $agent_id = (int) (isset($params['agent_id']) ? $params['agent_id'] : 0);
            $school_id = (int) $school_id;

            if ($agent_id <= 0 || $school_id <= 0) {
                $response = array('status' => 400, 'message' => 'Enter valid agent and school id !');
            } else if (!$this->App_model->agentHasSchoolAccess($agent_id, $school_id)) {
                $response = array('status' => 403, 'message' => 'Access denied to this school');
            } else {
                $access_row = $this->App_model->getAgentSchoolAccessRow($agent_id, $school_id);
                $vendor_id = !empty($access_row) ? (int) $access_row['vendor_id'] : 0;
                $school = $this->App_model->getSchoolDetails($school_id, $vendor_id);
                if (!empty($school)) {
                    $response = array('status' => 200, 'message' => 'Success', 'data' => $school);
                } else {
                    $response = array('status' => 404, 'message' => 'School not found');
                }
            }
        }
        $this->simple_json_output($response);
    }

    public function place_uniform_order()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            return $this->simple_json_output(array('status' => 400, 'message' => 'Bad request.'));
        }

        $params = json_decode(file_get_contents('php://input'), TRUE);

        $school_id = (int) (isset($params['school_id']) ? $params['school_id'] : 0);
        $parent_name = isset($params['parent_name']) ? trim($params['parent_name']) : '';
        $parent_mobile = isset($params['parent_mobile']) ? trim($params['parent_mobile']) : '';
        $payment_method = isset($params['payment_method']) ? trim($params['payment_method']) : 'cash';
        $items = isset($params['items']) ? $params['items'] : array();
        $children = isset($params['children_data']) ? $params['children_data'] : array();

        if ($school_id <= 0 || empty($parent_name) || empty($parent_mobile) || empty($items)) {
            return $this->simple_json_output(array('status' => 400, 'message' => 'Missing required fields: school_id, parent_name, parent_mobile, items.'));
        }

        $agent_id = (int) (isset($params['agent_id']) ? $params['agent_id'] : 0);

        $response = $this->App_model->placeUniformOrder(
            $school_id,
            $parent_name,
            $parent_mobile,
            $payment_method,
            $items,
            $children,
            $agent_id
        );

        $this->simple_json_output($response);
    }
    
    public function test_whatsapp()
    {
        $this->load->model('app_model');
    
        $phone = '9870678754';
        $parent_name = 'Dharmesh';          // dynamic value
        $order_unique_id = 'ORD123';     // dynamic value
        $file_url = 'https://bhashsms.com/pushwa/iframe/files/trai.pdf'; // document
    
        $response = $this->app_model->send_whatsapp(
            $phone,
            $parent_name,
            $order_unique_id,
            $file_url
        );
    
        echo "<pre>";
        print_r($response);
        exit;
    }

    public function get_agent_orders()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            return $this->simple_json_output(array('status' => 400, 'message' => 'Bad request.'));
        }

        $params = json_decode(file_get_contents('php://input'), TRUE);
        $agent_id = (int) (isset($params['agent_id']) ? $params['agent_id'] : 0);

        if ($agent_id <= 0) {
            return $this->simple_json_output(array('status' => 400, 'message' => 'Agent ID required.'));
        }

        $orders = $this->App_model->getAgentUniformOrders($agent_id);
        $this->simple_json_output(array('status' => 200, 'message' => 'Orders fetched', 'orders' => $orders));
    }

    public function get_order_details()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            return $this->simple_json_output(array('status' => 400, 'message' => 'Bad request.'));
        }

        $params = json_decode(file_get_contents('php://input'), TRUE);
        $vendor_id = (int) (isset($params['vendor_id']) ? $params['vendor_id'] : 0);
        $order_id = (int) (isset($params['order_id']) ? $params['order_id'] : 0);

        if ($vendor_id <= 0 || $order_id <= 0) {
            return $this->simple_json_output(array('status' => 400, 'message' => 'Vendor ID and Order ID required.'));
        }

        $order = $this->App_model->getUniformOrderDetail($vendor_id, $order_id);
        if (!$order) {
            return $this->simple_json_output(array('status' => 404, 'message' => 'Order not found.'));
        }

        $this->simple_json_output(array('status' => 200, 'message' => 'Order details fetched', 'order' => $order));
    }

    public function download_invoice()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            show_error('Bad request.', 400);
            return;
        }

        $params = json_decode(file_get_contents('php://input'), TRUE);
        $agent_id = (int) (isset($params['agent_id']) ? $params['agent_id'] : 0);
        $vendor_id = (int) (isset($params['vendor_id']) ? $params['vendor_id'] : 0);
        $order_id = (int) (isset($params['order_id']) ? $params['order_id'] : 0);

        if ($agent_id <= 0 || $vendor_id <= 0 || $order_id <= 0) {
            return $this->simple_json_output(array('status' => 400, 'message' => 'Agent ID, Vendor ID and Order ID required.'));
        }

        $order = $this->App_model->getUniformOrderDetail($vendor_id, $order_id);
        if (empty($order)) {
            return $this->simple_json_output(array('status' => 404, 'message' => 'Order not found.'));
        }

        if (!empty($order['school_id']) && !$this->App_model->agentHasSchoolAccess($agent_id, $order['school_id'])) {
            return $this->simple_json_output(array('status' => 403, 'message' => 'Access denied to this order.'));
        }

        $invoice_data = $this->build_invoice_data($vendor_id, $order);
        if (empty($invoice_data)) {
            return $this->simple_json_output(array('status' => 500, 'message' => 'Unable to prepare invoice.'));
        }

        $this->load->helper('common');
        $this->load->library('pdf');

        // Dompdf can exceed default 128MB on long invoices; align with vendor-side safeguards.
        @ini_set('memory_limit', '512M');
        @set_time_limit(120);

        $page_data = array('data' => $invoice_data);
        $invoice_view_path = APPPATH . 'views/invoice/invoice_bill.php';
        if (!file_exists($invoice_view_path)) {
            return $this->simple_json_output(array('status' => 500, 'message' => 'Invoice template not found.'));
        }

        $html_content = $this->load->view('invoice/invoice_bill', $page_data, TRUE);

        $invoice_dir = FCPATH . 'uploads/app_invoices/';
        if (!is_dir($invoice_dir) && !@mkdir($invoice_dir, 0775, TRUE) && !is_dir($invoice_dir)) {
            return $this->simple_json_output(array('status' => 500, 'message' => 'Unable to create invoice directory.'));
        }

        $pdfname = 'invoice_' . $invoice_data['order_obj']->order_unique_id . '_' . time() . '.pdf';
        $file_path = $invoice_dir . $pdfname;

        $this->pdf->set_paper('A4', 'portrait');
        $old_error_reporting = error_reporting();
        error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

        try {
            $this->pdf->set_option('isHtml5ParserEnabled', TRUE);
            $this->pdf->set_option('isRemoteEnabled', FALSE);
            $this->pdf->load_html($html_content);
            $this->pdf->render();
            file_put_contents($file_path, $this->pdf->output());
        } catch (Exception $e) {
            error_reporting($old_error_reporting);
            $this->pdf = new Pdf();
            $this->pdf->set_paper('A4', 'portrait');
            $this->pdf->set_option('isHtml5ParserEnabled', FALSE);
            $this->pdf->set_option('isRemoteEnabled', FALSE);
            $this->pdf->load_html($html_content);
            $this->pdf->render();
            file_put_contents($file_path, $this->pdf->output());
        }

        error_reporting($old_error_reporting);

        return $this->simple_json_output(array(
            'status' => 200,
            'message' => 'Invoice generated successfully',
            'download_url' => base_url('uploads/app_invoices/' . $pdfname),
            'file_name' => $pdfname,
        ));
    }

    private function build_invoice_data($vendor_id, $order)
    {
        if (empty($order) || !is_array($order)) {
            return array();
        }

        $products = isset($order['items']) && is_array($order['items']) ? $order['items'] : array();
        $items_arr = array();
        $gst_total = 0;
        $total_product_discount = 0;

        foreach ($products as $product) {
            $items_arr[] = (object) $product;
            $gst_total += isset($product['total_gst_amt']) ? (float) $product['total_gst_amt'] : 0;
            $total_product_discount += isset($product['discount_amt']) ? (float) $product['discount_amt'] : 0;
        }

        $company = $this->get_invoice_company_from_erp_clients($vendor_id);
        $logo_src = $this->get_invoice_logo_base64($company);

        $company_name = !empty($company['name']) ? $company['name'] : 'Shivam Books';
        $company_address = !empty($company['address']) ? $company['address'] : '';
        if (!empty($company['pincode'])) {
            $company_address = trim($company_address . ', ' . $company['pincode']);
        }

        $order_type_label = $this->resolve_invoice_order_type($products);
        $invoice_no = !empty($order['invoice_no']) ? $order['invoice_no'] : $order['order_unique_id'];
        $school_name = !empty($order['school_name']) ? $order['school_name'] : '';

        return array(
            'id' => isset($order['id']) ? (int) $order['id'] : 0,
            'order_unique_id' => isset($order['order_unique_id']) ? $order['order_unique_id'] : '',
            'user_name' => isset($order['user_name']) ? $order['user_name'] : '',
            'user_email' => isset($order['user_email']) ? $order['user_email'] : '',
            'user_phone' => isset($order['user_phone']) ? $order['user_phone'] : '',
            'order_date' => !empty($order['order_date']) ? date('d M Y | h:i A', strtotime($order['order_date'])) : '',
            'invoice_date' => !empty($order['invoice_date']) ? date('d M Y', strtotime($order['invoice_date'])) : date('d M Y'),
            'invoice_no' => $invoice_no,
            'payable_amt' => isset($order['payable_amt']) ? (float) $order['payable_amt'] : 0,
            'discount_amt' => isset($order['discount_amt']) ? (float) $order['discount_amt'] : 0,
            'delivery_charge' => isset($order['delivery_charge']) ? (float) $order['delivery_charge'] : 0,
            'payment_method' => isset($order['payment_method']) ? $order['payment_method'] : '',
            'currency' => isset($order['currency']) ? $order['currency'] : 'INR',
            'currency_code' => isset($order['currency_code']) ? $order['currency_code'] : '₹',
            'shipping' => isset($order['address']) && is_array($order['address']) ? $order['address'] : array(),
            'products' => $products,
            'gst_total' => $gst_total,
            'total_product_discount' => $total_product_discount,
            'freight_charges' => isset($order['freight_charges']) ? (float) $order['freight_charges'] : 0,
            'freight_gst' => isset($order['freight_gst']) ? (float) $order['freight_gst'] : 0,
            'freight_charges_excl' => isset($order['freight_charges_excl']) ? (float) $order['freight_charges_excl'] : 0,
            'freight_gst_per' => isset($order['freight_gst_per']) ? (float) $order['freight_gst_per'] : 0,
            'logo_src' => $logo_src,
            'company_name' => $company_name,
            'company_address' => $company_address,
            'company_gstin' => !empty($company['gstin']) ? $company['gstin'] : '-',
            'company_pan' => !empty($company['pan']) ? $company['pan'] : '-',
            'company_phone' => !empty($company['contact_number']) ? $company['contact_number'] : '',
            'order_type_label' => $order_type_label,
            'items_arr' => $items_arr,
            'bookset_products' => array(),
            'order_obj' => (object) array_merge($order, array('invoice_no' => $invoice_no, 'school_name' => $school_name)),
        );
    }

    private function resolve_invoice_order_type($products)
    {
        if (empty($products) || !is_array($products)) {
            return 'Individual';
        }

        foreach ($products as $product) {
            if (!isset($product['order_type'])) {
                continue;
            }

            $order_type = strtolower((string) $product['order_type']);
            if ($order_type === 'bookset' || $order_type === 'package') {
                return 'Bookset';
            }
            if ($order_type === 'uniform') {
                return 'Uniform';
            }
        }

        return 'Individual';
    }

    private function get_invoice_company_from_erp_clients($vendor_id)
    {
        $company = $this->App_model->getVendorInvoiceCompany((int) $vendor_id);
        return is_array($company) ? $company : array();
    }

    private function get_invoice_logo_base64($company = array())
    {
        if (empty($company) || empty($company['logo'])) {
            return '';
        }

        $logo_path = trim((string) $company['logo']);
        if ($logo_path === '') {
            return '';
        }

        $candidate_paths = array(
            FCPATH . ltrim($logo_path, '/'),
            FCPATH . 'book_erp_frontend/' . ltrim($logo_path, '/'),
        );

        foreach ($candidate_paths as $path) {
            if (!file_exists($path)) {
                continue;
            }

            $file_size = @filesize($path);
            if ($file_size <= 0 || $file_size > 300000) {
                continue;
            }

            $logo_data = @file_get_contents($path);
            if ($logo_data === false) {
                continue;
            }

            $image_info = @getimagesize($path);
            $mime_type = ($image_info !== false && isset($image_info['mime'])) ? $image_info['mime'] : 'image/png';
            return 'data:' . $mime_type . ';base64,' . base64_encode($logo_data);
        }

        return '';
    }

    private function simple_json_output($response = array())
    {
        ob_clean();
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}
