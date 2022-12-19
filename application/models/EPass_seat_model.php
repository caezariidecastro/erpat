<?php

class EPass_seat_model extends Crud_model {

    function __construct() {
        $this->table = 'epass_seat';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
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

        $sql = "SELECT $epass_seat_table.*, events.title as event_name, epass_area.event_id as event_id, epass_area.area_name as area_name, epass_block.block_name as block_name, (SELECT COUNT(id) FROM event_pass WHERE FIND_IN_SET($epass_seat_table.id, event_pass.seat_assign)) as assigned
        FROM $epass_seat_table 
            LEFT JOIN epass_block ON epass_block.id = $epass_seat_table.block_id
            LEFT JOIN epass_area ON epass_area.id = epass_block.area_id
            LEFT JOIN events ON events.id = epass_area.event_id
        $where";

        return $this->db->query($sql);
    }

    function get_seats_available($options = array()) {

        $where = "";
        $group_name = get_array_value($options, "group_name"); //TODO

        if($group_name == "seller") {
            $where = "AND (epass_area.area_name LIKE '%Upper Box%' OR epass_area.area_name LIKE '%Gen. Admin%')";
        } else if($group_name == "distributor") {
            $where = "AND (epass_area.area_name LIKE '%Lower Box%' OR epass_area.area_name LIKE '%Upper Box%')";
        } else if($group_name == "franchisee") {
            $where = "AND epass_area.area_name LIKE '%Patron%'";
        } else {
            $where = "AND (epass_area.area_name LIKE '%Upper Box%' OR epass_area.area_name LIKE '%Gen. Admin%')";
        }

        $event_id = get_array_value($options, "event_id");
        $seat_requested = get_array_value($options, "seat_requested");

        $sql = "SELECT epass_area.area_name, epass_seat.id, epass_seat.seat_name, (SELECT COUNT(event_pass.id) FROM event_pass WHERE FIND_IN_SET(epass_seat.id, event_pass.seat_assign)) as assigned, epass_seat.sort
        FROM `epass_seat` 
            INNER JOIN epass_block ON epass_block.id = epass_seat.block_id 
            INNER JOIN epass_area ON epass_area.id = epass_block.area_id 
            LEFT JOIN event_pass ON FIND_IN_SET(epass_seat.id, event_pass.seat_assign) 
        WHERE 
            epass_area.event_id = '$event_id' AND 
            event_pass.id IS NULL $where
        ORDER BY epass_seat.block_id ASC, epass_seat.sort ASC
        LIMIT $seat_requested";

        return $this->db->query($sql);
    }
}
