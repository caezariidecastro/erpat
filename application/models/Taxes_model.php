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

    function get_daily_raw_default() {
        return [
            array(1, 0, 684, 0, 0),
            array(2, 685, 1095, 0, 0.20),
            array(3, 1096, 2191, 82.19, 0.25),
            array(4, 2192, 5478, 356.16, 0.30),
            array(5, 5479, 21917, 1342.47, 0.32),
            array(6, 21918, 999999999, 6602.74, 0.35),
        ];
    }

    function get_weekly_raw_default() {
        return [
            array(1, 0, 4807, 0, 0),
            array(2, 4808, 7691, 0, 0.20),
            array(3, 7692, 15384, 576.92, 0.25),
            array(4, 15385, 38461, 2500, 0.30),
            array(5, 38462, 153845, 9423.08, 0.32),
            array(6, 153846, 999999999, 46346.15, 0.35),
        ];
    }

    function get_biweekly_raw_default() {
        return [
            array(1, 0, 10416, 0, 0),
            array(2, 10417, 16666, 0, 0.20),
            array(3, 16667, 33332, 1250, 0.25),
            array(4, 33333, 83332, 5416.67, 0.30),
            array(5, 83333, 333332, 20416.67, 0.32),
            array(6, 333333, 999999999, 100416.67, 0.35),
        ];
    }

    function get_monthly_raw_default() {
        return [
            array(1, 0, 20832, 0, 0),
            array(2, 20833, 33332, 0, 0.20),
            array(3, 33333, 66666, 2500, 0.25),
            array(4, 66667, 166666, 10833.33, 0.30),
            array(5, 166667, 666666, 40833.33, 0.32),
            array(6, 666667, 999999999, 200833.33, 0.35),
        ];
    }
}
