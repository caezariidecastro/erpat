<?php

class Incentive_categories_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'incentive_categories';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $incentive_categories_table = $this->db->dbprefix('incentive_categories');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $incentive_categories_table.id=$id";
        }

        $sql = "SELECT $incentive_categories_table.*, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS full_name
        FROM $incentive_categories_table
        LEFT JOIN users ON users.id = $incentive_categories_table.created_by
        WHERE $incentive_categories_table.deleted=0 $where";
        return $this->db->query($sql);
    }
}
