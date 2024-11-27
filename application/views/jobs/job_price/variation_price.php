    
<div id="job_price_box_detail_toggle">

    <?php 
        if($has_variation===TRUE){
            echo $price_html;
        }else{
            if($pc_excluded === TRUE){
                echo "<a href='#' data-fancybox='' data-src='#change_price_div_fb'>No Price Variation</a>";
            }else{
                echo "<a data-fancybox='' data-src='#job_price_variation_fb' href='#' class='fancybox'>No Price Variation</a>";
            }
        }
    ?>

</div>

<!-- Old price change fancybox-->
<div id="change_price_div_fb" style="display:none;">
    <div class="form-group">
        <label>Price</label>
        <div class="form-control-wrapper form-control-icon-left">
            <input type="text" id="job_price" class="tenantinput form-control price_field" value="<?php echo $job_row['j_price'] ?>">
            <i class="fa fa-dollar"></i>
        </div>
    </div>
    <div class="form-group">
        <label>Reason</label>
        <select id="price_reason" name="price_reason" class="form-control price_reason">
            <option value="">---</option>
            <option value="FOC" <?php echo ($job_row['price_reason'] == 'FOC') ? 'selected="selected"' : ''; ?>>FOC</option>
            <option value="Price match" <?php echo ($job_row['price_reason'] == 'Price match') ? 'selected="selected"' : ''; ?>>Price match</option>
            <option value="Multiple properties" <?php echo ($job_row['price_reason'] == 'Multiple properties') ? 'selected="selected"' : ''; ?>>Multiple properties</option>
            <option value="Agents Property" <?php echo ($job_row['price_reason'] == 'Agents Property') ? 'selected="selected"' : ''; ?>>Agents Property</option>
            <option value="Other" <?php echo ($job_row['price_reason'] == 'Other') ? 'selected="selected"' : ''; ?>>Other</option>
        </select>
    </div>
    <div class="form-group">
        <label>Details</label>
        <input type="text" id="price_detail" name="price_detail" class="proptenantinput form-control price_detail" value="<?php echo $job_row['price_detail'] ?>">
    </div>
    <div class="form-group">
        <?php if($can_edit_price==true){ ?>
        <button type="button" class="btn btn_update_price">Update Price</button>&nbsp;
        <button type="button" class="btn btn_update_all_price">Update Job/Service Price</button>
        <?php } ?>
    </div>
</div>
<!-- Old price change fancybox end-->

