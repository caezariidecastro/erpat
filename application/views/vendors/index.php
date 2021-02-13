<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang('supplier'); ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("vendors/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_supplier'), array("class" => "btn btn-default", "title" => lang('add_supplier'), "id" => "add_supplier_button")); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="vendors-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#vendors-table").appTable({
            source: '<?php echo_uri("vendors/list_data") ?>',
            order: [[0, 'desc']],
            columns: [
                {title: "<?php echo lang('name') ?> ", "class": "w20p"},
                {title: "<?php echo lang('address') ?>"},
                {title: "<?php echo lang('city') ?>"},
                {title: "<?php echo lang('state') ?>"},
                {title: "<?php echo lang('zip') ?>"},
                {title: "<?php echo lang('country') ?>"},
                {title: "<?php echo lang('created_on') ?>",},
                {title: "<?php echo lang('created_by') ?>",},
                {title: "<?php echo lang('contacts') ?>", "class": "text-center"},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5, 6, 7],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6, 7],
        });
    });
</script>