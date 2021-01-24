<?php

class Consumers_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'consumers';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $consumers_table = $this->db->dbprefix('consumers');
        $where = "";
        $id = get_array_value($options, "id");

        if ($id) {
            $where .= " AND $consumers_table.id=$id";
        }

        $sql = "SELECT $consumers_table.*, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS full_name
        FROM $consumers_table
        LEFT JOIN users ON users.id = $consumers_table.created_by
        WHERE $consumers_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
