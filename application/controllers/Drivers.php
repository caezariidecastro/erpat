<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Drivers extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Drivers_model");
        $this->load->model("Email_templates_model");
    }

    function index(){
        $this->template->rander("drivers/index");
    }

    function list_data(){
        $list_data = $this->Drivers_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_row($data) {
        $file_path = base_url(get_setting("profile_image_path").$data->license_image);

        return array(
            get_team_member_profile_link($data->id, $data->full_name, array("target" => "_blank")),
            $data->license_number,
            $data->license_image ? modal_anchor(get_uri("lds/drivers/view_license?file_path=".$file_path), "<img src='".$file_path."' style='width: 100px; height: auto;'/>", array( "title" => lang('license_image'), "data-post-id" => $data->id)) : "",
            $data->total_deliveries,
            $data->created_at,
            $data->created_by ? get_team_member_profile_link($data->created_by, $data->creator_name, array("target" => "_blank")) : "",
            modal_anchor(get_uri("lds/drivers/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_driver'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("lds/drivers/delete"), "data-action" => "delete-confirmation"))
        );
    }

    private function email($first_name, $last_name, $email, $password){
        $email_template = $this->Email_templates_model->get_final_template("login_info");

        $parser_data["SIGNATURE"] = $email_template->signature;
        $parser_data["USER_FIRST_NAME"] = $first_name;
        $parser_data["USER_LAST_NAME"] = $last_name;
        $parser_data["USER_LOGIN_EMAIL"] = $email;
        $parser_data["USER_LOGIN_PASSWORD"] = $password;
        $parser_data["DASHBOARD_URL"] = base_url();
        $parser_data["LOGO_URL"] = get_logo_url();

        $message = $this->parser->parse_string($email_template->message, $parser_data, TRUE);
        send_app_mail($email, $email_template->subject, $message);
    }

    function save() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');
        $first_name = $this->input->post('first_name');
        $last_name = $this->input->post('last_name');
        $email = $this->input->post('email');
        $password = make_random_string(8);
        $delete_file = $this->input->post("delete_file");
        $file_name = "";

        $driver_data = array(
            "first_name" => $first_name,
            "last_name" => $last_name,
            "email" => $email,
            "user_type" => "driver",
            "license_number" => $this->input->post("license_number")
        );

        if(!$id){
            if ($this->Users_model->is_email_exists($email)) {
                echo json_encode(array("success" => false, 'message' => lang('duplicate_email')));
                exit();
            }

            $driver_data["disable_login"] = 1;
            $driver_data["password"] = password_hash($password, PASSWORD_DEFAULT);
            $driver_data["created_at"] = get_current_utc_time();
            $driver_data["created_by"] = $this->login_user->id;

            $this->email($first_name, $last_name, $email, $password);
        }

        $driver_id = $this->Users_model->save($driver_data, $id);

        $job_data = array(
            "user_id" => $driver_id,
            "date_of_hire" => $this->input->post('date_of_hire'),
        );

        if ($_FILES) {
            $config['upload_path'] = get_setting("profile_image_path");
            $config['allowed_types'] = 'gif|jpg|png|jpeg';

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('license_image'))
            {
                $upload_data = $this->upload->data();
                $file_name = $upload_data["file_name"];
                $image_data = array("license_image" => $file_name);
                $this->Users_model->save($image_data, $driver_id);
            }
            else
            {
                echo json_encode(array("success" => false, 'message' => $this->upload->display_errors()));
                exit();
            }
        }

        if($delete_file){
            unlink(get_setting("profile_image_path").$delete_file[0]);
            $image_data = array("license_image" => $file_name);
            $this->Users_model->save($image_data, $driver_id);
        }

        $this->Users_model->save_job_info($job_data);

        if ($driver_id) {
            $options = array("id" => $driver_id);
            $driver_info = $this->Drivers_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $driver_info->id, "data" => $this->_make_row($driver_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));
        $id = $this->input->post('id');

        $view_data['model_info'] = $id ? $this->Drivers_model->get_driver($id)->row() : $this->Drivers_model->get_one();

        $this->load->view('drivers/modal_form', $view_data);
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));
        
        $id = $this->input->post('id');
        
        if ($this->Drivers_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    function view_license(){
        $view_data["file_path"] = $this->input->get("file_path");
        $this->load->view("drivers/view_license", $view_data);
    }
}
