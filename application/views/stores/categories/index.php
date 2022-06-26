<div class="table-responsive">
    <table id="stores-categories-table" class="display" cellspacing="0" width="100%">            
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#stores-categories-table").appTable({
            source: '<?php echo_uri("stores_categories/list_data") ?>',
            order: [[0, 'desc']],
            columns: [
                {title: "<?php echo lang('uuid') ?> ", "class": "w15p"},
                {title: "<?php echo lang('title') ?> ", "class": "w15p"},
                {title: "<?php echo lang('description') ?>", "class": "w100"},
                {title: "<?php echo lang('status') ?>", "class": "text-center"},
                {title: "<?php echo lang('created_at') ?>", "class": "w15p"},
                {title: "<?php echo lang('updated_at') ?>", "class": "w15p"},
                {title: "<?php echo lang('created_by') ?>", "class": "w10p"},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w10p"}
            ],
            printColumns: [0, 1, 2, 3],
            xlsColumns: [0, 1, 2, 3]
        });
    });
</script>