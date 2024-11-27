<div class="box-typical box-typical-padding">

<?php
    /**
     * Breadcrumbs template
     */
    $this->load->view('templates/breadcrumbs', $bc_items);
?>

	<header class="box-typical-header">

		<div class="box-typical box-typical-padding">
			<div class="for-groupss row">
				<div class="col-lg-10 col-md-8 columns">
					<div class="row">

						<div class='fl-left' style="float:left; padding-top: 28px; padding-left: 30px;">
							Login: <a style="margin:0!important;" target="__blank" href="https://my.mmgr.com.au/index.php/site/login">Maintenance Manager</a>
						</div>

						<div class="col-mdd-3">
							<label>&nbsp;</label>
							<button type="button" class="btn btn-inline" id="refresh_btn">
								<img class="inner_icon" src="../images/rebook.png">
								Refresh
							</button>
						</div>

					</div>
				</div>

			</div>
		</div>
	</header>

	<section>
		<div class="body-typical-body">
			<div class="table-responsive">
				<table class="table table-hover main-table" id="datatable">
					<thead>
						<tr>
							<th>Date</th>
							<th>Software</th>
							<th>Agency</th>
							<th>MITM/Work Order</th>
							<th>Invoice Number</th>
							<th>Address</th>
							<th>Inv. Amount</th>
							<th>Invoice/Cert</th>
							<th>Certificate</th>
							<th class="leftGreyBorder">Invoice</th>
							<th class="leftGreyBorder">Quote Amount</th>
							<th>QLD Upgrade Quote</th>
							<th>Needs Processing</th>
							<th>Note</th>
							<th>
								<div class="checkbox" style="margin:0;">
									<input name="chk_all" type="checkbox" id="check-all">
									<label for="check-all">&nbsp;</label>
								</div>
							</th>
						</tr>
					</thead>

					<tbody>
						<?php
						if($lists->num_rows()>0){
						$i = 0;

						foreach($lists->result_array() as $list_item): 	
						$params = array(
							'sel_query' => "sr.subregion_name as postcode_region_name, sr.sub_region_id as postcode_region_id",
							'postcode' => $list_item['p_postcode'],
						);
						$getRegion = $this->system_model->get_postcodes($params)->row();

						// grey alternation color
						$row_color = ($i%2==0)?"style='background-color:#eeeeee;'":"";
						
						?>
						<tr class="body_tr jalign_left" <?php echo $row_color; ?>>
							
							<td><?php echo ($this->customlib->isDateNotEmpty($list_item['jdate'])==true)?$this->customlib->formatDate($list_item['jdate'],'d/m/Y'):''; ?></td>
							<td>
								<?php echo ( $this->customlib->isDHAagenciesV2($list_item['franchise_groups_id'])==true )?'DHA':$list_item['m_name'];  ?>
								<input type="hidden" class="main_prog" value="<?php echo $list_item['m_name']; ?>" />
							</td>
							<td>
								<?php /*
								<a href="/view_agency_details.php?id=<?php echo $list_item['agency_id']; ?>"><?php echo $list_item['agency_name']; ?></a>
								*/
								echo $this->gherxlib->crmLink('vad',$list_item['a_id'],$list_item['agency_name']);
								?>

							</td>
							<td><?php echo $list_item['work_order']; ?></td>
							<td>
								<?php /*
								<a href="/view_job_details.php?id=<?php echo $list_item['jid']; ?>"><?php echo $this->customlib->getInvoiceNumber($list_item['jid']); ?></a>
								*/?>

								<?php
									$invoiceNum = $this->customlib->getInvoiceNumber($list_item['jid']);
									 echo $this->gherxlib->crmLink('vjd',$list_item['jid'],$invoiceNum);
								?>

							</td>
							
							
							<td>
							<?php
							/*
							<a href="/properties/details/?id=<?php echo $list_item['property_id']; ?>"><?php echo "{$list_item['p_address_1']} {$list_item['p_address_2']}, {$list_item['p_address_3']}"; ?></a>
							*/
							?>
							<?php
								$prop_address = $list_item['p_address_1']." ".$list_item['p_address_2'].", ".$list_item['p_address_3'];
								echo $this->gherxlib->crmLink('vpd',$list_item['property_id'],$prop_address);
							?>
							</td>
							
							
							<td>$<?php echo number_format($this->customlib->getInvoiceTotal($list_item['jid']),2); ?></td>
							
							<!-- combined -->
							<td>
								<?php //if( $list_item['ts_completed'] == 1 ): ?>
									<div <?php //echo ( $this->customlib->isDHAagenciesV2($list_item['franchise_groups_id'])==true )?'style="display:none;"':''; ?>>
										<a target="blank" href="<?php echo base_url(); ?>pdf/invoices/<?php echo $list_item['jid']; ?>/invoicing"><img src="/images/pdf.png" /></a>
										<a target="blank" style="margin-left: 10px; float: right;" href="<?php echo base_url(); ?>pdf/invoices/<?php echo $list_item['jid']; ?>/invoicing/D"><img src="/images/download_icon.png" /></a>
										<img src="/images/email_green.png" data-job_id="<?php echo $list_item['jid']; ?>" data-pdf_type="invoice_cert" class="btn_email_agency <?php echo ( date('Y-m-d',strtotime($list_item['mm_need_proc_inv_emailed'])) == date('Y-m-d') )?'fadeIt':''; ?>" />
									</div>
								<?php //endif; ?>							
							</td>

							<!-- Certificate -->
							<td class="leftGreyBorder">
								<div>
									<a target="blank" href="<?php echo base_url(); ?>pdf/certificates/<?php echo $list_item['jid']; ?>/certificate"><img src="/images/pdf.png" /></a>
									<a target="blank" style="margin-left: 10px; float: right;" href="<?php echo base_url(); ?>pdf/certificates/<?php echo $list_item['jid']; ?>/certificate/D"><img src="/images/download_icon.png" /></a>
									<img src="/images/email_green.png" data-job_id="<?php echo $list_item['jid']; ?>" data-pdf_type="invoice" class="btn_email_agency <?php echo ( date('Y-m-d',strtotime($list_item['mm_need_proc_inv_emailed'])) == date('Y-m-d') )?'fadeIt':''; ?>" />
								</div>
							</td>
							
							<!-- invoice -->
							<td class="leftGreyBorder">
								<div <?php //echo ( $this->customlib->isDHAagenciesV2($list_item['franchise_groups_id'])==true )?'style="display:none;"':''; ?>>
									<a target="blank" href="<?php echo base_url(); ?>pdf/invoices/<?php echo $list_item['jid']; ?>/invoicing"><img src="/images/pdf.png" /></a>
									<a target="blank" style="margin-left: 10px; float: right;" href="<?php echo base_url(); ?>pdf/invoices/<?php echo $list_item['jid']; ?>/invoicing/D"><img src="/images/download_icon.png" /></a>
									<img src="/images/email_green.png" data-job_id="<?php echo $list_item['jid']; ?>" data-pdf_type="invoice" class="btn_email_agency <?php echo ( date('Y-m-d',strtotime($list_item['mm_need_proc_inv_emailed'])) == date('Y-m-d') )?'fadeIt':''; ?>" />
									
								</div>
							</td>
							
							<?php
							if( $list_item['p_state']=='QLD' ){

								if( $list_item['prop_upgraded_to_ic_sa'] == 1 ){ ?>
									<td class="leftGreyBorder">Upgraded</td>
									<td>N/A</td>
								<?php
								}else{ ?>
								
								
									<!-- THIS IS HARDCODED; needs to be changed to dynamic sooner -->
									<?php
									$quote_qty = $list_item['qld_new_leg_alarm_num'];
									$price_240vrf = $this->customlib->get240vRfAgencyAlarm($list_item['a_id']);
									$quote_price = ( $price_240vrf > 0 )?$price_240vrf:200;
									$quote_total = $quote_price*$quote_qty;
									
									// QUOTE
									if( $quote_total > 0 ){ ?>
										<td class="leftGreyBorder">	
											<?php echo "$".number_format($quote_total,2);

												$has_brooks_quote = false;
												$has_cavius_quote = false;

												$agency_id = $list_item['a_id'];
	
												//quote pdf
											?>			
										</td>										
										<td>
											<?php 
												$this->db->select('COUNT(`agency_alarm_id`) AS agen_al_count');
												$this->db->from('agency_alarms');
												$this->db->where('agency_id', $agency_id);
												$this->db->where('alarm_pwr_id', 10);
												$query = $this->db->get();
												$result = $query->row();
												$check_brooks = $result->agen_al_count;

												if( $check_brooks > 0 ){
													$qt = "brooks";
													$qt_brooks = 1;
												}	

												if($qt_brooks == 1){
											?>
												<div <?php echo ( $this->customlib->isDHAagenciesV2($list_item['franchise_groups_id'])==true )?'style="display:none;"':''; ?>>
													<!--<a target="blank" href="view_quote.php?job_id=<?php //echo $list_item['jid']; ?>"><img src="/images/pdf.png" /></a> -->
													<!--<a target="blank" style="margin-left: 10px;" href="view_quote.php?job_id=<?php //echo $list_item['jid']; ?>&output_type=D"><img src="/images/download_icon.png" /></a> -->
													<a target="blank" href="<?php echo base_url(); ?>pdf/quotes/<?php echo $list_item['jid']; ?>/brooks"><img src="/images/pdf.png" /></a>
													<a target="blank" style="margin-left: 10px;" href="<?php echo base_url(); ?>pdf/quotes/<?php echo $list_item['jid']; ?>/brooks/quote/D"><img src="/images/download_icon.png" /></a>
													<img src="/images/email_green.png" data-job_id="<?php echo $list_item['jid']; ?>" data-pdf_type="quote" data-qt_type="brooks" class="btn_email_agency <?php echo ( date('Y-m-d',strtotime($list_item['qld_upgrade_quote_emailed'])) == date('Y-m-d') )?'fadeIt':''; ?>" />
												</div>
											<?php
												}

												$this->db->select('COUNT(`agency_alarm_id`) AS agen_al_count');
												$this->db->from('agency_alarms');
												$this->db->where('agency_id', $agency_id);
												$this->db->where('alarm_pwr_id', 22);
												$query1 = $this->db->get();
												$result1 = $query1->row();
												$check_emerald = $result1->agen_al_count;
												
												if( $check_emerald > 0 ){
													$qt = "emerald";
													$qt_emerald = 1;
												}

												if($qt_emerald == 1){
											?>
												<div <?php echo ( $this->customlib->isDHAagenciesV2($list_item['franchise_groups_id'])==true )?'style="display:none;"':''; ?>>
													<!--<a target="blank" href="view_quote.php?job_id=<?php //echo $list_item['jid']; ?>"><img src="/images/pdf.png" /></a> -->
													<!--<a target="blank" style="margin-left: 10px;" href="view_quote.php?job_id=<?php //echo $list_item['jid']; ?>&output_type=D"><img src="/images/download_icon.png" /></a> -->
													<a target="blank" href="<?php echo base_url(); ?>pdf/quotes/<?php echo $list_item['jid']; ?>/emerald/quote"><img src="/images/pdf.png" /></a>
													<a target="blank" style="margin-left: 10px;" href="<?php echo base_url(); ?>pdf/quotes/<?php echo $list_item['jid']; ?>/emerald/quote/D"><img src="/images/download_icon.png" /></a>
													<img src="/images/email_green.png" data-job_id="<?php echo $list_item['jid']; ?>" data-pdf_type="quote" data-qt_type="emerald" class="btn_email_agency <?php echo ( date('Y-m-d',strtotime($list_item['qld_upgrade_quote_emailed'])) == date('Y-m-d') )?'fadeIt':''; ?>" />
												</div>
											<?php
												}
											?>
										</td>
									<?php	
									}else{ ?>
										<td class="leftGreyBorder">N/A</td>
										<td>N/A</td>
									<?php
									}
									?>

								<?php
								}

							?>
								
							<?php
							}else{ ?>
							
								<td class="leftGreyBorder">N/A</td>
								<td>N/A</td>
							
							<?php
							}
							?>
							
							
							
							<td>

								<div class="checkbox" style="margin:0;">
									<input type="checkbox" id="nd_<?=$list_item['jid']?>" class="np_chk" <?php echo ($list_item['dha_need_processing']==1)?'checked="checked"':''; ?> value="<?php echo $list_item['jid']; ?>" />
									<label for="nd_<?=$list_item['jid']?>">&nbsp;</label>
								</div>
							
							</td>

							<!-- Note Table column -->
							<td>
								<button type="button" class="btn btn-primary add-note-btn" data-job-id="<?=  $list_item['jid'] ?>">Note</button>
							</td>
							<!-- End Note Table column -->

							<td>
								
								<div class="checkbox" style="margin:0;">
									<input type="checkbox" id="nd2_<?=$list_item['jid']?>" class="np_chk2" value="<?php echo $list_item['jid']; ?>" attra="<?=$list_item['dha_need_processing']?>" attrb="<?=$list_item['m_name']?>" data-invoice-cert="<?=$list_item['ts_completed']?>" data-quote="<?=$quote_total?>" />
									<label for="nd2_<?=$list_item['jid']?>">&nbsp;</label>
								</div>
							
							</td>
							
									
						</tr>

					<?php $i++; endforeach; ?>
					<?php } ?>
					</tbody>

				</table>                
				<div id="mbm_box" class="text-right">
					<div class="gbox_main">
						<div class="gbox">
							<button id="send_clear_btn" type="button" class="btn">Send and Clear</button>
						</div>
					</div>
				</div>
			
			</div>

		</div>
	</section>

