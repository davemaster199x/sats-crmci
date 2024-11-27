<?php

class Alarms_model extends CI_Model
	{
		private $table = "alarm";
		private $columns = [];
		public function __construct()
		{
			$this->load->database();
			$this->columns = ["job_id",  "alarm_power_id",  "alarm_type_id",  "new",  "pass",  "alarm_price",  "alarm_reason_id",  "make",  "model",  "expiry",  "ts_position",  "ts_fixing",  "ts_cleaned",  "ts_newbattery",  "ts_testbutton",  "ts_visualind",  "ts_simsmoke",  "ts_checkeddb",  "ts_meetsas1851",  "ts_expiry",  "ts_added",  "ts_discarded",  "ts_discarded_reason",  "ts_comments",  "ts_location",  "ts_trip_rate",  "ts_item_number",  "alarm_job_type_id",  "ts_height",  "ts_opening",  "ts_pass_reason",  "ts_required_compliance",  "tmh_alarm_id",  "tmh_imported",  "ts_db_rating",  "window_type_cw",  "window_material_cw",  "blind_type_cw",  "ftlgt1_6m_cw",  "tag_present_cw",  "clip_rfc_cw",  "clip_present_cw",  "loop_lt220m_cw",  "od_gt1m_cw",  "nm_tested_cw",  "ts_is_alarm_ic",  "ts_alarm_sounds_other",  "rec_batt_exp"];
		}


    public function getNewAlarms($params)
    {

        if ($params['sel_query'] && !empty($params['sel_query'])) {
            $sel_query = $params['sel_query'];
        } else {
            $sel_query = '*';
        }

        $this->db->select($sel_query);
        $this->db->from('alarm as alrm');
        $this->db->join('jobs as j', 'j.id = alrm.job_id', 'inner');
        $this->db->join('property as p', 'p.property_id = j.property_id', 'left');
        $this->db->join('agency as a', 'a.agency_id = p.agency_id', 'left');
        $this->db->join('alarm_pwr as alrm_p', 'alrm_p.alarm_pwr_id = alrm.alarm_power_id', 'inner');
        $this->db->join('alarm_reason as alrm_r', 'alrm_r.alarm_reason_id = alrm.alarm_reason_id', 'inner');
        $this->db->join('staff_accounts as sa', 'sa.StaffID = j.assigned_tech', 'inner');
        $this->db->where('alrm.alarm_id >', 0);


        //FILTERS
        if ($params['new'] != "" && $params['new']) {
            $this->db->where('alrm.new', $params['new']);
        }

        if ($params['state'] != "" && $params['state']) {
            $this->db->where('p.state', $params['state']);
        }

        if ($params['alarm_pwr'] != "" && $params['alarm_pwr']) {
            $this->db->where('alrm_p.alarm_pwr_id', $params['alarm_pwr']);
        }

        if ($params['alarm_reason'] != "" && $params['alarm_reason']) {
            $this->db->where('alrm_r.alarm_reason_id', $params['alarm_reason']);
        }

        if ($params['agency_filter'] != "" && $params['agency_filter']) {
            $this->db->where('a.agency_id', $params['agency_filter']);
        }

        if ($params['job_type'] != "" && $params['job_type']) {
            $this->db->where('j.job_type', $params['job_type']);
        }

        // date filter
        if ($params['filterDate'] != '' && $params['filterDate']) {
            if ($params['filterDate']['from'] != "" && $params['filterDate']['to'] != "") {
                $filter_date = "j.`date` BETWEEN '{$params['filterDate']['from']}' AND '{$params['filterDate']['to']}'";
                $this->db->where($filter_date);
            }
        }

        // tech filter
        if ($params['tech'] != "" && $params['tech']) {
            $this->db->where('j.assigned_tech', $params['tech']);
        }

        //active tech filter
        if ($params['active_tech'] != "" && $params['active_tech']) {
            $this->db->where('sa.active', $params['active_tech']);
        }

        // sort
        if (isset($params['sort_list'])) {
            foreach ($params['sort_list'] as $sort_arr) {
                if ($sort_arr['order_by'] != "" && $sort_arr['sort'] != '') {
                    $this->db->order_by($sort_arr['order_by'], $sort_arr['sort']);
                }
            }
        }

        // limit/offset
        if (isset($params['limit']) && $params['limit'] > 0) {
            $this->db->limit($params['limit'], $params['offset']);
        }


        return $this->db->get();
    }

    public function getDiscardedAlarms($params)
    {

        if ($params['sel_query'] && !empty($params['sel_query'])) {
            $sel_query = $params['sel_query'];
        } else {
            $sel_query = '*';
        }

        $this->db->select($sel_query);
        $this->db->from('alarm as a');
        $this->db->join('jobs as j', 'j.id = a.job_id', 'inner');
        $this->db->join('alarm_discarded_reason as adr', 'adr.id = a.ts_discarded_reason', 'left');

        $this->db->join('property as p', 'p.property_id = j.property_id', 'left');
        $this->db->join('postcode as post', 'post.postcode = p.postcode');
        $this->db->join('sub_regions as sub_reg', 'sub_reg.sub_region_id = post.sub_region_id');
        $this->db->join('regions as reg', 'reg.regions_id = sub_reg.region_id');

        $this->db->join('alarm_pwr as ap', 'ap.alarm_pwr_id = a.alarm_power_id', 'left');
        $this->db->join('alarm_type as at', 'at.alarm_type_id = a.alarm_type_id', 'left');
        $this->db->join('staff_accounts as sa', 'sa.StaffID = j.assigned_tech', 'left');
        $this->db->where('a.ts_discarded', 1);


        //FILTERS
        // search reason
        if ($params['reason'] && $params['reason'] != "") {
            $this->db->where('a.ts_discarded_reason', $params['reason']);
        }

        // search state
        if ($params['state'] && $params['state'] != "") {
            $this->db->where('p.state', $params['state']);
        }

        // date filter
        if ($params['filterDate']['from'] != "" && $params['filterDate']['to'] != "") {
            $date_filter_str = " CAST( j.`date` AS Date )  BETWEEN '{$params['filterDate']['from']}' AND '{$params['filterDate']['to']}' ";
            $this->db->where($date_filter_str);
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

    public function get_alarm_power($params)
    {

        if ($params['sel_query'] && !empty($params['sel_query'])) {
            $sel_query = $params['sel_query'];
        } else {
            $sel_query = "*";
        }

        $this->db->select($sel_query);
        $this->db->from('alarm_pwr as ap');
        $this->db->where('ap.alarm_pwr_id >', 0);

        if ($params['alarm_pwr_id'] != "") {
            $this->db->where('ap.alarm_pwr_id', $params['alarm_pwr_id']);
        }

        if ($params['alarm_reason'] != "") {
            $this->db->where('ap.alarm_reason_id', $params['alarm_reason']);
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

    public function getAlarmType()
    {
        return $this->db->select('alarm_type_id, alarm_type')
            ->from('alarm_type')
            ->where('alarm_job_type_id', 2)
            ->order_by('alarm_type', 'asc')
            ->get();
    }

    public function sync_alarms($params)
    {

        $this->load->model('jobs_model');

        // get previous smoke alarms that is job status completed
        $prev_job_sql = $this->jobs_model->getPrevSmokeAlarm($params['property_id']);

        if ($prev_job_sql->num_rows() > 0 && $params['job_id'] > 0) {

            // sync alarms
            $this->jobs_model->snycSmokeAlarmData($params['job_id'], $prev_job_sql);

            // mark job as sync
            $job_data = array('alarms_synced' => 1);
            $this->db->where('id', $params['job_id']);
            $this->db->update('jobs', $job_data);
        }
    }

    public function get($params = [], $single = false, $num_rows = false)
    {
        $this->db->flush_cache();
        if ($num_rows) {
            $this->db->select("COUNT({$this->table}.alarm_id) as totalRecord");
        } elseif (
            isset($params["select_column"]) &&
            !empty($params["select_column"])
        ) {
            $this->db->select($params["select_column"]);
        } else {
            $this->db->select("*");
        }

        $this->db->from($this->table);

        # joins 
        if (
            isset($params['custom_joins']) &&
            !empty($params['custom_joins'])
        ) {
            foreach ($params['custom_joins'] as $custom_joins) {
                $this->db->join($custom_joins['join_table'], $custom_joins['join_on'], $custom_joins['join_type']);
            }
        }

        # filters
        if (
            isset($params['conditions']) &&
            !empty($params['conditions'])
        ) {
            if (!is_array($params['conditions'])) {
                $params['conditions'] = [$params['conditions']];
            }

            foreach ($params['conditions'] as $condition) {
                if ($condition["type"] == "raw") {
                    $this->db->where($condition["column"], false);
                } elseif (
                    !empty(@$condition["column"]) &&
                    !empty(@$condition["value"])
                ) {
                    $this->db->{$condition["type"]}($condition["column"], $condition["value"]);
                }
            }
        }

        if (!$num_rows) {
            if (isset($params['limit']) && isset($params['offset'])) {
                $this->db->limit($params['limit'], $params['offset']);
            } elseif (isset($params['limit']) && !empty($params['limit'])) {
                $this->db->limit($params['limit']);
            } else {
                //$this->db->limit(10);
            }
        }

        if (
            isset($params['orderby']) &&
            !empty($params['orderby'])
        ) {
            $this->db->order_by($params['orderby'], (isset($params['orderstate']) && !empty($params['orderstate']) ? $params['orderstate'] : 'DESC'));
        } else {
            $this->db->order_by("{$this->table}.alarm_id", 'DESC');
        }

        $query = $this->db->get();

        if (
            isset($params['debug']) &&
            $params['debug']
        ) {
            echo $this->db->last_query();
        }

        if ($num_rows) {
            $row = $query->row();
            return (isset($row->totalRecord) && !empty($row->totalRecord) ? $row->totalRecord : "0");
        }

        if ($single) {
            return $query->row();
        } elseif (
            isset($params['alarm_id']) &&
            !empty($params['alarm_id']) &&
            !is_array($params['alarm_id'])
        ) {
            return $query->row();
        }
        return $query->result();
    }

    public function set_data($params, $id = 0)
    {

        if (empty($params)) {
            return false;
        }

        $model_data = array();

        foreach ($this->columns as $value) {
            if (isset($params[$value])) {
                $model_data[$value] = $params[$value];
            }
        }

        if (empty($model_data)) {
            return false;
        }

        if (
            isset($params["alarm_id"]) &&
            !empty($params["alarm_id"])
        ) {
            # $id = $params["alarm_id"];
            unset($params["alarm_id"]);
        }

        $this->db->flush_cache();
        $this->db->trans_begin();

        if (!empty($id)) {
            $this->db->where('alarm_id', $id);
            $this->db->update($this->table, $model_data);
        } else {
            $this->db->insert($this->table, $model_data);
            $id = $this->db->insert_id();
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return $id;
    }

    public function delete($params)
    {

        $this->db->flush_cache();
        $this->db->trans_begin();

        # filters

        if (
            isset($params['conditions']) &&
            !empty($params['conditions'])
        ) {
            if (!is_array($params['conditions'])) {
                $params['conditions'] = [$params['conditions']];
            }

            foreach ($params['conditions'] as $condition) {
                if ($condition["type"] == "raw") {
                    $this->db->where($condition["column"], false);
                } elseif (
                    !empty(@$condition["column"]) &&
                    !empty(@$condition["value"])
                ) {
                    $this->db->{$condition["type"]}($condition["column"], $condition["value"]);
                }
            }
        } else {
            return false;
        }

        $this->db->delete($this->table);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
    }

    /**
     * Upload alarm photo
     *
     * @param mixed $alarm_id, $alarm_type_photo, $field_name
     *
     * @return [boolean]
     */
    public function upload_alarm_images($alarm_id, $alarm_type_photo, $field_name) {
        $status = FALSE;

        if (!empty($_FILES[$alarm_type_photo]['name'])) {
            // Load file upload library
            $config['upload_path'] = FCPATH . 'images/alarm_images/'; // Specify upload directory
            $config['allowed_types'] = 'jpeg|jpg|png'; // Specify allowed file types
            $config['max_size'] = 5000; // Maximum file size in kilobytes

            $this->load->library('upload');
            $this->upload->initialize($config);

            if (!$this->upload->do_upload($alarm_type_photo)) {
                // File upload failed
                $status = FALSE;
            } else {
                // File uploaded successfully
                $status = TRUE;

                // Retrieve the original file name
                $upload_data = $this->upload->data();
                $original_file_name = $upload_data['orig_name'];

                // Update or insert image filename
                $this->update_or_insert_alarm_image($alarm_id, $original_file_name, $field_name);
            }
        }

        return $status;
    }

    /**
     * Insert/update alarm_images
     *
     * @param mixed $alarm_id, $original_file_name, $field_name
     *
     * @return
     */
    public function update_or_insert_alarm_image($alarm_id, $original_file_name, $field_name) {

        $this->db->where('alarm_id', $alarm_id);
        $has_data = $this->db->count_all_results('alarm_images') > 0;
    
        if ($has_data) {
            $this->db->where('alarm_id', $alarm_id);
            $this->db->update('alarm_images', array($field_name => $original_file_name));
        } else {
            $data = array(
                'alarm_id' => $alarm_id,
                $field_name => $original_file_name, // Set your value here
                'created' => date('Y-m-d H:i:s'), // Current timestamp
                'active' => 1 // Assuming default value is 1
            );
        
            $this->db->insert('alarm_images', $data);
        }
    }
}
