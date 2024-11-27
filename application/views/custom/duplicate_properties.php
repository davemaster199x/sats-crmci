<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('templates/new/header');

?>
<h1>Duplicate Properties</h1>
    <a href="javascript:;" id="about_page_fb_link" class="fb_trigger" data-fancybox data-src="#about_page_fb">About Popup</a>
    <div id="about_page_fb" class="fancybox" style="display:none;" >
        <h1>Duplicate Properties</h1>
        <p>
            Showing all properties that have 2 or more ACTIVE duplicates based on the following columns:<br>
        </p>
        <ul>
            <li>Number</li>
            <li>Street</li>
            <li>Suburb</li>
        </ul>
        <h2>SQL</h2>
        <code><?= $sql; ?></code>
    </div>



<table id="datatable" class="table table-hover main-table">
	<thead>
	<tr class="toprow jalign_left">
        <?php foreach ($cols as $key => $value) { ?>
		    <th><?= $value; ?></th>
		<?php } ?>
	</tr>
	</thead>
	<tbody>
	<?php
	if (!empty($data)) {
		foreach ($data as $row) {
		?>
			<tr class="body_tr jalign_left">
				<td style="text-align:center;">
                    <?= $this->gherxlib->crmLink('vpd',$row["Property ID"],$row["Property ID"], '_blank') ?>
                </td>
				<td class="sorting_2" style="text-align:center;"><?= $row['Number']; ?></td>
				<td class="sorting_1"><?= $row['Street']; ?></td>
				<td><?= $row['Suburb']; ?></td>
				<td style="text-align:center;"><?= $row['State']; ?></td>
				<td style="text-align:center;"><?= $row['Postcode']; ?></td>
				<td style="text-align:center;"><?= $row['Deleted']; ?></td>
				<td style="text-align:center;"><?= $row['Agency Deleted']; ?></td>
                <td style="text-align:center;"><a href="/agency/view_agency_details/<?= $row['Agency ID']; ?>" target="_blank"><?= $row['Agency ID']; ?></a></td>
                <td><a href="/agency/view_agency_details/<?= $row['Agency ID']; ?>" target="_blank"><?= $row['Agency Name']; ?></a></td>
			</tr>

		<?php } ?>
	<?php } else { ?>
		No Results
	<?php }?>
	</tbody>
</table>
</div>
<script>
    $(document).ready(function () {
        $('body').addClass('newlook');
    });
</script>
<?php
$this->load->view('templates/inner_footer');