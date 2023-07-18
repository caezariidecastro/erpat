<?php echo form_open(get_uri("finance/Loans/save_payment"), array("id" => "save-payment-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <?php if($loan_id) { $disabled = "disabled"; $loan_id_name = "";?>
        <input type="hidden" name="loan_id" value="<?php echo $loan_id; ?>" />
    <?php } else { $loan_id_name = "loan_id"; }?>


    <div class="form-group">
        <label for="loan_id" class=" col-md-2"><?php echo lang('loan'); ?></label>
        <div class=" col-md-10">
            <?php
                echo form_dropdown($loan_id_name, $loan_dropdowns, $loan_id, "class='select2 validate-hidden' $disabled");
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
                "value" => $model_info->date_paid ? convert_date_utc_to_local($model_info->date_paid, "Y-m-d") : "",
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
        <label for="penalty" class=" col-md-2"><?php echo lang('penalty'); ?></label>
        <div class=" col-md-10">
            <?php
            echo form_input(array(
                "id" => "penalty",
                "name" => "penalty",
                "value" => $model_info->late_interest,
                "class" => "form-control",
                "type" => "number",
                "placeholder" => lang('penalty')
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
                "value" => $model_info->remarks,
                "class" => "form-control",
                "placeholder" => lang('remarks')
            ));
            ?>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#save-payment-form").appForm({
            onSuccess: function (result) {
                $(".dataTable:visible").appTable({reload: true});
            }
        });

        $("#save-payment-form .select2").select2();
        setDatePicker("#date_paid");

    });
</script>