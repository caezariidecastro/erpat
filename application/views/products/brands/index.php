<div class="table-responsive">
    <table id="item-brands-table" class="display" cellspacing="0" width="100%">            
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#item-brands-table").appTable({
            source: '<?php echo_uri("sales/ProductBrands/list_data") ?>',
            order: [[0, 'desc']],
            columns: [
                {title: "<?php echo lang('title') ?> ", "class": "w20p"},
                {title: "<?php echo lang('description') ?>"},
                {title: "<?php echo lang('created_on') ?>",},
                {title: "<?php echo lang('created_by') ?>",},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4],
            xlsColumns: [0, 1, 2, 3, 4]
        });
    });
</script>