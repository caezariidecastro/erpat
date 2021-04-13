<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Transfers extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Inventory_transfers_model");
        $this->load->model("Inventory_model");
        $this->load->model("Warehouse_model");
        $this->load->model("Inventory_item_entries_model");
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
        $this->validate_user_sub_module_permission("module_lds");
        $this->template->rander("transfers/index");
    }

    function list_data(){
        $list_data = $this->Inventory_transfers_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_row($data) {
        $item_count = "<span class='label label-light w50'>".$data->item_count."</span>";

        return array(
            $data->reference_number,
            $data->transferee_name,
            $data->receiver_name,
            get_team_member_profile_link($data->dispatcher, $data->dispatcher_name, array("target" => "_blank")),
            get_team_member_profile_link($data->driver, $data->driver_name, array("target" => "_blank")),
            $data->vehicle_name,
            $item_count,
            nl2br($data->remarks),
            $data->created_on,
            get_team_member_profile_link($data->created_by, $data->creator_name, array("target" => "_blank")),
            $this->_get_status_label($data->status),
            modal_anchor(get_uri("lds/transfers/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_transfer'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("lds/transfers/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function get_inventory_items_select2_data($warehouse_id = null, $type = "object") {
        $inventory_items = $this->Inventory_model->get_details(array('warehouse_id' => $warehouse_id))->result();

        $inventory_items_select2 = array(array("id" => "", "text" => "-"));

        foreach ($inventory_items as $inventory_item) {
            $inventory_items_select2[] = array("id" => $inventory_item->id, "text" => $inventory_item->name . " (".$inventory_item->warehouse_name.": " . get_current_item_inventory_count($inventory_item) . " ) ", "unit_type" => $inventory_item->unit_abbreviation, "");
        }

        if($type == "json"){
            echo json_encode($inventory_items_select2);
        }
        else{
            return $inventory_items_select2;
        }
    }

    function save() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');
        $transferee = $this->input->post('transferee');
        $receiver = $this->input->post('receiver');
        $inventory_items = $this->input->post('inventory_items');
        $reference_number = $this->input->post('reference_number');

        $transfer_data = array(
            "transferee" => $transferee,
            "receiver" => $receiver,
            "dispatcher" => $this->input->post('dispatcher'),
            "driver" => $this->input->post('driver'),
            "vehicle_id" => $this->input->post('vehicle_id'),
            "remarks" => $this->input->post('remarks'),
        );

        if(!$id){
            $transfer_data["reference_number"] = $this->input->post('reference_number');
            $transfer_data["created_on"] = date('Y-m-d H:i:s');
            $transfer_data["created_by"] = $this->login_user->id;
        }

        if($id){
            // If edit mode, Delete previous inventory_items
            $this->Inventory_transfers_model->delete_transfer_item($reference_number);
            $transfer_data["status"] = $this->input->post("status");
        }


        if($transferee == $receiver){
            echo json_encode(array("success" => false, 'message' => lang('transfer_from_and_to_error')));
        }
        else{
            if(!$inventory_items){
                echo json_encode(array("success" => false, 'message' => lang('item_table_empty_error')));
            }
            else{
                for($i = 0; $i < count($inventory_items); $i++){
                    $inventory_item = json_decode($inventory_items[$i]);
                    $this->validate_items_warehouse($inventory_item->id, $receiver);
                }

                $transfer_id = $this->Inventory_transfers_model->save($transfer_data, $id);
    
                for($i = 0; $i < count($inventory_items); $i++){
                    $inventory_item = json_decode($inventory_items[$i]);
    
                    $data = array(
                        'inventory_id' => $inventory_item->id,
                        'reference_number' => $reference_number,
                        'quantity' => $inventory_item->value
                    );
                    
                    $this->Inventory_transfers_model->insert_transfer_item($data);
                }
    
                if ($transfer_id) {
                    $options = array("id" => $transfer_id);
                    $transfer_info = $this->Inventory_transfers_model->get_details($options)->row();
                    echo json_encode(array("success" => true, "id" => $transfer_info->id, "data" => $this->_make_row($transfer_info), 'message' => lang('record_saved')));
                } else {
                    echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
                }
            }
        }
    }

    private function validate_items_warehouse($item_id, $warehouse_id){
        if(!$this->Inventory_model->item_on_warehouse_check($item_id, $warehouse_id)){
            $item = $this->Inventory_model->get_one($item_id);
            $warehouse = $this->Warehouse_model->get_one($warehouse_id);

            echo json_encode(array("success" => false, 'message' => "Item $item->name doesn't exist on warehouse $warehouse->name"));
            die;
        }
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $model_info = $this->Inventory_transfers_model->get_one($this->input->post('id'));

        $view_data['model_info'] = $model_info;
        $view_data['user_dropdown'] = array("" => "-") + $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", array("deleted" => 0, "user_type" => "staff"));
        $view_data['driver_dropdown'] = array("" => "-") + $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", array("deleted" => 0, "user_type" => "driver"));
        $view_data['warehouse_dropdown'] = $this->_get_warehouse_dropdown_data();
        $view_data['vehicle_dropdown'] = $this->_get_vehicle_dropdown_data();
        $view_data['warehouse_item_select2'] = $model_info->transferee ? $this->get_inventory_items_select2_data($model_info->transferee) : array('id' => '', 'text' => '');
        $view_data["status_dropdown"] = $this->_get_statuses();

        $this->load->view('transfers/products/modal_form', $view_data);
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');

        if ($this->Inventory_transfers_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    function get_transferred_items($reference_number = null){
        if(!$reference_number){
            echo json_encode(array('data' => []));
        }
        else{
            $list_data = $this->Inventory_transfers_model->get_transferred_items($reference_number)->result();
            $result = array();
            foreach ($list_data as $data) {
                $result[] = array(
                    $data->inventory_id,
                    $data->item_name,
                    $data->quantity,
                    $data->unit_abbreviation,
                    '<a href="#" title="Delete" class="delete"><i class="fa fa-times fa-fw"></i></a>'
                );
            }
            echo json_encode(array("data" => $result));
        }
    }

    private function _get_status_label($status){
        $labeled_status = "";

        if($status == "draft"){
            $labeled_status = "<span class='label label-default'>Draft</span>";
        }

        if($status == "ongoing"){
            $labeled_status = "<span class='label label-primary'>Ongoing</span>";
        }

        if($status == "completed"){
            $labeled_status = "<span class='label label-success'>Completed</span>";
        }

        if($status == "cancelled"){
            $labeled_status = "<span class='label label-danger'>Cancelled</span>";
        }

        return $labeled_status;
    }

    private function _get_statuses(){
        return array(
            "draft" => "Draft",
            "ongoing" => "Ongoing",
            "completed" => "Completed",
            "cancelled" => "Cancelled"
        );
    }
}
