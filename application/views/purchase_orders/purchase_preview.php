<div id="page-content" class="p20 clearfix">
    <?php
    load_css(array(
        "assets/css/invoice.css",
    ));
    ?>

    <div class="invoice-preview">
        <?php if ($this->login_user->user_type === "supplier") {
            echo "<div class='text-center'>" . anchor("purchase_orders/download_pdf/" . $purchase_order_info->id, lang("download_pdf"), array("class" => "btn btn-default round")) . "</div>";
        }?>

        <?php
        if ($show_close_preview) {
            echo "<div class='text-center'>" . anchor("pid/purchases/view/" . $purchase_order_info->id, lang("close_preview"), array("class" => "btn btn-default round")) . "</div>";
        }
        ?>

        <div class="invoice-preview-container bg-white mt15">
            <div class="col-md-12">
                <div class="ribbon"><?php echo $purchase_order_status_label; ?></div>
            </div>

            <?php
            echo $purchase_preview;
            ?>
        </div>

    </div>
</div>
