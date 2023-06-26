<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Team extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->access_only_team_members();

        $this->init_permission_checker("department");
        $this->init_permission_checker("staff");
    }

    function index() {
        $view_data["can_add_new"] = $this->with_permission("department_create");
        $this->template->rander("team/index", $view_data);
    }

    function department(){
        $view_data["can_add_new"] = $this->with_permission("department_create");
        $this->template->rander("team/department", $view_data);
    }

    /* load team add/edit modal */

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));
        
        $team_members = $this->get_users_manage_only();
        $members_dropdown = array();

        $id = $this->input->post('id');
        if($id) {
            if(!$this->with_permission("department_update")) {
                exit_response_with_message("no_permission");
            }
        } else {
            if(!$this->with_permission("department_create")) {
                exit_response_with_message("no_permission");
            }
        }

        foreach ($team_members as $team_member) {
            $fullname = $team_member->first_name . " " . $team_member->last_name;
            if(get_setting('name_format') == "lastfirst") {
                $fullname = $team_member->last_name.", ".$team_member->first_name;
            }
            $members_dropdown[] = array("id" => $team_member->id, "text" => $fullname);
        }

        $view_data['members_dropdown'] = json_encode($members_dropdown);
        $view_data['model_info'] = $this->Team_model->get_one($id);
        $this->load->view('team/modal_form', $view_data);
    }

    function department_modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');
        if($id) {
            if(!$this->with_permission("department_update")) {
                exit_response_with_message("no_permission");
            }
        } else {
            if(!$this->with_permission("department_create")) {
                exit_response_with_message("no_permission");
            }
        }
        
        $team_members = $this->get_users_manage_only();
        $members_dropdown = array();

        foreach ($team_members as $team_member) {
            $fullname = $team_member->first_name . " " . $team_member->last_name;
            if(get_setting('name_format') == "lastfirst") {
                $fullname = $team_member->last_name.", ".$team_member->first_name;
            }
            $members_dropdown[] = array("id" => $team_member->id, "text" => $fullname);
        }

        $view_data['members_dropdown'] = json_encode($members_dropdown);
        $view_data['model_info'] = $this->Team_model->get_one($this->input->post('id'));
        $this->load->view('team/department_modal_form', $view_data);
    }

    /* add/edit a team */

    function save() {

        validate_submitted_data(array(
            "id" => "numeric",
            "title" => "required"
        ));

        $id = $this->input->post('id');
        if($id) {
            if(!$this->with_permission("department_update")) {
                exit_response_with_message("no_permission");
            }
        } else {
            if(!$this->with_permission("department_create")) {
                exit_response_with_message("no_permission");
            }
        }

        $data = array(
            "title" => $this->input->post('title'),
            "description" => $this->input->post('description'),
            "heads" => $this->input->post('heads'),
            "members" => $this->input->post('members'),
        );

        if(!$id){
            $data["date_created"] = get_current_utc_time();
            $data["created_by"] = $this->login_user->id;
        }

        $save_id = $this->Team_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    /* delete/undo a team */

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if($id) {
            if(!$this->with_permission("department_delete")) {
                exit_response_with_message("no_permission");
            }
        }

        if ($this->input->post('undo')) {
            if ($this->Team_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Team_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of team prepared for datatable */

    function list_data() {
        $option = array(
            "access_only" => $this->get_imploded_departments()
        );
        $list_data = $this->Team_model->get_details($option)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    /* reaturn a row of team list table */

    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Team_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    /* prepare a row of team list table */

    private function _make_row($data) {
        $member_count = $this->Users_model->get_actual_active($data->members);
        $total_members = "<span class='label label-light w100'><i class='fa fa-users'></i> " . $member_count . "</span>";
        $head_count = $this->Users_model->get_actual_active($data->heads);
        $total_heads = "<span class='label label-light w100'><i class='fa fa-users'></i> " . $head_count . "</span>";

        $actions = "";
        if($this->with_permission("department_update")) {            
            $actions = anchor(get_uri("hrs/team/export_qrcode/".$data->id), "<i class='fa fa-qrcode'></i> ", array("class" => "edit", "target" => "_blank"))
            .modal_anchor(get_uri("hrs/team/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_department'), "data-post-id" => $data->id));
        }

        if($this->with_permission("department_delete")) {            
            $actions .= js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_department'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("hrs/team/delete"), "data-action" => "delete-confirmation"));
        }

        return array(
            $data->title,
            $data->description,
            modal_anchor(get_uri("hrs/team/heads_list"), $total_heads, array("title" => lang('department_heads'), "data-post-heads" => $data->heads)),
            modal_anchor(get_uri("hrs/team/members_list"), $total_members, array("title" => lang('employee'), "data-post-members" => $data->members)),
            $data->date_created,
            get_team_member_profile_link($data->created_by, $data->creator_name, array("target" => "_blank")),
            $actions
        );
    }

    function members_list() {
        $view_data['team_members'] = $this->Users_model->get_team_members($this->input->post('members'))->result();
        $this->load->view('team/members_list', $view_data);
    }

    function heads_list() {
        $view_data['team_members'] = $this->Users_model->get_team_members($this->input->post('heads'))->result();
        $this->load->view('team/heads_list', $view_data);
    }

    function export_qrcode( $team_id = 0) {

        $team_info = $this->Team_model->get_details(array(
            "id" => $team_id
        ))->row();

        $this->load->library('pdf');
        $this->pdf->setPrintHeader(false);
        $this->pdf->setPrintFooter(false);
        $this->pdf->SetCellPadding(1);
        $this->pdf->setImageScale(2.0);
        $this->pdf->AddPage();
        $this->pdf->SetFontSize(9);

        $this->load->helper('utility');
        $users = get_team_all_unique($team_info->heads, $team_info->members);
        $total_pages = ceil(count($users)/20); //20 items per page
        $html = "Page 1 of $total_pages for ".$team_info->title;
        $this->pdf->writeHTML($html, true, false, true, false, '');

        $style = array(
            'border' => 2,
            'vpadding' => '2px',
            'hpadding' => '2px',
            'fgcolor' => array(0,0,0),
            'bgcolor' => array(255,255,255), //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );
		
		$current_page = 1;
        $current_col_number = 0;
		$current_rows = 0;

        $current_width = 10;
        $incremental_width = 50;
        
        $current_height = 20;
        $incremental_height  = 52;

        for( $i=0; $i<count($users); $i++ ) {
			if(!$user_info = $this->Users_model->get_details(array(
                'id'=>$users[$i],
                'status'=>'active'
                ))->row()) {
				continue; //skip this, user not found.
			}
			
            // QRCODE,H : QR-CODE Best error correction
            $this->pdf->write2DBarcode('{"id":"'.$users[$i].'"}', 'QRCODE,H', $current_width , $current_height, 40, 40, $style, 'N');
            $fullname = $user_info->first_name . " " . $user_info->last_name;
            if(get_setting('name_format') == "lastfirst") {
                $fullname = $user_info->last_name.", ".$user_info->first_name;
            }
            $this->pdf->Text($current_width , $current_height+41, $fullname);

            $current_width += $incremental_width;
			$current_rows += 1;
			
            if($current_col_number < 3) {
                $current_col_number += 1;
            } else {
                $current_width = 10;
                $current_col_number = 0;
                $current_height += $incremental_height;
			
				if($current_rows >= 20) {
					$current_rows = 0;
					$current_height = 20;
					$this->pdf->AddPage();
					
                    $current_page += 1;
					$html = "Page ".$current_page." of ".$team_info->title;
        			$this->pdf->writeHTML($html, true, false, true, false, '');
				}
            }
        }

        $pdf_file_name =  str_replace(" ", "-", $team_info->title) . "-QRcodes.pdf";
        $this->pdf->Output($pdf_file_name, "I");
    }

}

/* End of file team.php */
/* Location: ./application/controllers/team.php */