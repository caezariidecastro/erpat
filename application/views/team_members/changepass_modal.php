<?php echo form_open(get_uri("hrs/team_members/update_user_password"), array("id" => "reset-password-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" id="user_id" name="user_id" value="<?= $model_info->id ?>"/>
    <div role="tabpanel" class="tab-pane">
        <div class="form-group">
            <label for="new_password" class=" col-md-2"><?php echo lang('new_password'); ?></label>
            <div class=" col-md-8">
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
            <div class="col-md-1 p0">
                <a href="#" id="generate_password" class="btn btn-default" title="<?php echo lang('generate'); ?>"><span class="fa fa-refresh"></span></a>
            </div>
            <div class="col-md-1 p0">
                <a href="#" id="show_hide_password" class="btn btn-default" title="<?php echo lang('show_text'); ?>"><span class="fa fa-eye"></span></a>
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
    <button type="button" id="send_email" class="btn btn-warning"><span class="fa fa-email"></span> <?php echo lang('send_email'); ?></button>
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

        $("#send_email").click(function () {
            appLoader.show();
            $("#send_email").prop('disabled', true);
            $.ajax({
                url: "<?= get_uri("hrs/team_members/send_password_reset/".$model_info->id) ?>", 
                dataType: 'json',
                success: function(result){
                    if(result.success) {
                        appAlert.success(result.message);
                    } else {
                        appAlert.error(result.message);
                    }
                    appLoader.hide();
                    $("#send_email").prop('disabled', false);
                }, 
                function (request, status, error) {
                    appLoader.hide();
                    $("#send_email").prop('disabled', false);
                }
            })
        });

        $("#generate_password").click(function () {
            const gen_pass = getRndomString(8);
            $("#new_password").val( gen_pass );
            $("#confirm_password").val( gen_pass );
        });

        $("#show_hide_password").click(function () {
            var $target = $("#new_password"),
                    type = $target.attr("type");
            if (type === "password") {
                $(this).attr("title", "<?php echo lang("hide_text"); ?>");
                $(this).html("<span class='fa fa-eye-slash'></span>");
                $target.attr("type", "text");
            } else if (type === "text") {
                $(this).attr("title", "<?php echo lang("show_text"); ?>");
                $(this).html("<span class='fa fa-eye'></span>");
                $target.attr("type", "password");
            }

            var $target = $("#confirm_password"),
                    type = $target.attr("type");
            if (type === "password") {
                $(this).attr("title", "<?php echo lang("hide_text"); ?>");
                $(this).html("<span class='fa fa-eye-slash'></span>");
                $target.attr("type", "text");
            } else if (type === "text") {
                $(this).attr("title", "<?php echo lang("show_text"); ?>");
                $(this).html("<span class='fa fa-eye'></span>");
                $target.attr("type", "password");
            }
        });

        //$("#rfid_num").focus();
        $("#ajaxModalTitle").text("Reset Password");
    });

</script>