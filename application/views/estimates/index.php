<div id="page-content" class="p20 clearfix">
    <div class="panel clearfix">
        <ul id="estimate-tabs" data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang('estimates'); ?></h4></li>
            <li><a id="monthly-estimate-button" class="active" role="presentation" href="javascript:;" data-target="#monthly-estimates"><?php echo lang("monthly"); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("estimates/yearly/"); ?>" data-target="#yearly-estimates"><?php echo lang('yearly'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("estimate_requests"); ?>" data-target="#estimate-requests"><?php echo lang('requests'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("estimate_requests/estimate_forms"); ?>" data-target="#estimate-request-forms"><?php echo lang('forms'); ?></a></li>
            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                    <?php echo modal_anchor(get_uri("estimates/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_estimate'), array("class" => "btn btn-default", "title" => lang('add_estimate'), "id" => "add_estimate_button")); ?>
                    <?php echo modal_anchor(get_uri("estimate_requests/request_an_estimate_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('create_estimate_request'), array("class" => "btn btn-default", "title" => lang('create_estimate_request'), "id" => "add_create_estimate_request_button")); ?>
                    <?php echo modal_anchor(get_uri("estimate_requests/estimate_request_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_form'), array("class" => "btn btn-default", "title" => lang('add_form'), "id" => "add_form")); ?>
                </div>
            </div>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="monthly-estimates">
                <div class="table-responsive">
                    <table id="monthly-estimate-table" class="display" cellspacing="0" width="100%">   
                    </table>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="yearly-estimates"></div>
            <div role="tabpanel" class="tab-pane fade" id="estimate-requests">
                <?php $this->load->view('estimate_requests/index')?>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="estimate-request-forms">
                <?php $this->load->view('estimate_requests/estimate_forms')?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    loadEstimatesTable = function (selector, dateRange) {
        $(selector).appTable({
            source: '<?php echo_uri("estimates/list_data") ?>',
            order: [[0, "desc"]],
            dateRangeType: dateRange,
            filterDropdown: [{name: "status", class: "w150", options: <?php $this->load->view("estimates/estimate_statuses_dropdown"); ?>}],
            columns: [
                {title: "<?php echo lang("estimate") ?> ", "class": "w15p"},
                {title: "<?php echo lang("client") ?>"},
                {visible: false, searchable: false},
                {title: "<?php echo lang("type") ?>"},
                {title: "<?php echo lang("estimate_date") ?>", "iDataSort": 2, "class": "w20p"},
                {title: "<?php echo lang("amount") ?>", "class": "text-right w20p"},
                {title: "<?php echo lang("status") ?>", "class": "text-center"}
                <?php echo $custom_field_headers; ?>,
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: combineCustomFieldsColumns([0, 1, 3, 4, 5], '<?php echo $custom_field_headers; ?>'),
            xlsColumns: combineCustomFieldsColumns([0, 1, 3, 4, 5], '<?php echo $custom_field_headers; ?>'),
            summation: [{column: 5, dataType: 'currency', currencySymbol: AppHelper.settings.currencySymbol}]
        });
    };

    $(document).ready(function () {
        loadEstimatesTable("#monthly-estimate-table", "monthly");

        setInterval(function(){
            let tab_title = $("#estimate-tabs").find("li.active").text();
            tab_title == "Requests" ? $('#add_create_estimate_request_button').show() : $('#add_create_estimate_request_button').hide();
            tab_title == "Forms" ? $('#add_form').show() : $('#add_form').hide();
            tab_title == "Monthly" || tab_title == "Yearly" ? $('#add_estimate_button').show() : $('#add_estimate_button').hide();
        }, 200)
    });

</script>