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
            SELECT SUM(inventory_stock_override.stock)
            FROM inventory
            LEFT JOIN inventory_stock_override ON inventory_stock_override.inventory_id = inventory.id
            WHERE item_id = $inventory_items_table.id
            AND inventory.deleted = 0
        ), 0) AS stocks_override,
        COALESCE((
            SELECT SUM(invoice_items.quantity)
            FROM invoice_items
            LEFT JOIN inventory i ON i.id = invoice_items.inventory_id
            LEFT JOIN invoices ON invoices.id = invoice_items.invoice_id
            WHERE i.deleted = 0
            AND invoice_items.deleted = 0
            AND i.item_id = $inventory_items_table.id
            AND (
                invoice_items.delivery_reference_no IS NOT NULL
                OR
                invoice_items.delivery_reference_no != ''
            )
            AND invoices.status != 'cancelled'
        ), 0) AS delivered,
        COALESCE((
            SELECT SUM(bill_of_materials.quantity)
            FROM bill_of_materials
            LEFT JOIN productions ON productions.bill_of_material_id = bill_of_materials.id
            LEFT JOIN inventory ON inventory.id = bill_of_materials.inventory_id
            WHERE bill_of_materials.deleted = 0
            AND productions.status = 'completed'
            AND inventory.item_id = $inventory_items_table.id
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
            AND i.item_id = $inventory_items_table.id
        ), 0) AS invoiced
        FROM $inventory_items_table
        LEFT JOIN users creator ON creator.id = $inventory_items_table.created_by
        LEFT JOIN inventory_item_categories cat ON cat.id = $inventory_items_table.category
        LEFT JOIN units un ON un.id = $inventory_items_table.unit
        LEFT JOIN vendors v ON v.id = $inventory_items_table.vendor
        WHERE $inventory_items_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
