<div class="table-responsive">
    <table id="attendance-summary-table" class="display" cellspacing="0" width="100%">            
    </table>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#attendance-summary-table").appTable({
            source: '<?php echo_uri("hrs/attendance/summary_list_data/"); ?>',
            order: [[0, "desc"]],
            filterDropdown: [
                {name: "department_id", class: "w200", options: <?= json_encode($department_select2) ?>},
                {name: "user_id", class: "w200", options: <?php echo $team_members_dropdown; ?>}
            ],
            rangeDatepicker: [{startDate: {name: "start_date", value: moment().format("YYYY-MM-DD")}, endDate: {name: "end_date", value: moment().format("YYYY-MM-DD")}}],
            columns: [
                {title: "<?php echo lang("employee"); ?>", "class": "w20p"},
                {title: "<?php echo lang("department"); ?>", "class": "w15p text-center"},
                {title: "<?php echo lang("duration"); ?>", "class": "w5p text-right"},
                {title: "<?php echo lang("worked"); ?>", "class": "w5p text-right"},
                {title: "<?php echo lang("bonus"); ?>", "class": "w5p text-right"},
                {title: "<?php echo lang("lates"); ?>", "class": "w5p text-right"},
                {title: "<?php echo lang("overbreak"); ?>", "class": "w5p text-right"},
                {title: "<?php echo lang("undertime"); ?>", "class": "w5p text-right"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5, 6],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6],
            summation: [
                {column: 2, dataType: 'time'},
                {column: 3, dataType: 'number'},
                {column: 4, dataType: 'number'},
                {column: 5, dataType: 'number'},
                {column: 6, dataType: 'number'}
            ],
            tableRefreshButton: true,
        });
    });
</script>