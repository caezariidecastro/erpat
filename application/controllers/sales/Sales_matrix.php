<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sales_matrix extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Sales_matrix_model");
    }

    function index(){
        $this->validate_user_module_permission("module_sms");
        $this->template->rander("sales_matrix/index");
    }

    function list_data(){
        $list_data = $this->Sales_matrix_model->get_details(array(
            'start' => $this->input->post('start_date'),
            'end' => $this->input->post('end_date'),
        ))->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_row($data) {
        return array(
            $data->name,
            $data->category_name,
            number_with_decimal($data->total_sales)
        );
    }
}
