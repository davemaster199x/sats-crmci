<?php
defined('BASEPATH') OR exit('No direct script access allowed');
    
class Pdf extends MY_Controller {

    public function __construct() {

        parent::__construct();
        
        $this->load->library('HashEncryption');
        $this->load->library('encryption');
        
        $this->load->model('/inc/job_functions_model');
        $this->load->model('/inc/alarm_functions_model');
        $this->load->model('/inc/functions_model');
        $this->load->model('/inc/pdf_template');
        $this->load->model('vehicles_model');
    }

    public function view_combined_old()
    {
        $country_id = $this->config->item('country');
        $job_id = $this->input->get('job_id') ?? $this->uri->segment(3);
        $output_type = $this->input->get('output_type') ?? $this->uri->segment(4);
		
        //check job id
        if(!empty($job_id)){
            /**
             * Decrypt OR Decode Job ID
             * Handle current encryption and new hashIds
             */
            if (strlen($job_id) > 16) {
                $decrypt_job_id = $this->encryption->decrypt($job_id);
            } else {
	            /**
	             * This will handle backward compatibility with urls that are already generated in the past
	             */
				if (is_numeric($job_id)) {
					$decrypt_job_id = $job_id;
				} else {
					$decrypt_job_id = HashEncryption::decodeString($job_id);
				}
            }

            $this->system_model->updateInvoiceDetails($decrypt_job_id); ## Run updateInvoice first
            
            //get state by job_id
            $state_query = $this->pdf_template->get_state_by_job_id($decrypt_job_id)->row();
            $p_state = $state_query->p_state;

            // append checkdigit to job id for new invoice number
            $check_digit = $this->gherxlib->getCheckDigit(trim($decrypt_job_id));
            $bpay_ref_code = "{$decrypt_job_id}{$check_digit}";

            $job_details =  $this->job_functions_model->getJobDetails2($decrypt_job_id,$query_only = false);

            if($job_details == null){
                log_message('error', 'view_combined: Empty job_details.');
                exit('Error: Please contact admin.');
            }

            # Alarm Details
            $alarm_details = [];
	        if( Alarm_job_type_model::show_smoke_alarms($job_details['ajt_bundle_ids']) ){
                $alarm_details = $this->alarm_functions_model->getPropertyAlarms($decrypt_job_id, 1, 0, $job_details['jservice']);
            }
            $num_alarms = sizeof($alarm_details);

            # Property + Agent Details
            $property_details = $this->functions_model->getPropertyAgentDetails($job_details['property_id']);


            //pdf template switch base on state
            $pdf = $this->pdf_template->pdf_combined_template_v2($decrypt_job_id, $job_details, $property_details, $alarm_details, $num_alarms, $country_id, "I");
            //pdf template switch base on state end

            $output_type2 = ($output_type!='')?$output_type:'I';

            $file_name = '';
            if(in_array('32', array_map('trim', array_column($alarm_details, 'alarm_power_id')))){
                $file_name = 'Service_Report_and_Invoice_';
            } else {
                $file_name = 'Combined_Certificate_and_Invoice_';
            }

            $pdf->Output($file_name . $bpay_ref_code . '.pdf', $output_type2);

        }else{
            log_message('error', 'view_combined: Empty job id');
            exit('Error: Please contact admin.');
        }



    }


    public function view_combined()
    {
        $job_id = $this->input->get('job_id') ?? $this->uri->segment(3);
        $output_type = $this->input->get('output_type') ?? $this->uri->segment(4);

        //check job id
        if(!empty($job_id)){

            $invoiceFile        = $this->view_invoice($job_id, true);
            $certificateFile    = $this->view_certificate($job_id, true);

            if ($invoiceFile && $certificateFile) {
                $this->mergePDF($invoiceFile, $certificateFile, $output_type);
            } else {
                log_message('error','No temporary files generated for merging');
                exit('No temporary files generated for merging');
            }

        }else{
            log_message('error', 'view_combined: Empty job id');
            exit('Error: Please contact admin.');
        }
        
    }

    function mergePDF($invoiceFile, $certificateFile, $output_type)
    {
        $pdf = new Fpdi();

        if (file_exists($invoiceFile)){
            $invoicePageCount = $pdf->setSourceFile($invoiceFile);
            for ($pageNum = 1; $pageNum <= $invoicePageCount ; $pageNum++) {
                $invoice_page = $pdf->importPage($pageNum);
                $pdf->AddPage();
                $pdf->useTemplate($invoice_page);
            }
            unlink($invoiceFile); // Delete temporary file
        }

        if (file_exists($certificateFile)){
            $num_pages = $pdf->setSourceFile($certificateFile);
            for ($i = 1; $i <= $num_pages; $i++) {
                $compliance_page = $pdf->importPage($i);
                $pdf->AddPage();
                $pdf->useTemplate($compliance_page);
            }
            unlink($certificateFile); // Delete temporary file
        }

        $output_type = empty($output_type) ? 'I' : $output_type;

        $pdf->Output('Combined_Documents.pdf', $output_type);
    }


