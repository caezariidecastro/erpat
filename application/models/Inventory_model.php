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
            $where .= " AND inventory_items.id=$id";
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
            $item_query = "AND i.item_id = $inventory_table.item_id";
        }

        $sql = "SELECT inventory_items.*, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS full_name, w.name AS warehouse_name, w.address AS warehouse_address, inventory_items.name AS item_name, inventory_items.`description` AS `description`, units.abbreviation AS unit_abbreviation, inventory_items.id as inventory_id, $inventory_table.stock, $inventory_table.warehouse,
        COALESCE((
            SELECT SUM(inventory_stock_override.stock)
            FROM inventory_stock_override
            WHERE inventory_stock_override.deleted = 0
            AND inventory_stock_override.inventory_id = $inventory_table.id
        ), 0) AS stock_override,
        COALESCE((
            SELECT SUM(productions.quantity)
            FROM bill_of_materials
            LEFT JOIN productions ON productions.bill_of_material_id = bill_of_materials.id
            WHERE bill_of_materials.deleted = 0
            AND productions.status = 'completed'
            AND productions.inventory_id = $inventory_table.id
        ), 0) AS produced,
        COALESCE((
            SELECT SUM(invoice_items.quantity)
            FROM invoice_items
            LEFT JOIN inventory i ON i.id = invoice_items.inventory_id
            LEFT JOIN invoices ON invoices.id = invoice_items.invoice_id
            LEFT JOIN deliveries ON deliveries.invoice_id = invoices.id
            WHERE i.deleted = 0
            AND invoice_items.deleted = 0
            $delivered_query
            AND invoices.status NOT IN ('draft', 'cancelled')
            AND (
                (
                    SELECT ROUND(SUM(invoice_payments.amount), 2)
                    FROM invoice_payments
                    WHERE invoice_payments.invoice_id = invoices.id
                    AND invoice_payments.deleted = 0
                )
                = 
                (
                    SELECT ROUND(SUM(ii.total), 2)
                    FROM invoice_items ii
                    WHERE ii.invoice_id = invoices.id
                    AND ii.deleted = 0
                )
            )
            AND deliveries.status = 'completed'
            AND i.id = $inventory_table.id
        ), 0) AS delivered,
        COALESCE((
            SELECT SUM(invoice_items.quantity)
            FROM invoice_items
            LEFT JOIN inventory i ON i.id = invoice_items.inventory_id
            LEFT JOIN invoices ON invoices.id = invoice_items.invoice_id
            WHERE i.deleted = 0
            AND invoice_items.deleted = 0
            AND invoices.status NOT IN ('draft', 'cancelled')
            AND (
                (
                    SELECT ROUND(SUM(invoice_payments.amount), 2)
                    FROM invoice_payments
                    WHERE invoice_payments.invoice_id = invoices.id
                    AND invoice_payments.deleted = 0
                )
                = 
                (
                    SELECT ROUND(SUM(ii.total), 2)
                    FROM invoice_items ii
                    WHERE ii.invoice_id = invoices.id
                    AND ii.deleted = 0
                )
            )
            AND i.id = $inventory_table.id
        ), 0) AS invoiced,
        COALESCE((
            SELECT SUM(inventory_transfer_items.quantity)
            FROM inventory_transfer_items
            LEFT JOIN inventory_transfers ON inventory_transfers.reference_number = inventory_transfer_items.reference_number
            LEFT JOIN inventory i ON i.id = inventory_transfer_items.inventory_id
            WHERE inventory_transfers.deleted = 0
            AND inventory_transfer_items.deleted = 0
            $item_query
            AND inventory_transfers.transferee = $inventory_table.warehouse
            AND inventory_transfers.status = 'completed'
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
            AND inventory_transfers.status = 'completed'
        ), 0) AS received
        FROM inventory_items
        LEFT JOIN $inventory_table ON $inventory_table.item_id = inventory_items.id
        LEFT JOIN invoice_items ON invoice_items.inventory_id = inventory_items.id
        LEFT JOIN invoices ON invoices.id = invoice_items.invoice_id
        LEFT JOIN users ON users.id = inventory_items.created_by
        LEFT JOIN warehouses w ON w.id = $inventory_table.warehouse
        LEFT JOIN units ON units.id = inventory_items.unit
        WHERE inventory_items.deleted=0 $where";
        return $this->db->query($sql);
    }

    function item_on_warehouse_check($item_id, $warehouse_id){
        $this->db->where('item_id', $item_id);
        $this->db->where('warehouse', $warehouse_id);
        $this->db->where('deleted', '0');
        return $this->db->get('inventory')->num_rows();
    }

    function get_production_product_warehouse($bill_of_material_id){
        $inventory_table = $this->db->dbprefix('inventory');

        $sql = "SELECT $inventory_table.*, w.name AS warehouse_name
        FROM inventory_items
        LEFT JOIN $inventory_table ON $inventory_table.id = $inventory_table.item_id
        LEFT JOIN warehouses w ON w.id = $inventory_table.warehouse
        LEFT JOIN bill_of_materials ON bill_of_materials.item_id = $inventory_table.item_id
        WHERE $inventory_table.deleted=0 
        AND bill_of_materials.id = $bill_of_material_id";
        return $this->db->query($sql);
    }
}
