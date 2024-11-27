<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Jobs_model $jobs_model
 * @property Bundle_services_model $bundle_services_model
 */


class Jobs_ajax extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('job_platform_invoice_note_model');
		$this->load->model('jobs_model');
		$this->load->model('bundle_services_model');
		$this->load->model('global_settings_model');
	}

	public function get_job_note($jobId)
	{
		$existingNotes = $this->job_platform_invoice_note_model->as_array()->get('job_id', $jobId) ?? [];

		echo json_encode($existingNotes);
	}

	public function save_job_invoice_note()
	{
		if ($this->input->is_ajax_request()) {
			$jobId = $this->input->post('jobId');
			$noteText = $this->input->post('noteText');

			if (!empty($jobId) && !empty($noteText)) {
				$data = [
					'job_id' => $jobId,
					'note'   => $noteText
				];

				// Insert the note into the database
				$noteId = $this->job_platform_invoice_note_model->insert($data);

				if ($noteId !== false) {
					$response = array('success' => true, 'message' => 'Note saved successfully.');
				} else {
					$response = array('success' => false, 'message' => 'Failed to save note.');
				}
			} else {
				$response = array('success' => false, 'message' => 'Invalid data.');
			}

			header('Content-Type: application/json');
			echo json_encode($response);
		}
	}

    /**
     * Accepts an ajax call from a certain page and updates the database
     * @return void
     */
    public function update_note() {
        $response = [
            'success' => false,
            'message' => 'Failed to update note.'
        ];

        if ($this->input->is_ajax_request()) {
            // Get the data sent via AJAX
            $data = [
                'id'    => $this->input->post('jobNoteId'),
                'note'  => $this->input->post('noteText')
            ];

            // Update the note in the database
            $success = $this->job_platform_invoice_note_model->update($data);

            if ($success) {
                $response = [
                    'success' => true,
                    'message' => 'Note updated successfully.'
                ];
            }
        }

        echo json_encode($response);
    }

    public function create_jobs(){
        $property_id = $this->input->post('property_id');
        $alarm_job_type_id = $this->input->post('alarm_job_type_id');
        $job_type = $this->input->post('job_type');
        $price = $this->input->post('price');
        $vacant_from = $this->input->post('vacant_from');
        $vacant_from2 = ($vacant_from!="")?$vacant_from:'';

        $new_ten_start = $this->input->post('new_ten_start');
        $new_ten_start2 = ($new_ten_start!="")?$new_ten_start:'';
        $problem = $this->input->post('problem');
        $agency_id = $this->input->post('agency_id');
        $comments = "";

        $end_date_str = 'NULL';
        $start_date_str = 'NULL';
        $job_date_date_str = NULL;

        $service_name = $this->input->post('service_name') ?? '';
        $workorder_notes = $this->input->post('workorder_notes') ?? '';
        $job_status = $this->input->post('job_status') ?? 'To Be Booked';
        $onhold_start_date = $this->input->post('onhold_start_date') ?? '';
        $onhold_end_date = $this->input->post('onhold_end_date') ?? '';
        $job_date = $this->input->post('job_date') ?? '';
        $jtech_sel = $this->input->post('jtech_sel') ?? '';
        $preferred_alarm_id = $this->input->post('preferred_alarm_id') ?? '';
        $work_order = $this->input->post('work_order') ?? '';
        $job_vacant_start_date = $this->input->post('job_vacant_start_date');
        $job_vacant_end_date = $this->input->post('job_vacant_end_date');
        $vacant_prop = $this->input->post('vacant_prop');
        $property_manager = $this->input->post('property_manager');

        $allocate_notes = null;

	    $no_dates_provided = 0;

        if ($job_status == 'Completed') {
            $j_status = $job_status;

            $job_date_date_str = ( $job_date != '' ) ? date('Y-m-d',strtotime(str_replace('/','-',$job_date))) :'NULL';
        } else {
            $j_status = $job_status;
        }
        switch($job_type){
            case 'Once-off':
                $status = $j_status ?? "Send Letters";
                $comments = "{$job_type}";
                break;
            case 'Change of Tenancy':
                $status = $j_status ?? "To Be Booked";

                if( $vacant_from!="" ){
                    $start_date = date('Y-m-d',strtotime(str_replace('/','-',$vacant_from)));
                    $start_date_str = "'{$start_date}'";
                }else{
                    $start_date_str = 'NULL';
                }

                if( $new_ten_start !="" ){
                    $end_date = date('Y-m-d',strtotime(str_replace('/','-',$new_ten_start )));
                    $end_date_str = "'{$end_date}'";

                    /**
                     * Added start date calcution same as LR as per Thalia'request in doc
                     */
                    $start_date = date('Y-m-d',strtotime("{$end_date} -30 days"));
                    $start_date_str = "'{$start_date}'";
                }else{
                    $end_date_str = 'NULL';
                }



                if( $vacant_from=="" && $new_ten_start =="" ){
                    $no_dates_provided = 1;
                    $comments_temp = 'No Dates Provided';
                }else if( $vacant_from!="" && $new_ten_start =="" ){
                    $no_dates_provided = 1;
                    $comments_temp = "Vacant from {$vacant_from} - {$problem}";
                }else if( $vacant_from=="" && $new_ten_start !="" ){
                    $no_dates_provided = 1;
                    $comments_temp = "Book before {$new_ten_start} - {$problem}";
                }else{
                    $no_dates_provided = 0;
                    $comments_temp = "Vacant from {$vacant_from} - {$new_ten_start } {$problem}";
                }

                /**
                 * Added new condition for no-dates_provided as per Thali's request regarding new vacant_from and vacant_to fields
                 * Set no_dates_provided to 0 
                 */
                if($new_ten_start != "" && ($job_vacant_start_date != "" OR $job_vacant_end_date != "")){
                    $no_dates_provided = 0;
                }

                $comments = "COT {$comments_temp}";

                break;
            case 'Yearly Maintenance':
                $status = $j_status ?? "To Be Booked";
                break;
            case 'Fix or Replace':
                $status = $j_status ?? "To Be Booked";
                if( $new_ten_start2 != '' ){
                    $temp = " New Tenancy Starts ".$new_ten_start2.",";
                }else{
                    $temp = ',';
                }
                $comments = "{$job_type}{$temp} Comments: <strong>{$problem}</strong>";

                // insert workorder notes to allocate notes
                if( $job_status == 'Allocate' ){
                    $allocate_notes = $workorder_notes;
                }

                break;
            case '240v Rebook':
                $status = $j_status ?? "To Be Booked";
                $comments = "{$job_type}";
                break;
            case 'Lease Renewal':
                $status = $j_status ?? "To Be Booked";

                if( $new_ten_start!="" ){
                    $end_date = date('Y-m-d',strtotime(str_replace('/','-',$new_ten_start)));
                    $end_date_str = "'{$end_date}'";
                    $start_date = date('Y-m-d',strtotime("{$end_date} -30 days"));
                    $start_date_str = "'{$start_date}'";
                    $start_date_txt = date('d/m/Y',strtotime("{$end_date} -30 days"));
                }else{
                    $end_date_str = 'NULL';
                    $start_date_str = 'NULL';
                }

                $no_dates_provided = 0;

                if( $new_ten_start=="" ){
                    $no_dates_provided = 1;
                    $comments_temp = 'No Dates Provided';
                }else{
                    $no_dates_provided = 0;
                    $comments_temp = "{$start_date_txt} - {$new_ten_start} {$problem}";
                }

                $comments = "LR {$comments_temp}";

                /*
                $temp = "New Tenancy Starts ".$new_ten_start2;
                $comments = "{$job_type} {$temp}";
                */
                break;
            case 'Annual Visit':
                $status = $j_status ?? "To Be Booked";
                $comments = "{$job_type}";
                break;
            default:
                $status = $j_status ?? "To Be Booked";
        }

        //echo "Job Type: ".$job_type."<br />";

        $price2 = ($job_type=="Yearly Maintenance"||$job_type=="Once-off")?$price:0;


        $agen_sql = "
			SELECT `franchise_groups_id`
			FROM `agency`
			WHERE `agency_id` = {$agency_id}
		";
        $agen_exec = $this->db->query($agen_sql);
        $agen = $agen_exec->result_array();

        // if agency is DHA agencies with franchise group = 14(Defence Housing) OR if agency has maintenance program
	    $dha_need_processing = 0;
        if( $this->functions_model->isDHAagenciesV2($agen[0]['franchise_groups_id'])==true || $this->functions_model->agencyHasMaintenanceProgram($agency_id)==true ){
            $dha_need_processing = 1;
        }

        // if workorder exist it overrides job comments
        if( $workorder_notes != '' ){
            $comments = $workorder_notes;
        }

		$data = [
			'job_type' => $job_type,
			'property_id' => $property_id,
			'status' => $status,
			'service' => $alarm_job_type_id,
			'job_price' => $price2,
			'comments' => $comments,
			'start_date' => $start_date_str,
			'due_date' => $end_date_str,
			'no_dates_provided' => $no_dates_provided,
			'property_vacant' => $vacant_prop,
			'dha_need_processing' => $dha_need_processing,
            'date' => $job_date_date_str,
            'work_order' => $work_order
		];

	    if ($job_status == 'Completed') {
		    $data['assigned_tech'] = 'URGENT REPAIR';
		    $data['urgent_job_reason'] = $jtech_sel;
	    }

		// if job type is 'Fix or Replace' set it as urgent
		if( $job_type == 'Fix or Replace' ) {
			$data['urgent_job'] = 1;
			$data['urgent_job_reason'] = 'URGENT REPAIR';
		}

        // insert allocate notes
        if( $allocate_notes != '' ){
            $data['allocate_notes'] = $allocate_notes;
        }

		$job_id = $this->jobs_model->insert($data);

        // AUTO - UPDATE INVOICE DETAILS
        $this->system_model->updateInvoiceDetails($job_id);

        // This process will insert new start/end vacant dates to table 'job_vacant_dates' when Property Vancant checkbox ticked
        // If vancant ticked and new fields vacant_from/vacant_to is not empty > insert to new table 'job_vacant_dates' 
        if($vacant_prop == 1 && ($job_vacant_start_date != "" OR $job_vacant_end_date != "")){
            $job_vacant_dates_data = [
                'job_id'      => $job_id,
                'start_date'  => $this->system_model->formatDate($job_vacant_start_date),
                'end_date'    => $this->system_model->formatDate($job_vacant_end_date)
            ];
            $this->db->insert('job_vacant_dates',$job_vacant_dates_data);
        }

        // insert logs
        $today = date('Y-m-d H:i:s');
        $staff_id = $this->input->post('staff_id');
        $data = array(
            'title' => 1,
            'details' => "<strong>{$job_type}</strong> Job Created",
            'display_in_vjd' => 1,
            'job_id' => $job_id,
            'created_by_staff' => $staff_id,
            'created_date' => $today
        );
        $this->system_model->insert_log($data);

        // get alarm job type
        $this->db->select('*');
        $this->db->from('alarm_job_type');
        $this->db->where('id', $alarm_job_type_id);
        $ajt_sql = $this->db->get();

        $ajt = $ajt_sql->result_array();

        // If AJT is a bundle service, then loop through each sub-service (ajt) and sync
        if($ajt[0]['bundle']==1){
            $alarm_job_type_ids = explode(",",trim($ajt[0]['bundle_ids']));
            // insert bundles
            foreach($alarm_job_type_ids as $alarm_job_type_id){
                $data = [
	                'job_id' => $job_id,
	                'alarm_job_type_id' => $alarm_job_type_id,
                ];

	            $bundle_services_id = $this->bundle_services_model->insert($data);

                // sync alarm
                $syncParams = [
                    "job_id" => $job_id,
                    "jserv" => $alarm_job_type_id,
                    "bundle_id" => $bundle_services_id
                ];
                $this->jobs_model->runSync($syncParams);

            }
        }else{
            $syncParams = [
                "job_id" => $job_id,
                "jserv" => $alarm_job_type_id,
            ];
            $this->jobs_model->runSync($syncParams);
        }

        // expired 240v check
        if( $job_type == 'Fix or Replace' && $this->system_model->findExpired240vAlarm($job_id) == true ){
            $updateData = array(
                'job_type' => '240v Rebook'
            );

            $this->db->where('id', $job_id);
            $this->db->update('jobs', $updateData);
        }

        if( ( $job_type == 'Change of Tenancy' ||  $job_type == 'Lease Renewal' ) && $this->system_model->findExpired240vAlarm($job_id) == true ){
            $updateData = array(
                'comments' => '240v REBOOK - {$comments}'
            );

            $this->db->where('id', $job_id);
            $this->db->update('jobs', $updateData);
        }

        $data = array(
            'property_id' => $property_id,
            'alarm_job_type_id' => $alarm_job_type_id
        );
        $this->db->insert('property_propertytype', $data);

        // add logs
        //$service_name = $_POST['service_name'];
        $staff_id = $this->input->post('staff_id');
        $data = array(
            'property_id' => $property_id,
            'staff_id' => $staff_id,
            'event_type' => $ajt[0]['type'] . "Job Created",
            'event_details' => $job_type,
            'log_date' => date('Y-m-d H:i:s')
        );
        $this->db->insert('property_event_log', $data);

        // clear tenant details
        $delete_tenant = $this->input->post('delete_tenant');
        if($delete_tenant==1){
            $updateData = array(
                'active' => 0
            );
            $this->db->where('property_id', $property_id);
            $this->db->update('property_tenants', $updateData);
        }

        $is_recreated_bundle_service = false;
        $prior_property_alarm_count = $this->jobs_model->getPrevSmokeAlarm($property_id)->num_rows();

        if ($prior_property_alarm_count > 0) {
            $is_recreated_bundle_service = true;
        }

        //update property manager
        if(!empty($property_manager)){
            $pm_data = [
                'pm_id_new' => $property_manager
            ];
            $pm_where = [
                'property_id' => $property_id
            ];
            $this->db->update('property', $pm_data, $pm_where);
        }

        echo json_encode([
            "status" 	=> $status,
            "job_id" 	=> $job_id,
            "ajt_id" 	=> $alarm_job_type_id,
            "is_recreated_bundle_service" => $is_recreated_bundle_service
        ]);
    }
    
    /**
     * Update Global Settings Allocate Personel
     */
    public function ajax_update_allocate_personnel()
    {
        $data['status'] = false;
        $staff_id = $this->input->post('staff_id');
        $country_id = config_item('country');
        
        //fetch global_settings
        $result = $this->global_settings_model->get_all();
        
        $staff_id_imp = implode(",",$staff_id);
        
        //data
        $data = array(
            'allocate_personnel' => $staff_id_imp,
            'allocate_personnel_updated_by' => $this->session->staff_id
        );
        
        // Check if global_settings is empty (boolean false)
        if ($result) {
            //Update global_settings_id = 1
            $this->global_settings_model->where(['global_settings_id' => $country_id, 'active' => 1])->update($data);
            $data['status'] = true;
        } else {
            $this->global_settings_model->insert($data);
            $data['status'] = true;
        }
        
        echo json_encode($data);
    }


}