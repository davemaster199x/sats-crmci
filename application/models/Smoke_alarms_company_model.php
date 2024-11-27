<?php

class Smoke_alarms_company_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function get_smoke_alarms_company_list() 
    {
        return $this->db->select('sac.*')
                        ->from('smoke_alarms_company as sac')
                        ->where('sac.active', 1)
                        ->order_by('sac.company_name', 'asc')
                        ->get();
    }

}
