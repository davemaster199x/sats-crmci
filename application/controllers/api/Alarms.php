<?php

class Alarms extends MY_ApiController {

    public function __construct() {
        parent::__construct();

        $this->load->model('tech_model');
        $this->load->model('tech_run_model');
        $this->load->model('tech_locations_model');
    }

    public function add_alarm() {
        $this->load->helper(array('form', 'url'));

        $techId = $_POST['tech_id'];
        $jobId = $_POST['job_id'];
        $alarm_key = $_POST['alarm_key'];

        $lastLocation = $this->tech_locations_model->loadLastLocation($techId);

        $latitude =  $lastLocation['latitude'];
        $longitude =  $lastLocation['longitude'];

        if ($alarm["new"] == 0) {
            // add default values to existing alarms since by default, they are checked on CI
            $alarm["ts_fixing"] = 1;
            $alarm["ts_cleaned"] = 1;
            $alarm["ts_newbattery"] = 1;
            $alarm["ts_testbutton"] = 1;
            $alarm["ts_meetsas1851"] = 1;
            $alarm["ts_visualind"] = 1;
            $alarm["ts_alarm_sounds_other"] = 1;
        }

        $recBattExp = $alarm["rec_batt_exp"];
        // convert rec_batt_exp to YYYY-MM-DD
        if (isset($recBatExp) && preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $recBattExp) != 1) {
            $parts = explode("/", $recBattExp);
            $alarm["rec_batt_exp"] = "20{$parts[1]}-{$parts[0]}-01";
        }

        $this->db->trans_start();

        $alarm = json_decode($_POST['alarm'], true);
        $alarm["job_id"] = $jobId;
        $alarm["alarm_job_type_id"] = 2;

        // save new alarm
        $success = $this->db->set($alarm)
            ->insert("alarm");

        // get new alarm from database
        $newAlarmId = $this->db->insert_id();
        $newAlarm = $this->db->select()
            ->from("alarm")
            ->where("alarm_id", $newAlarmId)
            ->limit(1)
            ->get()->row_array();

        $this->db->trans_complete();

        $config = array(
            'upload_path' => "./images/alarm_images",
            'allowed_types' => "gif|jpg|png|jpeg|pdf",
            'overwrite' => TRUE
        );
    
        $this->load->library('upload',$config);

        $cpt = count($_FILES['files']['name']);
        $cpt1 = count($_FILES['files1']['name']);

        for($back = 0; $back < $cpt; $back++)
        {
            //$_FILES["file"]["name"] = "alarm_back"."_". date("YmdHis")."_".$latitude."_".$longitude;
            $_FILES["file"]["name"] = $jobId."_alarm_expiry".$alarm_key."_". date("YmdHis") ."_". $_FILES["files"]["name"][$back];
            $_FILES["file"]["type"] = $_FILES["files"]["type"][$back];
            $_FILES["file"]["tmp_name"] = $_FILES["files"]["tmp_name"][$back];
            $_FILES["file"]["error"] = $_FILES["files"]["error"][$back];
            $_FILES["file"]["size"] = $_FILES["files"]["size"][$back];

            if($this->upload->do_upload('file'))
            {   
                $alarm_img["alarm_id"] = $newAlarmId;
                $alarm_img["expiry_image_filename"] = $_FILES["file"]["name"];
                $alarm_img["image_lat"] = $latitude;
                $alarm_img["image_lng"] = $longitude;
                $alarm_img["created"] = date("YmdHis");
                $alarm_img["active"] = 1;

                // save new image filename in table 
                $this->db->set($alarm_img)
                ->insert("alarm_images");
            }
        }

        for($situ = 0; $situ < $cpt1; $situ++)
        {
            $_FILES["file"]["name"] = $jobId."_alarm_location".$alarm_key."_". date("YmdHis") ."_". $_FILES["files1"]["name"][$situ];
            $_FILES["file"]["type"] = $_FILES["files1"]["type"][$situ];
            $_FILES["file"]["tmp_name"] = $_FILES["files1"]["tmp_name"][$situ];
            $_FILES["file"]["error"] = $_FILES["files1"]["error"][$situ];
            $_FILES["file"]["size"] = $_FILES["files1"]["size"][$situ];

            if($this->upload->do_upload('file'))
            {   
                $alarm_img["alarm_id"] = $newAlarmId;
                $alarm_img["location_image_filename"] = $_FILES["file"]["name"];
                $alarm_img["image_lat"] = $latitude;
                $alarm_img["image_lng"] = $longitude;
                $alarm_img["created"] = date("YmdHis");
                $alarm_img["active"] = 1;

                // save new image filename in table 
                $postData["location_image_filename"] = $alarm_img["location_image_filename"];
                $this->db->set($postData)
                
                ->where("alarm_id", $newAlarmId)
                ->update("alarm_images");
            }
        }

