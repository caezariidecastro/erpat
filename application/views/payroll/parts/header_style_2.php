<table style="color: #444; width: 100%;">
    <tr class="invoice-preview-header-row">
        <td class="invoice-info-container invoice-header-style-two" style="width: 40%; vertical-align: top;"><?php
            $data = array(
                "color" => $color,
                "payroll_info" => $payroll_info
            );
            $this->load->view('payroll/parts/info', $data);
            ?>
        </td>
        <td class="hidden-invoice-preview-row" style="width: 20%;"></td>
        <td style="width: 40%; vertical-align: top;">
            <?php $this->load->view('payroll/parts/company_logo'); ?>
        </td>
    </tr>
    <tr>
        <td style="padding: 5px;"></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td><?php
            $this->load->view('payroll/parts/to', $data);
            ?>
        </td>
        <td></td>

    </tr>
</table>