<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Expenses extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->init_permission_checker("expense");
        $this->access_only_allowed_members();
        $this->load->model("Accounts_model");
        $this->load->model("Account_transactions_model");
        $this->load->model("Expenses_model");
        $this->load->model("Expense_categories_model");
        $this->load->model("Expenses_payments_model");
        $this->load->model("Invoice_payments_model");
        $this->load->model("Projects_model");
        $this->load->model("Clients_model");
        $this->load->model("Taxes_model");
    }

    //load the expenses list view
    function index() {
        $this->with_module("expense");

        $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("expenses", $this->login_user->is_admin, $this->login_user->user_type);

        $view_data['categories_dropdown'] = $this->_get_categories_dropdown();
        $view_data['members_dropdown'] = $this->_get_team_members_dropdown();
        $view_data["projects_dropdown"] = $this->_get_projects_dropdown_for_income_and_epxenses("expenses");

        $view_data["can_edit_expenses"] = true; //TODO: Check if user can edit expense row.

        $this->template->rander("expenses/index", $view_data);
    }

    //get categories dropdown
    private function _get_categories_dropdown() {
        $categories = $this->Expense_categories_model->get_all_where(array("deleted" => 0), 0, 0, "title")->result();

        $categories_dropdown = array(array("id" => "", "text" => "- " . lang("category") . " -"));
        foreach ($categories as $category) {
            $categories_dropdown[] = array("id" => $category->id, "text" => $category->title);
        }

        return json_encode($categories_dropdown);
    }

    //get team members dropdown
    private function _get_team_members_dropdown() {
        $team_members = $this->Users_model->get_all_where(array("deleted" => 0, "user_type" => "staff"), 0, 0, "first_name")->result();

        $members_dropdown = array(array("id" => "", "text" => "- " . lang("users") . " -"));
        foreach ($team_members as $team_member) {
            $members_dropdown[] = array("id" => $team_member->id, "text" => $team_member->first_name . " " . $team_member->last_name);
        }

        return json_encode($members_dropdown);
    }

    protected function _get_accounts_dropdown_data() {
        $accounts = $this->Accounts_model->get_all()->result();
        $accounts_dropdown = array('' => '-');

        foreach ($accounts as $group) {
            $accounts_dropdown[$group->id] = $group->name;
        }
        return $accounts_dropdown;
    }

    //prepare options dropdown for invoices list
    private function _make_options_dropdown($data, $recurring = false) {
        $expense_id = $data->id;

        $edit = '<li role="presentation">' . modal_anchor(get_uri("expenses/modal_form"), "<i class='fa fa-pencil'></i> " . lang('edit'), array("title" => lang('edit_invoice'), "data-post-id" => $expense_id)) . '</li>';

        $delete = '<li role="presentation">' . js_anchor("<i class='fa fa-times fa-fw'></i>" . lang('delete'), array('title' => lang('delete_invoice'), "class" => "delete", "data-id" => $expense_id, "data-action-url" => get_uri("expenses/delete"), "data-action" => "delete-confirmation")) . '</li>';

        $mark_unpaid = "";
        $add_payment = "";

        if($data->status != "cancelled" && !$recurring) {
            if((float)$data->payment_received < (float)$data->amount) {
                $add_payment = '<li role="presentation">' . modal_anchor(get_uri("expenses_payments/payment_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_payment'), array("title" => lang('add_payment'), "data-post-expense_id" => $expense_id)) . '</li>';
            } 

            if($data->status != "not_paid" && (float)$data->payment_received == 0 && (float)$data->payment_received < (float)$data->amount) {
                $mark_unpaid = '<li role="presentation">' . ajax_anchor(get_uri("expenses/mark_as_unpaid/".$expense_id), "<i class='fa fa-check'></i> " . lang('mark_invoice_as_not_paid'), array("data-reload-on-success" => "1")) . '</li>';
            } 
        }

        return '
                <span class="dropdown inline-block">
                    <button class="btn btn-default dropdown-toggle  mt0 mb0" type="button" data-toggle="dropdown" aria-expanded="true">
                        <i class="fa fa-cogs"></i>&nbsp;
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu pull-right" role="menu">' . $edit . $delete . $mark_unpaid . $add_payment . '</ul>
                </span>';
    }

    //load the expenses list yearly view
    function yearly() {
        $this->load->view("expenses/yearly_expenses");
    }

    //load custom expenses list
    function custom() {
        $this->load->view("expenses/custom_expenses");
    }

    //load the recurring view of expense list 
    function recurring() {
        $this->load->view("expenses/recurring_expenses_list");
    }

    //load the add/edit expense form
    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $client_id = $this->input->post('client_id');

        $model_info = $this->Expenses_model->get_one($this->input->post('id'));
        $view_data['categories_dropdown'] = $this->Expense_categories_model->get_dropdown_list(array("title"));

        $team_members = $this->Users_model->get_all_where(array("deleted" => 0, "user_type" => "staff"))->result();
        $members_dropdown = array();

        foreach ($team_members as $team_member) {
            $members_dropdown[$team_member->id] = $team_member->first_name . " " . $team_member->last_name;
        }

        $view_data['members_dropdown'] = array("0" => "-") + $members_dropdown;
        $view_data['clients_dropdown'] = array("" => "-") + $this->Clients_model->get_dropdown_list(array("company_name"), "id", array("is_lead" => 0));
        $view_data['projects_dropdown'] = array("0" => "-") + $this->Projects_model->get_dropdown_list(array("title"));
        $view_data['taxes_dropdown'] = array("" => "-") + $this->Taxes_model->get_dropdown_list(array("title"));

        $model_info->project_id = $model_info->project_id ? $model_info->project_id : $this->input->post('project_id');
        $model_info->user_id = $model_info->user_id ? $model_info->user_id : $this->input->post('user_id');

        $view_data['model_info'] = $model_info;
        $view_data['client_id'] = $client_id;

        $view_data['can_access_expenses'] = $this->can_access_expenses();
        $view_data['can_access_clients'] = $this->can_access_clients();
        $view_data['accounts_dropdown'] = $this->_get_accounts_dropdown_data();

        $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("expenses", $view_data['model_info']->id, $this->login_user->is_admin, $this->login_user->user_type)->result();
        $this->load->view('expenses/modal_form', $view_data);
    }

    //save an expense
    function save() {
        validate_submitted_data(array(
            "id" => "numeric",
            "expense_date" => "required",
            "category_id" => "required",
            "account_id" => "required",
            "amount" => "required"
        ));

        $id = $this->input->post('id');
        $account_id = $this->input->post('account_id');
        $amount = $this->input->post('amount');

        $target_path = get_setting("timeline_file_path");
        $files_data = move_files_from_temp_dir_to_permanent_dir($target_path, "expense");
        $new_files = unserialize($files_data);

        $recurring = $this->input->post('recurring') ? 1 : 0;
        $expense_date = $this->input->post('expense_date');
        $due_date = $this->input->post('due_date');
        $repeat_every = $this->input->post('repeat_every');
        $repeat_type = $this->input->post('repeat_type');
        $no_of_cycles = $this->input->post('no_of_cycles');

        $data = array(
            "expense_date" => $expense_date,
            "due_date" => $due_date,
            "title" => $this->input->post('title'),
            "description" => $this->input->post('description'),
            "account_id" => $account_id,
            "category_id" => $this->input->post('category_id'),
            "amount" => unformat_currency($amount),
            "client_id" => $this->input->post('expense_client_id'),
            "project_id" => $this->input->post('expense_project_id'),
            "user_id" => $this->input->post('expense_user_id'),
            "tax_id" => $this->input->post('tax_id') ? $this->input->post('tax_id') : 0,
            "tax_id2" => $this->input->post('tax_id2') ? $this->input->post('tax_id2') : 0,
            "recurring" => $recurring,
            "repeat_every" => $repeat_every ? $repeat_every : 0,
            "repeat_type" => $repeat_type ? $repeat_type : NULL,
            "no_of_cycles" => $no_of_cycles ? $no_of_cycles : 0,
        );

        $expense_info = $this->Expenses_model->get_one($id);

        //is editing? update the files if required
        if ($id) {
            $timeline_file_path = get_setting("timeline_file_path");
            $new_files = update_saved_files($timeline_file_path, $expense_info->files, $new_files);
        }

        $data["files"] = serialize($new_files);

        if ($recurring) {
            //set next recurring date for recurring expenses

            if ($id) {
                //update
                if ($this->input->post('next_recurring_date')) { //submitted any recurring date? set it.
                    $data['next_recurring_date'] = $this->input->post('next_recurring_date');
                } else {
                    //re-calculate the next recurring date, if any recurring fields has changed.
                    if ($expense_info->recurring != $data['recurring'] || $expense_info->repeat_every != $data['repeat_every'] || $expense_info->repeat_type != $data['repeat_type'] || $expense_info->expense_date != $data['expense_date']) {
                        $data['next_recurring_date'] = add_period_to_date($expense_date, $repeat_every, $repeat_type);
                    }
                }
            } else {
                //insert new
                $data['next_recurring_date'] = add_period_to_date($expense_date, $repeat_every, $repeat_type);
            }


            //recurring date must have to set a future date
            if (get_array_value($data, "next_recurring_date") && get_today_date() >= $data['next_recurring_date']) {
                echo json_encode(array("success" => false, 'message' => lang('past_recurring_date_error_message_title'), 'next_recurring_date_error' => lang('past_recurring_date_error_message'), "next_recurring_date_value" => $data['next_recurring_date']));
                return false;
            }
        }

        $save_id = $this->Expenses_model->save($data, $id);

        if($id){
            $data = array(
                'account_id' => $account_id,
                'amount' => $amount,
                'reference' => $id
            );

            $this->Account_transactions_model->update_expense($id, $data);
        }

        if ($save_id) {
            save_custom_fields("expenses", $save_id, $this->login_user->is_admin, $this->login_user->user_type);

            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    //delete/undo an expense
    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        $expense_info = $this->Expenses_model->get_one($id);

        if ($this->Expenses_model->delete($id)) {
            //delete the files
            $file_path = get_setting("timeline_file_path");
            if ($expense_info->files) {
                $files = unserialize($expense_info->files);

                foreach ($files as $file) {
                    delete_app_files($file_path, array($file));
                }
            }

            $this->Account_transactions_model->delete_expense($id);

            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    //get the expnese list data
    function list_data($recurring = false) {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $category_id = $this->input->post('category_id');
        $project_id = $this->input->post('project_id');
        $user_id = $this->input->post('user_id');

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("expenses", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array("start_date" => $start_date, "end_date" => $end_date, "category_id" => $category_id, "project_id" => $project_id, "user_id" => $user_id, "custom_fields" => $custom_fields, "recurring" => $recurring);
        $list_data = $this->Expenses_model->get_details($options)->result();

        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields, $recurring=="recurring"?true:false);
        }
        echo json_encode(array("data" => $result));
    }

    //get a row of expnese list
    private function _row_data($id) {
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("expenses", $this->login_user->is_admin, $this->login_user->user_type);
        $options = array("id" => $id, "custom_fields" => $custom_fields);
        $data = $this->Expenses_model->get_details($options)->row();
        return $this->_make_row($data, $custom_fields);
    }

    private function get_expense_balance($amount = 0, $expense_id = 0) {
        $total_payments = $this->Expenses_payments_model->get_total_payments($expense_id);
        return (float)$amount - (float)$total_payments;
    }

    //prepare a row of expnese list
    private function _make_row($data, $custom_fields, $recurring = false) {

        $description = $data->description;
        $title = $data->title;
        if ($data->linked_client_name) {
            if ($description) {
                $description .= "<br />";
            }
            $description .= lang("client") . ": " . get_client_contact_profile_link($data->client_id, $data->linked_client_name);
        }

        if ($data->project_title) {
            if ($description) {
                $description .= "<br /> ";
            }
            $description .= lang("project") . ": " . anchor(get_uri("pms/projects/view/" . $data->project_id), $data->project_title);
        }

        if ($data->linked_user_name) {
            if ($description) {
                $description .= "<br /> ";
            }
            $description .= lang("team_member") . ": " . get_team_member_profile_link($data->user_id, $data->linked_user_name);
        }

        if ($data->vendor_name) {
            if ($description) {
                $description .= "<br /> ";
            }
            $description .= lang("supplier") . ": " . get_supplier_contact_link($data->vendor_id, $data->vendor_name);
            $title = $title ? anchor(get_uri("mes/purchases/view/" . $data->title), get_purchase_order_id($data->title)) : "";
        }

        if ($data->recurring) {
            //show recurring information
            $recurring_stopped = false;
            $recurring_cycle_class = "";
            if ($data->no_of_cycles_completed > 0 && $data->no_of_cycles_completed == $data->no_of_cycles) {
                $recurring_cycle_class = "text-danger";
                $recurring_stopped = true;
            }

            $cycles = $data->no_of_cycles_completed . "/" . $data->no_of_cycles;
            if (!$data->no_of_cycles) { //if not no of cycles, so it's infinity
                $cycles = $data->no_of_cycles_completed . "/&#8734;";
            }

            if ($description) {
                $description .= "<br /> ";
            }

            $description .= lang("repeat_every") . ": " . $data->repeat_every . " " . lang("interval_" . $data->repeat_type);
            $description .= "<br /> ";
            $description .= "<span class='$recurring_cycle_class'>" . lang("cycles") . ": " . $cycles . "</span>";

            if (!$recurring_stopped && (int) $data->next_recurring_date) {
                $description .= "<br /> ";
                $description .= lang("next_recurring_date") . ": " . format_to_date($data->next_recurring_date, false);
            }
        } else {
            if ($data->recurring_expense_id) {
                $description .= "<br /> ";
                $description .= lang("generated_by") . ": " . lang("expense") ." #". $data->recurring_expense_id;
            }
        }

        $files_link = "";
        if ($data->files) {
            $files = unserialize($data->files);
            if (count($files)) {
                foreach ($files as $key => $value) {
                    $file_name = get_array_value($value, "file_name");
                    $link = " fa fa-" . get_file_icon(strtolower(pathinfo($file_name, PATHINFO_EXTENSION)));
                    $files_link .= js_anchor(" ", array('title' => "", "data-toggle" => "app-modal", "data-sidebar" => "0", "class" => "pull-left font-22 mr10 $link", "title" => remove_file_prefix($file_name), "data-url" => get_uri("fas/expenses/file_preview/" . $data->id . "/" . $key)));
                }
            }
        }

        $tax = 0;
        $tax2 = 0;
        if ($data->tax_percentage) {
            $tax = $data->amount * ($data->tax_percentage / 100);
        }
        if ($data->tax_percentage2) {
            $tax2 = $data->amount * ($data->tax_percentage2 / 100);
        }

        $invoice_url = modal_anchor(get_uri("fas/expenses/expense_details"), get_expense_id($data->id), array("title" => lang("expense_details"), "data-post-id" => $data->id));
        $invoice_url = $data->recurring ? get_expense_id($data->id) : $invoice_url;
        $invoice_url = format_to_date($data->expense_date, false)." - ".$invoice_url;
        $payable_balance = $this->get_expense_balance($data->amount, $data->id);
        $payable_payment = $data->amount - $payable_balance;

        $row_data = array(
            $data->id,
            $invoice_url,
            $data->category_title . " #".$data->category_id,
            $title,
            $description,
            $files_link,
            to_currency($data->amount),
            to_currency($tax),
            to_currency($data->amount + $tax + $tax2),
            to_currency($payable_payment),
            to_currency($payable_balance),
            get_expense_status_label($data)
        );

        foreach ($custom_fields as $field) {
            $cf_id = "cfv_" . $field->id;
            $row_data[] = $this->load->view("custom_fields/output_" . $field->field_type, array("value" => $data->$cf_id), true);
        }

        $row_data[] = $this->_make_options_dropdown($data, $recurring);
        
        return $row_data;
    }

    function file_preview($id = "", $key = "") {
        if ($id) {
            $expense_info = $this->Expenses_model->get_one($id);
            $files = unserialize($expense_info->files);
            $file = get_array_value($files, $key);

            $file_name = get_array_value($file, "file_name");
            $file_id = get_array_value($file, "file_id");
            $service_type = get_array_value($file, "service_type");

            $view_data["file_url"] = get_source_url_of_file($file, get_setting("timeline_file_path"));
            $view_data["is_image_file"] = is_image_file($file_name);
            $view_data["is_google_preview_available"] = is_google_preview_available($file_name);
            $view_data["is_viewable_video_file"] = is_viewable_video_file($file_name);
            $view_data["is_google_drive_file"] = ($file_id && $service_type == "google") ? true : false;

            $this->load->view("expenses/file_preview", $view_data);
        } else {
            show_404();
        }
    }

    /* upload a file */

    function upload_file() {
        upload_file_to_temp();
    }

    /* check valid file for ticket */

    function validate_expense_file() {
        return validate_post_file($this->input->post("file_name"));
    }

    //load the expenses yearly chart view
    function yearly_chart() {
        $this->load->view("expenses/yearly_chart");
    }

    function yearly_chart_data() {

        $months = array("january", "february", "march", "april", "may", "june", "july", "august", "september", "october", "november", "december");
        $result = array();

        $year = $this->input->post("year");
        if ($year) {
            $expenses = $this->Expenses_model->get_yearly_expenses_chart($year);
            $values = array();
            foreach ($expenses as $value) {
                $values[$value->month - 1] = $value->payment; //in array the month january(1) = index(0)
            }

            foreach ($months as $key => $month) {
                $value = get_array_value($values, $key);
                $result[] = array(lang("short_" . $month), $value ? $value : 0);
            }

            echo json_encode(array("data" => $result));
        }
    }

    function cash_flow_comparison() {
        $view_data["projects_dropdown"] = $this->_get_projects_dropdown_for_income_and_epxenses();
        $this->template->rander("expenses/summary/cash_flows", $view_data);
    }

    function cash_flow_comparison_data() {

        $year = $this->input->post("year");
        $project_id = $this->input->post("project_id");

        if ($year) {
            $expenses_data = $this->Expenses_model->get_yearly_expenses_chart($year, $project_id);
            $payments_data = $this->Invoice_payments_model->get_yearly_payments_chart($year, "", $project_id);

            $payments = array();
            $payments_array = array();

            $expenses = array();
            $expenses_array = array();

            for ($i = 1; $i <= 12; $i++) {
                $payments[$i] = 0;
                $expenses[$i] = 0;
            }

            foreach ($payments_data as $payment) {
                $payments[$payment->month] = $payment->total;
            }
            foreach ($expenses_data as $expense) {
                $expenses[$expense->month] = $expense->total;
            }

            foreach ($payments as $key => $payment) {
                $payments_array[] = array($key, $payment);
            }

            foreach ($expenses as $key => $expense) {
                $expenses_array[] = array($key, $expense);
            }

            echo json_encode(array("income" => $payments_array, "expenses" => $expenses_array));
        }
    }

    function income_statements() {
        $view_data["projects_dropdown"] = $this->_get_projects_dropdown_for_income_and_epxenses();
        $this->load->view("expenses/summary/income_statements", $view_data);
    }

    function income_statements_list_data() {

        $year = explode("-", $this->input->post("start_date"));
        $project_id = $this->input->post("project_id");

        if ($year) {
            $expenses_data = $this->Expenses_model->get_yearly_expenses_chart($year[0], $project_id);
            $payments_data = $this->Invoice_payments_model->get_yearly_payments_chart($year[0], "", $project_id);

            $payments = array();
            $expenses = array();
            $balances = array();

            for ($i = 1; $i <= 12; $i++) {
                $payments[$i] = 0;
                $expenses[$i] = 0;
            }

            foreach ($payments_data as $payment) {
                $payments[$payment->month] = $payment->total;
            }
            foreach ($expenses_data as $expense) {
                $expenses[$expense->month] = $expense->payment;
                $balances[$expense->month] = (float)$expense->total - (float)$expense->payment;
            }

            //get the list of summary
            $result = array();
            for ($i = 1; $i <= 12; $i++) {
                $result[] = $this->_row_data_of_summary($i, $payments[$i], $expenses[$i], $balances[$i]);
            }

            echo json_encode(array("data" => $result));
        }
    }

    //get the row of summary
    private function _row_data_of_summary($month_index, $payments, $expenses, $payables) {
        //get the month name
        $month_array = array(" ", "january", "february", "march", "april", "may", "june", "july", "august", "september", "october", "november", "december");

        $month = get_array_value($month_array, $month_index);

        $month_name = lang($month);
        $profit = $payments - $expenses;

        return array(
            $month_index,
            $month_name,
            to_currency($payments),
            to_currency($expenses),
            to_currency($profit),
            to_currency($payables)
        );
    }

    /* list of expense of a specific client, prepared for datatable  */

    function expense_list_data_of_client($client_id) {
        $this->access_only_team_members();

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("expenses", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array("client_id" => $client_id);

        $list_data = $this->Expenses_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields);
        }
        echo json_encode(array("data" => $result));
    }

    private function can_access_clients() {
        $permissions = $this->login_user->permissions;

        if (get_array_value($permissions, "client")) {
            return true;
        } else {
            return false;
        }
    }

    function expense_details() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $expense_id = $this->input->post('id');
        $options = array("id" => $expense_id);
        $info = $this->Expenses_model->get_details($options)->row();
        if (!$info) {
            show_404();
        }

        $view_data["expense_info"] = $info;
        $view_data['custom_fields_list'] = $this->Custom_fields_model->get_combined_details("expenses", $expense_id, $this->login_user->is_admin, $this->login_user->user_type)->result();

        $this->load->view("expenses/expense_details", $view_data);
    }

    function mark_as_unpaid($expense_id = 0) {
        if($expense_id) {
            $result = $this->update_expense_status($expense_id, "not_paid", true);
            echo $result?lang('error_occured'):lang('error_occured');
        } else {
            echo lang('error_occured');
        }
    }

    function update_expense_status($expense_id = 0, $status = "", $return = false) {

        if ($expense_id && $status) {
            //change the draft status of the expense
            $this->Expenses_model->update_expense_status($expense_id, $status);

            //save extra information for cancellation
            if ($status == "cancelled") {
                $data = array(
                    "cancelled_at" => get_current_utc_time(),
                    "cancelled_by" => $this->login_user->id
                );

                $this->Expenses_model->save($data, $expense_id);
            }

            if($return) {
                return array("success" => true, 'message' => lang('record_saved'));
            } else {
                echo json_encode(array("success" => true, 'message' => lang('record_saved')));
            }
        }

        return "";
    }

}

/* End of file expenses.php */
/* Location: ./application/controllers/expenses.php */