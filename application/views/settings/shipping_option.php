<div id="page-content" class="p20 clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "shipping_option";
            $this->load->view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <?php echo form_open(get_uri("settings/save_shipping_option_settings"), array("id" => "shipping_option-settings-form", "class" => "general-form dashed-row", "role" => "form")); ?>
            <div class="panel">
                <div class="panel-default panel-heading">
                    <h4><?php echo lang("shipping_option"); ?></h4>
                </div>
                <div class="panel-body">

                    <div class="form-group">
                        <label for="minimum_order" class=" col-md-3"><?php echo lang('minimum_order'); ?></label>
                        <div class="col-md-9">
                            <?php echo form_input(array(
                                "id" => "minimum_order",
                                "name" => "minimum_order",
                                "value" => (int)get_setting('minimum_order'),
                                "class" => "form-control",
                                "placeholder" => lang('minimum_order'),
                                "data-rule-required" => true,
                                "data-msg-required" => lang("field_required"),
                            )); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="base_delivery_fee" class=" col-md-3"><?= lang('base_delivery_fee'); ?></label>
                        <div class="col-md-9">
                            <?php echo form_input(array(
                                "id" => "base_delivery_fee",
                                "name" => "base_delivery_fee",
                                "value" => (int)get_setting('base_delivery_fee'),
                                "class" => "form-control",
                                "placeholder" => lang('base_delivery_fee'),
                                "data-rule-required" => true,
                                "data-msg-required" => lang("field_required"),
                            )); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="free_shipping_amount" class=" col-md-3"><?php echo lang('free_shipping_amount'); ?></label>
                        <div class="col-md-9">
                            <?php echo form_input(array(
                                "id" => "free_shipping_amount",
                                "name" => "free_shipping_amount",
                                "value" => (int)get_setting('free_shipping_amount'),
                                "class" => "form-control",
                                "placeholder" => lang('free_shipping_amount'),
                                "data-rule-required" => true,
                                "data-msg-required" => lang("field_required"),
                            )); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="shipping_computation" class=" col-md-3"><?php echo lang('shipping_computation'); ?></label>
                        <div class="col-md-9">
                            <?php echo form_dropdown(
                                "shipping_computation", array(
                                    "fixed" => lang("fixed"),
                                    "distance" => lang("distance"),
                                    "package" => lang("package")
                                ), get_setting('shipping_computation'), "class='select2 mini'"
                            ); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="fixed_amount" class=" col-md-3"><?php echo lang('fixed_amount'); ?></label>
                        <div class="col-md-9">
                            <?php echo form_input(array(
                                "id" => "fixed_amount",
                                "name" => "fixed_amount",
                                "value" => (int)get_setting('fixed_amount'),
                                "class" => "form-control",
                                "placeholder" => lang('fixed_amount'),
                                "data-rule-required" => true,
                                "data-msg-required" => lang("field_required"),
                            )); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="distance_rate" class=" col-md-3"><?php echo lang('distance_rate'); ?></label>
                        <div class="col-md-9">
                            <?php echo form_input(array(
                                "id" => "distance_rate",
                                "name" => "distance_rate",
                                "value" => (int)get_setting('distance_rate'),
                                "class" => "form-control",
                                "placeholder" => lang('distance_rate'),
                                "data-rule-required" => true,
                                "data-msg-required" => lang("field_required"),
                            )); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="package_rate" class=" col-md-3"><?php echo lang('package_rate'); ?></label>
                        <div class="col-md-9">
                            <?php echo form_input(array(
                                "id" => "package_rate",
                                "name" => "package_rate",
                                "value" => (int)get_setting('package_rate'),
                                "class" => "form-control",
                                "placeholder" => lang('package_rate'),
                                "data-rule-required" => true,
                                "data-msg-required" => lang("field_required"),
                            )); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="tax_applied" class=" col-md-3"><?php echo lang('tax_applied'); ?></label>
                        <div class="col-md-9">
                            <?php echo form_dropdown(
                                "tax_applied", $tax_lists, get_setting('tax_applied'), "class='select2 mini'"
                            ); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="delivery_verification" class=" col-md-3"><?php echo lang('delivery_verification'); ?></label>
                        <div class="col-md-9">
                            <?php echo form_dropdown(
                                "delivery_verification", array(
                                    "optional" => lang("optional"),
                                    "yes" => lang("yes"),
                                    "no" => lang("no"),
                                ), get_setting('delivery_verification'), "class='select2 mini'"
                            ); ?>
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
        $("#shipping_option-settings-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
            }
        });

        $("#shipping_option-settings-form .select2").select2();
    });
</script>