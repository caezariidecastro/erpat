<?php

class Deliveries_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'deliveries';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $deliveries_table = $this->db->dbprefix('deliveries');
        $where = "";
        $id = get_array_value($options, "id");
        $warehouse = get_array_value($options, "warehouse");
        $consumer = get_array_value($options, "consumer");

        if ($id) {
            $where .= " AND $deliveries_table.id=$id";
        }

        if($warehouse){
            $where .= " AND $deliveries_table.warehouse = $warehouse";
        }

        if($consumer){
            $where .= " AND $deliveries_table.consumer = $consumer";
        }

        $sql = "SELECT $deliveries_table.*, TRIM(CONCAT(creator.first_name, ' ', creator.last_name)) AS creator_name, TRIM(CONCAT(dispatcher.first_name, ' ', dispatcher.last_name)) AS dispatcher_name, TRIM(CONCAT(driver.first_name, ' ', driver.last_name)) AS driver_name, warehouse.name AS warehouse_name, TRIM(CONCAT(vehicle.brand,  ' ', vehicle.model, ' ', vehicle.year, ' ', vehicle.color)) AS vehicle_name, TRIM(CONCAT(consumer.first_name, ' ', consumer.last_name)) AS consumer_name, (
            SELECT COUNT(delivery_items.id)
            FROM delivery_items
            WHERE delivery_items.reference_number = $deliveries_table.reference_number
            AND delivery_items.deleted = 0
        ) AS item_count
        FROM $deliveries_table
        LEFT JOIN users creator ON creator.id = $deliveries_table.created_by
        LEFT JOIN users dispatcher ON dispatcher.id = $deliveries_table.dispatcher
        LEFT JOIN users driver ON driver.id = $deliveries_table.driver
        LEFT JOIN users consumer ON consumer.id = $deliveries_table.consumer
        LEFT JOIN warehouses warehouse ON warehouse.id = $deliveries_table.warehouse
        LEFT JOIN vehicles vehicle ON vehicle.id = $deliveries_table.vehicle
        WHERE $deliveries_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function delete_delivery_item($reference_number){
        $this->db->query("UPDATE delivery_items
        SET deleted = 1
        WHERE reference_number = '$reference_number'");
    }

    function insert_delivery_item($data){
        $this->db->insert('delivery_items', $data);
    }

    function get_delivered_items($reference_number){
        $sql = "SELECT delivery_items.*, i.name AS item_name, i.id AS id
        FROM delivery_items
        LEFT JOIN inventory i ON i.id = delivery_items.inventory_id
        WHERE delivery_items.reference_number = '$reference_number'
        AND delivery_items.deleted = 0";
        return $this->db->query($sql);
    }
}
