<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Material_categories extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Material_categories_model");
        $this->load->model("Vendors_model");
    }

    protected function _get_category_select2_data() {
        $material_categories = $this->Material_categories_model->get_all()->result();
        $category_select2 = array(array('id' => '', 'text'  => '- Categories -'));

        foreach ($material_categories as $group) {
            $category_select2[] = array('id' => $group->id, 'text' => $group->title) ;
        }
        return $category_select2;
    }

    protected function _get_vendor_select2_data() {
        $vendors = $this->Vendors_model->get_all()->result();
        $vendor_select2 = array(array('id' => '', 'text'  => '- Vendors -'));

        foreach ($vendors as $group) {
            $vendor_select2[] = array('id' => $group->id, 'text' => $group->name) ;
        }
        return $vendor_select2;
    }

    function index(){
        $view_data['category_select2'] = $this->_get_category_select2_data();
        $view_data['vendor_select2'] = $this->_get_vendor_select2_data();
        $this->template->rander("material_categories/index", $view_data);
    }

    function list_data(){
        $list_data = $this->Material_categories_model->get_details()->result();
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
            modal_anchor(get_uri("material_categories/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_category'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_material'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("material_categories/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function save() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');

        $material_data = array(
            "title" => $this->input->post('title'),
            "description" => $this->input->post('description'),
        );

        if(!$id){
            $material_data["created_on"] = date('Y-m-d H:i:s');
            $material_data["created_by"] = $this->login_user->id;
        }

        $material_id = $this->Material_categories_model->save($material_data, $id);
        if ($material_id) {
            $options = array("id" => $material_id);
            $material_info = $this->Material_categories_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $material_info->id, "data" => $this->_make_row($material_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Material_categories_model->get_one($this->input->post('id'));

        $this->load->view('material_categories/modal_form', $view_data);
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');

        if ($this->Material_categories_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }
}
