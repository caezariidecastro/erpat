<div class="panel clearfix">
    <ul id="material-invetory-tabs" data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
        <li><a role="presentation" href="<?php echo_uri("warehouse"); ?>" data-target="#inventory"><?php echo lang('warehouse'); ?></a></li>
        <div class="tab-title clearfix no-border">
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("material_inventory/add_material_inventory_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_inventory'), array("class" => "btn btn-default", "title" => lang('add_inventory'), "id" => "add_inventory_button")); ?>
            </div>
        </div>
    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade" id="inventory">
            <div class="table-responsive">
                <table id="material-inventory-table" class="display no-thead" cellspacing="0" width="100%">   
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('a[data-target="#inventory"]').click();
        
        $("#material-inventory-table").appTable({
            source: '<?php echo_uri("material_inventory/list_data/".$id) ?>',
            columns: [
                {title: "<?php echo lang('title') ?> ", "class": "text-left"},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center dropdown-option w100"}
            ],
        });

        setInterval(function(){
            $("#material-invetory-tabs").find("li.active").text() == "Warehouse" ? $('#add_inventory_button').show() : $('#add_inventory_button').hide()
        }, 200)
    });
</script>