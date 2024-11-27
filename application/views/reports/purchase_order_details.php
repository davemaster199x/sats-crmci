<style>
    .col-mdd-3{
        max-width:20%;
    }
    .fadeOutText{
        opacity:0.5;
    }
    .gio_aa_table tr td input{
        margin-right:10px;
        margin-bottom:10px;
    }
    .gio_aa_table tr td input.read-only{
        background:#eee;
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
        'link' => "/reports/purchase_order_details/{$this->uri->segment(3)}"
    )
);
$bc_data['bc_items'] = $bc_items;
$this->load->view('templates/breadcrumbs', $bc_data);
?>



	<section>
		<div class="body-typical-body">
			<div class="table-responsiveaaa" style="padding-top:25px;">
            <?php
                $form_attr = array(
                    'id' => 'jform'
                );
                echo form_open('/reports/purchase_order_details_update',$form_attr);
            ?>

                 <div class="row">
                    <div class="col-md-12 col-lg-5 columns">


                        <div class="form-group row">
                            <label class="col-md-4 form-control-label">Purchase Order No.</label>
                            <div class="col-md-8">
                                <p class="form-control-static"><input readonly="readonly" type="text" class="form-control" id="purchase_order_num" name="purchase_order_num" value="<?php echo $po['purchase_order_num'] ?>" ></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-4 form-control-label">Date</label>
                            <div class="col-md-8">
                                <p class="form-control-static"> <input name="date" class="flatpickr form-control flatpickr-input" data-allow-input="true" id="flatpickr" type="text" value="<?php echo $this->system_model->formatDate($po['po_date'],'d/m/Y') ?>" ></p>
                            </div>
                        </div>

                         <div class="form-group row">
                            <label class="col-md-4 form-control-label">Supplier Name</label>
                            <div class="col-md-8">
                                <p class="form-control-static"> 
                                    <select name="supplier" id="supplier" class="form-control">
                                        <option value="">Please Select</option>
                                            <?php 
                                                $upp_params = array(
                                                    'sel_query' => "company_name,suppliers_id",
                                                    'sort_list' => array(
                                                        array(
                                                            'order_by' => '`company_name`',
                                                            'sort' => 'ASC'
                                                        )
                                                    )
                                                );
                                                $supp_query = $this->supplier_model->getSupplier($upp_params);
                                                foreach($supp_query->result_array() as $row){
                                                    $opt_sel = ($row['suppliers_id'] == $po['suppliers_id'])?'selected="selected"':NULL;
                                            ?>
                                                    <option <?php echo $opt_sel; ?> value="<?php echo $row['suppliers_id'] ?>"><?php echo $row['company_name'] ?></option>
                                            <?php
                                                }
                                            ?>
                                        
                                    </select>
                                    <input type="hidden" name="supplier_name" id="supplier_name" value="<?php echo $po['company_name']; ?>" />
                                </p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-4 form-control-label">Supplier Address</label>
                            <div class="col-md-8">
                                <p class="form-control-static"><input readonly="readonly" type="text" class="form-control" id="supplier_address" name="supplier_address" value="<?php echo $po['address'] ?>" ></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-4 form-control-label">Supplier Email</label>
                            <div class="col-md-8">
                                <p class="form-control-static"><input readonly="readonly" type="text" class="form-control" id="supplier_email" name="supplier_email" value="<?php echo $po['email'] ?>" ></p>
                            </div>
                        </div>

                         <div class="handyman_div" <?php echo ( $po['suppliers_id'] == $this->purchase_model->getDynamicHandyManID() )?'style="display:block;"':'style="display:none;"'; ?>>
                            <div class="row form-group">
                                <label class="col-md-4 form-control-label">Agency</label>

                                <div class="col-md-8">
                                    <p class="form-control-static"> 
                                        <select class="form-control" name="agency" id="agency" style="width:400px;">
                                            <option value="">----</option>	
                                            <?php
                                            $a_params = array(
                                                'sel_query' => "agency_id,agency_name",
                                                'a_status' => 'active',
                                                'country_id' => $this->config->item('country'),
                                                'sort_list' => array(
                                                    array(
                                                        'order_by' => '`agency_name`',
                                                        'sort' => 'ASC'
                                                    )
                                                )
                                            );
                                            $agen_sql = $this->agency_model->get_agency($a_params);
                                            foreach( $agen_sql->result_array() as $agen) { ?>
                                                <option value="<?php echo $agen['agency_id']; ?>" <?php echo ($agen['agency_id']==$po['agency_id'])?'selected="selected"':''; ?>><?php echo $agen['agency_name']; ?></option>
                                            <?php	
                                            }
                                            ?>
                                    </select>	
                                    </p>
                                </div>
                            </div>
                            
                            <div class="row form-group">
                                <label class="col-md-4 form-control-label">Invoice Total</label>

                                    <p class="form-control-static">
                                    <div class="col-md-8 input-group">
                                        <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                        <input type="text" class="form-control" name="invoice_total" id="invoice_total"  value="<?php echo $po['invoice_total']; ?>" /> 
                                    </div>
                                    </p>

                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-4 form-control-label"><?php echo ($po['suppliers_id']==$this->purchase_model->getDynamicHandyManID())?'Problem':'Item'; ?></label>
                            <div class="col-md-8">

                                    <div id="purchase_order_item_ajax_box">
                                   
                                    </div>

                                <p class="form-control-static"><textarea class="form-control" name="item_note"><?php echo $po['item_note'] ?></textarea></p>
                            </div>
                        </div>
                        
                        <div id="Deliver_to_div" <?php echo ( $po['suppliers_id']==$this->purchase_model->getDynamicHandyManID() )?'style="display:none;"':''; ?>>
                            <div class="form-group row">
                                <label class="col-md-4 form-control-label">Deliver to</label>
                                <div class="col-md-8">
                                    <p class="form-control-static"> 
                                        <select name="deliver_to" id="deliver_to" class="form-control">
                                            <option value="">Please Select</option>
                                            <?php
                                            foreach($staff_list->result_array() as $staff_row){
                                                $staff_sel = ($staff_row['StaffID']==$po['deliver_to'])?'selected="selected"':NULL;
                                            ?>
                                                <option <?php echo $staff_sel; ?> value="<?php echo $staff_row['StaffID'] ?>" ><?php echo $this->system_model->formatStaffName($staff_row['FirstName'], $staff_row['LastName']) ?></option>
                                            <?php
                                            }
                                            ?>

                                        </select>
                                        <input type="hidden" name="deliver_to_name" id="deliver_to_name" value="<?php echo $this->system_model->formatStaffName($po['FirstName'], $po['LastName']) ?>" />
                                    </p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 form-control-label">Delivery Address</label>
                                <div class="col-md-8">
                                    <p class="form-control-static"><input readonly="true" type="text" class="form-control" id="delivery_address" name="delivery_address"value="<?php echo $po['delivery_address'] ?>" ></p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 form-control-label">Receiver Email</label>
                                <div class="col-md-8">
                                    <p class="form-control-static"><input readonly="true" type="text" class="form-control" id="reciever_email" name="reciever_email" value="<?php echo $po['delivery_email'] ?>" ></p>
                                </div>
                            </div>

                              <div class="form-group row">
                                <label class="col-md-4 form-control-label">Sales Agreement number</label>
                                <div class="col-md-8">
                                    <p class="form-control-static"><input readonly="true" type="text" class="form-control" id="sales_agreement_number" name="sales_agreement_number" value="<?php echo $po['sales_agreement_number'] ?>" ></p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 form-control-label">Comments</label>
                                <div class="col-md-8">
                                    <p class="form-control-static"><textarea class="form-control" name="comments"><?php echo $po['comments'] ?></textarea></p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 form-control-label">Ordered By</label>
                                <div class="col-md-8">
                                    <p class="form-control-static"> 
                                        <select name="ordered_by" id="ordered_by" class="form-control">
                                            <option value="">Please Select</option>
                                            <?php
                                            foreach($staff_list->result_array() as $staff_row){
                                                $staff_sel = ($staff_row['StaffID']==$po['ordered_by'])?'selected="selected"':NULL;
                                            ?>
                                                <option <?php echo $staff_sel; ?> value="<?php echo $staff_row['StaffID'] ?>" ><?php echo $this->system_model->formatStaffName($staff_row['FirstName'], $staff_row['LastName']) ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>

                                        <input type="hidden" name="ordered_by_name" id="ordered_by_name" value="<?php echo $po['order_by_fname']; ?>" />
						                <input type="hidden" name="ordered_by_full_name" id="ordered_by_full_name" value="<?php echo $po['order_by_fname']." ".$po['order_by_lname'] ?>" />
                                    </p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 form-control-label">Ordered by Email</label>
                                <div class="col-md-8">
                                <p class="form-control-static"><input readonly="true" type="text" class="form-control" id="order_by_email" name="order_by_email" value="<?php echo $po['order_by_email'] ?>" ></p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-4 form-control-label">&nbsp;</label>
                            <div class="col-md-8">
                           
                                <div class="checkbox" style="margin-bottom:1rem;">
                                    <input type="checkbox" id="check-2" name="email_purchase_order" value="1">
                                    <label for="check-2">Email Purchase Order</label>
                                </div>
                            
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-md-4 form-control-label">&nbsp;</label>
                            <div class="col-md-8">
                                <p class="form-control-static">
                                <input type="hidden" name="purchase_order_id" value="<?php echo $purchase_id; ?>">
                                <input type="submit" name="btn_update_po" id="btn_update_po" class="btn" value="Update"></p>
                            </div>
                        </div>
                        

                    </div>
                </div>

                


            </form>

			</div>
		</div>
	</section>

</div>

<!-- Fancybox Start -->
<a href="javascript:;" id="about_page_fb_link" class="fb_trigger" data-fancybox data-src="#about_page_fb">Trigger the fancybox</a>							
<div id="about_page_fb" class="fancybox" style="display:none;" >

	<h4><?php echo $title; ?></h4>
	<p>
    Details of the purchase order being viewed
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
                confirmButtonClass: "btn-success",
				showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                timer: <?php echo $this->config->item('timer') ?>
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




        //load purchase order item on doc load
        load_purchase_order_item_table_list_by_puchase_id();
            
        //load releavant supplier datas on dropdown changed
        $("#supplier").change(function(){
		
            var supplier = jQuery(this).val();

            load_purchase_order_item_table_list_by_supplier_id(supplier);

            ajax_get_supplier_info(supplier);
                        
                
        });


        // ajax deliver to
        jQuery("#deliver_to").change(function(){
            
            var staff_id = jQuery(this).val();
                        
            jQuery("#load-screen").show();
            jQuery.ajax({
                type: "POST",
                url: "/reports/ajax_get_staff_accounts",
                data: { 
                    staff_id: staff_id
                },
                dataType: "json"
            }).done(function( ret ) {		
                
                jQuery("#delivery_address").val(ret.address);
                jQuery("#reciever_email").val(ret.email);
                jQuery("#deliver_to_name").val(ret.fullname);
                jQuery("#load-screen").hide();
                
            });
                
        });


        //ajax order by
        jQuery("#ordered_by").change(function(){
		
            var staff_id = jQuery(this).val();
                        
            jQuery("#load-screen").show();
            jQuery.ajax({
                type: "POST",
                url: "/reports/ajax_get_staff_accounts",
                data: { 
                    staff_id: staff_id
                },
                dataType: "json"
            }).done(function( ret ) {		
                
                jQuery("#order_by_email").val(ret.email);
                jQuery("#ordered_by_name").val(ret.fullname);
                jQuery("#ordered_by_full_name").val(ret.fullname2);
                jQuery("#load-screen").hide();
                
            });
                
        });

        
    });



    function ajax_get_supplier_info(supp_id){

        jQuery.ajax({
            type: "POST",
            url: '/reports/ajax_get_supplier_details',
            dataType: 'json',
            data: { 
                supplier_id: supp_id
            }
        }).done(function( ret ){	
                $('#supplier_address').val(ret.query.address);
                $('#supplier_email').val(ret.query.email);
                jQuery("#supplier_name").val(ret.query.company_name);
                jQuery("#sales_agreement_number").val(ret.query.sales_agreement_number);
        });	
        
    }

    function load_purchase_order_item_table_list_by_puchase_id(){
        // show loader on load 
        jQuery("#load-screen").show();
        $('#purchase_order_item_ajax_box').html("");
        $('#purchase_order_item_ajax_box').load('/reports/ajax_purchase_order_item_list',{purchase_id:<?php echo $purchase_id ?>}, function(response, status, xhr){
            jQuery("#load-screen").hide();
        });
    }

    function load_purchase_order_item_table_list_by_supplier_id(supp_id){
        // show loader on load 
        jQuery("#load-screen").show();
        $('#purchase_order_item_ajax_box').html("");
        $('#purchase_order_item_ajax_box').load('/reports/ajax_purchase_order_item_list',{supplier_id:supp_id}, function(response, status, xhr){
            jQuery("#load-screen").hide();
        });
    }



</script>