<?php echo form_open(get_uri("Epass_Seat/save"), array("id" => "epass-form", "class" => "general-form", "role" => "form")); ?>
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
        <div class=" col-md-12">
            <?php
            echo form_input(array(
                "id" => "blocks_dropdown",
                "name" => "block_id",
                "value" => $model_info->block_id,
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
                "id" => "seat_name",
                "name" => "seat_name",
                "value" => $model_info->seat_name,
                "class" => "form-control",
                "placeholder" => lang('seat_name'),
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
                $("#seat-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        $("#block_name").focus();

        $('#areas_dropdown').select2({data: <?php echo json_encode($areas_dropdown); ?>});
        $('#blocks_dropdown').select2({data: <?php echo json_encode($blocks_dropdown); ?>});
        $('#events_dropdown').select2({data: <?php echo json_encode($events_dropdown); ?>}).on("change", function () {
            //Refresh data of block drowpdown
            $.ajax({
                url: "<?php echo base_url()?>Epass_Seat/list_areas/"+$(this).val(),
                method: "POST",
                dataType: "json",
                success: function(response){
                    $('#areas_dropdown').select2({data: response.data}).on("change", function () {
                        $.ajax({
                            url: "<?php echo base_url()?>Epass_Seat/list_blocks/"+$(this).val(),
                            method: "POST",
                            dataType: "json",
                            success: function(response){
                                $('#blocks_dropdown').select2({data: response.data}).on("change", function () {
                                    //Refresh data of block drowpdown
                                });
                            },
                            error: function (request, status, error) {
                                $('#areas_dropdown').select2({data: <?php echo json_encode($areas_dropdown); ?>});
                            }
                        })
                    });
                },
                error: function (request, status, error) {
                    $('#areas_dropdown').select2({data: <?php echo json_encode($areas_dropdown); ?>});
                }
            })
        });

        
    });
</script>    