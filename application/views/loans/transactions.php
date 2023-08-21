
<div class="page-title clearfix">
    <div class="title-button-group">
        <?php echo modal_anchor(get_uri("finance/Loans/modal_form_update"), "<i class='fa fa-bolt'></i> " . lang('update_status'), array("class" => "btn btn-default", "title" => lang('update_status'))); ?>
    </div>
</div>
<div class="table-responsive">
    <table id="list-transactions-table" class="display" cellspacing="0" width="100%">            
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#list-transactions-table").appTable({
            source: '<?php echo_uri("finance/Loans/list_transactions") ?>',
            filterDropdown: [
                {name: "user_id", class: "w200", options: <?php echo $team_members_dropdown; ?>},
            ],
            dateRangeType: "monthly",
            columns: [
                {title: '<?php echo lang("loan") ?>', "class": "w10p"},
                {title: '<?php echo lang("borrower") ?>'},
                {title: '<?php echo lang("stage_name") ?>', "class": "w20p"},
                {title: '<?php echo lang("remarks") ?>', "class": "w20p"},
                {title: '<?php echo lang("executed_by") ?>', "class": "w20p"},
                {title: '<?php echo lang("timestamp") ?>', "class": "w15p"},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4],
            xlsColumns: [0, 1, 2, 3, 4],
            tableRefreshButton: true,
        });
    });
</script>

