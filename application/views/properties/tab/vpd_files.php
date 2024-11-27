<style>
    .delfile, .caf_delete{font-size:20px;margin:0px;padding:0px;}
    .btn_del{
        font-size:20px;
    }
    td.td_del{margin-top:0px;margin-bottom:0px;width:100px;text-align:center;}
    .upload_form_div form input.upload_input{
        margin-top: 14px;
        padding-bottom: 10px;
        margin-right: 10px;
        float:left;
    }
    .upload_form_div{display:none;}
</style>
<div class="text-left files_div">
    <div class="row">
        <div class="col-md-12 columns">
                <section class="card card-blue-fill">
                    <header class="card-header">Property Files</header>
                    <div class="card-block">
                        <table class="table table-hover main-table table_agency_files table-no-border">
                            <thead>
                                <tr>
                                    <th> File Name</th>
                                    <th width="100px" class="text-center">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Old files -->
                               <?php
                               $property_files_old = $this->properties_model->getPropertyFiles($property_id);
                               foreach($property_files_old as $file) {
                                ?>
                                    <tr>
                                        <td><a target="_blank" href="<?=base_url()?>property_files/<?=$property_id?>/<?=$file?>"><?=$file; ?></a></td>
                                        <td class="td_del"><a class="delfile btn_del" data-toggle="tooltip" title="Delete" href="#" onclick="delete_vpd_files_old('<?=rawurlencode($file)?>',<?=$property_id?>)"><span class="fa fa-trash"></span></a></td>
                                    </tr>
                                <?php 
                                }
                                ?>
                                <!-- End Old files -->

                                <!-- New files -->
                                <?php
                                if ($property_files->num_rows()>0) {
                                    
                                    foreach($property_files->result() as $row){
                                ?>
                                    <tr>
                                        <td><a target="_blank" href="<?php echo $row->path ?>/<?php echo $row->filename ?>"><?php echo $row->filename; ?></a></td>
                                        <td class="td_del"><a class="delfile btn_del" data-toggle="tooltip" title="Delete" href="#" onclick="delete_vpd_files(<?php echo $row->property_id.','.$row->property_files_id; ?>)"><span class="fa fa-trash"></span></a></td>
                                    </tr>
                                <?php
                                    }
                                }
                                if (sizeof($property_files_old) == 0 && $property_files->num_rows() == 0) {
                                    echo "<tr><td colspan='2'>This Property Has No Uploaded Files. Upload One Below</td></tr>";
                                }
                                 ?>
                                <!-- End New files -->
                            </tbody>
                        </table>
                        <div class="vad_cta_box text-right"><button class="btn_add_upload btn">Add File</button></div>
                        <div class="upload_form_div right">
                            <form action="/properties/ajax_update_property" enctype="multipart/form-data" method="post">
                            <!-- <form action="/agency/vad_upload_agency_file" enctype="multipart/form-data" method="post"> -->
                                <input type="hidden" name="property_id" value="<?php echo $this->input->get_post('id') ?>">
                                <input type="hidden" name="property_update" value="upload_vpd_files">
                                <input type="file" id="fileupload" name="fileupload" class="submitbtnImg upload_input"> 						
                                <button style="float: left; margin-top: 5px;" class="addinput btn submitbtnImg eagdtbt btn_upload_now" id="btn_upload_now_agency_file" type="submit">Upload Now</button>
                            </form>
                        </div>
                    </div>
                </section>
        </div>
    </div>

</div>

<script>
    $('.btn_add_upload').on('click', function(){
        var kini = $(this);
        kini.parents('.vad_cta_box').next('.upload_form_div').toggle('slow');
    })

    function delete_vpd_files(property_id, property_files_id){
        if (confirm("Are you sure?")) {
            $('#load-screen').show();
            jQuery.ajax({
                type: "POST",
                url: "/properties/ajax_update_property",
                dataType: 'json',
                data: {
                    property_id: property_id,
                    property_files_id: property_files_id,
                    property_update: 'delete_vpd_files'
                    
                }
            }).done(function( ret ) {	
                $('#load-screen').hide();
                if(ret.status){
                    $('#load-screen').hide();
                    swal({
                        title:"Success!",
                        text: "Successfully Deleted",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonText: "OK",
                        closeOnConfirm: false,  
                        showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                        timer: <?php echo $this->config->item('timer') ?>
                    });
                    var full_url = "/properties/details/?id=<?php echo $property_id; ?>&tab=4";
                    setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                }
            }); 
        }
    }

    function delete_vpd_files_old(file, property_id){
        if (confirm("Are you sure?")) {
            $('#load-screen').show();
            jQuery.ajax({
                type: "POST",
                url: "/properties/ajax_update_property",
                dataType: 'json',
                data: {
                    property_id: property_id,
                    file: file,
                    property_update: 'delete_vpd_files_old'
                    
                }
            }).done(function( ret ) {	
                $('#load-screen').hide();
                if(ret.status){
                    $('#load-screen').hide();
                    swal({
                        title:"Success!",
                        text: "Successfully Deleted",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonText: "OK",
                        closeOnConfirm: false,  
                        showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                        timer: <?php echo $this->config->item('timer') ?>
                    });
                    var full_url = "/properties/details/?id=<?php echo $property_id; ?>&tab=4";
                    setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                }
            }); 
        }
    }
</script>