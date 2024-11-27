<?php
  $export_links_params_arr = array(
    'agency_filter' => $this->input->get_post('agency_filter'),
    'date_from_filter' => $this->input->get_post('date_from_filter'),
    'date_to_filter' => $this->input->get_post('date_to_filter')
);
$export_link_params = '/property_me/export_agency_pme_connections/?status=completed&'.http_build_query($export_links_params_arr);
?>
<style>
    .col-mdd-3{
        max-width:15.5%;
    }
    .borderless td, .borderless th {
        border: none;
        margin-top: 8px;
    }
</style>

<div class="box-typical box-typical-padding">

    <?php 
// breadcrumbs template
$bc_items = array(
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
            <?php
            $form_attr = array(
                'id' => 'jform'
            );
            echo form_open($uri,$form_attr);
            ?>
                <div class="for-groupss row">
                    <div class="col-lg-6 col-md-6 columns">
                        <div class="row">

                            <div class="col-md-3">
                                <label for="a">Agency</label>
                                <select id="agency_filter" name="agency_filter" class="form-control field_g2">
                                    <option value="">ALL</option>
                                    <?php                                    
                                    foreach($distinct_agency_sql->result() as $agency_row){                                            
                                    ?>
                                        <option <?php echo $sel ?> value="<?php echo $agency_row->agency_id ?>" <?php echo ( $agency_row->agency_id == $this->input->get_post('agency_filter') )?'selected':null; ?>><?php echo $agency_row->agency_name; ?></option>
                                    <?php
                                    }                                    
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-3">
                                    <label for="date_select">From:</label>
                                    <input name="date_from_filter" placeholder="ALL" class="flatpickr form-control flatpickr-input" data-allow-input="true" id="flatpickr" type="text"  value="<?php echo ( $this->input->get_post('date_from_filter')!= '' )?$this->input->get_post('date_from_filter'):null; ?>">
                            </div>

                            <div class="col-md-3">
                                <label for="date_select">To:</label>
                                <input name="date_to_filter" placeholder="ALL" class="flatpickr form-control flatpickr-input" data-allow-input="true" id="flatpickr" type="text"  value="<?php echo ( $this->input->get_post('date_to_filter')!= '' )?$this->input->get_post('date_to_filter'):null; ?>">
                            </div>
                        
                            <div class="col-md-1 columns">
                                <label class="col-sm-12 form-control-label">&nbsp;</label>
                                <input class="btn" type="submit" name="btn_search" value="Search">
                            </div>
                            
                        </div>
                    </div> 
                    <div class="col-lg-4 col-md-4 columns">
                        <div class="row">
                            <table class="table borderless">
                                <tr>
                                    <td>Able to connect: <?=$ableToCon?></span></td>
                                    <td>Needs to reconnect: <?=$fullCon?></span></td>
                                    <td>Fully connected: <?=$needToCon?></span></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 columns">
                        <section class="proj-page-section">
                            <div class="proj-page-attach" style="margin-top: 0px;">
                                <i class="fa fa-file-excel-o"></i>
                                <p class="name"><?php echo $title; ?></p>
                                <p>
                                    <a href="<?php echo $export_link_params ?>" target="blank">
                                        Export
                                    </a>
                                </p>
                            </div>
                        </section>
                    </div>                                                                   
                </div>
            </form>
        </div>
    </header>
    

    <section>
        <div class="body-typical-body">
            <div class="table-responsive">
                <table class="table table-hover main-table">
                    <thead>
                        <tr>    
                            <th>Agency</th>          
                            <th class="text-center">Connection Date</th>
                            <th class="text-center">Deliver Invoice via API</th>  
                            <th class="text-center">Settings</th>              
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        
                        if($agency_sql->num_rows() > 0){
                            foreach($agency_sql->result() as $agency_row){
                        ?>
                            <tr>     
                                <td>
                                    <a href="<?php echo "/agency/view_agency_details/{$agency_row->agency_id}"; ?>">
                                        <?php echo $agency_row->agency_name; ?>
                                    </a>                    
                                </td>                      
                                <td class="text-center">
                                    <?php 
                                    if ( $agency_row->access_token != '' ) {                                        
                                        echo ($this->system_model->isDateNotEmpty($agency_row->connection_date) == true) ? $this->system_model->formatDate($agency_row->connection_date, 'd/m/Y H:i') : '<span class="text-green fa fa-check"></span>'; 
                                    }else {
                                        echo '<span class="text-red fa fa-times"></span>';
                                    }
                                    ?>

                                </td>                                
                                <td class="text-center">
                                    <?php
                                    // check for property preference
                                    $pt_pref_sql = $this->db->query("
                                    SELECT COUNT(pt_ap_id) AS pt_ap_count
                                    FROM `propertytree_agency_preference`
                                    WHERE `agency_id` = {$agency_row->agency_id}                                    
                                    AND ( `creditor` IS NOT NULL AND `creditor` != '' )
                                    AND ( `account` IS NOT NULL AND `account` != '' )
                                    AND ( `prop_comp_cat` IS NOT NULL AND `prop_comp_cat` != '' )
                                    AND `active` = 1
                                    ");

                                    echo ( $pt_pref_sql->row()->pt_ap_count > 0 )?'<span class="text-green fa fa-check"></span>':'<span class="text-red fa fa-times"></span>';
                                    ?>                                    
                                </td>
                                <td class="text-center">
                                    <input type="hidden" class="agency" value="<?php echo $agency_row->agency_id; ?>" />
                                    <?php
                                    if( $agency_row->access_token != '' ){ ?>
                                        <button type="button" class="btn set_agency_preference_btn">Settings</button>
                                    <?php
                                    }
                                    ?>                                    
                                </td>
                            </tr>
                        <?php   
                            }
                        }else{ ?>
                            <tr><td colspan='2'>No Data</td></tr>
                        <?php    
                        }     
                                         
                        ?>                 
                    </tbody>

                </table>
            </div>

         <nav id="pagi_links" aria-label="Page navigation example" style="text-align:center"><?php echo $pagination; ?></nav>
        <div id="pagi_count" class="pagi_count text-center"><?php echo $pagi_count; ?></div>

        </div>
    </section>

</div>

<!-- Fancybox Start -->
<a href="javascript:;" id="about_page_fb_link" class="fb_trigger" data-fancybox data-src="#about_page_fb">Trigger the fancybox</a>                          
<div id="about_page_fb" class="fancybox" style="display:none;" >

    <h4><?php echo $title; ?></h4>
    <p>
    25/03/2020 14:26 - New permission property:write added
    </p>
<pre><code><?php echo $last_query; ?></code></pre>

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
                error += 'Account is Required\n';
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
                        text: "Agency Connected Successfully!",
                        type: "success",
                        confirmButtonClass: "btn-success",
                        showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                        timer: <?php echo $this->config->item('timer') ?>
                    });
                    setTimeout(function(){ location.reload(); }, <?php echo $this->config->item('timer') ?>);                                       

                });
                
            }                    

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
                error += 'Account is Required\n';
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
                        text: "Agency Connected Successfully!",
                        type: "success",
                        confirmButtonClass: "btn-success",
                        showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                        timer: <?php echo $this->config->item('timer') ?>
                    });
                    setTimeout(function(){ location.reload(); }, <?php echo $this->config->item('timer') ?>);                                       

                });
                
            }                    

        }        

    });
    
});
</script>