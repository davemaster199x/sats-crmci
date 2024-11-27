<?php
/**
 * @property staff_accounts_model $staff_accounts_model
 * @property calendar_model $calendar_model
 */

class Calendar extends MY_Controller {



    public function __construct(){
        parent::__construct();
        $this->load->database();
        $this->load->model([
            'jobs_model',
            'staff_accounts_model',
            'staff_classes_model',
            'calendar_model',
            'tech_model',
            'users_model',
        ]);
    }
    
    public function index(){

    }


    /**
     * Calendar page
     * Any changes to this stuff must also applied to tech version (view_individual_staff_calendar_tech)
     */
    public function my_calendar_admin(){
       


        $taff_params = array(
            'sel_query' => "sa.FirstName, sa.LastName",
            'staff_id' => $this->session->staff_id
        );
        $staff_name_query = $this->gherxlib->getStaffInfo($taff_params)->row_array();
        $staff_name = "{$staff_name_query['FirstName']}";

        $data['title'] = "{$staff_name}'s Calendar";


        $this->load->view('templates/inner_header', $data);
        $this->load->view('calendar/view_individual_staff_calendar', $data);
        $this->load->view('templates/inner_footer', $data);

    }


    /**
     * Tech Calendar (ajax request)
     * Json
     */
    public function json_tech_calendar(){
     
        $params = array(
            'sel_query' => "c.calendar_id, c.staff_id, c.region, c.date_start, c.date_finish, c.date_start_time, c.date_finish_time, c.booking_target, c.details, c.accomodation,c.marked_as_leave, s.FirstName, s.LastName, s.ClassID, acco.accomodation_id, acco.name as acco_name, acco.area as acco_area, acco.address as acco_address, acco.phone as acco_phone",
            'StaffID' => $this->session->staff_id,
            'sort_list' => array(
                array(
                    'order_by' => 'c.date_start',
                    'sort' => 'DESC'
                )
            )
            
        );
        $cal_query = $this->calendar_model->get_tech_calendar($params);



        if(!empty($cal_query)){
			foreach($cal_query->result() as $row){
				
                $color =  ($row->marked_as_leave==1)?'event-red':'event-blue';
                
                if($row->accomodation=='0'){ // Required
                    $icon = "home icon_required";
                }else if($row->accomodation == 2){ //Pending
                    $icon = "home icon_pending";
                }else if($row->accomodation == 1){ // Booked
                    $icon = "home icon_booked";
                }else if($row->accomodation===NULL){
                    $icon = "";
                }

				
				$data[] = array(
                    'id' => $row->calendar_id,
                    'staff_id' => $row->staff_id,
                    'start' => $row->date_start,
                    'end' => $row->date_finish."T23:59:00",
                    'details' => $row->details,
					'title' => $row->region,
                    'className' => $color,
                    'address' => $row->acco_address,
                    'ClassID' => $row->ClassID,
                    'accomodation' => $row->accomodation,
                    'accomodation_name' => $row->acco_name,
                    'acco_phone' => $row->acco_phone,
                    'cal_url' => "/calendar/add_calendar_entry_static?id={$row->calendar_id}",
                    'icon' => $icon,
                    'start_time' =>  $row->date_start_time,
                    'end_time' => $row->date_finish_time
				);
			}
		}
		
        echo json_encode($data);


    }


