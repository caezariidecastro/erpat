<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang('vehicles'); ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("vehicles/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_vehicle'), array("class" => "btn btn-default", "title" => lang('add_vehicle'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="vehicle-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#vehicle-table").appTable({
            source: '<?php echo_uri("vehicles/list_data") ?>',
            order: [[0, 'desc']],
            columns: [
                {title: "<?php echo lang('brand') ?> "},
                {title: "<?php echo lang('model') ?>"},
                {title: "<?php echo lang('year') ?>"},
                {title: "<?php echo lang('color') ?>"},
                {title: "<?php echo lang('transmission') ?>"},
                {title: "<?php echo lang('no_of_wheels') ?>"},
                {title: "<?php echo lang('plate_number') ?>"},
                {title: "<?php echo lang('max_cargo_weight') ?>"},
                {title: "<?php echo lang('created_by') ?>",},
                {title: "<?php echo lang('created_on') ?>",},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5, 6, 7],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6, 7]
        });
    });
</script>