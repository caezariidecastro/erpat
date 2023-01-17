<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <ul id="leaves-tabs" data-toggle="ajax-tab" class="nav nav-tabs bg-white inner" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang('employee'); ?></h4></li>
            <li><a  role="presentation" class="active" href="<?php echo_uri("Team_members/lists"); ?>" data-target="#user-panel"><?php echo lang("general"); ?></a></li>
            <li><a  role="presentation" href="<?php echo_uri("Team_members/personal_view"); ?>" data-target="#personal-panel"><?php echo lang("personal_info"); ?></a></li>
            <li><a  role="presentation" href="<?php echo_uri("Team_members/job_view"); ?>" data-target="#job-panel"><?php echo lang("job_info"); ?></a></li>
            <li><a  role="presentation" href="<?php echo_uri("Team_members/bank_view"); ?>" data-target="#bank-panel"><?php echo lang("bank_details"); ?></a></li>
            <li><a  role="presentation" href="<?php echo_uri("Team_members/contribution_view"); ?>" data-target="#contributions-panel"><?php echo lang("contributions"); ?></a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade active" id="user-panel"></div>
            <div role="tabpanel" class="tab-pane fade" id="personal-panel"></div>
            <div role="tabpanel" class="tab-pane fade" id="job-panel"></div>
            <div role="tabpanel" class="tab-pane fade" id="bank-panel"></div>
            <div role="tabpanel" class="tab-pane fade" id="contributions-panel"></div>
        </div>
    </div>
</div>
