<div id="page-content" class="p20 clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "kiosk";
            $this->load->view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <?php echo form_open(get_uri("settings/save_kiosk_settings"), array("id" => "kiosk-settings-form", "class" => "general-form dashed-row", "role" => "form")); ?>
            <div class="panel">
                <div class="panel-default panel-heading">
                    <h4><?= lang("kiosk_config") ?></h4>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="since_last_break" class=" col-md-2"><?= lang("since_last_break")  ?></label>
                        <div class="col-md-4">
                            <?php
                                $since_last_break = get_setting('since_last_break', 30);
                                echo form_input(array(
                                    "id" => "since_last_break",
                                    "name" => "since_last_break",
                                    "value" => $since_last_break,
                                    "class" => "form-control",
                                    "placeholder" => lang('since_last_break'),
                                ));
                            ?>
                        </div>
                        <label for="since_last_clock_out" class=" col-md-2"><?= lang("since_last_clock_out")  ?></label>
                        <div class="col-md-4">
                            <?php
                                $since_last_clock_out = get_setting('since_last_clock_out', 300);
                                echo form_input(array(
                                    "id" => "since_last_clock_out",
                                    "name" => "since_last_clock_out",
                                    "value" => $since_last_clock_out,
                                    "class" => "form-control",
                                    "placeholder" => lang('since_last_clock_out'),
                                ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="enable_selected_user_access" class=" col-md-2"><?php echo lang('enable_selected_user_access'); ?></label>
                        <div class="col-md-2">
                            <?php
                            echo form_dropdown(
                                "enable_selected_user_access", array(
                                "0" => lang("no"),
                                "1" => lang("yes")
                                ), get_setting('enable_selected_user_access'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                        <div class="col-md-8">
                            <input type="text" value="<?php echo get_setting('whitelisted_selected_user_access') ?>" name="whitelisted_selected_user_access" id="team_members_dropdown_user_access" class="w100p validate-hidden"  placeholder="<?php echo lang('whitelisted'); ?>"  />    
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="breaktime_tracking" class=" col-md-2"><?php echo lang('breaktime_tracking'); ?></label>
                        <div class="col-md-2">
                            <?php
                            echo form_dropdown(
                                "breaktime_tracking", array(
                                "0" => lang("no"),
                                "1" => lang("yes")
                                ), get_setting('breaktime_tracking'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                        <div class="col-md-8">
                            <input type="text" value="<?php echo get_setting('whitelisted_breaktime_tracking') ?>" name="whitelisted_breaktime_tracking" id="team_members_dropdown_breaktime_tracking" class="w100p validate-hidden"  placeholder="<?php echo lang('whitelisted'); ?>"  />    
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="auto_clockout" class=" col-md-2"><?php echo lang('auto_clockout'); ?></label>
                        <div class="col-md-2">
                            <?php
                            echo form_dropdown(
                                "auto_clockout", array(
                                "0" => lang("no"),
                                "1" => lang("yes")
                                ), get_setting('auto_clockout'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                        <div class="col-md-2">
                            <?php
                                $autoclockout_trigger_hour = get_setting('autoclockout_trigger_hour', 12.00);
                                echo form_input(array(
                                    "id" => "autoclockout_trigger_hour",
                                    "name" => "autoclockout_trigger_hour",
                                    "value" => $autoclockout_trigger_hour,
                                    "class" => "form-control",
                                    "placeholder" => lang('trigger_hour'),
                                ));
                            ?>
                        </div>
                        <div class="col-md-6">
                            <input type="text" value="<?php echo get_setting('whitelisted_autoclockout') ?>" name="whitelisted_autoclockout" id="team_members_dropdown_autoclockout" class="w100p validate-hidden"  placeholder="<?php echo lang('whitelisted'); ?>"  />    
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="auto_clockin" class=" col-md-2"><?php echo lang('auto_clockin'); ?></label>
                        <div class="col-md-10">
                            <input type="text" value="<?php echo get_setting('auto_clockin_employee') ?>" name="auto_clockin_employee" id="team_members_dropdown_autoclockin" class="w100p validate-hidden"  placeholder="<?php echo lang('type_employee_name'); ?>"  />    
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
        $("#kiosk-settings-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                if (result.success) {
                    appAlert.success(result.message, {duration: 10000});
                } else {
                    appAlert.error(result.message);
                }
            }
        });

        $("#kiosk-settings-form .select2").select2();

        function clockoutTriggerRefresh() {
            let curVal = $("#autoclockout_trigger_hour").val();
            let newVal = parseFloat(curVal).toFixed(2);
            $("#autoclockout_trigger_hour").val(newVal);
        }
        clockoutTriggerRefresh();
        $("#autoclockout_trigger_hour").change(() => {
            clockoutTriggerRefresh();
        });

        $("#team_members_dropdown_user_access, #team_members_dropdown_breaktime_tracking, #team_members_dropdown_autoclockout, #team_members_dropdown_autoclockin").select2({
            multiple: true,
            data: <?php echo ($members_dropdown); ?>
        });
    });
</script>