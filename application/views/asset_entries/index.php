<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang('assets'); ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("labels/modal_form"), "<i class='fa fa-tags'></i> " . lang('manage_labels'), array("class" => "btn btn-default", "title" => lang('manage_labels'), "data-post-type" => "asset_entry")); ?>
                <?php echo modal_anchor(get_uri("asset_entries/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_entry'), array("class" => "btn btn-default", "title" => lang('add_entry'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="asset-entry-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#asset-entry-table").appTable({
            source: "<?php echo_uri("asset_entries/list_data") ?>",
            order: [[0, "desc"]],
            columns: [
                {title: "<?php echo lang("title") ?> ", "class": "w15p"},
                {title: "<?php echo lang("description") ?>"},
                {title: "<?php echo lang("cost") ?>"},
                {title: "<?php echo lang("serial_number") ?>"},
                {title: "<?php echo lang("model") ?>"},
                {title: "<?php echo lang("brand") ?>"},
                {title: "<?php echo lang("purchase_date") ?>"},
                {title: "<?php echo lang("warranty_expiry_date") ?>"},
                {title: "<?php echo lang("type") ?>"},
                {title: "<?php echo lang("vendor") ?>"},
                {title: "<?php echo lang("category") ?>"},
                {title: "<?php echo lang("location") ?>"},
                {title: "<?php echo lang("created_on") ?>"},
                {title: "<?php echo lang("created_by") ?>"},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4],
            xlsColumns: [0, 1, 2, 3, 4]
        });
    });
</script>