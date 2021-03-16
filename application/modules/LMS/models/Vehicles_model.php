<?php

class Vehicles_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'vehicles';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $vehicles_table = $this->db->dbprefix('vehicles');
        $where = "";
        $id = get_array_value($options, "id");
        $start = get_array_value($options, "start");
        $end = get_array_value($options, "end");

        if ($id) {
            $where .= " AND $vehicles_table.id=$id";
        }

        if($start){
            $where .= " AND (
                $vehicles_table.date_from BETWEEN '$start' AND '$end'
                OR
                $vehicles_table.date_to BETWEEN '$start' AND '$end'
            )";
        }

        $sql = "SELECT $vehicles_table.*, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS full_name
        FROM $vehicles_table
        LEFT JOIN users ON users.id = $vehicles_table.created_by
        WHERE $vehicles_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
