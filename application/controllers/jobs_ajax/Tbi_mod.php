<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tbi_mod extends CI_Controller {

  	private $datafields;
  	private $lastIdInserted;

	public function __construct(){
		parent::__construct(); 
        $this->load->database();
        $this->load->model('jobs_model');
        $this->load->model('system_model');
        $this->load->model('functions_model');
        $this->load->helper('url');
        $this->load->library('customlib');
		$this->load->model('inc/email_functions_model');
    }

	public function ajax_do_invoice(){
		$job_id_arr = $this->input->post('job_id');
		$country_id = $this->config->item('country');

		foreach( $job_id_arr as $job_id ){
			$i = 0;

			$today = date('Y-m-d');
			$todaydt = date('Y-m-d H:i:s');
			$logged_user = $this->session->staff_id;
			$pme_billable = false;
			$palace_billable = false;

			// clear email array
			unset($jemail);
			$jemail = array();
			$indv_job_log_arr = [];

			// get job details
			$jobs_sql = $this->db->query("
			SELECT 
				`date`,
				`job_type`,
				`status`,
				`booked_with`,
				`booked_by`,
				`assigned_tech`,
				`property_id`
			FROM `jobs`
			WHERE `id` = {$job_id}
			");
			$jobs_row = $jobs_sql->row();

			// Job Type
			$job_type_to = 'Yearly Maintenance';
			if( $jobs_row->job_type != '' ){

				if ( $jobs_row->job_type != $job_type_to ) {
					$indv_job_log_arr[] = "Job Type updated from <strong>{$jobs_row->job_type}</strong> to <strong>{$job_type_to}</strong>";                            
				}

			}else{ 

				$indv_job_log_arr[] = "Job Type updated to <strong>{$job_type_to}</strong>";     

			}
            

			// Job Status
			$job_status_to = 'Merged Certificates';
			if( $jobs_row->status != '' ){

				if ( $jobs_row->status != $job_status_to ) {
					$indv_job_log_arr[] = "Job Status updated from <strong>{$jobs_row->status}</strong> to <strong>{$job_status_to}</strong>";                            
				}

			}else{

				$indv_job_log_arr[] = "Job Status updated to <strong>{$job_status_to}</strong>";      

			}
            

			// Booked With
			$booked_with_to = 'Agent';
			if( $jobs_row->booked_with != '' ){

				if ( $jobs_row->booked_with != $booked_with_to ) {
					$indv_job_log_arr[] = "Booked With updated from <strong>{$jobs_row->booked_with}</strong> to <strong>{$booked_with_to}</strong>";                            
				}

			}else{

				$indv_job_log_arr[] = "Booked With updated to <strong>{$booked_with_to}</strong>";    

			}            

			// Booked By
			$booked_by_to = $logged_user;

			// staff_accounts TO
			$staff_acc_sql = $this->db->query("
			SELECT 
				`FirstName`,
				`LastName`
			FROM `staff_accounts`
			WHERE `StaffID` = {$booked_by_to}
			");
			$staff_acc_row = $staff_acc_sql->row();
			$booked_by_to_user_full = $this->system_model->formatStaffName($staff_acc_row->FirstName, $staff_acc_row->LastName);

			if( $jobs_row->booked_by != '' ){

				if ( $jobs_row->booked_by != $booked_by_to ) {

					// staff_accounts FROM
					$staff_acc_sql2 = $this->db->query("
					SELECT 
						`FirstName`,
						`LastName`
					FROM `staff_accounts`
					WHERE `StaffID` = {$jobs_row->booked_by}
					");
					$staff_acc_row2 = $staff_acc_sql2->row();
					$booked_by_from_user_full = $this->system_model->formatStaffName($staff_acc_row2->FirstName, $staff_acc_row2->LastName);					
								
					$indv_job_log_arr[] = "Booked By updated from <strong>{$booked_by_from_user_full}</strong> to <strong>{$booked_by_to_user_full}</strong>";                            
				}

			}else{

				$indv_job_log_arr[] = "Booked By updated to <strong>{$booked_by_to_user_full}</strong>"; 

			}
            

			// Assigned Tech			
			$assigned_tech_to = 2; // Upfront Bill
			$tech_to = 'Upfront Bill';

			if( $jobs_row->assigned_tech != '' ){

				if ( $jobs_row->assigned_tech != $assigned_tech_to ) {

					// tech FROM
					$tech_acc_sql = $this->db->query("
					SELECT 
						`FirstName`,
						`LastName`
					FROM `staff_accounts`
					WHERE `StaffID` = {$jobs_row->assigned_tech}
					");
					$tech_acc_row = $tech_acc_sql->row();
					$tech_from = $this->system_model->formatStaffName($tech_acc_row->FirstName, $tech_acc_row->LastName);
								
					$indv_job_log_arr[] = "Technician updated from <strong>{$tech_from}</strong> to <strong>{$tech_to}</strong>";   
											 
				}

			}else{

				$indv_job_log_arr[] = "Technician updated to <strong>{$tech_to}</strong>";   

			}
            
			

			//update jobs fields
			$update_data = array(
				'job_type'      => $job_type_to,
				'status'	    => $job_status_to,				
				'booked_with'   => $booked_with_to,
				'booked_by'	    => $booked_by_to,
				'assigned_tech' => $assigned_tech_to
			);			

			if( $this->system_model->isDateNotEmpty($jobs_row->date) == false ){ // empty/null job date

				$update_data['date'] = $today;
				$indv_job_log_arr[] = "Date updated to <strong>".date('d/m/Y',strtotime($today))."</strong>";   

			}

			// insert job log
            if( count($indv_job_log_arr) > 0  ){

                $combined_job_log = implode(" | ",$indv_job_log_arr);

				//insert logs
				$log_params = array(
					'title' => 63,  // Job Update
					'details' => $combined_job_log,
					'display_in_vjd' => 1,
					'created_by_staff' => $this->session->staff_id,
					'property_id' => $jobs_row->property_id,
					'job_id' => $job_id
				);
				$this->system_model->insert_log($log_params);

            }
			
			$this->db->where('id', $job_id);
    		$this->db->update('jobs' ,$update_data);

			// get updated job
			// copied from email_functions.php, batchSendInvoicesCertificates function 
			$sql_str2 = "SELECT j.id, j.job_type, DATE_FORMAT(j.date,'%d/%m/%Y') AS job_date,
				DATE_FORMAT(j.date, '%d/%m/%Y') AS date,
				j.job_price, j.price_used, 
				j.status, p.address_1, p.address_2, p.address_3, 
				p.state, p.postcode, j.id, p.property_id,
				a.agency_id, a.send_emails, a.account_emails, a.send_combined_invoice,
				DATE_FORMAT(DATE_ADD(j.date, INTERVAL 1 YEAR), '%d/%m/%Y') AS retest_date,
				j.ss_location,
				j.ss_quantity,
				sa.FirstName, 
				sa.LastName,
				j.work_order,
				p.`landlord_email`,
				p.`property_managers_id`,
				a.`allow_indiv_pm_email_cc`,
				p.`pm_id_new`,
				a.`franchise_groups_id`,
				a.`agency_name`,
				p.`landlord_firstname`,
				p.`landlord_lastname`,
				p.`propertyme_prop_id`,
				a.`pme_supplier_id`,
				p.`palace_prop_id`,
				a.`palace_diary_id`,
				apd.`api`,
				apd.`api_prop_id`,
				j.`id` AS `jservice`
				FROM (jobs j, property p, agency a)
				LEFT JOIN staff_accounts AS sa ON j.assigned_tech = sa.StaffID 
				LEFT JOIN api_property_data AS apd ON p.property_id = apd.crm_prop_id   
				WHERE j.property_id = p.property_id 
				AND p.agency_id = a.agency_id
				AND j.`id` = {$job_id}
				";
			$query = $this->db->query($sql_str2);

			// get the result as a array
			$job = $query->result_array();		
			//print_r($job);
			//exit();

			// Pme property ID exist and agency supplier ID exist
			if( $job[$i]['api_prop_id'] != '' && $job[$i]['pme_supplier_id'] != '' && $job[$i]['api'] == 1){
				$pme_billable = true;
			}
			
			// Palace property ID exist and palace diary ID exist
			if( $job[$i]['api_prop_id'] != '' && $job[$i]['palace_diary_id'] != '' && $job[$i]['api'] == 4){
				$palace_billable = true;
			}

			// Palace property ID exist and palace diary ID exist
			if( $job[$i]['api_prop_id'] != '' && $job[$i]['api'] == 6){
				$ourtradie_billable = true;
			}
			
			if( $pme_billable == true ){ // skip email

				//insert job log
				$data = array(
					'contact_type' =>'Upfront Job moved',
					'eventdate'	   =>date('Y-m-d'),
					'comments'	   =>'PMe connected job moved from <b>To Be Invoiced</b> to <b>Merged Jobs</b> for invoicing',
					'job_id'	   =>$job_id,
					'staff_id'	   =>$this->session->staff_id,
					'eventtime'	   =>date('H:i')
				);
			
				$this->db->insert('job_log',$data);
				
			}else if( $palace_billable == true ){ // skip email

				//insert job log
				$data = array(
					'contact_type' =>'Upfront Job moved',
					'eventdate'	   =>date('Y-m-d'),
					'comments'	   =>'Palace connected job moved from <b>To Be Invoiced</b> to <b>Merged Jobs</b> for invoicing',
					'job_id'	   =>$job_id,
					'staff_id'	   =>$this->session->staff_id,
					'eventtime'	   =>date('H:i')
				);
			
				$this->db->insert('job_log',$data);
				
			}else if( $ourtradie_billable == true ){ // skip email

				//insert job log
				$data = array(
					'contact_type' =>'Upfront Job moved',
					'eventdate'	   =>date('Y-m-d'),
					'comments'	   =>'Ourtradie connected job moved from <b>To Be Invoiced</b> to <b>Merged Jobs</b> for invoicing',
					'job_id'	   =>$job_id,
					'staff_id'	   =>$this->session->staff_id,
					'eventtime'	   =>date('H:i')
				);
			
				$this->db->insert('job_log',$data);
				
			}else{ // send email
				
				/*
				// check if agency has maintenance program
				$jemail = $this->email_functions_model->processMergedSendToEmails($job[$i]['agency_id'],$job[$i]['account_emails'],$job);
				
				// email invoice
				$invoice_only = 1;
				$this->email_functions_model->sendInvoiceCertEmail($job[$i], $jemail,$country_id,$invoice_only);

				//update jobs fields
				$data = array(
					'client_emailed'      =>$todaydt,
					'sms_sent_merge'	  =>$todaydt
				);

				$this->db->where('id', $job_id);
    			$this->db->update('jobs' ,$data);
				*/

			}
			$i++;
		}
	}
}

