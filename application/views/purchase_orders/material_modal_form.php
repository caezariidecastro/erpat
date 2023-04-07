<?php echo form_open(get_uri("mes/PurchaseOrders/save_material"), array("id" => "purchase-order-material-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <input type="hidden" name="purchase_id" value="<?php echo $purchase_id; ?>" />
    <input type="hidden" name="vendor_id" value="<?php echo $vendor_id; ?>" />
    <input id="material_title" type="hidden" name="title" value="<?php echo $model_info->title; ?>" />
    <div class="form-group">
        <div class="col-md-12 text-off"> <?php echo lang('inactive_material_hidden'); ?></div>
    </div>
    <div class="form-group">
        <label for="material" class="col-md-3"><?php echo lang('material'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("material_id", $material_dropdown, $model_info ? $model_info->material_id : "", "class='select2 validate-hidden' id='material' data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="quantity" class=" col-md-3"><?php echo lang('quantity'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "quantity",
                "name" => "quantity",
                "value" => $model_info->quantity ? to_decimal_format($model_info->quantity) : "",
                "class" => "form-control",
                "placeholder" => lang('quantity'),
                "type" => "number",
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="unit_type" class=" col-md-3"><?php echo lang('unit_type'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "unit_type",
                "name" => "unit_type",
                "value" => $model_info->unit_type,
                "class" => "form-control",
                "placeholder" => lang('unit_type') . ' (Ex: hours, pc, etc.)'
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="rate" class=" col-md-3"><?php echo lang('amount'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "rate",
                "name" => "rate",
                "value" => $model_info->rate ? to_decimal_format($model_info->rate) : "",
                "class" => "form-control",
                "placeholder" => lang('amount'),
                "type" => "number",
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="material_inventory_id" class=" col-md-3"><?php echo lang('warehouse'); ?></label>
        <div class="col-md-9" id="warehouse-dropdown-section">
            <?php
            echo form_input(array(
                "id" => "material_inventory_id",
                "name" => "material_inventory_id",
                "value" => $model_info->material_inventory_id ? $model_info->material_inventory_id : "",
                "class" => "form-control",
                "placeholder" => lang('material_inventory_id'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#purchase-order-material-form").appForm({
            onSuccess: function (result) {
                $("#purchase-order-material-table").appTable({newData: result.data, dataId: result.id});
                $("#purchase_order_status_label").html(result.purchase_status);
            }
        });

        $("#material_inventory_id").select2({data: <?= json_encode($warehouse_dropdown)?>});

        $("#material").select2().change(function(){
            let material_id = $(this).val();

            if(material_id){
                $.ajax({
                    url: "<?php echo get_uri("sales/ProductEntries/get_item"); ?>",
                    data: {id: material_id, json: true},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {
                        $('#material_inventory_id').select2("destroy");
                        $("#material_inventory_id").hide();
                        appLoader.show({container: "#warehouse-dropdown-section"});

                        $.ajax({
                            url: "<?php echo get_uri("mes/PurchaseOrders/get_warehouses_select2_data") ?>",
                            data: {
                                material_id: material_id,
                                json: true
                            },
                            cache: false,
                            type: 'POST',
                            dataType: "json",
                            success: function (result) {
                                $("#material_inventory_id").show().val("");
                                $('#material_inventory_id').select2({data: result});
                                appLoader.hide();

                                if (response && response.success) {
                                    $("#unit_type").val(response.data.unit_abbreviation);
                                    $("#rate").val(response.data.cost_price);
                                    $("#material_title").val(response.data.name);
                                }
                            }
                        });
                    }
                });
            }
        });
    });
</script>