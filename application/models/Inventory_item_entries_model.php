<?php

class Inventory_item_entries_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = $this->db->dbprefix('inventory_items');
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $inventory_items_table = $this->table;
        $where = "";
        $id = get_array_value($options, "id");
        $category = get_array_value($options, "category");
        $vendor = get_array_value($options, "vendor");
        $kind = get_array_value($options, "kind");

        if ($id) {
            $where .= " AND $inventory_items_table.id=$id";
        }

        if($category){
            $where .= " AND $inventory_items_table.category = $category";
        }

        if($vendor){
            $where .= " AND $inventory_items_table.vendor = $vendor";
        }

        if($kind){
            $where .= " AND $inventory_items_table.kind = '$kind'";
        }

        $sql = "SELECT $inventory_items_table.*, TRIM(CONCAT(creator.first_name, ' ', creator.last_name)) AS creator_name, cat.title AS category_name, brand.name AS brand_name, un.title AS unit_abbreviation, v.name AS vendor_name, COALESCE((
            SELECT SUM(inventory.stock)
            FROM inventory
            WHERE item_id = $inventory_items_table.id
            AND inventory.deleted = 0
        ), 0) AS stock,
        COALESCE((
            SELECT SUM(inventory_stock_override.stock)
            FROM inventory
            LEFT JOIN inventory_stock_override ON inventory_stock_override.inventory_id = inventory.id
            WHERE item_id = $inventory_items_table.id
            AND inventory.deleted = 0
        ), 0) AS stock_override,
        COALESCE((
            SELECT SUM(productions.quantity)
            FROM productions
            LEFT JOIN inventory ON inventory.id = productions.inventory_id
            WHERE productions.deleted = 0
            AND productions.status = 'completed'
            AND inventory.item_id = $inventory_items_table.id
        ), 0) AS produced,
        COALESCE((
            SELECT SUM(invoice_items.quantity)
            FROM invoice_items
            LEFT JOIN inventory i ON i.id = invoice_items.inventory_id
            LEFT JOIN invoices ON invoices.id = invoice_items.invoice_id
            LEFT JOIN deliveries ON deliveries.reference_number = invoice_items.delivery_reference_no
            WHERE i.deleted = 0
            AND invoice_items.deleted = 0
            AND i.item_id = $inventory_items_table.id
            AND (
                invoice_items.delivery_reference_no IS NOT NULL
                OR
                invoice_items.delivery_reference_no != ''
            )
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
        ), 0) AS delivered,
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
            AND i.item_id = $inventory_items_table.id
        ), 0) AS invoiced,
        COALESCE((
            SELECT SUM(inventory_transfer_items.quantity)
            FROM inventory_transfer_items
            LEFT JOIN inventory_transfers ON inventory_transfers.reference_number = inventory_transfer_items.reference_number
            LEFT JOIN inventory i ON i.id = inventory_transfer_items.inventory_id
            WHERE inventory_transfers.deleted = 0
            AND inventory_transfer_items.deleted = 0
            AND i.item_id = $inventory_items_table.id
            AND inventory_transfers.transferee = i.id
            AND inventory_transfers.status = 'completed'
        ), 0) AS transferred,
        COALESCE((
            SELECT SUM(inventory_transfer_items.quantity)
            FROM inventory_transfer_items
            LEFT JOIN inventory_transfers ON inventory_transfers.reference_number = inventory_transfer_items.reference_number
            LEFT JOIN inventory i ON i.id = inventory_transfer_items.inventory_id
            WHERE inventory_transfers.deleted = 0
            AND inventory_transfer_items.deleted = 0
            AND i.item_id = $inventory_items_table.id
            AND inventory_transfers.receiver = i.id
            AND inventory_transfers.status = 'completed'
        ), 0) AS received,
        COALESCE((
            SELECT bill_of_materials.id
            FROM bill_of_materials
            WHERE bill_of_materials.deleted = 0 
            AND bill_of_materials.item_id = $inventory_items_table.id
        ), 0) AS bom
        FROM $inventory_items_table
        LEFT JOIN users creator ON creator.id = $inventory_items_table.created_by
        LEFT JOIN inventory_item_categories cat ON cat.id = $inventory_items_table.category
        LEFT JOIN product_brands brand ON brand.id = $inventory_items_table.brand 
        LEFT JOIN units un ON un.id = $inventory_items_table.unit
        LEFT JOIN vendors v ON v.id = $inventory_items_table.vendor
        WHERE $inventory_items_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
