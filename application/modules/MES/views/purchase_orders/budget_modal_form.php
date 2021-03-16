<?php echo form_open(get_uri("mes/PurchaseOrders/save_budget"), array("id" => "purchase-order-budget-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <input type="hidden" name="purchase_id" value="<?php echo $purchase_id; ?>" />
    <input type="hidden" name="account_id" value="<?php echo $account_id; ?>" />
    <div class="form-group">
        <label for="amount" class=" col-md-3"><?php echo lang('amount'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "amount",
                "name" => "amount",
                "value" => $model_info->amount ? $model_info->amount : "",
                "class" => "form-control",
                "placeholder" => lang('amount'),
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
        $("#purchase-order-budget-form").appForm({
            onSuccess: function (result) {
                $("#purchase-order-budget-table").appTable({newData: result.data, dataId: result.id});
                $("#purchase_order_status_label").html(result.purchase_status);
            }
        });

        $("#account").select2();
    });
</script>