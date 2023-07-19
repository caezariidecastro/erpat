<?php

class Loans_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'loans';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $stage_table = $this->db->dbprefix('loan_stages');
        $loans_table = $this->db->dbprefix('loans');
        $categories_table = $this->db->dbprefix('loan_categories');
        $users_table = $this->db->dbprefix('users');
        $fees_table = $this->db->dbprefix('loan_fees');
        $payments_table = $this->db->dbprefix('loan_payments');

        $offset = convert_seconds_to_time_format(get_timezone_offset());

        $where = "";

        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $loans_table.id=$id";
        }
        $borrower_id = get_array_value($options, "borrower_id");
        if ($borrower_id) {
            $where .= " AND $loans_table.borrower_id=$borrower_id";
        }
        $start_date = get_array_value($options, "start_date");
        if ($start_date) {
            $where .= " AND DATE(ADDTIME($loans_table.date_applied,'$offset'))>='$start_date'";
        }
        $end_date = get_array_value($options, "end_date");
        if ($end_date) {
            $where .= " AND DATE(ADDTIME($loans_table.date_applied,'$offset'))<='$end_date'";
        }

        $sql = "SELECT $loans_table.*, category_table.name as category_name, 
            (SELECT stage_name FROM $stage_table WHERE $stage_table.deleted=0 AND $stage_table.loan_id=$loans_table.id ORDER BY timestamp DESC LIMIT 1) as status,
            CONCAT(borrower_table.first_name, ' ',borrower_table.last_name) AS borrower_name, 
            CONCAT(cosigner_table.first_name, ' ',cosigner_table.last_name) AS cosigner_name,
            CONCAT(creator_table.first_name, ' ',creator_table.last_name) AS creator_name,
            IF((SELECT SUM($fees_table.amount) FROM $fees_table WHERE $fees_table.loan_id=$loans_table.id AND $fees_table.deleted=0), (SELECT SUM($fees_table.amount) FROM $fees_table WHERE $fees_table.loan_id=$loans_table.id AND $fees_table.deleted=0), 0) as fees,
            IF((SELECT SUM($payments_table.amount) FROM $payments_table WHERE $payments_table.loan_id=$loans_table.id AND $payments_table.deleted=0), (SELECT SUM($payments_table.amount) FROM $payments_table WHERE $payments_table.loan_id=$loans_table.id AND $payments_table.deleted=0), 0) as payments
        FROM $loans_table 
            LEFT JOIN $categories_table AS category_table ON category_table.id=$loans_table.category_id 
            LEFT JOIN $users_table AS borrower_table ON borrower_table.id=$loans_table.borrower_id 
            LEFT JOIN $users_table AS cosigner_table ON cosigner_table.id=$loans_table.cosigner_id
            LEFT JOIN $users_table AS creator_table ON creator_table.id=$loans_table.created_by
        WHERE $loans_table.deleted=0 $where";
        return $this->db->query($sql);
    }
}
