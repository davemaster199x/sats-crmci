<form method="POST" action="" id="edit-goal-form" class="text-center"> 
    <h3>Update Goals</h3>

	<table class="table table-hover main-table">
        <?php 
        $str_search = array('-','dha','Dha','Nsw','nsw','june');
        $str_replace = array(' ','DHA','DHA','NSW','NSW','June');
        foreach( $result->result_array() as $row ){
            $name = $row['name'];
            if($name == 'upgrade-booked'){
                $label = 'Upgrades (Booked)';
            } else if($name == 'upgrade-completed'){
                $label = 'Upgrades (Completed) '.date('F');
            } else if($name == 'upgrade-to-be-booked'){
                $label = 'Upgrades (To be booked)';
            }elseif( $name=='to-be-booked' ){
                $label = 'To Be Booked';
            }elseif( $name=='fix-or-replace' ){
                $label = 'Fix or Replace';
            }else{
                $label = $name;
            }

            $total_goal = $row['total_goal'];
            $label = str_replace($str_search, $str_replace, ucwords($label));
        ?>

            <tr class="parent_tr">
                <th><?php echo $label; ?></th>
                <td>
                    <input class="form-control goal_input" type="number" name="<?php echo $name; ?>" value="<?php echo $total_goal; ?>" autocomplete="off" required="">
                    <input type="hidden" class="orig_goal_input" value="<?php echo $total_goal ?>">
                </td>
                <td>
                    <!--<select name="staff_id" class="form-control staff_id" onchange="update_assign_goal(this.value)">-->
                    <select name="staff_id" class="form-control staff_id">
                        <option value="">--Select--</option>
                        <?php 
                        foreach( $active_user->result() as $row_user ){
                        ?>
                        <option <?php if($row['staff_id'] == $row_user->StaffID){ echo 'selected';} ?> value="<?=$row_user->StaffID?>"><?=$row_user->FirstName.' '.$row_user->LastName?></option>
                        <?php } ?>
                    </select>

                    <input type="hidden" class="orig_staff_id" value="<?php echo $row['staff_id'] ?>">
                    <input type="hidden" class="goal_id" value="<?php echo $row['id'] ?>">
                </td>
                
            </tr>

        <?php
        } ?>
	</table>

    <button id="btn_update_goals" type="submit" class="submitbtnImg btn">Update Goals</button>
  </form>



<script type="text/javascript">


jQuery(document).ready(function(){

   /* jQuery("#edit-goal-form").submit(function(e){
                e.preventDefault();
                $("#popup-box").show();
                jQuery("#load-screen").show();
                jQuery.ajax({
                    type: "POST",
                    processData: false,
                    contentType: false,
                    cache: false,
                    url: "/home/ajax_save_goal_count_data",
                    data: new FormData(this)
                }).done(function(ret){
                    
                    swal({
                        title: "Success!",
                        text: "Update Success",
                        type: "success",
                        confirmButtonClass: "btn-success",
                        showConfirmButton: <?php // echo $this->config->item('showConfirmButton') ?>,
                        timer: <?php // echo $this->config->item('timer') ?>
                    });
                    setTimeout(function(){ window.location='/home'; }, <?php // echo $this->config->item('timer') ?>);

                });
            });*/

            /*jQuery(".btn_update_goal").click(function(e){
                e.preventDefault();

                var node = $(this).parents('.parent_tr');
                var main_page_total_id = node.find('.main_page_total_id').val();
                var total_goal = node.find('.goal_input').val();
                var staff = node.find('.staff_id').val();
                
                jQuery("#load-screen").show();

                jQuery.ajax({
                    type: "POST",
                    dataType: 'json',
                    cache: false,
                    url: "/home/ajax_save_goal_count_data_v2",
                    data: { 
                        main_page_total_id: main_page_total_id,
                        total_goal: total_goal,
                        staff: staff
                    }
                }).done(function(ret){

                    jQuery("#load-screen").hide();

                    if( ret.status ){
                        swal({
                            title: "Success!",
                            text: "Update Success",
                            type: "success",
                            confirmButtonClass: "btn-success",
                            showConfirmButton: <?php // echo $this->config->item('showConfirmButton') ?>,
                            timer: <?php // echo $this->config->item('timer') ?>
                        });
                    }
                    
                });
            });*/

            jQuery("#btn_update_goals").click(function(e){
                e.preventDefault();

                var goal_id_each = $('.goal_id');

                var post = new Array();

                goal_id_each.each(function(){
                    
                    var node = $(this).parents('.parent_tr');

                    var goal_id = node.find('.goal_id').val();
                    var total_goal = node.find('.goal_input').val();
                    var staff = node.find('.staff_id').val();
                    
                    var jsondata = {
                        'goal_id': goal_id,
                        'total_goal': total_goal,
                        'staff': staff
                    }

                    var jsonstr = JSON.stringify(jsondata);

                    post.push(jsonstr);

                })

                jQuery("#load-screen").show();

                jQuery.ajax({
                    type: "POST",
                    dataType: 'json',
                    cache: false,
                    url: "/home/ajax_save_goal_count_data_v2",
                    data: { 
                        goal_arr: post
                    }
                }).done(function(ret){

                    jQuery("#load-screen").hide();

                    if( ret.status ){
                        swal({
                            title: "Success!",
                            text: "Update Success",
                            type: "success",
                            confirmButtonClass: "btn-success",
                            showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                            timer: <?php echo $this->config->item('timer') ?>
                        });
                    }
                    
                });
            });

    
});

   /* function update_assign_goal(value){
        array = value.split(',');
        goal_id = array[0];
        staff_id = array[1];

        jQuery("#load-screen").show();
        jQuery.ajax({
            type: "POST",
            url: "/home/update_main_page_total",
            data: {
                goal_id: goal_id,
                staff_id: staff_id
            }
        }).done(function( ) {
            jQuery("#load-screen").hide();
            swal({
                title: "Success!",
                text: "Update Success",
                type: "success",
                confirmButtonClass: "btn-success",
                showConfirmButton: <?php // echo $this->config->item('showConfirmButton') ?>,
                timer: <?php // echo $this->config->item('timer') ?>
            });
        });
    } */


</script>