<?php echo form_open(get_uri("payrolls/mark_as_paid/".$model_info->id), array("id" => "lock-payment-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="payroll_id" value="<?= $model_info->id; ?>" />
    <input type="hidden" name="expense_user_id" value="<?= $cur_user_id; ?>" />

    <div class="form-group">
        <label for="expense_account_id" class=" col-md-3"><?php echo lang('account'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_dropdown("expense_account_id", $account_dropdown, $model_info ? $model_info->account_id : "", "class='select2 validate-hidden' id='expense_account_id' data-rule-required='true' data-msg-required='".lang('field_required')."'");
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="expense_payment_method_id" class=" col-md-3"><?php echo lang('payment_method'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("expense_payment_method_id", $payment_methods_dropdown, "", "class='select2'");
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="expense_payment_category_id" class=" col-md-3"><?php echo lang('expense_category'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("expense_payment_category_id", $payment_categories_dropdown, "", "class='select2'");
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="expense_payment_date" class=" col-md-3"><?php echo lang('payment_date'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "expense_payment_date",
                "name" => "expense_payment_date",
                "value" => "",
                "class" => "form-control",
                "placeholder" => lang('payment_date'),
                "autocomplete" => "off",
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required")
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="expense_payment_amount" class=" col-md-3"><?php echo lang('amount'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "expense_payment_amount",
                "name" => "expense_payment_amount",
                "value" => "",
                "class" => "form-control",
                "type" => "number",
                "placeholder" => lang('amount'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="expense_payment_note" class="col-md-3"><?php echo lang('note'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "expense_payment_note",
                "name" => "expense_payment_note",
                "value" => "",
                "class" => "form-control",
                "placeholder" => lang('description'),
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
        $("#lock-payment-form").appForm({
            onSuccess: function (result) {
                if(result.success) {
                    location.reload();
                }
            },
            // onError: function (result) {
            //     appLoader.hide();
            //     appAlert.success(result.message, {duration: 5000});
            //     return true;
            // }
        });
        $("#lock-payment-form .select2").select2();

        setDatePicker("#expense_payment_date");

    });
</script>