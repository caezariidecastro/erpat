<?php

class Purchase_order_return_materials_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'purchase_order_return_materials';
        parent::__construct($this->table);
    }

    function is_material_has_return($purchase_order_material_id) {
        $purchase_order_return_materials = $this->db->dbprefix('purchase_order_return_materials');

        $sql = "SELECT COUNT(*) AS count
        FROM $purchase_order_return_materials
        WHERE $purchase_order_return_materials.deleted = 0 
        AND $purchase_order_return_materials.purchase_order_material_id = $purchase_order_material_id";
        return $this->db->query($sql)->row()->count;
    }

    function get_details($options = array()) {
        $purchase_order_return_materials_table = $this->db->dbprefix('purchase_order_return_materials');
        $where = "";
        $id = get_array_value($options, "id");
        $purchase_order_return_id = get_array_value($options, "purchase_order_return_id");

        if ($id) {
            $where .= " AND $purchase_order_return_materials_table.id=$id";
        }

        if ($purchase_order_return_id) {
            $where .= " AND $purchase_order_return_materials_table.purchase_order_return_id=$purchase_order_return_id";
        }

        if ($purchase_order_return_id) {
            $where .= " AND $purchase_order_return_materials_table.purchase_order_return_id=$purchase_order_return_id";
        }

        $sql = "SELECT $purchase_order_return_materials_table.*
        FROM $purchase_order_return_materials_table
        LEFT JOIN users ON users.id = $purchase_order_return_materials_table.created_by
        WHERE $purchase_order_return_materials_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function get_return_total_material($purchase_order_return_id){
        $purchase_order_return_materials_table = $this->db->dbprefix('purchase_order_return_materials');

        $sql = "SELECT COALESCE(SUM($purchase_order_return_materials_table.total), 0) AS total
        FROM $purchase_order_return_materials_table
        WHERE $purchase_order_return_materials_table.deleted=0
        AND $purchase_order_return_materials_table.purchase_order_return_id = $purchase_order_return_id";
        return $this->db->query($sql)->row()->total;
    }
}
