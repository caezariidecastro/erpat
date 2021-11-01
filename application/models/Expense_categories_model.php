<?php

class Expense_categories_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'expense_categories';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $expense_categories_table = $this->db->dbprefix('expense_categories');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $expense_categories_table.id=$id";
        }

        $sql = "SELECT $expense_categories_table.*
        FROM $expense_categories_table
        WHERE $expense_categories_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function get_details_by_title($title) {
        $expense_categories_table = $this->db->dbprefix('expense_categories');
        $sql = "SELECT $expense_categories_table.*
        FROM $expense_categories_table
        WHERE $expense_categories_table.deleted=0 
        AND $expense_categories_table.title = '$title'";
        return $this->db->query($sql);
    }

    function get_default_expense_categories() {
        $expense_categories_table = $this->db->dbprefix('expense_categories');

        $sql = "SELECT $expense_categories_table.id 
        FROM $expense_categories_table
        WHERE id <= 3 AND is_editable = 1";
        return $this->db->query($sql)->result();
    }

    function secure_default($id) {
        $expense_categories = $this->db->dbprefix('expense_categories');
    
        $sql = "UPDATE $expense_categories 
        SET is_editable='0' 
        WHERE id = '$id'";
        return $this->db->query($sql);
    }
}
