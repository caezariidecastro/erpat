<div id="page-content" class="p20 clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "team";
            $this->load->view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <div class="panel panel-default">
                <div class="page-title clearfix">
                    <h4> <?php echo lang('team'); ?></h4>
                    <?php if($can_add_new) {  ?>
                    <div class="title-button-group">
                        <?php echo modal_anchor(get_uri("hrs/team/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_team'), array("class" => "btn btn-default", "title" => lang('add_team'))); ?>
                    </div>
                    <?php } ?>
                </div>
                <div class="table-responsive">
                    <table id="team-table" class="display" cellspacing="0" width="100%">            
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#team-table").appTable({
            source: '<?php echo_uri("hrs/team/list_data") ?>',
            columns: [
                {title: "<?php echo lang("title"); ?>"},
                {title: "<?php echo lang("team_members"); ?>"},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            printColumns: [0, 1],
            tableRefreshButton: true
        });
    });
</script>