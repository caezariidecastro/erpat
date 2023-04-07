<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ProductEntries extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Inventory_item_entries_model");
        $this->load->model("Inventory_item_categories_model");
        $this->load->model("Inventory_item_brands_model");
        $this->load->model("Units_model");
        $this->load->model("Vendors_model");
    }

    protected function _get_category_dropdown_data() {
        $Inventory_item_categories = $this->Inventory_item_categories_model->get_all()->result();
        $category_dropdown = array('' => '-');

        foreach ($Inventory_item_categories as $group) {
            $category_dropdown[$group->id] = $group->title;
        }
        return $category_dropdown;
    }

    protected function _get_brand_dropdown_data() {
        $Inventory_item_brands = $this->Inventory_item_brands_model->get_all()->result();
        $brand_dropdown = array('' => '-');

        foreach ($Inventory_item_brands as $group) {
            $brand_dropdown[$group->id] = $group->name;
        }
        return $brand_dropdown;
    }

    protected function _get_kind_dropdown_data() {
        $kind_dropdown = array('' => '-');

        $kind_dropdown['finished_goods'] = "Finished Goods";
        $kind_dropdown['raw_materials'] = "Raw Materials";
        $kind_dropdown['work_in_process'] = "Work In Process";

        return $kind_dropdown;
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
        $inventory_item_categories = $this->Inventory_item_categories_model->get_all()->result();
        $category_select2 = array(array('id' => '', 'text'  => '- Categories -'));

        foreach ($inventory_item_categories as $group) {
            $category_select2[] = array('id' => $group->id, 'text' => $group->title) ;
        }
        return $category_select2;
    }

    protected function _get_brand_select2_data() {
        $inventory_item_brands = $this->Inventory_item_brands_model->get_all()->result();
        $brands_select2 = array(array('id' => '', 'text'  => '- Brands -'));

        foreach ($inventory_item_brands as $group) {
            $brands_select2[] = array('id' => $group->id, 'text' => $group->title) ;
        }
        return $brands_select2;
    }

    protected function _get_vendor_select2_data($status = null) {
        $vendors = $this->Vendors_model->get_details(array("status" => $status))->result();
        $vendor_select2 = array(array('id' => '', 'text'  => '- '.lang('suppliers').' -'));

        foreach ($vendors as $group) {
            $vendor_select2[] = array('id' => $group->id, 'text' => $group->name) ;
        }
        return $vendor_select2;
    }

    function index(){
        $view_data['category_select2'] = $this->_get_category_select2_data();
        $view_data['brand_select2'] = $this->_get_brand_select2_data();
        $view_data['vendor_select2'] = $this->_get_vendor_select2_data();
        $this->template->rander("products/index", $view_data);
    }

    function list_data(){
        $list_data = $this->Inventory_item_entries_model->get_details(array(
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
        $actions = "";
        $add_bom = "";
        $delete = "";
        $edit = "";
        $add_material = "";

        if($data->bom){
            $delete = '<li role="presentation">' . modal_anchor(get_uri("production/BillOfMaterials/delete_modal_form"), "<i class='fa fa-times fa-fw'></i> ". lang('delete'), array("class" => "delete", "title" => lang('delete'), "data-post-id" => $data->bom)) . '</li>';
            $edit = '<li role="presentation">' . modal_anchor(get_uri("production/BillOfMaterials/modal_form"), "<i class='fa fa-pencil'></i> ". lang('edit_bill_of_material'), array("class" => "edit", "title" => lang('edit_bill_of_material'), "data-post-id" => $data->bom, "data-post-item_id" => $data->id)) . '</li>';
            $add_material = '<li role="presentation">' . modal_anchor(get_uri("production/BillOfMaterials/add_material_modal_form"), "<i class='fa fa-plus-circle'></i> ". lang('add_view_materials'), array("class" => "edit", "title" => lang('add_view_materials'), "data-post-id" => $data->bom)) . '</li>';
    
        }
        else{
            $add_bom = '<li role="presentation">' . modal_anchor(get_uri("production/BillOfMaterials/modal_form"), "<i class='fa fa-plus-circle'></i> ". lang('add_bill_of_material'), array("class" => "edit", "title" => lang('add_bill_of_material'), "data-post-item_id" => $data->id)) . '</li>';
        }
        
        $actions = '<span class="dropdown inline-block" style="position: relative; right: 0; margin-top: 0;">
                        <button class="btn btn-default dropdown-toggle  mt0 mb0" type="button" data-toggle="dropdown" aria-expanded="true">
                            <i class="fa fa-cogs"></i>&nbsp;
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu pull-right" role="menu">' . $add_bom . $add_material . $edit . $delete . '</ul>
                    </span>';

        $image_url =  $data->image?$data->image:get_uri("assets/images/image.jpg"); //get_avatar($user_info->image)
        $product_preview = form_open(get_uri($url . "/save_product_image/" . $data->id), array("id" => $data->id."-product-image-form", "class" => "cropper-form", "role" => "form")). //style="opacity: 35%; font-size: 25px;" //style="opacity: 35%; font-size: 25px;"
        '<div id="'.$data->id.'-holder" class="file-upload btn mt0 p0 product-image-upload">
            <span><i class="btn fa fa-camera"></i></span> 
            <input id="'.$data->id.'-product_image_file" class="upload" name="'.$data->id.'-product_image_file" type="file" data-height="200" data-width="200" data-preview-container="#'.$data->id.'-product-image-preview" data-input-field="#'.$data->id.'-product_image" />
        </div>'.
        '<input type="hidden" class="product_base64" id="'.$data->id.'-product_image" name="'.$data->id.'-product_image" value=""  />'.
        "<span class='avatar avatar-s'><img id='".$data->id."-product-image-preview' src='$image_url' alt='...' style='max-width: 100px; border-radius: 5%;'></span>".
        form_close();

        return array(
            $product_preview,
            $data->name,
            $data->sku,
            $data->unit_abbreviation,
            $data->category_name,
            $data->brand_name,
            number_with_decimal($data->cost_price),
            number_with_decimal($data->selling_price),
            get_supplier_contact_link($data->vendor, $data->vendor_name),
            $data->created_on,
            get_team_member_profile_link($data->created_by, $data->creator_name, array("target" => "_blank")),
            $actions,
            modal_anchor(get_uri("sales/ProductEntries/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_entry'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_entry'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("sales/ProductEntries/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function update_product_image() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));
        $id = $this->input->post('id');

        $image = $this->input->post('image'); //TODO: Process
        $saved_url = save_base_64_image($image, get_setting("product_image_path"));

        //save url to database.
        $item_data["image"] = $saved_url;
        $item_id = $this->Inventory_item_entries_model->save($item_data, $id);
        
        if ($item_id) {
            echo json_encode(array("success" => true, 'message' => lang('record_saved')));
            exit;
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function save() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');

        $item_data = array(
            "name" => $this->input->post('name'),
            "description" => $this->input->post('description'),
            "sku" => $this->input->post('sku'),
            "unit" => $this->input->post('unit'),
            "category" => $this->input->post('category'),
            "brand" => $this->input->post('brand'),
            "kind" => $this->input->post('kinds'),
            "cost_price" => $this->input->post('cost_price'),
            "selling_price" => $this->input->post('selling_price'),
            "vendor" => $this->input->post('vendor'),
        );

        if(!$id){
            $item_data["created_on"] = date('Y-m-d H:i:s');
            $item_data["created_by"] = $this->login_user->id;
        }

        $item_id = $this->Inventory_item_entries_model->save($item_data, $id);
        if ($item_id) {
            $options = array("id" => $item_id);
            $item_info = $this->Inventory_item_entries_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $item_info->id, "data" => $this->_make_row($item_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');

        $view_data['model_info'] = $this->Inventory_item_entries_model->get_one($id);
        $view_data['category_dropdown'] = $this->_get_category_dropdown_data();
        $view_data['brand_dropdown'] = $this->_get_brand_dropdown_data();
        $view_data['kind_dropdown'] = $this->_get_kind_dropdown_data();
        $view_data['units_dropdown'] = $this->_get_units_dropdown_data();
        $view_data['vendor_dropdown'] = $this->_get_vendor_dropdown_data($id ? "" : "active");

        $this->load->view('products/entries/modal_form', $view_data);
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');

        if ($this->Inventory_item_entries_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    function get_item(){
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));
        
        $id = $this->input->post("id");
        $json = $this->input->post("json");
        
        $data = $this->Inventory_item_entries_model->get_details(array("id" => $id))->row();
        if($json){
            echo json_encode(array("success" => true, "data" => $data));
            exit;
        }
        return $data;
    }
}
