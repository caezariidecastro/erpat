<?php echo form_open(get_uri("purchase_orders/save"), array("id" => "purchase-orders-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info ? $model_info->id : "" ?>" />

    <div class="form-group">
        <label for="vendor_id" class="col-md-3"><?php echo lang('supplier'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("vendor_id", $vendor_dropdown, $model_info ? $model_info->vendor_id : "", "class='select2 validate-hidden' data-rule-required='true' data-msg-required='".lang("field_required")."' id='vendor_id'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="remarks" class="col-md-3"><?php echo lang('remarks'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "remarks",
                "name" => "remarks",
                "value" => $model_info ? $model_info->remarks : "",
                "class" => "form-control",
                "placeholder" => lang('remarks'),
                "data-rich-text-editor" => true,
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
        let reload_on_success = "<?php echo $reload?>";

        $("#purchase-orders-form").appForm({
            onSuccess: function (result) {
                $("#purchase-orders-table").appTable({newData: result.data, dataId: result.id});

                if(reload_on_success){
                    window.location.reload();
                }
            }
        });

        $("#vendor_id").select2();
    });
</script>