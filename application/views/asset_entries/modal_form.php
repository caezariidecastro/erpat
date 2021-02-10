<?php echo form_open(get_uri("asset_entries/save"), array("id" => "entry-form", "class" => "general-form", "role" => "form")); ?>
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
        <label for="cost" class=" col-md-3"><?php echo lang('cost'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "cost",
                "name" => "cost",
                "value" => $model_info ? $model_info->cost : "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('cost'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="serial_number" class=" col-md-3"><?php echo lang('serial_number'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "serial_number",
                "name" => "serial_number",
                "value" => $model_info ? $model_info->serial_number : "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('serial_number'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="model" class=" col-md-3"><?php echo lang('model'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "model",
                "name" => "model",
                "value" => $model_info ? $model_info->model : "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('model'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="purchase_date" class=" col-md-3"><?php echo lang('purchase_date'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "purchase_date",
                "name" => "purchase_date",
                "value" => $model_info ? $model_info->purchase_date : "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('purchase_date'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="warranty_expiry_date" class=" col-md-3"><?php echo lang('warranty_expiry_date'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "warranty_expiry_date",
                "name" => "warranty_expiry_date",
                "value" => $model_info ? $model_info->warranty_expiry_date : "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('warranty_expiry_date'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="type" class="col-md-3"><?php echo lang('type'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("type", $type_dropdown, $model_info ? $model_info->type : "", "class='select2 validate-hidden' data-rule-required='true' data-msg-required='".lang("field_required")."' id='type'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="brand_id" class="col-md-3"><?php echo lang('brand'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("brand_id", $brand_dropdown, $model_info ? $model_info->brand_id : "", "class='select2 validate-hidden' data-rule-required='true' data-msg-required='".lang("field_required")."' id='brand_id'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="vendor_id" class="col-md-3"><?php echo lang('vendor'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("vendor_id", $vendor_dropdown, $model_info ? $model_info->vendor_id : "", "class='select2 validate-hidden' data-rule-required='true' data-msg-required='".lang("field_required")."' id='vendor_id'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="category_id" class="col-md-3"><?php echo lang('category'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("category_id", $category_dropdown, $model_info ? $model_info->category_id : "", "class='select2 validate-hidden' data-rule-required='true' data-msg-required='".lang("field_required")."' id='category_id'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="location_id" class="col-md-3"><?php echo lang('location'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("location_id", $location_dropdown, $model_info ? $model_info->location_id : "", "class='select2 validate-hidden' data-rule-required='true' data-msg-required='".lang("field_required")."' id='location_id'");
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
        setDatePicker("#purchase_date");
        setDatePicker("#warranty_expiry_date");

        $("#entry-form").appForm({
            onSuccess: function (result) {
                $("#asset-entry-table").appTable({newData: result.data, dataId: result.id});
            }
        });

        $(".select2").select2();
    });
</script>