        if($success){
            $this->api->setStatusCode(201);
            $this->api->setSuccess(true);
            $this->api->setMessage('Alarm Added.');
            $this->api->putData('alarm', $newAlarm); // add new alarm to response
        }
        else {
            $this->api->setSuccess(false);
            $this->api->setMessage('Alarm could not be added.');
        }
    }

    public function update_alarm($alarmId) {
        $this->api->assertMethod('patch');

        $postData = $this->api->getPostData();

        $recBattExp = $postData["rec_batt_exp"];

        // convert rec_batt_exp to YYYY-MM-DD
        if (isset($recBattExp) && preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $recBattExp) != 1) {
            $parts = explode("/", $recBattExp);
            $postData["rec_batt_exp"] = "20{$parts[1]}-{$parts[0]}-01";
        }

        $success = $this->db->set($postData)
            ->where("alarm_id", $alarmId)
            ->update("alarm");

        if($success){
            $this->api->setStatusCode(200);
            $this->api->setSuccess(true);
            $this->api->setMessage('Alarm updated.');
        }
        else {
            $this->api->setSuccess(false);
            $this->api->setMessage('Alarm could not be updated.');
        }
    }

    public function update_alarm_image() {
        $jobId = $_POST['job_id'];
        $alarmId = $_POST['alarm_id'];

        $tmp_tech = $this->tech_model->getTechID($jobId);
        $techId = $tmp_tech['assigned_tech'];

        $lastLocation = $this->tech_locations_model->loadLastLocation($techId);

        $latitude =  $lastLocation['latitude'];
        $longitude =  $lastLocation['longitude'];

        $config = array(
            'upload_path' => "./images/alarm_images",
            'allowed_types' => "gif|jpg|png|jpeg|pdf",
            'overwrite' => TRUE
        );
    
        $this->load->library('upload',$config);

        $cpt = count($_FILES['files']['name']);
        $cpt1 = count($_FILES['files1']['name']);

        for($back = 0; $back < $cpt; $back++)
        {
            //$_FILES["file"]["name"] = "alarm_back"."_". date("YmdHis")."_".$latitude."_".$longitude;
            $_FILES["file"]["name"] = $jobId."_alarm_expiry_". date("YmdHis") ."_". $_FILES["files"]["name"][$back];
            $_FILES["file"]["type"] = $_FILES["files"]["type"][$back];
            $_FILES["file"]["tmp_name"] = $_FILES["files"]["tmp_name"][$back];
            $_FILES["file"]["error"] = $_FILES["files"]["error"][$back];
            $_FILES["file"]["size"] = $_FILES["files"]["size"][$back];

            if($this->upload->do_upload('file'))
            {   
                $alarm_img["alarm_id"] = $alarmId;
                $alarm_img["expiry_image_filename"] = $_FILES["file"]["name"];
                $alarm_img["image_lat"] = $latitude;
                $alarm_img["image_lng"] = $longitude;
                $alarm_img["created"] = date("YmdHis");
                $alarm_img["active"] = 1;

                // save new image filename in table 
                $success = $this->db->set($alarm_img)
                ->insert("alarm_images");
            }
        }

        for($situ = 0; $situ < $cpt1; $situ++)
        {
            $_FILES["file"]["name"] = $jobId."_alarm_location_". date("YmdHis") ."_". $_FILES["files1"]["name"][$situ];
            $_FILES["file"]["type"] = $_FILES["files1"]["type"][$situ];
            $_FILES["file"]["tmp_name"] = $_FILES["files1"]["tmp_name"][$situ];
            $_FILES["file"]["error"] = $_FILES["files1"]["error"][$situ];
            $_FILES["file"]["size"] = $_FILES["files1"]["size"][$situ];

            if($this->upload->do_upload('file'))
            {   
                $alarm_img["alarm_id"] = $alarmId;
                $alarm_img["location_image_filename"] = $_FILES["file"]["name"];
                $alarm_img["image_lat"] = $latitude;
                $alarm_img["image_lng"] = $longitude;
                $alarm_img["created"] = date("YmdHis");
                $alarm_img["active"] = 1;

                // save new image filename in table 
                $postData["location_image_filename"] = $alarm_img["location_image_filename"];
                $success = $this->db->set($postData)
                
                ->where("alarm_id", $alarmId)
                ->update("alarm_images");
            }
        }

        if($success){
            $this->api->setStatusCode(200);
            $this->api->setSuccess(true);
            $this->api->setMessage('Alarm updated.');
        }
        else {
            $this->api->setSuccess(false);
            $this->api->setMessage('Alarm could not be updated.');
        }
    }


    public function delete_alarm() {
        $this->api->assertMethod("delete");

        $jobId = $this->input->get("job_id");
        $alarmId = $this->input->get("alarm_id");

        $this->db->trans_start();
        $success = $this->db
            ->where("job_id", $jobId)
            ->where("alarm_id", $alarmId)
            ->limit(1)
            ->delete("alarm");

        // $success = $this->db
        //     ->where("alarm_id", $alarmId)
        //     ->delete("alarm_images");

        $this->db->trans_complete();
        if($success){
            $this->api->setStatusCode(200);
            $this->api->setSuccess(true);
            $this->api->setMessage('Alarm deleted.');
        }
        else {
            $this->api->setSuccess(false);
            $this->api->setMessage('Alarm could not be deleted.');
        }
    }

}