<?php echo form_open(get_uri("mes/RawMaterialInventory/add_material_inventory"), array("id" => "material-inventory-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="form-group">
        <label for="warehouse" class="col-md-3"><?php echo lang('warehouse'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("warehouse", $warehouse_dropdown, "", "class='select2 validate-hidden' id='warehouse' data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>
    </div>
    <input type="hidden" name="material" id="add_material">
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
        $("#material-inventory-form").appForm({
            onSuccess: function (result) {
                $("#material-inventory-table").appTable({reload: true});
                $("#materials-table").appTable({reload: true});
            },
            onSubmit: function(){
                $('#add_material').val($('#material_id').val());
            }
        });

        $('.select2').select2();
    });
    
</script>