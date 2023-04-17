<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * Types:
 * "" - Users default
 * "user" - Users custom & Clients custom
 * "client_default" - Clients default
 */

class Left_menus extends MY_Controller {

    protected $default = 'a:76:{i:0;a:1:{s:4:"name";s:9:"dashboard";}i:1;a:4:{s:4:"name";s:4:"Home";s:3:"url";s:1:"#";s:4:"icon";s:4:"home";s:15:"open_in_new_tab";s:0:"";}i:2;a:2:{s:4:"name";s:8:"timeline";s:11:"is_sub_menu";s:1:"1";}i:3;a:2:{s:4:"name";s:13:"announcements";s:11:"is_sub_menu";s:1:"1";}i:4;a:2:{s:4:"name";s:6:"events";s:11:"is_sub_menu";s:1:"1";}i:5;a:2:{s:4:"name";s:4:"todo";s:11:"is_sub_menu";s:1:"1";}i:6;a:2:{s:4:"name";s:5:"notes";s:11:"is_sub_menu";s:1:"1";}i:7;a:2:{s:4:"name";s:8:"messages";s:11:"is_sub_menu";s:1:"1";}i:8;a:4:{s:4:"name";s:14:"Human Resource";s:3:"url";s:1:"#";s:4:"icon";s:8:"suitcase";s:15:"open_in_new_tab";s:0:"";}i:9;a:2:{s:4:"name";s:8:"employee";s:11:"is_sub_menu";s:1:"1";}i:10;a:2:{s:4:"name";s:22:"submenu_hrm_department";s:11:"is_sub_menu";s:1:"1";}i:11;a:2:{s:4:"name";s:20:"submenu_hrm_schedule";s:11:"is_sub_menu";s:1:"1";}i:12;a:2:{s:4:"name";s:22:"submenu_hrm_attendance";s:11:"is_sub_menu";s:1:"1";}i:13;a:2:{s:4:"name";s:20:"submenu_hrm_overtime";s:11:"is_sub_menu";s:1:"1";}i:14;a:2:{s:4:"name";s:24:"submenu_hrm_disciplinary";s:11:"is_sub_menu";s:1:"1";}i:15;a:2:{s:4:"name";s:18:"submenu_hrm_leaves";s:11:"is_sub_menu";s:1:"1";}i:16;a:2:{s:4:"name";s:20:"submenu_hrm_holidays";s:11:"is_sub_menu";s:1:"1";}i:17;a:4:{s:4:"name";s:10:"Production";s:3:"url";s:1:"#";s:4:"icon";s:4:"fire";s:15:"open_in_new_tab";s:0:"";}i:18;a:2:{s:4:"name";s:19:"manufacturing_order";s:11:"is_sub_menu";s:1:"1";}i:19;a:2:{s:4:"name";s:17:"bill_of_materials";s:11:"is_sub_menu";s:1:"1";}i:20;a:2:{s:4:"name";s:17:"submenu_pid_units";s:11:"is_sub_menu";s:1:"1";}i:21;a:4:{s:4:"name";s:12:"Distribution";s:3:"url";s:1:"#";s:4:"icon";s:4:"bank";s:15:"open_in_new_tab";s:0:"";}i:22;a:2:{s:4:"name";s:10:"warehouses";s:11:"is_sub_menu";s:1:"1";}i:23;a:2:{s:4:"name";s:19:"submenu_lms_pallets";s:11:"is_sub_menu";s:1:"1";}i:24;a:4:{s:4:"name";s:7:"Finance";s:3:"url";s:1:"#";s:4:"icon";s:13:"cc-mastercard";s:15:"open_in_new_tab";s:0:"";}i:25;a:2:{s:4:"name";s:8:"payrolls";s:11:"is_sub_menu";s:1:"1";}i:26;a:2:{s:4:"name";s:19:"submenu_fas_summary";s:11:"is_sub_menu";s:1:"1";}i:27;a:2:{s:4:"name";s:19:"submenu_fas_payroll";s:11:"is_sub_menu";s:1:"1";}i:28;a:2:{s:4:"name";s:20:"submenu_fas_payments";s:11:"is_sub_menu";s:1:"1";}i:29;a:2:{s:4:"name";s:20:"submenu_fas_expenses";s:11:"is_sub_menu";s:1:"1";}i:30;a:2:{s:4:"name";s:5:"loans";s:11:"is_sub_menu";s:1:"1";}i:31;a:2:{s:4:"name";s:5:"taxes";s:11:"is_sub_menu";s:1:"1";}i:32;a:2:{s:4:"name";s:20:"submenu_fas_accounts";s:11:"is_sub_menu";s:1:"1";}i:33;a:4:{s:4:"name";s:9:"Logistics";s:3:"url";s:1:"#";s:4:"icon";s:5:"truck";s:15:"open_in_new_tab";s:0:"";}i:34;a:2:{s:4:"name";s:20:"submenu_lms_delivery";s:11:"is_sub_menu";s:1:"1";}i:35;a:2:{s:4:"name";s:21:"submenu_lms_transfers";s:11:"is_sub_menu";s:1:"1";}i:36;a:2:{s:4:"name";s:20:"submenu_lms_vehicles";s:11:"is_sub_menu";s:1:"1";}i:37;a:2:{s:4:"name";s:19:"submenu_lms_drivers";s:11:"is_sub_menu";s:1:"1";}i:38;a:4:{s:4:"name";s:5:"Sales";s:3:"url";s:1:"#";s:4:"icon";s:9:"cart-plus";s:15:"open_in_new_tab";s:0:"";}i:39;a:2:{s:4:"name";s:12:"sales_matrix";s:11:"is_sub_menu";s:1:"1";}i:40;a:2:{s:4:"name";s:20:"submenu_sms_invoices";s:11:"is_sub_menu";s:1:"1";}i:41;a:2:{s:4:"name";s:20:"submenu_sms_services";s:11:"is_sub_menu";s:1:"1";}i:42;a:2:{s:4:"name";s:20:"submenu_pid_products";s:11:"is_sub_menu";s:1:"1";}i:43;a:2:{s:4:"name";s:7:"clients";s:11:"is_sub_menu";s:1:"1";}i:44;a:2:{s:4:"name";s:21:"submenu_sms_customers";s:11:"is_sub_menu";s:1:"1";}i:45;a:2:{s:4:"name";s:6:"stores";s:11:"is_sub_menu";s:1:"1";}i:46;a:4:{s:4:"name";s:11:"Procurement";s:3:"url";s:1:"#";s:4:"icon";s:3:"fax";s:15:"open_in_new_tab";s:0:"";}i:47;a:2:{s:4:"name";s:21:"submenu_pid_purchases";s:11:"is_sub_menu";s:1:"1";}i:48;a:2:{s:4:"name";s:19:"submenu_pid_returns";s:11:"is_sub_menu";s:1:"1";}i:49;a:2:{s:4:"name";s:20:"submenu_pid_supplier";s:11:"is_sub_menu";s:1:"1";}i:50;a:4:{s:4:"name";s:8:"Safekeep";s:3:"url";s:1:"#";s:4:"icon";s:4:"lock";s:15:"open_in_new_tab";s:0:"";}i:51;a:2:{s:4:"name";s:13:"asset_entries";s:11:"is_sub_menu";s:1:"1";}i:52;a:2:{s:4:"name";s:16:"asset_categories";s:11:"is_sub_menu";s:1:"1";}i:53;a:2:{s:4:"name";s:14:"asset_location";s:11:"is_sub_menu";s:1:"1";}i:54;a:2:{s:4:"name";s:12:"asset_vendor";s:11:"is_sub_menu";s:1:"1";}i:55;a:2:{s:4:"name";s:11:"asset_brand";s:11:"is_sub_menu";s:1:"1";}i:56;a:4:{s:4:"name";s:9:"Marketing";s:3:"url";s:1:"#";s:4:"icon";s:10:"line-chart";s:15:"open_in_new_tab";s:0:"";}i:57;a:2:{s:4:"name";s:17:"submenu_mcs_leads";s:11:"is_sub_menu";s:1:"1";}i:58;a:2:{s:4:"name";s:18:"submenu_mcs_status";s:11:"is_sub_menu";s:1:"1";}i:59;a:2:{s:4:"name";s:18:"submenu_mcs_source";s:11:"is_sub_menu";s:1:"1";}i:60;a:2:{s:4:"name";s:9:"estimates";s:11:"is_sub_menu";s:1:"1";}i:61;a:2:{s:4:"name";s:11:"raffle_draw";s:11:"is_sub_menu";s:1:"1";}i:62;a:2:{s:4:"name";s:5:"epass";s:11:"is_sub_menu";s:1:"1";}i:63;a:4:{s:4:"name";s:11:"Help Center";s:3:"url";s:1:"#";s:4:"icon";s:9:"life-ring";s:15:"open_in_new_tab";s:0:"";}i:64;a:2:{s:4:"name";s:7:"tickets";s:11:"is_sub_menu";s:1:"1";}i:65;a:2:{s:4:"name";s:15:"help_page_title";s:11:"is_sub_menu";s:1:"1";}i:66;a:2:{s:4:"name";s:14:"knowledge_base";s:11:"is_sub_menu";s:1:"1";}i:67;a:2:{s:4:"name";s:5:"pages";s:11:"is_sub_menu";s:1:"1";}i:68;a:4:{s:4:"name";s:8:"Planning";s:3:"url";s:1:"#";s:4:"icon";s:11:"paper-plane";s:15:"open_in_new_tab";s:0:"";}i:69;a:2:{s:4:"name";s:24:"submenu_pms_all_projects";s:11:"is_sub_menu";s:1:"1";}i:70;a:2:{s:4:"name";s:23:"submenu_pms_view_gantts";s:11:"is_sub_menu";s:1:"1";}i:71;a:2:{s:4:"name";s:20:"submenu_pms_my_tasks";s:11:"is_sub_menu";s:1:"1";}i:72;a:2:{s:4:"name";s:22:"submenu_pms_timesheets";s:11:"is_sub_menu";s:1:"1";}i:73;a:4:{s:4:"name";s:8:"Security";s:3:"url";s:1:"#";s:4:"icon";s:11:"user-secret";s:15:"open_in_new_tab";s:0:"";}i:74;a:2:{s:4:"name";s:11:"access_logs";s:11:"is_sub_menu";s:1:"1";}i:75;a:1:{s:4:"name";s:8:"settings";}}'; 

