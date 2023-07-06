<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class DailyLog {

    //first break in datetime.
    protected $start_first_break = null;
    protected $end_first_break = null;

    //lunch break in datetime.
    protected $start_lunch_break = null;
    protected $end_lunch_break = null;

    //second break in datetime.
    protected $start_second_break = null;
    protected $end_second_break = null;

    //extra break in datetime.
    protected $start_extra_break = null;
    protected $end_extra_break = null;

    private function isDatetime( $datetime ) {
        if(strtotime($datetime) !== false) {
            return true;
        }

        return false;
    }

    private function setLunchBreak( $breakdata, $lunchPosition = 0 ) {

        if( $lunchPosition == 1 ) { //ob
            $this->start_lunch_break = $breakdata[0];
            $this->end_lunch_break = $breakdata[1];

            $this->start_second_break = $breakdata[2];
            $this->end_second_break = $breakdata[3];

            $this->start_extra_break = $breakdata[4];
            $this->end_extra_break = $breakdata[5];
        } else if( $lunchPosition == 2 ) { //normal
            $this->start_lunch_break = $breakdata[2];
            $this->end_lunch_break = $breakdata[3];

            $this->start_first_break = $breakdata[0];
            $this->end_first_break = $breakdata[1];

            $this->start_second_break = $breakdata[4];
            $this->end_second_break = $breakdata[5];
        } else if( $lunchPosition == 3 ) {
            $this->start_lunch_break = $breakdata[4];
            $this->end_lunch_break = $breakdata[5];

            $this->start_first_break = $breakdata[0];
            $this->end_first_break = $breakdata[1];

            $this->start_second_break = $breakdata[2];
            $this->end_second_break = $breakdata[3];
        }

        if( count($breakdata) >= 8 && $this->isDatetime($breakdata[6]) && $this->isDatetime($breakdata[7]) ) {
            $this->start_extra_break = $breakdata[6];
            $this->end_extra_break = $breakdata[7];
        }
    }

    // We need to call this to process.
    public function process($breakdata) {
        // If not all not a v2 then fallback all to v1.

        if( is_array($breakdata) ) {

            $hasVersion2 = false; // by default version.
            
            foreach($breakdata as $index => $date) {

                if (strpos("|", $date) !== false) { //v2
                    $fragment = explode("|", $date);

                    if( count($fragment) >= 2 && $this->isDatetime($fragment[0]) ) {
                        if( $fragment[1] === "start_first" ) {
                            $this->start_first_break = $fragment[0];
                            $hasVersion2 = true;
                        } else if( $fragment[1] === "end_first" ) {
                            $this->end_first_break[1] = $fragment[0];
                            $hasVersion2 = true;
                        } else if( $fragment[1] === "start_lunch" ) {
                            $this->start_lunch_break = $fragment[0];
                            $hasVersion2 = true;
                        } else if( $fragment[1] === "end_lunch" ) {
                            $this->end_lunch_break = $fragment[0];
                            $hasVersion2 = true;
                        } else if( $fragment[1] === "start_second" ) {
                            $this->start_second_break = $fragment[0];
                            $hasVersion2 = true;
                        } else if( $fragment[1] === "end_second" ) {
                            $this->end_second_break = $fragment[0];
                            $hasVersion2 = true;
                        } else if( $fragment[1] === "start_extra" ) {
                            $this->start_extra_break = $fragment[0];
                            $hasVersion2 = true;
                        } else if( $fragment[1] === "end_extra" ) {
                            $this->end_extra_break = $fragment[0];
                            $hasVersion2 = true;
                        }
                    }
                }
            }

            if($hasVersion2 == false) { //force all to be v1.

                $rank = 0;
                $lunch_break = 0; // greater than 15m and find the biggest

                $first_log = 0;
                if( count($breakdata) >= 2 && $this->isDatetime($breakdata[0]) && $this->isDatetime($breakdata[1]) ) {
                    $first_log = strtotime($breakdata[1])-strtotime($breakdata[0]);
                    $first_log = num_limit($first_log);

                    if($first_log >= 945 && $first_log > $lunch_break) {
                        $lunch_break = $first_log; 
                        $rank = 1;
                    }
                }

                $second_log = 0;
                if( count($breakdata) >= 4 && $this->isDatetime($breakdata[2]) && $this->isDatetime($breakdata[3]) ) {
                    $second_log = strtotime($breakdata[3])-strtotime($breakdata[2]);
                    $second_log = num_limit($second_log);

                    if($second_log >= 945 && $second_log > $lunch_break) {
                        $lunch_break = $second_log; 
                        $rank = 2;
                    }
                }

                $third_log = 0;
                if( count($breakdata) >= 6 && $this->isDatetime($breakdata[4]) && $this->isDatetime($breakdata[5]) ) {
                    $third_log = strtotime($breakdata[5])-strtotime($breakdata[4]);
                    $third_log = num_limit($third_log);

                    if($third_log >= 945 && $third_log > $lunch_break) {
                        $lunch_break = $third_log; 
                        $rank = 3;
                    }
                }

                $fourth_log = 0;
                if( count($breakdata) >= 8 && $this->isDatetime($breakdata[6]) && $this->isDatetime($breakdata[7]) ) {
                    $fourth_log = strtotime($breakdata[7])-strtotime($breakdata[6]);
                    $fourth_log = num_limit($fourth_log);

                    if($fourth_log >= 945 && $fourth_log > $lunch_break) {
                        $lunch_break = $fourth_log; 
                        $rank = 4;
                    }
                }

                //if still zero and logs first and second has value then get the highest of the 3.
                if($lunch_break === 0 && $first_log && $second_log && $third_log) {
                    $lunch_break = max( array($first_log, $second_log, $third_log) );

                    if ($first_log > $second_log && $first_log > $third_log) {
                        $lunch_break = $first_log;
                        $rank = 1;
                    }

                    if ($second_log > $first_log && $second_log > $third_log) {
                        $lunch_break = $second_log;
                        $rank = 2;
                    }

                    if ($third_log > $second_log && $third_log > $first_log) {
                        $lunch_break = $third_log;
                        $rank = 3;
                    }
                }

                $this->setLunchBreak($breakdata, $rank);
            }
        }

        return $this;
    }
    
    /**
     * Get the duration for the $break_type
     *
     * @param  mixed $break_type
     * @return number
     */
    public function getDuration( $break_type = "lunch", $hourDecimal = false ) {
        $duration = 0;

        if( $break_type == "lunch" ) {
            if( $this->isDatetime($this->start_lunch_break) && $this->isDatetime($this->end_lunch_break) ) {
                $duration = strtotime($this->end_lunch_break)-strtotime($this->start_lunch_break);
            }
        } else if( $break_type == "first" ) {
            if( $this->isDatetime($this->start_first_break) && $this->isDatetime($this->end_first_break) ) {
                $duration = strtotime($this->end_first_break)-strtotime($this->start_first_break);
            }
        } else if( $break_type == "second" ) {
            if( $this->isDatetime($this->start_second_break) && $this->isDatetime($this->end_second_break) ) {
                $duration = strtotime($this->end_second_break)-strtotime($this->start_second_break);
            }
        } else if( $break_type == "extra" ) {
            if( $this->isDatetime($this->start_extra_break) && $this->isDatetime($this->end_extra_break) ) {
                $duration = strtotime($this->end_extra_break)-strtotime($this->start_extra_break);
            }
        }
            
        return $hourDecimal == true ? convert_seconds_to_hour_decimal($duration) : $duration;
    }
        
    /**
     * Check if the $break_type is currently in progress.
     *
     * @param  mixed $break_type
     * @return bool
     */
    public function isPending( $break_type ) {
        if( isset($this->start_first_break) && !isset($this->end_first_break) && $break_type == "first" ) {
            return true;
        }

        if( isset($this->start_lunch_break) && !isset($this->end_lunch_break) && $break_type == "lunch" ) {
            return true;
        }

        if( isset($this->start_second_break) && !isset($this->end_second_break) && $break_type == "second" ) {
            return true;
        }

        if( isset($this->start_extra_break) && !isset($this->end_extra_break) && $break_type == "extra" ) {
            return true;
        }

        return false;
    }
}

