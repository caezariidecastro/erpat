<?php

class Productions_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'productions';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $productions_table = $this->db->dbprefix('productions');
        $where = "";
        $id = get_array_value($options, "id");
        $start = get_array_value($options, "start");
        $end = get_array_value($options, "end");
        $warehouse = get_array_value($options, "warehouse");

        if ($id) {
            $where .= " AND $productions_table.id=$id";
        }

        if($start){
            $where .= " AND (
                $productions_table.created_on BETWEEN '$start' AND '$end'
            )";
        }

        if ($warehouse) {
            $where .= " AND inventory.warehouse=$warehouse";
        }

        $sql = "SELECT $productions_table.*, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS full_name, bill_of_materials.title AS bill_of_material_title, inventory.name AS item_name, bill_of_materials.quantity AS bill_of_material_quantity, warehouses.name AS warehouse_name, units.abbreviation
        FROM $productions_table
        LEFT JOIN users ON users.id = $productions_table.created_by
        LEFT JOIN bill_of_materials ON bill_of_materials.id = $productions_table.bill_of_material_id
        LEFT JOIN inventory ON inventory.id = $productions_table.inventory_id
        LEFT JOIN units ON units.id = inventory.unit
        LEFT JOIN warehouses ON warehouses.id = inventory.warehouse
        WHERE $productions_table.deleted=0 $where";
        return $this->db->query($sql);
    }
}
