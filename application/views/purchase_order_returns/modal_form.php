<?php echo form_open(get_uri("purchase_order_returns/save"), array("id" => "purchase-order-returns-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info ? $model_info->id : "" ?>" />
    <div class="form-group">
        <div class="col-md-12 text-off"> <?php echo lang('add_return_completed_purchase_info'); ?></div>
    </div>
    <div class="form-group">
        <label for="purchase_id" class="col-md-3"><?php echo lang('purchase_id'); ?></label>
        <div class="col-md-9" id="purchase_id_dropdown_wrapper">
            <?php
            echo form_dropdown("purchase_id", $purchases_dropdown, $model_info ? $model_info->purchase_id : "", "class='select2 validate-hidden' id='purchase_id' data-rule-required='true' data-msg-required='".lang("field_required")."'");
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
        let return_id = "<?php echo $model_info->id?>";
        let reload_on_success = "<?php echo $reload?>";

        $("#purchase-order-returns-form").appForm({
            onSuccess: function (result) {
                $("#purchase-order-returns-table").appTable({newData: result.data, dataId: result.id});
                if(reload_on_success){
                    window.location.reload();
                }
            }
        });

        $('#purchase_id').select2().change(function(){
            if(return_id && return_id != $(this).val()){
               $("#purchase_id_dropdown_wrapper").append(`<p id="changing-purchase-id-warning" class="text-warning mt10">Note: Changing "Purchase ID" to "<?php echo lang("purchase")?> #${$(this).val()}" will lose your current return changes in "<?php echo lang("purchase")?> #${return_id}"</p>`);
            }

            if(return_id && return_id == $(this).val()){
               $("#changing-purchase-id-warning").remove();
            }
        });
    });
    
</script>