<?php echo form_open(get_uri("EventPass/reserve"), array("id" => "epass-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="form-group">
        <div class="col-md-12">
            <?php
            echo form_input(array(
                "id" => "events_dropdown",
                "name" => "event_id",
                "value" => "",
                "class" => "form-control",
                "placeholder" => lang('event_name'),
                "autofocus" => true,
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
                "value" => "",
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
                "value" => "",
                "class" => "form-control",
                "required" => true
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <input type="text" value="" name="seat_assigned" id="seats_dropdown" class="w100p validate-hidden"  placeholder="<?php echo lang('seats'); ?>"  />    
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
    <button type="submit" class="btn btn-success"><span class="fa fa-check-circle "></span> <?php echo lang('save'); ?></button>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#epass-form").appForm({
            onSuccess: function (result) {
                $("#epass-table").appTable({newData: result.data, dataId: result.id});
            }
        });

        $('#seats_dropdown').select2({data: <?php echo json_encode($seats_dropdown); ?>});
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
                                    $.ajax({
                                        url: "<?php echo base_url()?>Epass_Seat/list_available/"+$(this).val(),
                                        method: "POST",
                                        dataType: "json",
                                        success: function(response){
                                            $('#seats_dropdown').select2({multiple: true, data: response.data}).on("change", function () {
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
                },
                error: function (request, status, error) {
                    $('#areas_dropdown').select2({data: <?php echo json_encode($areas_dropdown); ?>});
                }
            })
        });
    });
</script>    