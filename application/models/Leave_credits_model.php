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
        $action = get_array_value($options, "action");
        if ($action) {
            $where .= " AND $leave_credits_table.action='$action'";
        }

        $sql = "SELECT $leave_credits_table.*, 
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

        $sql = "SELECT 
            SUM(IF($leave_credits_table.action='debit',$leave_credits_table.counts,0)) AS debits, 
            SUM(IF($leave_credits_table.action='credit',$leave_credits_table.counts,0)) AS credits 
        FROM $leave_credits_table 
        WHERE $leave_credits_table.deleted=0 $where";

        $result = $this->db->query($sql);
        if($result->num_rows() > 0) {
            $row = $result->result();
            return array (
                "debit" => $row[0]->debits,
                "credit" => $row[0]->credits,
                "balance" => $row[0]->debits - $row[0]->credits
            );
        } else {
            return array (
                "debit" => 0,
                "credit" => 0,
                "balance" => 0
            );
        }
        
    }

}
