<?php echo form_open(get_uri("finance/Loans/save"), array("id" => "create-loan-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="form-group">
        <label for="category_id" class=" col-md-2"><?php echo lang('category'); ?></label>
        <div class=" col-md-10">
            <?php
                echo form_dropdown("category_id", $loan_categories_dropdown, $model_info ? $model_info->category_id : "", "class='select2 validate-hidden' id='category_id'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="borrower_id" class=" col-md-2"><?php echo lang('borrower'); ?></label>
        <div class=" col-md-10">
            <?php
                echo form_dropdown("borrower_id", $team_members_dropdown, $model_info ? $model_info->borrower_id : "", "class='select2 validate-hidden' id='borrower_id' data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="cosigner_id" class=" col-md-2"><?php echo lang('cosigner'); ?></label>
        <div class=" col-md-10">
            <?php
                echo form_dropdown("cosigner_id", $team_members_dropdown, $model_info ? $model_info->cosigner_id : "", "class='select2 validate-hidden' id='cosigner_id'");
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="title" class=" col-md-2"><?php echo lang('principal'); ?></label>
        <div class=" col-md-10">
            <?php
            echo form_input(array(
                "id" => "principal_amount",
                "name" => "principal_amount",
                "value" => $model_info->principal_amount,
                "type" => "number",
                "min" => "0",
                "class" => "form-control",
                "placeholder" => lang('loan_amount'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="title" class=" col-md-2"><?php echo lang('interest_rate'); ?></label>
        <div class=" col-md-4">
            <?php
            echo form_input(array(
                "id" => "interest_rate",
                "name" => "interest_rate",
                "value" => $model_info->interest_rate,
                "type" => "number",
                "min" => "0",
                "class" => "form-control",
                "placeholder" => lang('interest_rate'),
                "autofocus" => true,
            ));
            ?>
        </div>
        <label for="title" class=" col-md-2"><?php echo lang('months_to_pay'); ?></label>
        <div class=" col-md-4">
            <?php
            echo form_input(array(
                "id" => "months_to_pay",
                "name" => "months_to_pay",
                "value" => $model_info->months_topay,
                "type" => "number",
                "min" => "0",
                "class" => "form-control",
                "placeholder" => lang('months_to_pay'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="title" class=" col-md-2"><?php echo lang('days_before_due'); ?></label>
        <div class=" col-md-4">
            <?php
            echo form_input(array(
                "id" => "days_before_due",
                "name" => "days_before_due",
                "value" => $model_info->days_before_due,
                "type" => "number",
                "min" => "0",
                "class" => "form-control",
                "placeholder" => lang('days_before_due'),
                "autofocus" => true,
            ));
            ?>
        </div>
        <label for="title" class=" col-md-2"><?php echo lang('penalty_rate'); ?></label>
        <div class=" col-md-4">
            <?php
            echo form_input(array(
                "id" => "penalty_rate",
                "name" => "penalty_rate",
                "value" => $model_info->penalty_rate,
                "type" => "number",
                "min" => "0",
                "class" => "form-control",
                "placeholder" => lang('penalty_rate'),
                "autofocus" => true,
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
        $("#create-loan-form").appForm({
            onSuccess: function (result) {
                $(".dataTable:visible").appTable({reload: true});
            }
        });

        $("#create-loan-form .select2").select2();

    });
</script>