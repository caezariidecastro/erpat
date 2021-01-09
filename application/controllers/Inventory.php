<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Inventory extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Inventory_model");
        $this->load->model("Inventory_item_entries_model");
        $this->load->model("Warehouse_model");
    }

    protected function _get_item_dropdown_data() {
        $items = $this->Inventory_item_entries_model->get_all()->result();
        $item_dropdown = array('' => '-');

        foreach ($items as $group) {
            $item_dropdown[$group->id] = $group->name;
        }
        return $item_dropdown;
    }

    protected function _get_warehouse_dropdown_data() {
        $Warehouses = $this->Warehouse_model->get_all()->result();
        $warehouse_dropdown = array('' => '-');

        foreach ($Warehouses as $group) {
            $warehouse_dropdown[$group->id] = $group->name;
        }
        return $warehouse_dropdown;
    }

    function index(){
        $this->template->rander("inventory/index");
    }

    function item_list_data(){
        $list_data = $this->Inventory_item_entries_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_item_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }
    
    function no_data(){
        echo json_encode(array("data" => []));
    }

    private function _item_make_row($data) {
        $row = '<div class="pull-left item-row" data-id="'.$data->id.'">
                    <div class="media-body">
                        <div class="media-heading">
                            <strong>'.$data->name.'</strong>
                            <span class="text-off pull-right">Stocks on hand: '.$data->stocks_on_hand.'</span>
                        </div>
                        '.$data->sku.'
                    </div>
                </div>';

        return array(
            $row
        );
    }

    function add_inventory_modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = "";
        $view_data['warehouse_dropdown'] = $this->_get_warehouse_dropdown_data();
        $view_data['item_dropdown'] = $this->_get_item_dropdown_data();

        $this->load->view('inventory/add', $view_data);
    }


    private function _inventory_make_row($data) {
        $detail = '<div class="pull-left item-row" data-id="'.$data->id.'">
                    <div class="media-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="media-heading">
                                    <strong>'.$data->warehouse_name.'</strong>
                                </div>
                                '.$data->warehouse_address.'
                            </div>
                            <div class="col-md-6">
                                <div class="text-off pull-right">
                                    Available stocks: '.$data->stock.'
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';

        return array(
            $detail,
            js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("inventory/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function add(){
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $item = $this->Inventory_item_entries_model->get_details(array(
            'id' => $this->input->post('item')
        ))->row();
        $warehouse = $this->input->post('warehouse');

        $inventory_data = array(
            "warehouse" => $warehouse,
            "stock" => $this->input->post('opening_stock'),
            "item_id" => $item->id,
            "name" => $item->name,
            "sku" => $item->sku,
            "unit" => $item->unit,
            "category" => $item->category,
            "cost_price" => $item->cost_price,
            "selling_price" => $item->selling_price,
            "vendor" => $item->vendor,
            "created_on" => date('Y-m-d H:i:s'),
            "created_by" => $this->login_user->id
        );

        $item_on_warehouse_check = $this->Inventory_model->item_on_warehouse_check($item->id, $warehouse);

        if($item_on_warehouse_check > 0){
            echo json_encode(array("success" => false, 'message' => lang('duplicate_item_error')));
        }
        else{
            $inventory_id = $this->Inventory_model->save($inventory_data);
            if ($inventory_id) {
                $options = array("id" => $inventory_id);
                $inventory_info = $this->Inventory_model->get_details($options)->row();
                echo json_encode(array("success" => true, "id" => $inventory_info->id, "data" => $this->_inventory_make_row($inventory_info), 'message' => lang('record_saved')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
            }
        }
    }

    function list_data($id){
        $list_data = $this->Inventory_model->get_details(array(
            'item_id' => $id
        ))->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_inventory_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    function item_details($id){
        $data = $this->load->view('inventory/item_details', array('id'=> $id));
        echo json_encode(array("data" => $data));
    }
}
