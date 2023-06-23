<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Services extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->access_only_team_members();

        $this->load->library("Uuid");
        $this->load->model("Services_model");
        $this->load->model("Services_categories_model");
    }

    protected function _get_status_select2_data() {
        $status_select2 = array(
            array("id" => "", "text"  => "- Status -"),
            array("id" => "active", "text"  => "Active"),
            array("id" => "inactive", "text"  => "Inactive"),
        );

        return $status_select2;
    }

    protected function make_labels_dropdown($type = "", $label_ids = "", $is_filter = false) {
        if (!$type) {
            show_404();
        }

        $labels_dropdown = $is_filter ? array(array("id" => "", "text" => "- " . lang("label") . " -")) : array();

        $options = array(
            "context" => $type
        );

        if ($label_ids) {
            $add_label_option = true;

            //check if any string is exists, 
            //if so, not include this parameter
            $explode_ids = explode(',', $label_ids);
            foreach ($explode_ids as $label_id) {
                if (!is_int($label_id)) {
                    $add_label_option = false;
                    break;
                }
            }

            if ($add_label_option) {
                $options["label_ids"] = $label_ids; //to edit labels where have access of others
            }
        }

        $labels = $this->Labels_model->get_details($options)->result();
        foreach ($labels as $label) {
            $labels_dropdown[] = array("id" => $label->id, "text" => $label->title);
        }

        return $labels_dropdown;
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
        $Services_categories = $this->Services_categories_model->get_all()->result();
        $category_dropdown = array('' => '-');

        foreach ($Services_categories as $item) {
            if(!empty($item->id)) {
                $category_dropdown[$item->id] = $item->title;
            }
        }
        return $category_dropdown;
    }

    protected function _get_category_select2_data() {
        $Services_categories = $this->Services_categories_model->get_all()->result();
        $category_select2 = array(array('id' => '', 'text'  => '- Categories -'));

        foreach ($Services_categories as $group) {
            $category_select2[] = array('id' => $group->id, 'text' => $group->title) ;
        }
        return $category_select2;
    }

    //load note list view
    function index() {
        $this->validate_access_to_items();

        $view_data['services_labels_dropdown'] = json_encode($this->make_labels_dropdown("services", "", true));
        $view_data['status_select2'] = $this->_get_status_select2_data();
        $view_data['category_select2'] = $this->_get_category_select2_data();
        $this->template->rander("services/index", $view_data);
    }

    /* load item modal */
    function modal_form() {
        $this->validate_access_to_items();

        $view_data['model_info'] = $this->Services_model->get_one($this->input->post('id'));
        $view_data['category_dropdown'] = $this->_get_category_dropdown_data();
        $view_data['label_suggestions'] = $this->make_labels_dropdown("services", $view_data['model_info']->labels);

        $this->load->view('services/modal_form', $view_data);
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
            "category_id" => $this->input->post('category'),
            "unit_type" => $this->input->post('unit_type'),
            "rate" => unformat_currency($this->input->post('item_rate')),
            "labels" => $this->input->post('labels') ? $this->input->post('labels') : "",
            "created_at" => get_current_utc_time()
        );

        if(!$id){
            $item_data["created_by"] = $this->login_user->id;
        } else {
            $item_data["active"] = $this->input->post('active');
        }

        $item_id = $this->Services_model->save($item_data, $id);
        if ($item_id) {
            $options = array("id" => $item_id);
            $item_info = $this->Services_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $item_info->id, "test"=>$item_id, "data" => $this->_make_item_row($item_info), 'message' => lang('record_saved')));
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
            if ($this->Services_model->delete($id, true)) {
                $options = array("id" => $id);
                $item_info = $this->Services_model->get_details($options)->row();
                echo json_encode(array("success" => true, "id" => $item_info->id, "data" => $this->_make_item_row($item_info), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Services_model->delete($id)) {
                $item_info = $this->Services_model->get_one($id);
                echo json_encode(array("success" => true, "id" => $item_info->id, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of items, prepared for datatable  */
    function list_data() {
        $this->validate_access_to_items();

        $list_data = $this->Services_model->get_details(array(
            'category' => $this->input->post('category_select2_filter'),
            'labels' => $this->input->post('labels_select2_filter'),
            'status' => $this->input->post('status_select2_filter')
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
        $status = $data->active == 1 ? "<small class='label label-success'>Active</small>" : "<small class='label label-danger'>Inactive</small>";

        $labels = "";
        if ($data->labels_list) {
            $labels = make_labels_view_data($data->labels_list, true);
        }

        return array(
            $data->title,
            nl2br($data->description),
            $data->category_name ? $data->category_name : "Uncategorized",
            $type,
            $data->rate,
            $labels,
            $status,
            $data->created_at,
            $data->updated_at,
            get_team_member_profile_link($data->created_by, $data->full_name, array("target" => "_blank")),
            modal_anchor(
                get_uri("Sales/Services/modal_form"), 
                "<i class='fa fa-pencil'></i>", 
                array(
                    "class" => "edit", "title" => lang('edit_item'), 
                    "data-post-id" => $data->id,
                )
            )
            . js_anchor("<i class='fa fa-times fa-fw'></i>", 
                array('title' => lang('delete'), 
                "class" => "delete", "data-id" => $data->id, 
                "data-action-url" => get_uri("Sales/Services/delete"), 
                "data-action" => "delete-confirmation"))
        );
    }

}

/* End of file Services.php */
/* Location: ./application/controllers/Services.php */