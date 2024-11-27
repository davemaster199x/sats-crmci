<?php

class Staff_accounts_model extends MY_Model {

	public $table = 'staff_accounts'; // you MUST mention the table name
	public $primary_key = 'StaffID'; // you MUST mention the primary key


	// ...Or you can set an array with the fields that cannot be filled by insert/update
	public $protected = [
		'StaffID'
	];

    // Some staff accounts are used for all different random purposes, this is mainly for aus but may be others
    const IGNORED_ACCOUNT_IDS = [
        1, // other supplier
        2, // blah
    ];

	// TEST ACCOUNTS BY COUNTRY ID
	const IGNORED_ACCOUNT_IDS_1 = [
		2043, // events
		2070, // dev testing
		2184, // nz prospect 123
		2195, // house ? brad2h
		2411, // new dev testing
		2482, // U D
		2483, // Dev Dev
		2525, // tech test
		2530, // I O
	];

	const IGNORED_ACCOUNT_IDS_2 = [
        2188, // Auckland j
        2216, // House
	];

    public function __construct() {
        $this->has_one['staff_class'] = ['Staff_classes_model','ClassID','ClassID'];

        parent::__construct();
    }

    /**
     * Use this to help filter out dummy and test accounts for the calendar
     * TODO - put in support for au nz sas
     * @param boolean $joined Default: true
     * @return int[]|string
     */
    public static function get_ignored_account_ids($joined = true)
    {
        $constant = 'self::IGNORED_ACCOUNT_IDS_' . config_item('country');
        $data = array_merge(SELF::IGNORED_ACCOUNT_IDS, constant($constant));

		if($joined){
            $data = join(',', $data);
        }

        return $data;
    }

    // wip
    
    public function get_staff_accounts($params) {

        if (isset($params['sel_query'])) {
            $sel_query = $params['sel_query'];
        } else {
            $sel_query = '*';
        }

        $this->db->select($sel_query);
        $this->db->from('`staff_accounts` AS sa');
        //$this->db->join('`alarm_job_type` AS ajt', 'j.`service` = ajt.`id`', 'left');	

        if (isset($params['joins']) && count($params['joins'])) {
            foreach ($params['joins'] as $join) {
                if ($join === 'country_access') {
                    $this->db->join('`country_access` AS ca', 'sa.`StaffID` = ca.`staff_accounts_id`', 'INNER');
                }
                if ($join === 'crm_login_restricted') {
                    $this->db->join('(SELECT staff_id, count(staff_id) AS crm_login_restricted
                                    FROM staff_permissions
                                    WHERE has_permission_on=5
                                    GROUP BY staff_id) AS sp',
                                    'sp.staff_id = sa.StaffID',
                                    'LEFT');
                }
            }
        }

        // filters
        if ( $params['staff_id'] > 0 ) {
            $this->db->where('sa.`StaffID`', $params['staff_id']);
        }
        if ( $params['class_id'] > 0 ) {
            $this->db->where('sa.`ClassID`', $params['class_id']);
        }
        if ( $params['email'] != '' ) {
            $this->db->where('sa.`Email`', $params['email']);
        }
        if ( $params['password'] != '' ) {
            $this->db->where('sa.`Password`', $params['password']);
        }
        if ( is_numeric($params['active']) ) {
            $this->db->where('sa.`active`', $params['active']);
        }
        if ( is_numeric($params['deleted']) ) {
            $this->db->where('sa.`Deleted`', $params['deleted']);
        }
        if ( $params['country_id'] > 0 ) {
            $this->db->where('ca.`country_id`', $params['country_id']);
        }

        // search
        if (isset($params['search']) && $params['search'] != '') {
            $search_filter = "CONCAT_WS(' ', LOWER(p.address_1), LOWER(p.address_2), LOWER(p.address_3), LOWER(p.state), LOWER(p.postcode))";
            $this->db->like($search_filter, $params['search']);
        }

        // custom filter
        if (isset($params['custom_where'])) {
            $this->db->where($params['custom_where']);
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
        if (isset($params['limit']) && $params['limit'] > 0) {
            $this->db->limit($params['limit'], $params['offset']);
        }


        $query = $this->db->get();
        if (isset($params['display_query']) && $params['display_query'] == 1) {
            echo $this->db->last_query();
        }

        return $query;
    }

