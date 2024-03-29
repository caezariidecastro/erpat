<?php

/**
 * get user's time zone offset 
 * 
 * @return active users timezone
 */
if (!function_exists('get_timezone_offset')) {

    function get_timezone_offset() {
        $timeZone = new DateTimeZone(get_setting("timezone", "Asia/Manila"));
        $dateTime = new DateTime("now", $timeZone);
        return $timeZone->getOffset($dateTime);
    }

}

/**
 * convert a local time to UTC 
 * 
 * @param string $date
 * @param string $format
 * @return utc date
 */
if (!function_exists('convert_date_local_to_utc')) {

    function convert_date_local_to_utc($date = "", $format = "Y-m-d H:i:s", $add_days = 0, $negative = false) {
        if (!$date) {
            return false;
        }
        //local timezone
        $time_offset = get_timezone_offset() * -1;

        //add time offset
        if($negative) {
            return date($format, strtotime($date . "-$add_days days") + $time_offset);
        } else {
            return date($format, strtotime($date . "+$add_days days") + $time_offset);
        }
    }

}

/**
 * get current utc time
 * 
 * @param string $format
 * @return utc date
 */
if (!function_exists('get_current_utc_time')) {

    function get_current_utc_time($format = "Y-m-d H:i:s") {
        $d = DateTime::createFromFormat("Y-m-d H:i:s", date("Y-m-d H:i:s"));
        $d->setTimeZone(new DateTimeZone("UTC"));
        return $d->format($format);
    }

}

/**
 * convert a UTC time to local timezon as defined on users setting
 * 
 * @param string $date_time
 * @param string $format
 * @return local date
 */
if (!function_exists('convert_date_utc_to_local')) {

    function convert_date_utc_to_local($date_time, $format = "Y-m-d H:i:s", $add_days = 0, $negative = false) {
        $date = new DateTime($date_time . ' +00:00');
        $date->setTimezone(new DateTimeZone(get_setting('timezone')));
        if($negative) {
            $date->sub(new DateInterval("P".$add_days."D"));
        } else {
            $date->add(new DateInterval("P".$add_days."D"));
        }
        return $date->format($format);
    }

}

/**
 * get current users local time
 * 
 * @param string $format
 * @return local date
 */
if (!function_exists('get_my_local_time')) {

    function get_my_local_time($format = "Y-m-d H:i:s") {
        return date($format, strtotime(get_current_utc_time()) + get_timezone_offset());
    }

}

/**
 * convert time string to 24 hours format 
 * 01:00 AM will be converted as 13:00:00 
 * 
 * @param string $time  required time format = 01:00 AM/PM
 * @return 24hrs time
 */
if (!function_exists('convert_time_to_24hours_format')) {

    function convert_time_to_24hours_format($time = "00:00 AM") {
         if (!$time)
            $time = "00:00 AM";

        if (strpos($time, "AM")) {
            $time = trim(str_replace("AM", "", $time));
            $check_time = explode(":", $time);
            if ($check_time[0] == 12) {
                $time = "00:" . get_array_value($check_time, 1);
            }
        } else if (strpos($time, "PM")) {
            $time = trim(str_replace("PM", "", $time));
            $check_time = explode(":", $time);
            if ($check_time[0] > 0 && $check_time[0] < 12) {
                $time = $check_time[0] + 12 . ":" . get_array_value($check_time, 1);
            }
        }
        
        $array_time = explode(":", $time);
        
        $hour = get_array_value($array_time, 0)? get_array_value($array_time, 0): "00";
        $minute = get_array_value($array_time, 1)? get_array_value($array_time, 1): "00";
        $secound = get_array_value($array_time, 2)? get_array_value($array_time, 2): "00";
        
        
        return $hour.":".$minute.":".$secound;
    }

}

/**
 * convert time string to 12 hours format 
 * 13:00:00 will be converted as 01:00 AM
 * 
 * @param string $time  required time format =  00:00:00
 * @return 12hrs time
 */
