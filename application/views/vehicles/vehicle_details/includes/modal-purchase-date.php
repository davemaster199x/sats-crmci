<div class="fancybox-form-container" id="fancybox-purchase-date" style="display:none;">
    <form class="fancybox-form" method="post" data-tables='<?= json_encode(["vehicles" => ["_idValue" => $vehicle->vehicles_id, "_idField" => "vehicles_id"]]) ?>'> 
        <section class="card card-blue-fill">
            <header class="card-header">Purchase Date</header>
            <div class="card-body">
                <div class="card-block">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-control-label">Purchase Date</label>
                                <input type="text" class="form-control flatpickr flatpickr-input date-field editable-field" data-table="vehicles" data-field="purchase_date" value="<?php echo ($this->system_model->isDateNotEmpty($vehicle->purchase_dat)) ? $this->system_model->formatDate($vehicle->purchase_date,'d/m/Y') : null;  ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary update-button">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </form>
</div>