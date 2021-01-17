<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang('payroll'); ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("payroll/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_payroll'), array("class" => "btn btn-default", "title" => lang('add_payroll'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="payroll-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#payroll-table").appTable({
            source: '<?php echo_uri("payroll/list_data") ?>',
            order: [[7, 'desc']],
            columns: [
                {title: "<?php echo lang('name') ?>"},
                {title: "<?php echo lang('account') ?>"},
                {title: "<?php echo lang('payment_method') ?>"},
                {title: "<?php echo lang('amount') ?>"},
                {title: "<?php echo lang('status') ?>"},
                {title: "<?php echo lang('note') ?>"},
                {title: "<?php echo lang('created_on') ?>",},
                {title: "<?php echo lang('created_by') ?>",},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center dropdown-option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5, 6, 7],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6, 7],
        });
    });
</script>