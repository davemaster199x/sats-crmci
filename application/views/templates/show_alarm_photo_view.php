<div class="col-md-6">
    <section class="card card-blue-fill">
        <header class="card-header">Pic of Expiry Date</header>
        <div class="card-block">
            <?php if($agency_pref_row['sel_pref_val'] == 0 && $agency_pref_row['sel_pref_val'] != ""): ?>
                <img src="<?php echo $not_included_image_placeholder; ?>">
            <?php else: ?>
                <?php if($expiry_image_filename != ""): ?>
                    <a href="#" data-fancybox="" data-src="/images/alarm_images/<?php echo $expiry_image_filename; ?>"><img style="width:100%" src="/images/alarm_images/<?php echo $expiry_image_filename; ?>"></a>
                <?php else: ?>
                    <img style="width:100%" src="<?php echo $no_image_placeholder; ?>">
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </section>
    <div style="display: flex; justify-content: center;">
        <?php if($can_delete_photo): ?>
            <button class="btn btn-danger" style="margin-right: 5px;" onclick="remove_alarm_photo(<?php echo $alarm_id; ?>, <?php echo $agency_id; ?>, 'alarm_expire_image')">Delete</button>
        <?php else: ?>
            <button class="btn btn-danger disabled" style="margin-right: 5px; background-color: grey; border-color: grey;" title="You don't have delete permission" disabled>Delete</button>
        <?php endif; ?>
        <?php if($can_add_photo): ?>
            <label for="alarm_expire_image_<?php echo $alarm_id; ?>" class="btn btn-primary">
                Add/Replace
            </label>
            <input type="file" accept="image/jpeg, image/png" name="alarm_expire_image_<?php echo $alarm_id; ?>" id="alarm_expire_image_<?php echo $alarm_id; ?>" class="d-none hidden-file">
        <?php else: ?>
            <button class="btn btn-primary disabled" style="background-color: grey; border-color: grey;" title="You don't have Add/Replace permission" disabled>Add/Replace</button>
        <?php endif; ?>
    </div>
</div>
<div class="col-md-6">
    <section class="card card-blue-fill">
        <header class="card-header">Pic of Alarm Location</header>
        <div class="card-block">
            <?php if($agency_pref_row['sel_pref_val'] == 0 && $agency_pref_row['sel_pref_val'] != ""): ?>
                <img src="<?php echo $not_included_image_placeholder; ?>">
            <?php else: ?>
                <?php if($location_image_filename != ""): ?>
                    <a href="#" data-fancybox="" data-src="/images/alarm_images/<?php echo $location_image_filename; ?>"><img style="width:100%" src="/images/alarm_images/<?php echo $location_image_filename; ?>"></a>
                <?php else: ?>
                    <img style="width:100%" src="<?php echo $no_image_placeholder; ?>">
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </section>
    <div style="display: flex; justify-content: center;">
        <?php if($can_delete_photo): ?>
            <button class="btn btn-danger" style="margin-right: 5px;" onclick="remove_alarm_photo(<?php echo $alarm_id; ?>, <?php echo $agency_id; ?>, 'alarm_location_image')">Delete</button>
        <?php else: ?>
            <button class="btn btn-danger disabled" style="margin-right: 5px; background-color: grey; border-color: grey;" title="You don't have delete permission" disabled>Delete</button>
        <?php endif; ?>
        <?php if($can_add_photo): ?>
            <input type="hidden" id="alarm_id_<?php echo $alarm_id; ?>" value="<?php echo $alarm_id; ?>">
            <label for="alarm_location_image_<?php echo $alarm_id; ?>" class="btn btn-primary">
                Add/Replace
            </label>
            <input type="file" accept="image/jpeg, image/png" name="alarm_location_image_<?php echo $alarm_id; ?>" id="alarm_location_image_<?php echo $alarm_id; ?>" class="d-none hidden-file">
        <?php else: ?>
            <button class="btn btn-primary disabled" style="background-color: grey; border-color: grey;" title="You don't have Add/Replace permission" disabled>Add/Replace</button>
        <?php endif; ?>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $("#alarm_expire_image_<?=$alarm_id?>").on("change", function() {
            // Get the alarm ID from the file input ID
            var alarm_id = $(this).attr("id").split("_").pop();
            
            // Use AJAX to upload the file
            var formData = new FormData();
            formData.append("alarm_id", alarm_id);
            formData.append("alarm_expire_image_<?=$alarm_id?>", this.files[0]);
            formData.append("alarm_type_photo", 'alarm_expire_image_'+alarm_id);
            formData.append("field_name", 'expiry_image_filename');
            
            $.ajax({
                url: "/jobs/upload_alarm_images",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    show_alarm_photo(<?=$alarm_id?>,<?=$agency_id?>)
                },
                error: function(xhr, status, error) {
                    // Handle error
                    alert("Error uploading file: " + error);
                }
            });
        });

        $("#alarm_location_image_<?=$alarm_id?>").on("change", function() {
            // Get the alarm ID from the file input ID
            var alarm_id = $(this).attr("id").split("_").pop();
            
            // Use AJAX to upload the file
            var formData = new FormData();
            formData.append("alarm_id", alarm_id);
            formData.append("alarm_location_image_<?=$alarm_id?>", this.files[0]);
            formData.append("alarm_type_photo", 'alarm_location_image_'+alarm_id);
            formData.append("field_name", 'location_image_filename');
            
            $.ajax({
                url: "/jobs/upload_alarm_images",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    show_alarm_photo(<?=$alarm_id?>,<?=$agency_id?>)
                },
                error: function(xhr, status, error) {
                    // Handle error
                    alert("Error uploading file: " + error);
                }
            });
        });
    });

</script>
