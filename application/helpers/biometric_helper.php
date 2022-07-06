<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class BioMeet {

    protected $ci = null;
    protected $on_debug = false;

    protected $hours_per_day = 8.0;
    protected $attendance = [];
    protected $attd_data = [];

    function __construct( $ci, $options = array(), $on_debug = false ) {
        $this->ci = $ci;
        $this->on_debug = $on_debug;

        $this->hours_per_day = isset($rates['hours_per_day']) && is_numeric($rates['hours_per_day'])?
            $rates['hours_per_day']:$this->hours_per_day;
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

    //TODO: Get the overbreak
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
            $to_time = is_date_exists($data->out_time) ? 
            strtotime($data->out_time) : null;
            $from_time = strtotime($data->in_time);

            //Get the instance of the schedule.
            $cur_sched = $this->ci->Schedule_model->get_details(array("id" => $data->sched_id))->row();

            //If no schedule make sure to have an actual in and out as official schedule.
            $sched_day = convert_date_utc_to_local($data->in_time, 'Y-m-d'); //local
            $sched_in = $data->in_time;

            //Get the current day schedule instance based on in time.
            $day_name = format_to_custom($data->in_time, 'D');
            if( isset( $cur_sched->{strtolower($day_name)} ) && $today_sched = unserialize($cur_sched->{strtolower($day_name)}) ) {
                $sched_time = convert_time_to_24hours_format( $today_sched['in'] ); //local
                $sched_in = convert_date_local_to_utc(  $sched_day .' '. $sched_time );
                $schedule += $this->hours_per_day;
            }
            $sched_in = strtotime($sched_in);

            //Get lates: x = diff_time(in_time, sched_in)
            $lates = convert_seconds_to_hour_decimal( max($from_time-$sched_in, 0) );

            if( $data->out_time ) {

                //If no schedule make sure to have an actual in and out as official schedule.
                if( $data->out_time ) {
                    $sched_day = convert_date_utc_to_local($data->out_time, 'Y-m-d'); //local
                    $sched_out = $data->out_time;
                }

                //Get the current day schedule instance based on in time.
                $day_name = format_to_custom($data->out_time, 'D');
                if( isset( $cur_sched->{strtolower($day_name)} ) && $today_sched = unserialize($cur_sched->{strtolower($day_name)}) ) {
                    $sched_time = convert_time_to_24hours_format( $today_sched['out'] ); //local
                    $sched_out = convert_date_local_to_utc(  $sched_day .' '. $sched_time );
                }
                $sched_out = strtotime($sched_out);

                //Actual time in hours decimal.
                $actual = max($to_time-$from_time, 0);

                //Get undertime: y = diff_time(sched_end, out_time)
                $under = convert_seconds_to_hour_decimal( max($sched_out-$to_time, 0) );
                
                //Get scheduled worked hours: z = diff_time(sched_in, sched_end) - 1 hour 
                $sched_hours = convert_seconds_to_hour_decimal( max($sched_end-$sched_in, 0) );

                //Get non worked hours: a = x+y
                $nonworked = convert_number_to_decimal( max(($lates+$under), 0) );

                //Get the worked hours: b = z-a;
                $worked = convert_number_to_decimal( max(($this->hours_per_day-$nonworked), 0) );

                //Current duration of actual
                $duration = $actual; 

                if($this->on_debug && $actual == 0) {
                    $duration = 'Invalid';
                    $worked = "Invalid";
                    $nonworked = 'Invalid';
                    $lates = 'Invalid';
                    $under = 'Invalid';
                }
            } else {
                if($this->on_debug) {
                    $duration = 'Pending';
                    $worked = 'Pending';
                    $nonworked = 'Pending';
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