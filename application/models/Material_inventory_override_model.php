<?php

class Material_inventory_override_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'material_inventory_stock_override';
        parent::__construct($this->table);
    }
}