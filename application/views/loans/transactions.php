
<div class="table-responsive">
    <table id="list-transactions-table" class="display" cellspacing="0" width="100%">            
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#list-transactions-table").appTable({
            source: '<?php echo_uri("finance/Loans/list_transactions") ?>',
            dateRangeType: "weekly",
            columns: [
                {title: '<?php echo lang("loan") ?>', "class": "w10p"},
                {title: '<?php echo lang("borrower") ?>'},
                {title: '<?php echo lang("stage") ?>', "class": "w20p"},
                {title: '<?php echo lang("remarks") ?>', "class": "w20p"},
                {title: '<?php echo lang("executed_by") ?>', "class": "w20p"},
                {title: '<?php echo lang("timestamp") ?>', "class": "w15p"},
            ],
            printColumns: [0, 1, 2, 3, 4],
            xlsColumns: [0, 1, 2, 3, 4],
            tableRefreshButton: true,
        });
    });
</script>

