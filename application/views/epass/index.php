<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <ul id="leaves-tabs" data-toggle="ajax-tab" class="nav nav-tabs bg-white inner" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang('epass'); ?></h4></li>
            <li><a  role="presentation" class="active" href="<?php echo_uri("EventPass/view"); ?>" data-target="#epass-panel"><?php echo lang("browse"); ?></a></li>
            <li><a  role="presentation" href="<?php echo_uri("Epass_Area/index"); ?>" data-target="#area-panel"><?php echo lang("area"); ?></a></li>
            <li><a  role="presentation" href="<?php echo_uri("Epass_Block/index"); ?>" data-target="#block-panel"><?php echo lang("block"); ?></a></li>
            <li><a  role="presentation" href="<?php echo_uri("Epass_Seat/index"); ?>" data-target="#seat-panel"><?php echo lang("seat"); ?></a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade active" id="epass-panel"></div>
            <div role="tabpanel" class="tab-pane fade" id="area-panel"></div>
            <div role="tabpanel" class="tab-pane fade" id="block-panel"></div>
            <div role="tabpanel" class="tab-pane fade" id="seat-panel"></div>
        </div>
    </div>
</div>