if (!function_exists('convert_time_to_12hours_format')) {

    function convert_time_to_12hours_format($time = "") {
        if ($time) {
            $am = " AM";
            $pm = " PM";
            if (get_setting("time_format") === "small") {
                $am = " am";
                $pm = " pm";
            }
            $check_time = explode(":", $time);
            $hour = $check_time[0] * 1;
            $minute = get_array_value($check_time, 1) * 1;
            $minute = ($minute < 10) ? "0" . $minute : $minute;

            $second = get_array_value($check_time, 2) * 1;
            if(!$second){
                $second= "00";
            }
            
            
            if ($hour == 0) {
                $time = "12:" . $minute .":" .$second . $am;
            } else if ($hour == 12) {
                $time = $hour . ":" . $minute.":" .$second  . $pm;
            } else if ($hour > 12) {
                $hour = $hour - 12;
                $hour = ($hour < 10) ? "0" . $hour : $hour;
                $time = $hour . ":" . $minute .":" .$second  . $pm;
            } else {
                $hour = ($hour < 10) ? "0" . $hour : $hour;
                $time = $hour . ":" . $minute .":" .$second  . $am;
            }
            return $time;
        }
    }

}

/**
 * prepare a decimal value from a time string
 * 
 * @param string $time  required time format =  00:00:00
 * @return number
 */
if (!function_exists('convert_time_string_to_decimal')) {

    function convert_time_string_to_decimal($time = "00:00:00") {
        $hms = explode(":", $time);
        return $hms[0] + (get_array_value($hms, "1") / 60) + (get_array_value($hms, "2") / 3600);
    }

}

/**
 * prepare a human readable time format from a decimal value of seconds
 * 
 * @param string $seconds
 * @return time
 */
if (!function_exists('convert_seconds_to_time_format')) {

    function convert_seconds_to_time_format($seconds = 0, $addUnits = false) {
        $is_negative = false;
        if ($seconds < 0) {
            $seconds = $seconds * -1;
            $is_negative = true;
        }
        $seconds = $seconds * 1;
        $hours = floor($seconds / 3600);
        $mins = floor(($seconds - ($hours * 3600)) / 60);
        $secs = floor($seconds % 60);

        $hours = ($hours < 10) ? "0" . $hours : $hours;
        $mins = ($mins < 10) ? "0" . $mins : $mins;
        $secs = ($secs < 10) ? "0" . $secs : $secs;

        $string = $hours . ":" . $mins . ":" . $secs;
        if($addUnits) {
            $string = $hours . "h " . $mins . "m " . $secs."s";
        }
        
        if ($is_negative) {
            $string = "-" . $string;
        }
        return $string;
    }

}

/**
 * get seconds form a given time string
 * 
 * @param string $time
 * @return seconds
 */
if (!function_exists('convert_time_string_to_second')) {

    function convert_time_string_to_second($time = "00:00:00") {
        $hms = explode(":", $time);
        return $hms[0] * 3600 + ($hms[1] * 60) + ($hms[2]);
    }

}


/**
 * convert a datetime string to relative time 
 * ex: $date_time = "2015-01-01 23:10:00" will return like this: Today at 23:10 PM
 * 
 * @param string $date_time .. it will be considered as UTC time.
 * @param string $convert_to_local .. to prevent conversion, pass $convert_to_local=false 
 * @return date time
 */
if (!function_exists('format_to_relative_time')) {

    function format_to_relative_time($date_time, $convert_to_local = true, $is_short_date = false) {
        if ($convert_to_local) {
            $date_time = convert_date_utc_to_local($date_time);
        }

        $target_date = new DateTime($date_time);
        $now = new DateTime();
        $now->setTimezone(new DateTimeZone(get_setting('timezone')));
        $today = $now->format("Y-m-d");
        $date = "";
        $short_date = "";
        if ($now->format("Y-m-d") == $target_date->format("Y-m-d")) {
            $date = lang("today_at");   //today
            $short_date = lang("today");
        } else if (date('Y-m-d', strtotime(' -1 day', strtotime($today))) === $target_date->format("Y-m-d")) {
            $date = lang("yesterday_at"); //yesterday
            $short_date = lang("yesterday");
        } else {
            $date = format_to_date($date_time);
            $short_date = format_to_date($date_time);
        }
        if ($is_short_date) {
            return $short_date;
        } else {
            if (get_setting("time_format") == "24_hours") {
                return $date . " " . $target_date->format("H:i");
            } else {
                return $date . " " . convert_time_to_12hours_format($target_date->format("H:i:s"));
            }
        }
    }

}

