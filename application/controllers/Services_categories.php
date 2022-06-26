<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Services_categories extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library("Uuid");
        $this->load->model("Services_categories_model");
    }

    function index(){
        $this->template->rander("services/categories/index");
    }

    function list_data(){
        $list_data = $this->Services_categories_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_row($data) {
        $status = $data->active == 1 ? "<small class='label label-success'>Active</small>" : "<small class='label label-danger'>Inactive</small>";
        return array(
            $data->uuid,
            $data->title,
            nl2br($data->description),
            $status,
            $data->created_at,
            $data->updated_at,
            get_team_member_profile_link($data->created_by, $data->full_name, array("target" => "_blank")),
            modal_anchor(get_uri("services_categories/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit user-status-confirm", "title" => lang('edit_category'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete user-status-confirm", "data-id" => $data->id, "data-action-url" => get_uri("services_categories/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function save() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');

        $data = array(
            "title" => $this->input->post('title'),
            "description" => $this->input->post('description'),
            "created_at" => get_current_utc_time()
        );

        if(!$id){
            $data["uuid"] = $this->uuid->v4();
            $data["created_by"] = $this->login_user->id;
        } else {
            $data["active"] = $this->input->post('active');
        }

        $id = $this->Services_categories_model->save($data, $id);
        if ($id) {
            $options = array("id" => $id);
            $contribution_info = $this->Services_categories_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $contribution_info->id, "data" => $this->_make_row($contribution_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Services_categories_model->get_one($this->input->post('id'));

        $this->load->view('services/categories/modal_form', $view_data);
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');

        if ($this->Services_categories_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }
}

/* End of file Services_categories.php */
/* Location: ./application/controllers/Services_categories.php */