    /**
     * View tech schedule page
     * Any changes to this stuff must also applied to tech version (view_tech_schedule_tech)
     */
    public function monthly_schedule_admin(){

        if(!$this->uri->segment(3) || !is_numeric($this->uri->segment(3))){
            redirect('/home/index_tech','refresh');
        }



        $tech_id = $this->uri->segment(3);

        $taff_params = array(
            'sel_query' => "sa.FirstName, sa.LastName",
            'staff_id' => $tech_id
        );
        $staff_name_query = $this->gherxlib->getStaffInfo($taff_params)->row_array();
        $staff_name = "{$staff_name_query['FirstName']}";
        $data['title'] = trim($staff_name)."'s Schedule";


        $data['day'] = date("d");
        $data['month'] = date("m");
        $data['year'] = date("y");


        $data['tech_id'] = $this->uri->segment(3);
       
        $usemonth = ($this->input->get_post('month')!="")?$this->input->get_post('month'):date('m');
        $data['usemonth'] = ($this->input->get_post('month')!="")?$this->input->get_post('month'):date('m');
        $useyear = ($this->input->get_post('year')!="")?$this->input->get_post('year'):date('Y');
        $data['useyear'] = ($this->input->get_post('year')!="")?$this->input->get_post('year'):date('Y');

        
        if($usemonth && $useyear){
            $data['current_month'] = date('F Y', strtotime("{$useyear}-{$usemonth}-01"));
        }else{
            $data['current_month'] = date('F Y');
        }

        // do the stuff for nextyear.
        if ($usemonth == 12)
        {
            $data['nextmonth'] = 1;
            $data['nextyear'] = $useyear+1;
        }else
        {
            $data['nextmonth'] = $usemonth+1;
            $data['nextyear'] = $useyear;
        }

        // do the stuff for prevyear.
        if ($usemonth == 1)
            {
            $data['prevmonth'] = 12;
            $data['prevyear'] = $useyear-1;
            }
        else
            {
            $data['prevmonth'] = $usemonth-1;
            $data['prevyear'] = $useyear;

        }

        //get days in month
        //$data['days_in_month'] = cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));
        $data['days_in_month'] = cal_days_in_month(CAL_GREGORIAN,$usemonth,$useyear);

        //get staff classID
        $params = array(
            'sel_query' => 'sa.ClassID',
            'staff_id' => $this->session->staff_id
        );
        $data['staff'] = $this->gherxlib->getStaffInfo($params)->row()->ClassID;

        // get all services for legend
        $data['serv_type_sql'] = $this->db->query("
            SELECT `id`, `type`
            FROM `alarm_job_type`
            WHERE `active` = 1
        ");

       

        $this->load->view('templates/inner_header', $data);
        $this->load->view('calendar/view_tech_schedule', $data);
        $this->load->view('templates/inner_footer', $data);

    }


    public function add_calendar_entry_static(){

        $data['cal_id'] = $this->input->get_post('id'); //calendar id
        $cal_staff_id = $this->input->get_post('staff_id');
        $cal_startdate = $this->input->get_post('startdate');
        $data['add'] = $this->input->get_post('add');

        if( (isset( $data['cal_id']) && !empty($data['cal_id'])) && !$cal_staff_id ){ //has/set call_id but no staff_id > edit individual calendar entry

            //GET CALENDAR ITEM BY ID
            $params = array(
                'sel_query' => "c.calendar_id, c.staff_id, c.region, c.date_start, c.date_start_time, c.date_finish_time, c.booking_staff, c.date_finish, c.booking_target, c.details, c.accomodation,c.marked_as_leave, s.FirstName, s.LastName, s.ClassID, acco.accomodation_id, acco.name as acco_name, acco.area as acco_area, acco.address as acco_address, acco.phone as acco_phone",
                'StaffID'=> $this->session->staff_id,
                'calendar_id' => $data['cal_id']
            );
            $data['row_cal'] = $this->calendar_model->get_tech_calendar($params)->row_array();

            $start_d = $data['row_cal']['date_start'];
            $finish_d = $data['row_cal']['date_finish'];
            $start_time = $data['row_cal']['date_start_time'];
            $finish_time = $data['row_cal']['date_finish_time'];

        }else if( $data['cal_id'] && $cal_staff_id ){ // cal id and staff id is set > edit popup from view_tech_calendar page

             //GET CALENDAR ITEM BY ID
             $params = array(
                'sel_query' => "c.calendar_id, c.staff_id, c.region, c.date_start, c.date_start_time, c.date_finish_time, c.booking_staff, c.date_finish, c.booking_target, c.details, c.accomodation,c.marked_as_leave, s.FirstName, s.LastName, s.ClassID, acco.accomodation_id, acco.name as acco_name, acco.area as acco_area, acco.address as acco_address, acco.phone as acco_phone",
                'StaffID'=> $cal_staff_id,
                'calendar_id' => $data['cal_id']
            );
            $data['row_cal'] = $this->calendar_model->get_tech_calendar($params)->row_array();

            $start_d = $data['row_cal']['date_start'];
            $finish_d = $data['row_cal']['date_finish'];
            $start_time = $data['row_cal']['date_start_time'];
            $finish_time = $data['row_cal']['date_finish_time'];


        }else if($cal_staff_id && !empty($cal_staff_id)){ //has staff_id > add new calendar entry with staff_id is set

            $data['row_cal']['staff_id'] = $cal_staff_id;

            $start_d = $cal_startdate;
            $finish_d = $cal_startdate;
            $start_time = "09:00";
            $finish_time = "17:00";

        }else if(!$data['cal_id'] && !$cal_staff_id ){ // callendar id and staff id url param is not set so use staff id instead
            
            $data['row_cal']['staff_id'] = $this->session->staff_id;

        }

        
            $data['start_date_data'] = ($start_d!="")?date('d/m/Y H:i', strtotime(str_replace("/","-",$start_d." ".$start_time))):date('d/m/Y 09:00');
            $data['finish_date_data'] = ($finish_d!="")?date('d/m/Y H:i', strtotime(str_replace("/","-",$finish_d." ".$finish_time))):date('d/m/Y 17:00');
            


            //GET STAFF fo dropdown
            $staff_params = array(
                'sel_query' => "sa.StaffID, sa.FirstName, sa.LastName",
                'sort_list' => array(
                    array(
                        'order_by'=> 'sa.FirstName',
                        'sort' => 'ASC'
                    )
                )
            );
            $data['staff_list'] = $this->gherxlib->getStaffInfo($staff_params);

        if ($this->input->get_post('leave_id') != '0') { //from the leave_requests.
            $this->load->model('users_model');
            $id = $this->input->get_post('leave_id');
            $country_id = $this->config->item('country');
            if($id && $id!=""){

                //staff dropdown
                $staffparams = array(
                    'sel_query' => 'sa.StaffID, sa.FirstName, sa.LastName',
                    'sort_list' => array(
                        array(
                            'order_by' => 'sa.FirstName',
                            'sort' => 'ASC'
                        )
                    )
                );
                $data['staff']  = $this->gherxlib->getStaffInfo($staffparams);

                //get leave request by id
                $sel_query = "
                l.leave_id,
                l.`date`,
                l.lday_of_work,
                l.fday_back,
                l.reason_for_leave,
                l.hr_app,
                l.hr_app_timestamp,
                l.line_manager_app,
                l.line_manager_app_timestamp,
                l.added_to_cal,
                l.added_to_cal_timestamp,
                l.staff_notified,
                l.staff_notified_timestamp,
                l.type_of_leave,
                l.num_of_days,
                l.status,
                l.backup_leave,
                l.comments,

                sa_emp.`StaffID` AS emp_staff_id,
                sa_emp.`FirstName` AS emp_fname,
                sa_emp.`LastName` AS emp_lname,
                sa_emp.`Email` AS emp_email,

                sa_lm.`StaffID` AS sa_lm_staff_id,
                sa_lm.`FirstName` AS lm_fname,
                sa_lm.`LastName` AS lm_lname,
                sa_lm.`Email` AS lm_email,

                lma.`FirstName` AS lma_fname,
                lma.`LastName` AS lma_lname,
                hra.`FirstName` AS hra_fname,
                hra.`LastName` AS hra_lname,
                atc.`FirstName` AS atc_fname,
                atc.`LastName` AS atc_lname,
                sn.`FirstName` AS sn_fname,
                sn.`LastName` AS sn_lname
                ";
                $params = array(
                    'sel_query' => $sel_query,
                    'country_id' => $country_id,
                    'leave_id' => $id
                );
                $data['row_leave'] = $this->users_model->getLeave($params)->row_array();
                $data['leave_id'] = $id;

                $type_of_leave = $data['row_leave']['type_of_leave'];

                $data['type_of_leave'] = $this->db->select('`leave_name`')
								->from('leave_types')
								->where('leave_type_id', $type_of_leave)
								->get()->result();
            }
        }

        $this->load->view('calendar/add_calendar_entry_static', $data);
    
    }


    
    public function add_new_entry(){


        $data['title'] = "Add New Calendar Entry";


        //get staff by staff_id
        $staff_params = array(
            'sel_query' => 'sa.ClassID, sa.StaffID, sa.FirstName, sa.LastName',
            'staff_id' => $this->session->staff_id
        );
        $staff = $this->gherxlib->getStaffInfo($staff_params)->row_array();
        $data['staff'] = $this->gherxlib->getStaffInfo($staff_params)->row_array(); //pass


        //redirect if tech
        if($staff['ClassID']==6){
            redirect('/home/index_tech','refresh');
        }


        $this->load->view('templates/inner_header', $data);
        $this->load->view('calendar/add_new_entry', $data);
        $this->load->view('templates/inner_footer', $data);
        

    }


    public function add_calendar_entry_static_process_ajax(){

        $json_data['status'] = false;
      
        $type = $this->input->post('type');
        $cal_id = $this->input->post('cal_id');
        $staff_id = $this->input->post('staff_id');
        $start_date = date('Y-m-d', strtotime(str_replace('/','-', $this->input->post('start_date'))));
        $finish_date = date('Y-m-d', strtotime(str_replace('/','-', $this->input->post('finish_date'))));
        $leave_type = $this->input->post('leave_type');
        $marked_as_leave = ($this->input->post('marked_as_leave')=='true')?1:0;
        $booking_staff = ($this->input->post('booking_staff')!="")?$this->input->post('booking_staff'):NULL;
        $details = $this->input->post('details');
        $accomodation = ($this->input->post('accomodation')!="")?$this->input->post('accomodation'):NULL;
        $accomodation_id = ($accomodation==1 || $accomodation ==2)?$this->input->post('accomodation_id'):'';
        $send_ical = ($this->input->post('send_ical')=='true')?1:0;
        

        $time_start = date('H:i', strtotime(str_replace('/','-', $this->input->post('start_date'))));
        $time_finish = date('H:i', strtotime(str_replace('/','-', $this->input->post('finish_date'))));

        if($cal_id && !empty($cal_id) && $type=='update'){ //DO UPDATE PROCESS HERE....

            $update_data = array(
                'staff_id' => $staff_id,
                'date_start' => $start_date,
                'date_finish' => $finish_date,
                'date_start_time' => $time_start,
                'date_finish_time' => $time_finish,
                'region' => $leave_type,
                'booking_staff' => $booking_staff,
                'marked_as_leave' => $marked_as_leave,
                'details' => $details,
                'accomodation' => $accomodation,
                'accomodation_id' => $accomodation_id
            );

            $this->db->where('calendar_id', $cal_id );
            $this->db->update('calendar', $update_data);
            $this->db->limit(1);

            if($this->db->affected_rows() >= 0){
                $json_data['status'] = true;
            }
            

        }
        


        if(!$cal_id && $type=='add'){ //ADD EVENT HERE... AND SEND ICAL IF CHECKED

            if(is_array($staff_id)){

                foreach($staff_id as $staff){

                    $add_data = array(
                        'staff_id' => $staff,
                        'date_start' =>  $start_date,
                        'date_finish' => $finish_date,
                        'region' => $leave_type,
                        'marked_as_leave' => $marked_as_leave,
                        'booking_staff' => $booking_staff,
                        'details' => $details,
                        'accomodation' => $accomodation,
                        'accomodation_id' => $accomodation_id,
                        'country_id' => $this->config->item('country'),
                        'date_start_time' => $time_start,
                        'date_finish_time' => $time_finish
                    );
                    $this->db->insert('calendar', $add_data);
                    $this->db->limit(1);
        
                    $insert_id = $this->db->insert_id(); //last insert id
        
                    if($this->db->affected_rows()>0){
        
                        if($send_ical==1){ //if send ical is checed > send ical
        
                            $cal_start_date = date('Y-m-d H:i:s', strtotime(str_replace('/','-', $this->input->post('start_date'))));
                            $cal_finish_date = date('Y-m-d H:i:s', strtotime(str_replace('/','-', $this->input->post('finish_date'))));
        
                            $sa_sql_params = array(
                                'sel_query' => "sa.FirstName, sa.LastName, sa.Email",
                                'staff_id' => $staff
                            );
                            $sa_sql = $this->gherxlib->getStaffInfo($sa_sql_params);
                            $sa = $sa_sql->row_array();
                            $subject = 'iCalendar';
                            $from_email = make_email('info');
                            $to_name = "{$sa['FirstName']} {$sa['LastName']}";
                            $to_email = $sa['Email'];
                            //$to_email = 'itsmegherx@gmail.com';
        
                            //get tech calendar info
                            $cal_query_params = array(
                                'sel_query' => "c.calendar_id, c.staff_id, c.region, c.date_start, c.date_finish, c.booking_target, c.details, c.accomodation,c.marked_as_leave, s.FirstName, s.LastName, s.ClassID, acco.accomodation_id, acco.name as acco_name, acco.area as acco_area, acco.address as acco_address, acco.phone as acco_phone",
                                'calendar_id' => $insert_id,
                                'StaffID' => $this->session->staff_id
                            );
                            $cal_query = $this->calendar_model->get_tech_calendar($cal_query_params)->row_array();
                            if($cal_query['accomodation']===NULL || $cal_query['accomodation']=='0'){
                                $email_details = "{$cal_query['details']}";
                            }else{
                                $email_details = "{$cal_query['acco_name']} | {$cal_query['acco_address']} | {$cal_query['acco_phone']} | {$cal_query['details']}";
                            }
                            //get tech calendar info end
                            
                            $this->calendar_model->send_ical_to_mail($subject,$from_email, $to_name, $to_email, $leave_type, $email_details, $cal_start_date, $cal_finish_date);
                        
                        }
        
                       
                    }

                }

                

            }

            $json_data['status'] = true;

        }


        echo json_encode($json_data);


    }


    /**
     * For Tech
     */
    public function monthly_schedule(){

        if(!$this->uri->segment(3) || !is_numeric($this->uri->segment(3))){
            redirect('/home/index_tech','refresh');
        }

        //

        $uri = '/calendar/monthly_schedule';

        $tech_id = $this->uri->segment(3);
        $staff_class_id = $this->system_model->getStaffClassID();

        $taff_params = array(
            'sel_query' => "sa.FirstName, sa.LastName",
            'staff_id' => $tech_id
        );
        $staff_name_query = $this->gherxlib->getStaffInfo($taff_params)->row_array();
        $staff_name = "{$staff_name_query['FirstName']}";
        $data['title'] = trim($staff_name)."'s Schedule";


        $data['day'] = date("d");
        $data['month'] = date("m");
        $data['year'] = date("y");


        $data['tech_id'] = $this->uri->segment(3);
       
        $usemonth = ($this->input->get_post('month')!="")?$this->input->get_post('month'):date('m');
        $data['usemonth'] = ($this->input->get_post('month')!="")?$this->input->get_post('month'):date('m');
        $useyear = ($this->input->get_post('year')!="")?$this->input->get_post('year'):date('Y');
        $data['useyear'] = ($this->input->get_post('year')!="")?$this->input->get_post('year'):date('Y');

        
        if($usemonth && $useyear){
            $data['current_month'] = date('F Y', strtotime("{$useyear}-{$usemonth}-01"));
        }else{
            $data['current_month'] = date('F Y');
        }

        // do the stuff for nextyear.
        if ($usemonth == 12)
        {
            $data['nextmonth'] = 1;
            $data['nextyear'] = $useyear+1;
        }else
        {
            $data['nextmonth'] = $usemonth+1;
            $data['nextyear'] = $useyear;
        }

        // do the stuff for prevyear.
        if ($usemonth == 1)
            {
            $data['prevmonth'] = 12;
            $data['prevyear'] = $useyear-1;
            }
        else
            {
            $data['prevmonth'] = $usemonth-1;
            $data['prevyear'] = $useyear;

        }

        //get days in month
        $data['days_in_month'] = cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));

