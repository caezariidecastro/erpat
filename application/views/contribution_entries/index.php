<div class="table-responsive">
    <table id="contribution-entries-table" class="display" cellspacing="0" width="100%">            
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#contribution-entries-table").appTable({
            source: '<?php echo_uri("contribution_entries/list_data") ?>',
            order: [[0, 'desc']],
            columns: [
                {title: "<?php echo lang('employee') ?> ", "class": "w20p"},
                {title: "<?php echo lang('amount') ?>"},
                {title: "<?php echo lang('category') ?>"},
                {title: "<?php echo lang('created_on') ?>",},
                {title: "<?php echo lang('created_by') ?>",},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4],
            xlsColumns: [0, 1, 2, 3, 4]
        });
    });
</script>