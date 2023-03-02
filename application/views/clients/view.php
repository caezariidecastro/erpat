<div class="page-title clearfix no-border bg-off-white">
    <h1>
        <?php echo lang('client_details') . " - " . $client_info->company_name ?>
        <span id="star-mark">
            <?php
            if ($is_starred) {
                $this->load->view('clients/star/starred', array("client_id" => $client_info->id));
            } else {
                $this->load->view('clients/star/not_starred', array("client_id" => $client_info->id));
            }
            ?>
        </span>
    </h1>
</div>

<div id="page-content" class="clearfix">

    <?php
    if ($client_info->lead_status_id) {
        $this->load->view("clients/lead_information");
    }
    ?>

    <div class="mt15">
        <?php $this->load->view("clients/info_widgets/index"); ?>
    </div>

    <ul id="client-tabs" data-toggle="ajax-tab" class="nav nav-tabs" role="tablist">
        <li><a  role="presentation" href="<?php echo_uri("sales/Clients/contacts/" . $client_info->id); ?>" data-target="#client-contacts"> <?php echo lang('contacts'); ?></a></li>
        <li><a  role="presentation" href="<?php echo_uri("sales/Clients/company_info_tab/" . $client_info->id); ?>" data-target="#client-info"> <?php echo lang('client_info'); ?></a></li>
        <li><a  role="presentation" href="<?php echo_uri("sales/Clients/projects/" . $client_info->id); ?>" data-target="#client-projects"><?php echo lang('projects'); ?></a></li>

        <?php if ($show_invoice_info) { ?>
            <li><a  role="presentation" href="<?php echo_uri("sales/Clients/invoices/" . $client_info->id); ?>" data-target="#client-invoices"> <?php echo lang('invoices'); ?></a></li>
            <li><a  role="presentation" href="<?php echo_uri("sales/Clients/payments/" . $client_info->id); ?>" data-target="#client-payments"> <?php echo lang('payments'); ?></a></li>
        <?php } ?>
        <?php if ($show_estimate_info) { ?>
            <li><a  role="presentation" href="<?php echo_uri("sales/Clients/estimates/" . $client_info->id); ?>" data-target="#client-estimates"> <?php echo lang('estimates'); ?></a></li>
        <?php } ?>
        <?php if ($show_estimate_request_info) { ?>
            <li><a  role="presentation" href="<?php echo_uri("sales/Clients/estimate_requests/" . $client_info->id); ?>" data-target="#client-estimate-requests"> <?php echo lang('estimate_requests'); ?></a></li>
        <?php } ?>
        <?php if ($show_ticket_info) { ?>
            <li><a  role="presentation" href="<?php echo_uri("sales/Clients/tickets/" . $client_info->id); ?>" data-target="#client-tickets"> <?php echo lang('tickets'); ?></a></li>
        <?php } ?>
        <?php if ($show_note_info) { ?>
            <li><a  role="presentation" href="<?php echo_uri("sales/Clients/notes/" . $client_info->id); ?>" data-target="#client-notes"> <?php echo lang('notes'); ?></a></li>
        <?php } ?>
        <li><a  role="presentation" href="<?php echo_uri("sales/Clients/files/" . $client_info->id); ?>" data-target="#client-files"><?php echo lang('files'); ?></a></li>

        <?php if ($show_event_info) { ?>
            <li><a  role="presentation" href="<?php echo_uri("sales/Clients/events/" . $client_info->id); ?>" data-target="#client-events"> <?php echo lang('events'); ?></a></li>
        <?php } ?>

        <?php if ($show_expense_info) { ?>
            <li><a  role="presentation" href="<?php echo_uri("sales/Clients/expenses/" . $client_info->id); ?>" data-target="#client-expenses"> <?php echo lang('expenses'); ?></a></li>
        <?php } ?>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade" id="client-projects"></div>
        <div role="tabpanel" class="tab-pane fade" id="client-files"></div>
        <div role="tabpanel" class="tab-pane fade" id="client-info"></div>
        <div role="tabpanel" class="tab-pane fade" id="client-contacts"></div>
        <div role="tabpanel" class="tab-pane fade" id="client-invoices"></div>
        <div role="tabpanel" class="tab-pane fade" id="client-payments"></div>
        <div role="tabpanel" class="tab-pane fade" id="client-estimates"></div>
        <div role="tabpanel" class="tab-pane fade" id="client-estimate-requests"></div>
        <div role="tabpanel" class="tab-pane fade" id="client-tickets"></div>
        <div role="tabpanel" class="tab-pane fade" id="client-notes"></div>
        <div role="tabpanel" class="tab-pane" id="client-events" style="min-height: 300px"></div>
        <div role="tabpanel" class="tab-pane fade" id="client-expenses"></div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        setTimeout(function () {
            var tab = "<?php echo $tab; ?>";
            if (tab === "info") {
                $("[data-target=#client-info]").trigger("click");
            }
        }, 210);

    });
</script>
