<?php

class Holidays_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'holidays';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $holidays_table = $this->db->dbprefix('holidays');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $holidays_table.id=$id";
        }

        $sql = "SELECT $holidays_table.*, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS full_name
        FROM $holidays_table
        LEFT JOIN users ON users.id = $holidays_table.created_by
        WHERE $holidays_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
