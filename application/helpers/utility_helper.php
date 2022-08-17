<?php

    if (!function_exists('get_team_all_unique')) {
        function get_team_all_unique($heads, $members) {
            $users = [];
            $lists = explode(",", $heads.",".$members);
            for ($i = 0; $i < count($lists); $i++) {
                if (isset($lists[$i]) && !in_array($lists[$i], $users)) {
                    $users[] = $lists[$i];
                }
            }   
            return $users;
        }
    }

    if (!function_exists('get_id_name')) {
        function get_id_name($id, $prefix = "", $zeros = 4) {
            return $prefix.str_pad($id, $zeros, '0', STR_PAD_LEFT);
        }
    }