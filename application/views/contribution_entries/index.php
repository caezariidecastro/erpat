<div class="table-responsive">
    <table id="contribution-entries-table" class="display" cellspacing="0" width="100%">            
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#contribution-entries-table").appTable({
            source: '<?php echo_uri("contribution_entries/list_data") ?>',
            filterDropdown: [
                {id: "category_select2_filter", name: "category_select2_filter", class: "w200", options: <?php echo json_encode($category_select2); ?>},
                {id: "users_select2_filter", name: "users_select2_filter", class: "w200", options: <?php echo json_encode($user_select2); ?>}
            ],
            dateRangeType: "monthly",
            order: [[0, 'desc']],
            columns: [
                {title: "<?php echo lang('employee') ?> ", "class": "w20p"},
                {title: "<?php echo lang('category') ?>"},
                {title: "<?php echo lang('amount') ?>", "class": "text-right"},
                {title: "<?php echo lang('created_on') ?>",},
                {title: "<?php echo lang('created_by') ?>",},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4],
            xlsColumns: [0, 1, 2, 3, 4],
            summation: [{column: 2, dataType: 'number'}]
        });
    });
</script>