<!-- job variation fancybox -->
<div id="job_price_variation_fb" style="display:none;">

    <?php 
    $form_attr = array(
        'id' => 'add_job_variation_form'
    );
    echo form_open(base_url("/jobs/details/{$job_row['jid']}"),$form_attr);
    ?>

        <section class="card card-blue-fill"> 
            <header class="card-header">Job Price Variation</header>
            <div class="card-block">
                
                    <div class="form-group" id="make_ym_tr">

                    <?php
                        $prop_price_var_params = array(
                            'service_type' => $job_row['j_service'],
                            'property_id' => $job_row['prop_id']
                        );
                        $prop_price_var_arr = $this->system_model->get_property_price_variation($prop_price_var_params);
                    ?>
                    
                        <div class="checkbox">
                            <input type="checkbox" id="make_ym">
                            <label for="make_ym">Change Job Type to Yearly Maintenance</label>

                            <input type="hidden" id="ppv_price" value="<?php echo $prop_price_var_arr['dynamic_price_total']; ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label id="amount_th">Variation Amount</label>
                        <div class="form-control-wrapper form-control-icon-left">
                        <input class="form-control" type="text" id="job_variation_amount" name="job_variation_amount" value="<?php echo ( $jv_row->amount > 0 )?number_format($jv_row->amount, 2):null; ?>">
                            <i class="fa fa-dollar"></i>
                        </div>
                    </div>

                    <div id="exclude_for_ym_div">
                        <div class="form-group">
                            <label>Type (Discount/Surcharge)</label>
                            <select id="apv_type" style="width:100%;" class="form-control" required>
                                <option value="">---</option>
                                <option value="1" <?php echo ( $jv_row->type == 1 )?'selected':null; ?>>Discount</option>
                                <option value="2" <?php echo ( $jv_row->type == 2 )?'selected':null; ?>>Surcharge</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Reason for Discount/Surcharge</label>
                            <select id="apv_reason" name="apv_reason"  class="form-control apv_reason" required>
                                <option value="">---</option>
                                <?php
                                    foreach( $agency_price_variation_reason_list->result() as $adr_row ){
                                ?>
                                    <option data-is_discount="<?php echo $adr_row->is_discount; ?>" value="<?php echo $adr_row->id; ?>" <?php echo ( $adr_row->id == $jv_row->reason )?'selected':null; ?>><?php echo $adr_row->reason; ?></option>
                                <?php
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <?php
                            //get selected display_on
                            $dv_type = 2; // job
                            $dv_sql = $this->db->query("
                            SELECT dv.`display_on`
                            FROM `display_variation` AS dv
                            LEFT JOIN `job_variation` AS jv ON ( dv.`variation_id` = jv.`id` && dv.`type` = $dv_type )   
                            WHERE jv.`job_id` = {$job_row['jid']}    
                            AND jv.`active` = 1
                            ");
                            $dv_row = $dv_sql->row();
                            //get selected display_on end
                            ?>
                            <label>Display On</label>
                            <select id="display_on" name="display_on"  class="form-control display_on">
                                <option value="">Do Not Display</option>
                                <?php 
                                    foreach( $display_on_list->result() as $display_on_row ){
                                ?>
                                    <option value="<?php echo $display_on_row->id; ?>" <?php echo ( $display_on_row->id == $dv_row->display_on )?'selected':null; ?>><?php echo $display_on_row->location; ?></option>
                                <?php
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
            </div>   
        </section>

        <?php if($can_edit_price==true){ ?>
        <input type="hidden" id="jv_id" value="<?php echo $jv_row->id; ?>" />
        <div class="row">
            <div class="col-md-6 text-left">
                <?php if( $jv_row->id!="" ){ ?>
                <button type="button" id="delete_job_price_variation" class="btn btn-danger">Delete</button>
                <?php } ?>
            </div>
            <div class="col-md-6 text-right">
                <button class="btn" type="submit">Update</button>
            </div>
        </div>
        <?php 

            }else{
                echo "<span class='text-red'>Completed Jobs can't be Edited</span>";
            } 
        ?>

    </form>
</div>
<!-- job variation fancybox end -->
                            

<script type="text/javascript">

    // Set global var
    var agency_status = '<?php echo $job_row['a_status']; ?>';
    var job_id = <?php echo $job_row['jid']; ?>;
    var property_id = <?php echo $job_row['prop_id']; ?>;
    var agency_id = <?php echo $job_row['a_id']; ?>;

    function show_hide_make_ym(){
        var job_type = jQuery("#job_type").val();

        if( job_type == 'Yearly Maintenance' ){
            jQuery("#make_ym_tr").hide();
        }else{
            jQuery("#make_ym_tr").show();
        }
    }

    function display_dynamic_variation_reason(apv_type)
    {

        if( apv_type == 1 ){ // discount

            jQuery("#apv_reason option[data-is_discount=1]").show(); // discount
            jQuery("#apv_reason option[data-is_discount=0]").hide(); // surcharge   
            
            
            <?php 
            if( $dv_sql->num_rows() == 0 ){ ?>
                jQuery("#display_on option[value='7']").prop("selected",true); // Invoice & Agency Portal
            <?php
            }
            ?>  
            
        }else{ // surcharge

            jQuery("#apv_reason option[data-is_discount=1]").hide(); // discount
            jQuery("#apv_reason option[data-is_discount=0]").show(); // surcharge 

            <?php 
            if( $dv_sql->num_rows() == 0 ){ ?>
                jQuery("#display_on option[value='']").prop("selected",true); // Do Not Display
            <?php
            }
            ?>           

        }

    }

    $(document).ready(function(){

         // display dynamic variation reason, on load
         var apv_type = jQuery("#apv_type").val();
        display_dynamic_variation_reason(apv_type);

        jQuery("#apv_type").change(function(){
            var apv_type = jQuery(this).val();
            display_dynamic_variation_reason(apv_type);
        });

        show_hide_make_ym();

        jQuery("#make_ym").change(function(){

            var make_ym_dom = jQuery(this);
            var job_variation_amount = jQuery("#job_variation_amount");

            if( make_ym_dom.prop("checked") == true ){ // ticked, make type and reason not required

                jQuery("#amount_th").text('Job Price');

                var ppv_price = jQuery("#ppv_price").val();
                job_variation_amount.val(ppv_price);
                job_variation_amount.prop("readonly",true);

                jQuery("#apv_type").prop("required",false);                
                jQuery("#apv_reason").prop("required",false);
                jQuery("#display_on").prop("required",false);

                // hide row
                jQuery("#exclude_for_ym_div").hide();

            }else{ // not ticked, put back type and reason as required

                jQuery("#amount_th").text('Variation Amount');

                job_variation_amount.val("");
                job_variation_amount.prop("readonly",false);

                jQuery("#apv_type").prop("required",true);
                jQuery("#apv_reason").prop("required",true);
                //jQuery("#display_on").prop("required",true);

                // show row
                jQuery("#exclude_for_ym_div").show();
                
            }            

         });

         //Update/add Job Price Variations
        jQuery("#add_job_variation_form").submit(function (e) {

            e.preventDefault();

            var make_ym = ( jQuery("#make_ym").prop("checked") == true )?1:0;
            var job_var_amount = jQuery("#job_variation_amount").val();
            var job_var_type = jQuery("#apv_type").val();
            var job_var_type_text = jQuery("#apv_type option:selected").text();
            var apv_reason = jQuery("#apv_reason").val();
            var apv_reason_text = jQuery("#apv_reason option:selected").text();
            var display_on = jQuery("#display_on").val();            

            if (parseInt(job_id) > 0) {                

                jQuery("#load-screen").show();
                jQuery.ajax({
                    type: "POST",
                    url: "/jobs/ajax_update_job_variation",
                    dataType: 'json',
                    data: {
                        job_id: job_id,
                        job_var_amount: job_var_amount,
                        job_var_type: job_var_type,
                        job_var_type_text: job_var_type_text,
                        job_var_reason: apv_reason,
                        job_var_reason_text: apv_reason_text,
                        make_ym: make_ym,
                        display_on: display_on
                    }
                }).done(function (ret) {
                    jQuery("#load-screen").hide();

                    if(ret.status){
                        $.fancybox.close();

                        swal({
                            title:"Success!",
                            text: "Price Variation Successfully Saved",
                            type: "success",
                            showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                            timer: <?php echo $this->config->item('timer') ?>
                        });

                        var full_url = window.location.href;
                        setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                            
                    }else{
                        if( ret.error!="" ){
                            swal('Error',ret.error,'error');
                        }else{
                            swal('Error','Internal error please contact admin','error');
                        }
                    }
                
                });

            }

            return false;

        });

        // delete job variation
        jQuery("#delete_job_price_variation").click(function () {

            var jv_id = jQuery("#jv_id").val();   
            var apv_type = $('#apv_type').val();         
            var job_variation_amount = $('#job_variation_amount').val();    
            var apv_reason_text = jQuery("#apv_reason option:selected").text(); 
            var display_on = $('#display_on').val();    
            var display_on_text = jQuery("#display_on option:selected").text(); 
                
            if( parseInt(jv_id) > 0 && parseInt(job_id) > 0) {

                swal({
                    title: "Warning!",
                    text: "Are you sure you want to delete this job variation?",
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
                                url: "/jobs/ajax_delete_job_variation",
                                dataType: 'json',
                                data: {
                                    job_id: job_id,
                                    jv_id: jv_id,
                                    apv_type: apv_type,
                                    job_variation_amount: job_variation_amount,
                                    apv_reason_text: apv_reason_text,
                                    display_on: display_on,
                                    display_on_text: display_on_text
                                }

                            }).done(function( retval ) {
                                $('#load-screen').hide(); //hide loader
                                if(retval.status){

                                    swal({
                                        title:"Success!",
                                        text: "Job Variation Successfully Deleted",
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

            }

        });

        // update job price
        jQuery(".btn_update_price").click(function () {

            var job_price = jQuery("#job_price").val();
            var price_reason = jQuery("#price_reason").val();
            var price_detail = jQuery("#price_detail").val();

            swal({
                title: "Warning!",
                text: "Update Price?",
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
                        url: "/jobs/ajax_update_job_price",
                        dataType: 'json',
                        data: {
                            job_id: job_id,
                            job_price: job_price,
                            price_reason: price_reason,
                            price_detail: price_detail
                        }

                    }).done(function( retval ) {
                        $('#load-screen').hide(); //hide loader
                        if(retval.status){

                            $.fancybox.close();

                            swal({
                                title:"Success!",
                                text: "Job Price Successfully Updated",
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

        });


        jQuery(".btn_update_all_price").click(function () {

            var job_price = jQuery("#job_price").val();
            var price_reason = jQuery("#price_reason").val();
            var price_detail = jQuery("#price_detail").val();
            var alarm_job_type_id = <?php echo $job_row['ajt_id'] ?>;
            //var property_id = <?php echo $job_row['prop_id'] ?>;
            //var orig_price = $("#orig_price").val();

            swal({
                title: "Warning!",
                text: "Update Job/Service Price?",
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
                        url: "/jobs/ajax_update_all_job_price",
                        dataType: 'json',
                        data: {
                            job_id: job_id,
                            job_price: job_price,
                            price_reason: price_reason,
                            price_detail: price_detail,
                            alarm_job_type_id: alarm_job_type_id,
                            property_id: property_id
                        }

                    }).done(function( retval ) {
                        $('#load-screen').hide(); //hide loader
                        if(retval.status){

                            $.fancybox.close();

                            swal({
                                title:"Success!",
                                text: "Job/Service Price Successfully Updated",
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

        });

    })

</script>