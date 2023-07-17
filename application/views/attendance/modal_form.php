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
            <label for="break_1st_start" class=" col-md-3 col-12"><?= lang(($index+1)."-rank").' '.lang('break_log'); ?></label>
            <div class=" col-md-4 col-12 form-group">
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
            <div class="col-md-3 col-12 form-group">
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
            <div class=" col-md-2 col-12 form-group" style="margin: 8px auto 0;">
                <label id="<?= $index+1 ?>-span"></label>
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
        <label class="col-md-3"><?php echo lang('duration'); ?></label>
        <label id="duration" class="col-md-3">-</label>
        <label class="col-md-3"><?php echo lang('total_break'); ?>-</label>
        <label id="total_break" class="col-md-3">-</label>
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

        <?php foreach ([1,3,5,7] as $index) { $succeding = $index+1;?>

        function part_<?= $index.$succeding ?>_break() {
            if( $('#<?= $index ?>-date').val() != "" && $('#<?= $index ?>-time').val() != "" && $('#<?= $succeding ?>-date').val() != "" && $('#<?= $succeding ?>-time').val() != "" ) {
                let tspan = "0h 0m";

                const start = new moment($('#<?= $index ?>-date').val() + ' ' + $('#<?= $index ?>-time').val());
                const end = new moment($('#<?= $succeding ?>-date').val() + ' ' + $('#<?= $succeding ?>-time').val());

                tspan = end.diff(start)/1000;
                
                let hours = '';
                if(tspan >= 3600) {
                    hours = parseInt(tspan/60/60) + 'h ';
                }
                let minutes = '';
                if(tspan > 60) {
                    minutes = (parseInt(tspan/60) % 60) + 'm';
                }

                if(hours || minutes) {
                    $('#<?= $index ?>-span').html( 'DURATION' );
                    $('#<?= $succeding ?>-span').html( hours + minutes );
                } else {
                    $('#<?= $index ?>-span').html( '-' );
                    $('#<?= $succeding ?>-span').html( '-' );
                }
            } else {
                $('#<?= $index ?>-span').html( '-' );
                $('#<?= $succeding ?>-span').html( '-' );
            }

            work_duration();
        } part_<?= $index.$succeding ?>_break();

        $('#<?= $index ?>-date, #<?= $index ?>-time, #<?= $succeding ?>-date, #<?= $succeding ?>-time').on('change', () => {
            part_<?= $index.$succeding ?>_break();
        });

        <?php } ?>

        function work_duration() {
            if( $('#in_date').val() != "" && $('#in_time').val() != "" && $('#out_date').val() != "" && $('#out_time').val() != "" ) {
                let tspan = "0h 0m";

                const start = new moment($('#in_date').val() + ' ' + $('#in_time').val());
                const end = new moment($('#out_date').val() + ' ' + $('#out_time').val());

                tspan = end.diff(start)/1000;
                
                let hours = '';
                if(tspan >= 3600) {
                    hours = parseInt(tspan/60/60) + 'h ';
                }
                let minutes = '';
                if(tspan > 60) {
                    minutes = (parseInt(tspan/60) % 60) + 'm';
                }

                if(hours || minutes) {
                    $('#duration').html( hours + minutes );
                } else {
                    $('#duration').html( '-' );
                }
            } else {
                $('#duration').html( '-' );
            }

            let total_minutes = 0;

            <?php foreach ([1,3,5,7] as $index) { $succeding = $index+1;?>

                if( $('#<?= $index ?>-date').val() != "" && $('#<?= $index ?>-time').val() != "" && $('#<?= $succeding ?>-date').val() != "" && $('#<?= $succeding ?>-time').val() != "" ) {
                    const start = new moment($('#<?= $index ?>-date').val() + ' ' + $('#<?= $index ?>-time').val());
                    const end = new moment($('#<?= $succeding ?>-date').val() + ' ' + $('#<?= $succeding ?>-time').val());

                    let cur_minutes = end.diff(start)/1000;
                    if(cur_minutes) {
                        total_minutes += cur_minutes/60;
                    }
                }

            <?php } ?>

            let hours = '';
            if(total_minutes >= 60) {
                hours = parseInt(total_minutes/60) + 'h ';
            }
            let minutes = '';
            if(total_minutes > 0) {
                minutes = (parseInt(total_minutes) % 60) + 'm';
            }

            $('#total_break').html( hours + minutes );
        } work_duration();

        $('#in_date, #in_time, #out_date, #out_time').on('change', () => {
            work_duration();
        });

        $("#log_type").val('<?= $model_info->log_type?$model_info->log_type:"overtime" ?>');    
        $("#attendance-form .select2").select2();
    });
</script>