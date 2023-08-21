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

<div id="page-content" class="clearfix p20" style="
    margin-top: 10px;
    padding-top: 15px;
    border-top: 1px dashed #b4bdc1 !important;
 ">
    <div class="panel clearfix">
        <ul id="expenses-tabs" data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab"><h5 class="pl15 pt10 pr15"><?php echo lang("credited_transactions"); ?></h5></li>
        </ul>

        <div id="subtable" class="table-responsive">
            <table id="transaction-logs-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    loadExpensesTable = function (selector, dateRange) {

        var config = {
            source: '<?php echo_uri("expenses_payments/payment_list_data") ?>',
            order: [[0, "asc"]],
            columns: [
                {visible: false, searchable: false}, //expense_detials
                {title: '<?php echo lang("date") ?>', "iDataSort": 0},
                {visible: false, searchable: false}, //date
                {title: '<?php echo lang("payment_method") ?>'},
                {title: '<?php echo lang("note") ?>'},
                {title: '<?php echo lang("total") ?>', "class": "text-right"},
                {visible: false, searchable: false} //actions
            ],
            summation: [{column: 5, dataType: 'currency'}, {column: 6, dataType: 'currency'}]
        };

        $(selector).appTable(config);
    };

    $(document).ready(function () {
        loadExpensesTable("#transaction-logs-table");
    });
</script>