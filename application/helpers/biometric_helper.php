<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class BioMeet {

    protected $ci = null;
    protected $on_debug = false;

    protected $hours_per_day = 8.00;
    protected $lunch_break = 1.00;
    protected $attendance = [];
    protected $attd_data = [];

    function __construct( $ci, $options = array(), $on_debug = false ) {
        $this->ci = $ci;
        $this->on_debug = $on_debug;

        $this->hours_per_day = isset($options['hours_per_day']) && is_numeric($options['hours_per_day'])?
            $options['hours_per_day']:$this->hours_per_day;
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
        $total = 0;
        foreach($this->attd_data as $data) {
            if( is_numeric($data['schedule']) ) {
                $total += $data['schedule'];
            }
        }
        return convert_number_to_decimal($total);
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

    public function calculate() {

        foreach($this->attendance as $data) {

            $schedule = 0;
            $duration = 0;
            $worked = 0;
            $nonworked = 0;
            $lates = 0;
            $over = 0;
            $under = 0;
            
            //Important attendance triggers.
            $from_time = strtotime($data->in_time);
            $to_time = is_date_exists($data->out_time) ? 
            strtotime($data->out_time) : null;

            //Get the instance of the schedule.
            $cur_sched = $this->ci->Schedule_model->get_details(array("id" => $data->sched_id))->row();

            //If no schedule make sure to have an actual in and out as official schedule.
            $sched_day = convert_date_utc_to_local($data->in_time, 'Y-m-d'); //local
            $sched_in = $data->in_time;

            //Get the current day schedule instance based on in time.
            $day_name = format_to_custom($data->in_time, 'D'); $has_sched_in = false; $current_schedule = null;
            if( isset( $cur_sched->{strtolower($day_name)} ) && $today_sched = unserialize($cur_sched->{strtolower($day_name)}) ) {
                $sched_time = convert_time_to_24hours_format( $today_sched['in'] ); //local
                $sched_in = convert_date_local_to_utc(  $sched_day .' '. $sched_time );
                $has_sched_in = true;
                $current_schedule = $today_sched;
            }
            $sched_in = strtotime($sched_in);

            //Get lates: x = diff_time(in_time, sched_in)
            $lates = convert_seconds_to_hour_decimal( max($from_time-$sched_in, 0) );

            if( $data->out_time ) {

                //If no schedule make sure to have an actual in and out as official schedule.
                if( !isset($data->sched_id) ) {
                    $sched_day = convert_date_utc_to_local($data->out_time, 'Y-m-d'); //local
                    $sched_out = $data->out_time;
                }

                //Get the current day schedule instance based on in time.
                $day_name = format_to_custom($data->out_time, 'D'); $has_sched_out = false;
                if( isset( $cur_sched->{strtolower($day_name)} ) && $today_sched = unserialize($cur_sched->{strtolower($day_name)}) ) {
                    $sched_time = convert_time_to_24hours_format( $today_sched['out'] ); //local
                    $sched_out = convert_date_local_to_utc(  $sched_day .' '. $sched_time );
                    $has_sched_out = true;
                }
                $sched_out = strtotime($sched_out);

                //Actual time in hours decimal.
                $actual = max($to_time-$from_time, 0);

                //Get undertime: y = diff_time(sched_end, out_time)
                $under = convert_seconds_to_hour_decimal( max($sched_out-$to_time, 0) );
                
                //Override schedule and hours per day according to schedule.
                if($has_sched_in && $has_sched_out) {
                    //Get scheduled worked hours: z = diff_time(sched_in, sched_end) - 1 hour 
                    $schedule = convert_seconds_to_hour_decimal( max($sched_out-$sched_in, 0) );

                    //Get the hours per day minus the lunch break.
                    if(isset($current_schedule) && isset($current_schedule['enabled_lunch']) && isset($current_schedule['in_lunch']) && isset($current_schedule['out_lunch'])) {
                        $lunch_start_time = convert_time_to_24hours_format( $current_schedule['in_lunch'] ); //local
                        $lunch_end_time = convert_time_to_24hours_format( $current_schedule['out_lunch'] ); //local
                        $lunch_start_date = convert_date_local_to_utc(  $sched_day .' '. $lunch_start_time );
                        $lunch_end_date = convert_date_local_to_utc(  $sched_day .' '. $lunch_end_time );
                        $this->lunch_break = convert_seconds_to_hour_decimal( max($lunch_end_date-$lunch_start_date, 0) );
                    }

                    //Default hours per day
                    $this->hours_per_day = $schedule - $this->lunch_break;
                }

                //BREAKTIME
                $btime = isset($data->break_time)?unserialize($data->break_time):[];

                //1ST Overbreak 
                $break_1st_start = isset($btime[0])?strtotime($btime[0]):null;
                $break_1st_end = isset($btime[1])?strtotime($btime[1]):null;
                if($break_1st_start && $break_1st_end) {
                    $break_1st = convert_seconds_to_hour_decimal( max($break_1st_end-$break_1st_start, 0) );
                    if($break_1st > 0.25) {
                        $over += ($break_1st-0.25);
                    }
                }

                //LUNCH Overbreak 
                $break_lunch_start = isset($btime[2])?strtotime($btime[2]):null;
                $break_lunch_end = isset($btime[3])?strtotime($btime[3]):null;
                if($break_lunch_start && $break_lunch_end) {
                    $break_lunch = convert_seconds_to_hour_decimal( max($break_lunch_end-$break_lunch_start, 0) );
                    if($break_lunch > 1.00) {
                        $over += ($break_lunch-1.00);
                    }
                }

                //2ND Overbreak 
                $break_2nd_start = isset($btime[4])?strtotime($btime[4]):null;
                $break_2nd_end = isset($btime[5])?strtotime($btime[5]):null;
                if($break_2nd_start && $break_2nd_end) {
                    $break_2nd = convert_seconds_to_hour_decimal( max($break_2nd_end-$break_2nd_start, 0) );
                    if($break_2nd > 0.25) {
                        $over += ($break_2nd-0.25);
                    }
                }

                //Get non worked hours: a = x+y
                $nonworked = convert_number_to_decimal( max(($lates+$over+$under), 0) );

                //Get the worked hours: b = z-a;
                $worked = convert_number_to_decimal( max(($this->hours_per_day-$nonworked), 0) );

                //Current duration of actual
                $duration = $actual; 

                if($this->on_debug && $actual == 0) {
                    $duration = 'Invalid';
                    $worked = "Invalid";
                    $nonworked = 'Invalid';
                    $lates = 'Invalid';
                    $over = 'Invalid';
                    $under = 'Invalid';
                }
            } else {
                if($this->on_debug) {
                    $duration = 'Pending';
                    $worked = 'Pending';
                    $nonworked = 'Pending';
                    $over = 'Pending';
                    $under = 'Pending';
                }
            }

            if($this->on_debug && !$data->sched_id) {
                $lates = '-';
                $under = '-';
            }

            $this->attd_data[] = array(
                "duration" => $duration,
                "schedule" => $schedule,
                "worked" => $worked,
                "absent" => $nonworked,
                "lates" => $lates,
                "over" => $over,
                "under" => $under
            );
        } 

        return $this;
    }
}