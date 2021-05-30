<?php

class Services_categories_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'services_categories';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $services_categories_table = $this->db->dbprefix('services_categories');
        $where = "";
        $id = get_array_value($options, "uuid");
        if ($id) {
            $where .= " AND $services_categories_table.uuid=$id";
        }

        $sql = "SELECT $services_categories_table.*, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS full_name
        FROM $services_categories_table
        LEFT JOIN users ON users.id = $services_categories_table.created_by
        WHERE $services_categories_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
