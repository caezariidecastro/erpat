<?php

class Purchase_orders_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'purchase_orders';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $purchase_orders_table = $this->db->dbprefix('purchase_orders');
        $where = "";
        $id = get_array_value($options, "id");
        $vendor_id = get_array_value($options, "vendor_id");

        if ($id) {
            $where .= " AND $purchase_orders_table.id=$id";
        }

        if ($vendor_id) {
            $where .= " AND $purchase_orders_table.vendor_id=$vendor_id";
        }

        $sql = "SELECT $purchase_orders_table.*, TRIM(CONCAT(creator.first_name, ' ', creator.last_name)) AS creator_name, vendor.name AS vendor_name
        , (
            SELECT COALESCE(SUM(purchase_order_materials.total), 0)
            FROM purchase_order_materials
            WHERE purchase_order_materials.purchase_id = $purchase_orders_table.id
        ) AS amount
        FROM $purchase_orders_table
        LEFT JOIN users creator ON creator.id = $purchase_orders_table.created_by
        LEFT JOIN vendors vendor ON vendor.id = $purchase_orders_table.vendor_id
        WHERE $purchase_orders_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