class BioMeet {

    protected $ci = null;
    protected $on_debug = false;

    protected $sched_hours = 40.00;
    protected $hours_per_day = 8.00;
    protected $first_break = 0.25;
    protected $lunch_break = 1.00;
    protected $second_break = 0.25;
    protected $attendance = [];
    protected $attd_data = [];

    protected $break_data = null;

    function __construct( $ci, $options = array(), $on_debug = false ) {
        $this->ci = $ci;
        $this->ci->load->helper('utility');
        $this->on_debug = $on_debug;

        $this->hours_per_day = isset($options['hours_per_day']) && is_numeric($options['hours_per_day'])?
            $options['hours_per_day']:$this->hours_per_day;
        return $this;
    }

    function setSchedHour( $hours ) {
        $this->sched_hours = $hours;
        return $this;
    }

    function setAttendance( $attd_list ) {
        $this->attendance = $attd_list;
        return $this;
    }
    
    function addAttendance( $attd_obj ) {
        $this->attendance[] = $attd_obj;
        return $this;
    }

    public function getTotalSchedule() {
        // $total = 0;
        // foreach($this->attd_data as $data) {
        //     if( is_numeric($data['schedule']) ) {
        //         $total += $data['schedule'];
        //     }
        // }
        return convert_number_to_decimal($this->sched_hours);
    }

