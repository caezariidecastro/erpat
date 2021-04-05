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
        $account_id = get_array_value($options, "account_id");
        $status = get_array_value($options, "status");

        if ($id) {
            $where .= " AND $purchase_orders_table.id=$id";
        }

        if ($vendor_id) {
            $where .= " AND $purchase_orders_table.vendor_id=$vendor_id";
        }

        if ($account_id) {
            $where .= " AND $purchase_orders_table.account_id=$account_id";
        }

        if ($status) {
            $where .= " AND $purchase_orders_table.status='$status'";
        }

        $sql = "SELECT $purchase_orders_table.*, TRIM(CONCAT(creator.first_name, ' ', creator.last_name)) AS creator_name, vendor.name AS vendor_name, account.name AS account_name, CONCAT(TRIM(canceller.first_name), ' ', TRIM(canceller.last_name)) AS cancelled_by_user
        , (
            SELECT COALESCE(SUM(purchase_order_materials.total), 0)
            FROM purchase_order_materials
            WHERE purchase_order_materials.purchase_id = $purchase_orders_table.id
        ) AS amount
        FROM $purchase_orders_table
        LEFT JOIN users creator ON creator.id = $purchase_orders_table.created_by
        LEFT JOIN users canceller ON creator.id = $purchase_orders_table.cancelled_by
        LEFT JOIN vendors vendor ON vendor.id = $purchase_orders_table.vendor_id
        LEFT JOIN accounts account ON account.id = $purchase_orders_table.account_id
        WHERE $purchase_orders_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
