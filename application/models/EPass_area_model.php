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

        $sql = "SELECT $epass_area_table.*, events.title as event_name, 
        (SELECT COUNT(epass_block.id) FROM epass_block WHERE epass_block.area_id = $epass_area_table.id AND epass_block.deleted = '0') as `blocks`,
        (
            SELECT COUNT(epass_seat.id) 
            FROM epass_seat 
                INNER JOIN 
                    epass_block ON epass_block.id = epass_seat.block_id AND epass_block.deleted = 0
            WHERE 
                epass_seat.deleted = '0' AND 
                epass_block.area_id = $epass_area_table.id
        ) as seats
        FROM $epass_area_table 
            LEFT JOIN events ON events.id = $epass_area_table.event_id AND events.deleted = 0
        $where GROUP BY $epass_area_table.id";

        return $this->db->query($sql);
    }
}