    public function getTotalDuration($in_time = null) {
        if( isset($in_time) ) {
            $duration = strtotime(get_current_utc_time())-strtotime($in_time);
            return convert_seconds_to_time_format($duration);
        }
        $total = 0;
        foreach($this->attd_data as $data) {
            if( is_numeric($data['duration']) ) {
                $total += $data['duration'];
            }
        }
        return convert_seconds_to_time_format($total);
    }

    public function getTotalWork() {
        $total = 0;
        foreach($this->attd_data as $data) {
            if( is_numeric($data['worked']) ) {
                $total += $data['worked'];
            }
        }
        return convert_number_to_decimal($total);
    }

    public function getTotalOvertime() {
        $total = 0;
        foreach($this->attd_data as $data) {
            if( is_numeric($data['overtime']) ) {
                $total += $data['overtime'];
            }
        }
        return convert_number_to_decimal($total);
    }

    public function getTotalAbsent() {
        $total = 0;
        foreach($this->attd_data as $data) {
            if( is_numeric($data['absent']) ) {
                $total += $data['absent'];
            }
        }
        return convert_number_to_decimal($total);
    }

    public function getTotalLates() {
        $total = 0;
        foreach($this->attd_data as $data) {
            if( is_numeric($data['lates']) ) {
                $total += $data['lates'];
            }
        }
        return convert_number_to_decimal($total);
    }

    public function getTotalOverbreak() {
        $total = 0;
        foreach($this->attd_data as $data) {
            if( is_numeric($data['over']) ) {
                $total += $data['over'];
            }
        }
        return convert_number_to_decimal($total);
    }

    public function getTotalUndertime() {
        $total = 0;
        foreach($this->attd_data as $data) {
            if( is_numeric($data['under']) ) {
                $total += $data['under'];
            }
        }
        return convert_number_to_decimal($total);
    }

    public function getTotalBonuspay() {
        $total = 0;
        foreach($this->attd_data as $data) {
            if( is_numeric($data['bonus']) ) {
                $total += $data['bonus'];
            }
        }
        return convert_number_to_decimal($total);
    }

    public function getTotalNightpay() {
        $total = 0;
        foreach($this->attd_data as $data) {
            if( is_numeric($data['night']) ) {
                $total += $data['night'];
            }
        }
        return convert_number_to_decimal($total);
    }

