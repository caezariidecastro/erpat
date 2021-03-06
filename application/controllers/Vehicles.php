<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Vehicles extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Vehicles_model");
    }

    function index(){
        $this->template->rander("vehicles/index");
    }

    function list_data(){
        $list_data = $this->Vehicles_model->get_details(array(
            'start' => $this->input->post('start_date'),
            'end' => $this->input->post('end_date'),
        ))->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_row($data) {
        $file_path = base_url(get_setting("profile_image_path").$data->image);

        return array(
            $data->brand,
            $data->model,
            $data->year,
            $data->color,
            $data->transmission,
            $data->no_of_wheels,
            $data->plate_number,
            $data->max_cargo_weight,
            $data->image ? modal_anchor(get_uri("vehicles/view_image?file_path=".$file_path), "<img src='".$file_path."' style='width: 100px; height: auto;'/>", array( "title" => lang('image'), "data-post-id" => $data->id)) : "",
            $data->created_on,
            get_team_member_profile_link($data->created_by, $data->full_name, array("target" => "_blank")),
            modal_anchor(get_uri("vehicles/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_vehicle'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("vehicles/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function save() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');
        $delete_file = $this->input->post('delete_file');

        $vehicle_data = array(
            "brand" => $this->input->post('brand'),
            "model" => $this->input->post('model'),
            "year" => $this->input->post('year'),
            "color" => $this->input->post('color'),
            "transmission" => $this->input->post('transmission'),
            "no_of_wheels" => $this->input->post('no_of_wheels'),
            "plate_number" => $this->input->post('plate_number'),
            "max_cargo_weight" => $this->input->post('max_cargo_weight'),
        );

        if(!$id){
            $vehicle_data["created_on"] = date('Y-m-d H:i:s');
            $vehicle_data["created_by"] = $this->login_user->id;
        }

        if ($_FILES) {
            $config['upload_path'] = get_setting("profile_image_path");
            $config['allowed_types'] = 'gif|jpg|png|jpeg';

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('image'))
            {
                $upload_data = $this->upload->data();
                $file_name = $upload_data["file_name"];
                $image_data = array("image" => $file_name);
                $this->Vehicles_model->save($image_data, $id);
            }
            else
            {
                echo json_encode(array("success" => false, 'message' => $this->upload->display_errors()));
                exit();
            }
        }

        if($delete_file){
            unlink(get_setting("profile_image_path").$delete_file[0]);
            $image_data = array("image" => $file_name);
            $this->Vehicles_model->save($image_data, $id);
        }

        $vehicle_id = $this->Vehicles_model->save($vehicle_data, $id);
        if ($vehicle_id) {
            $options = array("id" => $vehicle_id);
            $vehicle_info = $this->Vehicles_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $vehicle_info->id, "data" => $this->_make_row($vehicle_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Vehicles_model->get_one($this->input->post('id'));

        $this->load->view('vehicles/modal_form', $view_data);
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));
        
        $id = $this->input->post('id');
        
        if ($this->Vehicles_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    function view_image(){
        $view_data["file_path"] = $this->input->get("file_path");
        $this->load->view("vehicles/view_image", $view_data);
    }
}
