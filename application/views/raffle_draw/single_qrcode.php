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

<table>
    <?php 
        $column = 3;
        $current = 0;
        $counter = 0;

        foreach($lists as $index => $item) { 
            $counter += 1;
        
            if($current < $column) {
                echo "<tr>";
            }
    ?>
            
                <?php
                    $this->load->library('ImageEditor');
                    $qrcode = array(
                        "code" => "http://go.erpat.app/bse/".$item->uuid,
                        "uuid" => strtoupper($item->uuid)
                    );
                    $image_data = (new ImageEditor())->qrcode($qrcode);
                ?>
                <td width="33%" style="display: block;">
                    <div style="text-align: center;">
                    <img src="<?= $image_data["base64"] ?>" width="600" height="337"/>
                    </div>
                </td>
                
            </tr>
    <?php 
            $current += 1;
            if($counter == $column) {
                echo "</tr>";
                if($current <= count($lists)) {
                    echo "<tr>";
                }
                $counter = 0;
            }
        } 
    ?>
</table>