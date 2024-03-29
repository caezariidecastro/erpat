<div id="page-content" class="clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("labels/modal_form"), "<i class='fa fa-tags'></i> " . lang('manage_labels'), array("class" => "btn btn-default", "title" => lang('manage_labels'), "data-post-type" => "pallets")); ?>
                <?php echo modal_anchor(get_uri("inventory/Pallets/export_modal_form"), "<i class='fa fa-file'></i> " . lang('export_barcode'), array("class" => "btn btn-default", "title" => lang('export_barcode'))); ?>
                <?php echo modal_anchor(get_uri("inventory/Pallets/bulk_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('bulk_add'), array("class" => "btn btn-default", "title" => lang('bulk_add'))); ?>
                <?php echo modal_anchor(get_uri("inventory/Pallets/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_pallet'), array("class" => "btn btn-default", "title" => lang('add_pallet'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="pallet-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#pallet-table").appTable({
            source: '<?php echo_uri("inventory/Pallets/list_data/".$warehouse_id) ?>',
            order: [[0, 'desc']],
            filterDropdown: [
                {name: "labels_select2_filter", class: "w100", options: <?php echo $pallets_labels_dropdown; ?>}, 
                {name: "status_select2_filter", class: "w100", options: <?php echo json_encode($status_select2); ?>}, 
                {id: "bay_select2_filter", name: "bay_select2_filter", class: "w100", options: <?php echo json_encode($bay_select2); ?>}, 
                {id: "rack_select2_filter", name: "rack_select2_filter", class: "w100", options: <?php echo json_encode($rack_select2); ?>}, 
                {id: "zone_select2_filter", name: "zone_select2_filter", class: "w200", options: <?php echo json_encode($zone_select2); ?>}, 
            ],
            columns: [
                {title: "<?php echo lang('pallet_id') ?> ", "class": "text-center w100"},
                {title: "<?php echo lang('qrcode') ?>", "class": "text-center w100"},
                {title: "<?php echo lang('barcode') ?>", "class": "text-center w100"},
                {title: "<?php echo lang('rfid') ?>"},
                {title: "<?php echo lang('zone') ?> "},
                {title: "<?php echo lang('rack') ?> "},
                {title: "<?php echo lang('bay') ?> "},
                {title: "<?php echo lang('level') ?> "},
                {title: "<?php echo lang('position') ?> "},
                {title: "<?php echo lang('labels') ?>"},
                {title: "<?php echo lang('remarks') ?>"},
                {title: "<?php echo lang('status') ?>"},
                {title: "<?php echo lang('date_created') ?>"},
                {title: "<?php echo lang('created_by') ?>",},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14],
            tableRefreshButton: true,
        });
    });
</script>