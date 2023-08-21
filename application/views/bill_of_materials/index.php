<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang('bill_of_materials'); ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("production/BillOfMaterials/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_bill_of_material'), array("class" => "btn btn-default", "title" => lang('add_bill_of_material'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="bill-of-materials-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#bill-of-materials-table").appTable({
            source: '<?php echo_uri("production/BillOfMaterials/list_data") ?>',
            order: [[0, 'desc']],
            columns: [
                {title: "<?php echo lang('title') ?> ", "class": "w20p"},
                {title: "<?php echo lang('description') ?>"},
                {title: "<?php echo lang('output') ?>"},
                {title: "<?php echo lang('quantity') ?>"},
                {title: "<?php echo lang('created_by') ?>"},
                {title: "<?php echo lang('created_on') ?>"},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center dropdown-option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4],
            xlsColumns: [0, 1, 2, 3, 4]
        });
    });
</script>