<?php echo form_open(get_uri("asset_vendors/save"), array("id" => "vendor-form", "class" => "general-form", "role" => "form")); ?>
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
    <?php if($model_info->id){?>
    <div class="form-group">
        <label for="active" class=" col-md-3"><?php echo lang("active_inactive")?></label>
        <div class="col-md-9" id="invoice_client_selection_wrapper">
            <?php
            echo form_input(array(
                "id" => "active",
                "name" => "active",
                "value" => $model_info ? $model_info->active : "",
                "class" => "form-control validate-hidden",
                "data-rule-required" => "true",
                "data-msg-required" => lang('field_required'),
                "placeholder" => lang('active_inactive')
            ));
            ?>
        </div>
    </div>
    <?php }?>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#active").select2({data: <?= json_encode(array_replace($status_select2, array(0 => array("id" => "", "text"  => "-"))))?>});

        $("#vendor-form").appForm({
            onSuccess: function (result) {
                $("#asset-vendor-table").appTable({newData: result.data, dataId: result.id});
            }
        });
    });
</script>