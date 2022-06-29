<div id="page-content" class="p20 clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "calendar";
            $this->load->view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <?php echo form_open(get_uri("settings/save_calendar_settings"), array("id" => "calendar-settings-form", "class" => "general-form dashed-row", "role" => "form")); ?>
            <div class="panel">
                <div class="panel-default panel-heading">
                <h4><?php echo lang("calendar") ." ". lang("settings"); ?></h4>
                </div>
                <div class="panel-body">
                    
                <div class="form-group">
                        <label for="timezone" class=" col-md-2"><?php echo lang('timezone'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_dropdown(
                                    "timezone", $timezone_dropdown, get_setting('timezone'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="date_format" class=" col-md-2"><?php echo lang('date_format'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_dropdown(
                                "date_format", array(
                                    "d-m-Y" => "d-m-Y",
                                    "m-d-Y" => "m-d-Y",
                                    "Y-m-d" => "Y-m-d",
                                    "d/m/Y" => "d/m/Y",
                                    "m/d/Y" => "m/d/Y",
                                    "Y/m/d" => "Y/m/d",
                                    "d.m.Y" => "d.m.Y",
                                    "m.d.Y" => "m.d.Y",
                                    "Y.m.d" => "Y.m.d",
                                    ), get_setting('date_format'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="time_format" class=" col-md-2"><?php echo lang('time_format'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_dropdown(
                                    "time_format", array(
                                "capital" => "12 AM",
                                "small" => "12 am",
                                "24_hours" => "24 hours"
                                    ), get_setting('time_format'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="first_day_of_week" class=" col-md-2"><?php echo lang('first_day_of_week'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_dropdown(
                                    "first_day_of_week", array(
                                "0" => lang("sunday"),
                                "1" => lang("monday"),
                                "2" => lang("tuesday"),
                                "3" => lang("wednesday"),
                                "4" => lang("thursday"),
                                "5" => lang("friday"),
                                "6" => lang("saturday")
                                    ), get_setting('first_day_of_week'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="weekends" class=" col-md-2"><?php echo lang('weekends'); ?></label>
                        <div class="col-md-10">
                            <?php
                            $days_dropdown = array(
                                array("id" => "0", "text" => lang("sunday")),
                                array("id" => "1", "text" => lang("monday")),
                                array("id" => "2", "text" => lang("tuesday")),
                                array("id" => "3", "text" => lang("wednesday")),
                                array("id" => "4", "text" => lang("thursday")),
                                array("id" => "5", "text" => lang("friday")),
                                array("id" => "6", "text" => lang("saturday")),
                            );

                            echo form_input(array(
                                "id" => "weekends",
                                "name" => "weekends",
                                "value" => get_setting("weekends"),
                                "class" => "form-control",
                                "placeholder" => lang('weekends')
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
        $("#calendar-settings-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
            }
        });

        $("#calendar-settings-form .select2").select2();

        $("#weekends").select2({
            multiple: true,
            data: <?php echo (json_encode($days_dropdown)); ?>
        });
    });
</script>