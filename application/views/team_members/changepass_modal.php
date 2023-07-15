<?php echo form_open(get_uri("hrs/team_members/update_user_password"), array("id" => "reset-password-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" id="user_id" name="user_id" value="<?= $model_info->id ?>"/>
    <div role="tabpanel" class="tab-pane">
        <div class="form-group">
            <label for="new_password" class=" col-md-2"><?php echo lang('new_password'); ?></label>
            <div class=" col-md-10">
                <?php
                echo form_password(array(
                    "id" => "new_password",
                    "name" => "new_password",
                    "class" => "form-control",
                    "placeholder" => lang('new_password'),
                    "autocomplete" => "off",
                    "data-rule-minlength" => 6,
                    "data-msg-minlength" => lang("enter_minimum_6_characters"),
                ));
                ?>
            </div>
        </div>
        <div class="form-group">
            <label for="confirm_password" class=" col-md-2"><?php echo lang('confirm_password'); ?></label>
            <div class=" col-md-10">
                <?php
                echo form_password(array(
                    "id" => "confirm_password",
                    "name" => "confirm_password",
                    "class" => "form-control",
                    "placeholder" => lang('confirm_password'),
                    "autocomplete" => "off",
                    "data-rule-equalTo" => "#new_password",
                    "data-msg-equalTo" => lang("enter_same_value")
                ));
                ?>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">

    $(document).ready(function () {
        $("#reset-password-form").appForm({
            onSuccess: function (result) {
                if(result.success) {
                    appAlert.success(result.message);
                } else {
                    appAlert.error(result.message);
                }
            }
        });

        //$("#rfid_num").focus();
        $("#ajaxModalTitle").text("Reset Password");
    });

</script>