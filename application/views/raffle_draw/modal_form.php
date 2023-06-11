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
        <div class="col-md-4">
            <?php echo form_input(array(
                "id" => "number_of_winners",
                "name" => "number_of_winners",
                "type" => "number",
                "value" => strtoupper($model_info->winners),
                "class" => "form-control",
                "placeholder" => lang('number_of_winners'),
                "required" => true,
                ($model_info->id && $model_info->status!=='draft')?"disabled":"" => $model_info->id && $model_info->status!=='draft'?"disabled":"",
            )); ?>
        </div>
        <div class="col-md-4">
            <?php
            echo form_dropdown(
                "ranking", array("asc"=>"ASCENDING","desc"=>"DESCENDING"), 
                $model_info->ranking, 
                "class='select2 validate-hidden' id='ranking' ".(($model_info->id && $model_info->status!=='draft')?"disabled":"")." data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>
        <div class="col-md-4">
        <input type="text" value="<?php echo $model_info->crowd_type; ?>" name="crowd_type" id="crowd_type" class="w100p validate-hidden"  placeholder="<?php echo lang('crowd_type'); ?>" <?= ($model_info->id && $model_info->status!=='draft')?"disabled":"" ?>  />
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <div class="notepad">
                <?php
                echo form_textarea(array(
                    "id" => "description",
                    "name" => "description",
                    "value" => $model_info->description,
                    "class" => "form-control",
                    "placeholder" => lang('description') . "...",
                    "data-rich-text-editor" => true,
                ));
                ?>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-4">
            <?php
            echo form_dropdown(
                "raffle_type", array("standard"=>"QRCode Scan","countdown"=>"Countdown","spinner"=>"Spinner","wheel"=>"Wheel","mosaic"=>"Mosaic"), 
                $model_info->raffle_type, 
                "class='select2 validate-hidden' id='raffle_type' ".($model_info->id?"disabled":"")." data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>
        <div class="col-md-4">
            <?php
            echo form_input(array(
                "id" => "draw_date",
                "name" => "draw_date",
                "value" => $model_info->draw_date ? $model_info->draw_date : "",
                "class" => "form-control",
                "placeholder" => lang('draw_date'),
                "autocomplete" => "off",
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
        <div class="col-md-4">
            <?php
            echo form_input(array(
                "id" => "draw_time",
                "name" => "draw_time",
                "value" => $model_info->draw_time,
                "class" => "form-control",
                "placeholder" => $model_info->draw_time,
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
        $("#ranking, #raffle_type").select2();
        $("#crowd_type").select2({
            multiple: true,
            data: <?php echo json_encode([
                array("id"=>"user", "text"=>"User" ),
                array("id"=>"seller", "text"=>"Seller" ),
                array("id"=>"distributor", "text"=>"Distributor" ),
                array("id"=>"franchisee", "text"=>"Franchisee" )
            ]); ?>
        });
        
        $("#number_of_winners").on("input", function () {
            if($(this).val() > 1) {
                $("#ranking").prop( "disabled", false );
            } else {
                $("#ranking").prop( "disabled", true );
            }
        });

        setDatePicker("#draw_date");
        setTimePicker("#draw_time");

        $('#events_dropdown').select2({data: <?php echo json_encode($events_dropdown); ?>}).on("change", function () {
            console.log('Event ID: ', $(this).val());
        });
    });
</script>    