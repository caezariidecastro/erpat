<div class="container">
    <div class="tab-content">
        <?php echo form_open(get_uri("hrs/team_members/save_job_info/"), array("id" => "job-info-form", "class" => "general-form dashed-row white", "role" => "form")); ?>

        <input name="user_id" type="hidden" value="<?php echo $user_id; ?>" />
        <div class="panel">
            <div class="panel-default panel-heading">
                <h4><?php echo lang('job_info'); ?></h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label for="job_title" class=" col-md-2"><?php echo lang('job_title'); ?></label>
                    <div class="col-md-2">
                        <?php
                        echo form_input(array(
                            "id" => "job_title",
                            "name" => "job_title",
                            "value" => $job_info->job_title,
                            "class" => "form-control",
                            $can_update?"writable":"disabled" => true,
                            "placeholder" => lang('job_title')
                        ));
                        ?>
                    </div>
                    <label for="job_idnum" class=" col-md-2"><?php echo lang('job_idnum'); ?></label>
                    <div class="col-md-2">
                        <?php
                        echo form_input(array(
                            "id" => "job_idnum",
                            "name" => "job_idnum",
                            "value" => $job_info->job_idnum,
                            "class" => "form-control",
                            $can_update?"writable":"disabled" => true,
                            "placeholder" => lang('job_idnum')
                        ));
                        ?>
                    </div>
                    <label for="rfid_sticker" class=" col-md-2"><?php echo lang('rfid_num'); ?></label>
                    <div class="col-md-2">
                        <?php
                        echo form_input(array(
                            "id" => "rfid_num",
                            "name" => "rfid_num",
                            "value" => $job_info->rfid_num,
                            "disabled" => true,
                            "class" => "form-control",
                            $can_update?"writable":"disabled" => true,
                            "placeholder" => lang('rfid_num')
                        ));
                        ?>
                    </div>
                </div>
                <?php if( $payroll_enabled ) { ?>
                <div class="form-group">
                    <label for="salary" class=" col-md-2"><?php echo lang('monthly_salary')." <br>Based on Hourly Rate: <br><strong>".get_monthly_from_hourly($job_info->rate_per_hour)."</strong>"; ?></label>
                    <div class="col-md-2">
                        <?php
                        echo form_input(array(
                            "id" => "salary",
                            "name" => "salary",
                            "type" => "number",
                            "value" => $job_info->salary ? convert_number_to_decimal($job_info->salary) : 0,
                            "class" => "form-control",
                            $can_update?"writable":"disabled" => true,
                            "placeholder" => lang('salary')
                        ));
                        ?>
                    </div>
                    <label for="daily_rate" class=" col-md-1"><?php echo lang('daily_rate')." (*)"; ?></label>
                    <div class=" col-md-2">
                        <?php
                        echo form_input(array(
                            "id" => "daily_rate",
                            "name" => "daily_rate",
                            "type" => "number",
                            "value" => $job_info->daily_rate ? convert_number_to_decimal($job_info->daily_rate) : 0,
                            "class" => "form-control",
                            $can_update?"writable":"disabled" => true,
                            "placeholder" => lang('daily_rate')
                        ));
                        ?>
                    </div>
                    <label for="rate_per_hour" class=" col-md-1"><?php echo lang('rate_per_hour'); ?></label>
                    <div class=" col-md-1">
                        <?php
                        echo form_input(array(
                            "id" => "rate_per_hour",
                            "name" => "rate_per_hour",
                            "type" => "number",
                            "value" => $job_info->rate_per_hour ? convert_number_to_decimal($job_info->rate_per_hour) : 0,
                            "class" => "form-control",
                            $can_update?"writable":"disabled" => true,
                            "disabled" => true,
                            "placeholder" => lang('rate_per_hour')
                        ));
                        ?>
                    </div>
                    <label for="salary_term" class=" col-md-1"><?php echo lang('salary_term'); ?></label>
                    <div class="col-md-2">
                        <?= form_dropdown(
                            "salary_term", array(
                            "" => "- ".lang("select")." - ",
                            "daily" => lang("daily"),
                            "weekly" => lang("weekly"),
                            "biweekly" => lang("biweekly"),
                            "monthly" => lang("monthly"),
                        ), $job_info->salary_term, "class='select2 mini can_update_trigger'"); ?>
                    </div>
                </div>
                <?php } ?>
                <div class="form-group">
                    <label for="sched_id" class=" col-md-1"><?php echo lang('current_schedule'); ?></label>
                    <div class=" col-md-2">
                        <?php
                        echo form_dropdown("sched_id", $sched_dropdown, $job_info->sched_id, "class='select2 validate-hidden can_update_trigger' id='sched_id' ". "'");
                        ?>
                    </div>
                    <label for="date_of_hire" class=" col-md-1"><?php echo lang('date_of_hire'); ?></label>
                    <div class="col-md-2">
                        <?php
                        echo form_input(array(
                            "id" => "date_of_hire",
                            "name" => "date_of_hire",
                            "value" => $job_info->date_of_hire,
                            "class" => "form-control",
                            $can_update?"writable":"disabled" => true,
                            "placeholder" => lang('date_of_hire'),
                            "autocomplete" => "off"
                        ));
                        ?>
                    </div>
                    <label for="sched_id" class=" col-md-1"><?php echo lang('employment'); ?></label>
                    <div class="col-md-2">
                        <?= form_dropdown(
                            "employment_stage", array(
                            "" => "- ".lang("select")." - ",
                            "probationary" => lang("probationary"),
                            "floating" => lang("floating"),
                            "regular" => lang("regular"),
                        ), get_user_meta($user_id, "employment_stage"), "class='select2 mini can_update_trigger'"); ?>
                    </div>
                    <label for="sched_id" class=" col-md-1"><?php echo lang('date_regularized'); ?></label>
                    <div class="col-md-2">
                        <?php
                        echo form_input(array(
                            "id" => "date_regularized",
                            "name" => "date_regularized",
                            "value" => get_user_meta($user_id, "date_regularized"),
                            "class" => "form-control",
                            $can_update?"writable":"disabled" => true,
                            "placeholder" => lang('date_regularized'),
                            "autocomplete" => "off"
                        ));
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="signiture_url" class=" col-md-2"><?php echo lang('signiture_url'); ?></label>
                    <div class="col-md-10">
                        <?php
                        echo form_input(array(
                            "id" => "signiture_url",
                            "name" => "signiture_url",
                            "value" => $job_info->signiture_url,
                            "class" => "form-control",
                            $can_update?"writable":"disabled" => true,
                            "placeholder" => lang('signiture_url_placeholder'),
                            "autocomplete" => "off"
                        ));
                        ?>
                    </div>
                </div>
            </div>
            <div class="panel-default panel-heading">
                <h4><?php echo lang('emergency_contact'); ?></h4>
            </div>
            <div class="panel-body">
                <div></div>
                <div class="form-group">
                    <label for="contact_name" class=" col-md-2"><?php echo lang('contact_name'); ?></label>
                    <div class="col-md-5">
                        <?php
                        echo form_input(array(
                            "id" => "contact_name",
                            "name" => "contact_name",
                            "value" => $job_info->contact_name,
                            "class" => "form-control",
                            $can_update?"writable":"disabled" => true,
                            "placeholder" => lang('contact_name'),
                            "autocomplete" => "off"
                        ));
                        ?>
                    </div>
                    <label for="contact_phone" class=" col-md-2"><?php echo lang('contact_phone'); ?></label>
                    <div class="col-md-3">
                        <?php
                        echo form_input(array(
                            "id" => "contact_phone",
                            "name" => "contact_phone",
                            "value" => $job_info->contact_phone,
                            "class" => "form-control",
                            $can_update?"writable":"disabled" => true,
                            "placeholder" => lang('contact_phone'),
                            "autocomplete" => "off"
                        ));
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="contact_address" class=" col-md-2"><?php echo lang('contact_address'); ?></label>
                    <div class=" col-md-10">
                        <?php
                        echo form_textarea(array(
                            "id" => "contact_address",
                            "name" => "contact_address",
                            "value" => $job_info->contact_address,
                            "class" => "form-control",
                            $can_update?"writable":"disabled" => true,
                            "placeholder" => lang('contact_address')
                        ));
                        ?>
                    </div>
                </div>
            </div>
            <div class="panel-default panel-heading">
                <h4><?php echo lang('contributions'); ?></h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label for="tin" class=" col-md-2"><?php echo lang('tin'); ?></label>
                    <div class="col-md-4">
                        <?php
                        echo form_input(array(
                            "id" => "tin",
                            "name" => "tin",
                            "value" => $job_info->tin,
                            "class" => "form-control",
                            $can_update?"writable":"disabled" => true,
                            "placeholder" => lang('tin'),
                            "autocomplete" => "off"
                        ));
                        ?>
                    </div>
                    <label for="sss" class=" col-md-2"><?php echo lang('sss'); ?></label>
                    <div class="col-md-4">
                        <?php
                        echo form_input(array(
                            "id" => "sss",
                            "name" => "sss",
                            "value" => $job_info->sss,
                            "class" => "form-control",
                            $can_update?"writable":"disabled" => true,
                            "placeholder" => lang('sss'),
                            "autocomplete" => "off"
                        ));
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="pag_ibig" class=" col-md-2"><?php echo lang('pag_ibig'); ?></label>
                    <div class="col-md-4">
                        <?php
                        echo form_input(array(
                            "id" => "pag_ibig",
                            "name" => "pag_ibig",
                            "value" => $job_info->pag_ibig,
                            "class" => "form-control",
                            $can_update?"writable":"disabled" => true,
                            "placeholder" => lang('pag_ibig'),
                            "autocomplete" => "off"
                        ));
                        ?>
                    </div>
                    <label for="phil_health" class=" col-md-2"><?php echo lang('phil_health'); ?></label>
                    <div class="col-md-4">
                        <?php
                        echo form_input(array(
                            "id" => "phil_health",
                            "name" => "phil_health",
                            "value" => $job_info->phil_health,
                            "class" => "form-control",
                            $can_update?"writable":"disabled" => true,
                            "placeholder" => lang('phil_health'),
                            "autocomplete" => "off"
                        ));
                        ?>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <?php if($can_update) { ?>
                <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
                <?php } ?>
            </div>

        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#job-info-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
                window.location.href = "<?php echo get_uri("hrs/team_members/view/" . $job_info->user_id); ?>" + "/job_info";
            }
        });
        $("#job-info-form .select2").select2();

        setDatePicker("#date_of_hire, #date_regularized");

        function roundToDecimal(num) {
            return Math.round(num * 100) / 100
        }

        //from daily
        function dailyRateRefresh() {
            let curVal = $("#daily_rate").val();
            let newVal = parseFloat(curVal).toFixed(2);
            let hourRate = roundToDecimal( newVal/8 );

            let daysPerYear = <?= get_setting('days_per_year', 260) ?>;
            let dailyRate = newVal * (daysPerYear/12);

            $("#rate_per_hour").val( roundToDecimal(hourRate) );
            $("#salary").val( roundToDecimal(dailyRate) ); //compute here.
            
        }
        $("#daily_rate").change(() => {
            dailyRateRefresh();
        });

        //from monthly
        function monthlySalaryRefresh() {
            let curVal = $("#salary").val();
            let newVal = parseFloat(curVal).toFixed(2);

            let daysPerYear = <?= get_setting('days_per_year', 260) ?>;
            let dailyRate = roundToDecimal( (newVal*12)/daysPerYear );

            $("#daily_rate").val( dailyRate ); //compute here.
            let hourRate = roundToDecimal( dailyRate/8 );
            $("#rate_per_hour").val( hourRate );
        }
        $("#salary").change(() => {
            monthlySalaryRefresh();
        });

        <?php if(!$can_update) { ?>
            $(".can_update_trigger").prop("disabled", true);
        <?php } ?>
    });
</script>    