<!-- WORKAROUND -->

<div id="page-content" class="clearfix">
    <div style="max-width: 1000px; margin: auto;">
        <div class="page-title clearfix mt15">
            <h1 style="padding-right: 5px"><?php echo get_purchase_return_id($purchase_return_info->id); ?> </h1>
            <div class="title-button-group">
                <span class="dropdown inline-block mt10">
                    <button class="btn btn-info dropdown-toggle  mt0 mb0" type="button" data-toggle="dropdown" aria-expanded="true">
                        <i class='fa fa-cogs'></i> <?php echo lang('actions'); ?>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <?php 
                            if ($purchase_return_info->status !== "cancelled") { ?>
                            <li role="presentation"><?php echo modal_anchor(get_uri("mes/PurchaseReturns/send_return_modal_form/" . $purchase_return_info->id), "<i class='fa fa-envelope-o'></i> " . lang('email_returns_to_vendor'), array("title" => lang('email_returns_to_vendor'), "data-post-id" => $purchase_return_info->id, "role" => "menuitem", "tabindex" => "-1")); ?> </li>
                        <?php } ?>
                        <li role="presentation"><?php echo anchor(get_uri("mes/PurchaseReturns/download_pdf/" . $purchase_return_info->id), "<i class='fa fa-download'></i> " . lang('download_pdf'), array("title" => lang('download_pdf'))); ?> </li>
                        <li role="presentation"><?php echo anchor(get_uri("mes/PurchaseReturns/download_pdf/" . $purchase_return_info->id . "/view"), "<i class='fa fa-file-pdf-o'></i> " . lang('view_pdf'), array("title" => lang('view_pdf'), "target" => "_blank")); ?> </li>
                        <li role="presentation"><?php echo anchor(get_uri("mes/PurchaseReturns/preview/" . $purchase_return_info->id . "/1"), "<i class='fa fa-search'></i> " . lang('return_preview'), array("title" => lang('return_preview'))); ?> </li>
                        <li role="presentation"><?php echo js_anchor("<i class='fa fa-print'></i> " . lang('print_return'), array('title' => lang('print_return'), 'id' => 'print-return-btn')); ?> </li>
                        
                        <li role="presentation" id="status-actions" class="divider"></li>
                        <?php if ($purchase_return_info->status == "draft") { ?>
                            <li role="presentation" id="mark-as-cancelled"><?php echo modal_anchor(get_uri("mes/PurchaseReturns/cancel_form"), "<i class='fa fa-close'></i> " . lang('mark_as_cancelled'), array("title" => lang('mark_as_cancelled'), "data-post-id" => $purchase_return_info->id)); ?> </li>
                            <li role="presentation" id="mark-as-completed"><?php echo modal_anchor(get_uri("mes/PurchaseReturns/complete_form"), "<i class='fa fa-check'></i> " . lang('mark_as_completed'), array("title" => lang('mark_as_completed'), "data-post-id" => $purchase_return_info->id)); ?> </li>
                        <?php } ?>

                        <?php if ($purchase_return_info->status == "cancelled") { ?>
                            <li role="presentation" id="mark-as-draft"><?php echo modal_anchor(get_uri("mes/PurchaseReturns/draft_form"), "<i class='fa fa-file'></i> " . lang('mark_as_draft'), array("title" => lang('mark_as_draft'), "data-post-id" => $purchase_return_info->id)); ?> </li>
                            <li role="presentation" id="mark-as-completed"><?php echo modal_anchor(get_uri("mes/PurchaseReturns/complete_form"), "<i class='fa fa-check'></i> " . lang('mark_as_completed'), array("title" => lang('mark_as_completed'), "data-post-id" => $purchase_return_info->id)); ?> </li>
                        <?php } ?>

                        <?php if ($purchase_return_info->status == "completed") { ?>
                            <li role="presentation" id="mark-as-draft"><?php echo modal_anchor(get_uri("mes/PurchaseReturns/draft_form"), "<i class='fa fa-file'></i> " . lang('mark_as_draft'), array("title" => lang('mark_as_draft'), "data-post-id" => $purchase_return_info->id)); ?> </li>
                            <li role="presentation" id="mark-as-cancelled"><?php echo modal_anchor(get_uri("mes/PurchaseReturns/cancel_form"), "<i class='fa fa-close'></i> " . lang('mark_as_cancelled'), array("title" => lang('mark_as_cancelled'), "data-post-id" => $purchase_return_info->id)); ?> </li>
                        <?php } ?>

                        <?php if ($purchase_return_info->status !== "cancelled") { ?>
                            <li role="presentation" class="divider"></li>
                            <li role="presentation"><?php echo modal_anchor(get_uri("mes/PurchaseReturns/modal_form"), "<i class='fa fa-edit'></i> " . lang('edit_return'), array("title" => lang('edit_return'), "data-post-id" => $purchase_return_info->id, "role" => "menuitem", "tabindex" => "-1", "data-post-reload" => true)); ?> </li>
                        <?php } ?>

                    </ul>
                </span>
                <?php if ($purchase_return_info->status !== "cancelled") { ?>
                    <?php echo modal_anchor(get_uri("mes/PurchaseReturns/add_material_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_material_inventory'), array("class" => "btn btn-default", "title" => lang('add_material_inventory'), "data-post-purchase_id" => $purchase_return_info->purchase_id, "data-post-purchase_order_return_id" => $purchase_return_info->id)); ?>
                <?php } ?>
            </div>
        </div>

        <div>
            <?php $this->load->view("purchase_returns/purchase_return_status_bar"); ?>
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
                        "purchase_return_info" => $purchase_return_info
                    );

                    if ($invoice_style === "style_2") {
                        $this->load->view('purchase_returns/parts/header_style_2', $data);
                    } else {
                        $this->load->view('purchase_returns/parts/header_style_1', $data);
                    }
                    ?>
                </div>

                <div class="table-responsive mt15 pl15 pr15">
                    <table id="purchase-order-return-material-table" class="display" width="100%">            
                    </table>
                </div>

                <p class="b-t b-info pt10 m15"><?php echo nl2br($purchase_return_info->remarks); ?></p>

            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
    $(document).ready(function () {
        $("#purchase-order-return-material-table").appTable({
            source: '<?php echo_uri("mes/PurchaseReturns/material_list_data/" . $purchase_return_info->id) ?>',
            order: [[0, "asc"]],
            hideTools: true,
            displayLength: 100,
            columns: [
                {title: '<?php echo lang("material") ?> ', "bSortable": false},
                {title: '<?php echo lang("quantity") ?>', "class": "text-right w15p", "bSortable": false},
                {title: '<?php echo lang("amount") ?>', "class": "text-right w15p", "bSortable": false},
                {title: '<?php echo lang("total") ?>', "class": "text-right w15p", "bSortable": false},
                {title: '<?php echo lang("remarks") ?>', "class": "text-right w15p", "bSortable": false},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100", "bSortable": false, visible: true}
            ],
            summation: [{column: 3, dataType: 'number'}],
        });
    });

    $("#print-return-btn").click(function () {
        appLoader.show();

        $.ajax({
            url: "<?php echo get_uri('mes/PurchaseReturns/print_return/' . $purchase_return_info->id) ?>",
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

