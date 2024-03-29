<?php

/**
 * Get the monthly salary using user id.
 */
if (!function_exists('get_monthly_salary')) {
    function get_monthly_salary($user_id, $to_currency = true) {
        $ci = get_instance();
        $job_info = $ci->Users_model->get_job_info($user_id);
        
        $monthly_salary = get_monthly_from_hourly($job_info->rate_per_hour * 8);
        if( isset($job_info->salary) ) {
            $monthly_salary = convert_number_to_decimal($job_info->salary);
        }

        if($to_currency) {
            return to_currency($monthly_salary);
        }
        
        return $monthly_salary;
    }
}

/**
 * Get the monthly salary with hourly rate
 */
if (!function_exists('get_monthly_from_hourly')) {
    function get_monthly_from_hourly($hourly_rate, $to_currency = true) {
        $monthly_salary = (floatval($hourly_rate) * 8.0) * (get_setting('days_per_year', 261)/12);
        $monthly_salary = floor($monthly_salary/1) * 1;

        if($to_currency) {
            return to_currency($monthly_salary);
        } else {
            return $monthly_salary;
        }
    }
}

/**
 * Get the hourly rate using the user id.
 */
if (!function_exists('get_hourly_rate')) {
    function get_hourly_rate($user_id, $to_currency = true) {
        $ci = get_instance();
        $job_info = $ci->Users_model->get_job_info($user_id);

        $hourly_rate = 0;
        if( isset($job_info->rate_per_hour) ) {
            $hourly_rate = $job_info->rate_per_hour;
        }

        if($to_currency) {
            return to_currency($hourly_rate);
        }
        
        return $hourly_rate;
    }
}

if (!function_exists('get_sss_contribution')) {
    function get_sss_contribution($monthly_salary, $to_currency = true) {
        
        $current = 0;
        switch ($monthly_salary) {
            case $monthly_salary >= 5750 && $monthly_salary < 6250:
                $current = 315.0;
                break;
            case $monthly_salary >= 6250 && $monthly_salary < 6750:
                $current = 337.5;
                break;
            case $monthly_salary >= 6750 && $monthly_salary < 7250:
                $current = 360.0;
                break;
            case $monthly_salary >= 7250 && $monthly_salary < 7750:
                $current = 382.5;
                break;
            case $monthly_salary >= 7750 && $monthly_salary < 8250:
                $current = 405.0;
                break;
            case $monthly_salary >= 8250 && $monthly_salary < 8750:
                $current = 427.5;
                break;
            case $monthly_salary >= 8750 && $monthly_salary < 9250:
                $current = 450.0;
                break;

            case $monthly_salary >= 9250 && $monthly_salary < 10250:
                $current = 472.5;
                break;
            case $monthly_salary >= 10250 && $monthly_salary < 10750:
                $current = 495.0;
                break;
            case $monthly_salary >= 10750 && $monthly_salary < 11250:
                $current = 517.5;
                break;
            case $monthly_salary >= 11250 && $monthly_salary < 11750:
                $current = 540.0;
                break;
            case $monthly_salary >= 11750 && $monthly_salary < 12250:
                $current = 562.5;
                break;
            case $monthly_salary >= 12250 && $monthly_salary < 12750:
                $current = 585.0;
                break;
            case $monthly_salary >= 12750 && $monthly_salary < 13250:
                $current = 607.5;
                break;

            case $monthly_salary >= 13250 && $monthly_salary < 13750:
                $current = 630.0;
                break;
            case $monthly_salary >= 13750 && $monthly_salary < 14250:
                $current = 652.5;
                break;
            case $monthly_salary >= 14250 && $monthly_salary < 14750:
                $current = 675.0;
                break;
            case $monthly_salary >= 14750 && $monthly_salary < 15250:
                $current = 697.5;
                break;
            case $monthly_salary >= 15250 && $monthly_salary < 16250:
                $current = 720.0;
                break;
            case $monthly_salary >= 16250 && $monthly_salary < 16750:
                $current = 742.5;
                break;
            case $monthly_salary >= 16750 && $monthly_salary < 17250:
                $current = 765.0;
                break;

            case $monthly_salary >= 17250 && $monthly_salary < 17750:
                $current = 787.5;
                break;
            case $monthly_salary >= 17750 && $monthly_salary < 18250:
                $current = 810.0;
                break;
            case $monthly_salary >= 18250 && $monthly_salary < 18750:
                $current = 832.5;
                break;
            case $monthly_salary >= 18750 && $monthly_salary < 19250:
                $current = 855.0;
                break;
            case $monthly_salary >= 19250 && $monthly_salary < 19750:
                $current = 877.5;
                break;
            case $monthly_salary >= 19750 && $monthly_salary < 20250:
                $current = 900.0;
                break;
            case $monthly_salary >= 20250 && $monthly_salary < 20750:
                $current = 922.5;
                break;

            case $monthly_salary >= 20750 && $monthly_salary < 21250:
                $current = 945.0;
                break;
            case $monthly_salary >= 21250 && $monthly_salary < 21750:
                $current = 967.5;
                break;
            case $monthly_salary >= 21750 && $monthly_salary < 22250:
                $current = 990.0;
                break;
            case $monthly_salary >= 22250 && $monthly_salary < 22750:
                $current = 1012.5;
                break;
            case $monthly_salary >= 22750 && $monthly_salary < 23250:
                $current = 1035.0;
                break;
            case $monthly_salary >= 23250 && $monthly_salary < 23750:
                $current = 1057.5;
                break;
            case $monthly_salary >= 23750 && $monthly_salary < 24250:
                $current = 1080.0;
                break;
            case $monthly_salary >= 24250 && $monthly_salary < 24750:
                $current = 1102.5;
                break;

            case $monthly_salary >= 24750 && $monthly_salary < 25250:
                $current = 1125.0;
                break;
            case $monthly_salary >= 25250 && $monthly_salary < 25750:
                $current = 1147.5;
                break;
            case $monthly_salary >= 25750 && $monthly_salary < 26250:
                $current = 1170.0;
                break;
            case $monthly_salary >= 26250 && $monthly_salary < 26750:
                $current = 1192.5;
                break;
            case $monthly_salary >= 26750 && $monthly_salary < 27250:
                $current = 1215;
                break;
            case $monthly_salary >= 27250 && $monthly_salary < 27750:
                $current = 1237.5;
                break;
            case $monthly_salary >= 27750 && $monthly_salary < 28250:
                $current = 1260.0;
                break;
            case $monthly_salary >= 28250 && $monthly_salary < 28750:
                $current = 1282.5;
                break;
            case $monthly_salary >= 28750 && $monthly_salary < 29250:
                $current = 1305.0;
                break;
            case $monthly_salary >= 29250 && $monthly_salary < 29750:
                $current = 1327.5;
                break;

            case $monthly_salary >= 29750:
                $current = 1350.0;
                break;

            default:
                $current = 0;
                break;
        }

        if($to_currency) {
            return to_currency($current);
        }
            
        return $current;
    }
}

