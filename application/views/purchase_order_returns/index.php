<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang('returns'); ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("purchase_order_returns/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_purchase'), array("class" => "btn btn-default", "title" => lang('add_return'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="purchase-order-returns-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#purchase-order-returns-table").appTable({
            source: '<?php echo_uri("purchase_order_returns/list_data") ?>',
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
                {title: "<i class='fa fa-bars'></i>", "class": "text-center dropdown-option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5],
            xlsColumns: [0, 1, 2, 3, 4, 5]
        });
    });
</script>