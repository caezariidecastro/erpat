<?php 
        
defined('BASEPATH') OR exit('No direct script access allowed');
        
class Offers extends My_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->access_only_team_members();
        $this->load->library('Uuid');
        $this->load->model('Offers_model');
    }

    // protected function validate_access_to_offer($offer_info, $edit_mode = false) {
    //     if ($offer_info->is_public) {
    //         //it's a public_offer. visible to all available users
    //         if ($edit_mode) {
    //             //for edit mode, only creator and admin can access
    //             if ($this->login_user->id !== $offer_info->created_by && !$this->login_user->is_admin) {
    //                 redirect("forbidden");
    //             }
    //         }
    //     } else {
    //         if ($offer_info->client_id) {
    //             //this is a client's_offer. check client access permission
    //             $access_info = $this->get_access_info("client");
    //             if ($access_info->access_type != "all") {
    //                 redirect("forbidden");
    //             }
    //         } else if ($offer_info->user_id) {
    //             //this is a user's_offer. check user's access permission.
    //             redirect("forbidden");
    //         } else {
    //             //this is a private_offer. only available to creator
    //             if ($this->login_user->id !== $offer_info->created_by) {
    //                 redirect("forbidden");
    //             }
    //         }
    //     }
    // }

    //load offers list view
    function index() {
        $this->check_module_availability("module_offers");

        $this->template->rander("offers/index");
    }

    function modal_form() {
        $view_data['model_info'] = $this->Offers_model->get_one($this->input->post('id'));

        //check permission for saved_offer
        // if ($view_data['model_info']->id) {
        //     $this->validate_access_to_offer($view_data['model_info'], true);
        // }

        //$view_data['label_suggestions'] = $this->make_labels_dropdown(_offer", $view_data['model_info']->labels, false);
        $this->load->view('offers/modal_form', $view_data);
    }

    function save() {
        validate_submitted_data(array(
            "id" => "numeric",
            "title" => "required",
            "off" => "required|numeric",
            "min" => "required|numeric",
            "upto" => "required|numeric",
        ));

        $id = $this->input->post('id');

        $target_path = get_setting("timeline_file_path");
        $files_data = move_files_from_temp_dir_to_permanent_dir($target_path, "offers");
        $new_files = unserialize($files_data);

        $data = array(
            "uuid" => $this->uuid->v4(),
            "title" => $this->input->post('title'),
            "description" => $this->input->post('description'),
            
            "type" => $this->input->post('type'),
            "off" => $this->input->post('off'),
            "min" => $this->input->post('min'),
            "upto" => $this->input->post('upto'),

            "created_by" => $this->login_user->id
        );

        if ($id) {
            $offer_info = $this->Offers_model->get_one($id);
            $timeline_file_path = get_setting("timeline_file_path");

            $new_files = update_saved_files($timeline_file_path, $offer_info->files, $new_files);
        }

        $data["files"] = serialize($new_files);

        if ($id) {
            //saving existing offer. check permission
            $offer_info = $this->Offers_model->get_one($id);

            //$this->validate_access_to_offer($offer_info, true);
        } else {
            $data['created_at'] = get_current_utc_time();
        }

        $data = clean_data($data);

        $save_id = $this->Offers_model->save($data, $id);
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

        $offer_info = $this->Offers_model->get_one($id);
        //$this->validate_access_to_offer($offer_info, true);

        if ($this->Offers_model->delete($id)) {
            //delete the files
            $file_path = get_setting("timeline_file_path");
            if ($offer_info->files) {
                $files = unserialize($offer_info->files);

                foreach ($files as $file) {
                    delete_app_files($file_path, array($file));
                }
            }

            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    function list_data($type = "", $id = 0) {
        $options = array();

        $list_data = $this->Offers_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Offers_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    private function _make_row($data) {
        //$public_icon = "";
        // if ($data->is_public) {
        //     $public_icon = "<i class='fa fa-globe'></i> ";
        // }

        //$title = modal_anchor(get_uri("offers/view/" . $data->id), $public_icon . $data->title, array("title" => lang('offers'), "data-post-id" => $data->id));

        // if ($data->labels_list) {
        //     _offer_labels = make_labels_view_data($data->labels_list, true);
        //     $title .= "<br />" . _offer_labels;
        // }

        $files_link = "";
        if ($data->files) {
            $files = unserialize($data->files);
            if (count($files)) {
                foreach ($files as $key => $value) {
                    $file_name = get_array_value($value, "file_name");
                    $link = " fa fa-" . get_file_icon(strtolower(pathinfo($file_name, PATHINFO_EXTENSION)));
                    $files_link .= js_anchor(" ", array('title' => "", "data-toggle" => "app-modal", "data-sidebar" => "0", "class" => "pull-left font-22 mr10 $link", "title" => remove_file_prefix($file_name), "data-url" => get_uri("offers/file_preview/" . $data->id . "/" . $key)));
                }
            }
        }

        //only creator and admin can edit/delete offers
        $actions = modal_anchor(get_uri("offers/view/" . $data->id), "<i class='fa fa-bolt'></i>", array("class" => "edit", "title" => lang('offers_details'), "data-modal-title" => lang("offers"), "data-post-id" => $data->id));
        if ($data->created_by == $this->login_user->id || $this->login_user->is_admin) {
            $actions = modal_anchor(get_uri("offers/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_offers'), "data-post-id" => $data->id))
                    . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_offers'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("offers/delete"), "data-action" => "delete-confirmation"));
        }

        //format_to_relative_time($data->created_at),
        return array(
            $data->uuid,
            $data->title,
            $data->description,

            strtoupper($data->type),
            $data->off,
            $data->min,
            $data->upto,

            $files_link,
            get_team_member_profile_link($data->created_by, $data->creator, array("target" => "_blank")),
            $data->created_at,
            $data->updated_at,
            $actions
        );
    }

    function view() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $model_info = $this->Offers_model->get_details(array("id" => $this->input->post('id')))->row();

        //$this->validate_access_to_offer($model_info);

        $view_data['model_info'] = $model_info;
        $this->load->view('offers/view', $view_data);
    }

    function file_preview($id = "", $key = "") {
        if ($id) {
            $offers_info = $this->Offers_model->get_one($id);
            $files = unserialize($offers_info->files);
            $file = get_array_value($files, $key);

            $file_name = get_array_value($file, "file_name");
            $file_id = get_array_value($file, "file_id");
            $service_type = get_array_value($file, "service_type");

            $view_data["file_url"] = get_source_url_of_file($file, get_setting("timeline_file_path"));
            $view_data["is_image_file"] = is_image_file($file_name);
            $view_data["is_google_preview_available"] = is_google_preview_available($file_name);
            $view_data["is_viewable_video_file"] = is_viewable_video_file($file_name);
            $view_data["is_google_drive_file"] = ($file_id && $service_type == "google") ? true : false;

            $this->load->view("offers/file_preview", $view_data);
        } else {
            show_404();
        }
    }

    /* upload a file */

    function upload_file() {
        upload_file_to_temp();
    }

    /* check valid file for offers */

    function validate_offers_file() {
        return validate_post_file($this->input->post("file_name"));
    }
}
        
/* End of file  Offers.php */                        