<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Raffle_draw extends MY_Controller {

	function __construct() {
       	parent::__construct();
        $this->with_module("raffle", "redirect");
        $this->with_permission("raffle_draw", "redirect");

        $this->load->library('encryption');
		$this->load->model("Raffle_draw_model");
        $this->load->model("Events_model");
        $this->load->helper('utility');
    }

    function get_event_select2_data() {
        $events = $this->Events_model->get_details(array(
            "start_date" => get_current_utc_time('Y-m-d'),
            "end_date" => add_period_to_date(get_current_utc_time('Y-m-d'), 365)
        ))->result();
        $event_lists = array(array("id" => "", "text" => "- Select Event -"));
        foreach ($events as $key => $value) {
            $event_lists[] = array("id" => $value->id, "text" => trim($value->title . " (" . $value->start_date . " to " . $value->start_date .")"));
        }
        return $event_lists;
    }

    function get_winners_select2_data($raffle_id, $prize_id) {
        $winners = $this->Raffle_draw_model->get_winners(array(
            "raffle_id" => $raffle_id,
            "prize_id" => $prize_id,
        ))->result();
        $winner_lists = array(array("id" => "", "text" => "- Select Winner -"));
        foreach ($winners as $key => $value) {
            $winner_lists[] = array("id" => $value->id, "text" => $value->raffle_name." - ".strtoupper($value->participants_uuid) );
        }
        return $winner_lists;
    }

    function make_status_element($status = "draft") {
        if ($status == "active") {
            $status_class = "label-primary";
        } else if ($status == "cancelled") {
            $status_class = "label-danger";
        } else if ($status == "completed") {
            $status_class = "label-success";
        } else {
            $status_class = "label-default";
        }

        return "<span class='mt0 label $status_class large'>" . strtoupper($status) . "</span> ";
    }

    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Raffle_draw_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    function index(){
        $this->template->rander("raffle_draw/index");
    }

    function list_data(){
        $list_data = $this->Raffle_draw_model->get_details(array(
            'start' => $this->input->post('start_date'),
            'end' => $this->input->post('end_date')
        ))->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_row($data) {
        $passess_count = $this->Users_model->get_actual_active($data->passes);
        $pass_count = "<span class='label label-light w100'><i class='fa fa-users'></i> " . $passess_count . "</span>";
        return array(
            $data->id,
            $data->uuid,
            modal_anchor(get_uri("events/view"), $data->event_name, array("class" => "edit", "title" => lang('event_name'), "data-post-id" => encode_id($data->event_id, "event_id"))),
            $data->title,
            $data->description,
            $data->winners,
            $data->current_participants." / ".strval($data->total_participants?$data->total_participants:$data->current_participants),
            strtoupper($data->draw_preview),
            nl2br($data->remarks),
            strtoupper($data->ranking),
            $data->draw_date?convert_date_utc_to_local($data->draw_date, "Y-m-d h:i A"):"-",
            $this->make_status_element($data->status),
            get_team_member_profile_link($data->user_id, $data->user_name, array("target" => "_blank")),
            $data->timestamp,
            modal_anchor(get_uri("Raffle_draw/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('create_new_draw'), "data-post-id" => $data->id))
            .anchor(get_uri("Raffle_draw/export_qrcode/".$data->id), "<i class='fa fa-print'></i>", array("class" => "edit", "title" => lang('export_qrcode'), "target" => "_blank"))
            .modal_anchor(get_uri("Raffle_draw/modal_form_participants/"), "<i class='fa fa-users'></i>", array("class" => "edit", "title" => lang('view_participants'), "data-post-id" => $data->id))
            .modal_anchor(get_uri("Raffle_draw/modal_form_winners/"), "<i class='fa fa-star'></i>", array("class" => "edit", "title" => lang('view_winners'), "data-post-id" => $data->id))
            .anchor(get_uri("Raffle_draw/view_prizes/".$data->id), "<i class='fa fa-gift'></i>", array("title" => lang('prizes')))
            .modal_anchor(get_uri("Raffle_draw/modal_form_status"), "<i class='fa fa-power-off'></i>", array("class" => "edit", "title" => lang('update_Status'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("Raffle_draw/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));
        $id = $this->input->post('id');

        if($id) {
            $model_info = $this->Raffle_draw_model->get_details(array(
                "id" => $id
            ))->row();

            $localdt = convert_date_utc_to_local($model_info->draw_date);
            $model_info->draw_date = format_to_date($localdt);
            $model_info->draw_time = format_to_time($localdt, false);//date('H:i A', strtotime($model_info->draw_date)); //($model_info->draw_date);

            $view_data['model_info'] = $model_info;
        }

        $view_data['events_dropdown'] = $this->get_event_select2_data();
        $this->load->view('raffle_draw/modal_form', $view_data);
    }

    function save() {
        validate_submitted_data(array(
            "title" => "required",
            "number_of_winners" => "required",
            "total_participants" => "required",
            "draw_preview" => "required",
            "crowd_type" => "required",
            "draw_date" => "required",
            "draw_time" => "required",
        ));

        $id = $this->input->post('id');

        $draw_date = "";
        $date = $this->input->post('draw_date');
        $time = $this->input->post('draw_time');
        if(!empty($date) && !empty($time) ) {
            if (get_setting("time_format") != "24_hours") {
                $time = convert_time_to_24hours_format($time);
            }

            $draw_date = convert_date_local_to_utc($date." ".$time);
        }

        $data = array(
            "event_id" => $this->input->post('event_id'),
            "title" => $this->input->post('title'),
            "description" => $this->input->post('description'),
            "remarks" => $this->input->post('remarks'),
            "winners" => $this->input->post('number_of_winners'),
            "total_participants" => $this->input->post('total_participants'),
            "draw_preview" => $this->input->post('draw_preview'),
            "ranking" => $this->input->post('ranking'),
            //"labels" => $this->input->post('labels'),
            "draw_date" => $draw_date,
            "crowd_type" => $this->input->post('crowd_type'),
            "raffle_type" => $this->input->post('raffle_type'),
        );

        $data = clean_data($data);

        if(!$id) {
            $data["creator"] = $this->login_user->id;
            $data["uuid"] = $this->uuid->v4();
            $data["timestamp"] = get_current_utc_time();
        }

        $save_id = $this->Raffle_draw_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function modal_form_status() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        if($id = $this->input->post('id')) {
            $model_info = $this->Raffle_draw_model->get_details(array(
                "id" => $id
            ))->row();

            if($model_info->status == "draft") {
                $model_info->new_status = "active";
                $model_info->status_action = "Activate";
            } else if($model_info->status == "active") {
                $model_info->new_status = "cancelled";
                $model_info->status_action = "Cancel";
            } else {
                $model_info->new_status = "";
                $model_info->status_action = "None";
            }
            
            $view_data['model_info'] = $model_info;
            $this->load->view('raffle_draw/modal_form_status', $view_data);
        }
    }

    function list_participants($id = 0) {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        if($id) {
            $list_data = $this->Raffle_draw_model->get_participants(array(
                "raffle_id" => $id
            ))->result();
            
            $result = array();
            foreach ($list_data as $data) {
                $result[] = array(
                    $data->id,
                    get_custom_link("http://go.erpat.app/bse/", $data->uuid, array("target" => "_blank"), "LINK"),
                    get_custom_link(get_setting("raffle_entry_path"), strtoupper($data->uuid).".jpg", array("target" => "_blank"), strtoupper($data->uuid)),
                    get_team_member_profile_link($data->user_id, $data->user_name, array("target" => "_blank")),
                    $data->remarks,
                    $data->updated_at,
                );
            }
            echo json_encode(array("data" => $result));
        } else {
            echo json_encode(array("data" => array()));
        }
    }

    function modal_form_participants() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $raffle_id = $this->input->post('id');
        $view_data['model_info'] = $this->Raffle_draw_model->get_details(array("id"=>$raffle_id))->row();

        if($raffle_id) {
            $view_data['raffle_id'] = $id;
            $this->load->view('raffle_draw/modal_form_participants', $view_data);
        }
    }

    function list_winners($id = 0) {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        if($id) {
            $list_data = $this->Raffle_draw_model->get_winners(array(
                "raffle_id" => $id
            ))->result();
            
            $result = array();
            foreach ($list_data as $data) {
                $result[] = array(
                    $data->id,
                    $data->participants_uuid,
                    $data->prize_id,
                    get_team_member_profile_link($data->user_id, $data->user_name, array("target" => "_blank")),
                    $data->remarks,
                    $data->updated_at,
                );
            }
            echo json_encode(array("data" => $result));
        } else {
            echo json_encode(array("data" => array()));
        }
    }

    function modal_form_winners() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $raffle_id = $this->input->post('id');
        $view_data['model_info'] = $this->Raffle_draw_model->get_details(array("id"=>$raffle_id))->row();

        if($raffle_id) {
            $view_data['raffle_id'] = $raffle_id;
            $this->load->view('raffle_draw/modal_form_winners', $view_data);
        }
    }

    function modal_form_prizes($raffle_id) {
        validate_submitted_data(array(
            "id" => "numeric"
        ));
        $id = $this->input->post('id');
        if(!$id) {
            $id = "empty";
        }
        
        $view_data['model_info'] = $this->Raffle_draw_model->get_prizes(array("id"=>$id))->row();
        $view_data['winners_dropdown'] = $this->get_winners_select2_data($raffle_id, $id);
        $view_data['raffle_id'] = $raffle_id;
        
        if($raffle_id) {
            $this->load->view('raffle_draw/modal_form_prizes', $view_data);
        }
    }

    function list_prizes($id = 0) {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        if($id) {
            $list_data = $this->Raffle_draw_model->get_prizes(array(
                "raffle_id" => $id
            ))->result();
            
            $result = array();
            foreach ($list_data as $data) {
                $result[] = $this->make_row_prize($data);
            }
            echo json_encode(array("data" => $result));
        } else {
            echo json_encode(array("data" => array()));
        }
    }

    protected function make_row_prize($data) {
        $image_url =  $data->image_url?$data->image_url:get_uri("assets/images/image.jpg");

        $prize_preview = form_open(get_uri( "Raffle_draw/save_prize/" . $data->id), array("id" => $data->id."-prize-image-form", "class" => "cropper-form", "role" => "form")). 
        '<div id="'.$data->id.'-holder" class="file-upload btn mt0 p0 prize-image-upload">
            <span><i class="btn fa fa-camera"></i></span> 
            <input id="'.$data->id.'-prize_image_file" class="upload" name="'.$data->id.'-prize_image_file" type="file" data-height="200" data-width="200" data-preview-container="#'.$data->id.'-prize-image-preview" data-input-field="#'.$data->id.'-prize_image" />
        </div>'.
        '<input type="hidden" class="prize_base64" id="'.$data->id.'-prize_image" name="'.$data->id.'-prize_image" value=""  />'.
        "<span class='avatar avatar-s'><img id='".$data->id."-prize-image-preview' src='$image_url' alt='...' style='max-width: 100px; border-radius: 5%;'></span>".form_close();

        return array(
            $data->id,
            '<i class="fa fa-bars" aria-hidden="true"></i> ',
            $prize_preview,
            $data->participant_uuid,
            $data->remarks,
            $data->updated_at,
            modal_anchor(get_uri("Raffle_draw/modal_form_prizes/".$data->raffle_id), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_entry'), "data-post-id" => $data->id))
                . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_entry'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("Raffle_draw/delete_prize"), "data-action" => "delete-confirmation"))
        );
    }

    protected function row_prize($id) {
        $options = array("id" => $id);
        $data = $this->Raffle_draw_model->get_prizes($options)->row();
        return $this->make_row_prize($data);
    }

    function save_prize() {
        validate_submitted_data(array(
            "raffle_id" => "required",
            "remarks" => "required",
        ));
        $id = $this->input->post('id');

        $data = array(
            "raffle_id" => $this->input->post('raffle_id'),
            "winner_id" => $this->input->post('winner_id'),
            "remarks" => $this->input->post('remarks')
        );
        $data = clean_data($data);

        if(!$id) {
            $data["uuid"] = $this->uuid->v4();
        }

        $save_id = $this->Raffle_draw_model->save_prize($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->row_prize($save_id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function update_prize_image() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));
        $id = $this->input->post('id');

        $image = $this->input->post('image'); //TODO: Process
        $saved_url = save_base_64_image($image, get_setting("raffle_prize_path"));

        //save url to database.
        $data = array( "image_url" => $saved_url );
        $where = array( "id" => $id );
        $item_id = $this->Raffle_draw_model->update_prize_image($data, $where);
        
        if ($item_id) {
            echo json_encode(array("success" => true, 'message' => lang('record_saved')));
            exit;
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function view_prizes($id) {
        $raffle_id = $id;
        $view_data['model_info'] = $this->Raffle_draw_model->get_details(array("id"=>$raffle_id))->row();

        if($raffle_id) {
            $view_data['raffle_id'] = $id;
            $this->template->rander('raffle_draw/view_prizes', $view_data);
        }
    }

    function delete_prize() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));
        $id = $this->input->post('id');

        $success = $this->Raffle_draw_model->delete_prize($id);
        if ( $success ) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    function update_status() {
        validate_submitted_data(array(
            "id" => "required",
            "status" => "required",
        ));
        $id = $this->input->post('id');

        $data = array(
            "status" => $this->input->post('status'),
        );
        $data = clean_data($data);

        $save_id = $this->Raffle_draw_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));
        
        $id = $this->input->post('id');
        
        if ($this->Raffle_draw_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    function join($raffle_id = 0) {
        //TODO: Check API key first
        $user_id = $this->input->get('user_id');

        $user = $this->Users_model->get_details(array(
            "id" => $user_id,
            "user_type" => "customer"
        ));

        if(count($user->result()) == 0) {
            echo json_encode(array("success" => false, 'message' => lang('user_not_exist')));
            exit;
        }

        if(!$raffle_id) {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
            exit;
        }

        $raffle_object = $this->Raffle_draw_model->get_details(array("id"=>$raffle_id))->row();
        if(!$raffle_object) {
            echo json_encode(array("success" => false, 'message' => lang('raffle_not_exist')));
            exit;
        }

        if($raffle_object->current_participants >= $raffle_object->total_participants ) {
            echo json_encode(array("success" => false, 'message' => "Participants for this raffle is already full!"));
            exit;
        }

        $participated = $this->Raffle_draw_model->get_participants(array(
            "raffle_id"=>$raffle_id,
            "user_id"=>$user_id
        ));

        if(count($participated->result()) > 0) {
            echo json_encode(array("success" => false, 'message' => lang('raffle_user_exist')));
            exit;
        }

        $data = array(
            "uuid" => $this->uuid->v4(),
            "raffle_id" => $raffle_id,
            "user_id" => $user_id,
        );
        $data = clean_data($data);

        $save_id = $this->Raffle_draw_model->join_raffle($data);
        if ($save_id) {
            echo json_encode(array("success" => true, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('something_went_wrong')));
        }
    }

    function play($raffle_id = 0) {

        $raffle = $this->Raffle_draw_model->get_details(array(
            "id" => $raffle_id
        ))->row();

        if($raffle) {
            //Check if winners number is not equal.
            $winners = $this->Raffle_draw_model->get_winners(array(
                "raffle_id" => $raffle_id
            ))->result();

            if( count($winners) >= $raffle->winners ) {
                echo json_encode(array("success" => false, 'message' => lang('winners_completed')));
                exit;
            }

            $win_conf = array( "raffle_id" => $raffle_id, "winners" => 1 );
            $list_data = $this->Raffle_draw_model->pick_winners($win_conf)->result();

            if(count($list_data) == 0) {
                echo json_encode(array("success" => false, 'message' => lang('no_participants')));
                exit;
            }
            
            $result = array();
            foreach ($list_data as $data) {
                $result[] = array(
                    $data->id,
                    $data->uuid,
                    get_team_member_profile_link($data->user_id, $data->user_name, array("target" => "_blank")),
                    "Backend Draw",
                    $data->updated_at,
                );

                //Save winners here.
                $winner = array(
                    "uuid" => $this->uuid->v4(),
                    "raffle_id" => $raffle_id,
                    "participant_id" => $data->id,
                    "remarks" => "Backend Draw"
                );
                $this->Raffle_draw_model->save_winner($winner);
            }
            
            echo json_encode(array("success" => true, "data" => $result));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function clear_winners($raffle_id = 0) {

        if($raffle_id) {
            //Check if winners number is not equal.
            $cleared = $this->Raffle_draw_model->clear_winners($raffle_id);
            
            echo json_encode(array("success" => $cleared?true:false, "message" => lang('record_saved') ));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function anonymous_participants($raffle_id = 0, $total_joined = 1) {
        if($raffle_id) {
            for($i=0; $i < $total_joined; $i++) {
                $data = array(
                    "uuid" => $this->uuid->v4(),
                    "raffle_id" => $raffle_id,
                    "remarks" => "Bulk Join Anonymous"
                );

                $raffle_object = $this->Raffle_draw_model->get_details(array("id"=>$raffle_id))->row();
                if(!$raffle_object) {
                    echo json_encode(array("success" => false, 'message' => lang('raffle_not_exist')));
                    exit;
                }

                if($raffle_object->current_participants >= $raffle_object->total_participants ) {
                    echo json_encode(array("success" => false, 'message' => "Participants for this raffle is already full!"));
                    exit;
                }

                $cleared = $this->Raffle_draw_model->join_raffle($data);
            }
            
            echo json_encode(array("success" => $cleared?true:false, "message" => lang('record_saved') ));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function random_join($raffle_id = 0) {

        if($raffle_id) {
            $data = array("user_type"=>"customer", "randomize" => true);
            $subcribers = $this->Users_model->get_details($data)->row();

            $raffle_object = $this->Raffle_draw_model->get_details(array("id"=>$raffle_id))->row();
            if(!$raffle_object) {
                echo json_encode(array("success" => false, 'message' => lang('raffle_not_exist')));
                exit;
            }

            if($raffle_object->current_participants >= $raffle_object->total_participants ) {
                echo json_encode(array("success" => false, 'message' => "Participants for this raffle is already full!"));
                exit;
            }

            $data = array(
                "uuid" => $this->uuid->v4(),
                "raffle_id" => $raffle_id,
                "user_id" => $subcribers->id,
                "remarks" => "Random Join"
            );
            $cleared = $this->Raffle_draw_model->join_raffle($data);
            
            echo json_encode(array("success" => $cleared?true:false, "message" => lang('record_saved') ));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function clear_participants($raffle_id = 0) {

        if($raffle_id) {
            //Check if winners number is not equal.
            $cleared = $this->Raffle_draw_model->clear_participants($raffle_id);
            
            echo json_encode(array("success" => $cleared?true:false, "message" => lang('record_saved') ));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function export_qrcode( $raffle_id = 1) {

        $this->load->library('pdf');
        $this->pdf->setPrintHeader(false);
        $this->pdf->setPrintFooter(false);
        $this->pdf->setImageScale(1.0);
        $this->pdf->setEqualColumns(3, 69);
        $this->pdf->SetAutoPageBreak(true, -50);
        $this->pdf->setCellMargins('', 0, '', 0);
        $this->pdf->setCellPaddings('', 0, '', 0);
        $this->pdf->AddPage();

        $participant_lists = [];
        $list_data = $this->Raffle_draw_model->get_participants(array(
            "raffle_id" => $raffle_id
        ))->result();

        foreach($list_data as $item) {
            $participant_lists[] = $item;
        }
        
        $total = 0;
        $current = [];
        for( $i=0; $i<count($participant_lists); $i++ ) {
            $total += 1;
            $current[] = $participant_lists[$i];

            if(count($current) == 12 || $total == count($participant_lists)) {
                $html = $this->load->view("raffle_draw/single_qrcode", array(
                    'lists' => $current,
                ), true);
                $this->pdf->writeHTML($html, true, false, true, false, 'center');
                $current = [];
            }
            
        }

        $pdf_file_name =  "ExportParticipantCode.pdf";
        $this->pdf->Output($pdf_file_name, "I");
    }
}
