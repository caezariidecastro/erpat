<div id="page-content" class="p20 clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "modules";
            $this->load->view("settings/tabs", $tab_view);
            ?>
        </div>        

        <div class="col-sm-9 col-lg-10">
            <div class="panel panel-default">
                <div class="panel-default panel-heading">
                    <h4><?php echo lang("manage_modules"); ?></h4>
                    <div><?php echo lang("module_settings_instructions"); ?></div>
                </div>
                <div class="table-responsive">
                    <table id="module-table" class="display" cellspacing="0" width="100%">            
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#module-table").appTable({
            source: '<?php echo_uri("settings/module_list") ?>',
            order: [[0, 'desc']],
            columns: [
                {title: '<?php echo lang("name"); ?>'},
                {title: '<?php echo lang("groups"); ?>'},
                {title: '<?php echo lang("status"); ?>'},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option"}
            ]
        });
    });
</script>