<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Inventory_transfers extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Inventory_transfers_model");
        $this->load->model("Warehouse_model");
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
        $this->template->rander("inventory_transfers/index");
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
            modal_anchor(get_uri("inventory_transfers/items"), $item_count, array("title" => lang('items'))),
            nl2br($data->remarks),
            $data->created_on,
            get_team_member_profile_link($data->created_by, $data->creator_name, array("target" => "_blank")),
            modal_anchor(get_uri("inventory_transfers/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_transfer'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("inventory_transfers/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function save() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');

        $vendor_data = array(
            "transferee" => $this->input->post('transferee'),
            "receiver" => $this->input->post('receiver'),
            "dispatcher" => $this->input->post('dispatcher'),
            "driver" => $this->input->post('driver'),
            "remarks" => $this->input->post('remarks'),
        );

        if(!$id){
            $vendor_data["reference_number"] = $this->input->post('reference_number');
            $vendor_data["created_on"] = date('Y-m-d H:i:s');
            $vendor_data["created_by"] = $this->login_user->id;
        }

        if($id){
            // Delete previous items and insert new items
        }

        $vendor_id = $this->Inventory_transfers_model->save($vendor_data, $id);

        if ($vendor_id) {
            $options = array("id" => $vendor_id);
            $vendor_info = $this->Inventory_transfers_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $vendor_info->id, "data" => $this->_make_row($vendor_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Inventory_transfers_model->get_one($this->input->post('id'));
        $view_data['user_dropdown'] = array("" => "-") + $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", array("deleted" => 0, "user_type" => "staff"));
        $view_data['warehouse_dropdown'] = $this->_get_warehouse_dropdown_data();

        $this->load->view('inventory_transfers/modal_form', $view_data);
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
}
