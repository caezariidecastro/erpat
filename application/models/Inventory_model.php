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
        $id = get_array_value($options, "id");
        $item_id = get_array_value($options, "item_id");
        $warehouse_id = get_array_value($options, "warehouse_id");
        $item_query = "";
        $delivered_query = "";

        if ($id) {
            $where .= " AND $inventory_table.id=$id";
            $delivered_query .= " AND i.id=$id";
        }

        if ($item_id) {
            $where .= " AND $inventory_table.item_id=$item_id";
            $delivered_query .= " AND i.item_id=$item_id";
            $item_query = "AND i.item_id = $item_id";
        }

        if ($warehouse_id) {
            $where .= " AND $inventory_table.warehouse=$warehouse_id";
            $delivered_query .= " AND i.warehouse=$warehouse_id";
        }

        $sql = "SELECT $inventory_table.*, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS full_name, w.name AS warehouse_name, w.address AS warehouse_address, $inventory_table.name AS item_name, $inventory_table.stock, units.title AS unit_name, COALESCE((
            SELECT SUM(inventory_transfer_items.quantity)
            FROM inventory_transfer_items
            LEFT JOIN inventory_transfers ON inventory_transfers.reference_number = inventory_transfer_items.reference_number
            LEFT JOIN inventory i ON i.id = inventory_transfer_items.inventory_id
            WHERE inventory_transfers.deleted = 0
            AND inventory_transfer_items.deleted = 0
            $item_query
            AND inventory_transfers.transferee = $inventory_table.warehouse
        ), 0) AS transferred,
        COALESCE((
            SELECT SUM(inventory_transfer_items.quantity)
            FROM inventory_transfer_items
            LEFT JOIN inventory_transfers ON inventory_transfers.reference_number = inventory_transfer_items.reference_number
            LEFT JOIN inventory i ON i.id = inventory_transfer_items.inventory_id
            WHERE inventory_transfers.deleted = 0
            AND inventory_transfer_items.deleted = 0
            $item_query
            AND inventory_transfers.receiver = $inventory_table.warehouse
        ), 0) AS received,
        COALESCE((
            SELECT SUM(invoice_items.quantity)
            FROM invoice_items
            LEFT JOIN inventory i ON i.id = invoice_items.inventory_id
            LEFT JOIN invoices ON invoices.id = invoice_items.invoice_id
            WHERE i.deleted = 0
            AND invoice_items.deleted = 0
            $delivered_query
            AND (
                invoice_items.delivery_reference_no IS NOT NULL
                OR
                invoice_items.delivery_reference_no != ''
            )
            AND invoices.status != 'cancelled'
        ), 0) AS delivered,
        COALESCE((
            SELECT SUM(inventory_stock_override.stock)
            FROM inventory_stock_override
            WHERE inventory_stock_override.deleted = 0
            AND inventory_stock_override.inventory_id = $inventory_table.id
        ), 0) AS stock_override,
        COALESCE((
            SELECT SUM(bill_of_materials.quantity)
            FROM bill_of_materials
            LEFT JOIN productions ON productions.bill_of_material_id = bill_of_materials.id
            WHERE bill_of_materials.deleted = 0
            AND productions.status = 'completed'
            AND bill_of_materials.inventory_id = $inventory_table.id
        ), 0) AS produced,
        COALESCE((
            SELECT SUM(invoice_items.quantity)
            FROM invoice_items
            LEFT JOIN inventory i ON i.id = invoice_items.inventory_id
            LEFT JOIN invoices ON invoices.id = invoice_items.invoice_id
            WHERE i.deleted = 0
            AND invoice_items.deleted = 0
            AND (
                invoice_items.delivery_reference_no IS NULL
                OR
                invoice_items.delivery_reference_no = ''
            )
            AND invoices.status != 'cancelled'
        ), 0) AS invoiced
        FROM $inventory_table
        LEFT JOIN users ON users.id = $inventory_table.created_by
        LEFT JOIN warehouses w ON w.id = $inventory_table.warehouse
        LEFT JOIN units ON units.id = $inventory_table.unit
        WHERE $inventory_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function item_on_warehouse_check($item_id, $warehouse_id){
        $this->db->where('item_id', $item_id);
        $this->db->where('warehouse', $warehouse_id);
        $this->db->where('deleted', '0');
        return $this->db->get('inventory')->num_rows();
    }
}
