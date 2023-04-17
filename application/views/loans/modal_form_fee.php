<?php echo form_open(get_uri("finance/Loans/save_fee"), array("id" => "save-fee-form", "class" => "general-form", "role" => "form")); ?>
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

    <div class="form-group">
        <label for="title" class=" col-md-2"><?php echo lang('title'); ?></label>
        <div class=" col-md-10">
            <?php
            echo form_input(array(
                "id" => "title",
                "name" => "title",
                "value" => $model_info->title,
                "class" => "form-control",
                "placeholder" => lang('title'),
                "autofocus" => true,
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
        $("#save-fee-form").appForm({
            onSuccess: function (result) {
                location.reload();
            }
        });

        $("#save-fee-form .select2").select2();

    });
</script>