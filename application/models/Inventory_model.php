<?php

class Inventory_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'inventory';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $inventory_table = $this->db->dbprefix('inventory');
        $where = "";
        $addition_where = "";
        $id = get_array_value($options, "id");
        $item_id = get_array_value($options, "item_id");

        if ($id) {
            $where .= " AND $inventory_table.id=$id";
        }

        if ($item_id) {
            $where .= " AND $inventory_table.item_id=$item_id";
            $addition_where = "AND inventory_transfer_items.item_id = $item_id";
        }

        $sql = "SELECT $inventory_table.*, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS full_name, w.name AS warehouse_name, w.address AS warehouse_address, ii.name AS item_name, $inventory_table.stock, COALESCE((
            SELECT SUM(inventory_transfer_items.quantity)
            FROM inventory_transfer_items
            LEFT JOIN inventory_transfers ON inventory_transfers.reference_number = inventory_transfer_items.reference_number
            WHERE inventory_transfers.transferee = $inventory_table.warehouse
            AND inventory_transfers.deleted = 0
            AND inventory_transfer_items.deleted = 0
            $addition_where
        ), 0) AS transferred,
        COALESCE((
            SELECT SUM(inventory_transfer_items.quantity)
            FROM inventory_transfer_items
            LEFT JOIN inventory_transfers ON inventory_transfers.reference_number = inventory_transfer_items.reference_number
            WHERE inventory_transfers.receiver = $inventory_table.warehouse
            AND inventory_transfers.deleted = 0
            AND inventory_transfer_items.deleted = 0
            $addition_where
        ), 0) AS received
        FROM $inventory_table
        LEFT JOIN users ON users.id = $inventory_table.created_by
        LEFT JOIN warehouses w ON w.id = $inventory_table.warehouse
        LEFT JOIN inventory_items ii ON ii.id = $inventory_table.item_id
        WHERE $inventory_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function item_on_warehouse_check($item_id, $warehouse_id){
        $this->db->where('item_id', $item_id);
        $this->db->where('warehouse', $warehouse_id);
        return $this->db->get('inventory')->num_rows();
    }
}
