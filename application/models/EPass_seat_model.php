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

        $sql = "SELECT $epass_seat_table.*, events.title as event_name, epass_area.event_id as event_id, epass_area.area_name as area_name, epass_block.block_name as block_name, (SELECT COUNT(id) FROM event_pass WHERE FIND_IN_SET($epass_seat_table.id, event_pass.seat_assign)) as assigned, (SELECT TRIM(CONCAT('<a href=''#'' onclick=\'alert(`', 'ePass ID: ', event_pass.uuid, '`);\'>', users.first_name, ' ', users.last_name, '</a>')) FROM event_pass LEFT JOIN users ON users.id = event_pass.user_id WHERE FIND_IN_SET($epass_seat_table.id, event_pass.seat_assign)) as user_assign
        FROM $epass_seat_table 
            LEFT JOIN epass_block ON epass_block.id = $epass_seat_table.block_id AND epass_block.deleted = 0
            LEFT JOIN epass_area ON epass_area.id = epass_block.area_id AND epass_area.deleted = 0
            LEFT JOIN events ON events.id = epass_area.event_id AND events.deleted = 0
        $where ";

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
        } else if($group_name == "viewer") {
            $where = "AND (epass_area.area_name LIKE '%Upper Box%' OR epass_area.area_name LIKE '%Gen. Admin%')";
        } else {
            $where = "";
        }

        if($block_id = get_array_value($options, "block_id")) {
            $block_id = "AND epass_block.id = '$block_id'";
        }

        $event_id = get_array_value($options, "event_id");
        if($seat_requested = get_array_value($options, "seat_requested")) {
            $seat_requested = " LIMIT $seat_requested"; 
        }

        $sql = "SELECT epass_area.area_name, epass_block.block_name, epass_seat.id, epass_seat.seat_name, (SELECT COUNT(event_pass.id) FROM event_pass WHERE FIND_IN_SET(epass_seat.id, event_pass.seat_assign)) as assigned, epass_seat.sort
        FROM `epass_seat` 
            INNER JOIN epass_block ON epass_block.id = epass_seat.block_id AND epass_block.deleted = 0
            INNER JOIN epass_area ON epass_area.id = epass_block.area_id AND epass_area.deleted = 0
            LEFT JOIN event_pass ON FIND_IN_SET(epass_seat.id, event_pass.seat_assign)  AND event_pass.deleted = 0
        WHERE 
            epass_area.deleted = 0 AND
            epass_area.event_id = '$event_id' AND 
            event_pass.id IS NULL $block_id $where
        ORDER BY epass_seat.block_id ASC, epass_seat.sort ASC
        $seat_requested";

        return $this->db->query($sql);
    }
}
