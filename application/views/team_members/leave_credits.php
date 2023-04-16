<div class="table-responsive">
    <table id="leave-credit-table" class="display" cellspacing="0" width="100%">            
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#leave-credit-table").appTable({
            source: '<?php echo_uri("hrs/leave_credits/list_data") ?>',
            filterParams: {user_id: "<?php echo $applicant_id; ?>"},
            filterDropdown: [
                {name: "action", class: "w10", options: <?= json_encode(array(
                        array('id' => '', 'text'  => '- Transactions -'),
                        array('id' => 'balance', 'text'  => '- Balance -'),
                        array('id' => 'debit', 'text'  => '- Debit Only -'),
                        array('id' => 'credit', 'text'  => '- Credit Only -')
                    )); ?> 
                },
            ],
            columns: [
                {title: '<?php echo lang("employee"); ?>'},
                {title: '<?php echo lang("action"); ?>'},
                {title: '<?php echo lang("days"); ?>'},
                {title: '<?php echo lang("remarks"); ?>'}, //if no ref. leaves, then auto fill with, Generated.
                {title: '<?php echo lang("date_created"); ?>'},
                {title: '<?php echo lang("created_by"); ?>'},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3],
            xlsColumns: [0, 1, 2, 3],
            summation: [{column: 2, dataType: 'number'}],
        });
    });
</script>