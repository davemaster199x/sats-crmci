<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agency_ajax extends CI_Controller {

  public function __construct() {
      parent::__construct();
      $this->load->database();
      $this->load->library('email');
      $this->load->helper('email_helper');
      $this->load->model('agency_model');
  }

  public function ajax_high_touch_update_v2()
  {
      $agency_id = $this->input->get_post('agency_id');
      $priority = $this->input->get_post('priority');
      $priority_reason = $this->input->get_post('priority_reason');
      $ht_date_added = $this->input->get_post('ht_date_added');
      $unmark = $this->input->get_post('unmark');
      $staff_id = $this->session->staff_id;
      $success = 0;

      $this->load->model('email_model');

      $data['staff'] =  $this->staff_accounts_model->get_staff_accounts_details($staff_id);
      $data['agency_name']    = $this->agency_model->get_agency_details($agency_id);

      if (empty($priority_reason) && $priority > 0) {
        echo json_encode(array('error' => $success));
        return false;
      } 
      else {
        if (empty($priority_reason) && $priority == 0) {
            $reason = "";
        } 
        else{
            $reason = "because";
        }
        $update_ht = $this->agency_model->save_agency_high_touch($agency_id, $priority, $priority_reason, $staff_id);
      }

      if ($update_ht) {
          $success = 1;
          $marked_str = ( $priority >= 0 && $unmark == 0 )? 'marked' : 'unmarked';
          if($priority == 1){
            $ap = "High Touch (HT)";
          }
          else if($priority == 2){
            $ap = "Very Important Person (VIP)";
          }
          else if($priority == 3){
            $ap = "Handle With Care (HWC)";
          }
          else{
            $ap = "Regular Agency";
          }

          //insert log
          $log_details = "Agency <b>{$marked_str}</b> as {$ap} {$reason} {$priority_reason}";
          $log_params = array(
              'title' => 46,  // Agency Update
              'details' => $log_details,
              'display_in_vad' => 1,
              'created_by_staff' => $this->session->staff_id,
              'agency_id' => $agency_id
          );
          $this->system_model->insert_log($log_params);

          // email settings
          $email_config = Array(
              'mailtype' => 'html',
              'charset' => 'utf-8'
          );

          $from_name = "Smoke Alarm Testing Services";

          $from_email = $country_row->outgoing_email;
          $subject = 'Agency Priority Updated';

          $data['priority'] = ( $priority >= 0 && $unmark == 0 )? 'marked' : 'unmarked';
          $data['priority_reason'] =  $priority_reason;
          $data['agency_id'] = $agency_id;
          $data['abb'] = $ap;

          // content
          $to_email = make_email('info');
          $cc_email = make_email('sales');
          $email_body = $this->load->view('emails/agency_high_touch_email', $data, true);

          $this->email->to($to_email);
          $this->email->cc($cc_email);
          $this->email->subject($subject);
          $this->email->message($email_body);

          // send email
          if ($this->email->send()) {
              $ret_json = array(
                  'success' => $success
              );
          } else {
              echo 'error!'; die();
          }
      }

      echo json_encode($ret_json);
  }

  public function ajax_agency_api_documents()
  {
    $success = false;
    $is_invoice = $this->input->post('is_invoice');
    $is_certificate = $this->input->post('is_certificate');
    $field = $this->input->post('field');
    $agency_id = $this->input->post('agency_id');
    $date = date("Y-m-d H:i:s"); 

    try {

      if (!empty($agency_id)) {
        $success = 1;

        //check if agency_id exists on agency_api_documents
        $count = $this->agency_model->get_agency_api_documents($agency_id)->num_rows();
  
        if ($count > 0) {
          //update
          $data = array(
            'agency_id' => $agency_id,
            'is_invoice' => $is_invoice,
            'is_certificate' => $is_certificate,
            'date_modified' => $date
          );
          $this->agency_model->update_agency_api_documents($data);

          $log_details = "Agency API <strong>{$field}</strong> push preference updated to [Upload/Email]";
          $log_params = array(
              'title' => 46, // agency
              'details' => $log_details,
              'display_in_vad' => 1,
              'created_by_staff' => $this->session->staff_id,
              'agency_id' => $agency_id
          );
          $this->system_model->insert_log($log_params);

        } else { 
          //insert
          $data = array(
            'agency_id' => $agency_id,
            'is_invoice' => $is_invoice,
            'is_certificate' => $is_certificate,
            'date_created' => $date
          );
          $this->agency_model->insert_agency_api_documents($data);

          $log_details = "<strong>Agency API <strong>{$field}</strong> push preference updated to [Upload/Email]</strong>";
          $log_params = array(
              'title' => 46, // agency
              'details' => $log_details,
              'display_in_vad' => 1,
              'created_by_staff' => $this->session->staff_id,
              'agency_id' => $agency_id
          );
          $this->system_model->insert_log($log_params);
        }
        
      } else {
        throw new Exception('Agency ID is empty!');
      }

    } catch (Exception $e) {
      echo 'Message: ' .$e->getMessage();
    }

    echo json_encode(['success' => $success]);

  }

  /**
  * This will load the page for
  * This function is used to export data from Active Agency List - Mailer Export Button
  * @return void
  */
  public function get_ajax_active_agency_list()
  {
    $result = $this->agency_model->get_active_agency_list_data()->result();

    echo json_encode([
        'data' => $result
    ]);
  }

  /**
   * This function is used to export data from Agency List - View Agencies Export button
   * @return mixed
   */
  public function get_export_view_agencies_button()
  {
    $current_date = new DateTimeImmutable();
    $result = $this->agency_model->get_active_agency_list_data()->result_array();
    $filename = "View_Agencies_" . $current_date->format('Y-m-d') . ".csv";

    // Set headers for CSV download
    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename={$filename}");

    // Open the output stream
    $output = fopen('php://output', 'w');

    $headers = [
      "Agency Name", "Legal Name", "Address", "Suburb",	"Postcode",	"State", "Region", "Phone",	"Accounts Email",	"Agency Email", "Agency Contact",	"Contact Phone",
      "Contact Email",	"Sales Rep",	"Active Properties",	"Properties",	"Last Contact",	"Smoke Alarms", "Price",	"Corded Windows",	"Price",	"Water Meter",
      "Price",	"Smoke Alarm & Safety Switch",	"Price", "Bundle SA.CW.SS", "Price",	"Smoke Alarm & Water Meter", "Price",	"Smoke Alarms (IC)",	"Price",
      "Smoke Alarm & Safety Switch (IC)",	"Price",	"Bundle SA.CW.SS (IC)",	"Price",	"Water Efficiency", "Price",	"Smoke Alarms & Water Efficiency",	"Price",
      "Bundle SA.SS.WE",	"Price",	"Bundle SA.SS.CW.WE",	"Price", "Smoke Alarms & Corded Windows",	"Price", "Smoke Alarms & Corded Windows (IC)",	"Price",
      "Smoke Alarm & Safety Switch (View Only)", "Price",	"Bundle SA.CW.SSv",	"Price",	"Smoke Alarm & Safety Switch (View Only) (IC)",	"Price",
      "Bundle SA.CW.SSv (IC)",	"Price", "Bundle SA.SSv.WE",	"Price",	"Bundle SA.SSv.CW.WE",	"Price",

      "9v", "240v", "9vLi", "240vLi", "CO", "Batteries", "3vLi", "9vLi RF", "240vLi RF", "240v RF", "240vLi", "3vLiRF",
      "6vLiRF(cav)", "240vRF(cav)", "3vLiFP", "ABAX16", "3vLiRF Heat", "9v(EP)", "3vLi(EP)", "3vLiRF(EP)", "240v(EP)", "240vRF(EP)", "EP Wall Controller",
      "240v (RED)", "240vLi (RED)", "3vLiRF(RED)", "240vRF (RED)", "3vLi (RED)", "9v (RED)", "Safety Switch", "Red Wall Controller", "Low Voltage",

      "Franchise Group", "Country", "Country	Email Certificates?",	"Combined Cert / Invoice PDF?",	"Send Entry Notice?",
      "Work Order Required?",	"Auto Renew?", "Key Access Allowed?",	"Tenant Key Email Required?",	"Trust Acc",	"Activated Date",	"Source of Client"
    ];

    fputcsv($output, $headers);

    foreach ($result as $key => $row) {
      $agency_id = $row['agency_id'] ?? 0;

      $rowData = [
        'agency_name' => $row['agency_name'] ?? '',
        'legal_name' => $row['legal_name'] ?? '',
        'agency_address'  => $row['agency_address'] ?? '',
        'address3' => $row['address3'] ?? '',
        'postcode' => $row['postcode'] ?? '',
        'state' => $row['state'] ?? '',
        'region' => $row['region'] ?? '',
        'phone' => $row['phone'] ?? '',
        'accounts_emails' => $row['account_emails'] ?? '',
        'agency_emails' => $row['agency_emails'] ?? '',
        'agency_contact' => $row['agency_contact'] ?? '',
        'contact_phone' => $row['contact_phone'] ?? '',
        'contact_email' => $row['contact_email'] ?? '',
        'sales_rep' => $row['sales_rep'] ?? '',
        'active_properties' => $row['property_count'] ?? '',
        'tot_properties' => $row['tot_properties'] ?? '',
        'last_contact' => $row['last_contact'] ?? '',
      ];

      $ajt_sql = $this->agency_model->getActiveServices();
      foreach ($ajt_sql->result_array() as $ajt) {
        $trimJobTypes = trim($ajt['short_name']);
        $agency_service_price = $this->agency_model->getAgencyServicePrice($agency_id, $ajt['id']);
        $rowData[$trimJobTypes] = $this->system_model->getServiceCount($agency_id, $ajt['id']);

        $typePriceKey = trim($ajt['short_name']).'_price';
        $rowData[$typePriceKey] = ($agency_service_price > 0) ? sprintf ("$%s", number_format ($agency_service_price, 2)) : '';
      }

      $alarm_pwr_sql = $this->agency_model->getAlarmPower();
      foreach($alarm_pwr_sql->result_array() as $alarm_pwr){
          $alarm_price = $this->agency_model->getAgencyAlarmsPrice($agency_id, $alarm_pwr['alarm_pwr_id']);
          $rowData[] = ($alarm_price > 0) ? "$".number_format($alarm_price,2) : '';
      }

      $rowData2 = [
        'franchise_group_id'  => $row['franchise_groups_id'],
        'country_id'  => (int)$row['country_id'] === 1 ? "AU" : "NZ",
        'send_emails' => $row['send_emails'] ? 'Yes' : 'No',
        'send_combined_invoice' => $row['send_combined_invoice'] ? 'Yes' : 'No',
        'send_entry_notice' => $row['send_entry_notice'] ? 'Yes' : 'No',
        'require_work_order' => $row['require_work_order'] ? 'Yes' : 'No',
        'auto_renew' => $row['auto_renew'] ? 'Yes' : 'No',
        'key_allowed' => $row['key_allowed'] ? 'Yes' : 'No',
        'key_email_required'  => $row['key_email_required'] ? 'Yes' : 'No',
        'tsa_name' => $row['tsa_name'],
        'activated_date' => $row['activated_date'],
        'source_of_client' => $row['source_of_client'],
      ];

      $newData = array_merge($rowData, $rowData2);
      fputcsv($output, $newData);
    }

    fclose($output);
    exit;
  }

}