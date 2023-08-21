<div class="panel panel-default no-border clearfix mb0">

    <?php echo form_open(get_uri("settings/save_firebase_settings"), array("id" => "firebase-form", "class" => "general-form dashed-row", "role" => "form")); ?>

    <div class="panel-body">

        <div class="form-group">
            <label for="enable_firebase_integration" class="col-md-2 col-xs-8 col-sm-4"><?php echo lang('enable_push_notification'); ?></label>
            <div class="col-md-10 col-xs-4 col-sm-8">
                <?php
                echo form_checkbox("enable_firebase_integration", "1", get_setting("enable_firebase_integration") ? true : false, "id='enable_firebase_integration' class='ml15'");
                ?>
            </div>
        </div>

        <div id="firebase-details-area" class="<?php echo get_setting("enable_firebase_integration") ? "" : "hide" ?>">

            <div class="form-group">
                <label for="enable_chat_via_firebase" class="col-md-2 col-xs-8 col-sm-4"><?php echo lang('enable_chat_via_firebase'); ?></label>
                <div class="col-md-10 col-xs-4 col-sm-8">
                    <?php
                    echo form_checkbox("enable_chat_via_firebase", "1", get_setting("enable_chat_via_firebase") ? true : false, "id='enable_chat_via_firebase' class='ml15'");
                    ?>
                </div>
            </div>

            <div class="form-group">
                <label for="firebase_server_key" class=" col-md-2"><?php echo lang('server_key'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "firebase_server_key",
                        "name" => "firebase_server_key",
                        "value" => get_setting("firebase_server_key"),
                        "class" => "form-control",
                        "placeholder" => lang('firebase_server_key'),
                        "data-rule-required" => true,
                        "data-msg-required" => lang("field_required")
                    ));
                    ?>
                </div>
            </div>

        </div>

    </div>

    <div class="panel-footer">
        <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
        <?php if (get_setting("enable_firebase_integration") && get_setting("firebase_server_key") ) { ?>
            <button id="firebase-test-push-notification-btn" type="button" class="btn btn-info ml15"><span class="fa fa-bell-o"></span> <?php echo lang('test_push_notification'); ?></button>
        <?php } ?>
    </div>
    <?php echo form_close(); ?>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        $("#firebase-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                if (result.success) {
                    if ($("#enable_firebase_integration").is(":checked")) {
                        window.location.href = "<?php echo_uri("settings/integration/firebase"); ?>";
                    } else {
                        appAlert.success(result.message, {duration: 10000});
                    }
                }
            }
        });

        //show/hide push notification details area
        $("#enable_firebase_integration").click(function () {
            $("#firebase-test-push-notification-btn").addClass("hide");
            if ($(this).is(":checked")) {
                $("#firebase-details-area").removeClass("hide");
            } else {
                $("#firebase-details-area").addClass("hide");
            }
        });

        //show a demo push notification
        $("#firebase-test-push-notification-btn").click(function () {
            appLoader.show();
            $.ajax({
                url: '<?php echo_uri("firebase/trigger/cloud-messaging") ?>',
                type: "POST",
                data: {
                    title: 'ERPat System Push Notification',
                    body: 'This is a test notification!',
                    icon: 'https://businext.dev/files/system/default-stie-logo.png',
                    url: 'https://businext.dev/settings/integration/firebase',
                    extra: '{}'
                },
                dataType: "json",
                success: function (result) {
                    appLoader.hide();
                    if (result.success) {
                        appAlert.success('<?= lang('test_message_sent') ?>');
                    } else {
                        appAlert.error('<?= lang('test_message_fail') ?>');
                    }
                }
            });
        });

    });
</script>