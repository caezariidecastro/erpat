<div class="form-group">
    <label for="operator" class="col-md-3"><?php echo lang('operator'); ?></label>
    <div class=" col-md-9">
        <?php
        echo form_input(array(
            "id" => "operator",
            "name" => "operator",
            "value" => isset($model_info) ? $model_info->operator : ($operator ? $operator : ""),
            "class" => "form-control",
            "placeholder" => lang('operator'),
            "data-rule-required" => true,
            "data-msg-required" => lang("field_required"),
        ));
        ?>
    </div>
</div>
<div class="form-group">
    <label for="base_unit" class="col-md-3"><?php echo lang('base_unit'); ?></label>
    <div class="col-md-9">
        <?php
        echo form_dropdown("base_unit", $units_dropdown, isset($model_info) ? $model_info->base_unit : ($base_unit ? $base_unit : ""), "class='select2 validate-hidden' id='base_unit' data-rule-required='true' data-msg-required='".lang("field_required")."'");
        ?>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#base_unit").select2();
    });
</script>