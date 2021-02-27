<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang('purchases'); ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("purchase_orders/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_purchase'), array("class" => "btn btn-default", "title" => lang('add_purchase'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="purchase-orders-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#purchase-orders-table").appTable({
            source: '<?php echo_uri("purchase_orders/list_data") ?>',
            filterDropdown: [
                {id: "vendor_select2_filter", name: "vendor_select2_filter", class: "w200", options: <?php echo json_encode($vendor_select2); ?>},
            ],
            order: [[0, 'desc']],
            columns: [
                {title: "<?php echo lang('purchase_id') ?> "},
                {title: "<?php echo lang('supplier') ?>"},
                {title: "<?php echo lang('amount') ?>"},
                {title: "<?php echo lang('remarks') ?>"},
                {title: "<?php echo lang('created_on') ?>"},
                {title: "<?php echo lang('created_by') ?>"},
                {title: "<?php echo lang('status') ?>", "class": "text-center option w100"},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5, 6, 7],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6, 7]
        });
    });
</script>