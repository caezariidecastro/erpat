<?php echo form_open(get_uri("purchase_order_returns/save_material"), array("id" => "purchase-order-return-materials-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="purchase_order_return_id" value="<?php echo $purchase_order_return_id?>" />
    <input type="hidden" name="purchase_id" value="<?php echo $purchase_id?>" />
    <input type="hidden" name="id" value="<?php echo $model_info->id?>" />
    <div class="form-group">
        <label for="purchase_order_material_id" class="col-md-3"><?php echo lang('material'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("purchase_order_material_id", $purchase_order_materials_dropdown, $purchase_order_material_id, "class='select2 validate-hidden' id='purchase_order_material_id' data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="quantity" class="col-md-3"><?php echo lang('quantity'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "quantity",
                "name" => "quantity",
                "value" => $model_info->quantity,
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
        <label for="remarks" class="col-md-3"><?php echo lang('remarks'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "remarks",
                "name" => "remarks",
                "value" => $model_info->remarks,
                "class" => "form-control",
                "placeholder" => lang('remarks'),
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
        $("#purchase-order-return-materials-form").appForm({
            onSuccess: function (result) {
                $("#purchase-order-return-material-table").appTable({newData: result.data, dataId: result.id});
            }
        });

        $('#purchase_order_material_id').select2();
    });
    
</script>