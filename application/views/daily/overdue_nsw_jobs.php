
<style>
.total_box{
    margin-top: 18px;
}
.a_link.asc{
    top:3px;
}
.a_link.desc{
    top:-3px;
}
.fa-sort-up:before, .fa-sort-asc:before {
    content: "\f0de";
}
</style>
<div class="box-typical box-typical-padding">

<?php 
// breadcrumbs template
$bc_items = array(
    array(
        'title' => 'Reports',
        'link' => "/reports"
    ),
    array(
        'title' => $title,
        'status' => 'active',
        'link' => $uri
    )
);
$bc_data['bc_items'] = $bc_items;
$this->load->view('templates/breadcrumbs', $bc_data);
?>

 <header class="box-typical-header">
        <div class="box-typical box-typical-padding">

        <div class="for-groupss row">
            <div class="col-md-12 columns">
                <?php
                $form_attr = array(
                    'id' => 'nlm_reports',
                    'class' => ''
                );
                echo form_open('/daily/overdue_nsw_jobs', $form_attr);
                ?>
                        <div class="row">

                            <div class="col-md-2 columns">
                                <label>Agency</label>
                                <select class="form-control" id="agency_filter" name="agency_filter">
                                    <option value="">All</option>
                                    <?php
                                    foreach ($agency_filter->result_array() as $row) {
                                        $agency_sel = ($this->input->get_post('agency_filter')==$row['agency_id']) ? 'selected="true"' :NULL;
                                    ?>
                                        <option <?php echo $agency_sel; ?> value="<?php echo $row['agency_id'] ?>"><?php echo $row['agency_name'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <div class="fl-left region_filter_main_div">
                                    <label>	
                                    <?php 
                                        $defaultCountry = $this->config->item('country');
                                        echo $this->customlib->getDynamicRegionViaCountry($defaultCountry); 
                                    ?>:
                                    </label>
                                    <input type="text" name="region_filter_state" id='region_filter_state' class="form-control region_filter_state" placeholder="ALL" readonly="readonly" />
                                    
                                    <div id="region_dp_div" class="box-typical region_dp_div">
                                    
                                        <div class="region_dp_header">										
                                        </div>
                                        
                                        <div class="region_dp_body">								
                                        </div>
                                        
                                    </div>	
                                </div>
                            
                            </div>

                            <!-- <div class="col-md-2">
                                <label for="search">Retest Date:</label>
                                <div class="sort_dates">
                                    <select class="form-control" id="overdue_nsw_jobs_filter_date" name="overdue_nsw_jobs_filter_date">
                                        <option value="0" <?= ($this->input->post('overdue_nsw_jobs_filter_date') == 0) ? 'selected=true' : '' ; ?>>Low to High</option>
                                        <option value="1" <?= ($this->input->post('overdue_nsw_jobs_filter_date') == 1) ? 'selected=true' : '' ; ?>>High to Low</option>
                                    </select>
                                </div>
                            </div> -->

                            <div class="col-md-2">
                                <label for="search">Preferred Time:</label>
                                <div class="sort_time">
                                    <input id="preferred_time" type="text" class="form-control" name="overdue_nsw_jobs_filter_time" value="<?= $this->input->post('overdue_nsw_jobs_filter_time') ?>"/>
                                </div>
                            </div>

                            <!-- <div class="col-md-1">
                                <label for="search">Electrician Only(EO)</label>
                                <div class="checkbox" style="margin:0;">
                                    <input name="show_is_eo" type="checkbox" id="show_is_eo" value="1" <?php echo ( $this->input->get_post('show_is_eo') == 1 )?'checked':null; ?> />
                                    <label for="show_is_eo"></label>
                                </div>
                            </div> -->
                            
                            <div class="col-md-4 columns">
                                <div class="row">
                                    <div class="col-md-5">
                                        <label for="search">Electrician Only(EO)</label>
                                        <div class="checkbox" style="margin:0;">
                                            <input name="show_is_eo" type="checkbox" id="show_is_eo" value="1" <?php echo ( $this->input->get_post('show_is_eo') == 1 )?'checked':null; ?> />
                                            <label for="show_is_eo"></label>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="col-sm-12 form-control-label">&nbsp;</label>
                                        <button type="submit" class="btn btn-inline" name="submitFilter">Search</button>
                                    </div>
                                    <div class="col-md-2">
                                        <section class="proj-page-section float-left">
                                            <div class="proj-page-attach">
                                                <i class="fa fa-file-excel-o"></i>
                                                <p class="name"><?php echo $title; ?></p>
                                                <p>
                                                    <a href="<?php echo $export_link ?>">
                                                        Export
                                                    </a>
                                                </p>
                                            </div>
                                        </section>
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="col-md-2 columns">
                                <section class="proj-page-section float-right">
                                    <div class="proj-page-attach">
                                        <i class="fa fa-file-excel-o"></i>
                                        <p class="name"><?php echo $title; ?></p>
                                        <p>
                                            <a href="<?php echo $export_link ?>">
                                                Export
                                            </a>
                                        </p>
                                    </div>
                                </section>
                            </div> -->

                        </div>

                        

                <?php
                echo form_close();
                ?>
                </div>
             <!--   <div class="col-md-3 columns"> <div class="total_box text-right"><h5><?php echo $overdue_tot; ?> jobs are overdue</h5></div></div>
                <div class="col-md-3 columns"> <div class="total_box"><h5><?php echo $overdue_30days_tot; ?> jobs are due in less than 30 days</h5></div></div>
                                -->
               </div>

        </div>

    </header>

<section>
    <div class="body-typical-body">
        <div class="table-responsive">
            <table class="table table-hover main-table table-striped">
                <thead>
                    <tr>
                        <th>Deadline</th>
                        <th style="width:120px;">
                            Retest Date
                            <a data-toggle="tooltip" class="a_link <?php echo $sort ?>" href="<?php echo "/daily/overdue_nsw_jobs/?sort_header=1&order_by=retest_date&sort={$toggle_sort}&".http_build_query($header_link_params); ?>">
                                <em class="fa fa-sort-<?php echo $sort; ?>"></em>
                            </a>
                        </th>
                        <th>Allow EN</th>
                        <th>Property Address</th>
                        <th>Agency</th>
                        <th>Active Job Status</th>
                        <th>Active Job Age</th>
                        <th>
                            Preferred Time
                            <a data-toggle="tooltip" class="a_link <?php echo $sort ?>" href="<?php echo "/daily/overdue_nsw_jobs/?sort_header=1&order_by=preferred_time&sort={$toggle_sort}&".http_build_query($header_link_params); ?>">
                                <em class="fa fa-sort-<?php echo $sort; ?>"></em>
                            </a>
                        </th>
                        <th class="check_all_td">
                            <div class="checkbox" style="margin:0;">
                                <input name="chk_all" type="checkbox" id="check-all">
                                <label for="check-all">&nbsp;</label>
                            </div>
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                        foreach($lists as $u){

                            //$recent_job_sql = $this->daily_model->get_recent_created_job($u['property_id']);
                            //$recent_job_sql_row = $recent_job_sql->row_array();

                            $recent_job_sql_row = $u['recent_job'];

                            // $now = time();
                            // $your_date = strtotime($u['test_date']);
                            // $datediff = $now - $your_date;

                            // $deadline = 365-(round($datediff / (60 * 60 * 24)));
                            // if ($deadline > 15) {
                            //     continue;
                            // }
                    ?>
                            <tr class="body_tr jalign_left tbl_list_tr" <?php echo $bg_color; ?>>
                                <td><?php echo ( $u['deadline'] > 0 )?$u['deadline']:'<span class="text-danger" data-toggle="tooltip" title="Overdue" >+'.($u['deadline']*-1).'</span>'; ?></td>

                              <td>
                                <?php echo ($this->system_model->isDateNotEmpty($u['retest_date']) == true) ? $this->system_model->formatDate($u['retest_date'], 'd/m/Y') : NULL; ?>
                                </td>

                                <td>
                                    <?php echo ((int)$u['allow_en'] === 1 || (int)$u['allow_en'] === 2) ? "YES" : ""; ?>
                                </td>
								<td>
									<span class="txt_lbl">
                                        <?php echo $this->gherxlib->crmLink('vpd',$u['property_id'],"{$u['p_address1']} {$u['p_address2']}, {$u['p_address3']} {$u['p_state']} {$u['p_postcode']}"); ?>
									</span>
                                </td>
                                
                                <td><?php echo $this->gherxlib->crmLink('vad',$u['agency_id'],$u['agency_name']); ?></td>

                                <td>
                                    <span class="txt_lbl">
                                        <?php 
                                         echo $this->gherxlib->crmLink('vjd',$recent_job_sql_row['jid'],$recent_job_sql_row['jstatus']);
                                        ?>
                                    </span>
                                </td>

                                <td>
                                    <?php 
                                        $created =  $recent_job_sql_row['jcreated'];
                                        $date1 = date_create(date('Y-m-d', strtotime($created)));
                                        $date2 = date_create(date('Y-m-d'));
                                        $diff = date_diff($date1, $date2);
                                        $age = $diff->format("%r%a");

                                        if( !empty($recent_job_sql_row) ){
                                            $age_val = (((int) $age) != 0) ? $age : 0;
                                        }else{
                                            $age_val = NULL;
                                        }

                                        echo $age_val;
                                    ?>
                                </td>
                                <td><?= $u['preferred_time']; ?></td>
                                <td>
                                    <div class="checkbox">
                                        <input class="chk_job" name="chk_job[]" type="checkbox" id="check-<?php echo $u["id"] ?>" data-jobid="<?php echo $u["id"]; ?>" data-propid="<?php echo $u['property_id'] ?>" value="<?php echo $u['id']; ?>">                                        
                                        <label for="check-<?php echo $u["id"] ?>">&nbsp;</label>
                                    </div>
                                    <input type="hidden" class="job_type" value="<?php echo $u['j_type']; ?>" />
                                    <input type="hidden" class="is_eo" value="<?php echo $u['is_eo']; ?>" />
                                </td>
                        </tr>
                    <?php
                        }
                    ?>
                </tbody>

            </table>
            <div id="mbm_box" class="text-right">
                
                <div class="gbox_main">
                    <div class="gbox">
                        <input name="snooze_reason" class="form-control"  id="snooze_reason" type="text" placeholder="Snooze reason*" >
                    </div>
                    <div class="gbox">
                        <button id="snooze_btn" type="button" class="btn">Snooze</button>
                    </div>
                </div>

                <div class="gbox_main" style="margin-right:50px;">
                    <div class="gbox">
                    <select id="maps_tech" class="form-control">
                        <option value="">Please select Tech</option>
                        <?php
                            $params = array(
                                'sel_query'=> "sa.StaffID, sa.FirstName, sa.LastName, sa.is_electrician, sa.active as sa_active",
                            );
                            $tech = $this->system_model->getTech($params);
                            foreach($tech->result_array() as $row){
                        ?>
                            <option value="<?php echo $row['StaffID'] ?>" data-isElectrician="<?php echo $row['is_electrician']; ?>">
                            <?php 
                                echo $this->system_model->formatStaffName($row['FirstName'],$row['LastName']).( ( $row['is_electrician'] == 1 )?' [E]':null ); 
                            ?>
                            </option>
                        <?php
                            }
                        ?>
                    </select>
                    </div>
                    <div class="gbox">
                        <input name="assign_date" class="flatpickr form-control flatpickr-input" data-allow-input="true" id="assign_date" type="text" placeholder="Date" >
                    </div>
                    <div class="gbox">
                        <button id="assign_btn" type="button" class="btn">Assign</button>
                    </div>
                </div>

            </div>
        </div>

        <nav id="pagi_links" aria-label="Page navigation example" style="text-align:center"><?php echo $pagination; ?></nav>
        <div id="pagi_count" class="pagi_count text-center"><?php echo $pagi_count; ?></div>

    </div>
</section>

</div>


<style>
.main-table {
    border-left: 1px solid #dee2e6;
    border-right: 1px solid #dee2e6;
    border-bottom: 1px solid #dee2e6;
    margin-bottom: 20px;
}

.col-mdd-3 {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 15.2%;
    flex: 0 0 15.2%;
    max-width: 15.2%;

    position: relative;
    width: 100%;
    min-height: 1px;
    padding-right: 15px;
    padding-left: 15px;
}
</style>

<!-- Fancybox Start -->
<a href="javascript:;" id="about_page_fb_link" class="fb_trigger" data-fancybox data-src="#about_page_fb">Trigger the fancybox</a>							
<div id="about_page_fb" class="fancybox" style="display:none;" >

<h4><?php echo $title; ?></h4>
<pre><code><?php echo $page_query; ?></code></pre>

</div>
<!-- Fancybox END -->

<script type="text/javascript">

jQuery(document).ready(function(){

    // region filter selection, cant trigger without the timeout, dunno why :( 
	<?php
    if( !empty($this->input->get_post('sub_region_ms')) ){ ?>
        setTimeout(function(){ 
            jQuery("#region_filter_state").click();
            }, 500);		
    <?php
    }
    ?>

    // region filter click
    jQuery('.region_filter_main_div').on('click','.region_filter_state',function(){
            
            var obj  = jQuery(this);
            var state_chk = obj.prop("checked");
            var region_filter_json = <?php echo $region_filter_json; ?>;
            var state_ms_json = <?php echo $state_ms_json; ?>;
            
            jQuery("#load-screen").show();
            
            jQuery.ajax({
                type: "POST",
                url: "/sys/getRegionFilterState",
                data: { 
                    rf_class: 'property',
                    region_filter_json: region_filter_json
                }
            }).done(function( ret ){
                
                jQuery("#load-screen").hide();
                jQuery(".region_dp_header").html(ret);
                
                // searched
                var state_ms_json_num = state_ms_json.length;
                if( state_ms_json_num > 0 ){				
                    for( var i=0; i < state_ms_json_num; i++ ){
                        jQuery("#region_dp_div .state_ms[value='"+state_ms_json[i]+"']").click();
                    }
                }
                
                
            });
                    
        });

        // state click
        jQuery('.region_dp_div').on('click','.state_ms',function(){
            
            var obj  = jQuery(this);
            var state = obj.val();
            var state_chk = obj.prop("checked");
            var region_filter_json = <?php echo $region_filter_json; ?>;
            var region_ms_json = <?php echo $region_ms_json; ?>;
            
            if(state_chk==true){
                
                obj.parents(".state_div:first").find(".rf_state_lbl").addClass("rf_select");
                jQuery("#load-screen").show();
                
                jQuery.ajax({
                    type: "POST",
                    url: "/sys/getMainRegion",
                    data: { 
                        state: state,
                        rf_class: 'property',
                        region_filter_json: region_filter_json
                    }
                }).done(function( ret ){
                    
                    jQuery("#load-screen").hide();
                    obj.parents(".state_div:first").find(".region_div").html(ret);

                    // searched
                    var region_ms_json_num = region_ms_json.length;
                    if( region_ms_json_num > 0 ){				
                        for( var i=0; i < region_ms_json_num; i++ ){
                            obj.parents(".state_div:first").find(".region_ms[value='"+region_ms_json[i]+"']").click();
                        }
                    }
                    
                });
                
            }else{
                obj.parents(".state_div:first").find(".rf_state_lbl").removeClass("rf_select");
                obj.parents(".state_div:first").find(".region_div").html('');			
            }	
                    
        });

        // region click
        jQuery('.region_dp_div').on('click','.region_ms',function(){
            
            var obj  = jQuery(this);
            var region_id = obj.val();
            var state_chk = obj.prop("checked");
            var region_filter_json = <?php echo $region_filter_json; ?>;
            var sub_region_ms_json = <?php echo $sub_region_ms_json; ?>;
            
            if(state_chk==true){
                
                obj.parents(".region_div_chk:first").find(".rf_region_lbl").addClass("rf_select");
                jQuery("#load-screen").show();
                
                jQuery.ajax({
                    type: "POST",
                    url: "/sys/getSubRegion",
                    data: { 
                        region_id: region_id,
                        rf_class: 'property',
                        region_filter_json: region_filter_json
                    }
                }).done(function( ret ){
                    
                    jQuery("#load-screen").hide();
                    obj.parents(".region_div_chk:first").find(".sub_region_div").html(ret);

                    // searched
                    var sub_region_ms_json_num = sub_region_ms_json.length;
                    if( sub_region_ms_json_num > 0 ){				
                        for( var i=0; i < sub_region_ms_json_num; i++ ){
                            obj.parents(".region_div_chk:first").find(".sub_region_ms[value='"+sub_region_ms_json[i]+"']").click();
                        }
                    }
                    
                });
                
                
            }else{
                obj.parents(".region_div_chk:first").find(".rf_region_lbl").removeClass("rf_select");
                obj.parents(".region_div_chk:first").find(".sub_region_div").html('');
            }	
                    
        });

        // sub region 
        jQuery('.region_dp_div').on('click','.sub_region_ms',function(){
            
            var obj  = jQuery(this);
            var region_id = obj.val();
            var state_chk = obj.prop("checked");
            
            if(state_chk==true){			
                obj.parents(".sub_region_div_chk:first").find(".rf_sub_region_lbl").addClass("rf_select");			
            }else{
                obj.parents(".sub_region_div_chk:first").find(".rf_sub_region_lbl").removeClass("rf_select");
            }	
                    
        });

        // $('#btnMarkUnserviced').click(function(e){
        //     e.preventDefault();

        //     swal({
        //         html:true,
        //         title: "",
        //         text: "Are you sure you want to proceed?",
        //         type: "warning",
        //         showCancelButton: true,
        //         confirmButtonClass: "btn-success",
        //         confirmButtonText: "Yes",
        //         cancelButtonClass: "btn-danger",
        //         cancelButtonText: "No, Cancel!",
        //         closeOnConfirm: false,
        //         closeOnCancel: true,
        //     },
        //     function(isConfirm){
        //         if(isConfirm){

        //             $('#load-screen').show(); 
        //             swal.close();

        //             jQuery.ajax({
        //                 method: 'post',
        //                 processData: false,
        //                 contentType: false,
        //                 cache: false,
        //                 dataType: 'json',
        //                 data: { 
        //                         staff_id: <?php echo $this->session->staff_id ?>,
        //                     },
        //                 url: "/daily/unserviced_for_cron",
        //             }).done(function( crm_ret ){
        //                 if(crm_ret.status){
        //                     $('#load-screen').hide(); //hide loader
        //                     swal({
        //                         title:"Success!",
        //                         text: "Properties have been marked as unserviced.",
        //                         type: "success",
        //                         showCancelButton: false,
        //                         showConfirmButton: false,
        //                         confirmButtonText: "OK",
        //                         closeOnConfirm: false,
        //                         closeOnConfirm: false,
        //                         allowOutsideClick: false,
        //                         timer: 3000
        //                     },function(isConfirm){
        //                         swal.close();
        //                         location.reload();
        //                     });
        //                 }else{
        //                     swal('','All appropriate properties have already been marked as unserviced.','info');
        //                     $('#load-screen').hide(); //hide loader
        //                 }

        //             }); 
        //         }
        //     });
        // })


        $('#check-all').on('change',function(){
            var obj = $(this);
            var isChecked = obj.is(':checked');
            var divbutton = $('#mbm_box');
            if(isChecked){
                divbutton.show();
                $('.chk_job').prop('checked',true);
                $("tr.tbl_list_tr").addClass("yello_mark");
            }else{
                divbutton.hide();
                $('.chk_job').prop('checked',false);
                $("tr.tbl_list_tr").removeClass("yello_mark");
            }
        })

        $('.chk_job').on('change',function(){
            var obj = $(this);
            var isLength = $('.chk_job:checked').length;
            var divbutton = $('#mbm_box');
            if(obj.is(':checked')){
                divbutton.show();
                obj.parents('.tbl_list_tr').addClass('yello_mark');
            }else{
                obj.parents('.tbl_list_tr').removeClass('yello_mark');
                if(isLength<=0){
                    divbutton.hide();
                }
            }
        })

        jQuery("#snooze_btn").on('click',function(){

            var agay = [];
            var snooze_reason = $('#snooze_reason').val();

            jQuery(".chk_job:checked").each(function(){
                var obj_checkbox = $(this);
                var prop_id = obj_checkbox.attr('data-propid');
                var job_id = obj_checkbox.val();
                var json_data = {
                    prop_id: prop_id,
                    job_id: job_id
                }
                var json_str = JSON.stringify(json_data);
                agay.push(json_str);
            });
            
            //validation
            if(!snooze_reason.trim()){
                swal('','Snooze reason must not be empty.','error');
                return false;
            }

            $('#load-screen').show(); //show loader
            jQuery.ajax({
				type: "POST",
				url: "/daily/ajax_snooze",
				data: { 
                    agay: agay,
					snooze_reason: snooze_reason
				}
			}).done(function( ret ){
				$('#load-screen').hide(); //hide loader
				swal({
					title:"Success!",
					text: "Success",
					type: "success",
					showCancelButton: false,
					confirmButtonText: "OK",
					closeOnConfirm: false,
					showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
					timer: <?php echo $this->config->item('timer') ?>
				});
				setTimeout(function(){ window.location='/daily/overdue_nsw_jobs'; }, <?php echo $this->config->item('timer') ?>);
					
			});	

        })

        jQuery("#assign_btn").on('click',function(){
		
		var job_id = new Array();
		var tech_id = jQuery("#maps_tech").val();
		var is_tech_electrician = jQuery("#maps_tech option:selected").attr("data-isElectrician");
		var date = jQuery("#assign_date").val();
		var checkLength = $('.chk_job:checked').length;
		var for_elec_only = false;

		var error = "";
	
		//push job_id array
		jQuery(".chk_job:checked").each(function(){

            var job_chk_dom = jQuery(this);
            var parents_tr = job_chk_dom.parents("tr:first");
            var job_type = parents_tr.find(".job_type").val();
			var is_eo = parents_tr.find(".is_eo").val();		

            // 240v Rebook Jobs or Electrician Only(EO)		
            if( job_type == '240v Rebook' || is_eo == 1 ){
                for_elec_only = true;
            }

            job_id.push(jQuery(this).val());

        });

		//validations
		if(checkLength == 0){
			error += "Please select/tick Job\n";
		}
		if(tech_id==""){
			error += "Tech must not be empty\n";
		}
		if(date==""){
			error += "Date must not be empty\n";
		}
		
        // 240v Rebook or Electrician Only(EO) check
		if( tech_id > 0 && is_tech_electrician != 1 && for_elec_only == true ){ 		
			error += "Cannot assign 240v Rebook or Electrician Only(EO) job to non Electrician\n";
		}

		if( error != "" ){

			swal('',error,'error');
			return false;
			
		}else{

            if( job_id.length > 0 ){

                $('#load-screen').show(); //show loader
                jQuery.ajax({
                    type: "POST",
                    url: "/jobs/ajax_move_to_maps",
                    data: { 
                        job_id: job_id,
                        tech_id: tech_id,
                        date: date,
                        page_type: "overdue_nsw_jobs"
                    }
                }).done(function( ret ){
                    $('#load-screen').hide(); //hide loader
                    swal({
                        title:"Success!",
                        text: "Assigned success",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonText: "OK",
                        closeOnConfirm: false,
                        showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                        timer: <?php echo $this->config->item('timer') ?>

                    });
                    setTimeout(function(){ window.location='/daily/overdue_nsw_jobs'; }, <?php echo $this->config->item('timer') ?>);
                        
                });

            }			

		}		
				
	});

})

</script>