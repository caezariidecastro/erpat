<div class="page-title clearfix">
    <div class="title-button-group">
        <?php echo modal_anchor(get_uri("finance/Loans/modal_form_fee"), "<i class='fa fa-money'></i> " . lang('add_fee'), array("class" => "btn btn-default", "title" => lang('add_fee'))); ?>
    </div>
</div>
<div class="table-responsive">
    <table id="list-fees-table" class="display" cellspacing="0" width="100%">            
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#list-fees-table").appTable({
            source: '<?php echo_uri("finance/Loans/list_fees") ?>',
            filterDropdown: [
                {name: "user_id", class: "w200", options: <?php echo $team_members_dropdown; ?>},
            ],
            dateRangeType: "monthly",
            columns: [
                {title: '<?php echo lang("loan") ?>', "class": "w10p"},
                {title: '<?php echo lang("borrower") ?>', "class": "w20p"},
                {title: '<?php echo lang("item_name") ?>', "class": "w20p"},
                {title: '<?php echo lang("amount") ?>', "class": "w10p text-right"},
                {title: '<?php echo lang("remarks") ?>', "class": "w20p"},
                {title: '<?php echo lang("executed_by") ?>', "class": "w20p"},
                {title: '<?php echo lang("date_paid") ?>', "class": "w10p"},
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

