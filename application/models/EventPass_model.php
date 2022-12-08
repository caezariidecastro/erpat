<?php

class EventPass_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'event_pass';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $event_pass_table = $this->db->dbprefix('event_pass');
        $where = "";
        $id = get_array_value($options, "id");

        if ($id) {
            $where .= " AND $event_pass_table.id=$id";
        }

        $sql = "SELECT $event_pass_table.*, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS full_name
        FROM $event_pass_table 
            LEFT JOIN users ON users.id = $event_pass_table.user_id
        WHERE $event_pass_table.deleted=0 $where";
        return $this->db->query($sql);
    }
}
