<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("Epass_Area/modal_form"), "<i class='fa fa-plus'></i> " . lang('add_area'), array("class" => "btn btn-default", "title" => lang('add_area'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="area-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#area-table").appTable({
            source: '<?php echo_uri("Epass_Area/list_data") ?>',
            order: [[1, 'desc']],
            columns: [
                {visible: false, searchable: false},
                {title: '<?php echo lang("event_name"); ?>'},
                {title: '<?php echo lang("area_name"); ?>'},
                {title: '<?php echo lang("total_blocks"); ?>'},
                {title: '<?php echo lang("total_seats"); ?>'},
                {title: '<?php echo lang("vacant_seats"); ?>'},
                {title: '<?php echo lang("sort"); ?>'},
                {title: '<?php echo lang("remarks"); ?>'},
                {title: '<?php echo lang("date"); ?>'},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            tableRefreshButton: true,
            printColumns: [1,2,3,4,5,6,7],
            xlsColumns: [1,2,3,4,5,6,7],
        });
    });
</script>