    protected function get_lunch_nightdiff_overlap($btime) {
        $break_lunch_start = isset($btime[2])?strtotime($btime[2]):null;
        $break_lunch_end = isset($btime[3])?strtotime($btime[3]):null;

        if( isset($break_lunch_start) && isset($break_lunch_end)) {
            return get_night_differential(
                convert_date_format($break_lunch_start), 
                convert_date_format($break_lunch_end)
            );
        }

        return 0;
    }

    private function getScheduleObj( $data ) {

        $cur_sched = $this->ci->Schedule_model->get_details(array(
            "id" => $data->sched_id,
            "deleted" => true
        ))->row();
        
        if( isset($cur_sched) ) {

            $current_schedin = null;
            $current_schedout = null;

            $first_start_date = null;
            $first_end_date = null;

            $lunch_start_date = null;
            $lunch_end_date = null;

            $second_start_date = null;
            $second_end_date = null;
            $need_adjust = false;

            //First! If no schedule make sure to have an actual in and out as official schedule.
            $sched_day_in = convert_date_utc_to_local($data->in_time, 'Y-m-d'); //local
            $sched_in = convert_date_utc_to_local($data->in_time);

            //Second! Get the current day schedule instance based on in time.
            $day_name = convert_date_format($sched_day_in, 'D');  //local
            if( isset( $cur_sched->{strtolower($day_name)} ) && $today_sched = unserialize($cur_sched->{strtolower($day_name)}) ) {
                $sched_time = convert_time_to_24hours_format( $today_sched['in'] ); //local

                // Check if time start local is 00:00:00 to 00:03:00 then last day.
                // TODO: Have the start and end check to settings and also reconsider.
                $current_time = strtotime($sched_time); 
                $start_time = strtotime('00:00'); // 12 AM (midnight)
                $end_time = strtotime('03:00'); // 3 AM
                if ($current_time >= $start_time && $current_time <= $end_time) {
                    $shift = convert_date_utc_to_local($data->in_time, 'A');
                    if($shift === "PM") { //if in is pm then
                        $sched_day_in = add_period_to_date($sched_day_in, 1);
                        $need_adjust = true;
                    }
                }

                $sched_in = $sched_day_in .' '. $sched_time; //local
                $current_schedin = $today_sched;
            }

            //First! If no schedule make sure to have an actual in and out as official schedule.
            $sched_day_out = convert_date_utc_to_local($data->in_time, 'Y-m-d'); //local
            $sched_out = convert_date_utc_to_local($data->in_time);

            //Second! Get the current day schedule instance based on in time.
            if( isset( $cur_sched->{strtolower($day_name)} ) && $today_sched = unserialize($cur_sched->{strtolower($day_name)}) ) {
                $sched_time = convert_time_to_24hours_format( $today_sched['out'] ); //local
                if( strpos($today_sched['in'], "PM") !== false && strpos($today_sched['out'], "AM") !== false) {
                    $sched_day_out = add_day_to_datetime(convert_date_utc_to_local($data->in_time), 1, "Y-m-d");
                }
                $sched_out = $sched_day_out .' '. $sched_time; //local
                $current_schedout = $today_sched;

                if($need_adjust) {
                    $sched_out = add_day_to_datetime( $sched_out, 1);
                }
            }

            if($current_schedin && $current_schedout) {

                //Get the hours per day minus the lunch break.
                if(isset($current_schedin) && isset($current_schedin['enabled_first']) && isset($current_schedin['in_first']) && isset($current_schedin['out_first'])) {
                    $first_start_time = convert_time_to_24hours_format( $current_schedin['in_first'] ); //local
                    $first_start_date = $sched_day_in .' '. $first_start_time;

                    $first_end_time = convert_time_to_24hours_format( $current_schedin['out_first'] ); //local
                    $first_end_date = $sched_day_in .' '. $first_end_time;

                    //if IN lunch is PM and out is AM then use the sched day out. else same
                    if( contain_str($current_schedin['in_first'], 'PM') && contain_str($current_schedin['out_first'], 'AM') ) {
                        $first_end_date = $sched_day_out .' '. $first_end_time;
                    }
                }

                //Get the hours per day minus the lunch break.
                if(isset($current_schedin) && isset($current_schedin['enabled_lunch']) && isset($current_schedin['in_lunch']) && isset($current_schedin['out_lunch'])) {
                    $lunch_start_time = convert_time_to_24hours_format( $current_schedin['in_lunch'] ); //local
                    $lunch_start_date = $sched_day_in .' '. $lunch_start_time;

                    $lunch_end_time = convert_time_to_24hours_format( $current_schedin['out_lunch'] ); //local
                    $lunch_end_date = $sched_day_in .' '. $lunch_end_time;

                    //if IN lunch is PM and out is AM then use the sched day out. else same
                    if( contain_str($current_schedin['in_lunch'], 'PM') && contain_str($current_schedin['out_lunch'], 'AM') ) {
                        $lunch_end_date = $sched_day_out .' '. $lunch_end_time;
                    }
                }

                //Get the hours per day minus the lunch break.
                if(isset($current_schedin) && isset($current_schedin['enabled_second']) && isset($current_schedin['in_second']) && isset($current_schedin['out_second'])) {
                    $second_start_time = convert_time_to_24hours_format( $current_schedin['in_second'] ); //local
                    $second_start_date = $sched_day_in .' '. $second_start_time;

                    $second_end_time = convert_time_to_24hours_format( $current_schedin['out_second'] ); //local
                    $second_end_date = $sched_day_in .' '. $second_end_time;
                    
                    //if IN lunch is PM and out is AM then use the sched day out. else same
                    if( contain_str($current_schedin['in_second'], 'PM') && contain_str($current_schedin['out_second'], 'AM') ) {
                        $second_end_date = $sched_day_out .' '. $second_end_time;
                    }
                }

                if(strtotime($sched_in) !== false && strtotime($sched_out) !== false) {
                    $time_duration = num_limit(strtotime($sched_out)-strtotime($sched_in));
                }

                if(strtotime($first_start_date) !== false && strtotime($first_end_date) !== false) {
                    $first_duration = num_limit(strtotime($first_end_date)-strtotime($first_start_date));
                }

                if(strtotime($lunch_start_date) !== false && strtotime($lunch_end_date) !== false) {
                    $lunch_duration = num_limit(strtotime($lunch_end_date)-strtotime($lunch_start_date));
                }
                
                if(strtotime($second_start_date) !== false && strtotime($second_end_date) !== false) {
                    $second_duration = num_limit(strtotime($second_end_date)-strtotime($second_start_date));
                }

                return array(
                    "have_schedule" => (isset($sched_in) && isset($sched_out) ? true:false),
                    
                    "start_time" => $sched_in,
                    "end_time" => $sched_out,
                    "time_duration" => intval($time_duration),

                    "start_first" => $first_start_date,
                    "end_first" => $first_end_date,
                    "first_duration" => intval($first_duration),

                    "start_lunch" => $lunch_start_date,
                    "end_lunch" => $lunch_end_date,
                    "lunch_duration" => intval($lunch_duration),

                    "start_second" => $second_start_date,
                    "start_second" => $second_end_date,
                    "second_duration" => intval($second_duration),
                );
            }
        } 
        
        return false;
    }

