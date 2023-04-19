<?php

class Attendance_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->load->model("Schedule_model");
        $this->table = 'attendance';
        parent::__construct($this->table);
    }

    //Clock out all user more than time out.
    function auto_clockout() {
        $now = get_current_utc_time();
        
        $whitelisted = get_setting('whitelisted_autoclockout');

        // Auto clouckout greater than number of clocked hours.
        $trigger = floatval(get_setting('autoclockout_trigger_hour', 12.00));
        $sql = "UPDATE attendance SET out_time=in_time, status='clockout', note='System Clockout' 
            WHERE out_time IS NULL
                AND TIME_TO_SEC(TIMEDIFF('$now', in_time)) / 3600 >= $trigger ";
        if( !empty($whitelisted) ) {
            $sql .= "AND user_id NOT IN (".$whitelisted.")";
        }
        $this->db->query($sql);


        $autoclockout_list = get_setting("auto_clockin_employee");

        //get list of user in autoclocked out.
        $lists = explode(",",  $autoclockout_list);

        //loop and clock in who has 1m > greated thant schedule
        foreach($lists as $user_id) {
            $this->clock_out_by_schedule($user_id);
        }
    }

    function auto_clocked_in() {
        //get list of user in autoclocked in.
        $autoclockin_list = get_setting("auto_clockin_employee");
        $lists = explode(",",  $autoclockin_list);

        //loop and clock in who has 1m > greated thant schedule
        foreach($lists as $user_id) {
            // check if user have attendance today. will not work if detected in out greater than 1 sec.

            if( !$this->current_clock_in_record($user_id) && $this->get_today_clocked_duration($user_id) == 0  ) {
                $this->clock_in_by_schedule($user_id);
            }
        }
    }

    protected function clock_in_by_schedule($user_id) {
        // get the current sched id.
        if(!$sched_id = $this->Schedule_model->getUserSchedId($user_id)) {
            return;
        }

        // get the local time and day name.
        $current_local_time = get_my_local_time();
        $day_name = convert_date_format($current_local_time, 'D');
        $sched_date_in = convert_date_format($current_local_time, 'Y-m-d');

        // get the current user schedule by id.
        $cur_sched = $this->Schedule_model->get_details(array(
            "id" => $sched_id,
            "deleted" => true
        ))->row();
        
        //check if there is a schedule for today. get the schedule day name object.
        if( isset( $cur_sched->{strtolower($day_name)} ) && $today_sched = unserialize($cur_sched->{strtolower($day_name)}) ) {
            $sched_time = convert_time_to_24hours_format( $today_sched['in'] ); //local
            $scheduled_clocked_in = $sched_date_in .' '. $sched_time; //local
            $scheduled_clocked_in = convert_date_local_to_utc($scheduled_clocked_in); //utc

            $count_start = strtotime($scheduled_clocked_in);
            $count_end = strtotime(get_current_utc_time());

            //check if user is clocked in.
            if( max(($count_end-$count_start), 0) > 0 ) { 
                $data = array(
                    "sched_id" => $sched_id,
                    "in_time" => $scheduled_clocked_in,
                    "status" => "pending",
                    "user_id" => $user_id
                );

                $this->save($data);
            }
        }
    }

    protected function clock_out_by_schedule($user_id) {
        $attendance = $this->current_clock_in_record($user_id);
        if(!isset($attendance->id)) {
            return;
        }
        
        // get the local time and day name.
        $current_local_time = get_my_local_time();
        $day_name = convert_date_format($current_local_time, 'D');
        $sched_date_out = convert_date_format($current_local_time, 'Y-m-d');

        // get the current sched id.
        $sched_id = $attendance->sched_id;

        // get the current user schedule by id.
        $cur_sched = $this->Schedule_model->get_details(array(
            "id" => $sched_id,
            "deleted" => true
        ))->row();
        
        //check if there is a schedule for today. get the schedule day name object.
        if( isset( $cur_sched->{strtolower($day_name)} ) && $today_sched = unserialize($cur_sched->{strtolower($day_name)}) ) {
            $sched_time = convert_time_to_24hours_format( $today_sched['out'] ); //local
            $scheduled_clocked_out = $sched_date_out .' '. $sched_time; //local

            //Actual time attendance.
            $in_time = convert_date_utc_to_local($attendance->in_time);

            //Add 1 day if in_time is PM and Current is AM
            $from_time = convert_date_format($in_time, 'a');
            $to_time = convert_date_format($scheduled_clocked_out, 'a');
            if($from_time == "pm" && $to_time == "am") {
                $in_date = convert_date_format($in_time, 'Y-m-d');
                $sched_date_out = add_period_to_date($in_date, 1); //add one day
                $scheduled_clocked_out = $sched_date_out .' '. $sched_time; //local
            }

            $count_start = strtotime($scheduled_clocked_out);
            $count_end = strtotime($current_local_time);
            $time_diff_sec = max(($count_end-$count_start), 0);
            log_message("error", $time_diff_sec);
            if($time_diff_sec > 0) { 
                $data = array(
                    "out_time" => convert_date_local_to_utc($scheduled_clocked_out),
                    "status" => "pending",
                );
                $this->save($data, $attendance->id);
            }
        }
    }

    function current_clock_in_record($user_id) {
        $attendnace_table = $this->db->dbprefix('attendance');
        $sql = "SELECT $attendnace_table.*
        FROM $attendnace_table
        WHERE $attendnace_table.deleted=0 AND $attendnace_table.user_id=$user_id AND $attendnace_table.out_time IS NULL";
        $result = $this->db->query($sql);
        if($result->num_rows()) {
            return $result->row();
        } else {
            return false;
        }
    }

    function get_today_clocked_duration($user_id) {
        $attendnace_table = $this->db->dbprefix('attendance');
        $now_utc = get_current_utc_time();
        $date_utc = get_current_utc_time('Y-m-d');
        $sql = "SELECT TIME_TO_SEC(
                IF($attendnace_table.out_time, 
                    TIMEDIFF($attendnace_table.out_time,$attendnace_table.in_time),
                    TIMEDIFF('$now_utc',$attendnace_table.in_time)
                )
            ) total_sec
        FROM $attendnace_table 
        WHERE $attendnace_table.deleted=0 
            AND $attendnace_table.user_id=$user_id
            AND DATE($attendnace_table.in_time) = '$date_utc'";
        $result = $this->db->query($sql);

        if ($result->num_rows()) {
            return $result->row()->total_sec;
        } else {
            return 0;
        }
    }

    function log_time($user_id, $note = "", $returning = false) {

        $current_clock_record = $this->current_clock_in_record($user_id);
        $sched_id = $this->Schedule_model->getUserSchedId($user_id);

        $now = get_current_utc_time();
        $data = array();

        if ($current_clock_record && $current_clock_record->id) {
            $data = array(
                "out_time" => $now,
                "status" => "pending",
                "note" => $note
            );            
        } else {
            $data = array(
                "sched_id" => $sched_id,
                "in_time" => $now,
                "status" => "incomplete",
                "user_id" => $user_id
            );
        }

        if($returning) {
            $this->save($data, $current_clock_record->id);
            return is_object($current_clock_record);
        } else {
            return $this->save($data, $current_clock_record->id);
        }
    }

    function get_details($options = array()) {
        $attendnace_table = $this->db->dbprefix('attendance');
        $users_table = $this->db->dbprefix('users');
        $team_table = $this->db->dbprefix('team');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $attendnace_table.id=$id";
        }
        $offset = convert_seconds_to_time_format(get_timezone_offset());

        $start_date = get_array_value($options, "start_date");
        if ($start_date) {
            $where .= " AND DATE(ADDTIME($attendnace_table.in_time,'$offset'))>='$start_date'";
        }
        $end_date = get_array_value($options, "end_date");
        if ($end_date) {
            $where .= " AND DATE(ADDTIME($attendnace_table.in_time,'$offset'))<='$end_date'";
        }

        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $attendnace_table.user_id=$user_id";
        }

        $department_id = get_array_value($options, "department_id");
        if ($department_id) {
            $where .= " AND $team_table.id=$department_id";
        }

        $active_only = get_array_value($options, "active_only");
        if ($active_only) {
            $where .= " AND $users_table.status='active'";
        }

        $access_type = get_array_value($options, "access_type");

        if (!$id && $access_type !== "all") {

            $allowed_members = get_array_value($options, "allowed_members");
            if (is_array($allowed_members) && count($allowed_members)) {
                $allowed_members = join(",", $allowed_members);
            } else {
                $allowed_members = '0';
            }
            $login_user_id = get_array_value($options, "login_user_id");
            if ($login_user_id) {
                $allowed_members .= "," . $login_user_id;
            }
            $where .= " AND $attendnace_table.user_id IN($allowed_members)";
        }

        $only_clocked_in_members = get_array_value($options, "only_clocked_in_members");
        if ($only_clocked_in_members) {
            $where .= " AND $attendnace_table.out_time IS NULL";
        }

        $teams_lists = ", (SELECT GROUP_CONCAT($team_table.title) FROM $team_table WHERE deleted='0' AND (FIND_IN_SET($attendnace_table.user_id, $team_table.heads) OR FIND_IN_SET($attendnace_table.user_id, $team_table.members)) ) as team_list";

        $created_by_user = "CONCAT($users_table.first_name, ' ',$users_table.last_name) AS created_by_user";
        if(get_setting('name_format') == "lastfirst") {
            $created_by_user = "CONCAT($users_table.last_name, ', ', $users_table.first_name) AS created_by_user";
        }

        $sql = "SELECT DISTINCT $attendnace_table.id, $attendnace_table.*,  $created_by_user, $users_table.image as created_by_avatar, $users_table.id as user_id, $users_table.job_title as user_job_title $teams_lists 
        FROM $attendnace_table
        LEFT JOIN $users_table ON $users_table.id = $attendnace_table.user_id
        LEFT JOIN $team_table ON $team_table.deleted='0' AND (FIND_IN_SET($attendnace_table.user_id, $team_table.heads) OR FIND_IN_SET($attendnace_table.user_id, $team_table.members))
        WHERE $attendnace_table.deleted=0 $where
        ORDER BY $attendnace_table.in_time DESC";
        return $this->db->query($sql);
    }

    function get_summary_details($options = array()) {
        $attendnace_table = $this->db->dbprefix('attendance');
        $users_table = $this->db->dbprefix('users');

        $where = "";
        $offset = convert_seconds_to_time_format(get_timezone_offset());

        $start_date = get_array_value($options, "start_date");
        if ($start_date) {
            $where .= " AND DATE(ADDTIME($attendnace_table.in_time,'$offset'))>='$start_date'";
        }
        $end_date = get_array_value($options, "end_date");
        if ($end_date) {
            $where .= " AND DATE(ADDTIME($attendnace_table.in_time,'$offset'))<='$end_date'";
        }

        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $attendnace_table.user_id=$user_id";
        }

        $access_type = get_array_value($options, "access_type");

        if ($access_type !== "all") {

            $allowed_members = get_array_value($options, "allowed_members");
            if (is_array($allowed_members) && count($allowed_members)) {
                $allowed_members = join(",", $allowed_members);
            } else {
                $allowed_members = '0';
            }
            $login_user_id = get_array_value($options, "login_user_id");
            if ($login_user_id) {
                $allowed_members .= "," . $login_user_id;
            }
            $where .= " AND $attendnace_table.user_id IN($allowed_members)";
        }



        //we'll show the details deport in summary_detials view         
        $extra_inner_select = "";
        $extra_group_by = "";
        $extra_select = "";
        $sort_by = "";
        if (get_array_value($options, "summary_details")) {
            $extra_select = ", start_date ";
            $extra_inner_select = ", MAX(DATE(ADDTIME($attendnace_table.in_time,'$offset'))) AS start_date ";
            $extra_group_by = ", DATE(ADDTIME($attendnace_table.in_time,'$offset')) ";
            $sort_by = "ORDER BY user_id, start_date ASC"; //order by must be with user_id 
        }


        $sql = "SELECT user_id, total_duration, CONCAT($users_table.first_name, ' ',$users_table.last_name) AS created_by_user, $users_table.image as created_by_avatar $extra_select
                 FROM (SELECT $attendnace_table.user_id, SUM(TIMESTAMPDIFF(SECOND, $attendnace_table.in_time, $attendnace_table.out_time)) AS total_duration $extra_inner_select
                    FROM $attendnace_table
                    WHERE $attendnace_table.deleted=0 $where 
                    GROUP BY $attendnace_table.user_id $extra_group_by) AS new_summary_table 
                LEFT JOIN $users_table ON $users_table.id = new_summary_table.user_id
                $sort_by    
               ";

        return $this->db->query($sql);
    }

    function count_clock_status() {
        $attendnace_table = $this->db->dbprefix('attendance');
        $users_table = $this->db->dbprefix('users');

        $clocked_in = "SELECT $attendnace_table.user_id
        FROM $attendnace_table
        WHERE $attendnace_table.deleted=0 AND $attendnace_table.out_time IS NULL
        GROUP BY $attendnace_table.user_id";
        $clocked_in_result = $this->db->query($clocked_in);

        $total_members = "SELECT COUNT(id) AS total_members
        FROM $users_table
        WHERE $users_table.deleted=0 AND $users_table.user_type='staff' AND $users_table.status='active'";
        $total_members_result = $this->db->query($total_members)->row()->total_members;


        $info = new stdClass();
        $info->members_clocked_in = $clocked_in_result->num_rows();
        $info->total_members = $total_members_result ? $total_members_result : 0;
        $info->members_clocked_out = $total_members_result - $info->members_clocked_in;

        return $info;
    }

    function get_timecard_statistics($options = array()) {
        $attendnace_table = $this->db->dbprefix('attendance');

        $where = "";
        $offset = convert_seconds_to_time_format(get_timezone_offset());

        $start_date = get_array_value($options, "start_date");
        if ($start_date) {
            $where .= " AND DATE(ADDTIME($attendnace_table.in_time,'$offset'))>='$start_date'";
        }
        $end_date = get_array_value($options, "end_date");
        if ($end_date) {
            $where .= " AND DATE(ADDTIME($attendnace_table.in_time,'$offset'))<='$end_date'";
        }

        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $attendnace_table.user_id=$user_id";
        }

        $sql = "SELECT DATE_FORMAT($attendnace_table.in_time,'%d') AS day, SUM(TIME_TO_SEC(TIMEDIFF($attendnace_table.out_time,$attendnace_table.in_time))) total_sec
                FROM $attendnace_table 
                WHERE $attendnace_table.deleted=0 AND $attendnace_table.out_time IS NOT NULL $where
                GROUP BY DATE($attendnace_table.in_time)";
        return $this->db->query($sql);
    }

    function count_total_time($options = array()) {
        $attendnace_table = $this->db->dbprefix('attendance');
        $timesheet_table = $this->db->dbprefix('project_time');

        $attendance_where = "";
        $timesheet_where = "";

        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $attendance_where .= " AND $attendnace_table.user_id=$user_id";
            $timesheet_where .= " AND $timesheet_table.user_id=$user_id";
        }

        $project_id = get_array_value($options, "project_id");
        if ($project_id) {
            $timesheet_where .= " AND $timesheet_table.project_id=$project_id";

            $allowed_members = get_array_value($options, "allowed_members");
            if (is_array($allowed_members) && count($allowed_members)) {
                $allowed_members = join(",", $allowed_members);
                $timesheet_where .= " AND $timesheet_table.user_id IN($allowed_members)";
            }
        }

        $info = new stdClass();

        $attendance_sql = "SELECT  SUM(TIME_TO_SEC(TIMEDIFF($attendnace_table.out_time,$attendnace_table.in_time))) total_sec
                FROM $attendnace_table 
                WHERE $attendnace_table.deleted=0 AND $attendnace_table.out_time IS NOT NULL $attendance_where";
        $info->timecard_total = $this->db->query($attendance_sql)->row()->total_sec;

        $timesheet_sql = "SELECT (SUM(TIME_TO_SEC(TIMEDIFF($timesheet_table.end_time,$timesheet_table.start_time))) + SUM((ROUND(($timesheet_table.hours * 60), 0)) * 60)) total_sec
                FROM $timesheet_table 
                WHERE $timesheet_table.deleted=0 AND $timesheet_table.status='logged' $timesheet_where";

        $info->timesheet_total = $this->db->query($timesheet_sql)->row()->total_sec;

        return $info;
    }

    function get_clocked_out_members($options = array()) {
        $attendnace_table = $this->db->dbprefix('attendance');
        $users_table = $this->db->dbprefix('users');

        $where = "";
        $access_type = get_array_value($options, "access_type");
        if ($access_type !== "all") {

            $allowed_members = get_array_value($options, "allowed_members");
            if (is_array($allowed_members) && count($allowed_members)) {
                $allowed_members = join(",", $allowed_members);
            } else {
                $allowed_members = '0';
            }
            $login_user_id = get_array_value($options, "login_user_id");
            if ($login_user_id) {
                $allowed_members .= "," . $login_user_id;
            }
            $where .= " AND $users_table.id IN ($allowed_members)";
        }

        $member_name = "CONCAT($users_table.first_name, ' ',$users_table.last_name) AS member_name";
        if(get_setting('name_format') == "lastfirst") {
            $member_name = "CONCAT($users_table.last_name, ', ', $users_table.first_name) AS member_name";
        }

        $sql = "SELECT $member_name, $users_table.last_online, $users_table.image, $users_table.id, $users_table.job_title
        FROM $users_table
        WHERE $users_table.deleted=0 AND $users_table.status='active' AND $users_table.user_type='staff' AND $users_table.id NOT IN (SELECT $attendnace_table.user_id FROM $attendnace_table WHERE $attendnace_table.deleted=0 AND $attendnace_table.out_time IS NULL) $where
        ORDER BY $users_table.first_name DESC";
        return $this->db->query($sql);
    }

    function get_clock_in_out_details_of_all_users($options = array()) {
        $attendnace_table = $this->db->dbprefix('attendance');
        $users_table = $this->db->dbprefix('users');

        $where = "";

        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $users_table.id=$id";
        }

        $where_in = get_array_value($options, "where_in");
        if ($where_in) {
            $where_in_implode = implode(',', $where_in);
            $where .= " AND $users_table.id IN($where_in_implode)";
        }

        $member_name = "CONCAT($users_table.first_name, ' ',$users_table.last_name) AS member_name";
        if(get_setting('name_format') == "lastfirst") {
            $member_name = "CONCAT($users_table.last_name, ', ', $users_table.first_name) AS member_name";
        }

        $sql = "SELECT $member_name, $users_table.image, $users_table.id, attendance_table.id AS attendance_id, attendance_table.in_time
        FROM $users_table
        LEFT JOIN (SELECT user_id, id, in_time FROM $attendnace_table WHERE $attendnace_table.deleted=0 AND $attendnace_table.out_time IS NULL) AS attendance_table ON attendance_table.user_id=$users_table.id
        WHERE $users_table.deleted=0 AND $users_table.status='active' AND $users_table.user_type='staff' $where";
        return $this->db->query($sql);
    }

}
