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
            <?php if( $payroll_info->status == "ongoing" ) { ?>
            <div class="title-button-group mr15">
                <?php echo ajax_anchor(get_uri("payrolls/recalculate/" . $payroll_info->id), "<i class='fa fa-calculator'></i> " . lang('recalculate'), array("data-reload-on-success" => "1", "class"=>"btn btn-danger")); ?> 
            </div>
            <div class="title-button-group">
                <span class="dropdown inline-block mt10">
                    <button class="btn btn-info dropdown-toggle  mt0 mb0" type="button" data-toggle="dropdown" aria-expanded="true">
                        <i class='fa fa-cogs'></i> <?php echo lang('actions'); ?>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li role="presentation"> <?php echo anchor(get_uri("payrolls/download_approved_payslip/" . $payroll_info->id . "/view"), "<i class='fa fa-file-pdf-o'></i> " . lang('export_pdf'), array("title" => lang('export_pdf'), "target" => "_blank")); ?> </li>
                        <li role="presentation"> <?php echo modal_anchor(get_uri("payrolls/lock_payment/".$payroll_info->id), "<i class='fa fa-money'></i> " . lang('lock_payment'), array( "title" => lang('lock_payment'), "data-post-payroll_id" => $payroll_info->id)); ?> </li>
                        <li role="presentation"> <?php echo modal_anchor(get_uri("payrolls/mark_as_cancelled/".$payroll_info->id), "<i class='fa fa-times'></i> " . lang('cancel'), array( "title" => lang('cancel'), "data-post-payroll_id" => $payroll_info->id)); ?> </li>
                    </ul>
                </span>
            </div>
            <?php } ?>
        </div>
    </div>

    <div class="row mt20 ml15 mr15">
        <div class="panel panel-default p15 b-t">   
            <div class="clearfix">
                <h4> <?= lang('payroll_details'); ?></h4>
            </div>

            <div class="clearfix b-t b-info">
                <div class="col-md-12 mt20 mb20" style="text-align: left;">
                    <div class="row mb15">
                        <div class="col-md-3"> 
                            <h6>DEPARTMENT</h6><input id="fullname" type="text" name="fullname" value="<?= $department ?>" style="text-align: center;" disabled></input>
                            <h6>CREATED BY</h6><input id="fullname" type="text" name="fullname" value="<?= $payroll_info->creator_name ?>" style="text-align: center;" disabled></input>
                        </div>
                        <div class="col-md-3"> 
                            <h6>OFFICER</h6><input id="fullname" type="text" name="fullname" value="<?= $payroll_info->signee_name ?>" style="text-align: center;" disabled></input>
                            <h6>ACCOUNTANT</h6><input id="fullname" type="text" name="fullname" value="<?= $payroll_info->accountant_name ?>" style="text-align: center;" disabled></input>
                        </div>
                        <div class="col-md-3"> 
                            <h6>START DATE</h6><input name="job_title" value="<?= $payroll_info->start_date ?>" style="text-align: center;" disabled></input>
                            <h6>END DATE</h6><input name="department" value="<?= $payroll_info->end_date ?>" style="text-align: center;" disabled></input>
                        </div>
                        <div class="col-md-3"> 
                            <h6>SCHED HOURS</h6><input id="fullname" type="text" name="fullname" value="<?= $payroll_info->sched_hours ?>" style="text-align: center;" disabled></input>
                            <h6>PAYMENT DATE</h6><input name="monthly_salary" value="<?= $payroll_info->pay_date ?>" style="text-align: center;" disabled></input>
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
                <div class="col-sm-12 pl0 pr10">
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
                <!-- <div class="col-sm-12 col-md-7 p0" id="item-details-placeholder">
                    <div class="panel panel-default p15 b-t">
                        <div class="clearfix">
                            <h4><span id="payslip-section-head">Preview</span></h4>
                        </div>

                        <div class="clearfix b-t b-info ">
                            <div id="payslip-section" class="col-md-12 mt20 mb20" style="text-align: center;">
                            </div>
                        </div>
                    </div>
                </div> -->
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

        $("#payslip-table").appTable({
            source: '<?php echo_uri("payrolls/payslip_list_data/" . $payroll_info->id . "/") ?>',
            order: [[0, "asc"]],
            filterDropdown: [
                {   name: "status", class: "w150", options: <?= json_encode(array(
                        array('id' => '', 'text'  => '- Status -'),
                        array('id' => 'draft', 'text'  => '- Draft -'),
                        array('id' => 'approved', 'text'  => '- Approved -'),
                        array('id' => 'rejected', 'text'  => '- Rejected -')
                    )); ?> 
                },
            ],
            columns: [
                {title: '<?php echo lang("payslip_id") ?>', "class": "w10p"},
                {title: '<?php echo lang("employee") ?>', "class": "w15p", "iDataSort": 1},
                {title: '<?php echo lang("basic_pay") ?>', "class": "text-right w10p"},
                {title: '<?php echo lang("work_hour") ?>', "class": "text-right w10p"},
                {title: '<?php echo lang("overtime") ?>', "class": "text-right w10p"},
                {title: '<?php echo lang("deductions") ?>', "class": "text-right w10p"},
                {title: '<?php echo lang("gross_pay") ?>', "class": "text-right w10p"},
                {title: '<?php echo lang("tax_due") ?>', "class": "text-right w10p"},
                {title: '<?php echo lang("net_pay") ?>', "class": "text-right w10p"},
                {title: '<?php echo lang("status") ?>', "class": "text-right w10p"},
                {title: '<?php echo lang("sent") ?>', "class": "text-right w10p"},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center dropdown-option w10p", visible: optionVisibility}
            ],
            summation: [
                {column: 2, dataType: 'number'},
                {column: 3, dataType: 'currency'},
                {column: 4, dataType: 'currency'},
                {column: 5, dataType: 'currency'},
                {column: 6, dataType: 'currency'},
                {column: 7, dataType: 'currency'},
                {column: 8, dataType: 'currency'},
            ],
            tableRefreshButton: true,
            onInitComplete: function () {
                $('.override_btn').on('click', function() {
                    appLoader.show();
                    var id = $( this ).attr('id');
                    var page = $( this ).attr('name');

                    let dataJson = {};
                    if(page === 'check') {
                        dataJson.start_date = $( this ).data("start_date");
                        dataJson.end_date = $( this ).data("end_date");
                    }
                    
                    $.ajax({
                        url: "<?php echo get_uri("payrolls/"); ?>"+page+'/'+id,
                        data: dataJson,
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

