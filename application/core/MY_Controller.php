<?php

class MY_Controller extends CI_Controller {

    public $login_user;
    protected $access_type = "";
    protected $allowed_members = array();
    protected $allowed_ticket_types = array();
    protected $module_group = "";
    protected $is_user_a_project_member = false;
    protected $is_clients_project = false; //check if loged in user's client's project

    protected $specifics = array("leave", "attendance", "timesheet_manage_permission", "message_permission", "ticket_staff", "staff");

    function __construct($initialize = true) {
        parent::__construct();

        //check user's login status, if not logged in redirect to signin page
        $login_user_id = $this->Users_model->login_user_id();
        if (!$login_user_id) {
            $uri_string = uri_string();

            if (!$uri_string || $uri_string === "signin") {
                redirect('signin');
            } else {
                redirect('signin?redirect=' . get_uri($uri_string));
            }
        }

        //initialize login users required information
        $this->login_user = $this->Users_model->get_access_info($login_user_id);

        if(in_array($this->login_user->user_type, array("", "system"))) {
            $this->Users_model->sign_out();
            redirect('forbidden');
        }

        //initialize login users access permissions
        if ($this->login_user->permissions) {
            $permissions = unserialize($this->login_user->permissions);
            $this->login_user->permissions = is_array($permissions) ? $permissions : array();
        } else {
            $this->login_user->permissions = array();
        }

        //we can set ip restiction to access this module. validate user access
        $this->check_allowed_ip();
    }

    /**
     * Check ip restriction for none admin users
     */
    private function check_allowed_ip() {
        if (!$this->login_user->is_admin && $this->login_user->user_type === "staff") {
            $ip = get_real_ip();
            $allowed_ips = $this->Settings_model->get_setting("allowed_ip_addresses");
            if ($allowed_ips) {
                $user_whitelisted = $this->Settings_model->get_setting("whitelisted_user_ip_tracking");
                $allowed_ip_array = array_map('trim', preg_split('/\R/', $allowed_ips));
                if (!in_array($this->login_user->id, explode(",", $user_whitelisted)) && !in_array($ip, $allowed_ip_array)) {
                    redirect("forbidden");
                }
            }
        }
    }

    /**
     * Check if the current user is with permission and return as bool, redirect, and json exit.
     */
    protected function with_permission($permission, $failReturnAs = "bool") {
        $permission_lists = $this->login_user->permissions;
        if( $this->login_user->is_admin || get_array_value($permission_lists, $permission) ){
            return true;
        }
        
        if($failReturnAs === "redirect") {
            redirect("forbidden");
        } else if($failReturnAs !== "bool" && $failReturnAs !== "redirect") {
            echo json_encode( array("success" => false, "message" => lang($failReturnAs)) );
            exit;
        }

        return false;
    }

    /**
     * Check if the module is enabled or not.
     */
    protected function with_module($module_name, $failReturnAs = "bool") {
        if ( $this->Settings_model->get_setting("module_".$module_name) === "1" ) {
            return true;
        }

        if($failReturnAs === "redirect") {
            redirect("forbidden");
        } else if($failReturnAs!== "bool" && $failReturnAs !== "redirect") {
            echo json_encode( array("success" => false, "message" => lang($failReturnAs)) );
            exit;
        }

        return false;
    }

    protected function get_my_team_users($users, $module = "staff") {
        $user_list = $this->get_allowed_users_only($module);

        $included = array();
        foreach($users as $one) {
            if(in_array($one, $user_list )) {
                $included[] = $one;
            }
        }

        return $included;
    }

    protected function can_manage_user($user_id, $access = "staff", $allow_self = false) {
        if($this->login_user->is_admin) {
            return true;
        }
        
        $user_list = $this->get_allowed_users_only($access);
        if(!$allow_self && $this->login_user->id == $user_id) {
            return false;
        }
        
        return in_array($user_id, $user_list);
    }