/**
 * convert a datetime string to date format as defined on settings
 * ex: $date_time = "2015-01-01 23:10:00" will return like this: Today at 23:10 PM
 * 
 * @param string $date_time .. it will be considered as UTC time.
 * @param string $convert_to_local .. to prevent conversion, pass $convert_to_local=false 
 * @return date
 */
if (!function_exists('format_to_date')) {

    function format_to_date($date_time, $convert_to_local = true) {
        if (!$date_time) {
            return "";
        }

        if ($convert_to_local) {
            $date_time = convert_date_utc_to_local($date_time);
        }
        $target_date = new DateTime($date_time);
        return $target_date->format(get_setting('date_format'));
    }

}

/**
 * convert a datetime string to 12 hours time format
 * 
 * @param string $date_time .. it will be considered as UTC time.
 * @param string $convert_to_local .. to prevent conversion, pass $convert_to_local=false 
 * @return time
 */
if (!function_exists('format_to_time')) {

    function format_to_time($date_time, $convert_to_local = true) {
        if ($convert_to_local) {
            $date_time = convert_date_utc_to_local($date_time);
        }
        $target_date = new DateTime($date_time);

        if (get_setting("time_format") == "24_hours") {
            return $target_date->format("H:i");
        } else {
            return convert_time_to_12hours_format($target_date->format("H:i:s"));
        }
    }

}

/**
 * convert a datetime string to datetime format as defined on settings
 * 
 * @param string $date_time .. it will be considered as UTC time.
 * @param string $convert_to_local .. to prevent conversion, pass $convert_to_local=false 
 * @return date time
 */
if (!function_exists('format_to_datetime')) {

    function format_to_datetime($date_time, $convert_to_local = true) {
        if ($convert_to_local) {
            $date_time = convert_date_utc_to_local($date_time);
        }
        $target_date = new DateTime($date_time);
        $date = $target_date->format(get_setting('date_format'));

        if (get_setting("time_format") == "24_hours") {
            return $date . " " . $target_date->format("H:i");
        } else {
            return $date . " " . convert_time_to_12hours_format($target_date->format("H:i:s"));
        }
    }

}



/**
 * return users local time (today)
 * 
 * @return date
 */
if (!function_exists('get_today_date')) {

    function get_today_date() {
        return date("Y-m-d", strtotime(get_my_local_time()));
    }

}


/**
 * return users local time (tomorrow)
 * 
 * @return date
 */
if (!function_exists('get_tomorrow_date')) {

    function get_tomorrow_date() {
        $today = get_today_date();
        return date('Y-m-d', strtotime($today . ' + 1 days'));
    }

}

/**
 * add days with a given date
 * 
 * $date should be Y-m-d
 * $period_type should be days/months/years/weeks
 * 
 * @return date
 */
if (!function_exists('add_period_to_date')) {

    function add_period_to_date($date, $no_of = 0, $period_type = "days") {
        return date('Y-m-d', strtotime("+$no_of $period_type", strtotime($date)));
    }

}

/**
 * subtract days from a given date
 * 
 * $date should be Y-m-d
 * $period_type should be days/months/years/weeks
 * 
 * @return date
 */
if (!function_exists('subtract_period_from_date')) {

    function subtract_period_from_date($date, $no_of = 0, $period_type = "days") {
        return date('Y-m-d', strtotime("-$no_of $period_type", strtotime($date)));
    }

}


/**
 * get date difference in days
 * 
 * $start_date && $end_date should be Y-m-d format
 * 
 * @return difference in days
 */
