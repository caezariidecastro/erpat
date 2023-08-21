<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">

    <ul class="nav nav-tabs bg-white title" role="tablist">
        <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang("manage"); ?></h4></li>
        <div class="tab-title clearfix no-border">
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("ticket_types/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_ticket_type'), array("class" => "btn btn-default", "title" => lang('add_ticket_type'))); ?>
            </div>
        </div>
    </ul>

    <div class="panel panel-default">
        <div class="table-responsive">
            <table id="ticket-type-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#ticket-type-table").appTable({
            source: '<?php echo_uri("ticket_types/list_data") ?>',
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