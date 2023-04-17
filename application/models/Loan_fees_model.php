<?php

class Loan_fees_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'loan_fees';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $fees_table = $this->db->dbprefix('loan_fees');

        $where = "";

        $loan_id = get_array_value($options, "loan_id");
        if ($loan_id) {
            $where .= " AND $fees_table.loan_id=$loan_id";
        }

        $sql = "SELECT $fees_table.*
        FROM $fees_table 
        WHERE $fees_table.deleted=0 $where";
        return $this->db->query($sql)->result();
    }
}
