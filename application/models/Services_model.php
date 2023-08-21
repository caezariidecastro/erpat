<?php

class Services_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'services';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $services_table = $this->db->dbprefix('services');
        $where = "";
        $id = get_array_value($options, "id");
        $category = get_array_value($options, "category");
        if ($id) {
            $where .= " AND $services_table.id=$id";
        }
        if ($category) {
            $where .= " AND $services_table.category_id='$category'";
        }
        
        $labels = get_array_value($options, "labels");
        if ($labels) {
            $where .= " AND (FIND_IN_SET('$labels', $services_table.labels)) ";
        }
        $status = get_array_value($options, "status");
        if ($status) {
            $where .= " AND $services_table.status='$status'";
        }

        $is_unofficial = get_array_value($options, "is_unofficial");
        if ($is_unofficial) {
            $where .= " AND $services_table.unofficial IS NOT NULL";
        } else {
            $where .= " AND $services_table.unofficial IS NULL";
        }

        $select_labels_data_query = $this->get_labels_data_query();

        $sql = "SELECT $services_table.*, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS full_name, cat.title AS category_name, $select_labels_data_query 
        FROM $services_table
        LEFT JOIN users ON users.id = $services_table.created_by
        LEFT JOIN services_categories cat ON cat.id = $services_table.category_id
        WHERE $services_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
