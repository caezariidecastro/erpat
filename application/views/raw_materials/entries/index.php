<div class="table-responsive">
    <table id="material-entries-table" class="display" cellspacing="0" width="100%">            
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#material-entries-table").appTable({
            source: '<?php echo_uri("mes/RawMaterialEntries/list_data") ?>',
            filterDropdown: [
                {id: "category_select2_filter", name: "category_select2_filter", class: "w200", options: <?php echo json_encode($category_select2); ?>},
                {id: "vendor_select2_filter", name: "vendor_select2_filter", class: "w200", options: <?php echo json_encode($vendor_select2); ?>},
            ],
            order: [[0, 'desc']],
            columns: [
                {title: "<?php echo lang('name') ?> ", "class": "w20p"},
                {title: "<?php echo lang('sku') ?>"},
                {title: "<?php echo lang('unit') ?>"},
                {title: "<?php echo lang('category') ?>"},
                {title: "<?php echo lang('cost_price') ?>"},
                {title: "<?php echo lang('supplier') ?>"},
                {title: "<?php echo lang('created_on') ?>",},
                {title: "<?php echo lang('created_by') ?>",},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5],
            xlsColumns: [0, 1, 2, 3, 4, 5],
        });
    });
</script>