<div class="panel panel-default  p15 no-border m0">
    <span class="mr10"><?php echo $invoice_status_label; ?></span>

    <?php echo make_labels_view_data($invoice_info->labels_list, "", true); ?>

    <?php if ($invoice_info->project_id) { ?>
        <span class="ml15"><?php echo lang("project") . ": " . anchor(get_uri("pms/projects/view/" . $invoice_info->project_id), $invoice_info->project_title); ?></span>
    <?php } ?>

    <span class="ml15"><?php
        $client = "";
        $client_lang = "";

        if($invoice_info->client_id){
            $client = anchor(get_uri("sales/Clients/view/" . $invoice_info->client_id), $invoice_info->company_name);
            $client_lang = lang("client");
        }

        if($invoice_info->consumer_id){
            $client = get_team_member_profile_link($invoice_info->consumer_id, $invoice_info->consumer_name, array("target" => "_blank"));
            $client_lang = lang("consumer");
        }

        echo $client_lang . ": ";
        echo $client;
        
        ?>
    </span> 

    <span class="ml15"><?php
        $last_email_sent_lang = $invoice_info->client_id ? lang("last_email_sent") . ": " : "";
        echo $last_email_sent_lang;
        echo $invoice_info->client_id ? ((is_date_exists($invoice_info->last_email_sent_date)) ? format_to_date($invoice_info->last_email_sent_date, FALSE) : lang("never")) : "";
        ?>
    </span>
    <?php if ($invoice_info->recurring_invoice_id) { ?>
        <span class="ml15">
            <?php
            echo lang("created_from") . ": ";
            echo anchor(get_uri("sales/Invoices/view/" . $invoice_info->recurring_invoice_id), get_invoice_id($invoice_info->recurring_invoice_id));
            ?>
        </span>
    <?php } ?>

    <?php if ($invoice_info->cancelled_at) { ?>
        <span class="ml15"><?php echo lang("cancelled_at") . ": " . format_to_relative_time($invoice_info->cancelled_at); ?></span>
    <?php } ?>

    <?php if ($invoice_info->cancelled_by) { ?>
        <span class="ml15"><?php echo lang("cancelled_by") . ": " . get_team_member_profile_link($invoice_info->cancelled_by, $invoice_info->cancelled_by_user); ?></span>
    <?php } ?>

</div>