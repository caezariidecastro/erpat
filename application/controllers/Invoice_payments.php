<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Invoice_payments extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->init_permission_checker("invoice");
        $this->load->model("Accounts_model");
        $this->load->model("Account_transactions_model");
        $this->load->model("Invoices_model");
        $this->load->model("Invoice_payments_model");
        $this->load->model("Clients_model");
        $this->load->model("Payment_methods_model");
    }

    protected function _get_accounts_dropdown_data() {
        $accounts = $this->Accounts_model->get_all()->result();
        $accounts_dropdown = array('' => '-');

        foreach ($accounts as $group) {
            $accounts_dropdown[$group->id] = $group->name;
        }
        return $accounts_dropdown;
    }

    /* load invoice list view */

    function index() {
        if ($this->login_user->user_type === "staff") {
            $view_data['payment_method_dropdown'] = $this->get_payment_method_dropdown();
            $view_data["currencies_dropdown"] = $this->_get_currencies_dropdown();
            $view_data["projects_dropdown"] = $this->_get_projects_dropdown_for_income_and_epxenses("payments");
            $this->template->rander("invoices/payment_received", $view_data);
        } else {
            $view_data["client_info"] = $this->Clients_model->get_one($this->login_user->client_id);
            $view_data['client_id'] = $this->login_user->client_id;
            $view_data['page_type'] = "full";
            $this->template->rander("clients/payments/index", $view_data);
        }
    }

    function get_payment_method_dropdown() {
        $this->access_only_team_members();

        $payment_methods = $this->Payment_methods_model->get_all_where(array("deleted" => 0))->result();

        $payment_method_dropdown = array(array("id" => "", "text" => "- " . lang("payment_methods") . " -"));
        foreach ($payment_methods as $value) {
            $payment_method_dropdown[] = array("id" => $value->id, "text" => $value->title);
        }

        return json_encode($payment_method_dropdown);
    }

    //load the payment list yearly view
    function yearly() {
        $this->load->view("invoices/yearly_payments");
    }

    //load custom payment list
    function custom() {
        $this->load->view("invoices/custom_payments_list");
    }

    /* load payment modal */

    function payment_modal_form() {
        $this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric",
            "invoice_id" => "numeric"
        ));

        $view_data['model_info'] = $this->Invoice_payments_model->get_one($this->input->post('id'));

        $invoice_id = $this->input->post('invoice_id') ? $this->input->post('invoice_id') : $view_data['model_info']->invoice_id;

        if (!$invoice_id) {
            //prepare invoices dropdown
            $invoices = $this->Invoices_model->get_invoices_dropdown_list()->result();
            $invoices_dropdown = array();

            foreach ($invoices as $invoice) {
                $invoices_dropdown[$invoice->id] = get_invoice_id($invoice->id);
            }

            $view_data['invoices_dropdown'] = array("" => "-") + $invoices_dropdown;
        }

        $view_data['accounts_dropdown'] = $this->_get_accounts_dropdown_data();
        $view_data['payment_methods_dropdown'] = $this->Payment_methods_model->get_dropdown_list(array("title"), "id", array("online_payable" => 0, "deleted" => 0));
        $view_data['invoice_id'] = $invoice_id;

        $this->load->view('invoices/payment_modal_form', $view_data);
    }

    /* add or edit a payment */

    function save_payment() {
        $this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "numeric",
            "invoice_id" => "required|numeric",
            "account_id" => "required|numeric",
            "invoice_payment_method_id" => "required|numeric",
            "invoice_payment_date" => "required",
            "invoice_payment_amount" => "required"
        ));

        $id = $this->input->post('id');
        $invoice_id = $this->input->post('invoice_id');
        $account_id = $this->input->post('account_id');
        $amount = $this->input->post('invoice_payment_amount');

        $invoice_payment_data = array(
            "invoice_id" => $invoice_id,
            "account_id" => $account_id,
            "payment_date" => $this->input->post('invoice_payment_date'),
            "payment_method_id" => $this->input->post('invoice_payment_method_id'),
            "note" => $this->input->post('invoice_payment_note'),
            "amount" => unformat_currency($amount),
            "created_at" => get_current_utc_time(),
            "created_by" => $this->login_user->id,
        );

        $invoice_payment_id = $this->Invoice_payments_model->save($invoice_payment_data, $id);

        if($id){
            $data = array(
                'account_id' => $account_id,
                'amount' => $amount,
                'reference' => $id
            );

            $this->Account_transactions_model->update_payment($id, $data);
        }
        else{
            $this->Account_transactions_model->add_payment($account_id, $amount, $invoice_payment_id);
        }

        if ($invoice_payment_id) {

            //As receiving payment for the invoice, we'll remove the 'draft' status from the invoice 
            $this->Invoices_model->update_invoice_status($invoice_id);

            if (!$id) { //show payment confirmation for new payments only
                log_notification("invoice_payment_confirmation", array("invoice_payment_id" => $invoice_payment_id, "invoice_id" => $invoice_id), "0");
            }
            //get payment data
            $options = array("id" => $invoice_payment_id);
            $item_info = $this->Invoice_payments_model->get_details($options)->row();
            echo json_encode(array("success" => true, "invoice_id" => $item_info->invoice_id, "data" => $this->_make_payment_row($item_info), "invoice_total_view" => $this->_get_invoice_total_view($item_info->invoice_id), 'id' => $invoice_payment_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    /* delete or undo a payment */

    function delete_payment() {
        $this->access_only_allowed_members();

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');

        if ($this->Invoice_payments_model->delete($id)) {
            $this->Account_transactions_model->delete_payment($id);
            $item_info = $this->Invoice_payments_model->get_one($id);
            echo json_encode(array("success" => true, "invoice_id" => $item_info->invoice_id, "invoice_total_view" => $this->_get_invoice_total_view($item_info->invoice_id), 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    /* list of invoice payments, prepared for datatable  */

    function payment_list_data($invoice_id = 0) {
        if (!$this->can_view_invoices()) {
            redirect("forbidden");
        }

        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $payment_method_id = $this->input->post('payment_method_id');
        $options = array(
            "start_date" => $start_date,
            "end_date" => $end_date,
            "invoice_id" => $invoice_id,
            "payment_method_id" => $payment_method_id,
            "currency" => $this->input->post("currency"),
            "project_id" => $this->input->post("project_id"),
        );

        $list_data = $this->Invoice_payments_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_payment_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    /* list of invoice payments, prepared for datatable  */

    function payment_list_data_of_client($client_id = 0) {
        if (!$this->can_view_invoices($client_id)) {
            redirect("forbidden");
        }

        $options = array("client_id" => $client_id);
        $list_data = $this->Invoice_payments_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_payment_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    /* list of invoice payments, prepared for datatable  */

    function payment_list_data_of_project($project_id = 0) {
        $options = array("project_id" => $project_id);

        $list_data = $this->Invoice_payments_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_payment_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    /* prepare a row of invoice payment list table */

    private function _make_payment_row($data) {
        $invoice_url = "";
        if (!$this->can_view_invoices($data->client_id)) {
            redirect("forbidden");
        }

        if ($this->login_user->user_type == "staff") {
            $invoice_url = anchor(get_uri("sales/Invoices/view/" . $data->invoice_id), get_invoice_id($data->invoice_id));
        } else {
            $invoice_url = anchor(get_uri("sales/Invoices/preview/" . $data->invoice_id), get_invoice_id($data->invoice_id));
        }
        return array(
            $invoice_url,
            $data->payment_date,
            format_to_date($data->payment_date, false),
            $data->payment_method_title,
            $data->note,
            to_currency($data->amount, $data->currency_symbol),
            modal_anchor(get_uri("invoice_payments/payment_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_payment'), "data-post-id" => $data->id, "data-post-invoice_id" => $data->invoice_id,))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("invoice_payments/delete_payment"), "data-action" => "delete-confirmation"))
        );
    }

    /* invoice total section */

    private function _get_invoice_total_view($invoice_id = 0) {
        $view_data["invoice_total_summary"] = $this->Invoices_model->get_invoice_total_summary($invoice_id);
        $view_data["invoice_id"] = $invoice_id;
        $view_data["can_edit_invoices"] = $this->can_edit_invoices();
        return $this->load->view('invoices/invoice_total_section', $view_data, true);
    }

    //load the expenses yearly chart view
    function yearly_chart() {
        $view_data["currencies_dropdown"] = $this->_get_currencies_dropdown();
        $this->load->view("invoices/yearly_payments_chart", $view_data);
    }

    function yearly_chart_data() {

        $months = array("january", "february", "march", "april", "may", "june", "july", "august", "september", "october", "november", "december");
        $result = array();

        $year = $this->input->post("year");
        if ($year) {
            $currency = $this->input->post("currency");
            $payments = $this->Invoice_payments_model->get_yearly_payments_chart($year, $currency);
            $values = array();
            foreach ($payments as $value) {
                $values[$value->month - 1] = $value->total; //in array the month january(1) = index(0)
            }

            foreach ($months as $key => $month) {
                $value = get_array_value($values, $key);
                $result[] = array(lang("short_" . $month), $value ? $value : 0);
            }

            echo json_encode(array("data" => $result, "currency_symbol" => $currency));
        }
    }

    function get_stripe_payment_intent_session() {
        $this->access_only_clients();
        $this->load->library("stripe");
        try {
            $session = $this->stripe->get_stripe_payment_intent_session($this->input->post("input_data"), $this->login_user->id);
            if ($session->id) {
                echo json_encode(array("success" => true, "session_id" => $session->id, "publishable_key" => $this->stripe->get_publishable_key()));
            } else {
                echo json_encode(array('success' => false, 'message' => lang('error_occurred')));
            }
        } catch (Exception $ex) {
            echo json_encode(array("success" => false, "message" => $ex->getMessage()));
        }
    }

}

/* End of file payments.php */
/* Location: ./application/controllers/payments.php */