    public function view_invoice($job_id = null, $tempFile = false)
    {
        $country_id = $this->config->item('country');
	    $job_id = empty($job_id) ? ($this->input->get('job_id') ?? $this->uri->segment(3)) : $job_id;
	    $output_type = $this->input->get('output_type') ?? $this->uri->segment(4);

        if(!empty($job_id)){
            
            /**
             * Decrypt OR Decode Job ID
             * Handle current encryption and new hashIds
             */
            if (strlen($job_id) > 16) {
                $decrypt_job_id = $this->encryption->decrypt($job_id);
            } else {
	            /**
	             * This will handle backward compatibility with urls that are already generated in the past
	             */
	            if (is_numeric($job_id)) {
		            $decrypt_job_id = $job_id;
	            } else {
		            $decrypt_job_id = HashEncryption::decodeString($job_id);
	            }
            }

            $this->system_model->updateInvoiceDetails($decrypt_job_id); ## Run updateInvoice first

            // append checkdigit to job id for new invoice number
            $check_digit = $this->gherxlib->getCheckDigit(trim($decrypt_job_id));
            $bpay_ref_code = "{$decrypt_job_id}{$check_digit}";
            
            $job_details =  $this->job_functions_model->getJobDetails2($decrypt_job_id,$query_only = false);

            if($job_details == null){
                log_message('error', 'view_invoice: Empty job_details.');
                exit('Empty job_details');
            }

            # Alarm Details
            $alarm_details = [];
	        if( Alarm_job_type_model::show_smoke_alarms($job_details['ajt_bundle_ids']) ){
                $alarm_details = $this->alarm_functions_model->getPropertyAlarms($decrypt_job_id, 1, 0, $job_details['jservice']);
            }
            $num_alarms = sizeof($alarm_details);

            # Property + Agent Details
            $property_details = $this->functions_model->getPropertyAgentDetails($job_details['property_id']);

            $pdf = $this->pdf_template->pdf_invoice_template($decrypt_job_id, $job_details, $property_details, $alarm_details, $num_alarms, $country_id, "I");

            $output_type2 = ($output_type!='')?$output_type:'I';

            if (!$tempFile){
                $pdf->Output('Invoice_' . $bpay_ref_code . '.pdf', $output_type2);
            } else {
                $tempFilename = tempnam(sys_get_temp_dir(), 'pdf_'); // Create temporary filename

                $pdf->Output($tempFilename, 'F'); // Output to temporary file

                return $tempFilename; // Return temporary filename for merging (if not viewing)
            }

        }else{
            log_message('error', 'view_invoice: Empty job id.');
            exit('Error: Please contact admin.');
        }
        return null;
    }
	
