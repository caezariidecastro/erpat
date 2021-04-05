<div style=" margin: auto;">
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

<br />

<table class="table-responsive" style="width: 100%; color: #444;">            
    <tr style="font-weight: bold; background-color: <?php echo $color; ?>; color: #fff;  ">
        <th style="width: 45%; border-right: 1px solid #eee;"> <?php echo lang("material"); ?> </th>
        <th style="text-align: center;  width: 15%; border-right: 1px solid #eee;"> <?php echo lang("quantity"); ?></th>
        <th style="text-align: right;  width: 20%; border-right: 1px solid #eee;"> <?php echo lang("amount"); ?></th>
        <th style="text-align: right;  width: 20%; "> <?php echo lang("total"); ?></th>
    </tr>
    <?php
    foreach ($purchase_return_materials as $material) {
        ?>
        <tr style="background-color: #f4f4f4; ">
            <td style="width: 45%; border: 1px solid #fff; padding: 10px;"><?php echo $material->title; ?></td>
            <td style="text-align: center; width: 15%; border: 1px solid #fff;"> <?php echo $material->quantity . " " . $material->unit_type; ?></td>
            <td style="text-align: right; width: 20%; border: 1px solid #fff;"> <?php echo $material->rate ?></td>
            <td style="text-align: right; width: 20%; border: 1px solid #fff;"> <?php echo number_with_decimal($material->total); ?></td>
        </tr>
    <?php } ?>
    <tr>
        <td colspan="3" style="text-align: right;"><?php echo lang("total"); ?></td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: <?php echo $color; ?>; color: #fff;">
            <?php echo number_with_decimal($purchase_return_info->total); ?>
        </td>
    </tr>
</table>
<?php if ($purchase_return_info->remarks) { ?>
    <br />
    <br />
    <div style="border-top: 2px solid #f2f2f2; color:#444; padding:0 0 20px 0;"><br /><?php echo nl2br($purchase_return_info->remarks); ?></div>
<?php } else { ?> <!-- use table to avoid extra spaces -->
    <br /><br /><table class="invoice-pdf-hidden-table" style="border-top: 2px solid #f2f2f2; margin: 0; padding: 0; display: block; width: 100%; height: 10px;"></table>
<?php } ?>
<span style="color:#444; line-height: 14px;">
    <?php echo get_setting("invoice_footer"); ?>
</span>

