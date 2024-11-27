<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Property_Tree extends MY_Controller {

    function __construct(){

        parent::__construct();
		$this->load->model('property_tree_model');
        $this->load->model('properties_model');
        $this->load->model('inc/job_functions_model');
        $this->load->model('inc/alarm_functions_model');        
        $this->load->model('inc/pdf_template');
        
    }


    public function bulk_connect() {

        $this->load->model('api_model');
        
        $data['title'] = 'Property Tree Bulk Match';
        $country_id = $this->config->item('country');
        $uri = '/property_tree/bulk_connect';
        $data['uri'] = $uri;

        $api_id = 3; // Property Tree 
        
        $sel_query = "
            agen_api_tok.`agency_api_token_id`, 
            
            a.`agency_id`,
            a.`agency_name`,
            a.`no_bulk_match`
        ";
        $api_token_params = array(
            'sel_query' => $sel_query,
            'active' => 1,
            'api_id' => $api_id,
            'deactivated' => 1,
            'target' => 1,
            'group_by' => 'agen_api_tok.`agency_id`',
            'join_table' => array('agency'),
            'sort_list' => array(
                array(
                    'order_by' => 'a.agency_name',
                    'sort' => 'ASC'
                )
            ),
            'display_query' => 0            
        );
        $agencyQuery = $this->api_model->get_agency_api_tokens($api_token_params);        
        $data['agenList'] = $agencyQuery;

        $this->load->view('templates/inner_header', $data);
        $this->load->view('api/prop_tree_bulk_connect',$data);
        $this->load->view('templates/inner_footer', $data);
       
    }


    // check if note already exist
    public function if_notes_already_exist($params) {

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


    // get CRM list
    public function ajax_bulk_connect_get_crm_list(){
        
        //$agency_id = 1448;
        $agency_id = $this->input->get_post('agency_id');
        $sel_query = "
            p.`property_id`,
            p.`address_1`,
            p.`address_2`,
            p.`address_3`,
            p.`state`,
            p.`postcode`,
            p.`is_sales`
        ";
        $this->db->select($sel_query);
        $this->db->from('property AS p');
        $this->db->join('api_property_data AS apd', 'p.property_id = apd.crm_prop_id','left');
        $this->db->where('p.agency_id', $agency_id);
        $this->db->where('p.deleted', 0);
        $this->db->where("( p.is_nlm = 0 OR p.is_nlm IS NULL )");
        $this->db->where("( apd.crm_prop_id = '' OR apd.crm_prop_id IS NULL )");
        $lists = $this->db->get();    
    ?>
        <table id="crmProp" class="display table table-striped table-borderless" cellspacing="0" width="100%">
            <thead>
                <tr>		
                    <th class="chk_col">
                        <span class="checkbox">
                            <input type="checkbox" id="check-all" class="check-all">
                            <label for="check-all" class="chk_lbl"></label>
                        </span>
                    </th>							
                    <th class="address_col">Address</th>
                    <th style="display: none;"></th>						
                    <th style="display: none;"></th>
                    <th class="col_crm_btn"></th>
                    <th></th>
                </tr>
            </thead>							
            <tbody>
                <?php 
                    $note_ctr=1;
                    foreach ($lists->result() as $index => $row) { 

                    // sales property
					$sales_txt = ( $row->is_sales == 1 )?'(Sales)':null;
            
                    $prop_address = trim("{$row->address_1} {$row->address_2}, {$row->address_3} {$row->state} {$row->postcode} {$sales_txt}");
                ?>
                    <tr>	
                        <td class="chk_col">
                            <span class="checkbox">
                                <input type="checkbox" id="check-<?php echo $index; ?>" class="chk_prop">
                                <label for="check-<?php echo $index; ?>" class="chk_lbl"></label>
                            </span>
                        </td>										
                        <td class="crmAdd" data-crm_prop_link="<?php echo "{$this->config->item('crm_link')}/properties/details/?id={$row->property_id}"; ?>"><?=$prop_address?></td>
                        <td style="display: none;"><?=$row->property_id?></td>											
                        <td style="display: none;" class="sort_index">0</td>   
                        <td>
                            <input type="hidden" class="crm_full_address" value="<?php echo $prop_address; ?>" />
                            <input type="hidden" class="crm_addr_street_num" value="<?php echo $row->address_1; ?>" />
                            <input type="hidden" class="crm_addr_street_name" value="<?php echo $row->address_2; ?>" />                            
                            <input type="hidden" class="crm_addr_suburb" value="<?php echo $row->address_3; ?>" />
                            <input type="hidden" class="crm_addr_state" value="<?php echo $row->state; ?>" />
                            <input type="hidden" class="crm_addr_postcode" value="<?php echo $row->postcode; ?>" />
                            <input type="hidden" class="crm_prop_id" value="<?php echo $row->property_id; ?>" />   
                            
                            <input type="hidden" class="note_btn_class" value="<?php echo "crm_note_btn{$note_ctr}"; ?>" />
                            
                            

                            <?php 
                            
                             // check if already exist
                            $sv_notes_params = array(
                                'property_id' => $row->property_id,
                                'property_source' => 1
                            );
                            if( $this->if_notes_already_exist($sv_notes_params) == true ){ ?>
                                <button type="button" class="btn btn-primary jFaded" disabled="">Pending Verification</button>
                            <?php
                            }else{?>
                                <button type="button" class="btn btn-primary verify_nlm_btn crm_note_btn<?php echo $note_ctr; ?>" >PNV</button>
                            <?php
                            }
                            
                            ?>
                        </td>
                        <td><span class="fa fa-arrows-h match_arrow"></span></td>                  								
                    </tr>
                <?php
                    $note_ctr++;
                    }
                ?>
            </tbody>
        </table>
        <script>
        // crm datatable initialize
        $.fn.DataTable.ext.pager.numbers_length = 5;
        var crmTable = $('#crmProp').DataTable( {
        
            'bPaginate': true,
            'pageLength': 50,
            'lengthChange': true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            'columnDefs': [
                {
                    'targets': [0, 4, 5],
                    'orderable': false
                }
            ],           
            'order': [[1, 'asc']]

        });
        
        
        // sortable rows
        $( "#crmProp" ).sortable({

			items: "tr",
			cursor: 'move',
			opacity: 0.6,
			update: function() {
			}
            
        });
    

        </script>
    <?php  

    }


    // get Property Tree list
    public function ajax_bulk_connect_get_api_list(){ 

        $api_id = 3; // Property Tree

        $this->load->model('agency_api_model');
        $show_all_hidden_prop = $this->input->get_post('show_all_hidden_prop');
        $agency_id = $this->input->get_post('agency_id');
        $hide_pme_archived_prop = $this->input->get_post('hide_pme_archived_prop');

        $prop_tree_list = $this->property_tree_model->get_all_properties($agency_id);
        
        ?>
        <table id="pmeProp" class="display table table-borderless" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="address_col">Address</th>
                    <th style="display: none;"></th>
                    <th style="display: none;"></th>
                    <th class="col_pme_prop_chk">
                         <span class="checkbox">
                            <input type="checkbox" id="pme_prop_chk_all" />
                            <label for="pme_prop_chk_all"></label>
                        </span>
                    </th>
                    <th class="col_pme_btn d-none"></th>
                </tr>
            </thead>                        
            <tbody>
            <?php 
            $note_ctr = 1;           
            
            // get all connected properties
            $crm_connected_prop_sql_str = "
            SELECT `api_prop_id`
            FROM `api_property_data`
            WHERE `crm_prop_id` != ''
            AND `api` = {$api_id}
            ";
            $crm_connected_prop_sql = $this->db->query($crm_connected_prop_sql_str);

            $api_prop_id_arr = [];
            foreach( $crm_connected_prop_sql->result() as $crm_conn_prop_row ){
                $api_prop_id_arr[] = $crm_conn_prop_row->api_prop_id; 
            }

            foreach ( $prop_tree_list as $key => $address_obj_row ) {  
                                                             
                $hide_row = false;
                $address_obj_row_hl_class = null;              
                
                $api_prop_id = $address_obj_row->id;
                $address_obj = $address_obj_row->address;
                
                // street
                if( $address_obj->unit != '' && $address_obj->street_number != '' ){
                    $street_unit_num = "{$address_obj->unit}/{$address_obj->street_number}";
                }else if( $address_obj->unit != '' ){
                    $street_unit_num = "{$address_obj->unit}";
                }else if( $address_obj->street_number != '' ){
                    $street_unit_num = "{$address_obj->street_number}";
                }
                    
                $pt_prop_add = "{$street_unit_num} {$address_obj->address_line_1}, {$address_obj->suburb} {$address_obj->state} {$address_obj->post_code}";    

                //hide archive
                if( $hide_pme_archived_prop == 1 ){
                    if( $address_obj_row->archived == true ){
                        $hide_row = true;
                    }
                }

                // if API property already connected
                if( in_array($api_prop_id, $api_prop_id_arr) ){
                    $hide_row = true; // hide row
                }

                // check if property is set as hidden
                $api_id = 3; // PropertyMe
                $agency_api_model_params = array(
                    'api_prop_id' => $api_prop_id,                                                                
                    'agency_id' => $agency_id,
                    'api_id' => $api_id,
                );                                                       
                

                $is_api_property_hidden = $this->agency_api_model->is_api_property_hidden($agency_api_model_params);
                if( $is_api_property_hidden == true ){
                    $address_obj_row_hl_class = 'pme_hidden_row';
                }    

                $hide_row_api_property = false;  
                if( $is_api_property_hidden == true && $show_all_hidden_prop != 1 ){
                    $hide_row_api_property = true;
                }

                if( $address_obj_row->archived == true || $address_obj_row->deleted == true ){
                    $address_obj_row_hl_class = 'pme_archived_row';
                }

                if( $hide_row == false && $hide_row_api_property == false){
            ?>
            <tr class="<?php echo $address_obj_row_hl_class; ?>">
                <td class="pmeAdd"><?php echo $pt_prop_add; ?></td>
                <td style="display: none;"><?=$api_prop_id?></td>
                <td style="display: none;" class="sort_index">0</td>
                <td>
                    <span class="checkbox">
                        <input type="checkbox" id="pme_prop_chk-<?php echo $key; ?>" class="pme_prop_chk api_prop_chk">
                        <label for="pme_prop_chk-<?php echo $key; ?>"></label>
                    </span>
                </td>
                <td class="d-none">
                    <input type="hidden" class="pme_full_address" value="<?php echo $pt_prop_add; ?>" />

                    <input type="hidden" class="pme_addr_unit" value="<?php echo $address_obj->unit; ?>" />
                    <input type="hidden" class="pme_addr_number" value="<?php echo $address_obj->street_number; ?>" />
                    <input type="hidden" class="pme_addr_street" value="<?php echo $address_obj->address_line_1; ?>" />
                    <input type="hidden" class="pme_addr_suburb" value="<?php echo $address_obj->suburb; ?>" />                            
                    <input type="hidden" class="pme_addr_postalcode" value="<?php echo $address_obj->post_code; ?>" />
                    <input type="hidden" class="pme_addr_state" value="<?php echo $address_obj->state; ?>" />

                    <input type="hidden" class="pme_addr_text" value="<?php echo $pt_prop_add; ?>" />                                                                          
                    
                    <input type="hidden" class="pme_prop_id" value="<?php echo $api_prop_id; ?>" />
                    <input type="hidden" class="api_prop_id" value="<?php echo $api_prop_id; ?>" />                                         

                    <input type="hidden" class="note_btn_class" value="<?php echo "pme_note_btn{$note_ctr}"; ?>" />
                
                    <button type="button" class="btn btn-primary btn_add_prop_indiv">Add Property</button>   
                    <?php 
                    if( $is_api_property_hidden == true ){ ?>
                        <button type="button" class="btn btn-primary btn_unhide_api_prop">Unhide</button>
                    <?php
                    }else{ ?>
                        <button type="button" class="btn btn-success btn_hide_api_prop">Hide</button>
                    <?php
                    }
                    ?>                                                      
                    <input type="hidden" class="pnv_id" />                    
                </td>
            </tr>
            <?php    
                }         
            }
            ?>
            </tbody>
        </table>
        <div id="btn_add_prop_div" class="d-none">
            <button type="button" id="btn_add_prop" class="btn btn-primary">Add Property</button>
            <button type="button" id="btn_hide_prop_bulk" class="btn btn-primary">Hide Property</button>
            <button type="button" id="btn_unhide_prop_bulk" class="btn btn-primary">Unhide Property</button>
        </div>
        <script>
        // pme datatable initialize
        $.fn.DataTable.ext.pager.numbers_length = 5;
        var pmeTable = $('#pmeProp').DataTable({

            'bPaginate': true,
            'pageLength': 50,
            'lengthChange': true,  
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],  
            'columnDefs': [
                {
                    'targets': [3,4],
                    'orderable': false
                }
            ],       
            'order': [[0, 'asc']]

        });

      
        // sortable rows
        $( "#pmeProp" ).sortable({

            items: "tr",
            cursor: 'move',
            opacity: 0.6,
            update: function() {
            }

        }); 
      
        
        </script>
        <?php

    }

    public function bulk_connect_all() {

        $agency_filter = $this->input->get_post('agency_id');
        $crmArr = $this->input->get_post('crmArr');
        $pmeArr = $this->input->get_post('pmeArr');
        $connect_deleted_nlm_prop = $this->input->get_post('connect_deleted_nlm_prop');

        $api = 3; // Property Tree API

        for ($i=0; $i < count($pmeArr); $i++) { 

            //$status = $this->properties_model->payableCheck($crmArr[$i]);

            // if property service serviced to SATS and propert is NLM, update property service to NR and clear NLM
            $this->db->query("
            UPDATE `property_services` AS ps
            LEFT JOIN `property` AS p ON ps.`property_id` = p.`property_id`
            SET 
                ps.`service` = 2,
                ps.`is_payable` = 0,
                p.`is_nlm` = NULL,
                p.agency_deleted = 0,
                p.`nlm_timestamp` = NULL
            WHERE ps.`property_id` = {$crmArr[$i]}
            AND ps.`service` = 1
            AND p.`is_nlm` = 1
            ");
            $updated = $this->db->affected_rows();

            if( $updated > 0 ){

                // insert property log
                $params = array(
                    'title' => 91, // PropertyTree API
                    'details' => "Property services were updated to No Response if the service was ".$this->config->item('company_name_short').", and the property restored from it's NLM status",
                    'display_in_vpd' => 1,            
                    'created_by_staff' => $this->session->staff_id,
                    'property_id' => $crmArr[$i]
                );
                $this->system_model->insert_log($params);

            }

            // Insert job log
            if( $connect_deleted_nlm_prop == 1 ){
                
               $log_title = 65; // Property Update
               $log_details = "Property was restored from NLM by connecting on <b>PropertyTree</b> bulk match.";
               $log_params = array(
                   'title' => $log_title, 
                   'details' => $log_details,
                   'display_in_vpd' => 1,
                   'created_by_staff' => $this->session->staff_id,
                   'property_id' => $crmArr[$i]
               );
               $this->system_model->insert_log($log_params);

            }

            // insert property log
            $params = array(
                'title' => 91, // PropertyTree API
                'details' => 'Property <b>Linked</b> to <b/>PropertyTree</b> on Bulk Match',
                'display_in_vpd' => 1,            
                'created_by_staff' => $this->session->staff_id,
                'property_id' => $crmArr[$i]
            );
            $this->system_model->insert_log($params);

            $check = $this->properties_model->apiCheck($crmArr[$i]);

            if( $pmeArr[$i] != '' ){

                // check if API prop Id already exist
                $apd_sql = $this->db->query("
                SELECT COUNT(`id`) AS apd_count
                FROM `api_property_data`
                WHERE `crm_prop_id` = {$crmArr[$i]}
                AND `api` = {$api}
                ");
                $apd_row = $apd_sql->row();

                if( $apd_row->apd_count > 0 ){ // update

                    $update_data = array(
                        'api_prop_id' => $pmeArr[$i]
                    );
                    
                    $this->db->where('crm_prop_id', $crmArr[$i]);
                    $this->db->where('api', $api);
                    $this->db->update('api_property_data', $update_data);

                }else{ // insert

                    $data = array(
                        'crm_prop_id' => $crmArr[$i],
                        'api' => $api,
                        'api_prop_id' => $pmeArr[$i]
                    );                    
                    $this->db->insert('api_property_data', $data);

                }  
                
                if( $crmArr[$i] > 0 ){

                    // clear "cant connect to API" marker
                    $this->db->where('property_id', $crmArr[$i]);
                    $this->db->delete('property_cant_connect_to_api');
        
                }

            }
            
        }

        $updateStat = true;
        echo json_encode(array("updateStat" => $updateStat));
    }

    // bulk connect add property function 
    public function bulk_connect_add_property(){

        $this->load->model('properties_model');
        $this->load->model('agency_model');

        $agency_id = $this->input->get_post('agency_id');
        $pme_prop_arr = $this->input->get_post('pme_prop_arr');
        $disable_add = $this->input->get_post('disable_add');
        $dup_arr = [];
        $ret_str = '';
        $api_id = 3; // Property Tree

        foreach( $pme_prop_arr as $index => $pme_prop ){

            // decodes json string to actual json object
            $pme_prop_dec = json_decode($pme_prop);
            
            $pme_full_address = $pme_prop_dec->pme_full_address;
            $street_unit = $pme_prop_dec->pme_addr_unit;
            $street_num = $pme_prop_dec->pme_addr_number;
            $street_name = $pme_prop_dec->pme_addr_street;
            $suburb = $pme_prop_dec->pme_addr_suburb;
            $state = $pme_prop_dec->pme_addr_state;
            $postcode = $pme_prop_dec->pme_addr_postalcode;
    
            $lat = $pme_prop_dec->lat;
            $lng = $pme_prop_dec->lng;
            
            $pme_prop_id = $pme_prop_dec->pme_prop_id;
            $api_prop_id = $pme_prop_dec->api_prop_id;
            $key_number = $pme_prop_dec->key_number;
            $tenants_contact_id = $pme_prop_dec->tenants_contact_id;
            $owner_contact_id = $pme_prop_dec->owner_contact_id;         
           
            $street_arr = [];
           
    
            // address
            // join unit and streen num
            if( $street_unit !='' ){
                $street_arr[] = $street_unit;
            }
            if( $street_num !='' ){
                $street_arr[] = $street_num;
            }
    
            // combine
            $street_num_fin = implode("/",$street_arr);

            // split street name
            $street_name_imp = explode(" ",strtolower($street_name));

            // if st or st. is first word in street name then its 'Saint' else its 'Street'
            if( $street_name_imp[0] == 'st' || $street_name_imp[0] == 'st.' ){

                $street_name_fin = preg_replace("/\b{$street_name_imp[0]}\b/i", 'Saint', $street_name);

            }else{ // default

                $street_name_fin = $this->system_model->getStreetAbrvFullName($street_name);

            }            
            
            $check_dup_params = array(
                'street_num_fin' => $street_num_fin,
                'street_name_fin' => $street_name_fin,
                'suburb' => $suburb,
                'state' => $state,
                'postcode' => $postcode
            );
            $duplicate_query = $this->properties_model->check_duplicate_full_address($check_dup_params);
    
            if( $duplicate_query->num_rows()>0 ){ // existing property found
    
                $duplicate_row = $duplicate_query->row_array();            
                $dup_agency_id = $duplicate_row['agency_id'];
                
                $dup_arr[] = array(
                    'dup_property_id' => $duplicate_row['property_id'], 
                    'dup_property_address' => "{$duplicate_row['p_address_1']} {$duplicate_row['p_address_2']}, {$duplicate_row['p_address_3']} {$duplicate_row['p_state']} {$duplicate_row['p_postcode']}", 
                    'dup_prop_deleted' => $duplicate_row['is_nlm'],      
                    'dup_agency_id' => $duplicate_row['agency_id'],                                          
                    'dup_agency_name' => $duplicate_row['agency_name'],
                    'pme_prop_id' => $api_prop_id,
                    'api_prop_id' => $api_prop_id                                                              
                );
           
            }else{


                if( $disable_add != 1 ){

                    // Hume Community Housing Association
                    $prop_comments = '';
                    if( $agency_id==1598 ){            
                        $prop_comments = 'Please install 9vLi or 240v only. DO NOT INSTALL 240vLi';            
                    }        
        
                    // INSERT PROPERTY
                    // removed inserting lat and lng from API bec sometimes they have some weird coordinate like -999
                    // better leave it empty bec tech runs has auto-inserts of coordinate if they are empty on load                  
                    $property_data = array(
                        'agency_id' => $agency_id,
                        'address_1' => $street_num_fin,
                        'address_2' => $street_name_fin,
                        'address_3' => $suburb,
                        'state' => $state,
                        'postcode' => $postcode,
                        'added_by' => $this->session->staff_id,
                        'key_number' => $key_number,            
                        'comments' => $prop_comments
                    );
                    $add_property = $this->properties_model->add_property($property_data);
                    $prop_insert_id = $this->db->insert_id();
        
                    if( $add_property && !empty($prop_insert_id) ){

                        // connect to Property Tree property
                        if( $api_prop_id != '' ){
    
                            $data = array(
                                'crm_prop_id' => $prop_insert_id,
                                'api' => $api_id,
                                'api_prop_id' => $api_prop_id
                            );                            
                            $this->db->insert('api_property_data', $data);
                            
                        }
                                     
                        // insert property log
                        $params = array(
                            'title' => 2, //New Property Added
                            'details' => 'Added from Property Tree Bulk Match',
                            'display_in_vpd' => 1,
                            'agency_id' => $agency_id,
                            'created_by_staff' => $this->session->staff_id,
                            'property_id' => $prop_insert_id
                        );
                        $this->system_model->insert_log($params);                             
        
                    }
                    
                }                                                          
                
    
            }           
            
        }        

        echo json_encode($dup_arr);
        
    }


    public function connection_details($property_id) {

        $this->load->model('api_model');
        
        $data['title'] = 'Property Tree Connection Details Page';
        $uri = "/property_tree/connection_details/{$property_id}";
        $data['uri'] = $uri;
        
        $api_id = 3; // Property Tree
         
        if( $property_id > 0 ){

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

            // get crm property
            $sel_query = "
            p.`property_id`, 
            p.`address_1` AS p_address_1, 
            p.`address_2` AS p_address_2, 
            p.`address_3` AS p_address_3, 
            p.`state` AS p_state,
            p.`postcode` AS p_postcode,
            p.`lat`,
            p.`lng`,
            p.`comments`,

            a.`agency_id`,
            a.`agency_name`,

            c.`country` AS country_name
            ";

            $params = array(
                'sel_query' => $sel_query,                                                                
                'property_id' => $property_id,
                'join_table' => array('countries'),
                'display_query' => 0
            );
            $crm_prop_sql = $this->properties_model->get_properties($params);
            $crm_prop_row = $crm_prop_sql->row();
            $agency_id = $crm_prop_row->agency_id;

            if( $api_prop_id != '' ){
                                
                $data['crm_prop'] = $crm_prop_row;

                // get crm tenants            
                $this->db->select('*');
                $this->db->from('property_tenants');
                $this->db->where('property_id', $property_id);
                $this->db->where('active', 1);
                $query = $this->db->get();
                $data['crmTenant'] = $query->result_array();  

                // get property tree, property data            
                $api_prop_json = $this->property_tree_model->get_property($property_id);
                
                //check if reponse is array
                //response should be array
                //if array > means no request error
                //else there's a request error > means there's issue with api data like wrong api_prop_id
                if(is_array($api_prop_json)){
                    $api_prop_obj = $api_prop_json[0];
                }else{
                    //reposnse is not an array there's some issue with api data like not correct api_prop_id
                    $api_prop_obj = NULL;
                }
               
                $data['api_prop_data'] = $api_prop_obj;

                if( $api_prop_obj->tenancy != '' ){

                    // get property tree, tenant data           
                    $api_tenant_json = $this->property_tree_model->get_tenant($agency_id,$api_prop_obj->tenancy);
                    $data['contact_arr'] = $api_tenant_json->contacts;

                }            
                
                $data['property_id'] = $property_id;            

                $this->load->view('templates/inner_header', $data);
                $this->load->view('api/prop_tree_connection_page',$data); // already connected page
                $this->load->view('templates/inner_footer', $data); 

            }else { // not connected yet

                $data['prop_tree_list'] = $this->property_tree_model->get_all_properties($agency_id);
                $data['propDet'] = $crm_prop_row;

                // check if marked as "do not connect to API"
                $pccta_sql = $this->db->query("
                SELECT `comment`
                FROM `property_cant_connect_to_api`
                WHERE `property_id` = {$property_id}
                ");
                $data['do_not_connect_to_api'] = ( $pccta_sql->num_rows() > 0 )?true:false;
                $data['pccta_row'] = $pccta_sql->row();

                $this->load->view('templates/inner_header', $data);
                $this->load->view('api/prop_tree_manual_connect_page',$data); // search and match Pme properties
                $this->load->view('templates/inner_footer', $data);
    
            }
              
        }                                 

    }

    public function connect_agency() {

        
        $data['title'] = "Propery Tree - Connect Agency";        
        $uri = '/property_tree/connect_agency';
        $data['uri'] = $uri;

        $api_id = 3; // Property Tree API
        
        // get already stored authentication keys
        $auth_keys_sql_str = "
        SELECT apt.`access_token`
        FROM `agency_api_tokens` AS apt
        WHERE apt.`api_id` = {$api_id}
        AND apt.`access_token` != ''        
        ";
        $auth_keys_sql = $this->db->query($auth_keys_sql_str);

        $current_auth_keys_arr = [];
        foreach( $auth_keys_sql->result() as $auth_keys_row ){
            $current_auth_keys_arr[] = $auth_keys_row->access_token;
        }
        $data['current_auth_keys_arr'] = $current_auth_keys_arr;

        // get agencies activated with SATS, rate limit is 1 request per 5 minutes >.<
        $pt_auth_key_arr = [];
        $json_ret = $this->property_tree_model->get_agencies_activated_with_sats();

        if( $json_ret->httpcode == 200 ){ // no error, get from API

            foreach( $json_ret->json_decoded_response as $pt_app_key ){

                // check if we already have this API key on our record
                $pt_akp_sql = $this->db->query("
                SELECT *
                FROM `propertytree_app_key_pairs`
                WHERE `authentication_key` = '{$pt_app_key->key}'
                ");

                if( $pt_akp_sql->num_rows() > 0 ){ // API key found

                    $pt_akp_row = $pt_akp_sql->row();

                    if( $pt_akp_row->active == 1 ){ // do not display API keys already marked as inactive on our database

                        // display on page
                        $pt_auth_key_arr[] = (object) [
                            'auth_key' => $pt_app_key->key,
                            'company_name' => $pt_app_key->company_name,
                            'activation_date' => $pt_app_key->activation_date
                        ];

                    }                 

                }else{ // API key not in our system yet. so insert

                    // display on page
                    $pt_auth_key_arr[] = (object) [
                        'auth_key' => $pt_app_key->key,
                        'company_name' => $pt_app_key->company_name,
                        'activation_date' => $pt_app_key->activation_date
                    ];

                    // store in our db
                    $insert_data = array(
                        'authentication_key' => $pt_app_key->key,
                        'company_name' => $pt_app_key->company_name,
                        'activation_date' => $pt_app_key->activation_date
                    );                    
                    $this->db->insert('propertytree_app_key_pairs', $insert_data);

                }                                

            }                        

        }else{ // rate limit exceeded error, get from crm instead

            $pt_akp_sql = $this->db->query("
            SELECT *
            FROM `propertytree_app_key_pairs`
            WHERE `active` = 1
            ");

            foreach( $pt_akp_sql->result() as $pt_akp_row ){
                $pt_auth_key_arr[] = (object) [
                    'auth_key' => $pt_akp_row->authentication_key,
                    'company_name' => $pt_akp_row->company_name,
                    'activation_date' => $pt_akp_row->activation_date
                ];
            }                      

        }

        /*
        // menu bubble count
        $pt_bubble_count = 0;

        $country_txt = ( $this->config->item('country') == 1 )?'AUSTRALIA':'NEW ZEALAND';    
        foreach( $pt_auth_key_arr as $pt_auth_key_obj ){ 
                    
            $json_obj = $this->property_tree_model->get_agency_details($pt_auth_key_obj->auth_key);                        
            $address_obj = $json_obj->address;

            if( !in_array($pt_auth_key_obj->auth_key,$current_auth_keys_arr) ){

                // AU and NZ are in 1 accounts, so it needs country filter to display correct data per country
                if( $address_obj->country == $country_txt ){
                    $pt_bubble_count++;
                }
                
            }

        }
        */
        
        $data['pt_auth_key_arr'] = $pt_auth_key_arr;
        
        $agency_sql_str = "
        SELECT 
            a.`agency_id`, 
            a.`agency_name`
        FROM `agency` AS a
        LEFT JOIN `agency_api_integration` AS aai ON ( a.`agency_id` = aai.`agency_id` AND aai.`active` = 1 AND aai.`connected_service` = {$api_id} )
        LEFT JOIN `agency_api_tokens` AS apt ON ( a.`agency_id` = apt.`agency_id` AND apt.`active` = 1 AND apt.`api_id` = {$api_id} )
        WHERE a.`status` = 'active'
        AND ( aai.`api_integration_id` = '' OR aai.`api_integration_id` IS NULL )
        AND ( apt.`access_token` = '' OR apt.`access_token` IS NULL )
        ORDER BY a.`agency_name` ASC
        ";
        $data['agency_sql'] = $this->db->query($agency_sql_str);

        /*
        // update page total
        $page_tot_params = array(
            'page' => $uri,
            'total' => $pt_bubble_count
        );
        $this->system_model->update_page_total($page_tot_params);
        */

        $this->load->view('templates/inner_header', $data);
        $this->load->view('api/connect_agency', $data);
        $this->load->view('templates/inner_footer', $data);                     

    }


    public function agency_preference() {

        $data['title'] = "Propery Tree - Agency Preference";
        $uri = '/property_tree/agency_preference';
        $data['uri'] = $uri;

        $api_id = 3; // Property Tree API     
        
        // agency filter
        $agency_filter = $this->db->escape_str($this->input->get_post('agency_filter'));            
        
        // main query
        $sql_main = "
        FROM `agency_api_tokens` AS apt
        LEFT JOIN `agency` AS a ON ( apt.`agency_id` = a.`agency_id` AND apt.`api_id` = {$api_id} )
        INNER JOIN `propertytree_agency_preference` AS pt_agp ON a.`agency_id` = pt_agp.`agency_id`
        WHERE a.`status` = 'active'
        AND apt.`access_token` != ''
        ";

        // filter
        if( $agency_filter > 0 ){

             // listing query
            $data['pt_connected_agency_sql'] = $this->db->query("
            SELECT 
                a.`agency_id`, 
                a.`agency_name`,

                pt_agp.`creditor`,
                pt_agp.`account`,
                pt_agp.`prop_comp_cat`
            {$sql_main}
            AND a.`agency_id` = {$agency_filter}
            ORDER BY a.`agency_name` ASC
            ");

        } 
       
        // listing query
        $data['distinct_agency'] = $this->db->query("
        SELECT 
            DISTINCT(a.`agency_id`),
            a.`agency_name`
        {$sql_main}
        ORDER BY a.`agency_name` ASC
        ");

        $this->load->view('templates/inner_header', $data);
        $this->load->view('api/agency_preference', $data);
        $this->load->view('templates/inner_footer', $data);                     

    }

    public function ajax_connect_agency(){

        $agency = $this->input->get_post('agency');
        $auth_key = $this->input->get_post('auth_key');
        $pt_email = $this->input->get_post('pt_email');

        $country_id = $this->config->item('country');
        $today = date('Y-m-d H:i:s');

        // get country data
        $country_params = array(
            'sel_query' => 'c.agent_number, c.outgoing_email, c.`iso`',
            'country_id' => $country_id
        );
        $country_sql = $this->system_model->get_countries($country_params);
        $country_row = $country_sql->row(); 

        $api_id = 3; // property tree API

        if( $agency > 0 && $auth_key != '' ){

            // check if API integration already exist on agency
            $sql = $this->db->query("
            SELECT COUNT(`api_integration_id`) AS api_integ_count
            FROM `agency_api_integration`
            WHERE `agency_id` = {$agency}
            AND `connected_service` = {$api_id}
            AND `active` = 1
            ");

            if( $sql->row()->api_integ_count == 0 ){ // no PT API integration, so insert one

                // insert agency/auth token
                $insert_data = array(
                    'agency_id' => $agency,
                    'connected_service' => $api_id
                );            
                $this->db->insert('agency_api_integration', $insert_data); 

            }

            // check if API token already exist on agency
            $sql = $this->db->query("
            SELECT COUNT(`agency_api_token_id`) AS aat_count
            FROM `agency_api_tokens`
            WHERE `agency_id` = {$agency}
            AND `api_id` = {$api_id}
            AND `active` = 1
            ");

            if( $sql->row()->aat_count > 0 ){

                // update agency/auth token
                $update_data = array(
                    'access_token' => $auth_key
                );                
                $this->db->where('agency_id', $agency);
                $this->db->where('api_id', $api_id);
                $this->db->where('active', 1);
                $this->db->update('agency_api_tokens', $update_data);

            }else{

                // insert agency/auth token
                $insert_data = array(
                    'api_id' => $api_id,
                    'agency_id' => $agency,
                    'access_token' => $auth_key,
                    'connection_date' => $today
                );            
                $this->db->insert('agency_api_tokens', $insert_data); 

            } 

            if( $pt_email != '' ){

                // email
                $subject = 'SATS/Property Tree Activation';                
                $from_email = $this->config->item('sats_info_email');
                $from_name = 'Smoke Alarm Testing Services';   

                if( ENVIRONMENT == 'production' ){ // live

                    //$to_email = $pt_email; // email from PropertyTree         
        
                }else{ // dev
        
                    //$to_email = 'vaultdweller123@gmail.com'; 
                    $to_email = 'vanessah@sats.com.au';
        
                }

                //$cc_email = 'bent@sats.com.au';                                   
                
                $return_as_string = true;
                $email_body = null; // clear                           

                $email_body .= $this->load->view('emails/template/email_header', null, $return_as_string);            
                
                $email_body .= "
                <p>Hi there,</p>
    
                <p>Thank you for activating " . config_item('company_name_short') . " in the Partner Gateway in Property Tree. 
                There is one more step to complete this process, simply login to the " . config_item('company_name_short') . " Agency Portal and from there a popup will 
                guide you on how to complete the process.</p>

                <p>If you require any further assistance, please contact our customer service team {$this->config->item('sats_info_email')} {$country_row->agent_number}.</p>
                ";

                $email_body .= $this->load->view('emails/template/email_footer', null, $return_as_string);

                $this->email->to($to_email);                     
                //$this->email->cc($this->config->item('sats_keys_email'));      
                //$this->email->cc($cc_email);                           

                $this->email->subject($subject);
                $this->email->message($email_body);

                // send email
                $this->email->send();

                // insert log
                $params = array(
                    'title' => 91, // PropertyTree API
                    'details' => 'Partially connected to Property Tree API',
                    'display_in_vad' => 1,            
                    'created_by_staff' => $this->session->staff_id,
                    'agency_id' => $agency
                );
                $this->system_model->insert_log($params);

            }                     

        }        

    }

    public function hide_app_key_pairs(){

        $auth_key = $this->input->get_post('auth_key');

        if( $auth_key != '' ){          
            
            $update_data = array(
                'active' => 0
            );            
            $this->db->where('authentication_key', $auth_key);
            $this->db->where('active', 1);
            $this->db->update('propertytree_app_key_pairs', $update_data);

        }        

    }

    public function display_agency_preference(){

        $agency = $this->input->get_post('agency');
        $table = null;


        $ret_arr = [];

        // get creditors
        $ret_obj = $this->property_tree_model->get_creditors($agency);
        if( $ret_obj->httpcode == 200 ){
            $creditors_json = $ret_obj->json_decoded_response;
        } 
        
        // get account
        $ret_obj = $this->property_tree_model->get_accounts($agency);
        if( $ret_obj->httpcode == 200 ){
            $accounts_json = $ret_obj->json_decoded_response;
        }

        // get property compliance categories
        $ret_obj = $this->property_tree_model->property_compliance_categories($agency);
        if( $ret_obj->httpcode == 200 ){
            $prop_comp_cat_json = $ret_obj->json_decoded_response;
        }

        // get agency preference
        $pt_ap_sql = $this->db->query("
        SELECT *
        FROM `propertytree_agency_preference`
        WHERE `agency_id` = {$agency}
        AND `active` = 1
        ");
        $pt_ap_row = $pt_ap_sql->row();

        $table .= "
        <table class='table'>
            <tr>
                <td>Creditor:</td>
                <td>
                    <select id='pt_creditor' class='form-control' required>
                        <option value=''>---</option>
                        ";
                        foreach( $creditors_json as $creditors ){

                            $table .= "<option value='{$creditors->creditor_id}' ".( ( $creditors->creditor_id == $pt_ap_row->creditor )?'selected':null ).">{$creditors->name} ({$creditors->creditor_id})</option>";
                            
                        }
                        $table .= "
                    </select>
                </td>
            </tr>
            <tr>
                <td>Account:</td>
                <td>
                    <select id='pt_account' class='form-control' required>
                        <option value=''>---</option>
                        ";
                        foreach( $accounts_json as $accounts ){

                            $table .= "<option value='{$accounts->id}' ".( ( $accounts->id == $pt_ap_row->account )?'selected':null ).">{$accounts->name} ({$accounts->id})</option>";
                            
                        }
                        $table .= "
                    </select>
                </td>
            </tr>
            <tr>
                <td>Property Compliance Category:</td>
                <td>
                    <select id='pt_prop_comp_cat' class='form-control' required>
                        <option value=''>---</option>
                        ";
                        foreach( $prop_comp_cat_json as $prop_comp_cat ){

                            $table .= "<option value='{$prop_comp_cat->category_id}' ".( ( $prop_comp_cat->category_id == $pt_ap_row->prop_comp_cat )?'selected':null ).">{$prop_comp_cat->category_name} ({$prop_comp_cat->category_id})</option>";
                            
                        }
                        $table .= "
                    </select>
                </td>
            </tr>
        </table>
        ";

        echo $table;

    }

    public function save_agency_preference(){

        $agency_id = $this->input->get_post('agency_id');
        $creditor = $this->input->get_post('creditor');
        $account = $this->input->get_post('account');
        $prop_comp_cat = $this->input->get_post('prop_comp_cat');

        if( $agency_id > 0 ){

            $sql = $this->db->query("
            SELECT COUNT(pt_ap_id) AS pt_ap_count
            FROM propertytree_agency_preference
            WHERE `agency_id` = {$agency_id}
            ");
            
            if( $sql->row()->pt_ap_count ){ // already exist, update

                $update_data = array(
                    'creditor' => $creditor,
                    'account' => $account,
                    'prop_comp_cat' => $prop_comp_cat
                );                
                $this->db->where('agency_id', $agency_id);
                $this->db->update('propertytree_agency_preference', $update_data);
                

            }else{ // new, insert

                // insert agency/auth token
                $insert_data = array(
                    'agency_id' => $agency_id,
                    'creditor' => $creditor,
                    'account' => $account,
                    'prop_comp_cat' => $prop_comp_cat
                );            
                $this->db->insert('propertytree_agency_preference', $insert_data);           

            }            

        }        

    }


    public function ajax_function_link_property() {

        $pt_prop_id = $this->input->get_post('pt_prop_id');
        $crmId = $this->input->get_post('crmId');

        $api_id = 3; // property tree API

        if( $pt_prop_id != '' ){

            // check if API prop Id already exist
            $apd_sql = $this->db->query("
            SELECT COUNT(`id`) AS apd_count
            FROM `api_property_data`
            WHERE `crm_prop_id` = {$crmId}
            AND `api` = {$api_id}
            ");
            $apd_row = $apd_sql->row();

            if( $apd_row->apd_count > 0 ){ // update

                $update_data = array(
                    'api_prop_id' => $pt_prop_id
                );
                
                $this->db->where('crm_prop_id', $crmId);
                $this->db->where('api', $api_id);
                $this->db->update('api_property_data', $update_data);

            }else{ // insert

                $data = array(
                    'crm_prop_id' => $crmId,
                    'api' => $api_id,
                    'api_prop_id' => $pt_prop_id
                );                    
                $this->db->insert('api_property_data', $data);

            }

            // insert property log
            $params = array(
                'title' => 91, // PropertyTree API
                'details' => 'Property <b>Linked</b> to <b/>PropertyTree</b>',
                'display_in_vpd' => 1,            
                'created_by_staff' => $this->session->staff_id,
                'property_id' => $crmId
            );
            $this->system_model->insert_log($params);

            if( $crmId > 0 ){

                // clear "cant connect to API" marker
                $this->db->where('property_id', $crmId);
                $this->db->delete('property_cant_connect_to_api');
    
            }

            echo json_encode(array("updateStat" => true));

        }        

    }

    public function ajax_function_unlink_property() {
        
        $crmId = $this->input->get_post('crmId');

        $api_id = 3; // property tree API

        if( $crmId > 0 ){

            $this->db->where('crm_prop_id', $crmId);
            $this->db->where('api', $api_id);
            $this->db->delete('api_property_data');            
    
            // insert property log
            $params = array(
                'title' => 91, // PropertyTree API
                'details' => 'Property <b>Unlinked</b> to <b/>PropertyTree</b>',
                'display_in_vpd' => 1,            
                'created_by_staff' => $this->session->staff_id,
                'property_id' => $crmId
            );
            $this->system_model->insert_log($params);
            
            echo json_encode(array("updateStat" => true));

        } 
       
    }

    public function ajax_bulk_move_nlm_property(){

        $this->load->model('properties_model');
        
        $jdata['status'] = false;
        $property_id_arr = $this->input->post('property_id_arr');
        $pmeArr = $this->input->post('pmeArr');
        $agency_id = $this->input->post('sel_agency_id');
        $old_agency_id = $this->input->post('old_agency_id');
        
        if( !empty($property_id_arr) && !empty($pmeArr) ){

            for ($i=0; $i < count($property_id_arr); $i++) { 

                ## payable check
                $this->properties_model->payableCheck($property_id_arr[$i]);

                ## Update propert moved from old to new----- 
                $updateData = array(
                    'agency_id' => $agency_id,
                    'is_nlm' => NULL,
                    'nlm_display' => NULL,
                    'nlm_timestamp' => NULL,
                    'nlm_by_sats_staff' => NULL,
                    'nlm_by_agency' => NULL,
                    'agency_deleted' => 0
                );
                $this->db->where('property_id', $property_id_arr[$i]);
                $this->db->update('property', $updateData);
                $this->db->reset_query();
                ## Update propert moved from old to new end-----

                ##check api_property_data
                $check = $this->properties_model->apiCheck($property_id_arr[$i]);

                ##Update api_property_data table-----
                if(empty($check)){

                    $updateData_Api = array(
                        'api_prop_id' => $pmeArr[$i],
                        'crm_prop_id' => $property_id_arr[$i],
                        'api'         => 3
                    );
                    $this->db->insert('api_property_data', $updateData_Api);
                    $this->db->reset_query();

                }else{

                    $updateData_Api = array(
                        'api_prop_id' => $pmeArr[$i]
                    );
                    $this->db->where('crm_prop_id', $property_id_arr[$i]);
                    $this->db->where('api', 3);
                    $this->db->update('api_property_data', $updateData_Api);
                    $this->db->reset_query();

                }
                ##Update api_property_data table end-----

                ##Insert log-----
                $old_agency_name = $this->db->select('agency_name')->from('agency')->where('agency_id',$old_agency_id)->get()->row()->agency_name;
                $new_agency_name = $this->db->select('agency_name')->from('agency')->where('agency_id',$agency_id)->get()->row()->agency_name;
                $details = "Property moved from <strong>{$old_agency_name}</strong> to <strong>{$new_agency_name}</strong>, status changed from NLM to Active.";
                $params = array(
                    'title' => 2, ## New Property Added
                    'details' => $details,
                    'display_in_vpd' => 1,
                    'agency_id' => $agency_id,
                    'created_by_staff' => $this->session->staff_id,
                    'property_id' => $property_id_arr[$i]
                );
                $this->system_model->insert_log($params);
                ##Insert log end-----

            }

            $jdata['status'] = true;

        }

        echo json_encode($jdata);


    }

    public function get_property_tree_tenancy(){

        $tenancy_id = $this->input->get_post('tenancy_id');
        $agency_id = $this->input->get_post('agency_id');
        $ttarr = [];

        $tt =  $this->property_tree_model->get_tenant($agency_id, $tenancy_id);
        $ttmo = $tt->contacts;

       $tt = array(
        'name' => $tt->name,
        'fname' => $ttmo[0]->first_name,
        'lname' => $ttmo[0]->last_name,
        'email' => $ttmo[0]->email_address,
        'phone' => $ttmo[0]->mobile_phone_number
       );

       echo json_encode($tt);

    }


    public function agency_connections() {

        $data['title'] = "Agency PropertyTree Connections";
        $uri = '/property_me/agency_connections';
        $data['uri'] = $uri;

        $agency_filter = $this->input->get_post('agency_filter');
        $date_from_filter = $this->input->get_post('date_from_filter');
        $date_to_filter = $this->input->get_post('date_to_filter');
        $query_filter = '';

        $pt_api = 3;

        // pagination
        $per_page = $this->config->item('pagi_per_page');
        $offset = ( $this->input->get_post('offset') != '' )?$this->input->get_post('offset'):0;        

        if ( $date_from_filter != '' && $date_to_filter != '' ) {

            $from = $this->system_model->formatDate($date_from_filter);
            $to = $this->system_model->formatDate($date_to_filter);

            $query_filter .= "AND CAST(agen_tok.`connection_date` AS DATE) BETWEEN '{$from}' AND '{$to}'";
        }

        if( $agency_filter != '' ){
            $query_filter .= "AND a.`agency_id` = {$agency_filter}";
        }

        // get only Pme integrated agency
        $agency_sql_str = "
            SELECT 
                a.`agency_id`,
                a.`agency_name`,
                
                agen_tok.`agency_api_token_id`,
                agen_tok.`connection_date`,
                agen_tok.`access_token`
            FROM `agency` AS a 
            LEFT JOIN `agency_api_integration` AS agen_api ON (a.`agency_id` = agen_api.`agency_id` AND agen_api.`connected_service` = {$pt_api} )
            LEFT JOIN `agency_api_tokens` AS agen_tok ON ( a.`agency_id` = agen_tok.`agency_id` AND agen_tok.`api_id` = {$pt_api} )
            WHERE a.`status` = 'active'    
            AND agen_api.`connected_service` = {$pt_api}   
            AND agen_api.`active` = 1       
            {$query_filter}
            LIMIT {$offset}, {$per_page}
        ";
        $data['agency_sql'] = $this->db->query($agency_sql_str);
        $data['last_query'] = $this->db->last_query();

        $agency_sql_str_counter = "
            SELECT 
                a.`agency_id`,
                a.`agency_name`,
                
                agen_tok.`agency_api_token_id`,
                agen_tok.`connection_date`,
                agen_tok.`access_token`
            FROM `agency` AS a 
            LEFT JOIN `agency_api_integration` AS agen_api ON (a.`agency_id` = agen_api.`agency_id` AND agen_api.`connected_service` = 1 )
            LEFT JOIN `agency_api_tokens` AS agen_tok ON ( a.`agency_id` = agen_tok.`agency_id` AND agen_tok.`api_id` = 1 )
            WHERE a.`status` = 'active'    
            AND agen_api.`connected_service` = 1   
            AND agen_api.`active` = 1       
            {$query_filter}
        ";
        $agencyList = $this->db->query($agency_sql_str_counter);

        $ableToCon = 0;
        $needToCon = 0;
        $fullCon = 0;
        foreach($agencyList->result() as $agency_row){

            if ($agency_row->access_token == "" || is_null($agency_row->access_token)) {
                $ableToCon++;
            }else {
                if($this->system_model->isDateNotEmpty($agency_row->connection_date) == false){ 
                    $fullCon++;
                }else { 
                    $needToCon++;
                }
            }

        }

        $data['ableToCon'] = $ableToCon;
        $data['needToCon'] = $needToCon;
        $data['fullCon'] = $fullCon;

        // get total row
        $agency_sql_str = "
            SELECT COUNT(a.`agency_id`) AS a_count
            FROM `agency` AS a 
            LEFT JOIN `agency_api_integration` AS agen_api ON (a.`agency_id` = agen_api.`agency_id` AND agen_api.`connected_service` = 1 )
            LEFT JOIN `agency_api_tokens` AS agen_tok ON ( a.`agency_id` = agen_tok.`agency_id` AND agen_tok.`api_id` = 1 )
            WHERE a.`status` = 'active'                   
            AND agen_api.`connected_service` = 1
            AND agen_api.`active` = 1       
        ";
        $total_rows = $this->db->query($agency_sql_str)->row()->a_count;


        // get distinct agency
        $agency_sql_str = "
            SELECT DISTINCT(a.`agency_id`), a.`agency_name` 
            FROM `agency` AS a 
            LEFT JOIN `agency_api_integration` AS agen_api ON (a.`agency_id` = agen_api.`agency_id` AND agen_api.`connected_service` = 1 )
            LEFT JOIN `agency_api_tokens` AS agen_tok ON ( a.`agency_id` = agen_tok.`agency_id` AND agen_tok.`api_id` = 1 )
            WHERE a.`status` = 'active'     
            AND agen_api.`connected_service` = 1
            ORDER BY a.`agency_name` ASC                 
        ";
        $data['distinct_agency_sql'] = $this->db->query($agency_sql_str);

        $pagi_links_params_arr = array(
            'agency_filter' => $agency_filter,
            'date_from_filter' => $date_from_filter,
            'date_to_filter' => $date_to_filter,
        );
        $pagi_link_params = $uri.'/?'.http_build_query($pagi_links_params_arr);


        // pagination
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'offset';
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $per_page;
        $config['base_url'] = $pagi_link_params;

        $this->pagination->initialize($config);

        $data['pagination'] = $this->pagination->create_links();

        $pc_params = array(
            'total_rows' => $total_rows,
            'offset' => $offset,
            'per_page' => $per_page
        );
        $data['pagi_count'] = $this->jcclass->pagination_count($pc_params);
        

        //load views
        $this->load->view('templates/inner_header', $data);
        $this->load->view('api/pt_agency_connections', $data);
        $this->load->view('templates/inner_footer', $data);

    }


    public function get_tenants_and_landlords(){

        $agency_id = $this->input->get_post('agency_id');
        $tenancy_id = $this->input->get_post('pt_tenancy_id');
        $ownership_id = $this->input->get_post('pt_ownership_id');

        if( $tenancy_id != '' ){

            // get property tree, tenant data           
            $api_tenant_json_decoded = $this->property_tree_model->get_tenant($agency_id,$tenancy_id);

        }  
        
        if( $ownership_id != '' ){

            // get property tree, landlords       
            $api_owner_json_decoded = $this->property_tree_model->get_landlord($agency_id,$ownership_id);

        }

        $ret_arr = array(
            'tenant' => $api_tenant_json_decoded,
            'landlord' => $api_owner_json_decoded
        );

        echo json_encode($ret_arr);

    }
    
}