<?php

class Account_transactions_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'account_transactions';
        parent::__construct($this->table);
    }

    private function add_transaction($data){
        $this->db->insert('account_transactions', $data);
    }

    private function update_transaction($transaction, $type, $reference, $data){
        $this->db->where('transaction', $transaction);
        $this->db->where('type', $type);
        $this->db->where('reference', $reference);
        $this->db->update('account_transactions', $data);
    }

    private function delete_transaction($transaction, $type, $reference){
        $this->db->where('transaction', $transaction);
        $this->db->where('type', $type);
        $this->db->where('reference', $reference);
        $this->db->update('account_transactions', array('deleted' => 1));
    }

    // Initial balance

    function add_initial_balance($account_id, $amount, $reference){
        $data = array(
            'account_id' => $account_id,
            'amount' => $amount,
            'transaction' => 1,
            'type' => 1,
            'reference' => $reference
        );

        $this->add_transaction($data);
    }

    function update_initial_balance($reference, $data){
        $this->update_transaction(1, 1, $reference, $data);
    }

    function delete_initial_balance($reference){
        $this->delete_transaction(1, 1, $reference);
    }

    // Expense

    function add_expense($account_id, $amount, $reference){
        $data = array(
            'account_id' => $account_id,
            'amount' => $amount,
            'transaction' => 2,
            'type' => 2,
            'reference' => $reference
        );

        $this->add_transaction($data);
    }

    function update_expense($reference, $data){
        $this->update_transaction(2, 2, $reference, $data);
    }

    function delete_expense($reference){
        $this->delete_transaction(2, 2, $reference);
    }

    // Payment

    function add_payment($account_id, $amount, $reference){
        $data = array(
            'account_id' => $account_id,
            'amount' => $amount,
            'transaction' => 3,
            'type' => 1,
            'reference' => $reference
        );

        $this->add_transaction($data);
    }

    function update_payment($reference, $data){
        $this->update_transaction(3, 1, $reference, $data);
    }

    function delete_payment($reference){
        $this->delete_transaction(3, 1, $reference);
    }

    // Transfer

    function add_transfer($account_id, $amount, $reference, $type){
        $data = array(
            'account_id' => $account_id,
            'amount' => $amount,
            'transaction' => 4,
            'type' => $type,
            'reference' => $reference
        );

        $this->add_transaction($data);
    }

    function update_transfer($type, $reference, $data){
        $this->update_transaction(4, $type, $reference, $data);
    }

    function delete_transfer($reference){
        $this->delete_transaction(4, 2, $reference);
        $this->delete_transaction(4, 1, $reference);
    }

    // Payroll

    function add_payroll($account_id, $amount, $reference){
        $data = array(
            'account_id' => $account_id,
            'amount' => $amount,
            'transaction' => 5,
            'type' => 2,
            'reference' => $reference
        );

        $this->add_transaction($data);
    }

    function update_payroll($reference, $data){
        $this->update_transaction(5, 2, $reference, $data);
    }

    function delete_payroll($reference){
        $this->delete_transaction(5, 2, $reference);
    }

    // Contribution

    function add_contribution($account_id, $amount, $reference){
        $data = array(
            'account_id' => $account_id,
            'amount' => $amount,
            'transaction' => 6,
            'type' => 2,
            'reference' => $reference
        );

        $this->add_transaction($data);
    }

    function update_contribution($reference, $data){
        $this->update_transaction(6, 2, $reference, $data);
    }

    function delete_contribution($reference){
        $this->delete_transaction(6, 2, $reference);
    }

    // Incentive

    function add_incentive($account_id, $amount, $reference){
        $data = array(
            'account_id' => $account_id,
            'amount' => $amount,
            'transaction' => 7,
            'type' => 2,
            'reference' => $reference
        );

        $this->add_transaction($data);
    }

    function update_incentive($reference, $data){
        $this->update_transaction(7, 2, $reference, $data);
    }

    function delete_incentive($reference){
        $this->delete_transaction(7, 2, $reference);
    }

    // Purchase Order

    function add_purchase_order($account_id, $amount, $reference){
        $data = array(
            'account_id' => $account_id,
            'amount' => $amount,
            'transaction' => 8,
            'type' => 2,
            'reference' => $reference
        );

        $this->add_transaction($data);
    }

    function update_purchase_order($reference, $data){
        $this->update_transaction(8, 2, $reference, $data);
    }

    function delete_purchase_order($reference){
        $this->delete_transaction(8, 2, $reference);
    }

    // Purchase Return

    function add_purchase_return($account_id, $amount, $reference){
        $data = array(
            'account_id' => $account_id,
            'amount' => $amount,
            'transaction' => 9,
            'type' => 1,
            'reference' => $reference
        );

        $this->add_transaction($data);
    }

    function update_purchase_return($reference, $data){
        $this->update_transaction(9, 1, $reference, $data);
    }

    function delete_purchase_return($reference){
        $this->delete_transaction(9, 1, $reference);
    }
}
