<style type="text/css">
    .datatable-tools:first-child {
        display:  none;
    }
    #add-item-delivery-section{
        display: <?php echo $model_info->id  ? "block" : "none" ?>;
    }
</style>

<?php echo form_open(get_uri("deliveries/save"), array("id" => "deliveries-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info ? $model_info->id : "" ?>" />
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
        <label for="warehouse" class="col-md-3"><?php echo lang('warehouse'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("warehouse", $warehouse_dropdown, $model_info ? $model_info->warehouse : "", "class='select2 validate-hidden' id='warehouse' data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="consumer" class="col-md-3"><?php echo lang('consumer'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("consumer", $consumer_dropdown, $model_info ? $model_info->consumer : "", "class='select2 validate-hidden' id='consumer' data-rule-required='true' data-msg-required='".lang("field_required")."'");
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
            echo form_dropdown("driver", $user_dropdown, $model_info ? $model_info->driver : "", "class='select2 validate-hidden' id='driver' data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="vehicle" class="col-md-3"><?php echo lang('vehicle'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("vehicle", $vehicle_dropdown, $model_info ? $model_info->vehicle : "", "class='select2 validate-hidden' id='vehicle' data-rule-required='true' data-msg-required='".lang("field_required")."'");
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
    <div id="add-item-delivery-section">
        <div class="form-group">
            <label for="item" class="col-md-3"><?php echo lang('item'); ?></label>
            <div class="col-md-9" id="item-selection-section">
                <?php
                echo form_input(array(
                    "id" => "item",
                    "name" => "",
                    "value" => "",
                    "class" => "form-control",
                    "placeholder" => lang('item')
                ));
                ?>
            </div>
        </div>
        <div class="form-group">
            <label for="quantity" class="col-md-3"><?php echo lang('quantity'); ?></label>
            <div class=" col-md-9">
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
        </div>
        <div class="form-group">
            <div class="col-md-12">
                <button type="button" class="btn btn-default pull-right" onclick="add_item_on_item_delivery_table()"><span class="fa fa-plus-circle"></span> <?php echo lang('add'); ?></button>
            </div>
        </div>
        <hr>
    </div>
    <div class="form-group">
        <div class="pull-right mb15">
            <input type="text" id="search-items" class="datatable-search" placeholder="<?php echo lang('search') ?>">
        </div>
        <div class="table-responsive">
            <table id="deliveries-items-table" class="display" cellspacing="0" width="100%">            
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
        $("#deliveries-items-table").appTable({
            source: '<?php echo_uri("deliveries/get_delivered_items/".($model_info ? $model_info->reference_number : "")) ?>',
            order: [[0, 'desc']],
            columns: [
                {visible: false},
                {title: "<?php echo lang('name') ?> "},
                {title: "<?php echo lang('quantity') ?> "},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
        });

        itemsTable = $('#deliveries-items-table').DataTable();
        $('#search-items').keyup(function () {
            itemsTable.search($(this).val()).draw();
        });

        $("#deliveries-form").appForm({
            onSuccess: function (result) {
                $("#deliveries-table").appTable({newData: result.data, dataId: result.id});
            },
            onSubmit: function (data, self, options) {
                $('#reference_number').removeAttr('disabled');

                let itemsTableData = itemsTable.rows().data();

                for(let i = 0; i < itemsTableData.length; i++){
                    let properties = {
                        id: itemsTableData[i][0],
                        value: itemsTableData[i][2]
                    };
                    
                    $("<input/>").attr("type", 'hidden')
                                .attr("name", "delivery_items[]")
                                .attr("value", JSON.stringify(properties))
                                .appendTo("#deliveries-form");
                }

            },
            onError: function (result) {
                $('#reference_number').attr('disabled', 'disabled');
                return true;
            }
        });

        $('#consumer').select2();
        $('#dispatcher').select2();
        $('#driver').select2();
        $('#vehicle').select2();
        $('#item').select2({data: <?php echo json_encode($warehouse_item_select2)?>});

        $("#warehouse").select2().on("change", function () {
            let warehouse_id = $(this).val();

            if (warehouse_id) {
                $('#item').select2("destroy");
                $("#item").hide();
                appLoader.show({container: "#item-selection-section"});

                $.ajax({
                    url: "<?php echo get_uri("deliveries/get_inventory_items_select2_data") ?>" + `/${warehouse_id}/json`,
                    dataType: "json",
                    success: function (result) {
                        $("#item").show().val("");
                        $('#item').select2({data: result});
                        appLoader.hide();

                        $('#add-item-delivery-section').show();
                    }
                });
            }
        });

        $("#deliveries-items-table tbody").on("click", "a", function () {
            itemsTable
                .row($(this).parents('tr'))
                .remove()
                .draw();
        });
    });
    

    function add_item_on_item_delivery_table(){
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
            itemsTable.row.add([
                item_data.id,
                item_data.text,
                quantity,
                '<a href="#" title="Delete" class="delete"><i class="fa fa-times fa-fw"></i></a>'
            ]).draw();

            $('#item').select2('val', '');
            $('#quantity').val('');
        }
    }
    
</script>