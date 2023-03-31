<div id="page-content" class="p20 clearfix">
    <div class="row">

        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "general";
            $this->load->view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <?php echo form_open(get_uri("settings/save_general_settings"), array("id" => "general-settings-form", "class" => "general-form dashed-row", "role" => "form")); ?>
            <div class="panel">
                <div class="panel-default panel-heading">
                    <h4><?php echo lang("general_settings"); ?></h4>
                </div>
                <div class="panel-body post-dropzone">
                    <div class="form-group">
                        <label for="logo" class=" col-md-2"><?php echo lang('site_logo'); ?></label>
                        <div class=" col-md-10">
                            <div class="pull-left mr15">
                                <img id="site-logo-preview" src="<?php echo get_logo_url(); ?>" alt="..." />
                            </div>
                            <div class="pull-left file-upload btn btn-default btn-xs">
                                <span>...</span>
                                <input id="site_logo_file" class="cropbox-upload upload" name="site_logo_file" type="file" data-height="150" data-width="375" data-preview-container="#site-logo-preview" data-input-field="#site_logo" />
                            </div>
                            <input type="hidden" id="site_logo" name="site_logo" value=""  />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="favicon" class="col-md-2"><?php echo lang('favicon'); ?></label>
                        <div class="col-lg-10">
                            <div class="pull-left mr15">
                                <img id="favicon-preview" src="<?php echo get_favicon_url(); ?>" alt="..." />
                            </div>
                            <div class="pull-left file-upload btn btn-default btn-xs">
                                <span>...</span>
                                <input id="favicon_file" class="cropbox-upload upload" name="favicon_file" type="file" data-height="32" data-width="32" data-preview-container="#favicon-preview" data-input-field="#favicon" />
                            </div>
                            <input type="hidden" id="favicon" name="favicon" value="" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="show_logo_in_signin_page" class=" col-md-2"><?php echo lang('show_logo_in_signin_page'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_dropdown(
                                    "show_logo_in_signin_page", array(
                                "no" => lang("no"),
                                "yes" => lang("yes")
                                    ), get_setting('show_logo_in_signin_page'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="show_background_image_in_signin_page" class=" col-md-2"><?php echo lang('show_background_image_in_signin_page'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_dropdown(
                                    "show_background_image_in_signin_page", array(
                                "no" => lang("no"),
                                "yes" => lang("yes")
                                    ), get_setting('show_background_image_in_signin_page'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class=" col-md-2"><?php echo lang('signin_page_background'); ?></label>
                        <div class=" col-md-10">
                            <div class="pull-left mr15">
                                <img id="signin-background-preview" style="max-width: 100px; max-height: 80px;" src="<?php echo get_file_from_setting("signin_page_background"); ?>" alt="..." />
                            </div>
                            <div class="pull-left mr15">
                                <?php $this->load->view("includes/dropzone_preview"); ?>    
                            </div>
                            <div class="pull-left upload-file-button btn btn-default btn-xs">
                                <span>...</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="site_title" class=" col-md-2"><?php echo lang('site_title'); ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "site_title",
                                "name" => "site_title",
                                "value" => get_setting('site_title'),
                                "class" => "form-control",
                                "placeholder" => lang('site_title'),
                                "data-rule-required" => true,
                                "data-msg-required" => lang("field_required"),
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="site_admin_email" class=" col-md-2"><?php echo lang('site_admin_email'); ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "site_admin_email",
                                "name" => "site_admin_email",
                                "value" => get_setting('site_admin_email'),
                                "class" => "form-control",
                                "placeholder" => lang('site_admin_email'),
                                "data-rule-required" => true,
                                "data-msg-required" => lang("field_required"),
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="language" class=" col-md-2"><?php echo lang('language'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_dropdown(
                                    "language", $language_dropdown, get_setting('language'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="enable_training" class=" col-md-2"><?php echo lang('enable_training'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_dropdown(
                                    "enable_training", array(
                                        "no" => lang("no"),
                                        "yes" => lang("yes")
                                    ), get_setting('enable_training'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>

                </div>
                <div class="panel-footer">
                    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<?php $this->load->view("includes/cropbox"); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#general-settings-form .select2").select2();

        $("#general-settings-form").appForm({
            isModal: false,
            beforeAjaxSubmit: function (data) {
                $.each(data, function (index, obj) {
                    if (obj.name === "invoice_logo" || obj.name === "site_logo" || obj.name === "favicon") {
                        var image = replaceAll(":", "~", data[index]["value"]);
                        data[index]["value"] = image;
                    }
                });
            },
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
                if ($("#site_logo").val() || $("#invoice_logo").val() || $("#favicon").val() || result.reload_page) {
                    location.reload();
                }
            }
        });

        var uploadUrl = "<?php echo get_uri("settings/upload_file"); ?>";
        var validationUrl = "<?php echo get_uri("settings/validate_file"); ?>";

        var dropzone = attachDropzoneWithForm("#general-settings-form", uploadUrl, validationUrl, {maxFiles: 1});

        $(".cropbox-upload").change(function () {
            showCropBox(this);
        });

    });
</script>