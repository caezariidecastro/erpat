<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Holidays extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Holidays_model");
    }

    function index(){
        $this->validate_user_sub_module_permission("module_hrs");
        $this->template->rander("holidays/index");
    }

    function list_data(){
        $list_data = $this->Holidays_model->get_details(array(
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
            $data->title,
            nl2br($data->description),
            $data->date_from,
            $data->date_to,
            $data->created_on,
            get_team_member_profile_link($data->created_by, $data->full_name, array("target" => "_blank")),
            modal_anchor(get_uri("hrs/holidays/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_holiday'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("hrs/holidays/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function save() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');

        $holiday_data = array(
            "title" => $this->input->post('title'),
            "description" => $this->input->post('description'),
            "date_from" => $this->input->post('date_from'),
            "date_to" => $this->input->post('date_to'),
        );

        if(!$id){
            $holiday_data["created_on"] = date('Y-m-d H:i:s');
            $holiday_data["created_by"] = $this->login_user->id;
        }

        $holiday_id = $this->Holidays_model->save($holiday_data, $id);
        if ($holiday_id) {
            $options = array("id" => $holiday_id);
            $holiday_info = $this->Holidays_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $holiday_info->id, "data" => $this->_make_row($holiday_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Holidays_model->get_one($this->input->post('id'));

        $this->load->view('holidays/modal_form', $view_data);
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));
        
        $id = $this->input->post('id');
        
        if ($this->Holidays_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }
}
