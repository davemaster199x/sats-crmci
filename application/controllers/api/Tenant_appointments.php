<?php
header('Access-Control-Allow-Origin: *');
require APPPATH . './libraries/REST_Controller.php';
class Tenant_appointments extends REST_Controller {

	function __construct($config = 'rest') {
		parent::__construct($config);
        $this->load->model('Tenant_api_model', 'tam');
	} 

    function appointment_post() {

        $property_id = $this->input->post('property_id');
        
        $data['status'] = $this->tam->tenant_job($property_id);
        $job_status = $data['status'][0]->status;
        $job_id = $data['status'][0]->id;
        
        if($job_status == "Booked"){
            $data['tech'] = $this->tam->tenant_assigned_tech($job_id);
            $this->response($data['tech'], 200);
        }
        else{
            $data['retest'] = $this->tam->get_retest_property($property_id);
            $this->response($data['retest'], 200);
        }
    }

    function cancelled_post() {

        $property_id = $this->input->post('property_id');
        $cancelled = $this->input->post('cancelled');
        
        $data['status'] = $this->tam->tenant_job($property_id);
        $job_status = $data['status'][0]->status;
        $job_id = $data['status'][0]->id;
        
        if($job_status == "Booked"){
            $update_data = array(
                'status' => "To Be Booked"
            );
            $data['update'] = $this->tam->set_job_tbb($job_id, $update_data);
            $this->response($data['update'], 200);
        }
        else{
            $data['retest'] = $this->tam->get_retest_property($property_id);
            $this->response($data['retest'], 200);
        }
    }
}
