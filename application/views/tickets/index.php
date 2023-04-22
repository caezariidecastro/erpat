<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <ul id="leaves-tabs" data-toggle="ajax-tab" class="nav nav-tabs bg-white inner" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang('tickets'); ?></h4></li>
            <li><a  role="presentation" class="active" href="<?php echo_uri("tickets/view_browse"); ?>" data-target="#browse-panel"><?php echo lang("browse"); ?></a></li>
            <?php if($this->login_user->is_admin) { ?>
            <li><a  role="presentation" href="<?php echo_uri("tickets/view_templates"); ?>" data-target="#templates-panel"><?php echo lang("templates"); ?></a></li>
            <li><a  role="presentation" href="<?php echo_uri("tickets/view_types"); ?>" data-target="#types-panel"><?php echo lang("types"); ?></a></li>
            <?php } ?>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade active" id="browse-panel"></div>
            <?php if($this->login_user->is_admin) { ?>
            <div role="tabpanel" class="tab-pane fade" id="templates-panel"></div>
            <div role="tabpanel" class="tab-pane fade" id="types-panel"></div>
            <?php } ?>
        </div>
    </div>
</div>