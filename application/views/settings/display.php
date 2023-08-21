<div id="page-content" class="p20 clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "display";
            $this->load->view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <?php echo form_open(get_uri("settings/save_display_settings"), array("id" => "display-settings-form", "class" => "general-form dashed-row", "role" => "form")); ?>
            <div class="panel">
                <div class="panel-default panel-heading">
                    <h4><?php echo lang("display") ." ". lang("settings"); ?></h4>
                </div>
                <div class="panel-body">

                    <div class="form-group">
                        <label for="rows_per_page" class=" col-md-2"><?php echo lang('rows_per_page'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_dropdown(
                                    "rows_per_page", array(
                                "10" => "10",
                                "25" => "25",
                                "50" => "50",
                                "100" => "100",
                                    ), get_setting('rows_per_page'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="scrollbar" class=" col-md-2"><?php echo lang('scrollbar'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_dropdown(
                                    "scrollbar", array(
                                "jquery" => "jQuery",
                                "native" => "Native"
                                    ), get_setting('scrollbar'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>
                    <?php if (get_setting("disable_html_input")) { ?>
                        <!--flag the enable_rich_text_editor as disabled, when the disable_html_input is enabled-->
                        <input type="hidden" name="enable_rich_text_editor" value="no" />
                    <?php } else { ?>
                        <div class="form-group">
                            <label for="enable_rich_text_editor" class=" col-md-2"><?php echo lang('enable_rich_text_editor'); ?></label>
                            <div class="col-md-10">
                                <?php
                                echo form_dropdown(
                                        "enable_rich_text_editor", array(
                                    "0" => lang("no"),
                                    "1" => lang("yes")
                                        ), get_setting('enable_rich_text_editor'), "class='select2 mini'"
                                );
                                ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="name_format" class=" col-md-2"><?php echo lang('name_format'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_dropdown(
                                    "name_format", array(
                                "firstlast" => "Juan Dela Cruz",
                                "lastfirst" => "Dela Cruz, Juan"
                                    ), get_setting('name_format'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>
                    <?php /* ?><div class="form-group">
                        <label for="rtl" class=" col-md-2"><?php echo lang('rtl'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_dropdown(
                                    "rtl", array(
                                "0" => lang("no"),
                                "1" => lang("yes")
                                    ), get_setting('rtl'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div><?php */ ?>
                    <div class="form-group">
                        <label for="show_theme_color_changer" class=" col-md-2"><?php echo lang('show_theme_color_changer'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_dropdown(
                                    "show_theme_color_changer", array(
                                "no" => lang("no"),
                                "yes" => lang("yes")
                                    ), get_setting('show_theme_color_changer'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>
                    <div class="form-group color-plate" id="settings-color-plate">
                        <label for="default_theme_color" class="col-md-2"><?php echo lang('default_theme_color'); ?></label>
                        <div class="col-md-10">
                            <?php echo get_custom_theme_color_list(); ?>
                            <input id="default-theme-color" type="hidden" name="default_theme_color" value="<?php echo get_setting("default_theme_color"); ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="accepted_file_formats" class=" col-md-2"><?php echo lang('accepted_file_format'); ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "accepted_file_formats",
                                "name" => "accepted_file_formats",
                                "value" => get_setting('accepted_file_formats'),
                                "class" => "form-control",
                                "placeholder" => lang('comma_separated'),
                                "data-rule-required" => true,
                                "data-msg-required" => lang("field_required"),
                            ));
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

<script type="text/javascript">
    $(document).ready(function () {
        $("#display-settings-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
            }
        });

        var existingColor = "<?php echo get_setting("default_theme_color"); ?>";
        if (existingColor === "1d2632") {
            $("#settings-color-plate span:first-child").addClass("active");
        } else {
            $("#settings-color-plate").find("[data-color='" + existingColor + "']").addClass("active");
        }

        $("#settings-color-plate span").click(function () {
            $("#settings-color-plate span").removeClass("active");
            $(this).addClass("active");

            var color = $(this).attr("data-color");
            if (color) {
                $("#default-theme-color").val($(this).attr("data-color"));
            } else {
                $("#default-theme-color").val("1d2632");
            }
        });

        $("#display-settings-form .select2").select2();
    });
</script>