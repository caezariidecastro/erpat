<?php

class Asset_brands_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'asset_brands';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $asset_brands_table = $this->db->dbprefix('asset_brands');
        $where = "";
        $id = get_array_value($options, "id");
        $active = get_array_value($options, "active");
        if ($id) {
            $where .= " AND $asset_brands_table.id=$id";
        }
        if ($active) {
            $where .= " AND $asset_brands_table.active=$active";
        }

        $sql = "SELECT $asset_brands_table.*, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS full_name
        FROM $asset_brands_table
        LEFT JOIN users ON users.id = $asset_brands_table.created_by
        WHERE $asset_brands_table.deleted=0 $where";
        return $this->db->query($sql);
    }
}
