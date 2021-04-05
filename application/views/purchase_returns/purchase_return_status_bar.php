<div class="panel panel-default  p15 no-border m0">
    <span class="mr10" id="purchase_return_status_label"><?php echo $purchase_return_status_label; ?></span>

    <span class="ml15"><?php
        echo lang("purchase_id") . ": ";
        echo anchor("mes/PurchaseReturns/view/".$purchase_return_info->purchase_id, get_purchase_order_id($purchase_return_info->purchase_id));
        ?>
    </span> 

    <span class="ml15"><?php
        echo lang("supplier") . ": ";
        echo get_supplier_contact_link($purchase_return_info->vendor_id, $purchase_return_info->vendor_name);
        ?>
    </span>

    <span class="ml15" id="last_email_sent_date"><?php
        echo lang("last_email_sent") . ": ";
        echo $purchase_return_info->last_email_sent_date ? $purchase_return_info->last_email_sent_date : lang("never");
        ?>
    </span>

    <?php if ($purchase_return_info->cancelled_at) { ?>
        <span class="ml15"><?php echo lang("cancelled_at") . ": " . format_to_relative_time($purchase_return_info->cancelled_at); ?></span>
    <?php } ?>

    <?php if ($purchase_return_info->cancelled_by) { ?>
        <span class="ml15"><?php echo lang("cancelled_by") . ": " . get_team_member_profile_link($purchase_return_info->cancelled_by, $purchase_return_info->cancelled_by_user); ?></span>
    <?php } ?>

</div>