    public function calculate() {

        foreach($this->attendance as $data) {
            if(is_date_exists($data->out_time)) {

                $from_time = strtotime( convert_date_utc_to_local($data->in_time) );
                $to_time = strtotime( convert_date_utc_to_local($data->out_time) );
                $actual_duration = num_limit($to_time-$from_time);

                if( $data->log_type === "overtime") {
                    
                    $breaklog = isset($data->break_time)?unserialize($data->break_time):[];
                    $breakobj = (new DailyLog())->process($breaklog);

                    $over_trigger = 0.5; //TODO: Set this on config. Note: in hours
                    $break = $breakobj->getDuration('lunch', true);
                    $lunch = 0;
                    if($break > $over_trigger) { 
                        $lunch = $over_trigger;
                        $over = num_limit($break-$over_trigger);
                    }
                    
                    $night_diff_secs = get_night_differential( convert_date_utc_to_local($data->in_time), convert_date_utc_to_local($data->out_time) );
                    $night = num_limit(convert_seconds_to_hour_decimal( $night_diff_secs ) - $lunch, 8); 

                    $overtime = convert_seconds_to_hour_decimal( num_limit(($to_time-$from_time)-$lunch, $actual_duration) );

                    $this->attd_data[] = array(
                        "duration" => $actual_duration,
                        "schedule" => 0,
                        "worked" => 0,
                        "absent" => 0,
                        "overtime" => $overtime,
                        "bonus" => 0,
                        "night" => $night,
                        "lates" => 0,
                        "over" => $over,
                        "under" => 0
                    );
                    continue;
                }
                
                //We now require schedule for attendance.
                $schedobj = $this->getScheduleObj($data);
                if( !isset($schedobj['have_schedule']) ) {
                    $this->attd_data[] = array(
                        "duration" => $actual_duration,
                        "schedule" => 0,
                        "worked" => 0,
                        "absent" => 0,
                        "overtime" => 0,
                        "bonus" => 0,
                        "night" => 0,
                        "lates" => 0,
                        "over" => 0,
                        "under" => 0
                    );
                    continue;
                } //we required schedule in order to compute for attendance.

                $breaklog = isset($data->break_time)?unserialize($data->break_time):[];
                $breakobj = (new DailyLog())->process($breaklog);

                $sched_duration = convert_seconds_to_hour_decimal($schedobj["time_duration"]);
                
                $lunch_sched = convert_seconds_to_hour_decimal($schedobj["lunch_duration"]);
                $lunch_log = $breakobj->getDuration('lunch', true);

                $payable = num_limit($sched_duration-$lunch_sched);
                
                $under = convert_seconds_to_hour_decimal( num_limit(strtotime($schedobj["end_time"])-$to_time, $payable) );
                $lates = convert_seconds_to_hour_decimal( num_limit($from_time-strtotime($schedobj["start_time"]), $payable) );
                if( $lunch_sched > 0 ) {
                    if( $lunch_log > 0 ) {
                        $lunch = num_limit($lunch_log, $payable, $lunch_sched);
                    } else {
                        $under = num_limit( $under-$lunch_sched, $payable);
                    }
                    
                    $over = num_limit($lunch_log-$lunch_sched);
                }
                
                $nonworked = $lates + $over + $under;
                $worked = num_limit(convert_seconds_to_hour_decimal($actual_duration)-$nonworked, $payable);

                $pre_excess = convert_seconds_to_hour_decimal( num_limit($to_time-strtotime($schedobj["end_time"])) );
                $post_excess = convert_seconds_to_hour_decimal( num_limit(strtotime($schedobj["start_time"])-$from_time) );
                
                $overtime_trigger = number_with_decimal( num_limit(get_setting('overtime_trigger')) );
                if( $pre_excess > $overtime_trigger ) {
                    $overtime += $pre_excess;
                }
                if( $post_excess > $overtime_trigger ) {
                    $overtime += $post_excess;
                }

                $bonuspay_trigger = number_with_decimal( num_limit(get_setting('bonuspay_trigger')) );
                if( $pre_excess > $bonuspay_trigger ) {
                    $bonus += $pre_excess;
                }
                if( $post_excess > $bonuspay_trigger ) {
                    $bonus += $post_excess;
                }          

                $night_diff_secs = get_night_differential( $schedobj["start_time"], $schedobj["end_time"] );
                $night = num_limit(convert_seconds_to_hour_decimal( $night_diff_secs ) - $nonworked, 8); //TODO: 8, Get from config.

                //TODO: Process overbreak for personal.
                $this->attd_data[] = array(
                    "duration" => $actual_duration,
                    "schedule" => $sched_duration,
                    "worked" => $worked,
                    "absent" => $nonworked,
                    "overtime" => $overtime,
                    "night" => $night,
                    "bonus" => $bonus,
                    "lates" => $lates,
                    "over" => $over,
                    "under" => $under
                );
            }
        }
            
        return $this;
    }
}