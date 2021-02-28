<?php echo form_open(get_uri("purchase_order_returns/save_material"), array("id" => "purchase-order-return-materials-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="purchase_order_return_id" value="<?php echo $id?>" />
    <input type="hidden" name="purchase_id" value="<?php echo $purchase_id?>" />
    <div class="form-group">
        <label for="purchase_order_material_id" class="col-md-3"><?php echo lang('material'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("purchase_order_material_id", $purchase_order_materials_dropdown, null, "class='select2 validate-hidden' id='purchase_order_material_id' data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="quantity" class="col-md-3"><?php echo lang('quantity'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "quantity",
                "name" => "quantity",
                "value" => "",
                "class" => "form-control",
                "placeholder" => lang('quantity'),
                "type" => "number",
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
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
                "value" => "",
                "class" => "form-control",
                "placeholder" => lang('remarks'),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary pull-right"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
        </div>
    </div>
    <hr>
    <div class="table-responsive">
        <table id="purchase-order-return-materials-table" class="display" cellspacing="0" width="100%">            
        </table>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#purchase-order-return-materials-table").appTable({
            source: '<?php echo_uri("purchase_order_returns/material_list_data?purchase_order_return_id=".$id) ?>',
            order: [[0, 'desc']],
            hideTools: true,
            columns: [
                {title: "<?php echo lang('material') ?> "},
                {title: "<?php echo lang('quantity') ?>"},
                {title: "<?php echo lang('remarks') ?>"},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4],
            xlsColumns: [0, 1, 2, 3, 4]
        });

        $("#purchase-order-return-materials-form").appForm({
            closeModalOnSuccess: false,
            onSuccess: function (result) {
                $("#purchase-order-return-materials-table").appTable({newData: result.data, dataId: result.id});

                $(".modal-mask").html("<div class='circle-done'><i class='fa fa-check'></i></div>");
                setTimeout(function () {
                    $(".modal-mask").find('.circle-done').addClass('ok');
                    setTimeout(function(){
                        $('.modal-body').removeClass('hide');
                        $(".modal-mask").remove();
                    }, 500);
                }, 300);

                $('#purchase_order_material_id').select2("val", "");
                $("#remarks").val("");
                $("#quantity").val("");
            }
        });

        $('#purchase_order_material_id').select2();
    });
    
</script>