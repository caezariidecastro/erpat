<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang('balance_sheet'); ?></h1>
        </div>
        <div class="table-responsive">
            <table id="balance-sheet-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#balance-sheet-table").appTable({
            source: '<?php echo_uri("balance_sheet/list_data") ?>',
            order: [[0, 'desc']],
            columns: [
                {title: "<?php echo lang('account_name') ?> "},
                {title: "<?php echo lang('account_number') ?>"},
                {title: "<?php echo lang('credit') ?>", "class": "text-right"},
                {title: "<?php echo lang('debit') ?>", "class": "text-right"},
                {title: "<?php echo lang('balance') ?>", "class": "text-right"},
            ],
            printColumns: [0, 1, 2, 3, 4],
            xlsColumns: [0, 1, 2, 3, 4],
        });
    });
</script>