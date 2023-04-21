<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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

    function __construct( $ci, $options = array(), $on_debug = false ) {
        $this->ci = $ci;
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

    public function getTotalDuration() {
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

    protected function get_first_break($btime) {
        $break_1st_start = isset($btime[0])?strtotime($btime[0]):null;
        $break_1st_end = isset($btime[1])?strtotime($btime[1]):null;

        if($break_1st_start && $break_1st_end) {
            return convert_seconds_to_hour_decimal( max($break_1st_end-$break_1st_start, 0) );
        }

        return 0;
    }

    protected function get_lunch_break($btime) {
        $break_lunch_start = isset($btime[2])?strtotime($btime[2]):null;
        $break_lunch_end = isset($btime[3])?strtotime($btime[3]):null;

        if($break_lunch_start && $break_lunch_end) {
            return convert_seconds_to_hour_decimal( max($break_lunch_end-$break_lunch_start, 0) );
        }

        return 0;
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

    protected function get_second_break($btime) {
        $break_2nd_start = isset($btime[4])?strtotime($btime[4]):null;
        $break_2nd_end = isset($btime[5])?strtotime($btime[5]):null;
        
        if($break_2nd_start && $break_2nd_end) {
            return convert_seconds_to_hour_decimal( max($break_2nd_end-$break_2nd_start, 0) );
        }

        return 0;
    }

    public function calculate() {

        if( get_setting('attendance_calc_mode') == "complex" ) {

            foreach($this->attendance as $data) {

                //Get the instance of the schedule.
                $cur_sched = $this->ci->Schedule_model->get_details(array(
                    "id" => $data->sched_id,
                    "deleted" => true
                ))->row();

                //Important attendance primary data.
                $from_time = strtotime( convert_date_utc_to_local($data->in_time) );
                $to_time = is_date_exists($data->out_time) ? strtotime( convert_date_utc_to_local($data->out_time) ) : null;

                //Store important attendance metrics.
                $schedule = 0;
                $duration = 0;
                $worked = 0;
                $overtime = 0;
                $nonworked = 0;
                $lates = 0;
                $over = 0;
                $under = 0;
                $bonus = 0;
                $night = 0;
                
                $overtime_trigger = number_with_decimal(max(get_setting('overtime_trigger'), 0));
                $bonuspay_trigger = number_with_decimal(max(get_setting('bonuspay_trigger'), 0));

                $current_schedin = null;
                $current_schedout = null;

                //First! If no schedule make sure to have an actual in and out as official schedule.
                $sched_day_in = convert_date_utc_to_local($data->in_time, 'Y-m-d'); //local
                $sched_in = convert_date_utc_to_local($data->in_time);

                //Second! Get the current day schedule instance based on in time.
                $day_name = convert_date_format($sched_day_in, 'D');  //local
                if( isset( $cur_sched->{strtolower($day_name)} ) && $today_sched = unserialize($cur_sched->{strtolower($day_name)}) ) {
                    $sched_time = convert_time_to_24hours_format( $today_sched['in'] ); //local
                    $sched_in = $sched_day_in .' '. $sched_time; //local
                    $current_schedin = $today_sched;
                }

                //Important variable for metrics.
                $sched_in = strtotime($sched_in); //local

                //Get lates: x = diff_time(in_time, sched_in)
                $lates = convert_seconds_to_hour_decimal( max($from_time-$sched_in, 0) );

                // Pre Bonus Pay
                $pre_bonus = convert_seconds_to_hour_decimal( max($sched_in-$from_time, 0) );
                $pre_bonus = $pre_bonus>=$bonuspay_trigger?$bonuspay_trigger:0;
                $bonus += number_with_decimal($pre_bonus);

                if( $data->out_time ) {
                    //First! If no schedule make sure to have an actual in and out as official schedule.
                    $sched_day_out = convert_date_utc_to_local($data->out_time, 'Y-m-d'); //local
                    $sched_out = convert_date_utc_to_local($data->out_time);

                    //Second! Get the current day schedule instance based on in time.
                    if( isset( $cur_sched->{strtolower($day_name)} ) && $today_sched = unserialize($cur_sched->{strtolower($day_name)}) ) {
                        $sched_time = convert_time_to_24hours_format( $today_sched['out'] ); //local
                        $sched_out = $sched_day_out .' '. $sched_time; //local
                        $current_schedout = $today_sched;
                    }

                    //Important variable for metrics.
                    $sched_out = strtotime($sched_out);

                    //duration, Actual time in hours decimal.
                    $duration = max($to_time-$from_time, 0);

                    //undertime, Get undertime: y = diff_time(sched_end, out_time)
                    $under = convert_seconds_to_hour_decimal( max($sched_out-$to_time, 0) );

                    // Post Bonus Pay
                    $post_bonus = convert_seconds_to_hour_decimal( max($to_time-$sched_out, 0) );
                    $post_bonus = $post_bonus>=$bonuspay_trigger?$bonuspay_trigger:0;
                    $bonus += number_with_decimal($post_bonus);
                    
                    //Get scheduled worked hours: z = diff_time(sched_in, sched_end) - 1 hour 
                    $schedule = convert_seconds_to_hour_decimal( max($sched_out-$sched_in, 0) );
                    $this->hours_per_day = max(($schedule - $this->lunch_break), 8); //by default

                    //Override schedule and hours per day according to schedule.
                    if($current_schedin && $current_schedout) {
                        //Get the hours per day minus the lunch break.
                        if(isset($current_schedin) && isset($current_schedin['enabled_first']) && isset($current_schedin['in_first']) && isset($current_schedin['out_first'])) {
                            $first_start_time = convert_time_to_24hours_format( $current_schedin['in_first'] ); //local
                            $first_start_date = strtotime($sched_day_in .' '. $first_start_time);

                            $first_end_time = convert_time_to_24hours_format( $current_schedin['out_first'] ); //local
                            $first_end_date = strtotime($sched_day_in .' '. $first_end_time);

                            //if IN lunch is PM and out is AM then use the sched day out. else same
                            if( contain_str($current_schedin['in_first'], 'PM') && contain_str($current_schedin['out_first'], 'AM') ) {
                                $first_end_date = strtotime($sched_day_out .' '. $first_end_time);
                            }
                            
                            $this->first_break = convert_seconds_to_hour_decimal( max($first_end_date-$first_start_date, 0) );
                        }

                        //Get the hours per day minus the lunch break.
                        if(isset($current_schedin) && isset($current_schedin['enabled_lunch']) && isset($current_schedin['in_lunch']) && isset($current_schedin['out_lunch'])) {
                            $lunch_start_time = convert_time_to_24hours_format( $current_schedin['in_lunch'] ); //local
                            $lunch_start_date = strtotime($sched_day_in .' '. $lunch_start_time);

                            $lunch_end_time = convert_time_to_24hours_format( $current_schedin['out_lunch'] ); //local
                            $lunch_end_date = strtotime($sched_day_in .' '. $lunch_end_time);
                            //if IN lunch is PM and out is AM then use the sched day out. else same
                            if( contain_str($current_schedin['in_lunch'], 'PM') && contain_str($current_schedin['out_lunch'], 'AM') ) {
                                $lunch_end_date = strtotime($sched_day_out .' '. $lunch_end_time);
                            }
                            
                            $this->lunch_break = convert_seconds_to_hour_decimal( max($lunch_end_date-$lunch_start_date, 0) );
                        }

                        //Get the hours per day minus the lunch break.
                        if(isset($current_schedin) && isset($current_schedin['enabled_second']) && isset($current_schedin['in_second']) && isset($current_schedin['out_second'])) {
                            $second_start_time = convert_time_to_24hours_format( $current_schedin['in_second'] ); //local
                            $second_start_date = strtotime($sched_day_in .' '. $second_start_time);

                            $second_end_time = convert_time_to_24hours_format( $current_schedin['out_second'] ); //local
                            $second_end_date = strtotime($sched_day_in .' '. $second_end_time);
                            
                            //if IN lunch is PM and out is AM then use the sched day out. else same
                            if( contain_str($current_schedin['in_second'], 'PM') && contain_str($current_schedin['out_second'], 'AM') ) {
                                $second_end_date = strtotime($sched_day_out .' '. $second_end_time);
                            }
                            
                            $this->second_break = convert_seconds_to_hour_decimal( max($second_end_date-$second_start_date, 0) );
                        }

                        //Default hours per day
                        $this->hours_per_day = max($schedule - $this->lunch_break, 0);
                    }

                    //BREAKTIME
                    $btime = isset($data->break_time)?unserialize($data->break_time):[];

                    //1ST Overbreak
                    if($this->get_first_break($btime) > $this->second_break) { //Get from encode.
                        $over += ($this->get_first_break($btime)-$this->second_break);
                    }

                    //LUNCH Overbreak 
                    if($this->get_lunch_break($btime) > $this->lunch_break) {
                        $over += max($this->get_lunch_break($btime)-$this->lunch_break, 0);
                    }

                    //2ND Overbreak 
                    if($this->get_second_break($btime) > $this->second_break) {
                        $over += ($this->get_second_break($btime)-$this->second_break);
                    }

                    //Set the NightDiff
                    $night_diff_secs = get_night_differential(
                        convert_date_utc_to_local($data->in_time), 
                        convert_date_utc_to_local($data->out_time)
                    );
                    $break_on_night = $this->get_lunch_nightdiff_overlap($btime);
                    $night = convert_seconds_to_hour_decimal( $night_diff_secs ) - convert_seconds_to_hour_decimal($break_on_night); //deduct the lunch break.

                    //idle, Get non worked hours: a = x+y
                    $nonworked = convert_number_to_decimal( max(($lates+$over+$under), 0) );

                    //Add pre and post OT as regular pay. TODO: Make this configurable.
                    $pre_ot = convert_seconds_to_hour_decimal( max($sched_in-$from_time, 0) );
                    $overtime += $pre_ot>=$overtime_trigger ? $pre_ot:0; //60min greater
                    $post_ot = convert_seconds_to_hour_decimal( max($to_time-$sched_out, 0) );
                    $overtime += $post_ot>=$overtime_trigger ? $post_ot:0; //60min greater
                    
                    //Make sure that if nonworked is non zero deduct.
                    $worked = convert_number_to_decimal( max(($this->hours_per_day-$nonworked), 0) );

                    // Add pre and post bonus pay to worked!
                    $worked += $bonus;

                    //Make sure that worked is zero if zero.
                    $worked = convert_number_to_decimal($worked>0?$worked:0);

                    if($worked <= 0) {
                        $nonworked = convert_number_to_decimal( max($this->hours_per_day, 0) );
                    }

                    if($this->on_debug && $duration == 0) {
                        $duration = 'Invalid';
                        $worked = "Invalid";
                        $overtime = "Invalid";
                        $nonworked = 'Invalid';
                        $lates = 'Invalid';
                        $over = 'Invalid';
                        $under = 'Invalid';
                        $bonus = 'Invalid';
                        $night = 'Invalid';
                    }
                } else {
                    if($this->on_debug) {
                        $duration = 'Pending';
                        $worked = 'Pending';
                        $overtime = "Pending";
                        $nonworked = 'Pending';
                        $over = 'Pending';
                        $under = 'Pending';
                        $bonus = 'Pending';
                        $night = 'Pending';
                    }
                }

                if($this->on_debug && !$current_schedin && !$current_schedout) {
                    $lates = '-';
                    $under = '-';
                }

                $this->attd_data[] = array(
                    "duration" => $duration,
                    "schedule" => $schedule,
                    "worked" => $worked,
                    "overtime" => $overtime,
                    "absent" => $nonworked,
                    "lates" => $lates,
                    "over" => $over,
                    "under" => $under,
                    "bonus" => $bonus,
                    "night" => $night,
                );
            } 

        } else {

            foreach($this->attendance as $data) {
                if(is_date_exists($data->out_time)) {

                    $from_time = strtotime( convert_date_utc_to_local($data->in_time) );
                    $to_time = strtotime( convert_date_utc_to_local($data->out_time) );

                    $schedule = $this->hours_per_day;
                    $duration = convert_seconds_to_hour_decimal(max($to_time-$from_time, 0));

                    if($duration <= 5) {
                        $worked = $duration;
                    } else {
                        $worked = max($duration-$this->lunch_break, 0);
                    }
                    $worked = convert_seconds_to_hour_decimal($worked);

                    $under = $schedule-$worked;
                    $nonworked = $under;

                    $this->attd_data[] = array(
                        "duration" => $duration,
                        "schedule" => $schedule,
                        "worked" => $worked,
                        "absent" => $nonworked,
                        "lates" => 0, //no tracking
                        "over" => 0, //no tracking
                        "under" => $under
                    );
                }
            }
            
        }

        return $this;
    }
}