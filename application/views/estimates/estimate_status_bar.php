<div class="panel panel-default  p15 no-border m0">
    <span><?php echo lang("status") . ": " . $estimate_status_label; ?></span>
    <span class="ml15">
        <?php
        if ($estimate_info->is_lead) {
            echo lang("lead") . ": ";
            echo (anchor(get_uri("mcs/leads/view/" . $estimate_info->client_id), $estimate_info->company_name));
        } else if($estimate_info->client_id) {
            echo lang("client") . ": ";
            echo (anchor(get_uri("pms/clients/view/" . $estimate_info->client_id), $estimate_info->company_name));
        } else if($estimate_info->consumer_id) {
            echo lang("consumer") . ": ";
            echo get_team_member_profile_link($estimate_info->consumer_id, trim($consumer_info->first_name . " " . $consumer_info->last_name), array("target" => "_blank"));
        }
        ?>
    </span>
    <span class="ml15"><?php
        if(!$estimate_info->consumer_id) {
            echo lang("last_email_sent") . ": ";
            echo (is_date_exists($estimate_info->last_email_sent_date)) ? format_to_date($estimate_info->last_email_sent_date, FALSE) : lang("never");
        }
        ?>
    </span>
    <?php if (!$estimate_info->estimate_request_id == 0) {
        ?>
        <span class="ml15">
            <?php
            echo lang("estimate_request") . ": ";
            echo (anchor(get_uri("estimate_requests/view_estimate_request/" . $estimate_info->estimate_request_id), lang('estimate_request') . " - " . $estimate_info->estimate_request_id));
            ?>
        </span>
        <?php
    }
    ?>
    <span class="ml15"><?php
        if ($estimate_info->project_id) {
            echo lang("project") . ": ";
            echo (anchor(get_uri("pms/projects/view/" . $estimate_info->project_id), $estimate_info->project_title));
        }
        ?>
    </span>
</div>