<?php

class Units_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'units';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $units_table = $this->db->dbprefix('units');
        $where = "";
        $id = get_array_value($options, "id");

        if ($id) {
            $where .= " AND $units_table.id=$id";
        }

        $sql = "SELECT $units_table.*, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS full_name, base.value AS base_unit_value, base.title AS base_unit_title
        FROM $units_table
        LEFT JOIN users ON users.id = $units_table.created_by
        LEFT JOIN $units_table AS base ON base.id = $units_table.base_unit
        WHERE $units_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
