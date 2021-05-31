<?php

class Address_model extends Crud_model
{
    private $table = null;

    function __construct() {
        $this->table = 'address';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $address_table = $this->db->dbprefix('address');
        //$users_table = $this->db->dbprefix('users');

        $where = "";

        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $address_table.id=$id";
        }

        $uuid = get_array_value($options, "uuid");
        if ($uuid) {
            $where .= " AND $address_table.uuid='$uuid'";
        }

        $sql = "SELECT $address_table.* 
        FROM $address_table 
        WHERE $address_table.active=1 AND $address_table.deleted=0 $where";
        return $this->db->query($sql);
    }
}
