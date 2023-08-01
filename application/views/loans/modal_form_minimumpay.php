<?php echo form_open(get_uri("finance/Loans/save_minimumpay"), array("id" => "save-minimumpay-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="form-group">
        <label for="loan_id" class=" col-md-2"><?php echo lang('loan'); ?></label>
        <div class=" col-md-10">
            <?php
                echo form_dropdown("loan_id", $loan_dropdowns, $model_info ? $model_info->id : "", "class='select2 validate-hidden' disabled=true id='loan_id'");
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="borrower_id" class=" col-md-2"><?php echo lang('borrower'); ?></label>
        <div class=" col-md-10">
            <?php
                echo form_dropdown("borrower_id", $team_members_dropdown, $model_info ? $model_info->borrower_id : "", "class='select2 validate-hidden' disabled=true id='borrower_id' data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>
    </div>

    <input type="hidden" name="old_minpay" value="<?php echo $model_info->min_payment; ?>" />
    <div class="form-group">
        <label for="title" class=" col-md-2"><?php echo lang('minimum_payment'); ?></label>
        <div class=" col-md-10">
            <?php
            echo form_input(array(
                "id" => "minimum_payment",
                "name" => "minimum_payment",
                "value" => $model_info->min_payment,
                "class" => "form-control",
                "type" => "number",
                "placeholder" => lang('minimum_payment'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="title" class=" col-md-2"><?php echo lang('payroll_binding'); ?></label>
        <div class=" col-md-10">
            <?php
            echo form_dropdown("payroll_binding", array(
                "none" => "No Payroll Bindings",
                "daily" => "Daily Payout",
                "weekly" => "Weekly Payout",
                "biweekly" => "Bi-Weekly Payout",
                "monthly" => "Monthly Payout"
                ), $model_info->payroll_binding, "class='select2 validate-hidden' id='payroll_binding' ");
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="remarks" class=" col-md-2"><?php echo lang('remarks'); ?></label>
        <div class=" col-md-10">
            <?php
            echo form_textarea(array(
                "id" => "remarks",
                "name" => "remarks",
                "class" => "form-control",
                "placeholder" => lang('remarks')
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <div class=" col-md-12">
            <div class="alert alert-danger" role="alert">
                Change of Monthly Minimum Payment will affect the any paylip deductions including the months to pay reports.
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#save-minimumpay-form").appForm({
            onSuccess: function (result) {
                $(".dataTable:visible").appTable({reload: true});
            }
        });

        $("#save-minimumpay-form .select2").select2();

        $('#payroll_binding').on('change', () => {
            const old_minpay = <?= $orig_min_payment ?>;
            console.log(old_minpay);
            if( $('#payroll_binding').val() == "daily" ) {
                $('#minimum_payment').val(old_minpay/24);
            } else if( $('#payroll_binding').val() == "weekly" ) {
                $('#minimum_payment').val(old_minpay/4);
            } else if( $('#payroll_binding').val() == "bi-weekly" ) {
                $('#minimum_payment').val(old_minpay/2);
            } else {
                $('#minimum_payment').val(old_minpay);
            }
        });
    });
</script>