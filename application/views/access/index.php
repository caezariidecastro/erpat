<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <ul id="access-tabs" data-toggle="ajax-tab" class="nav nav-tabs bg-white inner" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang('door_access'); ?></h4></li>
            <li><a  role="presentation" class="active" href="<?php echo_uri("Access_logs/view"); ?>" data-target="#access_logs-panel"><?php echo lang("logs"); ?></a></li>
            <li><a  role="presentation" href="<?php echo_uri("Access_devices/index"); ?>" data-target="#access_devices-panel"><?php echo lang("devices"); ?></a></li>
            <li><a  role="presentation" href="<?php echo_uri("Access_device_categories/index"); ?>" data-target="#access_categories-panel"><?php echo lang("categories"); ?></a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade active" id="access_logs-panel"></div>
            <div role="tabpanel" class="tab-pane fade" id="access_devices-panel"></div>
            <div role="tabpanel" class="tab-pane fade" id="access_categories-panel"></div>
        </div>
    </div>
</div>
