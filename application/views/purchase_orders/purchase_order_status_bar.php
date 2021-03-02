<div class="panel panel-default  p15 no-border m0">
    <span class="mr10" id="purchase_order_status_label"><?php echo $purchase_order_status_label; ?></span>

    <span class="ml15"><?php
        echo lang("supplier") . ": ";
        echo $purchase_order_info->vendor_name;
        ?>
    </span> 

    <span class="ml15" id="last_email_sent_date"><?php
        echo lang("last_email_sent") . ": ";
        echo $purchase_order_info->last_email_sent_date ? $purchase_order_info->last_email_sent_date : lang("never");
        ?>
    </span>

    <?php if ($purchase_order_info->cancelled_at) { ?>
        <span class="ml15"><?php echo lang("cancelled_at") . ": " . format_to_relative_time($purchase_order_info->cancelled_at); ?></span>
    <?php } ?>

    <?php if ($purchase_order_info->cancelled_by) { ?>
        <span class="ml15"><?php echo lang("cancelled_by") . ": " . get_team_member_profile_link($purchase_order_info->cancelled_by, $purchase_order_info->cancelled_by_user); ?></span>
    <?php } ?>

</div>