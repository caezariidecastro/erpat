<?php echo form_open(get_uri("lds/zones/save"), array("id" => "zone-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info ? $model_info->id : "" ?>" />
    <div class="form-group">
        <label for="warehouse_id" class="col-md-3"><?php echo lang('from'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("warehouse_id", $warehouse_dropdown, $model_info ? $model_info->warehouse_id : "", "class='select2 validate-hidden' id='transferee' data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="qrcode" class=" col-md-3"><?php echo lang('qrcode'); ?></label>
        <div class="col-md-9">
            <?php echo form_input(array(
                "id" => "qrcode",
                "name" => "qrcode",
                "value" => $model_info ? $model_info->qrcode : "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('qrcode'),
                "autofocus" => true,
            )); ?>
        </div>
    </div>
    <div class="form-group">
        <label for="barcode" class=" col-md-3"><?php echo lang('barcode'); ?></label>
        <div class="col-md-9">
            <?php echo form_input(array(
                "id" => "barcode",
                "name" => "barcode",
                "value" => $model_info ? $model_info->barcode : "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('barcode'),
                "autofocus" => true,
            )); ?>
        </div>
    </div>
    <div class="form-group">
        <label for="rfid" class=" col-md-3"><?php echo lang('rfid'); ?></label>
        <div class="col-md-9">
            <?php echo form_input(array(
                "id" => "rfid",
                "name" => "rfid",
                "value" => $model_info ? $model_info->rfid : "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('rfid'),
                "autofocus" => true,
            )); ?>
        </div>
    </div>
    <div class="form-group">
        <label for="remarks" class=" col-md-3"><?php echo lang('remarks'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "remarks",
                "name" => "remarks",
                "value" => $model_info ? $model_info->remarks : "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('remarks'),
                "autofocus" => true,
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="labels" class=" col-md-3"><?php echo lang('labels'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "labels",
                "name" => "labels",
                "value" => $model_info ? $model_info->labels : "",
                "class" => "form-control",
                "placeholder" => lang('labels'),
                "autofocus" => true,
            ));
            ?>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#zone-form").appForm({
            onSuccess: function (result) {
                $("#zone-table").appTable({newData: result.data, dataId: result.id});
            }
        });

        $('.select2').select2();
        $("#labels").select2({multiple: true, data: <?php echo json_encode($label_suggestions); ?>});
    });
</script>