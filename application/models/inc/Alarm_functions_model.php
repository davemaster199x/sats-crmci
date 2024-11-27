<?php

class Alarm_functions_model extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
        $this->load->model('/inc/functions_model');
    }

    /**
     * This appears to be only used for smoke alarms and switches so i havent added additional checks for other services
     * @param mixed $job_id
     * @param int $incnew
     * @param int $discarded
     * @param int $alarm_job_type_id
     * 
     * @return [obj]
     */
    public function getPropertyAlarms($job_id, $incnew = 1, $discarded = 1, $alarm_job_type_id = 1)
    {
	    $smoke_alarm_ajt_id = $smoke_alarm_ajt_id ?? 0;
	    // Main SQL
		$sql = "  SELECT  a.*, 
							p.alarm_pwr, 
							t.alarm_type, 
							r.alarm_reason, 
							adr.reason AS discarded_reason_text
                    FROM 	alarm a 
                    LEFT JOIN alarm_pwr p ON a.alarm_power_id = p.alarm_pwr_id
                    LEFT JOIN alarm_type t ON t.alarm_type_id = a.alarm_type_id
                    LEFT JOIN alarm_reason r ON r.alarm_reason_id = a.alarm_reason_id
                    LEFT JOIN alarm_discarded_reason adr ON a.ts_discarded_reason = adr.id
                    WHERE a.job_id = '" . $job_id . "'";

		// Conditional WHERE SQL
        if(in_array($alarm_job_type_id, [4,5])) {
	        // Safety Switch view and mech should have same alarms
	        $sql .= " AND a.alarm_job_type_id IN (4,5)";
        } else {
	        $smoke_alarm_ajt_id = Alarm_job_type_model::get_bundled_smoke_alarm_service_id($alarm_job_type_id);
			if($smoke_alarm_ajt_id){
                // Commented as it was forcing alarms not to show on jobs, when we later see the cause of the different ajt_id and service, this might be reimplented.
				// $sql .= " AND a.alarm_job_type_id = {$smoke_alarm_ajt_id}";
			}
        }

	    if($incnew == 0) $sql .= " AND a.New = 0";
	    if($incnew == 2) $sql .= " AND a.New = 1";

	    if($discarded == 0) $sql .= " AND a.ts_discarded = 0";
	    if($discarded == 2) $sql .= " AND a.ts_discarded = 1";

	    $sql .= " ORDER BY a.alarm_id ASC ";

		$query = $this->db->query($sql) or die(mysql_error());
	    foreach ($query->result_array() as $row) {
		    $alarms[] = $row;
	    }

        return $alarms;
    }

    /**
     * Get alarm_pwr
     * 
     * @param int $alarm_job_type_id
     * 
     * @return [obj]
     */
    public function alarmGetAlarmPower($alarm_job_type_id = 1)
    {
        #Get alarm pwr
        $query = "SELECT * FROM alarm_pwr WHERE alarm_job_type_id = {$alarm_job_type_id}";
        $alarm_pwr = $this->db->query($query);

        return $alarm_pwr;
    }

    /**
     * Get alarm_type
     * 
     * @param int $alarm_job_type_id
     * 
     * @return [obj]
     */
    public function alarmGetAlarmType($alarm_job_type_id = 1)
    {
        #Get alarm type
        $query = "SELECT * FROM alarm_type WHERE alarm_job_type_id = {$alarm_job_type_id}";
        $alarm_type = $this->db->query($query);

        return $alarm_type;
    }

    /**
     * Get alarm_reason 
     * 
     * @param int $alarm_job_type_id
     * 
     * @return [obj]
     */
    public function alarmGetAlarmReason($alarm_job_type_id = 1)
    {
        #Get alarm reason
        $query = "SELECT * FROM alarm_reason WHERE alarm_job_type_id = {$alarm_job_type_id} ORDER BY `alarm_reason` ASC";
        $alarm_reason = $this->db->query($query);

        return $alarm_reason;
    }

    /**
     * Get data from 'alarm_discarded_reason' table
     * 
     * @param mixed $id=NULL
     * @param mixed $active=NULL
     * 
     * @return [obj]
     */
    public function getAlarmDiscardedReason($id = NULL, $active = NULL)
    {
        if((int) $id && $id != "")
        {
            $where['id'] = $id;
        }

        if(isset($active))
        {
            $where['active'] = $active;
        }

        return $this->db->order_by('reason', 'ASC')->get_where('alarm_discarded_reason', $where);
    }

    /**
     * Verifies if alarm is discarded or not
     * 
     * @param mixed $alarm_id
     * 
     * @return [bool]
     */
    public function alarmIsDiscarded($alarm_id)
    {
        if((int) $alarm_id)
        {
            $where = ['alarm_id' => $alarm_id];
            $q =  $this->db->get_where('alarm', $where)->row_array();
            $ts_discarded = $q['ts_discarded'];
            
            if($ts_discarded == 1){
                return TRUE;
            }
        }
        return FALSE;
    }

}
