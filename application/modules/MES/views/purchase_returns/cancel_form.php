<?php echo form_open(get_uri("mes/PurchaseReturns/cancel"), array("id" => "purchase-order-returns-cancel-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $id?>" />
    <?php echo lang('cancel_confirmation'); ?>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('cancel'); ?></button>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#ajaxModal .modal-dialog").css({"width": "400px"});
        $("#ajaxModal").on("hide.bs.modal", function(){
            $("#ajaxModal .modal-dialog").removeAttr("style");
        });

        $("#purchase-order-returns-cancel-form").appForm({
            onSuccess: function (result) {
                window.location.reload();
            }
        });
    });
</script>