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
}
