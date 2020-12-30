<?php echo form_open(get_uri("warehouse/save"), array("id" => "warehouse-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info ? $model_info->id : "" ?>" />
    <div class="form-group">
        <label for="head" class="col-md-3"><?php echo lang('head'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("head", $user_dropdown, $model_info ? $model_info->head : "", "class='select2 validate-hidden' id='signed_by' data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="name" class=" col-md-3"><?php echo lang('name'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "name",
                "name" => "name",
                "value" => $model_info ? $model_info->name : "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('name'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="address" class="col-md-3"><?php echo lang('address'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "address",
                "name" => "address",
                "value" => $model_info ? $model_info->address : "",
                "class" => "form-control",
                "placeholder" => lang('address'),
                "data-rich-text-editor" => true
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="zip_code" class=" col-md-3"><?php echo lang('zip_code'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "zip_code",
                "name" => "zip_code",
                "value" => $model_info ? $model_info->zip_code : "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('zip_code'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="email" class=" col-md-3"><?php echo lang('email'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "email",
                "name" => "email",
                "value" => $model_info ? $model_info->email : "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('email'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#warehouse-form").appForm({
            onSuccess: function (result) {
                $("#warehouse-table").appTable({newData: result.data, dataId: result.id});
            }
        });

        $('.select2').select2();
    });
</script>