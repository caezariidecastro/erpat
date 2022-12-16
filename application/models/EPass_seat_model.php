<?php

class EPass_seat_model extends Crud_model {

    function __construct() {
        $this->table = 'epass_seat';
        parent::__construct($this->table);
    }

    function get_details($options = array(), $lists = false) {
        $epass_seat_table = $this->db->dbprefix('epass_seat');
        $where = " WHERE $epass_seat_table.deleted=0 ";

        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $epass_seat_table.id=$id";
        }

        $event_id = get_array_value($options, "event_id");
        if ($event_id) {
            $where .= " AND $epass_seat_table.event_id = $event_id";
        }

        $sql = "SELECT $epass_seat_table.*, events.title as event_name, epass_area.event_id as event_id, epass_area.area_name as area_name, epass_block.block_name as block_name
        FROM $epass_seat_table 
            LEFT JOIN epass_block ON epass_block.id = $epass_seat_table.block_id
            LEFT JOIN epass_area ON epass_area.id = epass_block.area_id
            LEFT JOIN events ON events.id = epass_area.event_id
        $where";

        return $this->db->query($sql);
    }
}
