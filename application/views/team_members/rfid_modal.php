<?php echo form_open(get_uri("hrs/team_members/update_rfid_num"), array("id" => "set-rfid-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" id="user_id" name="user_id" value="<?= $model_info->id ?>"/>
    <div role="tabpanel" class="tab-pane">
        <div class="form-group">
            <label for="name" class=" col-md-3"><?php echo lang('fullname'); ?></label>
            <div class=" col-md-9">
                <?php
                $fullname = $model_info->first_name . " " . $model_info->last_name;
                if(get_setting('name_format') == "lastfirst") {
                    $fullname = $model_info->last_name.", ".$model_info->first_name;
                }
                echo form_input(array(
                    "id" => "fullname",
                    "name" => "fullname",
                    "value" => $fullname,
                    "class" => "form-control",
                    "placeholder" => lang('fullname'),
                    "autofocus" => true,
                    "disabled" => true,
                    "data-rule-required" => true,
                    "data-msg-required" => lang("field_required"),
                ));
                ?>
            </div>
        </div>
        <div class="form-group">
            <label for="name" class=" col-md-3"><?php echo lang('rfid_num'); ?></label>
            <div class=" col-md-9">
                <?php
                echo form_input(array(
                    "id" => "rfid_num",
                    "name" => "rfid_num",
                    "value" => $model_info->rfid_num,
                    "class" => "form-control",
                    "placeholder" => lang('rfid_num'),
                    "autofocus" => true,
                    "data-rule-required" => true,
                    "data-msg-required" => lang("field_required"),
                ));
                ?>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <div class="alert alert-primary" role="alert">
        Scan the RFID sticker,card, or tags...
    </div>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">

    let text = '';

    $(document).ready(function () {
        $("#set-rfid-form").appForm({
            onAjaxSuccess: function (e) {
                if(e.success) {
                    location.reload();
                }
            }
        });

        //$("#rfid_num").focus();
        $("#ajaxModalTitle").text("Set user RFID Number");
    });

    $(document).on('keypress',function(e) {
        
        if(e.which == 13) {
            console.log('Submit to server!');
            $("#rfid_num").val(text);
            $("#set-rfid-form").submit();
            text = '';
        } else {
            switch(e.which) {
                case 48:
                    text += '0';
                    break;
                case 49:
                    text += '1';
                    break;
                case 50:
                    text += '2';
                    break;
                case 51:
                    text += '3';
                    break;
                case 52:
                    text += '4';
                    break;
                case 53:
                    text += '5';
                    break;
                case 54:
                    text += '6';
                    break;
                case 55:
                    text += '7';
                    break;
                case 56:
                    text += '8';
                    break;
                case 57:
                    text += '9';
                    break;
                default:
                    break;
            }
        }
    });

</script>