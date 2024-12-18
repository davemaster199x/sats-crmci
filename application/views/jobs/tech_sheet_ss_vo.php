
<style>
/*
.switchboard_img{
    font-size: 30px;
    margin: 6px 0 0 17px;
}
*/
input.ss_image{
    width: auto;
    margin: 0;
}
.image_stored_lbl{
    margin: 11px 1px 1px 5px;
}
.ss_image_lbl{
    cursor:pointer;
}
.display_block{
    display: block;
}
.ss_pole_add,
.ss_reason_add{
    display: none;
}
</style>
<table class="table main-table">
    <thead>
        <tr>
            <th>Fusebox Viewed</th>

            <!-- Fusebox Viewed YES -->
            <th class="fbv_yes <?php echo ( $job_row->ts_safety_switch == 2 )?'show_it':'hide_it'; ?>">Switchboard Location</th>
            <th class="fbv_yes <?php echo ( $job_row->ts_safety_switch == 2 )?'show_it':'hide_it'; ?>">SS Quantity</th>
           
            <!-- Fusebox Viewed NO -->
            <th class="fbv_no <?php echo ( $job_row->ts_safety_switch == 1 )?'show_it':'hide_it'; ?>">Reason</th>                         
        </tr>
    </thead>

    <tbody>
    <tr>        
        <td>
            <div class="radio">
                <input type="radio" name="ts_safety_switch" class="form-control ts_safety_switch ts_safety_switch_yes chk_yes" id="switchboard_viewed_yes" data-db_table_field="ts_safety_switch" data-db_table="jobs" <?php echo ( $job_row->ts_safety_switch == 2 )?'checked':null; ?> value="2" /> 
                <label class="inline-block" for="switchboard_viewed_yes">Yes</label> 
            </div>
            <div class="radio">
                <input type="radio" name="ts_safety_switch" class="form-control ts_safety_switch ts_safety_switch_no chk_no" id="switchboard_viewed_no" data-db_table_field="ts_safety_switch" data-db_table="jobs" <?php echo ( $job_row->ts_safety_switch == 1 )?'checked':null; ?> value="1" /> 
                <label class="inline-block" for="switchboard_viewed_no">No</label> 
            </div>
        </td>  

        <!-- Fusebox Viewed YES -->
        <td class="fbv_yes <?php echo ( $job_row->ts_safety_switch == 2 )?'show_it':'hide_it'; ?>">
            <input type="text" name="ss_location" id="ss_location" class="form-control ss_location ss_location_main" data-db_table_field="ss_location" data-db_table="jobs" value="<?php echo strtoupper($job_row->ss_location); ?>" />            
        </td> 
        <td class="fbv_yes <?php echo ( $job_row->ts_safety_switch == 2 )?'show_it':'hide_it'; ?>">            
            <input type="number" name="ss_quantity" id="ss_quantity" class="form-control ss_quantity" data-db_table_field="ss_quantity" data-db_table="jobs" value="<?php echo $job_row->ss_quantity; ?>" />
        </td> 
     
        <!-- Fusebox Viewed NO -->
        <td class="fbv_no <?php echo ( $job_row->ts_safety_switch == 1 )?'show_it':'hide_it'; ?>">
            <select id="ts_safety_switch_reason" name="ts_safety_switch_reason" class="form-control ts_safety_switch_reason" data-db_table_field="ts_safety_switch_reason" data-db_table="jobs">                
                <option value="0" <?php echo ( $job_row->ts_safety_switch_reason == 0 && is_numeric($job_row->ts_safety_switch_reason) )? "selected":null; ?>>Circuit Breaker Only</option>
                <option value="1" <?php echo ( $job_row->ts_safety_switch_reason == 1 )? "selected":null; ?>>Unable to Locate</option>
                <option value="2" <?php echo ( $job_row->ts_safety_switch_reason == 2 )? "selected":null; ?>>Unable to Access</option>	                                                            
            </select>	                                             
        </td> 
            
        
    </tr> 
    
    </tbody>
</table>   

