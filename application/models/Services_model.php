<?php

class Services_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'services';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $services_table = $this->db->dbprefix('services');
        $where = "";
        $id = get_array_value($options, "id");
        $category = get_array_value($options, "category");
        if ($id) {
            $where .= " AND $services_table.id=$id";
        }
        if ($category) {
            $where .= " AND $services_table.category_id='$category'";
        }

        $sql = "SELECT $services_table.*, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS full_name, cat.title AS category_name
        FROM $services_table
        LEFT JOIN users ON users.id = $services_table.created_by
        LEFT JOIN services_categories cat ON cat.uuid = $services_table.category_id
        WHERE $services_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
