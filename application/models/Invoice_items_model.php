<?php

class Invoice_items_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'invoice_items';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $invoice_items_table = $this->db->dbprefix('invoice_items');
        $invoices_table = $this->db->dbprefix('invoices');
        $clients_table = $this->db->dbprefix('clients');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $invoice_items_table.id=$id";
        }
        $invoice_id = get_array_value($options, "invoice_id");
        if ($invoice_id) {
            $where .= " AND $invoice_items_table.invoice_id=$invoice_id";
        }

        $sql = "SELECT $invoice_items_table.*, (SELECT $clients_table.currency_symbol FROM $clients_table WHERE $clients_table.id=$invoices_table.client_id limit 1) AS currency_symbol, warehouses.name AS warehouse_name
        FROM $invoice_items_table
        LEFT JOIN $invoices_table ON $invoices_table.id=$invoice_items_table.invoice_id
        LEFT JOIN inventory ON inventory.id = $invoice_items_table.inventory_id
        LEFT JOIN warehouses ON warehouses.id = inventory.warehouse
        WHERE $invoice_items_table.deleted=0 $where
        ORDER BY $invoice_items_table.sort ASC";
        return $this->db->query($sql);
    }

    function get_item_suggestion($keyword = "") {
        $items_table = $this->db->dbprefix('services');
        
        $limits = 1000;
        $sql = "SELECT $items_table.title,  $items_table.unofficial
            FROM $items_table
            WHERE $items_table.deleted=0
                AND $items_table.active=1  
                AND $items_table.title LIKE '%$keyword%'
            LIMIT $limits 
        ";
        return $this->db->query($sql)->result();
    }

    function get_item_info_suggestion($item_name = "") {

        $items_table = $this->db->dbprefix('services');
        

        $sql = "SELECT $items_table.*
        FROM $items_table
        WHERE $items_table.deleted=0  AND $items_table.title LIKE '%$item_name%'
        ORDER BY id DESC LIMIT 1
        ";
        
        $result = $this->db->query($sql); 

        if ($result->num_rows()) {
            return $result->row();
        }

    }

}
