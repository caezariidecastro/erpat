<?php

class Inventory_transfers_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'inventory_transfers';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $inventory_transfers_table = $this->db->dbprefix('inventory_transfers');
        $where = "";
        $id = get_array_value($options, "id");
        $from = get_array_value($options, "from");
        $to = get_array_value($options, "to");

        if ($id) {
            $where .= " AND $inventory_transfers_table.id=$id";
        }

        if($from){
            $where .= " AND $inventory_transfers_table.warehouse_from = $from";
        }

        if($to){
            $where .= " AND $inventory_transfers_table.warehouse_to = $to";
        }

        $sql = "SELECT $inventory_transfers_table.*, TRIM(CONCAT(creator.first_name, ' ', creator.last_name)) AS creator_name, TRIM(CONCAT(dispatcher.first_name, ' ', dispatcher.last_name)) AS dispatcher_name, TRIM(CONCAT(driver.first_name, ' ', driver.last_name)) AS driver_name, transferee.name AS transferee_name, receiver.name AS receiver_name, (
            0
            -- SELECT COUNT(inventory_transfer_items.id)
            -- FROM inventory_transfer_items
            -- WHERE inventory_transfer_items.transfer_id = $inventory_transfers_table.id
            -- AND inventory_transfer_items.deleted = 0
        ) AS item_count
        FROM $inventory_transfers_table
        LEFT JOIN users creator ON creator.id = $inventory_transfers_table.created_by
        LEFT JOIN users dispatcher ON dispatcher.id = $inventory_transfers_table.dispatcher
        LEFT JOIN users driver ON driver.id = $inventory_transfers_table.driver
        LEFT JOIN warehouses transferee ON transferee.id = $inventory_transfers_table.transferee
        LEFT JOIN warehouses receiver ON receiver.id = $inventory_transfers_table.receiver
        WHERE $inventory_transfers_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function delete_transfer_item($id){
        $this->db->query("UPDATE inventory_transfer_items
        SET deleted = 1
        WHERE id = $id");
    }

    function insert_transfer_item($data){
        $this->db->insert('inventory_transfer_items', $data);
    }
}
