<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Asset_entries extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Asset_entries_model");
        $this->load->model("Asset_categories_model");
        $this->load->model("Asset_locations_model");
        $this->load->model("Asset_brands_model");
        $this->load->model("Asset_vendors_model");
    }

    protected function _get_location_dropdown_data() {
        $locations = $this->Asset_locations_model->get_all()->result();
        $location_dropdown = array('' => '-');

        foreach ($locations as $location) {
            $hierarchal_tag = getHierarchalTag($this->Asset_locations_model->get_parenting_count($location->id)->row()->parenting_count, "*");
            $location_dropdown[$location->id] = "$hierarchal_tag$location->title";
        }
        return $location_dropdown;
    }

    protected function _get_category_dropdown_data() {
        $categories = $this->Asset_categories_model->get_all()->result();
        $category_dropdown = array('' => '-');

        foreach ($categories as $category) {
            $hierarchal_tag = getHierarchalTag($this->Asset_categories_model->get_parenting_count($category->id)->row()->parenting_count, "*");
            $category_dropdown[$category->id] = "$hierarchal_tag$category->title";
        }
        return $category_dropdown;
    }

    protected function _get_brand_dropdown_data() {
        $brands = $this->Asset_brands_model->get_all()->result();
        $brand_dropdown = array('' => '-');

        foreach ($brands as $brand) {
            $brand_dropdown[$brand->id] = $brand->title;
        }
        return $brand_dropdown;
    }

    protected function _get_vendor_dropdown_data($status = null) {
        $vendors = $this->Asset_vendors_model->get_details(array("status" => $status))->result();
        $vendor_dropdown = array('' => '-');

        foreach ($vendors as $vendor) {
            $vendor_dropdown[$vendor->id] = $vendor->name;
        }
        return $vendor_dropdown;
    }

    protected function _get_type_dropdown_data() {
        return array(
            "" => "-", 
            "own" => "Own", 
            "lease" => "Lease", 
            "rental" => "Rental", 
            "contract" => "Contract", 
            "service" => "Service"
        );
    }

    function index(){
        $this->template->rander("asset_entries/index");
    }

    function list_data(){
        $list_data = $this->Asset_entries_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_row($data) {
        $labels = $data->labels ? make_labels_view_data($data->labels_list, true) : "";

        return array(
            $data->title,
            nl2br($data->description),
            number_with_decimal($data->cost),
            $data->serial_number,
            $data->model,
            $data->brand_name,
            $data->purchase_date,
            $data->warranty_expiry_date,
            ucwords($data->type),
            get_vendor_contact_link($data->vendor_id, $data->vendor_name),
            $data->category_name,
            $data->location_name,
            $data->created_on,
            get_team_member_profile_link($data->created_by, $data->full_name, array("target" => "_blank")),
            $labels,
            modal_anchor(get_uri("asset_entries/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_entry'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("asset_entries/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function save() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post("id");

        $entry_data = array(
            "title" => $this->input->post("title"),
            "description" => $this->input->post("description"),
            "cost" => $this->input->post("cost"),
            "serial_number" => $this->input->post("serial_number"),
            "model" => $this->input->post("model"),
            "brand_id" => $this->input->post("brand_id"),
            "purchase_date" => $this->input->post("purchase_date"),
            "warranty_expiry_date" => $this->input->post("warranty_expiry_date"),
            "type" => $this->input->post("type"),
            "vendor_id" => $this->input->post("vendor_id"),
            "category_id" => $this->input->post("category_id"),
            "location_id" => $this->input->post("location_id"),
            "labels" => $this->input->post("labels"),
        );

        if(!$id){
            $entry_data["created_on"] = date('Y-m-d H:i:s');
            $entry_data["created_by"] = $this->login_user->id;
        }

        $entry_id = $this->Asset_entries_model->save($entry_data, $id);

        if ($entry_id) {
            $options = array("id" => $entry_id);
            $entry_info = $this->Asset_entries_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $entry_info->id, "data" => $this->_make_row($entry_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');

        $view_data['model_info'] = $this->Asset_entries_model->get_one($id);
        $view_data["brand_dropdown"] = $this->_get_brand_dropdown_data();
        $view_data["category_dropdown"] = $this->_get_category_dropdown_data();
        $view_data["location_dropdown"] = $this->_get_location_dropdown_data();
        $view_data["vendor_dropdown"] = $this->_get_vendor_dropdown_data($id ? "" : "active");
        $view_data["type_dropdown"] = $this->_get_type_dropdown_data();
        $view_data['label_suggestions'] = $this->make_labels_dropdown("asset_entry", $view_data['model_info']->labels);

        $this->load->view('asset_entries/modal_form', $view_data);
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');

        if ($this->Asset_entries_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }
}
