<div id="page-content" class="p20 clearfix">

            <div class="panel panel-default">

                <ul id="ticket-type-tabs" data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
                    <li><a  role="presentation" class="active" href="javascript:;" data-target="#ticket-types-tab"> <?php echo lang('ticket_types'); ?></a></li>
                    <li><a role="presentation" href="<?php echo_uri("settings/tickets/"); ?>" data-target="#tickets-tab"><?php echo lang('tickets_settings'); ?></a></li>
                    <div class="tab-title clearfix no-border">
                        <div class="title-button-group">
                            <?php echo modal_anchor(get_uri("css/ticket_types/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_ticket_type'), array("class" => "btn btn-default", "title" => lang('add_ticket_type'))); ?>
                        </div>
                    </div>
                </ul>

                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade" id="ticket-types-tab">
                        <div class="table-responsive">
                            <table id="ticket-type-table" class="display" cellspacing="0" width="100%">            
                            </table>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="tickets-tab"></div>
                </div>

            </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#ticket-type-table").appTable({
            source: '<?php echo_uri("css/ticket_types/list_data") ?>',
            columns: [
                {title: '<?php echo lang("name"); ?>'},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            printColumns: [0, 1]
        });

        setTimeout(function () {
            var tab = "<?php echo $tab; ?>";
            if (tab === "imap") {
                $("[data-target=#imap_settings-tab]").trigger("click");
            }
        }, 210);

    });
</script>
