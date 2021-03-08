<table style="color: #444; width: 100%;">
    <tr class="invoice-preview-header-row">
        <td style="width: 45%; vertical-align: top;">
            <?php $this->load->view('invoices/invoice_parts/company_logo'); ?>
        </td>
        <td class="hidden-invoice-preview-row" style="width: 20%;"></td>
        <td class="invoice-info-container invoice-header-style-one" style="width: 35%; vertical-align: top; text-align: right"><?php
            $data = array(
                "vendor_info" => $vendor_info,
                "color" => $color,
                "purchase_return_info" => $purchase_return_info
            );
            $this->load->view('purchase_order_returns/parts/purchase_return_info', $data);
            ?>
        </td>
    </tr>
    <tr>
        <td style="padding: 5px;"></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td><?php
            $this->load->view('invoices/invoice_parts/bill_from', $data);
            ?>
        </td>
        <td></td>
        <td><?php
            $this->load->view('purchase_order_returns/parts/bill_to', $data);
            ?>
        </td>
    </tr>
</table>