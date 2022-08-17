<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang('rack'); ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("labels/modal_form"), "<i class='fa fa-tags'></i> " . lang('manage_labels'), array("class" => "btn btn-default", "title" => lang('manage_labels'), "data-post-type" => "racks")); ?>
                <?php echo modal_anchor(get_uri("lds/racks/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_rack'), array("class" => "btn btn-default", "title" => lang('add_rack'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="rack-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#rack-table").appTable({
            source: '<?php echo_uri("lds/racks/list_data") ?>',
            order: [[0, 'desc']],
            filterDropdown: [
                {name: "labels_select2_filter", class: "w200", options: <?php echo $racks_labels_dropdown; ?>}, {id: "zone_select2_filter", name: "zone_select2_filter", class: "w200", options: <?php echo json_encode($zone_select2); ?>}, {id: "warehouse_select2_filter", name: "warehouse_select2_filter", class: "w200", options: <?php echo json_encode($warehouse_select2); ?>},
            ],
            columns: [
                {title: "<?php echo lang('rack_id') ?> "},
                {title: "<?php echo lang('warehouse') ?> "},
                {title: "<?php echo lang('zone') ?> "},
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
            printColumns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
        });
    });
</script>