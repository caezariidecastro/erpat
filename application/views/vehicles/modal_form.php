<?php echo form_open(get_uri("vehicles/save"), array("id" => "vehicle-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info ? $model_info->id : "" ?>" />

    <div class="form-group">
        <label for="brand" class=" col-md-3"><?php echo lang('brand'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "brand",
                "name" => "brand",
                "value" => $model_info ? $model_info->brand : "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('brand'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="model" class="col-md-3"><?php echo lang('model'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "model",
                "name" => "model",
                "value" => $model_info ? $model_info->model : "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('model'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="year" class=" col-md-3"><?php echo lang('year'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "year",
                "name" => "year",
                "value" => $model_info ? $model_info->year : "",
                "class" => "form-control",
                "placeholder" => lang('year'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="color" class=" col-md-3"><?php echo lang('color'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "color",
                "name" => "color",
                "value" => $model_info ? $model_info->color : "",
                "class" => "form-control",
                "placeholder" => lang('color'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="transmission" class=" col-md-3"><?php echo lang('transmission'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "transmission",
                "name" => "transmission",
                "value" => $model_info ? $model_info->transmission : "",
                "class" => "form-control",
                "placeholder" => lang('transmission'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="no_of_wheels" class=" col-md-3"><?php echo lang('no_of_wheels'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "no_of_wheels",
                "name" => "no_of_wheels",
                "value" => $model_info ? $model_info->no_of_wheels : "",
                "class" => "form-control",
                "placeholder" => lang('no_of_wheels'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="plate_number" class=" col-md-3"><?php echo lang('plate_number'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "plate_number",
                "name" => "plate_number",
                "value" => $model_info ? $model_info->plate_number : "",
                "class" => "form-control",
                "placeholder" => lang('plate_number'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="max_cargo_weight" class=" col-md-3"><?php echo lang('max_cargo_weight'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "max_cargo_weight",
                "name" => "max_cargo_weight",
                "value" => $model_info ? $model_info->max_cargo_weight : "",
                "class" => "form-control",
                "placeholder" => lang('max_cargo_weight'),
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
        $("#vehicle-form").appForm({
            onSuccess: function (result) {
                $("#vehicle-table").appTable({newData: result.data, dataId: result.id});
            }
        });
    });
</script>