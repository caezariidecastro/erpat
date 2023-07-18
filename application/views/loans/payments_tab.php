<div class="page-title clearfix">
    <div class="title-button-group">
        <?php echo modal_anchor(get_uri("finance/Loans/modal_form_payment"), "<i class='fa fa-money'></i> " . lang('add_payment'), array("class" => "btn btn-default", "title" => lang('add_payment'))); ?>
    </div>
</div>
<div class="table-responsive">
    <table id="list-payments-table" class="display" cellspacing="0" width="100%">            
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#list-payments-table").appTable({
            source: '<?php echo_uri("finance/Loans/list_payments") ?>',
            filterDropdown: [
                {name: "user_id", class: "w200", options: <?php echo $team_members_dropdown; ?>},
            ],
            dateRangeType: "monthly",
            columns: [
                {title: '<?php echo lang("loan") ?>', "class": "w10p"},
                {title: '<?php echo lang("date_paid") ?>', "class": "w10p"},
                {title: '<?php echo lang("borrower") ?>', "class": "w20p"},
                {title: '<?php echo lang("amount") ?>', "class": "w10p text-right"},
                {title: '<?php echo lang("remarks") ?>', "class": "w20p"},
                {title: '<?php echo lang("executed_by") ?>', "class": "w20p"},
                {title: '<?php echo lang("timestamp") ?>', "class": "w15p"},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5, 6],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6],
            summation: [
                {column: 3, dataType: 'number'},
            ],
            tableRefreshButton: true,
        });
    });
</script>

