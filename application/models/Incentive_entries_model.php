<?php

class Incentive_entries_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'incentive_entries';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $incentive_entries_table = $this->db->dbprefix('incentive_entries');
        $where = "";
        $id = get_array_value($options, "id");
        $category = get_array_value($options, "category");
        $start = get_array_value($options, "start");
        $end = get_array_value($options, "end");

        if ($id) {
            $where .= " AND $incentive_entries_table.id=$id";
        }

        if($category){
            $where .= " AND $incentive_entries_table.category = $category";
        }

        if($start){
            $where .= " AND $incentive_entries_table.created_on BETWEEN '$start' AND '$end'";
        }

        $sql = "SELECT $incentive_entries_table.*, TRIM(CONCAT(emp.first_name, ' ', emp.last_name)) AS employee_name, TRIM(CONCAT(sb.first_name, ' ', sb.last_name)) AS signed_by_name, TRIM(CONCAT(creator.first_name, ' ', creator.last_name)) AS creator_name, cat.title AS category_name
        FROM $incentive_entries_table
        LEFT JOIN users emp ON emp.id = $incentive_entries_table.user
        LEFT JOIN users sb ON sb.id = $incentive_entries_table.signed_by
        LEFT JOIN users creator ON creator.id = $incentive_entries_table.created_by
        LEFT JOIN incentive_categories cat ON cat.id = $incentive_entries_table.category
        WHERE $incentive_entries_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
