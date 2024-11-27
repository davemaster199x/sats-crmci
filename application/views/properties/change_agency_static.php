<div class="box-typical box-typical-padding">

	<?php 
        // breadcrumbs template
        $bc_items = array(
            array(
                'title' => 'Properties',
                'link' => "/properties"
            ),
            array(
                'title' => $title,
                'status' => 'active',
                'link' => "/properties/change_agency_static?id={$this->input->get_post('id')}"
            )
        );
		$bc_data['bc_items'] = $bc_items;
	?>

	<?php $this->load->view('templates/breadcrumbs', $bc_data); ?>

	<section>
		<div class="alert_container"></div>

		<div class="body-typical-body">		

			<form id="AgencyForm" method="POST">
				<input id="previous_agency_id" type="hidden" name="previous_agency_id" value="<?= $previous_agency_id ?>" />
				<input id="current_agency_id" type="hidden" name="current_agency_id" value="0" />
				<input id="current_pm_id" type="hidden" name="current_pm_id" value="0" />
				<input id="property_id" type="hidden" name="property_id" value="<?= $this->input->get_post('id') ?>" />

				<div class="form-group row">
					<label for="agency" class="col-sm-2 col-md-2">Select Agency</label>
					<div class="col-sm-3 col-md-3">
						<select class="addinput form-control" name="agency" id="agency_list">
							<option value="">----</option>
							<?php foreach($agency_list as $row): ?>
								<option value="<?php echo $row->agency_id.','.$row->franchise_groups_id ?>"><?php echo $row->agency_name ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div id="pm_agency" style="display:none">
					<div class="form-group row" >
						<label for="agency_user_accounts" class="col-sm-2 col-md-">Select PM</label>
						<div class="col-sm-3 col-md-3">
							<select class="addinput form-control " name="agency_user_accounts" id="agency_user_accounts">
								
							</select>
						</div>
					</div>
				</div>
				<button type="submit" class="btn btn-primary mb-2">Change Agency</button>
			</form>

		</div>
	</section>
</div>

<style>
	.show { display: block; }
	.hide { display: none; }
	.success {
		border: 1px solid #4f8a10;
		background-color: #dff2bf;
		padding: 10px;
		margin: 5px 0px;
		color: #4f8a10;
	}
	label { padding: 10px; }
</style>
<script>

$(document).ready(function() {
	$('#agency_list').on("change", function(){
     	var agency_list = $(this).val();
		var array = agency_list.split(',');
		var agency_id = array[0];
		var franchise_groups_id = array[1];
     	var harris_agencies = ["1961","6203","6974"];

		var country = <?=$this->config->item('country')?>

		if (country == 1) { // AU live & Devsite same id
			var groups_id = '10';
		} else { // NZ
			var groups_id = '37';
		}

		//var check_landlord = <?=$check_landlord?>;

		var landlord_has_req_details = parseInt(<?php echo $landlord_has_req_details; ?>);

		if ( franchise_groups_id == groups_id && landlord_has_req_details != 1 ) {
			swal({
				title: "Info!",
				text: "This property is missing landlord data, please complete it before moving the property",
				type: "info"
			});

			// hide PM dropdown
			$("#pm_agency").css("display", "none");
			
			//reset agency dropdown
			$("#agency_list").val("");
		} else {

			$("#load-screen").show();

			//assign agency_id value on hidden input when onchange event is trigger
			$("#current_agency_id").val(agency_id);

			$.ajax({
				url: "<?php echo site_url(); ?>ajax/properties_ajax/ajax_get_agency_user_accounts",
				type: 'POST',
				data: { 
					'agency_id': agency_id,
				}
			}).done(function( res ){
				var _data = JSON.parse(res);
				var agency_html = "";

				if( harris_agencies.indexOf(agency_id) > -1 ){
					alert("If you have not been instructed to move this property and are only doing so as you think it is in the wrong portfolio, please check the KEY number first: \nKey Number 0 to 2999-  Harris Adelaide Portfolio\nKey Number 3000 to 3999- Harris Glenelg Portfolio\nKey Number 7000 to 7999-  Harris Stirling Portfolio");
				}

				$("#pm_agency").css("display", "block");
										
				//auto populate pm dropdown
				if (_data.success === true && (_data._data !== 'undefined' || _data._data !== null)) {
					agency_html += '<option value="">Please Select</option>';
					$.each(_data.data, function (key, value) {
						agency_html += '<option value="' + value.agency_user_account_id + '">' + value.fname + " " + value.lname  + '</option>';
					});
					$("#agency_user_accounts").html(agency_html);
				} else {
					agency_html += '<option value="">Please Select</option>';
					$("#agency_user_accounts").html(agency_html);

					swal({
						title: "Error!",
						text: "Agency User Account is empty!",
						type: "error"
					});

				}
				
				$('#load-screen').hide(); 

			});			
		}
		 
   	});

	$("#agency_user_accounts").on("change", function() {
		var pm_id = $(this).val();
		//assign agency_id value on hidden input when onchange event is trigger
		$("#current_pm_id").val(pm_id);
	});

	$("#AgencyForm").on("submit", function(e) {
		e.preventDefault();

		$("#load-screen").show();
		
		var previous_agency_id = $("#previous_agency_id").val();
		var current_agency_id = $("#current_agency_id").val();
		var pm_id = $("#current_pm_id").val();
		var property_id = $("#property_id").val();

		const jsonData = {
			"previous_agency_id" : previous_agency_id,
			"current_agency_id" : current_agency_id,
			"current_pm_id" : pm_id,
			"property_id" : property_id
		}

		$.ajax({
			url: "<?php echo site_url(); ?>ajax/properties_ajax/ajax_update_agency_property",
			type: 'POST',
			data: jsonData
		}).done(function( res ){
			var _data = JSON.parse(res);

			// append alert
			$(".alert_container").html(_data.data.alert_html);

			$('#load-screen').hide(); 

			// hide PM dropdown
			$("#pm_agency").css("display", "none");
			
			//reset agency dropdown
			$("#agency_list").val("");

		});		
	})
})

</script>