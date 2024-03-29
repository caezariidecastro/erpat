<?php echo form_open(get_uri("sales/Estimates/save"), array("id" => "estimate-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <input type="hidden" name="estimate_request_id" value="<?php echo $estimate_request_id; ?>" />

    <?php if ($is_clone) { ?>
        <input type="hidden" name="is_clone" value="1" />
        <input type="hidden" name="discount_amount" value="<?php echo $model_info->discount_amount; ?>" />
        <input type="hidden" name="discount_amount_type" value="<?php echo $model_info->discount_amount_type; ?>" />
        <input type="hidden" name="discount_type" value="<?php echo $model_info->discount_type; ?>" />
    <?php } ?>

    <div class="form-group">
        <label for="estimate_date" class=" col-md-3"><?php echo lang('estimate_date'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "estimate_date",
                "name" => "estimate_date",
                "value" => $model_info->estimate_date,
                "class" => "form-control",
                "placeholder" => lang('estimate_date'),
                "autocomplete" => "off",
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="valid_until" class=" col-md-3"><?php echo lang('valid_until'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "valid_until",
                "name" => "valid_until",
                "value" => $model_info->valid_until,
                "class" => "form-control",
                "placeholder" => lang('valid_until'),
                "autocomplete" => "off",
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
                "data-rule-greaterThanOrEqual" => "#estimate_date",
                "data-msg-greaterThanOrEqual" => lang("end_date_must_be_equal_or_greater_than_start_date")
            ));
            ?>
        </div>
    </div>
    <?php if (!$client_id) { ?>
    <div class="form-group">
        <label for="type" class=" col-md-3"><?php echo lang('type'); ?></label>
        <div class="col-md-9">
            <label for="service" class="mr10">
                <input id="service" name="type" type="radio" <?= !$model_info->type ? "checked" : ($model_info->type == "service" ? "checked" : "") ?> value="service"/>
                Client
            </label>
            <label for="product" class="">
                <input id="product" name="type" type="radio" <?= $model_info->type == "product" ? "checked" : "" ?> value="product"/>
                Customer
            </label>
        </div>
    </div>
    <?php } ?>
    <?php if ($client_id) { ?>
        <input type="hidden" name="estimate_client_id" value="<?php echo $client_id; ?>" />
    <?php } else if(!$model_info->consumer_id) { ?>
        <div class="form-group">
            <label for="estimate_client_id" class=" col-md-3" id="estimate_client_id_label"><?php echo lang('client'); ?></label>
            <div class="col-md-9" id="estimate_client_selection_wrapper">
                <?php
                echo form_input(array(
                    "id" => "estimate_client_id",
                    "name" => "estimate_client_id",
                    "value" => $model_info->client_id,
                    "class" => "form-control",
                    "data-rule-required" => "true",
                    "data-msg-required" => lang('field_required'),
                    "placeholder" => lang('client')
                ));
                ?>
            </div>
        </div>
    <?php } else if($model_info->consumer_id) { ?>
        <div class="form-group">
            <label for="estimate_client_id" class=" col-md-3" id="estimate_client_id_label"><?php echo lang('customer'); ?></label>
            <div class="col-md-9" id="estimate_client_selection_wrapper">
                <?php
                echo form_input(array(
                    "id" => "estimate_client_id",
                    "name" => "estimate_client_id",
                    "value" => $model_info->consumer_id,
                    "class" => "form-control",
                    "data-rule-required" => "true",
                    "data-msg-required" => lang('field_required'),
                    "placeholder" => lang('customer')
                ));
                ?>
            </div>
        </div>
    <?php } ?>

    <div class="form-group">
        <label for="tax_id" class=" col-md-3"><?php echo lang('tax'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("tax_id", $taxes_dropdown, array($model_info->tax_id), "class='select2 tax-select2'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="tax_id" class=" col-md-3"><?php echo lang('second_tax'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("tax_id2", $taxes_dropdown, array($model_info->tax_id2), "class='select2 tax-select2'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="estimate_note" class=" col-md-3"><?php echo lang('note'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "estimate_note",
                "name" => "estimate_note",
                "value" => $model_info->note ? $model_info->note : "",
                "class" => "form-control",
                "placeholder" => lang('note'),
                "data-rich-text-editor" => true
            ));
            ?>
        </div>
    </div>

    <?php $this->load->view("custom_fields/form/prepare_context_fields", array("custom_fields" => $custom_fields, "label_column" => "col-md-3", "field_column" => " col-md-9")); ?> 

    <?php if ($is_clone) { ?>
        <div class="form-group">
            <label for="copy_items"class=" col-md-12">
                <?php
                echo form_checkbox("copy_items", "1", true, "id='copy_items' disabled='disabled' class='pull-left mr15'");
                ?>    
                <?php echo lang('copy_items'); ?>
            </label>
        </div>
        <div class="form-group">
            <label for="copy_discount"class=" col-md-12">
                <?php
                echo form_checkbox("copy_discount", "1", true, "id='copy_discount' disabled='disabled' class='pull-left mr15'");
                ?>    
                <?php echo lang('copy_discount'); ?>
            </label>
        </div>
    <?php } ?> 

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#estimate-form").appForm({
            onSuccess: function (result) {
                if (typeof RELOAD_VIEW_AFTER_UPDATE !== "undefined" && RELOAD_VIEW_AFTER_UPDATE) {
                    location.reload();
                } else {
                    window.location = "<?php echo site_url('estimates/view'); ?>/" + result.id;
                }
            }
        });
        $("#estimate-form .tax-select2").select2();
        $("#estimate_client_id").select2({data: <?= json_encode($model_info->consumer_id ? $consumer_dropdown : $clients_dropdown)?>});

        setDatePicker("#estimate_date, #valid_until");

        $("#product").click(function(){
            $('#estimate_client_id').select2("destroy");
            $("#estimate_client_id_label").html("Customer");
            $("#estimate_client_id").attr("placeholder", "Customer");
            $("#estimate_client_id").hide();
            appLoader.show({container: "#estimate_client_selection_wrapper", css:"left: 7%; bottom: -30px;"});

            $.ajax({
                url: "<?php echo get_uri("sms/customer/get_customer_select2_data") ?>",
                dataType: "json",
                success: function (result) {
                    $("#estimate_client_id").show().val("");
                    $('#estimate_client_id').select2({data: result});
                    appLoader.hide();
                }
            });
        });

        $("#service").click(function(){
            $('#estimate_client_id').select2("destroy");
            $("#estimate_client_id_label").html("Client");
            $("#estimate_client_id").attr("placeholder", "Client");
            $("#estimate_client_id").hide();
            appLoader.show({container: "#estimate_client_selection_wrapper", css:"left: 7%; bottom: -30px;"});

            $.ajax({
                url: "<?php echo get_uri("sales/Estimate_requests/get_clients_and_leads_select2") ?>",
                dataType: "json",
                success: function (result) {
                    $("#estimate_client_id").show().val("");
                    $('#estimate_client_id').select2({data: result});
                    appLoader.hide();
                }
            });
        });
    });
</script>