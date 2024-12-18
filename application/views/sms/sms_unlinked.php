<div class="box-typical box-typical-padding">

    <?php
    // breadcrumbs template
    $bc_items = array(
        array(
            'title' => $title,
            'status' => 'active',
            'link' => "/sms/workorders_and_unlinked_sms"
        )
    );
    $bc_data['bc_items'] = $bc_items;
    $this->load->view('templates/breadcrumbs', $bc_data);
    ?>

    <header class="box-typical-header">
        <div class="box-typical box-typical-padding">
            <div class="form-row">
                <div class="col-md-8">
                    <form method=POST action="/sms/workorders_and_unlinked_sms" class="form-row">
                        
                        <!--
                        <div class="col-md-4">
                            <div class="row">
                              
                                <div class="col-md-12">
                                    <label class="phrase">Phrase</label>
                                    <input type="text" class="form-control" name="phrase" placeholder="Phrase" value="<?php echo $this->input->get_post('phrase'); ?>" />
                                </div>
                            </div>
                        </div>

                        <?php
                        // show on AU only
                        if( $this->config->item('country') == 1 ){ ?>

                            <div class="col-md-4">
                                <div class="row">
                                
                                    <div class="col-md-12">
                                        <label class="phrase">SMS Number Reference</label>
                                        <select name="sms_ref_id" id="sms_ref_id" class="form-control">
                                            <option value="">---</option>
                                            <?php
                                            $snr_sql = $this->db->query("
                                            SELECT 
                                                `sms_ref_id`,    
                                                `name`
                                            FROM `sms_number_reference`
                                            WHERE `active` = 1
                                            ");
                                            foreach( $snr_sql->result() as $snr_row ){ ?>
                                                <option value="<?php echo $snr_row->sms_ref_id; ?>" 
                                                    <?php echo ( $snr_row->sms_ref_id == $this->input->get_post('sms_ref_id') )?'selected':null; ?>
                                                >
                                                    <?php echo $snr_row->name; ?>
                                                </option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        <?php
                        }
                        ?>   
                        -->                     

                        <div class="col-md-4">     

                            <!--   
                            <div class="left">
                                <input type="hidden" name="search_flag" value="1" />
                                <label class="col-sm-12 form-control-label">&nbsp;</label>
                                <input class="btn btn-inline" type="submit" value="Search">
                            </div>
                           
                            <div class="left">
                                <label class="col-sm-12 form-control-label">&nbsp;</label>
                                <a href="/sms/workorders_and_unlinked_sms/<?Php echo ((int) $this->input->get('show_all') == 1) ? "" : "?show_all=1" ?>" >
                                    <button class="btn btn-inline" type="button" >
                                        <?php echo ($this->input->get('show_all') == 1) ? 'Unread Only' : 'Display ALL' ?>
                                    </button>
                                </a>
                            </div>
                            -->

                        </div>

                    </form>   
                </div>
                <div class="col-md-3">
                    <b>Dedicated SMS Number:</b> <?php if( config_item('theme') == 'sats' && config_item('country') == 1 ){ echo '0489 953 570'; } // AU only ?>
                </div>
                <div class="col-md-1">
                   
                </div>
            </div>
    </header>
    <section>
        <div class="body-typical-body">
            <div class="table-responsive">

                <table id="datatable" class="table table-hover main-table jmenu_table">

                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>From</th>
                            <th style="width:40%">Message</th>
                            <?php
                            if( $this->config->item('country') == 1 ){ // AU only ?>
                                <th>SMS Number Reference</th>
                            <?php
                            }
                            ?>       
                            <th>Unread</th>                     
                            <th>Unread</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach($list->result_array() as $row){ ?>
                        <tr>
                            <td><?php echo $this->system_model->formatDate($row['created_date'],"d/m/Y") ?></td>
                            <td><?php echo $this->system_model->formatDate($row['created_date'],"H:i:s") ?></td>
                            <td><?php echo $row['mobile']; ?></td>
                            <td><?php echo $row['response']; ?></td>                            
                            <?php
                            if( $this->config->item('country') == 1 ){ // AU only ?>
                                <td><?php echo $row['snr_name']; ?></td>
                            <?php
                            }
                            ?>  
                            <td><?php echo ( $row['unread'] == 1 )?'Yes':'No'; ?></td>  
                            <td>
                                <span class="checkbox">
                                    <input 
                                        type="checkbox" 
                                        id="sms_replies_chk<?php echo $row['sms_api_replies_id']; ?>" 
                                        class="sms_replies_chk" 
                                        <?php echo ( $row['unread'] == 1 )?'checked':null; ?>
                                    >
                                    <label for="sms_replies_chk<?php echo $row['sms_api_replies_id']; ?>" class="chk_lbl"></label>
                                </span>	
                                <input type="hidden" class="sar_id" value="<?php echo $row['sms_api_replies_id']; ?>" />
                            </td>                            
                        </tr>
                        <?php } ?>
                    </tbody>

                </table>				

            </div>

            <!--
            <nav id="pagi_links" aria-label="Page navigation example" style="text-align:center"><?php echo $pagination; ?></nav>
            <div id="pagi_count" class="pagi_count text-center"><?php echo $pagi_count; ?></div>
            -->


        </div>
    </section>


</div>
</div>




</div>

<!-- Fancybox START -->
<!-- ABOUT TEXT -->
<a href="javascript:;" id="about_page_fb_link" class="fb_trigger" data-fancybox data-src="#about_page_fb">Trigger the fancybox</a>							
<div id="about_page_fb" class="fancybox" style="display:none;" >

    <h4><?php echo $title; ?></h4>
    <p>
        This page shows all incoming SMS that is unlinked
    </p>
    <pre>
        <code><?php echo $last_query; ?></code>
    </pre>
</div>

<!-- Fancybox END -->

<style>
    .fancybox-content {
        width: 50%;
    }
    .temp_name_col {
        width: 30%;
    }
    .desc_col {
        width: 57%;
    }
    .tags_div button{
        margin-bottom: 5px;
        width: 84%;
    }
    .td_process_btn button {
        margin: 2px 0;
    }
    #datatable tr.odd {
        background-color: #fff !important;
    }
</style>
<script type="text/javascript">
    jQuery(document).ready(function () {

        <?php if ($this->session->flashdata('status') && $this->session->flashdata('status') == 'success') { ?>
            swal({
                title: "Success!",
                text: "<?php echo $this->session->flashdata('success_msg') ?>",
                type: "success",
                confirmButtonClass: "btn-success",
                showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                timer: <?php echo $this->config->item('timer') ?>
            });
        <?php } else if ($this->session->flashdata('status') && $this->session->flashdata('status') == 'error') { ?>
                swal({
                    title: "Error!",
                    text: "<?php echo $this->session->flashdata('error_msg') ?>",
                    type: "error",
                    confirmButtonClass: "btn-danger"
                });
        <?php } ?>

        jQuery(".sms_replies_chk").change(function () {

            var obj = jQuery(this);
            var chk_state = obj.prop("checked");
            var sar_id = obj.parents("tr:first").find(".sar_id").val();

            if (chk_state == true) {
                var unread = 1;
            } else {
                var unread = 0;
            }

            jQuery("#load-screen").show();
            jQuery.ajax({
                type: "POST",
                url: "/sms/toggle_sms_replies_action_ajax",
                data: {
                    sar_id: sar_id,
                    unread: unread
                }
            }).done(function (ret) {
                jQuery("#load-screen").hide();
                //window.location="/incoming_sms.php";
                //location.reload();
            });	


        });

    });
</script>