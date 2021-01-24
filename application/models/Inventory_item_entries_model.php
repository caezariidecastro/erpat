<?php

class Inventory_item_entries_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'inventory_items';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $inventory_items_table = $this->db->dbprefix('inventory_items');
        $where = "";
        $id = get_array_value($options, "id");
        $category = get_array_value($options, "category");
        $vendor = get_array_value($options, "vendor");

        if ($id) {
            $where .= " AND $inventory_items_table.id=$id";
        }

        if($category){
            $where .= " AND $inventory_items_table.category = $category";
        }

        if($vendor){
            $where .= " AND $inventory_items_table.vendor = $vendor";
        }

        $sql = "SELECT $inventory_items_table.*, TRIM(CONCAT(creator.first_name, ' ', creator.last_name)) AS creator_name, cat.title AS category_name, un.title AS unit_name, v.name AS vendor_name, COALESCE((
            SELECT SUM(inventory.stock)
            FROM inventory
            WHERE item_id = $inventory_items_table.id
            AND inventory.deleted = 0
        ), 0) AS stocks,
        COALESCE((
            SELECT SUM(delivery_items.id)
            FROM delivery_items
            LEFT JOIN deliveries ON deliveries.reference_number = delivery_items.reference_number
            LEFT JOIN inventory ON inventory.item_id = delivery_items.inventory_id
            WHERE inventory.item_id = $inventory_items_table.id
            AND delivery_items.deleted = 0
            AND deliveries.deleted = 0
            AND inventory.deleted = 0
        ), 0) AS delivered
        FROM $inventory_items_table
        LEFT JOIN users creator ON creator.id = $inventory_items_table.created_by
        LEFT JOIN inventory_item_categories cat ON cat.id = $inventory_items_table.category
        LEFT JOIN units un ON un.id = $inventory_items_table.unit
        LEFT JOIN vendors v ON v.id = $inventory_items_table.vendor
        WHERE $inventory_items_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
