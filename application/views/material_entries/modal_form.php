<?php echo form_open(get_uri("material_entries/save"), array("id" => "material-entries-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info ? $model_info->id : "" ?>" />
    <div class="form-group">
        <label for="category" class="col-md-3"><?php echo lang('category'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("category", $category_dropdown, $model_info ? $model_info->category : "", "class='select2 validate-hidden' id='category' data-rule-required='true' data-msg-required='".lang("field_required")."'");
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
                "value" => $model_info ? $model_info->cost_price : "",
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
        <label for="vendor" class="col-md-3"><?php echo lang('vendor'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("vendor", $vendor_dropdown, $model_info ? $model_info->vendor : "", "class='select2 validate-hidden' id='user' data-rule-required='true' data-msg-required='".lang("field_required")."'");
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
        $("#material-entries-form").appForm({
            onSuccess: function (result) {
                $("#material-entries-table").appTable({newData: result.data, dataId: result.id});
            }
        });

        $('.select2').select2();
    });
    
</script>