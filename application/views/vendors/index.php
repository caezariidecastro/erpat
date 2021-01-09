<div class="table-responsive">
    <table id="vendors-table" class="display" cellspacing="0" width="100%">            
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#vendors-table").appTable({
            source: '<?php echo_uri("vendors/list_data") ?>',
            order: [[0, 'desc']],
            columns: [
                {title: "<?php echo lang('name') ?> ", "class": "w20p"},
                {title: "<?php echo lang('contact') ?>"},
                {title: "<?php echo lang('email') ?>"},
                {title: "<?php echo lang('address') ?>"},
                {title: "<?php echo lang('created_on') ?>",},
                {title: "<?php echo lang('created_by') ?>",},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5],
            xlsColumns: [0, 1, 2, 3, 4, 5],
        });
    });
</script>