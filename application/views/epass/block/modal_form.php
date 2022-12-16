<?php echo form_open(get_uri("Epass_Block/save"), array("id" => "epass-form", "class" => "general-form", "role" => "form")); ?>
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
        <div class=" col-md-12">
            <?php
            echo form_input(array(
                "id" => "areas_dropdown",
                "name" => "area_id",
                "value" => $model_info->area_id,
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
                "id" => "block_name",
                "name" => "block_name",
                "value" => strtoupper($model_info->block_name),
                "class" => "form-control",
                "placeholder" => lang('block_name'),
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
                $("#block-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        $("#block_name").focus();

        $('#areas_dropdown').select2({data: <?php echo json_encode($areas_dropdown); ?>});
        $('#events_dropdown').select2({data: <?php echo json_encode($events_dropdown); ?>}).on("change", function () {
            //Refresh data of block drowpdown
            $.ajax({
                url: "<?php echo base_url()?>Epass_Block/list_areas/"+$(this).val(),
                method: "POST",
                dataType: "json",
                success: function(response){
                    $('#areas_dropdown').select2({data: response.data}).on("change", function () {
                        //Refresh data of block drowpdown
                    });
                },
                error: function (request, status, error) {
                    console.log('asdsadsa');
                    $('#areas_dropdown').select2({data: <?php echo json_encode($areas_dropdown); ?>});
                }
            })
        });

        
    });
</script>    