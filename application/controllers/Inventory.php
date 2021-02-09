<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Inventory extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Inventory_model");
        $this->load->model("Inventory_item_entries_model");
        $this->load->model("Warehouse_model");
        $this->load->model("Inventory_override_model");
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
                        <div class="row">
                            <div class="col-md-7">
                                <div class="media-heading">
                                    <strong>'.$data->name.'</strong>
                                    <br>'.$data->sku.'
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="text-off pull-right text-right">
                                    Stocks on hand: '.($data->stocks + $data->produced + $data->stocks_override - $data->delivered - $data->invoiced).'
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';

        return array(
            $row
        );
    }

    function add_inventory_modal_form() {
        $view_data['warehouse_dropdown'] = $this->_get_warehouse_dropdown_data();

        $this->load->view('inventory/add_inventory', $view_data);
    }

    function add_stock_modal_form($warehouse_id, $inventory_id) {
        $view_data['warehouse_id'] = $warehouse_id;
        $view_data['inventory_id'] = $inventory_id;

        $this->load->view('inventory/add_stock', $view_data);
    }


    private function _inventory_make_row($data) {
        $detail = '<div class="pull-left item-row" data-id="'.$data->id.'">
                    <div class="media-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="media-heading">
                                    <strong>'.$data->warehouse_name.'</strong>
                                </div>
                                '.$data->warehouse_address.'
                            </div>
                            <div class="col-md-4">
                                <div class="text-off pull-right text-right">
                                    Available stocks: '.($data->stock + $data->stock_override - $data->transferred + $data->received - $data->delivered - $data->invoiced).'
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';

        $delete = '<li role="presentation">' . js_anchor("<i class='fa fa-times fa-fw'></i>" . lang('delete'), array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("inventory/delete"), "data-action" => "delete-confirmation", "data-reload-on-success" => "1")) . '</li>';
        $add = '<li role="presentation">' . modal_anchor(get_uri("inventory/add_stock_modal_form/$data->warehouse/$data->id"), "<i class='fa fa-plus-circle'></i> " . lang('add_stock'), array( "title" => lang('add_stock'), "id" => "add_stock_button")) . '</li>';

        $actions = '<span class="dropdown inline-block" style="position: relative; right: 0; margin-top: 0;">
                        <button class="btn btn-default dropdown-toggle  mt0 mb0" type="button" data-toggle="dropdown" aria-expanded="true">
                            <i class="fa fa-cogs"></i>&nbsp;
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu pull-right" role="menu">' . $add . $delete . '</ul>
                    </span>';

        return array(
            $detail,
            $actions
        );
    }

    function add_inventory(){
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

    function add_stock(){
        validate_submitted_data(array(
            "inventory_id" => "numeric|required",
            "warehouse_id" => "numeric|required",
            "quantity" => "required",
        ));

        $inventory_id = $this->input->post('inventory_id');
        $warehouse_id = $this->input->post('warehouse_id');

        $inventory_override_data = array(
            "warehouse" => $warehouse_id,
            "stock" => $this->input->post('quantity'),
            "inventory_id" => $inventory_id,
            "created_on" => date('Y-m-d H:i:s'),
            "created_by" => $this->login_user->id
        );

        $override_id = $this->Inventory_override_model->save($inventory_override_data);
        if ($override_id) {
            $options = array("id" => $inventory_id);
            $inventory_info = $this->Inventory_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $inventory_info->id, "data" => $this->_inventory_make_row($inventory_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
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

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');

        $options = array("id" => $id);
        $inventory_info = $this->Inventory_model->get_details($options)->row();

        if($inventory_info->transferred || $inventory_info->received || $inventory_info->delivered || $inventory_info->produced || $inventory_info->invoiced){
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
        else{
            if ($this->Inventory_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }

    }

    function get_inventory(){
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        $options = array("id" => $id);
        $inventory_info = $this->Inventory_model->get_details($options)->row();

        if($inventory_info){
            echo json_encode(array("success" => true, 'inventory_info' => $inventory_info));
        }
        else{
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function get_warehouse_inventory_select2_data() {
        $inventories = $this->Inventory_model->get_details(array('warehouse_id' => $this->input->post('id')))->result();
        $inventory_select2 = array(array('id' => '', 'text' => '-'));

        foreach ($inventories as $inventory) {
            $inventory_select2[] = array("id" => $inventory->id, "text" => $inventory->item_name) ;
        }
        echo json_encode($inventory_select2);
    }
}
