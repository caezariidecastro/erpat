<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang('delivery'); ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("deliveries/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_delivery'), array("class" => "btn btn-default", "title" => lang('add_delivery'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="deliveries-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#deliveries-table").appTable({
            source: '<?php echo_uri("deliveries/list_data") ?>',
            order: [[8, 'desc']],
            columns: [
                {title: "<?php echo lang('reference_number') ?> ", "class": "w10p"},
                {title: "<?php echo lang('warehouse') ?> ", "class": "w10p"},
                {title: "<?php echo lang('consumer') ?>", "class": "w10p"},
                {title: "<?php echo lang('dispatcher') ?>"},
                {title: "<?php echo lang('driver') ?>"},
                {title: "<?php echo lang('vehicle') ?>"},
                {title: "<?php echo lang('remarks') ?>"},
                {title: "<?php echo lang('address') ?>"},
                {title: "<?php echo lang('created_on') ?>",},
                {title: "<?php echo lang('created_by') ?>",},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
        });
    });
</script>