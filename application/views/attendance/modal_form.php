<?php echo form_open(get_uri("hrs/attendance/save"), array("id" => "attendance-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <input type="hidden" name="status" value="<?= $status ?>" />
    
    <div class="clearfix">
        <div class="form-group">
            <label for="applicant_id" class=" col-md-3"><?php echo lang('user'); ?></label>
            <div class=" col-md-9">
                <?php
                if (isset($team_members_info)) {
                    $image_url = get_avatar($team_members_info->image);
                    echo "<span class='avatar avatar-xs mr10'><img src='$image_url' alt=''></span>" . $team_members_info->first_name . " " . $team_members_info->last_name;
                    ?>
                    <input type="hidden" name="user_id" value="<?php echo $team_members_info->id; ?>" />
                    <?php
                } else {
                    echo form_dropdown("user_id", $team_members_dropdown, "", "class='select2 validate-hidden' id='attendance_user_id' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                }
                ?>
            </div>
        </div>

        <div class="form-group">
            <label for="log_type" class=" col-md-3"><?php echo lang('log_type'); ?></label>
            <div class="col-md-4">
                <?= form_dropdown(
                        "log_type", 
                        array("overtime"=>"Overtime","schedule"=>"Scheduled"), 
                        array($model_info->log_type==="overtime"?"Overtime":"Scheduled"), 
                        "class='select2' id='log_type'"
                ); ?>
            </div>
            <div id="schedule_display" class="col-md-5 col-sm-5">
                <div class="form-group">
                    <?php
                        echo form_dropdown("sched_id", $sched_dropdown, $model_info->sched_id, "class='select2 validate-hidden' id='sched_id' required'");
                    ?>
                </div>
            </div>
        </div>

    </div>

    <div class="clearfix">
        <label for="clocked_in" class=" col-md-4 col-12"><?php echo lang('clocked_in'); ?></label>
        <div class="col-md-4 col-6 form-group">
            <?php
            $in_time = (is_date_exists($model_info->in_time)) ? convert_date_utc_to_local($model_info->in_time) : "";

            if ($time_format_24_hours) {
                $in_time_value = $in_time ? date("H:i", strtotime($in_time)) : "";
            } else {
                $in_time_value = $in_time ? convert_time_to_12hours_format(date("H:i:s", strtotime($in_time))) : "";
            }

            echo form_input(array(
                "id" => "in_date",
                "name" => "in_date",
                "value" => $in_time ? date("Y-m-d", strtotime($in_time)) : "",
                "class" => "form-control",
                "placeholder" => lang('in_date'),
                "autocomplete" => "off",
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
        <div class=" col-md-4 col-sm-4  form-group">
            <?php
            echo form_input(array(
                "id" => "in_time",
                "name" => "in_time",
                "value" => $in_time_value,
                "class" => "form-control",
                "placeholder" => lang('in_time'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>

    <div class="clearfix" style="padding: 15px; margin: 10px 15px 25px; background-color: #f3f3f3;;">
    <?php foreach ($model_info->break_time as $index => $break_log) { ?>

        <div class="clearfix">
            <label for="break_1st_start" class=" col-md-4 col-12"><?= lang(($index+1)."-rank").' '.lang('break_log'); ?></label>
            <div class=" col-md-4 col-6 form-group">
                <?php
                $first_start = is_date_exists($break_log) ? convert_date_utc_to_local($break_log) : "";

                if ($time_format_24_hours) {
                    $first_start_time = $first_start ? date("H:i", strtotime($first_start)) : "";
                } else {
                    $first_start_time = $first_start ? convert_time_to_12hours_format(date("H:i:s", strtotime($first_start))) : "";
                }

                echo form_input(array(
                    "id" => ($index+1)."-date",
                    "name" => ($index+1)."-date",
                    "value" => $break_log ? convert_date_utc_to_local($break_log, "Y-m-d") : "",
                    "class" => "form-control break_date",
                    "style" => "background-color: #ffffff; border-color: #dbdbdb;",
                    "placeholder" => lang('date'),
                    "autocomplete" => "off",
                    "data-rule-greaterThanOrEqual" => "#in_date",
                    "data-msg-greaterThanOrEqual" => lang("end_date_must_be_equal_or_greater_than_start_date")
                ));
                ?>
            </div>
            <div class=" col-md-4 col-6 form-group">
                <?php
                echo form_input(array(
                    "id" => ($index+1)."-time",
                    "name" => ($index+1)."-time",
                    "value" => $first_start_time,
                    "class" => "form-control break_time",
                    "style" => "background-color: #ffffff; border-color: #dbdbdb;",
                    "placeholder" => lang('time'),
                ));
                ?>
            </div>
        </div>
        
    <?php } ?>
    </div>

    <div class="clearfix">
        <label for="clocked_out" class=" col-md-4 col-12"><?php echo lang('clocked_out'); ?></label>
        <div class=" col-md-4 col-6 form-group">
            <?php
            $out_time = is_date_exists($model_info->out_time) ? convert_date_utc_to_local($model_info->out_time) : "";

            if ($time_format_24_hours) {
                $out_time_value = $out_time ? date("H:i", strtotime($out_time)) : "";
            } else {
                $out_time_value = $out_time ? convert_time_to_12hours_format(date("H:i:s", strtotime($out_time))) : "";
            }

            echo form_input(array(
                "id" => "out_date",
                "name" => "out_date",
                "value" => $out_time ? date("Y-m-d", strtotime($out_time)) : "",
                "class" => "form-control",
                "placeholder" => lang('out_date'),
                "autocomplete" => "off",
                //"data-rule-required" => true,
                //"data-msg-required" => lang("field_required"),
                "data-rule-greaterThanOrEqual" => "#in_date",
                "data-msg-greaterThanOrEqual" => lang("end_date_must_be_equal_or_greater_than_start_date")
            ));
            ?>
        </div>
        <div class=" col-md-4 col-6 form-group">
            <?php
            echo form_input(array(
                "id" => "out_time",
                "name" => "out_time",
                "value" => $out_time_value,
                "class" => "form-control",
                "placeholder" => lang('out_time'),
                //"data-rule-required" => true,
                //"data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="note" class=" col-md-3"><?php echo lang('note'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "note",
                "name" => "note",
                "class" => "form-control",
                "placeholder" => lang('note'),
                "value" => $model_info->note,
                "data-rich-text-editor" => true
            ));
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
        $("#attendance-form").appForm({
            onSuccess: function (result) {
                $(".dataTable:visible").appTable({newData: result.data, dataId: result.id});
            }
        });
        if (!$("#attendance_user_id").length) {
            $("#attendance_user_id").select2();
        }
        setDatePicker("#in_date, #out_date, #1-date, #2-date, #3-date, #4-date, #5-date, #6-date, #7-date, #8-date");

        setTimePicker("#in_time, #out_time, #1-time, #2-time, #3-time, #4-time, #5-time, #6-time, #7-time, #8-time");

        $("#name").focus();

        function checkLogtype(log_type) {            
            if(log_type === 'overtime') {
                $("#schedule_display").hide();
            } else {
                $("#schedule_display").show();
            }
        }
        $("#log_type").val('<?= $model_info->log_type?$model_info->log_type:"overtime" ?>');
        checkLogtype('<?= $model_info->log_type?$model_info->log_type:"overtime" ?>');

        $("#log_type").on('change', function () {
            checkLogtype($("#log_type").val());
        });        

        $("#attendance-form .select2").select2();
    });
</script>