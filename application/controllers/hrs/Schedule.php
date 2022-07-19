<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Schedule extends MY_Controller 
{
    function __construct() 
    {
        parent::__construct();
        $this->load->model("Schedule_model");
        $this->load->model('Users_model');

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
            convert_date_utc_to_local($data->date_created),
            get_team_member_profile_link($data->created_by, $data->creator_name, array("target" => "_blank")),
            $actions
        );
    }

    protected function processOne($data) {
        if($data) {
            $data_modal = $data;

            $days = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
            foreach($days as $day) {
                if($data->$day) {
                    $day_data = unserialize($data->$day);

                    $lunch_break = 0;
                    $trackings = ['', '_first', '_lunch', '_second'];
                    foreach($trackings as $tracking) {
                        $enable = $day."_enable".$tracking;
                        if( !empty($tracking) ) {
                            $data_modal->$enable = $day_data['enabled'.$tracking];

                            if($tracking === "_lunch" && isset($day_data['enabled_lunch']) && isset($day_data['in_lunch']) && isset($day_data['out_lunch'])) {
                                $in = get_today_date()." ".convert_time_to_24hours_format($day_data['in_lunch']);
                                if( strpos($day_data['in_lunch'], 'PM') !== false && strpos($day_data['out_lunch'], 'AM') !== false) {
                                    $out = get_tomorrow_date()." ".convert_time_to_24hours_format($day_data['out_lunch']);
                                } else {
                                    $out = get_today_date()." ".convert_time_to_24hours_format($day_data['out_lunch']);
                                }
                                $lunch_break += max(strtotime($out) - strtotime($in), 0);
                            }
                        } else {
                            $data_modal->$enable = 1;
                        }
                        
                        $in = $day."_in".$tracking;
                        $data_modal->$in = $day_data['in'.$tracking];
    
                        $out = $day."_out".$tracking;
                        $data_modal->$out = $day_data['out'.$tracking];
                    }
                    
                    $in = get_today_date()." ".convert_time_to_24hours_format($day_data['in']);
                    if( strpos($day_data['in'], 'PM') !== false && strpos($day_data['out'], 'AM') !== false) {
                        $out = get_tomorrow_date()." ".convert_time_to_24hours_format($day_data['out']);
                    } else {
                        $out = get_today_date()." ".convert_time_to_24hours_format($day_data['out']);
                    }
                    $sched = max(strtotime($out) - strtotime($in), 0);
                    $sched = max(($sched - $lunch_break), 0);

                    $hours = $day."_hours";
                    $data_modal->$hours = convert_seconds_to_hour_decimal($sched);

                    $text = $day."_text";
                    $data_modal->$text = $day_data['in']."-".$day_data['out'];
                }
            }

            return $data_modal;
        }
        return "";
    }

    //show add/edit attendance modal
    function modal_form($request = null) {
        validate_submitted_data(array(
            "id" => "numeric"
        ));
        
        $view_data['time_format_24_hours'] = get_setting("time_format") == "24_hours" ? true : false;
        $id = (int)$this->input->post('id');
        
        if($id !== 0) {
            $view_data['model_info'] = $this->Schedule_model->get_details(array("id" => $id))->row();
            $view_data['model_info'] = $this->processOne($view_data['model_info']);
        }

        //Show onmly view
        if($request == 'display') {
            $this->load->view('schedule/display', $view_data);
        } else {
            $this->load->view('schedule/modal_form', $view_data);
        }
    }

    protected function getDaySched($day) {
        $enabled = $this->input->post($day."_enable");
        $in = $this->input->post($day."_in");
        $out = $this->input->post($day."_out");

        $enabled_first = $this->input->post($day."_enable_first");
        $in_first = $this->input->post($day."_in_first");
        $out_first = $this->input->post($day."_out_first");

        $enabled_lunch = $this->input->post($day."_enable_lunch");
        $in_lunch = $this->input->post($day."_in_lunch");
        $out_lunch = $this->input->post($day."_out_lunch");

        $enabled_second = $this->input->post($day."_enable_second");
        $in_second = $this->input->post($day."_in_second");
        $out_second = $this->input->post($day."_out_second");

        if($enabled) {
            return serialize(
                array(
                    "in" => $in,
                    "out" => $out,

                    "enabled_first" => $enabled_first,
                    "in_first" => $in_first,
                    "out_first" => $out_first,

                    "enabled_lunch" => $enabled_lunch,
                    "in_lunch" => $in_lunch,
                    "out_lunch" => $out_lunch,

                    "enabled_second" => $enabled_second,
                    "in_second" => $in_second,
                    "out_second" => $out_second,
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

        $data["date_created"] = get_current_utc_time();
        $data["created_by"] = $this->login_user->id;

        $saved_id = $this->Schedule_model->save($data);

        if ($saved_id) {            
            $data = array(
                "sched_id"=>$saved_id,
                "prev_sched_id"=>$id
            );
            $this->Users_model->update_all_user_sched($data);

            // //if(!$test) {
            //     echo json_encode(array("success" => false, 'message' => json_encode($test) ));
            //     exit;
            // //}

            //Delete previous
            $this->Schedule_model->delete( $id );

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