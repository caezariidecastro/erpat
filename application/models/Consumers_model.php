<?php

class Consumers_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'users';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $users_table = $this->db->dbprefix('users');
        $where = "";
        $id = get_array_value($options, "id");

        if ($id) {
            $where .= " AND $users_table.id=$id";
        }

        $sql = "SELECT $users_table.*, TRIM(CONCAT(creator.first_name, ' ', creator.last_name)) AS full_name
        FROM $users_table
        LEFT JOIN users creator ON creator.id = users.created_by
        WHERE $users_table.deleted=0 $where
        AND $users_table.user_type = 'customer'";
        return $this->db->query($sql);
    }

}
