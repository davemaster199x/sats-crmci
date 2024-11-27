<style>
    .job_details_div h4{
        margin: 0;
        padding: 0;
        font-size: 16px;
        font-weight: bold;
    }
    .job_details_div hr{
        margin-top: 26px;
        margin-bottom:26px;
    }
    .job_det_fields label{
        font-weight: bold;
    }
    #load-screen{
		z-index:999999 !important;
	}
    .junderline_colored{
        color: #fff;
        text-decoration: underline;
    }
    .txt-bold{
        font-weight: bold;
    }
    .md-fancy-box{
        width: 500px;
    }
    .invoice_and_cert_sec a{
        margin-right:20px;
    }
    .flex_div{
        display: flex;
        flex-wrap: nowrap;
        align-items: center;
        gap:1%;
    }
    .invoice_details_div table tr td{
        padding-bottom: 5px;
        padding-top: 5px;
    }
    .invoice_details_div label{
        font-weight: 600;
    }
    .icon_actions .font-icon, .icon_actions .glyphicon{
        color:#adb7be;
    }
    .font-icon-inlinev2{
        position: relative!important;
        top: auto!important;
        left: auto!important;
    }
</style>
<div class="box-typical box-typical-padding">

    <?php
    // breadcrumbs template
    $bc_items = array(
        array(
            'title' => $title,
            'status' => 'active',
            'link' => "/jobs/details/{$this->uri->segment(3)}"
        )
    );
    $bc_data['bc_items'] = $bc_items;
    $this->load->view('templates/breadcrumbs', $bc_data);
    ?>
  
    <div class="body-typical-body">

        <div class="row">
            <div class="col text-center">
                <h3><a target="_blank" href="/properties/details/?id=<?php echo $job_row['prop_id'] ?>"><?php echo "{$job_row['p_address_1']} {$job_row['p_address_2']} {$job_row['p_address_3']}, {$job_row['p_state']} {$job_row['p_postcode']}" ?></a></h3>                
            </div>
        </div>

        <?php 
        if($show_api_connection_warning_message === TRUE): 

            $api_status_color = ($propertyIsConnectedToAPI === true) ? 'warning' : 'danger';
        ?>
        <div class="row">
            <div class="col api_tenants_head_text text-center">
                <div class="api_connection_warning_box alert alert-<?=$api_status_color?> alert-icon alert-close alert-dismissible fade show" role="alert">
                    <?php echo $propertyConnectionWarningMessage; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <section class="tabs-section">

            <!--.tabs-section-nav start-->
            <div class="tabs-section-nav tabs-section-nav-icons vjd_tab_div">
                <div class="tbl">
                    <ul class="nav" id="main-tab">
                        <li class="nav-item">
                            <!-- <a data-tabnum="1" class="nav-link active vjd_nav_item1" href="#tabs-1-tab-1" role="tab" data-toggle="tab"> -->
                            <a data-tabnum="1" class="nav-link <?php echo $tab == 1 ? 'active' : 'not-active' ?> vjd_nav_item1" href="/jobs/details/<?=$job_row['jid']?>/1">
                                <span class="nav-link-in">
                                    <i class="fa fa-wrench"></i>
                                    Job Details
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <!-- <a data-tabnum="2" class="nav-link vjd_nav_item2" href="#tabs-1-tab-2" role="tab" data-toggle="tab"> -->
                            <a data-tabnum="2" class="nav-link vjd_nav_item2 <?php echo $tab == 2 ? 'active' : 'not-active' ?>" href="/jobs/details/<?=$job_row['jid']?>/2">
                                <span class="nav-link-in">
                                    <i class="fa fa-home"></i>
                                    Property Details
                                </span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <!-- <a data-tabnum="3" class="nav-link vjd_nav_item3" href="#tabs-1-tab-3" role="tab" data-toggle="tab"> -->
                            <a data-tabnum="3" class="nav-link vjd_nav_item3 <?php echo $tab == 3 ? 'active' : 'not-active' ?>" href="/jobs/details/<?=$job_row['jid']?>/3">
                                <span class="nav-link-in">
                                    <i class="fa fa-file-text"></i>
                                    Accounts
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div><!--.tabs-section-nav end-->


            <div class="tab-content">

                <?php
                    $details = [];
                    $details['encryption_job_id'] = $encrypted_job_id;
                ?>

                <!-- Tab 1 -->
                <?php if($tab == 1): ?>
                <div role="tabpanel" class="job_details_div" id="tabs-1-tab-1">
                    <?php    $this->load->view('/jobs/job_job_details', $details); ?>
                </div>
                <?php endif; ?>
                <!-- Tab 1 end -->

                <!-- Tab 2 -->
                <?php if($tab == 2): ?>
                <div role="tabpanel" class="job_prop_details_div" id="tabs-1-tab-2">
                <?php $this->load->view('/jobs/job_job_property_details'); ?>
                </div>
                <?php endif; ?>
                <!-- Tab 2 end -->
               
                <!-- Tab 3 -->
                <?php if($tab == 3): ?>
                <div role="tabpanel" class="job_account_div" id="tabs-1-tab-3">
                <?php $this->load->view('/jobs/job_job_accounts'); ?>
                </div>
                <?php endif; ?>
                <!-- Tab 3 end -->

                

            </div>

        </section>

    </div>
   
</div>


