<div class="table-responsive">
    <table id="services-categories-table" class="display" cellspacing="0" width="100%">            
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#services-categories-table").appTable({
            source: '<?php echo_uri("services_categories/list_data") ?>',
            order: [[0, 'desc']],
            columns: [
                {title: "<?php echo lang('title') ?> ", "class": "w150"},
                {title: "<?php echo lang('description') ?>"},
                {title: "<?php echo lang('status') ?>", "class": "text-center w50"},
                {title: "<?php echo lang('created_at') ?>", "class": "text-center w100"},
                {title: "<?php echo lang('updated_at') ?>", "class": "text-center w100"},
                {title: "<?php echo lang('created_by') ?>", "class": "text-center w100"},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3],
            xlsColumns: [0, 1, 2, 3]
        });
    });
</script>