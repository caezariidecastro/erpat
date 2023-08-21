<div class="panel panel-default">
    <div class="tab-title clearfix">
        <h4> <?php echo lang('payments'); ?></h4>
    </div>
    <div class="table-responsive">
        <table id="payroll-payment-table" class="display" cellspacing="0" width="100%">            
        </table>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        var optionVisibility = false;
        if ("<?php echo $can_edit_payrolls ?>") {
            optionVisibility = true;
        }

        <?php //TODO: LISTING OF PAYSLIPS ?>
        $("#payroll-payment-table").appTable({
            source: '<?php echo_uri("payrolls/payment_list_data/" . $payroll_info->id . "/") ?>',
            order: [[0, "asc"]],
            columns: [
                {targets: [0], visible: false, searchable: false},
                {visible: false, searchable: false},
                {title: '<?php echo lang("payment_date") ?> ', "class": "w15p", "iDataSort": 1},
                {title: '<?php echo lang("payment_method") ?>', "class": "w15p"},
                {title: '<?php echo lang("note") ?>'},
                {title: '<?php echo lang("amount") ?>', "class": "text-right w15p"},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100", visible: optionVisibility}
            ]
        });

        //modify the delete confirmation texts
        // $("#confirmationModalTitle").html("<?php echo lang('cancel') . "?"; ?>");
        // $("#confirmDeleteButton").html("<i class='fa fa-times'></i> <?php echo lang("cancel"); ?>");
    });

    updateInvoiceStatusBar = function (payrollId) {
        $.ajax({
            url: "<?php echo get_uri("payrolls/get_payroll_status_bar"); ?>/" + payrollId,
            success: function (result) {
                if (result) {
                    $("#payroll-status-bar").html(result);
                }
            }
        });
    };

</script>