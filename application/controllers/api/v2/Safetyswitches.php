<?php

class Safetyswitches extends MY_ApiController
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('tech_model');
        $this->load->model('tech_run_model');
        $this->load->model('Safety_switch_model', 'safety_switch');
    }

    /*
    * Bhagvan: not using this method anymore in api v2
    */
    # function to insert safety switch version 2
    public function add()
    {
        $this->api->assertMethod('put');

        $postData = $this->api->getPostData();
        $ids = [];

        if (!empty($postData["safety_switches"])) {
            foreach ($postData["safety_switches"] as $switch_data) {
                $ids[] = $this->safety_switch->set_data($switch_data);
            }
        }

        if (!empty($ids)) {
            $query_param = array(
                "conditions" => array(
                    array(
                        "type"      => "where_in",
                        "column"    => "safety_switch_id",
                        "value"     => $ids
                    )
                )
            );

            $safetySwitches = $this->safety_switch->get($query_param);

            $this->api->setStatusCode(201);
            $this->api->setSuccess(true);
            $this->api->setMessage('Safety Switches Added.');
            $this->api->putData('safety_switches', $safetySwitches);
        } else {
            $this->api->setSuccess(false);
            $this->api->setMessage('Safety Switches could not be added.');
        }
    }

    /*
    * Bhagvan: not using this method anymore in api v2
    */
    # function to update safety switch version 2
    public function update($safetySwitchId = "")
    {
        $this->api->assertMethod('patch');

        $postData = $this->api->getPostData();

        if (!empty($postData["safety_switches"])) {
            foreach ($postData["safety_switches"] as $switch_data) {
                $ids[] = $this->safety_switch->set_data($switch_data);
            }
        }

        if (!empty($ids)) {
            $this->api->setStatusCode(200);
            $this->api->setSuccess(true);
            $this->api->setMessage('Safety Switch updated.');
        } else {
            $this->api->setSuccess(false);
            $this->api->setMessage('Safety Switch could not be updated.');
        }
    }

    /*
    * Bhagvan: not using this method anymore in api v2
    */
    // delete one safety switch
    public function delete()
    {
        $this->api->assertMethod("delete");

        $jobId = $this->input->get("job_id");
        $safetySwitchId = $this->input->get("safety_switch_id");

        $this->db->trans_start();
        $success = $this->db
            ->where("job_id", $jobId)
            ->where("safety_switch_id", $safetySwitchId)
            ->limit(1)
            ->delete("safety_switch");

        $this->db->trans_complete();
        if ($success) {
            $this->api->setStatusCode(200);
            $this->api->setSuccess(true);
            $this->api->setMessage('Safety Switch deleted.');
        } else {
            $this->api->setSuccess(false);
            $this->api->setMessage('Safety Switch could not be deleted.');
        }
    }

    /*
    * Bhagvan: not using this method anymore in api v2
    */
    public function upload_image()
    {
        $this->api->assertMethod('post');
        $postData = $this->api->getPostData();

        $jobId = $postData["job_id"];
        $fileName = $postData["file_name"];
        // get image from base64 data
        $image = base64_decode($postData["base64"]);

        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        $imageName = "switchboard{$job_id}" . rand() . date("YmdHis") . ".{$ext}"; // generate unique file name

        $success = file_put_contents("{$_SERVER['DOCUMENT_ROOT']}/uploads/switchboard_image/{$imageName}", $image); // move file to its proper directory

        if ($success) {
            $this->db->trans_start();

            $this->db->set([
                "ss_image" => $imageName,
            ])
                ->where("id", $jobId)
                ->update("jobs"); // save ss_image for the job

            $this->db->trans_complete();

            $success = $this->db->trans_status();

            if ($success) {
                // put ss_image data to response
                $this->api->putData("ss_image", "{$this->config->item("crmci_link")}/uploads/switchboard_image/{$imageName}");
            }
        }

        $this->api->setSuccess($success);
    }

    
}
