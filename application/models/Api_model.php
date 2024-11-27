<?php
class API_model extends CI_Model {

    private $request_limit;
    private $sleep_interval_sec;
    private $pt_api_gateway;

	public function __construct(){
        $this->load->database();
        
        $this->clientId = $this->config->item('PME_CLIENT_ID');
        $this->clientSecret = $this->config->item('PME_CLIENT_SECRET');
        $this->clientScope = $this->config->item('PME_CLIENT_Scope');
        $this->urlCallBack = urlencode($this->config->item('PME_URL_CALLBACK'));
        $this->accessTokenUrl = $this->config->item('PME_ACCESS_TOKEN_URL');
        $this->authorizeUrl = $this->config->item('PME_AUTHORIZE_URL');

        $this->request_limit = 240; //  PropertyTree API request limit
        $this->sleep_interval_sec = 60; // delay 1 minute 

        if( ENVIRONMENT ==  'production' ){ // LIVE

            $this->pt_api_gateway = 'https://api.propertytree.io';

        }else{ // DEV

            $this->pt_api_gateway = 'https://uatapi.propertytree.io';
        }
    }
	
	public function getPmeAccessToken($authorization_code) {

        $token_url = $this->accessTokenUrl;
        $client_id = $this->clientId;
        $client_secret = $this->clientSecret;
        $callback_uri = $this->urlCallBack;

        $authorization = base64_encode("$client_id:$client_secret");
        $header = array("Authorization: Basic {$authorization}","Content-Type: application/x-www-form-urlencoded");
        $content = "grant_type=authorization_code&code=$authorization_code&redirect_uri=$callback_uri";

        $curl_opt = array(
            CURLOPT_URL => $token_url,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $content
        );

        $curl = curl_init();
        
        curl_setopt_array($curl, $curl_opt);
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;

    }


    public function refreshPmeToken($refresh_token) {

        $token_url = $this->accessTokenUrl;
        $client_id = $this->clientId;
        $client_secret = $this->clientSecret;
        $callback_uri = $this->urlCallBack;

        $authorization = base64_encode("$client_id:$client_secret");
        $header = array("Authorization: Basic {$authorization}","Content-Type: application/x-www-form-urlencoded");
        $content = "grant_type=refresh_token&refresh_token=$refresh_token&redirect_uri=$callback_uri";

        $curl_opt = array(
            CURLOPT_URL => $token_url,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $content
        );

        $curl = curl_init();
        
        curl_setopt_array($curl, $curl_opt);
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;

    }

    public function pme_auth_link(){

        return $this->config->item('PME_AUTHORIZE_URL') . "?response_type=code&state=abc123&client_id=".$this->config->item('PME_CLIENT_ID')."&scope=".$this->config->item('PME_CLIENT_Scope')."&redirect_uri=".$this->config->item('PME_URL_CALLBACK');

    }

    public function get_agency_api($params)
    {

        if (isset($params['sel_query'])) {
            $sel_query = $params['sel_query'];
        } else {
            $sel_query = '*';
        }

        $this->db->select($sel_query);
        $this->db->from('`agency_api`');
		
        // filter
        if ( $params['agency_api_id'] > 0 ) {
            $this->db->where('`agency_api_id`', $params['agency_api_id']);
        }

        if( $params['active'] > 0 ){
			$this->db->where('`active`', $params['active']);
		}

      	// custom filter
        if( isset($params['custom_where']) ){
             $this->db->where($params['custom_where']);
        }
		
		// custom filter arr
        if( isset($params['custom_where_arr']) ){
			foreach( $params['custom_where_arr'] as $index => $custom_where ){
				if( $custom_where != '' ){
					$this->db->where($custom_where);
				}				
			}              
        }		
		
		// group by
        if( isset($params['group_by']) && $params['group_by'] != '' ){
              $this->db->group_by($params['group_by']);
        }		

        // sort
        if (isset($params['sort_list'])) {
            foreach ($params['sort_list'] as $sort_arr) {
                if ($sort_arr['order_by'] != "" && $sort_arr['sort'] != '') {
                    $this->db->order_by($sort_arr['order_by'], $sort_arr['sort']);
                }
            }
        }
		
		// custom filter
        if( isset($params['custom_sort']) ){
              $this->db->order_by($params['custom_sort']);
        }

        // limit
		if( isset($params['limit']) && $params['limit'] > 0 ){
			$this->db->limit( $params['limit'], $params['offset']);
		}	

		$query = $this->db->get();
		if( isset($params['display_query']) && $params['display_query'] == 1 ){
			echo $this->db->last_query();
		}
		
		return $query;
		
    }


