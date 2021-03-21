<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang('brands'); ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("ams/brands/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_brand'), array("class" => "btn btn-default", "title" => lang('add_brand'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="asset-brand-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#asset-brand-table").appTable({
            source: "<?php echo_uri("ams/brands/list_data") ?>",
            filterDropdown: [
                {id: "status_select2_filter", name: "status_select2_filter", class: "w200", options: <?php echo json_encode($status_select2); ?>},
            ],
            order: [[0, "desc"]],
            columns: [
                {title: "<?php echo lang("title") ?> ", "class": "w20p"},
                {title: "<?php echo lang("description") ?>"},
                {title: "<?php echo lang("created_on") ?>"},
                {title: "<?php echo lang("created_by") ?>"},
                {title: "<?php echo lang("active_inactive") ?>", "class": "text-center"},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4],
            xlsColumns: [0, 1, 2, 3, 4]
        });
    });
</script>