<div class="panel">
    <div class="tab-title clearfix">
        <h4><?php echo lang('estimates'); ?></h4>
        <div class="title-button-group">
            <?php echo modal_anchor(get_uri("sales/Estimate_requests/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_estimate'), array("class" => "btn btn-default", "data-post-client_id" => $client_id, "title" => lang('add_estimate'))); ?>
        </div>
    </div>
    <div class="table-responsive">
        <table id="estimate-table" class="display" width="100%">
            <tfoot>
                <tr>
                    <th colspan="4" class="text-right"><?php echo lang("total") ?>:</th>
                    <th class="text-right" data-current-page="4"></th>
                    <th> </th>
                </tr>
                <tr data-section="all_pages">
                    <th colspan="4" class="text-right"><?php echo lang("total_of_all_pages") ?>:</th>
                    <th class="text-right" data-all-page="4"></th>
                    <th> </th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var currencySymbol = "<?php echo $lead_info->currency_symbol; ?>";
        $("#estimate-table").appTable({
            source: '<?php echo_uri("sales/Estimates/estimate_list_data_of_client/" . $client_id) ?>',
            order: [[0, "desc"]],
            columns: [
                {title: "<?php echo lang("estimate") ?>", "class": "w15p"},
                {title: "<?php echo lang("owner") ?>", "class": "text-center w20p"},
                { visible: false, searchable: false },
                {title: "<?php echo lang("estimate_date") ?>", "iDataSort": 2, "class": "w15p"},
                {title: "<?php echo lang("amount") ?>", "class": "text-center w15p"},
                {title: "<?php echo lang("status") ?>", "class": "text-center w10p"}
                <?php echo $custom_field_headers; ?>,
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"},
            ],
            summation: [{column: 4, dataType: 'currency', currencySymbol: currencySymbol}]
        });
    });
</script>