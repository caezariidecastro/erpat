<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class PurchaseReturns extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Purchase_order_returns_model");
        $this->load->model("Purchase_orders_model");
        $this->load->model("Vendors_model");
        $this->load->model("Purchase_order_materials_model");
        $this->load->model("Purchase_order_return_materials_model");
        $this->load->model("Purchase_order_budgets_model");
        $this->load->model("Account_transactions_model");
        $this->load->model("Payment_methods_model");
        $this->load->model("Email_templates_model");
    }

    protected function _get_vendor_select2_data() {
        $vendor = $this->Vendors_model->get_all()->result();
        $vendor_select2 = array(array("id" => "", "text" => "- ".lang("suppliers")." -"));

        foreach ($vendor as $vendor) {
            $vendor_select2[] = array("id" => $vendor->id, "text" => $vendor->name);
        }
        return $vendor_select2;
    }

    protected function _get_purchase_dropdown_data() {
        $purchase = $this->Purchase_orders_model->get_details(array("status" => "completed"))->result();
        $purchase_dropdown = array("" => " - ");

        foreach ($purchase as $purchase) {
            $purchase_dropdown[$purchase->id] = lang("purchase")." #".$purchase->id;
        }
        return $purchase_dropdown;
    }

    protected function _get_purchase_materials_dropdown_data($purchase_id) {
        $materials = $this->Purchase_order_materials_model->get_purchase_materials($purchase_id)->result();
        $materials_dropdown = array("" => "-");

        foreach ($materials as $material) {
            $materials_dropdown[$material->id] = $material->title." (".lang("purchased").": ".$material->quantity.")";
        }
        return $materials_dropdown;
    }

    function index(){
        $view_data["vendor_select2"] = $this->_get_vendor_select2_data();
        $this->template->rander("purchase_returns/index", $view_data);
    }

    function list_data(){
        $list_data = $this->Purchase_order_returns_model->get_details(array(
            'vendor_id' => $this->input->post('vendor_select2_filter'),
        ))->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_row($data) {
        return array(
            anchor(get_uri("mes/PurchaseReturns/view/". $data->id), get_purchase_return_id($data->id)),
            anchor(get_uri("mes/PurchaseOrders/view/". $data->purchase_id), get_purchase_order_id($data->purchase_id)),
            get_supplier_contact_link($data->vendor_id, $data->vendor_name),
            number_with_decimal($data->amount),
            nl2br($data->remarks),
            $data->created_on,
            get_team_member_profile_link($data->created_by, $data->creator_name, array("target" => "_blank")),
            modal_anchor(get_uri("mes/PurchaseReturns/modal_form"), "<i class='fa fa-pencil'></i> ", array( "title" => lang('edit_return'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("mes/PurchaseReturns/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function save() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');
        $purchase_id = $this->input->post('purchase_id');

        $return_data = array(
            "purchase_id" => $purchase_id,
            "remarks" => $this->input->post('remarks'),
            "created_on" => get_my_local_time(),
            "created_by" => $this->login_user->id,
        );

        $return_info = $this->Purchase_order_returns_model->get_one($id);
        $is_purchase_has_return = $this->Purchase_order_returns_model->is_purchase_has_return($purchase_id);
        $update_same_return = $is_purchase_has_return && $return_info->purchase_id == $purchase_id;

        if(!$is_purchase_has_return || $update_same_return){
            if($return_info->purchase_id && $return_info->purchase_id != $purchase_id){ // if user somehow wants to change purchase id but there are existing data related to return
                // Delete related data (account transactions and return materials)
                $this->_delete_all_returns_related($id);
                $return_data["status"] = "draft";
            }

            $return_id = $this->Purchase_order_returns_model->save($return_data, $id);

            if ($return_id) {
                $options = array("id" => $return_id);
                $saved_return_info = $this->Purchase_order_returns_model->get_details($options)->row();
                echo json_encode(array("success" => true, "id" => $saved_return_info->id, "data" => $this->_make_row($saved_return_info), 'message' => lang('record_saved')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
            }
        }
        else{
            echo json_encode(array("success" => false, 'message' => lang("purchase")." #".$purchase_id. " " .lang('has_return_error')));
        }
    }

    private function _delete_all_returns_related($return_id){
        $return_materials = $this->Purchase_order_return_materials_model->get_details(array("purchase_order_return_id" => $return_id))->result();

        foreach($return_materials as $material){
            // Delete account transaction
            $transaction_data["deleted"] = "1";
            $this->Account_transactions_model->update_purchase_return($material->id, $transaction_data);
            
            // Delete return material
            $return_material_data["deleted"] = "1";
            $this->Purchase_order_return_materials_model->save($return_material_data, $material->id);
        }
    }

    function add_material_modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $purchase_id = $this->input->post("purchase_id");

        $view_data['model_info'] = $this->Purchase_order_return_materials_model->get_one($this->input->post('id'));
        $view_data['purchase_order_material_id'] = $this->input->post('purchase_order_material_id');
        $view_data['purchase_order_return_id'] = $this->input->post('purchase_order_return_id');
        $view_data["purchase_order_materials_dropdown"] = $this->_get_purchase_materials_dropdown_data($purchase_id);
        $view_data["purchase_id"] = $purchase_id;

        $this->load->view('purchase_returns/add_material_modal_form', $view_data);
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Purchase_order_returns_model->get_one($this->input->post('id'));
        $view_data["purchases_dropdown"] = $this->_get_purchase_dropdown_data();
        $view_data["reload"] = $this->input->post("reload");

        $this->load->view('purchase_returns/modal_form', $view_data);
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));
        
        $id = $this->input->post('id');
        
        if ($this->Purchase_order_returns_model->delete($id)) {
            $returned_materials = $this->Purchase_order_returns_model->get_purchase_return_materials($id)->result();
            foreach ($returned_materials as $returned_material) {
                $this->Account_transactions_model->delete_purchase_return($returned_material->id);
                $this->Purchase_order_return_materials_model->delete($returned_material->id);
            }
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    private function _make_material_row($data) {
        return array(
            $data->material_name,
            number_with_decimal($data->quantity),
            number_with_decimal($data->rate),
            number_with_decimal($data->total),
            nl2br($data->remarks),
            modal_anchor(get_uri("mes/PurchaseReturns/add_material_modal_form"), "<i class='fa fa-pencil'></i> ", array( "title" => lang('edit_return_material'), "data-post-id" => $data->id, "data-post-purchase_order_material_id" => $data->purchase_order_material_id, "data-post-purchase_id" => $data->purchase_id, "data-post-purchase_order_return_id" => $data->purchase_order_return_id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("mes/PurchaseReturns/delete_material"), "data-action" => "delete-confirmation"))
        );
    }

    function material_list_data($purchase_order_return_id){
        $result = array();

        if($purchase_order_return_id){
            $list_data = $this->Purchase_order_returns_model->get_purchase_return_materials($purchase_order_return_id)->result();
            foreach ($list_data as $data) {
                $result[] = $this->_make_material_row($data);
            }
        }

        echo json_encode(array("data" => $result));
    }

    function save_material() {
        $id = $this->input->post('id');
        $purchase_id = $this->input->post('purchase_id');
        $purchase_order_return_id = $this->input->post('purchase_order_return_id');
        $purchase_order_material_id = $this->input->post('purchase_order_material_id');
        $quantity = $this->input->post('quantity');

        $return_data = array(
            "purchase_order_return_id" => $purchase_order_return_id,
            "purchase_order_material_id" => $purchase_order_material_id,
            "quantity" => $quantity,
            "remarks" => $this->input->post('remarks')
        );

        if(!$id){
            $return_data["created_on"] = get_my_local_time();
            $return_data["created_by"] = $this->login_user->id;
        }

        $return_material = $this->Purchase_order_return_materials_model->get_one($id);
        $is_material_has_return = $this->Purchase_order_return_materials_model->is_material_has_return($purchase_order_material_id);
        $update_same_material = $is_material_has_return && $return_material->purchase_order_material_id == $purchase_order_material_id;

        if(!$is_material_has_return || ($update_same_material)){
            $amount = $this->Purchase_order_materials_model->get_one($purchase_order_material_id)->rate * $quantity;
            $account_id = $this->Purchase_orders_model->get_details(array("purchase_id" => $purchase_id))->row()->account_id;

            $return_data["total"] = $amount;
            $return_id = $this->Purchase_order_return_materials_model->save($return_data, $id);

            if(!$is_material_has_return){
                $this->Account_transactions_model->add_purchase_return($account_id, $amount, $return_id);
            }
            else{
                $transaction_data = array(
                    "account_id" => $account_id,
                    "amount" => $amount,
                    "reference" => $return_id
                );

                $this->Account_transactions_model->update_purchase_return($return_material->id, $transaction_data); 
            }

            if ($return_id) {
                $options = array("id" => $return_id);
                $return_info = $this->Purchase_order_returns_model->get_purchase_return_materials($purchase_order_return_id, $options)->row();
                echo json_encode(array("success" => true, "id" => $return_info->id, "data" => $this->_make_material_row($return_info), 'message' => lang('record_saved')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
            }
        }
        else{
            echo json_encode(array("success" => false, 'message' => lang('material_has_return_error')));
        }
    }

    function delete_material() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));
        
        $id = $this->input->post('id');
        
        if ($this->Purchase_order_return_materials_model->delete($id)) {
            $this->Account_transactions_model->delete_purchase_return($id);
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    function view($id = 0){
        if($id){
            $view_data = get_purchase_return_making_data($id);

            if ($view_data) {
                $this->template->rander("purchase_returns/view", $view_data);
            } else {
                show_404();
            }
        }
    }

    private function _debit_all_return_materials($purchase_order_return_id){
        $return_materials = $this->Purchase_order_return_materials_model->get_details(array("purchase_order_return_id" => $purchase_order_return_id))->result();

        foreach($return_materials as $material){
            $transaction_data["deleted"] = "0";
            $this->Account_transactions_model->update_purchase_return($material->id, $transaction_data); 
        }
    }

    private function _credit_all_return_materials($purchase_order_return_id){
        $return_materials = $this->Purchase_order_return_materials_model->get_details(array("purchase_order_return_id" => $purchase_order_return_id))->result();

        foreach($return_materials as $return_material){
            $transaction_data["deleted"] = "1";
            $this->Account_transactions_model->update_purchase_return($return_material->id, $transaction_data); 
        }
    }

    function cancel_form(){
        $view_data["id"] = $this->input->post("id");
        $this->load->view('purchase_returns/cancel_form', $view_data);
    }

    function cancel(){
        $id = $this->input->post("id");
        $data = array(
            "status" => "cancelled",
            "cancelled_at" => get_my_local_time(),
            "cancelled_by" => $this->login_user->id
        );

        if($this->Purchase_order_returns_model->save($data, $id)){
            $this->_credit_all_return_materials($id);
            echo json_encode(array('success' => true, 'message' => lang("success")));
        }
        else{
            echo json_encode(array('success' => false, 'message' => lang('error_occurred')));
        }
    }

    function draft_form(){
        $view_data["id"] = $this->input->post("id");
        $this->load->view('purchase_returns/draft_form', $view_data);
    }

    function mark_as_draft(){
        $id = $this->input->post("id");
        $data = array(
            "status" => "draft",
            "cancelled_at" => NULL,
            "cancelled_by" => NULL
        );

        if($this->Purchase_order_returns_model->save($data, $id)){
            $this->_credit_all_return_materials($id);
            echo json_encode(array('success' => true, 'message' => lang("success")));
        }
        else{
            echo json_encode(array('success' => false, 'message' => lang('error_occurred')));
        }
    }

    function complete_form(){
        $view_data["id"] = $this->input->post("id");
        $this->load->view('purchase_returns/complete_form', $view_data);
    }

    function mark_as_completed(){
        $id = $this->input->post("id");
        $data = array(
            "status" => "completed",
            "cancelled_at" => NULL,
            "cancelled_by" => NULL
        );

        if($this->Purchase_order_returns_model->save($data, $id)){
            $this->_debit_all_return_materials($id);
            echo json_encode(array('success' => true, 'message' => lang("success")));
        }
        else{
            echo json_encode(array('success' => false, 'message' => lang('error_occurred')));
        }
    }

    function send_return_modal_form($purchase_return_id = 0) {
        if ($purchase_return_id) {
            $options = array("id" => $purchase_return_id);
            $purchase_return_info = $this->Purchase_order_returns_model->get_details($options)->row();
            $view_data['purchase_return_info'] = $purchase_return_info;

            $contacts_options = array("user_type" => "supplier", "vendor_id" => $purchase_return_info->vendor_id);
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

            $template_data = $this->get_send_return_template($purchase_return_id, 0, "", $purchase_return_info, $primary_contact_info);
            $view_data['message'] = get_array_value($template_data, "message");
            $view_data['subject'] = get_array_value($template_data, "subject");

            $this->load->view('purchase_returns/send_return_modal_form', $view_data);
        } else {
            show_404();
        }
    }

    function get_send_return_template($purchase_return_id = 0, $contact_id = 0, $return_type = "", $purchase_return_info = "", $contact_info = "") {

        if (!$purchase_return_info) {
            $options = array("id" => $purchase_return_id);
            $purchase_return_info = $this->Purchase_order_returns_model->get_details($options)->row();
        }

        if (!$contact_info) {
            $contact_info = $this->Users_model->get_one($contact_id);
        }

        $email_template = $this->Email_templates_model->get_final_template("send_return_request");

        $parser_data["R_ID"] = $purchase_return_info->id;
        $parser_data["CONTACT_FIRST_NAME"] = $contact_info->first_name;
        $parser_data["RETURN_URL"] = get_uri("mes/PurchaseReturns/preview/" . $purchase_return_info->id);
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

    function send_return() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $purchase_return_id = $this->input->post('id');

        $contact_id = $this->input->post('contact_id');
        $cc = $this->input->post('return_cc');

        $custom_bcc = $this->input->post('return_bcc');
        $subject = $this->input->post('subject');
        $message = decode_ajax_post_data($this->input->post('message'));

        $contact = $this->Users_model->get_one($contact_id);

        $purchase_return_data = get_purchase_return_making_data($purchase_return_id);
        $attachement_url = prepare_purchase_pdf($purchase_return_data, "send_email");

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
            if ($this->Purchase_order_returns_model->save($status_data, $purchase_return_id)) {
                echo json_encode(array('success' => true, 'message' => lang("return_sent_message"), "purchase_return_$purchase_return_id" => $purchase_return_id, "last_email_sent_date" => lang("last_email_sent_date").": ".$last_email_sent_date));
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

    function upload_file() {
        upload_file_to_temp();
    }

    function validate_returns_file() {
        return validate_post_file($this->input->post("file_name"));
    }

    function download_pdf($purchase_return_id = 0, $mode = "download") {
        if ($purchase_return_id) {
            $purchase_return_data = get_purchase_return_making_data($purchase_return_id);

            prepare_return_pdf($purchase_return_data, $mode);
        } else {
            show_404();
        }
    }

    function preview($purchase_return_id = 0, $show_close_preview = false) {
        if ($purchase_return_id) {
            $view_data = get_purchase_return_making_data($purchase_return_id);

            $view_data['return_preview'] = prepare_return_pdf($view_data, "html");

            //show a back button
            $view_data['show_close_preview'] = $show_close_preview && $this->login_user->user_type === "staff" ? true : false;

            $view_data['purchase_return_id'] = $purchase_return_id;
            $view_data['payment_methods'] = $this->Payment_methods_model->get_available_online_payment_methods();

            $this->load->library("paypal");
            $view_data['paypal_url'] = $this->paypal->get_paypal_url();

            $this->template->rander("purchase_returns/return_preview", $view_data);
        } else {
            show_404();
        }
    }

    function print_return($purchase_return_id = 0) {
        if ($purchase_return_id) {
            $view_data = get_purchase_return_making_data($purchase_return_id);

            $view_data['return_preview'] = prepare_return_pdf($view_data, "html");

            echo json_encode(array("success" => true, "print_view" => $this->load->view("purchase_returns/print_return", $view_data, true)));
        } else {
            echo json_encode(array("success" => false, lang('error_occurred')));
        }
    }
}