</div>

<!-- Fancybox Start -->
<a href="javascript:" id="about_page_fb_link" class="fb_trigger" data-fancybox data-src="#about_page_fb">Trigger the fancybox</a>
<div id="about_page_fb" class="fancybox" style="display:none;" >

	<h4><?php echo $title; ?></h4>
	<p>This page shows any jobs that we have successfully completed but are under the maintenance program “Maintenance Manager, Our Tradie etc” and require us to upload into their system. Once processed, the jobs will no longer appear on this page.
</p>

</div>
<!-- Fancybox END -->

<!--  Bootstrap modal for adding notes -->
<div class="modal fade" id="addNoteModal" tabindex="-1" role="dialog" aria-labelledby="addNoteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNoteModalLabel">Platform Invoicing Note</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
				<div id="noteContent"></div>
                <form id="addNoteForm" name="addNoteForm" method="POST">
                    <input type="hidden" id="jobNoteId" name="jobNoteId" value="">
                    <input type="hidden" id="jobId" name="jobId" value="">
                    <div class="form-group">
                        <label for="noteText">Note:</label>
                        <textarea class="form-control" id="noteText" name="noteText" rows="4"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveNoteBtn">Save Note</button>
            </div>
        </div>
    </div>
</div>

<style>
	.btn_email_agency {
    cursor: pointer;
    margin-left: 5px;
	}
	.fadeIt {
		opacity: 0.5;
	}
