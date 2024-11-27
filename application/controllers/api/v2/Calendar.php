<?php

use App\Exceptions\HttpException;

class Calendar extends MY_ApiController
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('tech_model');
        $this->load->model('tech_run_model');
        $this->load->model('calendar_model');
        $this->load->model('figure_model');
    }

    public function monthly_schedule($year, $month)
    {
        $staffId = $this->api->getJWTItem('staff_id');
        $staffParams = array(
            'sel_query' => "sa.FirstName, sa.LastName",
            'staff_id' => $staffId
        );
        $staff = $this->gherxlib->getStaffInfo($staffParams)->row_array();

        $serviceTypes = $this->db->query("
            SELECT `id`, `type`
            FROM `alarm_job_type`
            WHERE `active` = 1
        ")->result_array();

        $this->api->setSuccess(true);
        $this->api->putData('staff', $staff);
        $this->api->putData('service_types', $serviceTypes);
        $this->api->putData('fn_agencies', $this->system_model->get_fn_agencies());
        $this->api->putData('vision_agencies', $this->system_model->get_vision_agencies());
    }

    public function tech_run_list($date)
    {
        $techId = $this->api->getJWTItem('staff_id');

        $techRunResult = $this->db->select('*')->from('tech_run')->where(array('assigned_tech' => $techId, 'date' => $date))->get();
        $techRun = null;
        $techRunRows = [];

        if ($techRunResult->num_rows() > 0) {
            $techRun = $techRunResult->row_array();
        }
        
        if( empty($techRun) ){
            $this->api->setSuccess(true);
            $this->api->putData('tech_run', []);
            $this->api->putData('tech_run_rows', []);
            $this->api->putData('agency_alarm', []);
            return;
        }

        # refresh run sheet to check is there any new jobs added
        $new_jobs_count = $this->tech_model->appendTechRunNewListings($techRun['tech_run_id'], $this->api->getJWTItem('staff_id'), $date, '', $this->config->item('country'), 1);
        $this->api->putData('new_jobs_count', $new_jobs_count);

        $trSel = "
            trr.`tech_run_rows_id`,
            trr.`row_id_type`,
            trr.`row_id`,
            trr.`hidden`,
            trr.`dnd_sorted`,
            trr.`highlight_color`,

            trr_hc.`tech_run_row_color_id`,
            trr_hc.`hex`,

            j.`id` AS jid,
            j.`precomp_jobs_moved_to_booked`,
            j.`completed_timestamp`,

            p.`property_id`,

            a.`agency_id`,
            a.`allow_upfront_billing`
        ";
        $trParams = [
            'sel_query' => $trSel,
            'sort_list' => [
                [
                    'order_by' => 'trr.sort_order_num',
                    'sort' => 'ASC'
                ]
            ],
            'display_only_booked' => 1,
            'display_query' => 0
        ];

        if ($techRun != null) {
            $techRunRowsResult = $this->tech_model->getTechRunRows($techRun['tech_run_id'], $this->config->item('country'), $trParams);
            if ($techRunRowsResult->num_rows() > 0) {
                $techRunRows = $techRunRowsResult->result_array();
            }

            $this->addExtraTechRunRowsData($techRunRows, $techId, $techRun['date']);
        }

        $agency_alarm = array();
        $agency_id = array_column($techRunRows, "agency_id");
        $agency_alarm_temp = $this->tech_run_model->get_agency_alarm($agency_id);
        if ($agency_alarm_temp) {
            foreach ($agency_alarm_temp as $value) {
                if (!isset($agency_alarm[$value->agency_id])) {
                    $agency_alarm[$value->agency_id] = [];
                }
                $agency_alarm[$value->agency_id][] = $value;
            }
        }

        $this->api->setSuccess(true);
        $this->api->putData('tech_run', $techRun);
        $this->api->putData('tech_run_rows', $techRunRows);
        $this->api->putData('agency_alarm', $agency_alarm);
    }

    private function addExtraTechRunRowsData(&$techRunRows, $techId, $date)
    {
        $icServices = $this->figure_model->getICService(); // ic service ids
        $countryId = $this->config->item('country');

        $techRunRowsAssoc = [];
        $techRunRowsAssocByJob = [];
        $techRunRowsAssocByKey = [];
        $techRunRowsAssocBySupplier = [];
        for ($x = 0; $x < count($techRunRows); $x++) {
            $techRunRow = &$techRunRows[$x];
            $techRunRow['job'] = null;
            $techRunRow['key'] = null;
            $techRunRow['supplier'] = null;
            $techRunRowsAssoc[$techRunRow['tech_run_rows_id']] = &$techRunRow;

            if ($techRunRows[$x]['row_id_type'] == 'job_id') {
                $techRunRowsAssocByJob[$techRunRow['row_id']] = &$techRunRow;
            } else if ($techRunRows[$x]['row_id_type'] == 'keys_id') {
                $techRunRowsAssocByKey[$techRunRow['row_id']] = &$techRunRow;
            } else if ($techRunRows[$x]['row_id_type'] == 'supplier_id') {
                $techRunRowsAssocBySupplier[$techRunRow['row_id']] = &$techRunRow;
            }
        }
        $jobIds = array_keys($techRunRowsAssocByJob);

        if (!empty($jobIds)) {
            $jobs = $this->tech_model->getJobRowDataWithJobIds($jobIds, $countryId);

            $propertyIds = [];
            $agencyIds = [];
            $jobsAssoc = [];
            for ($x = 0; $x < count($jobs); $x++) {
                $jobs[$x]['first_visit'] = true;
                $jobs[$x]['log'] = null;
                $jobs[$x]['alarm_make'] = null;
                $jobs[$x]['jnc_count'] = 0;
                $jobs[$x]['new_tenant'] = null;
                $propertyIds[] = $jobs[$x]['property_id'];
                $agencyIds[] = $jobs[$x]['agency_id'];

                if ($jobs[$x]["is_bundle_serv"] == 1) { // job can have multiple services

                    $alarmJobTypeIds = explode(",", $jobs[$x]["alarm_job_type_ids"]);
	                $jobs[$x]["hasSmokeAlarm"]      = Alarm_job_type_model::show_smoke_alarms($alarmJobTypeIds);
	                $jobs[$x]["hasSafetySwitch"]    = in_array(5, $alarmJobTypeIds);
	                $jobs[$x]["hasCordedWindow"]    = in_array(6, $alarmJobTypeIds);
	                $jobs[$x]["hasWaterEffeciency"] = in_array(15, $alarmJobTypeIds);
	                $jobs[$x]["is_view_only_service"]   = in_array(3, $alarmJobTypeIds);
	                $jobs[$x]["has_water_meter"]    = in_array(7, $alarmJobTypeIds);

                } else { // single service job

	                $jobs[$x]["hasSmokeAlarm"]      = Alarm_job_type_model::show_smoke_alarms($jobs[$x]["service_type_id"]);
                    $jobs[$x]["hasSafetySwitch"]    = $jobs[$x]["service_type_id"] == 5;
                    $jobs[$x]["hasCordedWindow"]    = $jobs[$x]["service_type_id"] == 6;
                    $jobs[$x]["hasWaterEffeciency"] = $jobs[$x]["service_type_id"] == 15;
                    $jobs[$x]["is_view_only_service"]   = $jobs[$x]["service_type_id"] == 3;
                    $jobs[$x]["has_water_meter"]    = $jobs[$x]["service_type_id"] == 7;

                }
                
                $jobs[$x]["is_ic_service"] = in_array($jobs[$x]["service_type_id"], $icServices);

                $jobsAssoc[$jobs[$x]['jid']] = &$jobs[$x];
            }

            $newTenants = $this->db->select('property_id,tenant_firstname,tenant_mobile')->from('property_tenants')->where([
                'active' => 1,
                'property_tenant_id >' => 0
            ])
                ->where_in('property_id', $propertyIds)
                ->get()->result_array();

            $propertiesVisits = $this->tech_model->checkPropertyFirstVisitsByIds($propertyIds);
            if ($countryId == 2) {
                $agencyAlarms = $this->system_model->displayOrcaOrCaviAlarmsByAgencyIds($agencyIds);
            }
            $jobExpiredAlarms = $this->system_model->findExpiredAlarmByJobIds($jobIds);

            for ($x = 0; $x < count($jobs); $x++) {
                $jobs[$x]['has_expired_alarms'] = $jobExpiredAlarms[$jobs[$x]['jid']] ?? false;
                foreach ($propertiesVisits as $property) {
                    if ($jobs[$x]['property_id'] == $property['property_id']) {
                        $jobs[$x]['first_visit'] = $property['j_count'] == 0;
                        break;
                    }
                }

                foreach ($newTenants as $newTenant) {
                    if (
                        $jobs[$x]['property_id'] == $newTenant['property_id'] &&
                        $jobs[$x]['booked_with'] == $newTenant['tenant_firstname']
                    ) {
                        $jobs[$x]['new_tenant'] = $newTenant;
                    }
                }

                if ($countryId == 2) {
                    foreach ($agencyAlarms as $alarmKey => $alarmMake) {
                        if ($jobs[$x]['agency_id'] == $alarmKey) {
                            $jobs[$x]['alarm_make'] = $alarmmake;
                            break;
                        }
                    }
                }
            }

            $jobsNotCompleted = $this->tech_run_model->getJobsNotCompleted($jobIds);
            foreach ($jobsNotCompleted as $notCompletedJob) {
                $jobsAssoc[$notCompletedJob->job_id]['jnc_count'] = $notCompletedJob->jnc_count;
            }

            $job_log_params = array(
                'sel_query' => "job_id, eventdate, eventtime",
                'job_ids' => $jobIds,
                'eventdate' => date('Y-m-d'),
                'contact_type' => 'Phone Call'
            );

            $logsResult = $this->tech_model->getJobLogByJobIds($job_log_params);

            foreach ($logsResult as $log) {
                $jobsAssoc[$log['job_id']]['log'] = $log;
            }

            foreach ($jobs as $job) {
                $techRunRowsAssocByJob[$job['jid']]['job'] = $job;
            }
        }

        $keyIds = array_keys($techRunRowsAssocByKey);

        if (!empty($keyIds)) {
            $keys = $this->tech_model->getTechRunKeysByIds($keyIds);

            $agencyIds = [];
            $keysAssoc = [];

            for ($x = 0; $x < count($keys); $x++) {
                $keys[$x]['first_visit'] = true;
                $keys[$x]['log'] = null;
                $keys[$x]['booked_keys'] = 0;
                $agencyIds[] = $keys[$x]['agency_id'];
                $keysAssoc[$keys[$x]['jid']] = &$keys[$x];
            }

            $numOfBookedKeys = $this->tech_model->getNumberOfBookedKeysByAgencyIds($techId, $date, $countryId, $agencyIds);

            for ($x = 0; $x < count($keys); $x++) {
                foreach ($numOfBookedKeys as $bookedKey) {
                    if ($keys[$x]['agency_id'] == $bookedKey['agency_id']) {
                        $keys[$x]['booked_keys'] = $bookedKey['j_count'];
                        break;
                    }
                }
            }

            foreach ($keys as $key) {
                $techRunRowsAssocByKey[$key['tech_run_keys_id']]['key'] = $key;
            }
        }

        $supplierIds = array_keys($techRunRowsAssocBySupplier);

        if (!empty($supplierIds)) {
            $suppliers = $this->tech_model->getTechRunSuppliersByIds($supplierIds);

            foreach ($suppliers as $supplier) {
                $techRunRowsAssocBySupplier[$supplier['tech_run_suppliers_id']]['supplier'] = $supplier;
            }
        }
    }

    // Update API- Data is taken from calendar table instead of leave table
    public function my_calendar()
    {
        $this->api->assertMethod('post');
        $post_data = $this->api->getPostData();

        if (!empty(@$post_data["month"])) {
            $start_date = date("Y-m-01", strtotime($post_data["month"]));
            $end_date = date("Y-m-t", strtotime($post_data["month"]));
        } else {
            $start_date = date("Y-m-01");
            $end_date = date("Y-m-t");
        }

        $this->db->select('*');
        $this->db->from('calendar');
        $this->db->where('staff_id', $this->api->getJWTItem("staff_id"));
        $this->db->where('date_start >=', $start_date);
        $this->db->where('date_start <=', $end_date);
        $this->db->order_by("date_start", "ASC");
        $result = $this->db->get();

        $data = [];
        if ($result->num_rows() > 0) {
            foreach ($result->result() as $row) {
                
                $data[] = array(
                    'id' => $row->calendar_id,
                    'staff_id' => $row->staff_id,
                    'start' => $row->date_start,
                    'end' => $row->date_finish,
                    'details' => $row->details,
                    'title' => $row->region,
                    'accomodation' => $row->accomodation,
                    'start_time' =>  $row->date_start_time,
                    'end_time' => $row->date_finish_time,
                    'marked_as_leave' => $row->marked_as_leave,
                    'type_of_leave' => "",
                );
            }
        }

        $this->api->setSuccess(true);
        $this->api->putData('leave', $data);
    }
}
