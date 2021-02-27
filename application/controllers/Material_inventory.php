<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Material_inventory extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Material_inventory_model");
        $this->load->model("Material_entries_model");
        $this->load->model("Warehouse_model");
        $this->load->model("Material_inventory_override_model");
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
        $this->template->rander("material_inventory/index");
    }

    function material_list_data(){
        $list_data = $this->Material_entries_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_material_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }
    
    function no_data(){
        echo json_encode(array("data" => []));
    }

    private function _material_make_row($data) {
        $row = '<div class="pull-left material-row" data-id="'.$data->id.'">
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
                                    Stocks on hand: '.($data->stocks + $data->stocks_override - $data->production_quantity + $data->purchased).'
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';

        return array(
            $row
        );
    }

    function add_material_inventory_modal_form() {
        $view_data['warehouse_dropdown'] = $this->_get_warehouse_dropdown_data();

        $this->load->view('material_inventory/add_material_inventory', $view_data);
    }

    function add_stock_modal_form($warehouse_id, $material_inventory_id) {
        $view_data['warehouse_id'] = $warehouse_id;
        $view_data['material_inventory_id'] = $material_inventory_id;

        $this->load->view('material_inventory/add_stock', $view_data);
    }


    private function _material_inventory_make_row($data) {
        $detail = '<div class="pull-left material-row" data-id="'.$data->id.'">
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
                                    Available stocks: '.($data->stock + $data->stock_override - $data->production_quantity + $data->purchased).'
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';

        $delete = '<li role="presentation">' . js_anchor("<i class='fa fa-times fa-fw'></i>" . lang('delete'), array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("material_inventory/delete"), "data-action" => "delete-confirmation", "data-reload-on-success" => "1")) . '</li>';
        $add = '<li role="presentation">' . modal_anchor(get_uri("material_inventory/add_stock_modal_form/$data->warehouse/$data->id"), "<i class='fa fa-plus-circle'></i> " . lang('add_stock'), array( "title" => lang('add_stock'), "id" => "add_stock_button")) . '</li>';

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

    function add_material_inventory(){
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $material = $this->Material_entries_model->get_details(array(
            'id' => $this->input->post('material')
        ))->row();
        $warehouse = $this->input->post('warehouse');

        $material_inventory_data = array(
            "warehouse" => $warehouse,
            "stock" => $this->input->post('opening_stock'),
            "material_id" => $material->id,
            "name" => $material->name,
            "sku" => $material->sku,
            "unit" => $material->unit,
            "category" => $material->category,
            "cost_price" => $material->cost_price,
            "vendor" => $material->vendor,
            "created_on" => date('Y-m-d H:i:s'),
            "created_by" => $this->login_user->id
        );

        $material_on_warehouse_check = $this->Material_inventory_model->material_on_warehouse_check($material->id, $warehouse);

        if($material_on_warehouse_check > 0){
            echo json_encode(array("success" => false, 'message' => lang('duplicate_material_error')));
        }
        else{
            $material_inventory_id = $this->Material_inventory_model->save($material_inventory_data);
            if ($material_inventory_id) {
                $options = array("id" => $material_inventory_id);
                $material_inventory_info = $this->Material_inventory_model->get_details($options)->row();
                echo json_encode(array("success" => true, "id" => $material_inventory_info->id, "data" => $this->_material_inventory_make_row($material_inventory_info), 'message' => lang('record_saved')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
            }
        }
    }

    function add_stock(){
        validate_submitted_data(array(
            "material_inventory_id" => "numeric|required",
            "warehouse_id" => "numeric|required",
            "quantity" => "required",
        ));

        $material_inventory_id = $this->input->post('material_inventory_id');
        $warehouse_id = $this->input->post('warehouse_id');

        $material_inventory_override_data = array(
            "warehouse" => $warehouse_id,
            "stock" => $this->input->post('quantity'),
            "material_inventory_id" => $material_inventory_id,
            "created_on" => date('Y-m-d H:i:s'),
            "created_by" => $this->login_user->id
        );

        $override_id = $this->Material_inventory_override_model->save($material_inventory_override_data);
        if ($override_id) {
            $options = array("id" => $material_inventory_id);
            $material_inventory_info = $this->Material_inventory_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $material_inventory_info->id, "data" => $this->_material_inventory_make_row($material_inventory_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function list_data($id){
        $list_data = $this->Material_inventory_model->get_details(array(
            'material_id' => $id
        ))->result();

        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_material_inventory_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    function material_details($id){
        $data = $this->load->view('material_inventory/material_details', array('id'=> $id));
        echo json_encode(array("data" => $data));
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');

        $options = array("id" => $id);
        $inventory_info = $this->Material_inventory_model->get_details($options)->row();

        if($inventory_info->production_quantity){
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
        else{
            if ($this->Material_inventory_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    function get_material_inventory(){
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        $options = array("id" => $id);
        $material_inventory_info = $this->Material_inventory_model->get_details($options)->row();

        if($material_inventory_info){
            echo json_encode(array("success" => true, 'material_inventory_info' => $material_inventory_info));
        }
        else{
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }
}
