<?php echo form_open(get_uri("hrs/attendance/save"), array("id" => "attendance-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

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

        <label for="current_schedule" class=" col-md-3 col-sm-3"><?php echo lang('current_schedule'); ?></label>
        <div class="col-md-6 col-sm-6 form-group">
            <div class="form-group">
                <?php
                    echo form_dropdown("sched_id", $sched_dropdown, $model_info->sched_id, "class='select2 validate-hidden' id='sched_id' required'");
                ?>
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

    <div class="clearfix">
        <label for="break_1st_start" class=" col-md-4 col-12"><?php echo lang('break_1st_start'); ?></label>
        <div class=" col-md-4 col-6 form-group">
            <?php
            $first_start = is_date_exists($model_info->break_time[0]) ? convert_date_utc_to_local($model_info->break_time[0]) : "";

            if ($time_format_24_hours) {
                $first_start_time = $first_start ? date("H:i", strtotime($first_start)) : "";
            } else {
                $first_start_time = $first_start ? convert_time_to_12hours_format(date("H:i:s", strtotime($first_start))) : "";
            }

            echo form_input(array(
                "id" => "first_start",
                "name" => "first_start",
                "value" => $model_info->break_time[0] ? date("Y-m-d", strtotime($model_info->break_time[0])) : "",
                "class" => "form-control",
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
                "id" => "first_start_time",
                "name" => "first_start_time",
                "value" => $first_start_time,
                "class" => "form-control",
                "placeholder" => lang('time'),
            ));
            ?>
        </div>
    </div>
    <div class="clearfix">
        <label for="break_1st_end" class=" col-md-4 col-12"><?php echo lang('break_1st_end'); ?></label>
        <div class=" col-md-4 col-6 form-group">
            <?php
            $first_end = is_date_exists($model_info->break_time[1]) ? convert_date_utc_to_local($model_info->break_time[1]) : "";

            if ($time_format_24_hours) {
                $first_end_time = $first_end ? date("H:i", strtotime($first_end)) : "";
            } else {
                $first_end_time = $first_end ? convert_time_to_12hours_format(date("H:i:s", strtotime($first_end))) : "";
            }

            echo form_input(array(
                "id" => "first_end",
                "name" => "first_end",
                "value" => $first_end ? date("Y-m-d", strtotime($first_end)) : "",
                "class" => "form-control",
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
                "id" => "first_end_time",
                "name" => "first_end_time",
                "value" => $first_end_time,
                "class" => "form-control",
                "placeholder" => lang('time'),
            ));
            ?>
        </div>
    </div>

    <div class="clearfix">
        <label for="break_lunch_start" class=" col-md-4 col-12"><?php echo lang('break_lunch_start'); ?></label>
        <div class=" col-md-4 col-6 form-group">
            <?php
            $lunch_start = is_date_exists($model_info->break_time[2]) ? convert_date_utc_to_local($model_info->break_time[2]) : "";

            if ($time_format_24_hours) {
                $lunch_start_time = $lunch_start ? date("H:i", strtotime($lunch_start)) : "";
            } else {
                $lunch_start_time = $lunch_start ? convert_time_to_12hours_format(date("H:i:s", strtotime($lunch_start))) : "";
            }

            echo form_input(array(
                "id" => "lunch_start",
                "name" => "lunch_start",
                "value" => $lunch_start ? date("Y-m-d", strtotime($lunch_start)) : "",
                "class" => "form-control",
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
                "id" => "lunch_start_time",
                "name" => "lunch_start_time",
                "value" => $lunch_start_time,
                "class" => "form-control",
                "placeholder" => lang('time'),
            ));
            ?>
        </div>
    </div>
    <div class="clearfix">
        <label for="break_lunch_end" class=" col-md-4 col-12"><?php echo lang('break_lunch_end'); ?></label>
        <div class=" col-md-4 col-6 form-group">
            <?php
            $lunch_end = is_date_exists($model_info->break_time[3]) ? convert_date_utc_to_local($model_info->break_time[3]) : "";

            if ($time_format_24_hours) {
                $lunch_end_time = $lunch_end ? date("H:i", strtotime($lunch_end)) : "";
            } else {
                $lunch_end_time = $lunch_end ? convert_time_to_12hours_format(date("H:i:s", strtotime($lunch_end))) : "";
            }

            echo form_input(array(
                "id" => "lunch_end",
                "name" => "lunch_end",
                "value" => $lunch_end ? date("Y-m-d", strtotime($lunch_end)) : "",
                "class" => "form-control",
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
                "id" => "lunch_end_time",
                "name" => "lunch_end_time",
                "value" => $lunch_end_time,
                "class" => "form-control",
                "placeholder" => lang('time'),
            ));
            ?>
        </div>
    </div>

    <div class="clearfix">
        <label for="break_2nd_start" class=" col-md-4 col-12"><?php echo lang('break_2nd_start'); ?></label>
        <div class=" col-md-4 col-6 form-group">
            <?php
            $second_start = is_date_exists($model_info->break_time[4]) ? convert_date_utc_to_local($model_info->break_time[4]) : "";

            if ($time_format_24_hours) {
                $second_start_time = $second_start ? date("H:i", strtotime($second_start)) : "";
            } else {
                $second_start_time = $second_start ? convert_time_to_12hours_format(date("H:i:s", strtotime($second_start))) : "";
            }

            echo form_input(array(
                "id" => "second_start",
                "name" => "second_start",
                "value" => $second_start ? date("Y-m-d", strtotime($second_start)) : "",
                "class" => "form-control",
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
                "id" => "second_start_time",
                "name" => "second_start_time",
                "value" => $second_start_time,
                "class" => "form-control",
                "placeholder" => lang('time'),
            ));
            ?>
        </div>
    </div>
    <div class="clearfix">
        <label for="break_2nd_end" class=" col-md-4 col-12"><?php echo lang('break_2nd_end'); ?></label>
        <div class=" col-md-4 col-6 form-group">
            <?php
            $second_end = is_date_exists($model_info->break_time[5]) ? convert_date_utc_to_local($model_info->break_time[5]) : "";

            if ($time_format_24_hours) {
                $second_end_time = $second_end ? date("H:i", strtotime($second_end)) : "";
            } else {
                $second_end_time = $second_end ? convert_time_to_12hours_format(date("H:i:s", strtotime($second_end))) : "";
            }

            echo form_input(array(
                "id" => "second_end",
                "name" => "second_end",
                "value" => $second_end ? date("Y-m-d", strtotime($second_end)) : "",
                "class" => "form-control",
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
                "id" => "second_end_time",
                "name" => "second_end_time",
                "value" => $second_end_time,
                "class" => "form-control",
                "placeholder" => lang('time'),
            ));
            ?>
        </div>
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
        setDatePicker("#in_date, #first_start, #first_end, #lunch_start, #lunch_end, #second_start, #second_end, #out_date");

        setTimePicker("#in_time, #first_start_time, #first_end_time, #lunch_start_time, #lunch_end_time, #second_start_time, #second_end_time, #out_time");

        $("#name").focus();

        $("#attendance-form .select2").select2();
    });
</script>