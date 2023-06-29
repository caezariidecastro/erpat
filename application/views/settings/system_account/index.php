<div id="page-content" class="p20 clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "system_account";
            $this->load->view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <div class="panel panel-default">
                <div class="page-title clearfix">
                    <h4> <?php echo lang('system_account'); ?></h4>
                    <div class="title-button-group">
                        <?php echo modal_anchor(get_uri("settings/settings_add_account"), "<i class='fa fa-plus-circle'></i> " . lang('add_account'), array("class" => "btn btn-default", "title" => lang('add_account'))); ?>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="system-account-table" class="display" cellspacing="0" width="100%">            
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#system-account-table").appTable({
            source: '<?php echo_uri("settings/settings_list_account") ?>',
            columns: [
                {title: '<?php echo lang("first_name"); ?>'},
                {title: '<?php echo lang("last_name"); ?>'},
                {title: '<?php echo lang("account_type"); ?>'},
                {title: '<?php echo lang("email"); ?>'},
                {title: '<?php echo lang("access_lists"); ?>'},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            tableRefreshButton: true,
        });
    });
</script>