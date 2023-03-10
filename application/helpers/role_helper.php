<?php

if (!function_exists('current_has_permit')) {
    function current_has_permit($permit_name, $redirect = false) {
        $ci = get_instance();
        $permission_lists = $ci->login_user->permissions;

        if( $ci->login_user->is_admin || get_array_value($permission_lists, $permit_name) ){
            return true;
        }

        if($redirect) {
            redirect("forbidden");
        }
        return false;
    }
}

if (!function_exists('user_has_permit')) {
    function user_has_permit($userid, $permit_name, $redirect = false) {
        $ci = get_instance();
        $permission_lists = @unserialize($ci->login_user->permissions);

        if( $ci->login_user->is_admin || get_array_value($permission_lists, $permit_name) ){
            return true;
        }

        if($redirect) {
            redirect("forbidden");
        }

        return false;
    }
}

if (!function_exists('module_enabled')) {
    function module_enabled($module_name, $redirect = false) {
        $ci = get_instance();
        
        if ( $ci->Settings_model->get_setting($module_name) === "1" ) {
            return true;
        }

        if($redirect) {
            redirect("forbidden");
        }

        return false;
    }
}