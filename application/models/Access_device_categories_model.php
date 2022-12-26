<?php

class Access_device_categories_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'access_device_categories';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $access_device_categories_table = $this->db->dbprefix('access_device_categories');
        $where = " WHERE $access_device_categories_table.deleted=0 ";

        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $access_device_categories_table.id=$id";
        }

        $sql = "SELECT $access_device_categories_table.*
        FROM $access_device_categories_table 
        $where";

        return $this->db->query($sql);
    }
}
