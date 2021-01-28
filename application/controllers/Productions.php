<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Productions extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Productions_model");
        $this->load->model("Bill_of_materials_model");
    }

    protected function _get_bill_of_material_dropdown_data() {
        $bill_of_materials = $this->Bill_of_materials_model->get_all()->result();
        $bill_of_material_dropdown = array('' => '-');

        foreach ($bill_of_materials as $bill_of_material) {
            $bill_of_material_dropdown[$bill_of_material->id] = $bill_of_material->title;
        }
        return $bill_of_material_dropdown;
    }

    protected function _get_statuses_dropdown_data() {
        return array("draft" => "Draft", "ongoing" => "Ongoing", "completed" => "Completed", "cancelled" => "Cancelled");
    }

    function index(){
        $this->template->rander("productions/index");
    }

    function list_data(){
        $list_data = $this->Productions_model->get_details(array(
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
        $status = "";
        if($data->status == 'draft'){
            $status = '<span class="mt0 label label-default large">Draft</span>';
        }
        if($data->status == 'ongoing'){
            $status = '<span class="mt0 label label-warning large">Ongoing</span>';
        }
        if($data->status == 'completed'){
            $status = '<span class="mt0 label label-success large">Completed</span>';
        }
        if($data->status == 'cancelled'){
            $status = '<span class="mt0 label label-danger large">Cancelled</span>';
        }

        return array(
            $data->bill_of_material_title,
            $data->item_name,
            $data->quantity,
            $status,
            $data->created_on,
            get_team_member_profile_link($data->created_by, $data->full_name, array("target" => "_blank")),
            modal_anchor(get_uri("productions/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_production'), "data-post-id" => $data->id))
        );
    }

    function save() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');
        $status = $this->input->post('status');

        $production_data = array(
            "bill_of_material_id" => $this->input->post('bill_of_material_id'),
        );

        if(!$id){
            $production_data["created_on"] = date('Y-m-d H:i:s');
            $production_data["created_by"] = $this->login_user->id;
        }

        if($status){
            $production_data["status"] = $status;
        }

        $production_id = $this->Productions_model->save($production_data, $id);
        if ($production_id) {
            $options = array("id" => $production_id);
            $production_info = $this->Productions_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $production_info->id, "data" => $this->_make_row($production_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Productions_model->get_one($this->input->post('id'));
        $view_data["bill_of_material_dropdown"] = $this->_get_bill_of_material_dropdown_data();
        $view_data["statuses_dropdown"] = $this->_get_statuses_dropdown_data();

        $this->load->view('productions/modal_form', $view_data);
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));
        
        $id = $this->input->post('id');
        
        if ($this->Productions_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }
}
