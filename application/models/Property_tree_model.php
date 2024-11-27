<?php

class Property_tree_model extends CI_Model
{

    private $api_gateway;
    private $application_key;
    private $subscription_key;
    private $request_limit;
    private $sleep_interval_sec;

    // rate limit
    private $request_counter;
    private $start_time;

    public function __construct(){

        $this->load->database();

        // AU and NZ used the same live/production keys
        if( ENVIRONMENT == 'production' ){ // LIVE

            $this->api_gateway = 'https://api.propertytree.io';
            $this->application_key = '3941b249-5113-4d49-9c38-f1bf6386cc35';
            $this->subscription_key = '8e5d8cb6af5f41bf8408c40de41b8d82';            

        }else{ // DEV

            $this->api_gateway = 'https://uatapi.propertytree.io';
            $this->application_key = '246f503a-7487-4d09-8ef8-2f1bd8c69fd4';
            $this->subscription_key = '3e9b29d41df3414cad93b9b043e52ec6';                                                

        }

        $this->request_limit = 240; //  PropertyTree API request limit
        $this->sleep_interval_sec = 60; // delay 1 minute    

        $this->load->model('/inc/job_functions_model');
        $this->load->model('/inc/pdf_template');
        $this->load->model('/inc/alarm_functions_model');
        $this->load->model('/inc/functions_model');
        $this->load->model('/inc/Email_functions_model');

        $this->load->library('HashEncryption');
    }

    public function set_request_counter($request_counter){
        $this->request_counter = $request_counter;
    }
 
    public function get_request_counter(){
        return $this->request_counter;
    }

    public function set_start_time($start_time){
        $this->start_time = $start_time;
    }
 
    public function get_start_time(){
        return $this->start_time;
    }

    public function getAccessToken($params){

        $agency_id = $params['agency_id'];
        $api_id = ( $params['api_id'] != '' )?$params['api_id']:4; // default is Palace

        if( $agency_id > 0 ){

            // get Pme tokens
                $sel_query = "
                access_token,
                expiry,
                refresh_token
            ";
            $this->db->select($sel_query);
            $this->db->from('agency_api_tokens');
            $this->db->where('agency_id', $agency_id);
            $this->db->where('api_id', $api_id);
            $pme_sql = $this->db->get();
            $pme_row = $pme_sql->row();

            $access_token = $pme_row->access_token;

            return $access_token;

        }        

    }

