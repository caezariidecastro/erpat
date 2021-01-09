<?php echo form_open(get_uri("inventory_transfers/save"), array("id" => "inventory-transfers-form", "class" => "general-form", "role" => "form")); ?>
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
        <label for="transferee" class="col-md-3"><?php echo lang('from'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("transferee", $warehouse_dropdown, $model_info ? $model_info->transferee : "", "class='select2 validate-hidden' id='transferee' data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="receiver" class="col-md-3"><?php echo lang('to'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("receiver", $warehouse_dropdown, $model_info ? $model_info->receiver : "", "class='select2 validate-hidden' id='transferee' data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="dispatcher" class="col-md-3"><?php echo lang('dispatcher'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("dispatcher", $user_dropdown, $model_info ? $model_info->dispatcher : "", "class='select2 validate-hidden' id='transferee' data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="driver" class="col-md-3"><?php echo lang('driver'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("driver", $user_dropdown, $model_info ? $model_info->driver : "", "class='select2 validate-hidden' id='transferee' data-rule-required='true' data-msg-required='".lang("field_required")."'");
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
        $("#inventory-transfers-form").appForm({
            onSuccess: function (result) {
                $("#inventory-transfers-table").appTable({newData: result.data, dataId: result.id});
            },
            onSubmit: function (data, self, options) {
                $('#reference_number').removeAttr('disabled');
            }
        });

        $('.select2').select2();
    });
    
</script>