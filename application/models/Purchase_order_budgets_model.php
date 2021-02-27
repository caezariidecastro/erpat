<?php

class Purchase_order_budgets_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'purchase_order_budgets';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $purchase_order_budgets_table = $this->db->dbprefix('purchase_order_budgets');
        $where = "";
        $id = get_array_value($options, "id");
        $purchase_id = get_array_value($options, "purchase_id");
        if ($id) {
            $where .= " AND $purchase_order_budgets_table.id=$id";
        }
        if ($purchase_id) {
            $where .= " AND $purchase_order_budgets_table.purchase_id=$purchase_id";
        }

        $sql = "SELECT $purchase_order_budgets_table.*, CONCAT(TRIM(creator.first_name), ' ', TRIM(creator.last_name)) AS creator_name
        FROM $purchase_order_budgets_table
        LEFT JOIN users creator ON creator.id = $purchase_order_budgets_table.created_by
        WHERE $purchase_order_budgets_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function get_purchase_total_budget($purchase_id){
        $purchase_order_budgets_table = $this->db->dbprefix('purchase_order_budgets');

        $sql = "SELECT COALESCE(SUM($purchase_order_budgets_table.amount), 0) AS total
        FROM $purchase_order_budgets_table
        WHERE $purchase_order_budgets_table.deleted=0
        AND $purchase_order_budgets_table.purchase_id = $purchase_id";
        return $this->db->query($sql)->row()->total;
    }
}
