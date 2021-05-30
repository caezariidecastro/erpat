<div id="page-content" class="p20 clearfix">
    <div class="row">

        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "galyon";
            $this->load->view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <?php echo form_open(get_uri("settings/save_galyon_settings"), array("id" => "galyon-settings-form", "class" => "general-form dashed-row", "role" => "form")); ?>
            <div class="panel">
                <div class="panel-default panel-heading">
                    <h4><?php echo lang("general_settings"); ?></h4>
                </div>
                <div class="panel-body post-dropzone">

                    <div class="form-group">
                        <label for="minimum_order" class=" col-md-2"><?php echo lang('minimum_order'); ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "minimum_order",
                                "name" => "minimum_order",
                                "type" => "number",
                                "value" => get_setting('minimum_order') ? get_setting('minimum_order') : 0.00,
                                "class" => "form-control",
                                "placeholder" => "0.00",
                                "data-rule-required" => true,
                                "data-msg-required" => lang("field_required"),
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="free_delivery" class=" col-md-2"><?php echo lang('free_delivery'); ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "free_delivery",
                                "name" => "free_delivery",
                                "type" => "number",
                                "value" => get_setting('free_delivery') ? get_setting('free_delivery') : 0.00,
                                "class" => "form-control",
                                "placeholder" => "0.00",
                                "data-rule-required" => true,
                                "data-msg-required" => lang("field_required"),
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="shipping_charge" class=" col-md-2"><?php echo lang('shipping_charge'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_dropdown(
                                    "shipping_charge", array(
                                        "km" => lang("per_kilometer"),
                                        "fixed" => lang("fixed_amount"),
                                    ), get_setting('shipping_charge'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="shipping_price" class=" col-md-2"><?php echo lang('shipping_price'); ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "shipping_price",
                                "name" => "shipping_price",
                                "type" => "number",
                                "value" => get_setting('shipping_price') ? get_setting('shipping_price') : 0.00,
                                "class" => "form-control",
                                "placeholder" => "0.00",
                                "data-rule-required" => true,
                                "data-msg-required" => lang("field_required"),
                            ));
                            ?>
                        </div>
                    </div>

                </div>
                <div class="panel-footer">
                    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#galyon-settings-form .select2").select2();
        
        $("#galyon-settings-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
                location.reload();
            }
        });
    });
</script>