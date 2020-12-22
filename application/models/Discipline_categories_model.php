<?php

class Discipline_categories_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'discipline_categories';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $discipline_categories_table = $this->db->dbprefix('discipline_categories');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $discipline_categories_table.id=$id";
        }

        $sql = "SELECT $discipline_categories_table.*, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS full_name
        FROM $discipline_categories_table
        LEFT JOIN users ON users.id = $discipline_categories_table.created_by
        WHERE $discipline_categories_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
