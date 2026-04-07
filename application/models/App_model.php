<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * App Model
 *
 * Handles all app-specific database operations
 * Centralized data access layer for mobile app API
 *
 * @package		ERP
 * @subpackage	Models
 * @category	Models
 */
class App_model extends CI_Model
{
    private $master_db;
    private $vendor_db_connections = array();

    public function __construct()
    {
        parent::__construct();
        $this->master_db = $this->load->database('default', TRUE);
    }

    public function check_auth_client()
    {
        return TRUE;
    }

    public function login($email, $password, $unique_id = '', $fcm_token = '', $agent_platform = '')
    {
        $email = trim((string) $email);
        $password = trim((string) $password);

        $query = $this->master_db
            ->select('id, username, email,  password, status, is_delete')
            ->from('erp_agent_users')
            ->where('email', $email)
            ->limit(1)
            ->get();

        $count = $query->num_rows();
        if ($count > 0) {
            $row = $query->row_array();
            $agent_id = (int) $row['id'];

            if ((int) $row['is_delete'] === 1) {
                return array(
                    'status' => 401,
                    'message' => 'Your account is deleted.',
                    'user_id' => '',
                    'user_email' => ''
                );
            }

            if ((int) $row['status'] !== 1) {
                return array(
                    'status' => 401,
                    'message' => 'Your account is inactive by admin.',
                    'user_id' => '',
                    'user_email' => ''
                );
            }

            if (sha1($password) !== (string) $row['password']) {
                return array(
                    'status' => 401,
                    'message' => 'Invalid email or password',
                    'user_id' => '',
                    'user_email' => ''
                );
            }

            $this->updateAgentLogin(
                $agent_id,
                array(
                    'unique_id' => $unique_id,
                    'fcm_token' => $fcm_token,
                    'platform' => $agent_platform,
                    'last_login' => date('Y-m-d H:i:s')
                )
            );

            $school_access = $this->getAgentSchools($agent_id);
            $vendor_id = !empty($school_access) && isset($school_access[0]['vendor_id'])
                ? (int) $school_access[0]['vendor_id']
                : 0;

            return array(
                'status' => 200,
                'message' => 'Success',
                'user_id' => $agent_id,
                'user_name' => (string) $row['username'],
                'user_email' => (string) $row['email'],
                'vendor_id' => $vendor_id,
                'account_type' => 'pos_agent',
                'school_access' => $school_access,
                'platform' => (string) $agent_platform
            );
        }

        return array(
            'status' => 400,
            'message' => 'This account is not registered with us',
            'user_id' => '',
            'user_email' => ''
        );
    }

    /**
     * ========== AUTHENTICATION ==========
     */

    /**
     * Authenticate Agent
     *
     * @param string $email
     * @param string $password
     * @return array
     */
    public function authenticateAgent($email, $password)
    {
        $email = trim((string) $email);
        $password = trim((string) $password);

        if (empty($email) || empty($password)) {
            return array(
                'success' => FALSE,
                'message' => 'Email and password required',
                'agent' => array()
            );
        }

        // Get agent by email
        $agent = $this->master_db
            ->select('id, username, email, password, status, phone')
            ->from('erp_agent_users')
            ->where('email', $email)
            ->where('is_delete', 0)
            ->get()
            ->row_array();

        if (!$agent) {
            return array(
                'success' => FALSE,
                'message' => 'Invalid email or password',
                'agent' => array()
            );
        }

        // Check status
        if ((int) $agent['status'] !== 1) {
            return array(
                'success' => FALSE,
                'message' => 'Agent account is inactive',
                'agent' => array()
            );
        }

        // Verify password
        if (sha1($password) !== $agent['password']) {
            return array(
                'success' => FALSE,
                'message' => 'Invalid email or password',
                'agent' => array()
            );
        }

        return array(
            'success' => TRUE,
            'message' => 'Authentication successful',
            'agent' => $agent
        );
    }

    /**
     * Update Agent Login Info
     *
     * @param int $agent_id
     * @param array $login_data
     * @return bool
     */
    public function updateAgentLogin($agent_id, $login_data = array())
    {
        $agent_id = (int) $agent_id;
        if ($agent_id <= 0 || empty($login_data)) {
            return FALSE;
        }

        $update_data = array();

        if (isset($login_data['unique_id'])) {
            $update_data['unique_id'] = trim((string) $login_data['unique_id']);
        }

        if (isset($login_data['fcm_token'])) {
            $update_data['fcm_token'] = trim((string) $login_data['fcm_token']);
        }

        if (isset($login_data['platform'])) {
            $update_data['platform'] = trim((string) $login_data['platform']);
        }

        if (isset($login_data['last_login'])) {
            $update_data['last_login'] = trim((string) $login_data['last_login']);
        }

        if (empty($update_data)) {
            return FALSE;
        }

        return $this->master_db
            ->where('id', $agent_id)
            ->update('erp_agent_users', $update_data);
    }

    /**
     * Clear Agent Device Info on Logout
     *
     * @param int $agent_id
     * @return bool
     */
    public function clearAgentDeviceInfo($agent_id)
    {
        $agent_id = (int) $agent_id;
        if ($agent_id <= 0) {
            return FALSE;
        }

        return $this->master_db
            ->where('id', $agent_id)
            ->update('erp_agent_users', array(
                'unique_id' => NULL,
                'fcm_token' => NULL,
                'platform' => NULL
            ));
    }

    /**
     * ========== AGENT DATA ==========
     */

    /**
     * Get Agent Profile
     *
     * @param int $agent_id
     * @return array
     */
    public function getAgentProfile($agent_id)
    {
        $agent_id = (int) $agent_id;
        if ($agent_id <= 0) {
            return array();
        }

        $agent = $this->master_db
            ->select('id, username, email, phone, status, created_at, last_login, platform')
            ->from('erp_agent_users')
            ->where('id', $agent_id)
            ->where('is_delete', 0)
            ->get()
            ->row_array();

        return $agent ? $agent : array();
    }

