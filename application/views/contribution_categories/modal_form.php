<?php echo form_open(get_uri("contribution_categories/save"), array("id" => "contribution-categories-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info ? $model_info->id : "" ?>" />

    <div class="form-group">
        <label for="title" class=" col-md-3"><?php echo lang('title'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "title",
                "name" => "title",
                "value" => $model_info ? $model_info->title : "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('title'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="description" class="col-md-3"><?php echo lang('description'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "description",
                "name" => "description",
                "value" => $model_info ? $model_info->description : "",
                "class" => "form-control",
                "placeholder" => lang('description'),
                "data-rich-text-editor" => true
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="mode_of_payment" class=" col-md-3"><?php echo lang('mode_of_payment'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "mode_of_payment",
                "name" => "mode_of_payment",
                "value" => $model_info ? $model_info->mode_of_payment : "",
                "class" => "form-control",
                "placeholder" => lang('mode_of_payment')
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
        setDatePicker("#date_from");
        setDatePicker("#date_to");

        $("#contribution-categories-form").appForm({
            onSuccess: function (result) {
                $("#contribution-categories-table").appTable({newData: result.data, dataId: result.id});
            }
        });
    });
</script>