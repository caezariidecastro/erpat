<?php

class Payroll_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'payroll';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $payroll_table = $this->db->dbprefix('payroll');
        $where = "";
        $id = get_array_value($options, "id");
        $category = get_array_value($options, "category");
        $start = get_array_value($options, "start");
        $end = get_array_value($options, "end");
        $employee_id = get_array_value($options, "employee_id");
        $account_id = get_array_value($options, "account_id");

        if ($id) {
            $where .= " AND $payroll_table.id=$id";
        }

        if($category){
            $where .= " AND $payroll_table.category = $category";
        }

        if($start){
            $where .= " AND $payroll_table.created_on BETWEEN '$start' AND '$end'";
        }

        if($employee_id){
            $where .= " AND $payroll_table.employee_id = $employee_id";
        }

        if($account_id){
            $where .= " AND $payroll_table.account_id = $account_id";
        }

        $sql = "SELECT $payroll_table.*
        , TRIM(CONCAT(creator.first_name, ' ', creator.last_name)) AS creator_name
        , TRIM(CONCAT(emp.first_name, ' ', emp.last_name)) AS employee_name
        , accounts.name AS account_name
        , pm.title AS payment_method
        , expenses.amount
        FROM $payroll_table
        LEFT JOIN users creator ON creator.id = $payroll_table.created_by
        LEFT JOIN users emp ON emp.id = $payroll_table.employee_id
        LEFT JOIN accounts ON accounts.id = $payroll_table.account_id
        LEFT JOIN payment_methods pm ON pm.id = $payroll_table.payment_method_id
        LEFT JOIN expenses ON expenses.id = $payroll_table.expense_id
        WHERE $payroll_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
