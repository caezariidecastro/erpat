<style>
    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    tr {
        padding: 0;
    }

    td, th {
        padding: 0;
    }
</style>

<?php 

        foreach($lists as $index => $item) { 

            //Generate QRCode
            $this->load->library('ImageEditor');
            $qrcode = array(
                "code" => "http://go.erpat.app/bse/".$item->uuid,
                "uuid" => strtoupper($item->uuid)
            );
            $image_data = (new ImageEditor())->qrcode($qrcode);

?>
            <div style="display: block; background-color: white; margin: 0;">
                <img src="<?= $image_data["base64"] ?>" width="600" height="337"/>
            </div>
<?php
        }