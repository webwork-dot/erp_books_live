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

    private function simple_json_output($response = array())
    {
        ob_clean();
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}
