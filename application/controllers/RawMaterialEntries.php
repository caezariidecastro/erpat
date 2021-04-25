<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class RawMaterialEntries extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Material_entries_model");
        $this->load->model("Material_categories_model");
        $this->load->model("Units_model");
        $this->load->model("Vendors_model");
    }

    protected function _get_category_dropdown_data() {
        $material_categories = $this->Material_categories_model->get_all()->result();
        $category_dropdown = array('' => '-');

        foreach ($material_categories as $group) {
            $category_dropdown[$group->id] = $group->title;
        }
        return $category_dropdown;
    }

    protected function _get_units_dropdown_data() {
        $units = $this->Units_model->get_all()->result();
        $units_dropdown = array('' => '-');

        foreach ($units as $group) {
            $units_dropdown[$group->id] = $group->title;
        }
        return $units_dropdown;
    }

    protected function _get_vendor_dropdown_data($status = null) {
        $vendor = $this->Vendors_model->get_details(array("status" => $status))->result();
        $vendor_dropdown = array('' => '-');

        foreach ($vendor as $group) {
            $vendor_dropdown[$group->id] = $group->name;
        }
        return $vendor_dropdown;
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
        $vendor_select2 = array(array('id' => '', 'text'  => '- '.lang('suppliers').' -'));

        foreach ($vendors as $group) {
            $vendor_select2[] = array('id' => $group->id, 'text' => $group->name) ;
        }
        return $vendor_select2;
    }

    function index(){
        $this->validate_user_module_permission("module_mes");
        $view_data['category_select2'] = $this->_get_category_select2_data();
        $view_data['vendor_select2'] = $this->_get_vendor_select2_data();
        $this->template->rander("raw_materials/index", $view_data);
    }

    function list_data(){
        $list_data = $this->Material_entries_model->get_details(array(
            'category' => $this->input->post('category_select2_filter'),
            'vendor' => $this->input->post('vendor_select2_filter')
        ))->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_row($data) {
        return array(
            $data->name,
            $data->sku,
            $data->unit_abbreviation,
            $data->category_name,
            number_with_decimal($data->cost_price),
            get_supplier_contact_link($data->vendor, $data->vendor_name),
            $data->created_on,
            get_team_member_profile_link($data->created_by, $data->creator_name, array("target" => "_blank")),
            modal_anchor(get_uri("mes/RawMaterialEntries/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_entry'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_entry'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("mes/RawMaterialEntries/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function save() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');

        $material_data = array(
            "name" => $this->input->post('name'),
            "sku" => $this->input->post('sku'),
            "unit" => $this->input->post('unit'),
            "category" => $this->input->post('category'),
            "cost_price" => $this->input->post('cost_price'),
            "vendor" => $this->input->post('vendor'),
        );

        if(!$id){
            $material_data["created_on"] = date('Y-m-d H:i:s');
            $material_data["created_by"] = $this->login_user->id;
        }

        $material_id = $this->Material_entries_model->save($material_data, $id);
        if ($material_id) {
            $options = array("id" => $material_id);
            $material_info = $this->Material_entries_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $material_info->id, "data" => $this->_make_row($material_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');

        $view_data['model_info'] = $this->Material_entries_model->get_one($id);
        $view_data['category_dropdown'] = $this->_get_category_dropdown_data();
        $view_data['units_dropdown'] = $this->_get_units_dropdown_data();
        $view_data['vendor_dropdown'] = $this->_get_vendor_dropdown_data($id ? "" : "active");

        $this->load->view('raw_materials/entries/modal_form', $view_data);
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');

        if ($this->Material_entries_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    function get_material(){
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));
        
        $options = array("id" => $this->input->post('id'));
        $material_info = $this->Material_entries_model->get_details($options)->row();

        echo json_encode(array("data" => $material_info, "success" => true));
    }
}
