<?php
header('Access-Control-Allow-Origin: *');
require APPPATH . './libraries/REST_Controller.php';
class Tenant_reports extends REST_Controller {

	function __construct($config = 'rest') {
		parent::__construct($config);
        $this->load->model('Tenant_api_model', 'tam');
	} 

    /*
    * Bhagvan: not using this method in mobile App APIs
    */
    function reports_post() {

        $token = $this->input->request_headers()['Authorization'];

        $jwt = new JWT();
        $jwtkey = "SecretKey";

        $response = $jwt->decode($token,$jwtkey,'HS256');
        $data = array(
            'status' => 200,
            'message' => 'success',
            'data' => []
        );

        if (!$response) { 
            $data['status'] = 500;
            $data['message'] = 'invalid token';
            $this->response($data, 200);
        } elseif ($response == 'expired') {
            $data['status'] = 500;
            $data['message'] = 'expired token';
            $this->response($data, 200);
        } 
        else {

            $property_id = $this->input->post('property_id');
            $tenant_id   = $this->input->post('tenant_id');
            $alarm_issue = $this->input->post('alarm_issue');
            $reason      = $this->input->post('reason');
            
            $report_data = array(
                'tenant_id'        => $tenant_id,
                'property_id'      => $property_id,
                'alarm_issue'      => $alarm_issue,
                'reason'           => $reason
            );

            $add_report = $this->tam->insert_report($report_data);
            $insert_id = $this->db->insert_id();

            if($insert_id > 0){
                $success_data = array(
                    'insert_id' => $insert_id,
                    'status'    => 200
                );
            }

            $this->response($success_data, 200);
        }
    }

    /*
    * Bhagvan: not using this method in mobile App APIs
    */
    function list_post() {
        $token = $this->input->request_headers()['Authorization'];

        $jwt = new JWT();
        $jwtkey = "SecretKey";

        $response = $jwt->decode($token,$jwtkey,'HS256');
        $data = array(
            'status' => 200,
            'message' => 'success',
            'data' => []
        );

        if (!$response) { 
            $data['status'] = 500;
            $data['message'] = 'invalid token';
            $this->response($data, 200);
        } elseif ($response == 'expired') {
            $data['status'] = 500;
            $data['message'] = 'expired token';
            $this->response($data, 200);
        } 
        else {
            $reports = $this->db->get('app_report_response')->result();
            $this->response($reports, 200);
        }
	}
}
