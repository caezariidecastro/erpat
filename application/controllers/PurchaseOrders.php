<?php

use ParagonIE\Sodium\Core\Curve25519\Ge\P2;

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class PurchaseOrders extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Purchase_orders_model");
        $this->load->model("Vendors_model");
        $this->load->model("Inventory_item_entries_model");
        $this->load->model("Purchase_order_materials_model");
        $this->load->model("Purchase_order_budgets_model");
        $this->load->model("Accounts_model");
        $this->load->model("Account_transactions_model");
        $this->load->model("Expense_categories_model");
        $this->load->model("Expenses_model");
        $this->load->model("Payment_methods_model");
        $this->load->model("Email_templates_model");
    }

    protected function _get_vendor_dropdown_data($status = null) {
        $vendor = $this->Vendors_model->get_details(array("status" => $status))->result();
        $vendor_dropdown = array('' => '-');

        foreach ($vendor as $vendor) {
            $vendor_dropdown[$vendor->id] = $vendor->name;
        }
        return $vendor_dropdown;
    }

    protected function _get_account_dropdown_data() {
        $account = $this->Accounts_model->get_all()->result();
        $account_dropdown = array('' => '-');

        foreach ($account as $account) {
            $account_dropdown[$account->id] = $account->name;
        }
        return $account_dropdown;
    }

    protected function _get_vendor_select2_data() {
        $vendor = $this->Vendors_model->get_all()->result();
        $vendor_select2 = array(array("id" => "", "text" => "- ".lang("suppliers")." -"));

        foreach ($vendor as $vendor) {
            $vendor_select2[] = array("id" => $vendor->id, "text" => $vendor->name);
        }
        return $vendor_select2;
    }

    protected function _get_account_select2_data() {
        $account = $this->Accounts_model->get_all()->result();
        $account_select2 = array(array("id" => "", "text" => "- ".lang("accounts")." -"));

        foreach ($account as $account) {
            $account_select2[] = array("id" => $account->id, "text" => $account->name);
        }
        return $account_select2;
    }

    protected function _get_accounts_dropdown_data() {
        $accounts = $this->Accounts_model->get_all()->result();
        $accounts_dropdown = array('' => '-');

        foreach ($accounts as $group) {
            $accounts_dropdown[$group->id] = $group->name;
        }
        return $accounts_dropdown;
    }

    function index(){
        $view_data["vendor_select2"] = $this->_get_vendor_select2_data();
        $view_data["account_select2"] = $this->_get_account_select2_data();
        $this->template->rander("purchase_orders/index", $view_data);
    }

    function save() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');

        $purchase_order_data = array(
            "vendor_id" => $this->input->post('vendor_id'),
            "account_id" => $this->input->post('account_id'),
            "remarks" => $this->input->post('remarks'),
        );

        if(!$id){
            $purchase_order_data["created_on"] = date('Y-m-d H:i:s');
            $purchase_order_data["created_by"] = $this->login_user->id;
        }

        $purchase_order_id = $this->Purchase_orders_model->save($purchase_order_data, $id);
        if ($purchase_order_id) {
            $options = array("id" => $purchase_order_id);
            $purchase_order_info = $this->Purchase_orders_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $purchase_order_info->id, "data" => $this->_make_row($purchase_order_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function list_data(){
        $list_data = $this->Purchase_orders_model->get_details(array(
            "vendor_id" => $this->input->post("vendor_select2_filter"),
            "account_id" => $this->input->post("account_select2_filter"),
        ))->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_row($data) {
        $status = get_purchase_order_status_label($data->status);

        return array(
            anchor(get_uri("mes/PurchaseOrders/view/" . $data->id), get_purchase_order_id($data->id)),
            $data->account_name,
            get_supplier_contact_link($data->vendor_id, $data->vendor_name),
            number_with_decimal($data->amount),
            nl2br($data->remarks),
            $data->created_on,
            get_team_member_profile_link($data->created_by, $data->creator_name, array("target" => "_blank")),
            $status,
            modal_anchor(get_uri("mes/PurchaseOrders/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_purchase'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("mes/PurchaseOrders/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function save_material(){
        $id = $this->input->post('id');
        $purchase_id = $this->input->post('purchase_id');
        $vendor_id = $this->input->post('vendor_id');
        $rate = $this->input->post('rate');
        $quantity = $this->input->post('quantity');

        $material_data = array(
            "title" => $this->input->post('title'),
            "unit_type" => $this->input->post('unit_type'),
            "rate" => $rate,
            "quantity" => $quantity,
            "total" => trim($rate) * trim($quantity),
            "purchase_id" => $purchase_id,
            "material_id" => $this->input->post('material_id'),
            "material_inventory_id" => $this->input->post('material_inventory_id'),
        );

        $purchase_order_material_id = $this->Purchase_order_materials_model->save($material_data, $id);
        if ($purchase_order_material_id) {
            $options = array("id" => $purchase_order_material_id);
            $purchase_order_material_info = $this->Purchase_order_materials_model->get_details($options)->row();

            $status = $this->_update_purchase_status($purchase_id);
            $purchase_status = get_purchase_order_status_label($status);

            echo json_encode(array("success" => true, "id" => $purchase_order_material_info->id, "data" => $this->_material_make_row($purchase_order_material_info, $vendor_id, $purchase_id ), 'message' => lang('record_saved'), "purchase_status" => $purchase_status));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');

        $view_data['model_info'] = $this->Purchase_orders_model->get_one($id);
        $view_data["vendor_dropdown"] = $this->_get_vendor_dropdown_data($id ? "" : "active");
        $view_data["account_dropdown"] = $this->_get_account_dropdown_data();
        $view_data["reload"] = $this->input->post("reload");

        $this->load->view('purchase_orders/modal_form', $view_data);
    }

    function view($purchase_order_id = 0){
        if ($purchase_order_id) {
            $view_data = get_purchase_order_making_data($purchase_order_id);

            if ($view_data) {
                $this->template->rander("purchase_orders/view", $view_data);
            } else {
                show_404();
            }
        }
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));
        
        $id = $this->input->post('id');

        $purchase_info = $this->Purchase_orders_model->get_details(array("id" => $id))->row();
        
        if ($this->Purchase_orders_model->delete($id)) {
            $this->Expenses_model->delete($purchase_info->expense_id);
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    function delete_material() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));
        
        $id = $this->input->post('id');
        $purchase_id = $this->input->get('purchase_id');
        
        if ($this->Purchase_order_materials_model->delete($id)) {
            $this->_update_purchase_status($purchase_id);
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    private function _material_make_row($data, $vendor_id, $purchase_id) {
        $title = "<div class='item-row strong mb5'>$data->title</div>";

        return array(
            $title,
            $data->quantity . " " .  $data->unit_type,
            number_with_decimal($data->rate),
            number_with_decimal($data->total),
            modal_anchor(get_uri("mes/PurchaseOrders/material_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_purchase'), "data-post-id" => $data->id, "data-post-vendor_id" => $vendor_id, "data-post-purchase_id" => $purchase_id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("mes/PurchaseOrders/delete_material?purchase_id=".$purchase_id), "data-action" => "delete-confirmation", "data-reload-on-success" => "1"))
        );
    }

    function material_list_data($purchase_id = 0, $vendor_id = 0){
        if($purchase_id){
            $list_data = $this->Purchase_order_materials_model->get_details(array(
                "purchase_id" => $purchase_id
            ))->result();
            $result = array();
            foreach ($list_data as $data) {
                $result[] = $this->_material_make_row($data, $vendor_id, $purchase_id);
            }
            echo json_encode(array("data" => $result));
        }
    }

    function material_modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $vendor_id = $this->input->post("vendor_id");

        $view_data['material_dropdown'] = array("" => "-") + $this->Inventory_item_entries_model->get_dropdown_list(array("name"), "id", array("vendor" => $vendor_id));
        $view_data['model_info'] = $this->Purchase_order_materials_model->get_one($this->input->post('id'));
        $view_data["warehouse_dropdown"] = $this->get_warehouses_select2_data($view_data['model_info']->material_id);
        $view_data["purchase_id"] = $this->input->post("purchase_id");
        $view_data["vendor_id"] = $vendor_id;

        $this->load->view('purchase_orders/material_modal_form', $view_data);
    }

    function get_warehouses_select2_data($material_id = 0){
        $material_id = !$material_id ? $this->input->post("material_id") : $material_id;
        $warehouses = $this->Inventory_item_entries_model->get_details(array("material_id" => $material_id))->result();

        $warehouse_list = array(array("id" => "", "text" => "-"));
        foreach ($warehouses as $value) {
            $warehouse_list[] = array("id" => $value->id, "text" => $value->warehouse_name);
        }

        if($this->input->post("json")){
            echo json_encode($warehouse_list);
        }

        return $warehouse_list;
    }

    function delete_budget() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));
        
        $id = $this->input->post('id');
        $purchase_id = $this->input->get('purchase_id');
        
        if ($this->Purchase_order_budgets_model->delete($id)) {
            $this->Account_transactions_model->delete_purchase_order($id);
            $this->_update_purchase_status($purchase_id);
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    private function _get_purchase_order_status($purchase_id){
        $purchase_info = $this->Purchase_orders_model->get_one($purchase_id);
        $total_budget = $this->Purchase_order_budgets_model->get_purchase_total_budget($purchase_id);
        $total_material = $this->Purchase_order_materials_model->get_purchase_total_material($purchase_id);
        $status = $purchase_info->status;
        
        if($status != "cancelled"){ // draft|partially_budgeted|completed
            if($total_material > 0 && $total_budget > 0){
                if(round($total_material) <= round($total_budget)){
                    $status = "completed";
                }
                
                if(round($total_material) > round($total_budget)){
                    $status = "partially_budgeted";
                }
            }
            else{
                $status = "draft";
            }
        }

        return $status;
    }

    private function _budget_make_row($data, $purchase_id) {
        return array(
            get_team_member_profile_link($data->created_by, $data->creator_name, array("target" => "_blank")),
            $data->created_on,
            number_with_decimal($data->amount),
            modal_anchor(get_uri("mes/PurchaseOrders/budget_modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_budget'), "data-post-id" => $data->id, "data-post-purchase_id" => $purchase_id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("mes/PurchaseOrders/delete_budget?purchase_id=".$purchase_id), "data-action" => "delete-confirmation", "data-reload-on-success" => "1"))
        );
    }

    function budget_list_data($purchase_id = 0){
        if($purchase_id){
            $list_data = $this->Purchase_order_budgets_model->get_details(array(
                "purchase_id" => $purchase_id
            ))->result();
            $result = array();
            foreach ($list_data as $data) {
                $result[] = $this->_budget_make_row($data, $purchase_id);
            }
            echo json_encode(array("data" => $result));
        }
    }

    function budget_modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $purchase_id = $this->input->post("purchase_id");

        $view_data['model_info'] = $this->Purchase_order_budgets_model->get_one($this->input->post('id'));
        $view_data["purchase_id"] = $purchase_id;
        $view_data["account_id"] = $this->Purchase_orders_model->get_one($purchase_id)->account_id;

        $this->load->view('purchase_orders/budget_modal_form', $view_data);
    }

    function save_budget(){
        $id = $this->input->post('id');
        $purchase_id = $this->input->post('purchase_id');
        $account_id = $this->input->post("account_id");
        $amount = $this->input->post("amount");

        $budget_data = array(
            "amount" => $amount,
            "purchase_id" => $this->input->post("purchase_id"),
        );

        if(!$id){
            $budget_data["created_by"] = $this->login_user->id;
            $budget_data["created_on"] = get_my_local_time("Y-m-d H:i:s");
        }

        $purchase_order_budget_id = $this->Purchase_order_budgets_model->save($budget_data, $id);

        $this->save_purchase_transaction($account_id, $amount, $id, $purchase_order_budget_id);

        if ($purchase_order_budget_id) {
            $options = array("id" => $purchase_order_budget_id);
            $purchase_order_budget_info = $this->Purchase_order_budgets_model->get_details($options)->row();

            $status = $this->_update_purchase_status($purchase_id);
            $purchase_status = get_purchase_order_status_label($status);

            echo json_encode(array("success" => true, "id" => $purchase_order_budget_info->id, "data" => $this->_budget_make_row($purchase_order_budget_info, $purchase_id), 'message' => lang('record_saved'), "purchase_status" => $purchase_status));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    private function _activate_all_budget($purchase_id){
        $purchase_budgets = $this->Purchase_order_budgets_model->get_details(array("purchase_id" => $purchase_id))->result();

        foreach($purchase_budgets as $budget){
            $transaction_data["deleted"] = "0";
            $this->Account_transactions_model->update_purchase_order($budget->id, $transaction_data); 
        }
    }

    private function _delete_all_budget($purchase_id){
        $purchase_budgets = $this->Purchase_order_budgets_model->get_details(array("purchase_id" => $purchase_id))->result();

        foreach($purchase_budgets as $budget){
            $transaction_data["deleted"] = "1";
            $this->Account_transactions_model->update_purchase_order($budget->id, $transaction_data); 
        }
    }

    private function save_expense($account_id, $amount, $vendor_id, $purchase_id){
        $expense_category_info = $this->Expense_categories_model->get_details_by_title("Purchase")->row();

        $expense_data = array(
            "account_id" => $account_id,
            "category_id" => $expense_category_info->id,
            "amount" => $amount,
            "vendor_id" => $vendor_id,
            "expense_date" => get_my_local_time("Y-m-d"),
            "title" => $purchase_id
        );

        return $this->Expenses_model->save($expense_data);
    }

    private function save_purchase_transaction($account_id, $amount, $id, $saved_id){
        $transaction_data = array(
            'account_id' => $account_id,
            'amount' => $amount,
            'reference' => $saved_id
        );

        if(!$id){// Not yet inserted? Insert!
            $this->Account_transactions_model->add_purchase_order($account_id, $amount, $saved_id); 
        }
        else{
            $this->Account_transactions_model->update_purchase_order($saved_id, $transaction_data); 
        }  
    }

    function send_purchase_modal_form($purchase_id = 0) {
        if ($purchase_id) {
            $options = array("id" => $purchase_id);
            $purchase_info = $this->Purchase_orders_model->get_details($options)->row();
            $view_data['purchase_info'] = $purchase_info;

            $contacts_options = array("user_type" => "supplier", "vendor_id" => $purchase_info->vendor_id);
            $contacts = $this->Users_model->get_details($contacts_options)->result();

            $primary_contact_info = "";
            $contacts_dropdown = array();
            foreach ($contacts as $contact) {
                if ($contact->is_primary_contact) {
                    $primary_contact_info = $contact;
                    $contacts_dropdown[$contact->id] = $contact->first_name . " " . $contact->last_name . " (" . lang("primary_contact") . ")";
                }
            }

            foreach ($contacts as $contact) {
                if (!$contact->is_primary_contact) {
                    $contacts_dropdown[$contact->id] = $contact->first_name . " " . $contact->last_name;
                }
            }

            $view_data['contacts_dropdown'] = $contacts_dropdown;

            $template_data = $this->get_send_purchase_template($purchase_id, 0, "", $purchase_info, $primary_contact_info);
            $view_data['message'] = get_array_value($template_data, "message");
            $view_data['subject'] = get_array_value($template_data, "subject");

            $this->load->view('purchase_orders/send_purchase_modal_form', $view_data);
        } else {
            show_404();
        }
    }

    function get_send_purchase_template($purchase_id = 0, $contact_id = 0, $return_type = "", $purchase_info = "", $contact_info = "") {

        if (!$purchase_info) {
            $options = array("id" => $purchase_id);
            $purchase_info = $this->Purchase_orders_model->get_details($options)->row();
        }

        if (!$contact_info) {
            $contact_info = $this->Users_model->get_one($contact_id);
        }

        $email_template = $this->Email_templates_model->get_final_template("send_purchase_request");

        $parser_data["P_ID"] = $purchase_info->id;
        $parser_data["CONTACT_FIRST_NAME"] = $contact_info->first_name;
        $parser_data["PURCHASE_URL"] = get_uri("mes/PurchaseOrders/preview/" . $purchase_info->id);
        $parser_data['SIGNATURE'] = $email_template->signature;
        $parser_data["LOGO_URL"] = get_logo_url();

        $message = $this->parser->parse_string($email_template->message, $parser_data, TRUE);
        $subject = $email_template->subject;

        if ($return_type == "json") {
            echo json_encode(array("success" => true, "message_view" => $message));
        } else {
            return array(
                "message" => $message,
                "subject" => $subject
            );
        }
    }

    function upload_file() {
        upload_file_to_temp();
    }

    function validate_purchases_file() {
        return validate_post_file($this->input->post("file_name"));
    }

    function download_pdf($purchase_id = 0, $mode = "download") {
        if ($purchase_id) {
            $purchase_data = get_purchase_order_making_data($purchase_id);

            prepare_purchase_pdf($purchase_data, $mode);
        } else {
            show_404();
        }
    }

    function preview($purchase_id = 0, $show_close_preview = false) {
        if ($purchase_id) {
            $view_data = get_purchase_order_making_data($purchase_id);

            $view_data['purchase_preview'] = prepare_purchase_pdf($view_data, "html");

            //show a back button
            $view_data['show_close_preview'] = $show_close_preview && $this->login_user->user_type === "staff" ? true : false;

            $view_data['purchase_id'] = $purchase_id;
            $view_data['payment_methods'] = $this->Payment_methods_model->get_available_online_payment_methods();

            $this->load->library("paypal");
            $view_data['paypal_url'] = $this->paypal->get_paypal_url();

            $this->template->rander("purchase_orders/purchase_preview", $view_data);
        } else {
            show_404();
        }
    }

    function print_purchase($purchase_id = 0) {
        if ($purchase_id) {
            $view_data = get_purchase_order_making_data($purchase_id);

            $view_data['purchase_preview'] = prepare_purchase_pdf($view_data, "html");

            echo json_encode(array("success" => true, "print_view" => $this->load->view("purchase_orders/print_purchase", $view_data, true)));
        } else {
            echo json_encode(array("success" => false, lang('error_occurred')));
        }
    }

    function send_purchase() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $purchase_id = $this->input->post('id');

        $contact_id = $this->input->post('contact_id');
        $cc = $this->input->post('purchase_cc');

        $custom_bcc = $this->input->post('purchase_bcc');
        $subject = $this->input->post('subject');
        $message = decode_ajax_post_data($this->input->post('message'));

        $contact = $this->Users_model->get_one($contact_id);

        $purchase_data = get_purchase_order_making_data($purchase_id);
        $attachement_url = prepare_purchase_pdf($purchase_data, "send_email");

        $default_bcc = get_setting('send_bcc_to'); //get default settings
        $bcc_emails = "";

        if ($default_bcc && $custom_bcc) {
            $bcc_emails = $default_bcc . "," . $custom_bcc;
        } else if ($default_bcc) {
            $bcc_emails = $default_bcc;
        } else if ($custom_bcc) {
            $bcc_emails = $custom_bcc;
        }

        //add uploaded files
        $target_path = get_setting("timeline_file_path");
        $files_data = move_files_from_temp_dir_to_permanent_dir($target_path, "purchase");
        $attachments = prepare_attachment_of_files(get_setting("timeline_file_path"), $files_data);

        //add purchase pdf
        array_unshift($attachments, array("file_path" => $attachement_url));

        if (send_app_mail($contact->email, $subject, $message, array("attachments" => $attachments, "cc" => $cc, "bcc" => $bcc_emails))) {
            // change email status
            $last_email_sent_date = get_my_local_time();
            $status_data = array("last_email_sent_date" => $last_email_sent_date);
            if ($this->Purchase_orders_model->save($status_data, $purchase_id)) {
                echo json_encode(array('success' => true, 'message' => lang("purchase_sent_message"), "purchase_id" => $purchase_id, "last_email_sent_date" => lang("last_email_sent_date").": ".$last_email_sent_date));
            }

            // delete the temp purchase
            if (file_exists($attachement_url)) {
                unlink($attachement_url);
            }

            //delete attachments
            if ($files_data) {
                $files = unserialize($files_data);
                foreach ($files as $file) {
                    delete_app_files($target_path, array($file));
                }
            }
        } else {
            echo json_encode(array('success' => false, 'message' => lang('error_occurred')));
        }
    }

    private function _update_purchase_status($purchase_id){
        $purchase_order_data["status"] = $this->_get_purchase_order_status($purchase_id);
        $this->Purchase_orders_model->save($purchase_order_data, $purchase_id);

        $purchase_info = $this->Purchase_orders_model->get_details(array("id" => $purchase_id))->row();

        // Delete previous expense related to purchase (if there is)
        if($purchase_info->expense_id){
            $expense_data["deleted"] = "1";
            $this->Expenses_model->save($expense_data, $purchase_info->expense_id);
        }

        $purchase_data["expense_id"] = NULL;

        if($purchase_order_data["status"] == "completed"){
            $total_budget = $this->Purchase_order_budgets_model->get_purchase_total_budget($purchase_id);

            // Save expense_id on purchase
            $purchase_data["expense_id"] = $this->save_expense($purchase_info->account_id, $total_budget, $purchase_info->vendor_id, $purchase_id);

            $this->_activate_all_budget($purchase_id);
        }
        else{
            $this->_delete_all_budget($purchase_id);
        }
        
        $this->Purchase_orders_model->save($purchase_data, $purchase_id);

        return $purchase_order_data["status"];
    }

    function cancel_form(){
        $view_data["id"] = $this->input->post("id");
        $this->load->view('purchase_orders/cancel_form', $view_data);
    }

    function cancel(){
        $id = $this->input->post("id");
        $data = array(
            "status" => "cancelled",
            "cancelled_at" => get_my_local_time(),
            "cancelled_by" => $this->login_user->id
        );

        if($this->Purchase_orders_model->save($data, $id)){
            echo json_encode(array('success' => true, 'message' => lang("success")));
        }
        else{
            echo json_encode(array('success' => false, 'message' => lang('error_occurred')));
        }
    }

    function draft_form(){
        $view_data["id"] = $this->input->post("id");
        $this->load->view('purchase_orders/draft_form', $view_data);
    }

    function mark_as_draft(){
        $id = $this->input->post("id");
        $data = array(
            "status" => "draft",
            "cancelled_at" => NULL,
            "cancelled_by" => NULL
        );

        if($this->Purchase_orders_model->save($data, $id)){
            echo json_encode(array('success' => true, 'message' => lang("success")));
        }
        else{
            echo json_encode(array('success' => false, 'message' => lang('error_occurred')));
        }
    }
}