	public function view_quote()
    {
        $staff_id =  $this->session->staff_id;
        $country_id = $this->config->item('country');
        $job_id = $this->input->get('job_id') ?? $this->uri->segment(3);
        $qt = $this->input->get('qt') ?? $this->uri->segment(4);
	    $output_type = $this->input->get('output_type') ?? $this->uri->segment(5);

        $this->load->model('jobs_model');
        $this->load->model('properties_model');

        //check job id
        if(!empty($job_id)){
            
            /**
             * Decrypt OR Decode Job ID
             * Handle current encryption and new hashIds
             */
            if (strlen($job_id) > 16) {
                $decrypt_job_id = $this->encryption->decrypt($job_id);
            } else {
	            /**
	             * This will handle backward compatibility with urls that are already generated in the past
	             */
	            if (is_numeric($job_id)) {
		            $decrypt_job_id = $job_id;
	            } else {
		            $decrypt_job_id = HashEncryption::decodeString($job_id);
	            }
            }

            // append checkdigit to job id for new invoice number
            $check_digit = $this->gherxlib->getCheckDigit(trim($decrypt_job_id));
            $bpay_ref_code = "{$decrypt_job_id}{$check_digit}";

            $job_details =  $this->job_functions_model->getJobDetails2($decrypt_job_id,$query_only = false);

            if($job_details == null) exit(EXIT_MESSAGE);
            # Alarm Details
            $alarm_details = [];
	        if( Alarm_job_type_model::show_smoke_alarms($job_details['ajt_bundle_ids']) ){
                $alarm_details = $this->alarm_functions_model->getPropertyAlarms($decrypt_job_id, 1, 0, $job_details['jservice']);
            }
            $num_alarms = sizeof($alarm_details);

            # Property + Agent Details
            $property_details = $this->functions_model->getPropertyAgentDetails($job_details['property_id']);

            if( $qt == 'combined'){

                /*
                  // combined quotes pdf *new
                  $pdf_name = 'combined_quotes_pdf_' . $bpay_ref_code.rand().date('YmdHis') . '.pdf';
                  $pdf_output = 'I'; //  send the file inline to the browser. The PDF viewer is used if available.
  
                  $combined_quotes_pdf_params = array(
                      'job_id' => $decrypt_job_id,
                      'job_details' => $job_details,
                      'property_details' => $property_details,
                      'pdf_name' => $pdf_name,
                      'pdf_output' => $pdf_output
                  );
                  $pdf = $this->pdf_template->view_quote($combined_quotes_pdf_params);
                  */
                    
                    $pdf_name = 'combined_quotes_pdf_' . $bpay_ref_code.rand().date('YmdHis') . '.pdf';                    
                    
                    $en_pdf_params = array(
                        'job_id' => $decrypt_job_id,
                        'output' => 'I',

                        'job_id' => $decrypt_job_id,
                        'job_details' => $job_details,
                        'property_details' => $property_details,                      
                        'pdf_name' => $pdf_name,           
                    );
                    $this->pdf_template->combined_qoutes($en_pdf_params);

            }else{

                $pdf = $this->pdf_template->pdf_quote_template_v2($decrypt_job_id, $job_details, $property_details, $alarm_details, $num_alarms, $country_id, "I", null, $qt);
                $output_type2 = ($output_type!='')?$output_type:'I'; 
                $quote_name = ($qt=='emerald') ? 'economical_quotes_pdf' : 'brooks_quotes_pdf';
                $pdf->Output($quote_name.'_'. $bpay_ref_code . '.pdf', $output_type2);

            }                       

        }else{
            exit('Error: Please contact admin.');
        }

    }

    /**
     * PDF view_certificate
     * @return false|string|void
     */
    public function view_certificate($job_id = null, $tempFile = false)
    {
        $staff_id =  $this->session->staff_id;
        $country_id = $this->config->item('country');
        $job_id = empty($job_id) ? ($this->input->get('job_id') ?? $this->uri->segment(3)) : $job_id;
	    $output_type = $this->input->get('output_type') ?? $this->uri->segment(4);

        //check job id
        if(!empty($job_id)){
            
            /**
             * Decrypt OR Decode Job ID
             * Handle current encryption and new hashIds
             */
            if (strlen($job_id) > 16) {
                $decrypt_job_id = $this->encryption->decrypt($job_id);
            } else {
	            /**
	             * This will handle backward compatibility with urls that are already generated in the past
	             */
	            if (is_numeric($job_id)) {
		            $decrypt_job_id = $job_id;
	            } else {
		            $decrypt_job_id = HashEncryption::decodeString($job_id);
	            }
            }
            
            //get state by job_id
            $state_query = $this->pdf_template->get_state_by_job_id($decrypt_job_id)->row();
            $p_state = $state_query->p_state;

            // append checkdigit to job id for new invoice number
            $check_digit = $this->gherxlib->getCheckDigit(trim($decrypt_job_id));
            $bpay_ref_code = "{$decrypt_job_id}{$check_digit}";
            
            $job_details =  $this->job_functions_model->getJobDetails2($decrypt_job_id,$query_only = false);

            if($job_details == null){
                log_message('error', 'view_certificate: Empty job_details');
                exit('Empty job_details');
            }
            # Alarm Details
            $alarm_details = [];
            if( Alarm_job_type_model::show_smoke_alarms($job_details['ajt_bundle_ids']) ){
                $alarm_details = $this->alarm_functions_model->getPropertyAlarms($decrypt_job_id, 1, 0, $job_details['jservice']);
            }
            $num_alarms = sizeof($alarm_details);

            # Property + Agent Details
            $property_details = $this->functions_model->getPropertyAgentDetails($job_details['property_id']);

            $pdf = $this->pdf_template->pdf_certificate_template_v2($decrypt_job_id, $job_details, $property_details, $alarm_details, $num_alarms, $country_id, "I");
     
            
            //pdf template switch base on state end

            $output_type2 = ($output_type!='')?$output_type:'I'; 

            $file_name = '';
            if(in_array('32', array_map('trim', array_column($alarm_details, 'alarm_power_id')))){
                $file_name = 'Service_Report_';
            } else {
                $file_name = 'Compliance_Certificate_';
            }

            if (!$tempFile){
                $pdf->Output($file_name . $bpay_ref_code . '.pdf', $output_type2);
            } else {
                $tempFilename = tempnam(sys_get_temp_dir(), 'pdf_'); // Create temporary filename

                $pdf->Output($tempFilename, 'F'); // Output to temporary file

                return $tempFilename; // Return temporary filename for merging (if not viewing)
            }

        }else{
            log_message('error', 'view_certificate: Empty job id');
            exit('Error: Please contact admin.');
        }
        return null;
    }

