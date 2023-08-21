<?php

class Access_devices_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'access_devices';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $access_devices_table = $this->db->dbprefix('access_devices');
        $where = " WHERE $access_devices_table.deleted=0 ";

        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $access_devices_table.id=$id";
        }

        $api_key = get_array_value($options, "api_key");
        if ($api_key) {
            $where .= " AND $access_devices_table.api_key='$api_key'";
        }

        $caetegory_id = get_array_value($options, "caetegory_id");
        if ($caetegory_id) {
            $where .= " AND $access_devices_table.caetegory_id=$caetegory_id";
        }

        $sql = "SELECT $access_devices_table.*, access_device_categories.title as category_name
        FROM $access_devices_table 
            LEFT JOIN access_device_categories ON access_device_categories.id = $access_devices_table.category_id
        $where";

        return $this->db->query($sql);
    }
}
