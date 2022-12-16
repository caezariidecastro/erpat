<?php

class EPass_area_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'epass_area';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $epass_area_table = $this->db->dbprefix('epass_area');
        $where = " WHERE $epass_area_table.deleted=0 ";

        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $epass_area_table.id=$id";
        }

        $event_id = get_array_value($options, "event_id");
        if ($event_id) {
            $where .= " AND $epass_area_table.event_id = $event_id";
        }

        $sql = "SELECT $epass_area_table.*, events.title as event_name
        FROM $epass_area_table 
            LEFT JOIN events ON events.id = $epass_area_table.event_id
        $where";

        return $this->db->query($sql);
    }
}
