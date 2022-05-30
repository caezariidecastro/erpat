<?php

class Overtime_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'overtime';
        parent::__construct($this->table);
    }

    function auto_clockout() {
        $now = get_current_utc_time();
        $sql = "UPDATE overtime SET end_time = '$now', note='System Clockout' 
            WHERE end_time IS NULL 
                AND TIME_TO_SEC(TIMEDIFF('$now', start_time)) / 3600 >= 12";
        $this->db->query($sql);
    }

    function current_clock_in_record($user_id) {
        $overtime_table = $this->db->dbprefix('overtime');
        $sql = "SELECT $overtime_table.*
        FROM $overtime_table
        WHERE $overtime_table.deleted=0 AND $overtime_table.user_id=$user_id AND $overtime_table.end_time IS NULL";
        $result = $this->db->query($sql);
        if ($result->num_rows()) {
            return $result->row();
        } else {
            return false;
        }
    }

    function log_time($user_id, $note = "", $returning = false) {

        //TODO: CHECK IF USER IS ENABLE FOR OVERTIME

        $current_clock_record = $this->current_clock_in_record($user_id);

        $now = get_current_utc_time();
        $data = array();

        if ($current_clock_record && $current_clock_record->id) {
            $data = array(
                "end_time" => $now,
                "notes" => $note
            );            
        } else {
            $data = array(
                "user_id" => $user_id,
                "start_time" => $now,
                "date_created" => $now
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
        $overtime_table = $this->db->dbprefix('overtime');
        $users_table = $this->db->dbprefix('users');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $overtime_table.id=$id";
        }
        $offset = convert_seconds_to_time_format(get_timezone_offset());

        $start_date = get_array_value($options, "start_date");
        if ($start_date) {
            $where .= " AND DATE(ADDTIME($overtime_table.start_time,'$offset'))>='$start_date'";
        }
        $end_date = get_array_value($options, "end_date");
        if ($end_date) {
            $where .= " AND DATE(ADDTIME($overtime_table.start_time,'$offset'))<='$end_date'";
        }

        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $overtime_table.user_id=$user_id";
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
            $where .= " AND $overtime_table.user_id IN($allowed_members)";
        }

        $only_clocked_in_members = get_array_value($options, "only_clocked_in_members");
        if ($only_clocked_in_members) {
            $where .= " AND $overtime_table.end_time IS NULL";
        }

        $sql = "SELECT $overtime_table.*,  CONCAT($users_table.first_name, ' ',$users_table.last_name) AS created_by_user, $users_table.image as created_by_avatar, $users_table.id as user_id, $users_table.job_title as user_job_title
        FROM $overtime_table
        LEFT JOIN $users_table ON $users_table.id = $overtime_table.user_id
        WHERE $overtime_table.deleted=0 $where
        ORDER BY $overtime_table.start_time DESC";
        return $this->db->query($sql);
    }

    function get_summary_details($options = array()) {
        $overtime_table = $this->db->dbprefix('overtime');
        $users_table = $this->db->dbprefix('users');

        $where = "";
        $offset = convert_seconds_to_time_format(get_timezone_offset());

        $start_date = get_array_value($options, "start_date");
        if ($start_date) {
            $where .= " AND DATE(ADDTIME($overtime_table.start_time,'$offset'))>='$start_date'";
        }
        $end_date = get_array_value($options, "end_date");
        if ($end_date) {
            $where .= " AND DATE(ADDTIME($overtime_table.start_time,'$offset'))<='$end_date'";
        }

        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $overtime_table.user_id=$user_id";
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
            $where .= " AND $overtime_table.user_id IN($allowed_members)";
        }



        //we'll show the details deport in summary_detials view         
        $extra_inner_select = "";
        $extra_group_by = "";
        $extra_select = "";
        $sort_by = "";
        if (get_array_value($options, "summary_details")) {
            $extra_select = ", start_date ";
            $extra_inner_select = ", MAX(DATE(ADDTIME($overtime_table.start_time,'$offset'))) AS start_date ";
            $extra_group_by = ", DATE(ADDTIME($overtime_table.start_time,'$offset')) ";
            $sort_by = "ORDER BY user_id, start_date ASC"; //order by must be with user_id 
        }


        $sql = "SELECT user_id, total_duration, CONCAT($users_table.first_name, ' ',$users_table.last_name) AS created_by_user, $users_table.image as created_by_avatar $extra_select
                 FROM (SELECT $overtime_table.user_id, SUM(TIMESTAMPDIFF(SECOND, $overtime_table.start_time, $overtime_table.end_time)) AS total_duration $extra_inner_select
                    FROM $overtime_table
                    WHERE $overtime_table.deleted=0 $where 
                    GROUP BY $overtime_table.user_id $extra_group_by) AS new_summary_table 
                LEFT JOIN $users_table ON $users_table.id = new_summary_table.user_id
                $sort_by    
               ";

        return $this->db->query($sql);
    }

    function count_clock_status() {
        $overtime_table = $this->db->dbprefix('overtime');
        $users_table = $this->db->dbprefix('users');

        $clocked_in = "SELECT $overtime_table.user_id
        FROM $overtime_table
        WHERE $overtime_table.deleted=0 AND $overtime_table.end_time IS NULL
        GROUP BY $overtime_table.user_id";
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
        $overtime_table = $this->db->dbprefix('overtime');

        $where = "";
        $offset = convert_seconds_to_time_format(get_timezone_offset());

        $start_date = get_array_value($options, "start_date");
        if ($start_date) {
            $where .= " AND DATE(ADDTIME($overtime_table.start_time,'$offset'))>='$start_date'";
        }
        $end_date = get_array_value($options, "end_date");
        if ($end_date) {
            $where .= " AND DATE(ADDTIME($overtime_table.start_time,'$offset'))<='$end_date'";
        }

        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $overtime_table.user_id=$user_id";
        }

        $sql = "SELECT DATE_FORMAT($overtime_table.start_time,'%d') AS day, SUM(TIME_TO_SEC(TIMEDIFF($overtime_table.end_time,$overtime_table.start_time))) total_sec
                FROM $overtime_table 
                WHERE $overtime_table.deleted=0 AND $overtime_table.end_time IS NOT NULL $where
                GROUP BY DATE($overtime_table.start_time)";
        return $this->db->query($sql);
    }

    function get_clocked_out_members($options = array()) {
        $overtime_table = $this->db->dbprefix('overtime');
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

        $sql = "SELECT CONCAT($users_table.first_name, ' ',$users_table.last_name) AS member_name, $users_table.last_online, $users_table.image, $users_table.id, $users_table.job_title
        FROM $users_table
        WHERE $users_table.deleted=0 AND $users_table.status='active' AND $users_table.user_type='staff' AND $users_table.id NOT IN (SELECT user_id from $overtime_table WHERE $overtime_table.deleted=0 AND $overtime_table.end_time IS NULL) $where
        ORDER BY $users_table.first_name DESC";
        return $this->db->query($sql);
    }

    function get_clock_in_out_details_of_all_users($options = array()) {
        $overtime_table = $this->db->dbprefix('overtime');
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

        $sql = "SELECT CONCAT($users_table.first_name, ' ',$users_table.last_name) AS member_name, $users_table.image, $users_table.id, $overtime_table.id AS overtime_id, $overtime_table.start_time
        FROM $users_table
        LEFT JOIN (SELECT user_id, id, start_time FROM $overtime_table WHERE $overtime_table.deleted=0 AND $overtime_table.end_time IS NULL) AS $overtime_table ON $overtime_table.user_id=$users_table.id
        WHERE $users_table.deleted=0 AND $users_table.status='active' AND $users_table.user_type='staff' $where";
        return $this->db->query($sql);
    }

}
