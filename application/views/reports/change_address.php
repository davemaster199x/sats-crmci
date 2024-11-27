<style>
    .col-mdd-3{
        max-width:20%;
    }
    #leave_form{
        margin-top:50px;
    }
    .flatpickr{width:100%!important}
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
            'link' => "/users/change_address"
        )
    );
    $bc_data['bc_items'] = $bc_items;
    $this->load->view('templates/breadcrumbs', $bc_data);
    ?>

	<section>
		<div class="body-typical-body">
            <?php echo form_open('/users/change_address','id=change_address'); ?>

                <div class="form-group row">
                    <label class="col-sm-2 form-control-label">Employee Name<span class="text-red">*</span></label>
                    <div class="col-sm-3">
                        <select  class="form-control" name="employee" id="employee">
                            <option value="">Please select</option>
                            <?php
                            foreach($staff->result_array() as $row){
                            ?>
                            <option <?php echo ($row['StaffID'] == $this->session->staff_id)? "selected" : NULL ?> value="<?php echo $row['StaffID'] ?>"><?php echo "{$row['FirstName']} {$row['LastName']}" ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                
                <br />
                <div class="form-group row">
                    <label class="col-sm-2 form-control-label" style="font-weight: bold">New Address<span class="text-red">*</span></label>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 form-control-label">Street / Unit Number <span class="text-red">*</span></label>
                    <div class="col-sm-3">
                    <input  type="text" name="street_number" id="street_number" class="form-control"/>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 form-control-label">Street Name <span class="text-red">*</span></label>
                    <div class="col-sm-3">
                    <input  type="text" name="street_name" id="street_name" class="form-control"/>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 form-control-label">Suburb<span class="text-red">*</span></label>
                    <div class="col-sm-3">
                        <input  type="text" name="suburb" id="suburb" class="form-control"/>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 form-control-label">State <span class="text-red">*</span></label>
                    <div class="col-sm-3">
                        <input  type="text" name="state" id="state" class="form-control"/>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 form-control-label">Postcode <span class="text-red">*</span></label>
                    <div class="col-sm-3">
                     <input  type="text" name="postcode" id="postcode" class="form-control"/>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 form-control-label">Move In Date <span class="text-red">*</span></label>
                    <div class="col-sm-3">
                    <input name="move_date" class="flatpickr form-control flatpickr-input" data-allow-input="true" id="move_date" type="text" value="">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 form-control-label">&nbsp;</label>
                    <div class="col-sm-3 text-right">
                    <input type="submit" class="btn" id="btn_change_address" name="btn_change_address" value="Submit">
                    </div>
                </div>
            
            </form>
		</div>
	</section>

</div>

<!-- Fancybox Start -->
<a href="javascript:;" id="about_page_fb_link" class="fb_trigger" data-fancybox data-src="#about_page_fb">Trigger the fancybox</a>							
<div id="about_page_fb" class="fancybox" style="display:none;" >

	<h4><?php echo $title; ?></h4>
	<p>
    Lorem ipsum...
	</p>

</div>
<!-- Fancybox END -->

<script type="text/javascript">


    jQuery(document).ready(function(){

        //success/error message sweel alert pop  start
        <?php if( $this->session->flashdata('status') &&  $this->session->flashdata('status') == 'success' ){?>
            swal({
                title: "Success!",
                text: "<?php echo $this->session->flashdata('success_msg') ?>",
                type: "success",
                confirmButtonClass: "btn-success"
            });
        <?php }else if(  $this->session->flashdata('status') &&  $this->session->flashdata('status') == 'error'  ){ ?>
            swal({
                title: "Error!",
                text: "<?php echo $this->session->flashdata('error_msg') ?>",
                type: "error",
                confirmButtonClass: "btn-danger"
            });
        <?php } ?>
        //success/error message sweel alert pop  end

        $('#change_address').submit(function(){

            var error = "";
            var submitcount = 0;
            
            // Leave Request Form
            var employee = jQuery("#employee").val();
            var street_number = jQuery("#street_number").val();
            var street_name = jQuery("#street_name").val();
            var suburb = jQuery("#suburb").val();
            var state = jQuery("#state").val();
            var postcode = jQuery("#postcode").val();
            var move_date = jQuery("#move_date").val();
            

            if( employee == "" ){
                error += "Name is required\n";
            }
            if( street_number == "" ){
                error += "Street Number is required\n";
            }
            if( street_name == "" ){
                error += "Street Name is required\n";
            }
            if( suburb == "" ){
                error += "Suburb is required\n";
            }
            if( state == "" ){
                error += "State is required\n";
            }
            if( postcode == "" ){
                error += "Postcode is required\n";
            }
            if( move_date == "" ){
                error += "Move Date is required\n";
            }

            if(error!=""){
                swal('',error,'error');
                return false;
            }

            if(submitcount==0){
                submitcount++;
                $(this).submit();
                return false;
            }else{
                swal('','Submission in progress','error');
            }

        })

    });

   


</script>