    function __construct() {
        parent::__construct();
        $this->load->library("left_menu");
    }

    private function check_left_menu_permission($type = "") {
        if ($type == "user") {
            if ($this->login_user->user_type == "staff") {
                $this->access_only_team_members();
            } else if ($this->login_user->user_type == "client") {
                $this->access_only_clients();
            }
        } else if (!$type || $type == "client_default") {
            $this->access_only_admin();
        }
    }

    function index($type = "") {
        $this->check_left_menu_permission($type);

        $view_data["available_items"] = $this->left_menu->get_available_items($type);
        $view_data["sortable_items"] = $this->left_menu->get_sortable_items($type);
        $view_data["preview"] = $this->left_menu->rander_left_menu(true, $type);

        if ($type == "user") {
            $this->load->view("left_menu/user_left_menu", $view_data);
        } else {
            $view_data["setting_active_tab"] = ($type == "client_default") ? "client_left_menu" : "left_menu";
            $view_data["type"] = $type;

            $this->template->rander("left_menu/index", $view_data);
        }
    }

    function save() {
        if (get_setting("disable_editing_left_menu_by_clients") && $this->login_user->user_type == "client") {
            redirect("forbidden");
        }

        $type = $this->input->post("type");
        $this->check_left_menu_permission($type);

        $items_data = $this->input->post("data");
        if ($items_data) {
            $items_data = json_decode($items_data, true);

            //check if the setting menu has been added, if not, add it to the bottom
            if ($this->login_user->is_admin && $type != "client_default" && array_search("settings", array_column($items_data, "name")) === false) {
                $items_data[] = array("name" => "settings");
            }

            $items_data = serialize($items_data);
        }

        if ($type == "user") {
            $this->Settings_model->save_setting("user_" . $this->login_user->id . "_left_menu", $items_data);
            echo json_encode(array("success" => true, 'redirect_to' => get_uri($this->_prepare_user_custom_redirect_to_url()), 'message' => lang('settings_updated')));
        } else {
            if ($type == "client_default") {
                $this->Settings_model->save_setting("default_client_left_menu", $items_data);
            } else {
                $this->Settings_model->save_setting("default_left_menu", $items_data);
            }

            echo json_encode(array("success" => true, 'message' => lang('settings_updated')));
        }
    }