        //get staff classID
        $params = array(
            'sel_query' => 'sa.ClassID',
            'staff_id' => $this->session->staff_id
        );
        $data['staff'] = $this->gherxlib->getStaffInfo($params)->row()->ClassID;

        // get all services for legend
        $data['serv_type_sql'] = $this->db->query("
            SELECT `id`, `type`
            FROM `alarm_job_type`
            WHERE `active` = 1
        ");

        $data['vts_quick_links'] = true;
        $data['uri'] = $uri;

        if( $staff_class_id == 6 ){ // tech
            $this->load->view('templates/inner_header_tech', $data);
        }else{
            $this->load->view('templates/inner_header', $data);
        }  
        $this->load->view('calendar/view_tech_schedule_tech', $data);
        if( $staff_class_id == 6 ){ // tech
            $this->load->view('templates/inner_footer_tech', $data);
        }else{
            $this->load->view('templates/inner_footer', $data);
        }         

    }


    /**
     * For Tech
     * 
     */
    public function my_calendar(){

        $taff_params = array(
            'sel_query' => "sa.FirstName, sa.LastName",
            'staff_id' => $this->session->staff_id
        );
        $staff_name_query = $this->gherxlib->getStaffInfo($taff_params)->row_array();
        $staff_name = "{$staff_name_query['FirstName']}";
        $staff_class_id = $this->system_model->getStaffClassID();

        $data['title'] = "{$staff_name}'s Calendar";


        if( $staff_class_id == 6 ){ // tech
            $this->load->view('templates/inner_header_tech', $data);
        }else{
            $this->load->view('templates/inner_header', $data);
        }   
        $this->load->view('calendar/view_individual_staff_calendar_tech', $data);
        $this->load->view('templates/inner_footer', $data);

    }

    
    public function ajax_get_tech_call_centre(){

        $tech_id = $this->input->post('tech');

        $params = array(
            'staff_id' => $tech_id
        );
        $cc_sql = $this->gherxlib->getStaffInfo($params);
        $cc = $cc_sql->row_array();

        $json_arrr = array(
            'other_call_centre' => $cc['other_call_centre'],
            'accomodation_id' => $cc['accomodation_id']
        );

        echo json_encode($json_arrr);

    }


