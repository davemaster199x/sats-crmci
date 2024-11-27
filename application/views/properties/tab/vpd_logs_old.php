<style>
    .log_list_box table td, .log_list_box table th{
        padding:11px 30px 10px 7px;
    }
    .preferences_list_box .radio{
        margin-bottom: 0px;
    }
    div.checkbox{margin: 0px;}
   
    .ob_check_icon{
        font-size:20px;
    }
</style>

<div class="log_list_box">

    <div class="log_listing_old text-left">
                            
        <table class="table table-hover main-table table_log_listing_old table-sm">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Staff Member</th>
                    <th>Comments</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if(count($property_old_logs)!=0){
                    foreach( $property_old_logs->result() as $p_log){ 
                ?>
                        <tr>
                            <td><?php echo date('d/m/Y',strtotime($p_log->log_date)); ?></td>
                            <td><?php echo $p_log->event_type; ?></td>
                            <td><?php echo $p_log->FirstName." ".$p_log->LastName; ?></td>
                            <td><?php echo $p_log->event_details; ?></td>
                        </tr>
                <?php 
                    }
                }else{
                    echo "<tr><td colspan='5'>No Data</td></tr>";
                } 
                ?>
            </tbody>
        </table>
            <nav id="pagi_links" aria-label="Page navigation example" style="text-align:center"><?php echo $pagination; ?></nav>
            <div id="pagi_count" class="pagi_count text-center"><?php echo $pagi_count; ?></div>
            <p>&nbsp;</p>
    </div>

</div>

<script type="text/javascript">
</script>