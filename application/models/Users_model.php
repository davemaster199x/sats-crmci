<?php

class Users_model extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
    }

    public function get_users($params)
    {
        if( isset($params['sel_query']) ){
            $sel_query = $params['sel_query'];
        }else{
            $sel_query = '*';
        }

        $this->db->select($sel_query);
        $this->db->from('`staff_accounts` AS sa');
        $this->db->join('`staff_classes` AS sc', 'sa.`ClassID` = sc.`ClassID`','left');
        $this->db->join('`country_access` AS ca', 'sa.`StaffID` = ca.`staff_accounts_id`', 'inner');

        //optional join tables (added:gherx)
        if ( $params['join_table'] && !empty($params['join_table']) ) {
            foreach ($params['join_table'] as $join_table) {
                if ($join_table == 'cc') {
                    $this->db->join('`staff_accounts` AS cc', 'cc.`StaffID` = sa.`other_call_centre`','left');
                }

                if ($join_table == 'staff_states') {
                    $this->db->join('`staff_states` AS ss', 'ss.`StaffID` = sa.`StaffID`','left');
                    $this->db->join('`states_def` AS sd', 'sd.`StateID` = ss.`StateID`','left');
                }

                if ($join_table == 'accomodation') {
                    $this->db->join('`accomodation` AS acco', 'sa.`accomodation_id` = acco.`accomodation_id`','left');                    
                }

            }
        }

        $this->db->where('ca.country_id', $this->config->item('country'));

        if( is_numeric($params['sa_deleted']) ){
            $this->db->where('sa.`Deleted`', $params['sa_deleted']);
        }

        if( is_numeric($params['user_search']) == 1 ){
            $this->db->like('sa.`FirstName`', $params['keyword']);
            $this->db->or_like('sa.`LastName`', $params['keyword']);
            $this->db->or_like('sa.`Email`', $params['keyword']);
        }

        if( isset($params['sa_active'])  && $params['sa_active'] != ''){
            $this->db->where('sa.`active`', $params['sa_active']);
        }

        if( isset($params['class_filter'])  && $params['class_filter'] != ''){
            $this->db->where('sa.`ClassID`', $params['class_filter']);
        }

        if( isset($params['assigned_cc'])  && $params['assigned_cc'] != ''){
            $this->db->where('sa.`other_call_centre`', $params['assigned_cc']);
        }

        if( $params['search'] && !empty($params['search']) ){
            $search_filter = "CONCAT_WS(' ', LOWER(sa.FirstName), LOWER(sa.LastName))";
            $this->db->like($search_filter, $params['search']);
        }

        if( $params['staff_states'] && !empty($params['staff_states']) ){
            $this->db->where('ss.`StateID`', $params['staff_states']);
        }

        //staff state address
        if( $params['state'] && !empty($params['state']) ){
            $this->db->like('sa.`address`', $params['state']);
        }

        //staff state address
        if( $params['state_acco'] && !empty($params['state_acco']) ){
            $this->db->like('acco.`state`', $params['state']);
        }

        // custom filter
        if (isset($params['custom_where'])) {
            $this->db->where($params['custom_where']);
        }

        // sort
        if( isset($params['sort_list']) ){
            foreach( $params['sort_list'] as $sort_arr ){
                if( $sort_arr['order_by']!="" && $sort_arr['sort']!='' ){
                    $this->db->order_by($sort_arr['order_by'], $sort_arr['sort']);
                }
            }
        }

        // limit
        if( isset($params['limit']) && $params['limit'] > 0 ){
            $this->db->limit( $params['limit'], $params['offset']);
        }


        $query = $this->db->get();

        return $query;

    }


    public function get_user($param){
        $query = $this->db
            ->select('CONCAT(staff_accounts.FirstName, " ", staff_accounts.LastName) AS name')
            ->from('staff_accounts')
            ->where('StaffID', $param)
            ->limit(1)
            ->get();

        if ($query->num_rows() > 0) {
            return $query->row(); 
        }
    }


    /**
     * Get Incident Report
     * $params params array
     * return query
     */
    public function getIncidentAndReport($params){


        if( $params['sel_query'] && $params['sel_query']!="" ){
            $sel_query = $params['sel_query'];
        }else{
            $sel_query = '*';
        }

        $this->db->select($sel_query);
        $this->db->from('incident_and_injury AS iai');
        $this->db->join('staff_accounts AS sa','sa.StaffID = iai.reported_to','left');
        $this->db->join('staff_accounts AS sa2','sa2.StaffID = iai.created_by','left');
        $this->db->where('iai.deleted',0);


        //iai_id filter
        if($params['iai_id'] && $params['iai_id']!=""){
            $this->db->where('iai.incident_and_injury_id', $params['iai_id']);
        }

        //staff active/inactive filter
        if($params['staff_active']!=""){
            $this->db->where('sa.active', $params['staff_active']);
        }


         // sort
         if( isset($params['sort_list']) ){
            foreach( $params['sort_list'] as $sort_arr ){
                if( $sort_arr['order_by']!="" && $sort_arr['sort']!='' ){
                    $this->db->order_by($sort_arr['order_by'], $sort_arr['sort']);
                }
            }
        }

        //date from and to filter
        if( $params['from']!='' && $params['to']!='' ){
            $where_date = " CAST(iai.`datetime_of_incident` AS DATE) BETWEEN '{$params['from']}' AND '{$params['to']}' ";
            $this->db->where($where_date);
        }

         // limit
         if( isset($params['limit']) && $params['limit'] > 0 ){
            $this->db->limit( $params['limit'], $params['offset']);
        }


        $query = $this->db->get();
        return $query;


    }

    /**
     * get Nature of Incident
     */
	public function getNatureOfIncident($nature_of_incident){
		switch($nature_of_incident){
			case 1:
				$nature_of_incident2 = 'Near Miss';
			break;
			case 2:
				$nature_of_incident2 = 'First Aid';
			break;
			case 3:
				$nature_of_incident2 = 'Medical Treatment';
			break;
			case 4:
				$nature_of_incident2 = 'Car accident';
			break;
			case 5:
				$nature_of_incident2 = 'Property damage';
			break;
			case 6:
				$nature_of_incident2 = 'Incident report';
			break;
            case 7:
				$nature_of_incident2 = 'Theft';
			break;
            case 8:
				$nature_of_incident2 = 'Other';
			break;
		}
		return $nature_of_incident2;
    }


    /**
     * Insert incident_and_injury
     * return last insert id
     */
    public function insert_incident_and_injury($data){

        $this->db->insert('incident_and_injury',$data);
        $this->db->limit(1);
        return $this->db->insert_id();

    }


    /**
     * Incident photo upload
     */
	function uploadIncidentReportUpload($file){

		// upload
		if($file){


			$country_folder = "/".strtolower($_SESSION['country_iso']);
			$image_name = "incident".rand().'_'.date('YmdHis');

			$folder = "images/incident{$country_folder}";


			// if folder does not exist, make one
			if(!is_dir($folder)){
				mkdir($folder);
			}

			// IMAGE 1
			$handle = new upload($file);
			if ($handle->uploaded) {

			  $handle->file_new_name_body   = $image_name;
			  $handle->image_resize         = true;
			  $handle->image_x              = 760;
			  $handle->image_ratio_y        = true;
			  $handle->process($_SERVER['DOCUMENT_ROOT'].$folder);
			  if ($handle->processed) {
				// get file extension
				$fn = explode("/",$file['type']);
				$file_ext = ($fn[1]=='jpeg')?'jpg':$fn[1];
				$db_ret['photo_of_incident'] = "{$folder}/{$image_name}.{$file_ext}";
				$handle->clean();
			  } else {
				$error = 'error : ' . $handle->error;
			  }

			}

			$db_ret['error'] = $error;

			return $db_ret;

		}

    }

    public function upload_photo_data($data){
        $this->db->insert('incident_photos',$data);
        if($this->db->affected_rows()>1){
            return true;
        }else{
            return false;
        }
    }

    public function delete_incident_photo($incident_and_injury_id,$incident_photos_id){

        $this->db->where('incident_and_injury_id',$incident_and_injury_id);
        $this->db->where('incident_photos_id',$incident_photos_id);
        $this->db->delete('incident_photos');
        if($this->db->affected_rows()>0){
            return true;
        }else{
            return false;
        }

    }

    public function update_incident_and_injury($incident_and_injury_id, $data){

        if($incident_and_injury_id){

            $this->db->where('incident_and_injury_id', $incident_and_injury_id);
            $this->db->update('incident_and_injury', $data);
            if($this->db->affected_rows()>0){
                return true;
            }else{
                return false;
            }

        }


    }


    public function getLeave($params){

        if( isset($params['sel_query']) ){
            $sel_query = $params['sel_query'];
        }else{
            $sel_query = '*';
        }

        $this->db->select($sel_query);
        $this->db->from('leave as l');
        $this->db->join('staff_accounts as sa_emp','sa_emp.StaffID = l.employee','left');
        $this->db->join('staff_accounts as sa_lm','sa_lm.StaffID = l.line_manager','left');
        $this->db->join('staff_accounts as lma','lma.StaffID = l.line_manager_app_by','left');
        $this->db->join('staff_accounts as hra','hra.StaffID = l.hr_app_by','left');
        $this->db->join('staff_accounts as atc','atc.StaffID = l.added_to_cal_by','left');
        $this->db->join('staff_accounts as sn','sn.StaffID = l.staff_notified_by','left');
        $this->db->where('l.active',1);
        $this->db->where('l.deleted',0);
        $this->db->where('l.leave_id >',0);


        //FILTERS -----
        //country id filter
        if($params['country_id'] && $params['country_id']!=""){
            $this->db->where('l.country_id',$params['country_id']);
        }

        //leave id filter
        if($params['leave_id'] && $params['leave_id']!=""){
            $this->db->where('l.leave_id', $params['leave_id']);
        }

        //approval filter
        if($params['needs_approval'] && $params['needs_approval']==1){
            $needs_approval_where = "( l.`hr_app` IS NULL OR l.`line_manager_app` IS NULL )";
            $this->db->where($needs_approval_where);
        }

        //employer id filter
        if($params['emp_id']!=""){
            $this->db->where('sa_emp.StaffID', $params['emp_id']);
        }

        if($params['lm_id']!=""){
            $this->db->where('sa_lm.StaffID', $params['lm_id']);
        }

        if($params['l_status']!=""){
            if($params['l_status'] != 'All'){
                $this->db->where('l.status', $params['l_status']);
            }
		}

        // custom filter
        if( isset($params['custom_where']) ){
            $this->db->where($params['custom_where']);
        }

        // custom filter array
        if( isset($params['custom_where_arr']) ){
            foreach( $params['custom_where_arr'] as $index => $custom_where ){
                if( $custom_where != '' ){
                    $this->db->where($custom_where);
                }
            }
        }

        // group by
        if( isset($params['group_by']) && $params['group_by'] != '' ){
            $this->db->group_by($params['group_by']);
        }

        // sort
        if (isset($params['sort_list'])) {
            foreach ($params['sort_list'] as $sort_arr) {
                if ($sort_arr['order_by'] != "" && $sort_arr['sort'] != '') {
                    $this->db->order_by($sort_arr['order_by'], $sort_arr['sort']);
                }
            }
        }

        // limit
		if( isset($params['limit']) && $params['limit'] > 0 ){
			$this->db->limit( $params['limit'], $params['offset']);
        }

        $query = $this->db->get();

		if( isset($params['display_query']) && $params['display_query'] == 1 ){
			echo $this->db->last_query();
		}

		return $query;

    }


    public function delete_leave($leave_id,$data){
        $this->db->where('leave_id', $leave_id);
        $this->db->update('leave',$data);
        if($this->db->affected_rows()>0){
            return true;
        }else{
            return false;
        }
    }

    public function insert_leave($data){
        $this->db->insert('leave', $data);
        $this->db->limit(1);
        return $this->db->insert_id();
    }

    public function edit_leave_details($data, $leave_id){
        $this->db->where('leave_id', $leave_id);
        $this->db->update('leave', $data);
        $this->db->limit(1);
        if($this->db->affected_rows()>0){
            return true;
        }else{
            return false;
        }
    }


    public function encryptWithOldCRM($pass) {

        $curl = curl_init();

        $postData = json_encode(['pass' => $pass]);
        // HTTP headers
        $http_header = [
            "Content-Type:application/json",
            "Content-Length:".strlen($postData),
            "Accept:application/json",
        ];

        $endpoint = "{$this->config->item('crm_link')}/ajax_encrypt.php";

        // curl options
        $curl_opt = [
            CURLOPT_URL => $endpoint,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $http_header,
            CURLOPT_POSTFIELDS => $postData,
        ];

        curl_setopt_array($curl, $curl_opt);

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response, true);
    }

    public function decryptWithOldCRM($pass) {

        $curl = curl_init();

        $postData = json_encode(['pass' => $pass]);
        // HTTP headers
        $http_header = [
            "Content-Type:application/json",
            "Content-Length:".strlen($postData),
            "Accept:application/json",
        ];

        $endpoint = "{$this->config->item('crm_link')}/ajax_decrypt.php";

        // curl options
        $curl_opt = [
            CURLOPT_URL => $endpoint,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $http_header,
            CURLOPT_POSTFIELDS => $postData,
        ];

        curl_setopt_array($curl, $curl_opt);

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response, true);
    }

    /**
     * check if check_home_content_block_users_block filter user_id has data
     * return num_rows
     */
    public function check_home_content_block_users_block($user_id){

        $this->db->select('id');
        $this->db->from('home_content_block_users_block');
        $this->db->where('user_id', $user_id);
        $q = $this->db->get();

        return $q->num_rows();

    }

    /**
     * Get count from main_page_total
     */
    public function get_main_count($name){
        $this->db->select('*');
        $this->db->from('main_page_total');
        $this->db->where('name', $name);
        $q = $this->db->get();
        if( $q->num_rows()>0 ){
            echo number_format($q->row()->total);
        }else{
            echo '0';
        }
    }

    /**
     * Get goal from main_page_total
     */
    public function get_main_goal($name){
        $this->db->select('*');
        $this->db->from('main_page_total');
        $this->db->where('name', $name);
        $q = $this->db->get();
        if( $q->num_rows()>0 ){
            echo number_format($q->row()->total_goal);
        }else{
            echo '0';
        }
    }

    public function getLeaveType( $leave_type_id = null ) {

        if( $leave_type_id > 0 ){

            $param_obj = (object) [
                'leave_type_id' => $leave_type_id
            ];
            $leave_types_sql = $this->system_model->get_leave_types($param_obj);
            $leave_types_row = $leave_types_sql->row();

            return $leave_types_row->leave_name;

        }

    }

    public function get_main_staff($name){
        $get_main_staff = $this->db->query("
            SELECT
                mpt.*,
                CONCAT(sa.FirstName, ' ', LEFT(sa.LastName, 1), '.') AS FullName
            FROM
                main_page_total AS mpt
            INNER JOIN
                staff_accounts AS sa ON mpt.staff_id = sa.StaffID
            WHERE mpt.name = '$name'
        ");
        $row_get_main_staff = $get_main_staff->row();
        $show_FullName = "<div class='ribbon left-top' style='background-color: #343434;'><span>$row_get_main_staff->FullName</span></div>";
        return ($row_get_main_staff) ? $show_FullName : '';
        
    }

    public function get_no_active_job()
    {
        $plus_two_month = date("Y-m-d",strtotime("+2 month"));	
        $between_from = date("Y-m", strtotime($plus_two_month.'-1 year'));
        $between_to = date("Y-m", strtotime("+2 month"));

        // $sql = "
        //     SELECT p.`property_id`, p.`address_1`, p.`address_2`, p.`address_3`, a.`agency_id`, a.`agency_name`, 
        //         p. `created`, j.service as j_service, j.job_type as j_type, ajt.type as ajt_type, aght.priority, pa.`hidden`
        //         FROM `property` AS p
        //         INNER JOIN jobs AS j ON p.property_id = j.property_id
        //         INNER JOIN property_services AS ps ON ( j.`service` = ps.`alarm_job_type_id` AND j.`property_id` = ps.`property_id` AND ps.service =1 )
        //         LEFT JOIN `intentionally_hidden_active_properties` AS pa ON pa.`property_id` = p.`property_id` 
        //         LEFT JOIN `agency` AS a ON p.`agency_id` = a.`agency_id`
        //         LEFT JOIN `agency_priority` AS aght ON a.`agency_id` = aght.`agency_id`
        //         LEFT JOIN `alarm_job_type` AS ajt ON j.`service` = ajt.`id`
        //         WHERE ( j.`status` =  'Cancelled' OR j.`status` =  'Completed')
        //         AND j.job_type != 'Once-off'  
        //         AND NOT YEAR(j.date) BETWEEN '" . date('Y', strtotime('-1 year')) . "' AND '" . date('Y') . "'
        //         AND YEAR(j.date) != '0'
        //         AND p.`deleted` =0
        //         AND a.`status` = 'active'
        //         AND j.`del_job` =0
        //         AND j.`status` = 'Completed'
        //         AND a.franchise_groups_id != 14
        //         AND a.`country_id` = {$this->config->item('country')}
        //         AND (
        //         p.`is_nlm` =0
        //         OR p.`is_nlm` IS NULL
        //                     )
        //         AND p.property_id NOT IN (
        //                 SELECT  DISTINCT(p.`property_id`)
        //                 FROM `jobs` AS j1
        //                 LEFT JOIN `property` AS p ON j1.`property_id` = p.`property_id`
        //                 LEFT JOIN `agency` AS a ON p.`agency_id` = a.`agency_id`
        //                 WHERE p.`deleted` =0
        //                 AND a.`status` = 'active'
        //                 AND j1.`del_job` = 0
        //                 AND j1.`status` =  'Completed'
        //                 AND a.`country_id` = {$this->config->item('country')}
        //                 AND ( p.`is_nlm` = 0 OR p.`is_nlm` IS NULL )
        //                 AND (
        //                 p.`is_nlm` =0
        //                 OR p.`is_nlm` IS NULL
        //                 )
        //                 AND YEAR( j1.`date` )
        //                 BETWEEN '" . date('Y', strtotime('-1 year')) . "' AND '" . date('Y') . "'
        //             )
        //         AND p.property_id NOT IN (
        //             SELECT DISTINCT(p2.`property_id`)
        //             FROM  `jobs` AS j2
        //             LEFT JOIN  `property` AS p2 ON j2.`property_id` = p2.`property_id` 
        //             LEFT JOIN  `agency` AS a2 ON p2.`agency_id` = a2.`agency_id` 
        //             WHERE p2.`deleted` =0
        //             AND a2.`status` =  'active'
        //             AND j2.`del_job` =0
        //             AND j2.`status` !=  'Cancelled'
        //             AND j2.`status` !=  'Completed'
        //             AND a2.`country_id` ={$this->config->item('country')}
        //         )
        //         AND pa.`hidden` IS  NULL OR pa.`hidden` = 0
        //         GROUP BY p.property_id
        //         ORDER BY p.property_id
        // ";

        $sql = "
            SELECT p.`property_id`, p.`address_1`, p.`address_2`, p.`address_3`, a.`agency_id`, a.`agency_name`, p.`created`, j.service as j_service, j.job_type as j_type, ajt.type as ajt_type, aght.priority, pa.`hidden`
            FROM `property` AS p
            INNER JOIN jobs AS j ON p.property_id = j.property_id
            INNER JOIN property_services AS ps ON ( j.`service` = ps.`alarm_job_type_id` AND j.`property_id` = ps.`property_id` AND ps.service =1 )
            LEFT JOIN `intentionally_hidden_active_properties` AS pa ON pa.`property_id` = p.`property_id` 
            LEFT JOIN `agency` AS a ON p.`agency_id` = a.`agency_id`
            LEFT JOIN `agency_priority` AS aght ON a.`agency_id` = aght.`agency_id`
            LEFT JOIN `alarm_job_type` AS ajt ON j.`service` = ajt.`id`
            WHERE ( j.`status` =  'Cancelled' OR j.`status` =  'Completed')
            AND j.job_type != 'Once-off'  
            AND NOT DATE_FORMAT(j.date,'%Y-%m') BETWEEN '" . $between_from . "' AND '" . $between_to . "'
            AND YEAR(j.date) != '0'
            AND p.`deleted` =0
            AND a.`status` = 'active'
            AND j.`del_job` =0
            AND j.`status` = 'Completed'
            AND a.franchise_groups_id != 14
            AND a.`country_id` = {$this->config->item('country')}
			AND ( p.`is_nlm` = 0 OR p.`is_nlm` IS NULL )
            AND p.property_id NOT IN (
                    SELECT  DISTINCT(p.`property_id`)
                    FROM `jobs` AS j1
                    LEFT JOIN `property` AS p ON j1.`property_id` = p.`property_id`
                    LEFT JOIN `agency` AS a ON p.`agency_id` = a.`agency_id`
                    WHERE p.`deleted` =0
                    AND a.`status` = 'active'
                    AND j1.`del_job` = 0
                    AND j1.`status` =  'Completed'
                    AND a.`country_id` = {$this->config->item('country')}
					AND ( p.`is_nlm` = 0 OR p.`is_nlm` IS NULL )
					AND DATE_FORMAT( j1.`date`,'%Y-%m' ) BETWEEN '" . $between_from . "' AND '" . $between_to . "'
                )
            AND p.property_id NOT IN (
                SELECT DISTINCT(p2.`property_id`)
                FROM  `jobs` AS j2
                LEFT JOIN  `property` AS p2 ON j2.`property_id` = p2.`property_id` 
                LEFT JOIN  `agency` AS a2 ON p2.`agency_id` = a2.`agency_id` 
                WHERE p2.`deleted` =0
                AND a2.`status` =  'active'
                AND j2.`del_job` =0
                AND j2.`status` !=  'Cancelled'
                AND j2.`status` !=  'Completed'
                AND a2.`country_id` ={$this->config->item('country')}
            )
            AND (pa.`hidden` IS  NULL OR pa.`hidden` = 0)
            GROUP BY p.property_id
            ORDER BY p.property_id
            ";

        $query = $this->db->query($sql);
        if( $query->num_rows()>0 ){
            echo number_format($query->num_rows());
        }else{
            echo '0';
        }
    }


}
