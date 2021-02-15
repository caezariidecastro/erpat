<?php echo form_open(get_uri("asset_vendors/save_contact"), array("id" => "asset-vendor-contacts-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info ? $model_info->id : "" ?>" />
    <input type="hidden" name="asset_vendor_id" value="<?php echo $asset_vendor_id?>" />
    <div class="form-group">
        <label for="first_name" class="col-md-3"><?php echo lang('first_name'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "first_name",
                "name" => "first_name",
                "value" => $model_info ? $model_info->first_name : "",
                "class" => "form-control",
                "placeholder" => lang('first_name'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="last_name" class="col-md-3"><?php echo lang('last_name'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "last_name",
                "name" => "last_name",
                "value" => $model_info ? $model_info->last_name : "",
                "class" => "form-control",
                "placeholder" => lang('last_name'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="email" class="col-md-3"><?php echo lang('email'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "email",
                "name" => "email",
                "value" => $model_info ? $model_info->email : "",
                "class" => "form-control",
                "placeholder" => lang('email'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="phone" class="col-md-3"><?php echo lang('phone'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "phone",
                "name" => "phone",
                "value" => $model_info ? $model_info->phone : "",
                "class" => "form-control",
                "placeholder" => lang('phone'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="job_title" class="col-md-3"><?php echo lang('job_title'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "job_title",
                "name" => "job_title",
                "value" => $model_info ? $model_info->job_title : "",
                "class" => "form-control",
                "placeholder" => lang('job_title'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="gender" class="col-md-3"><?php echo lang('gender'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_radio(array(
                "id" => "gender_male",
                "name" => "gender",
                    ), "male", $model_info->gender ? ($model_info->gender  == "male" ? true : false) : true);
            ?>
            <label for="gender_male" class="mr15"><?php echo lang('male'); ?></label> <?php
            echo form_radio(array(
                "id" => "gender_female",
                "name" => "gender",
                    ), "female", ($model_info->gender == "female") ? true : false);
            ?>
            <label for="gender_female" class=""><?php echo lang('female'); ?></label>
        </div>
    </div>
    <?php
        if (!$model_info->id) {
    ?>
    <div class="form-group">
        <label for="password" class="col-md-3"><?php echo lang('password'); ?></label>
        <div class=" col-md-8">
            <div class="input-group">
                <?php
                $password_field = array(
                    "id" => "password",
                    "name" => "password",
                    "class" => "form-control validate-hidden",
                    "placeholder" => lang('password'),
                    "readonly" => "readonly",
                    "onfocus" => "this.removeAttribute('readonly');",
                    "style" => "z-index:auto;"
                );
                $password_field["data-rule-required"] = true;
                $password_field["data-msg-required"] = lang("field_required");
                $password_field["data-rule-minlength"] = 6;
                $password_field["data-msg-minlength"] = lang("enter_minimum_6_characters");
                echo form_password($password_field);
                ?>
                <label for="password" class="input-group-addon clickable" id="generate_password"><span class="fa fa-key"></span> <?php echo lang('generate'); ?></label>
            </div>
        </div>
        <div class="col-md-1 p0">
            <a href="#" id="show_hide_password" class="btn btn-default" title="<?php echo lang('show_text'); ?>"><span class="fa fa-eye"></span></a>
        </div>
    </div>
    <div class="form-group ">
        <label for="email_login_details"  class="col-md-3"><?php echo lang('email_login_details')."?"; ?></label>
        <div class="col-md-9">  
            <?php
            echo form_checkbox("email_login_details", "1", true, "id='email_login_details'");
            ?> 
        </div>
    </div>
    <?php
        }
    ?>
    <div class="form-group ">
        <label for="is_primary_contact"  class="col-md-3"><?php echo lang('primary_contact')."?"; ?></label>
        <div class="col-md-9">
            <?php
            echo form_checkbox("is_primary_contact", "1", $model_info->is_primary_contact, "id='is_primary_contact'");
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
        $("#asset-vendor-contacts-form").appForm({
            onSuccess: function (result) {
                $("#asset-vendor-contacts-table").appTable({newData: result.data, dataId: result.id});
            }
        });

        $("#password").val(getRndomString(8));

        $("#generate_password").click(function () {
            $("#password").val(getRndomString(8));
        });

        $("#show_hide_password").click(function () {
            var $target = $("#password"),
                    type = $target.attr("type");
            if (type === "password") {
                $(this).attr("title", "<?php echo lang("hide_text"); ?>");
                $(this).html("<span class='fa fa-eye'></span>");
                $target.attr("type", "text");
            } else if (type === "text") {
                $(this).attr("title", "<?php echo lang("show_text"); ?>");
                $(this).html("<span class='fa fa-eye-slash'></span>");
                $target.attr("type", "password");
            }
        });
    });
</script>