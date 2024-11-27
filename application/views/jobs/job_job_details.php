<style>
   #add_event_fb{
    display: flex;
   }
   #add_event_fb .form-group{
        margin-right: 30px;
        margin-bottom: 0px;
   }

   .log_table tr td{
    height: 28px;
   }

   .booking_type_div .custom_tab_menu.active{
        color: #fff;;
   }

   .job_details_div .card .card-header{
        padding: 8px 10px;
   }

   .job_details_div .card .card-block{
        padding:10px 10px;
   }

   .fancybox-is-open .fancybox-bg{
    opacity:0.50!important;
   }

   .script_textbox{
    margin-bottom: 15px;
   }

</style>

<?php
    $data = $this->templatedatahandler->getData();
    extract($data);

    $user_account = $loggedInUser;
    $staff_name = $user_account->FirstName;
    $staff_last_name = $user_account->LastName;
 
?>


<!-- JOB Status warning/popup text -->
<?php 

    $this->load->view('/jobs/job_status_script_selection/script_selection');

?>
<!-- JOB status warning/popup text  end-->


<!-- ERROR POPUP !--->
<?php 

    $success_pop = [];
    $error_popup = [];
    if ($job_row['del_job'] == 1) { //deleted job
      $error_popup[] ="This job is deleted";
    }

    if ($job_row['assigned_tech'] == 1) {
        $error_popup[] ="Job Performed by Previous Supplier not " . config_item('company_name_short');
    }

    if ($row['agency_deleted']) {
        $error_popup[] ="This Property is No Longer Managed by this Agency!";
    }

    if ( $job_row['a_status'] == 'deactivated' ) {
        $error_popup[] ="Agency is deactivated: You cannot create a new job while an Agency is deactivated.";
    }

?>

    <?php if( !empty($error_popup) ){
    ?>
        <div class="alert alert-danger alert-fill alert-border-left alert-close alert-dismissible fade show text-center" role="alert">
            <?php 
               /* foreach($error_popup as $error_popup_row){
                    echo "<i class='font-icon font-icon-inline font-icon-warning'></i>";
                    echo $error_popup_row;
                }*/
                echo implode('<br/>',$error_popup)
            ?>
        </div>
    <?php
    } ?>
<!-- ERROR POPUP END !--->

<!-- success div/pop conditions -->
<?php 
if ( $this->input->get_post('pme_upload_status') ) {
    if ($this->input->get_post('pme_upload_status') == 1) {
        $success_pop[] = '<div class="success show text-center alert alert-success">'.$this->input->get_post('pme_msg').'</div>';
    }else {
        $error_popup[] = '<div class="error show text-cener alert alert-danger">This job has already been uploaded an Invoice/Bill to PMe.</div>';
    }
}

if ( $this->input->get_post('palace_upload_status') ) {
    if ($this->input->get_post('palace_upload_status') == 1) {
        $success_pop[] = '<div class="success show text-center alert alert-success">'.$this->input->get_post('palace_msg').'</div>';
    }else {
        $error_popup[] = '<div class="error show text-cener alert alert-danger">This job has already been uploaded an Invoice/Bill to Palace.</div>';
    }
}

if( $this->input->get_post['invoice_uploaded'] == 1 || $this->input->get_post['certificate_uploaded'] ) {

    $uploade_msg_arr = [];
    if( $this->input->get_post['invoice_uploaded'] == 1 ){
        $uploade_msg_arr[] = 'Invoice';
    }

    if( $this->input->get_post['certificate_uploaded'] == 1 ){
        $uploade_msg_arr[] = 'Certificate';
    }
    
    $uploade_msg_imp = implode(" and ",$uploade_msg_arr);

    $success_pop[] =  "<div class='success'>{$uploade_msg_imp} Console API Upload Successful!</div>";

}

?>
<!-- success div/pop conditions end -->

<!-- Other error popup/box -->
<?php
    if( !empty($other_err) ){
?>
     <div class="text-red" style="font-weight: bold;margin-bottom:10px;text-align:center;"><?php echo implode('<br/>', $other_err) ?></div>
<?php
    }
?>
<!-- Other error popup/box end -->

<!-- Other warning popup/box -->
<?php
    $other_warning = [];
    /*  //disabled as per Dan's request
    if( $job_row['bne_to_call']==1 ){
        
        if( $this->config->item('country')== 1 ){
            $other_warning[] = "* This job must be booked by Brisbane Call Center *";
        }else{
            $other_warning[] = "* This job must be booked by Auckland Call Center *";
        }
    }*/
?>
<?php 
    if( !empty($other_warning) ){
?>
        <div class="text-black" style="font-weight: bold;margin-bottom:10px;text-align:center;"><?php echo implode('<br/>', $other_warning) ?></div>
<?php
    }
?>
<!-- Other warning popup/box end -->

<!-- Success popup -->
<?php if( !empty($success_pop) ){
    ?>
        <div class="alert alert-success alert-fill alert-border-left alert-close alert-dismissible fade show text-center" role="alert">
            <?php 
                echo implode('<br/>',$success_pop)
            ?>
        </div>
    <?php
    } ?>


<!-- Success popup end -->

<!-- On Hold Warning -->
<?php if($job_row['j_status']=="On Hold" && ($job_row['start_date']!="" && date('Y-m-d', strtotime($job_row['start_date'])) > date('Y-m-d'))){ ?>
<div class="alert alert-warning alert-fill alert-border-left alert-close alert-dismissible fade show text-center" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
    This job should not be booked before <?php echo date('d/m/Y', strtotime($job_row['start_date'])) ?>
</div>
<?php } ?>
<!-- On Hold Warning end -->

