<div id="page-content" class="p20 clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "check_fix";
            $this->load->view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <?php echo form_open(get_uri("check_fix/execute"), array("id" => "check_fix-maintainance-form", "class" => "general-form dashed-row", "role" => "form")); ?>
            <div class="panel">
                <div class="panel-default panel-heading">
                    <h4><?php echo lang("check_fix"); ?></h4>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="last_check" class=" col-md-2"><?php echo lang('last_check'); ?>: </label>
                        <div id="last_check"><?= $last_check ?></div>
                    </div>
                </div>
                <div class="panel-footer">
                    <button type="submit" class="btn btn-primary"><span class="fa fa-wrench"></span> <?php echo lang('check_fix'); ?></button>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#check_fix-maintainance-form .select2").select2();

        $("#check_fix-maintainance-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 3000});
                $('#last_check').html(result.current);
            }
        });
    });
</script>