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
        echo 'API is working';
        exit;
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
    public function agent_categories()
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
                $categories = $this->App_model->getAgentCategories($agent_id);
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
                $response = array('status' => 200, 'message' => 'Success', 'uniforms' => $uniforms );
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
                $response = array('status' => 200, 'message' => 'Success', 'upi_info' => $upiInfo );
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

    private function simple_json_output($response = array())
    {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
}
