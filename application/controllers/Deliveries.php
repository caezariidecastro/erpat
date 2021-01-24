<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Deliveries extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Deliveries_model");
        $this->load->model("Inventory_model");
        $this->load->model("Warehouse_model");
        $this->load->model("Vehicles_model");
    }

    protected function _get_warehouse_dropdown_data() {
        $Warehouses = $this->Warehouse_model->get_all()->result();
        $warehouse_dropdown = array('' => '-');

        foreach ($Warehouses as $group) {
            $warehouse_dropdown[$group->id] = $group->name;
        }
        return $warehouse_dropdown;
    }

    protected function _get_vehicle_dropdown_data() {
        $Vehicles = $this->Vehicles_model->get_all()->result();
        $vehicle_dropdown = array('' => '-');

        foreach ($Vehicles as $group) {
            $vehicle_dropdown[$group->id] = "$group->brand $group->model $group->year $group->color";
        }
        return $vehicle_dropdown;
    }

    function index(){
        $this->template->rander("deliveries/index");
    }

    function list_data(){
        $list_data = $this->Deliveries_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_row($data) {

        return array(
            $data->reference_number,
            $data->warehouse_name,
            $data->consumer_name,
            get_team_member_profile_link($data->dispatcher, $data->dispatcher_name, array("target" => "_blank")),
            get_team_member_profile_link($data->driver, $data->driver_name, array("target" => "_blank")),
            $data->vehicle_name,
            nl2br($data->remarks),
            $data->created_on,
            get_team_member_profile_link($data->created_by, $data->creator_name, array("target" => "_blank")),
            modal_anchor(get_uri("deliveries/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_delivery'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("deliveries/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function get_inventory_items_select2_data($warehouse = 0, $type = "object") {
        if($type == 'json'){
            $items = $this->Inventory_model->get_dropdown_list(array("name"), "id", array("warehouse" => $warehouse));
            $item_list = array(array("id" => "", "text" => "-"));
            foreach ($items as $key => $value) {
                $item_list[] = array("id" => $key, "text" => $value);
            }
            echo json_encode($item_list);
        }
        else{
            $items = $this->Inventory_model->get_dropdown_list(array("name"), "id", array("warehouse" => $warehouse));
            $item_list = array(array("id" => "", "text" => "-"));
            foreach ($items as $key => $value) {
                $item_list[] = array("id" => $key, "text" => $value);
            }
            return $item_list;
        }
    }

    function save() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');
        $warehouse = $this->input->post('warehouse');

        $vendor_data = array(
            "warehouse" => $warehouse,
            "consumer" => $this->input->post('consumer'),
            "dispatcher" => $this->input->post('dispatcher'),
            "driver" => $this->input->post('driver'),
            "vehicle" => $this->input->post('vehicle'),
            "remarks" => $this->input->post('remarks'),
        );

        if(!$id){
            $vendor_data["reference_number"] = $this->input->post('reference_number');
            $vendor_data["created_on"] = date('Y-m-d H:i:s');
            $vendor_data["created_by"] = $this->login_user->id;
        }

        $vendor_id = $this->Deliveries_model->save($vendor_data, $id);

        if ($vendor_id) {
            $options = array("id" => $vendor_id);
            $vendor_info = $this->Deliveries_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $vendor_info->id, "data" => $this->_make_row($vendor_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $model_info = $this->Deliveries_model->get_one($this->input->post('id'));

        $view_data['model_info'] = $model_info;
        $view_data['user_dropdown'] = array("" => "-") + $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", array("deleted" => 0, "user_type" => "staff"));
        $view_data['consumer_dropdown'] = array("" => "-") + $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", array("deleted" => 0, "user_type" => "consumer"));
        $view_data['warehouse_dropdown'] = $this->_get_warehouse_dropdown_data();
        $view_data['vehicle_dropdown'] = $this->_get_vehicle_dropdown_data();
        $view_data['warehouse_item_select2'] = $model_info->warehouse ? $this->get_inventory_items_select2_data($model_info->warehouse) : array('id' => '', 'text' => '');

        $this->load->view('deliveries/modal_form', $view_data);
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');

        if ($this->Deliveries_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    function get_delivered_items($reference_number = null){
        if(!$reference_number){
            echo json_encode(array('data' => []));
        }
        else{
            $list_data = $this->Deliveries_model->get_delivered_items($reference_number)->result();
            $result = array();
            foreach ($list_data as $data) {
                $result[] = array(
                    $data->inventory_id,
                    $data->item_name,
                    $data->quantity,
                    '<a href="#" title="Delete" class="delete"><i class="fa fa-times fa-fw"></i></a>'
                );
            }
            echo json_encode(array("data" => $result));
        }
    }
}
