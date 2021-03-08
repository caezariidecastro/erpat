<?php

class Material_entries_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'materials';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $materials_table = $this->db->dbprefix('materials');
        $where = "";
        $id = get_array_value($options, "id");
        $category = get_array_value($options, "category");
        $vendor = get_array_value($options, "vendor");

        if ($id) {
            $where .= " AND $materials_table.id=$id";
        }

        if($category){
            $where .= " AND $materials_table.category = $category";
        }

        if($vendor){
            $where .= " AND $materials_table.vendor = $vendor";
        }

        $sql = "SELECT $materials_table.*, TRIM(CONCAT(creator.first_name, ' ', creator.last_name)) AS creator_name, cat.title AS category_name, un.title AS unit_name, v.name AS vendor_name, COALESCE((
            SELECT SUM(material_inventory.stock)
            FROM material_inventory
            WHERE material_id = $materials_table.id
            AND material_inventory.deleted = 0
        ), 0) AS stocks,
        COALESCE((
            SELECT SUM(material_inventory_stock_override.stock)
            FROM material_inventory
            LEFT JOIN material_inventory_stock_override ON material_inventory_stock_override.material_inventory_id = material_inventory.id
            WHERE material_id = $materials_table.id 
            AND material_inventory.deleted = 0
        ), 0) AS stocks_override,
        COALESCE((
            SELECT SUM(bill_of_materials_materials.quantity)
            FROM bill_of_materials_materials
            LEFT JOIN material_inventory ON material_inventory.id = bill_of_materials_materials.material_inventory_id
            LEFT JOIN productions ON productions.bill_of_material_id = bill_of_materials_materials.bill_of_material_id
            WHERE bill_of_materials_materials.deleted = 0
            AND material_inventory.material_id = $materials_table.id
            AND productions.status IN ('ongoing', 'completed')
        ), 0) AS production_quantity,
        COALESCE((
            SELECT SUM(purchase_order_materials.quantity)
            FROM purchase_order_materials
            LEFT JOIN purchase_orders ON purchase_orders.id = purchase_order_materials.purchase_id
            WHERE purchase_order_materials.material_id = $materials_table.id
            AND purchase_orders.deleted = 0
            AND purchase_orders.status = 'completed'
        ), 0) AS purchased,
        COALESCE((
            SELECT SUM(purchase_order_return_materials.quantity)
            FROM purchase_order_return_materials
            LEFT JOIN purchase_order_materials ON purchase_order_materials.id = purchase_order_return_materials.purchase_order_material_id
            LEFT JOIN purchase_order_returns ON purchase_order_returns.id = purchase_order_return_materials.purchase_order_return_id
            WHERE purchase_order_materials.material_id = $materials_table.id
            AND purchase_order_return_materials.deleted = 0
            AND purchase_order_returns.status = 'completed'
        ), 0) AS returned
        FROM $materials_table
        LEFT JOIN users creator ON creator.id = $materials_table.created_by
        LEFT JOIN material_categories cat ON cat.id = $materials_table.category
        LEFT JOIN units un ON un.id = $materials_table.unit
        LEFT JOIN vendors v ON v.id = $materials_table.vendor
        WHERE $materials_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
