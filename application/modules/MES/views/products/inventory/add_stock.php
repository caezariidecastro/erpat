<?php echo form_open(get_uri("mes/ProductInventory/add_stock"), array("id" => "inventory-override-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="warehouse_id" value="<?= $warehouse_id?>">
    <input type="hidden" name="inventory_id" value="<?= $inventory_id?>">
    <div class="form-group">
        <label for="quantity" class="col-md-3"><?php echo lang('quantity'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "quantity",
                "name" => "quantity",
                "value" => "",
                "type" => "number",
                "class" => "form-control",
                "placeholder" => lang('quantity'),
                "autofocus" => true,
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
        $("#inventory-override-form").appForm({
            onSuccess: function (result) {
                $("#inventory-table").appTable({reload: true});
                $("#items-table").appTable({reload: true});
            }
        });

        $('.select2').select2();
    });
    
</script>