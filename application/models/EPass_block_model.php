<?php

class EPass_block_model extends Crud_model {

    function __construct() {
        $this->table = 'epass_block';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $epass_block_table = $this->db->dbprefix('epass_block');
        $where = " WHERE $epass_block_table.deleted=0 ";

        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $epass_block_table.id=$id";
        }

        $area_id = get_array_value($options, "area_id");
        if ($area_id) {
            $where .= " AND $epass_block_table.area_id = $area_id";
        }

        $sql = "SELECT $epass_block_table.*, events.title as event_name, epass_area.area_name as area_name, epass_area.event_id as event_id, 
        (SELECT COUNT(id) FROM epass_seat WHERE epass_seat.block_id = $epass_block_table.id AND epass_seat.deleted = '0') as `seats`
        FROM $epass_block_table 
            LEFT JOIN epass_area ON epass_area.id = $epass_block_table.area_id AND epass_area.deleted = 0
            LEFT JOIN events ON events.id = epass_area.event_id AND events.deleted = 0
        $where";

        return $this->db->query($sql);
    }
}
