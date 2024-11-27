<!-- CONTENT START HERE -->
<?php
    $no_assigned_pm_txt = "<span style='font-style: italic;'>No Assigned Property Manager</span>";  // no assigned PM
?>

<p>Dear <?php echo $agency_name; ?>,</p>

<!-- Switch email content text here based agency api connected or not -->
<!-- Agency is conntect to API -->
<?php if( $agency_api_tokens_q->num_rows() > 0 ): ?>


    <?php if ($agency_state == "QLD"): ?>

        <?php if($agency_auto_renew == 1 && $subscription_billing == 1 && $apis->active == 1): ?>

            <p>
                Please <a href='<?php echo $agency_portal_link; ?>'>log in</a>
                to our Agency Portal and go to the 'Service Due' page to view properties that are now
                <?php if ($agency_auto_renew == 1 && $subscription_billing == 1 && $apis->active == 1): ?>
                    due for subscription renewal.
                <?php else: ?>
                    due for service.
                <?php endif; ?>
            </p>

            <p>Please note, because we have an active API connection with your Agency, our Team will adjust any updated tenant details required.</p>
            <p>If you no longer manage the property, then mark the checkbox and 'click' NO LONGER MANAGE. These changes can be done in multiple ways.</p>
            <p style="color:red;">Any properties that are still in 'Service Due' by the 15th of the month, will automatically be renewed to fulfill our obligations to your landlords.</p>

            <p>
                When you process the properties due for subscription renewal, IF we identify that a property needs to be serviced within the next 60 days (to meet legislative requirements) we will send the job straight to booking
                (previously this job was ‘on hold’ until the beginning of the following month).
            </p>
            
            <?php if (config_item('theme') === 'sats'): ?>
            <p>
                To view our Step-by-Step video on how to process properties that are due for subscription renewal, please click <a href='<?php echo $youtube_link ?>'>HERE</a>.
            </p>
            <?php endif; ?>
            <p>If you need any help or have questions, please contact our office on <?php echo $agent_number; ?> and speak with one of our friendly staff members.</p>

        <?php elseif($agency_auto_renew == 0 && $subscription_billing == 1 && $apis->active == 0): ?>

            <p>This is a courtesy to advise the below properties are due for subscription renewals here at <?=config_item('company_full_name');?> (<?=config_item('company_name_short');?>)</p>
            <p>Please note, because we have an active API connection with your Agency, our Team will adjust any updated tenant details required.</p>

            <p>We ask that you advise which properties are to continue <?=config_item('company_name_short');?> services and which are to be deactivated within our database (if required).</p>

            <br /><br />
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
                    $pm_name = ($pending_row->agency_user_account_id > 0) ? "{$pending_row->pm_fname} {$pending_row->pm_lname}" : $no_assigned_pm_txt;

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

            <p>
                Please note: We are under your strict instruction to NOT renew these properties as part of our Annual Maintenance program.
                If we are not granted permission to attend and complete works, <?=config_item('company_name_short');?> shall deem these properties non-compliant and accept no liability pertaining to their compliance.
            </p>
            <p>When you process the properties due for subscription renewal, and it has been identified that the property has not had a physical attendance completed for more than 12 months, we will generate a job to attend.</p>
            <p>Should you have any additional questions, please feel free to contact our friendly Customer Service Team on <?= config_item('customer_service'); ?> or please respond to this email directly.</p>

        <?php else: ?> <!-- Default -->

            <p>
                Please <a href='<?php echo $agency_portal_link; ?>'>log in</a>
                to our Agency Portal and go to the 'Service Due' page to view properties that are now
                <?php if ($agency_auto_renew == 1 && $subscription_billing == 1 && $apis->active == 1): ?>
                    due for subscription renewal.
                <?php else: ?>
                    due for service.
                <?php endif; ?>
            </p>
            <p>If the tenant details are correct and you still manage the property, then mark the check box and 'click' CREATE JOB.</p>
            <p>If you no longer manage the property, then mark the checkbox and 'click' NO LONGER MANAGE. These changes can be done in multiple ways.</p>
            <p style='color:red;'>Any properties that are still in 'Service Due' by the 15th of the month, will automatically be renewed to fulfill our obligations to your landlords.</p>

            <p>
                When you process the properties
                <?php if($agency_auto_renew == 1 && $subscription_billing == 1 && $apis->active == 1): ?>
                    due for subscription renewal,
                <?php else: ?>
                    due for service,
                <?php endif; ?>
                IF we identify that a property needs to be serviced within the next 60 days (to meet legislative requirements) we will send the job straight to booking
                (previously this job was ‘on hold’ until the beginning of the following month).
            </p>
            
            <?php if (config_item('theme') === 'sats'): ?>
            <p>
                To view our Step-by-Step video on how to process properties that are
                <?php if ($agency_auto_renew == 1 && $subscription_billing == 1 && $apis->active == 1): ?>
                    due for subscription renewal,
                <?php else: ?>
                    due for service,
                <?php endif; ?>
                please click <a href='<?php echo $youtube_link ?>'>HERE</a>.
            </p>
            <?php endif; ?>
            <p>If you need any help or have questions, please contact our office on <?php echo $agent_number; ?> and speak with one of our friendly staff members.</p>

        <?php endif; ?>

    <?php else: ?> <!-- Default -->

        <p>
            Please <a href='<?php echo $agency_portal_link; ?>'>log in</a>
            to our Agency Portal and go to the 'Service Due' page to view properties that are now
            <?php if ($agency_state == "QLD" && $agency_auto_renew == 1 && $subscription_billing == 1 && $apis->active == 1): ?>
                due for subscription renewal.
            <?php else: ?>
                due for service.
            <?php endif; ?>
        </p>
        <p>If the tenant details are correct and you still manage the property, then mark the check box and 'click' CREATE JOB.</p>
        <p>If you no longer manage the property, then mark the checkbox and 'click' NO LONGER MANAGE. These changes can be done in multiple ways.</p>
        <p style='color:red;'>Any properties that are still in 'Service Due' by the 15th of the month, will automatically be renewed to fulfill our obligations to your landlords.</p>

        <p>
            When you process the properties
            <?php if($agency_auto_renew == 1 && $subscription_billing == 1 && $apis->active == 1): ?>
                due for subscription renewal
            <?php else: ?>
                due for service,
            <?php endif; ?>
            IF we identify that a property needs to be serviced within the next 60 days (to meet legislative requirements) we will send the job straight to booking
            (previously this job was ‘on hold’ until the beginning of the following month).
        </p>
        
        <?php if (config_item('theme') === 'sats'): ?>
        <p>
            To view our Step-by-Step video on how to process properties that are
            <?php if ($agency_state == "QLD" && $agency_auto_renew == 1 && $subscription_billing == 1 && $apis->active == 1): ?>
                due for subscription renewal
            <?php else: ?>
                due for service,
            <?php endif; ?>
            please click <a href='<?php echo $youtube_link ?>'>HERE</a>.
        </p>
        <?php endif; ?>
        <p>If you need any help or have questions, please contact our office on <?php echo $agent_number; ?> and speak with one of our friendly staff members.</p>

    <?php endif; ?>

