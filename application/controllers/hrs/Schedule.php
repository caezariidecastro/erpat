<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Schedule extends MY_Controller 
{
    function __construct() 
    {
        parent::__construct();
        $this->load->model("Schedule_model");

        //this module is accessible only to team members 
        $this->access_only_team_members();

        //we can set ip restiction to access this module. validate user access
        $this->check_allowed_ip();

        //initialize managerial permission
        $this->init_permission_checker("attendance"); //TODO: Change to schdule permission.
    }

    //check ip restriction for none admin users
    private function check_allowed_ip() {
        if (!$this->login_user->is_admin) {
            $ip = get_real_ip();
            $allowed_ips = $this->Settings_model->get_setting("allowed_ip_addresses");
            if ($allowed_ips) {
                $allowed_ip_array = array_map('trim', preg_split('/\R/', $allowed_ips));
                if (!in_array($ip, $allowed_ip_array)) {
                    redirect("forbidden");
                }
            }
        }
    }

    private function _get_members_query_options($type = "") {
        if ($this->access_type === "all") {
            $where = array("user_type" => "staff");
        } else {
            if (!count($this->allowed_members) && $type != "data") {
                $where = array("user_type" => "nothing"); //don't show any users in dropdown
            } else {
                //add login user in dropdown list
                $allowed_members = $this->allowed_members;
                $allowed_members[] = $this->login_user->id;

                $where = array("user_type" => "staff", "where_in" => ($type == "data") ? $allowed_members : array("id" => $allowed_members));
            }
        }

        return $where;
    }

    private function _get_members_dropdown_list_for_filter() {
        //prepare the dropdown list of members
        //don't show none allowed members in dropdown
        $where = $this->_get_members_query_options();

        $members = $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", $where);

        $members_dropdown = array(array("id" => "", "text" => "- " . lang("user") . " -"));
        foreach ($members as $id => $name) {
            $members_dropdown[] = array("id" => $id, "text" => $name);
        }

        return $members_dropdown;
    }

    function index() {
        $this->check_module_availability("module_attendance");
        $this->validate_user_module_permission("module_hrs");

        $view_data['team_members_dropdown'] = json_encode($this->_get_members_dropdown_list_for_filter());
        $this->template->rander("schedule/index", $view_data);
    }

    function list() {
        $list_data = $this->Schedule_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    protected function dayserialize($daysched) {
        if(empty($daysched)) {
            return NULL;
        }
        
        $array = unserialize($daysched);

        if(!isset($array['in']) || !isset($array['out'])) {
            return NULL;
        } 

        if(empty($array['in']) || empty($array['out'])) {
            return NULL;
        }
        
        return $array['in']."-".$array['out'];
    }

    //make a row of role list table
    private function _make_row($data) {
        $edit = '<li role="presentation">' . modal_anchor(get_uri("hrs/schedule/modal_form"), "<i class='fa fa-pencil'></i> " . lang('edit'), array("title" => lang('edit'), "data-post-view" => "details", "data-post-id" => $data->id)) . '</li>';

        $delete = '<li role="presentation">' . js_anchor("<i class='fa fa-times fa-fw'></i>" . lang('delete'), array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("hrs/schedule/delete"), "data-action" => "delete-confirmation")) . '</li>';

        $actions = '<span class="dropdown inline-block">
                        <button class="btn btn-default dropdown-toggle  mt0 mb0" type="button" data-toggle="dropdown" aria-expanded="true">
                            <i class="fa fa-cogs"></i>&nbsp;
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu pull-right" role="menu">' . $edit . $delete . '</ul>
                    </span>';

        $monday = empty($data->mon) || $data->mon == null ? NULL : $this->dayserialize($data->mon);
        $tuesday = empty($data->tue) || $data->tue == null ? NULL : $this->dayserialize($data->tue);
        $wednesday = empty($data->wed) || $data->wed == null ? NULL : $this->dayserialize($data->wed);
        $thursday = empty($data->thu) || $data->thu == null ? NULL : $this->dayserialize($data->thu);
        $friday = empty($data->fri) || $data->fri == null ? NULL : $this->dayserialize($data->fri);
        $saturday = empty($data->sat) || $data->sat == null ? NULL : $this->dayserialize($data->sat);
        $sunday = empty($data->sun) || $data->sun == null ? NULL : $this->dayserialize($data->sun);

        return array(
            $data->title,
            nl2br($data->desc),
            $monday, $tuesday, $wednesday, $thursday, $friday, $saturday, $sunday,
            $data->date_created,
            get_team_member_profile_link($data->created_by, $data->creator_name, array("target" => "_blank")),
            $actions
        );
    }

    protected function processOne($data) {
        if($data) {
            $data_modal = $data;

            if($data->mon) {
                $day = unserialize($data->mon);
                $data_modal->mon_enable = 1;
                $data_modal->mon_in = $day['in'];
                $data_modal->mon_out = $day['out'];
                print_r($day);
            }

            if($data->tue) {
                $day = unserialize($data->tue);
                $data_modal->tue_enable = 1;
                $data_modal->tue_in = $day['in'];
                $data_modal->tue_out = $day['out'];
            }

            if($data->wed) {
                $day = unserialize($data->wed);
                $data_modal->wed_enable = 1;
                $data_modal->wed_in = $day['in'];
                $data_modal->wed_out = $day['out'];
            }

            if($data->thu) {
                $day = unserialize($data->thu);
                $data_modal->thu_enable = 1;
                $data_modal->thu_in = $day['in'];
                $data_modal->thu_out = $day['out'];
            }

            if($data->fri) {
                $day = unserialize($data->fri);
                $data_modal->fri_enable = 1;
                $data_modal->fri_in = $day['in'];
                $data_modal->fri_out = $day['out'];
            }

            if($data->sat) {
                $day = unserialize($data->sat);
                $data_modal->sat_enable = 1;
                $data_modal->sat_in = $day['in'];
                $data_modal->sat_out = $day['out'];
            }

            if($data->sun) {
                $day = unserialize($data->sun);
                $data_modal->sun_enable = 1;
                $data_modal->sun_in = $day['in'];
                $data_modal->sun_out = $day['out'];
            }

            return $data_modal;
        }
        return "";
    }

    //show add/edit attendance modal
    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));
        $id = $this->input->post('id');

        $view_data['time_format_24_hours'] = get_setting("time_format") == "24_hours" ? true : false;
        $view_data['model_info'] = $id ? $this->Schedule_model->get_details(array("id" => $id))->row() : NULL;
        $view_data['model_info'] = $this->processOne($view_data['model_info']);

        $this->load->view('schedule/modal_form', $view_data);
    }

    protected function getDaySched($day) {
        $enabled = $this->input->post($day."_enable");
        $in = $this->input->post($day."_in");
        $out = $this->input->post($day."_out");

        if($enabled) {
            return serialize(
                array(
                    "in" => $in,
                    "out" => $out,
                )
            );
        } else {
            return NULL;
        }
    }

    function save() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');
        $title = $this->input->post('title');
        $desc = $this->input->post('desc');

        $data = array(
            "title" => $title,
            "desc" => $desc,
        );

        $data["mon"] = $this->getDaySched("mon");
        $data["tue"] = $this->getDaySched("tue");
        $data["wed"] = $this->getDaySched("wed");
        $data["thu"] = $this->getDaySched("thu");
        $data["fri"] = $this->getDaySched("fri");
        $data["sat"] = $this->getDaySched("sat");
        $data["sun"] = $this->getDaySched("sun");

        if(!$id){
            $data["date_created"] = get_my_local_time();
            $data["created_by"] = $this->login_user->id;
        }

        $saved_id = $this->Schedule_model->save($data, $id);

        if ($saved_id) {
            $options = array("id" => $saved_id);
            $current = $this->Schedule_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $saved_id, "data" => $this->_make_row($current), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        $query = $this->Schedule_model->delete($id);

        if ($query) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

}