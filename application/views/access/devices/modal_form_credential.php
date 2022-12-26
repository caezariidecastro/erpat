<?php echo form_open(get_uri("Access_devices/generate_new_secret"), array("id" => "access_device-credential-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <div class="form-group">
        <div class="col-md-12">
            <?php
            echo form_input(array(
                "id" => "device_name",
                "name" => "device_name",
                "value" => strtoupper($model_info->api_key),
                "class" => "form-control",
                "placeholder" => lang('device_name'),
                "disabled" => true
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <?php
            echo form_input(array(
                "id" => "category_id",
                "name" => "category_id",
                "value" => $model_info->api_secret,
                "class" => "form-control",
                "disabled" => true
            ));
            ?>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="submit" class="btn btn-danger"><span class="fa fa-check-circle "></span> <?php echo lang('generate_new_secret'); ?></button>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#access_device-credential-form").appForm({
            onSuccess: function (result) {
                //$("#access_device-credential-table").appTable({newData: result.data, dataId: result.id});
            }
        });
    });
</script>    