<?php

class Holidays_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'holidays';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $holidays_table = $this->db->dbprefix('holidays');
        $where = "";
        $id = get_array_value($options, "id");
        $start = get_array_value($options, "start");
        $end = get_array_value($options, "end");
        $type = get_array_value($options, "type");

        if ($id) {
            $where .= " AND $holidays_table.id=$id";
        }

        if ($type) {
            $where .= " AND $holidays_table.type='$type'";
        }

        if($start){
            $where .= " AND (
                $holidays_table.date_from BETWEEN '$start' AND '$end'
                OR
                $holidays_table.date_to BETWEEN '$start' AND '$end'
            )";
        }

        $sql = "SELECT $holidays_table.*, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS full_name
        FROM $holidays_table
            LEFT JOIN users ON users.id = $holidays_table.created_by
        WHERE $holidays_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