    public function get_staff_accounts_with_country_access($params) {

        if (isset($params['sel_query'])) {
            $sel_query = $params['sel_query'];
        } else {
            $sel_query = '*';
        }

        $this->db->select($sel_query);
        $this->db->from('`staff_accounts` AS sa');
        if (isset($params['joins']) && count($params['joins'])) {
            foreach ($params['joins'] as $join) {
                if ($join === 'country_access') {
                    $this->db->join('`country_access` AS ca', 'sa.`StaffID` = ca.`staff_accounts_id`', 'LEFT');
                }
            }
        }
        //$this->db->join('`alarm_job_type` AS ajt', 'j.`service` = ajt.`id`', 'left');	
        // filters
        if (isset($params['staff_id'])) {
            $this->db->where('sa.`StaffID`', $params['staff_id']);
        }
        if (isset($params['class_id'])) {
            $this->db->where('sa.`ClassID`', $params['class_id']);
        }
        if (isset($params['email'])) {
            $this->db->where('sa.`Email`', $params['email']);
        }
        if (isset($params['password'])) {
            $this->db->where('sa.`Password`', $params['password']);
        }
        if (isset($params['active'])) {
            $this->db->where('sa.`active`', $params['active']);
        }
        if (isset($params['deleted'])) {
            $this->db->where('sa.`Deleted`', $params['deleted']);
        }

        // search
        if (isset($params['search']) && $params['search'] != '') {
            $search_filter = "CONCAT_WS(' ', LOWER(p.address_1), LOWER(p.address_2), LOWER(p.address_3), LOWER(p.state), LOWER(p.postcode))";
            $this->db->like($search_filter, $params['search']);
        }

        // custom filter
        if (isset($params['custom_where'])) {
            $this->db->where($params['custom_where']);
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
        if (isset($params['limit']) && $params['limit'] > 0) {
            $this->db->limit($params['limit'], $params['offset']);
        }


        $query = $this->db->get();
        if (isset($params['display_query']) && $params['display_query'] == 1) {
            echo $this->db->last_query();
        }

        return $query;
    }

    public function check_if_email_exist($email) {

        // get email
        $params = array(
            'sel_query' => 'COUNT(sa.StaffID) AS sa_count',
            'email' => $email,
            'display_query' => 0
        );

        // get user details
        $sql = $this->get_staff_accounts($params);
        $row = $sql->row();

        if ($row->sa_count > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_staff_accounts_details($id)
    {
        $params = array(
            'sel_query' => 'sa.FirstName, sa.email',
            'staff_id' => $id,
            'display_query' => 0
        );

        $query = $this->get_staff_accounts($params);
        $row = $query->row();
        
        return $row;
    }


    // MY_Model
    public function active()
    {
        $this->_database->where('active',1)
                        ->where('Deleted',0)
                        ->where('StaffID', 'NOT IN', self::get_ignored_account_ids());
        return $this;
    }

	/**
	 * Return an array of calendar events along with staff accounts and their class
	 * Used for view_tech_calendar
	 * @param array $staff_ids
	 * @return array
	 */
	public function get_with_staff_classes($staff_ids = [])
    {
        // User can specify the users they only want to see via filters
		$where_staff_ids = '';
        if(!empty($staff_ids)){
            $staff_ids = array_filter($staff_ids, 'is_numeric');
            $where_staff_ids = "AND StaffID IN (" . join(',', $staff_ids) . ")";
        }

        $sql = "SELECT  StaffID,
                        staff_accounts.ClassID,
                        staff_classes.ClassName,
                        FirstName,
                        LastName,
                        working_days,
                        sa_position,
                        CASE
							WHEN address LIKE '% nsw%' THEN 'NSW'
							WHEN address LIKE '% vic%' THEN 'VIC'
							WHEN address LIKE '% tas%' THEN 'TAS'
							WHEN address LIKE '% nt%' THEN 'NT'
							WHEN address LIKE '% sa%' THEN 'SA'
							WHEN address LIKE '% wa%' THEN 'WA'
                            
                            WHEN address LIKE '% auckland%' THEN 'AUK'
							WHEN address LIKE '% wellington%' THEN 'WGN'
                            
							ELSE 'QLD'
						END as state,
						CASE
							WHEN address LIKE '%zealand%' THEN 2
							ELSE 1
						END as country_id
                        

                FROM        staff_accounts
                LEFT JOIN   staff_classes ON staff_accounts.ClassID = staff_classes.ClassID
                WHERE       staff_accounts.Deleted = 0
                AND         staff_accounts.active = 1
                AND         staff_accounts.email LIKE '%@" . config_item('base_domain') . "'
                AND         staff_accounts.working_days IS NOT NULL
                AND         staff_accounts.working_days != ''
                AND         staff_accounts.StaffID NOT IN (" . self::get_ignored_account_ids() . ")
                " . $where_staff_ids . "
                ORDER BY    staff_classes.ClassName, staff_accounts.FirstName, staff_accounts.LastName";

       $results = $this->_database->query($sql)->result_array();

       if(!empty($results)){
           foreach($results as $key => $row){
               // needed for checking if its a work day or not on calendar
               $row['working_days'] = explode(',', $row['working_days']);

               // Create Smaller label with just first letter
               $working_days_label_array = array_map(function($day){
                   return $day[0];
               }, $row['working_days']);

               // label shows with name
               $row['working_days_label'] = join(' ', $working_days_label_array);

               // save our data
               $data[$row['StaffID']] = $row;
           }
           return $data;
       } else {
           return [];
       }
    }
}

