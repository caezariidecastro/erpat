<div class="table-responsive">
    <table id="custom-attendance-table" class="display" cellspacing="0" width="100%">
    </table>
</div>
<script type="text/javascript">
    $(document).ready(function () {

        $("#custom-attendance-table").appTable({
            source: '<?php echo_uri("hrs/attendance/list_data/"); ?>',
            order: [[2, "desc"]],
            filterParams: {
                datatable: true,
                user_id: "<?= $user_id ?>",
                start_date: "<?= $start_date ?>",
                end_date: "<?= $end_date ?>",
            },
            columns: [
                {title: "<?php echo lang("employee"); ?>", "class": "w10p"},
                {title: "<?php echo lang("department"); ?>", "class": "w5p text-center"},
                {title: "<?php echo lang("schedule"); ?>", class: "w5p"},
                {visible: false, searchable: false},
                {title: "<?php echo lang("in_date"); ?>", "class": "text-center w5p", iDataSort: 3},
                {title: "<?php echo lang("in_time"); ?>", "class": "text-center"},
                {visible: false, searchable: false},
                {title: "<?php echo lang("out_date"); ?>", "class": "text-center w5p", iDataSort: 6},
                {title: "<?php echo lang("out_time"); ?>", "class": "text-center"},
                {title: "<?php echo lang("duration"); ?>", "class": "text-right"},
                {title: "<?php echo lang("worked"); ?>", "class": "text-right"},
                {title: "<?= lang("regular") ." ". lang("overtime"); ?>", "class": "text-right"},
                {title: "<?= lang("restday") ." ". lang("overtime"); ?>", "class": "text-right"},
                {title: "<?php echo lang("bonus_pay"); ?>", "class": "text-right"},
                {title: "<?php echo lang("night"); ?>", "class": "text-right"},
                {title: "<?php echo lang("lates"); ?>", "class": "text-right"},
                {title: "<?php echo lang("overbreak"); ?>", "class": "text-right"},
                {title: "<?php echo lang("undertime"); ?>", "class": "ext-right"},
                {title: "<?php echo lang("log_type"); ?>", class: "w5p"},
                {title: "<?php echo lang("status"); ?>", class: "w5p"},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center dropdown-option"}
            ],
            printColumns: [0, 1, 3, 4, 5, 6, 7, 8, 9, 11, 12, 13, 14, 15, 16, 17, 18],
            xlsColumns: [0, 1, 3, 4, 5, 6, 7, 8, 9, 11, 12, 13, 14, 15, 16, 17, 18],
            summation: [
                {column: 9, dataType: 'time'},
                {column: 10, dataType: 'number'},
                {column: 11, dataType: 'number'},
                {column: 12, dataType: 'number'},
                {column: 13, dataType: 'number'},
                {column: 14, dataType: 'number'},
                {column: 15, dataType: 'number'},
                {column: 16, dataType: 'number'},
                {column: 17, dataType: 'number'},
            ],
            tableRefreshButton: true,
        });

        $(".modal-dialog").addClass("modal-lg");
    });
</script>    
