<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Asset_vendors extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Asset_vendors_model");
    }

    protected function _get_status_select2_data() {
        $status_select2 = array(
            array("id" => "", "text"  => "- All -"),
            array("id" => "active", "text"  => "Active"),
            array("id" => "inactive", "text"  => "Inactive"),
        );

        return $status_select2;
    }

    function index(){
        $view_data["status_select2"] = $this->_get_status_select2_data();
        $this->template->rander("asset_vendors/index", $view_data);
    }

    function list_data(){
        $list_data = $this->Asset_vendors_model->get_details(array(
            "status" => $this->input->post("status_select2_filter")
        ))->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    function contact_list_data(){
        $list_data = $this->Asset_vendors_model->get_contacts(array(
            "asset_vendor_id" => $this->input->get("asset_vendor_id")
        ))->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_contact_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_row($data) {
        return array(
            '<a href="'.base_url("pid/vendor/".$data->id."/contacts").'">'.$data->name.'</a>',
            nl2br($data->address),
            $data->city,
            $data->state,
            $data->zip,
            $data->country,
            $data->created_on,
            get_team_member_profile_link($data->created_by, $data->full_name, array("target" => "_blank")),
            $data->contacts,
            $data->status == "active" ? '<small class="label label-success">Active</small>' : '<small class="label label-danger">Inactive</small>',
            modal_anchor(get_uri("asset_vendors/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_vendor'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("asset_vendors/delete"), "data-action" => "delete-confirmation"))
        );
    }

    private function _make_contact_row($data) {
        return array(
            get_team_member_profile_link($data->id, $data->full_name, array("target" => "_blank")),
            $data->job_title,
            $data->email,
            $data->phone,
            $data->created_at,
            get_team_member_profile_link($data->created_by, $data->creator_name, array("target" => "_blank")),
            modal_anchor(get_uri("asset_vendors/add_contact_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_contact'), "data-post-id" => $data->id, "data-post-asset_vendor_id" => $data->asset_vendor_id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("asset_vendors/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function save() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');

        $asset_vendor_data = array(
            "name" => $this->input->post('name'),
            "address" => $this->input->post('address'),
            "city" => $this->input->post('city'),
            "state" => $this->input->post('state'),
            "zip" => $this->input->post('zip'),
            "country" => $this->input->post('country'),
            "status" => $this->input->post("status"),
        );

        if(!$id){
            $asset_vendor_data["created_on"] = date('Y-m-d H:i:s');
            $asset_vendor_data["created_by"] = $this->login_user->id;
        }

        $asset_vendor_id = $this->Asset_vendors_model->save($asset_vendor_data, $id);

        if ($asset_vendor_id) {
            $options = array("id" => $asset_vendor_id);
            $asset_vendor_info = $this->Asset_vendors_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $asset_vendor_info->id, "data" => $this->_make_row($asset_vendor_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Asset_vendors_model->get_one($this->input->post('id'));
        $view_data["status_select2"] = $this->_get_status_select2_data();

        $this->load->view('asset_vendors/modal_form', $view_data);
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');

        if ($this->Asset_vendors_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    function contacts($id){
        $view_data['name'] = $this->Asset_vendors_model->get_one($id)->name;
        $view_data["asset_vendor_id"] = $id;
        $this->template->rander("asset_vendors/contact_list", $view_data);
    }

    function add_contact_modal_form(){
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Users_model->get_one($this->input->post('id'));
        $view_data["asset_vendor_id"] = $this->input->post("asset_vendor_id");

        $this->load->view('asset_vendors/add_contact_modal_form', $view_data);
    }

    function save_contact(){
        validate_submitted_data(array(
            "first_name" => "required",
            "last_name" => "required",
            "asset_vendor_id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        $email = $this->input->post('email');
        $asset_vendor_id = $this->input->post('asset_vendor_id');
        $is_primary_contact = $this->input->post('is_primary_contact');

        $user_data = array(
            "first_name" => $this->input->post('first_name'),
            "last_name" => $this->input->post('last_name'),
            "phone" => $this->input->post('phone'),
            "job_title" => $this->input->post('job_title'),
            "gender" => $this->input->post('gender'),
            "email" => $email,
            "asset_vendor_id" => $asset_vendor_id,
            "user_type" => "vendor",
            "is_primary_contact" => $is_primary_contact ? $is_primary_contact : "0"
        );

        if (!$id) {
            if ($this->Users_model->is_email_exists($email)) {
                echo json_encode(array("success" => false, 'message' => lang('duplicate_email')));
                exit();
            }

            $password = password_hash($this->input->post("password"), PASSWORD_DEFAULT);

            $user_data["disable_login"] = "1";
            $user_data["password"] = $password;
            $user_data["created_at"] = date('Y-m-d H:i:s');
            $user_data["created_by"] = $this->login_user->id;

            if ($this->input->post('email_login_details')) {
                $email_template = $this->Email_templates_model->get_final_template("login_info");

                $parser_data["SIGNATURE"] = $email_template->signature;
                $parser_data["USER_FIRST_NAME"] = $user_data["first_name"];
                $parser_data["USER_LAST_NAME"] = $user_data["last_name"];
                $parser_data["USER_LOGIN_EMAIL"] = $email;
                $parser_data["USER_LOGIN_PASSWORD"] = $password;
                $parser_data["DASHBOARD_URL"] = base_url();
                $parser_data["LOGO_URL"] = get_logo_url();

                $message = $this->parser->parse_string($email_template->message, $parser_data, TRUE);
                // send_app_mail($email, $email_template->subject, $message);
            }
        }

        if($is_primary_contact){
            $primary_contact = $this->Asset_vendors_model->get_contacts(array(
                "asset_vendor_id" => $asset_vendor_id,
                "is_primary_contact" => 1
            ))->row();

            $primary_contact_data["is_primary_contact"] = "0";

            if($primary_contact){
                $this->Users_model->save($primary_contact_data, $primary_contact->id);
            }
        }

        $user_id = $this->Users_model->save($user_data, $id);

        if ($user_id) {
            $user_info = $this->Asset_vendors_model->get_contacts(array(
                "asset_vendor_id" => $asset_vendor_id
            ))->row();
            echo json_encode(array("success" => true, "id" => $user_info->id, "data" => $this->_make_contact_row($user_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }
}
