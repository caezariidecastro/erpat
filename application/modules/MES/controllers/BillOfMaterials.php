<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class BillOfMaterials extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Bill_of_materials_model");
        $this->load->model("Bill_of_materials_materials_model");
        $this->load->model("Material_inventory_model");
        $this->load->model("Inventory_item_entries_model");
    }

    protected function _get_material_dropdown_data() {
        $materials = $this->Material_inventory_model->get_details()->result();
        $material_dropdown = array('' => '-');

        foreach ($materials as $material) {
            $material_dropdown[$material->id] = $material->material_name . " (".$material->warehouse_name.")";
        }
        return $material_dropdown;
    }

    protected function _get_product_dropdown_data() {
        $products = $this->Inventory_item_entries_model->get_details()->result();
        $product_dropdown = array('' => '-');

        foreach ($products as $product) {
            $product_dropdown[$product->id] = $product->name;
        }
        return $product_dropdown;
    }

    function index(){
        $this->validate_user_sub_module_permission("module_mes");
        $this->template->rander("bill_of_materials/index");
    }

    function list_data(){
        $list_data = $this->Bill_of_materials_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_row($data) {
        $delete = '<li role="presentation">' . js_anchor("<i class='fa fa-times fa-fw'></i>" . lang('delete'), array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("mes/BillOfMaterials/delete"), "data-action" => "delete-confirmation")) . '</li>';
        $edit = '<li role="presentation">' . modal_anchor(get_uri("mes/BillOfMaterials/modal_form"), "<i class='fa fa-pencil'></i> ". lang('edit_bill_of_material'), array("class" => "edit", "title" => lang('edit_bill_of_material'), "data-post-id" => $data->id)) . '</li>';
        $add = '<li role="presentation">' . modal_anchor(get_uri("mes/BillOfMaterials/add_material_modal_form"), "<i class='fa fa-plus-circle'></i> ". lang('add_view_material'), array("class" => "edit", "title" => lang('add_view_material'), "data-post-id" => $data->id)) . '</li>';

        $actions = '<span class="dropdown inline-block" style="position: relative; right: 0; margin-top: 0;">
                        <button class="btn btn-default dropdown-toggle  mt0 mb0" type="button" data-toggle="dropdown" aria-expanded="true">
                            <i class="fa fa-cogs"></i>&nbsp;
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu pull-right" role="menu">' . $add. $edit . $delete . '</ul>
                    </span>';

        return array(
            $data->title,
            nl2br($data->description),
            $data->item_name,
            $data->quantity,
            $data->created_on,
            get_team_member_profile_link($data->created_by, $data->full_name, array("target" => "_blank")),
            $actions
        );
    }

    function save() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');
        $item_id = $this->input->post('item_id');

        $bill_of_material_data = array(
            "title" => $this->input->post('title'),
            "description" => $this->input->post('description'),
            "item_id" => $item_id,
            "quantity" => $this->input->post('quantity'),
        );

        if(!$id){
            $bill_of_material_data["created_on"] = date('Y-m-d H:i:s');
            $bill_of_material_data["created_by"] = $this->login_user->id;

            if($this->Bill_of_materials_model->has_existing_bill_of_material($item_id)){
                echo json_encode(array("success" => false, 'message' => lang('has_existing_bill_of_material_error')));
                exit;
            }
        }

        $bill_of_material_id = $this->Bill_of_materials_model->save($bill_of_material_data, $id);
        if ($bill_of_material_id) {
            $options = array("id" => $bill_of_material_id);
            $bill_of_material_info = $this->Bill_of_materials_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $bill_of_material_info->id, "data" => $this->_make_row($bill_of_material_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');
        $options = array("id" => $id);
        $model_info = $id ? $this->Bill_of_materials_model->get_details($options)->row() : $this->Bill_of_materials_model->get_one($id);

        $view_data['model_info'] = $model_info;
        $view_data["product_dropdown"] = $this->_get_product_dropdown_data();
        $view_data["item_id"] = $this->input->post("item_id");
        $view_data["unit_abbreviation"] = $model_info->item_id ? $this->Inventory_item_entries_model->get_details(array("id" => $model_info->item_id))->row()->unit_abbreviation : "";

        $this->load->view('bill_of_materials/modal_form', $view_data);
    }

    function delete_modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data["id"] = $this->input->post("id");

        $this->load->view('bill_of_materials/delete_modal_form', $view_data);
    }

    function add_material_modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Bill_of_materials_model->get_one($this->input->post('id'));
        $view_data["material_dropdown"] = $this->_get_material_dropdown_data();

        $this->load->view('bill_of_materials/add_material_modal_form', $view_data);
    }

    private function _material_make_row($data){
        $material_name = "<div class='item-row strong mb5'>$data->material_name</div><span>" . $data->warehouse_name . "</span>";

        return array(
            $material_name,
            $data->quantity,
            $data->unit_abbreviation,
            js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("mes/BillOfMaterials/delete_material"), "data-action" => "delete-confirmation"))
        );
    }

    function material_list_data(){
        $options = array('bill_of_material_id' => $this->input->get('id'));
        $list_data = $this->Bill_of_materials_model->get_materials($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_material_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));
        
        $id = $this->input->post('id');
        
        if ($this->Bill_of_materials_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    function delete_material() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));
        
        $id = $this->input->post('id');
        
        if ($this->Bill_of_materials_materials_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    function add_material() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));
        
        $material_inventory_id = $this->input->post('material_id');
        $bill_of_material_id = $this->input->post('id');

        $material_inventory_info = $this->Material_inventory_model->get_one($material_inventory_id);

        $bill_of_materials_materials_data = array(
            "material_inventory_id" => $material_inventory_id,
            "bill_of_material_id" => $bill_of_material_id,
            "quantity" => $this->input->post('quantity'),
            "created_on" => date('Y-m-d H:i:s'),
            "created_by" =>  $this->login_user->id,
        );

        if($this->Bill_of_materials_materials_model->is_bill_of_material_has_material($bill_of_material_id, $material_inventory_info->material_id)){
            echo json_encode(array("success" => false, 'message' => lang('material_already_added_error')));
        }
        else{
            $bill_of_materials_materials_id = $this->Bill_of_materials_materials_model->save($bill_of_materials_materials_data);
            if ($bill_of_materials_materials_id) {
                $options = array("id" => $bill_of_materials_materials_id);
                $bill_of_materials_materials_info = $this->Bill_of_materials_materials_model->get_details($options)->row();
                echo json_encode(array("success" => true, "id" => $bill_of_materials_materials_info->id, "data" => $this->_material_make_row($bill_of_materials_materials_info), 'message' => lang('record_saved')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
            }
        }

    }
}
