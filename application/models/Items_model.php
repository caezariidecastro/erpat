<?php

class Items_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'items';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $items_table = $this->db->dbprefix('items');
        $where = "";
        $id = get_array_value($options, "id");
        $category = get_array_value($options, "category");
        if ($id) {
            $where .= " AND $items_table.id=$id";
        }
        if ($category) {
            $where .= " AND $items_table.category=$category";
        }

        $sql = "SELECT $items_table.*, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS full_name, cat.title AS category_name
        FROM $items_table
        LEFT JOIN users ON users.id = $items_table.created_by
        LEFT JOIN item_categories cat ON cat.id = $items_table.category
        WHERE $items_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
