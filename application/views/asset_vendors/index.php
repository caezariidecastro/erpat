<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang('vendors'); ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("asset_vendors/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_vendor'), array("class" => "btn btn-default", "title" => lang('add_vendor'), "id" => "add_vendor_button")); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="asset-vendors-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#asset-vendors-table").appTable({
            source: '<?php echo_uri("asset_vendors/list_data") ?>',
            filterDropdown: [
                {id: "status_select2_filter", name: "status_select2_filter", class: "w200", options: <?php echo json_encode($status_select2); ?>},
            ],
            order: [[0, 'desc']],
            columns: [
                {title: "<?php echo lang('name') ?> ", "class": "w20p"},
                {title: "<?php echo lang('primary_contact') ?>"},
                {title: "<?php echo lang('address') ?>"},
                {title: "<?php echo lang('city') ?>"},
                {title: "<?php echo lang('state') ?>"},
                {title: "<?php echo lang('zip') ?>"},
                {title: "<?php echo lang('country') ?>"},
                {title: "<?php echo lang('created_on') ?>",},
                {title: "<?php echo lang('created_by') ?>",},
                {title: "<?php echo lang('contacts') ?>", "class": "text-center"},
                {title: "<?php echo lang("status") ?>", "class": "text-center"},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
        });
    });
</script>