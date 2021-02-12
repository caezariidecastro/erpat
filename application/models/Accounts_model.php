<?php

class Accounts_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'accounts';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $accounts_table = $this->db->dbprefix('accounts');
        $where = "";
        $id = get_array_value($options, "id");

        if ($id) {
            $where .= " AND $accounts_table.id=$id";
        }

        $sql = "SELECT $accounts_table.*, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS full_name
        FROM $accounts_table
        LEFT JOIN users ON users.id = $accounts_table.created_by
        WHERE $accounts_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function get_balance_sheet(){
        $accounts_table = $this->db->dbprefix('accounts');
        $sql = "SELECT $accounts_table.*,
        (
            SELECT SUM(account_transactions.amount)
            FROM account_transactions
            WHERE account_transactions.account_id = $accounts_table.id
            AND account_transactions.deleted = 0
            AND account_transactions.type = 'debit'
        ) AS debit,
        (
            SELECT SUM(account_transactions.amount)
            FROM account_transactions
            WHERE account_transactions.account_id = $accounts_table.id
            AND account_transactions.deleted = 0
            AND account_transactions.type = 'credit'
        ) AS credit
        FROM $accounts_table
        WHERE $accounts_table.deleted=0";
        return $this->db->query($sql);
    }
}
