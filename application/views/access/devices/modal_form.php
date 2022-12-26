<?php echo form_open(get_uri("Access_devices/save"), array("id" => "access_device-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <div class="form-group">
        <div class="col-md-12">
            <?php
            echo form_input(array(
                "id" => "device_name",
                "name" => "device_name",
                "value" => strtoupper($model_info->device_name),
                "class" => "form-control",
                "placeholder" => lang('device_name'),
                "required" => true
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
                "value" => $model_info->category_id,
                "class" => "form-control",
                "required" => true
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
              <input type="text" value="<?php echo $model_info->passes; ?>" name="passes" id="passes_dropdown" class="w100p validate-hidden"  placeholder="<?php echo lang('employee'); ?>"  />    
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <div class="notepad">
                <?php
                echo form_textarea(array(
                    "id" => "remarks",
                    "name" => "remarks",
                    "value" => $model_info->remarks,
                    "class" => "form-control",
                    "placeholder" => lang('remarks') . "...",
                    "data-rich-text-editor" => true,
                ));
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle "></span> <?php echo lang('save'); ?></button>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#access_device-form").appForm({
            onSuccess: function (result) {
                $("#access_device-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        $("#title").focus();

        $("#passes_dropdown").select2({
            multiple: true,
            data: <?php echo ($passes_dropdown); ?>
        });

        $('#category_id').select2({data: <?php echo json_encode($category_dropdown); ?>});
    });
</script>    