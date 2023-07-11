
<div id="page-content" class="clearfix p20">
    <div class="panel clearfix">
        <ul id="payrolls-tabs" data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang("payrolls"); ?></h4></li>
            <li><a id="monthly-expenses-button"  role="presentation" class="active" href="javascript:;" data-target="#payrolls-list"><?php echo lang("entries"); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("payrolls/earnings/"); ?>" data-target="#earnings-list"><?php echo lang('earnings'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("payrolls/contributions/"); ?>" data-target="#contributions-list"><?php echo lang('deductions'); ?></a></li>
            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                    <?php //if ($can_edit_payrolls) { ?>
                        <?php echo modal_anchor(get_uri("payrolls/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_payroll'), array("class" => "btn btn-default", "title" => lang('add_payroll'), "id" => "add_payrolls_button")); ?>
                        <?php echo modal_anchor(get_uri("payrolls/contribution_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_autocontri_button'), array("class" => "btn btn-default", "title" => lang('add_autocontri_button'), "id" => "add_autocontri_button")); ?>
                    <?php //} ?>
                </div>
            </div>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="payrolls-list">
                <div class="table-responsive">
                    <table id="payroll-table" class="display" cellspacing="0" width="100%">            
                    </table>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="earnings-list"></div>
            <div role="tabpanel" class="tab-pane fade" id="contributions-list"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#payroll-table").appTable({
            source: '<?php echo_uri("fas/payrolls/list_data") ?>',
            filterDropdown: [
                {id: "department_select2_filter", name: "department_select2_filter", class: "w200", options: <?php echo json_encode($department_select2); ?>}
            ],
            dateRangeType: "monthly",
            order: [[7, 'desc']],
            columns: [
                {visible: false, searchable: false},
                {title: "<?php echo lang('payroll_id') ?>"},
                {title: "<?php echo lang('department') ?>"},

                {title: "<?php echo lang('start_date') ?>"},
                {title: "<?php echo lang('end_date') ?>"},
                {title: "<?php echo lang('payment_date') ?>"},
                {title: "<?php echo lang('schedule_hours') ?>"},

                {title: "<?php echo lang('account') ?>"},

                {title: "<?php echo lang('total_payslips') ?>", "class": "text-right"},
                {title: "<?php echo lang('tax_table') ?>",},
                {title: "<?php echo lang('status') ?>", "class": "text-center"},

                {title: "<?php echo lang('note') ?>"}, //remarks
                {title: "<?php echo lang('assigned_to') ?>",},
                {title: "<?php echo lang('created_by') ?>",},
                {title: "<?php echo lang('date_created') ?>",},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center dropdown-option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
            summation: [{column: 6, dataType: 'number'}],
            tableRefreshButton: true,
        });

        setInterval(function(){
            $("#payrolls-tabs").find("li.active").text() == "Entries" ? $('#add_payrolls_button').show() : $('#add_payrolls_button').hide()
            $("#payrolls-tabs").find("li.active").text() == "Deductions" ? $('#add_autocontri_button').show() : $('#add_autocontri_button').hide()
        }, 200)
    });
</script>