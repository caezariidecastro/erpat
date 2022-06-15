<div class="table-responsive">
    <table id="custom-attendance-table" class="display" cellspacing="0" width="100%">
    </table>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#custom-attendance-table").appTable({
            source: '<?php echo_uri("hrs/attendance/list_data/"); ?>',
            order: [[2, "desc"]],
            filterDropdown: [
                {name: "user_id", class: "w200", options: <?php echo $team_members_dropdown; ?>}
            ],
            rangeDatepicker: [{startDate: {name: "start_date", value: moment().format("YYYY-MM-DD")}, endDate: {name: "end_date", value: moment().format("YYYY-MM-DD")}}],
            columns: [
                {title: "<?php echo lang("employee"); ?>", "class": "w20p"},
                {visible: false, searchable: false},
                {title: "<?php echo lang("in_date"); ?>", "class": "text-center w10p", iDataSort: 1},
                {title: "<?php echo lang("in_time"); ?>", "class": "text-center w10p"},
                {visible: false, searchable: false},
                {title: "<?php echo lang("out_date"); ?>", "class": "text-center w10p", iDataSort: 4},
                {title: "<?php echo lang("out_time"); ?>", "class": "text-center w10p"},
                {title: "<?php echo lang("duration"); ?>", "class": "w5p text-right"},
                {title: "<?php echo lang("worked"); ?>", "class": "w5p text-right"},
                {title: "<?php echo lang("idle"); ?>", "class": "w5p text-right"},
                {title: "<?php echo lang("lates"); ?>", "class": "w5p text-right"},
                {title: "<?php echo lang("undertime"); ?>", "class": "w5p text-right"},
                {title: "<?php echo lang("info"); ?>", "class": "text-center w50"},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            printColumns: [0, 2, 3, 5, 6, 7, 8, 9, 10, 11],
            xlsColumns: [0, 2, 3, 5, 6, 7, 8, 9, 10, 11],
            summation: [
                {column: 7, dataType: 'time'},
                {column: 8, dataType: 'number'},
                {column: 9, dataType: 'number'},
                {column: 10, dataType: 'number'},
                {column: 11, dataType: 'number'}
            ]
        });
    });
</script>