if (!function_exists('get_phealth_contribution')) {
    function get_phealth_contribution($monthly_salary, $to_currency = true) {
        
        $current = ($monthly_salary * 0.04) / 2; //employee share only

        if($to_currency) {
            return to_currency($current); 
        }
            
        return $current;
    }
}

if (!function_exists('get_pagibig_contribution')) {
    function get_pagibig_contribution($monthly_salary, $to_currency = true) {
        
        $current = 200 / 2; //employee share only

        if($to_currency) {
            return to_currency($current); 
        }
            
        return $current;
    }
}

if (!function_exists('get_user_deductions')) {
    function get_user_deductions($user_id, $is_raw = false) {
        $ci = get_instance();
        $result = $ci->Settings_model->get_setting("user_".$user_id."_deductions", "user");
        if($result) {
            $results = unserialize($result);
        } else {
            $results = [
                array(lang("sss_contri"), 0.00, 0.00, 0.00, 0.00),
                array(lang("pagibig_contri"), 0.00, 0.00, 0.00, 0.00),
                array(lang("philhealth_contri"), 0.00, 0.00, 0.00, 0.00),
                array(lang("hmo_contri"), 0.00, 0.00, 0.00, 0.00),
            ];
        }

        $data = [];
        foreach($results as $item) {
            if( $is_raw ) {
                $data[] = array(
                    lang($item[0]),
                    $item[1],
                    $item[2],
                    $item[3],
                    $item[4],
                );
            } else {
                $data[] = array(
                    lang($item[0]),
                    cell_input("daily_".$item[0], $item[1], "number"),
                    cell_input("weekly_".$item[0], $item[2], "number"),
                    cell_input("biweekly_".$item[0], $item[3], "number"),
                    cell_input("monthly_".$item[0], $item[4], "number"),
                );
            }
            
        }

        return $data;
    }
}

if (!function_exists('get_deduct_val')) {
    function get_deduct_val($deductions, $key, $target = "daily") {
        $value = 0;
        foreach($deductions as $item) {
            if($item[0] === $key) {
                if($target === "daily") {
                    $value = $item[1];
                } else if($target === "weekly") {
                    $value = $item[2];
                } else if($target === "biweekly") {
                    $value = $item[3];
                } else if($target === "monthly") {
                    $value = $item[4];
                }
            }
        }
        return $value;
    }
}

