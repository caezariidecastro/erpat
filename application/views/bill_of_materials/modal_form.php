<?php echo form_open(get_uri("bill_of_materials/save"), array("id" => "bill-of-materials-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info ? $model_info->id : "" ?>" />

    <div class="form-group">
        <label for="title" class=" col-md-3"><?php echo lang('title'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "title",
                "name" => "title",
                "value" => $model_info ? $model_info->title : "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('title'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="description" class="col-md-3"><?php echo lang('description'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "description",
                "name" => "description",
                "value" => $model_info ? $model_info->description : "",
                "class" => "form-control",
                "placeholder" => lang('description'),
                "data-rich-text-editor" => true
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="warehouse" class="col-md-3"><?php echo lang('warehouse'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("warehouse", $warehouse_dropdown, "", "class='select2 validate-hidden' id='warehouse' data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="inventory_id" class=" col-md-3"><?php echo lang('item'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "inventory_id",
                "name" => "inventory_id",
                "value" => $model_info ? $model_info->inventory_id : "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('inventory_id'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
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
                "placeholder" => lang('quantity'),
                "autofocus" => true,
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
        $("#bill-of-materials-form").appForm({
            onSuccess: function (result) {
                $("#bill-of-materials-table").appTable({newData: result.data, dataId: result.id});
            }
        });

        $('#inventory_id').select2({data: <?= json_encode(array(array('id' => '', 'text' => '-')))?>});

        $('#warehouse').select2().change(function(e){
            if(e.val){
                getWarehouseInventory(e.val);
            }
        });

        let isUpdate = "<?= isset($model_info->warehouse) ? $model_info->warehouse : ""?>";
        if(isUpdate){
            $('#warehouse').select2("val", isUpdate);
            getWarehouseInventory(isUpdate);
        }
    });

    function getWarehouseInventory(id){
        appLoader.show({container: "#inventory_wrapper"});
                
        $.ajax({
            url: "<?php echo get_uri("inventory/get_warehouse_inventory_select2_data"); ?>",
            data: {id: id},
            cache: false,
            type: 'POST',
            dataType: "json",
            success: function (response) {
                $('#inventory_id').select2({data: response});
                appLoader.hide();
            }
        });
    }
</script>