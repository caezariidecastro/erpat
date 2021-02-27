<?php

class Purchase_order_materials_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'purchase_order_materials';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $purchase_order_materials_table = $this->db->dbprefix('purchase_order_materials');
        $where = "";
        $id = get_array_value($options, "id");
        $purchase_id = get_array_value($options, "purchase_id");
        if ($id) {
            $where .= " AND $purchase_order_materials_table.id=$id";
        }
        if ($purchase_id) {
            $where .= " AND $purchase_order_materials_table.purchase_id=$purchase_id";
        }

        $sql = "SELECT $purchase_order_materials_table.*
        FROM $purchase_order_materials_table
        WHERE $purchase_order_materials_table.deleted=0 $where";
        return $this->db->query($sql);
    }
    
    function get_purchase_total_material($purchase_id){
        $purchase_order_materials_table = $this->db->dbprefix('purchase_order_materials');

        $sql = "SELECT COALESCE(SUM($purchase_order_materials_table.total), 0) AS total
        FROM $purchase_order_materials_table
        WHERE $purchase_order_materials_table.deleted=0
        AND $purchase_order_materials_table.purchase_id = $purchase_id";
        return $this->db->query($sql)->row()->total;
    }
}
