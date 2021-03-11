<div id="page-content" class="clearfix">
    <?php
    load_css(array(
        "assets/css/invoice.css",
    ));
    ?>

    <div id="print-wrapper" class="invoice-preview print-invoice">
        <?php echo $details?>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("html, body").addClass("dt-print-view");

        document.body.innerHTML = $("#print-wrapper").html();
        window.print();
    });

    window.onafterprint = function () {
        window.close();
    };
</script>