<?php

class Positions_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'positions';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $positions_table = $this->db->dbprefix('positions');
        $where = "";
        $id = get_array_value($options, "id");

        if ($id) {
            $where .= " AND $positions_table.id=$id";
        }

        $warehouse_id = get_array_value($options, "warehouse_id");
        if ($warehouse_id) {
            $where .= " AND zones.warehouse_id=$warehouse_id";
        }

        $zone_id = get_array_value($options, "zone_id");
        if ($zone_id) {
            $where .= " AND racks.zone_id=$zone_id";
        }

        $rack_id = get_array_value($options, "rack_id");
        if ($rack_id) {
            $where .= " AND bays.rack_id=$rack_id";
        }

        $bay_id = get_array_value($options, "bay_id");
        if ($bay_id) {
            $where .= " AND $positions_table.bay_id=$bay_id";
        }

        $level_id = get_array_value($options, "level_id");
        if ($level_id) {
            $where .= " AND $positions_table.level_id=$level_id";
        }

        $status = get_array_value($options, "status");
        if ($status) {
            $where .= " AND $positions_table.status='$status'";
        }

        $labels = get_array_value($options, "label_id");
        if ($labels) {
            $where .= " AND (FIND_IN_SET('$labels', $positions_table.labels)) ";
        }

        $select_labels_data_query = $this->get_labels_data_query();

        $sql = "SELECT $positions_table.*, creator.id as creator_id, TRIM(CONCAT(creator.first_name, ' ', creator.last_name)) AS created_by, warehouses.name AS warehouse_name, warehouses.id AS warehouse_id, racks.zone_id as zone_id, bays.rack_id as rack_id, levels.bay_id as bay_id, $positions_table.level_id as level_id, $select_labels_data_query
        FROM $positions_table
        LEFT JOIN users creator ON creator.id = $positions_table.created_by
        LEFT JOIN levels ON levels.id = $positions_table.level_id
        LEFT JOIN bays ON bays.id = levels.bay_id
        LEFT JOIN racks ON racks.id = bays.rack_id
        LEFT JOIN zones ON zones.id = racks.zone_id
        LEFT JOIN warehouses ON warehouses.id = zones.warehouse_id 
        WHERE $positions_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
