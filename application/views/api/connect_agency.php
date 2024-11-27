<style>
	.date_div {
		width: auto;
		margin-right: 13px;
	}

	table.dataTable thead > tr > th {
		padding-left: 10px !important;
		padding-right: initial !important;
	}

	table.dataTable thead .sorting:after,
	table.dataTable thead .sorting_asc:after,
	table.dataTable thead .sorting_desc:after {
		left: 80px !important;
		right: auto !important;
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
				'link' =>  $uri
			)
		);
		$bc_data['bc_items'] = $bc_items;
		$this->load->view('templates/breadcrumbs', $bc_data);
	?>
    <!--
	<header class="box-typical-header">
		<form action="<?php $uri; ?>" method="post">
			<div class="box-typical box-typical-padding">
				<div class="for-groupss row">
					<div class="col-md-10 columns">
						<div class="row">	

							<div class="ml-2">
								<label for="agency_filter">Agency</label>
								<select id="agency_filter" name="agency_filter"  class="form-control">                                
									<option value="">---</option>
                                    <?php
                                    /*
                                    foreach( $distinct_agency_sql->result() as $distinct_agency_row ){ ?>
                                        <option value="<?php echo $distinct_agency_row->agency_id; ?>" <?php echo ( $distinct_agency_row->agency_id == $this->input->get_post('agency_filter') )?'selected':null;  ?>>
                                            <?php echo $distinct_agency_row->agency_name; ?>
                                        </option>
                                    <?php
                                    }
                                    */
                                    ?>
								</select>							
							</div>

                            <div class="ml-2">
								<label for="job_status_filter">Job Status</label>
								<select id="job_status_filter" name="job_status_filter"  class="form-control">                                
									<option value="">---</option>
                                    <option value="Completed" <?php echo ( $this->input->get_post('job_status_filter') == 'Completed' )?'selected':null;  ?>>Completed</option>
                                    <option value="Cancelled" <?php echo ( $this->input->get_post('job_status_filter') == 'Cancelled' )?'selected':null;  ?>>Cancelled</option>
								</select>							
							</div>

							<div class="ml-2">
								<label class="col-sm-12 form-control-label">&nbsp;</label>
								<input type="submit" name="search_submit" id="search_submit" value="Search" class="btn">
							</div>				
						</div>
					</div>
				</div>
			</div>
		</form>
	</header>
    -->

	<div class="body-typical-body">
		<div class="table-responsive">
			<table class="table table-hover main-table table-striped" id="serverside-table">
				<thead>
                    <tr>    
                        <th>Company</th>
                        <th>Address</th>
                        <th>Email</th>
                        <th>Phone</th>                        
                        <th>Connect to Agency</th>
                        <th>Action</th>                              									                            						                           
                    </tr>
                </thead>									
                <tbody>
                    <?php   
                    if( count($pt_auth_key_arr) > 0 ){

                        $country_txt = ( $this->config->item('country') == 1 )?'AUSTRALIA':'NEW ZEALAND'; 
                        
                        // Initialize request counter and timestamp
                        $this->property_tree_model->set_request_counter(0);
                        $this->property_tree_model->set_start_time(time());

                        foreach( $pt_auth_key_arr as $pt_auth_key_obj ){                                             

                            if( !in_array($pt_auth_key_obj->auth_key,$current_auth_keys_arr) ){ // hide already connected agencies

                                // get agency details to display
                                $json_obj = $this->property_tree_model->get_agency_details($pt_auth_key_obj->auth_key);                        
                                $address_obj = $json_obj->address;

                                // AU and NZ are in 1 accounts, so it needs country filter to display correct data per country
                                if( $address_obj->country == $country_txt ){

                                    // street
                                    if( $address_obj->unit != '' && $address_obj->street_number != '' ){
                                        $street_unit_num = "{$address_obj->unit}/{$address_obj->street_number}";
                                    }else if( $address_obj->unit != '' ){
                                        $street_unit_num = "{$address_obj->unit}";
                                    }else if( $address_obj->street_number != '' ){
                                        $street_unit_num = "{$address_obj->street_number}";
                                    }

                                    $agency_address = "{$street_unit_num} {$address_obj->address_line_1} {$address_obj->suburb} {$address_obj->state} {$address_obj->post_code}";    
                                    ?>

                                    <tr>
                                        <td class="pt_agency_name"><?php echo $pt_auth_key_obj->company_name; ?></td>
                                        <td><?php echo $agency_address ?></td>
                                        <td class="pt_email"><?php echo $json_obj->email_address; ?></td>
                                        <td><?php echo $json_obj->phone_number; ?></td>                                                       
                                        <td>
                                            <?php
                                            if( in_array($pt_auth_key_obj->auth_key,$current_auth_keys_arr) ){ // connected 
                                                echo "already connected";
                                            }else{ // NOT connected 
                                            ?>
                                                <select class="form-control agency">                                
                                                    <option value="">---</option>     
                                                    <?php
                                                    foreach( $agency_sql->result() as $agency_row ){ ?>
                                                        <option value="<?php echo $agency_row->agency_id; ?>"><?php echo $agency_row->agency_name; ?></option>
                                                    <?php
                                                    }
                                                    ?>                               
                                                </select>
                                            <?php
                                            }
                                            ?>                                        	
                                        </td>
                                        <td>
                                            <input type="hidden" class="auth_key" value="<?php echo $pt_auth_key_obj->auth_key; ?>" />
                                            <?php
                                            if( !in_array($pt_auth_key_obj->auth_key,$current_auth_keys_arr) ){ ?>
                                                <button type="button" class="btn btn_connect">Connect</button>
                                            <?php
                                            }
                                            ?>                                        
                                            <button type="button" class="btn btn-danger btn_hide">Remove</button>                                    
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
		</div>
	</div>

	<nav aria-label="Page navigation example" style="text-align:center">
		<?php echo $pagination; ?>
	</nav>

	<div class="pagi_count text-center">
		<?php echo $pagi_count; ?>
	</div>

</div>


<!-- Fancybox START -->
<!-- ABOUT TEXT -->
<a href="javascript:;" id="about_page_fb_link" class="fb_trigger" data-fancybox data-src="#about_page_fb">Trigger the fancybox</a>							
<div id="about_page_fb" class="fancybox" style="display:none;" >
	<h4><?php echo $title; ?></h4>
	<p>Lorem Ipsum</p>
</div>

<div id="pt_select_settings" class="fancybox" style="display:none;"> 

    <p>Almost done! please select these settings to finish</p>    

    <div id="pt_preference_tbl_div"></div>

    <div class="text-right mt-2">
        <input type="hidden" id="agency_id" />
        <button type="button" id="pt_select_preference_btn" class="btn">Finish</button> 
    </div>
    
</div>
<!-- Fancybox END -->

<script>
function display_agency_preference(agency){

    var pt_select_settings_fb = jQuery("#pt_select_settings");

    if( agency > 0 ){

        jQuery.ajax({
            url: "/property_tree/display_agency_preference",
            type: 'POST',
            data: { 
                'agency': agency
            }
        }).done(function( ret ){

            // prefill
            // agency ID
            pt_select_settings_fb.find("#agency_id").val(agency);

            jQuery("#pt_preference_tbl_div").html(ret);       

            // launch fancybox
            $.fancybox.open({
                src  : '#pt_select_settings'
            });
            
            jQuery('#load-screen').hide();                         

        }); 

    }    

}

jQuery(document).ready(function(){
    			
    // connect propertytree authentication key to agency
    jQuery(".btn_connect").click(function(){

        var btn_connect_dom = jQuery(this); 
        var parent_tr = btn_connect_dom.parents("tr:first");

        var pt_select_settings_fb = jQuery("#pt_select_settings");

        var agency = parent_tr.find(".agency").val();
        var auth_key = parent_tr.find(".auth_key").val(); 
        var pt_agency_name = parent_tr.find(".pt_agency_name").text();         
        var agency_name = parent_tr.find(".agency option:selected").text();
        var pt_email = parent_tr.find(".pt_email").text();  
        
        if( agency > 0 ){            

            if( confirm( "This will connect Property Tree "+pt_agency_name+" to "+agency_name+". Proceed?" ) ){           
            
                if( agency > 0 && auth_key != '' ){

                    jQuery('#load-screen').show(); 
                    jQuery.ajax({
                        url: "/property_tree/ajax_connect_agency",
                        type: 'POST',
                        data: { 
                            'agency': agency,
                            'auth_key': auth_key,
                            'pt_email' : pt_email
                        }
                    }).done(function( ret ){
    
                       //display_agency_preference(agency); 
                       
                       jQuery('#load-screen').hide();
                       swal({
                            title: "Success!",
                            text: "Agency partially connected",
                            type: "success",
                            confirmButtonClass: "btn-success",
                            showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                            timer: <?php echo $this->config->item('timer') ?>
                        });
                        setTimeout(function(){ location.reload(); }, <?php echo $this->config->item('timer') ?>);    

                    });

                }            

            }

        }else{
            alert("Please select agency to connect");
        }      
        
    });     

    // save propertytree agency preference
    jQuery("#pt_select_preference_btn").click(function(){

        var pt_select_settings_fb = jQuery("#pt_select_settings");

        var agency_id = pt_select_settings_fb.find("#agency_id").val();
        var creditor = pt_select_settings_fb.find("#pt_creditor").val();
        var account = pt_select_settings_fb.find("#pt_account").val();
        var prop_comp_cat = pt_select_settings_fb.find("#pt_prop_comp_cat").val();

        if( agency_id > 0 ){
            
            jQuery('#load-screen').show(); 
            jQuery.ajax({
                url: "/property_tree/save_agency_preference",
                type: 'POST',
                data: { 
                    'agency_id': agency_id,
                    'creditor': creditor,
                    'account': account,
                    'prop_comp_cat': prop_comp_cat,
                }
            }).done(function( ret ){

                jQuery('#load-screen').hide(); 
                swal({
                    title: "Success!",
                    text: "Agency Connected Successfully!",
                    type: "success",
                    confirmButtonClass: "btn-success",
                    showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                    timer: <?php echo $this->config->item('timer') ?>
                });
                setTimeout(function(){ location.reload(); }, <?php echo $this->config->item('timer') ?>);                                       

            });

        }        

    });  

    jQuery(".btn_hide").click(function(){

        var btn_hide_dom = jQuery(this); 
        var parent_tr = btn_hide_dom.parents("tr:first");

        var auth_key = parent_tr.find(".auth_key").val();
        
        if( auth_key != '' ){
                                
            swal({
                title: "Warning!",
                text: "This will remove agency API key for PropertyTree API. Do you want to continue?",
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
                    
                    jQuery('#load-screen').show(); 
                    jQuery.ajax({
                        url: "/property_tree/hide_app_key_pairs",
                        type: 'POST',
                        data: { 
                            'auth_key': auth_key
                        }
                    }).done(function( ret ){

                        jQuery('#load-screen').hide(); 
                        location.reload();                                      

                    });                                                                

                }

            });	

        }        	            

    }); 
    
});
</script>