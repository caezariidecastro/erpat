<div id="page-content" class="p20 clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "finance";
            $this->load->view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <?php echo form_open(get_uri("settings/save_finance_settings"), array("id" => "finance-settings-form", "class" => "general-form dashed-row", "role" => "form")); ?>
            <div class="panel">
                <div class="panel-default panel-heading">
                    <h4><?php echo lang("finance") ." ". lang("settings"); ?></h4>
                </div>
                <div class="panel-body">

                <div class="form-group">
                        <label for="default_currency" class=" col-md-2"><?php echo lang('currency'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_dropdown(
                                    "default_currency", $currency_dropdown, get_setting('default_currency'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="currency_symbol" class=" col-md-2"><?php echo lang('currency_symbol'); ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "currency_symbol",
                                "name" => "currency_symbol",
                                "value" => get_setting('currency_symbol'),
                                "class" => "form-control",
                                "placeholder" => lang('currency_symbol'),
                                "data-rule-required" => true,
                                "data-msg-required" => lang("field_required"),
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="currency_position" class=" col-md-2"><?php echo lang('currency_position'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_dropdown(
                                    "currency_position", array(
                                "left" => lang("left"),
                                "right" => lang("right")
                                    ), get_setting('currency_position'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="decimal_separator" class=" col-md-2"><?php echo lang('decimal_separator'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_dropdown(
                                    "decimal_separator", array("." => "Dot (.)", "," => "Comma (,)"), get_setting('decimal_separator'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="no_of_decimals" class=" col-md-2"><?php echo lang('no_of_decimals'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_dropdown(
                                    "no_of_decimals", array(
                                "0" => "0",
                                "2" => "2"
                                    ), get_setting('no_of_decimals') == "0" ? "0" : "2", "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="attendance_calc_mode" class=" col-md-2"><?php echo lang('attendance_calc_mode'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_dropdown(
                                    "attendance_calc_mode", array(
                                "simple" => "Simple",
                                "complex" => "Complex",
                                    ), get_setting('attendance_calc_mode'), "class='select2 mini'"
                            );
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="basic_pay_calculation" class=" col-md-2"><?php echo lang('basic_pay_calculation'); ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_dropdown(
                                "basic_pay_calculation", array(
                                "hourly_based" => "Base on Worked hour",
                                "scheduled_based" => "Base on Schedule",
                            ), get_setting('basic_pay_calculation', 'hourly_based'), "class='select2 mini'"
                            );
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
        $("#finance-settings-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
            }
        });

        $("#finance-settings-form .select2").select2();
    });
</script>