if (!function_exists('get_contribution_by_category')) {
    function get_contribution_by_category($object, $target = "daily") {
        $data = array();
        foreach($object as $item) {
            if($target == "daily") {
                $data[$item[0]] = $item[1];
            } else if($target == "weekly") {
                $data[$item[0]] = $item[2];
            } else if($target == "biweekly") {
                $data[$item[0]] = $item[3];
            } else if($target == "monthly") {
                $data[$item[0]] = $item[4];
            } else {
                $data[$item[0]] = 0;
            }
        }
        return $data;
    }
}

if (!function_exists('cell_input')) {
    function cell_input($key, $val = "", $type = "text", $class="", $disabled = false) {
        $disabled = $disabled?"disabled":"";
        return "<input type='$type' id='$key' name='$key' value='$val' class='cell_input $class' $disabled style='text-align: right; border: 1px solid #d2d2d2; padding: 5px;'/>";
    }
}

if (!function_exists('get_compensation_tax')) {
    function get_compensation_tax($table = 'daily') {
        $ci = get_instance();
        $ci->load->model('Taxes_model');
        $result = false;

        if($table === 'daily') {
            $result = $ci->Settings_model->get_setting("daily_tax_table", "payroll");
            if($result) {
                $result = unserialize($result);
            } else {
                $result = $ci->Taxes_model->get_daily_raw_default();
            }
        } else if($table === 'weekly') {
            $result = $ci->Settings_model->get_setting("weekly_tax_table", "payroll");
            if($result) {
                $result = unserialize($result);
            } else {
                $result = $ci->Taxes_model->get_weekly_raw_default();
            }
        } else if($table === 'biweekly') {
            $result = $ci->Settings_model->get_setting("biweekly_tax_table", "payroll");
            if($result) {
                $result = unserialize($result);
            } else {
                $result = $ci->Taxes_model->get_biweekly_raw_default();
            }
        } else if($table === 'monthly') {
            $result = $ci->Settings_model->get_setting("monthly_tax_table", "payroll");
            if($result) {
                $result = unserialize($result);
            } else {
                $result = $ci->Taxes_model->get_monthly_raw_default();
            }
        }

        return $result;
    }
}

/**
 * Get the monthly salary using user id.
 */
if (!function_exists('get_date_hired')) {
    function get_date_hired($user_id, $format = "d F Y") {
        $ci = get_instance();
        $job_info = $ci->Users_model->get_job_info($user_id);
        
        if( is_date_exists($job_info->date_of_hire) ) {
            return convert_date_format($job_info->date_of_hire, $format);
        }
        
        return "-";
    }
}

/**
 * Get payslip item by user id, filter, and item name
 */
if (!function_exists('get_payslip_item')) {
    function get_payslip_item($payslip_id, $item_key, $item_type) {
        if(!$payslip_id) {
            return false;
        }

        $ci = get_instance();
        $ci->load->model("Payslip_".$item_type."_model");

        $object = $ci->{"Payslip_".$item_type."_model"}->get_details(array(
            "payslip_id" => $payslip_id,
            "item_key" => $item_key
        ))->row();

        if(!$object) {
            return false;
        }
        
        return $object;
    }
}

/**
 * Set payslip item by user id, filter, and item name
 */
if (!function_exists('set_payslip_item')) {
    function set_payslip_item($payslip_id, $item_key, $object, $item_type, $remarks = "") {
        if(!$payslip_id || !$item_key || !$item_type ) {
            return false;
        }
        $object['payslip_id'] = $payslip_id;
        $object['item_key'] = $item_key;
        
        $ci = get_instance();
        $ci->load->model("Payslip_".$item_type."_model");

        $find = $ci->{"Payslip_".$item_type."_model"}
            ->get_one_where(
                array( "payslip_id"=>$payslip_id, "item_key"=>$item_key, "deleted"=>0)
            );
        $id = isset($find->id)?$find->id:0;
        return $ci->{"Payslip_".$item_type."_model"}->save($object, $id);
    }
}

/**
 * Set payslip item by user id, filter, and item name
 */
if (!function_exists('save_payslip_mail')) {
    function save_payslip_mail($payslip_id, $object, $remarks = "") {
        if($payslip_id && $object ) {
            return false;
        }
        
        $ci = get_instance();
        $ci->load->model("Payslip_sents_model");

        $data['payslip_id'] = $payslip_id;
        $data['serialized'] = serialize($object);
        $data['remarks'] = $remarks;
        $data['created_by'] = $ci->login_user->id;
        $data['timestamp'] = get_current_utc_time();

        return $ci->Payslip_sents_model->save($data);
    }
}