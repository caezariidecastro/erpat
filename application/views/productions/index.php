<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang('productions'); ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("productions/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_production'), array("class" => "btn btn-default", "title" => lang('add_production'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="production-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#production-table").appTable({
            source: '<?php echo_uri("productions/list_data") ?>',
            filterDropdown: [
                {id: "warehouse_select2_filter", name: "warehouse_select2_filter", class: "w200", options: <?php echo json_encode($warehouse_select2); ?>},
            ],
            dateRangeType: "monthly",
            order: [[0, 'desc']],
            columns: [
                {title: "<?php echo lang('bill_of_material') ?> ", "class": "w20p"},
                {title: "<?php echo lang('output') ?>"},
                {title: "<?php echo lang('warehouse') ?>"},
                {title: "<?php echo lang('quantity') ?>"},
                {title: "<?php echo lang('status') ?>"},
                {title: "<?php echo lang('created_by') ?>",},
                {title: "<?php echo lang('created_on') ?>",},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5],
            xlsColumns: [0, 1, 2, 3, 4, 5]
        });
    });
</script>