<?php echo form_open(get_uri("mes/ManufacturingOrders/save"), array("id" => "production-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info ? $model_info->id : "" ?>" />
    <div class="form-group">
        <div class="col-md-12 text-off"> <?php echo lang('add_bill_of_material_info'); ?></div>
    </div>
    <?php if($model_info->id){?>
    <div class="form-group">
        <label for="status" class="col-md-3"><?php echo lang('status'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("status", $statuses_dropdown,  $model_info ? $model_info->status : "", "class='select2 validate-hidden' id='status' data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>
        <script>
            $(document).ready(function () {
                $('#status').select2();
            });
        </script>
    </div>
    <?php }?>
    <div class="form-group">
        <label for="bill_of_material_id" class="col-md-3"><?php echo lang('bill_of_material'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("bill_of_material_id", $bill_of_material_dropdown, $model_info->bill_of_material_id, "class='select2 validate-hidden' id='bill_of_material_id' data-rule-required='true' data-msg-required='".lang("field_required")."'");
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
                "value" => $model_info ? $model_info->quantity : "",
                "class" => "form-control validate-hidden",
                "type" => "number",
                "placeholder" => lang('quantity'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="buffer" class=" col-md-3"><?php echo lang('buffer'); ?></label>
        <div class="col-md-6">
            <?php
            echo form_input(array(
                "id" => "buffer",
                "name" => "buffer",
                "value" => $model_info ? $model_info->buffer : "",
                "class" => "form-control validate-hidden",
                "type" => "number",
                "placeholder" => lang('buffer'),
            ));
            ?>
        </div>
        <div class="col-md-3">
            <?php
            echo form_input(array(
                "id" => "buffer_percentage",
                "name" => "buffer_percentage",
                "value" => "%",
                "class" => "form-control",
                "placeholder" => lang('buffer_percentage'),
                "disabled" => "disabled",
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="inventory_id" class=" col-md-3"><?php echo lang('warehouse'); ?></label>
        <div class="col-md-9" id="inventory_selection_wrapper">
            <?php
            echo form_input(array(
                "id" => "inventory_id",
                "name" => "inventory_id",
                "value" => $model_info ? $model_info->inventory_id : "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('inventory_id'),
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
    let isUpdate = "<?php echo $model_info->id?>";
    
    $(document).ready(function () {
        $("#production-form").appForm({
            onSuccess: function (result) {
                $("#production-table").appTable({newData: result.data, dataId: result.id});
            }
        });

        $('#bill_of_material_id').select2().change(function(e){
            if(e.val){
                getProductWarehouse(e.val);
            }
        });
        $('#inventory_id').select2({data: <?= json_encode(array(array("id" => "", "text" => "-")))?>});

        if(isUpdate){
            getProductWarehouse(<?php echo $model_info->bill_of_material_id?>)
        }
    });

    function getProductWarehouse(id){
        $('#inventory_id').select2("destroy");
        $("#inventory_id").hide();
        appLoader.show({container: "#inventory_selection_wrapper", css:"left: 7%; bottom: -30px;"});

        $.ajax({
            url: "<?php echo get_uri("mes/ProductInventory/get_production_product_warehouse_select2_data") ?>" + `?id=${id}`,
            dataType: "json",
            success: function (result) {
                $("#inventory_id").show().val("");
                $('#inventory_id').select2({data: result});
                appLoader.hide();

                if(isUpdate){
                    $("#inventory_id").select2("val", "<?php echo $model_info->inventory_id?>");
                }
            }
        });
    }
</script>