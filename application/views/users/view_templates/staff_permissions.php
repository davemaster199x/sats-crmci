<div role="tabpanel" class="tab-pane fade active show" id="licencing-tab">
    <div class="row">
        <div class="col-sm-12">
            <h3 class="h3">Permissions</h3>
            <table class="table">
                <?php
                foreach( $perm_list_sql->result() as $perm_list_row ){ ?>
                    <tr>
                        <th><?php echo $perm_list_row->description; ?></th>
                        <td>
                            <div class="checkbox">
                                <input 
                                    type="checkbox" 
                                    name="permission_chk" 
                                    id="permission_chk<?php echo $perm_list_row->id; ?>" 
                                    class="permission_chk"
                                    value="<?php echo $perm_list_row->id; ?>" 
                                    <?php echo ( in_array($perm_list_row->id, $staff_perm_arr) )?'checked':null; ?>
                                />
                                <label for="permission_chk<?php echo $perm_list_row->id; ?>"></label>
                            </div>
                        </td>
                    </tr>
                <?php
                }
                ?>					
            </table>
        </div>
    </div>
</div>
<script>
jQuery(document).ready(function(){

    // update staff permissions
	jQuery(".permission_chk").change(function(){

        var permission_dom = jQuery(this); 
        var permission = permission_dom.val();
        var is_ticked = ( permission_dom.prop("checked") == true )?1:0;
        var staff_id = <?php echo $user["StaffID"] ?>;

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