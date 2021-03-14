<?php

class Material_inventory_transfers_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'material_inventory_transfers';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $material_inventory_transfers_table = $this->db->dbprefix('material_inventory_transfers');
        $where = "";
        $id = get_array_value($options, "id");
        $from = get_array_value($options, "from");
        $to = get_array_value($options, "to");

        if ($id) {
            $where .= " AND $material_inventory_transfers_table.id=$id";
        }

        if($from){
            $where .= " AND $material_inventory_transfers_table.warehouse_from = $from";
        }

        if($to){
            $where .= " AND $material_inventory_transfers_table.warehouse_to = $to";
        }

        $sql = "SELECT $material_inventory_transfers_table.*, TRIM(CONCAT(creator.first_name, ' ', creator.last_name)) AS creator_name, TRIM(CONCAT(dispatcher.first_name, ' ', dispatcher.last_name)) AS dispatcher_name, TRIM(CONCAT(driver.first_name, ' ', driver.last_name)) AS driver_name, transferee.name AS transferee_name, receiver.name AS receiver_name, CONCAT(TRIM(vehicles.brand), ' ', TRIM(vehicles.model), ' ', TRIM(vehicles.year), ' ', TRIM(vehicles.color)) AS vehicle_name, (
            SELECT COUNT(material_inventory_transfer_items.id)
            FROM material_inventory_transfer_items
            WHERE material_inventory_transfer_items.reference_number = $material_inventory_transfers_table.reference_number
            AND material_inventory_transfer_items.deleted = 0
        ) AS item_count
        FROM $material_inventory_transfers_table
        LEFT JOIN users creator ON creator.id = $material_inventory_transfers_table.created_by
        LEFT JOIN users dispatcher ON dispatcher.id = $material_inventory_transfers_table.dispatcher
        LEFT JOIN users driver ON driver.id = $material_inventory_transfers_table.driver
        LEFT JOIN vehicles ON vehicles.id = $material_inventory_transfers_table.vehicle_id
        LEFT JOIN warehouses transferee ON transferee.id = $material_inventory_transfers_table.transferee
        LEFT JOIN warehouses receiver ON receiver.id = $material_inventory_transfers_table.receiver
        WHERE $material_inventory_transfers_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function delete_transfer_item($reference_number){
        $this->db->query("UPDATE material_inventory_transfer_items
        SET deleted = 1
        WHERE reference_number = '$reference_number'");
    }

    function insert_transfer_item($data){
        $this->db->insert('material_inventory_transfer_items', $data);
    }

    function get_transferred_items($reference_number){
        $sql = "SELECT material_inventory_transfer_items.*, material_inventory.name AS item_name, material_inventory.id AS material_inventory_id, units.abbreviation AS unit_abbreviation
        FROM material_inventory_transfer_items
        LEFT JOIN material_inventory ON material_inventory.id = material_inventory_transfer_items.material_inventory_id
        LEFT JOIN units ON units.id = material_inventory.unit
        WHERE material_inventory_transfer_items.reference_number = '$reference_number'
        AND material_inventory_transfer_items.deleted = 0";
        return $this->db->query($sql);
    }
}
