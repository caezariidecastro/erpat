<?php

class Cities_model extends Crud_model
{
	private $table = null;

    function __construct() {
        $this->table = 'cities';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
      $cities_table = $this->db->dbprefix('cities');

      $where = "";

      $id = get_array_value($options, "id");
      if ($id) {
          $where .= " AND $cities_table.id=$id";
      }

      $uuid = get_array_value($options, "uuid");
      if ($uuid) {
          $where .= " AND $cities_table.uuid='$uuid'";
      }

      $sql = "SELECT $cities_table.* 
      FROM $cities_table 
      WHERE $cities_table.active=1 AND $cities_table.deleted=0 $where";
      return $this->db->query($sql);
  }
}