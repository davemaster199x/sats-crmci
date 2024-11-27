<style>
    .log_list_box table td, .log_list_box table th{
        padding:11px 30px 10px 7px;
    }
    .preferences_list_box .radio{
        margin-bottom: 0px;
    }
    div.checkbox{margin: 0px;}
   
    .ob_check_icon{
        font-size:20px;
    }
    .inline-span {
    display: inline;
    }
    .change_service_default, .add_service_default, .change_status_default{
        background-color: white;
        color: #00a8ff;
        border-color: #00a8ff;
        cursor: pointer;
        border-radius: 3px;
        border: solid 1px #00a8ff;
        font-weight: 600;
        padding: 0.375rem 0.75rem;
        line-height: 1.5;
        vertical-align: middle;
    }
    .orig_create_job_btn{
        display: none;
    }
</style>
<div class="log_list_box">

    <div class="log_listing_old text-left">
    <div class="row">
        <div class="col-md-8 columns text-left">
            <section class="card card-blue-fill">
                <header class="card-header">Property Services</header>
                <div class="card-block">
                    <table class="table main-table vad_pricing_table text-left table-no-border">
                        <thead>
                            <tr class='border-none align-left'>
                                <th >Services</th>
                                <th >Price</th>
                                <th >Service Status</th>
                                <th >Change/Add Service</th>
                                <th style='width: 15%;'>Create Job</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $has_service_to_sats = false;
                                if($services->num_rows() != 0){
                                    $i = 1;
                                    foreach( $services->result() as $ps){ 
                                        $show_service_type_row = false;

                                        if( $is_price_increase_excluded->num_rows() == 1 ){ // orig price
                                            $agency_price = $ps->price;
                                        } else {
                                            $price_var_params = array(
                                                'service_type' => $ps->ajt_id,
                                                'agency_id' => $agency_id
                                            );
                                            $price_var_arr = $this->system_model->get_agency_price_variation($price_var_params);
                                            $agency_price = $price_var_arr['price_breakdown_text']; // agency service price 
                                        }
                                        $pp_sql = $this->db->query("
                                            SELECT *
                                            FROM `property_services` AS ps
                                            LEFT JOIN `alarm_job_type` AS ajt ON ps.`alarm_job_type_id` = ajt.`id`
                                            WHERE ps.`alarm_job_type_id` = {$ps->ajt_id}
                                            AND ps.`property_id` = {$property_id}
                                            AND ajt.`active` = 1
                                        ");

                                        if($pp_sql->num_rows() > 0){
                                            $pp =$pp_sql->row();
                                            $property_services_id = $pp->property_services_id;
                                            $alarm_job_type_id = $pp->alarm_job_type_id;
                                            $serv = $pp->service;
                                            $price = $pp->price;
                                        }else{
                                            $property_services_id = '';
                                            $alarm_job_type_id = $ps->service_id;
                                            $serv = "";
                                            $price = $ps->price;
                                        }

                                        if( $is_price_increase_excluded->num_rows() == 1 ){ // orig price
                                            $job_price = $price;
                                            $final_job_price = $price;
                                        } else {
                                            $price_var_params = array(
                                                'service_type' => $alarm_job_type_id,
                                                'property_id' => $property_id
                                            );
                                            $price_var_arr = $this->system_model->get_property_price_variation($price_var_params);
                                            $job_price = $price_var_arr['dynamic_price_total']; // agency service price 
                                            $final_job_price = $price_var_arr['price_breakdown_text']; // agency service price 
                                        }

                                        // DIY(0) or Other provider(3), only if no service type serviced to SATS
                                        //Gherx: new added condition > show NR if price has changed when adding prop
                                        if( ( ( is_numeric($serv) && $serv == 0 ) || $serv == 3 || ($serv==2 && $pp->price!=$ps->price) ) && $service_to_sats_sql_str->num_rows() == 0 ){
                                            $show_service_type_row = true;
                                        }else if(  $serv == 1 ){ // service to SATS
                                            $show_service_type_row = true;
                                            $has_service_to_sats = true;
                                        }
                                        if( $show_service_type_row == true ){	
                                ?>
                                <tr>
                                    <td style="display:none;">
                                    <?php
                                        if($alarm_job_type_id==2 && $serv==1){ ?>
                                            <input type="text" id="hid_smoke_price" value="<?php echo $price; ?>" />
                                        <?php
                                        }
                                    ?>
                                    </td>
                                    <td>
                                        <?=$ps->type;?>
                                        &nbsp;&nbsp;<?=Alarm_job_type_model::icons($ps->ajt_id);?>
                                    </td>
                                    <td><?=$final_job_price;?></td>
                                    <td>	
                                        <?php 
                                            if ($serv==1) {
                                                $service_status = $this->config->item('company_name_short')." (Active Service)";
                                            } elseif($serv==0) {
                                                $service_status = "DIY (Selected)";
                                            } elseif($serv==2) {
                                                $service_status = "No Response (Selected)";
                                            } elseif($serv==3) {
                                                $service_status = "Other Provider (Selected)";
                                            }
                                        ?>
                                        <!-- Might put it again so I just comment it -->
                                        <!-- <a class="" data-auto-focus="false" data-fancybox data-src="#fancybox_service_status<?=$ps->ajt_id?>" href="javascript:;"><?=$service_status?></a> -->
                                        <label for=""><?=$service_status?></label>
                                        
                                        <div id="fancybox_service_status<?=$ps->ajt_id?>" style="display:none; padding: 30px">
                                            <section class="card card-blue-fill">
                                                <header class="card-header">
                                                    <div class="row">
                                                        <div class="col-md-9"> <span >Service Status </span> </div>
                                                    </div> 
                                            </header>
                                                <div class="card-block">
                                                    
                                                    <div id="ajax_address_div">
                                                        <div class="default_address">
                                                            <div class="row">
                                                                <div class="col-md-12 columns">
                                                                    <div class="form-group">
                                                                        <input type="radio" style="display:none;" value="1" class="serv_sats" name="service<?php echo $i; ?>" <?php echo ($serv==1)?'checked="checked"':''; ?>> <span style="color:<?php echo ($serv==1)?'black':'#cccccc'; ?>; display:none;"><?=$this->config->item('company_name_short')?></span>
                                                                        <input type="radio" value="0" class="serv_sats" name="service<?php echo $i; ?>" <?php echo ( is_numeric($serv) && $serv==0 )?'checked="checked"':''; ?>> <span style="color:<?php echo (is_numeric($serv) && $serv==0)?'black':'#cccccc'; ?>">DIY</span>
                                                                        <input type="radio" style="display:none;" value="2" class="serv_sats" name="service<?php echo $i; ?>" <?php echo ($serv==2||$serv=="")?'checked="checked"':''; ?>> <span style="color:<?php echo ($serv==2||$serv=="")?'black':'#cccccc'; ?>; display: none;">No Response</span>
                                                                        <input type="radio" value="3" id="serv_sats<?php echo $i; ?>" class="serv_sats<?php echo $i; ?>" name="service<?php echo $i; ?>" <?php echo ($serv==3)?'checked="checked"':''; ?>> <span style="color:<?php echo ($serv==3)?'black':'#cccccc'; ?>">Other Provider</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>                    
                                                </div>
                                            </section>

                                            <div class="text-right">
                                                <button class="btn btn-primmary" onclick="update_service_status(<?=$property_id?>,<?=$alarm_job_type_id?>,<?=$property_services_id?>,<?=$i?>,<?=$serv?>)">Update</button>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <button type='button' class='submitbtnImg blue-btn change_service_btn btn btn-primary' data-auto-focus="false" data-fancybox data-src="#fancybox_change_service" onclick="fancybox_change_service(<?=$alarm_job_type_id?>,<?=$property_services_id?>,<?=$price?>,<?=$serv?>)">Change/Add Service</button>
                                    </td>
                                    <?php if($serv==1){ ?>
                                        <td>
                                            <button type="button" id="jcreate_job_btn" class="btn btn-success" onclick="jcreate_job_btn(<?=$ps->ajt_id?>)">Create</button>
                                            <button type='button' id="orig_create_job_btn_<?=$ps->ajt_id?>" class='orig_create_job_btn submitbtnImg blue-btn change_service_btn btn btn-success' data-auto-focus="false" data-fancybox data-src="#fancybox_create_job" onclick="create_jobs(<?=$alarm_job_type_id?>,<?=$job_price?>,'<?=$ps->type?>')" >Create</button>
                                        </td>
                                    <?php } else {
                                        echo '<td></td>';
                                    } ?>
                                </tr>
                                <?php 
                                    }
                                    $i++;
                                }
                                }
                                ?>
                                <?php
                                if( $has_service_to_sats == false ){  ?>
                                <tr class="border-none">
                                    <td colspan="3" class="align-left">No active <?=$this->config->item('company_name_short')?> service.</td>
                                    <td colspan="2" class="align-left">
                                        <button type='button' class='submitbtnImg blue-btn btn btn-primary' id='add_new_service_btn' data-auto-focus="false" data-fancybox data-src="#fancybox_add_new_services">Add New Service</button>
                                    </td>
                                </tr>							
                                <?php
                                }
                                ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
        <div class="col-md-4 columns text-left">
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
    <section class="card card-blue-fill">
        <header class="card-header">Job History</header>
        <div class="card-block">
            <table class="table main-table table_log_listing_old table-sm" id="jobs_datatable">
            <thead>
                <tr style="font-weight: bold; background-color: #f6f8fa;">
                    <td>Date</td>
                    <td>Job Type</td>
                    <td>Service</td>
                    <td>Price</td>
                    <td>Total Price</td>
                    <td>Job Status</td>
                    <td class="text-center">Certificate/Invoice</td>

                    <?php if($agency_id_row->prop_upgraded_to_ic_sa != 1 && $agency_id_row->state == 'QLD') { ?>
                    <!-- <td align='center'>Brooks Quote</td>
                    <td align='center'><?=$this->properties_model->get_quotes_new_name(22)?> Quote</td>
                    <td align='center'>Combined Quote</td> -->
                    <td class="text-center">Quote</td>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                    $job_in_status = false;
                    if($plog_sql_str->num_rows() != 0){
                        foreach( $plog_sql_str->result_array() as $plog_row){ 
                            $job_alarms = [];
                            if (in_array($plog_row['j_service'], Alarm_job_type_model::SMOKE_ALARM_IDS)) {
                                $job_alarms = $this->alarm_functions_model->getPropertyAlarms($plog_row['jid'], 1, 0, $plog_row['j_service']);
                            }
                            //Check if the jobs contain Low Voltage Alarm Power
                            $has_low_voltage_alarm = in_array('32', array_map('trim', array_column($job_alarms, 'alarm_power_id'))) ? true : false;
                        if(

                            // condition 1
                            ( $plog_row['job_type'] == 'IC Upgrade' && $plog_row['jstatus'] == 'Merged Certificates' ) ||

                            // condition 2
                            (
                                $plog_row['prop_upgraded_to_ic_sa'] == 1 && $plog_row['jstatus'] == 'Merged Certificates'  &&
                                $plog_row['job_type'] != 'IC Upgrade' && !in_array($plog_row['j_service'], $ic_serv)
                            )

                        ){
                            $has_ic_upgrade_job =  true; // show button
                            $job_to_upgrade_to_ic_service = $plog_row['jid'];
                        }

                        // row background color
                        $row_color = ( $plog_row['assigned_tech'] == 1 || $plog_row['assigned_tech'] == 3 )?'style="background-color:#eeeeee;"':null;

                        // job type appended text
                        $job_type_append_txt = null;
                        if( $plog_row['assigned_tech'] == 1 ){ // default
                            $job_type_append_txt = '(NOT '.$this->config->item('company_name_short').')';
                        }else if( $plog_row['assigned_tech'] == 3 ){ // SATS tech
                            $job_type_append_txt = "({$this->config->item('company_name_short')} Tech)";
                        }
                         
                    ?>
                        <tr <?=$row_color?>>
                                <td><?=( $plog_row['jdate']!="" && $plog_row['jdate']!="0000-00-00" )?date("d/m/Y",strtotime($plog_row['jdate'])):'' ?></td>
                                <td>
                                    <a href="<?php echo $this->config->item('crmci_link'); ?>/jobs/details/<?=$plog_row['jid']?>"><?php echo $plog_row['job_type'].' '.(($plog_row['assigned_tech']==23)?'(Other Supplier)':'')." "?>
                                    (<?=$plog_row['jid']?>)
                                    <?=$job_type_append_txt?>
                                    </a>
                                </td>
                                <td>
                                    <!-- icon -->
                                    <?=Alarm_job_type_model::icons($plog_row['jservice']);?>
                                    <?php 
                                        $icons_str = null;
                                        // if job type is 'IC Upgrade' show IC upgrade icon
                                        if( $plog_row['job_type'] == 'IC Upgrade' ){
                                            $icons_str .= '<img src="'.base_url().'/images/icons-jobs/upgrade_colored.png" class="j_icons" />';
                                        }

                                        if( $plog_row['job_type'] == '240v Rebook' || $plog_row['is_eo'] == 1 ){
                                            $icons_str .= '<img src="'.base_url().'/images/icons-jobs/240v_colored.png" class="j_icons" />';
                                        }

                                        if( $plog_row['job_type'] == 'Fix or Replace' ){
                                            $icons_str .= '<img src="'.base_url().'/images/icons-jobs/fr_colored.png" class="j_icons" />';
                                        } 
                                        echo $icons_str;
                                    ?>
                                </td>
                                <td>$<?php echo number_format($plog_row['job_price'], 2); ?></td>
                                <td>
                                    <?php 
                                        $job_id = $plog_row['jid'];
                                        // get new alarm
                                        $alarm_tot_price = 0;
                                        $a_sql = $this->db->query("
                                            SELECT *
                                            FROM `alarm`
                                            WHERE `job_id`  = {$job_id}
                                            AND `new` = 1
                                            AND `ts_discarded` = 0
                                        ");
                                        foreach( $a_sql->result_array() as $a){ 
                                            $alarm_tot_price += $a['alarm_price'];
                                        }       
                                        
                                        $p_n_a_total = ($plog_row['job_price'] + $alarm_tot_price);
                                        $final_job_price_total = $p_n_a_total;

                                        // get job variation
                                        $jv_sql = $this->db->query("
                                        SELECT 
                                            `amount`,
                                            `type`,
                                            `reason`
                                        FROM `job_variation`
                                        WHERE `job_id` = {$job_id}                    
                                        AND `active` = 1
                                        ");
                                        $jv_row = $jv_sql->row();
            
                                        if( $jv_sql->num_rows() > 0 ){
            
                                            if( $jv_row->type == 1 ){ // discount
                                                $final_job_price_total = $p_n_a_total-$jv_row->amount;
                                                $math_operation = '-';
                                            }else{ // surcharge
                                                $final_job_price_total = $p_n_a_total+$jv_row->amount;
                                                $math_operation = '+';
                                            }
                                        }
                                    ?>
                                    <?='$'.number_format($final_job_price_total, 2)?>
                                </td>
                                <td>
                                    <?=$plog_row['jstatus']?>
                                    <?php 
                                        $thirty_days_ago = strtotime('-30 days');
                                        $jdate_timestamp = strtotime($plog_row['jdate']);
                                        if ($plog_row['jstatus'] == 'To Be Booked' || $plog_row['jstatus'] == 'Booked' || $plog_row['jstatus'] == 'Merged Certificates' || $plog_row['jstatus'] == 'Completed' || $jdate_timestamp >= $thirty_days_ago) {
                                            $job_in_status = true;
                                        }
                                    ?>
                                </td>
                                <?php if( $plog_row['jstatus']=='Completed' && $plog_row['assigned_tech']!=1 ){
                                    $encoded_job_id = rawurlencode(HashEncryption::encodeString($plog_row['jid']));
                                    $pdf_invoice_ci_link_view = "/pdf/invoices/{$encoded_job_id}";
                                    $pdf_certificate_ci_link_view = "/pdf/certificates/{$encoded_job_id}";
                                    $pdf_combine_ci_link_view = "/pdf/combined/{$encoded_job_id}";

                                    echo '<td class="text-center">
                                    <a class="" data-auto-focus="false" data-fancybox data-src="#fancybox_certificate_invoice'.$plog_row['jid'].'" href="javascript:;">View</a>
                                    <div id="fancybox_certificate_invoice'.$plog_row['jid'].'" style="display:none; min-width: 600px; padding: 30px">
                                        <section class="card card-blue-fill">
                                            <header class="card-header">
                                                <div class="row">
                                                    <div class="col-md-9" > <span >Certificate/Invoice</span> </div>
                                                </div> 
                                        </header>
                                            <div class="card-block">
                                                
                                                <div id="ajax_address_div">
                                                    <div class="default_address">
                                                        <div class="row">
                                                            <div class="col-md-12 columns">
                                                                <select name="" id="invoiceSelect" class="form-control" onchange="openSelectedLink(this);">
                                                                    <option value="">Select</option>
                                                                    <option value="" data-link="'.$pdf_invoice_ci_link_view.'">Invoice</option>';
                                                                    if ($plog_row['assigned_tech'] != 1 && $plog_row['assigned_tech'] != 2) : ?>
                                                                
                                                                        <option value="" data-link="<?=$pdf_certificate_ci_link_view ?>"><?= $has_low_voltage_alarm ? "Service Report" : "Compliance Certificate" ?></option>
                                                                        <option value="" data-link="<?= $pdf_combine_ci_link_view ?>">Combined</option>';
                                                                    <?php endif; ?>
                                                                    <?php
                                                                    echo '
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>                    
                                            </div>
                                        </section>
                                    </div>
                                    </td>
                                    ';
	                                
	                                $encoded_string = HashEncryption::encodeString($plog_row['jid']);
                                    if($agency_id_row->prop_upgraded_to_ic_sa != 1 && $agency_id_row->state == 'QLD') {

                                        $has_brooks_quote = false;
                                        $has_cavius_quote = false;

                                        //quote pdf
                                        // check if 240v RF brooks available on agency alarms
                                        $get_240v_rf_brooks_sql_str = $this->db->query("
                                        SELECT COUNT(`agency_alarm_id`) AS agen_al_count
                                        FROM `agency_alarms`
                                        WHERE `agency_id` = {$agency_id}
                                        AND `alarm_pwr_id` = 10
                                        ");
                                        $get_240v_rf_brooks_row = $get_240v_rf_brooks_sql_str->row_array();

                                        if( $get_240v_rf_brooks_row['agen_al_count'] > 0 ){
                                            // brooks pdf
                                            $pdf_quote_ci_link_view_brooks = "/pdf/quotes/{$encoded_string}/brooks";

                                            $has_brooks_quote = true;

                                        }

                                        // check if 240v RF emerald available on agency alarms
                                        $get_240vrf_emerald_sql_str = $this->db->query("
                                        SELECT COUNT(`agency_alarm_id`) AS agen_al_count
                                        FROM `agency_alarms`
                                        WHERE `agency_id` = {$agency_id}
                                        AND `alarm_pwr_id` = 22                                
                                        ");
                                        $get_240vrf_emerald_row = $get_240vrf_emerald_sql_str->row_array();
        
                                        if( $get_240vrf_emerald_row['agen_al_count'] > 0 ){
        
                                            // emerald pdf
                                            $pdf_quote_ci_link_view_emerald = "/pdf/quotes/{$encoded_string}/emerald";

                                            $has_emerald_quote = true;
        
                                        }

                                        // if( $has_brooks_quote == true && $has_cavius_quote == true && $has_emerald_quote == true && $ic_upgrade != 1 ){
                                        if( $has_brooks_quote == true && $has_emerald_quote == true && $agency_id_row->prop_upgraded_to_ic_sa != 1 ){

                                            // combined
                                            $pdf_quote_ci_link_view_combined = "/pdf/quotes/{$encoded_string}/combined";

                                            $combined_quote = true;
                                        }

                                        echo '
                                            <td class="text-center">
                                                <a class="" data-auto-focus="false" data-fancybox data-src="#fancybox_quote'.$plog_row['jid'].'" href="javascript:;">View</a>
                                                <div id="fancybox_quote'.$plog_row['jid'].'" style="display:none; min-width: 600px; padding: 30px">
                                                    <section class="card card-blue-fill">
                                                        <header class="card-header">
                                                            <div class="row">
                                                                <div class="col-md-9" > <span >Brooks/'.$this->properties_model->get_quotes_new_name(22).'/Combined Quote</span> </div>
                                                            </div> 
                                                    </header>
                                                        <div class="card-block">
                                                            
                                                            <div id="ajax_address_div">
                                                                <div class="default_address">
                                                                    <div class="row">
                                                                        <div class="col-md-12 columns">
                                                                            <select name="" id="invoiceSelect" class="form-control" onchange="openSelectedLink(this);">
                                                                                <option value="">Select</option>';
                                                                                if ($has_brooks_quote) {
                                                                                    echo '<option value="" data-link="'.$pdf_quote_ci_link_view_brooks.'">Brooks</option>';
                                                                                }
                                                                                if ($has_emerald_quote) {
                                                                                    echo '<option value="" data-link="'.$pdf_quote_ci_link_view_emerald.'">'.$this->properties_model->get_quotes_new_name(22).'</option>';
                                                                                }
                                                                                if ($combined_quote) {
                                                                                    echo '<option value="" data-link="'.$pdf_quote_ci_link_view_combined.'">Combined</option>';
                                                                                }
                                                                                echo '
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>                    
                                                        </div>
                                                    </section>
                                                </div>
                                            </td>
                                            
                                        ';
                                    } 
                                ?>
                                <?php } else{

                                echo "<td></td>";
                                if($agency_id_row->prop_upgraded_to_ic_sa != 1 && $agency_id_row->state == 'QLD') {
                                    echo "<td></td>";
                                    // echo "<td></td>";
                                    // echo "<td></td>";
                                } 
                                } ?>
                            </tr>
                    <?php 
                        }
                    }else{
                        echo "<tr class='align-left'><td colspan='100%'>No active jobs</td></tr>";
                        $no_active_jobs = 1;
                    } 
                    ?>
                </tbody>
            </table>
        </div>
    </section>
    <section class="card card-blue-fill">
        <header class="card-header">Invoice History</header>
        <div class="card-block">
            <table class="table main-table table_log_listing_old table-sm" id="invoice_datatable">
            <thead>
                <tr style="font-weight: bold; background-color: #f6f8fa;">
                    <td>Date</td>
                    <td>Invoice Number</td>
                    <td>Service</td>
                    <td>Price</td>
                    <td>Total Price</td>
                    <td>Status </td>
                    <td class="text-center">Invoice</td>
                </tr>
            </thead>
            <tbody>
                <?php
                    $job_in_status = false;
                    if($inv_his_sql->num_rows() != 0){
                        foreach( $inv_his_sql->result_array() as $plog_row){ 
                        if(

                            // condition 1
                            ( $plog_row['job_type'] == 'IC Upgrade' && $plog_row['jstatus'] == 'Merged Certificates' ) ||

                            // condition 2
                            (
                                $plog_row['prop_upgraded_to_ic_sa'] == 1 && $plog_row['jstatus'] == 'Merged Certificates'  &&
                                $plog_row['job_type'] != 'IC Upgrade' && !in_array($plog_row['j_service'], $ic_serv)
                            )

                        ){
                            $has_ic_upgrade_job =  true; // show button
                            $job_to_upgrade_to_ic_service = $plog_row['jid'];
                        }
                    ?>
                        <tr <?=($plog_row['assigned_tech']==1)?'style="background-color:#eeeeee;"':''?>>
                                <td><?=( $plog_row['jdate']!="" && $plog_row['jdate']!="0000-00-00" )?date("d/m/Y",strtotime($plog_row['jdate'])):'' ?></td>
                                <td>
                                    <?php
                                    $check_digit = $this->gherxlib->getCheckDigit(trim($plog_row['jid']));
                                    $bpay_ref_code = "{$plog_row['jid']}{$check_digit}";
                                    ?>
                                    <a href="<?php echo $this->config->item('crmci_link'); ?>/jobs/details/<?=$plog_row['jid']?>">
                                    <?=$bpay_ref_code?>
                                    </a>
                                </td>
                                <td>
                                    <!-- icon -->
                                    <?=Alarm_job_type_model::icons($plog_row['jservice']);?>
                                </td>
                                <td>$<?php echo number_format($plog_row['job_price'], 2); ?></td>
                                <td>
                                    <?php 
                                        $job_id = $plog_row['jid'];
                                        // get new alarm
                                        $alarm_tot_price = 0;
                                        $a_sql = $this->db->query("
                                            SELECT *
                                            FROM `alarm`
                                            WHERE `job_id`  = {$job_id}
                                            AND `new` = 1
                                            AND `ts_discarded` = 0
                                        ");
                                        foreach( $a_sql->result_array() as $a){ 
                                            $alarm_tot_price += $a['alarm_price'];
                                        }       
                                        
                                        $p_n_a_total = ($plog_row['job_price'] + $alarm_tot_price);
                                        $final_job_price_total = $p_n_a_total;

                                        // get job variation
                                        $jv_sql = $this->db->query("
                                        SELECT 
                                            `amount`,
                                            `type`,
                                            `reason`
                                        FROM `job_variation`
                                        WHERE `job_id` = {$job_id}                    
                                        AND `active` = 1
                                        ");
                                        $jv_row = $jv_sql->row();
            
                                        if( $jv_sql->num_rows() > 0 ){
            
                                            if( $jv_row->type == 1 ){ // discount
                                                $final_job_price_total = $p_n_a_total-$jv_row->amount;
                                                $math_operation = '-';
                                            }else{ // surcharge
                                                $final_job_price_total = $p_n_a_total+$jv_row->amount;
                                                $math_operation = '+';
                                            }
                                        }
                                    ?>
                                    <?='$'.number_format($final_job_price_total, 2)?>
                                </td>
                                <td>
                                    <?=$plog_row['jstatus']?>
                                    <?php 
                                        $thirty_days_ago = strtotime('-30 days');
                                        $jdate_timestamp = strtotime($plog_row['jdate']);
                                        if ($plog_row['jstatus'] == 'To Be Booked' || $plog_row['jstatus'] == 'Booked' || $plog_row['jstatus'] == 'Merged Certificates' || $plog_row['jstatus'] == 'Completed' || $jdate_timestamp >= $thirty_days_ago) {
                                            $job_in_status = true;
                                        }
                                    ?>
                                </td>
                                <?php if( $plog_row['jstatus']=='Completed' && $plog_row['assigned_tech']!=1 ){
                                    $pdf_invoice_ci_link_view = "/pdf/invoices/{$plog_row['jid']}";
                                    $pdf_certificate_ci_link_view = "/pdf/certificates/{$plog_row['jid']}";
                                    $pdf_combine_ci_link_view = "/pdf/combined/{$plog_row['jid']}";

                                    echo '<td class="text-center">
                                    <a class="" data-auto-focus="false" data-fancybox data-src="#fancybox_certificate_invoice'.$plog_row['jid'].'" href="javascript:;">View</a>
                                    <div id="fancybox_certificate_invoice'.$plog_row['jid'].'" style="display:none; min-width: 600px; padding: 30px">
                                        <section class="card card-blue-fill">
                                            <header class="card-header">
                                                <div class="row">
                                                    <div class="col-md-9" > <span >Invoice</span> </div>
                                                </div> 
                                        </header>
                                            <div class="card-block">
                                                
                                                <div id="ajax_address_div">
                                                    <div class="default_address">
                                                        <div class="row">
                                                            <div class="col-md-12 columns">
                                                                <select name="" class="form-control" onchange="view_history_invoice(this.value)"> 
                                                                        <option value="">Select</option>
                                                                        <option value="'.$pdf_invoice_ci_link_view.'">Invoice </option>';
                                                                        if( $plog_row['assigned_tech']!=1 && $plog_row['assigned_tech']!=2 ){
                                                                        echo '
                                                                        <option value="" data-link="'.$pdf_certificate_ci_link_view.'">Certificate</option>
                                                                        <option value="" data-link="'.$pdf_combine_ci_link_view.'">Combined</option>';
                                                                        }
                                                                        echo '
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>                    
                                            </div>
                                        </section>
                                    </div>
                                    </td
                                    ';
                                ?>
                                <?php } else{

                                echo "<td></td>";
                                } ?>
                            </tr>
                    <?php 
                        }
                    }else{
                        echo "<tr class='align-left'><td colspan='100%'>No Invoice History</td></tr>";
                    } 
                    ?>
                </tbody>
            </table>
        </div>
    </section>
    </div>

</div>

    <div id="fancybox_change_service" style="display:none;">
        <div class="text-center">
            <button type="button" id="change_service" class="change_service_default" onclick="show_service('change_service')">Change Service</button>
            <button type="button" id="add_service" class="add_service_default" onclick="show_service('add_service')">Add Service</button>
            <button type="button" id="change_service_status" class="change_status_default" onclick="show_service('change_service_status')">Change Service Status</button>
        </div> <br>
        <div id="show_change_service" style="display: none;">
            <section class="card card-blue-fill">
                <header class="card-header">
                    <div class="row">
                        <div class="col-md-9" > <span >Change Service</span> </div>
                    </div> 
            </header>
                <div class="card-block">
                    
                    <div id="ajax_address_div">
                        <div class="default_address">
                            <div class="row">
                                <div class="col-md-12 columns">
                                    <div class="form-group">
                                    <input type="hidden" name="from_service_type" id="from_service_type" value="">
                                    <select name="to_service_type" id="to_service_type" class="form-control">
                                        <option value="">---</option>
                                        <?php foreach( $agen_serv_sql->result() as $agen_serv_row){  ?>
                                            <option value="<?php echo $agen_serv_row->ajt_id; ?>"><?php echo "{$agen_serv_row->ajt_type} - ";
                                                if( $is_price_increase_excluded->num_rows() == 1 ){ // orig price 
                                                    echo '$'.$agen_serv_row->price;
                                                } else {
                                                    $price_var_params = array(
                                                        'service_type' => $agen_serv_row->ajt_id,
                                                        'property_id' => $property_id
                                                    );
                                                    $price_var_arr = $this->system_model->get_property_price_variation($price_var_params);
                                                    echo $price_var_arr['price_breakdown_text']; // agency service price 
                                                } ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>                    
                </div>
            </section>
            <div class="text-right">
                <button class="btn btn-primmary" onclick="change_service()">Change Service</button>
            </div>
        </div>
        <div id="show_add_service" style="display: none;">
            <section class="card card-blue-fill">
                <header class="card-header">
                    <div class="row">
                        <div class="col-md-9" > <span >Add Service</span> </div>
                    </div> 
            </header>
                <div class="card-block">
                    
                    <div id="ajax_address_div">
                        <div class="default_address">
                            <div class="row">
                                <div class="col-md-12 columns">
                                    <div class="form-group">
                                    <select id="new_service_type2" class="form-control">
                                        <option value="">---</option>
                                        <?php        
                                        
                                        foreach( $agen_serv_sql->result() as $agen_serv_row){ ?>																									
                                            <option value="<?php echo $agen_serv_row->ajt_id; ?>"><?php echo "{$agen_serv_row->ajt_type} - ";
                                            if( $is_price_increase_excluded->num_rows() == 1 ){ // orig price 
                                                echo '$'.$agen_serv_row->price;
                                            } else {
                                                $price_var_params = array(
                                                    'service_type' => $agen_serv_row->ajt_id,
                                                    'property_id' => $property_id
                                                );
                                                $price_var_arr = $this->system_model->get_property_price_variation($price_var_params);
                                                echo $price_var_arr['price_breakdown_text']; // agency service price 
                                            }
                                            ?></option>													
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-12 columns">
                                    <select id="new_service_type_status2" style="margin-bottom:5px; width: 200px; margin-left: -15px" class="form-control">
                                        <option value="">---</option>
                                        <option value="1"><?=$this->config->item('company_name_short')?></option>
                                        <option value="0">DIY</option>
                                        <option value="2">NO RESPONSE</option>
                                        <option value="3">OTHER PROVIDER</option>																				
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>                    
                </div>
            </section>

            <div class="text-right">
                <button class="btn btn-primmary" onclick="add_new_service_type_submit_btn(2)">Add New Service</button>
            </div>
        </div>

        <div id="show_change_service_status" style="display: none;">
            <section class="card card-blue-fill">
                <header class="card-header">
                    <div class="row">
                        <div class="col-md-9" > <span >Change Service Status</span> </div>
                    </div> 
            </header>
                <div class="card-block">
                    
                    <div id="ajax_address_div">
                        <div class="default_address">
                            <div class="row">
                                <div class="col-md-12 columns">
                                    <input type="hidden" name="css_alarm_job_type_id" id="css_alarm_job_type_id" value="">
                                    <input type="hidden" name="css_property_services_id" id="css_property_services_id" value="">
                                    <input type="hidden" name="css_price" id="css_price" value="">
                                    <input type="hidden" name="css_serv" id="css_serv" value="">
                                    <select id="change_service_status_value" class="form-control">
                                        <option value="">---</option>
                                        <option value="1"><?=config_item('company_name_short');?></option>
                                        <option value="0">DIY</option>
                                        <option value="2">NO RESPONSE</option>
                                        <option value="3">OTHER PROVIDER</option>																				
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>                    
                </div>
            </section>

            <div class="text-right">
                <button class="btn btn-primmary" onclick="change_service_status()">Change Service Status</button>
            </div>
        </div>
    </div>

    <div id="fancybox_create_job" style="display:none;width:548px;">
        <section class="card card-blue-fill">
            <header class="card-header">
                <div class="row">
                    <div class="col-md-9" > <span >Create Job</span> </div>
                </div> 
        </header>
            <div class="card-block">
                
                <div id="ajax_address_div">
                    <div class="default_address">
                        <div class="row">
                            <div class="col-md-12 columns">
                                <?php if($job_in_status) { ?>
                                <label for="" style="color: red;">*There is currently a job active for this property, are you sure <br> you want to continue adding an additional job?</label><br>
                                <?php } ?>
                                <div class="form-group">
                                <input type="hidden" name="cj_service_name" id="cj_service_name" value="">
                                <input type="hidden" name="cj_price" id="cj_price" value="">
                                <input type="hidden" name="cj_alarm_job_type_id" id="cj_alarm_job_type_id" value="">
                                    <select name="job_type" id="job_type" class="form-control job_type">
                                        <option value="">---</option>
                                            <?php foreach( $jt_Sql->result_array() as $jt){ ?>
                                                <option value="<?php echo $jt['job_type']; ?>"><?php echo $jt['job_type']; ?></option>
                                            <?php
                                            } ?>
                                        <option value="Other Supplier YM">Other Supplier YM</option>
                                    </select>
                                </div>
                               
                                <div class="new_ten_start" style="display:none; padding-top: 10px;">
                                    New Tenancy Starts<br />
                                    <input type="text" style="width: 140px;" data-allow-input="true" id="flatpickr" class="flatpickr new_ten_start_input form-control flatpickr-input" value=""><br />
                                </div>
                                <div class="desc_prob " style="display:none; padding-top: 10px;">
                                    Describe Problem<br />
                                    <input type="text" class="problem_input addinput no-l-m form-control" value="">
                                </div>

                                <div style="clear:both;"></div>

                                <span class="delete_tenant_span" style="display:none;">
                                    <div class="checkbox" style="margin:0; display: inline-block; vertical-align: middle;">
                                        <input type="checkbox" name="delete_tenant" class="delete_tenant" id="delete_tenant" value="1" />
                                        <label for="delete_tenant">&nbsp;</label>
                                    </div>
                                    <label id="label-check-1" style="display: inline-block; vertical-align: middle; ">Delete Tenant Details</label>
                                </span><br /><br>
                                <span class="vacant_prop_span" style="display:none;">
                                    <div class="checkbox" style="margin:0; display: inline-block; vertical-align: middle;">
                                        <input type="checkbox" name="vacant_prop" class="vacant_prop" id="vacant_prop" value="1" />
                                        <label for="vacant_prop">&nbsp;</label>
                                    </div>
                                    <label id="label-check-1" style="display: inline-block; vertical-align: middle; ">Vacant</label>
                                </span><br /><br>

                                <!-- job_vacant_dates new field start-->
                                <div class="job_vacant_dates_div" style="display: none;margin-bottom:15px;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input data-allow-input="true" class="form-control flatpickr" type="text" id="job_vacant_start_date" placeholder="Vacant from" style="width:100%">
                                        </div>
                                        <div class="col-md-6">
                                            <input data-allow-input="true" class="form-control flatpickr" type="text" id="job_vacant_end_date" placeholder="Vacant to" style="width:100%">
                                        </div>
                                    </div>
                                </div>
                                <!-- job_vacant_dates new field end  -->

                                <textarea rows="5" style="display:none;" name="workorder_notes" class="addtextarea vw-jb-tar workorder_notes form-control" placeholder="workorder notes"></textarea>

                                <!-- Work order field (by:gherx) --> <br>
                                <input style="margin-bottom:7px;display:none;" type="text" class="work_order form-control" name="work_order" placeholder="Work Order #">
                                <br/>
                                <!-- Work order field (by:gherx) end -->

                                <div class="pm_div form-group" style="display: none;">
                                    <label>Property Manager</label>
                                    <select id="property_manager" class="form-control">
                                        <option>Select Property Manager</option>
                                        <?php 
                                            foreach($pm->result_array() as $pm_row){
                                                $selected_pm = ($row['pm_id_new'] == $pm_row['agency_user_account_id']) ? 'selected' : null;
                                        ?>
                                            <option <?= $selected_pm ?> value="<?= $pm_row['agency_user_account_id'] ?>"><?php echo $pm_row['fname']." ".$pm_row['lname'] ?></option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                </div>

                                <select name="job_status" class="job_status addinput vpr-adev-sel form-control" style="display:none; width: 150px;">
                                    <option value='To Be Booked'>To Be Booked</option>
                                    <option value='Send Letters'>Send Letters</option>
                                    <option value='On Hold'>On Hold</option>
                                    <option value='Booked'>Booked</option>
                                    <option value='Pre Completion'>Pre Completion</option>
                                    <option value='Merged Certificates'>Merged Certificates</option>
                                    <option value='Completed'>Completed</option>
                                    <option value='Pending'>Pending</option>
                                    <option value='Cancelled'>Cancelled</option>
                                    <option value='Action Required'>Action Required</option>
                                    <option value='DHA'>DHA</option>
                                    <option value='To Be Invoiced'>To Be Invoiced</option>
                                    <option style='color:red;' value='Escalate'>Escalate **</option>
                                    <option style='color:red;' value='Allocate'>Allocate **</option>
                                </select><br />

                                <div class="onhold_date_div" style="display:none;">
                                    <div class="" style="padding-top: 10px;">
                                        Start Date<br />
                                        <input type="date" style="width: 140px;" data-allow-input="true" id="flatpickr" class="flatpickr onhold_start_date form-control flatpickr-input" /><br />
                                    </div>
                                    <div class="" style="padding-top: 10px;">
                                        End Date<br />
                                        <input type="date" style="width: 140px;" data-allow-input="true" id="flatpickr" class="flatpickr onhold_end_date form-control flatpickr-input" /><br />
                                    </div>
                                </div>

                                <div class="jdate_div" style="display:none;">
                                    <div class="" style="padding-top: 10px;">
                                        Job Date<br />
                                        <input type="text" style="width: 140px;" data-allow-input="true" id="flatpickr" class="flatpickr job_date form-control flatpickr-input" value="<?php echo date('d/m/Y'); ?>" /><br />
                                    </div>
                                </div>
                                <div class="jtech_div" style="display:none;">
                                    <div class="" style="padding-top: 10px;">
                                        Technician<br />
                                        <?php
                                        $jtech_sql = $this->db->query("
                                            SELECT sa.`StaffID`, sa.`FirstName`, sa.`LastName`, sa.`is_electrician`, sa.`active` AS sa_active
                                            FROM `staff_accounts` AS sa
                                            LEFT JOIN `country_access` AS ca ON sa.`StaffID` = ca.`staff_accounts_id`
                                            WHERE ca.`country_id` ={$this->config->item('country')}
                                            AND sa.`ClassID` = 6
                                            ORDER BY sa.`FirstName` ASC, sa.`LastName` ASC
                                        ");
                                        ?>
                                        <select id="jtech_sel" class="jtech_sel form-control" style="width: 150px;">
                                            <option value="">--- Select ---</option>
                                            <?php
                                            foreach( $jtech_sql->result_array() as $jtech_row){  ?>
                                                 <option value="<?php echo $jtech_row['StaffID']; ?>" <?php echo ( $jtech_row['StaffID'] == 1 )?'selected="selected"':''; ?>>
                                                 <?php
                                                    echo $jtech_row['FirstName'].' '.$jtech_row['LastName'].' '.
                                                    ( ( $jtech_row['is_electrician'] == 1 )?' [E]':null ).
                                                    ( ( $jtech_row['sa_active'] == 0 )?' (Inactive)':null );
                                                ?>
                                                </option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="preferred_alarm_div" style="display:none;">

                                    <select name="preferred_alarm_id" class="preferred_alarm_id addinput vpr-adev-sel form-control">
                                        <option value=''>--- Select Alarm Preference ---</option>	
                                        <?php																						
                                        
                                        $pref_al_count = $pref_al_sql->num_rows();

                                        if( $pref_al_count > 0 ){
                                            foreach( $pref_al_sql->result_array() as $pref_al_row){

                                                $alar_pwr_comb = $pref_al_row['alarm_make'];

                                        ?>
                                            <option value='<?php echo $pref_al_row['alarm_pwr_id']; ?>' <?php echo ( $pref_al_count == 1 )?'selected="selected"':null; ?>><?php echo $alar_pwr_comb; ?></option>	
                                        <?php
                                            }
                                        }
                                        ?>										
                                    </select>
                                    <div style=":both;"></div>
                                    <br>
                                    <div class="qld_new_leg_alarm_num_div">
                                    Total Number of alarms required to meet NEW legislation: <?php echo $row['qld_new_leg_alarm_num']; ?>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>                    
            </div>
        </section>
        <div class="pull-right">
            <button type="button" class="btn_create_job submitbtnImg colorwhite btn btn-primary" style="display:none; margin-top: 10px;">Create Repair Job</button>
        </div>

        <!-- <div class="text-right">
            <button class="btn btn-primmary" onclick="change_service()">Save</button>
        </div> -->
    </div>

    <div id="fancybox_show_non_active" style="display:none;">
        <section class="card card-blue-fill">
            <header class="card-header">
                <div class="row">
                    <div class="col-md-9" > <span >Non-Active Services</span> </div>
                </div> 
        </header>
            <div class="card-block">
                
            <table id="non_active_service_tbl" class="table">
                    <tr>
                        <th>Service</th>
                        <th>Status</th>
                    </tr>

                    <?php
                    foreach( $ps_sql->result() as $ps_row){ ?>
                        <tr>
                            <td><?php echo "{$ps_row->ajt_type} - \$";
                            $price_var_params = array(
                                'service_type' => $ps_row->ajt_id,
                                'property_id' => $property_id
                            );
                            $price_var_arr = $this->system_model->get_property_price_variation($price_var_params);
                            echo $price_var_arr['dynamic_price_total']; // property service price 
                            ?></td>
                            <td>
                                <input type="hidden" class="non_active_ps_id" value="<?php echo $ps_row->property_services_id; ?>" /> 													
                                <input type="radio"  class="non_active_service_status non_active_service_<?php echo $ps_row->property_services_id; ?>" name="non_active_service_<?php echo $ps_row->property_services_id; ?>" value="0" <?php echo ( is_numeric($ps_row->serv_status) && $ps_row->serv_status == 0 )?'checked':null; ?> />DIY													
                                <input type="radio"  class="non_active_service_status non_active_service_<?php echo $ps_row->property_services_id; ?>" name="non_active_service_<?php echo $ps_row->property_services_id; ?>" value="3" <?php echo ( $ps_row->serv_status == 3 )?'checked':null; ?> />Other Provider
                            </td>
                        </tr>

                    <?php
                    }
                    ?>									
                </table>           
            </div>
        </section>

        <div class="text-right">
            <button class="btn btn-primmary" id='non_active_service_update_btn'>Update</button>
        </div>
    </div>

    <div id="fancybox_add_new_services" style="display:none;">
        <section class="card card-blue-fill">
            <header class="card-header">
                <div class="row">
                    <div class="col-md-9" > <span >Add New Services</span> </div>
                </div> 
        </header>
            <div class="card-block">
                
                <div>
                    <select id="new_service_type3" class="form-control">
                        <option value="">---</option>
                        <?php
                        foreach( $agen_serv_sql->result() as $agen_serv_row){ ?>																									
                            <option value="<?php echo $agen_serv_row->ajt_id; ?>"><?php echo "{$agen_serv_row->ajt_type} - ";
                            if( $is_price_increase_excluded->num_rows() == 1 ){ // orig price 
                                echo '$'.$agen_serv_row->price;
                            } else {
                                $price_var_params = array(
                                    'service_type' => $agen_serv_row->ajt_id,
                                    'property_id' => $property_id
                                );
                                $price_var_arr = $this->system_model->get_property_price_variation($price_var_params);
                                echo $price_var_arr['price_breakdown_text']; // agency service price 
                            }
                            ?></option>													
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <br>
                <div>
                    <select id="new_service_type_status3" style="margin-bottom:5px; width: 200px;" class="form-control">
                        <option value="">---</option>
                        <option value="1"><?=$this->config->item('company_name_short')?></option>
                        <option value="0">DIY</option>
                        <option value="3">OTHER PROVIDER</option>																				
                    </select>
                </div>      
            </div>
        </section>

        <div class="text-right">
            <button class="btn btn-primmary" onclick="add_new_service_type_submit_btn(3)">Submit</button>
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

<script type="text/javascript">

    var property_id = "<?php echo $property_id ?? 0; ?>";

    function update_service_status(property_id, alarm_job_type_id, property_services_id, index, serv){
        const radios = document.querySelectorAll('.serv_sats'+index);
        let selectedValue = null;

        for (const radio of radios) {
            if (radio.checked) {
                selectedValue = radio.value;
                break; // Exit the loop once a checked radio is found
            }
        }

        if (selectedValue !== null) {
            $('#load-screen').show();
            jQuery.ajax({
                type: "POST",
                url: "/properties/ajax_update_property",
                dataType: 'json',
                data: {
                    property_id: property_id,
                    service: selectedValue,
                    alarm_job_type_id: alarm_job_type_id,
                    property_services_id: property_services_id,
                    serv: serv,
                    property_update: 'update_service_status'

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
                    var full_url = "/properties/details/?id=<?php echo $property_id; ?>&tab=2";
                    setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                }
            });
        } else {
            alert("Please select Service Status!");
        }
    }

    function change_service_status(){
        alarm_job_type_id = $("#css_alarm_job_type_id").val();
        property_services_id = $("#css_property_services_id").val();
        price = $("#css_price").val();
        serv = $("#css_serv").val();
        service = $("#change_service_status_value").val();

        if (service != '') {
            $('#load-screen').show();
            jQuery.ajax({
                type: "POST",
                url: "/properties/ajax_update_property",
                dataType: 'json',
                data: {
                    property_id: <?php echo $property_id; ?>,
                    service: service,
                    alarm_job_type_id: alarm_job_type_id,
                    property_services_id: property_services_id,
                    serv: serv,
                    price: price,
                    property_update: 'update_service_status'
                    
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
                    var full_url = "/properties/details/?id=<?php echo $property_id; ?>&tab=2";
                    setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                }
            }); 
        } else {
            alert("Please select Service Status!");
        }
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
                property_id: property_id,
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
                var full_url = "/properties/details/?id=<?php echo $property_id; ?>&tab=2";
                setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
            }
        }); 
    }

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
                var full_url = "/properties/details/?id=<?php echo $property_id; ?>&tab=2";
                setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
            }
        });
    }

    function show_service(name){
        $("#show_change_service").hide();
        $("#show_add_service").hide();
        $("#show_change_service_status").hide();
        $("#show_"+name).show();

        // Check if the clicked button is "change_service" and update its class
        if (name === "change_service") {
            $("#change_service").removeClass("change_service_default").addClass("btn btn-primary");
            $("#add_service").removeClass("btn btn-primary").addClass("add_service_default");
            $("#change_service_status").removeClass("btn btn-primary").addClass("change_service_default");
        } else if (name === "add_service") {
            // If "add_service" is clicked, update its class and reset "change_service" class
            $("#add_service").removeClass("add_service_default").addClass("btn btn-primary");
            $("#change_service").removeClass("btn btn-primary").addClass("change_service_default");
            $("#change_service_status").removeClass("btn btn-primary").addClass("change_service_default");
        } else if (name === "change_service_status") {
            // If "add_service" is clicked, update its class and reset "change_service" class
            $("#change_service_status").removeClass("change_status_default").addClass("btn btn-primary");
            $("#add_service").removeClass("btn btn-primary").addClass("change_service_default");
            $("#change_service").removeClass("btn btn-primary").addClass("change_service_default");
        }
    }

    function openSelectedLink(select) {
        var selectedOption = select.options[select.selectedIndex];
        var pdfLink = selectedOption.getAttribute('data-link');
        
        if (pdfLink) {
            window.open(pdfLink, '_blank');
        }
    }

    function view_history_invoice(value){
        window.open(value, '_blank');
    }
    <?php if($no_active_jobs != 1) { ?>
    $('#jobs_datatable').DataTable({
        paging: false,    // Disable pagination
        info: false,      // Disable table information display
        searching: false, // Disable searching
        columnDefs: [
            { 
                type: 'datetime-moment', 
                targets: [0], 
                render: function(data, type, full, meta) {
                    if (type === 'sort' || type === 'type') {
                        return moment(data, 'DD/MM/YYYY').format('YYYY-MM-DD');
                    }
                    return data;
                }
            }
        ],
        order: [[0, 'desc']]
    });
    <?php } ?>

    // $('#invoice_datatable').DataTable({
    //     paging: false,    // Disable pagination
    //     info: false,      // Disable table information display
    //     searching: false, // Disable searching
    //     columnDefs: [
    //         { 
    //             type: 'datetime-moment', 
    //             targets: [0], 
    //             render: function(data, type, full, meta) {
    //                 if (type === 'sort' || type === 'type') {
    //                     return moment(data, 'DD/MM/YYYY').format('YYYY-MM-DD');
    //                 }
    //                 return data;
    //             }
    //         }
    //     ]
    // });

    function fancybox_change_service(alarm_job_type_id, property_services_id, price, serv){
        $("#from_service_type").val(alarm_job_type_id);
        $("#css_alarm_job_type_id").val(alarm_job_type_id);
        $("#css_property_services_id").val(property_services_id);
        $("#css_price").val(price);
        $("#css_serv").val(serv);
        // alert(alarm_job_type_id);
    }

    function change_service(){
        from_service_type = $("#from_service_type").val();
        to_service_type = $("#to_service_type").val();

        if (confirm("Are you sure you want to update service type?")) {
            if (to_service_type > 0) {
                $('#load-screen').show();
                jQuery.ajax({
                    type: "POST",
                    url: "/properties/ajax_update_property",
                    dataType: 'json',
                    data: {
                        property_id: <?php echo $property_id; ?>,
                        agency_id: <?php echo $agency_id; ?>,
                        from_service_type: from_service_type,
                        to_service_type: to_service_type,
                        property_update: 'change_service'
                        
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
                        var full_url = "/properties/details/?id=<?php echo $property_id; ?>&tab=2";
                        setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                    }
                }); 
            } else {
                swal('','Service is required!','error');
            }
        }
    }

    function jcreate_job_btn(ajt_id){
        var prop_upgraded_to_ic_sa = parseInt('<?php echo $agency_id_row->prop_upgraded_to_ic_sa; ?>');
        var state = '<?php echo addslashes($agency_id_row->state); ?>'

        if( state == 'QLD' && prop_upgraded_to_ic_sa == 0 ){ // QLD only

            swal({
                title: "Warning!",
                text: "Property has been previously marked as Not Compliant do you wish to proceed?",
                type: "warning",						
                showCancelButton: true,
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes, Continue",
                cancelButtonClass: "btn-danger",
                cancelButtonText: "No, Cancel!",
                closeOnConfirm: true,
                showLoaderOnConfirm: true,
                closeOnCancel: true
            },
            function(isConfirm) {

                if (isConfirm) {							  
                    
                    jQuery("#orig_create_job_btn_"+ajt_id).click();						

                }

            });			

        }else{ // default, other state

            jQuery("#orig_create_job_btn_"+ajt_id).click();

        }
    }

    jQuery(document).ready(function(){

        jQuery(".job_type").change(function(){

        var btn_txt;

        jQuery(".workorder_notes").show();
        jQuery(".job_status").val('To Be Booked'); // default to most jobs
        jQuery(".preferred_alarm_div").hide();

        if(jQuery(this).val()=="Fix or Replace"){
            btn_txt = "Create Repair Job";
            jQuery(".desc_prob").show();
            jQuery(".vacant_from").hide();
            jQuery(".new_ten_start").show();
        }else if(jQuery(this).val()=="Change of Tenancy"){
            btn_txt = "Create "+jQuery(this).val()+" Job";
            jQuery(".vacant_from").show();
            jQuery(".new_ten_start").show();
            jQuery(".desc_prob").hide();
        }else if(jQuery(this).val()=="Lease Renewal"){
            btn_txt = "Create "+jQuery(this).val()+" Job";
            jQuery(".vacant_from").hide();
            jQuery(".new_ten_start").show();
            jQuery(".desc_prob").hide();
        }else if(jQuery(this).val()==""){
            jQuery(".create_job_div").hide();
        }else if(jQuery(this).val() == "Once-off"){
            btn_txt = "Create "+jQuery(this).val()+" Job";
            jQuery(".job_status").val('Send Letters');
        }else if(jQuery(this).val() == "Yearly Maintenance"){ // if YM and no job = send letters
            btn_txt = "Create "+jQuery(this).val()+" Job";
        }else if(jQuery(this).val()=="IC Upgrade"){
            btn_txt = "Create "+jQuery(this).val()+" Job";
            jQuery(".preferred_alarm_div").show();
        } else if(jQuery(this).val()=="Other Supplier YM"){
            btn_txt = "Create Yearly Maintenance Job";
            jQuery(".job_status").val("Completed").trigger("change");
        }else{
            btn_txt = "Create "+jQuery(this).val()+" Job";
            jQuery(".desc_prob").hide();
            jQuery(".vacant_from").hide();
            jQuery(".new_ten_start").hide();
        }


        jQuery(".btn_create_job").show();
        jQuery(".delete_tenant_span").show();
        jQuery(".vacant_prop_span").show();
        jQuery(".job_status").show();
        jQuery(".work_order").show();
        jQuery(".btn_create_job").html(btn_txt);
        $('.pm_div').show();

        });

        //Property Vacant > Show new fields when ticked else hide
        $("#vacant_prop").click(function(){
            var node = $(this);
            if(node.is(":checked")){
                $('.job_vacant_dates_div').slideDown();
            }else{
                $('.job_vacant_dates_div').slideUp();
            }
        })


    })

    // job status script
	jQuery(".job_status").change(function(){

        var job_status = jQuery(this);

        // 
        // on hold
        jQuery(".onhold_date_div .onhold_start_date").val('');
        jQuery(".onhold_date_div .onhold_end_date").val('');
        jQuery(".onhold_date_div").hide();

        // job date
        jQuery(".jdate_div").hide();

        // tech
        jQuery(".jtech_div").hide();

        if( job_status.val() == 'On Hold' ){
            jQuery(".onhold_date_div").show();
        }else if( job_status.val() == 'Completed' ){
            jQuery(".jdate_div").show();
            jQuery(".jtech_div").show();
        }

    });

    function create_jobs(alarm_job_type_id, price, service_name){
        // alert(`${alarm_job_type_id} ${price} ${service_name}` );
        $("#cj_service_name").val(service_name);
        $("#cj_price").val(price);
        $("#cj_alarm_job_type_id").val(alarm_job_type_id);
    }

    jQuery(".btn_create_job").click(function(){
    
		var property_id = <?php echo $property_id;  ?>;
		var alarm_job_type_id = jQuery("#cj_alarm_job_type_id").val();
		var jtype = jQuery(".job_type").val();

        if (jtype == 'Other Supplier YM') { // When job type is Other Supplier YM update it to Yearly Maintenance. Reason OSYM is only for automatic status = completed, tech = Other Suppliers
            var job_type = 'Yearly Maintenance';
        } else {
            var job_type = jtype;
        }

		var price = jQuery("#cj_price").val();
		var service_name = jQuery("#cj_service_name").val();

		var new_ten_start = jQuery(".new_ten_start_input").val();
		var problem = jQuery(".problem_input").val();
		var delete_tenant = jQuery(".delete_tenant:checked").val();
		var delete_tenant2 = (delete_tenant=="1")?1:0;
		var vacant_prop = jQuery(".vacant_prop:checked").val();
		var vacant_prop2 = (vacant_prop=="1")?1:0;
		var workorder_notes = jQuery(".workorder_notes").val();

		var job_status = jQuery(".job_status").val();
		var onhold_start_date = jQuery(".onhold_start_date").val();
		var onhold_end_date = jQuery(".onhold_end_date").val();

		var job_date = jQuery(".job_date").val();
		var jtech_sel = jQuery(".jtech_sel").val();

		var work_order = jQuery(".work_order").val();
		var preferred_alarm_id = jQuery(".preferred_alarm_id").val();
		var error = '';

        var job_vacant_start_date = jQuery("#job_vacant_start_date").val();
        var job_vacant_end_date = jQuery("#job_vacant_end_date").val();

        var property_manager = $('#property_manager').val();

		if( job_type == 'IC Upgrade' && preferred_alarm_id == '' ){
			error += "Alarm Preference is required\n";
		}

		if( error != '' ){
            swal('',error,'error');
		}else{
            $('#load-screen').show();
			// call ajax
			jQuery.ajax({
				type: "POST",
                url: '<?php echo site_url(); ?>ajax/jobs_ajax/create_jobs',
                dataType: 'json',
				data: {
					property_id: property_id,
					alarm_job_type_id: alarm_job_type_id,
					job_type: job_type,
					price: price,
					new_ten_start: new_ten_start,
					problem: problem,
					service_name: service_name,
					staff_id: <?php echo $this->session->staff_id ?? 0 ?>,
					delete_tenant : delete_tenant2,
					vacant_prop: vacant_prop2,
					agency_id: '<?php echo $agency_id ?? 0 ?>',
					workorder_notes: workorder_notes,

					job_status: job_status,
					onhold_start_date: onhold_start_date,
					onhold_end_date: onhold_end_date,

					job_date: job_date,
					jtech_sel: jtech_sel,

					work_order: work_order,
					preferred_alarm_id:preferred_alarm_id,
                    property_update: 'vpd_add_jobs',
                    job_vacant_start_date: job_vacant_start_date,
                    job_vacant_end_date: job_vacant_end_date,
                    property_manager: property_manager
				}
			}).done(function(response){
                console.log(response);
                console.log("Jobs Created!");
                if(response.job_id != ''){
                    sessionStorage.setItem('is_recreated_bundle_service', response.is_recreated_bundle_service);
                    sessionStorage.setItem('job_id', response.job_id);
                    sessionStorage.setItem('ajt_id', response.ajt_id);

                    setTimeout(
                        function(){
                            const job_id = sessionStorage.getItem('job_id');
                            const ajt_id = sessionStorage.getItem('ajt_id');
                            const is_recreated_bundle_service = sessionStorage.getItem('is_recreated_bundle_service');
                            if (is_recreated_bundle_service) {
                                jQuery.ajax({
                                    type: "POST",
                                    url: "<?php echo site_url(); ?>/jobs/ajax_recreate_bundle_services",
                                    data: {
                                        job_id: job_id,
                                        ajt_id: ajt_id
                                    }
                                }).done(function (res) {
                                    console.log("recreate bundle services!");
                                    sessionStorage.clear();
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
                                    window.location.reload();
                                });
                            } else {
                                //if is_recreated_bundle_service then we clear sessionStorage
                                $('#load-screen').hide();
                                sessionStorage.clear();
                                window.location.reload();
                            }
                        }, <?php echo $this->config->item('timer') ?>
                    );
                }
			});

		}

	});

    function update_subscription_source(){
        subscription_date = $("#subscription_date").val();
        subscription_source = $("#subscription_source").val();
        var dateParts = subscription_date.split("/");
        var sub_date = dateParts[2] + "-" + dateParts[1] + "-" + dateParts[0];

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
                var full_url = "/properties/details/?id=<?php echo $property_id; ?>&tab=2";
                setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
            }
        });
    }

    jQuery("#update_to_ic_service_btn").click(function(){

    var job_to_upgrade_to_ic_service = <?php echo ($job_to_upgrade_to_ic_service != '') ? $job_to_upgrade_to_ic_service:0; ?>;

    if( confirm("Are you sure you want to update the property to an IC service?") ){

        // call ajax
        jQuery.ajax({
            type: "POST",
            url: "/properties/ajax_update_property",
            dataType: 'json',
            data: {
                property_id: <?php echo $property_id;  ?>,
                job_to_upgrade_to_ic_service: job_to_upgrade_to_ic_service,
                property_update: 'update_to_ic_service'
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
                var full_url = "/properties/details/?id=<?php echo $property_id; ?>&tab=2";
                setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
            }
        });

    }

    });

    jQuery("#btn_create_pending").click(function(){

    var hid_smoke_price = jQuery("#hid_smoke_price").val(); 

    if(confirm("Are you sure you want to continue?")==true){
        // call ajax
        jQuery.ajax({
            type: "POST",
            url: "/properties/ajax_update_property",
            dataType: 'json',
            data: {
                property_id: <?php echo $property_id;  ?>,
                hid_smoke_price: hid_smoke_price,
                agency_id: <?php echo $agency_id; ?>,
                property_update: 'vpd_service_due_job'
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
                var full_url = "/properties/details/?id=<?php echo $property_id; ?>&tab=2";
                setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
            }
        });
    }

    });

    jQuery("#non_active_service_update_btn").click(function(){
		
		// loop through all non active services
		var non_active_ps_id_arr = [];
		var non_active_service_status_arr = [];
		jQuery(".non_active_ps_id").each(function(){

			var non_active_ps_id_dom = jQuery(this);
			var parents = non_active_ps_id_dom.parents("tr:first");
			var non_active_ps_id = non_active_ps_id_dom.val();

			var non_active_service_status = parents.find(".non_active_service_status:checked").val();

			if( non_active_ps_id > 0 ){
				non_active_ps_id_arr.push(non_active_ps_id);
				non_active_service_status_arr.push(non_active_service_status);
			}

		});	
		if( confirm("Are you sure you want to update service status of non-active services?") ){

            // call ajax
            jQuery.ajax({
                type: "POST",
                url: "/properties/ajax_update_property",
                dataType: 'json',
                data: {
                    property_id: <?php echo $property_id;  ?>,
                    non_active_ps_id_arr: non_active_ps_id_arr,
                    non_active_service_status_arr: non_active_service_status_arr,
                    property_update: 'non_active_service_update'
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
                    var full_url = "/properties/details/?id=<?php echo $property_id; ?>&tab=2";
                    setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                }
            });

		}

	});

    // jQuery("#add_new_service_type_submit_btn").click(
    function add_new_service_type_submit_btn(value){
        var new_service_type = jQuery("#new_service_type"+value).val();		
        var new_service_type_status = jQuery("#new_service_type_status"+value).val();
        var error = '';
        
        if( new_service_type == '' ){
            error += "Please Select Service Type\n";
        }

        if( new_service_type_status == '' ){
            error += "Service Type Status is required\n";
        }

        if( error != '' ){
            alert(error);
        }else{

            if( confirm("Are you sure you want to add new service type?") ){

                // call ajax
                jQuery.ajax({
                    type: "POST",
                    url: "/properties/ajax_update_property",
                    dataType: 'json',
                    data: {
                        property_id: <?php echo $property_id;  ?>,
                        agency_id: <?php echo $agency_id; ?>,
                        new_service_type: new_service_type,
                        new_service_type_status: new_service_type_status,
                        property_update: 'add_new_service_type'
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
                        var full_url = "/properties/details/?id=<?php echo $property_id; ?>&tab=2";
                        setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                    }
                });

            }

        }		
    }
    
</script>