<!-- MODALS -->
<div class="modal" id="btn_move_to_booked_modal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h3 class="modal-title">Send Back to Tech?</h3>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="form-group">
                    <label>Job Comments</label>
                    <textarea rows="5" class="form-control" id="vjd_send_back_to_tech_comments"><?php echo $job_row['j_comments']; ?></textarea>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success btn-send-back-to-tech" >Yes</button>
            </div>

        </div>
    </div>
</div>
<!-- MODALS END -->

<script type="text/javascript">

    $(document).ready(function(){ 

        // Initialize datepicket with custom date format and allow manual date input
        jQuery('.flatpickr_vjd').flatpickr({
			dateFormat: "d/m/Y",
            allowInput: true,
			locale: {
				firstDayOfWeek: 1
			}
		});
        
        //Send Back To Tech v2 > redo from swal to modal to git rid of JS error
        $('.btn-send-back-to-tech').click(function(e){
            e.preventDefault();
            var self = $(this);
            var parent_container = self.parents("#btn_move_to_booked_modal");
            var job_comment = parent_container.find('#vjd_send_back_to_tech_comments').val();

            $('#load-screen').show();
            jQuery.ajax({
                type: "POST",
                url: "/jobs/ajax_move_to_booked",
                dataType: 'json',
                data: {
                    job_id: <?php echo $job_row['jid'] ?? 0 ?>,
                    job_comments: job_comment
                }

            }).done(function( retval ) {
                //hide loader
                $('#load-screen').hide();
                
                if(retval.status){
                
                    swal({
                        title:"Success!",
                        text: "Send Back to Tech Success",
                        type: "success",
                        showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                        timer: <?php echo $this->config->item('timer') ?>
                    });
                
                    //Refresh page
                    var full_url = window.location.href;
                    setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                
                }else{
                    swal('Error','Internal error please contact admin','error');
                }

            });
        });

        // Create rebook job ajax request
        jQuery(".btn_create_rebook").click(function () {

            let job_id = <?=$job_row['jid'] ?? 0 ?>;
            let agency_status = '<?php echo $job_row['a_status']; ?>';

            var job_id_arr = new Array();
            job_id_arr.push(job_id);

            if(agency_status == 'deactivated'){
                alert('Error: Unable to do this while an Agency is Deactivated.');
            } else if(agency_status =='target'){
                alert('Error: Unable to do this while an Agency is Target.');
            } else {
                swal({
                    title: "Warning!",
                    text: "Rebook Job?",
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
                            url: "/jobs/ajax_rebook_script",
                            data: {
                                job_id: job_id_arr,
                                is_240v: 0
                            }

                        }).done(function( retval ) {
                            $('#load-screen').hide(); //hide loader

                            swal({
                                title:"Success!",
                                text: "Rebook Created",
                                type: "success",
                                showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                                timer: <?php echo $this->config->item('timer') ?>
                            });

                            var full_url = window.location.href;
                            setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);

                        });
                    }

                });
            }

        });

        // Create 240 rebook job
        jQuery(".btn_create_240v_rebook").click(function () {

            let job_id = <?=$job_row['jid'] ?? 0 ?>;
            let agency_status = '<?php echo $job_row['a_status']; ?>';

            var job_id_arr = new Array();
            job_id_arr.push(job_id);

            if(agency_status == 'deactivated'){
                swal('Error','Unable to do this while an Agency is Deactivated.','error');
            } else if(agency_status =='target'){
                swal('Error','Unable to do this while an Agency is Target.','error')
            } else {
                swal({
                    title: "Warning!",
                    text: "Rebook Job (240)?",
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
                            url: "/jobs/ajax_rebook_script",
                            data: {
                                job_id: job_id_arr,
                                is_240v: 1
                            }

                        }).done(function( retval ) {
                            $('#load-screen').hide(); //hide loader

                            swal({
                                title:"Success!",
                                text: "240v Rebook Created",
                                type: "success",
                                showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                                timer: <?php echo $this->config->item('timer') ?>
                            });

                            var full_url = window.location.href;
                            setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);

                        });
                    }

                });
            }

        });

        // Update to Merge button
        $('.btn_update_to_merge').click(function(){

            $('#load-screen').show(); //show loader

            jQuery.ajax({
                type: "POST",
                url: "/jobs/ajax_update_to_merged",
                dataType: 'json',
                data: {
                    job_id: <?=$job_row['jid'] ?? 0?>
                }

            }).done(function(response) {
                $('#load-screen').hide(); //hide loader

                if(response.status){
                    swal({
                        title:"Success!",
                        text: "Update to Merged Success",
                        type: "success",
                        showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                        timer: <?php echo $this->config->item('timer') ?>
                    });

                    var full_url = window.location.href;
                    setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                }else{
                    swal('Error','Error: Please contact admin.','error');
                }
            });

        });

        //ajax request for api property archived check
        $('#ajax_check_api_property_status').click(function(){
            
            var el = $(this);
            var prop_id = <?php echo $job_row['prop_id'] ?? 0 ?>;

            $('#load-screen').show();
            jQuery.ajax({
                type: "POST",
                url: "/jobs/apiPropertyIsArchived",
                dataType: 'json',
                data: {
                    prop_id: prop_id
                }

            }).done(function(response) {
                //hide loader
                $('#load-screen').hide();
                
                if(!jQuery.isEmptyObject(response)){
                    $('#api_prop_status_response_box').html(response.message);

                    //changed warning box from orange to green when property is active
                    if(response.isActive == true){
                        el.parents('.api_connection_warning_box').removeClass('alert-warning').addClass('alert-success');
                    }
                }

            });

        })



    }); //document ready end

</script>