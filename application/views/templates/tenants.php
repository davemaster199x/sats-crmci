<div id="tenants_ajax_container">

<style>
    #tenants_ajax_container .tabs-section-nav{
        overflow: unset;
    }
   .tenant_section a.del{
        margin-right:3px;
   }
   .inactive_tenants_menu a.active.show{
        color:#fa424a!important;
   }
</style>
   <?php 

    if($load_data === FALSE):
        $api_name = $this->api_model->apiName($api_tpe_id);
        echo "<b>Active {$api_name} API - Click to Reveal Tenants<b> <button class='btn btn-sm right' id='btn_request_to_load_data_tenant'>Show Tenants</button>";
   
    else:


    /**
     * CRM and API tenants check
     * If false (mismatched) then show popup/warning
     */
    
    if(!empty($mismatchedwarningText)): 
   ?>
    <div class="alert alert-danger alert-fill alert-close alert-dismissible fade show" role="alert">
       <?=$mismatchedwarningText?>
    </div>
    <?php endif; ?>


    <div class="tabs-section-nav tabs-section-nav-icons">
        <div class="tbl">
            <ul class="nav" role="tablist" id="tenants-tab">
                <li class="nav-item active_tenants_menu">
                    <a class="nav-link active <?php echo $this->config->item('theme') === "sats" ? 'sats_color' : 'sas_color'; ?>" href="#tenant_tab1" role="tab" data-toggle="tab">
                        Active
                    </a>
                </li>
                <li class="nav-item inactive_tenants_menu">
                    <a class="nav-link" href="#tenant_tab2" role="tab" data-toggle="tab">
                        Inactive
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade active show" id="tenant_tab1">

            <div class="table-responsive">
                <table class="table table-hover tenant_table">
                    <thead>
                        <tr style="background:#f6f8fa;">
                            <th>Primary</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Mobile</th>
                            <th>Landline</th>
                            <th>Email</th>
                            <th class="tbl-last-col" style="width: 110px;">Action</th>
                            <th>CRM</th>

                            <?php
                              if($enableApi===true)
                              {
                            ?>
                                <th><?php echo $connTextApi ?></th>
                            <?php
                              }
                            ?>

                            <th>Last Updated</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>


                        <?php
                        if(!empty($active_tenants)){ ?>
                        <?php

                            $crm_tenants_arr = [];
                            $count = 1;
                            $labe_count = 1;

                            foreach($active_tenants->result() as $active_tenants_row){

                                $crm_tenant_full_name = trim("{$active_tenants_row->tenant_firstname} {$active_tenants_row->tenant_lastname}");

                                //tenant mobile and landline removed any space for comparison purpose
                                $crm_tenant_mobile_trim = str_replace(' ','', trim($active_tenants_row->tenant_mobile));
                                $crm_tenant_landline_trim = str_replace(' ','', trim($active_tenants_row->tenant_landline));

                                ##CRM and API comaparison
                                //if($controlerApi=='console'){ //console comparison
                                if($agency_api == 5){ //console comparison

                                    $phone_match = false;
                                    $email_match = false;

                                    foreach($api_tenants_arr as $console_tenants_row){

                                        foreach($console_tenants_row['phone'] as $console_tenants_phone_row){
                                            if($active_tenants_row->tenant_mobile == $console_tenants_phone_row['number'] || $active_tenants_row->tenant_landline == $console_tenants_phone_row['number']){
                                                $phone_match = true;
                                            }
                                        }

                                        foreach($console_tenants_row['email'] as $console_tenants_email_row){
                                            if($active_tenants_row->tenant_email == $console_tenants_email_row['email']){
                                                $email_match = true;
                                            }
                                        }

                                    }


                                    if (
                                        array_search("{$active_tenants_row->tenant_firstname}", array_column($api_tenants_arr, 'fname')) !== FALSE &&
                                        array_search("{$active_tenants_row->tenant_lastname}", array_column($api_tenants_arr, 'lname')) !== FALSE &&
                                        $phone_match == true
                                        )
                                    {
                                        $tenant_exist_in_crm = 1;
                                    }else{
                                        $tenant_exist_in_crm = 0;
                                    }

                                }else{ //other API's comparison

                                    if (
                                        array_search("{$active_tenants_row->tenant_firstname}", array_column($api_tenants_arr, 'fname')) !== FALSE &&
                                        array_search("{$active_tenants_row->tenant_lastname}", array_column($api_tenants_arr, 'lname')) !== FALSE &&
                                        array_search("{$crm_tenant_mobile_trim}", array_column($api_tenants_arr, 'mobile')) !== FALSE &&
                                        array_search("{$crm_tenant_landline_trim}", array_column($api_tenants_arr, 'landline')) !== FALSE
                                        )
                                    {
                                        $tenant_exist_in_crm = 1;
                                    }else{
                                        $tenant_exist_in_crm = 0;
                                    }

                                }
                                ##CRM and API comaparison end

                        ?>
                        <tr class="tenant_row crm_tenant_row" style="position:relative;" data-tenant_exist_in_crm="<?php echo $tenant_exist_in_crm; ?>" >
                            <td>
                                <?php
                                    if($active_tenants_row->tenant_priority==1){
                                        echo "<span class='fa fa-key'></span>";
                                    }else{
                                        echo "";
                                    }
                                ?>
                            </td>
                            <td>
                                            <?php echo $active_tenants_row->tenant_firstname ?>
                                            <div style="display:none;"><input type="text" class="tenant_input form-control tenant_fname_field" name="tenant_fname" value="<?php echo $active_tenants_row->tenant_firstname ?>"></div>
                                        </td>
                                        <td>
                                            <?php echo $active_tenants_row->tenant_lastname ?>
                                            <div style="display:none;"><input type="text" class="tenant_input form-control tenant_lname_field" name="tenant_lname" value="<?php echo $active_tenants_row->tenant_lastname ?>"></div>
                                        </td>
                                        <td>
                                            <?php echo (!empty($active_tenants_row->tenant_mobile)) ? "<a href='tel:{$active_tenants_row->tenant_mobile}'>{$active_tenants_row->tenant_mobile}</a>" : '' ?>
                                            <div style="display:none;"><input type="text" class="tenant_input form-control tenant_mobile_field" name="tenant_mobile" value="<?php echo $active_tenants_row->tenant_mobile ?>"></div>
                                        </td>
                                        <td>
                                            <?php echo (!empty($active_tenants_row->tenant_landline)) ? "<a href='tel:{$active_tenants_row->tenant_landline}'>{$active_tenants_row->tenant_landline}</a>" : '' ?>
                                            <div style="display:none;"><input type="text" class="tenant_input form-control tenant_phone_field" name="tenant_landline" value="<?php echo $active_tenants_row->tenant_landline ?>"></div>
                                        </td>
                                        <td>
                                            <?php echo $active_tenants_row->tenant_email ?>
                                            <div style="display:none;"><input type="text" class="tenant_input form-control tenant_email_field" name="tenant_email" value="<?php echo $active_tenants_row->tenant_email ?>"></div>
                                        </td>
                                        <td class="tbl-last-col">
                                            <a data-fancybox data-src="#tenant_fancy_box_<?php echo $active_tenants_row->property_tenant_id; ?>" class="del edit_tenant" data-tenant_id="<?php echo $active_tenants_row->property_tenant_id ?>" href="#" data-toggle="tooltip" title="Edit"><span class="font-icon font-icon-pencil"></span></a>
                                            <a data-prop_id="<?php echo $active_tenants_row->property_id?>" data-tenant_id="<?php echo $active_tenants_row->property_tenant_id ?>" class="del deactivate_tenant" data-toggle="tooltip" title="Remove" href="#"><span class="font-icon font-icon-trash"></span></a>


                                            <?php
                                                ##envelop an sms icon
                                                if($active_tenants_row->tenant_email!=""){
                                                    echo "<a class='del' target='_blank' href='/email/send/?job_id={$job_id}&tenant_id=".$active_tenants_row->property_tenant_id."'><span class='fa fa-envelope text-green'></span></a>";
                                                }else{
                                                    echo "<span class='fa fa-envelope text-grey' style='margin-right: 3px;'></span>";
                                                }
                                                ##envelop an sms icon end

                                            if($job_id!=""){
                                                ##sms icon
                                                if( $this->sms_model->checkSmsforToday($job_id) ){
                                                    if( $active_tenants_row->tenant_mobile!="" ){
                                                        echo "<a target='_blank' class='text-green' href='/sms/send/?job_id={$job_id}&tenant_id=".$active_tenants_row->property_tenant_id."'><span class='fa fa-commenting text-green'></span></a>";
                                                    }else{
                                                        echo "<span class='fa fa-commenting text-grey'></span>";
                                                    }
                                                }
                                                ##sms icon end
                                            }
                                            ?>

                                        </td>
                                        <td><span class="fa fa-check-circle text-green"></span></td>

                                        <?php
                                            if($enableApi===true){
                                                
                                                if($tenant_exist_in_crm==1){
                                                    $api_check_cross_icon = 'check';
                                                    $api_check_cross_text_color ='text-green';
                                                }else{
                                                    $api_check_cross_icon = 'times';
                                                    $api_check_cross_text_color = 'text-red';
                                                }
                                                
                                        ?>
                                        <td>
                                            <span class="fa fa-<?=$api_check_cross_icon?>-circle <?=$api_check_cross_text_color?>"></span>
                                        </td>
                                        <?php } ?>

                                        <td>
                                            <?php

                                                if( $this->system_model->isDateNotEmpty($active_tenants_row->modifiedDate) ){
                                                    echo $this->system_model->formatDate($active_tenants_row->modifiedDate,'d/m/Y H:i');
                                                }else{
                                                    echo $this->system_model->formatDate($active_tenants_row->createdDate,'d/m/Y H:i');
                                                }

                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="checkbox">
                                                <input type="checkbox" name="chk_[]" id="check-<?=$count++?>" value='<?php echo $active_tenants_row->property_tenant_id ?>' onchange="toggleButton(this)">
                                                <label for="check-<?=$labe_count++?>">&nbsp;</label>
                                            </div>
                                        </td>

                        </tr>
                        <tr id="tenant_fancy_box_<?php echo $active_tenants_row->property_tenant_id; ?>" class="edit_tenant_field_box" style="display:none;">
                            <td>
                                <section class="card card-blue-fill">
                                    <header class="card-header">Edit Tenant</header>
                                    <div class="card-block">
                                        <table class="edit_tenant_field_box table tenant_table">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Main Contact</th>
                                                    <th>First Name</th>
                                                    <th>Last Name</th>
                                                    <th>Mobile</th>
                                                    <th>Landline</th>
                                                    <th>Email</th>
                                                </tr>
                                            </thead>
                                            <tr>
                                                <td class="text-center">
                                                    <div class="checkbox" style="margin: 0;">
                                                        <input type="checkbox" class="edit_t_is_primary" id="edit_t_is_primary_<?=$active_tenants_row->property_tenant_id?>" <?php echo ($active_tenants_row->tenant_priority==1) ? 'checked' : null; ?> >
                                                        <label for="edit_t_is_primary_<?=$active_tenants_row->property_tenant_id?>">&nbsp;</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group" style="margin: 0;">
                                                        <input placeholder="First Name" class="form-control" type="text" name="edit_tenant_fname" value="<?php echo $active_tenants_row->tenant_firstname ?>">
                                                    </div>
                                                </td>
                                                <td>  <div class="form-group" style="margin: 0;"><input placeholder="Last Name" class="form-control" type="text" name="edit_tenant_lname" value="<?php echo $active_tenants_row->tenant_lastname ?>"></div></td>
                                                <td> <div class="form-group" style="margin: 0;"> <input placeholder="Mobile" class="form-control tenant_mobile" type="text" name="edit_tenant_mobile" value="<?php echo $active_tenants_row->tenant_mobile ?>"></div></td>
                                                <td>  <div class="form-group" style="margin: 0;"><input placeholder="Landline" class="form-control phone-with-code-area-mask-input" type="text" name="edit_tenant_landline" value="<?php echo $active_tenants_row->tenant_landline ?>"></div></td>
                                                <td> <div class="form-group" style="margin: 0;"> <input placeholder="Email" class="form-control" type="text" name="edit_tenant_email" value="<?php echo $active_tenants_row->tenant_email ?>"></div></td>
                                            </tr>
                                        </table>
                                        <div class="form-group text-right">
                                            <a style="margin:10px 0px 0px 0px;" data-tenant_id="<?php echo $active_tenants_row->property_tenant_id ?>"  class="update_tenant btn btn-sm" href="$">Update Tenant</a>
                                            <!-- &nbsp;&nbsp;<a data-fancybox-close class="cancel_tenant btn btn-sm btn-danger " href="#">Cancel</a>-->
                                        </div>
                                    </div>
                                </section>
                            </td>

                        </tr>

                        <?php
                                //store crm tenants into array to use between api tenant and crm tenant comparison below
                                $crm_tenants_arr[] = array(
                                    'crm_tenant_firstname' => trim($active_tenants_row->tenant_firstname),
                                    'crm_tenant_lastname' => trim($active_tenants_row->tenant_lastname),
                                    'crm_tenant_mobile' => str_replace(' ', '', trim($active_tenants_row->tenant_mobile)),
                                    'crm_tenant_landline' => str_replace(' ', '', trim($active_tenants_row->tenant_landline)),
                                    'crm_tenant_email' => trim($active_tenants_row->tenant_email)
                                );
                            }
                        ?>
                        <?php }else{ ?>
                            <tr><td colspan="6"><span class="font-icon font-icon-warning red"></span> Property Vacant or No tenants on file</td>
                                </tr>
                        <?php   } ?>

                        <?php
                        //Apply any API but NOT Console
                        if($agency_api != 5){
                            foreach($api_tenants_arr as $tenant_row){

                                $api_tenants_full_name = "";
                                $crm_tenant_full_name = "";

                                $tenant_fname = $tenant_row['fname'];
                                $tenant_lname = $tenant_row['lname'];

                                //use company name if tenant name is empty
                                if( $tenant_row['fname']=="" && $tenant_row['lname']=="" ){
                                    $tenant_fname = $tenant_row['company_name'];
                                    $tenant_lname = '';
                                }

                                if (
                                    array_search("{$tenant_fname}", array_column($crm_tenants_arr, 'crm_tenant_firstname')) !== FALSE &&
                                    array_search("{$tenant_lname}", array_column($crm_tenants_arr, 'crm_tenant_lastname')) !== FALSE &&
                                    array_search("{$tenant_row['mobile']}", array_column($crm_tenants_arr, 'crm_tenant_mobile')) !== FALSE &&
                                    array_search("{$tenant_row['landline']}", array_column($crm_tenants_arr, 'crm_tenant_landline')) !== FALSE
                                ) {
                                    $tenant_exist_in_crm = 1;
                                }else{
                                    $tenant_exist_in_crm = 0;
                                }

                                ##show api tenant only if not added/exist in CRM
                                if($prop_is_connected_to_api===true){
                                    if($tenant_exist_in_crm==0){
                        ?>
                                        <tr class="api_tenants_tr" data-tenant_exist_in_crm="<?php echo $tenant_exist_in_crm; ?>">
                                            <td>&nbsp;</td>
                                            <td>
                                                <?php echo $tenant_fname ?>
                                                <input type="hidden" class="api_tenant_fname" value="<?php echo $tenant_fname ?>">
                                            </td>
                                            <td>
                                                <?php echo $tenant_lname ?>
                                                <input type="hidden" class="api_tenant_lname" value="<?php echo $tenant_lname ?>">
                                            </td>
                                            <td>
                                                <?php echo (!empty($tenant_row['mobile'])) ? "<a href='tel:{$tenant_row['mobile']}'>{$tenant_row['mobile']}</a>" : '' ?>
                                                <input type="hidden" class="api_tenant_mobile" value="<?php echo $tenant_row['mobile'] ?>">
                                            </td>
                                            <td>
                                                <?php echo (!empty($tenant_row['landline'])) ? "<a href='tel:{$tenant_row['landline']}'>{$tenant_row['landline']}</a>" : '' ?>
                                                <input type="hidden" class="api_tenant_landline" value="<?php echo $tenant_row['landline'] ?>">
                                            </td>
                                            <td>
                                                <?php echo $tenant_row['email'] ?>
                                                <input type="hidden" class="api_tenant_email" value="<?php echo $tenant_row['email'] ?>">
                                            </td>
                                            <td><button class="btn btn-sm add_api_tenants_to_crm_btn">Add</button></td>
                                            <td><span class="fa fa-times-circle text-red"></span></td>

                                            <?php if( $enableApi===true ){ ?>
                                            <td>
                                                <span class="fa fa-check-circle text-green"></span>
                                            </td>
                                            <?php } ?>

                                            <td><?php echo (!empty($tenant_row['UpdatedOn'])) ? $this->system_model->formatDate($tenant_row['UpdatedOn'], 'd/m/Y H:i:s') : null ?></td>
                                            <td class="text-center">
                                            <div class="checkbox">
                                                <input type="checkbox" class="chkApi" name="chkApi_[]" id="chkApi_<?=$count++?>">
                                                <label for="chkApi_<?=$labe_count++?>">&nbsp;</label>
                                            </div>
                                            </td>
                                        </tr>
                        <?php
                                    }
                                }
                            }
                        }
                        ?>

                    </tbody>
                </table>

                <!-- Console Tenants -->
                <?php
                    //if($controlerApi=='console'){
                    if($agency_api == 5){

                        //if(!empty($console_tenants_arr)){
                        if(!empty($api_tenants_arr)){
                ?>
                            <h4 style="margin-top:7px;margin-left:5px;">Console Tenants</h4>
                            <table class="table table-hover tenant_table">
                                <thead>
                                    <tr>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th class="text-center">Phone</th>
                                        <th class="text-center">Email</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <?php 
                                //foreach($console_tenants_arr as $console_tenants_row){
                                foreach($api_tenants_arr as $console_tenants_row){
                                ?>
                                    <tr>
                                        <td>
                                            <?php echo $console_tenants_row['fname'] ?>
                                            <input type="hidden" class="console_tenant_fname" value="<?php echo $console_tenants_row['fname'] ?>">
                                        </td>
                                        <td>
                                            <?php echo $console_tenants_row['lname'] ?>
                                            <input type="hidden" class="console_tenant_lname" value="<?php echo $console_tenants_row['lname'] ?>">
                                        </td>
                                        <td>
                                            <table class="table table-hover tenant_table">
                                                <thead>
                                                    <tr>
                                                        <th class="j_tbl_heading">Type</th>
                                                        <th class="j_tbl_heading">Number</th>
                                                        <th class="j_tbl_heading">Primary</th>
                                                        <th class="j_tbl_heading">Select As</th>
                                                    </tr>
                                                </thead>
                                            <?php
                                            foreach( $console_tenants_row['phone'] as $console_tenants_phone ){ ?>

                                                <tr>
                                                    <td><?php echo ucwords(strtolower($console_tenants_phone['type'])); ?></td>
                                                    <td>
                                                        <?php echo (!empty($console_tenants_phone['number'])) ? "<a href='tel:{$console_tenants_phone['number']}'>{$console_tenants_phone['number']}</a>" : '' ?>
                                                        <input type="hidden" class="console_tenant_phone_number" value="<?php echo $console_tenants_phone['number']; ?>" />
                                                    </td>
                                                    <td>
                                                        <?php echo ( $console_tenants_phone['primary'] == 1 )?'<span style="color:green">Yes</span>':'<span style="color:red">No</span>'; ?>
                                                    </td>
                                                    <td>
                                                        <select class="form-control select_phone_type">
                                                            <option value="">---</option>
                                                            <option value="1">Mobile</option>
                                                            <option value="2">Landline</option>
                                                        </select>
                                                    </td>
                                                </tr>

                                            <?php
                                            }
                                            ?>
                                            </table>
                                        </td>
                                        <td>
                                            <table class="table table-hover tenant_table">
                                                <thead>
                                                    <tr>
                                                        <th class="j_tbl_heading">Type</th>
                                                        <th class="j_tbl_heading">Email</th>
                                                        <th class="j_tbl_heading">Primary</th>
                                                        <th class="j_tbl_heading">Select</th>
                                                    </tr>
                                                </thead>
                                            <?php
                                            foreach( $console_tenants_row['email'] as $console_tenants_email ){ ?>

                                                <tr>
                                                    <td><?php echo ucwords(strtolower($console_tenants_email['type'])); ?></td>
                                                    <td>
                                                        <?php echo $console_tenants_email['email']; ?>
                                                        <input type="hidden" class="console_tenant_phone_number" value="<?php echo $console_tenants_email['email']; ?>" />
                                                    </td>
                                                    <td>
                                                        <?php echo ( $console_tenants_email['primary'] == 1 )?'<span style="color:green">Yes</span>':'<span style="color:red">No</span>'; ?>
                                                    </td>
                                                    <td>
                                                        <input type="radio" class="select_email console_tenant_email" name="select_email" value="<?php echo $console_tenants_email['email']; ?>" />
                                                    </td>
                                                </tr>

                                            <?php
                                            }
                                            ?>
                                            </table>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm add_console_tenants">Save</button>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </table>
                <?php
                        }
                    }
                ?>
                <!-- Console Tenants End -->

            </div>

        </div>

        <!-- Inactive Tenants -->
        <div role="tabpanel" class="tab-pane fade" id="tenant_tab2">

            <div class="table-responsive">
                <table class="table table-hover tenant_table">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Mobile</th>
                            <th>Landline</th>
                            <th>Email</th>
                            <th>Inactive Date</th>
                            <th>Reactivate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($in_active_tenants) && $in_active_tenants){
                            foreach($in_active_tenants->result() as $in_active_tenants_row):
                        ?>
                        <tr class="tenant_row crm_tenant_row">
                            <td>
                                <?php echo $in_active_tenants_row->tenant_firstname ?>
                            </td>
                            <td>
                                <?php echo $in_active_tenants_row->tenant_lastname ?>
                            </td>
                            <td>
                                <?php echo (!empty($in_active_tenants_row->tenant_mobile)) ? "<a href='tel:{$in_active_tenants_row->tenant_mobile}'>{$in_active_tenants_row->tenant_mobile}</a>" : '' ?>
                            </td>
                            <td>
                            <?php echo (!empty($in_active_tenants_row->tenant_landline)) ? "<a href='tel:{$in_active_tenants_row->tenant_landline}'>{$in_active_tenants_row->tenant_landline}</a>" : '' ?>
                            </td>
                            <td>
                                <?php echo $in_active_tenants_row->tenant_email ?>
                            </td>
                            <td>
                                <?php

                                        if( $this->system_model->isDateNotEmpty($in_active_tenants_row->modifiedDate) ){
                                            echo $this->system_model->formatDate($in_active_tenants_row->modifiedDate,'d/m/Y H:i');
                                        }else{
                                        null;
                                        }

                                ?>
                            </td>
                            <td><a data-prop_id="<?php echo $in_active_tenants_row->property_id?>" data-tenant_id="<?php echo $in_active_tenants_row->property_tenant_id ?>" class="refresh reactivate_tenant" data-toggle="tooltip" title="Marked as active" href="#"><span class="font-icon font-icon-refresh text-green"></span></a></td>
                        </tr>

                        <?php
                                endforeach;
                            }else{
                                echo '<tr><td colspan="6"><span class="font-icon font-icon-warning red"></span> No Inactive Tenants Found</td></tr>';
                        } ?>

                    </tbody>
                </table>
            </div>

        </div>
        <!-- Inactive Tenants end -->

        <!-- ADd new tenant section -->
        <div class="d-flex">
            <div class="mr-auto">
            <?php
                $prop_source_res = $this->properties_model->get_property_source($prop_id);
                echo ($prop_source_res['company_name'] && !empty($prop_source_res['company_name'])) ? "<button style='margin-top:10px;' type='button' class='btn btn-primary btn-sm'> {$prop_source_res['company_name']}</button>" : "";
            ?>
            </div>
            <div>
            <button id="btn_api_add_multiple_tenants" type="button" class="btn btn-sm btn-inline btn-primary" style="display:none; margin-top: 10px; margin-left: 5px;" >Add Multiple Tenants</button>
            <button id="myButtonDelete" type="button" class="btn btn-sm btn-inline btn-danger" style="display:none; margin-top: 10px; margin-left: 5px;" onclick="getCheckedItems()">‘Remove Tenant</button>
            <a href="javascript:;" data-fancybox="" data-src="#new_tenant_fields_box" id="plus_new_tenant_btn" class="btn btn-sm btn-inline btn-primary" style="margin-top: 10px; margin-left: 5px;">
                ADD Tenant
            </a>
        </div>
        </div>


            <div class="new_tenant_fields_box" id="new_tenant_fields_box" style="display:none;">
                <section class="card card-blue-fill">
                    <header class="card-header">Add New Tenant</header>
                    <div class="card-block">
                        <table class="table vpd_table tenant_table">
                            <thead>
                                <tr>
                                    <th class="text-center">Main Contact</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Mobile</th>
                                    <th>Landline</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">
                                        <div class="checkbox" style="margin: 0;">
                                            <input type="checkbox" class="new_t_is_primary" name="new_t_is_primary" id="new_t_is_primary" >
                                            <label for="new_t_is_primary">&nbsp;</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group" style="margin: 0;"><input placeholder="First Name"  type="text" class="form-control new_tenant_fname" name="new_tenant_fname"></div>
                                    </td>
                                    <td>
                                        <div class="form-group" style="margin: 0;"><input placeholder="Last Name" type="text" class="form-control new_tenant_lname" name="new_tenant_lname"></div>
                                    </td>
                                    <td>
                                        <div class="form-group" style="margin: 0;"><input placeholder="Mobile"  type="text" class="form-control tenant_mobile new_tenant_mobile" name="new_tenant_mobile"></div>
                                    </td>
                                    <td>
                                        <div class="form-group" style="margin: 0;"><input placeholder="Landline" type="text" class="form-control phone-with-code-area-mask-input new_tenant_landline" name="new_tenant_landline"></div>
                                    </td>
                                    <td>
                                        <div class="form-group" style="margin: 0;"><input placeholder="Email"  type="text" class="form-control new_tenant_email" name="new_tenant_email"></div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group text-right"><button style="margin:10px 0px 0px 0px;" id="add_new_tenant_btn" type="button" class="btn btn-sm btn-inline">Add Tenant</button></div>
                    </div>
                </section>
            </div>


        <!-- ADd new tenant section end -->

    </div>

    <?php endif; ?>