if (!function_exists('get_date_difference_in_days')) {

    function get_date_difference_in_days($start_date, $end_date) {

        $start = new DateTime($start_date);
        $end = new DateTime($end_date);

        return $end->diff($start)->format("%a");
    }

}

/**
 * get date difference in month
 * 
 * $start_date && $end_date should be Y-m-d format
 * 
 * @return difference in month
 */
if (!function_exists('get_date_difference_in_months')) {

    function get_date_difference_in_months($start_date, $end_date) {

        $start = new DateTime($start_date);
        $end = new DateTime($end_date);

        return $end->diff($start)->format("%a")/30.417;
    }

}

/**
 * is online user? if the last online <= 1 minute then we'll assume that the user is online
 * 
 * $start_date && $end_date 
 * 
 * @return boolean
 */
if (!function_exists('is_online_user')) {

    function is_online_user($last_online = "") {

        if (!$last_online) {
            //if we don't get any last online status that means the user is offline
            return false;
        } else {
            //if last online <= 1 minute then we'll assume that the user is online

            $now = get_my_local_time();
            $last_online = convert_date_utc_to_local($last_online);

            $diff_seconds = abs(strtotime($now) - strtotime($last_online));
            if ($diff_seconds <= 60) {
                return true;
            } else {
                return false;
            }
        }
    }

}



/**
 * Check if the date string is not empty.
 * 
 * $date 
 * 
 * @return boolean
 */
if (!function_exists('is_date_exists')) {

    function is_date_exists($date = "") {

        if (!$date || $date == "NULL" || is_null($date) || $date == "0000-00-00") {
            return false;
        } else {
            return true;
        }
    }

}

//convert to hours from humanize data
if (!function_exists('convert_humanize_data_to_hours')) {

    function convert_humanize_data_to_hours($hours = "") {
        require_once(APPPATH . "third_party/php-duration-master/init.php");

        $duration = \Init_duration::init($hours);
        $hours = $duration->toMinutes(null, 0); //convert in minutes
        $hours = $hours / 60; //final hours format

        return round($hours, 2);
    }

}

//convert humanize data to hours
if (!function_exists('convert_hours_to_humanize_data')) {

    function convert_hours_to_humanize_data($hours = "") {
        require_once(APPPATH . "third_party/php-duration-master/init.php");

        $duration = \Init_duration::init($hours . "h");
        $minutes = round($duration->toMinutes(), 0); //remove decimals from minutes first

        $duration = \Init_duration::init($minutes * 60);
        return $duration->humanize();
    }

}

//Convert time to hour in decimal.
if (!function_exists('convert_seconds_to_hour_decimal')) {

    function convert_seconds_to_hour_decimal($seconds = 0) {
        $is_negative = false;
        if ($seconds < 0) {
            $seconds = $seconds * -1;
            $is_negative = true;
        }
        $seconds = $seconds * 1;
        $hours = floor($seconds / 3600);
        $mins = floor(($seconds - ($hours * 3600)) / 60);
        $secs = floor($seconds % 60);

        $total = $hours + (($mins+($secs/60))/60);
        $total = number_format($total, 2, '.', ',');
        if ($is_negative) {
            $total = -$total;
        }
        return strval( convert_number_to_decimal($total) );
    }

}

if (!function_exists('add_day_to_datetime')) {

    function add_day_to_datetime($datetime, $days = 0, $format = "Y-m-d H:i:s") {
        $date = new DateTime($datetime);
        $date->add(new DateInterval("P".$days."D"));
       return $date->format($format);
    }

    
}

if (!function_exists('sub_day_to_datetime')) {

    function sub_day_to_datetime($datetime, $days = 0, $format = "Y-m-d H:i:s") {
        $date = new DateTime($datetime);
        $date->sub(new DateInterval("P".$days."D"));
       return $date->format($format);
    }

}

if (!function_exists('pluralize_text')) {

    function pluralize_text( $count, $text )
    {
        return $count . ( ( $count == 1 ) ? ( " $text" ) : ( " ${text}s" ) );
    }

}