    public function get_all_properties($agency_id) {  

        $api_id = 3; // Property Tree

        // API request limit solution
        $req_limit_params = array(
            'api_id' => $api_id,
            'request_limit' => $this->request_limit,
            'sleep_interval_sec' => $this->sleep_interval_sec,
            'agency_id' => $agency_id
        );
        //$this->system_model->api_request_limit_counter_and_delay($req_limit_params);

        // get access token        
        $pme_params = array(
            'agency_id' => $agency_id,
            'api_id' => $api_id
        );
        $access_token = $this->getAccessToken($pme_params);             
        
        $end_points = "{$this->api_gateway}/residentialproperty/v1/Properties";

        $curl = curl_init();

        // HTTP headers
        $http_header = array(
            "Authorization: Bearer {$access_token}",
            "Content-Type: application/json"
        );

        // API call
        $curl_opt = array(
            CURLOPT_URL => $end_points,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $http_header
        );

        curl_setopt_array( $curl, $curl_opt );

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response);

    }

    public function get_property($property_id) {  

        $api_id = 3; // Property Tree

        if( $property_id > 0 ){            

            // get agency ID from property 
            $prop_sql = $this->db->query("
            SELECT `agency_id`
            FROM `property`
            WHERE `property_id` = {$property_id}
            ");
            $prop_row = $prop_sql->row();
            $agency_id = $prop_row->agency_id;

            // API request limit solution
            $req_limit_params = array(                
                'api_id' => $api_id,
                'request_limit' => $this->request_limit,
                'sleep_interval_sec' => $this->sleep_interval_sec,
                'agency_id' => $agency_id
            );
            //$this->system_model->api_request_limit_counter_and_delay($req_limit_params);

            $this->rate_limit_solution();

            // get access token            
            $pme_params = array(
                'agency_id' => $agency_id,
                'api_id' => $api_id
            );
            $access_token = $this->getAccessToken($pme_params);              
            
            // get API property ID
            $crm_connected_prop_sql_str = "
            SELECT `api_prop_id`
            FROM `api_property_data`
            WHERE `crm_prop_id` = {$property_id}
            AND `api` = {$api_id}
            ";
            $crm_connected_prop_sql = $this->db->query($crm_connected_prop_sql_str);
            $crm_connected_prop_row = $crm_connected_prop_sql->row();
            $api_prop_id = $crm_connected_prop_row->api_prop_id;
            
            $end_points = "{$this->api_gateway}/residentialproperty/v1/Properties/{$api_prop_id}";

            $curl = curl_init();

            // HTTP headers
            $http_header = array(
                "Authorization: Bearer {$access_token}",
                "Content-Type: application/json"
            );

            // API call
            $curl_opt = array(
                CURLOPT_URL => $end_points,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => $http_header
            );

            curl_setopt_array( $curl, $curl_opt );

            $response = curl_exec($curl);
            curl_close($curl);

            return json_decode($response);

        }        

    }

    public function get_tenant($agency_id,$tenant_id){  
        
        $api_id = 3; // Property Tree

        // API request limit solution
        $req_limit_params = array(
            'api_id' => $api_id,
            'request_limit' => $this->request_limit,
            'sleep_interval_sec' => $this->sleep_interval_sec,
            'agency_id' => $agency_id
        );
        //$this->system_model->api_request_limit_counter_and_delay($req_limit_params);

        // get access token
        $pme_params = array(
            'agency_id' => $agency_id,
            'api_id' => $api_id
        );
        $access_token = $this->getAccessToken($pme_params);             
        
        $end_points = "{$this->api_gateway}/residentialproperty/v1/Tenancies/{$tenant_id}";

        $curl = curl_init();

        // HTTP headers
        $http_header = array(
            "Authorization: Bearer {$access_token}",
            "Content-Type: application/json"
        );

        // API call
        $curl_opt = array(
            CURLOPT_URL => $end_points,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $http_header
        );

        curl_setopt_array( $curl, $curl_opt );

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response);

    }  

    public function get_landlord($agency_id,$ownership_id){  
        
        $api_id = 3; // Property Tree

        // API request limit solution
        $req_limit_params = array(
            'api_id' => $api_id,
            'request_limit' => $this->request_limit,
            'sleep_interval_sec' => $this->sleep_interval_sec,
            'agency_id' => $agency_id
        );
        //$this->system_model->api_request_limit_counter_and_delay($req_limit_params);

        // get access token
        $pme_params = array(
            'agency_id' => $agency_id,
            'api_id' => $api_id
        );
        $access_token = $this->getAccessToken($pme_params);             
        
        $end_points = "{$this->api_gateway}/residentialproperty/v1/Ownerships/{$ownership_id}";

        $curl = curl_init();

        // HTTP headers
        $http_header = array(
            "Authorization: Bearer {$access_token}",
            "Content-Type: application/json"
        );

        // API call
        $curl_opt = array(
            CURLOPT_URL => $end_points,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $http_header
        );

        curl_setopt_array( $curl, $curl_opt );

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response);

    }
    
    // get agencies activated with SATS
    public function get_agencies_activated_with_sats(){            
        
        // Get Application Key Pairs: https://uatdeveloper.propertytree.io/api-details#api=rockend-apikey-service-api-external&operation=GetApplicationKeyPairs
        $end_points = "{$this->api_gateway}/apikey/v1/application_keys/{$this->application_key}";

        $curl = curl_init();

        // HTTP headers
        $http_header = array(
            "Ocp-Apim-Subscription-Key: {$this->subscription_key}"
        );

        // API call
        $curl_opt = array(
            CURLOPT_URL => $end_points,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $http_header
        );

        curl_setopt_array( $curl, $curl_opt );

        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE); // HTTP status code
        curl_close($curl);

        return (object) [
            'json_decoded_response' => json_decode($response),
            'httpcode' => $httpcode
        ];

    }

    public function get_agency_details($access_token){   
        
        $end_points = "{$this->api_gateway}/residentialproperty/v1/Agencies";

        $this->rate_limit_solution();

        $curl = curl_init();

        // HTTP headers
        $http_header = array(
            "Authorization: Bearer {$access_token}",
            "Content-Type: application/json"
        );

        // API call
        $curl_opt = array(
            CURLOPT_URL => $end_points,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $http_header
        );

        curl_setopt_array( $curl, $curl_opt );

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response);

    }   
    
    public function merge_jobs(){

        $job_status = "Merged Certificates";
        $country_id = $this->config->item('country');

        $ptree_api = 3;

        $sel_query_pme = "
        j.`id` AS jid,
        j.`status` AS j_status,
        j.`service` AS j_service,
        j.`created` AS j_created,
        j.`date` AS j_date,
        j.`comments` AS j_comments,
        j.`job_price` AS j_price,
        j.`job_type` AS j_type,
        j.`at_myob`,
        j.`sms_sent_merge`,
        j.`client_emailed`,
        j.`invoice_amount`,
        
        p.`property_id` AS prop_id, 
        p.`address_1` AS p_address_1, 
        p.`address_2` AS p_address_2, 
        p.`address_3` AS p_address_3,
        p.`state` AS p_state,
        p.`postcode` AS p_postcode,
        p.`comments` AS p_comments,
        p.`propertyme_prop_id`, 
        
        a.`agency_id` AS a_id,
        a.`agency_name` AS agency_name,
        a.`phone` AS a_phone,
        a.`address_1` AS a_address_1, 
        a.`address_2` AS a_address_2, 
        a.`address_3` AS a_address_3,
        a.`state` AS a_state,
        a.`postcode` AS a_postcode,
        a.`trust_account_software`,
        a.`tas_connected`,
        a.`send_emails`,
        a.`account_emails`,
        
        ajt.`id` AS ajt_id,
        ajt.`type` AS ajt_type,

        apd_ptree.`api` AS ptree_api,
        apd_ptree.`api_prop_id` AS ptree_prop_id,

        aad.is_invoice,
        aad.is_certificate
        ";

        $custom_where = "
        p.`send_to_email_not_api` = 0 AND
        ( 
            (
                apd_ptree.`api_prop_id` IS NOT NULL AND 
                apd_ptree.`api_prop_id` != '' AND 
                apd_ptree.`api` = {$ptree_api}
            ) AND            
            (  pt_agen_pref.creditor IS NOT NULL AND pt_agen_pref.creditor != '' ) AND
            (  pt_agen_pref.account IS NOT NULL AND pt_agen_pref.account != '' ) AND
            (  pt_agen_pref.prop_comp_cat IS NOT NULL AND pt_agen_pref.prop_comp_cat != '' ) AND
            ( 
                (
                    agen_api_doc.`is_invoice` = 1 OR
                    agen_api_doc.`is_invoice` IS NULL
                ) OR (
                    agen_api_doc.`is_certificate` = 1 OR
                    agen_api_doc.`is_certificate` IS NULL
                )
                     
            )
        ) AND
        (
            ( ajd_pt.`api_inv_uploaded` = 0 OR ajd_pt.`api_inv_uploaded` IS NULL ) AND 
            ( ajd_pt.`api_cert_uploaded` = 0 OR ajd_pt.`api_cert_uploaded` IS NULL )
        ) AND                  
        ( 
            j.`prop_comp_with_state_leg` IS NULL OR 
            j.`prop_comp_with_state_leg` = 1 
        )  AND
        ( j.`client_emailed` IS NULL OR j.`client_emailed` = '' )            
        ";
        $paramsPmeSent = array(
            'sel_query' => $sel_query_pme,
            'p_deleted' => 0,
            'a_status' => 'active',
            'del_job' => 0,
            'country_id' => $country_id,
            'job_status' => $job_status,
            'join_table' => array('job_type','alarm_job_type'),
            'custom_joins_arr' => array(

                array(
                    'join_table' => '`api_property_data` AS apd_ptree',
                    'join_on' => "( p.`property_id` = apd_ptree.`crm_prop_id` AND apd_ptree.`api` = {$ptree_api} )",
                    'join_type' => 'left'
                ),
                array(
                    'join_table' => '`api_job_data` AS ajd_pt',
                    'join_on' => "( j.`id` = ajd_pt.`crm_job_id` AND ajd_pt.`api` = {$ptree_api} )",
                    'join_type' => 'left'
                ),
                array(
                    'join_table' => '`propertytree_agency_preference` AS pt_agen_pref',
                    'join_on' => "a.`agency_id` = pt_agen_pref.`agency_id` AND pt_agen_pref.`active` = 1",
                    'join_type' => 'left'
                ),
                array(
                    'join_table' => '`agency_api_documents` AS agen_api_doc',
                    'join_on' => "a.`agency_id` = agen_api_doc.`agency_id`",
                    'join_type' => 'left'
                )
                  

            ),
            
            'custom_where' => $custom_where,
        );
        
        return $this->Pme_model->get_jobs_with_pme_connect($paramsPmeSent);

    }

    /*
    public function send_all_certificates_and_invoices() {

        $this->load->model('cron_model');

        $this->cron_model->create_pt_maintenance_request();
        $this->cron_model->pt_upload_invoice_and_certificate();

    }
    */

    // no longer used, use the cron one instead
    public function send_all_certificates_and_invoices(){

        $country_id = $this->config->item('country');       
	    $api = 3; // PropertyTree 

        // Initialize request counter and timestamp
        $this->request_counter = 0;
        $this->start_time = time();

        // http code success
        $httpcode_success_arr = array(200, 201, 202);

        // merge query
        $job_sql = $this->merge_jobs();

        foreach( $job_sql->result() as $job_row ){

            $job_id = $job_row->jid;      

            // for pdf url
            $encrypt = rawurlencode(HashEncryption::encodeString($job_id));
            $baseUrl = $_SERVER["SERVER_NAME"];
            if(isset($_SERVER['HTTPS'])){
                $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
            } else{
                $protocol = 'http';
            }

            // append checkdigit to job id for new invoice number
            $check_digit = $this->gherxlib->getCheckDigit(trim($job_id));
            $bpay_ref_code = "{$job_id}{$check_digit}"; 

            $job_details = $this->job_functions_model->getJobDetails2($job_id,$query_only = false);

            # Alarm Details
            $alarm_details = [];
            if (in_array($job_details['jservice'], Alarm_job_type_model::SMOKE_ALARM_IDS)) {
                $alarm_details = $this->alarm_functions_model->getPropertyAlarms($job_id, 1, 0, $job_details['jservice']);
            }
            $num_alarms = is_null($alarm_details) ? 0 : sizeof($alarm_details);               

            # Property + Agent Details
            $property_details = $this->functions_model->getPropertyAgentDetails($job_details['property_id']); 

            // get invoice pdf
            $invoice_pdf = $this->pdf_template->pdf_invoice_template($job_id, $job_details, $property_details, $alarm_details, $num_alarms, $country_id);   

            // get property      
            $api_prop_json = $this->get_property($job_details['property_id']);
            $api_prop_obj = $api_prop_json[0];
            $prop_management_id = $api_prop_obj->management_id;
            
            // get agency_api_documents
            $aad_sql = $this->db->query("
            SELECT `is_invoice`, `is_certificate`
            FROM `agency_api_documents`
            WHERE `agency_id` = {$job_details['agency_id']}
            ");
            $aad_row = $aad_sql->row();

            // get agency preference
            $agency_pref = $this->get_agency_preference($job_details['agency_id']);

            // upload invoice, do not send $0 invoice
            if( ( $aad_row->is_invoice == 1 || ( $aad_row->is_invoice == '' && !is_numeric($aad_row->is_invoice) ) ) && $job_details['invoice_balance'] > 0 ){

                // Add Creditor Invoice With Attachment
                $params_obj = (object)[
                    'job_details' => $job_details,
                    'invoice_pdf' => $invoice_pdf,
                    'bpay_ref_code' => $bpay_ref_code,
                    'prop_management_id' => $prop_management_id
                ];
                $add_cred_inv_with_attch_ret = $this->add_creditor_invoice_with_attachment($params_obj);
                $json_decoded_response = $add_cred_inv_with_attch_ret->json_decoded_response;

                if( in_array($add_cred_inv_with_attch_ret->httpcode, $httpcode_success_arr) && $json_decoded_response->invoice_id != '' ){ // invoice uploaded

                    // check api_job_data again if data exist in a certain job
                    $api_job_data_sql = $this->db->query("
                    SELECT COUNT(`id`) AS ajd_count
                    FROM `api_job_data`
                    WHERE `crm_job_id` = {$job_id}
                    ");
                    $ajd_count = $api_job_data_sql->row()->ajd_count;

                    // mark as invoice uploaded
                    if( $ajd_count > 0 ){ // already exist

                        // update
                        $this->db->query("
                        UPDATE `api_job_data`
                        SET `api_inv_uploaded` = 1
                        WHERE `crm_job_id` = {$job_id}
                        AND  `api` = {$api}
                        ");                                

                    }else{

                        // insert
                        $data = array(
                            'crm_job_id' => $job_id,
                            'api' => $api,
                            'api_inv_uploaded' => 1
                        );                                
                        $this->db->insert('api_job_data', $data);

                    }   

                    // insert job log
                    $log_details = "<a href='".$protocol."://{$baseUrl}/pdf/invoices/{$encrypt}'>Invoice</a> has been uploaded to PropertyTree API";
                    $log_params = array(
                        'title' => 91,  // PropertyTree API
                        'details' => $log_details,
                        'display_in_vjd' => 1,
                        'property_id' => $job_details['property_id'],
                        'job_id' => $job_id,
                        'agency_id' => $job_details['agency_id']
                    );

                    // if not CRON, user logged
                    if($this->session->staff_id !='' ){
                        $append_jlval = $this->session->staff_id;
                        $log_params['created_by_staff'] = $append_jlval;
                    }else{
                        $append_jlval = 1;
                        $log_params['auto_process'] = $append_jlval;
                    }

                    $this->system_model->insert_log($log_params);
                    
                }

            }             
            
            // do not upload certificate pdf for upfront bill(2)
            if ( 

                ( $aad_row->is_certificate == 1 || ( $aad_row->is_certificate == '' && !is_numeric($aad_row->is_certificate) ) ) && 
                $job_details['assigned_tech'] != 2 

            ) {

                // compliance notes
                $completed_date = ( $this->system_model->isDateNotEmpty($job_details['jdate']) )?$this->system_model->formatDate($job_details['jdate'],'d/m/Y'):null;
                $comp_notes = "{$job_details['ajt_type']} {$job_details['job_type']} job completed on {$completed_date}";

                // certificate pdf
                $certificate_pdf = $this->pdf_template->pdf_certificate_template_v2($job_id, $job_details, $property_details, $alarm_details, $num_alarms, $country_id);                  

                // check if compliance is general or smoke alarms
                $prop_comp_cat_type = 'General';
                $prop_comp_cat_ret = $this->property_compliance_categories($job_details['agency_id']);
                foreach( $prop_comp_cat_ret->json_decoded_response as $comp_cat_row ){

                    if( $comp_cat_row->category_id == $agency_pref->prop_comp_cat ){
                        $prop_comp_cat_type = $comp_cat_row->property_compliance_detail_type;
                    }

                }                
                
                // search for existing non-deleted open compliance
                $params_obj = (object)[
                    'job_details' => $job_details,
                    'prop_management_id' => $prop_management_id
                ];
                $compliance_api_ret = $this->search_compliance($params_obj);

                if( in_array($compliance_api_ret->httpcode, $httpcode_success_arr) && count($compliance_api_ret->json_decoded_response) > 0 ){ // has existing compliance found

                    $existing_compliance_id = $compliance_api_ret->json_decoded_response[0]->compliance_id;

                    // check if existing compliance has attachment
                    $params_obj = (object)[
                        'agency_id' => $job_details['agency_id'],
                        'compliance_id' => $existing_compliance_id
                    ];
                    $compliance_doc_ret = $this->get_compliance_documents($params_obj);

                    if( in_array($compliance_doc_ret->httpcode, $httpcode_success_arr) && count($compliance_doc_ret->json_decoded_response) > 0 ){ // has attachments
                        
                        // complete existing open compliance
                        $params_obj = (object)[
                            'job_id' => $job_id,
                            'agency_id' => $job_details['agency_id'],
                            'compliance_id' => $existing_compliance_id
                        ];
                        $complete_comp_ret = $this->complete_compliance($params_obj);

                        if( in_array($complete_comp_ret->httpcode, $httpcode_success_arr) ){

                            // create dynamic property compliance
                            $params_obj = (object)[
                                'job_id' => $job_id,
                                'agency_id' => $job_details['agency_id'],
                                'job_details' => $job_details,
                                'prop_management_id' => $prop_management_id,
                                'comp_notes' => $comp_notes,
                                'prop_comp_cat_type' => $prop_comp_cat_type
                            ];
                            $create_sa_comp_ret = $this->create_dynamic_alarm_compliance($params_obj);
                            $create_sa_comp_decoded_response = $create_sa_comp_ret->json_decoded_response;

                            if( in_array($create_sa_comp_ret->httpcode, $httpcode_success_arr) ){

                                $new_compliance_id = $create_sa_comp_decoded_response->property_compliance_id;

                                // attach document to new compliance
                                $params_obj = (object)[
                                    'job_id' => $job_id,
                                    'agency_id' => $job_details['agency_id'],
                                    'property_compliance_id' => $new_compliance_id,
                                    'certificate_pdf' => $certificate_pdf,
                                    'bpay_ref_code' => $bpay_ref_code
                                ];
                                $att_doc_to_comp_ret = $this->attach_document_to_compliance($params_obj);

                                if( in_array($att_doc_to_comp_ret->httpcode, $httpcode_success_arr) ){ // certificate uploaded

                                    // mark job as certificate of compliance API uploaded and insert log
                                    $mark_job_obj = (object)[
                                        'job_details' => $job_details
                                    ];
                                    $this->mark_job_as_compliance_and_insert_log($mark_job_obj);

                                    // complete open compliance
                                    $params_obj = (object)[
                                        'job_id' => $job_id,
                                        'agency_id' => $job_details['agency_id'],
                                        'compliance_id' => $new_compliance_id
                                    ];
                                    $complete_comp_ret = $this->complete_compliance($params_obj);

                                    if( $complete_comp_ret->httpcode == 200 ){

                                        // create another smoke alarm compliance and leave it open for next service :)
                                        $params_obj = (object)[
                                            'job_id' => $job_id,
                                            'agency_id' => $job_details['agency_id'],
                                            'job_details' => $job_details,
                                            'prop_management_id' => $prop_management_id,
                                            'prop_comp_cat_type' => $prop_comp_cat_type
                                        ];
                                        $this->create_dynamic_alarm_compliance($params_obj);

                                    }

                                }                                                                

                            }                            

                        }                        

                    }else{ // no compliance attachment found

                        // update existing smoke alarm compliance
                        $params_obj = (object)[
                            'job_details' => $job_details,             
                            'compliance_id' => $existing_compliance_id,
                            'comp_notes' => $comp_notes,
                            'prop_comp_cat_type' => $prop_comp_cat_type
                        ];
                        $update_sa_comp_ret = $this->update_alarm_compliance_dynamically($params_obj);   
                        
                        if( in_array($update_sa_comp_ret->httpcode, $httpcode_success_arr) ){

                            // attach document to existing compliance
                            $params_obj = (object)[
                                'job_id' => $job_id,
                                'agency_id' => $job_details['agency_id'],
                                'property_compliance_id' => $existing_compliance_id,
                                'certificate_pdf' => $certificate_pdf,
                                'bpay_ref_code' => $bpay_ref_code
                            ];
                            $att_doc_to_comp_ret = $this->attach_document_to_compliance($params_obj);

                            if( in_array($att_doc_to_comp_ret->httpcode, $httpcode_success_arr) ){ // certificate uploaded

                                // mark job as certificate of compliance API uploaded and insert log
                                $mark_job_obj = (object)[
                                    'job_details' => $job_details
                                ];
                                $this->mark_job_as_compliance_and_insert_log($mark_job_obj);

                                // complete open compliance
                                $params_obj = (object)[
                                    'job_id' => $job_id,
                                    'agency_id' => $job_details['agency_id'],
                                    'compliance_id' => $existing_compliance_id
                                ];
                                $complete_comp_ret = $this->complete_compliance($params_obj);

                                if( in_array($complete_comp_ret->httpcode, $httpcode_success_arr) ){

                                    // create another smoke alarm compliance and leave it open for next service :)
                                    $params_obj = (object)[
                                        'job_id' => $job_id,
                                        'agency_id' => $job_details['agency_id'],
                                        'job_details' => $job_details,
                                        'prop_management_id' => $prop_management_id,
                                        'prop_comp_cat_type' => $prop_comp_cat_type
                                    ];
                                    $this->create_dynamic_alarm_compliance($params_obj);

                                }                            
                                
                            }

                        }                                                

                    }

                }else{ // no existing compliance found

                    // create dynamic property compliance
                    $params_obj = (object)[
                        'job_id' => $job_id,
                        'agency_id' => $job_details['agency_id'],
                        'job_details' => $job_details,
                        'prop_management_id' => $prop_management_id,
                        'comp_notes' => $comp_notes,
                        'prop_comp_cat_type' => $prop_comp_cat_type
                    ];
                    $create_sa_comp_ret = $this->create_dynamic_alarm_compliance($params_obj);
                    $create_sa_comp_decoded_response = $create_sa_comp_ret->json_decoded_response;

                    if( in_array($create_sa_comp_ret->httpcode, $httpcode_success_arr) ){

                        $new_compliance_id = $create_sa_comp_decoded_response->property_compliance_id;

                        // attach document to new compliance
                        $params_obj = (object)[
                            'job_id' => $job_id,
                            'agency_id' => $job_details['agency_id'],
                            'property_compliance_id' => $new_compliance_id,
                            'certificate_pdf' => $certificate_pdf,
                            'bpay_ref_code' => $bpay_ref_code
                        ];
                        $att_doc_to_comp_ret = $this->attach_document_to_compliance($params_obj);                 

                        if( in_array($att_doc_to_comp_ret->httpcode, $httpcode_success_arr) ){ // certificate uploaded

                            // mark job as certificate of compliance API uploaded and insert log
                            $mark_job_obj = (object)[
                                'job_details' => $job_details
                            ];
                            $this->mark_job_as_compliance_and_insert_log($mark_job_obj);

                            // complete open compliance
                            $params_obj = (object)[
                                'job_id' => $job_id,
                                'agency_id' => $job_details['agency_id'],
                                'compliance_id' => $new_compliance_id
                            ];
                            $complete_comp_ret = $this->complete_compliance($params_obj);

                            if( in_array($complete_comp_ret->httpcode, $httpcode_success_arr) ){

                                // create another smoke alarm compliance and leave it open for next service :)
                                $params_obj = (object)[
                                    'job_id' => $job_id,
                                    'agency_id' => $job_details['agency_id'],
                                    'job_details' => $job_details,
                                    'prop_management_id' => $prop_management_id,
                                    'prop_comp_cat_type' => $prop_comp_cat_type
                                ];
                                $this->create_dynamic_alarm_compliance($params_obj);

                            }                            
                            
                        }                        

                    }                    

                }                                                

            }


            if( 
                !( 
                    ( $aad_row->is_invoice == 1 || $aad_row->is_invoice == NULL ) && 
                    ( $aad_row->is_certificate == 1 || $aad_row->is_certificate == NULL ) 
                ) 
            ){


                // copied from ram's function, he said this sends dynamic email based on agency preference
                $this->email_functions_model->batchSendInvoicesCertificates($job_id);

                /*
                // send invoice through email
                $job_params = array(
                    'job_id' => $job_id
                );
                $this->email_functions_model->send_invoice_email($job_params);
                */

            }

        }                                                              

    }    
    
    public function get_agency_preference($agency_id){

        if( $agency_id > 0 ){

            $sql = $this->db->query("
            SELECT *
            FROM `propertytree_agency_preference`
            WHERE `agency_id` = {$agency_id}
            ");

            return $sql->row();

        }        

    }
    
    /*
    public function create_maintenance_request($params_obj){

        $job_id = $params_obj->job_id;
        $job_details = $params_obj->job_details;
        $agency_id = $job_details['agency_id'];	
        $prop_management_id = $params_obj->prop_management_id;
        $bpay_ref_code = $params_obj->bpay_ref_code;

        $api_id = 3; // Property Tree

        // get access token        
        $pme_params = array(
            'agency_id' => $agency_id,
            'api_id' => $api_id
        );
        $access_token = $this->getAccessToken($pme_params);   
        
        // get agency preference
        $agency_pref = $this->get_agency_preference($agency_id);
        
        $end_points = "{$this->api_gateway}/maintenanceManager/api/v1/MaintenanceRequests";        
            
        // UUID/GUID
        $create_maintenance_uuid = $this->system_model->guidv4(); 

        // HTTP headers
        $http_header = array(
            "Authorization: Bearer {$access_token}",
            "Content-Type: application/json",
            "x-user-id: {$agency_pref->agent}"
        );

        // get service type
        $service_type_sql = $this->db->query("
        SELECT `type`
        FROM `alarm_job_type`
        WHERE `id` = {$job_details['jservice']}
        ");
        $service_type_row = $service_type_sql->row();
        $summary = "{$job_details['job_type']} {$service_type_row->type}";

        // maintenance request payload
        $curl_postfields = array(
            'integrator_operation_id' => $create_maintenance_uuid,
            'management_external_id' => $prop_management_id,
            'reference' => $bpay_ref_code,
            'summary' => $summary
        );

        // API call
        $curl = curl_init(); // start cURL

        // cURL options
        $curl_opt = array(
            CURLOPT_URL => $end_points,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $http_header,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($curl_postfields)
        );
        curl_setopt_array( $curl, $curl_opt );

        $response = curl_exec($curl); // cURL response
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE); // HTTP status code
        curl_close($curl); // close cURL  
        
        // capture json return
        $payload_final = ( count($curl_postfields) > 0 )?json_encode($curl_postfields):null;
        $http_header_final = ( count($http_header) > 0 )?json_encode($http_header):null;

        $api_data_params = array(
            'job_id' => $job_id,
            'api_endpoint' => $end_points,
            'http_header' => $http_header_final,
            'payload' => $payload_final,
            'http_status_code' => $httpcode,
            'raw_response' => $response
        );
        $this->system_model->capture_api_data($api_data_params);

        return (object) [
            'json_decoded_response' => json_decode($response),
            'httpcode' => $httpcode
        ];
        
    }
    */

    /*
    public function create_attachment($params_obj){

        $job_id = $params_obj->job_id;
        $agency_id = $params_obj->agency_id;

        $api_id = 3; // Property Tree

        // get access token        
        $pme_params = array(
            'agency_id' => $agency_id,
            'api_id' => $api_id
        );
        $access_token = $this->getAccessToken($pme_params);     
        
        // get agency preference
        $agency_pref = $this->get_agency_preference($agency_id);

        // API call
        $curl = curl_init(); // start cURL

        // create attachment
        $end_points = "{$this->api_gateway}/maintenanceManager/api/v1/Attachments/UploadUri";    
        
         // HTTP headers
         $http_header = array(
            "Authorization: Bearer {$access_token}",
            "Content-Type: application/json",
            "x-user-id: {$agency_pref->agent}"
        );
        
        // cURL options
        $curl_opt = array(
            CURLOPT_URL => $end_points,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $http_header,
            CURLOPT_POST => true
        );
        curl_setopt_array( $curl, $curl_opt );

        $response = curl_exec($curl); // cURL response
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE); // HTTP status code
        curl_close($curl); // close cURL

        // capture json return
        $http_header_final = ( count($http_header) > 0 )?json_encode($http_header):null;

        $api_data_params = array(
            'job_id' => $job_id,
            'api_endpoint' => $end_points,
            'http_header' => $http_header_final,
            'http_status_code' => $httpcode,
            'raw_response' => $response
        );
        $this->system_model->capture_api_data($api_data_params);

        return (object) [
            'json_decoded_response' => json_decode($response),
            'httpcode' => $httpcode
        ];

    }
   

    public function load_attachment($params_obj){

        $job_id = $params_obj->job_id;
        $full_file_name = $params_obj->full_file_name;
        $invoice_pdf = $params_obj->invoice_pdf;
        $load_attachment_uri = $params_obj->load_attachment_uri;                 

        // create temporary file
        $temp = tmpfile();
        fwrite($temp, $invoice_pdf);
        $invoice_path = stream_get_meta_data($temp)['uri'];              
                                
        // upload file using CurlFile
        $curl_postfields = array(
            '' => new CurlFile($invoice_path,'application/pdf',$full_file_name)
        );   

        // HTTP headers
        $load_attachment_http_header = array(           
            "x-ms-blob-type: BlockBlob",
            "x-ms-blob-content-disposition: attachment; filename={$full_file_name}"
        ); 

        // API call
        $curl = curl_init(); // start cURL
        
        // cURL options
        $curl_opt = array(
            CURLOPT_URL => $load_attachment_uri,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $load_attachment_http_header,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => $curl_postfields
        );
        curl_setopt_array( $curl, $curl_opt );

        $response = curl_exec($curl); // cURL response
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE); // HTTP status code
        curl_close($curl); // close cURL

        // capture json return
        $payload_final = ( count($curl_postfields) > 0 )?json_encode($curl_postfields):null;
        $http_header_final = ( count($load_attachment_http_header) > 0 )?json_encode($load_attachment_http_header):null;

        $api_data_params = array(
            'job_id' => $job_id,
            'api_endpoint' => $load_attachment_uri,
            'http_header' => $http_header_final,
            'payload' => $payload_final,
            'http_status_code' => $httpcode,
            'raw_response' => $response
        );
        $this->system_model->capture_api_data($api_data_params);
        
        return (object) [
            'json_decoded_response' => json_decode($response),
            'httpcode' => $httpcode
        ];

    }

    public function create_invoice_with_attachment($params_obj){

        $job_id = $params_obj->job_id;
        $job_details = $params_obj->job_details;
        $agency_id = $job_details['agency_id'];	
        $maintenance_request_external_id = $params_obj->maintenance_request_external_id;
        $load_attachment_uri = $params_obj->load_attachment_uri;
        $file_name = $params_obj->file_name;
        $file_type = $params_obj->file_type;

        $api_id = 3; // Property Tree

        // get access token        
        $pme_params = array(
            'agency_id' => $agency_id,
            'api_id' => $api_id
        );
        $access_token = $this->getAccessToken($pme_params);  
        
        // get agency preference
        $agency_pref = $this->get_agency_preference($agency_id);

        // API call
        $curl = curl_init(); // start cURL

        // Create Invoice and add attachment
        $end_points = "{$this->api_gateway}/maintenanceManager/api/v1/MaintenanceRequests/{$maintenance_request_external_id}/invoice";   
        
        // HTTP headers
        $http_header = array(
            "Authorization: Bearer {$access_token}",
            "Content-Type: application/json",
            "x-user-id: {$agency_pref->agent}"
        );          
                        
        $uuid = $this->system_model->guidv4(); 

        // get service type
        $service_type_sql = $this->db->query("
        SELECT `type`
        FROM `alarm_job_type`
        WHERE `id` = {$job_details['jservice']}
        ");
        $service_type_row = $service_type_sql->row();        

        // invoice attachment details
        $invoice_attachment_data = array(
            'uploaded_attachment_uri' => $load_attachment_uri,
            'attachment_friendly_name' => $file_name,
            'attachment_extension' => $file_type
        );

        // get GST
        $gst = ( $this->config->item('country') == 1 )?($job_details['invoice_balance']/11):($job_details['invoice_balance']*3)/23;

        // get creditors
        $creditor_reference = null;
        $params_obj = (object)[
			'agency_id' => $agency_id,
			'creditor_id' => $agency_pref->creditor
		];	
		$ret_obj = $this->get_creditor($params_obj);

        if( $ret_obj->httpcode == 200 ){
            $creditor_reference = $ret_obj->json_decoded_response->reference;
        } 
        
        $curl_postfields = array(
            'integrator_operation_id' => $uuid,
            'creditor_external_id' => $agency_pref->creditor,
            'creditor_reference' => $creditor_reference,
            'invoice_description' => $service_type_row->type,
            'amount_excluding_gst' => ($job_details['invoice_balance']-$gst),
            'due_date' => date('Y-m-d H:i:s',strtotime("+1 year")),
            'job_title' => $job_details['job_type'],
            'job_description' => $service_type_row->type,
            'account_code' =>  $agency_pref->account_code,
            'gst_amount' => $gst,
            'invoice_attachment' => $invoice_attachment_data
        );

        // API call
        $curl = curl_init(); // start cURL

        // cURL options
        $curl_opt = array(
            CURLOPT_URL => $end_points,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $http_header,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($curl_postfields)
        );
        curl_setopt_array( $curl, $curl_opt );

        $response = curl_exec($curl); // cURL response
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE); // HTTP status code
        curl_close($curl); // close cURL           

        // capture json return
        $payload_final = ( count($curl_postfields) > 0 )?json_encode($curl_postfields):null;
        $http_header_final = ( count($http_header) > 0 )?json_encode($http_header):null;

        $api_data_params = array(
            'job_id' => $job_id,
            'api_endpoint' => $end_points,
            'http_header' => $http_header_final,
            'payload' => $payload_final,
            'http_status_code' => $httpcode,
            'raw_response' => $response
        );
        $this->system_model->capture_api_data($api_data_params);

        return (object) [
            'json_decoded_response' => json_decode($response),
            'httpcode' => $httpcode
        ];

    }
  

    public function check_if_call_is_completed($params_obj){

        $job_id = $params_obj->job_id;
        $agency_id = $params_obj->agency_id;
        $integrator_operation_id = $params_obj->integrator_operation_id;

        $api_id = 3; // Property Tree      

        // get access token        
        $pme_params = array(
            'agency_id' => $agency_id,
            'api_id' => $api_id
        );
        $access_token = $this->getAccessToken($pme_params);  
        
        // get agency preference
        $agency_pref = $this->get_agency_preference($agency_id);
        
        $end_points = "{$this->api_gateway}/maintenanceManager/api/v1/queue/{$integrator_operation_id}";

        // HTTP headers
        $http_header = array(
            "Authorization: Bearer {$access_token}",
            "x-user-id: {$agency_pref->agent}"
        );

        $curl = curl_init();

        // API call
        $curl_opt = array(
            CURLOPT_URL => $end_points,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $http_header,
            CURLOPT_FOLLOWLOCATION => true
        );

        curl_setopt_array( $curl, $curl_opt );

        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE); // HTTP status code
        curl_close($curl);

        // capture json return
        $http_header_final = ( count($http_header) > 0 )?json_encode($http_header):null;

        $api_data_params = array(
            'job_id' => $job_id,
            'api_endpoint' => $end_points,
            'http_header' => $http_header_final,
            'http_status_code' => $httpcode,
            'raw_response' => $response
        );
        $this->system_model->capture_api_data($api_data_params);

        return (object) [
            'json_decoded_response' => json_decode($response),
            'httpcode' => $httpcode
        ];
        
    }
    */

    public function create_dynamic_alarm_compliance($params_obj){

        $job_id = $params_obj->job_id;
        $agency_id = $params_obj->agency_id;
        $job_details = $params_obj->job_details;
        $prop_management_id = $params_obj->prop_management_id;
        $comp_notes = $params_obj->comp_notes;
        $prop_comp_cat_type = $params_obj->prop_comp_cat_type;

        $api_id = 3; // Property Tree

        // API request limit solution
        $req_limit_params = array(
            'api_id' => $api_id,
            'request_limit' => $this->request_limit,
            'sleep_interval_sec' => $this->sleep_interval_sec,
            'agency_id' => $agency_id
        );
        //$this->system_model->api_request_limit_counter_and_delay($req_limit_params);  
        
        $this->rate_limit_solution();

        // get access token        
        $pme_params = array(
            'agency_id' => $agency_id,
            'api_id' => $api_id
        );
        $access_token = $this->getAccessToken($pme_params); 

        // get agency preference
        $agency_pref = $this->get_agency_preference($agency_id);
        
        // HTTP headers
        $http_header = array(
            "Authorization: Bearer {$access_token}",
            "Content-Type: application/json"
        );                
         
        // Add Dynamic Property Compliance
        $end_points = "{$this->api_gateway}/residentialproperty/v1/PropertyCompliance/{$prop_comp_cat_type}";

        // payload
        // frequency
        $frequency_arr = array(
            'value' => 12,
            'period' => 'Months'
        );

        // reminder
        $reminder_arr = array(
            'value' => 1,
            'period' => 'Months'
        );
        
        $last_service_date = ( $this->system_model->isDateNotEmpty($job_details['jdate']) )?$job_details['jdate']:null;        
        $next_service_date = ( $this->system_model->isDateNotEmpty($job_details['jdate']) )?date('Y-m-d H:i:s',strtotime("{$job_details['jdate']} +365 days")):null;
        $expiry_date = ( $this->system_model->isDateNotEmpty($next_service_date) )?date('Y-m-d H:i:s',strtotime("{$next_service_date} -1 day")):null;
        
        // general details
        $general_details_arr = array(
            'management_id' => $prop_management_id,
            'category_id' => $agency_pref->prop_comp_cat,
            'managed_by' => 'Agent',
            'serviced_by_creditor_id' => $agency_pref->creditor,
            'pinned_to_entity_type' => 'Property',
            'frequency' => $frequency_arr,
            'reminder' => $reminder_arr,
            'expiry_date' => $expiry_date,
            'last_service_date' => $last_service_date,
            'next_service_date' => $next_service_date
        );   
        
        if( $comp_notes != '' ){

            $completed_date = ( $this->system_model->isDateNotEmpty($job_details['jdate']) )?$this->system_model->formatDate($job_details['jdate'],'d/m/Y'):null;
            $general_details_arr['notes'] = "{$job_details['ajt_type']} {$job_details['job_type']} completed on {$completed_date}";

        }         

        // Add Property Compliance
        if( $prop_comp_cat_type == 'SmokeAlarm' ){ // Smoke Alarm                    

            // get alarms
            $alarms_sql = $this->db->query("
            SELECT al_pwr.`alarm_pwr_source`
            FROM `alarm` AS al
            LEFT JOIN `alarm_pwr` AS al_pwr ON al.`alarm_power_id` = al_pwr.`alarm_pwr_id`
            WHERE al.`job_id` = {$job_id}
            AND al.`ts_discarded` = 0
            ");

            $smoke_alarm_type = null;
            $is_240v_count = 0;
            $is_not_240v_count = 0;
            $alarms_total_count = $alarms_sql->num_rows();

            foreach( $alarms_sql->result() as $alarms_row ){

                if( $alarms_row->alarm_pwr_source == '240v' ){
                    $is_240v_count++;
                }else{
                    $is_not_240v_count++;
                }

            }

            // smoke alarm type
            if( $is_240v_count == $alarms_total_count ){
                $smoke_alarm_type = 'HardWired';
            }else if( $is_not_240v_count == $alarms_total_count ){
                $smoke_alarm_type = 'Battery';
            }else{
                $smoke_alarm_type = 'Mixed';
            }

            // payload
            $curl_postfields = array(
                'general_details' => $general_details_arr,
                'smoke_alarm_type' => $smoke_alarm_type
            );  

        }else{ // general

            // payload
            $curl_postfields = $general_details_arr;  

        }

        // API call
        $curl = curl_init(); // start cURL

        // cURL options
        $curl_opt = array(
            CURLOPT_URL => $end_points,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $http_header,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($curl_postfields)
        );
        curl_setopt_array( $curl, $curl_opt );

        $response = curl_exec($curl); // cURL response
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE); // HTTP status code
        curl_close($curl); // close cURL  
        
        // capture json return
        $payload_final = ( count($curl_postfields) > 0 )?json_encode($curl_postfields):null;
        $http_header_final = ( count($http_header) > 0 )?json_encode($http_header):null;

        $api_data_params = array(
            'job_id' => $job_id,
            'api_endpoint' => $end_points,
            'http_header' => $http_header_final,
            'payload' => $payload_final,
            'http_status_code' => $httpcode,
            'raw_response' => $response
        );
        $this->system_model->capture_api_data($api_data_params);

        return (object) [
            'json_decoded_response' => json_decode($response),
            'httpcode' => $httpcode
        ];

    }


    public function update_alarm_compliance_dynamically($params_obj){

        $job_details = $params_obj->job_details;

        $job_id = $job_details['jid'];
        $agency_id = $job_details['agency_id'];

        $compliance_id = $params_obj->compliance_id;
        $comp_notes = $params_obj->comp_notes;
        $prop_comp_cat_type = $params_obj->prop_comp_cat_type;

        // return variables
        $response = null;
        $httpcode = null;

        if( $compliance_id != '' ){

            $api_id = 3; // Property Tree

            // API request limit solution
            $req_limit_params = array(
                'api_id' => $api_id,
                'request_limit' => $this->request_limit,
                'sleep_interval_sec' => $this->sleep_interval_sec,
                'agency_id' => $agency_id
            );
            //$this->system_model->api_request_limit_counter_and_delay($req_limit_params);   
            
            $this->rate_limit_solution();

            // get access token        
            $pme_params = array(
                'agency_id' => $agency_id,
                'api_id' => $api_id
            );
            $access_token = $this->getAccessToken($pme_params); 

            // get agency preference
            $agency_pref = $this->get_agency_preference($agency_id);
            
            // HTTP headers
            $http_header = array(
                "Authorization: Bearer {$access_token}",
                "Content-Type: application/json"
            );        

            // Update Property Compliance Dynamically
            $end_points = "{$this->api_gateway}/residentialproperty/v1/PropertyCompliance/{$prop_comp_cat_type}/{$compliance_id}";            

            // payload
            // frequency
            $frequency_arr = array(
                'value' => 12,
                'period' => 'Months'
            );

            // reminder
            $reminder_arr = array(
                'value' => 11,
                'period' => 'Months'
            );
            
            $last_service_date = ( $this->system_model->isDateNotEmpty($job_details['jdate']) )?$job_details['jdate']:null;            
            $next_service_date = ( $this->system_model->isDateNotEmpty($job_details['jdate']) )?date('Y-m-d H:i:s',strtotime("{$job_details['jdate']} +365 days")):null;
            $expiry_date = ( $this->system_model->isDateNotEmpty($next_service_date) )?date('Y-m-d H:i:s',strtotime("{$next_service_date} -1 day")):null;
            
            // general details
            $general_details_arr = array(
                'category_id' => $agency_pref->prop_comp_cat,
                'managed_by' => 'Agent',
                'serviced_by_creditor_id' => $agency_pref->creditor,
                'pinned_to_entity_type' => 'Property',
                'frequency' => $frequency_arr,
                'reminder' => $reminder_arr,
                'expiry_date' => $expiry_date,
                'last_service_date' => $last_service_date,
                'next_service_date' => $next_service_date
            );    
            
            if( $comp_notes != '' ){

                $completed_date = ( $this->system_model->isDateNotEmpty($job_details['jdate']) )?$this->system_model->formatDate($job_details['jdate'],'d/m/Y'):null;
                $general_details_arr['notes'] = "{$job_details['ajt_type']} {$job_details['job_type']} completed on {$completed_date}";

            }            
            
            // Add Property Compliance
            if( $prop_comp_cat_type == 'SmokeAlarm' ){ // Smoke Alarm

                // get alarms
                $alarms_sql = $this->db->query("
                SELECT al_pwr.`alarm_pwr_source`
                FROM `alarm` AS al
                LEFT JOIN `alarm_pwr` AS al_pwr ON al.`alarm_power_id` = al_pwr.`alarm_pwr_id`
                WHERE al.`job_id` = {$job_id}
                AND al.`ts_discarded` = 0
                ");

                $smoke_alarm_type = null;
                $is_240v_count = 0;
                $is_not_240v_count = 0;
                $alarms_total_count = $alarms_sql->num_rows();

                foreach( $alarms_sql->result() as $alarms_row ){

                    if( $alarms_row->alarm_pwr_source == '240v' ){
                        $is_240v_count++;
                    }else{
                        $is_not_240v_count++;
                    }

                }

                // smoke alarm type
                if( $is_240v_count == $alarms_total_count ){
                    $smoke_alarm_type = 'HardWired';
                }else if( $is_not_240v_count == $alarms_total_count ){
                    $smoke_alarm_type = 'Battery';
                }else{
                    $smoke_alarm_type = 'Mixed';
                }

                // payload
                $curl_postfields = array(
                    'general_details' => $general_details_arr,
                    'smoke_alarm_type' => $smoke_alarm_type
                );  

            }else{ // general

                // payload
                $curl_postfields = $general_details_arr;  

            }

            // API call
            $curl = curl_init(); // start cURL

            // cURL options
            $curl_opt = array(
                CURLOPT_URL => $end_points,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => $http_header,
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS => json_encode($curl_postfields)
            );
            curl_setopt_array( $curl, $curl_opt );

            $response = curl_exec($curl); // cURL response
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE); // HTTP status code
            curl_close($curl); // close cURL  
            
            // capture json return
            $payload_final = ( count($curl_postfields) > 0 )?json_encode($curl_postfields):null;
            $http_header_final = ( count($http_header) > 0 )?json_encode($http_header):null;

            $api_data_params = array(
                'job_id' => $job_id,
                'api_endpoint' => $end_points,
                'http_header' => $http_header_final,
                'payload' => $payload_final,
                'http_status_code' => $httpcode,
                'raw_response' => $response
            );
            $this->system_model->capture_api_data($api_data_params);            

        }    
        
        return (object) [
            'json_decoded_response' => json_decode($response),
            'httpcode' => $httpcode
        ];

    }

    public function attach_document_to_compliance($params_obj){

        $job_id = $params_obj->job_id;
        $agency_id = $params_obj->agency_id;
        $property_compliance_id = $params_obj->property_compliance_id;
        $certificate_pdf = $params_obj->certificate_pdf;
        $bpay_ref_code = $params_obj->bpay_ref_code;

        $api_id = 3; // Property Tree

        // API request limit solution
        $req_limit_params = array(
            'api_id' => $api_id,
            'request_limit' => $this->request_limit,
            'sleep_interval_sec' => $this->sleep_interval_sec,
            'agency_id' => $agency_id
        );
        //$this->system_model->api_request_limit_counter_and_delay($req_limit_params);  
        
        $this->rate_limit_solution();

        // get access token        
        $pme_params = array(
            'agency_id' => $agency_id,
            'api_id' => $api_id
        );
        $access_token = $this->getAccessToken($pme_params); 

        // API call
        $curl = curl_init(); // start cURL

        // Attach Document to Compliance
        $end_points = "{$this->api_gateway}/residentialproperty/v1/Documents/Compliance/{$property_compliance_id}";

        // HTTP headers
        $http_header = array(
            "Authorization: Bearer {$access_token}"
        );

        // create temporary file
        $temp = tmpfile();
        fwrite($temp, $certificate_pdf);
        $certificate_path = stream_get_meta_data($temp)['uri'];

        // file name
        $fileName = "certificate_{$bpay_ref_code}_".date('YmdHis')."_info.pdf";

        // upload file using CurlFile
        $curl_postfields = array(
            'file' => new CurlFile($certificate_path,'application/pdf',$fileName)
        );   

        // cURL options
        $curl_opt = array(
            CURLOPT_URL => $end_points,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $http_header,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $curl_postfields
        );
        curl_setopt_array( $curl, $curl_opt );

        $response = curl_exec($curl); // cURL response
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE); // HTTP status code
        curl_close($curl); // close cURL

        // capture json return
        $payload_final = ( count($curl_postfields) > 0 )?json_encode($curl_postfields):null;
        $http_header_final = ( count($http_header) > 0 )?json_encode($http_header):null;

        $api_data_params = array(
            'job_id' => $job_id,
            'api_endpoint' => $end_points,
            'http_header' => $http_header_final,
            'payload' => $payload_final,
            'http_status_code' => $httpcode,
            'raw_response' => $response
        );
        $this->system_model->capture_api_data($api_data_params);

        return (object) [
            'json_decoded_response' => json_decode($response),
            'httpcode' => $httpcode
        ];

    }


    // Add Creditor Invoice With Attachment
    public function add_creditor_invoice_with_attachment($params_obj){

        $job_details = $params_obj->job_details;

        $job_id = $job_details['jid'];
        $agency_id = $job_details['agency_id'];
        $invoice_pdf = $params_obj->invoice_pdf;
        $bpay_ref_code = $params_obj->bpay_ref_code;
        $prop_management_id = $params_obj->prop_management_id;

        $api_id = 3; // Property Tree

        // API request limit solution
        $req_limit_params = array(
            'api_id' => $api_id,
            'request_limit' => $this->request_limit,
            'sleep_interval_sec' => $this->sleep_interval_sec,
            'agency_id' => $agency_id
        );
        //$this->system_model->api_request_limit_counter_and_delay($req_limit_params);   
        
        $this->rate_limit_solution();

        // get access token        
        $pme_params = array(
            'agency_id' => $agency_id,
            'api_id' => $api_id
        );
        $access_token = $this->getAccessToken($pme_params); 

        // get agency preference
        $agency_pref = $this->get_agency_preference($agency_id);

        // API call
        $curl = curl_init(); // start cURL

        // Add Creditor Invoice With Attachment
        $end_points = "{$this->api_gateway}/residentialproperty/v1/CreditorInvoices/withAttachment";

        // HTTP headers
        $http_header = array(
            "Authorization: Bearer {$access_token}"
        );

        // create temporary file
        $temp = tmpfile();
        fwrite($temp, $invoice_pdf);
        $invoice_path = stream_get_meta_data($temp)['uri'];

        // file name
        $fileName = "invoice_{$bpay_ref_code}_".date('YmdHis')."_info.pdf";

        $due_date = ( $this->system_model->isDateNotEmpty($job_details['jdate']) )?date('Y-m-d H:i:s',strtotime("{$job_details['jdate']} +30 days")):null;

        // company + service type name
        $invoice_desc = "{$this->config->item('company_name_short')} {$job_details['ajt_type']}";

        // invoice data
        $invoice_arr = array(
            "account_id" => $agency_pref->account,
            "creditor_id" => $agency_pref->creditor,
            "management_id" => $prop_management_id,
            "description" => $invoice_desc,
            "creditor_reference" => $bpay_ref_code,
            "amount" => $job_details['invoice_balance'],
            "due_date" => $due_date
        );                    

        // upload file using CurlFile
        $curl_postfields = array(
            'invoice_json' => json_encode($invoice_arr),
            'file' => new CurlFile($invoice_path,'application/pdf',$fileName)
        );   

        // cURL options
        $curl_opt = array(
            CURLOPT_URL => $end_points,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $http_header,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $curl_postfields
        );
        curl_setopt_array( $curl, $curl_opt );

        $response = curl_exec($curl); // cURL response
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE); // HTTP status code
        curl_close($curl); // close cURL

        // capture json return
        $payload_final = ( count($curl_postfields) > 0 )?json_encode($curl_postfields):null;
        $http_header_final = ( count($http_header) > 0 )?json_encode($http_header):null;

        $api_data_params = array(
            'job_id' => $job_id,
            'api_endpoint' => $end_points,
            'http_header' => $http_header_final,
            'payload' => $payload_final,
            'http_status_code' => $httpcode,
            'raw_response' => $response
        );
        $this->system_model->capture_api_data($api_data_params);

        return (object) [
            'json_decoded_response' => json_decode($response),
            'httpcode' => $httpcode
        ];

    }


    public function get_agents($agency_id){

        $api_id = 3; // Property Tree

        // API request limit solution
        $req_limit_params = array(
            'api_id' => $api_id,
            'request_limit' => $this->request_limit,
            'sleep_interval_sec' => $this->sleep_interval_sec,
            'agency_id' => $agency_id
        );
        //$this->system_model->api_request_limit_counter_and_delay($req_limit_params);        

        // get access token        
        $pme_params = array(
            'agency_id' => $agency_id,
            'api_id' => $api_id
        );
        $access_token = $this->getAccessToken($pme_params);             
        
        $end_points = "{$this->api_gateway}/residentialproperty/v1/Agents";

        // HTTP headers
        $http_header = array(
            "Authorization: Bearer {$access_token}"
        );

        // API call
        $curl = curl_init();
        
        $curl_opt = array(
            CURLOPT_URL => $end_points,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $http_header
        );
        
        curl_setopt_array( $curl, $curl_opt );

        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE); // HTTP status code

        curl_close($curl);

        return (object) [
            'json_decoded_response' => json_decode($response),
            'httpcode' => $httpcode
        ];
        
    }

    public function get_creditors($agency_id){

        $api_id = 3; // Property Tree

        // API request limit solution
        $req_limit_params = array(
            'api_id' => $api_id,
            'request_limit' => $this->request_limit,
            'sleep_interval_sec' => $this->sleep_interval_sec,
            'agency_id' => $agency_id
        );
        //$this->system_model->api_request_limit_counter_and_delay($req_limit_params);        

        // get access token        
        $pme_params = array(
            'agency_id' => $agency_id,
            'api_id' => $api_id
        );
        $access_token = $this->getAccessToken($pme_params);             
        
        $end_points = "{$this->api_gateway}/residentialproperty/v1/Creditors";

        // HTTP headers
        $http_header = array(
            "Authorization: Bearer {$access_token}"
        );

        // API call
        $curl = curl_init();
        
        $curl_opt = array(
            CURLOPT_URL => $end_points,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $http_header
        );
        
        curl_setopt_array( $curl, $curl_opt );

        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE); // HTTP status code

        curl_close($curl);

        return (object) [
            'json_decoded_response' => json_decode($response),
            'httpcode' => $httpcode
        ];
        
    }

    public function get_creditor($params_obj){

        $agency_id = $params_obj->agency_id;
        $creditor_id = $params_obj->creditor_id; 

        $api_id = 3; // Property Tree

        // API request limit solution
        $req_limit_params = array(
            'api_id' => $api_id,
            'request_limit' => $this->request_limit,
            'sleep_interval_sec' => $this->sleep_interval_sec,
            'agency_id' => $agency_id
        );
        //$this->system_model->api_request_limit_counter_and_delay($req_limit_params);        

        // get access token        
        $pme_params = array(
            'agency_id' => $agency_id,
            'api_id' => $api_id
        );
        $access_token = $this->getAccessToken($pme_params);             
        
        $end_points = "{$this->api_gateway}/residentialproperty/v1/Creditors/{$creditor_id}";

        // HTTP headers
        $http_header = array(
            "Authorization: Bearer {$access_token}"
        );

        // API call
        $curl = curl_init();
        
        $curl_opt = array(
            CURLOPT_URL => $end_points,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $http_header
        );
        
        curl_setopt_array( $curl, $curl_opt );

        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE); // HTTP status code

        curl_close($curl);

        return (object) [
            'json_decoded_response' => json_decode($response),
            'httpcode' => $httpcode
        ];
        
    }

    public function get_accounts($agency_id){

        $api_id = 3; // Property Tree

        // API request limit solution
        $req_limit_params = array(
            'api_id' => $api_id,
            'request_limit' => $this->request_limit,
            'sleep_interval_sec' => $this->sleep_interval_sec,
            'agency_id' => $agency_id
        );
        //$this->system_model->api_request_limit_counter_and_delay($req_limit_params);        

        // get access token        
        $pme_params = array(
            'agency_id' => $agency_id,
            'api_id' => $api_id
        );
        $access_token = $this->getAccessToken($pme_params);             
        
        $end_points = "{$this->api_gateway}/residentialproperty/v1/Accounts";

        // HTTP headers
        $http_header = array(
            "Authorization: Bearer {$access_token}"
        );

        // API call
        $curl = curl_init();
        
        $curl_opt = array(
            CURLOPT_URL => $end_points,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $http_header
        );
        
        curl_setopt_array( $curl, $curl_opt );

        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE); // HTTP status code

        curl_close($curl);

        return (object) [
            'json_decoded_response' => json_decode($response),
            'httpcode' => $httpcode
        ];
        
    }


    public function property_compliance_categories($agency_id){

        $api_id = 3; // Property Tree

        // API request limit solution
        $req_limit_params = array(
            'api_id' => $api_id,
            'request_limit' => $this->request_limit,
            'sleep_interval_sec' => $this->sleep_interval_sec,
            'agency_id' => $agency_id
        );
        //$this->system_model->api_request_limit_counter_and_delay($req_limit_params);        

        // get access token        
        $pme_params = array(
            'agency_id' => $agency_id,
            'api_id' => $api_id
        );
        $access_token = $this->getAccessToken($pme_params);             
        
        $end_points = "{$this->api_gateway}/residentialproperty/v1/PropertyComplianceCategory";

        // HTTP headers
        $http_header = array(
            "Authorization: Bearer {$access_token}"
        );

        // API call
        $curl = curl_init();
        
        $curl_opt = array(
            CURLOPT_URL => $end_points,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $http_header
        );
        
        curl_setopt_array( $curl, $curl_opt );

        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE); // HTTP status code

        curl_close($curl);

        return (object) [
            'json_decoded_response' => json_decode($response),
            'httpcode' => $httpcode
        ];
        
    }

    
    public function get_property_tree_agent_by_id($params) {

        $property_id = $params['property_id'];
        $agent_id = $params['agent_id'];
        
        $api_id = 3; // Property Tree 

        if( $property_id > 0 ){            

            // get agency ID from property 
            $prop_sql = $this->db->query("
            SELECT `agency_id`
            FROM `property`
            WHERE `property_id` = {$property_id}
            ");
            $prop_row = $prop_sql->row();
            $agency_id = $prop_row->agency_id;

            // API request limit solution
            $req_limit_params = array(
                'api_id' => $api_id,
                'request_limit' => $this->request_limit,
                'sleep_interval_sec' => $this->sleep_interval_sec,
                'agency_id' => $agency_id
            );
            //$this->system_model->api_request_limit_counter_and_delay($req_limit_params);

            // get access token
            $pme_params = array(
                'agency_id' => $agency_id,
                'api_id' => $api_id
            );
            $access_token = $this->getAccessToken($pme_params);   
            
            //echo "api_prop_json: <br />";
            $end_points = "{$this->api_gateway}/residentialproperty/v1/Agents/{$agent_id}";
            //echo "<br />";

            $curl = curl_init();

            // HTTP headers
            $http_header = array(
                "Authorization: Bearer {$access_token}",
                "Content-Type: application/json"
            );

            // API call
            $curl_opt = array(
                CURLOPT_URL => $end_points,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => $http_header
            );

            curl_setopt_array( $curl, $curl_opt );

            $response = curl_exec($curl);
            $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            /*
            echo "response: <br />";
            echo "<pre>";
            print_r($response);
            echo "</pre>";
            */
            curl_close($curl);

            return array(
                'raw_response' => $response,
                'json_decoded_response' => json_decode($response),
                'http_status_code' => $responseCode
            );

        }        

    }


    public function get_all_compliance($agency_id){

        $api_id = 3; // Property Tree

        // API request limit solution
        $req_limit_params = array(
            'api_id' => $api_id,
            'request_limit' => $this->request_limit,
            'sleep_interval_sec' => $this->sleep_interval_sec,
            'agency_id' => $agency_id
        );
        //$this->system_model->api_request_limit_counter_and_delay($req_limit_params);        

        // get access token        
        $pme_params = array(
            'agency_id' => $agency_id,
            'api_id' => $api_id
        );
        $access_token = $this->getAccessToken($pme_params);             
        
        $end_points = "{$this->api_gateway}/residentialproperty/v1/PropertyCompliance";

        // HTTP headers
        $http_header = array(
            "Authorization: Bearer {$access_token}"
        );

        // API call
        $curl = curl_init();
        
        $curl_opt = array(
            CURLOPT_URL => $end_points,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $http_header
        );
        
        curl_setopt_array( $curl, $curl_opt );

        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE); // HTTP status code

        curl_close($curl);

        return (object) [
            'json_decoded_response' => json_decode($response),
            'httpcode' => $httpcode
        ];
        
    }


    public function complete_compliance($params_obj){

        $job_id = $params_obj->job_id;        
        $agency_id = $params_obj->agency_id;

        $compliance_id = $params_obj->compliance_id;

        $api_id = 3; // Property Tree

        $this->rate_limit_solution();

        // get access token        
        $pme_params = array(
            'agency_id' => $agency_id,
            'api_id' => $api_id
        );
        $access_token = $this->getAccessToken($pme_params);   
        
        $end_points = "{$this->api_gateway}/residentialproperty/v1/PropertyCompliance/{$compliance_id}/complete";        

        // HTTP headers
        $http_header = array(
            "Authorization: Bearer {$access_token}"
        );

        // API call
        $curl = curl_init(); // start cURL

        // cURL options
        $curl_opt = array(
            CURLOPT_URL => $end_points,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $http_header,
            CURLOPT_POST => true
        );
        curl_setopt_array( $curl, $curl_opt );

        $response = curl_exec($curl); // cURL response
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE); // HTTP status code
        curl_close($curl); // close cURL  
        
        // capture json return
        $http_header_final = ( count($http_header) > 0 )?json_encode($http_header):null;

        $api_data_params = array(
            'job_id' => $job_id,
            'api_endpoint' => $end_points,
            'http_header' => $http_header_final,
            'http_status_code' => $httpcode,
            'raw_response' => $response
        );
        $this->system_model->capture_api_data($api_data_params);

        return (object) [
            'json_decoded_response' => json_decode($response),
            'httpcode' => $httpcode
        ];
        
    }

    public function search_compliance($params_obj){

        $job_details = $params_obj->job_details;

        $job_id = $job_details['jid'];
        $agency_id = $job_details['agency_id'];

        $prop_management_id = $params_obj->prop_management_id;

        $api_id = 3; // Property Tree

        // API request limit solution
        $req_limit_params = array(
            'api_id' => $api_id,
            'request_limit' => $this->request_limit,
            'sleep_interval_sec' => $this->sleep_interval_sec,
            'agency_id' => $agency_id
        );
        //$this->system_model->api_request_limit_counter_and_delay($req_limit_params);  
        
        $this->rate_limit_solution();

        // get access token        
        $pme_params = array(
            'agency_id' => $agency_id,
            'api_id' => $api_id
        );
        $access_token = $this->getAccessToken($pme_params); 

        // get agency preference
        $agency_pref = $this->get_agency_preference($agency_id);
        
        // HTTP headers
        $http_header = array(
            "Authorization: Bearer {$access_token}",
            "Content-Type: application/json"
        );        

        // search compliance
        $end_points = "{$this->api_gateway}/residentialproperty/v1/PropertyCompliance/search?PageNo=1&PageSize=1";        
        
        // general details
        $curl_postfields = array(
            'management_id' => $prop_management_id,
            'category_id' => $agency_pref->prop_comp_cat,
            'status' => 'Open'
        );        

        // API call
        $curl = curl_init(); // start cURL

        // cURL options
        $curl_opt = array(
            CURLOPT_URL => $end_points,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $http_header,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($curl_postfields)
        );
        curl_setopt_array( $curl, $curl_opt );

        $response = curl_exec($curl); // cURL response
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE); // HTTP status code
        curl_close($curl); // close cURL  
        
        // capture json return
        $payload_final = ( count($curl_postfields) > 0 )?json_encode($curl_postfields):null;
        $http_header_final = ( count($http_header) > 0 )?json_encode($http_header):null;

        $api_data_params = array(
            'job_id' => $job_id,
            'api_endpoint' => $end_points,
            'http_header' => $http_header_final,
            'payload' => $payload_final,
            'http_status_code' => $httpcode,
            'raw_response' => $response
        );
        $this->system_model->capture_api_data($api_data_params);

        return (object) [
            'json_decoded_response' => json_decode($response),
            'httpcode' => $httpcode
        ];

    }

    public function get_compliance_documents($params_obj){

        $agency_id = $params_obj->agency_id;
        $compliance_id = $params_obj->compliance_id;

        // return variables
        $response = null;
        $httpcode = null;

        if( $compliance_id != '' ){

            $api_id = 3; // Property Tree

            // API request limit solution
            $req_limit_params = array(
                'api_id' => $api_id,
                'request_limit' => $this->request_limit,
                'sleep_interval_sec' => $this->sleep_interval_sec,
                'agency_id' => $agency_id
            );
            //$this->system_model->api_request_limit_counter_and_delay($req_limit_params);  
            
            $this->rate_limit_solution();

            // get access token        
            $pme_params = array(
                'agency_id' => $agency_id,
                'api_id' => $api_id
            );
            $access_token = $this->getAccessToken($pme_params);             
            
            $end_points = "{$this->api_gateway}/residentialproperty/v1/Documents/Compliance/{$compliance_id}";

            // HTTP headers
            $http_header = array(
                "Authorization: Bearer {$access_token}"
            );

            // API call
            $curl = curl_init();
            
            $curl_opt = array(
                CURLOPT_URL => $end_points,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => $http_header
            );
            
            curl_setopt_array( $curl, $curl_opt );

            $response = curl_exec($curl);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE); // HTTP status code

            curl_close($curl);            

        }
        
        return (object) [
            'json_decoded_response' => json_decode($response),
            'httpcode' => $httpcode
        ];
        
    }

    // mark job as certificate of compliance API uploaded and insert log
    public function mark_job_as_compliance_and_insert_log($params_obj){

        $api = 3; // Property Tree

        $job_details = $params_obj->job_details;
        $job_id = $job_details['jid'];

        // for pdf url
        $encrypt = rawurlencode(HashEncryption::encodeString($job_id));
        $baseUrl = $_SERVER["SERVER_NAME"];
        if(isset($_SERVER['HTTPS'])){
            $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
        } else{
            $protocol = 'http';
        }                

        // check api_job_data again if data exist in a certain job
        $api_job_data_sql = $this->db->query("
        SELECT COUNT(`id`) AS ajd_count
        FROM `api_job_data`
        WHERE `crm_job_id` = {$job_id}
        ");
        $ajd_count = $api_job_data_sql->row()->ajd_count;

        // mark as certificate uploaded
        if( $ajd_count > 0 ){ // already exist

            // update
            $this->db->query("
            UPDATE `api_job_data`
            SET `api_cert_uploaded` = 1
            WHERE `crm_job_id` = {$job_id}
            AND  `api` = {$api}
            ");                                

        }else{

            // insert
            $data = array(
                'crm_job_id' => $job_id,
                'api' => $api,
                'api_cert_uploaded' => 1
            );                                
            $this->db->insert('api_job_data', $data);

        }   

        // insert job log
        $log_details = "<a href='".$protocol."://{$baseUrl}/pdf/certificates/{$encrypt}'>Cerficate</a> has been uploaded to PropertyTree API";
        $log_params = array(
            'title' => 91,  // PropertyTree API
            'details' => $log_details,
            'display_in_vjd' => 1,
            'property_id' => $job_details['property_id'],
            'job_id' => $job_id,
            'agency_id' => $job_details['agency_id']
        );

        // if not CRON, user logged
        if($this->session->staff_id !='' ){
            $append_jlval = $this->session->staff_id;
            $log_params['created_by_staff'] = $append_jlval;
        }else{
            $append_jlval = 1;
            $log_params['auto_process'] = $append_jlval;
        }

        $this->system_model->insert_log($log_params);        

    }

    public function rate_limit_solution(){
        
        // Increment request counter
        $this->request_counter++;

        // Check if 1 minute has passed
        if( time() - $this->start_time >= 60 ){

            // Reset counter and start time
            $this->request_counter = 0;
            $this->start_time = time();

        }

        // Check if reached rate limit
        if( $this->request_counter >= $this->request_limit ){

            // Sleep for the remaining time in the minute
            $remaining_time = 60 - (time() - $this->start_time);

            sleep($remaining_time);

            // Reset counter and start time
            $this->request_counter = 0;
            $this->start_time = time();

        }

    }

}
