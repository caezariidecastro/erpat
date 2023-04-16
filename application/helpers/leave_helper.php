<?php

if( !function_exists("get_monthly_leave_credit_earning") ) {
    function get_monthly_leave_credit_earning( $date_hired = false ) {
        $ci = get_instance();
        
        if($date_hired) {

            $current_date = get_my_local_time('Y-m-d');
            $month_diff = get_date_difference_in_months($date_hired, $current_date);
            $month_count = ceil($month_diff);

            $yearly_pto = get_setting("yearly_paid_time_off", 0);
            if($yearly_pto) {
                return $yearly_pto/12;
            } else {
                if($month_count > 0 && $month_count <= 12) {
                    return 0.83; //10 PTO
                } else if($month_count > 12 && $month_count <= 24) {
                    return 1.00; //12 PTO
                } else if($month_count > 24 && $month_count <= 36) {
                    return 1.25; //15 PTO
                } else if($month_count > 36 && $month_count <= 60) {
                    return 1.58; //19 PTO
                } else if($month_count > 60) {
                    return 2.00; //24 PTO
                }
            }
        }

        return 0;
    }
}