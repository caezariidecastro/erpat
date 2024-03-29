<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pages extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->with_module("page", "redirect");
        $this->with_permission("page", "redirect");

        $this->load->model("Pages_model");
    }

    function index() {
        $view_data['page_create'] = $this->with_permission("page_create");
        $this->template->rander("pages/index", $view_data);
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');
        
        if( isset($id) ) {
            $this->with_permission("page_edit", "no_permission");
        } else {
            $this->with_permission("page_create", "no_permission");
        }

        $view_data['model_info'] = $this->Pages_model->get_one($id);

        $this->load->view('pages/modal_form', $view_data);
    }

    function save() {

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');
        $slug = $this->input->post('slug');

        if ($this->Pages_model->is_slug_exists($slug, $id)) {
            echo json_encode(array("success" => false, 'message' => lang("page_url_cant_duplicate")));
            return false;
        }

        $data = array(
            "title" => $this->input->post('title'),
            "content" => decode_ajax_post_data($this->input->post('content')),
            "slug" => $slug,
            "status" => $this->input->post('status'),
            "internal_use_only" => is_null($this->input->post('internal_use_only')) ? "" : $this->input->post('internal_use_only'),
            "visible_to_team_members_only" => is_null($this->input->post('visible_to_team_members_only')) ? "" : $this->input->post('visible_to_team_members_only'),
            "visible_to_clients_only" => is_null($this->input->post('visible_to_clients_only')) ? "" : $this->input->post('visible_to_clients_only')
        );
    
        if( isset($id) ) {
            $this->with_permission("page_update", "no_permission");
        } else {
            $this->with_permission("page_create", "no_permission");
        }

        $save_id = $this->Pages_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function delete() {

        validate_submitted_data(array(
            "id" => "numeric"
        ));
        $this->with_permission("page_delete", "no_permission");

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Pages_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Pages_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    function list_data() {
        $list_data = $this->Pages_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Pages_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    private function _make_row($data) {
        $options = "";
        if($this->with_permission("page_update")) {
            $options = modal_anchor(get_uri("pages/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('add_page'), "data-post-id" => $data->id));
        }

        if($this->with_permission("page_delete")) {
            $options .= js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_page'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("pages/delete"), "data-action" => "delete"));
        }

        return array($data->title,
            anchor(get_uri("about") . "/" . $data->slug, get_uri("about") . "/" . $data->slug, array("target" => "_blank")),
            lang($data->status),
            $options
        );
    }

}

/* End of file Pages.php */
/* Location: ./application/controllers/Pages.php */