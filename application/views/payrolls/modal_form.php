<?php echo form_open(get_uri("fas/payrolls/save"), array("id" => "payroll-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info ? $model_info->id : "" ?>" />

    <div class="form-group">
        <div class="alert alert-info" role="alert">
            The Start and End date will be use to automatically get the worked hours of all the employee for the department that was choosen. 
        </div>
    </div>

    <div class="form-group">
        <label for="account_id" class="col-md-3"><?php echo lang('account'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("account_id", $account_dropdown, $model_info ? $model_info->account_id : "", "class='select2 validate-hidden' id='account_id' data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="department_id" class="col-md-3"><?php echo lang('department'); ?></label>
        <div class="col-md-9">
            <input type="text" value="<?= $model_info->department ?>" placeholder="Select Multiple" name="department_id" id="department_lists" class="w100p validate-hidden"  placeholder="<?php echo lang('departments'); ?>"  />   
        </div>
    </div>
    <?php if( isset($model_info) && isset($model_info->signed_by) && ($this->login_user->is_admin || $model_info->signed_by == $this->login_user->id) ) { ?>
    <div class="form-group">
        <label for="user_id" class="col-md-3"><?php echo lang('employee'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("user_id", $user_dropdown, $model_info ? $model_info->signed_by : "", "class='select2 validate-hidden' id='user_id' data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>
    </div>
    <?php } ?>
    <div class="form-group">
        <label for="start_date" class=" col-md-3"><?php echo lang('pay_period'); ?></label>
        <div class="col-md-5">
            <?php
            echo form_input(array(
                "id" => "start_date",
                "name" => "start_date",
                "value" => $model_info->start_date ? $model_info->start_date : '',
                "class" => "form-control recurring_element",
                "placeholder" => lang('start_date'),
                "autocomplete" => "off",
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
        <div class="col-md-4">
            <?php
            echo form_input(array(
                "id" => "end_date",
                "name" => "end_date",
                "value" => $model_info->end_date ? $model_info->end_date : '',
                "class" => "form-control recurring_element",
                "placeholder" => lang('end_date'),
                "autocomplete" => "off",
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="pay_date" class=" col-md-3"><?php echo lang('pay_date'); ?></label>
        <div class="col-md-5">
            <?php
            echo form_input(array(
                "id" => "pay_date",
                "name" => "pay_date",
                "value" => $model_info->pay_date ? $model_info->pay_date : '',
                "class" => "form-control recurring_element",
                "placeholder" => lang('pay_date'),
                "autocomplete" => "off",
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
        <div class="col-md-4">
            <?php
            echo form_input(array(
                "id" => "sched_hours",
                "name" => "sched_hours",
                "value" => $model_info->sched_hours ? $model_info->sched_hours : '',
                "class" => "form-control",
                "type" => "number",
                "placeholder" => lang('schedule_hours'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="tax_table" class=" col-md-3"><?php echo lang('tax_table'); ?></label>
        <div class="col-md-4">
            <?= form_dropdown(
                "tax_table", array(
                "" => "- ".lang("select")." - ",
                "daily" => lang("daily"),
                "weekly" => lang("weekly"),
                "biweekly" => lang("biweekly"),
                "monthly" => lang("monthly"),
            ), $job_info->tax_table, "class='select2 mini'"); ?>
        </div>
        <div class="col-md-5">
            <?php
            echo form_dropdown("accountant_id", $user_dropdown, $model_info ? $model_info->accountant_id : "", "class='select2 validate-hidden' id='accountant_id' data-rule-required='true' data-msg-required='".lang("field_required")."' ".(isset($model_info->id)?"disabled":""));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="earnings_included" class="col-md-3"><?php echo lang('earnings'); ?></label>
        <div class="col-md-9">
            <input type="text" value="<?= $model_info->earnings ?>" placeholder="Select Multiple" name="earnings_included" id="earnings_included" class="w100p validate-hidden"  placeholder="<?php echo lang('earnings'); ?>"  />   
        </div>
    </div>
    <div class="form-group">
        <label for="deductions_included" class="col-md-3"><?php echo lang('deductions'); ?></label>
        <div class="col-md-9">
            <input type="text" value="<?= $model_info->deductions ?>" placeholder="Select Multiple" name="deductions_included" id="deductions_included" class="w100p validate-hidden"  placeholder="<?php echo lang('deductions'); ?>"  />   
        </div>
    </div>
    <div class="form-group">
        <label for="note" class="col-md-3"><?php echo lang('note'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "remarks",
                "name" => "remarks",
                "value" => $model_info ? $model_info->remarks : "",
                "class" => "form-control",
                "placeholder" => lang('note'),
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
        $("#payroll-form").appForm({
            onSuccess: function (result) {
                $("#payroll-table").appTable({newData: result.data, dataId: result.id}); 
            },
        });

        $('.select2').select2();

        $('#department_lists').select2({
            multiple: true,
            data: <?php echo ($department_dropdown); ?>
        });

        $('#earnings_included').select2({
            multiple: true,
            data: <?php echo ($earning_dropdown); ?>
        });

        $('#deductions_included').select2({
            multiple: true,
            data: <?php echo ($deduction_dropdown); ?>
        });

        <?php if( $model_info ) { ?>
            $("#account_id").prop('disabled', true);
            $("#department_id").prop('disabled', true);

            $("#start_date").prop('disabled', true);
            $("#end_date").prop('disabled', true);
            $("#pay_date").prop('disabled', true);
        <?php } ?>

        setDatePicker("#start_date, #end_date, #pay_date");
    });
    
</script>