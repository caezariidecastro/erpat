<?php

class Payrolls_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'payrolls';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $where = "";
        $id = get_array_value($options, "id");
        $category = get_array_value($options, "category");
        $start = get_array_value($options, "start");
        $end = get_array_value($options, "end");
        $account_id = get_array_value($options, "account_id");
        $department_id = get_array_value($options, "department_id");

        if ($id) {
            $where .= " AND {$this->table}.id=$id";
        }

        if($category){
            $where .= " AND {$this->table}.category = $category";
        }

        if($start){
            $where .= " AND {$this->table}.pay_date BETWEEN '$start' AND '$end'";
        }

        if($account_id){
            $where .= " AND {$this->table}.account_id = $account_id";
        }

        if($department_id){
            $where .= " AND {$this->table}.department = $department_id";
        }

        $sql = "SELECT {$this->table}.*, 
        TRIM(CONCAT(creator.first_name, ' ', creator.last_name)) AS creator_name, 
        TRIM(CONCAT(signee.first_name, ' ', signee.last_name)) AS signee_name, 
        TRIM(CONCAT(accountant.first_name, ' ', accountant.last_name)) AS accountant_name, 
        department.title AS department_name, 
        (SELECT count(id) FROM payslips WHERE payslips.payroll = {$this->table}.id AND deleted=0) AS total_payslip,
        acct.name AS account_name 
        FROM {$this->table}
        LEFT JOIN accounts acct ON acct.id = {$this->table}.account_id
        LEFT JOIN team department ON department.id = {$this->table}.department
        LEFT JOIN users creator ON creator.id = {$this->table}.created_by
        LEFT JOIN users accountant ON accountant.id = {$this->table}.accountant_id
        LEFT JOIN users signee ON signee.id = {$this->table}.signed_by
        WHERE {$this->table}.deleted=0 $where";
        return $this->db->query($sql);
    }

}
