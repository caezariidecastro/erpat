<?php

class Racks_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'racks';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $racks_table = $this->db->dbprefix('racks');
        $where = "";
        $id = get_array_value($options, "id");

        if ($id) {
            $where .= " AND $racks_table.id=$id";
        }

        $warehouse_id = get_array_value($options, "warehouse_id");
        if ($warehouse_id) {
            $where .= " AND $racks_table.warehouse_id=$warehouse_id";
        }

        $zone_id = get_array_value($options, "zone_id");
        if ($zone_id) {
            $where .= " AND $racks_table.zone_id=$zone_id";
        }

        $labels = get_array_value($options, "label_id");
        if ($labels) {
            $where .= " AND (FIND_IN_SET('$labels', $racks_table.labels)) ";
        }

        $select_labels_data_query = $this->get_labels_data_query();

        $sql = "SELECT $racks_table.*, creator.id as creator_id, TRIM(CONCAT(creator.first_name, ' ', creator.last_name)) AS created_by, warehouses.name AS warehouse_name, $select_labels_data_query
        FROM $racks_table
        LEFT JOIN users creator ON creator.id = $racks_table.created_by
        LEFT JOIN zones ON zones.id = $racks_table.zone_id
        LEFT JOIN warehouses ON warehouses.id = zones.warehouse_id 
        WHERE $racks_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
