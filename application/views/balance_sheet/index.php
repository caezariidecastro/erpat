<div class="table-responsive">
    <table id="balance-sheet-table" class="display" cellspacing="0" width="100%">            
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#balance-sheet-table").appTable({
            source: '<?php echo_uri("fas/balance_sheet/list_data") ?>',
            order: [[0, 'desc']],
            columns: [
                {title: "<?php echo lang('account_name') ?> "},
                {title: "<?php echo lang('account_number') ?>"},
                {title: "<?php echo lang('debit') ?>", "class": "text-right"},
                {title: "<?php echo lang('credit') ?>", "class": "text-right"},
                {title: "<?php echo lang('balance') ?>", "class": "text-right"},
            ],
            printColumns: [0, 1, 2, 3, 4],
            xlsColumns: [0, 1, 2, 3, 4],
        });
    });
</script>