<h5>Switch Board Image</h5>
<div class="row fbv_yes <?php echo ( $job_row->ts_safety_switch == 2 )?'display_block':'hide_it'; ?>  mb-3">

    <?php 
    $form_attr = array(
        'id' => 'ss_image_upload_form'
    );
    echo form_open_multipart("/jobs/upload_ss_switchboard_images/?job_id={$this->input->get_post('job_id')}",$form_attr); 
    ?>
    <div class="col d-flex flex-row">
        <?php
        if( $job_row->ss_image != '' ){ 
        
            // dynamic switch of ss image
            if ( file_exists("{$_SERVER['DOCUMENT_ROOT']}/uploads/switchboard_image/{$job_row->ss_image}") ) {   
                // tecsheet CI
                $ss_image_upload_folder = '/uploads/switchboard_image';
            }else{ // old techsheet 
                $ss_image_upload_folder = "{$this->config->item("crm_link")}/images/ss_image";
            }
            
        ?> 
            <a href="<?php echo $ss_image_upload_folder ?>/<?php echo $job_row->ss_image; ?>" data-fancybox="images">                            
                <i class="fa fa-camera switchboard_img view_ss_image mt-2 mr-2"></i>                                             
            </a>
        <?php
        }
        ?>
        <input type="file" name="ss_image" id="ss_image" class="form-control ss_image mr-2" />     

        <input type="hidden" name="ss_image_hid" id="ss_image_hid" class="ss_image_hid" value="<?php echo $job_row->ss_image; ?>" />
        <button type="submit" id="switchboard_images_btn" class="btn <?php echo ( $job_row->ss_image != '' )?'btn-success':null; ?>">
            <?php echo ( $job_row->ss_image != '' )?'Saved':'Upload'; ?>
        </button>
    </div>      
    <?php echo form_close(); ?>  
   
</div>

