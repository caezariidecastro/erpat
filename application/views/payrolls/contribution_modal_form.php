<?php echo form_open(get_uri("payrolls/save_auto_contribution"), array("id" => "payroll-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="user_id" value="<?php echo $user_id ? $user_id : "" ?>" />

    <div class="form-group">
        <div class="alert alert-danger" role="alert">
             Please be aware that executing this function will reset all Contributions to the their official values based on each employees job offer and bsei formulas.
        </div>
    </div>

</div>

<div class="modal-footer">
    <button type="submit" class="btn btn-danger"><span class="fa fa-check-circle"></span> <?php echo lang('execute'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#payroll-form").appForm({
            onSuccess: function (result) {
                //appAlert.success(result.message);
                location.reload();
            },
        });
    });
    
</script>