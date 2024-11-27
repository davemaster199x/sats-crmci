<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Daily_ajax extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->database();
    $this->load->model('daily_model');
  }


  public function ajax_is_acknowledge_update()
  {
    $success = true;
    $message = 'Successfully save changes.';
    $property_id = $this->input->post('property_id');
    $acknowledge = $this->input->post('acknowledge');
    $staff_id = $this->session->staff_id;

 
    $params = array(
      'property_id'         => $property_id,
      'is_acknowledge'      => $acknowledge,
      'staff_id'            => $staff_id
    );

    if (!$this->daily_model->update_intentionally_hidden_active_properties($params)) {
      $success = false;
      $message = 'An error occurred while saving data.';
    }
    
    $response = [
      'success' => $success,
      'message' => $message,
    ];

    echo json_encode($response); 
  }

  public function ajax_is_acknowledge_multiple_update()
  {
    $selectedRecords = $this->input->post('selectedRecords');
    $property_id = $this->input->post('property_id');
    $staff_id = $this->session->staff_id;
    
    $success = true;
    $message = 'Successfully save changes.';

    foreach ($selectedRecords as $key => $selectedRecord) {
    
      $params = array(
        'property_id'         => $property_id[$key],
        'is_acknowledge'      => $selectedRecord,
        'staff_id'            => $staff_id
      );

      if (!$this->daily_model->update_intentionally_hidden_active_properties($params)) {
          $success = false;
          $message = 'An error occurred while saving data.';
          break;
      }
    }

    $response = [
      'success' => $success,
      'message' => $message,
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
  }

  /**
   * Snooze property for 30 days in 'daily/view_no_active_job_properties' page
   * params $property_id
   * @return json obj
   */
  public function ajaxSnoozeProperty()
  {
    $response['status'] = FALSE;

    $property_id = $this->input->post('prop_id');
    $prop_array_unique = array_unique($property_id);
    $snoozeData = [];
    $logsData = [];
    //$del_snooze_type_id = [];

    foreach($prop_array_unique as $prop_row_id){
      //set data to array for batch insert
      $snoozeData[] = [
        'property_id'       => $prop_row_id,
        'hidden'            => 1,
        'hidden_from_pages' => 2,
        'added_by'          => $this->session->staff_id,
        'date_created'      => date('Y-m-d H:i:s'),
        'date_modified'     => date('Y-m-d H:i:s')
      ];

      //set data for logs branch insert
      $logsData[] = [
        'title'             => 68,
        'details'           => "Property snoozed from 'No Active Job Properties' Page",
        'display_in_vpd'    => 1,
        'created_by_staff'  => $this->session->staff_id,
        'created_date'      => date('Y-m-d H:i:s'),
        'property_id'       => $prop_row_id
      ];

      //set delete id in array
      /*$del_snooze_type_id[] = $prop_row_id;*/
    }

    $this->db->trans_begin();

    //Disable/Inactivate the old one if alreay exist
    /*$del_snooze_type_id_imp = implode(", ", $del_snooze_type_id);
    $this->db->query("DELETE FROM snooze WHERE snooze_type = 1 AND snooze_type_id IN({$del_snooze_type_id_imp})");*/

    //Insert data in batch
    $this->db->insert_batch('hidden_properties', $snoozeData);
    $this->db->insert_batch('logs', $logsData);

    if($this->db->trans_status() === FALSE){
      $this->db->trans_rollback();
      log_message('error', 'view_no_active_job_properties: Query error forced a rollback');
    }else{
      $this->db->trans_commit();
      log_message('info', 'view_no_active_job_properties: Query completed successfully');
      $response['status'] = TRUE;
    }

    echo json_encode($response);
  }

}