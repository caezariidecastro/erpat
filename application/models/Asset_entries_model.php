<?php

class Asset_entries_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'asset_entries';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $asset_entries_table = $this->db->dbprefix('asset_entries');
        $where = "";
        $id = get_array_value($options, "id");
        $active = get_array_value($options, "active");
        if ($id) {
            $where .= " AND $asset_entries_table.id=$id";
        }
        if ($active) {
            $where .= " AND $asset_entries_table.active=$active";
        }

        $sql = "SELECT $asset_entries_table.*, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS full_name, brand.title AS brand_name, vendor.name AS vendor_name
        , (
            SELECT CONCAT(
                IF(
                    parent3.id IS NOT NULL
                    , CONCAT(parent3.title, ' > ')
                    , ''
                )
                , IF(
                    parent2.id IS NOT NULL
                    , CONCAT(parent2.title, ' > ')
                    , ''
                )
                , IF(
                    parent1.id IS NOT NULL
                    , CONCAT(parent1.title, ' > ')
                    , ''
                )
                , asset_locations.title
            )
            FROM asset_locations
            LEFT JOIN asset_locations parent1 ON parent1.id = asset_locations.parent_id
            LEFT JOIN asset_locations parent2 ON parent2.id = parent1.parent_id
            LEFT JOIN asset_locations parent3 ON parent3.id = parent2.parent_id
            WHERE asset_locations.deleted=0
            AND asset_locations.id = $asset_entries_table.location_id
        ) AS location_name
        , (
            SELECT CONCAT(
                IF(
                    parent3.id IS NOT NULL
                    , CONCAT(parent3.title, ' > ')
                    , ''
                )
                , IF(
                    parent2.id IS NOT NULL
                    , CONCAT(parent2.title, ' > ')
                    , ''
                )
                , IF(
                    parent1.id IS NOT NULL
                    , CONCAT(parent1.title, ' > ')
                    , ''
                )
                , asset_categories.title
            )
            FROM asset_categories
            LEFT JOIN asset_categories parent1 ON parent1.id = asset_categories.parent_id
            LEFT JOIN asset_categories parent2 ON parent2.id = parent1.parent_id
            LEFT JOIN asset_categories parent3 ON parent3.id = parent2.parent_id
            WHERE asset_categories.deleted=0
            AND asset_categories.id = $asset_entries_table.category_id
        ) AS category_name
        FROM $asset_entries_table
        LEFT JOIN users ON users.id = $asset_entries_table.created_by
        LEFT JOIN asset_brands brand ON brand.id = $asset_entries_table.brand_id
        LEFT JOIN vendors vendor ON vendor.id = $asset_entries_table.vendor_id
        WHERE $asset_entries_table.deleted=0 $where";
        return $this->db->query($sql);
    }
}
