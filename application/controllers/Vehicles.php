<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Vehicles extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Vehicles_model");
    }

    function index(){
        $this->validate_user_module_permission("module_lds");
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
        $images = "";
        if ($data->files) {
            $files = unserialize($data->files);
            if (count($files)) {
                foreach ($files as $key => $value) {


                    $file_name = get_array_value($value, "file_name");
                    $file_path = base_url() . get_setting("timeline_file_path") . $file_name;

                    $images .= modal_anchor(
                        get_uri("lds/vehicles/view_image?file_path=". $file_path), 
                        '<i class="pull-left font-22 mr10  fa fa-file-image-o"></i>',
                        array( "title" => remove_file_prefix($file_name), "data-post-id" => $data->id)
                    );
                }
            }
        }
        
        return array(
            $data->brand,
            $data->model,
            $data->year,
            $data->color,
            $data->transmission,
            $data->no_of_wheels,
            $data->plate_number,
            $data->max_cargo_weight,
            $images,
            $data->created_on,
            get_team_member_profile_link($data->created_by, $data->full_name, array("target" => "_blank")),
            modal_anchor(get_uri("lds/vehicles/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_vehicle'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("lds/vehicles/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function save() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');
        $target_path = get_setting("timeline_file_path");
        $files_data = move_files_from_temp_dir_to_permanent_dir($target_path, "vehicle");
        $new_files = unserialize($files_data);

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

        if ($id) {
            $vehicle_info = $this->Vehicles_model->get_one($id);
            $timeline_file_path = get_setting("timeline_file_path");

            $new_files = update_saved_files($timeline_file_path, $vehicle_info->files, $new_files);
        }

        $vehicle_data["files"] = serialize($new_files);

        $vehicle_data = clean_data($vehicle_data);

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

    function upload_file() {
        upload_file_to_temp();
    }

    function validate_vehicles_file() {
        return validate_post_file($this->input->post("file_name"));
    }
}