if (!function_exists('last_online_text')) {

    function last_online_text($last_online) {

        if(!$last_online) {
            return "Never";
        }

        //get Date diff as intervals 
        $d1 = new DateTime( convert_date_utc_to_local($last_online) );
        $d2 = new DateTime( get_my_local_time() );
        $interval = $d1->diff($d2);

        $diff_seconds = $interval->s;
        $diff_minutes = $interval->i;
        $diff_hours   = $interval->h;
        $diff_days    = $interval->d;
        $diff_weeks    = floor($interval->d/7);
        $diff_months  = $interval->m;
        $diff_years   = $interval->y;

        $suffix = ( $interval->invert ? ' ago' : '' );

        if ( $interval->y >= 1 ) {
            return pluralize_text( $interval->y, 'year' ) . $suffix;
        } else if ( $interval->m >= 1 ) {
            return pluralize_text( $interval->m, 'month' ) . $suffix;
        } else if ( $interval->d >= 1 ) {
            return pluralize_text( $interval->d, 'day' ) . $suffix;
        } else if ( $interval->h >= 1 ) {
            return pluralize_text( $interval->h, 'hour' ) . $suffix;
        } else if ( $interval->i >= 1 ) {
            return pluralize_text( $interval->i, 'minute' ) . $suffix;
        } else if ( $interval->s >= 1 ) {
            return pluralize_text( $interval->s, 'second' ) . $suffix;
        } else {
            return "Online";
        }
    }

}

if (!function_exists('get_day_name')) {

    function get_day_name($day_short) {
        
        switch($day_short) {
            case "mon":
                return "monday";
                break;
            case "tue":
                return "tuesday";
                break;
            case "wed":
                return "wednesday";
                break;
            case "thu":
                return "thursday";
                break;
            case "fri":
                return "friday";
                break;
            case "sat":
                return "saturday";
                break;
            case "sun":
                return "sunday";
                break;
            default:
                return "unknown";
                break;
        }
    }
    
}


if (!function_exists('get_sched_title')) {

    function get_sched_title($key) {
        
        switch($key) {
            case "":
                return "Regular";
                break;
            case "_first":
                return "1st Break";
                break;
            case "_lunch":
                return "Lunch Break";
                break;
            case "_second":
                return "2nd Break";
                break;
            default:
                return "unknown";
                break;
        }
    }
    
}

/**
 * convert timestamp to date with timezone.
 * 
 * @param string $format
 * @return local date
 */
if (!function_exists('convert_timestamp_to_date')) {

    function convert_timestamp_to_date($timestamp, $format = "Y-m-d H:i:s") {
        return date($format, $timestamp);
    }

}

/**
 * Convert the date to new specified format.
 * 
 * @param string $date
 * @param string $format
 * @return datetime date
 */
if (!function_exists('convert_date_format')) {
    function convert_date_format($date = "", $format = "Y-m-d H:i:s") {
        if (!$date) {
            return false;
        }

        return date($format, strtotime($date));
    }
}


if (!function_exists('get_time_overlap_seconds')) {
    function get_time_overlap_seconds($start_one, $end_one, $start_two, $end_two) {

        $start_one = new DateTime($start_one);
        $end_one = new DateTime($end_one);
        $start_two = new DateTime($start_two);
        $end_two = new DateTime($end_two);

        // Check if there is an overlap
        if ($start_one < $end_two && $start_two < $end_one) {
            // Calculate the overlapping time
            $overlapStart = max($start_one, $start_two);
            $overlapEnd = min($end_one, $end_two);
            $overlap = $overlapEnd->diff($overlapStart);

            return ($overlap->days * 24 * 60 * 60) + ($overlap->h * 60 * 60) + ($overlap->i * 60) + $overlap->s;
        }
     
        return 0; //Return 0 if there is no overlap
    }
}

if (!function_exists('is_within_range')) {
    function is_within_range($datetime, $start = '00:00', $end = '03:00') {
        $dt = new DateTime($datetime);
        $dt_time = $dt->format('H:i');
    
        if ($dt_time >= $start && $dt_time <= $end) {
            return true;
        } else {
            return false;
        }
    }
}