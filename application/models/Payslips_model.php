<?php

class Payslips_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'payslips';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $where = "";
        $id = get_array_value($options, "id");
        $payroll_id = get_array_value($options, "payroll_id");
        $user_id = get_array_value($options, "user_id");
        $department_id = get_array_value($options, "department_id");
        $status = get_array_value($options, "status");

        if ($id) {
            $where .= " AND {$this->table}.id=$id";
        }

        if($payroll_id){
            $where .= " AND {$this->table}.payroll = $payroll_id";
        }

        if($user_id){
            $where .= " AND {$this->table}.user = $user_id";
        }

        if($status){
            $where .= " AND {$this->table}.status='$status'";
        }

        if($department_id){
            $where .= " AND instance.department = $department_id";
        }

        $sql = "SELECT {$this->table}.*, 
        TRIM(CONCAT(employee.first_name, ' ', employee.last_name)) AS employee_name, 
        TRIM(CONCAT(signee.first_name, ' ', signee.last_name)) AS signee_name, 
        employee.job_title, department.title AS department_name
        FROM {$this->table}
        LEFT JOIN payrolls instance ON instance.id = {$this->table}.payroll
        LEFT JOIN team department ON department.id = instance.department
        LEFT JOIN users employee ON employee.id = {$this->table}.user
        LEFT JOIN users signee ON signee.id = {$this->table}.signed_by

        WHERE {$this->table}.deleted=0 $where";
        return $this->db->query($sql);
    }

}
