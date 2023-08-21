<?php

class Loan_categories_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'loan_categories';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $category_table = $this->db->dbprefix('loan_categories');
        $users_table = $this->db->dbprefix('users');

        $where = "";

        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $category_table.id=$id";
        }

        $sql = "SELECT $category_table.*, CONCAT(user_table.first_name, ' ',user_table.last_name) AS created_name
        FROM $category_table 
            LEFT JOIN $users_table AS user_table ON user_table.id=$category_table.created_by 
        WHERE $category_table.deleted=0 $where";
        
        return $this->db->query($sql);
    }
}
