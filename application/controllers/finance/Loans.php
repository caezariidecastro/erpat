<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Loans extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->with_module("module_loan", true);
        $this->with_permission("loan", true);

        $this->load->model("Loans_model");
        $this->load->model("Loan_categories_model");
        $this->load->model("Loan_fees_model");
        $this->load->model("Loan_payments_model");
        $this->load->model("Loan_transactions_model");
    }

    //load loan list view
    function index() {
        $this->template->rander("loans/index");
    }

    function entries() {
        $this->load->view("loans/entries");
    }

    //load loan add/edit form
    function modal_form() {
        $this->with_permission("loan_create", true);
        $view_data['model_info'] = $this->Loans_model->get_one($this->input->post('id'));
        $view_data['team_members_dropdown'] = array("" => "- Select -") + $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", array("deleted" => 0, "user_type" => "staff"));
        $view_data['loan_categories_dropdown'] = array("" => "- Select -") + $this->Loan_categories_model->get_dropdown_list(array("name"), "id", array("deleted" => 0, "status" => 1));
        $this->load->view('loans/modal_form', $view_data);
    }

    function modal_form_update() {
        $this->with_permission("loan_update", true);
        $view_data['model_info'] = $this->Loans_model->get_one($this->input->post('id'));
        $this->load->view('loans/modal_form_update', $view_data);
    }

    function modal_form_categories() {
        $id = $this->input->post('id');
        $view_data['model_info'] = $this->Loan_categories_model->get_one($id);
        $this->load->view('loans/modal_form_category', $view_data);
    }

    function modal_form_fee() {
        $view_data['loan_dropdowns'] = array("" => "- Select -") + $this->Loans_model->get_dropdown_list(array("id"), "id", array("deleted" => 0), "loan");
        $view_data['model_info'] = $this->Loan_fees_model->get_one($this->input->post('id'));
        $this->load->view('loans/modal_form_fee', $view_data);
    }

    function modal_form_payment() {
        $view_data['loan_dropdowns'] = array("" => "- Select -") + $this->Loans_model->get_dropdown_list(array("id"), "id", array("deleted" => 0), "loan");
        $id = $this->input->post('id');
        $view_data['model_info'] = $this->Loan_payments_model->get_one($id);
        $this->load->view('loans/modal_form_payment', $view_data);
    }

    function save_category() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');

        $data = array(
            "name" => $this->input->post('title'),
            "description" => $this->input->post('description'),
            "timestamp" => get_current_utc_time(),
            "status" => $this->input->post('active')
        );

        if(!$id){
            $data["created_by"] = $this->login_user->id;
        }

        $id = $this->Loan_categories_model->save($data, $id);
        if ($id) {
            $options = array("id" => $id);
            $loan_categories = $this->Loan_categories_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $loan_categories->id, "data" => $this->_make_row_category($loan_categories), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function save_update() {
        validate_submitted_data(array(
            "loan_id" => "numeric|required",
            "status" => "required",
            "stage_name" => "required",
            "remarks" => "required"
        ));

        $loan_id = $this->input->post('loan_id');
        $status = strtoupper($this->input->post('status'));
        $stage_name = $this->input->post('stage_name');
        $remarks = $this->input->post('remarks');

        $data = array(
            "loan_id" => $loan_id,
            "stage_name" => $status." - ".$stage_name,
            "remarks" => $remarks,
            "timestamp" => get_current_utc_time(),
            "executed_by" => $this->login_user->id,
            "serial_data" => null
        );

        $save_id = $this->Loan_transactions_model->save($data);
        if ($save_id && $loan_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($loan_id), 'id' => $loan_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function save_fee() {
        validate_submitted_data(array(
            "loan_id" => "numeric|required",
            "title" => "required",
            "amount" => "numeric|required"
        ));

        $loan_id = $this->input->post('loan_id');

        $data = array(
            "loan_id" => $loan_id,
            "title" => $this->input->post('title'),
            "amount" => $this->input->post('amount'),
            "remarks" => $this->input->post('remarks'),
        );

        if(!$id){
            $data["created_by"] = $this->login_user->id;
        }

        $save_id = $this->Loan_fees_model->save($data);
        if ($save_id) {
            echo json_encode(array("success" => true, "id" => $loan_id, "data" => $this->_row_data($loan_id), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function save_payment() {
        validate_submitted_data(array(
            "loan_id" => "numeric|required",
            "date_paid" => "required",
            "amount" => "numeric|required"
        ));

        $loan_id = $this->input->post('loan_id');

        $data = array(
            "loan_id" => $loan_id,
            "date_paid" => $this->input->post('date_paid'),
            "amount" => $this->input->post('amount'),
            "remarks" => $this->input->post('remarks'),
        );

        if(!$id){
            $data["created_by"] = $this->login_user->id;
        }

        $save_id = $this->Loan_payments_model->save($data);
        if ($save_id) {
            echo json_encode(array("success" => true, "id" => $loan_id, "data" => $this->_row_data($loan_id), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    //save loan
    function save() {
        $this->with_permission("loan_create", true);

        validate_submitted_data(array(
            "category_id" => "numeric|required",
            "borrower_id" => "numeric|required",
            "principal_amount" => "numeric|required",
            "months_to_pay" => "numeric|required",
        ));

        $id = $this->input->post('id');
        $data = array(
            "category_id" => $this->input->post('category_id'),
            "borrower_id" => $this->input->post('borrower_id'),
            "cosigner_id" => $this->input->post('cosigner_id'),
            "principal_amount" => $this->input->post('principal_amount'),
            "months_topay" => $this->input->post('months_to_pay'),
            "days_before_due" => $this->input->post('days_before_due'),
            "penalty_rate" => $this->input->post('penalty_rate'),
            "remarks" => $this->input->post('remarks'),
        );

        if(!$id){
            $data["date_applied"] = get_current_utc_time();
            $data["created_by"] = $this->login_user->id;
        }

        $save_id = $this->Loans_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    //delete/undo a leve type
    function delete() {
        $this->with_permission("loan_delete", true);
        
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Loans_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Loans_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    function delete_category() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->Loan_categories_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    //prepare loan list data for datatable
    function list_data() {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $user_id = $this->input->post('user_id');
        
        $options = array(
            "start_date" => $start_date,
            "end_date" => $end_date,
            "borrower_id" => $user_id,
        );

        $list_data = $this->Loans_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            if(!$data->id) {
                continue;
            }
            $result[] = $this->_make_row($data, $action=="balance"?true:false);
        }
        echo json_encode(array("data" => $result));
    }

    //get a row of loan row
    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Loans_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    protected function _make_row_category($data) {
        return array(
            $data->name,
            $data->description,
            $data->status?"ACTIVE":"INACTIVE",
            get_team_member_profile_link($data->created_by, $data->created_name, array("target" => "_blank")),
            convert_date_utc_to_local($data->timestamp),
            modal_anchor(get_uri("finance/Loans/modal_form_categories"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_category'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete user-status-confirm", "data-id" => $data->id, "data-action-url" => get_uri("finance/Loans/delete_category"), "data-action" => "delete-confirmation"))
        );
    }

    //make a row of loan row
    private function _make_row($data, $balance = false) {

        $loan_detail = modal_anchor(get_uri("finance/Loans/view_stages"), get_id_name($data->id, date("Y", strtotime($data->date_applied)).'-L', 4), array("class" => "edit", "title" => lang('loan')." ".lang('stages'), "data-post-id" => $data->id));

        $payment_detail = modal_anchor(get_uri("finance/Loans/view_payments"), to_currency($data->payments), array("class" => "edit", "title" => lang('loan')." ".lang('payments'), "data-post-id" => $data->id));

        $fees_detail = modal_anchor(get_uri("finance/Loans/view_fees"), to_currency($data->fees), array("class" => "edit", "title" => lang('loan')." ".lang('fees'), "data-post-id" => $data->id));

        $current_balance = ($data->principal_amount+$data->fees)-$data->payments;

        return array(
            $loan_detail,
            $data->category_name ?$data->category_name:"-",
            format_to_date($data->date_applied, true),
            get_team_member_profile_link($data->borrower_id, $data->borrower_name, array("target" => "_blank")),
            to_currency($data->principal_amount),
            $data->interest_rate."%",
            $data->months_topay,
            to_currency($data->min_payment),
            $payment_detail,
            to_currency($current_balance),
            $fees_detail,
            $data->days_before_due,
            $data->penalty_rate."%",
            format_to_date($data->start_payment, true),
            $data->remarks ? $data->remarks : "-",
            get_team_member_profile_link($data->cosigner_id, $data->cosigner_name, array("target" => "_blank")),
            modal_anchor(get_uri("finance/Loans/modal_form_update"), "<i class='fa fa-bolt fa-fw'></i>", array("class" => "edit", "title" => lang('update_status'), "data-post-id" => $data->id)).modal_anchor(get_uri("finance/Loans/modal_form_payment"), "<i class='fa fa-money fa-fw'></i>", array("class" => "edit", "title" => lang('add_payment'), "data-post-id" => $data->id)).
            modal_anchor(get_uri("finance/Loans/modal_form_fee"), "<i class='fa fa-tag fa-fw'></i>", array("class" => "edit", "title" => lang('add_fee'), "data-post-id" => $data->id))
            .js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("finance/Loans/delete"), "data-action" => "delete"))
        );
    }

    function view_fees() {
        $view_data['loan_info'] = $this->Loans_model->get_details(array("id"=>$this->input->post('id')))->row();
        $view_data['fees_detail'] = $this->Loan_fees_model->get_details(array("loan_id"=>$this->input->post('id')));
        $this->load->view('loans/fees_details', $view_data);
    }

    function view_payments() {
        $view_data['loan_info'] = $this->Loans_model->get_details(array("id"=>$this->input->post('id')))->row();
        $view_data['payments_detail'] = $this->Loan_payments_model->get_details(array("loan_id"=>$this->input->post('id')));
        $this->load->view('loans/payments_details', $view_data);
    }

    function view_stages() {
        $loan_info = $this->Loans_model->get_details(array("id"=>$this->input->post('id')))->row();
        $view_data['loan_id_name'] = get_id_name($loan_info->id, date("Y", strtotime($loan_info->date_applied)).'-S', 4);
        $view_data['loan_info'] = $loan_info;
        $view_data['stages_detail'] = $this->Loan_transactions_model->get_details(array("loan_id"=>$this->input->post('id')));
        $this->load->view('loans/stages_detail', $view_data);
    }

    function view_transactions() {
        $this->load->view("loans/transactions");
    }

    function list_transactions() {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $user_id = $this->input->post('user_id');
        
        $options = array(
            "loan_id" => $loan_id,
            "start_date" => $start_date,
            "end_date" => $end_date,
            "borrower_id" => $user_id,
        );

        $list_data = $this->Loan_transactions_model->get_details($options);
        $result = array();
        foreach ($list_data as $data) {
            $result[] = array(
                get_id_name($data->loan_id, date("Y", strtotime($data->date_applied)).'-T', 4),
                $data->borrower_name,
                $data->stage_name,
                $data->remarks,
                $data->executer_name,
                format_to_date($data->timestamp)
            );
        }
        echo json_encode(array("data" => $result));
    }

    function view_categories() {
        $this->load->view("loans/categories");
    }

    function list_categories() {
        $list_data = $this->Loan_categories_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row_category($data);
        }
        echo json_encode(array("data" => $result));
    }
}

/* End of file Loans.php */
/* Location: ./application/controllers/Loans.php */