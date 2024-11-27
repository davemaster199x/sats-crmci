<?php

class Gherxlib {

    // We'll use a constructor, as you can't directly call a function
    // from a property definition.
    protected $CI;

    public function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->database();
        $this->CI->load->model('jobs_model','jm'); //load jobs model
    }

    /**
     * FORMAT AGENCY/STAFF FULL NAME
     * return staff/agency abbv name
     */
    function formatStaffName($fname, $lname) {
        return "{$fname}" . ( ($lname != "") ? ' ' . strtoupper(substr($lname, 0, 1)) . '.' : '' );
    }

    /**
     * Get Global Setting
     * $params country_id
     * return query
     */
    function getGlobalSettings($params) {
        $this->CI->db->select('*');
        $this->CI->db->from('global_settings as gs');
        $this->CI->db->join('staff_accounts sa', 'sa.StaffID = gs.allocate_personnel');
        $this->CI->db->where('gs.active', 1);
        $this->CI->db->where('gs.deleted', 0);

        if ($params['country_id'] != "") {
            $this->CI->db->where('country_id', $params['country_id']);
        }

        $query = $this->CI->db->get();
        return $query;
    }

    /**
     * GET STAFF INFO
     * @params staff_id for row
     * return query
     */
    function getStaffInfo($params) {
        if ($params['sel_query'] && $params['sel_query'] != "") {
            $this->CI->db->select($params['sel_query']);
        } else {
            $this->CI->db->select('*');
        }

        $this->CI->db->from('staff_accounts as sa');
        $this->CI->db->join('country_access ca', 'ca.staff_accounts_id = sa.StaffID', 'INNER');

        // custom joins
        if (isset($params['custom_joins']) && $params['custom_joins'] != '') {
            $this->CI->db->join($params['custom_joins']['join_table'], $params['custom_joins']['join_on'], $params['custom_joins']['join_type']);
        }

        $this->CI->db->where('sa.active', 1);
        $this->CI->db->where('sa.Deleted', 0);
        $this->CI->db->where('ca.country_id', $this->CI->config->item('country'));

        //staff_id
        if ($params['staff_id'] != "") {
            $this->CI->db->where('sa.StaffID', $params['staff_id']);
        }

        if ($params['ClassID'] != "") {
            $this->CI->db->where('sa.ClassID', $params['ClassID']);
        }

        // sort
        if (isset($params['sort_list'])) {
            foreach ($params['sort_list'] as $sort_arr) {
                if ($sort_arr['order_by'] != "" && $sort_arr['sort'] != '') {
                    $this->CI->db->order_by($sort_arr['order_by'], $sort_arr['sort']);
                }
            }
        }

        $query = $this->CI->db->get();
        return $query;
    }

    /**
     * GET AGENCY INFO
     * $param agency_id
     * return row
     */
    function agency_info($agency_id) {
        $query = $this->CI->db->get_where('agency', array('agency_id' => $agency_id));
        return ($query->num_rows() > 0) ? $query->row() : false;
    }

    /**
     * Get Escalate Job Reason by JOB ID
     * @params job_id
     * return query
     */
    function getEscalateReason($job_id) {
        $this->CI->db->select('*');
        $this->CI->db->from('selected_escalate_job_reasons sejr');
        $this->CI->db->join('escalate_job_reasons ejr', 'ejr.escalate_job_reasons_id = sejr.escalate_job_reasons_id', 'left');
        $this->CI->db->where('sejr.deleted', 0);
        $this->CI->db->where('sejr.active', 1);
        $this->CI->db->where('sejr.job_id', $job_id);
        $query = $this->CI->db->get();
        return $query;
    }

    function getEscalateAgencyInfo($params) {
        if (isset($params['sel_query'])) {
            $sel_query = $params['sel_query'];
        } else {
            $sel_query = '*';
        }
        $this->CI->db->select($sel_query);
        $this->CI->db->from('escalate_agency_info as eai');

        // country id filter
        if (is_numeric($params['country_id'])) {
            $this->CI->db->where('eai.`country_id`', $params['country_id']);
        }

        // agency filters
        if (isset($params['agency_filter']) && $params['agency_filter'] != '') {
            $this->CI->db->where('eai.`agency_id`', $params['agency_filter']);
        }

        // Date filters
        if (isset($params['date']) && $params['date'] != '') {
            $this->CI->db->where("DATE_FORMAT(eai.date_created,'%Y-%m-%d')", $params['date']);
            //$this->CI->db->where("CAST( eai.`date_created` AS Date ) = '{$params['date']}'");
        }

        $this->CI->db->where('eai.active', 1);
        $this->CI->db->where('eai.deleted', 0);

        $query = $this->CI->db->get();
        return $query;
    }

    /**
     * GET $this->config->item('country') INFO
     * @param $country - country_id
     * return row
     */
    function getCountryViaCountryId($country) {
        $query = $this->CI->db->get_where('countries', array('country_id' => $country));
        return $query->row();
    }

    /**
     * GET REGION LABEL/NAME
     * @param $country - country_id
     * return region/district name (District/Region)
     */
    function getDynamicRegion($country) {
        // NZ
        if ($country == 2) {
            $region_str = 'District';
        } else {
            $region_str = 'Region';
        }
        return $region_str;
    }

    /**
     * GET STATE LABEL/NAME
     * $param $country - country_id
     * return state/region name (Region/State)
     */
    function getDynamicState($country) {
        // NZ
        if ($country == 2) {
            $state_str = 'Region';
        } else {
            $state_str = 'State';
        }
        return $state_str;
    }

    /**
     * GET AGE for BNE DATE AGE
     * @param $d1 - date
     * return age
     */
    function getAge($d1, $d2=null) {
        if( $d2!=NULL ){
            $date1 = date_create(date('Y-m-d', strtotime($d1)));
            $date2 = date_create(date('Y-m-d', strtotime($d2)));
            $diff = date_diff($date1, $date2);
            $age = $diff->format("%r%a");
            $age_val = (((int) $age) != 0) ? $age : 0;
        }else{
            $date1 = date_create(date('Y-m-d', strtotime($d1)));
            $date2 = date_create(date('Y-m-d'));
            $diff = date_diff($date1, $date2);
            $age = $diff->format("%r%a");
            $age_val = (((int) $age) != 0) ? $age : 0;
        }
       
        return $age_val;
    }

    /**
     * Get abbr
     */
    function getJobTypeAbbrv($jt) {

        // job type
        switch ($jt) {
            case 'Once-off':
                $jt = 'Once-off';
                break;
            case 'Change of Tenancy':
                $jt = 'COT';
                break;
            case 'Yearly Maintenance':
                $jt = 'YM';
                break;
            case 'Fix or Replace':
                $jt = 'FR';
                break;
            case '240v Rebook':
                $jt = '240v';
                break;
            case 'Lease Renewal':
                $jt = 'LR';
                break;
            case 'Annual Visit':
                $jt = 'Annual';
        }
        return $jt;
    }

    /**
     * Get Last Contact
     * @param job_id
     * return query
     */
    function getLastContact($job_id) {
        $query = $this->CI->db->select('eventdate')->from('job_log')->where('job_id', $job_id)->order_by('eventdate', 'DESC')->limit(1)->get();
        return $query;
    }

    /**
     * Get BNE to Call count for bubble
     * return num_rows
     */
    function getBneCount() {
        $custom_where = "p.`bne_to_call` = 1 AND j.status NOT IN('Completed','Cancelled','Merged Certificates','Booked','Pre Completion')";
        $sel_query = "j.`id` ";
        $params = array(
            'sel_query' => $sel_query,
            'p_deleted' => 0,
            'a_status' => 'active',
            'del_job' => 0,
            'custom_where' => $custom_where,
        );
        $query = $this->CI->jobs_model->get_jobs($params);
        return ($query->num_rows() > 0) ? $query->num_rows() : false;
    }

    /**
     * Get Allocate total count for buble
     * return num_rows
     */
    function getAllocateCount() {
        $country_id = $this->CI->config->item('country');
        $job_status = 'Allocate';
        $sel_query = "j.`id` ";
        $params = array(
            'sel_query' => $sel_query,
            'p_deleted' => 0,
            'a_status' => 'active',
            'del_job' => 0,
            'country_id' => $country_id,
            'job_status' => $job_status,
        );
        $query = $this->CI->jobs_model->get_jobs($params);
        return ($query->num_rows() > 0) ? $query->num_rows() : false;
    }

    /**
     * Get DHA total count for bubble
     * return num_rows
     */
    function getDHACount() {
        $country_id = $this->CI->config->item('country');
        $job_status = 'DHA';
        $sel_query = "j.`id` ";
        $params = array(
            'sel_query' => $sel_query,
            'p_deleted' => 0,
            'a_status' => 'active',
            'del_job' => 0,
            'country_id' => $country_id,
            'job_status' => $job_status,
        );
        $query = $this->CI->jobs_model->get_jobs($params);
        return ($query->num_rows() > 0) ? $query->num_rows() : false;
    }

    /**
     * GET Allocated by Name (FOR ALLOCATED)
     * @param Staff_id
     * return staff fname and lname
     */
    function getAllocatedBy($staff_id) {
        $query = $this->CI->db->get_where('staff_accounts', array('StaffID' => $staff_id), $limit, $offset);
        $row = $query->row_array();
        return "{$row['FirstName']}" . ( ($row['LastName'] != "") ? ' ' . strtoupper(substr($row['LastName'], 0, 1)) . '.' : '' );
    }

    /**
     * Allocated Deadline
     */
    function getAllocateDeadLine($all_opt, $all_ts) {

        if ($all_opt == 1 || $all_opt == 2) {

            if ($all_opt == 1) { // 2 hours
                $append_hour = 2;
            } else if ($all_opt == 2) { // 4 hours
                $append_hour = 4;
            }

            $deadline = date('Y-m-d H:i:s', strtotime($all_ts . " +{$append_hour} hours"));
        } else if ($all_opt == 3) {
            $deadline = date('Y-m-d 18:00:00');
        }

        return $deadline;
    }

    /**
     * Insert Notifications
     */
    function insertNewNotification($param) {

        // pass notification type, default is 1, general notification
        $notf_type = ( $param['notf_type'] != '' ) ? $param['notf_type'] : 1;

        $data = array(
            'notification_message' => $param['notf_msg'],
            'notify_to' => $param['staff_id'],
            'notf_type' => $notf_type,
            'country_id' => $param['country_id'],
        );
        $this->CI->db->insert('notifications', $data);

        if ($this->CI->db->affected_rows() > 0) {
            $dataUpdate = array('sound_notification' => 1);
            $this->CI->db->where('StaffID', $param['staff_id']);
            $this->CI->db->update('staff_accounts', $dataUpdate);
            $this->CI->db->limit(1);
        }
    }
    

    /**
     * GET who created Send Letters (For Added by in Send Letters)
     * @param property_id
     * return who created send letters
     */
    function getWhoCreatedSendLetters($property_id) {
        $query = $this->CI->db->get_where('property_event_log', array('property_id' => $property_id), $limit, $offset);
        $row = $query->row_array();
        if ($row['log_agency_id'] != "") {
            $who = 'AGENCY';
        } else if ($row['staff_id'] != 0) {
            $who = config_item('company_name_short');
        } else {
            $who = 'AGENCY';
        }

        return $who;
    }

    /**
     * GET who maximum tenant (For Send Letters)
     * @param N/A
     * return current maximum tenant
     */
    function getCurrentMaxTenants() {
        $num_tenants = 4;
        return $num_tenants;
    }

    /**
     * GET new tenant data (For Send Letters)
     * @param property_id, active
     * return new tenant data
     */
    function getNewTenantsData($params) {
        $query = $this->CI->db->get_where('property_tenants', array(
            'property_id' => $params['property_id'],
            'active' => $params['active'],
            'property_tenant_id >' => 0), $params['limit'], $params['offset']);
        $row = $query->result();
        return $row;
    }

    /**
     * GET email status (For Send Letters)
     * @param N/A
     * return new tenant data
     */
    function getCrnSetting($country_default) {
        $query = $this->CI->db->select('cron_send_letters')
                ->get_where('crm_settings', array('country_id' => $country_default));
        $row = $query->result();
        return $row;
    }

    function isDHAagencies($agency_id) {
        $dha_agencies = array(
            3043,
            3036,
            3046,
            1902,
            3044,
            1906,
            1927,
            3045
        );
        if (in_array($agency_id, $dha_agencies)) {
            return true;
        } else {
            return false;
        }
    }

    // get country data
    function get_country_data() {

        $country_id = $this->CI->config->item('country');
        // get country data
        $c_params = array('country_id' => $country_id);
        return $this->CI->system_model->get_countries($c_params);
    }

    // compute check digit
    function getCheckDigit($number) {

        $sumTable = array(array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9), array(0, 2, 4, 6, 8, 1, 3, 5, 7, 9));
        $length = strlen($number);
        $sum = 0;
        $flip = 1;
        // Sum digits (last one is check digit, which is not in parameter)
        for ($i = $length - 1; $i >= 0; --$i)
            $sum += $sumTable[$flip++ & 0x1][$number[$i]];
        // Multiply by 9
        $sum *= 9;

        return (int) substr($sum, -1, 1);
    }

    function crmLink($type, $id, $content, $target=NULL, $ht=NULL, $row_color = NULL) {

		if(!empty($target)){
			$target = ' target="' . $target . '"';
		} else {
			$target = '';
		}

        // Just update the link color to white cause in SAS theme the link cannot be read
        if ($row_color == 'greenRowBg') {
            $row_color = 'style="color: white;"';
        }

		if ($type == "vpd") {

            return '<a href="' . $this->CI->config->item("crmci_link") . '/properties/details/?id=' . $id . '" '. $target .' '.$row_color.'>' . $content . '</a>';
        } elseif ($type == "vjd") {

            return '<a href="' . $this->CI->config->item("crmci_link") . '/jobs/details/' . $id . '" '. $target .' '.$row_color.'>' . $content . '</a>';
        } elseif ($type == 'vad') {
            $htText = "";
            $thBoldClass = "";
            if($ht==1){
                $htText = "(HT)";
                $thBoldClass = "j_bold";
            }
            if($ht==2){
                $htText = "(VIP)";
                $thBoldClass = "j_bold";
            }
            if($ht==3){
                $htText = "(HWC)";
                $thBoldClass = "j_bold";
            }
            return '<a class="'.$thBoldClass.'" target="_blank" href="/agency/view_agency_details/' . $id . '">' . $content .' '.$htText. '</a>';
        } elseif ($type == 'tools') {

            return '<a href="' . $this->CI->config->item("crm_link") . '/view_tool_details.php?id=' . $id . '">' . $content . '</a>';
        } elseif ($type == 'vehicle') {

            return '<a href="' . $this->CI->config->item("crm_link") . '/view_vehicle_details.php?id=' . $id . '">' . $content . '</a>';
        } elseif ($type == 'run_sheet_admin') {

            return '<a href="' . $this->CI->config->item("crm_link") . '/run_sheet_admin.php?tr_id=' . $id . '">' . $content . '</a>';
        } elseif ($type == 'view_job_details_tech') {

            return '<a href="' . $this->CI->config->item("crm_link") . '/view_job_details_tech.php?id=' . $id . '">' . $content . '</a>';
        } elseif ($type == 'view_combined') {
            
            return '<a href="' . $this->CI->config->item("crm_link") . '/view_combined.php?id=' . $id . '">' . $content . '</a>';
        } elseif ($type === 'uploads_expenses') {
            
            return '<a target="_blank" href="' . $this->CI->config->item("crm_link") . '/' . $id . '">' . $content . '</a>';
        } elseif ($type === 'expense_details') {
            
            return '<a href="' . $this->CI->config->item("crm_link") . '/expense_details.php?id=' . $id . '">' . $content . '</a>';
        }elseif ($type === 'old_crm_task_ss') {
            
            return '<a class="fancybox-uploaded-screenshot" href="' . $this->CI->config->item("crm_link") . '/images/crm_task_screenshots/' . $id . '">' . $content . '</a>';
        }
    }

    function convertDate($date)
    {
        if(stristr($date, "/"))
        {
            $tmp = explode("/", $date);
            $date = $tmp[2] . "-" . $tmp[1] . "-" . $tmp[0];
        }
        return $date;
        
    }

    function convertDateAus($date) {
        if (stristr($date, "-")) {
            $tmp = explode("-", $date);
            $date = $tmp[2] . "/" . $tmp[1] . "/" . $tmp[0];
        }
        return $date;
    }

    function printa($val) {
        echo "<pre>";
        print_r($val);
        echo "</pre>";
    }

    function get_country_iso() {
        $this->CI->db->select('iso');
        $this->CI->db->from('countries');
        $this->CI->db->where('country_id', $this->CI->config->item('country'));
        return $this->CI->db->get()->row()->iso;
    }

    /**
     * Upload Files
     */
    public function do_upload($userfile, $params) {
        if ($params['upload_path'] && $params['upload_path'] != "") {
            $upload_path = $params['upload_path'];
        } else {
            $upload_path = './images/';
        }

        if ($params['max_size'] && $params['max_size'] != "") {
            $max_size = $params['max_size'];
        } else {
            $max_size = 0; //no limit
        }

        if ($params['max_width'] && $params['max_width'] != "") {
            $max_width = $params['max_width'];
        } else {
            $max_width = 0; //no limit
        }

        if ($params['max_height'] && $params['max_height'] != "") {
            $max_height = $params['max_height'];
        } else {
            $max_height = 0; //no limit
        }


        // ------ Set value if set
        if ($params['file_name'] && $params['file_name'] != "") {
            $config['file_name'] = $params['file_name'];
        }
        if ($params['allowed_types'] && $params['allowed_types'] != "") {
            $config['allowed_types'] = $params['allowed_types'];
        }

        $config['upload_path'] = $upload_path;
        $config['max_size'] = $max_size;
        $config['max_width'] = $max_width;
        $config['max_height'] = $max_height;

        $this->CI->load->library('upload');
        $this->CI->upload->initialize($config);

        if (!$this->CI->upload->do_upload($userfile)) {
            #return 	$this->CI->upload->display_errors();
            return false;
        } else {
            #return	$this->CI->upload->data();
            return true;
        }
    }

    // get last service  - return row
    public function get_last_service_row($property_id) {
        $this->CI->db->select("j.id, j.date, j.status, j.assigned_tech, j.job_type, p.qld_new_leg_alarm_num, p.prop_upgraded_to_ic_sa, .p.state");
        $this->CI->db->from("jobs j");
        $this->CI->db->join('property as p', 'p.property_id = j.property_id', 'left');
        $this->CI->db->where('j.property_id', $property_id);
        $this->CI->db->group_start();
        $this->CI->db->where('j.status', 'Completed');
        $this->CI->db->or_where('j.status', 'Merged Certificates');
        $this->CI->db->group_end();
        $this->CI->db->where('j.del_job', 0);
        $this->CI->db->order_by('j.date', 'DESC');
        $this->CI->db->limit(1);
        $query = $this->CI->db->get();
        if ($query->num_rows() > 0) {
            return $query;
        } else {
            return false;
        }
    }

    public function convertEmailToArray($email){
	
		unset($jemail);
		$jemail = array();
		$temp = explode("\n",trim($email));
		foreach($temp as $val){
			
			$val2 = preg_replace('/\s+/', '', $val);
			if(filter_var($val2, FILTER_VALIDATE_EMAIL)){
				$jemail[] = $val2;
			}
			
		}
		
		// send email
		return $jemail;
	
    }

    function getGlobalSettings_personnel() {

       /* 
        $globalParams = array('country_id'=>$this->CI->config->item('country'));
        $globalSettings = $this->CI->gherxlib->getGlobalSettings($globalParams)->row();
        $globalSettings_personnel = $globalSettings->allocate_personnel;
        */

        $this->CI->db->select('*');
        $this->CI->db->from('staff_accounts as sa');
        $this->CI->db->join('country_access ca', 'ca.staff_accounts_id = sa.StaffID', 'INNER');
        $this->CI->db->where('sa.active', 1);
        $this->CI->db->where('sa.Deleted', 0);
        $this->CI->db->where('ca.country_id', $this->CI->config->item('country'));
        //$this->CI->db->where_in('sa.StaffID',$globalSettings_personnel);
        $this->CI->db->where('sa.StaffID',$this->CI->session->staff_id);

        $query = $this->CI->db->get();
        return $query->row_array();
    }

    public function getDaysMissedBy($completed_date, $end_date){

        $completed_date = date_create(date('Y-m-d', strtotime($completed_date)));
        $end_date = date_create(date('Y-m-d', strtotime($end_date)));

        if($completed_date<=$end_date){
            return NULL;
        }else{
            $diff = date_diff($end_date,$completed_date);
            $age = $diff->format("%r%a");
            $age_val = (((int) $age) != 0) ? $age : 0;
            return $age_val;
        }

    }

    // return true if job/status == BOOKED, Pre Completion etc....
    function NLMjobStatusCheck($prop_id){

        $this->CI->db->select("COUNT(jobs.id) AS count");
        $this->CI->db->from('jobs');
        $this->CI->db->where('property_id', $prop_id);
        $this->CI->db->where('del_job', 0);
        $this->CI->db->group_start();
        $this->CI->db->where('status','Booked');
        $this->CI->db->or_where('status','Pre Completion');
        $this->CI->db->or_where('status','Merged Certificates');
        //$this->CI->db->or_where('status','Cancelled');
        $this->CI->db->group_end();
        $this->CI->db->limit(1);
        $query = $this->CI->db->get();
        try {
            return ($query->row()->count > 0) ? TRUE : FALSE;
        }
        catch (\Exception $ex) {
            return FALSE;
        }

    }

    //return row array
    function getbundleServices($job_id,$bs_id){

        $this->CI->db->select('*');
        $this->CI->db->from('bundle_services as bs');
        $this->CI->db->join('alarm_job_type as ajt','ajt.id = bs.alarm_job_type_id','left');
        $this->CI->db->where('job_id', $job_id);
        if($bs_id!=""){
            $this->CI->db->where('bundle_services_id',$bs_id);
        }
        $this->CI->db->order_by('ajt.id');

        return $this->CI->db->get()->row_array();

    }

    function runSync($job_id,$jserv,$bundle_id_param=NULL){

        //get job details
        $j5 =  $this->jGetJobDetails($job_id);

        //is bundle
	    if($j5['bundle']==1){ //is bundle

            // get bundle id
            $bun_ids = explode(",",trim($j5['bundle_ids']));

            $bundle_id = ($bundle_id_param!="")?$bundle_id_param:$bun_ids[0];

            // check if jobs are already synced
            $query = $this->CI->db->get_where('bundle_services',array('bundle_services_id' => $bundle_id));
            $js = $query->row_array();

            // not yet snyc, do sync
            if($js['sync']==0){

                    // get previous safety switch that is job status completed
                    switch($jserv){
                        case 2:
                            $prev_job_sql = $this->CI->jm->getPrevSmokeAlarm($j5['property_id']);
                        break;
                        case 12:
                            $prev_job_sql = $this->CI->jm->getPrevSmokeAlarm($j5['property_id']);
                        break;
                        case 5:
                            $prev_job_sql = $this->CI->jm->getPrevSafetySwitch($j5['property_id']);
                        break;
                        case 6:
                            $prev_job_sql = $this->CI->jm->getPrevCordedWindow($j5['property_id']);
                        break;
                        case 7:
                            $prev_job_sql = $this->CI->jm->getPrevWaterMeter($j5['property_id']);
                        break;
                        case 15: // WE
                            $prev_job_sql = $this->CI->jm->getPrevWaterEfficiency($j5['property_id']);
                        break;
                    }

                    if($prev_job_sql->num_rows()>0){
                        $prev_job_row = $prev_job_sql->row_array();
                        switch($jserv){
                            case 2:
                                $this->SnycSmokeAlarmData($job_id,$prev_job_row['id']);
                            break;
                            case 12:
                                $this->SnycSmokeAlarmData($job_id,$prev_job_row['id']);
                            break;
                            case 5:
                                $this->SnycSafetySwitchData($job_id,$prev_job_row['id']);
                            break;
                            case 6:
                                $this->SnycCordedWindowData($job_id,$prev_job_row['id']);
                            break;
                            case 7:
                                $this->SnycWaterMeter($job_id,$prev_job_row['id']);
                            break;
                            case 15: // WE
                                $this->SnycWaterEfficiency($job_id,$prev_job_row['id']);
                            break;
                        }

                        $this->markAsSyncBundle($bundle_id);

                    }



            }

        }else{  //is not bundle

            switch($jserv){
                case 2:
                    $is_sync = $j5['alarms_synced'];
                break;
                case 12:
                    $is_sync = $j5['alarms_synced'];
                break;
                case 5:
                    $is_sync = $j5['ss_sync'];
                break;
                case 6:
                    $is_sync = $j5['cw_sync'];
                break;
                case 7:
                    $is_sync = $j5['wm_sync'];
                break;
                case 15: // WE
                    $is_sync = $j5['we_sync'];
                break;
            }

            if($is_sync==0){

                	// get previous safety switch that is job status completed
                    switch($jserv){
                        case 2:
                            $prev_job_sql = $this->CI->jm->getPrevSmokeAlarm($j5['property_id']);
                        break;
                        case 12:
                            $prev_job_sql = $this->CI->jm->getPrevSmokeAlarm($j5['property_id']);
                        break;
                        case 5:
                            $prev_job_sql =  $this->CI->jm->getPrevSafetySwitch($j5['property_id']);
                            if( $prev_job_sql == false ){
                                $prev_job_sql = $this->CI->jm->getPrevSmokeAlarm($j5['property_id']);
                            }
                        break;
                        case 6:
                            $prev_job_sql = $this->CI->jm->getPrevCordedWindow($j5['property_id']);
                        break;
                        case 7:
                            $prev_job_sql = $this->CI->jm->getPrevWaterMeter($j5['property_id']);
                        break;
                        case 15: // WE
                            $prev_job_sql = $this->CI->jm->getPrevWaterEfficiency($j5['property_id']);
                        break;
                    }

                    
                    if($prev_job_sql->num_rows()>0){
                        $prev_job_row = $prev_job_sql->row_array();
                        switch($jserv){
                            case 2:
                                $this->SnycSmokeAlarmData($job_id,$prev_job_row['id']);
                            break;
                            // SA IC
                            case 12:
                                $this->SnycSmokeAlarmData($job_id,$prev_job_row['id']);
                            break;
                            case 5:
                                $this->SnycSafetySwitchData($job_id,$prev_job_row['id']);
                            break;
                            case 6:
                                $this->SnycCordedWindowData($job_id,$prev_job_row['id']);
                            break;
                            case 7:
                                $this->SnycWaterMeter($job_id,$prev_job_row['id']);
                            break;
                            case 15: // WE
                                $this->SnycWaterEfficiency($job_id,$prev_job_row['id']);
                            break;
                        }

                        $this->markAsSync($job_id,$jserv);

                    }


            }




        }
    }

    function jGetJobDetails($job_id){

        // get job details
        $this->CI->db->select('*');
        $this->CI->db->from('jobs as j');
        $this->CI->db->join('alarm_job_type as ajt','ajt.id = j.service','left');
        $this->CI->db->where('j.id',$job_id);
        $query = $this->CI->db->get();
        return ($query->num_rows()>0)?$query->row_array():false;

    }

    function SnycSmokeAlarmData($job_id,$prev_job_id){

        //$prev_job2 = $prev_job_id;

        //$get_job1 = $this->CI->db->get_where('jobs', array('id' => $prev_job_id));
        $get_job1 = $this->CI->db->select('*')->from('jobs')->where('id',$prev_job_id)->get();
        $prev_job = $get_job1->row_array();


        // update safety alarm details
        $data = array(
            'survey_numlevels' => $prev_job['survey_numlevels'],
            'survey_ceiling' => $prev_job['survey_ceiling'],
            'survey_ladder' => $prev_job['survey_ladder'],
            'ts_safety_switch' => $prev_job['ts_safety_switch'],
            'ss_location' => $prev_job['ss_location'],
            'ss_quantity' => $prev_job['ss_quantity'],
            'ts_safety_switch_reason' => $prev_job['ts_safety_switch_reason'],
            'ss_image' => $prev_job['ss_image']
        );
        $this->CI->db->where('id',$job_id);
        $this->CI->db->update('jobs',$data);

        // get previous job and insert previous alarm to this
       /* $get_alarm_sql = $this->CI->db->get_where('alarm',array('job_id'=>$prev_job['id'], 'ts_discarded' => 0));

        if( $get_alarm_sql->num_rows()>0){

            $get_alarm_sql_row = $get_alarm_sql->row_array();

            $data_insert_alarm = array(
                'job_id' => $job_id,
                'alarm_power_id' => $get_alarm_sql_row['alarm_power_id'],
                'alarm_type_id' => $get_alarm_sql_row['alarm_type_id'],
                'make' => $get_alarm_sql_row['make'],
                'model' => $get_alarm_sql_row['model'],
                'ts_position' => $get_alarm_sql_row['ts_position'],
                'alarm_job_type_id' => $get_alarm_sql_row['alarm_job_type_id'],
                'expiry' => $get_alarm_sql_row['expiry'],
                'ts_required_compliance' => $get_alarm_sql_row['ts_required_compliance']
            );
            $this->CI->db->insert('alarm',$data_insert_alarm);
         }
         */

        $this->CI->db->query("
		INSERT INTO
		`alarm` (
			`job_id`,
			`alarm_power_id`,
			`alarm_type_id`,
			`make`,
			`model`,
			`ts_position`,
			`alarm_job_type_id`,
			`expiry`,
			`ts_required_compliance`
		)
		SELECT
			{$job_id},
			`alarm_power_id`,
			`alarm_type_id`,
			UPPER( `make` ),
			UPPER( `model` ),
			UPPER( `ts_position` ),
			`alarm_job_type_id`,
			`expiry`,
			`ts_required_compliance`
		FROM `alarm`
		WHERE `job_id` = {$prev_job['id']}
		AND `ts_discarded` = 0
	");

    }

    function SnycSafetySwitchData($job_id,$prev_job_sql2){

        //get prop id
        $get_prop_id = $this->CI->db->get_where('jobs',array('id'=>$job_id));
        $p = $get_prop_id->row_array();

        //check if no ss data yet
        $this->CI->db->select('*');
        $this->CI->db->from('safety_switch as ss');
        $this->CI->db->join('jobs as j','j.id = ss.job_id','left');
        $this->CI->db->where('property_id',$p['property_id']);
        $this->CI->db->where('j.status','Completed');
        $ss_query = $this->CI->db->get();

        if($ss_query->num_rows()>0){ 	// has already SS data, get previous SS

            //get previous ss data
           // $prev_job2 = $prev_job_sql2;
            $query1 = $this->CI->db->get_where('jobs',array('id'=> $prev_job_sql2));

            $prev_job = $query1->row_array();

            // update safety switch job details
            $data = array(
                'ss_location' => $prev_job['ss_location'],
                'ss_quantity' => $prev_job['ss_quantity']
            );
            $this->CI->db->where('id',$job_id);
            $this->CI->db->update('jobs',$data);


        }else{ // no SS data yet, get it from alarm

            // get previous SA data
            $prev_job2 = getPrevSmokeAlarm($p['property_id']);
            $query1 = $this->CI->db->get_where('jobs',array('id'=> $prev_job2['id']));
            $prev_job = $query1->row_array();

            // update safety switch job details
            $data = array(
                'ss_location' => $prev_job['ss_location'],
                'ss_quantity' => $prev_job['ss_quantity']
            );
            $this->CI->db->where('id',$job_id);
            $this->CI->db->update('jobs',$data);

        }

        // get previous job and insert previous safety switch to this job
       /* $prev_ss_query = $this->CI->db->get_where('safety_switch',array('job_id'=>$prev_job['id']));
        $prev_ss_query_row = $prev_ss_query->row_array();

        $ss_data = array(
            'job_id' => $job_id,
            'make' => $prev_ss_query_row['make'],
            'model' => $prev_ss_query_row['model']
        );
        $this->CI->db->insert('safety_switch',$ss_data); */

        $this->CI->db->query("
		INSERT INTO
		`safety_switch` (
			`job_id`,
			`make`,
			`model`
		)
		SELECT {$job_id}, `make`, `model`
		FROM `safety_switch`
		WHERE `job_id` = {$prev_job['id']}
	");


    }

    function SnycCordedWindowData($job_id,$prev_job_sql2){

        //$prev_job2 = $prev_job_sql2;
        $query1 = $this->CI->db->get_where('jobs',array('id'=> $prev_job_sql2));

        $prev_job = $query1->row_array();

        // get previous job and insert previous corded window to this job
       /* $cc_query = $this->CI->db->get_where('corded_window',array('job_id'=>$prev_job['id']));
        $cc_query_row = $cc_query->row_array();

        $cc_data = array(
            'job_id' => $job_id,
            'covering' => $cc_query_row['covering'],
            'ftllt1_6m' => $cc_query_row['ftllt1_6m'],
            'tag_present' => $cc_query_row['tag_present'],
            'clip_rfc' => $cc_query_row['clip_rfc'],
            'clip_present' => $cc_query_row['clip_present'],
            'loop_lt220m' => $cc_query_row['loop_lt220m'],
            'seventy_n' => $cc_query_row['seventy_n'],
            'cw_image' => $cc_query_row['cw_image'],
            'location' => $cc_query_row['location'],
            'num_of_windows' => $cc_query_row['num_of_windows']
        );
        $this->CI->db->insert('corded_window',$cc_data); */

        $this->CI->db->query("
		INSERT INTO
		`corded_window` (
			`job_id`,
			`covering`,
			`ftllt1_6m`,
			`tag_present`,
			`clip_rfc`,
			`clip_present`,
			`loop_lt220m`,
			`seventy_n`,
			`cw_image`,
			`location`,
			`num_of_windows`
		)
		SELECT
			'{$job_id}',
			`covering`,
			`ftllt1_6m`,
			`tag_present`,
			`clip_rfc`,
			`clip_present`,
			`loop_lt220m`,
			`seventy_n`,
			`cw_image`,
			`location`,
			`num_of_windows`
		FROM `corded_window`
		WHERE `job_id` = {$prev_job['id']}
	");

    }

    function SnycWaterMeter($job_id,$prev_job_sql2){

        //$prev_job2 = $prev_job_sql2;
        $query1 = $this->CI->db->get_where('jobs',array('id'=> $prev_job_sql2));

        $prev_job = $query1->row_array();

        // get previous job and insert previous water meter to this job
        /*$wm_query = $this->CI->db->get_where('water_meter',array('job_id'=>$prev_job['id']));
        $wm_query_row = $wm_query->row_array();

        $wm_data = array(
            'job_id' => $job_id,
            'location' => $wm_query_row['location'],
            'meter_image' => $wm_query_row['meter_image'],
            'created_date' => $wm_query_row['created_date'],
            'active' => 1
        );
        $this->CI->db->insert('water_meter',$wm_data); */

        $this->CI->db->query("
		INSERT INTO
		`water_meter` (
			`job_id`,
			`location`,
			`meter_image`,
			`created_date`,
			`active`
		)
		SELECT
			'{$job_id}',
			`location`,
			`meter_image`,
			'".date('Y-m-d H:i:s')."',
			'1'
		FROM `water_meter`
		WHERE `job_id` = {$prev_job['id']}
	");

    }

    // get previous job and insert previous corded window to this job
    function SnycWaterEfficiency($job_id, $prev_job_sql2) {

        $today_full_ts = date('Y-m-d H:i:s');

        // get previous job
        $query1 = $this->CI->db->get_where('jobs',array('id'=> $prev_job_sql2));

        $prev_job = $query1->row_array();


        if(  $job_id > 0 && $prev_job['id'] > 0 ){

            $ss_sql2 = "
                INSERT INTO
                `water_efficiency` (
                    `job_id`,
                    `device`,
                    `location`,
                    `note`,
                    `created_date`
                )
                SELECT
                    '{$job_id}',
                    `device`,
                    `location`,
                    `note`,
                    '{$today_full_ts}'
                FROM `water_efficiency`
                WHERE `job_id` = {$prev_job['id']}
            ";
            $this->CI->db->query($ss_sql2);

        }

    }

    function markAsSyncBundle($bundle_id){

        // marked as synced
        $data = array('sync'=>1);
        $this->CI->db->where('bundle_services_id',$bundle_id);
        $this->CI->db->update('bundle_services',$data);

    }

    function markAsSync($job_id,$jserv){

        switch($jserv){
                case 2:
                    $sync_field = '`alarms_synced`';
                break;
                // SA IC
                case 12:
                    $sync_field = '`alarms_synced`';
                break;
                case 5:
                    $sync_field = '`ss_sync`';
                break;
                case 6:
                    $sync_field = '`cw_sync`';
                break;
                case 7:
                    $sync_field = '`wm_sync`';
                break;
                case 15: // WE
                    $sync_field = '`we_sync`';
                break;
            }

        // mark as sync
        $data = array($sync_field => 1);
        $this->CI->db->where('id',$job_id);
        $this->CI->db->update('jobs',$data);

    }
    public function is_safety_squad($agency_id){
        if(empty($agency_id)){
            return false;
        }
        $q= "
        SELECT 
            sac.`sac_id`,
            sac.`company_name`
        FROM `agencies_from_other_company` AS afoc
        LEFT JOIN `smoke_alarms_company` AS sac ON afoc.`company_id` = sac.`sac_id`
        LEFT JOIN `agency` AS a ON afoc.`agency_id` = a.`agency_id`
        WHERE afoc.`agency_id` = {$agency_id}
        AND afoc.`active` = 1
        AND afoc.company_id = 1
        ";
        $qq = $this->CI->db->query($q);
        if($qq->num_rows()>0){
            return true;
        }else{
            return false;
        }

    }

    public function isStaffElectrician($tech_id) {

        $sql_str = "
            SELECT COUNT(`StaffID`)
            FROM `staff_accounts`
            WHERE `StaffID` = {$tech_id}
            AND `is_electrician` = 1
        ";
    
        $sql = $this->CI->db->query($sql_str);
    
        if ( $sql->num_rows()  > 0 ) {
            return true;
        } else {
            return false;
        }
    }

    public function has_property_lockbox($prop_id){
        $this->CI->db->select('id');
        $this->CI->db->from('property_lockbox');
        $this->CI->db->where('property_id', $prop_id);
        $q = $this->CI->db->get();

        if( $q->num_rows() > 0 ){
            return true;
        }else{
            return false;
        }
    }

    public function has_extra_job_notes($job_id){
        $this->CI->db->select('id');
        $this->CI->db->from('extra_job_notes');
        $this->CI->db->where('job_id', $job_id);
        $q = $this->CI->db->get();

        if( $q->num_rows() > 0 ){
            return true;
        }else{
            return false;
        }
    }

    public function get_job_reason($reason_id){
        $this->CI->db->select('*');
        $this->CI->db->from('job_reason');
        $this->CI->db->where('job_reason_id', $reason_id);
        $q = $this->CI->db->get();

       return $q->row_array();
    }

    public function getGoogleMapDistance($orig,$dest){
	
        // init curl object        
        $ch = curl_init();
    
        // api key
        $API_key = $this->CI->config->item('gmap_api_key'); 
        
    
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".rawurlencode($orig)."&destinations=".rawurlencode($dest)."&key={$API_key}";
    
        // define options
        $optArray = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false
        );
    
        // apply those options
        curl_setopt_array($ch, $optArray);
    
        // execute request and get response
        $result = curl_exec($ch);
    
    
        $result_json = json_decode($result);
    
    
        curl_close($ch);
    
        return $result_json;
    
    }

    public function safety_switch_test_result($ss_id){

        if( $ss_id && $ss_id!="" ){
            $this->CI->db->select('test');
            $this->CI->db->from('safety_switch');
            $this->CI->db->where('safety_switch_id', $ss_id);
            $row = $this->CI->db->get()->row()->test;

            switch ($row) {
                case 1:
                    $jt = 'Pass';
                    break;
                case 0:
                    $jt = 'Fail';
                    break;
                case 2:
                    $jt = 'No Power';
                    break;
                case 3:
                    $jt = 'Not Tested';
            }
            return $jt;
        }

    }

    /**
     * @param mixed $preferred_alarm_id
     * 
     * @return [string]
     */
    public function preferred_alarm_name($preferred_alarm_id){
        $preferred_alar_q = $this->CI->db->select('alarm_make')->from('alarm_pwr ')->where('alarm_pwr_id',$preferred_alarm_id)->get()->row_array();
        return $preferred_alar_q['alarm_make'];
    }

    /**
     * @param mixed $val
     * 
     * @return [string]
     */
    public function isNullNotNull($val){
        if(trim($val)!=""){
            return $val;
        }else{
            return 'NULL';
        }
    }
    
    

}
