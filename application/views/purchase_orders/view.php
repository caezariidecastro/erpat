<!-- WORKAROUND -->

<div id="page-content" class="clearfix">
    <div style="max-width: 1000px; margin: auto;">
        <div class="page-title clearfix mt15">
            <h1><?php echo get_purchase_order_id($purchase_order_info->id); ?>
            </h1>
            <div class="title-button-group">
                <span class="dropdown inline-block mt10">
                    <button class="btn btn-info dropdown-toggle  mt0 mb0" type="button" data-toggle="dropdown" aria-expanded="true">
                        <i class='fa fa-cogs'></i> <?php echo lang('actions'); ?>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <?php 
                            if ($purchase_order_info->status !== "cancelled") { ?>
                            <li role="presentation"><?php echo modal_anchor(get_uri("purchase_orders/send_purchase_modal_form/" . $purchase_order_info->id), "<i class='fa fa-envelope-o'></i> " . lang('email_purchase_order_to_vendor'), array("title" => lang('email_purchase_order_to_vendor'), "data-post-id" => $purchase_order_info->id, "role" => "menuitem", "tabindex" => "-1")); ?> </li>
                        <?php } ?>
                        <li role="presentation"><?php echo anchor(get_uri("purchase_orders/download_pdf/" . $purchase_order_info->id), "<i class='fa fa-download'></i> " . lang('download_pdf'), array("title" => lang('download_pdf'))); ?> </li>
                        <li role="presentation"><?php echo anchor(get_uri("purchase_orders/download_pdf/" . $purchase_order_info->id . "/view"), "<i class='fa fa-file-pdf-o'></i> " . lang('view_pdf'), array("title" => lang('view_pdf'), "target" => "_blank")); ?> </li>
                        <li role="presentation"><?php echo anchor(get_uri("purchase_orders/preview/" . $purchase_order_info->id . "/1"), "<i class='fa fa-search'></i> " . lang('purchase_preview'), array("title" => lang('purchase_preview'), "target" => "_blank")); ?> </li>
                        <li role="presentation"><?php echo js_anchor("<i class='fa fa-print'></i> " . lang('print_purchase'), array('title' => lang('print_purchase'), 'id' => 'print-purchase-btn')); ?> </li>

                        <li role="presentation" class="divider"></li>

                        <?php if ($purchase_order_info->status !== "cancelled") { ?>
                            <li role="presentation"><?php echo modal_anchor(get_uri("purchase_orders/modal_form"), "<i class='fa fa-edit'></i> " . lang('edit_purchase'), array("title" => lang('edit_purchase'), "data-post-id" => $purchase_order_info->id, "role" => "menuitem", "tabindex" => "-1")); ?> </li>
                        <?php } ?>

                    </ul>
                </span>
                <?php if ($purchase_order_info->status !== "cancelled") { ?>
                    <?php echo modal_anchor(get_uri("purchase_orders/material_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_material_inventory'), array("class" => "btn btn-default", "title" => lang('add_material_inventory'), "data-post-purchase_id" => $purchase_order_info->id, "data-post-vendor_id" => $purchase_order_info->vendor_id)); ?>
                    <?php echo modal_anchor(get_uri("purchase_orders/budget_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_budget'), array("class" => "btn btn-default", "title" => lang('add_budget'), "data-post-purchase_id" => $purchase_order_info->id)); ?>
                <?php } ?>
            </div>
        </div>

        <div id="purchase-order-status-bar">
            <?php $this->load->view("purchase_orders/purchase_order_status_bar"); ?>
        </div>

        <div class="mt15">
            <div class="panel panel-default p15 b-t">
                <div class="clearfix p20">
                    <!-- small font size is required to generate the pdf, overwrite that for screen -->
                    <style type="text/css"> .invoice-meta {font-size: 100% !important;}</style>

                    <?php
                    $color = get_setting("invoice_color");
                    if (!$color) {
                        $color = "#2AA384";
                    }
                    $invoice_style = get_setting("invoice_style");
                    $data = array(
                        "vendor_info" => $vendor_info,
                        "color" => $color,
                        "purchase_order_info" => $purchase_order_info
                    );

                    if ($invoice_style === "style_2") {
                        $this->load->view('purchase_orders/parts/header_style_2', $data);
                    } else {
                        $this->load->view('purchase_orders/parts/header_style_1', $data);
                    }
                    ?>
                </div>

                <div class="table-responsive mt15 pl15 pr15">
                    <table id="purchase-order-material-table" class="display" width="100%">            
                    </table>
                </div>

                <?php
                $files = @unserialize($purchase_order_info->files);
                if ($files && is_array($files) && count($files)) {
                    ?>
                    <div class="clearfix">
                        <div class="col-md-12 mt20">
                            <p class="b-t"></p>
                            <div class="mb5 strong"><?php echo lang("files"); ?></div>
                            <?php
                            foreach ($files as $key => $value) {
                                $file_name = get_array_value($value, "file_name");
                                echo "<div>";
                                echo js_anchor(remove_file_prefix($file_name), array("data-toggle" => "app-modal", "data-sidebar" => "0", "data-url" => "purchase_orders/file_preview/" . $purchase_order_info->id . "/" . $key));
                                echo "</div>";
                            }
                            ?>
                        </div>
                    </div>
                <?php } ?>

                <p class="b-t b-info pt10 m15"><?php echo nl2br($purchase_order_info->remarks); ?></p>

            </div>
        </div>

        <div class="panel panel-default">
            <div class="tab-title clearfix">
                <h4> <?php echo lang('budget_list'); ?></h4>
            </div>
            <div class="table-responsive">
                <table id="purchase-order-budget-table" class="display" cellspacing="0" width="100%">            
                </table>
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
    $(document).ready(function () {
        $("#purchase-order-material-table").appTable({
            source: '<?php echo_uri("purchase_orders/material_list_data/" . $purchase_order_info->id . "/" . $purchase_order_info->vendor_id) ?>',
            order: [[0, "asc"]],
            hideTools: true,
            displayLength: 100,
            columns: [
                {title: '<?php echo lang("material") ?> ', "bSortable": false},
                {title: '<?php echo lang("quantity") ?>', "class": "text-right w15p", "bSortable": false},
                {title: '<?php echo lang("amount") ?>', "class": "text-right w15p", "bSortable": false},
                {title: '<?php echo lang("total") ?>', "class": "text-right w15p", "bSortable": false},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100", "bSortable": false, visible: true}
            ],
            summation: [{column: 3, dataType: 'number'}],
        });

        $("#purchase-order-budget-table").appTable({
            source: '<?php echo_uri("purchase_orders/budget_list_data/" . $purchase_order_info->id) ?>',
            order: [[0, "desc"]],
            columns: [
                {title: '<?php echo lang("created_by") ?>'},
                {title: '<?php echo lang("created_on") ?>'},
                {title: '<?php echo lang("amount") ?>', "class": "text-right"},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100", visible: true},
            ],
            summation: [{column: 2, dataType: 'number'}],
        });
    });

    updatePurchaseOrderStatusBar = function (purchaseOrderId) {
        $.ajax({
            url: "<?php echo get_uri("purchase_orders/get_purchase_order_status_bar"); ?>/" + purchaseOrderId,
            success: function (result) {
                if (result) {
                    $("#purchase-order-status-bar").html(result);
                }
            }
        });
    };

    $("#print-purchase-btn").click(function () {
        appLoader.show();

        $.ajax({
            url: "<?php echo get_uri('purchase_orders/print_purchase_order/' . $purchase_order_info->id) ?>",
            dataType: 'json',
            success: function (result) {
                if (result.success) {
                    document.body.innerHTML = result.print_view;
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

