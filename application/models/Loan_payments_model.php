<?php

class Loan_payments_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'loan_payments';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $payments_table = $this->db->dbprefix('loan_payments');

        $where = "";

        $loan_id = get_array_value($options, "loan_id");
        if ($loan_id) {
            $where .= " AND $payments_table.loan_id=$loan_id";
        }

        $sql = "SELECT $payments_table.*
        FROM $payments_table 
        WHERE $payments_table.deleted=0 $where";
        return $this->db->query($sql)->result();
    }
}
