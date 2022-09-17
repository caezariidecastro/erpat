<?php

class Taxes_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'taxes';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $taxes_table = $this->db->dbprefix('taxes');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $taxes_table.id=$id";
        }

        $sql = "SELECT $taxes_table.*
        FROM $taxes_table
        WHERE $taxes_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function get_weekly_raw_default() {
        return [
            array(1, 0, 4808, 0, 0),
            array(2, 4808, 7691, 0, 0.20),
            array(3, 7692, 15384, 576.92, 0.25),
            array(4, 15385, 38461, 2500, 0.30),
            array(5, 38462, 153845, 9423.08, 0.32),
            array(6, 153846, 999999999, 46346.15, 0.35),
        ];
    }
}
