<?php

class Agency_api_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function is_api_property_hidden($params){

        $api_prop_id = $params['api_prop_id'];
        $agency_id = $params['agency_id'];
        $api_id = $params['api_id'];

        if( $api_prop_id != '' ){

            $sql_str = "
            SELECT COUNT(hap.`id`) AS hap_count
            FROM `hidden_api_property` AS hap
            LEFT JOIN `agency_api_tokens` AS apt ON ( hap.`agency_id` = apt.`agency_id` AND apt.`api_id` = {$api_id} )
            WHERE hap.`api_prop_id` = '{$api_prop_id}'
            AND hap.`agency_id` = {$agency_id}
            AND apt.`api_id` = {$api_id}
            ";

            $sql = $this->db->query($sql_str);

            $hap_count = $sql->row()->hap_count;

            if( $hap_count > 0 ){
                return true;
            }else{
                return false;
            }

        }        

    }


    public function mark_property_cant_connect_to_api($params_obj){

        $property_id = $params_obj->property_id;
        $cant_connect_to_api_reason = $params_obj->cant_connect_to_api_reason;

        if( $property_id > 0 ){

            // check if marked as "do not connect to API"
            $pccta_sql = $this->db->query("
            SELECT COUNT(`pccta_id`) AS count
            FROM `property_cant_connect_to_api`
            WHERE `property_id` = {$property_id}
            ");

            if( $pccta_sql->row()->count > 0 ){ // exist, update

                $update_data = array(
                    'comment' => $cant_connect_to_api_reason
                );
                
                $this->db->where('property_id', $property_id);
                $this->db->update('property_cant_connect_to_api', $update_data);

            }else{ // new, inserrt

                $insert_data = array(
                    'property_id' => $property_id,
                    'comment' => $cant_connect_to_api_reason
                );        
                $this->db->insert('property_cant_connect_to_api', $insert_data);

            } 

        }              

    }

    public function unmark_property_cant_connect_to_api($property_id){

        if( $property_id > 0 ){

            $this->db->where('property_id', $property_id);
            $this->db->delete('property_cant_connect_to_api');     

        }
         

    }


}