    public function ajax_delete_calendar(){

        $json_data['status'] = false;
        $calendar_id = $this->input->post('calendar_id');

        if($calendar_id && !empty($calendar_id)){

            $this->db->where('calendar_id', $calendar_id);
            $this->db->delete('calendar');
            $this->db->limit(1);
            if($this->db->affected_rows()>0){
                $json_data['status'] = true;
            }

        }
       
        echo json_encode($json_data);

    }

    public function view_tech_calendar($month = null, $year = null){
        $data['exclude_gmap'] = true;

        // SIMPLIFIED - 12 lines into 2
        $data['month'] = $month ?? date('m');
        $data['year'] = $year ?? date('Y');

        // Create Date Object
        try {
            // Return new objects when modify etc are called
            $start = new DateTimeImmutable($data['year'] . '-' . $data['month']);
            $end = $start->modify('last day of this month');

            $today = new DateTimeImmutable('midnight');

            // Name of the month/year for dropdown calendar
            $data['current_month_year'] = $start->format('F Y');

            // Export Payroll CSV
            // Accounts wants it to default to the previous pay week
            $data['last_week_start'] = $today->modify('monday last week');
            $data['last_week_end'] = $today->modify('sunday last week');
        } catch (Exception $e) {
            log_message('error', 'calendar could not create date object from month/year arguments');
            return [];
        }



        /////////////////////////////
        /// DATA
        $calendar_data = $this->get_calendar_data($start, $end);
        $data['calendar'] = $calendar_data['calendar'];
        $data['dates'] = $calendar_data['dates'];

        // Create staff class and user array for filters to loop through
        $staff_classes = $this->staff_classes_model->as_dropdown('ClassName')->get_all();
        $data['filters'] = [];
        foreach($staff_classes as $staff_class_id => $staff_class_name){
            $data['filters'][$staff_class_id] = [
                    'ClassName' => $staff_class_name,
                    'staff' => [],
            ];
            foreach($calendar_data['staff_accounts'] as $staff_id => $staff_account){
                if($staff_account['ClassID'] == $staff_class_id){
                    $data['filters'][$staff_class_id]['staff'][$staff_id] = $staff_account['FirstName'] . '  ' . $staff_account['LastName'];
                }
            }
        }

        $data['title'] = "Staff Calendar";
        $this->load->view('templates/inner_header', $data);
        $this->load->view('calendar/view_tech_calendar', $data);
        $this->load->view('templates/inner_footer', $data);

    }

