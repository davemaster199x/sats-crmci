<?php $this->load->view('emails/template/email_header.php') ?>


<!-- CONTENT START HERE -->


<h3>Change of Address</h3>

<table style='margin:0;width:100%;'>
	<tr>
		<td>Date</td><td><?php echo date('d/m/Y',strtotime($today)) ?></td>
	</tr>
	<tr style="background-color:#efefef">
		<td>Name</td><td><?php echo $employee_name; ?></td>
	</tr>
	<tr>
		<td>Street Number</td><td><?php echo $street_number; ?></td>
	</tr>
	<tr style="background-color:#efefef">
		<td>Street Name</td><td><?php echo $street_name; ?></td>
	</tr>
	<tr>
		<td>Suburb</td><td><?php echo $suburb; ?></td>
	</tr>
	<tr style="background-color:#efefef">
		<td>State</td><td><?php echo $state; ?></td>
	</tr>
	<tr>
		<td>Postcode</td><td><?php echo $postcode; ?></td>
	</tr>
	<tr style="background-color:#efefef">
		<td>Move Date</td><td><?php echo $move_date; ?></td>
	</tr>

</table>

<p>Kind Regards,<br /><?=$this->config->item('company_name_short')?> Team</p>

<!-- CONTENT END HERE -->


<?php $this->load->view('emails/template/email_footer.php');