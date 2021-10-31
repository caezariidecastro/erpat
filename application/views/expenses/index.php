<div id="page-content" class="clearfix p20">
    <div class="panel clearfix">
        <ul id="expenses-tabs" data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang("expenses"); ?></h4></li>
            <li><a id="monthly-expenses-button"  role="presentation" class="active" href="javascript:;" data-target="#monthly-expenses"><?php echo lang("monthly"); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("fas/expenses/yearly/"); ?>" data-target="#yearly-expenses"><?php echo lang('yearly'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("fas/expenses/custom/"); ?>" data-target="#custom-expenses"><?php echo lang('custom'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("fas/expenses/recurring/"); ?>" data-target="#recurring-expenses"><?php echo lang('recurring'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("fas/expense_categories/"); ?>" data-target="#expense-categories"><?php echo lang('categories'); ?></a></li>
            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                    <?php echo modal_anchor(get_uri("fas/expenses/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_expense'), array("class" => "btn btn-default mb0", "title" => lang('add_expense'))); ?>
                </div>
            </div>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="monthly-expenses">
                <div class="table-responsive">
                    <table id="monthly-expense-table" class="display" cellspacing="0" width="100%">
                    </table>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="yearly-expenses"></div>
            <div role="tabpanel" class="tab-pane fade" id="custom-expenses"></div>
            <div role="tabpanel" class="tab-pane fade" id="recurring-expenses"></div>
            <div role="tabpanel" class="tab-pane fade" id="expense-categories"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    loadExpensesTable = function (selector, dateRange) {

        var optionVisibility = false;
        if ("<?php echo $can_edit_expenses ?>") {
            optionVisibility = true;
        }

        var recurring = dateRange == "recurring" ? "/recurring":"";
             
        var config = {
            source: '<?php echo_uri("fas/expenses/list_data") ?>'+recurring,
            filterDropdown: [
                {name: "category_id", class: "w150", options: <?php echo $categories_dropdown; ?>},
                {name: "user_id", class: "w150", options: <?php echo $members_dropdown; ?>},
                <?php if ($projects_dropdown) { ?>
                    {name: "project_id", class: "w150", options: <?php echo $projects_dropdown; ?>}
                <?php } ?>
            ],
            order: [[0, "asc"]],
            columns: [
                {visible: false, searchable: false},
                {title: '<?php echo lang("date") ?>', "iDataSort": 0},
                {title: '<?php echo lang("category") ?>'},
                {title: '<?php echo lang("title") ?>'},
                {title: '<?php echo lang("description") ?>'},
                {title: '<?php echo lang("files") ?>'},
                {title: '<?php echo lang("amount") ?>', "class": "text-right"},
                {title: '<?php echo lang("tax") ?>', "class": "text-right"},
                {title: '<?php echo lang("total") ?>', "class": "text-right"},
                {title: '<?php echo lang("payment") ?>', "class": "text-right"},
                {title: '<?php echo lang("balance") ?>', "class": "text-right"},
                {title: '<?php echo lang("status") ?>', "class": "text-right"}
                <?php echo $custom_field_headers; ?>,
                {title: '<i class="fa fa-bars"></i>', "class": "text-center dropdown-option w100", visible: optionVisibility}
            ],
            printColumns: [1, 2, 3, 4, 6, 7, 8, 9],
            xlsColumns: [1, 2, 3, 4, 6, 7, 8, 9],
            summation: [{column: 6, dataType: 'currency'}, {column: 7, dataType: 'currency'}, {column: 8, dataType: 'currency'}, {column: 9, dataType: 'currency'}, {column: 10, dataType: 'currency'}]
        };

        var customDatePicker = "";
        if (dateRange === "custom") {
            customDatePicker = [{startDate: {name: "start_date", value: moment().format("YYYY-MM-DD")}, endDate: {name: "end_date", value: moment().format("YYYY-MM-DD")}, showClearButton: true}];
            dateRange = "";
        }
        
        if(dateRange !== "recurring") {
            config.dateRangeType = dateRange;
            config.rangeDatepicker = customDatePicker;
        }

        $(selector).appTable(config);
    };

    $(document).ready(function () {
        loadExpensesTable("#monthly-expense-table", "monthly");
    });
</script>