    /**
     * Initialize the login user's permissions with readable format
     */
    protected function init_permission_checker($module) {
        $info = $this->get_access_info($module);
        $this->access_type = $info->access_type;
        $this->allowed_members = $info->allowed_members;
        $this->allowed_ticket_types = $info->allowed_ticket_types;
        if( !isset($this->allowed_departments) ) {
            $this->allowed_departments = $info->allowed_departments;
        }
        $this->module_group = $info->module_group;
    }

    protected function get_allowed_users_only($access, $emptyIfAll = false) {
        $allowed_members = array();

        $module_permission = get_array_value($this->login_user->permissions, $access);
        if ($this->login_user->is_admin || $module_permission === "all") {
            $list_users = $this->Users_model->get_all_active();
            if(!$emptyIfAll) {
                foreach($list_users as $user) {
                    $allowed_members[] = $user->id; 
                }
            }
        } else if ($module_permission === "specific") {
            $module_permission = get_array_value($this->login_user->permissions, $access . "_specific");
            $permissions = explode(",", $module_permission);

            //check the accessable users list
            if ( in_array($access, $this->specifics) ) {
                $allowed_members = $this->prepare_allowed_members_array($permissions);
            }
        }

        return $allowed_members;
    }

    //prepear the login user's permissions
    protected function get_access_info($group) {
        $info = new stdClass();
        $info->access_type = "";
        $info->allowed_members = array();
        $info->allowed_ticket_types = array();
        $info->module_group = $group;

        //admin users has access to everything
        if ($this->login_user->is_admin) {
            $info->access_type = "all";
        } else {

            //not an admin user? check module wise access permissions
            $module_permission = get_array_value($this->login_user->permissions, $group);

            if ($module_permission === "all") {
                //this user's has permission to access/manage everything of this module (same as admin)
                $info->access_type = "all";
            } else if ($module_permission === "specific") {
                //this user's has permission to access/manage sepcific items of this module

                $info->access_type = "specific";
                $module_permission = get_array_value($this->login_user->permissions, $group . "_specific");
                $permissions = explode(",", $module_permission);

                //check the accessable users list
                if ( in_array($group, $this->specifics) ) {
                    $info->allowed_members = $this->prepare_allowed_members_array($permissions);
                } else if ($group === "ticket") {
                    //check the accessable ticket types
                    $info->allowed_ticket_types = $permissions;
                } else if ($group === "department") {
                    //check the accessable ticket types
                    $info->allowed_departments = $permissions;
                }
            }
        }
        return $info;
    }

    protected function get_imploded_departments() {
        if( isset($this->allowed_departments) ) {
            return implode(",", $this->allowed_departments);
        }

        return "";
    }

    protected function prepare_allowed_members_array($permissions) {
        $allowed_members = array();
        $allowed_teams = array();
        foreach ($permissions as $vlaue) {
            $permission_on = explode(":", $vlaue);
            $type = get_array_value($permission_on, "0");
            $type_value = get_array_value($permission_on, "1");
            if ($type === "member") {
                array_push($allowed_members, $type_value);
            } else if ($type === "team") {
                array_push($allowed_teams, $type_value);
            }
        }


        if (count($allowed_teams)) {
            $team = $this->Team_model->get_members($allowed_teams)->result();

            foreach ($team as $value) {
                if ($value->members) {
                    $heads_array = explode(",", $value->heads);
                    $allowed_members = array_merge($allowed_members, $heads_array);
                    $members_array = explode(",", $value->members);
                    $allowed_members = array_merge($allowed_members, $members_array);
                }
            }
        }

        return $allowed_members;
    }

    //only allowed to access for team members 
    protected function access_only_team_members() {
        if ($this->login_user->user_type !== "staff") {
            redirect("forbidden");
        }
    }

    //only allowed to access for admin users
    protected function access_only_admin() {
        if (!$this->login_user->is_admin) {
            redirect("forbidden");
        }
    }

