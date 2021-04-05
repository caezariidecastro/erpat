<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang('warehouse'); ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("lds/warehouses/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_warehouse'), array("class" => "btn btn-default", "title" => lang('add_warehouse'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="warehouse-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#warehouse-table").appTable({
            source: '<?php echo_uri("lds/warehouses/list_data") ?>',
            // dateRangeType: "monthly",
            order: [[0, 'desc']],
            columns: [
                {title: "<?php echo lang('name') ?> "},
                {title: "<?php echo lang('address') ?>"},
                {title: "<?php echo lang('zip_code') ?>"},
                {title: "<?php echo lang('email') ?>"},
                {title: "<?php echo lang('head') ?>"},
                {title: "<?php echo lang('created_by') ?>",},
                {title: "<?php echo lang('date_created') ?>",},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5, 6],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6]
        });
    });
</script>