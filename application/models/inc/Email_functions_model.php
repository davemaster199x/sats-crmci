<?php

class Email_functions_model extends CI_Model
{

    private $image_fg_id = 40; // "Image" Franchise Groups

    public function __construct()
    {
        ini_set('max_execution_time', 600); 

		$this->load->database();
		$this->load->model('properties_model');
        $this->load->model('jobs_model');
        $this->load->library('email');
        $this->load->helper('email_helper');
        $this->load->model('/inc/job_functions_model');
        $this->load->model('/inc/alarm_functions_model');
        $this->load->model('/inc/functions_model');
        $this->load->model('/inc/pdf_template');
        
        $this->load->library('HashEncryption');
    }


    /**
     * @param int|null $id
     * @param int|null $country_id
     * @param bool|null $is_getList
     * @return array
     */
    public function batchSendInvoicesCertificates(?int $id = null, ?int $country_id = null, ?bool $is_getList = false)
	{
        $sent_count = 0;
        $country_id = ($country_id!="") ? $country_id : $this->config->item('country');

        if ($id !== null) {
            $sql_str = "SELECT 
                    j.id, j.job_type, DATE_FORMAT(j.date,'%d/%m/%Y') AS job_date,
                    DATE_FORMAT(j.date, '%d/%m/%Y') AS date,
                    j.job_price, j.price_used, 
                    j.status, p.address_1, p.address_2, p.address_3, 
                    p.state, p.postcode, j.id, p.property_id,
                    a.agency_id, a.send_emails, a.account_emails, a.send_combined_invoice,
                    DATE_FORMAT(DATE_ADD(j.date, INTERVAL 1 YEAR), '%d/%m/%Y') AS retest_date,
                    j.ss_location,
                    j.ss_quantity,
                    j.assigned_tech,
                    sa.FirstName, 
                    sa.LastName,
                    j.work_order,
                    j.comments, 
                    j.retest_interval, 
                    j.auto_renew, 
                    j.time_of_day, 
                    p.tenant_firstname1, 
                    p.tenant_lastname1, 
                    p.tenant_ph1, 
                    j.tech_comments, 
                    p.tenant_firstname2, 
                    p.tenant_lastname2, 
                    p.tenant_ph2, 
                    a.address_1 AS agent_address_1, 
                    a.address_2 AS agent_address_2, 
                    a.address_3 AS agent_address_3, 
                    a.phone AS agent_phone, 
                    a.state AS agent_state, 
                    a.postcode AS agent_postcode, 
                    p.price, 
                    j.ts_noshow, 
                    DATE_FORMAT(
                    j.client_emailed, '%e/%m/%Y @ %r'
                    ) AS LastSent, 
                    ts_doorknock, 
                    p.agency_deleted, 
                    j.key_access_required, 
                    p.tenant_email1, 
                    p.tenant_email2, 
                    p.tenant_mob1, 
                    p.tenant_mob2, 
                    DATE_FORMAT(
                    j.entry_notice_emailed, '%d/%m/%Y @ %r'
                    ) AS EntryNoticeLastSent, 
                    sa.ContactNumber, 
                    DATE_FORMAT(j.date, '%W') as booking_date_name, 
                    DATE_FORMAT(j.date, '%d') AS booking_date_day, 
                    DATE_FORMAT(j.date, '%m') AS booking_date_month, 
                    DATE_FORMAT(j.date, '%Y') AS booking_date_year, 
                    a.agency_emails, 
                    a.send_entry_notice, 
                    j.tmh_id, 
                    j.ts_db_reading, 
                    p.key_number, 
                    j.price_reason, 
                    j.price_detail, 
                    j.service AS jservice, 
                    a.`country_id`, 
                    j.`ps_qld_leg_num_alarm`, 
                    p.`qld_new_leg_alarm_num`, 
                    a.`display_bpay`, 
                    j.`show_as_paid`, 
                    j.`invoice_balance`, 
                    j.`invoice_payments`, 
                    a.`allow_upfront_billing`, 
                    j.`date` AS jdate, 
                    p.`prop_upgraded_to_ic_sa`, 
                    p.`landlord_email`, 
                    p.`property_managers_id`, 
                    a.`allow_indiv_pm_email_cc`, 
                    a.`invoice_pm_only`, 
                    j.`invoice_amount`, 
                    p.`pm_id_new`, 
                    a.`franchise_groups_id`, 
                    a.`agency_name`, 
                    p.`landlord_firstname`, 
                    p.`landlord_lastname`, 
                    a.`exclude_free_invoices`, 
                    j.`prop_comp_with_state_leg`, 
                    p.address_1 AS prop_street_num, 
                    p.address_2 AS prop_street_name, 
                    p.address_3 AS prop_suburb, 
                    p.send_to_email_not_api, 
                    aad.is_invoice, 
                    aad.is_certificate 
                FROM 
                    jobs AS j 
                    LEFT JOIN property AS p ON j.property_id = p.property_id 
                    LEFT JOIN agency AS a ON p.agency_id = a.agency_id 
                    LEFT JOIN staff_accounts AS sa ON j.assigned_tech = sa.StaffID 
                    LEFT JOIN agency_api_tokens AS aat ON (
                    a.`agency_id` = aat.`agency_id` 
                    AND aat.`api_id` = 1
                    ) 
                    LEFT JOIN api_property_data AS apd ON j.property_id = apd.crm_prop_id 
                    LEFT JOIN agency_api_documents AS aad ON aad.agency_id = a.agency_id 
                    LEFT JOIN `propertytree_agency_preference` AS pt_agen_pref ON ( a.`agency_id` = pt_agen_pref.`agency_id` AND pt_agen_pref.`active` = 1 )
                    LEFT JOIN `console_properties` AS `cp` ON (
                    p.`property_id` = cp.`crm_prop_id` 
                    AND cp.`active` = 1
                    ) 
                    LEFT JOIN `api_job_data` AS `ajd` ON (
                    j.`id` = ajd.`crm_job_id` 
                    AND ajd.`api` = 5
                    ) 
                    LEFT JOIN `api_property_data` AS `apd_pme` ON (
                    p.`property_id` = apd_pme.`crm_prop_id` 
                    AND apd_pme.`api` = 1
                    ) 
                    LEFT JOIN `api_property_data` AS `apd_palace` ON (
                    p.`property_id` = apd_palace.`crm_prop_id` 
                    AND apd_palace.`api` = 4
                    ) 
                    LEFT JOIN `api_property_data` AS `apd_pt` ON (
                    p.`property_id` = apd_pt.`crm_prop_id` 
                    AND apd_pt.`api` = 3
                    )
                WHERE j.status = 'Merged Certificates' 
                    AND a.account_emails LIKE '%@%' 
                    AND j.client_emailed IS NULL 
                    AND a.`country_id` = {$country_id}
                    AND p.`deleted` = 0 
                    AND (
                        p.`is_nlm` = 0 OR p.`is_nlm` IS NULL
                    ) 
                    AND a.`status` = 'active' 
                    AND a.`deleted` = 0 
                    AND j.`del_job` = 0
                    AND (
                            (
                                (
                                    apd_pme.`api_prop_id` IS NOT NULL 
                                    AND `apd_pme`.`api_prop_id` != '' 
                                    AND apd_pme.`api` = 1
                                ) 
                                AND (
                                    `a`.`pme_supplier_id` IS NOT NULL 
                                    AND `a`.`pme_supplier_id` != ''
                                ) 
                                AND (
                                    `aat`.`connection_date` IS NOT NULL 
                                    AND aat.connection_date != ''
                                )
                            ) 
                            OR (
                                (
                                    apd_palace.`api_prop_id` IS NOT NULL 
                                    AND `apd_palace`.`api_prop_id` != ''
                                    AND apd_palace.`api` = 4
                                ) 
                                AND (
                                    `a`.`palace_supplier_id` IS NOT NULL 
                                    AND `a`.`palace_supplier_id` != ''
                                ) 
                                AND (
                                    `a`.`palace_agent_id` IS NOT NULL 
                                    AND `a`.`palace_agent_id` != ''
                                ) 
                                AND (
                                    `a`.`palace_diary_id` IS NOT NULL 
                                    AND a.palace_diary_id != ''
                                )
                            ) OR(
                                (
                                    apd_pt.`api_prop_id` IS NOT NULL AND 
                                    apd_pt.`api_prop_id` != '' AND 
                                    apd_pt.`api` = 3
                                ) AND                                
                                (  pt_agen_pref.creditor IS NOT NULL AND pt_agen_pref.creditor != '' ) AND
                                (  pt_agen_pref.account IS NOT NULL AND pt_agen_pref.account != '' ) AND
                                (  pt_agen_pref.prop_comp_cat IS NOT NULL AND pt_agen_pref.prop_comp_cat != '' )
                            ) 
                    OR (
                        cp.`crm_prop_id` IS NOT NULL 
                        AND cp.`crm_prop_id` != ''
                    )
                    ) 
                    AND `p`.`send_to_email_not_api` = 0 
                    AND (
                        `j`.`prop_comp_with_state_leg` IS NULL 
                        OR j.`prop_comp_with_state_leg` = 1
                    ) AND j.id = {$id}
                ";

        } else {

            $sql_str = "SELECT j.id, j.job_type, DATE_FORMAT(j.date,'%d/%m/%Y') AS job_date,
                        DATE_FORMAT(j.date, '%d/%m/%Y') AS date,
                        j.job_price, j.price_used, 
                        j.status, p.address_1, p.address_2, p.address_3, 
                        p.state, p.postcode, j.id, p.property_id,
                        a.agency_id, a.send_emails, a.account_emails, a.send_combined_invoice,
                        DATE_FORMAT(DATE_ADD(j.date, INTERVAL 1 YEAR), '%d/%m/%Y') AS retest_date,
                        j.ss_location,
                        j.ss_quantity,
                        j.assigned_tech,
                        sa.FirstName, 
                        sa.LastName,
                        j.work_order,
    
                        j.comments,
                        j.retest_interval,
                        j.auto_renew,
                        j.time_of_day,
                        p.tenant_firstname1,
                        p.tenant_lastname1,
                        p.tenant_ph1,
                        j.tech_comments,
                        p.tenant_firstname2,
                        p.tenant_lastname2,
                        p.tenant_ph2,
                        a.address_1 AS agent_address_1, 
                        a.address_2 AS agent_address_2, 
                        a.address_3 AS agent_address_3,
                        a.phone AS agent_phone,
                        a.state AS agent_state,
                        a.postcode  AS agent_postcode,
                        p.price,
                        j.ts_noshow,
                        DATE_FORMAT(j.client_emailed, '%e/%m/%Y @ %r' ) AS LastSent,                    
                        ts_doorknock,
                        p.agency_deleted,
                        j.key_access_required, 
                        p.tenant_email1, 
                        p.tenant_email2, 
                        p.tenant_mob1, 
                        p.tenant_mob2,
                        DATE_FORMAT(j.entry_notice_emailed, '%d/%m/%Y @ %r') AS EntryNoticeLastSent, sa.ContactNumber, 
                        DATE_FORMAT(j.date, '%W') as booking_date_name,
                        DATE_FORMAT(j.date, '%d') AS booking_date_day,
                        DATE_FORMAT(j.date, '%m') AS booking_date_month,
                        DATE_FORMAT(j.date, '%Y') AS booking_date_year,
                        a.agency_emails,
                        a.send_entry_notice,
                        j.tmh_id,
                        j.ts_db_reading, 
                        p.key_number, 
                        j.price_reason, 
                        j.price_detail, 
                        j.service AS jservice,              
                        a.`country_id`,
                        j.`ps_qld_leg_num_alarm`,
                        p.`qld_new_leg_alarm_num`,
                        a.`display_bpay`,
                        j.`show_as_paid`,
                        j.`invoice_balance`,
                        j.`invoice_payments`,
                        a.`allow_upfront_billing`,
                        j.`date` AS jdate,
                        p.`prop_upgraded_to_ic_sa`,
    
                        p.`landlord_email`,
                        p.`property_managers_id`,
                        a.`allow_indiv_pm_email_cc`,
                        a.`invoice_pm_only`,
                        j.`invoice_amount`,
                        p.`pm_id_new`,
                        a.`franchise_groups_id`,
                        a.`agency_name`,
                        p.`landlord_firstname`,
                        p.`landlord_lastname`,
                        a.`exclude_free_invoices`,
                        j.`prop_comp_with_state_leg`,
                        p.address_1 AS prop_street_num, 
                        p.address_2 AS prop_street_name, 
                        p.address_3 AS prop_suburb
    
                        FROM jobs AS j
                        LEFT JOIN property AS p ON j.property_id = p.property_id
                        LEFT JOIN agency AS a ON p.agency_id = a.agency_id
                        LEFT JOIN staff_accounts AS sa ON j.assigned_tech = sa.StaffID
                        LEFT JOIN agency_api_tokens AS aat ON (a.`agency_id` = aat.`agency_id` AND aat.`api_id` = 1) 
                        LEFT JOIN api_property_data AS apd ON j.property_id = apd.crm_prop_id
                        LEFT JOIN `console_properties` AS cp ON ( p.`property_id` = cp.`crm_prop_id` AND cp.`active` = 1 )
                        LEFT JOIN `propertytree_agency_preference` AS pt_agen_pref ON ( a.`agency_id` = pt_agen_pref.`agency_id` AND pt_agen_pref.`active` = 1 )
                        LEFT JOIN `agency_api_documents` AS agen_api_doc ON a.`agency_id` = agen_api_doc.`agency_id`
                        WHERE j.status = 'Merged Certificates'                    
                        AND a.account_emails LIKE '%@%'
                        AND j.client_emailed IS NULL
                        AND a.`country_id` = {$country_id}
                        AND p.`deleted` =0
                        AND ( p.`is_nlm` = 0 OR p.`is_nlm` IS NULL )
                        AND a.`status` = 'active'
                        AND a.`deleted` = 0
                        AND j.`del_job` = 0
                        AND(
                            NOT ( 
                                (apd.`api_prop_id` IS NOT NULL AND apd.`api_prop_id` != '') AND 
                                apd.`api` = 1 AND
                                (a.pme_supplier_id IS NOT NULL AND a.pme_supplier_id != '') AND 
                                (aat.connection_date IS NOT NULL AND aat.connection_date != '') AND
                                p.`send_to_email_not_api` = 0 AND 
                                ( j.`prop_comp_with_state_leg` IS NULL OR j.`prop_comp_with_state_leg` = 1 )
                            ) AND
                            NOT ( 
                                (apd.`api_prop_id` IS NOT NULL AND apd.`api_prop_id` != '') AND 
                                apd.`api` = 4 AND
                                (a.palace_supplier_id IS NOT NULL AND a.palace_supplier_id != '') AND 
                                (a.palace_agent_id IS NOT NULL AND a.palace_agent_id != '') AND 
                                (a.palace_diary_id IS NOT NULL AND a.palace_diary_id != '') AND
                                p.`send_to_email_not_api` = 0 AND 
                                ( j.`prop_comp_with_state_leg` IS NULL OR j.`prop_comp_with_state_leg` = 1 )
                            ) AND 
                            NOT(
                                (
                                    apd.`api_prop_id` IS NOT NULL AND 
                                    apd.`api_prop_id` != '' AND 
                                    apd.`api` = 3
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
                            NOT ( 
                                (
                                    cp.`crm_prop_id` IS NOT NULL AND 
                                    cp.`crm_prop_id` != ''  
                                ) AND                            
                                (   
                                    j.`prop_comp_with_state_leg` IS NULL OR 
                                    j.`prop_comp_with_state_leg` = 1 
                                ) AND
                                p.`send_to_email_not_api` = 0
                            ) OR p.`send_to_email_not_api` = 1
                        )
                        ";
        }
		
		#Get jobs to send
		$query = $this->db->query($sql_str);
        
        if ($is_getList) {
            return $query->result_array();
        }
			
		#Send emails
        $error_prop = [];
		foreach($query->result_array() as $job)
		{
			unset($jemail);
			$jemail = array();

            // check if agency has maintenance program
            $jemail = $this->processMergedSendToEmails($job['agency_id'],$job['account_emails'],$job);

            // get landlord email
            $prop_sql = $this->db->query("
                SELECT `landlord_email` 
                FROM `property` 
                WHERE `property_id` = {$job['property_id']}                
            ");
            $prop_row = $prop_sql->row();
            $landlord_email = $prop_row->landlord_email;

            // if FG is private and landlord email is empty                
            if( $job['franchise_groups_id'] == 10 && $landlord_email == '' ){

                // do not send email but return the property
                $error_prop[] = "{$job['prop_street_num']} {$job['prop_street_name']}, {$job['prop_suburb']}";

            }else { // default
                if ($this->sendInvoiceCertEmail($job, $jemail, $country_id, 0)) {
                    $sent_count++;
                }
            }
		}

        return [
            'sent_count' => $sent_count,
            'error_prop' => $error_prop
        ];
			
    }
    

    // can be used to send email invoice per job, using same job query from bulk sending invoice
    public function send_invoice_email($params){

        $job_id = $params['job_id'];
        $country_id = $this->config->item('country');

        if( $job_id > 0 ){

            $sql_str = "
            SELECT j.id, j.job_type, DATE_FORMAT(j.date,'%d/%m/%Y') AS job_date,
            DATE_FORMAT(j.date, '%d/%m/%Y') AS date,
            j.job_price, j.price_used, 
            j.status, p.address_1, p.address_2, p.address_3, 
            p.state, p.postcode, j.id, p.property_id,
            a.agency_id, a.send_emails, a.account_emails, a.send_combined_invoice,
            DATE_FORMAT(DATE_ADD(j.date, INTERVAL 1 YEAR), '%d/%m/%Y') AS retest_date,
            j.ss_location,
            j.ss_quantity,
            j.assigned_tech,
            sa.FirstName, 
            sa.LastName,
            j.work_order,

            j.comments,
            j.retest_interval,
            j.auto_renew,
            j.time_of_day,
            p.tenant_firstname1,
            p.tenant_lastname1,
            p.tenant_ph1,
            j.tech_comments,
            p.tenant_firstname2,
            p.tenant_lastname2,
            p.tenant_ph2,
            a.address_1 AS agent_address_1, 
            a.address_2 AS agent_address_2, 
            a.address_3 AS agent_address_3,
            a.phone AS agent_phone,
            a.state AS agent_state,
            a.postcode  AS agent_postcode,
            p.price,
            j.ts_noshow,
            DATE_FORMAT(j.client_emailed, '%e/%m/%Y @ %r' ) AS LastSent,            
            ts_doorknock,
            p.agency_deleted,
            j.key_access_required, 
            p.tenant_email1, 
            p.tenant_email2, 
            p.tenant_mob1, 
            p.tenant_mob2,
            DATE_FORMAT(j.entry_notice_emailed, '%d/%m/%Y @ %r') AS EntryNoticeLastSent, sa.ContactNumber, 
            DATE_FORMAT(j.date, '%W') as booking_date_name,
            DATE_FORMAT(j.date, '%d') AS booking_date_day,
            DATE_FORMAT(j.date, '%m') AS booking_date_month,
            DATE_FORMAT(j.date, '%Y') AS booking_date_year,
            a.agency_emails,
            a.send_entry_notice,
            j.tmh_id,
            j.ts_db_reading, 
            p.key_number, 
            j.price_reason, 
            j.price_detail, 
            j.service AS jservice,              
            a.`country_id`,
            j.`ps_qld_leg_num_alarm`,
            p.`qld_new_leg_alarm_num`,
            a.`display_bpay`,
            j.`show_as_paid`,
            j.`invoice_balance`,
            j.`invoice_payments`,
            a.`allow_upfront_billing`,
            j.`date` AS jdate,
            p.`prop_upgraded_to_ic_sa`,

            p.`landlord_email`,
            p.`property_managers_id`,
            a.`allow_indiv_pm_email_cc`,
            a.`invoice_pm_only`,
            j.`invoice_amount`,
            p.`pm_id_new`,
            a.`franchise_groups_id`,
            a.`agency_name`,
            p.`landlord_firstname`,
            p.`landlord_lastname`
            FROM jobs AS j
            LEFT JOIN property AS p ON j.property_id = p.property_id
            LEFT JOIN agency AS a ON p.agency_id = a.agency_id
            LEFT JOIN staff_accounts AS sa ON j.assigned_tech = sa.StaffID            
            WHERE j.`id` = {$job_id}
            ";                        
            $query = $this->db->query($sql_str);
            $job = $query->row_array();

            if( isset($job) ){

                // clear email array
                unset($jemail);
                $jemail = array();
                $jemail = [];
    
                // check if agency has maintenance program
                $jemail = $this->processMergedSendToEmails($job['agency_id'],$job['account_emails'],$job);
                    
                if( $this->sendInvoiceCertEmail($job, $jemail, $country_id,0) ){
                    return true;
                }else{
                    return false;
                }

            }else{
                return false;
            }            

        }else{
            return false;
        }
        

    }


    /**
     * Check if agency has maintenance program
     * $params agency_id, agency account_emails, job query
     */
	function processMergedSendToEmails_old($agency_id,$agency_account_emails,$job){
	
		unset($jemail);
		$jemail = array();
		
		// check if agency has maintenance program
		$to_email = $agency_account_emails;
        $agency_has_mm = $this->system_model->check_agency_has_mm($agency_id);
		
		if( $agency_has_mm == true ){ // Maintenance Program Found
			$to_email = make_email('maintenance_manager');
		}else{
			if( $job['invoice_pm_only'] == 1 ){
                // only get sent to PM email
                
                //added by gherx > add catch to send to accounts email if PM is empty or not assigned to a property
                if($job['pm_id_new']=="" OR $job['pm_id_new']===NULL){
                    $to_email = $agency_account_emails;
                }
                
			}else{
				$to_email = $agency_account_emails;
			}		
		}
		
		if( $to_email !='' ){
			
			$temp = explode("\n",trim($to_email));
			foreach($temp as $val){
				$val2 = preg_replace('/\s+/', '', $val);
				if(filter_var($val2, FILTER_VALIDATE_EMAIL)){
					$jemail[] = $val2;
				}				
			}
			
		}
		
		
        //Add PM if Individual Property Mangers Receive Certificate & Invoice? = Yes AND Agency != MM
        if( $job['allow_indiv_pm_email_cc']==1 && $agency_has_mm == false ){
            
            // pm id
            $pm_id = $job['pm_id_new'];
            $pm_email_fin = "";
            
            if (!is_null($pm_id)) {
                // If property has PM with valid email
                /* comment out with user_type filter
                $pm_sql = $this->db->query("
                    SELECT `email`
                    FROM `agency_user_accounts`
                    WHERE `agency_user_account_id` = {$pm_id}
                    AND `email` != ''
                    AND `email` IS NOT NULL
                    AND `user_type` = 2
                ");
                */
                //added by gherx removed user_type filter (refer above commented out original query)
                $pm_sql = $this->db->query("
                    SELECT `email`
                    FROM `agency_user_accounts`
                    WHERE `agency_user_account_id` = {$pm_id}
                    AND `email` != ''
                    AND `email` IS NOT NULL
                ");

                if( $pm_sql->num_rows()>0 ){
                    
                    // email not empty, lets validate it
                    $pm = $pm_sql->row_array();
                    $pm_email2 = trim($pm['email']);
                    $pm_email3 = preg_replace('/\s+/', '', $pm_email2);
                    if(filter_var($pm_email3, FILTER_VALIDATE_EMAIL)){
                        $jemail[] = $pm_email3;
                    }
                    
                }
            }
            
		}
		
		return $jemail;
		
    }



    function processMergedSendToEmails($agency_id,$agency_account_emails,$job){

        $email_arr = [];
        $pm_email = null;

        // agency accounts email
        $agency_account_emails_exp = explode("\n",$agency_account_emails); // breakdown email
        $agency_account_email_arr = [];
        foreach($agency_account_emails_exp as $agency_account_email){

            // sanitize email
            if(filter_var(preg_replace('/\s+/', '', trim($agency_account_email)), FILTER_VALIDATE_EMAIL)){
                $agency_account_email_arr[] = $agency_account_email;
            }	

        }

        // get PM email
        if( $job['pm_id_new'] > 0 ){
                
            $pm_sql = $this->db->query("
                SELECT `email`
                FROM `agency_user_accounts`
                WHERE `agency_user_account_id` = {$job['pm_id_new']}
                AND `email` != ''
                AND `email` IS NOT NULL
            ");
            if( $pm_sql->num_rows() > 0 ){

                $pm_row = $pm_sql->row();   
                
                // sanitize email
                if( filter_var(preg_replace('/\s+/', '', trim($pm_row->email)), FILTER_VALIDATE_EMAIL) ){
                    $pm_email = $pm_row->email; // PM email
                }

            }

        } 

        if( $job['franchise_groups_id'] == 10 ){ // franchise group is private, send to landlord email
            
            // get landlord email
            $prop_sql = $this->db->query("
                SELECT `landlord_email` 
                FROM `property` 
                WHERE `property_id` = {$job['property_id']}                
            ");
            $prop_row = $prop_sql->row(); 

            // sanitize email
            if( filter_var(preg_replace('/\s+/', '', trim($prop_row->landlord_email)), FILTER_VALIDATE_EMAIL) ){
                $email_arr[] = $prop_row->landlord_email; // landlord email
            }

        }else if( $job['invoice_pm_only'] == 1 ){ // send to PM only, using "Invoice PM'S Only?" agency preference
                       
            $email_arr[] = $pm_email; // PM email
            
        }else{ // default
            
            // check if agency has maintenance program
            $agency_has_mm = $this->system_model->check_agency_has_mm($agency_id);
            
            if( $agency_has_mm == true ){ // Maintenance Program Found

                $email_arr[] = trim(make_email('maintenance_manager'));

            }else{     
                
                // default to agency account email
                $email_arr = $agency_account_email_arr;

                // add PM email, using "Individual Property Managers Receive Certificate & Invoice?" agency preference
                if( $job['allow_indiv_pm_email_cc'] == 1 ){
                    $email_arr[] = $pm_email;                    
                }

            }		

        }  
        
        if( count($email_arr) == 0 ){ // if empty, default to agency account email
            
            $email_arr = $agency_account_email_arr;

        }
		
		return $email_arr;
		
    }
    

    /**
     * sendInvoiceCertEmail
     * Return Boolean
     */


    function sendInvoiceCertEmail($job, $emails, $country_id='', $invoice_only=0)
    {

        # Needs to be in array format.
        if(!is_array($emails)) $emails = array($emails);

        $p_address = $job['address_1'] . " " . $job['address_2'] . " " . $job['address_3'] . " " . $job['state'] . " " . $job['postcode'];
        $qt_type = $job['qt'];
        
        $encrypt = rawurlencode(HashEncryption::encodeString($job['id']));
        $baseUrl = $_SERVER["SERVER_NAME"];
        if(isset($_SERVER['HTTPS'])){
            $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
        } else{
            $protocol = 'http';
        }

        $dynamic_text = ( $job['send_emails'] == 1 )?'You can also':'Please';

        // comma separated "send to" emails
        if (is_array($emails)) {
            $sent_to_imp = implode(", ",$emails);
        }else {
            $sent_to_imp = $emails;
        }

        if( $job['send_quote']==1 ){

            $email_txt = 'Quote';
            
           ## $pdf_link_str = 'You can also view your '.$email_txt.' <a href="'.$protocol.'://'.$baseUrl.'/pdf/view_quote/?job_id='.$encrypt.'">here</a>.';  //updated below

           $pdf_link_str = "{$dynamic_text} follow this <a href='{$protocol}://{$baseUrl}/pdf/view_quote/?job_id={$encrypt}&qt={$qt_type}'>link</a>  to view your ".$email_txt." for the above property.";
           $pdf_link_str2 = "You can also view a copy of your ".$email_txt." on the agency portal at any time.";
            
        }else if( $invoice_only == 1 || ( $job['assigned_tech'] == 1 || $job['assigned_tech'] == 2 ) ){ // Upfront bill and Other Supplier should only send invoice PDF

            $email_txt = 'Invoice';

            ##$pdf_link_str = 'You can also view your '.$email_txt.' <a href="'.$protocol.'://'.$baseUrl.'/pdf/view_invoice/?job_id='.$encrypt.'">here</a>.';  //updated below
            
            $pdf_link_str = "{$dynamic_text} follow this <a href='{$protocol}://{$baseUrl}/pdf/view_invoice/?job_id={$encrypt}'>link</a>  to view your ".$email_txt." for the above property.";
            $pdf_link_str2 = "You can also view a copy of your ".$email_txt." on the agency portal at any time.";

        }else{         
        
            if( $job['exclude_free_invoices'] == 1 && $job['invoice_amount'] == 0 ){ // send certificate only

                $email_txt = 'Statement of Compliance';                
                
                $pdf_link_str = "{$dynamic_text} follow this <a href='{$protocol}://{$baseUrl}/pdf/certificates/{$encrypt}'>link</a>  to view your ".$email_txt." for the above property.";
                $pdf_link_str2 = "You can also view a copy of your ".$email_txt." on the agency portal at any time.";                                                
            
            }else if( $job['send_combined_invoice'] == 1 ){ // combined

                $email_txt = 'Invoice/Statement of Compliance';                

                $pdf_link_str = "{$dynamic_text} follow this <a href='{$protocol}://{$baseUrl}/pdf/combined/{$encrypt}'>link</a>  to view your ".$email_txt." for the above property.";
                $pdf_link_str2 = "You can also view a copy of your ".$email_txt." on the agency portal at any time.";

            }else{ // default, invoice and cert

                // $email_txt = 'Invoice and Statement of Compliance';                

                // $pdf_link_str = "{$dynamic_text} follow this <a href='{$protocol}://{$baseUrl}/pdf/view_invoice/?job_id={$encrypt}'>link</a> to view your Invoice and this <a href='{$protocol}://{$baseUrl}/pdf/view_certificate/?job_id={$encrypt}'>link</a> to view your Statement of Compliance for the above property.";
                // $pdf_link_str2 = "You can also view a copy of your Invoice and Statement of Compliance on the agency portal at any time";                

                if ($job['is_invoice']  == 1 && $job['is_certificate'] == 0) {
                    $email_txt = 'Certificate';
        
                    $pdf_link_str = "{$dynamic_text} follow this <a href='{$protocol}://{$baseUrl}/pdf/certificates/{$encrypt}'>link</a> to view your Statement of Compliance for the above property.";
                    $pdf_link_str2 = "You can also view a copy of your Statement of Compliance on the agency portal at any time";
                }
                if ($job['is_invoice']  == 0 && $job['is_certificate'] == 1) {
        
                    $email_txt = 'Invoice';
        
                    $pdf_link_str = "{$dynamic_text} follow this <a href='{$protocol}://{$baseUrl}/pdf/invoices/{$encrypt}'>link</a> to view your Invoice.";
                    $pdf_link_str2 = "You can also view a copy of your Invoice on the agency portal at any time";
        
                }
                if (($job['is_invoice']  == null && $job['is_certificate'] == null) || ($job['is_invoice']  == 0 && $job['is_certificate'] == 0)) {
        
                    $email_txt = 'Invoice and Statement of Compliance';
        
                    $pdf_link_str = "{$dynamic_text} follow this <a href='{$protocol}://{$baseUrl}/pdf/combined/{$encrypt}'>link</a> to view your Invoice and this <a href='{$protocol}://{$baseUrl}/pdf/certificates/{$encrypt}'>link</a> to view your Statement of Compliance for the above property.";
                    $pdf_link_str2 = "You can also view a copy of your Invoice and Statement of Compliance on the agency portal at any time";
        
                }
            }
        
        }

        // SEND EMAIL
        
        $from_email = $this->gherxlib->getCountryViaCountryId($country_id);

        $job_details = $job;
        $job_id = $job['id'];   

        // append checkdigit to job id for new invoice number
        $check_digit = $this->gherxlib->getCheckDigit(trim($job_id));
        $bpay_ref_code = "{$job_id}{$check_digit}"; 

        
        if( is_numeric($job['prop_comp_with_state_leg']) && $job['prop_comp_with_state_leg'] == 0  ){ // if not compliant
            $subject_txt = "URGENT - Not Compliant - {$p_address}";
        }else if( $job['agency_id'] == 6502 && $job['invoice_amount'] > 0 ){ // Compass Housing QLD 
            $subject_txt = "Payment required ".$bpay_ref_code;
        }else{
            $subject_txt = config_item('company_name_short') . ' ' .$email_txt.' - ' . $job['address_1'] . " " . $job['address_2'] . " " . $job['address_3'];
        }

        //address email data
        $email_data['address1'] = $job['address_1'];
        $email_data['address2'] = $job['address_2'];
        $email_data['address3'] = $job['address_3'];
        $email_data['state'] = $job['state'];
        $email_data['postcode'] = $job['postcode'];

        $email_data['signatureCustom'] = $from_email->email_signature;   
        $email_data['tradingCustom'] = $from_email->trading_name;   
        $email_data['email_txt'] = $email_txt;   
        $email_data['send_emails'] = $job['send_emails'];

        // agency name switch, display landlord instead of FG is private
        if( $this->system_model->getAgencyPrivateFranchiseGroups($job['franchise_groups_id']) == true ){
            $agency_name_switch = "{$job['landlord_firstname']} {$job['landlord_lastname']}";
        }else{
            $agency_name_switch = $job['agency_name'];
        }
        
        $html_content  = "
        <p>
            Dear {$agency_name_switch},
        </p>
        <p>
            A copy of your {$email_txt} is available on the link below:
            <br /><br />
            {$pdf_link_str}
            <br /><br />
            If you have any questions or we can be of further assistance please feel free to contact us on {$from_email->agent_number} or {$from_email->outgoing_email}.<br />
        </p>
        <p>
            Regards,<br />
            Smoke Alarm and Testing Services
        </p>
        ";

        $email_data['pdf_link_str'] = $pdf_link_str;
        $email_data['pdf_link_str2'] = $pdf_link_str2;
        $email_data['content'] = $html_content;
        $email_data['agency_name_switch'] = $agency_name_switch;
        $email_data['agent_number'] = $from_email->agent_number;
        $email_data['outgoing_email'] = $from_email->outgoing_email;                    

        $email_not_pm_sent = false;


        $this->email->clear(TRUE); // Do not remove - for clearing attachment
        $this->email->to($emails);
        //$this->email->bcc(make_email('cc')); //Stopped sending to cc@sats on 24/09/2020 as per Daniel’s instructions
	    $this->email->bcc(make_email('bcc'));
        $is_copy = false;
        $this->email->subject($subject_txt);
        $body = $this->load->view('emails/certificate_and_invoice', $email_data, TRUE);
        $this->email->message($body);
       // $this->email->attach($_SERVER["DOCUMENT_ROOT"]."/images/logo.png");


        /**  PDF START HERE   */

        $this->load->library('JPDF');
        $pdf = new JPDF();


        $send_quote = $job_details['send_quote'];
        $mm_need_proc_inv = $job_details['mm_need_proc_inv'];


        /**  pdfInvoiceCertComb  */

        # Job Details
        if($job['platform_invoicing'] == 1){
            $job_details = $this->job_functions_model->getJobDetails2($job_id,$query_only = false);
        }

        # Appliance Details
        // $appliance_details = $this->alarm_functions_model->getPropertyAlarms($job_id, 1, 0);
        // $num_appliances = sizeof($appliance_details);

        # Alarm Details
        $alarm_details = [];
        if (in_array($job_details['jservice'], Alarm_job_type_model::SMOKE_ALARM_IDS)) {
            $alarm_details = $this->alarm_functions_model->getPropertyAlarms($job_id, 1, 0, $job_details['jservice']);
        }
        $num_alarms = sizeof($alarm_details);

        # Safety Switch Details
        // $safety_switches = $this->alarm_functions_model->getPropertyAlarms($job_id, 0, 1, 4);
        // $num_safety_switches = sizeof($safety_switches);

        // $job_tech_sheet_job_types = $this->job_functions_model->getTechSheetAlarmTypesJob($job_id, true);

        # Property + Agent Details
        $property_details = $this->functions_model->getPropertyAgentDetails($job_details['property_id']);

        /**  pdfInvoiceCertComb END  */

        // default "email sent" log 
        if( $send_quote == 1 ){
            $job_log_txt = 'Quote';
        }else if( $invoice_only == 1 ){
            $job_log_txt = 'Invoice';
        }else{
            if ($job["is_invoice"] == 1 && $job["is_certificate"] == 0) {
                $job_log_txt = 'Certificate';
            } else if ($job["is_invoice"] == 0 && $job["is_certificate"] == 1) {                                
                $job_log_txt = 'Invoice';
            } else {
                $job_log_txt = 'Invoice/Cert';
            }
        }

        $email_sent_log_txt = "{$job_log_txt} Email Sent to: <strong>{$sent_to_imp}</strong>";

        // add attachment
        if( $job_details['send_emails'] == 1 ){

            if( $send_quote == 1 ) {                

                //load template
                $pdf_quote_template = $this->pdf_template->pdf_quote_template($job_id, $job_details, $property_details, $alarm_details, $num_alarms, $country_id, "", $is_copy, $qt_type);
                
                // Attach Invoice Only
                $this->email->attach($pdf_quote_template, 'attachment', 'quote_' . $bpay_ref_code . '.pdf', 'application/pdf');

            }else if( $invoice_only == 1 || ( $job_details['assigned_tech'] == 1 || $job_details['assigned_tech'] == 2 ) ){ // Upfront bill and Other Supplier should only send invoice PDF

                //load template
                $invoice_pdf = $this->pdf_template->pdf_invoice_template($job_id, $job_details, $property_details, $alarm_details, $num_alarms, $country_id, "", $is_copy);
                
                // Attach Invoice Only
                $this->email->attach($invoice_pdf, 'attachment', 'invoice' . $bpay_ref_code . '.pdf', 'application/pdf');
            
            }else{ // default     

                if( $job_details['exclude_free_invoices'] == 1 && $job_details['invoice_amount'] == 0 ){ // send certificate only

                    $cert_pdf = $this->pdf_template->pdf_certificate_template_v2($job_id, $job_details, $property_details, $alarm_details, $num_alarms, $country_id, "", $is_copy);
                    $this->email->attach($cert_pdf, 'attachment', 'cert' . $bpay_ref_code . '.pdf', 'application/pdf');

                    // job log description                       
                    if( $job_details['send_combined_invoice']== 1 ){
                        $email_sent_log_txt = 'Certificate instead of combined sent to: <b>'.$sent_to_imp.'</b>, as the agency has declined $0 invoices';
                    }else{ // default
                        $email_sent_log_txt = 'Certificate only sent to: <b>'.$sent_to_imp.'</b>, as the agency has declined $0 invoices';
                    }                                   
                
                }else if( $job_details['send_combined_invoice'] == 1 ){ // sends combined pdf

                    $send_combined_invoice = $this->pdf_template->pdf_combined_template_v2($job_id, $job_details, $property_details, $alarm_details, $num_alarms, $country_id, "", $is_copy);
                    $this->email->attach($send_combined_invoice, 'attachment', 'invoice_cert_' . $bpay_ref_code . '.pdf', 'application/pdf');

                } else { // default, send both invoice and cert separately

                    // invoice
                    $invoice_pdf = $this->pdf_template->pdf_invoice_template($job_id, $job_details, $property_details, $alarm_details, $num_alarms, $country_id, "", $is_copy);
                    
                    // certificate
                    $cert_pdf = $this->pdf_template->pdf_certificate_template_v2($job_id, $job_details, $property_details, $alarm_details, $num_alarms, $country_id, "", $is_copy);
                    
                    if ($job_details["is_invoice"] == 1 && $job_details["is_certificate"] == 0) {
                        //attach invoice
                        $this->email->attach($invoice_pdf, 'attachment', 'invoice' . $bpay_ref_code . '.pdf', 'application/pdf');
                    } else if ($job_details["is_invoice"] == 0 && $job_details["is_certificate"] == 1) {
                        //attach certificate
                        $this->email->attach($cert_pdf, 'attachment', 'cert' . $bpay_ref_code . '.pdf', 'application/pdf');
                    } else {
                        // Attach invoice and certificate
                        $this->email->attach($invoice_pdf, 'attachment', 'invoice' . $bpay_ref_code . '.pdf', 'application/pdf');
                        $this->email->attach($cert_pdf, 'attachment', 'cert' . $bpay_ref_code . '.pdf', 'application/pdf');
                    }  
                }
                
            }
            
        }
        
        // do not send email for franchise group = Image
        //if( $job_details['franchise_groups_id'] != $this->image_fg_id ){

            if($this->email->send()){
                $email_not_pm_sent = true;
                if (is_array($emails)) {
                    $sent_to_imp = implode(", ",$emails);
                }else {
                    $sent_to_imp = $emails;
                }
            
                if( $send_quote == 1 ){
                    $job_log_txt = 'Quote';
                }else if( $invoice_only == 1 ){
                    $job_log_txt = 'Invoice';
                }else{
                    if ($job["is_invoice"] == 1 && $job["is_certificate"] == 0) {
                        $job_log_txt = 'Certificate';
                    } else if ($job["is_invoice"] == 0 && $job["is_certificate"] == 1) {                                
                        $job_log_txt = 'Invoice';
                    } else {
                        $job_log_txt = 'Invoice/Cert';
                    }
                }
            
                // job log                        
                $log_params = array(
                    'title' => 66,  // Merge Invoice Sent
                    'details' => $email_sent_log_txt,
                    'display_in_vjd' => 1,
                    'property_id' => $job_details['property_id'],
                    'job_id' => $job_details['id'],
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

        //}
        

        if($email_not_pm_sent){ 

            if( $send_quote == 1 ){ // quotes
                
                $this->db->query("
				UPDATE jobs 
				SET `qld_upgrade_quote_emailed` = NOW() 			
				WHERE id = {$job_id}
				");

            }else if( $mm_need_proc_inv == 1 ){ // MM precomp		
                
                $this->db->query("
				UPDATE jobs 
				SET 
					`mm_need_proc_inv_emailed` = NOW(),
					`client_emailed` = NOW() 
				WHERE id = {$job_id}
				");

            }else{

                // check if agency has maintenance program
                //$agency_has_mm = $this->system_model->check_agency_has_mm($job_details['agency_id']);
		
		        //if( $agency_has_mm == false ){ // on merge, only mark `client_emailed` if no maintenance manager
                    
                    $this->db->query("
					UPDATE jobs 
					SET `client_emailed` = NOW() 			
					WHERE id = {$job_id}
					");

                //}
                
            }
            return true;

        }else{
            return false;
        }



    }


    public function service_due_email()
    {
        $this->load->model('jobs_model');
        $this->load->model('agency_model');
        $this->load->model('api_model');

        $country_id = $this->config->item('country');
        $job_status = 'Pending';

        // get country data
        $country_params = array(
            'sel_query' => 'c.agent_number, c.outgoing_email',
            'country_id' => $country_id
        );
        $country_sql = $this->system_model->get_countries($country_params);
        $country_row = $country_sql->row();

        // get distinct agency
        $sel_query = "            
            DISTINCT(a.agency_id), 
            a.agency_name,
            a.agency_emails, 
            a.auto_renew,
            a.state,
            a.franchise_groups_id,
            a.allow_upfront_billing,
            
            aps.agency_pref_id,
            aps.sel_pref_val
        ";

        $custom_where = "a.agency_emails LIKE '%@%'";
        
        $job_params = array(
            'sel_query' => $sel_query,
            'custom_where' => $custom_where,
            'join_table' => array('agency_preference_selected'),

            'del_job' => 0,
            'p_deleted' => 0,
            'a_status' => 'active',

            'job_status' => $job_status,                
            'country_id' => $country_id,
                        
            'display_query' => 0
        );

        $dist_agency_sql = $this->jobs_model->get_jobs($job_params);

         // loop per agency
        foreach( $dist_agency_sql->result() as $dist_agency_row )
        {
            // Send renewals reminders via Email?
            if ( !($dist_agency_row->agency_pref_id == 26 && (int)$dist_agency_row->sel_pref_val === 0) ) {

                // clear per agency
                $to_emails_arr = [];
                $email_body = null;

                $agency_emails_imp = explode("\n",trim($dist_agency_row->agency_emails)); // split emails by new line

                // only add sanitized email on array
                foreach($agency_emails_imp as $agency_email){

                    $agency_email2 = preg_replace('/\s+/', '', $agency_email);
                    if(filter_var($agency_email2, FILTER_VALIDATE_EMAIL)){
                        $to_emails_arr[] = $agency_email2;
                    }

                }

                if( $dist_agency_row->agency_id > 0 ) {

                    // get pending jobs
                    $sel_query = "
                        j.`id`,
                        j.`start_date`,
                        
                        p.`address_1` AS p_address_1,
                        p.`address_2` AS p_address_2,
                        p.`address_3` AS p_address_3,
                        p.`pm_id_new`,
    
                        aua.`agency_user_account_id`,
                        aua.`fname` AS pm_fname,
                        aua.`lname` AS pm_lname
                    ";

                    $job_params = array(
                        'sel_query' => $sel_query,
                        'custom_where' => $custom_where,

                        'del_job' => 0,
                        'p_deleted' => 0,
                        'a_status' => 'active',

                        'job_status' => $job_status,
                        'country_id' => $country_id,
                        'agency_filter' => $dist_agency_row->agency_id,

                        'join_table' => array('agency_user_accounts'),

                        'sort_list' => array(
                            array(
                                'order_by' => 'j.start_date',
                                'sort' => 'ASC'
                            )
                        ),

                        'display_query' => 0
                    );

                    $api_sel = "
                        agen_api_int.`api_integration_id`,        
                        agen_api_int.`connected_service`,
                        agen_api_int.`active`,
                        agen_api_int.`date_activated`,
    
                        agen_api.`api_name`,
                        agen_api.`agency_api_id`
                    ";

                    $api_params = array(
                        'sel_query' => $api_sel,
                        'agency_id' => $dist_agency_row->agency_id,
                        'sort_list' => [
                            [
                                'order_by' => 'agen_api.api_name',
                                'sort' => 'ASC'
                            ]
                        ]
                    );
                    $api_q = $this->api_model->get_agency_api_integration($api_params)->row();

                    $pending_sql = $this->jobs_model->get_jobs($job_params);
                    $view_data['pending_sql'] = $pending_sql;
                    $pending_count = $pending_sql->num_rows();
                }

                // view data
                $view_data['agency_portal_link'] = $this->config->item('agency_link');
                $view_data['youtube_link'] = "https://youtu.be/RlMSzUKL_wQ";
                $view_data['agent_number'] = $country_row->agent_number;
                $view_data['agency_auto_renew'] = $dist_agency_row->auto_renew;
                $view_data['agency_name'] = $dist_agency_row->agency_name;
                $view_data['agency_state'] = $dist_agency_row->state;
                $view_data['subscription_billing'] = $dist_agency_row->allow_upfront_billing;
                $view_data['apis'] = $api_q;

                ##check if agency connected to api or not
                $view_data['agency_api_tokens_q'] = $this->db->query("SELECT * FROM `agency_api_tokens` WHERE agency_id = {$dist_agency_row->agency_id} AND active = 1");
                ##check if agency connected to api or not end

                $return_as_string =  true;

                // content
                $email_body .= $this->load->view('emails/template/email_header', $view_data, $return_as_string);

                if( $dist_agency_row->auto_renew == 1 ){
                    $email_body .= $this->load->view('emails/service_due_email', $view_data, $return_as_string);
                }else{ // auto_renew = 0
                    $email_body .= $this->load->view('emails/service_due_email_no_to_auto_renew', $view_data, $return_as_string);
                }

                $email_body .= $this->load->view('emails/template/email_footer', $view_data, $return_as_string);

                // subject
                $property_pluralised = 'Property';
                if($pending_count > 1){
                    $property_pluralised = 'Properties';
                }

                $state_subject = 'Due for Service';
                if($dist_agency_row->state === "QLD"){
                    $state_subject = 'Due for Subscription Renewal';
                }

                $subject = $this->config->item('company_name_short') . ' - ' . $pending_count . ' ' . $property_pluralised . ' ' . $state_subject;

                // send emails
                $this->email->to($to_emails_arr);
//                $this->email->bcc(make_email('bcc'));
                $this->email->subject($subject);
                $this->email->message($email_body);
                $this->email->send();

            }
        }
    }


    public function weekly_report_email(){

        $this->load->model('jobs_model');

        $country_id = $this->config->item('country');
        $now = date("Y-m-d");

        // get country data
        $country_params = array(
            'sel_query' => 'c.agent_number, c.outgoing_email',
            'country_id' => $country_id
        );
        $country_sql = $this->system_model->get_countries($country_params);
        $country_row = $country_sql->row();

        // get distinct agency
        $sel_query = "
        DISTINCT(a.agency_id), 
        a.`agency_name`,
        a.agency_emails,
        a.`franchise_groups_id`
        ";

        // also exclude Upfront bill, Other supplier and To Be Invoiced jobs 
        $custom_where = "
        a.agency_emails LIKE '%@%'
        AND(
            (
                j.status IN ('Completed', 'Merged Certificates')
                AND j.`date` BETWEEN '".date('Y-m-d',strtotime("-7 day"))."' AND '".$now."'
    
            ) OR
            (
                j.status = 'Booked'
                AND j.`date` BETWEEN '".$now."' AND '".date('Y-m-d',strtotime("+7 day"))."'
            )
        )  
        AND j.`assigned_tech` NOT IN(1,2,3)  
        AND j.`status` != 'To Be Invoiced'    
        ";
        
        $job_params = array(
            'sel_query' => $sel_query,
            'custom_where' => $custom_where,
            
            'del_job' => 0,
            'p_deleted' => 0,
            'a_status' => 'active',
                          
            'country_id' => $country_id,                
            'display_query' => 0
        );

        $dist_agency_sql = $this->jobs_model->get_jobs($job_params);

         // loop per agency
        foreach( $dist_agency_sql->result() as $dist_agency_row ){

            // clear per agency
            $to_emails_arr = [];   
            $email_body = null;
            
            $agency_emails_imp = explode("\n",trim($dist_agency_row->agency_emails)); // split emails by new line

            // only add sanitized email on array
            foreach($agency_emails_imp as $agency_email){              

                $agency_email2 = preg_replace('/\s+/', '', $agency_email);
                if(filter_var($agency_email2, FILTER_VALIDATE_EMAIL)){
                    $to_emails_arr[] = $agency_email2;
                }	                 
                            
            }

            if( $dist_agency_row->agency_id > 0 ){

                // get completed jobs 
                $sel_query = "                    
                    j.`id`,
                    j.date AS jdate,
                    
                    p.`address_1` AS p_address_1,
                    p.`address_2` AS p_address_2,
                    p.`address_3` AS p_address_3,
                    p.`pm_id_new`,

                    aua.`agency_user_account_id`,
                    aua.`fname` AS pm_fname,
                    aua.`lname` AS pm_lname
                ";

                $custom_where = "
                a.agency_emails LIKE '%@%'

                AND j.status IN ('Completed', 'Merged Certificates')
                AND j.`date` BETWEEN '".date('Y-m-d',strtotime("-7 day"))."' AND '".$now."'     
                AND j.`assigned_tech` NOT IN(1,2,3)   
                ";
                
                $job_params = array(
                    'sel_query' => $sel_query,
                    'custom_where' => $custom_where,
                    
                    'del_job' => 0,
                    'p_deleted' => 0,
                    'a_status' => 'active',
                                        
                    'country_id' => $country_id,
                    'agency_filter' => $dist_agency_row->agency_id,

                    'join_table' => array('agency_user_accounts'),	
                                
                    'sort_list' => array(	
                        array(
                            // 'order_by' => 'j.date',
                            'order_by' => 'p.pm_id_new',
                            'sort' => 'DESC'
                        )
                    ),
                    
                    'display_query' => 0
                );
                    
                $view_data['completed_sql'] = $this->jobs_model->get_jobs($job_params);      
                
                // get booked jobs 
                $sel_query = "
                    j.`id`,
                    j.date AS jdate,
                    
                    p.`address_1` AS p_address_1,
                    p.`address_2` AS p_address_2,
                    p.`address_3` AS p_address_3,
                    p.`pm_id_new`,

                    aua.`agency_user_account_id`,
                    aua.`fname` AS pm_fname,
                    aua.`lname` AS pm_lname
                ";

                $custom_where = "
                a.agency_emails LIKE '%@%'

                AND j.status = 'Booked'
                AND j.`date` BETWEEN '".$now."' AND '".date('Y-m-d',strtotime("+7 day"))."'
                AND j.`assigned_tech` NOT IN(1,2,3)  
                ";
                
                $job_params = array(
                    'sel_query' => $sel_query,
                    'custom_where' => $custom_where,
                    
                    'del_job' => 0,
                    'p_deleted' => 0,
                    'a_status' => 'active',
                                        
                    'country_id' => $country_id,
                    'agency_filter' => $dist_agency_row->agency_id,

                    'join_table' => array('agency_user_accounts'),	
                                
                    'sort_list' => array(	
                        array(
                            'order_by' => 'p.pm_id_new',
                            'sort' => 'DESC'
                        )
                    ),
                    
                    'display_query' => 0
                );
                    
                // view data
                $view_data['booked_sql'] = $this->jobs_model->get_jobs($job_params); 
                $view_data['agency_name'] = $dist_agency_row->agency_name;

                // GET current week and set to last day of the current week
                $year = date('Y');
                $weekNumber = date('W');
                $endDate = new DateTime();
                $endDate->setISODate($year, $weekNumber, 7);
                $endDateString = $endDate->format('d/m/Y');

                $return_as_string =  true;

                // content
                $email_body .= $this->load->view('emails/template/email_header', $view_data, $return_as_string);
                $email_body .= $this->load->view('emails/weekly_report_email', $view_data, $return_as_string);
                $email_body .= $this->load->view('emails/template/email_footer', $view_data, $return_as_string);

                // subject
                $subject = config_item('company_name_short') . " - Completed & Booked Job Report - Week Ending " . ($endDateString);
                $to_email = $to_emails_arr;

                $this->email->to($to_email);
                $this->email->cc(make_email('reports'));
//                $this->email->bcc(make_email('bcc'));

                $this->email->subject($subject);
                $this->email->message($email_body);

                // do not send email for franchise group = Image
                //if( $dist_agency_row->franchise_groups_id != $this->image_fg_id ){

                // send email
                $this->email->send();

                //}
                

            }


        }  

    }

    public function key_access_email(){

        $this->load->model('jobs_model');

        $country_id = $this->config->item('country');
        $job_status = 'Booked';
        $day = date('D');
        $is_thursday = false;
        $is_friday = false;
        $spec_agency = array(1328);

        $next_day = date('Y-m-d', strtotime('+1 days'));
        $nexy_2_days = date('Y-m-d', strtotime('+2 days'));
        $nexy_3_days = date('Y-m-d', strtotime('+3 days'));  
        $nexy_4_days = date('Y-m-d', strtotime('+4 days'));  
        $nexy_5_days = date('Y-m-d', strtotime('+5 days'));      

        // get country data
        $country_params = array(
            'sel_query' => 'c.agent_number, c.outgoing_email',
            'country_id' => $country_id
        );
        $country_sql = $this->system_model->get_countries($country_params);
        $country_row = $country_sql->row();

        // get distinct agency
        $sel_query = "
        DISTINCT(a.agency_id), 
        a.`agency_name`,
        a.agency_emails
        ";

        // get key access jobs
        $custom_where = "
        j.key_access_required = 1
        AND a.agency_emails LIKE '%@%'         
        ";

        if( $day == 'Fri' ){  // friday sends saturday and monday    

            $is_friday = true;
            
            
            if( $country_id == 1 ){ // AU
                $custom_where .= " AND ( j.date = '{$next_day}' OR j.date = '{$nexy_3_days}' ) ";
            }else if( $country_id == 2 ){ // NZ

                // default
                $custom_where .= " AND ( j.date = '{$next_day}' OR j.date = '{$nexy_3_days}' ) ";
                
                // use this if NZ has holiday on monday
                //$custom_where .= " AND ( j.date = '{$next_day}' OR j.date = '{$nexy_3_days}' OR j.date = '{$nexy_4_days}' ) "; 

            } 
            

            //$custom_where .= " AND ( j.date = '{$next_day}' OR j.date = '{$nexy_3_days}' OR j.date = '{$nexy_4_days}' ) "; ##disabled > use only when both AU/NZ has Monday holiday

        }else{
            $custom_where .= " AND j.date = '{$next_day}' ";
        }                
        
        
        $job_params = array(
            'sel_query' => $sel_query,
            'custom_where' => $custom_where,
            
            'del_job' => 0,
            'p_deleted' => 0,
            'a_status' => 'active',

            'job_status' => $job_status,                
            'country_id' => $country_id,
            
            'display_query' => 0
        );        

        $dist_agency_sql = $this->jobs_model->get_jobs($job_params);        

         // loop per agency
        foreach( $dist_agency_sql->result() as $dist_agency_row ){

            // clear per agency
            $to_emails_arr = [];   
            $email_cc = [];
            $email_body = null;

            // key access dates
            $key_access_date_arr = [];
            $has_fri = false;
            $has_sat = false;
            $has_mon = false;
            $has_tue = false;
            
            // split emails by new line
            $agency_emails_imp = explode("\n",trim($dist_agency_row->agency_emails)); 
            

            // only add sanitized email on array
            foreach($agency_emails_imp as $agency_email){              

                $agency_email2 = preg_replace('/\s+/', '', $agency_email);
                if(filter_var($agency_email2, FILTER_VALIDATE_EMAIL)){
                    $to_emails_arr[] = $agency_email2;
                }	                 
                            
            }

            $sent_to_imp = implode(", ",$to_emails_arr); // split by comma(,)

            if( $dist_agency_row->agency_id > 0 ){

                // CC to SATS keys email
                $email_cc[] = make_email('keys');

                // get key access jobs 
                $sel_query = "                    
                    j.`id` AS jid,
                    j.`date` AS jdate,
                    j.`key_access_details`,
                    j.`assigned_tech`,

                    sa.`StaffID`,
                    sa.`ClassID`,
                    sa.`FirstName` AS tech_fname,
                    sa.`LastName` AS tech_lname,
                    
                    p.`address_1` AS p_address_1,
                    p.`address_2` AS p_address_2,
                    p.`address_3` AS p_address_3,
                    p.`pm_id_new`,
                    p.`key_number`,

                    aua.`agency_user_account_id`,
                    aua.`fname` AS pm_fname,
                    aua.`lname` AS pm_lname
                ";
                
                $job_params = array(
                    'sel_query' => $sel_query,
                    'custom_where' => $custom_where,
                    
                    'del_job' => 0,
                    'p_deleted' => 0,
                    'a_status' => 'active',
        
                    'job_status' => $job_status,                   
                    'country_id' => $country_id,
                    'agency_filter' => $dist_agency_row->agency_id,

                    'join_table' => array('staff_accounts','agency_user_accounts'),	
                                
                    'sort_list' => array(	       
                        array(
                            'order_by' => 'sa.`FirstName`',
                            'sort' => 'ASC'
                        ),
                        array(
                            'order_by' => 'sa.`LastName`',
                            'sort' => 'ASC'
                        )
                    ),
                    
                    'display_query' => 0
                );              
        
                $job_sql = $this->jobs_model->get_jobs($job_params); 
                $view_data['job_sql'] = $job_sql;      
                
                if( $is_friday == true ){ // friday

                    foreach( $job_sql->result() as $job_row ){

                        $job_date_day = date('D',strtotime($job_row->jdate));
    
                        if( $job_date_day == 'Sat' ){
                            $has_sat = true;                            
                        }
    
                        if( $job_date_day == 'Mon' ){
                            $has_mon = true;                            
                        }                                                                                                                       
    
                    }

                    if( $has_sat == true ){
                        $key_access_date_arr[] = date("l d/m/Y",strtotime($next_day)); // saturday
                    }

                    if( $has_mon == true ){
                        $key_access_date_arr[] = date("l d/m/Y",strtotime($nexy_3_days)); // monday
                    }

                    // combine saturday and monday
                    if( $key_access_date_arr > 0 ){

                        if( count( $key_access_date_arr ) == 1 ){ // single
                                                   
                            $key_access_date_fin = $key_access_date_arr[0];
                                    
                        }else{  // multiple
                                            
                            $key_access_date_fin = implode(" AND ",$key_access_date_arr);                                                                       
                            
                        }
            
                    }

                }else{

                    $key_access_date_fin = date("l d/m/Y",strtotime($next_day));                                                         

                } 
                
                
                // CC to techs
                foreach( $job_sql->result() as $job_row ){

                    if( $job_row->assigned_tech > 0 ){

                       
                        // get technician
                        $tech_sql = $this->db->query("
                            SELECT `Email`
                            FROM `staff_accounts`
                            WHERE `StaffID` = {$job_row->assigned_tech}
                        ");
                        $tech_row = $tech_sql->row();
                        $tech_email = $tech_row->Email;
                
                        // add email for CC
                        if( in_array($tech_email,$email_cc)==false ){
                            if( filter_var($tech_email, FILTER_VALIDATE_EMAIL )){ // validate email
                                $email_cc[] = $tech_email;
                            }				
                        }
                
                    }                                                                                                                       

                }
                
                $imp_email_cc = implode(", ",$email_cc);

                // view data
                $view_data['outgoing_email'] = $country_row->outgoing_email;
                $view_data['agent_number'] = $country_row->agent_number;
                $view_data['agency_name'] = $dist_agency_row->agency_name;          
                $view_data['agency_id'] = $dist_agency_row->agency_id;
                $view_data['spec_agency'] = $spec_agency;        
                $view_data['key_access_date'] = $key_access_date_fin;  
                $view_data['sent_to_imp'] = $sent_to_imp;  
                $view_data['email_body_width'] = '1200px'; 

                $return_as_string =  true;

                // content
                $email_body .= $this->load->view('emails/template/email_header', $view_data, $return_as_string);
                $email_body .= $this->load->view('emails/key_access_email', $view_data, $return_as_string);
                $email_body .= $this->load->view('emails/template/email_footer', $view_data, $return_as_string);

                // subject
                $subject = "Keys to be collected for {$key_access_date_fin}";
                $to_email = $to_emails_arr;                                                

	            $this->email->reply_to(make_email('operations'),config_item('company_full_name'));
                $this->email->to($to_email);
                $this->email->cc($imp_email_cc);
                $this->email->subject($subject);
                $this->email->message($email_body);

                // send email
                $this->email->send();
                

            }


        }  

    }


    public function key_access_email_48_hours(){

        $this->load->model('jobs_model');

        $country_id = $this->config->item('country');
        $job_status = 'Booked';
        $day = date('D');
        $is_friday = false;
        $spec_agency = array(1328);

        $next_2_days = date('Y-m-d', strtotime('+2 days'));

        $next_3_days = date('Y-m-d', strtotime('+3 days'));  // next monday 
        $next_4_days = date('Y-m-d', strtotime('+4 days'));  // next tuesday       

        // get country data
        $country_params = array(
            'sel_query' => 'c.agent_number, c.outgoing_email',
            'country_id' => $country_id
        );
        $country_sql = $this->system_model->get_countries($country_params);
        $country_row = $country_sql->row();
        //echo $this->db->last_query();
        //exit();

        // get distinct agency
        $sel_query = "
        DISTINCT(a.agency_id), 
        a.`agency_name`,
        a.agency_emails
        ";

        // get key access jobs
        $custom_where = "
        j.key_access_required = 1 
        AND a.agency_emails LIKE '%@%'      
        AND a.`send_48_hr_key` = 1   
        ";

      
        if( $day == 'Fri' ){  // friday should sends monday and tuesday    

            $is_friday = true;
            
            if( $country_id == 1 ){ // AU

                $custom_where .= " AND ( j.date = '{$next_3_days}' OR j.date = '{$next_4_days}' ) ";

            }else if( $country_id == 2 ){ // NZ

                $custom_where .= " AND ( j.date = '{$next_3_days}' OR j.date = '{$next_4_days}' ) ";
                
                // use this if NZ has holiday on monday
                // pls edit accordingly for this 2 day interval cron
                //$custom_where .= " AND ( j.date = '{$next_2_days}' OR j.date = '{$next_3_days}' OR j.date = '{$next_4_days}' ) "; 

            } 

        }else{ // default, normal days

            $custom_where .= " AND j.date = '{$next_2_days}' ";

        }                
        
        
        $job_params = array(
            'sel_query' => $sel_query,
            'custom_where' => $custom_where,
            
            'del_job' => 0,
            'p_deleted' => 0,
            'a_status' => 'active',

            'job_status' => $job_status,                
            'country_id' => $country_id,
            'display_query' => 0
        );        

        $dist_agency_sql = $this->jobs_model->get_jobs($job_params);        
        //echo $this->db->last_query();
        //exit();

         // loop per agency
        foreach( $dist_agency_sql->result() as $dist_agency_row ){

            // clear per agency
            $to_emails_arr = [];   
            $email_cc = [];
            $email_body = null;

            // key access dates
            $key_access_date_arr = [];            
            $has_mon = false;
            $has_tue = false;
            
            // split emails by new line
            $agency_emails_imp = explode("\n",trim($dist_agency_row->agency_emails)); 
            

            // only add sanitized email on array
            foreach($agency_emails_imp as $agency_email){              

                $agency_email2 = preg_replace('/\s+/', '', $agency_email);
                if(filter_var($agency_email2, FILTER_VALIDATE_EMAIL)){
                    $to_emails_arr[] = $agency_email2;
                }	                 
                            
            }

            $sent_to_imp = implode(", ",$to_emails_arr); // split by comma(,)

            if( $dist_agency_row->agency_id > 0 ){

                // CC to SATS keys email
                $email_cc[] = make_email('keys');

                // get key access jobs 
                $sel_query = "                    
                    j.`id` AS jid,
                    j.`date` AS jdate,
                    j.`key_access_details`,
                    j.`assigned_tech`,

                    sa.`StaffID`,
                    sa.`ClassID`,
                    sa.`FirstName` AS tech_fname,
                    sa.`LastName` AS tech_lname,
                    
                    p.`address_1` AS p_address_1,
                    p.`address_2` AS p_address_2,
                    p.`address_3` AS p_address_3,
                    p.`pm_id_new`,
                    p.`key_number`,

                    aua.`agency_user_account_id`,
                    aua.`fname` AS pm_fname,
                    aua.`lname` AS pm_lname
                ";
                
                $job_params = array(
                    'sel_query' => $sel_query,
                    'custom_where' => $custom_where,
                    
                    'del_job' => 0,
                    'p_deleted' => 0,
                    'a_status' => 'active',
        
                    'job_status' => $job_status,                   
                    'country_id' => $country_id,
                    'agency_filter' => $dist_agency_row->agency_id,

                    'join_table' => array('staff_accounts','agency_user_accounts'),	
                                
                    'sort_list' => array(	       
                        array(
                            'order_by' => 'j.`date`',
                            'sort' => 'ASC'
                        ),
                        array(
                            'order_by' => 'j.`sort_order`',
                            'sort' => 'ASC'
                        )
                    ),
                    
                    'display_query' => 0
                );              
        
                $job_sql = $this->jobs_model->get_jobs($job_params); 
                $view_data['job_sql'] = $job_sql;      

                //echo $this->db->last_query();
                //print_r($email_cc);
                //exit();
                
                if( $is_friday == true ){ // friday

                    foreach( $job_sql->result() as $job_row ){

                        $job_date_day = date('D',strtotime($job_row->jdate));                       
    
                        if( $job_date_day == 'Mon' ){
                            $has_mon = true;                            
                        }      
                        
                        if( $job_date_day == 'Tue' ){
                            $has_tue = true;                            
                        }
    
                    }
                    

                    if( $has_mon == true ){
                        $key_access_date_arr[] = date("l d/m/Y",strtotime($next_3_days)); // monday
                    }

                    if( $has_tue == true ){
                        $key_access_date_arr[] = date("l d/m/Y",strtotime($next_4_days)); // tuesday
                    }

                    // combine monday and tuesday
                    if( $key_access_date_arr > 0 ){

                        if( count( $key_access_date_arr ) == 1 ){ // single
                                                   
                            $key_access_date_fin = $key_access_date_arr[0];
                                    
                        }else{  // multiple
                                            
                            $key_access_date_fin = implode(" AND ",$key_access_date_arr);                                                                       
                            
                        }
            
                    }

                }else{

                    $key_access_date_fin = date("l d/m/Y",strtotime($next_2_days));                                                         

                } 
                
                
                // CC to techs
                foreach( $job_sql->result() as $job_row ){

                    if( $job_row->assigned_tech > 0 ){

                       
                        // get technician
                        $tech_sql = $this->db->query("
                            SELECT `Email`
                            FROM `staff_accounts`
                            WHERE `StaffID` = {$job_row->assigned_tech}
                        ");
                        $tech_row = $tech_sql->row();
                        $tech_email = $tech_row->Email;
                
                        // add email for CC
                        if( in_array($tech_email,$email_cc)==false ){
                            if( filter_var($tech_email, FILTER_VALIDATE_EMAIL )){ // validate email
                                $email_cc[] = $tech_email;
                            }				
                        }
                
                    }                                                                                                                       

                }


                //$imp_email_cc = implode(", ",$email_cc);

                /*
                echo $this->db->last_query();
                print_r($email_cc);
                echo "<br /><br />";
                echo $imp_email_cc;
                exit();
                */
                
                // view data
                $view_data['outgoing_email'] = $country_row->outgoing_email;
                $view_data['agent_number'] = $country_row->agent_number;
                $view_data['agency_name'] = $dist_agency_row->agency_name;          
                $view_data['agency_id'] = $dist_agency_row->agency_id;
                $view_data['spec_agency'] = $spec_agency;        
                $view_data['key_access_date'] = $key_access_date_fin;  
                $view_data['sent_to_imp'] = $sent_to_imp;  
                $view_data['email_body_width'] = '1200px'; 

                $return_as_string =  true;

                // content
                $email_body .= $this->load->view('emails/template/email_header', $view_data, $return_as_string);
                $email_body .= $this->load->view('emails/key_access_email', $view_data, $return_as_string);
                $email_body .= $this->load->view('emails/template/email_footer', $view_data, $return_as_string);

                // subject
                $subject = "Keys to be collected for {$key_access_date_fin}";

                $to_email = $to_emails_arr;                                                


                $this->email->to($to_email); 
                $this->email->cc($email_cc);
                $this->email->subject($subject);
                $this->email->message($email_body);

                // send email
                $this->email->send();
                

            }


        }  

    }


    public function escalate_email(){

        $this->load->model('jobs_model');

        $country_id = $this->config->item('country');
        $job_status = 'Booked';
        $day = date('D');
        $is_friday = false;
        $spec_agency = array(1328);

        $next_day = date('Y-m-d', strtotime('+1 days'));
        $nexy_3_days = date('Y-m-d', strtotime('+3 days'));

        // get country data
        $country_params = array(
            'sel_query' => 'c.agent_number, c.outgoing_email',
            'country_id' => $country_id
        );
        $country_sql = $this->system_model->get_countries($country_params);
        $country_row = $country_sql->row();

        // get distinct agency
        $dist_agency_sql = $this->db->query("
            SELECT DISTINCT (
                a.`agency_id`
            ), a.`agency_name`, a.`franchise_groups_id`
            FROM `selected_escalate_job_reasons` AS sejr
            LEFT JOIN `escalate_job_reasons` AS ejr ON sejr.`escalate_job_reasons_id` = ejr.`escalate_job_reasons_id`
            LEFT JOIN `jobs` AS j ON sejr.`job_id` = j.`id`
            LEFT JOIN `property` AS p ON j.`property_id` = p.`property_id` 
            LEFT JOIN `agency` AS a ON p.`agency_id` = a.`agency_id` 
            WHERE j.status =  'Escalate'
            AND p.`deleted` =0
            AND ( p.is_nlm = 0 OR p.is_nlm IS NULL )
            AND a.`status` =  'active'
            AND j.`del_job` =0
            AND a.`country_id` ={$country_id}
            AND ejr.`active` = 1
            AND a.deleted = 0
            AND (
                sejr.`escalate_job_reasons_id` != 3 AND
                sejr.`escalate_job_reasons_id` != 4 AND
                sejr.`escalate_job_reasons_id` != 5 
            )
        ");        
        //echo $this->db->last_query();

         // loop per agency
        foreach( $dist_agency_sql->result() as $dist_agency_row ){

            // clear per agency
            $to_emails_arr = [];   
            $email_body = null;
            
            $agency_emails_imp = explode("\n",trim($dist_agency_row->agency_emails)); // split emails by new line

            // only add sanitized email on array
            foreach($agency_emails_imp as $agency_email){              

                $agency_email2 = preg_replace('/\s+/', '', $agency_email);
                if(filter_var($agency_email2, FILTER_VALIDATE_EMAIL)){
                    $to_emails_arr[] = $agency_email2;
                }	                 
                            
            }

            if( $dist_agency_row->agency_id > 0 ){

                // get escalate jobs 
                $escalate_sql = $this->db->query("
                SELECT DISTINCT (
                    j.`property_id`
                ), 
        
                p.`address_1` AS p_address_1, 
                p.`address_2` AS p_address_2, 
                p.`address_3` AS p_address_3,
                p.`state` AS p_state,
                p.`postcode` AS p_postcode,
                p.`property_managers_id`,
                p.`pm_id_new`,
                                                
                a.`agency_emails`,
                
                aua.`agency_user_account_id`,
                aua.`fname` AS pm_fname,
                aua.`lname` AS pm_lname
                
                FROM `selected_escalate_job_reasons` AS sejr
                LEFT JOIN `escalate_job_reasons` AS ejr ON sejr.`escalate_job_reasons_id` = ejr.`escalate_job_reasons_id`
                LEFT JOIN `jobs` AS j ON sejr.`job_id` = j.`id`
                LEFT JOIN `property` AS p ON j.`property_id` = p.`property_id` 
                LEFT JOIN `agency` AS a ON p.`agency_id` = a.`agency_id` 
                LEFT JOIN `agency_user_accounts` AS aua ON p.`pm_id_new` = aua.`agency_user_account_id`
                WHERE j.status =  'Escalate'
                AND p.`deleted` =0
                AND ( p.is_nlm = 0 OR p.is_nlm IS NULL )
                AND a.`status` =  'active'
                AND j.`del_job` =0
                AND a.`country_id` ={$country_id}
                AND ejr.`active` = 1
                AND (
                    sejr.`escalate_job_reasons_id` != 3 AND
                    sejr.`escalate_job_reasons_id` != 4 AND
                    sejr.`escalate_job_reasons_id` != 5 
                )
                AND a.`agency_id` = {$dist_agency_row->agency_id}
                AND a.deleted = 0
                ");         
                //echo $this->db->last_query();                
                $view_data['escalate_sql'] = $escalate_sql; 
                $escalate_count = $escalate_sql->num_rows();
             
                // view data
                $view_data['agency_portal_link'] = $this->config->item('agency_link');
                $view_data['outgoing_email'] = $country_row->outgoing_email;
                $view_data['agent_number'] = $country_row->agent_number;
                $view_data['agency_name'] = $dist_agency_row->agency_name;          
                $view_data['agency_id'] = $dist_agency_row->agency_id;
                $view_data['spec_agency'] = $spec_agency;        
                  

                $return_as_string =  true;

                // content
                $email_body .= $this->load->view('emails/template/email_header', $view_data, $return_as_string);
                $email_body .= $this->load->view('emails/escalate_email', $view_data, $return_as_string);
                $email_body .= $this->load->view('emails/template/email_footer', $view_data, $return_as_string);

                // subject
                $subject = config_item('company_name_short') . " – We need your help with {$escalate_count} ".( ($escalate_count > 1 )?'Properties':'Property' );
                $to_email = $to_emails_arr;                                                           

                $this->email->to($to_email);                     
                $this->email->cc(make_email('reports'));
                $this->email->subject($subject);
                $this->email->message($email_body);

                // do not send email for franchise group = Image
                //if( $dist_agency_row->franchise_groups_id != $this->image_fg_id ){ 

                    // send email
                    $this->email->send();

                //}
                

            }


        }  

    }



    public function email_tech_runs(){

        $this->load->model('jobs_model');
        $this->load->model('tech_model');

        $country_id = $this->config->item('country');

        // get country data
        $country_params = array(
            'sel_query' => 'c.agent_number, c.outgoing_email, c.`iso`',
            'country_id' => $country_id
        );
        $country_sql = $this->system_model->get_countries($country_params);
        $country_row = $country_sql->row();

        // tommorow
        $tomorrow = date('Y-m-d',strtotime('+1 day'));
        
        $day_txt = date('D');
        $date_filter = '';
        
        if( $day_txt == 'Fri' ){
            // get saturday and monday 
            $date_filter = " ( tr.date = '{$tomorrow}' OR tr.date = '" . date('Y-m-d', (strtotime('+3 days'))) . "' ) ";
        }else{
            $date_filter = " tr.date = '{$tomorrow}' ";
        }
      

        // get tech runs 
        $tr_sql_str = "
        SELECT 
            tr.`tech_run_id`,
            tr.`assigned_tech`,
            tr.`date`,
            tr.`start`,
            tr.`end`,

            sa.`StaffID`,
            sa.`FirstName`,
            sa.`LastName`,
            sa.`Email`
        FROM  `tech_run`  AS tr
        LEFT JOIN `staff_accounts` AS sa ON tr.`assigned_tech` = sa.`StaffID`
        WHERE  {$date_filter}
        AND tr.`country_id` = {$country_id}
        ";
        $tr_sql = $this->db->query($tr_sql_str);                               

        foreach( $tr_sql->result() as $tr_row ){

            // clear
            $email_body = null;

            $tr_id = $tr_row->tech_run_id;
            $tech_name = "{$tr_row->FirstName} {$tr_row->LastName}";
            $tech_Email = $tr_row->Email;
            $tech_run_date = $tr_row->date;
            $view_data['tech_id'] = $tr_row->assigned_tech;
            $view_data['date'] = $tr_row->date;
            

            //get techrun rows
            $tr_sel = "
                trr.`tech_run_rows_id`,
                trr.`row_id_type`,
                trr.`row_id`,
                trr.`hidden`,
                trr.`dnd_sorted`,
                trr.`highlight_color`,
                
                trr_hc.`tech_run_row_color_id`,
                trr_hc.`hex`,
                
                j.`id` AS jid,
                j.`precomp_jobs_moved_to_booked`,
                j.`completed_timestamp`,		

                p.`property_id`,

                a.`agency_id`,
                a.`allow_upfront_billing`
            ";
            $tr_params = array(
                'sel_query' => $tr_sel,
                'sort_list' => array(
                    array(
                        'order_by' => 'trr.sort_order_num',
                        'sort' => 'ASC'
                    )
                )
            );
            $view_data['jr_list2'] = $this->tech_model->getTechRunRows($tr_id, $country_id, $tr_params);
            //echo $this->db->last_query();    

            //get accomodation by tech run start
            $accom_query = $this->db->select('*')->from('accomodation')->where( array('accomodation_id'=> $tr_row->start, 'country_id'=> $country_id) )->get();
            $accom_row = $accom_query->row_array();
            $view_data['accom_name'] = $accom_row['name'];
            $view_data['start_agency_address'] = $accom_row['address'];


            //get accomodation by tech run end
            $accom_query_end = $this->db->select('*')->from('accomodation')->where( array('accomodation_id'=> $tr_row->end, 'country_id'=> $country_id) )->get();
            $end_acco = $accom_query_end->row_array();
            $view_data['end_accom_name'] = $end_acco['name'];
            $view_data['end_agency_address'] = $end_acco['address'];
        
            // view data
            $view_data['agency_portal_link'] = $this->config->item('agency_link');
            $view_data['outgoing_email'] = $country_row->outgoing_email;
            $view_data['agent_number'] = $country_row->agent_number;   
            $view_data['email_body_width'] = '1300px'; 
            $view_data['is_email'] = true; 
            

            $return_as_string =  true;

            // content
            $email_body .= $this->load->view('emails/template/email_header', $view_data, $return_as_string);
            $email_body .= $this->load->view('tech_run/tech_day_schedule_tech_table_list', $view_data, $return_as_string);
            $email_body .= $this->load->view('emails/template/email_footer', $view_data, $return_as_string);

            // subject
            $subject = "Tech Run({$country_row->iso}) - {$tech_name} - ".date('d/m/Y',strtotime($tech_run_date));
            $to_email = $tech_Email;                                                                                                                                        

            $this->email->to($to_email);                     
            $this->email->cc(make_email('operations'));

            $this->email->subject($subject);
            $this->email->message($email_body);

            // send email
            $this->email->send(); 

        }        
        

    }



    public function email_weekly_sales_report(){

        $this->load->model('reports_model');        
        $country_id = $this->config->item('country');  
        $view_data['title'] = "Weekly Sales Report";         
        
         // get country data
         $country_params = array(
            'sel_query' => 'c.agent_number, c.outgoing_email, c.`iso`',
            'country_id' => $country_id
        );
        $country_sql = $this->system_model->get_countries($country_params);
        $country_row = $country_sql->row();
        
        // get staff accounts displayed on weekly sales report
        $params = array( 
            'sel_query' => '
                sa.`StaffID`, 
                sa.`Email`,
                sa.`FirstName`,
                sa.`LastName`
            ', 
            'custom_where' => 'sa.`display_on_wsr` = 1',                        
            'active' => 1,
            'deleted' => 0,
            'display_query' => 0
        );
        
        // get user details
        $user_account_sql = $this->staff_accounts_model->get_staff_accounts($params);   

        // get staff accounts to send weekly sales report to
        $recieve_wsr_params = array( 
            'sel_query' => '
                sa.`StaffID`, 
                sa.`Email`,
                sa.`FirstName`,
                sa.`LastName`
            ', 
            'custom_where' => 'sa.`recieve_wsr` = 1',                        
            'active' => 1,
            'deleted' => 0,
            'display_query' => 0
        );
        
        // get user details
        $recieve_wsr_sql = $this->staff_accounts_model->get_staff_accounts($recieve_wsr_params); 
        $recieve_wsr_email_arr = [];
        foreach( $recieve_wsr_sql->result() as $user_account_row ){
            $recieve_wsr_email_arr[] = $user_account_row->Email;
        } 
        
        // view data
        $view_data['agency_portal_link'] = $this->config->item('agency_link');
        $view_data['outgoing_email'] = $country_row->outgoing_email;
        $view_data['agent_number'] = $country_row->agent_number;   
        $view_data['email_body_width'] = '900px'; 
        $view_data['is_email'] = true; 
      

        foreach( $user_account_sql->result() as $user_account_row ){

            $email_body = null;
            $staff_email = null;

            $staff_id = $user_account_row->StaffID;   
            $staff_email = $user_account_row->Email;                                 
            $view_data['staff_id'] = $staff_id; 

            // content
            $return_as_string =  true;
            $email_body .= $this->load->view('emails/template/email_header', $view_data, $return_as_string);            
            $email_body .= $this->load->view('emails/weekly_sales_report', $view_data, $return_as_string);
            $email_body .= $this->load->view('emails/template/email_footer', $view_data, $return_as_string);

            //echo $email_body;
            
            // subject
            $subject = "Sales Report - {$user_account_row->FirstName}";
            $to_email = $recieve_wsr_email_arr;

            $this->email->to($to_email);                     
            $this->email->cc($staff_email); // CC staff user                                 

            $this->email->subject($subject);
            $this->email->message($email_body);

            // send email
            $this->email->send(); 
            

        }
        

    }



    public function email_weekly_sales_report_reminder(){

        $this->load->model('reports_model');        
        $country_id = $this->config->item('country');  
        $view_data['title'] = "Weekly Sales Report Reminder";          
        
         // get country data
         $country_params = array(
            'sel_query' => 'c.agent_number, c.outgoing_email, c.`iso`',
            'country_id' => $country_id
        );
        $country_sql = $this->system_model->get_countries($country_params);
        $country_row = $country_sql->row();
        
        // get staff accounts
        $params = array( 
            'sel_query' => '
                sa.`StaffID`, 
                sa.`Email`,
                sa.`FirstName`,
                sa.`LastName`
            ', 
            'custom_where' => 'sa.`display_on_wsr` = 1',                        
            'active' => 1,
            'deleted' => 0,
            'display_query' => 0
        );
        
        // get user details
        $user_account_sql = $this->staff_accounts_model->get_staff_accounts($params);   
        
        // view data
        $view_data['agency_portal_link'] = $this->config->item('agency_link');
        $view_data['outgoing_email'] = $country_row->outgoing_email;
        $view_data['agent_number'] = $country_row->agent_number;   
        $view_data['email_body_width'] = '900px'; 
        $view_data['is_email'] = true; 
      

        foreach( $user_account_sql->result() as $user_account_row ){

            $email_body = null;

            $staff_id = $user_account_row->StaffID;
            $staff_email = $user_account_row->Email;
            $staff_name = "{$user_account_row->FirstName} {$user_account_row->LastName}";

            $view_data['staff_id'] = $staff_id; 
            $view_data['staff_name'] = $staff_name; 

            // content
            $return_as_string =  true;
            $email_body .= $this->load->view('emails/template/email_header', $view_data, $return_as_string);            
            $email_body .= $this->load->view('emails/weeky_sales_report_reminder', $view_data, $return_as_string);
            $email_body .= $this->load->view('emails/template/email_footer', $view_data, $return_as_string);

            //echo $email_body;
            
            // subject
            $subject = "Sales report due in 2 hours";
            $to_email = $staff_email;

            $this->email->to($to_email);
            $this->email->subject($subject);
            $this->email->message($email_body);

            // send email
            $this->email->send(); 
            

        }
        

    }



    public function agent_activity($params){

        $this->load->model('jobs_model');

        $country_id = $this->config->item('country');    
        $email_body = null; 

        // get country data
        $country_params = array(
            'sel_query' => 'c.agent_number, c.outgoing_email',
            'country_id' => $country_id
        );
        $country_sql = $this->system_model->get_countries($country_params);
        $country_row = $country_sql->row();

        $from = $params['from'];
        $to = $params['to'];

        // get agencies        
        $sql_str = "
        SELECT DISTINCT(a.`agency_id`), a.`agency_name`
        FROM `properties_tracked` AS pt 
        LEFT JOIN `property` AS p ON pt.`property_id` = p.`property_id`
        LEFT JOIN `agency` AS a ON p.`agency_id` = a.`agency_id`
        WHERE pt.`date` BETWEEN '{$from}' AND '{$to}'
        ORDER BY a.`agency_name` ASC
        ";        
        $dist_agency_sql = $this->db->query($sql_str);                
        
        // view data
        $view_data['dist_agency_sql'] = $dist_agency_sql;         
        $view_data['from'] = $from;
        $view_data['to'] = $to;
        $view_data['country_id'] = $country_id;

        $view_data['agency_portal_link'] = $this->config->item('agency_link');
        $view_data['outgoing_email'] = $country_row->outgoing_email;
        $view_data['agent_number'] = $country_row->agent_number;
        $view_data['email_body_width'] = '1230px';
                            
        $return_as_string =  true;

        // content
        $email_body .= $this->load->view('emails/template/email_header', $view_data, $return_as_string);
        $email_body .= $this->load->view('emails/agent_activity', $view_data, $return_as_string);
        $email_body .= $this->load->view('emails/template/email_footer', $view_data, $return_as_string);

        //echo $email_body;

        // subject
        $subject = $params['subject'];
        $to_email = make_email('sales');

        $this->email->to($to_email);
        $this->email->subject($subject);
        $this->email->message($email_body);

        // send email
        $this->email->send(); 
        

    }



    public function send_letters_email_to_agency($params){

        $country_id = $this->config->item('country');  
        
        // clear per agency
        $to_emails_arr = [];   
        $email_body = null;
        
        $agency_emails_imp = explode("\n",trim($params['agency_emails'])); // split emails by new line

        // only add sanitized email on array
        foreach($agency_emails_imp as $agency_email){              

            $agency_email2 = preg_replace('/\s+/', '', $agency_email);
            if(filter_var($agency_email2, FILTER_VALIDATE_EMAIL)){
                $to_emails_arr[] = $agency_email2;
            }	                 
                        
        }
        
        // get country data
        $country_params = array(
            'sel_query' => 'c.agent_number, c.outgoing_email',
            'country_id' => $country_id
        );
        $country_sql = $this->system_model->get_countries($country_params);
        $country_row = $country_sql->row();

        $view_data['paddress'] = $params['paddress'];
        $view_data['agency_name'] = $params['agency_name'];

        $view_data['agency_portal_link'] = $this->config->item('agency_link');
        $view_data['outgoing_email'] = $country_row->outgoing_email;
        $view_data['agent_number'] = $country_row->agent_number;
        //$view_data['email_body_width'] = '1230px';
                            
        $return_as_string =  true;

        // content
        $email_body .= $this->load->view('emails/template/email_header', $view_data, $return_as_string);
        $email_body .= $this->load->view('emails/send_letters_email_to_agency', $view_data, $return_as_string);
        $email_body .= $this->load->view('emails/template/email_footer', $view_data, $return_as_string);

        // subject
        $subject = "Tenant Notification {$params['paddress']}";
        $to_email = $to_emails_arr;

        $this->email->to($to_email);
        $this->email->subject($subject);
        $this->email->message($email_body);

        // do not send email for franchise group = Image
        //if( $params['franchise_groups_id'] != $this->image_fg_id ){

            // send email
            $this->email->send();

        //}
        

    }


    public function send_letters_email_to_agency_no_tenants($params){

        $country_id = $this->config->item('country');  
        
        // clear per agency
        $to_emails_arr = [];   
        $email_body = null;
        
        $agency_emails_imp = explode("\n",trim($params['agency_emails'])); // split emails by new line

        // only add sanitized email on array
        foreach($agency_emails_imp as $agency_email){              

            $agency_email2 = preg_replace('/\s+/', '', $agency_email);
            if(filter_var($agency_email2, FILTER_VALIDATE_EMAIL)){
                $to_emails_arr[] = $agency_email2;
            }	                 
                        
        }
        
        // get country data
        $country_params = array(
            'sel_query' => 'c.agent_number, c.outgoing_email',
            'country_id' => $country_id
        );
        $country_sql = $this->system_model->get_countries($country_params);
        $country_row = $country_sql->row();

        $view_data['paddress'] = $params['paddress'];
        $view_data['agency_name'] = $params['agency_name'];

        $view_data['agency_portal_link'] = $this->config->item('agency_link');
        $view_data['outgoing_email'] = $country_row->outgoing_email;
        $view_data['agent_number'] = $country_row->agent_number;
        //$view_data['email_body_width'] = '1230px';
                            
        $return_as_string =  true;

        // content
        $email_body .= $this->load->view('emails/template/email_header', $view_data, $return_as_string);
        $email_body .= $this->load->view('emails/send_letters_email_to_agency_no_tenants', $view_data, $return_as_string);
        $email_body .= $this->load->view('emails/template/email_footer', $view_data, $return_as_string);

        // subject
        $subject = "Ready for Booking {$params['paddress']}";
        $to_email = $to_emails_arr;

        $this->email->to($to_email);
        $this->email->subject($subject);
        $this->email->message($email_body);

        // do not send email for franchise group = Image
        //if( $params['franchise_groups_id'] != $this->image_fg_id ){

            // send email
            $this->email->send();

        //}
        

    }


    public function send_letters_email_to_agency_no_tenants_escalate($params){

        $country_id = $this->config->item('country');  
        
        // clear per agency
        $to_emails_arr = [];   
        $email_body = null;
        
        $agency_emails_imp = explode("\n",trim($params['agency_emails'])); // split emails by new line

        // only add sanitized email on array
        foreach($agency_emails_imp as $agency_email){              

            $agency_email2 = preg_replace('/\s+/', '', $agency_email);
            if(filter_var($agency_email2, FILTER_VALIDATE_EMAIL)){
                $to_emails_arr[] = $agency_email2;
            }	                 
                        
        }
        
        // get country data
        $country_params = array(
            'sel_query' => 'c.agent_number, c.outgoing_email',
            'country_id' => $country_id
        );
        $country_sql = $this->system_model->get_countries($country_params);
        $country_row = $country_sql->row();

        $view_data['paddress'] = $params['paddress'];
        $view_data['agency_name'] = $params['agency_name'];

        $view_data['agency_portal_link'] = $this->config->item('agency_link');
        $view_data['outgoing_email'] = $country_row->outgoing_email;
        $view_data['agent_number'] = $country_row->agent_number;
        //$view_data['email_body_width'] = '1230px';
                            
        $return_as_string =  true;

        // content
        $email_body .= $this->load->view('emails/template/email_header', $view_data, $return_as_string);
        $email_body .= $this->load->view('emails/send_letters_email_to_agency_no_tenants_escalate', $view_data, $return_as_string);
        $email_body .= $this->load->view('emails/template/email_footer', $view_data, $return_as_string);

        // subject
        $subject = "Ready for Booking {$params['paddress']}";
        $to_email = $to_emails_arr;

        $this->email->to($to_email);
        $this->email->subject($subject);
        $this->email->message($email_body);

        // do not send email for franchise group = Image
        //if( $params['franchise_groups_id'] != $this->image_fg_id ){

            // send email
            $this->email->send();

        //}
        

    }


    public function send_letters_email_to_tenants($params){

        $country_id = $this->config->item('country');     
        $to_emails_arr = $params['tenant_email_arr'];    
        
        // clear per agency        
        $email_body = null;

        
        // get country data
        $country_params = array(
            'sel_query' => '
                c.`agent_number`, 
                c.`outgoing_email`,
                c.`tenant_number`,
                c.`trading_name`
            ',
            'country_id' => $country_id
        );
        $country_sql = $this->system_model->get_countries($country_params);
        $country_row = $country_sql->row();

        $view_data['paddress'] = $params['paddress'];
        $view_data['agency_name'] = $params['agency_name'];
        $view_data['comb_tenant_names'] = $params['comb_tenant_names'];
        $view_data['service_type'] = $params['service_type'];
        $view_data['send_letter_date'] = date("F d, Y");
        $view_data['tenant_welcome_txt'] = $params['tenant_welcome_txt'];

        $view_data['agency_portal_link'] = $this->config->item('agency_link');
        $view_data['outgoing_email'] = $country_row->outgoing_email;
        $view_data['tenant_number'] = $country_row->tenant_number;
        $view_data['trading_name'] = $country_row->trading_name;
        $view_data['show_tenant_number'] = true; //show tenant number in header instead of agency number
        //$view_data['email_body_width'] = '1230px';
                            
        $return_as_string =  true;

        // content
        $email_body .= $this->load->view('emails/template/email_header', $view_data, $return_as_string);
        $email_body .= $this->load->view('emails/send_letters_email_to_tenants', $view_data, $return_as_string);
        $email_body .= $this->load->view('emails/template/email_footer', $view_data, $return_as_string);

        // subject
        $subject = "Tenant Notification {$params['paddress']}";
        $to_email = $to_emails_arr;
		
		//get vCard filepath
		$vcard_attachment_file = get_vcard();
		
        $this->email->to($to_email);
		$this->email->attach($vcard_attachment_file);
        $this->email->subject($subject);
        $this->email->message($email_body);

	    // Attempt to send email
	    if ($this->email->send()) {
		    // Email sent successfully, log a success message
		    log_message('info', 'Email sent successfully.');
	    } else {
		    // Email sending failed, log an error message
		    log_message('error', 'Email sending failed: ' . $this->email->print_debugger());
	    }
    }



    public function agency_compliance(){

        $this->load->model('jobs_model');
        $this->load->model('properties_model');

        $country_id = $this->config->item('country');
        $job_status = 'Booked';
        $day = date('D');
        $is_friday = false;
        $spec_agency = array(1328);

        $next_day = date('Y-m-d', strtotime('+1 days'));
        $nexy_3_days = date('Y-m-d', strtotime('+3 days'));

        // get country data
        $country_params = array(
            'sel_query' => 'c.agent_number, c.outgoing_email',
            'country_id' => $country_id
        );
        $country_sql = $this->system_model->get_countries($country_params);
        $country_row = $country_sql->row();

       
        // only on NSW and ACT state
        $custom_where = "
        (
            p.`state` = 'NSW' 
            OR p.`state` = 'ACT'
        )
        "; 

        // distinct agency
        $sel_query = "
            DISTINCT (
                a.`agency_id`
            ), a.`agency_name`
        ";
        $params = array(
            'sel_query' => $sel_query,                                                                
            'p_deleted' => 0,
            'a_status' => 'active',
            'custom_where' => $custom_where,

            'sort_list' => array(
                array(
                    'order_by' => 'p.`address_2`',
                    'sort' => 'ASC',
                )
            ),
                        
            'display_query' => 0
        );
        $dist_agency_sql = $this->properties_model->get_properties($params);

        
        // loop per agency
        foreach( $dist_agency_sql->result() as $dist_agency_row ){

            // clear per agency
            $to_emails_arr = [];   
            $email_body = null;
            
            $agency_emails_imp = explode("\n",trim($dist_agency_row->agency_emails)); // split emails by new line

            // only add sanitized email on array
            foreach($agency_emails_imp as $agency_email){              

                $agency_email2 = preg_replace('/\s+/', '', $agency_email);
                if(filter_var($agency_email2, FILTER_VALIDATE_EMAIL)){
                    $to_emails_arr[] = $agency_email2;
                }	                 
                            
            }

           
            if( $dist_agency_row->agency_id > 0 ){

                // get compliance properties                
                $sel_query = "
                    p.property_id,                         
                    p.`address_1` AS p_address_1,
                    p.`address_2` AS p_address_2,
                    p.`address_3` AS p_address_3,                                       
                ";
                $params = array(
                    'sel_query' => $sel_query,     
                    'agency_filter' => $dist_agency_row->agency_id,                                                           
                    'p_deleted' => 0,
                    'a_status' => 'active',
                    'custom_where' => $custom_where,

                    'sort_list' => array(
                        array(
                            'order_by' => 'p.`address_2`',
                            'sort' => 'ASC',
                        )
                    ),
                                
                    'display_query' => 0
                );
                $compliance_sql = $this->properties_model->get_properties($params);
                               
                $view_data['compliance_sql'] = $compliance_sql; 
                $compliance_count = $compliance_sql->num_rows();
             
                // view data
                $view_data['agency_portal_link'] = $this->config->item('agency_link');
                $view_data['outgoing_email'] = $country_row->outgoing_email;
                $view_data['agent_number'] = $country_row->agent_number;
                $view_data['agency_name'] = $dist_agency_row->agency_name;          
                $view_data['agency_id'] = $dist_agency_row->agency_id;
                $view_data['spec_agency'] = $spec_agency;        
                $view_data['email_body_width'] = '1230px';  

                $return_as_string =  false;

                // content
                $email_body .= $this->load->view('emails/template/email_header', $view_data, $return_as_string);
                $email_body .= $this->load->view('emails/agency_compliance', $view_data, $return_as_string);
                $email_body .= $this->load->view('emails/template/email_footer', $view_data, $return_as_string);

                // subject
                $subject = config_item('company_name_short') . " – We need compliance help with {$compliance_count} ".( ($compliance_count > 1 )?'Properties':'Property' );
                $to_email = $to_emails_arr;

                $this->email->to($to_email);                     
//                $this->email->bcc(make_email('bcc'));

                $this->email->subject($subject);
                $this->email->message($email_body);

                // send email
                //$this->email->send();
                

            }
           


        }  
        
        

    }



    public function email_completed_ic_ugprade(){

        $this->load->model('reports_model');        
        $country_id = $this->config->item('country');  
        
        $today = date('d/m/Y');
        $subject_txt = "QLD Upgraded as at {$today}"; 
        
         // get country data
         $country_params = array(
            'sel_query' => 'c.agent_number, c.outgoing_email, c.`iso`',
            'country_id' => $country_id
        );
        $country_sql = $this->system_model->get_countries($country_params);
        $country_row = $country_sql->row();

        $job_status = 'Completed';
        $view_data['title'] = $subject_txt; 

        $sel_query = "
        j.`id` AS jid,
        j.`status` AS j_status,
        j.`service` AS j_service,
        j.`created` AS j_created,
        j.`date` AS j_date,
        j.`comments` AS j_comments,
        j.`job_price` AS j_price,
        j.`job_type` AS j_type,
        j.`assigned_tech`,
        
        p.`property_id` AS prop_id, 
        p.`address_1` AS p_address_1, 
        p.`address_2` AS p_address_2, 
        p.`address_3` AS p_address_3,
        p.`state` AS p_state,
        p.`postcode` AS p_postcode,
        p.`comments` AS p_comments, 
        p.`created` AS p_created,
        j.`invoice_amount`,
        
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
        
        ajt.`id` AS ajt_id,
        ajt.`type` AS ajt_type
        ";

        // filter by job type = IC Upgrade, only show property created before 2020-11-01 and filter by current month
        $first_day_of_month = date('Y-m-01'); 
        $last_day_of_month = date('Y-m-t'); 

        $custom_where = "
            j.`job_type` = 'IC Upgrade' 
            AND Date(p.`created`) < '2020-11-01'
            AND j.`date` BETWEEN '{$first_day_of_month}' AND '{$last_day_of_month}'
        ";
       
        $params = array(
            'sel_query' => $sel_query,
            'custom_where' => $custom_where,
            //'p_deleted' => 0,
            //'a_status' => 'active',
            //'del_job' => 0,
            'country_id' => $country_id,    
            'job_status' => $job_status,        
            'join_table' => array('job_type','alarm_job_type'),
            
            'sort_list' => array(
                array(
                    'order_by' => 'j.created',
                    'sort' => 'ASC',
                ),
            ),
            'display_query' => 0
        );

        $view_data['lists'] = $this->jobs_model->get_jobs($params);

        // all rows
        $sel_query = "COUNT(j.`id`) AS jcount, SUM(`invoice_amount`) AS invoice_amount_tot";
        $params = array(
            'sel_query' => $sel_query,
            'custom_where'=> $custom_where,
            //'p_deleted' => 0,
            //'a_status' => 'active',
            //'del_job' => 0,
            'country_id' => $country_id,
            'job_status' => $job_status,
            'join_table' => array('job_type','alarm_job_type')
        );

        $job_sql = $this->jobs_model->get_jobs($params);
        $job_row = $job_sql->row();

        $total_rows = $job_row->jcount;
        $invoice_amount_tot = $job_row->invoice_amount_tot;

        $view_data['total_rows'] = $total_rows;
        $view_data['invoice_amount_tot'] = $invoice_amount_tot;

        // view data
        $view_data['agency_portal_link'] = $this->config->item('agency_link');
        $view_data['outgoing_email'] = $country_row->outgoing_email;
        $view_data['agent_number'] = $country_row->agent_number;   
        $view_data['email_body_width'] = '1200px'; 
        $view_data['is_email'] = true; 
                  
        // content
        $email_body = null;
        $return_as_string =  true;
        $email_body .= $this->load->view('emails/template/email_header', $view_data, $return_as_string);            
        $email_body .= $this->load->view('emails/completed_ic_upgrade', $view_data, $return_as_string);
        $email_body .= $this->load->view('emails/template/email_footer', $view_data, $return_as_string);

        echo $email_body;
        
        // subject
        $subject = $subject_txt;
        $to_email = 'jeremy@battensgroup.com.au';                                                                                                                                      

        $this->email->to($to_email);
        $this->email->subject($subject);
        $this->email->message($email_body);

        // send email
        $this->email->send(); 
        

    }




    public function send_qld_upgrade_report(){

        $this->load->model('reports_model');        
        $country_id = $this->config->item('country'); 

        $today = date('d/m/Y');
        $subject_txt = "QLD Pending Upgrades as at {$today}"; 

        $view_data['title'] = $subject_txt;                  
        
         // get country data
         $country_params = array(
            'sel_query' => 'c.agent_number, c.outgoing_email, c.`iso`',
            'country_id' => $country_id
        );
        $country_sql = $this->system_model->get_countries($country_params);
        $country_row = $country_sql->row();     
            
        // view data
        $view_data['agency_portal_link'] = $this->config->item('agency_link');
        $view_data['outgoing_email'] = $country_row->outgoing_email;
        $view_data['agent_number'] = $country_row->agent_number;   
        //$view_data['email_body_width'] = '900px'; 
        $view_data['is_email'] = true; 

        // get QLD property that needs upgrade
        $qld_upgrade_sql_str = "
            SELECT COUNT(p.`property_id`) AS p_count
            FROM `property` AS p
            WHERE p.`qld_new_leg_alarm_num` > 0
            AND p.`prop_upgraded_to_ic_sa` != 1  
            AND p.`state` = 'QLD'  
            AND p.`deleted` = 0      
            AND ( p.`is_nlm` = 0 OR p.`is_nlm` IS NULL )          
        ";
        $qld_upgrade_sql = $this->db->query($qld_upgrade_sql_str);
        $view_data['qld_prop_upgrade_count'] = $qld_upgrade_sql->row()->p_count;

        // fixed; computed by Ben 
        //$view_data['average_inv_amount'] = 854.02;   
        //$view_data['average_inv_amount'] = 650; 
        $view_data['average_inv_amount'] = 500; 

        // content
        $return_as_string = true;
        $email_body = null;

        $email_body .= $this->load->view('emails/template/email_header_qld_upgrade', $view_data, $return_as_string);            
        $email_body .= $this->load->view('emails/send_qld_upgrade_report', $view_data, $return_as_string);
        $email_body .= $this->load->view('emails/template/email_footer_qld_upgrade', $view_data, $return_as_string);

        //echo $email_body;
        
        // subject
        $subject = $subject_txt;
        $to_email = array('danielk@sats.com.au', 'amberm@sats.com.au', 'shaquilles@sats.com.au');  

        $this->email->to($to_email);                                             
        $this->email->subject($subject);
        $this->email->message($email_body);

        // send email
        $this->email->send(); 
    }


    public function mark_job_not_completed_email($job_id){
        
        $country_id = $this->config->item('country');             
        
        // clear per agency        
        $email_body = null;
        
        // get country data
        $country_params = array(
            'sel_query' => '
                c.`agent_number`, 
                c.`outgoing_email`,
                c.`tenant_number`,
                c.`trading_name`
            ',
            'country_id' => $country_id
        );
        $country_sql = $this->system_model->get_countries($country_params);
        $country_row = $country_sql->row();
    
        // get jobs data
        $job_sql = $this->db->query("
            SELECT 
                j.`id` AS jid,
    
                p.`property_id`,
                p.`address_1` AS p_address_1,
                p.`address_2` AS p_address_2,
                p.`address_3` AS p_address_3,
                p.`state` AS p_state,
                p.`postcode` AS p_postcode
        FROM `jobs` AS j
        LEFT JOIN `property` AS p ON j.`property_id` = p.`property_id`
        WHERE j.`id` = '{$job_id}'
        ");
        $job_row = $job_sql->row();
        $property_id = $job_row->property_id;        
        $p_address = "{$job_row->p_address_1} {$job_row->p_address_2} {$job_row->p_address_3}";
    
    
        $view_data['agency_portal_link'] = $this->config->item('agency_link');
        $view_data['outgoing_email'] = $country_row->outgoing_email;
        $view_data['tenant_number'] = $country_row->tenant_number;
        $view_data['trading_name'] = $country_row->trading_name;
        //$view_data['email_body_width'] = '1230px';
                            
        $return_as_string =  true;
    
        // mail
        $view_data['p_address'] = $p_address;
        $view_data['property_id'] = $property_id;
    
        // content
        $email_body .= $this->load->view('emails/template/email_header', $view_data, $return_as_string);
        $email_body .= $this->load->view('emails/refused_entry_email', $view_data, $return_as_string);
        $email_body .= $this->load->view('emails/template/email_footer', $view_data, $return_as_string);        
    
        // subject
        $subject = "Refused Entry";
        $to_email = make_email('noshow');

        $this->email->to($to_email);
        $this->email->subject($subject);
        $this->email->message($email_body);
    
        // send email
        $this->email->send(); 
        
    
    }



    public function send_email_to_agency_using_template($params){

        $this->load->model('email_model');

        $job_id = $params['job_id'];
        $email_type = $params['email_type'];
        $country_id = $this->config->item('country');  
        $staff_id =  $this->session->staff_id;
        
        // clear 
        $to_emails_arr = [];   
        $email_body = null;   
         
        $now = date('Y-m-d H:i:s');        

        if( $job_id > 0 &&  $email_type > 0 ){

            // get job data
            $sel_query = "
            j.`id` AS jid,
            a.`agency_emails`,
            a.`franchise_groups_id`
            ";
            
            $params = array(
                'sel_query' => $sel_query,
                'del_job' => 0,
                'p_deleted' => 0,
                'a_status' => 'active',          
                'country_id' => $country_id, 
                'job_id' => $job_id,                           
                'display_query' => 0
            );
            $job_sql = $this->jobs_model->get_jobs($params);
            $job_row = $job_sql->row();
            $agency_emails = $job_row->agency_emails; // get agency email
            $franchise_groups_id = $job_row->franchise_groups_id;

            $agency_emails_exp = explode("\n",trim($agency_emails)); // split emails by new line

            // only add sanitized email on array
            foreach($agency_emails_exp as $agency_email){              

                $agency_email2 = preg_replace('/\s+/', '', $agency_email);
                if(filter_var($agency_email2, FILTER_VALIDATE_EMAIL)){
                    $to_emails_arr[] = $agency_email2;
                }	                 
                            
            }

            // call joseph's email model for getting and parsing template
            $total_params = array(
                'echo_query' => 0,
                'email_templates_id' => $email_type
            );
            $email_temp_sql = $this->email_model->get_email_templates($total_params);               

            if( $email_temp_sql->num_rows() > 0 ){

                $email_temp_row = $email_temp_sql->row();

                $email_temp_params = array('job_id' => $job_id);
                $subject_parsed = $this->email_model->parseEmailTemplateTags($email_temp_params, $email_temp_row->subject);        
                $body_parsed = $this->email_model->parseEmailTemplateTags($email_temp_params, $email_temp_row->body);                                             
                
                // get country data
                $country_params = array(
                    'sel_query' => 'c.agent_number, c.outgoing_email',
                    'country_id' => $country_id
                );
                $country_sql = $this->system_model->get_countries($country_params);
                $country_row = $country_sql->row();

                $view_data['paddress'] = $params['paddress'];
                $view_data['agency_name'] = $params['agency_name'];

                $view_data['agency_portal_link'] = $this->config->item('agency_link');
                $view_data['outgoing_email'] = $country_row->outgoing_email;
                $view_data['agent_number'] = $country_row->agent_number;
                //$view_data['email_body_width'] = '1230px';
                                    
                $return_as_string =  true;

                // content
                $email_body .= $this->load->view('emails/template/email_header', $view_data, $return_as_string);
                $email_body .= nl2br($body_parsed);
                $email_body .= $this->load->view('emails/template/email_footer', $view_data, $return_as_string);             

                // subject
                $subject = $subject_parsed;
                $to_email = $to_emails_arr;

                $this->email->to($to_email);
                $this->email->subject($subject);
                $this->email->message($email_body);

                // do not send email for franchise group = Image
                //if( $franchise_groups_id != $this->image_fg_id ){

                    // send email
                    if( $this->email->send() ){

                        // insert job logs            
                        $log_title = 78; // Email Sent                                
                        $job_log = "Email sent to: <b>{$agency_emails}</b> Click <a class='sent_email_alink' href='javascript:void(0);'><b>HERE</b></a> to view email content";
                                            
                        $log_params = array(
                            'title' => $log_title,
                            'details' => $job_log,
                            'display_in_vjd' => 1,
                            'created_by_staff' => $staff_id,
                            'job_id' => $job_id
                        );
                        $this->system_model->insert_log($log_params); 
                        $log_id = $this->db->insert_id();

                        if( $log_id > 0 ){

                            // capture email sent
                            $data = array(
                                'log_id' => $log_id,
                                'from_email' => make_email('info'),
                                'to_email' => $agency_emails,                            
                                'subject' => $subject,
                                'email_body' => $body_parsed,
                                'date_created' => $now,
                            );
                            
                            $this->db->insert('email_templates_sent', $data);

                        }

                    }     
                
                //}

            }

        }        
        

    }


    public function send_email_using_template($params){

        $this->load->model('email_model');

        $job_id = $params['job_id'];
        $agency_id = $params['agency_id'];

        $from = $params['from'];
        $to = $params['to'];
        $cc = $params['cc'];
        $subject = $params['subject'];
        $body = $params['body'];
        $email_type_id = $params['email_type_id'];

        $attach_invoice = $params['attach_invoice'];
        $attach_cert = $params['attach_cert'];
        $attach_combined = $params['attach_combined'];     
        $brooks_quote = $params['brooks_quote'];
        $economical_quote = $params['economical_quote'];
        $cavius_quote = $params['cavius_quote'];
        $combined_quote = $params['combined_quote'];  
        $attach_mark_as_copy = $params['attach_mark_as_copy'];
        $is_copy = ( $attach_mark_as_copy == 1 )?true:false;

        $custom_attach_file = $params['custom_attach_file'];
        
        $country_id = $this->config->item('country');  
        $staff_id =  $this->session->staff_id;      
        
        // clear          
        $email_body = null;       
        
        $now = date('Y-m-d H:i:s');

        if( $job_id > 0  ){    
            
            // append checkdigit to job id for new invoice number
            $check_digit = $this->gherxlib->getCheckDigit(trim($job_id));
            $bpay_ref_code = "{$job_id}{$check_digit}";
            
            $job_details =  $this->job_functions_model->getJobDetails2($job_id);

            # Alarm Details
            $alarm_details = [];
            if (in_array($job_details['jservice'], Alarm_job_type_model::SMOKE_ALARM_IDS)) {
                $alarm_details = $this->alarm_functions_model->getPropertyAlarms($job_id, 1, 0, $job_details['jservice']);
            }
            $num_alarms = sizeof($alarm_details);

            # Property + Agent Details
            $property_details = $this->functions_model->getPropertyAgentDetails($job_details['property_id']);
                    
            $email_temp_params = array('job_id' => $job_id);
            $subject_parsed = $this->email_model->parseEmailTemplateTags($email_temp_params, $subject);        
            $body_parsed = $this->email_model->parseEmailTemplateTags($email_temp_params, $body);                                             
            
            // get country data
            $country_params = array(
                'sel_query' => 'c.agent_number, c.outgoing_email',
                'country_id' => $country_id
            );
            $country_sql = $this->system_model->get_countries($country_params);
            $country_row = $country_sql->row();

            $view_data['paddress'] = $params['paddress'];
            $view_data['agency_name'] = $params['agency_name'];

            $view_data['agency_portal_link'] = $this->config->item('agency_link');
            $view_data['outgoing_email'] = $country_row->outgoing_email;
            $view_data['agent_number'] = $country_row->agent_number;
            //$view_data['email_body_width'] = '1230px';
                                
            $return_as_string =  true;

            // content
            $email_body .= $this->load->view('emails/template/email_header', $view_data, $return_as_string);
            $email_body .= nl2br($body_parsed);
            $email_body .= $this->load->view('emails/template/email_footer', $view_data, $return_as_string);             

            // subject
            $subject = $subject_parsed;
               
            // TO
            $to_exp = explode(";",$to);
            $to_email = [];
            foreach(  $to_exp as $to_exp_val ){
                if( filter_var( trim($to_exp_val), FILTER_VALIDATE_EMAIL ) ){ // validate email
                    $to_email[] = $to_exp_val;
                }						
            }   
            
            // CC
            $cc_exp = explode(";",$cc);
            $cc_email = [];
            foreach(  $cc_exp as $cc_exp_val ){
                if( filter_var( trim($cc_exp_val), FILTER_VALIDATE_EMAIL ) ){ // validate email
                    $cc_email[] = $cc_exp_val;
                }						
            }

            $this->email->to($to_email);  
            if( count($cc_email) > 0 ){ // CC
                $this->email->cc($cc_email);  
            }                                                    

            $this->email->subject($subject);
            $this->email->message($email_body);

            if( $attach_invoice == 1 ){ // invoice
               
               $invoice_pdf = $this->pdf_template->pdf_invoice_template($job_id, $job_details, $property_details, $alarm_details, $num_alarms, $country_id,null,$is_copy);                           
               $this->email->attach($invoice_pdf, 'attachment', 'invoice' . $bpay_ref_code . '.pdf', 'application/pdf');
           
            }
            
            if( $attach_cert == 1 ) { // certificate                              

               //$cert_pdf = $this->pdf_template->pdf_certificate_template($job_id, $job_details, $property_details, $alarm_details, $num_alarms, $country_id,null,$is_copy);                                                    ;
               $cert_pdf = $this->pdf_template->pdf_certificate_template_v2($job_id, $job_details, $property_details, $alarm_details, $num_alarms, $country_id,null,$is_copy);                                                    ;
               $this->email->attach($cert_pdf, 'attachment', 'cert' . $bpay_ref_code . '.pdf', 'application/pdf');
               
            }
            
            if( $attach_combined == 1 ) { // combined
                              
                //$combined_invoice = $this->pdf_template->pdf_combined_template($job_id, $job_details, $property_details, $alarm_details, $num_alarms, $country_id,null,$is_copy);
                $combined_invoice = $this->pdf_template->pdf_combined_template_v2($job_id, $job_details, $property_details, $alarm_details, $num_alarms, $country_id,null,$is_copy);
                $this->email->attach($combined_invoice, 'attachment', 'invoice_cert_' . $bpay_ref_code . '.pdf', 'application/pdf');

            }

            if( $brooks_quote == 1 ) { // brooks quote
                          
                $qt = 'brooks';
                $brooks_quote_pdf = $this->pdf_template->pdf_quote_template_v2($job_id, $job_details, $property_details, $alarm_details, $num_alarms, $country_id,null,$is_copy,$qt);
                $this->email->attach($brooks_quote_pdf, 'attachment', 'brooks_quote_' . $bpay_ref_code . '.pdf', 'application/pdf');

            }

            if( $economical_quote == 1 ) { // economical/emerald quote

                $qt = 'emerald';
                $economical_quote_pdf = $this->pdf_template->pdf_quote_template_v2($job_id, $job_details, $property_details, $alarm_details, $num_alarms, $country_id,null,$is_copy,$qt);
                $this->email->attach($economical_quote_pdf, 'attachment', 'economical_quote_' . $bpay_ref_code . '.pdf', 'application/pdf');

            }

            if( $cavius_quote == 1 ) { // brooks quote
                          
                $qt = 'cavius';
                $cavius_quote_pdf = $this->pdf_template->pdf_quote_template($job_id, $job_details, $property_details, $alarm_details, $num_alarms, $country_id,null,$is_copy,$qt);
                $this->email->attach($cavius_quote_pdf, 'attachment', 'cavius_quote_' . $bpay_ref_code . '.pdf', 'application/pdf');

            }


            if( $combined_quote == 1 ) { // brooks quote
                          
                
                //$cavius_quote_pdf = $this->pdf_template->pdf_quote_template($job_id, $job_details, $property_details, $alarm_details, $num_alarms, $country_id,null,$is_copy,$qt);
                
                $pdf_name = 'combined_quotes_pdf_' . $bpay_ref_code.rand().date('YmdHis') . '.pdf';
                $pdf_output = 'S'; // return the document as a string.

                /*
                $combined_quotes_pdf_params = array(
                    'job_id' => $job_id,
                    'job_details' => $job_details,
                    'property_details' => $property_details,                    
                    'pdf_name' => $pdf_name,
                    'pdf_output' => $pdf_output
                );
                $combined_quote_pdf = $this->pdf_template->pdf_combined_quote_template($combined_quotes_pdf_params);
                */

                $combined_quotes_pdf_params = array(
                    'job_id' => $job_id,
                    'job_details' => $job_details,
                    'property_details' => $property_details,                    
                    'pdf_name' => $pdf_name,
                    'output' => $pdf_output
                );
                $combined_quote_pdf = $this->pdf_template->combined_qoutes($combined_quotes_pdf_params);
                
                $this->email->attach($combined_quote_pdf, 'attachment',  $pdf_name, 'application/pdf');

            }

            // custom attachment
            if( $custom_attach_file != '' ){
                $this->email->attach($custom_attach_file);
            }
           

            // send email
            if( $this->email->send() ){

                // delete temporary custom attach file
                if( $custom_attach_file != '' ){
                    unlink($custom_attach_file);
                }

                // insert job logs            
                $log_title = 78; // Email Sent

                if( $cc !='' ){
                    $cc_append_str = " and CC to: <b>{$cc}</b> ";
                }
                              
                $job_log = "Email sent to: <b>{$to}</b>{$cc_append_str} Click <a class='sent_email_alink' href='javascript:void(0);'><b>HERE</b></a> to view email content";
                                        
                $log_params = array(
                    'title' => $log_title,
                    'details' => $job_log,
                    'display_in_vjd' => 1,
                    'created_by_staff' => $staff_id,
                    'job_id' => $job_id
                );
                $this->system_model->insert_log($log_params); 
                $log_id = $this->db->insert_id();

                if( $log_id > 0 ){

                    // capture email sent
                    // email_type_id insert added on sept 24, 2021
                    $data = array(
                        'log_id' => $log_id,
                        'from_email' => $from,
                        'to_email' => $to,
                        'cc_email' => $cc,
                        'subject' => $subject,
                        'email_body' => $body_parsed,
                        'date_created' => $now,
                        'email_type_id' => $email_type_id
                    );
                    
                    $this->db->insert('email_templates_sent', $data);

                }
                

            }

            
        
        }elseif($agency_id > 0){

            $email_temp_params = array('agency_id' => $agency_id);
            $subject_parsed = $this->email_model->parseEmailTemplateTags($email_temp_params, $subject);        
            $body_parsed = $this->email_model->parseEmailTemplateTags($email_temp_params, $body); 
            
            // get country data
            $country_params = array(
                'sel_query' => 'c.agent_number, c.outgoing_email',
                'country_id' => $this->config->item('country')
            );
            $country_sql = $this->system_model->get_countries($country_params);
            $country_row = $country_sql->row();

            $view_data['paddress'] = $params['paddress'];
            $view_data['agency_name'] = $params['agency_name'];

            $view_data['agency_portal_link'] = $this->config->item('agency_link');
            $view_data['outgoing_email'] = $country_row->outgoing_email;
            $view_data['agent_number'] = $country_row->agent_number;

            $return_as_string =  true;

            // content
            $email_body .= $this->load->view('emails/template/email_header', $view_data, $return_as_string);
            $email_body .= nl2br($body_parsed);
            $email_body .= $this->load->view('emails/template/email_footer', $view_data, $return_as_string);             

            // subject
            $subject = $subject_parsed;

            // TO
            $to_exp = explode(";",$to);
            $to_email = [];
            foreach(  $to_exp as $to_exp_val ){
                if( filter_var( trim($to_exp_val), FILTER_VALIDATE_EMAIL ) ){ // validate email
                    $to_email[] = $to_exp_val;
                }						
            }   
              
            // CC
            $cc_exp = explode(";",$cc);
            $cc_email = [];
            foreach(  $cc_exp as $cc_exp_val ){
                if( filter_var( trim($cc_exp_val), FILTER_VALIDATE_EMAIL ) ){ // validate email
                    $cc_email[] = $cc_exp_val;
                }						
            } 

            $this->email->to($to_email);  
            if( count($cc_email) > 0 ){ // CC
                $this->email->cc($cc_email);  
            }                                                    

            $this->email->subject($subject);
            $this->email->message($email_body);

            // custom attachment
            if( $custom_attach_file != '' ){
                $this->email->attach($custom_attach_file);
            }

            // send email
            if( $this->email->send() ){

                // delete temporary custom attach file
                if( $custom_attach_file != '' ){
                    unlink($custom_attach_file);
                }

                // insert job logs            
                $log_title = 80; // Sales Emails

                if( $cc !='' ){
                    $cc_append_str = " and CC to: <b>{$cc}</b> ";
                }
                              
                $agency_log = "Email sent to: <b>{$to}</b>{$cc_append_str}";
                                        
                $log_params = array(
                    'title' => $log_title,
                    'details' => $agency_log,
                    'display_in_vad' => 1,
                    'created_by_staff' => $staff_id,
                    'agency_id' => $agency_id
                );
                $this->system_model->insert_log($log_params); 
                $log_id = $this->db->insert_id();   

                if( $log_id > 0 ){

                    // capture email sent
                    $data = array(
                        'log_id' => $log_id,
                        'from_email' => $from,
                        'to_email' => $to,
                        'cc_email' => $cc,
                        'subject' => $subject,
                        'email_body' => $body_parsed,
                        'date_created' => $now,
                    );
                    
                    $this->db->insert('email_templates_sent', $data);

                }
                

            }else{
                $this->email->print_debugger();exit();
            }


        } 
        

    }


    public function send_once_off_report(){

        $this->load->model('reports_model'); 

        $country_id = $this->config->item('country'); 
        $view_data['country_id'] = $country_id;

        $fg_dha = 14; // Defence Housing    
        $view_data['fg_dha'] = $fg_dha; 

        $today = date('d/m/Y');
        $subject_txt = "Property Numbers ". ( ( $country_id == 1 )?'AU':'NZ' ) ." week ending {$today}"; 

        $view_data['title'] = $subject_txt;                  
        
         // get country data
         $country_params = array(
            'sel_query' => 'c.agent_number, c.outgoing_email, c.`iso`',
            'country_id' => $country_id
        );
        $country_sql = $this->system_model->get_countries($country_params);
        $country_row = $country_sql->row();     
            
        // view data
        $view_data['agency_portal_link'] = $this->config->item('agency_link');
        $view_data['outgoing_email'] = $country_row->outgoing_email;
        $view_data['agent_number'] = $country_row->agent_number;   
        $view_data['email_body_width'] = '900px'; 
        $view_data['is_email'] = true; 

        $region_state_filter_str = null;
        $property_state_filter_str = null;

        if( $country_id == 1 ){ // AU

            $region_state_filter_str = "AND r.`region_state` IN('NSW','ACT','QLD','SA')";
            $property_state_filter_str = "AND p.`state` IN('NSW','ACT','QLD','SA')";
            $view_data['property_state_filter_str'] = $property_state_filter_str;

        }

        // get distinct property state
        $states_sql_str = "
        SELECT DISTINCT(r.`region_state`)
        FROM `postcode` AS pc        
        LEFT JOIN `sub_regions` AS sr ON pc.`sub_region_id` = sr.`sub_region_id`      
        LEFT JOIN `regions` AS r ON sr.`region_id` = r.`regions_id`
        WHERE pc.`deleted` = 0
        AND r.`country_id` = {$country_id}              
        {$region_state_filter_str}
        ";
        $view_data['states_sql'] = $this->db->query($states_sql_str);        

        // content
        $return_as_string = true;
        $email_body = null;

        $email_body .= $this->load->view('emails/template/email_header', $view_data, $return_as_string);            
        $email_body .= $this->load->view('emails/send_once_off_report', $view_data, $return_as_string);
        $email_body .= $this->load->view('emails/template/email_footer', $view_data, $return_as_string);

        //echo $email_body;
        
        // subject
        $subject = $subject_txt;
        $this->email->to(make_email('bi_property_numbers')); // dynamic bi_property_numbers email
        $this->email->subject($subject);
        $this->email->message($email_body);

        // send email
        $this->email->send(); 
        

    }


    public function email_tech_user_feedback(){

        $this->load->model('sms_model'); 
        $this->load->model('properties_model');

        $country_id = $this->config->item('country'); 
        $view_data['country_id'] = $country_id;
        $subject_txt = "Tenant feedback for last week"; 

        $view_data['title'] = $subject_txt;                  
        
         // get country data
         $country_params = array(
            'sel_query' => 'c.agent_number, c.outgoing_email, c.`iso`',
            'country_id' => $country_id
        );
        $country_sql = $this->system_model->get_countries($country_params);
        $country_row = $country_sql->row();     
            
        // view data        
        $view_data['outgoing_email'] = $country_row->outgoing_email;
        $view_data['agent_number'] = $country_row->agent_number;   
        //$view_data['email_body_width'] = '900px'; 
        //$view_data['is_email'] = true; 

        // list
        $cust_sel = "
        DISTINCT(ass_tech.`StaffID`) as at_StaffID,
        ass_tech.`FirstName` as at_FirstName,
        ass_tech.`LastName` as at_LastName,
        ass_tech.`Email` as at_Email
        ";

       
        $cust_filt = "
        Date(sar.`created_date`) > ( CURDATE( ) - INTERVAL 7 DAY )        
        ";
       
        $sms_type = 18; // SMS (Thank You)
        $list_params = array(
            'sort_list' => array(
                array(
                    'order_by' => 'sar.`datetime_entry`',
                    'sort' => 'DESC'
                )
            ),           
            'echo_query' => 0,
            'sms_type' => $sms_type,
            'sms_page' => 'incoming',                                    
            'custom_select' => $cust_sel,
            'custom_filter' => $cust_filt
        );
        $tech_list = $this->sms_model->getSMSrepliesMergedData($list_params);  
        
        foreach( $tech_list->result() as $tech_row ){

            if( $tech_row->at_StaffID > 0 ){

                $view_data['tech_row'] = $tech_row;

                // list
                $cust_sel = "
                sas.`sms_api_sent_id`,
                sas.`sent_by`,
                sas.`sms_type`,
                sas.`job_id`,

                sar.`sms_api_replies_id`,
                sar.`message_id`,
                sar.`created_date` AS sar_created_date,
                sar.`mobile` AS sar_mobile,
                sar.`response`,
                sar.`saved`,
                sar.`unread`,

                sa.`FirstName`,
                sa.`LastName`,

                sat.`type_name`,
                sat.`sms_api_type_id`,

                p.`property_id`,

                ass_tech.`StaffID` as at_StaffID,
                ass_tech.`FirstName` as at_FirstName,
                ass_tech.`LastName` as at_LastName
                ";

                
                $cust_filt = "
                Date(sar.`created_date`) > ( CURDATE( ) - INTERVAL 7 DAY )    
                AND j.`assigned_tech` = {$tech_row->at_StaffID}    
                ";
                
                $sms_type = 18; // SMS (Thank You)
                $list_params = array(
                    'sort_list' => array(
                        array(
                            'order_by' => 'sar.`datetime_entry`',
                            'sort' => 'DESC'
                        )
                    ),           
                    'echo_query' => 0,
                    'sms_type' => $sms_type,
                    'sms_page' => 'incoming',                                    
                    'custom_select' => $cust_sel,
                    'custom_filter' => $cust_filt
                );
                $feedback_sql = $this->sms_model->getSMSrepliesMergedData($list_params);
                
                if( $feedback_sql->num_rows() > 0 ){

                    $view_data['feedback_sql'] = $feedback_sql;

                    // content
                    $return_as_string = true;
                    $email_body = null;

                    $email_body .= $this->load->view('emails/template/email_header', $view_data, $return_as_string);            
                    $email_body .= $this->load->view('emails/tech_user_feedback', $view_data, $return_as_string);
                    $email_body .= $this->load->view('emails/template/email_footer', $view_data, $return_as_string);

                    //echo $email_body;
                    
                    // subject
                    $subject = $subject_txt;
                    
                    $from_email = $country_row->outgoing_email;
                    $from_name = config_item('company_full_name');

                    //$to_email = 'vaultdweller123@gmail.com'; 
                    $to_email = $tech_row->at_Email; 
                    //$cc_email = 'bent@sats.com.au';
                                                                                                                                                        

                    // email settings

                    $this->email->to($to_email);                     
                    //$this->email->cc(make_email('keys'));
                    //$this->email->cc($cc_email);                           

                    $this->email->subject($subject);
                    $this->email->message($email_body);

                    // send email
                    $this->email->send(); 

                }                

            }            

        }        
        

    }


    public function weekly_qld_compliance_report(){

        $this->load->model('jobs_model');

        $country_id = $this->config->item('country');
        $job_status = 'Pending';

        // get country data
        $country_params = array(
            'sel_query' => 'c.agent_number, c.outgoing_email',
            'country_id' => $country_id
        );
        $country_sql = $this->system_model->get_countries($country_params);
        $country_row = $country_sql->row();

        // get distinct agency        
        $dist_agency_sql = $this->db->query("
        SELECT DISTINCT(a.`agency_id`), a.`agency_name`, a.agency_emails, a.`franchise_groups_id`
        FROM `property_services` AS ps
        INNER JOIN `property` AS p ON ps.`property_id` = p.`property_id`
        INNER JOIN  (
            SELECT DISTINCT(j2.`property_id`)
            FROM jobs AS j2
            WHERE j2.`status` = 'Completed'
            AND j2.`assigned_tech` NOT IN(1,2,3)
            AND j2.`del_job` = 0            
        ) AS complJob ON complJob.`property_id` = p.`property_id`
        LEFT JOIN `agency` AS a ON p.`agency_id` = a.`agency_id`
        LEFT JOIN `agency_user_accounts` AS aua ON p.`pm_id_new` = aua.`agency_user_account_id`
        LEFT JOIN `alarm_job_type` AS ajt ON ps.`alarm_job_type_id` = ajt.`id`
        WHERE p.`deleted` = 0
        AND ps.`service` = 1
        AND p.`state` = 'QLD' 
        AND ajt.`id` != 12 
        AND ajt.`id` != 13 
        AND ajt.`id` != 14     
        AND a.`deleted` = 0 
        AND ( p.qld_new_leg_alarm_num > 0 OR p.qld_new_leg_alarm_num IS NULL )
		AND ( p.prop_upgraded_to_ic_sa = 0 OR p.prop_upgraded_to_ic_sa IS NULL )                
		AND ( p.is_nlm = 0 OR p.is_nlm IS NULL )
		AND p.`is_sales` != 1  
		AND p.`qld_upgrade_quote_approved_ts` IS NULL
		AND p.`property_id` NOT IN(
			SELECT DISTINCT(j3.`property_id`) 
            FROM `jobs` AS j3 
			WHERE j3.`del_job` = 0 
			AND j3.`job_type` = 'IC Upgrade'
			AND j3.`status` != 'Cancelled'
		)
        ");        

         // loop per agency
        foreach( $dist_agency_sql->result() as $dist_agency_row ){

            // clear per agency
            $to_emails_arr = [];   
            $email_body = null;
            
            $agency_emails_imp = explode("\n",trim($dist_agency_row->agency_emails)); // split emails by new line

            // only add sanitized email on array
            foreach($agency_emails_imp as $agency_email){              

                $agency_email2 = preg_replace('/\s+/', '', $agency_email);
                if(filter_var($agency_email2, FILTER_VALIDATE_EMAIL)){
                    $to_emails_arr[] = $agency_email2;
                }	                 
                            
            }

            if( $dist_agency_row->agency_id > 0 ){               
                        
                $psq_sql_str = "
                SELECT 
                    DISTINCT(p.`property_id`), 
                    p.`address_1` AS p_address_1, 
                    p.`address_2` AS p_address_2, 
                    p.`address_3` AS p_address_3,
                    p.`state` AS p_state, 
                    p.`postcode` AS p_postcode, 
                    
                    aua.`agency_user_account_id`, 
                    aua.`fname` AS pm_fname, 
                    aua.`lname` AS pm_lname
                FROM `property_services` AS ps
                INNER JOIN `property` AS p ON ps.`property_id` = p.`property_id`
                INNER JOIN(
                    SELECT DISTINCT(j2.`property_id`)
                    FROM jobs AS j2
                    WHERE j2.`status` = 'Completed'
                    AND j2.`assigned_tech` NOT IN(1,2,3)
                    AND j2.`del_job` = 0            
                ) AS complJob ON complJob.`property_id` = p.`property_id`
                LEFT JOIN `agency` AS a ON p.`agency_id` = a.`agency_id`
                LEFT JOIN `agency_user_accounts` AS aua ON p.`pm_id_new` = aua.`agency_user_account_id`
                LEFT JOIN `alarm_job_type` AS ajt ON ps.`alarm_job_type_id` = ajt.`id`
                WHERE p.`agency_id` = {$dist_agency_row->agency_id}
                AND p.`deleted` = 0
                AND ps.`service` = 1
                AND p.`state` = 'QLD' 
                AND ajt.`id` != 12 
                AND ajt.`id` != 13 
                AND ajt.`id` != 14    
                AND ( p.qld_new_leg_alarm_num > 0 OR p.qld_new_leg_alarm_num IS NULL )
                AND ( p.prop_upgraded_to_ic_sa = 0 OR p.prop_upgraded_to_ic_sa IS NULL )                
                AND ( p.is_nlm = 0 OR p.is_nlm IS NULL )
                AND p.`is_sales` != 1            
                AND p.`qld_upgrade_quote_approved_ts` IS NULL
                AND p.`property_id` NOT IN(
                    SELECT DISTINCT(j3.`property_id`) 
                    FROM `jobs` AS j3 
                    WHERE j3.`del_job` = 0 
                    AND j3.`job_type` = 'IC Upgrade'
                    AND j3.`status` != 'Cancelled'
                )
                ORDER BY p.`address_2` ASC, p.`address_1` ASC
                ";
                $ps_sql = $this->db->query($psq_sql_str); 
                $view_data['ps_sql'] = $ps_sql; 
                $ps_count = $ps_sql->num_rows();
                $view_data['ps_count'] = $ps_count;

                // had to run the main query here just to get that count >.<
                $row_count = 0;
                foreach( $ps_sql->result() as $job_row ){

                    /*
                    // check if it has IC Upgrade job where status != cancelled / deleted
                    $job_sql_str = "
                    SELECT COUNT(`id`) AS j_count
                    FROM `jobs` 
                    WHERE `property_id` = {$job_row->property_id}
                    AND `job_type` = 'IC Upgrade'
                    AND `status` != 'Cancelled'
                    AND `del_job` = 0
                    ";
                    //echo "<br />";
                    $job_sql = $this->db->query($job_sql_str);
                    $j_count = $job_sql->row()->j_count;

                    if( $j_count == 0 ){
                        $row_count++;
                    }
                    */

                }

                $view_data['row_count'] = $row_count;

                // Age
                $date1 = date_create(date('Y-m-d'));
                $date2 = date_create('2022-01-01');
                $diff = date_diff($date1, $date2);
                $age = $diff->format("%r%a");
                $age_val = (((int) $age) != 0) ? $age : 0;
                $view_data['days_remaining'] = $age_val;

                // view data                 
                $view_data['agency_name'] = $dist_agency_row->agency_name;     
                
                //if( $row_count > 0 ){

                    $return_as_string =  true;

                    // content
                    $email_body .= $this->load->view('emails/template/email_header', $view_data, $return_as_string);
                    $email_body .= $this->load->view('emails/weekly_qld_compliance_report', $view_data, $return_as_string);
                    $email_body .= $this->load->view('emails/template/email_footer', $view_data, $return_as_string);

                    //echo $email_body;

                    // subject
                    $subject = "Weekly QLD Compliance Report";
                    $to_email = $to_emails_arr;                                

                    // email settings
                    $this->email->to($to_email);                     
//                    $this->email->bcc(make_email('bcc'));

                    $this->email->subject($subject);
                    $this->email->message($email_body);

                    // do not send email for franchise group = Image
                    //if( $dist_agency_row->franchise_groups_id != $this->image_fg_id ){

                        // send email
                        $this->email->send();

                    //}

                //}                                        

            }


        }  

    }

    public function email_cancelled_active_jobs_of_agency($agency_id){

        if( $agency_id > 0 ){

            $country_id = $this->config->item('country'); 
            $view_data['country_id'] = $country_id;
            $subject_txt = "Agency cancelled active jobs"; 

            $view_data['title'] = $subject_txt;                  
            
            // get country data
            $country_params = array(
                'sel_query' => 'c.agent_number, c.outgoing_email, c.`iso`',
                'country_id' => $country_id
            );
            $country_sql = $this->system_model->get_countries($country_params);
            $country_row = $country_sql->row();     
                
            // view data        
            $view_data['outgoing_email'] = $country_row->outgoing_email;
            $view_data['agent_number'] = $country_row->agent_number;   
            //$view_data['email_body_width'] = '900px'; 
            //$view_data['is_email'] = true; 

            // get agency name
            $agency_sql = $this->db->query("
            SELECT `agency_id`, `agency_name`
            FROM `agency`
            WHERE `agency_id` = {$agency_id}
            ");
            $agency_row = $agency_sql->row();   
            $view_data['agency_name'] = $agency_row->agency_name;          

            // get active jobs of agency
            $job_sql = $this->db->query("
            SELECT 
                j.`id` AS jid,
                j.`job_type`,
                j.`status` AS jstatus,

                p.`property_id`,
                p.`address_1`,
                p.`address_2`,
                p.`address_3`,
                p.`state`,
                p.`postcode`
            FROM `jobs` AS j
            LEFT JOIN `property` AS p ON j.`property_id` = p.`property_id`
            WHERE p.`agency_id` = {$agency_id}
            AND p.`deleted` = 0 
            AND ( p.`is_nlm` = 0 OR p.`is_nlm` IS NULL )
            AND j.`status` IN('Booked','Pre Completion','Merged Certificates')
            ");     

            if( $job_sql->num_rows() > 0 ){

                $view_data['job_sql'] = $job_sql;

                // content
                $return_as_string = true;
                $email_body = null;

                $email_body .= $this->load->view('emails/template/email_header', $view_data, $return_as_string);            
                $email_body .= $this->load->view('emails/email_cancelled_active_jobs_of_agency', $view_data, $return_as_string);
                $email_body .= $this->load->view('emails/template/email_footer', $view_data, $return_as_string);

                //echo $email_body;
                
                // subject
                $subject = $subject_txt;
                $to_email = make_email('info');
                                                                                                                                                    
                // email settings
                $this->email->to($to_email);
                $this->email->subject($subject);
                $this->email->message($email_body);

                // send email
                $this->email->send();   

            }                        

        }                    

    }

    public function email_nlm_properties($agency_id){

        if( $agency_id > 0 ){

            $country_id = $this->config->item('country'); 
            $view_data['country_id'] = $country_id;
            $subject_txt = "Agency NLM properties"; 

            $view_data['title'] = $subject_txt;                  
            
            // get country data
            $country_params = array(
                'sel_query' => 'c.agent_number, c.outgoing_email, c.`iso`',
                'country_id' => $country_id
            );
            $country_sql = $this->system_model->get_countries($country_params);
            $country_row = $country_sql->row();     
                
            // view data        
            $view_data['outgoing_email'] = $country_row->outgoing_email;
            $view_data['agent_number'] = $country_row->agent_number;   
            //$view_data['email_body_width'] = '900px'; 
            //$view_data['is_email'] = true; 

            // get agency name
            $agency_sql = $this->db->query("
            SELECT `agency_id`, `agency_name`
            FROM `agency`
            WHERE `agency_id` = {$agency_id}
            ");
            $agency_row = $agency_sql->row();   
            $view_data['agency_name'] = $agency_row->agency_name;          

            // get active jobs of agency
            $nlm_sql = $this->db->query("
            SELECT 
                p.`property_id`,
                p.`address_1`,
                p.`address_2`,
                p.`address_3`,
                p.`state`,
                p.`postcode`
            FROM `property` AS p 
            WHERE p.`agency_id` = {$agency_id}
            AND p.`deleted` = 0 
            AND ( p.`is_nlm` = 0 OR p.`is_nlm` IS NULL )            
            ");    
            
            if( $nlm_sql->num_rows() > 0 ){

                $view_data['nlm_sql'] = $nlm_sql;

                // content
                $return_as_string = true;
                $email_body = null;

                $email_body .= $this->load->view('emails/template/email_header', $view_data, $return_as_string);            
                $email_body .= $this->load->view('emails/email_nlm_properties', $view_data, $return_as_string);
                $email_body .= $this->load->view('emails/template/email_footer', $view_data, $return_as_string);

                //echo $email_body;
                
                // subject
                $subject = $subject_txt;
                
                $from_email = $country_row->outgoing_email;
                $from_name = config_item('company_full_name');

                $to_email = make_email('info');
                                                                                                                                                    
                // email settings
                $this->email->to($to_email);
                $this->email->subject($subject);
                $this->email->message($email_body);

                // send email
                $this->email->send();   

            }            

        }                    

    }


    public function email_entry_notice($params){

        //params start
        $job_id = $params['job_id'];
        $email_to_type = $params['email_to_type'];
         //params start end

        // get country data
        $country_params = array(
            'sel_query' => 'c.agent_number, c.outgoing_email, c.tenant_number',
            'country_id' => $this->config->item('country')
        );
        $country_sql = $this->system_model->get_countries($country_params);
        $country_row = $country_sql->row();

        //get jobs data
        $sel_query = "
            j.`id` AS jid,
            j.`status` AS j_status,
            j.`service` AS j_service,
            j.`date` AS j_date,
            j.`job_price` AS j_price,
            j.`job_type` AS j_type,
            j.`urgent_job`,
            j.`job_reason_id`,
            
            p.`property_id` AS prop_id, 
            p.`address_1` AS p_address_1, 
            p.`address_2` AS p_address_2, 
            p.`address_3` AS p_address_3,
            p.`state` AS p_state,
            p.`postcode` AS p_postcode,
            p.pm_id_new,
            
            a.`agency_id` AS a_id,
            a.`agency_name` AS agency_name,
            a.agency_emails,
            a.`phone` AS a_phone,
            a.`address_1` AS a_address_1, 
            a.`address_2` AS a_address_2, 
            a.`address_3` AS a_address_3,
            a.`state` AS a_state,
            a.`postcode` AS a_postcode,
            a.en_to_pm,
            a.send_en_to_agency
        ";

        
        $job_params = array(
            'sel_query' => $sel_query,
            'job_id' => $job_id,
            'del_job' => 0,
            'p_deleted' => 0,
            'a_status' => 'active',
            'country_id' => $this->config->item('country'),
            'display_query' => 0
        );  
        $job_row = $this->jobs_model->get_jobs($job_params)->row_array();

        $prop_id = $job_row['prop_id']; //set property id variable
        $pm_id_new = $job_row['pm_id_new']; //set pm id variable
        $agency_id = $job_row['a_id']; //set agency id variable
        $agency_emails = $job_row['agency_emails']; //agency email variable
        $en_to_pm = $job_row['en_to_pm'];
        $send_en_to_agency = $job_row['send_en_to_agency'];
        $p_address = "{$job_row['p_address_1']} {$job_row['p_address_2']} {$job_row['p_address_3']}, {$job_row['p_state']} {$job_row['p_postcode']}";  
        //get jobs data end

        //get active tenants
        $tenant_email_arr = [];   
        $tenant_names_arr = [];
        $active_tenant_sel="pt.tenant_email,pt.tenant_firstname,pt.tenant_lastname,pt.tenant_mobile";
        $params_active = array('sel_query'=> $active_tenant_sel,'property_id'=>$prop_id, 'pt_active' => 1);
        $active_tenants = $this->properties_model->get_property_tenants($params_active);
        //get active tenants end

        //loop all tenant email and add/set to array
        foreach( $active_tenants->result_array() as $pt_row ){

            if (filter_var(trim($pt_row['tenant_email']), FILTER_VALIDATE_EMAIL)) {
                $tenant_email_arr[] = trim($pt_row['tenant_email']);
            }

            //tenants name
            if( $pt_row['tenant_firstname'] != '' ){

                $tenant_names_arr[] = $pt_row->tenant_firstname; 

            }   

            // append tenant names
            $tenants_str = null;
            $num_tenants = count($tenant_names_arr);

            for( $z=0; $z<$num_tenants; $z++ ){

                if($z==0){
                    $tenants_txt_sep = "";
                }else if($z==($num_tenants-1)){
                    $tenants_txt_sep = " and ";
                }else{
                    $tenants_txt_sep = ", ";
                }
                $tenants_str .= "{$tenants_txt_sep}{$tenant_names_arr[$z]}";
                
            }
            //tenants name end

        }
        //loop all tenant email and add/set to array end

        // get PM
        $pm_email = null;
        if( $pm_id_new > 0 ){
           
            $pm_sql = $this->db->query("
                SELECT `email`
                FROM `agency_user_accounts`
                WHERE `agency_user_account_id` = {$pm_id_new}    
                AND `agency_id` = {$agency_id}    
            ");
            if ( $pm_sql->num_rows() > 0) {

                // sanitize email            
                $pm_row = $pm_sql->row();                      
                if( filter_var(trim($pm_row->email), FILTER_VALIDATE_EMAIL) ) {
                    $pm_email = $pm_row->email;                                 
                }

            }

        }    

        // agency email
        $agency_emails_arr = []; // clear
        $agency_emails_imp = null;

        $agency_emails_imp = explode("\n", trim($agency_emails));
        foreach ($agency_emails_imp as $agency_email_row) {            
            if (filter_var(trim($agency_email_row), FILTER_VALIDATE_EMAIL)) {                
                $agency_emails_arr[] = $agency_email_row;                
            }
        }

        $en_bcc_emails = []; // clear
        if( $email_to_type==2 ){ //send also to agency as BCC
           
            if( $en_to_pm == 1 ){ // send to PM - YEs
    
                // PM exist, only send to PM
                if( $pm_email != '' ){ 
    
                    $en_bcc_emails[] = $pm_email;
    
                }else{ // PM doesnt exist, send to agency
                    
                    if( count($agency_emails_arr) > 0 ){
                        $en_bcc_emails = $agency_emails_arr;   
                    }
                                    
                }
    
            }else{ // send to PM - NO
    
                if ( $send_en_to_agency == 1 ) {
                    if( count($agency_emails_arr) > 0 ){
                        $en_bcc_emails = $agency_emails_arr;   
                    }
                }
    
            } 

        }
       

        // if "TO" email is empty move all BCC email to it, bec this email system will error if TO: is empty
        if( count($tenant_email_arr) == 0 ){		

            $tenant_email_arr = $en_bcc_emails; // TO email
            
            $en_bcc_emails = []; // clear other BCC email bec, it was already on "TO" email
            $en_bcc_emails[] = make_email('cc');

        }else{		
            $en_bcc_emails[] = make_email('cc');
        }

        $proceed_en_operation = true; // defaul to run EN
        if( $this->config->item('country') == 2 && count($tenant_email_arr) == 0 ){ // on NZ dont run EN if no tenant emails
            $proceed_en_operation = false;
        }

        if( $proceed_en_operation == true ){ //Proceed to email EN with attachement EN PDF

            // email settings
            $this->email->to($tenant_email_arr);     
            if( count($en_bcc_emails) > 0 ){
                $this->email->bcc($en_bcc_emails);  
            }                                                         
            $this->email->subject("Entry Notice - {$p_address}");

            //EN content conditions
            if( $job_row['j_type'] == "IC Upgrade" && $this->config->item('country')==1 ){

                $html_content  = "<p>Dear {$tenants_str},</p>\n";
                $html_content .= "<p>Please find the attached entry notice for ".$p_address." on {$job_row['j_date']}. We will collect the keys from {$job_row['agency_name']} to complete the service. Please ensure all bedrooms are accessible as we will be installing alarms into them as part of our Queensland upgrade service.</p>";
                $html_content .= "<p>If you would like to be in attendance or require a change to the date or time, please contact our office on {$country_row->tenant_number}.</p>";
                $html_content .= "<p><strong>Property Address</strong><br />";
                $html_content .= "{$p_address}</p>";
                $html_content .= "<p>Kind Regards,<br />". config_item('company_name_short') . " Team</p>";

            }else{

                $html_content  = "<p>Dear {$tenants_str},</p>\n";
                $html_content .= "<p>Please find the attached entry notice for ".$p_address." on {$job_row['j_date']}. We will collect the keys from {$job_row['agency_name']} to complete the service. Please contact us with any enquiries you may have.</p>";			
                $html_content .= "<p><strong>Property Address</strong><br />";
                $html_content .= "{$p_address}</p>";
                $html_content .= "<p>Kind Regards,<br />". config_item('company_name_short') . " Team</p>";
                
            }
            //EN content conditions end

            
            $view_data = '';
            $email_body = ''; // clear
            $email_body .= $this->load->view('/emails/template/email_header', $view_data, TRUE);
            $email_body .= nl2br($html_content);
            $email_body .= $this->load->view('/emails/template/email_footer', $view_data, TRUE);   

            $this->email->message($email_body);

            //pdf for attachment
            $pdf_name = 'en_pdf_'.rand().date('YmdHis').'.pdf';
            $en_pdf_params = array(
                'job_id' => $job_id,
                'output' => 'S'
            );
            $en_pdf = $this->pdf_template->entry_notice_switch($en_pdf_params);                    
            $this->email->attach($en_pdf, 'attachment',  $pdf_name, 'application/pdf');
            //pdf for attachment end

            if( $this->email->send() ){

                //update job entry_notice_emailed field
                $entry_notice_emailed_data = array(
                    'entry_notice_emailed' => date('Y-m-d H:i:s')
                );                    
                $this->db->where('id', $job_id);
                $this->db->update('jobs', $entry_notice_emailed_data);
                $this->db->limit(1);
                //update job entry_notice_emailed field end

                //insert log
                $combined_logs_arr = array();
                if ( count($tenant_names_arr) > 0 ){
                    $combined_logs_arr[] = "Entry Notice emailed to <strong>Tenants</strong>";                        
                }

                if ( $email_to_type == 2 ) {
                    $combined_logs_arr[] = "Entry Notice emailed to <strong>{$job_row['agency_name']}</strong>";
                }  
                if( count($combined_logs_arr) > 0 ){

                    // combined logs separator
                    $tenant_name_imp = implode('; ', $combined_logs_arr);

                    $log_params = array(
                        'title' => 63, // Job Update
                        'details' => $tenant_name_imp,
                        'display_in_vjd' => 1,
                        'created_by_staff' => $this->session->staff_id,
                        'job_id' => $job_id
                    );
                    $this->system_model->insert_log($log_params);

                }  
                //insert log END

                return true;
                
            }
            
        }

    }

    /**
     * @param mixed $params
     * 
     * @return [object]
     */
    public function get_email_template_sent($params){

        if (isset($params['sel_query'])) {
            $sel_query = $params['sel_query'];
        } else {
            $sel_query = '*';
        }

        $this->db->select($sel_query);
        $this->db->from('email_templates_sent AS ets');
        $this->db->where('email_templates_sent_id >',0);

        if($params['job_log_id'] && $params['job_log_id']!=""){
            $this->db->where('ets.job_log_id',$params['job_log_id']);
        }
        if($params['log_id'] && $params['log_id']!=""){
            $this->db->where('ets.log_id',$params['log_id']);
        }
        if($params['active'] && $params['active']!=""){
            $this->db->where('ets.active',$params['active']);
        }

        $q = $this->db->get();

        return $q;

    }

    
}


