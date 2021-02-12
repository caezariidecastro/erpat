<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang('account_transfers'); ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("account_transfers/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_account_transfer'), array("class" => "btn btn-default", "title" => lang('add_account_transfer'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="account-transfers-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#account-transfers-table").appTable({
            source: '<?php echo_uri("account_transfers/list_data") ?>',
            dateRangeType: "monthly",
            order: [[0, 'desc']],
            columns: [
                {title: "<?php echo lang('date') ?> "},
                {title: "<?php echo lang('from') ?>"},
                {title: "<?php echo lang('to') ?>"},
                {title: "<?php echo lang('amount') ?>"},
                {title: "<?php echo lang('note') ?>"},
                {title: "<?php echo lang('date_created') ?>",},
                {title: "<?php echo lang('created_by') ?>",},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5],
            xlsColumns: [0, 1, 2, 3, 4, 5],
        });
    });
</script>