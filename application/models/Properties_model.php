<?php

class Properties_model extends MY_Model {

    public $table = 'property'; // you MUST mention the table name
    public $primary_key = 'property_id'; // you MUST mention the primary key

    public function __construct() {
        parent::__construct();
        $this->load->helper('email_helper');
    }

    /**
     * Retrieve the base params for active properties
     * @param $property_id
     * @return array
     */
    public function get_active_properties_params($property_id = 0)
    {
        $params = [
            'a_status' => 'active',
            'a_deleted' => 0,
            'ps_service' => 1,
            'custom_where' => 'a.franchise_groups_id != 14',
            'join_table' => array('property_services'),
            //'display_query' => 1,
        ];

        if(!empty($property_id) && $property_id > 0){
            $params['property_id'] = $property_id;
        }

        return $params;
    }

    /**
     * Check if a property_id is an active property
     * @param int $property_id
     * @return bool
     */
    public function is_active_property(int $property_id = 0)
    {
        if(empty($property_id)){
            return false;
        }

        $result = $this->properties_model->get_active_properties($property_id);

        if(!empty($result)){
            return true;
        } else {
            return false;
        }
    }


    /**
     * THIS IS THE TRUTH OF ACTIVE PROPERTIES
     * returns about 102k properties
     * @param int $property_id
     * @return mixed
     */
    public function get_active_properties($property_id = 0)
    {
        $params = $this->get_active_properties_params($property_id);

        return $this->properties_model->get_properties($params)->result_array();
    }

