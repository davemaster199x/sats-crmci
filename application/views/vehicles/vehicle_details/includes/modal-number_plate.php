<div class="fancybox-form-container" id="fancybox-number_plate" style="display:none;">
    <form class="fancybox-form" method="post" data-tables='<?= json_encode(["vehicles" => ["_idValue" => $vehicle->vehicles_id, "_idField" => "vehicles_id1"]]) ?>'>
        <section class="card card-blue-fill">
            <header class="card-header">Number Plate</header>
            <div class="card-body">
                <div class="card-block">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-control-label">Number Plate</label>
                                <input type="text" class="form-control editable-field" data-table="vehicles" data-field="number_plate" value="<?= $vehicle->number_plate ?>">
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
