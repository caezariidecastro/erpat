<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <ul id="leaves-tabs" data-toggle="ajax-tab" class="nav nav-tabs bg-white inner" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang("leaves"); ?></h4></li>
            <li><a  role="presentation" class="active" href="<?php echo_uri("hrs/leaves/pending_approval/"); ?>" data-target="#leave-pending-approval"><?php echo lang("pending_approval"); ?></a></li>
            <li><a  role="presentation" href="<?php echo_uri("hrs/leaves/all_applications/"); ?>" data-target="#leave-all-applications"><?php echo lang("all_applications"); ?></a></li>
            <li><a  role="presentation" href="<?php echo_uri("hrs/leaves/leave_credits/"); ?>" data-target="#leave-credits"><?php echo lang("leave_credits"); ?></a></li>
            <?php if($this->login_user->is_admin) { ?>
            <li><a  role="presentation" href="<?php echo_uri("hrs/leaves/leave_types/"); ?>" data-target="#leave-types"><?php echo lang("leave_types"); ?></a></li>
            <?php } ?>
            <li><a  role="presentation" href="<?php echo_uri("hrs/leaves/summary/"); ?>" data-target="#leave-summary"><?php echo lang("summary"); ?></a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade active" id="leave-pending-approval"></div>
            <div role="tabpanel" class="tab-pane fade" id="leave-all-applications"></div>
            <div role="tabpanel" class="tab-pane fade" id="leave-credits"></div>
            <div role="tabpanel" class="tab-pane fade" id="leave-types"></div>
            <div role="tabpanel" class="tab-pane fade" id="leave-summary"></div>
        </div>
    </div>
</div>