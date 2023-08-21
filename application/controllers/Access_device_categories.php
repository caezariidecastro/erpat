<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Access_device_categories extends MY_Controller {

	function __construct() {
       	parent::__construct();
        $this->load->library('encryption');
		$this->load->model("Access_device_categories_model");
        $this->load->helper('utility');
    }

    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Access_device_categories_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    function index(){
        $this->load->view("access/categories/index");
    }

    function list_data(){
        $list_data = $this->Access_device_categories_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_row($data) {

        return array(
            $data->id,
            $data->title,
            nl2br($data->detail),
            $data->status?"Active":"Disabled",
            convert_date_utc_to_local($data->update_at),
            modal_anchor(get_uri("Access_device_categories/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('device_category'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("Access_device_categories/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));
        $id = $this->input->post('id');

        if($id) {
            $view_data['model_info'] = $this->Access_device_categories_model->get_details(array(
                "id" => $id
            ))->row();
        }

        $this->load->view('access/categories/modal_form', $view_data);
    }

    function save() {
        validate_submitted_data(array(
            "title" => "required"
        ));

        $id = $this->input->post('id');
        $title = $this->input->post('title');
        $detail = $this->input->post('detail');

        $data = array(
            "title" => $this->input->post('title'),
            "detail" => $this->input->post('detail'),
        );
        $data = clean_data($data);

        $save_id = $this->Access_device_categories_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));
        
        $id = $this->input->post('id');
        
        if ($this->Access_device_categories_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }
}
