<?php

class Sales_matrix_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'inventory_items';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $inventory_items_table = $this->db->dbprefix('inventory_items');
        $start = get_array_value($options, "start");
        $end = get_array_value($options, "end");
        
        $sql = "SELECT $inventory_items_table.*, cat.title AS category_name
        , COALESCE((
            SELECT SUM(invoice_items.total)
            FROM invoice_items
            LEFT JOIN deliveries ON deliveries.reference_number = invoice_items.delivery_reference_no
            LEFT JOIN inventory ON inventory.id = invoice_items.inventory_id
            WHERE deliveries.created_on BETWEEN '$start 00:00:00' AND '$end 23:59:59'
            AND inventory.item_id = $inventory_items_table.id
        ), 0) AS total_sales
        FROM $inventory_items_table
        LEFT JOIN inventory_item_categories cat ON cat.id = $inventory_items_table.category
        WHERE $inventory_items_table.deleted=0";
        return $this->db->query($sql);
    }

}