    public function get_agency_api_integration($params)
    {

        if (isset($params['sel_query'])) {
            $sel_query = $params['sel_query'];
        } else {
            $sel_query = '*';
        }

        $this->db->select($sel_query);
        $this->db->from('`agency_api_integration` AS agen_api_int');
        $this->db->join('`agency_api` AS agen_api', 'agen_api_int.`connected_service` = agen_api.`agency_api_id`', 'left');
        $this->db->join('`agency_api_documents` AS agen_api_doc', 'agen_api_int.`agency_id` = agen_api_doc.`agency_id`', 'left');

        // set joins
		if( $params['join_table'] > 0 ){
			
			foreach(  $params['join_table'] as $join_table ){
				
				if( $join_table == 'agency' ){
					$this->db->join('`agency` AS a', 'agen_api_int.`agency_id` = a.`agency_id`', 'left');
                }
                			
			}			
			
		}

        // custom joins
		if( isset($params['custom_joins']) && $params['custom_joins'] != '' ){
			$this->db->join($params['custom_joins']['join_table'],$params['custom_joins']['join_on'], $params['custom_joins']['join_type']);
        }
		
        // filter
        if ( $params['api_integration_id'] > 0 ) {
            $this->db->where('agen_api_int.`api_integration_id`', $params['api_integration_id']);
        }

        if ( is_numeric($params['active']) ) {
            $this->db->where('agen_api_int.`active`', $params['active']);
        }

        if ( $params['agency_id'] > 0 ) {
            $this->db->where('agen_api_int.`agency_id`', $params['agency_id']);
        }

        if ( $params['api_id'] > 0 ) {
            $this->db->where('agen_api_int.`connected_service`', $params['api_id']);
        }

      	// custom filter
        if( isset($params['custom_where']) ){
             $this->db->where($params['custom_where']);
        }
		
		// custom filter arr
        if( isset($params['custom_where_arr']) ){
			foreach( $params['custom_where_arr'] as $index => $custom_where ){
				if( $custom_where != '' ){
					$this->db->where($custom_where);
				}				
			}              
        }		
		
		// group by
        if( isset($params['group_by']) && $params['group_by'] != '' ){
              $this->db->group_by($params['group_by']);
        }		

        // sort
        if (isset($params['sort_list'])) {
            foreach ($params['sort_list'] as $sort_arr) {
                if ($sort_arr['order_by'] != "" && $sort_arr['sort'] != '') {
                    $this->db->order_by($sort_arr['order_by'], $sort_arr['sort']);
                }
            }
        }
		
		// custom filter
        if( isset($params['custom_sort']) ){
              $this->db->order_by($params['custom_sort']);
        }

        // limit
		if( isset($params['limit']) && $params['limit'] > 0 ){
			$this->db->limit( $params['limit'], $params['offset']);
		}	

		$query = $this->db->get();
		if( isset($params['display_query']) && $params['display_query'] == 1 ){
			echo $this->db->last_query();
		}
		
		return $query;
		
    }

    public function get_agency_api_tokens($params)
    {

        if (isset($params['sel_query'])) {
            $sel_query = $params['sel_query'];
        } else {
            $sel_query = '*';
        }

        $this->db->select($sel_query);
        $this->db->from('`agency_api_tokens` AS agen_api_tok');
        $this->db->join('`agency_api` AS agen_api', 'agen_api_tok.`api_id` = agen_api.`agency_api_id`', 'left');

        // set joins
		if( $params['join_table'] > 0 ){
			
			foreach(  $params['join_table'] as $join_table ){
				
				if( $join_table == 'agency' ){
					$this->db->join('`agency` AS a', 'agen_api_tok.`agency_id` = a.`agency_id`', 'left');
                }

                if( $join_table == 'pme_unmatched_property_count' ){
					$this->db->join('`pme_unmatched_property_count` AS pme_upc', 'agen_api_tok.`agency_id` = pme_upc.`agency_id`', 'left');
                }
                			
			}			
			
		}
		
        // filter
        if ( $params['agency_api_token_id'] > 0 ) {
            $this->db->where('agen_api_tok.`agency_api_token_id`', $params['agency_api_token_id']);
        }

        if ( $params['api_id'] > 0 ) {
            $this->db->where('agen_api_tok.`api_id`', $params['api_id']);
        }

        if ( $params['deactivated'] > 0 ) {
            $this->db->where('`a`.`status` !=', "deactivated");
        }

        if ( $params['target'] > 0 ) {
            $this->db->where('`a`.`status` !=', "target");
        }

        if ( $params['agency_id'] > 0 ) {
            $this->db->where('agen_api_tok.`agency_id`', $params['agency_id']);
        }

        if( $params['active'] > 0 ){
			$this->db->where('agen_api_tok.`active`', $params['active']);
		}

      	// custom filter
        if( isset($params['custom_where']) ){
             $this->db->where($params['custom_where']);
        }
		
		// custom filter arr
        if( isset($params['custom_where_arr']) ){
			foreach( $params['custom_where_arr'] as $index => $custom_where ){
				if( $custom_where != '' ){
					$this->db->where($custom_where);
				}				
			}              
        }		
		
		// group by
        if( isset($params['group_by']) && $params['group_by'] != '' ){
              $this->db->group_by($params['group_by']);
        }		

        // sort
        if (isset($params['sort_list'])) {
            foreach ($params['sort_list'] as $sort_arr) {
                if ($sort_arr['order_by'] != "" && $sort_arr['sort'] != '') {
                    $this->db->order_by($sort_arr['order_by'], $sort_arr['sort']);
                }
            }
        }
		
		// custom filter
        if( isset($params['custom_sort']) ){
              $this->db->order_by($params['custom_sort']);
        }

        // limit
		if( isset($params['limit']) && $params['limit'] > 0 ){
			$this->db->limit( $params['limit'], $params['offset']);
		}	

		$query = $this->db->get();
		if( isset($params['display_query']) && $params['display_query'] == 1 ){
			echo $this->db->last_query();
		}
		
		return $query;
		
    }

    public function get_agency_api_marker($params)
    {

        if (isset($params['sel_query'])) {
            $sel_query = $params['sel_query'];
        } else {
            $sel_query = '*';
        }

        $this->db->select($sel_query);
        $this->db->from('agency');
        $this->db->where('agency_id', $params['agency_id']);

        $query = $this->db->get();
		return $query;
		
    }

    public function if_notes_already_exist_in_pnv($params) {

        if( $params['property_id'] != '' && $params['property_source'] !='' ){

            $this->db->select('pnv_id');
            $this->db->from('properties_needs_verification');
            $this->db->where('property_id', $params['property_id']);
            $this->db->where('property_source', $params['property_source']);
            $query = $this->db->get();
            $pnv_count = $query->num_rows();

            if ($pnv_count > 0 ) {
                return true;
            }else {
                return false;
            }

        }         

    }

