<?php

class Material_inventory_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'material_inventory';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $material_inventory_table = $this->db->dbprefix('material_inventory');
        $where = "";
        $id = get_array_value($options, "id");
        $material_id = get_array_value($options, "material_id");
        $warehouse_id = get_array_value($options, "warehouse_id");

        if ($id) {
            $where .= " AND $material_inventory_table.id=$id";
        }

        if ($material_id) {
            $where .= " AND $material_inventory_table.material_id=$material_id";
        }

        if ($warehouse_id) {
            $where .= " AND $material_inventory_table.warehouse=$warehouse_id";
        }

        $sql = "SELECT $material_inventory_table.*, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS full_name, w.name AS warehouse_name, w.address AS warehouse_address, $material_inventory_table.name AS material_name, $material_inventory_table.stock, units.title AS unit_name, 
        COALESCE((
            SELECT SUM(material_inventory_stock_override.stock)
            FROM material_inventory_stock_override
            WHERE material_inventory_stock_override.deleted = 0
            AND material_inventory_stock_override.material_inventory_id = $material_inventory_table.id
        ), 0) AS stock_override,
        COALESCE((
            SELECT SUM(bill_of_materials_materials.quantity)
            FROM bill_of_materials_materials
            LEFT JOIN productions ON productions.bill_of_material_id = bill_of_materials_materials.bill_of_material_id
            WHERE bill_of_materials_materials.deleted = 0
            AND bill_of_materials_materials.material_inventory_id = $material_inventory_table.id
            AND productions.status IN ('ongoing', 'completed')
        ), 0) AS production_quantity,
        COALESCE((
            SELECT SUM(purchase_order_materials.quantity)
            FROM purchase_order_materials
            LEFT JOIN purchase_orders ON purchase_orders.id = purchase_order_materials.purchase_id
            WHERE purchase_order_materials.material_inventory_id = $material_inventory_table.id
            AND purchase_orders.deleted = 0
            AND purchase_orders.status = 'completed'
        ), 0) AS purchased,
        COALESCE((
            SELECT SUM(purchase_order_return_materials.quantity)
            FROM purchase_order_return_materials
            LEFT JOIN purchase_order_materials ON purchase_order_materials.id = purchase_order_return_materials.purchase_order_material_id
            WHERE purchase_order_materials.material_inventory_id = $material_inventory_table.id
            AND purchase_order_return_materials.deleted = 0
        ), 0) AS returned
        FROM $material_inventory_table
        LEFT JOIN users ON users.id = $material_inventory_table.created_by
        LEFT JOIN warehouses w ON w.id = $material_inventory_table.warehouse
        LEFT JOIN units ON units.id = $material_inventory_table.unit
        WHERE $material_inventory_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function material_on_warehouse_check($material_id, $warehouse_id){
        $this->db->where('material_id', $material_id);
        $this->db->where('warehouse', $warehouse_id);
        $this->db->where('deleted', '0');
        return $this->db->get('material_inventory')->num_rows();
    }
}
