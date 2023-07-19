<?php echo form_open(get_uri("finance/Loans/save_update"), array("id" => "update-loan-form", "class" => "general-form", "role" => "form")); ?>
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
    
    <div class="form-group">
        <label for="title" class=" col-md-2"><?php echo lang('status'); ?></label>
        <div class=" col-md-10">
            <?php
            echo form_dropdown("status", array(
                "draft" => "Draft",
                "pending" => "Pending",
                "processing" => "Processing",
                "approved" => "Approved",
                "ongoing" => "Ongoing",
                "cancelled" => "Cancelled",
                "suspended" => "Suspended",
                "completed" => "Completed",
                "delinquency" => "Delinquency",
                ), $loan_stage, "class='select2 validate-hidden' id='status' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="stage_name" class=" col-md-2"><?php echo lang('stage_name'); ?></label>
        <div class=" col-md-10">
            <?php
            echo form_input(array(
                "id" => "stage_name",
                "name" => "stage_name",
                "value" => $model_info->stage_name,
                "class" => "form-control",
                "placeholder" => lang('stage_name'),
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
        $("#update-loan-form").appForm({
            onSuccess: function (result) {
                location.reload();
            }
        });

        $("#update-loan-form .select2").select2();

    });
</script>