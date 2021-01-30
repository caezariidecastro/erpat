<?php

class Customers_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'customers';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $customers_table = $this->db->dbprefix('customers');
        $where = "";
        $id = get_array_value($options, "id");

        if ($id) {
            $where .= " AND $customers_table.id=$id";
        }

        $sql = "SELECT $customers_table.*, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS full_name
        FROM $customers_table
        LEFT JOIN users ON users.id = $customers_table.created_by
        WHERE $customers_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
