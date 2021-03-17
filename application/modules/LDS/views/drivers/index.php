<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang('drivers'); ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("lds/drivers/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_driver'), array("class" => "btn btn-default", "title" => lang('add_driver'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="driver-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#driver-table").appTable({
            source: '<?php echo_uri("lds/drivers/list_data") ?>',
            order: [[0, 'desc']],
            columns: [
                {title: "<?php echo lang('name') ?> "},
                {title: "<?php echo lang('license_number') ?>"},
                {title: "<?php echo lang('license_image') ?>"},
                {title: "<?php echo lang('total_deliveries') ?>"},
                {title: "<?php echo lang('created_on') ?>"},
                {title: "<?php echo lang('created_by') ?>"},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
        });
    });
</script>