</style>

<script>

jQuery(document).ready(function(){

	// Handle the click event for the "Add Note" button
	$(".add-note-btn").click(function () {
          
		let jobId = $(this).data("job-id"); 
		
		$.ajax({
			url: '<?php echo site_url(); ?>ajax/jobs_ajax/get_job_note/' + jobId,  
			type: "GET",
			success: function (response) {
				let data = JSON.parse(response)

				$("#addNoteModal").modal("show");
				if(data || data != null){
					$('#jobNoteId').val(data.id);
					$('#jobId').val(data.job_id);
					$("#noteText").val(data.note);
					$("#noteText").prop('readonly', true);
					$("#saveNoteBtn").text("Edit Note");
				}else{
					$('#jobNoteId').val('');
					$('#jobId').val(jobId);
					$("#noteText").val('');
					$("#noteText").prop('readonly', false);
					$("#saveNoteBtn").text("Save Note");
				}
			},
			error: function (res) {
				console.log("🚀 ~ file: dha_pre_com_jobs.php:466 ~ res:", res)
			},
		});
	});
        
	$("#saveNoteBtn").click(function () {

		let data = $("#addNoteForm").serialize();
		// let url = '<?php echo site_url(); ?>ajax/jobs_ajax/save_job_invoice_note';

		if ($("#saveNoteBtn").text() === "Save Note") {
			let url = '<?php echo site_url(); ?>ajax/jobs_ajax/save_job_invoice_note';

			jQuery('#load-screen').show();
			ajax(url, data).done(function(results){
				console.log(results);
				if (results.success){
					swal({
						title: "Success!",
						text: results.message,
						type: "success",
						showConfirmButton: false,
						timer: 1500
					}); 
			
					setInterval(() => {
						$('#load-screen').hide();
					}, 1000);    
				
				}else{
					swal({
						title: "Oops..",
						text: results.message,
						type: "error",
						showConfirmButton: true
					});
				}
			})
		} else if ($("#saveNoteBtn").text() === "Edit Note") {
			$("#noteText").prop("readonly", false);
			$("#saveNoteBtn").text("Update Note");
		} else {
			let url = '<?php echo site_url(); ?>ajax/jobs_ajax/update_note';

			jQuery('#load-screen').show();
			ajax(url, data).done(function(results){
				console.log(results);
				if (results.success){
					swal({
						title: "Success!",
						text: results.message,
						type: "success",
						showConfirmButton: false,
						timer: 1500
					}); 
					$("#noteText").prop("readonly", true);
					$("#saveNoteBtn").text("Edit Note");
			
					setInterval(() => {
						$('#load-screen').hide();
					}, 1000);    
				
				}else{
					swal({
						title: "Oops..",
						text: results.message,
						type: "error",
						showConfirmButton: true
					});
				}
			})
		}
	});

	// move/assign to maps 
	jQuery("#send_clear_btn").on('click',function(){
		
		var job_id = new Array();
		var attra = new Array();
		var attrb = new Array();
		var attrc = new Array();
		var attrd = new Array();
		
		//push job_id array
		jQuery(".np_chk2:checked").each(function(){
			job_id.push(jQuery(this).val());
			attra.push(jQuery(this).attr("attra"));
			attrb.push(jQuery(this).attr("attrb"));
			attrc.push(jQuery(this).attr("data-invoice-cert"));
			attrd.push(jQuery(this).attr("data-quote"));
		});

		$('#load-screen').show(); //show loader
		jQuery.ajax({
			type: "POST",
			url: "/jobs_ajax/dpc_mod/send_and_clear",
			data: { 
				job_id: job_id,
				attra : attra,
				attrb : attrb,
				data_invoice_cert : attrc,
				data_quote : attrd
			}
		}).done(function( ret ){
			$('#load-screen').hide(); //hide loader
			return false;
			
			swal({
				title:"Success!",
				text: "clear success",
				type: "success",
				showCancelButton: false,
				confirmButtonText: "OK",
				closeOnConfirm: false,

			},function(isConfirm){
			if(isConfirm){ 
				location.reload();
				}
			});
				
		});	
				
	});

	$('#check-all').on('change',function(){
		var obj = $(this);
		var isChecked = obj.is(':checked');
		var divbutton = $('#mbm_box');
		if(isChecked){
			divbutton.show();
			$('.np_chk2').prop('checked',true);
		}else{
			divbutton.hide();
			$('.np_chk2').prop('checked',false);
		}
	})

	$('.np_chk2').on('change',function(){
		var obj = $(this);
		var isLength = $('.np_chk2:checked').length;
		var divbutton = $('#mbm_box');
		if(isLength>0){
			divbutton.show();
		}else{
			divbutton.hide();
		}
	})
	
	jQuery(".btn_email_agency").click(function(){
		
		var job_id = jQuery(this).attr("data-job_id");
		var pdf_type = jQuery(this).attr("data-pdf_type");
		var qt_type = jQuery(this).attr("data-qt_type");

		swal({
	      title: "Confirm",
	      text: "Are you sure you sure you want to continue?",
	      type: "info",
	      showCancelButton: true,
	      showLoaderOnConfirm: true,
	    }, function () {
			$.ajax({
	            type: "POST",
	            url: '<?php echo base_url(); ?>jobs_ajax/dpc_mod/ajax_dha_precomp_email_agency_accounts',
				data: { 
					job_id: job_id,
					pdf_type: pdf_type,
					qt_type: qt_type
				}
			}).done(function(res){

				if (res == 1) {
        			location.reload();
				}else {
					swal('Email not sent','Something went wrong, kindly contact dev team.','error');
					return false;
				}
			});	
	    });	
				
	});
	
	
	
	
	jQuery(".np_chk").change(function(){
		
		var state = jQuery(this).prop('checked');
		var job_id = jQuery(this).val();
		var main_prog = jQuery(this).parents("tr:first").find('.main_prog').val();
		
		var dha_need_processing = (state==true)?1:0;

		$.ajax({
            type: "POST",
            url: '<?php echo base_url(); ?>jobs_ajax/dpc_mod/ajax_update_dha_need_processing',
			data: { 
				job_id: job_id,
				dha_need_processing: dha_need_processing,
				main_prog: main_prog
			}
		}).done(function(res){
			// jQuery("#btn_search").click();
		});	

	});

	jQuery("#refresh_btn").on('click', function() {
		location.reload(); //reload page
	});
	
});
</script>