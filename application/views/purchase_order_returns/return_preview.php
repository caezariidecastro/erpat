<div id="page-content" class="p20 clearfix">
    <?php
    load_css(array(
        "assets/css/invoice.css",
    ));
    ?>

    <div class="invoice-preview">
        <?php if ($this->login_user->user_type === "supplier") {
            echo "<div class='text-center'>" . anchor("purchase_order_returns/download_pdf/" . $purchase_return_info->id, lang("download_pdf"), array("class" => "btn btn-default round")) . "</div>";
        }?>

        <?php
        if ($show_close_preview) {
            echo "<div class='text-center'>" . anchor("pid/returns/view/" . $purchase_return_info->id, lang("close_preview"), array("class" => "btn btn-default round")) . "</div>";
        }
        ?>

        <div class="invoice-preview-container bg-white mt15">
            <div class="col-md-12">
                <div class="ribbon"><?php echo $purchase_return_status_label; ?></div>
            </div>

            <?php
            echo $return_preview;
            ?>
        </div>

    </div>
</div>