    /**
     * Get Agent Schools with Access Permissions
     *
     * @param int $agent_id
     * @return array
     */
    public function getAgentSchools($agent_id)
    {
        $agent_id = (int) $agent_id;
        if ($agent_id <= 0) {
            return array();
        }

        $access_rows = $this->master_db
            ->select('
				sa.id as access_id,
				sa.school_id,
				sa.vendor_id,
				sa.can_uniform,
				sa.can_bookset,
				sa.upi_qr_id,
				q.upi_id
			')
            ->from('erp_pos_agent_school_access sa')
            ->join('erp_school_upi_qr q', 'q.id = sa.upi_qr_id', 'left')
            ->where('sa.agent_user_id', $agent_id)
            ->where('sa.status', 1)
            ->order_by('sa.id', 'ASC')
            ->get()
            ->result_array();

        $by_vendor_school_ids = array();
        foreach ($access_rows as $row) {
            $vendor_id = (int) $row['vendor_id'];
            $school_id = (int) $row['school_id'];

            if ($vendor_id <= 0 || $school_id <= 0) {
                continue;
            }

            if (!isset($by_vendor_school_ids[$vendor_id])) {
                $by_vendor_school_ids[$vendor_id] = array();
            }

            if (!in_array($school_id, $by_vendor_school_ids[$vendor_id], TRUE)) {
                $by_vendor_school_ids[$vendor_id][] = $school_id;
            }
        }

        $school_map = array();
        foreach ($by_vendor_school_ids as $vendor_id => $school_ids) {
            $school_map[$vendor_id] = $this->getVendorSchoolMap((int) $vendor_id, $school_ids);
        }

        $formatted = array();
        foreach ($access_rows as $school) {
            $vendor_id = (int) $school['vendor_id'];
            $school_id = (int) $school['school_id'];
            $vendor_school = isset($school_map[$vendor_id][$school_id]) ? $school_map[$vendor_id][$school_id] : array();

            $formatted[] = array(
                'school_id' => $school_id,
                'school_name' => isset($vendor_school['school_name']) && $vendor_school['school_name'] !== ''
                    ? $vendor_school['school_name']
                    : 'School #' . $school_id,
                'school_image' => isset($vendor_school['school_image']) ? $vendor_school['school_image'] : '',
                'school_image_url' => isset($vendor_school['school_image_url']) ? $vendor_school['school_image_url'] : '',
                'address' => isset($vendor_school['address']) ? $vendor_school['address'] : '',
                'country_id' => isset($vendor_school['country_id']) ? (int) $vendor_school['country_id'] : 0,
                'state_id' => isset($vendor_school['state_id']) ? (int) $vendor_school['state_id'] : 0,
                'city_id' => isset($vendor_school['city_id']) ? (int) $vendor_school['city_id'] : 0,
                'country_name' => isset($vendor_school['country_name']) ? $vendor_school['country_name'] : '',
                'state_name' => isset($vendor_school['state_name']) ? $vendor_school['state_name'] : '',
                'city_name' => isset($vendor_school['city_name']) ? $vendor_school['city_name'] : '',
                'location' => isset($vendor_school['location']) ? $vendor_school['location'] : '',
                'vendor_id' => $vendor_id,
                'can_uniform' => (int) $school['can_uniform'],
                'can_bookset' => (int) $school['can_bookset'],
                'upi_qr_id' => (int) $school['upi_qr_id'],
                'upi_id' => $school['upi_id']
            );
        }

        usort($formatted, function ($a, $b) {
            return strcasecmp((string) $a['school_name'], (string) $b['school_name']);
        });

        return $formatted;
    }
    public function get_school_branches($school_id, $agent_id)
    {
        $school_id = (int) $school_id;
        $agent_id = (int) $agent_id;
        if ($agent_id <= 0) {
            return array();
        }
        $vendor_id = $this->master_db->select('vendor_id')
            ->from('erp_pos_agent_school_access')
            ->where('agent_user_id', $agent_id)
            ->where('school_id', $school_id)
            ->get()
            ->row()
            ->vendor_id;
        if ($vendor_id <= 0) {
            return array();
        }
        $vendor_db = $this->getVendorDB($vendor_id);

        $branches = $vendor_db->select('
        b.id,
        b.school_id,
        b.vendor_id,
        b.branch_name,
        b.slug,
        b.address,
        b.pincode,
        b.country_id,
        b.state_id,
        b.city_id,
        b.status,
        b.is_payment_required,
        b.deliver_at_school,
        b.created_at,
        b.updated_at,
        st.name AS state_name,
        ct.name AS city_name,
        co.name AS country_name
    ')
            ->from('erp_school_branches b')
            ->join('states st', 'st.id = b.state_id', 'left')
            ->join('cities ct', 'ct.id = b.city_id', 'left')
            ->join('countries co', 'co.id = b.country_id', 'left')
            ->where('b.school_id', $school_id)
            ->where('b.vendor_id', $vendor_id)
            ->get()
            ->result_array();

        $formatted = array();

        foreach ($branches as $branch) {
            $formatted[] = array(
                'branch_id' => $branch['id'],
                'school_id' => $branch['school_id'],
                'vendor_id' => $branch['vendor_id'],

                'branch_name' => $branch['branch_name'],
                'branch_slug' => $branch['slug'],

                'branch_address' => $branch['address'],
                'branch_pincode' => $branch['pincode'],

                'branch_city' => $branch['city_id'],
                'branch_city_name' => $branch['city_name'],

                'branch_state' => $branch['state_id'],
                'branch_state_name' => $branch['state_name'],

                'branch_country' => $branch['country_id'],
                'branch_country_name' => $branch['country_name'],

                'branch_status' => $branch['status'],

                'is_payment_required' => $branch['is_payment_required'],
                'deliver_at_school' => $branch['deliver_at_school'],

                'branch_created_at' => $branch['created_at'],
                'branch_updated_at' => $branch['updated_at'],
            );
        }
        return $formatted;

    }
    public function get_school_boards($school_id, $agent_id)
    {
        $school_id = (int) $school_id;
        $agent_id = (int) $agent_id;

        if ($agent_id <= 0) {
            return [];
        }

        // Get vendor_id
        $vendor = $this->master_db->select('vendor_id')
            ->from('erp_pos_agent_school_access')
            ->where('agent_user_id', $agent_id)
            ->where('school_id', $school_id)
            ->get()
            ->row();

        if (!$vendor || $vendor->vendor_id <= 0) {
            return [];
        }

        $vendor_db = $this->getVendorDB($vendor->vendor_id);

        // 🔥 Proper JOIN with mapping table
        $boards = $vendor_db->select('b.id, b.board_name')
            ->from('erp_school_boards_mapping m')
            ->join('erp_school_boards b', 'b.id = m.board_id', 'left')
            ->where('m.school_id', $school_id)
            ->where('b.status', 1)
            ->group_by('b.id') // avoid duplicates
            ->order_by('b.board_name', 'ASC')
            ->get()
            ->result_array();

        return $boards;
    }
    public function getAgentCategories($agent_id , $school_id)
    {
        $agent_id = (int) $agent_id;

        if ($agent_id <= 0) {
            return array();
        }
        if ($school_id <= 0) {
            return array();
        }

        $access = $this->master_db
            ->select('can_bookset, can_uniform')
            ->from('erp_pos_agent_school_access')
            ->where('agent_user_id', $agent_id)
            ->where('school_id', $school_id)
            ->where('status', 1)
            ->limit(1)
            ->get()
            ->row_array();

        if (empty($access)) {
            return array();
        }
        
        $categories = [];

        // Map flags → categories
        if (!empty($access['can_bookset']) && $access['can_bookset'] == 1) {
            $categories[] = [
                'id' => 1,
                'category_name' => 'Bookset',
                'type' => 'bookset'
            ];
        }

        if (!empty($access['can_uniform']) && $access['can_uniform'] == 1) {
            $categories[] = [
                'id' => 2,
                'category_name' => 'Uniform',
                'type' => 'uniform'
            ];
        }

        return $categories;
    }
    
    public function placeUniformOrder($school_id, $parent_name, $parent_mobile, $payment_method, $items, $children_data = array(), $agent_id = 0)
    {
        $school_id = (int) $school_id;
        $agent_id = (int) $agent_id;

        // STEP 1: Resolve vendor_id from school_id
        $row = $this->master_db
            ->select('vendor_id')
            ->from('erp_pos_agent_school_access')
            ->where('school_id', $school_id)
            ->where('status', 1)
            ->limit(1)
            ->get()
            ->row_array();

        if (empty($row['vendor_id'])) {
            return array('status' => 400, 'message' => 'Vendor not found for this school.');
        }

        $vendor_id = (int) $row['vendor_id'];

        // STEP 2: Load vendor DB
        $vendor_db = $this->getVendorDB($vendor_id);
        if (!$vendor_db) {
            return array('status' => 500, 'message' => 'Could not connect to vendor database.');
        }

        // STEP 3: Compute total
        $total_price = 0;
        foreach ($items as $item) {
            $price = (float) (isset($item['selling_price']) ? $item['selling_price'] : 0);
            $qty = (int) (isset($item['qty']) ? $item['qty'] : 1);
            $total_price += $price * $qty;
        }

        // STEP 4: Generate unique order ID in format ORDYYMMDDXXX (e.g. ORD260328344)
        $order_unique_id = 'ORD' . date('ymd') . rand(100, 999);

        // STEP 5: Insert tbl_order_details into vendor DB
        $order_row = array(
            'order_unique_id' => $order_unique_id,
            'user_id' => 0,         // NOT NULL in schema
            'agent_id' => $agent_id,
            'school_id' => $school_id,
            'user_name' => $parent_name,
            'user_phone' => $parent_mobile,
            'order_status' => '1',       // pending
            'payment_status' => 'success',
            'payment_method' => $payment_method,
            'order_type' => 'app',     // Matches enum('online','app')
            'type_order' => 'uniform', // Matches enum/varchar for 'uniform'
            'order_date' => date('Y-m-d H:i:s'),
            'source' => 'app',
            'checkout_type' => 'guest_checkout',
            'is_deliver_at_school' => 1,
            'children_data' => !empty($children_data) ? json_encode($children_data) : NULL,
            'total_amt' => $total_price,
            'payment_amount' => $total_price,
            'payable_amt' => $total_price,
            'order_address' => 0,         // Initialize before updating
        );

        $vendor_db->insert('tbl_order_details', $order_row);
        $order_id = (int) $vendor_db->insert_id();

        if ($order_id <= 0) {
            return array('status' => 500, 'message' => 'Failed to create order. Please try again.');
        }

        // STEP 6: Insert tbl_order_address
        $address_row = array(
            'order_id' => $order_id,
            'order_unique_id' => $order_unique_id,
            'name' => $parent_name,
            'mobile_no' => $parent_mobile,
            'address' => '',
            'city' => '',
            'state' => '',
            'pincode' => '',
            'country' => 'India',
            'address_type' => 'billing',
        );
        $vendor_db->insert('tbl_order_address', $address_row);
        $address_id = (int) $vendor_db->insert_id();

        // STEP 6.5: Update order with address_id (satisfy NOT NULL constraint)
        if ($address_id > 0) {
            $vendor_db->where('id', $order_id)->update('tbl_order_details', array('order_address' => $address_id));
        }

        // STEP 7: Insert tbl_order_items
        foreach ($items as $item) {
            $price = (float) (isset($item['selling_price']) ? $item['selling_price'] : 0);
            $qty = (int) (isset($item['qty']) ? $item['qty'] : 1);

            $vendor_db->insert('tbl_order_items', array(
                'order_id' => $order_id,
                'user_id' => 0,         // NOT NULL in schema
                'product_id' => (int) (isset($item['uniform_id']) ? $item['uniform_id'] : 0),
                'product_title' => isset($item['product_name']) ? $item['product_name'] : '',
                'product_qty' => $qty,
                'product_price' => $price,
                'total_price' => $price * $qty,
                'variation_id' => !empty($item['size_id']) ? (int) $item['size_id'] : NULL,
                'variation_name' => isset($item['size_name']) ? $item['size_name'] : '',
                'order_type' => 'uniform',
                'school_id' => isset($item['school_id']) ? (int) $item['school_id'] : $school_id,
                'branch_id' => !empty($item['branch_id']) ? (int) $item['branch_id'] : NULL,
            ));
        }
        
        // STEP 8: Send WhatsApp Notification
        $phone = $parent_mobile;
        
        // Optional PDF URL
        $file_url = base_url('uploads/invoice/' . $order_unique_id . '.pdf');
        
        // Call function
        $response = $this->send_whatsapp(
            $phone,
            $parent_name,
            $order_unique_id,
            $file_url // optional
        );
        
        // Log response (important for debugging)
        log_message('error', 'WA Response: ' . $response);
        

        return array(
            'status' => 200,
            'message' => 'Order placed successfully!',
            'order_id' => $order_id,
            'order_unique_id' => $order_unique_id,
            'vendor_id' => $vendor_id
        );
    }
    
   public function send_whatsapp($phone, $parent_name, $order_unique_id, $file_url = ''){
    $params = [
    'user'     => 'VarittyUniform_bwa',
    'pass'     => '123456',
    'sender'   => 'BUZWAP',
    'phone'    => $phone,
    'text'     => 'varitty_doc',
    'priority' => 'wa',
    'stype'    => 'normal',
    'Params'   => $parent_name . ',' . date('Y-m-d') . ',' . $order_unique_id
];

    // ✅ Add document support
    if (!empty($file_url)) {
        $params['htype'] = 'document';
        $params['fname'] = 'PDF';
        $params['url']   = $file_url;
    }

    $url = "http://bhashsms.com/api/sendmsgutil.php?" . http_build_query($params);

    // CURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        return curl_error($ch);
    }

    curl_close($ch);

    return $response;
}



    public function getAgentUniformOrders($agent_id)
    {
        $agent_id = (int) $agent_id;

        // 1. Get all vendor IDs this agent has access to
        $access = $this->master_db
            ->select('vendor_id')
            ->from('erp_pos_agent_school_access')
            ->where('agent_user_id', $agent_id)
            ->where('status', 1)
            ->get()
            ->result_array();

        if (empty($access))
            return array();

        $unique_vendors = array_unique(array_column($access, 'vendor_id'));
        $all_orders = array();

        foreach ($unique_vendors as $v_id) {
            $vendor_db = $this->getVendorDB($v_id);
            if (!$vendor_db)
                continue;

            $orders = $vendor_db
                ->select('*')
                ->from('tbl_order_details')
                ->where('agent_id', $agent_id)
                ->order_by('id', 'DESC')
                ->limit(50)
                ->get()
                ->result_array();

            foreach ($orders as &$ord) {
                $ord['vendor_id'] = $v_id; // useful for lookup
                $ord['status_text'] = $this->getOrderStatusText($ord['order_status']);

                // Fetch school name from VENDOR DB
                $school = $vendor_db->select('school_name')->from('erp_schools')->where('id', $ord['school_id'])->get()->row_array();
                $ord['school_name'] = $school ? $school['school_name'] : 'Unknown School';
            }
            $all_orders = array_merge($all_orders, $orders);
        }

        // Sort by date DESC
        usort($all_orders, function ($a, $b) {
            return strtotime($b['order_date']) - strtotime($a['order_date']);
        });

        return $all_orders;
    }

    public function getUniformOrderDetail($vendor_id, $order_id)
    {
        $vendor_db = $this->getVendorDB($vendor_id);
        if (!$vendor_db)
            return NULL;

        $order = $vendor_db->get_where('tbl_order_details', array('id' => $order_id))->row_array();
        if (!$order)
            return NULL;

        // Get vendor domain for absolute image URLs
        $vendor = $this->master_db
            ->select('domain')
            ->from('erp_clients')
            ->where('id', $vendor_id)
            ->get()
            ->row_array();
        $base_url = '';
        if (!empty($vendor['domain'])) {
            $base_url = rtrim('https://' . $vendor['domain'], '/') . '/';
        }

        $items = $vendor_db->get_where('tbl_order_items', array('order_id' => $order_id))->result_array();

        // Fetch images for each item
        foreach ($items as &$item) {
            $item['product_image'] = '';
            if (!empty($item['product_id'])) {
                $img = $vendor_db->select('image_path')
                    ->from('erp_uniform_images')
                    ->where('uniform_id', $item['product_id'])
                    ->where('is_main', 1)
                    ->limit(1)
                    ->get()
                    ->row_array();

                if (empty($img)) {
                    // Fallback to first available image
                    $img = $vendor_db->select('image_path')
                        ->from('erp_uniform_images')
                        ->where('uniform_id', $item['product_id'])
                        ->limit(1)
                        ->get()
                        ->row_array();
                }

                if (!empty($img['image_path'])) {
                    $item['product_image'] = $base_url . ltrim($img['image_path'], '/');
                }
            }
        }

        $order['items'] = $items;
        $order['address'] = $vendor_db->get_where('tbl_order_address', array('order_id' => $order_id))->row_array();
        $order['status_text'] = $this->getOrderStatusText($order['order_status']);

        // Fetch school name and logo from VENDOR DB
        if (!empty($order['school_id'])) {
            $has_logo = $vendor_db->field_exists('logo', 'erp_schools');
            $select_fields = $has_logo ? 'school_name, logo' : 'school_name';

            $school = $vendor_db->select($select_fields)->from('erp_schools')->where('id', $order['school_id'])->get()->row_array();

            $order['school_name'] = $school ? $school['school_name'] : 'Unknown School';
            $order['school_logo'] = ($school && $has_logo && !empty($school['logo'])) ? $base_url . ltrim($school['logo'], '/') : '';
        } else {
            $order['school_name'] = 'N/A';
            $order['school_logo'] = '';
        }

        return $order;
    }

    public function getVendorInvoiceCompany($vendor_id)
    {
        $vendor_id = (int) $vendor_id;
        if ($vendor_id <= 0) {
            return array();
        }

        $vendor_db = $this->getVendorDB($vendor_id);
        if (!$vendor_db || !$vendor_db->table_exists('erp_clients')) {
            return array();
        }

        $cols = array('name', 'address', 'pincode', 'pan', 'gstin');
        if ($vendor_db->field_exists('contact_number', 'erp_clients')) {
            $cols[] = 'contact_number';
        }
        if ($vendor_db->field_exists('logo', 'erp_clients')) {
            $cols[] = 'logo';
        }

        $row = $vendor_db
            ->select(implode(', ', $cols))
            ->from('erp_clients')
            ->limit(1)
            ->get()
            ->row_array();

        return is_array($row) ? $row : array();
    }

    private function getOrderStatusText($status)
    {
        $status = (int) $status;
        $map = array(
            1 => 'Pending',
            2 => 'Processing',
            3 => 'Shipped',
            4 => 'Delivered',
            5 => 'Cancelled',
            6 => 'Return Processed',
            0 => 'Confirmed'
        );
        return isset($map[$status]) ? $map[$status] : 'Unknown (' . $status . ')';
    }

    private function getVendorDB($vendor_id)
    {
        $vendor_id = (int) $vendor_id;
        if ($vendor_id <= 0) {
            return null;
        }

        // Fetch DB info from erp_clients
        $client = $this->master_db
            ->select('database_name, status')
            ->from('erp_clients')
            ->where('id', $vendor_id)
            ->where('status', 'active')
            ->limit(1)
            ->get()
            ->row_array();

        if (empty($client) || empty($client['database_name'])) {
            log_message('error', 'Vendor DB not found for ID: ' . $vendor_id);
            return null;
        }

        // Use full config array for dynamic DB connection
        $vendor_db = $this->load->database(array(
            'hostname' => $this->master_db->hostname,
            'username' => $this->master_db->username,
            'password' => $this->master_db->password,
            'database' => $client['database_name'],
            'dbdriver' => 'mysqli',
            'dbprefix' => '',
            'pconnect' => FALSE,
            'db_debug' => (ENVIRONMENT !== 'production'),
            'cache_on' => FALSE,
            'cachedir' => '',
            'char_set' => 'utf8',
            'dbcollat' => 'utf8_general_ci'
        ), TRUE);

        if (!$vendor_db || $vendor_db->conn_id === FALSE) {
            log_message('error', 'Failed to connect to vendor DB for ID: ' . $vendor_id);
            return null;
        }

        return $vendor_db;
    }
    public function getSchoolClasses($school_id)
    {
        $school_id = (int) $school_id;

        if ($school_id <= 0) {
            return array();
        }

        // STEP 1: Get vendor_id
        $row = $this->master_db
            ->select('vendor_id')
            ->from('erp_pos_agent_school_access')
            ->where('school_id', $school_id)
            ->where('status', 1)
            ->limit(1)
            ->get()
            ->row_array();

        if (empty($row['vendor_id'])) {
            return array();
        }

        // STEP 2: Load vendor DB dynamically
        $vendor_id = $row['vendor_id'];
        $vendor_db = $this->getVendorDB($vendor_id);

        if (!$vendor_db) {
            return array();
        }

        // STEP 3: Fetch classes
        $classes = $vendor_db
            ->select('id, class_name')
            ->from('classes')
            ->get()
            ->result_array();

        return $classes;
    }
    public function getSchoolUniforms($school_id)
    {
        $school_id = (int) $school_id;

        if ($school_id <= 0) {
            return array();
        }

        // STEP 1: Get vendor_id
        $row = $this->master_db
            ->select('vendor_id')
            ->from('erp_pos_agent_school_access')
            ->where('school_id', $school_id)
            ->where('status', 1)
            ->limit(1)
            ->get()
            ->row_array();

        if (empty($row['vendor_id'])) {
            return array();
        }

        // STEP 2: Load vendor DB dynamically
        $vendor_id = $row['vendor_id'];
        $vendor_db = $this->getVendorDB($vendor_id);

        if (!$vendor_db) {
            return array();
        }

        // Get vendor domain from master DB
        $vendor = $this->master_db
            ->select('domain')
            ->from('erp_clients')
            ->where('id', $vendor_id)
            ->get()
            ->row_array();
        $base_url = '';
        if (!empty($vendor['domain'])) {
            $base_url = rtrim('https://' . $vendor['domain'], '/') . '/';
        }

        // STEP 3: Fetch uniforms with image, size, and price
        $uniforms = $vendor_db
            ->select('u.*, img.image_path')
            ->from('erp_uniforms u')
            ->join('(SELECT uniform_id, image_path FROM erp_uniform_images WHERE image_path IS NOT NULL AND image_path != "" GROUP BY uniform_id) img', 'img.uniform_id = u.id', 'left')
            ->where('u.school_id', $school_id)
            ->where('u.status', 1)
            ->order_by('u.id', 'ASC')
            ->get()
            ->result_array();

        // Only return required fields for API, prefix image_path with domain
        $result = array();
        foreach ($uniforms as $row) {
            $sizes = $vendor_db
                ->select('s.id, s.name as size_name, usp.selling_price')
                ->from('erp_sizes s')
                ->join('erp_uniform_size_prices usp', 'usp.size_id = s.id AND usp.uniform_id = ' . $row['id'], 'left')
                ->where('s.size_chart_id', $row['size_chart_id'])
                ->get()
                ->result_array();
            $img_url = '';
            if (!empty($row['image_path'])) {
                $img_url = $base_url . ltrim($row['image_path'], '/');
            }
            $result[] = array(
                'id' => $row['id'],
                'product_name' => $row['product_name'],
                'image_path' => $img_url,
                'size' => $sizes,
                'price' => $row['price'],
                'gst_percentage' => (float) $row['gst_percentage'],
                'class_id' => $row['class_id'],
                'branch_id' => $row['branch_id'],
                'board_id' => $row['board_id'],
            );
        }
        return $result;
    }
    public function getSchoolupiInfo($school_id)
    {
        $school_id = (int) $school_id;

        if ($school_id <= 0) {
            return array();
        }

        // STEP 1: Get vendor_id
        $row = $this->master_db
            ->select('vendor_id')
            ->from('erp_pos_agent_school_access')
            ->where('school_id', $school_id)
            ->where('status', 1)
            ->limit(1)
            ->get()
            ->row_array();

        if (empty($row['vendor_id'])) {
            return array();
        }

        $upiInfo = $this->master_db
            ->select('qr_image_path, upi_id')
            ->from('erp_school_upi_qr')
            ->where('vendor_id', $row['vendor_id'])
            ->where('school_id', $school_id)
            ->get()
            ->row_array();
        $baseurl = $this->master_db
            ->select('domain')
            ->from('erp_clients')
            ->where('id', $row['vendor_id'])
            ->get()
            ->row_array();
        // $qr_image_url = 'https://' . $baseurl['domain'] . '/' . ltrim($upiInfo['qr_image_path'], '/');
        $qr_image_url = 'http://' . '192.168.1.106/pos-module/erp_books_live' . '/' . ltrim($upiInfo['qr_image_path'], '/');


        $result = array(
            'upi_id' => isset($upiInfo['upi_id']) ? $upiInfo['upi_id'] : '',
            'qr_image_url' => isset($qr_image_url) ? $qr_image_url : ''
        );
        return $result;
    }

    /**
     * Check if Agent Has Access to School
     *
     * @param int $agent_id
     * @param int $school_id
     * @return bool
     */
    public function agentHasSchoolAccess($agent_id, $school_id)
    {
        $agent_id = (int) $agent_id;
        $school_id = (int) $school_id;

        if ($agent_id <= 0 || $school_id <= 0) {
            return FALSE;
        }

        $count = $this->master_db
            ->from('erp_pos_agent_school_access')
            ->where('agent_user_id', $agent_id)
            ->where('school_id', $school_id)
            ->where('status', 1)
            ->count_all_results();

        return (int) $count > 0;
    }

    /**
     * ========== SCHOOL DATA ==========
     */

    /**
     * Get School Details
     *
     * @param int $school_id
     * @return array
     */
    public function getSchoolDetails($school_id, $vendor_id = 0)
    {
        $school_id = (int) $school_id;
        $vendor_id = (int) $vendor_id;
        if ($school_id <= 0) {
            return array();
        }

        if ($vendor_id <= 0) {
            return array();
        }

        $vendor_db = $this->getVendorDbConnection($vendor_id);
        if (!$vendor_db || $vendor_db->conn_id === FALSE) {
            return array();
        }

        $school = $vendor_db
            ->select('id, school_name,  contact_person, contact_phone, email, status, created_at')
            ->from('erp_schools')
            ->where('id', $school_id)
            ->get()
            ->row_array();

        if (!$school) {
            return array();
        }

        return array(
            'school_id' => (int) $school['id'],
            'school_name' => $school['school_name'],
            'contact_person' => $school['contact_person'],
            'contact_phone' => $school['contact_phone'],
            'email' => $school['email'],
            'status' => (int) $school['status'],
            'created_at' => $school['created_at']
        );
    }

    /**
     * Get agent-school access row from master DB.
     *
     * @param int $agent_id
     * @param int $school_id
     * @return array
     */
    public function getAgentSchoolAccessRow($agent_id, $school_id)
    {
        $agent_id = (int) $agent_id;
        $school_id = (int) $school_id;

        if ($agent_id <= 0 || $school_id <= 0) {
            return array();
        }

        $row = $this->master_db
            ->select('agent_user_id, school_id, vendor_id, can_uniform, can_bookset, upi_qr_id')
            ->from('erp_pos_agent_school_access')
            ->where('agent_user_id', $agent_id)
            ->where('school_id', $school_id)
            ->where('status', 1)
            ->limit(1)
            ->get()
            ->row_array();

        return $row ? $row : array();
    }

    /**
     * Get school names/locations from vendor DB.
     *
     * @param int $vendor_id
     * @param array $school_ids
     * @return array
     */
    private function getVendorSchoolMap($vendor_id, $school_ids = array())
    {
        $vendor_id = (int) $vendor_id;
        if ($vendor_id <= 0 || empty($school_ids) || !is_array($school_ids)) {
            return array();
        }

        $school_ids = array_values(array_unique(array_filter(array_map('intval', $school_ids))));
        if (empty($school_ids)) {
            return array();
        }
        $vendor = $this->master_db
            ->select('domain')
            ->from('erp_clients')
            ->where('id', $vendor_id)
            ->get()
            ->row_array();

        $base_url = '';
        if (!empty($vendor['domain'])) {
            $base_url = rtrim("https://" . $vendor['domain'], '/') . '/';
        }

        $vendor_db = $this->getVendorDbConnection($vendor_id);
        if (!$vendor_db || $vendor_db->conn_id === FALSE) {
            return array();
        }

        $has_country_id = $vendor_db->field_exists('country_id', 'erp_schools');
        $has_state_id = $vendor_db->field_exists('state_id', 'erp_schools');
        $has_city_id = $vendor_db->field_exists('city_id', 'erp_schools');
        $has_logo = $vendor_db->field_exists('logo', 'erp_schools');

        $vendor_db->from('erp_schools s');
        $vendor_db->where_in('s.id', $school_ids);
        $vendor_db->select('s.id, s.school_name, s.address');
        $vendor_db->select($has_country_id ? 's.country_id' : '0 AS country_id', FALSE);
        $vendor_db->select($has_state_id ? 's.state_id' : '0 AS state_id', FALSE);
        $vendor_db->select($has_city_id ? 's.city_id' : '0 AS city_id', FALSE);
        $vendor_db->select($has_logo ? 's.logo AS school_image' : "'' AS school_image", FALSE);

        if ($vendor_db->table_exists('states') && $has_state_id) {
            $vendor_db->join('states st', 'st.id = s.state_id', 'left');
            $vendor_db->select('st.name AS state_name');

            if (!$has_country_id && $vendor_db->field_exists('country_id', 'states')) {
                $vendor_db->select('st.country_id AS country_id', FALSE);
            }
        } else {
            $vendor_db->select("'' AS state_name", FALSE);
        }

        if ($vendor_db->table_exists('cities') && $has_city_id) {
            $vendor_db->join('cities ct', 'ct.id = s.city_id', 'left');
            $vendor_db->select('ct.name AS city_name');
        } else {
            $vendor_db->select("'' AS city_name", FALSE);
        }

        if ($vendor_db->table_exists('countries')) {
            if ($has_country_id) {
                $vendor_db->join('countries co', 'co.id = s.country_id', 'left');
                $vendor_db->select('co.name AS country_name');
            } elseif ($vendor_db->table_exists('states') && $has_state_id && $vendor_db->field_exists('country_id', 'states')) {
                $vendor_db->join('countries co', 'co.id = st.country_id', 'left');
                $vendor_db->select('co.name AS country_name');
            } else {
                $vendor_db->select("'' AS country_name", FALSE);
            }
        } else {
            $vendor_db->select("'' AS country_name", FALSE);
        }

        $rows = $vendor_db->get()->result_array();

        $image_map = array();
        if ($vendor_db->table_exists('erp_school_images')) {
            $images = $vendor_db
                ->select('school_id, image_path, is_primary, id')
                ->from('erp_school_images')
                ->where_in('school_id', $school_ids)
                ->order_by('is_primary', 'DESC')
                ->order_by('id', 'ASC')
                ->get()
                ->result_array();

            foreach ($images as $img) {
                $sid = (int) $img['school_id'];
                if (!isset($image_map[$sid]) && !empty($img['image_path'])) {
                    $image_map[$sid] = (string) $img['image_path'];
                }
            }
        }

        $mapped = array();
        foreach ($rows as $row) {
            $sid = (int) $row['id'];
            $raw_image = isset($row['school_image']) ? trim((string) $row['school_image']) : '';
            if ($raw_image === '' && isset($image_map[$sid])) {
                $raw_image = $image_map[$sid];
            }

            $country_name = isset($row['country_name']) ? trim((string) $row['country_name']) : '';
            $state_name = isset($row['state_name']) ? trim((string) $row['state_name']) : '';
            $city_name = isset($row['city_name']) ? trim((string) $row['city_name']) : '';

            $location_parts = array();
            if ($country_name !== '') {
                $location_parts[] = $country_name;
            }
            if ($state_name !== '') {
                $location_parts[] = $state_name;
            }
            if ($city_name !== '') {
                $location_parts[] = $city_name;
            }

            $mapped[$sid] = array(
                'school_name' => isset($row['school_name']) ? (string) $row['school_name'] : '',
                'school_image' => $raw_image,
                'school_image_url' => $raw_image !== '' ? $base_url . ltrim($raw_image, '/') : '',
                'address' => isset($row['address']) ? (string) $row['address'] : '',
                'country_id' => isset($row['country_id']) ? (int) $row['country_id'] : 0,
                'state_id' => isset($row['state_id']) ? (int) $row['state_id'] : 0,
                'city_id' => isset($row['city_id']) ? (int) $row['city_id'] : 0,
                'country_name' => $country_name,
                'state_name' => $state_name,
                'city_name' => $city_name,
                'location' => !empty($location_parts) ? implode(', ', $location_parts) : ''
            );
        }

        return $mapped;
    }

    /**
     * Convert relative image path to absolute URL.
     *
     * @param string $image_path
     * @return string
     */
    private function normalizeImageUrl($image_path)
    {
        $image_path = trim((string) $image_path);
        if ($image_path === '') {
            return '';
        }

        if (strpos($image_path, 'http://') === 0 || strpos($image_path, 'https://') === 0) {
            return $image_path;
        }

        return rtrim(base_url(), '/') . '/' . ltrim($image_path, '/');
    }

    /**
     * Get vendor DB connection using vendor/client id.
     *
     * @param int $vendor_id
     * @return object|false
     */
    private function getVendorDbConnection($vendor_id)
    {
        $vendor_id = (int) $vendor_id;
        if ($vendor_id <= 0) {
            return FALSE;
        }

        if (isset($this->vendor_db_connections[$vendor_id]) && is_object($this->vendor_db_connections[$vendor_id])) {
            return $this->vendor_db_connections[$vendor_id];
        }

        $vendor = $this->master_db
            ->select('id, database_name, status')
            ->from('erp_clients')
            ->where('id', $vendor_id)
            ->where('status', 'active')
            ->limit(1)
            ->get()
            ->row_array();

        if (empty($vendor) || empty($vendor['database_name'])) {
            return FALSE;
        }

        $vendor_db = $this->load->database(array(
            'hostname' => $this->master_db->hostname,
            'username' => $this->master_db->username,
            'password' => $this->master_db->password,
            'database' => $vendor['database_name'],
            'dbdriver' => 'mysqli',
            'dbprefix' => '',
            'pconnect' => FALSE,
            'db_debug' => (ENVIRONMENT !== 'production'),
            'cache_on' => FALSE,
            'cachedir' => '',
            'char_set' => 'utf8',
            'dbcollat' => 'utf8_general_ci'
        ), TRUE);

        if (!$vendor_db || $vendor_db->conn_id === FALSE) {
            return FALSE;
        }

        $this->vendor_db_connections[$vendor_id] = $vendor_db;
        return $vendor_db;
    }

    /**
     * Get School UPI Details
     *
     * @param int $school_id
     * @return array
     */
    public function getSchoolUpiDetails($school_id)
    {
        $school_id = (int) $school_id;
        if ($school_id <= 0) {
            return array();
        }

        $upi_details = $this->master_db
            ->select('id, upi_id, qr_image_path, payment_note')
            ->from('erp_school_upi_qr')
            ->where('school_id', $school_id)
            ->where('status', 1)
            ->get()
            ->row_array();

        return $upi_details ? $upi_details : array();
    }

    /**
     * ========== PRODUCT DATA ==========
     */

    /**
     * Get School Products by Category
     *
     * @param int $school_id
     * @param string $category
     * @return array
     */
    public function getSchoolProducts($school_id, $category = 'all')
    {
        $school_id = (int) $school_id;
        if ($school_id <= 0) {
            return array();
        }

        $category = trim((string) $category);

        $this->master_db->select('id, name, description, price, category, image_path, stock_quantity, created_at');
        $this->master_db->from('erp_products');
        $this->master_db->where('school_id', $school_id);
        $this->master_db->where('status', 1);

        if ($category !== 'all' && !empty($category)) {
            $this->master_db->where('category', $category);
        }

        $this->master_db->order_by('name', 'ASC');

        $products = $this->master_db->get()->result_array();

        $formatted = array();
        foreach ($products as $product) {
            $formatted[] = array(
                'product_id' => (int) $product['id'],
                'name' => $product['name'],
                'description' => $product['description'],
                'price' => (float) $product['price'],
                'category' => $product['category'],
                'image_path' => $product['image_path'],
                'stock_quantity' => (int) $product['stock_quantity'],
                'created_at' => $product['created_at']
            );
        }

        return $formatted;
    }

    /**
     * Get Product Details
     *
     * @param int $product_id
     * @return array
     */
    public function getProductDetails($product_id)
    {
        $product_id = (int) $product_id;
        if ($product_id <= 0) {
            return array();
        }

        $product = $this->master_db
            ->select('*')
            ->from('erp_products')
            ->where('id', $product_id)
            ->where('status', 1)
            ->get()
            ->row_array();

        return $product ? $product : array();
    }

    /**
     * ========== ORDERS ==========
     */

    /**
     * Create Order
     *
     * @param array $order_data
     * @return int|false
     */
    public function createOrder($order_data = array())
    {
        if (empty($order_data) || !is_array($order_data)) {
            return FALSE;
        }

        $this->master_db->insert('erp_orders', $order_data);

        if ($this->master_db->affected_rows() > 0) {
            return (int) $this->master_db->insert_id();
        }

        return FALSE;
    }

    /**
     * Get Agent Orders
     *
     * @param int $agent_id
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getAgentOrders($agent_id, $limit = 20, $offset = 0)
    {
        $agent_id = (int) $agent_id;
        if ($agent_id <= 0) {
            return array();
        }

        $orders = $this->master_db
            ->select('id, order_number, school_id, total_amount, status, created_at')
            ->from('erp_orders')
            ->where('agent_id', $agent_id)
            ->order_by('created_at', 'DESC')
            ->limit((int) $limit, (int) $offset)
            ->get()
            ->result_array();

        $formatted = array();
        foreach ($orders as $order) {
            $formatted[] = array(
                'order_id' => (int) $order['id'],
                'order_number' => $order['order_number'],
                'school_id' => (int) $order['school_id'],
                'total_amount' => (float) $order['total_amount'],
                'status' => $order['status'],
                'created_at' => $order['created_at']
            );
        }

        return $formatted;
    }

    /**
     * Get Order Details
     *
     * @param int $order_id
     * @param int $agent_id
     * @return array
     */
    public function getOrderDetails($order_id, $agent_id = 0)
    {
        $order_id = (int) $order_id;
        if ($order_id <= 0) {
            return array();
        }

        $this->master_db->select('*');
        $this->master_db->from('erp_orders');
        $this->master_db->where('id', $order_id);

        if ((int) $agent_id > 0) {
            $this->master_db->where('agent_id', (int) $agent_id);
        }

        $order = $this->master_db->get()->row_array();

        return $order ? $order : array();
    }
}
