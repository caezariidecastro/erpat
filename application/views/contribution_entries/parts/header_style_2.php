<table style="color: #444; width: 100%;">
    <tr class="invoice-preview-header-row">
        <td class="invoice-info-container invoice-header-style-two" style="width: 40%; vertical-align: top;"><?php
            $data = array(
                "color" => $color,
                "contribution_info" => $contribution_info
            );
            $this->load->view('contribution_entries/parts/info', $data);
            ?>
        </td>
        <td class="hidden-invoice-preview-row" style="width: 20%;"></td>
        <td style="width: 40%; vertical-align: top;">
            <?php $this->load->view('contribution_entries/parts/company_logo'); ?>
        </td>
    </tr>
    <tr>
        <td style="padding: 5px;"></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td><?php
            $this->load->view('contribution_entries/parts/to', $data);
            ?>
        </td>
        <td></td>

    </tr>
</table>