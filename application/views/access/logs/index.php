<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="table-responsive">
            <table id="access_logs-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#access_logs-table").appTable({
            source: '<?php echo_uri("Access_logs/list_data") ?>',
            order: [[1, 'desc']],
            filterDropdown: [
                {name: "device_id", class: "w150", options: <?php echo json_encode($device_dropdown); ?>}
            ],
            rangeDatepicker: [{startDate: {name: "start_date", value: moment().add(-7, 'days').format("YYYY-MM-DD")}, endDate: {name: "end_date", value: moment().format("YYYY-MM-DD")}}],
            columns: [
                {visible: false, searchable: false},
                {title: '<?php echo lang("device"); ?>'},
                {title: '<?php echo lang("employee"); ?>'},
                {title: '<?php echo lang("remarks"); ?>'},      
                {title: '<?php echo lang("timestamp"); ?>'}
            ],
            tableRefreshButton: true,
            // printColumns: [2, 4],
            // xlsColumns: [2, 4],
        });
    });
</script>