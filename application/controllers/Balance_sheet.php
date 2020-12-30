<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Balance_sheet extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Accounts_model");
        $this->load->model("Account_transfers_model");
    }

    function index(){
        $this->template->rander("balance_sheet/index");
    }

    function list_data(){
        $list_data = $this->Accounts_model->get_balance_sheet()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_row($data) {
        return array(
            $data->name,
            $data->number,
            number_with_decimal($data->debit),
            number_with_decimal($data->credit),
            number_with_decimal($data->debit - $data->credit)
        );
    }
}
