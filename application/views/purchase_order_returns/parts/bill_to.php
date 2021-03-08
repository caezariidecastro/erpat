<div><b><?php echo lang("bill_to"); ?></b></div>
<div style="line-height: 2px; border-bottom: 1px solid #f2f2f2;"> </div>
<div style="line-height: 3px;"> </div>
<strong><?php echo $purchase_return_info->vendor_name; ?> </strong>
<div style="line-height: 3px;"> </div>
<span class="invoice-meta" style="font-size: 90%; color: #666;">
    <div>
        <?php if ($vendor_info->address) { ?>
            <br /><?php echo $vendor_info->address; ?>
        <?php } ?>
        <?php if ($vendor_info->city) { ?>
            <br /><?php echo $vendor_info->city; ?>
        <?php } ?>
        <?php if ($vendor_info->state) { ?>
            <br /><?php echo $vendor_info->state; ?>
        <?php } ?>
        <?php if ($vendor_info->zip) { ?>
            <br /><?php echo $vendor_info->zip; ?>
        <?php } ?>
        <?php if ($vendor_info->country) { ?>
            <br /><?php echo $vendor_info->country; ?>
        <?php } ?>
    </div>
</span>