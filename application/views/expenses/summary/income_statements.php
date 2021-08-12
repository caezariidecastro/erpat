<div class="table-responsive">
    <table id="income_statements-table" class="display" cellspacing="0" width="100%">
    </table>
</div>

<script>
    $("#income_statements-table").appTable({
    source: '<?php echo_uri("fas/expenses/income_statements_list_data"); ?>',
            order: [[0, "desc"]],
            dateRangeType: "yearly",
            filterDropdown: [
<?php if ($projects_dropdown) { ?>
                {name: "project_id", class: "w200", options: <?php echo $projects_dropdown; ?>}
<?php } ?>
            ],
            columns: [
            {visible: false, searchable: false}, //sorting purpose only
            {title: '<?php echo lang("month") ?>', "class": "w30p", "iDataSort": 0},
            {title: '<?php echo lang("income") ?>', "class": "w20p text-right"},
            {title: '<?php echo lang("expenses") ?>', "class": "w20p text-right"},
            {title: '<?php echo lang("profit") ?>', "class": "w20p text-right"}
            ],
            printColumns: [1, 2, 3, 4],
    xlsColumns: [1, 2, 3, 4],
            summation: [{column:2, dataType: 'currency'}, {column:3, dataType: 'currency'}, {column:4, dataType: 'currency'}]
    }
    );
</script>