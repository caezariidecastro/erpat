<div class="table-responsive">
    <table id="attendance-export-table" class="display" cellspacing="0" width="100%">            
    </table>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#attendance-export-table").appTable({
            source: '<?php echo_uri("hrs/attendance/export_list_data/"); ?>',
            order: [[0, "asc"]],
            filterDropdown: [{name: "user_id", class: "w200", options: <?php echo $team_members_dropdown; ?>}],
            rangeDatepicker: [{startDate: {name: "start_date", value: moment().format("YYYY-MM-DD")}, endDate: {name: "end_date", value: moment().format("YYYY-MM-DD")}}],
            columns: [
                {visible: false, searchable: false},
                {title: "<?php echo lang("employee"); ?>", "class": "w20p", "iDataSort": 0},
                {title: "<?php echo lang("date"); ?>", "class": "w15p text-center", "bSortable": false},
                {title: "<?php echo lang("duration"); ?>", "class": "w5p text-right", "bSortable": false},
                {title: "<?php echo lang("worked"); ?>", "class": "w5p text-right", "bSortable": false},
                {title: "<?php echo lang("idle"); ?>", "class": "w5p text-right", "bSortable": false},
                {title: "<?php echo lang("lates"); ?>", "class": "w5p text-right", "bSortable": false},
                {title: "<?php echo lang("overbreak"); ?>", "class": "w5p text-right"},
                {title: "<?php echo lang("undertime"); ?>", "class": "w5p text-right", "bSortable": false}
            ],
            printColumns: [1, 2, 3, 4, 5, 6, 7],
            xlsColumns: [1, 2, 3, 4, 5, 6, 7],
            // summation: [
            //     {column: 3, dataType: 'time'},
            //     {column: 4, dataType: 'number'},
            //     {column: 5, dataType: 'number'},
            //     {column: 6, dataType: 'number'},
            //     {column: 7, dataType: 'number'}
            // ],
            tableRefreshButton: true,
        });
    });
</script>