    private function _prepare_user_custom_redirect_to_url() {
        $redirect_to = "hrs/employee/view/" . $this->login_user->id . "/left_menu";
        if ($this->login_user->user_type == "client") {
            $redirect_to = "clients/contact_profile/" . $this->login_user->id . "/left_menu";
        }

        return $redirect_to;
    }

    function add_menu_item_modal_form() {
        $model_info = new stdClass();
        $model_info->title = $this->input->post("title");
        $model_info->url = $this->input->post("url");
        $model_info->is_sub_menu = $this->input->post("is_sub_menu");
        $model_info->open_in_new_tab = $this->input->post("open_in_new_tab");
        $model_info->icon = $this->input->post("icon");

        $view_data["model_info"] = $model_info;

        $this->load->view("left_menu/add_menu_item_modal_form", $view_data);
    }

    function prepare_custom_menu_item_data() {
        $title = $this->input->post("title");
        $url = $this->input->post("url");
        $is_sub_menu = $this->input->post("is_sub_menu");
        $open_in_new_tab = $this->input->post("open_in_new_tab");
        $icon = $this->input->post("icon");

        $item_array = array("name" => $title, "url" => $url, "is_sub_menu" => $is_sub_menu, "icon" => $icon, "open_in_new_tab" => $open_in_new_tab);
        $item_data = $this->left_menu->_get_item_data($item_array);

        if ($item_data) {
            echo json_encode(array("success" => true, "item_data" => $item_data));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function restore($type = "") {
        $this->check_left_menu_permission($type);

        if ($type == "user") {
            $this->Settings_model->save_setting("user_" . $this->login_user->id . "_left_menu", $this->default);
            redirect($this->_prepare_user_custom_redirect_to_url());
        } else {
            if ($type == "client_default") {
                $this->Settings_model->save_setting("default_client_left_menu", $this->default);
                redirect("left_menus/index/client_default");
            } else {
                $this->Settings_model->save_setting("default_left_menu", $this->default);
                redirect("left_menus");
            }
        }
    }

}

/* End of file Left_menu.php */
/* Location: ./application/controllers/Left_menu.php */