<div id="page-content" class="p20 clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "modules";
            $this->load->view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <?php echo form_open(get_uri("settings/save_module_settings"), array("id" => "module-settings-form", "class" => "general-form dashed-row", "role" => "form")); ?>
            <div class="panel">
                <div class="panel-default panel-heading">
                    <h4><?php echo lang("manage_modules"); ?></h4>
                    <div><?php echo lang("module_settings_instructions"); ?></div>
                </div>
                <div class="panel-body">

                    <div class="form-group">
                        <label for="module_timeline" class="col-md-2 col-xs-8 col-sm-4"><?php echo lang('timeline'); ?></label>
                        <div class="col-md-10 col-xs-4 col-sm-8">
                            <?php
                            echo form_checkbox("module_timeline", "1", get_setting("module_timeline") ? true : false, "id='module_timeline' class='ml15'");
                            ?>                       
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="module_event" class="col-md-2 col-xs-8 col-sm-4"><?php echo lang('event'); ?></label>
                        <div class="col-md-10 col-xs-4 col-sm-8">
                            <?php
                            echo form_checkbox("module_event", "1", get_setting("module_event") ? true : false, "id='module_event' class='ml15'");
                            ?>                       
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="module_todo" class="col-md-2 col-xs-8 col-sm-4"><?php echo lang('todo'); ?></label>
                        <div class="col-md-10 col-xs-4 col-sm-8">
                            <?php
                            echo form_checkbox("module_todo", "1", get_setting("module_todo") ? true : false, "id='module_todo' class='ml15'");
                            ?>                       
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="module_note" class="col-md-2 col-xs-8 col-sm-4"><?php echo lang('note'); ?></label>
                        <div class="col-md-10 col-xs-4 col-sm-8">
                            <?php
                            echo form_checkbox("module_note", "1", get_setting("module_note") ? true : false, "id='module_note' class='ml15'");
                            ?>                       
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="module_message" class="col-md-2 col-xs-8 col-sm-4"><?php echo lang('message'); ?></label>
                        <div class="col-md-10 col-xs-4 col-sm-8">
                            <?php
                            echo form_checkbox("module_message", "1", get_setting("module_message") ? true : false, "id='module_message' class='ml15'");
                            ?>                       
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="module_chat" class="col-md-2 col-xs-8 col-sm-4"><?php echo lang('chat'); ?></label>
                        <div class="col-md-10 col-xs-4 col-sm-8">
                            <?php
                            echo form_checkbox("module_chat", "1", get_setting("module_chat") ? true : false, "id='module_chat' class='ml15'");
                            ?>                       
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="module_announcement" class="col-md-2 col-xs-8 col-sm-4"><?php echo lang('announcement'); ?></label>
                        <div class="col-md-10 col-xs-4 col-sm-8">
                            <?php
                            echo form_checkbox("module_announcement", "1", get_setting("module_announcement") ? true : false, "id='module_announcement' class='ml15'");
                            ?>                       
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="module_hrm" class="col-md-2 col-xs-8 col-sm-4"><?php echo lang('module_hrm'); ?></label>
                        <div class="col-md-10 col-xs-4 col-sm-8">
                            <?php
                            echo form_checkbox("module_hrm", "1", get_setting("module_hrm") ? true : false, "id='module_hrm' class='ml15'");
                            ?>                       
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_hrm_department", "1", get_setting("module_hrm_department") ? true : false, "id='module_hrm_department' class='ml15'");
                            ?>   
                            <label for="module_hrm_department"><?php echo lang('department'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_hrm_employee", "1", get_setting("module_hrm_employee") ? true : false, "id='module_hrm_employee' class='ml15'");
                            ?>   
                            <label for="module_hrm_employee"><?php echo lang('submenu_hrm_employee'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_attendance", "1", get_setting("module_attendance") ? true : false, "id='module_attendance' class='ml15'");
                            ?>   
                            <label for="module_attendance"><?php echo lang('attendance'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_hrm_disciplinary", "1", get_setting("module_hrm_disciplinary") ? true : false, "id='module_hrm_disciplinary' class='ml15'");
                            ?>   
                            <label for="module_hrm_disciplinary"><?php echo lang('disciplinary'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_leave", "1", get_setting("module_leave") ? true : false, "id='module_leave' class='ml15'");
                            ?>   
                            <label for="module_leave"><?php echo lang('leave'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_hrm_holidays", "1", get_setting("module_hrm_holidays") ? true : false, "id='module_hrm_holidays' class='ml15'");
                            ?>   
                            <label for="module_hrm_holidays"><?php echo lang('holidays'); ?></label>                    
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="module_fas" class="col-md-2 col-xs-8 col-sm-4"><?php echo lang('module_fas'); ?></label>
                        <div class="col-md-10 col-xs-4 col-sm-8">
                            <?php
                            echo form_checkbox("module_fas", "1", get_setting("module_fas") ? true : false, "id='module_fas' class='ml15'");
                            ?>                       
                        </div>
                    </div> 
                    <div class="form-group">
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_fas_summary", "1", get_setting("module_fas_summary") ? true : false, "id='module_fas_summary' class='ml15'");
                            ?>   
                            <label for="module_fas_summary"><?php echo lang('summary'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_fas_payments", "1", get_setting("module_fas_payments") ? true : false, "id='module_fas_payments' class='ml15'");
                            ?>   
                            <label for="module_fas_payments"><?php echo lang('payments'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_expense", "1", get_setting("module_expense") ? true : false, "id='module_expense' class='ml15'");
                            ?>   
                            <label for="module_expense"><?php echo lang('expense'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_fas_returns", "1", get_setting("module_fas_returns") ? true : false, "id='module_fas_returns' class='ml15'");
                            ?>   
                            <label for="module_fas_returns"><?php echo lang('submenu_fas_returns'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_fas_contributions", "1", get_setting("module_fas_contributions") ? true : false, "id='module_fas_contributions' class='ml15'");
                            ?>   
                            <label for="module_fas_contributions"><?php echo lang('contributions'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_fas_incentives", "1", get_setting("module_fas_incentives") ? true : false, "id='module_fas_incentives' class='ml15'");
                            ?>   
                            <label for="module_fas_incentives"><?php echo lang('incentives'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_fas_payroll", "1", get_setting("module_fas_payroll") ? true : false, "id='module_fas_payroll' class='ml15'");
                            ?>   
                            <label for="module_fas_payroll"><?php echo lang('payroll'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_fas_accounts", "1", get_setting("module_fas_accounts") ? true : false, "id='module_fas_accounts' class='ml15'");
                            ?>   
                            <label for="module_fas_accounts"><?php echo lang('accounts'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_fas_transfer", "1", get_setting("module_fas_transfer") ? true : false, "id='module_fas_transfer' class='ml15'");
                            ?>   
                            <label for="module_fas_transfer"><?php echo lang('transfers'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_fas_balancesheet", "1", get_setting("module_fas_balancesheet") ? true : false, "id='module_fas_balancesheet' class='ml15'");
                            ?>   
                            <label for="module_fas_balancesheet"><?php echo lang('submenu_fas_balancesheet'); ?></label>                    
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="module_pid" class="col-md-2 col-xs-8 col-sm-4"><?php echo lang('module_pid'); ?></label>
                        <div class="col-md-10 col-xs-4 col-sm-8">
                            <?php
                            echo form_checkbox("module_pid", "1", get_setting("module_pid") ? true : false, "id='module_pid' class='ml15'");
                            ?>                       
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_pid_productions", "1", get_setting("module_pid_productions") ? true : false, "id='module_pid_productions' class='ml15'");
                            ?>   
                            <label for="module_pid_productions"><?php echo lang('productions'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_pid_billofmaterials", "1", get_setting("module_pid_billofmaterials") ? true : false, "id='module_pid_billofmaterials' class='ml15'");
                            ?>   
                            <label for="module_pid_billofmaterials"><?php echo lang('bill_of_materials'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_pid_rawmaterials", "1", get_setting("module_pid_rawmaterials") ? true : false, "id='module_pid_rawmaterials' class='ml15'");
                            ?>   
                            <label for="module_pid_rawmaterials"><?php echo lang('materials'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_pid_inventory", "1", get_setting("module_pid_inventory") ? true : false, "id='module_pid_inventory' class='ml15'");
                            ?>   
                            <label for="module_pid_inventory"><?php echo lang('inventory'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_pid_products", "1", get_setting("module_pid_products") ? true : false, "id='module_pid_products' class='ml15'");
                            ?>   
                            <label for="module_pid_products"><?php echo lang('submenu_pid_items'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_pid_purchases", "1", get_setting("module_pid_purchases") ? true : false, "id='module_pid_purchases' class='ml15'");
                            ?>   
                            <label for="module_pid_purchases"><?php echo lang('submenu_pid_purchases'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_pid_supplier", "1", get_setting("module_pid_supplier") ? true : false, "id='module_pid_supplier' class='ml15'");
                            ?>   
                            <label for="module_pid_supplier"><?php echo lang('submenu_pid_supplier'); ?></label>                    
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="module_mcm" class="col-md-2 col-xs-8 col-sm-4"><?php echo lang('module_mcm'); ?></label>
                        <div class="col-md-10 col-xs-4 col-sm-8">
                            <?php
                            echo form_checkbox("module_mcm", "1", get_setting("module_mcm") ? true : false, "id='module_mcm' class='ml15'");
                            ?>                       
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-1">
                            <?php
                            echo form_checkbox("module_lead", "1", get_setting("module_lead") ? true : false, "id='module_lead' class='ml15'");
                            ?>                       
                        </div>
                        <label for="module_lead" class="col-md-3"><?php echo lang('lead'); ?></label>
                    </div>

                    <div class="form-group">
                        <label for="module_lms" class="col-md-2 col-xs-8 col-sm-4"><?php echo lang('module_lms'); ?></label>
                        <div class="col-md-10 col-xs-4 col-sm-8">
                            <?php
                            echo form_checkbox("module_lms", "1", get_setting("module_lms") ? true : false, "id='module_lms' class='ml15'");
                            ?>                       
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_lms_delivery", "1", get_setting("module_lms_delivery") ? true : false, "id='module_lms_delivery' class='ml15'");
                            ?>   
                            <label for="module_lms_delivery"><?php echo lang('delivery'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_lms_warehouse", "1", get_setting("module_lms_warehouse") ? true : false, "id='module_lms_warehouse' class='ml15'");
                            ?>   
                            <label for="module_lms_warehouse"><?php echo lang('warehouse'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_lms_transfer", "1", get_setting("module_lms_transfer") ? true : false, "id='module_lms_transfer' class='ml15'");
                            ?>   
                            <label for="module_lms_transfer"><?php echo lang('transfers'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_lms_vehicles", "1", get_setting("module_lms_vehicles") ? true : false, "id='module_lms_vehicles' class='ml15'");
                            ?>   
                            <label for="module_lms_vehicles"><?php echo lang('vehicles'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_lms_driver", "1", get_setting("module_lms_driver") ? true : false, "id='module_lms_driver' class='ml15'");
                            ?>   
                            <label for="module_lms_driver"><?php echo lang('driver'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_lms_consumer", "1", get_setting("module_lms_consumer") ? true : false, "id='module_lms_consumer' class='ml15'");
                            ?>   
                            <label for="module_lms_consumer"><?php echo lang('consumer'); ?></label>                    
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="module_sms" class="col-md-2 col-xs-8 col-sm-4"><?php echo lang('module_sms'); ?></label>
                        <div class="col-md-10 col-xs-4 col-sm-8">
                            <?php
                            echo form_checkbox("module_sms", "1", get_setting("module_sms") ? true : false, "id='module_sms' class='ml15'");
                            ?>                       
                        </div>
                    </div>  
                    <div class="form-group">
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_sms_pos", "1", get_setting("module_sms_pos") ? true : false, "id='module_sms_pos' class='ml15'");
                            ?>   
                            <label for="module_sms_pos"><?php echo lang('submenu_sms_pointofsale'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_sms_giftcard", "1", get_setting("module_sms_giftcard") ? true : false, "id='module_sms_giftcard' class='ml15'");
                            ?>   
                            <label for="module_sms_giftcard"><?php echo lang('submenu_sms_giftcard'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_sms_coupons", "1", get_setting("module_sms_coupons") ? true : false, "id='module_sms_coupons' class='ml15'");
                            ?>   
                            <label for="module_sms_coupons"><?php echo lang('submenu_sms_coupons'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_sms_sales_matrix", "1", get_setting("module_sms_sales_matrix") ? true : false, "id='module_sms_sales_matrix' class='ml15'");
                            ?>   
                            <label for="module_sms_sales_matrix"><?php echo lang('sales_matrix'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_estimate", "1", get_setting("module_estimate") ? true : false, "id='module_estimate' class='ml15'");
                            ?>   
                            <label for="module_estimate"><?php echo lang('estimate'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_estimate_request", "1", get_setting("module_estimate_request") ? true : false, "id='module_estimate_request' class='ml15'");
                            ?>   
                            <label for="module_estimate_request"><?php echo lang('estimate_request'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_invoice", "1", get_setting("module_invoice") ? true : false, "id='module_invoice' class='ml15'");
                            ?>   
                            <label for="module_invoice"><?php echo lang('invoice'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_sms_customers", "1", get_setting("module_sms_customers") ? true : false, "id='module_sms_customers' class='ml15'");
                            ?>   
                            <label for="module_sms_customers"><?php echo lang('customers'); ?></label>                    
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="module_ams" class="col-md-2 col-xs-8 col-sm-4"><?php echo lang('module_ams'); ?></label>
                        <div class="col-md-10 col-xs-4 col-sm-8">
                            <?php
                            echo form_checkbox("module_ams", "1", get_setting("module_ams") ? true : false, "id='module_ams' class='ml15'");
                            ?>                       
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_assets", "1", get_setting("module_assets") ? true : false, "id='module_assets' class='ml15'");
                            ?>   
                            <label for="module_assets"><?php echo lang('submenu_ams_assets'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_ams_category", "1", get_setting("module_ams_category") ? true : false, "id='module_ams_category' class='ml15'");
                            ?>   
                            <label for="module_ams_category"><?php echo lang('submenu_ams_category'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_ams_location", "1", get_setting("module_ams_location") ? true : false, "id='module_ams_location' class='ml15'");
                            ?>   
                            <label for="module_ams_location"><?php echo lang('submenu_ams_location'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_vendors", "1", get_setting("module_vendors") ? true : false, "id='module_vendors' class='ml15'");
                            ?>   
                            <label for="module_vendors"><?php echo lang('submenu_ams_vendors'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_brands", "1", get_setting("module_brands") ? true : false, "id='module_brands' class='ml15'");
                            ?>   
                            <label for="module_brands"><?php echo lang('submenu_ams_maker'); ?></label>                    
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="module_pms" class="col-md-2 col-xs-8 col-sm-4"><?php echo lang('module_pms'); ?></label>
                        <div class="col-md-10 col-xs-4 col-sm-8">
                            <?php
                            echo form_checkbox("module_pms", "1", get_setting("module_pms") ? true : false, "id='module_pms' class='ml15'");
                            ?>                       
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_allprojects", "1", get_setting("module_allprojects") ? true : false, "id='module_allprojects' class='ml15'");
                            ?>   
                            <label for="module_allprojects"><?php echo lang('submenu_pms_all_projects'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_mytask", "1", get_setting("module_mytask") ? true : false, "id='module_mytask' class='ml15'");
                            ?>   
                            <label for="module_mytask"><?php echo lang('submenu_pms_my_tasks'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_gantt", "1", get_setting("module_gantt") ? true : false, "id='module_gantt' class='ml15'");
                            ?>   
                            <label for="module_gantt"><?php echo lang('submenu_pms_view_gantts'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_project_timesheet", "1", get_setting("module_project_timesheet") ? true : false, "id='module_project_timesheet' class='ml15'");
                            ?>   
                            <label for="module_project_timesheet"><?php echo lang('submenu_pms_timesheets'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_clients", "1", get_setting("module_clients") ? true : false, "id='module_clients' class='ml15'");
                            ?>   
                            <label for="module_clients"><?php echo lang('submenu_pms_clients'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_services", "1", get_setting("module_services") ? true : false, "id='module_services' class='ml15'");
                            ?>   
                            <label for="module_services"><?php echo lang('submenu_pms_services'); ?></label>                    
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="module_hts" class="col-md-2 col-xs-8 col-sm-4"><?php echo lang('module_hts'); ?></label>
                        <div class="col-md-10 col-xs-4 col-sm-8">
                            <?php
                            echo form_checkbox("module_hts", "1", get_setting("module_hts") ? true : false, "id='module_hts' class='ml15'");
                            ?>                       
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_ticket", "1", get_setting("module_ticket") ? true : false, "id='module_ticket' class='ml15'");
                            ?>   
                            <label for="module_ticket"><?php echo lang('ticket'); ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_help", "1", get_setting("module_help") ? true : false, "id='module_help' class='ml15'");
                            ?>   
                            <label for="module_help"><?php echo lang('help') . " (" . lang("team_members") . ")"; ?></label>                    
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo form_checkbox("module_knowledge_base", "1", get_setting("module_knowledge_base") ? true : false, "id='module_knowledge_base' class='ml15'");
                            ?> 
                            <label for="module_knowledge_base"><?php echo lang('knowledge_base') . " (" . lang("public") . ")"; ?></label>                      
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
        $("#module-settings-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
                location.reload();
            }
        });
    });
</script>