<?php

class Tenant_alarms extends MY_ApiController {

    public function __construct() {
        parent::__construct();

        $this->load->model('Tenant_api_model', 'tam');
    }

    /*
    * Bhagvan: not using this method in mobile App APIs
    */
    public function alarms() {
        $property_id = trim($this->api->getPostData('property_id'));
        
        $data['id'] = $this->tam->tenant_job($property_id);
        $job_id = $data['id'][0]->id;

        /*
        echo $property_id;
        echo "<br /><br />";
        echo $job_id;
        exit();
        */

        $data['alarms'] = $this->tam->tenant_alarms($property_id, $job_id);
        //echo $this->db->last_query();
        //exit();

        if(!empty($data['alarms'])){
            $this->api->setStatusCode(200);
            $this->api->setSuccess(true);

            $this->api->putData('tenant_data', $data);
            return;
        }
        
        $this->api->setStatusCode(200);
        $this->api->setSuccess(false);
        $this->api->setMessage('No data available!');
    }

}
