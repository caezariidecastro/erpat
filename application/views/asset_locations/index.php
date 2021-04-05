<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang('location'); ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("ams/locations/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_location'), array("class" => "btn btn-default", "title" => lang('add_location'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="asset-location-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#asset-location-table").appTable({
            source: "<?php echo_uri("ams/locations/list_data") ?>",
            order: [[0, "desc"]],
            columns: [
                {title: "<?php echo lang("title") ?> ", "class": "w20p"},
                {title: "<?php echo lang("description") ?>"},
                {title: "<?php echo lang("parent") ?>"},
                {title: "<?php echo lang("created_on") ?>"},
                {title: "<?php echo lang("created_by") ?>"},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4],
            xlsColumns: [0, 1, 2, 3, 4]
        });
    });
</script>