<?php echo form_open(get_uri("roles/save"), array("id" => "role-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="form-group">
        <label for="title" class=" col-md-3"><?php echo lang('title'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "title",
                "name" => "title",
                "value" => $model_info->title,
                "class" => "form-control",
                "placeholder" => lang('title'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="staffs" class=" col-md-3"><?php echo lang('staffs'); ?></label>
        <div class="col-md-9">
            <input type="hidden" value="<?= $model_info->staffs ?>" name="prev_staffs" />
            <input type="text" value="<?= $model_info->staffs; ?>" name="staffs" id="staffs_dropdown" class="w100p validate-hidden"  placeholder="<?php echo lang('staffs'); ?>"  />    
        </div>
    </div>
    <div class="form-group">
        <?php if (!$model_info->id) { ?>
            <label for="copy_settings" class=" col-md-3"><?php echo lang('use_seetings_from'); ?></label>
            <div class=" col-md-9">
                <?php
                echo form_dropdown("copy_settings", $roles_dropdown, "", "class='select2' id='copy_settings' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                ?>
            </div>
        <?php } ?>
    </div>
</div>

<div class="modal-footer">
    <button id="clear_staffs" type="button" class="btn btn-danger"><span class="fa fa-trash "></span>  <?php echo lang('clear'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function() {
        $("#role-form").appForm({
            onSuccess: function(result) {
                $("#role-table").appTable({newData: result.data, dataId: result.id});
            }
        });

        $("#staffs_dropdown").select2({
            multiple: true,
            formatResult: teamAndMemberSelect2Format,
            formatSelection: teamAndMemberSelect2Format,
            data: <?php echo ($staffs_dropdown); ?>
        });

        $( "#clear_staffs" ).on( "click", function() {
            $('#staffs_dropdown').val(null).trigger('change');
        });

        $("#copy_settings").select2();
        $("#title").focus();
    });
</script>