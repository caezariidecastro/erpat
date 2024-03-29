<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Left_menu {

    private $ci = null;

    public function __construct() {
        $this->ci = & get_instance();
    }

    function get_available_items($type = "default") {
        $items_array = $this->_prepare_sidebar_menu_items($type);

        //remove used items
        $default_left_menu_items = $this->_get_left_menu_from_setting($type);

        foreach ($default_left_menu_items as $default_item) {
            unset($items_array[get_array_value($default_item, "name")]);
        }

        $items = "";
        foreach ($items_array as $item) {
            $items .= $this->_get_item_data($item, true);
        }

        return $items ? $items : "<span class='text-off empty-area-text'>" . lang('no_more_items_available') . "</span>";
    }

    private function _prepare_sidebar_menu_items($type = "") {
        $final_items_array = array();
        $items_array = $this->_get_sidebar_menu_items($type);

        foreach ($items_array as $item) {
            $main_menu_name = get_array_value($item, "name");

            if (isset($item["submenu"])) {
                //first add this menu removing the submenus
                if ($main_menu_name !== "finance" && $main_menu_name !== "help_and_support") {
                    $main_menu = $item;
                    unset($main_menu["submenu"]);
                    $final_items_array[$main_menu_name] = $main_menu;
                }

                $submenu = get_array_value($item, "submenu");
                foreach ($submenu as $key => $s_menu) {
                    //prepare help items differently
                    if ($main_menu_name == "help_and_support") {
                        $s_menu = $this->_make_customized_sub_menu_for_help_and_support($key, $s_menu);
                    }

                    if (get_array_value($s_menu, "class")) {
                        $final_items_array[get_array_value($s_menu, "name")] = $s_menu;
                    }
                }
            } else {
                $final_items_array[$main_menu_name] = $item;
            }
        }

        //add todo
        $final_items_array["todo"] = array("name" => "todo", "url" => "todo", "class" => "fa-check-square-o");

        return $final_items_array;
    }

    private function _make_customized_sub_menu_for_help_and_support($key, $s_menu) {
        if ($key == 1) {
            $s_menu["name"] = "help_articles";
        } else if ($key == 2) {
            $s_menu["name"] = "help_categories";
        } else if ($key == 4) {
            $s_menu["name"] = "knowledge_base_articles";
        } else if ($key == 5) {
            $s_menu["name"] = "knowledge_base_categories";
        }

        return $s_menu;
    }

    private function _get_left_menu_from_setting_for_rander($is_preview = false, $type = "default") {
        $user_left_menu = get_setting("user_" . $this->ci->login_user->id . "_left_menu");
        $default_left_menu = ($type == "client_default" || $this->ci->login_user->user_type == "client") ? get_setting("default_client_left_menu") : get_setting("default_left_menu");
        $custom_left_menu = "";

        //for preview, show the edit type preview
        if ($is_preview) {
            $custom_left_menu = $default_left_menu; //default preview
            if ($type == "user") {
                $custom_left_menu = $user_left_menu ? $user_left_menu : $default_left_menu; //user level preview
            }
        } else {
            $custom_left_menu = $user_left_menu ? $user_left_menu : $default_left_menu; //page rander
        }

        return $custom_left_menu ? json_decode(json_encode(@unserialize($custom_left_menu)), true) : array();
    }

    private function _get_left_menu_from_setting($type) {
        if ($type == "client_default") {
            $default_left_menu = get_setting("default_client_left_menu");
        } else if ($type == "user") {
            $default_left_menu = get_setting("user_" . $this->ci->login_user->id . "_left_menu");
        } else {
            $default_left_menu = get_setting("default_left_menu");
        }

        return $default_left_menu ? json_decode(json_encode(@unserialize($default_left_menu)), true) : array();
    }

    public function _get_item_data($item, $is_default_item = false) {
        $name = get_array_value($item, "name");
        $url = get_array_value($item, "url");
        $is_sub_menu = get_array_value($item, "is_sub_menu");
        $open_in_new_tab = get_array_value($item, "open_in_new_tab");
        $icon = get_array_value($item, "icon");

        if ($name) {
            $sub_menu_class = "";
            if ($is_sub_menu) {
                $sub_menu_class = "ml20";
            }

            $extra_attr = "";
            $edit_button = "";
            $name_lang = "";
            if ($is_default_item || !$url) {
                $name_lang = lang($name);
            } else {
                //custom menu item
                $extra_attr = "data-url='$url' data-icon='$icon' data-custom_menu_item_id='" . rand(2000, 400000000) . "' data-open_in_new_tab='$open_in_new_tab'";
                $name_lang = $name;
                $edit_button = modal_anchor(get_uri("left_menus/add_menu_item_modal_form"), "<i class='fa fa-pencil'></i> ", array("title" => lang('edit'), "class" => "custom-menu-edit-button", "data-post-title" => $name, "data-post-url" => $url, "data-post-is_sub_menu" => $is_sub_menu, "data-post-icon" => $icon, "data-post-open_in_new_tab" => $open_in_new_tab));
            }

            return "<div data-value='" . $name . "' $extra_attr class='left-menu-item mb5 widget clearfix p10 bg-white $sub_menu_class'>
                        <span class='pull-left text-left'><i class='fa fa-arrows text-off pr5'></i> " . $name_lang . "</span>
                        <span class='pull-right invisible'>
                            <i class='fa fa-level-down clickable make-sub-menu font-14' title='" . lang("make_previous_items_sub_menu") . "'></i>
                            $edit_button
                            <i class='fa fa-times text-danger clickable delete-left-menu-item font-14' title=" . lang("delete") . "></i>
                        </span>
                    </div>";
        }
    }

    function get_sortable_items($type = "default") {
        $items = "<div id='menu-item-list-2' class='js-left-menu-scrollbar add-column-drop text-center p15 menu-item-list sortable-items-container'>";

        $default_left_menu_items = $this->_get_left_menu_from_setting($type);
        if (count($default_left_menu_items)) {
            foreach ($default_left_menu_items as $item) {
                $items .= $this->_get_item_data($item);
            }
        } else {
            $items .= "<span class='text-off empty-area-text'>" . lang('drag_and_drop_items_here') . "</span>";
        }

        $items .= "</div>";

        return $items;
    }

    function rander_left_menu($is_preview = false, $type = "default") {
        $final_left_menu_items = array();
        $custom_left_menu_items = $this->_get_left_menu_from_setting_for_rander($is_preview, $type);

        if ($custom_left_menu_items) {
            $left_menu_items = $this->_prepare_sidebar_menu_items($type);

            $last_menu_item = ""; //store last menu item to the get the data on creating submenu
            $last_final_menu_item = ""; //store the last menu item of final left menu to add submenu to this item 

            foreach ($custom_left_menu_items as $key => $value) {
                $item_value_array = $this->_get_item_array_value($value, $left_menu_items);

                $is_sub_menu = get_array_value($value, "is_sub_menu");
                if ($is_sub_menu) {
                    //this is a sub menu, move it to it's parent item                        
                    //since the parent item is also a standalone menu item, make a submenu of that too
                    //but if any other menu item which haven't any submenu, added as a submenu of a menu item which have submenu, that won't be added

                    $parent_item_array = $this->_get_item_array_value(get_array_value($custom_left_menu_items, $last_menu_item), $left_menu_items);
                    if (!isset($parent_item_array["submenu"])) {
                        $final_left_menu_items[$last_final_menu_item]["submenu"][] = $parent_item_array;
                    }

                    //add this item
                    if($item_value_array) {
                        array_push($final_left_menu_items[$last_final_menu_item]["submenu"], $item_value_array);
                    }
                } else {
                    $final_left_menu_items[] = $item_value_array;
                    $last_menu_item = $key;
                    $last_final_menu_item = end($final_left_menu_items);
                    $last_final_menu_item = key($final_left_menu_items);
                }
            }
        }

        $view_data["show_devider"] = true;

        if (count($final_left_menu_items)) {
            $view_data["sidebar_menu"] = $final_left_menu_items;
            $view_data["show_devider"] = false;
        } else {
            $view_data["sidebar_menu"] = $this->_get_sidebar_menu_items($type);
        }

        $view_data["is_preview"] = $is_preview;
        return $this->ci->load->view("includes/left_menu", $view_data, true);
    }

    private function _get_item_array_value($data_array, $left_menu_items) {
        $name = get_array_value($data_array, "name");
        $url = get_array_value($data_array, "url");
        $icon = get_array_value($data_array, "icon");
        $open_in_new_tab = get_array_value($data_array, "open_in_new_tab");
        $item_value_array = array();

        if ($url) { //custom menu item
            $item_value_array = array("name" => $name, "url" => $url, "is_custom_menu_item" => true, "class" => "fa-$icon", "open_in_new_tab" => $open_in_new_tab);
        } else if (array_key_exists($name, $left_menu_items)) { //default menu items
            $item_value_array = get_array_value($left_menu_items, $name);
        }

        return $item_value_array;
    }

    private function _get_sidebar_menu_items($type = "") {

        $dashboard_menu = array("name" => "dashboard", "url" => "dashboard", "class" => "fa-desktop dashboard-menu");

        $selected_dashboard_id = get_setting("user_" . $this->ci->login_user->id . "_dashboard");
        if ($selected_dashboard_id) {
            $dashboard_menu = array("name" => "dashboard", "url" => "dashboard/view/" . $selected_dashboard_id, "class" => "fa-desktop dashboard-menu");
        }

        if ($this->ci->login_user->user_type == "staff" && $type !== "client_default") {

            $sidebar_menu = array("dashboard" => $dashboard_menu);

            $permissions = $this->ci->login_user->permissions;

            $access_expense = get_array_value($permissions, "expense");
            $access_invoice = get_array_value($permissions, "invoice");
            $access_client = get_array_value($permissions, "client");
            $access_lead = get_array_value($permissions, "lead");
            $access_leave = get_array_value($permissions, "leave");
            $access_estimate = get_array_value($permissions, "estimate");
            $access_items = ($this->ci->login_user->is_admin || $access_invoice || $access_estimate);

            $client_message_users = get_setting("client_message_users");
            $client_message_users_array = explode(",", $client_message_users);
            $access_messages = ($this->ci->login_user->is_admin || get_array_value($permissions, "message_permission") !== "" || in_array($this->ci->login_user->id, $client_message_users_array));

            if (module_enabled("module_timeline") == "1") {
                $sidebar_menu["timeline"] = array("name" => "timeline", "url" => "timeline", "class" => " fa-comments font-18");
            }

            if (module_enabled("module_announcement") == "1") {
                $sidebar_menu["announcements"] = array("name" => "announcements", "url" => "announcements", "class" => "fa-bullhorn");
            }

            if (module_enabled("module_todo") == "1") {
                $sidebar_menu["todo"] = array("name" => "todo", "url" => "todo", "class" => "fa-check-square-o font-16");
            }

            if (module_enabled("module_event") == "1") {
                $sidebar_menu["events"] = array("name" => "events", "url" => "events", "class" => "fa-calendar");
            }

            if (module_enabled("module_note") == "1") {
                $sidebar_menu["notes"] = array("name" => "notes", "url" => "notes", "class" => "fa-book font-16");
            }

            if (module_enabled("module_message") == "1" && $access_messages) {
                $sidebar_menu["messages"] = array("name" => "messages", "url" => "messages", "class" => "fa-envelope", "devider" => true, "badge" => count_unread_message(), "badge_class" => "badge-secondary");
            }

            // Start: Module permissions workaround

            // STAFFING
            
            $sidebar_menu["department"] = array("name" => "submenu_hrm_department", "url" => "hrs/department", "class" => "fa-circle");
            if (module_enabled("module_attendance") && current_has_permit("attendance")) {
                $sidebar_menu["attendance"] = array("name" => "submenu_hrm_attendance", "url" => "hrs/attendance", "class" => "fa-circle");
            }
            $sidebar_menu["disciplinary"] = array("name" => "submenu_hrm_disciplinary", "url" => "hrs/disciplinary", "class" => "fa-circle");
            if ($this->ci->login_user->is_admin || (module_enabled("module_leave") == "1" && $access_leave)) {
                $sidebar_menu["leaves"] = array("name" => "submenu_hrm_leaves", "url" => "hrs/leaves", "class" => "fa-circle");
            } else if (module_enabled("module_leave") == "1") {
                $sidebar_menu["leaves"] = array("name" => "submenu_hrm_leaves", "url" => "hrs/leaves/leave_info", "class" => "fa-circle");
            }
            $sidebar_menu["holidays"] = array("name" => "submenu_hrm_holidays", "url" => "hrs/holidays", "class" => "fa-circle");

            // STAFFING > Employee
            if( module_enabled("module_employee") && current_has_permit("staff") ) {
                $sidebar_menu["employee"] = array("name" => "employee", "url" => "Team_members", "class" => "fa-circle");
            }

            // STAFFING > Schedule
            if( module_enabled("module_schedule") && current_has_permit("schedule") ) {
                $sidebar_menu["schedule"] = array("name" => "submenu_hrm_schedule", "url" => "hrs/schedule", "class" => "fa-circle");
            }

            // SECURITY > ACCESS LOG
            if( module_enabled("module_access") && current_has_permit("access_logs") ) {
                $sidebar_menu["access_logs"] = array("name" => "access_logs", "url" => "access_logs", "class" => "fa-circle");
            }

            // End: Module permissions workaround

            //DISTRIBUTION
            if( module_enabled("module_warehouse") && current_has_permit("warehouse") ) {
                $sidebar_menu["warehouses"] = array("name" => "warehouses", "url" => "inventory/Warehouses", "class" => "fa-circle");
            }
            if( module_enabled("module_warehouse") && current_has_permit("warehouse") ) {
                $sidebar_menu["pallets"] = array("name" => "submenu_lms_pallets", "url" => "inventory/Pallets", "class" => "fa-circle");  
            }

            //FINANCE
            if( module_enabled("module_accounting_summary") && current_has_permit("accounting_summary") ) {
                $sidebar_menu["summary"] = array("name" => "submenu_fas_summary", "url" => "fas/summary", "class" => "fa-circle");
            }
            if( module_enabled("module_account") && current_has_permit("account") ) {
                $sidebar_menu["accounts"] = array("name" => "submenu_fas_accounts", "url" => "fas/accounts", "class" => "fa-circle");
            }
            if( module_enabled("module_payment") && current_has_permit("payment") ) {
                $sidebar_menu["payments"] = array("name" => "submenu_fas_payments", "url" => "fas/payments", "class" => "fa-circle");
            }
            if( module_enabled("module_expense") && current_has_permit("expense") ) {
                $sidebar_menu["expenses"] = array("name" => "submenu_fas_expenses", "url" => "fas/expenses", "class" => "fa-circle");
            }
            if( module_enabled("module_payroll") && current_has_permit("payroll") ) {
                $sidebar_menu["payrolls"] = array("name" => "payrolls", "url" => "Payrolls", "class" => "fa-circle");
            }
            if( module_enabled("module_loan") && current_has_permit("loan") ) {
                $sidebar_menu["loans"] = array("name" => "loans", "url" => "finance/Loans", "class" => "fa-circle");
            }
            if( current_has_permit("tax") ) {
                $sidebar_menu["taxes"] = array("name" => "taxes", "url" => "taxes", "class" => "fa-circle");
            }

            // PROCUREMENT
            if (module_enabled("module_purchase") && current_has_permit('purchase')) {
                $sidebar_menu["purchase-orders"] = array("name" => "submenu_pid_purchases", "url" => "mes/purchase-orders", "class" => "fa-circle");
            }
            if (module_enabled("module_return") && current_has_permit('return')) {
                $sidebar_menu["purchase-returns"] = array("name" => "submenu_pid_returns", "url" => "mes/purchase-returns", "class" => "fa-circle");
            }
            if (module_enabled("module_supplier") && current_has_permit('supplier')) {
                $sidebar_menu["suppliers"] = array("name" => "submenu_pid_supplier", "url" => "mes/suppliers", "class" => "fa-circle");
            }

            // PRODUCTION
            if (module_enabled("module_productions") && current_has_permit('production')) {
                $sidebar_menu["manufacturing-orders"] = array("name" => "manufacturing_order", "url" => "production/ManufacturingOrders", "class" => "fa-circle");
            }
            if (module_enabled("module_billofmaterials") && current_has_permit('billofmaterial')) {
                $sidebar_menu["bill-of-materials"] = array("name" => "bill_of_materials", "url" => "production/BillOfMaterials", "class" => "fa-circle"); 
            }      
            if (module_enabled("module_unit") && current_has_permit('unit')) {
                $sidebar_menu["units"] = array("name" => "submenu_pid_units", "url" => "mes/units", "class" => "fa-circle"); 
            }    

            // LOGISTICS
            if (module_enabled("module_delivery") && current_has_permit('delivery')) {
                $sidebar_menu["deliveries"] = array("name" => "submenu_lms_delivery", "url" => "lds/deliveries", "class" => "fa-circle");
            }
            if (current_has_permit('item_transfer')) {
                $sidebar_menu["lds_transfers"] = array("name" => "submenu_lms_transfers", "url" => "lds/transfers", "class" => "fa-circle");
            }
            if (module_enabled("module_vehicle") && current_has_permit('vehicle')) {
                $sidebar_menu["vehicles"] = array("name" => "submenu_lms_vehicles", "url" => "lds/vehicles", "class" => "fa-circle");
            }
            if (module_enabled("module_driver") && current_has_permit('driver')) {
                $sidebar_menu["drivers"] = array("name" => "submenu_lms_drivers", "url" => "lds/drivers", "class" => "fa-circle");   
            }

            //SALES
            if (module_enabled("module_sales_summary") && current_has_permit('sales_summary')) {
                $sidebar_menu["sales-matrix"] = array("name" => "sales_matrix", "url" => "sales/Sales_matrix", "class" => "fa-circle");
            }
            if (module_enabled("module_invoice") && current_has_permit('invoice')) {
                $sidebar_menu["invoices"] = array("name" => "submenu_sms_invoices", "url" => "sales/Invoices", "class" => "fa-circle");
            }
            if (module_enabled("module_service") && current_has_permit('service')) {
                $sidebar_menu["services"] = array("name" => "submenu_sms_services", "url" => "sales/Services", "class" => "fa-circle");
            }
            if (module_enabled("module_product") && current_has_permit('product')) {
                $sidebar_menu["products"] = array("name" => "submenu_pid_products", "url" => "sales/ProductEntries", "class" => "fa-circle");
            }
            if (module_enabled("module_clients") && current_has_permit('client')) {
                $sidebar_menu["clients"] = array("name" => "clients", "url" => "sales/Clients", "class" => "fa-circle");
            }
            if (module_enabled("module_clients") && current_has_permit('client')) {
                $sidebar_menu["customers"] = array("name" => "submenu_sms_customers", "url" => "sales/Customers", "class" => "fa-circle");
            }
            if (module_enabled("module_stores") && current_has_permit('store')) {
                $sidebar_menu["stores"] = array("name" => "stores", "url" => "sales/Stores", "class" => "fa-circle");
            }

            //MARKETING
            if (module_enabled("module_lead") && current_has_permit('lead')) {
                $sidebar_menu["leads"] = array("name" => "submenu_mcs_leads", "url" => "mcs/leads", "class" => "fa-circle");
            }
            if (module_enabled("module_lead") && current_has_permit('lead')) {
                $sidebar_menu["lead_status"] = array("name" => "submenu_mcs_status", "url" => "mcs/lead_status", "class" => "fa-circle");
            }
            if (module_enabled("module_lead") && current_has_permit('lead')) {
                $sidebar_menu["lead_source"] = array("name" => "submenu_mcs_source", "url" => "mcs/lead_source", "class" => "fa-circle");
            }
            if( module_enabled("module_estimate") && current_has_permit("estimate") ) {
                $sidebar_menu["estimates"] = array("name" => "estimates", "url" => "sales/Estimates", "class" => "fa-circle");
            } //module_estimate_request
            if( module_enabled("module_epass") && current_has_permit("event_epass") ) {
                $sidebar_menu["epass"] = array("name" => "epass", "url" => "mcs/epass", "class" => "fa-circle");
            }
            if( module_enabled("module_raffle") && current_has_permit("raffle_draw") ) {
                $sidebar_menu["module_raffle"] = array("name" => "raffle_draw", "url" => "raffle_draw", "class" => "fa-circle");
            }

            // ASSETS - GENERALIZED
            if (module_enabled("module_assets") && current_has_permit('asset')) {
                $sidebar_menu["entries"] = array("name" => "asset_entries", "url" => "ams/entries", "class" => "fa-circle");
            }
            if (module_enabled("module_asset_category") && current_has_permit('asset_category', false, true)) {
                $sidebar_menu["categories"] = array("name" => "asset_categories", "url" => "ams/categories", "class" => "fa-circle");
            }
            if (module_enabled("module_location") && current_has_permit('location')) {
                $sidebar_menu["locations"] = array("name" => "asset_location", "url" => "ams/locations", "class" => "fa-circle");
            }
            if (module_enabled("module_vendors") && current_has_permit('vendor')) {
                $sidebar_menu["vendors"] = array("name" => "asset_vendor", "url" => "ams/vendors", "class" => "fa-circle");
            }
            if (module_enabled("module_brands") && current_has_permit('brand')) {
                $sidebar_menu["brands"] = array("name" => "asset_brand", "url" => "ams/brands", "class" => "fa-circle");
            }

            // PLANNING
            if (module_enabled("module_allprojects") && current_has_permit('can_manage_all_projects')) {
                $sidebar_menu["all_projects"] = array("name" => "submenu_pms_all_projects", "url" => "pms/all_projects", "class" => "fa-circle");
            }

            if (module_enabled("module_mytask") && current_has_permit('can_manage_all_projects')) {
                $sidebar_menu["my_tasks"] = array("name" => "submenu_pms_my_tasks", "url" => "pms/my_tasks", "class" => "fa-circle");
            }

            if (module_enabled("module_gantt") && current_has_permit('can_manage_all_projects')) {
                $sidebar_menu["view_gantts"] = array("name" => "submenu_pms_view_gantts", "url" => "pms/view_gantts", "class" => "fa-circle");
            }

            if (module_enabled("module_project_timesheet") && current_has_permit('can_manage_all_projects')) {
                $sidebar_menu["timesheets"] = array("name" => "submenu_pms_timesheets", "url" => "pms/timesheets", "class" => "fa-circle");
            }

            // HELP CENTER

            if ( module_enabled("module_ticket") == "1" && current_has_permit('ticket') ) {

                $ticket_badge = 0;
                if ($this->ci->login_user->is_admin || $access_ticket === "all") {
                    $ticket_badge = count_new_tickets();
                } else if ($access_ticket === "specific") {
                    $specific_ticket_permission = get_array_value($permissions, "ticket_specific");
                    $ticket_badge = count_new_tickets($specific_ticket_permission);
                }

                $sidebar_menu["tickets"] = array("name" => "tickets", "url" => "tickets", "class" => "fa-circle", "badge" => $ticket_badge, "badge_class" => "badge-secondary");
            }

            if ( module_enabled("module_page") == "1" && current_has_permit('page') ) {
                $sidebar_menu["pages"] = array("name" => "pages", "url" => "pages", "class" => "fa-circle");
            }

            if ( module_enabled("module_help") == "1" && current_has_permit('help') ) {
                $sidebar_menu["help"] = array("name" => "help_page_title", "url" => "help", "class" => "fa-circle");
            }

            if ( module_enabled("module_knowledge_base") == "1" && current_has_permit('knowledge_base') ) {
                $sidebar_menu["knowledge_base"] = array("name" => "knowledge_base", "url" => "knowledge_base", "class" => "fa-circle");
            }

            if ($this->ci->login_user->is_admin) {
                $sidebar_menu["settings"] = array("name" => "settings", "url" => "settings/general", "class" => "fa-wrench");
            }
        } else {
            //client menu
            //get the array of hidden menu
            $hidden_menu = explode(",", get_setting("hidden_client_menus"));

            $sidebar_menu[] = $dashboard_menu;

            if (module_enabled("module_event") == "1" && !in_array("events", $hidden_menu)) {
                $sidebar_menu[] = array("name" => "events", "url" => "events", "class" => "fa-calendar");
            }

            //check message access settings for clients
            if (module_enabled("module_message") && get_setting("client_message_users")) {
                $sidebar_menu[] = array("name" => "messages", "url" => "messages", "class" => "fa-envelope", "badge" => count_unread_message());
            }

            if (!in_array("projects", $hidden_menu)) {
                $sidebar_menu[] = array("name" => "projects", "url" => "projects/all_projects", "class" => "fa fa-th-large");
            }


            if (module_enabled("module_estimate") && !in_array("estimates", $hidden_menu)) {
                $sidebar_menu[] = array("name" => "estimates", "url" => "sales/Estimates", "class" => "fa-file");
            }

            if (module_enabled("module_invoice") == "1") {
                if (!in_array("invoices", $hidden_menu)) {
                    $sidebar_menu[] = array("name" => "invoices", "url" => "sales/Invoices", "class" => "fa-file-text");
                }
                if (!in_array("payments", $hidden_menu)) {
                    $sidebar_menu[] = array("name" => "invoice_payments", "url" => "invoice_payments", "class" => "fa-money");
                }
            }

            if (module_enabled("module_ticket") == "1" && !in_array("tickets", $hidden_menu)) {
                $sidebar_menu[] = array("name" => "tickets", "url" => "tickets", "class" => "fa-life-ring");
            }

            if (module_enabled("module_announcement") == "1" && !in_array("announcements", $hidden_menu)) {
                $sidebar_menu[] = array("name" => "announcements", "url" => "announcements", "class" => "fa-bullhorn");
            }

            $sidebar_menu[] = array("name" => "users", "url" => "sales/Clients/users", "class" => "fa-user");

            if (get_setting("client_can_view_files")) {
                $sidebar_menu[] = array("name" => "files", "url" => "sales/Clients/files/" . $this->ci->login_user->id . "/page_view", "class" => "fa-file-image-o");
            }

            $sidebar_menu[] = array("name" => "my_profile", "url" => "sales/Clients/contact_profile/" . $this->ci->login_user->id, "class" => "fa-cog");

            if (module_enabled("module_knowledge_base") == "1" && !in_array("knowledge_base", $hidden_menu)) {
                $sidebar_menu[] = array("name" => "knowledge_base", "url" => "knowledge_base", "class" => "fa-question-circle");
            }
        }

        return $sidebar_menu;
    }

}
