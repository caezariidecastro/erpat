<?php

class Bill_of_materials_materials_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'bill_of_materials_materials';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $bill_of_materials_materials_table = $this->db->dbprefix('bill_of_materials_materials');
        $where = "";
        $id = get_array_value($options, "id");

        if ($id) {
            $where .= " AND $bill_of_materials_materials_table.id=$id";
        }

        $sql = "SELECT $bill_of_materials_materials_table.*, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS full_name, material_inventory.name AS material_name
        FROM $bill_of_materials_materials_table
        LEFT JOIN users ON users.id = $bill_of_materials_materials_table.created_by
        LEFT JOIN material_inventory ON material_inventory.id = $bill_of_materials_materials_table.material_inventory_id
        WHERE $bill_of_materials_materials_table.deleted=0 $where";
        return $this->db->query($sql);
    }
}
