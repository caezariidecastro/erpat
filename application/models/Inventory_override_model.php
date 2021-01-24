<?php

class Inventory_override_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'inventory_stock_override';
        parent::__construct($this->table);
    }
}