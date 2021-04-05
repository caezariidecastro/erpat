<style type="text/css">
    .datatable-tools:first-child {
        display:  none;
    }
    #add-item-transfer-section{
        display: <?php echo $model_info->id  ? "block" : "none" ?>;
    }
</style>

<?php echo form_open(get_uri("lds/TransferRawMaterials/save"), array("id" => "inventory-transfers-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info ? $model_info->id : "" ?>" />
    <?php if($model_info->id){?>
    <div class="form-group">
        <label for="status" class="col-md-3"><?php echo lang('status'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("status", $status_dropdown, $model_info ? $model_info->status : "", "class='select2 validate-hidden' id='status' data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>
    </div>
    <?php }?>
    <div class="form-group">
        <label for="reference_number" class="col-md-3"><?php echo lang('reference_number'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "reference_number",
                "name" => "reference_number",
                "value" => $model_info->id ? $model_info->reference_number : getToken(12),
                "class" => "form-control",
                "placeholder" => lang('reference_number'),
                "disabled" => "disabled",
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="transferee" class="col-md-3"><?php echo lang('from'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("transferee", $warehouse_dropdown, $model_info ? $model_info->transferee : "", "class='select2 validate-hidden' id='transferee' data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="receiver" class="col-md-3"><?php echo lang('to'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("receiver", $warehouse_dropdown, $model_info ? $model_info->receiver : "", "class='select2 validate-hidden' id='receiver' data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="dispatcher" class="col-md-3"><?php echo lang('dispatcher'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("dispatcher", $user_dropdown, $model_info ? $model_info->dispatcher : "", "class='select2 validate-hidden' id='dispatcher' data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="driver" class="col-md-3"><?php echo lang('driver'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("driver", $driver_dropdown, $model_info ? $model_info->driver : "", "class='select2 validate-hidden' id='driver' data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="vehicle_id" class="col-md-3"><?php echo lang('vehicle'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("vehicle_id", $vehicle_dropdown, $model_info ? $model_info->vehicle_id : "", "class='select2 validate-hidden' id='vehicle_id' data-rule-required='true' data-msg-required='".lang("field_required")."'");
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
                "data-rich-text-editor" => true
            ));
            ?>
        </div>
    </div>
    <hr>
    <div id="add-item-transfer-section">
        <div class="form-group">
            <label for="item" class="col-md-3"><?php echo lang('material'); ?></label>
            <div class="col-md-9" id="item-selection-section">
                <?php
                echo form_input(array(
                    "id" => "item",
                    "name" => "",
                    "value" => "",
                    "class" => "form-control",
                    "placeholder" => lang('product')
                ));
                ?>
            </div>
        </div>
        <div class="form-group">
            <label for="quantity" class="col-md-3"><?php echo lang('quantity'); ?></label>
            <div class=" col-md-6">
                <?php
                echo form_input(array(
                    "id" => "quantity",
                    "name" => "",
                    "value" => '',
                    "class" => "form-control",
                    "placeholder" => lang('quantity'),
                    "type" => 'number'
                ));
                ?>
            </div>
            <div class=" col-md-3">
                <?php
                echo form_input(array(
                    "id" => "unit_type",
                    "name" => "",
                    "value" => '',
                    "class" => "form-control",
                    "placeholder" => lang('unit_type'),
                    "type" => 'text',
                    "disabled" => "disabled"
                ));
                ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-12">
                <button type="button" class="btn btn-default pull-right" onclick="add_item_on_item_transfer_table()"><span class="fa fa-plus-circle"></span> <?php echo lang('add'); ?></button>
            </div>
        </div>
        <hr>
    </div>
    <div class="form-group">
        <div class="pull-right mb15">
            <input type="text" id="search-items" class="datatable-search" placeholder="<?php echo lang('search') ?>">
        </div>
        <div class="table-responsive">
            <table id="inventory-transfers-items-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    let itemsTable;

    $(document).ready(function () {
        $("#inventory-transfers-items-table").appTable({
            source: '<?php echo_uri("lds/TransferRawMaterials/get_transferred_items/".($model_info ? $model_info->reference_number : "")) ?>',
            order: [[0, 'desc']],
            columns: [
                {visible: false},
                {title: "<?php echo lang('name') ?> "},
                {title: "<?php echo lang('quantity') ?> "},
                {title: "<?php echo lang('unit_type') ?> "},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
        });

        itemsTable = $('#inventory-transfers-items-table').DataTable();
        $('#search-items').keyup(function () {
            itemsTable.search($(this).val()).draw();
        });

        $("#inventory-transfers-form").appForm({
            onSuccess: function (result) {
                $("#material-inventory-transfers-table").appTable({newData: result.data, dataId: result.id});
                //$("#inventory-transfers-table").appTable({newData: result.data, dataId: result.id});
            },
            onSubmit: function (data, self, options) {
                $('#reference_number').removeAttr('disabled');
                $('input[name^=inventory_items]').remove();

                let itemsTableData = itemsTable.rows().data();

                for(let i = 0; i < itemsTableData.length; i++){
                    let properties = {
                        id: itemsTableData[i][0],
                        value: itemsTableData[i][2]
                    };
                    
                    $("<input/>").attr("type", 'hidden')
                                .attr("name", "inventory_items[]")
                                .attr("value", JSON.stringify(properties))
                                .appendTo("#inventory-transfers-form");
                }

            },
            onError: function (result) {
                $('#reference_number').attr('disabled', 'disabled');
                return true;
            }
        });

        $('#receiver').select2();
        $('#dispatcher').select2();
        $('#driver').select2();
        $('#vehicle_id').select2();
        $('#item').select2({data: <?php echo json_encode($warehouse_item_select2)?>}).change(function(e){
            //console.log(e);
            if($(this).val()){
                $("#unit_type").val(e.added.unit_type);
            }
        });
        $("#status").select2();

        $("#transferee").select2().on("change", function () {
            let warehouse_id = $(this).val();

            if (warehouse_id) {
                $('#item').select2("destroy");
                $("#item").hide();
                appLoader.show({container: "#item-selection-section"});

                $.ajax({
                    url: "<?php echo get_uri("lds/TransferRawMaterials/get_inventory_items_select2_data") ?>" + `/${warehouse_id}/json`,
                    dataType: "json",
                    success: function (result) {
                        $("#item").show().val("");
                        $('#item').select2({data: result});
                        appLoader.hide();

                        $('#add-item-transfer-section').show();
                        itemsTable.clear().draw();
                    }
                });
            }
        });

        $("#inventory-transfers-items-table tbody").on("click", "a", function () {
            itemsTable
                .row($(this).parents('tr'))
                .remove()
                .draw();
        });
    });
    

    function add_item_on_item_transfer_table(){
        let item = $('#item').select2('val');
        let item_data = $('#item').select2('data');
        let quantity = $('#quantity').val();

        if(!item || !quantity){
            $('#item-error').remove();
            $('#quantity-error').remove();

            if(!item){
                $('#item').after('<span id="item-error" class="help-block text-danger">This field is required.</span>');

                $('#item').on('change', function(){
                    let temp_item = $(this).select2('val');
                    $('#item-error').remove();

                    if(!temp_item){
                        $('#item').after('<span id="item-error" class="help-block text-danger">This field is required.</span>');
                    }
                });
            }

            if(!quantity){
                $('#quantity').after('<span id="quantity-error" class="help-block text-danger">This field is required.</span>');

                $('#quantity').keyup(function(){
                    let temp_quantity = $('#quantity').val();
                    $('#quantity-error').remove();

                    if(!temp_quantity){
                        $('#quantity').after('<span id="quantity-error" class="help-block text-danger">This field is required.</span>');
                    }
                });
            }
        }
        else{
            let text = item_data.text.substr(0, item_data.text.indexOf("("));

            itemsTable.row.add([
                item_data.id,
                text,
                quantity,
                item_data.unit_type,
                '<a href="#" title="Delete" class="delete"><i class="fa fa-times fa-fw"></i></a>'
            ]).draw();

            $('#item').select2('val', '');
            $('#quantity').val('');
            $('#unit_type').val('');
        }
    }
    
</script>