<div id="page-content" class="p20 clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "database";
            $this->load->view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <?php echo form_open(get_uri("database/update"), array("id" => "database-maintainance-form", "class" => "general-form dashed-row", "role" => "form")); ?>
            <div class="panel">
                <div class="panel-default panel-heading">
                    <h4><?php echo lang("database"); ?></h4>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="latest_version" class=" col-md-2"><?php echo lang('latest_version'); ?>: </label>
                        <?= $latest_version ?>
                    </div>
                    <div class="form-group">
                        <label for="target_version" class=" col-md-2"><?php echo lang('target_version'); ?>: </label>
                        <div class=" col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "target_version",
                                "name" => "target_version",
                                "type" => "number",
                                "value" => $current_version,
                                "class" => "form-control",
                                "placeholder" => 0,
                                "data-rule-required" => true,
                                "data-msg-required" => lang("field_required")
                            ));
                            ?>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <button type="submit" class="btn btn-primary"><span class="fa fa-refresh"></span> <?php echo lang('update_database'); ?></button>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#database-maintainance-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 3000});
            }
        });

    });
</script>