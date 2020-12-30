<?php

class Warehouse_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'warehouses';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $warehouses_table = $this->db->dbprefix('warehouses');
        $where = "";
        $id = get_array_value($options, "id");

        if ($id) {
            $where .= " AND $warehouses_table.id=$id";
        }

        $sql = "SELECT $warehouses_table.*, TRIM(CONCAT(head.first_name, ' ', head.last_name)) AS head_name, TRIM(CONCAT(creator.first_name, ' ', creator.last_name)) AS creator_name
        FROM $warehouses_table
        LEFT JOIN users head ON head.id = $warehouses_table.head
        LEFT JOIN users creator ON creator.id = $warehouses_table.created_by
        WHERE $warehouses_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
