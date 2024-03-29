<div><b><?php echo lang("estimate_to"); ?></b></div>
<div style="line-height: 2px; border-bottom: 1px solid #f2f2f2;"> </div>
<div style="line-height: 3px;"> </div>
<strong><?php echo $client_info->company_name; ?> </strong>
<strong><?php echo trim($consumer_info->first_name . " " . $consumer_info->last_name); ?> </strong>
<div style="line-height: 3px;"> </div>
<span class="invoice-meta" style="font-size: 90%; color: #666;">
    <?php if ($client_info->address) { ?>
        <div><?php echo nl2br($client_info->address); ?>
            <?php if ($client_info->city) { ?>
                <br /> <?php echo $client_info->city; ?>
            <?php } ?>
            <?php if ($client_info->state) { ?>
                , <?php echo $client_info->state; ?>
            <?php } ?>
            <?php if ($client_info->zip) { ?>
                <?php echo $client_info->zip; ?>
            <?php } ?>
            <?php if ($client_info->country) { ?>
                <br /><?php echo $client_info->country; ?>
            <?php } ?>
            <?php if ($client_info->vat_number) { ?>
                <br /><?php echo lang("vat_number") . ": " . $client_info->vat_number; ?>
            <?php } ?>
        </div>
    <?php } ?>
    <?php if ($consumer_info->street) { ?>
        <div><?php echo nl2br($consumer_info->street); ?>
            <?php if ($consumer_info->city) { ?>
                <br/> <?php echo $consumer_info->city; ?>
            <?php } ?>
            <?php if ($consumer_info->state) { ?>
                , <?php echo $consumer_info->state; ?>
            <?php } ?>
            <?php if ($consumer_info->zip) { ?>
                <?php echo $consumer_info->zip; ?>
            <?php } ?>
            <?php if ($consumer_info->country) { ?>
                <br /><?php echo $consumer_info->country; ?>
            <?php } ?>
        </div>
    <?php } ?>
</span>