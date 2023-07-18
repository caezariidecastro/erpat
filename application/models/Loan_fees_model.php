<?php

class Loan_fees_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'loan_fees';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $fees_table = $this->db->dbprefix('loan_fees');
        $loans_table = $this->db->dbprefix('loans');
        $users_table = $this->db->dbprefix('users');
        $offset = convert_seconds_to_time_format(get_timezone_offset());

        $where = "";

        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $borrower = " AND loans_table.borrower_id=$user_id ";
        }
        $loan_id = get_array_value($options, "loan_id");
        if ($loan_id) {
            $where .= " AND $fees_table.loan_id=$loan_id";
        }
        $start_date = get_array_value($options, "start_date");
        if ($start_date) {
            $where .= " AND DATE(ADDTIME($fees_table.updated_at,'$offset'))>='$start_date'";
        }
        $end_date = get_array_value($options, "end_date");
        if ($end_date) {
            $where .= " AND DATE(ADDTIME($fees_table.updated_at,'$offset'))<='$end_date'";
        }

        $sql = "SELECT $fees_table.*, loans_table.updated_at as timestamp,
        CONCAT(borrower_table.first_name, ' ',borrower_table.last_name) AS borrower_name, 
        CONCAT(executer_table.first_name, ' ',executer_table.last_name) AS executer_name            
        FROM $fees_table 
            INNER JOIN $loans_table AS loans_table ON loans_table.id=$fees_table.loan_id $borrower 
            LEFT JOIN $users_table AS borrower_table ON borrower_table.id=loans_table.borrower_id 
            LEFT JOIN $users_table AS executer_table ON executer_table.id=$fees_table.created_by
        WHERE $fees_table.deleted=0 $where";
        return $this->db->query($sql);
    }
}
