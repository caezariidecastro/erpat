<?php

class Stores_categories_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = $this->db->dbprefix('stores_categories');
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $stores_categories_table = $this->table;
        $where = "";
        $id = get_array_value($options, "uuid");
        if ($id) {
            $where .= " AND $stores_categories_table.uuid=$id";
        }

        $sql = "SELECT $stores_categories_table.*, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS full_name
        FROM $stores_categories_table
        LEFT JOIN users ON users.id = $stores_categories_table.created_by
        WHERE $stores_categories_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
