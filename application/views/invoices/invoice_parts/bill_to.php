<div><b><?php echo lang("bill_to"); ?></b></div>
<div style="line-height: 2px; border-bottom: 1px solid #f2f2f2;"> </div>
<div style="line-height: 3px;"> </div>
<strong><?php echo $client_info->company_name; ?> </strong>
<div style="line-height: 3px;"> </div>
<span class="invoice-meta" style="font-size: 90%; color: #666;">
    <?php if ($client_info->address || $client_info->vat_number || (isset($client_info->custom_fields) && $client_info->custom_fields)) { ?>
        <div><?php echo nl2br($client_info->address); ?>
            <?php if ($client_info->city) { ?>
                <br /><?php echo $client_info->city; ?>
            <?php } ?>
            <?php if ($client_info->state) { ?>
                <br /><?php echo $client_info->state; ?>
            <?php } ?>
            <?php if ($client_info->zip) { ?>
                <br /><?php echo $client_info->zip; ?>
            <?php } ?>
            <?php if ($client_info->country) { ?>
                <br /><?php echo $client_info->country; ?>
            <?php } ?>
            <?php if ($client_info->vat_number) { ?>
                <br /><?php echo lang("vat_number") . ": " . $client_info->vat_number; ?>
            <?php } ?>
            <?php
            if (isset($client_info->custom_fields) && $client_info->custom_fields) {
                foreach ($client_info->custom_fields as $field) {
                    if ($field->value) {
                        echo "<br />" . $field->custom_field_title . ": " . $this->load->view("custom_fields/output_" . $field->custom_field_type, array("value" => $field->value), true);
                    }
                }
            }
            ?>


        </div>
    <?php } ?>
    <?php 
        if($invoice_info->consumer_id){
    ?>
        <div>
            <?php 
                echo $invoice_info->consumer_name;
            ?>
            <?php if ($delivery_info) { ?>
                <?php if ($delivery_info->street) { ?>
                    <br /><?php echo $delivery_info->street; ?>
                <?php } ?>
                <?php if ($delivery_info->city) { ?>
                    <br /><?php echo $delivery_info->city; ?>
                <?php } ?>
                <?php if ($delivery_info->state) { ?>
                    <br /><?php echo $delivery_info->state; ?>
                <?php } ?>
                <?php if ($delivery_info->zip) { ?>
                    <br /><?php echo $delivery_info->zip; ?>
                <?php } ?>
                <?php if ($delivery_info->country) { ?>
                    <br /><?php echo $delivery_info->country; ?>
                <?php } ?>
            <?php } else if ($consumer_info) { ?>
                <?php if ($consumer_info->street) { ?>
                    <br /><?php echo $consumer_info->street; ?>
                <?php } ?>
                <?php if ($consumer_info->city) { ?>
                    <br /><?php echo $consumer_info->city; ?>
                <?php } ?>
                <?php if ($consumer_info->state) { ?>
                    <br /><?php echo $consumer_info->state; ?>
                <?php } ?>
                <?php if ($consumer_info->zip) { ?>
                    <br /><?php echo $consumer_info->zip; ?>
                <?php } ?>
                <?php if ($consumer_info->country) { ?>
                    <br /><?php echo $consumer_info->country; ?>
                <?php } ?>
            <?php } ?>
        </div>
    <?php
        }
    ?>
</span>