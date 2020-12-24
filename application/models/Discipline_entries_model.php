<?php

class Discipline_entries_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'discipline_entries';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $discipline_entries_table = $this->db->dbprefix('discipline_entries');
        $where = "";
        $id = get_array_value($options, "id");
        $category = get_array_value($options, "category");

        if ($id) {
            $where .= " AND $discipline_entries_table.id=$id";
        }

        if($category){
            $where .= " AND $discipline_entries_table.category = $category";
        }

        $sql = "SELECT $discipline_entries_table.*, TRIM(CONCAT(emp.first_name, ' ', emp.last_name)) AS employee_name, TRIM(CONCAT(wit.first_name, ' ', wit.last_name)) AS witness_name, TRIM(CONCAT(creator.first_name, ' ', creator.last_name)) AS creator_name, cat.title AS category_name
        FROM $discipline_entries_table
        LEFT JOIN users emp ON emp.id = $discipline_entries_table.employee
        LEFT JOIN users wit ON wit.id = $discipline_entries_table.witness
        LEFT JOIN users creator ON creator.id = $discipline_entries_table.created_by
        LEFT JOIN discipline_categories cat ON cat.id = $discipline_entries_table.category
        WHERE $discipline_entries_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
