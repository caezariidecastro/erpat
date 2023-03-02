<?php echo form_open(get_uri("sales/ProductEntries/save"), array("id" => "inventory-item-entries-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info ? $model_info->id : "" ?>" />
    <?php if ($model_info->id) { ?>
        <div class="form-group">
            <div class="col-md-12 text-off"> <?php echo lang('item_editing_instruction'); ?></div>
        </div>
    <?php } ?>
    <div class="form-group">
        <label for="category" class="col-md-3"><?php echo lang('category'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("category", $category_dropdown, $model_info ? $model_info->category : "", "class='select2 validate-hidden' id='category' data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="brand" class="col-md-3"><?php echo lang('brand'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("brand", $brand_dropdown, $model_info ? $model_info->brand : "", "class='select2 validate-hidden' id='brand'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="name" class="col-md-3"><?php echo lang('name'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "name",
                "name" => "name",
                "value" => $model_info ? $model_info->name : "",
                "class" => "form-control",
                "placeholder" => lang('name'),
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
                "value" => $model_info->description ? $model_info->description : "",
                "class" => "form-control",
                "placeholder" => lang('description'),
                "data-rich-text-editor" => true
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="sku" class="col-md-3"><?php echo lang('sku'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "sku",
                "name" => "sku",
                "value" => $model_info ? $model_info->sku : "",
                "class" => "form-control",
                "placeholder" => lang('sku'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="vendor" class="col-md-3"><?php echo lang('unit'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("unit", $units_dropdown, $model_info ? $model_info->unit : "", "class='select2 validate-hidden' id='user' data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="cost_price" class="col-md-3"><?php echo lang('cost_price'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "cost_price",
                "name" => "cost_price",
                "value" => isset($model_info->cost_price) && (int)$model_info->cost_price > 0 ? $model_info->cost_price : "0",
                "class" => "form-control",
                "placeholder" => lang('cost_price'),
                "type" => "number",
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="selling_price" class="col-md-3"><?php echo lang('selling_price'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "selling_price",
                "name" => "selling_price",
                "value" => isset($model_info->selling_price) && (int)$model_info->selling_price > 0 ? $model_info->selling_price : "0",
                "class" => "form-control",
                "placeholder" => lang('selling_price'),
                "type" => "number",
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="vendor" class="col-md-3"><?php echo lang('supplier'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("vendor", $vendor_dropdown, $model_info ? $model_info->vendor : "", "class='select2 validate-hidden' id='user'");
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
        $("#inventory-item-entries-form").appForm({
            onSuccess: function (result) {
                $("#inventory-item-entries-table").appTable({newData: result.data, dataId: result.id});
            }
        });

        $('.select2').select2();

        $("#ajaxModal .modal-dialog").removeAttr("style");
    });
    
</script>