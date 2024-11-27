<?php

class Alarms extends MY_ApiController
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('tech_model');
        $this->load->model('tech_run_model');
        $this->load->model('tech_locations_model');
        $this->load->model('alarms_model', 'alarms');
    }

    /*
    * Bhagvan: not using this method anymore in api v2
    */
    public function add_alarm()
    {
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


        $this->db->trans_complete();

        $config = array(
            'upload_path' => "./images/alarm_images",
            'allowed_types' => "gif|jpg|png|jpeg|pdf",
            'overwrite' => TRUE
        );

        $this->load->library('upload', $config);

        $cpt = count($_FILES['files']['name']);
        $cpt1 = count($_FILES['files1']['name']);

        for ($back = 0; $back < $cpt; $back++) {
            //$_FILES["file"]["name"] = "alarm_back"."_". date("YmdHis")."_".$latitude."_".$longitude;
            $_FILES["file"]["name"] = $jobId . "_alarm_expiry" . $alarm_key . "_" . date("YmdHis") . "_" . $_FILES["files"]["name"][$back];
            $_FILES["file"]["type"] = $_FILES["files"]["type"][$back];
            $_FILES["file"]["tmp_name"] = $_FILES["files"]["tmp_name"][$back];
            $_FILES["file"]["error"] = $_FILES["files"]["error"][$back];
            $_FILES["file"]["size"] = $_FILES["files"]["size"][$back];

            if ($this->upload->do_upload('file')) {
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

        for ($situ = 0; $situ < $cpt1; $situ++) {
            $_FILES["file"]["name"] = $jobId . "_alarm_location" . $alarm_key . "_" . date("YmdHis") . "_" . $_FILES["files1"]["name"][$situ];
            $_FILES["file"]["type"] = $_FILES["files1"]["type"][$situ];
            $_FILES["file"]["tmp_name"] = $_FILES["files1"]["tmp_name"][$situ];
            $_FILES["file"]["error"] = $_FILES["files1"]["error"][$situ];
            $_FILES["file"]["size"] = $_FILES["files1"]["size"][$situ];

            if ($this->upload->do_upload('file')) {
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

        $newAlarm = $this->db->select()
            ->from("alarm")
            ->where("alarm_id", $newAlarmId)
            ->limit(1)
            ->get()->row_array();


        $query_param = array(
            "select_column" => "alarm.`alarm_id`,
            alarm.`alarm_power_id`,
            alarm.`alarm_type_id`,
            alarm.`alarm_reason_id`,
            alarm.`expiry`,
            alarm.`make`,
            alarm.`model`,
            alarm.`new`,
            alarm.`ts_added`,
            alarm.`ts_alarm_sounds_other`,
            alarm.`ts_cleaned`,
            alarm.`ts_db_rating`,
            alarm.`ts_discarded`,
            alarm.`ts_discarded_reason`,
            alarm.`ts_expiry`,
            alarm.`ts_fixing`,
            alarm.`ts_meetsas1851`,
            alarm.`ts_newbattery`,
            alarm.`ts_position`,
            alarm.`rec_batt_exp`,
            alarm.`ts_required_compliance`,
            alarm.`ts_testbutton`,
            alarm.`ts_visualind`,
            al_pwr.`alarm_pwr_id`,
            al_pwr.`alarm_pwr`,
            al_pwr.`alarm_make`,
            al_pwr.`alarm_model`,
            al_type.`alarm_type_id`,
            al_type.`alarm_type`,
            al_img.`location_image_filename`,
            al_img.`expiry_image_filename`",

            "custom_joins" => array(
                array(
                    "join_table"    => "alarm_pwr AS al_pwr",
                    "join_on"       => "alarm.alarm_power_id = al_pwr.alarm_pwr_id",
                    "join_type"       => "left"
                ),
                array(
                    "join_table"    => "alarm_type AS al_type",
                    "join_on"       => "alarm.alarm_type_id = al_type.alarm_type_id",
                    "join_type"       => "left"
                ),
                array(
                    "join_table"    => "alarm_images AS al_img",
                    "join_on"       => "alarm.alarm_id = al_img.alarm_id",
                    "join_type"       => "left"
                ),
            ),

            "conditions" => array(
                array(
                    "type"      => "where_in",
                    "column"    => "alarm.alarm_id",
                    "value"     => $newAlarmId
                )
            )
        );

        $alarm_data = $this->alarms->get($query_param);

        if ($success) {
            $this->api->setStatusCode(201);
            $this->api->setSuccess(true);
            $this->api->setMessage('Alarm Added.');
            $this->api->putData('alarm', $alarm_data); // add new alarm to response
        } else {
            $this->api->setSuccess(false);
            $this->api->setMessage('Alarm could not be added.');
        }
    }

    /*
    * Bhagvan: not using this method anymore in api v2
    */
    public function update_alarm($alarmId)
    {
        $jobId = $this->input->post("job_id", true);
        $alarm_data = json_decode($this->input->post("alarm", true), true);

        $recBattExp = $alarm_data["rec_batt_exp"];

        // convert rec_batt_exp to YYYY-MM-DD
        if (isset($recBattExp) && preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $recBattExp) != 1) {
            $parts = explode("/", $recBattExp);
            $alarm_data["rec_batt_exp"] = "20{$parts[1]}-{$parts[0]}-01";
        }

        $success = $this->db->set($alarm_data)
            ->where("alarm_id", $alarmId)
            ->update("alarm");
        

        $tmp_tech = $this->tech_model->getTechID($jobId);
        $lastLocation = $this->tech_locations_model->loadLastLocation($tmp_tech['assigned_tech']);

        $config = array(
            'upload_path' => "./images/alarm_images",
            'allowed_types' => "gif|jpg|png|jpeg|pdf",
            'overwrite' => TRUE
        );

        $this->load->library('upload', $config);

        $cpt = count($_FILES['files']['name']);
        $cpt1 = count($_FILES['files1']['name']);
        if($cpt > 0 || $cpt1 > 0) {
            $existing_alram_image = $this->db->select('*')->from('alarm_images')->where("alarm_id", $alarmId)->get()->row_array();
        }
        for ($back = 0; $back < $cpt; $back++) {
            $_FILES["file"]["name"] = $jobId . "_alarm_expiry_" . date("YmdHis") . "_" . $_FILES["files"]["name"][$back];
            $_FILES["file"]["type"] = $_FILES["files"]["type"][$back];
            $_FILES["file"]["tmp_name"] = $_FILES["files"]["tmp_name"][$back];
            $_FILES["file"]["error"] = $_FILES["files"]["error"][$back];
            $_FILES["file"]["size"] = $_FILES["files"]["size"][$back];

            if ($this->upload->do_upload('file')) {
                
                $alarm_img["image_lat"] = $lastLocation['latitude'];
                $alarm_img["image_lng"] = $lastLocation['longitude'];
                $alarm_img["expiry_image_filename"] = $_FILES["file"]["name"];
                if(!empty($existing_alram_image)) {
                    $success = $this->db->set($alarm_img)->where("alarm_id", $alarmId)->update("alarm_images");
                } else {
                    // save new image filename in table 
                    $alarm_img["alarm_id"] = $alarmId;
                    $alarm_img["created"] = date("YmdHis");
                    $alarm_img["active"] = 1;
                    
                    $success = $this->db->set($alarm_img)->insert("alarm_images");
                }
                
            }
        }

        for ($situ = 0; $situ < $cpt1; $situ++) {
            $_FILES["file"]["name"] = $jobId . "_alarm_location_" . date("YmdHis") . "_" . $_FILES["files1"]["name"][$situ];
            $_FILES["file"]["type"] = $_FILES["files1"]["type"][$situ];
            $_FILES["file"]["tmp_name"] = $_FILES["files1"]["tmp_name"][$situ];
            $_FILES["file"]["error"] = $_FILES["files1"]["error"][$situ];
            $_FILES["file"]["size"] = $_FILES["files1"]["size"][$situ];

            if ($this->upload->do_upload('file')) {
                $alarm_img["location_image_filename"] = $_FILES["file"]["name"];
                $success = $this->db->set($alarm_img)->where("alarm_id", $alarmId)->update("alarm_images");
            }
        }
        
        $query_param = array(
            "select_column" => "alarm.`alarm_id`,
            alarm.`alarm_power_id`,
            alarm.`alarm_type_id`,
            alarm.`alarm_reason_id`,
            alarm.`expiry`,
            alarm.`make`,
            alarm.`model`,
            alarm.`new`,
            alarm.`ts_added`,
            alarm.`ts_alarm_sounds_other`,
            alarm.`ts_cleaned`,
            alarm.`ts_db_rating`,
            alarm.`ts_discarded`,
            alarm.`ts_discarded_reason`,
            alarm.`ts_expiry`,
            alarm.`ts_fixing`,
            alarm.`ts_meetsas1851`,
            alarm.`ts_newbattery`,
            alarm.`ts_position`,
            alarm.`rec_batt_exp`,
            alarm.`ts_required_compliance`,
            alarm.`ts_testbutton`,
            alarm.`ts_visualind`,
            al_pwr.`alarm_pwr_id`,
            al_pwr.`alarm_pwr`,
            al_pwr.`alarm_make`,
            al_pwr.`alarm_model`,
            al_type.`alarm_type_id`,
            al_type.`alarm_type`,
            al_img.`location_image_filename`,
            al_img.`expiry_image_filename`",

            "custom_joins" => array(
                array(
                    "join_table"    => "alarm_pwr AS al_pwr",
                    "join_on"       => "alarm.alarm_power_id = al_pwr.alarm_pwr_id",
                    "join_type"     => "left"
                ),
                array(
                    "join_table"    => "alarm_type AS al_type",
                    "join_on"       => "alarm.alarm_type_id = al_type.alarm_type_id",
                    "join_type"     => "left"
                ),
                array(
                    "join_table"    => "alarm_images AS al_img",
                    "join_on"       => "alarm.alarm_id = al_img.alarm_id",
                    "join_type"     => "left"
                ),
            ),

            "conditions" => array(
                array(
                    "type"      => "where_in",
                    "column"    => "alarm.alarm_id",
                    "value"     => $alarmId
                )
            )
        );

        $alarm_data = $this->alarms->get($query_param);

        if ($success) {
            $this->api->setStatusCode(200);
            $this->api->setSuccess(true);
            $this->api->setMessage('Alarm updated.');
            $this->api->putData('alarm', $alarm_data); // add new alarm to response
        } else {
            $this->api->setSuccess(false);
            $this->api->setMessage('Alarm could not be updated.');
        }
    }

    /*
    * Bhagvan: not using this method anymore in api v2
    */
    public function update_alarm_image()
    {
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

        $this->load->library('upload', $config);

        $cpt = count($_FILES['files']['name']);
        $cpt1 = count($_FILES['files1']['name']);

        for ($back = 0; $back < $cpt; $back++) {
            //$_FILES["file"]["name"] = "alarm_back"."_". date("YmdHis")."_".$latitude."_".$longitude;
            $_FILES["file"]["name"] = $jobId . "_alarm_expiry_" . date("YmdHis") . "_" . $_FILES["files"]["name"][$back];
            $_FILES["file"]["type"] = $_FILES["files"]["type"][$back];
            $_FILES["file"]["tmp_name"] = $_FILES["files"]["tmp_name"][$back];
            $_FILES["file"]["error"] = $_FILES["files"]["error"][$back];
            $_FILES["file"]["size"] = $_FILES["files"]["size"][$back];

            if ($this->upload->do_upload('file')) {
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

        for ($situ = 0; $situ < $cpt1; $situ++) {
            $_FILES["file"]["name"] = $jobId . "_alarm_location_" . date("YmdHis") . "_" . $_FILES["files1"]["name"][$situ];
            $_FILES["file"]["type"] = $_FILES["files1"]["type"][$situ];
            $_FILES["file"]["tmp_name"] = $_FILES["files1"]["tmp_name"][$situ];
            $_FILES["file"]["error"] = $_FILES["files1"]["error"][$situ];
            $_FILES["file"]["size"] = $_FILES["files1"]["size"][$situ];

            if ($this->upload->do_upload('file')) {
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

        if ($success) {
            $this->api->setStatusCode(200);
            $this->api->setSuccess(true);
            $this->api->setMessage('Alarm updated.');
        } else {
            $this->api->setSuccess(false);
            $this->api->setMessage('Alarm could not be updated.');
        }
    }

    /*
    * Bhagvan: not using this method anymore in api v2
    */
    private function get_alarm($id) {
        $query_param = array(
            "select_column" => "alarm.`alarm_id`,
            alarm.`alarm_power_id`,
            alarm.`alarm_type_id`,
            alarm.`alarm_reason_id`,
            alarm.`expiry`,
            alarm.`make`,
            alarm.`model`,
            alarm.`new`,
            alarm.`ts_added`,
            alarm.`ts_alarm_sounds_other`,
            alarm.`ts_cleaned`,
            alarm.`ts_db_rating`,
            alarm.`ts_discarded`,
            alarm.`ts_discarded_reason`,
            alarm.`ts_expiry`,
            alarm.`ts_fixing`,
            alarm.`ts_meetsas1851`,
            alarm.`ts_newbattery`,
            alarm.`ts_position`,
            alarm.`rec_batt_exp`,
            alarm.`ts_required_compliance`,
            alarm.`ts_testbutton`,
            alarm.`ts_visualind`,
            al_pwr.`alarm_pwr_id`,
            al_pwr.`alarm_pwr`,
            al_pwr.`alarm_make`,
            al_pwr.`alarm_model`,
            al_type.`alarm_type_id`,
            al_type.`alarm_type`,
            al_img.`location_image_filename`,
            al_img.`expiry_image_filename`",

            "custom_joins" => array(
                array(
                    "join_table"    => "alarm_pwr AS al_pwr",
                    "join_on"       => "alarm.alarm_power_id = al_pwr.alarm_pwr_id",
                    "join_type"     => "left"
                ),
                array(
                    "join_table"    => "alarm_type AS al_type",
                    "join_on"       => "alarm.alarm_type_id = al_type.alarm_type_id",
                    "join_type"     => "left"
                ),
                array(
                    "join_table"    => "alarm_images AS al_img",
                    "join_on"       => "alarm.alarm_id = al_img.alarm_id",
                    "join_type"     => "left"
                ),
            ),

            "conditions" => array(
                array(
                    "type"      => "where_in",
                    "column"    => "alarm.alarm_id",
                    "value"     => $id
                )
            )
        );

        return $this->alarms->get($query_param);
    }

    /*
    * Bhagvan: not using this method anymore in api v2
    */
    public function add_alarm_v2 ()
    {
        $this->api->assertMethod('post');
        $this->load->helper(array('form', 'url'));
        $post_data = $this->api->getPostData();
        if (
            empty($post_data) ||
            !isset($post_data["alarm"]) ||
            empty($post_data["alarm"])
        ) {
            $this->api->setSuccess(false);
            return $this->api->setMessage('Alarm could not be added.');
        }

        $lastLocation = $this->tech_locations_model->loadLastLocation($post_data["tech_id"]);

        $alarm = $post_data['alarm'];
        $alarm["job_id"] = $post_data["job_id"];
        $alarm["alarm_job_type_id"] = 2;

        
        // convert rec_batt_exp to YYYY-MM-DD
        if (!empty($alarm["rec_batt_exp"]) && preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $alarm["rec_batt_exp"]) != 1) {
            $parts = explode("/", $alarm["rec_batt_exp"]);
            $alarm["rec_batt_exp"] = "20{$parts[1]}-{$parts[0]}-01";
        }

        $this->db->trans_start();

        // save new alarm
        $success = $this->db->set($alarm)->insert("alarm");
        // get new alarm from database
        $newAlarmId = $this->db->insert_id();

        $this->db->trans_complete();

        $allowed_types = ["gif", "jpg", "jpeg", "png", "pdf"];

        if (
            isset($post_data["image_expiry"]) && 
            !empty($post_data["image_expiry"]) && 
            in_array(strtolower(pathinfo($post_data["image_expiry"]["name"], PATHINFO_EXTENSION)), $allowed_types)
        ) {
            $file_name = $post_data["job_id"] . "_alarm_expiry" . $post_data["alarm_key"] . "_" . date("YmdHis") . "_" . $post_data["image_expiry"]["name"];
            file_put_contents("./images/alarm_images/" . $file_name, base64_decode($post_data["image_expiry"]["data"]));

            $alarm_img = array(
                "alarm_id"                  => $newAlarmId,
                "expiry_image_filename"     => $file_name,
                "image_lat"                 => $lastLocation['latitude'],
                "image_lng"                 => $lastLocation['longitude'],
                "created"                   => date("YmdHis"),
                "active"                    => 1
            );
            // save new image filename in table 
            $this->db->set($alarm_img)->insert("alarm_images");
        }

        if (
            isset($post_data["image_location"]) && 
            !empty($post_data["image_location"]) && 
            in_array(strtolower(pathinfo($post_data["image_location"]["name"], PATHINFO_EXTENSION)), $allowed_types)
        ) {
            $file_name = $post_data["job_id"] . "_alarm_location" . $post_data["alarm_key"] . "_" . date("YmdHis") . "_" . $post_data["image_location"]["name"];
            file_put_contents("./images/alarm_images/" . $file_name, base64_decode($post_data["image_location"]["data"]));

            $alarm_img = array(
                "location_image_filename"   => $file_name,
            );
            // save new image filename in table 
            $this->db->set($alarm_img)->where("alarm_id", $newAlarmId)->update("alarm_images");
        }

        $alarm_data = $this->get_alarm($newAlarmId);

        if ($success) {
            $this->api->setStatusCode(201);
            $this->api->setSuccess(true);
            $this->api->setMessage('Alarm Added.');
            $this->api->putData('alarm', $alarm_data); // add new alarm to response
        } else {
            $this->api->setSuccess(false);
            $this->api->setMessage('Alarm could not be added.');
        }
    }

    /*
    * Bhagvan: not using this method anymore in api v2
    */
    public function update_alarm_v2 ($alarmId)
    {
        $this->api->assertMethod('post');
        $this->load->helper(array('form', 'url'));
        $post_data = $this->api->getPostData();

        if (
            empty($post_data) ||
            !isset($post_data["alarm"]) ||
            empty($post_data["alarm"])
        ) {
            $this->api->setSuccess(false);
            return $this->api->setMessage('Alarm could not be updated.');
        }
        $alarm = $post_data['alarm'];
        
        // convert rec_batt_exp to YYYY-MM-DD
        if (!empty($alarm["rec_batt_exp"]) && preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $alarm["rec_batt_exp"]) != 1) {
            $parts = explode("/", $alarm["rec_batt_exp"]);
            $alarm["rec_batt_exp"] = "20{$parts[1]}-{$parts[0]}-01";
        }
        
        $success = $this->db->set($alarm)->where("alarm_id", $alarmId)->update("alarm");


        $tmp_tech = $this->tech_model->getTechID($post_data["job_id"]);
        $lastLocation = $this->tech_locations_model->loadLastLocation($tmp_tech['assigned_tech']);

        $allowed_types = ["gif", "jpg", "jpeg", "png", "pdf"];
        $existing_alram_image = $this->db->select('*')->from('alarm_images')->where("alarm_id", $alarmId)->get()->row_array();

        if (
            isset($post_data["image_expiry"]) && 
            !empty($post_data["image_expiry"]) && 
            in_array(strtolower(pathinfo($post_data["image_expiry"]["name"], PATHINFO_EXTENSION)), $allowed_types)
        ) {
            $file_name = $post_data["job_id"] . "_alarm_expiry" . $post_data["alarm_key"] . "_" . date("YmdHis") . "_" . $post_data["image_expiry"]["name"];
            file_put_contents("./images/alarm_images/" . $file_name, base64_decode($post_data["image_expiry"]["data"]));
            $alarm_img = array(
                "expiry_image_filename"     => $file_name,
                "image_lat"                 => $lastLocation['latitude'],
                "image_lng"                 => $lastLocation['longitude'],
            );

            if (!empty($existing_alram_image)) {
                $success = $this->db->set($alarm_img)->where("alarm_id", $alarmId)->update("alarm_images");
            } else {
                // save new image filename in table 
                $alarm_img["alarm_id"] = $alarmId;
                $alarm_img["created"] = date("YmdHis");
                $alarm_img["active"] = 1;

                $success = $this->db->set($alarm_img)->insert("alarm_images");
            }
            
        }

        if (
            isset($post_data["image_location"]) && 
            !empty($post_data["image_location"]) && 
            in_array(strtolower(pathinfo($post_data["image_location"]["name"], PATHINFO_EXTENSION)), $allowed_types)
        ) {
            $file_name = $post_data["job_id"] . "_alarm_location" . $post_data["alarm_key"] . "_" . date("YmdHis") . "_" . $post_data["image_location"]["name"];
            file_put_contents("./images/alarm_images/" . $file_name, base64_decode($post_data["image_location"]["data"]));

            $alarm_img = array(
                "location_image_filename"   => $file_name,
            );
            // save new image filename in table 
            $this->db->set($alarm_img)->where("alarm_id", $alarmId)->update("alarm_images");
        }

        $alarm_data = $this->get_alarm($alarmId);

        if ($success) {
            $this->api->setStatusCode(200);
            $this->api->setSuccess(true);
            $this->api->setMessage('Alarm updated.');
            $this->api->putData('alarm', $alarm_data); // add new alarm to response
        } else {
            $this->api->setSuccess(false);
            $this->api->setMessage('Alarm could not be updated.');
        }
    }

    /*
    * Bhagvan: not using this method anymore in api v2
    */
    public function delete_alarm()
    {
        $this->api->assertMethod("delete");

        $jobId = $this->input->get("job_id");
        $alarmId = $this->input->get("alarm_id");

        $this->db->trans_start();
        $success = $this->db
            ->where("job_id", $jobId)
            ->where("alarm_id", $alarmId)
            ->limit(1)
            ->delete("alarm");

        $this->db->trans_complete();
        if ($success) {
            $this->api->setStatusCode(200);
            $this->api->setSuccess(true);
            $this->api->setMessage('Alarm deleted.');
        } else {
            $this->api->setSuccess(false);
            $this->api->setMessage('Alarm could not be deleted.');
        }
    }
}
