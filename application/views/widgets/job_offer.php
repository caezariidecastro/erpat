<div class="box">
    <div class="box-content widget-container b-r">
        <div class="panel-body ">
            <h2 class=""><?= $job_info->salary?to_currency($job_info->salary):to_currency(get_monthly_salary($job_info->rate_per_hour)) ?></h2>
            <span class="text-off uppercase"><?php echo lang("monthly_salary"); ?></span>
        </div>
    </div>
    <div class="box-content widget-container ">
        <div class="panel-body ">
            <h2><?= to_currency($job_info->rate_per_hour) ?>/hr</h2>
            <span class="text-off uppercase"><?php echo lang("hourly_rate"); ?></span>
        </div>
    </div>
</div>