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
                                    foreach( $distinct_agency->result() as $distinct_row ){ ?>
                                        <option value="<?php echo $distinct_row->agency_id; ?>" <?php echo ( $distinct_row->agency_id == $this->input->get_post('agency_filter') )?'selected':null;  ?>>
                                            <?php echo $distinct_row->agency_name; ?>
                                        </option>
                                    <?php
                                    }
                                    ?>
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
    

	<div class="body-typical-body">
		<div class="table-responsive">

            <?php
            if( $this->input->get_post('agency_filter') ){ ?>

                <table class="table table-hover main-table table-striped text-center" id="serverside-table">
                    <thead>
                        <tr>    
                            <th rowspan="2">Agency</th>
                            <th colspan="2">Creditor</th>
                            <th colspan="2">Account</th>                        
                            <th colspan="2">Property Compliance Category</th>
                            <th rowspan="2">Action</th>                              									                            						                           
                        </tr>
                        <tr>             
                            <th>Name</th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>ID</th>
                        </tr>
                        <?php   
                        $country_txt = ( $this->config->item('country') == 1 )?'AUSTRALIA':'NEW ZEALAND';                      
                        foreach( $pt_connected_agency_sql->result() as $pt_connected_agency_row ){                      
                        ?>
                            <tr>
                                <td class="pt_agency_name">
                                    <a href="/agency/view_agency_details/<?php echo $pt_connected_agency_row->agency_id; ?>"><?php echo $pt_connected_agency_row->agency_name; ?></a>
                                </td>    
                                <td>
                                    <?php
                                    $ret_obj = $this->property_tree_model->get_creditors($pt_connected_agency_row->agency_id);
                                    if( $ret_obj->httpcode == 200 ){

                                        foreach( $ret_obj->json_decoded_response as $creditor_row ){

                                            if( $creditor_row->creditor_id == $pt_connected_agency_row->creditor ){
                                                echo $creditor_row->name;
                                            }

                                        }

                                    }
                                    ?>
                                </td> 
                                <td><?php echo $pt_connected_agency_row->creditor; ?></td>
                                <td>
                                    <?php
                                    $ret_obj = $this->property_tree_model->get_accounts($pt_connected_agency_row->agency_id);
                                    if( $ret_obj->httpcode == 200 ){

                                        foreach( $ret_obj->json_decoded_response as $account_row ){

                                            if( $account_row->id == $pt_connected_agency_row->account ){
                                                echo $account_row->name;
                                            }

                                        }

                                    }
                                    ?>
                                </td>
                                <td><?php echo $pt_connected_agency_row->account; ?></td>  
                                <td>
                                    <?php
                                    $ret_obj = $this->property_tree_model->property_compliance_categories($pt_connected_agency_row->agency_id);
                                    if( $ret_obj->httpcode == 200 ){

                                        foreach( $ret_obj->json_decoded_response as $comp_cat_row ){

                                            if( $comp_cat_row->category_id == $pt_connected_agency_row->prop_comp_cat ){
                                                echo $comp_cat_row->category_name;
                                            }

                                        }

                                    }
                                    ?>
                                </td>                                                   
                                <td><?php echo $pt_connected_agency_row->prop_comp_cat; ?></td>
                                <td>
                                    <input type="hidden" class="agency" value="<?php echo $pt_connected_agency_row->agency_id; ?>" />
                                    <button type="button" class="btn set_agency_preference_btn">Settings</button>                                   
                                </td>
                            </tr>
                        <?php
                        }                    
                        ?>
                    </thead>
                    <tbody></tbody>
                </table>

            <?php
            }
            ?>
				
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

    <div id="pt_preference_tbl_div"></div>

    <div class="text-right mt-2">
        <input type="hidden" id="agency_id" />
        <button type="button" id="pt_select_preference_btn" class="btn">Update</button> 
    </div>
    
</div>
<!-- Fancybox END -->

<script>
jQuery(document).ready(function(){
    			
   // set agency preference
   jQuery(".set_agency_preference_btn").click(function(){

        var btn_connect_dom = jQuery(this); 
        var parent_tr = btn_connect_dom.parents("tr:first");

        var agency = parent_tr.find(".agency").val();

        var pt_select_settings_fb = jQuery("#pt_select_settings");

        if( agency > 0 ){

            jQuery('#load-screen').show(); 
            jQuery.ajax({
                url: "/property_tree/display_agency_preference",
                type: 'POST',
                data: { 
                    'agency': agency
                }
            }).done(function( ret ){

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

    }); 

    // save propertytree agency preference
    jQuery("#pt_select_preference_btn").click(function(){

        var pt_select_settings_fb = jQuery("#pt_select_settings");

        var agency_id = pt_select_settings_fb.find("#agency_id").val();
        var creditor = pt_select_settings_fb.find("#pt_creditor").val();
        var account = pt_select_settings_fb.find("#pt_account").val();
        var prop_comp_cat = pt_select_settings_fb.find("#pt_prop_comp_cat").val();

        var error = '';         

        if( agency_id > 0 ){

            if( creditor == '' ){
                error += 'Creditor is Required\n';
            }

            if( account == '' ){
                error += 'Account Code is Required\n';
            }

            if( prop_comp_cat == '' ){
                error += 'Property Compliance Category is Required\n';
            }

            if( error != '' ){ // error
                swal('',error,'error');
            }else{

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
                        text: "Property Tree Configuration Updated Successfully!",
                        type: "success",
                        confirmButtonClass: "btn-success",
                        showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                        timer: <?php echo $this->config->item('timer') ?>
                    });
                    setTimeout(function(){ window.location='/property_tree/agency_preference' }, <?php echo $this->config->item('timer') ?>);                                       

                });
                
            }                    

        }        

    }); 
    
});
</script>