<style>

</style>


<div class="box-typical box-typical-padding">

    <?php $this->load->view('templates/breadcrumbs', $bc_items); ?>

    <section>
        <div class="body-typical-body">
            <div class="table-responsive">
                <table class="table table-hover main-table" id="datatable">
                    <thead>
                    <tr>
                        <th>Agency Name</th>
                        <th><?php echo $agency_number_abbr; ?> Number</th>
                        <th>Phone</th>
                        <th>Contact</th>
                        <th>Sales Rep</th>
                        <th><?= $states_label; ?></th>
                        <th><?= $regions_label; ?></th>
                        <th>Last Contact</th>
                        <th>Activated Date</th>
                        <th class="text-center"><i class="font-icon font-icon-home text-green" style="font-size:20px;"></i></th>
                        <th class="text-center"><i class="font-icon font-icon-home" style="font-size:20px;"></i></th>
                    </tr>
                    </thead>
                    
                    <!-- I have to put these in the tfoot to get the total active properties and properties total -->
                    <tfoot>
                        <tr>
                            <td colspan="8">&nbsp;</td>
                            <td>Total:</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </section>

</div>

<!-- Fancybox Start -->
<a href="javascript:" id="about_page_fb_link" class="fb_trigger" data-fancybox data-src="#about_page_fb">Trigger the fancybox</a>
<div id="about_page_fb" class="fancybox" style="display:none;" >

</div>
<!-- Fancybox END -->


<script type="module">

    jQuery(document).ready(function() {
        // jQuery('.dtsp-panesContainer').show();


    });
</script>