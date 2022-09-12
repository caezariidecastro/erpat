<style>
    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    tr {
        padding: 5px;
    }

    td, th {
        padding: 5px;
    }

    .body-color {
        background-color: #f3cae2;
    }
</style>

<table class="body-color">
    <?php foreach($lists as $index => $item) { ?>
        <tr>
            <td width="30%" style="display: block; border-bottom: 1px solid grey;">
                <div style="text-align: center; border-right: 2px dashed grey;">
                    <img src="data:image/png;base64,<?= get_qrcode_image($item, false, 6) ?>" width="100" height="100"/>
                    <label><?= $item ?></label>
                </div>
            </td>
            <td width="70%" style="display: block; border-bottom: 1px solid grey;">
                <div style="text-align: center;">
                    <label><?= get_setting('company_name') ?></label>
                    <div style="background-color: white; display: block; width: 100px;">
                        <img src="data:image/png;base64,<?= get_barcode_image($item, false) ?>"/>
                    </div>
                    <label><?= $item ?></label>
                </div>
            </td>
        </tr>
    <?php } ?>
</table>