    //access only allowed team members
    protected function access_only_allowed_members($exit = true) {
        if ($this->access_type === "all") {
            return true; //can access if user has permission
        } else if ($this->module_group === "ticket" && $this->access_type === "specific") {
            return true; //can access if it's tickets module and user has a pertial access
        } else {
            if($exit) {
                redirect("forbidden");
            } else {
                return false;
            }
        }
    }

    //team_member_update_permission
    protected function access_only_specific($specific_access, $specific_id = 0) {
        $access_info = $this->get_access_info($specific_access);
        if($access_info->access_type == "all") {
            return true;
        } else if(in_array($specific_id, $access_info->allowed_members)) {
            return true;
        } else {
            return false;
        }
    }

    //access only allowed team members or client contacts 
    protected function access_only_allowed_members_or_client_contact($client_id) {
        if ($this->login_user->is_admin) { //TODO: Fixed this permission issue.
            return true;
        }

        if ($this->access_type === "all") {
            return true; //can access if user has permission
        } else if ($this->module_group === "ticket" && $this->access_type === "specific") {
            return true; //can access if it's tickets module and user has a pertial access
        } else if ($this->login_user->client_id === $client_id) {
            return true; //can access if client id match 
        } else {
            redirect("forbidden");
        }
    }

    //allowed team members and clint himself can access  
    protected function access_only_allowed_members_or_contact_personally($user_id) {
        if (!($this->access_type === "all" || $user_id === $this->login_user->id)) {
            redirect("forbidden");
        }
    }

    //access all team members and client contact
    protected function access_only_team_members_or_client_contact($client_id) {
        if (!($this->login_user->user_type === "staff" || $this->login_user->client_id === $client_id)) {
            redirect("forbidden");
        }
    }

    //only allowed to access for admin users
    protected function access_only_clients() {
        if ($this->login_user->user_type != "client") {
            redirect("forbidden");
        }
    }

