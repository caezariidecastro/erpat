<?php echo form_open(get_uri("hrs/team_members/update_user_access"), array("id" => "set-access-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" id="user_id" name="user_id" value="<?= $model_info->id ?>"/>
    <div role="tabpanel" class="tab-pane">
        <div class="form-group">
            <label for="access_erpat" class="col-md-2"><?php echo lang('access_erpat'); ?></label>
            <div class="col-md-10">
                <?php
                echo form_checkbox("access_erpat", "1", $model_info->access_erpat ? true : false, "id='access_erpat' class='ml15'");
                ?>
                <span id="disable-login-help-block" class="ml10 <?php echo $model_info->access_erpat ? "hide" : "" ?>"><i class="fa fa-warning text-warning"></i> <?php echo lang("disable_access_help_message"); ?></span>
            </div>
        </div>

        <div class="form-group">
            <label for="access_syntry" class="col-md-2"><?php echo lang('access_syntry'); ?></label>
            <div class="col-md-10">
                <?php
                echo form_checkbox("access_syntry", "1", $model_info->access_syntry ? true : false, "id='access_syntry' class='ml15'");
                ?>
                <span id="disable-login-help-block" class="ml10 <?php echo $model_info->access_syntry ? "hide" : "" ?>"><i class="fa fa-warning text-warning"></i> <?php echo lang("disable_access_help_message"); ?></span>
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
        $("#set-access-form").appForm({
            onSuccess: function (result) {
                if(result.success) {
                    appAlert.success(result.message);
                } else {
                    appAlert.error(result.message);
                }
            }
        });

        //$("#rfid_num").focus();
        $("#ajaxModalTitle").text("Update User Access");
    });

</script>