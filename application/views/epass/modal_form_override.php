<?php echo form_open(get_uri("EventPass/add_override_epass"), array("id" => "epass-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="form-group">
        <label for="first_name" class=" col-md-3"><?php echo lang('first_name'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "first_name",
                "name" => "first_name",
                "value" => $model_info ? $model_info->first_name : "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('first_name'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="last_name" class=" col-md-3"><?php echo lang('last_name'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "last_name",
                "name" => "last_name",
                "value" => $model_info ? $model_info->last_name : "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('last_name'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="submit" class="btn btn-success"><span class="fa fa-check-circle "></span> <?php echo lang('save'); ?></button>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#epass-form").appForm({
            onSuccess: function (result) {
                $("#epass-table").appTable({newData: result.data, dataId: result.id});
            }
        });
    });
</script>    