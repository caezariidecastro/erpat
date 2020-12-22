<?php

class Contribution_entries_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'contribution_entries';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $contribution_entries_table = $this->db->dbprefix('contribution_entries');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $contribution_entries_table.id=$id";
        }

        $sql = "SELECT $contribution_entries_table.*, TRIM(CONCAT(emp.first_name, ' ', emp.last_name)) AS employee_name, TRIM(CONCAT(creator.first_name, ' ', creator.last_name)) AS creator_name, cat.title AS category_name
        FROM $contribution_entries_table
        LEFT JOIN users emp ON emp.id = $contribution_entries_table.employee
        LEFT JOIN users creator ON creator.id = $contribution_entries_table.created_by
        LEFT JOIN contribution_categories cat ON cat.id = $contribution_entries_table.category
        WHERE $contribution_entries_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
