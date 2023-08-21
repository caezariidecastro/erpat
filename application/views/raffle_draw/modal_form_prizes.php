<?php echo form_open(get_uri("Raffle_draw/save_prize"), array("id" => "prize_list-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <input type="hidden" name="raffle_id" value="<?php echo $raffle_id; ?>" />

    <div class="form-group">
        <div class=" col-md-12">
            <?php
            echo form_input(array(
                "id" => "winners_dropdown",
                "name" => "winner_id",
                "value" => $model_info->winner_id,
                "class" => "form-control",
                "required" => true
            ));
            ?>
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
        $("#prize_list-form").appForm({
            onSuccess: function (result) {
                $("#prize_list-table").appTable({newData: result.data, dataId: result.id});
            }
        });

        $('#winners_dropdown').select2({data: <?php echo json_encode($winners_dropdown); ?>}).on("change", function () {
            console.log('Winner ID: ', $(this).val());
        });
    });
</script>    