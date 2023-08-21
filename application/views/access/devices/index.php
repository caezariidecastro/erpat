<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("Access_devices/modal_form"), "<i class='fa fa-plus'></i> " . lang('add_device'), array("class" => "btn btn-default", "title" => lang('add_device'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="access_device-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#access_device-table").appTable({
            source: '<?php echo_uri("Access_devices/list_data") ?>',
            order: [[1, 'desc']],
            columns: [
                {visible: false, searchable: false},
                {title: '<?php echo lang("title"); ?>'},
                {title: '<?php echo lang("category"); ?>'},
                {title: '<?php echo lang("passes"); ?>'},
                {title: '<?php echo lang("details"); ?>'},
                {title: '<?php echo lang("status"); ?>'},
                {title: '<?php echo lang("date"); ?>'},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            tableRefreshButton: true,
            // printColumns: [2, 4],
            // xlsColumns: [2, 4],
        });
    });
</script>