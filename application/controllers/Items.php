<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Items extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->access_only_team_members();

        $this->load->model("Item_categories_model");
    }

    protected function validate_access_to_items() {
        $access_invoice = $this->get_access_info("invoice");
        $access_estimate = $this->get_access_info("estimate");

        //don't show the items if invoice/estimate module is not enabled
        if(!(get_setting("module_invoice") == "1" || get_setting("module_estimate") == "1" )){
            redirect("forbidden");
        }
        
        if ($this->login_user->is_admin) {
            return true;
        } else if ($access_invoice->access_type === "all" || $access_estimate->access_type === "all") {
            return true;
        } else {
            redirect("forbidden");
        }
    }

    protected function _get_category_dropdown_data() {
        $Item_categories = $this->Item_categories_model->get_all()->result();
        $category_dropdown = array('' => '-');

        foreach ($Item_categories as $group) {
            $category_dropdown[$group->id] = $group->title;
        }
        return $category_dropdown;
    }

    //load note list view
    function index() {
        $this->validate_access_to_items();

        $this->template->rander("items/index");
    }

    /* load item modal */

    function modal_form() {
        $this->validate_access_to_items();

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Items_model->get_one($this->input->post('id'));
        $view_data['category_dropdown'] = $this->_get_category_dropdown_data();

        $this->load->view('items/modal_form', $view_data);
    }

    /* add or edit an item */

    function save() {
        $this->validate_access_to_items();

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');

        $item_data = array(
            "title" => $this->input->post('title'),
            "description" => $this->input->post('description'),
            "category" => $this->input->post('category'),
            "unit_type" => $this->input->post('unit_type'),
            "rate" => unformat_currency($this->input->post('item_rate'))
        );

        if($id){
            $item_data["active"] = $this->input->post('active');
        }

        if(!$id){
            $item_data["created_on"] = date('Y-m-d H:i:s');
            $item_data["created_by"] = $this->login_user->id;
        }

        $item_id = $this->Items_model->save($item_data, $id);
        if ($item_id) {
            $options = array("id" => $item_id);
            $item_info = $this->Items_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $item_info->id, "data" => $this->_make_item_row($item_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    /* delete or undo an item */

    function delete() {
        $this->validate_access_to_items();

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Items_model->delete($id, true)) {
                $options = array("id" => $id);
                $item_info = $this->Items_model->get_details($options)->row();
                echo json_encode(array("success" => true, "id" => $item_info->id, "data" => $this->_make_item_row($item_info), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Items_model->delete($id)) {
                $item_info = $this->Items_model->get_one($id);
                echo json_encode(array("success" => true, "id" => $item_info->id, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of items, prepared for datatable  */

    function list_data() {
        $this->validate_access_to_items();

        $list_data = $this->Items_model->get_details(array(
            'category' => $this->input->post('category_select2_filter')
        ))->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_item_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    /* prepare a row of item list table */

    private function _make_item_row($data) {
        $type = $data->unit_type ? $data->unit_type : "";
        $active_inactive = $data->active == 1 ? "<small class='label label-success'>Active</small>" : "<small class='label label-danger'>Inactive</small>";

        return array(
            $data->title,
            nl2br($data->description),
            $data->category_name,
            $type,
            $data->rate,
            $data->created_on,
            get_team_member_profile_link($data->created_by, $data->full_name, array("target" => "_blank")),
            $active_inactive,
            modal_anchor(get_uri("items/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_item'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("items/delete"), "data-action" => "delete-confirmation"))
        );
    }

}

/* End of file items.php */
/* Location: ./application/controllers/items.php */