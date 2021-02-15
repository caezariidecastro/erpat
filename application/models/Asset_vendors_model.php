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
        $status = get_array_value($options, "status");

        if ($id) {
            $where .= " AND $asset_vendors_table.id=$id";
        }

        if ($status) {
            $where .= " AND $asset_vendors_table.status='$status'";
        }

        $sql = "SELECT $asset_vendors_table.*, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS full_name, (
            SELECT COUNT(*)
            FROM users u
            WHERE u.deleted = 0
            AND u.asset_vendor_id = $asset_vendors_table.id
        ) AS contacts
        FROM $asset_vendors_table
        LEFT JOIN users ON users.id = $asset_vendors_table.created_by
        WHERE $asset_vendors_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function get_primary_contact($asset_vendor_id) {
        $users_table = $this->db->dbprefix('users');

        $sql = "SELECT $users_table.id, $users_table.first_name, $users_table.last_name
        FROM $users_table
        WHERE $users_table.deleted=0 
        AND $users_table.asset_vendor_id = $asset_vendor_id
        AND $users_table.is_primary_contact=1";
        return $this->db->query($sql)->row();
    }

    function get_contacts($options) {
        $users_table = $this->db->dbprefix('users');
        $where = "";
        $asset_vendor_id = get_array_value($options, "asset_vendor_id");
        $is_primary_contact = get_array_value($options, "is_primary_contact");

        if ($asset_vendor_id) {
            $where .= " AND $users_table.asset_vendor_id=$asset_vendor_id";
        }

        if ($is_primary_contact) {
            $where .= " AND $users_table.is_primary_contact=$is_primary_contact";
        }

        $sql = "SELECT $users_table.*, TRIM(CONCAT(creator.first_name, ' ', creator.last_name)) AS creator_name, TRIM(CONCAT($users_table.first_name, ' ', $users_table.last_name)) AS full_name
        FROM $users_table
        LEFT JOIN users creator ON creator.id = $users_table.created_by
        WHERE $users_table.deleted=0 $where";
        return $this->db->query($sql);
    }
}
