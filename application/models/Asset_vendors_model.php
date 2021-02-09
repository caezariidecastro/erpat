<?php

class Asset_vendors_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'asset_vendors';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $asset_vendors_table = $this->db->dbprefix('asset_vendors');
        $where = "";
        $id = get_array_value($options, "id");
        $active = get_array_value($options, "active");
        if ($id) {
            $where .= " AND $asset_vendors_table.id=$id";
        }
        if ($active) {
            $where .= " AND $asset_vendors_table.active=$active";
        }

        $sql = "SELECT $asset_vendors_table.*, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS full_name
        FROM $asset_vendors_table
        LEFT JOIN users ON users.id = $asset_vendors_table.created_by
        WHERE $asset_vendors_table.deleted=0 $where";
        return $this->db->query($sql);
    }
}
