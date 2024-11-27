<?php

class Contacts extends MY_ApiController {

    public function __construct() {
        parent::__construct();

        $this->load->model('users_model');
    }

    public function index() {
        $country_id = $this->config->item('country');

        $sel_query = "
        DISTINCT(sa.staffID),
        sa.`staffID` AS sa_staffid,
		sa.`FirstName` AS sa_firstname,
		sa.`LastName` AS sa_lastname,
        sa.`sa_position` AS sa_position,
        sa.`contactNumber` AS contactNum,
        sa.`Email` AS sa_email,
        sa.`ipad_prepaid_serv_num AS sa_ipad_prepaid_serv_num`,
        sa.`TechID` AS sa_techID,
        sa.`active` AS sa_active,
        sa.address AS sa_address,

        sc.`className` AS sc_classname,
        sc.`ClassID` AS sc_classID
        ";

        //TECHS MAIN QUERY
        $params = array(
            'sel_query' => $sel_query,
            'join_table' => array('cc','accomodation'),
            'sa_deleted' => 0,
            'sa_active' => 1,
            'sort_list' => array(
                array(
                    'order_by' => 'sa.FirstName',
                    'sort' => 'ASC',
                ),
                array(
                    'order_by' => 'sa.LastName',
                    'sort' => 'ASC',
                )
            ),
        );

        //states dropdown filter
        $state_query = $this->db->select('state')->from('states_def')->where('country_id', $country_id)->order_by('state','ASC')->get();
        $states = $state_query->result_array();


        $contacts_list = $this->users_model->get_users($params)->result_array();
        //echo $this->db->last_query();
        //exit();

        $this->api->setStatusCode(200);
        $this->api->setSuccess(true);
        $this->api->putData('contacts', $contacts_list);
        $this->api->putData('states', $states);
    }

    public function search() {
        $keyword = trim($this->api->getPostData('key'));
        $country_id = $this->config->item('country');

        if ($keyword) {
            $sel_query = "
            DISTINCT(sa.staffID),
            sa.`staffID` AS sa_staffid,
            sa.`FirstName` AS sa_firstname,
            sa.`LastName` AS sa_lastname,
            sa.`sa_position` AS sa_position,
            sa.`contactNumber` AS contactNum,
            sa.`Email` AS sa_email,
            sa.`ipad_prepaid_serv_num AS sa_ipad_prepaid_serv_num`,
            sa.`TechID` AS sa_techID,
            sa.`active` AS sa_active,
            sa.address AS sa_address,

            sc.`className` AS sc_classname,
            sc.`ClassID` AS sc_classID
            ";

            //TECHS MAIN QUERY
            $params = array(
                'sel_query' => $sel_query,
                'join_table' => array('cc','accomodation'),
                'sa_deleted' => 0,
                'user_search'     => 1,
                'sa_active' => 1,
                'keyword'   => $keyword,
                'sort_list' => array(
                    array(
                        'order_by' => 'sa.FirstName',
                        'sort' => 'ASC',
                    ),
                    array(
                        'order_by' => 'sa.LastName',
                        'sort' => 'ASC',
                    )
                ),
            );

            $contacts_list = $this->users_model->get_users($params)->result_array();
            //echo $this->db->last_query();
            //exit();
            if(!empty($contacts_list)){
                $this->api->setStatusCode(200);
                $this->api->setSuccess(true);
                $this->api->putData('contacts', $contacts_list);
                $this->api->setMessage('Success');

                return;
            }
            else{
                $this->api->setStatusCode(200);
                $this->api->setSuccess(true);
                $this->api->putData('contacts', $contacts_list);
                $this->api->setMessage('Empty Result.');
            }
            
        }

        $this->api->setStatusCode(200);
        $this->api->setSuccess(false);
        $this->api->putData('contacts', $contacts_list);
        $this->api->setMessage('Empty Result.');
    }

}