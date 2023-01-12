<?php echo form_open(get_uri("Raffle_draw/update_status"), array("id" => "raffle_draw-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <input type="hidden" name="status" value="<?php echo $model_info->new_status; ?>" />
    <div class="form-group">
        <div class=" col-md-12">
            Please be careful updating the status of each raffle draw, this might affect the public participants reactions.
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="submit" class="btn btn-secondary <?= $model_info->new_status?"":"disabled" ?>"></span> <?php echo strtoupper($model_info->status_action); ?></button>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#raffle_draw-form").appForm({
            onSuccess: function (result) {
                $("#raffle_draw-table").appTable({newData: result.data, dataId: result.id});
            }
        });
    });
</script>    