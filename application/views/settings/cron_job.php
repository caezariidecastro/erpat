<div id="page-content" class="p20 clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "cron_job";
            $this->load->view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">

            <div class="panel">
                <div class="panel-default panel-heading">
                    <h4><?php echo lang("cron_job"); ?></h4>
                </div>
                <div class="panel-body general-form dashed-row">
                    <div class="form-group clearfix">
                        <label for="cron_job_link" class=" col-md-2"><?php echo lang('cron_job_link'); ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo get_uri("cron");
                            ?>
                        </div>
                    </div>  
                    <div class="form-group clearfix">
                        <label for="last_cron_job_run" class=" col-md-2"><?php echo lang('last_cron_job_run'); ?></label>
                        <div class=" col-md-10">
                            <?php
                            $status_class = "label-default";
                            $last_cron_job_time = get_setting('last_cron_job_time');
                            if ($last_cron_job_time) {
                                $text = format_to_datetime(date('Y-m-d H:i:s', $last_cron_job_time));

                                //show success color if last execution time is less then 60 min
                                if (round(abs($last_cron_job_time - strtotime(get_current_utc_time())) / 60) <= 60) {
                                    $status_class = "label-success";
                                }
                            } else {
                                $text = lang('never');
                                $status_class = "label-danger";
                            }

                            echo "<span id='last_cron_job_time' class='label $status_class large'>" . $text . "</span>";
                            ?>
                        </div>
                    </div> 
                    <div class="form-group clearfix">
                        <label for="recommended_execution_intervals" class=" col-md-2"><?php echo lang('recommended_execution_interval'); ?></label>
                        <div class=" col-md-10">
                            Every 10 minutes
                        </div>
                    </div> 
                    <div class="form-group clearfix">
                        <label  class=" col-md-2">Cron Job Command *</label>
                        <div class=" col-md-10">
                            <div>
                                <?php echo "<pre>wget -q -O- " . get_uri("cron") . "</pre>"; ?>
                            </div>
                        </div>
                    </div> 
                    <div class="form-group clearfix" style="margin: 0;">
                        <?php echo form_open(get_uri("settings/run_cron_command"), array("id" => "cron-settings-form", "class" => "general-form dashed-row", "style" => "display: block;", "role" => "form")); ?>
                        <button type="submit" class="btn btn-primary"><span class="fa fa-fire"></span> <?php echo lang('run_cron_command'); ?></button>
                        <div style="text-align: right; position: relative; top: -30px;">
                            <input id="autocron" type="checkbox" data-toggle="toggle" style="transform: scale(1.4); margin-right: 10px;">
                            Toggle to Auto-Cron every <input type="number" id="cronfreq" min="2" max="60" value="5"> s
                        </div>
                        <?php echo form_close(); ?>
                    </div> 
                </div>

            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#cron-settings-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                $('#last_cron_job_time').text(result.data);
                appAlert.success(result.message, {duration: 5000});
            },
            onError: function(result) {
                appLoader.hide();
                appAlert.error(result.message, {duration: 5000});
            }
        });

        let auto_loop = null;
        $('#autocron').on('input', function() {
            exe_loop();
        });

        $("#cronfreq").change(function(){
            exe_loop();
        });

        function exe_loop() {
            if(auto_loop) {
                clearInterval(auto_loop);
            }
            
            if( $('#autocron').is(":checked") ) {
                let freq = $('#cronfreq').val();
                start_loop( freq*1000 );
            }
        }

        function start_loop(frequency) {
            auto_loop = setInterval(() => {
                $.ajax({
                    url: "<?= get_uri("settings/run_cron_command") ?>",
                    data: {},
                    type: 'POST',
                    dataType: 'json',
                    success: function (response) {
                        if(response.success) {
                            $('#last_cron_job_time').text(response.data);
                            appAlert.success("Autocron executed successfully.", {duration: 2000});
                        }
                    }
                });
            }, frequency);
        }
    });
</script>