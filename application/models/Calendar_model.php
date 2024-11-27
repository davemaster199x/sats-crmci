<?php

class Calendar_model extends MY_Model
{
	public $table = 'calendar'; // you MUST mention the table name
	public $primary_key = 'calendar_id'; // you MUST mention the primary key


	// ...Or you can set an array with the fields that cannot be filled by insert/update
	public $protected = [
		'calendar_id'
	];
    public function __construct()
    {
        $this->load->database();

    }

    public function get_tech_calendar($params){

        if (isset($params['sel_query'])) {
            $sel_query = $params['sel_query'];
        } else {
            $sel_query = '*';
        }

        $this->db->select($sel_query);
        $this->db->from('calendar as c');
        $this->db->join('staff_accounts as s','s.StaffID = c.staff_id','inner');
        $this->db->join('country_access as ca','ca.staff_accounts_id = s.StaffID','left');
        $this->db->join('accomodation as acco','acco.accomodation_id = c.accomodation_id','left');
       #$this->db->where('s.StaffID',$this->session->staff_id);
        $this->db->where('ca.country_id', $this->config->item('country'));


        if($params['StaffID'] && !empty($params['StaffID'])){
            $this->db->where('s.StaffID', $params['StaffID']);
        }

        if($params['calendar_id'] && !empty($params['calendar_id'])){
            $this->db->where('c.calendar_id', $params['calendar_id']);
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

   public function getAccomodation($params){

        if (isset($params['sel_query'])) {
            $sel_query = $params['sel_query'];
        } else {
            $sel_query = '*';
        }

        $this->db->select($sel_query);
        $this->db->from('accomodation');
        $this->db->where('country_id', $this->config->item('country'));

        //accomodation_id filter
        if( $params['accomodation_id'] && !empty($params['accomodation_id']) ){
            $this->db->where('accomodation_id', $params['accomodation_id']);
        }

        //area filter
        if( $params['area'] && !empty($params['area']) ){
            $this->db->where('area', $params['area']);
        }

        //search
        if  ( $params['search'] && !empty($params['search']) ) {
            $this->db->like('address', $params['search']);
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

    public function jsanitize($input){
        return filter_var(trim($input), FILTER_SANITIZE_STRING);
    }

    public function send_ical_to_mail($subject='', $from_email="", $to_name='', $to_email='', $event_name='', $description='', $date_start='', $date_end='' ){

        $this->load->library('email');

        // data
        // santize input
        $summary     = $this->jsanitize($event_name);
        $date = date("Ymd\THis");
        $datestart   = date("Ymd\THis",strtotime(str_replace("/","-",$this->jsanitize($date_start))));
        $dateend     = date("Ymd\THis",strtotime(str_replace("/","-",$this->jsanitize($date_end))));
        $filename    = 'iCalendar'.date('YmdHis');
        
        $eol = PHP_EOL;
        $unique_id = md5(time());
        

        // attachment
        $message = "BEGIN:VCALENDAR" .$eol;
        $message .= "VERSION:2.0" .$eol;
        $message .= "PRODID:-//hacksw/handcal//NONSGML v1.0//EN" .$eol;
        $message .= "BEGIN:VEVENT" . $eol;
        $message .= "UID:{$unique_id}" .$eol;
        $message .= "DTSTAMP:{$date}" .$eol;
        $message .= "SUMMARY:{$summary}" . $eol;
        $message .= "DESCRIPTION:{$description}" . $eol;
        $message .= "DTSTART:{$datestart}". $eol;
        $message .= "DTEND:{$dateend}" .$eol;
        $message .= "END:VEVENT" .$eol;
        $message .= "END:VCALENDAR" . $eol;
       
        
        // mail it
        $this->email->to($to_email);
        $this->email->subject($subject);
        $this->email->attach($message,'attachment',"{$filename}.ics", 'text/calendar');
        $this->email->send();
            
    }

    /**
     * Fetch current staff filter for user
     */
    public function cal_filters(){
        $this->db->select('StaffId,StaffFilter,staff_class_filter');
        $this->db->from('cal_filters');
        $this->db->where('StaffId', $this->session->staff_id);
        $this->db->limit(1);
        $query = $this->db->get();
        return $query;
    }


    /**
     * // fetch all the calendar entries - single dates
     */
    public function fetch_calendar_by_diff_start_end_dates($month_start, $month_end){

        $sql = "SELECT c.calendar_id,
                        c.staff_id,
                        c.region,
                        c.date_start,
                        c.date_finish,
                        s.FirstName,
                        s.LastName,
                        DATEDIFF(date_finish,date_start),
                        booking_target,
                        c.accomodation,
                        c.marked_as_leave,
                        s.working_days  
                FROM calendar c 
                INNER JOIN staff_accounts s ON (s.StaffID = c.staff_id) 
                WHERE s.Deleted = 0 
                  AND c.date_start = c.date_finish 
                  AND c.date_start >= '{$month_start}' 
                  AND c.date_finish <= '{$month_end}' 
                  AND s.active = 1 
                  AND c.country_id ={$this->config->item('country')} 
                ORDER BY staff_id, date_start";
        $query = $this->db->query($sql);
        return $query;

    }

    /**
     * // fetch all the calendar entries - single dates
     */
    public function fetch_calendar_by_single_dates(){
        $sql = "SELECT c.calendar_id,
                        c.staff_id,
                        c.region,
                        c.date_start,
                        c.date_finish,
                        s.FirstName,
                        s.LastName,
                        DATEDIFF(date_finish,date_start) AS num_days,
                        booking_target,
                        c.accomodation,
                        c.marked_as_leave,
                        s.working_days  
                FROM calendar c 
                INNER JOIN staff_accounts s ON (s.StaffID = c.staff_id) 
                WHERE s.Deleted = 0 
                  AND c.date_start != c.date_finish 
                  AND s.active = 1 
                  AND c.country_id ={$this->config->item('country')} 
                ORDER BY staff_id, date_start";
        $query = $this->db->query($sql);
        return $query;
    }

    /**
     * @param DateTimeImmutable $start
     * @param DateTimeImmutable $end
     * @return array
     * @throws Exception
     */
    public function getEvents(DateTimeImmutable $start, DateTimeImmutable $end){
        if(empty($this->session->userdata('staff_id'))){
            return [];
        }

        $start_sql = $start->format('Y-m-d');
        $end_sql = $end->format('Y-m-d');

        $sql = "SELECT
        staff_accounts.ClassID,
        staff_classes.ClassName,
        FirstName,
        LastName,
        working_days,

        calendar_id,
        staff_id,
        region,
        date_start,
        date_finish,
        booking_target,
        accomodation,
        marked_as_leave
FROM    calendar
LEFT JOIN staff_accounts ON calendar.staff_id = staff_accounts.StaffID
LEFT JOIN staff_classes ON staff_classes.ClassID = staff_accounts.ClassID
WHERE   staff_accounts.Deleted = 0
AND     staff_accounts.active = 1
AND (
    (
        date_start = date_finish
        AND date_start >= '" . $start_sql . "'
        AND date_finish <= '" . $end_sql . "'
    )
    OR
    (
        date_start != date_finish
        AND (
            (date_start <= '" . $start_sql . "' AND date_finish >= '" . $start_sql . "')
            OR
            (date_start > '" . $start_sql . "' AND date_finish < '" . $end_sql . "')
            OR
            (date_start < '" . $end_sql . "' AND date_finish > '" . $end_sql . "')
        )
    )
)
AND     staff_accounts.StaffID NOT IN (" . Staff_accounts_model::get_ignored_account_ids() . ")
ORDER BY staff_classes.ClassName, staff_accounts.FirstName, staff_accounts.LastName, date_start";

        $events = $this->db->query($sql)->result_array();

        // We need to create rows for multiple day events
        $data = [];
        if(!empty($events)){
            foreach($events as $row){
                // Create the user's data array if it doesnt already exist
                if(empty($data[ $row['staff_id'] ])){
                    $data[ $row['staff_id'] ] = [];
                }

                // Once the users data structure exists, we can add events to the events array below
                // we are creating an array of dates from this event that are within the range
                $event_start = new DateTimeImmutable($row['date_start']);
                $event_finish = new DateTimeImmutable($row['date_finish']);

                // We are only concerned with dates for THIS date range
                // trim any dates before
                if($event_start < $start){
                    $event_start = $start;
                }

                // trim any dates after
                if($event_finish > $end){
                    $event_finish = $end;
                }

                // The +1 second is to ensure that there is at least 1 date in the period for single date events
                // A bit hacky but unsure of a simpler solution
                $event_dates = new DatePeriod(
                    $event_start,
                    new DateInterval('P1D'),
                    $event_finish->modify('+1 second')
                );

                // Add the date and event details to the events array for each day the event is within the range
                if(!empty($event_dates)){
                    foreach($event_dates as $event_date){
                        $data[ $row['staff_id'] ][$event_date->format('Y-m-d')][] = $row;
                    }
                }
            }
        }

        return $data;
    }

    /**
     * I found this api that we can use for the calendar
     * https://date.nager.at/PublicHoliday/Australia
     * @param DateTimeImmutable $start
     * @param DateTimeImmutable $end
     * @return array
     */
    public function get_public_holidays(DateTimeImmutable $start, DateTimeImmutable $end)
    {
        $this->load->driver('cache', ['adapter' => 'file']);

        // init our data array
        $data = [];

        // just a quick hack array of countries to save a db call
        $country_codes = [
            1 => 'au',
            2 => 'nz',
        ];

        // We will loop through the years of the range and get that data
        $years = new DatePeriod(
            $start,
            new DateInterval('P1Y'),
            $end->modify('+1 second')
        );

        foreach($years as $year) {
            $year_key = $year->format('Y');
            // Set our cache name, it will save all countries to a yearly file
            $cache_name = 'public_holidays_' . $year_key;

            // Check if cache exists
            if ( !$data[$year_key] = $this->cache->get($cache_name) ){
                // No cache found, Loop through each country and connect to its api
                foreach ($country_codes as $country_id => $country_code) {
                    $data[$year_key][$country_id] = [];
                    $curl = curl_init();

                    curl_setopt_array($curl, [
                        CURLOPT_URL => 'https://date.nager.at/api/v3/publicholidays/' . $year_key . '/' . $country_code,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'GET',
                    ]);

                    $api_response = curl_exec($curl);
                    curl_close($curl);

                    // convert response string into assoc array
                    $holiday_data = json_decode($api_response, true);
                    if (json_last_error() != JSON_ERROR_NONE) {
                        log_message('error', 'public holidays api not working');
                    }

                    // we need all states for global holidays
                    $CI =& get_instance();
                    $CI->load->model('states_def_model');
                    /** @var array $states */
                    $states = $CI->states_def_model->use_column_as_key('state')->where(
                        'country_id',
                        $country_id
                    )->get_all();

                    // Loop through each holiday and generate an array to be used by our calendar
                    foreach ($holiday_data as $holiday) {
                        // Set our Holiday name
                        $holiday_name = $holiday['localName'] . ' Public Holiday';

                        // National or state holiday?
                        if ($holiday['global']) {
                            // A global holiday is a national holiday so lets add this to all of our states
                            foreach ($states as $state_code => $state) {
                                $data[$year_key][$country_id][$state_code][$holiday['date']] = $holiday_name;
                            }
                        } else {
                            // create a cleaner array of the holidays included states to work with
                            $holiday_states = array_map(function ($value) {
                                // counties are states and are in format "AU-QLD", so strip the prefix
                                return explode('-', $value)[1];
                            }, $holiday['counties']);

                            // Create a list for the event title
                            $holiday_states_list = join(', ', $holiday_states);

                            // Add holiday to its respective states
                            foreach ($holiday_states as $state_code) {
                                $data[$year_key][$country_id][$state_code][$holiday['date']] = $holiday_name . ' | ' . $holiday_states_list;
                            }
                        }
                        // End if national or not
                    }
                    // End foreach Holiday
                }
                // End foreach Country

                // Save into the cache for a week
                $this->cache->save($cache_name, $api_response, 604800);
            }
            // End foreach Year
        }

        // return the cached data if it exists or the newly generated data
        return $data;
    }

}
