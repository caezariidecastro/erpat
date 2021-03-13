<div style=" margin: auto;">
    <?php
    $color = get_setting("invoice_color");
    if (!$color) {
        $color = "#2AA384";
    }
    $invoice_style = get_setting("invoice_style");
    $data = array(
        "color" => $color,
        "payroll_info" => $payroll_info
    );

    if ($invoice_style === "style_2") {
        $this->load->view('payroll/parts/header_style_2.php', $data);
    } else {
        $this->load->view('payroll/parts/header_style_1.php', $data);
    }
    ?>
</div>

<br />

<table class="table-responsive" style="width: 100%; color: #444;">            
    <tr style="font-weight: bold; background-color: <?php echo $color; ?>; color: #fff;  ">
        <th style="width: 50%; border-right: 1px solid #eee;"> <?php echo lang("account"); ?> </th>
        <th style="text-align: center;  width: 25%; border-right: 1px solid #eee;"> <?php echo lang("payment_method"); ?></th>
        <th style="text-align: right;  width: 25%; border-right: 1px solid #eee;"> <?php echo lang("amount"); ?></th>
    </tr>
    <tr style="background-color: #f4f4f4; ">
        <td style="text-align: left; width: 50%; border: 1px solid #fff;"> <?php echo $payroll_info->account_name?></td>
        <td style="text-align: center; width: 25%; border: 1px solid #fff;"> <?php echo $payroll_info->payment_method?></td>
        <td style="text-align: right; width: 25%; border: 1px solid #fff;"> <?php echo to_currency($payroll_info->amount); ?></td>
    </tr>
</table>
<?php if ($payroll_info->note) { ?>
    <br />
    <br />
    <div style="border-top: 2px solid #f2f2f2; color:#444; padding:0 0 20px 0;"><br /><?php echo nl2br($payroll_info->note); ?></div>
<?php }?>
