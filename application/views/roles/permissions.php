<div class="tab-content">
    <?php echo form_open(get_uri("roles/save_permissions"), array("id" => "permissions-form", "class" => "general-form dashed-row", "role" => "form")); ?>
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="panel">
        <div class="panel-default panel-heading">
            <h4><?php echo lang('permissions') . ": " . $model_info->title; ?></h4>
        </div>
        <div class="panel-body">
            <style>
                .perm-head {
                    margin: 10px 0 5px;
                    display: block;
                }
            </style>

            <ul class="permission-list">
                <!-- GENERAL -->
                <li>
                    <ul class="nav nav-tabs vertical settings p15" role="tablist">
                        <div class="clearfix settings-anchor collapsed" data-toggle="collapse" data-target="#roles-tab-general" aria-expanded="true">
                            <?php echo lang("general_settings"); ?><span class="pull-right"><i class="fa fa-plus-square-o"></i></span>
                        </div>
                        <div id="roles-tab-general" class="collapse ">
                            <ul class="list-group help-catagory">
                                <div>
                                    <?php
                                    echo form_checkbox("message_permission_no", "1", ($message_permission == "no") ? true : false, "id='message_permission_no'");
                                    ?>
                                    <label for="message_permission_no"><?php echo lang("cant_send_any_messages"); ?></label>
                                </div>
                                <div id="message_permission_specific_area" class="form-group <?php echo ($message_permission == "no") ? "hide" : ""; ?>">
                                    <?php
                                    echo form_checkbox("message_permission_specific_checkbox", "1", ($message_permission == "specific") ? true : false, "id='message_permission_specific_checkbox' class='message_permission_specific toggle_specific'");
                                    ?>
                                    <label for="message_permission_specific_checkbox"><?php echo lang("can_send_messages_to_specific_members_or_teams"); ?></label>
                                    <div class="specific_dropdown">
                                        <input type="text" value="<?php echo $message_permission_specific; ?>" name="message_permission_specific" id="message_permission_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />    
                                    </div>
                                </div>
                                <div>
                                    <?php
                                        echo form_checkbox("disable_event_sharing", "1", $disable_event_sharing ? true : false, "id='disable_event_sharing'");
                                    ?>
                                    <label for="disable_event_sharing"><?php echo lang("disable_event_sharing"); ?></label>
                                </div>
                                <div>
                                    <?php
                                        echo form_radio(array(
                                            "id" => "announcement_yes",
                                            "name" => "announcement_permission",
                                            "value" => "all",
                                                ), $announcement, ($announcement === "all") ? true : false);
                                    ?>
                                    <label for="announcement_yes"><?php echo lang("yes"); ?></label>
                                    <?php
                                        echo form_radio(array(
                                            "id" => "announcement_no",
                                            "name" => "announcement_permission",
                                            "value" => "",
                                                ), $announcement, ($announcement === "") ? true : false);
                                    ?>
                                    <label for="announcement_no"><?php echo lang("no"); ?> </label> - 
                                    <label for="announcement_yes"><?php echo lang("can_manage_announcements"); ?> </label>
                                </div>
                            </ul>
                        </div>
                    </ul>
                </li>
                <!-- HRS -->
                <li>
                    <ul class="nav nav-tabs vertical settings p15" role="tablist">
                        <div class="clearfix settings-anchor collapsed" data-toggle="collapse" data-target="#roles-tab-module_hrs" aria-expanded="true">
                            <?php echo lang("module_hrs"); ?><span class="pull-right"><i class="fa fa-plus-square-o"></i></span>
                        </div>
                        <div id="roles-tab-module_hrs" class="collapse ">
                            <ul class="list-group help-catagory">
                            <div class="form-group">
                                <?php
                                echo form_checkbox("module_hrs", "1", $module_hrs ? true : false, "id='module_hrs'");
                                ?>
                                <label for="module_hrs"><?php echo lang("module_enable"); ?></label>
                            </div>
                            <div class="form-group">
                                <h5>Employee</h5>
                                <?php echo form_checkbox("hrs_employee_view", "1", $hrs_employee_view ? true : false, "id='hrs_employee_view'"); ?>
                                <label for="hrs_employee_view">	&nbsp;<?php echo lang("view"); ?></label>
                                <?php echo form_checkbox("hrs_employee_add", "1", $hrs_employee_add ? true : false, "id='hrs_employee_add'"); ?>
                                <label for="hrs_employee_add"> &nbsp;<?php echo lang("add"); ?></label>
                                <?php echo form_checkbox("hrs_employee_edit", "1", $hrs_employee_edit ? true : false, "id='hrs_employee_edit'"); ?>
                                <label for="hrs_employee_edit">	&nbsp;<?php echo lang("edit"); ?></label>
                                <?php echo form_checkbox("hrs_employee_delete", "1", $hrs_employee_delete ? true : false, "id='hrs_employee_delete'"); ?>
                                <label for="hrs_employee_delete"> &nbsp;<?php echo lang("delete"); ?></label>
                                <?php echo form_checkbox("hrs_employee_invite", "1", $hrs_employee_invite ? true : false, "id='hrs_employee_invite'"); ?>
                                <label for="hrs_employee_invite"> &nbsp;<?php echo lang("invite"); ?></label>
                            </div>

                            <div>
                                <?php
                                echo form_checkbox("can_use_biometric", "1", $can_use_biometric ? true : false, "id='can_use_biometric'");
                                ?>
                                <label for="can_use_biometric"><?php echo lang("can_use_biometric"); ?></label>
                            </div>

                            <div>
                                <?php
                                echo form_checkbox("can_view_team_members_contact_info", "1", $can_view_team_members_contact_info ? true : false, "id='can_view_team_members_contact_info'");
                                ?>
                                <label for="can_view_team_members_contact_info"><?php echo lang("can_view_team_members_contact_info"); ?></label>
                            </div>
                            
                            <strong class="perm-head"><?php echo lang("can_manage_team_members_timecards"); ?>
                                <span class="help" data-toggle="tooltip" title="Add, edit and delete attendance"><i class="fa fa-question-circle"></i></span>
                            </strong>
                            <div>
                                <?php
                                echo form_radio(array(
                                    "id" => "attendance_permission_no",
                                    "name" => "attendance_permission",
                                    "value" => "",
                                    "class" => "attendance_permission toggle_specific",
                                        ), $attendance, ($attendance === "") ? true : false);
                                ?>
                                <label for="attendance_permission_no"><?php echo lang("no"); ?> </label>
                            </div>
                            <div>
                                <?php
                                echo form_radio(array(
                                    "id" => "attendance_permission_all",
                                    "name" => "attendance_permission",
                                    "value" => "all",
                                    "class" => "attendance_permission toggle_specific",
                                        ), $attendance, ($attendance === "all") ? true : false);
                                ?>
                                <label for="attendance_permission_all"><?php echo lang("yes_all_members"); ?></label>
                            </div>
                            <div class="form-group">
                                <?php
                                echo form_radio(array(
                                    "id" => "attendance_permission_specific",
                                    "name" => "attendance_permission",
                                    "value" => "specific",
                                    "class" => "attendance_permission toggle_specific",
                                        ), $attendance, ($attendance === "specific") ? true : false);
                                ?>
                                <label for="attendance_permission_specific"><?php echo lang("yes_specific_members_or_teams") . " (" . lang("excluding_his_her_time_cards") . ")"; ?>:</label>
                                <div class="specific_dropdown">
                                    <input type="text" value="<?php echo $attendance_specific; ?>" name="attendance_permission_specific" id="attendance_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />
                                </div>
                            </div>

                            <strong class="perm-head"><?php echo lang("can_manage_team_members_leave"); ?>
                                <span class="help" data-toggle="tooltip" title="Assign, approve or reject leave applications"><i class="fa fa-question-circle"></i></span> 
                            </strong>
                            <div>
                                <?php
                                echo form_radio(array(
                                    "id" => "leave_permission_no",
                                    "name" => "leave_permission",
                                    "value" => "",
                                    "class" => "leave_permission toggle_specific",
                                        ), $leave, ($leave === "") ? true : false);
                                ?>
                                <label for="leave_permission_no"><?php echo lang("no"); ?></label>
                            </div>
                            <div>
                                <?php
                                echo form_radio(array(
                                    "id" => "leave_permission_all",
                                    "name" => "leave_permission",
                                    "value" => "all",
                                    "class" => "leave_permission toggle_specific",
                                        ), $leave, ($leave === "all") ? true : false);
                                ?>
                                <label for="leave_permission_all"><?php echo lang("yes_all_members"); ?></label>
                            </div>
                            <div class="form-group pb0 mb0 no-border">
                                <?php
                                echo form_radio(array(
                                    "id" => "leave_permission_specific",
                                    "name" => "leave_permission",
                                    "value" => "specific",
                                    "class" => "leave_permission toggle_specific",
                                        ), $leave, ($leave === "specific") ? true : false);
                                ?>
                                <label for="leave_permission_specific"><?php echo lang("yes_specific_members_or_teams") . " (" . lang("excluding_his_her_leaves") . ")"; ?>:</label>
                                <div class="specific_dropdown">
                                    <input type="text" value="<?php echo $leave_specific; ?>" name="leave_permission_specific" id="leave_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />    
                                </div>

                            </div>
                            <div class="form-group">
                                <div>
                                    <?php
                                    echo form_checkbox("can_delete_leave_application", "1", $can_delete_leave_application ? true : false, "id='can_delete_leave_application'");
                                    ?>
                                    <label for="can_delete_leave_application"><?php echo lang("can_delete_leave_application"); ?> <span class="help" data-toggle="tooltip" title="Can delete based on his/her access permission"><i class="fa fa-question-circle"></i></span></label>
                                </div>
                            </div>

                            <strong class="perm-head"><?php echo lang("set_team_members_permission"); ?></strong>

                            <div>
                                <?php
                                echo form_checkbox("hide_team_members_list", "1", $hide_team_members_list ? true : false, "id='hide_team_members_list'");
                                ?>
                                <label for="hide_team_members_list"><?php echo lang("hide_team_members_list"); ?></label>
                            </div>

                            <div>
                                <?php
                                echo form_checkbox("can_view_team_members_social_links", "1", $can_view_team_members_social_links ? true : false, "id='can_view_team_members_social_links'");
                                ?>
                                <label for="can_view_team_members_social_links"><?php echo lang("can_view_team_members_social_links"); ?></label>
                            </div>

                            <div>
                                <label for="can_update_team_members_general_info_and_social_links">
                                    <?php echo lang("can_update_team_members_general_info_and_social_links"); ?>
                                </label>
                                <div class="ml15">
                                    <div>
                                        <?php
                                        echo form_radio(array(
                                            "id" => "team_member_update_permission_no",
                                            "name" => "team_member_update_permission",
                                            "value" => "",
                                            "class" => "team_member_update_permission toggle_specific",
                                                ), $team_member_update_permission, ($team_member_update_permission === "") ? true : false);
                                        ?>
                                        <label for="team_member_update_permission_no"><?php echo lang("no"); ?></label>
                                    </div>
                                    <div>
                                        <?php
                                        echo form_radio(array(
                                            "id" => "team_member_update_permission_all",
                                            "name" => "team_member_update_permission",
                                            "value" => "all",
                                            "class" => "team_member_update_permission toggle_specific",
                                                ), $team_member_update_permission, ($team_member_update_permission === "all") ? true : false);
                                        ?>
                                        <label for="team_member_update_permission_all"><?php echo lang("yes_all_members"); ?></label>
                                    </div>
                                    <div class="form-group">
                                        <?php
                                        echo form_radio(array(
                                            "id" => "team_member_update_permission_specific",
                                            "name" => "team_member_update_permission",
                                            "value" => "specific",
                                            "class" => "team_member_update_permission toggle_specific",
                                                ), $team_member_update_permission, ($team_member_update_permission === "specific") ? true : false);
                                        ?>
                                        <label for="team_member_update_permission_specific"><?php echo lang("yes_specific_members_or_teams"); ?>:</label>
                                        <div class="specific_dropdown">
                                            <input type="text" value="<?php echo $team_member_update_permission_specific; ?>" name="team_member_update_permission_specific" id="team_member_update_permission_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />    
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </ul>
                        </div>
                    </ul>
                </li>
                <!-- MCS -->
                <li>
                    <ul class="nav nav-tabs vertical settings p15" role="tablist">
                        <div class="clearfix settings-anchor collapsed" data-toggle="collapse" data-target="#roles-tab-module_mcs" aria-expanded="true">
                            <?php echo lang("module_mcs"); ?><span class="pull-right"><i class="fa fa-plus-square-o"></i></span>
                        </div>
                        <div id="roles-tab-module_mcs" class="collapse ">
                            <ul class="list-group help-catagory">
                            <div>
                            <?php
                            echo form_checkbox("module_mcs", "1", $module_mcs ? true : false, "id='module_mcs'");
                            ?>
                            <label for="module_mcs"><?php echo lang("module_enable"); ?></label>
                        </div>

                        <h5><?php echo lang("can_access_leads_information"); ?></h5>
                        <div>
                            <?php
                            echo form_radio(array(
                                "id" => "lead_no",
                                "name" => "lead_permission",
                                "value" => "",
                                    ), $lead, ($lead === "") ? true : false);
                            ?>
                            <label for="lead_no"><?php echo lang("no"); ?> </label>
                        </div>
                        <div>
                            <?php
                            echo form_radio(array(
                                "id" => "lead_yes",
                                "name" => "lead_permission",
                                "value" => "all",
                                    ), $lead, ($lead === "all") ? true : false);
                            ?>
                            <label for="lead_yes"><?php echo lang("yes"); ?></label>
                        </div>
                            </ul>
                        </div>
                    </ul>
                </li>
                <!-- SMS -->
                <li>
                    <ul class="nav nav-tabs vertical settings p15" role="tablist">
                        <div class="clearfix settings-anchor collapsed" data-toggle="collapse" data-target="#roles-tab-module_sms" aria-expanded="true">
                            <?php echo lang("module_sms"); ?><span class="pull-right"><i class="fa fa-plus-square-o"></i></span>
                        </div>
                        <div id="roles-tab-module_sms" class="collapse ">
                            <ul class="list-group help-catagory">
                                <div>
                                    <?php
                                    echo form_checkbox("module_sms", "1", $module_sms ? true : false, "id='module_sms'");
                                    ?>
                                    <label for="module_sms"><?php echo lang("module_enable"); ?></label>
                                </div>

                                <h5><?php echo lang("can_access_clients_information"); ?> <span class="help" data-toggle="tooltip" title="Hides all information of clients except company name."><i class="fa fa-question-circle"></i></span></h5>
                                <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "client_no",
                                        "name" => "client_permission",
                                        "value" => "",
                                            ), $client, ($client === "") ? true : false);
                                    ?>
                                    <label for="client_no"><?php echo lang("no"); ?> </label>
                                </div>
                                <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "client_yes",
                                        "name" => "client_permission",
                                        "value" => "all",
                                            ), $client, ($client === "all") ? true : false);
                                    ?>
                                    <label for="client_yes"><?php echo lang("yes"); ?></label>
                                </div>
                            </ul>
                        </div>
                    </ul>
                </li>
                <!-- FAS -->
                <li>
                    <ul class="nav nav-tabs vertical settings p15" role="tablist">
                        <div class="clearfix settings-anchor collapsed" data-toggle="collapse" data-target="#roles-tab-module_fas" aria-expanded="true">
                            <?php echo lang("module_fas"); ?><span class="pull-right"><i class="fa fa-plus-square-o"></i></span>
                        </div>
                        <div id="roles-tab-module_fas" class="collapse ">
                            <ul class="list-group help-catagory">
                                <div>
                            <?php
                            echo form_checkbox("module_fas", "1", $module_fas ? true : false, "id='module_fas'");
                            ?>
                            <label for="module_fas"><?php echo lang("module_enable"); ?></label>
                        </div>
                        <div>
                            <?php
                            echo form_checkbox("can_use_payhp", "1", $can_use_payhp ? true : false, "id='can_use_payhp'");
                            ?>
                            <label for="can_use_payhp"><?php echo lang("can_use_payhp"); ?></label>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <strong class="perm-head"><?php echo lang("can_access_payrolls"); ?></strong>
                                <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "payroll_no",
                                        "name" => "payroll_permission",
                                        "value" => "",
                                            ), $payroll, ($payroll === "") ? true : false);
                                    ?>
                                    <label for="payroll_no"><?php echo lang("no"); ?> </label>
                                </div>
                                <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "payroll_yes",
                                        "name" => "payroll_permission",
                                        "value" => "all",
                                            ), $payroll, ($payroll === "all") ? true : false);
                                    ?>
                                    <label for="payroll_yes"><?php echo lang("yes"); ?></label>
                                </div>
                                <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "payroll_read_only",
                                        "name" => "payroll_permission",
                                        "value" => "read_only",
                                            ), $payroll, ($payroll === "read_only") ? true : false);
                                    ?>
                                    <label for="payroll_read_only"><?php echo lang("read_only"); ?></label>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <h5><?php echo lang("can_access_expenses"); ?></h5>
                                <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "expense_no",
                                        "name" => "expense_permission",
                                        "value" => "",
                                            ), $expense, ($expense === "") ? true : false);
                                    ?>
                                    <label for="expense_no"><?php echo lang("no"); ?> </label>
                                </div>
                                <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "expense_yes",
                                        "name" => "expense_permission",
                                        "value" => "all",
                                            ), $expense, ($expense === "all") ? true : false);
                                    ?>
                                    <label for="expense_yes"><?php echo lang("yes"); ?></label>
                                </div>
                                <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "expense_read_only",
                                        "name" => "expense_permission",
                                        "value" => "read_only",
                                            ), $invoice, ($invoice === "read_only") ? true : false);
                                    ?>
                                    <label for="expense_read_only"><?php echo lang("read_only"); ?></label>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <strong class="perm-head"><?php echo lang("can_access_invoices"); ?></strong>
                                <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "invoice_no",
                                        "name" => "invoice_permission",
                                        "value" => "",
                                            ), $invoice, ($invoice === "") ? true : false);
                                    ?>
                                    <label for="invoice_no"><?php echo lang("no"); ?> </label>
                                </div>
                                <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "invoice_yes",
                                        "name" => "invoice_permission",
                                        "value" => "all",
                                            ), $invoice, ($invoice === "all") ? true : false);
                                    ?>
                                    <label for="invoice_yes"><?php echo lang("yes"); ?></label>
                                </div>
                                <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "invoice_read_only",
                                        "name" => "invoice_permission",
                                        "value" => "read_only",
                                            ), $invoice, ($invoice === "read_only") ? true : false);
                                    ?>
                                    <label for="invoice_read_only"><?php echo lang("read_only"); ?></label>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <strong class="perm-head"><?php echo lang("can_access_estimates"); ?></strong>
                                <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "estimate_no",
                                        "name" => "estimate_permission",
                                        "value" => "",
                                            ), $estimate, ($estimate === "") ? true : false);
                                    ?>
                                    <label for="estimate_no"><?php echo lang("no"); ?> </label>
                                </div>
                                <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "estimate_yes",
                                        "name" => "estimate_permission",
                                        "value" => "all",
                                            ), $estimate, ($estimate === "all") ? true : false);
                                    ?>
                                    <label for="estimate_yes"><?php echo lang("yes"); ?></label>
                                </div>
                            </div>
                        </div>

                            </ul>
                        </div>
                    </ul>
                </li>
                <!-- CSS -->
                <li>
                    <ul class="nav nav-tabs vertical settings p15" role="tablist">
                        <div class="clearfix settings-anchor collapsed" data-toggle="collapse" data-target="#roles-tab-module_css" aria-expanded="true">
                            <?php echo lang("module_css"); ?><span class="pull-right"><i class="fa fa-plus-square-o"></i></span>
                        </div>
                        <div id="roles-tab-module_css" class="collapse ">
                            <ul class="list-group help-catagory">
                                <div>
                                    <?php
                                    echo form_checkbox("module_css", "1", $module_css ? true : false, "id='module_css'");
                                    ?>
                                    <label for="module_css"><?php echo lang("module_enable"); ?></label>
                                </div>

                                <strong class="perm-head"><?php echo lang("can_manage_help_and_knowledge_base"); ?></strong>
                                <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "help_no",
                                        "name" => "help_and_knowledge_base",
                                        "value" => "",
                                            ), $help_and_knowledge_base, ($help_and_knowledge_base === "") ? true : false);
                                    ?>
                                    <label for="help_no"><?php echo lang("no"); ?> </label>
                                </div>
                                <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "help_yes",
                                        "name" => "help_and_knowledge_base",
                                        "value" => "all",
                                            ), $help_and_knowledge_base, ($help_and_knowledge_base === "all") ? true : false);
                                    ?>
                                    <label for="help_yes"><?php echo lang("yes"); ?></label>
                                </div>
                                <strong class="perm-head"><?php echo lang("can_access_tickets"); ?></strong>
                                <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "ticket_permission_no",
                                        "name" => "ticket_permission",
                                        "value" => "",
                                        "class" => "ticket_permission toggle_specific",
                                            ), $ticket, ($ticket === "") ? true : false);
                                    ?>
                                    <label for="ticket_permission_no"><?php echo lang("no"); ?> </label>
                                </div>
                                <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "ticket_permission_all",
                                        "name" => "ticket_permission",
                                        "value" => "all",
                                        "class" => "ticket_permission toggle_specific",
                                            ), $ticket, ($ticket === "all") ? true : false);
                                    ?>
                                    <label for="ticket_permission_all"><?php echo lang("yes_all_tickets"); ?></label>
                                </div>
                                <div class="form-group">
                                    <?php
                                    echo form_radio(array(
                                        "id" => "ticket_permission_specific",
                                        "name" => "ticket_permission",
                                        "value" => "specific",
                                        "class" => "ticket_permission toggle_specific",
                                            ), $ticket, ($ticket === "specific") ? true : false);
                                    ?>
                                    <label for="ticket_permission_specific"><?php echo lang("yes_specific_ticket_types"); ?>:</label>
                                    <div class="specific_dropdown">
                                        <input type="text" value="<?php echo $ticket_specific; ?>" name="ticket_permission_specific" id="ticket_types_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_ticket_types'); ?>"  />
                                    </div>
                                </div>
                            </ul>
                        </div>
                    </ul>
                </li>
                <!-- PMS -->
                <li>
                    <ul class="nav nav-tabs vertical settings p15" role="tablist">
                        <div class="clearfix settings-anchor collapsed" data-toggle="collapse" data-target="#roles-tab-module_pms" aria-expanded="true">
                            <?php echo lang("module_pms"); ?><span class="pull-right"><i class="fa fa-plus-square-o"></i></span>
                        </div>
                        <div id="roles-tab-module_pms" class="collapse ">
                            <ul class="list-group help-catagory">
                                <div>
                                    <?php
                                    echo form_checkbox("module_pms", "1", $module_pms ? true : false, "id='module_pms'");
                                    ?>
                                    <label for="module_pms"><?php echo lang("module_enable"); ?></label>
                                </div>
                                <strong class="perm-head"><?php echo lang("can_manage_team_members_project_timesheet"); ?></strong>
                                <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "timesheet_manage_permission_no",
                                        "name" => "timesheet_manage_permission",
                                        "value" => "",
                                        "class" => "timesheet_manage_permission toggle_specific",
                                            ), $timesheet_manage_permission, ($timesheet_manage_permission === "") ? true : false);
                                    ?>
                                    <label for="timesheet_manage_permission_no"><?php echo lang("no"); ?> </label>
                                </div>
                                <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "timesheet_manage_permission_all",
                                        "name" => "timesheet_manage_permission",
                                        "value" => "all",
                                        "class" => "timesheet_manage_permission toggle_specific",
                                            ), $timesheet_manage_permission, ($timesheet_manage_permission === "all") ? true : false);
                                    ?>
                                    <label for="timesheet_manage_permission_all"><?php echo lang("yes_all_members"); ?></label>
                                </div>
                                <div class="form-group">
                                    <?php
                                    echo form_radio(array(
                                        "id" => "timesheet_manage_permission_specific",
                                        "name" => "timesheet_manage_permission",
                                        "value" => "specific",
                                        "class" => "timesheet_manage_permission toggle_specific",
                                            ), $timesheet_manage_permission, ($timesheet_manage_permission === "specific") ? true : false);
                                    ?>
                                    <label for="timesheet_manage_permission_specific"><?php echo lang("yes_specific_members_or_teams"); ?>:</label>
                                    <div class="specific_dropdown">
                                        <input type="text" value="<?php echo $timesheet_manage_permission_specific; ?>" name="timesheet_manage_permission_specific" id="timesheet_manage_permission_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />
                                    </div>
                                </div>
                                <div>
                                    <?php
                                    echo form_checkbox("can_manage_all_projects", "1", $can_manage_all_projects ? true : false, "id='can_manage_all_projects'");
                                    ?>
                                    <label for="can_manage_all_projects"><?php echo lang("can_manage_all_projects"); ?></label>
                                </div>
                                <div>
                                    <?php
                                    echo form_checkbox("can_create_projects", "1", $can_create_projects ? true : false, "id='can_create_projects'");
                                    ?>
                                    <label for="can_create_projects"><?php echo lang("can_create_projects"); ?></label>
                                </div>
                                <div>
                                    <?php
                                    echo form_checkbox("can_edit_projects", "1", $can_edit_projects ? true : false, "id='can_edit_projects'");
                                    ?>
                                    <label for="can_edit_projects"><?php echo lang("can_edit_projects"); ?></label>
                                </div>
                                <div>
                                    <?php
                                    echo form_checkbox("can_delete_projects", "1", $can_delete_projects ? true : false, "id='can_delete_projects'");
                                    ?>
                                    <label for="can_delete_projects"><?php echo lang("can_delete_projects"); ?></label>
                                </div>
                                <div>
                                    <?php
                                    echo form_checkbox("can_add_remove_project_members", "1", $can_add_remove_project_members ? true : false, "id='can_add_remove_project_members'");
                                    ?>
                                    <label for="can_add_remove_project_members"><?php echo lang("can_add_remove_project_members"); ?></label>
                                </div>
                                <div>
                                    <?php
                                    echo form_checkbox("can_create_tasks", "1", $can_create_tasks ? true : false, "id='can_create_tasks'");
                                    ?>
                                    <label for="can_create_tasks"><?php echo lang("can_create_tasks"); ?></label>
                                </div>
                                <div>
                                    <?php
                                    echo form_checkbox("can_edit_tasks", "1", $can_edit_tasks ? true : false, "id='can_edit_tasks'");
                                    ?>
                                    <label for="can_edit_tasks"><?php echo lang("can_edit_tasks"); ?></label>
                                </div>
                                <div>
                                    <?php
                                    echo form_checkbox("can_delete_tasks", "1", $can_delete_tasks ? true : false, "id='can_delete_tasks'");
                                    ?>
                                    <label for="can_delete_tasks"><?php echo lang("can_delete_tasks"); ?></label>
                                </div>
                                <div>
                                    <?php
                                    echo form_checkbox("can_comment_on_tasks", "1", $can_comment_on_tasks ? true : false, "id='can_comment_on_tasks'");
                                    ?>
                                    <label for="can_comment_on_tasks"><?php echo lang("can_comment_on_tasks"); ?></label>
                                </div>
                                <div>
                                    <?php
                                    echo form_checkbox("show_assigned_tasks_only", "1", $show_assigned_tasks_only ? true : false, "id='show_assigned_tasks_only'");
                                    ?>
                                    <label for="show_assigned_tasks_only"><?php echo lang("show_assigned_tasks_only"); ?></label>
                                </div>
                                <div>
                                    <?php
                                    echo form_checkbox("can_update_only_assigned_tasks_status", "1", $can_update_only_assigned_tasks_status ? true : false, "id='can_update_only_assigned_tasks_status'");
                                    ?>
                                    <label for="can_update_only_assigned_tasks_status"><?php echo lang("can_update_only_assigned_tasks_status"); ?></label>
                                </div>
                                <div>
                                    <?php
                                    echo form_checkbox("can_create_milestones", "1", $can_create_milestones ? true : false, "id='can_create_milestones'");
                                    ?>
                                    <label for="can_create_milestones"><?php echo lang("can_create_milestones"); ?></label>
                                </div>
                                <div>
                                    <?php
                                    echo form_checkbox("can_edit_milestones", "1", $can_edit_milestones ? true : false, "id='can_edit_milestones'");
                                    ?>
                                    <label for="can_edit_milestones"><?php echo lang("can_edit_milestones"); ?></label>
                                </div>
                                <div>
                                    <?php
                                    echo form_checkbox("can_delete_milestones", "1", $can_delete_milestones ? true : false, "id='can_delete_milestones'");
                                    ?>
                                    <label for="can_delete_milestones"><?php echo lang("can_delete_milestones"); ?></label>
                                </div>

                                <div>
                                    <?php
                                    echo form_checkbox("can_delete_files", "1", $can_delete_files ? true : false, "id='can_delete_files'");
                                    ?>
                                    <label for="can_delete_files"><?php echo lang("can_delete_files"); ?></label>
                                </div>
                            </ul>
                        </div>
                    </ul>
                </li>               
                <!-- AMS -->
                <li>
                    <ul class="nav nav-tabs vertical settings p15" role="tablist">
                        <div class="clearfix settings-anchor collapsed" data-toggle="collapse" data-target="#roles-tab-module_ams" aria-expanded="true">
                            <?php echo lang("module_ams"); ?><span class="pull-right"><i class="fa fa-plus-square-o"></i></span>
                        </div>
                        <div id="roles-tab-module_ams" class="collapse ">
                            <ul class="list-group help-catagory">
                                <div>
                                    <?php
                                    echo form_checkbox("module_ams", "1", $module_ams ? true : false, "id='module_ams'");
                                    ?>
                                    <label for="module_ams"><?php echo lang("module_enable"); ?></label>
                                </div>
                            </ul>
                        </div>
                    </ul>
                </li>
                <!-- LDS -->
                <li>
                    <ul class="nav nav-tabs vertical settings p15" role="tablist">
                        <div class="clearfix settings-anchor collapsed" data-toggle="collapse" data-target="#roles-tab-module_lds" aria-expanded="true">
                            <?php echo lang("module_lds"); ?><span class="pull-right"><i class="fa fa-plus-square-o"></i></span>
                        </div>
                        <div id="roles-tab-module_lds" class="collapse ">
                            <ul class="list-group help-catagory">
                                <div>
                                    <?php
                                    echo form_checkbox("module_lds", "1", $module_lds ? true : false, "id='module_lds'");
                                    ?>
                                    <label for="module_lds"><?php echo lang("module_enable"); ?></label>
                                </div>
                            </ul>
                        </div>
                    </ul>
                </li>
                <!-- MES -->
                <li>
                    <ul class="nav nav-tabs vertical settings p15" role="tablist">
                        <div class="clearfix settings-anchor collapsed" data-toggle="collapse" data-target="#roles-tab-module_mes" aria-expanded="true">
                            <?php echo lang("module_mes"); ?><span class="pull-right"><i class="fa fa-plus-square-o"></i></span>
                        </div>
                        <div id="roles-tab-module_mes" class="collapse ">
                            <ul class="list-group help-catagory">
                                <div>
                                    <?php
                                    echo form_checkbox("module_mes", "1", $module_mes ? true : false, "id='module_mes'");
                                    ?>
                                    <label for="module_mes"><?php echo lang("module_enable"); ?></label>
                                </div>
                            </ul>
                        </div>
                    </ul>
                </li>

            </ul>

        </div>
        <div class="panel-footer">
            <button type="submit" class="btn btn-primary mr10"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#permissions-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
            }
        });

        $("#leave_specific_dropdown, #attendance_specific_dropdown, #timesheet_manage_permission_specific_dropdown,  #team_member_update_permission_specific_dropdown, #message_permission_specific_dropdown").select2({
            multiple: true,
            formatResult: teamAndMemberSelect2Format,
            formatSelection: teamAndMemberSelect2Format,
            data: <?php echo ($members_and_teams_dropdown); ?>
        });

        $("#ticket_types_specific_dropdown").select2({
            multiple: true,
            data: <?php echo ($ticket_types_dropdown); ?>
        });

        $('[data-toggle="tooltip"]').tooltip();

        $(".toggle_specific").click(function () {
            toggle_specific_dropdown();
        });

        toggle_specific_dropdown();

        function toggle_specific_dropdown() {
            var selectors = [".leave_permission", ".attendance_permission", ".timesheet_manage_permission", ".team_member_update_permission", ".ticket_permission", ".message_permission_specific"];
            $.each(selectors, function (index, element) {
                var $element = $(element + ":checked");
                if ((element !== ".message_permission_specific" && $element.val() === "specific") || (element === ".message_permission_specific" && $element.is(":checked") && !$("#message_permission_specific_area").hasClass("hide"))) {
                    $element.closest("li").find(".specific_dropdown").show().find("input").addClass("validate-hidden");
                } else {
                    //console.log($element.closest("li").find(".specific_dropdown"));
                    $(element).closest("li").find(".specific_dropdown").hide().find("input").removeClass("validate-hidden");
                }
            });

        }

        //show/hide message permission checkbox
        $("#message_permission_no").click(function () {
            if ($(this).is(":checked")) {
                $("#message_permission_specific_area").addClass("hide");
            } else {
                $("#message_permission_specific_area").removeClass("hide");
            }

            toggle_specific_dropdown();
        });
    });
</script>    