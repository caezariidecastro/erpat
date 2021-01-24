<style type="text/css">
    .datatable-tools:first-child {
        display:  none;
    }
    #add-item-delivery-section{
        display: <?php echo $model_info->id  ? "block" : "none" ?>;
    }
</style>

<?php echo form_open(get_uri("deliveries/save"), array("id" => "deliveries-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info ? $model_info->id : "" ?>" />
    <div class="form-group">
        <label for="reference_number" class="col-md-3"><?php echo lang('reference_number'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "reference_number",
                "name" => "reference_number",
                "value" => $model_info->id ? $model_info->reference_number : getToken(12),
                "class" => "form-control",
                "placeholder" => lang('reference_number'),
                "disabled" => "disabled",
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="warehouse" class="col-md-3"><?php echo lang('warehouse'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("warehouse", $warehouse_dropdown, $model_info ? $model_info->warehouse : "", "class='select2 validate-hidden' id='warehouse' data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="consumer" class="col-md-3"><?php echo lang('consumer'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("consumer", $consumer_dropdown, $model_info ? $model_info->consumer : "", "class='select2 validate-hidden' id='consumer' data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="dispatcher" class="col-md-3"><?php echo lang('dispatcher'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("dispatcher", $user_dropdown, $model_info ? $model_info->dispatcher : "", "class='select2 validate-hidden' id='dispatcher' data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="driver" class="col-md-3"><?php echo lang('driver'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("driver", $user_dropdown, $model_info ? $model_info->driver : "", "class='select2 validate-hidden' id='driver' data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="vehicle" class="col-md-3"><?php echo lang('vehicle'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("vehicle", $vehicle_dropdown, $model_info ? $model_info->vehicle : "", "class='select2 validate-hidden' id='vehicle' data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="remarks" class="col-md-3"><?php echo lang('remarks'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "remarks",
                "name" => "remarks",
                "value" => $model_info ? $model_info->remarks : "",
                "class" => "form-control",
                "placeholder" => lang('remarks'),
                "data-rich-text-editor" => true
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
        $("#deliveries-form").appForm({
            onSuccess: function (result) {
                $("#deliveries-table").appTable({newData: result.data, dataId: result.id});
            }
        });

        $('#consumer').select2();
        $('#dispatcher').select2();
        $('#driver').select2();
        $('#vehicle').select2();

        $("#warehouse").select2();
    });
</script>