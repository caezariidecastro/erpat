<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Stores extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->access_only_team_members();

        $this->load->library("Uuid");
        $this->load->model("Stores_model");
        $this->load->model("Stores_categories_model");
    }

    protected function _get_category_dropdown_data() {
        $Stores_categories = $this->Stores_categories_model->get_all()->result();
        $category_dropdown = array('' => '-');

        foreach ($Stores_categories as $item) {
            if(!empty($item->uuid)) {
                $category_dropdown[$item->uuid] = $item->name;
            }
        }
        return $category_dropdown;
    }

    protected function _get_category_select2_data() {
        $Stores_categories = $this->Stores_categories_model->get_all()->result();
        $category_select2 = array(array('id' => '', 'text'  => '- Categories -'));

        foreach ($Stores_categories as $group) {
            $category_select2[] = array('id' => $group->uuid, 'text' => $group->name) ;
        }
        return $category_select2;
    }

    //load note list view
    function index() {
        $this->validate_user_module_permission("module_sms");

        $view_data['category_select2'] = $this->_get_category_select2_data();
        $this->template->rander("stores/index", $view_data);
    }

    /* load item modal */
    function modal_form() {

        $view_data['model_info'] = $this->Stores_model->get_one($this->input->post('uuid'), true);
        $view_data['category_dropdown'] = $this->_get_category_dropdown_data();

        $this->load->view('stores/modal_form', $view_data);
    }

    /* add or edit an item */
    function save() {

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');

        $item_data = array(
            "name" => $this->input->post('title'),
            "description" => $this->input->post('description'),
            "category_id" => $this->input->post('category'),
            "timestamp" => get_current_utc_time()
        );

        if(!$id){
            $item_data["uuid"] = $this->uuid->v4();
            $item_data["created_by"] = $this->login_user->id;
        } else {
            $item_data["status"] = $this->input->post('active');
        }

        $item_id = $this->Stores_model->save($item_data, $id);
        if ($item_id) {
            $options = array("id" => $item_id);
            $item_info = $this->Stores_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $item_info->id, "test"=>$item_id, "data" => $this->_make_item_row($item_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    /* delete or undo an item */
    function delete() {

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Stores_model->delete($id, true)) {
                $options = array("id" => $id);
                $item_info = $this->Stores_model->get_details($options)->row();
                echo json_encode(array("success" => true, "id" => $item_info->id, "data" => $this->_make_item_row($item_info), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Stores_model->delete($id)) {
                $item_info = $this->Stores_model->get_one($id);
                echo json_encode(array("success" => true, "id" => $item_info->id, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of items, prepared for datatable  */
    function list_data() {

        $list_data = $this->Stores_model->get_details(array(
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
        $status = $data->status == 1 ? "<small class='label label-success'>Active</small>" : "<small class='label label-danger'>Inactive</small>";

        $image_url =  $data->image?$data->image:get_uri("assets/images/image.jpg"); //get_avatar($user_info->image)
        $store_preview = form_open(get_uri($url . "/save_store_image/" . $data->id), array("id" => $data->id."-store-image-form", "class" => "cropper-form", "role" => "form")). //style="opacity: 35%; font-size: 25px;" //style="opacity: 35%; font-size: 25px;"
        '<div id="'.$data->id.'-holder" class="file-upload btn mt0 p0 store-image-upload">
            <span><i class="btn fa fa-camera"></i></span> 
            <input id="'.$data->id.'-store_image_file" class="upload" name="'.$data->id.'-store_image_file" type="file" data-height="200" data-width="200" data-preview-container="#'.$data->id.'-store-image-preview" data-input-field="#'.$data->id.'-store_image" />
        </div>'.
        '<input type="hidden" class="store_base64" id="'.$data->id.'-store_image" name="'.$data->id.'-store_image" value=""  />'.
        "<span class='avatar avatar-s'><img id='".$data->id."-store-image-preview' src='$image_url' alt='...' style='max-width: 100px; border-radius: 5%;'></span>".
        form_close();

        return array(
            $data->uuid,
            $store_preview,
            $data->name,
            nl2br($data->description),
            $data->category_name ? $data->category_name : "Uncategorized",
            $status,
            $data->timestamp,
            $data->updated_at,
            get_team_member_profile_link($data->created_by, $data->full_name, array("target" => "_blank")),
            modal_anchor(
                get_uri("sales/Stores/modal_form"), 
                "<i class='fa fa-pencil'></i>", 
                array(
                    "class" => "edit", "title" => lang('edit_item'), 
                    "data-post-id" => $data->id,
                    "data-post-uuid" => $data->uuid
                )
            )
            . js_anchor("<i class='fa fa-times fa-fw'></i>", 
                array('title' => lang('delete'), 
                "class" => "delete", "data-id" => $data->id, 
                "data-action-url" => get_uri("sales/Stores/delete"), 
                "data-action" => "delete-confirmation"))
        );
    }

    function update_store_image() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));
        $id = $this->input->post('id');

        $image = $this->input->post('image'); //TODO: Process
        $saved_url = save_base_64_image($image, get_setting("store_image_path"));

        //save url to database.
        $item_data["image"] = $saved_url;
        $item_id = $this->Stores_model->save($item_data, $id);
        
        if ($item_id) {
            echo json_encode(array("success" => true, 'message' => lang('record_saved')));
            exit;
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }
}

/* End of file Stores.php */
/* Location: ./application/controllers/Stores.php */