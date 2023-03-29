<div class="table-responsive">
    <table id="payroll-attendance-table" class="display" cellspacing="0" width="100%">
    </table>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#payroll-attendance-table").appTable({
            source: '<?php echo_uri("hrs/attendance/list_data/"); ?>',
            filterParams: {
                datatable: true,
                user_id: "<?= $user_id ?>",
                start_date: "<?= $start_date ?>",
                end_date: "<?= $end_date ?>",
            },
            order: [[2, "desc"]],
            columns: [
                {title: "<?php echo lang("employee"); ?>", "class": "w20p"},
                {title: "<?php echo lang("department"); ?>", "class": "w15p text-center"},
                {visible: false, searchable: false},
                {title: "<?php echo lang("in_date"); ?>", "class": "text-center w10p", iDataSort: 2},
                {title: "<?php echo lang("in_time"); ?>", "class": "text-center w10p"},
                {title: "<?php echo lang("break_1st_start"); ?>", "class": "text-center w10p"},
                {title: "<?php echo lang("break_1st_end"); ?>", "class": "text-center w10p"},
                {title: "<?php echo lang("break_lunch_start"); ?>", "class": "text-center w10p"},
                {title: "<?php echo lang("break_lunch_end"); ?>", "class": "text-center w10p"},
                {title: "<?php echo lang("break_2nd_start"); ?>", "class": "text-center w10p"},
                {title: "<?php echo lang("break_2nd_end"); ?>", "class": "text-center w10p"},
                {visible: false, searchable: false},
                {title: "<?php echo lang("out_date"); ?>", "class": "text-center w10p", iDataSort: 5},
                {title: "<?php echo lang("out_time"); ?>", "class": "text-center w10p"},
                {title: "<?php echo lang("duration"); ?>", "class": "w5p text-right"},
                {title: "<?php echo lang("worked"); ?>", "class": "w5p text-right"},
                {title: "<?php echo lang("overtime"); ?>", "class": "w5p text-right"},
                {title: "<?php echo lang("bonus"); ?>", "class": "w5p text-right"},
                {title: "<?php echo lang("night"); ?>", "class": "w5p text-right"},
                {title: "<?php echo lang("lates"); ?>", "class": "w5p text-right"},
                {title: "<?php echo lang("overbreak"); ?>", "class": "w5p text-right"},
                {title: "<?php echo lang("undertime"); ?>", "class": "w5p text-right"},
                {title: "<?php echo lang("info"); ?>", "class": "text-center w50"},
            ],
            printColumns: [0, 1, 3, 4, 5, 6, 7, 8, 9, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21],
            xlsColumns: [0, 1, 3, 4, 5, 6, 7, 8, 9, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21],
            summation: [
                {column: 14, dataType: 'time'},
                {column: 15, dataType: 'number'},
                {column: 16, dataType: 'number'},
                {column: 17, dataType: 'number'},
                {column: 18, dataType: 'number'},
                {column: 19, dataType: 'number'},
                {column: 20, dataType: 'number'},
                {column: 21, dataType: 'number'}
            ],
            tableRefreshButton: true,
        });
    });
</script>