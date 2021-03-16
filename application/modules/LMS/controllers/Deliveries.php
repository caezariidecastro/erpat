<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Deliveries extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Deliveries_model");
        $this->load->model("Inventory_model");
        $this->load->model("Vehicles_model");
        $this->load->model("Consumers_model");
        $this->load->model("Invoices_model");
    }

    protected function _get_vehicle_dropdown_data() {
        $Vehicles = $this->Vehicles_model->get_all()->result();
        $vehicle_dropdown = array('' => '-');

        foreach ($Vehicles as $group) {
            $vehicle_dropdown[$group->id] = "$group->brand $group->model $group->year $group->color";
        }
        return $vehicle_dropdown;
    }

    protected function _get_consumer_dropdown_data() {
        $Consumers = $this->Consumers_model->get_all()->result();
        $consumer_dropdown = array('' => '-');

        foreach ($Consumers as $consumer) {
            $consumer_dropdown[$consumer->id] = "$consumer->first_name $consumer->last_name";
        }
        return $consumer_dropdown;
    }

    function get_users_select2_data(){
        $users = $this->Users_model->get_all_where(array("deleted" => 0, "user_type" => "staff"))->result();
        $users_select2 = array();

        foreach ($users as $user) {
            $users_select2[] = array("id" => $user->id, "text" => $user->first_name . " " . $user->last_name);
        }

        return $users_select2;
    }

    function index(){
        $this->validate_user_sub_module_permission("module_lms");
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

    private function _make_row($data) {
        $address = (trim($data->street) ? trim($data->street) . ", " : "") 
            . (trim($data->city)  ? trim($data->city)  . ", " : "") 
            . (trim($data->state)  ? trim($data->state)  . ", " : "") 
            . trim($data->country) ." ". trim($data->zip);

        $invoice_url = "";
        if ($this->login_user->user_type == "staff") {
            $invoice_url = anchor(get_uri("sms/invoices/view/" . $data->invoice_id), get_invoice_id($data->invoice_id));
        } else {
            $invoice_url = anchor(get_uri("invoices/preview/" . $data->invoice_id), get_invoice_id($data->invoice_id));
        }

        return array(
            $data->reference_number,
            $invoice_url,
            get_team_member_profile_link($data->consumer, $data->consumer_name, array("target" => "_blank")),
            get_team_member_profile_link($data->dispatcher, $data->dispatcher_name, array("target" => "_blank")),
            get_team_member_profile_link($data->driver, $data->driver_name, array("target" => "_blank")),
            $data->vehicle_name,
            nl2br($data->remarks),
            $address,
            $data->created_on,
            get_team_member_profile_link($data->created_by, $data->creator_name, array("target" => "_blank")),
            $this->_get_status_label($data->status),
            modal_anchor(get_uri("lms/deliveries/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_delivery'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("lms/deliveries/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function save() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');
        $consumer = $this->input->post('consumer');
        $invoice_id = $this->input->post('invoice_id');

        // ** Save invoice first
        $invoice_data = array(
            "bill_date" => date('Y-m-d'),
            "due_date" => date("Y-m-d", strtotime("+1 week")),
            "consumer_id" => $consumer,
        );

        $invoice_save_id = $this->Invoices_model->save($invoice_data, $invoice_id);
        // **

        $delivery_data = array(
            "invoice_id" => $invoice_save_id,
            "consumer" => $consumer,
            "dispatcher" => $this->input->post('dispatcher'),
            "driver" => $this->input->post('driver'),
            "vehicle" => $this->input->post('vehicle'),
            "remarks" => $this->input->post('remarks'),
            "street" => $this->input->post('street'),
            "city" => $this->input->post('city'),
            "state" => $this->input->post('state'),
            "zip" => $this->input->post('zip'),
            "country" => $this->input->post('country'),
            "passengers" => $this->input->post('passengers'),
        );

        if($id){
            $delivery_data["status"] = $this->input->post("status");
        }

        if(!$id){
            $delivery_data["reference_number"] = $this->input->post('reference_number');
            $delivery_data["created_on"] = date('Y-m-d H:i:s');
            $delivery_data["created_by"] = $this->login_user->id;
        }

        $delivery_id = $this->Deliveries_model->save($delivery_data, $id);

        if ($delivery_id) {
            $options = array("id" => $delivery_id);
            $delivery_info = $this->Deliveries_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $delivery_info->id, "data" => $this->_make_row($delivery_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $model_info = $this->Deliveries_model->get_one($this->input->post('id'));

        $view_data['users_select2'] = json_encode($this->get_users_select2_data());

        $view_data['model_info'] = $model_info;
        $view_data['user_dropdown'] = array("" => "-") + $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", array("deleted" => 0, "user_type" => "staff"));
        $view_data['driver_dropdown'] = array("" => "-") + $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", array("deleted" => 0, "user_type" => "driver"));
        $view_data['consumer_dropdown'] = array("" => "-") + $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", array("deleted" => 0, "user_type" => "customer"));
        $view_data['vehicle_dropdown'] = $this->_get_vehicle_dropdown_data();
        $view_data["status_dropdown"] = $this->_get_statuses();

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
