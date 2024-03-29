<?php if ($page_view) { ?>
    <div id="page-content" class="clearfix p20">
        <div class="panel clearfix">
        <?php } ?>

        <div class="panel">
            <div class="tab-title clearfix">
                <h4><?php echo lang('files'); ?></h4>
                <div class="title-button-group">
                    <?php
                    if ($this->login_user->user_type == "staff" || ($this->login_user->user_type == "client" && get_setting("client_can_add_files"))) {
                        echo modal_anchor(get_uri("sales/Clients/file_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_files'), array("class" => "btn btn-default", "title" => lang('add_files'), "data-post-client_id" => $client_id));
                    }
                    ?>
                </div>
            </div>

            <div class="table-responsive">
                <table id="client-file-table" class="display" width="100%">            
                </table>
            </div>
        </div>

        <?php if ($page_view) { ?>
        </div>
    </div>
<?php } ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#client-file-table").appTable({
            source: '<?php echo_uri("sales/Clients/files_list_data/" . $client_id) ?>',
            order: [[0, "desc"]],
            columns: [
                {title: '<?php echo lang("id") ?>'},
                {title: '<?php echo lang("file") ?>'},
                {title: '<?php echo lang("size") ?>'},
                {title: '<?php echo lang("uploaded_by") ?>'},
                {title: '<?php echo lang("created_date") ?>'},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4],
            xlsColumns: [0, 1, 2, 3, 4]
        });
    });
</script>