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
        $start = get_array_value($options, "start");
        $end = get_array_value($options, "end");

        if ($id) {
            $where .= " AND $accounts_table.id=$id";
        }

        if($start){
            $where .= " AND $accounts_table.created_on BETWEEN '$start' AND '$end'";
        }

        $sql = "SELECT $accounts_table.*, TRIM(CONCAT(users.first_name, ' ', users.last_name)) AS full_name
        FROM $accounts_table
        LEFT JOIN users ON users.id = $accounts_table.created_by
        WHERE $accounts_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    private function add_amount($account_id, $amount){
        $accounts_table = $this->db->dbprefix('accounts');
        $this->db->query("UPDATE $accounts_table
        SET $accounts_table.initial_balance = $accounts_table.initial_balance + $amount
        WHERE $accounts_table.id = $account_id");
    }

    private function deduct_amount($account_id, $amount){
        $accounts_table = $this->db->dbprefix('accounts');
        $this->db->query("UPDATE $accounts_table
        SET $accounts_table.initial_balance = $accounts_table.initial_balance - $amount
        WHERE $accounts_table.id = $account_id");
    }

    // Expense

    function undo_expense($expense_id){
        $expense = $this->db->query("SELECT account_id, amount
        FROM expenses
        WHERE expenses.id = $expense_id")->row();

        $this->add_amount($expense->account_id, $expense->amount);
    }

    function deduct_expense($account_id, $amount, $expense_id = null){
        if($expense_id){
            $this->undo_expense($expense_id);
        }

        $this->deduct_amount($account_id, $amount);
    }

    // Payment

    function undo_payment($invoice_payment_id){
        $payment = $this->db->query("SELECT account_id, amount
        FROM invoice_payments
        WHERE invoice_payments.id = $invoice_payment_id")->row();

        $this->deduct_amount($payment->account_id, $payment->amount);
    }

    function collect_payment($account_id, $amount, $invoice_payment_id = null){
        if($invoice_payment_id){
            $this->undo_payment($invoice_payment_id);
        }
        
        $this->add_amount($account_id, $amount);
    }

    // Transfer

    function undo_amount_transfer($account_transfer_id){
        $account_transfer = $this->db->query("SELECT account_from, account_to, amount
        FROM account_transfers
        WHERE account_transfers.id = $account_transfer_id")->row();

        $this->add_amount($account_transfer->account_from, $account_transfer->amount);
        $this->deduct_amount($account_transfer->account_to, $account_transfer->amount);
    }

    function transfer_amount($account_from, $account_to, $amount, $account_transfer_id = null){
        if($account_transfer_id){
            $this->undo_amount_transfer($account_transfer_id);
        }

        $this->deduct_amount($account_from, $amount);
        $this->add_amount($account_to, $amount);
    }
}
