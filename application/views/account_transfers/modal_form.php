<?php echo form_open(get_uri("account_transfers/save"), array("id" => "account-transfers-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info ? $model_info->id : "" ?>" />

    <div class="form-group">
        <label for="date" class=" col-md-3"><?php echo lang('date'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "date",
                "name" => "date",
                "value" => $model_info ? $model_info->date : "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('date'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="account_from" class="col-md-3"><?php echo lang('from'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("account_from", $accounts_dropdown, $model_info ? $model_info->account_from : "", "class='select2 validate-hidden' id='account_from' data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="account_to" class="col-md-3"><?php echo lang('to'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("account_to", $accounts_dropdown, $model_info ? $model_info->account_to : "", "class='select2 validate-hidden' id='account_to' data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="amount" class=" col-md-3"><?php echo lang('amount'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "amount",
                "name" => "amount",
                "value" => $model_info ? $model_info->amount : "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('amount'),
                "type" => "number",
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
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
        setDatePicker('#date');
        $('#account-transfers-form .select2').select2();

        $("#account-transfers-form").appForm({
            onSuccess: function (result) {
                $("#account-transfers-table").appTable({newData: result.data, dataId: result.id});
            }
        });
    });
</script>