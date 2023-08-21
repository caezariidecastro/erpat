<?php

class Stores_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = $this->db->dbprefix('stores');
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $stores_table = $this->table;
        $where = "";
        $id = get_array_value($options, "id");
        $category = get_array_value($options, "category");
        if ($id) {
            $where .= " AND $stores_table.id=$id";
        }
        if ($category) {
            $where .= " AND $stores_table.category_id='$category'";
        }

        $sql = "SELECT $stores_table.*, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS full_name, cat.name AS category_name
        FROM $stores_table
            LEFT JOIN stores_categories cat ON cat.uuid = $stores_table.category_id AND cat.status=1
            LEFT JOIN users ON users.id = $stores_table.created_by
        WHERE $stores_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
