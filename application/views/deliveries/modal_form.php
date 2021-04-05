<style type="text/css">
    .datatable-tools:first-child {
        display:  none;
    }
</style>

<?php echo form_open(get_uri("lds/deliveries/save"), array("id" => "deliveries-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info ? $model_info->id : "" ?>" />
    <input type="hidden" name="invoice_id" value="<?php echo $model_info ? $model_info->invoice_id : "" ?>" />
    <?php if($model_info->id){?>
    <div class="form-group">
        <label for="status" class="col-md-3"><?php echo lang('status'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("status", $status_dropdown, $model_info ? $model_info->status : "", "class='select2 validate-hidden' id='status' data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>
    </div>
    <?php }?>
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
            echo form_dropdown("driver", $driver_dropdown, $model_info ? $model_info->driver : "", "class='select2 validate-hidden' id='driver' data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="passengers" class=" col-md-3"><?php echo lang('passengers'); ?></label>
        <div class="col-md-9">
              <input type="text" value="<?php echo $model_info->passengers; ?>" name="passengers" id="passenger_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="-"  />    
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
    <hr>
    <div class="form-group">
        <label for="street" class="col-md-3"><?php echo lang('street'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "street",
                "name" => "street",
                "value" => $model_info ? $model_info->street : "",
                "class" => "form-control",
                "placeholder" => lang('street'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="city" class="col-md-3"><?php echo lang('city'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "city",
                "name" => "city",
                "value" => $model_info ? $model_info->city : "",
                "class" => "form-control",
                "placeholder" => lang('city'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="state" class="col-md-3"><?php echo lang('state'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "state",
                "name" => "state",
                "value" => $model_info ? $model_info->state : "",
                "class" => "form-control",
                "placeholder" => lang('state'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="zip" class="col-md-3"><?php echo lang('zip'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "zip",
                "name" => "zip",
                "value" => $model_info ? $model_info->zip : "",
                "class" => "form-control",
                "placeholder" => lang('zip'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="country" class="col-md-3"><?php echo lang('country'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "country",
                "name" => "country",
                "value" => $model_info ? $model_info->country : "",
                "class" => "form-control",
                "placeholder" => lang('country'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
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
            },
            onSubmit: function(){
                $('#reference_number').removeAttr("disabled");
            }
        });

        $('#consumer').select2().change(function(e){
            $.ajax({
                url: "<?php echo get_uri("consumers/get_consumer"); ?>",
                data: {id: e.val},
                cache: false,
                type: 'POST',
                dataType: "json",
                success: function (response) {
                    if (response && response.success) {
                        $("#street").val(response.consumer_info.street);
                        $("#city").val(response.consumer_info.city);
                        $("#state").val(response.consumer_info.state);
                        $("#zip").val(response.consumer_info.zip);
                        $("#country").val(response.consumer_info.country);
                    }
                }
            });
        });

        $('#dispatcher').select2();
        $('#driver').select2();
        $('#vehicle').select2();

        $("#status").select2();

        $("#passenger_dropdown").select2({
            multiple: true,
            data: <?php echo $users_select2; ?>
        });
    });
</script>