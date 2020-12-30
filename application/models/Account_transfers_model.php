<?php

class Account_transfers_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'account_transfers';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $account_transfers_table = $this->db->dbprefix('account_transfers');
        $where = "";
        $id = get_array_value($options, "id");
        $start = get_array_value($options, "start");
        $end = get_array_value($options, "end");

        if ($id) {
            $where .= " AND $account_transfers_table.id=$id";
        }

        if($start){
            $where .= " AND (
                $account_transfers_table.date BETWEEN '$start' AND '$end'
                OR
                $account_transfers_table.date BETWEEN '$start' AND '$end'
            )";
        }

        $sql = "SELECT $account_transfers_table.*, TRIM(CONCAT(creator.first_name, ' ', creator.last_name)) AS creator_name, TRIM(CONCAT(transferee.first_name, ' ', transferee.last_name)) AS transferee_name, TRIM(CONCAT(recipient.first_name, ' ', recipient.last_name)) AS recipient_name
        FROM $account_transfers_table
        LEFT JOIN users creator ON creator.id = $account_transfers_table.created_by
        LEFT JOIN users transferee ON transferee.id = $account_transfers_table.account_from
        LEFT JOIN users recipient ON recipient.id = $account_transfers_table.account_to
        WHERE $account_transfers_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
