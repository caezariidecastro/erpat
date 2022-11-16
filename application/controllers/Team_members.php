<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Team_members extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->access_only_team_members();
        $this->load->library('Phpqr');
        $this->load->model('Schedule_model');
        $this->load->model('Users_model');
        $this->load->model("Social_links_model");
        $this->load->model("Email_templates_model");
        $this->load->model("Verification_model");
        $this->load->model("General_files_model");
    }

    protected function _get_team_select2_data() {
        $teams = $this->Team_model->get_details()->result();
        $team_select2 = array(array('id' => '', 'text'  => '- Departments -'));

        foreach($teams as $team){
            $team_select2[] = array('id' => $team->id, 'text'  => $team->title);
        }

        return $team_select2;
    }

    private function can_view_team_members_contact_info() {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin) {
                return true;
            } else if (get_array_value($this->login_user->permissions, "can_view_team_members_contact_info") == "1") {
                return true;
            }
        }
    }

    private function can_view_team_members_social_links() {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin) {
                return true;
            } else if (get_array_value($this->login_user->permissions, "can_view_team_members_social_links") == "1") {
                return true;
            }
        }
    }

    private function update_only_allowed_members($user_id) {
        if ($this->can_update_team_members_info($user_id)) {
            return true; //own profile
        } else {
            redirect("forbidden");
        }
    }

    //only admin can change other user's info
    //none admin users can only change his/her own info
    //allowed members can update other members info    
    private function can_update_team_members_info($user_id) {
        $access_info = $this->get_access_info("team_member_update_permission");

        if ($this->login_user->id === $user_id) {
            return true; //own profile
        } else if ($access_info->access_type == "all") {
            return true; //has access to change all user's profile
        } else if ($user_id && in_array($user_id, $access_info->allowed_members)) {
            return true; //has permission to update this user's profile
        } else {

            return false;
        }
    }

    //only admin can change other user's info
    //none admin users can only change his/her own info
    private function only_admin_or_own_or_permission($user_id) {
        if ($user_id && ($this->login_user->id === $user_id || $this->validate_user_role_permission('team_member_update_permission_all', true))) {
            return true;
        } else {
            redirect("forbidden");
        }
    }

    function get_all_usertypes($with_delete = true) {
        
        if($with_delete) {
            $usertypes_dropdown = array(array("id" => "active", "text" => lang("active") ));
            $usertypes_dropdown[] = array("id" => "inactive", "text" => lang("inactive") );
            $usertypes_dropdown[] = array("id" => "applicant", "text" => "Applicant");
            $usertypes_dropdown[] = array("id" => "resigned", "text" => "Resigned");
            $usertypes_dropdown[] = array("id" => "terminated", "text" => "Terminated");
            $usertypes_dropdown[] = array("id" => "deleted", "text" => lang("deleted") );
        } else {
            $usertypes_dropdown = array(
                "employee" => "Employee",
                "applicant" => "Applicant",
                "resigned" => "Resigned",
                "terminated" => "Terminated"
            );
        }

        return $usertypes_dropdown;
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

    public function index() {
        if (!$this->can_view_team_members_list()) {
            redirect("forbidden");
        }

        $this->validate_user_module_permission("module_hrs");
        $this->validate_user_role_permission("hrs_employee_view");

        $view_data["show_contact_info"] = $this->can_view_team_members_contact_info();

        $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("team_members", $this->login_user->is_admin, $this->login_user->user_type);

        $view_data["usertype_dropdown"] = json_encode( $this->get_all_usertypes() );
        $view_data['users_labels_dropdown'] = json_encode($this->make_labels_dropdown("users", "", true));
        $view_data['department_select2'] = $this->_get_team_select2_data();
        $view_data['schedule_select2'] = $this->_get_schedule_select2_data();

        $this->template->rander("team_members/index", $view_data);
    }

    /* open new member modal */

    function modal_form() {
        $this->validate_user_role_permission("hrs_employee_add");

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['role_dropdown'] = $this->_get_roles_dropdown();

        $id = $this->input->post('id');
        $options = array(
            "id" => $id,
        );

        $view_data['model_info'] = $this->Users_model->get_details($options)->row();

        $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("team_members", 0, $this->login_user->is_admin, $this->login_user->user_type)->result();

        $this->load->view('team_members/modal_form', $view_data);
    }

    /* save new member */

    function add_team_member() {
        if(!$this->login_user->is_admin || (!$this->login_user->is_admin && !get_array_value($this->login_user->permissions, "team_member_update_permission")) ) {
			redirect("forbidden");
		}

        //check duplicate email address, if found then show an error message
        if ($this->Users_model->is_email_exists($this->input->post('email'))) {
            echo json_encode(array("success" => false, 'message' => lang('duplicate_email')));
            exit();
        }

        $password = $this->input->post("password");

        validate_submitted_data(array(
            "email" => "required|valid_email",
            "first_name" => "required",
            "last_name" => "required",
            "password" => "required",
            "role" => "required"
        ));

        $user_data = array(
            "uuid" => $this->uuid->v4(),
            "email" => $this->input->post('email'),
            "password" => $password ? password_hash($password, PASSWORD_DEFAULT) : "",
            "first_name" => $this->input->post('first_name'),
            "last_name" => $this->input->post('last_name'),
            "is_admin" => $this->input->post('is_admin'),
            "street" => $this->input->post('street') ? $this->input->post('street') : "",
            "city" => $this->input->post('city') ? $this->input->post('city') : "",
            "state" => $this->input->post('state') ? $this->input->post('state') : "",
            "country" => $this->input->post('country') ? $this->input->post('country') : "",
            "zip" => $this->input->post('zip') ? $this->input->post('zip') : "",
            "phone" => $this->input->post('phone'),
            "gender" => $this->input->post('gender'),
            "job_title" => $this->input->post('job_title'),
            "phone" => $this->input->post('phone'),
            "gender" => $this->input->post('gender'),
            "user_type" => "staff",
            "created_at" => get_current_utc_time()
        );

        //make role id or admin permission 
        $role = $this->input->post('role');
        $role_id = $role;

        if ($role === "admin") {
            $user_data["is_admin"] = 1;
            $user_data["role_id"] = 0;
        } else {
            $user_data["is_admin"] = 0;
            $user_data["role_id"] = $role_id;
        }


        //add a new team member
        $user_id = $this->Users_model->save($user_data);
        if ($user_id) {
            //user added, now add the job info for the user
            $job_data = array(
                "user_id" => $user_id,
                "salary" => $this->input->post('salary') ? $this->input->post('salary') : 0,
                "salary_term" => $this->input->post('salary_term'),
                "rate_per_hour" => $this->input->post('rate_per_hour'),
                "date_of_hire" => $this->input->post('date_of_hire'),
                "sss" => $this->input->post('sss'),
                "tin" => $this->input->post('tin'),
                "pag_ibig" => $this->input->post('pag_ibig'),
                "phil_health" => $this->input->post('phil_health'),
            );
            $this->Users_model->save_job_info($job_data);


            save_custom_fields("team_members", $user_id, $this->login_user->is_admin, $this->login_user->user_type);

            //send login details to user
            if ($this->input->post('email_login_details')) {

                //get the login details template
                $email_template = $this->Email_templates_model->get_final_template("login_info");

                $parser_data["SIGNATURE"] = $email_template->signature;
                $parser_data["USER_FIRST_NAME"] = $user_data["first_name"];
                $parser_data["USER_LAST_NAME"] = $user_data["last_name"];
                $parser_data["USER_LOGIN_EMAIL"] = $user_data["email"];
                $parser_data["USER_LOGIN_PASSWORD"] = $this->input->post('password');
                $parser_data["DASHBOARD_URL"] = base_url();
                $parser_data["LOGO_URL"] = get_logo_url();

                $message = $this->parser->parse_string($email_template->message, $parser_data, TRUE);
                send_app_mail($this->input->post('email'), $email_template->subject, $message);
            }
        }

        if ($user_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($user_id), 'id' => $user_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    /* edit employee */

    function edit_modal_form() {
        $this->validate_user_role_permission("hrs_employee_edit");

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['schedule_dropdown'] = $this->_get_schedule_dropdown();

        $view_data['role_dropdown'] = $this->_get_roles_dropdown();
        
        $view_data['status_dropdown'] = $this->get_all_usertypes(false);

        $view_data['label_suggestions'] = $this->make_labels_dropdown("users", $view_data['model_info']->labels);

        $id = $this->input->post('id');
        $options = array(
            "id" => $id,
        );

        $view_data['model_info'] = $this->Users_model->get_details($options)->row();
        if ($view_data['model_info']->is_admin) {
            $view_data['model_info']->role_id = "admin";
        }

        $cuser = $view_data['model_info'];
        if($cuser->user_type == "staff" && $cuser->resigned == "0" && $cuser->terminated == "0") {
            $view_data['model_info']->user_status = "employee";
        } else if($cuser->user_type == "staff" && $cuser->terminated == "1" ) {
            $view_data['model_info']->user_status = "terminated";
        } else if($cuser->user_type == "staff" && $cuser->resigned == "1" ) {
            $view_data['model_info']->user_status = "resigned";
        } else if($cuser->user_type == "applicant" ) {
            $view_data['model_info']->user_status = "applicant";
        }  

        $this->load->view('team_members/edit_modal_form', $view_data);
    }

    /* set rfid */
    function rfid_modal() {
        $this->validate_user_role_permission("hrs_employee_edit");

        validate_submitted_data(array(
            "id" => "numeric"
        ));
        
        $id = $this->input->post('id');
        $options = array(
            "id" => $id,
        );

        $view_data['model_info'] = $this->Users_model->get_details($options)->row();
        $this->load->view('team_members/rfid_modal', $view_data);
    }

    function update_rfid_num() {
        if( !$this->login_user->is_admin && !get_array_value($this->login_user->permissions, "team_member_update_permission") ) {
			redirect("forbidden");
		}

        validate_submitted_data(array(
            "user_id" => "required|numeric"
        ));

        $job_data = array(
            "user_id" => $this->input->post('user_id'),
            "rfid_num" => $this->input->post('rfid_num'),
        );

        if ($this->Users_model->save_job_info($job_data)) {
            echo json_encode(array("success" => true, 'message' => lang('record_updated')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('rfid_already_in_use')));
        }
    }

    function update_user_form($user_id) {
        $this->validate_user_role_permission("hrs_employee_edit");

        validate_submitted_data(array(
            "first_name" => "required",
            "last_name" => "required",
            "job_title" => "required",
            "role" => "required",
            "login" => "required",
            "status" => "required",
        ));

        $user_data = array(
            "first_name" => $this->input->post('first_name'),
            "last_name" => $this->input->post('last_name'),
            "job_title" => $this->input->post('job_title'),
            "disable_login" => $this->input->post('login'),
            "status" => $this->input->post('status'),
            "labels" => $this->input->post('labels') ? $this->input->post('labels') : "",
        );
        if( $this->input->post('role') == "admin") {
            $user_data["is_admin"] = "1";
            $user_data["role_id"] = "0";
        } else {
            $user_data["is_admin"] = "0";
            $user_data["role_id"] = $this->input->post('role');
        }

        if($access_erpat = $this->input->post('erpat')) {
            $user_data["access_erpat"] = $access_erpat;
        }
        if($access_syntry = $this->input->post('syntry')) {
            $user_data["access_syntry"] = $access_syntry;
        }
        if($access_galyon = $this->input->post('galyon')) {
            $user_data["access_galyon"] = $access_galyon;
        }

        $status_dropdown = $this->input->post('status_dropdown');
        if($status_dropdown == "employee") {
            $user_data["user_type"] = "staff";
            $user_data["resigned"] = "0";
            $user_data["terminated"] = "0";
        } else if($status_dropdown == "applicant" ) {
            $user_data["user_type"] = "applicant";
            $user_data["resigned"] = "0";
            $user_data["terminated"] = "0";
        } else if($status_dropdown == "terminated" ) {
            $user_data["status"] = "inactive";
            $user_data["resigned"] = "0";
            $user_data["terminated"] = "1";
        } else if($status_dropdown == "resigned" ) {
            $user_data["status"] = "inactive";
            $user_data["resigned"] = "1";
            $user_data["terminated"] = "0";
        }

        $user_data = clean_data($user_data);

        $user_info_updated = $this->Users_model->save($user_data, $user_id);

        //save_custom_fields("team_members", $user_id, $this->login_user->is_admin, $this->login_user->user_type);
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("team_members", $this->login_user->is_admin, $this->login_user->user_type);
        $data = $this->Users_model->get_details(array("id" => $user_id, "custom_fields" => $custom_fields))->row();
        $data = $this->_make_row($data, $custom_fields);

        if ($user_info_updated) {
            $job_sched_data = array(
                "user_id" => $user_id,
                "sched_id" => $this->input->post('schedule')
            );
            $this->Users_model->save_job_info($job_sched_data);

            echo json_encode(array("success" => true, "id" => $user_id, "data" => $data, 'message' => lang('record_updated')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    /* open invitation modal */

    function invitation_modal() {
        $this->validate_user_role_permission("hrs_employee_invite");

        $this->load->view('team_members/invitation_modal');
    }

    //send a team member invitation to an email address
    function send_invitation() {
        $this->validate_user_role_permission("hrs_employee_invite");

        validate_submitted_data(array(
            "email[]" => "required|valid_email"
        ));

        $email_array = $this->input->post('email');
        $email_array = array_unique($email_array);

        //get the send invitation template 
        $email_template = $this->Email_templates_model->get_final_template("team_member_invitation");

        $parser_data["INVITATION_SENT_BY"] = $this->login_user->first_name . " " . $this->login_user->last_name;
        $parser_data["SIGNATURE"] = $email_template->signature;
        $parser_data["SITE_URL"] = get_uri();
        $parser_data["LOGO_URL"] = get_logo_url();

        $send_email = array();

        foreach ($email_array as $email) {
            $verification_data = array(
                "type" => "invitation",
                "code" => make_random_string(),
                "params" => serialize(array(
                    "email" => $email,
                    "type" => "staff",
                    "expire_time" => time() + (24 * 60 * 60) //make the invitation url with 24hrs validity
                ))
            );

            $save_id = $this->Verification_model->save($verification_data);
            $verification_info = $this->Verification_model->get_one($save_id);

            $parser_data['INVITATION_URL'] = get_uri("signup/accept_invitation/" . $verification_info->code);

            //send invitation email
            $message = $this->parser->parse_string($email_template->message, $parser_data, TRUE);

            $send_email[] = send_app_mail($email, $email_template->subject, $message);
        }

        if (!in_array(false, $send_email)) {
            if (count($send_email) != 0 && count($send_email) == 1) {
                echo json_encode(array('success' => true, 'message' => lang("invitation_sent")));
            } else {
                echo json_encode(array('success' => true, 'message' => lang("invitations_sent")));
            }
        } else {
            echo json_encode(array('success' => false, 'message' => lang('error_occurred')));
        }
    }

    //prepere the data for members list
    function list_data($type_of_user = "staff") {
        if (!$this->can_view_team_members_list()) {
            redirect("forbidden");
        }

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("team_members", $this->login_user->is_admin, $this->login_user->user_type);
        $filter_user = $this->input->post("status");
        if($filter_user == "applicant") {
            $type_of_user = "applicant";
        } else if( empty($filter_user) ){
            $filter_user = "active";
        }

        $options = array(
            "status" => $filter_user,
            "label_id" => $this->input->post('label_id'),
            'department_id' => $this->input->post('department_select2_filter'),
            'sched_id' => $this->input->post('schedule_select2_filter'),
            "user_type" => $type_of_user,
            "custom_fields" => $custom_fields
        );

        $list_data = $this->Users_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields, $options);
        }
        echo json_encode(array("data" => $result));
    }

    //get a row data for member list
    function _row_data($id) {
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("team_members", $this->login_user->is_admin, $this->login_user->user_type);
        $options = array(
            "id" => $id,
            "custom_fields" => $custom_fields,
            "include_teams" => "yes"
        );

        $data = $this->Users_model->get_details($options)->row();
        return $this->_make_row($data, $custom_fields);
    }

    //prepare team member list row
    private function _make_row($data, $custom_fields, $options = NULL) {
        $image_url = get_avatar($data->image);
        $user_avatar = "<span class='avatar avatar-xs'><img src='$image_url' alt='...'></span>";

        $full_name = $data->first_name . " " . $data->last_name . " ";
        if(get_setting('name_format') == "lastfirst") {
            $full_name = $data->last_name . ", " . $data->first_name;
        }

        //check contact info view permissions
        $show_cotact_info = $this->can_view_team_members_contact_info();

        $labels = "";
        if ($data->labels_list) {
            $labels = make_labels_view_data($data->labels_list, true);
        }

        $row_data = array(
            $user_avatar,
            $data->job_idnum,
            get_team_member_profile_link($data->id, $full_name),
            $data->rfid_num,
            $show_cotact_info ? $data->email : "",
            $show_cotact_info && $data->phone ? $data->phone : "-",
            $labels,
            $data->job_title,
            $data->team_list?$data->team_list:"-",
            $data->sched_name?$data->sched_name:"-",
            last_online_text($data->last_online)
        );

        foreach ($custom_fields as $field) {
            $cf_id = "cfv_" . $field->id;
            $row_data[] = $this->load->view("custom_fields/output_" . $field->field_type, array("value" => $data->$cf_id), true);
        }

        $action_btn = "";
        if (($this->login_user->is_admin || get_array_value($this->login_user->permissions, "hrs_employee_edit")) && $this->login_user->id != $data->id && get_array_value($options, "status") != "deleted") {
            $action_btn = modal_anchor(get_uri("hrs/team_members/edit_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_employee'), "data-post-id" => $data->id));
            $action_btn .= modal_anchor(get_uri("hrs/team_members/rfid_modal"), "<i class='fa fa-barcode'></i>", array("class" => "edit", "title" => lang('set_rfid'), "data-post-id" => $data->id));
        }

        if (($this->login_user->is_admin || get_array_value($this->login_user->permissions, "hrs_employee_delete")) && $this->login_user->id != $data->id) {
            if(get_array_value($options, "status") != "deleted") {
                $action_btn .= js_anchor("<i class='fa fa-times fa-fw'></i>", 
                    array(
                        'title' => lang('delete_team_member'), 
                        "class" => "delete user-status-confirm", 
                        "data-id" => $data->id, 
                        "data-action-url" => get_uri("hrs/team_members/delete"), 
                        "data-action" => "delete-confirmation"));
            } else {
                $action_btn .= js_anchor("<i class='fa fa-refresh fa-fw'></i>", 
                    array(
                        'title' => lang('restore_team_member'), 
                        "class" => "restore success user-status-confirm", 
                        "data-reason" => "restore",
                        "data-id" => $data->id, 
                        "data-action-url" => get_uri("hrs/team_members/restore"), 
                        "data-action" => "delete-confirmation"));
            }
        }

        if($this->login_user->is_admin || (!$this->login_user->is_admin && !$data->is_admin)) {
            $row_data[] = $action_btn;
        } else {
            $row_data[] = "";
        }

        return $row_data;
    }

    //delete a team member
    function delete() {
        if (!$this->login_user->is_admin && !get_array_value($this->login_user->permissions, "hrs_employee_delete")) {
			redirect("forbidden");
		}

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');

        if ($id != $this->login_user->id && $this->Users_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    function restore() {
        if (!$this->login_user->is_admin && !get_array_value($this->login_user->permissions, "hrs_employee_delete")) {
			redirect("forbidden");
		}

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');

        if ($id != $this->login_user->id && $this->Users_model->delete($id, true)) {
            echo json_encode(array("success" => true, 'message' => lang('record_restored')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_restored')));
        }
    }

    //show team member's details view
    function view($id = 0, $tab = "") {
        if ($id * 1) {

            //if team member's list is disabled, but the user can see his/her own profile.
            if (!$this->can_view_team_members_list() && $this->login_user->id != $id) {
                redirect("forbidden");
            }

            //we have an id. view the team_member's profie
            $options = array("id" => $id);
            $user_info = $this->Users_model->get_details($options)->row();
            if ($user_info) {

                //check which tabs are viewable for current logged in user
                $view_data['show_timeline'] = get_setting("module_timeline") ? true : false;

                $can_update_team_members_info = $this->can_update_team_members_info($id);

                $view_data['show_general_info'] = $can_update_team_members_info;
                $view_data['show_job_info'] = false;

                $view_data['show_account_settings'] = user_role_has_permission('can_update_account');

                $show_attendance = false;
                $show_leave = false;

                $view_data["show_expense_info"] = (get_setting("module_expense") == "1" && $this->validate_user_role_permission('expense', true));

                //admin can access all members attendance and leave
                //none admin users can only access to his/her own information 

                if ($user_info->id === $this->login_user->id || $can_update_team_members_info) {
                    $show_attendance = true;
                    $show_leave = true;
                    $view_data['show_job_info'] = true;
                } else {
                    $view_data['show_job_info'] = true;

                    //none admin users but who has access to this team member's attendance and leave can access this info
                    $access_timecard = $this->get_access_info("attendance");
                    if ($access_timecard->access_type === "all" || in_array($user_info->id, $access_timecard->allowed_members)) {
                        $show_attendance = true;
                    }

                    $access_leave = $this->get_access_info("leave");
                    if ($access_leave->access_type === "all" || in_array($user_info->id, $access_leave->allowed_members)) {
                        $show_leave = true;
                    }
                }


                //check module availability
                $view_data['show_attendance'] = $show_attendance && get_setting("module_attendance") ? true : false;
                $view_data['show_overtime'] = $show_attendance && get_setting("module_overtime") ? true : false;
                $view_data['show_leave'] = $show_leave && get_setting("module_leave") ? true : false;


                //check contact info view permissions
                $show_cotact_info = $this->can_view_team_members_contact_info();
                $show_social_links = $this->can_view_team_members_social_links();

                //own info is always visible
                if ($id == $this->login_user->id) {
                    $show_cotact_info = true;
                    $show_social_links = true;
                }

                $view_data['show_cotact_info'] = $show_cotact_info;
                $view_data['show_social_links'] = $show_social_links;


                //show projects tab to admin
                $view_data['show_projects'] = false;
                if ($this->can_manage_all_projects()) {
                    $view_data['show_projects'] = true;
                }

                $view_data['show_projects_count'] = false;
                if ($this->can_manage_all_projects()) {
                    $view_data['show_projects_count'] = true;
                }

                $view_data['tab'] = $tab; //selected tab
                $view_data['user_info'] = $user_info;
                $view_data['social_link'] = $this->Social_links_model->get_one($id);

                $hide_send_message_button = true;
                $this->init_permission_checker("message_permission");
                if ($this->check_access_on_messages_for_this_user() && $this->validate_sending_message($id)) {
                    $hide_send_message_button = false;
                }
                $view_data['hide_send_message_button'] = $hide_send_message_button;
                $view_data['payroll_enabled'] = $this->validate_user_role_permission("payroll_enable", true);

                $this->template->rander("team_members/view", $view_data);
            } else {
                show_404();
            }
        } else {

            if (!$this->can_view_team_members_list()) {
                redirect("forbidden");
            }

            //we don't have any specific id to view. show the list of team_member
            $view_data['team_members'] = $this->Users_model->get_details(array("user_type" => "staff", "status" => "active"))->result();
            $this->template->rander("team_members/profile_card", $view_data);
        }
    }

    function qrcode($id) {
        if(!user_has_permission($this->login_user->id, 'attendance')) {
            redirect("forbidden");
        }        

        $user_info = $this->Users_model->get_details(array("id" => $id))->row();
        if($user_info == null) {
            redirect("forbidden");
        }
        
        $qrdata = array(
            "id" => $user_info->id
        );
        echo Phpqr::generate($qrdata, false);
    }

    function deductions($user_id) {
        if(!$this->login_user->is_admin && !get_array_value($this->login_user->permissions, "team_member_update_permission") ) {
			redirect("forbidden");
		}
        $view_data['user_id'] = $user_id;

        $this->load->view("team_members/deductions", $view_data);
    }

    function list_deductions($user_id) {
        $data = get_user_deductions($user_id);
        echo json_encode(array("data" => $data));
    }

    function save_deductions_info() {
        if(!$this->login_user->is_admin && !get_array_value($this->login_user->permissions, "team_member_update_permission") ) {
			redirect("forbidden");
		}

        $user_id = $this->input->post("user_id");
        $prefix = "user_".$user_id."_";

        $result = array();
        $lists = ["sss_contri","pagibig_contri","philhealth_contri","hmo_contri","company_loan","sss_loan","hdmf_loan"];
        foreach($lists as $item) {
            $result[] = array(
                $item, 
                $this->input->post("daily_".$item),
                $this->input->post("weekly_".$item),
                $this->input->post("biweekly_".$item),
                $this->input->post("monthly_".$item),
            );
        }
        $result = serialize($result);

        $saved = $this->Settings_model->save_setting($prefix."deductions", $result, "user");
        echo json_encode(array("success" => $saved, 'message' => lang('record_updated')));
    }

    //show the job information of a team member
    function job_info($user_id) {
        if(!$this->login_user->is_admin && !get_array_value($this->login_user->permissions, "team_member_update_permission") ) {
			redirect("forbidden");
		}

        $options = array("id" => $user_id);
        $user_info = $this->Users_model->get_details($options)->row();
        $view_data['sched_dropdown'] = $this->_get_schedule_dropdown();

        $view_data['user_id'] = $user_id;
        $view_data['payroll_enabled'] = $this->validate_user_role_permission("payroll_enable", true);
        $view_data['job_info'] = $this->Users_model->get_job_info($user_id);
        $view_data['job_info']->job_title = $user_info->job_title;
        $this->load->view("team_members/job_info", $view_data);
    }

     //prepare the dropdown list of schedule
     private function _get_schedule_dropdown() {
        $sched_dropdown = array(
            "" => '-',
        );

        $schedule = $this->Schedule_model->get_details()->result();
        foreach ($schedule as $item) {
            $sched_dropdown[$item->id] = $item->title;
        }
        return $sched_dropdown;
    }

    private function _get_schedule_select2_data() {
        $schedules = $this->Schedule_model->get_details()->result();
        $schedule_select2 = array(array('id' => '', 'text'  => '- Schedule -'));

        foreach($schedules as $schedule){
            $schedule_select2[] = array('id' => $schedule->id, 'text'  => $schedule->title);
        }

        return $schedule_select2;
    }

    //save job information of a team member
    function save_job_info() {
        if( !$this->login_user->is_admin && !get_array_value($this->login_user->permissions, "team_member_update_permission") ) {
			redirect("forbidden");
		}

        validate_submitted_data(array(
            "user_id" => "required|numeric"
        ));

        $user_id = $this->input->post('user_id');

        $job_data = array(
            "user_id" => $user_id,
            "job_idnum" => $this->input->post('job_idnum'),
            "sched_id" => $this->input->post('sched_id'),
            "salary" => unformat_currency($this->input->post('salary')),
            "salary_term" => $this->input->post('salary_term'),
            "rate_per_hour" => $this->input->post('rate_per_hour'),
            "date_of_hire" => $this->input->post('date_of_hire'),
            "sss" => $this->input->post('sss'),
            "tin" => $this->input->post('tin'),
            "pag_ibig" => $this->input->post('pag_ibig'),
            "phil_health" => $this->input->post('phil_health'),
            "contact_name" => $this->input->post('contact_name'),
            "contact_address" => $this->input->post('contact_address'),
            "contact_phone" => $this->input->post('contact_phone'),
            "signiture_url" => $this->input->post('signiture_url')
        );

        if($this->input->post('rfid_num')) {
           $job_data['"rfid_num"'] = $this->input->post('rfid_num');
        }

        //we'll save the job title in users table
        $user_data = array(
            "job_title" => $this->input->post('job_title')
        );

        $this->Users_model->save($user_data, $user_id);
        if ($this->Users_model->save_job_info($job_data)) {
            echo json_encode(array("success" => true, 'message' => lang('record_updated')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    //show general information of a team member
    function general_info($user_id) {
        $this->update_only_allowed_members($user_id);

        $view_data['user_info'] = $this->Users_model->get_one($user_id);
        $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("team_members", $user_id, $this->login_user->is_admin, $this->login_user->user_type)->result();

        $this->load->view("team_members/general_info", $view_data);
    }

    //save general information of a team member
    function save_general_info($user_id) {
        $this->update_only_allowed_members($user_id);

        validate_submitted_data(array(
            "first_name" => "required",
            "last_name" => "required"
        ));

        $user_data = array(
            "first_name" => $this->input->post('first_name'),
            "last_name" => $this->input->post('last_name'),
            "street" => $this->input->post('street'),
            "city" => $this->input->post('city'),
            "state" => $this->input->post('state'),
            "country" => $this->input->post('country'),
            "zip" => $this->input->post('zip'),
            "phone" => $this->input->post('phone'),
            "skype" => $this->input->post('skype'),
            "gender" => $this->input->post('gender'),
            "alternative_address" => $this->input->post('alternative_address'),
            "alternative_phone" => $this->input->post('alternative_phone'),
            "dob" => $this->input->post('dob'),
            "ssn" => $this->input->post('ssn')
        );

        $user_data = clean_data($user_data);

        $user_info_updated = $this->Users_model->save($user_data, $user_id);

        save_custom_fields("team_members", $user_id, $this->login_user->is_admin, $this->login_user->user_type);

        if ($user_info_updated) {
            echo json_encode(array("success" => true, 'message' => lang('record_updated')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    //show social links of a team member
    function social_links($user_id) {
        //important! here id=user_id
        $this->update_only_allowed_members($user_id);

        $view_data['user_id'] = $user_id;
        $view_data['model_info'] = $this->Social_links_model->get_one($user_id);
        $this->load->view("users/social_links", $view_data);
    }

    //save social links of a team member
    function save_social_links($user_id) {
        $this->update_only_allowed_members($user_id);

        $id = 0;
        $has_social_links = $this->Social_links_model->get_one($user_id);
        if (isset($has_social_links->id)) {
            $id = $has_social_links->id;
        }

        $social_link_data = array(
            "facebook" => $this->input->post('facebook'),
            "twitter" => $this->input->post('twitter'),
            "linkedin" => $this->input->post('linkedin'),
            "googleplus" => $this->input->post('googleplus'),
            "digg" => $this->input->post('digg'),
            "youtube" => $this->input->post('youtube'),
            "pinterest" => $this->input->post('pinterest'),
            "instagram" => $this->input->post('instagram'),
            "github" => $this->input->post('github'),
            "tumblr" => $this->input->post('tumblr'),
            "vine" => $this->input->post('vine'),
            "user_id" => $user_id,
            "id" => $id ? $id : $user_id
        );

        $social_link_data = clean_data($social_link_data);

        $this->Social_links_model->save($social_link_data, $id);
        echo json_encode(array("success" => true, 'message' => lang('record_updated')));
    }

    //show account settings of a team member
    function account_settings($user_id) {
        if( !user_role_has_permission('can_update_account') ) {
            redirect("forbidden");
        }

        $view_data['user_info'] = $this->Users_model->get_one($user_id);
        if ($view_data['user_info']->is_admin) {
            $view_data['user_info']->role_id = "admin";
        }
        $view_data['role_dropdown'] = $this->_get_roles_dropdown();

        $view_data['show_account_access'] = $this->access_only_specific('team_member_update_permission', $user_id);
        $this->load->view("users/account_settings", $view_data);
    }

    //show my preference settings of a team member
    function my_preferences() {
        $view_data["user_info"] = $this->Users_model->get_one($this->login_user->id);

        //language dropdown
        $view_data['language_dropdown'] = array();
        if (!get_setting("disable_language_selector_for_team_members")) {
            $view_data['language_dropdown'] = get_language_list();
        }

        $view_data["hidden_topbar_menus_dropdown"] = $this->get_hidden_topbar_menus_dropdown();

        $this->load->view("team_members/my_preferences", $view_data);
    }

    function save_my_preferences() {
        //setting preferences
        $settings = array("notification_sound_volume", "disable_push_notification", "hidden_topbar_menus", "disable_keyboard_shortcuts");

        if (!get_setting("disable_language_selector_for_team_members")) {
            array_push($settings, "personal_language");
        }

        foreach ($settings as $setting) {
            $value = $this->input->post($setting);
            if (is_null($value)) {
                $value = "";
            }

            $this->Settings_model->save_setting("user_" . $this->login_user->id . "_" . $setting, $value, "user");
        }

        //there was 2 settings in users table.
        //so, update the users table also


        $user_data = array(
            "enable_web_notification" => $this->input->post("enable_web_notification"),
            "enable_email_notification" => $this->input->post("enable_email_notification"),
        );

        $user_data = clean_data($user_data);

        $this->Users_model->save($user_data, $this->login_user->id);

        echo json_encode(array("success" => true, 'message' => lang('settings_updated')));
    }

    function save_personal_language($language) {
        if (!get_setting("disable_language_selector_for_team_members") && ($language || $language === "0")) {

            $language = clean_data($language);

            $this->Settings_model->save_setting("user_" . $this->login_user->id . "_personal_language", strtolower($language), "user");
        }
    }

    //prepare the dropdown list of roles
    private function _get_roles_dropdown() {
        $role_dropdown = array(
            "" => '-',
        );
        if($this->login_user->is_admin) {
            $role_dropdown['admin'] = lang('admin');
        }

        $roles = $this->Roles_model->get_all()->result();
        foreach ($roles as $role) {
            $role_dropdown[$role->id] = $role->title;
        }
        return $role_dropdown;
    }

    //save account settings of a team member
    function save_account_settings($user_id) {
        if( !user_role_has_permission('can_update_account') ) {
            echo json_encode(array("success" => false, 'message' => lang('no_permission')));
            exit();
        }

        if ($this->Users_model->is_email_exists($this->input->post('email'), $user_id)) {
            echo json_encode(array("success" => false, 'message' => lang('duplicate_email')));
            exit();
        }
        $account_data = array(
            "email" => $this->input->post('email')
        );

        if ($this->login_user->id != $user_id && $this->access_only_specific('team_member_update_permission', $user_id) ) {
            //only admin user has permission to update team member's role
            //but admin user can't update his/her own role 
            $role = $this->input->post('role');
            $role_id = $role;

            if ($role === "admin") {
                $account_data["is_admin"] = 1;
                $account_data["role_id"] = 0;
            } else {
                $account_data["is_admin"] = 0;
                $account_data["role_id"] = $role_id;
            }

            $account_data['disable_login'] = $this->input->post('disable_login');
            $account_data['access_erpat'] = $this->input->post('access_erpat');
            $account_data['access_syntry'] = $this->input->post('access_syntry');
            $account_data['access_madage'] = $this->input->post('access_madage');
            $account_data['access_galyon'] = $this->input->post('access_galyon');
            $account_data['status'] = $this->input->post('status') === "inactive" ? "inactive" : "active";
        }

        //don't reset password if user doesn't entered any password
        if ($this->input->post('password')) {
            $account_data['password'] = password_hash($this->input->post("password"), PASSWORD_DEFAULT);
        }

        if ($this->Users_model->save($account_data, $user_id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_updated')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    //save profile image of a team member
    function save_profile_image($user_id = 0) {
        $this->update_only_allowed_members($user_id);
        $user_info = $this->Users_model->get_one($user_id);

        //process the the file which has uploaded by dropzone
        $profile_image = str_replace("~", ":", $this->input->post("profile_image"));

        if ($profile_image) {
            $profile_image = serialize(move_temp_file("avatar.png", get_setting("profile_image_path"), "", $profile_image));

            //delete old file
            delete_app_files(get_setting("profile_image_path"), array(@unserialize($user_info->image)));

            $image_data = array("image" => $profile_image);

            $this->Users_model->save($image_data, $user_id);
            echo json_encode(array("success" => true, 'message' => lang('profile_image_changed')));
        }

        //process the the file which has uploaded using manual file submit
        if ($_FILES) {
            $profile_image_file = get_array_value($_FILES, "profile_image_file");
            $image_file_name = get_array_value($profile_image_file, "tmp_name");
            if ($image_file_name) {
                $profile_image = serialize(move_temp_file("avatar.png", get_setting("profile_image_path"), "", $image_file_name));

                //delete old file
                delete_app_files(get_setting("profile_image_path"), array(@unserialize($user_info->image)));

                $image_data = array("image" => $profile_image);
                $this->Users_model->save($image_data, $user_id);
                echo json_encode(array("success" => true, 'message' => lang('profile_image_changed')));
            }
        }
    }

    //show projects list of a team member
    function projects_info($user_id) {
        if ($user_id) {
            $view_data['user_id'] = $user_id;
            $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("projects", $this->login_user->is_admin, $this->login_user->user_type);
            $this->load->view("team_members/projects_info", $view_data);
        }
    }

    //show attendance list of a team member
    function attendance_info($user_id) {
        if ($user_id) {
            $view_data['user_id'] = $user_id;
            $this->load->view("team_members/attendance_info", $view_data);
        }
    }

    //show weekly attendance list of a team member
    function weekly_attendance() {
        $this->load->view("team_members/weekly_attendance");
    }

    //show weekly attendance list of a team member
    function custom_range_attendance() {
        $this->load->view("team_members/custom_range_attendance");
    }

    //show attendance summary of a team member
    function attendance_summary($user_id) {
        $view_data["user_id"] = $user_id;
        $this->load->view("team_members/attendance_summary", $view_data);
    }

    //show overtime list of a team member
    function overtime_info($user_id) {
        if ($user_id) {
            $view_data['user_id'] = $user_id;
            $this->load->view("team_members/overtime_info", $view_data);
        }
    }

    //show weekly overtime list of a team member
    function weekly_overtime() {
        $this->load->view("team_members/weekly_overtime");
    }

    //show weekly overtime list of a team member
    function custom_range_overtime() {
        $this->load->view("team_members/custom_range_overtime");
    }

    //show overtime summary of a team member
    function overtime_summary($user_id) {
        $view_data["user_id"] = $user_id;
        $this->load->view("team_members/overtime_summary", $view_data);
    }

    //show leave list of a team member
    function leave_info($applicant_id) {
        if ($applicant_id) {
            $view_data['applicant_id'] = $applicant_id;
            $this->load->view("team_members/leave_info", $view_data);
        }
    }

    //show yearly leave list of a team member
    function yearly_leaves() {
        $this->load->view("team_members/yearly_leaves");
    }

    //show credit leave list of a team member
    function leave_credits() {
        $view_data["user_id"] = $user_id;
        $this->load->view("team_members/leave_credits", $view_data);
    }

    //show yearly expense list of a team member
    function expense_info($user_id) {
        $view_data["user_id"] = $user_id;
        $this->load->view("team_members/expenses", $view_data);
    }

    /* load files tab */

    function files($user_id) {

        $this->update_only_allowed_members($user_id);

        $options = array("user_id" => $user_id);
        $view_data['files'] = $this->General_files_model->get_details($options)->result();
        $view_data['user_id'] = $user_id;
        $this->load->view("team_members/files/index", $view_data);
    }

    /* file upload modal */

    function file_modal_form() {
        $view_data['model_info'] = $this->General_files_model->get_one($this->input->post('id'));
        $user_id = $this->input->post('user_id') ? $this->input->post('user_id') : $view_data['model_info']->user_id;

        $this->update_only_allowed_members($user_id);

        $view_data['user_id'] = $user_id;
        $this->load->view('team_members/files/modal_form', $view_data);
    }

    /* save file data and move temp file to parmanent file directory */

    function save_file() {


        validate_submitted_data(array(
            "id" => "numeric",
            "user_id" => "required|numeric"
        ));

        $user_id = $this->input->post('user_id');
        $this->update_only_allowed_members($user_id);


        $files = $this->input->post("files");
        $success = false;
        $now = get_current_utc_time();

        $target_path = getcwd() . "/" . get_general_file_path("team_members", $user_id);

        //process the fiiles which has been uploaded by dropzone
        if ($files && get_array_value($files, 0)) {
            foreach ($files as $file) {
                $file_name = $this->input->post('file_name_' . $file);
                $file_info = move_temp_file($file_name, $target_path);
                if ($file_info) {
                    $data = array(
                        "user_id" => $user_id,
                        "file_name" => get_array_value($file_info, 'file_name'),
                        "file_id" => get_array_value($file_info, 'file_id'),
                        "service_type" => get_array_value($file_info, 'service_type'),
                        "description" => $this->input->post('description_' . $file),
                        "file_size" => $this->input->post('file_size_' . $file),
                        "created_at" => $now,
                        "uploaded_by" => $this->login_user->id
                    );
                    $success = $this->General_files_model->save($data);
                } else {
                    $success = false;
                }
            }
        }


        if ($success) {
            echo json_encode(array("success" => true, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    /* list of files, prepared for datatable  */

    function files_list_data($user_id = 0) {
        $options = array("user_id" => $user_id);

        $this->update_only_allowed_members($user_id);

        $list_data = $this->General_files_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_file_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_file_row($data) {
        $file_icon = get_file_icon(strtolower(pathinfo($data->file_name, PATHINFO_EXTENSION)));

        $image_url = get_avatar($data->uploaded_by_user_image);
        $uploaded_by = "<span class='avatar avatar-xs mr10'><img src='$image_url' alt='...'></span> $data->uploaded_by_user_name";

        $uploaded_by = get_team_member_profile_link($data->uploaded_by, $uploaded_by);

        $description = "<div class='pull-left'>" .
                js_anchor(remove_file_prefix($data->file_name), array('title' => "", "data-toggle" => "app-modal", "data-sidebar" => "0", "data-url" => get_uri("hrs/team_members/view_file/" . $data->id)));

        if ($data->description) {
            $description .= "<br /><span>" . $data->description . "</span></div>";
        } else {
            $description .= "</div>";
        }

        $options = anchor(get_uri("hrs/team_members/download_file/" . $data->id), "<i class='fa fa fa-cloud-download'></i>", array("title" => lang("download")));

        $options .= js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_file'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("hrs/team_members/delete_file"), "data-action" => "delete-confirmation"));


        return array($data->id,
            "<div class='fa fa-$file_icon font-22 mr10 pull-left'></div>" . $description,
            convert_file_size($data->file_size),
            $uploaded_by,
            format_to_datetime($data->created_at),
            $options
        );
    }

    function view_file($file_id = 0) {
        $file_info = $this->General_files_model->get_details(array("id" => $file_id))->row();

        if ($file_info) {

            if (!$file_info->user_id) {
                redirect("forbidden");
            }

            $this->update_only_allowed_members($file_info->user_id);

            $view_data['can_comment_on_files'] = false;

            $view_data["file_url"] = get_source_url_of_file(make_array_of_file($file_info), get_general_file_path("team_members", $file_info->user_id));
            $view_data["is_image_file"] = is_image_file($file_info->file_name);
            $view_data["is_google_preview_available"] = is_google_preview_available($file_info->file_name);
            $view_data["is_viewable_video_file"] = is_viewable_video_file($file_info->file_name);
            $view_data["is_google_drive_file"] = ($file_info->file_id && $file_info->service_type == "google") ? true : false;

            $view_data["file_info"] = $file_info;
            $view_data['file_id'] = $file_id;
            $this->load->view("team_members/files/view", $view_data);
        } else {
            show_404();
        }
    }

    /* download a file */

    function download_file($id) {

        $file_info = $this->General_files_model->get_one($id);

        if (!$file_info->user_id) {
            redirect("forbidden");
        }
        $this->update_only_allowed_members($file_info->user_id);

        //serilize the path
        $file_data = serialize(array(make_array_of_file($file_info)));

        download_app_files(get_general_file_path("team_members", $file_info->user_id), $file_data);
    }

    /* upload a post file */

    function upload_file() {
        upload_file_to_temp();
    }

    /* check valid file for user */

    function validate_file() {
        return validate_post_file($this->input->post("file_name"));
    }

    /* delete a file */

    function delete_file() {

        $id = $this->input->post('id');
        $info = $this->General_files_model->get_one($id);

        if (!$info->user_id) {
            redirect("forbidden");
        }

        $this->update_only_allowed_members($info->user_id);

        if ($this->General_files_model->delete($id)) {

            //delete the files
            delete_app_files(get_general_file_path("team_members", $info->user_id), array(make_array_of_file($info)));

            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    /* show keyboard shortcut modal form */

    function keyboard_shortcut_modal_form() {
        $this->load->view('team_members/keyboard_shortcut_modal_form');
    }

}

/* End of file team_member.php */
/* Location: ./application/controllers/team_member.php */