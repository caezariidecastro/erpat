<div class="panel panel-default">
    <div class="page-title clearfix">
        <div class="title-button-group">
            <?php echo modal_anchor(get_uri("labels/modal_form"), "<i class='fa fa-tags'></i> " . lang('manage_labels'), array("class" => "btn btn-default", "title" => lang('manage_labels'), "data-post-type" => "zones")); ?>
            <?php echo modal_anchor(get_uri("inventory/Zones/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_zone'), array("class" => "btn btn-default", "title" => lang('add_zone'))); ?>
        </div>
    </div>
    <div class="table-responsive">
        <table id="zone-table" class="display" cellspacing="0" width="100%">            
        </table>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#zone-table").appTable({
            source: '<?php echo_uri("inventory/Zones/list_data/".$warehouse_id) ?>',
            order: [[0, 'desc']],
            filterDropdown: [
                {name: "labels_select2_filter", class: "w100", options: <?php echo $zones_labels_dropdown; ?>}, 
                {name: "status_select2_filter", class: "w100", options: <?php echo json_encode($status_select2); ?>}, 
                <?php if(!$warehouse_id) { ?>
                    {id: "warehouse_select2_filter", name: "warehouse_select2_filter", class: "w150", options: <?php echo json_encode($warehouse_select2); ?>}
                <?php } ?>
            ],
            columns: [
                {title: "<?php echo lang('zone_id') ?> "},
                {title: "<?php echo lang('qrcode') ?>"},
                {title: "<?php echo lang('barcode') ?>"},
                {title: "<?php echo lang('rfid') ?>"},
                {title: "<?php echo lang('labels') ?>"},
                {title: "<?php echo lang('remarks') ?>"},
                {title: "<?php echo lang('status') ?>"},
                {title: "<?php echo lang('date_created') ?>"},
                {title: "<?php echo lang('created_by') ?>",},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
            tableRefreshButton: true,
        });
    });
</script>