<div class="table-responsive">
    <table id="overtime-summary-table" class="display" cellspacing="0" width="100%">            
    </table>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#overtime-summary-table").appTable({
            source: '<?php echo_uri("hrs/overtime/summary_list_data/"); ?>',
            order: [[0, "desc"]],
            filterParams: {user_id: "<?php echo $user_id; ?>"},
            rangeDatepicker: [{startDate: {name: "start_date", value: moment().format("YYYY-MM-DD")}, endDate: {name: "end_date", value: moment().format("YYYY-MM-DD")}}],
            columns: [
                {visible: false},
                {title: "<?php echo lang("duration"); ?>", "class": "text-right"},
                {title: "<?php echo lang("hours"); ?>",  "class": "text-right"}
            ],
            printColumns: [1, 2],
            xlsColumns: [1, 2]
        });
    });
</script>