<?php
    if( $this->jobs_model->isStrMappedFull($job_row['jid']) ){
        echo " <h5 class='text-red text-center'><b>This run is FULL please let operations know of this addition</h5></b>";
    }

    /*
    $findJobCompletedLast30Days = $this->jobs_model->findJobCompletedLast30Days($job_row['prop_id'], $job_row['jid']);
    if( $findJobCompletedLast30Days->num_rows() > 0 && $job_row['j_type'] != 'Fix or Replace' ){
        echo " <h5 class='text-red text-center'><b>Job completed in the last 30 days</h5></b>";
    }
    */

    ##Phone Call Logs----------
    $get_phone_call_from_old_joblog = $this->db->query("
        SELECT DATE_FORMAT(j.eventdate,'%d/%m/%Y') AS jl_date,
            j.contact_type,
            j.comments,
            j.log_id,
            s.FirstName,
            s.LastName,
            eventtime,
            j.`important`,
            j.`eventdate`
        FROM job_log j
        LEFT JOIN staff_accounts s ON s.StaffID = j.staff_id
        WHERE j.`job_id` = {$job_row['jid']}
        AND j.`deleted` = 0                                        
        AND j.`contact_type` = 'Phone Call'
        ORDER BY j.`eventdate` DESC
    ");
    $get_phone_call_from_old_joblog_row = $get_phone_call_from_old_joblog->row_array();

    $get_phone_call_from_newlog_params = array(
        'sel_query' => "l.log_id,l.created_date,l.title,l.details,ltit.title_name,aua.fname,aua.lname,aua.photo,sa.StaffID,sa.FirstName,sa.LastName",
        'job_id' => $job_row['jid'],
        'display_in_vjd' => 1,
        'deleted' => 0,
        'custom_where' => "l.title=93", //Phone Call
        'sort_list' => array(
            array(
                'order_by' => 'l.created_date',
                'sort' => 'DESC'
            )
        ),
        'display_query' => 0
    );
    $get_phone_call_from_newlog = $this->agency_model->getNewLogs($get_phone_call_from_newlog_params);
    $get_phone_call_from_newlog_row = $get_phone_call_from_newlog->row_array();

    $phone_call_staff_name = "";
    $phone_call_event_date = "";
    $phone_call_event_time = "";
    if( $get_phone_call_from_old_joblog->num_rows()>0 ){
        $phone_call_staff_name = "{$get_phone_call_from_old_joblog_row['FirstName']} {$get_phone_call_from_old_joblog_row['LastName']}";
        $phone_call_event_date = $get_phone_call_from_old_joblog_row['eventdate'];
        $phone_call_event_time = $get_phone_call_from_old_joblog_row['eventtime'];
    }else if( $get_phone_call_from_newlog->num_rows()>0 ){
        $phone_call_staff_name = "{$get_phone_call_from_newlog_row['FirstName']} {$get_phone_call_from_newlog_row['LastName']}";
        $phone_call_event_date = date('Y-m-d', strtotime($get_phone_call_from_newlog_row['created_date']));
        $phone_call_event_time = date('H:i', strtotime($get_phone_call_from_newlog_row['created_date']));
    }
    
    // huming agency only allows 2 days interval phone call
    if( $this->config->item('country') == 1 ){
        $hume_house_agency_id = 1598; // Hume Housing                                                                                   
        $day_interval = date("Y-m-d", strtotime("{$phone_call_event_date} +3 days"));
    } 

    if (
        (
            ( $get_phone_call_from_old_joblog->num_rows()>0 || $get_phone_call_from_newlog->num_rows()>0 ) &&
            $job_row['j_status'] == "To Be Booked" &&
            (
                ( $job_row['a_id'] == $hume_house_agency_id && date('Y-m-d') < $day_interval )  ||
                ( $phone_call_event_date == date('Y-m-d') ) 
            )
        ) || $job_row['allow_en'] == 2                                     
    ) {
        $hide_tenant_details = 1;
        echo "<h6>";
        if((int) $job_row['allow_en'] === 2 && (int) $job_row['no_en'] === 0){
            echo "Agency requested tenant not be called, but only entry noticed. Do not call these tenant. <br>If a tenant calls asking why we booked this, please advise them the agency has requested an entry notice be sent and keys collected. <br>If they have any issues please contact their agency.<br>";
        }else{ // default
            //if ((int) $job_row['no_en'] === 0) {
                echo "{$phone_call_staff_name} called on " . (date("d/m/Y", strtotime($phone_call_event_date))) . " @ {$phone_call_event_time}";
            //}
        }
        //if ((int) $job_row['allow_en'] === 2 && (int) $job_row['no_en'] === 0) {
            echo "<button id='btn_show_tenant' class='btn btn-sm' type='button'>Show</button></h6>";
        //}
    }
    ##Phone Call Logs end----------

?>


<div class="row">    

    <div class="col-md-8">
        <section class="card card-blue-fill">
            <header class="card-header">Agency Details</header>
            <div class="card-block">
                <div class="row">
                    <div class="col-md-2">
                        <h4>Agency</h4>
                        <a class="<?php echo ($job_row['a_priority']>0) ? "txt-bold" : null ?>" target="_blank" href="/agency/view_agency_details/<?php echo $job_row['a_id'] ?>"><?php echo $job_row['agency_name']; ?> <?php echo ($job_row['a_priority']>0) ? "(".$job_row['abbreviation'].")" :NULL ?></a>
                        <?php 
                        if( $job_row['allow_upfront_billing'] == 1 ){
                            echo '<span style="color:#0082c6" data-toggle="tooltip" title="Subscription Billing Customer" class="fa fa-dollar"><span>';
                        }
                        ?>
                    </div>
                    <div class="col-md-2">
                        <h4>Agency Number</h4>
                        <?php
                        $phone_number = $job_row['a_phone'];
                        $link_text = ($phone_number != "") ? $phone_number : 'No Data';
                        $href_attr = ($phone_number != "") ? 'tel:' . $phone_number : '#';
                        ?>
                        <a href="<?php echo $href_attr; ?>" class="call-button"><?php echo $link_text; ?></a>
                    </div>
                    <div class="col-md-3">
                        <h4>Agency Comments</h4>
                        <a href="#" data-fancybox data-src="#agency_specific_notes_fancybox"><?php echo ($job_row['agency_comments']!="")?$job_row['agency_comments']:'No Data'; ?></a>
                    </div>
                    <div class="col-md-2">
                            <h4>Agency Hours</h4>
                            <a href="#" data-fancybox data-src="#agency_specific_notes_fancybox"><?php echo ($job_row['agency_hours']!="") ? $job_row['agency_hours'] : 'No Data'; ?></a>
                    </div>
                    <div class="col-md-2">
                            <h4>Agency Specific Notes</h4>
                            <a data-fancybox data-src="#agency_specific_notes_fancybox" href="#"><?php echo ($job_row['agency_specific_notes']!="")?$job_row['agency_specific_notes']:"No Data"; ?></a>
                    </div>
                </div>
                <!-- Edit Agency Details Fancybox -->
                <div id="agency_specific_notes_fancybox" style="display: none;width:565px;">

                    <?php echo form_open('/jobs/ajax_update_job_detail', array('id'=>'agency_specific_notes_form','class'=>'update_job_detail_form')); ?>
                    <section class="card card-blue-fill">
                        <header class="card-header">Edit Agency Details</header>
                        <div class="card-block">
                            <div class="form-group">
                                <label>Agency Comments</label>
                                <textarea id="agency_comments" class="form-control"><?php echo $job_row['agency_comments'] ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Agency Hours</label>
                                <input id="agency_hours" class="form-control" value="<?php echo $job_row['agency_hours'] ?>">
                            </div>
                            <div class="form-group">
                                <label>Agency Specific Notes</label>
                                <textarea rows="3" id="agency_specific_notes" name="agency_specific_notes" class="form-control"><?php echo $job_row['agency_specific_notes']; ?></textarea>
                                <textarea  style="display:none;" rows="3" id="orig_agency_specific_notes" name="orig_agency_specific_notes" class="form-control"><?php echo $job_row['agency_specific_notes']; ?></textarea>
                            </div>
                        </div>
                    </section>
                    <div class="form-group text-right">
                        <?php if( $can_edit_completed_job==true ){ ?>
                            <button id="btn_update_agency_specific_notes" class="btn">Update</button>
                        <?php }else{
                            echo "<span class='text-red'>Completed Jobs can't be Edited</span>";
                        } ?>
                    </div>
                    <?php echo form_close(); ?>

            </div>
            </div>
        </section>
    </div>

</div>

<hr style="margin-top: 0px;margin-bottom:22px;">

<div class="row">

    <div class="col-md-7 tenant_details_div" style="<?php echo ($hide_tenant_details==1) ? 'display:none;' : ''; ?>">
        <?php 
            if($job_row['bne_to_call']==1){

                $card_colour_tenant_det = 'card-red-fill';
                
                if($this->config->item('country')==1){
                    $card_tenant_det_bne_text = " - Australian Call Centre to Call ONLY !";
                }else{
                    $card_tenant_det_bne_text = " - Auckland Call Centre to Call ONLY !";
                }

            }else{

                $card_colour_tenant_det = 'card-blue-fill';
                $card_tenant_det_bne_text = "";

            }
        ?>
        <section class="card <?php echo $card_colour_tenant_det; ?>">
            
            <header class="card-header">Tenant Details <?php echo $card_tenant_det_bne_text; ?></header>

            <div class="card-block">

                <!-- tenants tab -->
                <section class="tabs-section loader_wrapper_pos_rel tenant_section" style="margin-bottom:0px;">

                    <div class="loader_block_v2" style="display: none;"> <div id="div_loader"></div></div>

                    <div class="tenants_ajax_box"></div>

                </section>
                <!-- tenants tab end -->

            </div>
        </section>

    </div>


    <div class="col-md-5">

        <section class="card card-blue-fill">
            <header class="card-header">Available Days</header>
            <div class="card-block">
                <?php 
                    if( $job_row['j_status']!="Merged Certificates" && $job_row['j_status']!="Completed" ){

                        if ( $this->system_model->isDateNotEmpty($job_row['j_date']) ) {
                            $str_date = $job_row['j_date'];
                        } else if ( ( $this->system_model->isDateNotEmpty($job_row['j_date']) ) && $_GET['tr_date'] != "") {
                            // tech run date
                            $str_date = $this->input->get_post('tr_date');
                        } else {
                            // current date
                            $str_date = date('Y-m-d');
                        }

                        $future_str_q = $this->jobs_model->fetch_future_str($job_row['jid']);

                        if( $future_str_q->num_rows() > 0 ){
                ?>    
                    
                        <table class="future_str_table table table-hover main-table ">

                            <?php 
                                $ctr = 0;
                                foreach( $future_str_q->result_array() as $other_str ){ 
                                    
                                    $hiddenText = "";
                                    $showRow = 1;
                                    $show_hidden = $other_str['show_hidden'];
                                    $date = $other_str['tr_date'];
                                    $isElectrician = ( $other_str['is_electrician'] == 1 ) ? true : false;

                                    // only show 240v rebook to electrician
                                    if ( ( $other_str['job_type'] == '240v Rebook' || $other_str['is_eo'] == 1 ) && $isElectrician == false) {
                                        $hiddenText .= '240v<br />';
                                        $showRow = 0;
                                    } else {
                                        $showRow = 1;
                                    }

                                    if ($other_str['hidden'] == 1) {
                                        $hiddenText .= 'User<br />';
                                    }

                                    if ($other_str['unavailable'] == 1 && $other_str['unavailable_date'] == $date) {
                                        $isUnavailable = 1;
                                        $hiddenText .= 'Unavailable<br />';
                                    }

                                    $startDate = date('Y-m-d', strtotime($other_str['start_date']));

                                    if ($other_str['job_type'] == 'Lease Renewal' && ( $other_str['start_date'] != "" && $date < $startDate )) {
                                        $hiddenText .= 'LR<br />';
                                    }

                                    if ($other_str['job_type'] == 'Change of Tenancy' && ( $other_str['start_date'] != "" && $date < $startDate )) {
                                        $hiddenText .= 'COT<br />';
                                    }

                                    if ($other_str['j_status'] == 'DHA' && ( $other_str['start_date'] != "" && $date < $startDate )) {
                                        $hiddenText .= 'DHA<br />';
                                    }

                                    if ($other_str['j_status'] == 'On Hold' && ( $other_str['start_date'] != "" && $date < $startDate )) {
                                        $hiddenText .= 'On Hold<br />';
                                    }

                                    if( $show_hidden==0 && $hiddenText!="" ){
                                        $showRow = 0;
                                    }else{
                                        $showRow = 1;
                                    }

                                    if($showRow==1){

                                        if( $other_str['ready_to_book'] ==1 ){
                                            
                            ?>

                                    <tr>
                                        <td style="border-right: 1px solid #dee2e6;">
                                            <input type="hidden" class="tr_id" value="<?php echo $other_str['tech_run_id']; ?>" />
                                            <div style="display:none;"><?php echo "Unavailable: {$other_str['unavailable']} - {$other_str['unavailable_date']} Hidden Text: {$hiddenText}"; ?></div>

                                            <a href="/tech_run/set/?tr_id=<?php echo $other_str['tech_run_id']; ?>">
                                                <?php echo date('l d/m', strtotime($other_str['tr_date'])); ?>
                                            </a>
                                        </td>
                                        <td>
                                            <?php echo $this->system_model->formatStaffName($other_str['FirstName'], $other_str['LastName']); ?>
                                            <span>
                                                <?php
                                                $colour_tbl_sql = $this->jobs_model->getColourTableStatus($other_str['tech_run_id'], $other_str['highlight_color']);
                                                $colour_tbl = $colour_tbl_sql->row_array();

                                                    if ( $colour_tbl['time'] != '' || empty($colour_tbl_sql) ) {

                                                        $status_dif_txt = '';
                                                        $ct_booking_status = $colour_tbl['booking_status'];

                                                        if ($ct_booking_status != '') {

                                                            if ($ct_booking_status == 'FULL') {
                                                                $status_dif_txt = "<span style='color:red;'>(FULL)</span>";
                                                            } else {
                                                                $status_dif_txt = "({$ct_booking_status})";
                                                            }
                                                        }

                                                        if ($colour_tbl['no_keys'] == 1) {
                                                            $no_keys_txt = " <span style='color:red;'>NO KEYS</span>";
                                                        } else {
                                                            $no_keys_txt = "";
                                                        }

                                                        echo "({$colour_tbl['time']}{$no_keys_txt}) {$status_dif_txt}";
                                                    } else {
                                                        echo "(No Time Set)";
                                                    }

                                                ?>
                                            </span>
                                        </td>
                                    </tr>

                            <?php      
                                        $ctr++;
                                        }
                                    }   
                                } 
                            ?>
                            
                        </table>

                <?php
                        if ($ctr == 0) {
                            echo '<div>Sorry there are no other available days scheduled in your area at the moment</div>';
                        }

                        }else{
                            echo "Sorry there are no other available days scheduled in your area at the moment";
                        }
                    }
                ?>
            </div>
        </section>

    </div>

</div>

<hr style="margin-top: 0px;margin-bottom:22px;">

<div class="row job_det_fields">

    <div class="col-md-6">

        <div class="row">

            <div class="col-md-6">

                <section class="card card-blue-fill"> 
                    <header class="card-header">Job Details</header>   
                    <div class="card-block">
                        <div class="form-group form-flex">
                            <?php 
                            $job_icons_params = array(
                                'job_id' => $job_row['jid']
                            );
                            ?>
                            <label>Job Type <br/><small><?php  echo $this->system_model->display_job_icons($job_icons_params) ?></small> <?php echo ($job_row['holiday_rental']==1) ? "<small class='text-red'><strong>(SHORT TERM RENTAL)</strong></small>" : NULL ?></label>
                            <a data-src="#job_details_fancybox" data-fancybox href=""><?php echo $job_row['j_type'] ?></a>
                        </div>

                        <div class="form-group form-flex">
                            <label>Job Created</label>
                            <?php 
                            if ($job_row['j_status'] == 'Completed') {

                                // Age
                                $date1 = date_create(date('Y-m-d', strtotime($job_row['j_created'])));
                                $date2 = date_create($job_row['j_date']);
                                $diff = date_diff($date1, $date2);
                                $age = $diff->format("%r%a");
                                $age2 = (((int) $age) != 0) ? $age : 0;
                            } else {

                                // Age
                                $date1 = date_create(date('Y-m-d', strtotime($job_row['j_created'])));
                                $date2 = date_create(date('Y-m-d'));
                                $diff = date_diff($date1, $date2);
                                $age = $diff->format("%r%a");
                                $age2 = (((int) $age) != 0) ? $age : 0;
                            }

                            $day_text = ($age2 > 1) ? 'days' : 'day';
                            $age_text = ($age2 > 0) ? " - {$age2} {$day_text} old" : '';

                            $created_date = ($this->system_model->isDateNotEmpty($job_row['j_created'])) ? $this->system_model->formatDate($job_row['j_created'],'d/m/Y') : '';
                            echo "<div class='form-flex-2'>".$created_date.$age_text."</div>";
                            ?>
                        </div>

                        <div class="form-group form-flex">
                            <label>Job Number</label>
                            <?php echo "<div class='form-flex-2'>".$job_row['jid']."</div>" ?>
                        </div>
                        
                        <div class="form-group form-flex">
                            <label style="<?php echo ($job_row['urgent_job']==1) ? 'color:#fa424a' : null ?>">Urgent/ Outside of Scope</label>
                            <a data-fancybox="" data-src="#job_details_fancybox" href="#"> 
                                <?php
                                echo ($job_row['urgent_job']==1) ? 'Yes' : 'No'; 
                                echo ($job_row['urgent_job_reason']!="") ? " - {$job_row['urgent_job_reason']}" : null;
                                ?>
                            </a>
                        </div>

                        <div class="form-group form-flex">
                            <label>Lockbox Code</label>
                            <a data-fancybox="" data-src="#job_details_fancybox" href="#"> <?php echo ($job_row['code']!="") ? $job_row['code'] : 'No Data' ?></a>
                        </div>

                        <div class="form-group form-flex">
                            <label>Start Date/End Date</label>
                            <?php 
                            $start_date = ($this->system_model->isDateNotEmpty($job_row['start_date'])) ? $this->system_model->formatDate($job_row['start_date'],'d/m/Y') : '';
                            $due_date = ($this->system_model->isDateNotEmpty($job_row['due_date'])) ? $this->system_model->formatDate($job_row['due_date'],'d/m/Y') : '';

                            if( $start_date!="" || $due_date!="" ){
                                echo "<a data-fancybox='' data-src='#job_details_fancybox' href='#'>";
                                echo  "{$start_date} - {$due_date}";
                                echo "</a>";
                            }else{
                                echo "<a data-fancybox='' data-src='#job_details_fancybox' href='#'>No Data</a>";
                            }
                           ?>
                        </div>
                        
                        
                        <div class="form-group form-flex">
                            <label>Property Vacant</label>
                            <a data-fancybox data-src="#job_details_fancybox" href="#"><?php echo ($job_row['property_vacant']==1) ? 'Yes' : 'No'; ?></a>
                        </div>
                
                        <div class="form-group form-flex">
                            <label>Electrician Only </label>
                            <a data-fancybox data-src="#job_details_fancybox" href="#"><?php echo ($job_row['is_eo']==1) ? 'Yes' : 'No'; ?></a>
                        </div>

                        <div class="form-group form-flex">
                            <label>Key Number:</label>
                            <a data-src="#job_details_fancybox" data-fancybox href=""><?php echo ($job_row['key_number']!="") ? $job_row['key_number']: 'No Data' ?></a>
                        </div>
                        
                        <div class="form-group form-flex">
                            <label>Needs Processing DHA | Tapi | etc</label>
                            <a href="#" data-src="#job_details_fancybox" data-fancybox>
                                <?php echo ($job_row['dha_need_processing']=="" || $job_row['dha_need_processing']==0 ) ? 'No' : 'Yes'; ?>
                            </a>
                        </div>

                        <div class="form-group form-flex">
                            <label>Approved Alarms</label>
                            <?php
                                $preferred_alarm_name = "";
                                if( $job_row['preferred_alarm_id']==10 ){
                                    $preferred_alarm_name = "Brooks";
                                }elseif( $job_row['preferred_alarm_id']==14 ){
                                    $preferred_alarm_name = "Cavius";
                                }elseif( $job_row['preferred_alarm_id']==22 ){
                                    $preferred_alarm_name = "Emerald";
                                }else{
                                    $preferred_alarm_name = "No Data";
                                }
                            ?>
                            <a data-fancybox data-src="#job_details_fancybox" href="#"> <?php echo ($job_row['preferred_alarm_id']!="") ? $preferred_alarm_name : "No Data" ?></a>
                        </div>

                        <div class="form-group form-flex">
                            <label>House Alarm Code</label>
                            <a data-fancybox data-src="#job_details_fancybox" href="#"> <?php echo ($job_row['alarm_code']!="") ? $job_row['alarm_code'] : "No Data" ?></a>
                        </div>

                        <?php if($job_row['p_state']=='QLD'){ ?>
                        <div class="form-group form-flex">
                            <label>Property Upgraded (QLD)</label>
                            <a data-src="#prop_upgraded_fb" data-fancybox href=""><?php echo ($job_row['prop_upgraded_to_ic_sa']==1) ? 'Yes' : "No" ?></a>
                        </div>
                        <div style="display:none;" id="prop_upgraded_fb">
                            <section class="card card-blue-fill" style="margin-bottom:12px;">
                                <header class="card-header">Property Upgraded (QLD)</header>
                                <div class="card-block">
                                    <div class="form-group">
                                        <select class="prop_upgraded_to_ic_sa form-control">
                                            <option <?php echo ($job_row['prop_upgraded_to_ic_sa'] == 1) ? 'selected' : null; ?> value="1">Yes</option>
                                            <option <?php echo ($job_row['prop_upgraded_to_ic_sa'] <=0) ? 'selected' : null; ?> value="0">No</option>
                                        </select>
                                    </div>
                                </div>
                            </section>
                            <div class="form-groupss text-right">
                                <button type="button" id="btn_prop_upgraded_to_ic_sa" class="btn">Update</button>
                            </div>
                        </div>
                        <?php } ?>

                        <div class="form-group form-flex">
                            <label>Preferred Time <small><i class="text-blue"><?php echo ($this->system_model->isDateNotEmpty($job_row['preferred_time_ts'])) ? $this->system_model->formatDate($job_row['preferred_time_ts'],'d/m/Y H:i'):null; ?></i></small></label>
                            <a data-src="#preferred_time_fb" data-fancybox href=""><?php echo ($job_row['preferred_time']!="") ? $job_row['preferred_time'] : "No Data" ?></a>

                            <div id="preferred_time_fb" style="display: none;">
                                <section class="card card-blue-fill" style="margin-bottom:12px;">
                                    <header class="card-header">Edit Preferred Time</header>
                                    <div class="card-block">
                                        <div class="form-group">
                                            <label>Preferred Time</label>
                                            <input maxlength="20" class="preferred_time preferred_time_elem form-control" value="<?php echo $job_row['preferred_time'] ?>">
                                            <input type="hidden" name="orig_preferred_time" id="orig_preferred_time" value="<?php echo $job_row['preferred_time']; ?>">
                                        </div>
                                        <div class="form-group">
                                            <div class="checkbox">
                                                <input value="1" type="checkbox" id="out_of_tech_hours" <?php echo ($job_row['out_of_tech_hours']==1)?'checked':null; ?>>
                                                <label for="out_of_tech_hours">Outside of Tech Hours (7-3)</label>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <div class="form-group text-right">
                                    <button type="button" id="btn_update_pref_time" class="btn">Update</button>
                                </div>
                            </div>
                        </div>

                        <!---------- JOB PRICE -->
                        <div class="form-group form-flex ajax_price_toogle_main_box">
                            <label class="txt-bold">Job Price  <a data-toggle='tooltip' title="Show/Hide Price Breakdown" class="toggle_job_price_breakdown" href="#"><span class="fa fa-arrow-down"></span></a></label>
                            <div class="form-flex-2">
                                <?php echo "$". number_format($this->system_model->getJobTotalAmount($job_row['jid']), 2); ?>
                            </div>
                        </div>
                        <!-- Load price details/breakdown via ajax request here -->
                        <div class="form-group ajax_load_price_detail"></div>
                        <!----------- JOB PRICE END -->

                    </div>
                </section>        

            </div> 
            
            <div class="col-md-6">
                <?php 
                    $last_30_days_text = "";
                    $findJobCompletedLast30Days = $this->jobs_model->findJobCompletedLast30Days($job_row['prop_id'], $job_row['jid']);
                    if($job_row['j_status']=='Booked' || $job_row['j_status']=='Merged Certificates')
                    {
                        $card_colour = 'card-green-fill';
                        $btn_colour = "btn-success";
                    }
                    elseif($job_row['j_status']=='Cancelled')
                    {
                        $card_colour = 'card-red-fill';
                        $btn_colour = "btn-danger";
                    }
                    elseif($findJobCompletedLast30Days->num_rows() > 0 && $job_row['j_type'] != 'Fix or Replace')
                    {
                        $card_colour = 'card-red-fill';
                        $btn_colour = "btn-danger";
                        $last_30_days_text = '<small><strong>- Job Completed in the last 30 Days</strong></small>';
                    }
                    else{
                        $card_colour = 'card-blue-fill';
                        $btn_colour = "btn-primary";
                    }
                ?>
                <section class="card <?php echo $card_colour; ?>"> 
                    <header class="card-header">Booking Details <?php echo $last_30_days_text; ?></header>   
                    <div class="card-block">   

                        <div class="form-group form-flex">
                            <?php 
                                $ha_span = "";
                                if ($job_row['prop_upgraded_to_ic_sa'] == 1) {
                                    //$ha_span = "<strong class='text-red'>PROPERTY UPGRADED</strong>";
                                    $ha_span = ""; //removed as per DK request > no longer required
                                } else if ( $this->tech_model->check_prop_first_visit($job_row['prop_id']) == true ) {
                                    $ha_span = "<strong class='text-red'>FIRST VISIT</strong>";
                                }
                            ?>
                            <label>Job Status <small><?php echo $ha_span; ?></small></label>
                            <a data-src="#job_booking_fancybox" data-fancybox href=""><?php echo $job_row['j_status'] ?></a>
                        </div>

                        <?php if($job_row['j_status']=='Escalate'){ ?>
                            <div class="form-group form-flex">
                                <label>Escalate Reason</label>
                                <a data-src="#job_booking_fancybox" data-fancybox href=""><?php echo (!empty($selected_escalate_job_reasons_row))?$selected_escalate_job_reasons_row['reason_short']:'No Data' ?></a>
                            </div>
                        <?php } ?>

                        <?php if($job_row['j_status']=='Allocate'){ ?>
                            <div class="form-group form-flex">
                                <label>Allocate Notes</label>
                                <a data-src="#allocate_fancybox" data-fancybox href=""><?php echo ($job_row['allocate_notes']!="")?$job_row['allocate_notes']:'No Data' ?></a>
                            </div>
                            <div class="form-group form-flex">
                                <label>Allocate Response</label>
                                <a data-src="#allocate_fancybox" data-fancybox href=""><?php echo ($job_row['allocate_response']!="")?$job_row['allocate_response']:'No Data' ?></a>
                            </div>
                            <div id="allocate_fancybox" style="display: none;">
                                <?php echo form_open('/jobs/ajax_update_allocate_response_or_notes', array('id'=>'update_allocate_form')); ?>
                                <section class="card card-blue-fill">
                                    <header class="card-header">Allocate Notes/Response</header>
                                    <div class="card-block">
                                        <div class="form-group">
                                            <label>Allocate Notes</label>
                                            <textarea class="form-control allocate_notes"><?php echo $job_row['allocate_notes'] ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Allocate Response</label>
                                            <textarea class="form-control allocate_response"><?php echo $job_row['allocate_response'] ?></textarea>
                                        </div>
                                    </div>
                                </section>
                                <div class="text-right">
                                    <?php if(in_array($this->session->staff_id,$allocate_personnel_arr)){
                                        echo '<button class="btn" id="allocate_notes_response_btn">Save</button>';
                                    }else{
                                        echo "Only Assigned User can respond to this page";
                                    } ?>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        <?php } ?>

                        <div class="form-group form-flex">
                            <label>Date</label>
                            <a data-src="#job_booking_fancybox" data-fancybox href=""><?php echo ($this->system_model->isDateNotEmpty($job_row['j_date'])) ? $this->system_model->formatDate($job_row['j_date'],'d/m/Y') : 'No Data'; ?></a>
                        </div>

                        <div class="form-group form-flex">
                            <label>Time of Day <?php echo ($job_row['allow_dk'] == 1 && $job_row['no_dk'] == 0) ? null : "<br/><small class='text-red'><strong>NO DKs ALLOWED</strong></small>"; ?></label>
                            <a data-src="#job_booking_fancybox" data-fancybox href=""><?php echo ($job_row['time_of_day']!="") ? $job_row['time_of_day']: "No Data"; ?></a>
                        </div>

                        <?php if( $job_row['allow_dk'] == 1 && $job_row['no_dk'] != 1 ){ ?>
                        <div class="form-group form-flex">
                            <label>Door Knock</label>
                            <a data-src="#job_booking_fancybox" data-fancybox href=""><?php echo ( $job_row['door_knock']==1) ?'Yes' : 'No Data'; ?></a>
                        </div>
                        <?php } ?>

                        <div class="form-group form-flex">
                            <label>Key Access</label>
                            <a data-src='#job_booking_fancybox' data-fancybox href=''>
                                <?php
                                    if ($job_row['key_allowed'] == 1 && $job_row['no_keys'] != 1) {
                                        echo ($job_row['key_access_required']==1) ? "Yes | Authorised By: {$job_row['key_access_details']}" : "No";
                                    }else if($job_row['no_keys'] == 1){
                                        echo "<span class='text-red'>NO KEYS</span>";
                                    }else{
                                        echo "<span class='text-red'>NO KEY ACCESS ALLOWED</span>";
                                    }
                                ?>
                            </a>

                        </div>

                        <div class="form-group form-flex">
                            <label>Lockbox Code</label>
                            <a data-src="#job_booking_fancybox" data-fancybox href=""><?php echo ( $job_row['code']!="") ? $job_row['code'] : 'No Data'; ?></a>
                        </div>

                        <div class="form-group form-flex">
                            <label>Call Before</label>
                            <a data-src="#job_booking_fancybox" data-fancybox href=""><?php echo ($job_row['call_before']==1) ? "Yes - {$job_row['call_before_txt']}" : "No" ?></a>
                        </div>

                        <div class="form-group form-flex">
                            <label>Booked With</label>
                            <a data-src="#job_booking_fancybox" data-fancybox href=""><?php echo ($job_row['booked_with']!="")?$job_row['booked_with']:'No Data' ?></a>
                        </div>

                        <div class="form-group form-flex">
                            <label>Booked By</label>
                            <a data-src="#job_booking_fancybox" data-fancybox href=""><?php echo ($booked_by=="") ? "No Data" : "{$booked_by['FirstName']} {$booked_by['LastName']}" ?></a>
                        </div>

                        <div class="form-group form-flex">
                            <label>Technician</label>
                            <a data-src="#job_booking_fancybox" data-fancybox href=""><?php echo "{$job_row['tech_fname']} {$job_row['tech_lname']}" ?></a>
                        </div>

                        <div class="form-group form-flex">
                            <label>Job Notes For Technician</label>
                            <a javascript:; data-fancybox data-src="#job_booking_fancybox" href="#"><?php echo ($job_row['j_comments']!="") ? $job_row['j_comments'] : "No Data"; ?></a>
                        </div>
                        
                        <div class="form-group form-flex">
                            <label>Run Sheet Notes</label>
                            <a data-src="#job_booking_fancybox" data-fancybox href=""><?php echo ($job_row['tech_notes']!="") ? $job_row['tech_notes'] : "No Data" ?></a>
                        </div>

                        <div class="form-group">
                            <div class="row booking-details-buttons">
                                <div class="col-md-6 flex-div booking-btns-flex-box flex-column">
                                    <button data-toggle="modal" data-target="#btn_move_to_booked_modal" id="btn_move_to_booked" class="btn btn-sm btn-warning btn_move_to_booked text-left">Send Back to Tech</button>
                                    <button id="btn_create_rebook" class="btn btn-sm btn-danger btn_create_rebook text-left">Rebook Job</button>
                                    <button id="btn_create_240v_rebook" class="btn btn-sm btn-danger btn_create_240v_rebook text-left">Rebook Job (240v)</button>
                                </div>
                                <div class="col-md-6 flex-div view-booking-flex-box flex-column">
                                    <?php 
                                    $booking_button_text = "Create Booking";
                                    if($job_row['j_status']!="To Be Booked"){
                                        $booking_button_text = "View Booking";
                                    }
                                    ?>
                                    <a href="javascript:;" data-fancybox data-type="ajax" data-src="/jobs/ajax_job_booking?job_id=<?php echo $job_row['jid'] ?>" class="btn <?php echo $btn_colour;?>" style="width:140px;"><?php echo $booking_button_text; ?></a>
                                </div>
                            </div>
                        </div>

                    </div>
                </section>

            </div>

        </div>

    </div>

    <div class="col-md-6">

        <div class="row">

            <div class="col-md-6">

                <section class="card card-blue-fill"> 
                    <header class="card-header">Other Job Data</header>   
                    <div class="card-block">   

                        <div class="form-group form-flex">
                            <label class="form-flex-25">Invoice Number</label>
                            <?php 
                                $check_digit = $this->gherxlib->getCheckDigit(trim($job_row['jid']));
                                $invoice_num = "{$job_row['jid']}{$check_digit}";
                                echo "<div class='form-flex-2'>{$invoice_num}</div>";
                            ?>
                        </div>
                        
                        <div class="form-group form-flex">
                            <label class="form-flex-25">Cancelled Date</label>
                            <?php 
                                $cancelled_date = ($this->system_model->isDateNotEmpty($job_row['cancelled_date'])) ? $this->system_model->formatDate($job_row['cancelled_date'],'d/m/Y') : 'No Data';
                                echo "<div class='form-flex-2'>{$cancelled_date}</div>";
                            ?>
                        </div>

                        <div class="form-group form-flex">
                            <label class="form-flex-25">Deleted Date</label>
                            <?php 
                                $deleted_date = ($this->system_model->isDateNotEmpty($job_row['deleted_date'])) ? $this->system_model->formatDate($job_row['deleted_date'],'d/m/Y') : 'No Data';
                                echo "<div class='form-flex-2'>{$deleted_date}</div>";
                            ?>
                        </div>

                        <div class="form-group form-flex">
                            <label class="form-flex-25">Work Order</label>
                            <a data-fancybox data-src="#work_order_fb" href="#"><?php echo ($job_row['work_order'] != 'NULL' && $job_row['work_order'] != '') ? $job_row['work_order'] : 'No Data' ?></a>

                            <div style="display: none;" id="work_order_fb">
                                <section class="card card-blue-fill">
                                    <header class="card-header">Work Order</header>
                                    <div class="form-group card-block">
                                        <input id="work_order" value="<?php echo $job_row['work_order'] ?>" class="form-control">
                                        <input type="hidden" id="orig_work_order" value="<?php echo $job_row['work_order'] ?>">
                                    </div>
                                </section>
                                <div class="form-group text-right">
                                    <?php if( $can_edit_completed_job==true ){ ?>
                                        <button id="btn_update_work_order" class="btn">Update</button>
                                    <?php }else{
                                        echo "<span class='text-red'>Completed Jobs can't be Edited</span>";
                                    } ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group form-flex">
                            <label class="form-flex-25">Job Not Completed Due to</label>
                            <a data-fancybox data-src="#job_not_completed_due_to_fb" href="#"><?php echo ($job_row['job_reason_name']!="") ? $job_row['job_reason_name'] : "No Data" ?></a>
                            
                            <div id="job_not_completed_due_to_fb" style="display:none;width:400px;">
                                <section class="card card-blue-fill"> 
                                    <header class="card-header">Job Not Completed Due to</header>   
                                    <div class="form-group card-block">
                                        <select class="form-control" id="mark_as">
                                            <option value="">Please Select</option>
                                            <?php foreach( $job_reason_q->result_array() as $job_reason_row ){
                                                $job_reason_sel = ($job_reason_row['job_reason_id']==$job_row['job_reason_id']) ? 'selected' : null;
                                            ?>
                                            <option <?php echo $job_reason_sel; ?> value="<?php echo $job_reason_row['job_reason_id'] ?>"><?php echo $job_reason_row['name'] ?></option>
                                            <?php
                                            } ?>
                                        </select>
                                    </div>
                                </section>
                                <div class="form-group text-right">
                                    <?php if( $can_edit_completed_job==true ){ ?>
                                        <button id="btn_mark_not_completed" class="btn">Mark</button>
                                    <?php }else{
                                        echo "<span class='text-red'>Completed Jobs can't be Edited</span>";
                                    } ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group form-flex">
                            <label class="form-flex-25">Region</label>
                            <?php echo $regions_row['subregion_name'] ?>
                        </div>     
                        
                        <div>
                            <div class="form-group form-flex">
                                <label class="form-flex-25">Distance to Agency <a id="btn_check_distance_to_agency" href="#"><span class="fa fa-arrow-right"></span></a></label>
                                <div class="form-flex-2">
                                    <span id="distance_to_agency_span"></span>
                                </div>
                            </div>
                            
                        </div>

                    </div>
                </section>

                <section class="card card-blue-fill invoice_and_cert_sec">
                    <header class="card-header">Invoices and Certificates</header>
                    <div class="card-block">
                        <?php 
                        if( $job_row['j_status']=="Merged Certificates" || $job_row['j_status']=="Completed" ){ 
                        
                            if( $job_row['assigned_tech']!=1 && $job_row['assigned_tech']!=2 ){
                        ?>

                            <select id="invoice_pdfs_select" class="form-control">
                                <option value="">Select</option>
                                <option data-link="/pdf/combined/<?= $encrypted_job_id; ?>">View Combined</option>
                                <option data-link="/pdf/certificates/<?= $encrypted_job_id; ?>">View <?= $has_low_voltage_alarm ? 'Service Report' : 'Compliance Certificate' ?></option>
                                <option data-link="/pdf/invoices/<?= $encrypted_job_id; ?>">View Invoice</option>

                                <?php
                                if($job_row['p_state']=="QLD"){
                                    if($brooks_q_count>0){
                                        echo "<option data-link='/pdf/quotes/{$encrypted_job_id}/brooks'>View Brooks Quote</option>";
                                    }
                                    if($emerald_q_count>0){
                                        echo "<option data-link='/pdf/quotes/{$encrypted_job_id}>/emerald'>View Emerald Planet Quote</option>";
                                    }
                                    if($brooks_q_count>0 && $emerald_q_count>0){
                                        echo "<option data-link='/pdf/quotes/{$encrypted_job_id}/combined'>View Combined Quote</option>";
                                    }
                                }
                                ?>
                               
                            </select>

                        <?php } }else{ echo "<span class='text-red'>No Data to view until the job is completed</span>"; } ?>
                    </div>
                </section>
               
            </div> 

            <div class="col-md-6">

                <!-- Tenant Entry Notice -->
                <section class="card card-blue-fill">
                    <header class="card-header">Tenant Entry Notice</header>

                        <div class="card-block">
                            <?php
                                if( $job_row['send_entry_notice']!=1 ){
                                    echo '<span class="text-red">Agency does not allow Emailed Entry Notice.</span> Please use the button to Display, Print and Post Entry Notice';
                                }
                            ?>

                            <?php if( $job_row['no_en']==1 ){
                                echo "<span class='text-red'>This property is marked NO Entry Notice</span>";
                            }
                            ?>

                                <div class="flex_div_tt">

                                    <?php if( $this->system_model->isDateNotEmpty($job_row['en_date_issued']) ){ ?>

                                        <div class="form-group">
                                            <label>EN Date Issued:</label>
                                            <?php echo $this->system_model->isDateNotEmpty($job_row['en_date_issued']) ? date("d/m/Y", strtotime($job_row['en_date_issued'])) : ''; ?>
                                        </div>

                                        <div class="form-group">
                                            <a target="_blank" href="/pdf/entry_notice/<?php echo $encrypted_job_id; ?>" class="btn"><span class="fa fa-file-pdf-o"></span>&nbsp; Display EN PDF</a>
                                        </div>
                                    
                                        <?php } ?>

                                </div>
                               
                            <hr style="margin-bottom:15px;margin-top:15px;">
                            Last Emailed: <?php echo ($job_row['entry_notice_emailed'] == null) ? "<span class='text-red'>Never</span>" : "<span class='text-green'>" . date('d/m/Y H:i', strtotime($job_row['entry_notice_emailed'])) . " - See job log for additional info</span>"; ?>
                        </div>
                </section>
                <!-- Tenant Entry Notice end -->

            </div>     

        </div>

    </div>

</div>


<!-- job_details popup -->
<div id="job_details_fancybox" style="display: none;min-width:400px; width:565px;">
    <?php echo form_open('/jobs/ajax_update_job_detail', array('id'=>'update_job_details_form','class'=>'update_job_detail_form')); ?>
        <section class="card card-blue-fill"> 
            <header class="card-header">Edit Job</header>   
            <div class="card-block">       
                <div class="job_details_box">
                    <input type="hidden" id="prop_id" value="<?php echo $job_row['prop_id'] ?>">
                    <div class="row">
                        <div class="col-md-12">

                            <div class="form-group">
                                <label>Job Type</label>
                                <select id="job_type" class="vw-jb-sel form-control">
                                    <option value="None Selected">No Job Type Selected</option>
                                    <?php
                                        foreach( $job_type_list->result_array() as $job_type_row ){
                                            $sel_job_type = ($job_type_row['job_type']==$job_row['j_type']) ? 'selected' : null;
                                    ?>
                                        <option <?php echo $sel_job_type; ?> value="<?php echo $job_type_row['job_type'] ?>"><?php echo $job_type_row['job_type'] ?></option>
                                    <?php
                                        }
                                    ?>
                                </select>
                                <input type="hidden" name="orig_jobtype" id="orig_jobtype" value="<?php echo $job_row['j_type']; ?>">
                            </div>

                            <div class="form-group">
                                <label>Job Status</label>
                                <select class="vw-jb-sel form-control job_status">
                                    <option value="">No Job Status Selected</option>
                                    <?php 
                                    foreach($job_status_arr as $job_status_arr_val){
                                        $sel_job_status = ( $job_status_arr_val==$job_row['j_status'] ) ? 'selected' : null;
                                    ?>
                                        <option <?php echo $sel_job_status; ?> value='<?php echo$job_status_arr_val; ?>'><?php echo $job_status_arr_val; ?></option>
                                    <?php
                                    } 
                                    ?>
                                </select>
                            </div>

                            <div class="escalate_reason_main_box" style="display:<?php echo ($job_row['j_status'] == "Escalate") ? 'block' : 'none'; ?>;">
                                <div class="form-group">
                                    <label>Escalate Reason</label>
                                    <select class="form-control escalate_job_reason">
                                        <option value="">--Select--</option>
                                        <?php 
                                        foreach($escalate_job_reasons_list as $escalate_job_reasons_row){
                                            $selected_escalate_row =  ($selected_escalate_job_reasons_row['escalate_job_reasons_id']==$escalate_job_reasons_row['escalate_job_reasons_id']) ? 'selected' : null;
                                        ?>
                                        <option <?php echo $selected_escalate_row; ?> value="<?php echo $escalate_job_reasons_row['escalate_job_reasons_id'] ?>"><?php echo $escalate_job_reasons_row['reason_short'] ?></option>    
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="allocate_notes_main_box" style="display:<?php echo ($job_row['j_status'] == "Allocate") ? 'block' : 'none'; ?>;">
                                <div class="form-group">
                                    <label>Allocate Notes</label>
                                    <textarea class="form-control allocate_notes"><?php echo $job_row['allocate_notes'] ?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="checkbox">
                                    <input type="checkbox" name="urgent_job" id="urgent_job" class="urgent_job form-control" <?php echo ($job_row['urgent_job']==1) ? 'checked' : null; ?> value="1">
                                    <label for="urgent_job">Urgent/ Outside of scope</label>
                                </div>
                                <input type="text" name="urgent_job_reason" id="urgent_job_reason" class="form-control urgent_job_reason" value="<?php echo $job_row['urgent_job_reason'] ?>" placeholder="Urgent/ Outside of scope reason">
                                <input type="hidden" id="orig_urgent_job" value="<?php echo $job_row['urgent_job'] ?>">
                                <input type="hidden" id="orig_urgent_job_reason" value="<?php echo $job_row['urgent_job_reason'] ?>">
                            </div>

                            <div class="form-group">
                                <div class="checkbox">
                                    <input value="1" id="lock_box_chkbox_job_detail" type="checkbox"  class="lock_box_chkbox" value="1" <?php echo ($job_row['code']!='') ? "checked" : null; ?> >
                                    <label for="lock_box_chkbox_job_detail">Lock Box</label>
                                </div>
                                <div class="lockbox_code_box" style="display:<?php echo ($job_row['code']!='') ? 'block;' : 'none;'; ?>">
                                    <label>Lockbox Code</label>
                                    <input value="<?php echo $job_row['code'] ?>" class="form-control lockbox_code">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Start Date/End Date</label>
                                <div class="checkbox">
                                    <input value="1" type="checkbox" name="no_dates_provided" id="no_dates_provided" <?php echo ($job_row['no_dates_provided']==1) ? 'checked' : null;?>>
                                    <label for="no_dates_provided">Dates not Provided</label>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input placeholder="Start Date (DD/MM/YYYY)" type="text" class="flatpickr_vjd form-control start_date" value="<?php echo $start_date; ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <input placeholder="Due Date (DD/MM/YYYY)" type="text" class="flatpickr_vjd form-control due_date" value="<?php echo $due_date; ?>">
                                    </div>
                                </div>

                            </div>

                            <div class="form-group">
                                <div class="checkbox">
                                    <input value="1" type="checkbox" id="prop_vac" class="prop_vac form-control" <?php echo ($job_row['property_vacant']==1) ? 'checked' : null; ?>>
                                    <label for="prop_vac">Property Vacant</label>
                                </div>
                                <div class="prop_vacant_dates" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input onchange="validatePropertyVacantDates(this, document.getElementsByClassName('vacant_to')[0], 'start_date')" type="text" class="form-control flatpickr_vjd vacant_from" placeholder="Vacant From (dd/mm/YYYY)" value="<?php echo ($job_vacant_row['start_date']!="")?$this->system_model->formatDate($job_vacant_row['start_date'],'d/m/Y') : NULL ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <input onchange="validatePropertyVacantDates(document.getElementsByClassName('vacant_from')[0], this, 'end_date')" type="text" class="form-control flatpickr_vjd vacant_to" placeholder="Vacant To (dd/mm/YYYY)" value="<?php echo ($job_vacant_row['end_date']!="")?$this->system_model->formatDate($job_vacant_row['end_date'],'d/m/Y') : NULL ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="checkbox">
                                    <input value="1" type="checkbox" id="is_eo" class="is_eo form-control" <?php echo ( $job_row['is_eo']==1 ) ? 'checked' : null; ?>>
                                    <label for="is_eo">Electrician Only </label>
                                </div>
                                <input type="hidden" name="orig_is_eo" id="orig_is_eo" value="<?php echo $job_row['is_eo']; ?>">
                            </div>

                            <div class="form-group">
                                <label>Key Number</label>
                                <input maxlength="20" class="form-control key_number" maxlength="10" value="<?php echo $job_row['key_number']; ?>">

                                <input type="hidden" name="orig_key_number" id="orig_key_number" value="<?php echo $job_row['key_number']; ?>">
                            </div>
                            
                            <div class="form-group">
                                <div class="checkbox">
                                    <input value="1" type="checkbox" id="dha_need_processing" class="dha_need_processing form-control" <?php echo ($job_row['dha_need_processing']==1) ? 'checked' : null; ?>>
                                    <label for="dha_need_processing">Needs Processing DHA | Tapi | etc</label>
                                </div>
                                <input type="hidden" id="orig_dha_need_processing" value="<?php echo $job_row['dha_need_processing'] ?>">
                            </div>

                            <div class="form-group">
                                <label>Approved Alarms</label>
                                <select class="form-control preferred_alarm_id">
                                    <option value="">---</option>
                                    <option value="10" <?php echo ( $job_row['preferred_alarm_id'] == 10 )?'selected':null; ?>>Brooks</option>
                                    <option value="14" <?php echo ( $job_row['preferred_alarm_id'] == 14 )?'selected':null; ?>>Cavius</option>
                                    <option value="22" <?php echo ( $job_row['preferred_alarm_id'] == 22 )?'selected':null; ?>>Emerald</option>
                                </select>
                                <input type="hidden" id="orig_preferred_alarm_id" value="<?php echo $job_row['preferred_alarm_id'] ?>">
                            </div>

                            <div class="form-group">
                                <label>House Alarm Code</label>
                                <input value="<?php echo $job_row['alarm_code'] ?>" class="form-control alarm_code">
                                <input type="hidden" name="orig_alarm_code" id="orig_alarm_code" value="<?php echo $job_row['alarm_code'] ?>">
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </section>
        <div class="form-group">
            <div class="text-right">
                <?php if( $can_edit_completed_job==true ){ ?>
                    <button id="btn_update_job_details" class="btn">Update Job Details</button>
                <?php }else{
                    echo "<span class='text-red'>Completed Jobs can't be Edited</span>";
                } ?>
            </div>
        </div>
    </form>
</div>


<div id="job_booking_fancybox" style="display: none; width:565px;">
    <?php echo form_open('/jobs/ajax_update_job_detail', array('id'=>'update_job_booking_details_form','class'=>'update_job_detail_form')); ?>
    <input type="hidden" class="vacant_start_date" value="<?php echo ($job_vacant_row['start_date']!="") ? $job_vacant_row['start_date'] : NULL; ?>">
    <input type="hidden" class="vacant_end_date" value="<?php echo ($job_vacant_row['end_date']!="") ? $job_vacant_row['end_date'] : NULL; ?>">
    <section class="card card-blue-fill"> 
        <header class="card-header">Edit Job</header>   
            <div class="card-block"> 

                <div class="form-group">
                    <label>Job Status</label>
                    <select class="vw-jb-sel form-control job_status">
                        <option value="">No Job Type Selected</option>
                        <?php 
                        foreach($job_status_arr as $job_status_arr_val){
                            $sel_job_status = ( $job_status_arr_val==$job_row['j_status'] ) ? 'selected' : null;
                        ?>
                            <option <?php echo $sel_job_status; ?> value='<?php echo$job_status_arr_val; ?>'><?php echo $job_status_arr_val; ?></option>
                        <?php
                        } 
                        ?>
                    </select>
                </div>

                <div class="escalate_reason_main_box" style="display:<?php echo ($job_row['j_status'] == "Escalate") ? 'block' : 'none'; ?>;">
                    <div class="form-group">
                        <label>Escalate Reason</label>
                        <select class="form-control escalate_job_reason">
                            <option value="">--Select--</option>
                            <?php 
                            foreach($escalate_job_reasons_list as $escalate_job_reasons_row){
                                $selected_escalate_row =  ($selected_escalate_job_reasons_row['escalate_job_reasons_id']==$escalate_job_reasons_row['escalate_job_reasons_id']) ? 'selected' : null;
                            ?>
                            <option <?php echo $selected_escalate_row; ?> value="<?php echo $escalate_job_reasons_row['escalate_job_reasons_id'] ?>"><?php echo $escalate_job_reasons_row['reason_short'] ?></option>    
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="allocate_notes_main_box" style="display:none">
                    <div class="form-group">
                        <label>Allocate Notes</label>
                        <textarea class="form-control allocate_notes"><?php echo $job_row['allocate_notes'] ?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label>Date</label>
                    <input value="<?php echo ($this->system_model->isDateNotEmpty($job_row['j_date'])) ? $this->system_model->formatDate($job_row['j_date'],'d/m/Y') : ''; ?>" type="text" value="" class="addinput vw-jb-inpt jobdate form-control flatpickr_vjd" placeholder="DD/MM/YYYY" style="width:130px;">
                </div>

                <div class="form-group">
                    <label>Time of Day <?php echo ($job_row['allow_dk'] == 1 && $job_row['no_dk'] == 0) ? null : "<small class='text-red'>NO DKs ALLOWED</small>"; ?></label>
                    <input value="<?php echo $job_row['time_of_day']; ?>" type="text" class="timeofday form-control">
                </div>

                <!-- Key access -->
                <?php
                /**
                 * Show only key access relevant fields/input if allowed and has keys
                 * AND Entry Notice is allowed
                 */
                if (($job_row['key_allowed'] == 1 && $job_row['no_keys'] != 1) && $job_row['no_en'] != 1) {
                ?>
                <div class="form-group">
                    <div class="checkbox">
                        <input type="checkbox" id="key_access_required" class="key_access_required" value="1" <?php echo ($job_row['key_access_required']==1) ? "checked" : null; ?>>
                        <label for="key_access_required">Key Access</label>
                    </div>

                    <div class="key_access_details_div">
                        <div class="form-group phone_booking_tab_content">
                            <label>Authorised By</label>
                            <input type="text" value="<?php echo $job_row['key_access_details'] ?>" class="tenantinput addinput form-control key_access_details" placeholder="Authorised By">
                        </div>
                        <div class="form-group phone_booking_tab_content">
                            <label>Key Number</label>
                            <input class="form-control key_number" maxlength="10" value="<?php echo $job_row['key_number']; ?>">
                        </div>
                    </div>
                </div>
                <!-- Key access end -->
                <?php } 
                
                // Door Knock
                if( $job_row['allow_dk'] == 1 && $job_row['no_dk'] != 1 ){ ?>

                    <div class="form-group">
                        <div class="checkbox">
                            <input type="checkbox" id="dk_lb"  class="dk_lb" value="1" <?php echo ($job_row['door_knock']==1) ? "checked" : null; ?> >
                            <label for="dk_lb">Door Knock</label>
                        </div>                 
                    </div>

                <?php
                }                
                ?>                

                <div class="form-group">
                    <div class="checkbox">
                        <input value="1" type="checkbox" id="lock_box_chkbox_booking_detail" class="lock_box_chkbox" value="1" <?php echo ($job_row['code']!='') ? "checked" : null; ?> >
                        <label for="lock_box_chkbox_booking_detail">Lock Box</label>
                    </div>
                    <div class="lockbox_code_box" style="display:<?php echo ($job_row['code']!='') ? 'block;' : 'none;'; ?>">
                        <label>Lockbox Code</label>
                        <input value="<?php echo $job_row['code'] ?>" class="form-control lockbox_code">
                    </div>
                </div>

                <div class="form-group booking-group phone_booking_tab_content">
                    <div class="checkbox">
                        <input type="checkbox" id="call_before_chxbox"  class="call_before_chxbox" value="1" <?php echo ($job_row['call_before']==1) ? "checked" : null; ?> >
                        <label for="call_before_chxbox">Call Before</label>
                    </div>
                    <input value="<?php echo $job_row['call_before_txt']; ?>" type="text" maxlength="6" class="form-control call_before_txt" id="call_before_txt" placeholder="Call Before" >
                </div>

                <div class="form-group booking-group phone_booking_tab_content">
                    <label>Booked With</label>
                    <select class="form-control booked_with">
                        <option value="">-- Select --</option>
                        <?php
                            foreach( $active_tenants->result_array() as $active_tenants_row ){ 

                            if( $active_tenants_row['tenant_firstname']==$job_row['booked_with'] ){
                                $booked_with_mobile = $active_tenants_row['tenant_mobile'];
                            }

                            $sel_tenants = ($active_tenants_row['tenant_firstname']==$job_row['booked_with']) ? 'selected' : null;
                        ?>
                            <option data-mobile_num="<?php echo $active_tenants_row['tenant_mobile']; ?>" <?php echo $sel_tenants; ?> value="<?php echo $active_tenants_row['tenant_firstname'] ?>"><?php echo $active_tenants_row['tenant_firstname'] ?></option>
                        <?php } ?>
                        <option data-mobile_num="" value="Agent" <?php echo ( $job_row['booked_with'] == 'Agent' ) ? 'selected="selected"' : ''; ?>>Agent</option>
                    </select>

                    <input type="hidden" name="orig_booked_with" id="orig_booked_with" value="<?php echo $job_row['booked_with']; ?>">
                </div>

                <div class="form-group booking-group phone_booking_tab_content en_notice_tab_content dk_tab_content">
                    <label>Booked By</label>
                    <div class="row">
                        <div class="col-md-12">
                            <select class="form-control booked_by">
                                <option value="">-- Select --</option>
                                <?php 
                                foreach( $all_booked_by->result_array() as $all_booked_by_row ){
                                    $all_booked_by_sel = "";
                                    if($job_row['booked_by']>=1){
                                        $all_booked_by_sel = ($all_booked_by_row['StaffID'] == $job_row['booked_by']) ? 'selected' : null;
                                    }else{ //null/empty booked by set value default to logged-in user
                                        $all_booked_by_sel = ($all_booked_by_row['StaffID'] == $this->session->staff_id) ? 'selected' : null;
                                    }
                                   

                                ?>
                                    <option <?php echo $all_booked_by_sel; ?> value="<?php echo $all_booked_by_row['StaffID'] ?>"><?php echo $this->system_model->formatStaffName($all_booked_by_row['FirstName'],$all_booked_by_row['LastName']); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="orig_booked_by" id="orig_booked_by" value="<?php echo $job_row['booked_by']; ?>">
                </div>

                <div class="form-group booking-group phone_booking_tab_content en_notice_tab_content dk_tab_content">
                    <label>Job Notes FOR Technician</label>
                    <textarea rows="3" class="form-control comments"><?php echo $job_row['j_comments']; ?></textarea>
                </div>

                <div class="form-group booking-group en_notice_tab_content dk_tab_content">
                <label>Technician</label>
                <select class="form-control techid">
                    <option value="">-- Select --</option>
                    <?php 
                    if ($job_row['assigned_tech'] != '') {
                        $sel_tech = $job_row['assigned_tech'];
                    } else if ($job_row['assigned_tech'] == '' && $this->input->get_post('tr_tech_id') != '') {
                        $sel_tech = $this->input->get_post('tr_tech_id');
                    }
                    foreach( $technician->result_array() as $technician_row ){ 
                        $sel_tech_row = ( $technician_row['StaffID']==$sel_tech ) ? 'selected' : null;
                        $tech_red_color = ($technician_row['StaffID']==1 || $technician_row['StaffID']==2 || $technician_row['StaffID']==3) ? 'text-red' : null;
                    ?>
                        <option class="<?php echo $tech_red_color; ?>" <?php echo $sel_tech_row; ?> value="<?php echo $technician_row['StaffID'] ?>" data-is_electrian="<?php echo $technician_row['is_electrician']; ?>">
                        <?php 
                            echo $this->system_model->formatStaffName($technician_row['FirstName'],$technician_row['LastName']);
                            echo ($technician_row['is_electrician']==1) ? ' [E]' : null;
                            echo ($technician_row['sa_active'] == 0 ) ? ' (Inactive)' : null;
                        ?>
                        </option>
                    <?php } ?>
                </select>
                <input type="hidden" name="orig_techid" id="orig_techid" value="<?php echo $sel_tech; ?>">
            </div>

            <div class="form-group">
                <label>Run Sheet Notes</label>
                <div class="checkbox">
                    <input type="checkbox" id="job_entry_notice" class="run_sheet_notes_chk1 job_entry_notice" data-flag="1" value="1" <?php echo ( $job_row['job_entry_notice']==1) ?'checked':null; ?>>
                    <label for="job_entry_notice">Entry Notice</label>
                </div>
                <div class="checkbox">
                    <input type="checkbox" id="job_priority" class="run_sheet_notes_chk2 job_priority" data-flag="2" <?php echo ( $job_row['job_priority']==1) ?'checked':null; ?>>
                    <label for="job_priority">Do Not Cancel</label>
                </div>
                <input type="text" id="tech_notes" maxlength="15" value="<?php echo $job_row['tech_notes'] ?>" class="form-control tech_notes">

                <input type="hidden" class="orig_job_entry_notice" value="<?php echo $job_row['job_entry_notice']; ?>">
                <input type="hidden" class="orig_tech_notes" value="<?php echo $job_row['tech_notes']; ?>">
            </div>

        </div>
    </section>
    <div class="form-group">
        <div class="text-right">
            <?php if( $can_edit_completed_job==true ){ ?>
                <button id="btn_update_job_booking_details" class="btn">Save</button>
            <?php }else{
                echo "<span class='text-red'>Completed Jobs can't be Edited</span>";
            } ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<!-- job_details popup end -->

<hr/>

<!-- Job/Agency/Property NOTES -->
<section class="card card-blue-fill">
            <header class="card-header">Notes</header>

            <div class="card-block notes_card">
                <div class="row">
                    <div class="col-md-3">
                        <label>Property Notes</label>
                        <a javascript:; data-fancybox data-src="#notes_fancybox" href="#"><?php echo ($job_row['p_comments']!="")?$job_row['p_comments']:"No Data"; ?></a>
                    </div>
                    <div class="col-md-3">
                        <label>Repair Notes</label>
                        <a javascript:; data-fancybox data-src="#notes_fancybox" href="#"><?php echo ($job_row['repair_notes']!="")?$job_row['repair_notes']:"No Data"; ?></a>
                    </div>
                    <div class="col-md-3">
                        <label>Job Notes FROM Technician</label>
                        <a javascript:; data-fancybox data-src="#notes_fancybox" href="#"><?php echo ($job_row['tech_comments']!="")?$job_row['tech_comments']:"No Data"; ?></a>
                    </div>
                    <div class="col-md-3">
                        <label>Compliance Notes</label>
                        <a javascript:; data-fancybox data-src="#notes_fancybox" href="#"><?php echo  ($job_row['not_compliant_notes']!="")?$job_row['not_compliant_notes']:"No Data"; ?></a>
                    </div>
                </div>
            </div>

            <div style="display: none;width:565px;" id="notes_fancybox">

                <section class="card card-blue-fill">
                    <header class="card-header">Edit Job/Agency/Property Notes</header>
                    <div class="card-block">
                        <div class="form-group">
                            <label>Property Notes</label>
                            <textarea rows="3" id="prop_comments" name="prop_comments" class="form-control"><?php echo $job_row['p_comments']; ?></textarea>
                            <textarea style="display:none;"  rows="3" id="orig_prop_comments" name="orig_prop_comments" class="form-control"><?php echo $job_row['p_comments']; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Repair Notes</label>
                            <textarea rows="3" id="repair_notes" name="repair_notes" class="form-control"><?php echo $job_row['repair_notes']; ?></textarea>
                            <textarea style="display:none;"  rows="3" id="orig_repair_notes" name="orig_repair_notes" class="form-control"><?php echo $job_row['repair_notes']; ?></textarea>
                        </div>
                    
                        <div class="form-group">
                            <label>Job Notes FROM Technician</label>
                            <textarea rows="3" id="tech_comments" name="tech_comments" class="form-control"><?php echo $job_row['tech_comments']; ?></textarea>
                            <textarea style="display:none;"  rows="3" id="orig_tech_comments" name="orig_tech_comments" class="form-control"><?php echo $job_row['tech_comments']; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Compliance Notes</label>
                            <textarea rows="3" id="not_compliant_notes" name="not_compliant_notes" class="form-control"><?php echo $job_row['not_compliant_notes']; ?></textarea>
                            <textarea style="display:none;"  rows="3" id="orig_not_compliant_notes" name="orig_not_compliant_notes" class="form-control"><?php echo $job_row['not_compliant_notes']; ?></textarea>
                        </div>
                    </div>
                </section>

                <div class="form-group text-right">
                    <?php if( $can_edit_completed_job==true ){ ?>
                        <button id="btn_update_notes" class="btn">Update Notes</button>
                    <?php }else{
                        echo "<span class='text-red'>Completed Jobs can't be Edited</span>";
                    } ?>
                </div>

            </div>

</section>
<!-- Job/Agency/Property NOTES END -->

<!-- Invoices and Certificate -->
<section class="card card-blue-fill action_buttons_sec">
    <header class="card-header">Additional Functions</header>
    <div class="card-block">
        <div class="table-responsivess">
            <?php if( $can_edit_completed_job==true ){?>
           <div class="form-group row">
                <div class="col-md-8">
                    <a href="/jobs/tech_sheet/?job_id=<?php echo $job_row['jid'] ?>" class="btn btn-sm">View Tech Sheet</a>
                    <button type="button" id="sync_alarm_btn" class="btn btn-sm">Sync Smoke Alarms ONLY</button>
                    <button type="button" id="recreate_bundle_services_btn" class="btn btn-sm"> Recreate Bundle Services</button>
                    <?php if( $job_row['j_status'] == "Booked" ){ ?>
                    <button <?php echo ($booked_with_mobile=="") ? 'disabled' :null; ?> type="button" id="sms_to_conf_book" class="btn btn-sm">SMS to Confirm booking</button>
                    <?php } ?>

                    <?php 
                        if(  //PME
                            $isPME===true &&
                            $job_row['pme_supplier_id']!="" &&
                            ( $job_row['j_status'] == 'Merged Certificates' || $job_row['j_status'] == 'Completed' )
                        ){

                            echo '<button id="upload_invoice_bill_to_pme_btn" type="button" class="btn btn-sm">Upload Documents to PMe</button>';
                            echo "<input type='hidden' id='orig_is_pme_invoice_upload' name='is_pme_invoice_upload' value='{$job_row['is_pme_invoice_upload']}'>";
                        
                        }else if( //PALACE
                            $isPalace===true &&
                            $job_row['palace_supplier_id']!="" &&
                            $job_row['palace_diary_id']!="" &&
                            ( $job_row['j_status'] == 'Merged Certificates' || $job_row['j_status'] == 'Completed' )             
                        ){

                            echo '<button id="upload_invoice_bill_to_palace_btn" type="button" class="btn btn-sm">Upload Documents to Palace</button>';
                             echo "<input type='hidden' id='orig_is_palace_invoice_upload' name='orig_is_palace_invoice_upload' value='{$job_row['is_palace_invoice_upload']}'>";

                        }else if(
                            $isConsole===true && $ajd_sql_count==0
                        ){
                        //Console
                            echo '<button id="upload_invoice_bill_to_console_btn" type="button" class="btn btn-sm">Upload Documents to Console</button>';
                            echo "<input type='hidden' id='console_api_has_data' name='console_api_has_data' value='{$ajd_sql_count}'>";

                        }
                    ?>
                    
                </div>
                <div class="col-md-4 text-right">

                    <a href="#" data-src="#move_job_fb" data-fancybox=""  id="btn_move_job" class="btn fancybox btn-sm btn-warning">Move Job</a>
                    <!-- Move Job fancybox -->
                    <div id="move_job_fb" style="display:none;">
                        <section class="card card-blue-fill">
                            <header class="card-header">Move Job</header>   
                            <div class="card-block">
                                <div class="form-group">
                                    <label>Property ID</label>
                                    <input type="text" id="move_job_property_id" class="form-control">    
                                    <div id='search_prop_display' style="margin-top:5px;"></div>
                                </div>
                            </div>
                        </section>
                        <div class="form-group text-right">
                            <button type='button' id='btn_move' class='blue-btn submitbtnImg btn btn-sm' style='display:none;'>Move Job</button>
                        </div>
                    </div>

                    <?php if( $job_row['del_job']==1 ){
                        echo "<button class='btn btn-sm ' id='btn_restore_job' type='button'>Restore Job</button>";
                    }else{
                        echo '<button type="button" id="btn_del_job_temp" class="btn btn-sm btn-danger">Delete Job</button>';
                    } ?>

                    <a class="btn btn-sm" href="<?php echo $this->config->item('crm_link') ?>/view_job_details.php?id=<?php echo $job_row['jid'] ?>">Old VJD</a>

                </div>
           </div>
           <?php } ?>
           <div class="form-groupss">
                <?php                
                if( config_item('theme') === 'sats' ){ // SATS

                    if( config_item('country') == 1 ){ // AU only
                        
                        if( ENVIRONMENT=="production" ){
                            $paybyweb_link = 'https://paybyweb.nab.com.au/SecureBillPayment/start?org_id=3le&bill_name=smokealarm';
                        }else{
                            $paybyweb_link = 'https://demo.paybyweb.nab.com.au/SecureBillPayment/start?org_id=3le&bill_name=smokealarm';
                        }

                    }

                }else if( config_item('theme') === 'sas' ){ // SAS

                    $paybyweb_link = 'https://www.payway.com.au/sign-in';

                }                
                ?>
                <a target="_blank" href="<?php echo $paybyweb_link; ?>" class="btn btn-sm">Payment Portal</a>
                <a target="_blank" href="/email/send/?job_id=<?php echo $job_row['jid'] ?>" class="btn btn-sm btn-success">Email Templates</a>
                <a target="_blank" href="/sms/send/?job_id=<?php echo $job_row['jid'] ?>" class="btn btn-sm btn-success">SMS Templates</a>
           </div>
        </div>
    </div>
</section>
<!-- Invoices and Certificate end -->

<!-- logs -->
<section class="card card-blue-fill logs_sec">
    <header class="card-header">Logs</header>

    <div class="card-block">

        <div class="card-block-new-logs" style="margin-bottom: 20px;">
           
            <div class="add_event_section card">
                <div class="card-block">
                    
                    <div id="add_event_fb">
                        <div class="form-group">
                            <label>Date</label>
                            <input type="text" id="joblog-date" name="joblog-date" class="flatpickr form-control" value="<?php echo date('d/m/Y') ?>">
                        </div>
                        <div class="form-group">
                        <label>Contact Type</label>
                            <select class="form-control" id="contact_type" name="contact_type">
                                <option value="">Please Select</option>
                                <?php 
                                    foreach( $log_title_for_contact_type_dropdown['result_obj']->result_array() as $log_title_for_contact_type_dropdown_row ){
                                ?>
                                    <option <?php echo ($log_title_for_contact_type_dropdown_row['log_title_id']==93) ? 'selected' : null; ?> value="<?php echo $log_title_for_contact_type_dropdown_row['log_title_id'] ?>"><?php echo $log_title_for_contact_type_dropdown_row['title_name'] ?></option>
                                <?php
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Comment</label>
                            <input type="text" class="form-control" name="joblog-comments" id="joblog-comments" style="width: 500px;">
                        </div>
                        <div class="form-group">
                            <label>Unavailable</label>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="checkbox" style="margin-top:11px;">
                                            <input type="checkbox" id="unavailable">
                                            <label for="unavailable">&nbsp;</label>
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" name="unavailable_date" id="unavailable_date" class="form-control flatpickr" placeholder="Date">
                                    </div>
                                </div>
                        </div>
                        <div class="form-group">
                            <label>Important</label>
                                <div class="checkbox" style="margin-top:11px;">
                                    <input type="checkbox" id="important" value="1">
                                    <label for="important">&nbsp;</label>
                                </div>
                        </div>
                        <div class="form-group text-right">
                            <label>&nbsp;</label>
                            <button type="button" class="btn" id="add-log">Add Event</button>
                        </div>
                    </div>
                </div>
            </div>

            <?php if( $new_logs_q->num_rows()>0 ){ ?>
            <table class="table table-hover main-table log_table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Title</th>
                        <th>Who</th>
                        <th>Details</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach( $new_logs_q->result_array() as $new_logs_row ){ 
                        if($new_logs_row['important']==1){
                            $tr_bg = "#FFCCCB";
                        }else{
                            $tr_bg = "transparent";
                        }
                    ?>
                    <tr style="background:<?php echo $tr_bg; ?>">
                        <td data-order="<?php echo $new_logs_row['created_date'] ?>">
                            <input type="hidden" class="log_id" value="<?php echo $new_logs_row['log_id'] ?>">
                            <?php echo $this->system_model->formatDate($new_logs_row['created_date'],'d/m/Y'); ?>
                        </td>
                        <td>
                            <?php echo $this->system_model->formatDate($new_logs_row['created_date'],'H:i'); ?>
                        </td>
                        <td>
                            <?php echo $new_logs_row['title_name'] ?>
                        </td>
                        <td>
                            <?php
                            if( $new_logs_row['StaffID'] != '' ){ // sats staff
                                echo  $this->system_model->formatStaffName($new_logs_row['FirstName'], $new_logs_row['LastName']);
                            }else{ // agency portal users
                                echo $this->system_model->formatStaffName($new_logs_row['fname'],$new_logs_row['lname']);
                            }
                            ?>
                        </td>
                        <td>
                        <?php 
                        $params = array(
                            'log_details' => $new_logs_row['details'],
                            'log_id' => $new_logs_row['log_id']
                        );								
                            echo $this->agency_model->parseDynamicLink_to_crm($params);
                        ?>
                        </td>
                        <td class="text-center">
                        <?php
                        /**
                         * Ability to delete if logs owned by current staff logined
                         * And if title is from 'log_title_usable_pages' > it means log created manualy in VJD
                         */
                        if($new_logs_row['created_by_staff']==$this->session->staff_id && in_array($new_logs_row['title'], $log_title_for_contact_type_dropdown['usable_log_title_id'])){
                            echo "<a class='btn_delete_log' data-toggle='tooltip' title='Delete' href='javascript:void(0)'><span class='text-red fa fa-trash'></span></a>";
                            }
                        ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

            <?php }else{
                echo "No Data";
            } ?>
        </div>


        <div class="card-block-old-logs">
            <div class="table-responsive">
            <?php if( $old_job_log_q->num_rows()>0 ){ ?>
                <table class="table table-hover main-table log_table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Type</th>
                            <th>Who</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <?php 
                        foreach($old_job_log_q->result_array() as $old_job_log_row){ 
                            if($old_job_log_row['important']==1){
                                $tr_bg = "#FFCCCB";
                            }else{
                                $tr_bg = "transparent";
                            }
                        ?>
                            <tr style="background:<?php echo $tr_bg; ?>">
                                <td data-order="<?php echo $this->system_model->formatDate($old_job_log_row['jl_date'],'Y-m-d') ?>">
                                    <input type='hidden' class='job_log_id' value='<?php echo $old_job_log_row['log_id'] ?>' />
                                    <?php echo $old_job_log_row['jl_date'] ?>
                                </td>
                                <td><?php echo $old_job_log_row['eventtime'] ?></td>
                                <td><?php echo $old_job_log_row['contact_type'] ?></td>
                                <td>
                                    <?php
                                    $who = "";
                                    if( $old_job_log_row['log_agency_id']!="" ){
                                        $log_agency_id_row = $this->db->select('agency_name')->where('agency_id',$old_job_log_row['log_agency_id'])->get()->row_array();
                                        $who = $log_agency_id_row['agency_name'];
                                    }else{
                                        if ($old_job_log_row['auto_process'] == 1) {
                                            $who = 'Auto Processed';
                                        } else if ($old_job_log_row['staff_id'] != 0) {
                                            $who = $this->system_model->formatStaffName($old_job_log_row['FirstName'], $old_job_log_row['LastName']);
                                        } else {
                                            $who = 'Agency';
                                        }
                                    }
                                    echo $who;
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $jl_com = "";
                                    if ($old_job_log_row['comments'] == 'Invoice/Cert Email Sent') {
                                        $jl_com = 'Invoice/Cert Email Sent (Not by Agency, by ' . config_item('company_name_short') . ')';
                                    } else {
                                        $jl_com = $old_job_log_row['comments'];
                                    }
                                    echo $jl_com;
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>

                    </tbody>
                </table>
                <?php }else{
                    echo "No Data";
                } ?>
            </div>
        </div>

    </div>
</section>
<!-- logs end -->

<script type="text/javascript">

     function getTechNotesFromTickBox() {

        var jen_check = jQuery("#job_entry_notice").prop("checked");
        var jp_check = jQuery("#job_priority").prop("checked");
        var tech_notes_txt = '';

        jen_check_txt = (jen_check == true) ? 'EN - KEYS ' : '';
        jp_check_txt = (jp_check == true) ? 'DO NOT CANCEL ' : '';

        return tech_notes_txt = jen_check_txt + jp_check_txt;

    }


    function chckbox_show_hide_div(node,target)
    {

        if(node.is(':checked')){
           // $(target).show();
            $(target).slideDown();
        }else{
            //$(target).hide();
            $(target).slideUp();
            //$(target).val('');
        }

    }

    function booked_with_and_sms_tweak()
    {
        var booked_with_mobile = $('#booked_with_phone_booking option:selected').attr('data-mobile_num');

        if(booked_with_mobile==""){
            $('.no_mobile_num_text').text("(No Mobile Number)");
            $('#send_booking_sms').prop('checked', false).attr('disabled',true);
        }else{
            $('.no_mobile_num_text').text("");
            $('#send_booking_sms').prop('checked', true).attr('disabled',false);;
        }
    }

    function ajax_update_booking_req(obj)
    {
        $('#load-screen').show(); //show loader
        jQuery.ajax({
            type: "POST",
            url: "/jobs/ajax_update_job_detail",
            dataType: 'json',
            data: obj
        }).done(function( retval ) {
            $('#load-screen').hide(); //hide loader
            if(retval.status){

                swal({
                    title:"Success!",
                    text: "Job Successfully Updated",
                    type: "success",
                    showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                    timer: <?php echo $this->config->item('timer') ?>
                });

                var full_url = window.location.href;
                setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
            
            }else{
                if( retval.error!="" ){
                    swal('Error',retval.error,'error');
                }else{
                    swal('Error','Internal error please contact admin','error');
                }
            }

        });
    }

    // job event log submit script
    function add_job_event_log(){

        var job_id = <?php echo $job_row['jid']; ?>;
        var date = $('#joblog-date').val();
        var contact_type = $('#contact_type').val();
        var comment = $('#joblog-comments').val();
        var unavailable = $('#unavailable').val();
        var unavailable_date = $('#unavailable_date').val();
        var important = $('#important').val();

        var err = "";

        if(date==""){
            err+="Please Enter Date\n";
        }

        if(contact_type==""){
            err+="Please Enter Contact Type\n";
        }

        if(comment==""){
            err+="Please Enter Comment\n";
        }

        if(err!=""){
            swal('Error',err,'error');
            return false;
        }else{

            $('#load-screen').show(); //show loader

            jQuery.ajax({
                type: "POST",
                url: "/jobs/ajax_add_event_job_log",
                dataType: 'json',
                data: {
                    job_id: job_id,
                    date: date,
                    contact_type: contact_type,
                    comment: comment,
                    unavailable: (jQuery("#unavailable").prop("checked") == true )?1:0,
                    unavailable_date: unavailable_date,
                    important: (jQuery("#important").prop("checked") == true )?1:0, 
                }

            }).done(function( retval ) {
                $('#load-screen').hide(); //hide loader

                if(retval.status){

                    swal({
                        title:"Success!",
                        text: "Event Sucessfully Added.",
                        type: "success",
                        showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                        timer: <?php echo $this->config->item('timer') ?>
                    });

                    var full_url = window.location.href;
                    setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                    
                }else{
                    swal('Error','Internal error please contact admin','error');
                }
                
            });

        }

    }

    function validatePropertyVacantDates(start_date, end_date, target){
        var start_val = start_date.value;
        var end_val = end_date.value;
        var x = start_val.split("/");
        var y = end_val.split("/");
        var new_start_date = new Date(x[2], x[1], x[0]);
        var new_end_date = new Date(y[2], y[1], y[0]);

        var err = "";
        if(start_val != "" && end_val != ""){
            if(new_start_date > new_end_date && target == "start_date"){
                err = "Start Date must earlier than end date";
                start_date.value = "";
            }else if(new_end_date < new_start_date && target == "end_date"){
                err = "End Date must later than start date";
                end_date.value = "";
            }
        }

        if(err != ""){
            swal('Error', err, 'error');
            return false;
        }
    }

    $(document).ready(function(){

        // Set global var
        var agency_status = '<?php echo $job_row['a_status']; ?>';
        var job_id = <?php echo $job_row['jid'] ?? 0; ?>;
        var property_id = <?php echo $job_row['prop_id'] ?? 0; ?>;
        var agency_id = <?php echo $job_row['a_id'] ?? 0; ?>;

        <?php if ($this->session->flashdata('status') && $this->session->flashdata('status') == 'success') { ?>
            swal({
                title: "Success!",
                text: "<?php echo $this->session->flashdata('success_msg') ?>",
                type: "success",
                confirmButtonClass: "btn-success",
                showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                timer: <?php echo $this->config->item('timer') ?>
            });
        <?php } else if ($this->session->flashdata('status') && $this->session->flashdata('status') == 'error') { ?>
                    swal({
                        title: "Error!",
                        text: "<?php echo $this->session->flashdata('error_msg') ?>",
                        type: "error",
                        confirmButtonClass: "btn-danger"
                    });
        <?php } ?>
        
         //load tenants ajax box (via ajax)
         $('.loader_block_v2').show();
         $('.tenants_ajax_box').load('/jobs/tenants_ajax',{prop_id:<?php echo $job_row['prop_id'] ?>, job_id:<?php echo $job_row['jid'] ?>}, function(response, status, xhr){
            $('.loader_block_v2').hide();
            $('[data-toggle="tooltip"]').tooltip(); //init tooltip
            phone_mobile_mask(); //init phone/mobile mask
            //mobile_validation(); //init mobile validation
            //phone_validation(); //init phone validation
            //add_validate_tenant(); //init new tenant validation

        });

        jQuery("#job_entry_notice").change(function () {

            var checked = jQuery(this).prop("checked");
            var jp_check = jQuery("#job_priority").prop("checked");
            var tech_notes_txt = '';

            tech_notes_txt = getTechNotesFromTickBox();

            if (checked == true) {
                jQuery("#job_entry_notice_lbl").addClass("colorItRedBold");
            } else {
                jQuery("#job_entry_notice_lbl").removeClass("colorItRedBold");
            }
            jQuery("#tech_notes").val(tech_notes_txt);

        });

        jQuery("#job_priority").change(function () {

            var checked = jQuery(this).prop("checked");
            var jen_check = jQuery("#job_entry_notice").prop("checked");
            var tech_notes_txt = '';

            tech_notes_txt = getTechNotesFromTickBox();

            jQuery("#tech_notes").val(tech_notes_txt);

        });


        $('#update_job_details_form').submit(function(e){
            e.preventDefault();

            var form = $(this);
            var update_type = $(this).attr('id');

            var job_type = form.find('#job_type').val();
            var job_status = form.find('.job_status').val();
            var urgent_job = form.find('#urgent_job').prop('checked');
            var urgent_job_reason = form.find('#urgent_job_reason').val();
            var lockbox_code = form.find('.lockbox_code').val();
            var no_dates_provided = form.find('#no_dates_provided').prop('checked');
            var start_date = form.find('.start_date').val();
            var due_date = form.find('.due_date').val();
            var prop_vac = form.find('#prop_vac').prop('checked');
            var vacant_from = form.find('.vacant_from').val();
            var vacant_to = form.find('.vacant_to').val();
            var is_eo = form.find('#is_eo').prop('checked');
            var key_number = form.find('.key_number').val();
            var dha_need_processing = form.find('#dha_need_processing').prop('checked');        
            var preferred_alarm_id = form.find('.preferred_alarm_id').val();
            var alarm_code = form.find('.alarm_code').val();
            var allocate_notes = form.find('.allocate_notes').val();
            var escalate_job_reason = form.find('.escalate_job_reason').val();
            var escalate_job_reason_text = form.find('.escalate_job_reason option:selected').text();
            
            $('#load-screen').show(); //show loader
            jQuery.ajax({
                type: "POST",
                url: "/jobs/ajax_update_job_detail",
                dataType: 'json',
                data: {
                    update_type: update_type,
                    job_id: job_id,
                    prop_id: property_id,
                    job_type: job_type,
                    job_status: job_status,
                    urgent_job: urgent_job,
                    urgent_job_reason: urgent_job_reason,
                    lockbox_code: lockbox_code,
                    no_dates_provided: no_dates_provided,
                    start_date: start_date,
                    due_date: due_date,
                    prop_vac: prop_vac,
                    vacant_from: vacant_from,
                    vacant_to: vacant_to,
                    is_eo: is_eo,
                    key_number: key_number,
                    dha_need_processing: dha_need_processing,
                    preferred_alarm_id: preferred_alarm_id,
                    alarm_code: alarm_code,
                    allocate_notes: allocate_notes,
                    escalate_job_reason: escalate_job_reason,
                    escalate_job_reason_text: escalate_job_reason_text
                }

            }).done(function( retval ) {
                $('#load-screen').hide(); //hide loader
                if(retval.status){

                    swal({
                        title:"Success!",
                        text: "Job Successfully Updated",
                        type: "success",
                        showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                        timer: <?php echo $this->config->item('timer') ?>
                    });

                    var full_url = window.location.href;
                    setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                
                }else{
                    if( retval.error!="" ){
                        swal('Error',retval.error,'error');
                    }else{
                        swal('Error','Internal error please contact admin','error');
                    }
                }

            });
            
        })

        $('#update_job_booking_details_form').submit(function(e){

            e.preventDefault();

            var form = $(this);
            var update_type = form.attr('id');
            var job_status = form.find('.job_status').val();
            var job_date = form.find('.jobdate').val();
            var timeofday = form.find('.timeofday').val();
            var key_access_required = form.find('.key_access_required').prop("checked")
            var door_knock = form.find('.dk_lb').prop("checked")
            var key_access_details = form.find('.key_access_details').val();
            var key_number = form.find('.key_number').val();
            var lock_box = form.find('.lock_box_chkbox').prop("checked");
            var lockbox_code = form.find('.lockbox_code').val();
            var call_before = form.find('.call_before_chxbox').prop("checked")
            var call_before_txt = form.find('.call_before_txt').val();
            var booked_with = form.find('.booked_with').val();
            var booked_by = form.find('.booked_by').val();
            var comments = form.find('.comments').val();
            var techid = form.find('.techid').val();
            var job_entry_notice = form.find('.job_entry_notice').prop("checked")
            var job_priority = form.find('.job_priority').prop("checked")
            var tech_notes = form.find('.tech_notes').val();
            var allocate_notes = form.find('.allocate_notes').val();
            var escalate_job_reason = form.find('.escalate_job_reason').val();
            var escalate_job_reason_text = form.find('.escalate_job_reason option:selected').text();
            var vacant_start_date = form.find('.vacant_start_date').val();
            var vacant_end_date = form.find('.vacant_end_date').val();
            
            var objData = {
                update_type: update_type,
                job_id: job_id,
                prop_id: property_id,
                job_status: job_status,
                date: job_date,
                time_of_day: timeofday,
                key_access_required: key_access_required,
                door_knock: door_knock,
                key_access_details: key_access_details,
                key_number: key_number,
                lock_box: lock_box,
                lockbox_code: lockbox_code,
                call_before: call_before,
                call_before_txt: call_before_txt,
                booked_with: booked_with,
                booked_by: booked_by,
                comments: comments,
                assigned_tech: techid,
                job_entry_notice: job_entry_notice,
                job_priority: job_priority,
                tech_notes: tech_notes,
                allocate_notes: allocate_notes,
                escalate_job_reason: escalate_job_reason,
                escalate_job_reason_text: escalate_job_reason_text
            }
            
            if(job_status=="Booked"){
                jQuery.ajax({
                    type: "POST",
                    url: "/jobs/ajaxCheckKeyAccessAndVacantDate",
                    dataType: 'json',
                    data: {
                        key_access_required: key_access_required,
                        job_date: job_date,
                        vacant_start_date: vacant_start_date,
                        vacant_end_date: vacant_end_date
                    }

                }).done(function( retval ) {
                    
                    if(retval.status)
                    {
                        swal({
                            title: "Warning!",
                            text: "This job may not be vacant.",
                            type: "warning",
                            showCancelButton: false,
                            cancelButtonText: "Cancel!",
                            cancelButtonClass: "btn-danger",
                            confirmButtonClass: "btn-success",
                            confirmButtonText: "Proceed",
                            closeOnConfirm: false,
                        },
                        function(isConfirm) {
                            
                            ajax_update_booking_req(objData);

                        })

                    }else{
                        
                        ajax_update_booking_req(objData);

                    }

                });
            }else{
                ajax_update_booking_req(objData);
            }
            
        })


        $('#btn_update_notes').on('click',function(e){

            var update_type = $(this).attr('id');
            var prop_id = <?php echo $job_row['prop_id'] ?>;
            var prop_comments = $('#prop_comments').val();
            var orig_prop_comments = $('#orig_prop_comments').val();
            var repair_notes = $('#repair_notes').val();
            var orig_repair_notes = $('#orig_repair_notes').val();
            var tech_comments = $('#tech_comments').val();
            var orig_tech_comments = $('#orig_tech_comments').val();
            var not_compliant_notes = $('#not_compliant_notes').val();
            var orig_not_compliant_notes = $('#orig_not_compliant_notes').val();

            swal({
                title: "Warning!",
                text: "Update Job Details?",
                type: "warning",
                showCancelButton: true,
                cancelButtonText: "Cancel!",
                cancelButtonClass: "btn-danger",
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes",
                closeOnConfirm: false,
            },
            function(isConfirm) {

                if (isConfirm) { // yes

                        $('#load-screen').show(); //show loader
                        jQuery.ajax({
                            type: "POST",
                            url: "/jobs/ajax_update_job_detail",
                            dataType: 'json',
                            data: {
                                update_type: update_type,
                                job_id: <?php echo $job_row['jid'] ?>,
                                prop_id: prop_id,
                                agency_id: <?php echo $job_row['a_id'] ?>,
                                prop_comments: prop_comments,
                                orig_prop_comments: orig_prop_comments,
                                repair_notes: repair_notes,
                                orig_repair_notes: orig_repair_notes,
                                tech_comments: tech_comments,
                                orig_tech_comments: orig_tech_comments,
                                not_compliant_notes: not_compliant_notes,
                                orig_not_compliant_notes: orig_not_compliant_notes
                            }

                        }).done(function( retval ) {
                            $('#load-screen').hide(); //hide loader
                            if(retval.status){

                                swal({
                                    title:"Success!",
                                    text: "Job Successfully Updated",
                                    type: "success",
                                    showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                                    timer: <?php echo $this->config->item('timer') ?>
                                });

                                var full_url = window.location.href;
                                setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                            
                            }else{
                                if( retval.error!="" ){
                                    swal('Error',retval.error,'error');
                                }else{
                                    swal('Error','Internal error please contact admin','error');
                                }
                            }

                        });
                }

            })
        })

        $('#btn_mark_not_completed').on('click',function(e){

            var update_type = $(this).attr('id');
            var prop_id = <?php echo $job_row['prop_id'] ?>;
            var mark_as = $('#mark_as').val();
            var mark_as_comment = "";
            var selected_reason_text =  $( "#mark_as option:selected" ).text();

            if( mark_as == 25 ){  // Staff Sick
                mark_as_comment = 'Marked tech sick on <b><?php echo date('d/m/Y'); ?></b> by <b><?php echo $this->system_model->formatStaffName($staff_name,$staff_last_name); ?></b>';
            }else{
                mark_as_comment = "Job Not Completed Due to "+selected_reason_text;
            }

            swal({
                title: "Warning!",
                text: "Mark job not completed?",
                type: "warning",
                showCancelButton: true,
                cancelButtonText: "Cancel!",
                cancelButtonClass: "btn-danger",
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes",
                closeOnConfirm: false,
            },
            function(isConfirm) {

                if (isConfirm) { // yes

                        $('#load-screen').show(); //show loader
                        jQuery.ajax({
                            type: "POST",
                            url: "/jobs/ajax_update_job_detail",
                            dataType: 'json',
                            data: {
                                update_type: update_type,
                                job_id: <?php echo $job_row['jid'] ?>,
                                prop_id: prop_id,
                                mark_as: mark_as,
                                mark_as_comment: mark_as_comment,
                                mark_not_completed_door_knock: <?php echo $job_row['door_knock'] ?>
                            }

                        }).done(function( retval ) {
                            $('#load-screen').hide(); //hide loader
                            if(retval.status){

                                swal({
                                    title:"Success!",
                                    text: "Job Not Completed Successfully Updated",
                                    type: "success",
                                    showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                                    timer: <?php echo $this->config->item('timer') ?>
                                });

                                var full_url = window.location.href;
                                setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                            
                            }else{
                                if( retval.error!="" ){
                                    swal('Error',retval.error,'error');
                                }else{
                                    swal('Error','Internal error please contact admin','error');
                                }
                            }

                        });
                }

            })
        })

        jQuery("#btn_check_distance_to_agency").click(function (e) {
            e.preventDefault();

            jQuery("#load-screen").show();
            jQuery.ajax({
                type: "POST",
                url: "/jobs/ajax_get_distance_to_agency",
                dataType: 'json',
                data: {
                    property_id: "<?php echo $job_row['prop_id']; ?>",
                    agency_id: "<?php echo $job_row['a_id']; ?>"
                }
            }).done(function (retval) {

                if(retval.status){

                    $('#load-screen').hide(); //hide loader

                    jQuery("#distance_to_agency_span").html(retval.distance);

                }else{
                    if( retval.error!="" ){
                        swal('Error',retval.error,'error');
                    }else{
                        swal('Error','Internal error please contact admin','error');
                    }
                }
                

            });

        });

        jQuery("#sync_alarm_btn").click(function () {

            swal({
                title: "Warning!",
                text: "Sync Smoke Alarms Only?",
                type: "warning",
                showCancelButton: true,
                cancelButtonText: "Cancel!",
                cancelButtonClass: "btn-danger",
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes",
                closeOnConfirm: false,
            },
            function(isConfirm) {

                if (isConfirm) { // yes

                    $('#load-screen').show(); //show loader

                    jQuery.ajax({
                        type: "POST",
                        url: "/jobs/ajax_sync_smoke_alarms",
                        dataType: 'json',
                        data: {
                            job_id: job_id,
                            property_id: property_id
                        }

                    }).done(function( retval ) {
                        $('#load-screen').hide(); //hide loader

                        if(retval.status){

                            swal({
                                title:"Success!",
                                text: "Sync Smoke Alarms Success",
                                type: "success",
                                showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                                timer: <?php echo $this->config->item('timer') ?>
                            });

                            var full_url = window.location.href;
                            setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                            
                        }else{
                            swal('Error','Internal error please contact admin','error');
                        }
                        
                    });
                }

            });

        });

        jQuery("#recreate_bundle_services_btn").click(function () {

            var ajt_id = <?php echo $job_row['j_service'] ?>;

            if (parseInt(job_id) > 0 && parseInt(ajt_id) > 0) {

                swal({
                    title: "Warning!",
                    text: "You are about to reacreate bundle services, are you you want to proceed?",
                    type: "warning",
                    showCancelButton: true,
                    cancelButtonText: "Cancel!",
                    cancelButtonClass: "btn-danger",
                    confirmButtonClass: "btn-success",
                    confirmButtonText: "Yes",
                    closeOnConfirm: false,
                },
                function(isConfirm) {

                    if (isConfirm) { // yes

                        $('#load-screen').show(); //show loader

                        jQuery.ajax({
                            type: "POST",
                            url: "/jobs/ajax_recreate_bundle_services",
                            dataType: 'json',
                            data: {
                                job_id: job_id,
                                ajt_id: ajt_id
                            }

                        }).done(function( retval ) {
                            $('#load-screen').hide(); //hide loader

                            if(retval.status){

                                swal({
                                    title:"Success!",
                                    text: "Reacreate Bundle Service Success",
                                    type: "success",
                                    showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                                    timer: <?php echo $this->config->item('timer') ?>
                                });

                                var full_url = window.location.href;
                                setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                                
                            }else{
                                swal('Error','Internal error please contact admin','error');
                            }
                            
                        });
                    }

                });

            }

        });

        jQuery("#sms_to_conf_book").click(function () {

            var booked_with = jQuery("#booked_with").val();

            // get booked with tenant via finding the same name on tenants panel
            var booked_with_tenant_name_node = jQuery(".tenant_fname_field[value='" + booked_with + "']");
            // get booked with tenant value
            var booked_with_tenant_name = booked_with_tenant_name_node.val();
            // get booked with tenant mobile by finding mobile number on the same row
            var booked_with_tenant_mob = booked_with_tenant_name_node.parents("tr:first").find(".tenant_mobile_field").val();

            console.log("booked_with: "+booked_with);
            console.log("booked_with_tenant_name: "+booked_with_tenant_name);
            console.log("booked_with_tenant_mob: "+booked_with_tenant_mob);

            if (booked_with != '') {

                swal({
                title: "Warning!",
                text: "Are you sure you want to proceed?",
                type: "warning",
                showCancelButton: true,
                cancelButtonText: "Cancel!",
                cancelButtonClass: "btn-danger",
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes",
                closeOnConfirm: false,
            },
            function(isConfirm) {

                if (isConfirm) { // yes

                        $('#load-screen').show(); //show loader
                        jQuery.ajax({
                            type: "POST",
                            url: "/jobs/ajax_send_confirmed_booking_sms_tt",
                            dataType: 'json',
                            data: {
                                job_id: job_id,
                                prop_id: property_id,
                                booked_with_tenant_name: booked_with_tenant_name,
                                booked_with_tenant_mob: booked_with_tenant_mob
                            }

                        }).done(function( retval ) {
                            $('#load-screen').hide(); //hide loader
                            if(retval.status){

                                swal({
                                    title:"Success!",
                                    text: "Confirm Booking SMS Sent",
                                    type: "success",
                                    showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                                    timer: <?php echo $this->config->item('timer') ?>
                                });

                                var full_url = window.location.href;
                                setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                            
                            }else{
                                if( retval.error!="" ){
                                    swal('Error',retval.error,'error');
                                }else{
                                    swal('Error','Internal error please contact admin','error');
                                }
                            }

                        });
                }

            });


            } else {
                alert("Select Tenants in booked with dropdown to be sent SMS with");
            }


        });

        // upload invoice/bill to pme
        jQuery("#upload_invoice_bill_to_pme_btn").click(function () {

            var is_uploaded_to_api = $('#orig_is_pme_invoice_upload').val();
            var confirm_message = '';

            if (parseInt(job_id) > 0) {

                if( is_uploaded_to_api == '1' ){
                    confirm_message = 'This has already been uploaded to PropertyMe, do you want to upload again?';
                }else{
                    confirm_message = 'You are about to upload invoice and create bill on PropertyMe, do you want to proceed?';
                }

                swal({
                    title: "Warning!",
                    text: confirm_message,
                    type: "warning",
                    showCancelButton: true,
                    cancelButtonText: "Cancel!",
                    cancelButtonClass: "btn-danger",
                    confirmButtonClass: "btn-success",
                    confirmButtonText: "Yes",
                    closeOnConfirm: false,
                },
                function(isConfirm) {

                    if (isConfirm) { // yes
                        swal.close(); // Close swal to prevent multiple click in button
                        $('#load-screen').show(); //show loader to prevent multiple button click

                        var site_link = jQuery(location).attr('href');
                        window.location = "/property_me/send_all_certificates_and_invoices_via_vjd/?job_id="+job_id+"&url="+site_link;
                    
                    }

                });

            }

        });

        // upload invoice/bill to pme
        jQuery("#upload_invoice_bill_to_palace_btn").click(function () {

            var is_uploaded_to_api = $('#orig_is_palace_invoice_upload').val();
            var confirm_message = '';

            if (parseInt(job_id) > 0) {
                
                if( is_uploaded_to_api == '1' ){
                    confirm_message = 'This has already been uploaded to Palace, do you want to upload again?';
                }else{
                    confirm_message = 'You are about to upload invoice and create bill on Palace, do you want to proceed?';
                }

                swal({
                    title: "Warning!",
                    text: confirm_message,
                    type: "warning",
                    showCancelButton: true,
                    cancelButtonText: "Cancel!",
                    cancelButtonClass: "btn-danger",
                    confirmButtonClass: "btn-success",
                    confirmButtonText: "Yes",
                    closeOnConfirm: false,
                },
                function(isConfirm) {

                    if (isConfirm) { // yes

                        var site_link = jQuery(location).attr('href');
                        window.location = "/palace/send_all_certificates_and_invoices_via_vjd/?job_id="+job_id+"&url="+site_link;
                    
                    }

                });

            }

        });

        // upload invoice/bill to console
        jQuery("#upload_invoice_bill_to_console_btn").click(function () {

            var is_uploaded_to_api = $('#console_api_has_data').val();
            var confirm_message = '';

            if (parseInt(job_id) > 0) {

                if( is_uploaded_to_api > 0 ){
                    confirm_message = 'This has already been uploaded to Console, do you want to upload again?';
                }else{
                    confirm_message = 'You are about to upload invoice and create bill on Console, do you want to proceed?';
                }

                swal({
                    title: "Warning!",
                    text: confirm_message,
                    type: "warning",
                    showCancelButton: true,
                    cancelButtonText: "Cancel!",
                    cancelButtonClass: "btn-danger",
                    confirmButtonClass: "btn-success",
                    confirmButtonText: "Yes",
                    closeOnConfirm: false,
                },
                function(isConfirm) {

                    if (isConfirm) { // yes

                        window.location = "/console/upload_invoice_and_certificate/?job_id="+job_id;
                    
                    }

                });

            }

        });


        jQuery("#move_job_property_id").keyup(function () {

            var property_id = jQuery(this).val();
            var txt = '';

            jQuery.ajax({
                type: "POST",
                url: "/jobs/ajax_get_property",
                data: {
                    property_id: property_id
                }
            }).done(function (ret) {
                if (ret != '') {
                    txt = ret;
                    jQuery("#btn_move").show();
                } else {
                    txt = 'Property not found';
                    jQuery("#btn_move").hide();
                }
                jQuery("#search_prop_display").html(txt);
            });

        });


        jQuery("#btn_move").click(function () {

            var new_property_id = jQuery("#move_job_property_id").val();
            var old_prop_id = property_id;

            $('#load-screen').show(); //show loader
            jQuery.ajax({
                type: "POST",
                url: "/jobs/ajax_move_job_to_property",
                dataType: 'json',
                data: {
                    job_id: job_id,
                    property_id: new_property_id,
                    old_prop_id: old_prop_id
                }
            }).done(function (retval) {
                //window.location = "/properties/details/?id=" + old_prop_id + "&job_moved=1";
                $('#load-screen').hide(); //hide loader
                if(retval.status){

                    swal({
                        title:"Success!",
                        text: "Job Successfully Moved",
                        type: "success",
                        showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                        timer: <?php echo $this->config->item('timer') ?>
                    });

                    var full_url = window.location.href;
                    setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                
                }else{
                    if( retval.error!="" ){
                        swal('Error',retval.error,'error');
                    }else{
                        swal('Error','Internal error please contact admin','error');
                    }
                }
            });

        });


        jQuery("#btn_del_job_temp").click(function () {

            // invoice payment check
            jQuery.ajax({
                type: "POST",
                url: "/jobs/ajax_invoice_payment_check",
                data: {
                    job_id: job_id
                }
            }).done(function (ret) {

                var inv_pay_count = parseInt(ret);

                if( inv_pay_count > 0 ){
                    swal("Error","This job cannot be deleted as it has an attached payment.",'error')
                }else{

                    swal({
                        title: "Warning!",
                        text: "Are you sure you want to delete job?",
                        type: "warning",
                        showCancelButton: true,
                        cancelButtonText: "Cancel!",
                        cancelButtonClass: "btn-danger",
                        confirmButtonClass: "btn-success",
                        confirmButtonText: "Yes",
                        closeOnConfirm: false,
                    },
                    function(isConfirm) {

                        if (isConfirm) { // yes

                            $('#load-screen').show(); //show loader

                            jQuery.ajax({
                                type: "POST",
                                url: "/jobs/ajax_delete_job",
                                dataType: 'json',
                                data: {
                                    job_id: job_id,
                                    property_id: property_id,
                                    job_type: "<?php echo $job_row['j_type'] ?>",
                                    service: "<?php echo $job_row['j_service'] ?>"
                                }

                            }).done(function( retval ) {
                                $('#load-screen').hide(); //hide loader

                                if(retval.status){

                                    swal({
                                        title:"Success!",
                                        text: "Job Sucessfully Deleted.",
                                        type: "success",
                                        showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                                        timer: <?php echo $this->config->item('timer') ?>
                                    });

                                    var full_url = window.location.href;
                                    setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                                    
                                }else{
                                    swal('Error','Internal error please contact admin','error');
                                }
                                
                            });
                        
                        }

                    });

                }


            });

        });

        jQuery("#btn_restore_job").click(function () {
            swal({
                        title: "Warning!",
                        text: "Are you sure you want to restore job?",
                        type: "warning",
                        showCancelButton: true,
                        cancelButtonText: "Cancel!",
                        cancelButtonClass: "btn-danger",
                        confirmButtonClass: "btn-success",
                        confirmButtonText: "Yes",
                        closeOnConfirm: false,
                    },
                    function(isConfirm) {

                        if (isConfirm) { // yes

                            $('#load-screen').show(); //show loader

                            jQuery.ajax({
                                type: "POST",
                                url: "/jobs/ajax_restore_jobs",
                                dataType: 'json',
                                data: {
                                    job_id: job_id
                                }

                            }).done(function( retval ) {
                                $('#load-screen').hide(); //hide loader

                                if(retval.status){

                                    swal({
                                        title:"Success!",
                                        text: "Job Sucessfully Restored.",
                                        type: "success",
                                        showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                                        timer: <?php echo $this->config->item('timer') ?>
                                    });

                                    var full_url = window.location.href;
                                    setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                                    
                                }else{
                                    swal('Error','Internal error please contact admin','error');
                                }
                                
                            });
                        
                        }

                    });
        });


        $('#add-log').click(function(){

            add_job_event_log();

        });

        // allow enter key to add job event log
        jQuery("#joblog-comments").keypress(function(e){

            if(e.which == 13) { // enter key
                add_job_event_log();
            }

        });


        jQuery(".confirm_discard_yes_btn").click(function(){
            
            var node = $(this).parents('.ss_discarded_fb');
            var discard_reason = node.find('.ss_discard_reason').val();
            var discard_id = node.find('.discard_ss_id').val(); 

            var err="";

            if( discard_reason=="" ){
                err+="Discard reason is required\n";
            }

            if( err!="" ){
                swal('Error',err,'error');
                return false;
            }

            $('#load-screen').show(); //show loader

            jQuery.ajax({
                type: "POST",
                url: "/jobs/ajax_discard_safety_switch",
                dataType: 'json',
                data: {
                    job_id: job_id,
                    ss_id: discard_id,
                    ss_discard_reason: discard_reason
                }

            }).done(function( retval ) {
                $('#load-screen').hide(); //hide loader

                if(retval.status){

                    swal({
                        title:"Success!",
                        text: "Safety Switch Sucessfully Discarded.",
                        type: "success",
                        showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                        timer: <?php echo $this->config->item('timer') ?>
                    });

                    var full_url = window.location.href;
                    setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                    
                }else{
                    swal('Error','Internal error please contact admin','error');
                }
                
            });

        })

        /* Disabled as per Dan's request
        $('#btn_email_quote').click(function(){
            var quote_email_to = $('#quote_email_to').val();
            
            if(quote_email_to==""){
                swal('Error','Please Enter Quote Email','error');
                return false;
            }

            swal({
                title: "Warning!",
                text: "Are you sure you want to proceed?",
                type: "warning",
                showCancelButton: true,
                cancelButtonText: "Cancel!",
                cancelButtonClass: "btn-danger",
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes",
                closeOnConfirm: false,
            },
            function(isConfirm) {

                if (isConfirm) { // yes

                    $('#load-screen').show(); //show loader

                    jQuery.ajax({
                        type: "POST",
                        url: "/jobs/ajax_email_quote_to_agency",
                        dataType: 'json',
                        data: {
                            job_id: job_id,
                            prop_id: property_id,
                            quote_email_to: quote_email_to
                        }

                    }).done(function( retval ) {
                            $('#load-screen').hide(); //hide loader
                            if(retval.status){

                                swal({
                                    title:"Success!",
                                    text: "Quote Email Sent",
                                    type: "success",
                                    showConfirmButton: <?php // echo $this->config->item('showConfirmButton') ?>,
                                    timer: <?php // echo $this->config->item('timer') ?>
                                });

                                var full_url = window.location.href;
                                setTimeout(function(){ window.location=full_url }, <?php // echo $this->config->item('timer') ?>);
                            
                            }else{
                                if( retval.error!="" ){
                                    swal('Error',retval.error,'error');
                                }else{
                                    swal('Error','Internal error please contact admin','error');
                                }
                            }

                        });
                
                }

            });
        })
        */

        jQuery(".en_date_issued_dp").change(function () {

            var en_date_issued = jQuery(this).val();

            if (parseInt(job_id) > 0) {

                jQuery("#load-screen").show();
                jQuery.ajax({
                    type: "POST",
                    url: "/jobs/ajax_update_en_date_issued",
                    data: {
                        job_id: job_id,
                        en_date_issued: en_date_issued
                    }
                }).done(function (ret) {
                    jQuery("#load-screen").hide();
                    swal({
                        title:"Success!",
                        text: "EN Date Issued",
                        type: "success",
                        showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                        timer: <?php echo $this->config->item('timer') ?>
                    });

                    var full_url = window.location.href;
                    setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                });

            }

        });


        $('.email-entry-notice').click(function(e){

            e.preventDefault();
            var email_to_type = $(this).attr('data-email_to_type');

            swal({
                title: "Warning!",
                text: "Are you sure you want to email the entry notice?",
                type: "warning",
                showCancelButton: true,
                cancelButtonText: "Cancel!",
                cancelButtonClass: "btn-danger",
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes",
                closeOnConfirm: false,
            },
            function(isConfirm) {

                if (isConfirm) { // yes

                    $('#load-screen').show(); //show loader

                    jQuery.ajax({
                        type: "POST",
                        url: "/jobs/ajax_sendEntryNoticeEmail",
                        dataType: 'json',
                        data: {
                            job_id: job_id,
                            email_to_type: email_to_type // 1 = tenant only 2 = tenant+agency
                        }

                    }).done(function( retval ) {
                        $('#load-screen').hide(); //hide loader

                        if(retval.status){

                            swal({
                                title:"Success!",
                                text: "Entry noticed successfully emailed",
                                type: "success",
                                showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                                timer: <?php echo $this->config->item('timer') ?>
                            });

                            var full_url = window.location.href;
                            setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                            
                        }else{
                            swal('Error','Internal error please contact admin','error');
                        }
                        
                    });
                
                }

            });

        })

        // show hide tenant details script
        jQuery("#btn_show_tenant").click(function () {

            jQuery(".tenant_details_div").toggle('slow');

            if( $(this).html()=="Show" ){
                $(this).html('Hide');
            }else{
                $(this).html('Show');
            }

        });

        $('.log_table').DataTable({
            responsive: true,
            searching: false,
            "dom": 'rtip',
            order: [0, 'desc']
        });

        //job price breakdown toogle
        $('.toggle_job_price_breakdown').click(function(e){
            e.preventDefault();
            var obj = $(this);
            var target_box = obj.parents('.ajax_price_toogle_main_box').next('.ajax_load_price_detail');

            jQuery.ajax({
                type: "POST",
                url: "/jobs/ajax_get_vjd_price_details",
                data: {
                    job_id: job_id
                }
            }).done(function(response){
                //Empty all ajax price detail box first to get rid of duplicate content/fields
                $('.ajax_load_price_detail').html("");
                //Load response
                target_box.html(response);
            });
        })
        //job price breakdown toogle end

        $('#invoice_pdfs_select').change(function(){
            var link = $('#invoice_pdfs_select option:selected').attr('data-link');
            window.open(link,'_blank');
        })

        //key access js
        chckbox_show_hide_div($('.key_access_required'),'.key_access_details_div'); // run script on load
        $(".key_access_required").on('click', function(){ // run on click event
            var node = $(this);
            chckbox_show_hide_div(node,'.key_access_details_div');
        })
        //key access js end

        $('.lock_box_chkbox').click(function(e){
            var node = $(this);
            if(node.is(':checked')){
                $('.lockbox_code_box').show();
            }else{
                $('.lockbox_code_box').hide();
            }
        })

        //call before js
        chckbox_show_hide_div($('#call_before_chxbox'),'#call_before_txt') // run on load
        $("#call_before_chxbox").on('click', function(){
            var node = $(this);

            chckbox_show_hide_div(node,'#call_before_txt');

        })
        //call before js end

        //Property vacant toggle
        chckbox_show_hide_div($('#prop_vac'),'.prop_vacant_dates');
        $("#prop_vac").on('click', function(){ // run on click event
            var node = $(this);
            chckbox_show_hide_div(node,'.prop_vacant_dates');
        })
        //Property vacant toggle end

        //Book With dropdown get phone number on change
        booked_with_and_sms_tweak();
        $('#booked_with').change(function(){
            booked_with_and_sms_tweak();
        })
        //Book With dropdown get phone number on change end

        $('#agency_specific_notes_form').submit(function(e){
            e.preventDefault();
            
            var form = $(this);
            var agency_comments = form.find("#agency_comments").val();
            var agency_hours = form.find("#agency_hours").val();
            var agency_specific_notes = form.find('#agency_specific_notes').val();
            var update_type = form.attr('id');
            var action_url = form.attr('action');

            $('#load-screen').show(); //show loader
            jQuery.ajax({
                type: "POST",
                url: action_url,
                dataType: 'json',
                data: {
                    update_type: update_type,
                    job_id: job_id,
                    agency_id: agency_id,
                    agency_specific_notes: agency_specific_notes,
                    agency_comments: agency_comments,
                    agency_hours: agency_hours
                }

            }).done(function( retval ) {
                $('#load-screen').hide(); //hide loader

                if(retval.status){

                    swal({
                        title:"Success!",
                        text: "Agency specific notes successfully updated",
                        type: "success",
                        showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                        timer: <?php echo $this->config->item('timer') ?>
                    });

                    var full_url = window.location.href;
                    setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                    
                }else{
                    swal('Error','Internal error please contact admin','error');
                }
                
            });
        })

        $('#btn_update_work_order').click(function(e){
            e.preventDefault();
            
            var work_order = $('#work_order').val();
            var update_type = $(this).attr('id');

            $('#load-screen').show(); //show loader
            jQuery.ajax({
                type: "POST",
                url: '/jobs/ajax_update_job_detail',
                dataType: 'json',
                data: {
                    update_type: update_type,
                    job_id: job_id,
                    work_order: work_order
                }

            }).done(function( retval ) {
                $('#load-screen').hide(); //hide loader

                if(retval.status){

                    swal({
                        title:"Success!",
                        text: "Job successfully updated",
                        type: "success",
                        showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                        timer: <?php echo $this->config->item('timer') ?>
                    });

                    var full_url = window.location.href;
                    setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                    
                }else{
                    swal('Error','Internal error please contact admin','error');
                }
                
            });
        })

        $('#btn_prop_upgraded_to_ic_sa').click(function(e){
            e.preventDefault();
            
            var prop_upgraded_to_ic_sa = $('.prop_upgraded_to_ic_sa').val();
            var update_type = $(this).attr('id');

            $('#load-screen').show(); //show loader
            jQuery.ajax({
                type: "POST",
                url: '/jobs/ajax_update_job_detail',
                dataType: 'json',
                data: {
                    update_type: update_type,
                    job_id: job_id,
                    prop_id: property_id,
                    prop_upgraded_to_ic_sa: prop_upgraded_to_ic_sa
                }

            }).done(function( retval ) {
                $('#load-screen').hide(); //hide loader

                if(retval.status){

                    swal({
                        title:"Success!",
                        text: "Job successfully updated",
                        type: "success",
                        showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                        timer: <?php echo $this->config->item('timer') ?>
                    });

                    var full_url = window.location.href;
                    setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                    
                }else{
                    swal('Error','Internal error please contact admin','error');
                }
                
            });
        })

        $('#btn_update_pref_time').click(function(e){
            e.preventDefault();
            
            var update_type = $(this).attr('id');
            var preferred_time = $('.preferred_time').val();
            var out_of_tech_hours = $('#out_of_tech_hours').prop('checked');

            $('#load-screen').show(); //show loader
            jQuery.ajax({
                type: "POST",
                url: '/jobs/ajax_update_job_detail',
                dataType: 'json',
                data: {
                    update_type: update_type,
                    job_id: job_id,
                    prop_id: property_id,
                    preferred_time: preferred_time,
                    out_of_tech_hours: out_of_tech_hours
                }

            }).done(function( retval ) {
                $('#load-screen').hide(); //hide loader

                if(retval.status){

                    swal({
                        title:"Success!",
                        text: "Job successfully updated",
                        type: "success",
                        showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                        timer: <?php echo $this->config->item('timer') ?>
                    });

                    var full_url = window.location.href;
                    setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                    
                }else{
                    swal('Error','Internal error please contact admin','error');
                }
                
            });
        })

        $('#update_allocate_form').submit(function(e){
            e.preventDefault();

            var form = $(this);
            var url = form.attr('action');
            var allocate_note = form.find('.allocate_notes').val();
            var allocate_response = form.find('.allocate_response').val();

            $('#load-screen').show(); //show loader
            jQuery.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                data: {
                    job_id: job_id,
                    prop_id: property_id,
                    allocate_note: allocate_note,
                    allocate_response: allocate_response
                }

            }).done(function( retval ) {
                $('#load-screen').hide(); //hide loader

                if(retval.status){

                    swal({
                        title:"Success!",
                        text: "Allocate Saved",
                        type: "success",
                        showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                        timer: <?php echo $this->config->item('timer') ?>
                    });

                    var full_url = window.location.href;
                    setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                    
                }else{
                    swal('Error','Internal error please contact admin','error');
                }
                
            });
            
        })

        //allocate,escalate tweak start
        /*var status_val = $('.job_status').val();
        if(status_val=="Allocate"){
            $('.allocate_notes_main_box').show();
        }else{
            $('.allocate_notes_main_box').hide();
        }*/
        $('.job_status').change(function(){

            var node = $(this);
            var parent_form  = node.parents('.update_job_detail_form');
            var allocate_notes_box  = parent_form.find('.allocate_notes_main_box');
            var escalate_reason_main_box  = parent_form.find('.escalate_reason_main_box');

            if(node.val()=="Allocate"){
                allocate_notes_box.slideDown();
            }else{
                allocate_notes_box.slideUp();
            }

            if(node.val()=="Escalate"){
                escalate_reason_main_box.slideDown();
            }else{
                escalate_reason_main_box.slideUp();
            }

        })
        //allocate,escalate tweak end

        //email logs email data on fancybox
        jQuery(".sent_email_alink").click(function () {

            var obj = jQuery(this);
            var job_log_id = obj.parents("tr:first").find(".job_log_id").val();
            var log_id = obj.parents("tr:first").find(".log_id").val();

            jQuery("#load-screen").show();
            jQuery.ajax({
                type: "POST",
                cache: false,
                url: "/jobs/ajax_get_email_sent_data",
                data: {
                    job_log_id: job_log_id,
                    log_id: log_id
                }
            }).done(function (ret) {

                jQuery("#load-screen").hide();

                $.fancybox.open(ret);

            });

            return false;

        });
        //email logs email data on fancybox end


        //delete log
        jQuery(".btn_delete_log").click(function () {

            var obj = jQuery(this);
            var job_log_id = obj.parents("tr:first").find(".job_log_id").val();
            var log_id = obj.parents("tr:first").find(".log_id").val();

            swal({
                title: "Warning!",
                text: "Are you sure you want to delete log?",
                type: "warning",
                showCancelButton: true,
                cancelButtonText: "Cancel!",
                cancelButtonClass: "btn-danger",
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes",
                closeOnConfirm: false,
            },
            function(isConfirm) {

                if (isConfirm) { // yes

                    jQuery.ajax({
                        type: "POST",
                        url: "/jobs/ajax_delete_job_log",
                        dataType: 'json',
                        data: {
                            job_id: job_id,
                            job_log_id: job_log_id,
                            log_id: log_id
                        }
                    }).done(function (ret) {

                        jQuery("#load-screen").hide();

                        if(ret.status){
                            swal({
                                title:"Success!",
                                text: "Log successfully deleted",
                                type: "success",
                                showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                                timer: <?php echo $this->config->item('timer') ?>
                            });
                            var full_url = window.location.href;
                            setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);

                        }else{
                            swal('Error','Internal error please contact admin','error');
                        }

                    });

                }

            });

        });

        // Script text selection tweak
        $('.btn-script').on('click', function()
        {
            var obj = $(this);
            var target = obj.attr('data-target');
            var btn_label = obj.attr('data-label');

            // Hide all script text box first
            $('.script_textbox').hide();

            // Set all button label to orig
           // obj.html(btn_label);

            if(obj.html() == btn_label){

                // Put back original label to all buttons first
                $('.btn-script').each(function(){
                    $(this).html($(this).attr('data-label'));
                })

                // Show target script text
                $(target).show();

                // Update button label to cancel on clicked
                obj.html("Cancel");

            }else{
                $(target).hide();
                obj.html(btn_label);

                // show main script text
                $('.main_script_text').show();
            }

        })


    }); //document ready end

</script>