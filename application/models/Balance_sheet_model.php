<?php

class Balance_sheet_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'balance_sheet_model';
        parent::__construct($this->table);
    }
}
