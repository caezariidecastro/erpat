<?php

class Vendors_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'vendors';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $vendors_table = $this->db->dbprefix('vendors');
        $where = "";
        $id = get_array_value($options, "id");

        if ($id) {
            $where .= " AND $vendors_table.id=$id";
        }

        $sql = "SELECT $vendors_table.*, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS full_name
        FROM $vendors_table
        LEFT JOIN users ON users.id = $vendors_table.created_by
        WHERE $vendors_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