    /**
     * Get Property
     * This method accepts params to retrieve properties
     */
    public function get_properties($params) {

        if (isset($params['sel_query'])) {
            $sel_query = $params['sel_query'];
        } else {
            $sel_query = '*';
        }

        if(isset($params['search_type']) && $params['search_type'] != ''){
            $this->db->distinct()->select($sel_query);
        }
        else{
            $this->db->select($sel_query);
        }
        
        $this->db->from('`property` AS p');
        $this->db->join('`agency` AS a', 'p.`agency_id` = a.`agency_id`', 'left');
        $this->db->join('`agency_user_accounts` AS aua', 'p.`pm_id_new` = aua.`agency_user_account_id`', 'left');

        // set joins
        if (!empty($params['join_table'])) {

            foreach ($params['join_table'] as $join_table) {

                if ($join_table == 'property_services') {
                    $this->db->join('`property_services` AS ps', 'p.`property_id` = ps.`property_id`', 'inner');
                }

                if ($join_table == 'alarm_job_type') {
                    $this->db->join('`alarm_job_type` AS ajt', 'ps.`alarm_job_type_id` = ajt.`id`', 'left');
                }

                if ($join_table == 'jobs') {
                    $this->db->join('`jobs` AS j', 'p.`property_id` = j.`property_id`', 'inner');
                }

                if ($join_table == 'staff_accounts') {
                    $this->db->join('`staff_accounts` AS sa', 'sa.`StaffID` = a.`salesrep`', 'left');
                }

                if ($join_table == 'countries') {
                    $this->db->join('`countries` AS c', 'a.`country_id` = c.`country_id`', 'left');
                }

                 // API property generic table
                // PMe
                if ($join_table == 'api_property_data_pme') {
                    $this->db->join('`api_property_data` AS apd_pme', '( p.`property_id` = apd_pme.`crm_prop_id` AND apd_pme.`api` = 1 )', 'left');
                }

                // palace
                if ($join_table == 'api_property_data_palace') {
                    $this->db->join('`api_property_data` AS apd_palace', '( p.`property_id` = apd_palace.`crm_prop_id` AND apd_palace.`api` = 4 )', 'left');
                }

                // propertytree
                if ($join_table == 'api_property_data_pt') {
                    $this->db->join('`api_property_data` AS apd_pt', '( p.`property_id` = apd_pt.`crm_prop_id` AND apd_pt.`api` = 3 )', 'left');
                }

                // console
                if ($join_table == 'console') {
                    $this->db->join('`console_properties` AS cp', '( p.`property_id` = cp.`crm_prop_id` AND cp.`active` = 1 )', 'left');
                }

                if ($join_table == 'agency_priority') {
                    $this->db->join('agency_priority as aght', 'a.agency_id = aght.agency_id', 'left');
                }

                if ($join_table == 'agency_priority_marker_definition') {
                    $this->db->join('agency_priority_marker_definition as apmd', 'aght.priority = apmd.priority', 'left');
                }

                if ($join_table == 'property_lockbox') {
                    $this->db->join('property_lockbox as pl', 'p.property_id = pl.property_id', 'left');
                }

                if ($join_table == 'api_property_data') {
                    $this->db->join('api_property_data as apd', 'p.property_id = apd.crm_prop_id', 'left');
                }

                if ($join_table === 'postcode') {
                    $this->db->join('postcode AS pr', 'p.postcode = pr.postcode', 'left');
                }
                if ($join_table === 'sub_regions') {
                    $this->db->join('sub_regions as psr', 'pr.sub_region_id = psr.sub_region_id', 'left');
                }
                if ($join_table === 'regions') {
                    $this->db->join('regions as r', 'psr.region_id = r.`regions_id', 'left');
                }

                // join postcodes, sub-regions and regions
                if ($join_table === 'join_regions')  {
                    $this->db->join('`postcode` AS pc', 'p.`postcode` = pc.`postcode`', 'left');
                    $this->db->join('`sub_regions` AS sr', 'pc.`sub_region_id` = sr.`sub_region_id`', 'left');
                    $this->db->join('`regions` AS r', 'sr.`region_id` = r.`regions_id`', 'left');
                }
                
            }
        }

        // custom joins
        if (isset($params['custom_joins']) && $params['custom_joins'] != '') {
            $this->db->join($params['custom_joins']['join_table'], $params['custom_joins']['join_on'], $params['custom_joins']['join_type']);
        }

        // multiple custom joins
        if( isset($params['custom_joins_arr'])){
            foreach( $params['custom_joins_arr'] as $custom_joins ){
                $this->db->join($custom_joins['join_table'], $custom_joins['join_on'], $custom_joins['join_type']);
            }
        }

        if(isset($params['search_type']) && $params['search_type'] != ''){
            $this->db->join($params['custom_joins_bn']['join_table'], $params['custom_joins_bn']['join_on'], $params['custom_joins_bn']['join_type']);
            $this->db->like('opd.`building_name`', $params['building_name']);
        }

        // filters
        // property
        if (isset($params['property_id'])) {
            $this->db->where('p.`property_id`', $params['property_id']);
        }
        if (isset($params['p_deleted'])) {
            if (isset($params['exc_deleted'])) {
                // No delete filter in CI VPD
            } else {
                $this->db->where('p.`deleted`', $params['p_deleted']);
            }
        }else{//default
            $this->db->where('p.`deleted`',0);
        }
        if (isset($params['pm_id']) && $params['pm_id'] != '') {
            $this->db->where('p.`pm_id_new`', $params['pm_id']);
        }
        if (isset($params['job_id']) && $params['job_id'] != '') {
            $this->db->where('j.`id`', $params['job_id']);
        }
        if (isset($params['ps_service']) && $params['ps_service'] != '') {
            $this->db->where('ps.`service`', $params['ps_service']);
        }

        // agency filters
        if (isset($params['agency_filter']) && $params['agency_filter'] != '') {
            $this->db->where('a.`agency_id`', $params['agency_filter']);
        }
        if (isset($params['a_status'])) {
            $this->db->where('a.`status`', $params['a_status']);
        }
        if (isset($params['a_deleted'])) {
            $this->db->where('p.`agency_deleted`', $params['a_deleted']);
        }

        // Date filters
        if (isset($params['date']) && $params['date'] != '') {
            $this->db->where('p.`deleted_date`', $params['date']);
        }

        // is_nlm filter 0=active, 1=inactive/no longer managed
        // if the nlm param is not set, then we want to search for all active properties by getting only isnlm=0
        // if the nlm is set, its going to be set to 1 to therefore it will simply inverse the below isnlm=1
        if(!isset($params['is_nlm'])){
            $this->db->where("p.is_nlm != 1");
        }

        // search
        if (isset($params['search']) && $params['search'] != '') {
            $search_filter = "CONCAT_WS(' ', LOWER(p.address_1), LOWER(p.address_2), LOWER(p.address_3), LOWER(p.state), LOWER(p.postcode))";
            $this->db->like($search_filter, $params['search']);
        }

        // postcodes
        if (isset($params['postcodes']) && $params['postcodes'] != '') {
            $this->db->where("p.`postcode` IN ( {$params['postcodes']} )");
        }

        //state 
        if (isset($params['state_filter']) && $params['state_filter'] != '') {
            if($params['state_filter']==-1){
                $this->db->where("(p.state IS NULL OR p.state = '')");
            }else{
                $this->db->where('p.`state`', $params['state_filter']);
            }
            
        }

        //date filter - chops 
        
        if($params['next_services'] == 1){
            if ($params['date_filter_from'] == '' && $params['date_filter_to'] == '') {
                $next_30_days = date('Y-m-d',strtotime("+30 days"));
                $this->db->where('p.`retest_date` >=', $next_30_days);
            }
        
            if (isset($params['date_filter_from']) && $params['date_filter_from'] != '' && isset($params['date_filter_to']) && $params['date_filter_to'] != '') {
                $this->db->where('p.`retest_date` >=', $params['date_filter_from']);
                $this->db->where('p.`retest_date` <=', $params['date_filter_to']);
            }
        
            if (isset($params['date_filter_from']) && $params['date_filter_from'] != '' && $params['date_filter_to'] == '') {
                $this->db->where('p.`retest_date` >=', $params['date_filter_from']);
            }
        
            if (isset($params['date_filter_to']) && $params['date_filter_to'] != '' && $params['date_filter_from'] == '') {
                $this->db->where('p.`retest_date` <=', $params['date_filter_to']);
            }
        }

        if (is_numeric($params['country_id'])) {
            $this->db->where('a.`country_id`', $params['country_id']);
        }

        // Electrician Only(EO)	
        if ( is_numeric($params['is_eo']) ) {
            $this->db->where('j.`is_eo`', $params['is_eo']);
        }

        // salesrep
        if( is_numeric($params['salesrep']) && $params['salesrep'] > 0 ) {
            $this->db->where('a.`salesrep`', $params['salesrep']);
        }

        // custom filter
        if ( $params['custom_where'] != '' ) {
            $this->db->where($params['custom_where']);
        }

        // custom filter
        if (isset($params['custom_where_arr'])) {
            foreach ($params['custom_where_arr'] as $index => $custom_where) {
                if ($custom_where != '') {
                    $this->db->where($custom_where);
                }
            }
        }

        // group by
        if (isset($params['group_by']) && $params['group_by'] != '') {
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
        if (isset($params['limit']) && $params['limit'] > 0) {
            $this->db->limit($params['limit'], $params['offset']);
        }
        if(isset($params['count']) && $params['count']){
            $this->db->limit(1);
        }

        $query = $this->db->get();
        if (isset($params['display_query']) && $params['display_query'] == 1) {
            echo $this->db->last_query();
        }
        return $query;
    }

    public function getPropertyServices($params) {

        if (isset($params['sel_query'])) {
            $sel_query = $params['sel_query'];
        } else {
            $sel_query = '*';
        }

        $this->db->select($sel_query);
        $this->db->from('`property_services` AS ps');
        $this->db->join('`property` AS p', 'ps.`property_id` = p.`property_id`', 'left');
        $this->db->join('`agency` AS a', 'p.`agency_id` = a.`agency_id`', 'left');
        
        // set joins
        if ($params['join_table'] > 0) {

            foreach ($params['join_table'] as $join_table) {

                if ($join_table == 'alarm_job_type') {
                    $this->db->join('`alarm_job_type` AS ajt', 'ps.`alarm_job_type_id` = ajt.`id`', 'left');
                }

                if ($join_table == 'staff_accounts') {
                    $this->db->join('`staff_accounts` AS sa', 'sa.`StaffID` = a.`salesrep`', 'left');
                }

                if ($join_table == 'agency_priority') {
                    $this->db->join('`agency_priority` AS aght', 'a.`agency_id` = aght.`agency_id`', 'left');
                }
                
                if ($join_table == 'agency_priority_marker_definition') {
                    $this->db->join('`agency_priority_marker_definition` AS apmd', 'aght.`priority` = apmd.`priority`', 'left');
                }
            }
        }



        // custom joins
        if (isset($params['custom_joins']) && $params['custom_joins'] != '') {
            $this->db->join($params['custom_joins']['join_table'], $params['custom_joins']['join_on'], $params['custom_joins']['join_type']);
        }

        // filters
        // property
        if (isset($params['property_id'])) {
            $this->db->where('p.`property_id`', $params['property_id']);
        }
        if (isset($params['p_deleted'])) {
            $this->db->where('p.`deleted`', $params['p_deleted']);
            if(is_numeric($params['p_deleted']) && $params['p_deleted'] == 0){
                $this->db->where('( p.`is_nlm` = 0 OR p.`is_nlm` IS NULL )');
            }
        }else{
            $this->db->where('p.`deleted`', 0);
            $this->db->where('( p.`is_nlm` = 0 OR p.`is_nlm` IS NULL )');
        }
        if (isset($params['pm_id']) && $params['pm_id'] != '') {
            $this->db->where('p.`pm_id_new`', $params['pm_id']);
        }

        // agency filters
        if (isset($params['agency_filter']) && $params['agency_filter'] != '') {
            $this->db->where('a.`agency_id`', $params['agency_filter']);
        }
        if (isset($params['a_status'])) {
            $this->db->where('a.`status`', $params['a_status']);
        }

        // Date filters
        if (isset($params['date']) && $params['date'] != '') {
            $this->db->where('j.`date`', $params['date']);
        }

        //PS Service Status
        if (isset($params['ps_service']) && $params['ps_service'] != '') {
            $this->db->where('ps.`service`', $params['ps_service']);
        }

        // country ID
        if (is_numeric($params['country_id']) && $params['country_id'] > 0) {
            $this->db->where('a.`country_id`', $params['country_id']);
        }

        // salesrep
        if (is_numeric($params['salesrep']) && $params['salesrep'] > 0) {
            $this->db->where('a.`salesrep`', $params['salesrep']);
        }

        // ajt id
        if (isset($params['ajt_id']) && $params['ajt_id'] != '') {
            $this->db->where('ps.`alarm_job_type_id`', $params['ajt_id']);
        }

        // is payable?
        if ( is_numeric($params['is_payable']) ) {
            $this->db->where('ps.`is_payable`', $params['is_payable']);
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

        if (isset($params['is_nlm'])) {
            $this->db->where('is_nlm', $params['is_nlm']);
        }else{
            $tt_where = "(is_nlm=0 OR is_nlm IS NULL)";
            $this->db->where($tt_where);
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

    public function get_properties_with_active_services($params) {

        if (isset($params['sel_query'])) {
            $sel_query = $params['sel_query'];
        } else {
            $sel_query = '*';
        }

        $this->db->select($sel_query);
        $this->db->from('`property_services` AS ps');
        $this->db->join('`property` AS p', 'ps.`property_id` = p.`property_id`', 'left');
        $this->db->join('`agency` AS a', 'p.`agency_id` = a.`agency_id`', 'left');

        // filters
        // property
        if (isset($params['property_id'])) {
            $this->db->where('p.`property_id`', $params['property_id']);
        }
        if (isset($params['p_deleted'])) {
            $this->db->where('p.`deleted`', $params['p_deleted']);
        }
        if (isset($params['pm_id']) && $params['pm_id'] != '') {
            $this->db->where('p.`pm_id_new`', $params['pm_id']);
        }

        // agency filters
        if (isset($params['agency_filter']) && $params['agency_filter'] != '') {
            $this->db->where('a.`agency_id`', $params['agency_filter']);
        }
        if (isset($params['a_status'])) {
            $this->db->where('a.`status`', $params['a_status']);
        }

        // Date filters
        if (isset($params['date']) && $params['date'] != '') {
            $this->db->where('j.`date`', $params['date']);
        }

        //PS Service Status
        if (isset($params['ps_service']) && $params['ps_service'] != '') {
            $this->db->where('ps.`service`', $params['ps_service']);
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

    /**
     * Get New Tenant from new tenants table
     * params array
     * return query
     */
    public function get_new_tenants($params) {

        $this->db->select('pt.property_tenant_id, pt.property_id, pt.tenant_firstname, pt.tenant_lastname, pt.tenant_mobile, pt.tenant_landline, pt.tenant_email');
        $this->db->from('property_tenants as pt');

        if (!empty($params['property_id'])) {
            $this->db->where('property_id', $params['property_id']);
        }

        if (!empty($params['active'])) {
            $this->db->where('active', $params['active']);
        }

        if (!empty($params['limit'])) {
            $this->db->limit($params['limit']);
        }

        $query = $this->db->get();
        return $query;
    }

    /**
     * Update Tenant Details/Info/active/reactive
     */
    public function update_tenant_details($tenant_id, $data) {
        $this->db->where('property_tenant_id', $tenant_id);
        $this->db->update('property_tenants', $data);
        $this->db->limit(1);
        return ($this->db->affected_rows() > 0) ? true : false;
    }

    /**
     * ADD NEW TENANTS
     * Insert tenants by batch
     * param $data array
     * param $type normal/batch insert 
     */
    public function add_tenants($data, $type = NULL) {

        if ($type == "batch" && $type) { // type is set and = batch insert batch
            $this->db->insert_batch('property_tenants', $data);
            return ($this->db->affected_rows() > 0) ? true : false;
        } else { // type not set/normal insert normal
            $this->db->insert('property_tenants', $data);
            return ($this->db->affected_rows() > 0) ? true : false;
        }
    }

    /**
     * Update Property
     * @params property id
     * @params data array
     * return boolean
     */
    public function update_property($prop_id, $data) {

        $this->db->where('property_id', $prop_id);
        $this->db->update('property', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Update property services
     * @params property id
     * @params data array
     * return boolean
     */
    public function update_property_services($prop_id, $data) {
        $this->db->where('property_id', $prop_id);
        $this->db->update('property_services', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // property tenants
    public function get_property_tenants($params) {

		// If no valid parameters to narrow results returned, then return an empty query which wont break all of the ->result() usage.
        if(empty($params['property_id']) && empty($params['property_tenant_id']) && empty($params['custom_where'])){
			log_message('error', 'get_property_tenants was not provided valid parameters' . PHP_EOL . json_encode($params, JSON_PRETTY_PRINT) );
			return $this->db->query('select 1 from dual where false');
        }

		if (isset($params['sel_query'])) {
            $sel_query = $params['sel_query'];
        } else {
            $sel_query = '*';
        }

        $this->db->select($sel_query);
        $this->db->from('`property_tenants` AS pt');
        $this->db->join('`property` AS p', 'pt.`property_id` =  p.`property_id`', 'left');

        // set join
        if ($params['join_table'] > 0) {

            foreach ($params['join_table'] as $join_table) {

                if ($join_table == 'agency') {
                    $this->db->join('`agency` AS a', ' p.`agency_id` = a.`agency_id`', 'left');
                }
            }
        }

        // custom joins
        if (isset($params['custom_joins']) && $params['custom_joins'] != '') {
            $this->db->join($params['custom_joins']['join_table'], $params['custom_joins']['join_on'], $params['custom_joins']['join_type']);
        }

        // filter
        if (is_numeric($params['property_id'])) {
            $this->db->where('p.`property_id`', $params['property_id']);
        }
        if (is_numeric($params['p_deleted'])) {
            $this->db->where('p.`deleted`', $params['p_deleted']);
        }
        if (isset($params['a_status']) && $params['a_status'] != '') {
            $this->db->where('a.`status`', $params['a_status']);
        }
        if (is_numeric($params['country_id'])) {
            $this->db->where('a.`country_id`', $params['country_id']);
        }

        if (isset($params['agency_filter']) && $params['agency_filter'] != '') {
            $this->db->where('a.`agency_id`', $params['agency_filter']);
        }

        // State filters
        if (isset($params['state_filter']) && $params['state_filter'] != '') {
            $this->db->where('p.`state`', $params['state_filter']);
        }

        // Region filters
        if (isset($params['region_filter']) && $params['region_filter'] != '') {
            $this->db->where_in('p.`postcode`', $params['region_filter']);
        }

        //search
        if (isset($params['search']) && $params['search'] != '') {
            $search_filter = "CONCAT_WS(' ', LOWER(p.address_1), LOWER(p.address_2), LOWER(p.address_3), LOWER(p.state), LOWER(p.postcode))";
            $this->db->like($search_filter, $params['search']);
        }

        //search agency
        if (isset($params['search_agency']) && $params['search_agency'] != '') {
            $search_filter = "LOWER(a.agency_name)";
            $this->db->like($search_filter, $params['search_agency']);
        }

        // postcodes
        if (isset($params['postcodes']) && $params['postcodes'] != '') {
            $this->db->where("p.`postcode` IN ( {$params['postcodes']} )");
        }

        if ($params['property_tenant_id'] > 0) {
            $this->db->where('pt.`property_tenant_id`', $params['property_tenant_id']);
        }

        if ($params['pt_active'] !="") {
            $this->db->where('pt.`active`', $params['pt_active']);
        }

        // custom filter
        if (isset($params['custom_where'])) {
            $this->db->where($params['custom_where']);
        }

        // custom filter
        if (isset($params['custom_where_arr'])) {
            foreach ($params['custom_where_arr'] as $index => $custom_where) {
                if ($custom_where != '') {
                    $this->db->where($custom_where);
                }
            }
        }

        // group by
        if (isset($params['group_by']) && $params['group_by'] != '') {
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

        // custom filter
        if (isset($params['custom_sort'])) {
            $this->db->order_by($params['custom_sort']);
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

    function get_services($property_id, $alarm_job_type_id) {

        $ps_sql = $this->db->query("
			SELECT service
			FROM `property_services` 
			WHERE `property_id` = {$property_id}
			AND `alarm_job_type_id` = {$alarm_job_type_id}
		");

        if ($ps_sql->num_rows() > 0) {
            $s = $ps_sql->row_array();
            $service = $s['service'];
            switch ($service) {
                case 0:
                    $service = 'DIY';
                    break;
                case 1:
                    $service = config_item('company_name_short');
                    break;
                case 2:
                    $service = 'No Response';
                    break;
                case 3:
                    $service = 'Other Provider';
                    break;
            }
        } else {
            $service = "N/A";
        }

        return $service;
    }

    /**
     * Restore Property by property ID
     * @params Property ID
     * @parmas data array
     */
    public function restore_property($prop_id, $data) {

        $this->db->where('property_id', $prop_id);
        $this->db->update('property', $data);
        $this->db->limit(1);

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getCountryState() {
        $sql = "
            SELECT *
            FROM `states_def`
            WHERE `country_id` ={$this->config->item('country')}
        ";
        return $this->db->query($sql);
    }

    /**
     * Get PM 
     */
    public function get_agency_pm($params) {

        if (isset($params['sel_query'])) {
            $sel_query = $params['sel_query'];
        } else {
            $sel_query = '*';
        }

        $this->db->select($sel_query);
        $this->db->from('agency_user_accounts as aua');
        $this->db->join('agency_user_account_types as auat', 'auat.agency_user_account_type_id = aua.user_type', 'left');

        if ($params['active'] && !empty($params['active'])) {
            $this->db->where('aua.active', $params['active']);
        }

        if ($params['agency_id'] && !empty($params['agency_id'])) {
            $this->db->where('aua.agency_id', $params['agency_id']);
        }

        if ($params['user_type'] && !empty($params['user_type'])) {
            $this->db->where('aua.user_type', $params['user_type']);
        }

        // custom filter
        if (isset($params['custom_where'])) {
            $this->db->where($params['custom_where']);
        }

        // group by
        if (isset($params['group_by']) && $params['group_by'] != '') {
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
        if (isset($params['limit']) && $params['limit'] > 0) {
            $this->db->limit($params['limit'], $params['offset']);
        }

        $query = $this->db->get();
        if (isset($params['display_query']) && $params['display_query'] == 1) {
            echo $this->db->last_query();
        }

        return $query;
    }

    public function check_duplicate_property($address_1, $address_2, $address_3, $state, $postcode) {

        $address_1_escate = $this->db->escape_str($address_1);
        $address_2_escate = $this->db->escape_str($address_2);
        $address_3_escate = $this->db->escape_str($address_3);

        $duplicateQuery = "
            SELECT 
                p.`property_id`,
                p.`address_1` AS p_address_1,
                p.`address_2` AS p_address_2,
                p.`address_3` AS p_address_3,
                p.`state` AS p_state,
                p.`postcode` AS p_postcode,
                p.`deleted`,
                a.`agency_id`,
                a.`agency_name`
            FROM `property`AS p
            LEFT JOIN `agency` AS a ON p.`agency_id` = a.`agency_id`
            WHERE TRIM(LCASE(p.`address_1`)) = LCASE('" . $address_1_escate . "') 
            AND TRIM(LCASE(p.`address_2`)) = LCASE('" . $address_2_escate . "')
            AND TRIM(LCASE(p.`address_3`)) = LCASE('" . $address_3_escate . "') 
            AND TRIM(LCASE(p.`state`)) = LCASE('" . $this->db->escape_str($state) . "') 
            AND TRIM(LCASE(p.`postcode`)) = LCASE('" . $postcode . "');
        ";
        return $this->db->query($duplicateQuery);
    }

    public function check_duplicate_property_v2($params) {

        /*
          some address returns different suburb and postcode from the google object when matching with a property that has 2 address.
          sample property:
          10/2 Yulestar St Hamilton QLD 4007
          10/2 Yulestar St, Albion QLD 4010
         */

        $other_address = "{$params['suburb']} {$params['state']} {$params['postcode']}";

        $exist_in_crm_sql_str = "
            SELECT 
                `property_id`,
                `address_1`,
                `address_2`,
                `address_3`,
                `state`,
                `postcode`,
                `deleted`
            FROM `property`                                                          
            WHERE CONCAT_WS( ' ', TRIM(LOWER(address_3)), TRIM(LOWER(state)), TRIM(LOWER(postcode)) ) = '" . $this->db->escape_str(strtolower(trim($other_address))) . "'
            AND(
                TRIM(LOWER(address_1)) = '" . $this->db->escape_str(strtolower(trim($params['street_number_full']))) . "'
            )                                                                
            AND (
                TRIM(LOWER(address_2)) = '" . $this->db->escape_str(strtolower(trim($params['street_name']))) . "'
            )
            ORDER BY `address_2` ASC, `address_3` ASC, `address_1` ASC
        ";
        return $this->db->query($exist_in_crm_sql_str);
    }


    public function check_duplicate_full_address($params) {

        $street_num_fin_str = '';
        if( $params['street_num_fin'] != '' ){
            $street_num_fin_str = "{$params['street_num_fin']} ";
        }
        $full_address = "{$street_num_fin_str}{$params['street_name_fin']} {$params['suburb']} {$params['state']} {$params['postcode']}";

        $exist_in_crm_sql_str = "
            SELECT 
                p.`property_id`,
                p.`address_1` AS p_address_1,
                p.`address_2` AS p_address_2,
                p.`address_3` AS p_address_3,
                p.`state` AS p_state,
                p.`postcode` AS p_postcode,
                p.`deleted`,
                p.`is_nlm`,
                
                a.`agency_id`,
                a.`agency_name`
            FROM `property`AS p
            LEFT JOIN `agency` AS a ON p.`agency_id` = a.`agency_id`                                                     
            WHERE CONCAT_WS( ' ', TRIM(LOWER(p.`address_1`)), TRIM(LOWER(p.`address_2`)), TRIM(LOWER(p.`address_3`)), TRIM(LOWER(p.`state`)), TRIM(LOWER(p.`postcode`)) ) = '" . $this->db->escape_str(strtolower(trim($full_address))) . "'           
        ";
        return $this->db->query($exist_in_crm_sql_str);

    }

    public function add_property($data) {

        $this->db->insert('property', $data);
        $this->db->limit(1);

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    public function add_data_property($data) {

        $this->db->insert('api_property_data', $data);
        $this->db->limit(1);
    
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    //add property services 
    public function add_property_services($data) {

        $this->db->insert('property_services', $data);
        return ($this->db->affected_rows() > 0) ? true : false;
    }

    // add property type
    public function add_property_type($data) {

        $this->db->insert('property_propertytype', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Insert Jobs
     * return last insert id
     */
    public function add_jobs($data) {

        $this->db->insert('jobs', $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    /**
     * Insert Property Fiels
     * Return Boolean
     */
    public function isnert_property_files($data) {

        $this->db->insert('property_files', $data);
        $this->db->limit(1);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function nlm_property( $prop_id, $params = [] ) {

        $nlm_from_agency = $params['nlm_from_agency'];
        $agency_id = $params['agency_id'];
        $agency_status = $params['agency_status'];

        $proceed_nlm = false;
        $return = false;

        $this->load->model('jobs_model');
        
        if( $nlm_from_agency == true ){ // NLM from agency, skip active job check

            $proceed_nlm = true;

        }else{ // default, NLM from property

            $has_active_jobs = $this->system_model->NLMjobStatusCheck($prop_id);

            if( $has_active_jobs == false ){ // dont NLM if it has active jobs
                $proceed_nlm = true;
            }

        }
        
        if( $proceed_nlm == true ){ // NLM process below -------------         
            
            // leaving reason data
            $reason_they_left = $params['reason_they_left'];
            $other_reason = $params['other_reason'];

            if( is_numeric($reason_they_left) ){

				// insert agency leaving reason
				$agency_res_insert_data = array(
					'property_id' => $prop_id,
					'reason' => $reason_they_left
				);

				// "other" reason
				if( $reason_they_left == -1 ){
					$agency_res_insert_data['other_reason'] = $other_reason;
				}

				$this->db->insert('property_nlm_reason', $agency_res_insert_data);

			}

            $db_params = array(
                'agency_deleted' => 0,
                'booking_comments' => "No longer managed as of " . date('d/m/Y') . " - by SATS.",
                'is_nlm' => 1,
                'nlm_timestamp' => date('Y-m-d H:i:s'),                
                'nlm_by_sats_staff' => $this->session->staff_id
            );
    
            // check if property has money owing and needs to verify paid
            if( $this->system_model->check_verify_paid($prop_id) == true ){
                $db_params['nlm_display'] = 1;
            }
    
            $this->update_property($prop_id, $db_params);

            // unlink property
            if( $prop_id > 0 ){
                $this->db->delete('api_property_data', array('crm_prop_id' => $prop_id));
            }            
    
            // replaced "cancelled" update query to loop and cancel every job so logs can be inserted(as ben's instruction)
            // get all jobs except Completed and Cancelled
            $job_sql = $this->db->query("
            SELECT `id` AS jid, `status`
            FROM `jobs`
            WHERE `property_id` = {$prop_id}
            AND `status` NOT IN('Completed','Cancelled') 
            ");
    
            foreach( $job_sql->result() as $job_row ){
    
                if( $job_row->jid > 0 ){
                            
                    // update job to cancelled
                    $update_data = array(
                        'status' => 'Cancelled',
                        'comments' => "This property was marked No Longer Managed by SATS on " . date("d/m/Y") . " and all jobs cancelled",
                        'cancelled_date' => date('Y-m-d')
                    );                    
                    $this->db->where('id', $job_row->jid);
                    $this->db->where('property_id', $prop_id);
                    $this->db->update('jobs', $update_data);

                    // insert log
                    if( $nlm_from_agency == true ){ // when NLM was called from deactivating agency
                        
                        $log_details = "Job with status <b>{$job_row->status}</b> cancelled due to agency being marked <b>{$agency_status}/b>";
    
                    }else{ // default
                        
                        $log_details = "Job with status <b>{$job_row->status}</b> cancelled due to Property being marked NLM";
                    }
                    
                    $log_params = array(
                        'title' => 72,  // Job Status Updated
                        'details' => $log_details,
                        'display_in_vjd' => 1,
                        'created_by_staff' => $this->session->staff_id,
                        'job_id' => $job_row->jid
                    );
                    $this->system_model->insert_log($log_params);
    
                }                
    
            }
    
            
            //update property_services
            // if property has completed job with a price this month and service changed this month
            $this_month_start = date("Y-m-01");
            $this_month_end = date("Y-m-t");

            // get completed job this month
            $job_sql_str = "
            SELECT j.`id`
            FROM `jobs` AS j               
            WHERE j.`property_id` = {$prop_id}
            AND j.`status` = 'Completed'
            AND j.`job_price` > 0
            AND j.`date` BETWEEN '{$this_month_start}' AND '{$this_month_end}'                         
            ";
            $job_sql = $this->db->query($job_sql_str);

            // get status change this month
            $ps_sql_str = "
            SELECT ps.`status_changed`
            FROM `property` AS p 
            INNER JOIN `property_services` AS ps ON p.`property_id` = ps.`property_id`
            WHERE p.`property_id` = {$prop_id} 
            AND CAST( ps.`status_changed` AS DATE ) BETWEEN '{$this_month_start}' AND '{$this_month_end}'
            ";
            $ps_sql = $this->db->query($ps_sql_str);

            $clear_is_payable = null;
            $payable = '';
            if( $job_sql->num_rows() > 0 && $ps_sql->num_rows() > 0 ){

                // DO nothing, leave is_payable as it is

            }else{

                // clear is_payable
                $clear_is_payable = "`is_payable` = 0,";
                $payable = '0';

            }
            //update property_services end

            // loop through existing property services                
            $ps_sql2 = $this->db->query("
            SELECT 
                ps.`property_services_id` AS ps_id,
                ps.`is_payable`,
                ajt.`type` AS service_type_name 
            FROM `property_services` AS ps  
            LEFT JOIN `alarm_job_type` AS ajt ON ps.`alarm_job_type_id` = ajt.`id`              
            WHERE ps.`property_id` = {$prop_id}  
            AND ps.`service` NOT IN(0,3)
            AND ps.`service` = 1  
            ");

            foreach( $ps_sql2->result() as $ps_row2 ){

                if( $ps_row2->ps_id > 0 ){ 

                    $this->db->query("
                    UPDATE `property_services`
                    SET 
                        `service` = 2,
                        {$clear_is_payable}
                        `status_changed` = '".date('Y-m-d H:i:s')."'
                    WHERE `property_services_id` = {$ps_row2->ps_id}
                    AND `property_id` = {$prop_id}
                    ");

                    if ($payable == '0') {
                        $details =  "Property Service <b>{$ps_row2->service_type_name}</b> unmarked <b>payable</b>";
                        $params = array(
                            'title' => 3, // Property Service Updated
                            'details' => $details,
                            'display_in_vpd' => 1,									
                            'agency_id' => $agency_id,
                            'created_by_staff' => $this->session->staff_id,
                            'property_id' => $prop_id
                        );
                        $this->system_model->insert_log($params);
                    }
                }                    

            } 

            if( $this->db->affected_rows() > 0 ){

                // add log
                $property_log_params = array(
                    'title' => 3, // Property Service Updated
                    'details' => 'Service changed from <b>SATS</b> to <b>No Response</b> as the agency was deactivated',
                    'display_in_vpd' => 1,
                    'agency_id' => $agency_id,
                    'created_by_staff' => $this->session->staff_id,
                    'property_id' => $prop_id
                );
                $this->system_model->insert_log($property_log_params);

            }
    
            // Insert job log
            //get staff name
            $staff_params = array(
                'sel_query' => "FirstName,LastName",
                'staff_id' => $this->session->staff_id,
            );
            $staff_info = $this->gherxlib->getStaffInfo($staff_params)->row_array();
            $log_details = "No Longer Managed, By {$staff_info['FirstName']} {$staff_info['LastName']} ";
            $log_params = array(
                'title' => 6, //Property No Longer Managed 	
                'details' => $log_details,
                'display_in_vpd' => 1,
                'created_by_staff' => $this->session->staff_id,
                'property_id' => $prop_id,
            );
            $this->system_model->insert_log($log_params);
    
            if( $nlm_from_agency != true ){

                ## Gherx > Add NLM Email Notification
                if($this->config->item('country') == 1){ //email for AU only
                    $noti_params = array('property_id' => $prop_id);
                    $this->nlm_email_notification($noti_params);
                }

            }            
    
            $return = true;

        }

        return $return;

    }

    public function jFindDupProp($params) {

        if (isset($params['sel_query'])) {
            $sel_query = $params['sel_query'];
        } else {
            $sel_query = "*";
        }

        // query switch tweak in order to work agency filter
        if($params['agency_filter']>0){ //filter by agency_id

            if (is_numeric($params['offset']) && is_numeric($params['limit'])) {
                $pag_str .= " LIMIT {$params['offset']}, {$params['limit']} ";
            }

            $q = "
            SELECT `p`.`property_id`, `p`.`address_1`, `p`.`address_2`, `p`.`address_3`, `p`.`state`, `p`.`postcode`, `p`.`deleted`, `p2`.`agency_id`, `p2`.`agency_name`
            FROM property AS p
            LEFT JOIN (
                SELECT `p2`.`property_id`, COUNT( * ) AS jcount2, `a2`.`agency_id`, `a2`.`agency_name`
                FROM `property` as `p2`
                Left JOIN `agency` as `a2` ON `a2`.`agency_id` = `p2`.`agency_id`
                WHERE `p2`.`address_1` != ''
                AND `p2`.`address_2` != ''
                AND `p2`.`address_3` != ''
                AND `p2`.`is_sales` = 0
                AND p2.deleted = 0
                AND ( p2.`is_nlm` = 0 OR p2.`is_nlm` IS NULL )
                AND `a2`.`country_id` = {$this->config->item('country')}
                GROUP BY TRIM( p2.`address_1` ), TRIM( p2.`address_2` ), TRIM( p2.`address_3` ), TRIM( p2.`state` ), TRIM( p2.`postcode` )
                HAVING `jcount2` > 1
            ) AS p2 ON p.property_id = p2.property_id
            WHERE p2.agency_id = {$params['agency_filter']}
            {$pag_str}";

            $query = $this->db->query($q);

        }else{ // no filter 

            $this->db->select($sel_query);
            $this->db->from('property as p');
            $this->db->join('agency as a', 'a.agency_id = p.agency_id', 'left');
            $this->db->where("p.address_1!=", "");
            $this->db->where("p.address_2!=", "");
            $this->db->where("p.address_3!=", "");
            $this->db->where("p.is_sales", 0);        
            $this->db->where("p.deleted", 0);       
            $where = "(p.is_nlm IS NULL OR p.is_nlm = 0)"; 
            $this->db->where($where);
            $this->db->where('a.country_id', $this->config->item('country'));

            $group_by = "TRIM( p.`address_1` ) , TRIM( p.`address_2` ) , TRIM( p.`address_3` ) , TRIM( p.`state` ) , TRIM( p.`postcode` )";
            $this->db->group_by($group_by);

            $this->db->having('jcount >', 1);

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

        }

        return $query;

        
    }

    function jGetOtherDupProp($property_id, $address_1, $address_2, $address_3, $state, $postcode) {
        $query = $this->db->query("
			SELECT 
				p.property_id, 
				p.`address_1`, 
				p.`address_2`, 
				p.`address_3`, 
				p.`state`, 
				p.`postcode`, 
				p.`deleted`,
				
				a.`agency_id`,
				a.`agency_name`
			FROM `property` AS p 
			LEFT JOIN `agency` AS a ON p.`agency_id` = a.`agency_id`
			WHERE TRIM(LCASE(p.`address_1`)) = LCASE('" . $this->db->escape_str(trim($address_1)) . "') 
			  AND TRIM(LCASE(p.`address_2`)) = LCASE('" . $this->db->escape_str(trim($address_2)) . "') 
			  AND TRIM(LCASE(p.`address_3`)) = LCASE('" . $this->db->escape_str(trim($address_3)) . "') 
			  AND TRIM(LCASE(p.`state`)) = LCASE('" . $this->db->escape_str(trim($state)) . "') 
			  AND TRIM(LCASE(p.`postcode`)) = LCASE('" . $this->db->escape_str(trim($postcode)) . "')
            AND p.`property_id` != {$property_id}
            AND p.`is_sales` = 0
			AND a.`country_id` = {$this->config->item('country')};
        ");
        return $query;
    }

    function getPostcodeDuplicates() {

        // run comparison through all postcode
        $country_id = $this->config->item('country');
        $sql_str = "
			SELECT *
			FROM `postcode_regions` 
			WHERE `country_id` = {$country_id}
			AND `deleted` = 0
		";
        $sql = $this->db->query($sql_str);

        $duplicate = [];
        foreach ($sql->result_array() as $row) {

            // breakdown csv postcode, then compare to db postcode except itself
            $arr1 = explode(",", $row['postcode_region_postcodes']);
            $arr2 = array_filter($arr1);
            foreach ($arr2 as $pc) {

                $sql_str2 = "
				SELECT *
				FROM `postcode_regions` 
				WHERE `country_id` = {$country_id}
				AND `postcode_region_postcodes` LIKE '%{$pc}%'
				AND `postcode_region_id` != {$row['postcode_region_id']}
				AND `deleted` = 0
				";

                $sql2 = $this->db->query($sql_str2);
                if ($sql2->num_rows() > 0) {
                    $row2 = $sql2->row_array();

                    if (!in_array($pc, $duplicate)) {
                        $duplicate[] = $pc;
                    }
                }
            }
        }

        return $duplicate;
    }

    public function getPostcodeDuplicatesV2(){

        $params = array(
            'sel_query' => "sr.subregion_name as postcode_region_name, sr.sub_region_id as postcode_region_id, pc.postcode as postcode_region_postcodes",
            'delete' => 0        
        );
        $sql = $this->system_model->get_postcodes($params);

        $duplicate = [];
        foreach ($sql->result_array() as $row) {
           $pc = $row['postcode_region_postcodes'];
           $sub_region_id = $row['postcode_region_id'];

           $this->db->select('*');
           $this->db->from('postcode as pc');
           $this->db->join('sub_regions as sr','sr.sub_region_id = pc.sub_region_id','left');
           $this->db->where('pc.postcode', $pc);
           $this->db->where('sr.sub_region_id !=',$sub_region_id);
           $this->db->where('pc.deleted',0);
           $sql2 = $this->db->get();

           if ($sql2->num_rows() > 0) {

                if (!in_array($pc, $duplicate)) {
                    $duplicate[] = $pc;
                }

            }
        }
        return $duplicate;

    }

    function getPropertyNoAgency($start = 0, $limit = -1) {
        $this->db->select('*');
        $this->db->from('property');
        $this->db->where('agency_id=0');
        $this->db->order_by('tenant_ltr_sent', 'ASC');
        if ($limit >= 0) {
            $this->db->limit($limit, $start);
        }
        $query = $this->db->get();
        return $query;
    }

    function getPropertyNoAgencyCount() {
        $this->db->select('COUNT(*) as prop_count');
        $this->db->from('property');
        $this->db->where('agency_id=0');
        $this->db->order_by('tenant_ltr_sent', 'ASC');
        if ($limit >= 0) {
            $this->db->limit($limit, $start);
        }
        $query = $this->db->get();
        return $query;
    }

    public function get_properties_needs_verification($params) {

        if (isset($params['sel_query'])) {
            $sel_query = $params['sel_query'];
        } else {
            $sel_query = '*';
        }

        $this->db->select($sel_query);
        $this->db->from('properties_needs_verification AS pnv');
        $this->db->join('`property` AS p', 'pnv.`property_id` = p.`property_id` AND pnv.`property_source`=1', 'left');

        // PMe API properties needs it own agency ID field from pnv table bec it cannot get it from property table bec it doesnt belong to crm
        $this->db->join('`agency` AS a', 'pnv.`agency_id` = a.`agency_id`', 'inner');
        $this->db->join('agency_priority as aght', 'a.agency_id = aght.agency_id', 'left');
        $this->db->join('agency_priority_marker_definition as apmd', 'aght.priority = apmd.priority', 'left');

        if ($params['pnv_stat'] == 1) {
            $this->db->join('`api_property_data` AS apd', 'p.`property_id` = apd.`crm_prop_id`', 'left');
        }

        // custom joins
        if (isset($params['custom_joins']) && $params['custom_joins'] != '') {
            $this->db->join($params['custom_joins']['join_table'], $params['custom_joins']['join_on'], $params['custom_joins']['join_type']);
        }

        // filter
        if ($params['pnv_id'] > 0) {
            $this->db->where('pnv.`pnv_id`', $params['pnv_id']);
        }

        if (is_numeric($params['active'])) {
            $this->db->where('pnv.`active`', $params['active']);
        }

        if ($params['agency_id'] > 0) {
            $this->db->where('a.`agency_id`', $params['agency_id']);
        }

        if ($params['property_source'] > 0) {
            $this->db->where('pnv.`property_source`', $params['property_source']);
        }

        if ($params['property_id'] != '') {
            $this->db->where('pnv.`property_id`', $params['property_id']);
        }

        if (is_numeric($params['ignore_issue'])) {
            $this->db->where('pnv.`ignore_issue`', $params['ignore_issue']);
        }

        // custom filter
        if (isset($params['custom_where'])) {
            $this->db->where($params['custom_where']);
        }

        // custom filter
        if (isset($params['custom_where_arr'])) {
            foreach ($params['custom_where_arr'] as $index => $custom_where) {
                if ($custom_where != '') {
                    $this->db->where($custom_where);
                }
            }
        }

        // group by
        if (isset($params['group_by']) && $params['group_by'] != '') {
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

        // custom filter
        if (isset($params['custom_sort'])) {
            $this->db->order_by($params['custom_sort']);
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

    public function get_no_active_job_properties($select = null, $is_show = null, $offset=null, $limit=null) {
        if (is_numeric($offset) && is_numeric($limit)) {
            $str = " LIMIT {$offset}, {$limit}";
        }

        if ($select === "" || $select === null) {
            $select = "p.`property_id`, p.`address_1`, p.`address_2`, p.`address_3`, a.`agency_id`, a.`agency_name`, p.`created`, j.service as j_service, j.job_type as j_type, ajt.type as ajt_type, aght.priority, pa.`hidden`";
        }

        if ($is_show == 1) {
            // $is_show_str = "AND pa.`is_acknowledged` = {$is_show}";
            $is_show_str = "";
        } else {
            $is_show_str = "AND (pa.`hidden` IS  NULL OR pa.`hidden` = 0)";
        }        

        /*$sql = "
		SELECT $select
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
            AND (
            p.`is_nlm` =0
            OR p.`is_nlm` IS NULL
                        )
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
                    AND (
                    p.`is_nlm` =0
                    OR p.`is_nlm` IS NULL
                    )
                    AND DATE_FORMAT( j1.`date`,'%Y-%m' )
                    BETWEEN '" . $between_from . "' AND '" . $between_to . "'
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
            {$is_show_str}
            GROUP BY p.property_id
            ORDER BY p.property_id
            {$str}
	";*/

        $today = date('d');

        if( $today < 15 ){

            $plus_months_ts = strtotime("+1 month"); // plus 1 month
                        
        }else if( $today >= 15 && $this->config->item('country') == 2 ){

            $plus_months_ts = strtotime("+2 month"); // plus 2 month

        }

        $plus_months = date("Y-m-d",$plus_months_ts);	
        $between_from = date("Y-m", strtotime($plus_months.'-1 year'));
        $between_to = date("Y-m", $plus_months_ts);

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
            AND p.property_id NOT IN(
                SELECT property_id 
                FROM `hidden_properties` AS hp
                WHERE hp.hidden = 1
                AND hp.hidden_from_pages = 2
                AND DATE_FORMAT(NOW(), '%Y-%m-%d') <= DATE_FORMAT(DATE_ADD(date_created, INTERVAL 30 DAY), '%Y-%m-%d')
            )
            {$is_show_str}
            GROUP BY p.property_id
            ORDER BY p.property_id
            {$str}
            ";

        $query = $this->db->query($sql);

        // echo "<pre>";
        // echo $this->db->last_query();
        // exit;
        return $query;
    }

    public function nlm_email_notification($params){

        ##get property details
        $p_params = array(
            'sel_query'=> "p.property_id, p.address_1, p.address_2, p.address_3",
            'property_id' => $params['property_id'],
            'is_nlm' => 1
        );
        $prop_q = $this->get_properties($p_params);
        $prop_row = $prop_q->row_array();

        $email_data['prop_id'] = $prop_row['property_id'];
        $email_data['prop_name'] = "{$prop_row['address_1']} {$prop_row['address_2']}, {$prop_row['address_3']}";

        //email
        $this->email->to(make_email('accounts'));
        $this->email->subject("Property NLM Notification");
        $body = $this->load->view('emails/nlm_email.php', $email_data, TRUE);
        $this->email->message($body);
        $this->email->send();

    }

    public function get_properties_with_multiple_services($params) {

        $sel_query = "";
        if ($params['get_agencies']){
            $sel_query = "a.agency_name agency";
        } elseif ($params['get_states']){
            $sel_query = "p.state ";
        } else {
            $sel_query = "CONCAT(p.address_1, ' ', p.address_2,', ',p.address_3, ', ', p.state) address,
                      ps.property_id, 
                      pt.services AS property_services, 
                      a.agency_id,
                      a.agency_name,
                      aght.priority,
                      apmd.abbreviation,
                      asv.services AS agency_services";
        }

        $this->db->select($sel_query)
            ->from('(SELECT ps.property_id, COUNT(service) AS services
                    FROM property_services ps
                    WHERE ps.service = 1
                    GROUP BY ps.property_id
                    HAVING services > 1) AS ps')

            ->join('property AS p','p.property_id = ps.property_id')

            ->join('(SELECT ps.property_id , GROUP_CONCAT(ps.alarm_job_type_id) AS services
                    FROM property_services ps 
                    JOIN alarm_job_type AS aj ON aj.id = ps.alarm_job_type_id
                    WHERE ps.service = 1
                    GROUP BY ps.property_id
                    HAVING (2 NOT IN (services) AND 6 NOT IN (services)) OR 
                           (2 NOT IN (services) AND 15 NOT IN (services)) 
                    )  AS pt', 'pt.property_id = p.property_id')

            ->join('agency AS a','a.agency_id = p.agency_id')

            ->join('agency_priority AS aght','a.agency_id = aght.agency_id', 'LEFT')
            ->join('agency_priority_marker_definition AS apmd','aght.priority = apmd.priority', 'LEFT')

            ->join('(SELECT a.agency_id, GROUP_CONCAT(a.service_id) AS services 
                        FROM agency_services a 
                        GROUP BY agency_id) AS asv','a.agency_id = asv.agency_id')
            ->where('(p.is_nlm IS NULL OR p.is_nlm = 0)')
            ->where('p.deleted', 0)
            ->where('a.status', 'active');
        
        // state filter
        if ($params['state_filter']){
            $this->db->where('p.state', $params['state_filter']);
        }

        // agency filter
        if ($params['agency_filter']) {
            $this->db->where('a.agency_name', $params['agency_filter']);
        }

        // get agencies or states
        if ($params['get_agencies'] || $params['get_states']){
            return $this->db->get()->result(); 
        }

        // search filter
        if ($params['search_filter']){
            $this->db->having('(a.agency_name LIKE "%' . $params['search_filter'] . '%" 
                     OR address LIKE "%' . $params['search_filter'] . '%"  )');
        }

        // limit
        if (isset($params['limit']) && $params['limit'] > 0) {
            $this->db->limit($params['limit'], $params['offset']);
        }

        if ($params['total_rows']){
                return $this->db->get()->num_rows(); 
        }
            
        return $this->db->get()->result();               
    }

    //Get Agency Name
	public function getAgencyName($agency_id, $country_id){

        $this->db->select('agency_name');
        $this->db->from('agency');
        $this->db->where('agency_id', $agency_id);

        // country filter
        if ($country_id == 1) {
            $this->db->where('franchise_groups_id', 10);
        }

        if ($country_id == 2) {
            $this->db->where('franchise_groups_id', 37);
        }

        return $this->db->get()->result();   

    }

    public function payableCheck($property_id){
        // clear is_payable conditions, must be placed before property update bec nlm_timestamp gets cleared	
		$this_month_start = date("Y-m-01");
		$this_month_end = date("Y-m-t");

		$sixty_days_ago = date("Y-m-d",strtotime("-61 days"));

		// get NLM date
        $this->db->select('nlm_timestamp');
        $this->db->from('property');
        $this->db->where('property_id', $property_id);
        $data = $this->db->get()->result();

        $tmp_date = $data[0]->nlm_timestamp;

        $nlm_date = date('Y-m-d',strtotime($tmp_date));
        //$nlm_date = "2022-03-25";

        /*
        Month Start: 2022-04-01
        Month End: 2022-04-30
        Month Ago: 2022-02-18
        NLM Date: 2022-04-25
        */

		// if status change is within 60 days ago but not within this month
        if(  $nlm_date > $sixty_days_ago && !( $nlm_date >= $this_month_start && $nlm_date <= $this_month_end ) ){

            //echo "IF";
            //exit();

			// clear is_payable
            $updateService = array(
                'is_payable' => 0,
                'service'   => 2
            );

            $this->db->where('property_id', $property_id);
            $this->db->update('property_services', $updateService);

            if($this->db->affected_rows()>0){
                return true;
            }
            else{
                return false;
            }

		}
        else{
            //echo "ELSE";
            //exit();
            /*
			// update active service to is_payable to 1 and updated status changed to today

            $this->db->select('ajt.`type` AS ajt_type_name');
            $this->db->from('`property_services` as ps');
            $this->db->join('`alarm_job_type` AS ajt','ps.`alarm_job_type_id` = ajt.`id`','left');
            $this->db->where('ps.`property_id`', $property_id);
            $ps_tt_sql = $this->db->get()->result();
            
            //print_r($ps_tt_sql);
            //exit();

            ## Al > add is_payable log
			$mark_unmark = "marked";
            foreach ($ps_tt_sql as $val) {

                $logData = array(
                    'property_id' => $property_id,
                    'staff_id' => $_SESSION['staff_id'],
                    'event_type' => 'Property Sales Commission',
                    'event_details' => 'Property Service <b>'.$val->ajt_type_name.'</b> ' .$mark_unmark. ' <b>payable</b>',
                    'log_date' => date('Y-m-d H:i:s'),
                    'hide_delete' => 1
                );
                $this->db->insert('property_event_log', $logData);
            }
            */
            
            // set is_payable
            $updateService = array(
                'is_payable' => 1,
                'status_changed' => date('Y-m-d H:i:s')
            );

            $this->db->where('property_id', $property_id);
            $this->db->where('service', 1);
            $this->db->update('property_services', $updateService);

            if($this->db->affected_rows()>0){
                return true;
            }
            else{
                return false;
            }
		}
    }

    //Check property if from API
	public function apiCheck($property_id){

        $this->db->select('api');
        $this->db->from('api_property_data');
        $this->db->where('crm_prop_id', $property_id);

        return $this->db->get()->result();   

    }

    //Check property if from API
	public function get_connected_pme_properties($agency_id){
        $this->db->select('p.`property_id`, apd.`api`, apd.`api_prop_id`, p.`agency_id`, p.`is_nlm`, p.`address_1`, p.`address_2`, p.`address_3`, p.`state`, p.`postcode`');
        $this->db->from('property as p');
        $this->db->join('`api_property_data` AS apd', 'p.`property_id` = apd.`crm_prop_id`', 'left');
        $this->db->where('p.`agency_id`', $agency_id);
        $this->db->where('apd.`api_prop_id` !=', NULL);

        return $this->db->get()->result();   

    }

    public function get_unlinked_api_properties($params){

        $pag_str = "";
        
        if (isset($params['sel_query'])) {
            $sel_query = $params['sel_query'];
        } else {
            $sel_query = '*';
        }

        if( $params['custom_where'] && $params['custom_where']!="" ){
            $custom_where = $params['custom_where'];
        }

        if (is_numeric($params['offset']) && is_numeric($params['limit'])) {
            $pag_str .= " LIMIT {$params['offset']}, {$params['limit']} ";
        }

        return $this->db->query("
            SELECT {$sel_query}
            FROM `property` AS `p`
            LEFT JOIN `agency` AS `a` ON p.`agency_id` = a.`agency_id`
            LEFT JOIN `agency_api_tokens` as `apt` ON `apt`.`agency_id` = `p`.`agency_id`
            LEFT JOIN `agency_api` as `aapi` ON `apt`.`api_id` = `aapi`.`agency_api_id`
            LEFT JOIN `api_property_data` as `apd` ON `p`.`property_id` = `apd`.`crm_prop_id`
            LEFT JOIN `property_cant_connect_to_api` AS pccta ON p.`property_id` = pccta.`property_id`
            LEFT JOIN `properties_needs_verification` AS pnv ON p.`property_id` = pnv.`property_id`
            WHERE `p`.`deleted` = 0
            AND `a`.`status` = 'active'
            AND `p`.`agency_deleted` = 0
            AND (`p`.`is_nlm` =0 OR `p`.`is_nlm` IS NULL)
            AND apt.agency_api_token_id IS NOT NULL
            AND apd.api_prop_id IS NULL 
            AND (apd.active IS NULL OR apd.active = 1)
            AND (
                pccta.`pccta_id` IS NULL OR
                pccta.`pccta_id` = ''
            )
            AND (
                pnv.`property_id` IS NULL OR
                pnv.`property_id` = ''
            )
            {$custom_where}
            {$pag_str}
        ");
    }

    public function get_update_property_variation($agency_id, $offset = null, $per_page = null)
    {
        $this->db->select("
            a.agency_id,
            a.agency_name,
            aght.priority,
            p.property_id,
            p.address_1,
            p.address_2,
            p.address_3,
            p.state,
            p.postcode,
            p.qld_new_leg_alarm_num            
        ");

        $this->db->from("property as p");
        $this->db->join("agency as a", "p.agency_id = a.agency_id", "left");
        $this->db->join("agency_priority as aght", "a.agency_id = aght.agency_id", "left");
        $this->db->where("p.deleted", 0);
        $this->db->group_start();
        $this->db->where("p.is_nlm", 0);
        $this->db->or_where("p.is_nlm IS NULL");
        $this->db->group_end();

        if ($agency_id){
            $this->db->where("a.agency_id", $agency_id);
        }

        return $this->db->get()->result();
    }

    public function get_property_service_price($property_id)
    {
        $this->db->select("property_services_id, price");
        $this->db->from("property_services");
        $this->db->where("property_id", $property_id);
        
        return $this->db->get();
    }

    public function get_property_current_variation($property_id)
    {    
        $this->db->select("
            pv.agency_price_variation,
            apv.amount,
            apv.type,
            apvr.reason
        ");

        $this->db->from("property_variation as pv");
        $this->db->join("agency_price_variation as apv", "pv.agency_price_variation = apv.id", "left");
        $this->db->join("agency_price_variation_reason as apvr", "apv.reason = apvr.id", "left");
        $this->db->where("pv.property_id", $property_id);
        $this->db->where("pv.active", 1);

        return $this->db->get();
    }

    public function update_coordinates($params){

        $property_id = $params['property_id'];
        $acco_id = $params['acco_id'];

        if( $property_id > 0 ){ // property

            $prop_sql = $this->db->query("
            SELECT 
                `property_id`, 
                `address_1` AS p_address_1, 
                `address_2` AS p_address_2, 
                `address_3` AS p_address_3, 
                `state` AS p_state, 
                `postcode` AS p_postcode, 
                `lat`,
                `lng`
            FROM `property`
            WHERE `property_id` = {$property_id}
            ");            

            if( $prop_sql->num_rows() > 0 ){

                $prop_row = $prop_sql->row();
                $prop_address = "{$prop_row->p_address_1} {$prop_row->p_address_2} {$prop_row->p_address_3} {$prop_row->p_state} {$prop_row->p_postcode}";   

                if( ( $prop_row->lat == "" || $prop_row->lng == "" ) && $prop_address != '' ){
                    
                    $coordinate = $this->system_model->getGoogleMapCoordinates($prop_address);	                            

                    if( $property_id > 0 && $coordinate['lat'] != '' && $coordinate['lng'] != '' ){

                        // update lat lng
                        $update_data = array(
                            'lat' => $coordinate['lat'],
                            'lng' => $coordinate['lng']
                        );  
                        $this->db->where('property_id', $property_id);            
                        $this->db->update('property', $update_data);

                    }
                    
                }

            }            

        }else if( $acco_id > 0 ){ // accomodation

            $acco_sql = $this->db->query("
            SELECT 
                `lat`,
                `lng`,
                `address`
            FROM `accomodation`
            WHERE `accomodation_id` = {$acco_id}
            ");            

            if( $acco_sql->num_rows() > 0 ){

                $acco_row = $acco_sql->row();

                if( ( $acco_row->lat == "" || $acco_row->lng == "" ) && $acco_row->address != '' ){
                    
                    $coordinate = $this->system_model->getGoogleMapCoordinates($acco_row->address);	                            

                    if( $property_id > 0 && $coordinate['lat'] != '' && $coordinate['lng'] != '' ){

                        // update lat lng
                        $update_data = array(
                            'lat' => $coordinate['lat'],
                            'lng' => $coordinate['lng']
                        );  
                        $this->db->where('accomodation_id', $acco_id);            
                        $this->db->update('accomodation', $update_data);

                    }
                    
                }

            }            

        }        

    }

    public function restore_property_vpd($property_id,$del_tenant){
        if( $del_tenant==1  && $property_id > 0 ){

            // clear tenants
            $update_data = array(
                'active' => '0'
            );                    
            $this->db->where('active', 1);
            $this->db->where('property_id', $property_id);
            $this->db->update('property_tenants', $update_data);
    
       }
       if( $property_id > 0 ){

        $log_detail = '';
		$query = $this->db->query("
		SELECT ps.service, ajt.type, ps.is_payable, ps.property_services_id 
            FROM property_services AS ps 
            INNER JOIN `alarm_job_type` AS ajt ON ps.`alarm_job_type_id` = ajt.`id` 
            WHERE property_id = $property_id ORDER BY ps.property_services_id DESC LIMIT 1
		");
		$data = $query->row();
		$this->db->query("
			UPDATE property_services
			SET
				service=2
			WHERE `property_id`={$property_id} AND property_services_id = {$data->property_services_id};
			");
		$log_detail = '| '.$data->type.' Service updated from <strong>SATS</strong> to <strong>No Response</strong>';
		// clear is_payable conditions, must be placed before property update bec nlm_timestamp gets cleared	
		$this_month_start = date("Y-m-01");
		$this_month_end = date("Y-m-t");
		$is_payable_log = '';

		$sixty_days_ago = date("Y-m-d",strtotime("-61 days"));

		// get NLM date
		$prop_sql_str = $this->db->query("
		SELECT `nlm_timestamp`
		FROM `property`
		WHERE `property_id`= {$property_id}
		");		
		$prop_row = $prop_sql_str->row();
		$nlm_date = date('Y-m-d',strtotime($prop_row->nlm_timestamp));

		// if status change is within 60 days ago but not within this month
		if(  $nlm_date > $sixty_days_ago && !( $nlm_date >= $this_month_start && $nlm_date <= $this_month_end ) ){
			// clear is_payable
			$this->db->query("
			UPDATE `property_services`
			SET `is_payable` = 0   
			WHERE `property_id` = {$property_id}            
			");
			$is_payable_log = '| Property unmarked <strong>payable</strong>';

		}else{

                // update active service to is_payable to 1 and updated status changed to today

                ##al: get current PS values
                $ps_tt_sql = $this->db->query("
                SELECT ajt.`type` AS ajt_type_name
                FROM `property_services` as ps
                LEFT JOIN `alarm_job_type` AS ajt ON ps.`alarm_job_type_id` = ajt.`id`
                WHERE ps.`property_id` = {$property_id}
                AND ps.`service` = 1  
                ");

                $this->db->query("
                UPDATE `property_services`
                SET 
                    `is_payable` = 1,
                    `status_changed` = '".date('Y-m-d H:i:s')."'   
                WHERE `property_id` = {$property_id}   
                AND `service` = 1   
                ");

                ## Al > add is_payable log
                $mark_unmark = "marked";
                foreach( $ps_tt_sql->result() as $ps_tt_sql_row ){

                    $this->db->query("
                    INSERT INTO
                    `property_event_log`
                    (
                        `property_id`,
                        `staff_id`,
                        `event_type`,
                        `event_details`,
                        `log_date`,
                        `hide_delete`
                    )
                    VALUES(
                        {$property_id},
                        {$this->session->staff_id},
                        'Property Sales Commission',
                        'Property Service <b>{$ps_tt_sql_row->ajt_type_name}</b> {$mark_unmark} <b>payable</b>',
                        '".date('Y-m-d H:i:s')."',
                        1
                    )
                    ");

                }
                ## Al > add is_payable log end

            }
            $this->db->query("
            UPDATE property 
            SET 
                deleted=0, 
                agency_deleted=0, 
                `is_nlm` = 0, 
                `nlm_display` = NULL, 
                `nlm_timestamp` = NULL, 
                `nlm_by_sats_staff` = NULL, 
                `nlm_by_agency` = NULL
            WHERE `property_id`={$property_id};
            ");

        }

            $staff_id = $this->session->staff_id;
            
            $sa_sql = $this->db->query("
                SELECT *
                FROM `staff_accounts`
                WHERE `StaffID` ={$staff_id}
            ");
            $sa_row = $sa_sql->row();
            
            $this->db->query("INSERT INTO property_event_log (property_id, staff_id, event_type, event_details, log_date) 
                            VALUES (".$property_id.", ".$staff_id.", 'Property Restored', 'By {$sa_row->FirstName} {$sa_row->LastName} {$log_detail} {$is_payable_log}', NOW())");

            ##New > clear propery api id from new generic table
            $this->db->query("
            UPDATE api_property_data 
            SET 
                api_prop_id= NULL, 
                active=0
            WHERE `crm_prop_id`={$property_id};
            ");

    }

    public function get_properties_files($property_id){
        return $this->db->query("
            SELECT 
                pf.property_files_id,
                pf.property_id,
                pf.path,
                pf.filename
            FROM `property_files` AS pf
            WHERE pf.active = 1 AND property_id = {$property_id}
        ");
    }

    public function get_properties_old_logs($property_id){
        return $this->db->query("
            SELECT
                pl.log_date,
                pl.event_type,
                sa.FirstName,
                sa.LastName,
                pl.event_details
            FROM property_event_log AS pl
            LEFT JOIN `staff_accounts` AS sa ON pl.`staff_id` = sa.`StaffID`
            WHERE pl.property_id = {$property_id}
            ORDER BY pl.`id` DESC
        ");
    }

    public function get_properties_new_logs($params){
        if (isset($params['offset']) && isset($params['limit'])) {
            $pag_str .= " LIMIT {$params['offset']}, {$params['limit']} ";
        } elseif(isset($params['limit'])){
            $pag_str .= " LIMIT {$params['limit']} ";
        }
        
        return $this->db->query("
            SELECT 
                l.`log_id`,
                l.`created_date`,
                l.`title`,
                l.`details`,
                l.`auto_process`,
                l.`important`,

                ltit.`title_name`,

                aua.`fname`,
                aua.`lname`,
                aua.`photo`,

                sa.`StaffID`,
                sa.`FirstName`,
                sa.`LastName`
            FROM `logs` AS l
            LEFT JOIN `log_titles` AS ltit ON l.`title` = ltit.`log_title_id`
            LEFT JOIN `agency_user_accounts` AS aua ON l.`created_by` = aua.`agency_user_account_id`
            LEFT JOIN `staff_accounts` AS sa ON l.`created_by_staff` = sa.`StaffID`
            WHERE l.property_id = {$params['property_id']}
            ORDER BY l.`created_date` DESC
            {$pag_str}
        ");
			
    }

    public function get_properties_services($property_id){
        return $this->db->query("
        SELECT 
            ajt.id AS ajt_id,
            p.property_id,
            ajt.type,
            ps.price,
            ps.service_id
        FROM `agency_services` AS ps
        LEFT JOIN `alarm_job_type` AS ajt ON ps.`service_id` = ajt.`id`
        LEFT JOIN `property` AS p ON ps.`agency_id` = p.`agency_id`
        WHERE p.`property_id` = {$property_id}
        AND ajt.`active` =1
        ORDER BY `agency_services_id` ASC
        ");
    }

    public function service_to_sats_sql_str(){
        return $this->db->query("
                SELECT ps.`alarm_job_type_id`, ps.`service`
                FROM `property` AS p 
                INNER JOIN `property_services` AS ps ON p.`property_id` = ps.`property_id`
                LEFT JOIN `agency` AS a ON p.`agency_id` = a.`agency_id`
                INNER JOIN `agency_services` AS agen_serv ON ( a.`agency_id` = agen_serv.`agency_id` AND ps.`alarm_job_type_id` = agen_serv.`service_id` )
                WHERE p.`property_id` = 92
                AND ps.`service` = 1
                ");
    }

    public function price_increase_excluded_agency($property_id){
        return $this->db->query("
            SELECT 
                pie.agency_id,
                pie.exclude_until
            FROM `price_increase_excluded_agency` AS pie
            LEFT JOIN property AS p on pie.agency_id = p.agency_id
            WHERE p.`property_id` = {$property_id}                  
            AND (
                `exclude_until` >= '".date('Y-m-d')."' OR
                `exclude_until` IS NULL
            )
        ");
    }

    public function agen_serv_sql($property_id,$agency_id){
        return $this->db->query("
            SELECT 
                agen_serv.`price`,

                ajt.`id` AS ajt_id,
                ajt.`type` AS ajt_type
            FROM `agency_services` AS agen_serv
            LEFT JOIN `alarm_job_type` AS ajt ON agen_serv.`service_id` = ajt.`id`
            WHERE agen_serv.`agency_id` = {$agency_id}
            AND agen_serv.`service_id` NOT IN(
                SELECT `alarm_job_type_id`
                FROM `property_services` 
                WHERE `property_id` = {$property_id}
                AND `service` = 1
            )	
        "); 
    }

    public function vpd_change_service($params){
        $property_id = $params['property_id'];
        $agency_id = $params['agency_id'];
        $from_service_type = $params['from_service_type'];
        $to_service_type = $params['to_service_type'];

        $today = date('Y-m-d H:i:s');
        $this_month_start = date("Y-m-01");
        $this_month_end = date("Y-m-t");

        // check if IC service type is availble on agency
        $agency_serv_sql = $this->db->query("
        SELECT 
            `agency_services_id`,
            `price`
        FROM `agency_services` 
        WHERE `agency_id` = {$agency_id}
        AND `service_id` = {$to_service_type}
        ");

        if( $agency_serv_sql->num_rows() > 0 ){

            $agency_serv_row = $agency_serv_sql->row_array();
            $agency_serv_price = $agency_serv_row['price']; // agency service price       

            if( $to_service_type > 0 ){
                
                // get status changed date          
                $ps_sql = $this->db->query("
                SELECT `status_changed` 
                FROM `property_services`
                WHERE `alarm_job_type_id` = {$from_service_type} 
                AND `property_id` = {$property_id}  
                ");        
                $ps_sql_row = $ps_sql->row();
                $status_changed = date('Y-m-d',strtotime($ps_sql_row->status_changed));

                // if status changed is within the current month its payable
                $is_payable = ( $status_changed >= $this_month_start && $status_changed <= $this_month_end )?1:0;          
                
                // update service
                $service_to = 1; // SATS

                // clear, this will also fix issues on duplicates
                $this->db->query("
                DELETE 
                FROM `property_services`
                WHERE `alarm_job_type_id` = {$from_service_type} 
                AND `property_id` = {$property_id}  
                ");

                $this->db->query("
                DELETE 
                FROM `property_services`
                WHERE `alarm_job_type_id` = {$to_service_type} 
                AND `property_id` = {$property_id}  
                ");

                // insert service type
                $insert_serv_type_sql_str = $this->db->query("
                INSERT INTO
                `property_services` (
                    `property_id`,
                    `alarm_job_type_id`,
                    `service`,
                    `price`,
                    `status_changed`,
                    `is_payable`
                )
                VALUE(
                    {$property_id},
                    {$to_service_type},
                    {$service_to},
                    {$agency_serv_price},
                    '{$today}',
                    {$is_payable}
                )       
                ");  
                
                
                // from service type
                $ajt_sql = $this->db->query("
                SELECT `type`
                FROM `alarm_job_type`
                WHERE `id` = {$from_service_type}
                ");
                $ajt_row = $ajt_sql->row_array();
                $service_type_from = $ajt_row['type'];

                // to service type
                $ajt_sql = $this->db->query("
                SELECT `type`
                FROM `alarm_job_type`
                WHERE `id` = {$to_service_type}
                ");
                $ajt_row = $ajt_sql->row_array();
                $service_type_to = $ajt_row['type'];
                
                // insert property log
                $details = "Property Service Updated from <b>{$service_type_from}</b> to <b>{$service_type_to}</b>";
                $log_params = array(
                    'title' => 3, // property service update
                    'details' => $details,
                    'display_in_vpd' => 1,
                    'created_by_staff' => $this->session->staff_id,
                    'property_id' => $property_id
                );
                $this->system_model->insert_log($log_params);

            } 


        }
    }

    public function agency_id_query($property_id){
        return $this->db->query("
            SELECT agency_id, prop_upgraded_to_ic_sa, state
            FROM `property`
            WHERE `property_id` = {$property_id}
        "); 
    }

    public function get_quotes_new_name($alarm_pwr_id){

        $sel = "
            SELECT ap.`alarm_make`, qa.`title`
            FROM `alarm_pwr` as ap
            LEFT JOIN `quote_alarms` AS qa ON ap.`alarm_pwr_id` = qa.`alarm_pwr_id`
            WHERE ap.`alarm_pwr_id` = $alarm_pwr_id
        ";
        $sql = $this->db->query($sel); 
        $row = $sql->row_array();

        if( $row['title'] != "" ){
            return $row['title'];
        }else{
            return $row['alarm_make'];
        }

    }

    public function plog_sql_str($property_id){
        return $this->db->query("
            SELECT
                j.`id` AS jid,
                j.`created`,
                j.`job_type`,
                j.`service` AS jservice,
                j.`date` AS jdate,
                j.`status` AS jstatus,
                j.`assigned_tech`,
                j.`job_price`,
                j.`property_id`,
                j.`del_job`,

                p.`prop_upgraded_to_ic_sa`
            FROM `jobs` AS j
            LEFT JOIN `property` AS p ON j.`property_id` = p.`property_id`
            WHERE j.`property_id` = {$property_id}
            AND j.`del_job` = 0
            AND (j.assigned_tech != 2 OR j.assigned_tech IS NULL )
            ORDER BY j.`date` DESC
        "); 
    }

    public function inv_his_sql($property_id){
        return $this->db->query("
            SELECT
                j.`id` AS jid,
                j.`created`,
                j.`job_type`,
                j.`service` AS jservice,
                j.`date` AS jdate,
                j.`status` AS jstatus,
                j.`assigned_tech`,
                j.`job_price`,
                j.`property_id`,
                j.`del_job`,

                p.`prop_upgraded_to_ic_sa`
            FROM `jobs` AS j
            LEFT JOIN `property` AS p ON j.`property_id` = p.`property_id`
            LEFT JOIN
            staff_accounts
            ON 
                j.assigned_tech = staff_accounts.StaffID
            WHERE j.`property_id` = {$property_id}
            AND j.`del_job` = 0
            AND j.assigned_tech = 2
            ORDER BY j.`date` DESC
        "); 
    }

    public function create_jobs_vpd($property_id){
        $p_sql = $this->db->query("
        SELECT state, holiday_rental
        FROM `property` AS p
        WHERE p.property_id = {$property_id}
        "); 
        $p_row = $p_sql->row();


        $ps_sql2 = $this->db->query("
        SELECT COUNT(ps.`property_services_id`) AS ps_count
        FROM `property_services` AS ps
        LEFT JOIN `alarm_job_type` AS ajt ON ps.`alarm_job_type_id` = ajt.`id`
        WHERE ps.`property_id` = {$property_id}
        AND ajt.`is_ic` = 1
        AND ps.`service` = 1
        "); 
        $ps_row2 = $ps_sql2->row();

        // jobs
        $jobs_sql2 = $this->db->query("
        SELECT COUNT(`id`) AS j_count
        FROM `jobs`
        WHERE `property_id` = {$property_id}
        AND `job_type` = 'IC Upgrade' 
        AND `status` = 'Completed'
        "); 
        $jobs_row2 = $jobs_sql2->row();

        if( $ps_row2->ps_count > 0 && $jobs_row2->j_count > 0 ){

            $jt_Sql_filter = "WHERE `job_type` != 'IC Upgrade'";

        }else{

            // non QLD dont show Lease Renewal and IC Upgrade
            $jt_Sql_filter = null;
            if( $p_row->state != 'QLD' ){ // non QLD

                if( $p_row->state == 'NSW' ){ // NSW
                    
                    if( $p_row->holiday_rental != 1 ){ // for non short term rental, dont show 'IC Upgrade'
                        $jt_Sql_filter = "WHERE `job_type` != 'IC Upgrade'";
                    }													

                }else{ // non NSW

                    $jt_Sql_filter = "WHERE NOT `job_type` IN('Lease Renewal','IC Upgrade')";

                }																								

            }
            
        }
                                                                                                    

        return $this->db->query("
            SELECT *
            FROM `job_type`
            {$jt_Sql_filter}
            ORDER BY `job_type` ASC
        ");
    }

    public function add_jobs_vpd($params){
        $property_id = $params['property_id'];
        $alarm_job_type_id = $params['alarm_job_type_id'];
        $job_type = $params['job_type'];
        $price = $params['price'];

        $vacant_from = $params['vacant_from'];
        $vacant_from2 = ($vacant_from!="")?$vacant_from:'';
        $new_ten_start = $params['new_ten_start'];
        $new_ten_start2 = ($new_ten_start!="")?$new_ten_start:'';
        $problem = $params['problem'];
        $agency_id = $params['agency_id'];
        $workorder_notes = $params['workorder_notes'];
        $comments = "";

        $workorder_notes = $params['workorder_notes'];

        $onhold_start_date = $params['onhold_start_date'];
        $onhold_end_date = $params['onhold_end_date'];

        $job_date = $params['job_date'];

        $jtech_sel = $params['jtech_sel'];

        $work_order = (!empty($params['work_order'])) ? $params['work_order'] : 'NULL' ;

        $preferred_alarm_id = $params['preferred_alarm_id'];
        $vacant_prop = $params['vacant_prop'];

        $end_date_str = 'NULL';
        $start_date_str = 'NULL';
        $job_date_date_str = 'NULL';



        switch($job_type){
            case 'Once-off':
                $comments = "{$job_type}";
            break;
            case 'Change of Tenancy':
                
                if( $vacant_from!="" ){
                    $start_date = date('Y-m-d',strtotime(str_replace('/','-',$vacant_from)));
                    $start_date_str = "'{$start_date}'";
                }else{
                    $start_date_str = 'NULL';
                }

                if( $new_ten_start !="" ){
                    $end_date = date('Y-m-d',strtotime(str_replace('/','-',$new_ten_start )));
                    $end_date_str = "'{$end_date}'";
                }else{
                    $end_date_str = 'NULL';
                }

                $no_dates_provided = 0;

                if( $vacant_from=="" && $new_ten_start =="" ){
                    $no_dates_provided = 1;
                    $comments_temp = 'No Dates Provided';
                }else if( $vacant_from!="" && $new_ten_start =="" ){
                    $no_dates_provided = 1;
                    $comments_temp = "Vacant from {$vacant_from} - {$problem}";
                }else if( $vacant_from=="" && $new_ten_start !="" ){
                    $no_dates_provided = 1;
                    $comments_temp = "Book before {$new_ten_start} - {$problem}";			
                }else{
                    $no_dates_provided = 0;
                    $comments_temp = "Vacant from {$vacant_from} - {$new_ten_start } {$problem}";
                }
                
                $comments = "COT {$comments_temp}"; 
                
                

                
            break;
            case 'Yearly Maintenance':
                $j_sql = $this->db->query("
                    SELECT *
                    FROM `jobs`
                    WHERE `property_id` = {$property_id}
                    AND `del_job` = 0
                ");	

            break;
            case 'Fix or Replace':
            
                if( $new_ten_start2 != '' ){
                    $temp = " New Tenancy Starts ".$new_ten_start2.",";
                }else{
                    $temp = ',';
                }		
                $comments = "{$job_type}{$temp} Comments: <strong>{$problem}</strong>";
            break;
            case '240v Rebook':

                $comments = "{$job_type}";
            break;
            case 'Lease Renewal':
            
                
                if( $new_ten_start!="" ){
                    $end_date = date('Y-m-d',strtotime(str_replace('/','-',$new_ten_start)));
                    $end_date_str = "'{$end_date}'";
                    $start_date = date('Y-m-d',strtotime("{$end_date} -30 days"));
                    $start_date_str = "'{$start_date}'";
                    $start_date_txt = date('d/m/Y',strtotime("{$end_date} -30 days"));
                }else{
                    $end_date_str = 'NULL';
                    $start_date_str = 'NULL';
                }
                
                
                $no_dates_provided = 0;
                
                if( $new_ten_start=="" ){
                    $no_dates_provided = 1;
                    $comments_temp = 'No Dates Provided';
                }else{
                    $no_dates_provided = 0;
                    $comments_temp = "{$start_date_txt} - {$new_ten_start} {$problem}";
                }
                
                $comments = "LR {$comments_temp}"; 
                
            
            break;
            case 'Annual Visit':

                $comments = "{$job_type}";
            break;
            case 'IC Upgrade':

                //$comments = "{$job_type}";		

                // update preferred_alarm_id
                if( $property_id > 0 && $preferred_alarm_id > 0 ){

                    // get property `qld_new_leg_alarm_num`
                    $prop_sql = $this->db->query("
                    SELECT `qld_new_leg_alarm_num`
                    FROM `property`
                    WHERE `property_id` = {$property_id}
                    ");
                    $prop_row = $prop_sql->row_array();

                    // get alarm details
                    $alarm_pwr_sql = $this->db->query("
                    SELECT `alarm_pwr_id`, `alarm_pwr`, `alarm_make`
                    FROM alarm_pwr
                    WHERE `alarm_pwr_id` = {$preferred_alarm_id}
                    ");
                    $alarm_pwr_row = $alarm_pwr_sql->row_array();

                    if( $alarm_pwr_row['alarm_pwr_id'] == 10 ){
                        $alar_pwr_comb = "{$alarm_pwr_row['alarm_pwr']} ({$alarm_pwr_row['alarm_make']})";
                    }else{
                        $alar_pwr_comb = $alarm_pwr_row['alarm_pwr'];
                    }

                    $comments = "IC Upgrade created preferring <b>{$prop_row['qld_new_leg_alarm_num']}</b>, <b>{$alar_pwr_comb}</b> alarms";

                    // update preferred_alarm_id
                    $this->db->query("
                    UPDATE `property`
                    SET `preferred_alarm_id` = {$preferred_alarm_id}
                    WHERE `property_id` = {$property_id}
                    ");

                }else{

                    // update preferred alarm to Emerald Planet(EP), if job type is 'C Upgrade' and is_sales = 1
                    if( $job_type == 'IC Upgrade' ){
                        
                        $this->db->query("
                        UPDATE `property`
                        SET `preferred_alarm_id` = 22
                        WHERE `property_id` = {$property_id}
                        AND `is_sales` = 1
                        ");

                    }			

                }

            break;
        }



        if( $params['job_status'] != '' ){
            $job_status = $params['job_status'];
        }else{
            $job_status = "To Be Booked"; // default
        }


        if( $job_status == 'On Hold' || $vacant_prop == 1 ){

            $start_date_str = ( $onhold_start_date != '' )?"'".date('Y-m-d',strtotime(str_replace('/','-',$onhold_start_date)))."'":'NULL';
            $end_date_str = ( $onhold_end_date != '' )?"'".date('Y-m-d',strtotime(str_replace('/','-',$onhold_end_date)))."'":'NULL';

        }else if( $job_status == 'Completed' ){

            $job_date_date_str = ( $job_date != '' )?"'".date('Y-m-d',strtotime(str_replace('/','-',$job_date)))."'":'NULL';

            $assigned_tech_field_str = 'assigned_tech,';
            $assigned_tech_val_str = "{$jtech_sel},";

        }else if($job_status=="Allocate"){ ## Per Bens request > Added by gherx March 19, 21 (set allocate_opt to 3 to force show Allocate Fancybox Response with Staff to Notify field)
            ##$allocate_opt_field = 'allocate_opt,'; #disable/revert as per Ben's request
            ##$allocate_opt_val = '3,'; ##disable/revert as per Ben's request
        }


        $price2 = ($job_type=="Yearly Maintenance"||$job_type=="Once-off")?$price:0;





        // get Franchise Group
        $agen_sql = $this->db->query("
            SELECT `franchise_groups_id`
            FROM `agency`
            WHERE `agency_id` = {$agency_id}
        ");
        $agen = $agen_sql->row_array();

        // if agency is DHA agencies with franchise group = 14(Defence Housing) OR if agency has maintenance program
        if( $this->system_model->isDHAagenciesV2($agen['franchise_groups_id'])==true || $this->system_model->agencyHasMaintenanceProgram($agency_id)==true ){
            $dha_need_processing = 1;
        }

        // if workorder exist it overrides job comments
        if( $workorder_notes != '' ){
            $comments = $workorder_notes;
        }




        $sql = "INSERT INTO 
            jobs (
                `job_type`, 
                `property_id`, 
                `status`,
                `service`,
                {$urg_field}
                `job_price`,
                `comments`,
                `start_date`, 
                `due_date`, 
                `no_dates_provided`,
                `property_vacant`,
                `dha_need_processing`,	
                {$assigned_tech_field_str}	
                `date`,
                `work_order`		
            ) 
            VALUES (
                '{$job_type}', 
                '{$property_id}', 
                '{$job_status}',
                '{$alarm_job_type_id}',
                {$urg_val}
                '{$price2}',
                '{$comments}',
                {$start_date_str}, 
                {$end_date_str}, 
                '{$no_dates_provided}',
                '{$vacant_prop}',
                '{$dha_need_processing}',
                {$assigned_tech_val_str}
                {$job_date_date_str},
                '{$work_order}'
            )";
        $this->db->query($sql);

        // job id
        $job_id = $this->db->insert_id();


        // AUTO - UPDATE INVOICE DETAILS
        // $url = site_url('pdf/view_invoice') . '?job_id='.$job_id.'';
        $this->system_model->updateInvoiceDetails($job_id);
                
        // insert job logs
        $this->db->query("
            INSERT INTO 
            `job_log` (
                `contact_type`,
                `eventdate`,
                `eventtime`,
                `comments`,
                `job_id`,
                `staff_id`
            ) 
            VALUES (
                '<strong>{$job_type}</strong> Job Created',
                '" . date('Y-m-d') . "',
                '" . date('H:i') . "',
                '{$comments}', 
                '{$job_id}',
                '{$this->session->staff_id}'
            )
        ");

            
        //$sql;

        // get alarm job type
        $ajt_sql = $this->db->query("
            SELECT *
            FROM `alarm_job_type`
            WHERE `id` = {$alarm_job_type_id}
        ");
        $ajt = $ajt_sql->row_array();


        // if bundle
        if($ajt['bundle']==1){
            $b_ids = explode(",",trim($ajt['bundle_ids']));
            // insert bundles
            foreach($b_ids as $val){
                $this->db->query("
                    INSERT INTO
                    `bundle_services`(
                        `job_id`,
                        `alarm_job_type_id`
                    )
                    VALUES(
                        {$job_id},
                        {$val}
                    )
                ");
                
                
                $bundle_id = $this->db->insert_id();
                $bs_id = $bundle_id;
                $bs2 = $this->gherxlib->getbundleServices($job_id,$bs_id);
                $ajt_id = $bs2['alarm_job_type_id'];
                
                //echo "Job ID: {$job_id} - ajt ID: {$alarm_job_type_id} Bundle ID: {$bundle_id} <br />";
                
                // sync alarm
                $this->gherxlib->runSync($job_id,$ajt_id,$bundle_id);

            }	
        }else{
            $this->gherxlib->runSync($job_id,$alarm_job_type_id);
        }


        if( 
            ( $job_type == 'Change of Tenancy' ||  $job_type == 'Lease Renewal' ) && $this->system_model->findExpired240vAlarm($job_id) == true ||
            ( $job_type == 'Fix or Replace' && $this->system_model->getAll240vAlarm($job_id) == true )
        ){
            $this->db->query("
                UPDATE `jobs` 
                SET `comments` = '240v REBOOK - {$comments}'
                WHERE `id` = {$job_id}
            ");
        }



        $this->db->query("
            INSERT INTO 
            `property_propertytype` (
                `property_id`,
                `alarm_job_type_id`
            )
            VALUES (
                '".$property_id."',
                '".$alarm_job_type_id."'
            )
        ");


        // add logs
        //$service_name = $_POST['service_name'];
        $staff_id = $params['staff_id'];

        // if preferred_alarm_id selected
        if( $job_type == 'IC Upgrade' && $preferred_alarm_id > 0 ){

            $this->db->query("
                INSERT INTO 
                `property_event_log` (
                    `property_id`, 
                    `staff_id`, 
                    `event_type`, 
                    `event_details`, 
                    `log_date`
                ) 
                VALUES (
                    ".$property_id.",
                    ".$staff_id.",
                    '{$ajt['type']} Job Created',
                    '{$comments}',
                    '".date('Y-m-d H:i:s')."'
                )
            ");

        }else{ // default

            $this->db->query("
                INSERT INTO 
                `property_event_log` (
                    `property_id`, 
                    `staff_id`, 
                    `event_type`, 
                    `event_details`, 
                    `log_date`
                ) 
                VALUES (
                    ".$property_id.",
                    ".$staff_id.",
                    '{$ajt['type']} Job Created',
                    '{$job_type}',
                    '".date('Y-m-d H:i:s')."'
                )
            ");

        }


        // clear tenant details
        $delete_tenant = $params['delete_tenant'];
        if($delete_tenant==1){
            
            /*
            mysql_query("
                UPDATE `property`
                SET 
                    `tenant_firstname1` = '',
                    `tenant_lastname1` = '',
                    `tenant_ph1` = '',
                    `tenant_email1` = '',
                    `tenant_mob1` = '',
                    `tenant_firstname2` = '',
                    `tenant_lastname2` = '',
                    `tenant_ph2` = '',
                    `tenant_email2` = '',
                    `tenant_mob2` = ''
                WHERE `property_id` = {$property_id}
            ");
            */
            
            $this->db->query("
                UPDATE `property_tenants`
                SET `active` = 0
                WHERE `property_id` = {$property_id}
            ");

        }


        // EO - 'Electrician Only' check
        $this->system_model->mark_is_eo($job_id);
    }

    public function get_count_agencies_from_other_company($id)
    {
       return $this->db->select("afoc.*, sac.company_name, sac.sac_id")
                    ->from("agencies_from_other_company as afoc")
                    ->join("smoke_alarms_company as sac", "sac.sac_id = afoc.company_id", "LEFT")
                    ->where('afoc.active', 1)
                    ->where('afoc.agency_id', $id)
                    ->get()->num_rows();
    }

    public function get_agencies_from_other_company($id)
    {
       return $this->db->select("afoc.*, sac.company_name, sac.sac_id")
                    ->from("agencies_from_other_company as afoc")
                    ->join("smoke_alarms_company as sac", "sac.sac_id = afoc.company_id", "LEFT")
                    ->where('afoc.active', 1)
                    ->where('afoc.agency_id', $id)
                    ->get()->result();
    }

    public function save_properties_from_other_company($data)
    {
        return $this->db->insert('properties_from_other_company', $data);
    }

    public function get_smoke_alarm_company()
    {
        return $this->db->select("sac.sac_id, sac.company_name")
                    ->from("smoke_alarms_company as sac")
                    // ->join("smoke_alarms_company as sac", "afoc.company_id = sac.sac_id", "LEFT")
                    ->where('sac.active', 1)->get()->result();
    }

    public function get_retest_date($prop_id){
        $retest_date_query = $this->db->query("
            SELECT retest_date
            FROM `property`
            WHERE `property_id` = {$prop_id}
        ");
        $retest_date_row = $retest_date_query->row_array();

        return ($retest_date_row['retest_date']!=NULL && $retest_date_row['retest_date']!='1521-03-16' && $retest_date_row['retest_date']!='1521-03-17') ? date('d/m/Y', strtotime($retest_date_row['retest_date'])) : 'N/A';
    }

    public function job_to_upgrade_to_ic_service($job_to_upgrade_to_ic_service){
        $today = date('Y-m-d H:i:s');

        // job data
        echo $job_sql_str = "
        SELECT 
            j.`service` AS jservice,
            p.`property_id`,
            a.`agency_id`
        FROM `jobs` AS j
        LEFT JOIN `property` AS p ON j.`property_id` = p.`property_id`
        LEFT JOIN `agency` AS a ON p.`agency_id` = a.`agency_id`
        WHERE `id` = {$job_to_upgrade_to_ic_service}
        ";
        echo "<br />";

        $job_sql = $this->db->query($job_sql_str);
        $job_row = $job_sq->row_array();
        $agency_id = $job_row['agency_id'];
        $property_id = $job_row['property_id'];
        $service_type = $job_row['jservice'];

        // get last completed YM
        echo $last_com_ym_job_sql_str = "
        SELECT j.`date` AS jdate
        FROM `jobs` AS j
        LEFT JOIN `property` AS p ON j.`property_id` = p.`property_id`
        LEFT JOIN `agency` AS a ON p.`agency_id` = a.`agency_id`
        WHERE p.`property_id` = {$property_id}
        AND j.`job_type` = 'Yearly Maintenance'
        AND j.`status` IN('Completed','Merged Certificates') 
        ORDER BY j.`date` DESC
        LIMIT 1
        ";
        echo "<br />";
        $last_com_ym_job_sql_sql = $this->db->query($last_com_ym_job_sql_str);

        if( $last_com_ym_job_sql_sql->num_rows() > 0 ){

            $last_com_ym_job_sql_row = $last_com_ym_job_sql_sql->row_array();
            $last_com_ym_job_date = $last_com_ym_job_sql_row['jdate']; // last complete YM job date

            // determine what IC service it should upgrade to
            $to_ic_service_type = null;
            switch( $service_type ){

                // SA        
                case 2:
                    $to_ic_service_type = 12; // SA IC
                break;   
                
                // SASS        
                case 8:
                    $to_ic_service_type = 13; // SASS IC
                break;

                // SACWSS        
                case 9:
                    $to_ic_service_type = 14; // SACWSS IC
                break;

                // Smoke Alarms & Corded Windows        
                case 19:
                    $to_ic_service_type = 20; // Smoke Alarms & Corded Windows (IC)
                break;

            }

            // check if IC service type is availble on agency
            echo $agency_serv_sql_str = "
            SELECT 
                ps.`agency_services_id`,
                ps.`price`,
                ajt.`id` AS ajt_id
            FROM `agency_services` AS ps
            LEFT JOIN `alarm_job_type` AS ajt ON ps.`service_id` = ajt.`id`
            WHERE ps.`agency_id` = {$agency_id}
            AND ps.`service_id` = {$to_ic_service_type}
            ";
            echo "<br />";
            $agency_serv_sql = $this->db->query($agency_serv_sql_str);
            if( $agency_serv_sql->num_rows() > 0 ){

                $agency_serv_row = $agency_serv_sql->row_array();
                $agency_serv_price = $agency_serv_row['price']; // agency service price     

                $price_var_params = array(
                    'service_type' => $agency_serv_row['ajt_id'],
                    'property_id' => $property_id
                );
                $price_var_arr = $this->system_model->get_property_price_variation($price_var_params);
                $job_price = $price_var_arr['dynamic_price_total'];

                if( $to_ic_service_type > 0 ){



                // update current job service type to IC service type and 'service to' SATS           
                    $service_to = 1; // SATS

                    // clear by property ID and service type, this will also fix issues on duplicates
                    echo $delete_sql_str = "
                    DELETE 
                    FROM `property_services`
                    WHERE `alarm_job_type_id` = {$to_ic_service_type} 
                    AND `property_id` = {$property_id}  
                    ";
                    echo "<br />";
                    $this->db->query($delete_sql_str); 

                    ## by AL
                    $this_month_start = date("Y-m-01");
                    $this_month_end = date("Y-m-t");
                    $ps_sql_str = "
                    SELECT COUNT(`property_services_id`) AS ps_count
                    FROM `property_services`
                    WHERE `property_id` = {$property_id} 
                    AND `service`=1
                    AND `is_payable` = 1
                    AND `status_changed` BETWEEN '{$this_month_start}' AND '{$this_month_end} 23:59:59'
                    ";
                    $ps_sql = $this->db->query($ps_sql_str);
                    $ps_row = $ps_sql->row();
                    $ps_count =  $ps_row->ps_count;

                    if($ps_count>0){
                        $is_payable = 1;
                    }else{
                        $is_payable = 0;
                    }
                    ## by AL end

                    // insert IC service type
                    echo $insert_serv_type_sql_str = "
                    INSERT INTO
                    `property_services` (
                        `property_id`,
                        `alarm_job_type_id`,
                        `service`,
                        `price`,
                        `status_changed`,
                        `is_payable`
                    )
                    VALUE(
                        {$property_id},
                        {$to_ic_service_type},
                        {$service_to},
                        {$agency_serv_price},
                        '{$today}',
                        {$is_payable}
                    )       
                    ";  
                    echo "<br />";
                    $this->db->query($insert_serv_type_sql_str); 


                    

                    // update current job service type to 'service to' No response
                    $service_to = 2; // No Response    

                    // clear by property ID and service type, this will also fix issues on duplicates            
                    echo $delete_sql_str = "
                    DELETE 
                    FROM `property_services`
                    WHERE `alarm_job_type_id` = {$service_type} 
                    AND `property_id` = {$property_id}  
                    ";
                    echo "<br />";
                    $this->db->query($delete_sql_str);             
                    
                    // re-insert and service to NO response   
                /* ## Al: disable NR service insert
                echo $insert_serv_type_sql_str = "
                    INSERT INTO
                    `property_services` (
                        `property_id`,
                        `alarm_job_type_id`,
                        `service`,
                        `price`,
                        `status_changed`
                    )
                    VALUE(
                        {$property_id},
                        {$service_type},
                        {$service_to},
                        {$agency_serv_price},
                        '{$today}'
                    )       
                    ";  
                    echo "<br />";
                    mysql_query($insert_serv_type_sql_str); 
                    */




                    // create job - from create job function
                    $assigned_tech = 1; // Other Supplier

                    echo $create_job_sql = "
                    INSERT INTO 
                    jobs (
                        `job_type`, 
                        `property_id`, 
                        `status`,
                        `service`,            
                        `job_price`,	
                        `assigned_tech`,	
                        `date`		
                    ) 
                    VALUES (
                        'Yearly Maintenance', 
                        '{$property_id}', 
                        'Completed',
                        '{$to_ic_service_type}',            
                        '{$job_price}',
                        {$assigned_tech},
                        '{$last_com_ym_job_date}'            
                    )";
                    echo "<br />";
                    $this->db->query($create_job_sql);

                    // job id
                    $ic_job_id = $this->db->insert_id();

                    if( $ic_job_id > 0 ){

                        // AUTO - UPDATE INVOICE DETAILS
                        $this->system_model->updateInvoiceDetails($ic_job_id);

                        //  SYNC
                        // get alarm job type
                        $ajt_sql = $this->db->query("
                        SELECT *
                        FROM `alarm_job_type`
                        WHERE `id` = {$to_ic_service_type}
                        ");
                        $ajt = $ajt_sql->row_array();


                        // if bundle
                        if($ajt['bundle']==1){

                            $b_ids = explode(",",trim($ajt['bundle_ids']));

                            // insert bundles
                            foreach($b_ids as $val){

                                $this->db->query("
                                    INSERT INTO
                                    `bundle_services`(
                                        `job_id`,
                                        `alarm_job_type_id`
                                    )
                                    VALUES(
                                        {$ic_job_id},
                                        {$val}
                                    )
                                ");
                                                    
                                $bundle_id = $this->db->insert_id();
                                $bs_id = $bundle_id;
                                $bs2 = $this->gherxlib->getbundleServices($ic_job_id,$bs_id);
                                $ajt_id = $bs2['alarm_job_type_id'];
                                                                    
                                // sync alarm
                                $this->gherxlib->runSync($ic_job_id,$ajt_id,$bundle_id);

                            }	

                        }else{
                            $this->gherxlib->runSync($ic_job_id,$to_ic_service_type);
                        }
                        
                    }            
                    

                }
                

            }

        }
    }

    public function vpd_service_due_job($property_id, $hid_smoke_price, $agency_id){
        // get Franchise Group
        $agen_sql = $this->db->query("
            SELECT `franchise_groups_id`
            FROM `agency`
            WHERE `agency_id` = {$agency_id}
        ");
        $agen = $agen_sql->row_array();

        // if agency is DHA agencies with franchise group = 14(Defence Housing) OR if agency has maintenance program
        $dha_need_processing = 0;
        if( $this->system_model->isDHAagenciesV2($agen['franchise_groups_id'])==true || $this->system_model->agencyHasMaintenanceProgram($agency_id)==true ){
            $dha_need_processing = 1;
        }


        $this->db->query("
            INSERT INTO 
            `jobs` (
                `status`, 
                `retest_interval`, 
                `auto_renew`, 
                `job_type`, 
                `property_id`, 
                `sort_order`, 
                `job_price`, 
                `service`,
                `dha_need_processing`
            )
            VALUES(
                'Pending', 
                365, 
                1, 
                'Yearly Maintenance', 
                {$property_id}, 
                1,  
                '{$hid_smoke_price}', 
                2,
                '{$dha_need_processing}'
            )
        ");

        // job id
        $job_id = $this->db->insert_id();

        // AUTO - UPDATE INVOICE DETAILS
        $this->system_model->updateInvoiceDetails($job_id);

        // insert job logs
        $this->db->query("
            INSERT INTO 
            `job_log` (
                `contact_type`,
                `eventdate`,
                `eventtime`,
                `comments`,
                `job_id`,
                `staff_id`
            ) 
            VALUES (
                'Job Created',
                '" . date('Y-m-d') . "',
                '" . date('H:i') . "',
                '<strong>Service Due</strong> Job Created', 
                '{$job_id}',
                '{$this->session->staff_id}'
            )
        ");

        // add property logs
        $staff_id = $this->session->staff_id;
        $this->db->query("
            INSERT INTO 
            `property_event_log` (
                `property_id`, 
                `staff_id`, 
                `event_type`, 
                `event_details`, 
                `log_date`
            ) 
            VALUES (
                ".$property_id.",
                ".$staff_id.",
                'Job Created',
                'Service Due Job Created',
                '".date('Y-m-d H:i:s')."'
            )
        ");
    }

    public function non_active_service_update($property_id, $non_active_ps_id_arr, $non_active_service_status_arr){
        foreach( $non_active_ps_id_arr as $index => $non_active_ps_id ){

            if( $non_active_ps_id > 0 && is_numeric($non_active_service_status_arr[$index]) ){
        
                // insert service type
                $insert_serv_type_sql_str = "
                UPDATE `property_services` 
                SET `service` = ".$non_active_service_status_arr[$index]."
                WHERE `property_services_id` = ".$non_active_ps_id."   
                AND `property_id` = {$property_id}
                ";          
                $this->db->query($insert_serv_type_sql_str); 
        
            }    
        
        }
    }

    public function add_new_service_type($property_id, $agency_id, $new_service_type, $new_service_type_status)
    {
        $today = date('Y-m-d H:i:s');

        // check if IC service type is availble on agency
        $agency_serv_sql_str = "
        SELECT 
            `agency_services_id`,
            `price`
        FROM `agency_services` 
        WHERE `agency_id` = {$agency_id}
        AND `service_id` = {$new_service_type}
        ";
        $agency_serv_sql = $this->db->query($agency_serv_sql_str);
        if ($agency_serv_sql->num_rows() > 0) {
            $agency_serv_row = $agency_serv_sql->row_array();
            $agency_serv_price = $agency_serv_row['price']; // agency service price       

            if ($new_service_type > 0) {
                // clear by property ID and service type, this will also fix issues on duplicates
                $delete_sql_str = "
                DELETE 
                FROM `property_services`
                WHERE `alarm_job_type_id` = {$new_service_type} 
                AND `property_id` = {$property_id}  
                ";
                $this->db->query($delete_sql_str);

                // new service type name
                $ajt_sql = $this->db->query(
                    "
                SELECT `type`
                FROM `alarm_job_type`
                WHERE `id` = {$new_service_type}
                "
                );
                $ajt_row = $ajt_sql->row_array();
                $service_type_new = $ajt_row['type'];

                // ben's mark/unmark payable logic
                $this_month_start = date("Y-m-01");
                $this_month_end = date("Y-m-t");
                $is_payable = 1;

                // check if it has any property services
                $ps_sql_str = "
                SELECT COUNT(`property_services_id`) AS ps_count
                FROM `property_services`
                WHERE `property_id` = {$property_id}         
                ";
                $ps_sql = $this->db->query($ps_sql_str);
                $ps_row = $ps_sql->row();
                $ps_count = $ps_row->ps_count;

                if ($ps_count == 0) {
                    // is payable state for new service
                    $is_payable = 1;
                } else {
                    // check it has is payable status changed this month
                    $ps_sql_str = "
                    SELECT COUNT(`property_services_id`) AS ps_count
                    FROM `property_services`
                    WHERE `property_id` = {$property_id} 
                    AND `is_payable` = 1
                    AND DATE(`status_changed`) BETWEEN '{$this_month_start}' AND '{$this_month_end}'
                    ";
                    $ps_sql = $this->db->query($ps_sql_str);
                    $ps_row = $ps_sql->row();
                    $ps_count = $ps_row->ps_count;

                    if ($ps_count > 0) {
                        // is payable state for new service
                        $is_payable = 0;
                    } else {
                        // loop through existing property services
                        $ps_sql = $this->db->query(
                            "
                        SELECT `service`, `status_changed` 
                        FROM `property_services`                                 
                        WHERE `property_id` = {$property_id}    
                        ORDER BY `status_changed` DESC
                        "
                        );

                        $non_sats_count = 0;
                        $sixty_one_days_ago = date("Y-m-d", strtotime("-61 days"));
                        $sixt_one_days_older = false;

                        foreach ($ps_sql->result() as $ps_row) {
                            $status_changed = date('Y-m-d', strtotime($ps_row->status_changed));

                            if ($ps_row->service != 1) { // non SATS
                                $non_sats_count++;
                            }

                            if ($status_changed < $sixty_one_days_ago) {
                                $sixt_one_days_older = true;
                            }
                        }

                        if ($ps_sql->num_rows() == $non_sats_count && $sixt_one_days_older) {
                            // loop through existing property services
                            $ps_sql = $this->db->query(
                                "
                            SELECT 
                                ps.`is_payable`,
                                ajt.`type` AS service_type_name 
                            FROM `property_services` AS ps  
                            LEFT JOIN `alarm_job_type` AS ajt ON ps.`alarm_job_type_id` = ajt.`id`              
                            WHERE ps.`property_id` = {$property_id}    
                            "
                            );

                            foreach ($ps_sql->result() as $ps_row) {
                                if ($ps_row->is_payable == 1) {
                                    // insert logs
                                    $this->db->query(
                                        "
                                    INSERT INTO
                                    `property_event_log`
                                    (
                                        `property_id`,
                                        `staff_id`,
                                        `event_type`,
                                        `event_details`,
                                        `log_date`,
                                        `hide_delete`
                                    )
                                    VALUES(
                                        {$property_id},
                                        {$this->session->staff_id},
                                        'Property Sales Commission',
                                        'Property Service <b>{$ps_row->service_type_name}</b> unmarked <b>payable</b>',
                                        '" . date('Y-m-d H:i:s') . "',
                                        1
                                    )
                                    "
                                    );
                                }
                            }

                            // clear is payable
                            $this->db->query(
                                "
                            UPDATE `property_services`
                            SET `is_payable` = 0
                            WHERE `property_id` = {$property_id}    
                            "
                            );

                            // is payable state for new service
                            $is_payable = 1;
                        } else {
                            // is payable state for new service
                            $is_payable = 0;
                        }
                    }
                }

                // TO        
                //$service_to = 1; // SATS             
                $service_to = $new_service_type_status;

                // this is a totally new property service so it doesnt have a before is_payable state so should only log for its payable
                if ($is_payable == 1) {
                    // insert logs
                    $this->db->query(
                        "
                    INSERT INTO
                    `property_event_log`
                    (
                        `property_id`,
                        `staff_id`,
                        `event_type`,
                        `event_details`,
                        `log_date`,
                        `hide_delete`
                    )
                    VALUES(
                        {$property_id},
                        {$this->session->staff_id},
                        'Property Sales Commission',
                        'Property Service <b>{$service_type_new}</b> marked <b>payable</b>',
                        '" . date('Y-m-d H:i:s') . "',
                        1
                    )
                    "
                    );
                }

                // insert service type
                $insert_serv_type_sql_str = "
                INSERT INTO
                `property_services` (
                    `property_id`,
                    `alarm_job_type_id`,
                    `service`,
                    `price`,
                    `status_changed`,
                    `is_payable`
                )
                VALUE(
                    {$property_id},
                    {$new_service_type},
                    {$service_to},
                    {$agency_serv_price},
                    '{$today}',
                    {$is_payable}
                )       
                ";
                $this->db->query($insert_serv_type_sql_str);


                // insert property log
                $this->db->query(
                    "
                    INSERT INTO
                    `property_event_log`
                    (
                        `property_id`,
                        `staff_id`,
                        `event_type`,
                        `event_details`,
                        `log_date`,
                        `hide_delete`
                    )
                    VALUES(
                        {$property_id},
                        {$this->session->staff_id},
                        'New Property Service',
                        'New service added: <b>{$service_type_new}</b>',
                        '" . date('Y-m-d H:i:s') . "',
                        1
                    )
                "
                );
            }
        }

        // A new service type could affect which job is considered the last completed YM job after a move etc EDGE CASE
        $this->load->model('property_subscription_model');
        $this->property_subscription_model->refresh($property_id);
    }

    public function can_delete_property() {
        return $this->db->query("
        SELECT COUNT(`id`) AS sp_count
        FROM `staff_permissions`
        WHERE `staff_id` = {$this->session->staff_id}
        AND `has_permission_on` = 2
        "); 
    }

    public function check_invoice_payment($property_id) {
        $inv_pay_sql_str = "
            SELECT COUNT(inv_pay.`invoice_payment_id`) AS inv_pay_count
            FROM `invoice_payments` AS inv_pay
            LEFT JOIN payment_types AS pt ON inv_pay.`type_of_payment` = pt.`payment_type_id`
            LEFT JOIN `jobs` AS j ON inv_pay.`job_id` = j.`id`
            LEFT JOIN `property` AS p ON j.`property_id` = p.`property_id`
            WHERE j.`del_job` = 0
            AND inv_pay.`active` = 1
            AND p.`property_id` = {$property_id}
        ";

        $inv_pay_sql = $this->db->query($inv_pay_sql_str);
        $inv_pay_row = $inv_pay_sql->row_array();
        echo $inv_pay_row['inv_pay_count'];
    }

    public function delete_property_permanently($property_id, $delete_reason) {
        $sql1 = "
            UPDATE property
            SET
                `deleted`=1, 
                `reason`= '" . $delete_reason . "',
                `deleted_date` = '" . date('Y-m-d H:i:s') . "',
                `agency_deleted`=0,
                `booking_comments` = 'Deleted as of " . date("d/m/Y") . " - by SATS.',
                `nlm_by_sats_staff` = '{$this->session->staff_id}'
            WHERE property_id = {$property_id}
            ";
        $this->db->query($sql1);
    }

    public function update_agency_price_variation($property_id, $agency_price_variation) {
        $query_pv = $this->db->query("
            SELECT *
            FROM `property_variation`
            WHERE `property_id` = {$property_id}
        "); 

        if ($query_pv->num_rows() > 0) {
            $this->db->query("
                UPDATE property_variation
                SET
                    agency_price_variation = {$agency_price_variation}
                WHERE property_id = {$property_id}
            ");
        } else {
            $this->db->query("
                INSERT INTO 
                property_variation 
                (property_id, agency_price_variation, date_applied)
                VALUES 
                ({$property_id}, {$agency_price_variation}, '".date('Y-m-d')."')
            ");
        }
    }

    public function update_from_other_company($property_id, $from_other_company) {
        $query_foc = $this->db->query("
            UPDATE properties_from_other_company
            SET
                active = 0
            WHERE property_id = {$property_id}
        "); 

        if ($query_foc) {
            $this->db->query("
                INSERT INTO 
                properties_from_other_company 
                (property_id, company_id, added_date)
                VALUES 
                ({$property_id}, {$from_other_company}, '".date('Y-m-d')."')
            ");
        }
    }

    public function get_contact($params){

        $contact_id = $params['contact_id'];
        $agency_id = $params['agency_id'];

        $end_points = "https://app.propertyme.com/api/v1/contacts/{$contact_id}";

        $api_id = 1; // PMe

        // get access token
        $pme_params = array(
            'agency_id' => $agency_id,
            'api_id' => $api_id
        );
        $access_token = $this->pme_model->getAccessToken($pme_params);

        $pme_params = array(
            'access_token' => $access_token,
            'end_points' => $end_points
        );
        
        $response =  $this->pme_model->call_end_points_v2($pme_params);
        return $response;

    }

    public function get_palace_landlord($params){

        $prop_id = $params['prop_id'];
        $agency_id = $params['agency_id'];

        $propertyDet = $this->get_palace_prop_by_id($params);
        $ownerCode = $propertyDet[0]->PropertyOwnerCode;

        $api_id = 4; // Palace        

        $agency_api_tokens_sql = $this->db->query("
            SELECT 
                `access_token`,
                `expiry`,
                `refresh_token`,
                `system_use`
            FROM `agency_api_tokens`
            WHERE `agency_id` = {$agency_id}
            AND `api_id` = {$api_id}
        ");
        $a_api_tok_row = $agency_api_tokens_sql->row_array();
        $access_token = $a_api_tok_row['access_token'];
        $system = $a_api_tok_row['system_use'];

        if ($this->config->item('country') == 1) { // AU
            if ($system == "Legacy" || is_null($system)) {
                $palace_api_base = 'https://serviceapia.realbaselive.com';
            }else {
                $palace_api_base = 'https://api.getpalace.com';
            }
        } else if ($this->config->item('country') == 2) { // NZ
            if ($system == "Legacy" || is_null($system)) {
                $palace_api_base = 'https://serviceapi.realbaselive.com';
            }else {
                $palace_api_base = 'https://api.getpalace.com';
            }
        }
        $end_points = $palace_api_base."/Service.svc/RestService/ViewAllDetailedOwner";

        $pme_params = array(
            'access_token' => $access_token,
            'end_points' => $end_points
        );
        
        $ownerList = $this->call_palace_end_points($pme_params);
        $ownerList = isset($ownerList['ViewAllDetailedOwner']) ? $ownerList['ViewAllDetailedOwner'] : array();

        $resArr = array();
        foreach ($ownerList as $key => $value) {
            if ($value->OwnerCode == $ownerCode) {
                if ($value->OwnerArchived == 'false') {
                    array_push($resArr, $ownerList[$key]);
                }
            }
        }
        return $resArr;

    }

    public function get_palace_prop_by_id($params) {

        $prop_id = $params['prop_id'];
        $agency_id = $params['agency_id'];

        $api_id = 4; // Palace        

        $agency_api_tokens_sql = $this->db->query("
            SELECT 
                `access_token`,
                `expiry`,
                `refresh_token`,
                `system_use`
            FROM `agency_api_tokens`
            WHERE `agency_id` = {$agency_id}
            AND `api_id` = {$api_id}
        ");
        $a_api_tok_row = $agency_api_tokens_sql->row_array();
        $access_token = $a_api_tok_row['access_token'];
        $system = $a_api_tok_row['system_use'];

        if ($this->config->item('country') == 1) { // AU
            if ($system == "Legacy" || is_null($system)) {
                $palace_api_base = 'https://serviceapia.realbaselive.com';
            }else {
                $palace_api_base = 'https://api.getpalace.com';
            }
        } else if ($this->config->item('country') == 2) { // NZ
            if ($system == "Legacy" || is_null($system)) {
                $palace_api_base = 'https://serviceapi.realbaselive.com';
            }else {
                $palace_api_base = 'https://api.getpalace.com';
            }
        }
        $end_points = $palace_api_base."/Service.svc/RestService/ViewAllDetailedProperty";

        $pme_params = array(
            'access_token' => $access_token,
            'end_points' => $end_points
        );
        
        $propList = $this->call_palace_end_points($pme_params);
        $propList = isset($propList['ViewAllDetailedProperty']) ? $propList['ViewAllDetailedProperty'] : array();
        
        $resArr = array();
        foreach ($propList as $key => $value) {
            if ($value->PropertyCode == $params['prop_id']) {
                if ($value->PropertyArchived == 'false') {
                    array_push($resArr, $propList[$key]);
                }
            }
        }
        return $resArr;

    }

    public function call_palace_end_points($params)
    {
        $curl = curl_init();

        // HTTP headers
        $http_header = array(
            "Authorization: Basic {$params['access_token']}",
            "Content-Type: application/xml"
        );

        curl_setopt_array($curl, array(
          CURLOPT_URL => $params['end_points'],
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => $http_header,
          CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.12) Gecko/20101026 Firefox/3.6.12',
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $xml_snippet = simplexml_load_string( $response );
        $json_convert = json_encode( $xml_snippet );
        $json = json_decode( $json_convert );
        return (array)($json);

    }

    public function get_part_of_string($string, $start_str, $end_str) {

        $startpos = strpos($string, $start_str);
        $endpos = strpos($string, $end_str);

        $length = $endpos - $startpos;
        return substr($string, $startpos + 1, $length - 1);
    }

    // parse the tags on logs link
    public function parseDynamicLink($params) {

        $log_details = $params['log_details'];

        // property logs
        $tag = '{p_address}';
        // find the tag
        if (strpos($log_details, $tag) !== false) {

            // get logs data
            $log_sql_str = "
			SELECT `l`.`property_id`
			FROM `logs` AS `l`
			WHERE `l`.`log_id` = {$params['log_id']}
			";
            $log_sql = $this->db->query($log_sql_str);
            $l_row = $log_sql->row_array();
            $property_id = $l_row['property_id'];

            if (isset($property_id) && $property_id > 0) {

                // get property data
                $p_sql_str = "
				SELECT
					`p`.`property_id`,
					`p`.`address_1`,
					`p`.`address_2`,
					`p`.`address_3`,
					`p`.`state`,
					`p`.`postcode`
				FROM `property` AS `p`
				WHERE `p`.`property_id` = {$property_id}
				";
                $p_sql = $this->db->query($p_sql_str);
                $p_row = $p_sql->row_array();
                $vpd_link = "<a href='/properties/details/?id={$property_id}'>{$p_row['address_1']} {$p_row['address_2']} {$p_row['address_3']}</a>";

                // replace tags
                $log_details = str_replace($tag, $vpd_link, $log_details);
            }
        }


        // agency user
        $tag = 'agency_user';
        // find the tag
        if (strpos($log_details, $tag) !== false) {

            // break down the tag to get the agency user ID
            $tag_string = $this->get_part_of_string($log_details, '{', '}');
            $str_exp = explode(':', $tag_string);
            $aua_id = $str_exp[1];


            // get agency user data
            $sel_query = "
				aua.`agency_user_account_id`,
				aua.`fname`,
				aua.`lname`
			";

            $user_sql_str = "
			SELECT `aua`.`agency_user_account_id`, `aua`.`fname`, `aua`.`lname`
			FROM `agency_user_accounts` AS `aua`
			LEFT JOIN `agency_user_account_types` AS `auat` ON aua.`user_type` = auat.`agency_user_account_type_id`
			LEFT JOIN `agency` AS `a` ON aua.`agency_id` = a.`agency_id`
			WHERE `aua`.`agency_user_account_id` = {$aua_id}
			";
            $user_sql = $this->db->query($user_sql_str);
            $user_row = $user_sql->row_array();
            $user_full_name = "{$user_row['fname']} {$user_row['lname']}";

            // replace tags
            $log_details = str_replace('{' . $tag_string . '}', $user_full_name, $log_details);
        }


        // created by
        $tag = '{created_by}';
        // find the tag
        if (strpos($log_details, $tag) !== false) {

            // get logs data
            $log_sql_str = "
			SELECT `l`.`created_by`
			FROM `logs` AS `l`
			WHERE `l`.`log_id` = {$params['log_id']}
			";
            $log_sql = $this->db->query($log_sql_str);

            if ($log_sql->num_rows() > 0) {

                $l_row = $log_sql->row_array();
                $created_by = $l_row['created_by'];

                // get agency user data
                $sel_query = "
					aua.`agency_user_account_id`,
					aua.`fname`,
					aua.`lname`
				";

                $user_sql_str = "
				SELECT `aua`.`agency_user_account_id`, `aua`.`fname`, `aua`.`lname`
				FROM `agency_user_accounts` AS `aua`
				LEFT JOIN `agency_user_account_types` AS `auat` ON aua.`user_type` = auat.`agency_user_account_type_id`
				LEFT JOIN `agency` AS `a` ON aua.`agency_id` = a.`agency_id`
				WHERE `aua`.`agency_user_account_id` = {$created_by}
				";
                $user_sql = $this->db->query($user_sql_str);
                $user_row = $user_sql->row_array();
                $user_full_name = "{$user_row['fname']} {$user_row['lname']}";

                // replace tags
                $log_details = str_replace($tag, $user_full_name, $log_details);
            }
        }


        return $log_details;
    }

    public function getPropertyFiles($property_id) {

        # if subdir doesn't exist then return null
        if(!is_dir('./property_files/' . $property_id))
        {
            return null;
        }
        else 
        {
            if ($handle = opendir('./property_files/' . $property_id)) 
            {
                $files = array();
                
                while (false !== ($entry = readdir($handle))) 
                {
                    if($entry != "." && $entry != "..")
                    {	
                        $files[] = $entry;
                    }
                }
            
                closedir($handle);
            
                return $files;
            }
            else
            {
                return null;
            }
        }
    }

    /**
     * Get properties_from_other_company
     * 
     * @param int $prop_id
     * 
     * @return FALSE|array
     */
    public function get_property_source($prop_id)
    {
        if((int)$prop_id <= 0){
            return FALSE;
        }

        $sql_str= "
            SELECT 
                sac.`sac_id`,
                sac.`company_name`
            FROM `properties_from_other_company` AS pfoc
            LEFT JOIN `smoke_alarms_company` AS sac ON pfoc.`company_id` = sac.`sac_id`
            LEFT JOIN `property` AS prop ON pfoc.`property_id` = prop.`property_id`
            WHERE pfoc.`property_id` = ?
            AND pfoc.`active` = 1
        ";
        $sql = $this->db->query($sql_str, $prop_id);
    
        if ($sql->num_rows() > 0) {
            return $sql->row_array();
        } else {
            return FALSE;
        }
    }
}
