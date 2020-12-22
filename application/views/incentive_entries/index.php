<div class="table-responsive">
    <table id="incentive-entries-table" class="display" cellspacing="0" width="100%">            
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#incentive-entries-table").appTable({
            source: '<?php echo_uri("incentive_entries/list_data") ?>',
            order: [[0, 'desc']],
            columns: [
                {title: "<?php echo lang('category') ?>"},
                {title: "<?php echo lang('employee') ?>"},
                {title: "<?php echo lang('signed_by') ?>"},
                {title: "<?php echo lang('remarks') ?>"},
                {title: "<?php echo lang('created_on') ?>",},
                {title: "<?php echo lang('created_by') ?>",},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5],
            xlsColumns: [0, 1, 2, 3, 4, 5]
        });
    });
</script>