<table style="color: #444; width: 100%;">
    <tr class="invoice-preview-header-row">
        <td style="width: 45%; vertical-align: top;">
            <?php $this->load->view('payroll/parts/company_logo'); ?>
        </td>
        <td class="hidden-invoice-preview-row" style="width: 20%;"></td>
        <td class="invoice-info-container invoice-header-style-one" style="width: 35%; vertical-align: top; text-align: right"><?php
            $data = array(
                "payroll_info" => $payroll_info,
                "color" => $color
            );
            $this->load->view('payroll/parts/info', $data);
            ?>
        </td>
    </tr>
    <tr>
        <td style="padding: 5px;"></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td><?php
            $this->load->view('payroll/parts/to', $data);
            ?>
        </td>
    </tr>
</table>