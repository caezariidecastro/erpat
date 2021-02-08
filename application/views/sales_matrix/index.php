<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang('sales_matrix'); ?></h1>
        </div>
        <div class="table-responsive">
            <table id="sales-matrix-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#sales-matrix-table").appTable({
            source: '<?php echo_uri("sales_matrix/list_data") ?>',
            rangeDatepicker: [{startDate: {name: "start_date", value: ""}, endDate: {name: "end_date", value: moment().format("YYYY-MM-DD")}, showClearButton: true}],
            order: [[0, 'desc']],
            columns: [
                {title: "<?php echo lang('product') ?> "},
                {title: "<?php echo lang('category') ?>"},
                {title: "<?php echo lang('total_sales') ?>",},
            ],
            printColumns: [0, 1, 2],
            xlsColumns: [0, 1, 2]
        });
    });
</script>