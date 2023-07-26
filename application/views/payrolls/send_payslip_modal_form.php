<?php echo form_open(get_uri("fas/payrolls/send_payslip"), array("id" => "payslip-email-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="payslip_id" value="<?= $payslip_id ?>" />

    <div class="form-group">
        <div class="alert alert-info" role="alert">
            This will send the payslip to the respective employee email found on account settings.
        </div>
    </div>

    <div class="form-group">
        <label for="remarks" class="col-md-3"><?php echo lang('remarks'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "remarks",
                "name" => "remarks",
                "value" => "",
                "class" => "form-control",
                "placeholder" => lang('remarks'),
                "data-rich-text-editor" => true
            ));
            ?>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('send'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#payslip-email-form").appForm({
            onSuccess: function (result) {
                $(".dataTable:visible").appTable({reload: true});
            },
        });

        $('.select2').select2();
    });
    
</script>