<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Properties_ajax extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();

        $this->load->model('properties_model');
    }

    public function ajax_get_agency_from_other_company()
    {
        $success = false;
        $id = $this->input->post('agency_id');
        $data = array();

        if ( !empty($id) ) {

            $agency_count = $this->properties_model->get_count_agencies_from_other_company($id);

            if ($agency_count > 0) {
            	$data = $this->properties_model->get_agencies_from_other_company($id);
                $success = true;
            }

        }

        echo json_encode(['data' => $data, 'success' => $success]);
    }

    public function ajax_get_agency_user_accounts()
    {
        $success = false;
        $agency_id = $this->input->post('agency_id');
        $data = array();

        try {
            if ( !empty($agency_id) ) {
                $agency_user_accounts = $this->db->from('agency_user_accounts')->where('agency_id', $agency_id)->where('active', 1)->get()->result();
		        $data['agency_user_accounts'] = $agency_user_accounts[0];

                if (!empty($data['agency_user_accounts'])) {
                    $success = true;
                }                
            }            

        } catch(Exception $e) { //catch exception
            echo 'Message: ' .$e->getMessage();
        }

        echo json_encode(['data' => $data, 'success' => $success]);
    }

    public function ajax_update_agency_property()
    {
        $success                = false;
        $previous_agency_id     = (int) $this->input->post("previous_agency_id");
        $current_agency_id      = (int) $this->input->post("current_agency_id");
        $current_pm_id          = (int) $this->input->post("current_pm_id");
        $property_id            = (int) $this->input->post("property_id");
        $staff_id               = (int) $this->session->staff_id;

        $job_list_ids = [];
        $data = [];
        $data2 = [];

        // added new variable name
        $update_to_agency_id = (int) $this->input->post("current_agency_id");

        try {


            /*
            //get all job_ids 
            $prev_job_ids = $this->db->query("
                            SELECT j.id, j.service, j.property_id, j.job_price
                            FROM jobs as j
                            WHERE j.property_id = {$property_id} AND j.`status` != 'Completed'
                        ");

            $exclude_agencies = $this->db->query("
                SELECT *
                FROM `price_increase_excluded_agency`
                WHERE `agency_id` = {$current_agency_id}
                AND (
                    `exclude_until` >= '".date('Y-m-d')."' OR
                    `exclude_until` IS NULL
                )
            "); 
        
            $is_excluded = ( $exclude_agencies->num_rows() > 0 )? 1 : 0;

            if ($is_excluded === 1) {                
                
                //get agency_services price
                $get_agency_serv =  $this->db->query("
                    SELECT price, service_id FROM `agency_services` WHERE agency_id = {$current_agency_id}
                ")->result();

                foreach($get_agency_serv as $agen_row) {

                    // $price_var_params = array(
                    //     'service_type' => $agen_row->service_id,
                    //     'property_id' => $property_id
                    // );
                    // $price_var_arr = $this->system_model->get_property_price_variation($price_var_params);
                    // $job_price = $price_var_arr['dynamic_price_total']; // agency service price   
                    // $final_job_price = $price_var_arr['price_breakdown_text'];
                    // $data[] = $final_job_price;

                    $update_prop_sql = $this->db->query("
                        UPDATE `property_services` SET `price` = {$agen_row->price} 
                        WHERE property_id = {$property_id} AND alarm_job_type_id = {$agen_row->service_id}
                    ");

                }

                // $get_property_serv =  $this->db->query("
                //     SELECT price, alarm_job_type_id FROM `property_services` WHERE property_id = {$property_id}
                // ")->result();

                // foreach($prev_job_ids->result() as $job_row) {

                //     $update_jobs_sql = $this->db->query("
                //         UPDATE `jobs` SET `job_price` = {$job_price} WHERE id = {$job_row->id}
                //     ");
                // }

                // echo "<pre>";
                // var_dump($data);
                // exit;

            } else {

                foreach($prev_job_ids->result() as $job_row) {
                
                    // //get agency_services price
                    $price_var_params = array(
                        'service_type' => $job_row->service,
                        'property_id' => $property_id
                    );
                    $price_var_arr = $this->system_model->get_property_price_variation($price_var_params);
                    $job_price = $price_var_arr['dynamic_price_total']; // agency service price                    
                    
                    // $data[] = $job_price;
                    //check if exist
                    // $new_price = $get_agency_serv_price->price ? $get_agency_serv_price->price : 0;
                    // $new_price = number_format($new_price,2);
                    
                    //Update jobs.price
                    $update_jobs_sql = $this->db->query("
                        UPDATE `jobs` SET `job_price` = {$job_price} WHERE id = {$job_row->id}
                    ");   
                }
            }
            */
        
            //Update agency_id on property table
            $this->db->query("
                UPDATE property SET 
                    `agency_id` = {$current_agency_id},
                    `propertyme_prop_id` = NULL,
                    `palace_prop_id` = NULL, 
                    `pm_id_new` = {$current_pm_id}
                WHERE property_id = {$property_id}
            ");

            // must be place AFTER property UPDATE
            // update property and job price

            // get active("serviced to SATS") property service
            $ps_sql = $this->db->query("
            SELECT 
                `property_services_id`,
                `property_id`,
                `alarm_job_type_id`
            FROM `property_services`
            WHERE `service` = 1
            AND `property_id` = {$property_id}
            ");

            // loop update
            foreach( $ps_sql->result() as $ps_row ){

                // check if the agency to update to has the property service type
                $agency_serv_sql = $this->db->query("
                SELECT COUNT(`agency_services_id`) AS agency_serv_count
                FROM `agency_services`
                WHERE `agency_id` = {$update_to_agency_id}
                AND `service_id` = {$ps_row->alarm_job_type_id}
                ");

                if( $agency_serv_sql->row()->agency_serv_count > 0 ){ // property service exist on this agency

                    // get price from agency variation
                    $price_var_params = array(
                        'service_type' => $ps_row->alarm_job_type_id,
                        'agency_id' => $update_to_agency_id
                    );
                    $price_var_arr = $this->system_model->get_agency_price_variation($price_var_params);	
                    $ps_dynamic_price = $price_var_arr['dynamic_price_total'];	
                    
                    if( $ps_row->property_services_id > 0 ){

                        // update property service price
                        $ps_sql_update_str = "
                        UPDATE `property_services`
                        SET `price` = {$ps_dynamic_price}
                        WHERE `property_services_id` = {$ps_row->property_services_id}
                        ";
                        $this->db->query($ps_sql_update_str);

                    }                    

                    // get "non-completed" active jobs
                    $job_sql = $this->db->query("
                    SELECT 
                        j.`id` AS j_id,
                        j.`assigned_tech`,
                        j.`service`,

                        ajt.`bundle`,
                        ajt.`bundle_ids`
                    FROM `jobs` AS j		
                    LEFT JOIN `alarm_job_type` AS ajt ON j.`service` = ajt.`id`
                    WHERE j.`property_id` = {$property_id}
                    AND j.`service` = {$ps_row->alarm_job_type_id}
                    AND j.`status` NOT IN('Completed','Merged Certificates','Pre Completion','Cancelled') 
                    AND j.`del_job` = 0 	
                    ");

                    if( $job_sql->num_rows() > 0 ){

                        foreach( $job_sql->result() as $job_row ){

                            if( $job_row->j_id > 0 ){

                                // get price from property variation
                                $price_var_params = array(
                                    'service_type' => $ps_row->alarm_job_type_id,
                                    'property_id' => $property_id
                                );
                                $price_var_arr = $this->system_model->get_property_price_variation($price_var_params);
                                $dynamic_price = $price_var_arr['dynamic_price_total'];
                    
                                // update job price
                                $sql_update_str = "
                                UPDATE `jobs`
                                SET `job_price` = {$dynamic_price}
                                WHERE `id` = {$job_row->j_id}
                                ";
                                $this->db->query($sql_update_str);

                                // SOFT delete job variation
                                $this->db->query("
                                UPDATE `job_variation`
                                SET `active` = 0
                                WHERE `job_id` = {$job_row->j_id}
                                AND `reason` != 3 
                                ");

                            }                            

                        }                                                                           

                    }

                }                

            }   
                        
            // SOFT delete property variation
			$this->db->query("
			UPDATE `property_variation` AS pv 
			LEFT JOIN `agency_price_variation` AS apv ON pv.`agency_price_variation` = apv.`id`
			SET pv.`active` = 0
			WHERE pv.`property_id` = {$property_id}
			AND apv.`reason` != 3
			");

            // Update api_property_data
            $this->db->query("
                UPDATE api_property_data SET 
                    `api_prop_id` = NULL, 
                    `active` = 0
                WHERE crm_prop_id = {$property_id}
            ");

            // Update property_source
            $this->db->query("
                UPDATE `properties_from_other_company` SET 
                    `active` = 0
                WHERE `property_id` = {$property_id}
            ");

            $staff_result = $this->db->query("
                SELECT * FROM `staff_accounts` WHERE `StaffID` = {$staff_id}
            ")->row();

            $prev_prop_row = $this->db->query("
                SELECT 
                    property_id,
                    address_1,
                    address_2,
                    address_3,
                    state,
                    postcode
                FROM `property`
                WHERE `property_id` = {$property_id}
            ")->row_array();

            $property_address = "{$prev_prop_row['address_1']} {$prev_prop_row['address_2']} {$prev_prop_row['address_3']} {$prev_prop_row['state']} {$prev_prop_row['postcode']}";

            // get previous Agency address
            $prev_agency_data =  $this->db->query("
                SELECT `agency_name`
                FROM `agency`
                WHERE `agency_id` = {$previous_agency_id}
            ")->row();
            $prev_agency_name = $prev_agency_data->agency_name; 

            // get current Agency address
            $curr_agency_data = $this->db->query("
                SELECT `agency_name`
                FROM `agency`
                WHERE `agency_id` = {$current_agency_id}
            ")->row();
            $curr_agency_name = $curr_agency_data->agency_name; 

            /*
            // property logs
            $data = array(
                'property_id' => $property_id,
                'staff_id' => $staff_id,
                'event_type' => 'Agency Changed',
                'event_details' => 'Changed From {$prev_agency_name} to {$curr_agency_name}',
                'log_date' => date('Y-m-d H:i:s')
            );
            $this->db->insert('property_event_log', $data);
            */

            $details =  "Changed Agency from <b>{$prev_agency_name}<b/> to <b>{$curr_agency_name}</b>";
            $params = array(
                'title' => 65, // Property Update
                'details' => $details,
                'display_in_vpd' => 1,									
                'created_by_staff' => $staff_id,
                'property_id' => $property_id
            );
            $this->system_model->insert_log($params);

            $property_url = "/properties/details?id={$property_id}&tab=1";
            $prev_agency_url = "/agency/view_agency_details/{$previous_agency_id}";
            $curr_agency_url = "/agency/view_agency_details/{$current_agency_id}";

            $alert_html = "
                <div class='alert alert-success alert-dismissible fade show' role='alert'>
                    <a href='{$property_url}'>
                        {$property_address}
                    </a> 
                    Successfully Changed from <a href='{$prev_agency_url}'>{$prev_agency_name}</a> to <a href='{$curr_agency_url}'>{$curr_agency_name}</a>
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>×</span>
                    </button>
                </div>
            ";

            $data = [
                'property_address' => $property_address,
                'prev_agency_name' => $prev_agency_name,
                'curr_agency_name' => $curr_agency_name,
                'alert_html'       => $alert_html
            ];

            $success = true;

                
        } catch(Exception $e) { //catch exception
            echo 'Message: ' .$e->getMessage();
        }

        echo json_encode(['success' => $success, 'data' => $data]);
    }    


}