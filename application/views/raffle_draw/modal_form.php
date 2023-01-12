<?php echo form_open(get_uri("Raffle_draw/save"), array("id" => "raffle_draw-form", "class" => "general-form", "role" => "form")); ?>
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
        <div class="col-md-12">
            <?php echo form_input(array(
                "id" => "title",
                "name" => "title",
                "value" => strtoupper($model_info->title),
                "class" => "form-control",
                "placeholder" => lang('title'),
                "required" => true
            )); ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-6">
            <?php echo form_input(array(
                "id" => "number_of_winners",
                "name" => "number_of_winners",
                "type" => "number",
                "value" => strtoupper($model_info->winners),
                "class" => "form-control",
                "placeholder" => lang('number_of_winners'),
                "required" => true
            )); ?>
        </div>
        <div class="col-md-6">
            <?php
            echo form_dropdown(
                "ranking", array("asc"=>"ASC","desc"=>"DESC"), 
                $model_info->ranking, 
                "class='select2 validate-hidden' id='ranking' data-rule-required='true' data-msg-required='".lang("field_required")."'");
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
                    "placeholder" => lang('description') . "...",
                    "data-rich-text-editor" => true,
                ));
                ?>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="draw_date" class=" col-md-3"><?php echo lang('draw_date'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "draw_date",
                "name" => "draw_date",
                "value" => $model_info->draw_date ? $model_info->draw_date : "",
                "class" => "form-control",
                "placeholder" => lang('bill_date'),
                "autocomplete" => "off",
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <?php echo form_input(array(
                "id" => "remarks",
                "name" => "remarks",
                "value" => strtoupper($model_info->remarks),
                "class" => "form-control",
                "placeholder" => lang('remarks'),
            )); ?>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle "></span> <?php echo lang('save'); ?></button>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#raffle_draw-form").appForm({
            onSuccess: function (result) {
                $("#raffle_draw-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        $("#ranking").select2();
        
        $("#number_of_winners").on("input", function () {
            if($(this).val() > 1) {
                $("#ranking").prop( "disabled", false );
            } else {
                $("#ranking").prop( "disabled", true );
            }
        });

        setDatePicker("#draw_date");

        $('#events_dropdown').select2({data: <?php echo json_encode($events_dropdown); ?>}).on("change", function () {
            console.log('Event ID: ', $(this).val());
        });
    });
</script>    