    // PME
    public function get_property_pme($params){

        $prop_id = $params['prop_id'];
        $agency_id = $params['agency_id'];

        if( $prop_id != '' && $agency_id > 0 ){

            $end_points = "https://app.propertyme.com/api/v1/lots/{$prop_id}";
            $api_id = 1; // PMe    
    
            // get access token
            $pme_params = array(
                'agency_id' => $agency_id,
                'api_id' => $api_id
            );
            $access_token = $this->pme_model->getAccessToken($pme_params);
    
            $pme_params = array(
                'access_token' => $access_token,
                'end_points' => $end_points
            );
            
            return $this->pme_model->call_end_points_v2($pme_params);

        }       

    }

    public function get_property_tree_property($params) {

        $property_id = $params['property_id'];
        
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
            // $this->api_request_limit_counter_and_delay($req_limit_params);
            $this->system_model->api_request_limit_counter_and_delay($req_limit_params);

            // get access token
            $agency_api_tokens_str = "
            SELECT `access_token`
            FROM `agency_api_tokens`
            WHERE `agency_id` = {$agency_id}
            AND `api_id` = {$api_id}
            ";
            $agency_api_tokens_sql =  $this->db->query($agency_api_tokens_str);
            $a_api_tok_row = $agency_api_tokens_sql->row();
            $access_token = $a_api_tok_row->access_token;    
            
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
            
            //echo "api_prop_json: <br />";
            $end_points = "{$this->pt_api_gateway}/residentialproperty/v1/Properties/{$api_prop_id}";
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
	
    public function get_property_palace($params){

        $prop_id = $params['prop_id'];
        $agency_id = $params['agency_id'];

        $api_id = 4; // Palace        

        $agency_api_tokens_str = "
            SELECT 
                `access_token`,
                `expiry`,
                `refresh_token`,
                `system_use`
            FROM `agency_api_tokens`
            WHERE `agency_id` = {$agency_id}
            AND `api_id` = {$api_id}
        ";
        $agency_api_tokens_sql =  $this->db->query($agency_api_tokens_str);
        $a_api_tok_row = $agency_api_tokens_sql->row_array();
        $access_token = $a_api_tok_row['access_token'];
        $system = $a_api_tok_row['system_use'];

        if ($this->config->item('country') == 1) { // AU
            if ($system == "Legacy" || is_null($system)) {
                $palace_api_base = 'https://serviceapia.realbaselive.com';
            }else {
                $palace_api_base = 'https://api.getpalace.com';
            }
        } else if ($this->config->item('country') == 2) { // NZ
            if ($system == "Legacy" || is_null($system)) {
                $palace_api_base = 'https://serviceapi.realbaselive.com';
            }else {
                $palace_api_base = 'https://api.getpalace.com';
            }
        }

        $end_points = "{$palace_api_base}/Service.svc/RestService/v2DetailedProperty/JSON/{$prop_id}";

        $pme_params = array(
            'access_token' => $access_token,
            'end_points' => $end_points
        );
        return $this->get_palace_end_points($pme_params);

    }

    public function get_palace_end_points($params)
    {

        $curl = curl_init();

        // HTTP headers
        $http_header = array(
            "Authorization: Basic {$params['access_token']}",
            "Content-Type: application/json"
        );

        // curl options
        $curl_opt = array(
            CURLOPT_URL => $params['end_points'],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $http_header
        );     
        
        // parameters
        if( count($params['param_data']) > 0 ){  

            $curl_opt[CURLOPT_POST] = true;                                                        
		    $data_string = json_encode($params['param_data']);  
            $curl_opt[CURLOPT_POSTFIELDS] = $data_string;
            
        }  
              

        // display - debug
        if( $params['display'] == 1 ){
            print_r($curl_opt);
        }

        curl_setopt_array($curl, $curl_opt);

        $response = curl_exec($curl);
        curl_close($curl);

        //$response_decode = json_decode($response);

        return $response;
        
		
    }

    public function get_pme_tenant_v2($agency_id,$prop_id){
        $this->load->model('pme_model');

        $end_points = "https://app.propertyme.com/api/v1/tenancies?LotId={$prop_id}";

        $api_id = 1; // PMe

        // get access token
        $pme_params = array(
            'agency_id' => $agency_id,
            'api_id' => $api_id
        );
        $access_token = $this->pme_model->getAccessToken($pme_params);

        $pme_params = array(
            'access_token' => $access_token,
            'end_points' => $end_points
        );
        
        $response =  $this->pme_model->call_end_points_v2($pme_params);
        return json_decode($response);

    }

    public function get_pme_contact_v2($agency_id,$contact_id){
        $this->load->model('pme_model');

        $end_points = "https://app.propertyme.com/api/v1/contacts/{$contact_id}";

        $api_id = 1; // PMe

        // get access token
        $pme_params = array(
            'agency_id' => $agency_id,
            'api_id' => $api_id
        );
        $access_token = $this->pme_model->getAccessToken($pme_params);

        $pme_params = array(
            'access_token' => $access_token,
            'end_points' => $end_points
        );
        
        $response =  $this->pme_model->call_end_points_v2($pme_params);
        return json_decode($response);

    }

    /**
     * This function will check between crm tenant and api's tenant in VJD/VPD 
     * If has ACTIVE crm tenant and NOT ACTIVE tenant in api show warning popup > 'There are Inactive tenants that need to be Removed'
     * If has ACTIVE api tenant and NOT ACTIVE in crm show warning popup > 'There are active tenants that need to be added'
     * Only affect for agencies/properties that is connected to API
     * 
     * @param int $prop_id
     * 
     * @return array > string - $msgResponse | bool $isMisMatched
     */
    public function api_and_crm_tenants_mismatched($prop_id){
        
        $msgResponse = [];
        $isMisMatched = FALSE;

        //Get apis tenants
        $apis_tenants = $this->get_apis_tenants_v2($prop_id);

        //Exit when empty api tenants or property is not connected yet to api
        if(empty($apis_tenants['api_tenants_arr']) || $apis_tenants['prop_is_connected_to_api'] === false){
            return;
        }

        $api_tenants_arr = $apis_tenants['api_tenants_arr'];
       
        //Get crm active tenants
        $active_tenant_sel="pt.property_tenant_id, pt.property_id, pt.tenant_firstname, pt.tenant_lastname, pt.tenant_mobile, pt.tenant_landline, pt.tenant_email, pt.modifiedDate, pt.createdDate, pt.tenant_priority";
        $crm_active_tenants_params = array(
            'sel_query'=> $active_tenant_sel,
            'property_id'=>$prop_id,
            'pt_active' => 1
        );
        $active_tenants = $this->properties_model->get_property_tenants($crm_active_tenants_params);

        foreach($active_tenants->result_array() as $active_tenants_row){
            $crm_tenants_arr[] = array(
                'crm_tenant_firstname'  => trim($active_tenants_row['tenant_firstname']),
                'crm_tenant_lastname'   => trim($active_tenants_row['tenant_lastname']),
                'crm_tenant_mobile'     => str_replace(' ', '', $active_tenants_row['tenant_mobile']),
                'crm_tenant_landline'   => str_replace(' ', '', $active_tenants_row['tenant_landline']),
                'crm_tenant_email'      => trim($active_tenants_row['tenant_email']),
                'crm_compare_details'   => trim($active_tenants_row['tenant_firstname'])." ".trim($active_tenants_row['tenant_lastname'])." ".str_replace(' ', '', $active_tenants_row['tenant_mobile'])." ".str_replace(' ', '', $active_tenants_row['tenant_landline'])
            );
        }
        //Get crm active tenants end

        if($apis_tenants['agency_api'] == 5){
            //separate comparison for Console as Console has array of phone(mobile/landline) and emails

            $console_api_compare_detailsl = [];
            foreach($api_tenants_arr as $console_tenants_row){

                //Note phone and email is array so might need to loop to get the array values
                foreach($console_tenants_row['phone'] as $console_tenants_phone_row){
                    $api_console_phone = $console_tenants_phone_row['number'];
                    $api_console_phone_type = $console_tenants_phone_row['type'];

                    $new_console_mobile = ($api_console_phone_type == 'MOBILE') ? $api_console_phone : '';
                    $new_console_landline = ($api_console_phone_type == 'LANDLINE') ? $api_console_phone : '';
                   
                }
                $console_api_compare_detailsl[] = $console_tenants_row['fname']." ".$console_tenants_row['lname']." ".$new_console_mobile." ".$new_console_landline;
                
            }

            //Check if console api exist in crm
            //NOTE: Must remove any white space compare text in both api and crm tenants
            foreach($console_api_compare_detailsl as $val){
                $console_api_compare_text = str_replace(' ', '', $val);

                //There is active console api tenant that is not found in CRM tenants
                if(!in_array($console_api_compare_text, str_replace(' ', '', array_column($crm_tenants_arr, 'crm_compare_details')))){
                    $isMisMatched = TRUE;
                    $msgResponse[] = "There are active API tenants that need to be added.";
                }
            }

            //Next is we check if active CRM tenants exist in API
            //NOTE: Must remove any white space compare text in both api and crm tenants
            foreach($crm_tenants_arr as $crm_tenant_row){
                $console_crm_compare_text = str_replace(' ', '', $crm_tenant_row['crm_compare_details']);

                //There is active console crm tenant that is not found in API tenants
                if(!in_array($console_crm_compare_text,  str_replace(' ', '', $console_api_compare_detailsl))){
                    $isMisMatched = TRUE;
                    $msgResponse[] = "There are active CRM tenants that need to be removed.";
                }
            }

        }else{
            //Other API's check (NOT CONSOLE)

            //Check here if api tenant is found/exist in crm
            foreach($api_tenants_arr as $api_tenant_row){
                $compare_text = $api_tenant_row['api_compare_details'];

                //There is active api tenant that is not found in CRM tenants
                if(!in_array($compare_text, array_column($crm_tenants_arr, 'crm_compare_details'))){
                    $isMisMatched = TRUE;
                    $msgResponse[] = "There are active API tenants that need to be added.";
                }
            }

            //Check here if crm tenant is found/exist in api
            foreach($crm_tenants_arr as $crm_tenant_row){
                $compare_text = $crm_tenant_row['crm_compare_details'];

                //There is active crm tenant that is not found in API tenants
                if(!in_array($compare_text,  array_column($api_tenants_arr, 'api_compare_details'))){
                    $isMisMatched = TRUE;
                    $msgResponse[] = "There are active CRM tenants that need to be removed.";
                }
            }
            
        }

        //implode array to string and removed duplicate value
        $implodeMsgResponse = implode('<br/>', array_unique($msgResponse));

        //return array
        return [
            'isMisMatched'    => $isMisMatched,
            'msgResponse'   => "API tenant data mismatched! <br/> {$implodeMsgResponse}"
        ];

    }

    /**
     * This will check if agencies has tokens and if connected to any API's
     *
     * @param int $agency_id
     * 
     * @return false/array agency_api_tokens data > check agency_api table for api's details
     */
    public function agencyIsConnectedToAPI($agency_id)
    {

        //Check fOr all api's like PME/TREE/PALACE apart from Console > console has separate flow and db tables for tokens
        $params = array(
            'sel_query' => 'api_id, access_token, refresh_token',
            'active' => 1,
            'agency_id' => $agency_id
        );
        $api_token_q = $this->get_agency_api_tokens($params)->row_array();

        //Check for Console only
        $cak_str = "SELECT id, api_key, office_id, agency_id
            FROM `console_api_keys`
            WHERE `agency_id` = ?
            AND active = 1";
        $cak_q = $this->db->query($cak_str, $agency_id)->row_array();

        if(!empty($api_token_q)){
            //Connected to any api but not Console
            return [
                'api_id'        => $api_token_q['api_id'],
                'agency_id'     => $api_token_q['agency_id'],
                'access_token'  => $api_token_q['access_token'],
                'refresh_token' => $api_token_q['refresh_token']
            ];
        }elseif(!empty($cak_q)){
            //Connected to Console id = 5
            return [
                'api_id'        => 5,
                'agency_id'     => $cak_q['agency_id'],
                'api_key'  => $api_token_q['api_key'],
                'office_id' => $api_token_q['office_id']
            ];
        }else{
            return false;
        }

    }

    /**
     * This function will check if property is currently connected to API or Not
     * This will fetched apai_property_data and console_properties table only and check if exist/connected
     * Note: Must no API call here
     * 
     * @param int $api_id
     * @param int $prop_id
     * 
     * @return false/array
     */
    public function propertyIsConnectedToAPI($api_id, $prop_id)
    {
        if(empty($api_id)){
            log_message('error', 'propertyIsConnectedToAPI: Empty api_id');
            return false;
        }

        if(empty($prop_id)){
            log_message('error', 'propertyIsConnectedToAPI: Empty property_id');
            return false;
        }

        $response = [];

        if($api_id == 5){
        //CONSOLE API CHECK

            //API is console > check if property is connected to api console
            $cak_sql2 = "SELECT cp.console_prop_id
                FROM `property` AS p
                INNER JOIN `console_properties` AS cp ON p.`property_id` = cp.`crm_prop_id`
                WHERE cp.`active` = 1
                AND cp.`crm_prop_id` = ?";
            $console_row = $this->db->query($cak_sql2, $prop_id)->row_array();
            $api_prop_id = $console_row['console_prop_id'];

            if(!empty($api_prop_id)){
                return $response[] = [
                            'api_prop_id'    => $api_prop_id,
                            'api_type_id'   => 5
                        ];
            }

        }else{
        //API's CHECK (NOT CONSOLE)

            //API is not console > check api's data (PME|PTREE|OurTradie|Palace)
            $api_prop_data_row = $this->db->get_where('api_property_data',['crm_prop_id' => $prop_id, 'active' => 1])->row_array();
            $api_prop_id = $api_prop_data_row['api_prop_id'];
            $api_type_id = $api_prop_data_row['api'];

            if(!empty($api_prop_id)){
                return $response[] = [
                            'api_prop_id'    => $api_prop_id,
                            'api_type_id'   => $api_type_id
                        ];
            }

        }

        return false;
    }


    /**
     * This function will get all api tenants by property_id
     * Created in function for easy reuse and git rid of redanduncy
     * This might be use for comparing between api's and crm tenants and and fetching api tenants in VPD/VJD
     * VJD and VJD api tenants request might need to redo and use this function instead once check between api and crm tenants is done.
     * 
     * @param int $prop_id
     * 
     * @return array > array of tenants based on api property connected
     */
    public function get_apis_tenants_v2($prop_id)
    {

        $this->load->model('properties_model'); 
        $this->load->model('property_tree_model'); 
        $this->load->model('palace_model'); 
        $this->load->model('console_model');

        if(empty($prop_id)){
            log_message('error', 'get_apis_tenants_v2: Empty/invalid property id');
            return false;
        }

        //Property details
        $prop_q_str = "SELECT p.agency_id 
            FROM property as p
            WHERE p.property_id = ?";
        $prop_q = $this->db->query($prop_q_str, $prop_id)->row_array();
       
        //vars
        $agency_id = $prop_q['agency_id'];

        //Check agency tokens
        $agencyIsConnectedToAPI = $this->agencyIsConnectedToAPI($agency_id);
        
        $enableApi = false;
        $api_tenants_arr = [];
        $connTextApi = "";
        $controlerApi = "";
        $api_coonection_det_url = "";
        $prop_is_connected_to_api = false;

        //Agency is connected to API
        if($agencyIsConnectedToAPI !== FALSE){

            //Agency is connected to API flag
            $enableApi = true;

            //Separate process for Console API
            if($agencyIsConnectedToAPI['api_id'] == 5){

                $connTextApi = "Console";
                $controlerApi = "console";

                //Check if property is currently connected to any API's
                $prop_is_connected_to_api_data = $this->propertyIsConnectedToAPI($agencyIsConnectedToAPI['api_id'], $prop_id);

                //Property is currently connected to Console API
                if($prop_is_connected_to_api_data !== FALSE){
                    
                    /**
                     * NOW start Console API call here
                     */

                    $prop_is_connected_to_api = true;
                    $api_coonection_det_url = "/{$controlerApi}/connection_details/{$prop_id}";

                    $console_tenant_sql_str = "
                        SELECT *
                        FROM `console_property_tenants` AS cpt
                        INNER JOIN `console_properties` AS cp ON cpt.`console_prop_id` = cp.`console_prop_id`
                        WHERE cp.crm_prop_id = $prop_id
                        AND cpt.active = 1
                        AND cpt.`is_landlord` = 0
                    ";
                    $console_tenant_sql = $this->db->query($console_tenant_sql_str);

                    foreach( $console_tenant_sql->result() as $console_tenant_row ){

                        // get console tenants phones
                        $console_tent_phone_str = "
                            SELECT *
                            FROM `console_property_tenant_phones` AS cpt_phones
                            INNER JOIN `console_property_tenants` AS cpt ON cpt_phones.`contact_id` = cpt.`contact_id`
                            WHERE cpt.`contact_id` = {$console_tenant_row->contact_id}                         
                            AND cpt_phones.`active` = 1
                        ";
                        $console_tent_phone_sql = $this->db->query($console_tent_phone_str);

                        $console_tent_phone_arr = [];
                        foreach ($console_tent_phone_sql->result() as $console_tent_phone_row){
                            
                            $console_tent_phone_arr[] = array(
                                'type'      => trim($console_tent_phone_row->type),
                                'number'    => trim($console_tent_phone_row->number),
                                'primary'   => trim($console_tent_phone_row->is_primary)
                            );

                        }

                        // get console tenants emails
                        $console_tent_email_str = "SELECT *
                            FROM `console_property_tenant_emails` AS cpt_emails
                            INNER JOIN `console_property_tenants` AS cpt ON cpt_emails.`contact_id` = cpt.`contact_id`
                            WHERE cpt.`contact_id` = {$console_tenant_row->contact_id}                       
                            AND cpt_emails.`active` = 1";
                        $console_tent_email_sql = $this->db->query($console_tent_email_str);

                        $console_tent_email_arr = [];
                        foreach ( $console_tent_email_sql->result() as $console_tent_email_row ){ 
                            
                            $console_tent_email_arr[] = array(
                                'type'      => trim($console_tent_email_row->type),
                                'email'     => trim($console_tent_email_row->email),
                                'primary'   => trim($console_tent_email_row->is_primary)
                            );

                        }

                        $api_tenants_arr[] = array(
                            'fname'                 => trim($console_tenant_row->first_name),
                            'lname'                 => trim($console_tenant_row->last_name),
                            'phone'                 => $console_tent_phone_arr,
                            'email'                 => $console_tent_email_arr,
                            'UpdatedOn'             => NULL,
                            'api_compare_details'   => trim($console_tenant_row->first_name)." ".trim($console_tenant_row->last_name)
                        );

                    }

                }

                //property is not connected to Console
                $api_coonection_det_url = "/{$controlerApi}/to_connect/{$prop_id}";

            }else{
                //API's process excluded Console

                //Check if property is currently connected to any API's
                $prop_is_connected_to_api_data = $this->propertyIsConnectedToAPI($agencyIsConnectedToAPI['api_id'], $prop_id);
                $api_prop_id = $prop_is_connected_to_api_data['api_prop_id'];
                $api_type_id = $prop_is_connected_to_api_data['api_type_id'];

                if($prop_is_connected_to_api_data !== FALSE){
                    //Property is currenty connected to API

                    $prop_is_connected_to_api = true;

                    if($api_type_id == 1){
                        //PME tenant API call here

                        $connTextApi = "PropertyMe";
                        $controlerApi = "property_me";
                        $api_coonection_det_url = "/{$controlerApi}/property/{$prop_id}/{$agency_id}";

                        $pme_tenant_req = $this->get_pme_tenant_v2($agency_id, $api_prop_id);
                       
                       if(!empty($pme_tenant_req)){
                            $pme_contact_list_req = $this->get_pme_contact_v2($agency_id,$pme_tenant_req[0]->ContactId);

                            foreach($pme_contact_list_req->ContactPersons as $val) {
                                $api_tenants_arr[] = array(
                                    'fname'                 => trim($val->FirstName),
                                    'lname'                 => trim($val->LastName),
                                    'fullname'              => $val->FullName,
                                    'mobile'                => str_replace(' ', '', trim($val->CellPhone)),
                                    'landline'              => str_replace(' ', '', trim($val->HomePhone)),
                                    'email'                 => trim($val->Email),
                                    'UpdatedOn'             => trim($pme_contact_list_req->Contact->UpdatedOn),
                                    'api_compare_details'   => trim($val->FirstName)." ".trim($val->LastName)." ".str_replace(' ', '', trim($val->CellPhone))." ".str_replace(' ', '', trim($val->HomePhone))
                                );
                            }
                       }

                    }elseif($api_type_id == 3){
                        //PTREE tenant API call here

                        $connTextApi = "MRI Property Tree";
                        $controlerApi = "property_tree";
                        $api_coonection_det_url = "/{$controlerApi}/connection_details/{$prop_id}";

                        //Get property api details
                        $pTree_req = $this->property_tree_model->get_property($prop_id);

                        //Check if response is array
                        //It should be array else there's issue with api data like woring api_prop_id
                        //This check is a must in order to still show normal crm tenant even if there's issue with api_prop_id
                        if(is_array($pTree_req)){

                            //GEt proerty api contacts
                            if(!empty($pTree_req[0]->tenancy)){

                                $pTree_tenant_req = $this->property_tree_model->get_tenant($agency_id,$pTree_req[0]->tenancy);

                                foreach($pTree_tenant_req->contacts as $pt_tenant_row) {

                                    //Get Tenant contact type only
                                    if(in_array('Tenant', $pt_tenant_row->contact_types)){
                                        $api_tenants_arr[] = array(
                                        'fname'                     => trim($pt_tenant_row->first_name),
                                        'lname'                     => trim($pt_tenant_row->last_name),
                                            'mobile'                => str_replace(' ', '', trim($pt_tenant_row->mobile_phone_number)),
                                            'landline'              => str_replace(' ', '', trim($pt_tenant_row->phone_number)),
                                            'email'                 => trim($pt_tenant_row->email_address),
                                            'UpdatedOn'             => NULL,
                                            'api_compare_details'   => trim($pt_tenant_row->first_name)." ".trim($pt_tenant_row->last_name)." ".str_replace(' ', '', trim($pt_tenant_row->mobile_phone_number))." ".str_replace(' ', '', trim($pt_tenant_row->phone_number))
                                        );
                                    }

                                }
                                
                            }
                            
                        }
                        

                    }elseif($api_type_id == 4){
                        //PALACE tenant API call here

                        $connTextApi = "Palace";
                        $controlerApi = "palace";
                        $api_coonection_det_url = "/{$controlerApi}/property/{$prop_id}/{$agency_id}";

                        $palace_api_params = array(
                            'palace_prop_id' => $api_prop_id,
                            'agency_id' => $agency_id
                        );
                        $tenant_json_dec = $this->palace_model->get_tenants_by_property($palace_api_params);

                        foreach($tenant_json_dec as $tenant_json_data) {

                            $palace_tenant_obj_row = $tenant_json_data->TenancyTenants[0];

                            $api_tenants_arr[] = array(
                                'fname'                 => trim($palace_tenant_obj_row->TenantFirstName),
                                'lname'                 => trim($palace_tenant_obj_row->TenantLastName),
                                'mobile'                => str_replace(' ', '', $palace_tenant_obj_row->TenantPhoneMobile),
                                'landline'              => str_replace(' ', '', $palace_tenant_obj_row->TenantPhoneHome),
                                'email'                 => trim($palace_tenant_obj_row->TenantEmail),
                                'UpdatedOn'             => NULL,
                                'api_compare_details'   => trim($palace_tenant_obj_row->TenantFirstName)." ".trim($palace_tenant_obj_row->TenantLastName)." ".str_replace(' ', '', $palace_tenant_obj_row->TenantPhoneMobile)." ".str_replace(' ', '', $palace_tenant_obj_row->TenantPhoneHome)
                            );

                        }
                    }elseif($api_type_id == 6){
                        //Ourtradie tenant API call here
                        
                        $api = new OurtradieApi();

                        $connTextApi = "OurTradie";
                        $controlerApi = "ourtradie";
                        $api_coonection_det_url = "/{$controlerApi}/property/{$prop_id}/{$agency_id}";

                        $access_token = $agencyIsConnectedToAPI['access_token'];
                        $tmp_ref_token   = $agencyIsConnectedToAPI['refresh_token'];
                        $tmp_arr_ref_token = explode("+/-]",$tmp_ref_token);

                        $ot_agency_id = $tmp_arr_ref_token[1];
                        $_SESSION['ot_agency_id'] = $agency_id;

                        $params = array(
                            'Skip' 	    => 'No',
                            'Count'     => 'No',
                            'AgencyID'  => $ot_agency_id
                        );
                        $token = array('access_token' => $access_token);
                        $property = $api->query('GetAllResidentialProperties', $params, '', $token, true);

                        $data_property = array();
                        $data_property = json_decode($property, true);

                        $property_list = array_filter($data_property, function ($v) {
                        return $v !== 'OK';
                        });

                        foreach($property_list['data'] as $prop) {
        
                            if($prop['ID'] == $api_prop_id){
                                
                                foreach($prop['Tenant_Contacts'] as $api_tenant_row){
                                    $api_tenants_arr[] = array(
                                        'fname'                 => trim($api_tenant_row['FirstName']),
                                        'lname'                 => trim($api_tenant_row['LastName']),
                                        'mobile'                => str_replace(' ', '', $api_tenant_row['Mobile']),
                                        'landline'              => null,
                                        'email'                 => trim($api_tenant_row['Email']),
                                        'UpdatedOn'             => NULL,
                                        'api_compare_details'   => trim($api_tenant_row['FirstName'])." ".trim($api_tenant_row['LastName'])." ".str_replace(' ', '', $api_tenant_row['Mobile'])
                                    );
                                }
                            }
                        }
                    }

                }

            }

            return [
                'connTextApi'               => $connTextApi,
                'controlerApi'              => $controlerApi,
                'api_coonection_det_url'    => $api_coonection_det_url,
                'prop_is_connected_to_api'  => $prop_is_connected_to_api,
                'enableApi'                 => $enableApi,
                'api_tenants_arr'           => $api_tenants_arr,
                'agency_api'                => $agencyIsConnectedToAPI['api_id']
            ];
            
        }

        return [];

    }
    
    /**
     * This function will handle and check VPD/VJD tenant/property api connected or not connected warning/error message
     * This function fetched data to our DB only and return string and no any API call at all
     * In this case we will minimize API call on page load
     * API call for api property check if archived or not will happen only when 'CHECK' button/link is clicked
     * 
     * @param int $agency_id
     * @param int $prop_id
     * 
     * @return false/array
     */
    public function vjd_vpd_apis_error_warning_message($prop_id)
    {
        if(empty($prop_id)){
            log_message('error', 'vjd_vpd_apis_error_warning_message: Empty prop_id');
            return false;
        }

        //get agency_id
        $agency_id_q = $this->db->select('agency_id')->from('property')->where(['property_id' => $prop_id])->get()->row_array();
        $agency_id = $agency_id_q['agency_id'];

        //check agency if connected to api
        $agencyIsConnectedToAPI = $this->agencyIsConnectedToAPI($agency_id);

        if($agencyIsConnectedToAPI !== false){
            //Agency is connected

            switch ($agencyIsConnectedToAPI['api_id']) {
                case 5:
                    // Console message
                    $connTextApi = "Console";
                    $controlerApi = "console";
                    $api_coonection_det_url = "/{$controlerApi}/to_connect/{$prop_id}";

                    break;
                case 1:
                    // PME message

                    $connTextApi = "PropertyMe";
                    $controlerApi = "property_me";
                    $api_coonection_det_url = "/{$controlerApi}/property/{$prop_id}/{$agency_id}";

                    break;
                case 3:
                    //PTree message

                    $connTextApi = "MRI Property Tree";
                    $controlerApi = "property_tree";
                    $api_coonection_det_url = "/{$controlerApi}/connection_details/{$prop_id}";
                    
                    break;
                case 4:
                    // PALACE message

                    $connTextApi = "Palace";
                    $controlerApi = "palace";
                    $api_coonection_det_url = "/{$controlerApi}/property/{$prop_id}/{$agency_id}";

                    break;
                case 6:
                    // Ourtradie message

                    $connTextApi = "OurTradie";
                    $controlerApi = "ourtradie";
                    $api_coonection_det_url = "/{$controlerApi}/property/{$prop_id}/{$agency_id}";

                    break;
                default:
                    // code block executed if no match

            }

            //check property if connected to api
            $propertyIsConnectedToAPI = $this->propertyIsConnectedToAPI($agencyIsConnectedToAPI['api_id'], $prop_id);
    
            if($propertyIsConnectedToAPI !== false){
                //Property is connected to api
                //Do api related error/warning message here

                $api_type_id = $propertyIsConnectedToAPI['api_type_id'];

                //Catch here and add different console connection detail page link if property is connected to API
                //Applicable for Console only becase only Console has different url for connection details
                if($api_type_id == 5){
                    $api_coonection_det_url = "/{$controlerApi}/connection_details/{$prop_id}";
                }

                return [
                    'api_type_id'           => $api_type_id,
                    'propertyIsConnected'   => true,
                    'message'               => "This Property is connected to {$connTextApi} <a href='{$api_coonection_det_url}'>View {$connTextApi}</a>. <i class='font-icon font-icon-warning font-icon-inlinev2'></i> <a id='ajax_check_api_property_status' href='javascript:void(0);'>CHECK</a> status. <span id='api_prop_status_response_box'></span>",
                ];
                
            }else{

                return [
                    'api_type_id'           => null,
                    'propertyIsConnected'   => false,
                    'message'               => "This Property needs connecting to {$connTextApi} <a href='{$api_coonection_det_url}'>Connect Now</a>",
                ];

            }

        }

        return false;
    }

    /**
     * This method will check if api properties is archieved
     * 
     * @param int $prop_id
     * 
     * @return false/array
     */
    public function apiPropertyIsArchived($prop_id)
    {

        if(empty($prop_id)){
            log_message('error', 'apiPropertyIsArchived: Empty prop_id');
            return false;
        }

        //get agency_id
        $agency_id_q = $this->db->select('agency_id')->from('property')->where(['property_id' => $prop_id])->get()->row_array();
        $agency_id = $agency_id_q['agency_id'];

        if(empty($agency_id)){
            log_message('error', 'apiPropertyIsArchived: Empty agency_id');
            return false;
        }

        //check agency if connected to api
        $agencyIsConnectedToAPI = $this->agencyIsConnectedToAPI($agency_id);

        if($agencyIsConnectedToAPI !== false){
            //Agency is connected

            //check property if connected to api
            $propertyIsConnectedToAPI = $this->propertyIsConnectedToAPI($agencyIsConnectedToAPI['api_id'], $prop_id);

            $isActive = FALSE;
            if($propertyIsConnectedToAPI !== false){

                $api_type_id = $propertyIsConnectedToAPI['api_type_id'];
                $api_prop_id = $propertyIsConnectedToAPI['api_prop_id'];

                switch ($api_type_id) {
                    case 1:
                        //PME archived check
        
                        $pme_params = array(
                            'agency_id' => $agency_id,
                            'prop_id'   => $api_prop_id
                        );
                        $pme_prop_json = $this->pme_model->get_property($pme_params);
                        $pme_prop_json_dec = json_decode($pme_prop_json);
                        if( $pme_prop_json_dec->IsArchived == true ){
                            $error_popup = "Deactivated in PropertyMe";
                        }else{
                            $error_popup = "Active in PropertyMe";
                            $isActive = TRUE;
                        }
                        
                        break;
                    case 3:
                        // PTREE archieved check
        
                        $pTree_req = $this->property_tree_model->get_property($prop_id);

                        //Check if response is an array
                        //If array > means no error in response
                        //Else there's error > perhaps caused by wrong api_prop_id
                        if(is_array($pTree_req)){
                            if($pTree_req[0]->archived == true || $pTree_req[0]->deleted == true){
                                $error_popup = "Deactivated in PropertyTree";
                            }else{
                                $error_popup = "Active in PropertyTree";
                                $isActive = TRUE;
                            }
                        }else{
                            //response is object
                            //means it has error
                            //show error code and message
                            $errCode = $pTree_req->Code;
                            $errMsg = $pTree_req->ErrorMessage;
                            
                            $error_popup = "Code: {$errCode} <br/> ErrorMessage: $errMsg";
                        }
                        
                        break;
                    case 4:
                        // PALACE archieved check
        
                        $palace_api_params = array(
                            'agency_id' => $agency_id,
                            'palace_id' => $api_prop_id
                        );
                
                        $palace_prop_json = $this->palace_model->get_all_property_by_prop_code($palace_api_params);
                
                        if( $palace_prop_json->PropertyArchived == true ){
                            $error_popup = "Deactivated in Palace";
                        }else{
                            $error_popup = "Active in Palace";
                            $isActive = TRUE;
                        }
        
                        break;
                    case 5:
                        // CONSOLE archieved check
                        $error_popup = null;
                        break;
                    case 6:
                        // Ourtradie archieved check
                        $error_popup = null;
                        break;
                    default:
                        $error_popup = null;
                        // Code block executed if no match
                }

                if(!empty($error_popup)){
                    return [
                        'error_popup'   => $error_popup,
                        'isActive'      => $isActive
                    ];
                }

            }

        }

        return false;
        
    }

    /**
     * Display/show api name 
     * eg. Console, PME, PTREE etc.
     * 
     * @param int $api_type_id
     * 
     * @return string
     */
    public function apiName($api_type_id)
    {

        if(empty($api_type_id)){
            return;
        }

        $q = "SELECT api_name FROM agency_api WHERE agency_api_id = ?";
        $api_name_row = $this->db->query($q, $api_type_id)->row_array();

        return $api_name_row['api_name'];

    }
		
		
}