    //check who has permission to create projects
    protected function can_create_projects() {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_manage_all_projects") == "1") {
                return true;
            } else if (get_array_value($this->login_user->permissions, "can_create_projects") == "1") {
                return true;
            }
        } else {
            if (get_setting("client_can_create_projects")) {
                return true;
            }
        }
    }

    //get currency dropdown list
    protected function _get_currency_dropdown_select2_data() {
        $currency = array(array("id" => "", "text" => "-"));
        foreach (get_international_currency_code_dropdown() as $value) {
            $currency[] = array("id" => $value, "text" => $value);
        }
        return $currency;
    }

    //access team members and clients
    protected function access_only_team_members_or_client() {
        if (!($this->login_user->user_type === "staff" || $this->login_user->user_type === "client")) {
            redirect("forbidden");
        }
    }

    //When checking project permissions, to reduce db query we'll use this init function, where team members has to be access on the project
    protected function init_project_permission_checker($project_id = 0) {
        if ($this->login_user->user_type == "client") {
            $this->load->model("Projects_model");
            $project_info = $this->Projects_model->get_one($project_id);
            if ($project_info->client_id == $this->login_user->client_id) {
                $this->is_clients_project = true;
            }
        } else {
            $this->load->model("Project_members_model");
            $this->is_user_a_project_member = $this->Project_members_model->is_user_a_project_member($project_id, $this->login_user->id);
        }
    }

    protected function can_create_tasks($in_project = true) {
        if ($this->login_user->user_type == "staff") {
            if ($this->can_manage_all_projects()) {
                return true;
            } else if (get_array_value($this->login_user->permissions, "can_create_tasks") == "1") {
                //check is user a project member
                if ($in_project) {
                    return $this->is_user_a_project_member; //check the specific project permission
                } else {
                    return true;
                }
            }
        } else {
            //check settings for client's project permission
            if (get_setting("client_can_create_tasks")) {
                if ($in_project) {
                    //check if it's client's project
                    return $this->is_clients_project;
                } else {
                    //client has permission to create tasks on own projects
                    return true;
                }
            }
        }
    }

    protected function can_manage_all_projects() {
        if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_manage_all_projects") == "1") {
            return true;
        }
    }

    //get currencies dropdown
    protected function _get_currencies_dropdown() {
        $this->load->model("Invoices_model");
        $used_currencies = $this->Invoices_model->get_used_currencies_of_client()->result();

        if ($used_currencies) {
            $default_currency = get_setting("default_currency");

            $currencies_dropdown = array(
                array("id" => "", "text" => "- " . lang("currency") . " -"),
                array("id" => $default_currency, "text" => $default_currency) // add default currency
            );

            foreach ($used_currencies as $currency) {
                $currencies_dropdown[] = array("id" => $currency->currency, "text" => $currency->currency);
            }

            return json_encode($currencies_dropdown);
        }
    }

    //get hidden topbar menus dropdown
    protected function get_hidden_topbar_menus_dropdown() {
        //topbar menus dropdown
        $hidden_topbar_menus = array(
            "to_do",
            "favorite_projects",
            "dashboard_customization",
            "quick_add"
        );

        if ($this->login_user->user_type == "staff") {
            //favourite clients
            $access_client = get_array_value($this->login_user->permissions, "client");
            if ($this->login_user->is_admin || $access_client) {
                array_push($hidden_topbar_menus, "favorite_clients");
            }

            //custom language
            if (!get_setting("disable_language_selector_for_team_members")) {
                array_push($hidden_topbar_menus, "language");
            }
        } else {
            //custom language
            if (!get_setting("disable_language_selector_for_clients")) {
                array_push($hidden_topbar_menus, "language");
            }
        }

        $hidden_topbar_menus_dropdown = array();
        foreach ($hidden_topbar_menus as $hidden_menu) {
            $hidden_topbar_menus_dropdown[] = array("id" => $hidden_menu, "text" => lang($hidden_menu));
        }

        return json_encode($hidden_topbar_menus_dropdown);
    }

    //get existing projects dropdown for income and expenses
    protected function _get_projects_dropdown_for_income_and_epxenses($type = "all") {
        $this->load->model("Invoice_payments_model");
        $projects = $this->Invoice_payments_model->get_used_projects($type)->result();

        if ($projects) {
            $projects_dropdown = array(
                array("id" => "", "text" => "- " . lang("project") . " -"),
            );

            foreach ($projects as $project) {
                $projects_dropdown[] = array("id" => $project->id, "text" => $project->title);
            }

            return json_encode($projects_dropdown);
        }
    }

    protected function _get_groups_dropdown_select2_data($show_header = false) {
        $this->load->model("Client_groups_model");
        $client_groups = $this->Client_groups_model->get_all()->result();
        $groups_dropdown = array();

        if ($show_header) {
            $groups_dropdown[] = array("id" => "", "text" => "- " . lang("client_groups") . " -");
        }

        foreach ($client_groups as $group) {
            $groups_dropdown[] = array("id" => $group->id, "text" => $group->title);
        }
        return $groups_dropdown;
    }

    protected function get_clients_and_leads_dropdown($return_json = false) {
        $clients_dropdown = array("" => "-");
        $clients_json_dropdown = array(array("id" => "", "text" => "-"));
        $this->load->model("Clients_model");
        $clients = $this->Clients_model->get_all_where(array("deleted" => 0), 0, 0, "is_lead")->result();

        foreach ($clients as $client) {
            $company_name = $client->is_lead ? lang("lead") . ": " . $client->company_name : $client->company_name;

            $clients_dropdown[$client->id] = $company_name;
            $clients_json_dropdown[] = array("id" => $client->id, "text" => $company_name);
        }

        return $return_json ? $clients_json_dropdown : $clients_dropdown;
    }

    //check if the login user has restriction to show all tasks
    protected function show_assigned_tasks_only_user_id() {
        if ($this->login_user->user_type === "staff") {
            return get_array_value($this->login_user->permissions, "show_assigned_tasks_only") == "1" ? $this->login_user->id : false;
        }
    }

    //make calendar filter dropdown
    protected function get_calendar_filter_dropdown($type = "default") {
        /*
         * There should be all filters in main Events
         * On client->events tab, there will be only events and project deadlines field
         * On lead->events tab, there will be only events field
         */

        $this->load->helper('cookie');
        $selected_filters_cookie = get_cookie("calendar_filters_of_user_" . $this->login_user->id);
        $selected_filters_cookie_array = $selected_filters_cookie ? explode('-', $selected_filters_cookie) : array("events"); //load only events if there is no cookie

        $calendar_filter_dropdown = array(array("id" => "events", "text" => lang("events"), "isChecked" => in_array("events", $selected_filters_cookie_array) ? true : false));

        if ($type !== "lead") {
            if ($this->login_user->user_type == "staff" && $type == "default") {
                //approved leaves
                $leave_access_info = $this->get_access_info("leave");
                if ($leave_access_info->access_type) {
                    $calendar_filter_dropdown[] = array("id" => "leave", "text" => lang("leave"), "isChecked" => in_array("leave", $selected_filters_cookie_array) ? true : false);
                }

                //task start dates
                $calendar_filter_dropdown[] = array("id" => "task_start_date", "text" => lang("task_start_date"), "isChecked" => in_array("task_start_date", $selected_filters_cookie_array) ? true : false);

                //task deadlines
                $calendar_filter_dropdown[] = array("id" => "task_deadline", "text" => lang("task_deadline"), "isChecked" => in_array("task_deadline", $selected_filters_cookie_array) ? true : false);
            }

            //project start dates
            $calendar_filter_dropdown[] = array("id" => "project_start_date", "text" => lang("project_start_date"), "isChecked" => in_array("project_start_date", $selected_filters_cookie_array) ? true : false);

            //project deadlines
            $calendar_filter_dropdown[] = array("id" => "project_deadline", "text" => lang("project_deadline"), "isChecked" => in_array("project_deadline", $selected_filters_cookie_array) ? true : false);
        }

        return $calendar_filter_dropdown;
    }

    protected function make_labels_dropdown($type = "", $label_ids = "", $is_filter = false) {
        if (!$type) {
            show_404();
        }

        $labels_dropdown = $is_filter ? array(array("id" => "", "text" => "- " . lang("label") . " -")) : array();

        $options = array(
            "context" => $type
        );

        if ($type == "event" || $type == "note" || $type == "to_do") {
            $options["user_id"] = $this->login_user->id;
        }

        if ($label_ids) {
            $add_label_option = true;

            //check if any string is exists, 
            //if so, not include this parameter
            $explode_ids = explode(',', $label_ids);
            foreach ($explode_ids as $label_id) {
                if (!is_int($label_id)) {
                    $add_label_option = false;
                    break;
                }
            }

            if ($add_label_option) {
                $options["label_ids"] = $label_ids; //to edit labels where have access of others
            }
        }

        $labels = $this->Labels_model->get_details($options)->result();
        foreach ($labels as $label) {
            $labels_dropdown[] = array("id" => $label->id, "text" => $label->title);
        }

        return $labels_dropdown;
    }

    protected function can_edit_projects() {
        if ($this->login_user->user_type == "staff") {
            if ($this->can_manage_all_projects()) {
                return true;
            } else if (get_array_value($this->login_user->permissions, "can_edit_projects") == "1") {
                return true;
            }
        } else {
            if (get_setting("client_can_edit_projects")) {
                return true;
            }
        }
    }

    protected function get_user_options_for_query($only_type = "") {
        /*
         * team members can send message to all team members/can't send to any member/can send to specific members
         * clients can only send message to team members and to own contacts (as defined on Client settings)
         * team members can send message to clients (as defined on Client settings)
         */

        $options = array("login_user_id" => $this->login_user->id);
        $client_message_users = get_setting("client_message_users");

        if ($this->login_user->user_type == "staff") {
            //user is team member
            if ($only_type !== "client") {
                if (!get_array_value($this->login_user->permissions, "message_permission")) {
                    //user can manage all members
                    $options["all_members"] = true;
                } else if (get_array_value($this->login_user->permissions, "message_permission") == "specific") {
                    //user can manage only specific members
                    $options["specific_members"] = $this->allowed_members;
                }
            }

            $client_message_users_array = explode(",", $client_message_users);
            if (in_array($this->login_user->id, $client_message_users_array) && $only_type !== "staff") {
                //user can send message to clients
                $options["member_to_clients"] = true;
            }
        } else {
            //user is a client contact
            if ($client_message_users) {
                if ($only_type !== "client") {
                    $options["client_to_members"] = $client_message_users;
                }

                if (get_setting("client_message_own_contacts") && $only_type !== "staff") {
                    //client has permission to send message to own client contacts
                    $options["client_id"] = $this->login_user->client_id;
                }
            }
        }

        return $options;
    }

    protected function check_access_on_messages_for_this_user() {
        $accessable = true;

        if ($this->login_user->user_type == "staff") {
            $client_message_users = get_setting("client_message_users");
            $client_message_users_array = explode(",", $client_message_users);

            if (!$this->login_user->is_admin && get_array_value($this->login_user->permissions, "message_permission") == "" && !in_array($this->login_user->id, $client_message_users_array)) {
                $accessable = false;
            }
        } else {
            if (!get_setting("client_message_users")) {
                $accessable = false;
            }
        }

        return $accessable;
    }

    protected function can_view_invoices($client_id = 0) {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "invoice") === "all" || get_array_value($this->login_user->permissions, "invoice") === "read_only") {
                return true;
            }
        } else {
            if ($this->login_user->client_id === $client_id) {
                return true;
            }
        }
    }

    protected function can_edit_invoices() {
        if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "invoice") === "all")) {
            return true;
        }
    }

    protected function can_access_expenses() {
        $permissions = $this->login_user->permissions;
        if ($this->login_user->is_admin || get_array_value($permissions, "expense")) {
            return true;
        } else {
            return false;
        }
    }

    protected function validate_sending_message($to_user_id) {
        $users = $this->Messages_model->get_users_for_messaging($this->get_user_options_for_query())->result();
        if (!$this->check_access_on_messages_for_this_user() || !in_array($to_user_id, array_column($users, "id"))) {
            return false;
        }

        //so the sender could send message to the receiver
        //check if the receiver could also send message to the sender
        $to_user_info = $this->Users_model->get_one($to_user_id);
        if (!$to_user_info->is_admin && $to_user_info->user_type == "staff") {
            //receiver is a team member
            $permissions = array();
            $user_permissions = $this->Users_model->get_access_info($to_user_id)->permissions;
            if ($user_permissions) {
                $user_permissions = unserialize($user_permissions);
                $permissions = is_array($user_permissions) ? $user_permissions : array();
            }

            if (get_array_value($permissions, "message_permission") == "") {
                //user doesn't have permission to send any message
                return false;
            } else if (get_array_value($permissions, "message_permission") == "specific") {
                //user has access on specific members
                $module_permission = get_array_value($permissions, "message_permission_specific");
                $permissions = explode(",", $module_permission);
                $allowed_members = $this->prepare_allowed_members_array($permissions);
                if (!in_array($this->login_user->id, $allowed_members)) {
                    return false;
                }
            }
        }

        return true;
    }

    protected function get_users_select2_dropdown($default_text = "users", $module = "staff", $where = array()) {
        $assigned_to_dropdown = array(array("id" => "", "text" => "- " . lang($default_text) . " -"));

        $assigned_to_list = $this->Users_model->get_dropdown_list(
            array("first_name", "last_name"), "id", 
            array_merge($where, 
                array( "deleted" => 0, "status" => "active", "user_type" => "staff")
            ),
        );

        foreach ($assigned_to_list as $key => $value) {
            if( $this->login_user->is_admin || $this->access_type === "all") {
                $assigned_to_dropdown[] = array("id" => $key, "text" => $value);
            } else {
                $allowed_members = $this->get_allowed_users_only($module);
                foreach ($allowed_members as $user) {
                    if($key == $user) {
                        $assigned_to_dropdown[] = array("id" => $key, "text" => $value);
                    }
                }
            }
        }

        return $assigned_to_dropdown;
    }

    protected function get_users_select2_filter($default_text = "users", $module = "staff", $where = array()) {
        $assigned_to_filter = array("" => "- ".lang($default_text)." -");

        $assigned_to_list = $this->Users_model->get_dropdown_list(
            array("first_name", "last_name"), "id", 
            array_merge($where, 
                array( "deleted" => 0, "status" => "active", "user_type" => "staff")
            ),
        );

        foreach ($assigned_to_list as $key => $value) {
            if( $this->login_user->is_admin || $this->access_type === "all") {
                $assigned_to_filter[$key] = $value;
            } else {
                $allowed_members = $this->get_allowed_users_only($module);
                foreach ($allowed_members as $user) {
                    if($key == $user) {
                        $assigned_to_filter[$key] = $value;
                    }
                }
            }
        }

        return $assigned_to_filter;
    }

    protected function _get_team_select2_data() {
        $teams = $this->Team_model->get_details()->result();
        $team_select2 = array(array('id' => '', 'text'  => '- Departments -'));

        foreach($teams as $team){
            $team_select2[] = array('id' => $team->id, 'text'  => $team->title);
        }

        return $team_select2;
    }

    protected function _get_ticket_types_select2_filter() {

        $where = array();
        if ($this->login_user->user_type === "staff" && $this->access_type !== "all" && !empty($this->allowed_ticket_types)) {
            $where = array("where_in" => array("id" => $this->allowed_ticket_types));
        }

        $ticket_type = $this->Ticket_types_model->get_dropdown_list(array("title"), "id", $where);

        $ticket_type_dropdown = array(array("id" => "", "text" => "- " . lang("ticket_type") . " -"));
        foreach ($ticket_type as $id => $name) {
            $ticket_type_dropdown[] = array("id" => $id, "text" => $name);
        }
        return $ticket_type_dropdown;
    }

    protected function _get_leave_types_dropdown() {

        $leave_type = $this->Leave_types_model->get_dropdown_list(array("title"), "id", array("status" => "active"));

        $leave_type_dropdown = array(array("id" => "", "text" => "- " . lang("leave_type") . " -"));
        foreach ($leave_type as $id => $name) {
            $leave_type_dropdown[] = array("id" => $id, "text" => $name);
        }
        return $leave_type_dropdown;
    }

    protected function _get_leave_types_select2_data() {

        $option = array("status" => "active");
        $leave_types = $this->Leave_types_model->get_details($option)->result();
        $leave_type_select2 =  array(array("id" => "", "text" => "- " . lang("leave_type") . " -"));

        foreach($leave_types as $leave_type){
            $leave_type_select2[] = array('id' => $leave_type->id, 'text'  => $leave_type->title);
        }

        return $leave_type_select2;
    }

    protected function _get_account_select2_data() {
        $Accounts = $this->Accounts_model->get_all()->result();
        $account_select2 = array(array('id' => '', 'text'  => '- Accounts -'));

        foreach ($Accounts as $account) {
            $account_select2[] = array('id' => $account->id, 'text' => $account->name) ;
        }
        return $account_select2;
    }
}
