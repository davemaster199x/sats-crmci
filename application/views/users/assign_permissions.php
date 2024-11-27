<style>
#assign_permission_fb,
#remove_permission_fb, 
#action_div{
    display: none;
}
.checkbox{
    margin-bottom: unset !important;
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


<div class="body-typical-body">
    <div class="table-responsive">
        <table class="table mb-3">
            <thead>
                <tr>
                    <th>Staff</th>
                    <?php
                    foreach( $perm_list_sql_res as $perm_list_row ){ ?>
                        <th><?php echo $perm_list_row->description; ?></th>
                    <?php
                    }
                    ?>	
                    <th class="text-center">Action</th>
                </tr>
            </thead>

            <tbody>
                <?php                                              
                if( $staff_account_sql->num_rows() > 0 ){

                    foreach( $staff_account_sql->result() as $staff_account_row ){
                    ?>
                        <tr>    
                            <td><?php echo "{$staff_account_row->FirstName} {$staff_account_row->LastName}" ?></td>  
                            <?php
                            foreach( $perm_list_sql_res as $perm_list_row ){ 
                                
                                // staff permission
                                $staff_perm_sql = $this->db->query("
                                SELECT `id` AS count
                                FROM `staff_permissions`
                                WHERE `staff_id` = {$staff_account_row->StaffID}
                                AND `has_permission_on` = {$perm_list_row->id}
                                ");
                                $has_permission = ( $staff_perm_sql->row()->count > 0 )?true:false;
                                ?>
                                <td class="text-center">
                                    <div class="checkbox">
                                        <input 
                                            type="checkbox" 
                                            id="permission_chk<?php echo $staff_account_row->StaffID; ?>_<?php echo $perm_list_row->id; ?>" 
                                            class="permission_chk"
                                            value="<?php echo $perm_list_row->id; ?>" 
                                            <?php echo ( $has_permission  == true )?'checked':null; ?>
                                        />
                                        <label for="permission_chk<?php echo $staff_account_row->StaffID; ?>_<?php echo $perm_list_row->id; ?>"></label>
                                    </div>
                                </td>
                            <?php
                            }
                            ?>	 
                            <td class="text-center">
                                <div class="checkbox">
                                    <input 
                                        type="checkbox" 
                                        id="staff_chk<?php echo $staff_account_row->StaffID; ?>" 
                                        class="staff_chk"
                                        value="<?php echo $staff_account_row->StaffID; ?>" 
                                    />
                                    <label for="staff_chk<?php echo $staff_account_row->StaffID; ?>"></label>
                                </div>
                            </td>                                                 
                        </tr>
                    <?php
                    }

                }else{
                    echo "<tr><td colspan='100%'>No Data</td></tr>";
                }                                               
                ?>
            </tbody>

        </table>

        <div id="action_div">
            <button type="button" class="btn btn-danger float-left" id="remove_permission_btn">Remove Permissions</button>  
            <button type="button" class="btn btn-success float-right" id="add_permission_btn">Add Permissions</button>  
        </div>
            
                    
    </div>


</div>
		

<!-- Fancybox Start -->
<a href="javascript:;" id="about_page_fb_link" class="fb_trigger" data-fancybox data-src="#about_page_fb">Trigger the fancybox</a>							
<div id="about_page_fb" class="fancybox" style="display:none;" >
<h4><?php echo $title; ?></h4>
<p>Assign multiple or individual user permissions</p>
</div>

<!-- add permissions -->
<div class="fancybox" id="assign_permission_fb">
    <h5>Add Permissions</h5>
    <table class="table mb-2">
        <thead>
            <tr>
                <th>Permission</th>
                <th>
                    <div class="checkbox">
                        <input 
                            type="checkbox" 
                            id="permission_chk_all_fb" 
                        />
                        <label for="permission_chk_all_fb"></label>
                    </div>
                </td>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach( $perm_list_sql_res as $perm_list_row ){ ?>
            <tr>
                <th><?php echo $perm_list_row->description; ?></th>
                <td>
                    <div class="checkbox">
                        <input 
                            type="checkbox" 
                            id="permission_chk_fb<?php echo $perm_list_row->id; ?>" 
                            class="permission_chk_fb"
                            value="<?php echo $perm_list_row->id; ?>" 
                        />
                        <label for="permission_chk_fb<?php echo $perm_list_row->id; ?>"></label>
                    </div>
                </td>
            </tr>            
        <?php
        }
        ?>
        </tbody>
    </table>
    <button type="button" class="btn btn-success float-right" id="save_multiple_permission_btn">Add</button>
</div>

<!-- remove permissions -->
<div class="fancybox" id="remove_permission_fb">
    <h5>Remove Permissions</h5>
    <table class="table mb-2">
            <thead>
                <tr>
                    <th>Permission</th>
                    <th>
                        <div class="checkbox">
                            <input 
                                type="checkbox" 
                                id="remove_permission_chk_all_fb" 
                            />
                            <label for="remove_permission_chk_all_fb"></label>
                        </div>
                    </td>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach( $perm_list_sql_res as $perm_list_row ){ ?>
                <tr>
                    <th><?php echo $perm_list_row->description; ?></th>
                    <td>
                        <div class="checkbox">
                            <input 
                                type="checkbox" 
                                id="remove_permission_chk_fb<?php echo $perm_list_row->id; ?>" 
                                class="remove_permission_chk_fb"
                                value="<?php echo $perm_list_row->id; ?>" 
                            />
                            <label for="remove_permission_chk_fb<?php echo $perm_list_row->id; ?>"></label>
                        </div>
                    </td>
                </tr>            
            <?php
            }
            ?>
            </tbody>
        </table>
        <button type="button" class="btn btn-danger float-right" id="remove_multiple_permission_btn">Remove</button>
</div>
<!-- Fancybox END -->
<script>
jQuery(document).ready(function(){

    // add permissions
    jQuery("#add_permission_btn").click(function(){

        jQuery.fancybox.open({
            src  : '#assign_permission_fb'
        });

    });

    // remove permissions
    jQuery("#remove_permission_btn").click(function(){

        jQuery.fancybox.open({
            src  : '#remove_permission_fb'
        });

    });

    // show/hide add/remove permission button
    jQuery(".staff_chk").change(function(){
        
        var num_checked = jQuery(".staff_chk:checked").length;
        var staff_chk_dom = jQuery(this);
        var parent_tr = staff_chk_dom.parents("tr:first");

        // checkbox ticked/unticked highlight
        if( staff_chk_dom.prop("checked") == true ){
            parent_tr.addClass('bg-info');
        }else{
            parent_tr.removeClass('bg-info');
        }

        // show/hide action buttons
        if( num_checked > 0 ){
            jQuery("#action_div").show();
        }else{
            jQuery("#action_div").hide();
        }

    });

    // add permission save
    jQuery("#save_multiple_permission_btn").click(function(){

        var staff_dom = jQuery(".staff_chk:checked");
        var permission_dom = jQuery(".permission_chk_fb:checked");
        var num_perm_sel = permission_dom.length;

        if( num_perm_sel == 0 ){
            swal('','Please select permission','error');
        }else{

            var staff_arr = [];
            staff_dom.each(function(){
                
                if( jQuery(this).val() > 0 ){
                    staff_arr.push(jQuery(this).val());
                }

            });

            var permission_arr = [];
            permission_dom.each(function(){
                
                if( jQuery(this).val() > 0 ){
                    permission_arr.push(jQuery(this).val());
                }

            });

            if( staff_arr.length > 0 && permission_arr.length > 0 ){

                jQuery('#load-screen').show();
                jQuery.ajax({
                    type: "POST",
                    url: "/users/save_multiple_user_permissions",
                    data: { 	
                        'staff_arr': staff_arr,				
                        'permission_arr': permission_arr
                    }
                }).done(function( ret ){
                    
                    jQuery('#load-screen').hide();
                    swal({
                        title: "Success!",
                        text: "Multiple Permission Added",
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

    // remove permission save
    jQuery("#remove_multiple_permission_btn").click(function(){

        var staff_dom = jQuery(".staff_chk:checked");
        var permission_dom = jQuery(".remove_permission_chk_fb:checked");
        var num_perm_sel = permission_dom.length;

        if( num_perm_sel == 0 ){
            swal('','Please select permission','error');
        }else{

            var staff_arr = [];
            staff_dom.each(function(){
                
                if( jQuery(this).val() > 0 ){
                    staff_arr.push(jQuery(this).val());
                }

            });

            var permission_arr = [];
            permission_dom.each(function(){
                
                if( jQuery(this).val() > 0 ){
                    permission_arr.push(jQuery(this).val());
                }

            });

            if( staff_arr.length > 0 && permission_arr.length > 0 ){

                jQuery('#load-screen').show();
                jQuery.ajax({
                    type: "POST",
                    url: "/users/remove_multiple_user_permissions",
                    data: { 	
                        'staff_arr': staff_arr,				
                        'permission_arr': permission_arr
                    }
                }).done(function( ret ){
                    
                    jQuery('#load-screen').hide();
                    swal({
                        title: "Success!",
                        text: "Multiple Permission Removed",
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

    // select ALL   
    // add permission
    jQuery("#permission_chk_all_fb").change(function(){

        var check_all_dom = jQuery(this);

        if( check_all_dom.prop("checked") == true ){
            jQuery(".permission_chk_fb").prop("checked",true);
        }else{
            jQuery(".permission_chk_fb").prop("checked",false);
        }        

    });
    
    // remove permission
    jQuery("#remove_permission_chk_all_fb").change(function(){

        var check_all_dom = jQuery(this);

        if( check_all_dom.prop("checked") == true ){
            jQuery(".remove_permission_chk_fb").prop("checked",true);
        }else{
            jQuery(".remove_permission_chk_fb").prop("checked",false);
        }        

    });

    // update individual staff permissions
	jQuery(".permission_chk").change(function(){

        var permission_dom = jQuery(this); 
        var permission = permission_dom.val();
        var is_ticked = ( permission_dom.prop("checked") == true )?1:0;
        var parent_tr = permission_dom.parents("tr:first");
        var staff_id = parent_tr.find('.staff_chk').val();

        if( staff_id > 0 ){

            jQuery('#load-screen').show();
            jQuery.ajax({
                type: "POST",
                url: "/users/update_staff_permissions",
                data: { 	
                    'permission': permission,				
                    'is_ticked': is_ticked,
                    'staff_id': staff_id
                }
            }).done(function( ret ){
                
                jQuery('#load-screen').hide();

            });		

        }		

    });


});
</script>