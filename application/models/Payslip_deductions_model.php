<?php

class Payslip_deductions_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'payslips_deductions';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $where = "";

        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND {$this->table}.id=$id";
        }

        $payslip_id = get_array_value($options, "payslip_id");
        if($payslip_id){
            $where .= " AND {$this->table}.payslip_id = $payslip_id";
        }

        $item_key = get_array_value($options, "item_key");
        if($item_key){
            $where .= " AND {$this->table}.item_key = '$item_key'";
        }

        $sql = "SELECT {$this->table}.*
        FROM {$this->table}
        WHERE {$this->table}.deleted=0 $where";
        return $this->db->query($sql);
    }

}
