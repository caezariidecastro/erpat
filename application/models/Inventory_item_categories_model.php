<?php

class Inventory_item_categories_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'inventory_item_categories';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $inventory_item_categories_table = $this->db->dbprefix('inventory_item_categories');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $inventory_item_categories_table.id=$id";
        }

        $sql = "SELECT $inventory_item_categories_table.*, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS full_name
        FROM $inventory_item_categories_table
        LEFT JOIN users ON users.id = $inventory_item_categories_table.created_by
        WHERE $inventory_item_categories_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
