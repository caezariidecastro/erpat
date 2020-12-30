<?php echo form_open(get_uri("accounts/save"), array("id" => "account-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info ? $model_info->id : "" ?>" />

    <div class="form-group">
        <label for="account_number" class=" col-md-3"><?php echo lang('account_name'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "account_name",
                "name" => "account_name",
                "value" => $model_info ? $model_info->name : "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('account_name'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="account_number" class=" col-md-3"><?php echo lang('account_number'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "account_number",
                "name" => "account_number",
                "value" => $model_info ? $model_info->number : "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('account_number'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="initial_balance" class=" col-md-3"><?php echo lang('initial_balance'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "initial_balance",
                "name" => "initial_balance",
                "value" => $model_info ? $model_info->initial_balance : "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('initial_balance'),
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
                "value" => $model_info ? $model_info->remarks : "",
                "class" => "form-control",
                "placeholder" => lang('remarks'),
                "data-rich-text-editor" => true
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
        setDatePicker("#date_from");
        setDatePicker("#date_to");

        $("#account-form").appForm({
            onSuccess: function (result) {
                $("#account-table").appTable({newData: result.data, dataId: result.id});
            }
        });
    });
</script>