    public function get_calendar_data(DateTimeImmutable $start, DateTimeImmutable $end)
    {
        $data = [];

        /// The /calendar/export endpoint needs to export the currently selected staff or export all staff
        if($this->input->post('staff_filter') == 'selected'){
            $calendar_filter_ids_cookie = $this->input->cookie('calendar-filter-ids');
            $staff_ids = json_decode($calendar_filter_ids_cookie);
        }
        $staff_accounts = $this->staff_accounts_model->get_with_staff_classes($staff_ids);


        // ensure our dates are the min and max times of the range
        $start = $start->setTime(0,0, 0, 0);
        $end = $end->setTime(23,59, 59, 999999);

        // CALENDAR DATA FOR CURRENT MONTH
        $events_in_date_range = $this->calendar_model->getEvents($start, $end);
        $public_holidays = $this->calendar_model->get_public_holidays($start, $end);

        // Create a Month's daily loop using native PHP DateTime functions
        $dates = new DatePeriod(
            $start,
            new DateInterval('P1D'),
            $end->modify('+1 second')
        );

        // set today
        $today = new DateTimeImmutable('midnight');

        $calendar_data = [];

        // For consistency, we want each staff to have every day accounted for, whether its blank or not, so fill in the blanks
        // loop through staff
        foreach($staff_accounts as $key => $row){
            $staff_id = $row['StaffID'];

            $calendar_data[$staff_id] = $row;

            // setup a new events array for each user to include the full time period of date events
            $users_calendar = [];


            // loop through dates of the date range and CREATE every day
            foreach($dates as $date){
                $date_key = $date->format('Y-m-d');

                // init our user's calendar - an array of dates which contain data for each date and an array of events
                $users_calendar[$date_key] = [
                    'events' => $events_in_date_range[$staff_id][$date_key] ?? [],
                    // background highlight - today / leave / non working day
                    'highlight_class' => '',
                    'public_holiday' => '',
                    'leave_class' => '',
	                // we want to know if there are more than 2 events so that the cell can grow to show all the events to be clicked and edited
                    'expand_class' => (count($events_in_date_range[$staff_id][$date_key]) > 2 ? 'expand' : ''),
                ];

                // CELL HIGHLIGHTING
                // 1) GREEN - Current Date
                if($date == $today){
                    $users_calendar[$date_key]['highlight_class'] = 'today';
                }

                // 2) GREY - CLEARS EVENTS - Non working days should not show any events to minimise clutter
                if(!empty($row['working_days']) && !in_array($date->format('D'), $row['working_days'])){
                    // only change the highlight class if its not already set to today
                    $users_calendar[$date_key]['highlight_class'] = 'non-working-day';
                }

                // 3) RED - CLEARS EVENTS - If a public holiday exists for the current date skip any event for that day
                $public_holiday = $public_holidays[$date->format('Y')][$row['country_id']][$row['state']][$date_key] ?? [];
                if(!empty($public_holiday)){
	                $public_holiday = [
                        [
                            'public_holiday' => $public_holiday,
                        ],
                    ];
                    // add the 2 multi-dimensional arrays together, holiday first as that should be the most important thing we need to know
	                $users_calendar[$date_key]['events'] = array_merge(array_values($public_holiday), array_values($users_calendar[$date_key]['events']));
                    $users_calendar[$date_key]['public_holiday_class'] = 'public_holiday';
                }






                //
                foreach($users_calendar[$date_key]['events'] as $event_key => $event){
                    // 4) RED - Marked as leave - cant have accommodation so skip any event for that day
                    if($event['marked_as_leave'] == 1){
                        $users_calendar[$date_key]['leave_class'] = 'marked_as_leave';
                    }

                    // 4) HOUSE ICON - Accommodation
                    if(!is_null($event['accomodation'])){
                        switch($event['accomodation']){
                            case 2:
                                $accomodation_class = 'accomodation-pending';
                                break;
                            case 1:
	                            $accomodation_class = 'accomodation-booked';
                                break;
                            case 0:
	                            $accomodation_class = 'accomodation-required';
                                break;
                        }
                        $users_calendar[$date_key]['events'][$event_key]['accomodation_class'] = $accomodation_class;
                    }
                }
            }
            // Once the date range is done, add the  $users_events array to our new calendar_data
            $calendar_data[$staff_id]['users_calendar'] = $users_calendar;
        }

        // passing the dates through so they can be looped through independently
        $data = [
            'dates' => $dates,
            'staff_accounts' => $staff_accounts,
            'calendar' => $calendar_data,
        ];

        return $data;
    }

