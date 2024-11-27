<style>
    #nlm_from::placeholder {
    text-transform: uppercase;
    }
    #map-image {
        max-width: 100%;
        max-height: 100%;
        display: block;
        margin: auto;
    }
</style>
<div id="dialog-confirm" title="Confirm" style="display:none;">
  <p>Do you want to delete tenant data?</p>
</div>
<div class="box-typical-body">
    <!-- Address Details -->
        <!-- <div class="row">
            <div class="col-md-12 columns text-left">
                <h3>Address Details</h3>          
            </div>
        </div> -->

        <?php          
        if ($row['api'] == 1) {
            $api_name = 'PropertyMe ID';
        } elseif($row['api'] == 3) {
            $api_name = 'PropertyTree ID';
        } elseif($row['api'] == 4) {
            $api_name = 'Palace ID';
        } elseif($row['api'] == 6) {
            $api_name = 'Ourtradide ID';
        } else {
            $api_name = 'API Code';
        }

        // get connected property
        $cak_sql = $this->db->query("
            SELECT *
            FROM `property` AS p
            INNER JOIN `console_properties` AS cp ON ( p.`property_id` = cp.`crm_prop_id` AND cp.`active` = 1 )
            WHERE cp.`crm_prop_id` = {$property_id}																																
            ");
        $cak_row = $cak_sql->row();

        $enableApi = true;
        $controlerApi = 'console';
        $connTextApi = 'Console';
        $checkIdApi = $cak_row->console_prop_id;
        $console_prop_id = $cak_row->console_prop_id;
        ?>

        <div class="row">
            <div class="col-md-6 columns text-left">
                <section class="card card-blue-fill">
                    <header class="card-header">Property Details</header>
                    <div class="card-block">
                        <div class="row form-group">                            
                            <div class="col-md-4 column tt_boxes">
                                <label>Building Name</label>
                                <a class="" data-auto-focus="false" data-fancybox data-src="#fancybox_building_name" href="javascript:;"><?=($row['building_name'] != '') ? $row['building_name']:'No Data';?></a>
                            </div>
                            <div class="col-md-4 column tt_boxes">
                                <?php 
                                    if( $console_prop_id > 0 ){ // console

                                        $api_prop_id = $console_prop_id; 
                                        $apiname = "Console ID";
            
                                     }else{ // other API
            
                                        $api_prop_id = $row['api_prop_id'];
                                        $apiname = $api_name;
                                     }                                      
                                ?>
                                <label><?=$apiname?>
                                </label>
                                <a class="" data-auto-focus="false" data-fancybox data-src="#fancybox_api_id" href="javascript:;"><?=($api_prop_id != '') ? strlen($api_prop_id) > 10 ? substr($api_prop_id, 0, 10) . '...' : $api_prop_id:'No Data';?></a>                                
                            </div>
                            <div class="col-md-4 column tt_boxes">
                                <label for="">Region Path</label>
                                <a target="_blank" href="/admin/search_regions/?postcode=<?php echo $row['subregion_name']; ?>"><?php echo $row['region_name']; ?> | <?php echo $row['subregion_name']; ?></a>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-4 column tt_boxes">
                                <label>Alarm System Code
                                </label>
                                <a class="" data-auto-focus="false" data-fancybox data-src="#fancybox_alarm_code" href="javascript:;"><?=($row['alarm_code'] != '') ? $row['alarm_code']:'No Data';?></a>
                            </div> 
                            <div class="col-md-4 column tt_boxes">
                                <label>Key Number</label>
                                <?php
                                    // PMe
                                    $connected_to_pme = false;
                                    foreach( $api_token_sql->result_array() as $api_row){
                                        if ( $api_row['api_id'] == 1 ){ 
                                            $connected_to_pme = true;
                                        }
                                        if ( $api_row['api_id'] == 4 ){
                                            $connected_to_palace = true;
                                        }
                                        if ( $api_row['api_id'] == 3 ){ 
                                            $connected_to_propertytree = true;
                                        }
                                    }
                                    if( $row['api_prop_id'] != '' && $connected_to_pme == true && $agency_id > 0 ){

                                        $pme_key_number = null;

                                        // get pme property pm
                                        /*$pme_get_pm_params = array(
                                            'prop_id' =>  $row['propertyme_prop_id'],
                                            'agency_id' => $agency_id
                                        );*/

                                        $pme_get_pm_params = array(
                                            'prop_id' =>  $row['api_prop_id'],
                                            'agency_id' => $agency_id
                                        );

                                        $pme_get_pm_params_json = $this->api_model->get_property_pme($pme_get_pm_params);
                                        $pme_get_pm_params_dec = json_decode($pme_get_pm_params_json);

                                        $api_key_number = $pme_get_pm_params_dec->KeyNumber;
                                        if( $pme_get_pm_params_dec->KeyNumber != '' ){
                                            $pme_key_number =  "{$pme_get_pm_params_dec->KeyNumber} (PMe)";
                                        }

                                    }
                                ?>
                                <a class="" data-auto-focus="false" data-fancybox data-src="#fancybox_key_num" href="javascript:;"><?=($row['key_number'] != '') ? $row['key_number']:'No Data';?></a>
                                <?php if($pme_key_number == NULL){}else { echo $pme_key_number;} ?>
                            </div>
                            <div class="col-md-4 column tt_boxes">
                                
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-4 column tt_boxes">
                                <label>Property Notes
                                </label>
                                <a class="" data-auto-focus="false" data-fancybox data-src="#fancybox_prop_notes" href="javascript:;"><?=($row['comments'] != '') ? $row['comments']: 'No Data'; ?></a>
                            </div>
                            <div class="col-md-4 column tt_boxes">
                                <label>Property Upgraded (QLD)</label>
                                <?php
                                    $prop_upgraded_to_ic_sa_status = '';

                                    if ($row['prop_upgraded_to_ic_sa'] == 1) {
                                        $prop_upgraded_to_ic_sa_status = 'Yes';
                                    } else {
                                        $prop_upgraded_to_ic_sa_status = 'No';
                                    }
                                ?>
                                <a class="" data-auto-focus="false" data-fancybox data-src="#fancybox_propery_upgraded" href="javascript:;"><?=($prop_upgraded_to_ic_sa_status != '') ? $prop_upgraded_to_ic_sa_status:'No Data';?></a>
                            </div>

                            <div class="col-md-4 column tt_boxes">
                                <label>Source of Property</label>
                                    <?php
                                    $pfoc_row = $pfoc_sql->row();?>
                                <a class="" data-auto-focus="false" data-fancybox data-src="#fancybox_source_property" href="javascript:;"><?=($pfoc_row->company_name != '') ? $pfoc_row->company_name:'No Data';?></a>
                            </div>                            
                        </div>
                        <div class="row form-group">
                            <div class="col-md-4 column tt_boxes">
                                <label>Short Term Rental</label>
                                <a class="" data-auto-focus="false" data-fancybox data-src="#fancybox_short_term_rental" href="javascript:;"><?=($row['holiday_rental'] == 1) ? 'Yes':'No';?></a>
                            </div>
                            <?php if($row['state'] == 'QLD'){ ?>
                            <div class="col-md-4 column tt_boxes">
                                <label>Sales Property</label>
                                <a class="" data-auto-focus="false" data-fancybox data-src="#fancybox_sales_property" href="javascript:;"><?=($row['is_sales'] == 1) ? 'Yes':'No';?></a>
                            </div>
                            <?php } ?>
                            <div class="col-md-4 column tt_boxes">
                                <label>Lockbox Code</label>
                                <a class="" data-auto-focus="false" data-fancybox data-src="#fancybox_lockbox_code" href="javascript:;"><?=($row['code'] != '') ? $row['code']:'No Data';?></a>
                            </div>
                        </div>
                        <div class="row form-group" style="<?=($row['state'] != 'NSW') ? 'margin-bottom: 0;':'' ?>">
                            <div class="col-md-4 column tt_boxes">
                                <label>Property Manager</label>
                                <?php
                                $property_manager = '';
                                //default value of pm is 1
                                if (isset($row['pm_id_new'])) {
                                    $new_pm = 1;
                                    if ($new_pm == 1) {
                                        $agency_user_account_id_sql = $this->db->query("
                                            SELECT *
                                            FROM `agency_user_accounts`
                                            WHERE `agency_user_account_id` = {$row['pm_id_new']} ORDER BY fname           
                                        ");
                                    } else {
                                        $agency_user_account_id_sql = $this->db->query("
                                            SELECT *
                                            FROM `property_managers`
                                            WHERE `property_managers_id` = {$row['pm_id_new']} ORDER BY name     
                                        ");
                                    }
                                    $pm_sel_query = $agency_user_account_id_sql->row();
                                    $property_manager = $pm_sel_query->fname.''.$pm_sel_query->lname;
                                }

                                if( $row['api_prop_id'] != '' &&  $connected_to_pme == true && $agency_id > 0 ){

                                    // get pme property pm
                                    $pme_get_pm_params = array(
                                        'prop_id' =>  $row['api_prop_id'],
                                        'agency_id' => $agency_id
                                    );
        
                                    $pme_get_pm_params_json = $this->pme_model->get_pme_prop_pm($pme_get_pm_params);
                                    $pme_get_pm_params_dec = json_decode($pme_get_pm_params_json);
        
                                    $pme_prop_pm =  "{$pme_get_pm_params_dec->FirstName} {$pme_get_pm_params_dec->LastName} (PMe)";
        
                                }
        
                                //PALACE PM
                                if( $row['api_prop_id'] != '' &&  $connected_to_palace == true && $agency_id > 0 ){
        
                                    $palace_pm_params = array(
                                        'prop_id' =>  $row['api_prop_id'],
                                        'agency_id' => $agency_id
                                    );
                                    $palace_pm_q = $this->api_model->get_property_palace($palace_pm_params);
                                    $palace_get_pm_params_dec = json_decode($palace_pm_q);
        
                                    $pme_prop_pm =  "{$palace_get_pm_params_dec->PropertyAgentFullName} (Palace)";
        
                                }
        
                            
                                // //PropertyTree PM
                                if( $row['api_prop_id'] != '' &&  $connected_to_propertytree == true && $agency_id > 0 ){
        
                                    $propTree_pm_params = array(
                                        'property_id' => $property_id
                                    );
                    
                                    $propTree_q = $this->api_model->get_property_tree_property($propTree_pm_params);
                                    $propTree_q_json_decoded_response = $propTree_q['json_decoded_response'];
                                    $prop_tree_http_status_code = $propTree_q['http_status_code'];
        
                                    if( $prop_tree_http_status_code == 200 ){ // OK
                                        $api_prop_obj = $propTree_q_json_decoded_response[0];
                                        //echo "<pre>";
                                        //var_dump($api_prop_obj->agents);
                                        foreach( $api_prop_obj->agents as $index=>$value ){
                                            if($index==0){
                                                $agent_params = array(
                                                    'agent_id' => $value,
                                                    'property_id' => $property_id
                                                );
                                                $agenty_req_obj = $this->property_tree_model->get_property_tree_agent_by_id($agent_params);
        
                                                if( $agenty_req_obj['http_status_code'] == 200 ){
                                                    $agenty_req_obj_res = $agenty_req_obj['json_decoded_response'];
                                                    $pme_prop_pm =  "{$agenty_req_obj_res->first_name} {$agenty_req_obj_res->last_name} (PropertyTree)";
                                                }
                                                
                                            }
                                        }
                                        
                                    }
                                    
                                }
                                ?>
                                <a class="" data-auto-focus="false" data-fancybox data-src="#fancybox_property_manager" href="javascript:;"><?=($property_manager != '') ? $pm_sel_query->fname.' '.$pm_sel_query->lname:'No Data';?></a>
                                <?php echo ($pme_prop_pm != '') ? $pme_prop_pm : ''; ?>
                            </div>
                            <div class="col-md-4 column tt_boxes">
                                <label>Auto Renew Subscription
                                </label>
                                <a class="" data-auto-focus="false" data-fancybox data-src="#fancybox_manual_renewal" href="javascript:;"><?=($row['manual_renewal'] == 1) ? 'Yes':'No';?></a>
                            </div>
                            
                            <?php if(!empty($row['third_party_url'])){ ?>
                            <div class="col-md-4 column tt_boxes">
                                <label>Link</label>
                                <a href="<?php echo $row['third_party_url']; ?>" class="" target="_blank">
                                    Open in Microsoft Dynamics
                                </a>
                            </div>
                            <?php } ?>                               
                            
                        </div>
                        <?php
                        // only show on NSW
                        if( $row['state'] == 'NSW' ){ ?>
                            <div class="row form-group" style="margin-bottom: 0;">
                                
                                <div class="col-md-4 column tt_boxes">
                                    <label>Attached Garage Requires Alarm
                                    </label>
                                    <a class="" data-auto-focus="false" data-fancybox data-src="#fancybox_attached_garage_requires_alarm" href="javascript:;"><?=($row['service_garage'] == 1) ? 'Yes':'No';?></a>
                                </div>
                                
                            </div>
                        <?php } ?>
                    </div>
                </section>
                <section class="card card-blue-fill">
                    <header class="card-header">Preferences</header>
                    <div class="card-block">
                        <div class="row form-group">
                            <div class="col-md-6 column tt_boxes">
                                <div class="checkbox" style="margin:0; display: inline-block; vertical-align: middle;">
                                    <input type="checkbox" name="chk_[]" id="check-1" <?php echo (($row['bne_to_call']==1)?'checked="checked"':''); ?> value='1' onchange="update_tt_boxes('bne_to_call','check-1','Office to Call Tenant Only')">
                                    <label for="check-1">&nbsp;</label>
                                </div>
                                <label id="label-check-1" style="display: inline-block; vertical-align: middle; <?php echo (($row['bne_to_call']==1)?'':'font-weight: 500;'); ?>">Office to Call Tenant Only</label>
                            </div>
                            <div class="col-md-6 column tt_boxes">
                                <div class="checkbox" style="margin:0; display: inline-block; vertical-align: middle;">
                                    <input type="checkbox" name="chk_[]" id="check-2" <?php echo (($row['send_to_email_not_api']==1)?'checked="checked"':''); ?> value='1' onchange="update_tt_boxes('send_to_email_not_api','check-2','Email Invoice instead of API Upload')">
                                    <label for="check-2">&nbsp;</label>
                                </div>
                                <label id="label-check-2" style="display: inline-block; vertical-align: middle; <?php echo (($row['send_to_email_not_api']==1)?'':'font-weight: 500;'); ?>">Email Invoice instead of API Upload</label>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6 column tt_boxes">
                                <div class="checkbox" style="margin:0; display: inline-block; vertical-align: middle;">
                                    <input type="checkbox" name="chk_[]" id="check-3" <?php echo (($row['no_en']==1)?'checked="checked"':''); ?> value='1' onchange="update_tt_boxes('no_en','check-3','No Entry Notice Allowed')">
                                    <label for="check-3">&nbsp;</label>
                                </div>
                                <label id="label-check-3" style="display: inline-block; vertical-align: middle; <?php echo (($row['no_en']==1)?'':'font-weight: 500;'); ?>">No Entry Notice Allowed</label>
                            </div>
                            <div class="col-md-6 column tt_boxes">
                                <div class="checkbox" style="margin:0; display: inline-block; vertical-align: middle;">
                                    <input type="checkbox" name="chk_[]" id="check-4" <?php echo (($row['nlm_display']==1)?'checked="checked"':''); ?> value='1' onchange="update_tt_boxes('nlm_display','check-4','Payment is Verified')">
                                    <label for="check-4">&nbsp;</label>
                                </div>
                                <label id="label-check-4" style="display: inline-block; vertical-align: middle; <?php echo (($row['nlm_display']==1)?'':'font-weight: 500;'); ?>">Payment is Verified
                                </label>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6 column tt_boxes">
                                <div class="checkbox" style="margin:0; display: inline-block; vertical-align: middle;">
                                    <input type="checkbox" name="chk_[]" id="check-5" <?php echo (($row['no_keys']==1)?'checked="checked"':''); ?> value='1' onchange="update_tt_boxes('no_keys','check-5','No Keys at Agency')">
                                    <label for="check-5">&nbsp;</label>
                                </div>
                                <label id="label-check-5" style="display: inline-block; vertical-align: middle; <?php echo (($row['no_keys']==1)?'':'font-weight: 500;'); ?>">No Keys at Agency
                                </label>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6 column tt_boxes">
                                <div class="checkbox" style="margin:0; display: inline-block; vertical-align: middle;">
                                    <input type="checkbox" name="chk_[]" id="check-6" <?php echo (($row['no_dk']==1)?'checked="checked"':''); ?> value='1' onchange="update_tt_boxes('no_dk','check-6','No Door Knock Allowed')">
                                    <label for="check-6">&nbsp;</label>
                                </div>
                                <label id="label-check-6" style="display: inline-block; vertical-align: middle; <?php echo (($row['no_dk']==1)?'':'font-weight: 500;'); ?>">No Door Knock Allowed</label>
                            </div>
                        </div>
                        <div class="row form-group" style="<?=($can_delete_prop == true && $row['deleted'] == 0) ? '':'margin-bottom: 0;'?>">
                            <div class="col-md-6 column tt_boxes">
                                <div class="checkbox" style="margin:0; display: inline-block; vertical-align: middle;">
                                    <input type="checkbox" name="chk_[]" id="check-7" <?php echo (($row['requires_ppe']==1)?'checked="checked"':''); ?> value='1' onchange="update_tt_boxes('requires_ppe','check-7','PPE Required for Entry')">
                                    <label for="check-7">&nbsp;</label>
                                </div>
                                <label id="label-check-7" style="display: inline-block; vertical-align: middle; <?php echo (($row['requires_ppe']==1)?'':'font-weight: 500;'); ?>">PPE Required for Entry</label>
                            </div>
                        </div>
                        <?php if( $can_delete_prop == true && $row['deleted'] == 0 ){ ?>
                            <div class="row form-group" style="margin-bottom: 0;">
                                <div class="col-md-12 column tt_boxes">
                                        <button style="float: right;" type='button' class='submitbtnImg btn btn-danger' data-auto-focus="false" data-fancybox data-src="#fancybox_delete" href="javascript:;">Delete Property</button>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </section>
            </div>
            <div class="col-md-6 columns text-left">
                <div class="row">
                    <div class="col-md-6 columns text-left">
                        <section class="card card-blue-fill">
                            <header class="card-header">Property Status</header>
                            <div class="card-block">
                                <div class="row form-group">
                                    <div class="col-md-6 column tt_boxes">
                                        <label>Agency</label>
                                        <a href="/agency/view_agency_details/<?php echo $row['agency_id']; ?>" target="_blank"><?=$row['agency_name'];?></a>
                                        <a class="" target="_blank" id="btn_change_agency" style="display: inline-block; cursor: pointer;" data-toggle="tooltip" title="Change Agency" href='/properties/change_agency_static?id=<?php echo $row['property_id']; ?>'>
                                        <span class="font-icon font-icon-pencil"></span></a>
                                        <?php 
                                        if( $row['allow_upfront_billing'] == 1 ){
                                            echo '<span style="color:#0082c6" data-toggle="tooltip" title="Subscription Billing Customer" class="fa fa-dollar"><span>';
                                        }
                                        ?>
                                    </div>
                                    <div class="col-md-6 column tt_boxes">
                                        <label>Agency Key Pick up Address</label>
                                        <?php
                                        $key_add_num = 1;
                                        ?>
                                            <?php foreach($agency_add_sql_str->result() as $agency_add_row){ 
                                                $agen_add_comb = "{$agency_add_row->agen_add_street_num} {$agency_add_row->agen_add_street_name}, {$agency_add_row->agen_add_suburb}"; 
                                                $check_address_sql = $this->db->query("SELECT `id` FROM `property_keys` WHERE `property_id`='{$row['property_id']}' AND `agency_addresses_id`='{$agency_add_row->agen_add_id}'");
                                                if ($check_address_sql->num_rows() > 0) {
                                                    $agency_keys = "Key #$key_add_num $agen_add_comb";
                                                } 
                                                $key_add_num ++;
                                                ?>  
                                            <?php } ?>
                                        <a class="" data-auto-focus="false" data-fancybox data-src="#fancybox_agency_keys" href="javascript:;"><?=(!empty($agency_keys)) ? $agency_keys:"No Data";?></a>
                                    </div>
                                </div>
                                <div class="row form-group" style="<?=($row['deleted'] || $row['agency_deleted'] || $row['is_nlm']==1) ? '':'margin-bottom: 0;'?>">
                                    <div class="col-md-6 column tt_boxes">
                                        <?php if( $row['is_nlm']!=1 ){ ?>
                                            <label>Property Status</label>
                                            <a class="" style="color: green;" data-auto-focus="false" data-fancybox data-src="#fancybox_nlm" href="javascript:;">
                                            Active
                                            </a>
                                        <?php } else {
                                            echo '
                                            <label>Property Status</label>
                                            <a class="" style="color: red;" href="javascript:;">
                                            No Longer Managed
                                            </a>
                                            ';
                                        } ?>
                                    </div>
                                    <div class="col-md-6 column tt_boxes">
                                        <label>Property Price Variation</label>
                                        <?php
                                        $pv_row = $pv_sql->row();
                                        ?>
                                            <?php foreach($sql_agency_var->result() as $apv_row) { 
                                                if ($pv_row->agency_price_variation == $apv_row->id) {
                                                    $number = number_format($apv_row->amount, 2);
                                                    $type = ($apv_row->type == 1) ? 'Discount' : 'Surcharge';
                                                    $variation_name = '$'.$number.' ('.$type.' - '.$apv_row->apvr_reason.')';
                                                } 
                                            }
                                            ?>
                                        <a class="" data-auto-focus="false" data-fancybox data-src="#fancybox_agency_price_variation" href="javascript:;"><?=(!empty($variation_name)) ? $variation_name:"No Data";?></a>
                                    </div>
                                </div>
                                <?php if($row['deleted'] || $row['agency_deleted'] || $row['is_nlm']==1){ ?>
                                    <div class="row form-group" style="margin-bottom: 0;">
                                        <div class="col-md-6 column tt_boxes">
                                            <button type='button' id='restoreProb_btn' class='submitbtnImg btn btn-danger'>Restore this Property</button>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </section>
                    </div>
                    <div class="col-md-6 columns text-left">
                        <section class="card card-blue-fill">
                            <header class="card-header">Dates</header>
                            <div class="card-block">
                                <div class="row form-group">
                                    <div class="col-md-4 column tt_boxes">
                                        <label>Retest Date</label>
                                        <span><?php echo $this->properties_model->get_retest_date($property_id)?></span>
                                    </div>
                                    <div class="col-md-4 column tt_boxes">
                                        <label>Subscription Start Date</label>
                                        <?php
                                            // get property subscription
                                            
                                            $prop_subs_row = $prop_subs_sql->row();

                                            $today = date('Y-m-d');
                                            $this_year = date("Y");

                                            $sub_date_month = date("m",strtotime($prop_subs_row->subscription_date));
                                            $sub_date_day = date("d",strtotime($prop_subs_row->subscription_date));
                                            $sub_date_year = date("Y",strtotime($prop_subs_row->subscription_date));

                                            // this year using subscription month and day
                                            $sub_date_this_year = date('Y-m-d', strtotime("{$this_year}-{$sub_date_month}-{$sub_date_day}"));	

                                            // if today's date is within the subscription date this year
                                            if( $today >= date('Y-m-d', strtotime($sub_date_this_year) )  ){ 

                                                $sub_valid_from = date('Y-m-d', strtotime($sub_date_this_year));
                                            } else if($today < date('Y-m-d', strtotime($prop_subs_row->subscription_date)) ) {

                                                $sub_valid_from = date("{$sub_date_year}-{$sub_date_month}-{$sub_date_day}");
                                            } else { // else get previous year, but using subscript date month and day

                                                $sub_valid_from = date("Y-{$sub_date_month}-{$sub_date_day}", strtotime("-1 year"));
                                            }

                                            // subscription valid to = add 1 year then - 1 day
                                            $sub_valid_to_temp = date('Y-m-d', strtotime("{$sub_valid_from} +1 year"));
                                            $sub_valid_to = date('Y-m-d', strtotime("{$sub_valid_to_temp} -1 day"));

                                            $ps_sql3_row = $ps_sql3->row();
                                            ?>
                                        <span>
                                            <a class="" data-auto-focus="false" data-fancybox data-src="#fancybox_subscription_start_date_source" href="javascript:;"><?=($prop_subs_row->subscription_date != '') ? date('d/m/Y',strtotime($prop_subs_row->subscription_date)):'No Data';?></a>
                                        </span> 
                                    </div>
                                    <div class="col-md-4 column tt_boxes">
                                        <label>Source</label>
                                        <?php
                                            $subs_source_sql = $this->db->query("
                                            SELECT *
                                            FROM `subscription_source`		
                                            ");

                                            $source_name = '';
                                            foreach( $subs_source_sql->result() as $subs_source_row){
                                                if ($subs_source_row->id == $prop_subs_row->source) {
                                                    $source_name = $subs_source_row->source_name;
                                                }
                                            }
                                        ?>	
                                        <a class="" data-auto-focus="false" data-fancybox data-src="#fancybox_subscription_start_date_source" href="javascript:;"><?=($source_name != '') ? $source_name: 'No Data';?></a>
                                    </div>
                                </div>
                                <div class="row form-group" style="margin-bottom: 0;">
                                    <div class="col-md-4 column tt_boxes">
                                        <label>Subscription valid from</label>
                                        <?php
                                        if( $prop_subs_row->subscription_date != '' && $ps_sql3_row->ps_count > 0 ){ ?>
                                            <?php echo date('d/m/Y',strtotime($sub_valid_from)); ?> to <?php echo date('d/m/Y',strtotime($sub_valid_to)); ?>
                                        <?php
                                        } else {
                                            echo "No Data";
                                        }
                                        ?>	
                                    </div>
                                    <div class="col-md-4 column tt_boxes">
                                        <label>Last <?=$this->config->item('company_name_short')?> Visit</label>
                                        <?php
                                            $last_stats_visit_sql = $this->db->query("
                                            SELECT date
                                            FROM `jobs`
                                            WHERE (assigned_tech != 1 OR assigned_tech != 2)
                                            AND property_id = {$property_id}
                                            AND status = 'Completed'
                                            AND del_job = 0
                                            ORDER BY date DESC LIMIT 1
                                            ");
                                            $last_stats_visi_row = $last_stats_visit_sql->row();
                                        ?>	
                                        <?=($last_stats_visi_row->date != '') ? date('d/m/Y',strtotime($last_stats_visi_row->date)) : 'No Data';?>
                                    </div>
                                    <div class="col-md-4 column tt_boxes">
                                        <label>Subscription Billed
                                        </label>
                                        <a class="" data-auto-focus="false" data-fancybox data-src="#fancybox_subscription_billed" href="javascript:;"><?=($row['subscription_billed'] == 1) ? 'Yes':'No';?></a>
                                    </div>
                                </div>
                                <!-- <div class="row form-group">
                                    <div class="col-md-6 column tt_boxes">
                                        <button style='margin-right: 9px;' class="submitbtnImg colorwhite btn_update_prop btn btn-danger" onclick="update_subscription_source()" type="button">Update</button>
                                    </div>
                                </div> -->
                            </div>
                        </section>
                    </div>
                </div>
                <?php
                if ($this->config->item('theme') == 'sas') {
                    $border_color = '#00607f;';
                } else {
                    $border_color = '#00a8ff;';
                }
                if(isset($_GET['map'])){ ?>
                    <div id="map-canvas" style="width:100%;height:400px;border:1px solid #cccccc; border-color: <?=$border_color?>"></div>
                <?php } else { ?>
                    <div id="map-image-container" style="width:100%;height:400px;border:1px solid #cccccc; border-color: <?=$border_color?>">
                        <img id="map-image" style="cursor: pointer;" src="<?php echo base_url() ?>/images/google_map/vpd_map.png" alt="" onclick="view_map()">
                    </div>
                <?php } ?>
            </div>
        </div>
        <br>
    <!-- End of Address Details -->    

    <div id="fancybox_building_name" style="display:none; padding: 30px">
        <section class="card card-blue-fill">
            <header class="card-header">
                <div class="row">
                    <div class="col-md-9" > <span >Building Name </span> </div>
                </div> 
        </header>
            <div class="card-block">
                
                <div id="ajax_address_div">
                    <div class="default_address">
                        <div class="row">
                            <div class="col-md-12 columns">
                                <div class="form-group">
                                    <input type='text' name='building_name' id='building_name' value="<?php echo $row['building_name'] ?>" class='form-control vw-pro-dtl-tnt short-fld'>
                                    <input type='hidden' name='og_building_name' id='og_building_name' value="<?php echo $row['building_name'] ?>" class='form-control vw-pro-dtl-tnt short-fld'>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                    
            </div>
        </section>

        <div class="text-right">
            <button class="btn btn-primmary" id="btn_update_property_building_name">Update</button>
        </div>
    </div>

    <div id="fancybox_prop_notes" style="display:none; padding: 30px">
        <section class="card card-blue-fill">
            <header class="card-header">
                <div class="row">
                    <div class="col-md-9" > <span >Property Notes</span> </div>
                </div> 
        </header>
            <div class="card-block">
                
                <div id="ajax_address_div">
                    <div class="default_address">
                        <div class="row">
                            <div class="col-md-12 columns">
                                <div class="form-group">
                                    <textarea name="comments" id="comments" cols="30" rows="10" class="form-control"><?php echo $row['comments'] ?></textarea>
                                    <textarea style="display: none;" name="og_comments" id="og_comments" cols="30" rows="10" class="form-control"><?php echo $row['comments'] ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                    
            </div>
        </section>

        <div class="text-right">
            <button class="btn btn-primmary" id="btn_update_property_comments">Update</button>
        </div>
    </div>

    <div id="fancybox_lockbox_code" style="display:none; padding: 30px">
        <section class="card card-blue-fill">
            <header class="card-header">
                <div class="row">
                    <div class="col-md-9" > <span >Lockbox Code</span> </div>
                </div> 
        </header>
            <div class="card-block">
                
                <div id="ajax_address_div">
                    <div class="default_address">
                        <div class="row">
                            <div class="col-md-12 columns">
                                <div class="form-group">
                                <input type='text' name='code' id='code' value="<?php echo $row['code'] ?>" class='form-control vw-pro-dtl-tnt short-fld'>
                                    <input type='hidden' name='og_code' id='og_code' value="<?php echo $row['code'] ?>" class='form-control vw-pro-dtl-tnt short-fld'>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                    
            </div>
        </section>

        <div class="text-right">
            <button class="btn btn-primmary" id="btn_update_property_code">Update</button>
        </div>
    </div>

    <div id="fancybox_api_id" style="display:none; padding: 30px">
        <section class="card card-blue-fill">
            <header class="card-header">
                <div class="row">
                    <div class="col-md-9" > <span ><?=$apiname?></span> </div>
                </div> 
        </header>
            <div class="card-block">
                
                <div id="ajax_address_div">
                    <div class="default_address">
                        <div class="row">
                            <div class="col-md-12 columns">
                                <div class="form-group">
                                <input type='text' readonly name='api_prop_id' id='api_prop_id' value="<?php echo $api_prop_id ?>" class='form-control vw-pro-dtl-tnt short-fld'>
                                    <input type='hidden' name='og_api_prop_id' id='og_api_prop_id' value="<?php echo $api_prop_id ?>" class='form-control vw-pro-dtl-tnt short-fld'>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                    
            </div>
        </section>
        <div class="text-right">
            <button class="btn btn-danger" id="btn_remove_property_api_prop_id">Remove</button>
        </div>
    </div>    

    <div id="fancybox_key_num" style="display:none; padding: 30px">
        <section class="card card-blue-fill">
            <header class="card-header">
                <div class="row">
                    <div class="col-md-9" > <span >Key Number</span> </div>
                </div> 
        </header>
            <div class="card-block">
                
                <div id="ajax_address_div">
                    <div class="default_address">
                        <div class="row">
                            <div class="col-md-12 columns">
                                <div class="form-group">
                                <input type='text' name='key_number' id='key_number' value="<?php echo $row['key_number'] ?>" class='form-control vw-pro-dtl-tnt short-fld'>
                                    <input type='hidden' name='og_key_number' id='og_key_number' value="<?php echo $row['key_number'] ?>" class='form-control vw-pro-dtl-tnt short-fld'>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                    
            </div>
        </section>

        <div class="text-right">
            <?php if($pme_key_number != ""){ ?>
                <input type="hidden" name="api_key_number" id="api_key_number" value="<?=$api_key_number?>">
                <button style="float: left;" class="btn btn-danger" id="btn_update_property_key_num_api">Update to <?=$pme_key_number?></button>
            <?php } ?>
            <button class="btn btn-primmary" id="btn_update_property_key_num">Update</button>
        </div>
    </div>

    <div id="fancybox_alarm_code" style="display:none; padding: 30px">
        <section class="card card-blue-fill">
            <header class="card-header">
                <div class="row">
                    <div class="col-md-9" > <span >Alarm System Code</span> </div>
                </div> 
        </header>
            <div class="card-block">
                
                <div id="ajax_address_div">
                    <div class="default_address">
                        <div class="row">
                            <div class="col-md-12 columns">
                                <div class="form-group">
                                <input type='text' name='alarm_code' id='alarm_code' value="<?php echo $row['alarm_code'] ?>" class='form-control vw-pro-dtl-tnt short-fld'>
                                    <input type='hidden' name='og_alarm_code' id='og_alarm_code' value="<?php echo $row['alarm_code'] ?>" class='form-control vw-pro-dtl-tnt short-fld'>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                    
            </div>
        </section>

        <div class="text-right">
            <button class="btn btn-primmary" id="btn_update_property_alarm_code">Update</button>
        </div>
    </div>

    <div id="fancybox_nlm" style="display:none; min-width: 600px; padding: 30px">
        <section class="card card-blue-fill">
            <header class="card-header">
                <div class="row">
                    <div class="col-md-9" > <span >No Longer Manage</span> </div>
                </div> 
        </header>
            <div class="card-block">
                
                <div id="ajax_address_div">
                    <div class="default_address">
                        <div class="row">
                            <div class="col-md-6 columns">
                                <div class="form-group">
                                    <label class="form-label">No Longer Managed From*</label>
                                    <input type="text" style="width: 145px;" data-allow-input="true" class="flatpickr datepicker form-control flatpickr-input" name="nlm_from" id="nlm_from" value="" placeholder='DD/MM/YYYY' />
                                </div>
                            </div>
                            <div class="col-md-6 columns">
                                <div class="form-group">
                                    <label class="form-label">Reason they Left*</label>
                                    <select name="reason_they_left" id="reason_they_left" class="form-control" onchange="check_other_reason(this.value)">
                                        <option value="">---Select Reason---</option>
                                        <?php
                                            foreach( $lr_sql->result() as $lr_row ){ ?>
                                                <option value="<?php echo $lr_row->id; ?>"><?php echo $lr_row->reason; ?></option> 
                                            <?php
                                            }                                         
                                            ?> 
                                            <option value="-1">Other</option> 
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 columns" id="div_other_reason_nlm" style="display:none;">
                                <div class="form-group">
                                    <label class="form-label">"Other Reason"</label>
                                    <textarea name="other_reason_nlm" id="other_reason_nlm" cols="10" rows="3" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                    
            </div>
        </section>

        <div class="text-right">
            <button class="btn btn-primmary" id="btn_update_property_nlm">Update</button>
        </div>
    </div>

    <div id="fancybox_delete" style="display:none; min-width: 600px; padding: 30px">
        <section class="card card-blue-fill">
            <header class="card-header">
                <div class="row">
                    <div class="col-md-9" > <span >Delete Property</span> </div>
                </div> 
        </header>
            <div class="card-block">
                
                <div id="ajax_address_div">
                    <div class="default_address">
                        <div class="row">
                            <div class="col-md-12 columns">
                                <div class="form-group">
                                    <label class="form-label">Reason</label>
                                    <select name="delete_reason" id="delete_reason" class="form-control">
                                        <option value="">-- Select Reason --</option>
                                        <option value="Duplicate Property">Duplicate Property</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 columns" id="div_other_reason" style="display:none;">
                                <div class="form-group">
                                    <label class="form-label">"Other Reason"</label>
                                    <textarea name="other_reason" id="other_reason" cols="10" rows="3" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                    
            </div>
        </section>

        <div class="text-right">
            <button class="btn btn-danger" id="btn_delete_permanently">Delete</button>
        </div>
    </div>

    <div id="fancybox_source_property" style="display:none; min-width: 600px; padding: 30px">
        <section class="card card-blue-fill">
            <header class="card-header">
                <div class="row">
                    <div class="col-md-9" > <span >Source of Property</span> </div>
                </div> 
        </header>
            <div class="card-block">
                
                <div id="ajax_address_div">
                    <div class="default_address">
                        <div class="row">
                            <div class="col-md-12 columns">
                                <select name="from_other_company" id="from_other_company" class="form-control">
                                    <option value="">--- Select ---</option>
                                    <?php
                                    $pfoc_row = $pfoc_sql->row();
                                    foreach( $sa_comp_sql->result() as $sa_comp_row){ ?>
                                        <option 
                                            value="<?php echo $sa_comp_row->sac_id.','.$sa_comp_row->company_name; ?>" 
                                            <?php echo ( $sa_comp_row->sac_id == $pfoc_row->company_id )?'selected':null; ?>
                                        >
                                            <?php echo $sa_comp_row->company_name; ?>
                                        </option>
                                    <?php
                                    }
                                    ?>															
                                </select>
                                <input type="hidden" name="og_from_other_company" id="og_from_other_company" value="<?=$pfoc_row->company_id?>,<?=$pfoc_row->company_name?>">
                            </div>
                        </div>
                    </div>
                </div>                    
            </div>
        </section>
        <div class="text-right">
            <button class="btn btn-primary" onclick="update_from_other_company()">Update</button>
        </div>
    </div>

    <div id="fancybox_propery_upgraded" style="display:none; min-width: 600px; padding: 30px">
        <section class="card card-blue-fill">
            <header class="card-header">
                <div class="row">
                    <div class="col-md-9" > <span >Property Upgraded (QLD)</span> </div>
                </div> 
        </header>
            <div class="card-block">
                
                <div id="ajax_address_div">
                    <div class="default_address">
                        <div class="row">
                            <div class="col-md-12 columns">
                                <select name="prop_upgraded_to_ic_sa" id="prop_upgraded_to_ic_sa" class="form-control">
                                    <option value="">--- Select ---</option>
                                    <option value="1" <?php echo ( $row['prop_upgraded_to_ic_sa'] == 1 )?'selected="selected"':''; ?>>Yes</option>
                                    <option value="0" <?php echo ( is_numeric($row['prop_upgraded_to_ic_sa']) && $row['prop_upgraded_to_ic_sa'] == 0 )?'selected="selected"':''; ?>>No</option>
                                </select>
                                <input type="hidden" name="og_prop_upgraded_to_ic_sa" id="og_prop_upgraded_to_ic_sa" value="<?=$row['prop_upgraded_to_ic_sa']?>">
                            </div>
                        </div>
                    </div>
                </div>                    
            </div>
        </section>
        <div class="text-right">
            <button class="btn btn-primary" onclick="update_prop_upgraded_to_ic_sa()">Update</button>
        </div>
    </div>

    <div id="fancybox_property_manager" style="display:none; min-width: 600px; padding: 30px">
        <section class="card card-blue-fill">
            <header class="card-header">
                <div class="row">
                    <div class="col-md-9" > <span >Property Manager</span> </div>
                </div> 
        </header>
            <div class="card-block">
                
                <div id="ajax_address_div">
                    <div class="default_address">
                        <div class="row">
                            <div class="col-md-12 columns">
                            <?php
                                //default value of pm is 1
                                $pm_user_id = NULL;
                                if (isset($row['pm_id_new'])) {
                                    $new_pm = 1;
                                    if ($new_pm == 1) {
                                        $agency_user_account_id_sql = $this->db->query("
                                            SELECT *
                                            FROM `agency_user_accounts`
                                            WHERE `agency_user_account_id` = {$row['pm_id_new']} ORDER BY fname           
                                        ");
                                    } else {
                                        $agency_user_account_id_sql = $this->db->query("
                                            SELECT *
                                            FROM `property_managers`
                                            WHERE `property_managers_id` = {$row['pm_id_new']} ORDER BY name     
                                        ");
                                    }
                                    $pm_sel_query = $agency_user_account_id_sql->row();
                                    $pm_user_id = $pm_sel_query->agency_user_account_id;
                                }
                                ?>
                                <select name="pm_id_new" id="pm_id_new" class="form-control"> 
                                        <option value="">Select</option>
                                    <?php foreach($prop_manager->result() as $pm_row){ 
                                        $pm_sel = ($pm_row->agency_user_account_id==$pm_user_id)? 'selected="true"' : NULL;
                                        ?>
                                        <option <?php echo $pm_sel; ?> value="<?php echo $pm_row->agency_user_account_id.','.$pm_row->fname.' '.$pm_row->lname ?>"><?php echo "{$pm_row->fname} {$pm_row->lname}" ?></option>
                                    <?php } ?>
                                </select>
                                <input type="hidden" name="og_pm_id_new" id="og_pm_id_new" value="<?=$pm_sel_query->agency_user_account_id.','.$pm_sel_query->fname.' '.$pm_sel_query->lname?>">
                            </div>
                        </div>
                    </div>
                </div>                    
            </div>
        </section>
        <div class="text-right">
            <button class="btn btn-primary" onclick="update_pm()">Update</button>
        </div>
    </div>

    <div id="fancybox_agency_price_variation" style="display:none; min-width: 600px; padding: 30px">
        <section class="card card-blue-fill">
            <header class="card-header">
                <div class="row">
                    <div class="col-md-9" > <span >Property Price Variation</span> </div>
                </div> 
        </header>
            <div class="card-block">
                
                <div id="ajax_address_div">
                    <div class="default_address">
                        <div class="row">
                            <div class="col-md-12 columns">
                                <select name="agency_price_variation" id="agency_price_variation" class="form-control"> 
                                    <option value="">Select</option>
                                    <?php foreach($sql_agency_var->result() as $apv_row) { 
                                        if ($pv_row->agency_price_variation == $apv_row->id) {
                                            $variation_name = number_format($apv_row->amount, 2) . ' (' . (($apv_row->type == 1) ? 'Discount' : 'Surcharge') . ' ' . $apv_row->apvr_reason . ')';
                                        }
                                        if ($pv_row->agency_price_variation == $apv_row->id) {
                                            $number = number_format($apv_row->amount, 2);
                                            $type = ($apv_row->type == 1) ? 'Discount' : 'Surcharge';
                                            $variation_name = '$'.$number.' ('.$type.' - '.$apv_row->apvr_reason.')';
                                        } 
                                    ?>
                                        <option value="<?php echo $apv_row->id.','.number_format($apv_row->amount, 2) . ' (' . (($apv_row->type == 1) ? 'Discount' : 'Surcharge') . ' ' . $apv_row->apvr_reason . ')'; ?>" <?php echo ($pv_row->agency_price_variation == $apv_row->id) ? 'selected' : null; ?>>
                                            $<?php echo number_format($apv_row->amount, 2); ?> 
                                            (<?php echo ($apv_row->type == 1) ? 'Discount' : 'Surcharge'; ?> - <?php echo $apv_row->apvr_reason; ?>)
                                        </option>
                                    <?php } ?>
                                </select>
                                <input type="hidden" name="og_agency_price_variation" id="og_agency_price_variation" value="<?php echo $pv_row->agency_price_variation . ',' . $variation_name; ?>">
                            </div>
                        </div>
                    </div>
                </div>                    
            </div>
        </section>
        <div class="text-right">
            <button class="btn btn-danger" style="float: left;" onclick="remove_prop_variation('<?=$property_id?>')">Remove</button>
            <button class="btn btn-primary" onclick="update_price_varation()">Update</button>
        </div>
    </div>

    <div id="fancybox_subscription_start_date_source" style="display:none; min-width: 600px; padding: 30px">
        <section class="card card-blue-fill">
            <header class="card-header">
                <div class="row">
                    <div class="col-md-9" > <span >Subscription Start Date/Source</span> </div>
                </div> 
        </header>
            <div class="card-block">
                
                <div id="ajax_address_div">
                    <div class="default_address">
                        <div class="row">
                            <div class="col-md-6 columns">
                                <label for="">Date</label>
                                <?php
                                $subscription_date = strtotime($prop_subs_row->subscription_date);
                                $today = strtotime('today');
                                
                                if ($subscription_date != '') {
                                    $date_value = date('d/m/Y', $subscription_date);
                                } else {
                                    $date_value = date('d/m/Y');
                                }
                                ?>
                                    <input type="text" data-allow-input="true" class="flatpickr datepicker form-control flatpickr-input" name="subscription_date" id="subscription_date" value="<?php echo $date_value; ?>" />
                            </div>
                            <div class="col-md-6 columns">
                                <label for="">Source</label>
                                <select name="subscription_source" id="subscription_source" class="form-control" style="display: inherit; width: 200px;">
                                    <option value="">---</option>
                                    <?php
                                    $source_name = '';
                                    foreach( $subs_source_sql->result() as $subs_source_row){
                                        if ($subs_source_row->id == $prop_subs_row->source) {
                                            $source_name = $subs_source_row->source_name;
                                        }
                                        ?>
                                        <option value="<?php echo $subs_source_row->id; ?>" <?php echo ( $subs_source_row->id == $prop_subs_row->source )?'selected':null; ?>><?php echo $subs_source_row->source_name; ?></option>
                                    <?php
                                    }
                                    ?>									
                                </select>
                                <input type="hidden" data-allow-input="true" class="form-control" name="og_subscription_date" id="og_subscription_date" value="<?php echo $date_value; ?>" />
                                <input type="hidden" data-allow-input="true" class="form-control" name="og_subscription_source" id="og_subscription_source" value="<?php echo $source_name; ?>" />
                            </div>
                        </div>
                    </div>
                </div>                    
            </div>
        </section>
        <div class="text-right">
            <button class="btn btn-danger" style="float: left;" onclick="fetch_date()">Fetch Date</button>
            <button class="btn btn-primary" onclick="update_subscription_source()">Add/Update</button>
        </div>
    </div>

    <div id="fancybox_agency_keys" style="display:none; min-width: 600px; padding: 30px">
        <section class="card card-blue-fill">
            <header class="card-header">
                <div class="row">
                    <div class="col-md-9" > <span >Agency Key Pick up Address</span> </div>
                </div> 
        </header>
            <div class="card-block">
                
                <div id="ajax_address_div">
                    <div class="default_address">
                        <div class="row">
                            <div class="col-md-12 columns">
                                <select name="agency_keys" id="agency_keys" class="form-control"> 
                                        <option value="">Select</option>
                                    <?php 
                                    if( $agency_add_sql_str1->num_rows() > 0 ){																								
                                        foreach( $agency_add_sql_str1->result() as $agency_add_row ){
                                            $address_1 = $agency_add_row->address_1;
                                            $address_2 = $agency_add_row->address_2;
                                            $address_3 = $agency_add_row->address_3;
                                            $state = $agency_add_row->state;
                                            $postcode = $agency_add_row->postcode;
                                            $check_address_sql1 = $this->db->query("SELECT `id` FROM `agency_addresses` WHERE `agency_id`={$agency_id}");
                                            $row1 = $check_address_sql1->row();
                                            // $row = $check_address_sql->row();
                                            // if( $check_address_sql->num_rows() == 0 ){
                                                $agency_add_row_add_comb = "{$address_1} {$address_2}, {$address_3}"; 
                                                echo "<option value='$row1->id'>Default {$agency_name} {$agency_add_row_add_comb}</option>";
                                            // }
                                        }
                                    }

                                    $key_add_num1 = 1;
                                    foreach($agency_add_sql_str->result() as $agency_add_row){ 
                                        $agen_add_comb = "{$agency_add_row->agen_add_street_num} {$agency_add_row->agen_add_street_name}, {$agency_add_row->agen_add_suburb}"; 
                                        $check_address_sql = $this->db->query("SELECT `id` FROM `property_keys` WHERE `property_id`='{$row['property_id']}' AND `agency_addresses_id`='{$agency_add_row->agen_add_id}'");
                                        $is_selected = ($check_address_sql->num_rows() > 0 ? 'selected': '');
                                        echo "<option value='$agency_add_row->agen_add_id' $is_selected>Key #$key_add_num1 $agen_add_comb</option>";
                                        $key_add_num1 ++;
                                        ?>  
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>                    
            </div>
        </section>
        <div class="text-right">
            <button class="btn btn-primary" onclick="update_agency_keys()">Update</button>
        </div>
    </div>

    <div id="fancybox_attached_garage_requires_alarm" style="display:none; min-width: 600px; padding: 30px">
        <section class="card card-blue-fill">
            <header class="card-header">
                <div class="row">
                    <div class="col-md-9" > <span >Attached Garage Requires Alarm</span> </div>
                </div> 
        </header>
            <div class="card-block">
                
                <div id="ajax_address_div">
                    <div class="default_address">
                        <div class="row">
                            <div class="col-md-12 columns">
                                <select name="service_garage" id="service_garage" class="form-control"> 
                                        <option value="">Select</option>
                                        <option value="1" <?php echo (($row['service_garage']==1)?'selected':''); ?>>Yes</option>
                                        <option value="0" <?php echo (($row['service_garage']==0)?'selected':''); ?>>No</option>
                                        
                                </select>
                                <input type="hidden" id="og_service_garage" value="<?=$row['service_garage']?>">
                            </div>
                        </div>
                    </div>
                </div>                    
            </div>
        </section>
        <div class="text-right">
            <button class="btn btn-primary" onclick="update_yes_no('service_garage','Attached Garage Requires Alarm')">Update</button>
        </div>
    </div>

    <div id="fancybox_short_term_rental" style="display:none; min-width: 600px; padding: 30px">
        <section class="card card-blue-fill">
            <header class="card-header">
                <div class="row">
                    <div class="col-md-9" > <span >Short Term Rental</span> </div>
                </div> 
        </header>
            <div class="card-block">
                
                <div id="ajax_address_div">
                    <div class="default_address">
                        <div class="row">
                            <div class="col-md-12 columns">
                                <select name="holiday_rental" id="holiday_rental" class="form-control"> 
                                        <option value="">Select</option>
                                        <option value="1" <?php echo (($row['holiday_rental']==1)?'selected':''); ?>>Yes</option>
                                        <option value="0" <?php echo (($row['holiday_rental']==0)?'selected':''); ?>>No</option>
                                        
                                </select>
                                <input type="hidden" id="og_holiday_rental" value="<?=$row['holiday_rental']?>">
                            </div>
                        </div>
                    </div>
                </div>                    
            </div>
        </section>
        <div class="text-right">
            <button class="btn btn-primary" onclick="update_yes_no('holiday_rental','Short Term Rental')">Update</button>
        </div>
    </div>

    <div id="fancybox_sales_property" style="display:none; min-width: 600px; padding: 30px">
        <section class="card card-blue-fill">
            <header class="card-header">
                <div class="row">
                    <div class="col-md-9" > <span >Sales Property</span> </div>
                </div> 
        </header>
            <div class="card-block">
                
                <div id="ajax_address_div">
                    <div class="default_address">
                        <div class="row">
                            <div class="col-md-12 columns">
                                <select name="is_sales" id="is_sales" class="form-control"> 
                                        <option value="">Select</option>
                                        <option value="1" <?php echo (($row['is_sales']==1)?'selected':''); ?>>Yes</option>
                                        <option value="0" <?php echo (($row['is_sales']==0)?'selected':''); ?>>No</option>
                                        
                                </select>
                                <input type="hidden" id="og_holiday_rental" value="<?=$row['holiday_rental']?>">
                            </div>
                        </div>
                    </div>
                </div>                    
            </div>
        </section>
        <div class="text-right">
            <button class="btn btn-primary" onclick="update_yes_no('is_sales','Sales Property')">Update</button>
        </div>
    </div>

    <div id="fancybox_manual_renewal" style="display:none; min-width: 600px; padding: 30px">
        <section class="card card-blue-fill">
            <header class="card-header">
                <div class="row">
                    <div class="col-md-9" > <span >Auto Renew Subscription</span> </div>
                </div> 
        </header>
            <div class="card-block">
                
                <div id="ajax_address_div">
                    <div class="default_address">
                        <div class="row">
                            <div class="col-md-12 columns">
                                <select name="manual_renewal" id="manual_renewal" class="form-control"> 
                                        <option value="">Select</option>
                                        <option value="1" <?php echo (($row['manual_renewal']==1)?'selected':''); ?>>Yes</option>
                                        <option value="0" <?php echo (($row['manual_renewal']==0)?'selected':''); ?>>No</option>
                                        
                                </select>
                                <input type="hidden" id="og_manual_renewal" value="<?=$row['manual_renewal']?>">
                            </div>
                        </div>
                    </div>
                </div>                    
            </div>
        </section>
        <div class="text-right">
            <button class="btn btn-primary" onclick="update_yes_no('manual_renewal','Auto Renew Subscription')">Update</button>
        </div>
    </div>

    <div id="fancybox_subscription_billed" style="display:none; min-width: 600px; padding: 30px">
        <section class="card card-blue-fill">
            <header class="card-header">
                <div class="row">
                    <div class="col-md-9" > <span >Subscription Billed</span> </div>
                </div> 
        </header>
            <div class="card-block">
                
                <div id="ajax_address_div">
                    <div class="default_address">
                        <div class="row">
                            <div class="col-md-12 columns">
                                <select name="subscription_billed" id="subscription_billed" class="form-control"> 
                                        <option value="">Select</option>
                                        <option value="1" <?php echo (($row['subscription_billed']==1)?'selected':''); ?>>Yes</option>
                                        <option value="0" <?php echo (($row['subscription_billed']==0)?'selected':''); ?>>No</option>
                                        
                                </select>
                                <input type="hidden" id="og_subscription_billed" value="<?=$row['subscription_billed']?>">
                            </div>
                        </div>
                    </div>
                </div>                    
            </div>
        </section>
        <div class="text-right">
            <button class="btn btn-primary" onclick="update_yes_no('subscription_billed','Subscription Billed')">Update</button>
        </div>
    </div>
    <!-- end of pop up message  --> 
</div>

<script>

    function remove_prop_variation(property_id){

        og_agency_price_variation = $('#og_agency_price_variation').val();
        og_array = og_agency_price_variation.split(',');
        og_agency_price_variation = og_array[0];
        og_agency_price_variation_name = og_array[1];
        
        if (confirm("Are you sure?")) {
            $('#load-screen').show();
            jQuery.ajax({
                type: "POST",
                url: "/properties/ajax_update_property",
                dataType: 'json',
                data: {
                    property_id: property_id,
                    og_agency_price_variation_name: og_agency_price_variation_name,
                    property_update: 'remove_prop_variation'
                    
                }
            }).done(function( ret ) {	
                $('#load-screen').hide();
                if(ret.status){
                    $('#load-screen').hide();
                    swal({
                        title:"Success!",
                        text: "Remove Successful",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonText: "OK",
                        closeOnConfirm: false,  
                        showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                        timer: <?php echo $this->config->item('timer') ?>
                    });
                    var full_url = "/properties/details/?id=<?php echo $property_id; ?>&tab=1";
                    setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                }
            }); 
        }
    }
    
    function view_map(){
        var full_url = "/properties/details/?id=<?php echo $property_id; ?>&tab=1&map=1";
        window.location=full_url;
    }

    function update_yes_no(field,log_details){
        value =  $('#'+field).val();
        og_value =  $('#og_'+field).val();
        $('#load-screen').show();
        jQuery.ajax({
            type: "POST",
            url: "/properties/ajax_update_property",
            dataType: 'json',
            data: {
                property_id: <?php echo $property_id; ?>,
                name: field,
                value: value,
                og_value: og_value,
                log_details: log_details,
                property_update: 'update_tt_boxes_lightbox'
                
            }
        }).done(function( ret ) {	
            $('#load-screen').hide();
            if(ret.status){
                $('#load-screen').hide();
                swal({
                    title:"Success!",
                    text: "Update Successful",
                    type: "success",
                    showCancelButton: false,
                    confirmButtonText: "OK",
                    closeOnConfirm: false,  
                    showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                    timer: <?php echo $this->config->item('timer') ?>
                });
                var full_url = "/properties/details/?id=<?php echo $property_id; ?>&tab=1";
                setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
            }
        }); 
    }


    jQuery(document).ready(function(){
    
        //update building name
        $('#btn_update_property_building_name').on('click',function(){
            var building_name = $('#building_name').val();
            var og_building_name = $('#og_building_name').val();
            var err = "";

            if(building_name == ""){
                err+="Building Name is required";
            }

            if(err!=""){
                swal('',err,'error');
                return false;
            }

            $('#load-screen').show();
            jQuery.ajax({
                type: "POST",
                url: "/properties/ajax_update_property",
                dataType: 'json',
                data: {
                    property_id: <?php echo $property_id; ?>,
                    building_name: building_name,
                    og_building_name:og_building_name,
                    property_update: 'update_building_name'
                    
                }
            }).done(function( ret ) {	
                $('#load-screen').hide();
                if(ret.status){
                    $('#load-screen').hide();
                    swal({
                        title:"Success!",
                        text: "Update Successful",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonText: "OK",
                        closeOnConfirm: false,  
                        showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                        timer: <?php echo $this->config->item('timer') ?>
                    });
                    var full_url = "/properties/details/?id=<?php echo $property_id; ?>&tab=1";
                    setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                }
            }); 

        })

        //update comments
        $('#btn_update_property_comments').on('click',function(){
            var comments = $('#comments').val();
            var og_comments = $('#og_comments').val();
            var err = "";

            if(comments == ""){
                err+="Property Notes is required";
            }

            if(err!=""){
                swal('',err,'error');
                return false;
            }

            $('#load-screen').show();
            jQuery.ajax({
                type: "POST",
                url: "/properties/ajax_update_property",
                dataType: 'json',
                data: {
                    property_id: <?php echo $property_id; ?>,
                    comments: comments,
                    og_comments:og_comments,
                    property_update: 'update_comments'
                    
                }
            }).done(function( ret ) {	
                $('#load-screen').hide();
                if(ret.status){
                    $('#load-screen').hide();
                    swal({
                        title:"Success!",
                        text: "Update Successful",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonText: "OK",
                        closeOnConfirm: false,  
                        showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                        timer: <?php echo $this->config->item('timer') ?>
                    });
                    var full_url = "/properties/details/?id=<?php echo $property_id; ?>&tab=1";
                    setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                }
            }); 

        })

        //update lockbox code
        $('#btn_update_property_code').on('click',function(){
            var code = $('#code').val();
            var og_code = $('#og_code').val();
            var err = "";

            if(code == ""){
                err+="Lockbox Code is required";
            }

            if(err!=""){
                swal('',err,'error');
                return false;
            }

            $('#load-screen').show();
            jQuery.ajax({
                type: "POST",
                url: "/properties/ajax_update_property",
                dataType: 'json',
                data: {
                    property_id: <?php echo $property_id; ?>,
                    code: code,
                    og_code:og_code,
                    property_update: 'update_lockbox_code'
                    
                }
            }).done(function( ret ) {	
                $('#load-screen').hide();
                if(ret.status){
                    $('#load-screen').hide();
                    swal({
                        title:"Success!",
                        text: "Update Successful",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonText: "OK",
                        closeOnConfirm: false,  
                        showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                        timer: <?php echo $this->config->item('timer') ?>
                    });
                    var full_url = "/properties/details/?id=<?php echo $property_id; ?>&tab=1";
                    setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                }
            }); 

        })

        //update api id
        $('#btn_remove_property_api_prop_id').on('click',function(){

            $('#load-screen').show();
            jQuery.ajax({
                type: "POST",
                url: "/properties/ajax_update_property",
                dataType: 'json',
                data: {
                    property_id: <?php echo $property_id; ?>,
                    property_update: 'update_api_id'
                    
                }
            }).done(function( ret ) {	
                $('#load-screen').hide();
                if(ret.status){
                    $('#load-screen').hide();
                    swal({
                        title:"Success!",
                        text: "Update Successful",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonText: "OK",
                        closeOnConfirm: false,  
                        showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                        timer: <?php echo $this->config->item('timer') ?>
                    });
                    var full_url = "/properties/details/?id=<?php echo $property_id; ?>&tab=1";
                    setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                }
            }); 

        })


        //update key number
        $('#btn_update_property_key_num').on('click',function(){
            var key_number = $('#key_number').val();
            var og_key_number = $('#og_key_number').val();
            var err = "";

            if(key_number == ""){
                err+="Key Number is required";
            }

            if(err!=""){
                swal('',err,'error');
                return false;
            }

            $('#load-screen').show();
            jQuery.ajax({
                type: "POST",
                url: "/properties/ajax_update_property",
                dataType: 'json',
                data: {
                    property_id: <?php echo $property_id; ?>,
                    key_number: key_number,
                    og_key_number:og_key_number,
                    property_update: 'update_key_number'
                    
                }
            }).done(function( ret ) {	
                $('#load-screen').hide();
                if(ret.status){
                    $('#load-screen').hide();
                    swal({
                        title:"Success!",
                        text: "Update Successful",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonText: "OK",
                        closeOnConfirm: false,  
                        showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                        timer: <?php echo $this->config->item('timer') ?>
                    });
                    var full_url = "/properties/details/?id=<?php echo $property_id; ?>&tab=1";
                    setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                }
            }); 

        })

        $('#btn_update_property_key_num_api').on('click',function(){
            var api_key_number = $('#api_key_number').val();
            var og_key_number = $('#key_number').val();
            var err = "";

            if(key_number == ""){
                err+="Key Number is required";
            }

            if(err!=""){
                swal('',err,'error');
                return false;
            }

            $('#load-screen').show();
            jQuery.ajax({
                type: "POST",
                url: "/properties/ajax_update_property",
                dataType: 'json',
                data: {
                    property_id: <?php echo $property_id; ?>,
                    key_number: api_key_number,
                    og_key_number:og_key_number,
                    property_update: 'update_key_number'
                    
                }
            }).done(function( ret ) {	
                $('#load-screen').hide();
                if(ret.status){
                    $('#load-screen').hide();
                    swal({
                        title:"Success!",
                        text: "Update Successful",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonText: "OK",
                        closeOnConfirm: false,  
                        showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                        timer: <?php echo $this->config->item('timer') ?>
                    });
                    var full_url = "/properties/details/?id=<?php echo $property_id; ?>&tab=1";
                    setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                }
            }); 

        })

        //update Alarm System Code
        $('#btn_update_property_alarm_code').on('click',function(){
            var alarm_code = $('#alarm_code').val();
            var og_alarm_code = $('#og_alarm_code').val();
            var err = "";

            if(alarm_code == ""){
                err+="Alarm System Code is required";
            }

            if(err!=""){
                swal('',err,'error');
                return false;
            }

            $('#load-screen').show();
            jQuery.ajax({
                type: "POST",
                url: "/properties/ajax_update_property",
                dataType: 'json',
                data: {
                    property_id: <?php echo $property_id; ?>,
                    alarm_code: alarm_code,
                    og_alarm_code:og_alarm_code,
                    property_update: 'update_alarm_code'
                    
                }
            }).done(function( ret ) {	
                $('#load-screen').hide();
                if(ret.status){
                    $('#load-screen').hide();
                    swal({
                        title:"Success!",
                        text: "Update Successful",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonText: "OK",
                        closeOnConfirm: false,  
                        showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                        timer: <?php echo $this->config->item('timer') ?>
                    });
                    var full_url = "/properties/details/?id=<?php echo $property_id; ?>&tab=1";
                    setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                }
            }); 

        })

        //update nlm
        $('#btn_update_property_nlm').on('click',function(){
            var nlm_from = $('#nlm_from').val();
            var reason_they_left = $('#reason_they_left').val();
            var other_reason = $('#other_reason_nlm').val();
            var err = "";

            // validation
            if( reason_they_left == '' ){
                err += "'Reason They Left' is required\n";
            }else{
                if( reason_they_left == -1 && other_reason == '' ){
                    err += "'Other Reason' is required\n";
                }
            } 

            if(err!=""){
                swal('',err,'error');
                return false;
            }

            $('#load-screen').show();
            jQuery.ajax({
                type: "POST",
                url: "/properties/ajax_update_property",
                dataType: 'json',
                data: {
                    property_id: <?php echo $property_id; ?>,
                    agency_id: <?php echo $row['agency_id']; ?>,
                    nlm_from: nlm_from,
                    reason_they_left:reason_they_left,
                    other_reason:other_reason,
                    property_update: 'update_nlm'
                    
                }
            }).done(function( ret ) {	
                $('#load-screen').hide();
                if(ret.status){
                    $('#load-screen').hide();
                    swal({
                        title:"Success!",
                        text: "Update Successful",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonText: "OK",
                        closeOnConfirm: false,  
                        showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                        timer: 4000
                    });
                    var full_url = "/properties/details/?id=<?php echo $property_id; ?>&tab=1";
                    setTimeout(function(){ window.location=full_url }, 4000);
                } else {
                    $('#load-screen').hide();
                    swal({
                        title:"Warning!",
                        text: ret.stat_msg,
                        type: "warning",
                        showCancelButton: false,
                        confirmButtonText: "OK",
                        closeOnConfirm: false,  
                        showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                        timer: 4000
                    });
                    var full_url = "/properties/details/?id=<?php echo $property_id; ?>&tab=1";
                    setTimeout(function(){ window.location=full_url }, 4000);
                }
            }); 

        })

        // restore property script
        jQuery("#restoreProb_btn").click(function(){

            // added confirm
            if( confirm("Are you sure you want to restore this property?") ){

                jQuery( "#dialog-confirm" ).dialog({
                    resizable: false,
                    modal: true,
                    buttons: {
                        "Yes": function() {
                        var del_tenant = 1;
                        $('#load-screen').show();
                        jQuery.ajax({
                            type: "POST",
                            url: "/properties/ajax_update_property",
                            dataType: 'json',
                            data: {
                                property_id: <?php echo $property_id; ?>,
                                del_tenant: del_tenant,
                                property_update: 'restore_prop'
                                
                            }
                        }).done(function( ret ) {	
                            $('#load-screen').hide();
                            if(ret.status){
                                $('#load-screen').hide();
                                swal({
                                    title:"Success!",
                                    text: "Update Successful",
                                    type: "success",
                                    showCancelButton: false,
                                    confirmButtonText: "OK",
                                    closeOnConfirm: false,  
                                    showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                                    timer: <?php echo $this->config->item('timer') ?>
                                });
                                var full_url = "/properties/details/?id=<?php echo $property_id; ?>&tab=1";
                                setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                            } 
                        }); 
                        jQuery( this ).dialog( "close" );
                        },
                        "No": function() {
                        var del_tenant = 0;
                        $('#load-screen').show();
                        jQuery.ajax({
                            type: "POST",
                            url: "/properties/ajax_update_property",
                            dataType: 'json',
                            data: {
                                property_id: <?php echo $property_id; ?>,
                                del_tenant: del_tenant,
                                property_update: 'restore_prop'
                                
                            }
                        }).done(function( ret ) {	
                            $('#load-screen').hide();
                            if(ret.status){
                                $('#load-screen').hide();
                                swal({
                                    title:"Success!",
                                    text: "Update Successful",
                                    type: "success",
                                    showCancelButton: false,
                                    confirmButtonText: "OK",
                                    closeOnConfirm: false,  
                                    showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                                    timer: <?php echo $this->config->item('timer') ?>
                                });
                                var full_url = "/properties/details/?id=<?php echo $property_id; ?>&tab=1";
                                setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                            } 
                        }); 
                        jQuery( this ).dialog( "close" );
                        }
                    }
                });
            }
        });

    });

    function check_other_reason(value){
        if (value == '-1') {
            $('#div_other_reason_nlm').show();
        } else {
            $('#div_other_reason_nlm').hide();
        }
    }

    function update_price_varation(){

        agency_price_variation = $('#agency_price_variation').val();
        array = agency_price_variation.split(',');
        agency_price_variation = array[0];
        agency_price_variation_name = array[1];

        og_agency_price_variation = $('#og_agency_price_variation').val();
        og_array = og_agency_price_variation.split(',');
        og_agency_price_variation = og_array[0];
        og_agency_price_variation_name = og_array[1];

        $('#load-screen').show();
        jQuery.ajax({
            type: "POST",
            url: "/properties/ajax_update_property",
            dataType: 'json',
            data: {
                property_id: <?php echo $property_id; ?>,
                agency_price_variation: agency_price_variation,
                agency_price_variation_name: agency_price_variation_name,
                og_agency_price_variation: og_agency_price_variation,
                og_agency_price_variation_name: og_agency_price_variation_name,
                property_update: 'update_price_varation'
                
            }
        }).done(function( ret ) {	
            $('#load-screen').hide();
            if(ret.status){
                $('#load-screen').hide();
                swal({
                    title:"Success!",
                    text: "Update Successful",
                    type: "success",
                    showCancelButton: false,
                    confirmButtonText: "OK",
                    closeOnConfirm: false,  
                    showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                    timer: <?php echo $this->config->item('timer') ?>
                });
                var full_url = "/properties/details/?id=<?php echo $property_id; ?>&tab=1";
                setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
            }
        }); 
    }

    function update_pm(){

        pm_id_new = $('#pm_id_new').val();
        array = pm_id_new.split(',');
        pm_id_new = array[0];
        pm_name = array[1];

        og_pm_id_new = $('#og_pm_id_new').val();
        og_array = og_pm_id_new.split(',');
        og_pm_id_new = og_array[0];
        og_pm_name = og_array[1];
        $('#load-screen').show();
        jQuery.ajax({
            type: "POST",
            url: "/properties/ajax_update_property",
            dataType: 'json',
            data: {
                property_id: <?php echo $property_id; ?>,
                pm_id_new: pm_id_new,
                pm_name: pm_name,
                og_pm_id_new: og_pm_id_new,
                og_pm_name: og_pm_name,
                property_update: 'update_pm'
                
            }
        }).done(function( ret ) {	
            $('#load-screen').hide();
            if(ret.status){
                $('#load-screen').hide();
                swal({
                    title:"Success!",
                    text: "Update Successful",
                    type: "success",
                    showCancelButton: false,
                    confirmButtonText: "OK",
                    closeOnConfirm: false,  
                    showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                    timer: <?php echo $this->config->item('timer') ?>
                });
                var full_url = "/properties/details/?id=<?php echo $property_id; ?>&tab=1";
                setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
            }
        }); 
    }

    function update_prop_upgraded_to_ic_sa(){
        prop_upgraded_to_ic_sa = $('#prop_upgraded_to_ic_sa').val();
        og_prop_upgraded_to_ic_sa = $('#og_prop_upgraded_to_ic_sa').val();
        $('#load-screen').show();
        jQuery.ajax({
            type: "POST",
            url: "/properties/ajax_update_property",
            dataType: 'json',
            data: {
                property_id: <?php echo $property_id; ?>,
                prop_upgraded_to_ic_sa: prop_upgraded_to_ic_sa,
                og_prop_upgraded_to_ic_sa: og_prop_upgraded_to_ic_sa,
                property_update: 'update_prop_upgraded_to_ic_sa'
                
            }
        }).done(function( ret ) {	
            $('#load-screen').hide();
            if(ret.status){
                $('#load-screen').hide();
                swal({
                    title:"Success!",
                    text: "Update Successful",
                    type: "success",
                    showCancelButton: false,
                    confirmButtonText: "OK",
                    closeOnConfirm: false,  
                    showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                    timer: <?php echo $this->config->item('timer') ?>
                });
                var full_url = "/properties/details/?id=<?php echo $property_id; ?>&tab=1";
                setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
            }
        }); 
    }

    function update_from_other_company(){
        // alert(value);
        from_other_company = $('#from_other_company').val();
        array = from_other_company.split(',');
        sac_id = array[0];
        company_name = array[1];

        og_from_other_company = $('#og_from_other_company').val();
        og_array = og_from_other_company.split(',');
        og_sac_id = og_array[0];
        og_company_name = og_array[1];
        // alert(og_sac_id+' '+og_company_name);
        $('#load-screen').show();
        jQuery.ajax({
            type: "POST",
            url: "/properties/ajax_update_property",
            dataType: 'json',
            data: {
                property_id: <?php echo $property_id; ?>,
                sac_id: sac_id,
                company_name: company_name,
                og_sac_id: og_sac_id,
                og_company_name: og_company_name,
                property_update: 'update_from_other_company'
                
            }
        }).done(function( ret ) {	
            $('#load-screen').hide();
            if(ret.status){
                $('#load-screen').hide();
                swal({
                    title:"Success!",
                    text: "Update Successful",
                    type: "success",
                    showCancelButton: false,
                    confirmButtonText: "OK",
                    closeOnConfirm: false,  
                    showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                    timer: <?php echo $this->config->item('timer') ?>
                });
                var full_url = "/properties/details/?id=<?php echo $property_id; ?>&tab=1";
                setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
            }
        }); 
    }
    

    function update_agency_keys(){
        agency_keys = $('#agency_keys').val();
        $('#load-screen').show();
        jQuery.ajax({
            type: "POST",
            url: "/properties/ajax_update_property",
            dataType: 'json',
            data: {
                property_id: <?php echo $property_id; ?>,
                agency_id: <?php echo $row['agency_id']; ?>,
                agency_addresses_id: agency_keys,
                property_update: 'update_agency_keys'
                
            }
        }).done(function( ret ) {	
            $('#load-screen').hide();
            if(ret.status){
                $('#load-screen').hide();
                swal({
                    title:"Success!",
                    text: "Update Successful",
                    type: "success",
                    showCancelButton: false,
                    confirmButtonText: "OK",
                    closeOnConfirm: false,  
                    showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                    timer: <?php echo $this->config->item('timer') ?>
                });
                var full_url = "/properties/details/?id=<?php echo $property_id; ?>&tab=1";
                setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
            }
        }); 
    }

    function update_tt_boxes(name, value, log_details){
        
        var checkbox = document.getElementById(value);
        var final_value = checkbox.checked ? 1 : 0;
        
        $('#load-screen').show();
        jQuery.ajax({
            type: "POST",
            url: "/properties/ajax_update_property",
            dataType: 'json',
            data: {
                property_id: <?php echo $property_id; ?>,
                field_name: name,
                final_value: final_value,
                log_details: log_details,
                property_update: 'update_tt_boxes'
                
            }
        }).done(function( ret ) {	
            if(ret.status){
                if (final_value == 1) {
                    $('#label-'+value).css('font-weight', '');
                } else {
                    $('#label-'+value).css('font-weight', '500');
                }
                $('#load-screen').hide();
            }
        }); 
    }

    function update_landlord(){

        var landlord_firstname = $('#landlord_firstname').val();
        var landlord_lastname = $('#landlord_lastname').val();
        var landlord_mob = $('#landlord_mob').val();
        var landlord_ph = $('#landlord_ph').val();
        var landlord_email = $('#landlord_email').val();
        $('#load-screen').show();
        jQuery.ajax({
            type: "POST",
            url: "/properties/ajax_update_property",
            dataType: 'json',
            data: {
                property_id: <?php echo $property_id; ?>,
                landlord_firstname: landlord_firstname,
                landlord_lastname: landlord_lastname,
                landlord_mob: landlord_mob,
                landlord_ph: landlord_ph,
                landlord_email: landlord_email,
                property_update: 'update_landlord'
                
            }
        }).done(function( ret ) {	
            $('#load-screen').hide();
            if(ret.status){
                $('#load-screen').hide();
                swal({
                    title:"Success!",
                    text: "Update Successful",
                    type: "success",
                    showCancelButton: false,
                    confirmButtonText: "OK",
                    closeOnConfirm: false,  
                    showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                    timer: <?php echo $this->config->item('timer') ?>
                });
                var full_url = "/properties/details/?id=<?php echo $property_id; ?>&tab=1";
                setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
            }
        }); 
    }

    // call ajax for delete property
	jQuery("#btn_delete_permanently").click(function(){
		var delete_reason = jQuery("#delete_reason").val();

		if (delete_reason != '') {
			// invoice payment check
            $('#load-screen').show();
			jQuery.ajax({
				type: "POST",
				// url: "ajax_invoice_payment_check.php",
				url: "/properties/ajax_update_property",
				data: {
					property_id: <?php echo $property_id; ?>,
                    property_update: 'check_invoice_payment'
				}
			}).done(function (ret) {

				var inv_pay_count = parseInt(ret);

				if( inv_pay_count > 0 ){
					alert("This property cannot be deleted as it has a job with an attached payment.")
				}else{

					if(confirm("Are you sure you want to continue?")==true){

						jQuery.ajax({
							type: "POST",
							// url: "ajax_delete_property_permanently.php",
							url: "/properties/ajax_update_property",
							data: {
								property_id: <?php echo $property_id; ?>,
								delete_reason: delete_reason,
                                property_update: 'delete_property_permanently'
							}
						}).done(function(ret2){
							$('#load-screen').hide();
                            if(ret.status){
                                $('#load-screen').hide();
                                swal({
                                    title:"Success!",
                                    text: "Update Successful",
                                    type: "success",
                                    showCancelButton: false,
                                    confirmButtonText: "OK",
                                    closeOnConfirm: false,  
                                    showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                                    timer: <?php echo $this->config->item('timer') ?>
                                });
                                var full_url = "/properties/details/?id=<?php echo $property_id; ?>&tab=1";
                                setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                            }
						});

					}

				}


			});
		} else {
			alert('Please select reason!');
		}




	});

    function update_subscription_source(){
        subscription_date = $("#subscription_date").val();
        subscription_source = $("#subscription_source").val();
        var dateParts = subscription_date.split("/");
        var sub_date = dateParts[2] + "-" + dateParts[1] + "-" + dateParts[0];

        og_subscription_date = $("#og_subscription_date").val();
        og_subscription_source = $("#og_subscription_source").val();
        var og_dateParts = og_subscription_date.split("/");
        var og_sub_date = og_dateParts[2] + "-" + og_dateParts[1] + "-" + og_dateParts[0];

        if (subscription_source == '') {
            country = <?=$this->config->item('country') ?>;
            // set it to unknown source if subscription_source is empty
            if (country == 1) {
                source = 6; // Unknown in AU
            } else {
                source = 2; // Unknown in NZ
            }
        } else {
            source = subscription_source;
        }
        $('#load-screen').show();
        // call ajax
        jQuery.ajax({
            type: "POST",
            url: "/properties/ajax_update_property",
            dataType: 'json',
            data: {
                property_id: <?php echo $property_id;  ?>,
                subscription_date: sub_date,
                subscription_source: source,
                og_sub_date: og_sub_date,
                og_subscription_source: og_subscription_source,
                property_update: 'update_subscription_source'
            }
        }).done(function(ret){
            $('#load-screen').hide();
                if(ret.status){
                $('#load-screen').hide();
                swal({
                    title:"Success!",
                    text: "Successfully Added",
                    type: "success",
                    showCancelButton: false,
                    confirmButtonText: "OK",
                    closeOnConfirm: false,  
                    showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                    timer: <?php echo $this->config->item('timer') ?>
                });
                var full_url = "/properties/details/?id=<?php echo $property_id; ?>&tab=1";
                setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
            }
        });
    }

</script>