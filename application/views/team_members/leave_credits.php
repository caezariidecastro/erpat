<div class="table-responsive">
    <table id="leave-credit-table" class="display" cellspacing="0" width="100%">            
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#leave-credit-table").appTable({
            source: '<?php echo_uri("hrs/leave_credits/list_data") ?>',
            filterParams: {user_id: "<?php echo $applicant_id; ?>"},
            radioButtons: [{text: '<?php echo lang("debit") ?>', name: "action", value: "debit", isChecked: false}, {text: '<?php echo lang("credit") ?>', name: "action", value: "credit", isChecked: false}],
            rangeDatepicker: [{startDate: {name: "start_date", value: moment().format("YYYY-MM-DD")}, endDate: {name: "end_date", value: moment().format("YYYY-MM-DD")}}],
            columns: [
                {title: '<?php echo lang("employee"); ?>'},
                {title: '<?php echo lang("action"); ?>'},
                {title: '<?php echo lang("days"); ?>'},
                {title: '<?php echo lang("remarks"); ?>'}, //if no ref. leaves, then auto fill with, Generated.
                {title: '<?php echo lang("date_created"); ?>'},
                {title: '<?php echo lang("created_by"); ?>'},
                // {title: '<?php //echo lang("type"); ?>'},
                // {title: '<?php //echo lang("date"); ?>'},
                // {title: '<?php //echo lang("approve_date"); ?>'},
                // {title: '<?php //echo lang("approve_by"); ?>'},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3],
            xlsColumns: [0, 1, 2, 3],
            summation: [{column: 2, dataType: 'number'}]
        });
    });
</script>