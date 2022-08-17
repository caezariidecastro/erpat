<?php

class Zones_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'zones';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $zones_table = $this->db->dbprefix('zones');
        $where = "";
        $id = get_array_value($options, "id");

        if ($id) {
            $where .= " AND $zones_table.id=$id";
        }

        $warehouse_id = get_array_value($options, "warehouse_id");
        if ($warehouse_id) {
            $where .= " AND $zones_table.warehouse_id=$warehouse_id";
        }

        $status = get_array_value($options, "status");
        if ($status) {
            $where .= " AND $zones_table.status='$status'";
        }

        $labels = get_array_value($options, "label_id");
        if ($labels) {
            $where .= " AND (FIND_IN_SET('$labels', $zones_table.labels)) ";
        }

        $select_labels_data_query = $this->get_labels_data_query();

        $sql = "SELECT $zones_table.*, creator.id as creator_id, TRIM(CONCAT(creator.first_name, ' ', creator.last_name)) AS created_by, warehouses.name AS warehouse_name, $select_labels_data_query
        FROM $zones_table
        LEFT JOIN users creator ON creator.id = $zones_table.created_by
        LEFT JOIN warehouses ON warehouses.id = $zones_table.warehouse_id
        WHERE $zones_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
