<?php

class Asset_locations_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'asset_locations';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $asset_locations_table = $this->db->dbprefix('asset_locations');
        $where = "";
        $id = get_array_value($options, "id");
        $active = get_array_value($options, "active");
        if ($id) {
            $where .= " AND $asset_locations_table.id=$id";
        }
        if ($active) {
            $where .= " AND $asset_locations_table.active=$active";
        }

        $sql = "SELECT $asset_locations_table.*, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS full_name, parent.title AS parent_name
        FROM $asset_locations_table
        LEFT JOIN users ON users.id = $asset_locations_table.created_by
        LEFT JOIN $asset_locations_table parent ON parent.id = $asset_locations_table.parent_id
        WHERE $asset_locations_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function get_parenting_count($id){
        $asset_locations_table = $this->db->dbprefix('asset_locations');
        $sql = "SELECT COUNT($asset_locations_table.id) + COUNT(parent1.id) + COUNT(parent2.id) AS parenting_count
        FROM $asset_locations_table
        LEFT JOIN $asset_locations_table parent1 ON parent1.id = $asset_locations_table.parent_id
        LEFT JOIN $asset_locations_table parent2 ON parent2.id = parent1.parent_id
        WHERE $asset_locations_table.deleted=0
        AND $asset_locations_table.id = $id";
        return $this->db->query($sql);
    }
}
