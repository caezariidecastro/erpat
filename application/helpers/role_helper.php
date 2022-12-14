<?php

    if (!function_exists('check_module_enabled')) {
        function check_module_enabled($module_name) {
            if ( get_setting($module_name) == "1" ) {
                return true;
            }

            return false;
        }
    }