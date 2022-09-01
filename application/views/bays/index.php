<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?= lang('current_rack'); ?>: <label class="rack_id"><?= isset($rack_id)?get_id_name($rack_id, "R"):"All" ?></label></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("labels/modal_form"), "<i class='fa fa-tags'></i> " . lang('manage_labels'), array("class" => "btn btn-default", "title" => lang('manage_labels'), "data-post-type" => "bays")); ?>
                <?php echo modal_anchor(get_uri("lds/bays/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_bay'), array("class" => "btn btn-default", "title" => lang('add_bay'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="bay-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#bay-table").appTable({
            source: '<?php echo_uri("lds/bays/list_data/".$rack_id) ?>',
            order: [[0, 'desc']],
            filterDropdown: [
                {name: "labels_select2_filter", class: "w150", options: <?php echo $bays_labels_dropdown; ?>}, 
                {name: "status_select2_filter", class: "w100", options: <?php echo json_encode($status_select2); ?>}, 
                //{id: "rack_select2_filter", name: "rack_select2_filter", class: "w120", options: <?php //echo json_encode($rack_select2); ?>}, 
                //{id: "zone_select2_filter", name: "zone_select2_filter", class: "w120", options: <?php //echo json_encode($zone_select2); ?>}, 
                //{id: "warehouse_select2_filter", name: "warehouse_select2_filter", class: "w150", options: <?php //echo json_encode($warehouse_select2); ?>},
            ],
            columns: [
                {title: "<?php echo lang('bay_id') ?> "},
                {title: "<?php echo lang('warehouse') ?> "},
                {title: "<?php echo lang('zone') ?> "},
                {title: "<?php echo lang('rack') ?> "},
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
            printColumns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
        });
    });
</script>