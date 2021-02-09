<?php echo form_open(get_uri("invoices/save_item"), array("id" => "invoice-item-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <input type="hidden" name="invoice_id" value="<?php echo $invoice_id; ?>" />
    <input type="hidden" name="delivery_reference_no" value="<?= $delivery_info ? $delivery_info->reference_number : "" ?>" />
    <input type="hidden" id="inventory_id" name="inventory_id" value="<?php echo $model_info ? $model_info->inventory_id : "" ?>" />
    <div class="form-group">
        <div class="col-md-12 text-off"> <?php echo lang('inactive_hidden'); ?></div>
    </div>
    <div class="form-group">
        <label for="invoice_item_title" class=" col-md-3"><?php echo lang('product'); ?></label>
        <div class="col-md-9" id="invoice_item_title_wrapper">
            <?php
            echo form_input(array(
                "id" => "invoice_item_title",
                "name" => "invoice_item_title",
                "value" => $model_info->title,
                "class" => "form-control validate-hidden",
                "placeholder" => lang('product'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
            <a id="invoice_item_title_dropdwon_icon" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span>×</span></a>
        </div>
    </div>
    <div class="form-group">
        <label for="invoice_item_quantity" class=" col-md-3"><?php echo lang('quantity'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "invoice_item_quantity",
                "name" => "invoice_item_quantity",
                "value" => $model_info->quantity ? to_decimal_format($model_info->quantity) : "",
                "class" => "form-control",
                "placeholder" => lang('quantity'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="invoice_unit_type" class=" col-md-3"><?php echo lang('unit_type'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "invoice_unit_type",
                "name" => "invoice_unit_type",
                "value" => $model_info->unit_type,
                "class" => "form-control",
                "placeholder" => lang('unit_type') . ' (Ex: hours, pc, etc.)'
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="invoice_item_rate" class=" col-md-3"><?php echo lang('amount'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "invoice_item_rate",
                "name" => "invoice_item_rate",
                "value" => $model_info->rate ? to_decimal_format($model_info->rate) : "",
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
        $("#invoice-item-form").appForm({
            onSuccess: function (result) {
                $("#invoice-item-table").appTable({newData: result.data, dataId: result.id});
                $("#invoice-total-section").html(result.invoice_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.invoice_id);
                }
            }
        });

        var isUpdate = "<?php echo $model_info->id; ?>";
        if (!isUpdate) {
            applySelect2OnItemTitle();
        }

        //re-initialize item suggestion dropdown on request
        $("#invoice_item_title_dropdwon_icon").click(function () {
            applySelect2OnItemTitle();
        });

    });
    
    function applySelect2OnItemTitle(){
        $.ajax({
            url: "<?php echo get_uri("invoices/get_inventory_items_select2_data/"). ($delivery_info ? $delivery_info->warehouse : ""); ?>", 
            dataType: 'json',
            success: function(data){
                $("#invoice_item_title").select2({data: data}).change(function (e) {
                    let inventory_id = e.added.inventory_id;
                    $('#inventory_id').val(inventory_id);
        
                    $.ajax({
                        url: "<?php echo get_uri("inventory/get_inventory"); ?>",
                        data: {id: inventory_id},
                        cache: false,
                        type: 'POST',
                        dataType: "json",
                        success: function (response) {
                            if (response && response.success) {
                                $("#invoice_unit_type").val(response.inventory_info.unit_name);
                                $("#invoice_item_rate").val(response.inventory_info.selling_price);
                            }
                        }
                    });
                });
            }
        })
    }
</script>