    public function export()
    {
        $this->output->enable_profiler(false);

        $start = DateTimeImmutable::createFromFormat('d/m/Y', $this->input->post('payroll_from'));
        $end = DateTimeImmutable::createFromFormat('d/m/Y', $this->input->post('payroll_to'));

        $calendar_data = $this->get_calendar_data($start, $end);

        // resort data as accounts needs this list in alphabetical order
        usort($calendar_data['calendar'], function ($staff1, $staff2) {
            return $staff1['LastName'] <=> $staff2['LastName'];
        });

        $filename = 'staff-calendar_' . $start->format('Y-m-d') . '_' . $end->format('Y-m-d') . '.csv';

        // output headers so that the file is downloaded rather than displayed
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        // create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');
        //fputcsv($output, array_keys($calendar_data));

        $headers = [
            'Last Name',
            'First Name',
            'Position',
            'Working Days',
        ];

        // switch for first loop
        $make_headers = true;
        foreach($calendar_data['calendar'] as $staff_id => $user) {
            // Create our array of data
            $data = [
                $user['LastName'],
                $user['FirstName'],
                $user['sa_position'],
                $user['working_days_label'],
            ];

            foreach($user['users_calendar'] as $date => $date_data){
                // Only on first loop we generate the header row of dates
                if($make_headers){
                    array_push($headers, $date);
                }

                // Next loop through all the events for that day
                $events = [];
                foreach($date_data['events'] as $event){
                    $events[] = $event['region'];
                }
                // Now add the event list to the date
                array_push($data, join(' | ', $events));
            }


            // Converting data into csv

            // Output the data to the download stream
            if($make_headers){
                fputcsv($output, $headers);
                // disable switch after first loop
                $make_headers = false;
            }
            // add user row
            fputcsv($output, $data);
        }
    }

