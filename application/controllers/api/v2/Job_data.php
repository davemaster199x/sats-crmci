<?php

class Job_data extends MY_ApiController
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('tech_model');
        $this->load->model('tech_run_model');
        $this->load->model('vehicles_model');
        $this->load->model('kms_model');
        $this->load->model('tech_locations_model');
        $this->load->model('alarms_model', 'alarms');
        $this->load->model('Water_efficiency_model', 'water_efficiency');
        $this->load->model('Corded_window_model', 'corded_window');
        $this->load->model('Safety_switch_model', 'safety_switch');
        $this->load->model('Jobs_model');
        $this->load->model('job_sync_log_model');
        $this->load->model('water_meter_model');
        $this->load->model('Certifications_model');
        $this->load->model('Certification_types_model');

        $this->tech_location = '';
        $this->job_image_data = array(
            "alarms" => [],
            "switchboard_image" => []
        );
    }

    private function check_for_existing_image($alarm)
    {

        if (
            isset($alarm["image_expiry"]) &&
            !empty($alarm["image_expiry"])
        ) {
            // storing image data to class variable to return 
            $this->job_image_data["alarms"][$alarm["alarm_id"]]["image_expiry"] = $alarm["image_expiry"];
        }

        if (
            isset($alarm["image_location"]) &&
            !empty($alarm["image_location"])
        ) {
            // storing image data to class variable to return 
            $this->job_image_data["alarms"][$alarm["alarm_id"]]["image_location"] = $alarm["image_location"];
        }
    }

    private function save_alarms_data($alarm)
    {
        // convert rec_batt_exp to YYYY-MM-DD
        if (!empty($alarm["rec_batt_exp"]) && preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $alarm["rec_batt_exp"]) != 1) {
            $parts = explode("/", $alarm["rec_batt_exp"]);
            $alarm["rec_batt_exp"] = "20{$parts[1]}-{$parts[0]}-01";
        }

        $alarm_id = "";

        if (isset($alarm["is_new"]) && $alarm["is_new"] == 1) {
            $alarm_id = $this->alarms->set_data($alarm);
        } elseif (isset($alarm["is_updated"]) && $alarm["is_updated"] == 1) {
            $alarm_id = $alarm["alarm_id"];
            $this->alarms->set_data($alarm, $alarm_id);
        } elseif (isset($alarm["is_deleted"]) && $alarm["is_deleted"] == 1) {
            $alarm_id = $alarm["alarm_id"];
            $alarm["ts_discarded"] = 1;
            $this->alarms->set_data($alarm, $alarm_id);
        } elseif (isset($alarm["alarm_id"]) && !empty($alarm["alarm_id"])) {
            $alarm_id = $alarm["alarm_id"];
            $this->alarms->set_data($alarm, $alarm_id);
        }

        if (empty($alarm_id)) {
            return false;
        }

        $allowed_types = ["gif", "jpg", "jpeg", "png", "pdf"];
        if (
            isset($alarm["image_expiry"]) &&
            !empty($alarm["image_expiry"]) &&
            in_array(strtolower(pathinfo($alarm["image_expiry"]["name"], PATHINFO_EXTENSION)), $allowed_types)
        ) {
            // storing image data to class variable to return 
            $this->job_image_data["alarms"][$alarm_id]["image_expiry"] = $alarm["image_expiry"];

            $file_name = $alarm["job_id"] . "_alarm_expiry" . $alarm["alarm_key"] . "_" . date("YmdHis") . "_" . strtolower($alarm["image_expiry"]["name"]);
            file_put_contents("./images/alarm_images/" . $file_name, base64_decode($alarm["image_expiry"]["data"]));

            $alarm_img = array(
                "alarm_id"                  => $alarm_id,
                "expiry_image_filename"     => $file_name,
                "image_lat"                 => $this->tech_location['lat'],
                "image_lng"                 => $this->tech_location['lng'],
                "created"                   => date("YmdHis"),
                "active"                    => 1
            );

            $check_result = $this->db->select("alarm_id")->from("alarm_images")->where_in("alarm_id", $alarm_id)->get();
            if ($check_result->num_rows() > 0) {
                #update alaram Data
                $this->db->set($alarm_img)->where("alarm_id", $alarm_id)->update("alarm_images");
            } else {
                // save new image filename in table 
                $this->db->set($alarm_img)->insert("alarm_images");
            }
        }

        if (
            isset($alarm["image_location"]) &&
            !empty($alarm["image_location"]) &&
            in_array(strtolower(pathinfo($alarm["image_location"]["name"], PATHINFO_EXTENSION)), $allowed_types)
        ) {
            // storing image data to class variable to return 
            $this->job_image_data["alarms"][$alarm_id]["image_location"] = $alarm["image_location"];

            $file_name = $alarm["job_id"] . "_alarm_location" . $alarm["alarm_key"] . "_" . date("YmdHis") . "_" . strtolower($alarm["image_location"]["name"]);
            file_put_contents("./images/alarm_images/" . $file_name, base64_decode($alarm["image_location"]["data"]));

            $alarm_img = array(
                "location_image_filename"   => $file_name,
            );
            // save new image filename in table 
            $this->db->set($alarm_img)->where("alarm_id", $alarm_id)->update("alarm_images");
        }

        return $alarm_id;
    }


    private function save_water_efficiency_data($water_efficiency)
    {
        $_id = false;
        if (isset($water_efficiency["is_deleted"]) && $water_efficiency["is_deleted"] == 1) {
            if (isset($water_efficiency["water_efficiency_id"]) && !empty($water_efficiency["water_efficiency_id"])) {

                $condition = array(
                    "conditions" => array(
                        array(
                            "type"      => "where",
                            "column"    => "water_efficiency_id",
                            "value"     => $water_efficiency["water_efficiency_id"]
                        )
                    )
                );
                $this->water_efficiency->delete($condition);
            }
            return $_id;
        } elseif (isset($water_efficiency["is_new"]) && $water_efficiency["is_new"] == 1) {
            $_id = 0;
        } elseif (isset($water_efficiency["is_updated"]) && $water_efficiency["is_updated"] == 1) {
            $_id = $water_efficiency["water_efficiency_id"];
        } elseif (isset($water_efficiency["water_efficiency_id"]) && !empty($water_efficiency["water_efficiency_id"])) {
            $_id = $water_efficiency["water_efficiency_id"];
        }

        if ($_id !== false) {
            $_id = $this->water_efficiency->set_data($water_efficiency, $_id);
        }


        return $_id;
    }


    private function save_corded_windows_data($corded_window)
    {
        $_id = false;
        if (isset($corded_window["is_deleted"]) && $corded_window["is_deleted"] == 1) {
            if (isset($corded_window["corded_window_id"]) && !empty($corded_window["corded_window_id"])) {

                $condition = array(
                    "conditions" => array(
                        array(
                            "type"      => "where",
                            "column"    => "corded_window_id",
                            "value"     => $corded_window["corded_window_id"]
                        )
                    )
                );
                $this->corded_window->delete($condition);
            }
            return $_id;
        } elseif (isset($corded_window["is_new"]) && $corded_window["is_new"] == 1) {
            $_id = 0;
        } elseif (isset($corded_window["is_updated"]) && $corded_window["is_updated"] == 1) {
            $_id = $corded_window["corded_window_id"];
        } elseif (isset($corded_window["corded_window_id"]) && !empty($corded_window["corded_window_id"])) {
            $_id = $corded_window["corded_window_id"];
        }

        if ($_id !== false) {
            $_id = $this->corded_window->set_data($corded_window, $_id);
        }

        return $_id;
    }


    private function save_safety_switches_data($safety_switch)
    {
        $_id = false;
        if (isset($safety_switch["is_deleted"]) && $safety_switch["is_deleted"] == 1) {
            if (isset($safety_switch["safety_switch_id"]) && !empty($safety_switch["safety_switch_id"])) {

                $condition = array(
                    "conditions" => array(
                        array(
                            "type"      => "where",
                            "column"    => "safety_switch_id",
                            "value"     => $safety_switch["safety_switch_id"]
                        )
                    )
                );
                $this->safety_switch->delete($condition);
            }
            return $_id;
        } elseif (isset($safety_switch["is_new"]) && $safety_switch["is_new"] == 1) {
            $_id = 0;
        } elseif (isset($safety_switch["is_updated"]) && $safety_switch["is_updated"] == 1) {
            $_id = $safety_switch["safety_switch_id"];
        } elseif (isset($safety_switch["safety_switch_id"]) && !empty($safety_switch["safety_switch_id"])) {
            $_id = $safety_switch["safety_switch_id"];
        }

        if ($_id !== false) {
            $_id = $this->safety_switch->set_data($safety_switch, $_id);
        }

        return $_id;
    }

    public function save_tech_sheet()
    {
        $this->api->assertMethod('post');
        $this->load->helper(array('form', 'url'));

        $job_update_data = array();

        $postData = $this->api->getPostData();
        $this->form_validation->set_data($postData);

        // validation rules
        $this->form_validation->set_rules([
            [
                'field' => 'job_id',
                'rules' => 'required|integer',
            ]
        ]);

        $staff_id = $this->api->getJWTItem("staff_id");

        $today = date("Y-m-d H:i:s");
        if (
            isset($postData["submitTime"]) &&
            !empty($postData["submitTime"])
        ) {
            $today = $postData["submitTime"];
        }

        ###############################################################################
        // Start: json file store in folder

        if ( empty($postData["unique_key"]) ) {
            $this->api->setMessage("Please update the application");
            return $this->api->setSuccess(false);
        }

        $jsonFileName = $staff_id."-".$postData["job"]["jid"]."-".$postData["unique_key"].".json";
        
        $checkFolder = FCPATH.'uploads/job_log';
        if ( !is_dir($checkFolder) ) {
            mkdir($checkFolder, 0777);
        }

        if ( file_exists($checkFolder."/".$jsonFileName) ) {
            $this->api->putData('tech_sheet', $this->tech_run_model->get_job_details([$postData["job"]["jid"]]));
            return $this->api->setSuccess(true);
        } else {
            file_put_contents($checkFolder."/".$jsonFileName, json_encode($postData, true));

            $sync_data_array = array(
                "unique_key"    => $postData["unique_key"],
                "job_id"        => $postData["job"]["jid"],
                "staff_id"      => $staff_id,
                "payload"       => $jsonFileName,
                "status"        => 1
            );

            // Get all syncs for today
            $todays_syncs_where = [
                ['job_id', $sync_data_array["job_id"]],
                ['unique_key', $sync_data_array["unique_key"]],
                ['staff_id', $sync_data_array["staff_id"]]
            ];

            // Check for for entire payload if exist in database.
            $job_sync_logs = $this->job_sync_log_model
                ->as_array()
                ->where($todays_syncs_where)
                ->order_by('id', 'DESC')
                ->get();


            if (empty($job_sync_logs)) {
                // if unique and data changed then add it to database
                $this->job_sync_log_model->insert($sync_data_array);
            } else {
                // if exist then its duplicate, return back the techsheet data
                $this->api->putData('tech_sheet', $this->tech_run_model->get_job_details([$postData["job"]["jid"]]));
                return $this->api->setSuccess(true);
            }
        }
        ###############################################################################

        $job_check = $this->db->select("id, status")->from("jobs")->where("id", $postData["job"]["jid"])->get()->row();

        if (in_array($job_check->status, ["Merged Certificates", "Completed"])) {
            $this->api->putData('tech_sheet', $this->tech_run_model->get_job_details([$postData["job"]["jid"]]));
            return $this->api->setSuccess(true);
        }

        if ( isset($postData["latitude"]) && !empty($postData["latitude"]) && isset($postData["longitude"]) && !empty($postData["longitude"]) ){
            $this->db->insert("tech_locations", [
                'tech_id' => $staff_id,
                'lat' => $postData["latitude"],
                'lng' => $postData["longitude"],
                'created' => date("Y-m-d H:i:s"),
            ]);
        }
        $this->tech_location = $this->tech_locations_model->loadLastLocation($staff_id);

        $job_update_data = $postData["job"];

        if (
            isset($job_update_data["ss_image"]) &&
            !empty($job_update_data["ss_image"])
        ) {
            $job_update_data["ss_image"] = basename($job_update_data["ss_image"]);
        }

        #####################
        // Save alarm Data Start
		//log_message('debug', 'AJT ID: ' . print_r($postData["job"], true));
        if (isset($postData["new_alarms"]) && !empty($postData["new_alarms"])) {
            foreach ($postData["new_alarms"] as $alarm) {

                // check for alaram image
                if (isset($alarm["alarm_id"]) && !empty($alarm["alarm_id"])) {
                    $this->check_for_existing_image($alarm);
                }

                $alarm["job_id"] = $postData["job"]["jid"];
				// We dont want to save the alarm as the job type id
	            // we want to save the alarm with the job type id of the smoke alarm within the bundle
                $alarm["alarm_job_type_id"] = Alarm_job_type_model::get_bundled_smoke_alarm_service_id($postData["job"]["j_service"]);
                $this->save_alarms_data($alarm);
            }
        }

        if (isset($postData["existing_alarms"]) && !empty($postData["existing_alarms"])) {
            foreach ($postData["existing_alarms"] as $alarm) {

                // check for alaram image
                if (isset($alarm["alarm_id"]) && !empty($alarm["alarm_id"])) {
                    $this->check_for_existing_image($alarm);
                }

                $alarm["job_id"] = $postData["job"]["jid"];
                $alarm["alarm_job_type_id"] = Alarm_job_type_model::get_bundled_smoke_alarm_service_id($postData["job"]["j_service"]);
                $this->save_alarms_data($alarm);
            }
        }

        // Save alarm Data End
        #####################


        #####################
        // Save water efficiency Data Start

        if (isset($postData["water_efficiency_details"]) && !empty($postData["water_efficiency_details"])) {
            foreach ($postData["water_efficiency_details"] as $water_efficiency) {
                $result = $this->save_water_efficiency_data($water_efficiency);
            }
        }

        // Save water efficiency Data End
        #####################

        #####################
        // Save corded window Data Start

        if (isset($postData["corded_windows"]) && !empty($postData["corded_windows"])) {
            foreach ($postData["corded_windows"] as $corded_window) {
                $result = $this->save_corded_windows_data($corded_window);
            }
        }

        // Save corded window Data End
        #####################

        #####################
        // Save safety switches Data Start

//        log_message('debug', '====================');
//        log_message('debug', print_r($postData['safety_switches'], true));
//        log_message('debug', print_r($postData['switchboard_image'], true));
        if (isset($postData["safety_switches"]) && !empty($postData["safety_switches"])) {
            foreach ($postData["safety_switches"] as $safety_switch) {
                $result = $this->save_safety_switches_data($safety_switch);
            }
        }

        if (
            isset($postData["switchboard_image"]) &&
            !empty($postData["switchboard_image"]) &&
            isset($postData["switchboard_image"]["data"]) &&
            !empty($postData["switchboard_image"]["data"])
        ) {

            // storing image data to class variable to return 
            $this->job_image_data["switchboard_image"] = $postData["switchboard_image"];

            $image = base64_decode($postData["switchboard_image"]["data"]);

            $ext = strtolower(pathinfo($postData["switchboard_image"]["name"], PATHINFO_EXTENSION));
            $imageName = "switchboard{$postData["job"]["jid"]}" . rand() . date("YmdHis") . ".{$ext}";
//            log_message('debug', $imageName);
//            log_message('debug', "{$_SERVER['DOCUMENT_ROOT']}/uploads/switchboard_image/{$imageName}");
//            log_message('debug', $image);
            file_put_contents("{$_SERVER['DOCUMENT_ROOT']}/uploads/switchboard_image/{$imageName}", $image);
            $job_update_data["ss_image"] = $imageName;
        }
        //log_message('debug', '====================');
        // Save safety switches Data End
        #####################

        if(
            !empty($postData["water_meter"]["location"])
        ) {

            $upload_dir_water_meters = FCPATH . Water_meter_model::upload_path();
            if (!file_exists($upload_dir_water_meters)) {
                mkdir($upload_dir_water_meters, 0755, true);
            }

            $water_meter_data = array(
                "job_id"                => $postData["job"]["jid"],
                "location"              => $postData["water_meter"]["location"] ?? "",
                "reading"               => $postData["water_meter"]["reading"] ?? "",
                "meter_image"           => $postData["water_meter"]["meter_image"] ?? "",
                "meter_reading_image"   => $postData["water_meter"]["meter_reading_image"] ?? "",
                "active"                => 1
            );

            if (
                !empty($postData["water_meter"]["water_meter_image"]) &&
                !empty($postData["water_meter"]["water_meter_image"]["data"])
            ) {

                // storing image data to class variable to return
                $this->job_image_data["water_meter_image"] = $postData["water_meter"]["water_meter_image"];

                $image = base64_decode($postData["water_meter"]["water_meter_image"]["data"]);

                $ext = strtolower(pathinfo($postData["water_meter"]["water_meter_image"]["name"], PATHINFO_EXTENSION));
                $imageName = "water_meter{$postData["job"]["jid"]}" . rand() . date("YmdHis") . ".{$ext}";
                $water_meter_data["meter_image"] = Water_meter_model::upload_path($imageName);

                file_put_contents($water_meter_data["meter_image"], $image);

            }

            if (
                !empty($postData["water_meter"]["water_meter_reading_image"]) &&
                !empty($postData["water_meter"]["water_meter_reading_image"]["data"])
            ) {

                // storing image data to class variable to return
                $this->job_image_data["water_meter_reading_image"] = $postData["water_meter"]["water_meter_reading_image"];

                $image = base64_decode($postData["water_meter"]["water_meter_reading_image"]["data"]);

                $ext = strtolower(pathinfo($postData["water_meter"]["water_meter_reading_image"]["name"], PATHINFO_EXTENSION));
                $imageName = "water_meter{$postData["job"]["jid"]}" . rand() . date("YmdHis") . ".{$ext}";
                $water_meter_data["meter_reading_image"] = Water_meter_model::upload_path($imageName);

                file_put_contents($water_meter_data["meter_reading_image"], $image);
            }

            $water_meter = $this->water_meter_model->as_array()->where([['job_id', $postData["job"]["jid"]]])->order_by('water_meter_id', 'DESC')->get();

            if(empty($water_meter)) {
                $water_meter_data["created_date"] = date("Y-m-d H:i:s");
                $this->water_meter_model->insert($water_meter_data);
            } else {
                $this->db->set($water_meter_data)->where("water_meter_id", $water_meter["water_meter_id"])->update("water_meter");
            }
        }

        #####################
        // Save Job Data to complete Start

        $bundleUpdateData = [];

        // get job data to handle saving properly
        $job = $this->db->select("j.id AS jid, j.`status` AS jstatus, j.`service` AS jservice, j.`property_id`, p.`state` AS p_state, ajt.`bundle`")
            ->from("jobs AS j")
            ->join("property AS p", "j.property_id = p.property_id", "left")
            ->join("alarm_job_type AS ajt", "j.service = ajt.id", "left")
            ->where("j.id", $postData["job"]["jid"])
            ->get()->row();

        if ($job->bundle == 1) {

            $bundles = $this->db->select("bundle_services_id, alarm_job_type_id")
                ->from("bundle_services AS bs")
                ->join("alarm_job_type AS ajt", "ajt.id = bs.alarm_job_type_id", "LEFT")
                ->where("job_id", $postData["job"]["jid"])
                ->get()->result();

            // mark which services are confirmed
            foreach ($bundles as $bundle) {
                $bundleUpdateData[] = [
                    "completed" => 1,
                    "job_id" => $postData["job"]["jid"],
                    "bundle_services_id" => $bundle->bundle_services_id,
                ];

                $ts_confirm_marker = null;
                if ($bundle->alarm_job_type_id == 2) { // smoke alarm
                    $job_update_data['ts_techconfirm'] = 1;
                } else if ($bundle->alarm_job_type_id == 5) { // safety switch
                    $job_update_data['ss_techconfirm'] = 1;
                } else if ($bundle->alarm_job_type_id == 6) { // corded window
                    $job_update_data['cw_techconfirm'] = 1;
                } else if ($bundle->alarm_job_type_id == 15) { // water efficiency
                    $job_update_data['we_techconfirm'] = 1;
                }
            }
        } else {

            // mark the service confirmed
            $ts_confirm_marker = null;
            if ($job->jservice == 2) { // smoke alarm
                $job_update_data['ts_techconfirm'] = 1;
            } else if ($job->jservice == 5) { // safety switch
                $job_update_data['ss_techconfirm'] = 1;
            } else if ($job->jservice == 6) { // corded window
                $job_update_data['cw_techconfirm'] = 1;
            } else if ($job->jservice == 15) { // water efficiency
                $job_update_data['we_techconfirm'] = 1;
            }
        }

        $job_update_data["status"] = "Pre Completion";
        $job_update_data["ts_completed"] = "1";
        $job_update_data["completed_timestamp"] = $today;
        $job_update_data["precomp_jobs_moved_to_booked"] = "";
        $job_update_data["prop_comp_with_state_leg"] = $postData["job"]["prop_comp_with_state_leg"];
        $job_update_data["job_reason_id"] = "";
        $job_update_data["job_reason_comment"] = "";
        $job_update_data["no_smoke_alarm_reason"] = $postData["job"]["no_smoke_alarm_reason"];




        $this->Jobs_model->set_data($job_update_data, $postData["job"]["jid"]);

        $this->db->trans_start();
        // $this->db->set($job_update_data)->where("id", $postData["job"]["jid"])->update("jobs");

        $property_update_data = [
            "comments"                  => $postData["job"]["p_comments"],
            "qld_new_leg_alarm_num"     => $postData["job"]["qld_new_leg_alarm_num"],
            "alarm_code"                => $postData["job"]["alarm_code"],
        ];


        if (isset($postData["job"]["service_garage"]) && $postData["job"]["service_garage"] !== "") {
            $property_update_data["service_garage"] = $postData["job"]["service_garage"];

            // insert log
            $log_details = "<b>APP</b>: Property <b>" . (($postData["job"]["service_garage"] == 1) ? 'marked' : 'unmarked') . "</b> as <b>Attached garage requires alarm</b>";
            $log_params = array(
                'title' => 65,  // Property Update   
                'details' => $log_details,
                'display_in_vpd' => 1,
                'created_by_staff' => $staff_id,
                'property_id' => $job->property_id
            );
            $this->system_model->insert_log($log_params);
        }

        if ($job->property_id > 0) {

            $this->db->set(["code" => $postData["job"]["lb_code"]])->where("property_id", $job->property_id)->update("property_lockbox");

            if ($job->p_state == 'QLD') {
                $property_update_data["prop_upgraded_to_ic_sa"] = $postData["job"]["prop_upgraded_to_ic_sa"];
            }

            if (
                (isset($postData["job"]["short_term_rental_compliant"]) && !empty($postData["job"]["short_term_rental_compliant"])) ||
                (isset($postData["job"]["req_num_alarms"]) && !empty($postData["job"]["req_num_alarms"])) ||
                (isset($postData["job"]["req_heat_alarm"]) && !empty($postData["job"]["req_heat_alarm"]))
            ) {
                $nsw_update_data = [];

                if (isset($postData["job"]["short_term_rental_compliant"]) && !empty($postData["job"]["short_term_rental_compliant"])) {
                    $nsw_update_data["short_term_rental_compliant"] = $postData["job"]["short_term_rental_compliant"];
                }

                if (isset($postData["job"]["req_num_alarms"]) && !empty($postData["job"]["req_num_alarms"])) {
                    $nsw_update_data["req_num_alarms"] = $postData["job"]["req_num_alarms"];
                }

                if (isset($postData["job"]["req_heat_alarm"]) && !empty($postData["job"]["req_heat_alarm"])) {
                    $nsw_update_data["req_heat_alarm"] = $postData["job"]["req_heat_alarm"];
                }

                if (!empty($nsw_update_data)) {

                    $nsw_property_compliance = $this->db->select("*")
                        ->from("nsw_property_compliance")
                        ->where("property_id", $job->property_id)
                        ->get();

                    if ($nsw_property_compliance->num_rows() > 0) {
                        $this->db->where("property_id", $job->property_id)->update("nsw_property_compliance", $nsw_update_data);
                    } else {
                        $nsw_update_data["property_id"] = $job->property_id;
                        $this->db->insert("nsw_property_compliance", $nsw_update_data);
                    }
                }
            }
        }

        if (!empty($property_update_data)) {
            $this->db->set($property_update_data)->where("property_id", $job->property_id)->update("property");
        }

        if (!empty($bundleUpdateData)) {
            foreach ($bundleUpdateData as $bud) {
                $this->db->set(["completed" => $bud["completed"]])->where("job_id", $bud["job_id"])->where("bundle_services_id", $bud["bundle_services_id"])->update("bundle_services");
            }
        }



        if (!in_array($job_check->status, ["Pre Completion"])) {

            if (!empty($postData["job"]["no_smoke_alarm_reason"])) {
                $log_details = "<b>APP</b>: No Smoke Alarms were tested reason: <b>" .  $postData["job"]["no_smoke_alarm_reason"] . "</b>";
                $log_params = array(
                    'title' => 75, // Techsheet Completed
                    'details' => $log_details,
                    'display_in_vjd' => 1,
                    'created_by_staff' => $staff_id,
                    'job_id' => $postData["job"]["jid"]
                );
                $this->system_model->insert_log($log_params);
            }

            // insert log
            $log_details = "<b>APP</b>: job changed from <b>{$job->jstatus}</b> to <b>Pre Completion</b>";
            $log_params = array(
                'title' => 75, // Techsheet Completed
                'details' => $log_details,
                'display_in_vjd' => 1,
                'created_by_staff' => $staff_id,
                'job_id' => $postData["job"]["jid"]
            );
            $this->system_model->insert_log($log_params);
        }



        $this->db->trans_complete();

        $success = $this->db->trans_status();

        /* 
        *   Save data for eCoC form
        */
        if (
            isset($postData["certificate_required"]) &&
            !empty($postData["certificate_required"])
        ) {
          
            foreach ( $postData["certificate_required"] as $value ) {
                  
                $certification_types =  $this->Certification_types_model->where(['id'=> $value["certification_type_id"],"active" => 1])->get();
                if( empty($certification_types) ){
                    continue;
                }
                $exist_cert = $this->Certifications_model->where(["job_id" => $postData["job"]["jid"], "certification_id" => $value["certification_type_id"]])->get();
                if ( $value["option"] == "0" ) {
                    if(!empty($exist_cert)){
                        $this->Certifications_model->where(["id" => $exist_cert->id])->update(["status"=>"cancelled"]);
                        
                        // insert log
                        $log_details = "<b>APP</b>: <b>{$certification_types->name}</b> certification from <b>{$exist_cert->status}</b> to <b>cancelled</b>";
                        $log_params = array(
                            'title' => 114, // Job Certification
                            'details' => $log_details,
                            'display_in_vjd' => 1,
                            'created_by_staff' => $staff_id,
                            'job_id' => $postData["job"]["jid"]
                        );
                        $this->system_model->insert_log($log_params);
                    }
                } elseif ( $value["option"] == "1" ) {
                    if ( empty($exist_cert) ) {
                        $this->Certifications_model->insert(["certification_id"=>$value["certification_type_id"], "job_id"=>$postData["job"]["jid"]]);
                        
                        // insert log
                        $log_details = "<b>APP</b>: New certification of <b>{$certification_types->name}</b> is <b>open</b>";
                        $log_params = array(
                            'title' => 114, // Job Certification
                            'details' => $log_details,
                            'display_in_vjd' => 1,
                            'created_by_staff' => $staff_id,
                            'job_id' => $postData["job"]["jid"]
                        );
                        $this->system_model->insert_log($log_params);
                    } elseif ($exist_cert->status == "cancelled"){
                        $this->Certifications_model->where(["id" => $exist_cert->id])->update(["status"=>"open"]);

                        // insert log
                        $log_details = "<b>APP</b>: <b>{$certification_types->name}</b> certification from <b>cancelled</b> to <b>open</b>";
                        $log_params = array(
                            'title' => 114, // Job Certification
                            'details' => $log_details,
                            'display_in_vjd' => 1,
                            'created_by_staff' => $staff_id,
                            'job_id' => $postData["job"]["jid"]
                        );
                        $this->system_model->insert_log($log_params);
                    }
                }
            }
        }

        // Save Job Data to complete End
        #####################
        $this->api->putData('tech_sheet', $this->tech_run_model->get_job_details([$postData["job"]["jid"]], [$postData["job"]["jid"] => $this->job_image_data]));
        $this->api->setSuccess(true);
    }


    public function fix_saved_jobs()
    {

        $this->api->assertMethod('post');
        $this->load->helper(array('form', 'url'));

        $job_update_data = array();

        $postData = $this->api->getPostData();

        if (
            isset($postData["job"]["jid"]) &&
            !empty($postData["job"]["jid"])
        ) {

            // get job data to handle saving properly
            $job = $this->db->select("j.id AS jid, j.property_id, j.entry_gained_via, j.ss_location, j.survey_numlevels, j.ps_number_of_bedrooms, j.survey_numalarms, j.survey_ceiling, j.survey_ladder, j.ts_items_tested, j.ts_batteriesinstalled, j.ts_alarmsinstalled, j.ss_items_tested, j.cw_items_tested, j.we_items_tested, p.qld_new_leg_alarm_num")
                ->from("jobs AS j")
                ->join("property AS p", "j.property_id = p.property_id", "left")
                ->where("j.id", $postData["job"]["jid"])
                ->get()->row();

            $update_data = [];
            if (
                empty($job->entry_gained_via) &&
                !empty($postData["job"]["entry_gained_via"])
            ) {
                $update_data["entry_gained_via"] = $postData["job"]["entry_gained_via"];
            }

            if (
                empty($job->ss_location) &&
                !empty($postData["job"]["ss_location"])
            ) {
                $update_data["ss_location"] = $postData["job"]["ss_location"];
            }


            if (
                empty($job->ss_quantity) &&
                !empty($postData["job"]["ss_quantity"])
            ) {
                $update_data["ss_quantity"] = $postData["job"]["ss_quantity"];
            }


            if (
                empty($job->survey_numlevels) &&
                !empty($postData["job"]["survey_numlevels"])
            ) {
                $update_data["survey_numlevels"] = $postData["job"]["survey_numlevels"];
            }

            if (
                empty($job->ps_number_of_bedrooms) &&
                !empty($postData["job"]["ps_number_of_bedrooms"])
            ) {
                $update_data["ps_number_of_bedrooms"] = $postData["job"]["ps_number_of_bedrooms"];
            }

            if (
                empty($job->survey_numalarms) &&
                !empty($postData["job"]["survey_numalarms"])
            ) {
                $update_data["survey_numalarms"] = $postData["job"]["survey_numalarms"];
            }

            if (
                empty($job->survey_ceiling) &&
                !empty($postData["job"]["survey_ceiling"])
            ) {
                $update_data["survey_ceiling"] = $postData["job"]["survey_ceiling"];
            }

            if (
                empty($job->survey_ladder) &&
                !empty($postData["job"]["survey_ladder"])
            ) {
                $update_data["survey_ladder"] = $postData["job"]["survey_ladder"];
            }

            if (
                empty($job->entry_gained_via) &&
                !empty($postData["job"]["entry_gained_via"])
            ) {
                $update_data["entry_gained_via"] = $postData["job"]["entry_gained_via"];
            }

            if (
                empty($job->ts_items_tested) &&
                !empty($postData["job"]["ts_items_tested"])
            ) {
                $update_data["ts_items_tested"] = $postData["job"]["ts_items_tested"];
            }

            if (
                empty($job->ts_batteriesinstalled) &&
                !empty($postData["job"]["ts_batteriesinstalled"])
            ) {
                $update_data["ts_batteriesinstalled"] = $postData["job"]["ts_batteriesinstalled"];
            }

            if (
                empty($job->ts_alarmsinstalled) &&
                !empty($postData["job"]["ts_alarmsinstalled"])
            ) {
                $update_data["ts_alarmsinstalled"] = $postData["job"]["ts_alarmsinstalled"];
            }

            if (
                empty($job->ss_items_tested) &&
                !empty($postData["job"]["ss_items_tested"])
            ) {
                $update_data["ss_items_tested"] = $postData["job"]["ss_items_tested"];
            }

            if (
                empty($job->cw_items_tested) &&
                !empty($postData["job"]["cw_items_tested"])
            ) {
                $update_data["cw_items_tested"] = $postData["job"]["cw_items_tested"];
            }

            if (
                empty($job->we_items_tested) &&
                !empty($postData["job"]["we_items_tested"])
            ) {
                $update_data["we_items_tested"] = $postData["job"]["we_items_tested"];
            }


            if (!empty($update_data)) {

                $this->db->set($update_data)->where("id", $job->jid)->update("jobs");
            }


            if (
                empty($job->qld_new_leg_alarm_num) &&
                !empty($postData["job"]["qld_new_leg_alarm_num"])
            ) {
                $this->db->set(["qld_new_leg_alarm_num" => $postData["job"]["qld_new_leg_alarm_num"]])->where("property_id", $job->property_id)->update("property");
            }


            $this->api->putData('status', "Okay");
            return $this->api->setSuccess(true);
        } else {
            $this->api->putData('status', "invalid data");
            return $this->api->setSuccess(false);
        }
    }

    private function save_alarms_data_new($alarm)
    {
        // convert rec_batt_exp to YYYY-MM-DD
        if (!empty($alarm["rec_batt_exp"]) && preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $alarm["rec_batt_exp"]) != 1) {
            $parts = explode("/", $alarm["rec_batt_exp"]);
            $alarm["rec_batt_exp"] = "20{$parts[1]}-{$parts[0]}-01";
        }

		$alarm_id = "";
		if(!isset($alarm["alarm_id"]) || empty($alarm["alarm_id"])) {
			$alarm_id = $this->alarms->set_data($alarm);
		} else { 
			if (isset($alarm["is_deleted"]) && $alarm["is_deleted"] == 1) {
				$alarm["ts_discarded"] = 1;
			}
			$alarm_id = $alarm["alarm_id"];
            $this->alarms->set_data($alarm, $alarm_id);
		}

        if (empty($alarm_id)) {
            return false;
        }


        $allowed_types = ["gif", "jpg", "jpeg", "png", "pdf"];
        if (
            isset($alarm["image_expiry"]) &&
            !empty($alarm["image_expiry"]) &&
            in_array(strtolower(pathinfo($alarm["image_expiry"]["name"], PATHINFO_EXTENSION)), $allowed_types)
        ) {
            // storing image data to class variable to return 
            //$this->job_image_data["alarms"][$alarm_id]["image_expiry"] = $alarm["image_expiry"];

            $file_name = $alarm["job_id"] . "_alarm_expiry" . $alarm["alarm_key"] . "_" . date("YmdHis") . "_" . $alarm["image_expiry"]["name"];
            file_put_contents("./images/alarm_images/" . $file_name, base64_decode($alarm["image_expiry"]["data"]));

            $alarm_img = array(
                "alarm_id"                  => $alarm_id,
                "expiry_image_filename"     => $file_name,
                "image_lat"                 => $alarm['latitude_img'],
                "image_lng"                 => $alarm['longitude_img'],
                "created"                   => date("YmdHis"),
                "active"                    => 1
            );

            $check_result = $this->db->select("alarm_id")->from("alarm_images")->where_in("alarm_id", $alarm_id)->get();
            if ($check_result->num_rows() > 0) {
                #update alaram Data
                $this->db->set($alarm_img)->where("alarm_id", $alarm_id)->update("alarm_images");
            } else {
                // save new image filename in table 
                $this->db->set($alarm_img)->insert("alarm_images");
            }
        }

        if (
            isset($alarm["image_location"]) &&
            !empty($alarm["image_location"]) &&
            in_array(strtolower(pathinfo($alarm["image_location"]["name"], PATHINFO_EXTENSION)), $allowed_types)
        ) {
            // storing image data to class variable to return 
            //$this->job_image_data["alarms"][$alarm_id]["image_location"] = $alarm["image_location"];

            $file_name = $alarm["job_id"] . "_alarm_location" . $alarm["alarm_key"] . "_" . date("YmdHis") . "_" . $alarm["image_location"]["name"];
            file_put_contents("./images/alarm_images/" . $file_name, base64_decode($alarm["image_location"]["data"]));

            $alarm_img = array(
                "location_image_filename"   => $file_name,
            );
            // save new image filename in table 
            $this->db->set($alarm_img)->where("alarm_id", $alarm_id)->update("alarm_images");
        }

        return $alarm_id;
    }

    public function recover_alarms_data() {
		$this->load->model('Alarm_job_type_model');
		
        $this->api->assertMethod('post');
        $this->load->helper(array('form', 'url'));

        $job_update_data = array();

        $postData = $this->api->getPostData();

        if (
            empty($postData["job_id"]) && 
            empty($postData["date"])
        ) {
            echo "Invalid Data, Bye";
            return false;
        } 

        if (
            !empty($postData["job_id"]) && 
            !empty($postData["date"])
        ) {
            $job_id = explode("," , $postData['job_id']);
            $job_sync_data = $this->db->where_in("job_id", $job_id)->where("DATE_FORMAT(created_at, '%Y-%m-%d') = ", $postData["date"])->order_by("id", "ASC")->get("job_sync_log")->result();
        } elseif (
            !empty($postData["job_id"])
        ) {
            $job_id = explode("," , $postData['job_id']);
            $job_sync_data = $this->db->where_in("job_id", $job_id)->order_by("id", "ASC")->get("job_sync_log")->result();
        } elseif(!empty($postData["date"])){
            $job_sync_data = $this->db->where("DATE_FORMAT(created_at, '%Y-%m-%d') = ", $postData["date"])->order_by("id", "ASC")->get("job_sync_log")->result();
        } else {
            echo "Invalid Data, Bye";
            return false;
        }
		
		if(!empty($job_sync_data)){
			foreach($job_sync_data as $key => $value)
			{
				$updated_alarm_ids = [0];
				$payload_decode = json_decode($value->payload, true);
				if(empty($payload_decode)){
					continue;
				}
				
				if(!isset($payload_decode["job"]) || empty($payload_decode["job"])){
					continue;
				}

				$locationImg = $this->tech_locations_model->loadLastLocation($value->staff_id);
				$latitude_img = (isset($locationImg["lat"]) ? $locationImg["lat"] : "");
				$longitude_img = (isset($locationImg["lng"]) ? $locationImg["lng"] : "");
				
				if(isset($payload_decode["existing_alarms"]) && !empty($payload_decode["existing_alarms"]))
				{
					foreach($payload_decode["existing_alarms"] as $alarm)
					{
						$alarm["latitude_img"] = $latitude_img;
						$alarm["longitude_img"] = $longitude_img;
						$alarm["job_id"] = $value->job_id;
						$alarm["alarm_job_type_id"] = Alarm_job_type_model::get_bundled_smoke_alarm_service_id($payload_decode["job"]["j_service"]);
						
						
						if(isset($alarm["alarm_id"]) && !empty($alarm["alarm_id"]))
						{
							$updated_alarm_ids[] = $alarm["alarm_id"];
							if($alarm["is_updated"] == 1)
							{
								$this->save_alarms_data_new($alarm);
							}
						}else{
							$checkAlarmExist = $this->db->select('*')
									->from('alarm')
									->where('job_id', $value->job_id)
									->where('alarm_power_id', $alarm['alarm_power_id'])
									->where('alarm_type_id', $alarm['alarm_type_id'])
									->where('ts_position', $alarm['ts_position'])
									->where('ts_db_rating', $alarm['ts_db_rating'])
									->where('ts_alarm_sounds_other', $alarm['ts_alarm_sounds_other'])
									->where('make', $alarm['make'])
									->where('model', $alarm['model'])
									->where('ts_expiry', $alarm['ts_expiry'])
									->where('expiry', $alarm['expiry'])
									->where('alarm_reason_id', $alarm['alarm_reason_id'])
									->where_not_in('alarm_id', $updated_alarm_ids)
									->get()->row();

							if(empty($checkAlarmExist)){
								$this->save_alarms_data_new($alarm);
							}else{
								$alarm["alarm_id"] = $checkAlarmExist->alarm_id;
								$this->save_alarms_data_new($alarm);
							}
							
							print_r($checkAlarmExist);
						}
					}
				}

				if(isset($payload_decode["new_alarms"]) && !empty($payload_decode["new_alarms"]))
				{
					foreach($payload_decode["new_alarms"] as $alarm)
					{
						$alarm["latitude_img"] = $latitude_img;
						$alarm["longitude_img"] = $longitude_img;
						$alarm["job_id"] = $value->job_id;
						$alarm["alarm_job_type_id"] = Alarm_job_type_model::get_bundled_smoke_alarm_service_id($payload_decode["job"]["j_service"]);
						
						
						if(isset($alarm["alarm_id"]) && !empty($alarm["alarm_id"]))
						{
							$updated_alarm_ids[] = $alarm["alarm_id"];
							if($alarm["is_updated"] == 1)
							{
								$this->save_alarms_data_new($alarm);
							}
						}else{
							$checkAlarmExist = $this->db->select('*')
									->from('alarm')
									->where('job_id', $value->job_id)
									->where('alarm_power_id', $alarm['alarm_power_id'])
									->where('alarm_type_id', $alarm['alarm_type_id'])
									->where('ts_position', $alarm['ts_position'])
									->where('ts_db_rating', $alarm['ts_db_rating'])
									->where('ts_alarm_sounds_other', $alarm['ts_alarm_sounds_other'])
									->where('make', $alarm['make'])
									->where('model', $alarm['model'])
									->where('ts_expiry', $alarm['ts_expiry'])
									->where('expiry', $alarm['expiry'])
									->where('alarm_reason_id', $alarm['alarm_reason_id'])
									->where_not_in('alarm_id', $updated_alarm_ids)
									->get()->row();

							if(empty($checkAlarmExist)){
								$this->save_alarms_data_new($alarm);
							}else{
								$alarm["alarm_id"] = $checkAlarmExist->alarm_id;
								$this->save_alarms_data_new($alarm);
							}
						}
					}
				}
			}
		}
		print_r($job_sync_data);
    }

    public function ecoc_certifications()
    {
        $staff_id = $this->api->getJWTItem("staff_id");
        
        $select = "certifications.id, certifications.`status`, certifications.`job_id`, DATE_ADD(j.`date`, INTERVAL ct.`time_to_complete` DAY) as due_date, j.`date` as job_date, ct.`name` as certification_name, ct.`url` as certification_url, ct.`time_to_complete`";
        $cert_data = $this->Certifications_model
            ->as_array()
            ->fields($select)
            ->select("CONCAT(p.`address_1`,' ',p.`address_2`,', ',p.`address_3`,', ',p.`state`,' ',p.`postcode`) as property_address")
            ->join('certification_types as ct', 'ct.id = certifications.certification_id', 'LEFT')
            ->join('jobs as j', 'j.id = certifications.job_id AND j.assigned_tech = '.$staff_id, 'INNER')
            ->join('`property` AS p', 'j.`property_id` = p.`property_id`', 'LEFT')
            ->where_status('open','submitted','send_back')
            ->order_by('certifications.created', 'DESC')
            ->get_all();

        if(!empty($cert_data)){
            
            foreach( $cert_data as $key => $value ){
                $alarms = $this->db->select("
                    al.`alarm_id`,
                    al.`make`,
                    al.`model`,
                    al.`ts_position`,

                    al_pwr.`alarm_pwr`,
                    al_pwr.`alarm_make`,
                ")

                ->from("alarm AS al")
                ->join("alarm_pwr AS al_pwr", "al.alarm_power_id = al_pwr.alarm_pwr_id", "left")
                ->where("al.job_id", $value["job_id"])
                ->where("al.ts_discarded", 0)
                ->where("al.expiry >=", date("Y"))
                ->group_by('al.`alarm_id`')
                ->order_by("al.alarm_id", "ASC")
                ->get()->result_array();
                
                if ( in_array($value["status"], ["completed", "submitted"]) ) {
                    $cert_data[$key]["is_submitted"] = 1;
                } else {
                    $cert_data[$key]["is_submitted"] = 0;
                }

                $cert_data[$key]["alarms"] = $alarms;
                    
            }
            $this->api->setStatusCode(200);
            $this->api->setSuccess(true);
            $this->api->putData('certifications', $cert_data);
        }else{
            $this->api->setStatusCode(200);
            $this->api->setSuccess(false);
            $this->api->setMessage('No data available!');
        }
    }

    public function ecoc_certification_update()
    {
        $staff_id = $this->api->getJWTItem("staff_id");
        $certification_id = $this->api->getPostData('certification_id');
        $is_submitted = $this->api->getPostData('is_submitted');
       
        $exist_cert_data = $this->Certifications_model
            ->fields("certifications.*, ct.`name` as certification_name")
            ->join('certification_types as ct', 'ct.id = certifications.certification_id', 'LEFT')
            ->where(["certifications.id"=>$certification_id])->get();
        
        if(empty($exist_cert_data)){
            $this->api->setStatusCode(200);
            $this->api->setSuccess(false);
            $this->api->setMessage('No certifications data available!');
            return;
        }
        if(!in_array($is_submitted, ["0","1"])){
            $this->api->setStatusCode(200);
            $this->api->setSuccess(false);
            $this->api->setMessage('Invalid status data!');
            return;
        }
        
        if(in_array($exist_cert_data->status, ["open","submitted","send_back"])){
            if($is_submitted == 1){
                $cert_update_data = ["status" => "submitted", "app_completed_date" => date('Y-m-d H:i:s'), "app_completed_by" => $staff_id];
            }else{
                $cert_update_data = ["status" => "open", "app_completed_date" => NULL, "app_completed_by" => NULL];
            }
            
            if($exist_cert_data->status != $cert_update_data["status"]) {
                $this->Certifications_model->where(["id" => $certification_id])->update($cert_update_data);

                // insert log
                $log_details = "<b>APP</b>: <b>{$exist_cert_data->certification_name}</b> certification from <b>{$exist_cert_data->status}</b> to <b>{$cert_update_data['status']}</b>";
                $log_params = array(
                    'title' => 114, // Job Certification
                    'details' => $log_details,
                    'display_in_vjd' => 1,
                    'created_by_staff' => $staff_id,
                    'job_id' => $exist_cert_data->job_id
                );
                $this->system_model->insert_log($log_params);
            }
        }
        $this->api->setStatusCode(200);
        $this->api->setSuccess(true);
        $this->api->setMessage('Status updated successfully');
    }
}
