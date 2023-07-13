<?php

/**
 * Get payslip item by user id, filter, and item name
 */
if (!function_exists('get_user_option')) {
    function get_user_option($user_id, $item_key, $actions, $group = "biweekly", $text = false) {
        $ci = get_instance();

        $meta_key = "user_".$group."_".$item_key."_".$user_id."_".$actions;
        $meta_val = $ci->Settings_model->get_setting($meta_key, "user");

        if($text) {
            return $meta_val;
        }
        
        return num_limit($meta_val);
    }
}

/**
 * Set payslip item by user id, filter, and item name
 */
if (!function_exists('set_user_option')) {
    function set_user_option($user_id, $item_key, $item_value, $group = "option", $actions = "default") {
        $ci = get_instance();

        $meta_key = "user_".$group."_".$item_key."_".$user_id."_".$actions;
        return $ci->Settings_model->save_setting($meta_key, $item_value, "user");
    }
}