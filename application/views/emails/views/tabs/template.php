<?php
$edit_permission = false;
if($class_id == 2 || $class_id == 3 || $class_id == 9 || $class_id == 10){
    $edit_permission = true;
}
?>
<div class="box-typical-body mt-3">

    <div class="body-typical-body">
        <div class="table-responsive">
            <table id="datatable" class="table table-hover main-table">
                <thead>
                    <tr class="toprow jalign_left">				
                        <th>Template Name</th>
                        <th>Type</th>
                        <th>Subject</th>
                        <th>Call Centre</th>
                        <th>Active</th>	
                    </tr>
                </thead>
                <tbody>                
                    <?php if (!empty($templates)): ?>
                        <?php foreach ($templates as $et): ?>
                            <tr class="body_tr jalign_left">
                                <td><a href="/email/view_email_template/<?php echo $et['email_templates_id']; ?>"><?php echo $et['template_name']; ?></a></td>
                                <td><?php echo $et['ett_name'] ?></td>
                                <td><?php echo $et['subject']; ?></td>
                                <td class="<?php echo ($et['show_to_call_centre'] == 1) ? 'colorItGreen' : 'colorItRed'; ?>"><?php echo ($et['show_to_call_centre'] == 1) ? 'Yes' : 'No'; ?></td>
                                <td class="<?php echo ($et['et_active'] == 1) ? 'colorItGreen' : 'colorItRed'; ?>"><?php echo ($et['et_active'] == 1) ? 'Yes' : 'No'; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="100%" align="left">Empty</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php if($edit_permission){ ?>
    <a class="addBtn" href="/email/view_email_template"><button type="button" class="btn">Add New</button></a>
<?php } 