    public function staff_calendar_csv(){
        
        // GRAB THE DATES FROM THE URL
        $month_post = $this->input->get_post('month');
        $year_post = $this->input->get_post('year');
        $payroll_export = $this->input->get_post('payroll_export');
        $country_id = $this->config->item('country');

        if($month_post){ $month = $month_post; }
        if($year_post){ $year = $year_post; }

        // IF THE DATES DONT EXISIT IN URL THEN USE CURRENT
        if(!$month_post) {
            $month = date('m');
        }
        if(!$year_post) {
            $year = date('Y');
        }



        //START CSV
        // file name 
        $random_str = rand().'-'.date('YmdHis');
        $filename = 'StaffCalendar'.$random_str.'-'.$month.'-'.$year.'.csv';

        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename={$filename}");
        header("Pragma: no-cache");
        header("Expires: 0");

        
        //the tables rely on this to form.
        $monthname = mktime(0, 0, 0, $month, 1, $year);
        $monthname = date("F", $monthname);
        
        //get the number of days in the month
        $calendardays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        

        $countday = 0;
        $themonth = array();

        if( $payroll_export == 1 ){
          
		
            $payroll_from = $this->system_model->formatDate($this->input->get_post('payroll_from'));
            $payroll_to = $this->system_model->formatDate($this->input->get_post('payroll_to'));
            $start_date_loop = $payroll_from;

            // new
            while( $start_date_loop <= $payroll_to  ){
              
                $current_date = date('Y-m-d',strtotime($start_date_loop));
                $start_date_loop = date('Y-m-d',strtotime("{$start_date_loop} + 1 day"));
                $themonth[$countday]['date'] = $current_date; // this is from old code, this is how they store it so follow
                $countday++;
            }
           
        }else{
            
            
            while($countday < $calendardays) {
                        
                $thedate = $countday + 1;
                $whiledate = $year.'-'.$month.'-'.$thedate;
                
                $themonth[$countday]['date'] = $whiledate;
                            
                $countday = $countday + 1;
            }			
            
            
        }


		$date_str = '"Last Name","First Name",Position';
        foreach($themonth as $theday){
          $thedate = date("d/m/Y", strtotime($theday['date']));
          $date_str .= ',"'.$thedate.'"';
        }

        echo $date_str;
        echo "\n";
        
        $staff_params = array(
            'sel_query' => "DISTINCT(sa.`StaffID`), sa.StaffID, sa.FirstName, sa.LastName, sa.working_days, sa.sa_position, sc.ClassName, sc.ClassID",
            'custom_joins' => array('join_table'=> 'staff_classes as sc' ,'join_on'=> 'sc.ClassID = sa.ClassID' , 'join_type'=>'left'),
            'sort_list' => array(
                array(
                    'order_by' => 'sa.`LastName`',
                    'sort' => 'ASC',
                ),
                array(
                    'order_by' => 'sa.FirstName',
                    'sort' => 'ASC'
                )
            )
            
        );
        $tech_sql = $this->gherxlib->getStaffInfo($staff_params);

        foreach($tech_sql->result_array() as $tech){
		  
            $cal_fil_sql = $this->calendar_model->cal_filters();
            $cal_fil = $cal_fil_sql->row_array();
            $staff_filter = explode(",", $cal_fil['StaffFilter']);
            
            if(!in_array($tech['StaffID'], $staff_filter)){
    
                $staff_name = "{$tech['LastName']}, {$tech['FirstName']}";
                $position = ucfirst($tech['sa_position']);
            
                echo "\"{$tech['LastName']}\",\"{$tech['FirstName']}\",\"{$position}\"";
                
                
            
                foreach($themonth as $theday){
                        //echo ",Day: {$theday['date']}  Tech: {$tech['StaffID']}";
                        
                        
                        
                        // if weekend
                        $weekDay = date('w', strtotime($theday['date']));
                        $isWeekend = ($weekDay == 0 || $weekDay == 6)?1:0;
                        $jday = date("D",strtotime($theday['date']));
                        
                        // get staff working days
                        $sa_sql = $this->db->select('working_days')->from('staff_accounts')->where('StaffID',$tech['StaffID'])->get();
                        $sa = $sa_sql->row_array();
                        $wd = $sa['working_days'];
                        
                        // if not working day
                        if( strchr($wd,$jday)==false && $isWeekend==0 ){
                            echo ",OFF";
                        }else{
                            
                            $sql = $this->db->query("
                                SELECT c.`calendar_id`, c.`staff_id`, c.`region`, c.`date_start`, c.`date_finish`, s.`FirstName`, s.`LastName`
                                FROM `calendar` AS c 
                                INNER JOIN `staff_accounts` AS s ON (s.`StaffID` = c.`staff_id`)
                                WHERE s.`Deleted` = 0 
                                AND s.`active` = 1 
                                AND c.`staff_id` ={$tech['StaffID']}
                                AND '{$theday['date']}' BETWEEN c.`date_start` AND c.`date_finish`
                            ");
                            
                            if($sql->num_rows()>0){
                            
                                $region_arr = [];
                                $region_imp = null;

                                foreach( $sql->result() as $row ){

                                    $region_arr[] = $row->region;

                                } 

                                // if multiple items, add new line
                                if( count($region_arr) > 0 ){

                                    $region_imp = implode("\n",$region_arr);

                                }
                                
                                echo ',"'.$region_imp.'"';
                                
                            }else{
                                echo ",";
                            }
                            
                        }
                        
                    
                        
                    
                }	
        
                echo "\n";
            
            }
          
          }

    }

   
    public function ajax_get_tech_run_list(){

        $tech_id = $this->input->get_post('tech_id');
        $date = $this->input->get_post('date');


        //get tech run
        $tr_sql = $this->db->select('*')->from('tech_run')->where(array('assigned_tech' => $tech_id, 'date' => $date))->get();

        if($tr_sql->num_rows()>0){            
            $tr = $tr_sql->row_array();
            $tr_id = $tr['tech_run_id'];
        }
  
        
        if( $tr_id > 0 ){ ?>
            <div class="map_link float-left mt-2">
                <a href="/tech_run/run_sheet_map/?tr_id=<?php echo $tr_id; ?>">
                    <span class="fa fa-map-marker" style="font-size:16px;"></span>
                </a>
            </div>
        <?php
        }
        ?> 
        
        <div style="clear:both;"></div>
        
        <div class="row tds_tbl_div">
            <div class="col-sm-12">
            <?php            
            if( $tr_id > 0 ){

                //get techrunrows
                $tr_sel = "
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
                p.`qld_new_leg_alarm_num`,
                p.`preferred_alarm_id`,

                a.`agency_id`,
                a.`allow_upfront_billing`
                ";
                $tr_params = array(
                'sel_query' => $tr_sel,
                'sort_list' => array(
                array(
                'order_by' => 'trr.sort_order_num',
                'sort' => 'ASC'
                )
                ),
                'display_only_booked' => 1,
                'display_query' => 0
                );
                $view_data['jr_list2'] = $this->tech_model->getTechRunRows($tr_id, $this->config->item('country'), $tr_params);    
                $view_data['tech_id'] = $tech_id;
                $view_data['date'] = $date;  
                $view_data['show_completed_col'] = true;                    

                $this->load->view('tech_run/tech_day_schedule_tech_table_list',$view_data);	

            }                                   
            ?>
            </div>
        </div>  
        <?php
                   

    }


}



