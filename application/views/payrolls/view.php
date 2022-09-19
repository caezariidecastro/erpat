<style>
    .item-row{
        padding-top: 8px;
        padding-right: 15px;
        padding-bottom: 8px;
        padding-left: 0px;
    }

    #item-details-preview{
        display:  none;
    }
</style>

<div id="page-content" class="clearfix">
    <div class="row ml15 mr15">
        <div class="page-title clearfix mt15">
            <h1>
                <?= get_payroll_id($payroll_info->id); ?> - <?= $status ?>
            </h1>
            <div class="title-button-group mr15">
                <?php if( $payroll_info->status == "ongoing" ) { ?>
                <?php echo ajax_anchor(get_uri("payrolls/recalculate/" . $payroll_info->id), "<i class='fa fa-calculator'></i> " . lang('recalculate'), array("data-reload-on-success" => "1", "class"=>"btn btn-default")); ?> 
                <?php echo modal_anchor(get_uri("payrolls/lock_payment/".$payroll_info->id), "<i class='fa fa-money'></i> " . lang('lock_payment'), array("class" => "btn btn-default", "title" => lang('payslip_preview'), "data-post-payroll_id" => $payroll_info->id)); ?>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="row mt20 ml15 mr15">
        <div class="panel panel-default p15 b-t">   
            <div class="clearfix">
                <h4> <?= lang('payroll_details'); ?></h4>
            </div>

            <div class="clearfix b-t b-info">
                <div class="col-md-12 mt20 mb20" style="text-align: center;">
                    <div class="row mb15">
                        <div class="col-md-3"> 
                            <h6>DEPARTMENT</h6><input id="fullname" type="text" name="fullname" value="<?= $department ?>" style="text-align: center;" disabled></input>
                        </div>
                        <div class="col-md-3"> 
                            <h6>START DATE</h6><input name="job_title" value="<?= $start_date ?>" style="text-align: center;" disabled></input>
                        </div>
                        <div class="col-md-3"> 
                            <h6>END DATE</h6><input name="department" value="<?= $end_date ?>" style="text-align: center;" disabled></input>
                        </div>
                        <div class="col-md-3"> 
                            <h6>PAYMENT DATE</h6><input name="monthly_salary" value="<?= $pay_date ?>" style="text-align: center;" disabled></input>
                        </div>
                    </div>  
                </div>
            </div>

            <p class="b-t b-info pt10 m15"><?php echo nl2br($payroll_info->note); ?></p>
        </div>
    </div>
    
    <div class="row ml15 mr15">
        <div class="box">
            <div class="box-content message-view">
                <input type="hidden" id="item_id">
                <div class="col-sm-12 col-md-5 pl0 pr10">
                    <div id="message-list-box" class="panel panel-default">
                        <div class="panel-heading clearfix">
                            <div class="pull-left p5" style="font-size: 18px;">
                                <?= lang('payslips')?>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="payslip-table" class="display" cellspacing="0" width="100%">            
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-7 p0" id="item-details-placeholder">
                    <div class="panel panel-default p15 b-t">
                        <div class="clearfix">
                            <h4><span id="payslip-section-head">Preview</span></h4>
                        </div>

                        <div class="clearfix b-t b-info ">
                            <div id="payslip-section" class="col-md-12 mt20 mb20" style="text-align: center;">
                                <?= lang('select_a_payslip'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
    $(document).ready(function () {

        var optionVisibility = false;
        if ("<?php echo $can_edit_payrolls ?>") {
            optionVisibility = true;
        }

        <?php //TODO: LISTING OF PAYSLIPS ?>
        $("#payslip-table").appTable({
            source: '<?php echo_uri("payrolls/payslip_list_data/" . $payroll_info->id . "/") ?>',
            //order: [[0, "asc"]],
            //filterDropdown: [
                //{id: "user_select2_filter", name: "user_select2_filter", class: "w200", options: <?php //echo json_encode($user_select2); ?>},
                //{id: "department_select2_filter", name: "department_select2_filter", class: "w200", options: <?php //echo json_encode($department_select2); ?>},
            //],
            columns: [
                {title: '<?php echo lang("payslip_id") ?>'},
                {title: '<?php echo lang("employee") ?>', "class": "w15p", "iDataSort": 1},
                {title: '<?php echo lang("work_hour") ?>'},
                {title: '<?php echo lang("gross_pay") ?>', "class": "text-right w10p"},
                {title: '<?php echo lang("tax_due") ?>', "class": "text-right w10p"},
                {title: '<?php echo lang("net_pay") ?>', "class": "text-right w10p"},
                {title: '<?php echo lang("status") ?>'},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100", visible: optionVisibility}
            ],
            summation: [
                {column: 2, dataType: 'number'},
                {column: 4, dataType: 'currency'},
                {column: 5, dataType: 'currency'},
                {column: 6, dataType: 'currency'},
            ],
            //tableRefreshButton: true,
            onInitComplete: function () {
                $('.override_btn').on('click', function() {
                    appLoader.show();
                    var id = $( this ).attr('id');
                    var page = $( this ).attr('name');

                    $.ajax({
                        url: "<?php echo get_uri("payrolls/"); ?>"+page+'/'+id,
                        success: function (result) {
                            appLoader.hide();
                            const pageName = page.charAt(0).toUpperCase() + page.slice(1)+' <?= lang('payslip'); ?>';
                            $("#payslip-section-head").html(pageName);
                            $("#payslip-section").html(result);
                        }
                    });
                    
                });
            },
            onDeleteSuccess: function (result) {
                
            },
            onUndoSuccess: function (result) {
                $("#payroll-total-section").html(result.payroll_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.payroll_id);
                }
            }
        });

        //modify the delete confirmation texts
        // $("#confirmationModalTitle").html("<?php //echo lang('cancel') . "?"; ?>");
        // $("#confirmDeleteButton").html("<i class='fa fa-times'></i> <?php //echo lang("cancel"); ?>");

        // var payslipsTable = $('#payslip-table').DataTable();
        // $('#search-items').keyup(function () {
        //     payslipsTable.search($(this).val()).draw();
        // });
    });

    //print payroll
    $("#print-payroll-btn").click(function () {
        appLoader.show();

        $.ajax({
            url: "<?php echo get_uri('payrolls/print_payroll/' . $payroll_info->id) ?>",
            dataType: 'json',
            success: function (result) {
                if (result.success) {
                    document.body.innerHTML = result.print_view; //add payroll's print view to the page
                    $("html").css({"overflow": "visible"});

                    setTimeout(function () {
                        window.print();
                    }, 200);
                } else {
                    appAlert.error(result.message);
                }

                appLoader.hide();
            }
        });
    });

    //reload page after finishing print action
    window.onafterprint = function () {
        location.reload();
    };

</script>

<?php
    //required to send email 
    load_css(array(
        "assets/js/summernote/summernote.css",
    ));
    load_js(array(
        "assets/js/summernote/summernote.min.js",
    ));
?>

