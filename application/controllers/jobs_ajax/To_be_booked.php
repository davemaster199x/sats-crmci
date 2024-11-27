<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class To_be_booked extends CI_Controller {

	public function __construct(){
		parent::__construct(); 
        $this->load->database();
        $this->load->model('jobs_model');
        $this->load->helper('url');
    }   

	public function ajax_to_be_booked_assign_dk(){	
        $data['status'] = false;
        $job_id = $this->input->post('job_id');
        $tech_id = $this->input->post('tech_id');
        $date = $this->input->post('date');
        $date2 = date("Y-m-d",strtotime(str_replace("/","-",$date)));
        $staff_id = $this->session->staff_id;

        foreach($job_id as $val){

            // update job
            $job_data = array(
                'status' => 'To Be Booked',
                'assigned_tech' => $tech_id,
                'date' => $date2,
                'tech_notes' => 'Door Knock',
                'booked_with' => 'Agent',
                'booked_by' => $staff_id,
                'door_knock' => 1


            );
           $update_jobs =  $this->jobs_model->update_job($val,$job_data);

           if($update_jobs){ //TRUE

                //get tech name
                $tech_params = array(
                    'sel_query' => 'sa.FirstName, sa.LastName',
                    'staffID' => $tech_id
                );
                $tech = $this->system_model->getTech($tech_params)->row_array();
                $tech_name = $this->system_model->formatStaffName($tech['FirstName'],$tech['LastName']);

                //insert job log
                $log_details = "Door Knock Booked for {$date}. Technician {$tech_name}";
                $log_params = array(
                    'title' => 32,  //Door Knock Booked
                    'details' => $log_details,
                    'display_in_vjd' => 1,
                    'created_by_staff' => $staff_id,
                    'job_id' => $val
                );
                $this->system_model->insert_log($log_params);

                $data['status'] = true;

                /*
                // insert DK job as new row on tech run - this is Ben's instruction, tech's run sheet page "new job check" function can already do this
                if( $tech_id > 0 && $date2 != '' ){

                    // get tech run via tech and date filter
                    $this->db->select('tech_run_id');
                    $this->db->from('tech_run');                
                    $this->db->where('assigned_tech', $tech_id); 
                    $this->db->where('date', $date2); 
                    $tr_sql = $this->db->get();                    

                    if( $tr_sql->num_rows() > 0 ){

                        $tr_row = $tr_sql->row();

                        if( $tr_row->tech_run_id > 0 && $val > 0 ){

                            // insert to tech run 
                            $tech_run_data = array(
                                'tech_run_id' => $tr_row->tech_run_id,
                                'row_id_type' => 'job_id',
                                'row_id' => $val,
                                'sort_order_num' => 999999,
                                'dnd_sorted' => 0,
                                'created_date' => date('Y-m-d H:i:s'),
                                'status' => 1
                            );
                            
                            $this->db->insert('tech_run_rows', $tech_run_data);

                        }                                            

                    }
                    
                } 
                */               


           }


        }

        echo json_encode($data);
		
    }
    
    /**
     * Ajax rebook job request from TBB page
     * 
     * @param array $job_id_arr
     * @param int $is_240v
     * @param int $isDHA
     * 
     * @return json bool
     */
    public function ajax_rebook_script()
    {

        $json_response['status'] = false;
        $job_id_arr = $this->input->post('job_id');
        $is_240v = $this->input->post('is_240v');
        $isDHA = $this->input->post('isDHA');

        $rebook = $this->jobs_model->rebook_job($job_id_arr, $is_240v, $isDHA);

        if($rebook === true){
            $json_response['status'] = true;
        }

        echo json_encode($json_response);

    }

	

}

