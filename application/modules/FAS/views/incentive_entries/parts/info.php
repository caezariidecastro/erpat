<span class="invoice-info-title" style="font-size:20px; font-weight: bold;background-color: <?php echo $color; ?>; color: #fff;">&nbsp;<?php echo get_incentive_id($incentive_info->id); ?>&nbsp;</span>
<div style="line-height: 10px;"></div>
<span><?php echo lang("created_on") . ": " . format_to_date($incentive_info->created_on, false); ?></span><br />