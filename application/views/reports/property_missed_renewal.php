<style>
    .col-mdd-3{
        max-width:15.5%;
    }
    .j_is_bold{
        font-weight:bold;
    }
    #year_filter{
        width: 86px;
    }
    #renew_div{
        display: none;
    }
    #job_status_filter{
        width: auto;
    }
    .checkbox-toggle{
        margin-bottom: 0;
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

$export_links_params_arr = array(
    'date_from_filter' => $this->input->get_post('date_from_filter'),
    'date_to_filter' => $this->input->get_post('date_to_filter'),
    'tech_filter' =>  $this->input->get_post('tech_filter'),
    'reason_filter' =>  $this->input->get_post('reason_filter'),
    'job_type_filter' =>  $this->input->get_post('job_type_filter'),
    'date_filter' =>  $this->input->get_post('date')
);
$export_link_params = "/jobs/missed_jobs/?export=1&".http_build_query($export_links_params_arr);
?>

	<header class="box-typical-header">

        <div class="box-typical box-typical-padding">
            <?php
        $form_attr = array(
            'id' => 'header_filter_form'
        );
        echo form_open($uri,$form_attr);
        ?>
            <div class="for-groupss row">

            
                <div class="col-lg-10 col-md-12 columns">
           
                    <div class="row">                        
                        
                        <!--
                        <div class="col-md-2">
							<label>Agency</label>
							<select id="agency_filter" name="agency_filter" class="form-control">
								<option value="">---</option>
                                <?php                                                        
                                foreach( $agency_filter_sql->result() as $agency_row ){                                   
                                ?>
                                    <option 
                                        value="<?php echo $agency_row->agency_id; ?>" 
                                        <?php echo (  $agency_row->agency_id == $this->input->get_post('agency_filter') )?'selected':null; ?>
                                    >
                                        <?php echo $agency_row->agency_name; ?>
                                    </option>
                                <?php
                                }                            
                                ?>
							</select>							
						</div>

                        <div class="col-md-2">
							<label>State</label>
							<select id="state_filter" name="state_filter" class="form-control">
								<option value="">---</option>
                                <?php                                                      
                                foreach( $state_filter_sql->result() as $state_filter_row ){                                   
                                ?>
                                    <option 
                                        value="<?php echo $state_filter_row->state; ?>" 
                                        <?php echo (  $state_filter_row->state == $this->input->get_post('state_filter') )?'selected':null; ?>
                                    >
                                        <?php echo $state_filter_row->state; ?>
                                    </option>
                                <?php
                                }                        
                                ?>
							</select>							
						</div>
                        -->

                        <!--
                        <div class="col-md-3">
							<label>Search</label>
							<select id="date_search" name="date_search" class="form-control">
								<option value="">---</option>
                                <option value="1" <?php echo ( $this->input->get_post('date_search') == 1 )?'selected':null; ?>>Tenancy Start</option>
                                <option value="2" <?php echo ( $this->input->get_post('date_search') == 2 )?'selected':null; ?>>Tenancy End</option>
                                <option value="3" <?php echo ( $this->input->get_post('date_search') == 3 )?'selected':null; ?>>Agreement Start</option>
                                <option value="4" <?php echo ( $this->input->get_post('date_search') == 4 )?'selected':null; ?>>Agreement End</option>                     
							</select>							
						</div>
                        -->

                        <!--
                        <div class="col-md-2">
							<label>Month</label>
							<select name="month_filter" id="month_filter" class="form-control">
								<option value="">---</option>     
                                <?php
                                for( $m = 1; $m <= 12; $m++ ) {
                                $month = date('F', mktime(0,0,0,$m, 1, date('Y'))); 

                                $month_padded = str_pad($m, 2, '0', STR_PAD_LEFT); // add zero to single digit 
                                ?>
                                    <option value="<?php echo $month_padded; ?>" <?php echo ( $month_padded == $month_filter )?'selected':null; ?>><?php echo $month; ?></option>  
                                <?php
                                }
                                ?>                                     
							</select>							
						</div>

                        <div class="col-md-2">
							<label>Year</label>
                            <input type="number" name="year_filter" id="year_filter" class="form-control" value="<?php echo $year_filter; ?>" />							
						</div>
                        -->
                       

                        <div class="col-md-3 columns">
                            <label id="renewals_to_show_lbl"><?php echo ( $this->input->get_post('renewals_to_show') == 1 )?'Only Properties With Late Jobs':'All Missed Renewals'; ?></label>                              
                            <div class="checkbox-toggle">
                                <input type="checkbox" id="renewals_to_show" name="renewals_to_show" value="1" <?php echo ( $this->input->get_post('renewals_to_show') == 1 )?'checked':null; ?> />
                                <label for="renewals_to_show"></label>
                            </div>
                        </div>

                        <div class="col-md-3 columns">
                            <label id="show_fixed_prop_lbl"><?php echo ( $this->input->get_post('show_fixed_prop') == 1 )?'Show Fixed Properties':'Hide Fixed Properties'; ?></label>                              
                            <div class="checkbox-toggle">
                                <input type="checkbox" id="show_fixed_prop" name="show_fixed_prop" value="1" <?php echo ( $this->input->get_post('show_fixed_prop') == 1 )?'checked':null; ?> />
                                <label for="show_fixed_prop"></label>
                            </div>
                        </div>

                        <div class="col-md-3 columns">
                            <label id="custom_interval_lbl"><?php echo ( $this->input->get_post('custom_interval') == 1 )?'Custom Interval':'Default Interval'; ?></label>                              
                            <div class="checkbox-toggle">
                                <input type="checkbox" id="custom_interval" name="custom_interval" value=   "1" <?php echo ( $this->input->get_post('custom_interval') == 1 )?'checked':null; ?> />
                                <label for="custom_interval"></label>
                            </div>
                        </div>

                        <div class="col-md-1 columns">
                            <button type="button" id="btn_search" class="btn">Search</button>
                        </div>
                                                
                    </div>              

                </div>
                              
                <!--
                <div class="col-lg-2 columns">
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
				</div>  
                -->              
                                    
                </div>
                </form>
            </div>

        </header>

	<section>
		<div class="body-typical-body">
			<div class="table-responsive">

                <form id="proccess_renewal_form" action="/reports/proccess_renewal" method="POST">

                    <table id="datatable" class="table table-hover main-table" data-paging="false">
                        <thead>
                            <tr>    
                                <th>Job Date</th>       
                                <th>Service Type</th>
                                <th>Job Type</th>
                                <th>Job Status</th>                                                            
                                <th>
                                    Property Address
                                    <!--<a 
                                        data-toggle="tooltip" 
                                        class="a_link <?php echo $sort ?>" 
                                        href="<?php echo "{$uri}/?sort_header=1&order_by=p.address_2&sort={$toggle_sort}&".http_build_query($header_link_params); ?>"
                                    >
                                        <em class="fa fa-sort-<?php echo $sort; ?>"></em>
                                    </a>-->
                                </th>      
                                <th>Agency
                                    <!--<a 
                                        data-toggle="tooltip" 
                                        class="a_link <?php echo $sort ?>" 
                                        href="<?php echo "{$uri}/?sort_header=1&order_by=a.agency_name&sort={$toggle_sort}&".http_build_query($header_link_params); ?>"
                                    >
                                        <em class="fa fa-sort-<?php echo $sort; ?>"></em>
                                    </a>-->
                                </th>   
                                <th>
                                    <div class="checkbox" style="margin:0;">
                                        <input type="checkbox" id="check-all">
                                        <label for="check-all">&nbsp;</label>
                                    </div>
                                </th>                                                                                  
                            </tr>
                        </thead>

                        <tbody>
                            <?php                                              
                            if( $lists->num_rows() > 0 ){
                                foreach($lists->result() as $index => $row){

                                    // service
                                    $job_icons_params = array(
                                        'job_id' => $row->jid,
                                        'job_type' => $row->j_status
                                    );
                                    $service_type_icon = $this->system_model->display_job_icons($job_icons_params);

                                ?>
                                    <tr>  
                                        <td><?php echo ( $this->system_model->isDateNotEmpty($row->jdate) )?date("d/m/Y",strtotime($row->jdate)):null; ?></td>      
                                        <td><span class="d-none"><?php echo $row->service_type_txt; ?></span> <?php echo $service_type_icon; ?></td>                   
                                        <td>
                                            <a target="_blank" href="<?php echo $this->config->item("crmci_link"); ?>/jobs/details/<?php echo $row->jid; ?>">
                                                <?php echo $row->job_type; ?>
                                            </a>
                                        </td>
                                        <td><?php echo $row->j_status; ?></td>                                                                                
                                        <td>
                                            <a target="_blank" href="/properties/details/?id=<?php echo $row->property_id; ?>">
                                                <?php echo "{$row->p_address_1} {$row->p_address_2}, {$row->p_address_3} {$row->p_state}  {$row->p_postcode}"; ?>
                                            </a>
                                        </td>
                                        <td>                         
                                            <a target="_blank" href="/agency/view_agency_details/<?php echo $row->agency_id; ?>"><?php echo $row->agency_name; ?></a> 
                                        </td>    
                                        <td>
                                            <span class="checkbox">
                                                <input type="checkbox" id="chk_job_id_<?php echo $index; ?>" class="chk_job_id" name="job_id_arr[]" value="<?php echo $row->jid; ?>" />
                                                <label for="chk_job_id_<?php echo $index; ?>"></label>
                                            </span>
                                        </td>                                                                                                   
                                    </tr>
                                <?php
                                }
                            }else{
                                echo "
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>";
                            }                                               
                            ?>
                        </tbody>

                    </table>

                    <div id="renew_div">
                        
                        <button type="button" class="btn float-right" id="renew_btn">Create Jobs</button>
                        <select name="job_status_filter" id="job_status_filter" class="form-control float-right mr-2">
                            <option value=''>--- Select Status ---</option>
                            <!-- copied from vjd -->
                            <option value='Send Letters' $sl>Send Letters</option>
                            <option value='On Hold' $pa>On Hold</option>
                            <option value='To Be Booked' $tb>To Be Booked</option>
                            <option value='Booked' $bk>Booked</option>
                            <option value='Pre Completion' $pc>Pre Completion</option>
                            <option value='Merged Certificates' $mc>Merged Certificates</option>
                            <option value='Completed' $cp>Completed</option>
                            <option value='Pending' $pe>Pending</option>
                            <option value='Cancelled' $cl>Cancelled</option>
                            <option value='Action Required' $ar>Action Required</option>
                            <option value='DHA' $dha>DHA</option>
                            <option value='To Be Invoiced' $tbi>To Be Invoiced</option>
                            <option value='On Hold - COVID' $oh_cv19>On Hold - COVID</option>
                            <option style='color:red;' value='Escalate' $escalate>Escalate **</option>
                            <option style='color:red;' value='Allocate' $allocate>Allocate **</option>
                        </select>

                    </div>
                    

                </form>

			</div>

            <!--
            <nav id="pagi_links" aria-label="Page navigation example" style="text-align:center"><?php echo $pagination; ?></nav>
            <div id="pagi_count" class="pagi_count text-center"><?php echo $pagi_count; ?></div>
            -->

    </div>

<!-- Fancybox Start -->
<a href="javascript:;" id="about_page_fb_link" class="fb_trigger" data-fancybox data-src="#about_page_fb">Trigger the fancybox</a>							
<div id="about_page_fb" class="fancybox" style="display:none;" >
<h4><?php echo $title; ?></h4>
<pre><code><?php echo $sql_query; ?></code></pre>
</div>
<!-- Fancybox END -->
<script>
function show_renew_button(){

    var chk_job_id_count = jQuery(".chk_job_id:checked").length;

    if( chk_job_id_count > 0 ){
        jQuery("#renew_div").show();
    }else{
        jQuery("#renew_div").hide();
    }

}

jQuery(document).ready(function(){

    <?php
    // renew success message
    if( $this->input->get_post('renew_success') == 1 ){ ?>

            swal({
				title: "Success!",
				text: "Renewal Process Successful!",
				type: "success",
				confirmButtonClass: "btn-success",
				showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
				timer: <?php echo $this->config->item('timer') ?>
			});

    <?php
    }
    ?>

    // toggle scripts --- start
    jQuery("#renewals_to_show").change(function(){

        var renewals_to_show_dom = jQuery(this);
        var renewals_to_show_lbl_dom = jQuery("#renewals_to_show_lbl");

        if( renewals_to_show_dom.prop("checked") == true ){
            renewals_to_show_lbl_dom.text('Only Properties With Late Jobs');
        }else{
            renewals_to_show_lbl_dom.text('All Missed Renewals');
        }

    });

    jQuery("#show_fixed_prop").change(function(){

        var renewals_to_show_dom = jQuery(this);
        var renewals_to_show_lbl_dom = jQuery("#show_fixed_prop_lbl");

        if( renewals_to_show_dom.prop("checked") == true ){
            renewals_to_show_lbl_dom.text('Show Fixed Properties');
        }else{
            renewals_to_show_lbl_dom.text('Hide Fixed Properties');
        }

    });

    jQuery("#custom_interval").change(function(){

        var custom_interval_dom = jQuery(this);
        var custom_interval_lbl_dom = jQuery("#custom_interval_lbl");

        var custom_interval = ( custom_interval_dom.prop("checked") == true )?1:0;
        var month_filter = jQuery("#month_filter").val();
        var year_filter = jQuery("#year_filter").val();
        var renewals_to_show = ( jQuery("#renewals_to_show").prop("checked") == true )?1:0;
        var show_fixed_prop = ( jQuery("#show_fixed_prop").prop("checked") == true )?1:0;

        jQuery('#load-screen').show();
		jQuery.ajax({
			type: "POST",
			url: "/reports/pmr_dynamic_query",
			data: { 	
                'custom_interval': custom_interval,				
                'month_filter': month_filter,
                'year_filter': year_filter,
                'renewals_to_show': renewals_to_show,
                'show_fixed_prop': show_fixed_prop,
			}
		}).done(function( ret ){
				
			jQuery('#load-screen').hide();
            jQuery("#agency_filter").html(ret);
		
        });

    });
    // toggle scripts --- end

    jQuery(".chk_job_id").change(function(){

        show_renew_button();

    });

    jQuery("#renew_btn").click(function(){

        var job_status_filter = jQuery("#job_status_filter").val();
        var error = '';

        if( job_status_filter == '' ){
            error += 'Job status is required\n';
        }

        if( error != '' ){ // error

            swal('',error,'error');

        }else{ // success

            swal({
                title: "Warning!",
                text: "This will renew jobs selected. Continue?",
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
                    
                    jQuery("#proccess_renewal_form").submit();					

                }

            });

        }        			

    });

    
    jQuery("#btn_search").click(function(){

        var agency_filter = jQuery("#agency_filter").val();        
        var error = '';

        if( jQuery("#custom_interval").prop("checked") == true ){

            if( agency_filter == '' ){
                error += 'Agency filter is required for Custom Interval\n';
            }

        }
              
        if( error != '' ){ // error

            swal('',error,'error');

        }else{

            jQuery("#header_filter_form").submit();
            
        }

    });

    // check all script
    jQuery("#check-all").change(function(){

        var dom = jQuery(this);
        var is_ticked = dom.prop("checked");

        if( is_ticked == true ){
            jQuery(".chk_job_id:visible").prop("checked",true);
        }else{
            jQuery(".chk_job_id").prop("checked",false);
        }

        show_renew_button();

    });


    // header filter hyperlink fix
    jQuery("#proccess_renewal_form .dtsp-name").each(function(){
 
        var link_dom = jQuery(this);
        var link_txt = link_dom.find("a").html();
        link_dom.html(link_txt);

    });


});
</script>