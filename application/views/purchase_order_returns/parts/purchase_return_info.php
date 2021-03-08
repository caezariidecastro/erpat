<span class="invoice-info-title" style="font-size:20px; font-weight: bold;background-color: <?php echo $color; ?>; color: #fff;">&nbsp;<?php echo get_purchase_return_id($purchase_return_info->id); ?>&nbsp;</span>
<div style="line-height: 10px;"></div>
<span><?php echo lang("date_created") . ": " . format_to_date($purchase_return_info->created_on, false); ?></span><br />