    public function entry_notice() {

        $this->load->model('jobs_model');
        $this->load->model('properties_model');

        $job_id = $this->input->get('job_id') ?? $this->uri->segment('3');

        //check job id
        if( !empty($job_id) ){
			
	        /**
	         * Decrypt OR Decode Job ID
	         * Handle current encryption and new hashIds
	         */
	        if (strlen($job_id) > 16) {
		        $decrypt_job_id = $this->encryption->decrypt($job_id);
	        } else {
		        if (is_numeric($job_id)) {
			        $decrypt_job_id = $job_id;
		        } else {
			        $decrypt_job_id = HashEncryption::decodeString($job_id);
		        }
	        }
            
            $en_pdf_params = array(
                'job_id' => $decrypt_job_id,
                'output' => 'I'
            );
            $this->pdf_template->entry_notice_switch($en_pdf_params);            

        }else{
            exit('Error: Please contact admin.');
        }

    }

    public function safe_work_method_statement() {

        $this->load->model('jobs_model');

        $job_id =  $this->input->get('job_id') ?? $this->uri->segment('3');
        $swms_type = $this->uri->segment('4');

        //check job id
        if( $job_id > 0 ){        
            
            $en_pdf_params = array(
                'job_id' => $job_id,
                'swms_type' => $swms_type,
                'output' => 'I'
            );
            $this->pdf_template->swms($en_pdf_params);            

        }else{
            exit('Error: Please contact admin.');
        }

    }
	
	/**
	 * For some reason this function is not being use
	 * @return void
	 */
    public function vehicle_details(){
        $vehicle_id = $this->input->get('job_id') ?? $this->uri->segment(3);
        $this->pdf_template->vehicle_details($vehicle_id);  
    }
	
	/**
	 * For some reason this function is not being use
	 * @return void
	 */
    public function view_certificate_with_photos(){

        $country_id = $this->config->item('country');
        $job_id = $this->input->get('job_id') ?? $this->uri->segment(3);
        $page = $this->uri->segment('5');
        $output_type = $this->uri->segment('6');

        if($page == "certificate"){
            $job_id = HashEncryption::encodeString($job_id);
        }

        //check job id
        if(!empty($job_id)){
            
            /**
             * Decrypt OR Decode Job ID
             * Handle current encryption and new hashIds
             */
            if (strlen($job_id) > 16) {
                $decrypt_job_id = $this->encryption->decrypt($job_id);
            } else {
                $decrypt_job_id = HashEncryption::decodeString($job_id);
            }

            //get state by job_id
            $state_query = $this->pdf_template->get_state_by_job_id($decrypt_job_id)->row();
            $p_state = $state_query->p_state;

            // append checkdigit to job id for new invoice number
            $check_digit = $this->gherxlib->getCheckDigit(trim($decrypt_job_id));
            $bpay_ref_code = "{$decrypt_job_id}{$check_digit}";
            
            $job_details =  $this->job_functions_model->getJobDetails2($decrypt_job_id,$query_only = false);

            if($job_details == null) exit(EXIT_MESSAGE);
            # Alarm Details
            $alarm_details = [];
	        if( Alarm_job_type_model::show_smoke_alarms($job_details['ajt_bundle_ids']) ){
                $alarm_details = $this->alarm_functions_model->getPropertyAlarms($decrypt_job_id, 1, 0, $job_details['jservice']);
            }
            $num_alarms = sizeof($alarm_details);

            # Property + Agent Details
            $property_details = $this->functions_model->getPropertyAgentDetails($job_details['property_id']);
            
            //pdf template switch base on state 
            $pdf = $this->pdf_template->pdf_certificate_template_v3($decrypt_job_id, $job_details, $property_details, $alarm_details, $num_alarms, $country_id, "I");
            //pdf template switch base on state end

            $output_type2 = ($output_type!='')?$output_type:'I'; 

            $pdf->Output('invoice' . $bpay_ref_code . '.pdf', $output_type2);

        }else{
            exit('Error: Please contact admin.');
        }
    }
}