<!---
<?php
// get new alarms
$ss_sql = $this->db->query("
    SELECT `safety_switch_id`, `make`, `model`, `test`
    FROM `safety_switch`
    WHERE `job_id` = {$this->input->get_post('job_id')}
    AND `discarded` = 0
    ORDER BY `make`
");
if( $ss_sql->num_rows() > 0 ){ ?>
    <table id="ss_table_listing" class="table main-table">

        <thead>
                
            <tr>
                <th>Make</th>
                <th>Model</th>
                <th>Test</th>                
                <th>Discard</th>
            </tr>

        </thead>

        <tbody>

            <?php            
            foreach( $ss_sql->result() as $ss_row ){ ?>
                <tr>    
                    <td>
                        <input type="text" name="ss_make" class="form-control ss_make" value="<?php echo $ss_row->make; ?>" data-db_table_field="make" />
                    </td>
                    <td>
                        <input type="text" name="ss_model" class="form-control ss_model" value="<?php echo $ss_row->model; ?>" data-db_table_field="model" />
                    </td>                 
                    <td>
                        <select name="ss_test" class="form-control ss_test" data-db_table_field="test">
                            <option value="">---</option>
                            <option value="1" <?php echo ( $ss_row->test == 1 )?'selected':null; ?>>Pass</option>
                            <option value="0" <?php echo ( $ss_row->test == 0 && is_numeric($ss_row->test) )?'selected':null; ?>>Fail</option>
                            <option value="2" <?php echo ( $ss_row->test == 2 )?'selected':null; ?>>No Power</option>
                            <option value="3" <?php echo ( $ss_row->test == 3 )?'selected':null; ?>>Not Tested</option>          
                        </select>	                                             
                    </td>                 
                    <td>
                        <input type="hidden" class="safety_switch_id" value="<?php echo $ss_row->safety_switch_id; ?>">
                        <button type="button" id="btn_delete" class="btn btn-danger discard_ss_btn">Discard</button>
                    </td>
                </tr>
            <?php
            }
            ?>                         
            
        </tbody>                                  

    </table>
<?php
}else{ ?>
    <div class="alert alert-danger alert-no-border alert-close alert-dismissible fade show" role="alert">                       
        This Property has no Safety Switch on file. Please add Safety Switch below
    </div>
<?php
}
?>

                            


<button type="button" id="dispay_add_ss_btn" class="btn">Add Safety Switch</button>

<div class="row mt-3">

    <div class="col">
        Safety Switches Tested
        <input type="number" id="ss_items_tested" class="form-control d-inline ss_items_tested" data-db_table_field="ss_items_tested" data-db_table="jobs" value="<?php echo $job_row->ss_items_tested; ?>" />
    </div>

</div>


<div id="add_ss_fb" class="fancybox" style="display:none;" >

    <h4>Add Safety Switch</h4>

    <table id="add_ss_tbl" class="table main-table">

        <thead>
                
            <tr>
                <th>New?</th>
                <th>Reason</th>
                <th>Pole</th>
                <th>Make</th>
                <th>Model</th>    
                <th>Test</th>           
            </tr>

        </thead>

        <tbody class="add_ss_tbody">

            <tr class="ss_tr">   
                <td>
                    <select class="form-control ss_new_add">
                        <option value="">---</option>
                        <option value="1">Yes</option>
                        <option value="0">Existing</option>                                                     
                    </select>	
                </td>  
                <td>
                    <select class="form-control ss_reason_add">
                        <option value="">---</option>
                        <?php
                        // get safety switch reason
                        $ss_reason_sql = $this->db->query("
                        SELECT 
                            `ss_res_id`,    
                            `reason`                        
                        FROM `safety_switch_reason`
                        ");
                        foreach( $ss_reason_sql->result() as $ss_reason_row ){ ?>
                            <option value='<?php echo $ss_reason_row->ss_res_id; ?>'><?php echo $ss_reason_row->reason; ?></option>
                        <?php
                        }
                        ?>
                    </select>	
                </td>
                <td>
                    <select class="form-control ss_pole_add">
                        <option value="">---</option>
                        <?php
                        // get safety switch stocks
                        $ss_stock_sql = $this->db->query("
                        SELECT 
                            `ss_stock_id`,
                            `pole`,
                            `make`,
                            `model`                            
                        FROM `safety_switch_stock`
                        WHERE `active` = 1
                        ");
                        foreach( $ss_stock_sql->result() as $ss_stock_row ){ ?>
                            <option 
                                value="<?php echo $ss_stock_row->ss_stock_id; ?>"
                                data-ss_stock_make="<?php echo $ss_stock_row->make; ?>"
                                data-ss_stock_model="<?php echo $ss_stock_row->model; ?>"
                            >
                                <?php echo $ss_stock_row->pole; ?> Pole
                            </option>
                        <?php
                        }
                        ?>                                                   
                    </select>	
                </td>                
                <td>
                    <input type="text" class="form-control ss_make_add" />
                </td>                
                <td>
                    <input type="text" class="form-control ss_model_add" />
                </td>
                <td>
                    <select class="form-control ss_test_add">
                        <option value="">---</option>
                        <option value="1">Pass</option>
                        <option value="0">Fail</option>
                        <option value="2">No Power</option>
                        <option value="3">Not Tested</option>                                                     
                    </select>	 
                </td>
            </tr>             
           
        </tbody>                                  

    </table>

    <button type="button" id="add_ss_btn" class="btn btn-success">Add another Safety Switch</button>
    <button type="button" id="save_ss_btn" class="btn float-right">Save and Close</button>	

</div>
--->

<script>
function ts_ajax_safety_switch_inline_update(dom){
    
    var parent_row = dom.parents("tr:first");
    var db_table_field = dom.attr("data-db_table_field");
    var db_table_value  = dom.val(); 
    var safety_switch_id = parent_row.find(".safety_switch_id").val();

    // job update
    if( safety_switch_id > 0 ){

        //jQuery('#load-screen').show();
        jQuery(".techsheet_tab_next:visible").prop("disabled",true); // disable NEXT button

        jQuery.ajax({
            type: "POST",
            url: "/jobs/ajax_techsheet_safety_switch_row_update",
            data: { 
                safety_switch_id: safety_switch_id,
                db_table_field: db_table_field,
                db_table_value: db_table_value
            }
        }).done(function( ret ){

            //jQuery('#load-screen').hide(); 
            jQuery(".techsheet_tab_next:visible").prop("disabled",false); // enable NEXT button                            			

        });

    }

}
jQuery(document).ready(function(){

    // display add alarm form
    jQuery("#dispay_add_ss_btn").click(function(){

        $.fancybox.open({
            src  : '#add_ss_fb'
        });

    });

    // add more alarm to the form
    jQuery("#add_ss_btn").click(function(){

        var last_row = jQuery(".add_ss_tbody:last");
        var last_row_clone = last_row.clone();

        // get last row data
        var last_row_ss_new = last_row.find('.ss_new_add').val();
        var last_row_ss_reason = last_row.find('.ss_reason_add').val();
        var last_row_ss_pole = last_row.find('.ss_pole_add').val();
      
        // set clone value from last row data
        last_row_clone.find('.ss_new_add').val(last_row_ss_new);
        last_row_clone.find('.ss_reason_add').val(last_row_ss_reason);
        last_row_clone.find('.ss_pole_add').val(last_row_ss_pole);

        // insert clone in last row of table
        jQuery("#add_ss_tbl").append(last_row_clone);

    });


    // Add Safety Switch
	jQuery("#save_ss_btn").click(function(){
		
		var dom = jQuery(this); 
        var ss_arr = [];

        var error_arr = [];
        jQuery(".ss_tr").each(function(){

            dom = jQuery(this);
            
            var ss_make = dom.find(".ss_make_add").val();
            var ss_model = dom.find(".ss_model_add").val();
            var ss_test = dom.find(".ss_test_add").val();
            var ss_new = dom.find(".ss_new_add").val();
            var ss_pole = dom.find(".ss_pole_add").val();
            var ss_reason = dom.find(".ss_reason_add").val();


            if( ss_new == '' ){
                var error_txt = 'Please Select if Safety Switch is New or Existing';
                if( error_arr.includes(error_txt) == false ){
                    error_arr.push(error_txt);
                }
                
            } 
            
            if( ss_make == '' ){
                var error_txt = 'Make is required';
                if( error_arr.includes(error_txt) == false ){
                    error_arr.push(error_txt);
                }
                
            } 

            if( ss_model == '' ){
                var error_txt = 'Model is required';
                if( error_arr.includes(error_txt) == false ){
                    error_arr.push(error_txt);
                }
                
            }   

            if( ss_test == '' ){
                var error_txt = 'Test is required';
                if( error_arr.includes(error_txt) == false ){
                    error_arr.push(error_txt);
                }
                
            }

            // new
            if( ss_new == 1 ){ 

                if( ss_pole == '' ){

                    var error_txt = 'Pole is required for New Safety Switch';
                    if( error_arr.includes(error_txt) == false ){
                        error_arr.push(error_txt);
                    }
                    
                }
                
                if( ss_reason == '' ){

                    var error_txt = 'Reason is required for New Safety Switch';
                    if( error_arr.includes(error_txt) == false ){
                        error_arr.push(error_txt);
                    }

                }
                
            }
          
            json_data = {
                'ss_make': ss_make,
                'ss_model': ss_model,
                'ss_test': ss_test,
                'ss_new': ss_new,
                'ss_pole': ss_pole,
                'ss_reason': ss_reason
            }
            var json_str = JSON.stringify(json_data);

            ss_arr.push(json_str);

        });  

        //console.log(error_arr); 

        if( error_arr.length > 0 ){
            
            error_str = '';
            for( var i = 0; i < error_arr.length; i++ ){
                error_str += error_arr[i]+"\n";
            }

            swal('',error_str,'error');
            
        }else{

            
            jQuery('#load-screen').show();
            jQuery(".techsheet_tab_next:visible").prop("disabled",true); // disable NEXT button

            jQuery.ajax({
                type: "POST",
                url: "/jobs/ajax_add_safety_switch",
                data: {
                    job_id: <?php echo $this->input->get_post('job_id'); ?>,                    
                    ss_arr: ss_arr
                }
            }).done(function (ret) {

                jQuery('#load-screen').hide();
                jQuery(".techsheet_tab_next:visible").prop("disabled",false); // enable NEXT button
                location.reload();

            });
            
            

        }               
		
				
	});


    // delete safety switch
    jQuery(".discard_ss_btn").click(function(){

        var dom = jQuery(this);
        var parent_row = dom.parents("tr:first");
        
        var safety_switch_id = parent_row.find(".safety_switch_id").val();

        var swal_html_content = ""+
        "<p class='text-center'>This will discard this safety switch. <br />"+
        "Please select reason for discarding.</p>"+
        "<select class='form-control' id='ss_discard_reason'>"+
        "<option value=''>---</option>"+
        <?php
        // get safety switch reason
        $ss_reason_sql = $this->db->query("
        SELECT 
            `ss_res_id`,    
            `reason`                        
        FROM `safety_switch_reason`
        ");
        foreach( $ss_reason_sql->result() as $ss_reason_row ){ ?>
            "<option value='<?php echo $ss_reason_row->ss_res_id; ?>'><?php echo $ss_reason_row->reason; ?></option>"+
        <?php
        }
        ?>             
        "</select>"+
        "";        

        swal({
            html: true,
            title: "",
            text: swal_html_content,
            type: "warning",						
            showCancelButton: true,
            confirmButtonClass: "btn-success continue_discard_btn d-none",
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
                jQuery(".techsheet_tab_next:visible").prop("disabled",true); // disable NEXT button

                var ss_discard_reason = jQuery("#ss_discard_reason").val();

                jQuery.ajax({
                    type: "POST",
                    url: "/jobs/ajax_discard_techsheet_safety_switch",
                    data: {
                        job_id: <?php echo $this->input->get_post('job_id'); ?>,                        
                        safety_switch_id: safety_switch_id,
                        ss_discard_reason: ss_discard_reason
                    }
                }).done(function (ret) {

                    jQuery('#load-screen').hide();
                    jQuery(".techsheet_tab_next:visible").prop("disabled",false); // enable NEXT button
                    location.reload();

                });

            }

        });	

    });


    // show/hide continue discard button
    jQuery(document).on('change',"#ss_discard_reason",function(){

        var ss_discard_reason_dom = jQuery(this);

        if( ss_discard_reason_dom.val() > 0 ){

            jQuery(".continue_discard_btn").removeClass('d-none');

        }else{

            jQuery(".continue_discard_btn").addClass('d-none');

        } 

    });


    // corded window inline ajax update        
    jQuery(".ss_make, .ss_model, .ss_test",).change(function(){

        var dom = jQuery(this);         
        ts_ajax_safety_switch_inline_update(dom);   

    });


    jQuery(".ts_safety_switch_yes").click(function(){

        var dom = jQuery(this);
        var ts_safety_switch = dom.val();

        jQuery(".fbv_yes").show();
        jQuery(".fbv_no").hide();

    });


    jQuery(".ts_safety_switch_no").click(function(){

        var dom = jQuery(this);
        var ts_safety_switch = dom.val();

        jQuery(".fbv_yes").hide();
        jQuery(".fbv_no").show();

    });


    jQuery("#ss_image_upload_form").submit(function(){

        var ts_safety_switch  = jQuery(".ts_safety_switch:checked").val();
        var ss_image  = jQuery("#ss_image").val(); 
        
        if( ts_safety_switch == 2 && ss_image == '' ){     

            swal('','Please select/capture Switch Board Image','error');  
            return false;  

        }else{
            return true;
        }


    });

    // detect selected/captured file
    jQuery("input.ss_image[type='file']").change(function(){

        var ss_image = jQuery("input.ss_image[type='file']").val();
        var upload_btn_dom = jQuery("#switchboard_images_btn");
        
        if( ss_image != '' ){
            upload_btn_dom.text('Upload'); 
            upload_btn_dom.removeClass('btn-success'); 
        }          

    });
    
    jQuery("#add_ss_tbl").on('change','.ss_new_add',function(){

        var ss_new_dom = jQuery(this);
        var parent_tr = ss_new_dom.parents("tr:first");

        if( ss_new_dom.val() == 1 ){ // new

            parent_tr.find(".ss_pole_add").show();
            parent_tr.find(".ss_reason_add").show();

        }else{ // existing

            parent_tr.find(".ss_pole_add").hide();
            parent_tr.find(".ss_reason_add").hide();

            // clear
            parent_tr.find(".ss_pole_add").val('');
            parent_tr.find(".ss_make_add").val('');
            parent_tr.find(".ss_model_add").val('');

        }

    });

    jQuery("#add_ss_tbl").on('change','.ss_pole_add',function(){

        var ss_pole_dom = jQuery(this);
        var parent_tr = ss_pole_dom.parents("tr:first");

        // get selected dropdown
        var ss_pole_selected = ss_pole_dom.find("option:selected");

        // get make and model from data attribute
        var ss_stock_make = ss_pole_selected.attr("data-ss_stock_make");
        var ss_stock_model = ss_pole_selected.attr("data-ss_stock_model");

        // prefill
        parent_tr.find(".ss_make_add").val(ss_stock_make);
        parent_tr.find(".ss_model_add").val(ss_stock_model);
      
    });

});
</script>