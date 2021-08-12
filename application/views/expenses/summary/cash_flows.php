<style type="text/css">
    #yearly-expense-chart .flot-y1-axis {
        left: -35px !important;
    }
</style>

<div id="page-content" class="clearfix p20">
    <div class="panel clearfix">
        <ul id="income-vs-expenses-chart-tabs" data-toggle="ajax-tab" class="nav nav-tabs bg-white inner clearfix" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt5 pr15"><?php echo lang("summary"); ?></h4></li>
            <li><a id="income-vs-expenses-chart-button" role="presentation" class="active" href="javascript:;" data-target="#income-vs-expenses-chart-tab"><?php echo lang("cash_flows"); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("fas/expenses/income_statements/"); ?>" data-target="#income_statements"><?php echo lang("income_statements"); ?></a></li>
            <li><a role="presentation" href="#" data-target="#balancesheet"><?php echo lang("balance_sheet"); ?></a></li>
            <span class="help pull-right p15" data-toggle="tooltip" data-placement="left" title="<?php echo lang('income_expenses_widget_help_message') ?>"><i class="fa fa-question-circle"></i></span>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="income-vs-expenses-chart-tab">
                <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        <i class="fa fa-bar-chart pt10"></i>&nbsp; <?php echo lang("comparison"); ?>
                        <div class="pull-right">
                            <?php
                            if ($projects_dropdown) {
                                echo form_input(array(
                                    "id" => "projects-dropdown",
                                    "name" => "projects-dropdown",
                                    "class" => "select2 w200 reload-chart font-normal",
                                    "placeholder" => lang('project')
                                ));
                            }
                            ?>

                            <div class="inline-block" id="yearly-date-range-selector"></div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div id="income-vs-expenses-chart" style="width: 100%; height: 350px;"></div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        <i class="fa fa-bar-chart pt10"></i>&nbsp; <?php echo lang("expenses"); ?>
                    </div>
                    <div class="panel-body">
                        <div style="padding-left:35px;">
                            <div id="yearly-expense-chart" style="width:100%; height: 350px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div role="tabpanel" class="tab-pane fade" id="income_statements"></div>
            <div role="tabpanel" class="tab-pane fade" id="balancesheet">
                <?php $this->load->view("expenses/summary/balance_sheet")?>
            </div>
        </div>

    </div>
</div>

<script type="text/javascript">
    var initIncomeExpenseChart = function (income, expense) {
        var dataset = [
            {
                data: income,
                color: "rgba(0, 179, 147, 1)",
                lines: {
                    show: true,
                    fill: 0.2
                },
                points: {
                    show: false
                },
                shadowSize: 0
            },
            {
                label: "<?php echo lang('income'); ?>",
                data: income,
                color: "rgba(0, 179, 147, 1)",
                lines: {
                    show: false
                },
                points: {
                    show: true,
                    fill: true,
                    radius: 4,
                    fillColor: "#fff",
                    lineWidth: 1
                },
                shadowSize: 0,
                curvedLines: {
                    apply: false
                }
            },
            {
                data: expense,
                color: "#F06C71",
                lines: {
                    show: true,
                    fill: 0.2
                },
                points: {
                    show: false
                },
                shadowSize: 0
            },
            {
                label: "<?php echo lang('expense'); ?>",
                data: expense,
                color: "#F06C71",
                lines: {
                    show: false
                },
                points: {
                    show: true,
                    fill: true,
                    radius: 4,
                    fillColor: "#fff",
                    lineWidth: 1
                },
                shadowSize: 0,
                curvedLines: {
                    apply: false
                }
            }

        ];
        $.plot("#income-vs-expenses-chart", dataset, {
            series: {
                curvedLines: {
                    apply: true,
                    active: true,
                    monotonicFit: true
                }
            },
            legend: {
                show: true
            },
            yaxis: {
                min: 0
            },
            xaxis: {
                ticks: [[1, "<?php echo lang('short_january'); ?>"], [2, "<?php echo lang('short_february'); ?>"], [3, "<?php echo lang('short_march'); ?>"], [4, "<?php echo lang('short_april'); ?>"], [5, "<?php echo lang('short_may'); ?>"], [6, "<?php echo lang('short_june'); ?>"], [7, "<?php echo lang('short_july'); ?>"], [8, "<?php echo lang('short_august'); ?>"], [9, "<?php echo lang('short_september'); ?>"], [10, "<?php echo lang('short_october'); ?>"], [11, "<?php echo lang('short_november'); ?>"], [12, "<?php echo lang('short_december'); ?>"]]
            },
            grid: {
                color: "#bbb",
                hoverable: true,
                borderWidth: 0,
                backgroundColor: '#FFF'
            },
            tooltip: {
                show: true,
                content: function (x, y, z) {
                    if (x) {
                        return "%s: " + toCurrency(z);
                    } else {
                        return false;
                    }
                },
                defaultTheme: false
            }
        });
    };

    var prepareComparisonFlotChart = function (data) {
        var project_id = $("#projects-dropdown").val() || "0";
        data.project_id = project_id;

        appLoader.show();
        $.ajax({
            url: "<?php echo_uri("fas/expenses/cash_flow_comparison_data") ?>",
            data: data,
            cache: false,
            type: 'POST',
            dataType: "json",
            success: function (response) {
                appLoader.hide();
                initIncomeExpenseChart(response.income, response.expenses);
            }
        });
    };

    $(document).ready(function () {
        $("#income-vs-expenses-chart-button").trigger("click");
        var $projectsDropdown = $("#projects-dropdown"),
                data = {};

<?php if ($projects_dropdown) { ?>
            $projectsDropdown.select2({
                data: <?php echo $projects_dropdown; ?>
            });
<?php } ?>

        $(".reload-chart").change(function () {
            prepareComparisonFlotChart(data);
        });

        $("#yearly-date-range-selector").appDateRange({
            dateRangeType: "yearly",
            onChange: function (dateRange) {
                data = dateRange;
                prepareComparisonFlotChart(dateRange);
                prepareYearlyExpensesFlotChart(dateRange);
            },
            onInit: function (dateRange) {
                data = dateRange;
                prepareComparisonFlotChart(dateRange);
                prepareYearlyExpensesFlotChart(dateRange);
            }
        });

        $('[data-toggle="tooltip"]').tooltip();
    });

    var prepareYearlyExpensesFlotChart = function (data) {
        appLoader.show();
        $.ajax({
            url: "<?php echo_uri("fas/expenses/yearly_chart_data") ?>",
            data: data,
            cache: false,
            type: 'POST',
            dataType: "json",
            success: function (response) {
                appLoader.hide();
                initYearlyExpenseFlotChart(response.data);
            }
        });

    };

    var initYearlyExpenseFlotChart = function (data) {
        // var data = [["January", 1500], ["February", 100], ["March", 16000], ["April", 0], ["May", 17000], ["June", 10009]];

        $.plot("#yearly-expense-chart", [data], {
            series: {
                bars: {
                    show: true,
                    barWidth: 0.6,
                    align: "center"
                }
            },
            xaxis: {
                mode: "categories",
                tickLength: 0
            },
            grid: {
                color: "#bbb",
                hoverable: true,
                borderWidth: 0,
                backgroundColor: '#FFF'
            },
            tooltip: true,
            tooltipOpts: {
                content: function (x, y, z) {
                    if (x) {
                        return "%s: " + toCurrency(z);
                    } else {
                        return  toCurrency(z);
                    }
                },
                defaultTheme: false
            }
        });
    };
</script>
