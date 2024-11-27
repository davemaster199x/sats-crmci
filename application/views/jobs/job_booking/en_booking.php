<?php echo form_open('/jobs/ajax_update_job_detail', array('id'=>'vjd_en_booking_form','class'=>'update_job_detail_form')); ?>
    <section class="card card-blue-fill">
        <div class="card-block">
            <div class="job_details_box job_details_box_tab">
                <div class="form-group booking-group phone_booking_tab_content en_notice_tab_content dk_tab_content">
                    <label class="txt-bold">Date <span class="text-red">*</span></label>
                    <input value="<?php echo ($this->system_model->isDateNotEmpty($job_row['j_date'])) ? $this->system_model->formatDate($job_row['j_date'],'d/m/Y') : ''; ?>" type="text" id="jobdate_en_booking" value="" class="addinput vw-jb-inpt jobdate_en_booking flatpickr_vjd hasDatepicker form-control" placeholder="DD/MM/YYYY" style="width:125px;">
                    <input type="hidden" name="orig_jobdate" id="orig_jobdate" value="<?php echo $job_row['j_date']; ?>">
                </div>

                <div class="form-group booking-group en_notice_tab_content">
                    <label class="txt-bold">Entry Notice Date of Issue <span class="text-red">*</span></label>
                    <input type="text" id="en_date_issued_en_booking" class="datepicker flatpickr_vjd en_date_issued_dp form-control" value="<?php echo $this->system_model->isDateNotEmpty($job_row['en_date_issued']) ? date("d/m/Y", strtotime($job_row['en_date_issued'])) : ''; ?>" placeholder="DD/MM/YYYY"/>
                </div>

                <div class="form-group booking-group en_notice_tab_content">
                    <label class="txt-bold">Time of Entry <span class="text-red">*</span></label>
                    <?php 
                        $time_of_entry_en_booking_default_val = "8.30 - 5";
                        if($job_row['preferred_time']!=""){
                            $time_of_entry_en_booking_default_val = $job_row['preferred_time'];
                        }
                    ?>
                    <input type="text" id="time_of_entry_en_booking" class="time_of_entry_en_booking form-control" value="<?php echo $time_of_entry_en_booking_default_val; ?>" />
                </div>

                <div class="form-group booking-group phone_booking_tab_content en_notice_tab_content">
                    <div class="checkbox">
                        <input value="1" type="checkbox" id="lock_box_chkbox_en_booking" class="lock_box_chkbox" value="1" <?php echo ($job_row['code']!='') ? "checked" : null; ?> >
                        <label for="lock_box_chkbox_en_booking">Lock Box</label>
                    </div>
                    <div class="lockbox_code_box" style="display:<?php echo ($job_row['code']!='') ? 'block;' : 'none;'; ?>">
                        <label>Lockbox Code</label>
                        <input value="<?php echo $job_row['code'] ?>" id="lockbox_code_en_booking" class="form-control lockbox_code_en_booking">
                    </div>
                </div>

                <div class="form-group">
                    <label class="txt-bold">Run Sheet Notes <span class="text-red">*</span></label>
                    <div>
                        <div class="left checkbox" style="margin-right:15px;">
                            <input type="checkbox" id="job_entry_notice_en_booking" class="run_sheet_notes_chk1 job_entry_notice_en_booking" data-flag="1" value="1" checked='true'>
                            <label for="job_entry_notice_en_booking">Entry Notice</label>
                        </div>
                        <div class="left checkbox">
                            <input type="checkbox" id="job_priority_en_booking" class="run_sheet_notes_chk2 job_priority_en_booking" data-flag="2" <?php echo ( $job_row['job_priority']==1) ?'checked':null; ?>>
                            <label for="job_priority_en_booking">Do Not Cancel</label>
                        </div>
                    </div>
                    <input type="text" id="tech_notes_en_booking" maxlength="15" value="<?php echo( $job_row['tech_notes'] != "") ?  $job_row['tech_notes'] : 'EN - KEYS' ?>" class="form-control tech_notes">
                </div>
                    
                <div class="form-group booking-group en_booking_tab_content">
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

                </div>

                <div class="form-group booking-group phone_booking_tab_content en_notice_tab_content dk_tab_content">
                    <label class="txt-bold">Booked By <span class="text-red">*</span></label>
                    <div class="row">
                        <div class="col-md-12">
                            <select id="booked_by_en_booking" class="form-control">
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
                </div>

                <div class="form-group booking-group en_notice_tab_content dk_tab_content">
                    <label class="txt-bold">Technician <span class="text-red">*</span></label>
                    <select id="techid_en_booking" class="form-control techid_en_booking">
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
                    <label class="txt-bold">Job Notes FOR Technician</label>
                    <textarea rows="3" id="comments_en_booking" class="form-control"><?php echo $job_row['j_comments']; ?></textarea>
                </div>

                <div class="form-group booking-group en_notice_tab_content">
                    <div class="radio">
                        <input checked id="en_tenant_plus_agency_radio" class="form-control en_tenant_only_or_plus_agency" name="en_tenant_only_or_plus_agency" type="radio" value="2" /> 
                        <label for="en_tenant_plus_agency_radio">Entry Notice <span class="txt-bold">Tenant + Agency</span></label>
                    </div>
                    <div class="radio">
                        <input id="en_tenant_only_radio" class="form-control en_tenant_only_or_plus_agency" name="en_tenant_only_or_plus_agency" type="radio" value="1" /> 
                        <label for="en_tenant_only_radio">Entry Notice <span class="txt-bold">Tenant ONLY</span></label>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="form-group">
        <div class="row">
            <div class="col-md-12 text-right">
                <?php 
                if( $can_edit_completed_job ){ 
                    if($job_row['no_en']==1 OR $job_row['allow_en']==0){
                        echo "<span class='text-red'>This property is marked NO Entry Notice/No Entry Notice are Allowed</span>";
                    }else{
                        echo '<button class="btn">Issue Entry Notice</button>';
                    }
                }else{
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
            $(target).show();
        }else{
            $(target).hide();
            //$(target).val('');
        }

    }

    function getTechNotesFromTickBox() {

        var jen_check = jQuery("#job_entry_notice_en_booking").prop("checked");
        var jp_check = jQuery("#job_priority_en_booking").prop("checked");
        var tech_notes_txt = '';

        jen_check_txt = (jen_check == true) ? 'EN - KEYS ' : '';
        jp_check_txt = (jp_check == true) ? 'DO NOT CANCEL ' : '';

        return tech_notes_txt = jen_check_txt + jp_check_txt;

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


        $('.lock_box_chkbox').click(function(e){
            var node = $(this);
            if(node.is(':checked')){
                $('.lockbox_code_box').show();
            }else{
                $('.lockbox_code_box').hide();
                $('.lockbox_code').val(''); //clear value
            }
        })


        $('#vjd_en_booking_form').submit(function(e){
            e.preventDefault();

            var update_type = $(this).attr('id');
            var job_id = <?php echo $job_row['jid']; ?>;
            var prop_id = <?php echo $job_row['prop_id']; ?>;

            var job_status = "Booked";
            //var job_entry_notice = 1;
            var job_date = $('#jobdate_en_booking').val();
            var en_date_issued = $('#en_date_issued_en_booking').val();
            var time_of_entry = $('#time_of_entry_en_booking').val();
            var lockbox = $('#lock_box_chkbox_en_booking').prop('checked');
            var lockbox_code = $('#lockbox_code_en_booking').val();
            var job_priority = $('#job_priority_en_booking').prop('checked');
            var job_entry_notice = $('#job_entry_notice_en_booking').prop('checked');
            var tech_notes = $('#tech_notes_en_booking').val();
            var booked_by = $('#booked_by_en_booking').val();
            var assigned_tech = $('#techid_en_booking').val();
            var comments = $('#comments_en_booking').val();
            var en_tenant_only_or_plus_agency = $('.en_tenant_only_or_plus_agency:checked').val();
            var booked_with = $('#booked_with_phone_booking').val();

            swal({
                title: "Warning!",
                text: "Issue Entry Notice?",
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
                                job_id: job_id,
                                prop_id: prop_id,
                                job_status: job_status,
                                job_entry_notice: job_entry_notice,
                                job_date: job_date,
                                en_date_issued: en_date_issued,
                                time_of_entry: time_of_entry,
                                lockbox: lockbox,
                                lockbox_code: lockbox_code,
                                job_priority: job_priority,
                                tech_notes: tech_notes,
                                booked_by: booked_by,
                                assigned_tech: assigned_tech,
                                comments: comments,
                                en_tenant_only_or_plus_agency: en_tenant_only_or_plus_agency,
                                booked_with: booked_with
                            }

                        }).done(function( retval ) {
                            $('#load-screen').hide(); //hide loader
                            if(retval.status){

                                swal({
                                    title:"Success!",
                                    text: "Entry Notice Successfully Issued",
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
        });

        jQuery("#job_entry_notice_en_booking").change(function () {

            var checked = jQuery(this).prop("checked");
            var jp_check = jQuery("#job_priority_en_booking").prop("checked");
            var tech_notes_txt = '';

            tech_notes_txt = getTechNotesFromTickBox();

            jQuery("#tech_notes_en_booking").val(tech_notes_txt);

        });

        jQuery("#job_priority_en_booking").change(function () {

            var checked = jQuery(this).prop("checked");
            var jen_check = jQuery("#job_entry_notice_en_booking").prop("checked");
            var tech_notes_txt = '';

            tech_notes_txt = getTechNotesFromTickBox();

            jQuery("#tech_notes_en_booking").val(tech_notes_txt);

        });


    })

</script>