<?php else: ## Agency is NOT connected to any API ?>

    <?php if ($agency_state == "QLD"): ?>

        <?php if($agency_auto_renew == 1 && $subscription_billing == 1 && $apis->active == 0): ?>

            <p>
                Please <a href='<?php echo $agency_portal_link; ?>'>log in</a>
                to our Agency Portal and go to the 'Due for Subscription Renewal' page to view properties that are now
                <?php if ($agency_auto_renew == 1 && $subscription_billing == 1 && $apis->active == 0): ?>
                    due for subscription renewal.
                <?php else: ?>
                    due for service.
                <?php endif; ?>
            </p>

            <p>Please note, because we have an active API connection with your Agency, our Team will adjust any updated tenant details required.</p>
            <p>If you no longer manage the property, then mark the checkbox and 'click' NO LONGER MANAGE. These changes can be done in multiple ways.</p>
            <!--<p style="color:red;">Any properties that are still in 'Service Due' by the 15th of the month, will automatically be renewed to fulfill our obligations to your landlords.</p>-->
            <p style="color:red;">Any properties still visible in your 'Subscription Renewal' on the <?=$renewal_date_text;?> of the month will automatically renew to fulfil our obligations to your landlords.</p>

            <p>
                When you process the properties due for subscription renewal, IF we identify that a property needs to be serviced within the next 60 days (to meet legislative requirements) we will send the job straight to booking
                (previously this job was ‘on hold’ until the beginning of the following month).
            </p>
            
            <?php if (config_item('theme') === 'sats'): ?>
            <p>
                To view our Step-by-Step video on how to process properties that are due for subscription renewal, please click <a href='<?php echo $youtube_link ?>'>HERE</a>.
            </p>
            <?php endif; ?>
            <p>If you need any help or have questions, please contact our office on <?php echo $agent_number; ?> and speak with one of our friendly staff members.</p>

        <?php elseif($agency_auto_renew == 0 && $subscription_billing == 1 && $apis->active == 0): ?>

            <p>This is a courtesy to advise the below properties are due for subscription renewals here at <?=config_item('company_full_name');?> (<?=config_item('company_name_short');?>)</p>
            <p>Please note, because we have an active API connection with your Agency, our Team will adjust any updated tenant details required.</p>

            <p>We ask that you advise which properties are to continue <?=config_item('company_name_short');?> services and which are to be deactivated within our database (if required).</p>

            <br /><br />
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
                    $pm_name = ($pending_row->agency_user_account_id > 0) ? "{$pending_row->pm_fname} {$pending_row->pm_lname}" : $no_assigned_pm_txt;

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

            <p>
                Please note: We are under your strict instruction to NOT renew these properties as part of our Annual Maintenance program.
                If we are not granted permission to attend and complete works, <?=config_item('company_name_short');?> shall deem these properties non-compliant and accept no liability pertaining to their compliance.
            </p>
            <p>When you process the properties due for subscription renewal, and it has been identified that the property has not had a physical attendance completed for more than 12 months, we will generate a job to attend.</p>
            <p>Should you have any additional questions, please feel free to contact our friendly Customer Service Team on <?= config_item('customer_service'); ?> or please respond to this email directly.</p>

        <?php else: ?> <!-- Default -->

            <p>
                Please <a href='<?php echo $agency_portal_link; ?>'>log in</a>
                to our Agency Portal and go to the '<?php echo ($agency_auto_renew == 1 && $subscription_billing == 1 && $apis->active == 0) ? 'Due for Subscription Renewal' : 'Service Due' ?>' page to view properties that are now
                <?php if ($agency_auto_renew == 1 && $subscription_billing == 1 && $apis->active == 0): ?>
                    due for subscription renewal.
                <?php else: ?>
                    due for service.
                <?php endif; ?>
            </p>
            <p>If the tenant details are correct and you still manage the property, then mark the check box and 'click' CREATE JOB.</p>
            <p>If you no longer manage the property, then mark the checkbox and 'click' NO LONGER MANAGE. These changes can be done in multiple ways.</p>
            <!--<p style='color:red;'>Any properties that are still in 'Service Due' by the 15th of the month, will automatically be renewed to fulfill our obligations to your landlords.</p>-->
            <p style="color:red;">Any properties still visible in your 'Subscription Renewal' on the <?=$renewal_date_text;?> of the month will automatically renew to fulfil our obligations to your landlords.</p>

            <p>
                When you process the properties
                <?php if($agency_auto_renew == 1 && $subscription_billing == 1 && $apis->active == 0): ?>
                    due for subscription renewal,
                <?php else: ?>
                    due for service,
                <?php endif; ?>
                IF we identify that a property needs to be serviced within the next 60 days (to meet legislative requirements) we will send the job straight to booking
                (previously this job was ‘on hold’ until the beginning of the following month).
            </p>
            
            <?php if (config_item('theme') === 'sats'): ?>
            <p>
                To view our Step-by-Step video on how to process properties that are
                <?php if ($agency_auto_renew == 1 && $subscription_billing == 1 && $apis->active == 0): ?>
                    due for subscription renewal,
                <?php else: ?>
                    due for service,
                <?php endif; ?>
                please click <a href='<?php echo $youtube_link ?>'>HERE</a>.
            </p>
            <?php endif; ?>
            <p>If you need any help or have questions, please contact our office on <?php echo $agent_number; ?> and speak with one of our friendly staff members.</p>

        <?php endif; ?>

    <?php else: ?> <!-- Default -->

        <p>
            Please <a href='<?php echo $agency_portal_link; ?>'>log in</a>
            to our Agency Portal and go to the '<?php echo ($agency_auto_renew == 1 && $subscription_billing == 1 && $apis->active == 0) ? 'Due for Subscription Renewal' : 'Service Due' ?>' page to view properties that are now
            <?php if ($agency_auto_renew == 1 && $subscription_billing == 1 && $apis->active == 0): ?>
                due for subscription renewal.
            <?php else: ?>
                due for service.
            <?php endif; ?>
        </p>
        <p>If the tenant details are correct and you still manage the property, then mark the check box and 'click' CREATE JOB.</p>
        <p>If you no longer manage the property, then mark the checkbox and 'click' NO LONGER MANAGE. These changes can be done in multiple ways.</p>
        <!--<p style='color:red;'>Any properties that are still in 'Service Due' by the 15th of the month, will automatically be renewed to fulfill our obligations to your landlords.</p>-->
        <p style="color:red;">Any properties still visible in your 'Subscription Renewal' on the <?=$renewal_date_text;?> of the month will automatically renew to fulfil our obligations to your landlords.</p>

        <p>
            When you process the properties
            <?php if($agency_auto_renew == 1 && $subscription_billing == 1 && $apis->active == 0): ?>
                due for subscription renewal
            <?php else: ?>
                due for service,
            <?php endif; ?>
            IF we identify that a property needs to be serviced within the next 60 days (to meet legislative requirements) we will send the job straight to booking
            (previously this job was ‘on hold’ until the beginning of the following month).
        </p>
        
        <?php if (config_item('theme') === 'sats'): ?>
        <p>
            To view our Step-by-Step video on how to process properties that are
            <?php if ($agency_auto_renew == 1 && $subscription_billing == 1 && $apis->active == 0): ?>
                due for subscription renewal
            <?php else: ?>
                due for service,
            <?php endif; ?>
            please click <a href='<?php echo $youtube_link ?>'>HERE</a>.
        </p>
        <?php endif; ?>
        <p>If you need any help or have questions, please contact our office on <?php echo $agent_number; ?> and speak with one of our friendly staff members.</p>

    <?php endif; ?>

<?php endif; ?>

<br /><br />
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

<p>
    Kind Regards<br />
	<?=config_item('company_full_name');?>.
</p>	
<!-- CONTENT END HERE -->