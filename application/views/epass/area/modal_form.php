<?php echo form_open(get_uri("Epass_Area/save"), array("id" => "epass-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <div class="form-group">
        <div class=" col-md-12">
            <?php
            echo form_input(array(
                "id" => "events_dropdown",
                "name" => "event_id",
                "value" => $model_info->event_id,
                "class" => "form-control",
                "required" => true
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-6">
            <?php
            echo form_input(array(
                "id" => "area_name",
                "name" => "area_name",
                "value" => strtoupper($model_info->area_name),
                "class" => "form-control",
                "placeholder" => lang('area_name'),
                "required" => true
            ));
            ?>
        </div>
        <div class="col-md-6">
            <?php
            echo form_input(array(
                "id" => "sort",
                "name" => "sort",
                "type" => "number",
                "value" => strtoupper($model_info->sort),
                "class" => "form-control",
                "placeholder" => lang('sort'),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <div class="notepad">
                <?php
                echo form_textarea(array(
                    "id" => "description",
                    "name" => "description",
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
        $("#epass-form").appForm({
            onSuccess: function (result) {
                $("#area-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        $("#area_name").focus();

        $('#events_dropdown').select2({data: <?php echo json_encode($events_dropdown); ?>}).on("change", function () {
            console.log('Event ID: ', $(this).val());
        });
    });
</script>    