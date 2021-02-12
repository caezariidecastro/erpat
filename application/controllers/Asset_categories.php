<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Asset_categories extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Asset_categories_model");
    }

    protected function _get_category_dropdown_data($id = null) {
        $categories = $this->Asset_categories_model->get_all()->result();
        $category_dropdown = array('' => '-');

        foreach ($categories as $category) {
            $hierarchal_tag = getHierarchalTag($this->Asset_categories_model->get_parenting_count($category->id)->row()->parenting_count, "*");
            
            if($id != $category->id){
                $category_dropdown[$category->id] = "$hierarchal_tag$category->title";
            }
        }
        return $category_dropdown;
    }

    function index(){
        $this->template->rander("asset_categories/index");
    }

    function list_data(){
        $list_data = $this->Asset_categories_model->get_details()->result();
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
            $data->parent_name,
            $data->created_on,
            get_team_member_profile_link($data->created_by, $data->full_name, array("target" => "_blank")),
            modal_anchor(get_uri("asset_categories/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_category'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("asset_categories/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function save() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post("id");
        $parent_id = $this->input->post("parent_id");

        $category_data = array(
            "title" => $this->input->post("title"),
            "description" => $this->input->post("description"),
            "parent_id" => $parent_id ? $parent_id : NULL
        );

        if(!$id){
            $category_data["created_on"] = date('Y-m-d H:i:s');
            $category_data["created_by"] = $this->login_user->id;
        }

        $parenting_count = $parent_id ? $this->Asset_categories_model->get_parenting_count($parent_id)->row()->parenting_count : 0;

        if($parenting_count > 3){
            echo json_encode(array("success" => false, 'message' => lang('error_max_parenting')));
            exit;
        }

        $category_id = $this->Asset_categories_model->save($category_data, $id);

        if ($category_id) {
            $options = array("id" => $category_id);
            $category_info = $this->Asset_categories_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $category_info->id, "data" => $this->_make_row($category_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Asset_categories_model->get_one($this->input->post('id'));
        $view_data["parent_dropdown"] = $this->_get_category_dropdown_data($this->input->post('id'));

        $this->load->view('asset_categories/modal_form', $view_data);
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');

        if ($this->Asset_categories_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }
}
