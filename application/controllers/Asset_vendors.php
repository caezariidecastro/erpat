<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Asset_vendors extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Asset_vendors_model");
    }

    protected function _get_status_select2_data() {
        $status_select2 = array(
            array("id" => "", "text"  => "- All -"),
            array("id" => "1", "text"  => "Active"),
            array("id" => "0", "text"  => "Inactive"),
        );

        return $status_select2;
    }

    function index(){
        $view_data["status_select2"] = $this->_get_status_select2_data();
        $this->template->rander("asset_vendors/index", $view_data);
    }

    function list_data(){
        $list_data = $this->Asset_vendors_model->get_details()->result();
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
            $data->created_on,
            get_team_member_profile_link($data->created_by, $data->full_name, array("target" => "_blank")),
            $data->active ? '<small class="label label-success">Active</small>' : '<small class="label label-danger">Inactive</small>',
            modal_anchor(get_uri("asset_vendors/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_vendor'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("asset_vendors/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function save() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post("id");

        $vendor_data = array(
            "title" => $this->input->post("title"),
            "description" => $this->input->post("description"),
            "active" => !$id ? 1 : $this->input->post("active")
        );

        if(!$id){
            $vendor_data["created_on"] = date('Y-m-d H:i:s');
            $vendor_data["created_by"] = $this->login_user->id;
        }

        $vendor_id = $this->Asset_vendors_model->save($vendor_data, $id);

        if ($vendor_id) {
            $options = array("id" => $vendor_id);
            $vendor_info = $this->Asset_vendors_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $vendor_info->id, "data" => $this->_make_row($vendor_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Asset_vendors_model->get_one($this->input->post('id'));
        $view_data["status_select2"] = $this->_get_status_select2_data();

        $this->load->view('asset_vendors/modal_form', $view_data);
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');

        if ($this->Asset_vendors_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }
}