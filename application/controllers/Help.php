<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Help extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->with_module("module_help", true);
        $this->with_permission("help", true);

        $this->load->model("Help_categories_model");
        $this->load->model("Help_articles_model");
    }

    //show help page
    function index() {
        $type = "help";
        $view_data["type"] = $type;
        $view_data["list_articles"] = $this->with_permission($type, false, true);
        $view_data["list_categories"] = $this->with_permission($type."_category", false, true);

        $this->template->rander("help_and_knowledge_base/articles/index", $view_data);
    }

    //show article
    function view($id = 0) {
        if (!$id || !is_numeric($id)) {
            show_404();
        }

        $model_info = $this->Help_articles_model->get_details(array("id" => $id))->row();

        if (!$model_info) {
            show_404();
        }

        $this->Help_articles_model->increas_page_view($id);

        $view_data['selected_category_id'] = $model_info->category_id;
        $view_data['type'] = $model_info->type;
        $view_data['categories'] = $this->Help_categories_model->get_details(array("type" => $model_info->type))->result();
        $view_data['page_type'] = "article_view";

        $view_data['article_info'] = $model_info;

        $this->template->rander('help_and_knowledge_base/articles/view_page', $view_data);
    }

    //get search suggestion for autocomplete
    function get_article_suggestion() {
        $search = $this->input->post("search");
        if ($search) {
            $result = $this->Help_articles_model->get_suggestions("help", $search);

            echo json_encode($result);
        }
    }

    //show help category
    function category($id) {
        if (!$id || !is_numeric($id)) {
            show_404();
        }

        $category_info = $this->Help_categories_model->get_one($id);
        if (!$category_info || !$category_info->id) {
            show_404();
        }

        $view_data['page_type'] = "articles_list_view";
        $view_data['type'] = $category_info->type;
        $view_data['selected_category_id'] = $category_info->id;
        $view_data['categories'] = $this->Help_categories_model->get_details(array("type" => $category_info->type))->result();

        $view_data["articles"] = $this->Help_articles_model->get_articles_of_a_category($id)->result();
        $view_data["category_info"] = $category_info;

        $this->template->rander("help_and_knowledge_base/articles/view_page", $view_data);
    }

    //show help articles list
    function view_preview() {
        $view_data["type"] = "help";
        $view_data["categories"] = $this->Help_categories_model->get_details(array("type" => "help", "only_active_categories" => true))->result();
        $this->load->view("help_and_knowledge_base/index", $view_data);
    }

    //show help articles list
    function view_articles() {
        $view_data["type"] = "help";
        $view_data["categories"] = $this->Help_categories_model->get_details(array("type" => "help", "only_active_categories" => true))->result();
        $view_data["article_create"] = $this->with_permission("help_create");
        $this->load->view("help_and_knowledge_base/articles/tab-panel", $view_data);
    }

    //show help articles list
    function  view_categories() {
        $view_data["type"] = "help";
        $view_data["create_category"] = $this->with_permission("help_category_create");
        $this->load->view("help_and_knowledge_base/categories/tab-panel", $view_data);
    }

    //show knowledge base articles list
    function knowledge_base_articles() {
        $view_data["type"] = "knowledge_base";
        $this->template->rander("help_and_knowledge_base/articles/tab-panel", $view_data);
    }

    //show knowledge base articles list
    function knowledge_base_categories() {
        $view_data["type"] = "knowledge_base";
        $this->template->rander("help_and_knowledge_base/categories/tab-panel", $view_data);
    }

    //show add/edit category modal
    function category_modal_form($type) {
        validate_submitted_data(array(
            "id" => "numeric"
        ));
        $id = $this->input->post('id');

        if( isset($id) ) {
            $this->with_permission($type."_category_update", true);
        } else {
            $this->with_permission($type."_category_create", true);
        }

        $view_data['model_info'] = $this->Help_categories_model->get_one($id);
        $view_data['type'] = $type;
        $this->load->view('help_and_knowledge_base/categories/modal_form', $view_data);
    }

    //save category
    function save_category() {
        validate_submitted_data(array(
            "id" => "numeric",
            "title" => "required",
            "type" => "required"
        ));
        $id = $this->input->post('id');
        $type = $this->input->post('type');

        if( isset($id) ) {
            $this->check_permission($type."_category_update");
        } else {
            $this->check_permission($type."_category_create");
        }

        $data = array(
            "title" => $this->input->post('title'),
            "description" => $this->input->post('description'),
            "type" => $type,
            "sort" => $this->input->post('sort'),
            "status" => $this->input->post('status')
        );
        $save_id = $this->Help_categories_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_category_row_data($save_id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    //delete/undo a category 
    function delete_category() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));
        $id = $this->input->post('id');
        $type = $this->input->get('type');

        $this->check_permission($type."_category_delete");

        if ($this->input->post('undo')) {
            if ($this->Help_categories_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_category_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Help_categories_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    //prepare categories list data
    function categories_list_data($type) {
        $list_data = $this->Help_categories_model->get_details(array("type" => $type))->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_category_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    //get a row of category row
    private function _category_row_data($id) {
        $options = array("id" => $id);
        $data = $this->Help_categories_model->get_details($options)->row();
        return $this->_make_category_row($data);
    }

    //make a row of category row
    private function _make_category_row($data) {
        $type = $data->type;
        $option = "";
        if($this->with_permission($type."_category_update")) {
            $option .= modal_anchor(get_uri("help/category_modal_form/" . $type), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_category'), "data-post-id" => $data->id, "data-post-type" => $type));
        }
        if($this->with_permission($type."_category_delete")) {
            $option .= js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_category'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("help/delete_category?type=".$data->type), "data-action" => "delete"));
        }
        
        return array(
            $data->title,
            $data->description ? $data->description : "-",
            lang($data->status),
            $data->sort, 
            $option 
        );
    }

    //show add/edit article form
    function article_form($type, $id = 0) {
        $view_data['model_info'] = $this->Help_articles_model->get_one($id);
        $view_data['type'] = $type;
        $view_data['categories_dropdown'] = $this->Help_categories_model->get_dropdown_list(array("title"), "id", array("type" => $type));
        $view_data['create_article'] = $this->with_permission($type."_create", true);
        $this->template->rander('help_and_knowledge_base/articles/form', $view_data);
    }

    //save article
    function save_article() {
        validate_submitted_data(array(
            "id" => "numeric",
            "title" => "required",
            "category_id" => "numeric|required"
        ));

        $id = $this->input->post('id');
        $type = $this->input->post('type');

        if( isset($id) ) {
            $this->with_permission($type."_update", true);
        } else {
            $this->with_permission($type."_create", true);
        }
        
        $target_path = get_setting("timeline_file_path");
        $files_data = move_files_from_temp_dir_to_permanent_dir($target_path, "help");
        $new_files = unserialize($files_data);

        $data = array(
            "title" => $this->input->post('title'),
            "description" => decode_ajax_post_data($this->input->post('description')),
            "category_id" => $this->input->post('category_id'),
            "status" => $this->input->post('status')
        );
        
        //is editing? update the files if required
        if ($id) {
            $expense_info = $this->Help_articles_model->get_one($id);
            $timeline_file_path = get_setting("timeline_file_path");

            $new_files = update_saved_files($timeline_file_path, $expense_info->files, $new_files);
        }

        $data["files"] = serialize($new_files);


        if (!$id) {
            $data["created_by"] = $this->login_user->id;
            $data["created_at"] = get_my_local_time();
        }


        $save_id = $this->Help_articles_model->save($data, $id);
        if ($save_id) {
            $this->session->set_flashdata("success_message", lang('record_saved'));
            redirect(get_uri("help/article_form/" . $type . "/" . $save_id));
        } else {
            $this->session->set_flashdata("error_message", lang('error_occurred'));
            redirect(get_uri("help/article_form/" . $type));
        }
    }

    //delete/undo an article 
    function delete_article() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));
        $id = $this->input->post('id');
        $type = $this->input->get('type');

        $this->check_permission($type."_delete");
        
        if ($this->input->post('undo')) {
            if ($this->Help_articles_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_article_row_data($id, $type), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Help_articles_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    //prepare article list data
    function articles_list_data($type) {
        $list_data = $this->Help_articles_model->get_details(array("type" => $type))->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_article_row($data, $type);
        }
        echo json_encode(array("data" => $result));
    }

    //get a row of article row
    private function _article_row_data($id, $type) {
        $options = array("id" => $id);
        $data = $this->Help_articles_model->get_details($options)->row();
        return $this->_make_article_row($data, $type);
    }

    //make a row of article row
    private function _make_article_row($data, $type) {
        $option = "";
        if($this->with_permission($type."_update")) {
            $option .= anchor(get_uri("help/article_form/" . $data->type . "/" . $data->id), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_article')));
        }
        if($this->with_permission($type."_delete")) {
            $option .= js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_article'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("help/delete_article"), "data-action" => "delete"));
        }

        return array(
            anchor(get_uri("help/view/" . $data->id), $data->title),
            $data->category_title,
            lang($data->status),
            $data->total_views,
            $option
        );
    }
    
    // upload a file 
    function upload_file() {
        upload_file_to_temp();
    }

    // check valid file for ticket 

    function validate_file() {
        return validate_post_file($this->input->post("file_name"));
    }

    // download files 
    function download_files($id = 0) {
        $info = $this->Help_articles_model->get_one($id);
        download_app_files(get_setting("timeline_file_path"), $info->files);
    }

}

/* End of file help.php */
/* Location: ./application/controllers/help.php */