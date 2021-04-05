<?php
    $color = get_setting("invoice_color");
    if (!$color) {
        $color = "#2AA384";
    }
?>

<div class="invoice-preview-container bg-white mt15">
    <table style="width: 100%;">
        <tbody>
            <tr>
                <td><h4><strong>BOM: <?php echo $production_info->bill_of_material_title?></strong></h4></td>
                <td><h4><strong>Output: <?php echo $production_info->item_name . " (" . $production_info->bill_of_material_quantity . " " . $production_info->abbreviation . ")"?></strong></h4></td>
            </tr>
            <tr>
                <td><h4><strong>Quantity: <?php echo $production_info->quantity?></strong></h4></td>
                <td><h4><strong>Buffer: <?php echo $production_info->buffer?>%</strong></h4></td>
            </tr>
        </tbody>
    </table>
    <hr>
    <table id="production-materials-table" class="table-responsive" style="width: 100%; color: #444;">            
        <tr style="font-weight: bold; background-color: <?php echo $color; ?>; color: #fff;  ">
            <th style="width: 35%; border: 1px solid #eee; padding: 12px;"> <?php echo lang("material"); ?> </th>
            <th style="text-align: center;  width: 15%; border: 1px solid #eee; padding: 12px; padding: 12px;"> <?php echo lang("unit_type"); ?></th>
            <th style="text-align: center;  width: 15%; border: 1px solid #eee; padding: 12px;"> <?php echo lang("quantity"); ?></th>
            <th style="text-align: center;  width: 15%; border: 1px solid #eee; padding: 12px;"> <?php echo lang("buffer"); ?></th>
            <th style="text-align: center; width: 20%; border: 1px solid #eee; width: 20%; padding: 12px;"> <?php echo lang("total"); ?></th>
        </tr>
        
        <?php 
        foreach ($bill_of_material_materials as $material) {
            $buffer = $production_info->buffer ? $production_info->quantity * ($material->quantity * ($production_info->buffer / 100)) : 0;
            $quantity = $production_info->quantity * $material->quantity;
            $total = ($production_info->quantity * $material->quantity) + $buffer;
            $material_name = "<div class='item-row strong mb5'>$material->material_name</div><span>" . $material->warehouse_name . "</span>";
            ?>
            <tr style="background-color: #f4f4f4; ">
                <td style="width: 30%; border: 1px solid #eee; padding: 10px;"><?php echo $material_name; ?></td>
                <td style="text-align: center; width: 15%; border: 1px solid #eee; padding: 10px;"> <?php echo $material->unit_name ?></td>
                <td style="text-align: center; width: 15%; border: 1px solid #eee; padding: 10px;"> <?php echo number_format($buffer, 2, '.', '') ." ". $material->unit_abbreviation ?></td>
                <td style="text-align: center; width: 15%; border: 1px solid #eee; padding: 10px;"> <?php echo number_format($buffer, 2, '.', '')  ." ". $material->unit_abbreviation ?></td>
                <td style="text-align: center; width: 20%; border: 1px solid #eee; padding: 10px;"> <?php echo number_format($total, 3, '.', '') ." ". $material->unit_abbreviation; ?></td>
            </tr>
        <?php } ?>
    </table>
</div>
