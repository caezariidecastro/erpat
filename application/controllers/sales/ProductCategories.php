<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ProductCategories extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Inventory_item_categories_model");
        $this->load->model("Vendors_model");
    }

    function index(){
        $this->template->rander("products/entries/index");
    }

    function list_data(){
        $list_data = $this->Inventory_item_categories_model->get_details()->result();
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
            modal_anchor(get_uri("sales/ProductCategories/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_category'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("sales/ProductCategories/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function save() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');

        $contribution_data = array(
            "title" => $this->input->post('title'),
            "description" => $this->input->post('description'),
        );

        if(!$id){
            $contribution_data["created_on"] = get_current_utc_time();
            $contribution_data["created_by"] = $this->login_user->id;
        }

        $contribution_id = $this->Inventory_item_categories_model->save($contribution_data, $id);
        if ($contribution_id) {
            $options = array("id" => $contribution_id);
            $contribution_info = $this->Inventory_item_categories_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $contribution_info->id, "data" => $this->_make_row($contribution_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Inventory_item_categories_model->get_one($this->input->post('id'));

        $this->load->view('products/categories/modal_form', $view_data);
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');

        if ($this->Inventory_item_categories_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }
}
