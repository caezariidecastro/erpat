<?php

class Loan_payments_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'loan_payments';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $payments_table = $this->db->dbprefix('loan_payments');
        $loans_table = $this->db->dbprefix('loans');
        $users_table = $this->db->dbprefix('users');
        $offset = convert_seconds_to_time_format(get_timezone_offset());

        $where = "";

        $loan_id = get_array_value($options, "loan_id");
        if ($loan_id) {
            $where .= " AND $payments_table.loan_id=$loan_id";
        }
        $start_date = get_array_value($options, "start_date");
        if ($start_date) {
            $where .= " AND DATE(ADDTIME($payments_table.date_paid,'$offset'))>='$start_date'";
        }
        $end_date = get_array_value($options, "end_date");
        if ($end_date) {
            $where .= " AND DATE(ADDTIME($payments_table.date_paid,'$offset'))<='$end_date'";
        }

        $sql = "SELECT $payments_table.*, loans_table.updated_at as timestamp,
        CONCAT(borrower_table.first_name, ' ',borrower_table.last_name) AS borrower_name, 
        CONCAT(executer_table.first_name, ' ',executer_table.last_name) AS executer_name            
        FROM $payments_table 
            INNER JOIN $loans_table AS loans_table ON loans_table.id=$payments_table.loan_id
            LEFT JOIN $users_table AS borrower_table ON borrower_table.id=loans_table.borrower_id 
            LEFT JOIN $users_table AS executer_table ON executer_table.id=$payments_table.created_by
        WHERE $payments_table.deleted=0 $where";
        return $this->db->query($sql);
    }
}
