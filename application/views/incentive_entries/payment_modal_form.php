<?php echo form_open(get_uri("incentive_entries/pay/".$model_info->id), array("id" => "incentive-payment-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <div class="form-group">
        <label for="account_id" class=" col-md-3"><?php echo lang('account'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_dropdown("account_id", $accounts_dropdown, $model_info ? $model_info->account_id : "", "class='select2 validate-hidden' id='account_id' data-rule-required='true' data-msg-required='".lang('field_required')."'");
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="expense_payment_method_id" class=" col-md-3"><?php echo lang('payment_method'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("expense_payment_method_id", $payment_methods_dropdown, array($model_info->payment_method_id), "class='select2'");
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
                "value" => $model_info->payment_date,
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
                "value" => $model_info->amount ? $model_info->amount : "",
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
                "value" => $model_info->note ? $model_info->note : "",
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
        $("#incentive-payment-form").appForm({
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
        $("#incentive-payment-form .select2").select2();

        setDatePicker("#expense_payment_date");

    });
</script>