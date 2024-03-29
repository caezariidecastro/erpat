<div class="table-responsive">
    <table id="discipline-entries-table" class="display" cellspacing="0" width="100%">            
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#discipline-entries-table").appTable({
            source: '<?php echo_uri("hrs/discipline_entries/list_data") ?>',
            filterDropdown: [
                {id: "category_select2_filter", name: "category_select2_filter", class: "w200", options: <?php echo json_encode($category_select2); ?>},
            ],
            order: [[0, 'desc']],
            columns: [
                {title: "<?php echo lang('date_occurred') ?>"},
                {title: "<?php echo lang('category') ?>"},
                {title: "<?php echo lang('user') ?>"},
                {title: "<?php echo lang('witness') ?>"},
                {title: "<?php echo lang('description') ?>"},
                {title: "<?php echo lang('created_on') ?>",},
                {title: "<?php echo lang('created_by') ?>",},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5, 6],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6]
        });
    });
</script>