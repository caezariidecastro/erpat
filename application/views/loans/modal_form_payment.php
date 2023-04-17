<?php echo form_open(get_uri("finance/Loans/save_payment"), array("id" => "save-payment-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <div class="form-group">
        <label for="loan_id" class=" col-md-2"><?php echo lang('loan'); ?></label>
        <div class=" col-md-10">
            <?php
                echo form_dropdown("loan_id", $loan_dropdowns, $model_info ? $model_info->loan_id : "", "class='select2 validate-hidden' id='loan_id'");
            ?>
        </div>
    </div>

    <div id="single_day_section"  class="form-group date_section">
        <label id="date_label" for="date_paid" class=" col-md-2"><?php echo lang('date'); ?></label>
        <div class="col-md-10">
            <?php
            echo form_input(array(
                "id" => "date_paid",
                "name" => "date_paid",
                "value" => convert_date_format($model_info->date_paid, "d/m/Y"),
                "class" => "form-control",
                "placeholder" => lang('date'),
                "autocomplete" => "off",
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="title" class=" col-md-2"><?php echo lang('amount'); ?></label>
        <div class=" col-md-10">
            <?php
            echo form_input(array(
                "id" => "amount",
                "name" => "amount",
                "value" => $model_info->amount,
                "class" => "form-control",
                "type" => "number",
                "placeholder" => lang('amount'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
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
                "value" => $model_info->amount,
                "class" => "form-control",
                "placeholder" => lang('remarks')
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
        $("#save-payment-form").appForm({
            onSuccess: function (result) {
                location.reload();
            }
        });

        $("#save-payment-form .select2").select2();
        setDatePicker("#date_paid");

    });
</script>