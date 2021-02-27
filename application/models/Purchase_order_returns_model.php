<?php

class Purchase_order_returns_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'purchase_order_returns';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $purchase_order_returns_table = $this->db->dbprefix('purchase_order_returns');
        $where = "";
        $id = get_array_value($options, "id");
        $vendor_id = get_array_value($options, "vendor_id");

        if ($id) {
            $where .= " AND $purchase_order_returns_table.id=$id";
        }

        if ($vendor_id) {
            $where .= " AND purchase_orders.vendor_id=$vendor_id";
        }

        $sql = "SELECT $purchase_order_returns_table.*, TRIM(CONCAT(creator.first_name, ' ', creator.last_name)) AS creator_name, vendor.name AS vendor_name
        , 0 AS amount
        FROM $purchase_order_returns_table
        LEFT JOIN users creator ON creator.id = $purchase_order_returns_table.created_by
        LEFT JOIN purchase_orders ON purchase_orders.id = $purchase_order_returns_table.purchase_id
        LEFT JOIN vendors vendor ON vendor.id = purchase_orders.vendor_id
        WHERE $purchase_order_returns_table.deleted = 0 $where
        AND purchase_orders.deleted = 0";
        return $this->db->query($sql);
    }

    function is_purchase_has_return($purchase_id) {
        $purchase_order_returns_table = $this->db->dbprefix('purchase_order_returns');

        $sql = "SELECT COUNT(*) AS count
        FROM $purchase_order_returns_table
        WHERE $purchase_order_returns_table.deleted = 0 
        AND $purchase_order_returns_table.purchase_id = $purchase_id";
        return $this->db->query($sql)->row()->count;
    }
}
