<?php echo form_open(get_uri("settings/settings_save_account"), array("id" => "system-account-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="form-group">
        <label for="first_name" class=" col-md-4"><?php echo lang('first_name'); ?></label>
        <div class=" col-md-8">
            <?php
            echo form_input(array(
                "id" => "first_name",
                "name" => "first_name",
                "value" => $model_info->first_name,
                "class" => "form-control",
                "placeholder" => lang('first_name'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="last_name" class=" col-md-4"><?php echo lang('last_name'); ?></label>
        <div class=" col-md-8">
            <?php
            echo form_input(array(
                "id" => "last_name",
                "name" => "last_name",
                "value" => $model_info->last_name,
                "class" => "form-control",
                "placeholder" => lang('last_name'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="email" class=" col-md-4"><?php echo lang('email'); ?></label>
        <div class=" col-md-6">
            <?php
            echo form_input(array(
                "id" => "email",
                "name" => "email",
                "class" => "form-control",
                "placeholder" => lang('email'),
                "autofocus" => true,
                "autocomplete" => "off",
                "data-rule-email" => true,
                "data-msg-email" => lang("enter_valid_email"),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="new_pass" class=" col-md-4"><?php echo lang('new_pass'); ?></label>
        <div class=" col-md-8">
            <?php
            echo form_input(array(
                "id" => "new_pass",
                "name" => "new_pass",
                "type" => "password",
                "class" => "form-control",
                "placeholder" => lang('new_pass'),
                "autofocus" => true,
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="confirm_pass" class=" col-md-4"><?php echo lang('confirm_pass'); ?></label>
        <div class=" col-md-8">
            <?php
            echo form_input(array(
                "id" => "confirm_pass",
                "name" => "confirm_pass",
                "type" => "password",
                "class" => "form-control",
                "placeholder" => lang('confirm_pass'),
                "autofocus" => true,
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="access_syntry" class="col-md-4"><?php echo lang('access_syntry'); ?>
            <span class="help" data-toggle="tooltip" title="<?php echo lang('access_syntry_help_text'); ?>"><i class="fa fa-question-circle"></i></span>
        </label>
        <div class="col-md-8">
            <?php
            echo form_checkbox("access_syntry", "1", $model_info->id ? ($model_info->access_syntry ? 'checked' : '') : 'checked', "id='access_syntry'");
            ?> 
            Biometric App
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function() {
        $("#system-account-form").appForm({
            onSuccess: function(result) {
                if(result.success) {
                    $("#system-account-table").appTable({newData: result.data, dataId: result.id});
                } else {
                    appAlert.error(result.message, {duration: 3000})
                }
            }
        });
        $("#title").focus();
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>    