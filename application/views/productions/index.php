<style>
    @media print {
        #print-section{
            background-color: white;
            height: 100%;
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            margin: 0;
            font-size: 14px;
            line-height: 18px;
            display: block;
        }
    }

    #print-section{
        display: none;
    }
</style>

<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang('productions'); ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("productions/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_production'), array("class" => "btn btn-default", "title" => lang('add_production'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="production-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<div id="print-section"></div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#production-table").appTable({
            source: '<?php echo_uri("productions/list_data") ?>',
            filterDropdown: [
                {id: "warehouse_select2_filter", name: "warehouse_select2_filter", class: "w200", options: <?php echo json_encode($warehouse_select2); ?>},
            ],
            dateRangeType: "monthly",
            order: [[0, 'desc']],
            columns: [
                {title: "<?php echo lang('bill_of_material') ?> ", "class": "w20p"},
                {title: "<?php echo lang('output') ?>"},
                {title: "<?php echo lang('warehouse') ?>"},
                {title: "<?php echo lang('quantity') ?>"},
                {title: "<?php echo lang('buffer') ?>"},
                {title: "<?php echo lang('created_on') ?>",},
                {title: "<?php echo lang('created_by') ?>",},
                {title: "<?php echo lang('status') ?>", "class": "text-center w100"},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center dropdown-option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5, 6, 7],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6, 7],
        });
    });
</script>