<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<?php echo form_open('/jobs/ajax_update_job_detail', array('id'=>'vjd_phone_booking_form','class'=>'update_job_detail_form')); ?>
    <section class="card card-blue-fill">
        <div class="card-block">
            
            <div class="job_details_box job_details_box_tab">

                <div class="form-group booking-group phone_booking_tab_content en_notice_tab_content dk_tab_content">
                    <label class="txt-bold">Date <span class="text-red">*</span></label>
                    <input value="<?php echo ($this->system_model->isDateNotEmpty($job_row['j_date'])) ? $this->system_model->formatDate($job_row['j_date'],'d/m/Y') : ''; ?>" type="text" id="jobdate_phone_booking" value="" class="addinput vw-jb-inpt jobdate_phone_booking flatpickr_vjd hasDatepicker form-control" placeholder="DD/MM/YYYY" style="width:125px;">
                </div>

                <div class="form-group booking-group phone_booking_tab_content">
                    <label class="txt-bold">Time of Day <span class="text-red">*</span> <?php echo ($job_row['allow_dk'] == 1 && $job_row['no_dk'] == 0) ? null : "<small class='text-red'>NO DKs ALLOWED</small>"; ?></label>
                    <input value="<?php echo $job_row['time_of_day']; ?>" type="text" id="timeofday_phone_booking" class="form-control timeofday_phone_booking">
                </div>

                <div class="form-group booking-group phone_booking_tab_content en_notice_tab_content">
                    <div class="checkbox">
                        <input value="1" type="checkbox" id="lock_box_chkbox_phone_booking" class="lock_box_chkbox_phone_booking" value="1" <?php echo ($job_row['code']!='') ? "checked" : null; ?> >
                        <label for="lock_box_chkbox_phone_booking">Lock Box</label>
                    </div>
                    <div class="lockbox_code_box_phone_booking" style="display:<?php echo ($job_row['code']!='') ? 'block;' : 'none;'; ?>">
                        <label>Lockbox Code</label>
                        <input value="<?php echo $job_row['code'] ?>" id="lockbox_code_phone_booking" class="form-control lockbox_code_phone_booking">
                        <input type="hidden" class="orig_lockbox_code" value="<?php echo $job_row['code'] ?>">
                    </div>
                </div>

                <?php  
                /**
                 * Show only key access relevant fields/input if allowed and has keys
                 * AND Entry Notice is allowed
                 */
                if (($job_row['key_allowed'] == 1 && $job_row['no_keys'] != 1) && $job_row['no_en'] != 1) { 
                ?>
                <div class="form-group booking-group phone_booking_tab_content">
                    <div class="checkbox">
                        <input type="checkbox" id="key_access_required_phone_booking" class="key_access_required_phone_booking" value="1" <?php echo ($job_row['key_access_required']==1) ? "checked" : null; ?>>
                        <label for="key_access_required_phone_booking">Key Access</label>
                    </div>

                    <div class="key_access_details_div">
                        <div class="form-group">
                            <label>Authorised By</label>
                            <input type="text" value="<?php echo $job_row['key_access_details'] ?>" id="key_access_details_phone_booking" class="tenantinput addinput form-control" placeholder="Authorised By">
                        </div>
                        <div class="form-group">
                            <label>Key Number</label>
                            <input maxlength="20" id="key_number_phone_booking" class="form-control" maxlength="10" value="<?php echo $job_row['key_number']; ?>">
                        </div>
                    </div>

                    <input type="hidden" name="orig_key_access_required" id="orig_key_access_required" value="<?php echo $job_row['key_access_required']; ?>">
                    <input type="hidden" name="orig_key_access_details" id="orig_key_access_details" value="<?php echo $job_row['key_access_details']; ?>">
                </div>
                <?php } ?>

                <div class="form-group booking-group phone_booking_tab_content">
                    <div class="checkbox">
                        <input type="checkbox" id="call_before_chxbox_phone_booking" class="call_before_chxbox_phone_booking" value="1" <?php echo ($job_row['call_before']==1) ? "checked" : null; ?> >
                        <label for="call_before_chxbox_phone_booking">Call Before</label>
                    </div>
                    <input value="<?php echo $job_row['call_before_txt']; ?>" type="text" maxlength="6" id="call_before_txt_booking" class="form-control call_before_txt_booking" placeholder="Call Before" >
                    
                    <input type="hidden" name="orig_call_before" id="orig_call_before" value="<?php echo $job_row['call_before']; ?>">
                    <input type="hidden" name="orig_call_before_txt" id="orig_call_before_txt" value="<?php echo $job_row['call_before_txt']; ?>">
                </div>

                <div class="form-group">
                    <label class="txt-bold">Run Sheet Notes</label>
                    <div>
                        <div class="left" style="margin-right:15px;">
                            <div class="checkbox">
                                <input type="checkbox" id="job_entry_notice_phone_booking" class="run_sheet_notes_chk1 job_entry_notice" data-flag="1" value="1" <?php echo ( $job_row['job_entry_notice']==1) ?'checked':null; ?>>
                                <label for="job_entry_notice_phone_booking">Entry Notice</label>
                            </div>
                        </div>
                        <div class="left">
                            <div class="checkbox">
                                <input type="checkbox" id="job_priority_phone_booking" class="run_sheet_notes_chk2 job_priority" data-flag="2" <?php echo ( $job_row['job_priority']==1) ?'checked':null; ?>>
                                <label for="job_priority_phone_booking">Do Not Cancel</label>
                            </div>
                        </div>
                    </div>
                    <input type="text" id="tech_notes_phone_booking" maxlength="15" value="<?php echo $job_row['tech_notes'] ?>" class="form-control tech_notes">
                </div>

                <div class="form-group booking-group phone_booking_tab_content">
                    <label class="txt-bold">Booked With <span class="text-red">*</span></label>
                    <select id="booked_with_phone_booking" class="form-control booked_with_phone_booking">
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

                <div class="form-group booking-group">
                    <label class="txt-bold">Technician <span class="text-red">*</span></label>
                    <select id="techid_phone_booking" class="form-control techid_phone_booking">
                        <option value="">-- Select --</option>
                        <?php 
                        if ($job_row['assigned_tech'] != '') {
                            $sel_tech = $job_row['assigned_tech'];
                        } else if ($job_row['assigned_tech'] == '' && $this->input->get_post('tr_tech_id') != '') {
                            $sel_tech = $this->input->get_post('tr_tech_id');
                        }
                        foreach( $technician->result_array() as $technician_row ){ 
                            $sel_tech_row = ( $technician_row['StaffID']==$sel_tech ) ? 'selected' : null;
                            $tech_red_color = ($technician_row['StaffID']==1 || $technician_row['StaffID']==2) ? 'text-red' : null;
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
                </div>

                <div class="form-group booking-group phone_booking_tab_content en_notice_tab_content dk_tab_content">
                    <label class="txt-bold">Booked By <span class="text-red">*</span></label>
                    <div class="row">
                        <div class="col-md-12">
                            <select id="booked_by_phone_booking" class="form-control booked_by_phone_booking">
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
                    <label class="txt-bold">Job Notes FOR Technician</label>
                    <textarea rows="3" id="comments_phone_booking" class="form-control comments_phone_booking"><?php echo $job_row['j_comments']; ?></textarea>
                    <textarea style="display:none;" rows="3" id="orig_comments" name="orig_comments" class="form-control"><?php echo $job_row['j_comments']; ?></textarea>
                </div>

                <div class="form-group booking-group phone_booking_tab_content">
                    <div class="checkbox">
                        <input type="checkbox" value="1" id="send_booking_sms" checked="">
                        <label for="send_booking_sms">Send Booking SMS <strong class="text-red no_mobile_num_text"></strong></label>
                    </div>
                </div>

                <div class="form-group booking-group">
                    <div class="checkbox">
                        <input type="checkbox" value="1" id="issue_en_notice_chxbx_phone_booking" <?php echo ($job_row['no_en']==1 OR $job_row['allow_en']==0) ?"disabled" : null; ?>>
                        <label for="issue_en_notice_chxbx_phone_booking">Issue Entry notice <?php echo ($job_row['no_en']==1 OR $job_row['allow_en']==0) ? "<small class='text-red'>This property is marked NO Entry Notice/No Entry Notice are Allowed</small>" : null ?></label>
                    </div>                   
                </div>
                <div class="form-group" id="issue_en_box_phone_booking">
                    <div class="radio">
                        <input name="en_tenant_only_or_plus_agency" checked id="en_tenant_plus_agency_radio_phone_booking" class="form-control en_tenant_only_or_plus_agency" type="radio" value="2" /> 
                        <label for="en_tenant_plus_agency_radio_phone_booking">Entry Notice Tenant + Agency</label>
                    </div>
                    <div class="radio">
                        <input name="en_tenant_only_or_plus_agency" id="en_tenant_only_radio_phone_booking" class="form-control en_tenant_only_or_plus_agency" type="radio" value="1" /> 
                        <label for="en_tenant_only_radio_phone_booking">Entry Notice Tenant ONLY</label>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <?php
        if( $job_row['j_status'] == "Booked" ){

            if( $job_row['key_access_required' ==1] ){
                if ($job_row['key_email_req'] == 1) {
                    $key_access_txt =  "Just to confirm, we have you booked in for <span class='junderline_colored'>" . date('l', strtotime($job_row['j_date'])) . " " . date('d/m/Y', strtotime($job_row['j_date'])) . "</span> and we will collect the keys from {$agency_name_txt} and our technician will leave a card to let you know the job has been done. {$agency_name_txt} requires you to confirm this booking so I am going to email you a template that you will need to reply to. Is that ok? Great, I am sending that to you now. ";
                } else {
                    $key_access_txt =  "Just to confirm, we have you booked in for <span class='junderline_colored'>" . date('l', strtotime($job_row['j_date'])) . " " . date('d/m/Y', strtotime($job_row['j_date'])) . "</span> and we will collect the keys from {$agency_name_txt} and our technician will leave a card to let you know the job has been done. ";
                }
            }else{
                $key_access_txt = "Just to confirm, we have you booked in for <span class='junderline_colored'>" . date('l', strtotime($job_row['j_date'])) . " " . date('d/m/Y', strtotime($job_row['j_date'])) . "</span> at <span class='junderline_colored'>" . $job_row['time_of_day'] . "</span>. We will send you an SMS the day before to remind you of the appointment.";
            }

            $script_text2 = "
                Thanks <u>{$job_row['booked_with']}</u>. {$key_access_txt}<br/>
                Thanks and have a great day!
            ";

            echo '<div class="alert alert-success alert-fill alert-border-left alert-close alert-dismissible fade show" role="alert">';
            echo $script_text2;
            echo '</div>';

        } 
    ?>

    <div class="form-group">
        <div class="row">
            <div class="col-md-6">
                &nbsp;  
            </div>
            <div class="col-md-6 text-right">
                <input type="hidden" id="vacant_start_date_phone_booking" value="<?php echo ($job_vacant_row['start_date']!="") ? $job_vacant_row['start_date'] : NULL; ?>">
                <input type="hidden" id="vacant_end_date_phone_booking" value="<?php echo ($job_vacant_row['end_date']!="") ? $job_vacant_row['end_date'] : NULL; ?>">
                <?php if( $can_edit_completed_job ){ ?>
                <button class="btn">Save Booking</button>
                <?php }else{
                    echo "<span class='text-red'>Completed Jobs can't be Edited</span>";
                } ?>
            </div>
        </div>
    </div>
<?php echo form_close(); ?>


<script type="text/javascript">

    function chckbox_show_hide_div(node,target)
    {

        if(node.is(':checked')){
            //$(target).show();
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

    function getTechNotesFromTickBox() {

        var jen_check = jQuery("#job_entry_notice_phone_booking").prop("checked");
        var jp_check = jQuery("#job_priority_phone_booking").prop("checked");
        var tech_notes_txt = '';

        jen_check_txt = (jen_check == true) ? 'EN - KEYS ' : '';
        jp_check_txt = (jp_check == true) ? 'DO NOT CANCEL ' : '';

        return tech_notes_txt = jen_check_txt + jp_check_txt;

    }

    function ajax_phone_booking_req(obj)
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
                    text: "Phone Booking Successfully Submitted",
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

    $(document).ready(function(){

        //init datepicker
		jQuery('.flatpickr_vjd').flatpickr({
			dateFormat: "d/m/Y",
            allowInput: true,
			locale: {
				firstDayOfWeek: 1
			}
		});

        //lockbox toggle
        $('#lock_box_chkbox_phone_booking').click(function(e){
            var node = $(this);
            if(node.is(':checked')){
                $('.lockbox_code_box_phone_booking').show();
            }else{
                $('.lockbox_code_box_phone_booking').hide();
                $('.lockbox_code_box_phone_booking').val(''); //clear value
            }
        })

        //call before js
        chckbox_show_hide_div($('.call_before_chxbox_phone_booking'),'#call_before_txt_booking') // run on load
        $(".call_before_chxbox_phone_booking").on('click', function(){
            var node = $(this);

            chckbox_show_hide_div(node,'#call_before_txt_booking');

        })
        //call before js end

        //key access js
        chckbox_show_hide_div($('.key_access_required_phone_booking'),'.key_access_details_div'); // run script on load
        $(".key_access_required_phone_booking").on('click', function(){ // run on click event
            var node = $(this);
            chckbox_show_hide_div(node,'.key_access_details_div');
        })
        //key access js end

        //Issue EN
        chckbox_show_hide_div($('#issue_en_notice_chxbx_phone_booking'),'#issue_en_box_phone_booking');
        $("#issue_en_notice_chxbx_phone_booking").on('click', function(){ // run on click event
            var node = $(this);
            chckbox_show_hide_div(node,'#issue_en_box_phone_booking');
        })
        //Issue EN end

        //Book With dropdown get phone number on change
        booked_with_and_sms_tweak();
        $('#booked_with_phone_booking').change(function(){
            booked_with_and_sms_tweak();
        })
        //Book With dropdown get phone number on change end


        $('#vjd_phone_booking_form').submit(function(e){
            e.preventDefault();

            var update_type = $(this).attr('id');
            var job_id = <?php echo $job_row['jid']; ?>;
            var prop_id = <?php echo $job_row['prop_id']; ?>;

            var job_status = "Booked";
            var job_date = $('#jobdate_phone_booking').val();
            var time_of_day = $('#timeofday_phone_booking').val();
            var lockbox = $('#lock_box_chkbox_phone_booking').prop('checked');
            var lockbox_code = $('#lockbox_code_phone_booking').val();
            var key_access_required = $('#key_access_required_phone_booking').prop('checked');
            var key_access_details = $('#key_access_details_phone_booking').val();
            var key_number = $('#key_number_phone_booking').val();
            var call_before = $('#call_before_chxbox_phone_booking').prop('checked');
            var call_before_txt = $('#call_before_txt_booking').val();
            var booked_with = $('#booked_with_phone_booking').val();
            var booked_with_mobile = $('#booked_with_phone_booking option:selected').attr('data-mobile_num');
            var booked_by = $('#booked_by_phone_booking').val();
            var comments = $('#comments_phone_booking').val();
            var send_booking_sms = $('#send_booking_sms').prop('checked');
            var en_notice_checkbox = $('#issue_en_notice_chxbx_phone_booking').prop('checked');
            var en_tenant_only_or_plus_agency = $('.en_tenant_only_or_plus_agency:checked').val();
            var job_entry_notice = $('#job_entry_notice_phone_booking').prop("checked")
            var job_priority = $('#job_priority_phone_booking').prop("checked")
            var tech_notes = $('#tech_notes_phone_booking').val();
            var assigned_tech = $('#techid_phone_booking').val();
            var vacant_start_date = $('#vacant_start_date_phone_booking').val();
            var vacant_end_date = $('#vacant_end_date_phone_booking').val();

            var objData = {
                update_type: update_type,
                job_id: job_id,
                prop_id: prop_id,
                job_status: job_status,
                job_date: job_date,
                time_of_day: time_of_day,
                lockbox: lockbox,
                lockbox_code: lockbox_code,
                key_access_required: key_access_required,
                key_access_details: key_access_details,
                key_number: key_number,
                call_before: call_before,
                call_before_txt: call_before_txt,
                booked_with: booked_with,
                booked_with_mobile: booked_with_mobile,
                booked_by: booked_by,
                comments: comments,
                send_booking_sms: send_booking_sms,
                en_notice_checkbox: en_notice_checkbox,
                en_tenant_only_or_plus_agency: en_tenant_only_or_plus_agency,
                job_priority: job_priority,
                job_entry_notice: job_entry_notice,
                tech_notes: tech_notes,
                assigned_tech: assigned_tech
            }

            swal({
                title: "Warning!",
                text: "Save Phone Booking?",
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
                                
                                ajax_phone_booking_req(objData);

                            })

                        }else{
                           
                            ajax_phone_booking_req(objData);

                        }

                    });
                        
                }

            })
        });

        jQuery("#job_entry_notice_phone_booking").change(function () {

            var checked = jQuery(this).prop("checked");
            var jp_check = jQuery("#job_priority_phone_booking").prop("checked");
            var tech_notes_txt = '';

            tech_notes_txt = getTechNotesFromTickBox();

            if (checked == true) {
                jQuery("#job_entry_notice_lbl").addClass("colorItRedBold");
            } else {
                jQuery("#job_entry_notice_lbl").removeClass("colorItRedBold");
            }
            jQuery("#tech_notes_phone_booking").val(tech_notes_txt);

        });

        jQuery("#job_priority_phone_booking").change(function () {

            var checked = jQuery(this).prop("checked");
            var jen_check = jQuery("#job_entry_notice_phone_booking").prop("checked");
            var tech_notes_txt = '';

            tech_notes_txt = getTechNotesFromTickBox();

            jQuery("#tech_notes_phone_booking").val(tech_notes_txt);

        });

    })

</script>