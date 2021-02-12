<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang('accounts'); ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("accounts/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_account'), array("class" => "btn btn-default", "title" => lang('add_account'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="account-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#account-table").appTable({
            source: '<?php echo_uri("accounts/list_data") ?>',
            order: [[0, 'desc']],
            columns: [
                {title: "<?php echo lang('account_name') ?> ", "class": "w20p"},
                {title: "<?php echo lang('account_number') ?>"},
                {title: "<?php echo lang('initial_balance') ?>", "class": "w100 text-right"},
                {title: "<?php echo lang('remarks') ?>", "class": "w20p"},
                {title: "<?php echo lang('created_by') ?>",},
                {title: "<?php echo lang('date_created') ?>",},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5],
            xlsColumns: [0, 1, 2, 3, 4, 5],
            summation: [{column: 2, dataType: "number"}]
        });
    });
</script>