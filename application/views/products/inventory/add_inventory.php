<?php echo form_open(get_uri("sales/ProductInventory/add_inventory"), array("id" => "inventory-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="form-group">
        <label for="warehouse" class="col-md-3"><?php echo lang('warehouse'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("warehouse", $warehouse_dropdown, "", "class='select2 validate-hidden' id='warehouse' data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>
    </div>
    <input type="hidden" name="item" id="add_item">
    <div class="form-group">
        <label for="opening_stock" class="col-md-3"><?php echo lang('opening_stock'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "opening_stock",
                "name" => "opening_stock",
                "value" => "",
                "class" => "form-control",
                "placeholder" => lang('opening_stock'),
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
        $("#inventory-form").appForm({
            onSuccess: function (result) {
                $("#inventory-table").appTable({reload: true});
                $("#items-table").appTable({reload: true});
            },
            onSubmit: function(){
                $('#add_item').val($('#item_id').val());
            }
        });

        $('.select2').select2();
    });
    
</script>