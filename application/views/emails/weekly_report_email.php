<!-- CONTENT START HERE -->

<style>
    .img_hyperlink {
        position: relative;
        width: 30px;
        height: 30px;
        float: right;
        margin-right: 25px;
    }

</style>

<?php
// no assigned PM
$no_assigned_pm_txt = "<span style='font-style: italic;'>No Assigned Property Manager</span>"; 
?>
<p>Dear <?php echo $agency_name; ?>,</p>
<p>
    Please find the report below on jobs that are booked or recently completed.<br />
    Please email us if you have any enquiries.
</p>

<?php
if( $completed_sql->num_rows() > 0 ){ ?>
    <h4>Completed Jobs</h4>
    <table style="width:100%; border: 1px solid #efefef;">
    <tr>
        <td style="background-color: #404041; color: #ffffff; padding: 5px; width: 100px;"><b>Date</b></td>
        <td style="background-color: #404041; color: #ffffff; padding: 5px;"><b>Address</b></td>
        <td style="background-color: #404041; color: #ffffff; padding: 5px; width: 205px;"><b>Property Manager</b></td>
        <td style="background-color: #404041; color: #ffffff; padding: 5px; width: 50px;"><b>&nbsp;</b></td>
    </tr>
    <?php
    // get pending jobs
    $rowcount = 0;
    foreach( $completed_sql->result() as $completed_row ){
        // property address
        $paddress = "{$completed_row->p_address_1} {$completed_row->p_address_2}, {$completed_row->p_address_3}";

        // get property managers
        if( $completed_row->agency_user_account_id > 0 ){
            $pm_name = "{$completed_row->pm_fname} {$completed_row->pm_lname}";
        }else{
            $pm_name = $no_assigned_pm_txt;
        }  
    ?>
        <tr style="background-color:<?php echo ($rowcount % 2 == 0 ? null : "#efefef") ?>">
            <td style="padding: 5px; width: 100px;"><?php echo date('d/m/Y',strtotime($completed_row->jdate)); ?></td>
            <td style="padding: 5px;"><?php echo $paddress; ?></td>
            <td style="padding: 5px; width: 205px;"><?php echo $pm_name; ?></td>
            <td  style="padding: 5px; width: 205px;">
                <?php
                    $encrypt = rawurlencode(HashEncryption::encodeString($completed_row->id));
                    $baseUrl = $_SERVER["SERVER_NAME"];
                    if(isset($_SERVER['HTTPS'])){
                        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
                    } else{
                        $protocol = 'http';
                    }
                    $pdf_hyperlink = "{$protocol}://{$baseUrl}/pdf/certificates/{$encrypt}";
                    $img_url = "{$protocol}://{$baseUrl}/images/pdf.png";
                ?>
                <a href='<?php echo $pdf_hyperlink ?>'>
                    <img src="<?php echo $img_url ?>" alt="pdf logo" class="img_hyperlink"/>
                </a>
            </td>
        </tr>
    <?php
        $rowcount++;
    }
    ?>
    </table>
<?php
}
?>

<?php
if( $booked_sql->num_rows() > 0 ){ ?>
    <h4>Booked Jobs</h4>
    <table style="width:100%; border: 1px solid #efefef;">
    <tr>
        <td style="background-color: #404041; color: #ffffff; padding: 5px; width: 100px;"><b>Date</b></td>
        <td style="background-color: #404041; color: #ffffff; padding: 5px;"><b>Address</b></td>
        <td style="background-color: #404041; color: #ffffff; padding: 5px; width: 205px;"><b>Property Manager</b></td>
    </tr>
    <?php
    // get pending jobs
    $rowcount = 0;
    foreach( $booked_sql->result() as $booked_row ){

        // property address
        $paddress = "{$booked_row->p_address_1} {$booked_row->p_address_2}, {$booked_row->p_address_3}";
        
        // get property managers
        if( $booked_row->agency_user_account_id > 0 ){
            $pm_name = "{$booked_row->pm_fname} {$booked_row->pm_lname}";
        }else{
            $pm_name = $no_assigned_pm_txt;
        } 
    ?>
        <tr style="background-color:<?php echo ($rowcount % 2 == 0 ? null : "#efefef") ?>">
            <td style="padding: 5px; width: 100px;"><?php echo date('d/m/Y',strtotime($booked_row->jdate)); ?></td>
            <td style="padding: 5px;"><?php echo $paddress; ?></td>
            <td style="padding: 5px; width: 205px;"><?php echo $pm_name; ?></td>
        </tr>
    <?php
        $rowcount++;
    }
    ?>
    </table>
<?php
}
?>

<br /><br />

<p>
    Kind Regards<br />
	<?=config_item('company_full_name');?>.
</p>	
<!-- CONTENT END HERE -->