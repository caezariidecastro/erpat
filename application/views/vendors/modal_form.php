<?php echo form_open(get_uri("vendors/save"), array("id" => "vendors-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info ? $model_info->id : "" ?>" />
    <div class="form-group">
        <label for="name" class="col-md-3"><?php echo lang('name'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "name",
                "name" => "name",
                "value" => $model_info ? $model_info->name : "",
                "class" => "form-control",
                "placeholder" => lang('name'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="contact" class="col-md-3"><?php echo lang('contact'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "contact",
                "name" => "contact",
                "value" => $model_info ? $model_info->contact : "",
                "class" => "form-control",
                "placeholder" => lang('contact'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="email" class="col-md-3"><?php echo lang('email'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "email",
                "name" => "email",
                "value" => $model_info ? $model_info->email : "",
                "class" => "form-control",
                "placeholder" => lang('email'),
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
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#vendors-form").appForm({
            onSuccess: function (result) {
                $("#vendors-table").appTable({newData: result.data, dataId: result.id});
            }
        });
    });
    
</script>