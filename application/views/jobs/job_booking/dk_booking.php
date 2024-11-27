<?php echo form_open('/jobs/ajax_update_job_detail', array('id'=>'vjd_dk_booking_form','class'=>'update_job_detail_form')); ?>
    <input type="hidden" id="allow_dk" value="<?php echo $job_row['allow_dk'] ?>">
    <input type="hidden" name="dk_door_knock" id="dk_door_knock" value="1">
    <input type="hidden" name="dk_book_with" id="dk_book_with" value="Agent">
    <input type="hidden" name="booked_by" id="booked_by" value="user">

    <section class="card card-blue-fill">
        <div class="card-block">
            <div class="job_details_box job_details_box_tab">
                <?php 
                if($job_row['allow_dk'] <= 0){
                ?>
                    <div class="form-group booking-group dk_tab_content">
                        <span class='text-red'><strong>NO DKs ALLOWED</strong></span>
                    </div>
                <?php
                }
                ?>

                <div class="form-group booking-group phone_booking_tab_content en_notice_tab_content dk_tab_content">
                    <label class="txt-bold">Date <span class="text-red">*</span></label>
                    <input value="<?php echo ($this->system_model->isDateNotEmpty($job_row['j_date'])) ? $this->system_model->formatDate($job_row['j_date'],'d/m/Y') : ''; ?>" type="text" id="jobdate" name="jobdate" value="" class="addinput vw-jb-inpt jobdate flatpickr_vjd hasDatepicker form-control" placeholder="DD/MM/YYYY" style="width:125px;">
                </div>

                <div class="form-group booking-group en_notice_tab_content dk_tab_content">
                    <label class="txt-bold">Technician <span class="text-red">*</span></label>
                    <select id="techid" name="techid" class="form-control">
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

            </div>
        </div>
    </section>
    <div class="form-group">
        <div class="row">
            <div class="col-md-6">
                &nbsp;  
            </div>
            <div class="col-md-6 text-right">
                <?php if( $can_edit_completed_job ){ ?>
                <button id="btn_save_booking" class="btn">Book Door Knock</button>
                <?php }else{
                    echo "<span class='text-red'>Completed Jobs can't be Edited</span>";
                } ?>
            </div>
        </div>
    </div>
<?php echo form_close(); ?>

<script type="text/javascript">

    function getTechNotesFromTickBox() {

        var jen_check = jQuery("#job_entry_notice_dk_booking").prop("checked");
        var jp_check = jQuery("#job_priority_dk_booking").prop("checked");
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

        $('#vjd_dk_booking_form').submit(function(e){
            e.preventDefault();
            var form = $(this);

            var update_type = $(this).attr('id');
            var job_id = <?php echo $job_row['jid']; ?>;
            var prop_id = <?php echo $job_row['prop_id']; ?>;

            var job_status = "To Be Booked";
            var door_knock = form.find('#dk_door_knock').val();
            var job_date = form.find('#jobdate').val();
            var booked_by = form.find('#booked_by').val();
            var techid = form.find('#techid').val();
            var allow_dk = form.find('#allow_dk').val();
            //var job_priority = form.find('#job_priority_dk_booking').prop('checked');
            //var job_entry_notice = form.find('#job_entry_notice_dk_booking').prop('checked');
            //var tech_notes = form.find('#tech_notes_dk_booking').val();
            var booked_with = form.find('#dk_book_with').val();

            if(allow_dk<=0){ //No DK allowed > add swal override message
                var confirm_text = "This Agency does NOT allow Door Knocks. Do you want to override?";
            }else{ //DK yes - proceed to DK booking
                var confirm_text = "Save DK Booking?"
            }

            swal({
                title: "Warning!",
                text: confirm_text,
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
                            door_knock: door_knock,
                            job_date: job_date,
                            booked_by: booked_by,
                            techid: techid,
                            allow_dk: allow_dk,
                            booked_with: booked_with
                        }

                    }).done(function( retval ) {
                        $('#load-screen').hide(); //hide loader
                        if(retval.status){

                            swal({
                                title:"Success!",
                                text: "Door Knock Successfully Booked",
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

        jQuery("#job_entry_notice_dk_booking").change(function () {

            var checked = jQuery(this).prop("checked");
            var jp_check = jQuery("#job_priority_dk_booking").prop("checked");
            var tech_notes_txt = '';

            tech_notes_txt = getTechNotesFromTickBox();

            if (checked == true) {
                jQuery("#job_entry_notice_lbl").addClass("colorItRedBold");
            } else {
                jQuery("#job_entry_notice_lbl").removeClass("colorItRedBold");
            }
            jQuery("#tech_notes_dk_booking").val(tech_notes_txt);

        });

        jQuery("#job_priority_dk_booking").change(function () {

            var checked = jQuery(this).prop("checked");
            var jen_check = jQuery("#job_entry_notice_dk_booking").prop("checked");
            var tech_notes_txt = '';

            tech_notes_txt = getTechNotesFromTickBox();

            jQuery("#tech_notes_dk_booking").val(tech_notes_txt);

        });


    })

</script>