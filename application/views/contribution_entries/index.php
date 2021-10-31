<div class="table-responsive">
    <table id="contribution-entries-table" class="display" cellspacing="0" width="100%">            
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#contribution-entries-table").appTable({
            source: '<?php echo_uri("fas/contribution_entries/list_data") ?>',
            filterDropdown: [
                {id: "category_select2_filter", name: "category_select2_filter", class: "w200", options: <?php echo json_encode($category_select2); ?>},
                {id: "users_select2_filter", name: "users_select2_filter", class: "w200", options: <?php echo json_encode($user_select2); ?>},
                {id: "account_select2_filter", name: "account_select2_filter", class: "w200", options: <?php echo json_encode($account_select2); ?>}
            ],
            dateRangeType: "monthly",
            order: [[0, 'desc']],
            columns: [
                {title: "<?php echo lang('id') ?>"},
                {title: "<?php echo lang('category') ?>"},
                {title: "<?php echo lang('account') ?>"},
                {title: "<?php echo lang('user') ?>"},
                {title: "<?php echo lang('signed_by') ?>"},
                {title: "<?php echo lang('amount') ?>", "class": "text-right"},
                {title: "<?php echo lang('remarks') ?>"},
                {title: "<?php echo lang('status') ?>", "class": "text-center"},
                {title: "<?php echo lang('created_on') ?>",},
                {title: "<?php echo lang('created_by') ?>",},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center dropdown-option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
            summation: [{column: 4, dataType: 'number'}]
        });
    });
</script>