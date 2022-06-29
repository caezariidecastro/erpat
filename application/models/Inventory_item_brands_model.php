<?php

class Inventory_item_brands_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'product_brands';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $inventory_item_brands_table = $this->table ;

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $inventory_item_brands_table.id=$id";
        }

        $sql = "SELECT $inventory_item_brands_table.*, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS full_name
        FROM $inventory_item_brands_table
        LEFT JOIN users ON users.id = $inventory_item_brands_table.created_by
        WHERE $inventory_item_brands_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
