<?php

class Vendors_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'vendors';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $vendors_table = $this->db->dbprefix('vendors');
        $where = "";
        $id = get_array_value($options, "id");
        $status = get_array_value($options, "status");

        if ($id) {
            $where .= " AND $vendors_table.id=$id";
        }

        if ($status) {
            $where .= " AND $vendors_table.status='$status'";
        }

        $sql = "SELECT $vendors_table.*, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS full_name, (
            SELECT COUNT(*)
            FROM users u
            WHERE u.deleted = 0
            AND u.vendor_id = $vendors_table.id
        ) AS contacts
        FROM $vendors_table
        LEFT JOIN users ON users.id = $vendors_table.created_by
        WHERE $vendors_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function get_primary_contact($vendor_id) {
        $users_table = $this->db->dbprefix('users');

        $sql = "SELECT $users_table.id, $users_table.first_name, $users_table.last_name
        FROM $users_table
        WHERE $users_table.deleted=0 
        AND $users_table.vendor_id = $vendor_id
        AND $users_table.is_primary_contact=1";
        return $this->db->query($sql)->row();
    }

    function get_contacts($options) {
        $users_table = $this->db->dbprefix('users');
        $where = "";
        $vendor_id = get_array_value($options, "vendor_id");
        $is_primary_contact = get_array_value($options, "is_primary_contact");

        if ($vendor_id) {
            $where .= " AND $users_table.vendor_id=$vendor_id";
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
