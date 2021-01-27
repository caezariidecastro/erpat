<?php

class Material_categories_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'material_categories';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $material_categories_table = $this->db->dbprefix('material_categories');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $material_categories_table.id=$id";
        }

        $sql = "SELECT $material_categories_table.*, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS full_name
        FROM $material_categories_table
        LEFT JOIN users ON users.id = $material_categories_table.created_by
        WHERE $material_categories_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
