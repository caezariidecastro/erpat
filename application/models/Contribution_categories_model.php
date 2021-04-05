<?php

class Contribution_categories_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'contribution_categories';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $contribution_categories_table = $this->db->dbprefix('contribution_categories');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $contribution_categories_table.id=$id";
        }

        $sql = "SELECT $contribution_categories_table.*, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS full_name
        FROM $contribution_categories_table
        LEFT JOIN users ON users.id = $contribution_categories_table.created_by
        WHERE $contribution_categories_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
