<?php echo form_open(get_uri("holidays/save"), array("id" => "holiday-form", "class" => "general-form", "role" => "form")); ?>
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
        <label for="date_from" class=" col-md-3"><?php echo lang('from'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "date_from",
                "name" => "date_from",
                "value" => $model_info ? $model_info->date_from : "",
                "class" => "form-control",
                "placeholder" => lang('from'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="date_to" class=" col-md-3"><?php echo lang('to'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "date_to",
                "name" => "date_to",
                "value" => $model_info ? $model_info->date_to : "",
                "class" => "form-control",
                "placeholder" => lang('to'),
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

        $("#holiday-form").appForm({
            onSuccess: function (result) {
                $("#holiday-table").appTable({newData: result.data, dataId: result.id});
            }
        });
    });
</script>