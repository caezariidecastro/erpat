<?php

class Leave_credits_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'leave_credits';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $leave_credits_table = $this->db->dbprefix('leave_credits');
        $users_table = $this->db->dbprefix('users');
        $team_table = $this->db->dbprefix('team');

        $where = "";
        $offset = convert_seconds_to_time_format(get_timezone_offset());

        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $leave_credits_table.user_id=$user_id";
        }
        $start_date = get_array_value($options, "start_date");
        if ($start_date) {
            $where .= " AND DATE(ADDTIME($leave_credits_table.date_created,'$offset'))>='$start_date'";
        }
        $end_date = get_array_value($options, "end_date");
        if ($end_date) {
            $where .= " AND DATE(ADDTIME($leave_credits_table.date_created,'$offset'))<='$end_date'";
        }
        
        $leave_type_id = get_array_value($options, "leave_type_id");
        if ($leave_type_id) {
            $where .= " AND $leave_credits_table.leave_type_id=$leave_type_id";
        }

        $department_id = get_array_value($options, "department_id");
        if($department_id){
            $teams_ids = "( SELECT GROUP_CONCAT($team_table.id) FROM $team_table WHERE $team_table.deleted='0' AND (FIND_IN_SET($leave_credits_table.user_id, $team_table.heads) OR FIND_IN_SET($leave_credits_table.user_id, $team_table.members) ) )";
            $where .= " AND FIND_IN_SET('$department_id', $teams_ids) ";
        }

        $query = "";
        $action = get_array_value($options, "action");
        if ($action) {
            if($action == 'balance') {
                $query = " (SUM(IF($leave_credits_table.action='debit',$leave_credits_table.counts,0)) 
                - SUM(IF($leave_credits_table.action='credit',$leave_credits_table.counts,0))) as balance,";
                $where .= " GROUP BY user_id";
            } else {
                $where .= " AND $leave_credits_table.action='$action'";
            }
        }

        $sql = "SELECT $leave_credits_table.*, $query
            CONCAT(users_table.first_name, ' ',users_table.last_name) AS fullname, 
            CONCAT(creator_table.first_name, ' ',creator_table.last_name) AS creator
        FROM $leave_credits_table 
            LEFT JOIN $users_table AS users_table ON users_table.id= $leave_credits_table.user_id 
            LEFT JOIN $users_table AS creator_table ON creator_table.id= $leave_credits_table.created_by
        WHERE $leave_credits_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function get_balance($options = array()) {
        $leave_credits_table = $this->db->dbprefix('leave_credits');
        $where = "";

        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $leave_credits_table.user_id=$user_id";
        }

        $leave_type_id = get_array_value($options, "leave_type_id");
        if ($leave_type_id) {
            $where .= " AND $leave_credits_table.leave_type_id=$leave_type_id";
        }

        $sql = "SELECT 
            (SUM(IF($leave_credits_table.action='debit',$leave_credits_table.counts,0)) 
                - SUM(IF($leave_credits_table.action='credit',$leave_credits_table.counts,0))) as balance
        FROM $leave_credits_table 
        WHERE $leave_credits_table.deleted=0 $where";

        $result = $this->db->query($sql);
        if($result->num_rows() > 0) {
            $row = $result->result();
            return $row[0]->balance?$row[0]->balance:0.00;
        }

        return 0.00;
    }

}
