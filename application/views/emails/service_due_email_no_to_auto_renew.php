<!-- CONTENT START HERE -->
<?php 
// no assigned PM
$no_assigned_pm_txt = "<span style='font-style: italic;'>No Assigned Property Manager</span>"; 
?>
<p>
    Dear <?= $agency_name; ?>, <br />
</p>
<p>
    This is a courtesy email to advise that the below properties are due for their subscription renewal with <?= config_item("company_full_name") ?>.
</p>

<table style="width:100%; border: 1px solid #efefef;">

    <tr>
        <td style="background-color: #404041; color: #ffffff; padding: 5px;"><b>Month Due</b></td>
        <td style="background-color: #404041; color: #ffffff; padding: 5px;"><b>Address</b></td>
        <td style="background-color: #404041; color: #ffffff; padding: 5px;"><b>Property Manager</b></td>
    </tr>
    
    <?php
    // get pending jobs
    $rowcount = 0;
    foreach( $pending_sql->result() as $pending_row ){

        // property address
        $paddress = "{$pending_row->p_address_1} {$pending_row->p_address_2}, {$pending_row->p_address_3}";
        
        // get property managers
        if( $pending_row->agency_user_account_id > 0 ){
            $pm_name = "{$pending_row->pm_fname} {$pending_row->pm_lname}";
        }else{
            $pm_name = $no_assigned_pm_txt;
        }

    ?>
        <tr style="background-color:<?php echo ($rowcount % 2 == 0 ? null : "#efefef") ?>">
            <td style="padding: 5px;"><?php echo date('F',strtotime($pending_row->start_date)); ?></td>
            <td style="padding: 5px;"><?php echo $paddress; ?></td>
            <td style="padding: 5px;"><?php echo $pm_name; ?></td>
        </tr>
    <?php
        $rowcount++;
    }
    ?>

</table>

<p style="color:red;">
    Kindly be advised that we are strictly instructed NOT to visit these properties as part of our yearly maintenance schedule. Without explicit permission to carry
    out the required work, <?= config_item("company_full_name") ?> will consider these properties non-compliant and will not accept liability regarding their compliance.
</p>

<p>
    We ask that you advise which properties are to remain on our yearly maintenance schedule or noted as 'No Longer Managed'
</p>
<p>
    Should you have any additional questions, please feel free to contact our friendly Customer Service Team on <?= config_item('customer_service'); ?> or please respond to
    this email directly.
</p><br />

<p>
    Kind Regards,<br />
</p>
<p>
	<?=config_item('company_full_name');?>.
</p>
<!-- CONTENT END HERE -->