<script type="text/javascript">

    function toggleButton(checkbox) {
        var button = document.getElementById('myButtonDelete');
        var checkboxes = document.querySelectorAll('input[name="chk_[]"]:checked');

        if (checkboxes.length > 0) {
            button.style.display = 'block'; // Show the button when at least one checkbox is checked
            button.textContent = checkboxes.length === 1 ? 'Remove Tenant' : 'Remove Tenants';
        } else {
            button.style.display = 'none';  // Hide the button when no checkboxes are checked
        }
    }

    function getCheckedItems() {
        var checkboxes = document.getElementsByName('chk_[]');
        var checkedItems = [];

        checkboxes.forEach(function (checkbox) {
            if (checkbox.checked) {
                checkedItems.push(checkbox.value);
            }
        });

        if (checkedItems.length > 0) {
            swal({
                title: "",
                text: checkedItems.length === 1 ? 'Remove Tenant?' : 'Remove Tenants?',
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: "btn-danger",
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes, Remove",
                cancelButtonText: "No, Cancel!",
                closeOnConfirm: false,
                closeOnCancel: true,
            }, function (isConfirm) {
                if (isConfirm) {
                    $('#load-screen').show();

                    // Loop through checkedItems and send Ajax request for each selected tenant
                    checkedItems.forEach(function (tenant_id) {
                        jQuery.ajax({
                            type: "POST",
                            url: "<?php echo base_url('/jobs/ajax_update_tenant/') ?>",
                            dataType: 'json',
                            data: {
                                action: 'deactivate',
                                prop_id: <?php echo $prop_id; ?>,
                                tenant_id: tenant_id
                            }
                        }).done(function (data) {
                            if (data.status === true) {
                                if (data.error != "") {
                                    swal('Error', data.error, 'error');
                                }
                            } else {
                                swal('Error', 'Tenant error: Please try again', 'error');
                            }
                        });
                    });

                    $('#load-screen').hide();

                    // Show success message after processing all tenants
                    swal({
                        title: "Success!",
                        text: "Tenants Removed",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonText: "OK",
                        closeOnConfirm: true,
                    }, function (isConfirm) {
                        // Additional logic after confirming success, if needed
                    });

                    location.reload();
                } else {
                    return false;
                }
            });
        } else {
            // No checkboxes selected
            console.log('No checkboxes selected.');
        }
    }

    function isEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }

    $(document).ready(function(){

        <?php
        if($this->config->item('country')==1){ ?>
            var m_requiredLength = 10;
            var mobile_err = "Mobile Format must be 0412 222 222";
            var p_requiredLength = 10;
            var landline_err = "Landline Format must be 02 2222 2222";
        <?php  }else{ ?>
            var m_requiredLength = 8;
            var mobile_err = "Mobile Format must be between 8-12 digits"
            var p_requiredLength = 9;
            var landline_err = "Landline Format must be 02 222 2222";
        <?php } ?>

        //Add Tenant
        $('#add_new_tenant_btn').on('click',function(){
            var new_t_is_primary =  ($('.new_t_is_primary').is(':checked')) ? '1' : '0' ;
            var new_tenant_fname =  $('.new_tenant_fname').val();
            var new_tenant_lname =  $('.new_tenant_lname').val();
            var new_tenant_mobile = $('.new_tenant_mobile').val();
            var new_tenant_landline = $('.new_tenant_landline').val();
            var new_tenant_email = $('.new_tenant_email').val();

            var new_tenant_mobile_trim = new_tenant_mobile.replace(/ /g, '');
            var new_tenant_landline_trim = new_tenant_landline.replace(/ /g, '');

            var err="";
            var submitcount=0;
            var node = $(this);

            if( new_tenant_fname=="" )
            {
                err+="Please Enter First Name\n";
            }

            if(new_tenant_mobile_trim.length < m_requiredLength && new_tenant_mobile_trim.length != 0)
            {
                err+=mobile_err+"\n";
            }

            if(new_tenant_landline_trim.length < p_requiredLength && new_tenant_landline_trim.length !=0 )
            {
                err+=landline_err+"\n";
            }

            if(new_tenant_email!="" && !isEmail(new_tenant_email)){
                err+="Please Enter Valid Email\n";
            }

            if(err!=""){
                swal('Error',err,'error');
                return false;
            }

            if( submitcount==0 ){

                submitcount++;

                $('#load-screen').show();
                $.ajax({
                                    type: "POST",
                                    url: "<?php echo base_url('/jobs/ajax_add_tenant') ?>",
                                    dataType: 'json',
                                    data: {
                                        prop_id: <?php echo $prop_id; ?>,
                                        new_t_is_primary: new_t_is_primary,
                                        new_tenant_fname: new_tenant_fname,
                                        new_tenant_lname: new_tenant_lname,
                                        new_tenant_mobile: new_tenant_mobile,
                                        new_tenant_landline: new_tenant_landline,
                                        new_tenant_email: new_tenant_email
                                    }
                                 }).done(function(ret){
                                    $('#load-screen').hide();
                                     if(ret.status===true){
                                        if(ret.error!=""){
                                            swal('Error',ret.error,'error');
                                        } else {
                                            swal({
                                                    title:"Success!",
                                                    text: "Tenant Added",
                                                    type: "success",
                                                    showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                                                    timer: <?php echo $this->config->item('timer') ?>

                                                },function(isConfirm){
                                                    swal.close();
                                                    $.fancybox.close();
                                                    $('.loader_wrapper_pos_rel').find('.loader_block_v2').show(); //show loader
                                                    $('.loader_wrapper_pos_rel').find('.tenants_ajax_box').load('/jobs/tenants_ajax',{prop_id:<?php echo $prop_id ?? 0 ?>, job_id:<?php echo $job_id ?? 0 ?>, request_to_load_data:<?=1?>}, function(response, status, xhr){
                                                        $('.loader_wrapper_pos_rel').find('.loader_block_v2').hide();
                                                        $('[data-toggle="tooltip"]').tooltip(); //init tooltip
                                                        phone_mobile_mask(); //init phone/mobile mask
                                                        //mobile_validation(); //init mobile validation
                                                        //phone_validation(); //init phone validation
                                                        //add_validate_tenant();
                                                    });
                                                });
                                        }
                                     }
                                 });

                return false;
            }else{
                swal('Error','Form Submission in progress..','error');
                return false;
            }

        })
        //Add Tenant End

        //Deactiavate Tenant
        $(document).on('click','.deactivate_tenant',function(e){
            e.preventDefault();
            var obj = $(this);
            var tenant_id = $(this).data('tenant_id');
                    swal({
                        title: "",
                        text: "Remove Tenant?",
                        type: "warning",
                        showCancelButton: true,
                        cancelButtonClass: "btn-danger",
                        confirmButtonClass: "btn-success",
                        confirmButtonText: "Yes, Remove",
                        cancelButtonText: "No, Cancel!",
                        closeOnConfirm: false,
                        closeOnCancel: true,
                    },
                    function(isConfirm){
                        if(isConfirm){
                            $('#load-screen').show();
                            jQuery.ajax({
                                type: "POST",
                                url: "<?php echo base_url('/jobs/ajax_update_tenant/') ?>",
                                dataType: 'json',
                                data: {
                                    action: 'deactivate',
                                    prop_id: <?php echo $prop_id; ?>,
                                    tenant_id: tenant_id
                                }
                                }).done(function(data){
                                    $('#load-screen').hide();
                                    if(data.status===true){
                                        if(data.error!=""){
                                            swal('Error',data.error,'error');
                                        } else {
                                            swal({
                                                title:"Success!",
                                                text: "Tenant Removed",
                                                type: "success",
                                                showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                                                timer: <?php echo $this->config->item('timer') ?>

                                            },function(isConfirm){
                                                swal.close();
                                                $.fancybox.close();
                                                obj.parents('.loader_wrapper_pos_rel').find('.loader_block_v2').show(); //show loader
                                                $('.loader_wrapper_pos_rel').find('.tenants_ajax_box').load('/jobs/tenants_ajax',{prop_id:<?php echo $prop_id ?? 0; ?>, job_id:<?php echo $job_id ?? 0; ?>, request_to_load_data:<?=1?>}, function(response, status, xhr){
                                                    $('.loader_wrapper_pos_rel').find('.loader_block_v2').hide(); //hide loader
                                                    $('[data-toggle="tooltip"]').tooltip(); //init tooltip
                                                    phone_mobile_mask();
                                                    //mobile_validation();
                                                    //phone_validation();
                                                    // add_validate_tenant(); //init tenant validation
                                                });
                                            });
                                        }

                                    }else{
                                        swal('Error','Tenant error: Please try again','error');
                                }
                            });
                        }
                    })
        });
        //Deactiavate Tenant End

        // reactivate tenant
        $(document).on('click','.reactivate_tenant',function(e){
            e.preventDefault();
            var obj = $(this);
            var tenant_id = $(this).data('tenant_id');
                swal({
                    title: "",
                    text: "Reactivate Tenant?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-success",
                    confirmButtonText: "Yes, Reactivate",
                    cancelButtonClass: "btn-danger",
                    cancelButtonText: "No, Cancel!",
                    closeOnConfirm: false,
                    closeOnCancel: true,
                },
                function(isConfirm){
                    if(isConfirm){
                        $('#load-screen').show();
                        jQuery.ajax({
                            type: "POST",
                            url: "<?php echo base_url('/jobs/ajax_update_tenant/') ?>",
                            dataType: 'json',
                            data: {
                                action: 'reactivate',
                                prop_id: <?php echo $prop_id ?? 0; ?>,
                                tenant_id: tenant_id
                            }
                            }).done(function(data){
                                $('#load-screen').hide();
                                if(data.status===true){
                                    if(data.error!=""){
                                        swal('Error',data.error,'error');
                                    } else {
                                        swal({
                                            title:"Success!",
                                            text: "Tenant Reactivated",
                                            type: "success",
                                            showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                                            timer: <?php echo $this->config->item('timer') ?>

                                        },function(isConfirm){
                                            swal.close();
                                            $.fancybox.close();
                                            obj.parents('.loader_wrapper_pos_rel').find('.loader_block_v2').show(); //show loader
                                            obj.parents('.loader_wrapper_pos_rel').find('.tenants_ajax_box').load('/jobs/tenants_ajax',{prop_id:<?php echo $prop_id ?? 0; ?>, job_id:<?php echo $job_id ?? 0; ?>, request_to_load_data:<?=1?>}, function(response, status, xhr){
                                                $('.loader_wrapper_pos_rel').find('.loader_block_v2').hide(); //hide loader
                                                $('[data-toggle="tooltip"]').tooltip(); //init tooltip
                                                phone_mobile_mask();
                                                //mobile_validation();
                                                //phone_validation();
                                                //add_validate_tenant(); // init tenant validation
                                            });
                                        });
                                    }


                                }else{
                                    swal('Error','Tenant error: Please try again','error');
                            }
                        });
                    }
                })
        });
        // reactivate tenant end

        //update tenant details
        $('.update_tenant').on('click', function(e){
            e.preventDefault();
            obj = $(this);
            var prop_id = <?php echo $prop_id ?? 0 ?>;
            var edit_t_is_primary = (obj.parents('.edit_tenant_field_box').find('.edit_t_is_primary').is(':checked')) ? '1' : '0' ;
            var tenant_id = $(this).data('tenant_id');
            var tenant_fname = obj.parents('.edit_tenant_field_box').find('input[name="edit_tenant_fname"]').val();
            var tenant_lname = obj.parents('.edit_tenant_field_box').find('input[name="edit_tenant_lname"]').val();
            var tenant_mobile = obj.parents('.edit_tenant_field_box').find('input[name="edit_tenant_mobile"]').val();
            var tenant_landline = obj.parents('.edit_tenant_field_box').find('input[name="edit_tenant_landline"]').val();
            var tenant_email = obj.parents('.edit_tenant_field_box').find('input[name="edit_tenant_email"]').val();
            
            swal({
                    title: "",
                    text: "Update Tenant?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-success",
                    confirmButtonText: "Yes, Update",
                    cancelButtonClass: "btn-danger",
                    cancelButtonText: "No, Cancel!",
                    closeOnConfirm: false,
                    closeOnCancel: true,
                },
                function(isConfirm_t){
                    if(isConfirm_t){
                        $('#load-screen').show();
                        jQuery.ajax({
                                type: "POST",
                                url: "<?php echo base_url('/jobs/ajax_update_tenant/') ?>",
                                dataType: 'json',
                                data: {
                                    action: 'update',
                                    prop_id: prop_id,
                                    edit_t_is_primary: edit_t_is_primary,
                                    tenant_id: tenant_id,
                                    tenant_fname: tenant_fname,
                                    tenant_lname: tenant_lname,
                                    tenant_mobile: tenant_mobile,
                                    tenant_landline: tenant_landline,
                                    tenant_email: tenant_email
                                }
                                }).done(function(data){
                                    $('#load-screen').hide();
                                    if(data.status===true){
                                        if(data.error!=""){
                                            swal('Error',data.error,'error');
                                        } else {
                                            swal({
                                                title:"Success!",
                                                text: "Tenant Updated",
                                                type: "success",
                                                showCancelButton: false,
                                                confirmButtonText: "OK",
                                                closeOnConfirm: false,

                                            },function(isConfirm){
                                                swal.close();
                                                $.fancybox.close();
                                                $('.loader_wrapper_pos_rel').find('.loader_block_v2').show(); //show loader
                                                $('.loader_wrapper_pos_rel').find('.tenants_ajax_box').load('/jobs/tenants_ajax',{prop_id:<?php echo $prop_id ?? 0; ?>, job_id:<?php echo $job_id ?? 0; ?>, request_to_load_data:<?=1?>}, function(response, status, xhr){
                                                    $('.loader_wrapper_pos_rel').find('.loader_block_v2').hide(); //hide loader
                                                    $('[data-toggle="tooltip"]').tooltip(); //init tooltip
                                                    //add_validate_tenant(); // init tenant validation
                                                    phone_mobile_mask(); //init ph/mobile mask
                                                    //mobile_validation(); //init mobile validation
                                                    //phone_validation(); //init phone validation
                                                });
                                            });
                                        }

                                    }else{
                                        swal('Error','Tenant error: Please try again','error');
                                }
                            });
                    }
                }
            );
        })
        //update tenant details end

        //add console tenants
        $('.add_console_tenants').on('click', function(e){
            e.preventDefault();
            var node = $(this);
            var row_dom = node.parents("tr:first")

            var console_tenant_fname = row_dom.find(".console_tenant_fname").val();
		    var console_tenant_lname = row_dom.find(".console_tenant_lname").val();
            var console_tenant_email = row_dom.find(".console_tenant_email:checked").val();
            var console_tenant_mobile_arr = [];
            var console_tenant_landline_arr = [];
            var err="";

            row_dom.find(".select_phone_type").each(function(){

                var pt_dom = jQuery(this);
                var pt_dom_val = pt_dom.val();
                var phone_row_dom = pt_dom.parents("tr:first");
                var console_tenant_phone_number = phone_row_dom.find(".console_tenant_phone_number").val();

                if( pt_dom_val == 1 ){ // mobile
                    console_tenant_mobile_arr.push(console_tenant_phone_number);
                }

                if( pt_dom_val == 2 ){ // landline
                    console_tenant_landline_arr.push(console_tenant_phone_number);
                }

            });

            if( console_tenant_mobile_arr.length > 1 ){
                error += "Can only select 1 mobile number per tenant\n";
            }

            if( console_tenant_landline_arr.length > 1 ){
                error += "Can only select 1 landline per tenant\n";
            }

            if(err!=""){
                swal('Error',err,'error');
                return false;
            }

            $('#load-screen').show();
            jQuery.ajax({
                type: "POST",
                url: "<?php echo base_url('/jobs/ajax_save_console_tenant/') ?>",
                dataType: 'json',
                data: {
                    prop_id: <?php echo $prop_id; ?>,
                    tenant_fname: console_tenant_fname,
                    tenant_lname: console_tenant_lname,
                    tenant_mobile: console_tenant_mobile_arr[0],
                    tenant_landline: console_tenant_landline_arr[0],
                    tenant_email: console_tenant_email
                }
            }).done(function(data){
                $('#load-screen').hide();
                if(data.status===true){
                    if(data.error!=""){
                        swal('Error',data.error,'error');
                    } else {
                        swal({
                            title:"Success!",
                            text: "Console Tenant Added",
                            type: "success",
                            showCancelButton: false,
                            confirmButtonText: "OK",
                            closeOnConfirm: false,

                        },function(isConfirm){
                            swal.close();
                            $('.loader_wrapper_pos_rel').find('.loader_block_v2').show(); //show loader
                            $('.loader_wrapper_pos_rel').find('.tenants_ajax_box').load('/jobs/tenants_ajax',{prop_id:<?php echo $prop_id ?? 0; ?>, job_id:<?php echo $job_id ?? 0; ?>, request_to_load_data:<?=1?>}, function(response, status, xhr){
                                $('.loader_wrapper_pos_rel').find('.loader_block_v2').hide(); //hide loader
                                $('[data-toggle="tooltip"]').tooltip(); //init tooltip
                                //add_validate_tenant(); // init tenant validation
                                phone_mobile_mask();
                                //mobile_validation();
                                //phone_validation();
                            });
                        });
                    }


                }else{
                    swal('Error','Tenant error: Please try again','error');
                }
            });
        })
        //add console tenants end

        //Add api tenants to crm
        $('.add_api_tenants_to_crm_btn').on('click',function(e){
            e.preventDefault();
            var node = $(this);
            var row_node = node.parents("tr:first")

            var new_tenant_fname = row_node.find(".api_tenant_fname").val();
            var new_tenant_lname = row_node.find(".api_tenant_lname").val();
            var new_tenant_mobile = row_node.find(".api_tenant_mobile").val();
            var new_tenant_landline = row_node.find(".api_tenant_landline").val();
            var new_tenant_email = row_node.find(".api_tenant_email").val();

            swal({
                title: "",
                text: "Add API Tenant to CRM?",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-success",
                cancelButtonClass: "btn-danger",
                confirmButtonText: "Yes, Add",
                cancelButtonText: "No, Cancel!",
                closeOnConfirm: false,
                closeOnCancel: true,
            },function(isConfirm){
                if(isConfirm){

                    $('#load-screen').show();
                    jQuery.ajax({
                        type: "POST",
                        url: "<?php echo base_url('/jobs/ajax_add_tenant/') ?>",
                        dataType: 'json',
                        data: {
                            prop_id: <?php echo $prop_id ?? 0; ?>,
                            new_tenant_fname: new_tenant_fname,
                            new_tenant_lname: new_tenant_lname,
                            new_tenant_mobile: new_tenant_mobile,
                            new_tenant_landline: new_tenant_landline,
                            new_tenant_email: new_tenant_email
                        }
                    }).done(function(data){
                        $('#load-screen').hide();
                        if(data.status===true){
                            if(data.error!=""){
                                swal('Error',data.error,'error');
                            } else {
                                swal({
                                    title:"Success!",
                                    text: "API Tenant Added",
                                    type: "success",
                                    showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                                    timer: <?php echo $this->config->item('timer') ?>

                                },function(isConfirm){
                                    swal.close();
                                    $('.loader_wrapper_pos_rel').find('.loader_block_v2').show(); //show loader
                                    $('.loader_wrapper_pos_rel').find('.tenants_ajax_box').load('/jobs/tenants_ajax',{prop_id:<?php echo $prop_id ?? 0; ?>, job_id:<?php echo ($job_id != '') ? $job_id : 0; ?>, request_to_load_data:<?=1?>}, function(response, status, xhr){
                                        $('.loader_wrapper_pos_rel').find('.loader_block_v2').hide(); //hide loader
                                        $('[data-toggle="tooltip"]').tooltip(); //init tooltip
                                        phone_mobile_mask(); //init phone/mobile mask
                                        //mobile_validation(); //init mobile validation
                                        //phone_validation(); //init phone validation
                                    });
                                });
                            }


                        }else{
                            swal('Error','Tenant error: Please try again','error');
                        }
                    });
                }

            });
        })
        //Add api tenants to crm end

        //Add Multiple Tenants button toggle show/hide event (this is for API Tenants)
        $('.chkApi').on('change', function(){
            var checkedBox = $('.chkApi:checked');

            if($(this).is(':checked')){
                $(this).parents('.api_tenants_tr').addClass('selected_row');
            }else{
                $(this).parents('.api_tenants_tr').removeClass('selected_row');
            }

            if(checkedBox.length > 0){
                $('#btn_api_add_multiple_tenants').show();
               
            }else{
                $('#btn_api_add_multiple_tenants').hide();
            }
        });

        //DOM add multiple api tenant event
        $('#btn_api_add_multiple_tenants').on('click', function(e){
            e.preventDefault();

            //get all checked api checkboxes
            var checkedBox = $('.chkApi:checked');

            var data = [];
            checkedBox.each(function(){

                var node = $(this);
                var row_node = node.parents("tr:first");
                var api_tenant_fname = row_node.find('.api_tenant_fname').val();
                var api_tenant_lname = row_node.find('.api_tenant_lname').val();
                var api_tenant_mobile = row_node.find('.api_tenant_mobile').val();
                var api_tenant_landline = row_node.find('.api_tenant_landline').val();
                var api_tenant_email = row_node.find('.api_tenant_email').val();
                
                data.push({
                    'api_tenant_fname': api_tenant_fname,
                    'api_tenant_lname': api_tenant_lname,
                    'api_tenant_mobile': api_tenant_mobile.replace(/ /g, ''),
                    'api_tenant_landline': api_tenant_landline.replace(/ /g, ''),
                    'api_tenant_email': api_tenant_email
                });

            });

            $('#load-screen').show();
            jQuery.ajax({
                type: "POST",
                url: "<?php echo base_url('/jobs/ajax_add_api_tenant_in_bulk/') ?>",
                dataType: 'json',
                data: {
                    prop_id: <?php echo $prop_id ?? 0; ?>,
                    tenant_data: data
                }
            }).done(function(data){
                $('#load-screen').hide();
                if(data.status===true){
                    if(data.error!=""){
                        swal('Error',data.error,'error');
                    } else {
                        swal({
                            title:"Success!",
                            text: "API Tenant Added",
                            type: "success",
                            showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                            timer: <?php echo $this->config->item('timer') ?>

                        },function(isConfirm){
                            swal.close();
                            $('.loader_wrapper_pos_rel').find('.loader_block_v2').show(); //show loader
                            $('.loader_wrapper_pos_rel').find('.tenants_ajax_box').load('/jobs/tenants_ajax',{prop_id:<?php echo $prop_id ?? 0; ?>, job_id:<?php echo ($job_id != '') ? $job_id : 0; ?>, request_to_load_data:<?=1?>}, function(response, status, xhr){
                                $('.loader_wrapper_pos_rel').find('.loader_block_v2').hide(); //hide loader
                                $('[data-toggle="tooltip"]').tooltip(); //init tooltip
                                phone_mobile_mask(); //init phone/mobile mask
                            });
                        });
                    }

                }else{
                    swal('Error','Tenant error: Please try again','error');
                }
            });


        });

        //Request to call and load api tenants 
        $('#btn_request_to_load_data_tenant').click(function(e){

            e.preventDefault();
            $('.loader_wrapper_pos_rel').find('.loader_block_v2').show(); //show loader
            $('.loader_wrapper_pos_rel').find('.tenants_ajax_box').load('/jobs/tenants_ajax',{prop_id:<?php echo $prop_id ?? 0; ?>, job_id:<?php echo ($job_id != '') ? $job_id : 0; ?>, request_to_load_data:<?=1?>}, function(response, status, xhr){
                $('.loader_wrapper_pos_rel').find('.loader_block_v2').hide(); //hide loader
                $('[data-toggle="tooltip"]').tooltip(); //init tooltip
                phone_mobile_mask(); //init phone/mobile mask
            });

        })

    });
</script>

</div>
