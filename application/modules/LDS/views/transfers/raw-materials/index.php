<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang('transactions'); ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("lds/TransferRawMaterials/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_transfer'), array("class" => "btn btn-default", "title" => lang('add_transfer'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="material-inventory-transfers-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#material-inventory-transfers-table").appTable({
            source: '<?php echo_uri("lds/TransferRawMaterials/list_data") ?>',
            order: [[7, 'desc']],
            columns: [
                {title: "<?php echo lang('reference_number') ?> ", "class": "w10p"},
                {title: "<?php echo lang('from') ?> ", "class": "w10p"},
                {title: "<?php echo lang('to') ?>", "class": "w10p"},
                {title: "<?php echo lang('dispatcher') ?>"},
                {title: "<?php echo lang('driver') ?>"},
                {title: "<?php echo lang('vehicle') ?>"},
                {title: "<?php echo lang('items') ?>"},
                {title: "<?php echo lang('remarks') ?>", "class": "w15p"},
                {title: "<?php echo lang('created_on') ?>",},
                {title: "<?php echo lang('created_by') ?>",},
                {title: "<?php echo lang('status') ?>", "class": "text-center"},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
        });
    });
</script>