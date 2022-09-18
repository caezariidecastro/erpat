<div class="tab-content">
    <?php echo form_open(get_uri("hrs/team_members/save_job_info/"), array("id" => "job-info-form", "class" => "general-form dashed-row white", "role" => "form")); ?>

    <input name="user_id" type="hidden" value="<?php echo $user_id; ?>" />
    <div class="panel">
        <div class="panel-default panel-heading">
            <h4><?php echo lang('job_info'); ?></h4>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label for="job_idnum" class=" col-md-2"><?php echo lang('job_idnum'); ?></label>
                <div class="col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "job_idnum",
                        "name" => "job_idnum",
                        "value" => $job_info->job_idnum,
                        "class" => "form-control",
                        "placeholder" => lang('job_idnum')
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="rfid_sticker" class=" col-md-2"><?php echo lang('rfid_num'); ?></label>
                <div class="col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "rfid_num",
                        "name" => "rfid_num",
                        "value" => $job_info->rfid_num,
                        "disabled" => true,
                        "class" => "form-control",
                        "placeholder" => lang('rfid_num')
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="job_title" class=" col-md-2"><?php echo lang('job_title'); ?></label>
                <div class="col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "job_title",
                        "name" => "job_title",
                        "value" => $job_info->job_title,
                        "class" => "form-control",
                        "placeholder" => lang('job_title')
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="salary_term" class=" col-md-2"><?php echo lang('salary_term'); ?></label>
                <div class="col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "salary_term",
                        "name" => "salary_term",
                        "value" => $job_info->salary_term,
                        "class" => "form-control",
                        "placeholder" => lang('salary_term')
                    ));
                    ?>
                </div>
            </div>
            <?php if( $payroll_enabled ) { ?>
            <div class="form-group">
                <label for="salary" class=" col-md-2"><?php echo lang('monthly_salary')." <br>Based on Hourly Rate: <br><strong>".get_monthly_salary($job_info->rate_per_hour)."</strong>"; ?></label>
                <div class="col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "salary",
                        "name" => "salary",
                        "type" => "number",
                        "value" => $job_info->salary ? convert_number_to_decimal($job_info->salary) : 0,
                        "class" => "form-control",
                        "placeholder" => lang('salary')
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="rate_per_hour" class=" col-md-2"><?php echo lang('rate_per_hour'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "rate_per_hour",
                        "name" => "rate_per_hour",
                        "type" => "number",
                        "value" => $job_info->rate_per_hour ? convert_number_to_decimal($job_info->rate_per_hour) : 0,
                        "class" => "form-control",
                        "placeholder" => lang('rate_per_hour')
                    ));
                    ?>
                </div>
            </div>
            <?php } ?>
            <div class="form-group">
                <label for="sched_id" class=" col-md-2"><?php echo lang('current_schedule'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_dropdown("sched_id", $sched_dropdown, $job_info->sched_id, "class='select2 validate-hidden' id='sched_id' ". "'");
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="date_of_hire" class=" col-md-2"><?php echo lang('date_of_hire'); ?></label>
                <div class="col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "date_of_hire",
                        "name" => "date_of_hire",
                        "value" => $job_info->date_of_hire,
                        "class" => "form-control",
                        "placeholder" => lang('date_of_hire'),
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
                <div class="col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "contact_name",
                        "name" => "contact_name",
                        "value" => $job_info->contact_name,
                        "class" => "form-control",
                        "placeholder" => lang('contact_name'),
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
                        "placeholder" => lang('contact_address')
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="contact_phone" class=" col-md-2"><?php echo lang('contact_phone'); ?></label>
                <div class="col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "contact_phone",
                        "name" => "contact_phone",
                        "value" => $job_info->contact_phone,
                        "class" => "form-control",
                        "placeholder" => lang('contact_phone'),
                        "autocomplete" => "off"
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
                <label for="sss" class=" col-md-2"><?php echo lang('sss'); ?></label>
                <div class="col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "sss",
                        "name" => "sss",
                        "value" => $job_info->sss,
                        "class" => "form-control",
                        "placeholder" => lang('sss'),
                        "autocomplete" => "off"
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="tin" class=" col-md-2"><?php echo lang('tin'); ?></label>
                <div class="col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "tin",
                        "name" => "tin",
                        "value" => $job_info->tin,
                        "class" => "form-control",
                        "placeholder" => lang('tin'),
                        "autocomplete" => "off"
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="pag_ibig" class=" col-md-2"><?php echo lang('pag_ibig'); ?></label>
                <div class="col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "pag_ibig",
                        "name" => "pag_ibig",
                        "value" => $job_info->pag_ibig,
                        "class" => "form-control",
                        "placeholder" => lang('pag_ibig'),
                        "autocomplete" => "off"
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="phil_health" class=" col-md-2"><?php echo lang('phil_health'); ?></label>
                <div class="col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "phil_health",
                        "name" => "phil_health",
                        "value" => $job_info->phil_health,
                        "class" => "form-control",
                        "placeholder" => lang('phil_health'),
                        "autocomplete" => "off"
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

        setDatePicker("#date_of_hire");

    });
</script>    